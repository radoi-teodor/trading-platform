# Graph Report - trading-platform  (2026-11-08)

## Corpus Check
- 365 files · ~146,350 words
- Verdict: corpus is large enough that graph structure adds value.
- Unclassified: 92 file(s) not represented in the graph (top: .zip 34, .CHECKSUM 34, (none) 18)

## Summary
- 2926 nodes · 10900 edges · 121 communities (100 shown, 21 thin omitted)
- Extraction: 89% EXTRACTED · 11% INFERRED · 0% AMBIGUOUS · INFERRED: 1158 edges (avg confidence: 0.85)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `adc20c48`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- .of
- EngineTest.php
- Decimal
- Instrument
- SimulatedVenue
- .parse
- InstrumentDefinition
- Position
- .call
- SimulatedVenueTest.php
- .times
- SimulatedPerpVenueTest.php
- Candle
- ExchangeVerificationTest.php
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
- Node
- OrderRole
- .measure
- EnginePerpTest.php
- .klines
- SimClock
- DecimalMathTest.php
- Engine
- App\Strategy\Dsl\Document\DocumentChecker
- Timeframe
- BackfillTest.php
- DecimalMathTest.php
- Money
- .plus
- MarketType
- OrderManagerTest.php
- DocumentChecker.php
- sources
- User
- .handle
- package.json
- App\Support\Timeframe
- BinanceRestClientTest.php
- .build
- .run
- TokensTest.php
- PhrityTransport
- postgres
- RulesHistory
- Percent
- ParamKind
- .sqrt
- GlobalsTest.php
- RunBacktestCommandTest.php
- Asset
- ArchiveImportTest.php
- QualityCheck
- GoldenReportsTest.php
- SimulatedVenue
- IndicatorReferenceTest.php
- Ledger
- CandleRepositoryTest.php
- .minus
- .get
- .now
- CanonicalJson
- scripts
- .klines
- Group
- TypeChecker
- composer.json
- jobs
- postgres
- CandleFeedTest.php
- Window
- StrategyIdentity
- App\Support\Number\Decimal
- pint/composer.json
- Clock
- Quantity
- bootstrap/app.php
- require-dev
- scripts-descriptions
- App\Support\Side
- Venue.php
- config
- uses
- test
- with
- {closure#40}
- run
- EngineMode
- psr-4
- require
- DatabaseSeeder
- steps
- steps
- lint
- extra
- CompiledStrategyTest.php
- .assertSameAsset

## God Nodes (most connected - your core abstractions)
1. `Decimal` - 341 edges
2. `Symbol` - 208 edges
3. `Side` - 183 edges
4. `Candle` - 112 edges
5. `Price` - 109 edges
6. `Timeframe` - 100 edges
7. `MarketType` - 69 edges
8. `SimulatedVenue` - 68 edges
9. `Position` - 68 edges
10. `InstrumentRules` - 64 edges

## Surprising Connections (you probably didn't know these)
- `runIndicator()` --calls--> `Atr`  [INFERRED]
  tests/Unit/Strategy/IndicatorReferenceTest.php → app/Strategy/Indicators/Atr.php
- `runIndicator()` --calls--> `Ema`  [INFERRED]
  tests/Unit/Strategy/IndicatorReferenceTest.php → app/Strategy/Indicators/Ema.php
- `runIndicator()` --calls--> `Sma`  [INFERRED]
  tests/Unit/Strategy/IndicatorReferenceTest.php → app/Strategy/Indicators/Sma.php
- `compileDocument()` --calls--> `PolicyLimits`  [INFERRED]
  tests/Unit/Strategy/Dsl/CompiledStrategyTest.php → app/Strategy/Dsl/Document/PolicyLimits.php
- `playBars()` --calls--> `PositionView`  [INFERRED]
  tests/Unit/Strategy/Dsl/CompiledStrategyTest.php → app/Strategy/Data/PositionView.php

## Import Cycles
- None detected.

## Communities (121 total, 21 thin omitted)

### Community 0 - ".of"
Cohesion: 0.08
Nodes (70): AccountView, RejectionReason, BelowMinimum, DuplicateIntent, GrossExposure, Halted, InvalidStop, InvalidTarget (+62 more)

### Community 1 - "EngineTest.php"
Cohesion: 0.13
Nodes (26): ScriptedStrategy, {closure#1}(), {closure#10}(), {closure#13}(), {closure#14}(), {closure#16}(), {closure#17}(), {closure#18}() (+18 more)

### Community 2 - "Decimal"
Cohesion: 0.04
Nodes (34): {closure#1}(), {closure#1}(), FundingRate, {closure#1}(), Fill, PerpPosition, RoundTrip, RiskEvent (+26 more)

### Community 3 - "Instrument"
Cohesion: 0.08
Nodes (12): CandleRepository, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), CandleSource, Archive, Rest (+4 more)

### Community 4 - "SimulatedVenue"
Cohesion: 0.11
Nodes (14): Liquidity, Maker, Taker, OrderStatus, Cancelled, Expired, Filled, New (+6 more)

### Community 5 - ".parse"
Cohesion: 0.09
Nodes (35): DonchianBreakout, Parameters, carryDecisions(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}() (+27 more)

### Community 6 - "InstrumentDefinition"
Cohesion: 0.18
Nodes (9): InstrumentDefinition, InstrumentStatus, Delisted, Halted, Trading, {closure#3}(), {closure#4}(), {closure#5}() (+1 more)

### Community 7 - "Position"
Cohesion: 0.08
Nodes (15): Argument, BoolLiteral, Call, DurationLiteral, Identifier, NumberLiteral, OnTimeframe, Parameter (+7 more)

### Community 8 - ".call"
Cohesion: 0.12
Nodes (4): CompiledStrategy, {closure#34}(), Compiler, Program

### Community 9 - "SimulatedVenueTest.php"
Cohesion: 0.20
Nodes (42): Side, Buy, Sell, btc(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}() (+34 more)

### Community 10 - ".times"
Cohesion: 0.16
Nodes (4): SimulatedFuturesBook, {closure#3}(), Perpetual, {closure#5}()

### Community 11 - "SimulatedPerpVenueTest.php"
Cohesion: 0.22
Nodes (21): {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#15}(), {closure#18}(), {closure#19}() (+13 more)

### Community 12 - "Candle"
Cohesion: 0.10
Nodes (33): Candle, InvalidCandle, {closure#1}(), {closure#2}(), {closure#1}(), {closure#42}(), {closure#12}(), {closure#13}() (+25 more)

### Community 13 - "ExchangeVerificationTest.php"
Cohesion: 0.09
Nodes (30): {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#2}(), {closure#3}(), {closure#4}() (+22 more)

### Community 14 - "IndicatorsTest.php"
Cohesion: 0.13
Nodes (18): Atr, Ema, Sma, {closure#1}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#15}() (+10 more)

### Community 16 - "Node"
Cohesion: 0.09
Nodes (20): Lexer, Token, TokenKind, Colon, Comma, Dot, Duration, End (+12 more)

### Community 17 - ".of"
Cohesion: 0.16
Nodes (19): {closure#2}(), {closure#1}(), usdt(), {closure#11}(), {closure#3}(), {closure#6}(), {closure#7}(), {closure#8}() (+11 more)

### Community 18 - "App\Strategy\Dsl\Position"
Cohesion: 0.10
Nodes (15): InstrumentDirectory, {closure#5}(), DocumentChecker, PolicyLimits, DslError, YamlList, YamlMap, YamlReader (+7 more)

### Community 19 - "BinanceArchive"
Cohesion: 0.09
Nodes (7): ArchivePeriod, BinanceArchive, Backfill, BackfillReport, BackfillStep, UpsertResult, {closure#2}()

### Community 20 - "LedgerTest.php"
Cohesion: 0.20
Nodes (26): balanceOf(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#15}(), {closure#16}() (+18 more)

### Community 21 - "Illuminate\Database\Schema\Blueprint"
Cohesion: 0.11
Nodes (14): {closure#1}(), {closure#2}(), {closure#3}(), {closure#1}(), {closure#2}(), {closure#1}(), {closure#2}(), {closure#3}() (+6 more)

### Community 22 - "generate.py"
Cohesion: 0.14
Nodes (27): atr(), bars(), bb(), change(), dmi(), donchian(), ema(), highest() (+19 more)

### Community 23 - "ClientOrderId"
Cohesion: 0.08
Nodes (18): ClientOrderId, Income, IncomeKind, Funding, Liquidation, OcoRequest, OrderRequest, OrderType (+10 more)

### Community 24 - "Symbol"
Cohesion: 0.04
Nodes (28): VenuePosition, {closure#4}(), {closure#6}(), {closure#7}(), {closure#1}(), SimulatedPosition, {closure#5}(), BuyAndHold (+20 more)

### Community 25 - ".get"
Cohesion: 0.11
Nodes (8): ArchiveMissing, ChecksumMismatch, BinanceRequestFailed, LocationRestricted, RateLimited, VerificationSource, DailyArchive, Rest

### Community 26 - "ExitReason"
Cohesion: 0.16
Nodes (22): ExitReason, Flatten, Liquidation, Signal, Stop, Target, {closure#1}(), {closure#10}() (+14 more)

### Community 27 - "Node"
Cohesion: 0.19
Nodes (14): Binary, Lookback, Node, Unary, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}() (+6 more)

### Community 28 - "OrderRole"
Cohesion: 0.16
Nodes (7): Venue, OrderAck, ProtectionPlan, OrderManager, InstrumentRules, {closure#1}(), Position

### Community 29 - ".measure"
Cohesion: 0.23
Nodes (6): EquityTracker, Performance, {closure#1}(), {closure#2}(), {closure#5}(), trackedTwoDays()

### Community 30 - "EnginePerpTest.php"
Cohesion: 0.28
Nodes (14): {closure#10}(), {closure#11}(), {closure#14}(), {closure#2}(), {closure#5}(), {closure#6}(), {closure#7}(), {closure#8}() (+6 more)

### Community 31 - ".klines"
Cohesion: 0.16
Nodes (7): Json, KlineMessage, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), streamLines()

### Community 32 - "SimClock"
Cohesion: 0.25
Nodes (11): SimClock, {closure#1}(), {closure#1}(), {closure#2}(), definition(), {closure#23}(), {closure#1}(), {closure#2}() (+3 more)

### Community 33 - "DecimalMathTest.php"
Cohesion: 0.07
Nodes (41): {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#2}(), {closure#3}(), {closure#1}(), {closure#2}() (+33 more)

### Community 34 - "Engine"
Cohesion: 0.10
Nodes (7): DecisionLog, DecisionRecord, EngineRecord, IntentRecord, Engine, ReconciliationMismatch, Verdict

### Community 35 - "App\Strategy\Dsl\Document\DocumentChecker"
Cohesion: 0.13
Nodes (20): {closure#1}(), {closure#7}(), {closure#8}(), {closure#9}(), Expression, DslWriter, {closure#4}(), {closure#1}() (+12 more)

### Community 36 - "Timeframe"
Cohesion: 0.10
Nodes (30): CandleAggregator, CandleSequenceBroken, {closure#4}(), Timeframe, FifteenMinutes, FiveMinutes, FourHours, OneDay (+22 more)

### Community 37 - "BackfillTest.php"
Cohesion: 0.11
Nodes (18): {closure#11}(), {closure#13}(), {closure#14}(), {closure#15}(), {closure#16}(), {closure#4}(), {closure#5}(), {closure#6}() (+10 more)

### Community 38 - "DecimalMathTest.php"
Cohesion: 0.20
Nodes (11): DecimalMath, {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#5}() (+3 more)

### Community 39 - "Money"
Cohesion: 0.14
Nodes (22): FeeSchedule, CostModel, CandleFeed, Backtest, {closure#1}(), {closure#2}(), {closure#1}(), {closure#2}() (+14 more)

### Community 40 - ".plus"
Cohesion: 0.19
Nodes (4): {closure#1}(), Donchian, {closure#16}(), {closure#10}()

### Community 41 - "MarketType"
Cohesion: 0.29
Nodes (10): MarketType, Perp, Spot, {closure#1}(), {closure#3}(), goldenMinutes(), goldenRates(), perpetualReferencePrice() (+2 more)

### Community 42 - "OrderManagerTest.php"
Cohesion: 0.13
Nodes (29): OrderRole, Entry, Exit, Flatten, Stop, Target, TrackedOrder, {closure#1}() (+21 more)

### Community 43 - "DocumentChecker.php"
Cohesion: 0.08
Nodes (27): {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), ParameterSpec, FunctionSpec, Library, Scope (+19 more)

### Community 44 - "sources"
Cohesion: 0.09
Nodes (22): secret, type, sources, auth, channels, connector, exchange, id (+14 more)

### Community 47 - "package.json"
Cohesion: 0.13
Nodes (20): devDependencies, concurrently@^10.0.3, laravel-vite-plugin@^3.1, tailwindcss@^4.0.0, @tailwindcss/vite@^4.0.0, vite@^8.0.0, optionalDependencies, @laravel/multiplex@^0.4.1 (+12 more)

### Community 48 - "App\Support\Timeframe"
Cohesion: 0.10
Nodes (9): StoredInstruments, {closure#3}(), CarryTemplate, CheckResult, {closure#2}(), ExitSpec, StrategySpec, Vwap (+1 more)

### Community 49 - "BinanceRestClientTest.php"
Cohesion: 0.11
Nodes (20): binanceClient(), {closure#1}(), {closure#11}(), {closure#13}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}() (+12 more)

### Community 51 - ".run"
Cohesion: 0.32
Nodes (6): btcMinutes(), {closure#1}(), {closure#2}(), {closure#3}(), referencePrice(), GoldenData

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
Cohesion: 0.32
Nodes (7): RulesHistory, {closure#4}(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), rules()

### Community 56 - "Percent"
Cohesion: 0.24
Nodes (3): Percent, {closure#14}(), {closure#15}()

### Community 57 - "ParamKind"
Cohesion: 0.13
Nodes (12): {closure#1}(), {closure#2}(), ParamKind, Anchor, Count, Duration, Number, Percent (+4 more)

### Community 58 - ".sqrt"
Cohesion: 0.27
Nodes (9): Statistics, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}() (+1 more)

### Community 59 - "GlobalsTest.php"
Cohesion: 0.22
Nodes (4): Controller, {closure#3}(), {closure#4}(), {closure#5}()

### Community 60 - "RunBacktestCommandTest.php"
Cohesion: 0.17
Nodes (14): InstrumentFilter, BacktestRun, backtestFixture(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}() (+6 more)

### Community 61 - "Asset"
Cohesion: 0.16
Nodes (5): Marks, ParticipantResult, Asset, {closure#25}(), {closure#9}()

### Community 62 - "ArchiveImportTest.php"
Cohesion: 0.29
Nodes (10): archiveBytes(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}() (+2 more)

### Community 63 - "QualityCheck"
Cohesion: 0.11
Nodes (9): DatabaseCandleFeed, {closure#1}(), Rows, {closure#1}(), {closure#2}(), QualityCheck, QualityReport, {closure#10}() (+1 more)

### Community 64 - "GoldenReportsTest.php"
Cohesion: 0.14
Nodes (13): PerformanceMetrics, TradeStatistics, Report, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}() (+5 more)

### Community 65 - "SimulatedVenue"
Cohesion: 0.13
Nodes (7): InMemoryDecisionLog, SimulatedVenue, {closure#12}(), {closure#27}(), {closure#32}(), {closure#34}(), EngineHarness

### Community 66 - "IndicatorReferenceTest.php"
Cohesion: 0.06
Nodes (23): {closure#35}(), Bollinger, Highest, Hma, Lowest, Macd, RealizedVolatility, Rsi (+15 more)

### Community 67 - "Ledger"
Cohesion: 0.31
Nodes (5): Ledger, LedgerViolation, {closure#19}(), {closure#12}(), {closure#14}()

### Community 68 - "CandleRepositoryTest.php"
Cohesion: 0.15
Nodes (14): candleStore(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}() (+6 more)

### Community 70 - ".get"
Cohesion: 0.24
Nodes (4): RequestFailed, {closure#1}(), {closure#2}(), {closure#3}()

### Community 71 - ".now"
Cohesion: 0.15
Nodes (8): BackfillMarketData, CheckMarketData, SelectsInstruments, {closure#1}(), StreamCandles, VerifyMarketData, {closure#1}(), Universe

### Community 72 - "CanonicalJson"
Cohesion: 0.24
Nodes (6): CanonicalJson, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}()

### Community 73 - "scripts"
Cohesion: 0.14
Nodes (14): scripts, analyse, dev, format, mutate, post-autoload-dump, post-update-cmd, pre-package-uninstall (+6 more)

### Community 74 - ".klines"
Cohesion: 0.12
Nodes (9): {closure#1}(), {closure#2}(), EpochTime, ExchangeNumber, {closure#1}(), {closure#2}(), {closure#3}(), {closure#1}() (+1 more)

### Community 75 - "Group"
Cohesion: 0.20
Nodes (9): Group, Agent, Exit, Indicator, Order, Perpetual, Position, Price (+1 more)

### Community 76 - "TypeChecker"
Cohesion: 0.17
Nodes (12): AstPrinter, TypeChecker, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), sexpr(), {closure#1}() (+4 more)

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
Cohesion: 0.21
Nodes (14): MinuteCandles, {closure#1}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#2}(), {closure#3}(), {closure#4}() (+6 more)

### Community 81 - "Window"
Cohesion: 0.08
Nodes (7): Change, DonchianChannel, Period, Roc, Stochastic, Vwma, Window

### Community 82 - "StrategyIdentity"
Cohesion: 0.16
Nodes (5): StrategyIdentity, RenamedStrategy, {closure#1}(), {closure#2}(), {closure#3}()

### Community 83 - "App\Support\Number\Decimal"
Cohesion: 0.07
Nodes (43): {closure#5}(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#15}() (+35 more)

### Community 84 - "pint/composer.json"
Cohesion: 0.20
Nodes (9): config, sort-packages, description, license, name, require, laravel/pint, php (+1 more)

### Community 85 - "Clock"
Cohesion: 0.07
Nodes (20): {closure#1}(), {closure#2}(), {closure#3}(), BinanceEndpoints, BinanceRestClient, Sleeper, SystemSleeper, SyncInstruments (+12 more)

### Community 87 - "bootstrap/app.php"
Cohesion: 0.33
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

### Community 88 - "require-dev"
Cohesion: 0.22
Nodes (9): require-dev, fakerphp/faker, larastan/larastan, laravel/pail, laravel/pao, mockery/mockery, nunomaduro/collision, pestphp/pest (+1 more)

### Community 89 - "scripts-descriptions"
Cohesion: 0.22
Nodes (9): scripts-descriptions, analyse, format, mutate, test, test:llm, test:network, test:testnet (+1 more)

### Community 90 - "App\Support\Side"
Cohesion: 0.39
Nodes (3): {closure#6}(), TradeMemory, EntrySpec

### Community 91 - "Venue.php"
Cohesion: 0.29
Nodes (4): Balance, balances(), {closure#22}(), OverReportingVenue

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

### Community 96 - "{closure#40}"
Cohesion: 0.29
Nodes (4): {closure#1}(), {closure#34}(), {closure#40}(), orderUpdate()

### Community 97 - "run"
Cohesion: 0.33
Nodes (6): name, runs-on, steps, audit, run, steps

### Community 98 - "EngineMode"
Cohesion: 0.33
Nodes (5): EngineMode, Backtest, Live, Paper, Testnet

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

### Community 121 - "CompiledStrategyTest.php"
Cohesion: 0.09
Nodes (20): {closure#37}(), BarIndicator, DirectionalMovement, Keltner, Natr, aggregatedBars(), {closure#11}(), {closure#12}() (+12 more)

## Knowledge Gaps
- **117 isolated node(s):** `BelowMinimum`, `DuplicateIntent`, `GrossExposure`, `Halted`, `InvalidStop` (+112 more)
  These have ≤1 connection - possible missing edges. (Counts symbols only; 504 node(s) total have ≤1 connection when file, concept and rationale nodes are included.)
- **21 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `Decimal` connect `Decimal` to `.of`, `EngineTest.php`, `SimulatedVenue`, `.parse`, `InstrumentDefinition`, `Position`, `SimulatedVenueTest.php`, `.times`, `SimulatedPerpVenueTest.php`, `Candle`, `IndicatorsTest.php`, `ForwardingVenue`, `.of`, `LedgerTest.php`, `ClientOrderId`, `Symbol`, `ExitReason`, `Node`, `OrderRole`, `.measure`, `EnginePerpTest.php`, `SimClock`, `DecimalMathTest.php`, `Engine`, `Timeframe`, `DecimalMathTest.php`, `Money`, `.plus`, `MarketType`, `OrderManagerTest.php`, `.handle`, `.run`, `RulesHistory`, `Percent`, `.sqrt`, `RunBacktestCommandTest.php`, `Asset`, `QualityCheck`, `GoldenReportsTest.php`, `SimulatedVenue`, `Ledger`, `CandleRepositoryTest.php`, `.minus`, `CanonicalJson`, `.klines`, `CandleFeedTest.php`, `App\Support\Number\Decimal`, `Clock`, `Quantity`, `Venue.php`, `CompiledStrategyTest.php`, `.minus`?**
  _High betweenness centrality (0.170) - this node is a cross-community bridge._
- **What connects `BelowMinimum`, `DuplicateIntent`, `GrossExposure` to the rest of the system?**
  _117 weakly-connected nodes found - possible missing edges._
- **Should `.of` be split into smaller, more focused modules?**
  _Cohesion score 0.07592385218365062 - nodes in this community are weakly interconnected._
- **Why does `Candle` connect `Candle` to `EngineTest.php`, `Decimal`, `Instrument`, `SimulatedVenue`, `.parse`, `SimulatedVenueTest.php`, `.times`, `SimulatedPerpVenueTest.php`, `ExchangeVerificationTest.php`, `IndicatorsTest.php`, `ClientOrderId`, `Symbol`, `ExitReason`, `EnginePerpTest.php`, `.klines`, `Engine`, `Timeframe`, `Money`, `.plus`, `MarketType`, `OrderManagerTest.php`, `BinanceRestClientTest.php`, `.run`, `QualityCheck`, `SimulatedVenue`, `CandleRepositoryTest.php`, `.klines`, `CandleFeedTest.php`, `Clock`, `CompiledStrategyTest.php`?**
  _High betweenness centrality (0.059) - this node is a cross-community bridge._
- **Should `EngineTest.php` be split into smaller, more focused modules?**
  _Cohesion score 0.12727272727272726 - nodes in this community are weakly interconnected._
- **Why does `Symbol` connect `Symbol` to `.of`, `EngineTest.php`, `Decimal`, `Instrument`, `.parse`, `InstrumentDefinition`, `SimulatedVenueTest.php`, `.times`, `SimulatedPerpVenueTest.php`, `ExchangeVerificationTest.php`, `ForwardingVenue`, `.of`, `LedgerTest.php`, `ClientOrderId`, `ExitReason`, `OrderRole`, `EnginePerpTest.php`, `SimClock`, `Engine`, `BackfillTest.php`, `Money`, `MarketType`, `OrderManagerTest.php`, `.handle`, `App\Support\Timeframe`, `BinanceRestClientTest.php`, `.run`, `RulesHistory`, `RunBacktestCommandTest.php`, `Asset`, `GoldenReportsTest.php`, `SimulatedVenue`, `Ledger`, `CandleRepositoryTest.php`, `.minus`, `.now`, `CanonicalJson`, `CandleFeedTest.php`?**
  _High betweenness centrality (0.058) - this node is a cross-community bridge._
- **Should `Decimal` be split into smaller, more focused modules?**
  _Cohesion score 0.041647597254004576 - nodes in this community are weakly interconnected._