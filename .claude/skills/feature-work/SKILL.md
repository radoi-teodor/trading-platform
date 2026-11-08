---
name: feature-work
description: Use whenever the operator asks to build a new feature in the trading platform, change or extend an existing one, or add a module, a market data feed, an exchange, a source or connector, an agent role or tool, a DSL function or strategy template, a risk limit or breaker, a simulation method or metric, a ledger rule, a Livewire screen, a chart, a notification, a setting or a command, or says "implement", "add", "make X do Y", "change how X works", "next phase". Runs the mandatory analyse, ask, plan, approval protocol before any code is written. Not for a bug fix with a known cause or a one-line copy change.
---

# Feature work protocol

You are about to add or change behaviour in a platform that trades real money. It is one Laravel application:
market data daemons, one engine process per account, queued agents that call the Claude API, a backtester that
runs the same engine loop, and a Livewire UI. Data, strategies, risk, execution and agents feed each other, and a
change in one moves the others. A new term in a sizing rule can break a golden backtest; a new agent tool can
open a path to the exchange; a new column can break replay of old journals. Work that starts from a partial
reading of the code is how that happens.

**Nothing is edited until the operator has approved a written plan.** Not a test, not a config file, not a view.
The only files you may write before approval are the plan itself (plan mode) and notes in the scratchpad.

Talk to the operator in the language they write in (usually Romanian). Code, docs, commit messages and UI text
are in English: plain, no emojis, no em dashes (`docs/conventions.md`).

Read, in this order, before anything else:

1. `docs/README.md`: phases and status
2. `docs/decisions.md`: every D-number of the area the change touches, and the Open list. A decision that changes
   the design is written there before the code
3. `docs/architecture.md`: processes, modules and their allowed dependencies, the engine loop order, modes
4. The domain doc of the area: `docs/strategy/agents.md`, `docs/strategy/configurator.md`, `docs/risk-policy.md`,
   `docs/simulation.md`, `docs/sources.md`, `docs/security.md`
5. `docs/brand.md` if any UI, chart, notification or report text is involved
6. `docs/conventions.md`: code style, numbers and time, errors, tests, git
7. The "Known traps" list in `.claude/skills/bug-fix/SKILL.md`: design around them

## Steps

### 1. Intake (2 minutes)

Restate the request in three lines: what the operator wants, where they will see it (a screen, a chart, a
notification, a backtest report, the journal, a command), and what success looks like (a test passing, a golden
report unchanged or changed for a stated reason, a screen showing X). List what is explicitly NOT asked. If the
request is really a bug fix, say so and switch to `bug-fix`. If it is about promoting or judging a strategy,
switch to `strategy-promotion`.

### 2. Full analysis (read-only)

Walk the checklist below against the code, not from memory. Produce an **impact map**:
`file or component -> why it is affected -> invariant it carries`, with the tests that already cover each area.

- **Modules**: which of `Support`, `Market`, `Sources`, `Strategy`, `Risk`, `Portfolio`, `Execution`,
  `Simulation`, `Agents`, `Journal`, `Notifications`, `Web` change, and whether any new dependency breaks the
  allowed direction (`docs/architecture.md`, section 3). `Agents` never depends on `Execution`.
- **Engine loop**: at which step it runs (`docs/architecture.md`, section 4), on which event (candle close, fill,
  timer, command), in which process. The engine is the single writer of an account's trading state; the web
  process writes commands only.
- **One code path** (D-8): does it behave the same in backtest, paper, testnet and live? If it needs the clock,
  randomness or the venue, it takes them injected.
- **Risk**: which limits, breakers or pre-trade checks it touches or should touch (`docs/risk-policy.md`). Does
  anything raise risk? That needs the operator's re-authentication path (D-19), never a default.
- **Numbers and time**: every amount a `Number` value object with its asset, every time UTC from the `Clock`
  (D-11, D-12).
- **Determinism and replay**: seeded randomness, ordered events, golden tests that may move, old journals that
  must still replay.
- **Agents and the LLM boundary**: role, tools, output schema, budget, model setting, cost recording,
  untrusted-content wrapping, and D-17 (no LLM calls in backtests; agent features read the journal).
- **Exchange**: ccxt capabilities for Binance, exchange filters, rate limits, testnet behaviour, what happens on
  error (fail loud, halt; never fall back to paper).
- **Data**: migrations (additive), existing rows, the journal format version, catalog changes, retention.
- **UI**: the Livewire components and the data they read, Reverb events, the brand rules and checklist
  (`docs/brand.md`, Part C), widths from 360 to 1280 and up, the mode badge, the Halt button.
- **Security**: secrets, re-authentication, outbound hosts, what an agent could do with a new tool.
- **Operations**: new processes, supervisor config, health checks, runbook entries.

When the surface is wide, delegate read-only exploration to subagents. Give each one the reading list above and
the area's D-numbers in its prompt: a subagent starts with no context.

Deliverable: the impact map, written into the plan draft. If you cannot explain how the change travels from where
it starts (a candle, a source item, an agent run, an operator command) through the engine to the ledger, the
journal and what the operator sees, you are not done analysing.

### 3. Ask everything, once

Collect every ambiguity and every decision that would change the work materially, and ask them in ONE round with
`AskUserQuestion` (options plus a recommended default), grouped: scope and non-goals, risk and money impact,
where the operator sees it, data and migration, exchange and agent limits, performance, naming. Do not assume an
answer that changes the design. Do not ask what the code or the docs already answer. Product and design questions
whose answer follows from internal, single-operator use (D-29) are decided by you and recorded as a D-number in
the plan, not asked; money, risk, live trading, secrets, paid services and data deletion are always asked.

### 4. Plan and approval

Enter plan mode (`EnterPlanMode`). The plan has: context; answers received; the impact map; the design (the new
D-number first, config keys, data shape and its migration, UI from `docs/brand.md`); numbered steps, each one
shippable, naming the files it touches and the tests written first; risks and rollback (per step, `git revert`
of that step, and what happens to data written in between); docs; acceptance criteria (which test, golden report
or scenario proves it); verification (the commands below and the real-app check). Leave plan mode with
`ExitPlanMode`: that is the approval request. If the operator changes anything, update the plan and ask again.
"Looks good" on a plan the operator has not seen is not approval.

A large feature (a new phase) gets its design in `docs/` first.

### 5. Execute strictly on the approved plan

Follow the steps in order. A discovery that forces a deviation (a hidden coupling, a golden report moving for an
unexplained reason, a limit that no longer holds) is reported and approved before you act on it. TDD: the tests
named in the plan are written before the code they cover, and you watch them fail. Every step ends with:

```sh
composer format
composer analyse                     # phpstan at the project level, no new baseline entries
composer test                        # the full default suite: unit, feature, arch, golden
```

and, when the step touches the exchange or the agents, the opt-in suites (ask before the LLM one, it costs
money):

```sh
composer test:testnet                # TRADING_TESTNET=1, Binance testnet only
composer test:llm                    # TRADING_LLM=1, real Claude calls
```

then a commit and a push to the working branch (D-23). For a step in `Support`, `Strategy`, `Risk`, `Simulation`
or `Portfolio`, run the mutation pass on what changed when the operator agrees (`composer mutate`, long).

### 6. Close

- **The real app.** Run the app locally against a scratch database and paper mode, and use the feature as the
  operator would. Drive the browser with Playwright, screenshot at 1280 and 360 wide, in every mode the chosen
  identity supports. Never against a live account or production data.
- **Docs, in the same commits**: the D-number in `docs/decisions.md` (and the Open list), `docs/architecture.md`
  when processes, modules or the loop change, the domain doc of the area, `docs/runbook.md` for new operations,
  the phase status in `docs/README.md`.
- **Report** what shipped against the acceptance criteria, what did not, and what is left with the operator.

## Hard rules

- No edits before approval. No "small prep" edits either.
- The impact map names files; "probably affects X" is not analysis.
- Questions go to the operator in one batch, with options; silence is not an answer.
- A plan without tests, rollback and acceptance criteria is not a plan.
- Conventional Commits (`feat(risk): ...`) that say why, what and how it was proved; commit and push after every
  step; changes reach `main` through a pull request.
- These are invariants; the plan states how each affected one is preserved:
  - The model proposes, deterministic code disposes: no path from `app/Agents` to an order (D-7).
  - One code path for backtest, paper and live (D-8).
  - The exchange is the source of truth; protective orders live on the exchange (D-10).
  - No `float` money, no `now()` in the domain, no swallowed exception, no silent fallback.
  - Risk is raised only by the operator, with re-authentication and the 24 hour delay (D-19).
  - Backtests never call an LLM; agent features are point in time (D-17).
  - Old journals still replay and old rows still load.
  - The UI never waits on the engine, the exchange or the LLM.
  - The repository is public: no secret in code, config, docs, logs, prompts, commits or journal entries.
  - Tests and UI checks never touch a live account or production data.
