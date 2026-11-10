# Graph Report - trading-platform  (2026-11-10)

## Corpus Check
- 668 files · ~289,399 words
- Verdict: corpus is large enough that graph structure adds value.
- Unclassified: 103 file(s) not represented in the graph (top: .zip 38, .CHECKSUM 38, (none) 19)

## Summary
- 5472 nodes · 19078 edges · 224 communities (164 shown, 60 thin omitted)
- Extraction: 90% EXTRACTED · 10% INFERRED · 0% AMBIGUOUS · INFERRED: 1882 edges (avg confidence: 0.85)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `e629c641`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- SimulatedPerpVenueTest.php
- engineHarness
- .of
- .timestamp
- SettingsScreen
- Strategy
- DocumentChecker
- .now
- CompiledStrategy.php
- .of
- BinanceVenueTest.php
- PolicyLimits
- Candle
- ExchangeVerificationTest.php
- Engine
- .handle
- Strategy
- Illuminate\Database\ConnectionInterface
- UpsertResult
- ScreensTest.php
- Side
- Illuminate\Database\Schema\Blueprint
- generate.py
- App\Strategy\Dsl\Compiler\CompiledStrategy
- Symbol
- .measure
- TerminalQrCodeTest.php
- .of
- EngineUniverseTest.php
- StrategyVersion
- depthStreamWith
- oi-momentum-long.strategy.yaml
- App\Strategy\Data\StrategyIdentity
- App\Support\Clock\Clock
- Carbon\CarbonImmutable
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
- PreparesBacktests.php
- BinanceRestClientTest.php
- ema-cross-scalp-short.strategy.yaml
- ChartScreen.php
- ReadBack
- RuntimeException
- postgres
- UniverseSchedule
- App
- donchian-breakout-short.strategy.yaml
- IndicatorsTest.php
- support.mjs
- backtestFixture
- IndicatorReferenceTest.php
- .now
- .minus
- Illuminate\Http\Request
- donchian-breakout.strategy.yaml
- .run
- .expect
- Kind
- HealthChecks
- CompiledStrategyTest.php
- .evaluate
- App\Market\Binance\BinanceRestClient
- scripts
- ma-trend-short.strategy.yaml
- .evaluate
- EnginePerpTest.php
- composer.json
- jobs
- postgres
- ma-trend.strategy.yaml
- .isPositive
- RegimeLabelsTest.php
- App\Support\Number\Decimal
- pint/composer.json
- Instrument
- Illuminate\Contracts\Bus\Dispatcher
- App\Notifications\SendTelegram
- require-dev
- scripts-descriptions
- MarketBreakers
- AccountRuntime.php
- config
- uses
- test
- with
- SimClock
- jobs
- GridBacktest.php
- psr-4
- require
- ResearchUniverse
- StrategyGates.php
- uses
- .account
- devDependencies
- Laravel\Mcp\Response
- StrategyIdentity
- BinanceArchive
- ValueIndicator
- buy-and-hold.strategy.yaml
- TradingTool
- TokensTest.php
- ArchiveImportTest.php
- User
- Illuminate\Support\Facades\Schema
- ScalpingFunctionsTest.php
- .register
- funding-carry.strategy.yaml
- McpServerTest.php
- env.mjs
- chart.js
- TelegramSettings
- MarketDataSettingsTest.php
- settings-screen.blade.php
- AccountRuntime.php
- steps
- 2026_11_09_000014_create_health_tables.php
- DecimalMath
- App\Market\Models\Instrument
- App\Simulation\StrategyLibrary
- CandleRepositoryTest.php
- App\Execution\Models\Account
- App\Http\Controllers\ReauthController
- OrderBook
- scripts
- 2026_11_08_000007_create_strategy_gate_tables.php
- fake-telegram.mjs
- 2026_11_08_000003_create_strategy_trials_table.php
- .breaches
- 2026_11_08_000004_create_strategy_versions_tables.php
- 2026_11_08_000006_create_mcp_tables.php
- 2026_11_09_000017_create_live_tables.php
- strategies-screen.blade.php
- extra
- app.blade.php
- halt
- remove
- account-screen.blade.php
- Illuminate\Database\Migrations\Migration
- SimulationQueue
- CandleFeedTest.php
- AccountRuntime
- 2026_11_09_000027_add_to_to_market_jobs.php
- UniverseStrategyTest.php
- 2026_11_09_000012_create_account_results_tables.php
- 2026_11_09_000023_create_universe_members_table.php
- SettingsScreenTest.php
- GlobalsTest.php
- App\Strategy\Dsl\DslError
- UniverseBacktestTest.php
- ParserTest.php
- MarketType
- PreparesBacktests
- 2026_11_09_000025_create_book_depth_table.php
- 0001_01_01_000002_create_jobs_table.php
- 2026_11_09_000009_create_exchange_keys_table.php
- 2026_11_09_000018_add_effective_at_to_deployments.php
- 2026_11_09_000016_create_market_universe_tables.php
- .handle
- .mean
- start-local.ps1
- TestnetEngineTest.php
- .compute
- App\Execution\Journal\DatabaseDecisionLog
- 0001_01_01_000000_create_users_table.php
- MarketDataSettingsTest.php
- LiveAccountTest.php
- RulesHistory
- KlineMessageTest.php
- Illuminate\Contracts\Config\Repository
- CompiledStrategy
- App\Strategy\Data\StrategyContext
- .handle
- 2026_11_09_000020_add_lifetime_to_instruments.php
- CompiledStrategy
- Illuminate\Http\Client\Request
- GridPart.php
- .build
- PerformanceMetrics
- 2026_11_09_000026_create_liquidation_tables.php
- ResearchUniverse.php
- 2026_11_09_000022_create_daily_stats_table.php
- 2026_11_09_000010_add_two_factor_columns_to_users_table.php
- 2026_11_09_000024_create_positioning_table.php

## God Nodes (most connected - your core abstractions)
1. `Decimal` - 472 edges
2. `Side` - 202 edges
3. `App` - 182 edges
4. `Symbol` - 179 edges
5. `Candle` - 111 edges
6. `Timeframe` - 99 edges
7. `Price` - 92 edges
8. `Position` - 89 edges
9. `SimClock` - 85 edges
10. `SimulatedVenue` - 70 edges

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

## Communities (224 total, 60 thin omitted)

### Community 0 - "SimulatedPerpVenueTest.php"
Cohesion: 0.16
Nodes (29): FundingRate, {closure#8}(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}() (+21 more)

### Community 1 - "engineHarness"
Cohesion: 0.05
Nodes (57): {closure#5}(), CommandKind, Flatten, Halt, Pause, Resume, ResumeStrategy, EngineCommand (+49 more)

### Community 2 - ".of"
Cohesion: 0.10
Nodes (29): {closure#1}(), {closure#2}(), {closure#3}(), documentedLimits(), {closure#8}(), {closure#41}(), {closure#44}(), {closure#45}() (+21 more)

### Community 3 - ".timestamp"
Cohesion: 0.08
Nodes (15): {closure#1}(), CandleRepository, {closure#3}(), {closure#5}(), {closure#2}(), DatabaseCandleFeed, {closure#1}(), LiquidationRepository (+7 more)

### Community 4 - "SettingsScreen"
Cohesion: 0.08
Nodes (6): CreateMcpToken, RevokeMcpToken, Tokens, OpenAccount, StoreKeys, SettingsScreen

### Community 5 - "Strategy"
Cohesion: 0.11
Nodes (35): {closure#17}(), {closure#5}(), carryDecisions(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}() (+27 more)

### Community 6 - "DocumentChecker"
Cohesion: 0.18
Nodes (4): {closure#2}(), {closure#5}(), {closure#8}(), DocumentChecker

### Community 7 - ".now"
Cohesion: 0.17
Nodes (6): {closure#1}(), {closure#2}(), {closure#1}(), ReviewLadder, Ladder, LadderStatus

### Community 8 - "CompiledStrategy.php"
Cohesion: 0.14
Nodes (5): {closure#47}(), {closure#54}(), Compiler, {closure#1}(), Program

### Community 9 - ".of"
Cohesion: 0.16
Nodes (43): btc(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#15}(), {closure#16}() (+35 more)

### Community 10 - "BinanceVenueTest.php"
Cohesion: 0.09
Nodes (18): {closure#2}(), OrderType, Limit, Market, StopMarket, TakeProfitMarket, BinanceVenue, BinanceApiError (+10 more)

### Community 11 - "PolicyLimits"
Cohesion: 0.11
Nodes (14): InstrumentDirectory, StoredInstruments, {closure#1}(), CompileStrategy, PolicyLimits, Position, StrategyRepository, {closure#1}() (+6 more)

### Community 12 - "Candle"
Cohesion: 0.07
Nodes (44): Candle, InvalidCandle, {closure#1}(), {closure#2}(), VolumeProfile, {closure#2}(), {closure#1}(), {closure#42}() (+36 more)

### Community 13 - "ExchangeVerificationTest.php"
Cohesion: 0.12
Nodes (29): {closure#1}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}() (+21 more)

### Community 14 - "Engine"
Cohesion: 0.07
Nodes (10): DecisionRecord, EngineRecord, {closure#1}(), {closure#2}(), {closure#3}(), {closure#9}(), Engine, ReconciliationMismatch (+2 more)

### Community 16 - "Strategy"
Cohesion: 0.16
Nodes (19): Strategy, baselineDocument(), baselinePairs(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#14}() (+11 more)

### Community 17 - "Illuminate\Database\ConnectionInterface"
Cohesion: 0.05
Nodes (23): FlattenEngine, HaltEngine, IssuesCommands, PauseStrategy, ResumeEngine, {closure#1}(), StreamDepth, CandleSource (+15 more)

### Community 19 - "ScreensTest.php"
Cohesion: 0.15
Nodes (23): {closure#1}(), chartLiveQuery(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}() (+15 more)

### Community 20 - "Side"
Cohesion: 0.07
Nodes (77): ExitReason, Flatten, Liquidation, Signal, Stop, Target, Fill, Ledger (+69 more)

### Community 21 - "Illuminate\Database\Schema\Blueprint"
Cohesion: 0.14
Nodes (13): {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#1}(), {closure#2}(), {closure#3}() (+5 more)

### Community 22 - "generate.py"
Cohesion: 0.07
Nodes (30): atr(), bars(), bb(), change(), dmi(), donchian(), ema(), highest() (+22 more)

### Community 23 - "App\Strategy\Dsl\Compiler\CompiledStrategy"
Cohesion: 0.06
Nodes (19): OnTimeframe, {closure#1}(), {closure#2}(), {closure#3}(), {closure#5}(), {closure#6}(), {closure#7}(), {closure#8}() (+11 more)

### Community 24 - "Symbol"
Cohesion: 0.04
Nodes (33): IncomeKind, Funding, Liquidation, VenuePosition, {closure#12}(), {closure#2}(), {closure#2}(), {closure#3}() (+25 more)

### Community 25 - ".measure"
Cohesion: 0.16
Nodes (9): EquityTracker, Performance, {closure#1}(), {closure#2}(), {closure#3}(), {closure#5}(), {closure#6}(), {closure#8}() (+1 more)

### Community 26 - "TerminalQrCodeTest.php"
Cohesion: 0.21
Nodes (7): TerminalQrCode, {closure#3}(), {closure#4}(), {closure#5}(), expectedModules(), readColoured(), readPlain()

### Community 27 - ".of"
Cohesion: 0.05
Nodes (97): InstrumentRules, {closure#1}(), AccountView, RejectionReason, BelowMinimum, Breaker, Cooldown, Delisting (+89 more)

### Community 28 - "EngineUniverseTest.php"
Cohesion: 0.18
Nodes (19): {closure#10}(), {closure#11}(), {closure#13}(), {closure#14}(), {closure#17}(), {closure#2}(), {closure#3}(), {closure#6}() (+11 more)

### Community 29 - "StrategyVersion"
Cohesion: 0.16
Nodes (9): {closure#1}(), {closure#3}(), CheckResult, SaveResult, StrategyVersion, {closure#1}(), {closure#2}(), {closure#3}() (+1 more)

### Community 30 - "depthStreamWith"
Cohesion: 0.25
Nodes (5): {closure#1}(), {closure#2}(), depthMessage(), depthStreamWith(), FakeTransport

### Community 31 - "oi-momentum-long.strategy.yaml"
Cohesion: 0.07
Nodes (27): entries, long, exits, stop_loss, take_profit, when, fails_when, family (+19 more)

### Community 32 - "App\Strategy\Data\StrategyIdentity"
Cohesion: 0.14
Nodes (3): {closure#1}(), ScriptedUniverseStrategy, {closure#11}()

### Community 33 - "App\Support\Clock\Clock"
Cohesion: 0.07
Nodes (12): Sleeper, DepthStream, CandleStream, LiquidationCollector, StreamTransport, StrategyGateReport, LiveBar, DatabaseSeeder (+4 more)

### Community 34 - "Carbon\CarbonImmutable"
Cohesion: 0.05
Nodes (21): {closure#1}(), SeriesWindow, {closure#2}(), ArchivePeriod, {closure#1}(), {closure#9}(), BookDepth, FormingCandle (+13 more)

### Community 35 - "App\Livewire\StrategiesScreen"
Cohesion: 0.09
Nodes (8): AccountSnapshot, Deployment, AccountScreen, ChartScreen, HaltButton, {closure#1}(), ModeBadge, {closure#1}()

### Community 36 - "ScalpingCollectorTest.php"
Cohesion: 0.31
Nodes (11): aggTrade(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#6}(), {closure#7}(), collector() (+3 more)

### Community 37 - "HealthTest.php"
Cohesion: 0.11
Nodes (25): Account, {closure#5}(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}() (+17 more)

### Community 38 - "OrderManagerTest.php"
Cohesion: 0.06
Nodes (27): Venue, ClientOrderId, Income, OcoRequest, OrderAck, OrderRequest, OrderRole, Entry (+19 more)

### Community 39 - "InstrumentRules"
Cohesion: 0.08
Nodes (29): FeeSchedule, CostModel, CandleFeed, RiskProfile, Account, Benchmark, RiskPolicy, Backtest (+21 more)

### Community 40 - "Position"
Cohesion: 0.06
Nodes (24): {closure#1}(), Argument, BoolLiteral, Call, DurationLiteral, Identifier, NumberLiteral, Parameter (+16 more)

### Community 41 - "ema-cross-scalp-long.strategy.yaml"
Cohesion: 0.06
Nodes (33): entries, long, exits, stop_loss, take_profit, time_stop, when, fails_when (+25 more)

### Community 42 - "Decimal"
Cohesion: 0.04
Nodes (38): PerpPosition, Position, RoundTrip, {closure#10}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}() (+30 more)

### Community 43 - ".times"
Cohesion: 0.05
Nodes (26): Liquidity, Maker, Taker, OrderStatus, Cancelled, Expired, Filled, New (+18 more)

### Community 44 - "Parser"
Cohesion: 0.09
Nodes (33): Binary, Lookback, Node, Unary, Lexer, {closure#1}(), {closure#2}(), {closure#3}() (+25 more)

### Community 45 - "Timeframe"
Cohesion: 0.09
Nodes (39): CandleAggregator, CandleSequenceBroken, {closure#4}(), Timeframe, FifteenMinutes, FiveMinutes, FourHours, OneDay (+31 more)

### Community 46 - "Illuminate\Database\Eloquent\Model"
Cohesion: 0.07
Nodes (26): McpCall, AccountEquity, AccountTrade, PlainDecimal, UniverseEntry, {closure#1}(), {closure#2}(), {closure#3}() (+18 more)

### Community 47 - "package.json"
Cohesion: 0.15
Nodes (17): dependencies, codemirror@^6.0.2, @codemirror/lang-yaml@^6.1.3, @codemirror/language@^6.13.1, @lezer/highlight@^1.2.5, lightweight-charts@^5.2.1, optionalDependencies, @laravel/multiplex@^0.4.1 (+9 more)

### Community 48 - "PreparesBacktests.php"
Cohesion: 0.06
Nodes (20): {closure#1}(), InstrumentDefinition, InstrumentStatus, Delisted, Halted, Trading, AssumedRules, {closure#1}() (+12 more)

### Community 49 - "BinanceRestClientTest.php"
Cohesion: 0.19
Nodes (16): {closure#1}(), binanceClient(), {closure#1}(), {closure#11}(), {closure#13}(), {closure#15}(), {closure#16}(), {closure#18}() (+8 more)

### Community 50 - "ema-cross-scalp-short.strategy.yaml"
Cohesion: 0.06
Nodes (33): entries, short, exits, stop_loss, take_profit, time_stop, when, fails_when (+25 more)

### Community 51 - "ChartScreen.php"
Cohesion: 0.06
Nodes (29): {closure#2}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), FunctionSpec, Group, Exit (+21 more)

### Community 53 - "RuntimeException"
Cohesion: 0.09
Nodes (6): QueryTooSlow, ReplayDivergence, OrderBookOutOfSync, PhrityTransport, StreamFailed, LocalDiskUsage

### Community 54 - "postgres"
Cohesion: 0.10
Nodes (19): POSTGRES_DB, POSTGRES_PASSWORD, POSTGRES_USER, interval, retries, test, timeout, environment (+11 more)

### Community 55 - "UniverseSchedule"
Cohesion: 0.36
Nodes (3): UniverseSchedule, {closure#17}(), {closure#1}()

### Community 56 - "App"
Cohesion: 0.13
Nodes (27): App, {closure#12}(), {closure#1}(), {closure#2}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#2}() (+19 more)

### Community 57 - "donchian-breakout-short.strategy.yaml"
Cohesion: 0.07
Nodes (26): entries, short, default, range, step, default, range, step (+18 more)

### Community 58 - "IndicatorsTest.php"
Cohesion: 0.09
Nodes (23): Atr, Donchian, Ema, Period, Sma, {closure#5}(), bar(), {closure#1}() (+15 more)

### Community 59 - "support.mjs"
Cohesion: 0.26
Nodes (9): @playwright/test, confirmed(), checkLayout(), EMAIL, freshCode(), PASSWORD, reauthenticate(), totp() (+1 more)

### Community 60 - "backtestFixture"
Cohesion: 0.14
Nodes (34): ask(), {closure#10}(), {closure#6}(), {closure#7}(), {closure#8}(), {closure#1}(), {closure#2}(), {closure#4}() (+26 more)

### Community 61 - "IndicatorReferenceTest.php"
Cohesion: 0.04
Nodes (28): BarIndicator, Change, DonchianChannel, Hma, Keltner, Macd, Natr, Obv (+20 more)

### Community 63 - ".minus"
Cohesion: 0.16
Nodes (10): {closure#4}(), MonteCarlo, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}() (+2 more)

### Community 64 - "Illuminate\Http\Request"
Cohesion: 0.09
Nodes (9): ChartLiveController, AppServiceProvider, {closure#8}(), {closure#3}(), {closure#4}(), FortifyServiceProvider, {closure#1}(), {closure#2}() (+1 more)

### Community 65 - "donchian-breakout.strategy.yaml"
Cohesion: 0.07
Nodes (26): entries, long, default, range, step, default, range, step (+18 more)

### Community 66 - ".run"
Cohesion: 0.11
Nodes (27): Report, btcMinutes(), {closure#1}(), {closure#2}(), {closure#3}(), referencePrice(), {closure#13}(), {closure#16}() (+19 more)

### Community 67 - ".expect"
Cohesion: 0.23
Nodes (14): checkDocument(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#2}(), {closure#4}() (+6 more)

### Community 68 - "Kind"
Cohesion: 0.07
Nodes (39): {closure#3}(), {closure#4}(), {closure#7}(), {closure#9}(), ParameterSpec, DslWriter, Scope, {closure#4}() (+31 more)

### Community 69 - "HealthChecks"
Cohesion: 0.08
Nodes (7): CheckHealth, DiskUsage, {closure#1}(), HealthAlerts, HealthChecks, HealthResult, FakeDisk

### Community 70 - "CompiledStrategyTest.php"
Cohesion: 0.19
Nodes (15): aggregatedBars(), {closure#11}(), {closure#13}(), {closure#16}(), {closure#17}(), {closure#2}(), {closure#3}(), {closure#4}() (+7 more)

### Community 71 - ".evaluate"
Cohesion: 0.24
Nodes (9): Gates, brokenGate(), {closure#1}(), {closure#3}(), {closure#4}(), {closure#6}(), {closure#7}(), gate() (+1 more)

### Community 72 - "App\Market\Binance\BinanceRestClient"
Cohesion: 0.11
Nodes (9): ArchiveCatalog, {closure#7}(), {closure#1}(), {closure#2}(), {closure#5}(), FillDailyStats, SyncInstruments, DailyStats (+1 more)

### Community 73 - "scripts"
Cohesion: 0.15
Nodes (13): scripts, analyse, dev, format, mutate, post-autoload-dump, post-update-cmd, pre-package-uninstall (+5 more)

### Community 74 - "ma-trend-short.strategy.yaml"
Cohesion: 0.07
Nodes (26): entries, short, exits, stop_loss, when, fails_when, family, default (+18 more)

### Community 75 - ".evaluate"
Cohesion: 0.25
Nodes (8): WalkForward, WalkForwardSet, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), wfSet()

### Community 76 - "EnginePerpTest.php"
Cohesion: 0.19
Nodes (17): InMemoryDecisionLog, {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#2}(), {closure#5}() (+9 more)

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
Cohesion: 0.13
Nodes (18): Cscv, DeflatedSharpe, closeTo(), {closure#1}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}() (+10 more)

### Community 82 - "RegimeLabelsTest.php"
Cohesion: 0.12
Nodes (8): RegimeLabels, DirectionalMovement, Wilder, {closure#2}(), {closure#4}(), {closure#7}(), {closure#9}(), days()

### Community 83 - "App\Support\Number\Decimal"
Cohesion: 0.06
Nodes (66): {closure#6}(), {closure#3}(), {closure#10}(), {closure#5}(), {closure#8}(), {closure#4}(), {closure#12}(), {closure#1}() (+58 more)

### Community 84 - "pint/composer.json"
Cohesion: 0.20
Nodes (9): config, sort-packages, description, license, name, require, laravel/pint, php (+1 more)

### Community 85 - "Instrument"
Cohesion: 0.05
Nodes (20): ArchiveMissing, ChecksumMismatch, BinanceRequestFailed, LocationRestricted, RateLimited, CheckMarketData, SelectsInstruments, {closure#2}() (+12 more)

### Community 86 - "Illuminate\Contracts\Bus\Dispatcher"
Cohesion: 0.24
Nodes (4): {closure#4}(), Notifier, TelegramNotifier, {closure#18}()

### Community 87 - "App\Notifications\SendTelegram"
Cohesion: 0.17
Nodes (3): ImportMarketData, MarketJob, RunSimulation

### Community 88 - "require-dev"
Cohesion: 0.22
Nodes (9): require-dev, fakerphp/faker, larastan/larastan, laravel/pail, laravel/pao, mockery/mockery, nunomaduro/collision, pestphp/pest (+1 more)

### Community 89 - "scripts-descriptions"
Cohesion: 0.25
Nodes (8): scripts-descriptions, analyse, format, mutate, test, test:network, test:testnet, tools:install

### Community 90 - "MarketBreakers"
Cohesion: 0.11
Nodes (16): {closure#4}(), MinuteCandles, BreakerEvent, MarketBreakers, {closure#1}(), {closure#2}(), {closure#3}(), ReferenceMarketBreakers (+8 more)

### Community 91 - "AccountRuntime.php"
Cohesion: 0.18
Nodes (4): SizedStrategy, SizedUniverseStrategy, {closure#1}(), LimitedStrategy

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

### Community 96 - "SimClock"
Cohesion: 0.13
Nodes (14): SimClock, {closure#1}(), {closure#2}(), {closure#3}(), {closure#1}(), {closure#1}(), {closure#1}(), {closure#1}() (+6 more)

### Community 97 - "jobs"
Cohesion: 0.25
Nodes (8): name, runs-on, name, runs-on, steps, jobs, analyse, audit

### Community 98 - "GridBacktest.php"
Cohesion: 0.10
Nodes (14): {closure#11}(), {closure#12}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#6}(), {closure#1}(), {closure#3}() (+6 more)

### Community 99 - "psr-4"
Cohesion: 0.40
Nodes (5): autoload, psr-4, App\\, Database\\Factories\\, Database\\Seeders\\

### Community 100 - "require"
Cohesion: 0.22
Nodes (9): require, bacon/bacon-qr-code, laravel/fortify, laravel/framework, laravel/mcp, laravel/tinker, livewire/livewire, php (+1 more)

### Community 102 - "ResearchUniverse"
Cohesion: 0.09
Nodes (7): BuildUniverse, HistoryImports, ResearchUniverse, Universes, {closure#1}(), {closure#3}(), rankedPerp()

### Community 103 - "StrategyGates.php"
Cohesion: 0.09
Nodes (13): {closure#8}(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#7}(), {closure#9}() (+5 more)

### Community 104 - "uses"
Cohesion: 0.21
Nodes (12): steps, name, runs-on, steps, frontend, lint, name, runs-on (+4 more)

### Community 105 - ".account"
Cohesion: 0.14
Nodes (11): EnterOnceStrategy, Policies, {closure#1}(), {closure#2}(), __construct(), enterOnce(), identity(), limits() (+3 more)

### Community 106 - "devDependencies"
Cohesion: 0.18
Nodes (12): devDependencies, concurrently@^10.0.3, laravel-vite-plugin@^3.1, @playwright/test@1.63.0, tailwindcss@^4.0.0, @tailwindcss/vite@^4.0.0, vite@^8.0.0, concurrently (+4 more)

### Community 108 - "Laravel\Mcp\Response"
Cohesion: 0.13
Nodes (8): ChartTool, GetCandles, GetIndicators, {closure#1}(), ListInstruments, {closure#1}(), ChartSeries, StrategyTrial

### Community 117 - "StrategyIdentity"
Cohesion: 0.17
Nodes (5): StrategyIdentity, RenamedStrategy, {closure#1}(), {closure#2}(), {closure#3}()

### Community 118 - "BinanceArchive"
Cohesion: 0.06
Nodes (14): BinanceArchive, {closure#1}(), {closure#2}(), {closure#14}(), ExchangeNumber, RequestFailed, SystemSleeper, TradeFlowBuilder (+6 more)

### Community 119 - "ValueIndicator"
Cohesion: 0.10
Nodes (8): {closure#48}(), {closure#67}(), Bollinger, Highest, Lowest, Rsi, StandardDeviation, ValueIndicator

### Community 120 - "buy-and-hold.strategy.yaml"
Cohesion: 0.12
Nodes (16): entries, long, exits, stop_loss, fails_when, family, hypothesis, when (+8 more)

### Community 121 - "TradingTool"
Cohesion: 0.09
Nodes (11): BoundedRead, Refused, {closure#2}(), GetDepth, GetLiquidations, {closure#1}(), {closure#3}(), {closure#4}() (+3 more)

### Community 122 - "TokensTest.php"
Cohesion: 0.31
Nodes (6): {closure#3}(), {closure#4}(), contrastRatio(), contrastRules(), relativeLuminance(), themeTokens()

### Community 123 - "ArchiveImportTest.php"
Cohesion: 0.31
Nodes (10): archiveBytes(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}() (+2 more)

### Community 124 - "User"
Cohesion: 0.10
Nodes (3): User, UserFactory, {closure#1}()

### Community 125 - "Illuminate\Support\Facades\Schema"
Cohesion: 0.14
Nodes (4): {closure#1}(), {closure#1}(), {closure#1}(), {closure#2}()

### Community 126 - "ScalpingFunctionsTest.php"
Cohesion: 0.07
Nodes (31): {closure#11}(), {closure#13}(), {closure#3}(), {closure#5}(), Positioning, PriceMinute, TradeFlow, {closure#10}() (+23 more)

### Community 127 - ".register"
Cohesion: 0.06
Nodes (43): {closure#10}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#15}(), {closure#16}(), {closure#18}(), {closure#20}() (+35 more)

### Community 128 - "funding-carry.strategy.yaml"
Cohesion: 0.12
Nodes (15): fails_when, family, hypothesis, params, enter_when, exit_when, margin_buffer, max_leverage_perp (+7 more)

### Community 129 - "McpServerTest.php"
Cohesion: 0.13
Nodes (16): TradingServer, actAsToken(), {closure#11}(), {closure#19}(), {closure#2}(), {closure#20}(), {closure#21}(), {closure#22}() (+8 more)

### Community 130 - "env.mjs"
Cohesion: 0.27
Nodes (4): sizes, BASE_URL, E2E_ENV, TELEGRAM_URL

### Community 131 - "chart.js"
Cohesion: 0.27
Nodes (7): faded(), options(), priceChart(), token(), volumeColor(), dslEditor(), highlight

### Community 132 - "TelegramSettings"
Cohesion: 0.15
Nodes (6): NotificationSettings, SettingSource, Env, None, TelegramClient, TelegramSettings

### Community 133 - "MarketDataSettingsTest.php"
Cohesion: 0.12
Nodes (14): {closure#1}(), {closure#1}(), {closure#1}(), {closure#11}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}() (+6 more)

### Community 135 - "settings-screen.blade.php"
Cohesion: 0.15
Nodes (12): approveLive, confirm, forgetKey, forgetTelegram, importUniverse, monitor, notify, removeInstrument (+4 more)

### Community 137 - "AccountRuntime.php"
Cohesion: 0.07
Nodes (10): {closure#1}(), {closure#2}(), Balance, Asset, AssetMismatch, Money, Quantity, balances() (+2 more)

### Community 138 - "steps"
Cohesion: 0.33
Nodes (6): name, runs-on, services, steps, e2e, if

### Community 139 - "2026_11_09_000014_create_health_tables.php"
Cohesion: 0.33
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

### Community 140 - "DecimalMath"
Cohesion: 0.13
Nodes (16): Normal, DecimalMath, {closure#10}(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}() (+8 more)

### Community 141 - "App\Market\Models\Instrument"
Cohesion: 0.10
Nodes (14): {closure#1}(), Backfill, {closure#10}(), {closure#12}(), {closure#15}(), {closure#2}(), {closure#4}(), {closure#6}() (+6 more)

### Community 142 - "App\Simulation\StrategyLibrary"
Cohesion: 0.12
Nodes (8): CompileStrategy, ListStrategies, SaveStrategy, StrategiesScreen, {closure#3}(), SimulationJob, SimulationQueue, StrategyLibrary

### Community 143 - "CandleRepositoryTest.php"
Cohesion: 0.31
Nodes (11): candleStore(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}() (+3 more)

### Community 144 - "App\Execution\Models\Account"
Cohesion: 0.13
Nodes (8): LiveGate, LiveApproval, {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), SendDailyReport, DailyReport

### Community 145 - "App\Http\Controllers\ReauthController"
Cohesion: 0.19
Nodes (4): ApproveLive, ConfirmsOperator, CreateAccount, ReauthController

### Community 146 - "OrderBook"
Cohesion: 0.16
Nodes (8): DepthUpdate, OrderBook, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), levels(), update()

### Community 147 - "scripts"
Cohesion: 0.50
Nodes (4): scripts, build, dev, e2e

### Community 152 - ".breaches"
Cohesion: 0.24
Nodes (10): Band, bandReport(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#7}() (+2 more)

### Community 155 - "2026_11_09_000017_create_live_tables.php"
Cohesion: 0.33
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

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
Cohesion: 0.08
Nodes (8): DslReference, GetFunding, GetJob, GetStrategy, ListTrials, RunBacktest, RunGrid, SimulationTool

### Community 175 - "CandleFeedTest.php"
Cohesion: 0.22
Nodes (16): {closure#1}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#15}(), {closure#2}(), {closure#3}() (+8 more)

### Community 176 - "AccountRuntime"
Cohesion: 0.13
Nodes (3): AccountRuntime, RunEngine, WarmingVenue

### Community 178 - "UniverseStrategyTest.php"
Cohesion: 0.23
Nodes (19): CrossSectionalStrategy, {closure#10}(), {closure#12}(), {closure#15}(), {closure#16}(), {closure#18}(), {closure#2}(), {closure#3}() (+11 more)

### Community 181 - "SettingsScreenTest.php"
Cohesion: 0.18
Nodes (13): {closure#1}(), {closure#10}(), {closure#11}(), {closure#13}(), {closure#14}(), {closure#3}(), {closure#4}(), {closure#5}() (+5 more)

### Community 183 - "App\Strategy\Dsl\DslError"
Cohesion: 0.17
Nodes (13): {closure#2}(), {closure#1}(), {closure#1}(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}() (+5 more)

### Community 184 - "UniverseBacktestTest.php"
Cohesion: 0.09
Nodes (15): {closure#6}(), {closure#7}(), {closure#9}(), {closure#1}(), {closure#4}(), {closure#5}(), reportRows(), universeBacktest() (+7 more)

### Community 185 - "ParserTest.php"
Cohesion: 0.28
Nodes (6): AstPrinter, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), sexpr()

### Community 189 - "MarketType"
Cohesion: 0.07
Nodes (19): {closure#11}(), BinanceEndpoints, BinanceRestClient, {closure#3}(), {closure#4}(), EpochTime, Json, {closure#1}() (+11 more)

### Community 190 - "PreparesBacktests"
Cohesion: 0.17
Nodes (3): {closure#1}(), {closure#2}(), PreparesBacktests

### Community 192 - "0001_01_01_000002_create_jobs_table.php"
Cohesion: 0.33
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

### Community 195 - "2026_11_09_000016_create_market_universe_tables.php"
Cohesion: 0.40
Nodes (3): {closure#1}(), {closure#2}(), up()

### Community 196 - ".handle"
Cohesion: 0.20
Nodes (7): RunBacktest, CanonicalJson, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}()

### Community 197 - ".mean"
Cohesion: 0.25
Nodes (11): Statistics, {closure#1}(), {closure#3}(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}() (+3 more)

### Community 198 - "start-local.ps1"
Cohesion: 0.27
Nodes (4): Stop-Postgres(), Stop-ProcessTree(), Test-PostgresRunning(), Write-Step()

### Community 199 - "TestnetEngineTest.php"
Cohesion: 0.17
Nodes (7): ExchangeKey, {closure#1}(), {closure#2}(), {closure#3}(), fakeTestnet(), testnetAccount(), TestCase

### Community 201 - "App\Execution\Journal\DatabaseDecisionLog"
Cohesion: 0.21
Nodes (5): DecisionLog, IntentRecord, {closure#1}(), DatabaseDecisionLog, JournalEntry

### Community 202 - "0001_01_01_000000_create_users_table.php"
Cohesion: 0.33
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

### Community 203 - "MarketDataSettingsTest.php"
Cohesion: 0.18
Nodes (5): {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), savedEmaCross()

### Community 204 - "LiveAccountTest.php"
Cohesion: 0.24
Nodes (12): approveLive(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}() (+4 more)

### Community 205 - "RulesHistory"
Cohesion: 0.28
Nodes (8): RulesHistory, {closure#2}(), {closure#4}(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), rules()

### Community 206 - "KlineMessageTest.php"
Cohesion: 0.33
Nodes (6): KlineMessage, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), streamLines()

### Community 209 - "App\Strategy\Data\StrategyContext"
Cohesion: 0.20
Nodes (5): {closure#2}(), {closure#3}(), {closure#12}(), {closure#16}(), {closure#5}()

### Community 213 - "CompiledStrategy"
Cohesion: 0.14
Nodes (7): {closure#57}(), {closure#58}(), {closure#59}(), {closure#60}(), CrossSection, UniverseStrategy, {closure#19}()

### Community 214 - "Illuminate\Http\Client\Request"
Cohesion: 0.08
Nodes (30): SendTelegram, {closure#3}(), {closure#6}(), queryOf(), {closure#3}(), {closure#17}(), {closure#2}(), {closure#4}() (+22 more)

### Community 215 - "GridPart.php"
Cohesion: 0.22
Nodes (3): {closure#2}(), GridPart, SetOutcome

### Community 216 - ".build"
Cohesion: 0.33
Nodes (3): BacktestResult, ParticipantResult, ReportBuilder

## Knowledge Gaps
- **152 isolated node(s):** `Backtest`, `Live`, `Paper`, `Testnet`, `Env` (+147 more)
  These have ≤1 connection - possible missing edges. (Counts symbols only; 1015 node(s) total have ≤1 connection when file, concept and rationale nodes are included.)
- **60 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `Decimal` connect `Decimal` to `SimulatedPerpVenueTest.php`, `engineHarness`, `.of`, `Strategy`, `AccountRuntime.php`, `BinanceVenueTest.php`, `.of`, `Candle`, `DecimalMath`, `Engine`, `PolicyLimits`, `Strategy`, `Illuminate\Database\ConnectionInterface`, `Side`, `App\Strategy\Dsl\Compiler\CompiledStrategy`, `Symbol`, `.measure`, `.of`, `App\Strategy\Data\StrategyLimits`, `OrderManagerTest.php`, `InstrumentRules`, `Position`, `.times`, `Parser`, `AccountRuntime`, `App\Strategy\Dsl\DslError`, `IndicatorsTest.php`, `IndicatorReferenceTest.php`, `.minus`, `.run`, `Kind`, `.mean`, `.handle`, `.evaluate`, `CompiledStrategyTest.php`, `.evaluate`, `EnginePerpTest.php`, `RulesHistory`, `.isPositive`, `RegimeLabelsTest.php`, `App\Support\Number\Decimal`, `GridPart.php`, `PerformanceMetrics`, `AccountRuntime.php`, `GridBacktest.php`, `StrategyGates.php`, `ValueIndicator`?**
  _High betweenness centrality (0.099) - this node is a cross-community bridge._
- **Are the 181 inferred relationships involving `App` (e.g. with `.render()` and `up()`) actually correct?**
  _`App` has 181 INFERRED edges - model-reasoned connections that need verification._
- **What connects `Backtest`, `Live`, `Paper` to the rest of the system?**
  _152 weakly-connected nodes found - possible missing edges._
- **Should `engineHarness` be split into smaller, more focused modules?**
  _Cohesion score 0.054706163401815576 - nodes in this community are weakly interconnected._
- **Why does `Symbol` connect `Symbol` to `SimulatedPerpVenueTest.php`, `engineHarness`, `.timestamp`, `SettingsScreen`, `Strategy`, `DocumentChecker`, `.of`, `BinanceVenueTest.php`, `AccountRuntime.php`, `PolicyLimits`, `ExchangeVerificationTest.php`, `Engine`, `CandleRepositoryTest.php`, `Strategy`, `Illuminate\Database\ConnectionInterface`, `Candle`, `Side`, `.measure`, `.of`, `EngineUniverseTest.php`, `App\Strategy\Data\StrategyIdentity`, `OrderManagerTest.php`, `InstrumentRules`, `Decimal`, `.times`, `AccountRuntime`, `MarketType`, `.run`, `.handle`, `CompiledStrategyTest.php`, `EnginePerpTest.php`, `RulesHistory`, `Instrument`, `.account`, `Laravel\Mcp\Response`?**
  _High betweenness centrality (0.035) - this node is a cross-community bridge._
- **Should `.of` be split into smaller, more focused modules?**
  _Cohesion score 0.09659090909090909 - nodes in this community are weakly interconnected._
- **Why does `DslError` connect `Position` to `.expect`, `Kind`, `CompiledStrategyTest.php`, `.compute`, `Decimal`, `PolicyLimits`, `Laravel\Mcp\Response`, `Parser`, `Candle`, `Strategy`, `App\Strategy\Dsl\DslError`?**
  _High betweenness centrality (0.014) - this node is a cross-community bridge._