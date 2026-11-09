# Graph Report - trading-platform  (2026-11-09)

## Corpus Check
- 636 files · ~267,589 words
- Verdict: corpus is large enough that graph structure adds value.
- Unclassified: 103 file(s) not represented in the graph (top: .zip 38, .CHECKSUM 38, (none) 19)

## Summary
- 5112 nodes · 17647 edges · 205 communities (150 shown, 55 thin omitted)
- Extraction: 91% EXTRACTED · 9% INFERRED · 0% AMBIGUOUS · INFERRED: 1620 edges (avg confidence: 0.85)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `1b749e6d`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- SimulatedPerpVenueTest.php
- engineHarness
- CanonicalJson
- DslError
- Engine
- .parse
- DocumentChecker
- .now
- CompiledStrategy.php
- SimulatedVenueTest.php
- ClientOrderId
- .of
- Candle
- ExchangeVerificationTest.php
- Clock
- Illuminate\Contracts\Config\Repository
- Node
- Illuminate\Console\Command
- Instrument
- ScreensTest.php
- Side
- Illuminate\Database\Schema\Blueprint
- generate.py
- ReadBack.php
- Symbol
- LadderTest.php
- TerminalQrCodeTest.php
- ForwardingVenue
- .of
- Backfill
- RuntimeException
- oi-momentum-long.strategy.yaml
- StrategyIdentity
- EnginePerpTest.php
- BinanceArchive
- LadderTest.php
- ScalpingCollectorTest.php
- App\Market\Data\FundingRate
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
- OrderBook
- BinanceRestClientTest.php
- ema-cross-scalp-short.strategy.yaml
- App\Livewire\SettingsScreen
- ReadBack
- SettingsScreen
- postgres
- AccountRuntime.php
- CompiledStrategy
- donchian-breakout-short.strategy.yaml
- IndicatorsTest.php
- support.mjs
- RunBacktestCommandTest.php
- IndicatorReferenceTest.php
- Psr\Log\LoggerInterface
- DatabaseCandleFeed.php
- AppServiceProvider.php
- donchian-breakout.strategy.yaml
- .run
- CompiledStrategyTest.php
- CompiledStrategy.php
- Carbon\CarbonImmutable
- Illuminate\Http\Client\Factory
- DailyReport
- Tokens
- scripts
- ma-trend-short.strategy.yaml
- CandleFeedTest.php
- .measure
- composer.json
- jobs
- postgres
- ma-trend.strategy.yaml
- .zero
- MonteCarlo
- App\Support\Number\Decimal
- pint/composer.json
- App\Agents\Mcp\TradingServer
- RunSimulation.php
- DocumentCheckerTest.php
- require-dev
- scripts-descriptions
- WarmingVenue
- .fromStrings
- config
- uses
- test
- with
- AppServiceProvider.php
- jobs
- .parse
- psr-4
- require
- Illuminate\Http\Request
- StrategyGates.php
- uses
- .handle
- devDependencies
- TradingTool
- ParamKind
- ScalpingFunctionsTest.php
- .of
- buy-and-hold.strategy.yaml
- PreparesBacktests.php
- TokensTest.php
- ArchiveImportTest.php
- User
- 2026_11_09_000010_add_two_factor_columns_to_users_table.php
- .now
- .register
- funding-carry.strategy.yaml
- McpServerTest.php
- env.mjs
- chart.js
- TelegramSettings
- Percent
- settings-screen.blade.php
- StrategyLibrary
- steps
- 2026_11_09_000014_create_health_tables.php
- .walk
- App\Market\Models\Instrument
- ExchangeVerification.php
- CandleRepositoryTest.php
- Illuminate\Support\Facades\Schema
- ResearchUniverse
- Illuminate\Foundation\Testing\RefreshDatabase
- scripts
- 2026_11_08_000007_create_strategy_gate_tables.php
- fake-telegram.mjs
- Illuminate\Database\Migrations\Migration
- 0001_01_01_000001_create_cache_table.php
- 2026_11_08_000004_create_strategy_versions_tables.php
- 2026_11_08_000006_create_mcp_tables.php
- 2026_11_09_000011_create_notification_settings_table.php
- .evaluate
- strategies-screen.blade.php
- extra
- app.blade.php
- halt
- remove
- account-screen.blade.php
- GlobalsTest.php
- App\Simulation\StrategyLibrary
- .evaluate
- CandleAggregator
- 2026_11_09_000015_create_scalping_tables.php
- .handle
- 2026_11_09_000012_create_account_results_tables.php
- 2026_11_09_000023_create_universe_members_table.php
- SettingsScreenTest.php
- FundingCarry
- CompiledStrategy
- Laravel\Mcp\Response
- PerpetualBaselinesTest.php
- MarketType
- PolicyLimits
- AuthenticateMcpToken.php
- 0001_01_01_000002_create_jobs_table.php
- 2026_11_09_000009_create_exchange_keys_table.php
- 2026_11_09_000018_add_effective_at_to_deployments.php
- 2026_11_09_000016_create_market_universe_tables.php
- CandleStreamTest.php
- 2026_11_09_000025_create_book_depth_table.php
- start-local.ps1
- {closure#12}
- Price
- 0001_01_01_000000_create_users_table.php
- RejectionReason
- 2026_11_09_000020_add_lifetime_to_instruments.php

## God Nodes (most connected - your core abstractions)
1. `Decimal` - 500 edges
2. `Side` - 205 edges
3. `Symbol` - 191 edges
4. `App` - 166 edges
5. `Candle` - 134 edges
6. `Timeframe` - 118 edges
7. `Price` - 100 edges
8. `Position` - 96 edges
9. `SimClock` - 86 edges
10. `Clock` - 73 edges

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

## Communities (205 total, 55 thin omitted)

### Community 0 - "SimulatedPerpVenueTest.php"
Cohesion: 0.14
Nodes (32): PriceMinute, {closure#1}(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}() (+24 more)

### Community 1 - "engineHarness"
Cohesion: 0.09
Nodes (44): EngineCommand, EngineMode, Backtest, Live, Paper, Testnet, ScriptedStrategy, {closure#1}() (+36 more)

### Community 2 - "CanonicalJson"
Cohesion: 0.17
Nodes (7): RunBacktest, CanonicalJson, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}()

### Community 3 - "DslError"
Cohesion: 0.12
Nodes (14): {closure#1}(), {closure#1}(), {closure#5}(), {closure#7}(), {closure#8}(), DslError, YamlList, YamlReader (+6 more)

### Community 4 - "Engine"
Cohesion: 0.07
Nodes (14): DecisionLog, Venue, DecisionRecord, EngineRecord, IntentRecord, Engine, {closure#1}(), ReconciliationMismatch (+6 more)

### Community 5 - ".parse"
Cohesion: 0.11
Nodes (32): PositionView, {closure#5}(), carryDecisions(), {closure#10}(), {closure#11}(), {closure#13}(), {closure#14}(), {closure#15}() (+24 more)

### Community 6 - "DocumentChecker"
Cohesion: 0.31
Nodes (3): InstrumentDirectory, DocumentChecker, YamlMap

### Community 7 - ".now"
Cohesion: 0.19
Nodes (5): {closure#1}(), {closure#2}(), {closure#1}(), Ladder, LadderStatus

### Community 8 - "CompiledStrategy.php"
Cohesion: 0.11
Nodes (7): PerpetualTracker, {closure#27}(), {closure#45}(), {closure#46}(), {closure#48}(), Compiler, Program

### Community 9 - "SimulatedVenueTest.php"
Cohesion: 0.18
Nodes (39): btc(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#15}(), {closure#16}(), {closure#17}() (+31 more)

### Community 10 - "ClientOrderId"
Cohesion: 0.06
Nodes (30): {closure#2}(), ClientOrderId, OcoRequest, OrderAck, OrderRequest, OrderStatus, Cancelled, Expired (+22 more)

### Community 11 - ".of"
Cohesion: 0.10
Nodes (15): AssetMismatch, Money, {closure#2}(), {closure#1}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#16}() (+7 more)

### Community 12 - "Candle"
Cohesion: 0.05
Nodes (50): {closure#1}(), {closure#3}(), Candle, InvalidCandle, BarIndicator, DirectionalMovement, {closure#1}(), {closure#2}() (+42 more)

### Community 13 - "ExchangeVerificationTest.php"
Cohesion: 0.12
Nodes (29): {closure#1}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}() (+21 more)

### Community 14 - "Clock"
Cohesion: 0.08
Nodes (8): DatabaseDecisionLog, FundingRateRepository, Heartbeats, QualityCheck, QualityReport, GridPart, Clock, {closure#1}()

### Community 15 - "Illuminate\Contracts\Config\Repository"
Cohesion: 0.14
Nodes (4): ExportStrategies, ImportStrategies, PreparesBacktests, {closure#3}()

### Community 16 - "Node"
Cohesion: 0.14
Nodes (20): Binary, Parser, Token, TokenKind, Colon, Comma, Dot, Duration (+12 more)

### Community 17 - "Illuminate\Console\Command"
Cohesion: 0.06
Nodes (23): FlattenEngine, HaltEngine, IssuesCommands, PauseStrategy, ResumeEngine, CommandKind, Flatten, Halt (+15 more)

### Community 18 - "Instrument"
Cohesion: 0.07
Nodes (14): BackfillStep, CandleRepository, {closure#2}(), {closure#4}(), {closure#1}(), CandleSource, Archive, Rest (+6 more)

### Community 19 - "ScreensTest.php"
Cohesion: 0.09
Nodes (33): approveLive(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}(), {closure#8}() (+25 more)

### Community 20 - "Side"
Cohesion: 0.07
Nodes (69): ExitReason, Flatten, Liquidation, Signal, Stop, Target, Fill, Marks (+61 more)

### Community 21 - "Illuminate\Database\Schema\Blueprint"
Cohesion: 0.14
Nodes (13): {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#1}(), {closure#2}(), {closure#3}() (+5 more)

### Community 22 - "generate.py"
Cohesion: 0.07
Nodes (30): atr(), bars(), bb(), change(), dmi(), donchian(), ema(), highest() (+22 more)

### Community 23 - "ReadBack.php"
Cohesion: 0.13
Nodes (8): {closure#8}(), EntrySpec, ExitSpec, Expression, {closure#1}(), {closure#2}(), {closure#5}(), {closure#7}()

### Community 24 - "Symbol"
Cohesion: 0.05
Nodes (20): BuyAndHold, DonchianBreakout, MovingAverageTrend, BuildableStrategy, Close, EntryOrder, Intent, MoveStop (+12 more)

### Community 25 - "LadderTest.php"
Cohesion: 0.16
Nodes (24): {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}(), {closure#8}() (+16 more)

### Community 26 - "TerminalQrCodeTest.php"
Cohesion: 0.20
Nodes (7): TerminalQrCode, {closure#3}(), {closure#4}(), {closure#5}(), expectedModules(), readColoured(), readPlain()

### Community 28 - ".of"
Cohesion: 0.18
Nodes (44): RiskState, StrategyLimits, account(), accountPolicy(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}() (+36 more)

### Community 29 - "Backfill"
Cohesion: 0.14
Nodes (4): ArchivePeriod, Backfill, BackfillReport, BackfillMarketData

### Community 30 - "RuntimeException"
Cohesion: 0.10
Nodes (6): ReplayDivergence, ArchiveMissing, ChecksumMismatch, OrderBookOutOfSync, PhrityTransport, StreamFailed

### Community 31 - "oi-momentum-long.strategy.yaml"
Cohesion: 0.07
Nodes (27): entries, long, exits, stop_loss, take_profit, when, fails_when, family (+19 more)

### Community 32 - "StrategyIdentity"
Cohesion: 0.20
Nodes (5): StrategyIdentity, RenamedStrategy, {closure#1}(), {closure#2}(), {closure#3}()

### Community 33 - "EnginePerpTest.php"
Cohesion: 0.20
Nodes (16): InMemoryDecisionLog, {closure#10}(), {closure#11}(), {closure#13}(), {closure#14}(), {closure#2}(), {closure#5}(), {closure#6}() (+8 more)

### Community 34 - "BinanceArchive"
Cohesion: 0.07
Nodes (9): BinanceArchive, {closure#1}(), {closure#2}(), {closure#3}(), ExchangeNumber, TradeFlowBuilder, {closure#6}(), {closure#1}() (+1 more)

### Community 35 - "LadderTest.php"
Cohesion: 0.13
Nodes (22): Account, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#6}(), {closure#7}(), reportAccount() (+14 more)

### Community 36 - "ScalpingCollectorTest.php"
Cohesion: 0.31
Nodes (11): aggTrade(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#6}(), {closure#7}(), collector() (+3 more)

### Community 37 - "App\Market\Data\FundingRate"
Cohesion: 0.12
Nodes (8): {closure#15}(), {closure#9}(), BookDepth, {closure#1}(), PerpetualView, TradeMemory, {closure#2}(), {closure#4}()

### Community 38 - "OrderManagerTest.php"
Cohesion: 0.09
Nodes (31): OrderRole, Entry, Exit, Flatten, Stop, Target, TrackedOrder, {closure#1}() (+23 more)

### Community 39 - "BacktestTest.php"
Cohesion: 0.08
Nodes (38): FeeSchedule, CostModel, CandleFeed, FundingRate, InstrumentRules, RulesHistory, Backtest, {closure#1}() (+30 more)

### Community 40 - "App\Strategy\Dsl\Document\DocumentChecker"
Cohesion: 0.06
Nodes (27): Argument, BoolLiteral, Call, DurationLiteral, Identifier, Lookback, Node, NumberLiteral (+19 more)

### Community 41 - "ema-cross-scalp-long.strategy.yaml"
Cohesion: 0.06
Nodes (33): entries, long, exits, stop_loss, take_profit, time_stop, when, fails_when (+25 more)

### Community 42 - "Decimal"
Cohesion: 0.04
Nodes (51): {closure#6}(), RoundTrip, {closure#1}(), {closure#2}(), {closure#3}(), {closure#10}(), {closure#5}(), {closure#4}() (+43 more)

### Community 43 - ".zero"
Cohesion: 0.06
Nodes (11): SimulatedFuturesBook, SimulatedOrder, SimulatedVenue, UpdateStream, {closure#4}(), Obv, Perpetual, {closure#8}() (+3 more)

### Community 44 - "App"
Cohesion: 0.07
Nodes (31): SendTelegram, App, {closure#1}(), {closure#2}(), {closure#3}(), {closure#1}(), {closure#2}(), {closure#4}() (+23 more)

### Community 45 - "Timeframe"
Cohesion: 0.09
Nodes (18): {closure#2}(), {closure#4}(), CarryTemplate, {closure#2}(), {closure#8}(), Timeframe, FifteenMinutes, FiveMinutes (+10 more)

### Community 46 - "PaperEngineTest.php"
Cohesion: 0.04
Nodes (26): {closure#1}(), McpCall, McpToken, {closure#4}(), {closure#5}(), Notifier, AccountEquity, AccountSnapshot (+18 more)

### Community 47 - "package.json"
Cohesion: 0.15
Nodes (17): dependencies, codemirror@^6.0.2, @codemirror/lang-yaml@^6.1.3, @codemirror/language@^6.13.1, @lezer/highlight@^1.2.5, lightweight-charts@^5.2.1, optionalDependencies, @laravel/multiplex@^0.4.1 (+9 more)

### Community 48 - "OrderBook"
Cohesion: 0.15
Nodes (8): DepthUpdate, OrderBook, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), levels(), update()

### Community 49 - "BinanceRestClientTest.php"
Cohesion: 0.09
Nodes (30): {closure#3}(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), fakeArchiveListing(), {closure#5}(), binanceClient() (+22 more)

### Community 50 - "ema-cross-scalp-short.strategy.yaml"
Cohesion: 0.06
Nodes (33): entries, short, exits, stop_loss, take_profit, time_stop, when, fails_when (+25 more)

### Community 51 - "App\Livewire\SettingsScreen"
Cohesion: 0.06
Nodes (16): Deployment, ReauthController, AccountScreen, ChartScreen, {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}() (+8 more)

### Community 54 - "postgres"
Cohesion: 0.10
Nodes (19): POSTGRES_DB, POSTGRES_PASSWORD, POSTGRES_USER, interval, retries, test, timeout, environment (+11 more)

### Community 55 - "AccountRuntime.php"
Cohesion: 0.08
Nodes (13): {closure#1}(), {closure#2}(), Balance, VenuePosition, {closure#1}(), {closure#2}(), {closure#3}(), SimulatedPosition (+5 more)

### Community 56 - "CompiledStrategy"
Cohesion: 0.08
Nodes (13): SizedStrategy, {closure#1}(), LimitedStrategy, CompiledStrategy, {closure#1}(), {closure#2}(), __construct(), enterOnce() (+5 more)

### Community 57 - "donchian-breakout-short.strategy.yaml"
Cohesion: 0.07
Nodes (26): entries, short, default, range, step, default, range, step (+18 more)

### Community 58 - "IndicatorsTest.php"
Cohesion: 0.10
Nodes (20): Atr, Donchian, Ema, Sma, bar(), {closure#10}(), {closure#12}(), {closure#13}() (+12 more)

### Community 59 - "support.mjs"
Cohesion: 0.26
Nodes (9): @playwright/test, confirmed(), checkLayout(), EMAIL, freshCode(), PASSWORD, reauthenticate(), totp() (+1 more)

### Community 60 - "RunBacktestCommandTest.php"
Cohesion: 0.11
Nodes (28): {closure#1}(), {closure#4}(), {closure#6}(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}() (+20 more)

### Community 61 - "IndicatorReferenceTest.php"
Cohesion: 0.04
Nodes (29): {closure#58}(), Bollinger, Change, DonchianChannel, Highest, Hma, Lowest, Macd (+21 more)

### Community 62 - "Psr\Log\LoggerInterface"
Cohesion: 0.05
Nodes (15): BinanceEndpoints, {closure#1}(), {closure#2}(), StreamCandles, {closure#2}(), DepthStream, FormingCandles, {closure#1}() (+7 more)

### Community 63 - "DatabaseCandleFeed.php"
Cohesion: 0.07
Nodes (28): {closure#3}(), {closure#5}(), OpenInterest, Positioning, {closure#2}(), DatabaseCandleFeed, {closure#1}(), Rows (+20 more)

### Community 64 - "AppServiceProvider.php"
Cohesion: 0.09
Nodes (8): SystemSleeper, LocalDiskUsage, TelegramNotifier, AppServiceProvider, {closure#3}(), {closure#5}(), {closure#7}(), FortifyServiceProvider

### Community 65 - "donchian-breakout.strategy.yaml"
Cohesion: 0.07
Nodes (26): entries, long, default, range, step, default, range, step (+18 more)

### Community 66 - ".run"
Cohesion: 0.06
Nodes (48): RiskProfile, Account, Benchmark, RiskPolicy, PerformanceMetrics, Report, Strategy, {closure#1}() (+40 more)

### Community 67 - "CompiledStrategyTest.php"
Cohesion: 0.16
Nodes (13): KnownInstruments, aggregatedBars(), {closure#11}(), {closure#13}(), {closure#16}(), {closure#17}(), {closure#2}(), {closure#3}() (+5 more)

### Community 68 - "CompiledStrategy.php"
Cohesion: 0.05
Nodes (49): {closure#1}(), {closure#2}(), {closure#3}(), {closure#5}(), {closure#6}(), {closure#7}(), {closure#9}(), {closure#3}() (+41 more)

### Community 69 - "Carbon\CarbonImmutable"
Cohesion: 0.05
Nodes (37): Income, IncomeKind, Funding, Liquidation, Liquidity, Maker, Taker, OrderUpdate (+29 more)

### Community 70 - "Illuminate\Http\Client\Factory"
Cohesion: 0.11
Nodes (10): ArchiveCatalog, FillDailyStats, SyncInstruments, DailyStats, {closure#3}(), {closure#4}(), DailyHistory, {closure#1}() (+2 more)

### Community 72 - "Tokens"
Cohesion: 0.15
Nodes (3): CreateMcpToken, RevokeMcpToken, Tokens

### Community 73 - "scripts"
Cohesion: 0.15
Nodes (13): scripts, analyse, dev, format, mutate, post-autoload-dump, post-update-cmd, pre-package-uninstall (+5 more)

### Community 74 - "ma-trend-short.strategy.yaml"
Cohesion: 0.07
Nodes (26): entries, short, exits, stop_loss, when, fails_when, family, default (+18 more)

### Community 75 - "CandleFeedTest.php"
Cohesion: 0.25
Nodes (16): {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#15}(), {closure#3}() (+8 more)

### Community 76 - ".measure"
Cohesion: 0.15
Nodes (7): EquityTracker, Performance, {closure#2}(), {closure#1}(), {closure#2}(), {closure#5}(), trackedTwoDays()

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

### Community 81 - ".zero"
Cohesion: 0.06
Nodes (43): Statistics, Cscv, DeflatedSharpe, {closure#3}(), DecimalMath, {closure#3}(), {closure#1}(), {closure#2}() (+35 more)

### Community 82 - "MonteCarlo"
Cohesion: 0.30
Nodes (9): MonteCarlo, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), mcDecimals() (+1 more)

### Community 83 - "App\Support\Number\Decimal"
Cohesion: 0.07
Nodes (55): TradeFlow, {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#15}() (+47 more)

### Community 84 - "pint/composer.json"
Cohesion: 0.20
Nodes (9): config, sort-packages, description, license, name, require, laravel/pint, php (+1 more)

### Community 85 - "App\Agents\Mcp\TradingServer"
Cohesion: 0.18
Nodes (4): DslReference, GetFunding, ListStrategies, TradingServer

### Community 86 - "RunSimulation.php"
Cohesion: 0.12
Nodes (3): ImportMarketData, RunSimulation, SimulationJob

### Community 87 - "DocumentCheckerTest.php"
Cohesion: 0.30
Nodes (10): checkDocument(), {closure#1}(), {closure#2}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}(), {closure#8}() (+2 more)

### Community 88 - "require-dev"
Cohesion: 0.22
Nodes (9): require-dev, fakerphp/faker, larastan/larastan, laravel/pail, laravel/pao, mockery/mockery, nunomaduro/collision, pestphp/pest (+1 more)

### Community 89 - "scripts-descriptions"
Cohesion: 0.25
Nodes (8): scripts-descriptions, analyse, format, mutate, test, test:network, test:testnet, tools:install

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

### Community 96 - "AppServiceProvider.php"
Cohesion: 0.08
Nodes (7): CheckHealth, DiskUsage, {closure#1}(), HealthAlerts, HealthChecks, HealthResult, FakeDisk

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
Cohesion: 0.22
Nodes (9): require, bacon/bacon-qr-code, laravel/fortify, laravel/framework, laravel/mcp, laravel/tinker, livewire/livewire, php (+1 more)

### Community 102 - "Illuminate\Http\Request"
Cohesion: 0.12
Nodes (7): ChartLiveController, {closure#8}(), {closure#3}(), {closure#4}(), {closure#1}(), {closure#2}(), {closure#3}()

### Community 103 - "StrategyGates.php"
Cohesion: 0.07
Nodes (19): {closure#11}(), {closure#12}(), {closure#2}(), {closure#3}(), {closure#6}(), {closure#8}(), {closure#2}(), {closure#1}() (+11 more)

### Community 104 - "uses"
Cohesion: 0.21
Nodes (12): steps, name, runs-on, steps, frontend, lint, name, runs-on (+4 more)

### Community 106 - "devDependencies"
Cohesion: 0.18
Nodes (12): devDependencies, concurrently@^10.0.3, laravel-vite-plugin@^3.1, @playwright/test@1.63.0, tailwindcss@^4.0.0, @tailwindcss/vite@^4.0.0, vite@^8.0.0, concurrently (+4 more)

### Community 108 - "TradingTool"
Cohesion: 0.10
Nodes (8): GetJob, GetStrategy, RunBacktest, RunGrid, SaveStrategy, SimulationTool, TradingTool, SimulationQueue

### Community 117 - "ParamKind"
Cohesion: 0.14
Nodes (12): {closure#2}(), {closure#1}(), {closure#2}(), ParamKind, Anchor, Count, DepthBand, Duration (+4 more)

### Community 118 - "ScalpingFunctionsTest.php"
Cohesion: 0.25
Nodes (10): backtestableScalpingHour(), {closure#3}(), {closure#5}(), {closure#6}(), {closure#7}(), {closure#8}(), {closure#9}(), referencePart() (+2 more)

### Community 119 - ".of"
Cohesion: 0.08
Nodes (38): Band, {closure#2}(), {closure#8}(), {closure#9}(), {closure#41}(), {closure#44}(), {closure#45}(), {closure#46}() (+30 more)

### Community 120 - "buy-and-hold.strategy.yaml"
Cohesion: 0.12
Nodes (16): entries, long, exits, stop_loss, fails_when, family, hypothesis, when (+8 more)

### Community 121 - "PreparesBacktests.php"
Cohesion: 0.12
Nodes (10): {closure#1}(), {closure#2}(), {closure#2}(), {closure#3}(), {closure#5}(), {closure#7}(), {closure#8}(), {closure#1}() (+2 more)

### Community 122 - "TokensTest.php"
Cohesion: 0.31
Nodes (6): {closure#3}(), {closure#4}(), contrastRatio(), contrastRules(), relativeLuminance(), themeTokens()

### Community 123 - "ArchiveImportTest.php"
Cohesion: 0.19
Nodes (14): archiveBytes(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}() (+6 more)

### Community 124 - "User"
Cohesion: 0.12
Nodes (3): User, UserFactory, {closure#1}()

### Community 125 - "2026_11_09_000010_add_two_factor_columns_to_users_table.php"
Cohesion: 0.18
Nodes (4): {closure#1}(), {closure#2}(), {closure#1}(), {closure#2}()

### Community 126 - ".now"
Cohesion: 0.07
Nodes (13): AccountRuntime, LiveGate, OpenAccount, ApproveLive, ConfirmsOperator, CreateAccount, RunEngine, StoreKeys (+5 more)

### Community 127 - ".register"
Cohesion: 0.07
Nodes (49): SimClock, {closure#9}(), binanceVenue(), {closure#5}(), {closure#7}(), {closure#9}(), {closure#1}(), {closure#10}() (+41 more)

### Community 128 - "funding-carry.strategy.yaml"
Cohesion: 0.12
Nodes (15): fails_when, family, hypothesis, params, enter_when, exit_when, margin_buffer, max_leverage_perp (+7 more)

### Community 129 - "McpServerTest.php"
Cohesion: 0.30
Nodes (11): actAsToken(), {closure#10}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}() (+3 more)

### Community 130 - "env.mjs"
Cohesion: 0.27
Nodes (4): sizes, BASE_URL, E2E_ENV, TELEGRAM_URL

### Community 131 - "chart.js"
Cohesion: 0.27
Nodes (7): faded(), options(), priceChart(), token(), volumeColor(), dslEditor(), highlight

### Community 132 - "TelegramSettings"
Cohesion: 0.16
Nodes (6): NotificationSettings, SettingSource, Env, None, TelegramClient, TelegramSettings

### Community 133 - "Percent"
Cohesion: 0.24
Nodes (3): Percent, {closure#14}(), {closure#15}()

### Community 135 - "settings-screen.blade.php"
Cohesion: 0.15
Nodes (12): approveLive, confirm, forgetKey, forgetTelegram, importUniverse, monitor, notify, removeInstrument (+4 more)

### Community 137 - "StrategyLibrary"
Cohesion: 0.25
Nodes (4): CompileStrategy, StrategyLibrary, CheckResult, SaveResult

### Community 138 - "steps"
Cohesion: 0.33
Nodes (6): name, runs-on, services, steps, e2e, if

### Community 139 - "2026_11_09_000014_create_health_tables.php"
Cohesion: 0.33
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

### Community 140 - ".walk"
Cohesion: 0.31
Nodes (11): CandleFactory, aggregate(), {closure#1}(), {closure#11}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}() (+3 more)

### Community 141 - "App\Market\Models\Instrument"
Cohesion: 0.12
Nodes (18): {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#2}(), {closure#4}() (+10 more)

### Community 143 - "CandleRepositoryTest.php"
Cohesion: 0.19
Nodes (13): {closure#2}(), partitionOf(), candleStore(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}() (+5 more)

### Community 144 - "Illuminate\Support\Facades\Schema"
Cohesion: 0.15
Nodes (3): {closure#1}(), {closure#1}(), {closure#1}()

### Community 145 - "ResearchUniverse"
Cohesion: 0.10
Nodes (6): HistoryImports, {closure#2}(), {closure#3}(), ResearchUniverse, MarketJob, {closure#8}()

### Community 146 - "Illuminate\Foundation\Testing\RefreshDatabase"
Cohesion: 0.08
Nodes (25): {closure#1}(), {closure#1}(), {closure#2}(), {closure#3}(), fakeTestnet(), testnetAccount(), {closure#1}(), {closure#3}() (+17 more)

### Community 147 - "scripts"
Cohesion: 0.50
Nodes (4): scripts, build, dev, e2e

### Community 151 - "Illuminate\Database\Migrations\Migration"
Cohesion: 0.12
Nodes (3): {closure#1}(), {closure#1}(), {closure#1}()

### Community 156 - ".evaluate"
Cohesion: 0.29
Nodes (7): WalkForward, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), wfSet()

### Community 157 - "strategies-screen.blade.php"
Cohesion: 0.33
Nodes (5): open, promote, promoteDeployment, run, save

### Community 159 - "extra"
Cohesion: 0.67
Nodes (3): extra, laravel, dont-discover

### Community 174 - "App\Simulation\StrategyLibrary"
Cohesion: 0.09
Nodes (8): StrategiesScreen, StoredInstruments, {closure#1}(), InstrumentFilter, {closure#1}(), CompileStrategy, DatabaseSeeder, E2eSeeder

### Community 175 - ".evaluate"
Cohesion: 0.24
Nodes (9): Gates, brokenGate(), {closure#1}(), {closure#3}(), {closure#4}(), {closure#6}(), {closure#7}(), gate() (+1 more)

### Community 176 - "CandleAggregator"
Cohesion: 0.26
Nodes (6): CandleAggregator, CandleSequenceBroken, {closure#3}(), {closure#7}(), {closure#10}(), {closure#2}()

### Community 177 - "2026_11_09_000015_create_scalping_tables.php"
Cohesion: 0.33
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

### Community 181 - "SettingsScreenTest.php"
Cohesion: 0.18
Nodes (13): {closure#10}(), {closure#11}(), {closure#13}(), {closure#14}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}() (+5 more)

### Community 183 - "CompiledStrategy"
Cohesion: 0.31
Nodes (7): {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#7}(), readBackDocument(), readBackSpec()

### Community 184 - "Laravel\Mcp\Response"
Cohesion: 0.16
Nodes (6): ChartTool, GetCandles, GetIndicators, ListInstruments, ListTrials, ChartSeries

### Community 185 - "PerpetualBaselinesTest.php"
Cohesion: 0.36
Nodes (7): {closure#1}(), {closure#3}(), goldenMinutes(), goldenRates(), perpetualReferencePrice(), referenceMean(), reportTrades()

### Community 189 - "MarketType"
Cohesion: 0.07
Nodes (33): BinanceRequestFailed, BinanceRestClient, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), EpochTime, Json (+25 more)

### Community 190 - "PolicyLimits"
Cohesion: 0.13
Nodes (10): {closure#1}(), {closure#3}(), PolicyLimits, StrategyRepository, StrategyVersion, {closure#1}(), {closure#2}(), {closure#3}() (+2 more)

### Community 192 - "0001_01_01_000002_create_jobs_table.php"
Cohesion: 0.33
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

### Community 195 - "2026_11_09_000016_create_market_universe_tables.php"
Cohesion: 0.40
Nodes (3): {closure#1}(), {closure#2}(), up()

### Community 196 - "CandleStreamTest.php"
Cohesion: 0.16
Nodes (19): Sleeper, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#7}(), {closure#8}() (+11 more)

### Community 198 - "start-local.ps1"
Cohesion: 0.27
Nodes (4): Stop-Postgres(), Stop-ProcessTree(), Test-PostgresRunning(), Write-Step()

### Community 200 - "Price"
Cohesion: 0.14
Nodes (5): ProtectionPlan, {closure#1}(), {closure#2}(), PerpPosition, Price

### Community 202 - "0001_01_01_000000_create_users_table.php"
Cohesion: 0.33
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

### Community 203 - "RejectionReason"
Cohesion: 0.08
Nodes (29): AccountView, RejectionReason, BelowMinimum, Breaker, Cooldown, DuplicateIntent, EntryWorking, GrossExposure (+21 more)

## Knowledge Gaps
- **150 isolated node(s):** `Count`, `Number`, `Series`, `Anchor`, `Duration` (+145 more)
  These have ≤1 connection - possible missing edges. (Counts symbols only; 971 node(s) total have ≤1 connection when file, concept and rationale nodes are included.)
- **55 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `Decimal` connect `Decimal` to `SimulatedPerpVenueTest.php`, `engineHarness`, `CanonicalJson`, `DslError`, `Engine`, `.parse`, `DocumentChecker`, `Percent`, `SimulatedVenueTest.php`, `ClientOrderId`, `.of`, `Candle`, `.walk`, `Clock`, `ExchangeVerification.php`, `Illuminate\Foundation\Testing\RefreshDatabase`, `Side`, `ReadBack.php`, `Symbol`, `ForwardingVenue`, `.of`, `.evaluate`, `EnginePerpTest.php`, `App\Market\Data\FundingRate`, `OrderManagerTest.php`, `BacktestTest.php`, `App\Strategy\Dsl\Document\DocumentChecker`, `.zero`, `Timeframe`, `.evaluate`, `CandleAggregator`, `FundingCarry`, `AccountRuntime.php`, `CompiledStrategy`, `CompiledStrategy`, `IndicatorsTest.php`, `PerpetualBaselinesTest.php`, `RunBacktestCommandTest.php`, `IndicatorReferenceTest.php`, `PolicyLimits`, `.run`, `CompiledStrategyTest.php`, `CompiledStrategy.php`, `Carbon\CarbonImmutable`, `Price`, `RejectionReason`, `.measure`, `.zero`, `MonteCarlo`, `App\Support\Number\Decimal`, `DocumentCheckerTest.php`, `WarmingVenue`, `.fromStrings`, `StrategyGates.php`, `.handle`, `.of`, `PreparesBacktests.php`?**
  _High betweenness centrality (0.111) - this node is a cross-community bridge._
- **What connects `Count`, `Number`, `Series` to the rest of the system?**
  _150 weakly-connected nodes found - possible missing edges._
- **Should `SimulatedPerpVenueTest.php` be split into smaller, more focused modules?**
  _Cohesion score 0.1411764705882353 - nodes in this community are weakly interconnected._
- **Why does `Symbol` connect `Symbol` to `SimulatedPerpVenueTest.php`, `engineHarness`, `CanonicalJson`, `DslError`, `Engine`, `.parse`, `DocumentChecker`, `SimulatedVenueTest.php`, `ClientOrderId`, `.of`, `Candle`, `ExchangeVerificationTest.php`, `Illuminate\Contracts\Config\Repository`, `CandleRepositoryTest.php`, `Illuminate\Console\Command`, `Illuminate\Foundation\Testing\RefreshDatabase`, `Side`, `ForwardingVenue`, `.of`, `EnginePerpTest.php`, `OrderManagerTest.php`, `BacktestTest.php`, `Decimal`, `.zero`, `Timeframe`, `App\Simulation\StrategyLibrary`, `FundingCarry`, `AccountRuntime.php`, `Laravel\Mcp\Response`, `CompiledStrategy`, `PerpetualBaselinesTest.php`, `RunBacktestCommandTest.php`, `PolicyLimits`, `DatabaseCandleFeed.php`, `.run`, `CompiledStrategyTest.php`, `Carbon\CarbonImmutable`, `{closure#12}`, `Price`, `.zero`, `WarmingVenue`, `StrategyGates.php`, `.handle`, `.now`, `.register`?**
  _High betweenness centrality (0.041) - this node is a cross-community bridge._
- **Should `engineHarness` be split into smaller, more focused modules?**
  _Cohesion score 0.09423076923076923 - nodes in this community are weakly interconnected._
- **Why does `Candle` connect `Candle` to `SimulatedPerpVenueTest.php`, `engineHarness`, `.parse`, `SimulatedVenueTest.php`, `.walk`, `ExchangeVerificationTest.php`, `ExchangeVerification.php`, `CandleRepositoryTest.php`, `Instrument`, `EnginePerpTest.php`, `OrderManagerTest.php`, `BacktestTest.php`, `Decimal`, `.zero`, `Timeframe`, `CandleAggregator`, `PerpetualBaselinesTest.php`, `IndicatorsTest.php`, `IndicatorReferenceTest.php`, `MarketType`, `DatabaseCandleFeed.php`, `.run`, `CompiledStrategyTest.php`, `Carbon\CarbonImmutable`, `Price`, `.fromStrings`, `StrategyGates.php`?**
  _High betweenness centrality (0.032) - this node is a cross-community bridge._
- **Should `DslError` be split into smaller, more focused modules?**
  _Cohesion score 0.12063492063492064 - nodes in this community are weakly interconnected._