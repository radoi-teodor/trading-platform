# Strategy configurator

A strategy can be written two ways, and both end in the same typed object (D-14):

- **Trader language**: the operator writes the DSL directly, in the editor in the UI or as a file.
- **Natural language**: the operator describes the idea in plain words (Romanian or English) to a research agent
  working over MCP (D-63, D-64); it writes the DSL, runs backtests and shows its assumptions; the operator approves.

Whatever the path, the platform compiles the DSL, checks it, renders it back into plain sentences by code (not by
the model), and stores it as an immutable version. Only compiled versions can be backtested, paper traded or
promoted.

## 1. A strategy document

```yaml
strategy: btc-trend-4h                # id: lowercase, digits, hyphens
title: BTC trend follower on 4h
family: trend                         # trend | mean_reversion | carry
hypothesis: >
  Crypto trends persist after a moving-average cross on 4h when the daily trend agrees.
  Paid by late trend followers and by shorts forced to cover.
fails_when: Choppy ranges with ADX under 20; expect many small losses there.

market:
  exchange: binance
  type: spot                          # spot | perp
  instruments: [BTC/USDT]             # ccxt unified symbols; perps look like BTC/USDT:USDT
timeframe: 4h                         # strategies are evaluated when a candle of this timeframe closes

filters:                              # all must be true for a new entry
  - adx(14) > 20
  - natr(14) < 3                      # skip the most volatile days

entries:
  long:
    when: ema(20) crosses_above ema(50) and close > ema(200) on 1d
    order: market                     # market | limit(offset: -0.1%, expiry: 2 bars) (D-55)
  # short: only allowed when market.type is perp

exits:
  stop_loss: 2 * atr(14)              # distance in price; also 1.5% or level(lowest(low, 10))
  take_profit: 3R                     # optional; R is the initial risk
  trailing: chandelier(22, 3)         # optional; only ever tightens
  time_stop: 60 bars                  # optional
  when: ema(20) crosses_below ema(50) # optional discretionary exit

sizing:
  risk_per_trade: 0.5%                # of account equity, lost if the stop is hit
  max_position: 20%                   # of equity, cap on notional

limits:                               # can only be tighter than docs/risk-policy.md
  max_open_positions: 1
  max_trades_per_day: 2
  cooldown_after_loss: 8h
```

Numbers a search may move are declared as parameters and written `$name` (D-51):

```yaml
params:
  fast: { default: 20, range: [10, 30], step: 5 }
  slow: { default: 50, range: [40, 80], step: 10 }
entries:
  long:
    when: ema($fast) crosses_above ema($slow)
```

The read-back shows a parameter by its value in the version being read. Walk-forward and the robustness check
(`docs/simulation.md`, section 5) move only declared parameters.

A comparison such as buy and hold is written with `profile: benchmark` at the top level. It may say
`stop_loss: none`, is sized by the benchmark profile (all free quote, or 99 % of the free USD-M wallet at 1x) and
runs in backtests only (D-40, D-56). A template document (section 3) has `template:` and `params:` instead of
`entries` and `exits`.

Rules that the compiler enforces:

- Every strategy has a `stop_loss`. No stop, no compile, except `stop_loss: none` under `profile: benchmark`.
- `short` entries only on `perp`. Spot has no shorting (legacy lesson R5).
- `limits` and `sizing` may be tighter than the policy, never looser.
- Every function used must have enough history: the compiler computes the warm-up and refuses a backtest window
  shorter than it.
- Every symbol must exist on the exchange and be tradable in the chosen market type.

## 2. Expression language

### Types

| Type | Examples | Notes |
|------|----------|-------|
| `Series<Number>` | `close`, `ema(20)`, `atr(14) * 2` | One value per candle of the strategy timeframe |
| `Series<Bool>` | `rsi(14) < 30`, `ema(20) crosses_above ema(50)` | |
| `Number` | `20`, `2.5` | Promoted to a constant series where needed |
| `Percent` | `0.5%`, `-0.1%` | Stored as a fraction |
| `Duration` | `30m`, `8h`, `7d`, `60 bars` | |
| `Timeframe` | `1m`, `5m`, `15m`, `1h`, `4h`, `1d`, `1w` | After `on` |
| `RMultiple` | `2R`, `3R` | Only in `take_profit` |
| `String` | `"high_volatility"` | Enum values, checked |
| `Bool` | `true`, `false` | Constant conditions, such as a benchmark's entry |

A parameter `$name` has the type of its default (a number or a percent) and is replaced by its value when a
version is compiled with a parameter set.

Type errors are compile errors with a position and a suggestion.

### Grammar

```ebnf
expr      = or_expr ;
or_expr   = and_expr { "or" and_expr } ;
and_expr  = not_expr { "and" not_expr } ;
not_expr  = [ "not" ] cmp_expr ;
cmp_expr  = add_expr [ cmp_op add_expr ] ;
cmp_op    = ">" | ">=" | "<" | "<=" | "==" | "!=" | "crosses_above" | "crosses_below" ;
add_expr  = mul_expr { ( "+" | "-" ) mul_expr } ;
mul_expr  = unary { ( "*" | "/" ) unary } ;
unary     = [ "-" ] postfix ;
postfix   = primary [ "[" integer "]" ] [ "on" timeframe ] ;
primary   = number | percent | duration | r_multiple | string
          | call | identifier | param | "(" expr ")" ;
param     = "$" identifier ;
call      = identifier "(" [ arg { "," arg } ] ")" [ "." identifier ] ;
arg       = expr | identifier ":" expr ;
```

- `x[1]` is the previous closed candle's value; `x[0]` is the current one.
- `on` binds to the term right before it: `close > ema(200) on 1d` compares the 4h close with the daily EMA.
  Parenthesize to evaluate a whole condition on another timeframe: `(close > ema(200)) on 1d`.
- A higher timeframe always gives its **last closed** candle. A daily value never changes during the day it is
  being formed: no lookahead.
- `a crosses_above b` is true on the candle where `a[1] <= b[1]` and `a > b`.

### Functions (first version)

| Group | Functions |
|-------|-----------|
| Price | `open`, `high`, `low`, `close`, `volume`, `hl2`, `hlc3`, `ohlc4` |
| Averages | `sma(n, src: close)`, `ema(n, src)`, `wma(n, src)`, `hma(n, src)`, `vwma(n)`, `vwap(anchor: 1d)` |
| Momentum | `rsi(n: 14, src)`, `roc(n, src)`, `macd(fast: 12, slow: 26, signal: 9).line / .signal / .hist`, `stoch(k: 14, d: 3).k / .d` |
| Volatility | `atr(n: 14)`, `natr(n)`, `stdev(n, src)`, `realized_vol(n)`, `bb(n: 20, k: 2).upper / .middle / .lower / .width`, `donchian(n).upper / .lower`, `keltner(n, k).upper / .lower` |
| Trend | `adx(n: 14)`, `plus_di(n)`, `minus_di(n)`, `supertrend(n, k).line / .direction` |
| Windows | `highest(src, n)`, `lowest(src, n)`, `change(src, n)` |
| Perpetuals | `funding_rate`, `funding_rate_avg(n)`, `open_interest`, `oi_change(n)`, `basis`, `mark_price` |
| Position | `in_position`, `bars_in_trade`, `trade_r` (current open result in R) |
| Time | `hour_utc`, `weekday` |
| Exits only | `level(series)`, `chandelier(n, k)`, `atr_trail(n, k)` |

Not in the first version (D-52): `open_interest`, `oi_change`, `basis` and `mark_price` are refused at compile
time until their data is stored; phase 5 stores it (D-65). The language has no text values and no functions fed by
language models (D-64). `src` defaults to `close` everywhere.

Each function has a reference test against known values; the old pandas formulas at commit `e0ec48b`
(`agents/indicators/technical.py`) serve as oracles where they apply. New functions are added through `feature-work` with a D-number if they change semantics.

## 3. Semantics

- **When**: a strategy is evaluated once per closed candle of its timeframe, in the engine loop step 6
  (`docs/architecture.md`). It sees nothing of the candle still forming.
- **Entry**: when `filters` and `entries.<side>.when` are true and limits allow, the strategy emits an intent. The
  risk engine sizes and may reject it; the order manager places it.
- **Sizing**: `quantity = equity * risk_per_trade / stop_distance`, capped by `max_position` and by the policy,
  rounded down to the exchange lot step. Below the exchange minimum notional the intent is rejected with reason
  `below_min_notional`, not rounded up.
- **Stops**: placed on the exchange as soon as the entry fills (D-10). Trailing stops move the exchange order and
  never loosen it.
- **R**: the initial risk of the trade in price (entry minus stop for a long). `3R` take profit is three times
  that distance from the entry. Stop and target are fixed at the decision from the decision price, the close, so
  sizing and protection use the same numbers (D-53).
- **Limits**: `sizing` and `limits` are checked by the risk engine as per-strategy limits, after the account's
  (D-54).
- **Exits** fire in this order on a candle: stop (on the exchange, intrabar), take profit (on the exchange),
  `time_stop`, `when`.

### Templates

Some strategies are not single-instrument rules. They are implemented in code as templates and configured by
parameters in the same document:

```yaml
strategy: btc-funding-carry
title: BTC funding carry
family: carry
hypothesis: Leveraged longs pay positive funding; long spot plus short perpetual collects it.
fails_when: Funding turns negative for days, or the exchange fails.
template: funding_carry
params:
  spot: BTC/USDT
  perp: BTC/USDT:USDT
  enter_when: funding_rate_avg(9) > 0.01%      # per 8h period, average of the last 3 days
  exit_when: funding_rate_avg(3) < 0%
  max_leverage_perp: 2
  margin_buffer: 50%                           # extra margin kept on the perp leg
sizing:
  max_position: 30%
```

Templates have their own tests and their own gate thresholds where the defaults do not fit (a carry strategy has
few trades and long holds).

## 4. Read-back

After compiling, a renderer in code turns the AST into sentences. The operator approves the read-back, not the
model's description. For the example in section 1:

> On BTC/USDT spot, every time a 4-hour candle closes: if ADX(14) is above 20 and the normalized ATR(14) is under
> 3; then buy at market when the 20-period
> EMA crosses above the 50-period EMA and the close is above the daily 200-period EMA. Risk 0.5 % of equity per
> trade, at most 20 % of equity in the position. Stop 2 ATR(14) below the entry, on the exchange. Take profit at 3
> times the initial risk. Trail with a chandelier stop (22, 3). Leave after 60 candles, or when the 20 EMA crosses
> below the 50 EMA. At most 1 open position and 2 trades a day; wait 8 hours after a loss.

## 5. The natural-language path

1. The operator writes the idea, for example:
   *"Vreau să cumpăr ETH când scade mult într-o zi liniștită și să ies repede. Nu risca mai mult de jumătate de
   procent pe tranzacție."*
2. The research agent reads the chart with `get_indicators`, reads `dsl_reference`, writes a draft and calls
   `compile_strategy` until it compiles. Every interpretation it makes is listed as an assumption:
   - "scade mult": close at least 2 standard deviations under the 20-period mean on 15m (`bb(20, 2).lower`).
   - "zi liniștită": ADX(14) under 20 and the normalized ATR(14) under its usual level.
   - "ies repede": exit at the 20-period mean, or after 12 candles (3 hours).
   - Stop: 1.5 ATR(14), because the request gave none and every strategy needs one.
3. It saves the draft with `save_strategy`, runs `run_grid` with a walk-forward, reads the result with `get_job`,
   and returns the DSL, the read-back from code, the assumptions, and the results with costs.
4. The operator keeps, edits or drops the draft. Promotion follows `docs/risk-policy.md`.

The agent cannot skip compilation, cannot hide an assumption (the read-back would show it), and cannot promote.

## 6. Versions

- A version is immutable: id, version number, canonical YAML, its SHA-256, author (`operator` or
  `agent:<token name>`), parent version, created time, stage history.
- Editing creates a new version. Backtest reports, paper and live results always point at the exact version.
- `php artisan strategy:export` writes versions to `catalog/strategies/<id>.v<n>.strategy.yaml` for review in
  git; `strategy:import` loads them back.

## 7. Errors

Errors give the position, what is wrong in plain words, and a fix when there is an obvious one:

```
line 18, column 9: stop_loss is missing. Every strategy needs a stop. Example: stop_loss: 2 * atr(14)
line 22, column 15: cannot compare a price series with a percent (close > 2%). Did you mean close > close[1] * 1.02 ?
line 9, column 3: entries.short needs market.type perp; BTC/USDT on spot cannot be shorted.
line 31, column 25: risk_per_trade 2% is above the policy limit of 1%.
```
