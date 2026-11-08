# Brand and visual identity

Status: three candidate identities, choice open (D-24, O-2). Once the operator picks one, it is binding for every
screen, component, chart, email and notification. Anything that does not comply is rebuilt until it does; "it
was already like that" is not an exception.

- **Part A** holds the rules every identity shares. They apply now, before the choice.
- **Part B** describes the three identities: Graphite, Harbor, Voltage. These are codenames, not product names
  (O-3).
- **Part C** is the checklist every UI change passes.

All color values below were checked by script: every text token meets WCAG 2.2 AA (4.5:1) on the surfaces it is
used on, and the chart series palettes pass the color-vision-deficiency checks of the dataviz method
(section A5). A new token or a changed value is checked again before it ships.

---

## Part A. Shared rules

### A1. Tokens only

Every color, font, size, radius, shadow and spacing comes from a token. Tokens live in `resources/css/tokens.css`
(one block per identity and mode, selected by `data-theme` on `<html>`); Tailwind utilities map to them
(`bg-surface`, `text-muted`, `text-up`, `border-strong`). A hex, `rgb()` or `hsl()` value anywhere else (Blade,
PHP, JavaScript, component CSS) is a defect.

The token names are the same in every identity, so changing identity changes no markup:

| Token | Use |
|-------|-----|
| `bg` | Page background |
| `surface` | Cards, panels, tables, chart background |
| `surface-2` | Raised or alternate rows, inputs, hover |
| `border` | Hairlines, dividers, chart grid (decorative) |
| `border-strong` | Input and control borders (at least 3:1) |
| `text` | Primary text |
| `text-muted` | Secondary text, labels, axis text |
| `text-subtle` | Placeholders, disabled, large captions only |
| `accent` | Primary actions, selection, links, active navigation |
| `accent-contrast` | Text on an `accent` fill |
| `focus` | Focus ring |
| `up`, `down` | Price and P&L direction; `down` is also `danger` for destructive actions |
| `up-alt`, `down-alt` | The color-vision-safe direction pair (blue and orange), a user setting |
| `warn` | Caution states, testnet mode |
| `info` | Neutral information, backtest mode |
| `live` | Live mode only |
| `agent` | Marks content produced by an agent |
| `series-1` to `series-5` | Chart series identity, fixed order |

### A2. Numbers

- Prices, quantities, money, percentages and times in tables: the identity's monospace face with tabular
  figures, right-aligned.
- Precision comes from the instrument (tick size, lot step), never a fixed two decimals.
- Signed values always carry the sign and have a zero form: `+0.50%`, `-0.50%`, `0.00%`.
- Units and windows are always stated: `+4.2% net, 30 days`, `0.5% of equity`.
- Large money values use thousands separators; no abbreviations (`12.4k`) in tables, only in stat tiles.

### A3. Direction and status

- `up` and `down` mean price or P&L direction and nothing else. They are never used for series identity,
  branding or decoration.
- Direction is never color alone: always a sign, an arrow or a label next to the color.
- A user setting swaps `up` and `down` for `up-alt` and `down-alt` (blue and orange) for color-vision deficiency.
- Status (healthy, degraded, failed) uses `up`, `warn` and `down` with an icon and a word.

### A4. Modes

The mode is visible on every screen, in every notification and in every exported report.

| Mode | Badge | App shell |
|------|-------|-----------|
| Live | Solid `live` fill, label `LIVE` and the account name | 2px `live` line across the top of the window |
| Testnet | `warn` outline, label `TESTNET` | none |
| Paper | `text-muted` dashed outline, label `PAPER` | none |
| Backtest | `info` outline, label `BACKTEST` and the data window | none |

Actions that affect live money (promote to live, flatten, raise a limit) use a confirmation dialog that states the
consequence in one sentence and asks for re-authentication (`docs/security.md`).

### A5. Charts

- TradingView Lightweight Charts, configured only from tokens.
- Candles: body and wick in `up` / `down`. Volume histogram in the candle colors at reduced opacity.
- Grid in `border`, axis text in `text-muted` with the monospace face at the smallest size, crosshair in
  `text-subtle`, dashed.
- Trades: entry marker as a triangle (up for long, down for short) in `text`, exit marker as a square;
  stop-loss line dashed in `down`, take-profit dashed in `up`, entry price solid in `text-muted`.
- Equity curve: 2px line in `series-1`, area fill at 12% opacity. Drawdown: separate chart below, area in `down`
  at 20% opacity. Benchmarks: dashed `text-subtle`.
- Series palette: five slots, assigned in fixed order, never cycled. A sixth series folds into "Other" or the
  chart becomes small multiples. A series keeps its color when filters change.
- On a candle chart, indicator overlays use only slots 1, 3 and 4. Slots 2 and 5 (orange and magenta) sit too
  close to `down` to share a chart with candles.
- One y-axis per chart. Two measures with different scales become two charts.
- Two or more series get a legend; up to four also get direct labels. Text in charts uses text tokens, never the
  series color.
- Every chart has a table view and a hover tooltip or crosshair. In light mode slots 4 and 5 are below 3:1 on
  white, so a chart that uses them must show direct labels or rely on the table view.

### A6. Layout

- App shell: left navigation (232px, collapsible to 64px), top bar (48px) with the product mark, mode badge,
  account selector, health indicators, the **Halt** button, and the user menu.
- The Halt button is visible on every screen at every width, outlined in `down`.
- Content on a 12-column grid. Breakpoints: 1280 and up full; 1024 navigation collapsed; 768 monitoring layout;
  360 phone: positions, P&L, alerts and Halt only, read-mostly.

### A7. Components

Built once as Blade components, then composed: button (primary, secondary, ghost, danger), input, select,
toggle, segmented control, tabs, data table (sticky header, sortable, right-aligned numbers, dense), stat tile,
limit meter, badge (status, mode, stage, agent), banner, toast, confirmation dialog, drawer, DSL editor
(monospace, error gutter, line numbers), read-back panel, chart card, empty state, skeleton. Every component has
default, hover, focus, disabled, loading and error states.

### A8. Icons

One set: Lucide, 16px in tables and controls, 20px in navigation, stroke 1.5px. No emojis anywhere in the
product (the legacy UI used them for strategy styles).

### A9. Motion

Transitions 120 to 160ms, ease-out. A changed price tints its cell background in `up` or `down` at low opacity
for 300ms; numbers never animate or count up. `prefers-reduced-motion` turns tints and transitions off.

### A10. Voice

- English, sentence case, plain words, no exclamation marks, no emojis, no em dashes.
- Never: "guaranteed", "risk-free", "sure profit", "can't lose", "to the moon", or any promise of returns.
- Costs next to returns, risk before return: "Max drawdown 6.1%. Net return +14.2% over 12 months after fees and
  slippage."
- Agent output is labeled with the role, the time, the confidence and the sources: "Analyst, 12:00 UTC,
  confidence 0.71, 3 sources."
- Errors say what happened, why, and what to do next, without blaming the user.

### A11. Accessibility

WCAG 2.2 AA: text contrast as verified in Part B, visible 2px focus ring in `focus` with 2px offset, full
keyboard operation, labels on every control, a text summary and table view for every chart, no information by
color alone, support for forced colors.

### A12. Mark

Until the product is named (O-3), the mark is the product name set in the identity's display face. No logo
work before the name.

---

## Part B. The three identities

### B1. Graphite: the instrument

**Idea.** A precision instrument panel. Dark graphite, one cold accent (ice blue), amber kept only for caution.
Quiet, dense, every pixel spent on data.

**Suits** an operator who watches many instruments for long stretches, often at night, and wants maximum
information per screen.

**Personality:** precise, quiet, dense.

| Token | Dark (default) | Light (reports, print) |
|-------|----------------|-------------------------|
| `bg` | `#0A0C0F` | `#F4F5F7` |
| `surface` | `#101419` | `#FFFFFF` |
| `surface-2` | `#161B21` | `#EEF1F4` |
| `border` | `#232A32` | `#D9DEE4` |
| `border-strong` | `#5C6878` | `#8A94A1` |
| `text` | `#E6EAEF` | `#0E1318` |
| `text-muted` | `#9BA5B3` | `#4F5B69` |
| `text-subtle` | `#6F7A88` | `#6B7684` |
| `accent` | `#7CC4FA` | `#0A6FB8` |
| `accent-contrast` | `#06131D` | `#FFFFFF` |
| `focus` | `#7CC4FA` | `#0A6FB8` |
| `up` | `#33C48D` | `#0B7450` |
| `down` | `#F2606B` | `#C42F3C` |
| `up-alt` / `down-alt` | `#5AA9FF` / `#FF9F43` | `#1F63C6` / `#A84800` |
| `warn` | `#F2B33D` | `#8F5A00` |
| `info` | `#7CC4FA` | `#0A6FB8` |
| `live` | `#E35DD3` | `#A3168E` |
| `agent` | same as `info` | same as `info` |
| `series-1..5` | `#3987E5` `#D95926` `#9085E9` `#C98500` `#D55181` | `#2A78D6` `#EB6834` `#6250D6` `#EDA100` `#E87BA4` |

**Type.** IBM Plex Sans for interface and headings (400, 500, 600), IBM Plex Mono for numbers (400, 500). Base
13px; scale 11, 12, 13, 15, 18, 22, 28; line height 1.4.

**Shape and density.** Radius 2px on controls, 4px on cards. Surfaces separated by 1px borders, no shadows. Table
rows and controls 28px high, card padding 12px, gutter 12px.

**Signature details.** Section labels in 11px uppercase `text-muted` with wide letter spacing. Active navigation
marked by a 2px `accent` bar, not a fill. Sparklines inside table rows. 6px status dots.

**Voice.** Terse, like an instrument readout: "Order rejected. Quantity 0.00008 BTC is below the minimum of
0.0001 BTC."

### B2. Harbor: calm oversight

**Idea.** A safe harbor. Calm, explanatory, risk first. Light by default, with a full dark mode. Navy ink, teal
accent, generous space, every number with its context.

**Suits** an operator who checks in a few times a day, wants to understand rather than stare, and shares reports.

**Personality:** calm, clear, trustworthy.

| Token | Light (default) | Dark |
|-------|------------------|------|
| `bg` | `#F5F7FA` | `#0B1422` |
| `surface` | `#FFFFFF` | `#111D30` |
| `surface-2` | `#EDF1F6` | `#17263D` |
| `border` | `#D6DEE8` | `#24364F` |
| `border-strong` | `#8494A8` | `#566B88` |
| `text` | `#0F1B2D` | `#E8EEF6` |
| `text-muted` | `#46566B` | `#A3B2C6` |
| `text-subtle` | `#6B7A8F` | `#7A8BA2` |
| `accent` | `#0B7A7A` | `#2EC4B6` |
| `accent-contrast` | `#FFFFFF` | `#04211E` |
| `focus` | `#0B7A7A` | `#2EC4B6` |
| `up` | `#12784A` | `#3CCB8E` |
| `down` | `#B93A26` | `#F07A63` |
| `up-alt` / `down-alt` | `#1F63C6` / `#A84800` | `#5AA9FF` / `#FF9F43` |
| `warn` | `#8A5300` | `#F0B44C` |
| `info` | `#1D5FD1` | `#7AB0FF` |
| `live` | `#6D28D9` | `#A78BFA` |
| `agent` | same as `info` | same as `info` |
| `series-1..5` | `#2A78D6` `#EB6834` `#6250D6` `#EDA100` `#E87BA4` | `#3987E5` `#D95926` `#9085E9` `#C98500` `#D55181` |

**Type.** Manrope for headings (600, 700), Inter for interface (400, 500, 600), JetBrains Mono for numbers (400,
500). Base 14px; scale 12, 13, 14, 16, 20, 24, 32; line height 1.5.

**Shape and density.** Radius 8px on controls, 12px on cards. One soft shadow level for cards, one for dialogs
(shadow tokens, never inline values). Rows and controls 36px, card padding 20px, gutter 16 to 24px.

**Signature details.** Limit meters: a thin bar under every limited number showing how much of the limit is used
(daily loss, open risk, exposure). A context line under each headline number ("net of fees and slippage, last 30
days"). In every card, risk comes before return. Teal is rare: primary action and selection only.

**Voice.** Reassuring and explanatory: "Daily loss is 0.8% of the 2.0% limit. New entries continue."

### B3. Voltage: the quant lab

**Idea.** A modern lab where the agents are visible actors. Near-black, electric violet as the brand color, acid
lime reserved for agent activity. Geometric type, a schematic grid motif in empty states.

**Suits** showing the agents at work (briefs, drafts, the configurator) to an operator who wants a distinctive
tool.

**Personality:** sharp, modern, alive.

| Token | Dark (default) | Light (reports, print) |
|-------|----------------|-------------------------|
| `bg` | `#07070B` | `#F7F7FA` |
| `surface` | `#0F0F16` | `#FFFFFF` |
| `surface-2` | `#16161F` | `#F0F0F5` |
| `border` | `#262633` | `#E1E1EA` |
| `border-strong` | `#62627E` | `#8C8CA3` |
| `text` | `#EDEDF5` | `#101018` |
| `text-muted` | `#A1A1B5` | `#4C4C63` |
| `text-subtle` | `#74748B` | `#6E6E85` |
| `accent` | `#8F75FF` | `#5B3DF5` |
| `accent-contrast` | `#0B0716` | `#FFFFFF` |
| `focus` | `#9A82FF` | `#5B3DF5` |
| `up` | `#2FD3A7` | `#097558` |
| `down` | `#FF5470` | `#C8243F` |
| `up-alt` / `down-alt` | `#5AA9FF` / `#FF9F43` | `#1F63C6` / `#A84800` |
| `warn` | `#FFC857` | `#8A6100` |
| `info` | `#5CC8FF` | `#0A6CB3` |
| `live` | `#FF7A45` | `#C2410C` |
| `agent` | `#C6F432` | `#4D7A00` |
| `series-1..5` | `#9085E9` `#D95926` `#3987E5` `#C98500` `#D55181` | `#6250D6` `#EB6834` `#2A78D6` `#EDA100` `#E87BA4` |

**Type.** Space Grotesk for headings (500, 700), Geist for interface (400, 500, 600), Geist Mono for numbers (400,
500). Base 14px; scale 12, 13, 14, 16, 20, 26, 34; line height 1.45.

**Shape and density.** Radius 6px on controls, 10px on cards. 1px borders, no drop shadows; focus is a 3px glow
in `focus` at reduced opacity. Rows and controls 32px, card padding 16px, gutter 16px.

**Signature details.** Every agent-produced artifact carries a 2px `agent` rule on its left edge and an agent
chip ("Analyst"). An agent activity timeline on the dashboard. A 24px dotted grid behind empty states and the
configurator canvas only. Gradients are allowed on the product mark only, never on data.

**Voice.** Confident and exact: "Analyst: range regime on ETH/USDT, confidence 0.71, 3 sources."

### B4. Verified contrast

WCAG 2 contrast ratios on `surface` (text tokens need 4.5, `text-subtle` is limited to large text and
placeholders, `border-strong` needs 3.0):

| Identity, mode | text | text-muted | text-subtle | accent | label on accent | up | down | warn | border-strong |
|---|---|---|---|---|---|---|---|---|---|
| Graphite dark | 15.3 | 7.4 | 4.2 | 9.8 | 10.0 | 8.3 | 5.9 | 9.9 | 3.3 |
| Graphite light | 18.7 | 6.9 | 4.6 | 5.3 | 5.3 | 5.8 | 5.5 | 5.8 | 3.1 |
| Harbor light | 17.3 | 7.5 | 4.4 | 5.1 | 5.1 | 5.5 | 5.7 | 6.3 | 3.1 |
| Harbor dark | 14.5 | 7.8 | 4.9 | 7.8 | 7.8 | 8.2 | 6.2 | 9.1 | 3.1 |
| Voltage dark | 16.4 | 7.5 | 4.2 | 5.6 | 5.8 | 10.0 | 6.1 | 12.4 | 3.2 |
| Voltage light | 18.9 | 8.3 | 5.0 | 6.1 | 6.1 | 5.7 | 5.6 | 5.5 | 3.3 |

`text`, `text-muted`, `accent`, `up`, `down`, `up-alt`, `down-alt`, `warn`, `info`, `live` and `agent` also meet
4.5:1 on `surface-2` and `bg`. Series palettes, both modes: all slots inside the
lightness band and above the chroma floor; worst adjacent pair under simulated deuteranopia ΔE 13.2 (dark) and
16.3 (light), normal-vision floor 19.3 and 19.6. A Pest test will parse `tokens.css` and recheck these pairs on
every build (Phase 0).

### B5. How to choose

| If the priority is | Choose |
|--------------------|--------|
| Watching a lot at once, long sessions, a terminal feel | Graphite |
| Understanding and trust, occasional check-ins, sharing reports | Harbor |
| Making the agents visible, a distinctive modern tool | Voltage |

---

## Part C. Compliance checklist

Every pull request that touches UI answers each item; a "no" blocks the merge.

1. Only tokens: no color, font, size or radius literal outside `tokens.css`.
2. Built from existing components; a new component is added to A7 with all its states.
3. Numbers follow A2: monospace tabular, instrument precision, sign and zero form, units, right-aligned.
4. Direction is never color alone; the `up-alt` / `down-alt` setting still reads correctly.
5. The mode badge is visible; live-money actions use the confirmation dialog.
6. Charts follow A5: token colors, fixed series order, one axis, legend and table view.
7. Works at 1280, 1024, 768 and 360 wide; Halt reachable at every width.
8. Keyboard and screen reader: visible focus, labels, chart summaries.
9. Copy follows A10: no banned words, no emojis, no em dashes, costs and risk shown.
10. Every mode the identity supports (dark and light) was checked.
11. Screenshots at 1280 and 360 wide are attached to the pull request.
