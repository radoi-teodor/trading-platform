# API & Integration Reference

## Claude CLI Binary

### Overview

The platform uses the Claude Code CLI binary as a local subprocess. No API key or network call to Anthropic is needed -- the binary runs entirely on the local machine.

### Invocation

The `ClaudeEngine` class in `agents/claude/engine.py` wraps all Claude CLI calls:

```python
result = subprocess.run(
    [self.binary_path, "-p", prompt, "--output-format", "json"],
    capture_output=True,
    text=True,
    timeout=timeout,
)
```

| Argument | Purpose |
|----------|---------|
| `-p` | Pass the prompt as a command-line argument |
| `--output-format json` | Return structured JSON instead of plain text |

### Configuration

| Env Variable | Default | Description |
|-------------|---------|-------------|
| `CLAUDE_BINARY_PATH` | `claude` | Path to the Claude CLI binary |

### JSON Output Format

Claude CLI returns JSON when `--output-format json` is specified:

```json
{
  "result": "The actual text response from Claude...",
  "model": "claude-...",
  "stop_reason": "end_turn",
  "usage": {
    "input_tokens": 1234,
    "output_tokens": 567
  }
}
```

The `ClaudeEngine.ask()` method extracts `response.get("result", raw_stdout)`.

### Retry Logic

`ClaudeEngine.ask()` retries up to 3 times with exponential back-off:

| Attempt | Delay Before Retry |
|---------|-------------------|
| 1st failure | 5 seconds |
| 2nd failure | 15 seconds |
| 3rd failure | Raise `RuntimeError` |

Failures include non-zero exit codes, JSON parse errors, and timeouts.

### Timeout

Default timeout is **300 seconds** (5 minutes). This can be adjusted when calling `ClaudeEngine.ask(prompt, timeout=600)`.

### Daily Analysis Flow

`ClaudeEngine.daily_analysis(context)` orchestrates the full analysis:

1. `PromptBuilder.build_daily_analysis(context)` constructs the prompt with:
   - Current portfolio state (positions, cash, equity)
   - Recent scraped news with sentiment scores
   - Market prices for watchlist symbols
   - Technical indicators
   - User's risk tolerance and custom instructions
   - Previous analysis results for continuity

2. The prompt is sent to Claude CLI via `ask()`

3. `StrategyParser.parse(response)` extracts structured data:
   ```json
   {
     "trades": [
       {"symbol": "AAPL", "action": "buy", "quantity": 10, "reasoning": "..."}
     ],
     "closes": [
       {"symbol": "MSFT", "reasoning": "Take profits after 15% gain"}
     ],
     "alerts": [
       {"symbol": "TSLA", "message": "Earnings tomorrow", "severity": "warning"}
     ],
     "summary": "Overall market analysis text..."
   }
   ```

4. Results are logged to `claude_analyses` and child tables


## Broker APIs

### Paper Broker

- **File:** `agents/brokers/paper.py`
- **Purpose:** Simulated trading for testing; no real money involved
- **Configuration:** None required
- **Behavior:** Tracks virtual positions and cash balance in the database. Fill prices use the latest market data price. No fees.

### Alpaca

- **File:** `agents/brokers/alpaca.py`
- **API Docs:** https://docs.alpaca.markets/
- **Capabilities:** US stocks and ETFs, fractional shares, paper + live trading

| Env Variable | Description |
|-------------|-------------|
| `ALPACA_API_KEY` | Your Alpaca API key |
| `ALPACA_SECRET_KEY` | Your Alpaca secret key |
| `ALPACA_BASE_URL` | `https://paper-api.alpaca.markets` (paper) or `https://api.alpaca.markets` (live) |

**Setup steps:**

1. Create an account at https://alpaca.markets
2. Generate API keys from the dashboard
3. Start with the paper trading URL for testing
4. Set credentials in `.env` or via the Broker Settings UI

**Supported order types:** market, limit, stop, stop_limit

### Binance

- **File:** `agents/brokers/binance_broker.py`
- **API Docs:** https://binance-docs.github.io/apidocs/
- **Capabilities:** Cryptocurrency trading, spot market

| Env Variable | Description |
|-------------|-------------|
| `BINANCE_API_KEY` | Your Binance API key |
| `BINANCE_SECRET_KEY` | Your Binance secret key |
| `BINANCE_TESTNET` | `true` for testnet, `false` for live (default: `true`) |

**Setup steps:**

1. Create an account at https://www.binance.com
2. Enable API access and generate keys
3. For testing, use the Binance testnet at https://testnet.binance.vision
4. Set credentials in `.env` or via the Broker Settings UI

**Supported order types:** market, limit, stop_limit

### XTB

- **File:** `agents/brokers/xtb.py`
- **API Docs:** http://developers.xstore.pro/documentation/
- **Capabilities:** Forex, CFDs, stocks (varies by region)

| Env Variable | Description |
|-------------|-------------|
| `XTB_USER_ID` | Your XTB account user ID |
| `XTB_PASSWORD` | Your XTB account password |
| `XTB_DEMO` | `true` for demo account, `false` for live (default: `true`) |

**Setup steps:**

1. Create an account at https://www.xtb.com
2. Open a demo account for testing
3. Set credentials in `.env` or via the Broker Settings UI

**Note:** XTB uses WebSocket connections (xStation 5 API), not REST.

### Broker Interface

All brokers implement `BaseBroker` (defined in `agents/brokers/base.py`):

```python
class BaseBroker(ABC):
    def connect(self) -> None: ...
    def disconnect(self) -> None: ...
    def get_account(self) -> AccountInfo: ...
    def get_positions(self) -> list[Position]: ...
    def place_order(self, symbol, side, quantity, order_type, ...) -> OrderResult: ...
    def cancel_order(self, order_id) -> bool: ...
    def get_order_status(self, order_id) -> OrderResult: ...
```


## Market Data Sources

Market data providers are queried in a **priority waterfall**. If the first source fails or is not configured, the system falls through to the next:

```
1. Alpha Vantage  (if ALPHAVANTAGE_API_KEY is set)
       |
       v (fail/not configured)
2. Finnhub  (if FINNHUB_API_KEY is set)
       |
       v (fail/not configured)
3. Twelve Data  (if TWELVEDATA_API_KEY is set)
       |
       v (fail/not configured)
4. yfinance  (always available, no key needed)
```

### yfinance (Default)

- **File:** `agents/market_data/yfinance_source.py`
- **API Key:** Not required (uses Yahoo Finance public data)
- **Rate Limits:** Unofficial; avoid excessive requests
- **Data:** US and international stocks, ETFs, crypto, forex
- **Note:** This is the default fallback and works out of the box

### Alpha Vantage

- **File:** `agents/market_data/alpha_vantage.py`
- **API Key:** Required (`ALPHAVANTAGE_API_KEY`)
- **Free Tier:** 25 requests/day (paid plans available)
- **Sign Up:** https://www.alphavantage.co/support/#api-key
- **Data:** US stocks, forex, crypto, technical indicators

### Finnhub

- **File:** `agents/market_data/finnhub_source.py`
- **API Key:** Required (`FINNHUB_API_KEY`)
- **Free Tier:** 60 calls/minute
- **Sign Up:** https://finnhub.io/register
- **Data:** US stocks, forex, crypto, company fundamentals, news

### Twelve Data

- **File:** `agents/market_data/twelvedata_source.py`
- **API Key:** Required (`TWELVEDATA_API_KEY`)
- **Free Tier:** 800 calls/day, 8 per minute
- **Sign Up:** https://twelvedata.com/register
- **Data:** Stocks, forex, crypto, ETFs, indices

### Binance Market Data

- **File:** `agents/market_data/binance_data.py`
- **API Key:** Not required for public market data
- **Data:** Cryptocurrency pairs only

### XTB Market Data

- **File:** `agents/market_data/xtb_data.py`
- **API Key:** Uses XTB broker credentials
- **Data:** Whatever instruments are available on your XTB account

### Market Data Interface

All providers implement `BaseMarketData` (defined in `agents/market_data/base.py`):

```python
class BaseMarketData(ABC):
    def get_price(self, symbol: str) -> float: ...
    def get_historical(self, symbol: str, days: int = 30) -> list[OHLCV]: ...
    def search_symbol(self, query: str) -> list[dict[str, str]]: ...
```

The `OHLCV` dataclass:

```python
@dataclass
class OHLCV:
    timestamp: datetime
    open: float
    high: float
    low: float
    close: float
    volume: float
```


## Notification Channels

### Email (SMTP)

Laravel's built-in Mail system handles email delivery.

| Env Variable | Description | Example |
|-------------|-------------|---------|
| `MAIL_MAILER` | Mail driver | `smtp` |
| `MAIL_HOST` | SMTP server | `smtp.gmail.com` |
| `MAIL_PORT` | SMTP port | `587` |
| `MAIL_USERNAME` | SMTP username | `you@gmail.com` |
| `MAIL_PASSWORD` | SMTP password or app password | `xxxx xxxx xxxx xxxx` |
| `MAIL_ENCRYPTION` | Encryption type | `tls` |
| `MAIL_FROM_ADDRESS` | Sender address | `trading@example.com` |
| `MAIL_FROM_NAME` | Sender name | `AI Trading Platform` |

**Gmail tip:** Use an App Password (not your regular password) from https://myaccount.google.com/apppasswords.

### Telegram

Uses the Telegram Bot API via HTTP POST.

**Setup:**

1. Open Telegram and search for `@BotFather`
2. Send `/newbot` and follow the prompts
3. Copy the bot token (looks like `123456:ABC-DEF1234ghIkl-zyx57W2v1u123ew11`)
4. Start a conversation with your new bot (send any message)
5. Get your chat ID using the Artisan command:

```bash
php artisan setup:telegram
```

Or manually via the Telegram API:
```bash
curl "https://api.telegram.org/bot<YOUR_TOKEN>/getUpdates" | jq '.result[0].message.chat.id'
```

**How messages are sent:**

```
POST https://api.telegram.org/bot{token}/sendMessage
Content-Type: application/json

{
  "chat_id": "123456789",
  "text": "Trade executed: BUY 10 AAPL @ $185.50",
  "parse_mode": "Markdown"
}
```

| Env Variable | Description |
|-------------|-------------|
| `TELEGRAM_BOT_TOKEN` | Bot token from BotFather |
| `TELEGRAM_CHAT_ID` | Your chat or group ID |

### Discord

Uses Discord Webhooks (no bot required).

**Setup:**

1. In your Discord server, go to **Server Settings > Integrations > Webhooks**
2. Click **New Webhook**
3. Choose the channel for notifications
4. Copy the webhook URL

**How messages are sent:**

```
POST https://discord.com/api/webhooks/{id}/{token}
Content-Type: application/json

{
  "content": "Trade executed: BUY 10 AAPL @ $185.50",
  "username": "AI Trading Platform"
}
```

| Env Variable | Description |
|-------------|-------------|
| `DISCORD_WEBHOOK_URL` | Full webhook URL from Discord |


## Redis Pub/Sub Channels

### Channel: `trading:commands`

**Publisher:** Laravel (via `SendAgentCommand` job)
**Subscriber:** Python agents (`main.py` Redis listener thread)

| Message Type | Payload | Purpose |
|-------------|---------|---------|
| `trigger_analysis` | `{"user_id": 1, "symbols": [...]}` | Request immediate Claude analysis |
| `update_config` | `{"user_id": 1}` | Tell agents to reload user settings |
| `restart_scraper` | `{"scraper_id": 5}` | Restart a specific scraper |
| `stop_bot` | `{"user_id": 1}` | Stop the trading bot |

### Channel: `trading:notifications`

**Publisher:** Python agents
**Subscriber:** Laravel (via `ProcessRedisMessage` job)

| Message Type | Payload | Purpose |
|-------------|---------|---------|
| `trade_executed` | `{"symbol": "AAPL", "side": "buy", ...}` | Notify user of trade |
| `analysis_complete` | `{"analysis_id": 42, "trades": 3}` | Analysis finished |
| `error` | `{"message": "Broker connection failed"}` | Error alert |

### Channel: `trading:health`

**Publisher:** Python agents
**Subscriber:** Laravel (via `ProcessRedisMessage` job)

| Message Type | Payload | Purpose |
|-------------|---------|---------|
| `heartbeat` | `{"agent": "scraper_runner", "status": "ok"}` | Regular health check |
| `status_change` | `{"agent": "bot_manager", "status": "stopped"}` | State transition |

### Message Envelope Format

All Redis messages follow the same JSON envelope:

```json
{
  "type": "trade_executed",
  "user_id": 1,
  "payload": {
    "symbol": "AAPL",
    "side": "buy",
    "quantity": 10,
    "price": 185.50
  },
  "timestamp": "2026-04-06T14:30:00Z"
}
```
