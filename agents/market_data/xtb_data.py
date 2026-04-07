"""
XTBMarketData – market data via the XTB xStation5 socket API.

Reuses the XTB socket connection from the broker module.
"""

from __future__ import annotations

import logging
from datetime import datetime, timedelta
from typing import Any

from .base import BaseMarketData, OHLCV


class XTBMarketData(BaseMarketData):
    """Market data sourced from XTB's streaming API."""

    def __init__(self, xtb_broker: Any) -> None:
        """Accepts an already-connected ``XTBBroker`` instance."""
        self.broker = xtb_broker
        self.logger = logging.getLogger(__name__)

    def get_price(self, symbol: str) -> float:
        return self.broker.get_ticker_price(symbol)

    def get_historical(self, symbol: str, days: int = 30) -> list[OHLCV]:
        end = int(datetime.now().timestamp() * 1000)
        start = int((datetime.now() - timedelta(days=days)).timestamp() * 1000)

        resp = self.broker._send_command("getChartRangeRequest", {
            "info": {
                "symbol": symbol,
                "period": 1440,  # 1-day candles
                "start": start,
                "end": end,
            },
        })

        rate_infos = resp.get("returnData", {}).get("rateInfos", [])
        digits = resp.get("returnData", {}).get("digits", 0)
        multiplier = 10 ** digits if digits else 1

        bars: list[OHLCV] = []
        for r in rate_infos:
            o = float(r.get("open", 0)) / multiplier
            bars.append(
                OHLCV(
                    timestamp=datetime.fromtimestamp(r.get("ctm", 0) / 1000),
                    open=o,
                    high=o + float(r.get("high", 0)) / multiplier,
                    low=o + float(r.get("low", 0)) / multiplier,
                    close=o + float(r.get("close", 0)) / multiplier,
                    volume=float(r.get("vol", 0)),
                )
            )
        return bars

    def search_symbol(self, query: str) -> list[dict[str, str]]:
        resp = self.broker._send_command("getAllSymbols")
        symbols = resp.get("returnData", [])
        query_lower = query.lower()
        results: list[dict[str, str]] = []
        for s in symbols:
            sym = s.get("symbol", "")
            desc = s.get("description", "")
            if query_lower in sym.lower() or query_lower in desc.lower():
                results.append({
                    "symbol": sym,
                    "name": desc,
                    "category": s.get("categoryName", ""),
                })
            if len(results) >= 20:
                break
        return results
