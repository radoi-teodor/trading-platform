"""
Configuration module for the AI Trading Platform agents.

Reads settings from the project-root .env file and exposes them
as class-level attributes on ``Config``.
"""

from __future__ import annotations

import os
from pathlib import Path

from dotenv import load_dotenv

# .env lives one directory above agents/
_ENV_PATH = Path(__file__).resolve().parent.parent / ".env"
load_dotenv(_ENV_PATH)


def _env(key: str, default: str = "") -> str:
    """Return an environment variable, falling back to *default*."""
    val = os.getenv(key, default)
    # Laravel uses the literal string "null" for empty values
    if val == "null":
        return ""
    return val


def _env_int(key: str, default: int = 0) -> int:
    raw = _env(key, str(default))
    try:
        return int(raw)
    except (ValueError, TypeError):
        return default


def _env_bool(key: str, default: bool = False) -> bool:
    raw = _env(key, str(default)).lower()
    return raw in ("true", "1", "yes")


class Config:
    """Central configuration read from environment / .env file."""

    # ── Database ─────────────────────────────────────────────────────────
    DB_CONNECTION: str = _env("DB_CONNECTION", "mysql")
    DB_HOST: str = _env("DB_HOST", "127.0.0.1")
    DB_PORT: int = _env_int("DB_PORT", 3306)
    DB_DATABASE: str = _env("DB_DATABASE", "trading_platform")
    DB_USERNAME: str = _env("DB_USERNAME", "root")
    DB_PASSWORD: str = _env("DB_PASSWORD", "")

    # ── Redis ────────────────────────────────────────────────────────────
    REDIS_HOST: str = _env("REDIS_HOST", "127.0.0.1")
    REDIS_PORT: int = _env_int("REDIS_PORT", 6379)
    REDIS_PASSWORD: str = _env("REDIS_PASSWORD", "")

    # ── Claude CLI ───────────────────────────────────────────────────────
    CLAUDE_BINARY_PATH: str = _env("CLAUDE_BINARY_PATH", "claude")

    # ── Alpaca ───────────────────────────────────────────────────────────
    ALPACA_API_KEY: str = _env("ALPACA_API_KEY")
    ALPACA_SECRET_KEY: str = _env("ALPACA_SECRET_KEY")
    ALPACA_BASE_URL: str = _env("ALPACA_BASE_URL", "https://paper-api.alpaca.markets")

    # ── Binance ──────────────────────────────────────────────────────────
    BINANCE_API_KEY: str = _env("BINANCE_API_KEY")
    BINANCE_SECRET_KEY: str = _env("BINANCE_SECRET_KEY")
    BINANCE_TESTNET: bool = _env_bool("BINANCE_TESTNET", True)

    # ── XTB ──────────────────────────────────────────────────────────────
    XTB_USER_ID: str = _env("XTB_USER_ID")
    XTB_PASSWORD: str = _env("XTB_PASSWORD")
    XTB_DEMO: bool = _env_bool("XTB_DEMO", True)

    # ── Market data API keys ─────────────────────────────────────────────
    ALPHAVANTAGE_API_KEY: str = _env("ALPHAVANTAGE_API_KEY")
    FINNHUB_API_KEY: str = _env("FINNHUB_API_KEY")
    TWELVEDATA_API_KEY: str = _env("TWELVEDATA_API_KEY")

    # ── Logging ──────────────────────────────────────────────────────────
    LOG_LEVEL: str = _env("LOG_LEVEL", "INFO")

    # ── Derived helpers ──────────────────────────────────────────────────

    @classmethod
    def get_db_url(cls) -> str:
        """Return a SQLAlchemy database URL built from the config values."""
        if cls.DB_CONNECTION == "sqlite":
            db_path = Path(__file__).resolve().parent.parent / "laravel" / "database" / "database.sqlite"
            return f"sqlite:///{db_path}"
        # Default: MySQL via PyMySQL driver
        password_part = f":{cls.DB_PASSWORD}" if cls.DB_PASSWORD else ""
        return (
            f"mysql+pymysql://{cls.DB_USERNAME}{password_part}"
            f"@{cls.DB_HOST}:{cls.DB_PORT}/{cls.DB_DATABASE}"
            "?charset=utf8mb4"
        )

    @classmethod
    def get_redis_url(cls) -> str:
        """Return a Redis connection URL."""
        if cls.REDIS_PASSWORD:
            return f"redis://:{cls.REDIS_PASSWORD}@{cls.REDIS_HOST}:{cls.REDIS_PORT}/0"
        return f"redis://{cls.REDIS_HOST}:{cls.REDIS_PORT}/0"
