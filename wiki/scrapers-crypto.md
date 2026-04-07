# Crypto News Scrapers

Ready-to-use scraper configurations for cryptocurrency news and sentiment. Add these from the **Scrapers** page in the platform.

## RSS Feeds (Recommended — easiest to set up)

### CoinDesk
- **Name:** CoinDesk News
- **URL:** `https://www.coindesk.com/arc/outboundfeeds/rss/`
- **Type:** RSS
- **Schedule:** Every 15 Minutes
- **Keywords:** `bitcoin, ethereum, crypto, blockchain, defi, regulation, SEC, ETF`
- **Symbols:** `BTC, ETH`

### CoinTelegraph
- **Name:** CoinTelegraph
- **URL:** `https://cointelegraph.com/rss`
- **Type:** RSS
- **Schedule:** Every 15 Minutes
- **Keywords:** `bitcoin, ethereum, solana, price, rally, crash, bull, bear, whale`
- **Symbols:** `BTC, ETH, SOL`

### Bitcoin Magazine
- **Name:** Bitcoin Magazine
- **URL:** `https://bitcoinmagazine.com/.rss/full/`
- **Type:** RSS
- **Schedule:** Every 30 Minutes
- **Keywords:** `bitcoin, mining, halving, lightning, adoption, ETF`
- **Symbols:** `BTC`

### Decrypt
- **Name:** Decrypt
- **URL:** `https://decrypt.co/feed`
- **Type:** RSS
- **Schedule:** Every 15 Minutes
- **Keywords:** `bitcoin, ethereum, solana, defi, NFT, regulation, price`
- **Symbols:** `BTC, ETH, SOL`

### The Block
- **Name:** The Block
- **URL:** `https://www.theblock.co/rss.xml`
- **Type:** RSS
- **Schedule:** Every 15 Minutes
- **Keywords:** `bitcoin, ethereum, exchange, institutional, SEC, regulation`
- **Symbols:** `BTC, ETH`

### CryptoSlate
- **Name:** CryptoSlate
- **URL:** `https://cryptoslate.com/feed/`
- **Type:** RSS
- **Schedule:** Every 30 Minutes
- **Keywords:** `bitcoin, ethereum, altcoin, defi, market, price`
- **Symbols:** `BTC, ETH, SOL`

### Blockworks
- **Name:** Blockworks
- **URL:** `https://blockworks.co/feed`
- **Type:** RSS
- **Schedule:** Every 30 Minutes
- **Keywords:** `bitcoin, crypto, institutional, ETF, macro, fed`
- **Symbols:** `BTC, ETH`

## General Finance (also covers crypto)

### Reuters Business
- **Name:** Reuters Business
- **URL:** `https://feeds.reuters.com/reuters/businessNews`
- **Type:** RSS
- **Schedule:** Every 30 Minutes
- **Keywords:** `bitcoin, crypto, cryptocurrency, blockchain, fed, rates, inflation`
- **Symbols:** `BTC, ETH`

### Yahoo Finance Crypto
- **Name:** Yahoo Finance Crypto
- **URL:** `https://finance.yahoo.com/news/rssindex`
- **Type:** RSS
- **Schedule:** Every 30 Minutes
- **Keywords:** `bitcoin, crypto, ethereum, coinbase, binance`
- **Symbols:** `BTC, ETH`

## Quick Setup

The fastest way to get useful crypto sentiment data flowing to Claude:

1. Go to **Scrapers** in the sidebar
2. Add **CoinDesk** + **CoinTelegraph** + **Decrypt** (3 RSS feeds, takes 30 seconds)
3. Toggle them **Active**
4. Wait 15 minutes for articles to come in
5. Generate a new strategy — Claude will now have real crypto news context

## Tips

- **RSS feeds are more reliable** than HTML scrapers — they don't break when the site redesigns
- **Start with 2-3 feeds** to avoid noise. More feeds = more articles = longer Claude prompts
- **Keywords matter** — narrow keywords give Claude more relevant articles
- **Symbols must match your watchlist** — if you track `BTC` on watchlist, use `BTC` in scraper symbols too
- Articles are automatically scored for sentiment (bullish/bearish/neutral) before being sent to Claude
