"""
HTMLScraper – scrapes news articles from HTML pages using BeautifulSoup.

Configurable CSS selectors allow it to work with many different sites.
"""

from __future__ import annotations

import logging
from datetime import datetime
from typing import Any

import requests
from bs4 import BeautifulSoup  # type: ignore[import-untyped]

from .base import Article, BaseScraper


class HTMLScraper(BaseScraper):
    """HTML page scraper with configurable CSS selectors."""

    def __init__(
        self,
        *,
        article_selector: str = "article",
        title_selector: str = "h2",
        content_selector: str = "p",
        date_selector: str = "time",
        link_selector: str = "a",
        source_name: str = "web",
    ) -> None:
        self.article_selector = article_selector
        self.title_selector = title_selector
        self.content_selector = content_selector
        self.date_selector = date_selector
        self.link_selector = link_selector
        self.source_name = source_name
        self.logger = logging.getLogger(__name__)

    def fetch(self, url: str) -> list[dict[str, Any]]:
        """Fetch HTML from *url*, extract article blocks, return raw dicts."""
        try:
            resp = requests.get(
                url,
                headers={"User-Agent": "TradingPlatform/1.0"},
                timeout=15,
            )
            resp.raise_for_status()
        except requests.RequestException as exc:
            self.logger.error("HTML fetch failed for %s: %s", url, exc)
            return []

        soup = BeautifulSoup(resp.text, "html.parser")
        blocks = soup.select(self.article_selector)
        items: list[dict[str, Any]] = []

        for block in blocks:
            title_el = block.select_one(self.title_selector)
            content_els = block.select(self.content_selector)
            date_el = block.select_one(self.date_selector)
            link_el = block.select_one(self.link_selector)

            title = title_el.get_text(strip=True) if title_el else ""
            content = " ".join(el.get_text(strip=True) for el in content_els)
            date_str = date_el.get("datetime", date_el.get_text(strip=True)) if date_el else ""
            link = link_el.get("href", "") if link_el else ""

            if not title:
                continue

            # Make relative URLs absolute
            if link and not link.startswith("http"):
                from urllib.parse import urljoin
                link = urljoin(url, link)

            items.append({
                "title": title,
                "content": content,
                "url": link or url,
                "date_str": date_str,
            })

        self.logger.info("Fetched %d article blocks from %s", len(items), url)
        return items

    def parse(self, raw_data: list[dict[str, Any]]) -> list[Article]:
        """Convert raw dicts to ``Article`` objects."""
        articles: list[Article] = []
        for item in raw_data:
            published: datetime | None = None
            ds = item.get("date_str", "")
            if ds:
                for fmt in ("%Y-%m-%dT%H:%M:%S", "%Y-%m-%d %H:%M:%S", "%Y-%m-%d", "%B %d, %Y"):
                    try:
                        published = datetime.strptime(ds[:len(fmt) + 5], fmt)
                        break
                    except (ValueError, IndexError):
                        continue

            articles.append(
                Article(
                    title=item.get("title", ""),
                    content=item.get("content", ""),
                    url=item.get("url", ""),
                    source=self.source_name,
                    published_at=published,
                )
            )
        return articles
