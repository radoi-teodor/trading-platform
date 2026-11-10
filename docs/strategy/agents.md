# Research and how the platform stays profitable

This is the most important document of the platform. It says who researches strategies and what they may never
do, where profit is supposed to come from, and how the platform notices when it stops coming.

## 1. The honest target

No system is profitable in 95 % of cases, and one that shows a 95 % win rate is usually hiding a tail: small take
profits, wide or missing stops, averaging down. It wins for months and gives everything back in a day. Win rate
is not profitability.

What the platform promises instead (D-15):

1. **Edge before capital.** A strategy reaches live money only when, out of sample and after every cost, the
   lower bound of the 95 % confidence interval of its net expectancy per trade is above zero
   (`docs/simulation.md`, section 7).
2. **Edge while capital.** A live strategy stays live only while its results stay inside the band its tests
   predicted. Outside the band it is demoted automatically, before the loss becomes large.
3. **Bounded losses.** Hard limits in code cap the loss per trade, per day, per week and from the equity peak
   (`docs/risk-policy.md`). A bad period costs a known, small amount.
4. **No edge, no trade.** Staying in stablecoins is a valid and frequent output. Research is not rewarded for
   activity.

The operator should expect losing trades, losing weeks and losing months. The platform's job is to make sure
that over many trades the expected value is positive and measured, and that nothing can lose more than the
policy allows.

## 2. Where the profit is supposed to come from

Every strategy belongs to a family, and every family has a reason to pay: someone on the other side is paying
for something. A strategy without a written answer to "who pays me and why" is not promoted.

| Family | Who pays and why | Works in | Fails in | Typical horizon |
|--------|------------------|----------|----------|-----------------|
| Trend following | Slow reaction to information and herding; trends in crypto persist longer than in most markets | Trending regimes | Choppy ranges: many small losses | Hours to weeks |
| Mean reversion | Short-term overreaction, liquidation cascades that overshoot | Ranges, after forced selling | Strong trends: catching a falling knife | Minutes to days |
| Funding carry | Leveraged longs pay funding on perpetuals; long spot plus short perpetual collects it with little price exposure | Positive funding, calm basis | Funding turning negative, exchange failure, perpetual leg liquidated | Days to months |

Families are combined so their failures do not coincide: trend and mean reversion fail in opposite regimes;
carry earns while both wait. Risk is spread across families, not across symbols that move together.

If the operator wants the highest share of positive months, the portfolio leans on funding carry. It has the most
stable months and the lowest returns, and its risks (exchange failure, funding reversal) are handled by limits in
`docs/risk-policy.md`, not wished away.

## 3. Doctrine

Rules every strategy, research session and review follows.

1. **The model proposes, deterministic code disposes** (D-7). No agent places, changes or cancels orders.
2. **Hypothesis first.** A strategy states its family, who pays, the regime it needs and how it fails, before any
   backtest.
3. **Costs first.** Fees, spread, slippage and funding are in every number an agent reports. A gross return is
   never shown alone.
4. **Out of sample or it did not happen.** In-sample results are for debugging only.
5. **Few parameters, robust plateaus.** A strategy whose result collapses when a parameter moves 20 % is
   overfitted, however good its best point looks.
6. **Regime filters.** A strategy states, in its filters, the market it trades in (for example `adx(14) > 20`);
   outside it, it stays flat.
7. **Size by risk, not by conviction.** Position size comes from the distance to the stop and the risk budget,
   never from how confident a model sounds.
8. **Risk goes down freely and up slowly.** Pausing a strategy or cutting its size is always allowed; the
   opposite needs the gates or the operator (D-19).
9. **Everything is recorded.** Every draft, backtest, trial and engine decision is stored with its inputs and
   time. What is not recorded cannot be audited or replayed.

## 4. Who does what

The platform runs no language model and holds no model API key (D-64). Strategy research is done by an external
agent, such as Claude Code or Claude Desktop on the operator's machine, through the MCP endpoint (D-63). Everything
that touches money is deterministic code.

| Actor | Runs | Reads | Writes | May | May not |
|-------|------|-------|--------|-----|---------|
| Research agent (external, over MCP) | When the operator works with it | Candles, indicators, funding, order book depth, liquidations, the research universe, the DSL reference, saved versions, backtest and grid results, trials | Draft strategy versions, simulation runs | Compile, save drafts, backtest, grid, walk-forward | Promote anything; edit risk limits; place, change or cancel orders |
| Operator | Any time | Everything | Everything, through commands and the UI | Promote, resume, raise risk with the delay of D-19 | Place orders directly (only through strategies or flatten) |
| Risk engine and gates | Every engine event; on promotion | Everything | Verdicts, halts, demotions, gate reports | Halt, demote, reject, promote a draft to Backtested when every gate passes | Promote to any live stage (D-16) |

### Authority matrix

| Action | Operator | Automatic gates | Research agent |
|--------|----------|-----------------|----------------|
| Create a draft strategy | yes | n/a | yes |
| Run a backtest, grid or walk-forward | yes | yes | yes |
| Promote draft to Backtested or paper | yes | yes, if gates pass | no |
| Promote to any live stage | yes, explicitly | no | no |
| Pause a strategy | yes | yes | no |
| Resume a paused strategy | yes | no | no |
| Raise a risk limit | yes, with delay (D-19) | no | no |
| Lower a risk limit | yes | yes | no |
| Halt an account (kill switch) | yes | yes | no |
| Place or cancel an order | no (only through strategies or flatten) | engine only | no |

## 5. The research loop

Nothing learns by changing itself in production.

```
idea --> research agent: chart (get_indicators) --> document --> compile_strategy --> save_strategy (draft)
                                                                                           |
live <-- operator approval <-- paper stage passes <-- gates pass <-- run_grid + walk-forward <-+
  |
  +--> results out of band --> automatic demotion --> the operator and the agent study why --> new version
```

A new idea or a fix is a new strategy version with a stated reason ("stop too tight in high volatility: 61 % of
stops hit within 3 bars, then price recovered"). It goes through every gate like a new strategy. Old versions stay
in the database, immutable.

## 6. Edge monitoring and demotion

For every live or paper strategy the Risk module keeps, after each closed trade:

- net expectancy and its confidence interval over the last 30 and 100 trades;
- drawdown versus the backtest's drawdown distribution;
- hit rate and average win and loss versus the backtest band;
- slippage and fees versus the cost model;
- a CUSUM drift statistic on trade results against the backtest mean.

Demotion is automatic when any of these leaves its band (thresholds in `docs/risk-policy.md`): live goes back
to paper, paper goes back to draft with a review note. The operator is told why, with numbers.

## 7. Limits on research

- Every backtest the agent runs is recorded and counted: the number of trials of a family feeds the deflated
  Sharpe ratio (`docs/simulation.md`), so trying many variants raises the bar each one must clear.
- Each MCP token may make 60 calls a minute and keep 2 simulation runs waiting at a time (D-63).
- A series read over MCP has at most 1,000 rows, and no statement of a research tool holds the database for more
  than 5 seconds (D-93, `docs/universe.md`, section 6).
- There is no automatic regeneration of strategies: the legacy loop that asked for a new strategy every time one
  finished is forbidden.

## 8. Safety

- The MCP tools read market data and strategies and write only draft versions and simulation runs. There is no
  tool that places, changes or cancels an order, changes a limit, moves a stage or reads a secret.
- A draft cannot trade: it reaches paper only through the gates and live only with the operator's approval.
- Every call is recorded in `mcp_calls` without the token (D-63).
