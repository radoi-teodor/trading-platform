"""
AlphaVantageData – market data from the Alpha Vantage REST API.

Free tier allows 5 requests per minute.
"""

from __future__ import annotations

import logging
import time
from datetime import datetime

import requests

from .base import BaseMarketData, OHLCV


class AlphaVantageData(BaseMarketData):
    """Alpha Vantage REST API market data source."""

    BASE_URL = "https://www.alphavantage.co/query"
    RATE_LIMIT_INTERVAL = 12.0  # seconds between calls (5/min)

    def __init__(self, api_key: str) -> None:
        self.api_key = api_key
        self.logger = logging.getLogger(__name__)
        self._last_call: float = 0.0

    def get_price(self, symbol: str) -> float:
        data = self._request("GLOBAL_QUOTE", symbol=symbol)
        quote = data.get("Global Quote", {})
        price = quote.get("05. price", "0")
        return float(price)

    def get_historical(self, symbol: str, days: int = 30) -> list[OHLCV]:
        data = self._request(
            "TIME_SERIES_DAILY",
            symbol=symbol,
            outputsize="compact" if days <= 100 else "full",
        )
        series = data.get("Time Series (Daily)", {})
        bars: list[OHLCV] = []
        for date_str, values in sorted(series.items(), reverse=True)[:days]:
            bars.append(
                OHLCV(
                    timestamp=datetime.strptime(date_str, "%Y-%m-%d"),
                    open=float(values["1. open"]),
                    high=float(values["2. high"]),
                    low=float(values["3. low"]),
                    close=float(values["4. close"]),
                    volume=float(values["5. volume"]),
                )
            )
        return bars

    def search_symbol(self, query: str) -> list[dict[str, str]]:
        data = self._request("SYMBOL_SEARCH", keywords=query)
        results: list[dict[str, str]] = []
        for match in data.get("bestMatches", []):
            results.append({
                "symbol": match.get("1. symbol", ""),
                "name": match.get("2. name", ""),
                "type": match.get("3. type", ""),
                "region": match.get("4. region", ""),
            })
        return results

    # ── Internal ─────────────────────────────────────────────────────────

    def _request(self, function: str, **params: str) -> dict:
        """Make a rate-limited GET request to Alpha Vantage."""
        self._rate_limit()
        params_full = {"function": function, "apikey": self.api_key, **params}
        try:
            resp = requests.get(self.BASE_URL, params=params_full, timeout=30)
            resp.raise_for_status()
            return resp.json()
        except requests.RequestException as exc:
            self.logger.error("Alpha Vantage request failed: %s", exc)
            return {}

    def _rate_limit(self) -> None:
        """Enforce the 5-calls-per-minute rate limit."""
        elapsed = time.time() - self._last_call
        if elapsed < self.RATE_LIMIT_INTERVAL:
            time.sleep(self.RATE_LIMIT_INTERVAL - elapsed)
        self._last_call = time.time()
