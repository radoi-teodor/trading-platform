# Graph Report - trading-platform  (2026-11-09)

## Corpus Check
- 570 files · ~231,077 words
- Verdict: corpus is large enough that graph structure adds value.
- Unclassified: 101 file(s) not represented in the graph (top: .zip 38, .CHECKSUM 38, (none) 19)

## Summary
- 4518 nodes · 15435 edges · 208 communities (152 shown, 56 thin omitted)
- Extraction: 92% EXTRACTED · 8% INFERRED · 0% AMBIGUOUS · INFERRED: 1223 edges (avg confidence: 0.85)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `7c41b972`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- InstrumentRules
- EngineTest.php
- GoldenReportsTest.php
- DslError
- Asset
- .parse
- DocumentChecker
- .times
- CompiledStrategy.php
- Side
- Instrument
- .measure
- Candle
- ExchangeVerificationTest.php
- IndicatorsTest.php
- RiskPolicy
- Node
- Clock
- App\Support\Clock\Clock
- ScreensTest.php
- LedgerTest.php
- Illuminate\Database\Schema\Blueprint
- generate.py
- ReadBack
- Subscription
- .of
- ClientOrderId
- Symbol
- SimulatedPerpVenueTest.php
- Strategy
- App\Market\Stream\PhrityTransport
- Illuminate\Database\ConnectionInterface
- .zero
- ChartScreen.php
- Carbon\CarbonImmutable
- App
- PreparesBacktests.php
- .register
- .isGreaterThan
- FundingRate
- Node
- ema-cross-scalp-long.strategy.yaml
- Decimal
- SimulatedVenue
- OrderRole
- .walk
- Illuminate\Database\Eloquent\Model
- package.json
- Window
- BinanceRestClientTest.php
- ema-cross-scalp-short.strategy.yaml
- .handle
- Side
- Laravel\Mcp\Response
- postgres
- MonteCarlo
- Price
- donchian-breakout-short.strategy.yaml
- IndicatorReferenceTest.php
- support.mjs
- RunBacktestCommandTest.php
- .of
- BinanceArchive
- Backfill.php
- HealthChecks
- donchian-breakout.strategy.yaml
- .run
- SimClock
- App\Strategy\Dsl\Document\DocumentChecker
- InvalidArgumentException
- Carbon\CarbonImmutable
- UserFactory.php
- SimulationQueue
- scripts
- ma-trend-short.strategy.yaml
- FundingCarry
- .sqrt
- composer.json
- jobs
- postgres
- ma-trend.strategy.yaml
- DecimalMath
- CompiledStrategyTest.php
- App\Support\Number\Decimal
- pint/composer.json
- HealthTest.php
- RunSimulation.php
- Timeframe
- require-dev
- scripts-descriptions
- Account
- .fromStrings
- config
- uses
- test
- with
- StrategyVersion
- jobs
- .parse
- psr-4
- require
- EngineMode
- StrategyGates.php
- uses
- SettingsScreenTest.php
- devDependencies
- BinanceVenueTest.php
- Venue
- TradingTool
- CandleFeedTest.php
- buy-and-hold.strategy.yaml
- ChartSeriesTest.php
- TokensTest.php
- ArchiveImportTest.php
- FortifyServiceProvider.php
- Illuminate\Support\Facades\Schema
- BinanceArchive
- App\Market\Models\Instrument
- funding-carry.strategy.yaml
- McpServerTest.php
- env.mjs
- chart.js
- TelegramSettings
- EnginePerpTest.php
- settings-screen.blade.php
- 0001_01_01_000000_create_users_table.php
- steps
- 2026_11_09_000014_create_health_tables.php
- MarketBreakers
- MarketType
- PaperEngineTest.php
- Percent
- Illuminate\Database\Migrations\Migration
- .of
- DocumentCheckerTest.php
- scripts
- 0001_01_01_000002_create_jobs_table.php
- fake-telegram.mjs
- DirectionalMovement
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
- Quantity
- GlobalsTest.php
- StrategyLibrary
- CandleStream
- ParamKind
- EngineTest.php
- AppServiceProvider.php
- 2026_11_09_000012_create_account_results_tables.php
- 2026_11_09_000011_create_notification_settings_table.php
- .benchmark
- User
- SimulationJobsTest.php
- OrderManagerTest.php
- 2026_11_09_000013_add_daily_report_to_notification_settings.php
- .parse
- BarIndicator
- .evaluate
- OpenLong
- StrategyIdentity
- SettingsScreen
- HealthAlerts.php
- CandleRepositoryTest.php
- Account.php
- RulesHistory
- ScalpingBackfillTest.php
- CandleAggregator
- WarmingVenue
- VenuePosition
- UpsertResult
- .__construct
- 2026_11_09_000015_create_scalping_tables.php
- DslReference
- EngineCommand

## God Nodes (most connected - your core abstractions)
1. `Decimal` - 596 edges
2. `Symbol` - 251 edges
3. `Side` - 215 edges
4. `Candle` - 165 edges
5. `Timeframe` - 153 edges
6. `Price` - 116 edges
7. `Clock` - 107 edges
8. `App` - 103 edges
9. `Position` - 96 edges
10. `MarketType` - 85 edges

## Surprising Connections (you probably didn't know these)
- `{closure#13}()` --calls--> `App`  [INFERRED]
  tests/Feature/Web/SettingsScreenTest.php → app/Notifications/SettingSource.php
- `{closure#8}()` --calls--> `App`  [INFERRED]
  tests/Feature/Web/SettingsScreenTest.php → app/Notifications/SettingSource.php
- `{closure#3}()` --calls--> `App`  [INFERRED]
  tests/Feature/Network/BinancePublicDataTest.php → app/Notifications/SettingSource.php
- `{closure#5}()` --calls--> `App`  [INFERRED]
  tests/Feature/Network/BinancePublicDataTest.php → app/Notifications/SettingSource.php
- `{closure#4}()` --calls--> `App`  [INFERRED]
  tests/Feature/Simulation/SimulationJobsTest.php → app/Notifications/SettingSource.php

## Import Cycles
- None detected.

## Communities (208 total, 56 thin omitted)

### Community 0 - "InstrumentRules"
Cohesion: 0.09
Nodes (29): AccountView, RejectionReason, BelowMinimum, Breaker, Cooldown, DuplicateIntent, EntryWorking, GrossExposure (+21 more)

### Community 1 - "EngineTest.php"
Cohesion: 0.19
Nodes (29): ScriptedStrategy, {closure#10}(), {closure#13}(), {closure#14}(), {closure#16}(), {closure#17}(), {closure#2}(), {closure#20}() (+21 more)

### Community 2 - "GoldenReportsTest.php"
Cohesion: 0.13
Nodes (13): PerformanceMetrics, CanonicalJson, Report, {closure#21}(), {closure#8}(), {closure#9}(), goldenRuns(), reportTrades() (+5 more)

### Community 3 - "DslError"
Cohesion: 0.07
Nodes (20): {closure#1}(), DurationLiteral, Identifier, NumberLiteral, PercentLiteral, RMultipleLiteral, {closure#1}(), {closure#5}() (+12 more)

### Community 4 - "Asset"
Cohesion: 0.16
Nodes (3): {closure#2}(), Marks, Asset

### Community 5 - ".parse"
Cohesion: 0.12
Nodes (33): baselinePairs(), {closure#17}(), {closure#4}(), {closure#5}(), {closure#7}(), carryDecisions(), {closure#13}(), {closure#14}() (+25 more)

### Community 7 - ".times"
Cohesion: 0.10
Nodes (5): {closure#1}(), {closure#2}(), {closure#3}(), Perpetual, {closure#5}()

### Community 8 - "CompiledStrategy.php"
Cohesion: 0.13
Nodes (4): CompiledStrategy, {closure#33}(), Compiler, Program

### Community 9 - "Side"
Cohesion: 0.20
Nodes (42): Side, Buy, Sell, btc(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}() (+34 more)

### Community 10 - "Instrument"
Cohesion: 0.08
Nodes (12): CandleRepository, CandleSource, Archive, Rest, Stream, Instrument, ExchangeVerification, VerificationReport (+4 more)

### Community 11 - ".measure"
Cohesion: 0.16
Nodes (9): EquityTracker, Performance, {closure#1}(), {closure#2}(), {closure#3}(), {closure#5}(), {closure#6}(), {closure#8}() (+1 more)

### Community 12 - "Candle"
Cohesion: 0.12
Nodes (30): Candle, {closure#9}(), {closure#1}(), {closure#42}(), candleWith(), {closure#1}(), {closure#10}(), {closure#11}() (+22 more)

### Community 13 - "ExchangeVerificationTest.php"
Cohesion: 0.11
Nodes (29): {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#2}(), {closure#3}(), {closure#4}() (+21 more)

### Community 14 - "IndicatorsTest.php"
Cohesion: 0.09
Nodes (23): Atr, Donchian, Ema, Keltner, Sma, {closure#5}(), bar(), {closure#1}() (+15 more)

### Community 15 - "RiskPolicy"
Cohesion: 0.05
Nodes (32): InstrumentDirectory, StoredInstruments, {closure#1}(), RiskProfile, Account, Benchmark, {closure#1}(), RiskPolicy (+24 more)

### Community 16 - "Node"
Cohesion: 0.11
Nodes (21): Binary, Lexer, Parser, Token, TokenKind, Colon, Comma, Dot (+13 more)

### Community 17 - "Clock"
Cohesion: 0.06
Nodes (25): CreateMcpToken, RevokeMcpToken, AuthenticateMcpToken, Tokens, FlattenEngine, HaltEngine, IssuesCommands, PauseStrategy (+17 more)

### Community 18 - "App\Support\Clock\Clock"
Cohesion: 0.09
Nodes (4): AccountRuntime, RunEngine, ReplayDivergence, {closure#2}()

### Community 19 - "ScreensTest.php"
Cohesion: 0.09
Nodes (18): {closure#1}(), {closure#1}(), {closure#2}(), {closure#3}(), fakeTestnet(), testnetAccount(), {closure#1}(), {closure#2}() (+10 more)

### Community 20 - "LedgerTest.php"
Cohesion: 0.14
Nodes (33): Ledger, LedgerViolation, balanceOf(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}() (+25 more)

### Community 21 - "Illuminate\Database\Schema\Blueprint"
Cohesion: 0.19
Nodes (10): {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#1}(), {closure#2}(), {closure#3}() (+2 more)

### Community 22 - "generate.py"
Cohesion: 0.08
Nodes (30): atr(), bars(), bb(), change(), dmi(), donchian(), ema(), highest() (+22 more)

### Community 23 - "ReadBack"
Cohesion: 0.10
Nodes (9): {closure#6}(), EntrySpec, Expression, {closure#1}(), {closure#2}(), {closure#5}(), {closure#6}(), {closure#7}() (+1 more)

### Community 24 - "Subscription"
Cohesion: 0.06
Nodes (7): BuyAndHold, MovingAverageTrend, BuildableStrategy, Subscription, EnterOnceStrategy, HoldStrategy, {closure#1}()

### Community 25 - ".of"
Cohesion: 0.10
Nodes (55): RiskEvent, RiskEventKind, DailyLossHalt, DrawdownFlatten, DrawdownHalt, WeeklyLossHalt, RiskState, LimitedStrategy (+47 more)

### Community 26 - "ClientOrderId"
Cohesion: 0.07
Nodes (21): ClientOrderId, Income, IncomeKind, Funding, Liquidation, OcoRequest, OrderRequest, OrderUpdate (+13 more)

### Community 27 - "Symbol"
Cohesion: 0.06
Nodes (12): {closure#11}(), {closure#12}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#1}(), {closure#1}(), {closure#1}() (+4 more)

### Community 28 - "SimulatedPerpVenueTest.php"
Cohesion: 0.23
Nodes (24): {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#15}(), {closure#18}(), {closure#19}() (+16 more)

### Community 29 - "Strategy"
Cohesion: 0.22
Nodes (15): Strategy, baselineDocument(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#14}(), {closure#15}() (+7 more)

### Community 30 - "App\Market\Stream\PhrityTransport"
Cohesion: 0.08
Nodes (5): ArchiveMissing, ChecksumMismatch, PhrityTransport, StreamFailed, FakeTransport

### Community 31 - "Illuminate\Database\ConnectionInterface"
Cohesion: 0.08
Nodes (15): {closure#4}(), DatabaseCandleFeed, {closure#1}(), FundingRateRepository, Heartbeats, Rows, {closure#1}(), {closure#2}() (+7 more)

### Community 32 - ".zero"
Cohesion: 0.13
Nodes (18): Cscv, DeflatedSharpe, closeTo(), {closure#1}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}() (+10 more)

### Community 33 - "ChartScreen.php"
Cohesion: 0.06
Nodes (14): {closure#5}(), EngineCommandRow, JournalEntry, ReauthController, AccountScreen, ChartScreen, {closure#2}(), {closure#3}() (+6 more)

### Community 34 - "Carbon\CarbonImmutable"
Cohesion: 0.14
Nodes (14): OpenInterest, PriceMinute, TradeFlow, {closure#10}(), {closure#11}(), {closure#2}(), {closure#3}(), {closure#4}() (+6 more)

### Community 35 - "App"
Cohesion: 0.08
Nodes (27): SendTelegram, App, {closure#1}(), {closure#2}(), {closure#3}(), {closure#1}(), {closure#2}(), {closure#4}() (+19 more)

### Community 36 - "PreparesBacktests.php"
Cohesion: 0.11
Nodes (5): ExportStrategies, ImportStrategies, PreparesBacktests, PromoteStrategy, RunBacktest

### Community 37 - ".register"
Cohesion: 0.16
Nodes (12): {closure#11}(), {closure#14}(), {closure#15}(), {closure#16}(), {closure#4}(), {closure#6}(), {closure#7}(), {closure#8}() (+4 more)

### Community 39 - "FundingRate"
Cohesion: 0.08
Nodes (29): CandleFeed, Backtest, {closure#1}(), {closure#2}(), {closure#1}(), {closure#2}(), {closure#3}(), BacktestResult (+21 more)

### Community 40 - "Node"
Cohesion: 0.08
Nodes (18): Argument, BoolLiteral, Call, Lookback, Node, OnTimeframe, Parameter, Unary (+10 more)

### Community 41 - "ema-cross-scalp-long.strategy.yaml"
Cohesion: 0.06
Nodes (33): entries, long, exits, stop_loss, take_profit, time_stop, when, fails_when (+25 more)

### Community 42 - "Decimal"
Cohesion: 0.05
Nodes (38): {closure#6}(), RoundTrip, {closure#1}(), {closure#2}(), {closure#3}(), {closure#10}(), {closure#5}(), {closure#4}() (+30 more)

### Community 43 - "SimulatedVenue"
Cohesion: 0.11
Nodes (13): Liquidity, Maker, Taker, OrderStatus, Cancelled, Expired, Filled, New (+5 more)

### Community 44 - "OrderRole"
Cohesion: 0.11
Nodes (19): OrderRole, Entry, Exit, Flatten, Stop, Target, TrackedOrder, {closure#3}() (+11 more)

### Community 45 - ".walk"
Cohesion: 0.24
Nodes (13): CandleFactory, aggregate(), {closure#1}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#3}(), {closure#4}() (+5 more)

### Community 46 - "Illuminate\Database\Eloquent\Model"
Cohesion: 0.06
Nodes (12): {closure#1}(), McpCall, McpToken, AccountSnapshot, PlainDecimal, Deployment, {closure#1}(), InstrumentFilter (+4 more)

### Community 47 - "package.json"
Cohesion: 0.15
Nodes (17): dependencies, codemirror@^6.0.2, @codemirror/lang-yaml@^6.1.3, @codemirror/language@^6.13.1, @lezer/highlight@^1.2.5, lightweight-charts@^5.2.1, optionalDependencies, @laravel/multiplex@^0.4.1 (+9 more)

### Community 48 - "Window"
Cohesion: 0.08
Nodes (7): DonchianChannel, Highest, Lowest, StandardDeviation, Stochastic, Vwma, Window

### Community 49 - "BinanceRestClientTest.php"
Cohesion: 0.09
Nodes (30): {closure#3}(), {closure#13}(), {closure#5}(), binanceClient(), {closure#1}(), {closure#11}(), {closure#12}(), {closure#13}() (+22 more)

### Community 50 - "ema-cross-scalp-short.strategy.yaml"
Cohesion: 0.06
Nodes (33): entries, short, exits, stop_loss, take_profit, time_stop, when, fails_when (+25 more)

### Community 51 - ".handle"
Cohesion: 0.09
Nodes (9): GridBacktest, {closure#2}(), {closure#3}(), {closure#5}(), {closure#7}(), {closure#8}(), ReadsGridOptions, StrategyGates (+1 more)

### Community 52 - "Side"
Cohesion: 0.18
Nodes (23): ExitReason, Flatten, Liquidation, Signal, Stop, Target, {closure#1}(), {closure#10}() (+15 more)

### Community 54 - "postgres"
Cohesion: 0.10
Nodes (19): POSTGRES_DB, POSTGRES_PASSWORD, POSTGRES_USER, interval, retries, test, timeout, environment (+11 more)

### Community 55 - "MonteCarlo"
Cohesion: 0.15
Nodes (11): {closure#4}(), {closure#5}(), MonteCarlo, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}() (+3 more)

### Community 56 - "Price"
Cohesion: 0.10
Nodes (9): OrderAck, ProtectionPlan, {closure#1}(), {closure#2}(), {closure#2}(), OrderManager, PerpPosition, Position (+1 more)

### Community 57 - "donchian-breakout-short.strategy.yaml"
Cohesion: 0.07
Nodes (26): entries, short, default, range, step, default, range, step (+18 more)

### Community 58 - "IndicatorReferenceTest.php"
Cohesion: 0.06
Nodes (23): {closure#34}(), Bollinger, Change, Hma, Macd, RealizedVolatility, Roc, Rsi (+15 more)

### Community 59 - "support.mjs"
Cohesion: 0.27
Nodes (9): @playwright/test, confirmed(), checkLayout(), EMAIL, freshCode(), PASSWORD, reauthenticate(), totp() (+1 more)

### Community 60 - "RunBacktestCommandTest.php"
Cohesion: 0.10
Nodes (28): BacktestRun, {closure#4}(), {closure#6}(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}() (+20 more)

### Community 61 - ".of"
Cohesion: 0.08
Nodes (30): Balance, FeeSchedule, CostModel, balances(), {closure#40}(), {closure#41}(), {closure#44}(), {closure#45}() (+22 more)

### Community 62 - "BinanceArchive"
Cohesion: 0.14
Nodes (4): ArchivePeriod, Backfill, BackfillReport, BackfillMarketData

### Community 63 - "Backfill.php"
Cohesion: 0.10
Nodes (14): {closure#1}(), {closure#2}(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#3}(), {closure#5}() (+6 more)

### Community 64 - "HealthChecks"
Cohesion: 0.23
Nodes (3): {closure#1}(), HealthChecks, HealthResult

### Community 65 - "donchian-breakout.strategy.yaml"
Cohesion: 0.07
Nodes (26): entries, long, default, range, step, default, range, step (+18 more)

### Community 66 - ".run"
Cohesion: 0.25
Nodes (7): {closure#1}(), {closure#3}(), goldenMinutes(), goldenRates(), perpetualReferencePrice(), referenceMean(), GoldenData

### Community 67 - "SimClock"
Cohesion: 0.13
Nodes (14): InMemoryDecisionLog, SimClock, WallClock, {closure#1}(), {closure#1}(), {closure#23}(), {closure#27}(), EngineHarness (+6 more)

### Community 68 - "App\Strategy\Dsl\Document\DocumentChecker"
Cohesion: 0.05
Nodes (45): {closure#1}(), {closure#2}(), {closure#5}(), {closure#3}(), {closure#4}(), {closure#9}(), ParameterSpec, DslWriter (+37 more)

### Community 69 - "InvalidArgumentException"
Cohesion: 0.10
Nodes (3): {closure#1}(), {closure#1}(), Period

### Community 70 - "Carbon\CarbonImmutable"
Cohesion: 0.13
Nodes (7): {closure#4}(), DecisionLog, DecisionRecord, EngineRecord, IntentRecord, Engine, {closure#1}()

### Community 72 - "SimulationQueue"
Cohesion: 0.16
Nodes (4): RunBacktest, RunGrid, SimulationTool, SimulationQueue

### Community 73 - "scripts"
Cohesion: 0.15
Nodes (13): scripts, analyse, dev, format, mutate, post-autoload-dump, post-update-cmd, pre-package-uninstall (+5 more)

### Community 74 - "ma-trend-short.strategy.yaml"
Cohesion: 0.07
Nodes (26): entries, short, exits, stop_loss, when, fails_when, family, default (+18 more)

### Community 76 - ".sqrt"
Cohesion: 0.28
Nodes (10): Statistics, {closure#3}(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}() (+2 more)

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
Cohesion: 0.11
Nodes (16): Normal, DecimalMath, {closure#10}(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}() (+8 more)

### Community 82 - "CompiledStrategyTest.php"
Cohesion: 0.11
Nodes (21): FundingRate, Intent, PositionView, StrategyContext, {closure#24}(), aggregatedBars(), {closure#1}(), {closure#11}() (+13 more)

### Community 83 - "App\Support\Number\Decimal"
Cohesion: 0.08
Nodes (40): {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#15}(), {closure#16}() (+32 more)

### Community 84 - "pint/composer.json"
Cohesion: 0.20
Nodes (9): config, sort-packages, description, license, name, require, laravel/pint, php (+1 more)

### Community 85 - "HealthTest.php"
Cohesion: 0.12
Nodes (20): {closure#4}(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#5}(), {closure#6}(), {closure#7}(), reportAccount() (+12 more)

### Community 87 - "Timeframe"
Cohesion: 0.09
Nodes (20): {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), CarryTemplate, {closure#2}(), {closure#8}(), Timeframe (+12 more)

### Community 88 - "require-dev"
Cohesion: 0.22
Nodes (9): require-dev, fakerphp/faker, larastan/larastan, laravel/pail, laravel/pao, mockery/mockery, nunomaduro/collision, pestphp/pest (+1 more)

### Community 89 - "scripts-descriptions"
Cohesion: 0.25
Nodes (8): scripts-descriptions, analyse, format, mutate, test, test:network, test:testnet, tools:install

### Community 90 - "Account"
Cohesion: 0.13
Nodes (6): OpenAccount, CreateAccount, StoreKeys, Account, {closure#2}(), {closure#3}()

### Community 91 - ".fromStrings"
Cohesion: 0.19
Nodes (6): RegimeLabels, {closure#2}(), {closure#4}(), {closure#7}(), {closure#9}(), days()

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

### Community 96 - "StrategyVersion"
Cohesion: 0.24
Nodes (5): {closure#1}(), {closure#2}(), CheckResult, SaveResult, StrategyVersion

### Community 97 - "jobs"
Cohesion: 0.25
Nodes (8): name, runs-on, name, runs-on, steps, jobs, analyse, audit

### Community 98 - ".parse"
Cohesion: 0.39
Nodes (6): AstPrinter, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), sexpr()

### Community 99 - "psr-4"
Cohesion: 0.40
Nodes (5): autoload, psr-4, App\\, Database\\Factories\\, Database\\Seeders\\

### Community 100 - "require"
Cohesion: 0.25
Nodes (8): require, laravel/fortify, laravel/framework, laravel/mcp, laravel/tinker, livewire/livewire, php, phrity/websocket

### Community 102 - "EngineMode"
Cohesion: 0.33
Nodes (5): EngineMode, Backtest, Live, Paper, Testnet

### Community 103 - "StrategyGates.php"
Cohesion: 0.05
Nodes (32): {closure#11}(), {closure#12}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#6}(), {closure#8}(), {closure#2}() (+24 more)

### Community 104 - "uses"
Cohesion: 0.21
Nodes (12): steps, name, runs-on, steps, frontend, lint, name, runs-on (+4 more)

### Community 105 - "SettingsScreenTest.php"
Cohesion: 0.19
Nodes (11): ExchangeKey, {closure#10}(), {closure#11}(), {closure#13}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}() (+3 more)

### Community 106 - "devDependencies"
Cohesion: 0.18
Nodes (12): devDependencies, concurrently@^10.0.3, laravel-vite-plugin@^3.1, @playwright/test@1.63.0, tailwindcss@^4.0.0, @tailwindcss/vite@^4.0.0, vite@^8.0.0, concurrently (+4 more)

### Community 108 - "BinanceVenueTest.php"
Cohesion: 0.08
Nodes (21): {closure#2}(), OrderType, Limit, Market, StopMarket, TakeProfitMarket, BinanceVenue, BinanceApiError (+13 more)

### Community 117 - "Venue"
Cohesion: 0.09
Nodes (8): Venue, ForwardingVenue, {closure#12}(), {closure#22}(), {closure#32}(), {closure#34}(), {closure#49}(), OverReportingVenue

### Community 118 - "TradingTool"
Cohesion: 0.11
Nodes (7): GetFunding, GetJob, GetStrategy, ListInstruments, ListTrials, TradingServer, TradingTool

### Community 119 - "CandleFeedTest.php"
Cohesion: 0.25
Nodes (15): MinuteCandles, {closure#1}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#2}(), {closure#3}(), {closure#4}() (+7 more)

### Community 120 - "buy-and-hold.strategy.yaml"
Cohesion: 0.12
Nodes (16): entries, long, exits, stop_loss, fails_when, family, hypothesis, when (+8 more)

### Community 121 - "ChartSeriesTest.php"
Cohesion: 0.23
Nodes (10): chart(), {closure#10}(), {closure#11}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#6}(), {closure#7}() (+2 more)

### Community 122 - "TokensTest.php"
Cohesion: 0.31
Nodes (6): {closure#3}(), {closure#4}(), contrastRatio(), contrastRules(), relativeLuminance(), themeTokens()

### Community 123 - "ArchiveImportTest.php"
Cohesion: 0.35
Nodes (10): archiveBytes(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}() (+2 more)

### Community 124 - "FortifyServiceProvider.php"
Cohesion: 0.10
Nodes (8): AppServiceProvider, {closure#7}(), {closure#3}(), {closure#4}(), FortifyServiceProvider, {closure#1}(), {closure#2}(), {closure#3}()

### Community 126 - "BinanceArchive"
Cohesion: 0.19
Nodes (4): BinanceArchive, ExchangeNumber, {closure#1}(), {closure#2}()

### Community 127 - "App\Market\Models\Instrument"
Cohesion: 0.26
Nodes (5): {closure#2}(), {closure#4}(), {closure#6}(), {closure#8}(), ScalpingRepository

### Community 128 - "funding-carry.strategy.yaml"
Cohesion: 0.12
Nodes (15): fails_when, family, hypothesis, params, enter_when, exit_when, margin_buffer, max_leverage_perp (+7 more)

### Community 129 - "McpServerTest.php"
Cohesion: 0.30
Nodes (11): actAsToken(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}(), {closure#8}() (+3 more)

### Community 130 - "env.mjs"
Cohesion: 0.27
Nodes (4): sizes, BASE_URL, E2E_ENV, TELEGRAM_URL

### Community 131 - "chart.js"
Cohesion: 0.29
Nodes (6): faded(), options(), priceChart(), token(), dslEditor(), highlight

### Community 132 - "TelegramSettings"
Cohesion: 0.16
Nodes (6): NotificationSettings, SettingSource, Env, None, TelegramClient, TelegramSettings

### Community 133 - "EnginePerpTest.php"
Cohesion: 0.28
Nodes (14): {closure#10}(), {closure#11}(), {closure#14}(), {closure#2}(), {closure#5}(), {closure#6}(), {closure#7}(), {closure#8}() (+6 more)

### Community 135 - "settings-screen.blade.php"
Cohesion: 0.25
Nodes (7): confirm, forgetKey, forgetTelegram, monitor, notify, revokeToken, testTelegram

### Community 137 - "0001_01_01_000000_create_users_table.php"
Cohesion: 0.33
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

### Community 138 - "steps"
Cohesion: 0.33
Nodes (6): name, runs-on, services, steps, e2e, if

### Community 139 - "2026_11_09_000014_create_health_tables.php"
Cohesion: 0.33
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

### Community 140 - "MarketBreakers"
Cohesion: 0.29
Nodes (7): {closure#4}(), BreakerEvent, MarketBreakers, breakerMinute(), {closure#1}(), {closure#2}(), {closure#3}()

### Community 141 - "MarketType"
Cohesion: 0.07
Nodes (26): BinanceRequestFailed, BinanceRestClient, {closure#1}(), Json, LocationRestricted, RateLimited, RequestFailed, SyncInstruments (+18 more)

### Community 142 - "PaperEngineTest.php"
Cohesion: 0.19
Nodes (16): AccountEquity, AccountTrade, {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#2}() (+8 more)

### Community 143 - "Percent"
Cohesion: 0.24
Nodes (3): Percent, {closure#14}(), {closure#15}()

### Community 145 - ".of"
Cohesion: 0.09
Nodes (20): Fill, AssetMismatch, Money, {closure#2}(), {closure#1}(), usdt(), {closure#11}(), {closure#7}() (+12 more)

### Community 146 - "DocumentCheckerTest.php"
Cohesion: 0.30
Nodes (10): checkDocument(), {closure#1}(), {closure#2}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}(), {closure#8}() (+2 more)

### Community 147 - "scripts"
Cohesion: 0.50
Nodes (4): scripts, build, dev, e2e

### Community 148 - "0001_01_01_000002_create_jobs_table.php"
Cohesion: 0.33
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

### Community 157 - "strategies-screen.blade.php"
Cohesion: 0.40
Nodes (4): open, promote, run, save

### Community 159 - "extra"
Cohesion: 0.67
Nodes (3): extra, laravel, dont-discover

### Community 172 - "Quantity"
Cohesion: 0.24
Nodes (3): Quantity, {closure#10}(), {closure#9}()

### Community 173 - "GlobalsTest.php"
Cohesion: 0.14
Nodes (4): ChartTool, GetCandles, GetIndicators, Controller

### Community 174 - "StrategyLibrary"
Cohesion: 0.21
Nodes (5): CompileStrategy, ListStrategies, SaveStrategy, StrategiesScreen, StrategyLibrary

### Community 175 - "CandleStream"
Cohesion: 0.14
Nodes (6): BinanceEndpoints, Sleeper, {closure#1}(), StreamCandles, CandleStream, StreamTransport

### Community 176 - "ParamKind"
Cohesion: 0.13
Nodes (12): {closure#2}(), {closure#1}(), {closure#2}(), ParamKind, Anchor, Count, Duration, Number (+4 more)

### Community 177 - "EngineTest.php"
Cohesion: 0.11
Nodes (6): {closure#1}(), {closure#18}(), {closure#37}(), {closure#38}(), {closure#42}(), limitLong()

### Community 178 - "AppServiceProvider.php"
Cohesion: 0.10
Nodes (9): Notifier, SystemSleeper, DiskUsage, LocalDiskUsage, TelegramNotifier, {closure#3}(), {closure#4}(), {closure#6}() (+1 more)

### Community 181 - ".benchmark"
Cohesion: 0.15
Nodes (18): btcMinutes(), {closure#1}(), {closure#2}(), {closure#3}(), referencePrice(), {closure#13}(), {closure#16}(), {closure#3}() (+10 more)

### Community 183 - "SimulationJobsTest.php"
Cohesion: 0.21
Nodes (5): {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), savedEmaCross()

### Community 184 - "OrderManagerTest.php"
Cohesion: 0.36
Nodes (14): btcUsdt(), {closure#10}(), {closure#15}(), {closure#16}(), {closure#2}(), {closure#4}(), {closure#5}(), {closure#6}() (+6 more)

### Community 189 - ".parse"
Cohesion: 0.16
Nodes (10): EpochTime, KlineMessage, {closure#1}(), {closure#2}(), {closure#3}(), {closure#1}(), {closure#2}(), {closure#3}() (+2 more)

### Community 190 - "BarIndicator"
Cohesion: 0.11
Nodes (6): {closure#36}(), BarIndicator, Natr, Obv, Supertrend, VolumeProfile

### Community 191 - ".evaluate"
Cohesion: 0.24
Nodes (9): Gates, brokenGate(), {closure#1}(), {closure#3}(), {closure#4}(), {closure#6}(), {closure#7}(), gate() (+1 more)

### Community 192 - "OpenLong"
Cohesion: 0.21
Nodes (4): Close, EntryOrder, OpenLong, OpenShort

### Community 193 - "StrategyIdentity"
Cohesion: 0.20
Nodes (5): StrategyIdentity, RenamedStrategy, {closure#1}(), {closure#2}(), {closure#3}()

### Community 196 - "CandleRepositoryTest.php"
Cohesion: 0.28
Nodes (11): candleStore(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}() (+3 more)

### Community 198 - "RulesHistory"
Cohesion: 0.28
Nodes (8): RulesHistory, {closure#4}(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), rules(), {closure#10}()

### Community 199 - "ScalpingBackfillTest.php"
Cohesion: 0.21
Nodes (10): {closure#1}(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), scalpingBackfill() (+2 more)

### Community 200 - "CandleAggregator"
Cohesion: 0.29
Nodes (6): CandleAggregator, CandleSequenceBroken, {closure#3}(), {closure#6}(), {closure#10}(), {closure#2}()

### Community 202 - "VenuePosition"
Cohesion: 0.31
Nodes (4): VenuePosition, {closure#1}(), SimulatedPosition, {closure#13}()

### Community 205 - "2026_11_09_000015_create_scalping_tables.php"
Cohesion: 0.33
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

## Knowledge Gaps
- **142 isolated node(s):** `BelowMinimum`, `Breaker`, `Cooldown`, `DuplicateIntent`, `EntryWorking` (+137 more)
  These have ≤1 connection - possible missing edges. (Counts symbols only; 900 node(s) total have ≤1 connection when file, concept and rationale nodes are included.)
- **56 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `Decimal` connect `Decimal` to `InstrumentRules`, `EngineTest.php`, `GoldenReportsTest.php`, `DslError`, `Asset`, `.parse`, `DocumentChecker`, `.times`, `CompiledStrategy.php`, `Side`, `Instrument`, `.measure`, `Candle`, `IndicatorsTest.php`, `RiskPolicy`, `LedgerTest.php`, `ReadBack`, `.of`, `ClientOrderId`, `Symbol`, `SimulatedPerpVenueTest.php`, `Strategy`, `Illuminate\Database\ConnectionInterface`, `.zero`, `ChartScreen.php`, `Carbon\CarbonImmutable`, `PreparesBacktests.php`, `.isGreaterThan`, `FundingRate`, `Node`, `SimulatedVenue`, `OrderRole`, `.walk`, `Window`, `.handle`, `Side`, `MonteCarlo`, `Price`, `IndicatorReferenceTest.php`, `RunBacktestCommandTest.php`, `.of`, `Backfill.php`, `.run`, `App\Strategy\Dsl\Document\DocumentChecker`, `InvalidArgumentException`, `Carbon\CarbonImmutable`, `FundingCarry`, `.sqrt`, `DecimalMath`, `CompiledStrategyTest.php`, `App\Support\Number\Decimal`, `Timeframe`, `Account`, `.fromStrings`, `StrategyGates.php`, `BinanceVenueTest.php`, `Venue`, `CandleFeedTest.php`, `ChartSeriesTest.php`, `App\Market\Models\Instrument`, `EnginePerpTest.php`, `MarketType`, `Percent`, `.of`, `DocumentCheckerTest.php`, `DirectionalMovement`, `Quantity`, `EngineTest.php`, `.benchmark`, `OrderManagerTest.php`, `BarIndicator`, `.evaluate`, `OpenLong`, `RulesHistory`, `CandleAggregator`, `WarmingVenue`, `VenuePosition`, `.__construct`?**
  _High betweenness centrality (0.207) - this node is a cross-community bridge._
- **What connects `BelowMinimum`, `Breaker`, `Cooldown` to the rest of the system?**
  _142 weakly-connected nodes found - possible missing edges._
- **Should `InstrumentRules` be split into smaller, more focused modules?**
  _Cohesion score 0.08603145235892692 - nodes in this community are weakly interconnected._
- **Why does `Symbol` connect `Symbol` to `McpServerTest.php`, `GoldenReportsTest.php`, `DslError`, `Asset`, `.parse`, `DocumentChecker`, `.times`, `CompiledStrategy.php`, `Side`, `Instrument`, `EnginePerpTest.php`, `.measure`, `MarketType`, `ExchangeVerificationTest.php`, `RiskPolicy`, `Clock`, `App\Support\Clock\Clock`, `.of`, `LedgerTest.php`, `ScreensTest.php`, `ReadBack`, `Subscription`, `.of`, `ClientOrderId`, `SimulatedPerpVenueTest.php`, `Strategy`, `ChartScreen.php`, `PreparesBacktests.php`, `.register`, `.isGreaterThan`, `FundingRate`, `Decimal`, `SimulatedVenue`, `OrderRole`, `BinanceRestClientTest.php`, `EngineTest.php`, `.handle`, `Side`, `Laravel\Mcp\Response`, `.benchmark`, `Price`, `OrderManagerTest.php`, `RunBacktestCommandTest.php`, `OpenLong`, `.run`, `App\Strategy\Dsl\Document\DocumentChecker`, `InvalidArgumentException`, `Carbon\CarbonImmutable`, `RulesHistory`, `CandleRepositoryTest.php`, `WarmingVenue`, `VenuePosition`, `FundingCarry`, `CompiledStrategyTest.php`, `Timeframe`, `Account`, `StrategyGates.php`, `BinanceVenueTest.php`, `Venue`, `CandleFeedTest.php`, `ChartSeriesTest.php`, `App\Market\Models\Instrument`?**
  _High betweenness centrality (0.075) - this node is a cross-community bridge._
- **Should `GoldenReportsTest.php` be split into smaller, more focused modules?**
  _Cohesion score 0.13230769230769232 - nodes in this community are weakly interconnected._
- **Why does `Timeframe` connect `Timeframe` to `McpServerTest.php`, `EngineTest.php`, `DslError`, `EnginePerpTest.php`, `DocumentChecker`, `.parse`, `CompiledStrategy.php`, `Side`, `Instrument`, `Candle`, `MarketType`, `MarketBreakers`, `IndicatorsTest.php`, `DocumentCheckerTest.php`, `ReadBack`, `Subscription`, `Symbol`, `SimulatedPerpVenueTest.php`, `Illuminate\Database\ConnectionInterface`, `ChartScreen.php`, `.isGreaterThan`, `Node`, `Decimal`, `.walk`, `CandleStream`, `BinanceRestClientTest.php`, `EngineTest.php`, `.handle`, `Laravel\Mcp\Response`, `OrderManagerTest.php`, `IndicatorReferenceTest.php`, `.parse`, `Backfill.php`, `OpenLong`, `App\Strategy\Dsl\Document\DocumentChecker`, `CandleRepositoryTest.php`, `CandleAggregator`, `FundingCarry`, `.__construct`, `CompiledStrategyTest.php`, `App\Support\Number\Decimal`, `.fromStrings`, `StrategyGates.php`, `CandleFeedTest.php`, `ChartSeriesTest.php`?**
  _High betweenness centrality (0.046) - this node is a cross-community bridge._
- **Should `DslError` be split into smaller, more focused modules?**
  _Cohesion score 0.06583850931677018 - nodes in this community are weakly interconnected._