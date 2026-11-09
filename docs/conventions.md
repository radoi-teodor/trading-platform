# Conventions

Binding for every change, by a person or an agent. Where a rule exists because of a past failure, the failure is
named so nobody "simplifies" the rule away.

## 1. Language and tooling

- PHP 8.4 or newer, current Laravel. `declare(strict_types=1);` in every PHP file.
- Formatting: Laravel Pint, project preset (`pint.json`). Formatting is not discussed in review; Pint decides.
  Pint is installed apart from the application, in `tools/pint` (`composer tools:install`), because its package
  declares its own `App\` namespace, which would otherwise mix with ours in the autoloader.
- Static analysis: PHPStan through Larastan at level `max`, with no baseline. `config/` is not analysed because
  `env()` is untyped by design; code reads configuration through typed accessors (`Config::string()`,
  `Config::integer()`), which are checked where they are used.
- Tests: Pest, with the `arch()` plugin.
- Commands (defined in `composer.json`):

```sh
composer format          # pint
composer analyse         # phpstan
composer test            # pest, no network, fakes for the exchange
composer test:network    # TRADING_NETWORK=1, real public market data endpoints, opt-in, no keys
composer test:testnet    # TRADING_TESTNET=1, Binance testnet, opt-in
composer mutate          # Pest mutation testing on the domain modules (D-34); long, ask first
```

**Code graph for assistants.** [graphify](https://github.com/Graphify-Labs/graphify) keeps a knowledge graph of the
code in `graphify-out/` so that Claude Code queries it (`graphify query`, `path`, `explain`) instead of reading
whole files, which saves tokens. It is installed per repository: the skill in `.claude/skills/graphify/`, the hooks
in `.claude/settings.json` (advisory, they never block a read) and its rules in `CLAUDE.md`. The graph is built
from code only, locally, with tree-sitter and no LLM (`GRAPHIFY_BACKEND=none`), so nothing leaves the machine.
Install the CLI once with `uv tool install graphifyy` (Python 3.10 or newer) and refresh the graph after code
changes with `graphify update .`; `graphify hook install` adds local git hooks that do it on every commit. The
committed `graph.json` changes whole on every rebuild; that diff is expected.

## 2. Code style

- Classes are `final` unless built for extension. Value objects and DTOs are `readonly`.
- Every finite set is a backed enum (`OrderSide`, `Timeframe`, `StrategyStage`, `RiskVerdict`). No magic strings
  for states.
- No associative arrays across module boundaries: use DTOs. The legacy daily loss limit never fired because a
  dictionary key was spelled `max_daily_loss` in one place and `max_daily_loss_pct` in another.
- Constructor injection only. No facades and no `app()` inside `Strategy`, `Risk`, `Portfolio`, `Execution` and
  `Simulation`; they must run in a backtest without the framework booted.
- Small methods, one level of abstraction each. A method that needs a comment to explain what it does gets a
  better name instead; comments explain why.
- Names in full words: `stopLossDistance`, not `slDist`.

## 3. Numbers, money and time

- Prices, quantities, money, fees: `BcMath\Number` inside `Price`, `Quantity`, `Money` (D-11). `float` is
  forbidden in the domain modules, enforced by an architecture test. Indicators may compute in float internally
  but return `Number` at their boundary, rounded to the instrument's precision.
- Every amount knows its asset. Adding USDT to BTC is a type error, not a bug found in production (legacy
  lesson B2: fees charged in USDT were converted as if they were in BTC).
- Database: `numeric(36,18)` for amounts, `timestamptz` for times. JSON carries amounts as strings.
- Time: UTC, `CarbonImmutable`, read only from the injected `Clock` (D-12). `now()`, `time()`, `Carbon::now()`
  and `new DateTime()` are forbidden in domain modules.
- Randomness: an injected `Random\Randomizer` with a seeded engine. Every backtest stores its seed.
- Percentages are `Percent` objects holding the fraction (0.005 for 0.5 %). Displayed with a sign and a zero
  form: `+0.50%`, `-0.50%`, `0.00%` (`docs/brand.md`, A2).

## 4. Errors and logging

- Catch the specific exception you can handle, where you can handle it. A `catch (\Throwable)` with an empty or
  logging-only body is forbidden outside the outermost loop of a daemon (legacy lesson E1: a swallowed
  `ImportError` hid the indicators for the whole life of the project).
- No silent fallback. When a dependency fails, fail the operation, journal it and alert (legacy lesson R4).
- Structured logs (JSON, Monolog). Every line about trading carries `account`, `strategy_version`, `decision_id`
  or `order_id` where they exist.
- Never log secrets, full prompts with secrets, or raw exchange responses that contain keys or signatures.

## 5. Database

- PostgreSQL only. Migrations are additive; a released migration is never edited. Destructive changes happen in
  two releases (stop using, then drop).
- Foreign keys and unique constraints are declared, not assumed. Every `updateOrCreate` key has a unique index.
- Trading state for an account is written only by that account's engine process (single writer). The web
  process writes commands, not state.

## 6. Tests

- Tests are written first for domain logic and for every bug fix (the failing test proves the bug).
- No network in the default suite: `Http::preventStrayRequests()`, a fake venue.
- Golden tests: a fixed dataset, strategy version and seed produce a backtest report whose hash is committed. A
  change in the hash must be explained in the commit message. They live in `tests/Golden`, read Binance's daily
  archives committed under `tests/Fixtures/binance/golden`, and keep each report as indented JSON next to its hash,
  so a change shows as a readable diff. To accept a change on purpose: `UPDATE_GOLDEN=1 vendor/bin/pest tests/Golden`,
  review the diff, update the hashes.
- Architecture tests guard the module rules in `docs/architecture.md` section 3, the float ban, the clock ban,
  and "no facade in the domain".
- Tests and UI checks never touch a live account or the operator's production database.
- End-to-end: `npm run e2e` (Playwright, `tests/e2e`) runs the real app on the scratch database `trading_e2e`
  (seeded by `Tests\Support\E2eSeeder`, which refuses any other database) with a fake Telegram, and checks every
  screen at 360, 390, 768, 1024, 1280 and 1920 wide: no horizontal scroll, every control on screen, Halt, the mode
  and the navigation visible, no console error. A new screen or flow gets its spec there. Screenshots land in
  `storage/e2e/screens`. Needs `createdb trading_e2e` once.

## 7. UI

- Livewire components in `app/Livewire`, Blade components in `resources/views/components`.
- Colors, type, spacing, radii only through tokens (`docs/brand.md`). A hex value in a Blade file, a component
  class or a script is a review failure.
- The UI never waits on the engine or the exchange: it reads the database or cache and listens to
  Reverb. Long work is a queued job with visible progress.
- Every number from the market is shown in tabular monospace with the instrument's precision.
- The current mode (backtest, paper, testnet, live) is visible on every screen, and Halt stays on screen at any
  scroll position (the header is sticky).

## 8. Agents

- The platform runs no language model (D-64). Agents reach it only through the MCP endpoint (D-63); a new tool is
  a change to `app/Agents/Mcp/Tools` with a test, and never one that places orders, changes limits or moves a
  stage (D-7).

## 9. Git

- Branches and pull requests as in D-23. Commit and push after every completed step.
- Conventional Commits: `feat(risk): ...`, `fix(execution): ...`, `docs: ...`. The body says why, what changed
  and how it was proved (tests, golden hash, testnet run).
- No generated files, databases, `.env`, logs or `storage/` content in git.

## 10. Writing

Docs, code comments, commit messages and UI text: English, plain and short, no emojis, no em dashes. Say what a
thing does and why; skip what the code already says. A decision that changes the design goes into
`docs/decisions.md` before the code.
