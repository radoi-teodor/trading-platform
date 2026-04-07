"""
BaseBroker – abstract base class for all broker integrations.
"""

from __future__ import annotations

from abc import ABC, abstractmethod
from dataclasses import dataclass, field
from datetime import datetime
from typing import Any


@dataclass
class OrderResult:
    """Standardised result returned by every broker after placing an order."""

    order_id: str
    symbol: str
    side: str  # "buy" | "sell"
    quantity: float
    order_type: str  # "market" | "limit" | "stop" | "stop_limit"
    status: str  # "filled" | "pending" | "cancelled" | "rejected"
    fill_price: float | None = None
    filled_quantity: float = 0.0
    submitted_at: datetime | None = None
    filled_at: datetime | None = None
    raw: dict[str, Any] = field(default_factory=dict)


@dataclass
class Position:
    """Standardised open position."""

    symbol: str
    quantity: float
    avg_cost: float
    current_price: float = 0.0
    unrealized_pnl: float = 0.0
    side: str = "long"


@dataclass
class AccountInfo:
    """Standardised account summary."""

    equity: float
    cash: float
    buying_power: float
    currency: str = "USD"
    raw: dict[str, Any] = field(default_factory=dict)


class BaseBroker(ABC):
    """Abstract broker interface."""

    @abstractmethod
    def connect(self) -> None:
        """Establish connection / authenticate with the broker."""

    @abstractmethod
    def disconnect(self) -> None:
        """Cleanly close the broker connection."""

    @abstractmethod
    def get_balance(self) -> float:
        """Return available cash balance."""

    @abstractmethod
    def get_positions(self) -> list[Position]:
        """Return all open positions."""

    @abstractmethod
    def place_order(
        self,
        symbol: str,
        side: str,
        quantity: float,
        order_type: str = "market",
        price: float | None = None,
    ) -> OrderResult:
        """Submit an order and return the result."""

    @abstractmethod
    def cancel_order(self, order_id: str) -> bool:
        """Cancel an open order.  Return True on success."""

    @abstractmethod
    def get_order_status(self, order_id: str) -> OrderResult:
        """Fetch the current status of an existing order."""

    @abstractmethod
    def get_ticker_price(self, symbol: str) -> float:
        """Return the last traded price for *symbol*."""

    @abstractmethod
    def get_account_info(self) -> AccountInfo:
        """Return full account info summary."""
