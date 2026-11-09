# Graph Report - trading-platform  (2026-11-09)

## Corpus Check
- 567 files · ~229,444 words
- Verdict: corpus is large enough that graph structure adds value.
- Unclassified: 101 file(s) not represented in the graph (top: .zip 38, .CHECKSUM 38, (none) 19)

## Summary
- 4473 nodes · 15244 edges · 191 communities (146 shown, 45 thin omitted)
- Extraction: 92% EXTRACTED · 8% INFERRED · 0% AMBIGUOUS · INFERRED: 1208 edges (avg confidence: 0.85)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `1626a911`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- InstrumentRules
- EngineTest.php
- CanonicalJson
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
- StrategyIdentity
- .of
- Illuminate\Console\Command
- Symbol
- SimulatedPerpVenueTest.php
- Strategy
- App\Market\Stream\PhrityTransport
- Illuminate\Database\ConnectionInterface
- .zero
- Account
- Carbon\CarbonImmutable
- App
- PreparesBacktests.php
- .register
- RiskEventKind
- FundingRate
- Position
- ema-cross-scalp-long.strategy.yaml
- RoundTrip
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
- Fill
- donchian-breakout-short.strategy.yaml
- IndicatorReferenceTest.php
- support.mjs
- RunBacktestCommandTest.php
- .isPositive
- BinanceArchive
- StrategySpec
- HealthChecks
- donchian-breakout.strategy.yaml
- Report
- SimClock
- App\Strategy\Dsl\Document\DocumentChecker
- AccountRuntime.php
- Engine
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
- PolicyLimits
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
- OrderRequest
- TradingServer.php
- CandleFeedTest.php
- buy-and-hold.strategy.yaml
- ChartSeriesTest.php
- TokensTest.php
- ArchiveImportTest.php
- AppServiceProvider.php
- Illuminate\Support\Facades\Schema
- BinanceArchive
- Engine.php
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
- 2026_11_09_000009_create_exchange_keys_table.php
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
- ChartScreen.php
- Illuminate\Http\Client\Factory
- CandleStreamTest.php
- 2026_11_09_000012_create_account_results_tables.php
- Illuminate\Database\Migrations\Migration
- MovingAverageTrend
- Illuminate\Foundation\Testing\RefreshDatabase
- SimulationJobsTest.php
- .run
- 2026_11_09_000013_add_daily_report_to_notification_settings.php
- InstrumentSync.php
- BuyAndHold

## God Nodes (most connected - your core abstractions)
1. `Decimal` - 596 edges
2. `Symbol` - 251 edges
3. `Side` - 215 edges
4. `Candle` - 168 edges
5. `Timeframe` - 154 edges
6. `Price` - 116 edges
7. `Clock` - 112 edges
8. `App` - 100 edges
9. `Position` - 96 edges
10. `MarketType` - 86 edges

## Surprising Connections (you probably didn't know these)
- `{closure#6}()` --calls--> `App`  [INFERRED]
  tests/Feature/Market/ScalpingArchiveTest.php → app/Notifications/SettingSource.php
- `{closure#8}()` --calls--> `App`  [INFERRED]
  tests/Feature/Market/ScalpingArchiveTest.php → app/Notifications/SettingSource.php
- `{closure#11}()` --calls--> `App`  [INFERRED]
  tests/Feature/Market/ScalpingArchiveTest.php → app/Notifications/SettingSource.php
- `{closure#13}()` --calls--> `App`  [INFERRED]
  tests/Feature/Web/SettingsScreenTest.php → app/Notifications/SettingSource.php
- `{closure#8}()` --calls--> `App`  [INFERRED]
  tests/Feature/Web/SettingsScreenTest.php → app/Notifications/SettingSource.php

## Import Cycles
- None detected.

## Communities (191 total, 45 thin omitted)

### Community 0 - "InstrumentRules"
Cohesion: 0.07
Nodes (31): IntentRecord, {closure#1}(), AccountView, RejectionReason, BelowMinimum, Breaker, Cooldown, DuplicateIntent (+23 more)

### Community 1 - "EngineTest.php"
Cohesion: 0.12
Nodes (35): EngineCommand, ScriptedStrategy, {closure#10}(), {closure#13}(), {closure#14}(), {closure#16}(), {closure#17}(), {closure#2}() (+27 more)

### Community 2 - "CanonicalJson"
Cohesion: 0.24
Nodes (7): {closure#1}(), CanonicalJson, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}()

### Community 3 - "DslError"
Cohesion: 0.11
Nodes (14): {closure#1}(), {closure#1}(), {closure#8}(), DslError, YamlList, YamlReader, YamlScalar, YamlValue (+6 more)

### Community 4 - "Asset"
Cohesion: 0.22
Nodes (4): Marks, Asset, {closure#25}(), {closure#9}()

### Community 5 - ".parse"
Cohesion: 0.12
Nodes (33): baselinePairs(), {closure#11}(), {closure#14}(), {closure#17}(), {closure#5}(), carryDecisions(), {closure#13}(), {closure#14}() (+25 more)

### Community 6 - "DocumentChecker"
Cohesion: 0.27
Nodes (3): CompileStrategy, DocumentChecker, YamlMap

### Community 7 - ".times"
Cohesion: 0.08
Nodes (7): PerpPosition, {closure#1}(), {closure#2}(), {closure#3}(), {closure#2}(), InvalidNumber, Perpetual

### Community 8 - "CompiledStrategy.php"
Cohesion: 0.10
Nodes (4): CompiledStrategy, {closure#33}(), Compiler, Program

### Community 9 - "Side"
Cohesion: 0.10
Nodes (68): Side, Buy, Sell, btc(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}() (+60 more)

### Community 10 - "Instrument"
Cohesion: 0.06
Nodes (17): BackfillStep, CandleRepository, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), CandleSource, Archive (+9 more)

### Community 11 - ".measure"
Cohesion: 0.19
Nodes (9): EquityTracker, Performance, {closure#1}(), {closure#2}(), {closure#3}(), {closure#5}(), {closure#6}(), {closure#8}() (+1 more)

### Community 12 - "Candle"
Cohesion: 0.11
Nodes (29): Candle, InvalidCandle, KlineMessage, {closure#1}(), {closure#2}(), candleWith(), {closure#1}(), {closure#10}() (+21 more)

### Community 13 - "ExchangeVerificationTest.php"
Cohesion: 0.12
Nodes (27): {closure#1}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}() (+19 more)

### Community 14 - "IndicatorsTest.php"
Cohesion: 0.09
Nodes (21): Atr, Donchian, Ema, Sma, bar(), {closure#1}(), {closure#10}(), {closure#12}() (+13 more)

### Community 15 - "RiskPolicy"
Cohesion: 0.12
Nodes (20): RiskProfile, Account, Benchmark, RiskPolicy, Participant, {closure#1}(), {closure#2}(), {closure#3}() (+12 more)

### Community 16 - "Node"
Cohesion: 0.11
Nodes (21): Binary, Lexer, Parser, Token, TokenKind, Colon, Comma, Dot (+13 more)

### Community 17 - "Clock"
Cohesion: 0.07
Nodes (16): AuthenticateMcpToken, FlattenEngine, HaltEngine, IssuesCommands, PauseStrategy, ResumeEngine, CommandKind, Flatten (+8 more)

### Community 18 - "App\Support\Clock\Clock"
Cohesion: 0.08
Nodes (11): AccountRuntime, RunEngine, CandleFeed, SendDailyReport, DailyReport, {closure#2}(), {closure#1}(), {closure#2}() (+3 more)

### Community 19 - "ScreensTest.php"
Cohesion: 0.08
Nodes (15): ChartScreen, User, {closure#1}(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}() (+7 more)

### Community 20 - "LedgerTest.php"
Cohesion: 0.15
Nodes (30): Ledger, LedgerViolation, balanceOf(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}() (+22 more)

### Community 21 - "Illuminate\Database\Schema\Blueprint"
Cohesion: 0.19
Nodes (10): {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#1}(), {closure#2}(), {closure#3}() (+2 more)

### Community 22 - "generate.py"
Cohesion: 0.08
Nodes (30): atr(), bars(), bb(), change(), dmi(), donchian(), ema(), highest() (+22 more)

### Community 24 - "StrategyIdentity"
Cohesion: 0.05
Nodes (18): BuildableStrategy, Close, EntryOrder, Intent, OpenLong, OpenShort, StrategyContext, StrategyIdentity (+10 more)

### Community 25 - ".of"
Cohesion: 0.20
Nodes (43): RiskState, account(), accountPolicy(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}() (+35 more)

### Community 26 - "Illuminate\Console\Command"
Cohesion: 0.09
Nodes (16): CreateMcpToken, RevokeMcpToken, Tokens, BinanceRequestFailed, LocationRestricted, RateLimited, CheckMarketData, SelectsInstruments (+8 more)

### Community 27 - "Symbol"
Cohesion: 0.06
Nodes (27): Income, IncomeKind, Funding, Liquidation, OrderType, Limit, Market, StopMarket (+19 more)

### Community 28 - "SimulatedPerpVenueTest.php"
Cohesion: 0.21
Nodes (24): {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#15}(), {closure#18}(), {closure#19}() (+16 more)

### Community 29 - "Strategy"
Cohesion: 0.20
Nodes (15): {closure#3}(), Strategy, baselineDocument(), {closure#10}(), {closure#12}(), {closure#15}(), {closure#18}(), {closure#20}() (+7 more)

### Community 31 - "Illuminate\Database\ConnectionInterface"
Cohesion: 0.10
Nodes (11): DatabaseCandleFeed, {closure#1}(), Rows, QualityCheck, QualityReport, {closure#1}(), {closure#2}(), {closure#3}() (+3 more)

### Community 32 - ".zero"
Cohesion: 0.12
Nodes (19): Cscv, DeflatedSharpe, closeTo(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}() (+11 more)

### Community 33 - "Account"
Cohesion: 0.05
Nodes (18): OpenAccount, CreateAccount, StoreKeys, Account, JournalEntry, ReauthController, AccountScreen, HaltButton (+10 more)

### Community 34 - "Carbon\CarbonImmutable"
Cohesion: 0.09
Nodes (18): FundingRate, OpenInterest, PriceMinute, TradeFlow, PositionView, {closure#1}(), {closure#10}(), {closure#11}() (+10 more)

### Community 35 - "App"
Cohesion: 0.08
Nodes (31): SendTelegram, App, {closure#1}(), {closure#2}(), {closure#3}(), {closure#1}(), {closure#2}(), {closure#4}() (+23 more)

### Community 36 - "PreparesBacktests.php"
Cohesion: 0.07
Nodes (10): StoredInstruments, ExportStrategies, GridPart, ImportStrategies, {closure#2}(), {closure#5}(), {closure#7}(), {closure#8}() (+2 more)

### Community 37 - ".register"
Cohesion: 0.11
Nodes (17): {closure#3}(), {closure#11}(), {closure#13}(), {closure#14}(), {closure#15}(), {closure#16}(), {closure#4}(), {closure#5}() (+9 more)

### Community 38 - "RiskEventKind"
Cohesion: 0.24
Nodes (6): RiskEvent, RiskEventKind, DailyLossHalt, DrawdownFlatten, DrawdownHalt, WeeklyLossHalt

### Community 39 - "FundingRate"
Cohesion: 0.08
Nodes (31): InstrumentRules, {closure#1}(), RulesHistory, {closure#1}(), Backtest, RunDefinition, ReportBuilder, {closure#1}() (+23 more)

### Community 40 - "Position"
Cohesion: 0.06
Nodes (29): Argument, BoolLiteral, Call, DurationLiteral, Identifier, Lookback, Node, NumberLiteral (+21 more)

### Community 41 - "ema-cross-scalp-long.strategy.yaml"
Cohesion: 0.06
Nodes (33): entries, long, exits, stop_loss, take_profit, time_stop, when, fails_when (+25 more)

### Community 42 - "RoundTrip"
Cohesion: 0.13
Nodes (15): RoundTrip, {closure#1}(), {closure#2}(), {closure#4}(), {closure#6}(), {closure#7}(), {closure#10}(), {closure#2}() (+7 more)

### Community 43 - "SimulatedVenue"
Cohesion: 0.08
Nodes (22): FeeSchedule, Liquidity, Maker, Taker, OrderStatus, Cancelled, Expired, Filled (+14 more)

### Community 44 - "OrderRole"
Cohesion: 0.07
Nodes (37): ClientOrderId, OrderRole, Entry, Exit, Flatten, Stop, Target, ProtectionPlan (+29 more)

### Community 45 - ".walk"
Cohesion: 0.16
Nodes (19): CandleAggregator, CandleSequenceBroken, {closure#3}(), {closure#6}(), CandleFactory, aggregate(), {closure#1}(), {closure#10}() (+11 more)

### Community 46 - "Illuminate\Database\Eloquent\Model"
Cohesion: 0.06
Nodes (13): {closure#1}(), McpCall, McpToken, {closure#5}(), PlainDecimal, Deployment, EngineCommandRow, ExchangeKey (+5 more)

### Community 47 - "package.json"
Cohesion: 0.15
Nodes (17): dependencies, codemirror@^6.0.2, @codemirror/lang-yaml@^6.1.3, @codemirror/language@^6.13.1, @lezer/highlight@^1.2.5, lightweight-charts@^5.2.1, optionalDependencies, @laravel/multiplex@^0.4.1 (+9 more)

### Community 48 - "Window"
Cohesion: 0.08
Nodes (10): {closure#34}(), Change, Highest, Lowest, RealizedVolatility, Roc, StandardDeviation, ValueIndicator (+2 more)

### Community 49 - "BinanceRestClientTest.php"
Cohesion: 0.17
Nodes (14): {closure#9}(), binanceClient(), {closure#1}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#2}(), {closure#3}() (+6 more)

### Community 50 - "ema-cross-scalp-short.strategy.yaml"
Cohesion: 0.06
Nodes (33): entries, short, exits, stop_loss, take_profit, time_stop, when, fails_when (+25 more)

### Community 51 - ".handle"
Cohesion: 0.15
Nodes (3): GridBacktest, ReadsGridOptions, StrategyGates

### Community 52 - "Side"
Cohesion: 0.20
Nodes (23): ExitReason, Flatten, Liquidation, Signal, Stop, Target, {closure#1}(), {closure#10}() (+15 more)

### Community 53 - "Laravel\Mcp\Response"
Cohesion: 0.14
Nodes (5): GetFunding, GetJob, ListTrials, TradingTool, ChartSeries

### Community 54 - "postgres"
Cohesion: 0.10
Nodes (19): POSTGRES_DB, POSTGRES_PASSWORD, POSTGRES_USER, interval, retries, test, timeout, environment (+11 more)

### Community 55 - "MonteCarlo"
Cohesion: 0.15
Nodes (11): {closure#4}(), {closure#5}(), MonteCarlo, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}() (+3 more)

### Community 57 - "donchian-breakout-short.strategy.yaml"
Cohesion: 0.07
Nodes (26): entries, short, default, range, step, default, range, step (+18 more)

### Community 58 - "IndicatorReferenceTest.php"
Cohesion: 0.04
Nodes (26): {closure#36}(), BarIndicator, DonchianChannel, Hma, Keltner, Macd, Natr, Obv (+18 more)

### Community 59 - "support.mjs"
Cohesion: 0.27
Nodes (9): @playwright/test, confirmed(), checkLayout(), EMAIL, freshCode(), PASSWORD, reauthenticate(), totp() (+1 more)

### Community 60 - "RunBacktestCommandTest.php"
Cohesion: 0.10
Nodes (28): BacktestRun, {closure#4}(), {closure#6}(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}() (+20 more)

### Community 61 - ".isPositive"
Cohesion: 0.09
Nodes (13): {closure#2}(), {closure#4}(), {closure#5}(), {closure#6}(), Normal, {closure#1}(), {closure#3}(), {closure#4}() (+5 more)

### Community 62 - "BinanceArchive"
Cohesion: 0.12
Nodes (8): ArchivePeriod, Backfill, {closure#1}(), {closure#2}(), {closure#3}(), BackfillReport, BackfillMarketData, FundingRateRepository

### Community 63 - "StrategySpec"
Cohesion: 0.09
Nodes (17): {closure#6}(), CarryTemplate, {closure#7}(), EntrySpec, ExitSpec, Expression, StrategySpec, {closure#1}() (+9 more)

### Community 64 - "HealthChecks"
Cohesion: 0.10
Nodes (6): CheckHealth, DiskUsage, {closure#1}(), HealthAlerts, HealthChecks, HealthResult

### Community 65 - "donchian-breakout.strategy.yaml"
Cohesion: 0.07
Nodes (26): entries, long, default, range, step, default, range, step (+18 more)

### Community 66 - "Report"
Cohesion: 0.18
Nodes (10): PerformanceMetrics, Report, {closure#21}(), {closure#1}(), {closure#3}(), goldenMinutes(), goldenRates(), perpetualReferencePrice() (+2 more)

### Community 67 - "SimClock"
Cohesion: 0.08
Nodes (28): SimClock, WallClock, candleStore(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}() (+20 more)

### Community 68 - "App\Strategy\Dsl\Document\DocumentChecker"
Cohesion: 0.06
Nodes (34): {closure#1}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#9}(), ParameterSpec, DslWriter, Library (+26 more)

### Community 69 - "AccountRuntime.php"
Cohesion: 0.08
Nodes (6): {closure#1}(), {closure#2}(), {closure#4}(), AccountSnapshot, {closure#1}(), Period

### Community 70 - "Engine"
Cohesion: 0.15
Nodes (4): EngineRecord, Engine, ReconciliationMismatch, {closure#1}()

### Community 72 - "SimulationQueue"
Cohesion: 0.17
Nodes (4): RunBacktest, RunGrid, SimulationTool, SimulationQueue

### Community 73 - "scripts"
Cohesion: 0.15
Nodes (13): scripts, analyse, dev, format, mutate, post-autoload-dump, post-update-cmd, pre-package-uninstall (+5 more)

### Community 74 - "ma-trend-short.strategy.yaml"
Cohesion: 0.07
Nodes (26): entries, short, exits, stop_loss, when, fails_when, family, default (+18 more)

### Community 75 - "FundingCarry"
Cohesion: 0.16
Nodes (3): DonchianBreakout, FundingCarry, Parameters

### Community 76 - ".sqrt"
Cohesion: 0.11
Nodes (21): Statistics, Gates, {closure#1}(), {closure#2}(), {closure#3}(), brokenGate(), {closure#1}(), {closure#3}() (+13 more)

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
Cohesion: 0.14
Nodes (14): DecimalMath, {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#2}() (+6 more)

### Community 82 - "CompiledStrategyTest.php"
Cohesion: 0.14
Nodes (15): KnownInstruments, aggregatedBars(), {closure#11}(), {closure#13}(), {closure#16}(), {closure#17}(), {closure#2}(), {closure#3}() (+7 more)

### Community 83 - "App\Support\Number\Decimal"
Cohesion: 0.05
Nodes (58): {closure#6}(), {closure#1}(), {closure#2}(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#10}(), {closure#5}() (+50 more)

### Community 84 - "pint/composer.json"
Cohesion: 0.20
Nodes (9): config, sort-packages, description, license, name, require, laravel/pint, php (+1 more)

### Community 85 - "HealthTest.php"
Cohesion: 0.16
Nodes (13): {closure#7}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}(), {closure#8}() (+5 more)

### Community 86 - "RunSimulation.php"
Cohesion: 0.14
Nodes (4): RunSimulation, SimulationJob, {closure#4}(), {closure#5}()

### Community 87 - "Timeframe"
Cohesion: 0.09
Nodes (19): {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#2}(), {closure#8}(), Timeframe, FifteenMinutes (+11 more)

### Community 88 - "require-dev"
Cohesion: 0.22
Nodes (9): require-dev, fakerphp/faker, larastan/larastan, laravel/pail, laravel/pao, mockery/mockery, nunomaduro/collision, pestphp/pest (+1 more)

### Community 89 - "scripts-descriptions"
Cohesion: 0.25
Nodes (8): scripts-descriptions, analyse, format, mutate, test, test:network, test:testnet, tools:install

### Community 90 - "PolicyLimits"
Cohesion: 0.19
Nodes (4): InstrumentDirectory, {closure#1}(), PolicyLimits, StrategyRepository

### Community 91 - ".fromStrings"
Cohesion: 0.11
Nodes (12): {closure#9}(), RegimeLabels, {closure#1}(), {closure#42}(), {closure#1}(), {closure#1}(), {closure#2}(), {closure#4}() (+4 more)

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
Cohesion: 0.23
Nodes (6): {closure#1}(), {closure#1}(), {closure#2}(), CheckResult, SaveResult, StrategyVersion

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
Cohesion: 0.10
Nodes (16): DecisionLog, DecisionRecord, EngineMode, Backtest, Live, Paper, Testnet, InMemoryDecisionLog (+8 more)

### Community 103 - "StrategyGates.php"
Cohesion: 0.06
Nodes (24): {closure#11}(), {closure#12}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#6}(), {closure#8}(), {closure#2}() (+16 more)

### Community 104 - "uses"
Cohesion: 0.21
Nodes (12): steps, name, runs-on, steps, frontend, lint, name, runs-on (+4 more)

### Community 105 - "SettingsScreenTest.php"
Cohesion: 0.23
Nodes (11): {closure#10}(), {closure#11}(), {closure#13}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}() (+3 more)

### Community 106 - "devDependencies"
Cohesion: 0.18
Nodes (12): devDependencies, concurrently@^10.0.3, laravel-vite-plugin@^3.1, @playwright/test@1.63.0, tailwindcss@^4.0.0, @tailwindcss/vite@^4.0.0, vite@^8.0.0, concurrently (+4 more)

### Community 108 - "BinanceVenueTest.php"
Cohesion: 0.14
Nodes (13): {closure#2}(), BinanceVenue, binanceVenue(), {closure#1}(), {closure#2}(), {closure#4}(), {closure#5}(), {closure#6}() (+5 more)

### Community 117 - "OrderRequest"
Cohesion: 0.06
Nodes (14): Venue, Balance, OcoRequest, OrderAck, OrderRequest, WarmingVenue, ForwardingVenue, {closure#12}() (+6 more)

### Community 118 - "TradingServer.php"
Cohesion: 0.11
Nodes (6): DslReference, GetStrategy, ListInstruments, ListStrategies, SaveStrategy, TradingServer

### Community 119 - "CandleFeedTest.php"
Cohesion: 0.25
Nodes (15): MinuteCandles, {closure#1}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#2}(), {closure#3}(), {closure#4}() (+7 more)

### Community 120 - "buy-and-hold.strategy.yaml"
Cohesion: 0.12
Nodes (16): entries, long, exits, stop_loss, fails_when, family, hypothesis, when (+8 more)

### Community 121 - "ChartSeriesTest.php"
Cohesion: 0.23
Nodes (10): chart(), {closure#11}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}() (+2 more)

### Community 122 - "TokensTest.php"
Cohesion: 0.31
Nodes (6): {closure#3}(), {closure#4}(), contrastRatio(), contrastRules(), relativeLuminance(), themeTokens()

### Community 123 - "ArchiveImportTest.php"
Cohesion: 0.29
Nodes (10): archiveBytes(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}() (+2 more)

### Community 124 - "AppServiceProvider.php"
Cohesion: 0.05
Nodes (15): Notifier, {closure#1}(), LocalDiskUsage, TelegramNotifier, AppServiceProvider, {closure#3}(), {closure#5}(), {closure#6}() (+7 more)

### Community 126 - "BinanceArchive"
Cohesion: 0.13
Nodes (4): ReplayDivergence, ArchiveMissing, BinanceArchive, ChecksumMismatch

### Community 127 - "Engine.php"
Cohesion: 0.09
Nodes (7): {closure#1}(), {closure#11}(), {closure#2}(), LimitedStrategy, MoveStop, OpenCarry, StrategyLimits

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
Cohesion: 0.17
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
Cohesion: 0.06
Nodes (31): {closure#2}(), BinanceEndpoints, BinanceRestClient, {closure#1}(), EpochTime, ExchangeNumber, Json, RequestFailed (+23 more)

### Community 142 - "PaperEngineTest.php"
Cohesion: 0.16
Nodes (16): AccountEquity, AccountTrade, {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#2}() (+8 more)

### Community 143 - "Percent"
Cohesion: 0.24
Nodes (3): Percent, {closure#14}(), {closure#15}()

### Community 145 - ".of"
Cohesion: 0.12
Nodes (19): AssetMismatch, Money, {closure#2}(), {closure#1}(), usdt(), {closure#11}(), {closure#7}(), {closure#1}() (+11 more)

### Community 146 - "DocumentCheckerTest.php"
Cohesion: 0.30
Nodes (10): checkDocument(), {closure#1}(), {closure#2}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}(), {closure#8}() (+2 more)

### Community 147 - "scripts"
Cohesion: 0.50
Nodes (4): scripts, build, dev, e2e

### Community 148 - "0001_01_01_000002_create_jobs_table.php"
Cohesion: 0.33
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

### Community 151 - "DirectionalMovement"
Cohesion: 0.16
Nodes (3): DirectionalMovement, Rsi, Wilder

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
Cohesion: 0.27
Nodes (3): CompileStrategy, StrategiesScreen, StrategyLibrary

### Community 175 - "CandleStream"
Cohesion: 0.19
Nodes (3): CandleStream, StreamTransport, FakeTransport

### Community 176 - "ChartScreen.php"
Cohesion: 0.07
Nodes (25): {closure#2}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), FunctionSpec, Group, Exit (+17 more)

### Community 177 - "Illuminate\Http\Client\Factory"
Cohesion: 0.16
Nodes (3): BinanceApiError, BinanceUnreachable, SignedBinanceClient

### Community 178 - "CandleStreamTest.php"
Cohesion: 0.23
Nodes (10): Sleeper, SystemSleeper, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), streamMessages() (+2 more)

### Community 181 - "MovingAverageTrend"
Cohesion: 0.17
Nodes (9): MovingAverageTrend, {closure#3}(), {closure#6}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#20}(), {closure#8}() (+1 more)

### Community 182 - "Illuminate\Foundation\Testing\RefreshDatabase"
Cohesion: 0.23
Nodes (7): {closure#1}(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), versions()

### Community 183 - "SimulationJobsTest.php"
Cohesion: 0.21
Nodes (5): {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), savedEmaCross()

### Community 184 - ".run"
Cohesion: 0.35
Nodes (6): btcMinutes(), {closure#1}(), {closure#2}(), {closure#3}(), referencePrice(), GoldenData

### Community 189 - "InstrumentSync.php"
Cohesion: 0.27
Nodes (3): {closure#1}(), InstrumentSync, SyncResult

## Knowledge Gaps
- **142 isolated node(s):** `BelowMinimum`, `Breaker`, `Cooldown`, `DuplicateIntent`, `EntryWorking` (+137 more)
  These have ≤1 connection - possible missing edges. (Counts symbols only; 899 node(s) total have ≤1 connection when file, concept and rationale nodes are included.)
- **45 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `Decimal` connect `App\Support\Number\Decimal` to `InstrumentRules`, `EngineTest.php`, `CanonicalJson`, `Asset`, `EnginePerpTest.php`, `DocumentChecker`, `.times`, `CompiledStrategy.php`, `Side`, `.parse`, `.measure`, `Candle`, `MarketType`, `IndicatorsTest.php`, `RiskPolicy`, `Percent`, `.of`, `App\Support\Clock\Clock`, `DocumentCheckerTest.php`, `LedgerTest.php`, `ReadBack`, `StrategyIdentity`, `.of`, `DirectionalMovement`, `Symbol`, `SimulatedPerpVenueTest.php`, `Strategy`, `Illuminate\Database\ConnectionInterface`, `.zero`, `Account`, `Carbon\CarbonImmutable`, `PreparesBacktests.php`, `RiskEventKind`, `FundingRate`, `Position`, `RoundTrip`, `SimulatedVenue`, `OrderRole`, `.walk`, `Quantity`, `ChartScreen.php`, `Window`, `.handle`, `Side`, `Illuminate\Foundation\Testing\RefreshDatabase`, `MonteCarlo`, `Fill`, `.run`, `IndicatorReferenceTest.php`, `RunBacktestCommandTest.php`, `.isPositive`, `InstrumentSync.php`, `StrategySpec`, `Report`, `SimClock`, `App\Strategy\Dsl\Document\DocumentChecker`, `AccountRuntime.php`, `Engine`, `FundingCarry`, `.sqrt`, `DecimalMath`, `CompiledStrategyTest.php`, `Timeframe`, `PolicyLimits`, `.fromStrings`, `EngineMode`, `StrategyGates.php`, `BinanceVenueTest.php`, `OrderRequest`, `CandleFeedTest.php`, `ChartSeriesTest.php`, `AppServiceProvider.php`, `Engine.php`?**
  _High betweenness centrality (0.170) - this node is a cross-community bridge._
- **What connects `BelowMinimum`, `Breaker`, `Cooldown` to the rest of the system?**
  _142 weakly-connected nodes found - possible missing edges._
- **Should `InstrumentRules` be split into smaller, more focused modules?**
  _Cohesion score 0.07078039927404718 - nodes in this community are weakly interconnected._
- **Why does `Symbol` connect `Symbol` to `InstrumentRules`, `McpServerTest.php`, `CanonicalJson`, `EngineTest.php`, `Asset`, `.parse`, `DocumentChecker`, `.times`, `CompiledStrategy.php`, `Side`, `Instrument`, `EnginePerpTest.php`, `.measure`, `MarketType`, `ExchangeVerificationTest.php`, `RiskPolicy`, `App\Support\Clock\Clock`, `ScreensTest.php`, `LedgerTest.php`, `StrategyIdentity`, `.of`, `Illuminate\Console\Command`, `SimulatedPerpVenueTest.php`, `Strategy`, `Illuminate\Database\ConnectionInterface`, `Account`, `Carbon\CarbonImmutable`, `PreparesBacktests.php`, `.register`, `FundingRate`, `Position`, `RoundTrip`, `SimulatedVenue`, `OrderRole`, `ChartScreen.php`, `CandleStreamTest.php`, `.handle`, `Side`, `Laravel\Mcp\Response`, `MovingAverageTrend`, `Illuminate\Foundation\Testing\RefreshDatabase`, `Fill`, `.run`, `RunBacktestCommandTest.php`, `.isPositive`, `BuyAndHold`, `StrategySpec`, `Report`, `SimClock`, `App\Strategy\Dsl\Document\DocumentChecker`, `AccountRuntime.php`, `Engine`, `FundingCarry`, `CompiledStrategyTest.php`, `Timeframe`, `PolicyLimits`, `EngineMode`, `StrategyGates.php`, `BinanceVenueTest.php`, `OrderRequest`, `CandleFeedTest.php`, `ChartSeriesTest.php`, `Engine.php`?**
  _High betweenness centrality (0.076) - this node is a cross-community bridge._
- **Should `EngineTest.php` be split into smaller, more focused modules?**
  _Cohesion score 0.12255892255892256 - nodes in this community are weakly interconnected._
- **Why does `Timeframe` connect `Timeframe` to `McpServerTest.php`, `EngineTest.php`, `EnginePerpTest.php`, `DocumentChecker`, `.parse`, `CompiledStrategy.php`, `Side`, `Instrument`, `Candle`, `MarketType`, `MarketBreakers`, `IndicatorsTest.php`, `Clock`, `DocumentCheckerTest.php`, `ReadBack`, `StrategyIdentity`, `Symbol`, `SimulatedPerpVenueTest.php`, `Illuminate\Database\ConnectionInterface`, `Position`, `OrderRole`, `.walk`, `ChartScreen.php`, `Window`, `CandleStreamTest.php`, `Laravel\Mcp\Response`, `MovingAverageTrend`, `IndicatorReferenceTest.php`, `BinanceArchive`, `StrategySpec`, `SimClock`, `App\Strategy\Dsl\Document\DocumentChecker`, `AccountRuntime.php`, `FundingCarry`, `.sqrt`, `CompiledStrategyTest.php`, `App\Support\Number\Decimal`, `.fromStrings`, `StrategyGates.php`, `CandleFeedTest.php`, `ChartSeriesTest.php`, `AppServiceProvider.php`?**
  _High betweenness centrality (0.060) - this node is a cross-community bridge._
- **Should `DslError` be split into smaller, more focused modules?**
  _Cohesion score 0.10796221322537113 - nodes in this community are weakly interconnected._