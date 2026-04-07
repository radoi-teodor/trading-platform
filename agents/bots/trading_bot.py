"""
TradingBot – executes a DailyStrategy for a single user throughout the day.

Main loop runs every 30 seconds:
1. Fetch current prices from Redis cache
2. Execute planned trades when urgency conditions are met
3. Monitor stop-loss and take-profit on open positions
4. Check watchlist alert conditions
5. Enforce risk limits (daily loss cap)
6. Update heartbeat in Redis
"""

from __future__ import annotations

import json
import logging
import time
from datetime import datetime, timezone
from typing import Any

import redis

from ..brokers.base import BaseBroker, OrderResult


class TradingBot:
    """Executes a trading strategy for a single user."""

    LOOP_INTERVAL = 30  # seconds

    def __init__(
        self,
        user_id: int,
        broker: BaseBroker,
        redis_client: redis.Redis,  # type: ignore[type-arg]
        strategy: dict[str, Any],
        constraints: dict[str, Any] | None = None,
    ) -> None:
        self.user_id = user_id
        self.broker = broker
        self.redis = redis_client
        self.strategy = strategy
        self.constraints = constraints or {}
        self.logger = logging.getLogger(f"{__name__}.user_{user_id}")

        self._running = False
        self._pending_trades: list[dict[str, Any]] = list(strategy.get("trades", []))
        self._executed_orders: list[OrderResult] = []
        self._starting_equity: float = 0.0
        self._daily_pnl: float = 0.0

    # ── Public API ───────────────────────────────────────────────────────

    def start(self) -> None:
        """Run the main trading loop until stopped or market close."""
        self.logger.info("TradingBot starting for user %d", self.user_id)
        self._running = True

        try:
            account = self.broker.get_account_info()
            self._starting_equity = account.equity
        except Exception as exc:
            self.logger.error("Failed to get initial account info: %s", exc)
            self._starting_equity = 0.0

        while self._running:
            try:
                self._tick()
            except Exception as exc:
                self.logger.error("Bot tick error: %s", exc, exc_info=True)

            time.sleep(self.LOOP_INTERVAL)

        # End-of-day wrap-up
        self._end_of_day()

    def stop(self) -> None:
        """Signal the bot to stop after the current tick."""
        self.logger.info("TradingBot stopping for user %d", self.user_id)
        self._running = False

    def update_strategy(self, strategy: dict[str, Any]) -> None:
        """Hot-update the strategy (e.g. from a midday review)."""
        self.logger.info("Strategy updated for user %d", self.user_id)
        self.strategy = strategy
        self._pending_trades = list(strategy.get("trades", []))

    def get_results(self) -> dict[str, Any]:
        """Return a summary of today's execution for the EOD report."""
        try:
            account = self.broker.get_account_info()
            ending_equity = account.equity
        except Exception:
            ending_equity = self._starting_equity

        pnl = ending_equity - self._starting_equity
        return_pct = (pnl / self._starting_equity * 100) if self._starting_equity else 0.0

        return {
            "user_id": self.user_id,
            "date": datetime.now(timezone.utc).strftime("%Y-%m-%d"),
            "starting_equity": self._starting_equity,
            "ending_equity": ending_equity,
            "day_pnl": pnl,
            "day_return_pct": return_pct,
            "trades": [
                {
                    "symbol": o.symbol,
                    "action": o.side,
                    "quantity": o.quantity,
                    "fill_price": o.fill_price or 0.0,
                    "realized_pnl": 0.0,  # computed from position tracking
                }
                for o in self._executed_orders
            ],
            "morning_outlook": self.strategy.get("market_outlook"),
            "morning_confidence": self.strategy.get("confidence"),
        }

    # ── Main tick ────────────────────────────────────────────────────────

    def _tick(self) -> None:
        """Single iteration of the main loop."""
        # 1. Update price cache in Redis
        self._update_price_cache()

        # 2. Execute pending trades
        self._process_pending_trades()

        # 3. Monitor open positions for SL/TP
        self._monitor_positions()

        # 4. Check watchlist alerts
        self._check_watchlist()

        # 5. Check risk limits
        if self._check_risk_limits():
            self.logger.warning("Risk limit reached – stopping bot for user %d", self.user_id)
            self._running = False
            return

        # 6. Heartbeat
        self._heartbeat()

    # ── Trade execution ──────────────────────────────────────────────────

    def _process_pending_trades(self) -> None:
        """Check each pending trade and execute if urgency conditions are met."""
        remaining: list[dict[str, Any]] = []

        for trade in self._pending_trades:
            urgency = trade.get("urgency", "immediate")

            if urgency == "immediate":
                self._execute_trade(trade)
            elif urgency == "end_of_day":
                # end_of_day urgency: defer to EOD processing, keep in pending
                remaining.append(trade)
                continue
            elif urgency == "wait_for_dip":
                # Check if the price is favourable before executing
                try:
                    current = self.broker.get_ticker_price(trade["symbol"])
                    target_cents = trade.get("entry_price")
                    if target_cents is None:
                        self._execute_trade(trade)
                    else:
                        target = float(target_cents) / 100.0
                        if trade.get("action") == "buy" and current <= target:
                            self._execute_trade(trade)
                        elif trade.get("action") == "sell" and current >= target:
                            self._execute_trade(trade)
                        else:
                            remaining.append(trade)
                            continue
                except Exception as exc:
                    self.logger.warning("Price check failed for %s: %s", trade.get("symbol"), exc)
                    remaining.append(trade)
                    continue
            else:
                remaining.append(trade)
                continue

        self._pending_trades = remaining

    def _execute_trade(self, trade: dict[str, Any]) -> None:
        """Place an order with the broker."""
        symbol = trade["symbol"]
        side = trade["action"]  # buy/sell
        quantity = float(trade["quantity"])
        order_type = trade.get("order_type", "market")
        # entry_price is in cents from the strategy; convert to dollars for broker
        entry_price_cents = trade.get("entry_price")
        price: float | None = None
        if entry_price_cents is not None:
            price = float(entry_price_cents) / 100.0

        try:
            result = self.broker.place_order(
                symbol=symbol,
                side=side,
                quantity=quantity,
                order_type=order_type,
                price=price,
            )
            self._executed_orders.append(result)
            self.logger.info(
                "Order executed: %s %s %.4f %s – status=%s fill=%.2f",
                side, symbol, quantity, order_type,
                result.status, result.fill_price or 0,
            )

            # Publish to Redis for Laravel dashboard
            self.redis.publish(
                f"trading:user:{self.user_id}:orders",
                json.dumps({
                    "order_id": result.order_id,
                    "symbol": symbol,
                    "side": side,
                    "quantity": quantity,
                    "status": result.status,
                    "fill_price": result.fill_price,
                    "timestamp": datetime.now(timezone.utc).isoformat(),
                }),
            )
        except Exception as exc:
            self.logger.error("Failed to execute trade %s %s: %s", side, symbol, exc)

    # ── Position monitoring ──────────────────────────────────────────────

    def _monitor_positions(self) -> None:
        """Check stop-loss and take-profit levels on open positions.

        Note: stop_loss and take_profit values in the strategy are stored in
        cents.  We convert to dollars for comparison with broker prices.
        """
        positions = self.broker.get_positions()
        trades_by_symbol = {t["symbol"]: t for t in self.strategy.get("trades", [])}

        for pos in positions:
            trade_spec = trades_by_symbol.get(pos.symbol, {})
            sl_cents = trade_spec.get("stop_loss")
            tp_cents = trade_spec.get("take_profit")

            sl = float(sl_cents) / 100.0 if sl_cents is not None else None
            tp = float(tp_cents) / 100.0 if tp_cents is not None else None

            if sl is not None and pos.current_price <= sl:
                self.logger.warning(
                    "STOP LOSS triggered for %s at %.2f (SL=%.2f)",
                    pos.symbol, pos.current_price, sl,
                )
                self._execute_trade({
                    "symbol": pos.symbol,
                    "action": "sell",
                    "quantity": pos.quantity,
                    "order_type": "market",
                    "urgency": "immediate",
                })

            elif tp is not None and pos.current_price >= tp:
                self.logger.info(
                    "TAKE PROFIT triggered for %s at %.2f (TP=%.2f)",
                    pos.symbol, pos.current_price, tp,
                )
                self._execute_trade({
                    "symbol": pos.symbol,
                    "action": "sell",
                    "quantity": pos.quantity,
                    "order_type": "market",
                    "urgency": "immediate",
                })

    # ── Watchlist alerts ─────────────────────────────────────────────────

    def _check_watchlist(self) -> None:
        """Evaluate watchlist conditions from the strategy."""
        for item in self.strategy.get("watchlist_alerts", []):
            symbol = item.get("symbol", "")
            condition = item.get("condition_text", "")
            if not symbol or not condition:
                continue

            try:
                price = self.broker.get_ticker_price(symbol)
                triggered = self._evaluate_condition(price, condition)
                if triggered:
                    self.logger.info(
                        "Watchlist alert: %s condition '%s' triggered at %.2f",
                        symbol, condition, price,
                    )
                    self.redis.publish(
                        f"trading:user:{self.user_id}:alerts",
                        json.dumps({
                            "symbol": symbol,
                            "condition": condition,
                            "current_price": price,
                            "timestamp": datetime.now(timezone.utc).isoformat(),
                        }),
                    )
            except Exception:
                pass

    @staticmethod
    def _evaluate_condition(price: float, condition: str) -> bool:
        """Evaluate a simple text condition like 'price drops below 150'."""
        condition_lower = condition.lower()
        import re
        numbers = re.findall(r"[\d.]+", condition)
        if not numbers:
            return False
        target = float(numbers[-1])  # use last number found

        if any(w in condition_lower for w in ("below", "under", "drops", "falls")):
            return price < target
        elif any(w in condition_lower for w in ("above", "over", "rises", "breaks")):
            return price > target
        return False

    # ── Risk management ──────────────────────────────────────────────────

    def _check_risk_limits(self) -> bool:
        """Return True if daily loss limit has been breached."""
        max_loss = self.constraints.get("max_daily_loss")
        if max_loss is None:
            return False

        try:
            account = self.broker.get_account_info()
            current_pnl = account.equity - self._starting_equity
            self._daily_pnl = current_pnl

            if current_pnl < -abs(float(max_loss)):
                self.logger.error(
                    "DAILY LOSS LIMIT BREACHED: P&L=$%.2f  limit=$%.2f",
                    current_pnl, -abs(float(max_loss)),
                )
                return True
        except Exception as exc:
            self.logger.warning("Risk check failed: %s", exc)

        return False

    # ── Utilities ────────────────────────────────────────────────────────

    def _update_price_cache(self) -> None:
        """Cache current prices of all relevant symbols in Redis."""
        symbols: set[str] = set()
        for t in self.strategy.get("trades", []):
            symbols.add(t.get("symbol", ""))
        for w in self.strategy.get("watchlist_alerts", []):
            symbols.add(w.get("symbol", ""))
        symbols.discard("")

        for sym in symbols:
            try:
                price = self.broker.get_ticker_price(sym)
                self.redis.set(f"price:{sym}", str(price), ex=60)
            except Exception:
                pass

    def _heartbeat(self) -> None:
        """Write a heartbeat timestamp to Redis."""
        self.redis.set(
            f"bot:heartbeat:{self.user_id}",
            datetime.now(timezone.utc).isoformat(),
            ex=120,
        )

    def _end_of_day(self) -> None:
        """End-of-day cleanup: execute deferred trades and position closes."""
        # Execute any remaining pending trades with end_of_day urgency
        for trade in self._pending_trades:
            if trade.get("urgency") == "end_of_day":
                self._execute_trade(trade)
        self._pending_trades.clear()

        # Execute positions_to_close
        for close in self.strategy.get("positions_to_close", []):
            symbol = close.get("symbol", "")
            if not symbol:
                continue

            positions = self.broker.get_positions()
            pos = next((p for p in positions if p.symbol == symbol), None)
            if pos is None:
                continue

            if pos.quantity > 0:
                self._execute_trade({
                    "symbol": symbol,
                    "action": "sell",
                    "quantity": pos.quantity,
                    "order_type": "market",
                    "urgency": "immediate",
                })

        self.logger.info("TradingBot end-of-day complete for user %d", self.user_id)
