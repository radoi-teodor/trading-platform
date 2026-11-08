# Graph Report - trading-platform  (2026-11-08)

## Corpus Check
- 388 files · ~157,000 words
- Verdict: corpus is large enough that graph structure adds value.
- Unclassified: 92 file(s) not represented in the graph (top: .zip 34, .CHECKSUM 34, (none) 18)

## Summary
- 3177 nodes · 11588 edges · 132 communities (106 shown, 26 thin omitted)
- Extraction: 90% EXTRACTED · 10% INFERRED · 0% AMBIGUOUS · INFERRED: 1216 edges (avg confidence: 0.85)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `76418358`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- .of
- EngineTest.php
- App\Support\Number\Decimal
- Instrument
- SimulatedVenue
- .parse
- MarketType
- Position
- CompiledStrategy.php
- SimulatedVenueTest.php
- App\Support\Symbol
- SimulatedPerpVenueTest.php
- Candle
- ExchangeVerificationTest.php
- IndicatorsTest.php
- BinanceArchive
- Node
- .of
- App\Strategy\Dsl\Position
- StreamTransport
- Ledger
- Illuminate\Database\Schema\Blueprint
- generate.py
- SimulatedVenue
- StrategyContext
- Unit
- OrderManagerTest.php
- Node
- OrderRole
- .measure
- EnginePerpTest.php
- Engine.php
- ValidationStatisticsTest.php
- DecimalMathTest.php
- Engine
- App\Strategy\Dsl\Document\DocumentChecker
- Timeframe
- SimClock
- Decimal
- BacktestTest.php
- Symbol
- ema-cross-scalp-long.strategy.yaml
- RiskPolicy
- Kind
- sources
- User
- GridBacktest.php
- package.json
- Clock
- BinanceRestClientTest.php
- ema-cross-scalp-short.strategy.yaml
- .handle
- TokensTest.php
- ExitReason
- postgres
- InstrumentRules
- PreparesBacktests
- App\Strategy\Dsl\Compiler\CompiledStrategy
- .count
- GlobalsTest.php
- RunBacktestCommandTest.php
- OrderManager
- Carbon\CarbonImmutable
- CandleFeedTest.php
- .run
- QualityReport
- IndicatorReferenceTest.php
- MovingAverageTrend
- FundingRate
- Asset
- YamlMap
- Universe
- InvalidArgumentException
- scripts
- BinanceArchive.php
- InMemoryDecisionLog
- .walk
- composer.json
- jobs
- postgres
- .build
- Window
- CompiledStrategyTest.php
- App\Support\Number\Decimal
- pint/composer.json
- App\Strategy\Contracts\Strategy
- Quantity
- bootstrap/app.php
- require-dev
- scripts-descriptions
- PerpetualBaselinesTest.php
- FundingCarry
- config
- uses
- test
- with
- OrderUpdate
- run
- psr-4
- require
- DatabaseSeeder
- steps
- steps
- lint
- extra
- Parser
- .handle
- CompiledStrategyTest.php
- OrderRequest
- .parse
- CandleAggregator
- PerformanceMetrics
- StrategyIdentity
- ForwardingVenue
- .__construct
- .fromStrings
- ParserTest.php
- MinuteCandles

## God Nodes (most connected - your core abstractions)
1. `Decimal` - 295 edges
2. `Side` - 182 edges
3. `Symbol` - 169 edges
4. `Candle` - 103 edges
5. `Timeframe` - 99 edges
6. `Price` - 81 edges
7. `Position` - 68 edges
8. `MarketType` - 66 edges
9. `Ledger` - 56 edges
10. `SimulatedVenue` - 56 edges

## Surprising Connections (you probably didn't know these)
- `backtestFixture()` --calls--> `SimClock`  [INFERRED]
  tests/Feature/Simulation/RunBacktestCommandTest.php → app/Support/Clock/SimClock.php
- `perpFixture()` --calls--> `FundingRate`  [INFERRED]
  tests/Feature/Simulation/RunBacktestCommandTest.php → app/Market/Data/FundingRate.php
- `{closure#14}()` --calls--> `MoveStop`  [INFERRED]
  tests/Unit/Execution/EngineTest.php → app/Strategy/Data/MoveStop.php
- `{closure#7}()` --calls--> `MoveStop`  [INFERRED]
  tests/Unit/Execution/EngineTest.php → app/Strategy/Data/MoveStop.php
- `{closure#4}()` --references--> `Candle`  [EXTRACTED]
  tests/Feature/Market/BinanceRestClientTest.php → app/Market/Data/Candle.php

## Import Cycles
- None detected.

## Communities (132 total, 26 thin omitted)

### Community 0 - ".of"
Cohesion: 0.05
Nodes (78): AccountView, RejectionReason, BelowMinimum, Cooldown, DuplicateIntent, EntryWorking, GrossExposure, Halted (+70 more)

### Community 1 - "EngineTest.php"
Cohesion: 0.12
Nodes (29): ScriptedStrategy, {closure#1}(), {closure#10}(), {closure#13}(), {closure#14}(), {closure#16}(), {closure#17}(), {closure#2}() (+21 more)

### Community 2 - "App\Support\Number\Decimal"
Cohesion: 0.08
Nodes (19): {closure#10}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}(), {closure#8}() (+11 more)

### Community 3 - "Instrument"
Cohesion: 0.06
Nodes (16): BackfillReport, BackfillStep, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), BackfillMarketData, CandleSource (+8 more)

### Community 4 - "SimulatedVenue"
Cohesion: 0.15
Nodes (13): Liquidity, Maker, Taker, OrderStatus, Cancelled, Expired, Filled, New (+5 more)

### Community 5 - ".parse"
Cohesion: 0.12
Nodes (31): {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#15}(), {closure#16}(), {closure#17}() (+23 more)

### Community 6 - "MarketType"
Cohesion: 0.07
Nodes (21): BinanceRequestFailed, BinanceRestClient, Json, LocationRestricted, RateLimited, RequestFailed, SyncInstruments, InstrumentStatus (+13 more)

### Community 7 - "Position"
Cohesion: 0.10
Nodes (13): Argument, BoolLiteral, Call, DurationLiteral, Identifier, NumberLiteral, Parameter, PercentLiteral (+5 more)

### Community 8 - "CompiledStrategy.php"
Cohesion: 0.09
Nodes (7): {closure#3}(), {closure#4}(), CompiledStrategy, {closure#34}(), Compiler, Program, {closure#2}()

### Community 9 - "SimulatedVenueTest.php"
Cohesion: 0.16
Nodes (47): OcoRequest, Side, Buy, Sell, btc(), {closure#1}(), {closure#10}(), {closure#11}() (+39 more)

### Community 10 - "App\Support\Symbol"
Cohesion: 0.15
Nodes (5): {closure#7}(), EntryOrder, Intent, OpenLong, OpenShort

### Community 11 - "SimulatedPerpVenueTest.php"
Cohesion: 0.22
Nodes (25): {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#15}(), {closure#18}() (+17 more)

### Community 12 - "Candle"
Cohesion: 0.17
Nodes (27): Candle, {closure#1}(), {closure#2}(), candleWith(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}() (+19 more)

### Community 13 - "ExchangeVerificationTest.php"
Cohesion: 0.10
Nodes (28): {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}() (+20 more)

### Community 14 - "IndicatorsTest.php"
Cohesion: 0.12
Nodes (16): Atr, Ema, Sma, bar(), {closure#1}(), {closure#12}(), {closure#13}(), {closure#14}() (+8 more)

### Community 15 - "BinanceArchive"
Cohesion: 0.11
Nodes (6): BinanceArchive, BinanceEndpoints, SystemSleeper, AppServiceProvider, {closure#1}(), {closure#2}()

### Community 16 - "Node"
Cohesion: 0.09
Nodes (20): Lexer, Token, TokenKind, Colon, Comma, Dot, Duration, End (+12 more)

### Community 17 - ".of"
Cohesion: 0.12
Nodes (19): AssetMismatch, Money, {closure#2}(), {closure#1}(), usdt(), {closure#11}(), {closure#7}(), {closure#1}() (+11 more)

### Community 18 - "App\Strategy\Dsl\Position"
Cohesion: 0.14
Nodes (10): DslError, YamlList, YamlReader, YamlScalar, YamlValue, {closure#1}(), {closure#2}(), {closure#3}() (+2 more)

### Community 19 - "StreamTransport"
Cohesion: 0.08
Nodes (4): PhrityTransport, StreamFailed, StreamTransport, FakeTransport

### Community 20 - "Ledger"
Cohesion: 0.16
Nodes (28): Ledger, LedgerViolation, balanceOf(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}() (+20 more)

### Community 21 - "Illuminate\Database\Schema\Blueprint"
Cohesion: 0.10
Nodes (15): {closure#1}(), {closure#2}(), {closure#3}(), {closure#1}(), {closure#2}(), {closure#1}(), {closure#2}(), {closure#3}() (+7 more)

### Community 22 - "generate.py"
Cohesion: 0.09
Nodes (28): atr(), bars(), bb(), change(), dmi(), donchian(), ema(), highest() (+20 more)

### Community 23 - "SimulatedVenue"
Cohesion: 0.09
Nodes (10): Venue, ClientOrderId, OrderAck, {closure#2}(), SimulatedVenue, {closure#12}(), {closure#22}(), {closure#32}() (+2 more)

### Community 24 - "StrategyContext"
Cohesion: 0.08
Nodes (14): DonchianBreakout, BuildableStrategy, Strategy, Close, PositionView, StrategyContext, Subscription, EnterOnceStrategy (+6 more)

### Community 25 - "Unit"
Cohesion: 0.09
Nodes (18): {closure#1}(), {closure#2}(), ParamKind, Anchor, Count, Duration, Number, Percent (+10 more)

### Community 26 - "OrderManagerTest.php"
Cohesion: 0.30
Nodes (14): btcUsdt(), {closure#10}(), {closure#15}(), {closure#16}(), {closure#2}(), {closure#4}(), {closure#5}(), {closure#6}() (+6 more)

### Community 27 - "Node"
Cohesion: 0.19
Nodes (12): Lookback, Node, OnTimeframe, Unary, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}() (+4 more)

### Community 28 - "OrderRole"
Cohesion: 0.15
Nodes (15): OrderRole, Entry, Exit, Flatten, Stop, Target, TrackedOrder, {closure#1}() (+7 more)

### Community 29 - ".measure"
Cohesion: 0.18
Nodes (9): EquityTracker, Performance, {closure#1}(), {closure#2}(), {closure#3}(), {closure#5}(), {closure#6}(), {closure#8}() (+1 more)

### Community 30 - "EnginePerpTest.php"
Cohesion: 0.26
Nodes (15): {closure#10}(), {closure#11}(), {closure#13}(), {closure#14}(), {closure#2}(), {closure#5}(), {closure#6}(), {closure#7}() (+7 more)

### Community 31 - "Engine.php"
Cohesion: 0.07
Nodes (16): EngineMode, Backtest, Live, Paper, Testnet, {closure#4}(), {closure#6}(), {closure#1}() (+8 more)

### Community 32 - "ValidationStatisticsTest.php"
Cohesion: 0.11
Nodes (20): Cscv, DeflatedSharpe, Normal, closeTo(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}() (+12 more)

### Community 33 - "DecimalMathTest.php"
Cohesion: 0.06
Nodes (52): DecimalMath, Perpetual, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#2}(), {closure#3}() (+44 more)

### Community 34 - "Engine"
Cohesion: 0.12
Nodes (7): EngineRecord, IntentRecord, Engine, ReconciliationMismatch, {closure#1}(), {closure#1}(), {closure#3}()

### Community 35 - "App\Strategy\Dsl\Document\DocumentChecker"
Cohesion: 0.09
Nodes (20): InstrumentDirectory, StoredInstruments, CompileStrategy, CheckResult, {closure#1}(), {closure#7}(), PolicyLimits, StrategySpec (+12 more)

### Community 36 - "Timeframe"
Cohesion: 0.12
Nodes (15): {closure#8}(), Timeframe, FifteenMinutes, FiveMinutes, FourHours, OneDay, OneHour, OneMinute (+7 more)

### Community 37 - "SimClock"
Cohesion: 0.08
Nodes (27): SimClock, {closure#11}(), {closure#14}(), {closure#15}(), {closure#16}(), {closure#4}(), {closure#6}(), {closure#7}() (+19 more)

### Community 38 - "Decimal"
Cohesion: 0.06
Nodes (19): Fill, PerpPosition, Position, RoundTrip, {closure#1}(), {closure#1}(), {closure#3}(), {closure#1}() (+11 more)

### Community 39 - "BacktestTest.php"
Cohesion: 0.14
Nodes (20): FeeSchedule, CostModel, CandleFeed, Backtest, Participant, {closure#1}(), RunDefinition, {closure#3}() (+12 more)

### Community 40 - "Symbol"
Cohesion: 0.08
Nodes (10): ProtectionPlan, VenueOrder, VenuePosition, {closure#1}(), SimulatedPosition, InstrumentDefinition, MoveStop, OpenCarry (+2 more)

### Community 41 - "ema-cross-scalp-long.strategy.yaml"
Cohesion: 0.06
Nodes (33): entries, long, exits, stop_loss, take_profit, time_stop, when, fails_when (+25 more)

### Community 42 - "RiskPolicy"
Cohesion: 0.15
Nodes (10): RiskProfile, Account, Benchmark, RiskPolicy, RunBacktest, {closure#1}(), {closure#2}(), {closure#3}() (+2 more)

### Community 43 - "Kind"
Cohesion: 0.07
Nodes (31): {closure#3}(), {closure#8}(), {closure#9}(), EntrySpec, Expression, DslWriter, FunctionSpec, Library (+23 more)

### Community 44 - "sources"
Cohesion: 0.09
Nodes (22): secret, type, sources, auth, channels, connector, exchange, id (+14 more)

### Community 46 - "GridBacktest.php"
Cohesion: 0.06
Nodes (20): {closure#10}(), {closure#14}(), {closure#16}(), {closure#17}(), {closure#18}(), {closure#3}(), {closure#5}(), {closure#6}() (+12 more)

### Community 47 - "package.json"
Cohesion: 0.13
Nodes (20): devDependencies, concurrently@^10.0.3, laravel-vite-plugin@^3.1, tailwindcss@^4.0.0, @tailwindcss/vite@^4.0.0, vite@^8.0.0, optionalDependencies, @laravel/multiplex@^0.4.1 (+12 more)

### Community 48 - "Clock"
Cohesion: 0.11
Nodes (15): Backfill, {closure#1}(), {closure#2}(), {closure#3}(), CandleRepository, FundingRateRepository, Heartbeats, {closure#1}() (+7 more)

### Community 49 - "BinanceRestClientTest.php"
Cohesion: 0.09
Nodes (26): Sleeper, {closure#13}(), {closure#5}(), binanceClient(), {closure#1}(), {closure#11}(), {closure#12}(), {closure#13}() (+18 more)

### Community 50 - "ema-cross-scalp-short.strategy.yaml"
Cohesion: 0.06
Nodes (33): entries, short, exits, stop_loss, take_profit, time_stop, when, fails_when (+25 more)

### Community 51 - ".handle"
Cohesion: 0.24
Nodes (6): CanonicalJson, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}()

### Community 52 - "TokensTest.php"
Cohesion: 0.31
Nodes (7): {closure#3}(), {closure#4}(), {closure#5}(), contrastRatio(), contrastRules(), relativeLuminance(), themeTokens()

### Community 53 - "ExitReason"
Cohesion: 0.18
Nodes (24): ExitReason, Flatten, Liquidation, Signal, Stop, Target, {closure#1}(), {closure#10}() (+16 more)

### Community 54 - "postgres"
Cohesion: 0.10
Nodes (19): POSTGRES_DB, POSTGRES_PASSWORD, POSTGRES_USER, interval, retries, test, timeout, environment (+11 more)

### Community 55 - "InstrumentRules"
Cohesion: 0.24
Nodes (10): InstrumentRules, RulesHistory, {closure#2}(), {closure#4}(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}() (+2 more)

### Community 56 - "PreparesBacktests"
Cohesion: 0.11
Nodes (8): {closure#1}(), {closure#2}(), {closure#2}(), {closure#1}(), PreparesBacktests, {closure#1}(), {closure#2}(), {closure#3}()

### Community 57 - "App\Strategy\Dsl\Compiler\CompiledStrategy"
Cohesion: 0.09
Nodes (16): {closure#1}(), {closure#2}(), {closure#5}(), {closure#6}(), {closure#4}(), {closure#5}(), ParameterSpec, Group (+8 more)

### Community 58 - ".count"
Cohesion: 0.13
Nodes (18): {closure#13}(), Statistics, WalkForward, WalkForwardSet, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}() (+10 more)

### Community 59 - "GlobalsTest.php"
Cohesion: 0.22
Nodes (4): Controller, {closure#3}(), {closure#4}(), {closure#5}()

### Community 60 - "RunBacktestCommandTest.php"
Cohesion: 0.12
Nodes (24): {closure#1}(), InstrumentFilter, BacktestRun, StrategyTrial, backtestFixture(), {closure#1}(), {closure#10}(), {closure#11}() (+16 more)

### Community 61 - "OrderManager"
Cohesion: 0.20
Nodes (3): {closure#1}(), {closure#2}(), OrderManager

### Community 62 - "Carbon\CarbonImmutable"
Cohesion: 0.17
Nodes (11): ArchivePeriod, archiveBytes(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}() (+3 more)

### Community 63 - "CandleFeedTest.php"
Cohesion: 0.44
Nodes (11): {closure#1}(), {closure#12}(), {closure#13}(), {closure#2}(), {closure#4}(), {closure#5}(), {closure#8}(), {closure#9}() (+3 more)

### Community 64 - ".run"
Cohesion: 0.23
Nodes (14): Report, btcMinutes(), {closure#1}(), {closure#2}(), referencePrice(), {closure#2}(), {closure#3}(), {closure#4}() (+6 more)

### Community 66 - "IndicatorReferenceTest.php"
Cohesion: 0.06
Nodes (24): {closure#35}(), Bollinger, Change, Highest, Hma, Lowest, Macd, RealizedVolatility (+16 more)

### Community 68 - "FundingRate"
Cohesion: 0.12
Nodes (17): FundingRate, DatabaseCandleFeed, {closure#1}(), Rows, {closure#10}(), {closure#11}(), candleStore(), {closure#1}() (+9 more)

### Community 69 - "Asset"
Cohesion: 0.12
Nodes (6): Balance, Marks, Asset, balances(), {closure#25}(), {closure#9}()

### Community 71 - "Universe"
Cohesion: 0.08
Nodes (12): ArchiveMissing, ChecksumMismatch, CheckMarketData, SelectsInstruments, {closure#1}(), StreamCandles, VerifyMarketData, Universe (+4 more)

### Community 72 - "InvalidArgumentException"
Cohesion: 0.13
Nodes (3): {closure#3}(), SimulatedFuturesBook, Period

### Community 73 - "scripts"
Cohesion: 0.14
Nodes (14): scripts, analyse, dev, format, mutate, post-autoload-dump, post-update-cmd, pre-package-uninstall (+6 more)

### Community 74 - "BinanceArchive.php"
Cohesion: 0.14
Nodes (9): {closure#1}(), {closure#2}(), EpochTime, ExchangeNumber, {closure#1}(), {closure#2}(), {closure#3}(), {closure#1}() (+1 more)

### Community 75 - "InMemoryDecisionLog"
Cohesion: 0.20
Nodes (5): DecisionLog, DecisionRecord, InMemoryDecisionLog, {closure#27}(), EngineHarness

### Community 76 - ".walk"
Cohesion: 0.26
Nodes (13): CandleFactory, aggregate(), {closure#1}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#3}(), {closure#4}() (+5 more)

### Community 77 - "composer.json"
Cohesion: 0.17
Nodes (11): autoload-dev, psr-4, description, keywords, license, minimum-stability, name, prefer-stable (+3 more)

### Community 78 - "jobs"
Cohesion: 0.17
Nodes (11): concurrency, cancel-in-progress, group, jobs, name, on, pull_request, push (+3 more)

### Community 79 - "postgres"
Cohesion: 0.17
Nodes (12): POSTGRES_DB, POSTGRES_PASSWORD, POSTGRES_USER, env, image, options, ports, image (+4 more)

### Community 80 - ".build"
Cohesion: 0.25
Nodes (4): {closure#2}(), BacktestResult, ParticipantResult, ReportBuilder

### Community 81 - "Window"
Cohesion: 0.11
Nodes (4): DonchianChannel, Stochastic, Vwma, Window

### Community 82 - "CompiledStrategyTest.php"
Cohesion: 0.15
Nodes (13): KnownInstruments, {closure#11}(), {closure#13}(), {closure#16}(), {closure#17}(), {closure#2}(), {closure#3}(), {closure#4}() (+5 more)

### Community 83 - "App\Support\Number\Decimal"
Cohesion: 0.07
Nodes (37): {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#15}(), {closure#16}(), {closure#17}() (+29 more)

### Community 84 - "pint/composer.json"
Cohesion: 0.20
Nodes (9): config, sort-packages, description, license, name, require, laravel/pint, php (+1 more)

### Community 86 - "Quantity"
Cohesion: 0.19
Nodes (3): Quantity, {closure#10}(), {closure#9}()

### Community 87 - "bootstrap/app.php"
Cohesion: 0.33
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

### Community 88 - "require-dev"
Cohesion: 0.22
Nodes (9): require-dev, fakerphp/faker, larastan/larastan, laravel/pail, laravel/pao, mockery/mockery, nunomaduro/collision, pestphp/pest (+1 more)

### Community 89 - "scripts-descriptions"
Cohesion: 0.22
Nodes (9): scripts-descriptions, analyse, format, mutate, test, test:llm, test:network, test:testnet (+1 more)

### Community 90 - "PerpetualBaselinesTest.php"
Cohesion: 0.24
Nodes (8): {closure#1}(), {closure#3}(), goldenMinutes(), goldenRates(), perpetualReferencePrice(), referenceMean(), reportTrades(), GoldenData

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

### Community 96 - "OrderUpdate"
Cohesion: 0.22
Nodes (7): Income, IncomeKind, Funding, Liquidation, OrderUpdate, UpdateStream, {closure#3}()

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

### Community 121 - "CompiledStrategyTest.php"
Cohesion: 0.12
Nodes (8): {closure#37}(), BarIndicator, DirectionalMovement, Keltner, Natr, Supertrend, Vwap, {closure#9}()

### Community 122 - "OrderRequest"
Cohesion: 0.26
Nodes (6): OrderRequest, OrderType, Limit, Market, StopMarket, TakeProfitMarket

### Community 123 - ".parse"
Cohesion: 0.26
Nodes (6): KlineMessage, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), streamLines()

### Community 124 - "CandleAggregator"
Cohesion: 0.36
Nodes (5): CandleAggregator, CandleSequenceBroken, {closure#6}(), {closure#10}(), {closure#2}()

### Community 126 - "StrategyIdentity"
Cohesion: 0.38
Nodes (4): StrategyIdentity, {closure#1}(), {closure#2}(), {closure#3}()

### Community 129 - ".fromStrings"
Cohesion: 0.33
Nodes (3): {closure#1}(), {closure#42}(), {closure#12}()

### Community 130 - "ParserTest.php"
Cohesion: 0.53
Nodes (4): AstPrinter, {closure#1}(), {closure#4}(), sexpr()

### Community 131 - "MinuteCandles"
Cohesion: 0.33
Nodes (4): MinuteCandles, {closure#14}(), {closure#3}(), {closure#7}()

## Knowledge Gaps
- **121 isolated node(s):** `BelowMinimum`, `Cooldown`, `DuplicateIntent`, `EntryWorking`, `GrossExposure` (+116 more)
  These have ≤1 connection - possible missing edges. (Counts symbols only; 578 node(s) total have ≤1 connection when file, concept and rationale nodes are included.)
- **26 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `Decimal` connect `Decimal` to `.__construct`, `.of`, `App\Support\Number\Decimal`, `SimulatedVenue`, `.parse`, `MarketType`, `Position`, `SimulatedVenueTest.php`, `Candle`, `IndicatorsTest.php`, `.of`, `Ledger`, `SimulatedVenue`, `StrategyContext`, `OrderManagerTest.php`, `Node`, `OrderRole`, `.measure`, `EnginePerpTest.php`, `Engine.php`, `ValidationStatisticsTest.php`, `DecimalMathTest.php`, `Engine`, `Timeframe`, `SimClock`, `BacktestTest.php`, `Symbol`, `RiskPolicy`, `GridBacktest.php`, `Clock`, `.handle`, `ExitReason`, `InstrumentRules`, `PreparesBacktests`, `App\Strategy\Dsl\Compiler\CompiledStrategy`, `.count`, `RunBacktestCommandTest.php`, `OrderManager`, `CandleFeedTest.php`, `.run`, `MovingAverageTrend`, `FundingRate`, `Asset`, `Universe`, `InvalidArgumentException`, `BinanceArchive.php`, `.walk`, `.build`, `App\Support\Number\Decimal`, `Quantity`, `PerpetualBaselinesTest.php`, `FundingCarry`, `OrderUpdate`, `CompiledStrategyTest.php`, `OrderRequest`, `CandleAggregator`, `PerformanceMetrics`, `ForwardingVenue`?**
  _High betweenness centrality (0.123) - this node is a cross-community bridge._
- **What connects `BelowMinimum`, `Cooldown`, `DuplicateIntent` to the rest of the system?**
  _121 weakly-connected nodes found - possible missing edges._
- **Should `.of` be split into smaller, more focused modules?**
  _Cohesion score 0.051742919389978215 - nodes in this community are weakly interconnected._
- **Why does `Timeframe` connect `Timeframe` to `.__construct`, `.fromStrings`, `EngineTest.php`, `Instrument`, `.parse`, `MarketType`, `CompiledStrategy.php`, `SimulatedVenueTest.php`, `Candle`, `IndicatorsTest.php`, `StrategyContext`, `OrderManagerTest.php`, `Node`, `EnginePerpTest.php`, `Symbol`, `Clock`, `BinanceRestClientTest.php`, `ExitReason`, `PreparesBacktests`, `App\Strategy\Dsl\Compiler\CompiledStrategy`, `CandleFeedTest.php`, `MovingAverageTrend`, `FundingRate`, `YamlMap`, `BinanceArchive.php`, `.walk`, `CompiledStrategyTest.php`, `FundingCarry`, `CompiledStrategyTest.php`, `.parse`, `CandleAggregator`?**
  _High betweenness centrality (0.040) - this node is a cross-community bridge._
- **Should `EngineTest.php` be split into smaller, more focused modules?**
  _Cohesion score 0.1192156862745098 - nodes in this community are weakly interconnected._
- **Why does `Symbol` connect `Symbol` to `App\Support\Number\Decimal`, `Instrument`, `SimulatedVenue`, `.parse`, `MarketType`, `SimulatedVenueTest.php`, `App\Support\Symbol`, `SimulatedPerpVenueTest.php`, `ExchangeVerificationTest.php`, `.of`, `Ledger`, `SimulatedVenue`, `StrategyContext`, `OrderManagerTest.php`, `OrderRole`, `.measure`, `EnginePerpTest.php`, `Engine.php`, `Engine`, `SimClock`, `Decimal`, `BacktestTest.php`, `BinanceRestClientTest.php`, `.handle`, `ExitReason`, `InstrumentRules`, `PreparesBacktests`, `RunBacktestCommandTest.php`, `OrderManager`, `CandleFeedTest.php`, `.run`, `MovingAverageTrend`, `FundingRate`, `Asset`, `Universe`, `InvalidArgumentException`, `InMemoryDecisionLog`, `.build`, `CompiledStrategyTest.php`, `Quantity`, `PerpetualBaselinesTest.php`, `FundingCarry`, `OrderUpdate`, `OrderRequest`, `ForwardingVenue`?**
  _High betweenness centrality (0.039) - this node is a cross-community bridge._
- **Should `App\Support\Number\Decimal` be split into smaller, more focused modules?**
  _Cohesion score 0.08478513356562137 - nodes in this community are weakly interconnected._