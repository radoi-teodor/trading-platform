# Simulation

How the platform finds out whether a strategy would have worked, whether it works now without money, and why a
live day went the way it did. Everything here runs the same engine loop as live trading (D-8).

## 1. Modes

| Mode | Question it answers | Data | Venue |
|------|---------------------|------|-------|
| Backtest | Would it have worked? | History | Simulated |
| Walk-forward | Does it keep working on data it was not tuned on? | History, rolling windows | Simulated |
| Monte Carlo | How bad could it plausibly get? | Backtest trades and returns | n/a (resampling) |
| Paper | Does it work now? | Live feed | Simulated on live data |
| Testnet | Do our orders behave on the real exchange software? | Binance testnet | Binance testnet |
| Replay | Why did this live day go this way? | Recorded live inputs | Simulated, deterministic |

Testnet prices and liquidity differ from the real market. Testnet proves order mechanics (types, filters,
rejects, reconciliation), never profitability.

## 2. Data

- Point in time: every record has the exchange time and our received time. A backtest at time `t` sees only what
  was received before `t`.
- Coverage check before every run: gaps, duplicate candles, zero-volume runs, outliers against the 1-minute
  median. A run on data with unresolved gaps refuses to start and says where the gaps are.
- Delisted instruments stay in the database with their history, so a universe chosen "as of" a date is not
  biased by survivors.
- Funding history for perpetuals, fee rates as read from the account (D-28) and exchange filters as they were
  (lot step, minimum notional) are versioned by date.

## 3. Fill and cost model

Decisions happen when a strategy's candle closes. The simulated venue then works on 1-minute candles (D-36), so
what happens inside a 4-hour candle is replayed minute by minute instead of guessed. An order decided at `t` is
live from the minute that opens at `t`.

| Order | Fill rule |
|-------|-----------|
| Market | At the open of the next minute, plus slippage. Taker fee |
| Limit | Filled only if the price trades through the limit by at least one tick (a touch is not a fill), at the limit price. At most 10 % of the minute's volume per minute; the rest stays working. Maker fee. A limit that is already marketable when it arrives fills like a market order, never worse than its limit, taker fee |
| Stop (protective) | Triggered when the minute's range reaches the stop; filled at the stop minus slippage, or at the open minus slippage if the minute opened beyond the stop (gap). Taker fee |
| Take profit | As a limit order |

- **Same minute.** When one minute reaches both the stop and the take profit, the stop fills first. Optimistic
  assumptions are never the default.
- **Slippage (cost model v1, D-37).** Market and stop fills move against the order by half an estimated spread
  plus an impact term: `price × (0.0002 + 0.1 × participation)`, where participation is the order quantity divided
  by the minute's volume. The values are conservative defaults in `config/simulation.php`, calibrated from paper
  and live fills in Phase 4 under a new cost model version. Every fill records its slippage, so the report shows
  what the assumption cost.
- **Fees (D-28, D-38).** Maker or taker by order type, at the rates read from the account; before an account
  exists, Regular (VIP 0) without the BNB discount: 0.100 % maker and taker on spot. On spot the fee is charged in
  the asset received.
- **Funding.** Applied at each funding timestamp with the historical rate, to the positions open at that moment,
  valued at the close of the minute that ends then (D-44).
- **Perpetuals.** Stops and take-profits are market orders triggered on the minute's range, reduce-only, filled at
  the trigger or at the open when the minute gapped through it, plus slippage; the stop is matched first (D-46). A
  reduce-only order that would open or increase a position expires. Fees are 0.020 % maker and 0.050 % taker on the
  notional, in USDT (D-47). The mark price is the last price (D-44). A position is liquidated when the minute opens
  beyond its liquidation price, or its adverse extreme reaches it after the orders of that minute; the whole
  isolated margin is lost (D-45).
- **Filters.** Lot step, tick size and minimum notional applied by the same order manager live trading uses, with
  the filters valid at the time (`docs/engine.md`, section 4).

## 4. Determinism

A run is defined by: strategy name, version and parameters, data window, universe, initial balances, fee rates,
cost model version, risk profile, seed and engine version. The same definition on the same data gives the same
report, byte for byte, on any machine (D-39, D-42):

- every number is a decimal (`BcMath`), statistics included; no float enters a report;
- the report is canonical JSON: keys sorted, numbers as strings, no wall-clock time; its identity is the SHA-256
  of that text;
- the data is identified by a fingerprint, the SHA-256 of every candle the run read, so the same definition on
  corrected data gives a different report, visibly;
- ties in event order are broken by a fixed rule (time, then instrument, then event type);
- `Engine::VERSION` is raised with every change of behaviour.

Golden tests pin the hashes of a few runs on committed data (`docs/conventions.md`). Reports are stored as
compressed JSON under `storage/app/simulations`, with a summary row in `backtest_runs`.

## 5. Validation methods

- **Hold-out**: the last 20 % of the available history is the test set. It is used once per strategy version,
  for the final check. Looking at it to tune anything makes it in-sample.
- **Walk-forward**: rolling windows, by default 12 months in-sample and 3 months out-of-sample, stepped by 3
  months. Parameters are chosen in each in-sample window and judged only on the following out-of-sample window.
  Each parameter set of the grid runs once over the whole history, all in one pass, and the windows are slices of
  these runs (D-57). A trade counts in the window where it closes. The in-sample choice is the set with the
  highest net expectancy per trade among those with at least the gate's trade count in the window (ties: the
  set listed first). `--workers` splits the grid over processes; the result does not depend on it.
- **Parameter robustness**: every declared parameter (`$name`, D-51) is moved 20 % up and down, alone and in
  every combination, rounded to its step and kept in its range. A good strategy sits on a plateau, not on a peak.
- **Cost stress**: the same run with fees times 1.5 and slippage times 2.
- **Monte Carlo**: 2,000 resamples of the trade sequence (bootstrap with replacement) and of daily returns (block
  bootstrap, blocks of 5 days), plus runs that skip a random 10 % of trades, from a seeded `Xoshiro256StarStar`
  (D-58). Output: distributions of final return, maximum drawdown and longest losing streak, as percentiles 5,
  50 and 95.
- **Regime breakdown**: results split by the regime labels in force at entry (deterministic labels before the
  journal starts, recorded analyst labels after). The deterministic labels (D-58): daily ADX(14) at 25 or more is
  `trend`, under 20 `range`, otherwise `unknown`; 30-day realized volatility above its 80th percentile over the
  history read is `high_volatility`, which takes precedence.
- **Multiple testing**: every backtest of a strategy family, by the operator or an agent, is counted in
  `strategy_trials`. The count and the variance of the trials' Sharpe ratios feed the deflated Sharpe ratio of
  Bailey and López de Prado (2014). For parameter searches, the probability of backtest overfitting is estimated
  with combinatorially symmetric cross-validation over 16 partitions of the daily returns (Bailey, Borwein, López
  de Prado and Zhu, 2017).
- **Hold-out once**: a version's use of the hold-out is recorded; a second request for it is refused.

## 6. Metrics in every report

Net profit and return after all costs; net expectancy per trade in quote currency and in R, with its 95 %
confidence interval; profit factor; number of trades; win rate and average win and loss (shown, never gated on);
maximum drawdown and its duration; Sharpe, Sortino and Calmar ratios; time in market; turnover; costs as a share
of gross profit; monthly returns table and the share of positive months; maximum adverse and favorable excursion
per trade; comparison with the baselines in section 11.

How they are computed (D-39: decimals, 18 digits, the same result on every machine):

| Metric | Definition |
|--------|------------|
| Return | Final equity divided by initial equity, minus 1. Final equity marks open positions at the last close |
| CAGR | `(final / initial) ^ (365 / days) - 1` |
| Maximum drawdown | Largest fall from a running peak, on equity marked every minute; its duration runs from the peak to the recovery, or to the end |
| Daily returns | From equity at 00:00 UTC of consecutive days |
| Sharpe | Mean daily return divided by its sample standard deviation, times `sqrt(365)` (crypto trades every day); no risk-free rate |
| Sortino | Mean daily return divided by the downside deviation `sqrt(sum(min(r, 0)^2) / n)`, times `sqrt(365)` |
| Calmar | CAGR divided by maximum drawdown |
| Expectancy | Mean net profit per closed trade, in quote and in R; 95 % interval `mean ± t × sd / sqrt(n)`, Student t below 30 trades, 1.96 from 30 |
| Profit factor | Sum of winning net profits divided by the absolute sum of losing ones; absent when nothing lost |
| Time in market | Share of minutes with an open position |
| Turnover | Traded notional divided by initial equity |
| Costs | Fees plus slippage, valued in quote at fill time, and their share of gross profit |

## 7. Gates for the Backtested stage

All must hold, on out-of-sample data (walk-forward windows plus the hold-out):

| Gate | Default |
|------|---------|
| Trades | At least 100 (slow strategies may argue for 30, recorded in their version) |
| Edge (D-15) | Lower bound of the 95 % confidence interval of net expectancy per trade above zero |
| Profit factor after costs | At least 1.2 |
| Walk-forward consistency | Positive in at least 2 of every 3 out-of-sample windows |
| Deflated Sharpe ratio | Probability that the true Sharpe is above zero at least 0.95 |
| Robustness | At least 80 % of the parameter neighbours keep a profit factor of 1.1 or more |
| Cost stress | Net expectancy still above zero |
| Outlier dependence | Removing the best 1 % of trades keeps net expectancy above zero |
| Drawdown | Monte Carlo 95th percentile drawdown, at the planned size, below the account drawdown limit |

A failed gate is reported with its numbers and what would have to change. Gates are never relaxed for one
strategy without a D-number. The carry template counts at least 12 months out of sample and 10 closed cycles
instead of trades (D-60). `php artisan strategy:gates` writes the gate report; `strategy:promote` moves a version
from Draft to Backtested only when every gate passes, and never to a live stage (D-16).

## 8. Paper trading

- Default paper mode runs the strategy on the live feed against the simulated venue, with the same fill and cost
  model. It runs continuously and costs nothing.
- Every live strategy keeps a **paper twin**: the same version running in paper. The difference between twin and
  live measures execution quality (slippage, missed fills, latency).
- Paper results feed the band (`docs/risk-policy.md`, section 7) and the promotion decision.

## 9. Replay

The engine records what it received during live and paper trading: candles, trades, ticks used, commands,
agent outputs read, venue responses. Replay feeds those records into the engine with `SimClock` and checks that
it makes the same decisions. A replay that diverges is a bug by definition. Replay never calls the exchange or
an LLM.

## 10. LLM outputs in simulation

A model has seen market history, so it cannot be backtested on it (D-17).

- Backtests never call an LLM.
- Agent-derived features (`regime()`, `risk_flag()`, `event()`) read the journal: only outputs that were really
  produced before the simulated time count.
- Before the journal covers a period, those features return their neutral defaults. A strategy that needs them
  to be profitable cannot pass the gates on old data, and must prove itself forward in paper.

## 11. Baselines

Every report compares the strategy with:

- buy and hold of the same instrument (or BTC for a basket);
- a daily 50/200 moving-average trend rule on the same instrument;
- cash (zero return), because staying flat is always an option.

Buy and hold and the trend rule run through the same engine, with the benchmark risk profile: all free quote in
one position, no stop, no loss halts, backtests only (D-40). They read the same candles in the same pass as the
strategy. The trend rule needs 200 days before the window; when the data does not reach that far, the report says
the comparison is missing and why.

A strategy that does not beat cash after costs and risk is not promoted, however busy it looks.

## 12. Performance targets

- One year of 1-minute candles, one instrument, one strategy: under 60 seconds. Measured on 2026-11-08 in the
  development container (PHP 8.4, one core, PostgreSQL on the same machine): `donchian-breakout` on BTC/USDT from
  2025-10-01 to 2026-10-01, 531,360 candles with its warmup, 49 s. Met; no D-number needed.
- With both benchmarks in the same pass, three accounts over 1,033,920 candles (2024-11 to 2026-10, the 50/200 rule's
  warmup included) take 156 s.
- Perpetuals, measured on 2026-11-08 in the same container: a run on the BTC perpetual from 2025-06-01 to
  2026-10-01 with its two spot benchmarks reads 1,978,560 candles in 206 to 219 s (short 50/200 rule, short Donchian);
  the funding carry, which follows spot and perpetual, takes 219 to 236 s. About 36 µs per candle and account, below
  the spot runs' 50 µs.
- A 3-year walk-forward with a 50-point parameter neighbourhood: under 30 minutes, spread over the `simulation`
  queue workers. To be measured in Phase 3, where walk-forward is built.
