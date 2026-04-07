"""
ClaudeEngine – wraps all communication with the ``claude`` CLI binary.

Every interaction is logged to the ``claude_analyses`` table so that the
Laravel dashboard can display prompt / response history.
"""

from __future__ import annotations

import json
import logging
import subprocess
import time
from datetime import datetime, timezone
from typing import Any

from sqlalchemy import text
from sqlalchemy.orm import Session


class ClaudeEngine:
    """High-level interface to the Claude Code CLI binary."""

    def __init__(self, db_session: Session, binary_path: str = "claude") -> None:
        self.binary_path = binary_path
        self.db: Session = db_session
        self.logger = logging.getLogger(__name__)

    # ── Public API ───────────────────────────────────────────────────────

    def ask(self, prompt: str, timeout: int = 300) -> str:
        """Send *prompt* to the claude CLI and return the text response.

        Retries up to 3 times with exponential back-off (5 s, 15 s, 45 s).
        """
        retries = 3
        delays = [5, 15, 45]
        last_error: Exception | None = None
        prompt_len = len(prompt)

        for attempt in range(retries):
            self.logger.info(
                "Claude CLI call attempt %d/%d — prompt size: %d chars, timeout: %ds",
                attempt + 1, retries, prompt_len, timeout,
            )
            start_time = time.time()
            try:
                result = subprocess.run(
                    [self.binary_path, "-p", prompt, "--output-format", "json"],
                    capture_output=True,
                    text=True,
                    timeout=timeout,
                )
                elapsed = time.time() - start_time

                if result.returncode != 0:
                    self.logger.error(
                        "Claude CLI returned code %d after %.1fs — stderr: %s",
                        result.returncode, elapsed, result.stderr[:500],
                    )
                    raise RuntimeError(
                        f"Claude CLI error (code {result.returncode}): {result.stderr[:300]}"
                    )

                self.logger.info(
                    "Claude CLI responded in %.1fs — output size: %d chars",
                    elapsed, len(result.stdout),
                )

                response = json.loads(result.stdout)
                answer = response.get("result", result.stdout)
                self.logger.info(
                    "Claude response parsed — result size: %d chars, first 100: %s",
                    len(answer), answer[:100].replace("\n", " "),
                )
                return answer

            except subprocess.TimeoutExpired:
                elapsed = time.time() - start_time
                self.logger.error(
                    "Claude CLI TIMED OUT after %.1fs (limit: %ds) on attempt %d/%d",
                    elapsed, timeout, attempt + 1, retries,
                )
                last_error = TimeoutError(f"Claude timed out after {timeout}s")
                if attempt < retries - 1:
                    self.logger.info("Retrying in %ds...", delays[attempt])
                    time.sleep(delays[attempt])

            except Exception as exc:
                elapsed = time.time() - start_time
                last_error = exc
                self.logger.warning(
                    "Claude attempt %d/%d failed after %.1fs: %s",
                    attempt + 1, retries, elapsed, exc,
                )
                if attempt < retries - 1:
                    self.logger.info("Retrying in %ds...", delays[attempt])
                    time.sleep(delays[attempt])

        raise RuntimeError(f"Claude failed after {retries} attempts: {last_error}")

    def daily_analysis(self, context: dict[str, Any]) -> dict[str, Any]:
        """Run the full daily analysis cycle.

        1. Build the mega-prompt from *context*.
        2. Send to Claude CLI.
        3. Parse and validate the JSON strategy response.
        4. Log everything to the database.
        """
        from .prompt_builder import PromptBuilder
        from .strategy_parser import StrategyParser

        self.logger.info("=== DAILY ANALYSIS START for user %s ===", context.get("user_id"))
        style = context.get("strategy_style", "day_trading")
        self.logger.info("Strategy style: %s | Symbols: %s", style, context.get("constraints", {}).get("allowed_symbols", "any"))

        self.logger.info("Building prompt...")
        prompt = PromptBuilder.build_daily_analysis(context)
        self.logger.info("Prompt built — %d chars", len(prompt))

        # Log the outgoing prompt to DB
        analysis_id = self._log_prompt(
            user_id=context.get("user_id"),
            date=context.get("date"),
            prompt=prompt,
        )
        self.logger.info("Sending to Claude CLI (analysis_id=%d)...", analysis_id)

        total_start = time.time()
        raw_response = self.ask(prompt)
        total_elapsed = time.time() - total_start
        self.logger.info("Claude responded in %.1fs total", total_elapsed)

        # Log the raw response
        self._log_response(analysis_id, raw_response)

        # Parse and validate
        self.logger.info("Parsing Claude response...")
        strategy = StrategyParser.parse(raw_response, context.get("constraints", {}))
        num_trades = len(strategy.get("trades", []))
        self.logger.info(
            "Strategy parsed — outlook: %s, confidence: %.0f%%, trades: %d",
            strategy.get("market_outlook", "?"),
            strategy.get("confidence", 0) * 100,
            num_trades,
        )

        # Persist the parsed strategy
        self._update_analysis(analysis_id, strategy)

        # Cancel old pending trades from previous strategies
        self._cancel_old_pending_trades(context.get("user_id"), analysis_id, strategy)

        # Insert individual trade recommendations
        self._insert_analysis_trades(analysis_id, strategy)

        self.logger.info("=== DAILY ANALYSIS COMPLETE — %d trades planned ===", num_trades)
        strategy["_analysis_id"] = analysis_id
        return strategy

    def midday_review(self, context: dict[str, Any]) -> dict[str, Any]:
        """Lighter midday review – checks if position adjustments are needed."""
        from .prompt_builder import PromptBuilder
        from .strategy_parser import StrategyParser

        prompt = PromptBuilder.build_midday_review(context)
        raw_response = self.ask(prompt)
        return StrategyParser.parse(raw_response, context.get("constraints", {}))

    def end_of_day_summary(self, results: dict[str, Any]) -> str:
        """Get Claude's narrative end-of-day summary and lessons learned."""
        from .prompt_builder import PromptBuilder

        prompt = PromptBuilder.build_eod_summary(results)
        return self.ask(prompt)

    # ── Private DB helpers ───────────────────────────────────────────────

    def _log_prompt(
        self,
        user_id: int | None,
        date: str | None,
        prompt: str,
    ) -> int:
        """Find existing pending analysis or create a new one. Return its ID."""
        now = datetime.now(timezone.utc)

        # Check if Laravel already created a pending analysis for this user
        existing = self.db.execute(
            text(
                "SELECT id FROM claude_analyses "
                "WHERE user_id = :uid AND status = 'pending' "
                "ORDER BY created_at DESC LIMIT 1"
            ),
            {"uid": user_id},
        ).fetchone()

        if existing:
            # Update the existing pending record with the prompt
            analysis_id = existing[0]
            self.db.execute(
                text(
                    "UPDATE claude_analyses SET prompt_sent = :prompt "
                    "WHERE id = :id"
                ),
                {"prompt": prompt, "id": analysis_id},
            )
            self.db.commit()
            self.logger.info("Using existing pending analysis id=%s for user=%s", analysis_id, user_id)
            return analysis_id

        # No pending exists — create a new one
        result = self.db.execute(
            text(
                "INSERT INTO claude_analyses "
                "(user_id, date, market_outlook, confidence, reasoning, "
                " strategy_json, status, trades_planned, prompt_sent, "
                " raw_response, created_at) "
                "VALUES (:user_id, :date, :outlook, :confidence, :reasoning, "
                " :strategy_json, :status, :trades_planned, :prompt_sent, "
                " :raw_response, :created_at)"
            ),
            {
                "user_id": user_id,
                "date": date or now.strftime("%Y-%m-%d"),
                "outlook": "neutral",
                "confidence": 0.0,
                "reasoning": "",
                "strategy_json": "{}",
                "trades_planned": 0,
                "prompt_sent": prompt,
                "raw_response": "",
                "status": "pending",
                "created_at": now,
            },
        )
        self.db.commit()
        analysis_id = result.lastrowid  # type: ignore[assignment]
        self.logger.info("Created new analysis id=%s for user=%s", analysis_id, user_id)
        return analysis_id

    def _log_response(self, analysis_id: int, raw_response: str) -> None:
        """Update the analysis row with the raw Claude response text."""
        self.db.execute(
            text(
                "UPDATE claude_analyses "
                "SET raw_response = :raw_response, status = :status "
                "WHERE id = :id"
            ),
            {
                "id": analysis_id,
                "raw_response": raw_response,
                "status": "active",
            },
        )
        self.db.commit()
        self.logger.info("Logged raw response for analysis id=%s", analysis_id)

    def _update_analysis(self, analysis_id: int, strategy: dict[str, Any]) -> None:
        """Update the analysis row with the parsed & validated strategy."""
        trades = strategy.get("trades", [])
        self.db.execute(
            text(
                "UPDATE claude_analyses "
                "SET strategy_json = :strategy_json, "
                "    market_outlook = :outlook, "
                "    confidence = :confidence, "
                "    reasoning = :reasoning, "
                "    risk_assessment = :risk_assessment, "
                "    lessons_from_yesterday = :lessons, "
                "    trades_planned = :trades_planned, "
                "    next_review = :next_review, "
                "    status = :status "
                "WHERE id = :id"
            ),
            {
                "id": analysis_id,
                "strategy_json": json.dumps(strategy),
                "outlook": strategy.get("market_outlook", "neutral"),
                "confidence": strategy.get("confidence", 0),
                "reasoning": strategy.get("reasoning", ""),
                "risk_assessment": strategy.get("risk_assessment"),
                "lessons": strategy.get("lessons_from_yesterday"),
                "trades_planned": len(trades),
                "next_review": strategy.get("next_review", "tomorrow")
                    if strategy.get("next_review") in ("midday", "end_of_day", "tomorrow")
                    else "tomorrow",
                "status": "active",
            },
        )
        self.db.commit()
        self.logger.info(
            "Updated analysis id=%s  outlook=%s  trades=%d",
            analysis_id,
            strategy.get("market_outlook"),
            len(trades),
        )

    def _insert_analysis_trades(self, analysis_id: int, strategy: dict[str, Any]) -> None:
        """Insert individual trade recommendations into claude_analysis_trades."""
        now = datetime.now(timezone.utc)
        for trade in strategy.get("trades", []):
            self.db.execute(
                text(
                    "INSERT INTO claude_analysis_trades "
                    "(claude_analysis_id, symbol, action, order_type, quantity, "
                    " entry_price_cents, stop_loss_cents, take_profit_cents, "
                    " urgency, confidence, reasoning, status, created_at, updated_at) "
                    "VALUES (:analysis_id, :symbol, :action, :order_type, :quantity, "
                    " :entry_price, :stop_loss, :take_profit, "
                    " :urgency, :confidence, :reasoning, :status, :now, :now)"
                ),
                {
                    "analysis_id": analysis_id,
                    "symbol": trade.get("symbol", ""),
                    "action": trade.get("action", "buy")
                        if trade.get("action") in ("buy", "sell")
                        else "buy",
                    "order_type": trade.get("order_type", "market")
                        if trade.get("order_type") in ("market", "limit")
                        else "market",
                    "quantity": trade.get("quantity", 0),
                    "entry_price": trade.get("entry_price"),
                    "stop_loss": trade.get("stop_loss"),
                    "take_profit": trade.get("take_profit"),
                    "urgency": trade.get("urgency", "immediate")
                        if trade.get("urgency") in ("immediate", "wait_for_dip", "end_of_day")
                        else "immediate",
                    "confidence": trade.get("confidence", 0),
                    "reasoning": trade.get("reasoning", ""),
                    "status": "pending",
                    "now": now,
                },
            )

        # Insert positions to close
        for close in strategy.get("positions_to_close", []):
            self.db.execute(
                text(
                    "INSERT INTO claude_analysis_closes "
                    "(claude_analysis_id, symbol, reason, urgency, status, "
                    " created_at, updated_at) "
                    "VALUES (:analysis_id, :symbol, :reason, :urgency, :status, :now, :now)"
                ),
                {
                    "analysis_id": analysis_id,
                    "symbol": close.get("symbol", ""),
                    "reason": close.get("reason", ""),
                    "urgency": close.get("urgency", "end_of_day"),
                    "status": "pending",
                    "now": now,
                },
            )

        # Insert watchlist alerts
        for alert in strategy.get("watchlist_alerts", []):
            self.db.execute(
                text(
                    "INSERT INTO claude_analysis_alerts "
                    "(claude_analysis_id, symbol, condition_text, parsed_condition, "
                    " triggered, created_at, updated_at) "
                    "VALUES (:analysis_id, :symbol, :condition_text, :parsed, "
                    " :triggered, :now, :now)"
                ),
                {
                    "analysis_id": analysis_id,
                    "symbol": alert.get("symbol", ""),
                    "condition_text": alert.get("condition_text", ""),
                    "parsed": json.dumps(alert.get("parsed", {})),
                    "triggered": False,
                    "now": now,
                },
            )

        self.db.commit()
        self.logger.info(
            "Inserted %d trades, %d closes, %d alerts for analysis id=%s",
            len(strategy.get("trades", [])),
            len(strategy.get("positions_to_close", [])),
            len(strategy.get("watchlist_alerts", [])),
            analysis_id,
        )

    def _cancel_old_pending_trades(
        self, user_id: int | None, new_analysis_id: int, strategy: dict[str, Any]
    ) -> None:
        """Cancel pending trades from old strategies that are NOT carried over."""
        if not user_id:
            return

        # Symbols in the new strategy
        new_symbols = {t.get("symbol", "").upper() for t in strategy.get("trades", [])}
        # Also normalize: BTCUSDT → BTC
        new_bases = set()
        for s in new_symbols:
            base = s
            for suffix in ("USDT", "USDC", "BUSD", "-USD", "-USDT"):
                if base.endswith(suffix):
                    base = base[:-len(suffix)]
                    break
            new_bases.add(base)
            new_bases.add(s)  # keep original too

        # Find old pending trades (from other analyses)
        rows = self.db.execute(
            text(
                "SELECT cat.id, cat.symbol "
                "FROM claude_analysis_trades cat "
                "JOIN claude_analyses ca ON ca.id = cat.claude_analysis_id "
                "WHERE ca.user_id = :uid AND cat.status = 'pending' "
                "AND cat.claude_analysis_id != :new_id"
            ),
            {"uid": user_id, "new_id": new_analysis_id},
        ).fetchall()

        cancelled = 0
        for row in rows:
            trade_id, symbol = row[0], row[1].upper()
            # Normalize old symbol too
            base = symbol
            for suffix in ("USDT", "USDC", "BUSD", "-USD", "-USDT"):
                if base.endswith(suffix):
                    base = base[:-len(suffix)]
                    break

            # If new strategy has a trade for this symbol, the old one is superseded
            # If not, cancel it — Claude chose not to include it
            self.db.execute(
                text(
                    "UPDATE claude_analysis_trades SET status = 'skipped', "
                    "skip_reason = 'Superseded by new strategy' "
                    "WHERE id = :id"
                ),
                {"id": trade_id},
            )
            cancelled += 1

        if cancelled:
            self.db.commit()
            self.logger.info("Cancelled %d old pending trades for user %s", cancelled, user_id)
