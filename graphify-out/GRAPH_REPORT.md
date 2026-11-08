# Graph Report - trading-platform  (2026-11-08)

## Corpus Check
- 431 files · ~165,723 words
- Verdict: corpus is large enough that graph structure adds value.
- Unclassified: 92 file(s) not represented in the graph (top: .zip 34, .CHECKSUM 34, (none) 18)

## Summary
- 3434 nodes · 12477 edges · 137 communities (104 shown, 33 thin omitted)
- Extraction: 89% EXTRACTED · 11% INFERRED · 0% AMBIGUOUS · INFERRED: 1323 edges (avg confidence: 0.85)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `066cd8b1`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- .of
- EngineTest.php
- App\Simulation\Metrics\Performance
- Instrument
- OrderStatus
- .parse
- Instrument
- Position
- CompiledStrategy.php
- SimulatedVenueTest.php
- App\Support\Symbol
- SimulatedPerpVenueTest.php
- Candle
- ExchangeVerificationTest.php
- IndicatorsTest.php
- AppServiceProvider.php
- Node
- InvalidArgumentException
- App\Strategy\Dsl\Position
- Backfill.php
- Side
- Illuminate\Database\Schema\Blueprint
- generate.py
- ReadBack
- Symbol
- ParamKind
- CandleStreamTest.php
- Expression
- StrategyLibrary
- .times
- EnginePerpTest.php
- App\Agents\Mcp\TradingServer
- ValidationStatisticsTest.php
- .of
- Carbon\CarbonImmutable
- DocumentChecker
- Timeframe
- SimClock
- MarketType
- BacktestTest.php
- RiskPolicy
- ema-cross-scalp-long.strategy.yaml
- GridBacktest.php
- autoload-dev
- OrderRole
- User
- Illuminate\Database\Eloquent\Model
- package.json
- DocumentCheckerTest.php
- BinanceRestClientTest.php
- ema-cross-scalp-short.strategy.yaml
- .handle
- TokensTest.php
- Laravel\Mcp\Response
- postgres
- InstrumentRules
- App\Support\Clock\Clock
- .count
- ChartSeries
- RunBacktestCommandTest.php
- SimulatedFuturesBook.php
- BinanceArchive
- CandleFeedTest.php
- OrderManager
- PerpetualBaselinesTest.php
- IndicatorReferenceTest.php
- SimClock
- App\Strategy\Dsl\Document\DocumentChecker
- {closure#40}
- Income
- .count
- App\Portfolio\Data\Fill
- scripts
- StrategyVersionsTest.php
- App\Market\Stream\PhrityTransport
- .walk
- composer.json
- jobs
- postgres
- StrategyVersion
- Window
- CompiledStrategyTest.php
- App\Support\Number\Decimal
- pint/composer.json
- Fill
- RunSimulation.php
- DirectionalMovement
- require-dev
- scripts-descriptions
- .run
- SimulationTool
- config
- uses
- test
- with
- App\Simulation\Validation\WalkForward
- run
- psr-4
- require
- DatabaseSeeder
- steps
- steps
- lint
- UpsertResult
- Carbon\CarbonImmutable
- .handle
- .fromStrings
- Symbol
- DomainPurityTest.php
- bootstrap/app.php
- EngineMode
- MarketType
- .__construct
- McpServerTest.php
- .parse
- Decimal
- FundingCarry
- Price

## God Nodes (most connected - your core abstractions)
1. `Decimal` - 294 edges
2. `Side` - 184 edges
3. `Symbol` - 169 edges
4. `Candle` - 103 edges
5. `Timeframe` - 98 edges
6. `Price` - 81 edges
7. `MarketType` - 67 edges
8. `Position` - 61 edges
9. `Ledger` - 56 edges
10. `SimulatedVenue` - 56 edges

## Surprising Connections (you probably didn't know these)
- `readBackSpec()` --calls--> `PolicyLimits`  [INFERRED]
  tests/Unit/Strategy/Dsl/ReadBackTest.php → app/Strategy/Dsl/Document/PolicyLimits.php
- `{closure#4}()` --references--> `Candle`  [EXTRACTED]
  tests/Feature/Market/BinanceRestClientTest.php → app/Market/Data/Candle.php
- `{closure#7}()` --references--> `Candle`  [EXTRACTED]
  tests/Feature/Market/CandleRepositoryTest.php → app/Market/Data/Candle.php
- `{closure#12}()` --references--> `Candle`  [EXTRACTED]
  tests/Unit/Market/CandleAggregatorTest.php → app/Market/Data/Candle.php
- `{closure#13}()` --references--> `Candle`  [EXTRACTED]
  tests/Unit/Market/CandleAggregatorTest.php → app/Market/Data/Candle.php

## Import Cycles
- None detected.

## Communities (137 total, 33 thin omitted)

### Community 0 - ".of"
Cohesion: 0.06
Nodes (75): AccountView, RejectionReason, BelowMinimum, Cooldown, DuplicateIntent, EntryWorking, GrossExposure, Halted (+67 more)

### Community 1 - "EngineTest.php"
Cohesion: 0.08
Nodes (34): RenamedStrategy, ScriptedStrategy, {closure#1}(), {closure#10}(), {closure#13}(), {closure#14}(), {closure#16}(), {closure#17}() (+26 more)

### Community 2 - "App\Simulation\Metrics\Performance"
Cohesion: 0.24
Nodes (10): {closure#15}(), {closure#10}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}() (+2 more)

### Community 3 - "Instrument"
Cohesion: 0.09
Nodes (13): BinanceEndpoints, BinanceRestClient, Sleeper, CandleRepository, SyncInstruments, FundingRateRepository, Heartbeats, {closure#1}() (+5 more)

### Community 4 - "OrderStatus"
Cohesion: 0.10
Nodes (12): Liquidity, Maker, Taker, OrderStatus, Cancelled, Expired, Filled, New (+4 more)

### Community 5 - ".parse"
Cohesion: 0.12
Nodes (33): carryDecisions(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#15}(), {closure#16}() (+25 more)

### Community 6 - "Instrument"
Cohesion: 0.06
Nodes (18): {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), CandleSource, Archive, Rest, Stream (+10 more)

### Community 7 - "Position"
Cohesion: 0.07
Nodes (19): Argument, BoolLiteral, Call, DurationLiteral, Identifier, Lookback, Node, NumberLiteral (+11 more)

### Community 8 - "CompiledStrategy.php"
Cohesion: 0.11
Nodes (9): {closure#1}(), {closure#2}(), {closure#3}(), {closure#3}(), {closure#33}(), Compiler, Program, {closure#2}() (+1 more)

### Community 9 - "SimulatedVenueTest.php"
Cohesion: 0.20
Nodes (44): Side, Buy, Sell, btc(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}() (+36 more)

### Community 10 - "App\Support\Symbol"
Cohesion: 0.09
Nodes (11): {closure#6}(), {closure#7}(), {closure#1}(), LimitedStrategy, EntryOrder, OpenLong, OpenShort, {closure#5}() (+3 more)

### Community 11 - "SimulatedPerpVenueTest.php"
Cohesion: 0.22
Nodes (24): {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#15}(), {closure#18}(), {closure#19}() (+16 more)

### Community 12 - "Candle"
Cohesion: 0.17
Nodes (27): Candle, {closure#1}(), {closure#2}(), candleWith(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}() (+19 more)

### Community 13 - "ExchangeVerificationTest.php"
Cohesion: 0.09
Nodes (30): {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#2}(), {closure#3}(), {closure#4}() (+22 more)

### Community 14 - "IndicatorsTest.php"
Cohesion: 0.12
Nodes (16): Donchian, bar(), {closure#1}(), {closure#10}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#15}() (+8 more)

### Community 15 - "AppServiceProvider.php"
Cohesion: 0.11
Nodes (8): SystemSleeper, AppServiceProvider, {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), SimulationQueue

### Community 16 - "Node"
Cohesion: 0.11
Nodes (28): Binary, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}() (+20 more)

### Community 17 - "InvalidArgumentException"
Cohesion: 0.14
Nodes (6): {closure#1}(), {closure#1}(), {closure#1}(), {closure#5}(), Period, StrategyRepository

### Community 18 - "App\Strategy\Dsl\Position"
Cohesion: 0.15
Nodes (10): DslError, YamlList, YamlMap, YamlReader, YamlScalar, YamlValue, {closure#1}(), {closure#2}() (+2 more)

### Community 19 - "Backfill.php"
Cohesion: 0.07
Nodes (15): ArchiveMissing, ChecksumMismatch, {closure#1}(), {closure#2}(), {closure#3}(), BinanceRequestFailed, LocationRestricted, RateLimited (+7 more)

### Community 20 - "Side"
Cohesion: 0.05
Nodes (79): ExitReason, Flatten, Liquidation, Signal, Stop, Target, Marks, RoundTrip (+71 more)

### Community 21 - "Illuminate\Database\Schema\Blueprint"
Cohesion: 0.07
Nodes (20): {closure#1}(), {closure#2}(), {closure#3}(), {closure#1}(), {closure#2}(), {closure#1}(), {closure#2}(), {closure#3}() (+12 more)

### Community 22 - "generate.py"
Cohesion: 0.09
Nodes (28): atr(), bars(), bb(), change(), dmi(), donchian(), ema(), highest() (+20 more)

### Community 24 - "Symbol"
Cohesion: 0.08
Nodes (13): BuyAndHold, DonchianBreakout, BuildableStrategy, Strategy, StrategyContext, StrategyIdentity, Subscription, EnterOnceStrategy (+5 more)

### Community 25 - "ParamKind"
Cohesion: 0.18
Nodes (10): {closure#1}(), {closure#2}(), ParamKind, Anchor, Count, Duration, Number, Percent (+2 more)

### Community 26 - "CandleStreamTest.php"
Cohesion: 0.16
Nodes (9): StreamFailed, {closure#1}(), {closure#2}(), {closure#4}(), {closure#5}(), streamMessages(), streamWith(), FakeTransport (+1 more)

### Community 27 - "Expression"
Cohesion: 0.14
Nodes (8): CarryTemplate, CheckResult, {closure#7}(), EntrySpec, ExitSpec, Expression, StrategySpec, {closure#1}()

### Community 28 - "StrategyLibrary"
Cohesion: 0.21
Nodes (4): CompileStrategy, SaveStrategy, StrategyLibrary, SaveResult

### Community 29 - ".times"
Cohesion: 0.08
Nodes (9): PerpPosition, {closure#4}(), {closure#9}(), {closure#3}(), {closure#43}(), Perpetual, {closure#5}(), {closure#16}() (+1 more)

### Community 30 - "EnginePerpTest.php"
Cohesion: 0.24
Nodes (15): InMemoryDecisionLog, {closure#1}(), {closure#10}(), {closure#11}(), {closure#14}(), {closure#5}(), {closure#6}(), {closure#7}() (+7 more)

### Community 31 - "App\Agents\Mcp\TradingServer"
Cohesion: 0.15
Nodes (5): DslReference, GetStrategy, ListInstruments, ListStrategies, TradingServer

### Community 32 - "ValidationStatisticsTest.php"
Cohesion: 0.10
Nodes (20): Cscv, DeflatedSharpe, Normal, closeTo(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}() (+12 more)

### Community 33 - ".of"
Cohesion: 0.07
Nodes (43): DecimalMath, {closure#41}(), {closure#44}(), {closure#45}(), {closure#46}(), {closure#47}(), {closure#48}(), {closure#8}() (+35 more)

### Community 34 - "Carbon\CarbonImmutable"
Cohesion: 0.13
Nodes (5): EngineRecord, IntentRecord, Engine, ReconciliationMismatch, {closure#2}()

### Community 36 - "Timeframe"
Cohesion: 0.12
Nodes (14): {closure#4}(), Timeframe, FifteenMinutes, FiveMinutes, FourHours, OneDay, OneHour, OneMinute (+6 more)

### Community 37 - "SimClock"
Cohesion: 0.11
Nodes (18): {closure#11}(), {closure#13}(), {closure#14}(), {closure#15}(), {closure#16}(), {closure#4}(), {closure#5}(), {closure#6}() (+10 more)

### Community 38 - "MarketType"
Cohesion: 0.14
Nodes (6): {closure#2}(), CandleFeed, FundingRate, MarketType, Perp, Spot

### Community 39 - "BacktestTest.php"
Cohesion: 0.18
Nodes (17): FeeSchedule, CostModel, Backtest, Participant, RunDefinition, Money, {closure#5}(), InMemoryCandleFeed (+9 more)

### Community 40 - "RiskPolicy"
Cohesion: 0.18
Nodes (9): RiskProfile, Account, Benchmark, RiskPolicy, {closure#1}(), {closure#2}(), {closure#3}(), documentedLimits() (+1 more)

### Community 41 - "ema-cross-scalp-long.strategy.yaml"
Cohesion: 0.06
Nodes (33): entries, long, exits, stop_loss, take_profit, time_stop, when, fails_when (+25 more)

### Community 42 - "GridBacktest.php"
Cohesion: 0.09
Nodes (12): {closure#1}(), {closure#10}(), {closure#11}(), {closure#14}(), {closure#17}(), {closure#18}(), {closure#19}(), {closure#2}() (+4 more)

### Community 43 - "autoload-dev"
Cohesion: 0.67
Nodes (3): autoload-dev, psr-4, Tests\\

### Community 44 - "OrderRole"
Cohesion: 0.12
Nodes (29): OrderRole, Entry, Exit, Flatten, Stop, Target, TrackedOrder, {closure#1}() (+21 more)

### Community 46 - "Illuminate\Database\Eloquent\Model"
Cohesion: 0.12
Nodes (7): {closure#1}(), McpCall, McpToken, InstrumentFilter, BacktestRun, StrategyTrial, StrategyStage

### Community 47 - "package.json"
Cohesion: 0.13
Nodes (20): devDependencies, concurrently@^10.0.3, laravel-vite-plugin@^3.1, tailwindcss@^4.0.0, @tailwindcss/vite@^4.0.0, vite@^8.0.0, optionalDependencies, @laravel/multiplex@^0.4.1 (+12 more)

### Community 48 - "DocumentCheckerTest.php"
Cohesion: 0.19
Nodes (14): {closure#1}(), {closure#1}(), {closure#1}(), checkDocument(), {closure#1}(), {closure#2}(), {closure#4}(), {closure#5}() (+6 more)

### Community 49 - "BinanceRestClientTest.php"
Cohesion: 0.17
Nodes (12): binanceClient(), {closure#1}(), {closure#11}(), {closure#13}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}() (+4 more)

### Community 50 - "ema-cross-scalp-short.strategy.yaml"
Cohesion: 0.06
Nodes (33): entries, short, exits, stop_loss, take_profit, time_stop, when, fails_when (+25 more)

### Community 51 - ".handle"
Cohesion: 0.13
Nodes (8): RunBacktest, CanonicalJson, ReportBuilder, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}()

### Community 52 - "TokensTest.php"
Cohesion: 0.31
Nodes (7): {closure#3}(), {closure#4}(), {closure#5}(), contrastRatio(), contrastRules(), relativeLuminance(), themeTokens()

### Community 53 - "Laravel\Mcp\Response"
Cohesion: 0.12
Nodes (5): GetFunding, GetJob, ListTrials, TradingTool, ChartSeries

### Community 54 - "postgres"
Cohesion: 0.10
Nodes (19): POSTGRES_DB, POSTGRES_PASSWORD, POSTGRES_USER, interval, retries, test, timeout, environment (+11 more)

### Community 55 - "InstrumentRules"
Cohesion: 0.28
Nodes (11): InstrumentRules, RulesHistory, {closure#1}(), {closure#2}(), {closure#4}(), {closure#1}(), {closure#2}(), {closure#3}() (+3 more)

### Community 56 - "App\Support\Clock\Clock"
Cohesion: 0.09
Nodes (9): StoredInstruments, {closure#1}(), {closure#2}(), {closure#1}(), CompileStrategy, ExportStrategies, ImportStrategies, PreparesBacktests (+1 more)

### Community 58 - ".count"
Cohesion: 0.08
Nodes (24): EquityTracker, Performance, Statistics, WalkForward, WalkForwardSet, {closure#1}(), {closure#2}(), {closure#3}() (+16 more)

### Community 59 - "ChartSeries"
Cohesion: 0.14
Nodes (4): ChartTool, GetCandles, GetIndicators, Controller

### Community 60 - "RunBacktestCommandTest.php"
Cohesion: 0.20
Nodes (21): {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#15}(), {closure#16}() (+13 more)

### Community 61 - "SimulatedFuturesBook.php"
Cohesion: 0.13
Nodes (8): VenuePosition, {closure#1}(), {closure#2}(), {closure#3}(), SimulatedPosition, {closure#1}(), {closure#13}(), {closure#1}()

### Community 62 - "BinanceArchive"
Cohesion: 0.10
Nodes (12): ArchivePeriod, BinanceArchive, archiveBytes(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}() (+4 more)

### Community 63 - "CandleFeedTest.php"
Cohesion: 0.25
Nodes (15): MinuteCandles, {closure#1}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#2}(), {closure#3}(), {closure#4}() (+7 more)

### Community 65 - "PerpetualBaselinesTest.php"
Cohesion: 0.22
Nodes (8): MovingAverageTrend, {closure#1}(), {closure#3}(), goldenMinutes(), goldenRates(), perpetualReferencePrice(), referenceMean(), reportTrades()

### Community 66 - "IndicatorReferenceTest.php"
Cohesion: 0.07
Nodes (34): Atr, BarIndicator, Bollinger, Change, DonchianChannel, Ema, Highest, Hma (+26 more)

### Community 67 - "SimClock"
Cohesion: 0.08
Nodes (28): SimClock, WallClock, candleStore(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}() (+20 more)

### Community 68 - "App\Strategy\Dsl\Document\DocumentChecker"
Cohesion: 0.06
Nodes (40): {closure#2}(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#9}(), ParameterSpec, FunctionSpec (+32 more)

### Community 69 - "{closure#40}"
Cohesion: 0.18
Nodes (3): Balance, balances(), {closure#40}()

### Community 70 - "Income"
Cohesion: 0.24
Nodes (8): Income, IncomeKind, Funding, Liquidation, {closure#3}(), {closure#1}(), {closure#34}(), orderUpdate()

### Community 71 - ".count"
Cohesion: 0.10
Nodes (12): CreateMcpToken, RevokeMcpToken, Backfill, BackfillReport, BackfillMarketData, CheckMarketData, SelectsInstruments, {closure#1}() (+4 more)

### Community 73 - "scripts"
Cohesion: 0.15
Nodes (13): scripts, analyse, dev, format, mutate, post-autoload-dump, post-update-cmd, pre-package-uninstall (+5 more)

### Community 74 - "StrategyVersionsTest.php"
Cohesion: 0.26
Nodes (8): InstrumentDirectory, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), versions(), KnownInstruments

### Community 76 - ".walk"
Cohesion: 0.22
Nodes (17): CandleAggregator, CandleSequenceBroken, {closure#6}(), aggregate(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}() (+9 more)

### Community 77 - "composer.json"
Cohesion: 0.17
Nodes (11): description, extra, laravel, keywords, dont-discover, license, minimum-stability, name (+3 more)

### Community 78 - "jobs"
Cohesion: 0.17
Nodes (11): concurrency, cancel-in-progress, group, jobs, name, on, pull_request, push (+3 more)

### Community 79 - "postgres"
Cohesion: 0.17
Nodes (12): POSTGRES_DB, POSTGRES_PASSWORD, POSTGRES_USER, env, image, options, ports, image (+4 more)

### Community 82 - "CompiledStrategyTest.php"
Cohesion: 0.20
Nodes (15): PositionView, {closure#24}(), aggregatedBars(), {closure#11}(), {closure#13}(), {closure#16}(), {closure#17}(), {closure#2}() (+7 more)

### Community 83 - "App\Support\Number\Decimal"
Cohesion: 0.05
Nodes (45): WalkForwardResult, {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#15}() (+37 more)

### Community 84 - "pint/composer.json"
Cohesion: 0.20
Nodes (9): config, sort-packages, description, license, name, require, laravel/pint, php (+1 more)

### Community 86 - "RunSimulation.php"
Cohesion: 0.10
Nodes (6): RunSimulation, SimulationJob, {closure#1}(), {closure#2}(), {closure#3}(), savedEmaCross()

### Community 88 - "require-dev"
Cohesion: 0.22
Nodes (9): require-dev, fakerphp/faker, larastan/larastan, laravel/pail, laravel/pao, mockery/mockery, nunomaduro/collision, pestphp/pest (+1 more)

### Community 89 - "scripts-descriptions"
Cohesion: 0.25
Nodes (8): scripts-descriptions, analyse, format, mutate, test, test:network, test:testnet, tools:install

### Community 90 - ".run"
Cohesion: 0.15
Nodes (17): PerformanceMetrics, Report, btcMinutes(), {closure#1}(), {closure#2}(), {closure#3}(), referencePrice(), {closure#2}() (+9 more)

### Community 91 - "SimulationTool"
Cohesion: 0.25
Nodes (3): RunBacktest, RunGrid, SimulationTool

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

### Community 96 - "App\Simulation\Validation\WalkForward"
Cohesion: 0.17
Nodes (9): {closure#1}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}(), {closure#8}(), {closure#9}() (+1 more)

### Community 97 - "run"
Cohesion: 0.33
Nodes (6): name, runs-on, steps, audit, run, steps

### Community 99 - "psr-4"
Cohesion: 0.40
Nodes (5): autoload, psr-4, App\\, Database\\Factories\\, Database\\Seeders\\

### Community 100 - "require"
Cohesion: 0.33
Nodes (6): require, laravel/framework, laravel/mcp, laravel/tinker, php, phrity/websocket

### Community 103 - "steps"
Cohesion: 0.50
Nodes (4): name, runs-on, steps, analyse

### Community 104 - "steps"
Cohesion: 0.50
Nodes (4): name, runs-on, steps, frontend

### Community 105 - "lint"
Cohesion: 0.50
Nodes (4): lint, name, runs-on, steps

### Community 119 - "Carbon\CarbonImmutable"
Cohesion: 0.09
Nodes (9): DecisionLog, DecisionRecord, RiskEvent, RiskEventKind, DailyLossHalt, DrawdownFlatten, DrawdownHalt, WeeklyLossHalt (+1 more)

### Community 120 - ".handle"
Cohesion: 0.21
Nodes (7): GridBacktest, {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), readBackDocument(), readBackSpec()

### Community 121 - ".fromStrings"
Cohesion: 0.15
Nodes (12): {closure#1}(), {closure#42}(), chart(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}() (+4 more)

### Community 122 - "Symbol"
Cohesion: 0.09
Nodes (9): {closure#1}(), Parameters, Close, Intent, MoveStop, OpenCarry, Symbol, {closure#3}() (+1 more)

### Community 123 - "DomainPurityTest.php"
Cohesion: 0.40
Nodes (4): {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}()

### Community 124 - "bootstrap/app.php"
Cohesion: 0.17
Nodes (4): AuthenticateMcpToken, {closure#1}(), {closure#2}(), {closure#3}()

### Community 125 - "EngineMode"
Cohesion: 0.33
Nodes (5): EngineMode, Backtest, Live, Paper, Testnet

### Community 127 - "MarketType"
Cohesion: 0.06
Nodes (25): {closure#1}(), {closure#1}(), EpochTime, ExchangeNumber, Json, InstrumentDefinition, InstrumentStatus, Delisted (+17 more)

### Community 129 - "McpServerTest.php"
Cohesion: 0.27
Nodes (11): actAsToken(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}(), {closure#8}() (+3 more)

### Community 130 - ".parse"
Cohesion: 0.12
Nodes (14): AstPrinter, {closure#8}(), DslWriter, Lexer, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}() (+6 more)

### Community 131 - "Decimal"
Cohesion: 0.07
Nodes (13): {closure#1}(), {closure#2}(), {closure#3}(), TradeStatistics, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}() (+5 more)

### Community 133 - "Price"
Cohesion: 0.06
Nodes (23): Venue, ClientOrderId, OcoRequest, OrderAck, OrderRequest, OrderType, Limit, Market (+15 more)

## Knowledge Gaps
- **115 isolated node(s):** `DailyLossHalt`, `DrawdownFlatten`, `DrawdownHalt`, `WeeklyLossHalt`, `Exit` (+110 more)
  These have ≤1 connection - possible missing edges. (Counts symbols only; 597 node(s) total have ≤1 connection when file, concept and rationale nodes are included.)
- **33 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `Decimal` connect `Decimal` to `.__construct`, `.of`, `App\Simulation\Metrics\Performance`, `Instrument`, `OrderStatus`, `Price`, `Instrument`, `FundingCarry`, `CompiledStrategy.php`, `SimulatedVenueTest.php`, `Position`, `SimulatedPerpVenueTest.php`, `Candle`, `.parse`, `IndicatorsTest.php`, `InvalidArgumentException`, `Side`, `Symbol`, `.times`, `EnginePerpTest.php`, `ValidationStatisticsTest.php`, `.of`, `Carbon\CarbonImmutable`, `MarketType`, `BacktestTest.php`, `RiskPolicy`, `GridBacktest.php`, `OrderRole`, `.handle`, `InstrumentRules`, `App\Support\Clock\Clock`, `.count`, `SimulatedFuturesBook.php`, `CandleFeedTest.php`, `OrderManager`, `PerpetualBaselinesTest.php`, `SimClock`, `{closure#40}`, `Income`, `App\Portfolio\Data\Fill`, `.walk`, `CompiledStrategyTest.php`, `App\Support\Number\Decimal`, `Fill`, `.run`, `Carbon\CarbonImmutable`, `Symbol`, `MarketType`?**
  _High betweenness centrality (0.096) - this node is a cross-community bridge._
- **What connects `DailyLossHalt`, `DrawdownFlatten`, `DrawdownHalt` to the rest of the system?**
  _115 weakly-connected nodes found - possible missing edges._
- **Should `.of` be split into smaller, more focused modules?**
  _Cohesion score 0.06195698618430423 - nodes in this community are weakly interconnected._
- **Why does `Symbol` connect `Symbol` to `.of`, `EngineTest.php`, `App\Simulation\Metrics\Performance`, `Instrument`, `Decimal`, `Price`, `Instrument`, `FundingCarry`, `.parse`, `SimulatedVenueTest.php`, `App\Support\Symbol`, `SimulatedPerpVenueTest.php`, `ExchangeVerificationTest.php`, `AppServiceProvider.php`, `InvalidArgumentException`, `Side`, `Symbol`, `CandleStreamTest.php`, `.times`, `EnginePerpTest.php`, `Carbon\CarbonImmutable`, `SimClock`, `MarketType`, `BacktestTest.php`, `OrderRole`, `.handle`, `InstrumentRules`, `.count`, `SimulatedFuturesBook.php`, `CandleFeedTest.php`, `OrderManager`, `PerpetualBaselinesTest.php`, `SimClock`, `{closure#40}`, `Income`, `.count`, `App\Portfolio\Data\Fill`, `Fill`, `.run`, `Carbon\CarbonImmutable`, `MarketType`?**
  _High betweenness centrality (0.036) - this node is a cross-community bridge._
- **Should `EngineTest.php` be split into smaller, more focused modules?**
  _Cohesion score 0.07885763000852515 - nodes in this community are weakly interconnected._
- **Why does `Candle` connect `Candle` to `.__construct`, `EngineTest.php`, `Decimal`, `OrderStatus`, `Price`, `Instrument`, `.parse`, `CompiledStrategy.php`, `SimulatedVenueTest.php`, `ExchangeVerificationTest.php`, `IndicatorsTest.php`, `Backfill.php`, `Side`, `Symbol`, `CandleStreamTest.php`, `EnginePerpTest.php`, `Timeframe`, `MarketType`, `BacktestTest.php`, `OrderRole`, `BinanceRestClientTest.php`, `BinanceArchive`, `CandleFeedTest.php`, `PerpetualBaselinesTest.php`, `SimClock`, `{closure#40}`, `.walk`, `App\Support\Number\Decimal`, `.run`, `Carbon\CarbonImmutable`, `.fromStrings`, `Symbol`, `MarketType`?**
  _High betweenness centrality (0.033) - this node is a cross-community bridge._
- **Should `Instrument` be split into smaller, more focused modules?**
  _Cohesion score 0.08597285067873303 - nodes in this community are weakly interconnected._