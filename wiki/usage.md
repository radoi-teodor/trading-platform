# Usage Guide

## First Login

After setup, open http://localhost:8000 and log in with the demo account:

- **Email:** `demo@example.com`
- **Password:** `password`

You can change the password and email under **Settings > Profile** after logging in.

To create additional accounts, visit `/register`.


## Dashboard

The dashboard (`/dashboard`) is the home screen showing a real-time overview of your trading platform.

### Stat Cards

| Card | What It Shows |
|------|---------------|
| **Portfolio Value** | Total equity across all positions + cash balance |
| **Today's P&L** | Profit/loss for the current trading day (dollar and percentage) |
| **Open Positions** | Number of currently held positions |
| **Pending Orders** | Orders submitted but not yet filled |
| **Claude Status** | Last analysis timestamp and status (success/error) |
| **Agent Health** | Whether the Python agents are running (heartbeat check) |

### Auto-Refresh

The dashboard uses Blade `meta http-equiv="refresh"` to periodically reload the page and show updated data. No JavaScript frameworks are involved. The refresh interval is typically 60 seconds.

### Quick Actions

From the dashboard you can:

- **Trigger Analysis** - Manually invoke Claude to generate a new strategy
- **View Latest Strategy** - Jump to the most recent Claude analysis
- **View Portfolio** - Go to positions and trades


## Claude Strategies

Navigate to **Strategies** (`/strategies`) to view Claude's AI-generated trading strategies.

### Strategy List

The main strategies page shows a chronological list of all Claude analyses, each displaying:

- **Date and time** of the analysis
- **Status** (completed, failed, pending)
- **Number of planned trades** (buys, sells, closes)
- **Number of alerts** generated

### Viewing a Strategy

Click on any strategy to see its full detail page (`/strategies/{id}`), which includes:

#### Claude's Reasoning

A formatted view of Claude's market analysis, including:
- Market conditions assessment
- Risk factors identified
- Rationale for each recommended trade
- Overall portfolio strategy

#### Planned Trades

A table of trades Claude recommended:

| Column | Description |
|--------|-------------|
| Symbol | Stock ticker (e.g., AAPL) |
| Action | buy, sell, or hold |
| Quantity | Number of shares |
| Reasoning | Why Claude recommends this trade |
| Executed | Whether the bot actually executed it |

#### Planned Closes

Positions Claude recommended closing, with the reasoning for each.

#### Alerts

Market alerts Claude flagged, with severity levels (info, warning, critical).

#### Raw Prompt / Response

Expandable sections showing:
- **Full prompt** - The exact text sent to Claude CLI (useful for debugging)
- **Full response** - Claude's raw JSON output before parsing

### Today's Strategy

Click **Today** (`/strategies/today`) to jump directly to the most recent analysis for the current date.

### Manual Analysis Trigger

Click the **Trigger Analysis** button (`POST /strategies/trigger`) to manually request a new Claude analysis outside the scheduled cycle. This is useful when:

- You want an immediate strategy update after breaking news
- You just changed your risk settings or watchlist
- You want to test after configuring a new scraper

The trigger publishes a `trigger_analysis` command to Redis, which the Python agent picks up and processes.


## Portfolio

Navigate to **Portfolio** (`/portfolio`) to manage and monitor your investments.

### Overview (`/portfolio`)

Summary view showing:
- Total portfolio value and cash balance
- Overall P&L (daily, weekly, all-time)
- Asset allocation breakdown

### Positions (`/portfolio/positions`)

Table of all currently held positions:

| Column | Description |
|--------|-------------|
| Symbol | Stock ticker |
| Quantity | Shares held |
| Avg Cost | Average entry price |
| Current Price | Latest market price |
| Market Value | Quantity x Current Price |
| Unrealized P&L | Profit/loss if closed now |
| Side | Long or short |

### Trade History (`/portfolio/trades`)

Complete log of all executed trades:

| Column | Description |
|--------|-------------|
| Date/Time | When the trade executed |
| Symbol | Stock ticker |
| Side | Buy or sell |
| Quantity | Shares traded |
| Price | Execution price |
| Fees | Broker fees |
| Total | Price x Quantity +/- fees |

### Performance (`/portfolio/performance`)

Historical performance charts rendered with server-side data:
- Equity curve over time (from `portfolio_snapshots`)
- Daily P&L bars
- Comparison against benchmarks


## Scrapers

Navigate to **Scrapers** (`/scrapers`) to configure data collection.

### Scraper List

Shows all configured scrapers with:
- Name and type (RSS or HTML)
- Target URL
- Active/inactive status toggle
- Last run time and article count

### Adding an RSS Scraper

1. Click **Create Scraper** (`/scrapers/create`)
2. Fill in the form:
   - **Name:** A descriptive label (e.g., "Reuters Business")
   - **Type:** RSS
   - **URL:** The RSS feed URL (e.g., `https://feeds.reuters.com/reuters/businessNews`)
   - **Active:** Toggle on
3. Click **Save**

The scraper runner will pick it up on its next cycle (every 30 minutes).

### Adding an HTML Scraper

HTML scrapers require CSS selectors to extract data from web pages:

1. Click **Create Scraper**
2. Fill in the form:
   - **Name:** A descriptive label (e.g., "MarketWatch Headlines")
   - **Type:** HTML
   - **URL:** The page URL
   - **Config (JSON):**

```json
{
  "article_selector": "div.article__content",
  "title_selector": "h3.article__headline a",
  "content_selector": "p.article__summary",
  "link_selector": "h3.article__headline a",
  "link_attribute": "href"
}
```

3. Click **Save**

### Testing a Scraper

Click the **Test** button on any scraper (`POST /scrapers/{id}/test`) to run a one-time fetch. The test will show:
- Number of articles found
- Sample article titles
- Any errors encountered

This lets you verify CSS selectors and feed URLs before activating the scraper.

### Toggling Active/Inactive

Click the **Toggle** button (`POST /scrapers/{id}/toggle`) to enable or disable a scraper. Inactive scrapers are skipped by the scraper runner.

### Sentiment Analysis

Each scraped article automatically receives a sentiment score:
- **Positive (> 0.5):** Bullish signal
- **Neutral (around 0):** No strong signal
- **Negative (< -0.5):** Bearish signal

Sentiment scores are included in Claude's analysis context, helping it gauge market mood.


## Watchlist

Navigate to **Watchlist** (`/watchlist`) to manage the symbols you are tracking.

### Adding Symbols

Enter a stock ticker (e.g., `AAPL`, `MSFT`, `TSLA`) and click **Add**. Watchlist symbols are:
- Included in Claude's analysis context
- Tracked for price data by the market data module
- Highlighted in news articles

### Removing Symbols

Click the **Remove** button next to any symbol to delete it from your watchlist.


## News

Navigate to **News** (`/news`) to browse all scraped articles.

### News Feed

A chronological list of all articles collected by your active scrapers, showing:
- Headline and source
- Publication date
- Sentiment score badge (positive/neutral/negative)
- Mentioned symbols (linked to watchlist)

### Article Detail

Click any article (`/news/{id}`) to view the full content, source URL, and associated symbols.


## Settings

### Strategy Settings (`/settings/strategy`)

Configure how Claude generates trading strategies:

| Setting | Description | Default |
|---------|-------------|---------|
| Risk Tolerance | low, medium, high, aggressive | medium |
| Max Position Size | Maximum percentage of portfolio per position | 10% |
| Max Open Positions | Maximum concurrent positions | 10 |
| Preferred Sectors | Sectors to focus on (comma-separated) | (all) |
| Avoid Symbols | Symbols Claude should never trade | (none) |
| Custom Instructions | Free-text instructions appended to Claude's prompt | (empty) |

The **Custom Instructions** field is particularly powerful. Examples:

```
Focus on dividend-paying stocks with PE ratio under 20.
Avoid biotech stocks. Prefer companies with market cap over $10B.
Be more conservative during earnings season.
Never hold positions overnight.
```

### Broker Settings (`/settings/broker`)

Configure your broker connection:

| Broker | Required Fields |
|--------|----------------|
| **Paper** (default) | None - simulated trading with virtual funds |
| **Alpaca** | API Key, Secret Key, Base URL (paper or live) |
| **Binance** | API Key, Secret Key, Testnet toggle |
| **XTB** | User ID, Password, Demo toggle |

All credentials are encrypted before storage using Laravel's encryption.

**Important:** Start with Paper trading to verify the platform works correctly before connecting a real broker.

### Notification Settings (`/settings/notifications`)

Configure notification channels:

#### Email

- **SMTP settings** are configured in `.env` (MAIL_HOST, MAIL_PORT, etc.)
- Set your notification email address in the UI
- Click **Test** to send a test email

#### Telegram

1. Create a bot via [@BotFather](https://t.me/BotFather) on Telegram
2. Copy the bot token
3. Start a chat with your bot and send any message
4. Get your chat ID (the Artisan command `php artisan setup:telegram` can help)
5. Enter the bot token and chat ID in settings
6. Click **Test** to verify

#### Discord

1. In your Discord server, go to **Server Settings > Integrations > Webhooks**
2. Click **New Webhook**, configure name and channel
3. Copy the webhook URL
4. Paste the URL in settings
5. Click **Test** to verify

### Profile (`/settings/profile`)

- Update your name and email
- Change your password


## Logs

Navigate to **Logs** (`/logs`) for debugging and monitoring.

### System Logs

Shows recent entries from the `bot_logs` table, including:
- Timestamp
- Log level (INFO, WARNING, ERROR)
- Message
- Context data (JSON)

Filter by log level to focus on errors.

### Claude Logs (`/logs/claude`)

A dedicated view showing all Claude CLI interactions:
- Timestamp of each call
- Prompt length
- Response length
- Success/failure status
- Execution time

Useful for diagnosing Claude CLI issues, slow responses, or parsing failures.

### Agent Health

The dashboard shows agent heartbeat status from the `agent_health` table. Each Python agent thread reports its status and last activity timestamp. If an agent stops reporting, the health indicator turns red.
