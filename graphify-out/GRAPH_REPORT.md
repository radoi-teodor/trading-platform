# Graph Report - trading-platform  (2026-11-09)

## Corpus Check
- 527 files · ~216,391 words
- Verdict: corpus is large enough that graph structure adds value.
- Unclassified: 92 file(s) not represented in the graph (top: .zip 34, .CHECKSUM 34, (none) 18)

## Summary
- 4219 nodes · 14449 edges · 190 communities (145 shown, 45 thin omitted)
- Extraction: 93% EXTRACTED · 7% INFERRED · 0% AMBIGUOUS · INFERRED: 1068 edges (avg confidence: 0.85)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `1b3a4430`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- .of
- EngineTest.php
- Clock
- BinanceArchive
- InstrumentRules
- .parse
- DocumentChecker
- ChartScreen.php
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
- Position
- MarketType
- LedgerTest.php
- Illuminate\Database\Schema\Blueprint
- generate.py
- ReadBack
- DonchianBreakout
- AppServiceProvider.php
- FundingCarry
- Symbol
- .measure
- Strategy
- CandleStreamTest.php
- StrategyIdentity
- .zero
- OpenAccountTest.php
- InMemoryDecisionLog
- ExitReason
- PaperEngineTest.php
- BackfillTest.php
- RiskPolicy
- BacktestTest.php
- RejectionReason
- ema-cross-scalp-long.strategy.yaml
- Decimal
- .assertSameAsset
- OrderRole
- User
- Illuminate\Database\Eloquent\Model
- package.json
- SimulatedVenue
- BinanceRestClientTest.php
- ema-cross-scalp-short.strategy.yaml
- EnginePerpTest.php
- SimClock
- Laravel\Mcp\Response
- postgres
- InstrumentRules
- Illuminate\Console\Command
- donchian-breakout-short.strategy.yaml
- IndicatorReferenceTest.php
- RunEngine.php
- RunBacktestCommandTest.php
- TelegramTest.php
- Universe
- .__construct
- donchian-breakout.strategy.yaml
- MarketType
- CandleRepositoryTest.php
- App\Strategy\Dsl\Document\DocumentChecker
- .of
- Carbon\CarbonImmutable
- ScreensTest.php
- ArchiveImportTest.php
- scripts
- ma-trend-short.strategy.yaml
- DslError
- .walk
- composer.json
- jobs
- postgres
- ma-trend.strategy.yaml
- DecimalMath
- FundingRate
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
- Illuminate\Contracts\Config\Repository
- run
- Engine.php
- psr-4
- require
- DatabaseSeeder
- steps
- steps
- lint
- CandleFeedTest.php
- ClientOrderId
- StrategyLibrary
- StrategiesScreen.php
- RiskState
- buy-and-hold.strategy.yaml
- ChartSeriesTest.php
- TokensTest.php
- StrategyGates.php
- AppServiceProvider.php
- Illuminate\Support\Facades\Schema
- Illuminate\Database\Migrations\Migration
- .sqrt
- funding-carry.strategy.yaml
- McpServerTest.php
- Illuminate\Http\Client\Request
- .zero
- TelegramSettings
- PreparesBacktests.php
- ReadBackTest.php
- 0001_01_01_000000_create_users_table.php
- TradingServer.php
- .encode
- MarketBreakers
- BinanceArchive.php
- StrategySpec
- Account
- DatabaseCandleFeed
- .times
- InvalidArgumentException
- WalkForward.php
- 0001_01_01_000002_create_jobs_table.php
- Agents/TokensTest.php
- OpenLong
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
- ExchangeVerificationTest.php
- QualityCheck
- .assessEntry
- .evaluate
- GoldenReportsTest.php
- .benchmark
- SimulationJobsTest.php
- DocumentCheckerTest.php
- .handle
- DirectionalMovement
- TelegramNotifier
- Illuminate\Foundation\Testing\RefreshDatabase
- MinuteCandles
- VolumeProfile
- .account
- EngineMode
- 2026_11_09_000011_create_notification_settings_table.php

## God Nodes (most connected - your core abstractions)
1. `Decimal` - 597 edges
2. `Symbol` - 252 edges
3. `Side` - 215 edges
4. `Candle` - 170 edges
5. `Timeframe` - 155 edges
6. `Price` - 116 edges
7. `Clock` - 114 edges
8. `Position` - 96 edges
9. `MarketType` - 86 edges
10. `SimulatedVenue` - 73 edges

## Surprising Connections (you probably didn't know these)
- `{closure#2}()` --calls--> `App`  [INFERRED]
  tests/Feature/Agents/TokensTest.php → app/Notifications/SettingSource.php
- `{closure#3}()` --calls--> `App`  [INFERRED]
  tests/Feature/Agents/TokensTest.php → app/Notifications/SettingSource.php
- `{closure#2}()` --calls--> `App`  [INFERRED]
  tests/Feature/Execution/OpenAccountTest.php → app/Notifications/SettingSource.php
- `{closure#4}()` --calls--> `App`  [INFERRED]
  tests/Feature/Execution/OpenAccountTest.php → app/Notifications/SettingSource.php
- `{closure#5}()` --calls--> `App`  [INFERRED]
  tests/Feature/Execution/OpenAccountTest.php → app/Notifications/SettingSource.php

## Import Cycles
- None detected.

## Communities (190 total, 45 thin omitted)

### Community 0 - ".of"
Cohesion: 0.27
Nodes (35): account(), accountPolicy(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#2}(), {closure#21}() (+27 more)

### Community 1 - "EngineTest.php"
Cohesion: 0.13
Nodes (35): ScriptedStrategy, {closure#1}(), {closure#10}(), {closure#13}(), {closure#14}(), {closure#16}(), {closure#17}(), {closure#2}() (+27 more)

### Community 2 - "Clock"
Cohesion: 0.08
Nodes (18): FlattenEngine, HaltEngine, IssuesCommands, PauseStrategy, ResumeEngine, CommandKind, Flatten, Halt (+10 more)

### Community 3 - "BinanceArchive"
Cohesion: 0.06
Nodes (15): ReplayDivergence, ArchiveMissing, ArchivePeriod, BinanceArchive, ChecksumMismatch, {closure#1}(), {closure#2}(), {closure#3}() (+7 more)

### Community 4 - "InstrumentRules"
Cohesion: 0.08
Nodes (16): Liquidity, Maker, Taker, OrderStatus, Cancelled, Expired, Filled, New (+8 more)

### Community 5 - ".parse"
Cohesion: 0.10
Nodes (42): baselinePairs(), {closure#17}(), {closure#7}(), {closure#9}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}() (+34 more)

### Community 7 - "ChartScreen.php"
Cohesion: 0.06
Nodes (27): {closure#2}(), ChartScreen, {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), FunctionSpec, Group (+19 more)

### Community 8 - "CompiledStrategy.php"
Cohesion: 0.15
Nodes (3): {closure#33}(), Compiler, Program

### Community 9 - "SimulatedVenueTest.php"
Cohesion: 0.10
Nodes (71): Side, Buy, Sell, btc(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}() (+63 more)

### Community 10 - "Instrument"
Cohesion: 0.07
Nodes (15): Backfill, BackfillStep, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), CandleSource, Archive (+7 more)

### Community 11 - "SimulatedPerpVenueTest.php"
Cohesion: 0.21
Nodes (24): {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#15}(), {closure#18}(), {closure#19}() (+16 more)

### Community 12 - "Candle"
Cohesion: 0.11
Nodes (30): Candle, InvalidCandle, {closure#9}(), {closure#1}(), {closure#42}(), candleWith(), {closure#1}(), {closure#10}() (+22 more)

### Community 13 - "ExchangeVerificationTest.php"
Cohesion: 0.19
Nodes (16): {closure#2}(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#2}(), {closure#4}() (+8 more)

### Community 14 - "IndicatorsTest.php"
Cohesion: 0.09
Nodes (22): Atr, Donchian, Ema, Sma, {closure#5}(), bar(), {closure#1}(), {closure#10}() (+14 more)

### Community 16 - "Node"
Cohesion: 0.11
Nodes (29): Binary, Node, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}() (+21 more)

### Community 17 - "PolicyLimits"
Cohesion: 0.15
Nodes (9): InstrumentDirectory, PolicyLimits, StrategyRepository, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), versions() (+1 more)

### Community 18 - "Position"
Cohesion: 0.05
Nodes (22): Argument, BoolLiteral, DurationLiteral, Identifier, Lookback, NumberLiteral, OnTimeframe, Parameter (+14 more)

### Community 19 - "MarketType"
Cohesion: 0.09
Nodes (12): BinanceRestClient, {closure#1}(), Json, RequestFailed, SyncInstruments, InstrumentDefinition, InstrumentStatus, Delisted (+4 more)

### Community 20 - "LedgerTest.php"
Cohesion: 0.16
Nodes (31): Ledger, LedgerViolation, balanceOf(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}() (+23 more)

### Community 21 - "Illuminate\Database\Schema\Blueprint"
Cohesion: 0.19
Nodes (10): {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#1}(), {closure#2}(), {closure#3}() (+2 more)

### Community 22 - "generate.py"
Cohesion: 0.08
Nodes (30): atr(), bars(), bb(), change(), dmi(), donchian(), ema(), highest() (+22 more)

### Community 23 - "ReadBack"
Cohesion: 0.10
Nodes (10): {closure#6}(), {closure#7}(), EntrySpec, Expression, {closure#1}(), {closure#2}(), {closure#5}(), {closure#6}() (+2 more)

### Community 24 - "DonchianBreakout"
Cohesion: 0.06
Nodes (8): BuyAndHold, DonchianBreakout, MovingAverageTrend, BuildableStrategy, Subscription, EnterOnceStrategy, HoldStrategy, {closure#1}()

### Community 25 - "AppServiceProvider.php"
Cohesion: 0.10
Nodes (14): BinanceEndpoints, Sleeper, SystemSleeper, {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#1}() (+6 more)

### Community 27 - "Symbol"
Cohesion: 0.05
Nodes (26): Income, IncomeKind, Funding, Liquidation, VenueOrder, VenuePosition, {closure#12}(), {closure#2}() (+18 more)

### Community 28 - ".measure"
Cohesion: 0.16
Nodes (8): EquityTracker, Performance, {closure#1}(), {closure#2}(), {closure#5}(), {closure#6}(), {closure#8}(), trackedTwoDays()

### Community 29 - "Strategy"
Cohesion: 0.15
Nodes (19): Participant, Strategy, baselineDocument(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#14}(), {closure#15}() (+11 more)

### Community 30 - "CandleStreamTest.php"
Cohesion: 0.07
Nodes (10): KlineMessage, PhrityTransport, StreamFailed, StreamTransport, FakeTransport, {closure#1}(), {closure#2}(), {closure#3}() (+2 more)

### Community 31 - "StrategyIdentity"
Cohesion: 0.17
Nodes (5): StrategyIdentity, RenamedStrategy, {closure#1}(), {closure#2}(), {closure#3}()

### Community 32 - ".zero"
Cohesion: 0.09
Nodes (20): Cscv, DeflatedSharpe, Normal, closeTo(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}() (+12 more)

### Community 33 - "OpenAccountTest.php"
Cohesion: 0.10
Nodes (8): OpenAccount, CreateAccount, StoreKeys, {closure#1}(), {closure#2}(), {closure#4}(), {closure#5}(), {closure#6}()

### Community 34 - "InMemoryDecisionLog"
Cohesion: 0.16
Nodes (9): InMemoryDecisionLog, {closure#1}(), {closure#2}(), BacktestResult, ParticipantResult, {closure#1}(), {closure#2}(), {closure#3}() (+1 more)

### Community 35 - "ExitReason"
Cohesion: 0.22
Nodes (22): ExitReason, Flatten, Liquidation, Signal, Stop, Target, {closure#1}(), {closure#10}() (+14 more)

### Community 36 - "PaperEngineTest.php"
Cohesion: 0.24
Nodes (11): JournalEntry, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}() (+3 more)

### Community 37 - "BackfillTest.php"
Cohesion: 0.14
Nodes (14): {closure#11}(), {closure#14}(), {closure#15}(), {closure#16}(), {closure#4}(), {closure#6}(), {closure#7}(), {closure#8}() (+6 more)

### Community 38 - "RiskPolicy"
Cohesion: 0.15
Nodes (9): RiskProfile, Account, Benchmark, {closure#1}(), RiskPolicy, {closure#1}(), {closure#2}(), {closure#3}() (+1 more)

### Community 39 - "BacktestTest.php"
Cohesion: 0.13
Nodes (13): CandleFeed, Backtest, RunDefinition, ReportBuilder, InMemoryCandleFeed, backtestSetup(), {closure#1}(), {closure#11}() (+5 more)

### Community 40 - "RejectionReason"
Cohesion: 0.07
Nodes (26): RejectionReason, BelowMinimum, Breaker, Cooldown, DuplicateIntent, EntryWorking, GrossExposure, Halted (+18 more)

### Community 41 - "ema-cross-scalp-long.strategy.yaml"
Cohesion: 0.06
Nodes (33): entries, long, exits, stop_loss, take_profit, time_stop, when, fails_when (+25 more)

### Community 42 - "Decimal"
Cohesion: 0.05
Nodes (40): {closure#6}(), RoundTrip, {closure#1}(), {closure#2}(), {closure#3}(), {closure#10}(), {closure#5}(), {closure#4}() (+32 more)

### Community 44 - "OrderRole"
Cohesion: 0.09
Nodes (32): OrderRole, Entry, Exit, Flatten, Stop, Target, ProtectionPlan, TrackedOrder (+24 more)

### Community 45 - "User"
Cohesion: 0.11
Nodes (3): User, CreateUser, UserFactory

### Community 46 - "Illuminate\Database\Eloquent\Model"
Cohesion: 0.07
Nodes (11): {closure#1}(), McpCall, AccountSnapshot, Deployment, ExchangeKey, {closure#1}(), InstrumentFilter, StrategyGateReport (+3 more)

### Community 47 - "package.json"
Cohesion: 0.07
Nodes (37): dependencies, codemirror@^6.0.2, @codemirror/lang-yaml@^6.1.3, @codemirror/language@^6.13.1, @lezer/highlight@^1.2.5, lightweight-charts@^5.2.1, devDependencies, concurrently@^10.0.3 (+29 more)

### Community 48 - "SimulatedVenue"
Cohesion: 0.06
Nodes (15): {closure#2}(), Venue, Balance, FeeSchedule, CostModel, SimulatedVenue, ForwardingVenue, {closure#12}() (+7 more)

### Community 49 - "BinanceRestClientTest.php"
Cohesion: 0.21
Nodes (11): binanceClient(), {closure#1}(), {closure#11}(), {closure#13}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}() (+3 more)

### Community 50 - "ema-cross-scalp-short.strategy.yaml"
Cohesion: 0.06
Nodes (33): entries, short, exits, stop_loss, take_profit, time_stop, when, fails_when (+25 more)

### Community 51 - "EnginePerpTest.php"
Cohesion: 0.28
Nodes (14): {closure#10}(), {closure#11}(), {closure#14}(), {closure#2}(), {closure#5}(), {closure#6}(), {closure#7}(), {closure#8}() (+6 more)

### Community 52 - "SimClock"
Cohesion: 0.13
Nodes (14): SimClock, WallClock, {closure#1}(), {closure#1}(), {closure#2}(), {closure#3}(), definition(), {closure#23}() (+6 more)

### Community 53 - "Laravel\Mcp\Response"
Cohesion: 0.08
Nodes (14): ChartTool, CompileStrategy, GetCandles, GetFunding, GetIndicators, GetJob, GetStrategy, ListTrials (+6 more)

### Community 54 - "postgres"
Cohesion: 0.10
Nodes (19): POSTGRES_DB, POSTGRES_PASSWORD, POSTGRES_USER, interval, retries, test, timeout, environment (+11 more)

### Community 55 - "InstrumentRules"
Cohesion: 0.19
Nodes (11): {closure#1}(), {closure#1}(), RulesHistory, {closure#4}(), {closure#41}(), {closure#1}(), {closure#2}(), {closure#3}() (+3 more)

### Community 56 - "Illuminate\Console\Command"
Cohesion: 0.12
Nodes (9): RevokeMcpToken, CheckMarketData, StoredInstruments, {closure#1}(), CompileStrategy, ExportStrategies, GridPart, {closure#2}() (+1 more)

### Community 57 - "donchian-breakout-short.strategy.yaml"
Cohesion: 0.07
Nodes (26): entries, short, default, range, step, default, range, step (+18 more)

### Community 58 - "IndicatorReferenceTest.php"
Cohesion: 0.04
Nodes (35): {closure#34}(), {closure#36}(), BarIndicator, Bollinger, Change, DonchianChannel, Highest, Hma (+27 more)

### Community 59 - "RunEngine.php"
Cohesion: 0.09
Nodes (8): {closure#4}(), {closure#5}(), RunEngine, Notifier, EngineCommand, EngineCommandRow, BinanceApiError, SignedBinanceClient

### Community 60 - "RunBacktestCommandTest.php"
Cohesion: 0.18
Nodes (23): BacktestRun, {closure#4}(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}() (+15 more)

### Community 61 - "TelegramTest.php"
Cohesion: 0.14
Nodes (21): SendTelegram, SettingSource, App, Env, None, {closure#1}(), {closure#10}(), {closure#11}() (+13 more)

### Community 62 - "Universe"
Cohesion: 0.14
Nodes (7): BackfillReport, BackfillMarketData, SelectsInstruments, {closure#1}(), StreamCandles, VerifyMarketData, Universe

### Community 64 - ".__construct"
Cohesion: 0.13
Nodes (3): AccountRuntime, DatabaseDecisionLog, WarmingVenue

### Community 65 - "donchian-breakout.strategy.yaml"
Cohesion: 0.07
Nodes (26): entries, long, default, range, step, default, range, step (+18 more)

### Community 66 - "MarketType"
Cohesion: 0.21
Nodes (11): MarketType, Perp, Spot, {closure#1}(), {closure#3}(), goldenMinutes(), goldenRates(), perpetualReferencePrice() (+3 more)

### Community 67 - "CandleRepositoryTest.php"
Cohesion: 0.28
Nodes (11): candleStore(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}() (+3 more)

### Community 68 - "App\Strategy\Dsl\Document\DocumentChecker"
Cohesion: 0.06
Nodes (35): Call, {closure#1}(), {closure#2}(), {closure#5}(), {closure#3}(), {closure#9}(), ParameterSpec, DslWriter (+27 more)

### Community 69 - ".of"
Cohesion: 0.09
Nodes (22): Marks, Asset, {closure#2}(), {closure#1}(), {closure#25}(), {closure#9}(), usdt(), {closure#11}() (+14 more)

### Community 70 - "Carbon\CarbonImmutable"
Cohesion: 0.10
Nodes (8): DecisionLog, DecisionRecord, EngineRecord, IntentRecord, Engine, {closure#1}(), ReconciliationMismatch, Verdict

### Community 71 - "ScreensTest.php"
Cohesion: 0.20
Nodes (9): {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), operator(), web() (+1 more)

### Community 72 - "ArchiveImportTest.php"
Cohesion: 0.29
Nodes (10): archiveBytes(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}() (+2 more)

### Community 73 - "scripts"
Cohesion: 0.15
Nodes (13): scripts, analyse, dev, format, mutate, post-autoload-dump, post-update-cmd, pre-package-uninstall (+5 more)

### Community 74 - "ma-trend-short.strategy.yaml"
Cohesion: 0.07
Nodes (26): entries, short, exits, stop_loss, when, fails_when, family, default (+18 more)

### Community 75 - "DslError"
Cohesion: 0.09
Nodes (16): {closure#1}(), AstPrinter, {closure#1}(), {closure#4}(), {closure#5}(), {closure#8}(), DslError, Lexer (+8 more)

### Community 76 - ".walk"
Cohesion: 0.16
Nodes (19): CandleAggregator, CandleSequenceBroken, {closure#3}(), {closure#6}(), CandleFactory, aggregate(), {closure#1}(), {closure#10}() (+11 more)

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
Cohesion: 0.16
Nodes (14): DecimalMath, {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#2}() (+6 more)

### Community 82 - "FundingRate"
Cohesion: 0.09
Nodes (23): FundingRate, Intent, PositionView, StrategyContext, {closure#1}(), {closure#2}(), {closure#18}(), {closure#37}() (+15 more)

### Community 83 - "App\Support\Number\Decimal"
Cohesion: 0.07
Nodes (40): {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#15}(), {closure#16}() (+32 more)

### Community 84 - "pint/composer.json"
Cohesion: 0.20
Nodes (9): config, sort-packages, description, license, name, require, laravel/pint, php (+1 more)

### Community 85 - "MonteCarlo"
Cohesion: 0.23
Nodes (9): MonteCarlo, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), mcDecimals() (+1 more)

### Community 86 - "RunSimulation.php"
Cohesion: 0.13
Nodes (3): RunSimulation, SimulationJob, SimulationQueue

### Community 87 - "Timeframe"
Cohesion: 0.08
Nodes (20): {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), CarryTemplate, {closure#2}(), {closure#8}(), Timeframe (+12 more)

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

### Community 96 - "Illuminate\Contracts\Config\Repository"
Cohesion: 0.11
Nodes (7): FundingRateRepository, ImportStrategies, PreparesBacktests, PromoteStrategy, {closure#1}(), {closure#2}(), RunBacktest

### Community 97 - "run"
Cohesion: 0.33
Nodes (6): name, runs-on, steps, audit, run, steps

### Community 98 - "Engine.php"
Cohesion: 0.08
Nodes (8): {closure#1}(), {closure#11}(), {closure#2}(), {closure#3}(), {closure#9}(), MoveStop, OpenCarry, {closure#25}()

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
Cohesion: 0.07
Nodes (19): {closure#2}(), ClientOrderId, OcoRequest, OrderAck, OrderRequest, OrderType, Limit, Market (+11 more)

### Community 117 - "StrategyLibrary"
Cohesion: 0.16
Nodes (8): {closure#1}(), ListStrategies, {closure#2}(), StrategiesScreen, StrategyLibrary, CheckResult, SaveResult, StrategyVersion

### Community 118 - "StrategiesScreen.php"
Cohesion: 0.14
Nodes (6): ReauthController, AccountScreen, HaltButton, {closure#1}(), ModeBadge, {closure#1}()

### Community 119 - "RiskState"
Cohesion: 0.17
Nodes (12): RiskEvent, RiskEventKind, DailyLossHalt, DrawdownFlatten, DrawdownHalt, WeeklyLossHalt, RiskState, {closure#13}() (+4 more)

### Community 120 - "buy-and-hold.strategy.yaml"
Cohesion: 0.12
Nodes (16): entries, long, exits, stop_loss, fails_when, family, hypothesis, when (+8 more)

### Community 121 - "ChartSeriesTest.php"
Cohesion: 0.24
Nodes (8): chart(), {closure#11}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#6}(), {closure#7}(), {closure#9}()

### Community 122 - "TokensTest.php"
Cohesion: 0.31
Nodes (6): {closure#3}(), {closure#4}(), contrastRatio(), contrastRules(), relativeLuminance(), themeTokens()

### Community 123 - "StrategyGates.php"
Cohesion: 0.08
Nodes (13): {closure#2}(), {closure#3}(), {closure#6}(), {closure#8}(), {closure#2}(), {closure#1}(), {closure#2}(), {closure#3}() (+5 more)

### Community 124 - "AppServiceProvider.php"
Cohesion: 0.09
Nodes (7): AppServiceProvider, {closure#3}(), {closure#4}(), FortifyServiceProvider, {closure#1}(), {closure#2}(), {closure#3}()

### Community 127 - ".sqrt"
Cohesion: 0.23
Nodes (12): Statistics, {closure#1}(), {closure#2}(), {closure#3}(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}() (+4 more)

### Community 128 - "funding-carry.strategy.yaml"
Cohesion: 0.12
Nodes (15): fails_when, family, hypothesis, params, enter_when, exit_when, margin_buffer, max_leverage_perp (+7 more)

### Community 129 - "McpServerTest.php"
Cohesion: 0.16
Nodes (13): AuthenticateMcpToken, McpToken, actAsToken(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}() (+5 more)

### Community 130 - "Illuminate\Http\Client\Request"
Cohesion: 0.18
Nodes (12): {closure#3}(), {closure#4}(), {closure#6}(), queryOf(), {closure#13}(), {closure#5}(), {closure#12}(), {closure#2}() (+4 more)

### Community 131 - ".zero"
Cohesion: 0.11
Nodes (4): Fill, PerpPosition, Position, {closure#16}()

### Community 132 - "TelegramSettings"
Cohesion: 0.17
Nodes (3): NotificationSettings, TelegramClient, TelegramSettings

### Community 133 - "PreparesBacktests.php"
Cohesion: 0.20
Nodes (5): {closure#2}(), {closure#3}(), {closure#5}(), {closure#7}(), {closure#8}()

### Community 135 - "ReadBackTest.php"
Cohesion: 0.44
Nodes (6): {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), readBackDocument(), readBackSpec()

### Community 137 - "0001_01_01_000000_create_users_table.php"
Cohesion: 0.33
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

### Community 138 - "TradingServer.php"
Cohesion: 0.17
Nodes (3): DslReference, ListInstruments, TradingServer

### Community 139 - ".encode"
Cohesion: 0.24
Nodes (6): CanonicalJson, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}()

### Community 140 - "MarketBreakers"
Cohesion: 0.29
Nodes (7): {closure#4}(), BreakerEvent, MarketBreakers, breakerMinute(), {closure#1}(), {closure#2}(), {closure#3}()

### Community 141 - "BinanceArchive.php"
Cohesion: 0.13
Nodes (9): {closure#1}(), {closure#2}(), EpochTime, ExchangeNumber, {closure#1}(), {closure#2}(), {closure#3}(), {closure#1}() (+1 more)

### Community 142 - "StrategySpec"
Cohesion: 0.15
Nodes (3): StrategyGates, CompiledStrategy, StrategySpec

### Community 143 - "Account"
Cohesion: 0.13
Nodes (7): Account, {closure#1}(), {closure#2}(), {closure#3}(), fakeTestnet(), testnetAccount(), {closure#6}()

### Community 144 - "DatabaseCandleFeed"
Cohesion: 0.18
Nodes (8): DatabaseCandleFeed, {closure#1}(), Rows, {closure#1}(), {closure#2}(), {closure#2}(), {closure#10}(), {closure#11}()

### Community 145 - ".times"
Cohesion: 0.10
Nodes (7): {closure#4}(), {closure#3}(), Keltner, Percent, Perpetual, {closure#14}(), {closure#15}()

### Community 146 - "InvalidArgumentException"
Cohesion: 0.07
Nodes (7): {closure#4}(), {closure#5}(), Period, InvalidNumber, Quantity, {closure#10}(), {closure#9}()

### Community 147 - "WalkForward.php"
Cohesion: 0.14
Nodes (10): {closure#11}(), {closure#12}(), {closure#1}(), {closure#3}(), {closure#5}(), {closure#6}(), {closure#7}(), {closure#8}() (+2 more)

### Community 148 - "0001_01_01_000002_create_jobs_table.php"
Cohesion: 0.33
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

### Community 150 - "Agents/TokensTest.php"
Cohesion: 0.14
Nodes (5): CreateMcpToken, Tokens, {closure#1}(), {closure#2}(), {closure#3}()

### Community 151 - "OpenLong"
Cohesion: 0.20
Nodes (4): Close, EntryOrder, OpenLong, OpenShort

### Community 157 - "strategies-screen.blade.php"
Cohesion: 0.40
Nodes (4): open, promote, run, save

### Community 159 - "extra"
Cohesion: 0.67
Nodes (3): extra, laravel, dont-discover

### Community 172 - "ExchangeVerificationTest.php"
Cohesion: 0.26
Nodes (12): {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}() (+4 more)

### Community 174 - ".assessEntry"
Cohesion: 0.25
Nodes (5): AccountView, RiskEngine, LimitedStrategy, StrategyLimits, {closure#23}()

### Community 175 - ".evaluate"
Cohesion: 0.29
Nodes (7): WalkForward, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), wfSet()

### Community 176 - "GoldenReportsTest.php"
Cohesion: 0.23
Nodes (6): PerformanceMetrics, Report, {closure#21}(), {closure#8}(), {closure#9}(), goldenRuns()

### Community 177 - ".benchmark"
Cohesion: 0.29
Nodes (9): btcMinutes(), {closure#1}(), {closure#2}(), {closure#3}(), referencePrice(), {closure#13}(), {closure#16}(), {closure#3}() (+1 more)

### Community 178 - "SimulationJobsTest.php"
Cohesion: 0.21
Nodes (4): {closure#1}(), {closure#2}(), {closure#3}(), savedEmaCross()

### Community 179 - "DocumentCheckerTest.php"
Cohesion: 0.30
Nodes (10): checkDocument(), {closure#1}(), {closure#2}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}(), {closure#8}() (+2 more)

### Community 183 - "Illuminate\Foundation\Testing\RefreshDatabase"
Cohesion: 0.28
Nodes (5): {closure#1}(), {closure#1}(), {closure#2}(), {closure#3}(), gatedVersion()

### Community 184 - "MinuteCandles"
Cohesion: 0.29
Nodes (4): MinuteCandles, {closure#14}(), {closure#3}(), {closure#7}()

### Community 185 - "VolumeProfile"
Cohesion: 0.38
Nodes (3): VolumeProfile, {closure#10}(), fiveMinuteBars()

### Community 186 - ".account"
Cohesion: 0.33
Nodes (4): Policies, {closure#26}(), {closure#27}(), {closure#28}()

### Community 187 - "EngineMode"
Cohesion: 0.40
Nodes (5): EngineMode, Backtest, Live, Paper, Testnet

## Knowledge Gaps
- **135 isolated node(s):** `Env`, `None`, `BelowMinimum`, `Breaker`, `Cooldown` (+130 more)
  These have ≤1 connection - possible missing edges. (Counts symbols only; 855 node(s) total have ≤1 connection when file, concept and rationale nodes are included.)
- **45 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `Decimal` connect `Decimal` to `.of`, `EngineTest.php`, `Clock`, `InstrumentRules`, `.parse`, `DocumentChecker`, `ChartScreen.php`, `CompiledStrategy.php`, `SimulatedVenueTest.php`, `SimulatedPerpVenueTest.php`, `Candle`, `IndicatorsTest.php`, `Node`, `PolicyLimits`, `Position`, `MarketType`, `LedgerTest.php`, `ReadBack`, `DonchianBreakout`, `FundingCarry`, `Symbol`, `.measure`, `Strategy`, `.zero`, `InMemoryDecisionLog`, `ExitReason`, `RiskPolicy`, `BacktestTest.php`, `RejectionReason`, `OrderRole`, `Illuminate\Database\Eloquent\Model`, `SimulatedVenue`, `EnginePerpTest.php`, `SimClock`, `InstrumentRules`, `IndicatorReferenceTest.php`, `RunBacktestCommandTest.php`, `.isFull`, `.__construct`, `MarketType`, `App\Strategy\Dsl\Document\DocumentChecker`, `.of`, `Carbon\CarbonImmutable`, `DslError`, `.walk`, `DecimalMath`, `FundingRate`, `App\Support\Number\Decimal`, `MonteCarlo`, `Timeframe`, `.evaluate`, `.fromStrings`, `Illuminate\Contracts\Config\Repository`, `Engine.php`, `CandleFeedTest.php`, `ClientOrderId`, `RiskState`, `ChartSeriesTest.php`, `StrategyGates.php`, `.sqrt`, `.zero`, `PreparesBacktests.php`, `ReadBackTest.php`, `.encode`, `BinanceArchive.php`, `StrategySpec`, `DatabaseCandleFeed`, `.times`, `InvalidArgumentException`, `WalkForward.php`, `OpenLong`, `QualityCheck`, `.assessEntry`, `.evaluate`, `GoldenReportsTest.php`, `.benchmark`, `DocumentCheckerTest.php`, `DirectionalMovement`, `VolumeProfile`, `.roundDownToTick`?**
  _High betweenness centrality (0.184) - this node is a cross-community bridge._
- **What connects `Env`, `None`, `BelowMinimum` to the rest of the system?**
  _135 weakly-connected nodes found - possible missing edges._
- **Should `EngineTest.php` be split into smaller, more focused modules?**
  _Cohesion score 0.12508735150244585 - nodes in this community are weakly interconnected._
- **Why does `Symbol` connect `Symbol` to `.of`, `McpServerTest.php`, `Clock`, `.zero`, `InstrumentRules`, `PreparesBacktests.php`, `DocumentChecker`, `ChartScreen.php`, `CompiledStrategy.php`, `SimulatedVenueTest.php`, `Instrument`, `SimulatedPerpVenueTest.php`, `.parse`, `ExchangeVerificationTest.php`, `StrategySpec`, `EngineTest.php`, `.encode`, `PolicyLimits`, `.times`, `MarketType`, `LedgerTest.php`, `InvalidArgumentException`, `OpenLong`, `DonchianBreakout`, `ReadBack`, `FundingCarry`, `AppServiceProvider.php`, `.measure`, `Strategy`, `OpenAccountTest.php`, `InMemoryDecisionLog`, `ExitReason`, `BackfillTest.php`, `BacktestTest.php`, `Decimal`, `OrderRole`, `QualityCheck`, `Illuminate\Database\Eloquent\Model`, `ExchangeVerificationTest.php`, `SimulatedVenue`, `.benchmark`, `GoldenReportsTest.php`, `EnginePerpTest.php`, `SimClock`, `Laravel\Mcp\Response`, `InstrumentRules`, `RunEngine.php`, `RunBacktestCommandTest.php`, `Universe`, `.__construct`, `MarketType`, `CandleRepositoryTest.php`, `App\Strategy\Dsl\Document\DocumentChecker`, `.of`, `Carbon\CarbonImmutable`, `DslError`, `FundingRate`, `Timeframe`, `Illuminate\Contracts\Config\Repository`, `Engine.php`, `CandleFeedTest.php`, `ClientOrderId`, `ChartSeriesTest.php`, `StrategyGates.php`?**
  _High betweenness centrality (0.080) - this node is a cross-community bridge._
- **Should `Clock` be split into smaller, more focused modules?**
  _Cohesion score 0.07591836734693877 - nodes in this community are weakly interconnected._
- **Why does `Timeframe` connect `Timeframe` to `McpServerTest.php`, `Clock`, `BinanceArchive`, `EngineTest.php`, `.parse`, `DocumentChecker`, `ChartScreen.php`, `CompiledStrategy.php`, `SimulatedVenueTest.php`, `Instrument`, `SimulatedPerpVenueTest.php`, `Candle`, `BinanceArchive.php`, `StrategySpec`, `MarketBreakers`, `DatabaseCandleFeed`, `Node`, `Position`, `MarketType`, `InvalidArgumentException`, `IndicatorsTest.php`, `OpenLong`, `DonchianBreakout`, `ReadBack`, `FundingCarry`, `Symbol`, `AppServiceProvider.php`, `Decimal`, `OrderRole`, `EnginePerpTest.php`, `DocumentCheckerTest.php`, `Laravel\Mcp\Response`, `IndicatorReferenceTest.php`, `CandleRepositoryTest.php`, `App\Strategy\Dsl\Document\DocumentChecker`, `DslError`, `.walk`, `FundingRate`, `App\Support\Number\Decimal`, `.fromStrings`, `CandleFeedTest.php`, `ChartSeriesTest.php`, `StrategyGates.php`, `.sqrt`?**
  _High betweenness centrality (0.043) - this node is a cross-community bridge._
- **Should `BinanceArchive` be split into smaller, more focused modules?**
  _Cohesion score 0.062040816326530614 - nodes in this community are weakly interconnected._