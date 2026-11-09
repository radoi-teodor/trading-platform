# Graph Report - trading-platform  (2026-11-10)

## Corpus Check
- 652 files · ~278,933 words
- Verdict: corpus is large enough that graph structure adds value.
- Unclassified: 103 file(s) not represented in the graph (top: .zip 38, .CHECKSUM 38, (none) 19)

## Summary
- 5325 nodes · 18526 edges · 218 communities (152 shown, 66 thin omitted)
- Extraction: 90% EXTRACTED · 10% INFERRED · 0% AMBIGUOUS · INFERRED: 1763 edges (avg confidence: 0.85)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `2f32d5d4`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- SimulatedPerpVenueTest.php
- engineHarness
- .encode
- .of
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
- .act
- App\Support\Symbol
- Strategy
- Clock
- Instrument
- ScreensTest.php
- Side
- Illuminate\Database\Schema\Blueprint
- generate.py
- App\Strategy\Dsl\Compiler\CompiledStrategy
- Symbol
- ScalpingFunctionsTest.php
- TerminalQrCodeTest.php
- RejectionReason
- EngineUniverseTest.php
- App\Market\Instruments\Universe
- App\Market\Stream\PhrityTransport
- oi-momentum-long.strategy.yaml
- App\Strategy\Data\StrategyContext
- EnginePerpTest.php
- TradeFlowBuilder
- App\Livewire\StrategiesScreen
- ScalpingCollectorTest.php
- LadderTest.php
- OrderManagerTest.php
- InstrumentRules
- DslError
- ema-cross-scalp-long.strategy.yaml
- Decimal
- .times
- RoundTrip
- Timeframe
- Carbon\CarbonImmutable
- package.json
- BookDepth
- BinanceRestClientTest.php
- ema-cross-scalp-short.strategy.yaml
- ChartScreen.php
- ReadBack
- MarketDataSettingsTest.php
- postgres
- UniverseStrategy
- App\Strategy\Contracts\Strategy
- donchian-breakout-short.strategy.yaml
- IndicatorsTest.php
- support.mjs
- RunBacktestCommandTest.php
- IndicatorReferenceTest.php
- App\Support\Clock\Clock
- DecimalMath
- Illuminate\Http\Request
- donchian-breakout.strategy.yaml
- .run
- StrategySpec
- Kind
- MarketBreakersTest.php
- InstrumentSyncTest.php
- Statistics
- App\Execution\Journal\DatabaseDecisionLog
- scripts
- ma-trend-short.strategy.yaml
- .evaluate
- .measure
- composer.json
- jobs
- postgres
- ma-trend.strategy.yaml
- .isPositive
- MonteCarlo
- App\Support\Number\Decimal
- pint/composer.json
- HealthChecks
- RunSimulation.php
- .parse
- require-dev
- scripts-descriptions
- CommandKind
- .fromStrings
- config
- uses
- test
- with
- DiskUsage
- jobs
- Node
- psr-4
- require
- Quantity
- StrategyGates.php
- uses
- .handle
- devDependencies
- Laravel\Mcp\Response
- ChartSeriesTest.php
- BinanceArchive
- .breaches
- buy-and-hold.strategy.yaml
- PaperEngineTest.php
- TokensTest.php
- ArchiveImportTest.php
- User
- Illuminate\Support\Facades\Schema
- StrategyIdentity
- .register
- funding-carry.strategy.yaml
- McpServerTest.php
- env.mjs
- chart.js
- TelegramSettings
- Percent
- settings-screen.blade.php
- StrategyLibrary.php
- steps
- 2026_11_09_000014_create_health_tables.php
- .of
- App\Market\Models\Instrument
- RiskState
- CandleRepositoryTest.php
- Deployment
- .now
- OrderBookTest.php
- scripts
- 2026_11_08_000007_create_strategy_gate_tables.php
- fake-telegram.mjs
- 2026_11_08_000003_create_strategy_trials_table.php
- bootstrap/app.php
- 2026_11_08_000004_create_strategy_versions_tables.php
- 2026_11_08_000006_create_mcp_tables.php
- 2026_11_09_000017_create_live_tables.php
- AppServiceProvider
- strategies-screen.blade.php
- extra
- app.blade.php
- halt
- remove
- account-screen.blade.php
- Illuminate\Database\Migrations\Migration
- EngineMode
- .evaluate
- Venue
- 2026_11_09_000027_add_to_to_market_jobs.php
- UniverseStrategyTest.php
- 2026_11_09_000012_create_account_results_tables.php
- 2026_11_09_000023_create_universe_members_table.php
- SettingsScreenTest.php
- FundingCarry
- App\Strategy\Dsl\DslError
- ResearchUniverse.php
- MarketType
- PolicyLimits
- 2026_11_09_000025_create_book_depth_table.php
- 0001_01_01_000002_create_jobs_table.php
- 2026_11_09_000009_create_exchange_keys_table.php
- 2026_11_09_000018_add_effective_at_to_deployments.php
- 2026_11_09_000016_create_market_universe_tables.php
- .number
- TradingServer
- start-local.ps1
- StrategyTrial
- Symbol
- GlobalsTest.php
- 0001_01_01_000000_create_users_table.php
- depthStreamWith
- LadderStatus
- InstrumentRules
- App\Strategy\Indicators\BarIndicator
- Engine.php
- RiskPolicy
- 2026_11_09_000020_add_lifetime_to_instruments.php
- CrossSection
- App
- AppServiceProvider.php
- 2026_11_09_000026_create_liquidation_tables.php
- 2026_11_09_000022_create_daily_stats_table.php
- 2026_11_09_000010_add_two_factor_columns_to_users_table.php
- 2026_11_09_000024_create_positioning_table.php

## God Nodes (most connected - your core abstractions)
1. `Decimal` - 489 edges
2. `Side` - 203 edges
3. `Symbol` - 187 edges
4. `App` - 168 edges
5. `Candle` - 126 edges
6. `Timeframe` - 108 edges
7. `Price` - 98 edges
8. `Position` - 89 edges
9. `SimClock` - 86 edges
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
- `{closure#3}()` --calls--> `App`  [INFERRED]
  tests/Feature/Network/BinancePublicDataTest.php → app/Notifications/SettingSource.php

## Import Cycles
- None detected.

## Communities (218 total, 66 thin omitted)

### Community 0 - "SimulatedPerpVenueTest.php"
Cohesion: 0.18
Nodes (27): PriceMinute, {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#15}(), {closure#18}() (+19 more)

### Community 1 - "engineHarness"
Cohesion: 0.12
Nodes (35): ScriptedStrategy, {closure#1}(), {closure#10}(), {closure#13}(), {closure#14}(), {closure#16}(), {closure#17}(), {closure#2}() (+27 more)

### Community 2 - ".encode"
Cohesion: 0.30
Nodes (6): CanonicalJson, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}()

### Community 3 - ".of"
Cohesion: 0.25
Nodes (38): account(), accountPolicy(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#2}() (+30 more)

### Community 5 - "Strategy"
Cohesion: 0.12
Nodes (30): {closure#17}(), carryDecisions(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#15}() (+22 more)

### Community 6 - "DocumentChecker"
Cohesion: 0.18
Nodes (4): {closure#2}(), {closure#5}(), {closure#8}(), DocumentChecker

### Community 8 - "CompiledStrategy.php"
Cohesion: 0.07
Nodes (9): {closure#1}(), PerpetualTracker, CompiledStrategy, {closure#47}(), {closure#54}(), Compiler, {closure#1}(), Program (+1 more)

### Community 9 - "SimulatedVenueTest.php"
Cohesion: 0.17
Nodes (42): btc(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#15}(), {closure#16}() (+34 more)

### Community 10 - "BinanceVenueTest.php"
Cohesion: 0.13
Nodes (10): {closure#2}(), BinanceVenue, BinanceApiError, BinanceUnreachable, SignedBinanceClient, {closure#1}(), {closure#4}(), {closure#6}() (+2 more)

### Community 11 - ".of"
Cohesion: 0.07
Nodes (25): Marks, {closure#7}(), Asset, AssetMismatch, Money, balances(), {closure#2}(), {closure#40}() (+17 more)

### Community 12 - "Candle"
Cohesion: 0.12
Nodes (30): {closure#1}(), {closure#3}(), Candle, InvalidCandle, {closure#1}(), {closure#2}(), candleWith(), {closure#1}() (+22 more)

### Community 13 - "ExchangeVerificationTest.php"
Cohesion: 0.10
Nodes (31): VerificationSource, DailyArchive, Rest, {closure#1}(), {closure#12}(), {closure#13}(), {closure#2}(), {closure#3}() (+23 more)

### Community 15 - "App\Support\Symbol"
Cohesion: 0.06
Nodes (13): {closure#1}(), {closure#1}(), {closure#2}(), ExportStrategies, GridBacktest, GridPart, ImportStrategies, {closure#1}() (+5 more)

### Community 16 - "Strategy"
Cohesion: 0.15
Nodes (20): LimitedStrategy, Strategy, baselineDocument(), baselinePairs(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}() (+12 more)

### Community 17 - "Clock"
Cohesion: 0.09
Nodes (10): CreateMcpToken, RevokeMcpToken, AuthenticateMcpToken, Tokens, FlattenEngine, HaltEngine, IssuesCommands, PauseStrategy (+2 more)

### Community 18 - "Instrument"
Cohesion: 0.05
Nodes (26): BackfillStep, CandleRepository, {closure#2}(), {closure#4}(), {closure#1}(), CandleSource, Archive, Rest (+18 more)

### Community 19 - "ScreensTest.php"
Cohesion: 0.10
Nodes (30): {closure#1}(), approveLive(), {closure#9}(), {closure#1}(), {closure#2}(), {closure#3}(), fakeTestnet(), testnetAccount() (+22 more)

### Community 20 - "Side"
Cohesion: 0.10
Nodes (58): ExitReason, Flatten, Liquidation, Signal, Stop, Target, Fill, Ledger (+50 more)

### Community 21 - "Illuminate\Database\Schema\Blueprint"
Cohesion: 0.14
Nodes (13): {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#1}(), {closure#2}(), {closure#3}() (+5 more)

### Community 22 - "generate.py"
Cohesion: 0.07
Nodes (30): atr(), bars(), bb(), change(), dmi(), donchian(), ema(), highest() (+22 more)

### Community 23 - "App\Strategy\Dsl\Compiler\CompiledStrategy"
Cohesion: 0.07
Nodes (20): AstPrinter, {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}(), {closure#8}() (+12 more)

### Community 24 - "Symbol"
Cohesion: 0.05
Nodes (17): BuyAndHold, DonchianBreakout, MovingAverageTrend, BuildableStrategy, Close, EntryOrder, Intent, OpenLong (+9 more)

### Community 25 - "ScalpingFunctionsTest.php"
Cohesion: 0.08
Nodes (26): Positioning, {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#2}(), {closure#3}() (+18 more)

### Community 26 - "TerminalQrCodeTest.php"
Cohesion: 0.21
Nodes (7): TerminalQrCode, {closure#3}(), {closure#4}(), {closure#5}(), expectedModules(), readColoured(), readPlain()

### Community 27 - "RejectionReason"
Cohesion: 0.07
Nodes (32): AccountView, RejectionReason, BelowMinimum, Breaker, Cooldown, Delisting, DuplicateIntent, EntryWorking (+24 more)

### Community 28 - "EngineUniverseTest.php"
Cohesion: 0.16
Nodes (20): Policies, {closure#10}(), {closure#11}(), {closure#13}(), {closure#14}(), {closure#17}(), {closure#2}(), {closure#3}() (+12 more)

### Community 29 - "App\Market\Instruments\Universe"
Cohesion: 0.05
Nodes (16): Backfill, BackfillReport, BackfillMarketData, CheckMarketData, SelectsInstruments, {closure#1}(), {closure#2}(), StreamCandles (+8 more)

### Community 31 - "oi-momentum-long.strategy.yaml"
Cohesion: 0.07
Nodes (27): entries, long, exits, stop_loss, take_profit, when, fails_when, family (+19 more)

### Community 32 - "App\Strategy\Data\StrategyContext"
Cohesion: 0.11
Nodes (10): {closure#2}(), {closure#3}(), {closure#1}(), ScriptedUniverseStrategy, {closure#12}(), {closure#16}(), {closure#5}(), onCandle() (+2 more)

### Community 33 - "EnginePerpTest.php"
Cohesion: 0.24
Nodes (16): {closure#1}(), {closure#10}(), {closure#11}(), {closure#13}(), {closure#14}(), {closure#2}(), {closure#5}(), {closure#6}() (+8 more)

### Community 35 - "App\Livewire\StrategiesScreen"
Cohesion: 0.11
Nodes (6): Account, ReauthController, AccountScreen, HaltButton, {closure#1}(), ModeBadge

### Community 36 - "ScalpingCollectorTest.php"
Cohesion: 0.12
Nodes (19): WallClock, {closure#1}(), aggTrade(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#6}() (+11 more)

### Community 37 - "LadderTest.php"
Cohesion: 0.10
Nodes (35): {closure#4}(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}() (+27 more)

### Community 38 - "OrderManagerTest.php"
Cohesion: 0.09
Nodes (31): OrderAck, OrderRole, Entry, Exit, Flatten, Stop, Target, TrackedOrder (+23 more)

### Community 39 - "InstrumentRules"
Cohesion: 0.09
Nodes (24): FeeSchedule, InMemoryDecisionLog, CostModel, CandleFeed, FundingRate, Backtest, {closure#1}(), {closure#2}() (+16 more)

### Community 40 - "DslError"
Cohesion: 0.20
Nodes (12): {closure#1}(), DslError, YamlList, YamlMap, YamlReader, YamlScalar, YamlValue, {closure#1}() (+4 more)

### Community 41 - "ema-cross-scalp-long.strategy.yaml"
Cohesion: 0.06
Nodes (33): entries, long, exits, stop_loss, take_profit, time_stop, when, fails_when (+25 more)

### Community 42 - "Decimal"
Cohesion: 0.03
Nodes (49): {closure#6}(), PerpPosition, Position, {closure#1}(), {closure#2}(), {closure#3}(), {closure#10}(), {closure#11}() (+41 more)

### Community 43 - ".times"
Cohesion: 0.06
Nodes (17): Liquidity, Maker, Taker, OrderStatus, Cancelled, Expired, Filled, New (+9 more)

### Community 44 - "RoundTrip"
Cohesion: 0.13
Nodes (15): RoundTrip, {closure#1}(), {closure#2}(), {closure#4}(), {closure#6}(), {closure#7}(), {closure#10}(), {closure#2}() (+7 more)

### Community 45 - "Timeframe"
Cohesion: 0.06
Nodes (65): CandleAggregator, CandleSequenceBroken, {closure#3}(), {closure#4}(), {closure#2}(), {closure#3}(), Timeframe, FifteenMinutes (+57 more)

### Community 46 - "Carbon\CarbonImmutable"
Cohesion: 0.03
Nodes (28): McpCall, McpToken, AccountEquity, AccountSnapshot, AccountTrade, PlainDecimal, ExchangeKey, {closure#1}() (+20 more)

### Community 47 - "package.json"
Cohesion: 0.15
Nodes (17): dependencies, codemirror@^6.0.2, @codemirror/lang-yaml@^6.1.3, @codemirror/language@^6.13.1, @lezer/highlight@^1.2.5, lightweight-charts@^5.2.1, optionalDependencies, @laravel/multiplex@^0.4.1 (+9 more)

### Community 48 - "BookDepth"
Cohesion: 0.13
Nodes (6): {closure#9}(), BookDepth, OrderBook, PerpetualView, {closure#2}(), {closure#4}()

### Community 49 - "BinanceRestClientTest.php"
Cohesion: 0.08
Nodes (30): {closure#3}(), {closure#6}(), {closure#7}(), {closure#1}(), {closure#3}(), binanceClient(), {closure#1}(), {closure#11}() (+22 more)

### Community 50 - "ema-cross-scalp-short.strategy.yaml"
Cohesion: 0.06
Nodes (33): entries, short, exits, stop_loss, take_profit, time_stop, when, fails_when (+25 more)

### Community 51 - "ChartScreen.php"
Cohesion: 0.08
Nodes (18): {closure#2}(), ChartScreen, {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#1}(), {closure#2}() (+10 more)

### Community 53 - "MarketDataSettingsTest.php"
Cohesion: 0.07
Nodes (11): {closure#4}(), Notifier, BuildUniverse, HistoryImports, ResearchUniverse, MarketJob, TelegramNotifier, {closure#3}() (+3 more)

### Community 54 - "postgres"
Cohesion: 0.10
Nodes (19): POSTGRES_DB, POSTGRES_PASSWORD, POSTGRES_USER, interval, retries, test, timeout, environment (+11 more)

### Community 56 - "App\Strategy\Contracts\Strategy"
Cohesion: 0.10
Nodes (13): SizedStrategy, SizedUniverseStrategy, {closure#1}(), {closure#2}(), {closure#3}(), {closure#1}(), {closure#2}(), __construct() (+5 more)

### Community 57 - "donchian-breakout-short.strategy.yaml"
Cohesion: 0.07
Nodes (26): entries, short, default, range, step, default, range, step (+18 more)

### Community 58 - "IndicatorsTest.php"
Cohesion: 0.09
Nodes (23): Atr, Donchian, Ema, Period, Sma, {closure#5}(), bar(), {closure#1}() (+15 more)

### Community 59 - "support.mjs"
Cohesion: 0.26
Nodes (9): @playwright/test, confirmed(), checkLayout(), EMAIL, freshCode(), PASSWORD, reauthenticate(), totp() (+1 more)

### Community 60 - "RunBacktestCommandTest.php"
Cohesion: 0.08
Nodes (46): {closure#1}(), {closure#2}(), {closure#4}(), {closure#5}(), scalpingBackfill(), {closure#4}(), {closure#6}(), {closure#1}() (+38 more)

### Community 61 - "IndicatorReferenceTest.php"
Cohesion: 0.03
Nodes (33): BarIndicator, Bollinger, Change, DonchianChannel, Highest, Hma, Keltner, Lowest (+25 more)

### Community 62 - "App\Support\Clock\Clock"
Cohesion: 0.05
Nodes (10): DepthStream, FormingCandles, Heartbeats, LiquidationRepository, CandleStream, LiquidationCollector, ScalpingCollector, StreamTransport (+2 more)

### Community 63 - "DecimalMath"
Cohesion: 0.19
Nodes (12): DecimalMath, {closure#1}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#2}(), {closure#4}() (+4 more)

### Community 64 - "Illuminate\Http\Request"
Cohesion: 0.18
Nodes (4): ChartLiveController, {closure#8}(), {closure#3}(), {closure#4}()

### Community 65 - "donchian-breakout.strategy.yaml"
Cohesion: 0.07
Nodes (26): entries, long, default, range, step, default, range, step (+18 more)

### Community 66 - ".run"
Cohesion: 0.09
Nodes (27): PerformanceMetrics, TradeStatistics, Report, btcMinutes(), {closure#1}(), {closure#2}(), {closure#3}(), referencePrice() (+19 more)

### Community 67 - "StrategySpec"
Cohesion: 0.17
Nodes (6): EntrySpec, ExitSpec, Expression, StrategySpec, UniverseRule, {closure#10}()

### Community 68 - "Kind"
Cohesion: 0.06
Nodes (50): {closure#1}(), {closure#3}(), {closure#4}(), {closure#7}(), {closure#9}(), ParameterSpec, DslWriter, FunctionSpec (+42 more)

### Community 69 - "MarketBreakersTest.php"
Cohesion: 0.23
Nodes (8): {closure#4}(), MinuteCandles, BreakerEvent, MarketBreakers, breakerMinute(), {closure#1}(), {closure#2}(), {closure#3}()

### Community 70 - "InstrumentSyncTest.php"
Cohesion: 0.09
Nodes (17): InstrumentDefinition, InstrumentStatus, Delisted, Halted, Trading, {closure#1}(), InstrumentSync, SyncResult (+9 more)

### Community 71 - "Statistics"
Cohesion: 0.27
Nodes (10): Statistics, {closure#3}(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}() (+2 more)

### Community 72 - "App\Execution\Journal\DatabaseDecisionLog"
Cohesion: 0.18
Nodes (5): DecisionLog, DecisionRecord, IntentRecord, {closure#1}(), DatabaseDecisionLog

### Community 73 - "scripts"
Cohesion: 0.15
Nodes (13): scripts, analyse, dev, format, mutate, post-autoload-dump, post-update-cmd, pre-package-uninstall (+5 more)

### Community 74 - "ma-trend-short.strategy.yaml"
Cohesion: 0.07
Nodes (26): entries, short, exits, stop_loss, when, fails_when, family, default (+18 more)

### Community 75 - ".evaluate"
Cohesion: 0.29
Nodes (7): WalkForward, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), wfSet()

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

### Community 81 - ".isPositive"
Cohesion: 0.12
Nodes (20): Cscv, DeflatedSharpe, closeTo(), {closure#1}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}() (+12 more)

### Community 82 - "MonteCarlo"
Cohesion: 0.15
Nodes (11): {closure#4}(), {closure#5}(), MonteCarlo, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}() (+3 more)

### Community 83 - "App\Support\Number\Decimal"
Cohesion: 0.07
Nodes (54): {closure#3}(), {closure#8}(), {closure#3}(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}() (+46 more)

### Community 84 - "pint/composer.json"
Cohesion: 0.20
Nodes (9): config, sort-packages, description, license, name, require, laravel/pint, php (+1 more)

### Community 85 - "HealthChecks"
Cohesion: 0.21
Nodes (4): CheckHealth, {closure#1}(), HealthChecks, HealthResult

### Community 86 - "RunSimulation.php"
Cohesion: 0.08
Nodes (9): ImportMarketData, {closure#3}(), RunSimulation, SimulationJob, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}() (+1 more)

### Community 87 - ".parse"
Cohesion: 0.33
Nodes (6): KlineMessage, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), streamLines()

### Community 88 - "require-dev"
Cohesion: 0.22
Nodes (9): require-dev, fakerphp/faker, larastan/larastan, laravel/pail, laravel/pao, mockery/mockery, nunomaduro/collision, pestphp/pest (+1 more)

### Community 89 - "scripts-descriptions"
Cohesion: 0.25
Nodes (8): scripts-descriptions, analyse, format, mutate, test, test:network, test:testnet, tools:install

### Community 90 - "CommandKind"
Cohesion: 0.16
Nodes (9): {closure#5}(), CommandKind, Flatten, Halt, Pause, Resume, ResumeStrategy, EngineCommand (+1 more)

### Community 91 - ".fromStrings"
Cohesion: 0.09
Nodes (14): {closure#1}(), {closure#2}(), RegimeLabels, DirectionalMovement, {closure#2}(), {closure#42}(), {closure#1}(), {closure#1}() (+6 more)

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

### Community 97 - "jobs"
Cohesion: 0.25
Nodes (8): name, runs-on, name, runs-on, steps, jobs, analyse, audit

### Community 98 - "Node"
Cohesion: 0.05
Nodes (46): Argument, Binary, BoolLiteral, Call, DurationLiteral, Identifier, Lookback, Node (+38 more)

### Community 99 - "psr-4"
Cohesion: 0.40
Nodes (5): autoload, psr-4, App\\, Database\\Factories\\, Database\\Seeders\\

### Community 100 - "require"
Cohesion: 0.22
Nodes (9): require, bacon/bacon-qr-code, laravel/fortify, laravel/framework, laravel/mcp, laravel/tinker, livewire/livewire, php (+1 more)

### Community 102 - "Quantity"
Cohesion: 0.15
Nodes (5): {closure#2}(), Balance, Quantity, {closure#10}(), {closure#9}()

### Community 103 - "StrategyGates.php"
Cohesion: 0.11
Nodes (12): {closure#3}(), {closure#8}(), {closure#2}(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}() (+4 more)

### Community 104 - "uses"
Cohesion: 0.21
Nodes (12): steps, name, runs-on, steps, frontend, lint, name, runs-on (+4 more)

### Community 106 - "devDependencies"
Cohesion: 0.18
Nodes (12): devDependencies, concurrently@^10.0.3, laravel-vite-plugin@^3.1, @playwright/test@1.63.0, tailwindcss@^4.0.0, @tailwindcss/vite@^4.0.0, vite@^8.0.0, concurrently (+4 more)

### Community 108 - "Laravel\Mcp\Response"
Cohesion: 0.06
Nodes (19): ChartTool, CompileStrategy, DslReference, GetCandles, GetFunding, GetIndicators, GetJob, GetStrategy (+11 more)

### Community 117 - "ChartSeriesTest.php"
Cohesion: 0.18
Nodes (11): VolumeProfile, chart(), {closure#10}(), {closure#11}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#6}() (+3 more)

### Community 118 - "BinanceArchive"
Cohesion: 0.05
Nodes (19): ReplayDivergence, ArchiveCatalog, ArchiveMissing, ArchivePeriod, BinanceArchive, ChecksumMismatch, BinanceRequestFailed, ExchangeNumber (+11 more)

### Community 119 - ".breaches"
Cohesion: 0.24
Nodes (10): Band, bandReport(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#7}() (+2 more)

### Community 120 - "buy-and-hold.strategy.yaml"
Cohesion: 0.12
Nodes (16): entries, long, exits, stop_loss, fails_when, family, hypothesis, when (+8 more)

### Community 121 - "PaperEngineTest.php"
Cohesion: 0.23
Nodes (13): JournalEntry, {closure#1}(), {closure#11}(), {closure#13}(), {closure#2}(), {closure#3}(), {closure#5}(), {closure#6}() (+5 more)

### Community 122 - "TokensTest.php"
Cohesion: 0.31
Nodes (6): {closure#3}(), {closure#4}(), contrastRatio(), contrastRules(), relativeLuminance(), themeTokens()

### Community 123 - "ArchiveImportTest.php"
Cohesion: 0.31
Nodes (10): archiveBytes(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}() (+2 more)

### Community 124 - "User"
Cohesion: 0.12
Nodes (3): User, UserFactory, {closure#1}()

### Community 125 - "Illuminate\Support\Facades\Schema"
Cohesion: 0.14
Nodes (4): {closure#1}(), {closure#1}(), {closure#1}(), {closure#2}()

### Community 126 - "StrategyIdentity"
Cohesion: 0.24
Nodes (5): StrategyIdentity, RenamedStrategy, {closure#1}(), {closure#2}(), {closure#3}()

### Community 127 - ".register"
Cohesion: 0.06
Nodes (59): SimClock, {closure#10}(), binanceVenue(), {closure#2}(), {closure#5}(), {closure#7}(), {closure#9}(), {closure#1}() (+51 more)

### Community 128 - "funding-carry.strategy.yaml"
Cohesion: 0.12
Nodes (15): fails_when, family, hypothesis, params, enter_when, exit_when, margin_buffer, max_leverage_perp (+7 more)

### Community 129 - "McpServerTest.php"
Cohesion: 0.26
Nodes (13): actAsToken(), {closure#11}(), {closure#12}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}() (+5 more)

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

### Community 138 - "steps"
Cohesion: 0.33
Nodes (6): name, runs-on, services, steps, e2e, if

### Community 139 - "2026_11_09_000014_create_health_tables.php"
Cohesion: 0.33
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

### Community 140 - ".of"
Cohesion: 0.12
Nodes (25): {closure#8}(), {closure#9}(), {closure#44}(), {closure#45}(), {closure#46}(), {closure#47}(), {closure#48}(), {closure#1}() (+17 more)

### Community 141 - "App\Market\Models\Instrument"
Cohesion: 0.06
Nodes (30): {closure#1}(), {closure#2}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#15}() (+22 more)

### Community 142 - "RiskState"
Cohesion: 0.32
Nodes (5): RiskState, {closure#15}(), {closure#17}(), {closure#18}(), {closure#22}()

### Community 143 - "CandleRepositoryTest.php"
Cohesion: 0.31
Nodes (11): candleStore(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}() (+3 more)

### Community 144 - "Deployment"
Cohesion: 0.18
Nodes (5): {closure#1}(), {closure#2}(), Deployment, {closure#1}(), {closure#1}()

### Community 145 - ".now"
Cohesion: 0.04
Nodes (18): AccountRuntime, LiveGate, OpenAccount, ApproveLive, ConfirmsOperator, CreateAccount, RunEngine, StoreKeys (+10 more)

### Community 146 - "OrderBookTest.php"
Cohesion: 0.42
Nodes (7): OrderBookOutOfSync, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), levels(), update()

### Community 147 - "scripts"
Cohesion: 0.50
Nodes (4): scripts, build, dev, e2e

### Community 152 - "bootstrap/app.php"
Cohesion: 0.32
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

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

### Community 174 - "EngineMode"
Cohesion: 0.40
Nodes (5): EngineMode, Backtest, Live, Paper, Testnet

### Community 175 - ".evaluate"
Cohesion: 0.24
Nodes (9): Gates, brokenGate(), {closure#1}(), {closure#3}(), {closure#4}(), {closure#6}(), {closure#7}(), gate() (+1 more)

### Community 176 - "Venue"
Cohesion: 0.07
Nodes (9): Venue, WarmingVenue, ForwardingVenue, {closure#12}(), {closure#22}(), {closure#32}(), {closure#34}(), {closure#49}() (+1 more)

### Community 178 - "UniverseStrategyTest.php"
Cohesion: 0.14
Nodes (18): CrossSectionalStrategy, UniverseSchedule, {closure#10}(), {closure#12}(), {closure#15}(), {closure#17}(), {closure#18}(), {closure#3}() (+10 more)

### Community 181 - "SettingsScreenTest.php"
Cohesion: 0.18
Nodes (13): {closure#10}(), {closure#11}(), {closure#13}(), {closure#14}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}() (+5 more)

### Community 183 - "App\Strategy\Dsl\DslError"
Cohesion: 0.21
Nodes (13): {closure#2}(), {closure#1}(), {closure#1}(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}() (+5 more)

### Community 189 - "MarketType"
Cohesion: 0.10
Nodes (16): BinanceEndpoints, BinanceRestClient, {closure#3}(), {closure#4}(), EpochTime, Json, Sleeper, MarketType (+8 more)

### Community 190 - "PolicyLimits"
Cohesion: 0.08
Nodes (19): {closure#1}(), {closure#3}(), InstrumentDirectory, StoredInstruments, CompileStrategy, CheckResult, PolicyLimits, Position (+11 more)

### Community 192 - "0001_01_01_000002_create_jobs_table.php"
Cohesion: 0.33
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

### Community 195 - "2026_11_09_000016_create_market_universe_tables.php"
Cohesion: 0.40
Nodes (3): {closure#1}(), {closure#2}(), up()

### Community 196 - ".number"
Cohesion: 0.29
Nodes (4): {closure#4}(), {closure#45}(), {closure#46}(), {closure#70}()

### Community 198 - "start-local.ps1"
Cohesion: 0.27
Nodes (4): Stop-Postgres(), Stop-ProcessTree(), Test-PostgresRunning(), Write-Step()

### Community 200 - "Symbol"
Cohesion: 0.04
Nodes (36): ClientOrderId, Income, IncomeKind, Funding, Liquidation, OcoRequest, OrderRequest, OrderType (+28 more)

### Community 202 - "0001_01_01_000000_create_users_table.php"
Cohesion: 0.33
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

### Community 203 - "depthStreamWith"
Cohesion: 0.25
Nodes (5): {closure#1}(), {closure#2}(), depthMessage(), depthStreamWith(), FakeTransport

### Community 205 - "InstrumentRules"
Cohesion: 0.24
Nodes (12): InstrumentRules, {closure#1}(), RulesHistory, {closure#2}(), {closure#4}(), {closure#41}(), {closure#1}(), {closure#2}() (+4 more)

### Community 208 - "Engine.php"
Cohesion: 0.08
Nodes (12): {closure#1}(), {closure#11}(), {closure#12}(), {closure#2}(), {closure#3}(), {closure#9}(), RiskEvent, RiskEventKind (+4 more)

### Community 209 - "RiskPolicy"
Cohesion: 0.13
Nodes (12): RiskProfile, Account, Benchmark, RiskPolicy, {closure#2}(), {closure#1}(), {closure#2}(), {closure#3}() (+4 more)

### Community 213 - "CrossSection"
Cohesion: 0.15
Nodes (11): {closure#42}(), {closure#56}(), {closure#57}(), {closure#58}(), {closure#59}(), {closure#60}(), {closure#62}(), {closure#65}() (+3 more)

### Community 214 - "App"
Cohesion: 0.06
Nodes (50): SendTelegram, App, {closure#1}(), {closure#2}(), {closure#3}(), {closure#2}(), {closure#3}(), {closure#4}() (+42 more)

### Community 215 - "AppServiceProvider.php"
Cohesion: 0.14
Nodes (7): SystemSleeper, LocalDiskUsage, {closure#1}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}()

## Knowledge Gaps
- **152 isolated node(s):** `Halted`, `PositionOpen`, `DuplicateIntent`, `NoPosition`, `NoStop` (+147 more)
  These have ≤1 connection - possible missing edges. (Counts symbols only; 994 node(s) total have ≤1 connection when file, concept and rationale nodes are included.)
- **66 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `Decimal` connect `Decimal` to `engineHarness`, `.encode`, `.of`, `Engine`, `Percent`, `DocumentChecker`, `Strategy`, `SimulatedVenueTest.php`, `BinanceVenueTest.php`, `.of`, `Candle`, `App\Market\Models\Instrument`, `.act`, `App\Support\Symbol`, `RiskState`, `.of`, `Instrument`, `Strategy`, `Side`, `App\Strategy\Dsl\Compiler\CompiledStrategy`, `Symbol`, `RejectionReason`, `EnginePerpTest.php`, `OrderManagerTest.php`, `InstrumentRules`, `.times`, `RoundTrip`, `Timeframe`, `.evaluate`, `Venue`, `FundingCarry`, `App\Strategy\Contracts\Strategy`, `IndicatorsTest.php`, `IndicatorReferenceTest.php`, `PolicyLimits`, `DecimalMath`, `.run`, `StrategySpec`, `Kind`, `Statistics`, `Symbol`, `.evaluate`, `.measure`, `InstrumentRules`, `App\Strategy\Indicators\BarIndicator`, `Engine.php`, `RiskPolicy`, `.isPositive`, `MonteCarlo`, `App\Support\Number\Decimal`, `.fromStrings`, `Node`, `Quantity`, `StrategyGates.php`, `.handle`, `ChartSeriesTest.php`?**
  _High betweenness centrality (0.111) - this node is a cross-community bridge._
- **What connects `Halted`, `PositionOpen`, `DuplicateIntent` to the rest of the system?**
  _152 weakly-connected nodes found - possible missing edges._
- **Should `engineHarness` be split into smaller, more focused modules?**
  _Cohesion score 0.11753246753246753 - nodes in this community are weakly interconnected._
- **Why does `Symbol` connect `Symbol` to `engineHarness`, `.encode`, `.of`, `Strategy`, `DocumentChecker`, `SimulatedVenueTest.php`, `BinanceVenueTest.php`, `.of`, `ExchangeVerificationTest.php`, `.act`, `App\Support\Symbol`, `CandleRepositoryTest.php`, `.now`, `Instrument`, `Strategy`, `Side`, `Symbol`, `RejectionReason`, `EngineUniverseTest.php`, `App\Market\Instruments\Universe`, `App\Strategy\Data\StrategyContext`, `EnginePerpTest.php`, `OrderManagerTest.php`, `InstrumentRules`, `Decimal`, `.times`, `RoundTrip`, `Timeframe`, `Venue`, `FundingCarry`, `PolicyLimits`, `.run`, `App\Execution\Journal\DatabaseDecisionLog`, `.measure`, `InstrumentRules`, `Engine.php`, `Quantity`, `StrategyGates.php`, `.handle`, `Laravel\Mcp\Response`, `ChartSeriesTest.php`, `.register`?**
  _High betweenness centrality (0.030) - this node is a cross-community bridge._
- **Should `Strategy` be split into smaller, more focused modules?**
  _Cohesion score 0.11517165005537099 - nodes in this community are weakly interconnected._
- **Why does `Candle` connect `Candle` to `engineHarness`, `Strategy`, `SimulatedVenueTest.php`, `ExchangeVerificationTest.php`, `CandleRepositoryTest.php`, `Instrument`, `EnginePerpTest.php`, `OrderManagerTest.php`, `InstrumentRules`, `Decimal`, `Timeframe`, `Carbon\CarbonImmutable`, `IndicatorsTest.php`, `IndicatorReferenceTest.php`, `MarketType`, `App\Support\Clock\Clock`, `.run`, `MarketBreakersTest.php`, `Symbol`, `App\Strategy\Indicators\BarIndicator`, `.parse`, `.fromStrings`, `StrategyGates.php`, `ChartSeriesTest.php`?**
  _High betweenness centrality (0.021) - this node is a cross-community bridge._
- **Should `CompiledStrategy.php` be split into smaller, more focused modules?**
  _Cohesion score 0.07246376811594203 - nodes in this community are weakly interconnected._