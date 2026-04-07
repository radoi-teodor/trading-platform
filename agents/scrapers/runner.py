"""
ScraperRunner – orchestrates scraping, dedup, sentiment scoring, and DB storage.

Loads scraper configurations from the database, runs each on its schedule,
performs keyword filtering, deduplication by URL, basic sentiment analysis,
and symbol tagging, then stores results in the ``scraped_articles`` table.
"""

from __future__ import annotations

import json
import logging
import re
import time
from datetime import datetime, timezone
from pathlib import Path
from typing import Any

from sqlalchemy import text
from sqlalchemy.orm import Session
from textblob import TextBlob  # type: ignore[import-untyped]

from .base import Article
from .html_scraper import HTMLScraper
from .rss_scraper import RSSScraper


# Load custom financial word dictionary
_DATA_DIR = Path(__file__).resolve().parent.parent / "data"
_FINANCIAL_WORDS: dict[str, list[str]] = {}
try:
    with open(_DATA_DIR / "financial_words.json", encoding="utf-8") as fh:
        _FINANCIAL_WORDS = json.load(fh)
except FileNotFoundError:
    pass

_POSITIVE_WORDS: set[str] = set(_FINANCIAL_WORDS.get("positive", []))
_NEGATIVE_WORDS: set[str] = set(_FINANCIAL_WORDS.get("negative", []))


class ScraperRunner:
    """Loads scraper configs from DB, runs them, analyses and stores articles."""

    def __init__(self, db_session: Session) -> None:
        self.db = db_session
        self.logger = logging.getLogger(__name__)
        self._scrapers: dict[str, HTMLScraper | RSSScraper] = {}

    # ── Public API ───────────────────────────────────────────────────────

    def run_all(self) -> int:
        """Run every scraper config that is due.  Return total new articles stored."""
        configs = self._load_configs()
        total_new = 0

        for cfg in configs:
            if not self._is_due(cfg):
                continue

            scraper = self._get_scraper(cfg)
            url = cfg.get("url", "")
            if not url:
                continue

            self.logger.info("Running scraper '%s' on %s", cfg.get("name"), url)

            try:
                raw = scraper.fetch(url)
                articles = scraper.parse(raw)
            except Exception as exc:
                self.logger.error("Scraper '%s' failed: %s", cfg.get("name"), exc)
                self._update_last_run(cfg["id"])
                continue

            keywords = cfg.get("keywords", [])
            symbols = cfg.get("symbols", [])

            for article in articles:
                # Keyword filter
                if keywords and not self._matches_keywords(article, keywords):
                    continue

                # Dedup by URL
                if self._url_exists(article.url):
                    continue

                # Symbol tagging
                article.symbols = self._tag_symbols(article, symbols)

                # Sentiment analysis
                article.sentiment_score = self._compute_sentiment(article)

                # Store
                self._store_article(article, cfg.get("id"))
                total_new += 1

            self._update_last_run(cfg["id"])

        self.logger.info("Scraper run complete – %d new articles", total_new)
        return total_new

    # ── Config loading ───────────────────────────────────────────────────

    def _load_configs(self) -> list[dict[str, Any]]:
        """Load active scraper configs from the ``scrapers`` table."""
        rows = self.db.execute(
            text(
                "SELECT id, user_id, name, type, url, selector_config, keyword_filters, "
                "associated_symbols, schedule, last_run_at "
                "FROM scrapers WHERE is_active = 1"
            )
        ).fetchall()

        configs: list[dict[str, Any]] = []
        for r in rows:
            selectors = {}
            if r[5]:
                try:
                    selectors = json.loads(r[5]) if isinstance(r[5], str) else r[5]
                except (json.JSONDecodeError, TypeError):
                    pass

            keywords_raw = r[6]
            keywords: list[str] = []
            if keywords_raw:
                try:
                    keywords = json.loads(keywords_raw) if isinstance(keywords_raw, str) else keywords_raw
                except (json.JSONDecodeError, TypeError):
                    pass

            symbols_raw = r[7]
            symbols_list: list[str] = []
            if symbols_raw:
                try:
                    symbols_list = json.loads(symbols_raw) if isinstance(symbols_raw, str) else symbols_raw
                except (json.JSONDecodeError, TypeError):
                    pass

            # Parse schedule string (e.g. "60" or "every 60 minutes") to minutes
            schedule_raw = r[8] or "60"
            try:
                schedule_minutes = int(re.sub(r"[^\d]", "", schedule_raw) or "60")
            except (ValueError, TypeError):
                schedule_minutes = 60

            configs.append({
                "id": r[0],
                "user_id": r[1],
                "name": r[2],
                "type": r[3],  # "html" or "rss"
                "url": r[4],
                "selectors": selectors,
                "keywords": keywords,
                "symbols": symbols_list,
                "schedule_minutes": schedule_minutes,
                "last_run_at": r[9],
            })
        return configs

    def _is_due(self, cfg: dict[str, Any]) -> bool:
        """Check if enough time has passed since last run."""
        last_run = cfg.get("last_run_at")
        if last_run is None:
            return True
        if isinstance(last_run, str):
            try:
                last_run = datetime.fromisoformat(last_run)
            except ValueError:
                return True
        if not isinstance(last_run, datetime):
            return True
        elapsed_min = (datetime.now(timezone.utc) - last_run.replace(tzinfo=timezone.utc)).total_seconds() / 60
        return elapsed_min >= cfg.get("schedule_minutes", 60)

    def _update_last_run(self, config_id: int) -> None:
        self.db.execute(
            text("UPDATE scrapers SET last_run_at = :now WHERE id = :id"),
            {"now": datetime.now(timezone.utc), "id": config_id},
        )
        self.db.commit()

    # ── Scraper factory ──────────────────────────────────────────────────

    def _get_scraper(self, cfg: dict[str, Any]) -> HTMLScraper | RSSScraper:
        """Return a scraper instance for the config type."""
        key = f"{cfg['type']}_{cfg['id']}"
        if key not in self._scrapers:
            if cfg["type"] == "rss":
                self._scrapers[key] = RSSScraper(source_name=cfg.get("name", "rss"))
            else:
                sel = cfg.get("selectors", {})
                self._scrapers[key] = HTMLScraper(
                    article_selector=sel.get("article", "article"),
                    title_selector=sel.get("title", "h2"),
                    content_selector=sel.get("content", "p"),
                    date_selector=sel.get("date", "time"),
                    link_selector=sel.get("link", "a"),
                    source_name=cfg.get("name", "web"),
                )
        return self._scrapers[key]

    # ── Filtering & tagging ──────────────────────────────────────────────

    @staticmethod
    def _matches_keywords(article: Article, keywords: list[str]) -> bool:
        """Return True if any keyword appears in the title or content."""
        text_lower = (article.title + " " + article.content).lower()
        return any(kw.lower() in text_lower for kw in keywords)

    def _url_exists(self, url: str) -> bool:
        """Check if an article with this URL is already stored."""
        if not url:
            return False
        row = self.db.execute(
            text("SELECT 1 FROM scraped_articles WHERE url = :url LIMIT 1"),
            {"url": url},
        ).fetchone()
        return row is not None

    @staticmethod
    def _tag_symbols(article: Article, candidate_symbols: list[str]) -> list[str]:
        """Find which candidate symbols are mentioned in the article text."""
        combined = article.title + " " + article.content
        found: list[str] = []
        for sym in candidate_symbols:
            # Look for $SYMBOL or standalone SYMBOL (word boundary)
            pattern = rf"(?:\$|(?<!\w)){re.escape(sym)}(?!\w)"
            if re.search(pattern, combined, re.IGNORECASE):
                found.append(sym.upper())
        return found

    @staticmethod
    def _compute_sentiment(article: Article) -> float:
        """Compute sentiment score in [-1.0, 1.0].

        Blends TextBlob polarity with a custom financial-word score.
        """
        text_combined = article.title + " " + article.content
        words = text_combined.lower().split()

        # TextBlob polarity
        try:
            tb_score = TextBlob(text_combined).sentiment.polarity
        except Exception:
            tb_score = 0.0

        # Financial word scoring
        pos_count = sum(1 for w in words if w in _POSITIVE_WORDS)
        neg_count = sum(1 for w in words if w in _NEGATIVE_WORDS)
        total = pos_count + neg_count
        fin_score = (pos_count - neg_count) / total if total else 0.0

        # Blend: 60% financial, 40% TextBlob
        blended = 0.6 * fin_score + 0.4 * tb_score
        return max(-1.0, min(1.0, blended))

    # ── DB storage ───────────────────────────────────────────────────────

    def _store_article(self, article: Article, scraper_id: int | None) -> None:
        """Insert an article into the ``scraped_articles`` table."""
        now = datetime.now(timezone.utc)

        # Derive sentiment label from score
        score = article.sentiment_score or 0.0
        if score > 0.1:
            sentiment_label = "bullish"
        elif score < -0.1:
            sentiment_label = "bearish"
        else:
            sentiment_label = "neutral"

        self.db.execute(
            text(
                "INSERT INTO scraped_articles "
                "(scraper_id, title, content, url, source, published_at, "
                " symbols, sentiment_score, sentiment_label, fed_to_claude, "
                " created_at, updated_at) "
                "VALUES (:sid, :title, :content, :url, :source, :pub, "
                " :symbols, :sentiment, :label, 0, :now, :now)"
            ),
            {
                "sid": scraper_id,
                "title": article.title[:500],
                "content": article.content[:5000],
                "url": article.url[:1000],
                "source": article.source[:100],
                "pub": article.published_at or now,
                "symbols": json.dumps(article.symbols),
                "sentiment": article.sentiment_score,
                "label": sentiment_label,
                "now": now,
            },
        )
        self.db.commit()
