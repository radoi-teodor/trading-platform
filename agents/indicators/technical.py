"""
Technical indicator calculations.

All functions accept a list/array of prices (closing prices unless noted)
and return computed indicator values using numpy/pandas.
"""

from __future__ import annotations

import numpy as np
import pandas as pd


def SMA(prices: list[float] | np.ndarray, period: int) -> list[float]:
    """Simple Moving Average.

    Returns a list of length ``len(prices)``.  The first ``period - 1``
    values are ``NaN``.
    """
    s = pd.Series(prices)
    return s.rolling(window=period).mean().tolist()


def EMA(prices: list[float] | np.ndarray, period: int) -> list[float]:
    """Exponential Moving Average.

    Uses the standard *span* parameter (decay = 2 / (period + 1)).
    """
    s = pd.Series(prices)
    return s.ewm(span=period, adjust=False).mean().tolist()


def RSI(prices: list[float] | np.ndarray, period: int = 14) -> list[float]:
    """Relative Strength Index (Wilder's smoothing).

    Returns values in [0, 100].  First ``period`` entries are ``NaN``.
    """
    s = pd.Series(prices)
    delta = s.diff()

    gain = delta.clip(lower=0)
    loss = -delta.clip(upper=0)

    avg_gain = gain.ewm(alpha=1.0 / period, min_periods=period, adjust=False).mean()
    avg_loss = loss.ewm(alpha=1.0 / period, min_periods=period, adjust=False).mean()

    rs = avg_gain / avg_loss.replace(0, np.nan)
    rsi = 100.0 - (100.0 / (1.0 + rs))
    return rsi.tolist()


def MACD(
    prices: list[float] | np.ndarray,
    fast_period: int = 12,
    slow_period: int = 26,
    signal_period: int = 9,
) -> dict[str, list[float]]:
    """Moving Average Convergence Divergence.

    Returns a dict with keys ``macd``, ``signal``, and ``histogram``.
    """
    s = pd.Series(prices)
    fast_ema = s.ewm(span=fast_period, adjust=False).mean()
    slow_ema = s.ewm(span=slow_period, adjust=False).mean()
    macd_line = fast_ema - slow_ema
    signal_line = macd_line.ewm(span=signal_period, adjust=False).mean()
    histogram = macd_line - signal_line

    return {
        "macd": macd_line.tolist(),
        "signal": signal_line.tolist(),
        "histogram": histogram.tolist(),
    }


def BollingerBands(
    prices: list[float] | np.ndarray,
    period: int = 20,
    num_std: float = 2.0,
) -> dict[str, list[float]]:
    """Bollinger Bands.

    Returns a dict with keys ``upper``, ``middle`` (SMA), and ``lower``.
    """
    s = pd.Series(prices)
    middle = s.rolling(window=period).mean()
    std = s.rolling(window=period).std()
    upper = middle + num_std * std
    lower = middle - num_std * std

    return {
        "upper": upper.tolist(),
        "middle": middle.tolist(),
        "lower": lower.tolist(),
    }
