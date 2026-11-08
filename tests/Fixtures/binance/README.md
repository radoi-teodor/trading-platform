# Binance fixtures

Where each file comes from. Tests never call the network; `composer test:network` checks the real endpoints.

| File | Provenance |
|------|------------|
| `spot-exchange-info.json` | Real response of `GET https://data-api.binance.vision/api/v3/exchangeInfo?symbols=["BTCUSDT","ETHUSDT","XRPUSDT"]`, captured 2026-11-08 |
| `spot-klines-btcusdt.json` | Real response of `GET https://data-api.binance.vision/api/v3/klines?symbol=BTCUSDT&interval=1m&startTime=1790812800000&limit=5` (2026-10-01 00:00 to 00:04 UTC), captured 2026-11-08 |
| `perp-klines-btcusdt.from-archive.json` | The first five rows of the real USD-M archive `futures/um/daily/klines/BTCUSDT/1m/BTCUSDT-1m-2026-11-06.zip` (checksum verified), written in the 12-field REST layout documented for `GET /fapi/v1/klines`. `fapi.binance.com` refused this environment (451) |
| `perp-funding-btcusdt.from-archive.json` | The first three rates of the real archive `futures/um/monthly/fundingRate/BTCUSDT/BTCUSDT-fundingRate-2026-09.zip`, in the object layout documented for `GET /fapi/v1/fundingRate`; `fundingTime` floored to the second, `markPrice` empty because the archive has none |
| `perp-exchange-info.hand-written.json` | Written by hand from the field structure documented for `GET /fapi/v1/exchangeInfo`; values are plausible, not captured. Includes a quarterly contract and a settling symbol to test filtering. Replace with a real capture when one is made from the operator's machine |
| `archive/*.zip` and `archive/*.zip.CHECKSUM` | Real archives and their published checksums from `data.binance.vision`: spot and USD-M BTCUSDT 1m for 2026-11-06, USD-M BTCUSDT funding for 2026-09 |
| `spot-stream-kline-btcusdt.jsonl` | Two real messages from `wss://data-stream.binance.vision/stream?streams=btcusdt@kline_1m`, captured 2026-11-08: one update of an open candle (`x: false`), then the same candle closed (`x: true`). No USD-M capture: `fstream.binance.com` accepts the connection from this environment but sends only pings |

## golden/

Binance's daily 1-minute spot archives of BTCUSDT and ETHUSDT for 2026-10-01 to 2026-10-10, downloaded from
`https://data.binance.vision/data/spot/daily/klines/<SYMBOL>/1m/` on 2026-11-08 with their `.CHECKSUM` files; every
zip matched its published SHA-256. The golden tests (`tests/Golden`) read them through the real archive parser.
They are never edited: a new dataset is a new directory and new golden hashes.
