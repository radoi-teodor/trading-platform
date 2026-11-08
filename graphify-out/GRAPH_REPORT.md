# Graph Report - trading-platform  (2026-11-08)

## Corpus Check
- 454 files · ~174,624 words
- Verdict: corpus is large enough that graph structure adds value.
- Unclassified: 92 file(s) not represented in the graph (top: .zip 34, .CHECKSUM 34, (none) 18)

## Summary
- 3756 nodes · 13186 edges · 147 communities (116 shown, 31 thin omitted)
- Extraction: 90% EXTRACTED · 10% INFERRED · 0% AMBIGUOUS · INFERRED: 1360 edges (avg confidence: 0.85)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `4d69abdc`
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
- DocumentChecker
- SimulatedPerpVenueTest.php
- Candle
- ExchangeVerificationTest.php
- IndicatorsTest.php
- App\Simulation\SimulationQueue
- Node
- StrategyVersionsTest.php
- App\Strategy\Dsl\Position
- RuntimeException
- Ledger
- Illuminate\Database\Schema\Blueprint
- generate.py
- ReadBack
- Symbol
- ParamKind
- RejectionReason
- Expression
- App\Strategy\Dsl\Compiler\CompiledStrategy
- Fill
- EnginePerpTest.php
- DslBaselinesTest.php
- ValidationStatisticsTest.php
- .of
- Carbon\CarbonImmutable
- Side
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
- .isPositive
- TokensTest.php
- Laravel\Mcp\Response
- postgres
- InstrumentRules
- PreparesBacktests
- donchian-breakout-short.strategy.yaml
- .measure
- ChartSeries
- RunBacktestCommandTest.php
- Side
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
- Stringable
- scripts
- ma-trend-short.strategy.yaml
- Asset
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
- StrategyLibrary
- require-dev
- scripts-descriptions
- .run
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
- .evaluate
- Engine.php
- buy-and-hold.strategy.yaml
- App\Market\Data\Candle
- FundingCarry
- Ledger
- bootstrap/app.php
- GridPart.php
- .handle
- MarketType
- funding-carry.strategy.yaml
- McpServerTest.php
- .parse
- Decimal
- App\Simulation\Backtest
- Price
- App\Market\Archive\BinanceArchive
- .encode
- ReadBackTest.php
- ForwardingVenue
- StrategyVersion
- Group
- .__construct
- .push

## God Nodes (most connected - your core abstractions)
1. `Decimal` - 294 edges
2. `Side` - 184 edges
3. `Symbol` - 169 edges
4. `Candle` - 103 edges
5. `Timeframe` - 98 edges
6. `Price` - 81 edges
7. `MarketType` - 68 edges
8. `Position` - 61 edges
9. `SimulatedVenue` - 56 edges
10. `Ledger` - 56 edges

## Surprising Connections (you probably didn't know these)
- `{closure#4}()` --references--> `Candle`  [EXTRACTED]
  tests/Feature/Market/BinanceRestClientTest.php → app/Market/Data/Candle.php
- `{closure#7}()` --references--> `Candle`  [EXTRACTED]
  tests/Feature/Market/CandleRepositoryTest.php → app/Market/Data/Candle.php
- `{closure#12}()` --references--> `Candle`  [EXTRACTED]
  tests/Unit/Market/CandleAggregatorTest.php → app/Market/Data/Candle.php
- `{closure#13}()` --references--> `Candle`  [EXTRACTED]
  tests/Unit/Market/CandleAggregatorTest.php → app/Market/Data/Candle.php
- `{closure#14}()` --calls--> `MoveStop`  [INFERRED]
  tests/Unit/Execution/EngineTest.php → app/Strategy/Data/MoveStop.php

## Import Cycles
- None detected.

## Communities (147 total, 31 thin omitted)

### Community 0 - ".of"
Cohesion: 0.17
Nodes (45): RiskState, LimitedStrategy, StrategyLimits, account(), accountPolicy(), {closure#1}(), {closure#10}(), {closure#11}() (+37 more)

### Community 1 - "EngineTest.php"
Cohesion: 0.08
Nodes (35): {closure#1}(), RenamedStrategy, ScriptedStrategy, {closure#1}(), {closure#10}(), {closure#13}(), {closure#14}(), {closure#16}() (+27 more)

### Community 2 - "App\Support\Clock\Clock"
Cohesion: 0.12
Nodes (7): CreateMcpToken, RevokeMcpToken, AuthenticateMcpToken, McpToken, ExportStrategies, ImportStrategies, PromoteStrategy

### Community 3 - "AppServiceProvider.php"
Cohesion: 0.05
Nodes (14): BinanceEndpoints, SystemSleeper, {closure#1}(), StreamCandles, CandleStream, PhrityTransport, StreamFailed, StreamTransport (+6 more)

### Community 4 - "OrderStatus"
Cohesion: 0.07
Nodes (18): Income, Liquidity, Maker, Taker, OrderStatus, Cancelled, Expired, Filled (+10 more)

### Community 5 - ".parse"
Cohesion: 0.10
Nodes (35): {closure#7}(), DonchianBreakout, baselinePairs(), {closure#5}(), {closure#10}(), {closure#11}(), {closure#13}(), {closure#14}() (+27 more)

### Community 6 - "Instrument"
Cohesion: 0.05
Nodes (27): Backfill, {closure#1}(), {closure#2}(), {closure#3}(), BackfillStep, CandleRepository, {closure#1}(), {closure#2}() (+19 more)

### Community 7 - "Position"
Cohesion: 0.07
Nodes (19): {closure#2}(), Argument, BoolLiteral, Call, DurationLiteral, Identifier, Lookback, Node (+11 more)

### Community 8 - "CompiledStrategy.php"
Cohesion: 0.11
Nodes (7): {closure#1}(), {closure#3}(), CompiledStrategy, {closure#33}(), Compiler, Program, Vwap

### Community 9 - "SimulatedVenueTest.php"
Cohesion: 0.17
Nodes (42): btc(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#15}(), {closure#16}() (+34 more)

### Community 11 - "SimulatedPerpVenueTest.php"
Cohesion: 0.18
Nodes (25): {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#15}(), {closure#18}() (+17 more)

### Community 12 - "Candle"
Cohesion: 0.15
Nodes (27): Candle, {closure#1}(), {closure#2}(), candleWith(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}() (+19 more)

### Community 13 - "ExchangeVerificationTest.php"
Cohesion: 0.10
Nodes (29): {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#2}(), {closure#3}(), {closure#4}() (+21 more)

### Community 14 - "IndicatorsTest.php"
Cohesion: 0.10
Nodes (18): Atr, {closure#1}(), Ema, Sma, Wilder, bar(), {closure#1}(), {closure#12}() (+10 more)

### Community 15 - "App\Simulation\SimulationQueue"
Cohesion: 0.17
Nodes (4): RunBacktest, RunGrid, SimulationTool, SimulationQueue

### Community 16 - "Node"
Cohesion: 0.11
Nodes (29): Binary, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}() (+21 more)

### Community 17 - "StrategyVersionsTest.php"
Cohesion: 0.15
Nodes (8): InstrumentDirectory, StrategyRepository, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), versions(), KnownInstruments

### Community 18 - "App\Strategy\Dsl\Position"
Cohesion: 0.09
Nodes (17): {closure#1}(), {closure#1}(), {closure#2}(), {closure#5}(), DslError, YamlList, YamlMap, YamlReader (+9 more)

### Community 19 - "RuntimeException"
Cohesion: 0.13
Nodes (9): ArchiveMissing, ChecksumMismatch, BinanceRequestFailed, LocationRestricted, RateLimited, VerifyMarketData, VerificationSource, DailyArchive (+1 more)

### Community 20 - "Ledger"
Cohesion: 0.22
Nodes (26): balanceOf(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#15}(), {closure#16}() (+18 more)

### Community 21 - "Illuminate\Database\Schema\Blueprint"
Cohesion: 0.06
Nodes (22): {closure#1}(), {closure#2}(), {closure#3}(), {closure#1}(), {closure#2}(), {closure#1}(), {closure#2}(), {closure#3}() (+14 more)

### Community 22 - "generate.py"
Cohesion: 0.09
Nodes (28): atr(), bars(), bb(), change(), dmi(), donchian(), ema(), highest() (+20 more)

### Community 24 - "Symbol"
Cohesion: 0.06
Nodes (18): BuyAndHold, MovingAverageTrend, BuildableStrategy, Strategy, Close, Intent, MoveStop, OpenCarry (+10 more)

### Community 25 - "ParamKind"
Cohesion: 0.15
Nodes (10): {closure#1}(), {closure#2}(), ParamKind, Anchor, Count, Duration, Number, Percent (+2 more)

### Community 26 - "RejectionReason"
Cohesion: 0.08
Nodes (27): AccountView, RejectionReason, BelowMinimum, Cooldown, DuplicateIntent, EntryWorking, GrossExposure, Halted (+19 more)

### Community 27 - "Expression"
Cohesion: 0.24
Nodes (4): CarryTemplate, CheckResult, ExitSpec, StrategySpec

### Community 28 - "App\Strategy\Dsl\Compiler\CompiledStrategy"
Cohesion: 0.10
Nodes (13): {closure#1}(), {closure#2}(), {closure#3}(), {closure#5}(), {closure#6}(), {closure#7}(), EntrySpec, Expression (+5 more)

### Community 29 - "Fill"
Cohesion: 0.12
Nodes (3): PerpPosition, Position, {closure#3}()

### Community 30 - "EnginePerpTest.php"
Cohesion: 0.28
Nodes (14): {closure#10}(), {closure#11}(), {closure#14}(), {closure#2}(), {closure#5}(), {closure#6}(), {closure#7}(), {closure#8}() (+6 more)

### Community 31 - "DslBaselinesTest.php"
Cohesion: 0.22
Nodes (16): baselineDocument(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#14}(), {closure#15}(), {closure#17}(), {closure#18}() (+8 more)

### Community 32 - "ValidationStatisticsTest.php"
Cohesion: 0.14
Nodes (18): Cscv, DeflatedSharpe, closeTo(), {closure#1}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}() (+10 more)

### Community 33 - ".of"
Cohesion: 0.07
Nodes (48): {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#1}(), {closure#2}(), {closure#3}(), documentedLimits() (+40 more)

### Community 34 - "Carbon\CarbonImmutable"
Cohesion: 0.09
Nodes (7): DecisionLog, DecisionRecord, EngineRecord, IntentRecord, Engine, ReconciliationMismatch, RiskEvent

### Community 35 - "Side"
Cohesion: 0.23
Nodes (22): ExitReason, Flatten, Liquidation, Signal, Stop, Target, {closure#1}(), {closure#10}() (+14 more)

### Community 36 - "Timeframe"
Cohesion: 0.11
Nodes (15): {closure#2}(), {closure#4}(), Timeframe, FifteenMinutes, FiveMinutes, FourHours, OneDay, OneHour (+7 more)

### Community 37 - "SimClock"
Cohesion: 0.08
Nodes (27): SimClock, WallClock, {closure#11}(), {closure#14}(), {closure#15}(), {closure#16}(), {closure#4}(), {closure#6}() (+19 more)

### Community 38 - "PreparesBacktests.php"
Cohesion: 0.14
Nodes (7): {closure#3}(), {closure#5}(), {closure#7}(), {closure#8}(), {closure#1}(), {closure#2}(), {closure#3}()

### Community 39 - "BacktestTest.php"
Cohesion: 0.10
Nodes (28): FeeSchedule, CostModel, CandleFeed, RiskProfile, Account, Benchmark, RiskPolicy, Backtest (+20 more)

### Community 40 - "Illuminate\Contracts\Config\Repository"
Cohesion: 0.21
Nodes (6): StoredInstruments, {closure#1}(), CompileStrategy, PolicyLimits, {closure#2}(), {closure#1}()

### Community 41 - "ema-cross-scalp-long.strategy.yaml"
Cohesion: 0.06
Nodes (33): entries, long, exits, stop_loss, take_profit, time_stop, when, fails_when (+25 more)

### Community 42 - "App\Support\Number\Decimal"
Cohesion: 0.04
Nodes (52): {closure#10}(), {closure#11}(), {closure#12}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}() (+44 more)

### Community 43 - "autoload-dev"
Cohesion: 0.67
Nodes (3): autoload-dev, psr-4, Tests\\

### Community 44 - "OrderRole"
Cohesion: 0.14
Nodes (29): OrderRole, Entry, Exit, Flatten, Stop, Target, TrackedOrder, {closure#1}() (+21 more)

### Community 46 - "Illuminate\Database\Eloquent\Model"
Cohesion: 0.09
Nodes (13): {closure#1}(), McpCall, {closure#1}(), InstrumentFilter, BacktestRun, StrategyGateReport, StrategyHoldout, StrategyTrial (+5 more)

### Community 47 - "package.json"
Cohesion: 0.13
Nodes (20): devDependencies, concurrently@^10.0.3, laravel-vite-plugin@^3.1, tailwindcss@^4.0.0, @tailwindcss/vite@^4.0.0, vite@^8.0.0, optionalDependencies, @laravel/multiplex@^0.4.1 (+12 more)

### Community 48 - "DocumentCheckerTest.php"
Cohesion: 0.30
Nodes (10): checkDocument(), {closure#1}(), {closure#2}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}(), {closure#8}() (+2 more)

### Community 49 - "BinanceRestClientTest.php"
Cohesion: 0.10
Nodes (25): Sleeper, {closure#13}(), {closure#5}(), binanceClient(), {closure#1}(), {closure#11}(), {closure#12}(), {closure#13}() (+17 more)

### Community 50 - "ema-cross-scalp-short.strategy.yaml"
Cohesion: 0.06
Nodes (33): entries, short, exits, stop_loss, take_profit, time_stop, when, fails_when (+25 more)

### Community 51 - ".isPositive"
Cohesion: 0.19
Nodes (5): Normal, DecimalMath, {closure#10}(), {closure#11}(), {closure#12}()

### Community 52 - "TokensTest.php"
Cohesion: 0.31
Nodes (7): {closure#3}(), {closure#4}(), {closure#5}(), contrastRatio(), contrastRules(), relativeLuminance(), themeTokens()

### Community 53 - "Laravel\Mcp\Response"
Cohesion: 0.10
Nodes (10): ChartTool, GetCandles, GetFunding, GetIndicators, GetJob, ListStrategies, ListTrials, SaveStrategy (+2 more)

### Community 54 - "postgres"
Cohesion: 0.10
Nodes (19): POSTGRES_DB, POSTGRES_PASSWORD, POSTGRES_USER, interval, retries, test, timeout, environment (+11 more)

### Community 55 - "InstrumentRules"
Cohesion: 0.24
Nodes (11): InstrumentRules, {closure#1}(), RulesHistory, {closure#4}(), {closure#41}(), {closure#1}(), {closure#2}(), {closure#3}() (+3 more)

### Community 57 - "donchian-breakout-short.strategy.yaml"
Cohesion: 0.07
Nodes (26): entries, short, default, range, step, default, range, step (+18 more)

### Community 58 - ".measure"
Cohesion: 0.12
Nodes (15): EquityTracker, Performance, Statistics, {closure#2}(), {closure#3}(), {closure#1}(), {closure#2}(), {closure#1}() (+7 more)

### Community 60 - "RunBacktestCommandTest.php"
Cohesion: 0.18
Nodes (22): {closure#4}(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#15}() (+14 more)

### Community 61 - "Side"
Cohesion: 0.18
Nodes (11): VenuePosition, {closure#1}(), SimulatedPosition, Fill, RoundTrip, Side, Buy, Sell (+3 more)

### Community 62 - "BinanceArchive"
Cohesion: 0.10
Nodes (12): ArchivePeriod, BinanceArchive, archiveBytes(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}() (+4 more)

### Community 63 - "CandleFeedTest.php"
Cohesion: 0.25
Nodes (15): MinuteCandles, {closure#1}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#2}(), {closure#3}(), {closure#4}() (+7 more)

### Community 64 - "App\Support\Symbol"
Cohesion: 0.09
Nodes (7): {closure#2}(), OrderManager, {closure#3}(), {closure#1}(), EntryOrder, OpenShort, {closure#4}()

### Community 65 - "donchian-breakout.strategy.yaml"
Cohesion: 0.07
Nodes (26): entries, long, default, range, step, default, range, step (+18 more)

### Community 66 - "IndicatorReferenceTest.php"
Cohesion: 0.05
Nodes (24): {closure#34}(), Bollinger, Change, Highest, Hma, Lowest, Macd, RealizedVolatility (+16 more)

### Community 67 - "CandleRepositoryTest.php"
Cohesion: 0.10
Nodes (15): candleStore(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}() (+7 more)

### Community 68 - "App\Strategy\Dsl\Document\DocumentChecker"
Cohesion: 0.07
Nodes (32): {closure#3}(), {closure#4}(), {closure#9}(), ParameterSpec, DslWriter, FunctionSpec, Library, Scope (+24 more)

### Community 69 - ".of"
Cohesion: 0.11
Nodes (21): AssetMismatch, Money, {closure#2}(), {closure#1}(), usdt(), {closure#3}(), {closure#6}(), {closure#8}() (+13 more)

### Community 70 - "App\Agents\Mcp\TradingServer"
Cohesion: 0.15
Nodes (4): DslReference, GetStrategy, ListInstruments, TradingServer

### Community 71 - "Universe"
Cohesion: 0.10
Nodes (7): BackfillReport, BackfillMarketData, CheckMarketData, SelectsInstruments, Universe, QualityCheck, QualityReport

### Community 73 - "scripts"
Cohesion: 0.15
Nodes (13): scripts, analyse, dev, format, mutate, post-autoload-dump, post-update-cmd, pre-package-uninstall (+5 more)

### Community 74 - "ma-trend-short.strategy.yaml"
Cohesion: 0.07
Nodes (26): entries, short, exits, stop_loss, when, fails_when, family, default (+18 more)

### Community 75 - "Asset"
Cohesion: 0.19
Nodes (4): Marks, Asset, {closure#25}(), {closure#9}()

### Community 76 - ".walk"
Cohesion: 0.25
Nodes (15): CandleAggregator, CandleFactory, aggregate(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}() (+7 more)

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
Cohesion: 0.10
Nodes (5): DonchianChannel, StandardDeviation, Stochastic, Vwma, Window

### Community 82 - "CompiledStrategyTest.php"
Cohesion: 0.12
Nodes (22): FundingRate, PositionView, StrategyContext, {closure#1}(), {closure#42}(), carryDecisions(), {closure#24}(), aggregatedBars() (+14 more)

### Community 83 - "App\Support\Number\Decimal"
Cohesion: 0.07
Nodes (39): {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#15}(), {closure#16}() (+31 more)

### Community 84 - "pint/composer.json"
Cohesion: 0.20
Nodes (9): config, sort-packages, description, license, name, require, laravel/pint, php (+1 more)

### Community 85 - "MonteCarlo"
Cohesion: 0.23
Nodes (9): MonteCarlo, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), mcDecimals() (+1 more)

### Community 86 - "RunSimulation.php"
Cohesion: 0.10
Nodes (6): RunSimulation, SimulationJob, {closure#1}(), {closure#2}(), {closure#3}(), savedEmaCross()

### Community 87 - "StrategyLibrary"
Cohesion: 0.26
Nodes (3): CompileStrategy, StrategyLibrary, SaveResult

### Community 88 - "require-dev"
Cohesion: 0.22
Nodes (9): require-dev, fakerphp/faker, larastan/larastan, laravel/pail, laravel/pao, mockery/mockery, nunomaduro/collision, pestphp/pest (+1 more)

### Community 89 - "scripts-descriptions"
Cohesion: 0.25
Nodes (8): scripts-descriptions, analyse, format, mutate, test, test:network, test:testnet, tools:install

### Community 90 - ".run"
Cohesion: 0.13
Nodes (20): PerformanceMetrics, Report, btcMinutes(), {closure#1}(), {closure#2}(), referencePrice(), {closure#13}(), {closure#16}() (+12 more)

### Community 91 - ".fromStrings"
Cohesion: 0.18
Nodes (7): RegimeLabels, {closure#1}(), {closure#2}(), {closure#4}(), {closure#7}(), {closure#9}(), days()

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

### Community 108 - ".evaluate"
Cohesion: 0.12
Nodes (17): Gates, WalkForward, WalkForwardSet, brokenGate(), {closure#1}(), {closure#3}(), {closure#4}(), {closure#6}() (+9 more)

### Community 119 - "Engine.php"
Cohesion: 0.10
Nodes (11): IncomeKind, Funding, Liquidation, {closure#4}(), {closure#6}(), {closure#1}(), RiskEventKind, DailyLossHalt (+3 more)

### Community 120 - "buy-and-hold.strategy.yaml"
Cohesion: 0.12
Nodes (16): entries, long, exits, stop_loss, fails_when, family, hypothesis, when (+8 more)

### Community 121 - "App\Market\Data\Candle"
Cohesion: 0.10
Nodes (16): {closure#36}(), BarIndicator, DirectionalMovement, Keltner, Natr, Supertrend, chart(), {closure#1}() (+8 more)

### Community 122 - "FundingCarry"
Cohesion: 0.15
Nodes (5): FundingCarry, Parameters, Donchian, {closure#10}(), {closure#15}()

### Community 123 - "Ledger"
Cohesion: 0.31
Nodes (5): Ledger, LedgerViolation, {closure#19}(), {closure#12}(), {closure#14}()

### Community 124 - "bootstrap/app.php"
Cohesion: 0.29
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

### Community 125 - "GridPart.php"
Cohesion: 0.21
Nodes (3): {closure#2}(), GridPart, SetOutcome

### Community 127 - "MarketType"
Cohesion: 0.06
Nodes (29): BinanceRestClient, {closure#1}(), RequestFailed, SyncInstruments, InstrumentDefinition, InstrumentStatus, Delisted, Halted (+21 more)

### Community 128 - "funding-carry.strategy.yaml"
Cohesion: 0.12
Nodes (15): fails_when, family, hypothesis, params, enter_when, exit_when, margin_buffer, max_leverage_perp (+7 more)

### Community 129 - "McpServerTest.php"
Cohesion: 0.21
Nodes (12): actAsToken(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}(), {closure#8}() (+4 more)

### Community 130 - ".parse"
Cohesion: 0.16
Nodes (9): AstPrinter, {closure#8}(), Lexer, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), sexpr() (+1 more)

### Community 131 - "Decimal"
Cohesion: 0.07
Nodes (14): Balance, {closure#2}(), {closure#1}(), {closure#2}(), {closure#3}(), TradeStatistics, Decimal, InvalidNumber (+6 more)

### Community 132 - "App\Simulation\Backtest"
Cohesion: 0.10
Nodes (14): EngineMode, Backtest, Live, Paper, Testnet, InMemoryDecisionLog, {closure#1}(), {closure#2}() (+6 more)

### Community 133 - "Price"
Cohesion: 0.07
Nodes (22): Venue, ClientOrderId, OcoRequest, OrderAck, OrderRequest, OrderType, Limit, Market (+14 more)

### Community 138 - "App\Market\Archive\BinanceArchive"
Cohesion: 0.09
Nodes (17): {closure#1}(), {closure#2}(), EpochTime, ExchangeNumber, Json, KlineMessage, {closure#5}(), {closure#1}() (+9 more)

### Community 139 - ".encode"
Cohesion: 0.17
Nodes (7): RunBacktest, CanonicalJson, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}()

### Community 140 - "ReadBackTest.php"
Cohesion: 0.38
Nodes (7): {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), readBackDocument(), readBackSpec()

### Community 143 - "Group"
Cohesion: 0.22
Nodes (8): Group, Exit, Indicator, Order, Perpetual, Position, Price, Time

### Community 145 - ".push"
Cohesion: 0.43
Nodes (3): CandleSequenceBroken, {closure#6}(), {closure#2}()

## Knowledge Gaps
- **115 isolated node(s):** `DailyLossHalt`, `DrawdownFlatten`, `DrawdownHalt`, `WeeklyLossHalt`, `Backtest` (+110 more)
  These have ≤1 connection - possible missing edges. (Counts symbols only; 735 node(s) total have ≤1 connection when file, concept and rationale nodes are included.)
- **31 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `Decimal` connect `Decimal` to `.of`, `EngineTest.php`, `OrderStatus`, `Price`, `Instrument`, `App\Simulation\Backtest`, `.parse`, `SimulatedVenueTest.php`, `App\Market\Archive\BinanceArchive`, `Position`, `Candle`, `ForwardingVenue`, `.encode`, `IndicatorsTest.php`, `.__construct`, `App\Strategy\Dsl\Position`, `Ledger`, `Symbol`, `RejectionReason`, `Fill`, `EnginePerpTest.php`, `.of`, `Carbon\CarbonImmutable`, `Side`, `Timeframe`, `SimClock`, `PreparesBacktests.php`, `BacktestTest.php`, `App\Support\Number\Decimal`, `OrderRole`, `.isPositive`, `InstrumentRules`, `PreparesBacktests`, `.measure`, `RunBacktestCommandTest.php`, `Side`, `CandleFeedTest.php`, `App\Support\Symbol`, `CandleRepositoryTest.php`, `.of`, `Universe`, `Stringable`, `Asset`, `.walk`, `CompiledStrategyTest.php`, `App\Support\Number\Decimal`, `.run`, `Engine.php`, `App\Market\Data\Candle`, `FundingCarry`, `Ledger`, `MarketType`?**
  _High betweenness centrality (0.118) - this node is a cross-community bridge._
- **What connects `DailyLossHalt`, `DrawdownFlatten`, `DrawdownHalt` to the rest of the system?**
  _115 weakly-connected nodes found - possible missing edges._
- **Should `EngineTest.php` be split into smaller, more focused modules?**
  _Cohesion score 0.07757885763000852 - nodes in this community are weakly interconnected._
- **Why does `Symbol` connect `Symbol` to `AppServiceProvider.php`, `OrderStatus`, `Price`, `Instrument`, `Decimal`, `App\Simulation\Backtest`, `SimulatedVenueTest.php`, `.parse`, `SimulatedPerpVenueTest.php`, `.encode`, `ExchangeVerificationTest.php`, `ForwardingVenue`, `App\Strategy\Dsl\Position`, `Ledger`, `RejectionReason`, `Fill`, `EnginePerpTest.php`, `Carbon\CarbonImmutable`, `Side`, `SimClock`, `BacktestTest.php`, `App\Support\Number\Decimal`, `OrderRole`, `BinanceRestClientTest.php`, `InstrumentRules`, `RunBacktestCommandTest.php`, `Side`, `CandleFeedTest.php`, `App\Support\Symbol`, `CandleRepositoryTest.php`, `.of`, `Universe`, `Stringable`, `Asset`, `CompiledStrategyTest.php`, `.run`, `Engine.php`, `FundingCarry`, `Ledger`, `MarketType`?**
  _High betweenness centrality (0.046) - this node is a cross-community bridge._
- **Should `App\Support\Clock\Clock` be split into smaller, more focused modules?**
  _Cohesion score 0.11904761904761904 - nodes in this community are weakly interconnected._
- **Why does `MarketType` connect `MarketType` to `AppServiceProvider.php`, `CandleRepositoryTest.php`, `SimClock`, `Instrument`, `Universe`, `BacktestTest.php`, `App\Market\Archive\BinanceArchive`, `DocumentChecker`, `.encode`, `ExchangeVerificationTest.php`, `RunBacktestCommandTest.php`, `BinanceRestClientTest.php`, `ReadBack`, `PreparesBacktests`, `.run`, `App\Strategy\Dsl\Compiler\CompiledStrategy`, `BinanceArchive`, `CandleFeedTest.php`?**
  _High betweenness centrality (0.021) - this node is a cross-community bridge._
- **Should `AppServiceProvider.php` be split into smaller, more focused modules?**
  _Cohesion score 0.04839685420447671 - nodes in this community are weakly interconnected._