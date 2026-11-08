# Graph Report - trading-platform  (2026-11-08)

## Corpus Check
- 450 files · ~171,576 words
- Verdict: corpus is large enough that graph structure adds value.
- Unclassified: 92 file(s) not represented in the graph (top: .zip 34, .CHECKSUM 34, (none) 18)

## Summary
- 3716 nodes · 13036 edges · 145 communities (120 shown, 25 thin omitted)
- Extraction: 90% EXTRACTED · 10% INFERRED · 0% AMBIGUOUS · INFERRED: 1352 edges (avg confidence: 0.85)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `be4d93ba`
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
- App\Support\Clock\Clock
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
- InvalidArgumentException
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
- donchian-breakout-short.strategy.yaml
- .measure
- ChartSeries
- RunBacktestCommandTest.php
- Symbol
- BinanceArchive
- CandleFeedTest.php
- OrderManager
- donchian-breakout.strategy.yaml
- IndicatorReferenceTest.php
- CandleRepositoryTest.php
- App\Strategy\Dsl\Document\DocumentChecker
- .of
- OrderUpdate
- Backfill
- Money
- scripts
- ma-trend-short.strategy.yaml
- App\Market\Stream\PhrityTransport
- .walk
- composer.json
- jobs
- postgres
- ma-trend.strategy.yaml
- CompiledStrategyTest.php
- App\Support\Number\Decimal
- pint/composer.json
- MonteCarlo
- RunSimulation.php
- .times
- require-dev
- scripts-descriptions
- .run
- .fromStrings
- config
- uses
- test
- with
- .sqrt
- run
- psr-4
- require
- DatabaseSeeder
- steps
- steps
- lint
- .evaluate
- Carbon\CarbonImmutable
- buy-and-hold.strategy.yaml
- App\Market\Data\Candle
- FundingCarry
- Price
- bootstrap/app.php
- EngineMode
- .evaluate
- MarketType
- funding-carry.strategy.yaml
- McpServerTest.php
- .parse
- Decimal
- App\Simulation\Backtest
- Price
- .parse
- .encode
- Percent
- Close
- Lexer
- Group
- MinuteCandles

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
- `baselineDocument()` --calls--> `PolicyLimits`  [INFERRED]
  tests/Golden/DslBaselinesTest.php → app/Strategy/Dsl/Document/PolicyLimits.php
- `{closure#13}()` --calls--> `Participant`  [INFERRED]
  tests/Golden/DslBaselinesTest.php → app/Simulation/Data/Participant.php
- `{closure#16}()` --calls--> `Participant`  [INFERRED]
  tests/Golden/DslBaselinesTest.php → app/Simulation/Data/Participant.php
- `{closure#4}()` --references--> `Candle`  [EXTRACTED]
  tests/Feature/Market/BinanceRestClientTest.php → app/Market/Data/Candle.php
- `{closure#7}()` --references--> `Candle`  [EXTRACTED]
  tests/Feature/Market/CandleRepositoryTest.php → app/Market/Data/Candle.php

## Import Cycles
- None detected.

## Communities (145 total, 25 thin omitted)

### Community 0 - ".of"
Cohesion: 0.16
Nodes (45): RiskState, LimitedStrategy, StrategyLimits, account(), accountPolicy(), {closure#1}(), {closure#10}(), {closure#11}() (+37 more)

### Community 1 - "EngineTest.php"
Cohesion: 0.11
Nodes (31): ScriptedStrategy, {closure#1}(), {closure#10}(), {closure#13}(), {closure#14}(), {closure#16}(), {closure#17}(), {closure#2}() (+23 more)

### Community 2 - "App\Simulation\Metrics\Performance"
Cohesion: 0.10
Nodes (16): {closure#1}(), {closure#2}(), {closure#4}(), {closure#6}(), {closure#7}(), {closure#10}(), {closure#2}(), {closure#5}() (+8 more)

### Community 3 - "Instrument"
Cohesion: 0.09
Nodes (13): BinanceEndpoints, Sleeper, CheckMarketData, SelectsInstruments, {closure#1}(), StreamCandles, VerifyMarketData, Heartbeats (+5 more)

### Community 4 - "OrderStatus"
Cohesion: 0.08
Nodes (14): Liquidity, Maker, Taker, OrderStatus, Cancelled, Expired, Filled, New (+6 more)

### Community 5 - ".parse"
Cohesion: 0.12
Nodes (35): Parameters, {closure#17}(), carryDecisions(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}() (+27 more)

### Community 6 - "Instrument"
Cohesion: 0.08
Nodes (11): CandleRepository, {closure#2}(), {closure#4}(), CandleSource, Archive, Rest, Stream, Instrument (+3 more)

### Community 7 - "Position"
Cohesion: 0.10
Nodes (14): Argument, BoolLiteral, Call, DurationLiteral, Identifier, Lookback, Node, NumberLiteral (+6 more)

### Community 8 - "CompiledStrategy.php"
Cohesion: 0.09
Nodes (11): {closure#1}(), {closure#2}(), {closure#3}(), {closure#3}(), {closure#4}(), CompiledStrategy, {closure#33}(), Compiler (+3 more)

### Community 9 - "SimulatedVenueTest.php"
Cohesion: 0.18
Nodes (42): btc(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#15}(), {closure#16}() (+34 more)

### Community 10 - "App\Support\Symbol"
Cohesion: 0.12
Nodes (7): {closure#6}(), {closure#7}(), {closure#1}(), EntryOrder, Intent, OpenLong, OpenShort

### Community 11 - "SimulatedPerpVenueTest.php"
Cohesion: 0.19
Nodes (25): {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#15}(), {closure#18}() (+17 more)

### Community 12 - "Candle"
Cohesion: 0.14
Nodes (30): {closure#1}(), {closure#2}(), {closure#1}(), {closure#3}(), Candle, {closure#1}(), {closure#2}(), candleWith() (+22 more)

### Community 13 - "ExchangeVerificationTest.php"
Cohesion: 0.11
Nodes (30): {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#2}(), {closure#3}(), {closure#4}() (+22 more)

### Community 14 - "IndicatorsTest.php"
Cohesion: 0.09
Nodes (20): Donchian, Ema, Period, Sma, bar(), {closure#1}(), {closure#10}(), {closure#12}() (+12 more)

### Community 15 - "AppServiceProvider.php"
Cohesion: 0.12
Nodes (7): SystemSleeper, AppServiceProvider, {closure#2}(), {closure#3}(), {closure#4}(), {closure#6}(), SimulationQueue

### Community 16 - "Node"
Cohesion: 0.11
Nodes (28): Binary, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}() (+20 more)

### Community 17 - "App\Support\Clock\Clock"
Cohesion: 0.08
Nodes (19): CreateMcpToken, RevokeMcpToken, InstrumentDirectory, CompileStrategy, PolicyLimits, StrategyRepository, {closure#1}(), {closure#2}() (+11 more)

### Community 18 - "App\Strategy\Dsl\Position"
Cohesion: 0.11
Nodes (12): {closure#5}(), DocumentChecker, DslError, YamlList, YamlMap, YamlReader, YamlScalar, YamlValue (+4 more)

### Community 19 - "RuntimeException"
Cohesion: 0.11
Nodes (7): ArchiveMissing, ChecksumMismatch, VerificationSource, DailyArchive, Rest, StreamFailed, FakeTransport

### Community 20 - "Ledger"
Cohesion: 0.11
Nodes (32): Marks, Ledger, LedgerViolation, balanceOf(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}() (+24 more)

### Community 21 - "Illuminate\Database\Schema\Blueprint"
Cohesion: 0.06
Nodes (22): {closure#1}(), {closure#2}(), {closure#3}(), {closure#1}(), {closure#2}(), {closure#1}(), {closure#2}(), {closure#3}() (+14 more)

### Community 22 - "generate.py"
Cohesion: 0.09
Nodes (28): atr(), bars(), bb(), change(), dmi(), donchian(), ema(), highest() (+20 more)

### Community 23 - "ReadBack"
Cohesion: 0.18
Nodes (3): {closure#6}(), {closure#7}(), ReadBack

### Community 24 - "Symbol"
Cohesion: 0.06
Nodes (13): BuyAndHold, DonchianBreakout, MovingAverageTrend, BuildableStrategy, Strategy, StrategyIdentity, Subscription, EnterOnceStrategy (+5 more)

### Community 25 - "ParamKind"
Cohesion: 0.13
Nodes (12): {closure#2}(), {closure#1}(), {closure#2}(), ParamKind, Anchor, Count, Duration, Number (+4 more)

### Community 26 - "RejectionReason"
Cohesion: 0.08
Nodes (28): AccountView, RejectionReason, BelowMinimum, Cooldown, DuplicateIntent, EntryWorking, GrossExposure, Halted (+20 more)

### Community 27 - "Expression"
Cohesion: 0.14
Nodes (8): CarryTemplate, CheckResult, {closure#7}(), EntrySpec, ExitSpec, Expression, StrategySpec, {closure#1}()

### Community 28 - "App\Strategy\Dsl\Compiler\CompiledStrategy"
Cohesion: 0.14
Nodes (6): {closure#1}(), {closure#6}(), {closure#1}(), DslWriter, {closure#2}(), {closure#5}()

### Community 29 - "Fill"
Cohesion: 0.10
Nodes (9): Fill, PerpPosition, Position, RoundTrip, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}() (+1 more)

### Community 30 - "EnginePerpTest.php"
Cohesion: 0.26
Nodes (15): {closure#10}(), {closure#11}(), {closure#13}(), {closure#14}(), {closure#2}(), {closure#5}(), {closure#6}(), {closure#7}() (+7 more)

### Community 31 - "DslBaselinesTest.php"
Cohesion: 0.11
Nodes (21): {closure#2}(), {closure#3}(), {closure#2}(), RenamedStrategy, baselineDocument(), baselinePairs(), {closure#10}(), {closure#11}() (+13 more)

### Community 32 - "ValidationStatisticsTest.php"
Cohesion: 0.09
Nodes (23): {closure#3}(), {closure#4}(), Cscv, DeflatedSharpe, Normal, {closure#3}(), closeTo(), {closure#1}() (+15 more)

### Community 33 - ".of"
Cohesion: 0.07
Nodes (50): DecimalMath, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#1}(), {closure#2}(), {closure#3}() (+42 more)

### Community 34 - "Carbon\CarbonImmutable"
Cohesion: 0.16
Nodes (6): DecisionLog, EngineRecord, IntentRecord, Engine, {closure#2}(), ReconciliationMismatch

### Community 35 - "Side"
Cohesion: 0.17
Nodes (30): ExitReason, Flatten, Liquidation, Signal, Stop, Target, Side, Buy (+22 more)

### Community 36 - "Timeframe"
Cohesion: 0.11
Nodes (14): InvalidCandle, Timeframe, FifteenMinutes, FiveMinutes, FourHours, OneDay, OneHour, OneMinute (+6 more)

### Community 37 - "SimClock"
Cohesion: 0.08
Nodes (26): SimClock, WallClock, {closure#11}(), {closure#14}(), {closure#15}(), {closure#16}(), {closure#4}(), {closure#6}() (+18 more)

### Community 38 - "InvalidArgumentException"
Cohesion: 0.09
Nodes (15): {closure#3}(), FundingRate, DatabaseCandleFeed, {closure#1}(), FundingRateRepository, Rows, GridPart, {closure#1}() (+7 more)

### Community 39 - "BacktestTest.php"
Cohesion: 0.14
Nodes (18): FeeSchedule, CostModel, CandleFeed, Backtest, Participant, {closure#1}(), RunDefinition, InMemoryCandleFeed (+10 more)

### Community 40 - "RiskPolicy"
Cohesion: 0.13
Nodes (11): RiskProfile, Account, Benchmark, {closure#1}(), RiskPolicy, {closure#2}(), {closure#1}(), Policies (+3 more)

### Community 41 - "ema-cross-scalp-long.strategy.yaml"
Cohesion: 0.06
Nodes (33): entries, long, exits, stop_loss, take_profit, time_stop, when, fails_when (+25 more)

### Community 42 - "GridBacktest.php"
Cohesion: 0.07
Nodes (21): {closure#11}(), {closure#12}(), {closure#13}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}() (+13 more)

### Community 43 - "autoload-dev"
Cohesion: 0.67
Nodes (3): autoload-dev, psr-4, Tests\\

### Community 44 - "OrderRole"
Cohesion: 0.12
Nodes (29): OrderRole, Entry, Exit, Flatten, Stop, Target, TrackedOrder, {closure#1}() (+21 more)

### Community 45 - "User"
Cohesion: 0.10
Nodes (3): User, UserFactory, {closure#1}()

### Community 46 - "Illuminate\Database\Eloquent\Model"
Cohesion: 0.09
Nodes (10): {closure#1}(), McpCall, McpToken, {closure#1}(), InstrumentFilter, BacktestRun, StrategyGateReport, StrategyHoldout (+2 more)

### Community 47 - "package.json"
Cohesion: 0.13
Nodes (20): devDependencies, concurrently@^10.0.3, laravel-vite-plugin@^3.1, tailwindcss@^4.0.0, @tailwindcss/vite@^4.0.0, vite@^8.0.0, optionalDependencies, @laravel/multiplex@^0.4.1 (+12 more)

### Community 48 - "DocumentCheckerTest.php"
Cohesion: 0.15
Nodes (12): {closure#1}(), SaveResult, StrategyVersion, checkDocument(), {closure#2}(), {closure#4}(), {closure#5}(), {closure#6}() (+4 more)

### Community 49 - "BinanceRestClientTest.php"
Cohesion: 0.11
Nodes (22): BinanceRequestFailed, LocationRestricted, RateLimited, binanceClient(), {closure#1}(), {closure#11}(), {closure#13}(), {closure#3}() (+14 more)

### Community 50 - "ema-cross-scalp-short.strategy.yaml"
Cohesion: 0.06
Nodes (33): entries, short, exits, stop_loss, take_profit, time_stop, when, fails_when (+25 more)

### Community 52 - "TokensTest.php"
Cohesion: 0.31
Nodes (7): {closure#3}(), {closure#4}(), {closure#5}(), contrastRatio(), contrastRules(), relativeLuminance(), themeTokens()

### Community 53 - "Laravel\Mcp\Response"
Cohesion: 0.06
Nodes (18): ChartTool, CompileStrategy, DslReference, GetCandles, GetFunding, GetIndicators, GetJob, GetStrategy (+10 more)

### Community 54 - "postgres"
Cohesion: 0.10
Nodes (19): POSTGRES_DB, POSTGRES_PASSWORD, POSTGRES_USER, interval, retries, test, timeout, environment (+11 more)

### Community 55 - "InstrumentRules"
Cohesion: 0.12
Nodes (20): InstrumentRules, {closure#1}(), RulesHistory, {closure#13}(), {closure#5}(), {closure#12}(), {closure#2}(), {closure#1}() (+12 more)

### Community 56 - "App\Support\Clock\Clock"
Cohesion: 0.10
Nodes (6): StoredInstruments, {closure#1}(), ExportStrategies, GridBacktest, ImportStrategies, PreparesBacktests

### Community 57 - "donchian-breakout-short.strategy.yaml"
Cohesion: 0.07
Nodes (26): entries, short, default, range, step, default, range, step (+18 more)

### Community 58 - ".measure"
Cohesion: 0.16
Nodes (5): EquityTracker, Performance, {closure#1}(), {closure#2}(), trackedTwoDays()

### Community 60 - "RunBacktestCommandTest.php"
Cohesion: 0.10
Nodes (23): {closure#4}(), {closure#6}(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}() (+15 more)

### Community 61 - "Symbol"
Cohesion: 0.18
Nodes (5): VenuePosition, {closure#1}(), SimulatedPosition, OpenCarry, Symbol

### Community 62 - "BinanceArchive"
Cohesion: 0.11
Nodes (12): ArchivePeriod, BinanceArchive, archiveBytes(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}() (+4 more)

### Community 63 - "CandleFeedTest.php"
Cohesion: 0.44
Nodes (11): {closure#1}(), {closure#12}(), {closure#13}(), {closure#2}(), {closure#4}(), {closure#5}(), {closure#8}(), {closure#9}() (+3 more)

### Community 64 - "OrderManager"
Cohesion: 0.21
Nodes (3): {closure#4}(), {closure#1}(), OrderManager

### Community 65 - "donchian-breakout.strategy.yaml"
Cohesion: 0.07
Nodes (26): entries, long, default, range, step, default, range, step (+18 more)

### Community 66 - "IndicatorReferenceTest.php"
Cohesion: 0.07
Nodes (34): Atr, BarIndicator, Bollinger, Change, DonchianChannel, Highest, Hma, Keltner (+26 more)

### Community 67 - "CandleRepositoryTest.php"
Cohesion: 0.28
Nodes (11): candleStore(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}() (+3 more)

### Community 68 - "App\Strategy\Dsl\Document\DocumentChecker"
Cohesion: 0.07
Nodes (31): {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#9}(), ParameterSpec, FunctionSpec, Library (+23 more)

### Community 69 - ".of"
Cohesion: 0.13
Nodes (20): AssetMismatch, {closure#16}(), {closure#2}(), {closure#40}(), {closure#1}(), usdt(), {closure#3}(), {closure#6}() (+12 more)

### Community 70 - "OrderUpdate"
Cohesion: 0.19
Nodes (8): Income, IncomeKind, Funding, Liquidation, OrderUpdate, VenueFill, UpdateStream, {closure#3}()

### Community 71 - "Backfill"
Cohesion: 0.16
Nodes (5): Backfill, BackfillReport, BackfillStep, BackfillMarketData, UpsertResult

### Community 72 - "Money"
Cohesion: 0.11
Nodes (3): Asset, Money, Quantity

### Community 73 - "scripts"
Cohesion: 0.15
Nodes (13): scripts, analyse, dev, format, mutate, post-autoload-dump, post-update-cmd, pre-package-uninstall (+5 more)

### Community 74 - "ma-trend-short.strategy.yaml"
Cohesion: 0.07
Nodes (26): entries, short, exits, stop_loss, when, fails_when, family, default (+18 more)

### Community 76 - ".walk"
Cohesion: 0.21
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

### Community 80 - "ma-trend.strategy.yaml"
Cohesion: 0.07
Nodes (26): entries, long, exits, stop_loss, when, fails_when, family, default (+18 more)

### Community 82 - "CompiledStrategyTest.php"
Cohesion: 0.17
Nodes (16): PositionView, StrategyContext, aggregatedBars(), {closure#11}(), {closure#13}(), {closure#16}(), {closure#17}(), {closure#2}() (+8 more)

### Community 83 - "App\Support\Number\Decimal"
Cohesion: 0.06
Nodes (43): WalkForwardResult, {closure#5}(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}() (+35 more)

### Community 84 - "pint/composer.json"
Cohesion: 0.20
Nodes (9): config, sort-packages, description, license, name, require, laravel/pint, php (+1 more)

### Community 85 - "MonteCarlo"
Cohesion: 0.14
Nodes (11): {closure#4}(), {closure#5}(), MonteCarlo, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}() (+3 more)

### Community 86 - "RunSimulation.php"
Cohesion: 0.10
Nodes (6): RunSimulation, SimulationJob, {closure#1}(), {closure#2}(), {closure#3}(), savedEmaCross()

### Community 87 - ".times"
Cohesion: 0.18
Nodes (4): {closure#3}(), {closure#42}(), Wilder, {closure#9}()

### Community 88 - "require-dev"
Cohesion: 0.22
Nodes (9): require-dev, fakerphp/faker, larastan/larastan, laravel/pail, laravel/pao, mockery/mockery, nunomaduro/collision, pestphp/pest (+1 more)

### Community 89 - "scripts-descriptions"
Cohesion: 0.25
Nodes (8): scripts-descriptions, analyse, format, mutate, test, test:network, test:testnet, tools:install

### Community 90 - ".run"
Cohesion: 0.11
Nodes (26): PerformanceMetrics, Report, btcMinutes(), {closure#1}(), {closure#2}(), {closure#3}(), referencePrice(), {closure#13}() (+18 more)

### Community 91 - ".fromStrings"
Cohesion: 0.12
Nodes (10): RegimeLabels, {closure#1}(), {closure#42}(), {closure#1}(), {closure#2}(), {closure#4}(), {closure#7}(), {closure#9}() (+2 more)

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

### Community 96 - ".sqrt"
Cohesion: 0.27
Nodes (10): Statistics, {closure#3}(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}() (+2 more)

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
Cohesion: 0.24
Nodes (9): Gates, brokenGate(), {closure#1}(), {closure#3}(), {closure#4}(), {closure#6}(), {closure#7}(), gate() (+1 more)

### Community 119 - "Carbon\CarbonImmutable"
Cohesion: 0.08
Nodes (9): DecisionRecord, {closure#1}(), QualityReport, RiskEvent, RiskEventKind, DailyLossHalt, DrawdownFlatten, DrawdownHalt (+1 more)

### Community 120 - "buy-and-hold.strategy.yaml"
Cohesion: 0.12
Nodes (16): entries, long, exits, stop_loss, fails_when, family, hypothesis, when (+8 more)

### Community 121 - "App\Market\Data\Candle"
Cohesion: 0.13
Nodes (13): {closure#1}(), {closure#2}(), DirectionalMovement, chart(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}() (+5 more)

### Community 123 - "Price"
Cohesion: 0.16
Nodes (3): ProtectionPlan, MoveStop, Price

### Community 124 - "bootstrap/app.php"
Cohesion: 0.15
Nodes (5): AuthenticateMcpToken, TradingServer, {closure#1}(), {closure#2}(), {closure#3}()

### Community 125 - "EngineMode"
Cohesion: 0.33
Nodes (5): EngineMode, Backtest, Live, Paper, Testnet

### Community 126 - ".evaluate"
Cohesion: 0.25
Nodes (8): WalkForward, WalkForwardSet, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), wfSet()

### Community 127 - "MarketType"
Cohesion: 0.05
Nodes (32): {closure#1}(), {closure#2}(), BinanceRestClient, EpochTime, ExchangeNumber, Json, RequestFailed, SyncInstruments (+24 more)

### Community 128 - "funding-carry.strategy.yaml"
Cohesion: 0.12
Nodes (15): fails_when, family, hypothesis, params, enter_when, exit_when, margin_buffer, max_leverage_perp (+7 more)

### Community 129 - "McpServerTest.php"
Cohesion: 0.21
Nodes (12): actAsToken(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}(), {closure#8}() (+4 more)

### Community 130 - ".parse"
Cohesion: 0.13
Nodes (17): {closure#1}(), AstPrinter, {closure#1}(), {closure#8}(), {closure#1}(), {closure#1}(), {closure#1}(), {closure#1}() (+9 more)

### Community 131 - "Decimal"
Cohesion: 0.08
Nodes (13): Balance, {closure#1}(), {closure#2}(), {closure#1}(), {closure#3}(), TradeStatistics, {closure#6}(), Decimal (+5 more)

### Community 132 - "App\Simulation\Backtest"
Cohesion: 0.24
Nodes (5): InMemoryDecisionLog, {closure#1}(), {closure#2}(), BacktestResult, ParticipantResult

### Community 133 - "Price"
Cohesion: 0.06
Nodes (18): Venue, ClientOrderId, OcoRequest, OrderAck, OrderRequest, OrderType, Limit, Market (+10 more)

### Community 138 - ".parse"
Cohesion: 0.26
Nodes (6): KlineMessage, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), streamLines()

### Community 139 - ".encode"
Cohesion: 0.30
Nodes (6): CanonicalJson, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}()

### Community 140 - "Percent"
Cohesion: 0.24
Nodes (3): Percent, {closure#14}(), {closure#15}()

### Community 141 - "Close"
Cohesion: 0.20
Nodes (3): Close, {closure#18}(), {closure#37}()

### Community 143 - "Group"
Cohesion: 0.22
Nodes (8): Group, Exit, Indicator, Order, Perpetual, Position, Price, Time

### Community 144 - "MinuteCandles"
Cohesion: 0.33
Nodes (4): MinuteCandles, {closure#14}(), {closure#3}(), {closure#7}()

## Knowledge Gaps
- **115 isolated node(s):** `BelowMinimum`, `Cooldown`, `DuplicateIntent`, `EntryWorking`, `GrossExposure` (+110 more)
  These have ≤1 connection - possible missing edges. (Counts symbols only; 725 node(s) total have ≤1 connection when file, concept and rationale nodes are included.)
- **25 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `Decimal` connect `Decimal` to `.of`, `App\Simulation\Metrics\Performance`, `OrderStatus`, `Price`, `Instrument`, `App\Simulation\Backtest`, `CompiledStrategy.php`, `SimulatedVenueTest.php`, `Position`, `.encode`, `Candle`, `Percent`, `IndicatorsTest.php`, `Ledger`, `Symbol`, `RejectionReason`, `.parse`, `App\Strategy\Dsl\Compiler\CompiledStrategy`, `Fill`, `EnginePerpTest.php`, `DslBaselinesTest.php`, `ValidationStatisticsTest.php`, `.of`, `Carbon\CarbonImmutable`, `Side`, `Timeframe`, `SimClock`, `InvalidArgumentException`, `BacktestTest.php`, `RiskPolicy`, `GridBacktest.php`, `OrderRole`, `InstrumentRules`, `.measure`, `RunBacktestCommandTest.php`, `Symbol`, `CandleFeedTest.php`, `OrderManager`, `.of`, `OrderUpdate`, `Money`, `.walk`, `CompiledStrategyTest.php`, `App\Support\Number\Decimal`, `.times`, `.run`, `.sqrt`, `Carbon\CarbonImmutable`, `App\Market\Data\Candle`, `FundingCarry`, `Price`, `MarketType`?**
  _High betweenness centrality (0.097) - this node is a cross-community bridge._
- **What connects `BelowMinimum`, `Cooldown`, `DuplicateIntent` to the rest of the system?**
  _115 weakly-connected nodes found - possible missing edges._
- **Should `EngineTest.php` be split into smaller, more focused modules?**
  _Cohesion score 0.11320754716981132 - nodes in this community are weakly interconnected._
- **Why does `Symbol` connect `Symbol` to `App\Simulation\Metrics\Performance`, `Instrument`, `OrderStatus`, `Price`, `Instrument`, `Decimal`, `App\Simulation\Backtest`, `SimulatedVenueTest.php`, `App\Support\Symbol`, `SimulatedPerpVenueTest.php`, `.parse`, `Close`, `ExchangeVerificationTest.php`, `AppServiceProvider.php`, `.encode`, `Ledger`, `Symbol`, `Fill`, `EnginePerpTest.php`, `Carbon\CarbonImmutable`, `Side`, `SimClock`, `InvalidArgumentException`, `BacktestTest.php`, `OrderRole`, `BinanceRestClientTest.php`, `InstrumentRules`, `RunBacktestCommandTest.php`, `CandleFeedTest.php`, `OrderManager`, `CandleRepositoryTest.php`, `OrderUpdate`, `Money`, `CompiledStrategyTest.php`, `.run`, `Carbon\CarbonImmutable`, `FundingCarry`, `Price`, `MarketType`?**
  _High betweenness centrality (0.028) - this node is a cross-community bridge._
- **Should `App\Simulation\Metrics\Performance` be split into smaller, more focused modules?**
  _Cohesion score 0.0960591133004926 - nodes in this community are weakly interconnected._
- **Why does `Candle` connect `Candle` to `Decimal`, `OrderStatus`, `Price`, `Instrument`, `.parse`, `CompiledStrategy.php`, `SimulatedVenueTest.php`, `.parse`, `Close`, `IndicatorsTest.php`, `ExchangeVerificationTest.php`, `EnginePerpTest.php`, `Timeframe`, `InvalidArgumentException`, `BacktestTest.php`, `OrderRole`, `BinanceRestClientTest.php`, `InstrumentRules`, `Symbol`, `CandleFeedTest.php`, `CandleRepositoryTest.php`, `.walk`, `CompiledStrategyTest.php`, `MonteCarlo`, `.times`, `.run`, `.fromStrings`, `Carbon\CarbonImmutable`, `App\Market\Data\Candle`, `Price`, `MarketType`?**
  _High betweenness centrality (0.025) - this node is a cross-community bridge._
- **Should `Instrument` be split into smaller, more focused modules?**
  _Cohesion score 0.09219858156028368 - nodes in this community are weakly interconnected._