# Graph Report - trading-platform  (2026-11-10)

## Corpus Check
- 652 files · ~278,234 words
- Verdict: corpus is large enough that graph structure adds value.
- Unclassified: 103 file(s) not represented in the graph (top: .zip 38, .CHECKSUM 38, (none) 19)

## Summary
- 5309 nodes · 18467 edges · 220 communities (155 shown, 65 thin omitted)
- Extraction: 90% EXTRACTED · 10% INFERRED · 0% AMBIGUOUS · INFERRED: 1756 edges (avg confidence: 0.85)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `8b042091`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- SimulatedPerpVenueTest.php
- engineHarness
- .encode
- ExitReason
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
- Illuminate\Contracts\Config\Repository
- Strategy
- Clock
- Instrument
- ScreensTest.php
- Ledger
- Illuminate\Database\Schema\Blueprint
- generate.py
- App\Strategy\Dsl\Compiler\CompiledStrategy
- Symbol
- .times
- TerminalQrCodeTest.php
- .of
- EngineUniverseTest.php
- .handle
- RuntimeException
- oi-momentum-long.strategy.yaml
- App\Strategy\Data\StrategyContext
- EnginePerpTest.php
- TradeFlowBuilder
- healthyMachine
- ScalpingCollectorTest.php
- ladderSetup
- OrderManagerTest.php
- InstrumentRules
- App\Strategy\Dsl\Document\DocumentChecker
- ema-cross-scalp-long.strategy.yaml
- Decimal
- .times
- App
- Timeframe
- Illuminate\Database\Eloquent\Model
- package.json
- OrderBook
- BinanceRestClientTest.php
- ema-cross-scalp-short.strategy.yaml
- ChartScreen.php
- ReadBack
- Illuminate\Contracts\Bus\Dispatcher
- postgres
- UniverseStrategy
- SizedStrategyTest.php
- donchian-breakout-short.strategy.yaml
- IndicatorsTest.php
- support.mjs
- RunBacktestCommandTest.php
- IndicatorReferenceTest.php
- App\Support\Clock\Clock
- Carbon\CarbonImmutable
- Illuminate\Http\Request
- donchian-breakout.strategy.yaml
- .run
- DocumentCheckerTest.php
- Kind
- MarketBreakersTest.php
- ArchiveCatalog
- DailyReport
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
- App\Strategy\Contracts\Strategy
- RunSimulation.php
- .parse
- require-dev
- scripts-descriptions
- OrderAck
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
- Carbon\CarbonImmutable
- .breaches
- buy-and-hold.strategy.yaml
- InvalidArgumentException
- TokensTest.php
- ArchiveImportTest.php
- User
- Illuminate\Support\Facades\Schema
- App\Strategy\Data\StrategyIdentity
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
- App\Support\Symbol
- GridWorkersTest.php
- CandleRepositoryTest.php
- App\Strategy\Contracts\CrossSectionalStrategy
- .now
- OrderBookTest.php
- scripts
- 2026_11_08_000007_create_strategy_gate_tables.php
- fake-telegram.mjs
- 2026_11_08_000003_create_strategy_trials_table.php
- Window
- 2026_11_08_000004_create_strategy_versions_tables.php
- 2026_11_08_000006_create_mcp_tables.php
- 2026_11_09_000017_create_live_tables.php
- CompiledStrategyTest.php
- strategies-screen.blade.php
- extra
- app.blade.php
- halt
- remove
- account-screen.blade.php
- Illuminate\Database\Migrations\Migration
- Marks
- .evaluate
- SimulatedVenue
- 2026_11_09_000027_add_to_to_market_jobs.php
- UniverseStrategyTest.php
- 2026_11_09_000012_create_account_results_tables.php
- 2026_11_09_000023_create_universe_members_table.php
- SettingsScreenTest.php
- FundingCarry
- App\Strategy\Dsl\DslError
- ParserTest.php
- ResearchUniverse.php
- MarketType
- PolicyLimits
- 2026_11_09_000025_create_book_depth_table.php
- 0001_01_01_000002_create_jobs_table.php
- 2026_11_09_000009_create_exchange_keys_table.php
- 2026_11_09_000018_add_effective_at_to_deployments.php
- 2026_11_09_000016_create_market_universe_tables.php
- .number
- VerificationReport
- start-local.ps1
- Tokens
- Symbol
- GlobalsTest.php
- 0001_01_01_000000_create_users_table.php
- CandleStreamTest.php
- LadderStatus
- InstrumentRules
- StrategyVersion
- Engine.php
- RiskConfigTest.php
- SimulationJobsTest.php
- 2026_11_09_000020_add_lifetime_to_instruments.php
- CrossSection
- App
- AppServiceProvider.php
- 2026_11_09_000026_create_liquidation_tables.php
- 2026_11_09_000022_create_daily_stats_table.php
- 2026_11_09_000010_add_two_factor_columns_to_users_table.php
- 2026_11_09_000024_create_positioning_table.php

## God Nodes (most connected - your core abstractions)
1. `Decimal` - 492 edges
2. `Side` - 203 edges
3. `Symbol` - 187 edges
4. `App` - 168 edges
5. `Candle` - 134 edges
6. `Timeframe` - 109 edges
7. `Price` - 100 edges
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

## Communities (220 total, 65 thin omitted)

### Community 0 - "SimulatedPerpVenueTest.php"
Cohesion: 0.18
Nodes (27): PriceMinute, {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#15}(), {closure#18}() (+19 more)

### Community 1 - "engineHarness"
Cohesion: 0.13
Nodes (35): EngineCommand, ScriptedStrategy, {closure#10}(), {closure#13}(), {closure#14}(), {closure#16}(), {closure#17}(), {closure#2}() (+27 more)

### Community 2 - ".encode"
Cohesion: 0.30
Nodes (6): CanonicalJson, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}()

### Community 3 - "ExitReason"
Cohesion: 0.16
Nodes (27): ExitReason, Flatten, Liquidation, Signal, Stop, Target, {closure#1}(), {closure#10}() (+19 more)

### Community 4 - "Engine"
Cohesion: 0.13
Nodes (4): EngineRecord, Engine, {closure#2}(), {closure#1}()

### Community 5 - "Strategy"
Cohesion: 0.11
Nodes (36): {closure#17}(), {closure#5}(), carryDecisions(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}() (+28 more)

### Community 6 - "DocumentChecker"
Cohesion: 0.21
Nodes (4): {closure#2}(), {closure#5}(), {closure#8}(), DocumentChecker

### Community 7 - ".now"
Cohesion: 0.21
Nodes (5): {closure#1}(), {closure#2}(), {closure#1}(), ReviewLadder, Ladder

### Community 8 - "CompiledStrategy.php"
Cohesion: 0.07
Nodes (8): PerpetualTracker, CompiledStrategy, {closure#47}(), {closure#54}(), Compiler, {closure#1}(), Program, Rankings

### Community 9 - "SimulatedVenueTest.php"
Cohesion: 0.17
Nodes (42): btc(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#15}(), {closure#16}() (+34 more)

### Community 10 - "BinanceVenueTest.php"
Cohesion: 0.12
Nodes (14): {closure#2}(), BinanceVenue, BinanceApiError, BinanceUnreachable, SignedBinanceClient, binanceVenue(), {closure#1}(), {closure#4}() (+6 more)

### Community 11 - ".of"
Cohesion: 0.12
Nodes (18): AssetMismatch, Money, {closure#2}(), {closure#1}(), usdt(), {closure#6}(), {closure#1}(), {closure#11}() (+10 more)

### Community 12 - "Candle"
Cohesion: 0.12
Nodes (31): {closure#1}(), {closure#3}(), {closure#4}(), Candle, InvalidCandle, {closure#1}(), {closure#2}(), candleWith() (+23 more)

### Community 13 - "ExchangeVerificationTest.php"
Cohesion: 0.10
Nodes (32): VerificationSource, DailyArchive, Rest, {closure#1}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#2}() (+24 more)

### Community 15 - "Illuminate\Contracts\Config\Repository"
Cohesion: 0.12
Nodes (6): {closure#1}(), {closure#2}(), ExportStrategies, ImportStrategies, PreparesBacktests, PromoteStrategy

### Community 16 - "Strategy"
Cohesion: 0.19
Nodes (18): Strategy, baselineDocument(), baselinePairs(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#14}() (+10 more)

### Community 17 - "Clock"
Cohesion: 0.09
Nodes (13): AuthenticateMcpToken, FlattenEngine, HaltEngine, IssuesCommands, PauseStrategy, ResumeEngine, CommandKind, Flatten (+5 more)

### Community 18 - "Instrument"
Cohesion: 0.04
Nodes (18): BackfillStep, CandleRepository, CheckMarketData, SelectsInstruments, {closure#2}(), CandleSource, Archive, Rest (+10 more)

### Community 19 - "ScreensTest.php"
Cohesion: 0.14
Nodes (20): {closure#1}(), {closure#1}(), chartLiveQuery(), {closure#10}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#16}() (+12 more)

### Community 20 - "Ledger"
Cohesion: 0.19
Nodes (28): Ledger, balanceOf(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#15}() (+20 more)

### Community 21 - "Illuminate\Database\Schema\Blueprint"
Cohesion: 0.14
Nodes (13): {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#1}(), {closure#2}(), {closure#3}() (+5 more)

### Community 22 - "generate.py"
Cohesion: 0.07
Nodes (30): atr(), bars(), bb(), change(), dmi(), donchian(), ema(), highest() (+22 more)

### Community 23 - "App\Strategy\Dsl\Compiler\CompiledStrategy"
Cohesion: 0.09
Nodes (15): {closure#1}(), {closure#2}(), {closure#3}(), {closure#6}(), {closure#7}(), {closure#8}(), {closure#9}(), EntrySpec (+7 more)

### Community 24 - "Symbol"
Cohesion: 0.05
Nodes (24): BuyAndHold, DonchianBreakout, MovingAverageTrend, BuildableStrategy, Close, EntryOrder, Intent, OpenLong (+16 more)

### Community 25 - ".times"
Cohesion: 0.08
Nodes (7): Fill, PerpPosition, Position, InvalidNumber, Perpetual, {closure#5}(), {closure#5}()

### Community 26 - "TerminalQrCodeTest.php"
Cohesion: 0.21
Nodes (7): TerminalQrCode, {closure#3}(), {closure#4}(), {closure#5}(), expectedModules(), readColoured(), readPlain()

### Community 27 - ".of"
Cohesion: 0.05
Nodes (83): AccountView, RejectionReason, BelowMinimum, Breaker, Cooldown, DuplicateIntent, EntryWorking, GrossExposure (+75 more)

### Community 28 - "EngineUniverseTest.php"
Cohesion: 0.30
Nodes (13): {closure#1}(), {closure#11}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}(), {closure#8}(), {closure#9}() (+5 more)

### Community 30 - "RuntimeException"
Cohesion: 0.11
Nodes (6): ReplayDivergence, ReconciliationMismatch, OrderBookOutOfSync, PhrityTransport, StreamFailed, LedgerViolation

### Community 31 - "oi-momentum-long.strategy.yaml"
Cohesion: 0.07
Nodes (27): entries, long, exits, stop_loss, take_profit, when, fails_when, family (+19 more)

### Community 32 - "App\Strategy\Data\StrategyContext"
Cohesion: 0.16
Nodes (7): {closure#2}(), {closure#3}(), {closure#1}(), {closure#10}(), {closure#3}(), {closure#11}(), {closure#2}()

### Community 33 - "EnginePerpTest.php"
Cohesion: 0.13
Nodes (23): EngineMode, Backtest, Live, Paper, Testnet, InMemoryDecisionLog, {closure#1}(), {closure#2}() (+15 more)

### Community 35 - "healthyMachine"
Cohesion: 0.39
Nodes (8): {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), healthAt(), healthyMachine(), telegramSent()

### Community 36 - "ScalpingCollectorTest.php"
Cohesion: 0.13
Nodes (16): SimClock, WallClock, {closure#1}(), {closure#1}(), {closure#1}(), {closure#1}(), {closure#1}(), forceOrder() (+8 more)

### Community 37 - "ladderSetup"
Cohesion: 0.50
Nodes (9): at(), {closure#1}(), {closure#10}(), {closure#3}(), {closure#4}(), {closure#6}(), {closure#8}(), ladderSetup() (+1 more)

### Community 38 - "OrderManagerTest.php"
Cohesion: 0.10
Nodes (31): OrderRole, Entry, Exit, Flatten, Stop, Target, TrackedOrder, {closure#1}() (+23 more)

### Community 39 - "InstrumentRules"
Cohesion: 0.08
Nodes (27): FeeSchedule, CostModel, CandleFeed, FundingRate, RiskProfile, Account, Benchmark, RiskPolicy (+19 more)

### Community 40 - "App\Strategy\Dsl\Document\DocumentChecker"
Cohesion: 0.06
Nodes (23): {closure#1}(), Argument, BoolLiteral, Call, DurationLiteral, Identifier, NumberLiteral, Parameter (+15 more)

### Community 41 - "ema-cross-scalp-long.strategy.yaml"
Cohesion: 0.06
Nodes (33): entries, long, exits, stop_loss, take_profit, time_stop, when, fails_when (+25 more)

### Community 42 - "Decimal"
Cohesion: 0.04
Nodes (52): {closure#6}(), RoundTrip, {closure#1}(), {closure#2}(), {closure#3}(), {closure#10}(), {closure#5}(), {closure#4}() (+44 more)

### Community 43 - ".times"
Cohesion: 0.09
Nodes (18): Income, Liquidity, Maker, Taker, OrderStatus, Cancelled, Expired, Filled (+10 more)

### Community 44 - "App"
Cohesion: 0.12
Nodes (20): SendTelegram, {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#15}() (+12 more)

### Community 45 - "Timeframe"
Cohesion: 0.08
Nodes (33): CandleAggregator, CandleSequenceBroken, {closure#4}(), {closure#8}(), Vwap, Timeframe, FifteenMinutes, FiveMinutes (+25 more)

### Community 46 - "Illuminate\Database\Eloquent\Model"
Cohesion: 0.03
Nodes (55): McpCall, {closure#5}(), Account, AccountEquity, AccountSnapshot, AccountTrade, PlainDecimal, Deployment (+47 more)

### Community 47 - "package.json"
Cohesion: 0.15
Nodes (17): dependencies, codemirror@^6.0.2, @codemirror/lang-yaml@^6.1.3, @codemirror/language@^6.13.1, @lezer/highlight@^1.2.5, lightweight-charts@^5.2.1, optionalDependencies, @laravel/multiplex@^0.4.1 (+9 more)

### Community 49 - "BinanceRestClientTest.php"
Cohesion: 0.13
Nodes (18): BinanceRequestFailed, LocationRestricted, RateLimited, binanceClient(), {closure#1}(), {closure#11}(), {closure#13}(), {closure#15}() (+10 more)

### Community 50 - "ema-cross-scalp-short.strategy.yaml"
Cohesion: 0.06
Nodes (33): entries, short, exits, stop_loss, take_profit, time_stop, when, fails_when (+25 more)

### Community 51 - "ChartScreen.php"
Cohesion: 0.06
Nodes (27): {closure#2}(), ChartScreen, FunctionSpec, Group, Exit, Indicator, Order, Perpetual (+19 more)

### Community 53 - "Illuminate\Contracts\Bus\Dispatcher"
Cohesion: 0.22
Nodes (3): {closure#4}(), Notifier, TelegramNotifier

### Community 54 - "postgres"
Cohesion: 0.10
Nodes (19): POSTGRES_DB, POSTGRES_PASSWORD, POSTGRES_USER, interval, retries, test, timeout, environment (+11 more)

### Community 55 - "UniverseStrategy"
Cohesion: 0.17
Nodes (3): UniverseSchedule, UniverseStrategy, {closure#17}()

### Community 56 - "SizedStrategyTest.php"
Cohesion: 0.24
Nodes (9): {closure#1}(), {closure#2}(), __construct(), enterOnce(), identity(), limits(), onCandle(), subscriptions() (+1 more)

### Community 57 - "donchian-breakout-short.strategy.yaml"
Cohesion: 0.07
Nodes (26): entries, short, default, range, step, default, range, step (+18 more)

### Community 58 - "IndicatorsTest.php"
Cohesion: 0.08
Nodes (23): {closure#48}(), Atr, Donchian, Ema, Period, Sma, ValueIndicator, bar() (+15 more)

### Community 59 - "support.mjs"
Cohesion: 0.26
Nodes (9): @playwright/test, confirmed(), checkLayout(), EMAIL, freshCode(), PASSWORD, reauthenticate(), totp() (+1 more)

### Community 60 - "RunBacktestCommandTest.php"
Cohesion: 0.13
Nodes (34): {closure#1}(), {closure#2}(), {closure#4}(), {closure#5}(), scalpingBackfill(), {closure#4}(), {closure#1}(), {closure#10}() (+26 more)

### Community 61 - "IndicatorReferenceTest.php"
Cohesion: 0.05
Nodes (22): BarIndicator, Bollinger, DonchianChannel, Hma, Keltner, Macd, Natr, RealizedVolatility (+14 more)

### Community 62 - "App\Support\Clock\Clock"
Cohesion: 0.04
Nodes (19): Backfill, BinanceEndpoints, Sleeper, {closure#1}(), StreamCandles, {closure#2}(), DepthStream, FormingCandles (+11 more)

### Community 63 - "Carbon\CarbonImmutable"
Cohesion: 0.09
Nodes (27): {closure#2}(), {closure#2}(), DatabaseCandleFeed, {closure#1}(), Rows, {closure#1}(), {closure#2}(), {closure#3}() (+19 more)

### Community 64 - "Illuminate\Http\Request"
Cohesion: 0.12
Nodes (7): ChartLiveController, {closure#8}(), {closure#3}(), {closure#4}(), {closure#1}(), {closure#2}(), {closure#3}()

### Community 65 - "donchian-breakout.strategy.yaml"
Cohesion: 0.07
Nodes (26): entries, long, default, range, step, default, range, step (+18 more)

### Community 66 - ".run"
Cohesion: 0.10
Nodes (28): PerformanceMetrics, Report, btcMinutes(), {closure#1}(), {closure#2}(), {closure#3}(), referencePrice(), {closure#13}() (+20 more)

### Community 67 - "DocumentCheckerTest.php"
Cohesion: 0.18
Nodes (15): UniverseRule, checkDocument(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#2}() (+7 more)

### Community 68 - "Kind"
Cohesion: 0.07
Nodes (39): {closure#2}(), {closure#3}(), {closure#3}(), {closure#4}(), {closure#7}(), {closure#9}(), ParameterSpec, DslWriter (+31 more)

### Community 69 - "MarketBreakersTest.php"
Cohesion: 0.23
Nodes (8): {closure#4}(), MinuteCandles, BreakerEvent, MarketBreakers, breakerMinute(), {closure#1}(), {closure#2}(), {closure#3}()

### Community 70 - "ArchiveCatalog"
Cohesion: 0.08
Nodes (10): ArchiveCatalog, FillDailyStats, {closure#1}(), SyncInstruments, DailyStats, DailyHistory, {closure#1}(), InstrumentSync (+2 more)

### Community 72 - "App\Execution\Journal\DatabaseDecisionLog"
Cohesion: 0.21
Nodes (4): DecisionLog, IntentRecord, {closure#1}(), DatabaseDecisionLog

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
Cohesion: 0.11
Nodes (18): EquityTracker, Performance, Statistics, {closure#3}(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#5}() (+10 more)

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
Nodes (19): Cscv, DeflatedSharpe, {closure#7}(), closeTo(), {closure#1}(), {closure#11}(), {closure#12}(), {closure#13}() (+11 more)

### Community 82 - "MonteCarlo"
Cohesion: 0.15
Nodes (11): {closure#4}(), {closure#5}(), MonteCarlo, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}() (+3 more)

### Community 83 - "App\Support\Number\Decimal"
Cohesion: 0.07
Nodes (54): {closure#3}(), {closure#3}(), {closure#5}(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}() (+46 more)

### Community 84 - "pint/composer.json"
Cohesion: 0.20
Nodes (9): config, sort-packages, description, license, name, require, laravel/pint, php (+1 more)

### Community 85 - "App\Strategy\Contracts\Strategy"
Cohesion: 0.26
Nodes (3): SizedStrategy, {closure#1}(), LimitedStrategy

### Community 87 - ".parse"
Cohesion: 0.33
Nodes (6): KlineMessage, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), streamLines()

### Community 88 - "require-dev"
Cohesion: 0.22
Nodes (9): require-dev, fakerphp/faker, larastan/larastan, laravel/pail, laravel/pao, mockery/mockery, nunomaduro/collision, pestphp/pest (+1 more)

### Community 89 - "scripts-descriptions"
Cohesion: 0.25
Nodes (8): scripts-descriptions, analyse, format, mutate, test, test:network, test:testnet, tools:install

### Community 91 - ".fromStrings"
Cohesion: 0.11
Nodes (10): {closure#1}(), {closure#2}(), RegimeLabels, DirectionalMovement, {closure#1}(), {closure#2}(), {closure#4}(), {closure#7}() (+2 more)

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
Cohesion: 0.08
Nodes (33): Binary, Lookback, Node, OnTimeframe, Unary, Lexer, {closure#1}(), {closure#2}() (+25 more)

### Community 99 - "psr-4"
Cohesion: 0.40
Nodes (5): autoload, psr-4, App\\, Database\\Factories\\, Database\\Seeders\\

### Community 100 - "require"
Cohesion: 0.22
Nodes (9): require, bacon/bacon-qr-code, laravel/fortify, laravel/framework, laravel/mcp, laravel/tinker, livewire/livewire, php (+1 more)

### Community 102 - "Quantity"
Cohesion: 0.24
Nodes (3): Quantity, {closure#10}(), {closure#9}()

### Community 103 - "StrategyGates.php"
Cohesion: 0.06
Nodes (20): {closure#11}(), {closure#12}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#6}(), {closure#8}(), {closure#2}() (+12 more)

### Community 104 - "uses"
Cohesion: 0.21
Nodes (12): steps, name, runs-on, steps, frontend, lint, name, runs-on (+4 more)

### Community 105 - ".handle"
Cohesion: 0.14
Nodes (4): GridBacktest, ReadsGridOptions, StrategyGates, StrategySpec

### Community 106 - "devDependencies"
Cohesion: 0.18
Nodes (12): devDependencies, concurrently@^10.0.3, laravel-vite-plugin@^3.1, @playwright/test@1.63.0, tailwindcss@^4.0.0, @tailwindcss/vite@^4.0.0, vite@^8.0.0, concurrently (+4 more)

### Community 108 - "Laravel\Mcp\Response"
Cohesion: 0.06
Nodes (20): ChartTool, DslReference, GetCandles, GetFunding, GetIndicators, GetJob, GetStrategy, ListInstruments (+12 more)

### Community 117 - "ChartSeriesTest.php"
Cohesion: 0.17
Nodes (13): VolumeProfile, chart(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#2}(), {closure#3}(), {closure#4}() (+5 more)

### Community 118 - "Carbon\CarbonImmutable"
Cohesion: 0.03
Nodes (64): {closure#4}(), ArchiveMissing, ArchivePeriod, BinanceArchive, {closure#1}(), {closure#2}(), ChecksumMismatch, {closure#1}() (+56 more)

### Community 119 - ".breaches"
Cohesion: 0.24
Nodes (10): Band, bandReport(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#7}() (+2 more)

### Community 120 - "buy-and-hold.strategy.yaml"
Cohesion: 0.12
Nodes (16): entries, long, exits, stop_loss, fails_when, family, hypothesis, when (+8 more)

### Community 121 - "InvalidArgumentException"
Cohesion: 0.14
Nodes (7): {closure#1}(), {closure#2}(), {closure#3}(), {closure#5}(), {closure#7}(), {closure#8}(), {closure#1}()

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

### Community 127 - ".register"
Cohesion: 0.04
Nodes (55): {closure#3}(), {closure#1}(), {closure#6}(), {closure#7}(), {closure#1}(), {closure#3}(), {closure#10}(), {closure#12}() (+47 more)

### Community 128 - "funding-carry.strategy.yaml"
Cohesion: 0.12
Nodes (15): fails_when, family, hypothesis, params, enter_when, exit_when, margin_buffer, max_leverage_perp (+7 more)

### Community 129 - "McpServerTest.php"
Cohesion: 0.25
Nodes (14): actAsToken(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}() (+6 more)

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

### Community 137 - "StrategyLibrary.php"
Cohesion: 0.20
Nodes (3): CompileStrategy, StrategiesScreen, StrategyLibrary

### Community 138 - "steps"
Cohesion: 0.33
Nodes (6): name, runs-on, services, steps, e2e, if

### Community 139 - "2026_11_09_000014_create_health_tables.php"
Cohesion: 0.33
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

### Community 140 - ".of"
Cohesion: 0.07
Nodes (42): {closure#5}(), DecimalMath, {closure#2}(), {closure#8}(), {closure#9}(), {closure#13}(), {closure#44}(), {closure#45}() (+34 more)

### Community 141 - "App\Support\Symbol"
Cohesion: 0.05
Nodes (25): {closure#10}(), {closure#12}(), {closure#2}(), {closure#4}(), {closure#6}(), {closure#8}(), {closure#1}(), UniverseEntry (+17 more)

### Community 143 - "CandleRepositoryTest.php"
Cohesion: 0.31
Nodes (11): candleStore(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}() (+3 more)

### Community 145 - ".now"
Cohesion: 0.03
Nodes (23): AccountRuntime, LiveGate, OpenAccount, ApproveLive, ConfirmsOperator, CreateAccount, RunEngine, StoreKeys (+15 more)

### Community 146 - "OrderBookTest.php"
Cohesion: 0.38
Nodes (7): DepthUpdate, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), levels(), update()

### Community 147 - "scripts"
Cohesion: 0.50
Nodes (4): scripts, build, dev, e2e

### Community 152 - "Window"
Cohesion: 0.07
Nodes (8): Change, Highest, Lowest, Roc, StandardDeviation, Stochastic, Vwma, Window

### Community 155 - "2026_11_09_000017_create_live_tables.php"
Cohesion: 0.33
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

### Community 156 - "CompiledStrategyTest.php"
Cohesion: 0.11
Nodes (19): {closure#3}(), {closure#3}(), {closure#1}(), {closure#42}(), aggregatedBars(), {closure#1}(), {closure#11}(), {closure#12}() (+11 more)

### Community 157 - "strategies-screen.blade.php"
Cohesion: 0.33
Nodes (5): open, promote, promoteDeployment, run, save

### Community 159 - "extra"
Cohesion: 0.67
Nodes (3): extra, laravel, dont-discover

### Community 173 - "Illuminate\Database\Migrations\Migration"
Cohesion: 0.12
Nodes (4): {closure#1}(), {closure#2}(), {closure#1}(), {closure#1}()

### Community 174 - "Marks"
Cohesion: 0.42
Nodes (3): Marks, {closure#25}(), {closure#9}()

### Community 175 - ".evaluate"
Cohesion: 0.24
Nodes (9): Gates, brokenGate(), {closure#1}(), {closure#3}(), {closure#4}(), {closure#6}(), {closure#7}(), gate() (+1 more)

### Community 176 - "SimulatedVenue"
Cohesion: 0.09
Nodes (10): SimulatedVenue, ForwardingVenue, {closure#12}(), balances(), {closure#22}(), {closure#32}(), {closure#34}(), {closure#49}() (+2 more)

### Community 178 - "UniverseStrategyTest.php"
Cohesion: 0.23
Nodes (18): CrossSectionalStrategy, {closure#10}(), {closure#12}(), {closure#15}(), {closure#16}(), {closure#18}(), {closure#3}(), {closure#4}() (+10 more)

### Community 181 - "SettingsScreenTest.php"
Cohesion: 0.24
Nodes (9): {closure#10}(), {closure#11}(), {closure#14}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), confirmed() (+1 more)

### Community 183 - "App\Strategy\Dsl\DslError"
Cohesion: 0.16
Nodes (14): {closure#2}(), {closure#1}(), KnownInstruments, {closure#1}(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}() (+6 more)

### Community 184 - "ParserTest.php"
Cohesion: 0.32
Nodes (6): AstPrinter, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), sexpr()

### Community 189 - "MarketType"
Cohesion: 0.08
Nodes (25): BinanceRestClient, {closure#3}(), {closure#4}(), EpochTime, Json, RequestFailed, InstrumentDefinition, InstrumentStatus (+17 more)

### Community 190 - "PolicyLimits"
Cohesion: 0.11
Nodes (14): InstrumentDirectory, StoredInstruments, {closure#1}(), CompileStrategy, GridPart, PolicyLimits, Position, StrategyRepository (+6 more)

### Community 192 - "0001_01_01_000002_create_jobs_table.php"
Cohesion: 0.33
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

### Community 195 - "2026_11_09_000016_create_market_universe_tables.php"
Cohesion: 0.40
Nodes (3): {closure#1}(), {closure#2}(), up()

### Community 196 - ".number"
Cohesion: 0.33
Nodes (4): {closure#4}(), {closure#45}(), {closure#46}(), {closure#70}()

### Community 198 - "start-local.ps1"
Cohesion: 0.27
Nodes (4): Stop-Postgres(), Stop-ProcessTree(), Test-PostgresRunning(), Write-Step()

### Community 199 - "Tokens"
Cohesion: 0.17
Nodes (4): CreateMcpToken, RevokeMcpToken, McpToken, Tokens

### Community 200 - "Symbol"
Cohesion: 0.06
Nodes (26): Venue, ClientOrderId, OcoRequest, OrderRequest, OrderType, Limit, Market, StopMarket (+18 more)

### Community 202 - "0001_01_01_000000_create_users_table.php"
Cohesion: 0.33
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

### Community 203 - "CandleStreamTest.php"
Cohesion: 0.15
Nodes (17): {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#7}(), {closure#8}(), {closure#9}() (+9 more)

### Community 205 - "InstrumentRules"
Cohesion: 0.24
Nodes (11): InstrumentRules, RulesHistory, {closure#2}(), {closure#4}(), {closure#41}(), {closure#1}(), {closure#2}(), {closure#3}() (+3 more)

### Community 206 - "StrategyVersion"
Cohesion: 0.24
Nodes (5): {closure#1}(), {closure#3}(), CheckResult, SaveResult, StrategyVersion

### Community 208 - "Engine.php"
Cohesion: 0.05
Nodes (17): {closure#1}(), {closure#2}(), Balance, IncomeKind, Funding, Liquidation, {closure#1}(), {closure#11}() (+9 more)

### Community 209 - "RiskConfigTest.php"
Cohesion: 0.50
Nodes (4): {closure#1}(), {closure#2}(), {closure#3}(), documentedLimits()

### Community 210 - "SimulationJobsTest.php"
Cohesion: 0.21
Nodes (5): {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), savedEmaCross()

### Community 213 - "CrossSection"
Cohesion: 0.15
Nodes (11): {closure#42}(), {closure#56}(), {closure#57}(), {closure#58}(), {closure#59}(), {closure#60}(), {closure#62}(), {closure#65}() (+3 more)

### Community 214 - "App"
Cohesion: 0.06
Nodes (45): App, {closure#1}(), {closure#2}(), {closure#3}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}() (+37 more)

### Community 215 - "AppServiceProvider.php"
Cohesion: 0.11
Nodes (8): SystemSleeper, LocalDiskUsage, AppServiceProvider, {closure#3}(), {closure#4}(), {closure#5}(), {closure#7}(), FortifyServiceProvider

## Knowledge Gaps
- **151 isolated node(s):** `Env`, `None`, `approveLive`, `confirm`, `forgetKey` (+146 more)
  These have ≤1 connection - possible missing edges. (Counts symbols only; 990 node(s) total have ≤1 connection when file, concept and rationale nodes are included.)
- **65 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `Decimal` connect `Decimal` to `engineHarness`, `.encode`, `ExitReason`, `Engine`, `Percent`, `Strategy`, `CompiledStrategy.php`, `SimulatedVenueTest.php`, `BinanceVenueTest.php`, `StrategyLibrary.php`, `Candle`, `.of`, `.of`, `App\Strategy\Contracts\CrossSectionalStrategy`, `Strategy`, `Instrument`, `Ledger`, `App\Strategy\Dsl\Compiler\CompiledStrategy`, `Symbol`, `.times`, `Window`, `.of`, `CompiledStrategyTest.php`, `RuntimeException`, `EnginePerpTest.php`, `OrderManagerTest.php`, `InstrumentRules`, `App\Strategy\Dsl\Document\DocumentChecker`, `.times`, `Timeframe`, `Marks`, `.evaluate`, `SimulatedVenue`, `FundingCarry`, `IndicatorsTest.php`, `IndicatorReferenceTest.php`, `PolicyLimits`, `Carbon\CarbonImmutable`, `.run`, `Kind`, `Symbol`, `.evaluate`, `.measure`, `InstrumentRules`, `Engine.php`, `.isPositive`, `MonteCarlo`, `App\Support\Number\Decimal`, `RiskConfigTest.php`, `App\Strategy\Contracts\Strategy`, `OrderAck`, `.fromStrings`, `Node`, `Quantity`, `StrategyGates.php`, `.handle`, `ChartSeriesTest.php`, `Carbon\CarbonImmutable`, `InvalidArgumentException`?**
  _High betweenness centrality (0.145) - this node is a cross-community bridge._
- **What connects `Env`, `None`, `approveLive` to the rest of the system?**
  _151 weakly-connected nodes found - possible missing edges._
- **Should `engineHarness` be split into smaller, more focused modules?**
  _Cohesion score 0.1264849755415793 - nodes in this community are weakly interconnected._
- **Why does `Symbol` connect `Symbol` to `engineHarness`, `.encode`, `ExitReason`, `Strategy`, `DocumentChecker`, `SimulatedVenueTest.php`, `BinanceVenueTest.php`, `App\Support\Symbol`, `.act`, `CandleRepositoryTest.php`, `ExchangeVerificationTest.php`, `.now`, `Instrument`, `Strategy`, `Ledger`, `Symbol`, `.times`, `.of`, `EngineUniverseTest.php`, `CompiledStrategyTest.php`, `RuntimeException`, `App\Strategy\Data\StrategyContext`, `EnginePerpTest.php`, `OrderManagerTest.php`, `InstrumentRules`, `Decimal`, `.times`, `Timeframe`, `Marks`, `SimulatedVenue`, `FundingCarry`, `App\Strategy\Dsl\DslError`, `PolicyLimits`, `.run`, `.measure`, `InstrumentRules`, `Engine.php`, `OrderAck`, `StrategyGates.php`, `.handle`, `Laravel\Mcp\Response`, `ChartSeriesTest.php`, `InvalidArgumentException`?**
  _High betweenness centrality (0.027) - this node is a cross-community bridge._
- **Should `Engine` be split into smaller, more focused modules?**
  _Cohesion score 0.13086770981507823 - nodes in this community are weakly interconnected._
- **Why does `LocalDiskUsage` connect `AppServiceProvider.php` to `DiskUsage`?**
  _High betweenness centrality (0.027) - this node is a cross-community bridge._
- **Should `Strategy` be split into smaller, more focused modules?**
  _Cohesion score 0.11137254901960784 - nodes in this community are weakly interconnected._