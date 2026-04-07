"""
BotManager – manages one TradingBot per active user.

Spawns bots in threads, monitors health, auto-restarts crashed bots,
publishes status to Redis, and handles graceful shutdown.
"""

from __future__ import annotations

import json
import logging
import threading
import time
from datetime import datetime, timezone
from typing import Any

import redis

from ..brokers.base import BaseBroker
from .trading_bot import TradingBot


class BotManager:
    """Manages the lifecycle of per-user TradingBot instances."""

    HEALTH_CHECK_INTERVAL = 30  # seconds
    MAX_RESTARTS = 3

    def __init__(self, redis_client: redis.Redis) -> None:  # type: ignore[type-arg]
        self.redis = redis_client
        self.logger = logging.getLogger(__name__)

        self._bots: dict[int, TradingBot] = {}
        self._threads: dict[int, threading.Thread] = {}
        self._restart_counts: dict[int, int] = {}
        self._running = False
        self._monitor_thread: threading.Thread | None = None

    # ── Public API ───────────────────────────────────────────────────────

    def start(self) -> None:
        """Start the health-monitoring loop in a background thread."""
        self._running = True
        self._monitor_thread = threading.Thread(target=self._monitor_loop, daemon=True)
        self._monitor_thread.start()
        self.logger.info("BotManager started")

    def stop(self) -> None:
        """Gracefully stop all bots and the manager."""
        self.logger.info("BotManager stopping – shutting down %d bots", len(self._bots))
        self._running = False

        for user_id, bot in list(self._bots.items()):
            bot.stop()

        # Wait for threads to finish (with timeout)
        for user_id, thread in list(self._threads.items()):
            thread.join(timeout=60)
            if thread.is_alive():
                self.logger.warning("Bot thread for user %d did not stop in time", user_id)

        self._bots.clear()
        self._threads.clear()
        self._restart_counts.clear()
        self.logger.info("BotManager stopped")

    def spawn_bot(
        self,
        user_id: int,
        broker: BaseBroker,
        strategy: dict[str, Any],
        constraints: dict[str, Any] | None = None,
        db_session: Any = None,
        analysis_id: int | None = None,
    ) -> None:
        """Create and start a TradingBot for *user_id*."""
        if user_id in self._bots:
            self.logger.warning("Bot for user %d already running – stopping old one", user_id)
            self.stop_bot(user_id)

        bot = TradingBot(
            user_id=user_id,
            broker=broker,
            redis_client=self.redis,
            strategy=strategy,
            constraints=constraints,
            db_session=db_session,
            analysis_id=analysis_id,
        )
        thread = threading.Thread(target=self._run_bot, args=(user_id, bot), daemon=True)

        self._bots[user_id] = bot
        self._threads[user_id] = thread
        self._restart_counts[user_id] = 0

        thread.start()
        self._publish_status(user_id, "running")
        self.logger.info("Spawned bot for user %d", user_id)

    def stop_bot(self, user_id: int) -> None:
        """Stop a specific user's bot."""
        bot = self._bots.pop(user_id, None)
        if bot:
            bot.stop()
        thread = self._threads.pop(user_id, None)
        if thread:
            thread.join(timeout=30)
        self._restart_counts.pop(user_id, None)
        self._publish_status(user_id, "stopped")
        self.logger.info("Stopped bot for user %d", user_id)

    def update_strategy(self, user_id: int, strategy: dict[str, Any]) -> bool:
        """Hot-update the strategy for a running bot.  Returns False if no bot found."""
        bot = self._bots.get(user_id)
        if bot is None:
            return False
        bot.update_strategy(strategy)
        return True

    def get_bot_results(self, user_id: int) -> dict[str, Any] | None:
        """Get execution results from a bot (for EOD summary)."""
        bot = self._bots.get(user_id)
        if bot is None:
            return None
        return bot.get_results()

    def get_status(self) -> dict[str, Any]:
        """Return a summary of all managed bots."""
        statuses: dict[str, Any] = {}
        for uid, bot in self._bots.items():
            thread = self._threads.get(uid)
            statuses[str(uid)] = {
                "running": bot._running,
                "thread_alive": thread.is_alive() if thread else False,
                "restarts": self._restart_counts.get(uid, 0),
            }
        return statuses

    # ── Internal ─────────────────────────────────────────────────────────

    def _run_bot(self, user_id: int, bot: TradingBot) -> None:
        """Thread target – runs the bot and catches crashes."""
        try:
            bot.start()
        except Exception as exc:
            self.logger.error("Bot for user %d crashed: %s", user_id, exc, exc_info=True)
            self._publish_status(user_id, "crashed")

    def _monitor_loop(self) -> None:
        """Periodically check bot health and restart crashed bots."""
        while self._running:
            time.sleep(self.HEALTH_CHECK_INTERVAL)
            if not self._running:
                break

            for user_id in list(self._threads.keys()):
                thread = self._threads.get(user_id)
                bot = self._bots.get(user_id)

                if thread is None or bot is None:
                    continue

                if not thread.is_alive() and bot._running:
                    # Thread died unexpectedly – attempt restart
                    restarts = self._restart_counts.get(user_id, 0)
                    if restarts >= self.MAX_RESTARTS:
                        self.logger.error(
                            "Bot for user %d exceeded max restarts (%d) – giving up",
                            user_id, self.MAX_RESTARTS,
                        )
                        self._publish_status(user_id, "failed")
                        self._bots.pop(user_id, None)
                        self._threads.pop(user_id, None)
                        continue

                    self.logger.warning(
                        "Bot for user %d died – restarting (attempt %d/%d)",
                        user_id, restarts + 1, self.MAX_RESTARTS,
                    )
                    self._restart_counts[user_id] = restarts + 1

                    new_bot = TradingBot(
                        user_id=user_id,
                        broker=bot.broker,
                        redis_client=bot.redis,
                        strategy=bot.strategy,
                        constraints=bot.constraints,
                    )
                    new_thread = threading.Thread(
                        target=self._run_bot, args=(user_id, new_bot), daemon=True,
                    )
                    self._bots[user_id] = new_bot
                    self._threads[user_id] = new_thread
                    new_thread.start()
                    self._publish_status(user_id, "restarted")

            # Publish overall manager status to Redis
            self.redis.set(
                "bot_manager:status",
                json.dumps({
                    "active_bots": len(self._bots),
                    "timestamp": datetime.now(timezone.utc).isoformat(),
                    "bots": self.get_status(),
                }),
                ex=120,
            )

    def _publish_status(self, user_id: int, status: str) -> None:
        """Publish bot status change to Redis for the Laravel dashboard."""
        self.redis.publish(
            f"trading:user:{user_id}:bot_status",
            json.dumps({
                "user_id": user_id,
                "status": status,
                "timestamp": datetime.now(timezone.utc).isoformat(),
            }),
        )
        self.redis.set(
            f"bot:status:{user_id}",
            status,
            ex=86400,
        )
