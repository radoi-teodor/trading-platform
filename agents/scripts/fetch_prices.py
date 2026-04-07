#!/usr/bin/env python3
"""Fetch historical OHLCV data and output as JSON.

Usage: python fetch_prices.py SYMBOL [DAYS]
Example: python fetch_prices.py AAPL 90
         python fetch_prices.py BTC 90    (auto-converts to BTC-USD for yfinance)
         python fetch_prices.py ETH-USD 90

Crypto symbols (BTC, ETH, SOL, etc.) are auto-normalized:
- For yfinance: BTC → BTC-USD
- For Binance:  BTC → BTCUSDT
"""

import json
import sys
import os
from datetime import datetime, timedelta
from pathlib import Path

# Known crypto base symbols
CRYPTO_BASES = {
    "BTC", "ETH", "BNB", "SOL", "XRP", "ADA", "DOGE", "DOT",
    "AVAX", "MATIC", "LINK", "UNI", "ATOM", "LTC", "FIL",
    "NEAR", "APT", "ARB", "OP", "INJ", "TIA", "SUI", "SEI",
    "PEPE", "SHIB", "WIF", "BONK", "RENDER", "FET", "TAO",
}


def is_crypto(symbol: str) -> bool:
    """Check if symbol is a crypto asset."""
    upper = symbol.upper()
    base = upper.replace("-USD", "").replace("USDT", "").replace("-USDT", "")
    return base in CRYPTO_BASES or "-USD" in upper or upper.endswith("USDT")


def to_yfinance_symbol(symbol: str) -> str:
    """Normalize symbol for yfinance (crypto needs -USD suffix)."""
    upper = symbol.upper()
    if not is_crypto(upper):
        return upper  # stocks work as-is

    # Strip any existing suffix
    base = upper.replace("-USD", "").replace("USDT", "").replace("-USDT", "")
    return f"{base}-USD"


def to_binance_symbol(symbol: str) -> str:
    """Normalize symbol for Binance API (crypto needs USDT suffix)."""
    upper = symbol.upper()
    base = upper.replace("-USD", "").replace("USDT", "").replace("-USDT", "")
    return f"{base}USDT"


def fetch_from_yfinance(symbol: str, days: int) -> list[dict]:
    """Fetch from yfinance (no API key needed)."""
    import yfinance as yf
    yf_symbol = to_yfinance_symbol(symbol)
    end = datetime.now()
    start = end - timedelta(days=days)
    ticker = yf.Ticker(yf_symbol)
    df = ticker.history(start=start.strftime("%Y-%m-%d"), end=end.strftime("%Y-%m-%d"))
    if df.empty:
        return []
    records = []
    for date, row in df.iterrows():
        records.append({
            "date": date.strftime("%Y-%m-%d"),
            "open": round(float(row["Open"]), 2),
            "high": round(float(row["High"]), 2),
            "low": round(float(row["Low"]), 2),
            "close": round(float(row["Close"]), 2),
            "volume": int(row["Volume"]),
        })
    return records


def fetch_from_binance(symbol: str, days: int) -> list[dict]:
    """Fetch from Binance public API (no key needed for klines)."""
    import requests
    binance_symbol = to_binance_symbol(symbol)
    end_ms = int(datetime.now().timestamp() * 1000)
    start_ms = int((datetime.now() - timedelta(days=days)).timestamp() * 1000)
    url = "https://api.binance.com/api/v3/klines"
    params = {
        "symbol": binance_symbol,
        "interval": "1d",
        "startTime": start_ms,
        "endTime": end_ms,
        "limit": min(days, 1000),
    }
    resp = requests.get(url, params=params, timeout=30)
    if resp.status_code != 200:
        return []
    records = []
    for k in resp.json():
        records.append({
            "date": datetime.fromtimestamp(k[0] / 1000).strftime("%Y-%m-%d"),
            "open": round(float(k[1]), 2),
            "high": round(float(k[2]), 2),
            "low": round(float(k[3]), 2),
            "close": round(float(k[4]), 2),
            "volume": int(float(k[5])),
        })
    return records


def fetch_from_alphavantage(symbol: str, days: int) -> list[dict]:
    """Fetch from Alpha Vantage (needs API key)."""
    project_root = str(Path(__file__).resolve().parent.parent.parent)
    sys.path.insert(0, project_root)
    try:
        from agents.config import Config
        if not Config.ALPHAVANTAGE_API_KEY:
            return []
    except Exception:
        return []
    import requests
    url = "https://www.alphavantage.co/query"
    params = {
        "function": "TIME_SERIES_DAILY",
        "symbol": symbol,
        "outputsize": "full" if days > 100 else "compact",
        "apikey": Config.ALPHAVANTAGE_API_KEY,
    }
    resp = requests.get(url, params=params, timeout=30)
    data = resp.json()
    ts = data.get("Time Series (Daily)", {})
    if not ts:
        return []
    cutoff = (datetime.now() - timedelta(days=days)).strftime("%Y-%m-%d")
    records = []
    for date_str, vals in sorted(ts.items()):
        if date_str < cutoff:
            continue
        records.append({
            "date": date_str,
            "open": round(float(vals["1. open"]), 2),
            "high": round(float(vals["2. high"]), 2),
            "low": round(float(vals["3. low"]), 2),
            "close": round(float(vals["4. close"]), 2),
            "volume": int(vals["5. volume"]),
        })
    return records


def main():
    if len(sys.argv) < 2:
        print(json.dumps({"error": "Usage: fetch_prices.py SYMBOL [DAYS]"}))
        sys.exit(1)

    symbol = sys.argv[1].upper()
    days = int(sys.argv[2]) if len(sys.argv) > 2 else 90

    records = []

    # Try sources in priority order
    if is_crypto(symbol):
        sources = ["binance", "yfinance"]
    else:
        sources = ["alphavantage", "yfinance"]

    for source in sources:
        try:
            if source == "binance":
                records = fetch_from_binance(symbol, days)
            elif source == "alphavantage":
                records = fetch_from_alphavantage(symbol, days)
            elif source == "yfinance":
                records = fetch_from_yfinance(symbol, days)

            if records:
                break
        except Exception:
            continue

    print(json.dumps(records))


if __name__ == "__main__":
    main()
