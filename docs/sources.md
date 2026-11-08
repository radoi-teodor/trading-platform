# Sources

Where the agents and the strategies get their information, where that is configured, and the rules a source must
follow.

## 1. Where sources are specified

Three places, each with one job (D-18):

| Place | Holds | Changed by |
|-------|-------|------------|
| `catalog/sources.yaml` | What a source is: kind, connector, endpoint, auth reference, polling, license, trust prior, which roles use it | A commit, reviewed like code |
| Sources screen (database) | Runtime state: enabled, weight override, health, last fetch, error count, learned reliability | The operator in the UI; the platform for health |
| Encrypted settings or `.env` | Secrets named by the catalog (`secret: FRED_API_KEY`) | The operator; never in git |

On deploy, `php artisan sources:sync` loads the catalog into the database. Catalog fields win for catalog
sources; runtime fields are never overwritten. A source the operator adds in the UI is stored with
`origin = operator` and can be written back to the catalog with `php artisan sources:export`.

A source is enabled only after its connector passes a health check (reachable, parses, returns recent data,
respects the declared rate limit). Every entry in the catalog starts as `planned` or `candidate`.

## 2. Kinds

| Kind | Examples | Goes to | Used by |
|------|----------|---------|---------|
| `market` | Exchange candles, trades, ticker, funding, open interest | `Market` tables | Strategies, Analyst, simulation |
| `derivatives` | Options implied volatility indices | `Market` tables | Analyst, strategies |
| `reference` | Market cap, supply, categories, token metadata | Reference tables | Analyst, universe selection |
| `onchain` | Stablecoin supply, TVL, network fees | Feature tables | Analyst |
| `sentiment` | Fear and greed style indices | Feature tables | Analyst |
| `news` | Publisher feeds, aggregators | Raw items, then events | Event Extractor |
| `announcements` | Exchange listings, delistings, maintenance | Raw items, then events | Event Extractor, risk flags |
| `regulatory`, `macro` | Regulator press releases, central bank releases, macro series | Raw items, events, series | Event Extractor, Analyst |

## 3. A catalog entry

```yaml
- id: coindesk-rss                  # unique, lowercase, hyphens
  kind: news
  title: CoinDesk headlines
  connector: rss                    # ccxt | rss | http_json
  url: https://www.coindesk.com/arc/outboundfeeds/rss/
  poll: 5m                          # or stream for WebSocket connectors
  auth: none                        # or {type: header | query_key, secret: NAME}
  rate_limit: 12/h
  language: en
  trust: 0.7                        # prior weight 0..1; the platform learns a reliability score next to it
  license: Headlines and summaries only; link to the original; no full-text storage
  instruments: auto                 # auto = link by the Event Extractor; or an explicit list
  used_by: [event-extractor]
  status: planned                   # planned | candidate | enabled | disabled | retired
  notes: ""
```

## 4. Pipeline

```
connector fetch -> normalize -> deduplicate -> raw item (stored with published_at and received_at)
     market kinds -> Market tables -> features
     text kinds   -> Event Extractor (LLM, schema) -> events -> DSL functions and analyst briefs
```

- Deduplication by canonical URL, then by near-identical title and body within 48 hours. Duplicates raise the
  corroboration count of the first item instead of creating a new one.
- Raw items keep what the license allows (often title, summary, link) and nothing more.
- Events point back to the raw items they came from (`source_item_ids`).

## 5. Trust

Each source has a prior `trust` in the catalog and a learned reliability score: how often its high-impact events
were confirmed by a second source within 24 hours, and how often the predicted direction matched the price move
in the following hours. The Analyst sees both. A source whose reliability falls below 0.3 over 50 events is
proposed for disabling (the operator decides).

## 6. How agents reach sources

Only through tools (`docs/architecture.md`, section 5). The same tools serve the platform's agents and, read-only,
external agents over MCP.

| Tool | Returns |
|------|---------|
| `get_candles(symbol, timeframe, from, to)` | Candles, point in time |
| `get_features(symbol, names, at)` | Indicator and on-chain feature values |
| `get_derivatives(symbol, at)` | Funding, open interest, basis, implied volatility |
| `search_events(filters)` | Structured events with source ids |
| `get_source_items(ids)` | Raw items as stored, marked untrusted |
| `get_brief(universe, at)` | The analyst brief in force at a time |
| `get_portfolio(account)` | Positions and exposure, read-only |
| `compile_strategy(dsl)`, `run_backtest(version, window)` | For the Strategy Author only |

Every result carries the ids of the sources and records it came from. Agents must cite them in their output;
citations are stored in the journal.

## 7. Rules for every source

- **License and terms first.** No source is added without reading its terms. Prefer official APIs and feeds; no
  HTML scraping of sites that do not allow it.
- **Rate limits** are declared and enforced by the connector, with backoff on errors.
- **SSRF guard**: connectors only call hosts declared in the catalog, resolve them to public addresses only, and
  never follow a redirect to another host. The operator cannot point a connector at `localhost`, a private
  network or a cloud metadata address (legacy lesson S3).
- **Untrusted content**: text from a source is data. It is wrapped and labeled in every prompt; agents never
  follow instructions found in it.
- **Identification**: connectors send a plain product User-Agent. Where a publisher requires contact details for
  automated access, the operator supplies them in settings; they are never taken from an account email by
  default.
- **Retention**: raw text 90 days, events and market data indefinitely, unless the license says otherwise.

## 8. Adding a source

1. Read the terms; write the license line.
2. Add the entry to `catalog/sources.yaml` with `status: candidate`.
3. If it needs a new connector type, follow `feature-work`.
4. Run `php artisan sources:check <id>`: reachable, parses, recent data, rate limit respected.
5. Enable it on the Sources screen. Watch its first events in the journal.

## 9. First catalog

The initial list is in `catalog/sources.yaml`. None is enabled yet; endpoints and terms are checked by the
connector health check before any is.

| Group | Sources |
|-------|---------|
| Market | Binance spot, Binance USD-M (public data: funding, open interest, mark price), Bybit (cross-check, later) |
| Derivatives | Deribit public API (volatility index) |
| Reference | CoinGecko |
| On-chain | DefiLlama (TVL, stablecoins), mempool.space (Bitcoin fees) |
| Sentiment | Alternative.me fear and greed index |
| News | CoinDesk, Cointelegraph, Decrypt feeds; CryptoPanic aggregator |
| Announcements | Binance listings and delistings (official channel to be confirmed) |
| Regulatory and macro | SEC press releases, Federal Reserve press releases, FRED series |
