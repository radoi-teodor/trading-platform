# AI Trading Platform - Wiki

An AI-powered stock trading platform that uses Claude Code CLI to analyze market data, generate trading strategies, and execute trades through multiple broker integrations. Built with Laravel 11, Python 3.11+ agents, and a pure Blade + Tailwind CSS frontend.

## Documentation

| Document | Description |
|----------|-------------|
| [Architecture](architecture.md) | System design, data flow, database schema, queue system, security |
| [Setup Guide](setup.md) | Full installation instructions for Ubuntu 22.04/24.04 |
| [Usage Guide](usage.md) | End-user guide: dashboard, strategies, portfolio, scrapers, settings |
| [API & Integrations](api-integration.md) | Claude CLI, broker APIs, market data sources, notifications |
| [Development Guide](development.md) | Project structure, extending the platform, commands, env vars |
| [Windows Setup](setup-windows.md) | Installation guide for Windows 10/11 with PowerShell scripts |
| [Crypto Scrapers](scrapers-crypto.md) | Ready-to-use RSS feeds for Bitcoin, Ethereum, crypto news |

## Quick Start

### Linux / macOS

```bash
# Clone and run
./start.sh

# Open http://localhost:8000
# Login: demo@example.com / password
```

### Windows

PowerShell scripts are provided for Windows setup and startup:

```powershell
# First-time setup (checks prerequisites, installs dependencies, creates database)
.\setup-windows.ps1

# Start all components (web server, queue worker, Python agents)
.\start-windows.ps1

# Open http://localhost:8000
# Login: demo@example.com / password
```

See the [Windows Setup Guide](setup-windows.md) for detailed instructions, prerequisites, and troubleshooting.

## Tech Stack

- **Backend:** Laravel 11 (PHP 8.3), MySQL or SQLite, Redis
- **Agents:** Python 3.11+ (scrapers, trading bots, Claude CLI integration)
- **Frontend:** Blade templates + Tailwind CSS via CDN (no Node.js required)
- **AI:** Claude Code CLI binary (pre-installed, no API key needed)
- **Brokers:** Paper trading, Alpaca, Binance, XTB

## Daily Cycle

```
SCRAPE --> ANALYZE --> EXECUTE --> REPORT --> REVIEW
```

1. **Scrape** - RSS/HTML scrapers collect market news and sentiment
2. **Analyze** - Claude CLI processes data and generates trading strategies
3. **Execute** - Trading bots place orders through configured broker
4. **Report** - Notifications sent via Email, Telegram, or Discord
5. **Review** - Dashboard displays results; Claude reviews end-of-day performance
