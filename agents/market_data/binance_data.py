"""
BinanceMarketData – public Binance API (no key required).

Crypto pairs only.
"""

from __future__ import annotations

import logging
from datetime import datetime

import requests

from .base import BaseMarketData, OHLCV


class BinanceMarketData(BaseMarketData):
    """Market data from the public Binance REST API."""

    BASE_URL = "https://api.binance.com/api/v3"

    def __init__(self) -> None:
        self.logger = logging.getLogger(__name__)

    def get_price(self, symbol: str) -> float:
        try:
            resp = requests.get(
                f"{self.BASE_URL}/ticker/price",
                params={"symbol": symbol},
                timeout=10,
            )
            resp.raise_for_status()
            return float(resp.json().get("price", 0))
        except requests.RequestException as exc:
            self.logger.error("Binance price request failed for %s: %s", symbol, exc)
            return 0.0

    def get_historical(self, symbol: str, days: int = 30) -> list[OHLCV]:
        try:
            resp = requests.get(
                f"{self.BASE_URL}/klines",
                params={
                    "symbol": symbol,
                    "interval": "1d",
                    "limit": days,
                },
                timeout=15,
            )
            resp.raise_for_status()
            raw = resp.json()
        except requests.RequestException as exc:
            self.logger.error("Binance klines failed for %s: %s", symbol, exc)
            return []

        bars: list[OHLCV] = []
        for k in raw:
            # Binance kline: [open_time, O, H, L, C, V, close_time, ...]
            bars.append(
                OHLCV(
                    timestamp=datetime.fromtimestamp(k[0] / 1000),
                    open=float(k[1]),
                    high=float(k[2]),
                    low=float(k[3]),
                    close=float(k[4]),
                    volume=float(k[5]),
                )
            )
        return bars

    def search_symbol(self, query: str) -> list[dict[str, str]]:
        try:
            resp = requests.get(f"{self.BASE_URL}/exchangeInfo", timeout=15)
            resp.raise_for_status()
            symbols = resp.json().get("symbols", [])
        except requests.RequestException as exc:
            self.logger.error("Binance exchangeInfo failed: %s", exc)
            return []

        query_upper = query.upper()
        results: list[dict[str, str]] = []
        for s in symbols:
            sym = s.get("symbol", "")
            if query_upper in sym:
                results.append({
                    "symbol": sym,
                    "name": f"{s.get('baseAsset', '')}/{s.get('quoteAsset', '')}",
                    "status": s.get("status", ""),
                })
            if len(results) >= 20:
                break
        return results
