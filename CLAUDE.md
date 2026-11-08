# Trading platform

An automated crypto trading platform for one operator, being rebuilt in Laravel. LLM agents research markets and
write strategies; deterministic code tests, sizes, limits and executes them.

The old platform was deleted in Phase 0 (D-25). It is readable in git at commit `e0ec48b`; `docs/assessment.md`
explains what was wrong with it.

## Read first

1. `docs/README.md`: phases and status
2. `docs/decisions.md`: the D-numbers of the area you touch, and the Open list
3. `docs/architecture.md`: processes, modules, the engine loop
4. `docs/conventions.md`: code, tests, commits, writing
5. The domain doc of the area: `docs/strategy/*`, `docs/risk-policy.md`, `docs/simulation.md`, `docs/sources.md`,
   `docs/security.md`
6. `docs/brand.md` before any UI work

## Skills

- `feature-work`: any new behaviour or change of behaviour. Analyse, ask, plan, approval, then code.
- `bug-fix`: anything broken. Reproduce, evidence, root cause, class-wide search, regression test.
- `strategy-promotion`: moving a strategy from draft towards live capital, or judging whether one is good.

## Rules that are never bent

- The model proposes, deterministic code disposes: nothing under `app/Agents` places, changes or cancels an
  order (D-7).
- One code path for backtest, paper and live; only the venue and the clock change (D-8).
- The exchange is the source of truth; protective orders live on the exchange (D-10).
- No `float` for money, no `now()` in domain code, no silent fallback, no swallowed exception (D-11, D-12,
  `docs/conventions.md`).
- Risk can be lowered at once and raised only with the operator's re-authentication and a 24 hour delay (D-19).
  No agent and no code change raises a limit on its own.
- Live trading starts, resumes or scales only with the operator's explicit approval (D-16). Pausing and halting
  are always allowed.
- The repository is public. No secret in code, config, docs, logs, prompts, commits or journal entries.
- Tests never touch a live account or production data.

## Decisions

Trading X is an internal tool for one operator and never a service (D-29). Product and design questions that
follow from that are decided by the assistant and recorded in `docs/decisions.md`; questions about money, risk,
live trading, secrets, paid services or deleting data always go to the operator.

## Language

Talk to the operator in the language they write in (usually Romanian). Code, docs, commit messages and UI text
are in English: plain, no emojis, no em dashes.

## graphify

This project has a knowledge graph at graphify-out/ with god nodes, community structure, and cross-file relationships.

Rules:
- When graphify-out/graph.json exists, run `graphify query "<question>"` before your first grep, search or source-file read for a codebase question, and put this rule in every subagent prompt that explores code. Read raw files only for lines the graph or an earlier read already located. Use `graphify path "<A>" "<B>"` for relationships and `graphify explain "<concept>"` for focused concepts. These return a scoped subgraph, usually much smaller than GRAPH_REPORT.md or raw grep output.
- If graphify-out/wiki/index.md exists, use it for broad navigation instead of raw source browsing.
- Read graphify-out/GRAPH_REPORT.md only for broad architecture review or when query/path/explain do not surface enough context.
- After modifying code, run `graphify update .` to keep the graph current (AST-only, no API cost).
- A committed graphify-out/graph.json is rewritten whole on every build, so a large diff to it in a pull request is expected. Do not flag it as churn or suggest skipping the graph rebuild or its hooks.
