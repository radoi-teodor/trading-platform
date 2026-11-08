# Graph Report - trading-platform  (2026-11-08)

## Corpus Check
- 429 files · ~163,006 words
- Verdict: corpus is large enough that graph structure adds value.
- Unclassified: 92 file(s) not represented in the graph (top: .zip 34, .CHECKSUM 34, (none) 18)

## Summary
- 3387 nodes · 12294 edges · 138 communities (109 shown, 29 thin omitted)
- Extraction: 89% EXTRACTED · 11% INFERRED · 0% AMBIGUOUS · INFERRED: 1315 edges (avg confidence: 0.85)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `911c7835`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- .of
- EngineTest.php
- App\Support\Number\Decimal
- Instrument
- .zero
- .parse
- DatabaseCandleFeed
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
- Money
- App\Strategy\Dsl\Position
- App\Market\Stream\PhrityTransport
- Side
- Illuminate\Database\Schema\Blueprint
- generate.py
- ForwardingVenue
- Symbol
- ParamKind
- RejectionReason
- .isPositive
- .fromStrings
- .plus
- EnginePerpTest.php
- App\Market\Data\InstrumentRules
- ValidationStatisticsTest.php
- .of
- Carbon\CarbonImmutable
- DocumentChecker
- Timeframe
- SimClock
- .isGreaterThan
- BacktestTest.php
- DonchianBreakout
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
- OrderManagerTest.php
- .measure
- ChartSeries
- RunBacktestCommandTest.php
- Price
- BinanceArchive
- CandleFeedTest.php
- OrderManager
- ArchiveImportTest.php
- IndicatorReferenceTest.php
- SimClock
- App\Strategy\Dsl\Document\DocumentChecker
- App\Strategy\Contracts\Strategy
- SimulatedFuturesBook.php
- .count
- .reconcile
- scripts
- .parse
- App\Simulation\Backtest
- .walk
- composer.json
- jobs
- postgres
- .times
- Window
- CompiledStrategyTest.php
- App\Support\Number\Decimal
- pint/composer.json
- .count
- RunSimulation.php
- CandleAggregator
- require-dev
- scripts-descriptions
- .run
- .evaluate
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
- Stringable
- App\Risk\RiskState
- PreparesBacktests.php
- CompiledStrategyTest.php
- Symbol
- Group
- bootstrap/app.php
- EngineMode
- QualityReport
- MarketType
- .__construct
- McpServerTest.php
- .parse
- Decimal
- FundingCarry
- OrderRequest

## God Nodes (most connected - your core abstractions)
1. `Decimal` - 294 edges
2. `Side` - 182 edges
3. `Symbol` - 169 edges
4. `Candle` - 103 edges
5. `Timeframe` - 97 edges
6. `Price` - 81 edges
7. `MarketType` - 66 edges
8. `Position` - 61 edges
9. `Ledger` - 56 edges
10. `SimulatedVenue` - 56 edges

## Surprising Connections (you probably didn't know these)
- `checkDocument()` --calls--> `PolicyLimits`  [INFERRED]
  tests/Unit/Strategy/Dsl/DocumentCheckerTest.php → app/Strategy/Dsl/Document/PolicyLimits.php
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

## Communities (138 total, 29 thin omitted)

### Community 0 - ".of"
Cohesion: 0.17
Nodes (47): {closure#2}(), RiskState, account(), accountPolicy(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}() (+39 more)

### Community 1 - "EngineTest.php"
Cohesion: 0.08
Nodes (39): Balance, ScriptedStrategy, balances(), {closure#1}(), {closure#10}(), {closure#13}(), {closure#14}(), {closure#16}() (+31 more)

### Community 2 - "App\Support\Number\Decimal"
Cohesion: 0.07
Nodes (23): {closure#15}(), {closure#10}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}() (+15 more)

### Community 3 - "Instrument"
Cohesion: 0.07
Nodes (15): Backfill, {closure#3}(), BackfillStep, BinanceEndpoints, CandleRepository, CandleSource, Archive, Rest (+7 more)

### Community 4 - ".zero"
Cohesion: 0.10
Nodes (16): Liquidity, Maker, Taker, OrderAck, OrderStatus, Cancelled, Expired, Filled (+8 more)

### Community 5 - ".parse"
Cohesion: 0.12
Nodes (33): carryDecisions(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#15}(), {closure#16}() (+25 more)

### Community 6 - "DatabaseCandleFeed"
Cohesion: 0.19
Nodes (7): {closure#2}(), {closure#4}(), DatabaseCandleFeed, {closure#1}(), Rows, {closure#10}(), {closure#11}()

### Community 7 - "Position"
Cohesion: 0.08
Nodes (17): Argument, BoolLiteral, Call, DurationLiteral, Identifier, Lookback, Node, NumberLiteral (+9 more)

### Community 8 - "CompiledStrategy.php"
Cohesion: 0.13
Nodes (8): {closure#1}(), {closure#2}(), {closure#3}(), {closure#33}(), Compiler, Program, {closure#2}(), {closure#8}()

### Community 9 - "SimulatedVenueTest.php"
Cohesion: 0.20
Nodes (42): Side, Buy, Sell, btc(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}() (+34 more)

### Community 10 - "App\Support\Symbol"
Cohesion: 0.08
Nodes (12): {closure#4}(), {closure#6}(), {closure#7}(), {closure#1}(), LimitedStrategy, EntryOrder, OpenLong, OpenShort (+4 more)

### Community 11 - "SimulatedPerpVenueTest.php"
Cohesion: 0.22
Nodes (24): {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#15}(), {closure#18}(), {closure#19}() (+16 more)

### Community 12 - "Candle"
Cohesion: 0.14
Nodes (31): {closure#1}(), {closure#2}(), {closure#1}(), {closure#3}(), Candle, {closure#1}(), {closure#2}(), candleWith() (+23 more)

### Community 13 - "ExchangeVerificationTest.php"
Cohesion: 0.10
Nodes (29): {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}() (+21 more)

### Community 14 - "IndicatorsTest.php"
Cohesion: 0.12
Nodes (13): Ema, Sma, {closure#1}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#16}(), {closure#2}() (+5 more)

### Community 15 - "AppServiceProvider.php"
Cohesion: 0.16
Nodes (5): SystemSleeper, AppServiceProvider, {closure#2}(), {closure#4}(), {closure#6}()

### Community 16 - "Node"
Cohesion: 0.09
Nodes (29): Binary, Lexer, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}() (+21 more)

### Community 17 - "Money"
Cohesion: 0.13
Nodes (16): AssetMismatch, Money, {closure#2}(), {closure#1}(), usdt(), {closure#7}(), {closure#1}(), {closure#11}() (+8 more)

### Community 18 - "App\Strategy\Dsl\Position"
Cohesion: 0.16
Nodes (10): DslError, YamlList, YamlMap, YamlReader, YamlScalar, YamlValue, {closure#1}(), {closure#2}() (+2 more)

### Community 19 - "App\Market\Stream\PhrityTransport"
Cohesion: 0.07
Nodes (11): ArchiveMissing, ChecksumMismatch, BinanceRequestFailed, LocationRestricted, RateLimited, VerificationSource, DailyArchive, Rest (+3 more)

### Community 20 - "Side"
Cohesion: 0.08
Nodes (58): ExitReason, Flatten, Liquidation, Signal, Stop, Target, Marks, Ledger (+50 more)

### Community 21 - "Illuminate\Database\Schema\Blueprint"
Cohesion: 0.07
Nodes (20): {closure#1}(), {closure#2}(), {closure#3}(), {closure#1}(), {closure#2}(), {closure#1}(), {closure#2}(), {closure#3}() (+12 more)

### Community 22 - "generate.py"
Cohesion: 0.09
Nodes (28): atr(), bars(), bb(), change(), dmi(), donchian(), ema(), highest() (+20 more)

### Community 24 - "Symbol"
Cohesion: 0.08
Nodes (13): BuyAndHold, MovingAverageTrend, BuildableStrategy, Strategy, StrategyContext, StrategyIdentity, Subscription, EnterOnceStrategy (+5 more)

### Community 25 - "ParamKind"
Cohesion: 0.14
Nodes (12): {closure#2}(), {closure#1}(), {closure#2}(), ParamKind, Anchor, Count, Duration, Number (+4 more)

### Community 26 - "RejectionReason"
Cohesion: 0.08
Nodes (27): AccountView, RejectionReason, BelowMinimum, Cooldown, DuplicateIntent, EntryWorking, GrossExposure, Halted (+19 more)

### Community 27 - ".isPositive"
Cohesion: 0.20
Nodes (5): Normal, DecimalMath, {closure#11}(), {closure#12}(), {closure#13}()

### Community 28 - ".fromStrings"
Cohesion: 0.25
Nodes (5): {closure#1}(), {closure#1}(), {closure#42}(), {closure#12}(), bar()

### Community 29 - ".plus"
Cohesion: 0.13
Nodes (7): Fill, PerpPosition, Position, RoundTrip, {closure#1}(), {closure#2}(), {closure#4}()

### Community 30 - "EnginePerpTest.php"
Cohesion: 0.28
Nodes (14): {closure#10}(), {closure#11}(), {closure#12}(), {closure#14}(), {closure#5}(), {closure#6}(), {closure#7}(), {closure#8}() (+6 more)

### Community 32 - "ValidationStatisticsTest.php"
Cohesion: 0.15
Nodes (17): Cscv, closeTo(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#13}(), {closure#14}(), {closure#15}() (+9 more)

### Community 33 - ".of"
Cohesion: 0.07
Nodes (51): {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#1}(), {closure#2}(), {closure#3}(), documentedLimits() (+43 more)

### Community 34 - "Carbon\CarbonImmutable"
Cohesion: 0.13
Nodes (7): DecisionLog, EngineRecord, IntentRecord, Engine, {closure#1}(), {closure#3}(), {closure#2}()

### Community 35 - "DocumentChecker"
Cohesion: 0.21
Nodes (3): CompileStrategy, DocumentChecker, PolicyLimits

### Community 36 - "Timeframe"
Cohesion: 0.12
Nodes (14): {closure#4}(), Timeframe, FifteenMinutes, FiveMinutes, FourHours, OneDay, OneHour, OneMinute (+6 more)

### Community 37 - "SimClock"
Cohesion: 0.11
Nodes (18): {closure#11}(), {closure#13}(), {closure#14}(), {closure#15}(), {closure#16}(), {closure#4}(), {closure#5}(), {closure#6}() (+10 more)

### Community 39 - "BacktestTest.php"
Cohesion: 0.17
Nodes (18): FeeSchedule, CostModel, CandleFeed, FundingRate, Backtest, Participant, RunDefinition, {closure#5}() (+10 more)

### Community 40 - "DonchianBreakout"
Cohesion: 0.27
Nodes (4): DonchianBreakout, Donchian, {closure#10}(), {closure#15}()

### Community 41 - "ema-cross-scalp-long.strategy.yaml"
Cohesion: 0.06
Nodes (33): entries, long, exits, stop_loss, take_profit, time_stop, when, fails_when (+25 more)

### Community 42 - "GridBacktest.php"
Cohesion: 0.06
Nodes (18): {closure#1}(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#14}(), {closure#17}(), {closure#18}(), {closure#19}() (+10 more)

### Community 43 - "autoload-dev"
Cohesion: 0.67
Nodes (3): autoload-dev, psr-4, Tests\\

### Community 44 - "OrderRole"
Cohesion: 0.15
Nodes (15): OrderRole, Entry, Exit, Flatten, Stop, Target, TrackedOrder, {closure#1}() (+7 more)

### Community 45 - "User"
Cohesion: 0.10
Nodes (3): User, UserFactory, {closure#1}()

### Community 46 - "Illuminate\Database\Eloquent\Model"
Cohesion: 0.11
Nodes (8): {closure#1}(), McpCall, McpToken, {closure#1}(), InstrumentFilter, BacktestRun, StrategyTrial, StrategyStage

### Community 47 - "package.json"
Cohesion: 0.13
Nodes (20): devDependencies, concurrently@^10.0.3, laravel-vite-plugin@^3.1, tailwindcss@^4.0.0, @tailwindcss/vite@^4.0.0, vite@^8.0.0, optionalDependencies, @laravel/multiplex@^0.4.1 (+12 more)

### Community 48 - "DocumentCheckerTest.php"
Cohesion: 0.11
Nodes (16): {closure#1}(), {closure#1}(), {closure#1}(), SaveResult, StrategyVersion, {closure#1}(), checkDocument(), {closure#1}() (+8 more)

### Community 49 - "BinanceRestClientTest.php"
Cohesion: 0.13
Nodes (19): Sleeper, binanceClient(), {closure#1}(), {closure#11}(), {closure#13}(), {closure#3}(), {closure#4}(), {closure#5}() (+11 more)

### Community 50 - "ema-cross-scalp-short.strategy.yaml"
Cohesion: 0.06
Nodes (33): entries, short, exits, stop_loss, take_profit, time_stop, when, fails_when (+25 more)

### Community 51 - ".handle"
Cohesion: 0.29
Nodes (5): CanonicalJson, {closure#1}(), {closure#3}(), {closure#4}(), {closure#5}()

### Community 52 - "TokensTest.php"
Cohesion: 0.31
Nodes (7): {closure#3}(), {closure#4}(), {closure#5}(), contrastRatio(), contrastRules(), relativeLuminance(), themeTokens()

### Community 53 - "Laravel\Mcp\Response"
Cohesion: 0.07
Nodes (15): CompileStrategy, DslReference, GetFunding, GetJob, GetStrategy, ListInstruments, ListStrategies, ListTrials (+7 more)

### Community 54 - "postgres"
Cohesion: 0.10
Nodes (19): POSTGRES_DB, POSTGRES_PASSWORD, POSTGRES_USER, interval, retries, test, timeout, environment (+11 more)

### Community 55 - "InstrumentRules"
Cohesion: 0.29
Nodes (10): InstrumentRules, RulesHistory, {closure#2}(), {closure#4}(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}() (+2 more)

### Community 56 - "App\Support\Clock\Clock"
Cohesion: 0.07
Nodes (16): AuthenticateMcpToken, InstrumentDirectory, StoredInstruments, {closure#1}(), {closure#2}(), {closure#1}(), ExportStrategies, ImportStrategies (+8 more)

### Community 57 - "OrderManagerTest.php"
Cohesion: 0.30
Nodes (14): btcUsdt(), {closure#10}(), {closure#15}(), {closure#16}(), {closure#2}(), {closure#4}(), {closure#5}(), {closure#6}() (+6 more)

### Community 58 - ".measure"
Cohesion: 0.16
Nodes (9): EquityTracker, Performance, {closure#1}(), {closure#2}(), {closure#3}(), {closure#5}(), {closure#6}(), {closure#8}() (+1 more)

### Community 59 - "ChartSeries"
Cohesion: 0.11
Nodes (5): ChartTool, GetCandles, GetIndicators, Controller, ChartSeries

### Community 60 - "RunBacktestCommandTest.php"
Cohesion: 0.18
Nodes (21): {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#15}(), {closure#16}() (+13 more)

### Community 62 - "BinanceArchive"
Cohesion: 0.08
Nodes (6): ArchivePeriod, BinanceArchive, {closure#1}(), Instrument, ExchangeVerification, VerificationReport

### Community 63 - "CandleFeedTest.php"
Cohesion: 0.23
Nodes (15): MinuteCandles, {closure#1}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#2}(), {closure#3}(), {closure#4}() (+7 more)

### Community 65 - "ArchiveImportTest.php"
Cohesion: 0.29
Nodes (10): archiveBytes(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}() (+2 more)

### Community 66 - "IndicatorReferenceTest.php"
Cohesion: 0.06
Nodes (26): {closure#34}(), Atr, Change, Highest, Hma, Lowest, Macd, Natr (+18 more)

### Community 67 - "SimClock"
Cohesion: 0.08
Nodes (28): SimClock, WallClock, candleStore(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}() (+20 more)

### Community 68 - "App\Strategy\Dsl\Document\DocumentChecker"
Cohesion: 0.06
Nodes (35): {closure#3}(), {closure#4}(), {closure#5}(), {closure#8}(), {closure#9}(), ParameterSpec, DslWriter, FunctionSpec (+27 more)

### Community 69 - "App\Strategy\Contracts\Strategy"
Cohesion: 0.16
Nodes (3): {closure#1}(), CompiledStrategy, RenamedStrategy

### Community 70 - "SimulatedFuturesBook.php"
Cohesion: 0.10
Nodes (13): Income, IncomeKind, Funding, Liquidation, OrderUpdate, {closure#2}(), {closure#3}(), UpdateStream (+5 more)

### Community 71 - ".count"
Cohesion: 0.09
Nodes (11): CreateMcpToken, RevokeMcpToken, BackfillReport, BackfillMarketData, CheckMarketData, SelectsInstruments, {closure#1}(), StreamCandles (+3 more)

### Community 73 - "scripts"
Cohesion: 0.15
Nodes (13): scripts, analyse, dev, format, mutate, post-autoload-dump, post-update-cmd, pre-package-uninstall (+5 more)

### Community 74 - ".parse"
Cohesion: 0.33
Nodes (6): KlineMessage, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), streamLines()

### Community 75 - "App\Simulation\Backtest"
Cohesion: 0.20
Nodes (6): InMemoryDecisionLog, {closure#1}(), {closure#2}(), BacktestResult, ParticipantResult, ReportBuilder

### Community 76 - ".walk"
Cohesion: 0.32
Nodes (12): aggregate(), {closure#1}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#3}(), {closure#4}(), {closure#5}() (+4 more)

### Community 77 - "composer.json"
Cohesion: 0.17
Nodes (11): description, extra, laravel, keywords, dont-discover, license, minimum-stability, name (+3 more)

### Community 78 - "jobs"
Cohesion: 0.17
Nodes (11): concurrency, cancel-in-progress, group, jobs, name, on, pull_request, push (+3 more)

### Community 79 - "postgres"
Cohesion: 0.17
Nodes (12): POSTGRES_DB, POSTGRES_PASSWORD, POSTGRES_USER, env, image, options, ports, image (+4 more)

### Community 80 - ".times"
Cohesion: 0.19
Nodes (3): Percent, Perpetual, {closure#5}()

### Community 81 - "Window"
Cohesion: 0.10
Nodes (5): DonchianChannel, StandardDeviation, Stochastic, Vwma, Window

### Community 82 - "CompiledStrategyTest.php"
Cohesion: 0.19
Nodes (13): PositionView, KnownInstruments, aggregatedBars(), {closure#11}(), {closure#13}(), {closure#16}(), {closure#17}(), {closure#5}() (+5 more)

### Community 83 - "App\Support\Number\Decimal"
Cohesion: 0.08
Nodes (37): {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#15}(), {closure#16}(), {closure#17}() (+29 more)

### Community 84 - "pint/composer.json"
Cohesion: 0.20
Nodes (9): config, sort-packages, description, license, name, require, laravel/pint, php (+1 more)

### Community 85 - ".count"
Cohesion: 0.30
Nodes (9): Statistics, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}() (+1 more)

### Community 86 - "RunSimulation.php"
Cohesion: 0.09
Nodes (8): {closure#3}(), RunSimulation, SimulationJob, SimulationQueue, {closure#1}(), {closure#2}(), {closure#3}(), savedEmaCross()

### Community 87 - "CandleAggregator"
Cohesion: 0.30
Nodes (6): CandleAggregator, CandleSequenceBroken, {closure#3}(), {closure#9}(), {closure#10}(), {closure#2}()

### Community 88 - "require-dev"
Cohesion: 0.22
Nodes (9): require-dev, fakerphp/faker, larastan/larastan, laravel/pail, laravel/pao, mockery/mockery, nunomaduro/collision, pestphp/pest (+1 more)

### Community 89 - "scripts-descriptions"
Cohesion: 0.25
Nodes (8): scripts-descriptions, analyse, format, mutate, test, test:network, test:testnet, tools:install

### Community 90 - ".run"
Cohesion: 0.08
Nodes (29): RiskProfile, Account, Benchmark, RiskPolicy, RunBacktest, Report, btcMinutes(), {closure#1}() (+21 more)

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

### Community 96 - "App\Simulation\Validation\WalkForward"
Cohesion: 0.18
Nodes (8): {closure#1}(), {closure#3}(), {closure#5}(), {closure#6}(), {closure#7}(), {closure#8}(), {closure#9}(), WalkForwardWindow

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

### Community 119 - "App\Risk\RiskState"
Cohesion: 0.24
Nodes (6): RiskEvent, RiskEventKind, DailyLossHalt, DrawdownFlatten, DrawdownHalt, WeeklyLossHalt

### Community 120 - "PreparesBacktests.php"
Cohesion: 0.23
Nodes (3): GridBacktest, DeflatedSharpe, {closure#12}()

### Community 121 - "CompiledStrategyTest.php"
Cohesion: 0.08
Nodes (17): {closure#36}(), BarIndicator, DirectionalMovement, Keltner, Vwap, chart(), {closure#1}(), {closure#2}() (+9 more)

### Community 122 - "Symbol"
Cohesion: 0.07
Nodes (11): DecisionRecord, {closure#1}(), {closure#1}(), Parameters, Close, Intent, MoveStop, OpenCarry (+3 more)

### Community 123 - "Group"
Cohesion: 0.22
Nodes (8): Group, Exit, Indicator, Order, Perpetual, Position, Price, Time

### Community 124 - "bootstrap/app.php"
Cohesion: 0.33
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

### Community 125 - "EngineMode"
Cohesion: 0.40
Nodes (5): EngineMode, Backtest, Live, Paper, Testnet

### Community 127 - "MarketType"
Cohesion: 0.05
Nodes (31): {closure#2}(), BinanceRestClient, EpochTime, ExchangeNumber, Json, RequestFailed, SyncInstruments, InstrumentDefinition (+23 more)

### Community 129 - "McpServerTest.php"
Cohesion: 0.21
Nodes (12): actAsToken(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}(), {closure#8}() (+4 more)

### Community 130 - ".parse"
Cohesion: 0.17
Nodes (10): AstPrinter, {closure#6}(), {closure#7}(), EntrySpec, Expression, {closure#1}(), {closure#2}(), {closure#3}() (+2 more)

### Community 131 - "Decimal"
Cohesion: 0.08
Nodes (14): VenuePosition, {closure#1}(), SimulatedPosition, PerformanceMetrics, {closure#1}(), {closure#2}(), {closure#3}(), TradeStatistics (+6 more)

### Community 133 - "OrderRequest"
Cohesion: 0.14
Nodes (9): ClientOrderId, OcoRequest, OrderRequest, OrderType, Limit, Market, StopMarket, TakeProfitMarket (+1 more)

## Knowledge Gaps
- **115 isolated node(s):** `Price`, `Indicator`, `Perpetual`, `Position`, `Time` (+110 more)
  These have ≤1 connection - possible missing edges. (Counts symbols only; 592 node(s) total have ≤1 connection when file, concept and rationale nodes are included.)
- **29 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `Decimal` connect `Decimal` to `.__construct`, `EngineTest.php`, `App\Support\Number\Decimal`, `Instrument`, `.zero`, `OrderRequest`, `.of`, `FundingCarry`, `Position`, `SimulatedVenueTest.php`, `App\Support\Symbol`, `.parse`, `Candle`, `IndicatorsTest.php`, `Money`, `Side`, `ForwardingVenue`, `Symbol`, `RejectionReason`, `.isPositive`, `.plus`, `EnginePerpTest.php`, `App\Market\Data\InstrumentRules`, `.of`, `Carbon\CarbonImmutable`, `.isGreaterThan`, `BacktestTest.php`, `DonchianBreakout`, `GridBacktest.php`, `OrderRole`, `.handle`, `InstrumentRules`, `App\Support\Clock\Clock`, `OrderManagerTest.php`, `.measure`, `Price`, `CandleFeedTest.php`, `SimClock`, `App\Strategy\Contracts\Strategy`, `SimulatedFuturesBook.php`, `.count`, `.reconcile`, `App\Simulation\Backtest`, `.walk`, `.times`, `CompiledStrategyTest.php`, `.count`, `CandleAggregator`, `.run`, `Stringable`, `App\Risk\RiskState`, `CompiledStrategyTest.php`, `Symbol`, `MarketType`?**
  _High betweenness centrality (0.114) - this node is a cross-community bridge._
- **What connects `Price`, `Indicator`, `Perpetual` to the rest of the system?**
  _115 weakly-connected nodes found - possible missing edges._
- **Should `EngineTest.php` be split into smaller, more focused modules?**
  _Cohesion score 0.07885763000852515 - nodes in this community are weakly interconnected._
- **Why does `Symbol` connect `Symbol` to `EngineTest.php`, `App\Support\Number\Decimal`, `Decimal`, `.zero`, `OrderRequest`, `Instrument`, `FundingCarry`, `.parse`, `SimulatedVenueTest.php`, `App\Support\Symbol`, `SimulatedPerpVenueTest.php`, `ExchangeVerificationTest.php`, `Side`, `ForwardingVenue`, `Symbol`, `.plus`, `EnginePerpTest.php`, `Carbon\CarbonImmutable`, `SimClock`, `BacktestTest.php`, `DonchianBreakout`, `GridBacktest.php`, `OrderRole`, `BinanceRestClientTest.php`, `.handle`, `InstrumentRules`, `OrderManagerTest.php`, `.measure`, `ChartSeries`, `Price`, `BinanceArchive`, `CandleFeedTest.php`, `SimClock`, `App\Strategy\Contracts\Strategy`, `SimulatedFuturesBook.php`, `.count`, `.reconcile`, `App\Simulation\Backtest`, `.run`, `Stringable`, `MarketType`?**
  _High betweenness centrality (0.048) - this node is a cross-community bridge._
- **Should `App\Support\Number\Decimal` be split into smaller, more focused modules?**
  _Cohesion score 0.06821480406386067 - nodes in this community are weakly interconnected._
- **Why does `Candle` connect `Candle` to `.__construct`, `Instrument`, `.zero`, `Decimal`, `DatabaseCandleFeed`, `.parse`, `SimulatedVenueTest.php`, `ExchangeVerificationTest.php`, `IndicatorsTest.php`, `Side`, `Symbol`, `.fromStrings`, `EnginePerpTest.php`, `Carbon\CarbonImmutable`, `Timeframe`, `BacktestTest.php`, `DonchianBreakout`, `BinanceRestClientTest.php`, `OrderManagerTest.php`, `Price`, `BinanceArchive`, `CandleFeedTest.php`, `SimClock`, `App\Strategy\Contracts\Strategy`, `.parse`, `.walk`, `CandleAggregator`, `.run`, `CompiledStrategyTest.php`, `Symbol`, `MarketType`?**
  _High betweenness centrality (0.030) - this node is a cross-community bridge._
- **Should `Instrument` be split into smaller, more focused modules?**
  _Cohesion score 0.07139079851930195 - nodes in this community are weakly interconnected._