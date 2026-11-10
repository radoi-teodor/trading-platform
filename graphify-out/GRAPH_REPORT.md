# Graph Report - trading-platform  (2026-11-10)

## Corpus Check
- 675 files · ~294,448 words
- Verdict: corpus is large enough that graph structure adds value.
- Unclassified: 103 file(s) not represented in the graph (top: .zip 38, .CHECKSUM 38, (none) 19)

## Summary
- 5522 nodes · 19244 edges · 210 communities (155 shown, 55 thin omitted)
- Extraction: 90% EXTRACTED · 10% INFERRED · 0% AMBIGUOUS · INFERRED: 1900 edges (avg confidence: 0.85)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `50121bcf`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- SimulatedPerpVenueTest.php
- engineHarness
- .times
- .timestamp
- SettingsScreen
- Strategy
- DocumentChecker
- .now
- CompiledStrategy.php
- .of
- Symbol
- PolicyLimits
- Candle
- ExchangeVerificationTest.php
- Engine
- .handle
- Venue
- Illuminate\Console\Command
- Carbon\CarbonImmutable
- ScreensTest.php
- Side
- Illuminate\Database\Schema\Blueprint
- generate.py
- Node
- Symbol
- .plus
- TerminalQrCodeTest.php
- .of
- EngineUniverseTest.php
- DslError
- .fromStrings
- oi-momentum-long.strategy.yaml
- App\Strategy\Data\StrategyContext
- ScalpingFunctionsTest.php
- BookDepthTest.php
- App\Livewire\StrategiesScreen
- ScalpingCollectorTest.php
- Engine.php
- OrderManagerTest.php
- .zero
- Position
- ema-cross-scalp-long.strategy.yaml
- Decimal
- .times
- TokenKind
- Timeframe
- Illuminate\Database\Eloquent\Model
- package.json
- App\Support\Symbol
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
- Psr\Log\LoggerInterface
- .minus
- Illuminate\Http\Request
- donchian-breakout.strategy.yaml
- .run
- OrderUpdate
- Kind
- HealthChecks
- DecisionRecord
- ChartSeries
- App\Market\Instruments\DailyHistory
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
- CandleRepository
- RunEngine.php
- BinanceRequestFailed
- require-dev
- scripts-descriptions
- MarketBreakers
- SizedStrategyTest.php
- config
- uses
- test
- with
- GetListingStats.php
- jobs
- LiveBar
- psr-4
- require
- App\Support\Clock\Clock
- StrategyGates.php
- uses
- Percent
- devDependencies
- Laravel\Mcp\Response
- .__construct
- BinanceArchive
- DslReference
- buy-and-hold.strategy.yaml
- TradingTool
- TokensTest.php
- ArchiveImportTest.php
- User
- Illuminate\Support\Facades\Schema
- App\Market\Binance\BinanceRestClient
- .register
- funding-carry.strategy.yaml
- McpServerTest.php
- env.mjs
- chart.js
- TelegramSettings
- settings-screen.blade.php
- Quantity
- steps
- 2026_11_09_000014_create_health_tables.php
- DecimalMath
- App\Market\Models\Instrument
- StrategyLibrary
- CandleRepositoryTest.php
- OrderBookTest.php
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
- .now
- 2026_11_09_000027_add_to_to_market_jobs.php
- UniverseStrategyTest.php
- 2026_11_09_000012_create_account_results_tables.php
- 2026_11_09_000023_create_universe_members_table.php
- StrategySpec
- UniverseBacktestTest.php
- MarketType
- PreparesBacktests
- 2026_11_09_000025_create_book_depth_table.php
- 0001_01_01_000002_create_jobs_table.php
- 2026_11_09_000009_create_exchange_keys_table.php
- 2026_11_09_000018_add_effective_at_to_deployments.php
- 2026_11_09_000016_create_market_universe_tables.php
- .handle
- .sqrt
- start-local.ps1
- App\Market\Data\FundingRate
- DatabaseDecisionLog
- 0001_01_01_000000_create_users_table.php
- MarketDataSettingsTest.php
- RulesHistory
- TradingServer
- .handle
- 2026_11_09_000020_add_lifetime_to_instruments.php
- CompiledStrategy
- BinanceVenueTest.php
- Illuminate\Database\ConnectionInterface
- App\Support\Number\Price
- 2026_11_09_000026_create_liquidation_tables.php
- ScreenMarket.php
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
- `{closure#2}()` --calls--> `App`  [INFERRED]
  tests/Feature/Web/MarketDataSettingsTest.php → app/Notifications/SettingSource.php

## Import Cycles
- None detected.

## Communities (210 total, 55 thin omitted)

### Community 0 - "SimulatedPerpVenueTest.php"
Cohesion: 0.20
Nodes (27): PriceMinute, {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#15}(), {closure#18}() (+19 more)

### Community 1 - "engineHarness"
Cohesion: 0.09
Nodes (43): EngineCommand, EngineMode, Backtest, Live, Paper, Testnet, EntryOrder, MoveStop (+35 more)

### Community 2 - ".times"
Cohesion: 0.06
Nodes (33): InstrumentRules, {closure#1}(), AccountView, RejectionReason, BelowMinimum, Breaker, Cooldown, Delisting (+25 more)

### Community 3 - ".timestamp"
Cohesion: 0.08
Nodes (18): {closure#1}(), {closure#2}(), OpenInterest, {closure#2}(), DatabaseCandleFeed, {closure#1}(), {closure#1}(), {closure#2}() (+10 more)

### Community 4 - "SettingsScreen"
Cohesion: 0.07
Nodes (13): CreateMcpToken, RevokeMcpToken, Tokens, SettingsScreen, {closure#10}(), {closure#11}(), {closure#14}(), {closure#3}() (+5 more)

### Community 5 - "Strategy"
Cohesion: 0.07
Nodes (57): Strategy, baselineDocument(), baselinePairs(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}() (+49 more)

### Community 6 - "DocumentChecker"
Cohesion: 0.17
Nodes (6): {closure#7}(), {closure#9}(), DocumentChecker, EntrySpec, ExitSpec, Expression

### Community 7 - ".now"
Cohesion: 0.18
Nodes (3): ReviewLadder, Ladder, LadderStatus

### Community 8 - "CompiledStrategy.php"
Cohesion: 0.08
Nodes (10): {closure#1}(), {closure#2}(), {closure#3}(), PerpetualTracker, {closure#47}(), {closure#54}(), Compiler, {closure#1}() (+2 more)

### Community 9 - ".of"
Cohesion: 0.11
Nodes (65): {closure#1}(), Perpetual, Side, Buy, Sell, btc(), {closure#10}(), {closure#11}() (+57 more)

### Community 10 - "Symbol"
Cohesion: 0.06
Nodes (25): {closure#2}(), Balance, ClientOrderId, OcoRequest, OrderRequest, OrderType, Limit, Market (+17 more)

### Community 11 - "PolicyLimits"
Cohesion: 0.10
Nodes (16): {closure#1}(), {closure#3}(), InstrumentDirectory, StoredInstruments, {closure#1}(), CompileStrategy, PolicyLimits, Position (+8 more)

### Community 12 - "Candle"
Cohesion: 0.07
Nodes (40): Candle, InvalidCandle, KlineMessage, DirectionalMovement, {closure#1}(), {closure#2}(), VolumeProfile, candleWith() (+32 more)

### Community 13 - "ExchangeVerificationTest.php"
Cohesion: 0.12
Nodes (29): {closure#1}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}() (+21 more)

### Community 14 - "Engine"
Cohesion: 0.15
Nodes (3): EngineRecord, Engine, ReconciliationMismatch

### Community 15 - ".handle"
Cohesion: 0.19
Nodes (3): {closure#2}(), {closure#3}(), StrategyGates

### Community 16 - "Venue"
Cohesion: 0.06
Nodes (10): Venue, WarmingVenue, ForwardingVenue, {closure#12}(), balances(), {closure#22}(), {closure#32}(), {closure#34}() (+2 more)

### Community 17 - "Illuminate\Console\Command"
Cohesion: 0.05
Nodes (25): AuthenticateMcpToken, ApproveLive, ConfirmsOperator, CreateAccount, FlattenEngine, HaltEngine, IssuesCommands, PauseStrategy (+17 more)

### Community 18 - "Carbon\CarbonImmutable"
Cohesion: 0.08
Nodes (12): FormingCandle, Positioning, QualityReport, RiskEvent, RiskEventKind, DailyLossHalt, DrawdownFlatten, DrawdownHalt (+4 more)

### Community 19 - "ScreensTest.php"
Cohesion: 0.11
Nodes (26): {closure#1}(), chartLiveQuery(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}() (+18 more)

### Community 20 - "Side"
Cohesion: 0.06
Nodes (66): ExitReason, Flatten, Liquidation, Signal, Stop, Target, Fill, Ledger (+58 more)

### Community 21 - "Illuminate\Database\Schema\Blueprint"
Cohesion: 0.14
Nodes (13): {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#1}(), {closure#2}(), {closure#3}() (+5 more)

### Community 22 - "generate.py"
Cohesion: 0.07
Nodes (30): atr(), bars(), bb(), change(), dmi(), donchian(), ema(), highest() (+22 more)

### Community 23 - "Node"
Cohesion: 0.24
Nodes (12): Binary, Node, DslWriter, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}() (+4 more)

### Community 24 - "Symbol"
Cohesion: 0.03
Nodes (40): BuyAndHold, DonchianBreakout, FundingCarry, MovingAverageTrend, BuildableStrategy, LimitedStrategy, Close, Intent (+32 more)

### Community 25 - ".plus"
Cohesion: 0.11
Nodes (5): PerpPosition, Position, {closure#16}(), {closure#6}(), {closure#7}()

### Community 26 - "TerminalQrCodeTest.php"
Cohesion: 0.21
Nodes (7): TerminalQrCode, {closure#3}(), {closure#4}(), {closure#5}(), expectedModules(), readColoured(), readPlain()

### Community 27 - ".of"
Cohesion: 0.17
Nodes (44): RiskState, StrategyLimits, account(), accountPolicy(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}() (+36 more)

### Community 28 - "EngineUniverseTest.php"
Cohesion: 0.18
Nodes (20): {closure#10}(), {closure#11}(), {closure#13}(), {closure#14}(), {closure#17}(), {closure#18}(), {closure#2}(), {closure#3}() (+12 more)

### Community 29 - "DslError"
Cohesion: 0.22
Nodes (11): {closure#1}(), DslError, YamlList, YamlMap, YamlReader, YamlScalar, YamlValue, {closure#1}() (+3 more)

### Community 30 - ".fromStrings"
Cohesion: 0.16
Nodes (19): {closure#9}(), {closure#3}(), {closure#2}(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}() (+11 more)

### Community 31 - "oi-momentum-long.strategy.yaml"
Cohesion: 0.07
Nodes (27): entries, long, exits, stop_loss, take_profit, when, fails_when, family (+19 more)

### Community 32 - "App\Strategy\Data\StrategyContext"
Cohesion: 0.11
Nodes (10): {closure#2}(), {closure#3}(), {closure#1}(), ScriptedUniverseStrategy, {closure#12}(), {closure#16}(), {closure#5}(), onCandle() (+2 more)

### Community 33 - "ScalpingFunctionsTest.php"
Cohesion: 0.16
Nodes (13): {closure#1}(), {closure#3}(), {closure#4}(), forceOrder(), backtestableScalpingHour(), {closure#3}(), {closure#5}(), {closure#6}() (+5 more)

### Community 34 - "BookDepthTest.php"
Cohesion: 0.12
Nodes (7): {closure#9}(), BookDepth, OrderBook, {closure#1}(), {closure#2}(), {closure#4}(), depthSnapshot()

### Community 35 - "App\Livewire\StrategiesScreen"
Cohesion: 0.08
Nodes (10): Account, Deployment, ReauthController, AccountScreen, HaltButton, {closure#1}(), ModeBadge, {closure#2}() (+2 more)

### Community 36 - "ScalpingCollectorTest.php"
Cohesion: 0.16
Nodes (14): WallClock, aggTrade(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#6}(), collector() (+6 more)

### Community 37 - "Engine.php"
Cohesion: 0.11
Nodes (6): {closure#1}(), {closure#11}(), {closure#12}(), {closure#2}(), {closure#3}(), {closure#9}()

### Community 38 - "OrderManagerTest.php"
Cohesion: 0.08
Nodes (33): OrderAck, OrderRole, Entry, Exit, Flatten, Stop, Target, ProtectionPlan (+25 more)

### Community 39 - ".zero"
Cohesion: 0.05
Nodes (50): FeeSchedule, CostModel, CandleFeed, Backtest, {closure#1}(), {closure#7}(), {closure#2}(), RunBacktest (+42 more)

### Community 40 - "Position"
Cohesion: 0.07
Nodes (15): Argument, BoolLiteral, Call, DurationLiteral, Identifier, Lookback, NumberLiteral, OnTimeframe (+7 more)

### Community 41 - "ema-cross-scalp-long.strategy.yaml"
Cohesion: 0.06
Nodes (33): entries, long, exits, stop_loss, take_profit, time_stop, when, fails_when (+25 more)

### Community 42 - "Decimal"
Cohesion: 0.04
Nodes (41): RoundTrip, {closure#1}(), {closure#1}(), {closure#10}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}() (+33 more)

### Community 43 - ".times"
Cohesion: 0.07
Nodes (20): Liquidity, Maker, Taker, OrderStatus, Cancelled, Expired, Filled, New (+12 more)

### Community 44 - "TokenKind"
Cohesion: 0.10
Nodes (19): Lexer, Token, TokenKind, Colon, Comma, Dot, Duration, End (+11 more)

### Community 45 - "Timeframe"
Cohesion: 0.06
Nodes (54): CandleAggregator, CandleSequenceBroken, {closure#8}(), Timeframe, FifteenMinutes, FiveMinutes, FourHours, OneDay (+46 more)

### Community 46 - "Illuminate\Database\Eloquent\Model"
Cohesion: 0.04
Nodes (55): McpCall, McpToken, IntentRecord, {closure#1}(), AccountEquity, AccountSnapshot, AccountTrade, PlainDecimal (+47 more)

### Community 47 - "package.json"
Cohesion: 0.14
Nodes (19): dependencies, codemirror@^6.0.2, @codemirror/lang-yaml@^6.1.3, @codemirror/language@^6.13.1, @lezer/highlight@^1.2.5, lightweight-charts@^5.2.1, optionalDependencies, @laravel/multiplex@^0.4.1 (+11 more)

### Community 48 - "App\Support\Symbol"
Cohesion: 0.04
Nodes (33): {closure#1}(), {closure#2}(), BackfillMarketData, SelectsInstruments, {closure#2}(), StreamCandles, {closure#1}(), VerifyMarketData (+25 more)

### Community 49 - "BinanceRestClientTest.php"
Cohesion: 0.08
Nodes (32): {closure#3}(), {closure#6}(), {closure#7}(), {closure#1}(), {closure#3}(), {closure#12}(), {closure#22}(), {closure#9}() (+24 more)

### Community 50 - "ema-cross-scalp-short.strategy.yaml"
Cohesion: 0.06
Nodes (33): entries, short, exits, stop_loss, take_profit, time_stop, when, fails_when (+25 more)

### Community 51 - "ChartScreen.php"
Cohesion: 0.04
Nodes (44): {closure#2}(), ChartScreen, {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#2}(), AstPrinter (+36 more)

### Community 52 - "ReadBack"
Cohesion: 0.08
Nodes (14): {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}(), {closure#8}(), {closure#9}() (+6 more)

### Community 53 - "RuntimeException"
Cohesion: 0.07
Nodes (8): QueryTooSlow, ArchiveMissing, ChecksumMismatch, OrderBookOutOfSync, PhrityTransport, StreamFailed, LocalDiskUsage, FakeTransport

### Community 54 - "postgres"
Cohesion: 0.10
Nodes (19): POSTGRES_DB, POSTGRES_PASSWORD, POSTGRES_USER, interval, retries, test, timeout, environment (+11 more)

### Community 55 - "UniverseSchedule"
Cohesion: 0.16
Nodes (4): UniverseSchedule, UniverseStrategy, {closure#17}(), {closure#1}()

### Community 56 - "App"
Cohesion: 0.05
Nodes (57): SendTelegram, App, {closure#12}(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#3}(), {closure#1}() (+49 more)

### Community 57 - "donchian-breakout-short.strategy.yaml"
Cohesion: 0.07
Nodes (26): entries, short, default, range, step, default, range, step (+18 more)

### Community 58 - "IndicatorsTest.php"
Cohesion: 0.09
Nodes (21): Atr, Donchian, Ema, Period, Sma, {closure#5}(), bar(), {closure#1}() (+13 more)

### Community 59 - "support.mjs"
Cohesion: 0.26
Nodes (9): @playwright/test, confirmed(), checkLayout(), EMAIL, freshCode(), PASSWORD, reauthenticate(), totp() (+1 more)

### Community 60 - "backtestFixture"
Cohesion: 0.08
Nodes (52): BacktestRun, {closure#10}(), {closure#23}(), {closure#6}(), {closure#8}(), {closure#1}(), {closure#2}(), {closure#4}() (+44 more)

### Community 61 - "IndicatorReferenceTest.php"
Cohesion: 0.04
Nodes (35): {closure#67}(), BarIndicator, Bollinger, Change, DonchianChannel, Highest, Hma, Keltner (+27 more)

### Community 62 - "Psr\Log\LoggerInterface"
Cohesion: 0.05
Nodes (18): BinanceEndpoints, Sleeper, SystemSleeper, {closure#1}(), {closure#1}(), {closure#2}(), DepthStream, FormingCandles (+10 more)

### Community 63 - ".minus"
Cohesion: 0.27
Nodes (9): MonteCarlo, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), mcDecimals() (+1 more)

### Community 64 - "Illuminate\Http\Request"
Cohesion: 0.06
Nodes (13): ArchiveCatalog, SyncInstruments, AppServiceProvider, {closure#2}(), {closure#6}(), {closure#7}(), {closure#8}(), {closure#3}() (+5 more)

### Community 65 - "donchian-breakout.strategy.yaml"
Cohesion: 0.07
Nodes (26): entries, long, default, range, step, default, range, step (+18 more)

### Community 66 - ".run"
Cohesion: 0.07
Nodes (32): RiskProfile, Account, Benchmark, RiskPolicy, PerformanceMetrics, Report, {closure#1}(), {closure#2}() (+24 more)

### Community 67 - "OrderUpdate"
Cohesion: 0.17
Nodes (11): Income, IncomeKind, Funding, Liquidation, OrderUpdate, {closure#1}(), UpdateStream, {closure#3}() (+3 more)

### Community 68 - "Kind"
Cohesion: 0.07
Nodes (37): {closure#1}(), {closure#3}(), {closure#4}(), ParameterSpec, Scope, {closure#4}(), TypeChecker, Kind (+29 more)

### Community 69 - "HealthChecks"
Cohesion: 0.16
Nodes (5): DiskUsage, {closure#1}(), HealthChecks, HealthResult, FakeDisk

### Community 71 - "ChartSeries"
Cohesion: 0.17
Nodes (5): {closure#1}(), ChartTool, GetCandles, GetIndicators, ChartSeries

### Community 72 - "App\Market\Instruments\DailyHistory"
Cohesion: 0.14
Nodes (8): {closure#1}(), {closure#2}(), FillDailyStats, DailyStats, {closure#1}(), {closure#3}(), {closure#4}(), DailyHistory

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
Cohesion: 0.20
Nodes (15): InMemoryDecisionLog, {closure#10}(), {closure#11}(), {closure#14}(), {closure#2}(), {closure#5}(), {closure#6}(), {closure#7}() (+7 more)

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
Cohesion: 0.10
Nodes (20): Cscv, DeflatedSharpe, Normal, closeTo(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}() (+12 more)

### Community 82 - "RegimeLabelsTest.php"
Cohesion: 0.18
Nodes (7): RegimeLabels, {closure#1}(), {closure#2}(), {closure#4}(), {closure#7}(), {closure#9}(), days()

### Community 83 - "App\Support\Number\Decimal"
Cohesion: 0.05
Nodes (71): {closure#2}(), {closure#3}(), {closure#6}(), Liquidations, TradeFlow, {closure#10}(), {closure#5}(), {closure#8}() (+63 more)

### Community 84 - "pint/composer.json"
Cohesion: 0.20
Nodes (9): config, sort-packages, description, license, name, require, laravel/pint, php (+1 more)

### Community 85 - "CandleRepository"
Cohesion: 0.07
Nodes (8): BackfillStep, CandleRepository, UpsertResult, ExchangeVerification, VerificationReport, VerificationSource, DailyArchive, Rest

### Community 86 - "RunEngine.php"
Cohesion: 0.11
Nodes (10): {closure#4}(), {closure#5}(), Notifier, EngineCommandRow, TelegramNotifier, {closure#1}(), {closure#2}(), {closure#3}() (+2 more)

### Community 87 - "BinanceRequestFailed"
Cohesion: 0.15
Nodes (6): BinanceApiError, BinanceRequestFailed, BinanceUnreachable, LocationRestricted, RateLimited, RequestFailed

### Community 88 - "require-dev"
Cohesion: 0.22
Nodes (9): require-dev, fakerphp/faker, larastan/larastan, laravel/pail, laravel/pao, mockery/mockery, nunomaduro/collision, pestphp/pest (+1 more)

### Community 89 - "scripts-descriptions"
Cohesion: 0.25
Nodes (8): scripts-descriptions, analyse, format, mutate, test, test:network, test:testnet, tools:install

### Community 90 - "MarketBreakers"
Cohesion: 0.11
Nodes (16): {closure#4}(), MinuteCandles, BreakerEvent, MarketBreakers, {closure#1}(), {closure#2}(), {closure#3}(), ReferenceMarketBreakers (+8 more)

### Community 91 - "SizedStrategyTest.php"
Cohesion: 0.10
Nodes (10): SizedStrategy, SizedUniverseStrategy, {closure#1}(), {closure#2}(), __construct(), enterOnce(), identity(), limits() (+2 more)

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

### Community 96 - "GetListingStats.php"
Cohesion: 0.22
Nodes (4): HeavyCalls, {closure#1}(), GetListingStats, ListingStudy

### Community 97 - "jobs"
Cohesion: 0.25
Nodes (8): name, runs-on, name, runs-on, steps, jobs, analyse, audit

### Community 99 - "psr-4"
Cohesion: 0.40
Nodes (5): autoload, psr-4, App\\, Database\\Factories\\, Database\\Seeders\\

### Community 100 - "require"
Cohesion: 0.22
Nodes (9): require, bacon/bacon-qr-code, laravel/fortify, laravel/framework, laravel/mcp, laravel/tinker, livewire/livewire, php (+1 more)

### Community 102 - "App\Support\Clock\Clock"
Cohesion: 0.07
Nodes (9): StrategiesScreen, BuildUniverse, HistoryImports, ResearchUniverse, ImportMarketData, MarketJob, RunSimulation, SimulationJob (+1 more)

### Community 103 - "StrategyGates.php"
Cohesion: 0.07
Nodes (19): {closure#11}(), {closure#12}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#6}(), {closure#8}(), {closure#2}() (+11 more)

### Community 104 - "uses"
Cohesion: 0.21
Nodes (12): steps, name, runs-on, steps, frontend, lint, name, runs-on (+4 more)

### Community 105 - "Percent"
Cohesion: 0.24
Nodes (3): Percent, {closure#14}(), {closure#15}()

### Community 106 - "devDependencies"
Cohesion: 0.18
Nodes (12): devDependencies, concurrently@^10.0.3, laravel-vite-plugin@^3.1, @playwright/test@1.63.0, tailwindcss@^4.0.0, @tailwindcss/vite@^4.0.0, vite@^8.0.0, concurrently (+4 more)

### Community 108 - "Laravel\Mcp\Response"
Cohesion: 0.14
Nodes (5): SeriesWindow, {closure#1}(), {closure#1}(), ListTrials, StrategyTrial

### Community 118 - "BinanceArchive"
Cohesion: 0.14
Nodes (6): BinanceArchive, {closure#1}(), ExchangeNumber, TradeFlowBuilder, {closure#1}(), {closure#2}()

### Community 120 - "buy-and-hold.strategy.yaml"
Cohesion: 0.12
Nodes (16): entries, long, exits, stop_loss, fails_when, family, hypothesis, when (+8 more)

### Community 121 - "TradingTool"
Cohesion: 0.11
Nodes (10): BoundedRead, Refused, GetDepth, GetFunding, GetJob, GetLiquidations, {closure#1}(), ListInstruments (+2 more)

### Community 122 - "TokensTest.php"
Cohesion: 0.31
Nodes (6): {closure#3}(), {closure#4}(), contrastRatio(), contrastRules(), relativeLuminance(), themeTokens()

### Community 123 - "ArchiveImportTest.php"
Cohesion: 0.11
Nodes (13): ArchivePeriod, Backfill, BackfillReport, archiveBytes(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}() (+5 more)

### Community 124 - "User"
Cohesion: 0.10
Nodes (3): User, UserFactory, {closure#1}()

### Community 125 - "Illuminate\Support\Facades\Schema"
Cohesion: 0.14
Nodes (4): {closure#1}(), {closure#1}(), {closure#1}(), {closure#2}()

### Community 126 - "App\Market\Binance\BinanceRestClient"
Cohesion: 0.08
Nodes (25): {closure#2}(), {closure#3}(), {closure#11}(), {closure#13}(), {closure#3}(), {closure#5}(), {closure#7}(), {closure#1}() (+17 more)

### Community 127 - ".register"
Cohesion: 0.07
Nodes (35): FundingRate, SimClock, {closure#1}(), {closure#10}(), {closure#13}(), {closure#14}(), {closure#15}(), {closure#16}() (+27 more)

### Community 128 - "funding-carry.strategy.yaml"
Cohesion: 0.12
Nodes (15): fails_when, family, hypothesis, params, enter_when, exit_when, margin_buffer, max_leverage_perp (+7 more)

### Community 129 - "McpServerTest.php"
Cohesion: 0.12
Nodes (21): actAsToken(), ask(), {closure#11}(), {closure#19}(), {closure#2}(), {closure#20}(), {closure#21}(), {closure#22}() (+13 more)

### Community 130 - "env.mjs"
Cohesion: 0.27
Nodes (4): sizes, BASE_URL, E2E_ENV, TELEGRAM_URL

### Community 131 - "chart.js"
Cohesion: 0.31
Nodes (6): faded(), options(), priceChart(), token(), volumeColor(), instrumentPicker()

### Community 132 - "TelegramSettings"
Cohesion: 0.12
Nodes (8): SendDailyReport, DailyReport, NotificationSettings, SettingSource, Env, None, TelegramClient, TelegramSettings

### Community 135 - "settings-screen.blade.php"
Cohesion: 0.15
Nodes (12): approveLive, confirm, forgetKey, forgetTelegram, importUniverse, monitor, notify, removeInstrument (+4 more)

### Community 137 - "Quantity"
Cohesion: 0.24
Nodes (3): Quantity, {closure#10}(), {closure#9}()

### Community 138 - "steps"
Cohesion: 0.33
Nodes (6): name, runs-on, services, steps, e2e, if

### Community 139 - "2026_11_09_000014_create_health_tables.php"
Cohesion: 0.33
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

### Community 140 - "DecimalMath"
Cohesion: 0.13
Nodes (15): DecimalMath, InvalidNumber, {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}() (+7 more)

### Community 141 - "App\Market\Models\Instrument"
Cohesion: 0.14
Nodes (15): {closure#1}(), {closure#10}(), {closure#12}(), {closure#14}(), {closure#2}(), {closure#4}(), {closure#6}(), {closure#8}() (+7 more)

### Community 142 - "StrategyLibrary"
Cohesion: 0.13
Nodes (7): CompileStrategy, GetStrategy, ListStrategies, SaveStrategy, StrategyLibrary, CheckResult, SaveResult

### Community 143 - "CandleRepositoryTest.php"
Cohesion: 0.21
Nodes (15): CandleSource, Archive, Rest, Stream, candleStore(), {closure#1}(), {closure#2}(), {closure#3}() (+7 more)

### Community 146 - "OrderBookTest.php"
Cohesion: 0.38
Nodes (7): DepthUpdate, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), levels(), update()

### Community 147 - "scripts"
Cohesion: 0.50
Nodes (4): scripts, build, dev, e2e

### Community 152 - ".breaches"
Cohesion: 0.29
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
Cohesion: 0.09
Nodes (6): RunBacktest, RunGrid, SimulationTool, Controller, {closure#3}(), SimulationQueue

### Community 176 - ".now"
Cohesion: 0.10
Nodes (16): AccountRuntime, LiveGate, OpenAccount, RunEngine, StoreKeys, ReplayDivergence, {closure#3}(), {closure#5}() (+8 more)

### Community 178 - "UniverseStrategyTest.php"
Cohesion: 0.23
Nodes (18): CrossSectionalStrategy, {closure#10}(), {closure#12}(), {closure#15}(), {closure#16}(), {closure#18}(), {closure#3}(), {closure#4}() (+10 more)

### Community 183 - "StrategySpec"
Cohesion: 0.16
Nodes (12): CarryTemplate, StrategySpec, UniverseRule, {closure#10}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}() (+4 more)

### Community 184 - "UniverseBacktestTest.php"
Cohesion: 0.09
Nodes (15): {closure#6}(), {closure#7}(), {closure#9}(), {closure#1}(), {closure#4}(), {closure#5}(), reportRows(), universeBacktest() (+7 more)

### Community 189 - "MarketType"
Cohesion: 0.09
Nodes (21): BinanceRestClient, {closure#3}(), {closure#4}(), EpochTime, Json, MarketType, Perp, Spot (+13 more)

### Community 190 - "PreparesBacktests"
Cohesion: 0.13
Nodes (3): {closure#1}(), {closure#2}(), PreparesBacktests

### Community 192 - "0001_01_01_000002_create_jobs_table.php"
Cohesion: 0.33
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

### Community 195 - "2026_11_09_000016_create_market_universe_tables.php"
Cohesion: 0.40
Nodes (3): {closure#1}(), {closure#2}(), up()

### Community 196 - ".handle"
Cohesion: 0.30
Nodes (6): CanonicalJson, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}()

### Community 197 - ".sqrt"
Cohesion: 0.12
Nodes (21): Statistics, Gates, {closure#1}(), {closure#2}(), {closure#3}(), brokenGate(), {closure#1}(), {closure#3}() (+13 more)

### Community 198 - "start-local.ps1"
Cohesion: 0.27
Nodes (4): Stop-Postgres(), Stop-ProcessTree(), Test-PostgresRunning(), Write-Step()

### Community 200 - "App\Market\Data\FundingRate"
Cohesion: 0.22
Nodes (5): {closure#2}(), {closure#15}(), PerpetualView, {closure#9}(), {closure#8}()

### Community 202 - "0001_01_01_000000_create_users_table.php"
Cohesion: 0.33
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

### Community 203 - "MarketDataSettingsTest.php"
Cohesion: 0.09
Nodes (14): {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), savedEmaCross(), {closure#1}(), {closure#2}(), {closure#3}() (+6 more)

### Community 205 - "RulesHistory"
Cohesion: 0.25
Nodes (9): RulesHistory, {closure#2}(), {closure#4}(), {closure#41}(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}() (+1 more)

### Community 213 - "CompiledStrategy"
Cohesion: 0.12
Nodes (6): CompiledStrategy, {closure#57}(), {closure#58}(), {closure#59}(), CrossSection, {closure#19}()

### Community 214 - "BinanceVenueTest.php"
Cohesion: 0.27
Nodes (10): binanceVenue(), {closure#1}(), {closure#2}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}(), {closure#8}() (+2 more)

### Community 215 - "Illuminate\Database\ConnectionInterface"
Cohesion: 0.08
Nodes (7): FundingRateRepository, Heartbeats, AssumedRules, QualityCheck, CheckHealth, HealthAlerts, GridPart

### Community 216 - "App\Support\Number\Price"
Cohesion: 0.13
Nodes (6): Marks, {closure#2}(), BacktestResult, ParticipantResult, {closure#9}(), {closure#1}()

### Community 219 - "ScreenMarket.php"
Cohesion: 0.07
Nodes (15): {closure#3}(), {closure#4}(), ListUniverse, {closure#1}(), {closure#4}(), ScreenMarket, ScreenMeasure, Funding (+7 more)

## Knowledge Gaps
- **157 isolated node(s):** `PriceReturn`, `QuoteVolume`, `Funding`, `OpenInterestChange`, `Volatility` (+152 more)
  These have ≤1 connection - possible missing edges. (Counts symbols only; 1024 node(s) total have ≤1 connection when file, concept and rationale nodes are included.)
- **55 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `Decimal` connect `Decimal` to `engineHarness`, `.times`, `Strategy`, `DocumentChecker`, `.of`, `Symbol`, `PolicyLimits`, `Candle`, `DecimalMath`, `Engine`, `Quantity`, `Venue`, `Carbon\CarbonImmutable`, `Side`, `Node`, `Symbol`, `.plus`, `.of`, `Engine.php`, `OrderManagerTest.php`, `.zero`, `Position`, `.times`, `App\Support\Symbol`, `ChartScreen.php`, `ReadBack`, `StrategySpec`, `IndicatorsTest.php`, `IndicatorReferenceTest.php`, `PreparesBacktests`, `.minus`, `.run`, `OrderUpdate`, `Kind`, `.sqrt`, `.handle`, `App\Market\Data\FundingRate`, `.evaluate`, `EnginePerpTest.php`, `RulesHistory`, `.isPositive`, `RegimeLabelsTest.php`, `App\Support\Number\Decimal`, `CompiledStrategy`, `BinanceVenueTest.php`, `App\Support\Number\Price`, `SizedStrategyTest.php`, `StrategyGates.php`, `Percent`, `.__construct`, `.register`?**
  _High betweenness centrality (0.131) - this node is a cross-community bridge._
- **Are the 181 inferred relationships involving `App` (e.g. with `.render()` and `up()`) actually correct?**
  _`App` has 181 INFERRED edges - model-reasoned connections that need verification._
- **What connects `PriceReturn`, `QuoteVolume`, `Funding` to the rest of the system?**
  _157 weakly-connected nodes found - possible missing edges._
- **Should `engineHarness` be split into smaller, more focused modules?**
  _Cohesion score 0.08779631255487269 - nodes in this community are weakly interconnected._
- **Why does `Timeframe` connect `Timeframe` to `engineHarness`, `Strategy`, `DocumentChecker`, `CompiledStrategy.php`, `.of`, `Candle`, `CandleRepositoryTest.php`, `Node`, `Symbol`, `.fromStrings`, `App\Strategy\Data\StrategyContext`, `OrderManagerTest.php`, `Position`, `.times`, `UniverseStrategyTest.php`, `ReadBack`, `StrategySpec`, `IndicatorsTest.php`, `IndicatorReferenceTest.php`, `.sqrt`, `ChartSeries`, `EnginePerpTest.php`, `RegimeLabelsTest.php`, `CandleRepository`, `.__construct`?**
  _High betweenness centrality (0.022) - this node is a cross-community bridge._
- **Should `.times` be split into smaller, more focused modules?**
  _Cohesion score 0.06436487638533675 - nodes in this community are weakly interconnected._
- **Why does `Symbol` connect `Symbol` to `engineHarness`, `Strategy`, `DocumentChecker`, `.of`, `PolicyLimits`, `Candle`, `ExchangeVerificationTest.php`, `Engine`, `CandleRepositoryTest.php`, `Venue`, `Side`, `Symbol`, `.plus`, `.of`, `EngineUniverseTest.php`, `App\Strategy\Data\StrategyContext`, `Engine.php`, `OrderManagerTest.php`, `.zero`, `Decimal`, `.times`, `App\Support\Symbol`, `.now`, `StrategySpec`, `backtestFixture`, `Illuminate\Http\Request`, `.run`, `OrderUpdate`, `.handle`, `DecisionRecord`, `ChartSeries`, `EnginePerpTest.php`, `RulesHistory`, `BinanceVenueTest.php`, `App\Support\Number\Price`, `.__construct`, `.register`?**
  _High betweenness centrality (0.021) - this node is a cross-community bridge._