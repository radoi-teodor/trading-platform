# Graph Report - trading-platform  (2026-11-08)

## Corpus Check
- cluster-only mode — file stats not available

## Summary
- 2416 nodes · 9107 edges · 119 communities (94 shown, 25 thin omitted)
- Extraction: 89% EXTRACTED · 11% INFERRED · 0% AMBIGUOUS · INFERRED: 1015 edges (avg confidence: 0.85)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `9e723e93`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- .of
- EngineTest.php
- Decimal
- Instrument
- SimulatedVenue
- .parse
- BinanceRestClient
- Position
- MarketType
- SimulatedVenueTest.php
- .times
- SimulatedPerpVenueTest.php
- Candle
- ExchangeVerificationTest.php
- IndicatorsTest.php
- OrderAck
- TokenKind
- .of
- BinanceArchive.php
- BinanceArchive
- LedgerTest.php
- Illuminate\Database\Schema\Blueprint
- RiskPolicy
- Side
- Symbol
- FundingRate
- ExitReason
- StrategyContext
- OrderRole
- .measure
- .of
- Price
- SimClock
- DecimalMathTest.php
- Engine
- Asset
- Timeframe
- BackfillTest.php
- OpenLong
- BacktestTest.php
- SimulatedFuturesBook
- .walk
- OrderManagerTest.php
- Node
- sources
- User
- .handle
- package.json
- Carbon\CarbonImmutable
- BinanceRestClientTest.php
- Engine.php
- FeeSchedule
- .expect
- PhrityTransport
- postgres
- RulesHistory
- Report
- .standardDeviation
- Parser
- CandleStreamTest.php
- RunBacktestCommandTest.php
- Stringable
- ArchiveImportTest.php
- Rows
- .run
- MinuteCandles
- CandleStream
- Ledger
- CandleRepositoryTest.php
- .zero
- TrackedOrder
- .count
- CanonicalJson
- scripts
- Instrument.php
- Subscription
- DslError
- composer.json
- jobs
- postgres
- CandleFeedTest.php
- DonchianBreakout
- StrategyIdentity
- TokensTest.php
- pint/composer.json
- ArchivePeriod
- FundingCarry
- bootstrap/app.php
- require-dev
- scripts-descriptions
- .__construct
- Fill
- config
- uses
- test
- with
- .__construct
- run
- QualityReport
- psr-4
- require
- DatabaseSeeder
- steps
- steps
- lint
- Illuminate\Foundation\Testing\RefreshDatabase
- extra

## God Nodes (most connected - your core abstractions)
1. `Decimal` - 354 edges
2. `Symbol` - 208 edges
3. `Side` - 183 edges
4. `Candle` - 114 edges
5. `Price` - 109 edges
6. `Timeframe` - 95 edges
7. `SimulatedVenue` - 68 edges
8. `Position` - 68 edges
9. `MarketType` - 66 edges
10. `InstrumentRules` - 64 edges

## Surprising Connections (you probably didn't know these)
- `{closure#4}()` --references--> `Candle`  [EXTRACTED]
  tests/Feature/Market/BinanceRestClientTest.php → app/Market/Data/Candle.php
- `{closure#7}()` --references--> `Candle`  [EXTRACTED]
  tests/Feature/Market/CandleRepositoryTest.php → app/Market/Data/Candle.php
- `{closure#12}()` --references--> `Candle`  [EXTRACTED]
  tests/Unit/Market/CandleAggregatorTest.php → app/Market/Data/Candle.php
- `{closure#13}()` --references--> `Candle`  [EXTRACTED]
  tests/Unit/Market/CandleAggregatorTest.php → app/Market/Data/Candle.php
- `{closure#5}()` --references--> `Candle`  [EXTRACTED]
  tests/Unit/Simulation/BacktestTest.php → app/Market/Data/Candle.php

## Import Cycles
- None detected.

## Communities (119 total, 25 thin omitted)

### Community 0 - ".of"
Cohesion: 0.08
Nodes (63): AccountView, RejectionReason, BelowMinimum, DuplicateIntent, GrossExposure, Halted, InvalidStop, InvalidTarget (+55 more)

### Community 1 - "EngineTest.php"
Cohesion: 0.07
Nodes (46): InMemoryDecisionLog, Policies, ScriptedStrategy, {closure#10}(), {closure#11}(), {closure#13}(), {closure#14}(), {closure#2}() (+38 more)

### Community 2 - "Decimal"
Cohesion: 0.05
Nodes (22): {closure#1}(), {closure#2}(), RoundTrip, {closure#3}(), {closure#4}(), {closure#10}(), {closure#2}(), {closure#3}() (+14 more)

### Community 3 - "Instrument"
Cohesion: 0.06
Nodes (16): Backfill, BackfillReport, BackfillStep, CandleRepository, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}() (+8 more)

### Community 4 - "SimulatedVenue"
Cohesion: 0.10
Nodes (19): ClientOrderId, Liquidity, Maker, Taker, OrderStatus, Cancelled, Expired, Filled (+11 more)

### Community 5 - ".parse"
Cohesion: 0.11
Nodes (35): Strategy, {closure#5}(), carryDecisions(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}() (+27 more)

### Community 6 - "BinanceRestClient"
Cohesion: 0.08
Nodes (16): BinanceRestClient, {closure#1}(), Json, RequestFailed, InstrumentDefinition, InstrumentStatus, Delisted, Halted (+8 more)

### Community 7 - "Position"
Cohesion: 0.07
Nodes (12): Argument, BoolLiteral, Call, DurationLiteral, Identifier, NumberLiteral, Parameter, PercentLiteral (+4 more)

### Community 8 - "MarketType"
Cohesion: 0.10
Nodes (14): BackfillMarketData, CheckMarketData, SelectsInstruments, StreamCandles, SyncInstruments, VerifyMarketData, MarketType, Perp (+6 more)

### Community 9 - "SimulatedVenueTest.php"
Cohesion: 0.21
Nodes (37): btc(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#15}(), {closure#16}(), {closure#17}() (+29 more)

### Community 11 - "SimulatedPerpVenueTest.php"
Cohesion: 0.21
Nodes (21): {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#15}(), {closure#18}(), {closure#19}() (+13 more)

### Community 12 - "Candle"
Cohesion: 0.13
Nodes (31): {closure#1}(), {closure#1}(), {closure#2}(), Candle, {closure#1}(), {closure#2}(), {closure#1}(), {closure#42}() (+23 more)

### Community 13 - "ExchangeVerificationTest.php"
Cohesion: 0.11
Nodes (29): {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}() (+21 more)

### Community 14 - "IndicatorsTest.php"
Cohesion: 0.11
Nodes (19): Atr, Ema, Period, Sma, bar(), {closure#1}(), {closure#12}(), {closure#13}() (+11 more)

### Community 15 - "OrderAck"
Cohesion: 0.08
Nodes (8): Venue, OrderAck, ForwardingVenue, {closure#12}(), {closure#22}(), {closure#32}(), {closure#34}(), OverReportingVenue

### Community 16 - "TokenKind"
Cohesion: 0.09
Nodes (20): Lexer, Token, TokenKind, Colon, Comma, Dot, Duration, End (+12 more)

### Community 17 - ".of"
Cohesion: 0.12
Nodes (18): AssetMismatch, Money, {closure#2}(), {closure#1}(), usdt(), {closure#6}(), {closure#1}(), {closure#11}() (+10 more)

### Community 18 - "BinanceArchive.php"
Cohesion: 0.10
Nodes (14): {closure#2}(), EpochTime, ExchangeNumber, KlineMessage, {closure#1}(), {closure#2}(), {closure#3}(), {closure#1}() (+6 more)

### Community 19 - "BinanceArchive"
Cohesion: 0.10
Nodes (7): BinanceArchive, BinanceEndpoints, Sleeper, SystemSleeper, AppServiceProvider, {closure#1}(), {closure#2}()

### Community 20 - "LedgerTest.php"
Cohesion: 0.21
Nodes (25): balanceOf(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#15}(), {closure#16}() (+17 more)

### Community 21 - "Illuminate\Database\Schema\Blueprint"
Cohesion: 0.11
Nodes (14): {closure#1}(), {closure#2}(), {closure#3}(), {closure#1}(), {closure#2}(), {closure#1}(), {closure#2}(), {closure#3}() (+6 more)

### Community 22 - "RiskPolicy"
Cohesion: 0.11
Nodes (18): RiskProfile, Account, Benchmark, {closure#1}(), RiskPolicy, {closure#2}(), Participant, {closure#1}() (+10 more)

### Community 23 - "Side"
Cohesion: 0.11
Nodes (15): OcoRequest, OrderRequest, OrderType, Limit, Market, StopMarket, TakeProfitMarket, Side (+7 more)

### Community 24 - "Symbol"
Cohesion: 0.10
Nodes (11): VenuePosition, {closure#6}(), {closure#7}(), {closure#1}(), SimulatedPosition, {closure#1}(), {closure#5}(), {closure#1}() (+3 more)

### Community 25 - "FundingRate"
Cohesion: 0.09
Nodes (13): ArchiveMissing, ChecksumMismatch, {closure#3}(), BinanceRequestFailed, LocationRestricted, RateLimited, CandleSource, Archive (+5 more)

### Community 26 - "ExitReason"
Cohesion: 0.17
Nodes (24): ExitReason, Flatten, Liquidation, Signal, Stop, Target, {closure#1}(), {closure#10}() (+16 more)

### Community 27 - "StrategyContext"
Cohesion: 0.09
Nodes (7): MovingAverageTrend, Close, OpenShort, StrategyContext, EnterOnceStrategy, {closure#18}(), {closure#37}()

### Community 28 - "OrderRole"
Cohesion: 0.16
Nodes (8): OrderRole, Entry, Exit, Flatten, Stop, Target, OrderManager, {closure#14}()

### Community 29 - ".measure"
Cohesion: 0.17
Nodes (8): EquityTracker, Performance, {closure#1}(), {closure#2}(), {closure#3}(), {closure#5}(), {closure#8}(), trackedTwoDays()

### Community 30 - ".of"
Cohesion: 0.12
Nodes (21): {closure#41}(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#13}(), {closure#14}(), {closure#15}(), {closure#16}() (+13 more)

### Community 31 - "Price"
Cohesion: 0.12
Nodes (9): ProtectionPlan, {closure#1}(), PerpPosition, Position, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}() (+1 more)

### Community 32 - "SimClock"
Cohesion: 0.13
Nodes (15): SimClock, WallClock, {closure#1}(), {closure#1}(), {closure#2}(), {closure#3}(), definition(), {closure#1}() (+7 more)

### Community 33 - "DecimalMathTest.php"
Cohesion: 0.14
Nodes (15): DecimalMath, InvalidNumber, {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}() (+7 more)

### Community 34 - "Engine"
Cohesion: 0.23
Nodes (4): EngineRecord, IntentRecord, Engine, ReconciliationMismatch

### Community 35 - "Asset"
Cohesion: 0.12
Nodes (7): Balance, Marks, ParticipantResult, Asset, balances(), {closure#25}(), {closure#9}()

### Community 36 - "Timeframe"
Cohesion: 0.12
Nodes (14): {closure#8}(), Timeframe, FifteenMinutes, FiveMinutes, FourHours, OneDay, OneHour, OneMinute (+6 more)

### Community 37 - "BackfillTest.php"
Cohesion: 0.14
Nodes (13): {closure#11}(), {closure#14}(), {closure#15}(), {closure#16}(), {closure#4}(), {closure#6}(), {closure#7}(), {closure#8}() (+5 more)

### Community 38 - "OpenLong"
Cohesion: 0.09
Nodes (4): BuyAndHold, BuildableStrategy, OpenLong, HoldStrategy

### Community 39 - "BacktestTest.php"
Cohesion: 0.14
Nodes (11): CandleFeed, Backtest, {closure#2}(), ReportBuilder, InMemoryCandleFeed, {closure#10}(), {closure#4}(), {closure#5}() (+3 more)

### Community 40 - "SimulatedFuturesBook"
Cohesion: 0.13
Nodes (10): Income, IncomeKind, Funding, Liquidation, OrderUpdate, SimulatedFuturesBook, UpdateStream, {closure#3}() (+2 more)

### Community 41 - ".walk"
Cohesion: 0.19
Nodes (17): CandleAggregator, CandleSequenceBroken, {closure#6}(), aggregate(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}() (+9 more)

### Community 42 - "OrderManagerTest.php"
Cohesion: 0.28
Nodes (15): btcUsdt(), {closure#10}(), {closure#12}(), {closure#15}(), {closure#16}(), {closure#2}(), {closure#4}(), {closure#5}() (+7 more)

### Community 43 - "Node"
Cohesion: 0.14
Nodes (12): Lookback, Node, OnTimeframe, Unary, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}() (+4 more)

### Community 44 - "sources"
Cohesion: 0.09
Nodes (22): secret, type, sources, auth, channels, connector, exchange, id (+14 more)

### Community 47 - "package.json"
Cohesion: 0.13
Nodes (20): devDependencies, concurrently@^10.0.3, laravel-vite-plugin@^3.1, tailwindcss@^4.0.0, @tailwindcss/vite@^4.0.0, vite@^8.0.0, optionalDependencies, @laravel/multiplex@^0.4.1 (+12 more)

### Community 48 - "Carbon\CarbonImmutable"
Cohesion: 0.14
Nodes (8): RiskEvent, RiskEventKind, DailyLossHalt, DrawdownFlatten, DrawdownHalt, WeeklyLossHalt, BacktestResult, PositionView

### Community 49 - "BinanceRestClientTest.php"
Cohesion: 0.17
Nodes (12): binanceClient(), {closure#1}(), {closure#11}(), {closure#13}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}() (+4 more)

### Community 50 - "Engine.php"
Cohesion: 0.12
Nodes (8): DecisionLog, DecisionRecord, EngineMode, Backtest, Live, Paper, Testnet, {closure#4}()

### Community 51 - "FeeSchedule"
Cohesion: 0.17
Nodes (15): FeeSchedule, CostModel, RunDefinition, {closure#34}(), {closure#40}(), {closure#44}(), {closure#45}(), {closure#46}() (+7 more)

### Community 52 - ".expect"
Cohesion: 0.13
Nodes (11): Controller, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#3}(), {closure#4}(), {closure#5}() (+3 more)

### Community 53 - "PhrityTransport"
Cohesion: 0.12
Nodes (3): PhrityTransport, StreamFailed, FakeTransport

### Community 54 - "postgres"
Cohesion: 0.10
Nodes (19): POSTGRES_DB, POSTGRES_PASSWORD, POSTGRES_USER, interval, retries, test, timeout, environment (+11 more)

### Community 55 - "RulesHistory"
Cohesion: 0.17
Nodes (12): {closure#1}(), RulesHistory, {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#4}(), {closure#1}() (+4 more)

### Community 56 - "Report"
Cohesion: 0.19
Nodes (9): PerformanceMetrics, Report, {closure#1}(), {closure#3}(), goldenMinutes(), goldenRates(), perpetualReferencePrice(), referenceMean() (+1 more)

### Community 57 - ".standardDeviation"
Cohesion: 0.29
Nodes (9): Statistics, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}() (+1 more)

### Community 59 - "CandleStreamTest.php"
Cohesion: 0.17
Nodes (13): {closure#13}(), {closure#5}(), {closure#12}(), {closure#2}(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}() (+5 more)

### Community 60 - "RunBacktestCommandTest.php"
Cohesion: 0.21
Nodes (12): backtestFixture(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}() (+4 more)

### Community 61 - "Stringable"
Cohesion: 0.15
Nodes (3): Quantity, {closure#10}(), {closure#9}()

### Community 62 - "ArchiveImportTest.php"
Cohesion: 0.26
Nodes (10): archiveBytes(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}() (+2 more)

### Community 63 - "Rows"
Cohesion: 0.24
Nodes (5): DatabaseCandleFeed, {closure#1}(), Rows, {closure#10}(), {closure#11}()

### Community 64 - ".run"
Cohesion: 0.25
Nodes (8): btcMinutes(), {closure#1}(), {closure#2}(), {closure#3}(), referencePrice(), {closure#3}(), {closure#6}(), GoldenData

### Community 65 - "MinuteCandles"
Cohesion: 0.14
Nodes (4): MinuteCandles, {closure#14}(), {closure#3}(), {closure#7}()

### Community 66 - "CandleStream"
Cohesion: 0.22
Nodes (3): {closure#1}(), CandleStream, StreamTransport

### Community 67 - "Ledger"
Cohesion: 0.29
Nodes (5): Ledger, LedgerViolation, {closure#19}(), {closure#12}(), {closure#14}()

### Community 68 - "CandleRepositoryTest.php"
Cohesion: 0.28
Nodes (11): candleStore(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}() (+3 more)

### Community 70 - "TrackedOrder"
Cohesion: 0.37
Nodes (7): TrackedOrder, {closure#1}(), {closure#2}(), OrderBook, {closure#11}(), {closure#13}(), {closure#17}()

### Community 71 - ".count"
Cohesion: 0.24
Nodes (3): {closure#1}(), SyncResult, QualityCheck

### Community 72 - "CanonicalJson"
Cohesion: 0.24
Nodes (6): CanonicalJson, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}()

### Community 73 - "scripts"
Cohesion: 0.14
Nodes (14): scripts, analyse, dev, format, mutate, post-autoload-dump, post-update-cmd, pre-package-uninstall (+6 more)

### Community 74 - "Instrument.php"
Cohesion: 0.22
Nodes (3): {closure#1}(), InstrumentFilter, BacktestRun

### Community 75 - "Subscription"
Cohesion: 0.20
Nodes (3): Intent, Subscription, {closure#1}()

### Community 76 - "DslError"
Cohesion: 0.24
Nodes (6): AstPrinter, DslError, {closure#1}(), {closure#2}(), {closure#3}(), sexpr()

### Community 77 - "composer.json"
Cohesion: 0.17
Nodes (11): autoload-dev, psr-4, description, keywords, license, minimum-stability, name, prefer-stable (+3 more)

### Community 78 - "jobs"
Cohesion: 0.17
Nodes (11): concurrency, cancel-in-progress, group, jobs, name, on, pull_request, push (+3 more)

### Community 79 - "postgres"
Cohesion: 0.17
Nodes (12): POSTGRES_DB, POSTGRES_PASSWORD, POSTGRES_USER, env, image, options, ports, image (+4 more)

### Community 80 - "CandleFeedTest.php"
Cohesion: 0.44
Nodes (11): {closure#1}(), {closure#12}(), {closure#13}(), {closure#2}(), {closure#4}(), {closure#5}(), {closure#8}(), {closure#9}() (+3 more)

### Community 81 - "DonchianBreakout"
Cohesion: 0.29
Nodes (4): DonchianBreakout, Donchian, {closure#10}(), {closure#15}()

### Community 82 - "StrategyIdentity"
Cohesion: 0.33
Nodes (4): StrategyIdentity, {closure#1}(), {closure#2}(), {closure#3}()

### Community 83 - "TokensTest.php"
Cohesion: 0.31
Nodes (7): {closure#3}(), {closure#4}(), {closure#5}(), contrastRatio(), contrastRules(), relativeLuminance(), themeTokens()

### Community 84 - "pint/composer.json"
Cohesion: 0.20
Nodes (9): config, sort-packages, description, license, name, require, laravel/pint, php (+1 more)

### Community 87 - "bootstrap/app.php"
Cohesion: 0.33
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

### Community 88 - "require-dev"
Cohesion: 0.22
Nodes (9): require-dev, fakerphp/faker, larastan/larastan, laravel/pail, laravel/pao, mockery/mockery, nunomaduro/collision, pestphp/pest (+1 more)

### Community 89 - "scripts-descriptions"
Cohesion: 0.22
Nodes (9): scripts-descriptions, analyse, format, mutate, test, test:llm, test:network, test:testnet (+1 more)

### Community 92 - "config"
Cohesion: 0.29
Nodes (7): pestphp/pest-plugin, php-http/discovery, config, allow-plugins, optimize-autoloader, preferred-install, sort-packages

### Community 93 - "uses"
Cohesion: 0.29
Nodes (7): GITHUB_TOKEN, secrets, name, runs-on, steps, env, uses

### Community 94 - "test"
Cohesion: 0.29
Nodes (7): test, php, fail-fast, matrix, name, runs-on, strategy

### Community 95 - "with"
Cohesion: 0.29
Nodes (7): with, cache, coverage, extensions, fetch-depth, node-version, php-version

### Community 97 - "run"
Cohesion: 0.33
Nodes (6): name, runs-on, steps, audit, run, steps

### Community 99 - "psr-4"
Cohesion: 0.40
Nodes (5): autoload, psr-4, App\\, Database\\Factories\\, Database\\Seeders\\

### Community 100 - "require"
Cohesion: 0.40
Nodes (5): require, laravel/framework, laravel/tinker, php, phrity/websocket

### Community 103 - "steps"
Cohesion: 0.50
Nodes (4): name, runs-on, steps, analyse

### Community 104 - "steps"
Cohesion: 0.50
Nodes (4): name, runs-on, steps, frontend

### Community 105 - "lint"
Cohesion: 0.50
Nodes (4): lint, name, runs-on, steps

### Community 108 - "extra"
Cohesion: 0.67
Nodes (3): extra, laravel, dont-discover

## Knowledge Gaps
- **86 isolated node(s):** `BelowMinimum`, `DuplicateIntent`, `GrossExposure`, `Halted`, `InvalidStop` (+81 more)
  These have ≤1 connection - possible missing edges. (Counts symbols only; 431 node(s) total have ≤1 connection when file, concept and rationale nodes are included.)
- **25 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `Decimal` connect `Decimal` to `.of`, `EngineTest.php`, `SimulatedVenue`, `.parse`, `BinanceRestClient`, `Position`, `MarketType`, `SimulatedVenueTest.php`, `.times`, `SimulatedPerpVenueTest.php`, `Candle`, `IndicatorsTest.php`, `OrderAck`, `.of`, `BinanceArchive.php`, `LedgerTest.php`, `RiskPolicy`, `Side`, `Symbol`, `FundingRate`, `ExitReason`, `StrategyContext`, `OrderRole`, `.measure`, `.of`, `Price`, `SimClock`, `DecimalMathTest.php`, `Engine`, `Asset`, `BacktestTest.php`, `SimulatedFuturesBook`, `.walk`, `OrderManagerTest.php`, `Node`, `.handle`, `Carbon\CarbonImmutable`, `Engine.php`, `FeeSchedule`, `RulesHistory`, `Report`, `.standardDeviation`, `RunBacktestCommandTest.php`, `Stringable`, `Rows`, `.run`, `MinuteCandles`, `Ledger`, `.zero`, `TrackedOrder`, `.count`, `CanonicalJson`, `Instrument.php`, `CandleFeedTest.php`, `DonchianBreakout`, `FundingCarry`, `.__construct`, `Fill`, `.__construct`?**
  _High betweenness centrality (0.180) - this node is a cross-community bridge._
- **What connects `BelowMinimum`, `DuplicateIntent`, `GrossExposure` to the rest of the system?**
  _86 weakly-connected nodes found - possible missing edges._
- **Should `.of` be split into smaller, more focused modules?**
  _Cohesion score 0.08480649784997611 - nodes in this community are weakly interconnected._
- **Why does `Symbol` connect `Symbol` to `.of`, `EngineTest.php`, `Decimal`, `Instrument`, `SimulatedVenue`, `.parse`, `BinanceRestClient`, `MarketType`, `SimulatedVenueTest.php`, `.times`, `SimulatedPerpVenueTest.php`, `ExchangeVerificationTest.php`, `OrderAck`, `.of`, `LedgerTest.php`, `RiskPolicy`, `Side`, `FundingRate`, `ExitReason`, `StrategyContext`, `OrderRole`, `.measure`, `Price`, `SimClock`, `Engine`, `Asset`, `BackfillTest.php`, `OpenLong`, `BacktestTest.php`, `SimulatedFuturesBook`, `OrderManagerTest.php`, `.handle`, `Carbon\CarbonImmutable`, `Engine.php`, `FeeSchedule`, `RulesHistory`, `Report`, `CandleStreamTest.php`, `RunBacktestCommandTest.php`, `Stringable`, `.run`, `MinuteCandles`, `Ledger`, `CandleRepositoryTest.php`, `.zero`, `TrackedOrder`, `CanonicalJson`, `Instrument.php`, `Subscription`, `CandleFeedTest.php`, `DonchianBreakout`, `FundingCarry`, `.__construct`, `Fill`?**
  _High betweenness centrality (0.059) - this node is a cross-community bridge._
- **Should `EngineTest.php` be split into smaller, more focused modules?**
  _Cohesion score 0.07473684210526316 - nodes in this community are weakly interconnected._
- **Why does `Timeframe` connect `Timeframe` to `EngineTest.php`, `Decimal`, `Instrument`, `.parse`, `BinanceRestClient`, `MarketType`, `SimulatedVenueTest.php`, `SimulatedPerpVenueTest.php`, `Candle`, `IndicatorsTest.php`, `BinanceArchive.php`, `Symbol`, `FundingRate`, `ExitReason`, `StrategyContext`, `Price`, `OpenLong`, `.walk`, `OrderManagerTest.php`, `Node`, `CandleStreamTest.php`, `CandleRepositoryTest.php`, `Subscription`, `CandleFeedTest.php`, `DonchianBreakout`, `FundingCarry`, `.__construct`, `.__construct`?**
  _High betweenness centrality (0.048) - this node is a cross-community bridge._
- **Should `Decimal` be split into smaller, more focused modules?**
  _Cohesion score 0.054563492063492064 - nodes in this community are weakly interconnected._