---
name: bug-fix
description: Use whenever the operator reports something broken in the trading platform: "bug", "does not work", "error", "crashes", "hangs", "wrong number", "why did it buy/sell", an unexpected order or position, a reconciliation mismatch, a halt or demotion that looks wrong, a backtest that does not match paper or live, a golden test going red, P&L or fees that look wrong, stale prices, an MCP tool failing, a Livewire screen showing stale or wrong values, a failing build or deploy. Runs the reproduce, evidence, root cause, class-wide search, graded approval, minimal fix plus regression test protocol. If the fix turns out to need new behaviour, switch to the feature-work skill.
---

# Bug-fix protocol

A bug report is a symptom. In a trading system a wrong number usually comes from somewhere other than where it
shows: a P&L that drifts can be a fee charged in the wrong asset, a candle counted twice, a timezone, a stop that
lived only in memory, or a config key spelled two ways. The protocol exists so a fix explains the cause, covers
every place the cause lives, and leaves a test behind.

Talk to the operator in the language they write in (usually Romanian). Code, docs, commit messages and UI text
are in English: plain, no emojis, no em dashes.

**If live money is exposed** and the fault can place wrong orders or leave positions unprotected: halt the
account first (`php artisan engine:halt <account>`). A halt only stops new entries and keeps protective orders,
so it is always safe. Tell the operator at once. Flattening positions needs the operator's explicit yes.

Read before anything else:

1. The "Known traps" list below: match the symptom against what has already been paid for, FIRST
2. The D-numbers of the area in `docs/decisions.md`, and `docs/architecture.md` (the loop order, modes,
   processes). A behaviour a D-number chose on purpose is not a bug: say so
3. The domain doc of the area (`docs/risk-policy.md`, `docs/simulation.md`, `docs/strategy/*`)
4. `git log --oneline -- <path>` for the area

## Steps

### 1. Reproduce, or prove you cannot

Before any theory: a failing Pest test (`composer test -- --filter=SomeTest`), a replay of the recorded inputs
(`php artisan engine:replay <account> --from=<time> --to=<time>`), a backtest with the exact run definition (strategy
version hash, window, cost model, seed), the testnet suite, or the app doing it on a scratch database in paper
mode. Write the exact command into the diagnosis. If it cannot be reproduced, say so and work from evidence,
never from a guess.

### 2. Evidence first, then ranked hypotheses

Collect facts: the decision record in the journal (inputs, intents, risk verdicts, orders), the engine event log,
orders and fills, the exchange's own order history matched by client order id, reconciliation records, candles
around the time with their received times, the strategy version, the agent runs read, Horizon failed jobs,
logs, `git log -- <path>`. Then list hypotheses **ranked by the evidence**, each with what would confirm or refute
it and the command to do that. Eliminate; do not pick the first plausible one.

### 3. Root cause, not symptom

The diagnosis says *why* the code produces the fault, in one paragraph a reviewer can check. "Catch the
exception" or "round the value" without the sentence that explains where it comes from is not a cause.

### 4. The class, not the instance

With the cause known, search the whole repository for the same pattern (`app`, `tests`, `config`, `resources`,
`database`: a concrete grep, listed with its results in the diagnosis) and name every other occurrence. Check the
Known traps too. The fix covers every occurrence, or the operator decides explicitly to leave them. This step is
not optional and is the one most often skipped.

### 5. Graded approval

- **Trivial**: one obvious cause, one file, no change to a model, a limit, a config value, the data format, a
  golden report or behaviour -> fix it, add the regression test, report briefly.
- **Standard**: everything else, including any change to sizing, risk, fills, costs, a DSL function, an MCP
  tool, or a golden report -> write the diagnosis (below) with the cause, the fix options and their
  blast radius (which golden reports and which live strategies could move), and **wait for the operator's
  confirmation before editing**.
- **Incident**: live money, an exchange key, the production database, a released version, or several areas -> a
  full plan in plan mode. Halt first if money is exposed, then read-only work; back up the database before
  touching anything. Every state-changing command (a cancel, a flatten, a migration, a key rotation, a restore)
  is confirmed one by one. Rollback is `git revert` and a redeploy, or the previous release.

The diagnosis:

```
Symptom:         what the operator saw
Reproduction:    the exact command or steps -> what happens
Evidence:        journal records, orders and fills, exchange history, candles, config, commits
Hypotheses:      ranked; each with the check that confirms or refutes it, and its result
Root cause:      one paragraph
Class search:    the grep -> every hit, and whether it has the same fault
Fix options:     each with its blast radius
Regression test: its name; it fails before the fix
Tier:            trivial | standard | incident
```

### 6. Minimal fix with a regression test

The test calls the code the way its user does and fails before the fix, for the reason you expect; it passes
after and is named in the report. No refactoring alongside, no "while I am here". A fix that needs new
behaviour, a new setting or a new D-number is feature work: stop and switch skills.

### 7. Verify and close

The area's tests, `composer format`, `composer analyse`, the full `composer test`, and the testnet suite when the
fix touches the exchange path. A bug found in the running app is checked again in the running app (scratch
database, paper mode). A golden report that moves is explained in the commit. If the fix reveals a new trap, add
it to Known traps below in the same commit (symptom, cause, where to search, the commit). If it contradicts a
D-number or a doc, correct that.

The commit is `fix(<scope>): <what the operator sees now>`, with a body that says where it was found, the cause
and the regression test; commit and push to the working branch (D-23). Report: symptom, cause, the class search
and its result, the fix, the test, and what was deliberately left.

## Known traps

Traps this project already paid for. The first ones come from the legacy platform (`docs/assessment.md`, commit
`e0ec48b`); the new code is designed against them, so a symptom that looks like one of them usually is one.
Add to the list when a fix reveals a new one.

- **A limit that nothing reads.** The legacy daily loss limit never fired: the bot read `max_daily_loss`, the
  orchestrator wrote `max_daily_loss_pct`. Cause: configuration passed as string-keyed arrays. Search: array
  access with string keys on config or constraint data (`\['max_`, `->get\('max_`, `\?\? `) in `app/Risk`,
  `app/Strategy`, `app/Execution`. Every limit needs a test that trips it.
- **A swallowed error hides a whole feature.** The legacy indicators never reached the model for the life of the
  project because an import error was caught and ignored. Search: `catch \(\\?Throwable`, `catch \(\\?Exception`
  with an empty or log-only body outside a daemon's outermost loop.
- **Silent fallback between modes.** The legacy platform switched to paper when the exchange failed, so the
  operator could not tell what was real. Search: any `catch` that constructs another venue or broker, any
  default mode.
- **Money in float or integer cents.** Sub-cent prices and 8-decimal quantities break. Search: `float`, `(float)`,
  `round(`, `_cents` in domain modules (the architecture test should already fail).
- **Fee in the wrong asset.** The legacy import multiplied a USDT fee by the price as if it were in BTC (about
  210,000 USD of fake fees). Search: fee conversions; every fee must carry its asset.
- **Time without a zone, or the server clock.** Legacy "12:00" and "16:00" were meant as New York hours and
  compared against UTC; two date formats in one column broke "today" filters. Search: `now()`, `Carbon::now`,
  `time()`, `date(` in domain code; string comparisons of times.
- **Stops in memory.** Legacy stops lived in the bot process; a restart orphaned positions. Every open position
  must have a protective order on the exchange. Search: exits evaluated locally without an exchange order.
- **Short on spot.** Spot cannot be shorted; a sell either liquidates holdings or is rejected. Search: short
  intents where the instrument's market type is spot.
- **Exchange filters.** Unrounded quantities and prices are rejected (lot step, tick size, minimum notional).
  Search: order construction that bypasses the filter rounding.
- **Symbol spellings.** `BTC`, `BTCUSDT`, `BTC-USD` and `BTC/USDT` were mixed in legacy code with suffix
  stripping in five places. Use ccxt unified symbols everywhere. Search: `USDT'`, `-USD`, `str_replace` and
  `rtrim` on symbols.
- **Laravel prefixes Redis channels.** A `publish` through the default client goes to a prefixed channel; the
  listener on the bare name never hears it (legacy bridge). Search: `Redis::publish`, channel names, the
  `prefix` option in `config/database.php`.
- **Lookahead in backtests.** Filling at the close of the candle that produced the signal, a higher timeframe
  value used before its candle closed, or data read by exchange time instead of received time. Search: fill
  logic using the signal candle, `on <tf>` evaluation, queries on `exchange_time` in the simulation path.
- **Same-candle stop and target.** Assuming the target filled first makes backtests optimistic. The rule is
  1-minute replay, else stop first.
- **Testnet is not the market.** Testnet prices and liquidity differ; use it for mechanics only.
- **Long-running PHP processes.** Daemons accumulate memory and keep dead database connections; static caches
  leak state between events. Search: `static $` caches in services used by daemons, missing reconnect handling.
- **An agent's tools.** An MCP tool must never act on the exchange, a limit or a stage (D-7, D-63); search
  `app/Agents` for any new write.
- **Signed number formats.** A format like `+0.00;-0.00` turns a tiny negative into `-+0.00`; every signed format
  needs a zero form (`docs/brand.md`, A2).
- **Shell strings.** The legacy watchlist built a shell command from user input (command injection). Pass
  arguments as arrays to `Process`, never a concatenated string. Search: `Process::run('`, `shell_exec`, `exec(`.
- **Pest negated arch expectations with an array subject pass silently.** `expect(['now', 'time'])->not->
  toBeUsedIn('App\Risk')` and `expect(['App\A', 'App\B'])->not->toUse(...)` passed while `App\Risk` called
  `now()`. Give every negated arch expectation one subject and put the list in the target:
  `expect('App\Risk')->not->toUse(['now', 'time'])`. Prove a new arch rule with a temporary violating class
  before trusting it. Search: `expect\(\[` followed by `->not->` in `tests/Arch`.
- **Binance archive time units.** Spot kline archives switched from milliseconds to microseconds on
  2025-01-01 and have no header; USD-M archives are in milliseconds with a header line; funding `calc_time`
  carries millisecond jitter. A parser that assumes one unit puts candles in the year 57,000 or in 1970.
  Recognize the unit by digit count and fail on anything else (`docs/market-data.md`, section 3). Search:
  `/ 1000`, `* 1000`, `createFromTimestampMs` in `app/Market`.
- **Binance numbers in scientific notation.** Funding archives write small rates as `9.2E-7`, `-7E-8`, `0E-8`
  (BNB: 57 of 94 rows in October 2026). `Decimal::of` refuses exponents on purpose; every Binance number goes
  through `ExchangeNumber::plain` (or `Json::number`), which moves the decimal point in the text, never through
  float. Search: `Decimal::of(` or `Price::of(` fed directly from a response or CSV field in `app/Market`.
- **Binance archives can be incomplete.** The USD-M monthly archives for October 2026 have no 2026-10-28 for
  any pair, and the daily archive for that day is not published (404). The backfill fills such holes through REST
  after each archive, and `market:check` reports what stays missing. Never assume an archive is complete.
- **Asset codes are not ASCII.** Binance spot lists `币安人生/USDT`; an `[A-Z0-9]` check made the whole
  instrument sync fail on one symbol (found by `composer test:network`, phase 1 step 4). Asset and Symbol accept
  letters and digits of any script (`\p{L}\p{N}`, `/u`). Search: `A-Z`, `strtoupper` in `app/`.
- **`api.binance.com` answers 451 from restricted locations** (cloud sandboxes, some regions). It is not an
  outage; use the configured public-data hosts (`*.binance.vision`) or run from the operator's machine.
- **A large answer under a whole-transfer timeout.** The spot `exchangeInfo` is 17.8 MB uncompressed, two thirds
  of it permission sets nothing reads; the 15 s HTTP timeout covers the whole transfer, so on a slow line
  `market:instruments spot` timed out, and decoding it passed Herd's 128 MB memory limit. REST answers travel
  gzip-compressed and the spot `exchangeInfo` asks `showPermissionSets=false` (147 KB, same filters). Search:
  `->timeout(` on downloads whose size grows (`BinanceArchive::fetch`, 120 s for daily archives of up to 25 MB).
- **Packages that bring their own `App\` namespace.** `laravel/pint` maps `App\` to its own internal app, which
  merged into ours. Such tools live in `tools/<name>` with their own composer project. Search:
  `grep "'App\\\\\\\\'" vendor/composer/autoload_psr4.php` must list only `app/`.

## Hard rules

- No cause without a reproduction or evidence.
- No fix without the class-wide search.
- No fix without a regression test.
- Standard and incident fixes wait for confirmation; "it is obvious" is not a tier.
- Halting is always allowed; flattening, cancelling, key changes and production data changes need the
  operator's explicit yes for that command.
- The repository is public. Secrets (exchange keys, API tokens) are never printed, logged, committed or pasted
  while debugging: read them only where they are used.
