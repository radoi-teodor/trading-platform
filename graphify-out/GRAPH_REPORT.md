# Graph Report - trading-platform  (2026-11-09)

## Corpus Check
- 613 files · ~258,796 words
- Verdict: corpus is large enough that graph structure adds value.
- Unclassified: 103 file(s) not represented in the graph (top: .zip 38, .CHECKSUM 38, (none) 19)

## Summary
- 4935 nodes · 16998 edges · 216 communities (160 shown, 56 thin omitted)
- Extraction: 91% EXTRACTED · 9% INFERRED · 0% AMBIGUOUS · INFERRED: 1547 edges (avg confidence: 0.85)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `19d79202`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- Strategy
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
- PolicyLimits
- Node
- Illuminate\Console\Command
- Instrument
- ScreensTest.php
- LedgerTest.php
- Illuminate\Database\Schema\Blueprint
- generate.py
- App\Strategy\Dsl\Compiler\CompiledStrategy
- Symbol
- RejectionReason
- User
- Price
- .of
- SimulatedPerpVenueTest.php
- RuntimeException
- oi-momentum-long.strategy.yaml
- App\Market\Data\FundingRate
- SimClock
- BinanceArchive
- LadderTest.php
- Illuminate\Http\Client\Request
- InstrumentSyncTest.php
- OrderManagerTest.php
- BacktestTest.php
- App\Strategy\Dsl\Document\DocumentChecker
- ema-cross-scalp-long.strategy.yaml
- Decimal
- .zero
- App
- Timeframe
- Illuminate\Database\Eloquent\Model
- package.json
- Window
- BinanceRestClientTest.php
- ema-cross-scalp-short.strategy.yaml
- App\Livewire\SettingsScreen
- ReadBack
- Illuminate\Contracts\JsonSchema\JsonSchema
- postgres
- InvalidArgumentException
- SizedStrategyTest.php
- donchian-breakout-short.strategy.yaml
- IndicatorsTest.php
- support.mjs
- RunBacktestCommandTest.php
- IndicatorReferenceTest.php
- App\Support\Clock\Clock
- .timestamp
- RunEngine.php
- donchian-breakout.strategy.yaml
- .run
- StrategyIdentity
- App\Strategy\Dsl\Document\DocumentChecker
- PreparesBacktests
- CandleFeedTest.php
- SettingsScreen
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
- HealthChecks
- jobs
- .parse
- psr-4
- require
- AppServiceProvider.php
- StrategyGates.php
- uses
- DailyReport
- devDependencies
- Laravel\Mcp\Response
- ParamKind
- App\Agents\Mcp\TradingServer
- RulesHistoryTest.php
- buy-and-hold.strategy.yaml
- ChartSeriesTest.php
- TokensTest.php
- ArchiveImportTest.php
- Laravel\Fortify\Contracts\TwoFactorAuthenticationProvider
- Illuminate\Database\Migrations\Migration
- StrategyVersion
- .register
- funding-carry.strategy.yaml
- McpServerTest.php
- env.mjs
- chart.js
- TelegramSettings
- Income
- settings-screen.blade.php
- 0001_01_01_000000_create_users_table.php
- steps
- 2026_11_09_000014_create_health_tables.php
- DecimalMath
- App\Support\Clock\Clock
- Statistics
- CandleRepositoryTest.php
- Illuminate\Database\Migrations\Migration
- Illuminate\Support\Facades\Artisan
- QualityCheck
- scripts
- 0001_01_01_000002_create_jobs_table.php
- fake-telegram.mjs
- Carbon\CarbonImmutable
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
- MarketDataSettingsTest.php
- Illuminate\Foundation\Testing\RefreshDatabase
- OrderManagerTest.php
- Asset
- 2026_11_09_000012_create_account_results_tables.php
- Venue
- SettingsScreenTest.php
- Ledger
- App\Strategy\Dsl\Document\StrategySpec
- SimulationQueue
- 2026_11_09_000013_add_daily_report_to_notification_settings.php
- MarketType
- BarIndicator
- .zero
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
- ForwardingVenue
- .of
- LiveAccountTest.php
- TradingTool
- Lexer
- Fill
- App\Agents\Mcp\Tools\ListTrials
- AccountRuntime
- WallClock
- 2026_11_09_000020_add_lifetime_to_instruments.php
- VenuePosition
- StrategyContext
- .number

## God Nodes (most connected - your core abstractions)
1. `Decimal` - 503 edges
2. `Side` - 205 edges
3. `Symbol` - 193 edges
4. `App` - 153 edges
5. `Candle` - 136 edges
6. `Timeframe` - 120 edges
7. `Price` - 100 edges
8. `Position` - 96 edges
9. `SimClock` - 83 edges
10. `Clock` - 73 edges

## Surprising Connections (you probably didn't know these)
- `{closure#2}()` --calls--> `App`  [INFERRED]
  tests/Feature/Web/MarketDataSettingsTest.php → app/Notifications/SettingSource.php
- `{closure#2}()` --calls--> `App`  [INFERRED]
  tests/Feature/Agents/TokensTest.php → app/Notifications/SettingSource.php
- `{closure#3}()` --calls--> `App`  [INFERRED]
  tests/Feature/Agents/TokensTest.php → app/Notifications/SettingSource.php
- `{closure#13}()` --calls--> `App`  [INFERRED]
  tests/Feature/Web/SettingsScreenTest.php → app/Notifications/SettingSource.php
- `{closure#8}()` --calls--> `App`  [INFERRED]
  tests/Feature/Web/SettingsScreenTest.php → app/Notifications/SettingSource.php

## Import Cycles
- None detected.

## Communities (216 total, 56 thin omitted)

### Community 0 - "Strategy"
Cohesion: 0.08
Nodes (35): LimitedStrategy, Strategy, baselineDocument(), baselinePairs(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#14}() (+27 more)

### Community 1 - "engineHarness"
Cohesion: 0.11
Nodes (39): EngineCommand, ScriptedStrategy, {closure#1}(), {closure#10}(), {closure#13}(), {closure#14}(), {closure#16}(), {closure#17}() (+31 more)

### Community 2 - "CanonicalJson"
Cohesion: 0.30
Nodes (6): CanonicalJson, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}()

### Community 3 - "DslError"
Cohesion: 0.10
Nodes (16): {closure#1}(), {closure#1}(), {closure#5}(), {closure#8}(), DslError, YamlList, YamlReader, YamlScalar (+8 more)

### Community 4 - "Engine"
Cohesion: 0.14
Nodes (4): EngineRecord, Engine, FormingCandle, ChartHistoryMissing

### Community 5 - ".parse"
Cohesion: 0.11
Nodes (35): {closure#12}(), {closure#17}(), {closure#5}(), carryDecisions(), {closure#10}(), {closure#11}(), {closure#13}(), {closure#14}() (+27 more)

### Community 7 - ".now"
Cohesion: 0.19
Nodes (4): {closure#1}(), {closure#2}(), Ladder, LadderStatus

### Community 8 - "CompiledStrategy.php"
Cohesion: 0.19
Nodes (4): {closure#24}(), {closure#42}(), Compiler, Program

### Community 9 - "Side"
Cohesion: 0.20
Nodes (42): Side, Buy, Sell, btc(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}() (+34 more)

### Community 10 - "BinanceVenueTest.php"
Cohesion: 0.09
Nodes (21): {closure#2}(), OrderType, Limit, Market, StopMarket, TakeProfitMarket, BinanceVenue, {closure#4}() (+13 more)

### Community 11 - ".of"
Cohesion: 0.12
Nodes (18): AssetMismatch, Money, {closure#2}(), {closure#1}(), usdt(), {closure#7}(), {closure#1}(), {closure#11}() (+10 more)

### Community 12 - "Candle"
Cohesion: 0.10
Nodes (35): {closure#1}(), {closure#3}(), Candle, InvalidCandle, {closure#9}(), {closure#1}(), {closure#2}(), {closure#1}() (+27 more)

### Community 13 - "ExchangeVerificationTest.php"
Cohesion: 0.11
Nodes (29): {closure#1}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}() (+21 more)

### Community 14 - "Quantity"
Cohesion: 0.24
Nodes (3): Quantity, {closure#10}(), {closure#9}()

### Community 15 - "PolicyLimits"
Cohesion: 0.07
Nodes (17): AuthenticateMcpToken, InstrumentDirectory, FundingRateRepository, Heartbeats, StoredInstruments, {closure#1}(), CompileStrategy, GridPart (+9 more)

### Community 16 - "Node"
Cohesion: 0.11
Nodes (29): Binary, Node, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}() (+21 more)

### Community 17 - "Illuminate\Console\Command"
Cohesion: 0.06
Nodes (20): CreateMcpToken, RevokeMcpToken, Tokens, FlattenEngine, HaltEngine, IssuesCommands, PauseStrategy, ResumeEngine (+12 more)

### Community 18 - "Instrument"
Cohesion: 0.04
Nodes (25): BackfillReport, BackfillStep, CandleRepository, BackfillMarketData, SelectsInstruments, {closure#1}(), {closure#2}(), StreamCandles (+17 more)

### Community 19 - "ScreensTest.php"
Cohesion: 0.14
Nodes (22): {closure#1}(), chartLiveQuery(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#15}() (+14 more)

### Community 20 - "LedgerTest.php"
Cohesion: 0.22
Nodes (26): balanceOf(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#15}(), {closure#16}() (+18 more)

### Community 21 - "Illuminate\Database\Schema\Blueprint"
Cohesion: 0.14
Nodes (13): {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#1}(), {closure#2}(), {closure#3}() (+5 more)

### Community 22 - "generate.py"
Cohesion: 0.07
Nodes (30): atr(), bars(), bb(), change(), dmi(), donchian(), ema(), highest() (+22 more)

### Community 23 - "App\Strategy\Dsl\Compiler\CompiledStrategy"
Cohesion: 0.08
Nodes (17): {closure#1}(), {closure#2}(), {closure#3}(), {closure#5}(), {closure#6}(), {closure#7}(), {closure#8}(), {closure#9}() (+9 more)

### Community 24 - "Symbol"
Cohesion: 0.04
Nodes (26): {closure#1}(), BuyAndHold, DonchianBreakout, FundingCarry, MovingAverageTrend, Parameters, BuildableStrategy, Close (+18 more)

### Community 25 - "RejectionReason"
Cohesion: 0.08
Nodes (30): InstrumentRules, AccountView, RejectionReason, BelowMinimum, Breaker, Cooldown, DuplicateIntent, EntryWorking (+22 more)

### Community 26 - "User"
Cohesion: 0.07
Nodes (9): User, TerminalQrCode, UserFactory, {closure#3}(), {closure#4}(), {closure#5}(), expectedModules(), readColoured() (+1 more)

### Community 27 - "Price"
Cohesion: 0.09
Nodes (16): Balance, ClientOrderId, OcoRequest, OrderRequest, OrderUpdate, VenueFill, VenueOrder, {closure#2}() (+8 more)

### Community 28 - ".of"
Cohesion: 0.16
Nodes (44): RiskState, StrategyLimits, account(), accountPolicy(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}() (+36 more)

### Community 29 - "SimulatedPerpVenueTest.php"
Cohesion: 0.20
Nodes (26): {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#15}(), {closure#18}(), {closure#19}() (+18 more)

### Community 30 - "RuntimeException"
Cohesion: 0.08
Nodes (7): ReplayDivergence, ArchiveMissing, ChecksumMismatch, PhrityTransport, StreamFailed, StreamTransport, FakeTransport

### Community 31 - "oi-momentum-long.strategy.yaml"
Cohesion: 0.07
Nodes (27): entries, long, exits, stop_loss, take_profit, when, fails_when, family (+19 more)

### Community 32 - "App\Market\Data\FundingRate"
Cohesion: 0.15
Nodes (4): {closure#1}(), {closure#3}(), PerpetualTracker, PerpetualView

### Community 33 - "SimClock"
Cohesion: 0.07
Nodes (38): EngineMode, Backtest, Live, Paper, Testnet, InMemoryDecisionLog, SimulatedVenue, MinuteCandles (+30 more)

### Community 34 - "BinanceArchive"
Cohesion: 0.10
Nodes (7): ArchivePeriod, BinanceArchive, {closure#10}(), ExchangeNumber, TradeFlowBuilder, {closure#1}(), {closure#2}()

### Community 35 - "LadderTest.php"
Cohesion: 0.10
Nodes (35): {closure#4}(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}() (+27 more)

### Community 36 - "Illuminate\Http\Client\Request"
Cohesion: 0.10
Nodes (24): {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), fakeArchiveListing(), {closure#12}(), {closure#20}(), {closure#5}() (+16 more)

### Community 37 - "InstrumentSyncTest.php"
Cohesion: 0.07
Nodes (20): InstrumentDefinition, InstrumentStatus, Delisted, Halted, Trading, {closure#1}(), InstrumentSync, SyncResult (+12 more)

### Community 38 - "OrderManagerTest.php"
Cohesion: 0.10
Nodes (19): OrderAck, OrderRole, Entry, Exit, Flatten, Stop, Target, ProtectionPlan (+11 more)

### Community 39 - "BacktestTest.php"
Cohesion: 0.07
Nodes (37): FeeSchedule, CostModel, CandleFeed, FundingRate, RulesHistory, RiskProfile, Account, Benchmark (+29 more)

### Community 40 - "App\Strategy\Dsl\Document\DocumentChecker"
Cohesion: 0.06
Nodes (18): {closure#2}(), Argument, BoolLiteral, Call, DurationLiteral, Identifier, Lookback, NumberLiteral (+10 more)

### Community 41 - "ema-cross-scalp-long.strategy.yaml"
Cohesion: 0.06
Nodes (33): entries, long, exits, stop_loss, take_profit, time_stop, when, fails_when (+25 more)

### Community 42 - "Decimal"
Cohesion: 0.03
Nodes (49): {closure#6}(), RoundTrip, {closure#1}(), {closure#2}(), {closure#3}(), {closure#10}(), {closure#5}(), {closure#4}() (+41 more)

### Community 43 - ".zero"
Cohesion: 0.10
Nodes (17): Liquidity, Maker, Taker, OrderStatus, Cancelled, Expired, Filled, New (+9 more)

### Community 44 - "App"
Cohesion: 0.08
Nodes (32): SendTelegram, App, {closure#1}(), {closure#2}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#1}() (+24 more)

### Community 45 - "Timeframe"
Cohesion: 0.07
Nodes (36): CandleAggregator, CandleSequenceBroken, {closure#2}(), {closure#4}(), {closure#2}(), {closure#8}(), Vwap, Timeframe (+28 more)

### Community 46 - "Illuminate\Database\Eloquent\Model"
Cohesion: 0.06
Nodes (26): McpCall, McpToken, AccountEquity, AccountSnapshot, AccountTrade, PlainDecimal, {closure#1}(), {closure#2}() (+18 more)

### Community 47 - "package.json"
Cohesion: 0.15
Nodes (17): dependencies, codemirror@^6.0.2, @codemirror/lang-yaml@^6.1.3, @codemirror/language@^6.13.1, @lezer/highlight@^1.2.5, lightweight-charts@^5.2.1, optionalDependencies, @laravel/multiplex@^0.4.1 (+9 more)

### Community 48 - "Window"
Cohesion: 0.10
Nodes (5): DonchianChannel, StandardDeviation, Stochastic, Vwma, Window

### Community 49 - "BinanceRestClientTest.php"
Cohesion: 0.17
Nodes (18): {closure#16}(), binanceClient(), {closure#1}(), {closure#11}(), {closure#13}(), {closure#15}(), {closure#16}(), {closure#17}() (+10 more)

### Community 50 - "ema-cross-scalp-short.strategy.yaml"
Cohesion: 0.06
Nodes (33): entries, short, exits, stop_loss, take_profit, time_stop, when, fails_when (+25 more)

### Community 51 - "App\Livewire\SettingsScreen"
Cohesion: 0.06
Nodes (16): Account, Deployment, JournalEntry, ReauthController, AccountScreen, ChartScreen, {closure#2}(), {closure#3}() (+8 more)

### Community 54 - "postgres"
Cohesion: 0.10
Nodes (19): POSTGRES_DB, POSTGRES_PASSWORD, POSTGRES_USER, interval, retries, test, timeout, environment (+11 more)

### Community 55 - "InvalidArgumentException"
Cohesion: 0.11
Nodes (10): {closure#1}(), {closure#2}(), {closure#1}(), {closure#1}(), {closure#3}(), {closure#5}(), {closure#8}(), {closure#1}() (+2 more)

### Community 56 - "SizedStrategyTest.php"
Cohesion: 0.14
Nodes (10): SizedStrategy, {closure#1}(), {closure#2}(), {closure#1}(), {closure#2}(), identity(), limits(), onCandle() (+2 more)

### Community 57 - "donchian-breakout-short.strategy.yaml"
Cohesion: 0.07
Nodes (26): entries, short, default, range, step, default, range, step (+18 more)

### Community 58 - "IndicatorsTest.php"
Cohesion: 0.10
Nodes (20): Atr, Ema, Period, Sma, {closure#5}(), bar(), {closure#1}(), {closure#12}() (+12 more)

### Community 59 - "support.mjs"
Cohesion: 0.26
Nodes (9): @playwright/test, confirmed(), checkLayout(), EMAIL, freshCode(), PASSWORD, reauthenticate(), totp() (+1 more)

### Community 60 - "RunBacktestCommandTest.php"
Cohesion: 0.14
Nodes (26): PriceMinute, BacktestRun, {closure#1}(), {closure#9}(), {closure#4}(), {closure#1}(), {closure#10}(), {closure#11}() (+18 more)

### Community 61 - "IndicatorReferenceTest.php"
Cohesion: 0.06
Nodes (23): {closure#43}(), Bollinger, Change, Highest, Hma, Lowest, Macd, RealizedVolatility (+15 more)

### Community 62 - "App\Support\Clock\Clock"
Cohesion: 0.08
Nodes (7): ChartLiveController, Backfill, {closure#1}(), FormingCandles, CandleStream, ScalpingCollector, LiveBar

### Community 63 - ".timestamp"
Cohesion: 0.16
Nodes (11): {closure#2}(), {closure#4}(), OpenInterest, DatabaseCandleFeed, {closure#1}(), Rows, {closure#1}(), {closure#2}() (+3 more)

### Community 64 - "RunEngine.php"
Cohesion: 0.10
Nodes (7): {closure#4}(), {closure#5}(), Notifier, EngineCommandRow, HistoryImports, MarketJob, TelegramNotifier

### Community 65 - "donchian-breakout.strategy.yaml"
Cohesion: 0.07
Nodes (26): entries, long, default, range, step, default, range, step (+18 more)

### Community 66 - ".run"
Cohesion: 0.09
Nodes (28): PerformanceMetrics, Report, btcMinutes(), {closure#1}(), {closure#2}(), {closure#3}(), referencePrice(), {closure#13}() (+20 more)

### Community 67 - "StrategyIdentity"
Cohesion: 0.22
Nodes (5): StrategyIdentity, RenamedStrategy, {closure#1}(), {closure#2}(), {closure#3}()

### Community 68 - "App\Strategy\Dsl\Document\DocumentChecker"
Cohesion: 0.08
Nodes (30): {closure#3}(), {closure#4}(), {closure#9}(), ParameterSpec, FunctionSpec, Library, Scope, {closure#1}() (+22 more)

### Community 69 - "PreparesBacktests"
Cohesion: 0.11
Nodes (3): PreparesBacktests, PromoteStrategy, RunBacktest

### Community 70 - "CandleFeedTest.php"
Cohesion: 0.31
Nodes (13): {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#2}(), {closure#4}(), {closure#5}() (+5 more)

### Community 71 - "SettingsScreen"
Cohesion: 0.08
Nodes (8): LiveGate, OpenAccount, LiveApproval, {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), SettingsScreen

### Community 72 - "ExitReason"
Cohesion: 0.17
Nodes (23): ExitReason, Flatten, Liquidation, Signal, Stop, Target, {closure#1}(), {closure#10}() (+15 more)

### Community 73 - "scripts"
Cohesion: 0.15
Nodes (13): scripts, analyse, dev, format, mutate, post-autoload-dump, post-update-cmd, pre-package-uninstall (+5 more)

### Community 74 - "ma-trend-short.strategy.yaml"
Cohesion: 0.07
Nodes (26): entries, short, exits, stop_loss, when, fails_when, family, default (+18 more)

### Community 75 - ".breaches"
Cohesion: 0.24
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
Cohesion: 0.13
Nodes (18): Cscv, DeflatedSharpe, closeTo(), {closure#1}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}() (+10 more)

### Community 82 - "MonteCarlo"
Cohesion: 0.15
Nodes (11): {closure#4}(), {closure#5}(), MonteCarlo, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}() (+3 more)

### Community 83 - "App\Support\Number\Decimal"
Cohesion: 0.07
Nodes (53): TradeFlow, {closure#1}(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}() (+45 more)

### Community 84 - "pint/composer.json"
Cohesion: 0.20
Nodes (9): config, sort-packages, description, license, name, require, laravel/pint, php (+1 more)

### Community 85 - ".evaluate"
Cohesion: 0.24
Nodes (9): Gates, brokenGate(), {closure#1}(), {closure#3}(), {closure#4}(), {closure#6}(), {closure#7}(), gate() (+1 more)

### Community 86 - "RunSimulation.php"
Cohesion: 0.13
Nodes (4): ImportMarketData, {closure#3}(), RunSimulation, SimulationJob

### Community 87 - "DocumentCheckerTest.php"
Cohesion: 0.27
Nodes (10): DslWriter, checkDocument(), {closure#2}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}(), {closure#8}() (+2 more)

### Community 88 - "require-dev"
Cohesion: 0.22
Nodes (9): require-dev, fakerphp/faker, larastan/larastan, laravel/pail, laravel/pao, mockery/mockery, nunomaduro/collision, pestphp/pest (+1 more)

### Community 89 - "scripts-descriptions"
Cohesion: 0.25
Nodes (8): scripts-descriptions, analyse, format, mutate, test, test:network, test:testnet, tools:install

### Community 90 - "Engine.php"
Cohesion: 0.08
Nodes (11): {closure#1}(), {closure#11}(), {closure#2}(), {closure#3}(), {closure#9}(), RiskEvent, RiskEventKind, DailyLossHalt (+3 more)

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
Cohesion: 0.10
Nodes (6): DiskUsage, {closure#1}(), HealthAlerts, HealthChecks, HealthResult, FakeDisk

### Community 97 - "jobs"
Cohesion: 0.25
Nodes (8): name, runs-on, name, runs-on, steps, jobs, analyse, audit

### Community 98 - ".parse"
Cohesion: 0.11
Nodes (18): AstPrinter, Group, Exit, Indicator, Order, Perpetual, Position, Price (+10 more)

### Community 99 - "psr-4"
Cohesion: 0.40
Nodes (5): autoload, psr-4, App\\, Database\\Factories\\, Database\\Seeders\\

### Community 100 - "require"
Cohesion: 0.22
Nodes (9): require, bacon/bacon-qr-code, laravel/fortify, laravel/framework, laravel/mcp, laravel/tinker, livewire/livewire, php (+1 more)

### Community 102 - "AppServiceProvider.php"
Cohesion: 0.05
Nodes (16): ArchiveCatalog, SystemSleeper, SyncInstruments, LocalDiskUsage, AppServiceProvider, {closure#4}(), {closure#5}(), {closure#6}() (+8 more)

### Community 103 - "StrategyGates.php"
Cohesion: 0.05
Nodes (31): {closure#11}(), {closure#12}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#6}(), {closure#8}(), {closure#2}() (+23 more)

### Community 104 - "uses"
Cohesion: 0.21
Nodes (12): steps, name, runs-on, steps, frontend, lint, name, runs-on (+4 more)

### Community 106 - "devDependencies"
Cohesion: 0.18
Nodes (12): devDependencies, concurrently@^10.0.3, laravel-vite-plugin@^3.1, @playwright/test@1.63.0, tailwindcss@^4.0.0, @tailwindcss/vite@^4.0.0, vite@^8.0.0, concurrently (+4 more)

### Community 108 - "Laravel\Mcp\Response"
Cohesion: 0.16
Nodes (5): ChartTool, GetCandles, GetIndicators, ListInstruments, ChartSeries

### Community 117 - "ParamKind"
Cohesion: 0.22
Nodes (7): ParamKind, Anchor, Count, Duration, Number, Percent, Series

### Community 118 - "App\Agents\Mcp\TradingServer"
Cohesion: 0.21
Nodes (3): DslReference, GetFunding, TradingServer

### Community 119 - "RulesHistoryTest.php"
Cohesion: 0.33
Nodes (5): {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), rules()

### Community 120 - "buy-and-hold.strategy.yaml"
Cohesion: 0.12
Nodes (16): entries, long, exits, stop_loss, fails_when, family, hypothesis, when (+8 more)

### Community 121 - "ChartSeriesTest.php"
Cohesion: 0.17
Nodes (11): VolumeProfile, chart(), {closure#10}(), {closure#11}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#6}() (+3 more)

### Community 122 - "TokensTest.php"
Cohesion: 0.31
Nodes (6): {closure#3}(), {closure#4}(), contrastRatio(), contrastRules(), relativeLuminance(), themeTokens()

### Community 123 - "ArchiveImportTest.php"
Cohesion: 0.29
Nodes (10): archiveBytes(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}() (+2 more)

### Community 124 - "Laravel\Fortify\Contracts\TwoFactorAuthenticationProvider"
Cohesion: 0.31
Nodes (3): ApproveLive, ConfirmsOperator, CreateAccount

### Community 125 - "Illuminate\Database\Migrations\Migration"
Cohesion: 0.15
Nodes (3): {closure#1}(), {closure#1}(), {closure#1}()

### Community 126 - "StrategyVersion"
Cohesion: 0.22
Nodes (5): {closure#1}(), ListStrategies, {closure#3}(), SaveResult, StrategyVersion

### Community 127 - ".register"
Cohesion: 0.13
Nodes (17): {closure#10}(), {closure#13}(), {closure#14}(), {closure#15}(), {closure#18}(), {closure#21}(), {closure#22}(), {closure#23}() (+9 more)

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
Cohesion: 0.16
Nodes (6): NotificationSettings, SettingSource, Env, None, TelegramClient, TelegramSettings

### Community 133 - "Income"
Cohesion: 0.13
Nodes (10): Income, IncomeKind, Funding, Liquidation, UpdateStream, {closure#3}(), {closure#1}(), {closure#34}() (+2 more)

### Community 135 - "settings-screen.blade.php"
Cohesion: 0.17
Nodes (11): approveLive, confirm, forgetKey, forgetTelegram, monitor, notify, removeInstrument, revokeToken (+3 more)

### Community 137 - "0001_01_01_000000_create_users_table.php"
Cohesion: 0.33
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

### Community 138 - "steps"
Cohesion: 0.33
Nodes (6): name, runs-on, services, steps, e2e, if

### Community 139 - "2026_11_09_000014_create_health_tables.php"
Cohesion: 0.33
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

### Community 140 - "DecimalMath"
Cohesion: 0.15
Nodes (15): DecimalMath, InvalidNumber, {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}() (+7 more)

### Community 141 - "App\Support\Clock\Clock"
Cohesion: 0.09
Nodes (25): {closure#1}(), {closure#1}(), {closure#11}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}() (+17 more)

### Community 142 - "Statistics"
Cohesion: 0.22
Nodes (12): Statistics, {closure#1}(), {closure#2}(), {closure#3}(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}() (+4 more)

### Community 143 - "CandleRepositoryTest.php"
Cohesion: 0.28
Nodes (11): candleStore(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}() (+3 more)

### Community 144 - "Illuminate\Database\Migrations\Migration"
Cohesion: 0.15
Nodes (3): {closure#1}(), {closure#1}(), {closure#1}()

### Community 145 - "Illuminate\Support\Facades\Artisan"
Cohesion: 0.12
Nodes (11): {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), scalpingBackfill(), {closure#6}(), {closure#1}() (+3 more)

### Community 146 - "QualityCheck"
Cohesion: 0.18
Nodes (3): CheckMarketData, QualityCheck, QualityReport

### Community 147 - "scripts"
Cohesion: 0.50
Nodes (4): scripts, build, dev, e2e

### Community 148 - "0001_01_01_000002_create_jobs_table.php"
Cohesion: 0.33
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

### Community 151 - "Carbon\CarbonImmutable"
Cohesion: 0.29
Nodes (7): {closure#4}(), BreakerEvent, MarketBreakers, breakerMinute(), {closure#1}(), {closure#2}(), {closure#3}()

### Community 157 - "strategies-screen.blade.php"
Cohesion: 0.33
Nodes (5): open, promote, promoteDeployment, run, save

### Community 159 - "extra"
Cohesion: 0.67
Nodes (3): extra, laravel, dont-discover

### Community 174 - "StrategyLibrary"
Cohesion: 0.21
Nodes (4): CompileStrategy, StrategiesScreen, StrategyLibrary, CheckResult

### Community 175 - "MarketDataSettingsTest.php"
Cohesion: 0.11
Nodes (11): {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), savedEmaCross(), {closure#2}(), {closure#3}(), {closure#4}() (+3 more)

### Community 176 - "Illuminate\Foundation\Testing\RefreshDatabase"
Cohesion: 0.12
Nodes (7): DatabaseSeeder, {closure#1}(), {closure#2}(), {closure#3}(), {closure#1}(), {closure#1}(), E2eSeeder

### Community 177 - "OrderManagerTest.php"
Cohesion: 0.36
Nodes (14): btcUsdt(), {closure#10}(), {closure#15}(), {closure#16}(), {closure#2}(), {closure#4}(), {closure#5}(), {closure#6}() (+6 more)

### Community 178 - "Asset"
Cohesion: 0.16
Nodes (4): Marks, Asset, {closure#25}(), {closure#9}()

### Community 180 - "Venue"
Cohesion: 0.16
Nodes (4): Venue, ReconciliationMismatch, {closure#32}(), {closure#34}()

### Community 181 - "SettingsScreenTest.php"
Cohesion: 0.20
Nodes (13): {closure#10}(), {closure#11}(), {closure#13}(), {closure#14}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}() (+5 more)

### Community 182 - "Ledger"
Cohesion: 0.24
Nodes (5): Ledger, LedgerViolation, {closure#19}(), {closure#12}(), {closure#14}()

### Community 183 - "App\Strategy\Dsl\Document\StrategySpec"
Cohesion: 0.31
Nodes (7): {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#7}(), readBackDocument(), readBackSpec()

### Community 184 - "SimulationQueue"
Cohesion: 0.18
Nodes (4): RunBacktest, RunGrid, SimulationTool, SimulationQueue

### Community 189 - "MarketType"
Cohesion: 0.09
Nodes (21): BinanceEndpoints, BinanceRequestFailed, BinanceRestClient, BinanceUnreachable, EpochTime, Json, LocationRestricted, RateLimited (+13 more)

### Community 190 - "BarIndicator"
Cohesion: 0.14
Nodes (4): BarIndicator, Keltner, Natr, Obv

### Community 192 - ".handle"
Cohesion: 0.14
Nodes (4): GridBacktest, ReadsGridOptions, StrategyGates, StrategySpec

### Community 195 - "2026_11_09_000016_create_market_universe_tables.php"
Cohesion: 0.40
Nodes (3): {closure#1}(), {closure#2}(), up()

### Community 196 - "CandleStreamTest.php"
Cohesion: 0.21
Nodes (14): Sleeper, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#7}(), {closure#8}() (+6 more)

### Community 197 - "2026_11_09_000017_create_live_tables.php"
Cohesion: 0.33
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

### Community 198 - "start-local.ps1"
Cohesion: 0.27
Nodes (4): Stop-Postgres(), Stop-ProcessTree(), Test-PostgresRunning(), Write-Step()

### Community 201 - ".decide"
Cohesion: 0.17
Nodes (5): DecisionLog, DecisionRecord, IntentRecord, {closure#1}(), DatabaseDecisionLog

### Community 203 - ".of"
Cohesion: 0.09
Nodes (29): {closure#1}(), {closure#2}(), {closure#3}(), documentedLimits(), {closure#41}(), {closure#44}(), {closure#45}(), {closure#46}() (+21 more)

### Community 204 - "LiveAccountTest.php"
Cohesion: 0.13
Nodes (18): ExchangeKey, approveLive(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}() (+10 more)

### Community 208 - "App\Agents\Mcp\Tools\ListTrials"
Cohesion: 0.33
Nodes (3): {closure#1}(), ListTrials, StrategyTrial

### Community 210 - "WallClock"
Cohesion: 0.33
Nodes (3): WallClock, {closure#1}(), {closure#6}()

### Community 214 - "VenuePosition"
Cohesion: 0.31
Nodes (4): VenuePosition, {closure#1}(), SimulatedPosition, {closure#13}()

### Community 218 - ".number"
Cohesion: 0.29
Nodes (4): {closure#4}(), {closure#40}(), {closure#41}(), {closure#57}()

## Knowledge Gaps
- **148 isolated node(s):** `open`, `save`, `run`, `promote`, `promoteDeployment` (+143 more)
  These have ≤1 connection - possible missing edges. (Counts symbols only; 947 node(s) total have ≤1 connection when file, concept and rationale nodes are included.)
- **56 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `Decimal` connect `Decimal` to `Strategy`, `engineHarness`, `CanonicalJson`, `DslError`, `Engine`, `Income`, `DocumentChecker`, `.parse`, `Side`, `BinanceVenueTest.php`, `.of`, `Candle`, `App\Support\Clock\Clock`, `Statistics`, `PolicyLimits`, `Node`, `DecimalMath`, `Quantity`, `LedgerTest.php`, `App\Strategy\Dsl\Compiler\CompiledStrategy`, `Symbol`, `RejectionReason`, `Price`, `.of`, `App\Market\Data\FundingRate`, `SimClock`, `OrderManagerTest.php`, `BacktestTest.php`, `App\Strategy\Dsl\Document\DocumentChecker`, `.zero`, `Timeframe`, `Window`, `OrderManagerTest.php`, `Asset`, `Venue`, `Ledger`, `InvalidArgumentException`, `SizedStrategyTest.php`, `App\Strategy\Dsl\Document\StrategySpec`, `IndicatorsTest.php`, `RunBacktestCommandTest.php`, `IndicatorReferenceTest.php`, `BarIndicator`, `.zero`, `.handle`, `.isPositive`, `.run`, `App\Strategy\Dsl\Document\DocumentChecker`, `CandleFeedTest.php`, `WarmingVenue`, `.plus`, `ExitReason`, `ForwardingVenue`, `.of`, `.measure`, `Fill`, `DecimalMath`, `MonteCarlo`, `App\Support\Number\Decimal`, `.evaluate`, `VenuePosition`, `DocumentCheckerTest.php`, `Engine.php`, `.fromStrings`, `StrategyGates.php`, `RulesHistoryTest.php`, `ChartSeriesTest.php`?**
  _High betweenness centrality (0.125) - this node is a cross-community bridge._
- **What connects `open`, `save`, `run` to the rest of the system?**
  _148 weakly-connected nodes found - possible missing edges._
- **Should `Strategy` be split into smaller, more focused modules?**
  _Cohesion score 0.07686274509803921 - nodes in this community are weakly interconnected._
- **Why does `Symbol` connect `Symbol` to `Strategy`, `engineHarness`, `CanonicalJson`, `DslError`, `Engine`, `Income`, `DocumentChecker`, `.parse`, `Side`, `BinanceVenueTest.php`, `ExchangeVerificationTest.php`, `PolicyLimits`, `CandleRepositoryTest.php`, `Instrument`, `LedgerTest.php`, `RejectionReason`, `Price`, `.of`, `SimClock`, `OrderManagerTest.php`, `BacktestTest.php`, `Decimal`, `OrderManagerTest.php`, `Asset`, `Venue`, `Ledger`, `InvalidArgumentException`, `App\Strategy\Dsl\Document\StrategySpec`, `RunBacktestCommandTest.php`, `.zero`, `.handle`, `.run`, `CandleFeedTest.php`, `WarmingVenue`, `SettingsScreen`, `.decide`, `ExitReason`, `.plus`, `ForwardingVenue`, `.measure`, `Fill`, `VenuePosition`, `Engine.php`, `StrategyGates.php`, `Laravel\Mcp\Response`, `RulesHistoryTest.php`, `ChartSeriesTest.php`?**
  _High betweenness centrality (0.042) - this node is a cross-community bridge._
- **Should `engineHarness` be split into smaller, more focused modules?**
  _Cohesion score 0.10987726475745178 - nodes in this community are weakly interconnected._
- **Why does `Side` connect `Side` to `Strategy`, `DslError`, `Engine`, `Income`, `.parse`, `BinanceVenueTest.php`, `.of`, `LedgerTest.php`, `App\Strategy\Dsl\Compiler\CompiledStrategy`, `Symbol`, `RejectionReason`, `Price`, `SimulatedPerpVenueTest.php`, `SimClock`, `OrderManagerTest.php`, `BacktestTest.php`, `Decimal`, `.zero`, `OrderManagerTest.php`, `Asset`, `Venue`, `ReadBack`, `.zero`, `ExitReason`, `.plus`, `.of`, `.measure`, `Fill`, `VenuePosition`, `DocumentCheckerTest.php`?**
  _High betweenness centrality (0.017) - this node is a cross-community bridge._
- **Should `DslError` be split into smaller, more focused modules?**
  _Cohesion score 0.10336817653890824 - nodes in this community are weakly interconnected._