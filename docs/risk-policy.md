# Risk policy

Hard limits enforced by `app/Risk` on every engine event, in every mode. Strategies may be tighter, never looser.
Values below are defaults in `config/risk.php`; per-account overrides live in the database. Lowering a limit
takes effect at the next event; raising one needs re-authentication and waits 24 hours (D-19).

## 1. Account limits

| Limit | Default | Ceiling | On breach |
|-------|---------|---------|-----------|
| Risk per trade (loss if the stop is hit) | 0.5 % of equity | 1 % | Intent resized down |
| Total open risk (sum over open positions) | 3 % | 5 % | New intents rejected |
| Open risk per correlation bucket (assets with 30-day correlation above 0.7 share a bucket) | 1.5 % | 2.5 % | New intents in the bucket rejected |
| Notional per instrument | 25 % of equity | 40 % | Intent resized down |
| Gross exposure (both legs of a carry pair count) | 150 % | 200 % | New intents rejected |
| Net directional exposure | 100 % | 100 % | New intents rejected |
| Open positions | 6 | 10 | New intents rejected |
| Daily loss (from equity at 00:00 UTC, realized plus unrealized) | 2 % | 3 % | Halt new entries until 00:00 UTC |
| Weekly loss (from Monday 00:00 UTC) | 5 % | 7 % | Halt new entries until the operator resumes |
| Drawdown from equity peak | 10 % | 15 % | Halt all strategies; operator review |
| Hard drawdown from equity peak | 15 % | 20 % | Flatten the account, then halt |

A halt stops new entries and cancels working entry orders. Protective orders stay on the exchange. Positions are
closed only by their own exits or by a flatten.

Every directional entry under this policy carries a stop: risk per trade is the loss if the stop is hit, so an
entry without one is rejected. The one exception is a carry pair (long spot, short perpetual for the same quantity),
whose protection is the hedge: it is sized by its template's maximum position, never above the notional per
instrument, with both legs counted in gross exposure and the perpetual leg's extra margin (D-48). A stop may be moved towards the price, never away from it. How the engine applies these limits,
in order, is in `docs/engine.md`, section 5. Backtests of benchmarks (buy and hold, the 50/200 trend rule) use a
separate benchmark profile that this policy does not govern and that paper and live refuse (D-40).

## 2. Perpetual futures (when enabled, D-4)

| Limit | Default | Ceiling |
|-------|---------|---------|
| Leverage per position | 2x | 3x |
| Margin mode | isolated | isolated |
| Liquidation distance | at least 3 times the stop distance | n/a |
| Extra margin on carry legs | 50 % | n/a |

An entry whose liquidation price would sit closer than three stop distances is rejected: the stop must always
trigger long before the exchange liquidates.

## 3. Circuit breakers

| Breaker | Condition | Action |
|---------|-----------|--------|
| Stale data | No closed candle within 2 timeframes, or WebSocket silent for 30 s on an instrument with open orders | No new entries on that instrument until fresh |
| Price sanity | Last price more than 5 % away from the 1-minute median of the last 15 minutes | Reject entries on that instrument |
| Wide spread | Spread above 3 times its 30-day median | No market orders on that instrument |
| Volatility shock | A 1-minute move above 5 times the 1-minute ATR of the last hour | Pause entries on that instrument for 15 minutes |
| Venue errors | 5 order errors in 10 minutes, or any authentication error | Halt the account and alert |
| Reconciliation | A mismatch not resolved within 2 events | Halt the account and alert |
| Clock skew | Local clock more than 1 s away from the exchange server time | Halt the account and alert |

## 4. Pre-trade checks on every order

- Quantity rounded down to the lot step; notional at least the exchange minimum.
- Limit prices on the correct side of the market and within 2 % of the last price.
- Exits on perpetuals are reduce-only.
- Client order id unique and derived from the decision id, so a retry cannot double an order.
- At most one entry intent per strategy, instrument and candle.
- The account mode matches the venue: a live venue refuses an engine started in paper mode and the reverse.

## 5. Kill switch

| Level | Effect | Who |
|-------|--------|-----|
| Pause strategy | The strategy emits no new intents; its positions keep their exits | Operator, automatic demotion |
| Halt account | No new entries for the account; working entry orders cancelled; protective orders kept | Operator, breakers, loss limits |
| Flatten account | Cancel every order, close every position at market (carry pairs leg by leg, hedge last), then halt | Operator, hard drawdown limit |

The halt button is on every screen. The same actions exist on the command line (`php artisan engine:halt`,
`engine:flatten`) and work when the web process is down. Resuming after a halt is an operator action only.

## 6. Promotion ladder

| Stage | Capital | Enter when | Leave down when |
|-------|---------|------------|-----------------|
| Draft | none | It compiles | n/a |
| Backtested | none | Backtest gates pass (`docs/simulation.md`, section 7) | A new data window fails the gates |
| Paper | none | Automatic after the gates | Results leave the band (section 7) |
| Live micro | Risk per trade times 0.25, notional at most 5 % of equity | 30 days and 30 trades in paper inside the band, and the operator approves | Out of band, any execution anomaly, or the operator |
| Live | Size multiplier 0.5, then 1.0 | 30 days and 20 trades at the previous step inside the band, and the operator approves each step | Out of band, drawdown budget breached, or the operator |

Templates with few trades (carry) use duration-based thresholds stated in their own document.

## 7. The band

The band is what the backtest says the live results should look like. A strategy is out of band when any of
these holds:

- the 95 % confidence interval of net expectancy over its last 30 trades lies entirely below the backtest's;
- its drawdown exceeds the 95th percentile of the backtest's Monte Carlo drawdown distribution;
- a CUSUM test on trade results against the backtest mean alarms;
- average slippage plus fees per trade exceeds the cost model by more than 50 %.

Out of band means demotion one stage down, a journal entry with the numbers, and a notification.

## 8. Changing this policy

The values here are the defaults. Changing a default is a code change with a D-number. Changing an account
override is an operator action in the UI, journaled, with the asymmetry of D-19. No agent can change either.
