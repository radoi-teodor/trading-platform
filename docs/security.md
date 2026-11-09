# Security

The platform holds exchange keys that can move real money, takes calls from research agents over MCP, and lives
in a public repository. This document lists what is protected, from whom, and how.

## 1. What must not happen

| Threat | Worst outcome | Main controls |
|--------|---------------|---------------|
| Exchange key leaked | Account drained or traded against the operator | Trade-only keys with no withdrawal right, IP allowlist on the exchange, encrypted at rest, never logged |
| Web UI taken over | Strategies changed, risk raised, account flattened | One account, strong password and TOTP, no registration, re-authentication for sensitive actions, delayed risk increases (D-19) |
| MCP token stolen or agent misled | Strategies read, CPU spent on backtests, harmful drafts | Endpoint off by default, hashed and revocable tokens, rate limit, no order, limit or promotion tool (D-7, D-63); drafts must pass gates and operator approval |
| Bug places wrong orders | Loss | Risk engine and pre-trade checks, kill switch, exchange-side stops, testnet and paper before live |
| Secret committed to the public repository | Key exposed forever in history | Secrets only in encrypted settings or `.env`, secret scanning in CI, `.gitignore` for data files |

## 2. Exchange keys

- Created by the operator on Binance with trading enabled and **withdrawals disabled**, restricted to the
  server's IP address. A key with withdrawal permission is refused at connect time if the exchange reports it.
- Stored in the database with Laravel encrypted casts (D-20), decrypted only inside the engine process for the
  account that owns it.
- Separate keys per account and per environment (testnet, live). A testnet key never works live and the reverse.
- Rotated at least every 90 days and immediately after any suspicion. Rotation is a runbook procedure.
- Entered only through `php artisan account:keys <account>` (asked interactively, never as an argument) or the
  Account screen after re-authentication. Never through chat, a file in the repository or an environment variable.
- The one exception: the opt-in testnet suite reads a testnet key from `TRADING_TESTNET_KEY` and
  `TRADING_TESTNET_SECRET` in the shell that runs it; testnet keys carry no money (D-74).
- The Telegram bot token and chat id live in `.env` (`TELEGRAM_BOT_TOKEN`, `TELEGRAM_CHAT_ID`), never in the
  repository, the journal or a message.

## 3. Access to the platform

- One operator account (D-5). No registration route. No default or seeded password: the first user is created
  by an artisan command that asks for the password interactively.
- Password plus TOTP. Login throttled. Sessions expire after inactivity.
- Built with Laravel Fortify: the first user comes from `php artisan user:create`, which asks for the password and
  shows the TOTP secret once; there is no registration or password reset by email. Re-authentication asks for the
  password and a fresh TOTP code and lasts 10 minutes.
- Re-authentication (password and TOTP) for: raising a risk limit, promoting to a live stage, adding or replacing
  exchange keys, enabling perpetuals, flattening an account.
- The web process is not exposed on `0.0.0.0` without TLS and a reverse proxy. Production runs with
  `APP_DEBUG=false`.
- The MCP endpoint (D-63) is off by default and authenticated with a bearer token stored only as a SHA-256 hash,
  revocable, limited to 60 calls a minute and two queued simulation runs, every call recorded without the token.
  It reads everything an agent may read and writes only draft strategy versions and simulation runs: it has no tool
  that places, changes or cancels orders, changes a limit or moves a strategy's stage. It serves the operator's own
  network; exposed beyond it, it sits behind TLS and the reverse proxy like the web process.

## 4. Agents

- The platform runs no language model and holds no model API key (D-64). Agents reach it only through the MCP
  endpoint, whose tools are a fixed list (`docs/strategy/agents.md`). There is no tool that places, changes or
  cancels orders, changes limits, moves a stage or reads secrets.
- Journal entries and the MCP call log never contain secrets, keys, tokens or signatures.

## 5. Outbound requests

- The platform calls only Binance (REST, WebSocket and the public archives) and the notification hosts.
- Notification webhooks are restricted to known hosts (Telegram API, the configured mail server). The response
  body of an outbound call is never shown back to the user verbatim.

## 6. Repository

- The repository is public. Nothing under `storage/`, no database file, no `.env`, no logs, no exports of the
  journal are committed. CI runs a secret scanner on every push.
- The legacy SQLite file was committed with an exchange test key in it. The operator revokes that key, and the
  file is removed from the tip in Phase 0 (D-22). History is not rewritten.
- Dependencies are pinned by `composer.lock` and `package-lock.json`, updated deliberately, and checked by
  `composer audit` and `npm audit` in CI. No runtime CDN scripts (D-3).

## 7. Data

- Database backups daily, encrypted, kept 30 days, restore tested monthly (runbook).
- The journal is append-only in the application; corrections are new entries that reference the old ones.

## 8. Reporting a problem

A suspected key leak or unexplained order: halt the account first (`php artisan engine:halt <account>`), then
follow the incident section of `docs/runbook.md`.
