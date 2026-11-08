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

**D-6. LLM backend: the Claude API** (2026-11-08, accepted)
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
qualifiers (`on 4h`) and static types. Natural language goes through the Strategy Author agent, which must
produce valid DSL; a deterministic read-back renders the compiled strategy into plain sentences so the operator
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

**D-17. LLM outputs are point-in-time features** (2026-11-08, accepted)
A model has seen market history in training, so a backtest where it "decides" on past data is contaminated.
Every agent output is journaled with the time it was produced. Backtests may use only outputs that were really
produced at that time; before the journal starts, LLM-derived features take their neutral default. LLM agents
are evaluated forward only (paper and live).

**D-18. Sources: catalog in git, state in the database** (2026-11-08, accepted)
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

## Open

| Id | Question | Blocks |
|----|----------|--------|
| O-6 | Pay Binance fees in BNB (25 % off spot)? Needs a small BNB balance whose price moves. | Live cost model (D-28 reads it from the account either way) |
