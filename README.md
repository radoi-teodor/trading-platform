# AI Trading Platform

AI-powered stock trading platform where Claude is the decision-making engine. Scrapers feed market data and news to Claude (via the `claude` CLI binary), Claude generates daily trading strategies with full reasoning, and Python bots execute those strategies autonomously.

## Architecture

```
Scrapers → DB → Claude CLI → Strategy JSON → Trading Bots → Broker → Results → DB → Next Day Context
```

**Daily Cycle:**
1. **SCRAPE** — ScraperAgents collect news, sentiment, market data continuously
2. **ANALYZE** — Python orchestrator pipes all collected data to `claude` binary, Claude returns structured JSON strategy
3. **EXECUTE** — TradingBots follow Claude's strategy all day, executing trades when conditions are met
4. **REPORT** — End of day, results are compiled and stored
5. **REVIEW** — Users view every strategy Claude created, full reasoning, planned vs executed trades, and performance in the Laravel UI

## Tech Stack

| Component | Technology |
|-----------|-----------|
| Web Framework | Laravel 11, PHP 8.3 |
| Frontend | Blade templates + Tailwind CSS (no JS frameworks) |
| Database | MySQL or SQLite |
| Cache/Pub-Sub | Redis |
| AI Engine | `claude` CLI binary (Claude Code) |
| Trading Agents | Python 3.11+ |
| Brokers | Paper (simulated), Alpaca, Binance, XTB |

## Prerequisites

- PHP 8.3+ with Composer
- Python 3.11+
- Redis server
- MySQL 8.0+ or SQLite
- `claude` CLI binary installed and authenticated

### Verify Claude Binary

```bash
# Check installation
claude --version

# Test it works
claude -p "say hello" --output-format json
```

The platform uses the `claude` binary directly — **no Anthropic API key needed**. The binary handles authentication.

## Quick Start

### 1. Clone and Configure

```bash
cp .env.example .env
# Edit .env with your database and Redis settings
```

### 2. Laravel Setup

```bash
cd laravel
composer install
php artisan key:generate
php artisan migrate
php artisan db:seed    # Creates demo user and sample data
```

### 3. Python Setup

```bash
cd agents
python3 -m venv venv
source venv/bin/activate    # Windows: venv\Scripts\activate
pip install -r requirements.txt
```

### 4. Start Everything

```bash
# Option A: Use start script
chmod +x start.sh
./start.sh

# Option B: Start manually (3 terminals)
# Terminal 1: Laravel
cd laravel && php artisan serve --host=0.0.0.0 --port=8000

# Terminal 2: Queue worker
cd laravel && php artisan queue:work --tries=3 --timeout=120

# Terminal 3: Python agents
cd agents && source venv/bin/activate && python main.py
```

### 5. Access the Platform

- **URL:** http://localhost:8000
- **Demo Login:** demo@example.com / password

## Demo Walkthrough

1. **Login** with demo credentials
2. **Dashboard** — See portfolio value, today's P&L, active bots and scrapers
3. **Strategies** — Browse past Claude analyses with full reasoning, planned trades, and execution results
4. **Strategy Detail** — Read Claude's market analysis, see every trade it recommended, compare planned vs actual
5. **Settings > Strategy** — Configure risk tolerance, write custom instructions for Claude
6. **Scrapers** — Add news sources that feed data to Claude's analysis

## Broker Configuration

### Paper Trading (Default)
No configuration needed. Simulates trades against real market prices.

### Alpaca (US Stocks)
```env
ALPACA_API_KEY=your_key
ALPACA_SECRET_KEY=your_secret
ALPACA_BASE_URL=https://paper-api.alpaca.markets
```
Get keys at: https://app.alpaca.markets/signup

### Binance (Crypto)
```env
BINANCE_API_KEY=your_key
BINANCE_SECRET_KEY=your_secret
BINANCE_TESTNET=true
```
Get keys at: https://www.binance.com/en/my/settings/api-management

### XTB (Forex/CFDs)
```env
XTB_USER_ID=your_id
XTB_PASSWORD=your_password
XTB_DEMO=true
```
Register at: https://www.xtb.com

## Market Data API Keys (Free Tier)

All optional — the platform falls back through available sources:

| Source | Free Tier | Env Variable | Sign Up |
|--------|-----------|-------------|---------|
| Alpha Vantage | 5 calls/min | `ALPHAVANTAGE_API_KEY` | https://www.alphavantage.co/support/#api-key |
| Finnhub | 60 calls/min | `FINNHUB_API_KEY` | https://finnhub.io/register |
| Twelve Data | 8 calls/min | `TWELVEDATA_API_KEY` | https://twelvedata.com/register |
| yfinance | Unlimited | None needed | — |
| Binance Public | Unlimited (crypto) | None needed | — |

## Notifications Setup

### Telegram
1. Create a bot via [@BotFather](https://t.me/botfather) on Telegram
2. Get the bot token
3. Send a message to your bot, then get your chat ID from `https://api.telegram.org/bot<TOKEN>/getUpdates`
4. Enter token and chat ID in Settings > Notifications

### Discord
1. In your Discord server, go to channel Settings > Integrations > Webhooks
2. Create a webhook and copy the URL
3. Enter the webhook URL in Settings > Notifications

### Email
Configure SMTP settings in `.env`:
```env
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your@gmail.com
MAIL_PASSWORD=your_app_password
```

## Artisan Commands

```bash
php artisan agents:health              # Check Python agent statuses
php artisan analysis:trigger {user_id} # Manually trigger Claude analysis
php artisan market:symbols             # Manage tracked symbols
php artisan market:backfill AAPL --days=365  # Backfill historical data
php artisan telegram:setup             # Telegram setup instructions
php artisan notifications:daily-summary # Send daily summary
php artisan logs:clean --days=90       # Purge old logs
```

## How Claude Communication Works

The `claude` binary is called via Python subprocess:

```python
result = subprocess.run(
    ["claude", "-p", prompt, "--output-format", "json"],
    capture_output=True, text=True, timeout=300
)
response = json.loads(result.stdout)
strategy = response["result"]
```

Every prompt and response is logged to the `claude_analyses` table for full transparency. Users can view the raw I/O in the Strategies section.

## Troubleshooting

| Issue | Solution |
|-------|----------|
| `claude: command not found` | Install Claude Code CLI and ensure it's in PATH, or set `CLAUDE_BINARY_PATH` in .env |
| `claude` returns auth error | Run `claude` manually first to authenticate |
| Redis connection refused | Start Redis: `redis-server` or `docker-compose up redis` |
| No market data | Add at least one API key (Alpha Vantage recommended) or ensure yfinance works |
| Queue jobs not processing | Run `php artisan queue:work` in a separate terminal |
| Python agents won't start | Check `agents/venv` exists and requirements are installed |

## Project Structure

```
/project-root
├── .env.example
├── start.sh
├── README.md
├── docker-compose.yml
├── /laravel              ← Laravel 11 web application
│   ├── /app
│   │   ├── /Http/Controllers
│   │   ├── /Models
│   │   ├── /Services
│   │   ├── /Jobs
│   │   └── /Console/Commands
│   ├── /database
│   │   ├── /migrations
│   │   └── /seeders
│   └── /resources/views  ← Blade templates
├── /agents               ← Python trading system
│   ├── main.py           ← Orchestrator
│   ├── /claude           ← Claude CLI wrapper
│   ├── /brokers          ← Broker integrations
│   ├── /market_data      ← Data sources
│   ├── /scrapers         ← News scrapers
│   ├── /bots             ← Trading bots
│   └── /indicators       ← Technical analysis
```

## License

Private — All rights reserved.
