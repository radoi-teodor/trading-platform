"""Reference values for the scalping functions of the DSL (D-79), independent of the PHP code.

Run: python3 -I tests/Fixtures/scalping-functions/generate.py > tests/Fixtures/scalping-functions/reference.json

It writes 60 minutes of a perpetual (candles with taker buy volume, trade flow, mark price, premium index, and open
interest every 5 minutes) and what each function reads at the close of every 5-minute candle.
"""

import json
from decimal import Decimal as D, getcontext, ROUND_DOWN

getcontext().prec = 60
START = 1793491200  # 2026-11-01 00:00:00 UTC
MINUTES = 60
STEP = 300


def text(value):
    if value is None:
        return None
    s = format(value.normalize(), 'f')
    if '.' in s:
        s = s.rstrip('0').rstrip('.')
    return '0' if s in ('-0', '') else s


def div(a, b):
    # PHP's bcdiv at scale 18 truncates toward zero.
    return (a / b).quantize(D('1e-18'), rounding=ROUND_DOWN)


seed = 12345


def rand(n):
    global seed
    seed = (seed * 1103515245 + 12345) % 2147483648
    return seed % n


minutes = []
price = D('100.00')
for i in range(MINUTES):
    t = START + 60 * i
    o = price
    c = o + D(rand(200) - 100) / 100
    h = max(o, c) + D(rand(50)) / 100
    lo = min(o, c) - D(rand(50)) / 100
    vol = D(10 + rand(90)) + D(rand(100)) / 100
    buy = (vol * D(rand(101)) / 100).quantize(D('0.01'))
    minutes.append({
        'open_time': t, 'open': text(o), 'high': text(h), 'low': text(lo), 'close': text(c),
        'volume': text(vol), 'taker_buy': text(buy),
        'large_buy': text(D(rand(500)) / 100), 'large_sell': text(D(rand(500)) / 100),
        'mark': text(c + D(rand(20) - 10) / 100), 'premium': text(D(rand(200) - 100) / 1000000),
    })
    price = c

open_interest = [{'time': START + 300 * k, 'contracts': text(D(90000 + rand(5000)) + D(rand(1000)) / 1000), 'value': '1'} for k in range(MINUTES // 5)]

expected = []
deltas = []
ois = []
for k in range(MINUTES // 5):
    window = minutes[5 * k: 5 * k + 5]
    close = START + 300 * (k + 1)
    vol = sum(D(m['volume']) for m in window)
    buy = sum(D(m['taker_buy']) for m in window)
    delta = buy - (vol - buy)
    deltas.append(delta)
    known = [o for o in open_interest if o['time'] + 300 <= close]
    oi = D(known[-1]['contracts']) if known else None
    ois.append(oi)
    row = {
        'time': close,
        'buy_volume': text(buy), 'sell_volume': text(vol - buy), 'delta': text(delta),
        'cvd(3)': text(sum(deltas[-3:])) if len(deltas) >= 3 else None,
        'large_buy_volume': text(sum(D(m['large_buy']) for m in window)),
        'large_delta': text(sum(D(m['large_buy']) - D(m['large_sell']) for m in window)),
        'open_interest': text(oi),
        'oi_change(2)': text(div(ois[-1], ois[-3]) - 1) if len(ois) >= 3 and ois[-1] is not None and ois[-3] is not None else None,
        'mark_price': window[-1]['mark'], 'basis': window[-1]['premium'],
    }
    expected.append(row)

print(json.dumps({'minutes': minutes, 'open_interest': open_interest, 'expected': expected}, indent=1))
