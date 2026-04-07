"""
BinanceBroker – Binance spot & futures integration via ``python-binance``.

Supports live and testnet modes.
"""

from __future__ import annotations

import logging
from datetime import datetime, timezone
from typing import Any

from .base import AccountInfo, BaseBroker, OrderResult, Position


class BinanceBroker(BaseBroker):
    """Broker implementation backed by the Binance exchange."""

    def __init__(
        self,
        api_key: str,
        secret_key: str,
        testnet: bool = True,
    ) -> None:
        self.api_key = api_key
        self.secret_key = secret_key
        self.testnet = testnet
        self.client: Any = None
        self.logger = logging.getLogger(__name__)

    def connect(self) -> None:
        from binance.client import Client  # type: ignore[import-untyped]

        if self.testnet:
            # Binance Demo uses https://demo-api.binance.com
            self.client = Client(self.api_key, self.secret_key)
            self.client.API_URL = "https://demo-api.binance.com/api"
            self.logger.info("Binance connected in DEMO mode (demo-api.binance.com)")
        else:
            self.client = Client(self.api_key, self.secret_key)
            self.logger.info("Binance connected in LIVE mode")

        try:
            # Verify connection by fetching account
            account = self.client.get_account()
            self.logger.info(
                "Binance account verified — can_trade: %s",
                account.get("canTrade", False),
            )
        except Exception as exc:
            self.logger.warning("Binance account check failed: %s", exc)

    def disconnect(self) -> None:
        if self.client:
            self.client.close_connection()
        self.client = None
        self.logger.info("Binance disconnected")

    def get_balance(self) -> float:
        assert self.client is not None
        account = self.client.get_account()
        for b in account.get("balances", []):
            if b["asset"] == "USDT":
                return float(b["free"])
        return 0.0

    def get_positions(self) -> list[Position]:
        assert self.client is not None
        account = self.client.get_account()
        positions: list[Position] = []
        for b in account.get("balances", []):
            qty = float(b["free"]) + float(b["locked"])
            if qty > 0 and b["asset"] not in ("USDT", "USD", "BUSD"):
                symbol = b["asset"] + "USDT"
                try:
                    ticker = self.client.get_symbol_ticker(symbol=symbol)
                    price = float(ticker["price"])
                except Exception:
                    price = 0.0
                positions.append(
                    Position(
                        symbol=symbol,
                        quantity=qty,
                        avg_cost=0.0,  # Binance doesn't expose avg cost directly
                        current_price=price,
                        unrealized_pnl=0.0,
                        side="long",
                    )
                )
        return positions

    def place_order(
        self,
        symbol: str,
        side: str,
        quantity: float,
        order_type: str = "market",
        price: float | None = None,
    ) -> OrderResult:
        assert self.client is not None
        from binance.enums import (  # type: ignore[import-untyped]
            ORDER_TYPE_LIMIT,
            ORDER_TYPE_MARKET,
            ORDER_TYPE_STOP_LOSS,
            ORDER_TYPE_STOP_LOSS_LIMIT,
            SIDE_BUY,
            SIDE_SELL,
            TIME_IN_FORCE_GTC,
        )

        type_map = {
            "market": ORDER_TYPE_MARKET,
            "limit": ORDER_TYPE_LIMIT,
            "stop": ORDER_TYPE_STOP_LOSS,
            "stop_limit": ORDER_TYPE_STOP_LOSS_LIMIT,
        }
        side_map = {"buy": SIDE_BUY, "sell": SIDE_SELL}

        params: dict[str, Any] = {
            "symbol": symbol,
            "side": side_map.get(side, side.upper()),
            "type": type_map.get(order_type, order_type.upper()),
            "quantity": quantity,
        }

        if order_type == "limit" and price is not None:
            params["price"] = str(price)
            params["timeInForce"] = TIME_IN_FORCE_GTC
        elif order_type in ("stop", "stop_limit") and price is not None:
            params["stopPrice"] = str(price)
            if order_type == "stop_limit":
                params["price"] = str(price)
                params["timeInForce"] = TIME_IN_FORCE_GTC

        result = self.client.create_order(**params)
        self.logger.info("Binance order placed: %s", result.get("orderId"))
        return self._convert_order(result)

    def cancel_order(self, order_id: str) -> bool:
        assert self.client is not None
        try:
            # order_id format: "SYMBOL:ORDER_ID"
            parts = order_id.split(":", 1)
            if len(parts) == 2:
                self.client.cancel_order(symbol=parts[0], orderId=int(parts[1]))
            else:
                self.logger.warning("Cannot parse order_id '%s', expected SYMBOL:ID", order_id)
                return False
            return True
        except Exception as exc:
            self.logger.warning("Failed to cancel Binance order %s: %s", order_id, exc)
            return False

    def get_order_status(self, order_id: str) -> OrderResult:
        assert self.client is not None
        parts = order_id.split(":", 1)
        if len(parts) != 2:
            raise ValueError(f"Invalid order_id format '{order_id}', expected SYMBOL:ID")
        result = self.client.get_order(symbol=parts[0], orderId=int(parts[1]))
        return self._convert_order(result)

    def get_ticker_price(self, symbol: str) -> float:
        assert self.client is not None
        ticker = self.client.get_symbol_ticker(symbol=symbol)
        return float(ticker["price"])

    def get_account_info(self) -> AccountInfo:
        assert self.client is not None
        account = self.client.get_account()
        usdt = 0.0
        total = 0.0
        for b in account.get("balances", []):
            free = float(b["free"])
            locked = float(b["locked"])
            if b["asset"] == "USDT":
                usdt = free
                total += free + locked
            elif (free + locked) > 0:
                try:
                    ticker = self.client.get_symbol_ticker(symbol=b["asset"] + "USDT")
                    total += (free + locked) * float(ticker["price"])
                except Exception:
                    pass
        return AccountInfo(equity=total, cash=usdt, buying_power=usdt, currency="USDT")

    # ── Binance-specific extras ──────────────────────────────────────────

    def get_futures_positions(self) -> list[Position]:
        """Return positions from Binance futures account."""
        assert self.client is not None
        positions: list[Position] = []
        for p in self.client.futures_position_information():
            qty = float(p.get("positionAmt", 0))
            if qty != 0:
                entry = float(p.get("entryPrice", 0))
                mark = float(p.get("markPrice", 0))
                pnl = float(p.get("unRealizedProfit", 0))
                positions.append(
                    Position(
                        symbol=p["symbol"],
                        quantity=abs(qty),
                        avg_cost=entry,
                        current_price=mark,
                        unrealized_pnl=pnl,
                        side="long" if qty > 0 else "short",
                    )
                )
        return positions

    # ── Internal ─────────────────────────────────────────────────────────

    @staticmethod
    def _convert_order(raw: dict[str, Any]) -> OrderResult:
        status_map = {
            "NEW": "pending",
            "PARTIALLY_FILLED": "pending",
            "FILLED": "filled",
            "CANCELED": "cancelled",
            "REJECTED": "rejected",
            "EXPIRED": "cancelled",
        }
        raw_status = raw.get("status", "NEW")
        fills = raw.get("fills", [])
        fill_price: float | None = None
        if fills:
            total_cost = sum(float(f["price"]) * float(f["qty"]) for f in fills)
            total_qty = sum(float(f["qty"]) for f in fills)
            fill_price = total_cost / total_qty if total_qty else None

        order_id_str = f"{raw.get('symbol', '')}:{raw.get('orderId', '')}"

        return OrderResult(
            order_id=order_id_str,
            symbol=raw.get("symbol", ""),
            side=raw.get("side", "").lower(),
            quantity=float(raw.get("origQty", 0)),
            order_type=raw.get("type", "").lower(),
            status=status_map.get(raw_status, raw_status.lower()),
            fill_price=fill_price,
            filled_quantity=float(raw.get("executedQty", 0)),
            submitted_at=datetime.fromtimestamp(
                raw.get("transactTime", 0) / 1000, tz=timezone.utc
            ) if raw.get("transactTime") else None,
            filled_at=None,
            raw=raw,
        )
