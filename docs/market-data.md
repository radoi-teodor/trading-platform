# Market data

How the platform gets, stores and checks exchange data. Phase 1 covers Binance instruments and their filters,
1-minute candles for spot and USD-M perpetuals, and funding rates. Trades, open interest and the top of the order
book come with the phases that need them.

## 1. Sources (D-30)

Public market data needs no key. The platform reads it with its own small Binance client, not ccxt: the
endpoints are few and simple, Binance's native filters are more precise than a normalized copy, and the
official public-data hosts below are not known to ccxt.

| Data | Source | Default endpoint (config key) |
|------|--------|-------------------------------|
| Spot instruments and filters | `GET /api/v3/exchangeInfo` | `https://data-api.binance.vision` (`exchanges.binance.spot.rest`) |
| Perp instruments and filters | `GET /fapi/v1/exchangeInfo` | `https://fapi.binance.com` (`exchanges.binance.perp.rest`) |
| Candle history | Monthly and daily archives with a SHA-256 `.CHECKSUM` | `https://data.binance.vision` (`exchanges.binance.archive`) |
| Funding history | Monthly archives; recent rates from `GET /fapi/v1/fundingRate` | same |
| Recent candles | `GET /api/v3/klines` (1000 per page), `GET /fapi/v1/klines` (1500 per page) | the REST hosts above |
| Live candles | Combined WebSocket streams `<symbol>@kline_1m` | `wss://data-stream.binance.vision` (spot), `wss://fstream.binance.com/market` (perp: the market streams moved under `/market`; the bare host connects and sends nothing) |

`data-api.binance.vision`, `data-stream.binance.vision` and `data.binance.vision` are Binance's own public-data
hosts. They are the spot defaults because public reads should not spend the trading API's request weight, and
because the main hosts refuse some locations (they answer 451). Every endpoint is configurable; on the
operator's machine `api.binance.com` works too.

## 2. Data model (D-31)

Plain PostgreSQL, no extension. Five spot and five perpetual instruments produce about 430,000 candle rows a
month; the schema is revisited at 50 million rows.

| Table | Key | Holds |
|-------|-----|-------|
| `instruments` | `(exchange, market, venue_symbol)` | `symbol` (ccxt-style unified: `BTC/USDT`, `BTC/USDT:USDT`), base, quote, settle, status, first and last seen |
| `instrument_filters` | `(instrument_id, valid_from)` | tick size, lot step, minimum and maximum quantity, minimum notional, raw filters. A new row only when the filters change, so a backtest can use the filters as they were |
| `candles` | `(instrument_id, timeframe, open_time)` | open, high, low, close, base and quote volume, trade count, taker buy volume, `source`, `received_at` |
| `funding_rates` | `(instrument_id, funding_time)` | rate, interval in hours, mark price when known, `source`, `received_at` |
| `heartbeats` | `process` | last beat of each long-running process, with details |

All amounts are `numeric(36,18)`, all times `timestamptz` in UTC. The database refuses a candle whose low is above
its open, close or high, or whose high is below its open, close or low, or whose volume is negative.

**When a candle is known.** A candle exists for a strategy from its close (`open_time` plus the timeframe).
`received_at` records when we stored it; it measures our own lag and audits imports, but it is not what a backtest
filters on, because history imported today was public long ago.

**Several sources for one candle.** Sources rank `archive` above `rest` above `stream`. A higher-ranked source
overwrites a lower one; an equal or lower one never overwrites. Any difference in values between two sources for
the same closed candle is logged as a discrepancy with both versions, because it means one of them is wrong.

## 3. Getting history: archive, then REST, then stream

`php artisan market:backfill <symbol> --market=spot|perp --from=YYYY-MM [--to=YYYY-MM-DD]`

1. Every full month in the range comes from the monthly archive.
2. Days of the current month that are already archived come from daily archives.
3. The rest, up to the last closed minute, comes from REST.

Each archive is downloaded with its `.CHECKSUM`; the SHA-256 of the zip must match before anything is read. A
mismatch stops the import of that file with an error; nothing partial is written.

Published archives are not always complete: the USD-M monthly archives for October 2026 have no 2026-10-28 for
any pair, and no daily archive exists for that day. After each archive the backfill looks for the minutes of the
asked range that the archive did not have and requests them from REST (`rest gap` steps). What REST cannot
provide stays missing and `market:check` reports it.

Numbers can arrive in scientific notation (funding archives write `9.2E-7` or `0E-8`). Every number from Binance
is rewritten as an exact plain decimal by moving the decimal point in the text, never through float.

Archive formats differ and the parser checks them instead of guessing:

| Archive | Header | Time unit |
|---------|--------|-----------|
| Spot klines | none | microseconds since 2025-01-01, milliseconds before |
| USD-M klines | `open_time,open,high,...` | milliseconds |
| USD-M funding | `calc_time,funding_interval_hours,last_funding_rate` | milliseconds |

The time unit is recognized by the number of digits (13 for milliseconds, 16 for microseconds); anything else is
an error. Funding `calc_time` can carry a few milliseconds of jitter (`...800005`); it is floored to the second.

## 4. Live candles

`php artisan market:stream spot` and `php artisan market:stream perp`: one process per market, each holding one
combined WebSocket stream for all instruments of that market in the universe.

- Only closed candles (`k.x = true`) are stored, with `source = stream`.
- The minute still forming (`k.x = false`) is kept for the live chart only (D-87): one row per instrument in
  `forming_candles`, rewritten at most once a second with the time it was received. Strategies, the engine,
  backtests and agents never read it (an architecture test).
- The client answers server pings; Binance closes connections after 24 hours, so the process reconnects
  before that on its own.
- After every connect or reconnect the process repairs the gap: for each instrument it fetches from REST
  every closed candle after the last one stored. If REST is unavailable, the gap is reported loudly and stays
  visible to `market:check` until the daily archive fills it; there is no silent fallback.
- Disconnects back off exponentially from 1 to 60 seconds.
- Each process writes a heartbeat at least every 30 seconds.

## 5. Quality and verification

`php artisan market:check <symbol> --market=... --from=... --to=...` reports, reading a day at a time so that a
window of years fits in memory (two years of BTC/USDT: 22 s within 256 MB):

| Check | Severity |
|-------|----------|
| Missing minutes (gaps), with the missing ranges | error |
| OHLC violations or negative volume (the database refuses them; the check guards imported history) | error |
| Runs of zero volume longer than 30 minutes | warning |
| A close more than 5 % away from the median close of the surrounding 15 minutes | warning |

It exits non-zero on any error, and on warnings with `--strict`.

`php artisan market:verify <symbol> --market=... --from=... --to=... --sample=500 [--against=rest|daily-archive]`
draws a seeded random sample of stored candles and compares every field with a fresh read from the exchange.
`rest` is the default and the independent check for archive imports. `daily-archive` compares with Binance's
daily files, which are produced separately from the monthly ones; it is the check used where the REST host is not
reachable. Any mismatch is listed and the command exits non-zero.

**Phase 1 is done** when October 2026 for the ten instruments of the universe has no gap, no OHLC violation, and
no mismatch in a 500-candle sample per instrument.

## 6. Universe (D-33, D-80)

The universe is the `market_universe` table, edited on the Settings screen; `config/market.php` seeds it once
(BTC, ETH, SOL, BNB and XRP against USDT, spot and USD-M perpetuals). `market:instruments` loads every instrument
the exchange lists; only the universe is backfilled and streamed, and an instrument can be history only. The
stream reads the universe when it starts.

**Lifetime (D-89).** `market:instruments perp` keeps each perpetual's listing (`onboardDate`), its delivery when it
was settled, and its underlying type; stock, index and commodity contracts (`TRADIFI_PERPETUAL`) are perpetuals
with their own type. `market:instruments perp --history` also registers, as delisted, every perpetual only the
public archive listing still knows. Backfill and `market:check` stop at the listing and at the delisting: those
minutes are not gaps. A delisted instrument is filled from archives only (REST no longer knows it), and a minute
its archives lack stays a gap. A delisted instrument joins the universe for its history only.

## 7. Tests

The default suite never touches the network: the HTTP client is faked, stray requests fail the test, and
fixtures are real responses captured from Binance's public hosts (`tests/Fixtures/binance/`). Where a host could
not be reached when the fixture was made, the fixture is the example from Binance's API documentation and says
so in its file name. The opt-in `network` group (`composer test:network`) reads the real public endpoints.

## 8. Data for scalping (D-79, D-81)

| Store | Archive (`data.binance.vision`, futures/um) | Live | Known from |
|-------|---------------------------------------------|------|------------|
| `trade_flow` (instrument, minute): large-trade volume bought and sold, their count, the largest trade's notional | `daily/aggTrades/{SYM}/{SYM}-aggTrades-{day}.zip`, read as a stream and reduced to minutes | `<sym>@aggTrade`, reduced in memory | the minute's close |
| `open_interest` (instrument, 5-minute time): contracts and quote value | `daily/metrics/{SYM}/{SYM}-metrics-{day}.zip` (`create_time` is text, UTC) | `GET /futures/data/openInterestHist?period=5m` | its time plus 5 minutes |
| `positioning` (instrument, 5-minute time, D-91): accounts long to short (`long_short_ratio`), top traders' positions long to short (`top_trader_ratio`), taker buy to sell volume (`taker_ratio`) | the same `daily/metrics` file: `count_long_short_ratio`, `sum_toptrader_long_short_ratio`, `sum_taker_long_short_vol_ratio` (`--oi` imports both) | `GET /futures/data/globalLongShortAccountRatio`, `topLongShortPositionRatio`, `takerlongshortRatio` (`period=5m`), every 30 s | its time plus 5 minutes |
| `book_depth` (instrument, 5-minute time, D-91): quote notional of bids within 1, 2 and 5 % below the mid price, and of asks above | `daily/bookDepth/{SYM}/{SYM}-bookDepth-{day}.zip` (a snapshot about every 30 s: `timestamp,percentage,depth,notional`), the last of each 5 minutes kept (`--depth`) | `market:depth`: a local order book from `GET /fapi/v1/depth?limit=1000` and `<sym>@depth@500ms` on `wss://fstream.binance.com/public`, resynced on any missed update; its depth written when each 5 minutes ends by event time. Levels beyond the snapshot's 1,000 appear as they change, so the 5 % band reads low for a while after a start | the end of its 5 minutes |
| `mark_price_minutes` and `premium_index_minutes` (instrument, minute): open, high, low, close | `daily/markPriceKlines/{SYM}/1m/...` and `daily/premiumIndexKlines/{SYM}/1m/...` | `<sym>@markPrice@1s`; `GET /fapi/v1/premiumIndexKlines` | the minute's close |

The large-trade notional is set per instrument in the universe (BTC 100,000 USDT, ETH 50,000 by default) and applied
at import, so changing it means importing again. Unlike candles, a row is written once, by whichever source comes
first, and later sources only fill gaps (D-84): a paper engine's replay must read what it read live.
Volume bought and sold per minute needs no new data: the candles carry `taker_buy_volume`. The aggregated trades
are the largest download: a day of BTC is 5.6 MB zipped on a quiet Sunday (439,279 trades on 2026-11-01) and about
24 MB on a weekday (BTC and ETH on 2026-11-04), so twelve months of both is roughly 10 to 15 GB, read as a stream
and deleted day by day; only the minutes are kept. Some days are not published (2026-10-28 is missing).

`php artisan market:backfill BTC/USDT:USDT --from=2025-11 --flow --oi --mark` imports them (`--candles=no` leaves the
candles out, `--large=` overrides the notional); the Settings screen starts the same import as a job. A day Binance has
not published yet is reported and skipped.

**Live (D-81).** `market:stream perp` subscribes to `@aggTrade` and `@markPrice@1s` for the perpetuals in
`config/market.php` (`scalping`, BTC and ETH by default), reduces them to minutes and writes each minute once it is
over. Every 10 seconds it reads the premium index and open interest over REST; on every reconnect it refills the last
hour of marks, premium, open interest and missing trade flow over REST. A gap longer than an hour stays until
`market:backfill --flow --oi --mark` fills it from the next day's archive; an engine that reads the data waits for
it meanwhile and its stale-data breaker and the health check tell you. `fapi.binance.com` could not be reached
where this was built (451), so the live path is tested on Binance's documented message shapes and still has to be
watched once on the operator's machine.

