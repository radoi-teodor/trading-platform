# Graph Report - trading-platform  (2026-11-08)

## Corpus Check
- 356 files · ~141,514 words
- Verdict: corpus is large enough that graph structure adds value.
- Unclassified: 92 file(s) not represented in the graph (top: .zip 34, .CHECKSUM 34, (none) 18)

## Summary
- 2788 nodes · 10312 edges · 126 communities (101 shown, 25 thin omitted)
- Extraction: 89% EXTRACTED · 11% INFERRED · 0% AMBIGUOUS · INFERRED: 1114 edges (avg confidence: 0.85)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `e9316efd`
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
- ExchangeVerification.php
- SimulatedVenueTest.php
- .times
- SimulatedPerpVenueTest.php
- Candle
- QualityCheckTest.php
- IndicatorsTest.php
- ForwardingVenue
- Node
- .of
- App\Strategy\Dsl\Position
- BinanceArchive
- LedgerTest.php
- Illuminate\Database\Schema\Blueprint
- generate.py
- ClientOrderId
- Symbol
- .get
- ExitReason
- YamlMap
- OrderRole
- .measure
- EnginePerpTest.php
- Engine.php
- SimClock
- DecimalMathTest.php
- Engine
- .expect
- Timeframe
- BackfillTest.php
- DecimalMathTest.php
- Money
- Price
- .walk
- OrderManagerTest.php
- DocumentChecker.php
- sources
- User
- .handle
- package.json
- App\Support\Timeframe
- BinanceRestClientTest.php
- .build
- TokensTest.php
- PhrityTransport
- postgres
- RulesHistory
- ExchangeVerificationTest.php
- ParamKind
- .sqrt
- GlobalsTest.php
- RunBacktestCommandTest.php
- Asset
- ArchiveImportTest.php
- Illuminate\Database\ConnectionInterface
- .run
- .zero
- IndicatorReferenceTest.php
- Ledger
- CandleRepositoryTest.php
- .minus
- TrackedOrder
- .count
- CanonicalJson
- scripts
- .klines
- Side
- DslError
- composer.json
- jobs
- postgres
- CandleFeedTest.php
- Window
- StrategyIdentity
- App\Support\Number\Decimal
- pint/composer.json
- Clock
- Carbon\CarbonImmutable
- bootstrap/app.php
- require-dev
- scripts-descriptions
- .__construct
- Venue.php
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
- RiskProfile
- extra
- .push
- runIndicator
- App\Market\Data\Candle
- Fill
- .isFull
- .times
- .assertSameAsset

## God Nodes (most connected - your core abstractions)
1. `Decimal` - 354 edges
2. `Symbol` - 208 edges
3. `Side` - 183 edges
4. `Candle` - 114 edges
5. `Price` - 109 edges
6. `Timeframe` - 96 edges
7. `MarketType` - 69 edges
8. `SimulatedVenue` - 68 edges
9. `Position` - 68 edges
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
- `runIndicator()` --calls--> `Atr`  [INFERRED]
  tests/Unit/Strategy/IndicatorReferenceTest.php → app/Strategy/Indicators/Atr.php

## Import Cycles
- None detected.

## Communities (126 total, 25 thin omitted)

### Community 0 - ".of"
Cohesion: 0.06
Nodes (74): InstrumentRules, {closure#1}(), AccountView, RejectionReason, BelowMinimum, DuplicateIntent, GrossExposure, Halted (+66 more)

### Community 1 - "EngineTest.php"
Cohesion: 0.12
Nodes (28): ScriptedStrategy, {closure#1}(), {closure#10}(), {closure#13}(), {closure#14}(), {closure#16}(), {closure#17}(), {closure#18}() (+20 more)

### Community 2 - "Decimal"
Cohesion: 0.06
Nodes (26): {closure#1}(), {closure#2}(), RoundTrip, {closure#1}(), {closure#10}(), {closure#2}(), {closure#4}(), {closure#5}() (+18 more)

### Community 3 - "Instrument"
Cohesion: 0.08
Nodes (12): Backfill, {closure#3}(), BackfillReport, BackfillStep, BackfillMarketData, CandleSource, Archive, Rest (+4 more)

### Community 4 - "SimulatedVenue"
Cohesion: 0.16
Nodes (10): Liquidity, Maker, Taker, OrderStatus, Cancelled, Expired, Filled, New (+2 more)

### Community 5 - ".parse"
Cohesion: 0.10
Nodes (35): {closure#1}(), Strategy, carryDecisions(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}() (+27 more)

### Community 6 - "BinanceRestClient"
Cohesion: 0.11
Nodes (15): BinanceRestClient, {closure#1}(), Json, InstrumentDefinition, InstrumentStatus, Delisted, Halted, Trading (+7 more)

### Community 7 - "Position"
Cohesion: 0.07
Nodes (17): Argument, BoolLiteral, Call, DurationLiteral, Identifier, Lookback, NumberLiteral, OnTimeframe (+9 more)

### Community 9 - "SimulatedVenueTest.php"
Cohesion: 0.19
Nodes (39): btc(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#15}(), {closure#16}() (+31 more)

### Community 10 - ".times"
Cohesion: 0.12
Nodes (4): SimulatedFuturesBook, Perpetual, {closure#6}(), {closure#7}()

### Community 11 - "SimulatedPerpVenueTest.php"
Cohesion: 0.22
Nodes (22): {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#15}(), {closure#18}() (+14 more)

### Community 12 - "Candle"
Cohesion: 0.12
Nodes (32): {closure#1}(), {closure#1}(), {closure#2}(), {closure#1}(), {closure#3}(), Candle, {closure#1}(), {closure#42}() (+24 more)

### Community 13 - "QualityCheckTest.php"
Cohesion: 0.19
Nodes (16): {closure#2}(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#2}(), {closure#4}() (+8 more)

### Community 14 - "IndicatorsTest.php"
Cohesion: 0.13
Nodes (19): Atr, Donchian, Ema, Sma, bar(), {closure#1}(), {closure#10}(), {closure#12}() (+11 more)

### Community 16 - "Node"
Cohesion: 0.09
Nodes (31): Binary, Node, Lexer, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}() (+23 more)

### Community 17 - ".of"
Cohesion: 0.18
Nodes (18): {closure#2}(), {closure#1}(), usdt(), {closure#11}(), {closure#6}(), {closure#7}(), {closure#1}(), {closure#11}() (+10 more)

### Community 18 - "App\Strategy\Dsl\Position"
Cohesion: 0.14
Nodes (10): DslError, YamlList, YamlReader, YamlScalar, YamlValue, {closure#1}(), {closure#2}(), {closure#3}() (+2 more)

### Community 19 - "BinanceArchive"
Cohesion: 0.13
Nodes (5): BinanceArchive, BinanceEndpoints, AppServiceProvider, {closure#1}(), {closure#2}()

### Community 20 - "LedgerTest.php"
Cohesion: 0.22
Nodes (25): balanceOf(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#15}(), {closure#16}() (+17 more)

### Community 21 - "Illuminate\Database\Schema\Blueprint"
Cohesion: 0.11
Nodes (14): {closure#1}(), {closure#2}(), {closure#3}(), {closure#1}(), {closure#2}(), {closure#1}(), {closure#2}(), {closure#3}() (+6 more)

### Community 22 - "generate.py"
Cohesion: 0.14
Nodes (27): atr(), bars(), bb(), change(), dmi(), donchian(), ema(), highest() (+19 more)

### Community 23 - "ClientOrderId"
Cohesion: 0.12
Nodes (10): ClientOrderId, OrderUpdate, VenueFill, VenueOrder, {closure#2}(), {closure#3}(), {closure#1}(), {closure#2}() (+2 more)

### Community 24 - "Symbol"
Cohesion: 0.06
Nodes (13): {closure#5}(), BuyAndHold, DonchianBreakout, BuildableStrategy, Close, OpenLong, OpenShort, Subscription (+5 more)

### Community 25 - ".get"
Cohesion: 0.09
Nodes (9): ArchiveMissing, ChecksumMismatch, BinanceRequestFailed, LocationRestricted, RateLimited, RequestFailed, {closure#1}(), {closure#2}() (+1 more)

### Community 26 - "ExitReason"
Cohesion: 0.15
Nodes (23): ExitReason, Flatten, Liquidation, Signal, Stop, Target, {closure#1}(), {closure#10}() (+15 more)

### Community 28 - "OrderRole"
Cohesion: 0.19
Nodes (8): OrderAck, OrderRole, Entry, Exit, Flatten, Stop, Target, OrderManager

### Community 29 - ".measure"
Cohesion: 0.18
Nodes (8): EquityTracker, Performance, {closure#1}(), {closure#2}(), {closure#3}(), {closure#5}(), {closure#8}(), trackedTwoDays()

### Community 30 - "EnginePerpTest.php"
Cohesion: 0.28
Nodes (14): {closure#10}(), {closure#11}(), {closure#14}(), {closure#2}(), {closure#5}(), {closure#6}(), {closure#7}(), {closure#8}() (+6 more)

### Community 31 - "Engine.php"
Cohesion: 0.12
Nodes (5): DecisionLog, DecisionRecord, {closure#6}(), {closure#7}(), OpenCarry

### Community 32 - "SimClock"
Cohesion: 0.13
Nodes (14): SimClock, WallClock, {closure#1}(), {closure#1}(), {closure#1}(), {closure#2}(), definition(), {closure#1}() (+6 more)

### Community 33 - "DecimalMathTest.php"
Cohesion: 0.07
Nodes (39): {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#2}(), {closure#3}(), {closure#1}(), {closure#2}() (+31 more)

### Community 34 - "Engine"
Cohesion: 0.16
Nodes (5): EngineRecord, IntentRecord, Engine, ReconciliationMismatch, Intent

### Community 35 - ".expect"
Cohesion: 0.17
Nodes (13): CheckResult, {closure#1}(), EntrySpec, checkDocument(), {closure#1}(), {closure#2}(), {closure#4}(), {closure#5}() (+5 more)

### Community 36 - "Timeframe"
Cohesion: 0.11
Nodes (14): {closure#8}(), Timeframe, FifteenMinutes, FiveMinutes, FourHours, OneDay, OneHour, OneMinute (+6 more)

### Community 37 - "BackfillTest.php"
Cohesion: 0.12
Nodes (19): {closure#11}(), {closure#13}(), {closure#14}(), {closure#15}(), {closure#16}(), {closure#4}(), {closure#5}(), {closure#6}() (+11 more)

### Community 38 - "DecimalMathTest.php"
Cohesion: 0.17
Nodes (14): DecimalMath, {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#2}() (+6 more)

### Community 39 - "Money"
Cohesion: 0.13
Nodes (23): FeeSchedule, CostModel, CandleFeed, RiskPolicy, Backtest, {closure#2}(), Participant, {closure#1}() (+15 more)

### Community 40 - "Price"
Cohesion: 0.09
Nodes (10): Income, IncomeKind, Funding, Liquidation, ProtectionPlan, {closure#1}(), PerpPosition, Position (+2 more)

### Community 41 - ".walk"
Cohesion: 0.21
Nodes (17): CandleAggregator, CandleSequenceBroken, {closure#6}(), aggregate(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}() (+9 more)

### Community 42 - "OrderManagerTest.php"
Cohesion: 0.33
Nodes (15): btcUsdt(), {closure#10}(), {closure#14}(), {closure#15}(), {closure#16}(), {closure#2}(), {closure#4}(), {closure#5}() (+7 more)

### Community 43 - "DocumentChecker.php"
Cohesion: 0.05
Nodes (49): {closure#3}(), {closure#4}(), {closure#5}(), {closure#7}(), {closure#8}(), {closure#9}(), Expression, ParameterSpec (+41 more)

### Community 44 - "sources"
Cohesion: 0.09
Nodes (22): secret, type, sources, auth, channels, connector, exchange, id (+14 more)

### Community 45 - "User"
Cohesion: 0.10
Nodes (3): User, UserFactory, {closure#1}()

### Community 47 - "package.json"
Cohesion: 0.13
Nodes (20): devDependencies, concurrently@^10.0.3, laravel-vite-plugin@^3.1, tailwindcss@^4.0.0, @tailwindcss/vite@^4.0.0, vite@^8.0.0, optionalDependencies, @laravel/multiplex@^0.4.1 (+12 more)

### Community 48 - "App\Support\Timeframe"
Cohesion: 0.14
Nodes (6): InstrumentDirectory, StoredInstruments, CarryTemplate, {closure#2}(), Vwap, KnownInstruments

### Community 49 - "BinanceRestClientTest.php"
Cohesion: 0.08
Nodes (23): Sleeper, SystemSleeper, binanceClient(), {closure#1}(), {closure#11}(), {closure#13}(), {closure#3}(), {closure#4}() (+15 more)

### Community 50 - ".build"
Cohesion: 0.11
Nodes (11): EngineMode, Backtest, Live, Paper, Testnet, InMemoryDecisionLog, {closure#1}(), {closure#2}() (+3 more)

### Community 52 - "TokensTest.php"
Cohesion: 0.31
Nodes (7): {closure#3}(), {closure#4}(), {closure#5}(), contrastRatio(), contrastRules(), relativeLuminance(), themeTokens()

### Community 53 - "PhrityTransport"
Cohesion: 0.09
Nodes (4): PhrityTransport, StreamFailed, StreamTransport, FakeTransport

### Community 54 - "postgres"
Cohesion: 0.10
Nodes (19): POSTGRES_DB, POSTGRES_PASSWORD, POSTGRES_USER, interval, retries, test, timeout, environment (+11 more)

### Community 55 - "RulesHistory"
Cohesion: 0.28
Nodes (8): RulesHistory, {closure#4}(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), rules(), {closure#10}()

### Community 56 - "ExchangeVerificationTest.php"
Cohesion: 0.19
Nodes (13): {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}() (+5 more)

### Community 57 - "ParamKind"
Cohesion: 0.12
Nodes (12): {closure#1}(), {closure#2}(), ParamKind, Anchor, Count, Duration, Number, Percent (+4 more)

### Community 58 - ".sqrt"
Cohesion: 0.30
Nodes (9): Statistics, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}() (+1 more)

### Community 59 - "GlobalsTest.php"
Cohesion: 0.22
Nodes (4): Controller, {closure#3}(), {closure#4}(), {closure#5}()

### Community 60 - "RunBacktestCommandTest.php"
Cohesion: 0.14
Nodes (15): {closure#1}(), InstrumentFilter, BacktestRun, backtestFixture(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}() (+7 more)

### Community 61 - "Asset"
Cohesion: 0.09
Nodes (7): Marks, Asset, Quantity, {closure#25}(), {closure#9}(), {closure#10}(), {closure#9}()

### Community 62 - "ArchiveImportTest.php"
Cohesion: 0.17
Nodes (11): ArchivePeriod, archiveBytes(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}() (+3 more)

### Community 63 - "Illuminate\Database\ConnectionInterface"
Cohesion: 0.18
Nodes (7): {closure#2}(), {closure#4}(), DatabaseCandleFeed, {closure#1}(), Rows, {closure#10}(), {closure#11}()

### Community 64 - ".run"
Cohesion: 0.11
Nodes (25): Report, MovingAverageTrend, btcMinutes(), {closure#1}(), {closure#2}(), {closure#3}(), referencePrice(), {closure#1}() (+17 more)

### Community 65 - ".zero"
Cohesion: 0.10
Nodes (7): Venue, SimulatedVenue, {closure#12}(), {closure#22}(), {closure#32}(), {closure#34}(), {closure#40}()

### Community 66 - "IndicatorReferenceTest.php"
Cohesion: 0.09
Nodes (13): Highest, Hma, Macd, Wma, assertMatchesReference(), {closure#10}(), {closure#11}(), {closure#13}() (+5 more)

### Community 67 - "Ledger"
Cohesion: 0.26
Nodes (5): Ledger, LedgerViolation, {closure#19}(), {closure#12}(), {closure#14}()

### Community 68 - "CandleRepositoryTest.php"
Cohesion: 0.28
Nodes (11): candleStore(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}() (+3 more)

### Community 70 - "TrackedOrder"
Cohesion: 0.28
Nodes (9): TrackedOrder, {closure#4}(), {closure#1}(), {closure#2}(), OrderBook, {closure#11}(), {closure#12}(), {closure#13}() (+1 more)

### Community 71 - ".count"
Cohesion: 0.11
Nodes (10): CheckMarketData, SelectsInstruments, {closure#1}(), StreamCandles, VerifyMarketData, Universe, QualityCheck, VerificationSource (+2 more)

### Community 72 - "CanonicalJson"
Cohesion: 0.24
Nodes (6): CanonicalJson, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}()

### Community 73 - "scripts"
Cohesion: 0.14
Nodes (14): scripts, analyse, dev, format, mutate, post-autoload-dump, post-update-cmd, pre-package-uninstall (+6 more)

### Community 74 - ".klines"
Cohesion: 0.10
Nodes (14): {closure#2}(), EpochTime, ExchangeNumber, KlineMessage, {closure#1}(), {closure#2}(), {closure#3}(), {closure#1}() (+6 more)

### Community 75 - "Side"
Cohesion: 0.10
Nodes (18): OcoRequest, OrderRequest, OrderType, Limit, Market, StopMarket, TakeProfitMarket, VenuePosition (+10 more)

### Community 76 - "DslError"
Cohesion: 0.36
Nodes (6): AstPrinter, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), sexpr()

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
Cohesion: 0.12
Nodes (4): Change, Period, Roc, Window

### Community 82 - "StrategyIdentity"
Cohesion: 0.23
Nodes (4): StrategyIdentity, {closure#1}(), {closure#2}(), {closure#3}()

### Community 83 - "App\Support\Number\Decimal"
Cohesion: 0.12
Nodes (7): ExitSpec, PolicyLimits, StrategySpec, Bollinger, Rsi, Supertrend, Wilder

### Community 84 - "pint/composer.json"
Cohesion: 0.20
Nodes (9): config, sort-packages, description, license, name, require, laravel/pint, php (+1 more)

### Community 85 - "Clock"
Cohesion: 0.14
Nodes (9): CandleRepository, SyncInstruments, FundingRateRepository, Heartbeats, {closure#1}(), InstrumentSync, SyncResult, CandleStream (+1 more)

### Community 86 - "Carbon\CarbonImmutable"
Cohesion: 0.15
Nodes (8): FundingRate, MinuteCandles, FundingCarry, PositionView, StrategyContext, {closure#14}(), {closure#3}(), {closure#7}()

### Community 87 - "bootstrap/app.php"
Cohesion: 0.33
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

### Community 88 - "require-dev"
Cohesion: 0.22
Nodes (9): require-dev, fakerphp/faker, larastan/larastan, laravel/pail, laravel/pao, mockery/mockery, nunomaduro/collision, pestphp/pest (+1 more)

### Community 89 - "scripts-descriptions"
Cohesion: 0.22
Nodes (9): scripts-descriptions, analyse, format, mutate, test, test:llm, test:network, test:testnet (+1 more)

### Community 91 - "Venue.php"
Cohesion: 0.29
Nodes (3): Balance, balances(), OverReportingVenue

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

### Community 106 - "RiskProfile"
Cohesion: 0.33
Nodes (3): RiskProfile, Account, Benchmark

### Community 108 - "extra"
Cohesion: 0.67
Nodes (3): extra, laravel, dont-discover

### Community 120 - "runIndicator"
Cohesion: 0.14
Nodes (3): DonchianChannel, Lowest, Stochastic

### Community 121 - "App\Market\Data\Candle"
Cohesion: 0.17
Nodes (5): DirectionalMovement, Keltner, Natr, {closure#8}(), {closure#9}()

### Community 126 - ".isFull"
Cohesion: 0.20
Nodes (3): RealizedVolatility, StandardDeviation, {closure#16}()

### Community 127 - ".times"
Cohesion: 0.15
Nodes (3): {closure#3}(), {closure#3}(), Percent

## Knowledge Gaps
- **117 isolated node(s):** `Price`, `Indicator`, `Perpetual`, `Agent`, `Position` (+112 more)
  These have ≤1 connection - possible missing edges. (Counts symbols only; 495 node(s) total have ≤1 connection when file, concept and rationale nodes are included.)
- **25 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `Decimal` connect `Decimal` to `.of`, `EngineTest.php`, `Instrument`, `SimulatedVenue`, `.parse`, `BinanceRestClient`, `Position`, `SimulatedVenueTest.php`, `.times`, `SimulatedPerpVenueTest.php`, `Candle`, `IndicatorsTest.php`, `ForwardingVenue`, `Node`, `.of`, `LedgerTest.php`, `ClientOrderId`, `Symbol`, `ExitReason`, `OrderRole`, `.measure`, `EnginePerpTest.php`, `Engine.php`, `DecimalMathTest.php`, `Engine`, `Timeframe`, `DecimalMathTest.php`, `Money`, `Price`, `.walk`, `OrderManagerTest.php`, `.handle`, `BinanceRestClientTest.php`, `.build`, `RulesHistory`, `.sqrt`, `RunBacktestCommandTest.php`, `Asset`, `.run`, `.zero`, `Ledger`, `.minus`, `TrackedOrder`, `.count`, `CanonicalJson`, `.klines`, `Side`, `CandleFeedTest.php`, `App\Support\Number\Decimal`, `Clock`, `Carbon\CarbonImmutable`, `.__construct`, `Venue.php`, `.__construct`, `RiskProfile`, `App\Market\Data\Candle`, `Fill`, `.times`?**
  _High betweenness centrality (0.207) - this node is a cross-community bridge._
- **What connects `Price`, `Indicator`, `Perpetual` to the rest of the system?**
  _117 weakly-connected nodes found - possible missing edges._
- **Should `.of` be split into smaller, more focused modules?**
  _Cohesion score 0.06472060050041702 - nodes in this community are weakly interconnected._
- **Why does `Candle` connect `Candle` to `EngineTest.php`, `Decimal`, `Instrument`, `SimulatedVenue`, `.parse`, `BinanceRestClient`, `ExchangeVerification.php`, `SimulatedVenueTest.php`, `.times`, `SimulatedPerpVenueTest.php`, `QualityCheckTest.php`, `IndicatorsTest.php`, `ClientOrderId`, `ExitReason`, `EnginePerpTest.php`, `Engine.php`, `Engine`, `Timeframe`, `Money`, `Price`, `.walk`, `OrderManagerTest.php`, `BinanceRestClientTest.php`, `Illuminate\Database\ConnectionInterface`, `.run`, `.zero`, `CandleRepositoryTest.php`, `.klines`, `CandleFeedTest.php`, `Clock`, `Carbon\CarbonImmutable`, `.__construct`, `App\Market\Data\Candle`?**
  _High betweenness centrality (0.040) - this node is a cross-community bridge._
- **Should `EngineTest.php` be split into smaller, more focused modules?**
  _Cohesion score 0.12303422756706753 - nodes in this community are weakly interconnected._
- **Why does `Symbol` connect `Symbol` to `.of`, `EngineTest.php`, `Decimal`, `Instrument`, `.parse`, `BinanceRestClient`, `SimulatedVenueTest.php`, `.times`, `SimulatedPerpVenueTest.php`, `QualityCheckTest.php`, `ForwardingVenue`, `LedgerTest.php`, `ClientOrderId`, `ExitReason`, `OrderRole`, `.measure`, `EnginePerpTest.php`, `Engine.php`, `Engine`, `BackfillTest.php`, `Money`, `Price`, `OrderManagerTest.php`, `.handle`, `App\Support\Timeframe`, `BinanceRestClientTest.php`, `.build`, `RulesHistory`, `ExchangeVerificationTest.php`, `RunBacktestCommandTest.php`, `Asset`, `.run`, `.zero`, `Ledger`, `CandleRepositoryTest.php`, `TrackedOrder`, `.count`, `CanonicalJson`, `Side`, `CandleFeedTest.php`, `Carbon\CarbonImmutable`, `.__construct`, `Venue.php`, `Fill`?**
  _High betweenness centrality (0.039) - this node is a cross-community bridge._
- **Should `Decimal` be split into smaller, more focused modules?**
  _Cohesion score 0.05737704918032787 - nodes in this community are weakly interconnected._