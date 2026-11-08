# Engine

How one account's engine turns market events into decisions, orders and a ledger. The same code runs a backtest,
paper trading and live trading (D-8); only the venue and the clock change. `architecture.md` section 4 lists the
loop; this document gives the rules behind each step. Phase 2 builds it for spot, long only (D-35).

## 1. Events

The engine reacts to one kind of market event in Phase 2: **minute closed**. At time `t` it carries the 1-minute
candle of every instrument the account follows whose minute ended at `t` (open time `t - 1m`).

- A backtest reads candles from the database day by day and groups them by minute. Within one minute, candles are
  ordered by unified symbol. Ties between event types, when more types exist, are broken by time, then
  instrument, then event type (`simulation.md`, section 4).
- Strategies do not see 1-minute candles unless their timeframe is 1 minute. Each strategy declares an instrument
  and a timeframe; an aggregator builds that timeframe from the 1-minute candles and hands the strategy a closed
  candle when the last minute of the period closes. A 4-hour candle opening at 08:00 is decided on at 12:00.
- Fills, timers and operator commands become events of their own in Phase 4. In a backtest, fills come from the
  simulated venue and are read at the next minute event, which is how a live engine sees them too: after they
  happened.

## 2. Order of work in one event

1. **Stamp.** The clock is set to `t` (`SimClock` in a backtest) and every record of this event carries `t`.
2. **Commands.** Pause, resume, halt, flatten and promote, queued since the last event. Phase 4.
3. **Reconcile.** Read the venue's order updates since the last event, its open orders and its balances. Apply
   every new fill to the ledger. Compare the ledger with the venue (section 6).
4. **Mark.** Value every asset at the last close of its instrument. Equity is in the account's quote asset
   (USDT). An asset without a price is an error, never a zero.
5. **Risk state.** Update equity peak, day and week start equity, and the halts of `risk-policy.md`, section 1.
   A hard drawdown breach flattens the account here, before any strategy runs.
6. **Protect.** For every position without a working protective order, place one (section 4).
7. **Strategies.** Feed the 1-minute candles to the aggregators. Every strategy whose candle closed at `t` is
   called with that candle and its own position; it returns intents.
8. **Risk engine.** Approve, resize or reject each intent (section 5). Every rejection has a reason code.
9. **Order manager.** Turn approved intents into orders: rounded to the exchange filters valid at `t`, with client
   order ids derived from the decision id, sent to the venue.
10. **Journal.** One decision record per strategy call: candle, intents, verdicts, orders. Engine records for
    halts, flattening and reconciliation.
11. **Publish.** State changes for the UI and notifications. Phase 4.

The simulated venue matches orders against the 1-minute candle that follows the event (section 3), so an order
decided at `t` can fill no earlier than the open of the minute starting at `t`. Nothing in a backtest can see a
price before it existed.

## 3. Venue

`App\Execution\Contracts\Venue` is what the engine knows of an exchange:

| Method | Returns |
|--------|---------|
| `submit(OrderRequest)` | Accepted with the venue's order id, or rejected with a reason |
| `submitOco(OcoRequest)` | A pair of exit orders where a fill of one cancels the other |
| `cancel(ClientOrderId)` | Cancelled, or already final |
| `updatesSince(cursor)` | Order updates in venue order: accepted, partially filled, filled, cancelled, expired, rejected; each fill with price, quantity, fee and fee asset |
| `openOrders()` | Snapshot of working orders |
| `balances()` | Snapshot of balances per asset, free and locked |

**Simulated venue (D-41).** It keeps its own balances and orders like an exchange: an order locks the funds it
needs, an order the balance cannot cover is rejected, a fill moves balances and charges the fee. It is fed each
1-minute candle after the engine has handled the event before it, and matches its working orders against that
candle with the fill rules of `simulation.md`, section 3 (D-36, D-37). It never reads the engine's state.

## 4. Orders and their life

| Intent | Orders |
|--------|--------|
| Open long | A market buy. Phase 2 enters at market only; limit entries come with the DSL (Phase 3) |
| Close | Cancel the position's protective orders, then a market sell of the position, rounded down to the lot step |
| Adjust stop | Cancel the protective orders and place them again at once with the new stop. A stop may only move towards the price |

- **Protective orders live on the venue (D-10).** When an entry fills, the next event places an OCO for the
  position: a stop-loss sell at the stop and, when the strategy gave one, a take-profit limit sell. On Binance spot
  an OCO is placed after the entry fill, not attached to it; in the simulation it becomes active one minute after
  the fill. A stop already crossed when it becomes active fills at that minute's open.
- When one leg of the OCO fills, fully or partially, the venue cancels the other leg, as Binance does. A partial
  fill leaves a remainder without protection; the next event protects it again with the same stop and target.
- **Client order ids** are derived from the decision id and the order's role (`entry`, `exit`, `stop`, `target`),
  at most 36 characters, so a retry can never place an order twice.
- **Rounding.** Quantities round down to the lot step. Prices round to the tick in the direction that does not
  increase risk: a stop rounds towards the market, a take profit away from it. An order below the minimum quantity
  or minimum notional is not sent; the intent is rejected with `below_minimum`.
- **Filters as they were.** The order manager uses the filters valid at the event time. Before the first stored
  filter row of an instrument the oldest known row is used, and the backtest report says so.

## 5. Risk in the engine

Phase 2 enforces `risk-policy.md`, section 1, except the correlation buckets (Phase 4), plus the pre-trade
checks of section 4 that concern spot.

| Check, in order | Result |
|-----------------|--------|
| Account or strategy halted | Reject `halted` |
| Strategy already holds the instrument (no pyramiding in Phase 2) | Reject `position_open` |
| Second entry intent for the same strategy, instrument and candle | Reject `duplicate_intent` |
| No stop under the account policy | Reject `no_stop` |
| Stop at or above the decision price | Reject `invalid_stop` |
| Open positions at the limit | Reject `max_positions` |
| Size from risk per trade: equity times 0.5 % divided by (decision price minus stop) | Sized |
| Notional above 25 % of equity | Resized down |
| Open risk after this entry above 3 % of equity | Reject `open_risk` |
| Cost above free cash, with a 1 % buffer for slippage and gaps | Resized down |
| Below the exchange minimum after rounding | Reject `below_minimum` |

Close intents are always approved: reducing risk is never blocked. An adjust that moves a stop away from the
price is rejected with `stop_widening`.

**Loss limits.** Daily loss is measured from equity at 00:00 UTC and halts entries until the next 00:00 UTC.
Weekly loss from Monday 00:00 UTC halts entries until the operator resumes; in a backtest that is the rest of the
run, and the report says when it happened. A drawdown of 10 % from the peak halts all strategies; 15 % flattens the
account, then halts. A halt keeps protective orders. It would also cancel working entry orders, but Phase 2 enters
at market only and a market order fills or expires in the minute after its decision, so none is working when a
limit is reached; cancelling them comes with limit entries.

**Benchmark profile (D-40).** Buy and hold and the 50/200 trend rule are comparisons, not strategies. They run
with a profile that puts all free quote into one position by quote amount, needs no stop and has no loss halts.
The engine accepts this profile only in backtest mode; paper and live refuse to start with it.

## 6. Ledger and reconciliation

The ledger (`App\Portfolio`) is the account's book:

- **Balances per asset.** A buy adds the base quantity and removes the quote cost; a sell does the reverse.
- **Fees (D-38).** On spot without BNB payment the fee is charged in the asset received: base on a buy, quote on a
  sell. Taker rate for market and stop orders and for limits that fill on arrival, maker rate for limits that rested.
- **Positions per strategy and instrument.** Quantity net of fees, average entry price, initial stop, quote cost.
- **Dust.** A sell rounds down to the lot step, so a remainder smaller than the step can stay. When a position
  cannot sell any further it is closed and its remainder moves to the account's unattributed holdings, still
  valued in equity.
- **Round trips.** From the first entry fill to the closing exit: entry and exit average prices, gross and net
  profit in quote, fees valued in quote at fill time, initial risk and R multiple when there was a stop, the lowest
  low and highest high while open (maximum adverse and favourable excursion), and the exit reason (`stop`,
  `target`, `signal`, `flatten`). A position still open when a backtest ends is marked to market and reported
  apart; it is not closed by the simulation.
- **Identity.** For every asset: balance = initial balance + bought - sold - fees charged in that asset. Tests
  check it after every fill.

**Reconciliation (D-10, D-41).** At every event the ledger's balances must equal the venue's (free plus locked),
asset by asset, to the last digit, and every working order the engine believes in must be open on the venue. In
live trading the venue wins and the difference is journaled and alerted (Phase 4). In a backtest both sides are
our code, so a difference can only be a bug: the run stops with the mismatch.

## 7. Journal

`App\Execution\Contracts\DecisionLog` receives the records of step 10. A backtest keeps them in memory and
summarises them in its report: decisions, intents, verdicts by reason code, orders, halts. The database journal
comes in Phase 4. Intents emitted during warmup (before the run's start, while indicators fill) are not acted
on and are counted apart.

## 8. Perpetuals (Phase 2b)

USD-M perpetuals settled in USDT, one-way mode, isolated margin (D-43). The account has two wallets: spot and USD-M.
USDT in the spot wallet is not margin; the split is set when a run starts (D-49).

**Positions.** One strategy at most per perpetual instrument and account, so the venue's net position is the
strategy's position; the engine refuses a second one. A position has a side, a quantity, an average entry price `E`,
a leverage `L` set before the entry, and an isolated margin `M`:

| Event | Effect |
|-------|--------|
| Fill that opens or adds `q` at `p` | `M += q × p / L` taken from the wallet; fee taken from the wallet; `E` becomes the average |
| Fill that reduces by `q` at `p` | Realized `side × q × (p - E)` and the released margin `M × q / Q` go back to the wallet; fee taken from the wallet |
| Funding at time `T`, rate `r`, mark `P` | `-side × Q × P × r` paid from or received into the wallet (D-44) |
| Liquidation | The position closes and `M` is lost (D-45) |

Equity is the spot holdings at their marks, plus the USD-M wallet, plus every position's `M + side × Q × (P - E)`.

**Liquidation price** with the maintenance margin rate `m` (D-45): a long at `(Q × E - M) / (Q × (1 - m))`, a short at
`(Q × E + M) / (Q × (1 + m))`.

**Protection (D-46).** Binance futures has no OCO. A position keeps a reduce-only STOP_MARKET and, when the strategy
gave a target, a reduce-only TAKE_PROFIT_MARKET. When one fills the engine cancels the other at the next event;
reduce-only makes sure the other can never open a position in between. Exits, flattening and signal closes are
reduce-only market orders.

**Carry pairs (D-48).** `OpenCarry` buys spot and sells the perpetual for the same quantity in one decision. The pair
has no stop: the hedge is its protection. Its size and leverage follow D-48; when one leg is gone the strategy closes
the other; a flatten closes the spot leg first, then the hedge.

**Reconciliation** compares, at every event, the spot balances, the USD-M wallet and every position (side, quantity,
entry price, margin) with the venue's. The venue's updates are one numbered stream of order updates and income
(funding, liquidation), so the ledger books them in the order they happened.
