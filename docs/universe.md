# Multi-market research (Phase 7)

The platform researched one instrument at a time on five perpetuals. Phase 7 lets a strategy look at many
markets at once: a universe of the 50 most liquid USD-M perpetuals over two years, chosen point in time so that
coins delisted since are part of the past (no survivorship bias), with order book depth, positioning ratios,
liquidations and listing data, and strategies that rank instruments against each other. The research agent
reads all of it over MCP. Decisions: D-89 to D-94.

## 1. Instruments and their lifetime (D-89)

- Every USD-M perpetual that ever existed is known. Current contracts come from `exchangeInfo`, whose `onboardDate`
  is the listing time (`instruments.listed_at`). Delisted contracts come from the public archive listing
  (`data.binance.vision`, paged S3 XML of `futures/um/monthly/klines/`): the first month with a file gives the
  listing month, the first candle the listing minute.
- `instruments.delisted_at` is the minute after the last candle with volume. Archives can hold flat, zero-volume
  candles after a delisting; they are cut there.
- `underlying_type` keeps what `exchangeInfo` says (COIN, INDEX, and others). A research universe holds USDT-quoted
  crypto perpetuals only: USDC and BUSD duplicates and contracts on stocks or gold are left out.
- Minutes before `listed_at` and from `delisted_at` on are not gaps. Backfill does not ask REST for them,
  `market:check` does not report them, and the feed does not expect them.

## 2. The research universe (D-89)

A universe is a rule, not a list: the top N USDT-quoted crypto perpetuals by 30-day quote volume, re-ranked on the
first day of each month (UTC). `market:universe:build --top=50 --from=2024-11` computes it from `daily_stats` and
stores dated rows in `universe_members` (month, instrument, rank). A backtest at time t only sees the members at t. A
coin that was in the top 50 in 2025 and was delisted later is in the 2025 backtest.

The top churns: from 2024-11 to 2026-11, 300 coins were members at some time, 26 of them delisted since. Each is
imported only over its runs of member months, from the month before (the indicators' warmup): 435 runs, about 74
million minutes, about 15 GB, instead of about 40 GB to today.

## 3. Storage at scale (D-90)

50 perpetuals over two years of 1-minute candles are about 52 million rows, about 11 GB. The universe is point in
time, so more than 50 are stored: the top 50 from 2024-11 has had 300 members over 435 membership spans, about 15 GB
of 1-minute candles (D-89 imports a member only over the months it was in). `candles` is partitioned by
month on `open_time` (PostgreSQL declarative partitioning, no extension). `daily_stats` (per instrument per day:
open, high, low, close, quote volume, trades, average funding, open interest) serves rankings, screens and listing
studies without reading minutes; it is filled for every symbol from the daily kline archives. The feed reads many
instruments with a server-side cursor in hour chunks. `start-local.ps1` gives PostgreSQL `shared_buffers=1GB` and
`work_mem=64MB`.

## 4. Depth, positioning and liquidations (D-91)

| Data | Source | History | Stored |
|------|--------|---------|--------|
| Order book depth | `futures/um/daily/bookDepth` archives | from 2023, most symbols | `book_depth`: bid and ask notional within 1, 2 and 5 % of the price, per instrument per 5 minutes |
| Long/short and taker ratios | `futures/um/daily/metrics` archives (already read for open interest) | as open interest | table `positioning` |
| Liquidations | `!forceOrder@arr` on `wss://fstream.binance.com/market` | from the day collection starts; Binance publishes none before | `liquidation_minutes`: long and short count and notional per instrument per minute |

Binance sends at most one liquidation per symbol per second on that stream, so the liquidation series is a lower
bound. A strategy reading a series over a window without data is refused, never fed zeros.

## 5. Cross-sectional strategies (D-92)

```yaml
strategy: xs-momentum-4h
universe: { rule: top, n: 50, by: quote_volume_30d }
timeframe: 4h
entries:
  long:
    when: top(5, roc(42))             # the 5 members with the highest 7-day return
  short:
    when: bottom(5, roc(42))
exits:
  stop_loss: 2 * atr(14)
  when: rank(roc(42)) > 10 and rank(roc(42)) <= count - 10
```

- `universe:` replaces `market.instruments`; the rule is part of the version, membership comes from
  `universe_members` at each decision.
- `rank(expr)` (1 is the highest), `percentile(expr)`, `top(n, expr)`, `bottom(n, expr)` and `count` exist only in
  a universe strategy. At each close of the strategy's timeframe every member's ranked values are worked out, then
  the members are ranked (decimals, ties broken by symbol), then every member decides
  (`App\Strategy\Dsl\Compiler\UniverseStrategy`, `CrossSectionalStrategy::onCloses`).
- Exits and stop moves of the batch go first. Entries go in rank order once those closes have filled, so freed
  positions and risk go to the best ranked. Every entry has a stop, as everywhere. When a close of the batch went
  to the venue, the entries wait one minute for it; they are held in the engine's memory, so a process that stops
  in that minute does not place them.
- The order of entries is the rank in the first ranking of that side's `when`, else of `filters`; `bottom` counts
  from the lowest; a long goes before a short of the same place, then by symbol.
- A member enters the ranking in the months it is a member (`universe_members`, by the month of the close in UTC)
  and after its warmup in candles without a gap; a gap starts its history again.
- A position in an instrument that left the universe is closed (`universe.left`). A delisting closes its position
  at the last price before `delisted_at`.
- Per-symbol strategies run exactly as before; the golden reports do not move.

### In simulation

- `backtest:run`, `backtest:grid` and `strategy:gates` give a universe strategy the members of each month of the
  run from `universe_members` (`App\Strategy\Dsl\Compiler\Universes`), through the same engine as every other run
  (D-8). A run is refused when the universe is not built, is built for fewer members than the rule names, or has
  nobody in a month of the run: a strategy never runs on another universe than the one it names.
- A member is needed around its months as a member: from a month before them, which is its warmup as the import
  brings it, to a day after them, where what is held in an instrument that left is closed, and not past the moment
  it stopped trading. A gap inside that refuses the run, as for any instrument.
- A perpetual delisted before its exchange filters were ever read has them worked out from its candles
  (`App\Market\Instruments\AssumedRules`: the finest decimal of its prices and volumes in its last stored month,
  a least notional of 5), and the report lists it under `data.filters_assumed_before`.
- In the hour before an instrument stops trading its position is closed (`delisting_close`) and an entry in it is
  refused (`delisting`).
- A universe run is compared with holding BTC spot in the quote asset.
- Gates: the history is the months the universe is built for, to the end of what is stored of the last month's
  members that still trade, not the days every member has in common. The regimes are those of the member ranked
  first when the history starts.
- Background runs (D-62): a run over a universe has six hours instead of one, and a backtest or grid of more than
  40,000 instrument-days (instruments times days; the top 50 over two years is 36,500) is refused at submission.
- Speed. The strategy is given every candle of the run; a benchmark is given the candles of the instrument it
  holds and no others. The price sanity and volatility shock breakers keep a sorted window and a running sum for
  each instrument instead of sorting and summing every minute; they say what they said before, minute by minute
  (`MarketBreakersTest` holds the two together). Measured on 2026-11-10 on the operator's machine, 50 synthetic
  members over 2 days, a 1h strategy, candles from memory: 144,000 candles in 3.6 s, and 4.2 s with two spot
  benchmarks (6.5 s and 9.4 s before). That is about 40,000 candles a second, so the engine's part of the top 50
  over two years (52.6 million candles) is about 25 minutes; reading them from PostgreSQL comes on top and is
  measured in step 8.

### On an account

An account cannot run a universe strategy yet, in paper, testnet or live: `engine:run` refuses to start with one
deployed (`App\Execution\AccountRuntime`). The engine itself decides a universe batch in every mode alike; what is
missing is around it, in the account's process: it waits at a minute until every instrument it follows has a
candle, which a member that is not trading that minute never has; it reads the members once at start, and a
process that runs for weeks crosses months; and the stream would have to keep about 50 instruments current. This
is open question O-7.

Status: the document, the compiler, the engine batch, the backtest, the grid and the gates over a universe are done
(steps 5 and 6). MCP tools are step 7; the import of the members and the example strategy are step 8.

## 6. Research over MCP (D-93)

Read-only tools, each marked read-only to the agent's client. Agents read, save drafts and run simulations; they
do not import data or change the universe, and a tool that finds nothing says which operator command brings it.

| Tool | Answers | Bounds |
|------|---------|--------|
| `list_universe` | The members of the month of a day, by rank, as they were ranked then: symbol, status, listing and delisting time, 30-day quote volume, first and last stored minute. Without a day, the last month built | One month an answer; a month outside what was built is refused |
| `get_depth` | Bid and ask notional within 1, 2 and 5 % of the price: the stored 5-minute rows, or their average over 15m, 1h, 4h or 1d steps (two decimals), and the days of the window with no row | 1,000 rows |
| `get_liquidations` | Long and short liquidations, counted and in quote notional: the stored minutes, or their sum over 5m to 1d steps, the days the stream was not heard whole, and a note that the numbers are a lower bound | 1,000 rows; a window the stream never heard is refused, never answered with zeros |
| `list_instruments` | Every instrument with candles and its first and last stored minute, read from the index (two rows an instrument) instead of grouping every candle | |
| `screen_market`, `get_listing_stats` | Ranking the market from `daily_stats`, and what a coin did after its listing | Not built yet; with them comes a rate limit of their own |

A series is asked for with `from`, `to` (exclusive) and `every`; more than 1,000 rows is refused with the count, so
the agent shortens the window or lengthens the step. Every statement of these tools runs under
`MCP_STATEMENT_TIMEOUT_MS` (5 seconds), waiting for a lock included: a read behind a long import ends with "The
data took longer than 5 s to read", not with a held connection. `tools/list` answers every tool in its first page.

## 7. Positions and risk (D-94)

Universe strategies are planned for up to 10 open positions at about 0.3 % risk each. The account defaults stay 6
positions and 3 % total open risk; the operator raises them in Settings (re-authentication, 24 hours, D-19).
Correlation buckets are not implemented yet; most perpetuals move together, so a book of 10 is less diversified than
it looks.

## 8. Cost

| Work | Estimate |
|------|----------|
| Import of the top 50 from 2024-11, point in time (300 members, 435 spans) | about 15 GB in PostgreSQL, several hours, once |
| Backtest of a universe strategy over 2 years | about 25 minutes in the engine (measured on synthetic candles, section 5), plus the reading of 52.6 million candles; under an hour expected, measured in step 8 |
| Gates | many hours; run them in the background |
