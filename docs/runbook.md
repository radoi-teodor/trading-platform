# Runbook

How to operate the platform and what to do when something goes wrong. Commands are the planned ones; each is
created in the phase that needs it and this file is updated with it.

## 1. Daily routine

1. Open the dashboard. Check the mode banner, the health strip (market data, engines, workers, sources, LLM
   budget) and the overnight journal.
2. Read the Reviewer's daily note. Approve or reject pending proposals; nothing changes until you do.
3. Check demotions and halts from the last 24 hours and their reasons.

## 2. Processes

| Process | Start | Healthy when |
|---------|-------|--------------|
| market-data | `php artisan market:stream` | Every enabled instrument has a candle less than 2 timeframes old |
| engine | `php artisan engine:run <account> --mode=<paper\|live>` | Heartbeat under 10 s, no open reconciliation mismatch |
| horizon | `php artisan horizon` | No queue older than 5 minutes, failed jobs reviewed |
| scheduler | `php artisan schedule:work` | Last run under 2 minutes ago |
| reverb | `php artisan reverb:start` | UI shows live prices |

All run under a supervisor with restart on failure. `php artisan platform:health` prints the same checks.

## 3. Kill switch

| Situation | Command | Effect |
|-----------|---------|--------|
| Something looks wrong, not sure what | `php artisan engine:halt <account>` | No new entries; stops stay on the exchange |
| A strategy misbehaves | `php artisan strategy:pause <strategy> --account=<account>` | That strategy stops emitting intents |
| Losses must stop now | `php artisan engine:flatten <account>` | Cancels all orders, closes all positions at market, then halts |

The same buttons are on every screen. The commands work when the web process is down. If the server itself is
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

**Drawdown limit hit.** The policy has already halted or flattened. Read the Reviewer's incident note and the
per-strategy breakdown. Resume only with a written reason in the journal.

**LLM unavailable or over budget.** Nothing to do for safety: agent features fall back to neutral defaults.
Check the budget screen; raise a budget only on purpose.

## 5. Key rotation

1. Create a new key on Binance: trading on, withdrawals off, IP restricted.
2. Settings, Accounts, replace key (re-authentication required).
3. The engine reconnects at the next event and reconciles.
4. Delete the old key on Binance.

## 6. Backups and restore

- Daily encrypted database backup, 30 days kept.
- Monthly restore test into a scratch database: `php artisan platform:health` against it must pass.
- The catalog and strategy exports are in git; the journal is in the database backups only.

## 7. Deploy

1. CI green on the pull request; merge to `main`.
2. Halt live engines (`engine:halt`), deploy, run migrations, `sources:sync`.
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

## 9. Backtests

| Task | Command |
|------|---------|
| Backtest a strategy | `php artisan backtest:run <strategy> --from=YYYY-MM-DD --to=YYYY-MM-DD [--symbol=BTC/USDT] [--param=key=value] [--equity=10000] [--no-benchmarks]` |

Strategies and benchmarks are listed in `config/simulation.php`. A run refuses to start on history with gaps,
warmup included, and says where they are; backfill first. Each run stores its report as
`storage/app/simulations/<sha256>.json.gz` and a summary row in `backtest_runs`. The same definition on the same data
gives the same hash (D-42); a different hash on unchanged data and code is a bug.
