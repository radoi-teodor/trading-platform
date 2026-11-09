# Graph Report - trading-platform  (2026-11-09)

## Corpus Check
- 624 files · ~262,666 words
- Verdict: corpus is large enough that graph structure adds value.
- Unclassified: 103 file(s) not represented in the graph (top: .zip 38, .CHECKSUM 38, (none) 19)

## Summary
- 5009 nodes · 17244 edges · 212 communities (159 shown, 53 thin omitted)
- Extraction: 91% EXTRACTED · 9% INFERRED · 0% AMBIGUOUS · INFERRED: 1579 edges (avg confidence: 0.85)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `96f7690b`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- CompiledStrategyTest.php
- engineHarness
- CanonicalJson
- DslError
- Engine
- .parse
- DocumentChecker
- .now
- CompiledStrategy.php
- Side
- BinanceVenueTest.php
- .of
- Candle
- ExchangeVerificationTest.php
- Quantity
- Illuminate\Contracts\Config\Repository
- Node
- Clock
- Instrument
- ScreensTest.php
- LedgerTest.php
- Illuminate\Database\Schema\Blueprint
- generate.py
- App\Strategy\Dsl\Compiler\CompiledStrategy
- Symbol
- Illuminate\Support\Facades\Http
- TerminalQrCodeTest.php
- ClientOrderId
- .of
- SimulatedPerpVenueTest.php
- RuntimeException
- oi-momentum-long.strategy.yaml
- RuntimeException
- EnginePerpTest.php
- BinanceArchive
- LadderTest.php
- SimClock
- App\Support\Clock\Clock
- OrderManagerTest.php
- BacktestTest.php
- App\Strategy\Dsl\Document\DocumentChecker
- ema-cross-scalp-long.strategy.yaml
- Decimal
- .zero
- App
- Timeframe
- PaperEngineTest.php
- package.json
- RiskConfigTest.php
- BinanceRestClientTest.php
- ema-cross-scalp-short.strategy.yaml
- App\Livewire\SettingsScreen
- ReadBack
- SettingsScreen
- postgres
- App\Support\Symbol
- SizedStrategyTest.php
- donchian-breakout-short.strategy.yaml
- IndicatorsTest.php
- support.mjs
- RunBacktestCommandTest.php
- IndicatorReferenceTest.php
- ScalpingCollector
- Illuminate\Database\ConnectionInterface
- AppServiceProvider.php
- donchian-breakout.strategy.yaml
- .run
- Strategy
- App\Strategy\Dsl\Document\DocumentChecker
- Symbol
- DailyHistory.php
- App\Execution\Models\Account
- ExitReason
- scripts
- ma-trend-short.strategy.yaml
- .breaches
- .measure
- composer.json
- jobs
- postgres
- ma-trend.strategy.yaml
- DecimalMath
- MonteCarlo
- App\Support\Number\Decimal
- pint/composer.json
- TradingTool
- RunSimulation.php
- DocumentCheckerTest.php
- require-dev
- scripts-descriptions
- Carbon\CarbonImmutable
- .fromStrings
- config
- uses
- test
- with
- HealthChecks
- jobs
- App\Strategy\Dsl\Document\DocumentChecker
- psr-4
- require
- Illuminate\Http\Request
- StrategyGates.php
- uses
- .walk
- devDependencies
- Laravel\Mcp\Response
- App\Strategy\Dsl\TypeChecker
- ScalpingFunctionsTest.php
- RulesHistoryTest.php
- buy-and-hold.strategy.yaml
- ChartSeriesTest.php
- TokensTest.php
- ArchiveImportTest.php
- App\Http\Controllers\ReauthController
- Illuminate\Database\Migrations\Migration
- LiveGate
- .register
- funding-carry.strategy.yaml
- McpServerTest.php
- env.mjs
- chart.js
- TelegramSettings
- Percent
- settings-screen.blade.php
- OrderManagerTest.php
- steps
- 2026_11_09_000014_create_health_tables.php
- DecimalMath
- App\Market\Models\Instrument
- Statistics
- CandleRepositoryTest.php
- Illuminate\Database\Migrations\Migration
- MarketDataSettingsTest.php
- CandleFeedTest.php
- scripts
- 0001_01_01_000002_create_jobs_table.php
- fake-telegram.mjs
- .fromStrings
- 0001_01_01_000001_create_cache_table.php
- 2026_11_08_000004_create_strategy_versions_tables.php
- 2026_11_08_000006_create_mcp_tables.php
- .isFull
- 2026_11_09_000010_add_two_factor_columns_to_users_table.php
- strategies-screen.blade.php
- extra
- app.blade.php
- halt
- remove
- account-screen.blade.php
- GlobalsTest.php
- App\Simulation\StrategyLibrary
- .evaluate
- Asset
- 2026_11_09_000015_create_scalping_tables.php
- App\Simulation\Backtest
- 2026_11_09_000012_create_account_results_tables.php
- 2026_11_09_000022_create_daily_stats_table.php
- SettingsScreenTest.php
- FundingCarry
- CompiledStrategy
- SimulationQueue
- 2026_11_09_000013_add_daily_report_to_notification_settings.php
- MarketType
- StrategyVersion
- AccountRuntime
- .handle
- .isPositive
- 2026_11_09_000018_add_effective_at_to_deployments.php
- 2026_11_09_000016_create_market_universe_tables.php
- CandleStreamTest.php
- 2026_11_09_000017_create_live_tables.php
- start-local.ps1
- ResearchUniverse.php
- Price
- .decide
- 0001_01_01_000000_create_users_table.php
- .times
- Illuminate\Http\Client\Request
- QualityReport
- ReadsGridOptions
- 2026_11_08_000003_create_strategy_trials_table.php
- Illuminate\Database\Eloquent\Model
- 2026_11_09_000020_add_lifetime_to_instruments.php
- VenuePosition

## God Nodes (most connected - your core abstractions)
1. `Decimal` - 502 edges
2. `Side` - 205 edges
3. `Symbol` - 191 edges
4. `App` - 159 edges
5. `Candle` - 134 edges
6. `Timeframe` - 119 edges
7. `Price` - 100 edges
8. `Position` - 96 edges
9. `SimClock` - 85 edges
10. `Clock` - 73 edges

## Surprising Connections (you probably didn't know these)
- `{closure#2}()` --calls--> `App`  [INFERRED]
  tests/Feature/Web/MarketDataSettingsTest.php → app/Notifications/SettingSource.php
- `{closure#11}()` --calls--> `App`  [INFERRED]
  tests/Feature/Market/ScalpingArchiveTest.php → app/Notifications/SettingSource.php
- `{closure#6}()` --calls--> `App`  [INFERRED]
  tests/Feature/Market/ScalpingArchiveTest.php → app/Notifications/SettingSource.php
- `{closure#8}()` --calls--> `App`  [INFERRED]
  tests/Feature/Market/ScalpingArchiveTest.php → app/Notifications/SettingSource.php
- `{closure#8}()` --calls--> `App`  [INFERRED]
  tests/Feature/Notifications/HealthTest.php → app/Notifications/SettingSource.php

## Import Cycles
- None detected.

## Communities (212 total, 53 thin omitted)

### Community 0 - "CompiledStrategyTest.php"
Cohesion: 0.15
Nodes (16): LimitedStrategy, PositionView, StrategyContext, KnownInstruments, {closure#24}(), aggregatedBars(), {closure#11}(), {closure#13}() (+8 more)

### Community 1 - "engineHarness"
Cohesion: 0.10
Nodes (39): EngineCommand, MoveStop, ScriptedStrategy, {closure#1}(), {closure#10}(), {closure#13}(), {closure#14}(), {closure#16}() (+31 more)

### Community 2 - "CanonicalJson"
Cohesion: 0.30
Nodes (6): CanonicalJson, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}()

### Community 3 - "DslError"
Cohesion: 0.20
Nodes (8): YamlList, YamlReader, YamlScalar, YamlValue, {closure#1}(), {closure#2}(), {closure#4}(), plainYaml()

### Community 4 - "Engine"
Cohesion: 0.13
Nodes (4): EngineRecord, Engine, ReconciliationMismatch, PerpetualTracker

### Community 5 - ".parse"
Cohesion: 0.11
Nodes (36): {closure#17}(), {closure#4}(), {closure#5}(), carryDecisions(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}() (+28 more)

### Community 7 - ".now"
Cohesion: 0.17
Nodes (6): {closure#1}(), {closure#2}(), {closure#1}(), ReviewLadder, Ladder, LadderStatus

### Community 8 - "CompiledStrategy.php"
Cohesion: 0.16
Nodes (3): {closure#42}(), Compiler, Program

### Community 9 - "Side"
Cohesion: 0.11
Nodes (68): Side, Buy, Sell, btc(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}() (+60 more)

### Community 10 - "BinanceVenueTest.php"
Cohesion: 0.08
Nodes (21): {closure#2}(), OrderType, Limit, Market, StopMarket, TakeProfitMarket, BinanceVenue, BinanceApiError (+13 more)

### Community 11 - ".of"
Cohesion: 0.14
Nodes (15): AssetMismatch, {closure#1}(), usdt(), {closure#1}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#16}() (+7 more)

### Community 12 - "Candle"
Cohesion: 0.07
Nodes (35): {closure#1}(), {closure#3}(), Candle, InvalidCandle, {closure#1}(), {closure#2}(), candleWith(), {closure#1}() (+27 more)

### Community 13 - "ExchangeVerificationTest.php"
Cohesion: 0.10
Nodes (31): {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#2}(), {closure#3}(), {closure#4}() (+23 more)

### Community 14 - "Quantity"
Cohesion: 0.24
Nodes (3): Quantity, {closure#10}(), {closure#9}()

### Community 15 - "Illuminate\Contracts\Config\Repository"
Cohesion: 0.07
Nodes (10): InstrumentDirectory, StoredInstruments, {closure#1}(), {closure#2}(), CompileStrategy, GridPart, PreparesBacktests, RunBacktest (+2 more)

### Community 16 - "Node"
Cohesion: 0.11
Nodes (21): Binary, Lexer, Parser, Token, TokenKind, Colon, Comma, Dot (+13 more)

### Community 17 - "Clock"
Cohesion: 0.06
Nodes (20): CreateMcpToken, RevokeMcpToken, Tokens, FlattenEngine, HaltEngine, IssuesCommands, PauseStrategy, ResumeEngine (+12 more)

### Community 18 - "Instrument"
Cohesion: 0.04
Nodes (21): BackfillStep, CandleRepository, SelectsInstruments, {closure#1}(), {closure#2}(), StreamCandles, {closure#1}(), VerifyMarketData (+13 more)

### Community 19 - "ScreensTest.php"
Cohesion: 0.15
Nodes (24): {closure#1}(), chartLiveQuery(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}() (+16 more)

### Community 20 - "LedgerTest.php"
Cohesion: 0.14
Nodes (31): Ledger, LedgerViolation, balanceOf(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}() (+23 more)

### Community 21 - "Illuminate\Database\Schema\Blueprint"
Cohesion: 0.14
Nodes (12): {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#1}(), {closure#2}(), {closure#1}() (+4 more)

### Community 22 - "generate.py"
Cohesion: 0.07
Nodes (30): atr(), bars(), bb(), change(), dmi(), donchian(), ema(), highest() (+22 more)

### Community 23 - "App\Strategy\Dsl\Compiler\CompiledStrategy"
Cohesion: 0.08
Nodes (12): {closure#2}(), {closure#3}(), {closure#5}(), {closure#6}(), {closure#7}(), {closure#8}(), {closure#9}(), {closure#1}() (+4 more)

### Community 24 - "Symbol"
Cohesion: 0.05
Nodes (18): BuyAndHold, DonchianBreakout, MovingAverageTrend, BuildableStrategy, Close, EntryOrder, Intent, OpenLong (+10 more)

### Community 25 - "Illuminate\Support\Facades\Http"
Cohesion: 0.21
Nodes (6): {closure#1}(), {closure#2}(), {closure#3}(), fakeTestnet(), testnetAccount(), TestCase

### Community 26 - "TerminalQrCodeTest.php"
Cohesion: 0.21
Nodes (7): TerminalQrCode, {closure#3}(), {closure#4}(), {closure#5}(), expectedModules(), readColoured(), readPlain()

### Community 27 - "ClientOrderId"
Cohesion: 0.05
Nodes (28): Venue, Balance, ClientOrderId, Income, IncomeKind, Funding, Liquidation, OcoRequest (+20 more)

### Community 28 - ".of"
Cohesion: 0.09
Nodes (72): RejectionReason, BelowMinimum, Breaker, Cooldown, DuplicateIntent, EntryWorking, GrossExposure, Halted (+64 more)

### Community 29 - "SimulatedPerpVenueTest.php"
Cohesion: 0.22
Nodes (26): {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#15}(), {closure#18}(), {closure#19}() (+18 more)

### Community 30 - "RuntimeException"
Cohesion: 0.12
Nodes (3): PhrityTransport, StreamFailed, FakeTransport

### Community 31 - "oi-momentum-long.strategy.yaml"
Cohesion: 0.07
Nodes (27): entries, long, exits, stop_loss, take_profit, when, fails_when, family (+19 more)

### Community 32 - "RuntimeException"
Cohesion: 0.16
Nodes (10): ReplayDivergence, ArchiveMissing, ChecksumMismatch, BinanceRequestFailed, LocationRestricted, RateLimited, RequestFailed, VerificationSource (+2 more)

### Community 33 - "EnginePerpTest.php"
Cohesion: 0.21
Nodes (15): OpenCarry, {closure#10}(), {closure#11}(), {closure#14}(), {closure#2}(), {closure#5}(), {closure#6}(), {closure#7}() (+7 more)

### Community 35 - "LadderTest.php"
Cohesion: 0.08
Nodes (36): {closure#4}(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}() (+28 more)

### Community 36 - "SimClock"
Cohesion: 0.09
Nodes (25): SimClock, WallClock, {closure#1}(), {closure#1}(), aggTrade(), {closure#1}(), {closure#2}(), {closure#3}() (+17 more)

### Community 37 - "App\Support\Clock\Clock"
Cohesion: 0.08
Nodes (12): BuildUniverse, SyncInstruments, HistoryImports, {closure#1}(), InstrumentSync, ResearchUniverse, SyncResult, CreateUser (+4 more)

### Community 38 - "OrderManagerTest.php"
Cohesion: 0.11
Nodes (17): OrderRole, Entry, Exit, Flatten, Stop, Target, TrackedOrder, {closure#1}() (+9 more)

### Community 39 - "BacktestTest.php"
Cohesion: 0.08
Nodes (31): FeeSchedule, CostModel, CandleFeed, FundingRate, RiskProfile, Account, Benchmark, RiskPolicy (+23 more)

### Community 40 - "App\Strategy\Dsl\Document\DocumentChecker"
Cohesion: 0.07
Nodes (24): Argument, BoolLiteral, Call, DurationLiteral, Identifier, Lookback, Node, NumberLiteral (+16 more)

### Community 41 - "ema-cross-scalp-long.strategy.yaml"
Cohesion: 0.06
Nodes (33): entries, long, exits, stop_loss, take_profit, time_stop, when, fails_when (+25 more)

### Community 42 - "Decimal"
Cohesion: 0.06
Nodes (33): RoundTrip, {closure#3}(), {closure#1}(), {closure#2}(), {closure#4}(), {closure#6}(), {closure#7}(), {closure#10}() (+25 more)

### Community 43 - ".zero"
Cohesion: 0.08
Nodes (17): Liquidity, Maker, Taker, OrderStatus, Cancelled, Expired, Filled, New (+9 more)

### Community 44 - "App"
Cohesion: 0.09
Nodes (26): SendTelegram, App, {closure#1}(), {closure#2}(), {closure#3}(), {closure#1}(), {closure#2}(), {closure#4}() (+18 more)

### Community 45 - "Timeframe"
Cohesion: 0.09
Nodes (17): {closure#2}(), {closure#4}(), {closure#2}(), {closure#8}(), Timeframe, FifteenMinutes, FiveMinutes, FourHours (+9 more)

### Community 46 - "PaperEngineTest.php"
Cohesion: 0.13
Nodes (19): AccountEquity, AccountTrade, {closure#1}(), {closure#2}(), {closure#3}(), {closure#1}(), {closure#10}(), {closure#11}() (+11 more)

### Community 47 - "package.json"
Cohesion: 0.15
Nodes (17): dependencies, codemirror@^6.0.2, @codemirror/lang-yaml@^6.1.3, @codemirror/language@^6.13.1, @lezer/highlight@^1.2.5, lightweight-charts@^5.2.1, optionalDependencies, @laravel/multiplex@^0.4.1 (+9 more)

### Community 48 - "RiskConfigTest.php"
Cohesion: 0.50
Nodes (4): {closure#1}(), {closure#2}(), {closure#3}(), documentedLimits()

### Community 49 - "BinanceRestClientTest.php"
Cohesion: 0.15
Nodes (18): binanceClient(), {closure#1}(), {closure#11}(), {closure#13}(), {closure#15}(), {closure#16}(), {closure#17}(), {closure#18}() (+10 more)

### Community 50 - "ema-cross-scalp-short.strategy.yaml"
Cohesion: 0.06
Nodes (33): entries, short, exits, stop_loss, take_profit, time_stop, when, fails_when (+25 more)

### Community 51 - "App\Livewire\SettingsScreen"
Cohesion: 0.06
Nodes (17): Account, AccountSnapshot, Deployment, JournalEntry, ReauthController, AccountScreen, ChartScreen, {closure#2}() (+9 more)

### Community 54 - "postgres"
Cohesion: 0.10
Nodes (19): POSTGRES_DB, POSTGRES_PASSWORD, POSTGRES_USER, interval, retries, test, timeout, environment (+11 more)

### Community 55 - "App\Support\Symbol"
Cohesion: 0.07
Nodes (11): {closure#1}(), {closure#2}(), {closure#11}(), {closure#2}(), {closure#3}(), {closure#1}(), {closure#2}(), SimulatedVenue (+3 more)

### Community 56 - "SizedStrategyTest.php"
Cohesion: 0.08
Nodes (16): SizedStrategy, {closure#1}(), {closure#2}(), {closure#3}(), {closure#5}(), {closure#7}(), {closure#8}(), {closure#1}() (+8 more)

### Community 57 - "donchian-breakout-short.strategy.yaml"
Cohesion: 0.07
Nodes (26): entries, short, default, range, step, default, range, step (+18 more)

### Community 58 - "IndicatorsTest.php"
Cohesion: 0.15
Nodes (15): Donchian, Ema, Sma, bar(), {closure#1}(), {closure#10}(), {closure#12}(), {closure#13}() (+7 more)

### Community 59 - "support.mjs"
Cohesion: 0.26
Nodes (9): @playwright/test, confirmed(), checkLayout(), EMAIL, freshCode(), PASSWORD, reauthenticate(), totp() (+1 more)

### Community 60 - "RunBacktestCommandTest.php"
Cohesion: 0.07
Nodes (35): BacktestRun, {closure#1}(), {closure#1}(), {closure#4}(), {closure#6}(), {closure#1}(), {closure#10}(), {closure#11}() (+27 more)

### Community 61 - "IndicatorReferenceTest.php"
Cohesion: 0.04
Nodes (39): {closure#43}(), {closure#45}(), Atr, BarIndicator, Bollinger, Change, DonchianChannel, Highest (+31 more)

### Community 62 - "ScalpingCollector"
Cohesion: 0.12
Nodes (4): FormingCandles, CandleStream, ScalpingCollector, StreamTransport

### Community 63 - "Illuminate\Database\ConnectionInterface"
Cohesion: 0.09
Nodes (15): {closure#2}(), {closure#4}(), OpenInterest, DatabaseCandleFeed, {closure#1}(), Heartbeats, Rows, QualityCheck (+7 more)

### Community 64 - "AppServiceProvider.php"
Cohesion: 0.05
Nodes (15): {closure#4}(), RunEngine, Notifier, ArchiveCatalog, BinanceEndpoints, Sleeper, SystemSleeper, LocalDiskUsage (+7 more)

### Community 65 - "donchian-breakout.strategy.yaml"
Cohesion: 0.07
Nodes (26): entries, long, default, range, step, default, range, step (+18 more)

### Community 66 - ".run"
Cohesion: 0.10
Nodes (22): PerformanceMetrics, Report, btcMinutes(), {closure#1}(), {closure#2}(), referencePrice(), {closure#21}(), {closure#1}() (+14 more)

### Community 67 - "Strategy"
Cohesion: 0.11
Nodes (21): Strategy, RenamedStrategy, baselineDocument(), baselinePairs(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}() (+13 more)

### Community 68 - "App\Strategy\Dsl\Document\DocumentChecker"
Cohesion: 0.07
Nodes (36): {closure#1}(), {closure#3}(), {closure#4}(), {closure#9}(), ParameterSpec, FunctionSpec, Group, Exit (+28 more)

### Community 69 - "Symbol"
Cohesion: 0.07
Nodes (12): {closure#1}(), {closure#12}(), {closure#2}(), {closure#3}(), {closure#9}(), {closure#2}(), {closure#3}(), {closure#4}() (+4 more)

### Community 70 - "DailyHistory.php"
Cohesion: 0.14
Nodes (4): ArchivePeriod, FillDailyStats, DailyStats, DailyHistory

### Community 71 - "App\Execution\Models\Account"
Cohesion: 0.12
Nodes (8): OpenAccount, StoreKeys, {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), SendDailyReport, DailyReport

### Community 72 - "ExitReason"
Cohesion: 0.19
Nodes (23): ExitReason, Flatten, Liquidation, Signal, Stop, Target, {closure#1}(), {closure#10}() (+15 more)

### Community 73 - "scripts"
Cohesion: 0.15
Nodes (13): scripts, analyse, dev, format, mutate, post-autoload-dump, post-update-cmd, pre-package-uninstall (+5 more)

### Community 74 - "ma-trend-short.strategy.yaml"
Cohesion: 0.07
Nodes (26): entries, short, exits, stop_loss, when, fails_when, family, default (+18 more)

### Community 75 - ".breaches"
Cohesion: 0.29
Nodes (10): Band, bandReport(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#7}() (+2 more)

### Community 76 - ".measure"
Cohesion: 0.18
Nodes (9): EquityTracker, Performance, {closure#1}(), {closure#2}(), {closure#3}(), {closure#5}(), {closure#6}(), {closure#8}() (+1 more)

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

### Community 81 - "DecimalMath"
Cohesion: 0.12
Nodes (20): Cscv, DeflatedSharpe, {closure#3}(), closeTo(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}() (+12 more)

### Community 82 - "MonteCarlo"
Cohesion: 0.11
Nodes (11): {closure#4}(), {closure#5}(), MonteCarlo, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}() (+3 more)

### Community 83 - "App\Support\Number\Decimal"
Cohesion: 0.07
Nodes (53): {closure#3}(), PerpetualView, {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}() (+45 more)

### Community 84 - "pint/composer.json"
Cohesion: 0.20
Nodes (9): config, sort-packages, description, license, name, require, laravel/pint, php (+1 more)

### Community 85 - "TradingTool"
Cohesion: 0.09
Nodes (8): DslReference, GetFunding, GetStrategy, ListInstruments, ListTrials, SaveStrategy, TradingServer, TradingTool

### Community 86 - "RunSimulation.php"
Cohesion: 0.12
Nodes (5): ImportMarketData, MarketJob, {closure#3}(), RunSimulation, SimulationJob

### Community 87 - "DocumentCheckerTest.php"
Cohesion: 0.24
Nodes (11): DslWriter, checkDocument(), {closure#1}(), {closure#2}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}() (+3 more)

### Community 88 - "require-dev"
Cohesion: 0.22
Nodes (9): require-dev, fakerphp/faker, larastan/larastan, laravel/pail, laravel/pao, mockery/mockery, nunomaduro/collision, pestphp/pest (+1 more)

### Community 89 - "scripts-descriptions"
Cohesion: 0.25
Nodes (8): scripts-descriptions, analyse, format, mutate, test, test:network, test:testnet, tools:install

### Community 90 - "Carbon\CarbonImmutable"
Cohesion: 0.09
Nodes (18): {closure#4}(), {closure#1}(), {closure#1}(), FormingCandle, MinuteCandles, BreakerEvent, MarketBreakers, RiskEvent (+10 more)

### Community 91 - ".fromStrings"
Cohesion: 0.13
Nodes (8): RegimeLabels, DirectionalMovement, {closure#1}(), {closure#2}(), {closure#4}(), {closure#7}(), {closure#9}(), days()

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

### Community 96 - "HealthChecks"
Cohesion: 0.11
Nodes (6): CheckHealth, DiskUsage, {closure#1}(), HealthAlerts, HealthChecks, HealthResult

### Community 97 - "jobs"
Cohesion: 0.25
Nodes (8): name, runs-on, name, runs-on, steps, jobs, analyse, audit

### Community 98 - "App\Strategy\Dsl\Document\DocumentChecker"
Cohesion: 0.14
Nodes (14): AstPrinter, {closure#1}(), {closure#5}(), {closure#7}(), {closure#8}(), EntrySpec, Expression, DslError (+6 more)

### Community 99 - "psr-4"
Cohesion: 0.40
Nodes (5): autoload, psr-4, App\\, Database\\Factories\\, Database\\Seeders\\

### Community 100 - "require"
Cohesion: 0.22
Nodes (9): require, bacon/bacon-qr-code, laravel/fortify, laravel/framework, laravel/mcp, laravel/tinker, livewire/livewire, php (+1 more)

### Community 102 - "Illuminate\Http\Request"
Cohesion: 0.08
Nodes (9): AuthenticateMcpToken, ChartLiveController, AppServiceProvider, {closure#3}(), {closure#4}(), FortifyServiceProvider, {closure#1}(), {closure#2}() (+1 more)

### Community 103 - "StrategyGates.php"
Cohesion: 0.05
Nodes (32): {closure#11}(), {closure#12}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#6}(), {closure#8}(), {closure#2}() (+24 more)

### Community 104 - "uses"
Cohesion: 0.21
Nodes (12): steps, name, runs-on, steps, frontend, lint, name, runs-on (+4 more)

### Community 105 - ".walk"
Cohesion: 0.18
Nodes (17): CandleAggregator, CandleSequenceBroken, {closure#7}(), aggregate(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}() (+9 more)

### Community 106 - "devDependencies"
Cohesion: 0.18
Nodes (12): devDependencies, concurrently@^10.0.3, laravel-vite-plugin@^3.1, @playwright/test@1.63.0, tailwindcss@^4.0.0, @tailwindcss/vite@^4.0.0, vite@^8.0.0, concurrently (+4 more)

### Community 108 - "Laravel\Mcp\Response"
Cohesion: 0.15
Nodes (6): ChartTool, {closure#1}(), GetCandles, GetIndicators, GetJob, ChartSeries

### Community 117 - "App\Strategy\Dsl\TypeChecker"
Cohesion: 0.08
Nodes (18): {closure#2}(), {closure#1}(), {closure#2}(), ParamKind, Anchor, Count, Duration, Number (+10 more)

### Community 118 - "ScalpingFunctionsTest.php"
Cohesion: 0.10
Nodes (22): PriceMinute, TradeFlow, {closure#10}(), {closure#11}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}() (+14 more)

### Community 119 - "RulesHistoryTest.php"
Cohesion: 0.25
Nodes (9): RulesHistory, {closure#2}(), {closure#4}(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), rules() (+1 more)

### Community 120 - "buy-and-hold.strategy.yaml"
Cohesion: 0.12
Nodes (16): entries, long, exits, stop_loss, fails_when, family, hypothesis, when (+8 more)

### Community 121 - "ChartSeriesTest.php"
Cohesion: 0.23
Nodes (11): chart(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#2}(), {closure#3}(), {closure#5}(), {closure#6}() (+3 more)

### Community 122 - "TokensTest.php"
Cohesion: 0.31
Nodes (6): {closure#3}(), {closure#4}(), contrastRatio(), contrastRules(), relativeLuminance(), themeTokens()

### Community 123 - "ArchiveImportTest.php"
Cohesion: 0.31
Nodes (10): archiveBytes(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}() (+2 more)

### Community 124 - "App\Http\Controllers\ReauthController"
Cohesion: 0.09
Nodes (3): User, UserFactory, {closure#1}()

### Community 125 - "Illuminate\Database\Migrations\Migration"
Cohesion: 0.15
Nodes (3): {closure#1}(), {closure#1}(), {closure#1}()

### Community 126 - "LiveGate"
Cohesion: 0.17
Nodes (4): LiveGate, ApproveLive, ConfirmsOperator, CreateAccount

### Community 127 - ".register"
Cohesion: 0.09
Nodes (23): {closure#10}(), {closure#13}(), {closure#14}(), {closure#15}(), {closure#16}(), {closure#18}(), {closure#20}(), {closure#23}() (+15 more)

### Community 128 - "funding-carry.strategy.yaml"
Cohesion: 0.12
Nodes (15): fails_when, family, hypothesis, params, enter_when, exit_when, margin_buffer, max_leverage_perp (+7 more)

### Community 129 - "McpServerTest.php"
Cohesion: 0.28
Nodes (12): actAsToken(), {closure#10}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}() (+4 more)

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

### Community 137 - "OrderManagerTest.php"
Cohesion: 0.36
Nodes (14): btcUsdt(), {closure#10}(), {closure#15}(), {closure#16}(), {closure#2}(), {closure#4}(), {closure#5}(), {closure#6}() (+6 more)

### Community 138 - "steps"
Cohesion: 0.33
Nodes (6): name, runs-on, services, steps, e2e, if

### Community 139 - "2026_11_09_000014_create_health_tables.php"
Cohesion: 0.33
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

### Community 140 - "DecimalMath"
Cohesion: 0.12
Nodes (14): DecimalMath, {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#2}() (+6 more)

### Community 141 - "App\Market\Models\Instrument"
Cohesion: 0.07
Nodes (21): Backfill, {closure#10}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}() (+13 more)

### Community 142 - "Statistics"
Cohesion: 0.22
Nodes (12): Statistics, {closure#1}(), {closure#2}(), {closure#3}(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}() (+4 more)

### Community 143 - "CandleRepositoryTest.php"
Cohesion: 0.31
Nodes (11): candleStore(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}() (+3 more)

### Community 144 - "Illuminate\Database\Migrations\Migration"
Cohesion: 0.15
Nodes (3): {closure#1}(), {closure#1}(), {closure#1}()

### Community 145 - "MarketDataSettingsTest.php"
Cohesion: 0.08
Nodes (19): {closure#1}(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), scalpingBackfill() (+11 more)

### Community 146 - "CandleFeedTest.php"
Cohesion: 0.25
Nodes (15): {closure#1}(), {closure#10}(), {closure#11}(), {closure#13}(), {closure#14}(), {closure#15}(), {closure#3}(), {closure#4}() (+7 more)

### Community 147 - "scripts"
Cohesion: 0.50
Nodes (4): scripts, build, dev, e2e

### Community 148 - "0001_01_01_000002_create_jobs_table.php"
Cohesion: 0.33
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

### Community 151 - ".fromStrings"
Cohesion: 0.29
Nodes (4): {closure#9}(), {closure#1}(), {closure#42}(), {closure#12}()

### Community 157 - "strategies-screen.blade.php"
Cohesion: 0.33
Nodes (5): open, promote, promoteDeployment, run, save

### Community 159 - "extra"
Cohesion: 0.67
Nodes (3): extra, laravel, dont-discover

### Community 174 - "App\Simulation\StrategyLibrary"
Cohesion: 0.15
Nodes (6): CompileStrategy, ListStrategies, StrategiesScreen, StrategyLibrary, CheckResult, SaveResult

### Community 175 - ".evaluate"
Cohesion: 0.24
Nodes (9): Gates, brokenGate(), {closure#1}(), {closure#3}(), {closure#4}(), {closure#6}(), {closure#7}(), gate() (+1 more)

### Community 176 - "Asset"
Cohesion: 0.20
Nodes (4): Marks, Asset, {closure#25}(), {closure#9}()

### Community 177 - "2026_11_09_000015_create_scalping_tables.php"
Cohesion: 0.33
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

### Community 178 - "App\Simulation\Backtest"
Cohesion: 0.16
Nodes (11): EngineMode, Backtest, Live, Paper, Testnet, InMemoryDecisionLog, {closure#1}(), {closure#2}() (+3 more)

### Community 181 - "SettingsScreenTest.php"
Cohesion: 0.18
Nodes (12): ExchangeKey, {closure#10}(), {closure#11}(), {closure#13}(), {closure#14}(), {closure#3}(), {closure#4}(), {closure#5}() (+4 more)

### Community 183 - "CompiledStrategy"
Cohesion: 0.12
Nodes (9): CompiledStrategy, TradeMemory, {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#7}(), readBackDocument() (+1 more)

### Community 184 - "SimulationQueue"
Cohesion: 0.19
Nodes (4): RunBacktest, RunGrid, SimulationTool, SimulationQueue

### Community 189 - "MarketType"
Cohesion: 0.06
Nodes (32): {closure#1}(), BinanceRestClient, EpochTime, ExchangeNumber, Json, InstrumentDefinition, InstrumentStatus, Delisted (+24 more)

### Community 190 - "StrategyVersion"
Cohesion: 0.42
Nodes (3): {closure#1}(), {closure#3}(), StrategyVersion

### Community 192 - ".handle"
Cohesion: 0.16
Nodes (4): StrategyGates, CarryTemplate, ExitSpec, StrategySpec

### Community 193 - ".isPositive"
Cohesion: 0.08
Nodes (13): {closure#6}(), {closure#1}(), {closure#10}(), {closure#5}(), {closure#4}(), {closure#12}(), {closure#1}(), {closure#2}() (+5 more)

### Community 195 - "2026_11_09_000016_create_market_universe_tables.php"
Cohesion: 0.40
Nodes (3): {closure#1}(), {closure#2}(), up()

### Community 196 - "CandleStreamTest.php"
Cohesion: 0.22
Nodes (14): {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}(), {closure#8}() (+6 more)

### Community 197 - "2026_11_09_000017_create_live_tables.php"
Cohesion: 0.33
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

### Community 198 - "start-local.ps1"
Cohesion: 0.27
Nodes (4): Stop-Postgres(), Stop-ProcessTree(), Test-PostgresRunning(), Write-Step()

### Community 200 - "Price"
Cohesion: 0.10
Nodes (6): ProtectionPlan, {closure#2}(), Fill, PerpPosition, Position, Price

### Community 201 - ".decide"
Cohesion: 0.18
Nodes (5): DecisionLog, DecisionRecord, IntentRecord, {closure#1}(), DatabaseDecisionLog

### Community 202 - "0001_01_01_000000_create_users_table.php"
Cohesion: 0.33
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

### Community 203 - ".times"
Cohesion: 0.12
Nodes (8): InstrumentRules, {closure#2}(), AccountView, Verdict, RiskEngine, StrategyLimits, Perpetual, {closure#5}()

### Community 204 - "Illuminate\Http\Client\Request"
Cohesion: 0.10
Nodes (27): approveLive(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}() (+19 more)

### Community 208 - "Illuminate\Database\Eloquent\Model"
Cohesion: 0.07
Nodes (12): {closure#1}(), McpCall, McpToken, {closure#5}(), PlainDecimal, EngineCommandRow, LiveApproval, UniverseEntry (+4 more)

### Community 214 - "VenuePosition"
Cohesion: 0.31
Nodes (4): VenuePosition, {closure#1}(), SimulatedPosition, {closure#13}()

## Knowledge Gaps
- **149 isolated node(s):** `forgetTelegram`, `testTelegram`, `confirm`, `notify`, `monitor` (+144 more)
  These have ≤1 connection - possible missing edges. (Counts symbols only; 963 node(s) total have ≤1 connection when file, concept and rationale nodes are included.)
- **53 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `Decimal` connect `Decimal` to `CompiledStrategyTest.php`, `engineHarness`, `CanonicalJson`, `Engine`, `Percent`, `DocumentChecker`, `.parse`, `Side`, `BinanceVenueTest.php`, `OrderManagerTest.php`, `Candle`, `App\Market\Models\Instrument`, `Statistics`, `DecimalMath`, `Illuminate\Contracts\Config\Repository`, `Quantity`, `Instrument`, `.of`, `LedgerTest.php`, `App\Strategy\Dsl\Compiler\CompiledStrategy`, `Symbol`, `ClientOrderId`, `.of`, `SimulatedPerpVenueTest.php`, `.isFull`, `EnginePerpTest.php`, `SimClock`, `OrderManagerTest.php`, `BacktestTest.php`, `App\Strategy\Dsl\Document\DocumentChecker`, `.zero`, `Timeframe`, `.evaluate`, `Asset`, `RiskConfigTest.php`, `App\Simulation\Backtest`, `FundingCarry`, `App\Support\Symbol`, `SizedStrategyTest.php`, `CompiledStrategy`, `IndicatorsTest.php`, `RunBacktestCommandTest.php`, `IndicatorReferenceTest.php`, `.handle`, `.isPositive`, `.run`, `Strategy`, `App\Strategy\Dsl\Document\DocumentChecker`, `Symbol`, `Price`, `ExitReason`, `.times`, `.measure`, `DecimalMath`, `MonteCarlo`, `App\Support\Number\Decimal`, `VenuePosition`, `DocumentCheckerTest.php`, `Carbon\CarbonImmutable`, `.fromStrings`, `App\Strategy\Dsl\Document\DocumentChecker`, `StrategyGates.php`, `.walk`, `App\Strategy\Dsl\TypeChecker`, `RulesHistoryTest.php`, `ChartSeriesTest.php`?**
  _High betweenness centrality (0.142) - this node is a cross-community bridge._
- **What connects `forgetTelegram`, `testTelegram`, `confirm` to the rest of the system?**
  _149 weakly-connected nodes found - possible missing edges._
- **Should `engineHarness` be split into smaller, more focused modules?**
  _Cohesion score 0.09573412698412699 - nodes in this community are weakly interconnected._
- **Why does `Symbol` connect `Symbol` to `CompiledStrategyTest.php`, `engineHarness`, `CanonicalJson`, `Engine`, `.parse`, `DocumentChecker`, `Side`, `BinanceVenueTest.php`, `OrderManagerTest.php`, `ExchangeVerificationTest.php`, `Illuminate\Contracts\Config\Repository`, `CandleRepositoryTest.php`, `Instrument`, `LedgerTest.php`, `App\Strategy\Dsl\Compiler\CompiledStrategy`, `Symbol`, `ClientOrderId`, `.of`, `SimulatedPerpVenueTest.php`, `EnginePerpTest.php`, `SimClock`, `OrderManagerTest.php`, `BacktestTest.php`, `Decimal`, `.zero`, `Timeframe`, `Asset`, `App\Simulation\Backtest`, `FundingCarry`, `App\Support\Symbol`, `CompiledStrategy`, `RunBacktestCommandTest.php`, `MarketType`, `.handle`, `.isPositive`, `.run`, `Strategy`, `App\Execution\Models\Account`, `Price`, `.decide`, `ExitReason`, `.measure`, `VenuePosition`, `Carbon\CarbonImmutable`, `App\Strategy\Dsl\Document\DocumentChecker`, `StrategyGates.php`, `Laravel\Mcp\Response`, `RulesHistoryTest.php`, `ChartSeriesTest.php`?**
  _High betweenness centrality (0.037) - this node is a cross-community bridge._
- **Should `Engine` be split into smaller, more focused modules?**
  _Cohesion score 0.1292929292929293 - nodes in this community are weakly interconnected._
- **Why does `App` connect `App` to `McpServerTest.php`, `TelegramSettings`, `BinanceVenueTest.php`, `App\Market\Models\Instrument`, `ExchangeVerificationTest.php`, `CandleRepositoryTest.php`, `MarketDataSettingsTest.php`, `CandleFeedTest.php`, `ScreensTest.php`, `Illuminate\Support\Facades\Http`, `LadderTest.php`, `SimClock`, `App\Support\Clock\Clock`, `PaperEngineTest.php`, `BinanceRestClientTest.php`, `App\Livewire\SettingsScreen`, `SettingsScreenTest.php`, `RunBacktestCommandTest.php`, `MarketType`, `.run`, `2026_11_09_000016_create_market_universe_tables.php`, `CandleStreamTest.php`, `Illuminate\Http\Client\Request`, `ScalpingFunctionsTest.php`, `ArchiveImportTest.php`, `.register`?**
  _High betweenness centrality (0.021) - this node is a cross-community bridge._
- **Should `.parse` be split into smaller, more focused modules?**
  _Cohesion score 0.11428571428571428 - nodes in this community are weakly interconnected._