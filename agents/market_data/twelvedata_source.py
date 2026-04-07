"""
TwelveDataSource – market data from the Twelve Data REST API.

Free tier: 8 calls/minute.
"""

from __future__ import annotations

import logging
import time

import requests

from .base import BaseMarketData, OHLCV
from datetime import datetime


class TwelveDataSource(BaseMarketData):
    """Market data backed by twelvedata.com."""

    BASE_URL = "https://api.twelvedata.com"
    RATE_LIMIT_INTERVAL = 7.5  # 8 calls/min

    def __init__(self, api_key: str) -> None:
        self.api_key = api_key
        self.logger = logging.getLogger(__name__)
        self._last_call: float = 0.0

    def get_price(self, symbol: str) -> float:
        data = self._request("/price", symbol=symbol)
        return float(data.get("price", 0))

    def get_historical(self, symbol: str, days: int = 30) -> list[OHLCV]:
        data = self._request(
            "/time_series",
            symbol=symbol,
            interval="1day",
            outputsize=str(days),
        )
        values = data.get("values", [])
        bars: list[OHLCV] = []
        for v in values:
            bars.append(
                OHLCV(
                    timestamp=datetime.strptime(v["datetime"], "%Y-%m-%d"),
                    open=float(v["open"]),
                    high=float(v["high"]),
                    low=float(v["low"]),
                    close=float(v["close"]),
                    volume=float(v["volume"]),
                )
            )
        return bars

    def search_symbol(self, query: str) -> list[dict[str, str]]:
        data = self._request("/symbol_search", symbol=query)
        results: list[dict[str, str]] = []
        for item in data.get("data", []):
            results.append({
                "symbol": item.get("symbol", ""),
                "name": item.get("instrument_name", ""),
                "type": item.get("instrument_type", ""),
                "exchange": item.get("exchange", ""),
            })
        return results

    def _request(self, path: str, **params: str) -> dict:
        self._rate_limit()
        params["apikey"] = self.api_key
        try:
            resp = requests.get(f"{self.BASE_URL}{path}", params=params, timeout=15)
            resp.raise_for_status()
            return resp.json()
        except requests.RequestException as exc:
            self.logger.error("Twelve Data request %s failed: %s", path, exc)
            return {}

    def _rate_limit(self) -> None:
        elapsed = time.time() - self._last_call
        if elapsed < self.RATE_LIMIT_INTERVAL:
            time.sleep(self.RATE_LIMIT_INTERVAL - elapsed)
        self._last_call = time.time()
