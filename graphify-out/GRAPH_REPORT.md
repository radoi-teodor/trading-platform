# Graph Report - trading-platform  (2026-11-09)

## Corpus Check
- 493 files · ~207,257 words
- Verdict: corpus is large enough that graph structure adds value.
- Unclassified: 92 file(s) not represented in the graph (top: .zip 34, .CHECKSUM 34, (none) 18)

## Summary
- 4015 nodes · 14182 edges · 152 communities (122 shown, 30 thin omitted)
- Extraction: 89% EXTRACTED · 11% INFERRED · 0% AMBIGUOUS · INFERRED: 1506 edges (avg confidence: 0.85)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `fc83a540`
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
- App\Strategy\Dsl\Document\StrategySpec
- App\Strategy\Dsl\Position
- MarketType
- LedgerTest.php
- Illuminate\Database\Schema\Blueprint
- generate.py
- ReadBack
- Symbol
- ParamKind
- .assessEntry
- App\Strategy\Dsl\Compiler\CompiledStrategy
- .measure
- Money
- EnginePerpTest.php
- DslBaselinesTest.php
- .zero
- .of
- EngineRecord
- ExitReason
- PaperEngineTest.php
- SimClock
- InvalidArgumentException
- BacktestTest.php
- RunEngine.php
- ema-cross-scalp-long.strategy.yaml
- StrategyGates.php
- autoload-dev
- OrderRole
- User
- Illuminate\Database\Eloquent\Model
- package.json
- App\Execution\Venues\SimulatedVenue
- BinanceRestClientTest.php
- ema-cross-scalp-short.strategy.yaml
- App\Support\Symbol
- DocumentCheckerTest.php
- Laravel\Mcp\Response
- postgres
- InstrumentRules
- PreparesBacktests
- donchian-breakout-short.strategy.yaml
- Window
- AccountRuntime
- RunBacktestCommandTest.php
- Asset
- BinanceArchive
- BinanceVenue
- App\Support\Symbol
- donchian-breakout.strategy.yaml
- .run
- CandleRepositoryTest.php
- App\Strategy\Dsl\Document\DocumentChecker
- .expect
- Engine
- TestnetEngineTest.php
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
- StrategySpec
- require-dev
- scripts-descriptions
- .evaluate
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
- Price
- MarketBreakers
- buy-and-hold.strategy.yaml
- App\Market\Data\Candle
- GridBacktest.php
- .evaluate
- App\Execution\Data\EngineRecord
- App\Simulation\Validation\WalkForward
- .reconcile
- .count
- funding-carry.strategy.yaml
- McpServerTest.php
- AuthenticateMcpToken.php
- Decimal
- .__construct
- OrderAck
- App\Market\Archive\BinanceArchive
- .encode
- Carbon\CarbonImmutable
- RiskProfile
- StrategyLibrary
- BinanceVenue.php
- Expression
- Percent
- Quantity
- .onCandle
- GridPart.php
- Rsi
- App\Simulation\Data\RunDefinition

## God Nodes (most connected - your core abstractions)
1. `Decimal` - 272 edges
2. `Side` - 187 edges
3. `Symbol` - 160 edges
4. `Timeframe` - 98 edges
5. `Candle` - 94 edges
6. `Price` - 77 edges
7. `MarketType` - 68 edges
8. `Position` - 61 edges
9. `SimulatedVenue` - 57 edges
10. `Ledger` - 56 edges

## Surprising Connections (you probably didn't know these)
- `binanceVenue()` --calls--> `SimClock`  [INFERRED]
  tests/Feature/Execution/BinanceVenueTest.php → app/Support/Clock/SimClock.php
- `{closure#5}()` --calls--> `OcoRequest`  [INFERRED]
  tests/Feature/Execution/BinanceVenueTest.php → app/Execution/Data/OcoRequest.php
- `testnetAccount()` --calls--> `SimClock`  [INFERRED]
  tests/Feature/Execution/TestnetEngineTest.php → app/Support/Clock/SimClock.php
- `{closure#1}()` --calls--> `OcoRequest`  [INFERRED]
  tests/Feature/Testnet/BinanceTestnetTest.php → app/Execution/Data/OcoRequest.php
- `{closure#1}()` --calls--> `WallClock`  [INFERRED]
  tests/Feature/Testnet/BinanceTestnetTest.php → app/Support/Clock/WallClock.php

## Import Cycles
- None detected.

## Communities (152 total, 30 thin omitted)

### Community 0 - ".of"
Cohesion: 0.08
Nodes (71): RejectionReason, BelowMinimum, Breaker, Cooldown, DuplicateIntent, EntryWorking, GrossExposure, Halted (+63 more)

### Community 1 - "EngineTest.php"
Cohesion: 0.09
Nodes (41): CommandKind, Flatten, Halt, Pause, Resume, ResumeStrategy, EngineCommand, ScriptedStrategy (+33 more)

### Community 2 - "App\Support\Clock\Clock"
Cohesion: 0.07
Nodes (16): CreateMcpToken, RevokeMcpToken, McpToken, {closure#1}(), {closure#2}(), CreateAccount, FlattenEngine, HaltEngine (+8 more)

### Community 3 - "RuntimeException"
Cohesion: 0.07
Nodes (8): SystemSleeper, PhrityTransport, StreamFailed, AppServiceProvider, {closure#3}(), {closure#4}(), {closure#6}(), FakeTransport

### Community 4 - "OrderStatus"
Cohesion: 0.07
Nodes (17): Liquidity, Maker, Taker, OrderStatus, Cancelled, Expired, Filled, New (+9 more)

### Community 5 - ".parse"
Cohesion: 0.10
Nodes (37): {closure#12}(), Parameters, {closure#17}(), {closure#5}(), carryDecisions(), {closure#10}(), {closure#11}(), {closure#12}() (+29 more)

### Community 6 - "Instrument"
Cohesion: 0.06
Nodes (15): CheckMarketData, SelectsInstruments, {closure#1}(), StreamCandles, VerifyMarketData, {closure#1}(), Universe, Instrument (+7 more)

### Community 7 - "Position"
Cohesion: 0.07
Nodes (18): Argument, BoolLiteral, Call, DurationLiteral, Identifier, Lookback, Node, NumberLiteral (+10 more)

### Community 8 - "CompiledStrategy.php"
Cohesion: 0.10
Nodes (8): {closure#3}(), CompiledStrategy, {closure#33}(), Compiler, Program, {closure#2}(), {closure#8}(), Vwap

### Community 9 - "SimulatedVenueTest.php"
Cohesion: 0.17
Nodes (44): OcoRequest, OrderRequest, Side, Buy, Sell, btc(), {closure#10}(), {closure#11}() (+36 more)

### Community 10 - "Clock"
Cohesion: 0.06
Nodes (21): Backfill, BackfillReport, BackfillStep, BinanceEndpoints, Sleeper, CandleRepository, {closure#1}(), {closure#2}() (+13 more)

### Community 11 - "SimulatedPerpVenueTest.php"
Cohesion: 0.21
Nodes (24): {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#15}(), {closure#18}(), {closure#19}() (+16 more)

### Community 12 - "Candle"
Cohesion: 0.10
Nodes (33): Candle, InvalidCandle, {closure#9}(), {closure#1}(), {closure#42}(), {closure#12}(), {closure#13}(), candleWith() (+25 more)

### Community 13 - "ExchangeVerificationTest.php"
Cohesion: 0.10
Nodes (30): {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#2}(), {closure#3}(), {closure#4}() (+22 more)

### Community 14 - "IndicatorsTest.php"
Cohesion: 0.13
Nodes (18): Atr, Ema, Keltner, Sma, bar(), {closure#1}(), {closure#12}(), {closure#13}() (+10 more)

### Community 15 - "App\Simulation\SimulationQueue"
Cohesion: 0.10
Nodes (4): RunBacktest, RunGrid, SimulationTool, Controller

### Community 16 - "Node"
Cohesion: 0.11
Nodes (28): Binary, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}() (+20 more)

### Community 17 - "App\Strategy\Dsl\Document\StrategySpec"
Cohesion: 0.14
Nodes (10): StoredInstruments, {closure#1}(), CompileStrategy, PolicyLimits, {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}() (+2 more)

### Community 18 - "App\Strategy\Dsl\Position"
Cohesion: 0.11
Nodes (12): {closure#5}(), DocumentChecker, DslError, YamlList, YamlMap, YamlReader, YamlScalar, YamlValue (+4 more)

### Community 19 - "MarketType"
Cohesion: 0.06
Nodes (25): BinanceRequestFailed, BinanceRestClient, Json, LocationRestricted, RateLimited, RequestFailed, SyncInstruments, InstrumentDefinition (+17 more)

### Community 20 - "LedgerTest.php"
Cohesion: 0.16
Nodes (31): Ledger, LedgerViolation, balanceOf(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}() (+23 more)

### Community 21 - "Illuminate\Database\Schema\Blueprint"
Cohesion: 0.05
Nodes (28): {closure#1}(), {closure#2}(), {closure#3}(), {closure#1}(), {closure#2}(), {closure#1}(), {closure#2}(), {closure#3}() (+20 more)

### Community 22 - "generate.py"
Cohesion: 0.08
Nodes (30): atr(), bars(), bb(), change(), dmi(), donchian(), ema(), highest() (+22 more)

### Community 24 - "Symbol"
Cohesion: 0.05
Nodes (21): BuyAndHold, DonchianBreakout, FundingCarry, MovingAverageTrend, BuildableStrategy, Strategy, Close, Intent (+13 more)

### Community 25 - "ParamKind"
Cohesion: 0.10
Nodes (14): {closure#2}(), {closure#1}(), {closure#2}(), ParamKind, Anchor, Count, Duration, Number (+6 more)

### Community 27 - "App\Strategy\Dsl\Compiler\CompiledStrategy"
Cohesion: 0.07
Nodes (23): {closure#1}(), {closure#1}(), {closure#2}(), {closure#5}(), {closure#6}(), {closure#1}(), {closure#3}(), {closure#4}() (+15 more)

### Community 28 - ".measure"
Cohesion: 0.19
Nodes (9): EquityTracker, Performance, {closure#1}(), {closure#2}(), {closure#3}(), {closure#5}(), {closure#6}(), {closure#8}() (+1 more)

### Community 29 - "Money"
Cohesion: 0.20
Nodes (3): Fill, AssetMismatch, Money

### Community 30 - "EnginePerpTest.php"
Cohesion: 0.30
Nodes (13): {closure#10}(), {closure#11}(), {closure#14}(), {closure#5}(), {closure#6}(), {closure#7}(), {closure#8}(), {closure#9}() (+5 more)

### Community 31 - "DslBaselinesTest.php"
Cohesion: 0.09
Nodes (23): {closure#1}(), {closure#2}(), RenamedStrategy, baselineDocument(), baselinePairs(), {closure#10}(), {closure#11}(), {closure#12}() (+15 more)

### Community 32 - ".zero"
Cohesion: 0.11
Nodes (23): CostModel, Cscv, DeflatedSharpe, {closure#23}(), {closure#44}(), {closure#45}(), {closure#46}(), closeTo() (+15 more)

### Community 33 - ".of"
Cohesion: 0.07
Nodes (37): {closure#8}(), Normal, DecimalMath, InvalidNumber, {closure#10}(), {closure#24}(), {closure#1}(), {closure#10}() (+29 more)

### Community 34 - "EngineRecord"
Cohesion: 0.14
Nodes (10): EngineMode, Backtest, Live, Paper, Testnet, EngineRecord, {closure#1}(), {closure#3}() (+2 more)

### Community 35 - "ExitReason"
Cohesion: 0.14
Nodes (23): ExitReason, Flatten, Liquidation, Signal, Stop, Target, RoundTrip, {closure#1}() (+15 more)

### Community 36 - "PaperEngineTest.php"
Cohesion: 0.11
Nodes (19): Account, AccountSnapshot, JournalEntry, SendTelegram, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}() (+11 more)

### Community 37 - "SimClock"
Cohesion: 0.07
Nodes (30): SimClock, WallClock, {closure#11}(), {closure#14}(), {closure#15}(), {closure#16}(), {closure#4}(), {closure#6}() (+22 more)

### Community 38 - "InvalidArgumentException"
Cohesion: 0.13
Nodes (7): {closure#1}(), {closure#3}(), {closure#5}(), {closure#7}(), {closure#1}(), {closure#2}(), Period

### Community 39 - "BacktestTest.php"
Cohesion: 0.09
Nodes (26): FeeSchedule, RiskPolicy, Backtest, Participant, RunDefinition, Report, ReportBuilder, {closure#1}() (+18 more)

### Community 40 - "RunEngine.php"
Cohesion: 0.10
Nodes (13): BinanceApiError, BinanceUnreachable, SignedBinanceClient, binanceVenue(), {closure#1}(), {closure#2}(), {closure#4}(), {closure#5}() (+5 more)

### Community 41 - "ema-cross-scalp-long.strategy.yaml"
Cohesion: 0.06
Nodes (33): entries, long, exits, stop_loss, take_profit, time_stop, when, fails_when (+25 more)

### Community 42 - "StrategyGates.php"
Cohesion: 0.06
Nodes (23): {closure#8}(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#7}(), {closure#1}() (+15 more)

### Community 43 - "autoload-dev"
Cohesion: 0.67
Nodes (3): autoload-dev, psr-4, Tests\\

### Community 44 - "OrderRole"
Cohesion: 0.12
Nodes (29): OrderRole, Entry, Exit, Flatten, Stop, Target, TrackedOrder, {closure#1}() (+21 more)

### Community 46 - "Illuminate\Database\Eloquent\Model"
Cohesion: 0.08
Nodes (14): McpCall, Deployment, ExchangeKey, {closure#1}(), InstrumentFilter, BacktestRun, StrategyGateReport, StrategyHoldout (+6 more)

### Community 47 - "package.json"
Cohesion: 0.13
Nodes (20): devDependencies, concurrently@^10.0.3, laravel-vite-plugin@^3.1, tailwindcss@^4.0.0, @tailwindcss/vite@^4.0.0, vite@^8.0.0, optionalDependencies, @laravel/multiplex@^0.4.1 (+12 more)

### Community 48 - "App\Execution\Venues\SimulatedVenue"
Cohesion: 0.14
Nodes (8): {closure#1}(), {closure#2}(), WarmingVenue, {closure#22}(), {closure#32}(), {closure#34}(), {closure#49}(), OverReportingVenue

### Community 49 - "BinanceRestClientTest.php"
Cohesion: 0.10
Nodes (26): {closure#3}(), {closure#13}(), {closure#5}(), binanceClient(), {closure#1}(), {closure#11}(), {closure#12}(), {closure#13}() (+18 more)

### Community 50 - "ema-cross-scalp-short.strategy.yaml"
Cohesion: 0.06
Nodes (33): entries, short, exits, stop_loss, take_profit, time_stop, when, fails_when (+25 more)

### Community 51 - "App\Support\Symbol"
Cohesion: 0.12
Nodes (9): {closure#2}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#1}(), EntryOrder, OpenLong, OpenShort (+1 more)

### Community 52 - "DocumentCheckerTest.php"
Cohesion: 0.15
Nodes (17): InstrumentDirectory, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), versions(), KnownInstruments, checkDocument() (+9 more)

### Community 53 - "Laravel\Mcp\Response"
Cohesion: 0.08
Nodes (14): ChartTool, DslReference, GetCandles, GetFunding, GetIndicators, GetJob, GetStrategy, ListInstruments (+6 more)

### Community 54 - "postgres"
Cohesion: 0.10
Nodes (19): POSTGRES_DB, POSTGRES_PASSWORD, POSTGRES_USER, interval, retries, test, timeout, environment (+11 more)

### Community 55 - "InstrumentRules"
Cohesion: 0.26
Nodes (11): InstrumentRules, {closure#1}(), RulesHistory, {closure#4}(), {closure#41}(), {closure#1}(), {closure#2}(), {closure#3}() (+3 more)

### Community 56 - "PreparesBacktests"
Cohesion: 0.16
Nodes (4): {closure#1}(), GridPart, PreparesBacktests, RunBacktest

### Community 57 - "donchian-breakout-short.strategy.yaml"
Cohesion: 0.07
Nodes (26): entries, short, default, range, step, default, range, step (+18 more)

### Community 58 - "Window"
Cohesion: 0.06
Nodes (17): {closure#34}(), Bollinger, Change, DonchianChannel, Highest, Hma, Lowest, Roc (+9 more)

### Community 59 - "AccountRuntime"
Cohesion: 0.14
Nodes (3): AccountRuntime, RunEngine, DatabaseDecisionLog

### Community 60 - "RunBacktestCommandTest.php"
Cohesion: 0.12
Nodes (23): {closure#4}(), {closure#6}(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}() (+15 more)

### Community 61 - "Asset"
Cohesion: 0.12
Nodes (7): Balance, Marks, {closure#2}(), Asset, balances(), {closure#25}(), {closure#9}()

### Community 62 - "BinanceArchive"
Cohesion: 0.09
Nodes (14): ReplayDivergence, ArchiveMissing, BinanceArchive, ChecksumMismatch, archiveBytes(), {closure#1}(), {closure#2}(), {closure#3}() (+6 more)

### Community 63 - "BinanceVenue"
Cohesion: 0.23
Nodes (3): {closure#2}(), BinanceVenue, {closure#1}()

### Community 64 - "App\Support\Symbol"
Cohesion: 0.12
Nodes (7): {closure#1}(), {closure#11}(), {closure#2}(), {closure#3}(), {closure#9}(), {closure#1}(), OrderManager

### Community 65 - "donchian-breakout.strategy.yaml"
Cohesion: 0.07
Nodes (26): entries, long, default, range, step, default, range, step (+18 more)

### Community 66 - ".run"
Cohesion: 0.13
Nodes (22): btcMinutes(), {closure#1}(), {closure#2}(), referencePrice(), {closure#13}(), {closure#21}(), {closure#2}(), {closure#3}() (+14 more)

### Community 67 - "CandleRepositoryTest.php"
Cohesion: 0.28
Nodes (11): candleStore(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}() (+3 more)

### Community 68 - "App\Strategy\Dsl\Document\DocumentChecker"
Cohesion: 0.08
Nodes (29): {closure#9}(), DslWriter, FunctionSpec, Library, Scope, TypeChecker, Kind, Condition (+21 more)

### Community 69 - ".expect"
Cohesion: 0.09
Nodes (31): {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#3}(), {closure#4}(), {closure#5}(), contrastRatio() (+23 more)

### Community 71 - "TestnetEngineTest.php"
Cohesion: 0.18
Nodes (8): {closure#5}(), EngineCommandRow, {closure#1}(), {closure#2}(), {closure#3}(), fakeTestnet(), testnetAccount(), TestCase

### Community 72 - "SimulationJobsTest.php"
Cohesion: 0.15
Nodes (4): {closure#1}(), {closure#2}(), {closure#3}(), savedEmaCross()

### Community 73 - "scripts"
Cohesion: 0.15
Nodes (13): scripts, analyse, dev, format, mutate, post-autoload-dump, post-update-cmd, pre-package-uninstall (+5 more)

### Community 74 - "ma-trend-short.strategy.yaml"
Cohesion: 0.07
Nodes (26): entries, short, exits, stop_loss, when, fails_when, family, default (+18 more)

### Community 75 - ".parse"
Cohesion: 0.19
Nodes (7): AstPrinter, Lexer, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), sexpr()

### Community 76 - "Timeframe"
Cohesion: 0.09
Nodes (41): CandleAggregator, CandleSequenceBroken, {closure#2}(), {closure#4}(), Timeframe, FifteenMinutes, FiveMinutes, FourHours (+33 more)

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
Cohesion: 0.09
Nodes (13): {closure#1}(), {closure#2}(), Macd, RealizedVolatility, assertMatchesReference(), {closure#10}(), {closure#11}(), {closure#15}() (+5 more)

### Community 82 - "CompiledStrategyTest.php"
Cohesion: 0.21
Nodes (14): aggregatedBars(), {closure#11}(), {closure#13}(), {closure#16}(), {closure#17}(), {closure#2}(), {closure#3}(), {closure#4}() (+6 more)

### Community 83 - "App\Support\Number\Decimal"
Cohesion: 0.05
Nodes (56): {closure#1}(), {closure#2}(), {closure#3}(), {closure#10}(), {closure#5}(), {closure#4}(), {closure#12}(), {closure#1}() (+48 more)

### Community 84 - "pint/composer.json"
Cohesion: 0.20
Nodes (9): config, sort-packages, description, license, name, require, laravel/pint, php (+1 more)

### Community 85 - "MonteCarlo"
Cohesion: 0.30
Nodes (9): MonteCarlo, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), mcDecimals() (+1 more)

### Community 87 - "StrategySpec"
Cohesion: 0.24
Nodes (4): CarryTemplate, CheckResult, ExitSpec, StrategySpec

### Community 88 - "require-dev"
Cohesion: 0.22
Nodes (9): require-dev, fakerphp/faker, larastan/larastan, laravel/pail, laravel/pao, mockery/mockery, nunomaduro/collision, pestphp/pest (+1 more)

### Community 89 - "scripts-descriptions"
Cohesion: 0.25
Nodes (8): scripts-descriptions, analyse, format, mutate, test, test:network, test:testnet, tools:install

### Community 90 - ".evaluate"
Cohesion: 0.24
Nodes (9): Gates, brokenGate(), {closure#1}(), {closure#3}(), {closure#4}(), {closure#6}(), {closure#7}(), gate() (+1 more)

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

### Community 96 - ".handle"
Cohesion: 0.13
Nodes (3): GridBacktest, ReadsGridOptions, StrategyGates

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

### Community 108 - "Price"
Cohesion: 0.09
Nodes (19): ClientOrderId, Income, IncomeKind, Funding, Liquidation, OrderType, Limit, Market (+11 more)

### Community 119 - "MarketBreakers"
Cohesion: 0.29
Nodes (7): {closure#4}(), BreakerEvent, MarketBreakers, breakerMinute(), {closure#1}(), {closure#2}(), {closure#3}()

### Community 120 - "buy-and-hold.strategy.yaml"
Cohesion: 0.12
Nodes (16): entries, long, exits, stop_loss, fails_when, family, hypothesis, when (+8 more)

### Community 121 - "App\Market\Data\Candle"
Cohesion: 0.07
Nodes (20): {closure#1}(), {closure#3}(), BarIndicator, DirectionalMovement, Natr, Obv, Supertrend, VolumeProfile (+12 more)

### Community 122 - "GridBacktest.php"
Cohesion: 0.22
Nodes (6): {closure#11}(), {closure#12}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#6}()

### Community 123 - ".evaluate"
Cohesion: 0.25
Nodes (8): WalkForward, WalkForwardSet, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), wfSet()

### Community 124 - "App\Execution\Data\EngineRecord"
Cohesion: 0.21
Nodes (5): {closure#4}(), Notifier, TelegramNotifier, SimulationQueue, {closure#1}()

### Community 125 - "App\Simulation\Validation\WalkForward"
Cohesion: 0.17
Nodes (9): {closure#1}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}(), {closure#8}(), {closure#9}() (+1 more)

### Community 126 - ".reconcile"
Cohesion: 0.15
Nodes (3): Venue, ReconciliationMismatch, {closure#12}()

### Community 127 - ".count"
Cohesion: 0.24
Nodes (11): Statistics, {closure#3}(), {closure#3}(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}() (+3 more)

### Community 128 - "funding-carry.strategy.yaml"
Cohesion: 0.12
Nodes (15): fails_when, family, hypothesis, params, enter_when, exit_when, margin_buffer, max_leverage_perp (+7 more)

### Community 129 - "McpServerTest.php"
Cohesion: 0.30
Nodes (11): actAsToken(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}(), {closure#8}() (+3 more)

### Community 130 - "AuthenticateMcpToken.php"
Cohesion: 0.21
Nodes (4): AuthenticateMcpToken, {closure#1}(), {closure#2}(), {closure#3}()

### Community 131 - "Decimal"
Cohesion: 0.06
Nodes (18): {closure#2}(), PerpPosition, Position, {closure#3}(), PerformanceMetrics, {closure#1}(), {closure#2}(), {closure#3}() (+10 more)

### Community 132 - ".__construct"
Cohesion: 0.12
Nodes (7): DecisionLog, DecisionRecord, IntentRecord, {closure#1}(), InMemoryDecisionLog, BacktestResult, ParticipantResult

### Community 138 - "App\Market\Archive\BinanceArchive"
Cohesion: 0.09
Nodes (16): {closure#1}(), {closure#2}(), EpochTime, ExchangeNumber, KlineMessage, {closure#5}(), {closure#1}(), {closure#2}() (+8 more)

### Community 139 - ".encode"
Cohesion: 0.30
Nodes (6): CanonicalJson, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}()

### Community 140 - "Carbon\CarbonImmutable"
Cohesion: 0.06
Nodes (23): ArchivePeriod, {closure#1}(), {closure#2}(), {closure#3}(), {closure#1}(), CandleFeed, FundingRate, MinuteCandles (+15 more)

### Community 141 - "RiskProfile"
Cohesion: 0.25
Nodes (7): RiskProfile, Account, Benchmark, {closure#1}(), {closure#2}(), {closure#3}(), documentedLimits()

### Community 142 - "StrategyLibrary"
Cohesion: 0.13
Nodes (6): CompileStrategy, {closure#1}(), ListStrategies, StrategyLibrary, SaveResult, StrategyVersion

### Community 143 - "BinanceVenue.php"
Cohesion: 0.19
Nodes (6): VenuePosition, {closure#1}(), {closure#1}(), SimulatedPosition, {closure#13}(), {closure#1}()

### Community 144 - "Expression"
Cohesion: 0.33
Nodes (4): {closure#7}(), EntrySpec, Expression, {closure#1}()

### Community 145 - "Percent"
Cohesion: 0.24
Nodes (3): Percent, {closure#14}(), {closure#15}()

### Community 146 - "Quantity"
Cohesion: 0.24
Nodes (3): Quantity, {closure#10}(), {closure#9}()

## Knowledge Gaps
- **122 isolated node(s):** `Flatten`, `Halt`, `Pause`, `Resume`, `ResumeStrategy` (+117 more)
  These have ≤1 connection - possible missing edges. (Counts symbols only; 767 node(s) total have ≤1 connection when file, concept and rationale nodes are included.)
- **30 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `Decimal` connect `Decimal` to `.of`, `OrderStatus`, `OrderAck`, `Instrument`, `Position`, `.parse`, `SimulatedVenueTest.php`, `App\Market\Archive\BinanceArchive`, `.encode`, `Candle`, `Carbon\CarbonImmutable`, `RiskProfile`, `BinanceVenue.php`, `IndicatorsTest.php`, `Percent`, `Quantity`, `MarketType`, `LedgerTest.php`, `.onCandle`, `App\Simulation\Data\RunDefinition`, `Symbol`, `.assessEntry`, `.measure`, `Money`, `EnginePerpTest.php`, `.zero`, `.of`, `EngineRecord`, `ExitReason`, `SimClock`, `InvalidArgumentException`, `BacktestTest.php`, `RunEngine.php`, `StrategyGates.php`, `OrderRole`, `App\Execution\Venues\SimulatedVenue`, `InstrumentRules`, `PreparesBacktests`, `RunBacktestCommandTest.php`, `Asset`, `App\Support\Symbol`, `.run`, `.expect`, `Engine`, `Timeframe`, `App\Support\Number\Decimal`, `Price`, `App\Market\Data\Candle`, `.reconcile`, `.count`?**
  _High betweenness centrality (0.087) - this node is a cross-community bridge._
- **What connects `Flatten`, `Halt`, `Pause` to the rest of the system?**
  _122 weakly-connected nodes found - possible missing edges._
- **Should `.of` be split into smaller, more focused modules?**
  _Cohesion score 0.07716196846631629 - nodes in this community are weakly interconnected._
- **Why does `Symbol` connect `Symbol` to `.of`, `Decimal`, `.__construct`, `.parse`, `Instrument`, `OrderAck`, `SimulatedVenueTest.php`, `.encode`, `Carbon\CarbonImmutable`, `ExchangeVerificationTest.php`, `BinanceVenue.php`, `MarketType`, `LedgerTest.php`, `App\Simulation\Data\RunDefinition`, `.assessEntry`, `.measure`, `Money`, `EnginePerpTest.php`, `DslBaselinesTest.php`, `.zero`, `ExitReason`, `SimClock`, `InvalidArgumentException`, `BacktestTest.php`, `RunEngine.php`, `StrategyGates.php`, `OrderRole`, `App\Execution\Venues\SimulatedVenue`, `BinanceRestClientTest.php`, `App\Support\Symbol`, `InstrumentRules`, `RunBacktestCommandTest.php`, `Asset`, `BinanceVenue`, `App\Support\Symbol`, `.run`, `CandleRepositoryTest.php`, `Engine`, `Timeframe`, `Price`, `.reconcile`?**
  _High betweenness centrality (0.044) - this node is a cross-community bridge._
- **Should `EngineTest.php` be split into smaller, more focused modules?**
  _Cohesion score 0.09471153846153846 - nodes in this community are weakly interconnected._
- **Why does `RejectionReason` connect `.of` to `App\Support\Symbol`, `.assessEntry`, `Engine`?**
  _High betweenness centrality (0.023) - this node is a cross-community bridge._
- **Should `App\Support\Clock\Clock` be split into smaller, more focused modules?**
  _Cohesion score 0.06948051948051948 - nodes in this community are weakly interconnected._