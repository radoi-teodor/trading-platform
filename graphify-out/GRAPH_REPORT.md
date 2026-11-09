# Graph Report - trading-platform  (2026-11-09)

## Corpus Check
- 620 files · ~261,006 words
- Verdict: corpus is large enough that graph structure adds value.
- Unclassified: 103 file(s) not represented in the graph (top: .zip 38, .CHECKSUM 38, (none) 19)

## Summary
- 4978 nodes · 17154 edges · 209 communities (152 shown, 57 thin omitted)
- Extraction: 91% EXTRACTED · 9% INFERRED · 0% AMBIGUOUS · INFERRED: 1564 edges (avg confidence: 0.85)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `3b0c49a1`
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
- DailyStatsTest.php
- TerminalQrCodeTest.php
- Price
- .of
- SimulatedPerpVenueTest.php
- RuntimeException
- oi-momentum-long.strategy.yaml
- App\Market\Data\Candle
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
- RiskPolicy
- BinanceRestClientTest.php
- ema-cross-scalp-short.strategy.yaml
- App\Livewire\SettingsScreen
- ReadBack
- SettingsScreen
- postgres
- InvalidArgumentException
- SizedStrategyTest.php
- donchian-breakout-short.strategy.yaml
- IndicatorsTest.php
- support.mjs
- RunBacktestCommandTest.php
- IndicatorReferenceTest.php
- ScalpingCollector
- CandleFeedTest.php
- AppServiceProvider.php
- donchian-breakout.strategy.yaml
- .run
- Strategy
- App\Strategy\Dsl\Document\DocumentChecker
- PreparesBacktests
- ArchiveCatalog
- .now
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
- .evaluate
- RunSimulation.php
- DocumentCheckerTest.php
- require-dev
- scripts-descriptions
- Engine.php
- .fromStrings
- config
- uses
- test
- with
- Carbon\CarbonImmutable
- jobs
- .parse
- psr-4
- require
- Illuminate\Http\Request
- StrategyGates.php
- uses
- Tokens
- devDependencies
- Laravel\Mcp\Response
- ParamKind
- ScalpingArchiveTest.php
- RulesHistoryTest.php
- buy-and-hold.strategy.yaml
- ChartSeriesTest.php
- TokensTest.php
- ArchiveImportTest.php
- UserFactory.php
- Illuminate\Database\Migrations\Migration
- HealthTest.php
- .register
- funding-carry.strategy.yaml
- McpServerTest.php
- env.mjs
- chart.js
- TelegramSettings
- Percent
- settings-screen.blade.php
- .parse
- steps
- 2026_11_09_000014_create_health_tables.php
- DecimalMath
- App\Support\Clock\Clock
- Statistics
- CandleRepositoryTest.php
- Illuminate\Database\Migrations\Migration
- ScalpingBackfillTest.php
- TelegramClient
- scripts
- 0001_01_01_000002_create_jobs_table.php
- fake-telegram.mjs
- .fromStrings
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
- App\Simulation\StrategyLibrary
- .__construct
- User
- 2026_11_09_000015_create_scalping_tables.php
- App\Simulation\Backtest
- 2026_11_09_000012_create_account_results_tables.php
- 2026_11_09_000022_create_daily_stats_table.php
- SettingsScreenTest.php
- {closure#3}
- App\Strategy\Dsl\Document\StrategySpec
- 2026_11_09_000013_add_daily_report_to_notification_settings.php
- MarketType
- SimulatedVenue
- .handle
- .isPositive
- 2026_11_09_000018_add_effective_at_to_deployments.php
- 2026_11_09_000016_create_market_universe_tables.php
- CandleStreamTest.php
- 2026_11_09_000017_create_live_tables.php
- start-local.ps1
- WarmingVenue
- .plus
- .decide
- .of
- LiveAccountTest.php
- Fill
- Illuminate\Database\Eloquent\Model
- 2026_11_09_000020_add_lifetime_to_instruments.php
- VenuePosition
- .compute
- .number

## God Nodes (most connected - your core abstractions)
1. `Decimal` - 502 edges
2. `Side` - 205 edges
3. `Symbol` - 191 edges
4. `App` - 155 edges
5. `Candle` - 134 edges
6. `Timeframe` - 119 edges
7. `Price` - 100 edges
8. `Position` - 96 edges
9. `SimClock` - 83 edges
10. `Clock` - 72 edges

## Surprising Connections (you probably didn't know these)
- `{closure#4}()` --calls--> `App`  [INFERRED]
  tests/Feature/Simulation/SimulationJobsTest.php → app/Notifications/SettingSource.php
- `{closure#2}()` --calls--> `App`  [INFERRED]
  tests/Feature/Web/MarketDataSettingsTest.php → app/Notifications/SettingSource.php
- `{closure#13}()` --calls--> `App`  [INFERRED]
  tests/Feature/Web/SettingsScreenTest.php → app/Notifications/SettingSource.php
- `{closure#8}()` --calls--> `App`  [INFERRED]
  tests/Feature/Web/SettingsScreenTest.php → app/Notifications/SettingSource.php
- `up()` --calls--> `App`  [INFERRED]
  database/migrations/2026_11_09_000016_create_market_universe_tables.php → app/Notifications/SettingSource.php

## Import Cycles
- None detected.

## Communities (209 total, 57 thin omitted)

### Community 0 - "CompiledStrategyTest.php"
Cohesion: 0.18
Nodes (16): PositionView, aggregatedBars(), {closure#11}(), {closure#13}(), {closure#16}(), {closure#17}(), {closure#2}(), {closure#3}() (+8 more)

### Community 1 - "engineHarness"
Cohesion: 0.08
Nodes (48): {closure#5}(), CommandKind, Flatten, Halt, Pause, Resume, ResumeStrategy, EngineCommand (+40 more)

### Community 2 - "CanonicalJson"
Cohesion: 0.30
Nodes (6): CanonicalJson, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}()

### Community 3 - "DslError"
Cohesion: 0.11
Nodes (12): {closure#1}(), DslError, YamlList, YamlReader, YamlScalar, YamlValue, {closure#1}(), {closure#1}() (+4 more)

### Community 5 - ".parse"
Cohesion: 0.10
Nodes (36): {closure#12}(), FundingCarry, {closure#11}(), {closure#14}(), {closure#5}(), carryDecisions(), {closure#10}(), {closure#11}() (+28 more)

### Community 7 - ".now"
Cohesion: 0.13
Nodes (8): {closure#1}(), {closure#2}(), Deployment, {closure#1}(), {closure#1}(), ReviewLadder, Ladder, LadderStatus

### Community 8 - "CompiledStrategy.php"
Cohesion: 0.13
Nodes (9): {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#42}(), {closure#43}(), {closure#45}(), Compiler (+1 more)

### Community 9 - "Side"
Cohesion: 0.15
Nodes (50): FeeSchedule, CostModel, Side, Buy, Sell, btc(), {closure#10}(), {closure#11}() (+42 more)

### Community 10 - "BinanceVenueTest.php"
Cohesion: 0.09
Nodes (18): {closure#2}(), BinanceVenue, {closure#4}(), BinanceApiError, BinanceUnreachable, SignedBinanceClient, binanceVenue(), {closure#1}() (+10 more)

### Community 11 - ".of"
Cohesion: 0.10
Nodes (19): Asset, AssetMismatch, Money, {closure#2}(), {closure#1}(), usdt(), {closure#11}(), {closure#1}() (+11 more)

### Community 12 - "Candle"
Cohesion: 0.16
Nodes (29): {closure#1}(), {closure#3}(), Candle, {closure#1}(), {closure#2}(), {closure#12}(), {closure#13}(), candleWith() (+21 more)

### Community 13 - "ExchangeVerificationTest.php"
Cohesion: 0.11
Nodes (31): {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#2}(), {closure#3}(), {closure#4}() (+23 more)

### Community 14 - "Quantity"
Cohesion: 0.24
Nodes (3): Quantity, {closure#10}(), {closure#9}()

### Community 15 - "Illuminate\Contracts\Config\Repository"
Cohesion: 0.14
Nodes (6): InstrumentDirectory, StoredInstruments, CompileStrategy, GridPart, PolicyLimits, StrategyRepository

### Community 16 - "Node"
Cohesion: 0.14
Nodes (20): Binary, Parser, Token, TokenKind, Colon, Comma, Dot, Duration (+12 more)

### Community 17 - "Clock"
Cohesion: 0.06
Nodes (20): FlattenEngine, HaltEngine, IssuesCommands, PauseStrategy, ResumeEngine, StoreKeys, CheckMarketData, SelectsInstruments (+12 more)

### Community 18 - "Instrument"
Cohesion: 0.05
Nodes (16): BackfillStep, CandleRepository, {closure#1}(), CandleSource, Archive, Rest, Stream, UpsertResult (+8 more)

### Community 19 - "ScreensTest.php"
Cohesion: 0.17
Nodes (22): {closure#1}(), chartLiveQuery(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#15}() (+14 more)

### Community 20 - "LedgerTest.php"
Cohesion: 0.15
Nodes (29): Ledger, LedgerViolation, balanceOf(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}() (+21 more)

### Community 21 - "Illuminate\Database\Schema\Blueprint"
Cohesion: 0.14
Nodes (13): {closure#1}(), {closure#2}(), {closure#3}(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}() (+5 more)

### Community 22 - "generate.py"
Cohesion: 0.07
Nodes (30): atr(), bars(), bb(), change(), dmi(), donchian(), ema(), highest() (+22 more)

### Community 23 - "App\Strategy\Dsl\Compiler\CompiledStrategy"
Cohesion: 0.07
Nodes (13): {closure#2}(), {closure#6}(), {closure#7}(), {closure#8}(), {closure#9}(), {closure#7}(), EntrySpec, Expression (+5 more)

### Community 24 - "Symbol"
Cohesion: 0.05
Nodes (22): {closure#1}(), BuyAndHold, DonchianBreakout, BuildableStrategy, Close, EntryOrder, Intent, MoveStop (+14 more)

### Community 25 - "DailyStatsTest.php"
Cohesion: 0.10
Nodes (11): {closure#1}(), {closure#2}(), {closure#3}(), fakeTestnet(), testnetAccount(), {closure#1}(), {closure#3}(), {closure#4}() (+3 more)

### Community 26 - "TerminalQrCodeTest.php"
Cohesion: 0.21
Nodes (7): TerminalQrCode, {closure#3}(), {closure#4}(), {closure#5}(), expectedModules(), readColoured(), readPlain()

### Community 27 - "Price"
Cohesion: 0.06
Nodes (24): Balance, ClientOrderId, Income, IncomeKind, Funding, Liquidation, OcoRequest, OrderRequest (+16 more)

### Community 28 - ".of"
Cohesion: 0.06
Nodes (81): InstrumentRules, {closure#1}(), AccountView, RejectionReason, BelowMinimum, Breaker, Cooldown, DuplicateIntent (+73 more)

### Community 29 - "SimulatedPerpVenueTest.php"
Cohesion: 0.20
Nodes (27): {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#15}(), {closure#18}() (+19 more)

### Community 30 - "RuntimeException"
Cohesion: 0.12
Nodes (3): PhrityTransport, StreamFailed, FakeTransport

### Community 31 - "oi-momentum-long.strategy.yaml"
Cohesion: 0.07
Nodes (27): entries, long, exits, stop_loss, take_profit, when, fails_when, family (+19 more)

### Community 32 - "App\Market\Data\Candle"
Cohesion: 0.09
Nodes (13): ReplayDivergence, ArchiveMissing, ChecksumMismatch, {closure#11}(), {closure#3}(), {closure#4}(), {closure#3}(), PerpetualView (+5 more)

### Community 33 - "EnginePerpTest.php"
Cohesion: 0.30
Nodes (13): {closure#10}(), {closure#11}(), {closure#14}(), {closure#5}(), {closure#6}(), {closure#7}(), {closure#8}(), {closure#9}() (+5 more)

### Community 34 - "BinanceArchive"
Cohesion: 0.08
Nodes (7): BinanceArchive, {closure#1}(), {closure#10}(), ExchangeNumber, TradeFlowBuilder, {closure#1}(), {closure#2}()

### Community 35 - "LadderTest.php"
Cohesion: 0.13
Nodes (24): {closure#4}(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#5}(), {closure#6}(), {closure#7}(), reportAccount() (+16 more)

### Community 36 - "SimClock"
Cohesion: 0.11
Nodes (23): FundingRate, SimClock, WallClock, {closure#1}(), {closure#2}(), {closure#3}(), {closure#1}(), aggTrade() (+15 more)

### Community 37 - "App\Support\Clock\Clock"
Cohesion: 0.05
Nodes (21): {closure#1}(), SyncInstruments, InstrumentDefinition, InstrumentStatus, Delisted, Halted, Trading, FormingCandles (+13 more)

### Community 38 - "OrderManagerTest.php"
Cohesion: 0.10
Nodes (34): OrderAck, OrderRole, Entry, Exit, Flatten, Stop, Target, ProtectionPlan (+26 more)

### Community 39 - "BacktestTest.php"
Cohesion: 0.08
Nodes (24): CandleFeed, Backtest, RunBacktest, Participant, RunDefinition, ReportBuilder, {closure#1}(), {closure#2}() (+16 more)

### Community 40 - "App\Strategy\Dsl\Document\DocumentChecker"
Cohesion: 0.08
Nodes (24): Argument, BoolLiteral, Call, DurationLiteral, Identifier, Lookback, Node, NumberLiteral (+16 more)

### Community 41 - "ema-cross-scalp-long.strategy.yaml"
Cohesion: 0.06
Nodes (33): entries, long, exits, stop_loss, take_profit, time_stop, when, fails_when (+25 more)

### Community 42 - "Decimal"
Cohesion: 0.05
Nodes (42): {closure#6}(), RoundTrip, {closure#1}(), {closure#2}(), {closure#3}(), {closure#10}(), {closure#5}(), {closure#4}() (+34 more)

### Community 43 - ".zero"
Cohesion: 0.11
Nodes (13): Liquidity, Maker, Taker, OrderStatus, Cancelled, Expired, Filled, New (+5 more)

### Community 44 - "App"
Cohesion: 0.08
Nodes (30): SendTelegram, App, {closure#1}(), {closure#2}(), {closure#3}(), {closure#1}(), {closure#2}(), {closure#4}() (+22 more)

### Community 45 - "Timeframe"
Cohesion: 0.06
Nodes (31): CandleAggregator, CandleSequenceBroken, Parameters, CarryTemplate, {closure#2}(), {closure#8}(), Timeframe, FifteenMinutes (+23 more)

### Community 46 - "PaperEngineTest.php"
Cohesion: 0.12
Nodes (20): AccountEquity, AccountTrade, JournalEntry, {closure#1}(), {closure#2}(), {closure#3}(), {closure#1}(), {closure#10}() (+12 more)

### Community 47 - "package.json"
Cohesion: 0.15
Nodes (17): dependencies, codemirror@^6.0.2, @codemirror/lang-yaml@^6.1.3, @codemirror/language@^6.13.1, @lezer/highlight@^1.2.5, lightweight-charts@^5.2.1, optionalDependencies, @laravel/multiplex@^0.4.1 (+9 more)

### Community 48 - "RiskPolicy"
Cohesion: 0.13
Nodes (12): {closure#1}(), RiskProfile, Account, Benchmark, RiskPolicy, {closure#1}(), {closure#2}(), {closure#3}() (+4 more)

### Community 49 - "BinanceRestClientTest.php"
Cohesion: 0.10
Nodes (29): {closure#1}(), {closure#3}(), {closure#16}(), binanceClient(), {closure#1}(), {closure#11}(), {closure#13}(), {closure#15}() (+21 more)

### Community 50 - "ema-cross-scalp-short.strategy.yaml"
Cohesion: 0.06
Nodes (33): entries, short, exits, stop_loss, take_profit, time_stop, when, fails_when (+25 more)

### Community 51 - "App\Livewire\SettingsScreen"
Cohesion: 0.07
Nodes (14): Account, ReauthController, AccountScreen, ChartScreen, {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}() (+6 more)

### Community 54 - "postgres"
Cohesion: 0.10
Nodes (19): POSTGRES_DB, POSTGRES_PASSWORD, POSTGRES_USER, interval, retries, test, timeout, environment (+11 more)

### Community 55 - "InvalidArgumentException"
Cohesion: 0.08
Nodes (14): {closure#1}(), {closure#2}(), {closure#2}(), {closure#1}(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#5}() (+6 more)

### Community 56 - "SizedStrategyTest.php"
Cohesion: 0.11
Nodes (11): SizedStrategy, LimitedStrategy, {closure#1}(), {closure#2}(), __construct(), enterOnce(), identity(), limits() (+3 more)

### Community 57 - "donchian-breakout-short.strategy.yaml"
Cohesion: 0.07
Nodes (26): entries, short, default, range, step, default, range, step (+18 more)

### Community 58 - "IndicatorsTest.php"
Cohesion: 0.09
Nodes (22): Atr, Donchian, Ema, Sma, {closure#5}(), fiveMinuteBars(), bar(), {closure#10}() (+14 more)

### Community 59 - "support.mjs"
Cohesion: 0.26
Nodes (9): @playwright/test, confirmed(), checkLayout(), EMAIL, freshCode(), PASSWORD, reauthenticate(), totp() (+1 more)

### Community 60 - "RunBacktestCommandTest.php"
Cohesion: 0.07
Nodes (34): BacktestRun, {closure#1}(), {closure#4}(), {closure#6}(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}() (+26 more)

### Community 61 - "IndicatorReferenceTest.php"
Cohesion: 0.03
Nodes (35): {closure#54}(), BarIndicator, Bollinger, Change, DonchianChannel, Highest, Hma, Keltner (+27 more)

### Community 63 - "CandleFeedTest.php"
Cohesion: 0.10
Nodes (27): {closure#2}(), {closure#4}(), {closure#2}(), DatabaseCandleFeed, {closure#1}(), Rows, {closure#1}(), {closure#2}() (+19 more)

### Community 64 - "AppServiceProvider.php"
Cohesion: 0.07
Nodes (11): {closure#4}(), Notifier, HistoryImports, CheckHealth, HealthAlerts, LocalDiskUsage, TelegramNotifier, {closure#3}() (+3 more)

### Community 65 - "donchian-breakout.strategy.yaml"
Cohesion: 0.07
Nodes (26): entries, long, default, range, step, default, range, step (+18 more)

### Community 66 - ".run"
Cohesion: 0.09
Nodes (30): PerformanceMetrics, Report, MovingAverageTrend, btcMinutes(), {closure#1}(), {closure#2}(), referencePrice(), {closure#13}() (+22 more)

### Community 67 - "Strategy"
Cohesion: 0.21
Nodes (14): Strategy, baselineDocument(), baselinePairs(), {closure#10}(), {closure#12}(), {closure#15}(), {closure#17}(), {closure#18}() (+6 more)

### Community 68 - "App\Strategy\Dsl\Document\DocumentChecker"
Cohesion: 0.06
Nodes (45): {closure#1}(), {closure#1}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#9}(), ParameterSpec, DslWriter (+37 more)

### Community 69 - "PreparesBacktests"
Cohesion: 0.13
Nodes (6): {closure#2}(), {closure#3}(), {closure#1}(), {closure#2}(), {closure#1}(), PreparesBacktests

### Community 70 - "ArchiveCatalog"
Cohesion: 0.22
Nodes (5): ArchiveCatalog, FillDailyStats, DailyStats, DailyHistory, {closure#6}()

### Community 71 - ".now"
Cohesion: 0.05
Nodes (15): AccountRuntime, LiveGate, OpenAccount, ApproveLive, ConfirmsOperator, CreateAccount, RunEngine, LiveApproval (+7 more)

### Community 72 - "ExitReason"
Cohesion: 0.19
Nodes (24): ExitReason, Flatten, Liquidation, Signal, Stop, Target, {closure#1}(), {closure#10}() (+16 more)

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
Cohesion: 0.19
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
Cohesion: 0.09
Nodes (22): Cscv, DeflatedSharpe, DirectionalMovement, Wilder, {closure#3}(), closeTo(), {closure#1}(), {closure#10}() (+14 more)

### Community 82 - "MonteCarlo"
Cohesion: 0.15
Nodes (11): {closure#4}(), {closure#5}(), MonteCarlo, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}() (+3 more)

### Community 83 - "App\Support\Number\Decimal"
Cohesion: 0.08
Nodes (48): {closure#5}(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#15}() (+40 more)

### Community 84 - "pint/composer.json"
Cohesion: 0.20
Nodes (9): config, sort-packages, description, license, name, require, laravel/pint, php (+1 more)

### Community 85 - ".evaluate"
Cohesion: 0.29
Nodes (7): WalkForward, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), wfSet()

### Community 86 - "RunSimulation.php"
Cohesion: 0.13
Nodes (4): ImportMarketData, MarketJob, RunSimulation, SimulationJob

### Community 87 - "DocumentCheckerTest.php"
Cohesion: 0.15
Nodes (17): {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), versions(), KnownInstruments, checkDocument() (+9 more)

### Community 88 - "require-dev"
Cohesion: 0.22
Nodes (9): require-dev, fakerphp/faker, larastan/larastan, laravel/pail, laravel/pao, mockery/mockery, nunomaduro/collision, pestphp/pest (+1 more)

### Community 89 - "scripts-descriptions"
Cohesion: 0.25
Nodes (8): scripts-descriptions, analyse, format, mutate, test, test:network, test:testnet, tools:install

### Community 90 - "Engine.php"
Cohesion: 0.09
Nodes (13): {closure#1}(), {closure#11}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#9}(), MinuteCandles, BreakerEvent (+5 more)

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

### Community 96 - "Carbon\CarbonImmutable"
Cohesion: 0.06
Nodes (16): ArchivePeriod, Backfill, {closure#1}(), BackfillReport, {closure#1}(), BackfillMarketData, FormingCandle, OpenInterest (+8 more)

### Community 97 - "jobs"
Cohesion: 0.25
Nodes (8): name, runs-on, name, runs-on, steps, jobs, analyse, audit

### Community 98 - ".parse"
Cohesion: 0.18
Nodes (8): AstPrinter, {closure#8}(), Lexer, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), sexpr()

### Community 99 - "psr-4"
Cohesion: 0.40
Nodes (5): autoload, psr-4, App\\, Database\\Factories\\, Database\\Seeders\\

### Community 100 - "require"
Cohesion: 0.22
Nodes (9): require, bacon/bacon-qr-code, laravel/fortify, laravel/framework, laravel/mcp, laravel/tinker, livewire/livewire, php (+1 more)

### Community 102 - "Illuminate\Http\Request"
Cohesion: 0.09
Nodes (9): ChartLiveController, AppServiceProvider, {closure#8}(), {closure#3}(), {closure#4}(), FortifyServiceProvider, {closure#1}(), {closure#2}() (+1 more)

### Community 103 - "StrategyGates.php"
Cohesion: 0.07
Nodes (18): {closure#11}(), {closure#12}(), {closure#2}(), {closure#3}(), {closure#6}(), {closure#8}(), {closure#2}(), {closure#1}() (+10 more)

### Community 104 - "uses"
Cohesion: 0.21
Nodes (12): steps, name, runs-on, steps, frontend, lint, name, runs-on (+4 more)

### Community 105 - "Tokens"
Cohesion: 0.23
Nodes (3): CreateMcpToken, RevokeMcpToken, Tokens

### Community 106 - "devDependencies"
Cohesion: 0.18
Nodes (12): devDependencies, concurrently@^10.0.3, laravel-vite-plugin@^3.1, @playwright/test@1.63.0, tailwindcss@^4.0.0, @tailwindcss/vite@^4.0.0, vite@^8.0.0, concurrently (+4 more)

### Community 108 - "Laravel\Mcp\Response"
Cohesion: 0.06
Nodes (19): ChartTool, CompileStrategy, DslReference, GetCandles, GetFunding, GetIndicators, GetJob, GetStrategy (+11 more)

### Community 117 - "ParamKind"
Cohesion: 0.15
Nodes (11): {closure#2}(), {closure#2}(), ParamKind, Anchor, Count, Duration, Number, Percent (+3 more)

### Community 118 - "ScalpingArchiveTest.php"
Cohesion: 0.22
Nodes (10): TradeFlow, {closure#10}(), {closure#11}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}() (+2 more)

### Community 119 - "RulesHistoryTest.php"
Cohesion: 0.28
Nodes (8): RulesHistory, {closure#4}(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), rules(), {closure#10}()

### Community 120 - "buy-and-hold.strategy.yaml"
Cohesion: 0.12
Nodes (16): entries, long, exits, stop_loss, fails_when, family, hypothesis, when (+8 more)

### Community 121 - "ChartSeriesTest.php"
Cohesion: 0.24
Nodes (8): chart(), {closure#1}(), {closure#11}(), {closure#2}(), {closure#3}(), {closure#6}(), {closure#7}(), {closure#9}()

### Community 122 - "TokensTest.php"
Cohesion: 0.31
Nodes (6): {closure#3}(), {closure#4}(), contrastRatio(), contrastRules(), relativeLuminance(), themeTokens()

### Community 123 - "ArchiveImportTest.php"
Cohesion: 0.31
Nodes (10): archiveBytes(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}() (+2 more)

### Community 125 - "Illuminate\Database\Migrations\Migration"
Cohesion: 0.15
Nodes (3): {closure#1}(), {closure#1}(), {closure#1}()

### Community 126 - "HealthTest.php"
Cohesion: 0.29
Nodes (9): {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#8}(), healthAt(), healthyMachine() (+1 more)

### Community 127 - ".register"
Cohesion: 0.12
Nodes (20): {closure#10}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#15}(), {closure#18}(), {closure#20}(), {closure#21}() (+12 more)

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
Cohesion: 0.21
Nodes (5): NotificationSettings, SettingSource, Env, None, TelegramSettings

### Community 133 - "Percent"
Cohesion: 0.24
Nodes (3): Percent, {closure#14}(), {closure#15}()

### Community 135 - "settings-screen.blade.php"
Cohesion: 0.17
Nodes (11): approveLive, confirm, forgetKey, forgetTelegram, monitor, notify, removeInstrument, revokeToken (+3 more)

### Community 137 - ".parse"
Cohesion: 0.33
Nodes (6): KlineMessage, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), streamLines()

### Community 138 - "steps"
Cohesion: 0.33
Nodes (6): name, runs-on, services, steps, e2e, if

### Community 139 - "2026_11_09_000014_create_health_tables.php"
Cohesion: 0.33
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

### Community 140 - "DecimalMath"
Cohesion: 0.14
Nodes (14): DecimalMath, {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#2}() (+6 more)

### Community 141 - "App\Support\Clock\Clock"
Cohesion: 0.11
Nodes (20): {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}(), {closure#8}(), {closure#9}() (+12 more)

### Community 142 - "Statistics"
Cohesion: 0.11
Nodes (21): Statistics, Gates, {closure#1}(), {closure#2}(), {closure#3}(), brokenGate(), {closure#1}(), {closure#3}() (+13 more)

### Community 143 - "CandleRepositoryTest.php"
Cohesion: 0.31
Nodes (11): candleStore(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}() (+3 more)

### Community 144 - "Illuminate\Database\Migrations\Migration"
Cohesion: 0.15
Nodes (3): {closure#1}(), {closure#1}(), {closure#1}()

### Community 145 - "ScalpingBackfillTest.php"
Cohesion: 0.21
Nodes (10): {closure#1}(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), scalpingBackfill() (+2 more)

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
Cohesion: 0.13
Nodes (8): {closure#1}(), ListStrategies, {closure#3}(), StrategiesScreen, StrategyLibrary, CheckResult, SaveResult, StrategyVersion

### Community 177 - "2026_11_09_000015_create_scalping_tables.php"
Cohesion: 0.33
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

### Community 178 - "App\Simulation\Backtest"
Cohesion: 0.16
Nodes (8): InMemoryDecisionLog, Marks, {closure#1}(), {closure#2}(), BacktestResult, ParticipantResult, {closure#25}(), {closure#9}()

### Community 181 - "SettingsScreenTest.php"
Cohesion: 0.10
Nodes (19): {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}(), {closure#10}() (+11 more)

### Community 183 - "App\Strategy\Dsl\Document\StrategySpec"
Cohesion: 0.35
Nodes (7): {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#7}(), readBackDocument(), readBackSpec()

### Community 189 - "MarketType"
Cohesion: 0.08
Nodes (20): BinanceEndpoints, BinanceRequestFailed, BinanceRestClient, EpochTime, Json, LocationRestricted, RateLimited, RequestFailed (+12 more)

### Community 191 - "SimulatedVenue"
Cohesion: 0.08
Nodes (6): Venue, SimulatedVenue, ForwardingVenue, {closure#12}(), {closure#32}(), {closure#34}()

### Community 192 - ".handle"
Cohesion: 0.14
Nodes (4): GridBacktest, ReadsGridOptions, StrategyGates, StrategySpec

### Community 195 - "2026_11_09_000016_create_market_universe_tables.php"
Cohesion: 0.40
Nodes (3): {closure#1}(), {closure#2}(), up()

### Community 196 - "CandleStreamTest.php"
Cohesion: 0.24
Nodes (13): {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#7}(), {closure#8}(), {closure#9}() (+5 more)

### Community 197 - "2026_11_09_000017_create_live_tables.php"
Cohesion: 0.33
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

### Community 198 - "start-local.ps1"
Cohesion: 0.27
Nodes (4): Stop-Postgres(), Stop-ProcessTree(), Test-PostgresRunning(), Write-Step()

### Community 200 - ".plus"
Cohesion: 0.08
Nodes (9): PerpPosition, {closure#4}(), {closure#2}(), TradeMemory, VolumeProfile, Perpetual, {closure#16}(), {closure#10}() (+1 more)

### Community 201 - ".decide"
Cohesion: 0.09
Nodes (16): DecisionLog, DecisionRecord, EngineMode, Backtest, Live, Paper, Testnet, EngineRecord (+8 more)

### Community 203 - ".of"
Cohesion: 0.10
Nodes (25): balances(), {closure#41}(), {closure#17}(), {closure#5}(), {closure#7}(), {closure#24}(), {closure#1}(), {closure#1}() (+17 more)

### Community 204 - "LiveAccountTest.php"
Cohesion: 0.22
Nodes (12): approveLive(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}() (+4 more)

### Community 208 - "Illuminate\Database\Eloquent\Model"
Cohesion: 0.06
Nodes (11): AuthenticateMcpToken, McpCall, McpToken, AccountSnapshot, PlainDecimal, ExchangeKey, UniverseEntry, StrategyGateReport (+3 more)

### Community 214 - "VenuePosition"
Cohesion: 0.31
Nodes (4): VenuePosition, {closure#1}(), SimulatedPosition, {closure#13}()

### Community 218 - ".number"
Cohesion: 0.33
Nodes (4): {closure#4}(), {closure#40}(), {closure#41}(), {closure#57}()

## Knowledge Gaps
- **148 isolated node(s):** `Limit`, `Market`, `StopMarket`, `TakeProfitMarket`, `Anchor` (+143 more)
  These have ≤1 connection - possible missing edges. (Counts symbols only; 956 node(s) total have ≤1 connection when file, concept and rationale nodes are included.)
- **57 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `Decimal` connect `Decimal` to `CompiledStrategyTest.php`, `engineHarness`, `CanonicalJson`, `Engine`, `.parse`, `DocumentChecker`, `Percent`, `Side`, `BinanceVenueTest.php`, `.of`, `Candle`, `DecimalMath`, `Statistics`, `Illuminate\Contracts\Config\Repository`, `Quantity`, `LedgerTest.php`, `App\Strategy\Dsl\Compiler\CompiledStrategy`, `Symbol`, `Price`, `.of`, `SimulatedPerpVenueTest.php`, `App\Market\Data\Candle`, `EnginePerpTest.php`, `SimClock`, `OrderManagerTest.php`, `BacktestTest.php`, `App\Strategy\Dsl\Document\DocumentChecker`, `.zero`, `Timeframe`, `.__construct`, `RiskPolicy`, `App\Simulation\Backtest`, `InvalidArgumentException`, `SizedStrategyTest.php`, `App\Strategy\Dsl\Document\StrategySpec`, `IndicatorsTest.php`, `RunBacktestCommandTest.php`, `IndicatorReferenceTest.php`, `SimulatedVenue`, `CandleFeedTest.php`, `.handle`, `.run`, `.isPositive`, `App\Strategy\Dsl\Document\DocumentChecker`, `Strategy`, `WarmingVenue`, `.plus`, `.decide`, `ExitReason`, `.of`, `.measure`, `Fill`, `DecimalMath`, `MonteCarlo`, `App\Support\Number\Decimal`, `.evaluate`, `VenuePosition`, `DocumentCheckerTest.php`, `Engine.php`, `.fromStrings`, `StrategyGates.php`, `RulesHistoryTest.php`, `ChartSeriesTest.php`?**
  _High betweenness centrality (0.130) - this node is a cross-community bridge._
- **What connects `Limit`, `Market`, `StopMarket` to the rest of the system?**
  _148 weakly-connected nodes found - possible missing edges._
- **Should `engineHarness` be split into smaller, more focused modules?**
  _Cohesion score 0.07531531531531531 - nodes in this community are weakly interconnected._
- **Why does `Symbol` connect `Symbol` to `CompiledStrategyTest.php`, `engineHarness`, `CanonicalJson`, `Engine`, `.parse`, `DocumentChecker`, `Side`, `BinanceVenueTest.php`, `.of`, `ExchangeVerificationTest.php`, `Illuminate\Contracts\Config\Repository`, `CandleRepositoryTest.php`, `Clock`, `LedgerTest.php`, `Price`, `.of`, `SimulatedPerpVenueTest.php`, `EnginePerpTest.php`, `SimClock`, `App\Support\Clock\Clock`, `OrderManagerTest.php`, `BacktestTest.php`, `Decimal`, `Timeframe`, `App\Simulation\Backtest`, `InvalidArgumentException`, `App\Strategy\Dsl\Document\StrategySpec`, `RunBacktestCommandTest.php`, `CandleFeedTest.php`, `SimulatedVenue`, `.handle`, `.run`, `Strategy`, `App\Strategy\Dsl\Document\DocumentChecker`, `PreparesBacktests`, `WarmingVenue`, `.now`, `.decide`, `.plus`, `ExitReason`, `.measure`, `Fill`, `DecimalMath`, `VenuePosition`, `DocumentCheckerTest.php`, `Engine.php`, `StrategyGates.php`, `Laravel\Mcp\Response`, `RulesHistoryTest.php`, `ChartSeriesTest.php`?**
  _High betweenness centrality (0.033) - this node is a cross-community bridge._
- **Should `DslError` be split into smaller, more focused modules?**
  _Cohesion score 0.112375533428165 - nodes in this community are weakly interconnected._
- **Why does `Candle` connect `Candle` to `CompiledStrategyTest.php`, `engineHarness`, `.parse`, `.parse`, `Side`, `ExchangeVerificationTest.php`, `Statistics`, `CandleRepositoryTest.php`, `Instrument`, `.fromStrings`, `Price`, `App\Market\Data\Candle`, `EnginePerpTest.php`, `App\Support\Clock\Clock`, `OrderManagerTest.php`, `BacktestTest.php`, `Decimal`, `Timeframe`, `.__construct`, `IndicatorsTest.php`, `IndicatorReferenceTest.php`, `MarketType`, `CandleFeedTest.php`, `.isPositive`, `.run`, `.plus`, `DecimalMath`, `Engine.php`, `.fromStrings`, `Carbon\CarbonImmutable`, `StrategyGates.php`, `ChartSeriesTest.php`, `HealthTest.php`?**
  _High betweenness centrality (0.021) - this node is a cross-community bridge._
- **Should `Engine` be split into smaller, more focused modules?**
  _Cohesion score 0.10431372549019607 - nodes in this community are weakly interconnected._