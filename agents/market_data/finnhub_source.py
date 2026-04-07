"""
FinnhubData – market data from the Finnhub REST API.

Free tier: 60 calls/minute.
"""

from __future__ import annotations

import logging
import time
from datetime import datetime, timedelta

import requests

from .base import BaseMarketData, OHLCV


class FinnhubData(BaseMarketData):
    """Market data backed by Finnhub.io."""

    BASE_URL = "https://finnhub.io/api/v1"
    RATE_LIMIT_INTERVAL = 1.0  # 60 calls/min → 1 s between calls

    def __init__(self, api_key: str) -> None:
        self.api_key = api_key
        self.logger = logging.getLogger(__name__)
        self._last_call: float = 0.0

    def get_price(self, symbol: str) -> float:
        data = self._request("/quote", s=symbol)
        return float(data.get("c", 0))  # c = current price

    def get_historical(self, symbol: str, days: int = 30) -> list[OHLCV]:
        end = int(datetime.now().timestamp())
        start = int((datetime.now() - timedelta(days=days)).timestamp())
        data = self._request(
            "/stock/candle",
            symbol=symbol,
            resolution="D",
            **{"from": str(start), "to": str(end)},
        )
        if data.get("s") != "ok":
            self.logger.warning("Finnhub candle request returned status: %s", data.get("s"))
            return []

        bars: list[OHLCV] = []
        timestamps = data.get("t", [])
        opens = data.get("o", [])
        highs = data.get("h", [])
        lows = data.get("l", [])
        closes = data.get("c", [])
        volumes = data.get("v", [])

        for i in range(len(timestamps)):
            bars.append(
                OHLCV(
                    timestamp=datetime.fromtimestamp(timestamps[i]),
                    open=float(opens[i]),
                    high=float(highs[i]),
                    low=float(lows[i]),
                    close=float(closes[i]),
                    volume=float(volumes[i]),
                )
            )
        return bars

    def search_symbol(self, query: str) -> list[dict[str, str]]:
        data = self._request("/search", q=query)
        results: list[dict[str, str]] = []
        for item in data.get("result", []):
            results.append({
                "symbol": item.get("symbol", ""),
                "name": item.get("description", ""),
                "type": item.get("type", ""),
            })
        return results

    def _request(self, path: str, **params: str) -> dict:
        self._rate_limit()
        params["token"] = self.api_key
        try:
            resp = requests.get(f"{self.BASE_URL}{path}", params=params, timeout=15)
            resp.raise_for_status()
            return resp.json()
        except requests.RequestException as exc:
            self.logger.error("Finnhub request %s failed: %s", path, exc)
            return {}

    def _rate_limit(self) -> None:
        elapsed = time.time() - self._last_call
        if elapsed < self.RATE_LIMIT_INTERVAL:
            time.sleep(self.RATE_LIMIT_INTERVAL - elapsed)
        self._last_call = time.time()
