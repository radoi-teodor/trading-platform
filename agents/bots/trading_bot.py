"""
TradingBot – executes a DailyStrategy for a single user throughout the day.

Supports day-trading with multiple entries/exits per symbol:
1. Fetch current prices every 30 seconds
2. Enter positions when strategy entry conditions are met
3. Exit when take-profit or stop-loss triggers
4. Re-enter if reentry_allowed and max_entries not exceeded
5. Track daily trade count, win/loss, and P&L
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
    """Executes a trading strategy for a single user with day-trading support."""

    LOOP_INTERVAL = 30  # seconds

    def __init__(
        self,
        user_id: int,
        broker: BaseBroker,
        redis_client: Any,
        strategy: dict[str, Any],
        constraints: dict[str, Any] | None = None,
        db_session: Any = None,
        analysis_id: int | None = None,
    ) -> None:
        self.user_id = user_id
        self.broker = broker
        self.redis = redis_client
        self.strategy = strategy
        self.constraints = constraints or {}
        self.db = db_session
        self.analysis_id = analysis_id
        self.logger = logging.getLogger(f"{__name__}.user_{user_id}")

        self._running = False
        self._executed_orders: list[OrderResult] = []
        self._starting_equity: float = 0.0
        self._daily_pnl: float = 0.0
        self._price_cache: dict[str, tuple[float, float]] = {}  # symbol -> (price, ts)

        # ── Day-trading state ────────────────────────────────────────────
        # All trade specs from the strategy (never removed; re-entry uses these)
        self._trade_specs: list[dict[str, Any]] = list(strategy.get("trades", []))

        # symbol -> number of times we've entered a position today
        self._trade_entries: dict[str, int] = {}

        # symbol -> open position info dict:
        #   { "entry_price": float, "quantity": float, "side": str,
        #     "stop_loss": float|None, "take_profit": float|None,
        #     "trade_spec": dict }
        self._open_positions: dict[str, dict[str, Any]] = {}

        # Aggregate daily counters
        self._daily_trades_count: int = 0
        self._wins: int = 0
        self._losses: int = 0
        self._total_realized_pnl: float = 0.0
        self._per_symbol_stats: dict[str, dict[str, Any]] = {}
        # per_symbol_stats[symbol] = { "entries": 0, "wins": 0, "losses": 0, "pnl": 0.0 }

        # Trades that only fire once at end of day (urgency == "end_of_day")
        self._eod_trades: list[dict[str, Any]] = [
            t for t in self._trade_specs if t.get("urgency") == "end_of_day"
        ]

    # ── Price helper ────────────────────────────────────────────────────

    def _get_price(self, symbol: str) -> float | None:
        """Get current price, trying broker first then yfinance as fallback."""
        now = time.time()

        # Check cache (30s TTL)
        if symbol in self._price_cache:
            cached_price, cached_at = self._price_cache[symbol]
            if now - cached_at < 30:
                return cached_price

        # Try broker first
        try:
            price = self.broker.get_ticker_price(symbol)
            if price and price > 0:
                self._price_cache[symbol] = (price, now)
                return price
        except Exception:
            pass

        # Fallback to yfinance
        try:
            import yfinance as yf
            ticker = yf.Ticker(symbol)
            price = float(ticker.fast_info.last_price)
            if price > 0:
                self._price_cache[symbol] = (price, now)
                return price
        except Exception as exc:
            self.logger.debug("yfinance fallback failed for %s: %s", symbol, exc)

        self.logger.warning("Could not get price for %s from any source", symbol)
        return None

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
        """Hot-update the strategy (e.g. from a midday review).

        Merges new trade specs without resetting entry counts for symbols
        that already have trades in progress.
        """
        self.logger.info("Strategy updated for user %d", self.user_id)
        self.strategy = strategy
        new_specs = list(strategy.get("trades", []))

        # Keep entry counts for symbols that carry over
        existing_symbols = {t["symbol"] for t in self._trade_specs}
        for spec in new_specs:
            sym = spec["symbol"]
            if sym not in existing_symbols:
                self._trade_entries.setdefault(sym, 0)

        self._trade_specs = new_specs
        self._eod_trades = [
            t for t in self._trade_specs if t.get("urgency") == "end_of_day"
        ]

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
            "total_trades": self._daily_trades_count,
            "wins": self._wins,
            "losses": self._losses,
            "total_realized_pnl": self._total_realized_pnl,
            "per_symbol_stats": dict(self._per_symbol_stats),
            "trades": [
                {
                    "symbol": o.symbol,
                    "action": o.side,
                    "quantity": o.quantity,
                    "fill_price": o.fill_price or 0.0,
                    "realized_pnl": 0.0,  # individual fill P&L tracked separately
                }
                for o in self._executed_orders
            ],
            "morning_outlook": self.strategy.get("market_outlook"),
            "morning_confidence": self.strategy.get("confidence"),
        }

    # ── Main tick ────────────────────────────────────────────────────────

    def _tick(self) -> None:
        """Single iteration of the main loop."""
        # 1. Refresh price cache for all symbols
        self._update_price_cache()

        # 2. Process each trade spec: check entries and exits
        self._process_trades()

        # 3. Check watchlist alerts
        self._check_watchlist()

        # 4. Check risk limits
        if self._check_risk_limits():
            self.logger.warning("Risk limit reached – stopping bot for user %d", self.user_id)
            self._running = False
            return

        # 5. Heartbeat
        self._heartbeat()

    # ── Core day-trading logic ──────────────────────────────────────────

    def _process_trades(self) -> None:
        """For each trade spec, manage entry/exit with re-entry support."""
        if not hasattr(self, "_logged_skip_reasons"):
            self._logged_skip_reasons: set[str] = set()

        max_daily = self.constraints.get("max_trades", 999)

        for spec in self._trade_specs:
            if spec.get("urgency") == "end_of_day":
                continue

            symbol = spec["symbol"]
            action = spec.get("action", "buy")
            reentry_allowed = spec.get("reentry_allowed", False)
            max_entries = spec.get("max_entries", 1)
            entries_so_far = self._trade_entries.get(symbol, 0)

            # ── Case A: We have an open position for this symbol ────────
            if symbol in self._open_positions:
                self._check_exit(symbol)
                continue

            # ── Case B: No open position – check if we should enter ─────
            skip_reason = None
            if self._daily_trades_count >= max_daily:
                skip_reason = f"daily limit reached ({max_daily})"
            elif entries_so_far >= max_entries:
                skip_reason = f"max entries reached ({entries_so_far}/{max_entries})"
            elif entries_so_far > 0 and not reentry_allowed:
                skip_reason = "reentry not allowed"

            if skip_reason:
                log_key = f"{symbol}:{skip_reason}"
                if log_key not in self._logged_skip_reasons:
                    self._logged_skip_reasons.add(log_key)
                    self.logger.debug("SKIP %s %s: %s", action.upper(), symbol, skip_reason)
                continue

            current_price = self._get_price(symbol)
            if current_price is None:
                continue

            if self._entry_condition_met(spec, current_price):
                self._open_position(spec, current_price)
            else:
                # Log waiting status periodically (every 5 minutes)
                log_key = f"waiting:{symbol}"
                if log_key not in self._logged_skip_reasons:
                    self._logged_skip_reasons.add(log_key)
                    entry_cents = spec.get("entry_price")
                    entry_str = f"${float(entry_cents)/100:.2f}" if entry_cents else "market"
                    cond = spec.get("entry_condition", "price_below" if action == "buy" else "price_above")
                    self.logger.info(
                        "WAITING: %s %s — need %s %s, current $%.2f",
                        action.upper(), symbol, cond, entry_str, current_price,
                    )

    def _entry_condition_met(self, spec: dict[str, Any], current_price: float) -> bool:
        """Check if the entry condition for this trade spec is met."""
        entry_cents = spec.get("entry_price")
        action = spec.get("action", "buy")
        urgency = spec.get("urgency", "immediate")
        entry_condition = spec.get("entry_condition", "")

        # If urgency is "immediate" and no entry_price, always enter on first check
        if urgency == "immediate" and entry_cents is None:
            return True

        # If we have an entry_price, use it as the trigger
        if entry_cents is not None:
            entry_price = float(entry_cents) / 100.0

            # Use explicit entry_condition if provided
            if entry_condition == "price_below":
                return current_price <= entry_price
            elif entry_condition == "price_above":
                return current_price >= entry_price

            # Fallback: infer from action
            if action == "buy":
                return current_price <= entry_price
            else:  # sell
                return current_price >= entry_price

        # wait_for_dip with no entry_price: can't evaluate, skip
        return False

    def _open_position(self, spec: dict[str, Any], current_price: float) -> None:
        """Open a new position based on the trade spec."""
        symbol = spec["symbol"]
        side = spec["action"]
        quantity = float(spec["quantity"])
        order_type = spec.get("order_type", "market")

        # For limit orders, use entry_price; for market orders, no price needed
        price: float | None = None
        entry_cents = spec.get("entry_price")
        if entry_cents is not None and order_type == "limit":
            price = float(entry_cents) / 100.0

        try:
            result = self.broker.place_order(
                symbol=symbol,
                side=side,
                quantity=quantity,
                order_type=order_type,
                price=price,
            )
            self._executed_orders.append(result)

            fill_price = result.fill_price or current_price
            entries = self._trade_entries.get(symbol, 0) + 1
            self._trade_entries[symbol] = entries
            self._daily_trades_count += 1

            # Calculate SL/TP — percentage mode takes priority over fixed price
            sl_pct = spec.get("stop_loss_pct")  # e.g. 0.5 = 0.5%
            tp_pct = spec.get("take_profit_pct")  # e.g. 1.0 = 1.0%
            sl_cents = spec.get("stop_loss")
            tp_cents = spec.get("take_profit")

            if sl_pct is not None:
                # Calculate SL from actual entry price
                if side == "buy":
                    stop_loss = fill_price * (1 - float(sl_pct) / 100)
                else:
                    stop_loss = fill_price * (1 + float(sl_pct) / 100)
            elif sl_cents is not None:
                stop_loss = float(sl_cents) / 100.0
            else:
                stop_loss = None

            if tp_pct is not None:
                # Calculate TP from actual entry price
                if side == "buy":
                    take_profit = fill_price * (1 + float(tp_pct) / 100)
                else:
                    take_profit = fill_price * (1 - float(tp_pct) / 100)
            elif tp_cents is not None:
                take_profit = float(tp_cents) / 100.0
            else:
                take_profit = None

            self._open_positions[symbol] = {
                "entry_price": fill_price,
                "quantity": quantity,
                "side": side,
                "stop_loss": stop_loss,
                "take_profit": take_profit,
                "sl_pct": float(sl_pct) if sl_pct is not None else None,
                "tp_pct": float(tp_pct) if tp_pct is not None else None,
                "trade_spec": spec,
            }

            # Init per-symbol stats
            if symbol not in self._per_symbol_stats:
                self._per_symbol_stats[symbol] = {
                    "entries": 0, "wins": 0, "losses": 0, "pnl": 0.0,
                }
            self._per_symbol_stats[symbol]["entries"] += 1

            sl_display = f"${stop_loss:.2f}" if stop_loss else "none"
            tp_display = f"${take_profit:.2f}" if take_profit else "none"
            if sl_pct is not None:
                sl_display += f" ({sl_pct}%)"
            if tp_pct is not None:
                tp_display += f" ({tp_pct}%)"
            self.logger.info(
                "ENTRY #%d: %s %s %.4f %s @ $%.2f  SL=%s  TP=%s",
                entries, side.upper(), symbol, quantity, order_type, fill_price,
                sl_display, tp_display,
            )

            # Publish to Redis
            self._publish_order_event(result, symbol, side, quantity)

            # Mark the analysis trade as executed in DB
            self._mark_trade_executed(symbol, entries)

        except Exception as exc:
            self.logger.error("Failed to open position %s %s: %s", side, symbol, exc)

    def _mark_trade_executed(self, symbol: str, entry_num: int) -> None:
        """Update claude_analysis_trades status to 'executed' and increment trades_executed."""
        if not self.db or not self.analysis_id:
            self.logger.warning("Cannot mark trade executed: db=%s analysis_id=%s", bool(self.db), self.analysis_id)
            return
        try:
            from sqlalchemy import text

            # Normalize symbol for matching (BTCUSDT and BTC should both match)
            sym_base = symbol.upper()
            for suffix in ("USDT", "USDC", "BUSD", "-USD", "-USDT"):
                if sym_base.endswith(suffix):
                    sym_base = sym_base[:-len(suffix)]
                    break

            # Find the first pending trade for this symbol (SQLite doesn't support LIMIT in UPDATE)
            row = self.db.execute(
                text(
                    "SELECT id, symbol FROM claude_analysis_trades "
                    "WHERE claude_analysis_id = :aid AND status = 'pending' "
                    "ORDER BY id ASC"
                ),
                {"aid": self.analysis_id},
            ).fetchall()

            matched_id = None
            for r in row:
                trade_sym = r[1].upper()
                trade_base = trade_sym
                for suffix in ("USDT", "USDC", "BUSD", "-USD", "-USDT"):
                    if trade_base.endswith(suffix):
                        trade_base = trade_base[:-len(suffix)]
                        break
                if trade_base == sym_base or trade_sym == symbol.upper():
                    matched_id = r[0]
                    break

            if matched_id:
                self.db.execute(
                    text("UPDATE claude_analysis_trades SET status = 'executed' WHERE id = :id"),
                    {"id": matched_id},
                )
                self.db.execute(
                    text("UPDATE claude_analyses SET trades_executed = trades_executed + 1 WHERE id = :aid"),
                    {"aid": self.analysis_id},
                )
                self.db.commit()
                self.logger.info("Marked analysis trade id=%d as EXECUTED for %s", matched_id, symbol)
            else:
                self.logger.debug("No pending analysis trade found for %s (analysis %d)", symbol, self.analysis_id)
        except Exception as exc:
            self.logger.warning("Failed to mark trade executed for %s: %s", symbol, exc)

    def _check_exit(self, symbol: str) -> None:
        """Check stop-loss and take-profit for an open position."""
        pos_info = self._open_positions[symbol]
        current_price = self._get_price(symbol)
        if current_price is None:
            return

        sl = pos_info["stop_loss"]
        tp = pos_info["take_profit"]
        side = pos_info["side"]
        reason = ""

        triggered = False

        if side == "buy":
            # Long position: SL triggers when price drops, TP when price rises
            if sl is not None and current_price <= sl:
                reason = "stop_loss"
                triggered = True
            elif tp is not None and current_price >= tp:
                reason = "take_profit"
                triggered = True
        else:
            # Short position: SL triggers when price rises, TP when price drops
            if sl is not None and current_price >= sl:
                reason = "stop_loss"
                triggered = True
            elif tp is not None and current_price <= tp:
                reason = "take_profit"
                triggered = True

        if triggered:
            self._close_position(symbol, current_price, reason)

    def _close_position(self, symbol: str, current_price: float, reason: str) -> None:
        """Close an open position and record P&L."""
        pos_info = self._open_positions[symbol]
        quantity = pos_info["quantity"]
        entry_price = pos_info["entry_price"]
        side = pos_info["side"]

        # Determine close side (opposite of entry)
        close_side = "sell" if side == "buy" else "buy"

        try:
            result = self.broker.place_order(
                symbol=symbol,
                side=close_side,
                quantity=quantity,
                order_type="market",
            )
            self._executed_orders.append(result)

            fill_price = result.fill_price or current_price

            # Calculate P&L
            if side == "buy":
                pnl = (fill_price - entry_price) * quantity
            else:
                pnl = (entry_price - fill_price) * quantity

            is_win = pnl >= 0
            if is_win:
                self._wins += 1
            else:
                self._losses += 1
            self._total_realized_pnl += pnl

            # Update per-symbol stats
            stats = self._per_symbol_stats.get(symbol, {
                "entries": 0, "wins": 0, "losses": 0, "pnl": 0.0,
            })
            if is_win:
                stats["wins"] += 1
            else:
                stats["losses"] += 1
            stats["pnl"] += pnl
            self._per_symbol_stats[symbol] = stats

            self.logger.info(
                "EXIT (%s): %s %s %.4f @ %.2f  entry=%.2f  P&L=$%.2f  %s",
                reason, close_side, symbol, quantity, fill_price,
                entry_price, pnl, "WIN" if is_win else "LOSS",
            )

            # Remove from open positions (makes symbol eligible for re-entry)
            del self._open_positions[symbol]

            # Publish to Redis
            self._publish_order_event(result, symbol, close_side, quantity)

            # Log daily summary after each close
            self.logger.info(
                "Daily stats: trades=%d  wins=%d  losses=%d  realized_pnl=$%.2f",
                self._daily_trades_count, self._wins, self._losses,
                self._total_realized_pnl,
            )

        except Exception as exc:
            self.logger.error("Failed to close position %s: %s", symbol, exc)

    # ── Watchlist alerts ─────────────────────────────────────────────────

    def _check_watchlist(self) -> None:
        """Evaluate watchlist conditions (log each alert only once)."""
        if not hasattr(self, "_triggered_alerts"):
            self._triggered_alerts: set[str] = set()

        for item in self.strategy.get("watchlist_alerts", []):
            symbol = item.get("symbol", "")
            condition = item.get("condition_text", "")
            if not symbol or not condition:
                continue

            alert_key = f"{symbol}:{condition}"
            if alert_key in self._triggered_alerts:
                continue  # already logged this alert

            try:
                price = self._get_price(symbol)
                if price is None:
                    continue
                triggered = self._evaluate_condition(price, condition)
                if triggered:
                    self._triggered_alerts.add(alert_key)
                    self.logger.info(
                        "ALERT: %s — '%s' triggered at $%.2f",
                        symbol, condition, price,
                    )
                    if self.redis:
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
        for t in self._trade_specs:
            symbols.add(t.get("symbol", ""))
        for w in self.strategy.get("watchlist_alerts", []):
            symbols.add(w.get("symbol", ""))
        symbols.discard("")

        for sym in symbols:
            try:
                price = self._get_price(sym)
                if price is not None:
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

    def _publish_order_event(
        self, result: OrderResult, symbol: str, side: str, quantity: float,
    ) -> None:
        """Publish an order event to Redis for the Laravel dashboard."""
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

    def _end_of_day(self) -> None:
        """End-of-day cleanup: close open positions, execute deferred trades."""
        # Close any remaining open positions
        for symbol in list(self._open_positions.keys()):
            current_price = self._get_price(symbol)
            if current_price is not None:
                self.logger.info("EOD: closing open position for %s", symbol)
                self._close_position(symbol, current_price, "end_of_day")

        # Execute end-of-day urgency trades
        for trade in self._eod_trades:
            symbol = trade["symbol"]
            if symbol not in self._open_positions:
                self._execute_eod_trade(trade)

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
                try:
                    result = self.broker.place_order(
                        symbol=symbol,
                        side="sell",
                        quantity=pos.quantity,
                        order_type="market",
                    )
                    self._executed_orders.append(result)
                    self.logger.info("EOD: closed position %s per strategy", symbol)
                except Exception as exc:
                    self.logger.error("EOD: failed to close %s: %s", symbol, exc)

        self.logger.info(
            "TradingBot end-of-day complete for user %d  |  "
            "trades=%d  wins=%d  losses=%d  realized_pnl=$%.2f",
            self.user_id, self._daily_trades_count,
            self._wins, self._losses, self._total_realized_pnl,
        )

    def _execute_eod_trade(self, trade: dict[str, Any]) -> None:
        """Execute a single end-of-day trade."""
        symbol = trade["symbol"]
        side = trade["action"]
        quantity = float(trade["quantity"])
        order_type = trade.get("order_type", "market")

        price: float | None = None
        entry_cents = trade.get("entry_price")
        if entry_cents is not None and order_type == "limit":
            price = float(entry_cents) / 100.0

        try:
            result = self.broker.place_order(
                symbol=symbol,
                side=side,
                quantity=quantity,
                order_type=order_type,
                price=price,
            )
            self._executed_orders.append(result)
            self._daily_trades_count += 1
            self.logger.info(
                "EOD trade executed: %s %s %.4f %s – status=%s fill=%.2f",
                side, symbol, quantity, order_type,
                result.status, result.fill_price or 0,
            )
            self._publish_order_event(result, symbol, side, quantity)
        except Exception as exc:
            self.logger.error("Failed to execute EOD trade %s %s: %s", side, symbol, exc)
