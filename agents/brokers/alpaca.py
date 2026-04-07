"""
AlpacaBroker – Alpaca Markets REST API integration.

Uses the ``alpaca-trade-api`` library for authentication and order management.
"""

from __future__ import annotations

import logging
from datetime import datetime, timezone

from .base import AccountInfo, BaseBroker, OrderResult, Position


class AlpacaBroker(BaseBroker):
    """Broker implementation backed by Alpaca Markets."""

    def __init__(self, api_key: str, secret_key: str, base_url: str) -> None:
        self.api_key = api_key
        self.secret_key = secret_key
        self.base_url = base_url
        self.api = None  # set in connect()
        self.logger = logging.getLogger(__name__)

    def connect(self) -> None:
        import alpaca_trade_api as tradeapi  # type: ignore[import-untyped]

        self.api = tradeapi.REST(
            key_id=self.api_key,
            secret_key=self.secret_key,
            base_url=self.base_url,
        )
        account = self.api.get_account()
        self.logger.info(
            "Alpaca connected – account %s  status=%s  equity=$%s",
            account.id, account.status, account.equity,
        )

    def disconnect(self) -> None:
        self.api = None
        self.logger.info("Alpaca disconnected")

    def get_balance(self) -> float:
        assert self.api is not None
        account = self.api.get_account()
        return float(account.cash)

    def get_positions(self) -> list[Position]:
        assert self.api is not None
        raw_positions = self.api.list_positions()
        result: list[Position] = []
        for p in raw_positions:
            result.append(
                Position(
                    symbol=p.symbol,
                    quantity=float(p.qty),
                    avg_cost=float(p.avg_entry_price),
                    current_price=float(p.current_price),
                    unrealized_pnl=float(p.unrealized_pl),
                    side=p.side,
                )
            )
        return result

    def place_order(
        self,
        symbol: str,
        side: str,
        quantity: float,
        order_type: str = "market",
        price: float | None = None,
    ) -> OrderResult:
        assert self.api is not None

        kwargs: dict = {
            "symbol": symbol,
            "qty": quantity,
            "side": side,
            "type": order_type,
            "time_in_force": "day",
        }
        if order_type == "limit" and price is not None:
            kwargs["limit_price"] = price
        elif order_type == "stop" and price is not None:
            kwargs["stop_price"] = price
        elif order_type == "stop_limit" and price is not None:
            kwargs["stop_price"] = price
            kwargs["limit_price"] = price

        order = self.api.submit_order(**kwargs)

        self.logger.info("Alpaca order submitted: %s %s %s %s", side, quantity, symbol, order.id)

        return self._convert_order(order)

    def cancel_order(self, order_id: str) -> bool:
        assert self.api is not None
        try:
            self.api.cancel_order(order_id)
            self.logger.info("Alpaca order %s cancelled", order_id)
            return True
        except Exception as exc:
            self.logger.warning("Failed to cancel Alpaca order %s: %s", order_id, exc)
            return False

    def get_order_status(self, order_id: str) -> OrderResult:
        assert self.api is not None
        order = self.api.get_order(order_id)
        return self._convert_order(order)

    def get_ticker_price(self, symbol: str) -> float:
        assert self.api is not None
        trade = self.api.get_latest_trade(symbol)
        return float(trade.price)

    def get_account_info(self) -> AccountInfo:
        assert self.api is not None
        acct = self.api.get_account()
        return AccountInfo(
            equity=float(acct.equity),
            cash=float(acct.cash),
            buying_power=float(acct.buying_power),
            currency=acct.currency,
            raw={
                "id": acct.id,
                "status": acct.status,
                "pattern_day_trader": acct.pattern_day_trader,
            },
        )

    # ── Internal helpers ─────────────────────────────────────────────────

    @staticmethod
    def _convert_order(order) -> OrderResult:  # type: ignore[no-untyped-def]
        """Convert an Alpaca order object to internal ``OrderResult``."""
        filled_at = None
        if order.filled_at:
            try:
                filled_at = datetime.fromisoformat(str(order.filled_at))
            except (ValueError, TypeError):
                filled_at = None

        submitted_at = None
        if order.submitted_at:
            try:
                submitted_at = datetime.fromisoformat(str(order.submitted_at))
            except (ValueError, TypeError):
                submitted_at = datetime.now(timezone.utc)

        return OrderResult(
            order_id=str(order.id),
            symbol=order.symbol,
            side=order.side,
            quantity=float(order.qty),
            order_type=order.type,
            status=order.status,
            fill_price=float(order.filled_avg_price) if order.filled_avg_price else None,
            filled_quantity=float(order.filled_qty) if order.filled_qty else 0.0,
            submitted_at=submitted_at,
            filled_at=filled_at,
            raw={"alpaca_id": str(order.id), "client_order_id": order.client_order_id},
        )
