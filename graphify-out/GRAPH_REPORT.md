# Graph Report - trading-platform  (2026-11-09)

## Corpus Check
- 518 files · ~213,270 words
- Verdict: corpus is large enough that graph structure adds value.
- Unclassified: 92 file(s) not represented in the graph (top: .zip 34, .CHECKSUM 34, (none) 18)

## Summary
- 4135 nodes · 14224 edges · 172 communities (125 shown, 47 thin omitted)
- Extraction: 93% EXTRACTED · 7% INFERRED · 0% AMBIGUOUS · INFERRED: 1031 edges (avg confidence: 0.85)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `a76e22e3`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- .of
- EngineTest.php
- Clock
- RuntimeException
- SimulatedVenue
- .parse
- QualityCheck
- Position
- CompiledStrategy.php
- SimulatedVenueTest.php
- Instrument
- SimulatedPerpVenueTest.php
- Candle
- ExchangeVerificationTest.php
- IndicatorsTest.php
- GlobalsTest.php
- Node
- PolicyLimits
- App\Strategy\Dsl\Position
- MarketType
- LedgerTest.php
- Illuminate\Database\Schema\Blueprint
- generate.py
- ReadBack
- Symbol
- ChartScreen.php
- InstrumentRules
- Price
- .measure
- AssetMismatch
- CandleStreamTest.php
- StrategyIdentity
- .zero
- .of
- EngineMode
- ExitReason
- PaperEngineTest.php
- BackfillTest.php
- InvalidArgumentException
- .build
- RunEngine.php
- ema-cross-scalp-long.strategy.yaml
- Decimal
- OrderManagerTest.php
- OrderRole
- User
- Illuminate\Database\Eloquent\Model
- package.json
- Venue
- BinanceRestClientTest.php
- ema-cross-scalp-short.strategy.yaml
- OpenLong
- SimClock
- Laravel\Mcp\Response
- postgres
- InstrumentRules
- PreparesBacktests
- donchian-breakout-short.strategy.yaml
- IndicatorReferenceTest.php
- AccountRuntime
- RunBacktestCommandTest.php
- Asset
- BinanceArchive
- Engine.php
- donchian-breakout.strategy.yaml
- .run
- CandleRepositoryTest.php
- App\Strategy\Dsl\Document\DocumentChecker
- .of
- Engine
- ScreensTest.php
- DatabaseCandleFeed
- scripts
- ma-trend-short.strategy.yaml
- DslError
- .walk
- composer.json
- jobs
- postgres
- ma-trend.strategy.yaml
- DecimalMath
- CompiledStrategyTest.php
- App\Support\Number\Decimal
- pint/composer.json
- MonteCarlo
- RunSimulation.php
- Timeframe
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
- RiskState
- psr-4
- require
- DatabaseSeeder
- steps
- steps
- lint
- CandleFeedTest.php
- ClientOrderId
- PerpetualBaselinesTest.php
- StrategiesScreen.php
- RiskEventKind
- buy-and-hold.strategy.yaml
- ChartSeriesTest.php
- TokensTest.php
- StrategyGates.php
- AppServiceProvider.php
- Illuminate\Support\Facades\Schema
- Illuminate\Database\Migrations\Migration
- .count
- funding-carry.strategy.yaml
- McpServerTest.php
- AuthenticateMcpToken.php
- .times
- .__construct
- StrategyLimits
- WallClock
- 0001_01_01_000000_create_users_table.php
- App\Market\Archive\BinanceArchive
- .encode
- Carbon\CarbonImmutable
- RiskProfile
- StrategyLibrary
- BinanceVenue.php
- ReadBack.php
- Percent
- Quantity
- .isGreaterThan
- 0001_01_01_000002_create_jobs_table.php
- DirectionalMovement
- VolumeProfile
- 0001_01_01_000001_create_cache_table.php
- 2026_11_08_000004_create_strategy_versions_tables.php
- 2026_11_08_000006_create_mcp_tables.php
- 2026_11_08_000007_create_strategy_gate_tables.php
- 2026_11_09_000010_add_two_factor_columns_to_users_table.php
- strategies-screen.blade.php
- extra
- app.blade.php
- halt
- remove
- account-screen.blade.php

## God Nodes (most connected - your core abstractions)
1. `Decimal` - 598 edges
2. `Symbol` - 253 edges
3. `Side` - 215 edges
4. `Candle` - 170 edges
5. `Timeframe` - 155 edges
6. `Clock` - 122 edges
7. `Price` - 116 edges
8. `Position` - 96 edges
9. `MarketType` - 86 edges
10. `SimulatedVenue` - 73 edges

## Surprising Connections (you probably didn't know these)
- `{closure#2}()` --references--> `EngineRecord`  [EXTRACTED]
  tests/Unit/Execution/EnginePerpTest.php → app/Execution/Data/EngineRecord.php
- `{closure#1}()` --references--> `EngineRecord`  [EXTRACTED]
  tests/Unit/Execution/EngineTest.php → app/Execution/Data/EngineRecord.php
- `{closure#12}()` --references--> `TrackedOrder`  [EXTRACTED]
  tests/Unit/Execution/OrderManagerTest.php → app/Execution/Data/TrackedOrder.php
- `{closure#4}()` --references--> `Candle`  [EXTRACTED]
  tests/Feature/Market/BinanceRestClientTest.php → app/Market/Data/Candle.php
- `{closure#7}()` --references--> `Candle`  [EXTRACTED]
  tests/Feature/Market/CandleRepositoryTest.php → app/Market/Data/Candle.php

## Import Cycles
- None detected.

## Communities (172 total, 47 thin omitted)

### Community 0 - ".of"
Cohesion: 0.26
Nodes (36): account(), accountPolicy(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#2}(), {closure#21}() (+28 more)

### Community 1 - "EngineTest.php"
Cohesion: 0.07
Nodes (55): EngineCommand, InMemoryDecisionLog, Policies, ScriptedStrategy, {closure#10}(), {closure#11}(), {closure#14}(), {closure#2}() (+47 more)

### Community 2 - "Clock"
Cohesion: 0.06
Nodes (24): CreateMcpToken, RevokeMcpToken, FlattenEngine, HaltEngine, IssuesCommands, PauseStrategy, ResumeEngine, StoreKeys (+16 more)

### Community 3 - "RuntimeException"
Cohesion: 0.12
Nodes (3): PhrityTransport, StreamFailed, FakeTransport

### Community 4 - "SimulatedVenue"
Cohesion: 0.11
Nodes (6): Liquidity, Maker, Taker, SimulatedOrder, SimulatedVenue, {closure#40}()

### Community 5 - ".parse"
Cohesion: 0.08
Nodes (47): Strategy, baselineDocument(), baselinePairs(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#14}() (+39 more)

### Community 7 - "Position"
Cohesion: 0.06
Nodes (25): Argument, BoolLiteral, Call, DurationLiteral, Identifier, Lookback, Node, NumberLiteral (+17 more)

### Community 8 - "CompiledStrategy.php"
Cohesion: 0.09
Nodes (7): {closure#1}(), CompiledStrategy, {closure#33}(), {closure#34}(), {closure#36}(), Compiler, Program

### Community 9 - "SimulatedVenueTest.php"
Cohesion: 0.19
Nodes (42): Side, Buy, Sell, btc(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}() (+34 more)

### Community 10 - "Instrument"
Cohesion: 0.06
Nodes (19): Backfill, {closure#1}(), {closure#2}(), {closure#3}(), BackfillStep, CandleRepository, {closure#1}(), {closure#2}() (+11 more)

### Community 11 - "SimulatedPerpVenueTest.php"
Cohesion: 0.24
Nodes (24): {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#15}(), {closure#18}(), {closure#19}() (+16 more)

### Community 12 - "Candle"
Cohesion: 0.13
Nodes (30): Candle, {closure#9}(), {closure#1}(), {closure#42}(), candleWith(), {closure#1}(), {closure#10}(), {closure#11}() (+22 more)

### Community 13 - "ExchangeVerificationTest.php"
Cohesion: 0.10
Nodes (32): VerificationSource, DailyArchive, Rest, {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#2}() (+24 more)

### Community 14 - "IndicatorsTest.php"
Cohesion: 0.14
Nodes (15): Donchian, bar(), {closure#1}(), {closure#10}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#15}() (+7 more)

### Community 16 - "Node"
Cohesion: 0.11
Nodes (21): Binary, Lexer, Parser, Token, TokenKind, Colon, Comma, Dot (+13 more)

### Community 17 - "PolicyLimits"
Cohesion: 0.07
Nodes (27): InstrumentDirectory, StoredInstruments, {closure#1}(), CompileStrategy, CheckResult, PolicyLimits, SaveResult, StrategyRepository (+19 more)

### Community 18 - "App\Strategy\Dsl\Position"
Cohesion: 0.13
Nodes (10): DocumentChecker, YamlList, YamlMap, YamlReader, YamlScalar, YamlValue, {closure#1}(), {closure#2}() (+2 more)

### Community 19 - "MarketType"
Cohesion: 0.06
Nodes (22): BinanceEndpoints, BinanceRequestFailed, BinanceRestClient, {closure#1}(), LocationRestricted, RateLimited, RequestFailed, SyncInstruments (+14 more)

### Community 20 - "LedgerTest.php"
Cohesion: 0.15
Nodes (31): Ledger, LedgerViolation, balanceOf(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}() (+23 more)

### Community 21 - "Illuminate\Database\Schema\Blueprint"
Cohesion: 0.19
Nodes (10): {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#1}(), {closure#2}(), {closure#3}() (+2 more)

### Community 22 - "generate.py"
Cohesion: 0.08
Nodes (30): atr(), bars(), bb(), change(), dmi(), donchian(), ema(), highest() (+22 more)

### Community 24 - "Symbol"
Cohesion: 0.03
Nodes (24): {closure#12}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#1}(), {closure#1}(), {closure#1}(), BuyAndHold (+16 more)

### Community 25 - "ChartScreen.php"
Cohesion: 0.07
Nodes (25): {closure#2}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), FunctionSpec, Group, Exit (+17 more)

### Community 26 - "InstrumentRules"
Cohesion: 0.09
Nodes (30): InstrumentRules, AccountView, RejectionReason, BelowMinimum, Breaker, Cooldown, DuplicateIntent, EntryWorking (+22 more)

### Community 27 - "Price"
Cohesion: 0.09
Nodes (6): ProtectionPlan, {closure#1}(), {closure#2}(), PerpPosition, Position, Price

### Community 28 - ".measure"
Cohesion: 0.19
Nodes (9): EquityTracker, Performance, {closure#1}(), {closure#2}(), {closure#3}(), {closure#5}(), {closure#6}(), {closure#8}() (+1 more)

### Community 30 - "CandleStreamTest.php"
Cohesion: 0.12
Nodes (11): Sleeper, {closure#1}(), CandleStream, StreamTransport, {closure#1}(), {closure#2}(), {closure#4}(), {closure#5}() (+3 more)

### Community 31 - "StrategyIdentity"
Cohesion: 0.17
Nodes (5): StrategyIdentity, RenamedStrategy, {closure#1}(), {closure#2}(), {closure#3}()

### Community 32 - ".zero"
Cohesion: 0.09
Nodes (22): {closure#7}(), Cscv, DeflatedSharpe, Normal, Vwap, closeTo(), {closure#1}(), {closure#10}() (+14 more)

### Community 33 - ".of"
Cohesion: 0.11
Nodes (25): {closure#41}(), {closure#44}(), {closure#45}(), {closure#46}(), {closure#47}(), {closure#48}(), {closure#24}(), {closure#1}() (+17 more)

### Community 34 - "EngineMode"
Cohesion: 0.14
Nodes (9): {closure#1}(), {closure#2}(), CreateAccount, EngineMode, Backtest, Live, Paper, Testnet (+1 more)

### Community 35 - "ExitReason"
Cohesion: 0.13
Nodes (25): ExitReason, Flatten, Liquidation, Signal, Stop, Target, Fill, {closure#1}() (+17 more)

### Community 36 - "PaperEngineTest.php"
Cohesion: 0.14
Nodes (17): JournalEntry, SendTelegram, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}() (+9 more)

### Community 37 - "BackfillTest.php"
Cohesion: 0.10
Nodes (20): {closure#3}(), {closure#11}(), {closure#13}(), {closure#14}(), {closure#15}(), {closure#16}(), {closure#4}(), {closure#5}() (+12 more)

### Community 38 - "InvalidArgumentException"
Cohesion: 0.06
Nodes (17): FeeSchedule, CostModel, RiskProfile, Account, Benchmark, {closure#1}(), RiskPolicy, {closure#2}() (+9 more)

### Community 39 - ".build"
Cohesion: 0.13
Nodes (9): {closure#1}(), {closure#2}(), BacktestResult, ParticipantResult, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}() (+1 more)

### Community 40 - "RunEngine.php"
Cohesion: 0.10
Nodes (15): {closure#2}(), BinanceVenue, BinanceApiError, BinanceUnreachable, SignedBinanceClient, binanceVenue(), {closure#1}(), {closure#2}() (+7 more)

### Community 41 - "ema-cross-scalp-long.strategy.yaml"
Cohesion: 0.06
Nodes (33): entries, long, exits, stop_loss, take_profit, time_stop, when, fails_when (+25 more)

### Community 42 - "Decimal"
Cohesion: 0.03
Nodes (56): {closure#6}(), {closure#1}(), {closure#2}(), RoundTrip, {closure#1}(), {closure#2}(), {closure#3}(), {closure#10}() (+48 more)

### Community 43 - "OrderManagerTest.php"
Cohesion: 0.33
Nodes (15): btcUsdt(), {closure#10}(), {closure#12}(), {closure#15}(), {closure#16}(), {closure#2}(), {closure#4}(), {closure#5}() (+7 more)

### Community 44 - "OrderRole"
Cohesion: 0.11
Nodes (15): OrderRole, Entry, Exit, Flatten, Stop, Target, TrackedOrder, {closure#1}() (+7 more)

### Community 45 - "User"
Cohesion: 0.08
Nodes (4): ReauthController, User, CreateUser, UserFactory

### Community 46 - "Illuminate\Database\Eloquent\Model"
Cohesion: 0.06
Nodes (14): {closure#1}(), {closure#1}(), McpCall, McpToken, AccountSnapshot, Deployment, {closure#2}(), {closure#1}() (+6 more)

### Community 47 - "package.json"
Cohesion: 0.08
Nodes (31): dependencies, codemirror@^6.0.2, @codemirror/lang-yaml@^6.1.3, lightweight-charts@^5.2.1, devDependencies, concurrently@^10.0.3, laravel-vite-plugin@^3.1, tailwindcss@^4.0.0 (+23 more)

### Community 48 - "Venue"
Cohesion: 0.06
Nodes (12): {closure#2}(), Venue, Balance, WarmingVenue, ForwardingVenue, {closure#12}(), balances(), {closure#22}() (+4 more)

### Community 49 - "BinanceRestClientTest.php"
Cohesion: 0.21
Nodes (11): binanceClient(), {closure#1}(), {closure#11}(), {closure#13}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}() (+3 more)

### Community 50 - "ema-cross-scalp-short.strategy.yaml"
Cohesion: 0.06
Nodes (33): entries, short, exits, stop_loss, take_profit, time_stop, when, fails_when (+25 more)

### Community 51 - "OpenLong"
Cohesion: 0.22
Nodes (4): Close, EntryOrder, OpenLong, OpenShort

### Community 52 - "SimClock"
Cohesion: 0.10
Nodes (19): SimClock, {closure#1}(), {closure#1}(), {closure#1}(), {closure#2}(), {closure#3}(), definition(), {closure#1}() (+11 more)

### Community 53 - "Laravel\Mcp\Response"
Cohesion: 0.09
Nodes (12): ChartTool, GetCandles, GetFunding, GetIndicators, GetJob, ListInstruments, ListTrials, RunBacktest (+4 more)

### Community 54 - "postgres"
Cohesion: 0.10
Nodes (19): POSTGRES_DB, POSTGRES_PASSWORD, POSTGRES_USER, interval, retries, test, timeout, environment (+11 more)

### Community 55 - "InstrumentRules"
Cohesion: 0.17
Nodes (15): {closure#1}(), RulesHistory, {closure#2}(), {closure#4}(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}() (+7 more)

### Community 57 - "donchian-breakout-short.strategy.yaml"
Cohesion: 0.07
Nodes (26): entries, short, default, range, step, default, range, step (+18 more)

### Community 58 - "IndicatorReferenceTest.php"
Cohesion: 0.07
Nodes (35): Atr, BarIndicator, Bollinger, Change, DonchianChannel, Ema, Highest, Hma (+27 more)

### Community 59 - "AccountRuntime"
Cohesion: 0.12
Nodes (6): AccountRuntime, RunEngine, DatabaseDecisionLog, ReplayDivergence, Account, {closure#1}()

### Community 60 - "RunBacktestCommandTest.php"
Cohesion: 0.14
Nodes (27): BacktestRun, {closure#4}(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}() (+19 more)

### Community 61 - "Asset"
Cohesion: 0.22
Nodes (4): Marks, Asset, {closure#25}(), {closure#9}()

### Community 62 - "BinanceArchive"
Cohesion: 0.09
Nodes (14): ArchiveMissing, ArchivePeriod, BinanceArchive, ChecksumMismatch, archiveBytes(), {closure#1}(), {closure#2}(), {closure#3}() (+6 more)

### Community 64 - "Engine.php"
Cohesion: 0.10
Nodes (9): DecisionLog, DecisionRecord, IntentRecord, {closure#1}(), {closure#11}(), {closure#2}(), {closure#3}(), {closure#9}() (+1 more)

### Community 65 - "donchian-breakout.strategy.yaml"
Cohesion: 0.07
Nodes (26): entries, long, default, range, step, default, range, step (+18 more)

### Community 66 - ".run"
Cohesion: 0.09
Nodes (23): RunBacktest, PerformanceMetrics, Report, btcMinutes(), {closure#1}(), {closure#2}(), {closure#3}(), referencePrice() (+15 more)

### Community 67 - "CandleRepositoryTest.php"
Cohesion: 0.10
Nodes (15): candleStore(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}() (+7 more)

### Community 68 - "App\Strategy\Dsl\Document\DocumentChecker"
Cohesion: 0.07
Nodes (35): {closure#1}(), {closure#2}(), {closure#5}(), {closure#6}(), {closure#3}(), {closure#9}(), ParameterSpec, DslWriter (+27 more)

### Community 69 - ".of"
Cohesion: 0.13
Nodes (25): Backtest, InMemoryCandleFeed, {closure#2}(), {closure#1}(), usdt(), backtestSetup(), {closure#1}(), {closure#11}() (+17 more)

### Community 70 - "Engine"
Cohesion: 0.12
Nodes (4): {closure#4}(), EngineRecord, Engine, ReconciliationMismatch

### Community 71 - "ScreensTest.php"
Cohesion: 0.12
Nodes (15): ExchangeKey, {closure#1}(), {closure#2}(), {closure#3}(), fakeTestnet(), testnetAccount(), {closure#1}(), {closure#2}() (+7 more)

### Community 72 - "DatabaseCandleFeed"
Cohesion: 0.26
Nodes (5): DatabaseCandleFeed, {closure#1}(), Rows, {closure#10}(), {closure#11}()

### Community 73 - "scripts"
Cohesion: 0.15
Nodes (13): scripts, analyse, dev, format, mutate, post-autoload-dump, post-update-cmd, pre-package-uninstall (+5 more)

### Community 74 - "ma-trend-short.strategy.yaml"
Cohesion: 0.07
Nodes (26): entries, short, exits, stop_loss, when, fails_when, family, default (+18 more)

### Community 75 - "DslError"
Cohesion: 0.16
Nodes (12): {closure#1}(), AstPrinter, {closure#1}(), {closure#4}(), {closure#5}(), {closure#8}(), DslError, {closure#1}() (+4 more)

### Community 76 - ".walk"
Cohesion: 0.17
Nodes (18): CandleAggregator, CandleSequenceBroken, {closure#3}(), {closure#6}(), aggregate(), {closure#1}(), {closure#10}(), {closure#11}() (+10 more)

### Community 77 - "composer.json"
Cohesion: 0.17
Nodes (11): autoload-dev, psr-4, description, keywords, license, minimum-stability, name, prefer-stable (+3 more)

### Community 78 - "jobs"
Cohesion: 0.17
Nodes (11): concurrency, cancel-in-progress, group, jobs, name, on, pull_request, push (+3 more)

### Community 79 - "postgres"
Cohesion: 0.17
Nodes (12): POSTGRES_DB, POSTGRES_PASSWORD, POSTGRES_USER, env, image, options, ports, image (+4 more)

### Community 80 - "ma-trend.strategy.yaml"
Cohesion: 0.07
Nodes (26): entries, long, exits, stop_loss, when, fails_when, family, default (+18 more)

### Community 81 - "DecimalMath"
Cohesion: 0.13
Nodes (14): DecimalMath, {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#2}() (+6 more)

### Community 82 - "CompiledStrategyTest.php"
Cohesion: 0.17
Nodes (14): aggregatedBars(), {closure#1}(), {closure#11}(), {closure#13}(), {closure#16}(), {closure#17}(), {closure#2}(), {closure#3}() (+6 more)

### Community 83 - "App\Support\Number\Decimal"
Cohesion: 0.08
Nodes (40): {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#15}(), {closure#16}() (+32 more)

### Community 84 - "pint/composer.json"
Cohesion: 0.20
Nodes (9): config, sort-packages, description, license, name, require, laravel/pint, php (+1 more)

### Community 85 - "MonteCarlo"
Cohesion: 0.30
Nodes (9): MonteCarlo, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), mcDecimals() (+1 more)

### Community 86 - "RunSimulation.php"
Cohesion: 0.09
Nodes (6): RunSimulation, SimulationJob, {closure#1}(), {closure#2}(), {closure#3}(), savedEmaCross()

### Community 87 - "Timeframe"
Cohesion: 0.09
Nodes (19): {closure#2}(), {closure#3}(), {closure#4}(), CarryTemplate, {closure#2}(), {closure#8}(), Timeframe, FifteenMinutes (+11 more)

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
Cohesion: 0.12
Nodes (6): FundingRateRepository, Heartbeats, GridBacktest, ReadsGridOptions, StrategyGates, StrategySpec

### Community 97 - "run"
Cohesion: 0.33
Nodes (6): name, runs-on, steps, audit, run, steps

### Community 98 - "RiskState"
Cohesion: 0.35
Nodes (7): RiskState, {closure#13}(), {closure#15}(), {closure#17}(), {closure#18}(), {closure#22}(), {closure#23}()

### Community 99 - "psr-4"
Cohesion: 0.40
Nodes (5): autoload, psr-4, App\\, Database\\Factories\\, Database\\Seeders\\

### Community 100 - "require"
Cohesion: 0.25
Nodes (8): require, laravel/fortify, laravel/framework, laravel/mcp, laravel/tinker, livewire/livewire, php, phrity/websocket

### Community 103 - "steps"
Cohesion: 0.50
Nodes (4): name, runs-on, steps, analyse

### Community 104 - "steps"
Cohesion: 0.50
Nodes (4): name, runs-on, steps, frontend

### Community 105 - "lint"
Cohesion: 0.50
Nodes (4): lint, name, runs-on, steps

### Community 106 - "CandleFeedTest.php"
Cohesion: 0.44
Nodes (11): {closure#1}(), {closure#12}(), {closure#13}(), {closure#2}(), {closure#4}(), {closure#5}(), {closure#8}(), {closure#9}() (+3 more)

### Community 108 - "ClientOrderId"
Cohesion: 0.06
Nodes (25): ClientOrderId, OcoRequest, OrderAck, OrderRequest, OrderStatus, Cancelled, Expired, Filled (+17 more)

### Community 117 - "PerpetualBaselinesTest.php"
Cohesion: 0.36
Nodes (7): {closure#1}(), {closure#3}(), goldenMinutes(), goldenRates(), perpetualReferencePrice(), referenceMean(), reportTrades()

### Community 118 - "StrategiesScreen.php"
Cohesion: 0.11
Nodes (7): {closure#5}(), EngineCommandRow, AccountScreen, ChartScreen, HaltButton, ModeBadge, {closure#1}()

### Community 119 - "RiskEventKind"
Cohesion: 0.24
Nodes (6): RiskEvent, RiskEventKind, DailyLossHalt, DrawdownFlatten, DrawdownHalt, WeeklyLossHalt

### Community 120 - "buy-and-hold.strategy.yaml"
Cohesion: 0.12
Nodes (16): entries, long, exits, stop_loss, fails_when, family, hypothesis, when (+8 more)

### Community 121 - "ChartSeriesTest.php"
Cohesion: 0.23
Nodes (11): chart(), {closure#10}(), {closure#11}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}() (+3 more)

### Community 122 - "TokensTest.php"
Cohesion: 0.31
Nodes (6): {closure#3}(), {closure#4}(), contrastRatio(), contrastRules(), relativeLuminance(), themeTokens()

### Community 123 - "StrategyGates.php"
Cohesion: 0.07
Nodes (22): {closure#2}(), {closure#3}(), {closure#4}(), {closure#6}(), {closure#8}(), {closure#2}(), GridPart, {closure#1}() (+14 more)

### Community 124 - "AppServiceProvider.php"
Cohesion: 0.07
Nodes (14): Notifier, SystemSleeper, TelegramNotifier, AppServiceProvider, {closure#3}(), {closure#5}(), {closure#6}(), {closure#3}() (+6 more)

### Community 127 - ".count"
Cohesion: 0.15
Nodes (12): Statistics, {closure#1}(), {closure#2}(), {closure#3}(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}() (+4 more)

### Community 128 - "funding-carry.strategy.yaml"
Cohesion: 0.12
Nodes (15): fails_when, family, hypothesis, params, enter_when, exit_when, margin_buffer, max_leverage_perp (+7 more)

### Community 129 - "McpServerTest.php"
Cohesion: 0.12
Nodes (16): DslReference, GetStrategy, ListStrategies, SaveStrategy, TradingServer, actAsToken(), {closure#2}(), {closure#3}() (+8 more)

### Community 131 - ".times"
Cohesion: 0.11
Nodes (6): SimulatedFuturesBook, {closure#3}(), Perpetual, {closure#17}(), {closure#5}(), {closure#5}()

### Community 137 - "0001_01_01_000000_create_users_table.php"
Cohesion: 0.33
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

### Community 138 - "App\Market\Archive\BinanceArchive"
Cohesion: 0.09
Nodes (16): {closure#1}(), {closure#2}(), EpochTime, ExchangeNumber, Json, KlineMessage, {closure#1}(), {closure#2}() (+8 more)

### Community 139 - ".encode"
Cohesion: 0.30
Nodes (6): CanonicalJson, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}()

### Community 140 - "Carbon\CarbonImmutable"
Cohesion: 0.08
Nodes (20): {closure#4}(), CandleFeed, FundingRate, MinuteCandles, {closure#1}(), {closure#2}(), BreakerEvent, MarketBreakers (+12 more)

### Community 141 - "RiskProfile"
Cohesion: 0.50
Nodes (4): {closure#1}(), {closure#2}(), {closure#3}(), documentedLimits()

### Community 142 - "StrategyLibrary"
Cohesion: 0.29
Nodes (3): CompileStrategy, StrategiesScreen, StrategyLibrary

### Community 143 - "BinanceVenue.php"
Cohesion: 0.14
Nodes (13): Income, IncomeKind, Funding, Liquidation, OrderUpdate, VenueFill, {closure#1}(), UpdateStream (+5 more)

### Community 144 - "ReadBack.php"
Cohesion: 0.13
Nodes (9): {closure#7}(), EntrySpec, ExitSpec, Expression, {closure#1}(), {closure#2}(), {closure#5}(), {closure#6}() (+1 more)

### Community 145 - "Percent"
Cohesion: 0.24
Nodes (3): Percent, {closure#14}(), {closure#15}()

### Community 146 - "Quantity"
Cohesion: 0.24
Nodes (3): Quantity, {closure#10}(), {closure#9}()

### Community 148 - "0001_01_01_000002_create_jobs_table.php"
Cohesion: 0.33
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

### Community 157 - "strategies-screen.blade.php"
Cohesion: 0.40
Nodes (4): open, promote, run, save

### Community 159 - "extra"
Cohesion: 0.67
Nodes (3): extra, laravel, dont-discover

## Knowledge Gaps
- **133 isolated node(s):** `Halt`, `Resume`, `Flatten`, `Pause`, `ResumeStrategy` (+128 more)
  These have ≤1 connection - possible missing edges. (Counts symbols only; 841 node(s) total have ≤1 connection when file, concept and rationale nodes are included.)
- **47 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `Decimal` connect `Decimal` to `.of`, `EngineTest.php`, `SimulatedVenue`, `.parse`, `QualityCheck`, `Position`, `CompiledStrategy.php`, `SimulatedVenueTest.php`, `Instrument`, `SimulatedPerpVenueTest.php`, `Candle`, `IndicatorsTest.php`, `PolicyLimits`, `App\Strategy\Dsl\Position`, `MarketType`, `LedgerTest.php`, `ReadBack`, `Symbol`, `ChartScreen.php`, `InstrumentRules`, `Price`, `.measure`, `.zero`, `.of`, `EngineMode`, `ExitReason`, `InvalidArgumentException`, `.build`, `RunEngine.php`, `OrderManagerTest.php`, `OrderRole`, `Illuminate\Database\Eloquent\Model`, `Venue`, `OpenLong`, `SimClock`, `InstrumentRules`, `PreparesBacktests`, `IndicatorReferenceTest.php`, `RunBacktestCommandTest.php`, `Asset`, `.isFull`, `Engine.php`, `.run`, `CandleRepositoryTest.php`, `App\Strategy\Dsl\Document\DocumentChecker`, `.of`, `Engine`, `DatabaseCandleFeed`, `DslError`, `.walk`, `DecimalMath`, `CompiledStrategyTest.php`, `App\Support\Number\Decimal`, `MonteCarlo`, `Timeframe`, `.evaluate`, `.fromStrings`, `.handle`, `RiskState`, `CandleFeedTest.php`, `ClientOrderId`, `PerpetualBaselinesTest.php`, `RiskEventKind`, `ChartSeriesTest.php`, `StrategyGates.php`, `.count`, `.times`, `.__construct`, `StrategyLimits`, `App\Market\Archive\BinanceArchive`, `.encode`, `Carbon\CarbonImmutable`, `RiskProfile`, `BinanceVenue.php`, `ReadBack.php`, `Percent`, `Quantity`, `.isGreaterThan`, `DirectionalMovement`, `VolumeProfile`?**
  _High betweenness centrality (0.216) - this node is a cross-community bridge._
- **What connects `Halt`, `Resume`, `Flatten` to the rest of the system?**
  _133 weakly-connected nodes found - possible missing edges._
- **Should `EngineTest.php` be split into smaller, more focused modules?**
  _Cohesion score 0.067926455566905 - nodes in this community are weakly interconnected._
- **Why does `Symbol` connect `Symbol` to `.of`, `McpServerTest.php`, `Clock`, `.times`, `SimulatedVenue`, `.parse`, `EngineTest.php`, `CompiledStrategy.php`, `SimulatedVenueTest.php`, `Instrument`, `SimulatedPerpVenueTest.php`, `Carbon\CarbonImmutable`, `ExchangeVerificationTest.php`, `.encode`, `BinanceVenue.php`, `ReadBack.php`, `PolicyLimits`, `App\Strategy\Dsl\Position`, `MarketType`, `LedgerTest.php`, `ChartScreen.php`, `Price`, `.measure`, `CandleStreamTest.php`, `EngineMode`, `ExitReason`, `BackfillTest.php`, `InvalidArgumentException`, `.build`, `RunEngine.php`, `Decimal`, `OrderManagerTest.php`, `OrderRole`, `Illuminate\Database\Eloquent\Model`, `Venue`, `OpenLong`, `SimClock`, `Laravel\Mcp\Response`, `InstrumentRules`, `PreparesBacktests`, `RunBacktestCommandTest.php`, `Asset`, `Engine.php`, `.run`, `CandleRepositoryTest.php`, `App\Strategy\Dsl\Document\DocumentChecker`, `.of`, `Engine`, `DslError`, `CompiledStrategyTest.php`, `Timeframe`, `.handle`, `CandleFeedTest.php`, `ClientOrderId`, `PerpetualBaselinesTest.php`, `ChartSeriesTest.php`, `StrategyGates.php`?**
  _High betweenness centrality (0.057) - this node is a cross-community bridge._
- **Should `Clock` be split into smaller, more focused modules?**
  _Cohesion score 0.05821917808219178 - nodes in this community are weakly interconnected._
- **Why does `Timeframe` connect `Timeframe` to `McpServerTest.php`, `EngineTest.php`, `.__construct`, `.parse`, `Position`, `CompiledStrategy.php`, `SimulatedVenueTest.php`, `App\Market\Archive\BinanceArchive`, `Instrument`, `Candle`, `Carbon\CarbonImmutable`, `SimulatedPerpVenueTest.php`, `IndicatorsTest.php`, `ReadBack.php`, `PolicyLimits`, `App\Strategy\Dsl\Position`, `MarketType`, `ReadBack`, `Symbol`, `ChartScreen.php`, `Price`, `CandleStreamTest.php`, `.zero`, `ExitReason`, `InvalidArgumentException`, `Decimal`, `OrderManagerTest.php`, `OpenLong`, `Laravel\Mcp\Response`, `IndicatorReferenceTest.php`, `BinanceArchive`, `CandleRepositoryTest.php`, `App\Strategy\Dsl\Document\DocumentChecker`, `DslError`, `.walk`, `DecimalMath`, `CompiledStrategyTest.php`, `App\Support\Number\Decimal`, `.fromStrings`, `.handle`, `CandleFeedTest.php`, `ChartSeriesTest.php`, `StrategyGates.php`, `.count`?**
  _High betweenness centrality (0.049) - this node is a cross-community bridge._
- **Should `RuntimeException` be split into smaller, more focused modules?**
  _Cohesion score 0.11578947368421053 - nodes in this community are weakly interconnected._