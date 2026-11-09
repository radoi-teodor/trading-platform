# Graph Report - trading-platform  (2026-11-09)

## Corpus Check
- 584 files · ~240,799 words
- Verdict: corpus is large enough that graph structure adds value.
- Unclassified: 101 file(s) not represented in the graph (top: .zip 38, .CHECKSUM 38, (none) 19)

## Summary
- 4646 nodes · 16009 edges · 202 communities (149 shown, 53 thin omitted)
- Extraction: 91% EXTRACTED · 9% INFERRED · 0% AMBIGUOUS · INFERRED: 1417 edges (avg confidence: 0.85)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `3d862396`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- QualityCheck
- EngineTest.php
- CanonicalJson
- DslError
- .of
- .parse
- DocumentChecker
- .zero
- CompiledStrategy.php
- .of
- Instrument
- .measure
- Candle
- ExchangeVerificationTest.php
- IndicatorsTest.php
- PolicyLimits
- Node
- Clock
- RunEngine.php
- ScreensTest.php
- LedgerTest.php
- Illuminate\Database\Schema\Blueprint
- generate.py
- ReadBack
- Symbol
- .of
- ClientOrderId
- Strategy
- .now
- CompiledStrategy.php
- App\Market\Stream\PhrityTransport
- Account
- Price
- StrategiesScreen.php
- Carbon\CarbonImmutable
- App
- PreparesBacktests.php
- SimClock
- .isLessThan
- BacktestTest.php
- App\Strategy\Dsl\Document\DocumentChecker
- ema-cross-scalp-long.strategy.yaml
- Decimal
- SimulatedVenue
- User
- .walk
- Illuminate\Database\Eloquent\Model
- package.json
- .compute
- BinanceRestClientTest.php
- ema-cross-scalp-short.strategy.yaml
- Illuminate\Database\ConnectionInterface
- Side
- Laravel\Mcp\Response
- postgres
- Window
- OrderManagerTest.php
- donchian-breakout-short.strategy.yaml
- IndicatorReferenceTest.php
- support.mjs
- RunBacktestCommandTest.php
- .sqrt
- SettingsScreen
- DecisionRecord
- HealthChecks
- donchian-breakout.strategy.yaml
- .run
- WallClock
- App\Strategy\Dsl\Document\DocumentChecker
- InvalidArgumentException
- Engine
- .evaluate
- SimulationQueue
- scripts
- ma-trend-short.strategy.yaml
- FundingCarry
- .measure
- composer.json
- jobs
- postgres
- ma-trend.strategy.yaml
- DecimalMath
- PerpetualView
- App\Support\Number\Decimal
- pint/composer.json
- HealthTest.php
- RunSimulation.php
- Timeframe
- require-dev
- scripts-descriptions
- BarIndicator
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
- Laravel\Mcp\Response
- StrategyGates.php
- uses
- SettingsScreenTest.php
- devDependencies
- BinanceVenueTest.php
- OrderManagerTest.php
- App\Agents\Mcp\TradingServer
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
- Carbon\CarbonImmutable
- ChartSeries.php
- PaperEngineTest.php
- CandleRepositoryTest.php
- Illuminate\Support\Facades\Schema
- Money
- RuntimeException
- scripts
- 0001_01_01_000002_create_jobs_table.php
- fake-telegram.mjs
- WalkForward.php
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
- GlobalsTest.php
- StrategyLibrary
- App\Support\Clock\Clock
- ChartScreen.php
- StrategyVersionsTest.php
- Percent
- 2026_11_09_000012_create_account_results_tables.php
- DiskUsage
- RiskPolicy
- DocumentCheckerTest.php
- SimulationJobsTest.php
- Quantity
- 2026_11_09_000013_add_daily_report_to_notification_settings.php
- BinanceRestClient
- WarmingVenue
- .number
- ForwardingVenue
- bootstrap/app.php
- 2026_11_09_000016_create_market_universe_tables.php
- 2026_11_09_000009_create_exchange_keys_table.php
- SystemSleeper
- RulesHistory
- ScalpingFunctionsTest.php
- Symbol
- 2026_11_09_000015_create_scalping_tables.php

## God Nodes (most connected - your core abstractions)
1. `Decimal` - 521 edges
2. `Side` - 208 edges
3. `Symbol` - 207 edges
4. `Candle` - 136 edges
5. `Timeframe` - 125 edges
6. `App` - 118 edges
7. `Price` - 100 edges
8. `Position` - 96 edges
9. `Clock` - 84 edges
10. `MarketType` - 75 edges

## Surprising Connections (you probably didn't know these)
- `up()` --calls--> `App`  [INFERRED]
  database/migrations/2026_11_09_000016_create_market_universe_tables.php → app/Notifications/SettingSource.php
- `{closure#8}()` --calls--> `App`  [INFERRED]
  tests/Feature/Notifications/HealthTest.php → app/Notifications/SettingSource.php
- `{closure#2}()` --calls--> `App`  [INFERRED]
  tests/Feature/Web/MarketDataSettingsTest.php → app/Notifications/SettingSource.php
- `{closure#13}()` --calls--> `App`  [INFERRED]
  tests/Feature/Web/SettingsScreenTest.php → app/Notifications/SettingSource.php
- `{closure#8}()` --calls--> `App`  [INFERRED]
  tests/Feature/Web/SettingsScreenTest.php → app/Notifications/SettingSource.php

## Import Cycles
- None detected.

## Communities (202 total, 53 thin omitted)

### Community 1 - "EngineTest.php"
Cohesion: 0.11
Nodes (39): EngineCommand, ScriptedStrategy, {closure#1}(), {closure#10}(), {closure#13}(), {closure#14}(), {closure#16}(), {closure#17}() (+31 more)

### Community 2 - "CanonicalJson"
Cohesion: 0.30
Nodes (6): CanonicalJson, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}()

### Community 3 - "DslError"
Cohesion: 0.14
Nodes (14): {closure#1}(), {closure#1}(), {closure#5}(), {closure#7}(), {closure#8}(), DslError, YamlList, YamlReader (+6 more)

### Community 4 - ".of"
Cohesion: 0.10
Nodes (18): Balance, Marks, Asset, balances(), {closure#25}(), {closure#9}(), {closure#1}(), {closure#11}() (+10 more)

### Community 5 - ".parse"
Cohesion: 0.07
Nodes (56): PositionView, StrategyContext, {closure#17}(), {closure#5}(), {closure#18}(), {closure#37}(), carryDecisions(), {closure#10}() (+48 more)

### Community 7 - ".zero"
Cohesion: 0.09
Nodes (11): Fill, PerpPosition, Position, {closure#1}(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}() (+3 more)

### Community 8 - "CompiledStrategy.php"
Cohesion: 0.20
Nodes (4): {closure#42}(), Compiler, Atr, {closure#1}()

### Community 9 - ".of"
Cohesion: 0.05
Nodes (107): MonteCarlo, {closure#8}(), {closure#1}(), {closure#2}(), {closure#3}(), documentedLimits(), {closure#1}(), {closure#10}() (+99 more)

### Community 10 - "Instrument"
Cohesion: 0.05
Nodes (24): BackfillStep, CandleRepository, {closure#2}(), {closure#4}(), CandleSource, Archive, Rest, Stream (+16 more)

### Community 11 - ".measure"
Cohesion: 0.19
Nodes (9): EquityTracker, Performance, {closure#1}(), {closure#2}(), {closure#3}(), {closure#5}(), {closure#6}(), {closure#8}() (+1 more)

### Community 12 - "Candle"
Cohesion: 0.11
Nodes (35): {closure#1}(), {closure#3}(), Candle, KlineMessage, {closure#9}(), {closure#1}(), {closure#2}(), {closure#1}() (+27 more)

### Community 13 - "ExchangeVerificationTest.php"
Cohesion: 0.12
Nodes (29): {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#2}(), {closure#3}(), {closure#4}() (+21 more)

### Community 14 - "IndicatorsTest.php"
Cohesion: 0.08
Nodes (22): Donchian, Ema, Period, Sma, {closure#5}(), bar(), {closure#1}(), {closure#10}() (+14 more)

### Community 15 - "PolicyLimits"
Cohesion: 0.11
Nodes (9): InstrumentDirectory, StoredInstruments, {closure#1}(), CompileStrategy, GridPart, CheckResult, PolicyLimits, SaveResult (+1 more)

### Community 16 - "Node"
Cohesion: 0.12
Nodes (29): Binary, Node, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}() (+21 more)

### Community 17 - "Clock"
Cohesion: 0.07
Nodes (21): FlattenEngine, HaltEngine, IssuesCommands, PauseStrategy, ResumeEngine, CommandKind, Flatten, Halt (+13 more)

### Community 18 - "RunEngine.php"
Cohesion: 0.06
Nodes (8): AccountRuntime, {closure#4}(), {closure#5}(), RunEngine, {closure#2}(), {closure#3}(), DailyReport, {closure#2}()

### Community 19 - "ScreensTest.php"
Cohesion: 0.11
Nodes (17): {closure#1}(), {closure#1}(), {closure#2}(), {closure#3}(), fakeTestnet(), testnetAccount(), {closure#1}(), {closure#1}() (+9 more)

### Community 20 - "LedgerTest.php"
Cohesion: 0.16
Nodes (34): Ledger, LedgerViolation, Side, Buy, Sell, balanceOf(), {closure#10}(), {closure#11}() (+26 more)

### Community 21 - "Illuminate\Database\Schema\Blueprint"
Cohesion: 0.19
Nodes (10): {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#1}(), {closure#2}(), {closure#3}() (+2 more)

### Community 22 - "generate.py"
Cohesion: 0.07
Nodes (30): atr(), bars(), bb(), change(), dmi(), donchian(), ema(), highest() (+22 more)

### Community 23 - "ReadBack"
Cohesion: 0.11
Nodes (8): EntrySpec, Expression, {closure#1}(), {closure#2}(), {closure#5}(), {closure#6}(), {closure#7}(), ReadBack

### Community 24 - "Symbol"
Cohesion: 0.05
Nodes (13): BuyAndHold, DonchianBreakout, MovingAverageTrend, BuildableStrategy, StrategyIdentity, Subscription, RenamedStrategy, EnterOnceStrategy (+5 more)

### Community 25 - ".of"
Cohesion: 0.06
Nodes (79): AccountView, RejectionReason, BelowMinimum, Breaker, Cooldown, DuplicateIntent, EntryWorking, GrossExposure (+71 more)

### Community 26 - "ClientOrderId"
Cohesion: 0.09
Nodes (11): Venue, ClientOrderId, OcoRequest, OrderAck, OrderRequest, OrderType, Limit, Market (+3 more)

### Community 27 - "Strategy"
Cohesion: 0.14
Nodes (19): LimitedStrategy, Strategy, baselineDocument(), baselinePairs(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}() (+11 more)

### Community 28 - ".now"
Cohesion: 0.08
Nodes (10): BackfillReport, BackfillMarketData, SelectsInstruments, {closure#1}(), {closure#2}(), StreamCandles, {closure#1}(), {closure#2}() (+2 more)

### Community 29 - "CompiledStrategy.php"
Cohesion: 0.10
Nodes (9): {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}(), {closure#8}() (+1 more)

### Community 30 - "App\Market\Stream\PhrityTransport"
Cohesion: 0.12
Nodes (3): PhrityTransport, StreamFailed, FakeTransport

### Community 31 - "Account"
Cohesion: 0.18
Nodes (3): OpenAccount, CreateAccount, StoreKeys

### Community 32 - "Price"
Cohesion: 0.10
Nodes (13): Income, IncomeKind, Funding, Liquidation, OrderUpdate, ProtectionPlan, VenueFill, {closure#1}() (+5 more)

### Community 33 - "StrategiesScreen.php"
Cohesion: 0.10
Nodes (10): ReauthController, AccountScreen, ChartScreen, {closure#3}(), {closure#4}(), {closure#5}(), HaltButton, {closure#1}() (+2 more)

### Community 34 - "Carbon\CarbonImmutable"
Cohesion: 0.06
Nodes (29): ArchivePeriod, {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#3}(), {closure#5}(), {closure#7}() (+21 more)

### Community 35 - "App"
Cohesion: 0.09
Nodes (26): SendTelegram, App, {closure#1}(), {closure#2}(), {closure#3}(), {closure#2}(), {closure#4}(), {closure#5}() (+18 more)

### Community 37 - "SimClock"
Cohesion: 0.09
Nodes (27): SimClock, {closure#1}(), {closure#11}(), {closure#14}(), {closure#15}(), {closure#16}(), {closure#4}(), {closure#6}() (+19 more)

### Community 39 - "BacktestTest.php"
Cohesion: 0.11
Nodes (23): FeeSchedule, CostModel, CandleFeed, FundingRate, Backtest, {closure#2}(), RunBacktest, BacktestResult (+15 more)

### Community 40 - "App\Strategy\Dsl\Document\DocumentChecker"
Cohesion: 0.07
Nodes (16): Argument, BoolLiteral, Call, DurationLiteral, Identifier, Lookback, NumberLiteral, OnTimeframe (+8 more)

### Community 41 - "ema-cross-scalp-long.strategy.yaml"
Cohesion: 0.06
Nodes (33): entries, long, exits, stop_loss, take_profit, time_stop, when, fails_when (+25 more)

### Community 42 - "Decimal"
Cohesion: 0.05
Nodes (38): {closure#6}(), {closure#2}(), RoundTrip, {closure#1}(), {closure#2}(), {closure#3}(), {closure#10}(), {closure#5}() (+30 more)

### Community 43 - "SimulatedVenue"
Cohesion: 0.06
Nodes (21): Liquidity, Maker, Taker, OrderStatus, Cancelled, Expired, Filled, New (+13 more)

### Community 44 - "User"
Cohesion: 0.10
Nodes (3): User, UserFactory, {closure#1}()

### Community 45 - ".walk"
Cohesion: 0.14
Nodes (19): CandleAggregator, CandleSequenceBroken, {closure#3}(), {closure#6}(), CandleFactory, aggregate(), {closure#1}(), {closure#10}() (+11 more)

### Community 46 - "Illuminate\Database\Eloquent\Model"
Cohesion: 0.06
Nodes (12): McpCall, McpToken, AccountSnapshot, PlainDecimal, Deployment, ExchangeKey, {closure#1}(), InstrumentFilter (+4 more)

### Community 47 - "package.json"
Cohesion: 0.15
Nodes (17): dependencies, codemirror@^6.0.2, @codemirror/lang-yaml@^6.1.3, @codemirror/language@^6.13.1, @lezer/highlight@^1.2.5, lightweight-charts@^5.2.1, optionalDependencies, @laravel/multiplex@^0.4.1 (+9 more)

### Community 49 - "BinanceRestClientTest.php"
Cohesion: 0.08
Nodes (34): Sleeper, {closure#3}(), {closure#13}(), {closure#5}(), binanceClient(), {closure#1}(), {closure#11}(), {closure#12}() (+26 more)

### Community 50 - "ema-cross-scalp-short.strategy.yaml"
Cohesion: 0.06
Nodes (33): entries, short, exits, stop_loss, take_profit, time_stop, when, fails_when (+25 more)

### Community 51 - "Illuminate\Database\ConnectionInterface"
Cohesion: 0.11
Nodes (5): FundingRateRepository, GridBacktest, ReadsGridOptions, StrategyGates, StrategySpec

### Community 52 - "Side"
Cohesion: 0.17
Nodes (23): ExitReason, Flatten, Liquidation, Signal, Stop, Target, {closure#1}(), {closure#11}() (+15 more)

### Community 53 - "Laravel\Mcp\Response"
Cohesion: 0.10
Nodes (9): GetFunding, GetJob, GetStrategy, ListStrategies, {closure#1}(), ListTrials, SaveStrategy, TradingTool (+1 more)

### Community 54 - "postgres"
Cohesion: 0.10
Nodes (19): POSTGRES_DB, POSTGRES_PASSWORD, POSTGRES_USER, interval, retries, test, timeout, environment (+11 more)

### Community 55 - "Window"
Cohesion: 0.10
Nodes (5): DonchianChannel, Lowest, Stochastic, Vwma, Window

### Community 56 - "OrderManagerTest.php"
Cohesion: 0.11
Nodes (16): OrderRole, Entry, Exit, Flatten, Stop, Target, TrackedOrder, {closure#1}() (+8 more)

### Community 57 - "donchian-breakout-short.strategy.yaml"
Cohesion: 0.07
Nodes (26): entries, short, default, range, step, default, range, step (+18 more)

### Community 58 - "IndicatorReferenceTest.php"
Cohesion: 0.05
Nodes (26): {closure#43}(), Bollinger, Change, Highest, Hma, Macd, Obv, RealizedVolatility (+18 more)

### Community 59 - "support.mjs"
Cohesion: 0.27
Nodes (9): @playwright/test, confirmed(), checkLayout(), EMAIL, freshCode(), PASSWORD, reauthenticate(), totp() (+1 more)

### Community 60 - "RunBacktestCommandTest.php"
Cohesion: 0.10
Nodes (29): BacktestRun, {closure#1}(), {closure#4}(), {closure#6}(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}() (+21 more)

### Community 61 - ".sqrt"
Cohesion: 0.15
Nodes (14): DecimalMath, {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#2}() (+6 more)

### Community 62 - "SettingsScreen"
Cohesion: 0.10
Nodes (4): CreateMcpToken, RevokeMcpToken, Tokens, SettingsScreen

### Community 63 - "DecisionRecord"
Cohesion: 0.14
Nodes (6): DecisionLog, DecisionRecord, IntentRecord, {closure#1}(), DatabaseDecisionLog, JournalEntry

### Community 64 - "HealthChecks"
Cohesion: 0.23
Nodes (3): {closure#1}(), HealthChecks, HealthResult

### Community 65 - "donchian-breakout.strategy.yaml"
Cohesion: 0.07
Nodes (26): entries, long, default, range, step, default, range, step (+18 more)

### Community 66 - ".run"
Cohesion: 0.09
Nodes (27): PerformanceMetrics, Report, btcMinutes(), {closure#1}(), {closure#2}(), {closure#3}(), referencePrice(), {closure#13}() (+19 more)

### Community 67 - "WallClock"
Cohesion: 0.33
Nodes (3): WallClock, {closure#1}(), {closure#6}()

### Community 68 - "App\Strategy\Dsl\Document\DocumentChecker"
Cohesion: 0.07
Nodes (34): {closure#2}(), {closure#3}(), {closure#4}(), {closure#9}(), ParameterSpec, DslWriter, FunctionSpec, Library (+26 more)

### Community 69 - "InvalidArgumentException"
Cohesion: 0.05
Nodes (22): {closure#1}(), {closure#2}(), EngineMode, Backtest, Live, Paper, Testnet, {closure#1}() (+14 more)

### Community 70 - "Engine"
Cohesion: 0.14
Nodes (3): EngineRecord, Engine, ReconciliationMismatch

### Community 71 - ".evaluate"
Cohesion: 0.24
Nodes (9): Gates, brokenGate(), {closure#1}(), {closure#3}(), {closure#4}(), {closure#6}(), {closure#7}(), gate() (+1 more)

### Community 72 - "SimulationQueue"
Cohesion: 0.16
Nodes (4): RunBacktest, RunGrid, SimulationTool, SimulationQueue

### Community 73 - "scripts"
Cohesion: 0.15
Nodes (13): scripts, analyse, dev, format, mutate, post-autoload-dump, post-update-cmd, pre-package-uninstall (+5 more)

### Community 74 - "ma-trend-short.strategy.yaml"
Cohesion: 0.07
Nodes (26): entries, short, exits, stop_loss, when, fails_when, family, default (+18 more)

### Community 76 - ".measure"
Cohesion: 0.15
Nodes (12): {closure#1}(), {closure#2}(), Statistics, {closure#3}(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}() (+4 more)

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
Cohesion: 0.10
Nodes (22): {closure#3}(), {closure#4}(), Cscv, DeflatedSharpe, Normal, closeTo(), {closure#1}(), {closure#10}() (+14 more)

### Community 83 - "App\Support\Number\Decimal"
Cohesion: 0.08
Nodes (50): {closure#8}(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#15}() (+42 more)

### Community 84 - "pint/composer.json"
Cohesion: 0.20
Nodes (9): config, sort-packages, description, license, name, require, laravel/pint, php (+1 more)

### Community 85 - "HealthTest.php"
Cohesion: 0.14
Nodes (19): Account, {closure#1}(), {closure#2}(), {closure#3}(), {closure#6}(), {closure#7}(), reportAccount(), reportEvent() (+11 more)

### Community 86 - "RunSimulation.php"
Cohesion: 0.14
Nodes (3): ImportMarketData, RunSimulation, SimulationJob

### Community 87 - "Timeframe"
Cohesion: 0.09
Nodes (17): InvalidCandle, CarryTemplate, {closure#2}(), {closure#8}(), Timeframe, FifteenMinutes, FiveMinutes, FourHours (+9 more)

### Community 88 - "require-dev"
Cohesion: 0.22
Nodes (9): require-dev, fakerphp/faker, larastan/larastan, laravel/pail, laravel/pao, mockery/mockery, nunomaduro/collision, pestphp/pest (+1 more)

### Community 89 - "scripts-descriptions"
Cohesion: 0.25
Nodes (8): scripts-descriptions, analyse, format, mutate, test, test:network, test:testnet, tools:install

### Community 90 - "BarIndicator"
Cohesion: 0.09
Nodes (6): BarIndicator, DirectionalMovement, Keltner, Natr, Vwap, Wilder

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
Cohesion: 0.31
Nodes (3): {closure#1}(), {closure#2}(), StrategyVersion

### Community 97 - "jobs"
Cohesion: 0.25
Nodes (8): name, runs-on, name, runs-on, steps, jobs, analyse, audit

### Community 98 - ".parse"
Cohesion: 0.19
Nodes (7): AstPrinter, Lexer, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), sexpr()

### Community 99 - "psr-4"
Cohesion: 0.40
Nodes (5): autoload, psr-4, App\\, Database\\Factories\\, Database\\Seeders\\

### Community 100 - "require"
Cohesion: 0.25
Nodes (8): require, laravel/fortify, laravel/framework, laravel/mcp, laravel/tinker, livewire/livewire, php, phrity/websocket

### Community 102 - "Laravel\Mcp\Response"
Cohesion: 0.22
Nodes (4): ChartTool, GetCandles, GetIndicators, ChartSeries

### Community 103 - "StrategyGates.php"
Cohesion: 0.07
Nodes (21): {closure#2}(), {closure#3}(), {closure#4}(), {closure#6}(), {closure#8}(), {closure#2}(), {closure#1}(), {closure#2}() (+13 more)

### Community 104 - "uses"
Cohesion: 0.21
Nodes (12): steps, name, runs-on, steps, frontend, lint, name, runs-on (+4 more)

### Community 105 - "SettingsScreenTest.php"
Cohesion: 0.21
Nodes (12): {closure#10}(), {closure#11}(), {closure#13}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}() (+4 more)

### Community 106 - "devDependencies"
Cohesion: 0.18
Nodes (12): devDependencies, concurrently@^10.0.3, laravel-vite-plugin@^3.1, @playwright/test@1.63.0, tailwindcss@^4.0.0, @tailwindcss/vite@^4.0.0, vite@^8.0.0, concurrently (+4 more)

### Community 108 - "BinanceVenueTest.php"
Cohesion: 0.11
Nodes (15): {closure#2}(), BinanceVenue, BinanceApiError, BinanceUnreachable, SignedBinanceClient, binanceVenue(), {closure#1}(), {closure#2}() (+7 more)

### Community 117 - "OrderManagerTest.php"
Cohesion: 0.36
Nodes (14): btcUsdt(), {closure#10}(), {closure#15}(), {closure#16}(), {closure#2}(), {closure#4}(), {closure#5}(), {closure#6}() (+6 more)

### Community 118 - "App\Agents\Mcp\TradingServer"
Cohesion: 0.17
Nodes (3): DslReference, ListInstruments, TradingServer

### Community 119 - "CandleFeedTest.php"
Cohesion: 0.34
Nodes (13): {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#2}(), {closure#4}(), {closure#5}() (+5 more)

### Community 120 - "buy-and-hold.strategy.yaml"
Cohesion: 0.12
Nodes (16): entries, long, exits, stop_loss, fails_when, family, hypothesis, when (+8 more)

### Community 121 - "ChartSeriesTest.php"
Cohesion: 0.21
Nodes (11): chart(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#6}() (+3 more)

### Community 122 - "TokensTest.php"
Cohesion: 0.31
Nodes (6): {closure#3}(), {closure#4}(), contrastRatio(), contrastRules(), relativeLuminance(), themeTokens()

### Community 123 - "ArchiveImportTest.php"
Cohesion: 0.26
Nodes (10): archiveBytes(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}() (+2 more)

### Community 124 - "FortifyServiceProvider.php"
Cohesion: 0.14
Nodes (7): AppServiceProvider, {closure#3}(), {closure#4}(), {closure#7}(), {closure#3}(), {closure#4}(), FortifyServiceProvider

### Community 126 - "BinanceArchive"
Cohesion: 0.14
Nodes (5): BinanceArchive, ExchangeNumber, TradeFlowBuilder, {closure#1}(), {closure#2}()

### Community 127 - "App\Market\Models\Instrument"
Cohesion: 0.11
Nodes (21): {closure#2}(), {closure#4}(), {closure#6}(), {closure#8}(), ScalpingRepository, {closure#1}(), {closure#2}(), {closure#2}() (+13 more)

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
Cohesion: 0.10
Nodes (10): AuthenticateMcpToken, Notifier, SendDailyReport, NotificationSettings, SettingSource, Env, None, TelegramClient (+2 more)

### Community 133 - "EnginePerpTest.php"
Cohesion: 0.17
Nodes (17): InMemoryDecisionLog, {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#2}(), {closure#5}() (+9 more)

### Community 135 - "settings-screen.blade.php"
Cohesion: 0.20
Nodes (9): confirm, forgetKey, forgetTelegram, monitor, notify, removeInstrument, revokeToken, setUniverse (+1 more)

### Community 137 - "0001_01_01_000000_create_users_table.php"
Cohesion: 0.33
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

### Community 138 - "steps"
Cohesion: 0.33
Nodes (6): name, runs-on, services, steps, e2e, if

### Community 139 - "2026_11_09_000014_create_health_tables.php"
Cohesion: 0.33
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

### Community 140 - "Carbon\CarbonImmutable"
Cohesion: 0.29
Nodes (7): {closure#4}(), BreakerEvent, MarketBreakers, breakerMinute(), {closure#1}(), {closure#2}(), {closure#3}()

### Community 141 - "ChartSeries.php"
Cohesion: 0.13
Nodes (5): {closure#1}(), {closure#2}(), {closure#1}(), {closure#2}(), {closure#5}()

### Community 142 - "PaperEngineTest.php"
Cohesion: 0.16
Nodes (18): AccountEquity, AccountTrade, {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#2}() (+10 more)

### Community 143 - "CandleRepositoryTest.php"
Cohesion: 0.28
Nodes (11): candleStore(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}() (+3 more)

### Community 145 - "Money"
Cohesion: 0.18
Nodes (5): AssetMismatch, Money, {closure#2}(), {closure#1}(), usdt()

### Community 146 - "RuntimeException"
Cohesion: 0.16
Nodes (6): ReplayDivergence, ArchiveMissing, ChecksumMismatch, BinanceRequestFailed, LocationRestricted, RateLimited

### Community 147 - "scripts"
Cohesion: 0.50
Nodes (4): scripts, build, dev, e2e

### Community 148 - "0001_01_01_000002_create_jobs_table.php"
Cohesion: 0.33
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

### Community 151 - "WalkForward.php"
Cohesion: 0.14
Nodes (10): {closure#11}(), {closure#12}(), {closure#1}(), {closure#3}(), {closure#5}(), {closure#6}(), {closure#7}(), {closure#8}() (+2 more)

### Community 157 - "strategies-screen.blade.php"
Cohesion: 0.40
Nodes (4): open, promote, run, save

### Community 159 - "extra"
Cohesion: 0.67
Nodes (3): extra, laravel, dont-discover

### Community 174 - "StrategyLibrary"
Cohesion: 0.31
Nodes (3): CompileStrategy, StrategiesScreen, StrategyLibrary

### Community 175 - "App\Support\Clock\Clock"
Cohesion: 0.08
Nodes (6): Backfill, BinanceEndpoints, CandleStream, ScalpingCollector, StreamTransport, HealthAlerts

### Community 176 - "ChartScreen.php"
Cohesion: 0.09
Nodes (18): {closure#2}(), Group, Exit, Indicator, Order, Perpetual, Position, Price (+10 more)

### Community 177 - "StrategyVersionsTest.php"
Cohesion: 0.16
Nodes (14): {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), versions(), KnownInstruments, {closure#1}() (+6 more)

### Community 178 - "Percent"
Cohesion: 0.24
Nodes (3): Percent, {closure#14}(), {closure#15}()

### Community 180 - "DiskUsage"
Cohesion: 0.16
Nodes (4): DiskUsage, LocalDiskUsage, {closure#6}(), FakeDisk

### Community 181 - "RiskPolicy"
Cohesion: 0.15
Nodes (10): RiskProfile, Account, Benchmark, RiskPolicy, {closure#2}(), {closure#3}(), Policies, {closure#26}() (+2 more)

### Community 182 - "DocumentCheckerTest.php"
Cohesion: 0.30
Nodes (10): checkDocument(), {closure#1}(), {closure#2}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}(), {closure#8}() (+2 more)

### Community 183 - "SimulationJobsTest.php"
Cohesion: 0.10
Nodes (11): {closure#1}(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), savedEmaCross(), {closure#2}(), {closure#3}() (+3 more)

### Community 184 - "Quantity"
Cohesion: 0.24
Nodes (3): Quantity, {closure#10}(), {closure#9}()

### Community 189 - "BinanceRestClient"
Cohesion: 0.06
Nodes (30): {closure#1}(), {closure#2}(), BinanceRestClient, EpochTime, Json, RequestFailed, SyncInstruments, InstrumentDefinition (+22 more)

### Community 191 - ".number"
Cohesion: 0.29
Nodes (4): {closure#4}(), {closure#40}(), {closure#41}(), {closure#57}()

### Community 193 - "bootstrap/app.php"
Cohesion: 0.32
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

### Community 195 - "2026_11_09_000016_create_market_universe_tables.php"
Cohesion: 0.40
Nodes (3): {closure#1}(), {closure#2}(), up()

### Community 198 - "RulesHistory"
Cohesion: 0.24
Nodes (12): InstrumentRules, {closure#1}(), RulesHistory, {closure#2}(), {closure#4}(), {closure#41}(), {closure#1}(), {closure#2}() (+4 more)

### Community 199 - "ScalpingFunctionsTest.php"
Cohesion: 0.24
Nodes (10): backtestableScalpingHour(), {closure#2}(), {closure#3}(), {closure#5}(), {closure#6}(), {closure#7}(), {closure#8}(), referencePart() (+2 more)

### Community 202 - "Symbol"
Cohesion: 0.07
Nodes (15): VenuePosition, {closure#2}(), {closure#3}(), {closure#4}(), {closure#1}(), SimulatedPosition, Close, EntryOrder (+7 more)

### Community 205 - "2026_11_09_000015_create_scalping_tables.php"
Cohesion: 0.33
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

## Knowledge Gaps
- **144 isolated node(s):** `forgetTelegram`, `testTelegram`, `confirm`, `notify`, `monitor` (+139 more)
  These have ≤1 connection - possible missing edges. (Counts symbols only; 902 node(s) total have ≤1 connection when file, concept and rationale nodes are included.)
- **53 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `Decimal` connect `Decimal` to `QualityCheck`, `EngineTest.php`, `CanonicalJson`, `DslError`, `.of`, `.parse`, `DocumentChecker`, `.zero`, `CompiledStrategy.php`, `.of`, `Instrument`, `.measure`, `Candle`, `EnginePerpTest.php`, `IndicatorsTest.php`, `PolicyLimits`, `Node`, `Money`, `LedgerTest.php`, `WalkForward.php`, `Symbol`, `.of`, `ClientOrderId`, `ReadBack`, `Strategy`, `CompiledStrategy.php`, `Price`, `StrategiesScreen.php`, `Carbon\CarbonImmutable`, `PreparesBacktests.php`, `SimClock`, `.isLessThan`, `BacktestTest.php`, `App\Strategy\Dsl\Document\DocumentChecker`, `SimulatedVenue`, `.walk`, `.compute`, `StrategyVersionsTest.php`, `Percent`, `Illuminate\Database\ConnectionInterface`, `Side`, `RiskPolicy`, `DocumentCheckerTest.php`, `Window`, `OrderManagerTest.php`, `Quantity`, `IndicatorReferenceTest.php`, `RunBacktestCommandTest.php`, `BinanceRestClient`, `WarmingVenue`, `.sqrt`, `ForwardingVenue`, `.run`, `App\Strategy\Dsl\Document\DocumentChecker`, `InvalidArgumentException`, `Engine`, `RulesHistory`, `.evaluate`, `Symbol`, `FundingCarry`, `.measure`, `DecimalMath`, `PerpetualView`, `App\Support\Number\Decimal`, `Timeframe`, `BarIndicator`, `.fromStrings`, `StrategyGates.php`, `BinanceVenueTest.php`, `OrderManagerTest.php`, `CandleFeedTest.php`, `ChartSeriesTest.php`, `App\Market\Models\Instrument`?**
  _High betweenness centrality (0.208) - this node is a cross-community bridge._
- **What connects `forgetTelegram`, `testTelegram`, `confirm` to the rest of the system?**
  _144 weakly-connected nodes found - possible missing edges._
- **Should `EngineTest.php` be split into smaller, more focused modules?**
  _Cohesion score 0.10987726475745178 - nodes in this community are weakly interconnected._
- **Why does `Symbol` connect `Symbol` to `EngineTest.php`, `CanonicalJson`, `DslError`, `.of`, `.parse`, `DocumentChecker`, `.zero`, `EnginePerpTest.php`, `.of`, `Instrument`, `.measure`, `ExchangeVerificationTest.php`, `PolicyLimits`, `CandleRepositoryTest.php`, `RunEngine.php`, `ScreensTest.php`, `LedgerTest.php`, `ReadBack`, `Symbol`, `.of`, `ClientOrderId`, `Strategy`, `.now`, `Price`, `StrategiesScreen.php`, `SimClock`, `BacktestTest.php`, `Decimal`, `SimulatedVenue`, `.compute`, `StrategyVersionsTest.php`, `Illuminate\Database\ConnectionInterface`, `Side`, `OrderManagerTest.php`, `RunBacktestCommandTest.php`, `BinanceRestClient`, `WarmingVenue`, `DecisionRecord`, `ForwardingVenue`, `.run`, `InvalidArgumentException`, `RulesHistory`, `Engine`, `FundingCarry`, `Timeframe`, `Laravel\Mcp\Response`, `StrategyGates.php`, `BinanceVenueTest.php`, `OrderManagerTest.php`, `CandleFeedTest.php`, `ChartSeriesTest.php`, `App\Market\Models\Instrument`?**
  _High betweenness centrality (0.049) - this node is a cross-community bridge._
- **Should `DslError` be split into smaller, more focused modules?**
  _Cohesion score 0.13781512605042018 - nodes in this community are weakly interconnected._
- **Why does `Candle` connect `Candle` to `EngineTest.php`, `EnginePerpTest.php`, `.parse`, `.zero`, `CompiledStrategy.php`, `.of`, `Instrument`, `Carbon\CarbonImmutable`, `ExchangeVerificationTest.php`, `IndicatorsTest.php`, `CandleRepositoryTest.php`, `Price`, `Carbon\CarbonImmutable`, `.isLessThan`, `BacktestTest.php`, `Decimal`, `SimulatedVenue`, `.walk`, `App\Support\Clock\Clock`, `BinanceRestClientTest.php`, `Window`, `IndicatorReferenceTest.php`, `BinanceRestClient`, `.run`, `PerpetualView`, `App\Support\Number\Decimal`, `Timeframe`, `BarIndicator`, `.fromStrings`, `StrategyGates.php`, `OrderManagerTest.php`, `CandleFeedTest.php`, `ChartSeriesTest.php`?**
  _High betweenness centrality (0.039) - this node is a cross-community bridge._
- **Should `.of` be split into smaller, more focused modules?**
  _Cohesion score 0.10160427807486631 - nodes in this community are weakly interconnected._