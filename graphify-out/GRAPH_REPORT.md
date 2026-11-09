# Graph Report - trading-platform  (2026-11-09)

## Corpus Check
- 642 files · ~269,717 words
- Verdict: corpus is large enough that graph structure adds value.
- Unclassified: 103 file(s) not represented in the graph (top: .zip 38, .CHECKSUM 38, (none) 19)

## Summary
- 5153 nodes · 17786 edges · 212 communities (152 shown, 60 thin omitted)
- Extraction: 91% EXTRACTED · 9% INFERRED · 0% AMBIGUOUS · INFERRED: 1623 edges (avg confidence: 0.85)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `c0e667a2`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- SimulatedPerpVenueTest.php
- engineHarness
- CanonicalJson
- DslError
- Engine
- Strategy
- DocumentChecker
- .now
- CompiledStrategy.php
- SimulatedVenueTest.php
- BinanceVenueTest.php
- .of
- Candle
- ExchangeVerificationTest.php
- App\Market\Instruments\Universe
- Illuminate\Contracts\Config\Repository
- Node
- Illuminate\Console\Command
- Instrument
- ScreensTest.php
- Ledger
- Illuminate\Database\Schema\Blueprint
- generate.py
- CompiledStrategy.php
- Symbol
- .times
- TerminalQrCodeTest.php
- RejectionReason
- .of
- Backfill
- RuntimeException
- oi-momentum-long.strategy.yaml
- App\Livewire\StrategiesScreen
- EnginePerpTest.php
- BinanceArchive
- LadderTest.php
- ScalpingCollectorTest.php
- LadderTest.php
- OrderManagerTest.php
- InstrumentRules
- App\Strategy\Dsl\Document\DocumentChecker
- ema-cross-scalp-long.strategy.yaml
- Decimal
- App\Market\Data\Candle
- App
- .walk
- PaperEngineTest.php
- package.json
- OrderBook
- BinanceRestClientTest.php
- ema-cross-scalp-short.strategy.yaml
- ChartScreen.php
- ReadBack
- Timeframe
- postgres
- .act
- CompiledStrategy
- donchian-breakout-short.strategy.yaml
- IndicatorsTest.php
- support.mjs
- RunBacktestCommandTest.php
- IndicatorReferenceTest.php
- App\Support\Clock\Clock
- Carbon\CarbonImmutable
- AppServiceProvider.php
- donchian-breakout.strategy.yaml
- .run
- DocumentCheckerTest.php
- Kind
- MarketBreakersTest.php
- ArchiveCatalog
- Illuminate\Contracts\Bus\Dispatcher
- SettingsScreen
- scripts
- ma-trend-short.strategy.yaml
- CandleFeedTest.php
- .measure
- composer.json
- jobs
- postgres
- ma-trend.strategy.yaml
- .isPositive
- MonteCarlo
- App\Support\Number\Decimal
- pint/composer.json
- App\Agents\Mcp\TradingServer
- RunSimulation.php
- .parse
- require-dev
- scripts-descriptions
- Venue
- .fromStrings
- config
- uses
- test
- with
- AppServiceProvider.php
- jobs
- ParserTest.php
- psr-4
- require
- DirectionalMovement
- StrategyGates.php
- uses
- .handle
- devDependencies
- Laravel\Mcp\Response
- ChartSeriesTest.php
- App\Market\Data\Candle
- .breaches
- buy-and-hold.strategy.yaml
- PreparesBacktests.php
- TokensTest.php
- ArchiveImportTest.php
- User
- Illuminate\Support\Facades\Schema
- App\Execution\Models\Account
- .register
- funding-carry.strategy.yaml
- McpServerTest.php
- env.mjs
- chart.js
- TelegramSettings
- Percent
- settings-screen.blade.php
- App\Simulation\StrategyLibrary
- steps
- 2026_11_09_000014_create_health_tables.php
- AuthenticateMcpToken.php
- App\Support\Symbol
- GridWorkersTest.php
- CandleRepositoryTest.php
- 2026_11_08_000005_create_simulation_jobs_table.php
- ResearchUniverse
- Illuminate\Database\Eloquent\Model
- scripts
- 2026_11_08_000007_create_strategy_gate_tables.php
- fake-telegram.mjs
- Illuminate\Database\Migrations\Migration
- PromoteStrategy.php
- 2026_11_08_000004_create_strategy_versions_tables.php
- 2026_11_08_000006_create_mcp_tables.php
- 2026_11_09_000017_create_live_tables.php
- .evaluate
- strategies-screen.blade.php
- extra
- app.blade.php
- halt
- remove
- account-screen.blade.php
- 0001_01_01_000001_create_cache_table.php
- DecimalMath
- .evaluate
- OrderManagerTest.php
- 2026_11_09_000027_add_to_to_market_jobs.php
- 2026_11_09_000012_create_account_results_tables.php
- 2026_11_09_000023_create_universe_members_table.php
- SettingsScreenTest.php
- .__construct
- CompiledStrategy
- 2026_11_09_000011_create_notification_settings_table.php
- RunEngine.php
- MarketType
- PolicyLimits
- 2026_11_09_000025_create_book_depth_table.php
- 0001_01_01_000002_create_jobs_table.php
- 2026_11_09_000009_create_exchange_keys_table.php
- 2026_11_09_000018_add_effective_at_to_deployments.php
- 2026_11_09_000016_create_market_universe_tables.php
- depthStreamWith
- LiveAccountTest.php
- start-local.ps1
- App\Execution\Journal\DatabaseDecisionLog
- Symbol
- SimulationQueue
- 0001_01_01_000000_create_users_table.php
- RuntimeException
- StrategyVersion
- Engine.php
- 2026_11_09_000020_add_lifetime_to_instruments.php
- ResearchUniverse.php
- 2026_11_09_000013_add_daily_report_to_notification_settings.php
- 2026_11_09_000026_create_liquidation_tables.php
- 2026_11_09_000022_create_daily_stats_table.php

## God Nodes (most connected - your core abstractions)
1. `Decimal` - 500 edges
2. `Side` - 205 edges
3. `Symbol` - 191 edges
4. `App` - 168 edges
5. `Candle` - 134 edges
6. `Timeframe` - 118 edges
7. `Price` - 100 edges
8. `Position` - 96 edges
9. `SimClock` - 87 edges
10. `Clock` - 73 edges

## Surprising Connections (you probably didn't know these)
- `{closure#10}()` --calls--> `App`  [INFERRED]
  tests/Feature/Market/ScalpingArchiveTest.php → app/Notifications/SettingSource.php
- `{closure#13}()` --calls--> `App`  [INFERRED]
  tests/Feature/Market/ScalpingArchiveTest.php → app/Notifications/SettingSource.php
- `{closure#6}()` --calls--> `App`  [INFERRED]
  tests/Feature/Market/ScalpingArchiveTest.php → app/Notifications/SettingSource.php
- `{closure#8}()` --calls--> `App`  [INFERRED]
  tests/Feature/Market/ScalpingArchiveTest.php → app/Notifications/SettingSource.php
- `{closure#13}()` --calls--> `App`  [INFERRED]
  tests/Feature/Web/SettingsScreenTest.php → app/Notifications/SettingSource.php

## Import Cycles
- None detected.

## Communities (212 total, 60 thin omitted)

### Community 0 - "SimulatedPerpVenueTest.php"
Cohesion: 0.17
Nodes (27): PriceMinute, {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#15}(), {closure#18}() (+19 more)

### Community 1 - "engineHarness"
Cohesion: 0.11
Nodes (40): EngineCommand, EngineMode, Backtest, Live, Paper, Testnet, ScriptedStrategy, {closure#10}() (+32 more)

### Community 2 - "CanonicalJson"
Cohesion: 0.18
Nodes (7): RunBacktest, CanonicalJson, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}()

### Community 3 - "DslError"
Cohesion: 0.11
Nodes (15): {closure#1}(), {closure#1}(), {closure#5}(), {closure#8}(), DslError, YamlList, YamlReader, YamlScalar (+7 more)

### Community 4 - "Engine"
Cohesion: 0.13
Nodes (5): EngineRecord, Engine, ReconciliationMismatch, {closure#3}(), {closure#1}()

### Community 5 - "Strategy"
Cohesion: 0.05
Nodes (70): LimitedStrategy, Strategy, PositionView, StrategyContext, baselineDocument(), baselinePairs(), {closure#10}(), {closure#11}() (+62 more)

### Community 7 - ".now"
Cohesion: 0.14
Nodes (7): {closure#1}(), {closure#2}(), Deployment, {closure#1}(), {closure#1}(), Ladder, LadderStatus

### Community 8 - "CompiledStrategy.php"
Cohesion: 0.14
Nodes (5): {closure#47}(), {closure#48}(), {closure#50}(), Compiler, Program

### Community 9 - "SimulatedVenueTest.php"
Cohesion: 0.10
Nodes (68): Side, Buy, Sell, {closure#9}(), btc(), {closure#10}(), {closure#11}(), {closure#12}() (+60 more)

### Community 10 - "BinanceVenueTest.php"
Cohesion: 0.09
Nodes (22): {closure#2}(), OrderType, Limit, Market, StopMarket, TakeProfitMarket, BinanceVenue, BinanceApiError (+14 more)

### Community 11 - ".of"
Cohesion: 0.10
Nodes (21): AssetMismatch, {closure#2}(), {closure#40}(), {closure#1}(), usdt(), {closure#11}(), {closure#3}(), {closure#6}() (+13 more)

### Community 12 - "Candle"
Cohesion: 0.11
Nodes (31): {closure#1}(), {closure#3}(), Candle, InvalidCandle, {closure#1}(), {closure#2}(), {closure#42}(), candleWith() (+23 more)

### Community 13 - "ExchangeVerificationTest.php"
Cohesion: 0.15
Nodes (23): {closure#12}(), {closure#2}(), {closure#3}(), {closure#5}(), {closure#7}(), {closure#9}(), verifiedInstrument(), {closure#1}() (+15 more)

### Community 14 - "App\Market\Instruments\Universe"
Cohesion: 0.08
Nodes (11): CheckMarketData, SelectsInstruments, {closure#1}(), {closure#2}(), StreamCandles, VerifyMarketData, {closure#1}(), {closure#2}() (+3 more)

### Community 15 - "Illuminate\Contracts\Config\Repository"
Cohesion: 0.13
Nodes (5): {closure#1}(), {closure#2}(), ExportStrategies, ImportStrategies, PreparesBacktests

### Community 16 - "Node"
Cohesion: 0.14
Nodes (20): Binary, Parser, Token, TokenKind, Colon, Comma, Dot, Duration (+12 more)

### Community 17 - "Illuminate\Console\Command"
Cohesion: 0.08
Nodes (16): FlattenEngine, HaltEngine, IssuesCommands, PauseStrategy, ResumeEngine, CommandKind, Flatten, Halt (+8 more)

### Community 18 - "Instrument"
Cohesion: 0.07
Nodes (18): BackfillStep, CandleRepository, {closure#1}(), CandleSource, Archive, Rest, Stream, UpsertResult (+10 more)

### Community 19 - "ScreensTest.php"
Cohesion: 0.14
Nodes (23): {closure#1}(), {closure#1}(), chartLiveQuery(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}() (+15 more)

### Community 20 - "Ledger"
Cohesion: 0.08
Nodes (57): ExitReason, Flatten, Liquidation, Signal, Stop, Target, Ledger, LedgerViolation (+49 more)

### Community 21 - "Illuminate\Database\Schema\Blueprint"
Cohesion: 0.14
Nodes (13): {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#1}(), {closure#2}(), {closure#3}() (+5 more)

### Community 22 - "generate.py"
Cohesion: 0.07
Nodes (30): atr(), bars(), bb(), change(), dmi(), donchian(), ema(), highest() (+22 more)

### Community 23 - "CompiledStrategy.php"
Cohesion: 0.09
Nodes (14): {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}(), {closure#8}() (+6 more)

### Community 24 - "Symbol"
Cohesion: 0.04
Nodes (21): BuyAndHold, DonchianBreakout, FundingCarry, MovingAverageTrend, BuildableStrategy, Close, EntryOrder, Intent (+13 more)

### Community 25 - ".times"
Cohesion: 0.06
Nodes (14): PerpPosition, Position, {closure#4}(), Perpetual, {closure#1}(), {closure#3}(), goldenMinutes(), goldenRates() (+6 more)

### Community 26 - "TerminalQrCodeTest.php"
Cohesion: 0.20
Nodes (7): TerminalQrCode, {closure#3}(), {closure#4}(), {closure#5}(), expectedModules(), readColoured(), readPlain()

### Community 27 - "RejectionReason"
Cohesion: 0.07
Nodes (29): AccountView, RejectionReason, BelowMinimum, Breaker, Cooldown, DuplicateIntent, EntryWorking, GrossExposure (+21 more)

### Community 28 - ".of"
Cohesion: 0.19
Nodes (43): RiskState, account(), accountPolicy(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}() (+35 more)

### Community 29 - "Backfill"
Cohesion: 0.16
Nodes (4): ArchivePeriod, Backfill, BackfillReport, BackfillMarketData

### Community 31 - "oi-momentum-long.strategy.yaml"
Cohesion: 0.07
Nodes (27): entries, long, exits, stop_loss, take_profit, when, fails_when, family (+19 more)

### Community 32 - "App\Livewire\StrategiesScreen"
Cohesion: 0.09
Nodes (8): Account, ReauthController, AccountScreen, ChartScreen, HaltButton, {closure#1}(), ModeBadge, {closure#2}()

### Community 33 - "EnginePerpTest.php"
Cohesion: 0.19
Nodes (17): InMemoryDecisionLog, {closure#1}(), {closure#10}(), {closure#11}(), {closure#13}(), {closure#14}(), {closure#2}(), {closure#5}() (+9 more)

### Community 34 - "BinanceArchive"
Cohesion: 0.13
Nodes (6): BinanceArchive, {closure#14}(), ExchangeNumber, TradeFlowBuilder, {closure#1}(), {closure#2}()

### Community 35 - "LadderTest.php"
Cohesion: 0.23
Nodes (10): {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#8}(), healthAt(), healthyMachine() (+2 more)

### Community 36 - "ScalpingCollectorTest.php"
Cohesion: 0.13
Nodes (18): WallClock, aggTrade(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#6}(), collector() (+10 more)

### Community 37 - "LadderTest.php"
Cohesion: 0.13
Nodes (25): {closure#4}(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}() (+17 more)

### Community 38 - "OrderManagerTest.php"
Cohesion: 0.11
Nodes (17): OrderRole, Entry, Exit, Flatten, Stop, Target, ProtectionPlan, TrackedOrder (+9 more)

### Community 39 - "InstrumentRules"
Cohesion: 0.07
Nodes (39): FeeSchedule, CostModel, CandleFeed, FundingRate, InstrumentRules, RulesHistory, RiskPolicy, Backtest (+31 more)

### Community 40 - "App\Strategy\Dsl\Document\DocumentChecker"
Cohesion: 0.08
Nodes (25): Argument, BoolLiteral, Call, DurationLiteral, Identifier, Lookback, Node, NumberLiteral (+17 more)

### Community 41 - "ema-cross-scalp-long.strategy.yaml"
Cohesion: 0.06
Nodes (33): entries, long, exits, stop_loss, take_profit, time_stop, when, fails_when (+25 more)

### Community 42 - "Decimal"
Cohesion: 0.04
Nodes (49): {closure#6}(), RoundTrip, {closure#1}(), {closure#2}(), {closure#3}(), {closure#10}(), {closure#5}(), {closure#4}() (+41 more)

### Community 43 - "App\Market\Data\Candle"
Cohesion: 0.07
Nodes (20): Liquidity, Maker, Taker, OrderStatus, Cancelled, Expired, Filled, New (+12 more)

### Community 44 - "App"
Cohesion: 0.06
Nodes (39): SendTelegram, App, {closure#1}(), {closure#2}(), {closure#3}(), {closure#2}(), {closure#4}(), {closure#5}() (+31 more)

### Community 45 - ".walk"
Cohesion: 0.16
Nodes (19): CandleAggregator, CandleSequenceBroken, {closure#3}(), {closure#7}(), CandleFactory, aggregate(), {closure#1}(), {closure#10}() (+11 more)

### Community 46 - "PaperEngineTest.php"
Cohesion: 0.11
Nodes (21): {closure#5}(), AccountEquity, AccountTrade, EngineCommandRow, {closure#1}(), {closure#2}(), {closure#3}(), {closure#1}() (+13 more)

### Community 47 - "package.json"
Cohesion: 0.15
Nodes (17): dependencies, codemirror@^6.0.2, @codemirror/lang-yaml@^6.1.3, @codemirror/language@^6.13.1, @lezer/highlight@^1.2.5, lightweight-charts@^5.2.1, optionalDependencies, @laravel/multiplex@^0.4.1 (+9 more)

### Community 48 - "OrderBook"
Cohesion: 0.16
Nodes (8): DepthUpdate, OrderBook, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), levels(), update()

### Community 49 - "BinanceRestClientTest.php"
Cohesion: 0.08
Nodes (31): {closure#6}(), {closure#7}(), {closure#1}(), {closure#3}(), binanceClient(), {closure#1}(), {closure#11}(), {closure#13}() (+23 more)

### Community 50 - "ema-cross-scalp-short.strategy.yaml"
Cohesion: 0.06
Nodes (33): entries, short, exits, stop_loss, take_profit, time_stop, when, fails_when (+25 more)

### Community 51 - "ChartScreen.php"
Cohesion: 0.05
Nodes (35): {closure#2}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), EntrySpec, Expression, FunctionSpec (+27 more)

### Community 53 - "Timeframe"
Cohesion: 0.10
Nodes (16): CarryTemplate, {closure#2}(), {closure#8}(), Timeframe, FifteenMinutes, FiveMinutes, FourHours, OneDay (+8 more)

### Community 54 - "postgres"
Cohesion: 0.10
Nodes (19): POSTGRES_DB, POSTGRES_PASSWORD, POSTGRES_USER, interval, retries, test, timeout, environment (+11 more)

### Community 56 - "CompiledStrategy"
Cohesion: 0.11
Nodes (12): SizedStrategy, {closure#1}(), {closure#2}(), {closure#1}(), {closure#2}(), __construct(), enterOnce(), identity() (+4 more)

### Community 57 - "donchian-breakout-short.strategy.yaml"
Cohesion: 0.07
Nodes (26): entries, short, default, range, step, default, range, step (+18 more)

### Community 58 - "IndicatorsTest.php"
Cohesion: 0.08
Nodes (25): Atr, Donchian, Ema, Sma, VolumeProfile, {closure#10}(), {closure#5}(), fiveMinuteBars() (+17 more)

### Community 59 - "support.mjs"
Cohesion: 0.26
Nodes (9): @playwright/test, confirmed(), checkLayout(), EMAIL, freshCode(), PASSWORD, reauthenticate(), totp() (+1 more)

### Community 60 - "RunBacktestCommandTest.php"
Cohesion: 0.09
Nodes (33): BacktestRun, {closure#1}(), {closure#4}(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}() (+25 more)

### Community 61 - "IndicatorReferenceTest.php"
Cohesion: 0.03
Nodes (35): {closure#60}(), BarIndicator, Bollinger, Change, DonchianChannel, Highest, Hma, Keltner (+27 more)

### Community 62 - "App\Support\Clock\Clock"
Cohesion: 0.05
Nodes (13): BinanceEndpoints, Sleeper, DepthStream, FormingCandles, FundingRateRepository, Heartbeats, LiquidationRepository, CandleStream (+5 more)

### Community 63 - "Carbon\CarbonImmutable"
Cohesion: 0.07
Nodes (26): {closure#1}(), {closure#9}(), {closure#2}(), {closure#4}(), BookDepth, FormingCandle, Liquidations, OpenInterest (+18 more)

### Community 64 - "AppServiceProvider.php"
Cohesion: 0.06
Nodes (16): ChartLiveController, SystemSleeper, LocalDiskUsage, AppServiceProvider, {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}() (+8 more)

### Community 65 - "donchian-breakout.strategy.yaml"
Cohesion: 0.07
Nodes (26): entries, long, default, range, step, default, range, step (+18 more)

### Community 66 - ".run"
Cohesion: 0.08
Nodes (26): RiskProfile, Account, Benchmark, PerformanceMetrics, Report, {closure#1}(), {closure#2}(), {closure#3}() (+18 more)

### Community 67 - "DocumentCheckerTest.php"
Cohesion: 0.30
Nodes (10): checkDocument(), {closure#1}(), {closure#2}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}(), {closure#8}() (+2 more)

### Community 68 - "Kind"
Cohesion: 0.07
Nodes (31): {closure#3}(), {closure#4}(), {closure#7}(), {closure#9}(), ParameterSpec, Library, Scope, {closure#4}() (+23 more)

### Community 69 - "MarketBreakersTest.php"
Cohesion: 0.21
Nodes (7): MinuteCandles, BreakerEvent, MarketBreakers, breakerMinute(), {closure#1}(), {closure#2}(), {closure#3}()

### Community 70 - "ArchiveCatalog"
Cohesion: 0.10
Nodes (8): ArchiveCatalog, FillDailyStats, SyncInstruments, DailyStats, DailyHistory, {closure#1}(), InstrumentSync, SyncResult

### Community 71 - "Illuminate\Contracts\Bus\Dispatcher"
Cohesion: 0.15
Nodes (5): {closure#4}(), Notifier, SendDailyReport, DailyReport, TelegramNotifier

### Community 72 - "SettingsScreen"
Cohesion: 0.09
Nodes (5): CreateMcpToken, RevokeMcpToken, McpToken, Tokens, SettingsScreen

### Community 73 - "scripts"
Cohesion: 0.15
Nodes (13): scripts, analyse, dev, format, mutate, post-autoload-dump, post-update-cmd, pre-package-uninstall (+5 more)

### Community 74 - "ma-trend-short.strategy.yaml"
Cohesion: 0.07
Nodes (26): entries, short, exits, stop_loss, when, fails_when, family, default (+18 more)

### Community 75 - "CandleFeedTest.php"
Cohesion: 0.21
Nodes (16): DatabaseCandleFeed, {closure#1}(), {closure#10}(), {closure#13}(), {closure#14}(), {closure#15}(), {closure#2}(), {closure#3}() (+8 more)

### Community 76 - ".measure"
Cohesion: 0.09
Nodes (18): EquityTracker, Performance, Statistics, {closure#1}(), {closure#2}(), {closure#3}(), {closure#1}(), {closure#2}() (+10 more)

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
Cohesion: 0.15
Nodes (16): DeflatedSharpe, Normal, closeTo(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#2}() (+8 more)

### Community 82 - "MonteCarlo"
Cohesion: 0.15
Nodes (11): {closure#4}(), {closure#5}(), MonteCarlo, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}() (+3 more)

### Community 83 - "App\Support\Number\Decimal"
Cohesion: 0.07
Nodes (58): {closure#3}(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#15}() (+50 more)

### Community 84 - "pint/composer.json"
Cohesion: 0.20
Nodes (9): config, sort-packages, description, license, name, require, laravel/pint, php (+1 more)

### Community 85 - "App\Agents\Mcp\TradingServer"
Cohesion: 0.14
Nodes (6): ChartTool, DslReference, GetCandles, GetIndicators, ListInstruments, TradingServer

### Community 86 - "RunSimulation.php"
Cohesion: 0.13
Nodes (3): ImportMarketData, RunSimulation, SimulationJob

### Community 87 - ".parse"
Cohesion: 0.33
Nodes (6): KlineMessage, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), streamLines()

### Community 88 - "require-dev"
Cohesion: 0.22
Nodes (9): require-dev, fakerphp/faker, larastan/larastan, laravel/pail, laravel/pao, mockery/mockery, nunomaduro/collision, pestphp/pest (+1 more)

### Community 89 - "scripts-descriptions"
Cohesion: 0.25
Nodes (8): scripts-descriptions, analyse, format, mutate, test, test:network, test:testnet, tools:install

### Community 90 - "Venue"
Cohesion: 0.06
Nodes (11): Venue, OrderAck, WarmingVenue, ForwardingVenue, {closure#12}(), balances(), {closure#22}(), {closure#32}() (+3 more)

### Community 91 - ".fromStrings"
Cohesion: 0.18
Nodes (7): RegimeLabels, {closure#1}(), {closure#2}(), {closure#4}(), {closure#7}(), {closure#9}(), days()

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

### Community 96 - "AppServiceProvider.php"
Cohesion: 0.13
Nodes (5): DiskUsage, {closure#1}(), HealthAlerts, HealthChecks, HealthResult

### Community 97 - "jobs"
Cohesion: 0.25
Nodes (8): name, runs-on, name, runs-on, steps, jobs, analyse, audit

### Community 98 - "ParserTest.php"
Cohesion: 0.21
Nodes (5): AstPrinter, Lexer, {closure#1}(), {closure#4}(), sexpr()

### Community 99 - "psr-4"
Cohesion: 0.40
Nodes (5): autoload, psr-4, App\\, Database\\Factories\\, Database\\Seeders\\

### Community 100 - "require"
Cohesion: 0.22
Nodes (9): require, bacon/bacon-qr-code, laravel/fortify, laravel/framework, laravel/mcp, laravel/tinker, livewire/livewire, php (+1 more)

### Community 103 - "StrategyGates.php"
Cohesion: 0.07
Nodes (20): {closure#11}(), {closure#12}(), {closure#2}(), {closure#3}(), {closure#6}(), {closure#8}(), {closure#2}(), GridPart (+12 more)

### Community 104 - "uses"
Cohesion: 0.21
Nodes (12): steps, name, runs-on, steps, frontend, lint, name, runs-on (+4 more)

### Community 105 - ".handle"
Cohesion: 0.13
Nodes (5): GridBacktest, ReadsGridOptions, StrategyGates, ExitSpec, StrategySpec

### Community 106 - "devDependencies"
Cohesion: 0.18
Nodes (12): devDependencies, concurrently@^10.0.3, laravel-vite-plugin@^3.1, @playwright/test@1.63.0, tailwindcss@^4.0.0, @tailwindcss/vite@^4.0.0, vite@^8.0.0, concurrently (+4 more)

### Community 108 - "Laravel\Mcp\Response"
Cohesion: 0.11
Nodes (10): GetFunding, GetJob, GetStrategy, ListStrategies, {closure#1}(), ListTrials, SaveStrategy, TradingTool (+2 more)

### Community 117 - "ChartSeriesTest.php"
Cohesion: 0.24
Nodes (8): chart(), {closure#1}(), {closure#11}(), {closure#2}(), {closure#3}(), {closure#6}(), {closure#7}(), {closure#9}()

### Community 118 - "App\Market\Data\Candle"
Cohesion: 0.05
Nodes (37): {closure#1}(), {closure#2}(), {closure#15}(), {closure#3}(), {closure#5}(), {closure#7}(), {closure#1}(), {closure#2}() (+29 more)

### Community 119 - ".breaches"
Cohesion: 0.29
Nodes (10): Band, bandReport(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#7}() (+2 more)

### Community 120 - "buy-and-hold.strategy.yaml"
Cohesion: 0.12
Nodes (16): entries, long, exits, stop_loss, fails_when, family, hypothesis, when (+8 more)

### Community 121 - "PreparesBacktests.php"
Cohesion: 0.07
Nodes (16): {closure#1}(), {closure#2}(), {closure#3}(), {closure#2}(), {closure#1}(), ChartHistoryMissing, {closure#1}(), {closure#3}() (+8 more)

### Community 122 - "TokensTest.php"
Cohesion: 0.31
Nodes (6): {closure#3}(), {closure#4}(), contrastRatio(), contrastRules(), relativeLuminance(), themeTokens()

### Community 123 - "ArchiveImportTest.php"
Cohesion: 0.29
Nodes (10): archiveBytes(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}() (+2 more)

### Community 124 - "User"
Cohesion: 0.10
Nodes (3): User, UserFactory, {closure#1}()

### Community 125 - "Illuminate\Support\Facades\Schema"
Cohesion: 0.14
Nodes (4): {closure#1}(), {closure#1}(), {closure#2}(), {closure#1}()

### Community 126 - "App\Execution\Models\Account"
Cohesion: 0.09
Nodes (11): LiveGate, OpenAccount, ApproveLive, ConfirmsOperator, CreateAccount, StoreKeys, LiveApproval, {closure#2}() (+3 more)

### Community 127 - ".register"
Cohesion: 0.05
Nodes (65): SimClock, {closure#9}(), {closure#1}(), {closure#1}(), {closure#4}(), {closure#10}(), {closure#12}(), {closure#13}() (+57 more)

### Community 128 - "funding-carry.strategy.yaml"
Cohesion: 0.12
Nodes (15): fails_when, family, hypothesis, params, enter_when, exit_when, margin_buffer, max_leverage_perp (+7 more)

### Community 129 - "McpServerTest.php"
Cohesion: 0.30
Nodes (11): actAsToken(), {closure#10}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}() (+3 more)

### Community 130 - "env.mjs"
Cohesion: 0.27
Nodes (4): sizes, BASE_URL, E2E_ENV, TELEGRAM_URL

### Community 131 - "chart.js"
Cohesion: 0.27
Nodes (7): faded(), options(), priceChart(), token(), volumeColor(), dslEditor(), highlight

### Community 132 - "TelegramSettings"
Cohesion: 0.15
Nodes (6): NotificationSettings, SettingSource, Env, None, TelegramClient, TelegramSettings

### Community 133 - "Percent"
Cohesion: 0.24
Nodes (3): Percent, {closure#14}(), {closure#15}()

### Community 135 - "settings-screen.blade.php"
Cohesion: 0.15
Nodes (12): approveLive, confirm, forgetKey, forgetTelegram, importUniverse, monitor, notify, removeInstrument (+4 more)

### Community 137 - "App\Simulation\StrategyLibrary"
Cohesion: 0.25
Nodes (3): CompileStrategy, StrategiesScreen, StrategyLibrary

### Community 138 - "steps"
Cohesion: 0.33
Nodes (6): name, runs-on, services, steps, e2e, if

### Community 139 - "2026_11_09_000014_create_health_tables.php"
Cohesion: 0.33
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

### Community 141 - "App\Support\Symbol"
Cohesion: 0.07
Nodes (20): {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#2}(), {closure#4}(), {closure#6}(), {closure#8}() (+12 more)

### Community 143 - "CandleRepositoryTest.php"
Cohesion: 0.31
Nodes (11): candleStore(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}() (+3 more)

### Community 145 - "ResearchUniverse"
Cohesion: 0.12
Nodes (5): BuildUniverse, HistoryImports, ResearchUniverse, MarketJob, {closure#8}()

### Community 146 - "Illuminate\Database\Eloquent\Model"
Cohesion: 0.07
Nodes (8): McpCall, AccountSnapshot, PlainDecimal, {closure#1}(), InstrumentFilter, UniverseEntry, StrategyHoldout, StrategyStage

### Community 147 - "scripts"
Cohesion: 0.50
Nodes (4): scripts, build, dev, e2e

### Community 155 - "2026_11_09_000017_create_live_tables.php"
Cohesion: 0.33
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

### Community 156 - ".evaluate"
Cohesion: 0.29
Nodes (7): WalkForward, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), wfSet()

### Community 157 - "strategies-screen.blade.php"
Cohesion: 0.33
Nodes (5): open, promote, promoteDeployment, run, save

### Community 159 - "extra"
Cohesion: 0.67
Nodes (3): extra, laravel, dont-discover

### Community 174 - "DecimalMath"
Cohesion: 0.14
Nodes (14): DecimalMath, {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#2}() (+6 more)

### Community 175 - ".evaluate"
Cohesion: 0.24
Nodes (9): Gates, brokenGate(), {closure#1}(), {closure#3}(), {closure#4}(), {closure#6}(), {closure#7}(), gate() (+1 more)

### Community 176 - "OrderManagerTest.php"
Cohesion: 0.28
Nodes (15): btcUsdt(), {closure#10}(), {closure#14}(), {closure#15}(), {closure#16}(), {closure#2}(), {closure#4}(), {closure#5}() (+7 more)

### Community 181 - "SettingsScreenTest.php"
Cohesion: 0.18
Nodes (13): {closure#10}(), {closure#11}(), {closure#13}(), {closure#14}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}() (+5 more)

### Community 183 - "CompiledStrategy"
Cohesion: 0.14
Nodes (9): CompiledStrategy, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#7}(), readBackDocument() (+1 more)

### Community 185 - "RunEngine.php"
Cohesion: 0.14
Nodes (4): AccountRuntime, RunEngine, ReplayDivergence, {closure#2}()

### Community 189 - "MarketType"
Cohesion: 0.08
Nodes (23): BinanceRestClient, {closure#3}(), {closure#4}(), EpochTime, Json, RequestFailed, InstrumentDefinition, InstrumentStatus (+15 more)

### Community 190 - "PolicyLimits"
Cohesion: 0.09
Nodes (14): InstrumentDirectory, StoredInstruments, {closure#1}(), CompileStrategy, CheckResult, PolicyLimits, SaveResult, StrategyRepository (+6 more)

### Community 192 - "0001_01_01_000002_create_jobs_table.php"
Cohesion: 0.33
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

### Community 195 - "2026_11_09_000016_create_market_universe_tables.php"
Cohesion: 0.40
Nodes (3): {closure#1}(), {closure#2}(), up()

### Community 196 - "depthStreamWith"
Cohesion: 0.20
Nodes (6): {closure#1}(), {closure#2}(), depthMessage(), depthStreamWith(), FakeTransport, RecordingSleeper

### Community 197 - "LiveAccountTest.php"
Cohesion: 0.16
Nodes (15): ExchangeKey, approveLive(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#8}(), {closure#9}() (+7 more)

### Community 198 - "start-local.ps1"
Cohesion: 0.27
Nodes (4): Stop-Postgres(), Stop-ProcessTree(), Test-PostgresRunning(), Write-Step()

### Community 199 - "App\Execution\Journal\DatabaseDecisionLog"
Cohesion: 0.14
Nodes (6): DecisionLog, DecisionRecord, IntentRecord, {closure#1}(), DatabaseDecisionLog, JournalEntry

### Community 200 - "Symbol"
Cohesion: 0.05
Nodes (31): Balance, ClientOrderId, Income, IncomeKind, Funding, Liquidation, OcoRequest, OrderRequest (+23 more)

### Community 201 - "SimulationQueue"
Cohesion: 0.10
Nodes (5): RunBacktest, RunGrid, SimulationTool, Controller, SimulationQueue

### Community 202 - "0001_01_01_000000_create_users_table.php"
Cohesion: 0.33
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

### Community 203 - "RuntimeException"
Cohesion: 0.18
Nodes (6): ArchiveMissing, ChecksumMismatch, BinanceRequestFailed, LocationRestricted, RateLimited, OrderBookOutOfSync

### Community 206 - "StrategyVersion"
Cohesion: 0.42
Nodes (3): {closure#1}(), {closure#3}(), StrategyVersion

### Community 208 - "Engine.php"
Cohesion: 0.07
Nodes (14): {closure#1}(), {closure#11}(), {closure#12}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#9}(), RiskEvent (+6 more)

## Knowledge Gaps
- **150 isolated node(s):** `Env`, `None`, `approveLive`, `confirm`, `forgetKey` (+145 more)
  These have ≤1 connection - possible missing edges. (Counts symbols only; 978 node(s) total have ≤1 connection when file, concept and rationale nodes are included.)
- **60 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `Decimal` connect `Decimal` to `engineHarness`, `CanonicalJson`, `DslError`, `Engine`, `Strategy`, `DocumentChecker`, `Percent`, `SimulatedVenueTest.php`, `BinanceVenueTest.php`, `.of`, `Candle`, `Instrument`, `Ledger`, `CompiledStrategy.php`, `Symbol`, `.times`, `RejectionReason`, `.of`, `.evaluate`, `EnginePerpTest.php`, `OrderManagerTest.php`, `InstrumentRules`, `App\Strategy\Dsl\Document\DocumentChecker`, `App\Market\Data\Candle`, `.walk`, `DecimalMath`, `.evaluate`, `OrderManagerTest.php`, `Timeframe`, `.__construct`, `.act`, `CompiledStrategy`, `CompiledStrategy`, `IndicatorsTest.php`, `RunBacktestCommandTest.php`, `IndicatorReferenceTest.php`, `PolicyLimits`, `Carbon\CarbonImmutable`, `.run`, `DocumentCheckerTest.php`, `Kind`, `Symbol`, `.measure`, `Engine.php`, `.isPositive`, `MonteCarlo`, `App\Support\Number\Decimal`, `Venue`, `.fromStrings`, `DirectionalMovement`, `StrategyGates.php`, `.handle`, `ChartSeriesTest.php`, `App\Market\Data\Candle`, `PreparesBacktests.php`?**
  _High betweenness centrality (0.106) - this node is a cross-community bridge._
- **What connects `Env`, `None`, `approveLive` to the rest of the system?**
  _150 weakly-connected nodes found - possible missing edges._
- **Should `engineHarness` be split into smaller, more focused modules?**
  _Cohesion score 0.10677966101694915 - nodes in this community are weakly interconnected._
- **Why does `Symbol` connect `Symbol` to `engineHarness`, `CanonicalJson`, `DslError`, `Engine`, `Strategy`, `DocumentChecker`, `SimulatedVenueTest.php`, `BinanceVenueTest.php`, `App\Support\Symbol`, `App\Market\Instruments\Universe`, `CandleRepositoryTest.php`, `Instrument`, `Ledger`, `CompiledStrategy.php`, `Symbol`, `.times`, `.of`, `EnginePerpTest.php`, `OrderManagerTest.php`, `InstrumentRules`, `Decimal`, `App\Market\Data\Candle`, `OrderManagerTest.php`, `Timeframe`, `.__construct`, `.act`, `CompiledStrategy`, `RunEngine.php`, `RunBacktestCommandTest.php`, `PolicyLimits`, `.run`, `App\Execution\Journal\DatabaseDecisionLog`, `Engine.php`, `Venue`, `StrategyGates.php`, `.handle`, `Laravel\Mcp\Response`, `ChartSeriesTest.php`, `PreparesBacktests.php`, `App\Execution\Models\Account`, `.register`?**
  _High betweenness centrality (0.038) - this node is a cross-community bridge._
- **Should `DslError` be split into smaller, more focused modules?**
  _Cohesion score 0.11470985155195682 - nodes in this community are weakly interconnected._
- **Why does `Timeframe` connect `Timeframe` to `SimulatedPerpVenueTest.php`, `engineHarness`, `DslError`, `Strategy`, `DocumentChecker`, `CompiledStrategy.php`, `SimulatedVenueTest.php`, `Candle`, `CandleRepositoryTest.php`, `Instrument`, `CompiledStrategy.php`, `Symbol`, `EnginePerpTest.php`, `App\Strategy\Dsl\Document\DocumentChecker`, `.walk`, `OrderManagerTest.php`, `Tests\Support\CandleFactory`, `ReadBack`, `.__construct`, `CompiledStrategy`, `IndicatorsTest.php`, `MarketType`, `App\Support\Clock\Clock`, `IndicatorReferenceTest.php`, `DocumentCheckerTest.php`, `Kind`, `MarketBreakersTest.php`, `CandleFeedTest.php`, `.measure`, `.fromStrings`, `StrategyGates.php`, `.handle`, `Laravel\Mcp\Response`, `ChartSeriesTest.php`, `App\Market\Data\Candle`?**
  _High betweenness centrality (0.020) - this node is a cross-community bridge._
- **Should `Engine` be split into smaller, more focused modules?**
  _Cohesion score 0.13333333333333333 - nodes in this community are weakly interconnected._