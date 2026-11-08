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

Decisions happen when a candle closes. What happens next:

| Order | Fill rule |
|-------|-----------|
| Market | At the next candle's open, plus latency, plus slippage |
| Limit | Filled only if the price trades through the limit by at least one tick (a touch is not a fill). At most 10 % of the candle's volume per candle; the rest waits or expires |
| Stop (protective) | Triggered when the candle's range crosses the stop; filled at the stop plus slippage, or at the open if the candle opened beyond the stop (gap) |
| Take profit | As a limit order |

- **Slippage**: half the estimated spread plus an impact term that grows with order size relative to recent
  volume. Calibrated from paper and live fills once they exist; conservative defaults before.
- **Same candle ambiguity**: when one candle contains both the stop and the take profit, the simulator replays
  that candle on 1-minute data if available. Without it, the stop is assumed to fill first. Optimistic
  assumptions are never the default.
- **Fees**: maker or taker by order type, at the account's tier, charged in the asset the exchange would charge.
- **Funding**: applied at each funding timestamp with the historical rate.
- **Filters**: lot step, tick size and minimum notional applied by the same code the live order manager uses.

## 4. Determinism

A run is defined by: strategy version hash, data window, universe, cost model version, seed, engine version. The
same definition gives the same report, byte for byte. Ties in event order are broken by a fixed rule (time,
then instrument, then event type). Every report stores its definition and hash; golden tests pin a few of them
(`docs/conventions.md`).

## 5. Validation methods

- **Hold-out**: the last 20 % of the available history is the test set. It is used once per strategy version,
  for the final check. Looking at it to tune anything makes it in-sample.
- **Walk-forward**: rolling windows, by default 12 months in-sample and 3 months out-of-sample, stepped by 3
  months. Parameters are chosen in each in-sample window and judged only on the following out-of-sample window.
- **Parameter robustness**: every numeric parameter is moved 20 % up and down. A good strategy sits on a plateau,
  not on a peak.
- **Cost stress**: the same run with fees times 1.5 and slippage times 2.
- **Monte Carlo**: 2,000 resamples of the trade sequence (bootstrap with replacement) and of daily returns (block
  bootstrap), plus runs that skip a random 10 % of trades. Output: distributions of final return, maximum
  drawdown and longest losing streak.
- **Regime breakdown**: results split by the regime labels in force at entry (deterministic labels before the
  journal starts, recorded analyst labels after).
- **Multiple testing**: every backtest of a strategy family, by the operator or an agent, is counted. The count
  feeds the deflated Sharpe ratio. For parameter searches, the probability of backtest overfitting is estimated
  with combinatorially symmetric cross-validation.

## 6. Metrics in every report

Net profit and return after all costs; net expectancy per trade in quote currency and in R, with its 95 %
confidence interval; profit factor; number of trades; win rate and average win and loss (shown, never gated on);
maximum drawdown and its duration; Sharpe, Sortino and Calmar ratios; time in market; turnover; costs as a share
of gross profit; monthly returns table and the share of positive months; maximum adverse and favorable excursion
per trade; comparison with the baselines in section 11.

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
strategy without a D-number.

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

A strategy that does not beat cash after costs and risk is not promoted, however busy it looks.

## 12. Performance targets

To be measured in Phase 2 and adjusted with a D-number:

- one year of 1-minute candles, one instrument, one strategy: under 60 seconds;
- a 3-year walk-forward with a 50-point parameter neighbourhood: under 30 minutes, spread over the `simulation`
  queue workers.
