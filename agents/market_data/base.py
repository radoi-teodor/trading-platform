"""
BaseMarketData – abstract interface for all market data providers.
"""

from __future__ import annotations

from abc import ABC, abstractmethod
from dataclasses import dataclass
from datetime import datetime


@dataclass
class OHLCV:
    """Single candlestick bar."""

    timestamp: datetime
    open: float
    high: float
    low: float
    close: float
    volume: float


class BaseMarketData(ABC):
    """Abstract market data source."""

    @abstractmethod
    def get_price(self, symbol: str) -> float:
        """Return the latest price for *symbol*."""

    @abstractmethod
    def get_historical(self, symbol: str, days: int = 30) -> list[OHLCV]:
        """Return daily OHLCV bars for the past *days* trading days."""

    @abstractmethod
    def search_symbol(self, query: str) -> list[dict[str, str]]:
        """Search for symbols matching *query*.

        Returns a list of dicts with at least ``symbol`` and ``name`` keys.
        """
