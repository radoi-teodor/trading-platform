"""
RSSScraper – scrapes news from RSS / Atom feeds using ``feedparser``.
"""

from __future__ import annotations

import logging
from datetime import datetime
from time import mktime
from typing import Any

import feedparser  # type: ignore[import-untyped]

from .base import Article, BaseScraper


class RSSScraper(BaseScraper):
    """RSS / Atom feed scraper."""

    def __init__(self, source_name: str = "rss") -> None:
        self.source_name = source_name
        self.logger = logging.getLogger(__name__)

    def fetch(self, url: str) -> list[dict[str, Any]]:
        """Parse an RSS/Atom feed at *url* and return raw entry dicts."""
        feed = feedparser.parse(url)
        if feed.bozo and not feed.entries:
            self.logger.warning("Feed parse error for %s: %s", url, feed.bozo_exception)
            return []

        items: list[dict[str, Any]] = []
        for entry in feed.entries:
            content = ""
            if hasattr(entry, "summary"):
                content = entry.summary
            elif hasattr(entry, "content"):
                content = entry.content[0].get("value", "") if entry.content else ""

            items.append({
                "title": getattr(entry, "title", ""),
                "content": content,
                "url": getattr(entry, "link", ""),
                "published_parsed": getattr(entry, "published_parsed", None),
            })

        self.logger.info("Fetched %d entries from RSS %s", len(items), url)
        return items

    def parse(self, raw_data: list[dict[str, Any]]) -> list[Article]:
        articles: list[Article] = []
        for item in raw_data:
            published: datetime | None = None
            pp = item.get("published_parsed")
            if pp:
                try:
                    published = datetime.fromtimestamp(mktime(pp))
                except (TypeError, ValueError, OverflowError):
                    pass

            # Strip HTML tags from content
            content = item.get("content", "")
            try:
                from bs4 import BeautifulSoup  # type: ignore[import-untyped]
                content = BeautifulSoup(content, "html.parser").get_text(separator=" ", strip=True)
            except ImportError:
                pass

            articles.append(
                Article(
                    title=item.get("title", ""),
                    content=content,
                    url=item.get("url", ""),
                    source=self.source_name,
                    published_at=published,
                )
            )
        return articles
