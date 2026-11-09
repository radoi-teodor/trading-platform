# Decisions

Every design choice that shapes the code gets a D-number here, before the code that follows it. A D-number is
never renumbered or deleted; a decision that changes is marked "Superseded by D-x" and the new one explains why.
The Open list at the end holds what is still waiting on the owner.

Format: **D-n. Title** (date, status). Context, decision, consequences.

## Foundation

**D-1. Rebuild instead of refactor** (2026-11-08, accepted; the legacy-code clause superseded by D-25)
The review in `assessment.md` found risk limits that nothing enforces, an edge that is negative after fees,
client-side stops, plaintext credentials and no tests. Fixing that in place costs more than a clean design.
The legacy code (`agents/`, `laravel/` at the repository root) stays read-only for reference until the new
platform reaches the same screens, then it is deleted. Git keeps it.

**D-2. All PHP, Laravel** (2026-11-08, accepted)
One language, one deploy, one test suite, for a single operator who knows Laravel. Current Laravel, PHP 8.4 or
newer. Exchange access through the PHP edition of `ccxt` (REST, and WebSocket through ReactPHP). Claude through
the official Anthropic PHP SDK. Python is not on the live path. If classical machine learning is ever needed, it
comes as a separate research lab that reads the same data and never places or changes orders (a new D-number).
Consequence: the backtest and execution engine is ours (D-9).

**D-3. UI: Livewire and Alpine** (2026-11-08, accepted)
Server-rendered Livewire components, Alpine for local interaction, Vite for assets, TradingView Lightweight Charts
from npm, Laravel Reverb for live updates. No CDN scripts at runtime. JavaScript only where the browser must do
the work (charts, keyboard shortcuts).

**D-4. Markets in the first version** (2026-11-08, accepted)
Crypto only. Binance first. Spot is first-class. USD-M perpetual futures are built behind a capability flag and
enabled only once the operator has confirmed access from their jurisdiction (Open item O-1). A second exchange
(Bybit) comes after the engine is proven on Binance. Update 2026-11-08: the operator confirmed USD-M futures
access from Romania (O-1 closed). Perpetuals are in scope from the first engine phases, still behind the flag,
which the operator turns on per account.

**D-5. One operator** (2026-11-08, accepted)
One human operator, several exchange accounts or sub-accounts. No public registration. The platform does not
manage money or send signals for other people: that would be an investment service under MiFID II, and for
crypto a service under MiCA.

**D-6. LLM backend: the Claude API** (2026-11-08, superseded by D-64)
Agents call the Claude API through the official PHP SDK, with tool use and structured output. The model is set
per agent role in `config/agents.php`; the default is `claude-opus-5-5`. A provider interface keeps a second
backend possible. The `claude` CLI is not used: no tool use, no cost accounting, prompt in argv.

**D-7. The model proposes, deterministic code disposes** (2026-11-08, accepted)
No LLM output reaches an exchange directly. Agents write briefs, events and draft strategies; the risk engine
sizes and limits; the execution engine places orders. Enforced by architecture tests: nothing under
`app/Agents` may depend on `app/Execution`.

**D-8. One code path for backtest, paper and live** (2026-11-08, accepted)
The same strategy runtime, risk engine, order manager and ledger run in all three modes. Only the `Venue`
(simulated or exchange) and the `Clock` (simulated or wall) change. A result that only exists in one mode does
not count.

**D-9. Our own engine, bar and trade level** (2026-11-08, accepted)
Consequence of D-2: no NautilusTrader or freqtrade. Scope: decisions on candle close (1 minute to 1 day), fills
modelled from candles and trades. No order-book microstructure, no market making, no high-frequency trading.
Reaction time is seconds.

**D-10. The exchange is the source of truth** (2026-11-08, accepted)
Protective orders (stop-loss, take-profit) live on the exchange, not in process memory. Local state is
reconciled from the exchange on every engine event and on a timer. A restart loses nothing.

**D-11. Decimal arithmetic** (2026-11-08, accepted)
Prices, quantities, money and fees are `BcMath\Number` inside value objects (`Price`, `Quantity`, `Money`),
stored as `numeric(36,18)`, serialized as strings. No `float` and no integer cents in the domain.

**D-12. Time: UTC and an injected clock** (2026-11-08, accepted)
Every timestamp is UTC, stored as `timestamptz`, handled as `CarbonImmutable`. Domain code reads time only from
the `Clock` interface (`WallClock`, `SimClock`), never from `now()`. Market sessions are an attribute of the
instrument, not of the server clock.

**D-13. Storage** (2026-11-08, accepted)
PostgreSQL is the only database. Market data (candles, trades, funding, open interest) in TimescaleDB
hypertables if the host provides the extension, plain partitioned tables otherwise; the repository hides the
difference. Redis for queues, cache, locks and the tick stream.

## Strategy and risk

**D-14. Strategy DSL** (2026-11-08, accepted)
A strategy is a YAML document with expressions in our own small typed language (`docs/strategy/configurator.md`).
Our own parser, not Symfony ExpressionLanguage, because the trader syntax needs infix `crosses_above`, timeframe
qualifiers (`on 4h`) and static types. Natural language goes through a research agent over MCP (D-64), which
must produce valid DSL; a deterministic read-back renders the compiled strategy into plain sentences so the operator
checks meaning without trusting the model's paraphrase.

**D-15. What "95 %" means** (2026-11-08, accepted)
No target is set on win rate or on "percentage of profitable trades": those reward hidden tail risk. The 95 % is
the confidence level of the promotion gate: a strategy reaches live capital only when the lower bound of the
95 % confidence interval of its net expectancy per trade, out of sample and after all costs, is above zero, and
it stays live only while its live results stay inside the band its tests predict. Portfolio targets are
expressed as risk limits (`docs/risk-policy.md`), not as promised returns.

**D-16. Promotion ladder with automatic demotion** (2026-11-08, accepted)
Draft, backtested, paper, live-micro, live. Moving up needs the gates in `docs/risk-policy.md` and, for any live
stage, the operator's explicit approval. Moving down is automatic.

**D-17. LLM outputs are point-in-time features** (2026-11-08, superseded by D-64)
A model has seen market history in training, so a backtest where it "decides" on past data is contaminated.
Every agent output is journaled with the time it was produced. Backtests may use only outputs that were really
produced at that time; before the journal starts, LLM-derived features take their neutral default. LLM agents
are evaluated forward only (paper and live).

**D-18. Sources: catalog in git, state in the database** (2026-11-08, superseded by D-64)
Source definitions live in `catalog/sources.yaml`, reviewed like code. Runtime state (enabled, weight, health,
last fetch) lives in the database and is edited from the UI. Operator-added sources are stored with
`origin = operator` and exported to the catalog by command. Source content is untrusted data, never
instructions.

**D-19. Asymmetric risk changes** (2026-11-08, accepted)
Limits live in `config/risk.php` and in the database for per-account overrides. A change that lowers risk takes
effect at the next engine event. A change that raises risk needs re-authentication and takes effect after 24
hours, and is journaled.

**D-20. Secrets** (2026-11-08, accepted)
Exchange keys and tokens are stored with Laravel encrypted casts; the whole platform is PHP, so one encryption
scheme serves everyone. Exchange keys must be trade-only (no withdrawal permission) and IP-restricted. No secret
in code, config files, docs, logs, commits, prompts or journal entries.

## Process

**D-21. Documentation language and style** (2026-11-08, accepted)
Code, docs, commit messages and UI text in English: plain, no emojis, no em dashes. The assistant talks to the
operator in the language the operator writes in.

**D-22. Committed database** (2026-11-08, accepted)
The SQLite file committed by the legacy app is removed from the tip of the repository and ignored. History is
not rewritten; the operator revokes the exposed exchange key instead.

**D-23. Branches** (2026-11-08, accepted)
Work happens on a short-lived branch (the one a session is given, otherwise `feat/<slug>` or `fix/<slug>`),
committed and pushed after every step. Changes reach `main` through a pull request with a green CI. Conventional
Commits.

**D-24. Visual identity: Graphite** (2026-11-08, accepted)
Three candidate identities were drawn up in `docs/brand.md`. The operator chose Graphite: dark graphite, ice-blue
accent, amber kept for caution, IBM Plex Sans and Plex Mono, dense layout. It is binding for every screen,
chart, notification and report. Harbor and Voltage stay in `docs/brand.md` as rejected alternatives for the
record; nothing may use their values.

**D-25. Legacy code deleted in Phase 0** (2026-11-08, accepted)
The operator chose to delete the old Python agents, the old Laravel app, the wiki and the old scripts at once
instead of keeping them under `legacy/` until parity. They remain readable in git at commit `e0ec48b`, which
`assessment.md` cites file by file. Indicator oracles and other references point at that commit.

**D-26. Product name: Trading X** (2026-11-08, accepted, working name)
The product is called Trading X until the operator renames it. The mark is the name set in IBM Plex Sans 600
(`docs/brand.md`, A12). "Graphite" stays a codename for the identity.

**D-27. Hosting: local first** (2026-11-08, accepted)
The platform runs on the operator's machine for now: Docker Compose for PostgreSQL and Redis, PHP processes
under a local supervisor. Because the database runs in our own container, TimescaleDB is available; whether to
use it is decided with the market data schema in Phase 1. Moving to a server is a new D-number, and it changes
the exchange key IP restriction (`docs/security.md`, section 2).

**D-28. Fees come from the account, not from assumptions** (2026-11-08, accepted)
The engine reads the account's real commission rates from Binance when it connects and once a day: spot through
`GET /api/v3/account/commission` (standard rates and the BNB discount multiplier), USD-M futures through the
account's fee tier and commission endpoint. Fills record the fee actually charged and its asset. Backtests use
the rates last read from the account; before any account is connected they use Regular (VIP 0) rates without
the BNB discount, the pessimistic case: spot 0.100 % maker and taker (confirmed on Binance's fee page on
2026-11-08), USD-M 0.020 % maker and 0.050 % taker (from secondary sources, to confirm against the account).

**D-29. Internal tool, never a service; product decisions delegated** (2026-11-08, accepted)
Trading X is used internally by its operator and will never be offered as SaaS or to other users. This
strengthens D-5. Consequences, decided without further questions:
- single tenant: no tenant ids, plans, billing, onboarding, marketing pages or public sign-up;
- English-only interface (D-21), operator-centric: dense screens, keyboard shortcuts and expert features before
  guided flows;
- reports and exports are written for the operator, not for clients or regulators;
- tooling and infrastructure are chosen for one maintainer on one machine first (D-27).

Delegation: product and design questions whose answer follows from internal, single-operator use are decided by
the assistant and recorded here as D-numbers instead of being asked. Always still asked: anything that moves or
risks money (risk limits, sizing, live stages), exchange keys and secrets, paid services and LLM budgets, deleting
data, and the plan approval that `feature-work` requires before code.

**D-30. Public market data through our own Binance client** (2026-11-08, accepted)
Instruments, filters, candles and funding come from Binance's public endpoints through a small client of our
own, not ccxt: few simple endpoints, native filters that are more precise than a normalized copy, and Binance's
public-data hosts (`*.binance.vision`), which ccxt does not know. History comes from the checksummed archives,
the recent tail from REST, live candles from WebSocket (`docs/market-data.md`). Every endpoint is configurable.
D-2 still holds for trading; whether trading goes through ccxt is decided in Phase 4.

**D-31. Plain PostgreSQL for market data** (2026-11-08, accepted)
No TimescaleDB for now: the first universe makes about 430,000 candle rows a month, which plain tables with
unique keys handle. Revisited at 50 million rows. Settles the question left open in D-13 and D-27.

**D-32. The ledger moves to Phase 2** (2026-11-08, accepted)
The ledger is built where its first fills appear, with the simulated venue, instead of in Phase 1 without a user.

**D-33. First universe** (2026-11-08, accepted)
BTC, ETH, SOL, BNB and XRP against USDT, on spot and as USD-M perpetuals: the most liquid pairs, enough to test
every code path. Kept in `config/market.php`.

**D-34. Mutation testing with Pest** (2026-11-08, accepted)
Pest 5 ships mutation testing (`pest --mutate`), so Infection is not added. `composer mutate` runs it on the
domain modules.

**D-35. Phase 2 simulates spot, long only** (2026-11-08, accepted)
The engine, ledger and simulated venue are built and proven on spot first. Perpetuals need short positions,
isolated margin, funding at every funding time, liquidation and reduce-only exits; they come in Phase 2b, before
the DSL, so that Phase 3 can express strategies on both markets.

**D-36. The simulated venue works on 1-minute candles** (2026-11-08, accepted)
Strategies decide on closed candles of their own timeframe, built from 1-minute candles. The venue matches orders
against each 1-minute candle, so stops, targets and limits inside a long candle are replayed minute by minute
instead of guessed. An order decided at `t` is live from the minute that opens at `t`. When one minute reaches
both a stop and a target, the stop fills first.

**D-37. Cost model version 1** (2026-11-08, accepted)
Market and stop fills slip by `price × (0.0002 + 0.1 × participation)`, participation being the order quantity
over the minute's volume: half of an estimated spread plus a linear impact term. Limits and targets fill only when
the price trades through by one tick, at most 10 % of the minute's volume. These are conservative guesses kept in
`config/simulation.php` until paper and live fills calibrate them (Phase 4), which makes cost model version 2.

**D-38. Spot fees in the asset received** (2026-11-08, accepted)
Without BNB payment (O-6 still open) Binance charges spot fees in the asset the order receives: base on a buy,
quote on a sell. Taker for market and stop orders and for limits that fill on arrival, maker for limits that
rested. Selling rounds down to the lot step, so a remainder smaller than the step can stay as dust; the ledger
keeps it as unattributed holdings, valued in equity.

**D-39. Statistics in decimals** (2026-11-08, accepted)
Metrics use `BcMath` decimals at 18 digits: square roots from `BcMath`, logarithm and exponential from series in
`App\Support\Number\DecimalMath`. Float results depend on the C library and would make the golden hashes differ
between machines; decimals do not.

**D-40. Benchmark risk profile, backtests only** (2026-11-08, accepted)
Buy and hold and the 50/200 trend rule are comparisons. They run through the same engine with a profile that puts
all free quote into one position, needs no stop and has no loss halts. The engine refuses this profile outside
backtest mode. Strategies run with the account policy from `config/risk.php`, whose defaults equal
`docs/risk-policy.md` and are pinned by a test, so no code change raises a limit unnoticed (D-19).

**D-41. The simulated venue keeps its own books** (2026-11-08, accepted)
Like an exchange, the simulated venue holds balances and orders, locks funds for working orders and rejects what
the balance cannot cover. The engine reconciles its ledger against it at every event, with the same code it will
use against Binance (D-10). In a backtest a mismatch stops the run, because it can only be a bug.

**D-42. Backtest reports are canonical and hashed** (2026-11-08, accepted)
A report is canonical JSON (sorted keys, numbers as strings, no wall-clock time) identified by its SHA-256. It
holds the run definition, a fingerprint of the data read, metrics, trades, equity, decision counts and
comparisons. Reports are stored compressed under `storage/app/simulations` with a summary row in `backtest_runs`.
`Engine::VERSION` is raised by hand with every behaviour change; golden tests catch the changes that were not
meant.

**D-43. Perpetual accounting** (2026-11-08, accepted)
USD-M perpetuals settled in USDT, in one-way mode with isolated margin, in a USD-M wallet separate from the spot
wallet. At most one strategy trades a given perpetual in an account, so that the exchange's net position is that
strategy's position; the engine refuses a second one. Formulas in `docs/engine.md`, section 8.

**D-44. Mark price and funding in simulation** (2026-11-08, accepted)
The simulation uses the last price as the mark price: wicks reach liquidation more easily than on the real mark,
which is the pessimistic side. Funding is applied at its timestamp to the positions open at that moment, valued at
the close of the minute that ends then, and paid from or received into the USD-M wallet. How Binance books funding
on isolated positions is confirmed on testnet in Phase 4, with the ingestion of the mark price archives.

**D-45. Simulated liquidation** (2026-11-08, accepted)
The liquidation price uses an assumed maintenance margin rate, 1 % by default in `config/simulation.php`, more than
Binance's first tier for the majors, until the account's brackets are read (they need a key). A position is
liquidated when a minute opens beyond its liquidation price or its adverse extreme reaches it, and loses its whole
isolated margin.

**D-46. Protective orders on perpetuals** (2026-11-08, accepted)
A reduce-only STOP_MARKET and an optional reduce-only TAKE_PROFIT_MARKET; the engine cancels the other when one
fills, since Binance futures has no OCO. The stop is matched first within a minute. Exits, flattening and signal
closes are reduce-only market orders.

**D-47. USD-M fees** (2026-11-08, accepted)
Regular (VIP 0) rates of D-28 until an account is read: 0.020 % maker, 0.050 % taker, in USDT on the notional.

**D-48. Carry pairs are hedged, not directional** (2026-11-08, accepted by the operator with the Phase 2b plan)
A funding carry (long spot, short perpetual, same quantity) has no stop; the hedge protects it. Each leg's notional
is the template's maximum position (30 % in `docs/strategy/configurator.md`), never above the notional per instrument
of the risk policy, within free cash and gross exposure (both legs count). The perpetual leg's leverage is the lower
of the strategy's and the policy's, divided by one plus the margin buffer (50 % at least) and rounded down to a whole
number, at least 1: with 2x and 50 % that is 1x, which puts liquidation about twice the price away. When one leg is
gone the other is closed; a flatten closes the spot leg, then the hedge. Every other entry still needs a stop.

**D-49. Wallets at the start of a run** (2026-11-08, accepted)
A strategy that trades only spot starts with everything in the spot wallet; only perpetuals, everything in USD-M;
both, a split from `config/simulation.php` (50/50 by default). Transfers between wallets come with Phase 4.
Benchmarks run on the spot instrument of the same base, in their own accounts.

**D-50. Short baselines** (2026-11-08, accepted)
`ma-trend` and `donchian-breakout` take a `side` parameter, long or short; short only on perpetuals
(`docs/strategy/configurator.md`). `funding-carry` implements the configurator's carry template. The benchmark profile
on a perpetual sizes the position at 99 % of the free USD-M wallet at 1x, leaving room for slippage and fees.

**D-51. DSL grammar, parameters and numbers** (2026-11-08, accepted with the Phase 3 plan)
The expression grammar is the one in `docs/strategy/configurator.md`, section 2, plus `true` and `false` and named
parameters written `$name`. A document declares its parameters under `params:` with a default and, optionally, a
range and a step; walk-forward and the robustness check move only these. Documents are read by our own reader for
the YAML subset they use (block and one-line flow mappings and lists, quoted and plain scalars, folded and literal
text, comments), which keeps every scalar as its exact text with its line and column. `symfony/yaml`, named in the
approved plan, was dropped while building it: it turns `0.30` into a float. Errors point where the operator typed.
Every number is a `Decimal` with 18 digits (D-11, D-39).

**D-52. Function library, first version** (2026-11-08, accepted with the Phase 3 plan)
Every function of the configurator's table whose data the platform stores. `open_interest`, `oi_change`, `basis`
and `mark_price` are refused at compile time until their data is ingested. The agent functions (`regime()`, `risk_flag`,
`event`, `brief_confidence()`) were removed with D-64. Conventions: SMA-seeded EMA,
Wilder smoothing for RSI, ATR, ADX and the directional indicators, population standard deviation, Bollinger on
the population standard deviation. Each function has a reference test against values computed independently with
Python's `decimal` module (`tests/Fixtures/indicators/`).

**D-53. Timeframes and history in the DSL** (2026-11-08, accepted with the Phase 3 plan)
`x on 1d` subscribes the strategy to the higher timeframe, before its own, and reads the last closed candle of it,
including one that closes in the same event: never one still forming. `x[n]` reads n closed candles back. The
compiler computes the warmup from the expression graph, as the longest history any expression needs, and a
backtest window shorter than it is refused. Stops and targets are fixed at the decision, from the decision price
(the close), so the risk engine sizes and the venue protects with the same numbers.

**D-54. Strategy limits in the risk engine** (2026-11-08, accepted with the Phase 3 plan)
`sizing` and `limits` of a document become per-strategy limits checked by the risk engine after the account's:
risk per trade and maximum position resize down, maximum open positions, trades per day (UTC) and the cooldown
after a losing trade reject with `strategy_positions`, `trades_per_day` and `cooldown`. They can only be tighter
than the account's policy; the compiler refuses a looser one. The strategy context carries the strategy's last
closed trade (net result and time), which the time and loss rules need.

**D-55. Limit entries** (2026-11-08, accepted with the Phase 3 plan)
`order: limit(offset: -0.1%, expiry: 2 bars)` places a limit entry at the decision price moved by the offset,
working until it fills or expires; fills follow `docs/simulation.md`, section 3. Expiry and halts cancel a working
entry. Spot and perpetuals; the simulated USD-M book gains limit orders with the same rules.

**D-56. Benchmark documents and templates** (2026-11-08, accepted with the Phase 3 plan)
A document with `profile: benchmark` may write `stop_loss: none` and is sized by the benchmark profile (D-40,
D-50). It runs in backtests only; paper and live refuse it, as they refuse the profile. This lets the comparisons
themselves be written in the DSL. `template: funding_carry` builds the coded carry (D-48, D-50) from the document's
parameters.

**D-57. Walk-forward on continuous runs** (2026-11-08, accepted with the Phase 3 plan)
Every parameter set of the grid runs once over the whole history, all of them in one pass over the candles as
participants of one backtest. Each in-sample window picks a set on its slice of these runs; the following
out-of-sample window is judged on the slice of that set's run. A trade counts in the window where it closes, so a
slice may start with a position open; the report says how many did. The grid is split over child processes
(`--workers`), and the result does not depend on their number. Independent runs per window would cost hours for a
3-year search and were declined by the operator.

**D-58. Validation statistics** (2026-11-08, accepted with the Phase 3 plan)
- Monte Carlo: 2,000 resamples with a seeded `Xoshiro256StarStar`, the same on every machine: trades with
  replacement, daily returns in blocks of 5 days, and runs that skip 10 % of the trades.
- Deflated Sharpe ratio after Bailey and López de Prado, from the trials of the strategy family, their Sharpe
  ratios' variance, and the skewness and kurtosis of the returns.
- Probability of backtest overfitting by combinatorially symmetric cross-validation over 16 partitions.
- Regime labels before the journal: ADX(14) on daily candles at 25 or more is `trend`, under 20 `range`, between
  them `unknown`; 30-day realized volatility above its 80th percentile over the history read is `high_volatility`.
- The hold-out is the last 20 % of the history, used once per strategy version; the use is recorded and a second
  request refused.
- Every backtest of a family, by anyone, is recorded as a trial.

**D-59. No screens in Phase 3** (2026-11-08, accepted with the Phase 3 plan)
Phase 3 is reached through commands: compile, read-back, versions, backtests, walk-forward and gates. The
strategy editor is built with the first screens in Phase 4, where Livewire is installed, so that the shell, the
mode badge and the Halt button come first. This is the exception to "each phase ends with its screens".

**D-60. Duration-based gates for the carry template** (2026-11-08, accepted by the operator with the Phase 3 plan)
A carry trades rarely and holds long. Its trade-count gate is at least 12 months out of sample and 10 closed
cycles instead of 100 trades; every other gate of `docs/simulation.md`, section 7, applies unchanged.

**D-61. Strategy versions in the database** (2026-11-08, accepted with the runtime-strategies plan)
A strategy is a sequence of immutable versions in `strategy_versions`: id, version number, the document's text, its
SHA-256, the author (`operator` or `agent:<token name>`), the parent version and the time; `strategy_stages` holds
each version's stage history, starting at Draft. Only a document that compiles is saved. Saving a document identical
to the latest version returns that version; a different one becomes the next version. `strategy:import` and
`strategy:export` move versions between the database and `catalog/strategies/`, and backtests name a version as
`<id>@v<n>` (docs/strategy/configurator.md, section 6).

**D-62. Simulation runs in the background** (2026-11-08, accepted with the runtime-strategies plan)
Backtests, grids and walk-forwards asked for by an agent (and later the UI) are rows of `simulation_jobs` run by a
worker on the `simulation` queue: kind, parameters, status (queued, running, done, failed), summary, error and
author. The queue uses the `database` connection until Redis is installed, with a two-hour retry window, a one-hour
timeout and a single try, so that a long grid is never started twice. Commands, jobs and the UI share one
`BacktestService`, so a job's report is the command's report (D-42).

**D-63. MCP over HTTP with hashed tokens** (2026-11-08, accepted by the operator with the runtime-strategies plan)
External agents (Claude Desktop, Claude Code) reach the platform through `/mcp`, built with `laravel/mcp` and off
unless `MCP_ENABLED=true`. A token is created by `php artisan mcp:token <name>`, shown once and stored only as its
SHA-256, compared in constant time; `mcp:revoke` ends it. Every call is recorded in `mcp_calls` (tool, token name,
hash of the arguments, duration, outcome), never the token. Each token gets 60 calls a minute and at most two queued
simulation runs. The tools read market data, indicators, strategies and results, and write only draft versions and
simulation runs (D-7): none places an order, changes a limit or moves a stage. The endpoint is meant for the
operator's own network; beyond it, TLS in front is required (docs/security.md).

**D-64. No language model inside the platform** (2026-11-08, decided by the operator before phase 4)
The platform runs no LLM and holds no model API key. Strategy research is done by an external agent (Claude Code
or Claude Desktop on the operator's machine) through the MCP endpoint (D-63), which writes only draft versions and
simulation runs. The planned internal roles (Event Extractor, Market Analyst, Reviewer, Allocator), the source
pipeline (news, sentiment, macro, on-chain; `catalog/sources.yaml`, `docs/sources.md`) and the DSL functions fed
by them (`regime()`, `risk_flag()`, `event()`, `brief_confidence()`, and with them text values in the language)
are dropped: their value could only be measured after months of paper trading (D-17), they cost a paid API, and
nothing the operator trades needs them. The strategy families are trend, mean reversion and carry. Demotion
stays automatic in code (`docs/risk-policy.md`, section 7). Supersedes D-6, D-17 and D-18; the `Sources` module
leaves the architecture.

**D-65. Phase 5 is market data for scalping** (2026-11-08, decided by the operator with D-64)
Phase 5 stores what short-horizon perpetual strategies can use: aggregated trades from Binance's archives, open
interest and the mark price (which unlock `open_interest`, `oi_change`, `mark_price` and `basis`, D-52), and longer
history for the other perpetuals. It gets its own plan when it starts.

**D-66. How the gates are measured** (2026-11-08, decided with the Phase 3 close, D-29)
`strategy:gates` judges the set the walk-forward chose in its last window. Robustness runs that set's neighbours
over the in-sample history; the cost stress runs it from the first out-of-sample day to the end. The hold-out runs
once per version and is recorded: a later report reads the recorded outcome instead of refusing outright (D-58 said
a second request is refused), so a report can be rewritten without ever looking at the hold-out twice. The
drawdown gate reads the 95th percentile of the trade resamples, at the account's sizing. `backtest:grid` and
`strategy:gates` take `--workers`: the sets are split over child processes reading the parent's database, and the
outcomes are identical whatever their number (D-57).

**D-67. The live feed reads closed minutes from the database** (2026-11-09, accepted with the Phase 4 plan)
`engine:run` polls `candles` every 2 seconds for minutes closed by `market:stream` and handles a minute once every
followed instrument has it; a missing minute trips the stale-data breaker. Redis is not needed for it; the tick
stream of `architecture.md` waits until a strategy needs prices inside the minute.

**D-68. Paper accounts restart by replay** (2026-11-09, accepted with the Phase 4 plan)
A paper account's state is rebuilt on start by replaying its minutes and commands from its start through the same
loop, and every replayed decision must equal the journaled one; the first difference halts the account and alerts.
A restart or a power cut loses nothing, and every restart is also a replay test.

**D-69. Binance through our own client, not ccxt** (2026-11-09, accepted with the Phase 4 plan; amends D-2)
Signed REST (HMAC-SHA256) and the user data stream are our code, next to the public client of Phase 1, which
already knows Binance's symbols, filters and errors. The PHP edition of ccxt is large, synchronous and would sit
beside that code. Testnet and live differ only in their base URLs and keys.

**D-70. Polling instead of Reverb in Phase 4** (2026-11-09, accepted with the Phase 4 plan; amends D-3)
Livewire components poll the account snapshot every 2 seconds. One operator does not need a WebSocket server;
Reverb comes back with a D-number when something needs pushes.

**D-71. Paper follows Backtested automatically** (2026-11-09, accepted with the Phase 4 plan)
When a version becomes Backtested it is deployed to the paper account and its stage moves to Paper, both
journaled (`risk-policy.md`, section 6). The engine picks it up at its next start. Paper runs only versions that
passed the gates (the operator's answer to the Phase 4 questions).

**D-72. OBV and volume profile join the library** (2026-11-09, accepted with the Phase 4 plan)
`obv()` is a volume series. `vp_poc(n)`, `vp_vah(n)` and `vp_val(n)` are price series of the volume profile of the
last n candles: 24 bins between their lowest low and highest high, each candle's volume spread evenly over the bins
its range covers, the point of control the fullest bin's middle (the lower on a tie), and the value area grown from
it, one bin at a time towards the larger neighbour, until it holds 70 % of the volume. They are checked against the
Python oracle like every other indicator.

**D-73. Market breakers on every account engine, backtests included** (2026-11-09, with Phase 4 step 3)
Price sanity and volatility shock (`risk-policy.md`, section 3) are evaluated by the engine on every minute, so a
backtest pauses entries exactly as paper and live will (D-8). Benchmarks run without them (`market_breakers` is off in
the benchmark profile) and the closed-form carry test turns them off explicitly. Three golden reports changed
because a breaker blocked an entry (Donchian on ETH, the short Donchian and the carry); the benchmark reports did
not. Measured on the golden data (Binance, 2026-10-01 to 10), the volatility shock as written (a minute above 5
times the hour's mean range) trips 10 to 30 times a day per instrument, each time pausing entries for 15 minutes;
changing that threshold is a risk decision left to the operator. Wide spread needs the order book and waits for the
tick stream; stale data, venue errors and clock skew are tripped by `engine:run` and the venue. Commands are applied
right after the venue is reconciled, so a flatten sees the positions the venue reports.

**D-74. The testnet venue** (2026-11-09, with Phase 4 step 5)
`BinanceVenue` reads fills over REST when the engine asks for updates at each minute (each working order's status
and trades, and the perpetuals' funding), which is when a live engine sees fills anyway (`engine.md`, section 1); the
user data stream can come later without changing the engine. Spot protection is an exchange OCO (limit maker above,
stop-loss below); a perpetual's is a reduce-only stop and take-profit on isolated margin. Only the account's own
assets and instruments are reported. A testnet engine starts from the exchange as it is and refuses an account with
open orders or positions; it warms its indicators up on stored minutes without a request per minute; it halts on a
clock more than a second off Binance's or a bad key, and stops on any other venue error for its supervisor to restart.
The opt-in suite reads testnet keys from the shell (`TRADING_TESTNET_KEY`, `TRADING_TESTNET_SECRET`); live keys
are never read that way.

**D-75. The operator's settings in the app** (2026-11-09, accepted by the operator with the settings plan)
The Settings screen configures what was `.env` and command-line only: Telegram (bot token, chat id, a test message),
which optional notifications are sent, paper and testnet accounts, each testnet account's Binance key, and MCP
tokens. Risk limits and breaker thresholds are shown there read-only: changing them stays a code change with a test
(D-19). Telegram's token and chat id live in one `notification_settings` row with encrypted casts (D-20); a value set
in the app wins over `.env` (`TELEGRAM_BOT_TOKEN`, `TELEGRAM_CHAT_ID`), and the screen names the source of each value
(set in the app, from .env, not set), so the fallback is never silent. A stored secret is never shown back: the
screen says it is set and when, and for an exchange key the last 4 characters of the API key (not the secret). The
fields are emptied after saving. Re-authentication (password and a fresh code, 10 minutes) is asked before changing
the Telegram token or chat id, adding, replacing or deleting an exchange key, and creating an MCP token: each of
those gives access or redirects alerts. Revoking a token, turning off an optional notification and creating a paper
or testnet account do not. Halts, reconciliation, replay divergence, venue errors, clock skew, liquidation and stale
data always notify; commands notify by default and volatility shocks and price sanity do not (D-73), and those three
are the operator's choice. The test message is sent at once with a 10 second timeout and reports a status only. A
new key is used from the engine's next start. The accounts and tokens screens and the commands share one service
each (`App\Execution\Accounts\OpenAccount`, `App\Agents\Tokens`), so the rules are the same on both paths.

**D-76. What the engine publishes for reading** (2026-11-09, accepted by the operator with the report and live plan)
Besides the snapshot (D-70), `publish` writes every closed round trip to `account_trades` (keyed by account and the
trip's index in the ledger, so a replay rewrites the same rows) and one equity point per hour to `account_equity`.
Both are derived from the journal-replayed state, never read back by the engine, and safe to rebuild. The daily
report, the equity chart and the band of D-83 read them.

**D-77. The daily report** (2026-11-09, accepted with the same plan)
`report:daily` runs from the scheduler at 08:00 Europe/Bucharest (`notifications.daily_report_at`) and sends one
Telegram message per account: equity and its change over 24 hours, trades closed (count, winners, net, fees,
funding), halts and commands, breakers tripped by kind and instrument, reconciliation mismatches and stale data. It
is sent when nothing happened too, so its absence is itself a signal. The operator can turn it off in Settings.

**D-78. The health check** (2026-11-09, accepted with the same plan; the operator chose local alerts only)
`platform:health` prints each check; the scheduler runs it every minute with `--alert`. Checks: each market stream's
heartbeat under 2 minutes and the latest candle of every streamed instrument under 3 minutes; each monitored
account's snapshot under 3 minutes; no notification job waiting over 2 minutes and no new failed job; no queued
simulation or import waiting over 10 minutes with none running; free disk above 10 % and 5 GB. Alerts are sent at
once through `TelegramClient`, not the queue, since the queue may be what failed: one message when a check fails, a
reminder every hour while it lasts, and one when it recovers, with the state in `health_checks`. An account is
monitored from its first snapshot unless the operator turns it off. If the machine or the scheduler stops, nothing
alerts; the missing daily report is the sign.

**D-79. Data for scalping** (2026-11-09, accepted with the same plan; supersedes the open details of D-65)
Three stores, filled from Binance's archives and, live, from the stream (D-81). `trade_flow`: aggregated trades
reduced to one row per minute (volume bought and sold by trades of at least the instrument's large-trade notional,
their count, the largest trade's notional); raw trades are never stored (the operator's answer). `open_interest`:
the futures `metrics` archive, one row per 5 minutes, open interest in contracts and in quote; a row stamped T is
known from T + 5 minutes, so a backtest never reads it earlier than live could. `mark_price_minutes` and
`premium_index_minutes`: the mark price and the premium index of every minute (open, high, low, close), from
`markPriceKlines` and `premiumIndexKlines`.
DSL functions: `open_interest` (the latest known value, contracts), `oi_change(n)` (open interest now over n candles
ago, minus 1), `mark_price` (the mark's close), `basis` (the premium index close, Binance's own basis for funding);
from the candles already stored, `buy_volume`, `sell_volume`, `delta` (bought minus sold) and `cvd(n)` (delta summed
over n candles); from `trade_flow`, `large_buy_volume`, `large_sell_volume` and `large_delta`. Each has a reference
test against the Python oracle (D-52). The simulated venue values funding and checks liquidation at the stored mark
when the minute has one and at the last price otherwise (amends D-44); the golden data has no marks, so its reports
do not move. A backtest that uses one of these functions refuses a range its data does not cover.

**D-80. The universe lives in the app** (2026-11-09, accepted with the same plan; amends D-33)
`market_universe` replaces the list in `config/market.php`, which only seeds it. From Settings the operator adds or
removes any instrument `market:instruments` has stored, chooses streamed or history only, turns the scalping data on,
sets the large-trade notional, and starts a history import as a job on the `market` queue with its progress shown.
The stream reads the universe when it starts; the screen says when a restart is needed.

**D-81. Scalping data, live** (2026-11-09, accepted with the same plan)
`market:stream perp` adds `<symbol>@markPrice@1s` (the minute's last value is its mark close, highest and lowest its
high and low) and `<symbol>@aggTrade` (reduced to the minute in memory and written at its close) for instruments
with the scalping data on. A poller in the same process reads the premium index each minute and open interest each
5 minutes over REST, and repairs gaps over REST on reconnect, as for candles. `engine:run` waits for these rows on
the instruments where a strategy reads them, like it waits for candles (D-67).

**D-82. The live venue** (2026-11-09, accepted with the same plan; the operator chose to build it now, blocked)
A live account uses the same `BinanceVenue` and signed client as testnet, with `exchanges.binance.live.*` (D-69).
Before trading, the engine reads the key's restrictions (`/sapi/v1/account/apiRestrictions`) and refuses a key that
can withdraw, is not restricted to IP addresses, or cannot trade the account's markets. A live account is opened
only after re-authentication, and `engine:run` refuses it until the operator approves it, in the UI after
re-authentication or with `account:approve-live`, which asks for the password and a code. The approval is journaled
and takes effect 24 hours later (D-19); the operator can withdraw it until then.

**D-83. The ladder to live in code** (2026-11-09, accepted with the same plan; implements D-15 and D-16)
Each deployment's results are the account's trades of its strategy since it started. The band (`risk-policy.md`,
section 7) is computed every day by the scheduler against the version's latest passing gate report; a deployment
out of band is demoted one stage at once, its deployment ended, journaled and notified. Moving up is offered only
when the stage's conditions hold (30 days and 30 trades in paper for live micro; 30 days and 20 trades at the
previous step for each live step), asks for re-authentication and the operator's approval, and takes effect 24
hours later. A deployment's `size_multiplier` (0.25 with notional at most 5 % of equity for live micro, 0.5, then
1.0) only tightens the strategy's limits; at 1.0 nothing changes, so the golden reports do not move.

## Open

| Id | Question | Blocks |
|----|----------|--------|
| O-6 | Pay Binance fees in BNB (25 % off spot)? Needs a small BNB balance whose price moves. | Live cost model (D-28 reads it from the account either way) |
