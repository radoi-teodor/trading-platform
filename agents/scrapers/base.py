"""
BaseScraper – abstract interface for news/data scrapers.
"""

from __future__ import annotations

from abc import ABC, abstractmethod
from dataclasses import dataclass, field
from datetime import datetime


@dataclass
class Article:
    """A single scraped article."""

    title: str
    content: str
    url: str
    source: str
    published_at: datetime | None = None
    symbols: list[str] = field(default_factory=list)
    sentiment_score: float | None = None


class BaseScraper(ABC):
    """Abstract scraper interface."""

    @abstractmethod
    def fetch(self, url: str) -> list[dict]:
        """Fetch raw data from *url* and return a list of raw item dicts."""

    @abstractmethod
    def parse(self, raw_data: list[dict]) -> list[Article]:
        """Parse raw item dicts into ``Article`` instances."""
