# Graph Report - trading-platform  (2026-11-08)

## Corpus Check
- 431 files · ~165,861 words
- Verdict: corpus is large enough that graph structure adds value.
- Unclassified: 92 file(s) not represented in the graph (top: .zip 34, .CHECKSUM 34, (none) 18)

## Summary
- 3424 nodes · 12376 edges · 137 communities (110 shown, 27 thin omitted)
- Extraction: 90% EXTRACTED · 10% INFERRED · 0% AMBIGUOUS · INFERRED: 1295 edges (avg confidence: 0.85)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `003536e0`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- .of
- EngineTest.php
- App\Support\Number\Decimal
- Instrument
- .zero
- .parse
- InstrumentSync.php
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
- .of
- App\Strategy\Dsl\Position
- App\Market\Stream\PhrityTransport
- Side
- Illuminate\Database\Schema\Blueprint
- generate.py
- SimulatedVenue
- Symbol
- Unit
- OrderManagerTest.php
- RejectionReason
- OrderRole
- Decimal
- EnginePerpTest.php
- App\Market\Data\InstrumentRules
- ValidationStatisticsTest.php
- .of
- Engine
- .expect
- Timeframe
- SimClock
- .times
- BacktestTest.php
- Symbol
- ema-cross-scalp-long.strategy.yaml
- Money
- Kind
- sources
- User
- GridBacktest.php
- package.json
- App\Strategy\Dsl\Document\CheckResult
- BinanceRestClientTest.php
- ema-cross-scalp-short.strategy.yaml
- .handle
- TokensTest.php
- Laravel\Mcp\Response
- postgres
- InstrumentRules
- PreparesBacktests
- StrategyVersionsTest.php
- .measure
- GlobalsTest.php
- RunBacktestCommandTest.php
- OrderManager
- BinanceArchive
- CandleFeedTest.php
- .run
- ArchiveImportTest.php
- IndicatorReferenceTest.php
- SimClock
- App\Strategy\Dsl\Document\DocumentChecker
- Node
- InvalidArgumentException
- Clock
- TradingTool
- scripts
- BinanceRestClient
- App\Simulation\Backtest
- .walk
- composer.json
- jobs
- postgres
- .handle
- Window
- CompiledStrategyTest.php
- App\Support\Number\Decimal
- pint/composer.json
- .count
- RunSimulation.php
- bootstrap/app.php
- require-dev
- scripts-descriptions
- PerpetualBaselinesTest.php
- .evaluate
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
- Illuminate\Database\Eloquent\Model
- .handle
- CompiledStrategyTest.php
- Engine.php
- App\Strategy\Dsl\Compiler\CompiledStrategy
- App\Support\Clock\Clock
- App\Strategy\Contracts\Strategy
- DecimalMathTest.php
- Backfill.php
- .__construct
- McpServerTest.php
- ParserTest.php
- Stringable
- FundingCarry
- OrderType
- DslReference
- ModulesTest.php

## God Nodes (most connected - your core abstractions)
1. `Decimal` - 295 edges
2. `Side` - 182 edges
3. `Symbol` - 169 edges
4. `Candle` - 103 edges
5. `Timeframe` - 99 edges
6. `Price` - 81 edges
7. `Position` - 68 edges
8. `MarketType` - 66 edges
9. `SimulatedVenue` - 56 edges
10. `Ledger` - 56 edges

## Surprising Connections (you probably didn't know these)
- `{closure#4}()` --calls--> `Ema`  [INFERRED]
  tests/Unit/Simulation/ChartSeriesTest.php → app/Strategy/Indicators/Ema.php
- `{closure#2}()` --references--> `FundingRate`  [EXTRACTED]
  tests/Feature/Market/FundingRateRepositoryTest.php → app/Market/Data/FundingRate.php
- `perpFixture()` --calls--> `FundingRate`  [INFERRED]
  tests/Support/backtest-fixtures.php → app/Market/Data/FundingRate.php
- `{closure#10}()` --calls--> `FundingRate`  [INFERRED]
  tests/Unit/Execution/SimulatedPerpVenueTest.php → app/Market/Data/FundingRate.php
- `{closure#11}()` --calls--> `FundingRate`  [INFERRED]
  tests/Unit/Execution/SimulatedPerpVenueTest.php → app/Market/Data/FundingRate.php

## Import Cycles
- None detected.

## Communities (137 total, 27 thin omitted)

### Community 0 - ".of"
Cohesion: 0.16
Nodes (47): RiskState, StrategyLimits, account(), accountPolicy(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}() (+39 more)

### Community 1 - "EngineTest.php"
Cohesion: 0.10
Nodes (32): {closure#1}(), ScriptedStrategy, {closure#1}(), {closure#10}(), {closure#13}(), {closure#14}(), {closure#16}(), {closure#17}() (+24 more)

### Community 2 - "App\Support\Number\Decimal"
Cohesion: 0.06
Nodes (22): {closure#10}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}(), {closure#8}() (+14 more)

### Community 3 - "Instrument"
Cohesion: 0.06
Nodes (20): BackfillStep, CandleRepository, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), CandleSource, Archive (+12 more)

### Community 4 - ".zero"
Cohesion: 0.13
Nodes (12): Liquidity, Maker, Taker, OrderStatus, Cancelled, Expired, Filled, New (+4 more)

### Community 5 - ".parse"
Cohesion: 0.12
Nodes (34): carryDecisions(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#15}(), {closure#16}() (+26 more)

### Community 6 - "InstrumentSync.php"
Cohesion: 0.19
Nodes (5): {closure#1}(), InstrumentSync, SyncResult, {closure#1}(), InstrumentFilter

### Community 7 - "Position"
Cohesion: 0.07
Nodes (16): Argument, BoolLiteral, Call, DurationLiteral, Identifier, Lookback, NumberLiteral, OnTimeframe (+8 more)

### Community 8 - "CompiledStrategy.php"
Cohesion: 0.12
Nodes (7): {closure#1}(), {closure#3}(), {closure#3}(), {closure#34}(), Compiler, Program, {closure#2}()

### Community 9 - "SimulatedVenueTest.php"
Cohesion: 0.15
Nodes (46): OcoRequest, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), btc(), {closure#10}(), {closure#11}() (+38 more)

### Community 10 - "App\Support\Symbol"
Cohesion: 0.19
Nodes (3): EntryOrder, OpenLong, OpenShort

### Community 11 - "SimulatedPerpVenueTest.php"
Cohesion: 0.17
Nodes (25): {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#15}(), {closure#18}() (+17 more)

### Community 12 - "Candle"
Cohesion: 0.13
Nodes (31): {closure#1}(), Candle, {closure#1}(), {closure#2}(), {closure#1}(), {closure#42}(), candleWith(), {closure#1}() (+23 more)

### Community 13 - "ExchangeVerificationTest.php"
Cohesion: 0.10
Nodes (29): {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}() (+21 more)

### Community 14 - "IndicatorsTest.php"
Cohesion: 0.13
Nodes (15): Atr, Ema, Sma, {closure#1}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#16}() (+7 more)

### Community 15 - "AppServiceProvider.php"
Cohesion: 0.14
Nodes (6): SystemSleeper, AppServiceProvider, {closure#2}(), {closure#3}(), {closure#4}(), {closure#6}()

### Community 16 - "Node"
Cohesion: 0.09
Nodes (20): Lexer, Token, TokenKind, Colon, Comma, Dot, Duration, End (+12 more)

### Community 17 - ".of"
Cohesion: 0.11
Nodes (17): AssetMismatch, {closure#2}(), {closure#1}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#15}() (+9 more)

### Community 18 - "App\Strategy\Dsl\Position"
Cohesion: 0.11
Nodes (13): {closure#5}(), DocumentChecker, DslError, YamlList, YamlMap, YamlReader, YamlScalar, YamlValue (+5 more)

### Community 19 - "App\Market\Stream\PhrityTransport"
Cohesion: 0.12
Nodes (3): PhrityTransport, StreamFailed, FakeTransport

### Community 20 - "Side"
Cohesion: 0.08
Nodes (65): ExitReason, Flatten, Liquidation, Signal, Stop, Target, Fill, Marks (+57 more)

### Community 21 - "Illuminate\Database\Schema\Blueprint"
Cohesion: 0.07
Nodes (20): {closure#1}(), {closure#2}(), {closure#3}(), {closure#1}(), {closure#2}(), {closure#1}(), {closure#2}(), {closure#3}() (+12 more)

### Community 22 - "generate.py"
Cohesion: 0.09
Nodes (28): atr(), bars(), bb(), change(), dmi(), donchian(), ema(), highest() (+20 more)

### Community 23 - "SimulatedVenue"
Cohesion: 0.06
Nodes (14): Venue, Balance, ClientOrderId, OrderAck, OrderRequest, VenueOrder, {closure#2}(), ForwardingVenue (+6 more)

### Community 24 - "Symbol"
Cohesion: 0.09
Nodes (12): DonchianBreakout, MovingAverageTrend, BuildableStrategy, Strategy, StrategyIdentity, Subscription, EnterOnceStrategy, HoldStrategy (+4 more)

### Community 25 - "Unit"
Cohesion: 0.13
Nodes (12): {closure#1}(), {closure#2}(), ParamKind, Anchor, Count, Duration, Number, Percent (+4 more)

### Community 26 - "OrderManagerTest.php"
Cohesion: 0.30
Nodes (14): btcUsdt(), {closure#10}(), {closure#15}(), {closure#16}(), {closure#2}(), {closure#4}(), {closure#5}(), {closure#6}() (+6 more)

### Community 27 - "RejectionReason"
Cohesion: 0.07
Nodes (28): {closure#2}(), AccountView, RejectionReason, BelowMinimum, Cooldown, DuplicateIntent, EntryWorking, GrossExposure (+20 more)

### Community 28 - "OrderRole"
Cohesion: 0.15
Nodes (15): OrderRole, Entry, Exit, Flatten, Stop, Target, TrackedOrder, {closure#1}() (+7 more)

### Community 29 - "Decimal"
Cohesion: 0.05
Nodes (20): ProtectionPlan, VenuePosition, {closure#1}(), SimulatedPosition, PerpPosition, Position, RoundTrip, {closure#1}() (+12 more)

### Community 30 - "EnginePerpTest.php"
Cohesion: 0.30
Nodes (13): {closure#10}(), {closure#11}(), {closure#14}(), {closure#5}(), {closure#6}(), {closure#7}(), {closure#8}(), {closure#9}() (+5 more)

### Community 31 - "App\Market\Data\InstrumentRules"
Cohesion: 0.13
Nodes (5): {closure#2}(), {closure#3}(), SimulatedFuturesBook, SimulatedOrder, {closure#1}()

### Community 32 - "ValidationStatisticsTest.php"
Cohesion: 0.11
Nodes (20): Cscv, DeflatedSharpe, Normal, closeTo(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}() (+12 more)

### Community 33 - ".of"
Cohesion: 0.09
Nodes (31): {closure#1}(), {closure#2}(), {closure#3}(), documentedLimits(), {closure#44}(), {closure#45}(), {closure#46}(), {closure#47}() (+23 more)

### Community 34 - "Engine"
Cohesion: 0.11
Nodes (8): DecisionLog, DecisionRecord, EngineRecord, IntentRecord, Engine, ReconciliationMismatch, {closure#3}(), {closure#2}()

### Community 35 - ".expect"
Cohesion: 0.21
Nodes (12): CompileStrategy, CheckResult, PolicyLimits, checkDocument(), {closure#2}(), {closure#4}(), {closure#5}(), {closure#6}() (+4 more)

### Community 36 - "Timeframe"
Cohesion: 0.11
Nodes (15): {closure#2}(), {closure#4}(), Timeframe, FifteenMinutes, FiveMinutes, FourHours, OneDay, OneHour (+7 more)

### Community 37 - "SimClock"
Cohesion: 0.11
Nodes (18): {closure#11}(), {closure#13}(), {closure#14}(), {closure#15}(), {closure#16}(), {closure#4}(), {closure#5}(), {closure#6}() (+10 more)

### Community 38 - ".times"
Cohesion: 0.09
Nodes (7): {closure#2}(), Percent, Perpetual, {closure#16}(), {closure#5}(), {closure#6}(), {closure#7}()

### Community 39 - "BacktestTest.php"
Cohesion: 0.17
Nodes (13): CandleFeed, FundingRate, Backtest, InMemoryCandleFeed, Policies, backtestSetup(), {closure#1}(), {closure#11}() (+5 more)

### Community 40 - "Symbol"
Cohesion: 0.06
Nodes (11): {closure#7}(), {closure#1}(), BuyAndHold, Parameters, Close, Intent, MoveStop, OpenCarry (+3 more)

### Community 41 - "ema-cross-scalp-long.strategy.yaml"
Cohesion: 0.06
Nodes (33): entries, long, exits, stop_loss, take_profit, time_stop, when, fails_when (+25 more)

### Community 42 - "Money"
Cohesion: 0.13
Nodes (14): FeeSchedule, CostModel, RiskProfile, Account, Benchmark, RiskPolicy, {closure#1}(), {closure#2}() (+6 more)

### Community 43 - "Kind"
Cohesion: 0.12
Nodes (18): {closure#7}(), {closure#8}(), {closure#9}(), EntrySpec, Expression, DslWriter, FunctionSpec, Scope (+10 more)

### Community 44 - "sources"
Cohesion: 0.09
Nodes (22): secret, type, sources, auth, channels, connector, exchange, id (+14 more)

### Community 46 - "GridBacktest.php"
Cohesion: 0.07
Nodes (21): {closure#10}(), {closure#11}(), {closure#14}(), {closure#15}(), {closure#17}(), {closure#18}(), {closure#19}(), {closure#2}() (+13 more)

### Community 47 - "package.json"
Cohesion: 0.13
Nodes (20): devDependencies, concurrently@^10.0.3, laravel-vite-plugin@^3.1, tailwindcss@^4.0.0, @tailwindcss/vite@^4.0.0, vite@^8.0.0, optionalDependencies, @laravel/multiplex@^0.4.1 (+12 more)

### Community 48 - "App\Strategy\Dsl\Document\CheckResult"
Cohesion: 0.19
Nodes (3): {closure#1}(), SaveResult, StrategyVersion

### Community 49 - "BinanceRestClientTest.php"
Cohesion: 0.12
Nodes (20): Sleeper, binanceClient(), {closure#1}(), {closure#11}(), {closure#13}(), {closure#3}(), {closure#4}(), {closure#5}() (+12 more)

### Community 50 - "ema-cross-scalp-short.strategy.yaml"
Cohesion: 0.06
Nodes (33): entries, short, exits, stop_loss, take_profit, time_stop, when, fails_when (+25 more)

### Community 51 - ".handle"
Cohesion: 0.33
Nodes (5): CanonicalJson, {closure#1}(), {closure#3}(), {closure#4}(), {closure#5}()

### Community 52 - "TokensTest.php"
Cohesion: 0.31
Nodes (7): {closure#3}(), {closure#4}(), {closure#5}(), contrastRatio(), contrastRules(), relativeLuminance(), themeTokens()

### Community 53 - "Laravel\Mcp\Response"
Cohesion: 0.15
Nodes (3): {closure#2}(), ChartSeries, StrategyLibrary

### Community 54 - "postgres"
Cohesion: 0.10
Nodes (19): POSTGRES_DB, POSTGRES_PASSWORD, POSTGRES_USER, interval, retries, test, timeout, environment (+11 more)

### Community 55 - "InstrumentRules"
Cohesion: 0.30
Nodes (11): InstrumentRules, RulesHistory, {closure#2}(), {closure#4}(), {closure#41}(), {closure#1}(), {closure#2}(), {closure#3}() (+3 more)

### Community 57 - "StrategyVersionsTest.php"
Cohesion: 0.15
Nodes (10): InstrumentDirectory, StoredInstruments, {closure#1}(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}() (+2 more)

### Community 58 - ".measure"
Cohesion: 0.17
Nodes (9): EquityTracker, Performance, {closure#1}(), {closure#2}(), {closure#3}(), {closure#5}(), {closure#6}(), {closure#8}() (+1 more)

### Community 59 - "GlobalsTest.php"
Cohesion: 0.14
Nodes (4): ChartTool, GetCandles, GetIndicators, Controller

### Community 60 - "RunBacktestCommandTest.php"
Cohesion: 0.19
Nodes (21): {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#15}(), {closure#16}() (+13 more)

### Community 62 - "BinanceArchive"
Cohesion: 0.11
Nodes (5): ArchivePeriod, BinanceArchive, Backfill, BackfillReport, BackfillMarketData

### Community 63 - "CandleFeedTest.php"
Cohesion: 0.25
Nodes (15): MinuteCandles, {closure#1}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#2}(), {closure#3}(), {closure#4}() (+7 more)

### Community 64 - ".run"
Cohesion: 0.13
Nodes (18): PerformanceMetrics, Report, btcMinutes(), {closure#1}(), {closure#2}(), {closure#3}(), referencePrice(), {closure#1}() (+10 more)

### Community 65 - "ArchiveImportTest.php"
Cohesion: 0.29
Nodes (10): archiveBytes(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}() (+2 more)

### Community 66 - "IndicatorReferenceTest.php"
Cohesion: 0.05
Nodes (26): {closure#35}(), Change, DonchianChannel, Highest, Hma, Lowest, Macd, RealizedVolatility (+18 more)

### Community 67 - "SimClock"
Cohesion: 0.08
Nodes (29): SimClock, WallClock, candleStore(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}() (+21 more)

### Community 68 - "App\Strategy\Dsl\Document\DocumentChecker"
Cohesion: 0.08
Nodes (23): {closure#3}(), {closure#4}(), ParameterSpec, Library, {closure#1}(), Kind, Condition, Duration (+15 more)

### Community 69 - "Node"
Cohesion: 0.23
Nodes (12): Binary, Node, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}() (+4 more)

### Community 70 - "InvalidArgumentException"
Cohesion: 0.09
Nodes (9): {closure#1}(), RunBacktest, RunGrid, SimulationTool, SimulationQueue, {closure#1}(), Period, {closure#1}() (+1 more)

### Community 71 - "Clock"
Cohesion: 0.06
Nodes (14): BinanceEndpoints, CheckMarketData, SelectsInstruments, {closure#1}(), StreamCandles, VerifyMarketData, FundingRateRepository, Heartbeats (+6 more)

### Community 72 - "TradingTool"
Cohesion: 0.10
Nodes (10): CompileStrategy, GetFunding, GetJob, GetStrategy, ListInstruments, ListStrategies, ListTrials, SaveStrategy (+2 more)

### Community 73 - "scripts"
Cohesion: 0.14
Nodes (14): scripts, analyse, dev, format, mutate, post-autoload-dump, post-update-cmd, pre-package-uninstall (+6 more)

### Community 74 - "BinanceRestClient"
Cohesion: 0.06
Nodes (27): {closure#2}(), BinanceRestClient, {closure#1}(), EpochTime, ExchangeNumber, Json, InstrumentDefinition, InstrumentStatus (+19 more)

### Community 75 - "App\Simulation\Backtest"
Cohesion: 0.17
Nodes (10): EngineMode, Backtest, Live, Paper, Testnet, InMemoryDecisionLog, {closure#1}(), {closure#2}() (+2 more)

### Community 76 - ".walk"
Cohesion: 0.20
Nodes (17): CandleAggregator, CandleSequenceBroken, {closure#6}(), aggregate(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}() (+9 more)

### Community 77 - "composer.json"
Cohesion: 0.17
Nodes (11): autoload-dev, psr-4, description, keywords, license, minimum-stability, name, prefer-stable (+3 more)

### Community 78 - "jobs"
Cohesion: 0.17
Nodes (11): concurrency, cancel-in-progress, group, jobs, name, on, pull_request, push (+3 more)

### Community 79 - "postgres"
Cohesion: 0.17
Nodes (12): POSTGRES_DB, POSTGRES_PASSWORD, POSTGRES_USER, env, image, options, ports, image (+4 more)

### Community 82 - "CompiledStrategyTest.php"
Cohesion: 0.15
Nodes (17): PositionView, StrategyContext, {closure#24}(), aggregatedBars(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#16}() (+9 more)

### Community 83 - "App\Support\Number\Decimal"
Cohesion: 0.08
Nodes (37): {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#15}(), {closure#16}(), {closure#17}() (+29 more)

### Community 84 - "pint/composer.json"
Cohesion: 0.20
Nodes (9): config, sort-packages, description, license, name, require, laravel/pint, php (+1 more)

### Community 85 - ".count"
Cohesion: 0.18
Nodes (13): Statistics, Donchian, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}() (+5 more)

### Community 86 - "RunSimulation.php"
Cohesion: 0.10
Nodes (6): RunSimulation, SimulationJob, {closure#1}(), {closure#2}(), {closure#3}(), savedEmaCross()

### Community 87 - "bootstrap/app.php"
Cohesion: 0.17
Nodes (4): AuthenticateMcpToken, {closure#1}(), {closure#2}(), {closure#3}()

### Community 88 - "require-dev"
Cohesion: 0.22
Nodes (9): require-dev, fakerphp/faker, larastan/larastan, laravel/pail, laravel/pao, mockery/mockery, nunomaduro/collision, pestphp/pest (+1 more)

### Community 89 - "scripts-descriptions"
Cohesion: 0.22
Nodes (9): scripts-descriptions, analyse, format, mutate, test, test:llm, test:network, test:testnet (+1 more)

### Community 90 - "PerpetualBaselinesTest.php"
Cohesion: 0.29
Nodes (10): MarketType, Perp, Spot, {closure#1}(), {closure#3}(), goldenMinutes(), goldenRates(), perpetualReferencePrice() (+2 more)

### Community 91 - ".evaluate"
Cohesion: 0.25
Nodes (8): WalkForward, WalkForwardSet, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), wfSet()

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
Nodes (8): Income, IncomeKind, Funding, Liquidation, OrderUpdate, UpdateStream, {closure#3}(), {closure#1}()

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

### Community 108 - "extra"
Cohesion: 0.67
Nodes (3): extra, laravel, dont-discover

### Community 119 - "Illuminate\Database\Eloquent\Model"
Cohesion: 0.12
Nodes (6): {closure#1}(), McpCall, McpToken, BacktestRun, StrategyTrial, StrategyStage

### Community 121 - "CompiledStrategyTest.php"
Cohesion: 0.09
Nodes (16): {closure#37}(), BarIndicator, DirectionalMovement, Keltner, Natr, Vwap, chart(), {closure#1}() (+8 more)

### Community 122 - "Engine.php"
Cohesion: 0.08
Nodes (10): {closure#4}(), {closure#6}(), {closure#1}(), {closure#1}(), RiskEvent, RiskEventKind, DailyLossHalt, DrawdownFlatten (+2 more)

### Community 123 - "App\Strategy\Dsl\Compiler\CompiledStrategy"
Cohesion: 0.11
Nodes (13): {closure#1}(), {closure#2}(), {closure#5}(), {closure#6}(), Group, Agent, Exit, Indicator (+5 more)

### Community 124 - "App\Support\Clock\Clock"
Cohesion: 0.15
Nodes (7): CreateMcpToken, RevokeMcpToken, SyncInstruments, ExportStrategies, ImportStrategies, {closure#1}(), StrategyRepository

### Community 125 - "App\Strategy\Contracts\Strategy"
Cohesion: 0.11
Nodes (6): {closure#1}(), LimitedStrategy, CompiledStrategy, RenamedStrategy, {closure#18}(), {closure#37}()

### Community 126 - "DecimalMathTest.php"
Cohesion: 0.22
Nodes (10): DecimalMath, {closure#1}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#5}(), {closure#6}() (+2 more)

### Community 127 - "Backfill.php"
Cohesion: 0.08
Nodes (15): ArchiveMissing, ChecksumMismatch, {closure#1}(), {closure#2}(), {closure#3}(), BinanceRequestFailed, LocationRestricted, RateLimited (+7 more)

### Community 129 - "McpServerTest.php"
Cohesion: 0.27
Nodes (11): actAsToken(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}(), {closure#8}() (+3 more)

### Community 130 - "ParserTest.php"
Cohesion: 0.36
Nodes (6): AstPrinter, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), sexpr()

### Community 133 - "OrderType"
Cohesion: 0.33
Nodes (5): OrderType, Limit, Market, StopMarket, TakeProfitMarket

## Knowledge Gaps
- **121 isolated node(s):** `DailyLossHalt`, `DrawdownFlatten`, `DrawdownHalt`, `WeeklyLossHalt`, `Agent` (+116 more)
  These have ≤1 connection - possible missing edges. (Counts symbols only; 618 node(s) total have ≤1 connection when file, concept and rationale nodes are included.)
- **27 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `Decimal` connect `Decimal` to `.__construct`, `EngineTest.php`, `App\Support\Number\Decimal`, `.of`, `.zero`, `FundingCarry`, `InstrumentSync.php`, `Position`, `CompiledStrategy.php`, `SimulatedVenueTest.php`, `Stringable`, `SimulatedPerpVenueTest.php`, `Candle`, `.parse`, `IndicatorsTest.php`, `.of`, `Side`, `SimulatedVenue`, `Symbol`, `OrderManagerTest.php`, `RejectionReason`, `OrderRole`, `EnginePerpTest.php`, `App\Market\Data\InstrumentRules`, `ValidationStatisticsTest.php`, `.of`, `Engine`, `.times`, `BacktestTest.php`, `Symbol`, `Money`, `GridBacktest.php`, `.handle`, `InstrumentRules`, `PreparesBacktests`, `.measure`, `RunBacktestCommandTest.php`, `OrderManager`, `CandleFeedTest.php`, `.run`, `SimClock`, `Node`, `InvalidArgumentException`, `Clock`, `BinanceRestClient`, `App\Simulation\Backtest`, `.walk`, `CompiledStrategyTest.php`, `.count`, `PerpetualBaselinesTest.php`, `OrderUpdate`, `CompiledStrategyTest.php`, `Engine.php`, `DecimalMathTest.php`?**
  _High betweenness centrality (0.125) - this node is a cross-community bridge._
- **What connects `DailyLossHalt`, `DrawdownFlatten`, `DrawdownHalt` to the rest of the system?**
  _121 weakly-connected nodes found - possible missing edges._
- **Should `EngineTest.php` be split into smaller, more focused modules?**
  _Cohesion score 0.10102843315184513 - nodes in this community are weakly interconnected._
- **Why does `Symbol` connect `Symbol` to `App\Support\Number\Decimal`, `Instrument`, `.zero`, `FundingCarry`, `.parse`, `Stringable`, `SimulatedVenueTest.php`, `App\Support\Symbol`, `SimulatedPerpVenueTest.php`, `ExchangeVerificationTest.php`, `AppServiceProvider.php`, `Side`, `SimulatedVenue`, `Symbol`, `OrderManagerTest.php`, `RejectionReason`, `OrderRole`, `Decimal`, `EnginePerpTest.php`, `App\Market\Data\InstrumentRules`, `Engine`, `SimClock`, `BacktestTest.php`, `Money`, `BinanceRestClientTest.php`, `.handle`, `InstrumentRules`, `.measure`, `RunBacktestCommandTest.php`, `OrderManager`, `CandleFeedTest.php`, `.run`, `SimClock`, `Clock`, `BinanceRestClient`, `App\Simulation\Backtest`, `CompiledStrategyTest.php`, `PerpetualBaselinesTest.php`, `OrderUpdate`, `Engine.php`, `App\Strategy\Contracts\Strategy`?**
  _High betweenness centrality (0.037) - this node is a cross-community bridge._
- **Should `App\Support\Number\Decimal` be split into smaller, more focused modules?**
  _Cohesion score 0.06110102843315184 - nodes in this community are weakly interconnected._
- **Why does `Timeframe` connect `Timeframe` to `.__construct`, `EngineTest.php`, `Instrument`, `FundingCarry`, `.parse`, `Position`, `CompiledStrategy.php`, `SimulatedVenueTest.php`, `Candle`, `IndicatorsTest.php`, `App\Strategy\Dsl\Position`, `Side`, `Symbol`, `OrderManagerTest.php`, `EnginePerpTest.php`, `BacktestTest.php`, `Symbol`, `BinanceRestClientTest.php`, `PreparesBacktests`, `BinanceArchive`, `CandleFeedTest.php`, `SimClock`, `Node`, `Clock`, `BinanceRestClient`, `.walk`, `CompiledStrategyTest.php`, `CompiledStrategyTest.php`, `Backfill.php`?**
  _High betweenness centrality (0.027) - this node is a cross-community bridge._
- **Should `Instrument` be split into smaller, more focused modules?**
  _Cohesion score 0.060528559249786874 - nodes in this community are weakly interconnected._