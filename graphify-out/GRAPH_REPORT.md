# Graph Report - trading-platform  (2026-11-09)

## Corpus Check
- 482 files · ~202,781 words
- Verdict: corpus is large enough that graph structure adds value.
- Unclassified: 92 file(s) not represented in the graph (top: .zip 34, .CHECKSUM 34, (none) 18)

## Summary
- 3930 nodes · 13784 edges · 150 communities (121 shown, 29 thin omitted)
- Extraction: 89% EXTRACTED · 11% INFERRED · 0% AMBIGUOUS · INFERRED: 1455 edges (avg confidence: 0.85)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `56419fdb`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- .of
- EngineTest.php
- App\Support\Clock\Clock
- RuntimeException
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
- IndicatorsTest.php
- App\Simulation\SimulationQueue
- Node
- .account
- App\Strategy\Dsl\Position
- MarketType
- LedgerTest.php
- Illuminate\Database\Schema\Blueprint
- generate.py
- ReadBack
- Symbol
- ParamKind
- RejectionReason
- App\Strategy\Dsl\Compiler\CompiledStrategy
- .measure
- Fill
- EnginePerpTest.php
- DslBaselinesTest.php
- .count
- .of
- Carbon\CarbonImmutable
- ExitReason
- PaperEngineTest.php
- SimClock
- App\Support\Symbol
- BacktestTest.php
- OrderManagerTest.php
- ema-cross-scalp-long.strategy.yaml
- StrategyGates.php
- autoload-dev
- OrderRole
- User
- Illuminate\Database\Eloquent\Model
- package.json
- FundingCarry
- BinanceRestClientTest.php
- ema-cross-scalp-short.strategy.yaml
- App\Support\Number\Price
- TokensTest.php
- Laravel\Mcp\Response
- postgres
- InstrumentRules
- PreparesBacktests
- donchian-breakout-short.strategy.yaml
- Window
- AccountRuntime
- RunBacktestCommandTest.php
- Ledger
- BinanceArchive
- CandleFeedTest.php
- App\Support\Symbol
- donchian-breakout.strategy.yaml
- .benchmark
- CandleRepositoryTest.php
- App\Strategy\Dsl\Document\DocumentChecker
- .of
- App\Agents\Mcp\TradingServer
- Universe
- SimulationJobsTest.php
- scripts
- ma-trend-short.strategy.yaml
- .parse
- Timeframe
- composer.json
- jobs
- postgres
- ma-trend.strategy.yaml
- IndicatorReferenceTest.php
- CompiledStrategyTest.php
- App\Support\Number\Decimal
- pint/composer.json
- MonteCarlo
- RunSimulation.php
- App\Support\Number\Decimal
- require-dev
- scripts-descriptions
- RiskState
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
- OrderUpdate
- MarketBreakers
- buy-and-hold.strategy.yaml
- App\Market\Data\Candle
- .times
- .build
- Illuminate\Database\ConnectionInterface
- App\Simulation\Validation\WalkForward
- .reconcile
- .run
- funding-carry.strategy.yaml
- McpServerTest.php
- PerpetualBaselinesTest.php
- Decimal
- App\Execution\Engine
- ClientOrderId
- App\Market\Archive\BinanceArchive
- .encode
- RiskEventKind
- RiskPolicy
- StrategyVersion
- VenuePosition
- Expression
- Tests\Support\GoldenData
- MinuteCandles
- QualityReport
- {closure#12}

## God Nodes (most connected - your core abstractions)
1. `Decimal` - 272 edges
2. `Side` - 183 edges
3. `Symbol` - 160 edges
4. `Timeframe` - 98 edges
5. `Candle` - 94 edges
6. `Price` - 77 edges
7. `MarketType` - 66 edges
8. `Position` - 61 edges
9. `SimulatedVenue` - 60 edges
10. `Ledger` - 56 edges

## Surprising Connections (you probably didn't know these)
- `paperAccount()` --calls--> `SimClock`  [INFERRED]
  tests/Feature/Execution/PaperEngineTest.php → app/Support/Clock/SimClock.php
- `{closure#1}()` --calls--> `EngineRecord`  [INFERRED]
  tests/Feature/Notifications/TelegramTest.php → app/Execution/Data/EngineRecord.php
- `{closure#4}()` --references--> `Candle`  [EXTRACTED]
  tests/Feature/Market/BinanceRestClientTest.php → app/Market/Data/Candle.php
- `{closure#7}()` --references--> `Candle`  [EXTRACTED]
  tests/Feature/Market/CandleRepositoryTest.php → app/Market/Data/Candle.php
- `{closure#12}()` --references--> `Candle`  [EXTRACTED]
  tests/Unit/Market/CandleAggregatorTest.php → app/Market/Data/Candle.php

## Import Cycles
- None detected.

## Communities (150 total, 29 thin omitted)

### Community 0 - ".of"
Cohesion: 0.27
Nodes (35): account(), accountPolicy(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#2}(), {closure#21}() (+27 more)

### Community 1 - "EngineTest.php"
Cohesion: 0.09
Nodes (42): CommandKind, Flatten, Halt, Pause, Resume, ResumeStrategy, EngineCommand, ScriptedStrategy (+34 more)

### Community 2 - "App\Support\Clock\Clock"
Cohesion: 0.08
Nodes (14): CreateMcpToken, RevokeMcpToken, AuthenticateMcpToken, McpToken, CreateAccount, FlattenEngine, HaltEngine, IssuesCommands (+6 more)

### Community 3 - "RuntimeException"
Cohesion: 0.07
Nodes (8): ArchiveMissing, ChecksumMismatch, VerificationSource, DailyArchive, Rest, PhrityTransport, StreamFailed, FakeTransport

### Community 4 - "OrderStatus"
Cohesion: 0.06
Nodes (23): Liquidity, Maker, Taker, OrderStatus, Cancelled, Expired, Filled, New (+15 more)

### Community 5 - ".parse"
Cohesion: 0.15
Nodes (25): {closure#11}(), {closure#14}(), carryDecisions(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#19}() (+17 more)

### Community 6 - "Instrument"
Cohesion: 0.08
Nodes (13): {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#1}(), {closure#1}(), Instrument, Rows (+5 more)

### Community 7 - "Position"
Cohesion: 0.08
Nodes (16): Argument, BoolLiteral, Call, DurationLiteral, Identifier, Lookback, Node, NumberLiteral (+8 more)

### Community 8 - "CompiledStrategy.php"
Cohesion: 0.10
Nodes (8): {closure#1}(), {closure#2}(), CompiledStrategy, {closure#33}(), Compiler, Program, {closure#2}(), {closure#8}()

### Community 9 - "SimulatedVenueTest.php"
Cohesion: 0.16
Nodes (48): Side, Buy, Sell, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), btc() (+40 more)

### Community 10 - "Clock"
Cohesion: 0.07
Nodes (14): BinanceEndpoints, Sleeper, SystemSleeper, CandleRepository, CandleSource, Archive, Rest, Stream (+6 more)

### Community 11 - "SimulatedPerpVenueTest.php"
Cohesion: 0.24
Nodes (24): {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#15}(), {closure#18}(), {closure#19}() (+16 more)

### Community 12 - "Candle"
Cohesion: 0.13
Nodes (28): Candle, InvalidCandle, {closure#1}(), {closure#2}(), candleWith(), {closure#1}(), {closure#10}(), {closure#11}() (+20 more)

### Community 13 - "ExchangeVerificationTest.php"
Cohesion: 0.11
Nodes (28): {closure#1}(), {closure#12}(), {closure#13}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}() (+20 more)

### Community 14 - "IndicatorsTest.php"
Cohesion: 0.08
Nodes (23): Atr, Donchian, Ema, Period, Sma, {closure#5}(), bar(), {closure#1}() (+15 more)

### Community 15 - "App\Simulation\SimulationQueue"
Cohesion: 0.09
Nodes (5): RunBacktest, RunGrid, SimulationTool, Controller, {closure#3}()

### Community 16 - "Node"
Cohesion: 0.11
Nodes (29): Binary, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}() (+21 more)

### Community 17 - ".account"
Cohesion: 0.08
Nodes (20): InstrumentDirectory, StoredInstruments, {closure#1}(), CompileStrategy, PolicyLimits, StrategyRepository, {closure#1}(), {closure#2}() (+12 more)

### Community 18 - "App\Strategy\Dsl\Position"
Cohesion: 0.11
Nodes (12): {closure#5}(), DocumentChecker, DslError, YamlList, YamlMap, YamlReader, YamlScalar, YamlValue (+4 more)

### Community 19 - "MarketType"
Cohesion: 0.07
Nodes (23): BinanceRestClient, {closure#1}(), Json, RequestFailed, SyncInstruments, InstrumentDefinition, InstrumentStatus, Delisted (+15 more)

### Community 20 - "LedgerTest.php"
Cohesion: 0.16
Nodes (32): {closure#2}(), balanceOf(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#15}() (+24 more)

### Community 21 - "Illuminate\Database\Schema\Blueprint"
Cohesion: 0.06
Nodes (27): {closure#1}(), {closure#2}(), {closure#3}(), {closure#1}(), {closure#2}(), {closure#1}(), {closure#2}(), {closure#3}() (+19 more)

### Community 22 - "generate.py"
Cohesion: 0.08
Nodes (30): atr(), bars(), bb(), change(), dmi(), donchian(), ema(), highest() (+22 more)

### Community 24 - "Symbol"
Cohesion: 0.05
Nodes (19): {closure#1}(), BuyAndHold, DonchianBreakout, MovingAverageTrend, BuildableStrategy, Strategy, Close, Intent (+11 more)

### Community 25 - "ParamKind"
Cohesion: 0.12
Nodes (12): {closure#2}(), {closure#1}(), {closure#2}(), ParamKind, Anchor, Count, Duration, Number (+4 more)

### Community 26 - "RejectionReason"
Cohesion: 0.08
Nodes (29): AccountView, RejectionReason, BelowMinimum, Breaker, Cooldown, DuplicateIntent, EntryWorking, GrossExposure (+21 more)

### Community 27 - "App\Strategy\Dsl\Compiler\CompiledStrategy"
Cohesion: 0.10
Nodes (16): {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), Group, Exit (+8 more)

### Community 28 - ".measure"
Cohesion: 0.14
Nodes (9): EquityTracker, Performance, {closure#1}(), {closure#2}(), {closure#3}(), {closure#5}(), {closure#6}(), {closure#8}() (+1 more)

### Community 29 - "Fill"
Cohesion: 0.11
Nodes (7): ProtectionPlan, Fill, PerpPosition, Position, Price, Perpetual, {closure#16}()

### Community 30 - "EnginePerpTest.php"
Cohesion: 0.23
Nodes (15): InMemoryDecisionLog, {closure#10}(), {closure#11}(), {closure#14}(), {closure#2}(), {closure#5}(), {closure#6}(), {closure#7}() (+7 more)

### Community 31 - "DslBaselinesTest.php"
Cohesion: 0.12
Nodes (17): RenamedStrategy, baselineDocument(), baselinePairs(), {closure#10}(), {closure#12}(), {closure#15}(), {closure#18}(), {closure#20}() (+9 more)

### Community 32 - ".count"
Cohesion: 0.05
Nodes (47): Statistics, Cscv, DeflatedSharpe, Gates, Normal, {closure#3}(), WalkForward, WalkForwardSet (+39 more)

### Community 33 - ".of"
Cohesion: 0.06
Nodes (48): {closure#1}(), {closure#6}(), DecimalMath, {closure#1}(), {closure#2}(), {closure#3}(), documentedLimits(), {closure#41}() (+40 more)

### Community 34 - "Carbon\CarbonImmutable"
Cohesion: 0.13
Nodes (5): EngineRecord, IntentRecord, Engine, ReconciliationMismatch, BacktestResult

### Community 35 - "ExitReason"
Cohesion: 0.22
Nodes (23): ExitReason, Flatten, Liquidation, Signal, Stop, Target, {closure#1}(), {closure#10}() (+15 more)

### Community 36 - "PaperEngineTest.php"
Cohesion: 0.13
Nodes (18): JournalEntry, SendTelegram, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}() (+10 more)

### Community 37 - "SimClock"
Cohesion: 0.07
Nodes (31): SimClock, WallClock, {closure#11}(), {closure#13}(), {closure#14}(), {closure#15}(), {closure#16}(), {closure#4}() (+23 more)

### Community 38 - "App\Support\Symbol"
Cohesion: 0.12
Nodes (6): {closure#1}(), {closure#1}(), {closure#3}(), {closure#5}(), {closure#1}(), {closure#2}()

### Community 39 - "BacktestTest.php"
Cohesion: 0.17
Nodes (16): FeeSchedule, Backtest, RunDefinition, InMemoryCandleFeed, Policies, {closure#26}(), {closure#27}(), {closure#28}() (+8 more)

### Community 40 - "OrderManagerTest.php"
Cohesion: 0.26
Nodes (16): InstrumentRules, {closure#1}(), btcUsdt(), {closure#10}(), {closure#15}(), {closure#16}(), {closure#2}(), {closure#4}() (+8 more)

### Community 41 - "ema-cross-scalp-long.strategy.yaml"
Cohesion: 0.06
Nodes (33): entries, long, exits, stop_loss, take_profit, time_stop, when, fails_when (+25 more)

### Community 42 - "StrategyGates.php"
Cohesion: 0.08
Nodes (15): {closure#11}(), {closure#12}(), {closure#3}(), {closure#8}(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}() (+7 more)

### Community 43 - "autoload-dev"
Cohesion: 0.67
Nodes (3): autoload-dev, psr-4, Tests\\

### Community 44 - "OrderRole"
Cohesion: 0.15
Nodes (15): OrderRole, Entry, Exit, Flatten, Stop, Target, TrackedOrder, {closure#1}() (+7 more)

### Community 46 - "Illuminate\Database\Eloquent\Model"
Cohesion: 0.08
Nodes (11): {closure#1}(), McpCall, AccountSnapshot, Deployment, {closure#1}(), InstrumentFilter, BacktestRun, StrategyGateReport (+3 more)

### Community 47 - "package.json"
Cohesion: 0.13
Nodes (20): devDependencies, concurrently@^10.0.3, laravel-vite-plugin@^3.1, tailwindcss@^4.0.0, @tailwindcss/vite@^4.0.0, vite@^8.0.0, optionalDependencies, @laravel/multiplex@^0.4.1 (+12 more)

### Community 48 - "FundingCarry"
Cohesion: 0.13
Nodes (8): FundingCarry, Parameters, {closure#17}(), {closure#14}(), {closure#15}(), {closure#16}(), {closure#17}(), {closure#18}()

### Community 49 - "BinanceRestClientTest.php"
Cohesion: 0.11
Nodes (22): BinanceRequestFailed, LocationRestricted, RateLimited, binanceClient(), {closure#1}(), {closure#11}(), {closure#13}(), {closure#3}() (+14 more)

### Community 50 - "ema-cross-scalp-short.strategy.yaml"
Cohesion: 0.06
Nodes (33): entries, short, exits, stop_loss, take_profit, time_stop, when, fails_when (+25 more)

### Community 51 - "App\Support\Number\Price"
Cohesion: 0.13
Nodes (6): {closure#2}(), EntryOrder, OpenLong, OpenShort, StrategyLimits, {closure#25}()

### Community 52 - "TokensTest.php"
Cohesion: 0.31
Nodes (7): {closure#3}(), {closure#4}(), {closure#5}(), contrastRatio(), contrastRules(), relativeLuminance(), themeTokens()

### Community 53 - "Laravel\Mcp\Response"
Cohesion: 0.08
Nodes (14): ChartTool, CompileStrategy, GetCandles, GetFunding, GetIndicators, GetJob, GetStrategy, ListInstruments (+6 more)

### Community 54 - "postgres"
Cohesion: 0.10
Nodes (19): POSTGRES_DB, POSTGRES_PASSWORD, POSTGRES_USER, interval, retries, test, timeout, environment (+11 more)

### Community 55 - "InstrumentRules"
Cohesion: 0.25
Nodes (9): RulesHistory, {closure#2}(), {closure#4}(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), rules() (+1 more)

### Community 56 - "PreparesBacktests"
Cohesion: 0.12
Nodes (5): {closure#2}(), GridPart, PreparesBacktests, RunBacktest, SetOutcome

### Community 57 - "donchian-breakout-short.strategy.yaml"
Cohesion: 0.07
Nodes (26): entries, short, default, range, step, default, range, step (+18 more)

### Community 58 - "Window"
Cohesion: 0.10
Nodes (5): DonchianChannel, StandardDeviation, Stochastic, Vwma, Window

### Community 59 - "AccountRuntime"
Cohesion: 0.14
Nodes (7): AccountRuntime, {closure#2}(), RunEngine, DatabaseDecisionLog, ReplayDivergence, Account, EngineCommandRow

### Community 60 - "RunBacktestCommandTest.php"
Cohesion: 0.12
Nodes (23): {closure#4}(), {closure#6}(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}() (+15 more)

### Community 61 - "Ledger"
Cohesion: 0.16
Nodes (9): Marks, Ledger, LedgerViolation, {closure#2}(), ParticipantResult, {closure#19}(), {closure#9}(), {closure#12}() (+1 more)

### Community 62 - "BinanceArchive"
Cohesion: 0.10
Nodes (12): ArchivePeriod, BinanceArchive, archiveBytes(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}() (+4 more)

### Community 63 - "CandleFeedTest.php"
Cohesion: 0.44
Nodes (11): {closure#1}(), {closure#12}(), {closure#13}(), {closure#2}(), {closure#4}(), {closure#5}(), {closure#8}(), {closure#9}() (+3 more)

### Community 65 - "donchian-breakout.strategy.yaml"
Cohesion: 0.07
Nodes (26): entries, long, default, range, step, default, range, step (+18 more)

### Community 66 - ".benchmark"
Cohesion: 0.28
Nodes (12): Participant, {closure#13}(), {closure#16}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}() (+4 more)

### Community 67 - "CandleRepositoryTest.php"
Cohesion: 0.28
Nodes (11): candleStore(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}() (+3 more)

### Community 68 - "App\Strategy\Dsl\Document\DocumentChecker"
Cohesion: 0.08
Nodes (28): {closure#3}(), {closure#4}(), ParameterSpec, DslWriter, FunctionSpec, Library, Scope, {closure#1}() (+20 more)

### Community 69 - ".of"
Cohesion: 0.08
Nodes (20): Balance, Asset, AssetMismatch, Money, balances(), {closure#2}(), {closure#1}(), usdt() (+12 more)

### Community 71 - "Universe"
Cohesion: 0.09
Nodes (10): Backfill, BackfillReport, BackfillStep, BackfillMarketData, SelectsInstruments, {closure#1}(), StreamCandles, VerifyMarketData (+2 more)

### Community 72 - "SimulationJobsTest.php"
Cohesion: 0.10
Nodes (9): {closure#1}(), {closure#1}(), {closure#2}(), {closure#3}(), savedEmaCross(), {closure#1}(), {closure#2}(), {closure#3}() (+1 more)

### Community 73 - "scripts"
Cohesion: 0.15
Nodes (13): scripts, analyse, dev, format, mutate, post-autoload-dump, post-update-cmd, pre-package-uninstall (+5 more)

### Community 74 - "ma-trend-short.strategy.yaml"
Cohesion: 0.07
Nodes (26): entries, short, exits, stop_loss, when, fails_when, family, default (+18 more)

### Community 75 - ".parse"
Cohesion: 0.13
Nodes (13): AstPrinter, {closure#8}(), Lexer, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), sexpr() (+5 more)

### Community 76 - "Timeframe"
Cohesion: 0.09
Nodes (31): CandleAggregator, CandleSequenceBroken, {closure#3}(), Timeframe, FifteenMinutes, FiveMinutes, FourHours, OneDay (+23 more)

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

### Community 81 - "IndicatorReferenceTest.php"
Cohesion: 0.06
Nodes (25): {closure#34}(), Bollinger, Change, Highest, Hma, Lowest, Macd, RealizedVolatility (+17 more)

### Community 82 - "CompiledStrategyTest.php"
Cohesion: 0.08
Nodes (23): {closure#9}(), LimitedStrategy, PositionView, StrategyContext, {closure#1}(), {closure#18}(), {closure#37}(), {closure#42}() (+15 more)

### Community 83 - "App\Support\Number\Decimal"
Cohesion: 0.07
Nodes (38): {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#15}(), {closure#16}(), {closure#17}() (+30 more)

### Community 84 - "pint/composer.json"
Cohesion: 0.20
Nodes (9): config, sort-packages, description, license, name, require, laravel/pint, php (+1 more)

### Community 85 - "MonteCarlo"
Cohesion: 0.12
Nodes (11): {closure#4}(), {closure#5}(), MonteCarlo, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}() (+3 more)

### Community 87 - "App\Support\Number\Decimal"
Cohesion: 0.05
Nodes (36): {closure#1}(), {closure#2}(), {closure#3}(), {closure#1}(), {closure#10}(), {closure#2}(), {closure#5}(), {closure#8}() (+28 more)

### Community 88 - "require-dev"
Cohesion: 0.22
Nodes (9): require-dev, fakerphp/faker, larastan/larastan, laravel/pail, laravel/pao, mockery/mockery, nunomaduro/collision, pestphp/pest (+1 more)

### Community 89 - "scripts-descriptions"
Cohesion: 0.25
Nodes (8): scripts-descriptions, analyse, format, mutate, test, test:network, test:testnet, tools:install

### Community 90 - "RiskState"
Cohesion: 0.35
Nodes (7): RiskState, {closure#13}(), {closure#15}(), {closure#17}(), {closure#18}(), {closure#22}(), {closure#23}()

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

### Community 96 - ".handle"
Cohesion: 0.13
Nodes (4): GridBacktest, {closure#2}(), ReadsGridOptions, StrategyGates

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

### Community 108 - "OrderUpdate"
Cohesion: 0.17
Nodes (11): Income, IncomeKind, Funding, Liquidation, OrderUpdate, VenueFill, {closure#3}(), {closure#1}() (+3 more)

### Community 119 - "MarketBreakers"
Cohesion: 0.17
Nodes (11): EngineMode, Backtest, Live, Paper, Testnet, BreakerEvent, MarketBreakers, breakerMinute() (+3 more)

### Community 120 - "buy-and-hold.strategy.yaml"
Cohesion: 0.12
Nodes (16): entries, long, exits, stop_loss, fails_when, family, hypothesis, when (+8 more)

### Community 121 - "App\Market\Data\Candle"
Cohesion: 0.09
Nodes (19): {closure#36}(), BarIndicator, Keltner, Natr, Obv, VolumeProfile, Vwap, chart() (+11 more)

### Community 123 - ".build"
Cohesion: 0.21
Nodes (3): PerformanceMetrics, Report, ReportBuilder

### Community 124 - "Illuminate\Database\ConnectionInterface"
Cohesion: 0.10
Nodes (11): Notifier, CandleFeed, DatabaseCandleFeed, TelegramNotifier, AppServiceProvider, {closure#2}(), {closure#6}(), SimulationQueue (+3 more)

### Community 125 - "App\Simulation\Validation\WalkForward"
Cohesion: 0.18
Nodes (8): {closure#1}(), {closure#3}(), {closure#5}(), {closure#6}(), {closure#7}(), {closure#8}(), {closure#9}(), WalkForwardWindow

### Community 127 - ".run"
Cohesion: 0.32
Nodes (6): btcMinutes(), {closure#1}(), {closure#2}(), {closure#3}(), referencePrice(), GoldenData

### Community 128 - "funding-carry.strategy.yaml"
Cohesion: 0.12
Nodes (15): fails_when, family, hypothesis, params, enter_when, exit_when, margin_buffer, max_leverage_perp (+7 more)

### Community 129 - "McpServerTest.php"
Cohesion: 0.21
Nodes (12): actAsToken(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}(), {closure#8}() (+4 more)

### Community 130 - "PerpetualBaselinesTest.php"
Cohesion: 0.36
Nodes (7): {closure#1}(), {closure#3}(), goldenMinutes(), goldenRates(), perpetualReferencePrice(), referenceMean(), reportTrades()

### Community 131 - "Decimal"
Cohesion: 0.06
Nodes (18): {closure#1}(), {closure#2}(), RoundTrip, {closure#1}(), {closure#2}(), {closure#3}(), TradeStatistics, {closure#1}() (+10 more)

### Community 132 - "App\Execution\Engine"
Cohesion: 0.07
Nodes (11): {closure#1}(), DecisionLog, DecisionRecord, {closure#1}(), {closure#11}(), {closure#2}(), {closure#3}(), {closure#4}() (+3 more)

### Community 133 - "ClientOrderId"
Cohesion: 0.06
Nodes (18): ClientOrderId, OcoRequest, OrderAck, OrderRequest, OrderType, Limit, Market, StopMarket (+10 more)

### Community 138 - "App\Market\Archive\BinanceArchive"
Cohesion: 0.07
Nodes (22): {closure#1}(), {closure#2}(), {closure#1}(), {closure#2}(), {closure#3}(), EpochTime, ExchangeNumber, FundingRate (+14 more)

### Community 139 - ".encode"
Cohesion: 0.33
Nodes (5): CanonicalJson, {closure#1}(), {closure#3}(), {closure#4}(), {closure#5}()

### Community 140 - "RiskEventKind"
Cohesion: 0.24
Nodes (6): RiskEvent, RiskEventKind, DailyLossHalt, DrawdownFlatten, DrawdownHalt, WeeklyLossHalt

### Community 141 - "RiskPolicy"
Cohesion: 0.24
Nodes (5): RiskProfile, Account, Benchmark, RiskPolicy, {closure#1}()

### Community 142 - "StrategyVersion"
Cohesion: 0.11
Nodes (17): {closure#1}(), {closure#1}(), {closure#1}(), SaveResult, StrategyVersion, {closure#1}(), {closure#1}(), checkDocument() (+9 more)

### Community 143 - "VenuePosition"
Cohesion: 0.31
Nodes (4): VenuePosition, {closure#1}(), SimulatedPosition, {closure#13}()

### Community 144 - "Expression"
Cohesion: 0.29
Nodes (5): {closure#7}(), {closure#9}(), EntrySpec, Expression, {closure#1}()

### Community 145 - "Tests\Support\GoldenData"
Cohesion: 0.29
Nodes (6): {closure#12}(), {closure#2}(), {closure#1}(), {closure#3}(), {closure#4}(), {closure#5}()

### Community 146 - "MinuteCandles"
Cohesion: 0.29
Nodes (4): MinuteCandles, {closure#14}(), {closure#3}(), {closure#7}()

## Knowledge Gaps
- **122 isolated node(s):** `Flatten`, `Halt`, `Pause`, `Resume`, `ResumeStrategy` (+117 more)
  These have ≤1 connection - possible missing edges. (Counts symbols only; 756 node(s) total have ≤1 connection when file, concept and rationale nodes are included.)
- **29 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `Decimal` connect `Decimal` to `.of`, `OrderStatus`, `ClientOrderId`, `App\Execution\Engine`, `Instrument`, `Position`, `SimulatedVenueTest.php`, `App\Market\Archive\BinanceArchive`, `SimulatedPerpVenueTest.php`, `Candle`, `RiskEventKind`, `RiskPolicy`, `VenuePosition`, `IndicatorsTest.php`, `Tests\Support\GoldenData`, `.encode`, `MarketType`, `LedgerTest.php`, `Symbol`, `RejectionReason`, `.parse`, `.measure`, `Fill`, `EnginePerpTest.php`, `.count`, `.of`, `ExitReason`, `SimClock`, `App\Support\Symbol`, `BacktestTest.php`, `OrderManagerTest.php`, `OrderRole`, `FundingCarry`, `App\Support\Number\Price`, `InstrumentRules`, `PreparesBacktests`, `Ledger`, `CandleFeedTest.php`, `App\Support\Symbol`, `.benchmark`, `.of`, `Timeframe`, `CompiledStrategyTest.php`, `App\Support\Number\Decimal`, `MonteCarlo`, `App\Support\Number\Decimal`, `OrderUpdate`, `App\Market\Data\Candle`, `.times`, `.build`, `Illuminate\Database\ConnectionInterface`, `.reconcile`, `.run`?**
  _High betweenness centrality (0.089) - this node is a cross-community bridge._
- **What connects `Flatten`, `Halt`, `Pause` to the rest of the system?**
  _122 weakly-connected nodes found - possible missing edges._
- **Should `EngineTest.php` be split into smaller, more focused modules?**
  _Cohesion score 0.08909995477159656 - nodes in this community are weakly interconnected._
- **Why does `Symbol` connect `Symbol` to `Decimal`, `OrderStatus`, `App\Execution\Engine`, `ClientOrderId`, `Instrument`, `.parse`, `SimulatedVenueTest.php`, `App\Market\Archive\BinanceArchive`, `SimulatedPerpVenueTest.php`, `.encode`, `ExchangeVerificationTest.php`, `VenuePosition`, `Tests\Support\GoldenData`, `MarketType`, `LedgerTest.php`, `{closure#12}`, `.measure`, `Fill`, `EnginePerpTest.php`, `Carbon\CarbonImmutable`, `ExitReason`, `SimClock`, `App\Support\Symbol`, `BacktestTest.php`, `OrderManagerTest.php`, `OrderRole`, `FundingCarry`, `BinanceRestClientTest.php`, `App\Support\Number\Price`, `InstrumentRules`, `PreparesBacktests`, `AccountRuntime`, `Ledger`, `CandleFeedTest.php`, `App\Support\Symbol`, `CandleRepositoryTest.php`, `.of`, `Universe`, `CompiledStrategyTest.php`, `App\Support\Number\Decimal`, `OrderUpdate`, `Illuminate\Database\ConnectionInterface`, `.reconcile`, `.run`?**
  _High betweenness centrality (0.041) - this node is a cross-community bridge._
- **Should `App\Support\Clock\Clock` be split into smaller, more focused modules?**
  _Cohesion score 0.07918367346938776 - nodes in this community are weakly interconnected._
- **Why does `Timeframe` connect `Timeframe` to `EngineTest.php`, `.parse`, `Instrument`, `Position`, `CompiledStrategy.php`, `SimulatedVenueTest.php`, `Clock`, `App\Market\Archive\BinanceArchive`, `Candle`, `IndicatorsTest.php`, `App\Strategy\Dsl\Position`, `MarketType`, `ReadBack`, `Symbol`, `EnginePerpTest.php`, `OrderManagerTest.php`, `FundingCarry`, `BinanceRestClientTest.php`, `PreparesBacktests`, `BinanceArchive`, `CandleFeedTest.php`, `CandleRepositoryTest.php`, `CompiledStrategyTest.php`, `MonteCarlo`, `App\Market\Data\Candle`, `Illuminate\Database\ConnectionInterface`?**
  _High betweenness centrality (0.029) - this node is a cross-community bridge._
- **Should `RuntimeException` be split into smaller, more focused modules?**
  _Cohesion score 0.07130124777183601 - nodes in this community are weakly interconnected._