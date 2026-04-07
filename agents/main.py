"""
Main Orchestrator – entry point for the AI Trading Platform agents.

Coordinates:
- Scraper runner (background thread)
- Bot manager (background thread)
- Scheduled Claude analyses (daily, midday, EOD)
- Redis pub/sub listener for commands from the Laravel web app
- Graceful shutdown on SIGINT / SIGTERM
"""

from __future__ import annotations

import json
import logging
import signal
import sys
import threading
import time
from datetime import datetime, timezone
from typing import Any

import redis as redis_lib
from sqlalchemy import create_engine, text
from sqlalchemy.orm import Session, sessionmaker

from .config import Config
from .claude.engine import ClaudeEngine
from .scrapers.runner import ScraperRunner
from .bots.bot_manager import BotManager
from .brokers.paper import PaperBroker
from .brokers.alpaca import AlpacaBroker
from .brokers.binance_broker import BinanceBroker
from .brokers.xtb import XTBBroker
from .market_data.yfinance_source import YFinanceData


# ── Logging setup ────────────────────────────────────────────────────────

def _setup_logging() -> None:
    level = getattr(logging, Config.LOG_LEVEL.upper(), logging.INFO)
    logging.basicConfig(
        level=level,
        format="%(asctime)s [%(levelname)s] %(name)s: %(message)s",
        datefmt="%Y-%m-%d %H:%M:%S",
        handlers=[
            logging.StreamHandler(sys.stdout),
            logging.FileHandler("agents.log", encoding="utf-8"),
        ],
    )


logger = logging.getLogger(__name__)


# ── Database / Redis factories ───────────────────────────────────────────

def _create_db_session() -> Session:
    engine = create_engine(Config.get_db_url(), pool_pre_ping=True, pool_recycle=3600)
    factory = sessionmaker(bind=engine)
    return factory()


def _create_redis() -> redis_lib.Redis:  # type: ignore[type-arg]
    return redis_lib.Redis(
        host=Config.REDIS_HOST,
        port=Config.REDIS_PORT,
        password=Config.REDIS_PASSWORD or None,
        decode_responses=True,
    )


# ── Broker factory ───────────────────────────────────────────────────────

def _create_broker(user_cfg: dict[str, Any], db: Session) -> Any:
    """Create the appropriate broker based on user configuration."""
    broker_type = user_cfg.get("broker_type", "paper")

    if broker_type == "alpaca":
        broker = AlpacaBroker(
            api_key=user_cfg.get("alpaca_api_key", Config.ALPACA_API_KEY),
            secret_key=user_cfg.get("alpaca_secret_key", Config.ALPACA_SECRET_KEY),
            base_url=user_cfg.get("alpaca_base_url", Config.ALPACA_BASE_URL),
        )
    elif broker_type == "binance":
        broker = BinanceBroker(
            api_key=user_cfg.get("binance_api_key", Config.BINANCE_API_KEY),
            secret_key=user_cfg.get("binance_secret_key", Config.BINANCE_SECRET_KEY),
            testnet=Config.BINANCE_TESTNET,
        )
    elif broker_type == "xtb":
        broker = XTBBroker(
            user_id=user_cfg.get("xtb_user_id", Config.XTB_USER_ID),
            password=user_cfg.get("xtb_password", Config.XTB_PASSWORD),
            demo=Config.XTB_DEMO,
        )
    else:
        market_data = YFinanceData()
        broker = PaperBroker(
            db_session=db,
            user_id=user_cfg["user_id"],
            market_data_source=market_data,
            initial_balance=float(user_cfg.get("initial_balance", 100_000)),
        )

    broker.connect()
    return broker


# ── Data gathering ───────────────────────────────────────────────────────

def _gather_context(
    user_cfg: dict[str, Any],
    broker: Any,
    db: Session,
    redis_client: redis_lib.Redis,  # type: ignore[type-arg]
) -> dict[str, Any]:
    """Assemble the full context dict that the ClaudeEngine needs."""
    user_id = user_cfg["user_id"]
    today = datetime.now(timezone.utc).strftime("%Y-%m-%d")

    # Portfolio state
    account = broker.get_account_info()
    positions = broker.get_positions()
    portfolio = {
        "cash": account.cash,
        "total_equity": account.equity,
        "day_pnl": 0.0,
        "positions": [
            {
                "symbol": p.symbol,
                "quantity": p.quantity,
                "avg_cost": p.avg_cost,
                "current_price": p.current_price,
                "unrealized_pnl": p.unrealized_pnl,
            }
            for p in positions
        ],
    }

    # Market data – use cached prices from Redis if available
    market_data: dict[str, Any] = {}
    symbols = user_cfg.get("symbols", [])
    for sym in symbols:
        cached = redis_client.get(f"price:{sym}")
        if cached:
            market_data[sym] = {"close": float(cached)}

    # News from scraped_articles (join through scrapers to filter by user)
    rows = db.execute(
        text(
            "SELECT sa.id, sa.title, sa.content, sa.url, sa.source, "
            "       sa.sentiment_score, sa.symbols "
            "FROM scraped_articles sa "
            "JOIN scrapers s ON s.id = sa.scraper_id "
            "WHERE s.user_id = :uid AND sa.fed_to_claude = 0 "
            "ORDER BY sa.created_at DESC LIMIT 30"
        ),
        {"uid": user_id},
    ).fetchall()

    news = []
    article_ids: list[int] = []
    for r in rows:
        article_ids.append(r[0])
        syms = []
        if r[6]:
            try:
                syms = json.loads(r[6]) if isinstance(r[6], str) else r[6]
            except (json.JSONDecodeError, TypeError):
                pass
        news.append({
            "title": r[1],
            "content": r[2][:500] if r[2] else "",
            "url": r[3],
            "source": r[4],
            "sentiment_score": r[5],
            "symbols": syms,
        })

    # Mark articles as fed
    if article_ids:
        placeholders = ", ".join(f":id_{i}" for i in range(len(article_ids)))
        params = {f"id_{i}": aid for i, aid in enumerate(article_ids)}
        db.execute(
            text(
                f"UPDATE scraped_articles SET fed_to_claude = 1 "
                f"WHERE id IN ({placeholders})"
            ),
            params,
        )
        db.commit()

    # Yesterday's results
    yesterday_row = db.execute(
        text(
            "SELECT strategy_json, market_outlook, confidence "
            "FROM claude_analyses "
            "WHERE user_id = :uid AND date < :today "
            "ORDER BY date DESC LIMIT 1"
        ),
        {"uid": user_id, "today": today},
    ).fetchone()

    yesterday: dict[str, Any] = {}
    if yesterday_row and yesterday_row[0]:
        try:
            yesterday = json.loads(yesterday_row[0]) if isinstance(yesterday_row[0], str) else {}
        except (json.JSONDecodeError, TypeError):
            pass

    # Constraints
    constraints = {
        "max_position_pct": user_cfg.get("max_position_pct", 20),
        "max_daily_loss": user_cfg.get("max_daily_loss", 1000),
        "max_trades": user_cfg.get("max_trades", 10),
        "allowed_symbols": user_cfg.get("allowed_symbols", "any"),
        "forbidden_symbols": user_cfg.get("forbidden_symbols", []),
    }

    return {
        "user_id": user_id,
        "date": today,
        "user_profile": {
            "risk_tolerance": user_cfg.get("risk_tolerance", "moderate"),
            "markets": user_cfg.get("markets", ["US equities"]),
            "capital": account.equity,
            "trading_style": user_cfg.get("trading_style", "swing"),
            "custom_instructions": user_cfg.get("custom_instructions", ""),
        },
        "portfolio": portfolio,
        "market_data": market_data,
        "news": news,
        "yesterday_results": yesterday,
        "constraints": constraints,
    }


# ── User config loader ──────────────────────────────────────────────────

def _load_user_configs(db: Session) -> list[dict[str, Any]]:
    """Load active trading agent configurations from the database."""
    rows = db.execute(
        text(
            "SELECT u.id, u.name, ta.broker_type, ta.settings, ta.is_active, "
            "       ta.analysis_schedule, ta.midday_review_enabled "
            "FROM users u "
            "JOIN trading_agents ta ON ta.user_id = u.id "
            "WHERE ta.is_active = 1"
        )
    ).fetchall()

    configs: list[dict[str, Any]] = []
    for r in rows:
        settings: dict[str, Any] = {}
        if r[3]:
            try:
                settings = json.loads(r[3]) if isinstance(r[3], str) else r[3]
            except (json.JSONDecodeError, TypeError):
                pass

        configs.append({
            "user_id": r[0],
            "name": r[1],
            "broker_type": r[2] or "paper",
            "analysis_schedule": r[5] or "09:30",
            "midday_review_enabled": bool(r[6]),
            **settings,
        })

    return configs


# ── Redis command listener ───────────────────────────────────────────────

def _redis_listener(
    redis_client: redis_lib.Redis,  # type: ignore[type-arg]
    bot_manager: BotManager,
    claude_engine: ClaudeEngine,
    db: Session,
    shutdown_event: threading.Event,
) -> None:
    """Listen for commands from the Laravel app via Redis pub/sub."""
    pubsub = redis_client.pubsub()
    pubsub.subscribe("trading:commands")
    log = logging.getLogger(f"{__name__}.redis_listener")
    log.info("Redis command listener started")

    while not shutdown_event.is_set():
        try:
            message = pubsub.get_message(timeout=1.0)
            if message and message["type"] == "message":
                try:
                    data = json.loads(message["data"])
                except (json.JSONDecodeError, TypeError):
                    continue

                command = data.get("command")
                user_id = data.get("user_id")
                log.info("Received command: %s for user %s", command, user_id)

                if command == "trigger_analysis" and user_id:
                    # Manual trigger from dashboard
                    _handle_manual_trigger(user_id, claude_engine, bot_manager, db, redis_client)

                elif command == "stop_bot" and user_id:
                    bot_manager.stop_bot(int(user_id))

                elif command == "update_config" and user_id:
                    log.info("Config update for user %s – will apply on next cycle", user_id)

                elif command == "shutdown":
                    log.info("Shutdown command received via Redis")
                    shutdown_event.set()

        except Exception as exc:
            log.error("Redis listener error: %s", exc)
            time.sleep(1)

    pubsub.unsubscribe()
    pubsub.close()
    log.info("Redis command listener stopped")


def _handle_manual_trigger(
    user_id: int,
    claude_engine: ClaudeEngine,
    bot_manager: BotManager,
    db: Session,
    redis_client: redis_lib.Redis,  # type: ignore[type-arg]
) -> None:
    """Handle a manually triggered analysis from the Laravel dashboard."""
    log = logging.getLogger(f"{__name__}.manual_trigger")
    try:
        configs = _load_user_configs(db)
        user_cfg = next((c for c in configs if c["user_id"] == user_id), None)
        if not user_cfg:
            log.warning("No config found for user %d", user_id)
            return

        broker = _create_broker(user_cfg, db)
        context = _gather_context(user_cfg, broker, db, redis_client)
        strategy = claude_engine.daily_analysis(context)

        bot_manager.spawn_bot(
            user_id=user_id,
            broker=broker,
            strategy=strategy,
            constraints=context.get("constraints"),
        )
        log.info("Manual analysis + bot spawn complete for user %d", user_id)
    except Exception as exc:
        log.error("Manual trigger failed for user %d: %s", user_id, exc, exc_info=True)


# ── Main ─────────────────────────────────────────────────────────────────

def main() -> None:
    """Main entry point for the trading platform agents."""
    _setup_logging()
    logger.info("AI Trading Platform agents starting")

    # Core connections
    db = _create_db_session()
    try:
        redis_client = _create_redis()
        redis_client.ping()
        logger.info("Redis connected at %s:%s", Config.REDIS_HOST, Config.REDIS_PORT)
    except Exception as exc:
        logger.warning("Redis not available (%s) – running without real-time features", exc)
        redis_client = None

    claude_engine = ClaudeEngine(db_session=db, binary_path=Config.CLAUDE_BINARY_PATH)
    scraper_runner = ScraperRunner(db_session=db)
    bot_manager = BotManager(redis_client=redis_client)

    shutdown_event = threading.Event()

    # Signal handling
    def _signal_handler(signum: int, frame: Any) -> None:
        logger.info("Received signal %d – initiating shutdown", signum)
        shutdown_event.set()

    signal.signal(signal.SIGINT, _signal_handler)
    if hasattr(signal, "SIGTERM"):
        signal.signal(signal.SIGTERM, _signal_handler)

    # Start bot manager health monitor
    bot_manager.start()

    # Background thread: scraper runner
    def _scraper_loop() -> None:
        while not shutdown_event.is_set():
            try:
                scraper_runner.run_all()
            except Exception as exc:
                logger.error("Scraper run failed: %s", exc, exc_info=True)
            # Wait 5 minutes between scraper cycles
            shutdown_event.wait(300)

    scraper_thread = threading.Thread(target=_scraper_loop, daemon=True)
    scraper_thread.start()

    # Background thread: Redis command listener (only if Redis is available)
    if redis_client is not None:
        listener_thread = threading.Thread(
            target=_redis_listener,
            args=(redis_client, bot_manager, claude_engine, db, shutdown_event),
            daemon=True,
        )
        listener_thread.start()
    else:
        logger.info("Skipping Redis listener – Redis not available")

    logger.info("All background services started – entering main scheduling loop")

    # ── Main scheduling loop ─────────────────────────────────────────────
    analysis_done_today: set[int] = set()
    midday_done_today: set[int] = set()
    eod_done_today: set[int] = set()
    last_date = ""

    while not shutdown_event.is_set():
        now = datetime.now(timezone.utc)
        current_date = now.strftime("%Y-%m-%d")
        current_time = now.strftime("%H:%M")

        # Reset tracking sets at midnight
        if current_date != last_date:
            analysis_done_today.clear()
            midday_done_today.clear()
            eod_done_today.clear()
            last_date = current_date

        try:
            user_configs = _load_user_configs(db)
        except Exception as exc:
            logger.error("Failed to load user configs: %s", exc)
            shutdown_event.wait(60)
            continue

        for user_cfg in user_configs:
            user_id = user_cfg["user_id"]

            # ── Morning analysis ─────────────────────────────────────────
            scheduled_time = user_cfg.get("analysis_schedule", "09:30")
            if current_time >= scheduled_time and user_id not in analysis_done_today:
                logger.info("Running daily analysis for user %d", user_id)
                try:
                    broker = _create_broker(user_cfg, db)
                    context = _gather_context(user_cfg, broker, db, redis_client)
                    strategy = claude_engine.daily_analysis(context)

                    bot_manager.spawn_bot(
                        user_id=user_id,
                        broker=broker,
                        strategy=strategy,
                        constraints=context.get("constraints"),
                    )
                    analysis_done_today.add(user_id)
                    logger.info("Daily analysis complete for user %d", user_id)
                except Exception as exc:
                    logger.error(
                        "Daily analysis failed for user %d: %s", user_id, exc, exc_info=True,
                    )
                    analysis_done_today.add(user_id)  # avoid retry storm

            # ── Midday review ────────────────────────────────────────────
            if (
                user_cfg.get("midday_review_enabled")
                and current_time >= "12:00"
                and user_id not in midday_done_today
                and user_id in analysis_done_today
            ):
                logger.info("Running midday review for user %d", user_id)
                try:
                    results = bot_manager.get_bot_results(user_id)
                    if results:
                        broker_inst = bot_manager._bots[user_id].broker if user_id in bot_manager._bots else None
                        if broker_inst:
                            positions = broker_inst.get_positions()
                            midday_context = {
                                "user_id": user_id,
                                "positions": [
                                    {
                                        "symbol": p.symbol,
                                        "quantity": p.quantity,
                                        "avg_cost": p.avg_cost,
                                        "current_price": p.current_price,
                                        "unrealized_pnl": p.unrealized_pnl,
                                    }
                                    for p in positions
                                ],
                                "market_update": {},
                                "original_strategy": bot_manager._bots[user_id].strategy,
                                "constraints": user_cfg,
                            }
                            updated = claude_engine.midday_review(midday_context)
                            if updated.get("trades"):
                                bot_manager.update_strategy(user_id, updated)
                    midday_done_today.add(user_id)
                except Exception as exc:
                    logger.error("Midday review failed for user %d: %s", user_id, exc)
                    midday_done_today.add(user_id)

            # ── End of day ───────────────────────────────────────────────
            eod_time = user_cfg.get("eod_time", "16:00")
            if (
                current_time >= eod_time
                and user_id not in eod_done_today
                and user_id in analysis_done_today
            ):
                logger.info("Running end-of-day for user %d", user_id)
                try:
                    results = bot_manager.get_bot_results(user_id)
                    if results:
                        summary = claude_engine.end_of_day_summary(results)
                        # Store EOD summary as a bot log entry
                        db.execute(
                            text(
                                "INSERT INTO bot_logs "
                                "(user_id, level, message, context, created_at) "
                                "VALUES (:uid, :level, :message, :context, :now)"
                            ),
                            {
                                "uid": user_id,
                                "level": "info",
                                "message": f"End-of-day summary: {summary[:500]}",
                                "context": json.dumps({
                                    "type": "eod_summary",
                                    "full_summary": summary,
                                    "day_pnl": results.get("day_pnl", 0),
                                    "date": current_date,
                                }),
                                "now": now,
                            },
                        )
                        db.commit()

                        # Publish to Redis for Laravel (if available)
                        if redis_client is not None:
                            redis_client.publish(
                                f"trading:user:{user_id}:eod",
                                json.dumps({
                                    "date": current_date,
                                    "summary": summary[:1000],
                                    "pnl": results.get("day_pnl", 0),
                                }),
                            )

                    # Stop the bot for the day
                    bot_manager.stop_bot(user_id)
                    eod_done_today.add(user_id)
                except Exception as exc:
                    logger.error("EOD failed for user %d: %s", user_id, exc)
                    eod_done_today.add(user_id)

        # Sleep until next check (30 seconds)
        shutdown_event.wait(30)

    # ── Shutdown ─────────────────────────────────────────────────────────
    logger.info("Shutting down…")
    bot_manager.stop()
    db.close()
    redis_client.close()
    logger.info("Shutdown complete")


if __name__ == "__main__":
    main()
