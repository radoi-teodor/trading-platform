# Runbook

How to operate the platform and what to do when something goes wrong. Commands are the planned ones; each is
created in the phase that needs it and this file is updated with it.

## 1. Daily routine

1. Open the dashboard. Check the mode banner, the health strip (market data, engines, workers) and the overnight
   journal.
2. Check demotions and halts from the last 24 hours and their reasons.

## 2. Processes

| Process | Start | Healthy when |
|---------|-------|--------------|
| market-data | `php artisan market:stream spot` and `php artisan market:stream perp` | Every enabled instrument has a candle less than 2 timeframes old |
| engine | `php artisan engine:run <account>` (the account's mode: paper or testnet) | The account's snapshot is under 2 minutes old, no open reconciliation mismatch |
| notifications | `php artisan queue:work database --queue=notifications` | No failed jobs |
| simulation worker | `php artisan queue:work simulation --queue=simulation,market --timeout=21600 --tries=1` | Runs and history imports leave `queued` |
| scheduler | `php artisan schedule:work` | The daily report arrives each morning (D-77); `php artisan schedule:list` shows the jobs |
| web | `php artisan serve` (or PHP-FPM behind a reverse proxy) | `/up` answers |

All run under a supervisor with restart on failure. `php artisan platform:health` prints the checks of D-78 (each
stream's heartbeat and the latest candle of every streamed instrument, each watched engine's last publish, the
notification and simulation queues, the disk) and exits non-zero when one fails. The scheduler runs it every minute
and tells you on Telegram, directly and not through the queue: once when a check fails, every hour while it does,
and when it recovers. Turn the watch of one account off on Settings, Accounts (a paper account you do not run). If
the whole machine stops, nothing can tell you: the missing daily report is the sign.

A supervisord file for the always-on machine, `/etc/supervisor/conf.d/trading.conf` (change the path and the user;
`supervisorctl reread && supervisorctl update` loads it). The web server is left out: run it as you serve PHP.

```ini
[group:trading]
programs=stream-spot,stream-perp,engine-paper,notifications,simulation,scheduler

[program:stream-spot]
command=php artisan market:stream spot
directory=/srv/trading-platform
user=trading
autorestart=true
startretries=100
stopwaitsecs=30
redirect_stderr=true
stdout_logfile=/srv/trading-platform/storage/logs/stream-spot.log

[program:stream-perp]
command=php artisan market:stream perp
directory=/srv/trading-platform
user=trading
autorestart=true
startretries=100
stopwaitsecs=30
redirect_stderr=true
stdout_logfile=/srv/trading-platform/storage/logs/stream-perp.log

[program:engine-paper]
command=php artisan engine:run paper
directory=/srv/trading-platform
user=trading
autorestart=true
startretries=100
stopwaitsecs=30
redirect_stderr=true
stdout_logfile=/srv/trading-platform/storage/logs/engine-paper.log

[program:notifications]
command=php artisan queue:work database --queue=notifications --sleep=1
directory=/srv/trading-platform
user=trading
autorestart=true
redirect_stderr=true
stdout_logfile=/srv/trading-platform/storage/logs/notifications.log

[program:scheduler]
command=php artisan schedule:work
directory=/srv/trading-platform
user=trading
autorestart=true
redirect_stderr=true
stdout_logfile=/srv/trading-platform/storage/logs/scheduler.log

[program:simulation]
command=php artisan queue:work simulation --queue=simulation,market --timeout=21600 --tries=1
directory=/srv/trading-platform
user=trading
autorestart=true
stopwaitsecs=3600
redirect_stderr=true
stdout_logfile=/srv/trading-platform/storage/logs/simulation.log
```

A testnet account gets its own `[program:engine-testnet]` with `engine:run testnet`. Without Redis on the machine,
set `CACHE_STORE=file` (or `database`) in `.env`: the login throttle uses the cache. Notifications use the
database queue whatever `QUEUE_CONNECTION` says (`NOTIFICATIONS_QUEUE`).

## 3. Kill switch

| Situation | Command | Effect |
|-----------|---------|--------|
| Something looks wrong, not sure what | `php artisan engine:halt <account>` | No new entries; stops stay on the exchange |
| A strategy misbehaves | `php artisan engine:pause <account> <id>@v<n>` (`--resume` to resume) | Its intents are rejected; its stops stay |
| Everything is understood | `php artisan engine:resume <account>` | Lifts the halts (the day's loss lifts by itself at midnight UTC) |
| Losses must stop now | `php artisan engine:flatten <account>` | Cancels all orders, closes all positions at market, then halts |

The same buttons are on every screen. The commands work when the web process is down: each writes a row of
`engine_commands`, which the engine applies at its next minute and journals. If the server itself is
unreachable, use the exchange's own interface: cancel orders, close positions, then disable the API key.

## 4. Incidents

Start every incident the same way: halt the affected account, write down the time, then diagnose.

**Exchange unreachable or erroring.** The engine halts itself after the error threshold. Protective orders on the
exchange still protect positions. Check the exchange status page. Resume only after reconciliation shows no
mismatch.

**Reconciliation mismatch.** The exchange is right (D-10). Read the mismatch record: missing fill, unknown order,
balance difference. Never edit trading state by hand in the database; run `php artisan engine:reconcile
<account> --verbose` and let the engine adopt the exchange state. Open a `bug-fix` if the cause is ours.

**Stale market data.** Entries stop on the affected instruments by themselves. Restart `market:stream`; check the
gap report; backfill with `php artisan market:backfill <symbol> --from=<time>`.

**Unexpected order or position.** Halt. Compare the exchange order history with the journal by client order id.
An order without a journal record means the key may be used elsewhere: disable the key on the exchange
immediately and rotate it.

**Suspected key leak.** Disable the key on the exchange first, then rotate (section 5), then review exchange
order and login history.

**Drawdown limit hit.** The policy has already halted or flattened. Read the per-strategy breakdown and the
journal of the halt. Resume only with a written reason in the journal.

## 5. Key rotation

1. Create a new key on Binance: trading on, withdrawals off, IP restricted.
2. Settings, Testnet keys, store the new key over the old one (re-authentication required); restart the engine.
3. The engine reads the new key at its start and reconciles with the exchange.
4. Delete the old key on Binance.

## 6. Backups and restore

- Daily encrypted database backup, 30 days kept.
- Monthly restore test into a scratch database: `php artisan platform:health` against it must pass.
- Strategy exports are in git; the journal is in the database backups only.

## 7. Deploy

1. CI green on the pull request; merge to `main`.
2. Halt live engines (`engine:halt`), deploy, run migrations.
3. Start processes; check `platform:health`; resume engines (operator action).

## 8. Market data

| Task | Command |
|------|---------|
| Load or refresh instruments and filters | `php artisan market:instruments all` |
| Fill history | `php artisan market:backfill --universe --from=YYYY-MM [--to=...] --funding` |
| Keep candles current | `php artisan market:stream spot` and `php artisan market:stream perp`, under the supervisor |
| Look for gaps and bad rows | `php artisan market:check --universe --from=... --to=...` (`--strict` fails on warnings) |
| Compare with the exchange | `php artisan market:verify --universe --from=... --to=... --sample=500 [--against=daily-archive]` |
| Read the real public endpoints in tests | `composer test:network` |

Everything is idempotent: rerunning a backfill only fills what is missing or replaces lower-ranked data.

**First run on the operator's machine.** The rebuild was developed from a location Binance refuses (451 on
`api.binance.com` and `fapi.binance.com`; `fstream.binance.com` sends only pings), so three things were checked
only through Binance's public-data hosts and archives and should be confirmed once from Romania:

1. `composer test:network`: the real USD-M exchangeInfo, klines and funding endpoints parse.
2. `php artisan market:instruments perp`, then `php artisan market:backfill --universe --from=2026-10 --to=2026-10`:
   the `rest gap` steps should now fill 2026-10-28 for the perpetuals, which Binance's archives do not have;
   `php artisan market:check --universe --from=2026-10 --to=2026-10` should then report no gap.
3. `php artisan market:stream perp` for a few minutes: closed candles appear with source 1 (stream).

**Health.** Each stream writes a row in `heartbeats` (`market:stream spot`, `market:stream perp`) at least every
30 seconds, with the result of its last gap repair. A heartbeat older than 2 minutes means the process is down.

**The universe and history (D-80).** Settings, Market data lists the instruments followed: streamed or history
only, with the scalping data on for perpetuals, and the notional from which a trade is large. Add any instrument
`php artisan market:instruments perp` (or `spot`) has stored, then import its history from a day: the import runs as
a job on the simulation worker, shows its progress there, and estimates its size. Twelve months of candles and
scalping data for BTC and ETH perpetuals is roughly 1.3 GB in the database and 10 to 15 GB downloaded once. Restart
`market:stream` after changing what is streamed. On the command line:
`php artisan market:backfill BTC/USDT:USDT --from=2025-11 --funding --flow --oi --mark`.

## 9. Backtests

| Task | Command |
|------|---------|
| Backtest a strategy | `php artisan backtest:run <strategy> --from=YYYY-MM-DD --to=YYYY-MM-DD [--symbol=BTC/USDT] [--param=key=value] [--equity=10000] [--no-benchmarks]` |
| Check a strategy document | `php artisan strategy:compile catalog/strategies/<id>.strategy.yaml` |
| Save documents as versions | `php artisan strategy:import [catalog/strategies or a file]`: an unchanged document keeps its version |
| Write versions to the catalog | `php artisan strategy:export [catalog/strategies]`: `<id>.v<n>.strategy.yaml` |
| Backtest a saved version | `php artisan backtest:run <id>@v<n> --from=YYYY-MM-DD --to=YYYY-MM-DD` (also `backtest:grid <id>@v<n>`) |
| Backtest a strategy document | `php artisan backtest:run --file=catalog/strategies/<id>.strategy.yaml --from=YYYY-MM-DD --to=YYYY-MM-DD [--param=fast=12] [--no-benchmarks]` |
| Search a document's parameters | `php artisan backtest:grid --file=catalog/strategies/<id>.strategy.yaml --from=YYYY-MM-DD --to=YYYY-MM-DD [--grid=fast=5..13:2] [--grid=slow=15,21,34]` |
| Walk forward over a grid | `php artisan backtest:grid --file=... --from=... --to=... --walk-forward [--in=12m] [--out=3m] [--step=3m] [--min-trades=100] [--workers=4]` |
| Judge a version against the gates | `php artisan strategy:gates <id>@v<n> [--in=12m] [--out=3m] [--step=3m] [--min-trades=100] [--seed=2026] [--workers=4]` |
| Promote a version to Backtested | `php artisan strategy:promote <id>@v<n>` (only after a passing gate report; never to paper or live) |
| Read a version in plain sentences | `php artisan strategy:compile <file>` prints the read-back |

Strategies written in the DSL (`docs/strategy/configurator.md`) live in `catalog/strategies/` and run with `--file`;
their instruments and timeframe come from the document and `--param` overrides a declared parameter. `backtest:grid` runs every
parameter set in one pass (by default each declared parameter's range and step, at most 400 sets), lists them by
Sharpe ratio, and gives the deflated Sharpe ratio of the best set against every trial of the document's family and
the probability of backtest overfitting (D-58). Every backtest of a document is recorded in `strategy_trials`, so a
search cannot hide how many tries it took. `--workers` splits the sets over child processes that read the same
database; the sets come back identical whatever their number. Errors are printed
as `file:line:column: problem`. Hand-coded strategies and benchmarks are listed in `config/simulation.php`. A run refuses to start on history with gaps,
warmup included, and says where they are; backfill first. A perpetual is named with its settle asset
(`--symbol=BTC/USDT:USDT`) and also needs funding without gaps. The equity starts in the USD-M wallet for a
perpetual-only run, in the spot wallet for a spot-only run, and is split by `perpetual.spot_share_when_both` when a
run trades both (D-49). Benchmarks always hold the spot instrument of the same base, with the whole equity in spot. Each run stores its report as
`storage/app/simulations/<sha256>.json.gz` and a summary row in `backtest_runs`. The same definition on the same data
gives the same hash (D-42); a different hash on unchanged data and code is a bug.

## 10. Agents over MCP

The MCP endpoint (D-63) lets Claude Desktop or Claude Code read candles and indicators, compile and save draft
strategies, and run backtests, grids and walk-forwards. It cannot trade, change a limit or promote.

| Task | Command |
|------|---------|
| Turn it on | `MCP_ENABLED=true` in `.env`, then restart the web process |
| Create a token | Settings, MCP tokens (or `php artisan mcp:token <name>`): copy it now, it is shown once and stored only as a hash |
| Revoke a token | Settings, MCP tokens (or `php artisan mcp:revoke <name>`) |
| Run queued simulations | `php artisan queue:work simulation --queue=simulation,market --timeout=21600 --tries=1` |

Connect a client to `http://<host>:8000/mcp` with the header `Authorization: Bearer <token>`. For Claude Code:
`claude mcp add --transport http trading-x http://127.0.0.1:8000/mcp --header "Authorization: Bearer <token>"`.
The endpoint is for your own network; anywhere else it must sit behind TLS. Every call is listed in `mcp_calls`.

The rate limit (60 calls a minute per token, `MCP_CALLS_PER_MINUTE`) is kept in the cache store, Redis by default;
without Redis running, set `CACHE_STORE=file`. A run waits in `simulation_jobs` until the worker takes it, and each
token has at most 2 runs waiting or running. A window over missing candles fails with the gap named in `get_job`
(for BTC perpetual, Binance did not publish 2026-10-28; end windows before it, or fill it as section 8 says).

The usual flow for an agent: `list_instruments`, `get_indicators` on the chart it studies, `dsl_reference`, then
`compile_strategy` until it compiles, `save_strategy` (a draft, `<id>@v<n>`, authored `agent:<token name>`),
`run_grid` with a walk-forward on that version, and `get_job` until it is done. Checked on 2026-11-08 with the
official MCP Python SDK over HTTP on real BTC perpetual candles: a 6-set grid with a 30d/15d walk-forward over
2026-08-01 to 2026-10-27 took 39 s in the worker.

## 11. Paper trading

| Task | Command |
|------|---------|
| Create the paper account | Settings, Accounts (or `php artisan account:create paper [--equity=10000] [--symbol=BTC/USDT] [--start="2026-11-10 00:00"]`) |
| Run it | `php artisan engine:run paper` (keeps running; `--once` handles what is stored and stops) |
| Put a strategy in paper | It happens on its own when `strategy:promote` moves a version to Backtested (D-71); restart the engine to load it |

The engine reads the minutes `market:stream` stores, so the stream must run too. A restart replays every minute
since the account started and checks each decision against the journal (D-68): "The replay matches the journal."
A divergence stops the engine with the journal record that differs; nothing more runs until you look. A missing
minute is never skipped: the engine waits, journals `stale_data` after 2 minutes and tells you on Telegram.

**Telegram.** Create a bot with @BotFather and copy its token; send the bot a message and read your chat id from
`https://api.telegram.org/bot<token>/getUpdates`. Enter both on the Settings screen (it asks for your password and a
fresh code first) and press "Send a test message". They are stored encrypted and never shown back; `.env`
(`TELEGRAM_BOT_TOKEN`, `TELEGRAM_CHAT_ID`) still works and applies when the app has none, and the screen says which
one is used (D-75). Never put them in the repository or a chat. Halts, reconciliation mismatches, replay
divergences, venue errors, clock skew, liquidation and stale data always send a message; commands, volatility shocks
and price sanity are switches on the same screen (commands on, the other two off, since they trip many times a day,
D-73).

**Daily report (D-77).** At 08:00 Bucharest time (`DAILY_REPORT_AT`, `DAILY_REPORT_TIMEZONE` in `.env`) the scheduler
sends each account's last 24 hours: equity and its change, trades closed, halts and commands, breakers, stale data
and mismatches. `php artisan report:daily --print` shows it now without sending. Turn it off in Settings,
Notifications. No report in the morning means the scheduler or the notifications worker is down.

## 12. Testnet

| Task | Command |
|------|---------|
| Create the account | Settings, Accounts, mode testnet (or `php artisan account:create testnet --mode=testnet [--symbol=BTC/USDT]`) |
| Store its key | Settings, Testnet keys, after your password and a code (or `php artisan account:keys testnet`, asked twice, hidden); from testnet.binance.vision, trade only. The engine reads it at its next start |
| Run it | `php artisan engine:run testnet` |
| Check the venue against the real test network | `TRADING_TESTNET=1 TRADING_TESTNET_KEY=... TRADING_TESTNET_SECRET=... composer test:testnet` |

A testnet engine starts from the exchange as it is (D-74): close every order and position for its instruments on
the testnet site first. It halts when the machine's clock is more than a second off Binance's (fix the time
sync) or when the key is refused, and stops on any other venue error; its supervisor restarts it. Binance blocks
some regions: run it from where your account works.

## 13. The screens

| Task | Command |
|------|---------|
| Create the operator (once) | `php artisan user:create you@example.com`: the password twice, then add the TOTP key it shows to your authenticator and type a code |
| Build the front end | `npm ci && npm run build` |
| Serve it on this machine | `php artisan serve` and open `http://127.0.0.1:8000` |

Chart: candles of a stored instrument with layers from the DSL's library; price layers over the candles, the
others in panels, conditions as markers, the window's volume profile on the right, and a table view. Strategies:
the editor compiles as you type, shows errors with their line and column and the read-back, saves versions, runs
backtests, grids and gate reports in the background (the simulation worker must run) and promotes a passing
version to Backtested. Account: the snapshot of each account, its journal and its commands; resume, resume a
strategy and flatten ask for your password and a fresh code, valid 10 minutes. Settings (D-75): Telegram and the
notification switches, accounts, testnet keys and MCP tokens (a new token is shown once); the risk limits and
breaker thresholds are shown and change only in code (D-19). Changing Telegram, a key or creating a token asks for
your password and a fresh code. Halt is on every screen and stays on screen when you scroll.


## 14. Live (Phase 6, D-82, D-83)

Live is built and stays off until a strategy qualifies. Nothing in this section is ever done by an agent.

| Task | Command |
|------|---------|
| See where each deployment stands | Strategies, Ladder to live: stage, size, days, trades, band, the next step and what it still needs |
| Review the band now | `php artisan strategy:review` (the scheduler runs it every day at 00:41 UTC) |
| Create the live account | Settings, Accounts, mode live, after your password and a fresh code (or `php artisan account:create live --mode=live`) |
| Store its key | Settings, Exchange keys (or `php artisan account:keys live`) |
| Approve it | Settings, "Approve live trading", after your password and a fresh code (or `php artisan account:approve-live live`); it takes effect 24 hours later |
| Withdraw the approval | Settings, Withdraw (or `php artisan account:approve-live live --withdraw`); at once |
| Promote a deployment one step | Strategies, Ladder to live, "Promote to ...", after your password and a fresh code; it takes effect 24 hours later |

**Starting live micro, step by step**, once the Ladder shows a paper deployment ready (30 days and 30 trades, in band):

1. On binance.com create an API key for this machine only: enable spot or futures trading as the strategy needs,
   leave withdrawals off, restrict it to this machine's IP address. Never paste it anywhere else.
2. Create the live account with a small balance you accept to lose, then store the key on it.
3. Approve the account. The 24 hours start now.
4. Promote the paper deployment from Strategies. It becomes a live micro deployment on the live account at a
   quarter of the size, with a 5 % of equity cap on each position's notional, effective 24 hours later.
5. Run `php artisan engine:run live` under supervisor (as in section 2) after both delays are over. It refuses to
   start, and says why, while the approval is younger than 24 hours, when the key can withdraw, is not limited to
   an IP address or cannot trade the account's markets, or when a deployment on it is not at a live stage.
6. Close every order and position for the account's instruments on Binance before the first start: the engine
   starts from the exchange as it is (D-74).
7. Watch the daily report and the Account screen. The next steps (half size, then full size) need 30 days and 20
   trades in band each and your approval again.

**Demotion.** A deployment out of band is demoted at once, without asking: the deployment ends, the running
engine pauses the strategy at its next minute, the stage is written, the journal and Telegram say why. A live
deployment drops to live micro and live micro to paper; the smaller step applies from the engine's next start, and
resuming the strategy is your decision after you read why it left the band. Halting is always allowed (section 3).
