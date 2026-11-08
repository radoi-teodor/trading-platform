"""Reference values for the validation statistics (D-58), computed independently of the PHP code.

The normal distribution and the deflated Sharpe ratio use mpmath at 50 digits; CSCV ranks configurations by their
Sharpe ratio computed directly (mean over sample deviation, with a square root). Run from the repository root:

    python3 -m venv /tmp/ref && /tmp/ref/bin/pip install mpmath
    /tmp/ref/bin/python -I tests/Fixtures/validation/generate.py > tests/Fixtures/validation/reference.json
"""
import itertools
import json

import mpmath as m

m.mp.dps = 50


def text(x, places=18):
    return m.nstr(m.mpf(x), 40, min_fixed=-100, max_fixed=100) if places is None else format(m.mpf(x), f".{places}f")


def returns(seed, count, drift):
    out = []
    for _ in range(count):
        seed = (seed * 1103515245 + 12345) % 2**31
        out.append(m.mpf(seed % 2001 - 1000) / 100000 + drift)
    return seed, out


def expected_maximum(trials, variance):
    if trials == 1 or variance == 0:
        return m.mpf(0)
    gamma = m.euler
    return m.sqrt(variance) * ((1 - gamma) * m.sqrt(2) * m.erfinv(2 * (1 - m.mpf(1) / trials) - 1)
                               + gamma * m.sqrt(2) * m.erfinv(2 * (1 - 1 / (trials * m.e)) - 1))


def probability(sharpe, observations, skew, kurt, benchmark):
    z = (sharpe - benchmark) * m.sqrt(observations - 1) / m.sqrt(1 - skew * sharpe + (kurt - 1) / 4 * sharpe**2)
    return m.ncdf(z)


def moments(xs):
    n = len(xs)
    mean = m.fsum(xs) / n
    m2 = m.fsum((x - mean) ** 2 for x in xs) / n
    m3 = m.fsum((x - mean) ** 3 for x in xs) / n
    m4 = m.fsum((x - mean) ** 4 for x in xs) / n
    sd = m.sqrt(m.fsum((x - mean) ** 2 for x in xs) / (n - 1))
    return mean, sd, m3 / m2 ** m.mpf(1.5), m4 / m2**2


def cscv(matrix, partitions):
    length = len(matrix[0])
    size = length // partitions
    skip = length - size * partitions

    def sharpe(series, blocks):
        values = [v for b in blocks for v in series[skip + b * size: skip + (b + 1) * size]]
        mean = m.fsum(values) / len(values)
        sd = m.sqrt(m.fsum((v - mean) ** 2 for v in values) / (len(values) - 1))
        return mean / sd

    below = total = 0
    for chosen in itertools.combinations(range(partitions), partitions // 2):
        rest = [b for b in range(partitions) if b not in chosen]
        ins = [sharpe(s, chosen) for s in matrix]
        best = max(range(len(matrix)), key=lambda c: (ins[c], -c))
        outs = [sharpe(s, rest) for s in matrix]
        rank = 1 + sum(1 for c in range(len(matrix)) if outs[c] < outs[best] or (outs[c] == outs[best] and c < best))
        omega = m.mpf(rank) / (len(matrix) + 1)
        below += 1 if m.log(omega / (1 - omega)) <= 0 else 0
        total += 1
    return below, total


seed, sample = returns(7, 250, m.mpf("0.0004"))
mean, sd, skew, kurt = moments(sample)
matrix = []
for c in range(6):
    seed, series = returns(seed, 50, m.mpf(c - 2) / 10000)
    matrix.append(series)
below, total = cscv(matrix, 16)

print(json.dumps({
    "cdf": [[text(x), text(m.ncdf(x))] for x in ["-6", "-3.5", "-1.96", "-0.5", "0", "0.3", "1", "1.645", "2.5", "3.2", "5", "8"]],
    "inverse": [[text(p), text(m.sqrt(2) * m.erfinv(2 * m.mpf(p) - 1))] for p in ["0.001", "0.025", "0.5", "0.9", "0.99", "0.999", "0.999999"]],
    "expected_maximum": [[n, text(v), text(expected_maximum(n, m.mpf(v)))] for n, v in [(1, "0.0004"), (10, "0.0001"), (100, "0.0004"), (1000, "0.000025")]],
    "probability": [[text(s), t, text(g3), text(g4), text(b), text(probability(m.mpf(s), t, m.mpf(g3), m.mpf(g4), m.mpf(b)))]
                    for s, t, g3, g4, b in [("0.1", 250, "-0.3", "5", "0"), ("0.08", 1000, "0", "3", "0.05"), ("0.02", 365, "0.5", "8", "0.06")]],
    "sample": [text(x) for x in sample],
    "moments": {"mean": text(mean), "deviation": text(sd), "skewness": text(skew), "kurtosis": text(kurt)},
    "cscv": {"returns": [[text(x) for x in s] for s in matrix], "partitions": 16, "below": below, "combinations": total},
}, indent=1))
