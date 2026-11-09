# Architecture

The target design. Status of each part is in `docs/README.md`; the reasons are D-numbers in `docs/decisions.md`.

## 1. In one picture

```
                                   Operator (browser)   research agent (MCP, D-63)
                                               |  commands        |
                                               v                  v
  Binance (public) -> [market-data daemons] -> PostgreSQL <-- [web: Livewire, MCP server]
        ^   WebSocket + REST     |               ^   ^                |
        |                        | ticks (Redis) |   |                | jobs
        |                        v               |   |                v
        |                  [engine daemon] ------+   +------- [workers]
        |   orders, protective   |  one per account          backtests, grids, walk-forwards,
        +------------------------+  single writer             notifications
```

Everything is one Laravel application started as several processes. There is no second language and no shared
database between two codebases.

## 2. Processes

| Process | Command | Owns | Notes |
|---------|---------|------|-------|
| web | `php artisan serve` / PHP-FPM | HTTP, Livewire UI, MCP endpoint | Never waits on the engine or the network; reads state, writes commands |
| reverb | `php artisan reverb:start` | WebSockets to the browser | Throttled broadcasts (prices at most 4 per second per instrument) |
| market-data | `php artisan market:stream spot` and `market:stream perp` | One combined WebSocket stream per market | Blocking client, one process per market; stores closed candles and, for perpetuals with scalping data, the mark price, trade flow, premium index and open interest (D-81); repairs gaps on reconnect (`docs/market-data.md`) |
| engine | `php artisan engine:run {account}` | Orders, positions, ledger of one account, in the account's mode (paper, testnet, or live once approved, D-82) | The only writer of trading state for that account; reads closed minutes from the database (D-67), restarts by replay (D-68) |
| queue workers | `php artisan queue:work --queue=notifications,default` and the simulation worker below | Queued jobs | Horizon once Redis is installed |
| simulation worker | `php artisan queue:work simulation --queue=simulation,market --timeout=21600 --tries=1` | Background backtests, grids, walk-forwards (D-62) and history imports (D-80) | Until Redis and Horizon are installed |
| scheduler | `php artisan schedule:work` | Timed jobs | `platform:health --alert` every minute (D-78), `report:daily` (D-77), the band of every deployment once a day (D-83) |

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
| `Strategy` | DSL parser, type checker, indicators, compiler, read-back, strategy versions | Support, Market |
| `Risk` | Policy, limits, sizing, circuit breakers, kill switch, promotion gates | Support, Market, Strategy |
| `Portfolio` | Ledger (fills, fees, funding), positions, equity, P&L | Support, Market |
| `Execution` | Engine loop, `Venue` (simulated, Binance), order manager, reconciliation | Support, Market, Strategy, Risk, Portfolio |
| `Simulation` | Backtest runner, walk-forward, Monte Carlo, metrics, reports | Support, Market, Strategy, Risk, Portfolio, Execution (simulated venue only) |
| `Agents` | The MCP server for external research agents: tools, tokens, call log (D-63, D-64) | Support, Market, Strategy, Simulation, Portfolio (read), Journal |
| `Journal` | Engine decision records, command log | Support |
| `Notifications` | Telegram (Phase 4) and its settings (D-75), the daily report and the health check (D-77, D-78), email | Support, Market (instruments, for the health check), Journal, Execution (its `Notifier` contract, records and accounts) |
| `Web` | Livewire components, controllers, MCP server | everything above, read-only except through commands |

The rule that matters most: **`Agents` never depends on `Execution`**, and nothing in `Agents` can produce an
order (D-7). An agent influences trading only through draft strategy versions, which reach money only through the
gates and the operator.

## 4. The engine loop

One engine process per account. It reacts to events (a candle closes, a fill arrives, a timer fires, a command
arrives) and handles each one to the end before the next. Order inside one event:

1. **Stamp** the event with the clock (`WallClock` live and paper, `SimClock` in backtests).
2. **Reconcile** with the venue: open orders, fills, balances, positions. The venue wins every disagreement
   (D-10). A disagreement is journaled and alerted.
3. **Apply commands** queued since the last event (pause, resume, halt, flatten). Commands are journaled with the
   event they were applied at.
4. **Update the ledger**: fills, fees in the asset they were charged in, funding payments; positions; equity.
5. **Update risk state**: daily and weekly loss, drawdown from peak, data freshness, venue error rate. Trip
   circuit breakers if needed.
6. **Evaluate strategies** whose instrument and timeframe this event concerns. Output: intents (open, close,
   adjust), never orders.
7. **Risk engine** approves, resizes or rejects each intent against `docs/risk-policy.md` and the strategy's own
   limits. Rejections carry a reason code.
8. **Order manager** turns approved intents into orders with idempotent client order ids, rounded to the
   exchange filters, and attaches protective orders on the exchange.
9. **Journal** one decision record: input hashes (candles, features used), intents, risk verdicts, orders.
10. **Publish** state changes for the UI (Reverb) and notifications.

A backtest runs exactly this loop with `SimClock` and the simulated venue (D-8). The rules of each step, the
venue contract and the ledger are in `docs/engine.md`.

## 5. Agents

The platform runs no language model (D-64). An external agent, such as Claude Code or Claude Desktop, reaches it
through the MCP server built with `laravel/mcp` (D-63): tools in `app/Agents/Mcp/Tools` read candles, indicators,
funding, strategies and results, and write only draft strategy versions and simulation runs, which the
`simulation` workers execute. Every call is recorded in `mcp_calls` without the token. The roles and their limits
are in `docs/strategy/agents.md`.

## 6. Data

- **PostgreSQL**: everything. Market data in hypertables or partitioned tables (D-13).
- **Redis**: queues, cache, locks, the tick stream from market-data to the engines.
- **Disk**: backtest artifacts (equity curves, trade lists) as compressed JSON under `storage/app/simulations`,
  referenced from the database.

Point-in-time rule: every market record stores the time the exchange stamped it and the time we received it. Backtests read by received time, so they never see data before it existed.

Migrations are additive. Old rows stay readable: a column change ships with a migration of existing data in the
same release, and the journal format carries a version.

## 7. Modes

| Mode | Clock | Venue | Money | Who can start it |
|------|-------|-------|-------|------------------|
| Backtest | `SimClock` over history | Simulated, history | none | Operator, research agent over MCP |
| Paper | `WallClock` | Simulated on the live feed | none | Operator, automatic after backtest gates |
| Testnet | `WallClock` | Binance testnet | test funds | Operator, for exchange mechanics only |
| Live | `WallClock` | Binance | real | Operator only, after gates (D-16) |

The current mode is always visible in the UI and in every notification. There is no fallback from one mode to
another: a live engine that cannot reach the exchange halts and alerts (lesson R4 of the assessment).

## 8. Repository layout (target)

```
app/            Laravel application, modules as in section 3
catalog/        strategy documents and exports (*.strategy.yaml)
config/         Laravel config, including risk.php, mcp.php, exchanges.php
database/       migrations, factories, seeders (seeders never create a default password)
docs/           this documentation
resources/      views, Livewire templates, css tokens
tests/          Pest: Unit, Feature, Arch, Golden (backtest snapshots)
```
