"""
PaperBroker – simulated broker backed by database state.

Uses real market prices for realistic paper trading.  Market orders fill
instantly; limit orders fill when the price condition is met (checked on
each ``check_pending_orders`` call).

All monetary values in the database are stored in **cents** to match the
Laravel migration schema.  The broker interface speaks in **dollars**, so
conversions happen at the DB boundary.
"""

from __future__ import annotations

import logging
import uuid
from datetime import datetime, timezone
from typing import Any

from sqlalchemy import text
from sqlalchemy.orm import Session

from .base import AccountInfo, BaseBroker, OrderResult, Position


def _dollars_to_cents(d: float) -> int:
    """Convert a dollar float to integer cents."""
    return int(round(d * 100))


def _cents_to_dollars(c: int | float) -> float:
    """Convert integer cents to dollar float."""
    return float(c) / 100.0


class PaperBroker(BaseBroker):
    """Simulated broker that persists all state in MySQL/SQLite.

    Uses the ``portfolios``, ``orders``, ``trades``, and ``positions``
    tables defined by the Laravel migrations.
    """

    def __init__(
        self,
        db_session: Session,
        user_id: int,
        market_data_source: Any = None,
        initial_balance: float = 100_000.0,
    ) -> None:
        self.db = db_session
        self.user_id = user_id
        self.market_data = market_data_source
        self.initial_balance = initial_balance
        self.logger = logging.getLogger(__name__)
        self._connected = False
        self._portfolio_id: int | None = None

    # ── BaseBroker implementation ────────────────────────────────────────

    def connect(self) -> None:
        """Ensure a portfolio row exists in DB for this user."""
        row = self.db.execute(
            text("SELECT id FROM portfolios WHERE user_id = :uid LIMIT 1"),
            {"uid": self.user_id},
        ).fetchone()
        if row is None:
            bal_cents = _dollars_to_cents(self.initial_balance)
            result = self.db.execute(
                text(
                    "INSERT INTO portfolios "
                    "(user_id, name, balance_cents, initial_balance_cents, "
                    " created_at, updated_at) "
                    "VALUES (:uid, :name, :bal, :ibal, :now, :now)"
                ),
                {
                    "uid": self.user_id,
                    "name": "Paper Trading",
                    "bal": bal_cents,
                    "ibal": bal_cents,
                    "now": datetime.now(timezone.utc),
                },
            )
            self.db.commit()
            self._portfolio_id = result.lastrowid  # type: ignore[assignment]
        else:
            self._portfolio_id = row[0]
        self._connected = True
        self.logger.info(
            "PaperBroker connected for user %s (portfolio %s)",
            self.user_id, self._portfolio_id,
        )

    def disconnect(self) -> None:
        self._connected = False
        self.logger.info("PaperBroker disconnected for user %s", self.user_id)

    def get_balance(self) -> float:
        row = self.db.execute(
            text("SELECT balance_cents FROM portfolios WHERE id = :pid"),
            {"pid": self._portfolio_id},
        ).fetchone()
        return _cents_to_dollars(row[0]) if row else 0.0

    def get_positions(self) -> list[Position]:
        rows = self.db.execute(
            text(
                "SELECT symbol, quantity, avg_entry_price_cents, "
                "       current_price_cents, side "
                "FROM positions "
                "WHERE portfolio_id = :pid AND quantity != 0 AND closed_at IS NULL"
            ),
            {"pid": self._portfolio_id},
        ).fetchall()

        positions: list[Position] = []
        for r in rows:
            current = self._get_price(r[0])
            qty = float(r[1])
            avg = _cents_to_dollars(r[2])
            side = r[4] or "long"
            pnl = (current - avg) * qty if side == "long" else (avg - current) * qty
            positions.append(
                Position(
                    symbol=r[0],
                    quantity=qty,
                    avg_cost=avg,
                    current_price=current,
                    unrealized_pnl=pnl,
                    side=side,
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
        now = datetime.now(timezone.utc)

        if order_type == "market":
            fill_price = self._get_price(symbol)
            self._execute_fill(symbol, side, quantity, fill_price)
            status = "filled"
            filled_at = now
        else:
            fill_price = None
            status = "pending"
            filled_at = None

        price_cents = _dollars_to_cents(price) if price is not None else None

        result = self.db.execute(
            text(
                "INSERT INTO orders "
                "(portfolio_id, symbol, type, side, quantity, "
                " price_cents, status, filled_at, created_at, updated_at) "
                "VALUES (:pid, :sym, :type, :side, :qty, "
                " :price, :status, :fa, :now, :now)"
            ),
            {
                "pid": self._portfolio_id,
                "sym": symbol,
                "type": order_type,
                "side": side,
                "qty": quantity,
                "price": price_cents,
                "status": status,
                "fa": filled_at,
                "now": now,
            },
        )
        self.db.commit()
        order_id = str(result.lastrowid)

        # If filled, also insert a trade record
        if status == "filled" and fill_price is not None:
            self._insert_trade(int(order_id), symbol, side, quantity, fill_price, now)

        self.logger.info(
            "Paper order %s: %s %s %.4f %s @ %s – %s",
            order_id, side, symbol, quantity, order_type, fill_price, status,
        )

        return OrderResult(
            order_id=order_id,
            symbol=symbol,
            side=side,
            quantity=quantity,
            order_type=order_type,
            status=status,
            fill_price=fill_price,
            filled_quantity=quantity if status == "filled" else 0.0,
            submitted_at=now,
            filled_at=filled_at,
        )

    def cancel_order(self, order_id: str) -> bool:
        result = self.db.execute(
            text(
                "UPDATE orders SET status = 'cancelled', updated_at = :now "
                "WHERE id = :oid AND portfolio_id = :pid AND status = 'pending'"
            ),
            {
                "oid": int(order_id),
                "pid": self._portfolio_id,
                "now": datetime.now(timezone.utc),
            },
        )
        self.db.commit()
        return result.rowcount > 0  # type: ignore[union-attr]

    def get_order_status(self, order_id: str) -> OrderResult:
        row = self.db.execute(
            text(
                "SELECT id, symbol, side, quantity, type, status, "
                "       price_cents, filled_at, created_at "
                "FROM orders WHERE id = :oid AND portfolio_id = :pid"
            ),
            {"oid": int(order_id), "pid": self._portfolio_id},
        ).fetchone()
        if row is None:
            raise ValueError(f"Order {order_id} not found")
        fill_price = _cents_to_dollars(row[6]) if row[6] else None
        return OrderResult(
            order_id=str(row[0]),
            symbol=row[1],
            side=row[2],
            quantity=float(row[3]),
            order_type=row[4],
            status=row[5],
            fill_price=fill_price,
            filled_quantity=float(row[3]) if row[5] == "filled" else 0.0,
            submitted_at=row[8],
            filled_at=row[7],
        )

    def get_ticker_price(self, symbol: str) -> float:
        return self._get_price(symbol)

    def get_account_info(self) -> AccountInfo:
        cash = self.get_balance()
        positions = self.get_positions()
        position_value = sum(p.current_price * p.quantity for p in positions)
        equity = cash + position_value
        return AccountInfo(equity=equity, cash=cash, buying_power=cash)

    # ── Paper-specific methods ───────────────────────────────────────────

    def check_pending_orders(self) -> list[OrderResult]:
        """Check pending limit/stop orders and fill them if conditions are met."""
        rows = self.db.execute(
            text(
                "SELECT id, symbol, side, quantity, type, price_cents "
                "FROM orders WHERE portfolio_id = :pid AND status = 'pending'"
            ),
            {"pid": self._portfolio_id},
        ).fetchall()

        filled: list[OrderResult] = []
        now = datetime.now(timezone.utc)

        for r in rows:
            oid = r[0]
            symbol = r[1]
            side = r[2]
            qty = float(r[3])
            otype = r[4]
            limit_price = _cents_to_dollars(r[5]) if r[5] else None

            current = self._get_price(symbol)
            should_fill = False

            if otype == "limit":
                if side == "buy" and limit_price is not None and current <= limit_price:
                    should_fill = True
                elif side == "sell" and limit_price is not None and current >= limit_price:
                    should_fill = True
            elif otype in ("stop_loss", "take_profit"):
                if side == "buy" and limit_price is not None and current >= limit_price:
                    should_fill = True
                elif side == "sell" and limit_price is not None and current <= limit_price:
                    should_fill = True

            if should_fill:
                self._execute_fill(symbol, side, qty, current)
                self.db.execute(
                    text(
                        "UPDATE orders SET status='filled', "
                        "filled_at=:fa, updated_at=:now "
                        "WHERE id=:oid"
                    ),
                    {"fa": now, "now": now, "oid": oid},
                )
                self._insert_trade(oid, symbol, side, qty, current, now)
                self.db.commit()
                filled.append(
                    OrderResult(
                        order_id=str(oid), symbol=symbol, side=side,
                        quantity=qty, order_type=otype, status="filled",
                        fill_price=current, filled_quantity=qty,
                        filled_at=now,
                    )
                )
                self.logger.info("Pending order %s filled at %.2f", oid, current)

        return filled

    # ── Internals ────────────────────────────────────────────────────────

    def _get_price(self, symbol: str) -> float:
        """Fetch the current price from the attached market data source."""
        if self.market_data is not None:
            try:
                return self.market_data.get_price(symbol)
            except Exception:
                self.logger.warning("Could not fetch price for %s from market data", symbol)
        return 0.0

    def _execute_fill(self, symbol: str, side: str, quantity: float, price: float) -> None:
        """Update cash balance and position records for a fill."""
        cost = price * quantity
        if side == "buy":
            self._adjust_cash(-cost)
            self._update_position(symbol, quantity, price, "long")
        else:
            self._adjust_cash(cost)
            self._update_position(symbol, -quantity, price, "long")

    def _adjust_cash(self, delta_dollars: float) -> None:
        delta_cents = _dollars_to_cents(delta_dollars)
        self.db.execute(
            text(
                "UPDATE portfolios SET balance_cents = balance_cents + :delta, "
                "updated_at = :now WHERE id = :pid"
            ),
            {"delta": delta_cents, "pid": self._portfolio_id, "now": datetime.now(timezone.utc)},
        )
        self.db.commit()

    def _update_position(self, symbol: str, qty_delta: float, price: float, side: str) -> None:
        """Add to or create a position.  If quantity reaches 0, mark as closed."""
        row = self.db.execute(
            text(
                "SELECT quantity, avg_entry_price_cents FROM positions "
                "WHERE portfolio_id = :pid AND symbol = :sym AND closed_at IS NULL"
            ),
            {"pid": self._portfolio_id, "sym": symbol},
        ).fetchone()

        now = datetime.now(timezone.utc)
        price_cents = _dollars_to_cents(price)

        if row is None:
            self.db.execute(
                text(
                    "INSERT INTO positions "
                    "(portfolio_id, symbol, quantity, avg_entry_price_cents, "
                    " current_price_cents, side, opened_at, created_at, updated_at) "
                    "VALUES (:pid, :sym, :qty, :avg, :cur, :side, :now, :now, :now)"
                ),
                {
                    "pid": self._portfolio_id,
                    "sym": symbol,
                    "qty": qty_delta,
                    "avg": price_cents,
                    "cur": price_cents,
                    "side": side,
                    "now": now,
                },
            )
        else:
            old_qty = float(row[0])
            old_avg_cents = int(row[1])
            new_qty = old_qty + qty_delta
            # Weighted average cost only when adding to position
            if qty_delta > 0 and new_qty > 0:
                new_avg_cents = int(
                    ((old_avg_cents * old_qty) + (price_cents * qty_delta)) / new_qty
                )
            else:
                new_avg_cents = old_avg_cents

            if new_qty == 0:
                # Position fully closed
                self.db.execute(
                    text(
                        "UPDATE positions SET quantity = 0, "
                        "current_price_cents = :cur, closed_at = :now, updated_at = :now "
                        "WHERE portfolio_id = :pid AND symbol = :sym AND closed_at IS NULL"
                    ),
                    {"cur": price_cents, "now": now, "pid": self._portfolio_id, "sym": symbol},
                )
            else:
                self.db.execute(
                    text(
                        "UPDATE positions SET quantity = :qty, "
                        "avg_entry_price_cents = :avg, current_price_cents = :cur, "
                        "updated_at = :now "
                        "WHERE portfolio_id = :pid AND symbol = :sym AND closed_at IS NULL"
                    ),
                    {
                        "qty": new_qty,
                        "avg": new_avg_cents,
                        "cur": price_cents,
                        "now": now,
                        "pid": self._portfolio_id,
                        "sym": symbol,
                    },
                )
        self.db.commit()

    def _insert_trade(
        self,
        order_id: int,
        symbol: str,
        side: str,
        quantity: float,
        price: float,
        executed_at: datetime,
    ) -> None:
        """Insert a record into the ``trades`` table."""
        price_cents = _dollars_to_cents(price)
        self.db.execute(
            text(
                "INSERT INTO trades "
                "(order_id, portfolio_id, symbol, side, quantity, price_cents, "
                " pnl_cents, fee_cents, executed_at, created_at, updated_at) "
                "VALUES (:oid, :pid, :sym, :side, :qty, :price, "
                " :pnl, :fee, :exec, :now, :now)"
            ),
            {
                "oid": order_id,
                "pid": self._portfolio_id,
                "sym": symbol,
                "side": side,
                "qty": quantity,
                "price": price_cents,
                "pnl": 0,
                "fee": 0,
                "exec": executed_at,
                "now": executed_at,
            },
        )
        self.db.commit()
