# Graph Report - trading-platform  (2026-11-09)

## Corpus Check
- 456 files · ~177,825 words
- Verdict: corpus is large enough that graph structure adds value.
- Unclassified: 92 file(s) not represented in the graph (top: .zip 34, .CHECKSUM 34, (none) 18)

## Summary
- 3773 nodes · 13248 edges · 142 communities (113 shown, 29 thin omitted)
- Extraction: 90% EXTRACTED · 10% INFERRED · 0% AMBIGUOUS · INFERRED: 1391 edges (avg confidence: 0.85)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `3425c26d`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- .of
- EngineTest.php
- App\Support\Clock\Clock
- AppServiceProvider.php
- OrderStatus
- .parse
- Instrument
- Position
- CompiledStrategy.php
- SimulatedVenueTest.php
- Clock
- SimulatedPerpVenueTest.php
- Candle
- ExchangeVerificationTest.php
- .call
- AppServiceProvider.php
- Node
- StrategyVersionsTest.php
- App\Strategy\Dsl\Position
- Backfill.php
- Side
- Illuminate\Database\Schema\Blueprint
- generate.py
- ReadBack
- Symbol
- ParamKind
- Engine
- Expression
- RejectionReason
- Fill
- EnginePerpTest.php
- DslBaselinesTest.php
- ValidationStatisticsTest.php
- .of
- Carbon\CarbonImmutable
- .isGreaterThan
- Timeframe
- SimClock
- PreparesBacktests.php
- BacktestTest.php
- Illuminate\Contracts\Config\Repository
- ema-cross-scalp-long.strategy.yaml
- App\Support\Number\Decimal
- autoload-dev
- OrderRole
- User
- Illuminate\Database\Eloquent\Model
- package.json
- DocumentCheckerTest.php
- BinanceRestClientTest.php
- ema-cross-scalp-short.strategy.yaml
- .account
- TokensTest.php
- Laravel\Mcp\Response
- postgres
- InstrumentRules
- PreparesBacktests
- donchian-breakout-short.strategy.yaml
- .count
- ChartSeries
- RunBacktestCommandTest.php
- Symbol
- BinanceArchive
- CandleFeedTest.php
- App\Support\Symbol
- donchian-breakout.strategy.yaml
- IndicatorReferenceTest.php
- CandleRepositoryTest.php
- App\Strategy\Dsl\Document\DocumentChecker
- .of
- App\Agents\Mcp\TradingServer
- Universe
- SimulationJobsTest.php
- scripts
- ma-trend-short.strategy.yaml
- StrategyVersionsTest.php
- .walk
- composer.json
- jobs
- postgres
- ma-trend.strategy.yaml
- Window
- CompiledStrategyTest.php
- App\Support\Number\Decimal
- pint/composer.json
- MonteCarlo
- RunSimulation.php
- App\Simulation\Metrics\Performance
- require-dev
- scripts-descriptions
- RiskPolicy
- .fromStrings
- config
- uses
- test
- with
- .handle
- run
- psr-4
- require
- DatabaseSeeder
- steps
- steps
- lint
- Percent
- App\Support\Symbol
- buy-and-hold.strategy.yaml
- App\Market\Data\Candle
- Donchian
- .fromStrings
- bootstrap/app.php
- .handle
- MarketType
- funding-carry.strategy.yaml
- McpServerTest.php
- .parse
- Decimal
- ClientOrderId
- App\Market\Archive\BinanceArchive
- .encode
- App\Strategy\Dsl\Document\DocumentChecker
- StrategyVersion
- Group
- .__construct

## God Nodes (most connected - your core abstractions)
1. `Decimal` - 294 edges
2. `Side` - 184 edges
3. `Symbol` - 169 edges
4. `Candle` - 103 edges
5. `Timeframe` - 98 edges
6. `Price` - 81 edges
7. `MarketType` - 68 edges
8. `Position` - 61 edges
9. `Ledger` - 56 edges
10. `SimulatedVenue` - 56 edges

## Surprising Connections (you probably didn't know these)
- `runIndicator()` --calls--> `Change`  [INFERRED]
  tests/Unit/Strategy/IndicatorReferenceTest.php → app/Strategy/Indicators/Change.php
- `runIndicator()` --calls--> `DirectionalMovement`  [INFERRED]
  tests/Unit/Strategy/IndicatorReferenceTest.php → app/Strategy/Indicators/DirectionalMovement.php
- `runIndicator()` --calls--> `DonchianChannel`  [INFERRED]
  tests/Unit/Strategy/IndicatorReferenceTest.php → app/Strategy/Indicators/DonchianChannel.php
- `runIndicator()` --calls--> `Hma`  [INFERRED]
  tests/Unit/Strategy/IndicatorReferenceTest.php → app/Strategy/Indicators/Hma.php
- `runIndicator()` --calls--> `Keltner`  [INFERRED]
  tests/Unit/Strategy/IndicatorReferenceTest.php → app/Strategy/Indicators/Keltner.php

## Import Cycles
- None detected.

## Communities (142 total, 29 thin omitted)

### Community 0 - ".of"
Cohesion: 0.25
Nodes (38): account(), accountPolicy(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#2}() (+30 more)

### Community 1 - "EngineTest.php"
Cohesion: 0.08
Nodes (35): {closure#1}(), RenamedStrategy, ScriptedStrategy, {closure#1}(), {closure#10}(), {closure#13}(), {closure#14}(), {closure#16}() (+27 more)

### Community 2 - "App\Support\Clock\Clock"
Cohesion: 0.14
Nodes (6): CreateMcpToken, RevokeMcpToken, AuthenticateMcpToken, ExportStrategies, ImportStrategies, PromoteStrategy

### Community 3 - "AppServiceProvider.php"
Cohesion: 0.06
Nodes (15): BinanceEndpoints, Sleeper, CandleStream, PhrityTransport, StreamFailed, StreamTransport, {closure#1}(), {closure#2}() (+7 more)

### Community 4 - "OrderStatus"
Cohesion: 0.07
Nodes (19): Liquidity, Maker, Taker, OrderStatus, Cancelled, Expired, Filled, New (+11 more)

### Community 5 - ".parse"
Cohesion: 0.09
Nodes (40): baselinePairs(), {closure#17}(), {closure#7}(), {closure#9}(), {closure#3}(), {closure#5}(), {closure#6}(), carryDecisions() (+32 more)

### Community 6 - "Instrument"
Cohesion: 0.08
Nodes (12): Backfill, BackfillStep, CandleRepository, CandleSource, Archive, Rest, Stream, UpsertResult (+4 more)

### Community 7 - "Position"
Cohesion: 0.08
Nodes (19): Argument, BoolLiteral, Call, DurationLiteral, Identifier, Lookback, Node, NumberLiteral (+11 more)

### Community 8 - "CompiledStrategy.php"
Cohesion: 0.10
Nodes (9): {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), CompiledStrategy, {closure#33}(), Compiler, Program (+1 more)

### Community 9 - "SimulatedVenueTest.php"
Cohesion: 0.16
Nodes (43): OcoRequest, btc(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#15}() (+35 more)

### Community 10 - "Clock"
Cohesion: 0.08
Nodes (16): {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), DatabaseCandleFeed, {closure#1}(), FundingRateRepository, Heartbeats (+8 more)

### Community 11 - "SimulatedPerpVenueTest.php"
Cohesion: 0.20
Nodes (25): {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#15}(), {closure#18}() (+17 more)

### Community 12 - "Candle"
Cohesion: 0.16
Nodes (28): Candle, {closure#1}(), {closure#2}(), candleWith(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}() (+20 more)

### Community 13 - "ExchangeVerificationTest.php"
Cohesion: 0.10
Nodes (30): VerificationSource, DailyArchive, Rest, {closure#12}(), {closure#13}(), {closure#2}(), {closure#3}(), {closure#4}() (+22 more)

### Community 14 - ".call"
Cohesion: 0.08
Nodes (26): Atr, Bollinger, Ema, Highest, Lowest, RealizedVolatility, Rsi, Sma (+18 more)

### Community 15 - "AppServiceProvider.php"
Cohesion: 0.08
Nodes (10): RunBacktest, RunGrid, SimulationTool, SystemSleeper, AppServiceProvider, {closure#2}(), {closure#3}(), {closure#4}() (+2 more)

### Community 16 - "Node"
Cohesion: 0.11
Nodes (29): Binary, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}() (+21 more)

### Community 17 - "StrategyVersionsTest.php"
Cohesion: 0.16
Nodes (4): {closure#2}(), GridPart, SetOutcome, StrategyRepository

### Community 18 - "App\Strategy\Dsl\Position"
Cohesion: 0.11
Nodes (12): {closure#5}(), DocumentChecker, DslError, YamlList, YamlMap, YamlReader, YamlScalar, YamlValue (+4 more)

### Community 19 - "Backfill.php"
Cohesion: 0.09
Nodes (14): ReconciliationMismatch, ArchiveMissing, ChecksumMismatch, {closure#1}(), {closure#2}(), {closure#3}(), BinanceRequestFailed, LocationRestricted (+6 more)

### Community 20 - "Side"
Cohesion: 0.09
Nodes (60): ExitReason, Flatten, Liquidation, Signal, Stop, Target, RoundTrip, Ledger (+52 more)

### Community 21 - "Illuminate\Database\Schema\Blueprint"
Cohesion: 0.06
Nodes (22): {closure#1}(), {closure#2}(), {closure#3}(), {closure#1}(), {closure#2}(), {closure#1}(), {closure#2}(), {closure#3}() (+14 more)

### Community 22 - "generate.py"
Cohesion: 0.08
Nodes (30): atr(), bars(), bb(), change(), dmi(), donchian(), ema(), highest() (+22 more)

### Community 24 - "Symbol"
Cohesion: 0.06
Nodes (14): BuyAndHold, FundingCarry, MovingAverageTrend, BuildableStrategy, Strategy, StrategyContext, StrategyIdentity, Subscription (+6 more)

### Community 25 - "ParamKind"
Cohesion: 0.09
Nodes (14): {closure#2}(), {closure#1}(), {closure#2}(), {closure#1}(), {closure#2}(), ParamKind, Anchor, Count (+6 more)

### Community 26 - "Engine"
Cohesion: 0.15
Nodes (5): IntentRecord, Engine, AccountView, Verdict, RiskEngine

### Community 27 - "Expression"
Cohesion: 0.15
Nodes (8): CarryTemplate, CheckResult, {closure#7}(), EntrySpec, ExitSpec, Expression, StrategySpec, {closure#1}()

### Community 28 - "RejectionReason"
Cohesion: 0.08
Nodes (24): RejectionReason, BelowMinimum, Cooldown, DuplicateIntent, EntryWorking, GrossExposure, Halted, InvalidStop (+16 more)

### Community 29 - "Fill"
Cohesion: 0.10
Nodes (9): Fill, PerpPosition, Position, {closure#1}(), {closure#2}(), {closure#3}(), {closure#16}(), {closure#6}() (+1 more)

### Community 30 - "EnginePerpTest.php"
Cohesion: 0.12
Nodes (21): Close, OpenCarry, Policies, {closure#10}(), {closure#11}(), {closure#13}(), {closure#14}(), {closure#2}() (+13 more)

### Community 31 - "DslBaselinesTest.php"
Cohesion: 0.26
Nodes (13): baselineDocument(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#14}(), {closure#15}(), {closure#18}(), {closure#20}() (+5 more)

### Community 32 - "ValidationStatisticsTest.php"
Cohesion: 0.11
Nodes (20): Cscv, DeflatedSharpe, Normal, closeTo(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}() (+12 more)

### Community 33 - ".of"
Cohesion: 0.07
Nodes (50): DecimalMath, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#1}(), {closure#2}(), {closure#3}() (+42 more)

### Community 34 - "Carbon\CarbonImmutable"
Cohesion: 0.08
Nodes (20): DecisionLog, DecisionRecord, EngineMode, Backtest, Live, Paper, Testnet, EngineRecord (+12 more)

### Community 36 - "Timeframe"
Cohesion: 0.13
Nodes (13): Timeframe, FifteenMinutes, FiveMinutes, FourHours, OneDay, OneHour, OneMinute, OneWeek (+5 more)

### Community 37 - "SimClock"
Cohesion: 0.06
Nodes (36): SimClock, WallClock, {closure#11}(), {closure#13}(), {closure#14}(), {closure#15}(), {closure#16}(), {closure#4}() (+28 more)

### Community 38 - "PreparesBacktests.php"
Cohesion: 0.12
Nodes (9): {closure#1}(), {closure#2}(), {closure#3}(), {closure#5}(), {closure#7}(), {closure#8}(), {closure#1}(), {closure#2}() (+1 more)

### Community 39 - "BacktestTest.php"
Cohesion: 0.11
Nodes (23): FeeSchedule, CostModel, CandleFeed, FundingRate, Backtest, Participant, {closure#1}(), RunDefinition (+15 more)

### Community 40 - "Illuminate\Contracts\Config\Repository"
Cohesion: 0.29
Nodes (3): StoredInstruments, CompileStrategy, PolicyLimits

### Community 41 - "ema-cross-scalp-long.strategy.yaml"
Cohesion: 0.06
Nodes (33): entries, long, exits, stop_loss, take_profit, time_stop, when, fails_when (+25 more)

### Community 42 - "App\Support\Number\Decimal"
Cohesion: 0.04
Nodes (39): {closure#10}(), {closure#11}(), {closure#12}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}() (+31 more)

### Community 43 - "autoload-dev"
Cohesion: 0.67
Nodes (3): autoload-dev, psr-4, Tests\\

### Community 44 - "OrderRole"
Cohesion: 0.11
Nodes (31): OrderRole, Entry, Exit, Flatten, Stop, Target, OrderUpdate, TrackedOrder (+23 more)

### Community 46 - "Illuminate\Database\Eloquent\Model"
Cohesion: 0.08
Nodes (14): {closure#1}(), McpCall, McpToken, {closure#1}(), InstrumentFilter, BacktestRun, StrategyGateReport, StrategyHoldout (+6 more)

### Community 47 - "package.json"
Cohesion: 0.13
Nodes (20): devDependencies, concurrently@^10.0.3, laravel-vite-plugin@^3.1, tailwindcss@^4.0.0, @tailwindcss/vite@^4.0.0, vite@^8.0.0, optionalDependencies, @laravel/multiplex@^0.4.1 (+12 more)

### Community 48 - "DocumentCheckerTest.php"
Cohesion: 0.35
Nodes (9): checkDocument(), {closure#2}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}(), {closure#8}(), {closure#9}() (+1 more)

### Community 49 - "BinanceRestClientTest.php"
Cohesion: 0.17
Nodes (12): binanceClient(), {closure#1}(), {closure#11}(), {closure#13}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}() (+4 more)

### Community 50 - "ema-cross-scalp-short.strategy.yaml"
Cohesion: 0.06
Nodes (33): entries, short, exits, stop_loss, take_profit, time_stop, when, fails_when (+25 more)

### Community 52 - "TokensTest.php"
Cohesion: 0.31
Nodes (7): {closure#3}(), {closure#4}(), {closure#5}(), contrastRatio(), contrastRules(), relativeLuminance(), themeTokens()

### Community 53 - "Laravel\Mcp\Response"
Cohesion: 0.11
Nodes (7): CompileStrategy, GetFunding, GetStrategy, ListStrategies, ListTrials, TradingTool, StrategyLibrary

### Community 54 - "postgres"
Cohesion: 0.10
Nodes (19): POSTGRES_DB, POSTGRES_PASSWORD, POSTGRES_USER, interval, retries, test, timeout, environment (+11 more)

### Community 55 - "InstrumentRules"
Cohesion: 0.24
Nodes (11): InstrumentRules, {closure#1}(), RulesHistory, {closure#4}(), {closure#41}(), {closure#1}(), {closure#2}(), {closure#3}() (+3 more)

### Community 57 - "donchian-breakout-short.strategy.yaml"
Cohesion: 0.07
Nodes (26): entries, short, default, range, step, default, range, step (+18 more)

### Community 58 - ".count"
Cohesion: 0.05
Nodes (36): InMemoryDecisionLog, {closure#1}(), {closure#2}(), BacktestResult, ParticipantResult, EquityTracker, Performance, Statistics (+28 more)

### Community 59 - "ChartSeries"
Cohesion: 0.11
Nodes (5): ChartTool, GetCandles, GetIndicators, Controller, ChartSeries

### Community 60 - "RunBacktestCommandTest.php"
Cohesion: 0.12
Nodes (23): {closure#4}(), {closure#6}(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}() (+15 more)

### Community 61 - "Symbol"
Cohesion: 0.13
Nodes (6): VenuePosition, {closure#1}(), SimulatedPosition, Parameters, MoveStop, Symbol

### Community 62 - "BinanceArchive"
Cohesion: 0.10
Nodes (12): ArchivePeriod, BinanceArchive, archiveBytes(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}() (+4 more)

### Community 63 - "CandleFeedTest.php"
Cohesion: 0.25
Nodes (15): MinuteCandles, {closure#1}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#2}(), {closure#3}(), {closure#4}() (+7 more)

### Community 65 - "donchian-breakout.strategy.yaml"
Cohesion: 0.07
Nodes (26): entries, long, default, range, step, default, range, step (+18 more)

### Community 66 - "IndicatorReferenceTest.php"
Cohesion: 0.08
Nodes (13): Hma, Macd, Wma, assertMatchesReference(), {closure#10}(), {closure#11}(), {closure#13}(), {closure#14}() (+5 more)

### Community 67 - "CandleRepositoryTest.php"
Cohesion: 0.28
Nodes (11): candleStore(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}() (+3 more)

### Community 68 - "App\Strategy\Dsl\Document\DocumentChecker"
Cohesion: 0.08
Nodes (31): {closure#9}(), DslWriter, FunctionSpec, Library, Scope, {closure#1}(), {closure#4}(), TypeChecker (+23 more)

### Community 69 - ".of"
Cohesion: 0.06
Nodes (30): Balance, VenueFill, {closure#2}(), Marks, Asset, AssetMismatch, Money, balances() (+22 more)

### Community 70 - "App\Agents\Mcp\TradingServer"
Cohesion: 0.13
Nodes (5): DslReference, GetJob, ListInstruments, SaveStrategy, TradingServer

### Community 71 - "Universe"
Cohesion: 0.09
Nodes (9): BackfillReport, BackfillMarketData, CheckMarketData, SelectsInstruments, {closure#1}(), StreamCandles, Universe, QualityCheck (+1 more)

### Community 72 - "SimulationJobsTest.php"
Cohesion: 0.15
Nodes (4): {closure#1}(), {closure#2}(), {closure#3}(), savedEmaCross()

### Community 73 - "scripts"
Cohesion: 0.15
Nodes (13): scripts, analyse, dev, format, mutate, post-autoload-dump, post-update-cmd, pre-package-uninstall (+5 more)

### Community 74 - "ma-trend-short.strategy.yaml"
Cohesion: 0.07
Nodes (26): entries, short, exits, stop_loss, when, fails_when, family, default (+18 more)

### Community 75 - "StrategyVersionsTest.php"
Cohesion: 0.26
Nodes (8): InstrumentDirectory, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), versions(), KnownInstruments

### Community 76 - ".walk"
Cohesion: 0.18
Nodes (18): CandleAggregator, CandleSequenceBroken, {closure#3}(), {closure#6}(), aggregate(), {closure#1}(), {closure#10}(), {closure#11}() (+10 more)

### Community 77 - "composer.json"
Cohesion: 0.17
Nodes (11): description, extra, laravel, keywords, dont-discover, license, minimum-stability, name (+3 more)

### Community 78 - "jobs"
Cohesion: 0.17
Nodes (11): concurrency, cancel-in-progress, group, jobs, name, on, pull_request, push (+3 more)

### Community 79 - "postgres"
Cohesion: 0.17
Nodes (12): POSTGRES_DB, POSTGRES_PASSWORD, POSTGRES_USER, env, image, options, ports, image (+4 more)

### Community 80 - "ma-trend.strategy.yaml"
Cohesion: 0.07
Nodes (26): entries, long, exits, stop_loss, when, fails_when, family, default (+18 more)

### Community 81 - "Window"
Cohesion: 0.09
Nodes (8): {closure#42}(), {closure#43}(), Change, DonchianChannel, Roc, StandardDeviation, Vwma, Window

### Community 82 - "CompiledStrategyTest.php"
Cohesion: 0.21
Nodes (14): aggregatedBars(), {closure#11}(), {closure#13}(), {closure#16}(), {closure#17}(), {closure#2}(), {closure#3}(), {closure#4}() (+6 more)

### Community 83 - "App\Support\Number\Decimal"
Cohesion: 0.07
Nodes (44): WalkForwardResult, {closure#5}(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}() (+36 more)

### Community 84 - "pint/composer.json"
Cohesion: 0.20
Nodes (9): config, sort-packages, description, license, name, require, laravel/pint, php (+1 more)

### Community 85 - "MonteCarlo"
Cohesion: 0.21
Nodes (9): MonteCarlo, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), mcDecimals() (+1 more)

### Community 87 - "App\Simulation\Metrics\Performance"
Cohesion: 0.27
Nodes (9): {closure#10}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}(), {closure#8}() (+1 more)

### Community 88 - "require-dev"
Cohesion: 0.22
Nodes (9): require-dev, fakerphp/faker, larastan/larastan, laravel/pail, laravel/pao, mockery/mockery, nunomaduro/collision, pestphp/pest (+1 more)

### Community 89 - "scripts-descriptions"
Cohesion: 0.25
Nodes (8): scripts-descriptions, analyse, format, mutate, test, test:network, test:testnet, tools:install

### Community 90 - "RiskPolicy"
Cohesion: 0.09
Nodes (20): {closure#1}(), RiskProfile, Account, Benchmark, RiskPolicy, PerformanceMetrics, TradeStatistics, Report (+12 more)

### Community 91 - ".fromStrings"
Cohesion: 0.15
Nodes (7): RegimeLabels, DirectionalMovement, {closure#2}(), {closure#4}(), {closure#7}(), {closure#9}(), days()

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

### Community 108 - "Percent"
Cohesion: 0.24
Nodes (3): Percent, {closure#14}(), {closure#15}()

### Community 119 - "App\Support\Symbol"
Cohesion: 0.07
Nodes (10): {closure#4}(), {closure#6}(), {closure#7}(), {closure#1}(), LimitedStrategy, EntryOrder, Intent, OpenLong (+2 more)

### Community 120 - "buy-and-hold.strategy.yaml"
Cohesion: 0.12
Nodes (16): entries, long, exits, stop_loss, fails_when, family, hypothesis, when (+8 more)

### Community 121 - "App\Market\Data\Candle"
Cohesion: 0.08
Nodes (17): BarIndicator, Keltner, Natr, Obv, VolumeProfile, Vwap, chart(), {closure#10}() (+9 more)

### Community 122 - "Donchian"
Cohesion: 0.14
Nodes (5): Donchian, Period, Wilder, {closure#10}(), {closure#15}()

### Community 123 - ".fromStrings"
Cohesion: 0.22
Nodes (6): {closure#9}(), {closure#1}(), {closure#42}(), {closure#1}(), {closure#1}(), {closure#12}()

### Community 124 - "bootstrap/app.php"
Cohesion: 0.33
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

### Community 127 - "MarketType"
Cohesion: 0.18
Nodes (15): MarketType, Perp, Spot, btcMinutes(), {closure#1}(), {closure#2}(), referencePrice(), {closure#1}() (+7 more)

### Community 128 - "funding-carry.strategy.yaml"
Cohesion: 0.12
Nodes (15): fails_when, family, hypothesis, params, enter_when, exit_when, margin_buffer, max_leverage_perp (+7 more)

### Community 129 - "McpServerTest.php"
Cohesion: 0.27
Nodes (11): actAsToken(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}(), {closure#8}() (+3 more)

### Community 130 - ".parse"
Cohesion: 0.19
Nodes (7): AstPrinter, Lexer, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), sexpr()

### Community 131 - "Decimal"
Cohesion: 0.06
Nodes (19): Income, IncomeKind, Funding, Liquidation, ProtectionPlan, {closure#2}(), {closure#3}(), {closure#1}() (+11 more)

### Community 133 - "ClientOrderId"
Cohesion: 0.10
Nodes (9): ClientOrderId, OrderAck, OrderRequest, OrderType, Limit, Market, StopMarket, TakeProfitMarket (+1 more)

### Community 138 - "App\Market\Archive\BinanceArchive"
Cohesion: 0.05
Nodes (29): {closure#1}(), {closure#2}(), BinanceRestClient, {closure#1}(), EpochTime, ExchangeNumber, Json, SyncInstruments (+21 more)

### Community 139 - ".encode"
Cohesion: 0.17
Nodes (7): RunBacktest, CanonicalJson, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}()

### Community 140 - "App\Strategy\Dsl\Document\DocumentChecker"
Cohesion: 0.11
Nodes (17): {closure#1}(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#8}(), ParameterSpec, {closure#1}() (+9 more)

### Community 142 - "StrategyVersion"
Cohesion: 0.26
Nodes (3): {closure#1}(), SaveResult, StrategyVersion

### Community 143 - "Group"
Cohesion: 0.22
Nodes (8): Group, Exit, Indicator, Order, Perpetual, Position, Price, Time

## Knowledge Gaps
- **115 isolated node(s):** `Funding`, `Liquidation`, `DailyLossHalt`, `DrawdownFlatten`, `DrawdownHalt` (+110 more)
  These have ≤1 connection - possible missing edges. (Counts symbols only; 737 node(s) total have ≤1 connection when file, concept and rationale nodes are included.)
- **29 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `Decimal` connect `Decimal` to `.of`, `EngineTest.php`, `OrderStatus`, `ClientOrderId`, `.parse`, `Position`, `SimulatedVenueTest.php`, `App\Market\Archive\BinanceArchive`, `Clock`, `Candle`, `.encode`, `.call`, `.__construct`, `Side`, `Symbol`, `Engine`, `RejectionReason`, `Fill`, `EnginePerpTest.php`, `ValidationStatisticsTest.php`, `.of`, `Carbon\CarbonImmutable`, `.isGreaterThan`, `SimClock`, `PreparesBacktests.php`, `BacktestTest.php`, `App\Support\Number\Decimal`, `OrderRole`, `.account`, `InstrumentRules`, `PreparesBacktests`, `.count`, `RunBacktestCommandTest.php`, `Symbol`, `CandleFeedTest.php`, `App\Support\Symbol`, `.of`, `Universe`, `.walk`, `App\Support\Number\Decimal`, `App\Simulation\Metrics\Performance`, `RiskPolicy`, `Percent`, `App\Support\Symbol`, `App\Market\Data\Candle`, `Donchian`, `MarketType`?**
  _High betweenness centrality (0.095) - this node is a cross-community bridge._
- **What connects `Funding`, `Liquidation`, `DailyLossHalt` to the rest of the system?**
  _115 weakly-connected nodes found - possible missing edges._
- **Should `EngineTest.php` be split into smaller, more focused modules?**
  _Cohesion score 0.08077260755048288 - nodes in this community are weakly interconnected._
- **Why does `Candle` connect `Candle` to `EngineTest.php`, `Decimal`, `OrderStatus`, `AppServiceProvider.php`, `Instrument`, `.parse`, `SimulatedVenueTest.php`, `Clock`, `App\Market\Archive\BinanceArchive`, `ExchangeVerificationTest.php`, `.call`, `.__construct`, `Backfill.php`, `Symbol`, `EnginePerpTest.php`, `Carbon\CarbonImmutable`, `Timeframe`, `BacktestTest.php`, `OrderRole`, `BinanceRestClientTest.php`, `BinanceArchive`, `CandleFeedTest.php`, `CandleRepositoryTest.php`, `.of`, `.walk`, `MonteCarlo`, `App\Market\Data\Candle`, `Donchian`, `.fromStrings`, `MarketType`?**
  _High betweenness centrality (0.035) - this node is a cross-community bridge._
- **Should `App\Support\Clock\Clock` be split into smaller, more focused modules?**
  _Cohesion score 0.14492753623188406 - nodes in this community are weakly interconnected._
- **Why does `Symbol` connect `Symbol` to `EngineTest.php`, `Decimal`, `OrderStatus`, `ClientOrderId`, `Instrument`, `.parse`, `AppServiceProvider.php`, `SimulatedVenueTest.php`, `App\Market\Archive\BinanceArchive`, `SimulatedPerpVenueTest.php`, `Clock`, `ExchangeVerificationTest.php`, `.encode`, `Side`, `Symbol`, `Engine`, `Fill`, `EnginePerpTest.php`, `Carbon\CarbonImmutable`, `.isGreaterThan`, `SimClock`, `PreparesBacktests.php`, `BacktestTest.php`, `OrderRole`, `.account`, `InstrumentRules`, `.count`, `ChartSeries`, `RunBacktestCommandTest.php`, `CandleFeedTest.php`, `App\Support\Symbol`, `CandleRepositoryTest.php`, `.of`, `Universe`, `App\Simulation\Metrics\Performance`, `RiskPolicy`, `App\Support\Symbol`, `MarketType`?**
  _High betweenness centrality (0.028) - this node is a cross-community bridge._
- **Should `AppServiceProvider.php` be split into smaller, more focused modules?**
  _Cohesion score 0.0602322206095791 - nodes in this community are weakly interconnected._