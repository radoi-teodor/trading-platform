# Graph Report - trading-platform  (2026-11-09)

## Corpus Check
- 553 files · ~225,764 words
- Verdict: corpus is large enough that graph structure adds value.
- Unclassified: 93 file(s) not represented in the graph (top: .zip 34, .CHECKSUM 34, (none) 19)

## Summary
- 4365 nodes · 14924 edges · 189 communities (140 shown, 49 thin omitted)
- Extraction: 92% EXTRACTED · 8% INFERRED · 0% AMBIGUOUS · INFERRED: 1182 edges (avg confidence: 0.85)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `35bae66b`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- .of
- EngineTest.php
- App\Livewire\SettingsScreen
- DslError
- .zero
- .parse
- DocumentChecker
- .times
- CompiledStrategy.php
- .of
- Instrument
- .measure
- Candle
- ExchangeVerificationTest.php
- IndicatorsTest.php
- RiskPolicy
- Node
- Clock
- App\Execution\Models\Account
- ScreensTest.php
- LedgerTest.php
- Illuminate\Database\Schema\Blueprint
- generate.py
- ReadBack
- StrategyIdentity
- AppServiceProvider.php
- InvalidArgumentException
- App\Execution\Venues\BinanceVenue
- SimulationJobsTest.php
- Strategy
- PhrityTransport
- PaperEngineTest.php
- .zero
- StrategyVersion
- Money
- TrackedOrder
- PreparesBacktests.php
- SimClock
- Engine.php
- FundingRate
- Position
- ema-cross-scalp-long.strategy.yaml
- Decimal
- SimulatedVenue
- OrderRole
- .walk
- Illuminate\Database\Eloquent\Model
- package.json
- ForwardingVenue
- BinanceRestClientTest.php
- ema-cross-scalp-short.strategy.yaml
- Asset
- Side
- Laravel\Mcp\Response
- postgres
- InstrumentRules
- OrderManagerTest.php
- donchian-breakout-short.strategy.yaml
- IndicatorReferenceTest.php
- support.mjs
- RunBacktestCommandTest.php
- TelegramTest.php
- BinanceArchive
- StrategyIdentity
- RunEngine.php
- donchian-breakout.strategy.yaml
- .run
- CandleRepositoryTest.php
- App\Strategy\Dsl\Document\DocumentChecker
- AccountRuntime.php
- Carbon\CarbonImmutable
- User
- SimulationQueue
- scripts
- ma-trend-short.strategy.yaml
- ReadBack.php
- .sqrt
- composer.json
- jobs
- postgres
- ma-trend.strategy.yaml
- DecimalMath
- CompiledStrategyTest.php
- App\Support\Number\Decimal
- pint/composer.json
- EngineMode
- RunSimulation.php
- Timeframe
- require-dev
- scripts-descriptions
- StrategyLibrary
- .fromStrings
- config
- uses
- test
- with
- Ledger
- jobs
- InstrumentFilter
- psr-4
- require
- QualityReport
- .evaluate
- uses
- SettingsScreenTest.php
- devDependencies
- BinanceVenueTest.php
- ClientOrderId
- TradingServer.php
- CandleFeedTest.php
- buy-and-hold.strategy.yaml
- ChartSeriesTest.php
- TokensTest.php
- ArchiveImportTest.php
- AppServiceProvider.php
- Illuminate\Support\Facades\Schema
- 2026_11_09_000011_create_notification_settings_table.php
- CommandKind
- funding-carry.strategy.yaml
- McpServerTest.php
- env.mjs
- chart.js
- TelegramSettings
- EnginePerpTest.php
- settings-screen.blade.php
- 0001_01_01_000000_create_users_table.php
- steps
- .encode
- Carbon\CarbonImmutable
- MarketType
- Illuminate\Foundation\Testing\RefreshDatabase
- Percent
- BackfillTest.php
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
- .parse
- .reconcile
- ChartScreen.php
- E2eSeeder.php
- VenuePosition
- 2026_11_09_000012_create_account_results_tables.php
- Illuminate\Database\Migrations\Migration
- DatabaseDecisionLog
- .__construct
- TestnetEngineTest.php
- 2026_11_09_000013_add_daily_report_to_notification_settings.php

## God Nodes (most connected - your core abstractions)
1. `Decimal` - 598 edges
2. `Symbol` - 251 edges
3. `Side` - 215 edges
4. `Candle` - 170 edges
5. `Timeframe` - 155 edges
6. `Price` - 116 edges
7. `Clock` - 115 edges
8. `Position` - 96 edges
9. `App` - 89 edges
10. `MarketType` - 87 edges

## Surprising Connections (you probably didn't know these)
- `{closure#6}()` --calls--> `App`  [INFERRED]
  tests/Feature/Notifications/DailyReportTest.php → app/Notifications/SettingSource.php
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

## Communities (189 total, 49 thin omitted)

### Community 0 - ".of"
Cohesion: 0.06
Nodes (74): AccountView, RejectionReason, BelowMinimum, Breaker, Cooldown, DuplicateIntent, EntryWorking, GrossExposure (+66 more)

### Community 1 - "EngineTest.php"
Cohesion: 0.11
Nodes (37): ScriptedStrategy, {closure#1}(), {closure#10}(), {closure#13}(), {closure#14}(), {closure#16}(), {closure#17}(), {closure#18}() (+29 more)

### Community 2 - "App\Livewire\SettingsScreen"
Cohesion: 0.07
Nodes (10): CreateMcpToken, RevokeMcpToken, Tokens, OpenAccount, CreateAccount, StoreKeys, Account, {closure#2}() (+2 more)

### Community 3 - "DslError"
Cohesion: 0.09
Nodes (21): {closure#1}(), AstPrinter, {closure#8}(), DslError, YamlList, YamlReader, YamlScalar, YamlValue (+13 more)

### Community 4 - ".zero"
Cohesion: 0.15
Nodes (5): PerpPosition, {closure#7}(), Perpetual, balances(), {closure#23}()

### Community 5 - ".parse"
Cohesion: 0.10
Nodes (39): {closure#1}(), DonchianBreakout, {closure#11}(), {closure#14}(), {closure#17}(), carryDecisions(), {closure#10}(), {closure#11}() (+31 more)

### Community 7 - ".times"
Cohesion: 0.09
Nodes (6): {closure#2}(), {closure#4}(), {closure#3}(), {closure#6}(), VolumeProfile, InvalidNumber

### Community 8 - "CompiledStrategy.php"
Cohesion: 0.14
Nodes (3): {closure#33}(), Compiler, Program

### Community 9 - ".of"
Cohesion: 0.05
Nodes (103): {closure#6}(), {closure#4}(), {closure#5}(), MonteCarlo, {closure#8}(), {closure#10}(), {closure#11}(), {closure#12}() (+95 more)

### Community 10 - "Instrument"
Cohesion: 0.06
Nodes (19): BackfillStep, CandleRepository, {closure#2}(), {closure#4}(), CandleSource, Archive, Rest, Stream (+11 more)

### Community 11 - ".measure"
Cohesion: 0.18
Nodes (8): EquityTracker, Performance, {closure#1}(), {closure#2}(), {closure#3}(), {closure#5}(), {closure#8}(), trackedTwoDays()

### Community 12 - "Candle"
Cohesion: 0.10
Nodes (36): {closure#1}(), {closure#2}(), {closure#1}(), {closure#3}(), Candle, {closure#9}(), {closure#1}(), {closure#2}() (+28 more)

### Community 13 - "ExchangeVerificationTest.php"
Cohesion: 0.11
Nodes (29): {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}() (+21 more)

### Community 14 - "IndicatorsTest.php"
Cohesion: 0.08
Nodes (24): Atr, Donchian, Ema, Keltner, Period, Sma, {closure#5}(), bar() (+16 more)

### Community 15 - "RiskPolicy"
Cohesion: 0.13
Nodes (12): RiskProfile, Account, Benchmark, {closure#1}(), RiskPolicy, {closure#1}(), {closure#2}(), {closure#3}() (+4 more)

### Community 16 - "Node"
Cohesion: 0.11
Nodes (21): Binary, Lexer, Parser, Token, TokenKind, Colon, Comma, Dot (+13 more)

### Community 17 - "Clock"
Cohesion: 0.05
Nodes (24): AuthenticateMcpToken, McpToken, FlattenEngine, HaltEngine, IssuesCommands, PauseStrategy, ResumeEngine, SyncInstruments (+16 more)

### Community 18 - "App\Execution\Models\Account"
Cohesion: 0.16
Nodes (9): DailyReport, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#6}(), reportAccount(), reportEvent() (+1 more)

### Community 19 - "ScreensTest.php"
Cohesion: 0.14
Nodes (11): {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#7}(), {closure#1}(), {closure#2}() (+3 more)

### Community 20 - "LedgerTest.php"
Cohesion: 0.23
Nodes (25): balanceOf(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#15}(), {closure#16}() (+17 more)

### Community 21 - "Illuminate\Database\Schema\Blueprint"
Cohesion: 0.19
Nodes (10): {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#1}(), {closure#2}(), {closure#3}() (+2 more)

### Community 22 - "generate.py"
Cohesion: 0.08
Nodes (30): atr(), bars(), bb(), change(), dmi(), donchian(), ema(), highest() (+22 more)

### Community 23 - "ReadBack"
Cohesion: 0.13
Nodes (8): ReadBack, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), readBackDocument(), readBackSpec()

### Community 24 - "StrategyIdentity"
Cohesion: 0.04
Nodes (30): {closure#12}(), {closure#2}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#3}(), FundingRate, {closure#1}() (+22 more)

### Community 25 - "AppServiceProvider.php"
Cohesion: 0.24
Nodes (4): Notifier, SystemSleeper, TelegramNotifier, {closure#4}()

### Community 26 - "InvalidArgumentException"
Cohesion: 0.08
Nodes (13): Backfill, BackfillReport, BackfillMarketData, CheckMarketData, SelectsInstruments, {closure#1}(), StreamCandles, VerifyMarketData (+5 more)

### Community 27 - "App\Execution\Venues\BinanceVenue"
Cohesion: 0.17
Nodes (10): Balance, Income, IncomeKind, Funding, Liquidation, OrderUpdate, VenueFill, {closure#1}() (+2 more)

### Community 28 - "SimulationJobsTest.php"
Cohesion: 0.21
Nodes (5): {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), savedEmaCross()

### Community 29 - "Strategy"
Cohesion: 0.17
Nodes (17): {closure#3}(), Strategy, baselineDocument(), baselinePairs(), {closure#10}(), {closure#12}(), {closure#15}(), {closure#18}() (+9 more)

### Community 30 - "PhrityTransport"
Cohesion: 0.12
Nodes (3): PhrityTransport, StreamFailed, FakeTransport

### Community 31 - "PaperEngineTest.php"
Cohesion: 0.15
Nodes (17): AccountEquity, AccountTrade, JournalEntry, {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}() (+9 more)

### Community 32 - ".zero"
Cohesion: 0.10
Nodes (20): Cscv, DeflatedSharpe, Normal, closeTo(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}() (+12 more)

### Community 33 - "StrategyVersion"
Cohesion: 0.08
Nodes (10): {closure#1}(), ReauthController, AccountScreen, ChartScreen, HaltButton, {closure#1}(), ModeBadge, {closure#1}() (+2 more)

### Community 34 - "Money"
Cohesion: 0.15
Nodes (4): Fill, Position, AssetMismatch, Money

### Community 35 - "TrackedOrder"
Cohesion: 0.21
Nodes (9): TrackedOrder, {closure#1}(), {closure#2}(), OrderBook, {closure#1}(), {closure#11}(), {closure#12}(), {closure#13}() (+1 more)

### Community 36 - "PreparesBacktests.php"
Cohesion: 0.04
Nodes (19): FundingRateRepository, {closure#3}(), {closure#8}(), GridBacktest, {closure#2}(), {closure#2}(), {closure#5}(), PreparesBacktests (+11 more)

### Community 37 - "SimClock"
Cohesion: 0.13
Nodes (15): SimClock, WallClock, {closure#1}(), {closure#1}(), {closure#1}(), {closure#2}(), {closure#3}(), definition() (+7 more)

### Community 38 - "Engine.php"
Cohesion: 0.09
Nodes (11): {closure#1}(), {closure#11}(), {closure#2}(), {closure#3}(), {closure#9}(), RiskEvent, RiskEventKind, DailyLossHalt (+3 more)

### Community 39 - "FundingRate"
Cohesion: 0.11
Nodes (20): FeeSchedule, CostModel, CandleFeed, Backtest, {closure#1}(), RunDefinition, ReportBuilder, {closure#3}() (+12 more)

### Community 40 - "Position"
Cohesion: 0.06
Nodes (25): Argument, BoolLiteral, Call, DurationLiteral, Identifier, Lookback, Node, NumberLiteral (+17 more)

### Community 41 - "ema-cross-scalp-long.strategy.yaml"
Cohesion: 0.06
Nodes (33): entries, long, exits, stop_loss, take_profit, time_stop, when, fails_when (+25 more)

### Community 42 - "Decimal"
Cohesion: 0.04
Nodes (53): {closure#6}(), {closure#1}(), RoundTrip, {closure#1}(), {closure#2}(), {closure#3}(), {closure#10}(), {closure#11}() (+45 more)

### Community 43 - "SimulatedVenue"
Cohesion: 0.08
Nodes (20): Liquidity, Maker, Taker, OrderStatus, Cancelled, Expired, Filled, New (+12 more)

### Community 44 - "OrderRole"
Cohesion: 0.13
Nodes (9): OrderRole, Entry, Exit, Flatten, Stop, Target, ProtectionPlan, OrderManager (+1 more)

### Community 45 - ".walk"
Cohesion: 0.18
Nodes (18): CandleAggregator, CandleSequenceBroken, {closure#3}(), {closure#6}(), aggregate(), {closure#1}(), {closure#10}(), {closure#11}() (+10 more)

### Community 46 - "Illuminate\Database\Eloquent\Model"
Cohesion: 0.09
Nodes (9): {closure#1}(), McpCall, AccountSnapshot, PlainDecimal, ExchangeKey, StrategyGateReport, StrategyHoldout, StrategyTrial (+1 more)

### Community 47 - "package.json"
Cohesion: 0.15
Nodes (17): dependencies, codemirror@^6.0.2, @codemirror/lang-yaml@^6.1.3, @codemirror/language@^6.13.1, @lezer/highlight@^1.2.5, lightweight-charts@^5.2.1, optionalDependencies, @laravel/multiplex@^0.4.1 (+9 more)

### Community 48 - "ForwardingVenue"
Cohesion: 0.15
Nodes (4): ForwardingVenue, {closure#22}(), {closure#49}(), OverReportingVenue

### Community 49 - "BinanceRestClientTest.php"
Cohesion: 0.09
Nodes (28): {closure#13}(), {closure#5}(), binanceClient(), {closure#1}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#2}() (+20 more)

### Community 50 - "ema-cross-scalp-short.strategy.yaml"
Cohesion: 0.06
Nodes (33): entries, short, exits, stop_loss, take_profit, time_stop, when, fails_when (+25 more)

### Community 51 - "Asset"
Cohesion: 0.15
Nodes (4): Marks, Asset, {closure#25}(), {closure#9}()

### Community 52 - "Side"
Cohesion: 0.15
Nodes (28): ExitReason, Flatten, Liquidation, Signal, Stop, Target, Side, Buy (+20 more)

### Community 53 - "Laravel\Mcp\Response"
Cohesion: 0.11
Nodes (8): ChartTool, GetCandles, GetFunding, GetIndicators, GetJob, ListTrials, TradingTool, ChartSeries

### Community 54 - "postgres"
Cohesion: 0.10
Nodes (19): POSTGRES_DB, POSTGRES_PASSWORD, POSTGRES_USER, interval, retries, test, timeout, environment (+11 more)

### Community 55 - "InstrumentRules"
Cohesion: 0.25
Nodes (9): RulesHistory, {closure#2}(), {closure#4}(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), rules() (+1 more)

### Community 56 - "OrderManagerTest.php"
Cohesion: 0.30
Nodes (14): btcUsdt(), {closure#10}(), {closure#15}(), {closure#16}(), {closure#2}(), {closure#4}(), {closure#5}(), {closure#6}() (+6 more)

### Community 57 - "donchian-breakout-short.strategy.yaml"
Cohesion: 0.07
Nodes (26): entries, short, default, range, step, default, range, step (+18 more)

### Community 58 - "IndicatorReferenceTest.php"
Cohesion: 0.03
Nodes (33): {closure#34}(), {closure#36}(), BarIndicator, Bollinger, Change, DonchianChannel, Highest, Hma (+25 more)

### Community 59 - "support.mjs"
Cohesion: 0.27
Nodes (9): @playwright/test, confirmed(), checkLayout(), EMAIL, freshCode(), PASSWORD, reauthenticate(), totp() (+1 more)

### Community 60 - "RunBacktestCommandTest.php"
Cohesion: 0.10
Nodes (28): BacktestRun, {closure#4}(), {closure#6}(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}() (+20 more)

### Community 61 - "TelegramTest.php"
Cohesion: 0.12
Nodes (21): SendTelegram, App, {closure#1}(), {closure#2}(), {closure#3}(), {closure#1}(), {closure#10}(), {closure#11}() (+13 more)

### Community 62 - "BinanceArchive"
Cohesion: 0.10
Nodes (6): ReplayDivergence, ArchiveMissing, ArchivePeriod, BinanceArchive, ChecksumMismatch, {closure#5}()

### Community 63 - "StrategyIdentity"
Cohesion: 0.18
Nodes (5): StrategyIdentity, RenamedStrategy, {closure#1}(), {closure#2}(), {closure#3}()

### Community 64 - "RunEngine.php"
Cohesion: 0.12
Nodes (4): AccountRuntime, {closure#5}(), RunEngine, EngineCommandRow

### Community 65 - "donchian-breakout.strategy.yaml"
Cohesion: 0.07
Nodes (26): entries, long, default, range, step, default, range, step (+18 more)

### Community 66 - ".run"
Cohesion: 0.09
Nodes (28): {closure#2}(), Participant, PerformanceMetrics, TradeStatistics, Report, btcMinutes(), {closure#1}(), {closure#2}() (+20 more)

### Community 67 - "CandleRepositoryTest.php"
Cohesion: 0.28
Nodes (11): candleStore(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}() (+3 more)

### Community 68 - "App\Strategy\Dsl\Document\DocumentChecker"
Cohesion: 0.06
Nodes (34): {closure#1}(), {closure#2}(), {closure#1}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#9}(), ParameterSpec (+26 more)

### Community 69 - "AccountRuntime.php"
Cohesion: 0.13
Nodes (3): {closure#1}(), {closure#2}(), {closure#1}()

### Community 70 - "Carbon\CarbonImmutable"
Cohesion: 0.12
Nodes (7): {closure#4}(), DecisionLog, DecisionRecord, EngineRecord, IntentRecord, Engine, {closure#1}()

### Community 72 - "SimulationQueue"
Cohesion: 0.16
Nodes (5): RunBacktest, RunGrid, SimulationTool, {closure#3}(), SimulationQueue

### Community 73 - "scripts"
Cohesion: 0.15
Nodes (13): scripts, analyse, dev, format, mutate, post-autoload-dump, post-update-cmd, pre-package-uninstall (+5 more)

### Community 74 - "ma-trend-short.strategy.yaml"
Cohesion: 0.07
Nodes (26): entries, short, exits, stop_loss, when, fails_when, family, default (+18 more)

### Community 75 - "ReadBack.php"
Cohesion: 0.14
Nodes (9): {closure#6}(), {closure#7}(), EntrySpec, Expression, {closure#1}(), {closure#2}(), {closure#5}(), {closure#6}() (+1 more)

### Community 76 - ".sqrt"
Cohesion: 0.12
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
Cohesion: 0.15
Nodes (14): DecimalMath, {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#2}() (+6 more)

### Community 82 - "CompiledStrategyTest.php"
Cohesion: 0.11
Nodes (17): PositionView, KnownInstruments, {closure#24}(), aggregatedBars(), {closure#11}(), {closure#13}(), {closure#16}(), {closure#17}() (+9 more)

### Community 83 - "App\Support\Number\Decimal"
Cohesion: 0.07
Nodes (41): {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#15}(), {closure#16}() (+33 more)

### Community 84 - "pint/composer.json"
Cohesion: 0.20
Nodes (9): config, sort-packages, description, license, name, require, laravel/pint, php (+1 more)

### Community 85 - "EngineMode"
Cohesion: 0.12
Nodes (10): EngineMode, Backtest, Live, Paper, Testnet, {closure#1}(), {closure#2}(), {closure#4}() (+2 more)

### Community 86 - "RunSimulation.php"
Cohesion: 0.14
Nodes (4): RunSimulation, SimulationJob, {closure#4}(), {closure#5}()

### Community 87 - "Timeframe"
Cohesion: 0.08
Nodes (20): {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), CarryTemplate, {closure#2}(), {closure#8}(), Timeframe (+12 more)

### Community 88 - "require-dev"
Cohesion: 0.22
Nodes (9): require-dev, fakerphp/faker, larastan/larastan, laravel/pail, laravel/pao, mockery/mockery, nunomaduro/collision, pestphp/pest (+1 more)

### Community 89 - "scripts-descriptions"
Cohesion: 0.25
Nodes (8): scripts-descriptions, analyse, format, mutate, test, test:network, test:testnet, tools:install

### Community 90 - "StrategyLibrary"
Cohesion: 0.19
Nodes (5): CompileStrategy, StrategiesScreen, StrategyLibrary, CheckResult, SaveResult

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

### Community 96 - "Ledger"
Cohesion: 0.27
Nodes (5): Ledger, LedgerViolation, {closure#19}(), {closure#12}(), {closure#14}()

### Community 97 - "jobs"
Cohesion: 0.25
Nodes (8): name, runs-on, name, runs-on, steps, jobs, analyse, audit

### Community 98 - "InstrumentFilter"
Cohesion: 0.14
Nodes (3): Deployment, {closure#1}(), InstrumentFilter

### Community 99 - "psr-4"
Cohesion: 0.40
Nodes (5): autoload, psr-4, App\\, Database\\Factories\\, Database\\Seeders\\

### Community 100 - "require"
Cohesion: 0.25
Nodes (8): require, laravel/fortify, laravel/framework, laravel/mcp, laravel/tinker, livewire/livewire, php, phrity/websocket

### Community 103 - ".evaluate"
Cohesion: 0.29
Nodes (7): WalkForward, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), wfSet()

### Community 104 - "uses"
Cohesion: 0.21
Nodes (12): steps, name, runs-on, steps, frontend, lint, name, runs-on (+4 more)

### Community 105 - "SettingsScreenTest.php"
Cohesion: 0.25
Nodes (9): {closure#10}(), {closure#11}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#8}(), confirmed() (+1 more)

### Community 106 - "devDependencies"
Cohesion: 0.18
Nodes (12): devDependencies, concurrently@^10.0.3, laravel-vite-plugin@^3.1, @playwright/test@1.63.0, tailwindcss@^4.0.0, @tailwindcss/vite@^4.0.0, vite@^8.0.0, concurrently (+4 more)

### Community 108 - "BinanceVenueTest.php"
Cohesion: 0.10
Nodes (16): {closure#2}(), BinanceVenue, BinanceApiError, BinanceUnreachable, SignedBinanceClient, binanceVenue(), {closure#1}(), {closure#2}() (+8 more)

### Community 117 - "ClientOrderId"
Cohesion: 0.08
Nodes (13): Venue, ClientOrderId, OcoRequest, OrderAck, OrderRequest, OrderType, Limit, Market (+5 more)

### Community 118 - "TradingServer.php"
Cohesion: 0.11
Nodes (6): DslReference, GetStrategy, ListInstruments, ListStrategies, SaveStrategy, TradingServer

### Community 119 - "CandleFeedTest.php"
Cohesion: 0.44
Nodes (11): {closure#1}(), {closure#12}(), {closure#13}(), {closure#2}(), {closure#4}(), {closure#5}(), {closure#8}(), {closure#9}() (+3 more)

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
Cohesion: 0.29
Nodes (10): archiveBytes(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}() (+2 more)

### Community 124 - "AppServiceProvider.php"
Cohesion: 0.08
Nodes (8): AppServiceProvider, {closure#6}(), {closure#3}(), {closure#4}(), FortifyServiceProvider, {closure#1}(), {closure#2}(), {closure#3}()

### Community 127 - "CommandKind"
Cohesion: 0.20
Nodes (7): CommandKind, Flatten, Halt, Pause, Resume, ResumeStrategy, EngineCommand

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
Cohesion: 0.11
Nodes (7): SendDailyReport, NotificationSettings, SettingSource, Env, None, TelegramClient, TelegramSettings

### Community 133 - "EnginePerpTest.php"
Cohesion: 0.11
Nodes (24): InMemoryDecisionLog, {closure#1}(), {closure#2}(), BacktestResult, ParticipantResult, {closure#1}(), {closure#2}(), {closure#3}() (+16 more)

### Community 135 - "settings-screen.blade.php"
Cohesion: 0.29
Nodes (6): confirm, forgetKey, forgetTelegram, notify, revokeToken, testTelegram

### Community 137 - "0001_01_01_000000_create_users_table.php"
Cohesion: 0.33
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

### Community 138 - "steps"
Cohesion: 0.33
Nodes (6): name, runs-on, services, steps, e2e, if

### Community 139 - ".encode"
Cohesion: 0.16
Nodes (8): RunBacktest, CanonicalJson, {closure#8}(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}()

### Community 140 - "Carbon\CarbonImmutable"
Cohesion: 0.18
Nodes (11): {closure#4}(), MinuteCandles, BreakerEvent, MarketBreakers, {closure#14}(), {closure#3}(), {closure#7}(), breakerMinute() (+3 more)

### Community 141 - "MarketType"
Cohesion: 0.05
Nodes (32): {closure#1}(), {closure#2}(), BinanceEndpoints, BinanceRequestFailed, BinanceRestClient, {closure#1}(), EpochTime, ExchangeNumber (+24 more)

### Community 142 - "Illuminate\Foundation\Testing\RefreshDatabase"
Cohesion: 0.25
Nodes (7): {closure#1}(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), versions()

### Community 143 - "Percent"
Cohesion: 0.24
Nodes (3): Percent, {closure#14}(), {closure#15}()

### Community 144 - "BackfillTest.php"
Cohesion: 0.16
Nodes (13): {closure#11}(), {closure#14}(), {closure#15}(), {closure#16}(), {closure#4}(), {closure#6}(), {closure#7}(), {closure#8}() (+5 more)

### Community 145 - ".of"
Cohesion: 0.16
Nodes (19): {closure#16}(), {closure#2}(), {closure#40}(), {closure#1}(), usdt(), {closure#6}(), {closure#7}(), {closure#1}() (+11 more)

### Community 146 - "DocumentCheckerTest.php"
Cohesion: 0.35
Nodes (9): checkDocument(), {closure#2}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}(), {closure#8}(), {closure#9}() (+1 more)

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

### Community 174 - ".parse"
Cohesion: 0.33
Nodes (6): KlineMessage, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), streamLines()

### Community 176 - "ChartScreen.php"
Cohesion: 0.06
Nodes (27): {closure#2}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), FunctionSpec, Group, Exit (+19 more)

### Community 177 - "E2eSeeder.php"
Cohesion: 0.33
Nodes (3): DatabaseSeeder, {closure#1}(), E2eSeeder

### Community 178 - "VenuePosition"
Cohesion: 0.31
Nodes (4): VenuePosition, {closure#1}(), SimulatedPosition, {closure#13}()

### Community 183 - "TestnetEngineTest.php"
Cohesion: 0.52
Nodes (5): {closure#1}(), {closure#2}(), {closure#3}(), fakeTestnet(), testnetAccount()

## Knowledge Gaps
- **141 isolated node(s):** `forgetTelegram`, `testTelegram`, `confirm`, `notify`, `forgetKey` (+136 more)
  These have ≤1 connection - possible missing edges. (Counts symbols only; 882 node(s) total have ≤1 connection when file, concept and rationale nodes are included.)
- **49 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `Decimal` connect `Decimal` to `.of`, `EngineTest.php`, `App\Livewire\SettingsScreen`, `.zero`, `EnginePerpTest.php`, `.parse`, `.times`, `CompiledStrategy.php`, `.of`, `Instrument`, `.measure`, `Candle`, `MarketType`, `DocumentChecker`, `RiskPolicy`, `IndicatorsTest.php`, `Clock`, `App\Execution\Models\Account`, `.of`, `Percent`, `Illuminate\Foundation\Testing\RefreshDatabase`, `LedgerTest.php`, `ReadBack`, `StrategyIdentity`, `DirectionalMovement`, `DocumentCheckerTest.php`, `App\Execution\Venues\BinanceVenue`, `Strategy`, `.zero`, `Money`, `TrackedOrder`, `PreparesBacktests.php`, `SimClock`, `Engine.php`, `FundingRate`, `Position`, `SimulatedVenue`, `OrderRole`, `.walk`, `Quantity`, `.reconcile`, `ChartScreen.php`, `ForwardingVenue`, `VenuePosition`, `Asset`, `Side`, `.__construct`, `InstrumentRules`, `OrderManagerTest.php`, `IndicatorReferenceTest.php`, `RunBacktestCommandTest.php`, `.encode`, `RunEngine.php`, `.run`, `App\Strategy\Dsl\Document\DocumentChecker`, `AccountRuntime.php`, `Carbon\CarbonImmutable`, `ReadBack.php`, `.sqrt`, `DecimalMath`, `CompiledStrategyTest.php`, `App\Support\Number\Decimal`, `Timeframe`, `.fromStrings`, `Ledger`, `.evaluate`, `BinanceVenueTest.php`, `ClientOrderId`, `CandleFeedTest.php`, `ChartSeriesTest.php`?**
  _High betweenness centrality (0.193) - this node is a cross-community bridge._
- **What connects `forgetTelegram`, `testTelegram`, `confirm` to the rest of the system?**
  _141 weakly-connected nodes found - possible missing edges._
- **Should `.of` be split into smaller, more focused modules?**
  _Cohesion score 0.06415929203539823 - nodes in this community are weakly interconnected._
- **Why does `Symbol` connect `StrategyIdentity` to `.of`, `McpServerTest.php`, `App\Livewire\SettingsScreen`, `EngineTest.php`, `.zero`, `EnginePerpTest.php`, `DocumentChecker`, `.parse`, `CompiledStrategy.php`, `.of`, `Instrument`, `.encode`, `.measure`, `MarketType`, `ExchangeVerificationTest.php`, `Illuminate\Foundation\Testing\RefreshDatabase`, `BackfillTest.php`, `Clock`, `ScreensTest.php`, `LedgerTest.php`, `InvalidArgumentException`, `App\Execution\Venues\BinanceVenue`, `Strategy`, `Money`, `TrackedOrder`, `PreparesBacktests.php`, `SimClock`, `Engine.php`, `FundingRate`, `Decimal`, `SimulatedVenue`, `OrderRole`, `ChartScreen.php`, `BinanceRestClientTest.php`, `VenuePosition`, `Asset`, `Side`, `Laravel\Mcp\Response`, `ForwardingVenue`, `InstrumentRules`, `OrderManagerTest.php`, `RunBacktestCommandTest.php`, `RunEngine.php`, `.run`, `CandleRepositoryTest.php`, `App\Strategy\Dsl\Document\DocumentChecker`, `AccountRuntime.php`, `Carbon\CarbonImmutable`, `ReadBack.php`, `CompiledStrategyTest.php`, `Timeframe`, `Ledger`, `InstrumentFilter`, `BinanceVenueTest.php`, `ClientOrderId`, `CandleFeedTest.php`, `ChartSeriesTest.php`?**
  _High betweenness centrality (0.067) - this node is a cross-community bridge._
- **Should `EngineTest.php` be split into smaller, more focused modules?**
  _Cohesion score 0.11403508771929824 - nodes in this community are weakly interconnected._
- **Why does `Clock` connect `Clock` to `App\Livewire\SettingsScreen`, `Instrument`, `.encode`, `MarketType`, `ExchangeVerificationTest.php`, `Illuminate\Foundation\Testing\RefreshDatabase`, `BackfillTest.php`, `AppServiceProvider.php`, `InvalidArgumentException`, `StrategyVersion`, `PreparesBacktests.php`, `SimClock`, `SimulatedVenue`, `BinanceRestClientTest.php`, `DatabaseDecisionLog`, `RunBacktestCommandTest.php`, `TelegramTest.php`, `BinanceArchive`, `RunEngine.php`, `CandleRepositoryTest.php`, `Carbon\CarbonImmutable`, `SimulationQueue`, `RunSimulation.php`, `StrategyLibrary`, `BinanceVenueTest.php`, `ClientOrderId`, `CandleFeedTest.php`?**
  _High betweenness centrality (0.041) - this node is a cross-community bridge._
- **Should `App\Livewire\SettingsScreen` be split into smaller, more focused modules?**
  _Cohesion score 0.07493061979648474 - nodes in this community are weakly interconnected._