# Documentation

An automated crypto trading platform for one operator. A research agent, working over MCP, writes strategies;
deterministic code tests, sizes, limits and executes them. Everything runs in one Laravel application.

The platform is being rebuilt. `assessment.md` explains why; the old code was deleted in Phase 0 and is readable
in git at commit `e0ec48b` (D-25).

## Reading order

| Document | What it holds |
|----------|---------------|
| `assessment.md` | What the old platform did, what failed, what survives |
| `decisions.md` | Every design decision (D-numbers) and the open questions |
| `architecture.md` | Processes, modules, the engine loop, data, modes |
| `conventions.md` | How code, tests, commits and docs are written |
| `strategy/agents.md` | Who researches strategies, their authority, and how the system stays profitable |
| `strategy/configurator.md` | The strategy DSL and the natural-language configurator |
| `risk-policy.md` | Hard limits, sizing, circuit breakers, the promotion ladder |
| `market-data.md` | Exchange data: sources, storage, history, live stream, quality checks |
| `simulation.md` | Backtests, walk-forward, Monte Carlo, paper trading, replay, promotion gates |
| `engine.md` | The engine loop, the venue contract, orders, risk checks, the ledger and reconciliation |
| `security.md` | Secrets, keys, access, threat model |
| `runbook.md` | Running the platform, the kill switch, incidents |
| `brand.md` | Visual identity: shared rules and three candidate identities |
| `universe.md` | Multi-market research: the universe, its data and cross-sectional strategies (Phase 7) |
| `glossary.md` | Trading terms in plain words, used by the configurator |

Skills for agents working on the code: `.claude/skills/feature-work`, `.claude/skills/bug-fix`,
`.claude/skills/strategy-promotion`.

## Phases

Each phase ends with its tests green, its docs updated and its screens built in the chosen identity.

| Phase | Scope | Done when | Status |
|-------|-------|-----------|--------|
| 0. Hygiene | Delete the old code and the committed database (D-25), new Laravel skeleton, CI (Pint, PHPStan, Pest, arch tests), docs | CI green on an empty skeleton; this documentation merged | Done: CI green on 2026-11-08 (Pint, PHPStan max, Pest on PHP 8.4 and 8.5, Vite build, audits, secret scan) |
| 1. Foundations | Value objects and clock; Binance instruments and filters; candle and funding history from archives and REST, live stream (WebSocket); data quality checks (`market-data.md`) | A month of 1m candles for 5 pairs, gap-free, matches the exchange | Done 2026-11-08: October 2026 for the five spot pairs has no gap and 0 mismatches in 2,500 candles compared with Binance REST; the live stream and its gap repair work on real data. Perpetuals: 0 mismatches against the daily archives, but Binance did not publish 2026-10-28 and the REST fill needs the operator's machine (see runbook, section 8) |
| 2. Simulation | Spot, long only (D-35): ledger (D-32), simulated venue on 1m candles, engine loop, fill and cost model, risk sizing and loss limits, metrics, reports; three baseline strategies coded by hand (`engine.md`) | Baselines reproduce known results; golden tests in place | Done 2026-11-08: on Binance's real archives buy and hold equals its closed form and the 12/48 hourly trend rule gives the same trades and equity as an independent reference; three golden reports pinned, identical on PHP 8.4 and 8.5 in CI. On BTC/USDT from 2025-06 to 2026-10 the 50/200 daily rule's entries and exits match a PostgreSQL window-function computation, to the fill price. One year of 1-minute candles runs in 49 s |
| 2b. Perpetuals | Short positions, isolated margin, funding, liquidation, reduce-only exits in the simulation (D-35) | A funding carry and a short baseline reproduce known results | Done 2026-11-08 (`engine.md`, section 8; D-43 to D-50): on Binance's real USD-M archives the funding carry equals its closed form (both legs, funding, fees) and the short 12/48 hourly trend rule gives the same trades, funding and equity as an independent reference; three perpetual golden reports pinned. On BTC from 2025-06 to 2026-10 the funding booked by the carry and both short baselines equals a PostgreSQL computation from `funding_rates` and the candles, to the last digit, over 64 positions. The perpetual filters there are the hand-written fixture's, and the reports say so |
| 3. Strategy DSL | Parser, type checker, indicator library, compiler, read-back, walk-forward, Monte Carlo, promotion gates | The baselines rewritten in the DSL give identical reports | Done 2026-11-09 (`strategy/configurator.md`, `simulation.md` sections 5 and 7; D-51 to D-60, D-66). The six baselines written in the DSL give reports identical to the hand-coded ones on the golden data (a test) and on real BTC candles from 2025-06 to 2026-10 (each run 170 to 380 s). Strategies are defined at runtime (D-61 to D-63): versions in the database, background runs, and an MCP endpoint for a research agent. The gates on Donchian breakout over BTC spot 2023-11-09 to 2026-11-01 (28 sets, 12m/3m walk-forward, hold-out from 2026-03-28) ran in 1284 s with 4 workers and fail 7 of 9: 53 trades, profit factor 1.035, deflated Sharpe 0.613, hold-out expectancy negative; robustness and cost stress pass. No strategy is Backtested yet. The mutation pass on the phase 3 modules was not run |
| 4. Paper and execution | First screen: the chart, with candles and indicators picked from the DSL's function library and laid over it as layers, each with its own parameters and as many as wanted (EMA 9 and EMA 21 together), drawn with the backtest's formulas (moving averages, VWAP, Bollinger, Keltner, Donchian and volume profile on price; MACD, RSI, Stochastic, ADX, ATR and OBV in panels), OBV and volume profile (POC, value area) joining the library then. Live feed into the simulated venue, Binance testnet venue, risk engine, order manager, reconciliation, kill switch, notifications | 30 days of paper trading without a reconciliation mismatch | Built 2026-11-09, criterion pending (D-67 to D-74; `engine.md` section 9; runbook sections 2, 11 to 13). Paper engine on the live feed with replay and journal verification on restart, market breakers, operator commands (halt, resume, flatten, pause) from the command line and the screens, Binance testnet venue with its own signed client, Telegram alerts, login with TOTP and re-authentication, the Chart, Strategies and Account screens, the daily Telegram report and the health check (D-76 to D-78). Checked locally on stored data; the testnet suite and the 30 days run on the operator's machine |
| 5. Data for scalping | Aggregated trades from Binance's archives, open interest and mark price for perpetuals (unlocking `open_interest`, `oi_change`, `mark_price`, `basis`), longer history for the other perpetuals (D-65). No language model runs inside the platform: research is done by an agent over MCP (D-64) | A strategy using open interest or the mark price runs in backtest and paper on stored data | Done 2026-11-09 (D-79 to D-81, D-84; `market-data.md` section 8): October 2026 of BTC perpetual scalping data imported from Binance's archives (18 s a day; open interest complete, the trade flow, mark and premium of 2026-10-28 and the mark and premium of 2026-10-29 not published). `catalog/strategies/oi-momentum-long` (open interest change, volume delta, basis, mark price) backtests 2026-10-02 to 10-27 in 14 s (78 trades, a loss: it is an example) and runs in paper on the same data to 2026-10-28, where the missing marks stop it as designed, then replays identically after a restart. Left on the operator's machine: the twelve-month import of BTC and ETH (about 3.5 hours) and the live collector on fapi.binance.com, which answers 451 here |
| 6. Live | Live venue, capital ladder from micro size | First strategy live at micro size with the operator's approval | Built 2026-11-09 and blocked until a strategy qualifies (D-82, D-83; `engine.md` section 10; runbook section 14). The live venue on api.binance.com and fapi.binance.com through the testnet code path; a live account opens only after re-authentication and trades only 24 hours after the operator's approval, with a key that cannot withdraw, is limited to IP addresses and can trade its markets; each deployment's trades checked daily against its gate report (the band), out of band demoted at once; promotion to live micro (a quarter of the size, 5 % notional cap), half and full size from the Strategies screen after re-authentication, effective 24 hours later. Checked with recorded responses; no request reaches api.binance.com in tests. Criterion pending: no strategy has passed its 30 days of paper |
| 7. Multi-market research | Every USD-M perpetual with its lifetime, a point-in-time universe of the top 50 over 2 years, partitioned storage and daily stats, order book depth, positioning ratios and liquidations, cross-sectional strategies (`rank`, `top`, `bottom`), MCP tools for the research agent (`universe.md`, D-89 to D-94) | A cross-sectional strategy backtests over the universe of 50 since 2024-11, its report includes later delisted coins, and the agent can screen the market and run it over MCP | In progress |

## How to run

The platform runs locally (D-27). Requirements: PHP 8.4 or newer with the `bcmath`, `intl`, `pdo_pgsql` and
`redis` extensions, Composer, Node 22, Docker.

```sh
cp .env.example .env
docker compose up -d        # PostgreSQL 17 (with the trading_test database) and Redis 7, on 127.0.0.1 only
composer setup              # dependencies, tools/pint, app key, migrations, front-end build
```

Checks, as CI runs them:

```sh
composer format -- --test   # Pint, from tools/pint
composer analyse            # PHPStan level max
composer test               # Pest: feature, architecture and brand suites, no network
npm run build               # Vite and Tailwind, IBM Plex fonts bundled
npm run e2e                 # Playwright: every screen at six widths on the scratch database trading_e2e
```

On Windows without Docker (D-85), `.\start-local.ps1` does the setup on its first run and starts the platform on
every run: a portable PostgreSQL 17 in `%LOCALAPPDATA%\TradingPlatform` (no service, no administrator rights; the
databases `trading`, `trading_test` and `trading_e2e`), `.env` with the cache and the default queue on the
database instead of Redis, the dependencies, the app key, the front-end build and pending migrations. Then it runs
the web process on http://127.0.0.1:8000, the simulation worker and the notifications worker in one window, and
restarts one that dies. Ctrl+C stops them and PostgreSQL; `.\start-local.ps1 -Stop` does it after the window was
closed. `-Stream` adds the market data streams and the scheduler, `-Scheduler` the scheduler alone, `-Engine paper`
an engine. The processes it starts run with `memory_limit=2G`, through an ini file in
`%LOCALAPPDATA%\TradingPlatform\php`, since PHP's usual 128 MB is too little for backtests over months of 1-minute
candles; for a command typed by hand use `php -d memory_limit=2G artisan ...`. Create the operator once with
`php artisan user:create <email>`.

`php artisan serve` starts the web process; `/up` is the health check. The screens are Chart, Strategies, Account
and Settings (`runbook.md` section 13).

Market data (`market-data.md`, commands in `runbook.md` section 8):

```sh
php artisan market:instruments all
php artisan market:backfill --universe --from=2026-10 --funding
php artisan market:stream spot            # and market:stream perp, one process each
php artisan market:check --universe --from=2026-10
php artisan market:verify --universe --from=2026-10 --to=2026-10
```
