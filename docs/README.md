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
| 3. Strategy DSL | Parser, type checker, indicator library, compiler, read-back, walk-forward, Monte Carlo, promotion gates | The baselines rewritten in the DSL give identical reports | In progress (`strategy/configurator.md`, `simulation.md` sections 5 and 7; D-51 to D-60). Strategies defined at runtime (D-61 to D-63): versions in the database, background runs, and an MCP endpoint through which an agent reads the chart, writes and saves drafts and runs grids and walk-forwards; the UI for it comes with phase 4 |
| 4. Paper and execution | First screen: the chart, with candles and indicators picked from the DSL's function library and laid over it as layers, each with its own parameters and as many as wanted (EMA 9 and EMA 21 together), drawn with the backtest's formulas (moving averages, VWAP, Bollinger, Keltner, Donchian and volume profile on price; MACD, RSI, Stochastic, ADX, ATR and OBV in panels), OBV and volume profile (POC, value area) joining the library then. Live feed into the simulated venue, Binance testnet venue, risk engine, order manager, reconciliation, kill switch, notifications | 30 days of paper trading without a reconciliation mismatch | Planned |
| 5. Data for scalping | Aggregated trades from Binance's archives, open interest and mark price for perpetuals (unlocking `open_interest`, `oi_change`, `mark_price`, `basis`), longer history for the other perpetuals (D-65). No language model runs inside the platform: research is done by an agent over MCP (D-64) | A strategy using open interest or the mark price runs in backtest and paper on stored data | Planned |
| 6. Live | Live venue, capital ladder from micro size | First strategy live at micro size with the operator's approval | Planned |

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
```

`php artisan serve` starts the web process; `/up` is the health check. There are no screens yet.

Market data (`market-data.md`, commands in `runbook.md` section 8):

```sh
php artisan market:instruments all
php artisan market:backfill --universe --from=2026-10 --funding
php artisan market:stream spot            # and market:stream perp, one process each
php artisan market:check --universe --from=2026-10
php artisan market:verify --universe --from=2026-10 --to=2026-10
```
