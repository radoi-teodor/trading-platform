# Architecture

## System Overview

```
+----------------------------------------------------------+
|                    Ubuntu Server                          |
|                                                          |
|  +-----------------------+    +------------------------+ |
|  |    Laravel 11 App     |    |    Python Agents       | |
|  |    (PHP 8.3)          |    |    (Python 3.11+)      | |
|  |                       |    |                        | |
|  |  - Web UI (Blade)     |    |  - ScraperRunner       | |
|  |  - Auth / Sessions    |    |  - ClaudeEngine        | |
|  |  - Queue Workers      |    |  - BotManager          | |
|  |  - Artisan Commands   |    |  - TradingBot          | |
|  |  - Notification Svc   |    |  - Market Data         | |
|  +---------+---+---------+    +----+---+---+-----------+ |
|            |   |                   |   |   |             |
|            v   v                   v   v   |             |
|  +---------+---+---------+    +----+---+---+--+          |
|  |  MySQL / SQLite       |    |  Redis        |          |
|  |  (shared database)    |    |  (pub/sub +   |          |
|  |                       |    |   cache)      |          |
|  +-----------------------+    +---------------+          |
|                                                          |
|  +----------------------------------------------------+  |
|  |  Claude Code CLI Binary                            |  |
|  |  (invoked as subprocess by Python agents)          |  |
|  +----------------------------------------------------+  |
|                                                          |
|  +----------------------------------------------------+  |
|  |  External Services                                 |  |
|  |  - Broker APIs (Alpaca, Binance, XTB)              |  |
|  |  - Market Data (Alpha Vantage, yfinance, Finnhub,  |  |
|  |    Twelve Data)                                    |  |
|  |  - Notifications (Telegram, Discord, SMTP)         |  |
|  +----------------------------------------------------+  |
+----------------------------------------------------------+
```

## Daily Cycle

The platform operates on a five-phase daily cycle:

```
06:00         08:00          09:30-16:00       16:30         20:00
  |             |                |               |             |
SCRAPE ----> ANALYZE -------> EXECUTE -------> REPORT -----> REVIEW
  |             |                |               |             |
Collect      Claude CLI       Bots place      Send daily    Claude reviews
news &       processes        orders via      summary via   EOD performance
market       data, outputs    configured      Email /       and adjusts
data         strategy JSON    broker API      Telegram /    strategy for
from RSS/                                    Discord       next session
HTML sources
```

### Phase 1: SCRAPE

- `ScraperRunner` iterates over all active `Scraper` records from the database
- RSS scrapers parse XML feeds; HTML scrapers use CSS selectors on web pages
- Each article is saved to `scraped_articles` with extracted symbols and sentiment score
- Runs every 30 minutes in the background thread

### Phase 2: ANALYZE

- `ClaudeEngine` builds a mega-prompt via `PromptBuilder.build_daily_analysis()`
- Context includes: portfolio state, recent news with sentiment, market prices, technical indicators, user risk settings, custom instructions
- Claude CLI is invoked as a subprocess: `claude -p "<prompt>" --output-format json`
- Response is parsed by `StrategyParser` into structured trades, closes, and alerts
- Everything is logged to `claude_analyses` + child tables

### Phase 3: EXECUTE

- `BotManager` reads the latest `ClaudeAnalysis` with its planned trades
- `TradingBot` sends orders to the configured broker (Paper, Alpaca, Binance, or XTB)
- Order results are logged to `orders` and `trades` tables
- Position tracking updated in `positions` table

### Phase 4: REPORT

- Laravel `SendNotification` job fires after trade execution
- `NotificationService` dispatches to all configured channels (Email, Telegram, Discord)
- Daily summary includes: trades executed, P&L, portfolio value, Claude's reasoning
- `DailySummary` Artisan command can also be triggered manually

### Phase 5: REVIEW

- End-of-day analysis via Claude CLI reviews actual vs. expected outcomes
- Performance data stored in `portfolio_snapshots`
- Insights feed back into the next day's analysis context


## Component Breakdown

### Laravel Application (`laravel/`)

| Component | Path | Purpose |
|-----------|------|---------|
| Controllers | `app/Http/Controllers/` | 13 controllers handling web UI routes |
| Models | `app/Models/` | 22 Eloquent models mapping to database tables |
| Jobs | `app/Jobs/` | 3 queue jobs (ProcessRedisMessage, SendAgentCommand, SendNotification) |
| Services | `app/Services/` | NotificationService for multi-channel dispatch |
| Commands | `app/Console/Commands/` | 7 Artisan commands for CLI operations |
| Views | `resources/views/` | Blade templates with Tailwind CSS via CDN |
| Routes | `routes/web.php` | All web routes (auth, dashboard, strategies, portfolio, etc.) |
| Migrations | `database/migrations/` | 25 migration files defining the full schema |

### Python Agents (`agents/`)

| Component | Path | Purpose |
|-----------|------|---------|
| Main Orchestrator | `main.py` | Entry point; coordinates all agent threads |
| Claude Engine | `claude/engine.py` | Wraps Claude CLI subprocess calls |
| Prompt Builder | `claude/prompt_builder.py` | Constructs analysis prompts with full context |
| Strategy Parser | `claude/strategy_parser.py` | Parses Claude's JSON responses into actionable trades |
| Bot Manager | `bots/bot_manager.py` | Manages trading bot lifecycle |
| Trading Bot | `bots/trading_bot.py` | Executes trades through broker API |
| Scraper Runner | `scrapers/runner.py` | Schedules and runs all active scrapers |
| RSS Scraper | `scrapers/rss_scraper.py` | Parses RSS/Atom feeds |
| HTML Scraper | `scrapers/html_scraper.py` | Scrapes HTML pages with CSS selectors |
| Broker Base | `brokers/base.py` | ABC defining broker interface (BaseBroker) |
| Paper Broker | `brokers/paper.py` | Simulated trading for testing |
| Alpaca Broker | `brokers/alpaca.py` | Alpaca Markets API integration |
| Binance Broker | `brokers/binance_broker.py` | Binance exchange integration |
| XTB Broker | `brokers/xtb.py` | XTB trading platform integration |
| Market Data Base | `market_data/base.py` | ABC defining market data interface |
| yfinance | `market_data/yfinance_source.py` | Yahoo Finance data (free, no key) |
| Alpha Vantage | `market_data/alpha_vantage.py` | Alpha Vantage API |
| Finnhub | `market_data/finnhub_source.py` | Finnhub API |
| Twelve Data | `market_data/twelvedata_source.py` | Twelve Data API |
| Config | `config.py` | Reads .env and exposes typed config values |


## Data Flow

### Scraper Data to Claude

```
RSS/HTML Sources
      |
      v
ScraperRunner.run_all()
      |
      v
BaseScraper.fetch() --> BaseScraper.parse() --> Article dataclass
      |
      v
INSERT into scraped_articles (title, content, url, source, symbols, sentiment_score)
      |
      v
PromptBuilder.build_daily_analysis() reads recent articles
      |
      v
Mega-prompt includes news headlines, sentiment scores, mentioned symbols
      |
      v
ClaudeEngine.ask(prompt) --> subprocess: claude -p "<prompt>" --output-format json
      |
      v
Claude CLI returns JSON strategy
```

### Claude Strategy to Trade Execution

```
Claude CLI JSON response
      |
      v
StrategyParser.parse(response) --> dict with trades, closes, alerts
      |
      v
INSERT into claude_analyses (prompt, response, parsed data)
INSERT into claude_analysis_trades (symbol, action, quantity, reasoning)
INSERT into claude_analysis_closes (symbol, reasoning)
INSERT into claude_analysis_alerts (symbol, message, severity)
      |
      v
BotManager reads latest analysis
      |
      v
TradingBot.execute_trades(analysis)
      |
      v
BaseBroker.place_order(symbol, side, quantity, order_type)
      |
      v
OrderResult --> INSERT into orders, trades
Position updates --> UPDATE positions
      |
      v
Redis PUBLISH 'trading:notifications' {trade_summary}
      |
      v
Laravel ProcessRedisMessage job --> SendNotification job
      |
      v
NotificationService dispatches to Email / Telegram / Discord
```


## Communication: Laravel and Python

Laravel and the Python agents communicate through two shared channels:

### 1. Shared Database (MySQL/SQLite)

Both Laravel (via Eloquent ORM) and Python (via SQLAlchemy) read/write to the same database. Key shared tables:

| Table | Written by | Read by | Purpose |
|-------|-----------|---------|---------|
| `scrapers` | Laravel | Python | Scraper configuration |
| `scraped_articles` | Python | Laravel | Scraped news data |
| `claude_analyses` | Python | Laravel | Claude prompt/response logs |
| `claude_analysis_trades` | Python | Laravel | Planned trades from Claude |
| `orders` | Python | Laravel | Placed order records |
| `trades` | Python | Laravel | Filled trade records |
| `positions` | Python | Laravel | Current portfolio positions |
| `portfolio_snapshots` | Python | Laravel | Historical portfolio values |
| `user_settings` | Laravel | Python | User preferences, risk tolerance |
| `broker_credentials` | Laravel | Python | Encrypted broker API keys |
| `bot_logs` | Python | Laravel | Agent activity logs |
| `agent_health` | Python | Laravel | Agent heartbeat/status |

### 2. Redis Pub/Sub

Real-time commands and events flow through Redis channels:

| Channel | Publisher | Subscriber | Messages |
|---------|-----------|------------|----------|
| `trading:commands` | Laravel | Python | `trigger_analysis`, `update_config`, `restart_scraper` |
| `trading:notifications` | Python | Laravel | Trade executed, analysis complete, error alerts |
| `trading:health` | Python | Laravel | Heartbeat, status updates |

Message format (JSON):

```json
{
  "type": "trigger_analysis",
  "user_id": 1,
  "payload": {
    "symbols": ["AAPL", "MSFT"],
    "reason": "manual_trigger"
  },
  "timestamp": "2026-04-06T10:30:00Z"
}
```


## Database Schema Overview

25 tables organized by domain:

### Authentication & Users

| Table | Key Columns | Purpose |
|-------|-------------|---------|
| `users` | id, name, email, password | User accounts |
| `sessions` | id, user_id, ip_address, payload | Session management |
| `user_settings` | id, user_id, key, value | Key-value settings per user |

### Portfolio & Trading

| Table | Key Columns | Purpose |
|-------|-------------|---------|
| `portfolios` | id, user_id, name, balance, currency | Portfolio containers |
| `positions` | id, portfolio_id, symbol, quantity, avg_cost, side | Open positions |
| `orders` | id, portfolio_id, symbol, side, quantity, type, status | Order records |
| `trades` | id, order_id, symbol, side, quantity, price, fees | Executed fills |
| `portfolio_snapshots` | id, portfolio_id, equity, date | Daily equity snapshots |

### Claude AI Analysis

| Table | Key Columns | Purpose |
|-------|-------------|---------|
| `claude_analyses` | id, user_id, date, prompt, response, status | Full prompt/response log |
| `claude_analysis_trades` | id, analysis_id, symbol, action, quantity, reasoning | Planned trades |
| `claude_analysis_closes` | id, analysis_id, symbol, reasoning | Planned position closes |
| `claude_analysis_alerts` | id, analysis_id, symbol, message, severity | Market alerts |

### Scrapers & News

| Table | Key Columns | Purpose |
|-------|-------------|---------|
| `scrapers` | id, user_id, name, type, url, config, is_active | Scraper definitions |
| `scraped_articles` | id, scraper_id, title, content, url, sentiment_score | Scraped data |
| `stock_prices` | id, symbol, open, high, low, close, volume, date | Cached market data |

### Watchlist

| Table | Key Columns | Purpose |
|-------|-------------|---------|
| `watchlists` | id, user_id, name | Named watchlists |
| `watchlist_items` | id, watchlist_id, symbol | Symbols in watchlist |

### Notifications

| Table | Key Columns | Purpose |
|-------|-------------|---------|
| `notification_channels` | id, user_id, type, config, is_active | Channel configurations |
| `notification_rules` | id, user_id, event_type, channel_id | Which events trigger which channels |
| `notification_log` | id, channel_id, subject, body, status, sent_at | Delivery log |

### Broker & Infrastructure

| Table | Key Columns | Purpose |
|-------|-------------|---------|
| `broker_credentials` | id, user_id, broker_type, credentials (encrypted) | Encrypted API keys |
| `bot_logs` | id, level, message, context, created_at | Python agent logs |
| `agent_health` | id, agent_name, status, last_heartbeat | Agent monitoring |
| `cache` | key, value, expiration | Laravel cache store |
| `jobs` | id, queue, payload, attempts, reserved_at | Laravel queue jobs |


## Queue System

Laravel uses the **database** queue driver with three job types:

| Job | Queue | Purpose | Retry |
|-----|-------|---------|-------|
| `ProcessRedisMessage` | `default` | Handles incoming Redis pub/sub messages from Python | 3 attempts |
| `SendAgentCommand` | `default` | Publishes commands to Python via Redis | 3 attempts |
| `SendNotification` | `notifications` | Dispatches notifications to Email/Telegram/Discord | 3 attempts |

Queue workers are started with:

```bash
php artisan queue:work --tries=3 --timeout=120
```

The `jobs` table stores pending jobs; failed jobs go to `failed_jobs` for later inspection.


## Notification Pipeline

```
Trade Executed / Analysis Complete / Alert
      |
      v
Redis PUBLISH 'trading:notifications'
      |
      v
Laravel ProcessRedisMessage job (queue worker)
      |
      v
SendNotification job created
      |
      v
NotificationService reads user's active notification_channels
      |
      +---> Email (SMTP via Laravel Mail)
      |
      +---> Telegram (Bot API HTTP POST)
      |
      +---> Discord (Webhook HTTP POST)
      |
      v
notification_log entry created (status: sent/failed)
```

### Channel Configuration

Each channel stores its config as encrypted JSON in `notification_channels.config`:

- **Email:** `{"address": "user@example.com"}`
- **Telegram:** `{"bot_token": "...", "chat_id": "..."}`
- **Discord:** `{"webhook_url": "https://discord.com/api/webhooks/..."}`


## Security

### Broker Credential Encryption

Broker API keys and secrets are stored in the `broker_credentials` table with Laravel's built-in encryption (`encrypt()` / `decrypt()`). The `APP_KEY` in `.env` serves as the encryption key. Credentials are only decrypted in memory when the Python agents need to initialize a broker connection.

### Authentication

- Session-based authentication via Laravel's built-in auth system
- CSRF token protection on all POST/PUT/DELETE routes
- Password hashing with bcrypt
- Session stored in database (`sessions` table)

### Network Security

- The Laravel app and Python agents communicate only through localhost (Redis, database)
- No external API endpoints are exposed; the web UI is the sole entry point
- Broker API keys are never logged or displayed in the UI after initial setup
- Claude CLI runs locally as a subprocess; no data leaves the server except to broker/data APIs
