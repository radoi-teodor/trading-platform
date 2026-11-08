# Security

The platform holds exchange keys that can move real money, runs agents that read untrusted text, and lives in a
public repository. This document lists what is protected, from whom, and how.

## 1. What must not happen

| Threat | Worst outcome | Main controls |
|--------|---------------|---------------|
| Exchange key leaked | Account drained or traded against the operator | Trade-only keys with no withdrawal right, IP allowlist on the exchange, encrypted at rest, never logged |
| Web UI taken over | Strategies changed, risk raised, account flattened | One account, strong password and TOTP, no registration, re-authentication for sensitive actions, delayed risk increases (D-19) |
| Prompt injection through a source | An agent writes a harmful draft or brief | Agents have no order or limit tools (D-7); drafts must pass gates and operator approval; content labeled untrusted |
| Server-side request forgery | Platform used to reach internal services | Catalog-only hosts, public addresses only, no cross-host redirects |
| Bug places wrong orders | Loss | Risk engine and pre-trade checks, kill switch, exchange-side stops, testnet and paper before live |
| Secret committed to the public repository | Key exposed forever in history | Secrets only in encrypted settings or `.env`, secret scanning in CI, `.gitignore` for data files |

## 2. Exchange keys

- Created by the operator on Binance with trading enabled and **withdrawals disabled**, restricted to the
  server's IP address. A key with withdrawal permission is refused at connect time if the exchange reports it.
- Stored in the database with Laravel encrypted casts (D-20), decrypted only inside the engine process for the
  account that owns it.
- Separate keys per account and per environment (testnet, live). A testnet key never works live and the reverse.
- Rotated at least every 90 days and immediately after any suspicion. Rotation is a runbook procedure.

## 3. Access to the platform

- One operator account (D-5). No registration route. No default or seeded password: the first user is created
  by an artisan command that asks for the password interactively.
- Password plus TOTP. Login throttled. Sessions expire after inactivity.
- Re-authentication (password and TOTP) for: raising a risk limit, promoting to a live stage, adding or replacing
  exchange keys, enabling perpetuals, flattening an account.
- The web process is not exposed on `0.0.0.0` without TLS and a reverse proxy. Production runs with
  `APP_DEBUG=false`.
- The MCP endpoint is read-only, authenticated with a token, and off by default.

## 4. Agents

- Tools are an allowlist per role (`docs/strategy/agents.md`). There is no tool that places, changes or cancels
  orders, changes limits or reads secrets.
- Source text and raw items are wrapped as untrusted data in prompts.
- Outputs are validated against schemas; anything else is rejected.
- Prompts and journal entries never contain secrets, keys, signatures or personal data beyond what a decision
  needs.

## 5. Outbound requests

- Connectors call only hosts declared in `catalog/sources.yaml`, after resolving them to public addresses.
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
