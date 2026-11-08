# Graph Report - trading-platform  (2026-11-08)

## Corpus Check
- 325 files · ~130,171 words
- Verdict: corpus is large enough that graph structure adds value.
- Unclassified: 92 file(s) not represented in the graph (top: .zip 34, .CHECKSUM 34, (none) 18)
- WARNING: 92 of 441 files seen (21%) have no supported extractor and are missing from this graph entirely. It may look complete while representing only part of the codebase.

## Summary
- 2563 nodes · 9542 edges · 132 communities (103 shown, 29 thin omitted)
- Extraction: 89% EXTRACTED · 11% INFERRED · 0% AMBIGUOUS · INFERRED: 1065 edges (avg confidence: 0.85)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `8b476472`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- .of
- EngineTest.php
- Decimal
- CandleRepository
- SimulatedVenue
- .parse
- BinanceRestClient
- Position
- Instrument
- SimulatedVenueTest.php
- .times
- SimulatedPerpVenueTest.php
- Candle
- ExchangeVerificationTest.php
- IndicatorsTest.php
- ForwardingVenue
- TokenKind
- .of
- BinanceArchive.php
- CandleStream
- LedgerTest.php
- Illuminate\Database\Schema\Blueprint
- generate.py
- Price
- Symbol
- .get
- ExitReason
- BuildableStrategy
- OrderRole
- .measure
- EnginePerpTest.php
- Carbon\CarbonImmutable
- SimClock
- DecimalMathTest.php
- Engine
- Asset
- Timeframe
- BackfillTest.php
- OpenLong
- Money
- SimulatedFuturesBook
- .walk
- OrderManagerTest.php
- Node
- sources
- User
- .handle
- package.json
- InstrumentRules
- BinanceRestClientTest.php
- InMemoryDecisionLog
- .expect
- PhrityTransport
- postgres
- RulesHistory
- BinanceArchive
- Subscription
- Parser
- .fromStrings
- RunBacktestCommandTest.php
- Stringable
- ArchiveImportTest.php
- Illuminate\Database\ConnectionInterface
- .run
- Venue
- IndicatorReferenceTest.php
- Ledger
- CandleRepositoryTest.php
- .minus
- TrackedOrder
- InvalidArgumentException
- CanonicalJson
- scripts
- .klines
- VenuePosition
- DslError
- composer.json
- jobs
- postgres
- CandleFeedTest.php
- Window
- StrategyIdentity
- App\Support\Number\Decimal
- pint/composer.json
- .now
- StrategyContext
- bootstrap/app.php
- require-dev
- scripts-descriptions
- .__construct
- GoldenReportsTest.php
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
- HoldStrategy
- extra
- Illuminate\Support\Facades\Http
- runIndicator
- App\Market\Data\Candle
- .zero
- Donchian
- EngineTest.php
- RiskEventKind
- App\Support\Timeframe
- Percent
- {closure#40}
- EngineMode
- Macd
- AssetMismatch

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
- `runIndicator()` --calls--> `Atr`  [INFERRED]
  tests/Unit/Strategy/IndicatorReferenceTest.php → app/Strategy/Indicators/Atr.php
- `runIndicator()` --calls--> `Ema`  [INFERRED]
  tests/Unit/Strategy/IndicatorReferenceTest.php → app/Strategy/Indicators/Ema.php
- `runIndicator()` --calls--> `Sma`  [INFERRED]
  tests/Unit/Strategy/IndicatorReferenceTest.php → app/Strategy/Indicators/Sma.php
- `{closure#4}()` --references--> `Candle`  [EXTRACTED]
  tests/Feature/Market/BinanceRestClientTest.php → app/Market/Data/Candle.php
- `{closure#7}()` --references--> `Candle`  [EXTRACTED]
  tests/Feature/Market/CandleRepositoryTest.php → app/Market/Data/Candle.php

## Import Cycles
- None detected.

## Communities (132 total, 29 thin omitted)

### Community 0 - ".of"
Cohesion: 0.10
Nodes (63): RejectionReason, BelowMinimum, DuplicateIntent, GrossExposure, Halted, InvalidStop, InvalidTarget, LiquidationTooClose (+55 more)

### Community 1 - "EngineTest.php"
Cohesion: 0.19
Nodes (23): ScriptedStrategy, {closure#10}(), {closure#13}(), {closure#14}(), {closure#16}(), {closure#17}(), {closure#2}(), {closure#20}() (+15 more)

### Community 2 - "Decimal"
Cohesion: 0.07
Nodes (24): Balance, RoundTrip, {closure#10}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}() (+16 more)

### Community 3 - "CandleRepository"
Cohesion: 0.14
Nodes (7): BackfillStep, CandleRepository, CandleSource, Archive, Rest, Stream, UpsertResult

### Community 4 - "SimulatedVenue"
Cohesion: 0.15
Nodes (11): Liquidity, Maker, Taker, OrderStatus, Cancelled, Expired, Filled, New (+3 more)

### Community 5 - ".parse"
Cohesion: 0.10
Nodes (38): {closure#1}(), Strategy, {closure#4}(), {closure#5}(), {closure#7}(), carryDecisions(), {closure#10}(), {closure#11}() (+30 more)

### Community 6 - "BinanceRestClient"
Cohesion: 0.10
Nodes (17): BinanceRestClient, {closure#1}(), InstrumentDefinition, InstrumentStatus, Delisted, Halted, Trading, MarketType (+9 more)

### Community 7 - "Position"
Cohesion: 0.07
Nodes (12): Argument, BoolLiteral, Call, DurationLiteral, Identifier, NumberLiteral, Parameter, PercentLiteral (+4 more)

### Community 8 - "Instrument"
Cohesion: 0.17
Nodes (4): {closure#1}(), Instrument, ExchangeVerification, VerificationReport

### Community 9 - "SimulatedVenueTest.php"
Cohesion: 0.16
Nodes (43): OcoRequest, Side, Buy, Sell, btc(), {closure#10}(), {closure#11}(), {closure#12}() (+35 more)

### Community 10 - ".times"
Cohesion: 0.10
Nodes (7): PerpPosition, {closure#3}(), Perpetual, {closure#16}(), {closure#5}(), {closure#6}(), {closure#7}()

### Community 11 - "SimulatedPerpVenueTest.php"
Cohesion: 0.21
Nodes (21): {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#15}(), {closure#18}(), {closure#19}() (+13 more)

### Community 12 - "Candle"
Cohesion: 0.14
Nodes (31): {closure#1}(), {closure#2}(), {closure#1}(), {closure#3}(), Candle, {closure#1}(), {closure#2}(), candleWith() (+23 more)

### Community 13 - "ExchangeVerificationTest.php"
Cohesion: 0.11
Nodes (30): {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#2}(), {closure#3}(), {closure#4}() (+22 more)

### Community 14 - "IndicatorsTest.php"
Cohesion: 0.15
Nodes (16): Atr, Ema, Sma, bar(), {closure#1}(), {closure#12}(), {closure#13}(), {closure#14}() (+8 more)

### Community 15 - "ForwardingVenue"
Cohesion: 0.13
Nodes (4): ForwardingVenue, balances(), {closure#22}(), OverReportingVenue

### Community 16 - "TokenKind"
Cohesion: 0.09
Nodes (20): Lexer, Token, TokenKind, Colon, Comma, Dot, Duration, End (+12 more)

### Community 17 - ".of"
Cohesion: 0.13
Nodes (21): Fill, {closure#2}(), {closure#1}(), usdt(), {closure#11}(), {closure#3}(), {closure#6}(), {closure#7}() (+13 more)

### Community 18 - "BinanceArchive.php"
Cohesion: 0.14
Nodes (10): {closure#1}(), {closure#2}(), {closure#3}(), ExchangeNumber, FundingRate, {closure#1}(), {closure#2}(), {closure#3}() (+2 more)

### Community 19 - "CandleStream"
Cohesion: 0.07
Nodes (10): BinanceEndpoints, Sleeper, SystemSleeper, {closure#1}(), CandleStream, StreamTransport, AppServiceProvider, {closure#1}() (+2 more)

### Community 20 - "LedgerTest.php"
Cohesion: 0.21
Nodes (26): balanceOf(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#15}(), {closure#16}() (+18 more)

### Community 21 - "Illuminate\Database\Schema\Blueprint"
Cohesion: 0.10
Nodes (14): {closure#1}(), {closure#2}(), {closure#3}(), {closure#1}(), {closure#2}(), {closure#1}(), {closure#2}(), {closure#3}() (+6 more)

### Community 22 - "generate.py"
Cohesion: 0.14
Nodes (27): atr(), bars(), bb(), change(), dmi(), donchian(), ema(), highest() (+19 more)

### Community 23 - "Price"
Cohesion: 0.07
Nodes (13): ClientOrderId, OrderRequest, OrderType, Limit, Market, StopMarket, TakeProfitMarket, VenueOrder (+5 more)

### Community 24 - "Symbol"
Cohesion: 0.10
Nodes (9): {closure#7}(), {closure#5}(), {closure#1}(), Close, MoveStop, OpenShort, Symbol, {closure#3}() (+1 more)

### Community 25 - ".get"
Cohesion: 0.15
Nodes (6): LocationRestricted, RateLimited, RequestFailed, {closure#1}(), {closure#2}(), {closure#3}()

### Community 26 - "ExitReason"
Cohesion: 0.18
Nodes (22): ExitReason, Flatten, Liquidation, Signal, Stop, Target, {closure#1}(), {closure#10}() (+14 more)

### Community 28 - "OrderRole"
Cohesion: 0.14
Nodes (11): OrderAck, OrderRole, Entry, Exit, Flatten, Stop, Target, ProtectionPlan (+3 more)

### Community 29 - ".measure"
Cohesion: 0.13
Nodes (15): EquityTracker, Performance, Statistics, {closure#1}(), {closure#2}(), {closure#5}(), trackedTwoDays(), {closure#1}() (+7 more)

### Community 30 - "EnginePerpTest.php"
Cohesion: 0.28
Nodes (14): {closure#10}(), {closure#11}(), {closure#14}(), {closure#2}(), {closure#5}(), {closure#6}(), {closure#7}(), {closure#8}() (+6 more)

### Community 31 - "Carbon\CarbonImmutable"
Cohesion: 0.09
Nodes (11): DecisionLog, DecisionRecord, EngineRecord, {closure#4}(), {closure#6}(), CandleFeed, MinuteCandles, Position (+3 more)

### Community 32 - "SimClock"
Cohesion: 0.14
Nodes (13): SimClock, WallClock, {closure#1}(), {closure#2}(), {closure#3}(), definition(), {closure#1}(), {closure#1}() (+5 more)

### Community 33 - "DecimalMathTest.php"
Cohesion: 0.08
Nodes (36): {closure#41}(), {closure#44}(), {closure#45}(), {closure#46}(), {closure#47}(), {closure#48}(), {closure#24}(), {closure#1}() (+28 more)

### Community 34 - "Engine"
Cohesion: 0.17
Nodes (3): IntentRecord, Engine, ReconciliationMismatch

### Community 35 - "Asset"
Cohesion: 0.17
Nodes (4): Marks, Asset, {closure#25}(), {closure#9}()

### Community 36 - "Timeframe"
Cohesion: 0.12
Nodes (14): {closure#8}(), Timeframe, FifteenMinutes, FiveMinutes, FourHours, OneDay, OneHour, OneMinute (+6 more)

### Community 37 - "BackfillTest.php"
Cohesion: 0.16
Nodes (12): {closure#11}(), {closure#14}(), {closure#15}(), {closure#16}(), {closure#4}(), {closure#6}(), {closure#7}(), {closure#8}() (+4 more)

### Community 39 - "Money"
Cohesion: 0.11
Nodes (24): FeeSchedule, CostModel, RiskProfile, Account, Benchmark, {closure#1}(), RiskPolicy, Backtest (+16 more)

### Community 40 - "SimulatedFuturesBook"
Cohesion: 0.14
Nodes (10): Income, IncomeKind, Funding, Liquidation, OrderUpdate, VenueFill, SimulatedFuturesBook, UpdateStream (+2 more)

### Community 41 - ".walk"
Cohesion: 0.21
Nodes (17): CandleAggregator, CandleSequenceBroken, {closure#6}(), aggregate(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}() (+9 more)

### Community 42 - "OrderManagerTest.php"
Cohesion: 0.30
Nodes (14): btcUsdt(), {closure#10}(), {closure#15}(), {closure#16}(), {closure#2}(), {closure#4}(), {closure#5}(), {closure#6}() (+6 more)

### Community 43 - "Node"
Cohesion: 0.14
Nodes (12): Lookback, Node, OnTimeframe, Unary, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}() (+4 more)

### Community 44 - "sources"
Cohesion: 0.09
Nodes (22): secret, type, sources, auth, channels, connector, exchange, id (+14 more)

### Community 47 - "package.json"
Cohesion: 0.13
Nodes (20): devDependencies, concurrently@^10.0.3, laravel-vite-plugin@^3.1, tailwindcss@^4.0.0, @tailwindcss/vite@^4.0.0, vite@^8.0.0, optionalDependencies, @laravel/multiplex@^0.4.1 (+12 more)

### Community 48 - "InstrumentRules"
Cohesion: 0.20
Nodes (4): InstrumentRules, AccountView, Verdict, RiskEngine

### Community 49 - "BinanceRestClientTest.php"
Cohesion: 0.16
Nodes (17): {closure#13}(), {closure#5}(), binanceClient(), {closure#1}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#2}() (+9 more)

### Community 50 - "InMemoryDecisionLog"
Cohesion: 0.21
Nodes (7): InMemoryDecisionLog, {closure#1}(), {closure#2}(), BacktestResult, ParticipantResult, {closure#27}(), EngineHarness

### Community 52 - ".expect"
Cohesion: 0.07
Nodes (25): Controller, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#3}(), {closure#4}(), {closure#5}() (+17 more)

### Community 53 - "PhrityTransport"
Cohesion: 0.12
Nodes (3): PhrityTransport, StreamFailed, FakeTransport

### Community 54 - "postgres"
Cohesion: 0.10
Nodes (19): POSTGRES_DB, POSTGRES_PASSWORD, POSTGRES_USER, interval, retries, test, timeout, environment (+11 more)

### Community 55 - "RulesHistory"
Cohesion: 0.21
Nodes (10): {closure#1}(), RulesHistory, {closure#2}(), {closure#4}(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}() (+2 more)

### Community 57 - "Subscription"
Cohesion: 0.20
Nodes (3): Intent, Subscription, {closure#1}()

### Community 59 - ".fromStrings"
Cohesion: 0.29
Nodes (8): {closure#1}(), {closure#2}(), {closure#4}(), {closure#5}(), streamMessages(), streamWith(), {closure#1}(), {closure#42}()

### Community 60 - "RunBacktestCommandTest.php"
Cohesion: 0.16
Nodes (15): {closure#1}(), InstrumentFilter, BacktestRun, backtestFixture(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}() (+7 more)

### Community 61 - "Stringable"
Cohesion: 0.15
Nodes (3): Quantity, {closure#10}(), {closure#9}()

### Community 62 - "ArchiveImportTest.php"
Cohesion: 0.29
Nodes (10): archiveBytes(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}() (+2 more)

### Community 63 - "Illuminate\Database\ConnectionInterface"
Cohesion: 0.11
Nodes (10): {closure#2}(), {closure#4}(), DatabaseCandleFeed, {closure#1}(), Heartbeats, Rows, {closure#1}(), QualityCheck (+2 more)

### Community 64 - ".run"
Cohesion: 0.16
Nodes (15): MovingAverageTrend, btcMinutes(), {closure#1}(), {closure#2}(), referencePrice(), {closure#3}(), {closure#6}(), {closure#1}() (+7 more)

### Community 65 - "Venue"
Cohesion: 0.18
Nodes (4): Venue, {closure#12}(), {closure#32}(), {closure#34}()

### Community 66 - "IndicatorReferenceTest.php"
Cohesion: 0.12
Nodes (9): Hma, Wma, assertMatchesReference(), {closure#10}(), {closure#11}(), {closure#13}(), {closure#14}(), {closure#7}() (+1 more)

### Community 67 - "Ledger"
Cohesion: 0.31
Nodes (5): Ledger, LedgerViolation, {closure#19}(), {closure#12}(), {closure#14}()

### Community 68 - "CandleRepositoryTest.php"
Cohesion: 0.19
Nodes (12): candleStore(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}() (+4 more)

### Community 69 - ".minus"
Cohesion: 0.17
Nodes (3): {closure#2}(), {closure#4}(), InvalidNumber

### Community 70 - "TrackedOrder"
Cohesion: 0.23
Nodes (8): TrackedOrder, {closure#1}(), {closure#2}(), OrderBook, {closure#11}(), {closure#12}(), {closure#13}(), {closure#17}()

### Community 71 - "InvalidArgumentException"
Cohesion: 0.09
Nodes (12): ArchiveMissing, ChecksumMismatch, BinanceRequestFailed, CheckMarketData, SelectsInstruments, StreamCandles, SyncInstruments, VerifyMarketData (+4 more)

### Community 72 - "CanonicalJson"
Cohesion: 0.24
Nodes (6): CanonicalJson, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}()

### Community 73 - "scripts"
Cohesion: 0.14
Nodes (14): scripts, analyse, dev, format, mutate, post-autoload-dump, post-update-cmd, pre-package-uninstall (+6 more)

### Community 74 - ".klines"
Cohesion: 0.13
Nodes (11): EpochTime, Json, KlineMessage, {closure#1}(), {closure#2}(), {closure#3}(), {closure#1}(), {closure#2}() (+3 more)

### Community 75 - "VenuePosition"
Cohesion: 0.31
Nodes (4): VenuePosition, {closure#1}(), SimulatedPosition, {closure#13}()

### Community 76 - "DslError"
Cohesion: 0.24
Nodes (7): AstPrinter, DslError, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), sexpr()

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

### Community 81 - "Window"
Cohesion: 0.15
Nodes (4): Change, Roc, StandardDeviation, Window

### Community 82 - "StrategyIdentity"
Cohesion: 0.29
Nodes (4): StrategyIdentity, {closure#1}(), {closure#2}(), {closure#3}()

### Community 83 - "App\Support\Number\Decimal"
Cohesion: 0.16
Nodes (5): Bollinger, Keltner, Rsi, Supertrend, Wilder

### Community 84 - "pint/composer.json"
Cohesion: 0.20
Nodes (9): config, sort-packages, description, license, name, require, laravel/pint, php (+1 more)

### Community 85 - ".now"
Cohesion: 0.20
Nodes (5): Backfill, BackfillReport, BackfillMarketData, FundingRateRepository, Clock

### Community 86 - "StrategyContext"
Cohesion: 0.15
Nodes (4): FundingCarry, OpenCarry, PositionView, StrategyContext

### Community 87 - "bootstrap/app.php"
Cohesion: 0.33
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

### Community 88 - "require-dev"
Cohesion: 0.22
Nodes (9): require-dev, fakerphp/faker, larastan/larastan, laravel/pail, laravel/pao, mockery/mockery, nunomaduro/collision, pestphp/pest (+1 more)

### Community 89 - "scripts-descriptions"
Cohesion: 0.22
Nodes (9): scripts-descriptions, analyse, format, mutate, test, test:llm, test:network, test:testnet (+1 more)

### Community 91 - "GoldenReportsTest.php"
Cohesion: 0.16
Nodes (7): PerformanceMetrics, Report, ReportBuilder, {closure#2}(), {closure#8}(), {closure#9}(), goldenRuns()

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

### Community 120 - "runIndicator"
Cohesion: 0.13
Nodes (5): DonchianChannel, Highest, Lowest, Stochastic, runIndicator()

### Community 121 - "App\Market\Data\Candle"
Cohesion: 0.16
Nodes (5): DirectionalMovement, Natr, Vwma, {closure#8}(), {closure#9}()

### Community 123 - "Donchian"
Cohesion: 0.20
Nodes (4): Donchian, Period, {closure#10}(), {closure#15}()

### Community 124 - "EngineTest.php"
Cohesion: 0.14
Nodes (3): {closure#1}(), {closure#18}(), {closure#37}()

### Community 125 - "RiskEventKind"
Cohesion: 0.24
Nodes (6): RiskEvent, RiskEventKind, DailyLossHalt, DrawdownFlatten, DrawdownHalt, WeeklyLossHalt

### Community 126 - "App\Support\Timeframe"
Cohesion: 0.27
Nodes (3): RealizedVolatility, Vwap, {closure#16}()

### Community 128 - "{closure#40}"
Cohesion: 0.29
Nodes (4): {closure#1}(), {closure#34}(), {closure#40}(), orderUpdate()

### Community 129 - "EngineMode"
Cohesion: 0.33
Nodes (5): EngineMode, Backtest, Live, Paper, Testnet

## Knowledge Gaps
- **86 isolated node(s):** `BelowMinimum`, `DuplicateIntent`, `GrossExposure`, `Halted`, `InvalidStop` (+81 more)
  These have ≤1 connection - possible missing edges. (Counts symbols only; 453 node(s) total have ≤1 connection when file, concept and rationale nodes are included.)
- **29 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `Decimal` connect `Decimal` to `.of`, `EngineTest.php`, `SimulatedVenue`, `.parse`, `BinanceRestClient`, `Position`, `Instrument`, `SimulatedVenueTest.php`, `.times`, `SimulatedPerpVenueTest.php`, `Candle`, `IndicatorsTest.php`, `ForwardingVenue`, `.of`, `BinanceArchive.php`, `LedgerTest.php`, `Price`, `ExitReason`, `BuildableStrategy`, `OrderRole`, `.measure`, `EnginePerpTest.php`, `Carbon\CarbonImmutable`, `SimClock`, `DecimalMathTest.php`, `Engine`, `Asset`, `Timeframe`, `Money`, `SimulatedFuturesBook`, `.walk`, `OrderManagerTest.php`, `Node`, `.handle`, `InstrumentRules`, `InMemoryDecisionLog`, `.expect`, `RulesHistory`, `RunBacktestCommandTest.php`, `Stringable`, `Illuminate\Database\ConnectionInterface`, `.run`, `Ledger`, `.minus`, `TrackedOrder`, `InvalidArgumentException`, `CanonicalJson`, `VenuePosition`, `CandleFeedTest.php`, `App\Support\Number\Decimal`, `StrategyContext`, `.__construct`, `GoldenReportsTest.php`, `.__construct`, `App\Market\Data\Candle`, `.zero`, `Donchian`, `EngineTest.php`, `RiskEventKind`, `Percent`?**
  _High betweenness centrality (0.165) - this node is a cross-community bridge._
- **What connects `BelowMinimum`, `DuplicateIntent`, `GrossExposure` to the rest of the system?**
  _86 weakly-connected nodes found - possible missing edges._
- **Should `.of` be split into smaller, more focused modules?**
  _Cohesion score 0.10329507589781563 - nodes in this community are weakly interconnected._
- **Why does `Symbol` connect `Symbol` to `.of`, `Decimal`, `SimulatedVenue`, `.parse`, `BinanceRestClient`, `Instrument`, `SimulatedVenueTest.php`, `.times`, `SimulatedPerpVenueTest.php`, `ExchangeVerificationTest.php`, `ForwardingVenue`, `.of`, `BinanceArchive.php`, `LedgerTest.php`, `Price`, `ExitReason`, `BuildableStrategy`, `OrderRole`, `EnginePerpTest.php`, `Carbon\CarbonImmutable`, `SimClock`, `Engine`, `Asset`, `BackfillTest.php`, `OpenLong`, `Money`, `SimulatedFuturesBook`, `OrderManagerTest.php`, `.handle`, `InstrumentRules`, `InMemoryDecisionLog`, `RulesHistory`, `Subscription`, `.fromStrings`, `RunBacktestCommandTest.php`, `Stringable`, `Illuminate\Database\ConnectionInterface`, `.run`, `Venue`, `Ledger`, `CandleRepositoryTest.php`, `TrackedOrder`, `InvalidArgumentException`, `CanonicalJson`, `VenuePosition`, `CandleFeedTest.php`, `StrategyContext`, `.__construct`, `GoldenReportsTest.php`, `HoldStrategy`, `EngineTest.php`?**
  _High betweenness centrality (0.073) - this node is a cross-community bridge._
- **Should `Decimal` be split into smaller, more focused modules?**
  _Cohesion score 0.06693803708729082 - nodes in this community are weakly interconnected._
- **Why does `Timeframe` connect `Timeframe` to `EngineTest.php`, `CandleRepository`, `.parse`, `BinanceRestClient`, `Instrument`, `SimulatedVenueTest.php`, `SimulatedPerpVenueTest.php`, `Candle`, `IndicatorsTest.php`, `BinanceArchive.php`, `CandleStream`, `Symbol`, `ExitReason`, `BuildableStrategy`, `EnginePerpTest.php`, `OpenLong`, `.walk`, `OrderManagerTest.php`, `Node`, `BinanceArchive`, `Subscription`, `.fromStrings`, `Illuminate\Database\ConnectionInterface`, `.run`, `CandleRepositoryTest.php`, `.klines`, `CandleFeedTest.php`, `StrategyContext`, `.__construct`, `.__construct`, `HoldStrategy`, `App\Market\Data\Candle`, `EngineTest.php`, `App\Support\Timeframe`?**
  _High betweenness centrality (0.039) - this node is a cross-community bridge._
- **Should `CandleRepository` be split into smaller, more focused modules?**
  _Cohesion score 0.1383399209486166 - nodes in this community are weakly interconnected._