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
stores dated rows in `universe_members` (instrument, from, to). A backtest at time t only sees the members at t. A
coin that was in the top 50 in 2025 and was delisted later is in the 2025 backtest.

## 3. Storage at scale (D-90)

50 perpetuals over two years of 1-minute candles are about 52 million rows, about 11 GB. `candles` is partitioned by
month on `open_time` (PostgreSQL declarative partitioning, no extension). `daily_stats` (per instrument per day:
open, high, low, close, quote volume, trades, average funding, open interest) serves rankings, screens and listing
studies without reading minutes; it is filled for every symbol from the daily kline archives. The feed reads many
instruments with a server-side cursor in hour chunks. `start-local.ps1` gives PostgreSQL `shared_buffers=1GB` and
`work_mem=64MB`.

## 4. Depth, positioning and liquidations (D-91)

| Data | Source | History | Stored |
|------|--------|---------|--------|
| Order book depth | `futures/um/daily/bookDepth` archives | from 2023, most symbols | `book_depth`: bid and ask notional within 1, 2 and 5 % of the price, per instrument per 5 minutes |
| Long/short and taker ratios | `futures/um/daily/metrics` archives (already read for open interest) | as open interest | columns of `open_interest` |
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
- `rank(expr)` (1 is the highest), `percentile(expr)`, `top(n, expr)`, `bottom(n, expr)` exist only in a universe
  strategy. At each close of the strategy's timeframe the engine evaluates every member, then ranks them (decimals,
  ties broken by symbol), then decides.
- Exits and stop moves of the batch go first. Entries go in rank order once those closes have filled, so freed
  positions and risk go to the best ranked. Every entry has a stop, as everywhere.
- A member enters the ranking after its warmup; a delisting closes its position at the last price before
  `delisted_at`.
- Per-symbol strategies run exactly as before; the golden reports do not move.

## 6. Research over MCP (D-93)

Read-only tools: `list_universe`, `screen_market`, `get_depth`, `get_liquidations`, `get_listing_stats`, with row and
window caps and their own rate limit. Agents read, save drafts and run simulations; they do not import data or
change the universe.

## 7. Positions and risk (D-94)

Universe strategies are planned for up to 10 open positions at about 0.3 % risk each. The account defaults stay 6
positions and 3 % total open risk; the operator raises them in Settings (re-authentication, 24 hours, D-19).
Correlation buckets are not implemented yet; most perpetuals move together, so a book of 10 is less diversified than
it looks.

## 8. Cost

| Work | Estimate |
|------|----------|
| Import of 50 perpetuals x 2 years | about 11 GB in PostgreSQL, several hours, once |
| Backtest of a universe strategy over 2 years | 1 to 2 hours |
| Gates | many hours; run them in the background |
