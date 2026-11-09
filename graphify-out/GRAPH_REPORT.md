# Graph Report - trading-platform  (2026-11-10)

## Corpus Check
- 658 files · ~283,757 words
- Verdict: corpus is large enough that graph structure adds value.
- Unclassified: 103 file(s) not represented in the graph (top: .zip 38, .CHECKSUM 38, (none) 19)

## Summary
- 5389 nodes · 18756 edges · 218 communities (154 shown, 64 thin omitted)
- Extraction: 90% EXTRACTED · 10% INFERRED · 0% AMBIGUOUS · INFERRED: 1812 edges (avg confidence: 0.85)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `f7d34edd`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- SimulatedPerpVenueTest.php
- engineHarness
- .encode
- PreparesBacktests.php
- SettingsScreen
- Strategy
- DocumentChecker
- .now
- CompiledStrategy.php
- .of
- BinanceVenueTest.php
- SimClock
- Candle
- ExchangeVerificationTest.php
- Engine
- App\Support\Symbol
- Strategy
- Clock
- Instrument
- ScreensTest.php
- Ledger
- Illuminate\Database\Schema\Blueprint
- generate.py
- App\Strategy\Dsl\Compiler\CompiledStrategy
- Symbol
- ScalpingFunctionsTest.php
- TerminalQrCodeTest.php
- .of
- EngineUniverseTest.php
- E2eSeeder.php
- DepthStreamTest.php
- oi-momentum-long.strategy.yaml
- App\Strategy\Contracts\Strategy
- LiveAccountTest.php
- DslError
- App\Livewire\StrategiesScreen
- ScalpingCollectorTest.php
- HealthTest.php
- OrderManagerTest.php
- InstrumentRules
- Position
- ema-cross-scalp-long.strategy.yaml
- Decimal
- .times
- Parser
- Timeframe
- Illuminate\Database\Eloquent\Model
- package.json
- Laravel\Mcp\Response
- BinanceRestClientTest.php
- ema-cross-scalp-short.strategy.yaml
- ChartScreen.php
- ReadBack
- App\Market\Stream\PhrityTransport
- postgres
- UniverseStrategy
- LadderTest.php
- donchian-breakout-short.strategy.yaml
- IndicatorsTest.php
- support.mjs
- backtestFixture
- IndicatorReferenceTest.php
- App\Support\Clock\Clock
- .plus
- Illuminate\Http\Request
- donchian-breakout.strategy.yaml
- .run
- .expect
- Kind
- Quantity
- Illuminate\Support\Facades\Http
- Statistics
- App\Execution\Journal\DatabaseDecisionLog
- scripts
- ma-trend-short.strategy.yaml
- .evaluate
- DailyStatsTest.php
- composer.json
- jobs
- postgres
- ma-trend.strategy.yaml
- .isPositive
- ForwardingVenue
- App\Support\Number\Decimal
- pint/composer.json
- HealthChecks
- RunSimulation.php
- App\Market\Instruments\Universe
- require-dev
- scripts-descriptions
- Carbon\CarbonImmutable
- .fromStrings
- config
- uses
- test
- with
- .isGreaterThan
- jobs
- TokenKind
- psr-4
- require
- InvalidArgumentException
- StrategyGates.php
- uses
- StrategySpec
- devDependencies
- App\Agents\Mcp\Tools\DslReference
- bootstrap/app.php
- BinanceArchive
- .breaches
- buy-and-hold.strategy.yaml
- TradingTool
- TokensTest.php
- ArchiveImportTest.php
- User
- Illuminate\Support\Facades\Schema
- RuntimeException
- .register
- funding-carry.strategy.yaml
- McpServerTest.php
- env.mjs
- chart.js
- TelegramSettings
- UserFactory.php
- settings-screen.blade.php
- steps
- 2026_11_09_000014_create_health_tables.php
- .isPositive
- App\Market\Models\Instrument
- StrategyLibrary
- CandleRepositoryTest.php
- AppServiceProvider
- App\Execution\Models\Account
- OrderBook
- scripts
- 2026_11_08_000007_create_strategy_gate_tables.php
- fake-telegram.mjs
- 2026_11_08_000003_create_strategy_trials_table.php
- ResearchUniverse
- 2026_11_08_000004_create_strategy_versions_tables.php
- 2026_11_08_000006_create_mcp_tables.php
- 2026_11_09_000017_create_live_tables.php
- ScalpingBackfillTest.php
- strategies-screen.blade.php
- extra
- app.blade.php
- halt
- remove
- account-screen.blade.php
- Illuminate\Database\Migrations\Migration
- SimulationQueue
- WarmingVenue
- 2026_11_09_000027_add_to_to_market_jobs.php
- UniverseStrategyTest.php
- 2026_11_09_000012_create_account_results_tables.php
- 2026_11_09_000023_create_universe_members_table.php
- SettingsScreenTest.php
- DailyHistory
- App\Strategy\Dsl\DslError
- UniverseBacktestTest.php
- ChartLiveController.php
- MarketType
- PolicyLimits
- 2026_11_09_000025_create_book_depth_table.php
- 0001_01_01_000002_create_jobs_table.php
- 2026_11_09_000009_create_exchange_keys_table.php
- 2026_11_09_000018_add_effective_at_to_deployments.php
- 2026_11_09_000016_create_market_universe_tables.php
- App\Agents\Mcp\TradingServer
- start-local.ps1
- Symbol
- 0001_01_01_000000_create_users_table.php
- MarketDataSettingsTest.php
- InstrumentRules
- App\Execution\Engine
- streamWith
- 2026_11_09_000020_add_lifetime_to_instruments.php
- CrossSection
- App
- AppServiceProvider.php
- Illuminate\Support\Facades\DB
- 2026_11_09_000026_create_liquidation_tables.php
- 2026_11_09_000022_create_daily_stats_table.php
- QualityReport
- StrategyGatesTest.php
- SeedingTest.php
- 2026_11_09_000010_add_two_factor_columns_to_users_table.php
- 2026_11_09_000024_create_positioning_table.php

## God Nodes (most connected - your core abstractions)
1. `Decimal` - 477 edges
2. `Side` - 203 edges
3. `Symbol` - 184 edges
4. `App` - 179 edges
5. `Candle` - 122 edges
6. `Timeframe` - 106 edges
7. `Price` - 98 edges
8. `Position` - 89 edges
9. `SimClock` - 85 edges
10. `SimulatedVenue` - 69 edges

## Surprising Connections (you probably didn't know these)
- `{closure#4}()` --calls--> `App`  [INFERRED]
  tests/Feature/Simulation/SimulationJobsTest.php → app/Notifications/SettingSource.php
- `{closure#1}()` --calls--> `App`  [INFERRED]
  tests/Feature/Support/ClockBindingTest.php → app/Notifications/SettingSource.php
- `{closure#13}()` --calls--> `App`  [INFERRED]
  tests/Feature/Web/SettingsScreenTest.php → app/Notifications/SettingSource.php
- `{closure#8}()` --calls--> `App`  [INFERRED]
  tests/Feature/Web/SettingsScreenTest.php → app/Notifications/SettingSource.php
- `up()` --calls--> `App`  [INFERRED]
  database/migrations/2026_11_09_000016_create_market_universe_tables.php → app/Notifications/SettingSource.php

## Import Cycles
- None detected.

## Communities (218 total, 64 thin omitted)

### Community 0 - "SimulatedPerpVenueTest.php"
Cohesion: 0.18
Nodes (28): PriceMinute, {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#15}() (+20 more)

### Community 1 - "engineHarness"
Cohesion: 0.06
Nodes (64): EngineCommand, EngineMode, Backtest, Live, Paper, Testnet, InMemoryDecisionLog, EntryOrder (+56 more)

### Community 2 - ".encode"
Cohesion: 0.30
Nodes (6): CanonicalJson, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}()

### Community 3 - "PreparesBacktests.php"
Cohesion: 0.06
Nodes (24): {closure#5}(), {closure#2}(), {closure#4}(), FundingRate, Liquidations, OpenInterest, {closure#2}(), DatabaseCandleFeed (+16 more)

### Community 4 - "SettingsScreen"
Cohesion: 0.07
Nodes (8): CreateMcpToken, RevokeMcpToken, Tokens, OpenAccount, StoreKeys, SettingsScreen, {closure#1}(), UniverseEntry

### Community 5 - "Strategy"
Cohesion: 0.09
Nodes (39): {closure#12}(), Parameters, {closure#17}(), {closure#4}(), {closure#5}(), carryDecisions(), {closure#10}(), {closure#11}() (+31 more)

### Community 7 - ".now"
Cohesion: 0.17
Nodes (6): {closure#1}(), {closure#2}(), {closure#1}(), ReviewLadder, Ladder, LadderStatus

### Community 8 - "CompiledStrategy.php"
Cohesion: 0.07
Nodes (9): PerpetualTracker, CompiledStrategy, {closure#47}(), {closure#48}(), {closure#50}(), {closure#54}(), Compiler, {closure#1}() (+1 more)

### Community 9 - ".of"
Cohesion: 0.09
Nodes (74): FeeSchedule, CostModel, {closure#1}(), RunDefinition, Side, Buy, Sell, btc() (+66 more)

### Community 10 - "BinanceVenueTest.php"
Cohesion: 0.09
Nodes (21): {closure#2}(), OrderType, Limit, Market, StopMarket, TakeProfitMarket, BinanceVenue, BinanceApiError (+13 more)

### Community 11 - "SimClock"
Cohesion: 0.12
Nodes (20): SimClock, WallClock, {closure#1}(), {closure#1}(), {closure#1}(), forceOrder(), {closure#1}(), {closure#2}() (+12 more)

### Community 12 - "Candle"
Cohesion: 0.06
Nodes (42): Candle, InvalidCandle, {closure#1}(), {closure#2}(), VolumeProfile, candleWith(), {closure#1}(), {closure#10}() (+34 more)

### Community 13 - "ExchangeVerificationTest.php"
Cohesion: 0.12
Nodes (29): {closure#1}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}() (+21 more)

### Community 14 - "Engine"
Cohesion: 0.10
Nodes (5): DecisionLog, DecisionRecord, EngineRecord, Engine, ReconciliationMismatch

### Community 15 - "App\Support\Symbol"
Cohesion: 0.07
Nodes (7): AuthenticateMcpToken, {closure#1}(), {closure#2}(), GridBacktest, PreparesBacktests, ReadsGridOptions, StrategyGates

### Community 16 - "Strategy"
Cohesion: 0.09
Nodes (28): RiskProfile, Account, Benchmark, RiskPolicy, Strategy, {closure#1}(), {closure#2}(), {closure#3}() (+20 more)

### Community 17 - "Clock"
Cohesion: 0.05
Nodes (24): FlattenEngine, HaltEngine, IssuesCommands, PauseStrategy, ResumeEngine, CommandKind, Flatten, Halt (+16 more)

### Community 18 - "Instrument"
Cohesion: 0.08
Nodes (13): BackfillStep, CandleRepository, {closure#2}(), {closure#1}(), CandleSource, Archive, Rest, Stream (+5 more)

### Community 19 - "ScreensTest.php"
Cohesion: 0.14
Nodes (22): {closure#1}(), chartLiveQuery(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}() (+14 more)

### Community 20 - "Ledger"
Cohesion: 0.05
Nodes (68): ExitReason, Flatten, Liquidation, Signal, Stop, Target, Marks, Ledger (+60 more)

### Community 21 - "Illuminate\Database\Schema\Blueprint"
Cohesion: 0.14
Nodes (13): {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#1}(), {closure#2}(), {closure#3}() (+5 more)

### Community 22 - "generate.py"
Cohesion: 0.07
Nodes (30): atr(), bars(), bb(), change(), dmi(), donchian(), ema(), highest() (+22 more)

### Community 23 - "App\Strategy\Dsl\Compiler\CompiledStrategy"
Cohesion: 0.07
Nodes (22): AstPrinter, {closure#3}(), {closure#5}(), {closure#6}(), {closure#7}(), {closure#8}(), {closure#9}(), {closure#2}() (+14 more)

### Community 24 - "Symbol"
Cohesion: 0.03
Nodes (35): BuyAndHold, DonchianBreakout, FundingCarry, MovingAverageTrend, BuildableStrategy, LimitedStrategy, Close, Intent (+27 more)

### Community 25 - "ScalpingFunctionsTest.php"
Cohesion: 0.23
Nodes (11): backtestableScalpingHour(), {closure#2}(), {closure#3}(), {closure#5}(), {closure#6}(), {closure#7}(), {closure#8}(), {closure#9}() (+3 more)

### Community 26 - "TerminalQrCodeTest.php"
Cohesion: 0.21
Nodes (7): TerminalQrCode, {closure#3}(), {closure#4}(), {closure#5}(), expectedModules(), readColoured(), readPlain()

### Community 27 - ".of"
Cohesion: 0.19
Nodes (43): RiskState, account(), accountPolicy(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}() (+35 more)

### Community 28 - "EngineUniverseTest.php"
Cohesion: 0.15
Nodes (19): Policies, {closure#10}(), {closure#11}(), {closure#13}(), {closure#14}(), {closure#17}(), {closure#3}(), {closure#6}() (+11 more)

### Community 29 - "E2eSeeder.php"
Cohesion: 0.10
Nodes (8): {closure#1}(), {closure#3}(), {closure#1}(), InstrumentFilter, StrategyVersion, DatabaseSeeder, {closure#1}(), E2eSeeder

### Community 30 - "DepthStreamTest.php"
Cohesion: 0.08
Nodes (14): BinanceEndpoints, Sleeper, {closure#1}(), StreamDepth, {closure#2}(), DepthStream, StreamFailed, StreamTransport (+6 more)

### Community 31 - "oi-momentum-long.strategy.yaml"
Cohesion: 0.07
Nodes (27): entries, long, exits, stop_loss, take_profit, when, fails_when, family (+19 more)

### Community 32 - "App\Strategy\Contracts\Strategy"
Cohesion: 0.06
Nodes (21): SizedStrategy, SizedUniverseStrategy, {closure#2}(), {closure#2}(), {closure#3}(), {closure#1}(), ScriptedUniverseStrategy, {closure#12}() (+13 more)

### Community 33 - "LiveAccountTest.php"
Cohesion: 0.11
Nodes (20): approveLive(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#7}(), {closure#8}() (+12 more)

### Community 34 - "DslError"
Cohesion: 0.22
Nodes (11): {closure#1}(), DslError, YamlList, YamlMap, YamlReader, YamlScalar, YamlValue, {closure#1}() (+3 more)

### Community 35 - "App\Livewire\StrategiesScreen"
Cohesion: 0.08
Nodes (10): Deployment, ReauthController, AccountScreen, HaltButton, {closure#1}(), ModeBadge, {closure#2}(), {closure#4}() (+2 more)

### Community 36 - "ScalpingCollectorTest.php"
Cohesion: 0.35
Nodes (10): aggTrade(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#6}(), collector(), defaultBinanceFakes() (+2 more)

### Community 37 - "HealthTest.php"
Cohesion: 0.21
Nodes (13): {closure#7}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}(), {closure#8}() (+5 more)

### Community 38 - "OrderManagerTest.php"
Cohesion: 0.09
Nodes (32): OrderRole, Entry, Exit, Flatten, Stop, Target, ProtectionPlan, TrackedOrder (+24 more)

### Community 39 - "InstrumentRules"
Cohesion: 0.06
Nodes (40): Backtest, RunBacktest, Participant, ReportBuilder, AssetMismatch, {closure#13}(), {closure#16}(), {closure#2}() (+32 more)

### Community 40 - "Position"
Cohesion: 0.07
Nodes (25): Argument, BoolLiteral, Call, DurationLiteral, Identifier, Lookback, Node, NumberLiteral (+17 more)

### Community 41 - "ema-cross-scalp-long.strategy.yaml"
Cohesion: 0.06
Nodes (33): entries, long, exits, stop_loss, take_profit, time_stop, when, fails_when (+25 more)

### Community 42 - "Decimal"
Cohesion: 0.03
Nodes (42): RoundTrip, {closure#1}(), {closure#2}(), {closure#3}(), {closure#10}(), {closure#2}(), {closure#3}(), {closure#4}() (+34 more)

### Community 43 - ".times"
Cohesion: 0.07
Nodes (18): Liquidity, Maker, Taker, OrderStatus, Cancelled, Expired, Filled, New (+10 more)

### Community 44 - "Parser"
Cohesion: 0.33
Nodes (3): Binary, Parser, Token

### Community 45 - "Timeframe"
Cohesion: 0.05
Nodes (63): CandleAggregator, CandleSequenceBroken, {closure#3}(), {closure#4}(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}() (+55 more)

### Community 46 - "Illuminate\Database\Eloquent\Model"
Cohesion: 0.04
Nodes (43): McpCall, McpToken, {closure#5}(), Account, AccountEquity, AccountSnapshot, AccountTrade, PlainDecimal (+35 more)

### Community 47 - "package.json"
Cohesion: 0.15
Nodes (17): dependencies, codemirror@^6.0.2, @codemirror/lang-yaml@^6.1.3, @codemirror/language@^6.13.1, @lezer/highlight@^1.2.5, lightweight-charts@^5.2.1, optionalDependencies, @laravel/multiplex@^0.4.1 (+9 more)

### Community 48 - "Laravel\Mcp\Response"
Cohesion: 0.21
Nodes (4): ChartTool, GetCandles, GetIndicators, ChartSeries

### Community 49 - "BinanceRestClientTest.php"
Cohesion: 0.07
Nodes (33): {closure#3}(), {closure#6}(), {closure#1}(), {closure#3}(), {closure#12}(), {closure#22}(), {closure#5}(), {closure#9}() (+25 more)

### Community 50 - "ema-cross-scalp-short.strategy.yaml"
Cohesion: 0.06
Nodes (33): entries, short, exits, stop_loss, take_profit, time_stop, when, fails_when (+25 more)

### Community 51 - "ChartScreen.php"
Cohesion: 0.05
Nodes (36): {closure#2}(), ChartScreen, {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), FunctionSpec, Group (+28 more)

### Community 54 - "postgres"
Cohesion: 0.10
Nodes (19): POSTGRES_DB, POSTGRES_PASSWORD, POSTGRES_USER, interval, retries, test, timeout, environment (+11 more)

### Community 55 - "UniverseStrategy"
Cohesion: 0.15
Nodes (4): UniverseSchedule, UniverseStrategy, {closure#17}(), {closure#1}()

### Community 56 - "LadderTest.php"
Cohesion: 0.44
Nodes (11): at(), {closure#1}(), {closure#10}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}() (+3 more)

### Community 57 - "donchian-breakout-short.strategy.yaml"
Cohesion: 0.07
Nodes (26): entries, short, default, range, step, default, range, step (+18 more)

### Community 58 - "IndicatorsTest.php"
Cohesion: 0.11
Nodes (19): Donchian, Ema, {closure#5}(), bar(), {closure#10}(), {closure#12}(), {closure#13}(), {closure#14}() (+11 more)

### Community 59 - "support.mjs"
Cohesion: 0.26
Nodes (9): @playwright/test, confirmed(), checkLayout(), EMAIL, freshCode(), PASSWORD, reauthenticate(), totp() (+1 more)

### Community 60 - "backtestFixture"
Cohesion: 0.15
Nodes (26): BacktestRun, {closure#1}(), {closure#4}(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}() (+18 more)

### Community 61 - "IndicatorReferenceTest.php"
Cohesion: 0.06
Nodes (33): Atr, BarIndicator, Bollinger, Change, DonchianChannel, Highest, Hma, Keltner (+25 more)

### Community 62 - "App\Support\Clock\Clock"
Cohesion: 0.06
Nodes (15): {closure#1}(), {closure#3}(), {closure#1}(), StreamCandles, LiquidationRepository, CandleStream, LiquidationCollector, ScalpingCollector (+7 more)

### Community 63 - ".plus"
Cohesion: 0.08
Nodes (13): Fill, PerpPosition, MonteCarlo, Perpetual, {closure#16}(), {closure#1}(), {closure#2}(), {closure#3}() (+5 more)

### Community 64 - "Illuminate\Http\Request"
Cohesion: 0.24
Nodes (3): {closure#8}(), {closure#3}(), {closure#4}()

### Community 65 - "donchian-breakout.strategy.yaml"
Cohesion: 0.07
Nodes (26): entries, long, default, range, step, default, range, step (+18 more)

### Community 66 - ".run"
Cohesion: 0.11
Nodes (21): PerformanceMetrics, Report, btcMinutes(), {closure#1}(), {closure#2}(), referencePrice(), {closure#21}(), {closure#1}() (+13 more)

### Community 67 - ".expect"
Cohesion: 0.24
Nodes (16): {closure#12}(), checkDocument(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#2}(), {closure#4}() (+8 more)

### Community 68 - "Kind"
Cohesion: 0.08
Nodes (31): {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#7}(), {closure#9}(), ParameterSpec, Scope (+23 more)

### Community 69 - "Quantity"
Cohesion: 0.24
Nodes (3): Quantity, {closure#10}(), {closure#9}()

### Community 70 - "Illuminate\Support\Facades\Http"
Cohesion: 0.10
Nodes (16): InstrumentDefinition, InstrumentStatus, Delisted, Halted, Trading, {closure#1}(), InstrumentSync, SyncResult (+8 more)

### Community 71 - "Statistics"
Cohesion: 0.13
Nodes (19): Statistics, Gates, {closure#3}(), brokenGate(), {closure#1}(), {closure#3}(), {closure#4}(), {closure#6}() (+11 more)

### Community 72 - "App\Execution\Journal\DatabaseDecisionLog"
Cohesion: 0.27
Nodes (3): IntentRecord, {closure#1}(), DatabaseDecisionLog

### Community 73 - "scripts"
Cohesion: 0.15
Nodes (13): scripts, analyse, dev, format, mutate, post-autoload-dump, post-update-cmd, pre-package-uninstall (+5 more)

### Community 74 - "ma-trend-short.strategy.yaml"
Cohesion: 0.07
Nodes (26): entries, short, exits, stop_loss, when, fails_when, family, default (+18 more)

### Community 75 - ".evaluate"
Cohesion: 0.25
Nodes (8): WalkForward, WalkForwardSet, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), wfSet()

### Community 76 - "DailyStatsTest.php"
Cohesion: 0.24
Nodes (5): {closure#1}(), {closure#3}(), {closure#4}(), dayLine(), monthsListing()

### Community 77 - "composer.json"
Cohesion: 0.17
Nodes (11): autoload-dev, psr-4, description, keywords, license, minimum-stability, name, prefer-stable (+3 more)

### Community 78 - "jobs"
Cohesion: 0.18
Nodes (10): concurrency, cancel-in-progress, group, name, on, pull_request, push, permissions (+2 more)

### Community 79 - "postgres"
Cohesion: 0.25
Nodes (8): POSTGRES_DB, POSTGRES_PASSWORD, POSTGRES_USER, env, image, options, ports, postgres

### Community 80 - "ma-trend.strategy.yaml"
Cohesion: 0.07
Nodes (26): entries, long, exits, stop_loss, when, fails_when, family, default (+18 more)

### Community 81 - ".isPositive"
Cohesion: 0.11
Nodes (20): Cscv, DeflatedSharpe, {closure#3}(), closeTo(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}() (+12 more)

### Community 83 - "App\Support\Number\Decimal"
Cohesion: 0.05
Nodes (70): {closure#6}(), {closure#3}(), TradeFlow, {closure#10}(), {closure#5}(), {closure#8}(), {closure#3}(), {closure#4}() (+62 more)

### Community 84 - "pint/composer.json"
Cohesion: 0.20
Nodes (9): config, sort-packages, description, license, name, require, laravel/pint, php (+1 more)

### Community 85 - "HealthChecks"
Cohesion: 0.08
Nodes (7): CheckHealth, DiskUsage, {closure#1}(), HealthAlerts, HealthChecks, HealthResult, FakeDisk

### Community 86 - "RunSimulation.php"
Cohesion: 0.08
Nodes (9): {closure#4}(), Notifier, HistoryImports, ImportMarketData, MarketJob, TelegramNotifier, RunSimulation, SimulationJob (+1 more)

### Community 87 - "App\Market\Instruments\Universe"
Cohesion: 0.16
Nodes (4): ArchivePeriod, Backfill, BackfillReport, BackfillMarketData

### Community 88 - "require-dev"
Cohesion: 0.22
Nodes (9): require-dev, fakerphp/faker, larastan/larastan, laravel/pail, laravel/pao, mockery/mockery, nunomaduro/collision, pestphp/pest (+1 more)

### Community 89 - "scripts-descriptions"
Cohesion: 0.25
Nodes (8): scripts-descriptions, analyse, format, mutate, test, test:network, test:testnet, tools:install

### Community 90 - "Carbon\CarbonImmutable"
Cohesion: 0.06
Nodes (24): {closure#1}(), {closure#1}(), CandleFeed, FormingCandle, MinuteCandles, Positioning, BreakerEvent, MarketBreakers (+16 more)

### Community 91 - ".fromStrings"
Cohesion: 0.11
Nodes (10): {closure#1}(), {closure#2}(), RegimeLabels, DirectionalMovement, {closure#1}(), {closure#2}(), {closure#4}(), {closure#7}() (+2 more)

### Community 92 - "config"
Cohesion: 0.29
Nodes (7): pestphp/pest-plugin, php-http/discovery, config, allow-plugins, optimize-autoloader, preferred-install, sort-packages

### Community 93 - "uses"
Cohesion: 0.33
Nodes (6): GITHUB_TOKEN, secrets, name, runs-on, steps, env

### Community 94 - "test"
Cohesion: 0.18
Nodes (11): test, php, image, ports, redis, fail-fast, matrix, name (+3 more)

### Community 95 - "with"
Cohesion: 0.20
Nodes (10): with, cache, coverage, extensions, fetch-depth, name, node-version, path (+2 more)

### Community 97 - "jobs"
Cohesion: 0.25
Nodes (8): name, runs-on, name, runs-on, steps, jobs, analyse, audit

### Community 98 - "TokenKind"
Cohesion: 0.09
Nodes (18): Lexer, TokenKind, Colon, Comma, Dot, Duration, End, Identifier (+10 more)

### Community 99 - "psr-4"
Cohesion: 0.40
Nodes (5): autoload, psr-4, App\\, Database\\Factories\\, Database\\Seeders\\

### Community 100 - "require"
Cohesion: 0.22
Nodes (9): require, bacon/bacon-qr-code, laravel/fortify, laravel/framework, laravel/mcp, laravel/tinker, livewire/livewire, php (+1 more)

### Community 102 - "InvalidArgumentException"
Cohesion: 0.08
Nodes (10): {closure#1}(), {closure#2}(), {closure#1}(), {closure#1}(), {closure#3}(), {closure#5}(), {closure#7}(), {closure#1}() (+2 more)

### Community 103 - "StrategyGates.php"
Cohesion: 0.07
Nodes (19): {closure#11}(), {closure#12}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#6}(), {closure#8}(), {closure#1}() (+11 more)

### Community 104 - "uses"
Cohesion: 0.21
Nodes (12): steps, name, runs-on, steps, frontend, lint, name, runs-on (+4 more)

### Community 105 - "StrategySpec"
Cohesion: 0.14
Nodes (7): CarryTemplate, EntrySpec, ExitSpec, Expression, StrategySpec, UniverseRule, {closure#10}()

### Community 106 - "devDependencies"
Cohesion: 0.18
Nodes (12): devDependencies, concurrently@^10.0.3, laravel-vite-plugin@^3.1, @playwright/test@1.63.0, tailwindcss@^4.0.0, @tailwindcss/vite@^4.0.0, vite@^8.0.0, concurrently (+4 more)

### Community 117 - "bootstrap/app.php"
Cohesion: 0.32
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

### Community 118 - "BinanceArchive"
Cohesion: 0.13
Nodes (6): BinanceArchive, {closure#2}(), ExchangeNumber, TradeFlowBuilder, {closure#1}(), {closure#2}()

### Community 119 - ".breaches"
Cohesion: 0.29
Nodes (10): Band, bandReport(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#7}() (+2 more)

### Community 120 - "buy-and-hold.strategy.yaml"
Cohesion: 0.12
Nodes (16): entries, long, exits, stop_loss, fails_when, family, hypothesis, when (+8 more)

### Community 121 - "TradingTool"
Cohesion: 0.16
Nodes (5): GetJob, GetStrategy, ListStrategies, SaveStrategy, TradingTool

### Community 122 - "TokensTest.php"
Cohesion: 0.31
Nodes (6): {closure#3}(), {closure#4}(), contrastRatio(), contrastRules(), relativeLuminance(), themeTokens()

### Community 123 - "ArchiveImportTest.php"
Cohesion: 0.29
Nodes (10): archiveBytes(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}() (+2 more)

### Community 125 - "Illuminate\Support\Facades\Schema"
Cohesion: 0.14
Nodes (4): {closure#1}(), {closure#1}(), {closure#1}(), {closure#2}()

### Community 126 - "RuntimeException"
Cohesion: 0.10
Nodes (20): ArchiveMissing, ChecksumMismatch, {closure#3}(), BinanceRequestFailed, LocationRestricted, RateLimited, RequestFailed, VerificationSource (+12 more)

### Community 127 - ".register"
Cohesion: 0.10
Nodes (26): {closure#10}(), {closure#13}(), {closure#14}(), {closure#15}(), {closure#16}(), {closure#18}(), {closure#20}(), {closure#23}() (+18 more)

### Community 128 - "funding-carry.strategy.yaml"
Cohesion: 0.12
Nodes (15): fails_when, family, hypothesis, params, enter_when, exit_when, margin_buffer, max_leverage_perp (+7 more)

### Community 129 - "McpServerTest.php"
Cohesion: 0.27
Nodes (13): actAsToken(), {closure#10}(), {closure#11}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}() (+5 more)

### Community 130 - "env.mjs"
Cohesion: 0.27
Nodes (4): sizes, BASE_URL, E2E_ENV, TELEGRAM_URL

### Community 131 - "chart.js"
Cohesion: 0.27
Nodes (7): faded(), options(), priceChart(), token(), volumeColor(), dslEditor(), highlight

### Community 132 - "TelegramSettings"
Cohesion: 0.11
Nodes (8): SendDailyReport, DailyReport, NotificationSettings, SettingSource, Env, None, TelegramClient, TelegramSettings

### Community 135 - "settings-screen.blade.php"
Cohesion: 0.15
Nodes (12): approveLive, confirm, forgetKey, forgetTelegram, importUniverse, monitor, notify, removeInstrument (+4 more)

### Community 138 - "steps"
Cohesion: 0.33
Nodes (6): name, runs-on, services, steps, e2e, if

### Community 139 - "2026_11_09_000014_create_health_tables.php"
Cohesion: 0.33
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

### Community 140 - ".isPositive"
Cohesion: 0.08
Nodes (16): {closure#2}(), Normal, DecimalMath, {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}() (+8 more)

### Community 141 - "App\Market\Models\Instrument"
Cohesion: 0.09
Nodes (19): {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#2}(), {closure#4}(), {closure#6}() (+11 more)

### Community 142 - "StrategyLibrary"
Cohesion: 0.18
Nodes (6): CompileStrategy, {closure#3}(), SimulationQueue, StrategyLibrary, CheckResult, SaveResult

### Community 143 - "CandleRepositoryTest.php"
Cohesion: 0.31
Nodes (11): candleStore(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}() (+3 more)

### Community 145 - "App\Execution\Models\Account"
Cohesion: 0.08
Nodes (10): AccountRuntime, LiveGate, ApproveLive, ConfirmsOperator, CreateAccount, RunEngine, ReplayDivergence, LiveApproval (+2 more)

### Community 146 - "OrderBook"
Cohesion: 0.13
Nodes (9): DepthUpdate, OrderBook, OrderBookOutOfSync, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), levels() (+1 more)

### Community 147 - "scripts"
Cohesion: 0.50
Nodes (4): scripts, build, dev, e2e

### Community 152 - "ResearchUniverse"
Cohesion: 0.14
Nodes (4): {closure#2}(), {closure#3}(), ResearchUniverse, Universes

### Community 155 - "2026_11_09_000017_create_live_tables.php"
Cohesion: 0.33
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

### Community 156 - "ScalpingBackfillTest.php"
Cohesion: 0.43
Nodes (6): {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), scalpingBackfill()

### Community 157 - "strategies-screen.blade.php"
Cohesion: 0.33
Nodes (5): open, promote, promoteDeployment, run, save

### Community 159 - "extra"
Cohesion: 0.67
Nodes (3): extra, laravel, dont-discover

### Community 173 - "Illuminate\Database\Migrations\Migration"
Cohesion: 0.12
Nodes (4): {closure#1}(), {closure#2}(), {closure#1}(), {closure#1}()

### Community 174 - "SimulationQueue"
Cohesion: 0.11
Nodes (4): RunBacktest, RunGrid, SimulationTool, Controller

### Community 178 - "UniverseStrategyTest.php"
Cohesion: 0.21
Nodes (18): CrossSectionalStrategy, {closure#10}(), {closure#12}(), {closure#15}(), {closure#16}(), {closure#18}(), {closure#3}(), {closure#4}() (+10 more)

### Community 181 - "SettingsScreenTest.php"
Cohesion: 0.18
Nodes (13): {closure#10}(), {closure#11}(), {closure#13}(), {closure#14}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}() (+5 more)

### Community 183 - "App\Strategy\Dsl\DslError"
Cohesion: 0.16
Nodes (14): {closure#2}(), {closure#1}(), KnownInstruments, {closure#1}(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}() (+6 more)

### Community 184 - "UniverseBacktestTest.php"
Cohesion: 0.21
Nodes (5): {closure#1}(), {closure#4}(), {closure#5}(), reportRows(), universeBacktest()

### Community 189 - "MarketType"
Cohesion: 0.11
Nodes (19): BinanceRestClient, {closure#2}(), {closure#3}(), {closure#4}(), EpochTime, Json, MarketType, Perp (+11 more)

### Community 190 - "PolicyLimits"
Cohesion: 0.11
Nodes (9): InstrumentDirectory, StoredInstruments, {closure#1}(), CompileStrategy, {closure#2}(), GridPart, SetOutcome, PolicyLimits (+1 more)

### Community 192 - "0001_01_01_000002_create_jobs_table.php"
Cohesion: 0.33
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

### Community 195 - "2026_11_09_000016_create_market_universe_tables.php"
Cohesion: 0.40
Nodes (3): {closure#1}(), {closure#2}(), up()

### Community 197 - "App\Agents\Mcp\TradingServer"
Cohesion: 0.13
Nodes (5): DslReference, GetFunding, ListInstruments, ListTrials, TradingServer

### Community 198 - "start-local.ps1"
Cohesion: 0.27
Nodes (4): Stop-Postgres(), Stop-ProcessTree(), Test-PostgresRunning(), Write-Step()

### Community 200 - "Symbol"
Cohesion: 0.05
Nodes (34): Venue, Balance, ClientOrderId, Income, IncomeKind, Funding, Liquidation, OcoRequest (+26 more)

### Community 202 - "0001_01_01_000000_create_users_table.php"
Cohesion: 0.33
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

### Community 203 - "MarketDataSettingsTest.php"
Cohesion: 0.09
Nodes (13): {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), savedEmaCross(), {closure#2}(), {closure#3}(), {closure#4}() (+5 more)

### Community 205 - "InstrumentRules"
Cohesion: 0.06
Nodes (41): InstrumentRules, {closure#1}(), RulesHistory, AccountView, RejectionReason, BelowMinimum, Breaker, Cooldown (+33 more)

### Community 208 - "App\Execution\Engine"
Cohesion: 0.08
Nodes (9): {closure#1}(), {closure#11}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#9}(), {closure#15}(), {closure#4}() (+1 more)

### Community 210 - "streamWith"
Cohesion: 0.19
Nodes (16): {closure#3}(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#7}(), {closure#8}() (+8 more)

### Community 213 - "CrossSection"
Cohesion: 0.25
Nodes (5): {closure#57}(), {closure#58}(), {closure#59}(), CrossSection, {closure#19}()

### Community 214 - "App"
Cohesion: 0.08
Nodes (29): SendTelegram, App, {closure#1}(), {closure#2}(), {closure#3}(), {closure#1}(), {closure#2}(), {closure#4}() (+21 more)

### Community 215 - "AppServiceProvider.php"
Cohesion: 0.07
Nodes (10): ArchiveCatalog, {closure#1}(), {closure#15}(), SystemSleeper, SyncInstruments, LocalDiskUsage, {closure#4}(), {closure#5}() (+2 more)

### Community 217 - "Illuminate\Support\Facades\DB"
Cohesion: 0.13
Nodes (10): {closure#6}(), {closure#7}(), {closure#9}(), {closure#1}(), {closure#2}(), {closure#5}(), {closure#6}(), universeGrid() (+2 more)

### Community 222 - "StrategyGatesTest.php"
Cohesion: 0.60
Nodes (4): {closure#1}(), {closure#2}(), {closure#3}(), gatedVersion()

## Knowledge Gaps
- **152 isolated node(s):** `Flatten`, `Liquidation`, `Signal`, `Stop`, `Target` (+147 more)
  These have ≤1 connection - possible missing edges. (Counts symbols only; 1006 node(s) total have ≤1 connection when file, concept and rationale nodes are included.)
- **64 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `Decimal` connect `Decimal` to `engineHarness`, `.encode`, `PreparesBacktests.php`, `Strategy`, `CompiledStrategy.php`, `.of`, `BinanceVenueTest.php`, `SimClock`, `.isPositive`, `Candle`, `Engine`, `Strategy`, `Ledger`, `App\Strategy\Dsl\Compiler\CompiledStrategy`, `Symbol`, `.of`, `App\Strategy\Contracts\Strategy`, `OrderManagerTest.php`, `InstrumentRules`, `Position`, `.times`, `Timeframe`, `WarmingVenue`, `UniverseStrategyTest.php`, `IndicatorsTest.php`, `IndicatorReferenceTest.php`, `PolicyLimits`, `.plus`, `.run`, `Kind`, `Quantity`, `Statistics`, `Symbol`, `.evaluate`, `InstrumentRules`, `App\Execution\Engine`, `.isPositive`, `ForwardingVenue`, `App\Support\Number\Decimal`, `AppServiceProvider.php`, `Carbon\CarbonImmutable`, `.fromStrings`, `.isGreaterThan`, `InvalidArgumentException`, `StrategyGates.php`, `StrategySpec`?**
  _High betweenness centrality (0.115) - this node is a cross-community bridge._
- **What connects `Flatten`, `Liquidation`, `Signal` to the rest of the system?**
  _152 weakly-connected nodes found - possible missing edges._
- **Should `engineHarness` be split into smaller, more focused modules?**
  _Cohesion score 0.056767676767676765 - nodes in this community are weakly interconnected._
- **Why does `Timeframe` connect `Timeframe` to `engineHarness`, `Strategy`, `DocumentChecker`, `CompiledStrategy.php`, `.of`, `Candle`, `.isPositive`, `CandleRepositoryTest.php`, `Instrument`, `App\Strategy\Dsl\Compiler\CompiledStrategy`, `Symbol`, `App\Strategy\Contracts\Strategy`, `OrderManagerTest.php`, `Position`, `Laravel\Mcp\Response`, `UniverseStrategyTest.php`, `ReadBack`, `IndicatorsTest.php`, `IndicatorReferenceTest.php`, `MarketType`, `App\Support\Clock\Clock`, `Statistics`, `Symbol`, `.isPositive`, `streamWith`, `Carbon\CarbonImmutable`, `.fromStrings`, `StrategySpec`?**
  _High betweenness centrality (0.032) - this node is a cross-community bridge._
- **Should `PreparesBacktests.php` be split into smaller, more focused modules?**
  _Cohesion score 0.06105834464043419 - nodes in this community are weakly interconnected._
- **Why does `StrategyIdentity` connect `Symbol` to `App\Strategy\Contracts\Strategy`, `engineHarness`, `Strategy`, `InstrumentRules`, `.of`, `Carbon\CarbonImmutable`?**
  _High betweenness centrality (0.026) - this node is a cross-community bridge._
- **Should `SettingsScreen` be split into smaller, more focused modules?**
  _Cohesion score 0.06666666666666667 - nodes in this community are weakly interconnected._