"""
YFinanceData – market data via the ``yfinance`` library.

No API key required.  Good for historical data and basic quotes.
"""

from __future__ import annotations

import logging
from datetime import datetime, timedelta

import yfinance as yf  # type: ignore[import-untyped]

from .base import BaseMarketData, OHLCV


class YFinanceData(BaseMarketData):
    """Market data backed by Yahoo Finance (via yfinance)."""

    def __init__(self) -> None:
        self.logger = logging.getLogger(__name__)

    def get_price(self, symbol: str) -> float:
        ticker = yf.Ticker(symbol)
        info = ticker.info
        price = info.get("regularMarketPrice") or info.get("currentPrice") or info.get("previousClose", 0)
        return float(price)

    def get_historical(self, symbol: str, days: int = 30) -> list[OHLCV]:
        ticker = yf.Ticker(symbol)
        end = datetime.now()
        start = end - timedelta(days=int(days * 1.5))  # extra buffer for non-trading days
        df = ticker.history(start=start.strftime("%Y-%m-%d"), end=end.strftime("%Y-%m-%d"))

        bars: list[OHLCV] = []
        for idx, row in df.tail(days).iterrows():
            bars.append(
                OHLCV(
                    timestamp=idx.to_pydatetime(),  # type: ignore[union-attr]
                    open=float(row["Open"]),
                    high=float(row["High"]),
                    low=float(row["Low"]),
                    close=float(row["Close"]),
                    volume=float(row["Volume"]),
                )
            )
        return bars

    def search_symbol(self, query: str) -> list[dict[str, str]]:
        try:
            tickers = yf.Tickers(query)
            results: list[dict[str, str]] = []
            for sym in tickers.tickers:
                info = tickers.tickers[sym].info
                results.append({
                    "symbol": sym,
                    "name": info.get("longName", info.get("shortName", sym)),
                })
            return results
        except Exception as exc:
            self.logger.warning("yfinance search failed: %s", exc)
            return [{"symbol": query, "name": query}]
