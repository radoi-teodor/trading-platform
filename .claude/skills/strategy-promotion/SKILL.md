---
name: strategy-promotion
description: Use whenever the operator asks whether a strategy is good, asks to test, validate, promote, deploy, go live with, scale up, resume or demote a strategy, asks why a strategy was demoted or halted, or asks to compare strategies. Runs the evidence-first promotion review against the gates in docs/simulation.md and docs/risk-policy.md and produces a promotion report. Never moves a strategy into any live stage without the operator's explicit approval. Not for writing a new strategy (that is the Strategy Author agent or feature-work for new DSL features) or for fixing a bug in the engine (bug-fix).
---

# Strategy promotion protocol

A strategy earns capital with evidence, one stage at a time (D-16), and loses it automatically when the evidence
turns. This skill checks the evidence, writes it down, and asks the operator where the operator must decide.
It does not make strategies look better: a failed gate is the most useful outcome it can produce.

Talk to the operator in the language they write in (usually Romanian). Reports stored in the platform are in
English.

Read first: `docs/strategy/agents.md` (sections 1 to 3 and 7), `docs/simulation.md` (sections 3 to 8),
`docs/risk-policy.md` (sections 6 and 7), and D-14 to D-17 in `docs/decisions.md`.

## Steps

### 1. Identify exactly what is judged

The strategy id, the version number and its SHA-256, the current stage, the target stage, the account. Every
number in the report refers to that version. If the operator talks about "the BTC strategy", resolve it to a
version and confirm.

### 2. Check the hypothesis

The version must state its family, who pays and why, the regime it needs, and how it fails
(`docs/strategy/configurator.md`, section 1). Missing or vague: stop here and say what is missing.

### 3. Check the data

Coverage report for the instruments and window: gaps, duplicates, outliers, delisted instruments included,
funding history for perpetuals. Note what agent features the strategy reads and from when the journal covers
them (D-17); before that date those features are neutral defaults.

### 4. Reproduce the evidence

Re-run, do not trust a stored report: the backtest with its stored definition (must give the same hash), then
the walk-forward, the robustness neighbourhood, the cost stress, the Monte Carlo and the hold-out
(`docs/simulation.md`, section 5). Record how many backtests have been run on this strategy family (agents and
operator together): that count goes into the deflated Sharpe ratio.

### 5. Apply the gates

Fill the table for the target stage. Every row has the measured value, the threshold and pass or fail.

- Backtested: the gates in `docs/simulation.md`, section 7.
- Paper: entered automatically after the gates; leaving it needs the paper conditions.
- Live micro and live steps: the conditions in `docs/risk-policy.md`, section 6, the band in section 7, the paper
  twin's execution comparison, and no open reconciliation or data incident attributable to the strategy.

A gate is never relaxed inside this skill. A proposed change to a threshold is a D-number through `feature-work`.

### 6. Check fit with the book

Correlation with the strategies already live, the effect on total open risk and on the correlation bucket
budgets, and the size the risk policy would give it. A strategy that passes alone but doubles an existing
exposure is reported as such.

### 7. Write the promotion report

Stored in the platform and attached to the version:

```
Strategy:        id, version, hash, family
Hypothesis:      one paragraph, as written in the version
Stage:           current -> requested
Data:            window, coverage, agent features and their journal coverage
Evidence:        the gate table with values, thresholds, pass or fail
Robustness:      neighbourhood, cost stress, Monte Carlo drawdown percentiles
Fit:             correlation with live strategies, risk budget use
Risks:           how it fails and what the band will watch
Verdict:         promote | hold | demote, with the reason in one sentence
```

### 8. Decide

- **Into paper**: automatic when the backtest gates pass. Report it.
- **Into any live stage, or any size increase**: present the report and ask the operator with
  `AskUserQuestion` (promote, hold, or reject). Only an explicit "promote" answer counts. The operator then
  confirms in the UI with re-authentication (`docs/security.md`); this skill does not perform that step.
- **Down**: demotion is automatic when the band is broken. When asked why it happened, show the numbers that
  broke the band and the journal records around it.
- **Resume after a pause or halt**: operator only, with the reason recorded in the journal.

## Hard rules

- No live stage, no resume and no size increase without the operator's explicit approval for that version.
- No gate relaxed for one strategy. No "close enough".
- Every number in the report is reproduced in this run, not copied from an older report.
- Agent-derived features are never evaluated on history before the journal covers it (D-17).
- Win rate is shown, never used as a reason to promote.
- Nothing here changes risk limits; that is the operator's action under D-19.
