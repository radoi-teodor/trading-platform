# Architecture

The target design. Status of each part is in `docs/README.md`; the reasons are D-numbers in `docs/decisions.md`.

## 1. In one picture

```
                 catalog/sources.yaml          Operator (browser, Livewire)
                          |                            |  commands
                          v                            v
  Binance (ccxt) --> [market-data daemon] --> PostgreSQL <-- [web: Livewire, MCP server]
        ^   WebSocket + REST     |               ^   ^                |
        |                        | ticks (Redis) |   |                | jobs
        |                        v               |   |                v
        |                  [engine daemon] ------+   +------- [Horizon workers]
        |   orders, protective   |  one per account          sources, event extraction,
        +------------------------+  single writer             agents (Claude API), backtests,
                                                              notifications
```

Everything is one Laravel application started as several processes. There is no second language and no shared
database between two codebases.

## 2. Processes

| Process | Command | Owns | Notes |
|---------|---------|------|-------|
| web | `php artisan serve` / PHP-FPM | HTTP, Livewire UI, MCP endpoint | Never waits on the engine or the network; reads state, writes commands |
| reverb | `php artisan reverb:start` | WebSockets to the browser | Throttled broadcasts (prices at most 4 per second per instrument) |
| market-data | `php artisan market:stream` | Exchange WebSocket subscriptions, candle building, backfill | ReactPHP loop; writes candles, trades, funding, open interest; publishes ticks to a Redis stream |
| engine | `php artisan engine:run {account} --mode=paper\|live` | Orders, positions, ledger of one account | The only writer of trading state for that account |
| horizon | `php artisan horizon` | Queued jobs | Queues: `sources`, `agents`, `simulation` (CPU-heavy), `notifications`, `default` |
| scheduler | `php artisan schedule:work` | Timed jobs | Source polling, agent runs, reconciliation sweep, daily reports |

Long-running PHP processes are supervised (Supervisor or systemd), restart on failure, and restart themselves
after a memory or uptime limit. Each one reconnects to the database on failure instead of reusing a dead
connection.

## 3. Modules

A modular monolith under `app/`. Each module has `Contracts/`, `Data/` (immutable DTOs), `Actions/`, `Models/`,
`Events/` and `Jobs/` as needed. Dependencies point one way; architecture tests (Pest `arch()`) enforce them.

| Module | Responsibility | May depend on |
|--------|----------------|---------------|
| `Support` | Value objects (`Price`, `Quantity`, `Money`, `Percent`, `Timeframe`, `Symbol`), `Clock`, seeded random | nothing |
| `Market` | Instruments and their exchange filters, candles, trades, funding, open interest, data quality | Support |
| `Sources` | Source catalog, connectors, raw items, structured events | Support, Market |
| `Strategy` | DSL parser, type checker, indicators, compiler, read-back, strategy versions | Support, Market |
| `Risk` | Policy, limits, sizing, circuit breakers, kill switch, promotion gates | Support, Market, Strategy |
| `Portfolio` | Ledger (fills, fees, funding), positions, equity, P&L | Support, Market |
| `Execution` | Engine loop, `Venue` (simulated, Binance), order manager, reconciliation | Support, Market, Strategy, Risk, Portfolio |
| `Simulation` | Backtest runner, walk-forward, Monte Carlo, metrics, reports | Support, Market, Strategy, Risk, Portfolio, Execution (simulated venue only) |
| `Agents` | Agent roles, tools, prompts, structured outputs, cost accounting | Support, Market, Sources, Strategy, Simulation, Portfolio (read), Journal |
| `Journal` | Decision records, agent records, command log | Support |
| `Notifications` | Telegram, email | Support, Journal |
| `Web` | Livewire components, controllers, MCP server | everything above, read-only except through commands |

The rule that matters most: **`Agents` never depends on `Execution`**, and nothing in `Agents` can produce an
order (D-7). Agents influence trading only through artifacts the deterministic code reads: briefs, events,
draft strategy versions, and pause requests.

## 4. The engine loop

One engine process per account. It reacts to events (a candle closes, a fill arrives, a timer fires, a command
arrives) and handles each one to the end before the next. Order inside one event:

1. **Stamp** the event with the clock (`WallClock` live and paper, `SimClock` in backtests).
2. **Apply commands** queued since the last event (pause, resume, halt, flatten, promote). Commands are journaled
   with the event they were applied at.
3. **Reconcile** with the venue: open orders, fills, balances, positions. The venue wins every disagreement
   (D-10). A disagreement is journaled and alerted.
4. **Update the ledger**: fills, fees in the asset they were charged in, funding payments; positions; equity.
5. **Update risk state**: daily and weekly loss, drawdown from peak, data freshness, venue error rate. Trip
   circuit breakers if needed.
6. **Evaluate strategies** whose instrument and timeframe this event concerns. Output: intents (open, close,
   adjust), never orders.
7. **Risk engine** approves, resizes or rejects each intent against `docs/risk-policy.md` and the strategy's own
   limits. Rejections carry a reason code.
8. **Order manager** turns approved intents into orders with idempotent client order ids, rounded to the
   exchange filters, and attaches protective orders on the exchange.
9. **Journal** one decision record: input hashes (candles, features, briefs used), intents, risk verdicts,
   orders.
10. **Publish** state changes for the UI (Reverb) and notifications.

A backtest runs exactly this loop with `SimClock` and the simulated venue (D-8).

## 5. Agents

Agents run as queued jobs, off the engine's path. Each role (`docs/strategy/agents.md`) has a system prompt in
`resources/prompts/`, a set of tools, a structured output schema and a model in `config/agents.php`.

Tools are PHP classes in `app/Agents/Tools`. The same classes serve two clients:
- the platform's own agents, as Claude API tools;
- external agents (Claude Code, Claude Desktop) through the MCP server built with `laravel/mcp`, read-only.

Every tool result names the sources it came from. Every agent run is journaled: inputs, tool calls, output,
tokens, cost, duration.

## 6. Data

- **PostgreSQL**: everything. Market data in hypertables or partitioned tables (D-13).
- **Redis**: queues, cache, locks, the tick stream from market-data to the engines.
- **Disk**: backtest artifacts (equity curves, trade lists) as compressed JSON under `storage/app/simulations`,
  referenced from the database.

Point-in-time rule: every market record and every source item stores the time the exchange or publisher stamped
it and the time we received it. Backtests read by received time, so they never see data before it existed.

Migrations are additive. Old rows stay readable: a column change ships with a migration of existing data in the
same release, and the journal format carries a version.

## 7. Modes

| Mode | Clock | Venue | Money | Who can start it |
|------|-------|-------|-------|------------------|
| Backtest | `SimClock` over history | Simulated, history | none | Operator, agents (Strategy Author, Reviewer) |
| Paper | `WallClock` | Simulated on the live feed | none | Operator, automatic after backtest gates |
| Testnet | `WallClock` | Binance testnet | test funds | Operator, for exchange mechanics only |
| Live | `WallClock` | Binance | real | Operator only, after gates (D-16) |

The current mode is always visible in the UI and in every notification. There is no fallback from one mode to
another: a live engine that cannot reach the exchange halts and alerts (lesson R4 of the assessment).

## 8. Repository layout (target)

```
app/            Laravel application, modules as in section 3
catalog/        sources.yaml, strategy exports (*.strategy.yaml)
config/         Laravel config, including risk.php, agents.php, exchanges.php
database/       migrations, factories, seeders (seeders never create a default password)
docs/           this documentation
resources/      views, Livewire templates, css tokens, prompts/
tests/          Pest: Unit, Feature, Arch, Golden (backtest snapshots)
legacy/         the old Python agents and Laravel app, read-only, deleted after parity (D-1)
```
