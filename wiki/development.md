# Development Guide

## Project Structure

```
trading-platform/
├── start.sh                    # One-command startup script
├── docker-compose.yml          # Docker setup
├── .env                        # Environment variables (shared)
│
├── laravel/                    # Laravel 11 application
│   ├── app/
│   │   ├── Console/
│   │   │   └── Commands/
│   │   │       ├── AgentsHealth.php        # Check agent heartbeats
│   │   │       ├── BackfillMarketData.php  # Backfill historical prices
│   │   │       ├── CleanLogs.php           # Purge old log entries
│   │   │       ├── DailySummary.php        # Send daily summary notification
│   │   │       ├── ManageSymbols.php       # Add/remove tracked symbols
│   │   │       ├── SetupTelegram.php       # Helper for Telegram bot setup
│   │   │       └── TriggerAnalysis.php     # Manually trigger Claude analysis
│   │   ├── Http/
│   │   │   └── Controllers/
│   │   │       ├── AuthController.php
│   │   │       ├── BrokerSettingsController.php
│   │   │       ├── ClaudeStrategyController.php
│   │   │       ├── DashboardController.php
│   │   │       ├── LogController.php
│   │   │       ├── NewsController.php
│   │   │       ├── NotificationSettingsController.php
│   │   │       ├── PortfolioController.php
│   │   │       ├── ProfileController.php
│   │   │       ├── ScraperController.php
│   │   │       ├── StrategySettingsController.php
│   │   │       └── WatchlistController.php
│   │   ├── Jobs/
│   │   │   ├── ProcessRedisMessage.php     # Handle incoming Redis messages
│   │   │   ├── SendAgentCommand.php        # Publish commands to Python agents
│   │   │   └── SendNotification.php        # Dispatch notifications
│   │   ├── Mail/                           # Mailable classes
│   │   ├── Models/
│   │   │   ├── AgentHealth.php
│   │   │   ├── BotLog.php
│   │   │   ├── BrokerCredential.php
│   │   │   ├── ClaudeAnalysis.php
│   │   │   ├── ClaudeAnalysisAlert.php
│   │   │   ├── ClaudeAnalysisClose.php
│   │   │   ├── ClaudeAnalysisTrade.php
│   │   │   ├── NotificationChannel.php
│   │   │   ├── NotificationLog.php
│   │   │   ├── NotificationRule.php
│   │   │   ├── Order.php
│   │   │   ├── Portfolio.php
│   │   │   ├── PortfolioSnapshot.php
│   │   │   ├── Position.php
│   │   │   ├── ScrapedArticle.php
│   │   │   ├── Scraper.php
│   │   │   ├── StockPrice.php
│   │   │   ├── Trade.php
│   │   │   ├── User.php
│   │   │   ├── UserSetting.php
│   │   │   ├── Watchlist.php
│   │   │   └── WatchlistItem.php
│   │   ├── Providers/
│   │   └── Services/
│   │       └── NotificationService.php     # Multi-channel notification dispatch
│   ├── bootstrap/
│   ├── config/
│   ├── database/
│   │   └── migrations/                     # 25 migration files
│   ├── public/
│   ├── resources/
│   │   └── views/                          # Blade templates (Tailwind via CDN)
│   ├── routes/
│   │   └── web.php                         # All web routes
│   ├── storage/
│   ├── tests/
│   ├── artisan
│   └── composer.json
│
├── agents/                     # Python 3.11+ agents
│   ├── __init__.py
│   ├── main.py                 # Main orchestrator (entry point)
│   ├── config.py               # Reads .env, exposes Config class
│   ├── requirements.txt        # Python dependencies
│   ├── claude/
│   │   ├── __init__.py
│   │   ├── engine.py           # Claude CLI subprocess wrapper
│   │   ├── prompt_builder.py   # Builds analysis prompts
│   │   └── strategy_parser.py  # Parses Claude JSON responses
│   ├── bots/
│   │   ├── __init__.py
│   │   ├── bot_manager.py      # Manages trading bot lifecycle
│   │   └── trading_bot.py      # Executes trades via broker
│   ├── brokers/
│   │   ├── __init__.py
│   │   ├── base.py             # BaseBroker ABC
│   │   ├── paper.py            # Paper trading (simulated)
│   │   ├── alpaca.py           # Alpaca Markets
│   │   ├── binance_broker.py   # Binance exchange
│   │   └── xtb.py              # XTB platform
│   ├── market_data/
│   │   ├── __init__.py
│   │   ├── base.py             # BaseMarketData ABC
│   │   ├── yfinance_source.py  # Yahoo Finance (default)
│   │   ├── alpha_vantage.py    # Alpha Vantage
│   │   ├── finnhub_source.py   # Finnhub
│   │   ├── twelvedata_source.py # Twelve Data
│   │   ├── binance_data.py     # Binance market data
│   │   └── xtb_data.py         # XTB market data
│   ├── scrapers/
│   │   ├── __init__.py
│   │   ├── base.py             # BaseScraper ABC
│   │   ├── rss_scraper.py      # RSS/Atom feed parser
│   │   ├── html_scraper.py     # HTML scraper with CSS selectors
│   │   └── runner.py           # Schedules and runs all scrapers
│   ├── indicators/             # Technical analysis indicators
│   └── data/                   # Static data files
│
└── wiki/                       # This documentation
```


## Adding a New Broker

All brokers implement the `BaseBroker` ABC defined in `agents/brokers/base.py`.

### Step 1: Create the broker file

Create `agents/brokers/my_broker.py`:

```python
from __future__ import annotations

from .base import AccountInfo, BaseBroker, OrderResult, Position


class MyBroker(BaseBroker):
    """Integration with MyBroker API."""

    def __init__(self, api_key: str, secret: str, demo: bool = True) -> None:
        self.api_key = api_key
        self.secret = secret
        self.demo = demo
        self._client = None

    def connect(self) -> None:
        # Initialize API client
        self._client = ...

    def disconnect(self) -> None:
        if self._client:
            self._client.close()

    def get_account(self) -> AccountInfo:
        data = self._client.get_account()
        return AccountInfo(
            equity=data["equity"],
            cash=data["cash"],
            buying_power=data["buying_power"],
            currency=data.get("currency", "USD"),
            raw=data,
        )

    def get_positions(self) -> list[Position]:
        positions = self._client.list_positions()
        return [
            Position(
                symbol=p["symbol"],
                quantity=p["qty"],
                avg_cost=p["avg_entry"],
                current_price=p["current_price"],
                unrealized_pnl=p["unrealized_pnl"],
            )
            for p in positions
        ]

    def place_order(
        self,
        symbol: str,
        side: str,
        quantity: float,
        order_type: str = "market",
        limit_price: float | None = None,
        stop_price: float | None = None,
    ) -> OrderResult:
        result = self._client.submit_order(
            symbol=symbol,
            side=side,
            qty=quantity,
            type=order_type,
        )
        return OrderResult(
            order_id=result["id"],
            symbol=symbol,
            side=side,
            quantity=quantity,
            order_type=order_type,
            status=result["status"],
            fill_price=result.get("fill_price"),
            raw=result,
        )

    def cancel_order(self, order_id: str) -> bool:
        return self._client.cancel(order_id)

    def get_order_status(self, order_id: str) -> OrderResult:
        data = self._client.get_order(order_id)
        return OrderResult(
            order_id=data["id"],
            symbol=data["symbol"],
            side=data["side"],
            quantity=data["qty"],
            order_type=data["type"],
            status=data["status"],
            fill_price=data.get("fill_price"),
            raw=data,
        )
```

### Step 2: Register in the broker factory

Edit `agents/main.py` and add your broker to the `_create_broker()` function:

```python
from .brokers.my_broker import MyBroker

def _create_broker(user_cfg, db):
    broker_type = user_cfg.get("broker_type", "paper")

    # ... existing brokers ...

    elif broker_type == "my_broker":
        broker = MyBroker(
            api_key=user_cfg.get("api_key"),
            secret=user_cfg.get("secret"),
            demo=user_cfg.get("demo", True),
        )
```

### Step 3: Add env variables

Add to `agents/config.py`:

```python
MY_BROKER_API_KEY: str = _env("MY_BROKER_API_KEY")
MY_BROKER_SECRET: str = _env("MY_BROKER_SECRET")
MY_BROKER_DEMO: bool = _env_bool("MY_BROKER_DEMO", True)
```

### Step 4: Add UI support

Add broker option to `BrokerSettingsController.php` and the corresponding Blade view.


## Adding a New Market Data Source

All sources implement `BaseMarketData` from `agents/market_data/base.py`.

### Step 1: Create the source file

Create `agents/market_data/my_source.py`:

```python
from __future__ import annotations

from datetime import datetime

from .base import BaseMarketData, OHLCV


class MySourceData(BaseMarketData):
    """Market data from MySource API."""

    def __init__(self, api_key: str) -> None:
        self.api_key = api_key

    def get_price(self, symbol: str) -> float:
        # Call API, return latest price
        ...

    def get_historical(self, symbol: str, days: int = 30) -> list[OHLCV]:
        # Call API, return OHLCV bars
        ...

    def search_symbol(self, query: str) -> list[dict[str, str]]:
        # Return [{"symbol": "AAPL", "name": "Apple Inc."}, ...]
        ...
```

### Step 2: Add to the priority waterfall

In the main orchestrator or market data factory, add your source to the fallback chain with the appropriate API key check.

### Step 3: Add env variable

Add `MY_SOURCE_API_KEY` to `agents/config.py`.


## Adding a New Scraper Type

All scrapers implement `BaseScraper` from `agents/scrapers/base.py`.

### Step 1: Create the scraper file

Create `agents/scrapers/my_scraper.py`:

```python
from __future__ import annotations

from .base import Article, BaseScraper


class MyScraper(BaseScraper):
    """Scraper for MySource data format."""

    def fetch(self, url: str) -> list[dict]:
        # Fetch raw data from URL
        ...

    def parse(self, raw_data: list[dict]) -> list[Article]:
        # Convert raw data to Article instances
        return [
            Article(
                title=item["title"],
                content=item["body"],
                url=item["link"],
                source="mysource",
                published_at=item.get("date"),
                symbols=self._extract_symbols(item["body"]),
            )
            for item in raw_data
        ]
```

### Step 2: Register in the scraper runner

Edit `agents/scrapers/runner.py` to recognize the new scraper type and instantiate it.


## Adding a New Notification Channel

### Step 1: Update NotificationService

Edit `laravel/app/Services/NotificationService.php` to add a new dispatch method:

```php
protected function sendToMyChannel(NotificationChannel $channel, string $subject, string $body): bool
{
    $config = json_decode(decrypt($channel->config), true);

    // Send notification via your channel's API
    $response = Http::post($config['endpoint'], [
        'message' => $body,
    ]);

    return $response->successful();
}
```

### Step 2: Add channel type to the UI

Update `NotificationSettingsController.php` and the notification settings Blade view to include the new channel type with its configuration fields.


## Database Migrations

### Creating a new migration

```bash
cd laravel
php artisan make:migration create_my_table
```

### Migration conventions

- Tables use snake_case: `my_new_table`
- Foreign keys follow Laravel convention: `user_id`, `portfolio_id`
- Timestamps: always include `$table->timestamps()` (creates `created_at`, `updated_at`)
- Soft deletes: use `$table->softDeletes()` where appropriate

### Running migrations

```bash
# Run pending migrations
php artisan migrate

# Rollback last batch
php artisan migrate:rollback

# Fresh install (drops all tables)
php artisan migrate:fresh --seed
```

### SQLAlchemy compatibility

The Python agents read the same database via SQLAlchemy using raw SQL (`sqlalchemy.text()`). When adding columns or tables, ensure both the Laravel migration and any Python queries are updated.


## Testing

### Laravel

```bash
cd laravel
php artisan test
```

Tests are in `laravel/tests/`. Follow Laravel's testing conventions with `Feature` and `Unit` subdirectories.

### Python

```bash
cd agents
source venv/bin/activate
python -m pytest
```

### Manual testing

1. Use Paper broker to test trading without real money
2. Use the **Test** button on scrapers to verify data collection
3. Use the **Test** button on notification channels to verify delivery
4. Use `php artisan tinker` for ad-hoc database queries
5. Check `agents.log` and `laravel/storage/logs/laravel.log` for errors


## Code Style

### PHP (Laravel)

- Follow **PSR-12** coding standard
- Use type hints on all method parameters and return types
- Use Laravel conventions for naming (controllers, models, migrations)
- Blade templates: use Tailwind CSS utility classes, no custom CSS

### Python (Agents)

- Follow **PEP 8** style guide
- Use type hints (Python 3.11+ syntax with `X | None` unions)
- Use `from __future__ import annotations` for forward references
- Dataclasses for structured data (`@dataclass`)
- ABCs for interfaces (`ABC`, `@abstractmethod`)


## Artisan Commands Reference

| Command | Description |
|---------|-------------|
| `php artisan agents:health` | Check the health/heartbeat of Python agents |
| `php artisan market:backfill {symbol} {--days=30}` | Backfill historical price data |
| `php artisan logs:clean {--days=30}` | Delete log entries older than N days |
| `php artisan daily:summary` | Send daily trading summary notification |
| `php artisan symbols:manage` | Add or remove tracked symbols |
| `php artisan setup:telegram` | Interactive Telegram bot setup helper |
| `php artisan analysis:trigger` | Manually trigger a Claude analysis |

Standard Laravel commands used frequently:

| Command | Description |
|---------|-------------|
| `php artisan migrate` | Run pending database migrations |
| `php artisan migrate:fresh --seed` | Reset database and seed demo data |
| `php artisan db:seed` | Run database seeders |
| `php artisan queue:work --tries=3` | Start the queue worker |
| `php artisan tinker` | Interactive REPL for database queries |
| `php artisan route:list` | List all registered routes |
| `php artisan key:generate` | Generate APP_KEY for encryption |


## Environment Variables Reference

### Application

| Variable | Default | Description |
|----------|---------|-------------|
| `APP_NAME` | `AI Trading Platform` | Application name |
| `APP_ENV` | `local` | Environment (local, production) |
| `APP_KEY` | (generated) | Encryption key (run `key:generate`) |
| `APP_DEBUG` | `true` | Show debug info (set `false` in production) |
| `APP_URL` | `http://localhost:8000` | Base URL of the application |

### Database

| Variable | Default | Description |
|----------|---------|-------------|
| `DB_CONNECTION` | `sqlite` | Database driver (sqlite, mysql) |
| `DB_HOST` | `127.0.0.1` | MySQL host |
| `DB_PORT` | `3306` | MySQL port |
| `DB_DATABASE` | `trading_platform` | MySQL database name |
| `DB_USERNAME` | `root` | MySQL username |
| `DB_PASSWORD` | (empty) | MySQL password |

### Redis

| Variable | Default | Description |
|----------|---------|-------------|
| `REDIS_HOST` | `127.0.0.1` | Redis server host |
| `REDIS_PORT` | `6379` | Redis server port |
| `REDIS_PASSWORD` | (empty) | Redis password (if set) |

### Queue

| Variable | Default | Description |
|----------|---------|-------------|
| `QUEUE_CONNECTION` | `database` | Queue driver |

### Claude CLI

| Variable | Default | Description |
|----------|---------|-------------|
| `CLAUDE_BINARY_PATH` | `claude` | Path to the Claude CLI binary |

### Alpaca Broker

| Variable | Default | Description |
|----------|---------|-------------|
| `ALPACA_API_KEY` | (empty) | Alpaca API key |
| `ALPACA_SECRET_KEY` | (empty) | Alpaca secret key |
| `ALPACA_BASE_URL` | `https://paper-api.alpaca.markets` | Paper or live URL |

### Binance Broker

| Variable | Default | Description |
|----------|---------|-------------|
| `BINANCE_API_KEY` | (empty) | Binance API key |
| `BINANCE_SECRET_KEY` | (empty) | Binance secret key |
| `BINANCE_TESTNET` | `true` | Use testnet if true |

### XTB Broker

| Variable | Default | Description |
|----------|---------|-------------|
| `XTB_USER_ID` | (empty) | XTB account user ID |
| `XTB_PASSWORD` | (empty) | XTB account password |
| `XTB_DEMO` | `true` | Use demo account if true |

### Market Data

| Variable | Default | Description |
|----------|---------|-------------|
| `ALPHAVANTAGE_API_KEY` | (empty) | Alpha Vantage API key |
| `FINNHUB_API_KEY` | (empty) | Finnhub API key |
| `TWELVEDATA_API_KEY` | (empty) | Twelve Data API key |

### Email (SMTP)

| Variable | Default | Description |
|----------|---------|-------------|
| `MAIL_MAILER` | `smtp` | Mail driver |
| `MAIL_HOST` | `127.0.0.1` | SMTP server |
| `MAIL_PORT` | `587` | SMTP port |
| `MAIL_USERNAME` | (empty) | SMTP username |
| `MAIL_PASSWORD` | (empty) | SMTP password |
| `MAIL_ENCRYPTION` | `tls` | Encryption (tls, ssl, null) |
| `MAIL_FROM_ADDRESS` | (empty) | Sender email address |
| `MAIL_FROM_NAME` | `${APP_NAME}` | Sender display name |

### Telegram

| Variable | Default | Description |
|----------|---------|-------------|
| `TELEGRAM_BOT_TOKEN` | (empty) | Telegram bot token from BotFather |
| `TELEGRAM_CHAT_ID` | (empty) | Target chat/group ID |

### Discord

| Variable | Default | Description |
|----------|---------|-------------|
| `DISCORD_WEBHOOK_URL` | (empty) | Discord webhook URL |

### Logging

| Variable | Default | Description |
|----------|---------|-------------|
| `LOG_LEVEL` | `INFO` | Python agent log level (DEBUG, INFO, WARNING, ERROR) |
