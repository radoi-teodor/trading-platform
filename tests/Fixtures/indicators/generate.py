"""Reference values for the DSL's indicators (D-52), computed independently of the PHP code with Python's decimal
module at 60 significant digits. Run from the repository root:

    python3 -I tests/Fixtures/indicators/generate.py > tests/Fixtures/indicators/reference.json

The bars are a deterministic walk (a linear congruential generator), so the file is reproducible. Each case names
the indicator, its arguments and its expected values per bar (null while it warms up). Definitions follow
docs/strategy/configurator.md, section 2, and D-52.
"""
import json
from decimal import Decimal as D, getcontext

getcontext().prec = 60

HOUR = 3600
START = 1790812800  # 2026-10-01 00:00 UTC
BARS = 160


def bars():
    seed = 20261108
    price = 10000
    out = []
    for i in range(BARS):
        seed = (seed * 1103515245 + 12345) % 2**31
        step = seed % 401 - 200
        seed = (seed * 1103515245 + 12345) % 2**31
        wick_up = seed % 120
        seed = (seed * 1103515245 + 12345) % 2**31
        wick_down = seed % 120
        seed = (seed * 1103515245 + 12345) % 2**31
        volume = seed % 900000 + (0 if i % 37 else 0)
        open_ = price
        close = max(1000, price + step)
        high = max(open_, close) + wick_up
        low = min(open_, close) - wick_down
        out.append({
            "time": START + i * HOUR,
            "open": D(open_) / 100, "high": D(high) / 100, "low": D(low) / 100, "close": D(close) / 100,
            "volume": D(volume) / 1000,
        })
        price = close
    return out


def window(values, i, n):
    return values[i - n + 1:i + 1] if i >= n - 1 and all(v is not None for v in values[i - n + 1:i + 1]) else None


def sma(xs, n):
    return [sum(w) / n if (w := window(xs, i, n)) else None for i in range(len(xs))]


def ema(xs, n):
    out, value, seeded = [], None, []
    alpha = D(2) / (n + 1)
    for x in xs:
        if x is None:
            out.append(None)
            continue
        if value is None:
            seeded.append(x)
            if len(seeded) == n:
                value = sum(seeded) / n
        else:
            value = value + alpha * (x - value)
        out.append(value)
    return out


def wma(xs, n):
    weights = list(range(1, n + 1))
    total = sum(weights)
    return [sum(w * x for w, x in zip(weights, win)) / total if (win := window(xs, i, n)) else None for i in range(len(xs))]


def hma(xs, n):
    half, root = wma(xs, n // 2), wma(xs, n)
    raw = [2 * a - b if a is not None and b is not None else None for a, b in zip(half, root)]
    return wma(raw, int(D(n).sqrt()))


def vwma(closes, volumes, n):
    out = []
    for i in range(len(closes)):
        if i < n - 1:
            out.append(None)
            continue
        vol = sum(volumes[i - n + 1:i + 1])
        out.append(sum(c * v for c, v in zip(closes[i - n + 1:i + 1], volumes[i - n + 1:i + 1])) / vol if vol else None)
    return out


def vwap(bs, anchor_seconds):
    out, num, den, period = [], D(0), D(0), None
    for b in bs:
        p = b["time"] - b["time"] % anchor_seconds
        if p != period:
            num, den, period = D(0), D(0), p
        num += (b["high"] + b["low"] + b["close"]) / 3 * b["volume"]
        den += b["volume"]
        out.append(num / den if den else None)
    return out


def rsi(xs, n):
    out, gains, losses, avg_gain, avg_loss = [None], [], [], None, None
    for i in range(1, len(xs)):
        change = xs[i] - xs[i - 1]
        gain, loss = max(change, D(0)), max(-change, D(0))
        if avg_gain is None:
            gains.append(gain)
            losses.append(loss)
            if len(gains) == n:
                avg_gain, avg_loss = sum(gains) / n, sum(losses) / n
        else:
            avg_gain = (avg_gain * (n - 1) + gain) / n
            avg_loss = (avg_loss * (n - 1) + loss) / n
        if avg_gain is None:
            out.append(None)
        elif avg_loss == 0:
            out.append(D(50) if avg_gain == 0 else D(100))
        else:
            out.append(100 - 100 / (1 + avg_gain / avg_loss))
    return out


def roc(xs, n):
    return [100 * (xs[i] / xs[i - n] - 1) if i >= n else None for i in range(len(xs))]


def macd(xs, fast, slow, signal):
    line = [f - s if f is not None and s is not None else None for f, s in zip(ema(xs, fast), ema(xs, slow))]
    sig = ema(line, signal)
    hist = [l - s if l is not None and s is not None else None for l, s in zip(line, sig)]
    return {"line": line, "signal": sig, "hist": hist}


def stoch(bs, k, d):
    ks = []
    for i in range(len(bs)):
        if i < k - 1:
            ks.append(None)
            continue
        hh = max(b["high"] for b in bs[i - k + 1:i + 1])
        ll = min(b["low"] for b in bs[i - k + 1:i + 1])
        ks.append(D(50) if hh == ll else 100 * (bs[i]["close"] - ll) / (hh - ll))
    return {"k": ks, "d": sma(ks, d)}


def true_ranges(bs):
    out = []
    for i, b in enumerate(bs):
        r = b["high"] - b["low"]
        if i > 0:
            c = bs[i - 1]["close"]
            r = max(r, abs(b["high"] - c), abs(b["low"] - c))
        out.append(r)
    return out


def wilder(xs, n):
    """Wilder's average: the mean of the first n values, then (previous x (n - 1) + x) / n."""
    out, first, value = [], [], None
    for x in xs:
        if x is None:
            out.append(None)
            continue
        if value is None:
            first.append(x)
            if len(first) == n:
                value = sum(first) / n
        else:
            value = (value * (n - 1) + x) / n
        out.append(value)
    return out


def atr(bs, n):
    return wilder(true_ranges(bs), n)


def stdev(xs, n):
    out = []
    for i in range(len(xs)):
        w = window(xs, i, n)
        if not w:
            out.append(None)
            continue
        mean = sum(w) / n
        out.append((sum((x - mean) ** 2 for x in w) / n).sqrt())
    return out


def realized_vol(xs, n, timeframe_seconds):
    returns = [None] + [(xs[i] / xs[i - 1]).ln() for i in range(1, len(xs))]
    per_year = D(365 * 86400) / timeframe_seconds
    out = []
    for i in range(len(xs)):
        w = window(returns, i, n)
        if not w:
            out.append(None)
            continue
        mean = sum(w) / n
        out.append((sum((r - mean) ** 2 for r in w) / (n - 1)).sqrt() * per_year.sqrt())
    return out


def bb(xs, n, k):
    mid, sd = sma(xs, n), stdev(xs, n)
    up = [m + k * s if m is not None else None for m, s in zip(mid, sd)]
    lo = [m - k * s if m is not None else None for m, s in zip(mid, sd)]
    width = [(u - l) / m if m is not None else None for u, l, m in zip(up, lo, mid)]
    return {"upper": up, "middle": mid, "lower": lo, "width": width}


def donchian(bs, n):
    up = [max(b["high"] for b in bs[i - n + 1:i + 1]) if i >= n - 1 else None for i in range(len(bs))]
    lo = [min(b["low"] for b in bs[i - n + 1:i + 1]) if i >= n - 1 else None for i in range(len(bs))]
    return {"upper": up, "lower": lo, "middle": [(u + l) / 2 if u is not None else None for u, l in zip(up, lo)]}


def keltner(bs, n, k):
    mid, a = ema([b["close"] for b in bs], n), atr(bs, n)
    return {
        "upper": [m + k * x if m is not None and x is not None else None for m, x in zip(mid, a)],
        "middle": [m if m is not None and x is not None else None for m, x in zip(mid, a)],
        "lower": [m - k * x if m is not None and x is not None else None for m, x in zip(mid, a)],
    }


def dmi(bs, n):
    plus_dm, minus_dm, tr = [None], [None], [None]
    for i in range(1, len(bs)):
        up = bs[i]["high"] - bs[i - 1]["high"]
        down = bs[i - 1]["low"] - bs[i]["low"]
        plus_dm.append(up if up > down and up > 0 else D(0))
        minus_dm.append(down if down > up and down > 0 else D(0))
        c = bs[i - 1]["close"]
        tr.append(max(bs[i]["high"] - bs[i]["low"], abs(bs[i]["high"] - c), abs(bs[i]["low"] - c)))

    def smoothed(xs):
        out, first, value = [], [], None
        for x in xs:
            if x is None:
                out.append(None)
                continue
            if value is None:
                first.append(x)
                if len(first) == n:
                    value = sum(first)
            else:
                value = value - value / n + x
            out.append(value)
        return out

    sp, sm, st = smoothed(plus_dm), smoothed(minus_dm), smoothed(tr)
    pdi = [100 * p / t if p is not None and t else (D(0) if p is not None else None) for p, t in zip(sp, st)]
    mdi = [100 * m / t if m is not None and t else (D(0) if m is not None else None) for m, t in zip(sm, st)]
    dx = [(100 * abs(p - m) / (p + m) if p + m else D(0)) if p is not None else None for p, m in zip(pdi, mdi)]
    return {"plus_di": pdi, "minus_di": mdi, "adx": wilder(dx, n)}


def supertrend(bs, n, k):
    a = atr(bs, n)
    line, direction = [], []
    fu = fl = None
    d = None
    for i, b in enumerate(bs):
        if a[i] is None:
            line.append(None)
            direction.append(None)
            continue
        hl2 = (b["high"] + b["low"]) / 2
        bu, bl = hl2 + k * a[i], hl2 - k * a[i]
        if fu is None:
            fu, fl, d = bu, bl, 1
        else:
            prev_close = bs[i - 1]["close"]
            fu = bu if bu < fu or prev_close > fu else fu
            fl = bl if bl > fl or prev_close < fl else fl
            if d == 1 and b["close"] < fl:
                d = -1
            elif d == -1 and b["close"] > fu:
                d = 1
        line.append(fl if d == 1 else fu)
        direction.append(D(d))
    return {"line": line, "direction": direction}


def highest(xs, n):
    return [max(w) if (w := window(xs, i, n)) else None for i in range(len(xs))]


def lowest(xs, n):
    return [min(w) if (w := window(xs, i, n)) else None for i in range(len(xs))]


def change(xs, n):
    return [xs[i] - xs[i - n] if i >= n else None for i in range(len(xs))]


def text(value):
    if value is None:
        return None
    return format(value.quantize(D("1e-24")).normalize(), "f")


def main():
    bs = bars()
    close = [b["close"] for b in bs]
    hl2 = [(b["high"] + b["low"]) / 2 for b in bs]
    volume = [b["volume"] for b in bs]
    cases = [
        ("sma", {"n": 20}, sma(close, 20)),
        ("sma", {"n": 7, "src": "hl2"}, sma(hl2, 7)),
        ("ema", {"n": 20}, ema(close, 20)),
        ("wma", {"n": 10}, wma(close, 10)),
        ("hma", {"n": 16}, hma(close, 16)),
        ("hma", {"n": 9}, hma(close, 9)),
        ("vwma", {"n": 20}, vwma(close, volume, 20)),
        ("vwap", {"anchor": "1d"}, vwap(bs, 86400)),
        ("rsi", {"n": 14}, rsi(close, 14)),
        ("roc", {"n": 9}, roc(close, 9)),
        ("macd", {"fast": 12, "slow": 26, "signal": 9}, macd(close, 12, 26, 9)),
        ("stoch", {"k": 14, "d": 3}, stoch(bs, 14, 3)),
        ("atr", {"n": 14}, atr(bs, 14)),
        ("natr", {"n": 14}, [100 * x / c if x is not None else None for x, c in zip(atr(bs, 14), close)]),
        ("stdev", {"n": 20}, stdev(close, 20)),
        ("realized_vol", {"n": 24, "timeframe": "1h"}, realized_vol(close, 24, HOUR)),
        ("bb", {"n": 20, "k": 2}, bb(close, 20, D(2))),
        ("donchian", {"n": 20}, donchian(bs, 20)),
        ("keltner", {"n": 20, "k": 1.5}, keltner(bs, 20, D("1.5"))),
        ("dmi", {"n": 14}, dmi(bs, 14)),
        ("supertrend", {"n": 10, "k": 3}, supertrend(bs, 10, D(3))),
        ("highest", {"n": 10}, highest(close, 10)),
        ("lowest", {"n": 10}, lowest(close, 10)),
        ("change", {"n": 5}, change(close, 5)),
    ]
    out = {
        "bars": [{k: (v if k == "time" else text(v)) for k, v in b.items()} for b in bs],
        "cases": [
            {"function": f, "arguments": args, "values": {k: [text(x) for x in v] for k, v in values.items()} if isinstance(values, dict) else {"value": [text(x) for x in values]}}
            for f, args, values in cases
        ],
    }
    print(json.dumps(out, indent=1, sort_keys=True))


main()
