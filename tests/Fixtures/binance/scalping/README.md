# Scalping archives (BTCUSDT USD-M perpetual, 2026-11-01)

Downloaded from `https://data.binance.vision/data/futures/um/daily/` on 2026-11-09 and checked against the published
`.CHECKSUM` files:

- `BTCUSDT-metrics-2026-11-01.zip`: unchanged, with Binance's checksum.
- `markPriceKlines/BTCUSDT-1m-2026-11-01.zip` and `premiumIndexKlines/BTCUSDT-1m-2026-11-01.zip`: unchanged, with
  Binance's checksums.
- `BTCUSDT-aggTrades-2026-11-01.zip`: the real day has 439,279 trades (5.6 MB); this file holds its header and first
  5,000 trades (00:00:00 to 00:21:57 UTC), byte for byte, zipped again, with a checksum computed here.

The expected values in `tests/Feature/Market/ScalpingArchiveTest.php` were computed from these files with Python's
`decimal` module, independently of the PHP code. These files are never edited; a new dataset means a new directory.
