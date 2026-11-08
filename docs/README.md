# Documentation

An automated crypto trading platform for one operator. LLM agents research markets and write strategies;
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
| `strategy/agents.md` | Agent roles, their authority, and how the system stays profitable |
| `strategy/configurator.md` | The strategy DSL and the natural-language configurator |
| `risk-policy.md` | Hard limits, sizing, circuit breakers, the promotion ladder |
| `simulation.md` | Backtests, walk-forward, Monte Carlo, paper trading, replay, promotion gates |
| `sources.md` | Where agents get information and how a source is added |
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
| 0. Hygiene | Delete the old code and the committed database (D-25), new Laravel skeleton, CI (Pint, PHPStan, Pest, arch tests), docs | CI green on an empty skeleton; this documentation merged | Docs written, rest planned |
| 1. Foundations | Value objects, clock, ledger; Binance instruments and filters; candle backfill (REST) and live stream (WebSocket); data quality checks | A month of 1m candles for 5 pairs, gap-free, matches the exchange | Planned |
| 2. Simulation | Simulated venue, engine loop, fill and cost model, metrics, reports; three baseline strategies coded by hand | Baselines reproduce known results; golden tests in place | Planned |
| 3. Strategy DSL | Parser, type checker, indicator library, compiler, read-back, walk-forward, Monte Carlo, promotion gates | The baselines rewritten in the DSL give identical reports | Planned |
| 4. Paper and execution | Live feed into the simulated venue, Binance testnet venue, risk engine, order manager, reconciliation, kill switch, notifications | 30 days of paper trading without a reconciliation mismatch | Planned |
| 5. Sources and agents | Source catalog and connectors, event extraction, MCP tools, Analyst, Strategy Author (natural-language configurator), Reviewer, decision journal, cost tracking | A strategy described in plain words reaches paper through the gates | Planned |
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
