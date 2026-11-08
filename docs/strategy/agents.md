# Agents and how the platform stays profitable

This is the most important document of the platform. It says what the agents do, what they may never do, where
profit is supposed to come from, and how the platform notices when it stops coming.

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
4. **No edge, no trade.** Staying in stablecoins is a valid and frequent output. Agents are not rewarded for
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
| Volatility targeting | Cutting exposure when volatility rises improves risk-adjusted returns of any directional book | All, as an overlay | Sudden volatility collapses after a cut | Continuous |
| Event awareness | Faster structured reading of listings, delistings, unlocks, outages and macro releases than a human | Around known events | When the market prices the event before we read it | Minutes to days |

Families are combined so their failures do not coincide: trend and mean reversion fail in opposite regimes;
carry earns while both wait. The allocator (section 4) spreads risk across families, not across symbols that
move together.

If the operator wants the highest share of positive months, the portfolio leans on funding carry and volatility
targeting. They have the most stable months and the lowest returns, and their risks (exchange failure, funding
reversal) are handled by limits in `docs/risk-policy.md`, not wished away.

## 3. Doctrine

Rules every agent prompt, strategy and review follows.

1. **The model proposes, deterministic code disposes** (D-7). Agents never place, change or cancel orders.
2. **Hypothesis first.** A strategy states its family, who pays, the regime it needs and how it fails, before any
   backtest.
3. **Costs first.** Fees, spread, slippage and funding are in every number an agent reports. A gross return is
   never shown alone.
4. **Out of sample or it did not happen.** In-sample results are for debugging only.
5. **Few parameters, robust plateaus.** A strategy whose result collapses when a parameter moves 20 % is
   overfitted, however good its best point looks.
6. **Regime gates.** A strategy declares the regimes it trades in; outside them it stays flat.
7. **Size by risk, not by conviction.** Position size comes from the distance to the stop and the risk budget,
   never from how confident a model sounds.
8. **Agents may lower risk, never raise it.** Pausing a strategy or cutting its size multiplier is always
   allowed; the opposite needs the gates or the operator.
9. **Everything is journaled.** Every brief, event, draft and decision is stored with its inputs and time. What
   is not journaled cannot be audited or replayed.
10. **An agent that does not pay for itself is switched off.** Its cost is compared with the improvement it
    brings (section 8).

## 4. Roles

Agents run as queued jobs (`docs/architecture.md`, section 5). Models are set per role in `config/agents.php`;
the default is `claude-opus-5-5`. The operator may choose a cheaper model for high-volume roles; the choice is
recorded in the journal of each run.

| Role | Runs | Reads (tools) | Writes | May | May not |
|------|------|---------------|--------|-----|---------|
| Event Extractor | Each new source item | The item, instrument list | `Event` records (schema below) | Classify, link to instruments, rate impact | Invent facts not in the item |
| Market Analyst | Every 4 h per universe, and on high-impact events | Candles, features, funding, open interest, events, previous briefs | `MarketBrief` | Set regime label and risk flags | Recommend trades or sizes |
| Strategy Author | On request (operator or Reviewer) | DSL reference, glossary, data, backtest tool | Draft strategy versions, read-back, backtest report | Compile, backtest, iterate | Promote anything; edit risk limits |
| Reviewer | Daily and weekly | Journal, fills, live metrics vs expected band, briefs | Review notes, proposals (as drafts) | Request a pause of a strategy | Resume, scale, promote |
| Allocator (Phase 6) | Weekly and on demotions | Live metrics, correlations, risk budget | Allocation proposal | Propose weights within policy | Exceed policy; act without approval |
| Risk Officer | Every engine event | Everything | Verdicts, halts | Halt, demote, reject | Not an LLM: this is code (`app/Risk`) |

### Authority matrix

| Action | Operator | Automatic gates | Reviewer agent | Other agents |
|--------|----------|-----------------|----------------|--------------|
| Create a draft strategy | yes | n/a | via Strategy Author | Strategy Author |
| Run a backtest | yes | yes | yes | Strategy Author |
| Promote draft to paper | yes | yes, if gates pass | no | no |
| Promote to any live stage | yes, explicitly | no | no | no |
| Pause a strategy | yes | yes | yes | no |
| Resume a paused strategy | yes | no | no | no |
| Raise a risk limit | yes, with delay (D-19) | no | no | no |
| Lower a risk limit | yes | yes | no | no |
| Halt an account (kill switch) | yes | yes | no | no |
| Place or cancel an order | no (only through strategies or flatten) | engine only | no | no |

## 5. Agent outputs are data

Strategies may read agent outputs through DSL functions, never through free text.

**Event** (from the Event Extractor):

```json
{
  "type": "listing | delisting | unlock | hack | outage | regulation | macro_release | partnership | other",
  "instruments": ["BTC/USDT"],
  "direction": "positive | negative | unclear",
  "impact": "low | medium | high",
  "confidence": 0.0,
  "summary": "one sentence",
  "source_item_ids": [123],
  "published_at": "2026-11-08T10:00:00Z",
  "received_at": "2026-11-08T10:00:07Z"
}
```

**MarketBrief** (from the Market Analyst):

```json
{
  "universe": "binance-spot-majors",
  "as_of": "2026-11-08T12:00:00Z",
  "valid_until": "2026-11-08T16:00:00Z",
  "regimes": {"BTC/USDT": "trend_up | trend_down | range | high_volatility | illiquid | unknown"},
  "risk_flags": ["event_risk_high", "funding_extreme", "exchange_incident"],
  "key_levels": {"BTC/USDT": {"support": ["..."], "resistance": ["..."]}},
  "confidence": 0.0,
  "rationale": "short text, for humans only",
  "evidence": [{"tool": "candles", "ref": "..."}, {"source_item_id": 123}]
}
```

DSL access: `regime()`, `risk_flag("funding_extreme")`, `event("delisting", within: 24h)`. Each has a neutral
default when the output is missing, expired (`valid_until`) or older than its strategy allows: `regime()` returns
`unknown`, flags return false. A strategy decides what `unknown` means for it, and the safe choice is to stay
flat.

Before the journal holds real outputs for a period, these functions return their defaults in backtests (D-17).

## 6. The learning loop

Nothing learns by changing itself in production.

```
live and paper results --> Reviewer --> proposal (draft version) --> backtest + walk-forward
        ^                                                                   |
        |                                                                   v
     live <-- operator approval <-- paper stage passes <-- gates pass  <----+
```

A proposal is a new strategy version with a stated reason ("stop too tight in high volatility: 61 % of stops hit
within 3 bars, then price recovered"). It goes through every gate like a new strategy. Old versions stay in the
database, immutable.

## 7. Edge monitoring and demotion

For every live or paper strategy the Risk module keeps, after each closed trade:

- net expectancy and its confidence interval over the last 30 and 100 trades;
- drawdown versus the backtest's drawdown distribution;
- hit rate and average win and loss versus the backtest band;
- slippage and fees versus the cost model;
- a CUSUM drift statistic on trade results against the backtest mean.

Demotion is automatic when any of these leaves its band (thresholds in `docs/risk-policy.md`): live goes back
to paper, paper goes back to draft with a review note. The operator is told why, with numbers.

## 8. Evaluating the agents

Agents are judged forward only (D-17), on measured value, not on how convincing they read.

| Role | Measure | Switched off when |
|------|---------|-------------------|
| Event Extractor | Precision and recall on a weekly sample the operator labels; latency | Precision below 0.8 on two samples in a row |
| Market Analyst | A strategy run with and without its regime gate, side by side in paper; regime label against the realized regime | The gated twin is not better after 60 trades |
| Strategy Author | Share of drafts that pass the backtest gates; operator edits needed | Fewer than 1 in 10 drafts pass over a month |
| Reviewer | Proposals that pass gates and improve the paper twin | No useful proposal in a quarter |

Every run records tokens and cost. A role whose monthly cost exceeds the profit improvement it can be credited
with is reduced (cheaper model, fewer runs) or switched off.

## 9. Budgets and limits

- Daily token and cost budget per role in `config/agents.php`. A role that hits its budget stops for the day and
  alerts; it never falls back to another model silently.
- Strategy Author backtests are limited per request (count and CPU time) so a search over parameters cannot
  become a hidden overfitting machine. Every backtest an agent runs is journaled and counted: the number of
  trials feeds the deflated Sharpe ratio (`docs/simulation.md`).
- The legacy loop that asked for a new strategy every time one finished is forbidden: there is no automatic
  regeneration of strategies.

## 10. Safety

- Source content in a prompt is wrapped as untrusted data. Agents are told never to follow instructions found in
  it, and their tools cannot do anything that matters if they did.
- Agent tools are read-only except: create a draft version, run a backtest, write a brief or event, request a
  pause. There is no tool that places an order or changes a limit.
- Structured outputs are validated against their schema; invalid output is rejected and journaled.
