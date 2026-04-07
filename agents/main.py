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

_engine = None
_session_factory = None


def _init_db() -> None:
    """Initialize the shared engine and session factory (call once at startup)."""
    global _engine, _session_factory
    db_url = Config.get_db_url()
    connect_args = {}
    if "sqlite" in db_url:
        connect_args["check_same_thread"] = False
    _engine = create_engine(db_url, pool_pre_ping=True, pool_recycle=3600, connect_args=connect_args)
    # Enable WAL mode for SQLite to allow concurrent reads during writes
    if "sqlite" in db_url:
        with _engine.connect() as conn:
            conn.execute(text("PRAGMA journal_mode=WAL"))
            conn.execute(text("PRAGMA busy_timeout=5000"))
    _session_factory = sessionmaker(bind=_engine)


def _create_db_session() -> Session:
    """Create a new DB session (safe to call from any thread)."""
    if _session_factory is None:
        _init_db()
    return _session_factory()


def _create_redis() -> redis_lib.Redis:  # type: ignore[type-arg]
    return redis_lib.Redis(
        host=Config.REDIS_HOST,
        port=Config.REDIS_PORT,
        password=Config.REDIS_PASSWORD or None,
        decode_responses=True,
    )


# ── Broker factory ───────────────────────────────────────────────────────

def _create_paper_broker(user_cfg: dict[str, Any], db: Session) -> Any:
    """Create a paper broker as default/fallback."""
    market_data = YFinanceData()
    broker = PaperBroker(
        db_session=db,
        user_id=user_cfg["user_id"],
        market_data_source=market_data,
        initial_balance=float(user_cfg.get("initial_balance", 100_000)),
    )
    broker.connect()
    return broker


def _create_broker(user_cfg: dict[str, Any], db: Session) -> Any:
    """Create the appropriate broker. Falls back to paper if keys are missing or connection fails."""
    broker_type = user_cfg.get("broker_type", "paper")
    creds = user_cfg.get("broker_credentials", {})

    if broker_type == "paper":
        return _create_paper_broker(user_cfg, db)

    # For real brokers, check if credentials exist before attempting connection
    try:
        if broker_type == "alpaca":
            api_key = creds.get("api_key") or Config.ALPACA_API_KEY
            secret_key = creds.get("api_secret") or Config.ALPACA_SECRET_KEY
            if not api_key or not secret_key:
                logger.warning("Alpaca API keys not configured — falling back to paper broker")
                return _create_paper_broker(user_cfg, db)
            broker = AlpacaBroker(
                api_key=api_key,
                secret_key=secret_key,
                base_url=creds.get("base_url") or Config.ALPACA_BASE_URL,
            )

        elif broker_type == "binance":
            api_key = creds.get("api_key") or Config.BINANCE_API_KEY
            secret_key = creds.get("api_secret") or Config.BINANCE_SECRET_KEY
            if not api_key or not secret_key:
                logger.warning("Binance API keys not configured — falling back to paper broker")
                return _create_paper_broker(user_cfg, db)
            broker = BinanceBroker(
                api_key=api_key,
                secret_key=secret_key,
                testnet=creds.get("testnet", Config.BINANCE_TESTNET),
            )

        elif broker_type == "xtb":
            user_id_xtb = creds.get("user_id") or Config.XTB_USER_ID
            password = creds.get("password") or Config.XTB_PASSWORD
            if not user_id_xtb or not password:
                logger.warning("XTB credentials not configured — falling back to paper broker")
                return _create_paper_broker(user_cfg, db)
            broker = XTBBroker(
                user_id=user_id_xtb,
                password=password,
                demo=creds.get("demo", Config.XTB_DEMO),
            )

        else:
            logger.warning("Unknown broker type '%s' — using paper broker", broker_type)
            return _create_paper_broker(user_cfg, db)

        broker.connect()
        logger.info("Connected to %s broker", broker_type)
        return broker

    except Exception as exc:
        logger.warning(
            "Failed to connect to %s broker (%s) — falling back to paper broker",
            broker_type, exc,
        )
        return _create_paper_broker(user_cfg, db)


# ── Data gathering ───────────────────────────────────────────────────────

def _gather_context(
    user_cfg: dict[str, Any],
    broker: Any,
    db: Session,
    redis_client: Any = None,
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

    # Market data – load OHLCV from stock_prices + compute indicators
    market_data: dict[str, Any] = {}

    # Get watchlist symbols for this user
    watchlist_rows = db.execute(
        text(
            "SELECT wi.symbol FROM watchlist_items wi "
            "JOIN watchlists w ON w.id = wi.watchlist_id "
            "WHERE w.user_id = :uid"
        ),
        {"uid": user_id},
    ).fetchall()
    symbols = [r[0] for r in watchlist_rows]

    for sym in symbols:
        # Last 30 days OHLCV
        ohlcv_rows = db.execute(
            text(
                "SELECT date, open, high, low, close, volume "
                "FROM stock_prices WHERE symbol = :sym "
                "ORDER BY date DESC LIMIT 30"
            ),
            {"sym": sym},
        ).fetchall()

        if not ohlcv_rows:
            market_data[sym] = {"note": "no historical data available"}
            continue

        closes = [float(r[4]) / 100.0 for r in reversed(ohlcv_rows)]  # cents to dollars
        latest = ohlcv_rows[0]

        # Compute technical indicators
        indicators: dict[str, Any] = {}
        try:
            from .indicators.technical import sma, rsi, macd, bollinger_bands
            if len(closes) >= 20:
                sma20 = sma(closes, 20)
                indicators["SMA_20"] = round(sma20[-1], 2) if sma20 else None
            if len(closes) >= 14:
                rsi_values = rsi(closes, 14)
                indicators["RSI_14"] = round(rsi_values[-1], 2) if rsi_values else None
            if len(closes) >= 26:
                macd_data = macd(closes)
                indicators["MACD"] = round(macd_data["macd"][-1], 4) if macd_data["macd"] else None
                indicators["MACD_signal"] = round(macd_data["signal"][-1], 4) if macd_data["signal"] else None
            if len(closes) >= 20:
                bb = bollinger_bands(closes, 20)
                indicators["BB_upper"] = round(bb["upper"][-1], 2) if bb["upper"] else None
                indicators["BB_lower"] = round(bb["lower"][-1], 2) if bb["lower"] else None
        except Exception:
            pass  # indicators are optional

        # Volume anomaly check (today vs 20-day avg)
        volumes = [float(r[5]) for r in reversed(ohlcv_rows)]
        avg_volume = sum(volumes[:-1]) / max(len(volumes) - 1, 1) if len(volumes) > 1 else volumes[0]
        current_volume = volumes[-1] if volumes else 0
        volume_anomaly = None
        if avg_volume > 0 and current_volume > avg_volume * 2:
            volume_anomaly = f"{current_volume/avg_volume:.1f}x average"

        # Last 5 days OHLCV for prompt
        recent_days = []
        for r in ohlcv_rows[:5]:
            recent_days.append({
                "date": str(r[0]),
                "open": float(r[1]) / 100,
                "high": float(r[2]) / 100,
                "low": float(r[3]) / 100,
                "close": float(r[4]) / 100,
                "volume": int(r[5]),
            })

        market_data[sym] = {
            "open": float(latest[1]) / 100,
            "high": float(latest[2]) / 100,
            "low": float(latest[3]) / 100,
            "close": float(latest[4]) / 100,
            "volume": int(latest[5]),
            "recent_days": recent_days,
            "indicators": indicators,
            "volume_anomaly": volume_anomaly,
        }

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

    # Recent trade history (last 20 trades from DB)
    trade_rows = db.execute(
        text(
            "SELECT t.symbol, t.side, t.quantity, t.price_cents, t.pnl_cents, "
            "       t.fee_cents, t.executed_at "
            "FROM trades t "
            "JOIN portfolios p ON p.id = t.portfolio_id "
            "WHERE p.user_id = :uid "
            "ORDER BY t.executed_at DESC LIMIT 20"
        ),
        {"uid": user_id},
    ).fetchall()

    recent_trades = []
    for r in trade_rows:
        recent_trades.append({
            "symbol": r[0],
            "side": r[1],
            "quantity": float(r[2]),
            "price": float(r[3]) / 100.0,
            "pnl": float(r[4]) / 100.0 if r[4] else 0,
            "fee": float(r[5]) / 100.0 if r[5] else 0,
            "executed_at": str(r[6]),
        })

    # Pending trades from active strategies (so Claude knows what's already planned)
    pending_rows = db.execute(
        text(
            "SELECT cat.symbol, cat.action, cat.quantity, cat.entry_price_cents, "
            "       cat.stop_loss_cents, cat.take_profit_cents, cat.status, cat.urgency "
            "FROM claude_analysis_trades cat "
            "JOIN claude_analyses ca ON ca.id = cat.claude_analysis_id "
            "WHERE ca.user_id = :uid AND ca.status = 'active' AND cat.status = 'pending'"
        ),
        {"uid": user_id},
    ).fetchall()

    pending_trades = []
    for r in pending_rows:
        pending_trades.append({
            "symbol": r[0], "action": r[1], "quantity": float(r[2]),
            "entry_price": float(r[3]) / 100 if r[3] else None,
            "stop_loss": float(r[4]) / 100 if r[4] else None,
            "take_profit": float(r[5]) / 100 if r[5] else None,
            "status": r[6], "urgency": r[7],
        })

    # Constraints — only allow symbols from the user's watchlist
    constraints = {
        "max_position_pct": user_cfg.get("max_position_pct", 20),
        "max_daily_loss_pct": user_cfg.get("max_daily_loss_pct", 5),
        "max_trades": user_cfg.get("max_daily_trades", 10),
        "allowed_symbols": symbols if symbols else "any",
    }

    return {
        "user_id": user_id,
        "date": today,
        "user_profile": {
            "risk_tolerance": user_cfg.get("risk_tolerance", "moderate"),
            "markets": user_cfg.get("preferred_markets", ["us_stocks"]),
            "capital": account.equity,
            "custom_instructions": user_cfg.get("custom_instructions", ""),
        },
        "portfolio": portfolio,
        "market_data": market_data,
        "news": news,
        "recent_trades": recent_trades,
        "pending_trades": pending_trades,
        "yesterday_results": yesterday,
        "constraints": constraints,
        "broker_type": user_cfg.get("broker_type", "paper"),
        "strategy_style": user_cfg.get("strategy_style", "day_trading"),
        "custom_notes": user_cfg.get("custom_notes", ""),
    }


# ── Broker balance sync ─────────────────────────────────────────────────

def _sync_broker_balance(user_id: int, broker: Any, db: Session) -> None:
    """Sync real broker state (balance, positions, trades) to DB for Laravel to display."""
    try:
        account = broker.get_account_info()
        balance_cents = int(round(account.cash * 100))
        equity_cents = int(round(account.equity * 100))

        # Get portfolio ID
        row = db.execute(
            text("SELECT id FROM portfolios WHERE user_id = :uid LIMIT 1"),
            {"uid": user_id},
        ).fetchone()
        if not row:
            return
        portfolio_id = row[0]

        # Update portfolio balance (cash only, NOT equity — dashboard adds positions separately)
        db.execute(
            text("UPDATE portfolios SET balance_cents = :balance WHERE id = :pid"),
            {"balance": balance_cents, "pid": portfolio_id},
        )

        # Sync positions from broker
        positions = broker.get_positions()
        now = datetime.now(timezone.utc)

        # Get existing open positions in DB
        existing = db.execute(
            text("SELECT id, symbol FROM positions WHERE portfolio_id = :pid AND closed_at IS NULL"),
            {"pid": portfolio_id},
        ).fetchall()
        existing_symbols = {r[1]: r[0] for r in existing}

        broker_symbols = set()
        for pos in positions:
            broker_symbols.add(pos.symbol)
            price_cents = int(round(pos.current_price * 100))
            entry_cents = int(round(pos.avg_cost * 100)) if pos.avg_cost else price_cents
            qty = pos.quantity

            if pos.symbol in existing_symbols:
                # Update existing position
                db.execute(
                    text(
                        "UPDATE positions SET current_price_cents = :price, "
                        "quantity = :qty, avg_entry_price_cents = :entry "
                        "WHERE id = :id"
                    ),
                    {"price": price_cents, "qty": qty, "entry": entry_cents, "id": existing_symbols[pos.symbol]},
                )
            else:
                # Insert new position from broker
                db.execute(
                    text(
                        "INSERT INTO positions (portfolio_id, symbol, quantity, avg_entry_price_cents, "
                        "current_price_cents, side, opened_at, created_at, updated_at) "
                        "VALUES (:pid, :sym, :qty, :entry, :price, 'long', :now, :now, :now)"
                    ),
                    {"pid": portfolio_id, "sym": pos.symbol, "qty": qty,
                     "entry": entry_cents, "price": price_cents, "now": now},
                )

        # Close positions that no longer exist on broker
        for sym, pos_id in existing_symbols.items():
            if sym not in broker_symbols:
                db.execute(
                    text("UPDATE positions SET closed_at = :now WHERE id = :id"),
                    {"now": now, "id": pos_id},
                )

        # Sync recent trades from broker (if broker supports it)
        try:
            _sync_broker_trades(user_id, portfolio_id, broker, db)
        except Exception:
            pass  # Not all brokers support trade history

        db.commit()
        logger.debug(
            "Synced user %d: $%.2f equity, %d positions",
            user_id, account.equity, len(positions),
        )
    except Exception as exc:
        logger.warning("Balance sync failed for user %d: %s", user_id, exc)


def _sync_broker_trades(user_id: int, portfolio_id: int, broker: Any, db: Session) -> None:
    """Sync trade history from broker to the trades table."""
    # Get the most recent trade timestamp we have
    last_row = db.execute(
        text("SELECT MAX(executed_at) FROM trades WHERE portfolio_id = :pid"),
        {"pid": portfolio_id},
    ).fetchone()
    last_synced = last_row[0] if last_row and last_row[0] else None

    # For Binance, fetch trades per symbol from positions
    if not hasattr(broker, 'client'):
        return

    now = datetime.now(timezone.utc)

    # Get symbols we care about (from positions + recent orders)
    symbols_to_check = set()
    positions = broker.get_positions()
    for pos in positions:
        symbols_to_check.add(pos.symbol)

    # Also check common symbols
    for sym in symbols_to_check:
        try:
            binance_sym = sym.replace("-USD", "USDT")
            if not binance_sym.endswith("USDT"):
                binance_sym += "USDT"

            raw_trades = broker.client.get_my_trades(symbol=binance_sym, limit=20)
            for t in raw_trades:
                trade_time = datetime.fromtimestamp(t["time"] / 1000, tz=timezone.utc)

                # Skip if we already have this trade (check by time + symbol)
                if last_synced and str(trade_time) <= str(last_synced):
                    continue

                side = "buy" if t["isBuyer"] else "sell"
                price_cents = int(round(float(t["price"]) * 100))
                qty = float(t["qty"])
                fee_cents = int(round(float(t["commission"]) * float(t.get("price", 1)) * 100))

                # Create order record
                order_id = db.execute(
                    text(
                        "INSERT INTO orders (portfolio_id, symbol, type, side, quantity, "
                        "price_cents, status, filled_at, created_at, updated_at) "
                        "VALUES (:pid, :sym, 'market', :side, :qty, :price, 'filled', :time, :time, :time)"
                    ),
                    {"pid": portfolio_id, "sym": sym, "side": side, "qty": qty,
                     "price": price_cents, "time": trade_time},
                ).lastrowid

                # Create trade record
                db.execute(
                    text(
                        "INSERT INTO trades (order_id, portfolio_id, symbol, side, quantity, "
                        "price_cents, fee_cents, executed_at, created_at, updated_at) "
                        "VALUES (:oid, :pid, :sym, :side, :qty, :price, :fee, :time, :time, :time)"
                    ),
                    {"oid": order_id, "pid": portfolio_id, "sym": sym, "side": side,
                     "qty": qty, "price": price_cents, "fee": fee_cents, "time": trade_time},
                )
        except Exception:
            continue


# ── User config loader ──────────────────────────────────────────────────

def _load_user_configs(db: Session) -> list[dict[str, Any]]:
    """Load active user configurations from user_settings + broker_credentials."""
    rows = db.execute(
        text(
            "SELECT u.id, u.name, "
            "       us.risk_tolerance, us.max_daily_trades, us.max_position_pct, "
            "       us.max_daily_loss_pct, us.preferred_markets, us.custom_instructions, "
            "       us.analysis_schedule, us.auto_execute, us.timezone, "
            "       bc.broker, bc.credentials, "
            "       us.strategy_style "
            "FROM users u "
            "JOIN user_settings us ON us.user_id = u.id "
            "LEFT JOIN broker_credentials bc ON bc.user_id = u.id AND bc.is_active = 1"
        )
    ).fetchall()

    configs: list[dict[str, Any]] = []
    for r in rows:
        # Parse broker credentials JSON
        broker_creds: dict[str, Any] = {}
        if r[12]:
            try:
                broker_creds = json.loads(r[12]) if isinstance(r[12], str) else {}
            except (json.JSONDecodeError, TypeError):
                pass

        # Parse preferred markets JSON
        markets: list[str] = []
        if r[6]:
            try:
                markets = json.loads(r[6]) if isinstance(r[6], str) else []
            except (json.JSONDecodeError, TypeError):
                pass

        # Map analysis_schedule to a time string
        schedule_map = {
            "premarket": "08:00",
            "market_open": "09:30",
            "midday": "12:00",
            "market_close": "16:00",
        }
        schedule_val = r[8] or "premarket"
        analysis_time = schedule_map.get(schedule_val, schedule_val)

        configs.append({
            "user_id": r[0],
            "name": r[1],
            "risk_tolerance": r[2] or "moderate",
            "max_daily_trades": r[3] or 10,
            "max_position_pct": r[4] or 20,
            "max_daily_loss_pct": r[5] or 5,
            "preferred_markets": markets,
            "custom_instructions": r[7] or "",
            "analysis_schedule": analysis_time,
            "auto_execute": bool(r[9]),
            "timezone": r[10] or "America/New_York",
            "broker_type": r[11] or "paper",
            "broker_credentials": broker_creds,
            "midday_review_enabled": schedule_val in ("midday", "every_4_hours"),
            "strategy_style": r[13] if len(r) > 13 and r[13] else "day_trading",
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
                    _handle_manual_trigger(
                        user_id, claude_engine, bot_manager, db, redis_client,
                        strategy_style=data.get("strategy_style"),
                        custom_notes=data.get("custom_notes", ""),
                    )

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
    strategy_style: str | None = None,
    custom_notes: str = "",
) -> None:
    """Handle a manually triggered analysis from the Laravel dashboard."""
    log = logging.getLogger(f"{__name__}.manual_trigger")
    try:
        configs = _load_user_configs(db)
        user_cfg = next((c for c in configs if c["user_id"] == user_id), None)
        if not user_cfg:
            log.warning("No config found for user %d", user_id)
            return

        # Override strategy style and custom notes from the trigger request
        if strategy_style:
            user_cfg["strategy_style"] = strategy_style
        if custom_notes:
            user_cfg["custom_notes"] = custom_notes

        broker = _create_broker(user_cfg, db)
        context = _gather_context(user_cfg, broker, db, redis_client)
        strategy = claude_engine.daily_analysis(context)
        analysis_id = strategy.pop("_analysis_id", None)

        bot_db = _create_db_session()  # separate session for bot thread
        bot_manager.spawn_bot(
            user_id=user_id,
            broker=broker,
            strategy=strategy,
            constraints=context.get("constraints"),
            db_session=bot_db,
            analysis_id=analysis_id,
        )
        log.info("Manual analysis + bot spawn complete for user %d", user_id)
    except Exception as exc:
        log.error("Manual trigger failed for user %d: %s", user_id, exc, exc_info=True)


# ── Main ─────────────────────────────────────────────────────────────────

def main() -> None:
    """Main entry point for the trading platform agents."""
    _setup_logging()
    logger.info("AI Trading Platform agents starting")

    # Initialize DB engine (shared, thread-safe)
    _init_db()

    # Redis
    try:
        redis_client = _create_redis()
        redis_client.ping()
        logger.info("Redis connected at %s:%s", Config.REDIS_HOST, Config.REDIS_PORT)
    except Exception as exc:
        logger.warning("Redis not available (%s) – running without real-time features", exc)
        redis_client = None

    # Each component gets its own DB session
    main_db = _create_db_session()
    claude_engine = ClaudeEngine(db_session=main_db, binary_path=Config.CLAUDE_BINARY_PATH)
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

    # Background thread: scraper runner (own DB session)
    def _scraper_loop() -> None:
        scraper_db = _create_db_session()
        scraper_runner = ScraperRunner(db_session=scraper_db)
        while not shutdown_event.is_set():
            try:
                scraper_runner.run_all()
            except Exception as exc:
                logger.error("Scraper run failed: %s", exc, exc_info=True)
            shutdown_event.wait(300)
        scraper_db.close()

    scraper_thread = threading.Thread(target=_scraper_loop, daemon=True)
    scraper_thread.start()

    # Background thread: Redis command listener (own DB session, only if Redis available)
    if redis_client is not None:
        listener_db = _create_db_session()
        listener_engine = ClaudeEngine(db_session=listener_db, binary_path=Config.CLAUDE_BINARY_PATH)
        listener_thread = threading.Thread(
            target=_redis_listener,
            args=(redis_client, bot_manager, listener_engine, listener_db, shutdown_event),
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
    user_brokers: dict[int, Any] = {}  # cached broker instances per user
    last_balance_sync: dict[int, datetime] = {}  # last sync time per user
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
            user_brokers.clear()  # reconnect brokers daily
            last_date = current_date

        try:
            user_configs = _load_user_configs(main_db)
        except Exception as exc:
            logger.error("Failed to load user configs: %s", exc)
            shutdown_event.wait(60)
            continue

        for user_cfg in user_configs:
            user_id = user_cfg["user_id"]

            # ── Get or create broker (cached per user) ──────────────────
            if user_id not in user_brokers:
                try:
                    user_brokers[user_id] = _create_broker(user_cfg, main_db)
                except Exception as exc:
                    logger.warning("Broker creation failed for user %d: %s", user_id, exc)
                    continue

            broker = user_brokers[user_id]

            # ── Sync broker balance + agent health (once per minute) ───
            if user_id not in last_balance_sync or (datetime.now(timezone.utc) - last_balance_sync[user_id]).seconds >= 60:
                _sync_broker_balance(user_id, broker, main_db)
                last_balance_sync[user_id] = datetime.now(timezone.utc)

                # Update agent_health for this user's bot
                bot_running = user_id in bot_manager._bots
                try:
                    existing = main_db.execute(
                        text("SELECT id FROM agent_health WHERE agent_type='trading_bot' AND user_id=:uid"),
                        {"uid": user_id},
                    ).fetchone()
                    if existing:
                        main_db.execute(
                            text("UPDATE agent_health SET status=:status, last_heartbeat_at=:now, metadata=:meta WHERE id=:id"),
                            {"status": "running" if bot_running else "stopped", "now": now, "meta": json.dumps({"broker": user_cfg.get("broker_type", "paper")}), "id": existing[0]},
                        )
                    else:
                        main_db.execute(
                            text("INSERT INTO agent_health (agent_type, agent_id, user_id, status, last_heartbeat_at, metadata) VALUES ('trading_bot', :aid, :uid, :status, :now, :meta)"),
                            {"aid": f"bot-user-{user_id}", "uid": user_id, "status": "running" if bot_running else "stopped", "now": now, "meta": json.dumps({"broker": user_cfg.get("broker_type", "paper")})},
                        )
                    main_db.commit()
                except Exception:
                    pass

            # ── Check for pending analyses in DB (fallback if Redis missed the trigger)
            pending_row = main_db.execute(
                text(
                    "SELECT id FROM claude_analyses "
                    "WHERE user_id = :uid AND status = 'pending' "
                    "ORDER BY created_at DESC LIMIT 1"
                ),
                {"uid": user_id},
            ).fetchone()

            if pending_row and user_id not in analysis_done_today:
                pending_analysis_id = pending_row[0]
                logger.info("Found pending analysis id=%d for user %d — running now", pending_analysis_id, user_id)
                try:
                    context = _gather_context(user_cfg, broker, main_db, redis_client)
                    strategy = claude_engine.daily_analysis(context)
                    analysis_id = strategy.pop("_analysis_id", pending_analysis_id)

                    bot_db = _create_db_session()
                    bot_manager.spawn_bot(
                        user_id=user_id,
                        broker=broker,
                        strategy=strategy,
                        constraints=context.get("constraints"),
                        db_session=bot_db,
                        analysis_id=analysis_id,
                    )
                    analysis_done_today.add(user_id)
                    logger.info("Daily analysis complete for user %d", user_id)
                except Exception as exc:
                    logger.error(
                        "Daily analysis failed for user %d: %s", user_id, exc, exc_info=True,
                    )
                    # Mark any pending analyses as failed
                    try:
                        main_db.execute(
                            text(
                                "UPDATE claude_analyses SET status = 'failed', "
                                "reasoning = :err "
                                "WHERE user_id = :uid AND status = 'pending'"
                            ),
                            {"uid": user_id, "err": f"Analysis failed: {str(exc)[:500]}"},
                        )
                        main_db.commit()
                    except Exception:
                        pass
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
                        main_db.execute(
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
                        main_db.commit()

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
    main_db.close()
    redis_client.close()
    logger.info("Shutdown complete")


if __name__ == "__main__":
    main()
