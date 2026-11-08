# Assessment of the existing platform (November 2026)

This is a read-only review of the code as it stood at commit `e0ec48b` ("Added loop", April 2026). It decides what
survives the rebuild, what is rewritten and what is new. Every claim points at a file and line so a reviewer can
check it. The code itself was deleted in Phase 0 (D-25); `git show e0ec48b:<path>` shows any file cited here.

## 1. What the platform is today

```
RSS scraper -> SQLite -> mega-prompt -> `claude -p` (CLI subprocess) -> JSON strategy -> TradingBot thread
   -> broker (paper / Binance / Alpaca / XTB) -> SQLite -> Laravel Blade UI
```

- `agents/` (Python, ~5,500 lines): an orchestrator (`main.py`) that, per user, builds a context, asks the Claude
  CLI for a JSON strategy, and spawns one `TradingBot` thread that polls prices every 30 s, opens positions and
  closes them on stop-loss / take-profit.
- `laravel/` (Laravel 11, Blade + Tailwind CDN): auth, settings, strategy and trade views, scrapers, watchlist.
- Both sides share one SQLite or MySQL database. Laravel owns the migrations; Python writes raw SQL against the
  same tables by hand.
- No tests, no CI, no lint or type check anywhere in the repository.

The committed database shows how it behaved in practice: 37 strategies were generated on a single day
(2026-04-07), 70 of 98 planned trades were skipped, and the stored realized P&L of every trade is zero.

## 2. Findings that decide the rebuild

Ranked by how much money or safety they put at risk.

### 2.1 Risk controls exist in the settings but are not enforced

| # | Finding | Evidence |
|---|---------|----------|
| R1 | The daily loss limit never triggers. The bot reads `constraints["max_daily_loss"]`; the orchestrator only ever sets `max_daily_loss_pct`, so the check returns early every tick. | `agents/bots/trading_bot.py:721`, `agents/main.py:402` |
| R2 | Position size is whatever number the LLM writes in `quantity`. `max_position_pct` is copied onto the trade as `_max_position_pct` and never read again. | `agents/claude/strategy_parser.py:243` |
| R3 | Stop-loss and take-profit live only in the bot's memory and are checked every 30 s. Nothing is placed on the exchange. If the process stops, open positions are unprotected; after a restart `_open_positions` is empty, so they are orphaned, and the resumed bot can enter the same specs again. | `agents/bots/trading_bot.py:57`, `agents/main.py` resume block |
| R4 | Any broker connection error silently falls back to the paper broker. The operator cannot tell whether the bot trades real or simulated money. | `agents/main.py:99-170` |
| R5 | "Short" on Binance spot is a plain sell. Spot has no shorting: it either sells holdings the user owns or is rejected. | `agents/bots/trading_bot.py` `_open_position` |
| R6 | The "loop" commit makes the bot ask for a new strategy as soon as the current one finishes, with no budget, cooldown or daily cap on LLM calls or trades. | `agents/bots/trading_bot.py` `_check_all_trades_complete` |
| R7 | Binance orders are sent without rounding to the symbol filters (`LOT_SIZE`, `PRICE_FILTER`, `NOTIONAL`), so many are rejected. | `agents/brokers/binance_broker.py` (no filter handling) |

### 2.2 The edge was negative by construction

| # | Finding | Evidence |
|---|---------|----------|
| E1 | Technical indicators never reach the model. The orchestrator imports `sma, rsi, macd, bollinger_bands`; the module defines `SMA, RSI, MACD, BollingerBands`. The `ImportError` is swallowed by `except Exception: pass`. | `agents/main.py:239`, `agents/indicators/technical.py:14-76` |
| E2 | The only market data in the prompt is the last 30 daily candles, while the default style is scalping with 0.1-0.3 % targets. There is no intraday data, order book, spread, funding or volatility measure. | `agents/main.py` `_gather_context`, `agents/claude/prompt_builder.py:101` |
| E3 | Fees are ignored. A 0.1-0.3 % scalping target against a 0.1 % taker fee per side (0.2 % round trip) loses on average even with a perfect 50 % hit rate. The paper broker charges no fee and no slippage, so paper results look better than reality. | `agents/brokers/paper.py:443` (`fee: 0`) |
| E4 | News sentiment is TextBlob plus a 40-word dictionary. It cannot tell "BTC ETF approved" from "BTC ETF rejected" with any reliability. | `agents/scrapers/runner.py:233-251`, `agents/data/financial_words.json` |
| E5 | There is no backtest, no paper-versus-live comparison and no baseline (buy and hold, a simple trend rule). Nobody can say whether a strategy ever had an edge. | whole repository |

### 2.3 Bookkeeping is wrong

| # | Finding | Evidence |
|---|---------|----------|
| B1 | Money is stored as integer cents. That breaks for crypto: sub-cent prices, 8-decimal quantities, fees charged in the base asset. | every `*_cents` column |
| B2 | Fee import multiplies the commission by the price even when the commission is already in USDT. The committed data shows about 210,000 USD of "fees" on 19 BTC sells. | `agents/main.py:611` |
| B3 | Realized P&L is never written (`pnl_cents` is 0 on every trade), so the dashboard cannot show what the strategies earned. | `agents/brokers/paper.py`, `agents/main.py` trade sync |
| B4 | The portfolio's initial balance (100,000) is a paper default, while the synced cash comes from the Binance demo account, so any return computed from them is meaningless. | `portfolios` table |
| B5 | Times are mixed: the scheduler compares UTC clock strings with "12:00" and "16:00" that were meant as New York market hours, and closes every crypto position at 16:00 UTC although crypto trades around the clock. | `agents/main.py` main loop, `_load_user_configs` |

### 2.4 Code health

| # | Finding | Evidence |
|---|---------|----------|
| C1 | `NameError` on the variable `action` in the "WAITING" log branch aborts the whole tick (alerts, risk check, heartbeat) the first time each symbol waits for its entry. | `agents/bots/trading_bot.py:291-294` |
| C2 | A bot restarted by the health monitor loses its DB session and analysis id, so it can no longer record executions. | `agents/bots/bot_manager.py` `_monitor_loop` |
| C3 | SQLite-only SQL (`date('now')`, `INSERT OR IGNORE`) although MySQL is advertised. | `agents/bots/trading_bot.py`, `agents/main.py` |
| C4 | The prompt goes to the CLI as one argv string: size-limited by the OS, visible in the process list, no structured output, no tool use, no token or cost accounting. Prices are requested "in cents", which invites arithmetic slips by the model. | `agents/claude/engine.py:47`, `agents/claude/prompt_builder.py:353` |
| C5 | XTB switched its xAPI off on 14 March 2025; `brokers/xtb.py` and `market_data/xtb_data.py` cannot work. `alpaca-trade-api` is deprecated in favour of `alpaca-py`. | `agents/brokers/xtb.py:21` |
| C6 | 50 broad `except Exception` handlers, 11 of them a bare `pass`, hide failures like E1. | `grep -rn "except Exception" agents` |
| C7 | `laravel/database/database.sqlite` and its WAL files are committed. They hold user data and must leave the repository. | `laravel/.gitignore` |

## 3. Laravel side

### 3.1 Security

| # | Finding | Evidence |
|---|---------|----------|
| S1 | Broker credentials and notification tokens are stored as plain JSON (`array` cast), although the wiki and the flash message say they are encrypted. Python can read them only because they are not. | `laravel/app/Models/BrokerCredential.php:24`, `wiki/architecture.md:375` |
| S2 | Command injection: the watchlist backfill builds a shell string from the user-supplied symbol (validated only as `string|max:10`) and runs it through `Process::run`. Any registered user reaches it. | `laravel/app/Http/Controllers/WatchlistController.php:74-75` |
| S3 | SSRF: "test scraper" fetches any URL (localhost, LAN, cloud metadata) and reports the result; the Discord webhook URL is POSTed as given and the response body is shown back. | `ScraperController.php:155`, `NotificationService.php:114` |
| S4 | Open registration, no login throttling, no password reset; the start script re-seeds `demo@example.com` / `password` and serves on `0.0.0.0`; `.env.example` has `APP_DEBUG=true`. | `routes/web.php:117`, `start.sh:40,49` |
| S5 | Tailwind Play CDN and an unpinned Chart.js from jsDelivr, without SRI. | `layouts/app.blade.php:7-8` |

No IDOR was found: every query is scoped by the authenticated user. No unescaped Blade output was found.

### 3.2 Plumbing that does not run

- The Redis bridge is dead. The three jobs (`ProcessRedisMessage`, `SendAgentCommand`, `SendNotification`) are
  never dispatched and nothing subscribes on the Laravel side. Laravel publishes through a client that prefixes the
  channel (`config/database.php:55`), so Python, listening on `trading:commands`, likely never hears it. "Run
  analysis" works only because Python polls the database for `status = 'pending'`.
- The Laravel scheduler is never started (no `schedule:work`, no cron), so `routes/console.php` never runs.
- Notification rules have no UI and are deleted on every settings save, so only the "Test" button sends anything.
- Nothing writes `portfolio_snapshots`, so the performance page and daily summary are always empty.
- Dates in `claude_analyses.date` are written in two formats (Python `YYYY-MM-DD`, Laravel
  `YYYY-MM-DD 00:00:00`), so equality filters on "today" miss rows.

### 3.3 UI as it exists

Dark only, system font, no monospace numerals, hard-coded hex colors with no theme tokens (`#0f1117` background,
`#1a1d29` cards, `#2a2d3a` borders, `#3b82f6` accent, `#22c55e` / `#ef4444` up and down). A reasonable component
set (card, stat-card, badge, button, form inputs, data table) but 26 raw inputs and 10 raw tables copy classes
inline. Charts: Chart.js and TradingView Lightweight Charts 4.1, daily bars only. Pages refresh with
`<meta http-equiv="refresh" content="60">`; there are no live updates.

The screen inventory (dashboard, strategies, planned trades, trade chart, watchlist chart, portfolio, logs,
settings, scrapers) is a useful checklist for the new UI. The code and the look are not kept: the new UI follows
`docs/brand.md`.

## 4. Verdict per area

| Area | Verdict | Why |
|------|---------|-----|
| Orchestrator `agents/main.py` | Rewrite | One 1,100-line module mixing scheduling, SQL, sync and broker setup; no seams for tests |
| `TradingBot` | Rewrite as an execution engine | In-memory state, client-side stops, no reconciliation (R1-R7) |
| `ClaudeEngine` + `PromptBuilder` | Replace | Claude API with tool use and structured output instead of a CLI mega-prompt; agents fetch the data they need |
| `StrategyParser` | Replace | A typed strategy schema (Strategy DSL) validated before anything runs |
| Strategy JSON fields (entry condition, SL/TP %, re-entry, max entries) | Keep the ideas | They become part of the DSL |
| `BaseBroker` interface | Keep the idea | Re-implemented over `ccxt` with exchange filters, protective orders and reconciliation |
| Binance broker | Replace | `ccxt` adapter; spot and USD-M perpetuals |
| Alpaca broker | Defer | Re-implement on `alpaca-py` only if equities are in scope |
| XTB broker and data | Drop | API shut down by XTB |
| Paper broker | Rewrite | Becomes the simulator: fees, slippage, latency, partial fills, funding |
| Market data sources (yfinance, Alpha Vantage, Finnhub, Twelve Data) | Replace | Exchange data through `ccxt` and WebSockets; equity providers only if equities are in scope |
| Indicators | Rewrite | In PHP, with tests; the current pandas formulas serve as test oracles |
| RSS scraper | Keep the idea | Becomes one connector type in the Sources registry |
| HTML scraper | Drop | Fragile and often against site terms; replaced by APIs and feeds |
| Sentiment (TextBlob) | Drop | Replaced by LLM event extraction with a fixed schema |
| Shared DB with hand-written SQL on both sides | Replace | One application owns the schema (D-2) |
| Redis pub/sub | Replace | Laravel queues (Horizon), a Redis stream for ticks, Reverb for the browser; pub/sub loses messages when nobody listens |
| Prompt and response logging | Keep the idea | Becomes the decision journal: every agent input, tool call, output, cost and outcome |
| Notifications (Telegram, Discord, email) | Keep the idea | Re-implemented behind the new event bus |
| Setup scripts, `start*.sh`, `wiki/` | Replace | `docker compose`, one task runner, and `docs/` |
| Laravel app (auth, controllers, Blade views) | Rebuild | A new Laravel application (D-2, D-3); fixing S1-S5 and the dead plumbing in 3.2 costs more than starting clean; the screen inventory is kept as a checklist |
| Laravel schema | Replace | One owner for the schema; decimals instead of cents; one timestamp format (UTC, with zone) |
| Committed `database.sqlite` and `bootstrap/cache` | Remove from the repository | User data in a public repository |

## 5. Lessons the new design must carry

1. **The model proposes, deterministic code disposes.** An LLM never sends an order. It writes or selects a
   strategy in a typed schema; a risk engine sizes and limits it; an execution engine places it.
2. **One code path for backtest, paper and live.** A strategy that only exists as a prompt cannot be tested.
3. **Risk limits are code with tests, not settings.** R1 and R2 were settings that nothing read.
4. **The exchange is the source of truth.** Protective orders sit on the exchange; local state is reconciled from it.
5. **Fail loud.** No silent fallback from live to paper, no swallowed import errors.
6. **Costs first.** Fees, spread, slippage and funding are part of every simulation from day one.
7. **No edge, no trade.** Staying flat is a valid output of every agent.

## 6. Decisions taken

The owner answered the open questions on 2026-11-08. They are recorded in `docs/decisions.md`: all PHP on Laravel
(D-2), Livewire UI (D-3), crypto on Binance with spot first and perpetuals behind a flag (D-4), one operator
(D-5), the Claude API (D-6), our own engine (D-9). The visual identity is still open (D-24).
