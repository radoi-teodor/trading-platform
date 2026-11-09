# Graph Report - trading-platform  (2026-11-09)

## Corpus Check
- 610 files · ~255,493 words
- Verdict: corpus is large enough that graph structure adds value.
- Unclassified: 101 file(s) not represented in the graph (top: .zip 38, .CHECKSUM 38, (none) 19)

## Summary
- 4899 nodes · 16868 edges · 209 communities (159 shown, 50 thin omitted)
- Extraction: 91% EXTRACTED · 9% INFERRED · 0% AMBIGUOUS · INFERRED: 1527 edges (avg confidence: 0.85)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `0cd9159a`
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
- MovingAverageTrend
- .of
- Candle
- ExchangeVerificationTest.php
- .isGreaterThan
- PolicyLimits
- Node
- Illuminate\Console\Command
- Instrument
- ScreensTest.php
- Ledger
- Illuminate\Database\Schema\Blueprint
- generate.py
- App\Strategy\Dsl\Document\DocumentChecker
- Symbol
- RejectionReason
- TerminalQrCodeTest.php
- Price
- .of
- SimulatedPerpVenueTest.php
- RuntimeException
- oi-momentum-long.strategy.yaml
- App\Market\Data\FundingRate
- EnginePerpTest.php
- BinanceArchive
- App
- SimClock
- RiskState
- OrderManagerTest.php
- BacktestTest.php
- App\Strategy\Dsl\Document\DocumentChecker
- ema-cross-scalp-long.strategy.yaml
- Decimal
- .zero
- ScalpingArchiveTest.php
- Timeframe
- Illuminate\Database\Eloquent\Model
- package.json
- DonchianBreakout
- BinanceRestClientTest.php
- ema-cross-scalp-short.strategy.yaml
- App\Livewire\SettingsScreen
- ReadBack
- Laravel\Mcp\Response
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
- healthyMachine
- donchian-breakout.strategy.yaml
- .run
- StrategyIdentity
- App\Strategy\Dsl\Document\DocumentChecker
- PreparesBacktests
- CandleFeedTest.php
- SettingsScreen
- ScalpingFunctionsTest.php
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
- .handle
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
- LiveBar
- ParamSpec
- App\Agents\Mcp\TradingServer
- RulesHistory
- buy-and-hold.strategy.yaml
- ChartSeriesTest.php
- TokensTest.php
- ArchiveImportTest.php
- App\Execution\Models\Account
- Illuminate\Database\Migrations\Migration
- StrategyVersion
- .register
- funding-carry.strategy.yaml
- McpServerTest.php
- env.mjs
- chart.js
- TelegramSettings
- settings-screen.blade.php
- 0001_01_01_000000_create_users_table.php
- steps
- 2026_11_09_000014_create_health_tables.php
- DecimalMath
- App\Support\Clock\Clock
- Statistics
- CandleRepositoryTest.php
- Illuminate\Database\Migrations\Migration
- 2026_11_09_000019_create_forming_candles_table.php
- App\Market\Data\MarketType
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
- TradingTool
- StrategyLibrary
- App\Market\FundingRateRepository
- Illuminate\Foundation\Testing\RefreshDatabase
- .__construct
- PaperEngineTest.php
- 2026_11_09_000012_create_account_results_tables.php
- EnterOnceStrategy
- SettingsScreenTest.php
- Timeframe
- App\Strategy\Dsl\Document\StrategySpec
- ReadsGridOptions
- 2026_11_09_000013_add_daily_report_to_notification_settings.php
- MarketType
- Venue
- Clock
- 2026_11_09_000018_add_effective_at_to_deployments.php
- 2026_11_09_000016_create_market_universe_tables.php
- CandleStreamTest.php
- 2026_11_09_000017_create_live_tables.php
- start-local.ps1
- FundingCarry
- .plus
- .decide
- RiskPolicy
- LiveAccountTest.php
- 2026_11_09_000015_create_scalping_tables.php
- Percent
- ChartSeries.php
- AccountRuntime
- VenuePosition
- StrategyContext
- .number

## God Nodes (most connected - your core abstractions)
1. `Decimal` - 514 edges
2. `Side` - 205 edges
3. `Symbol` - 201 edges
4. `App` - 146 edges
5. `Candle` - 136 edges
6. `Timeframe` - 121 edges
7. `Price` - 100 edges
8. `Position` - 96 edges
9. `SimClock` - 80 edges
10. `Clock` - 77 edges

## Surprising Connections (you probably didn't know these)
- `{closure#13}()` --calls--> `App`  [INFERRED]
  tests/Feature/Web/SettingsScreenTest.php → app/Notifications/SettingSource.php
- `{closure#8}()` --calls--> `App`  [INFERRED]
  tests/Feature/Web/SettingsScreenTest.php → app/Notifications/SettingSource.php
- `{closure#3}()` --calls--> `App`  [INFERRED]
  tests/Feature/Network/BinancePublicDataTest.php → app/Notifications/SettingSource.php
- `{closure#5}()` --calls--> `App`  [INFERRED]
  tests/Feature/Network/BinancePublicDataTest.php → app/Notifications/SettingSource.php
- `{closure#2}()` --calls--> `App`  [INFERRED]
  tests/Feature/Web/MarketDataSettingsTest.php → app/Notifications/SettingSource.php

## Import Cycles
- None detected.

## Communities (209 total, 50 thin omitted)

### Community 0 - "CompiledStrategyTest.php"
Cohesion: 0.16
Nodes (17): LimitedStrategy, aggregatedBars(), {closure#1}(), {closure#11}(), {closure#13}(), {closure#16}(), {closure#17}(), {closure#2}() (+9 more)

### Community 1 - "engineHarness"
Cohesion: 0.10
Nodes (43): EngineCommand, EngineMode, Backtest, Live, Paper, Testnet, ScriptedStrategy, {closure#1}() (+35 more)

### Community 2 - "CanonicalJson"
Cohesion: 0.30
Nodes (6): CanonicalJson, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}()

### Community 3 - "DslError"
Cohesion: 0.11
Nodes (13): {closure#1}(), {closure#1}(), {closure#8}(), DslError, YamlList, YamlReader, YamlScalar, YamlValue (+5 more)

### Community 4 - "Engine"
Cohesion: 0.11
Nodes (7): EngineRecord, Engine, {closure#1}(), FormingCandle, OpenInterest, ChartHistoryMissing, {closure#7}()

### Community 5 - ".parse"
Cohesion: 0.10
Nodes (35): {closure#12}(), {closure#17}(), {closure#5}(), carryDecisions(), {closure#10}(), {closure#11}(), {closure#13}(), {closure#14}() (+27 more)

### Community 6 - "DocumentChecker"
Cohesion: 0.34
Nodes (3): {closure#5}(), DocumentChecker, YamlMap

### Community 7 - ".now"
Cohesion: 0.19
Nodes (4): {closure#1}(), {closure#2}(), Ladder, LadderStatus

### Community 8 - "CompiledStrategy.php"
Cohesion: 0.16
Nodes (5): {closure#3}(), {closure#7}(), {closure#42}(), Compiler, Program

### Community 9 - "Side"
Cohesion: 0.10
Nodes (69): PositionView, Side, Buy, Sell, {closure#8}(), btc(), {closure#10}(), {closure#11}() (+61 more)

### Community 10 - "MovingAverageTrend"
Cohesion: 0.09
Nodes (6): BuyAndHold, MovingAverageTrend, BuildableStrategy, Subscription, HoldStrategy, {closure#1}()

### Community 11 - ".of"
Cohesion: 0.12
Nodes (19): AssetMismatch, Money, {closure#2}(), {closure#1}(), usdt(), {closure#11}(), {closure#7}(), {closure#1}() (+11 more)

### Community 12 - "Candle"
Cohesion: 0.12
Nodes (33): Candle, {closure#9}(), {closure#1}(), {closure#2}(), {closure#1}(), {closure#42}(), candleWith(), {closure#1}() (+25 more)

### Community 13 - "ExchangeVerificationTest.php"
Cohesion: 0.12
Nodes (27): {closure#1}(), {closure#12}(), {closure#13}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}() (+19 more)

### Community 14 - ".isGreaterThan"
Cohesion: 0.15
Nodes (4): {closure#2}(), InvalidNumber, {closure#10}(), {closure#9}()

### Community 15 - "PolicyLimits"
Cohesion: 0.10
Nodes (13): InstrumentDirectory, StoredInstruments, {closure#1}(), CompileStrategy, GridPart, PolicyLimits, StrategyRepository, {closure#1}() (+5 more)

### Community 16 - "Node"
Cohesion: 0.11
Nodes (21): Binary, Lexer, Parser, Token, TokenKind, Colon, Comma, Dot (+13 more)

### Community 17 - "Illuminate\Console\Command"
Cohesion: 0.06
Nodes (19): CreateMcpToken, RevokeMcpToken, Tokens, FlattenEngine, HaltEngine, IssuesCommands, PauseStrategy, ResumeEngine (+11 more)

### Community 18 - "Instrument"
Cohesion: 0.08
Nodes (10): BackfillStep, CandleRepository, CandleSource, Archive, Rest, Stream, UpsertResult, Instrument (+2 more)

### Community 19 - "ScreensTest.php"
Cohesion: 0.10
Nodes (27): {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), chartLiveQuery(), {closure#10}(), {closure#11}() (+19 more)

### Community 20 - "Ledger"
Cohesion: 0.07
Nodes (56): ExitReason, Flatten, Liquidation, Signal, Stop, Target, Marks, Ledger (+48 more)

### Community 21 - "Illuminate\Database\Schema\Blueprint"
Cohesion: 0.19
Nodes (10): {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#1}(), {closure#2}(), {closure#3}() (+2 more)

### Community 22 - "generate.py"
Cohesion: 0.07
Nodes (30): atr(), bars(), bb(), change(), dmi(), donchian(), ema(), highest() (+22 more)

### Community 23 - "App\Strategy\Dsl\Document\DocumentChecker"
Cohesion: 0.25
Nodes (6): {closure#6}(), {closure#9}(), EntrySpec, Expression, {closure#2}(), {closure#5}()

### Community 24 - "Symbol"
Cohesion: 0.09
Nodes (9): Close, EntryOrder, Intent, MoveStop, OpenCarry, OpenLong, OpenShort, Period (+1 more)

### Community 25 - "RejectionReason"
Cohesion: 0.08
Nodes (30): AccountView, RejectionReason, BelowMinimum, Breaker, Cooldown, DuplicateIntent, EntryWorking, GrossExposure (+22 more)

### Community 26 - "TerminalQrCodeTest.php"
Cohesion: 0.20
Nodes (7): TerminalQrCode, {closure#3}(), {closure#4}(), {closure#5}(), expectedModules(), readColoured(), readPlain()

### Community 27 - "Price"
Cohesion: 0.06
Nodes (29): {closure#2}(), Balance, ClientOrderId, Income, IncomeKind, Funding, Liquidation, OcoRequest (+21 more)

### Community 28 - ".of"
Cohesion: 0.20
Nodes (41): account(), accountPolicy(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#2}() (+33 more)

### Community 29 - "SimulatedPerpVenueTest.php"
Cohesion: 0.17
Nodes (27): {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#15}(), {closure#18}() (+19 more)

### Community 30 - "RuntimeException"
Cohesion: 0.06
Nodes (14): ReplayDivergence, ReconciliationMismatch, ArchiveMissing, ChecksumMismatch, BinanceRequestFailed, LocationRestricted, RateLimited, RequestFailed (+6 more)

### Community 31 - "oi-momentum-long.strategy.yaml"
Cohesion: 0.07
Nodes (27): entries, long, exits, stop_loss, take_profit, when, fails_when, family (+19 more)

### Community 32 - "App\Market\Data\FundingRate"
Cohesion: 0.15
Nodes (5): {closure#11}(), PerpetualTracker, PerpetualView, TradeMemory, {closure#7}()

### Community 33 - "EnginePerpTest.php"
Cohesion: 0.26
Nodes (15): {closure#10}(), {closure#11}(), {closure#12}(), {closure#14}(), {closure#2}(), {closure#5}(), {closure#6}(), {closure#7}() (+7 more)

### Community 34 - "BinanceArchive"
Cohesion: 0.16
Nodes (4): BinanceArchive, TradeFlowBuilder, {closure#1}(), {closure#2}()

### Community 35 - "App"
Cohesion: 0.07
Nodes (48): SendTelegram, App, {closure#1}(), {closure#2}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#4}() (+40 more)

### Community 36 - "SimClock"
Cohesion: 0.09
Nodes (25): FundingRate, SimClock, WallClock, {closure#1}(), {closure#2}(), {closure#3}(), {closure#1}(), {closure#2}() (+17 more)

### Community 37 - "RiskState"
Cohesion: 0.28
Nodes (5): RiskState, {closure#15}(), {closure#17}(), {closure#18}(), {closure#22}()

### Community 38 - "OrderManagerTest.php"
Cohesion: 0.09
Nodes (31): OrderAck, OrderRole, Entry, Exit, Flatten, Stop, Target, TrackedOrder (+23 more)

### Community 39 - "BacktestTest.php"
Cohesion: 0.09
Nodes (23): FeeSchedule, InMemoryDecisionLog, CostModel, CandleFeed, Backtest, {closure#1}(), {closure#2}(), BacktestResult (+15 more)

### Community 40 - "App\Strategy\Dsl\Document\DocumentChecker"
Cohesion: 0.06
Nodes (27): Argument, BoolLiteral, Call, DurationLiteral, Identifier, Lookback, Node, NumberLiteral (+19 more)

### Community 41 - "ema-cross-scalp-long.strategy.yaml"
Cohesion: 0.06
Nodes (33): entries, long, exits, stop_loss, take_profit, time_stop, when, fails_when (+25 more)

### Community 42 - "Decimal"
Cohesion: 0.04
Nodes (41): {closure#6}(), RoundTrip, {closure#1}(), {closure#2}(), {closure#3}(), {closure#10}(), {closure#5}(), {closure#4}() (+33 more)

### Community 43 - ".zero"
Cohesion: 0.08
Nodes (18): Liquidity, Maker, Taker, OrderStatus, Cancelled, Expired, Filled, New (+10 more)

### Community 44 - "ScalpingArchiveTest.php"
Cohesion: 0.22
Nodes (11): TradeFlow, {closure#10}(), {closure#11}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}() (+3 more)

### Community 45 - "Timeframe"
Cohesion: 0.18
Nodes (18): CandleAggregator, CandleSequenceBroken, {closure#9}(), CandleFactory, aggregate(), {closure#1}(), {closure#10}(), {closure#11}() (+10 more)

### Community 46 - "Illuminate\Database\Eloquent\Model"
Cohesion: 0.06
Nodes (12): McpCall, McpToken, AccountEquity, AccountSnapshot, AccountTrade, PlainDecimal, JournalEntry, {closure#1}() (+4 more)

### Community 47 - "package.json"
Cohesion: 0.15
Nodes (17): dependencies, codemirror@^6.0.2, @codemirror/lang-yaml@^6.1.3, @codemirror/language@^6.13.1, @lezer/highlight@^1.2.5, lightweight-charts@^5.2.1, optionalDependencies, @laravel/multiplex@^0.4.1 (+9 more)

### Community 48 - "DonchianBreakout"
Cohesion: 0.27
Nodes (4): DonchianBreakout, Donchian, {closure#10}(), {closure#15}()

### Community 49 - "BinanceRestClientTest.php"
Cohesion: 0.07
Nodes (35): binanceVenue(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}() (+27 more)

### Community 50 - "ema-cross-scalp-short.strategy.yaml"
Cohesion: 0.06
Nodes (33): entries, short, exits, stop_loss, take_profit, time_stop, when, fails_when (+25 more)

### Community 51 - "App\Livewire\SettingsScreen"
Cohesion: 0.07
Nodes (15): Account, Deployment, ReauthController, AccountScreen, ChartScreen, {closure#2}(), {closure#3}(), {closure#4}() (+7 more)

### Community 53 - "Laravel\Mcp\Response"
Cohesion: 0.09
Nodes (12): ChartTool, GetCandles, GetIndicators, GetJob, GetStrategy, {closure#1}(), ListTrials, RunBacktest (+4 more)

### Community 54 - "postgres"
Cohesion: 0.10
Nodes (19): POSTGRES_DB, POSTGRES_PASSWORD, POSTGRES_USER, interval, retries, test, timeout, environment (+11 more)

### Community 55 - "InvalidArgumentException"
Cohesion: 0.06
Nodes (14): {closure#1}(), {closure#2}(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#5}(), {closure#7}(), {closure#8}() (+6 more)

### Community 56 - "SizedStrategyTest.php"
Cohesion: 0.13
Nodes (11): SizedStrategy, {closure#1}(), {closure#1}(), {closure#2}(), __construct(), enterOnce(), identity(), limits() (+3 more)

### Community 57 - "donchian-breakout-short.strategy.yaml"
Cohesion: 0.07
Nodes (26): entries, short, default, range, step, default, range, step (+18 more)

### Community 58 - "IndicatorsTest.php"
Cohesion: 0.12
Nodes (15): Atr, Ema, Sma, {closure#1}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#16}() (+7 more)

### Community 59 - "support.mjs"
Cohesion: 0.26
Nodes (9): @playwright/test, confirmed(), checkLayout(), EMAIL, freshCode(), PASSWORD, reauthenticate(), totp() (+1 more)

### Community 60 - "RunBacktestCommandTest.php"
Cohesion: 0.07
Nodes (35): PriceMinute, BacktestRun, {closure#1}(), {closure#4}(), {closure#6}(), {closure#1}(), {closure#10}(), {closure#11}() (+27 more)

### Community 61 - "IndicatorReferenceTest.php"
Cohesion: 0.03
Nodes (34): {closure#43}(), BarIndicator, Change, DirectionalMovement, DonchianChannel, Highest, Hma, Keltner (+26 more)

### Community 62 - "App\Support\Clock\Clock"
Cohesion: 0.07
Nodes (8): {closure#1}(), {closure#3}(), FormingCandles, Heartbeats, CandleStream, ScalpingCollector, StreamTransport, HealthAlerts

### Community 63 - ".timestamp"
Cohesion: 0.21
Nodes (8): {closure#2}(), {closure#4}(), DatabaseCandleFeed, {closure#1}(), Rows, {closure#1}(), {closure#2}(), {closure#3}()

### Community 64 - "healthyMachine"
Cohesion: 0.38
Nodes (9): {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#8}(), healthAt(), healthyMachine() (+1 more)

### Community 65 - "donchian-breakout.strategy.yaml"
Cohesion: 0.07
Nodes (26): entries, long, default, range, step, default, range, step (+18 more)

### Community 66 - ".run"
Cohesion: 0.07
Nodes (43): Report, Strategy, btcMinutes(), {closure#1}(), {closure#2}(), {closure#3}(), referencePrice(), baselineDocument() (+35 more)

### Community 67 - "StrategyIdentity"
Cohesion: 0.22
Nodes (5): StrategyIdentity, RenamedStrategy, {closure#1}(), {closure#2}(), {closure#3}()

### Community 68 - "App\Strategy\Dsl\Document\DocumentChecker"
Cohesion: 0.08
Nodes (31): {closure#3}(), {closure#4}(), {closure#7}(), {closure#9}(), ParameterSpec, DslWriter, FunctionSpec, Library (+23 more)

### Community 69 - "PreparesBacktests"
Cohesion: 0.12
Nodes (8): {closure#2}(), {closure#3}(), {closure#4}(), {closure#1}(), {closure#1}(), PreparesBacktests, PromoteStrategy, {closure#4}()

### Community 70 - "CandleFeedTest.php"
Cohesion: 0.31
Nodes (13): {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#2}(), {closure#4}(), {closure#5}() (+5 more)

### Community 71 - "SettingsScreen"
Cohesion: 0.12
Nodes (3): OpenAccount, StoreKeys, SettingsScreen

### Community 72 - "ScalpingFunctionsTest.php"
Cohesion: 0.30
Nodes (8): backtestableScalpingHour(), {closure#3}(), {closure#5}(), {closure#6}(), {closure#8}(), referencePart(), scalpingReference(), storeScalpingHour()

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
Cohesion: 0.16
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
Cohesion: 0.14
Nodes (11): {closure#4}(), {closure#5}(), MonteCarlo, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}() (+3 more)

### Community 83 - "App\Support\Number\Decimal"
Cohesion: 0.07
Nodes (50): {closure#5}(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#15}() (+42 more)

### Community 84 - "pint/composer.json"
Cohesion: 0.20
Nodes (9): config, sort-packages, description, license, name, require, laravel/pint, php (+1 more)

### Community 85 - ".handle"
Cohesion: 0.09
Nodes (13): StrategyGates, Gates, CarryTemplate, ExitSpec, StrategySpec, brokenGate(), {closure#1}(), {closure#3}() (+5 more)

### Community 86 - "RunSimulation.php"
Cohesion: 0.10
Nodes (5): HistoryImports, ImportMarketData, MarketJob, RunSimulation, SimulationJob

### Community 87 - "DocumentCheckerTest.php"
Cohesion: 0.30
Nodes (10): checkDocument(), {closure#1}(), {closure#2}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}(), {closure#8}() (+2 more)

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

### Community 96 - "HealthChecks"
Cohesion: 0.23
Nodes (3): {closure#1}(), HealthChecks, HealthResult

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
Cohesion: 0.03
Nodes (24): {closure#4}(), Notifier, BinanceApiError, BinanceUnreachable, SignedBinanceClient, SystemSleeper, DiskUsage, LocalDiskUsage (+16 more)

### Community 103 - "StrategyGates.php"
Cohesion: 0.05
Nodes (31): {closure#11}(), {closure#12}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#6}(), {closure#8}(), {closure#2}() (+23 more)

### Community 104 - "uses"
Cohesion: 0.21
Nodes (12): steps, name, runs-on, steps, frontend, lint, name, runs-on (+4 more)

### Community 106 - "devDependencies"
Cohesion: 0.18
Nodes (12): devDependencies, concurrently@^10.0.3, laravel-vite-plugin@^3.1, @playwright/test@1.63.0, tailwindcss@^4.0.0, @tailwindcss/vite@^4.0.0, vite@^8.0.0, concurrently (+4 more)

### Community 117 - "ParamSpec"
Cohesion: 0.11
Nodes (13): {closure#2}(), {closure#1}(), {closure#1}(), {closure#2}(), ParamKind, Anchor, Count, Duration (+5 more)

### Community 118 - "App\Agents\Mcp\TradingServer"
Cohesion: 0.17
Nodes (4): DslReference, GetFunding, ListInstruments, TradingServer

### Community 119 - "RulesHistory"
Cohesion: 0.22
Nodes (13): InstrumentRules, RulesHistory, {closure#2}(), {closure#3}(), {closure#5}(), {closure#4}(), {closure#41}(), {closure#1}() (+5 more)

### Community 120 - "buy-and-hold.strategy.yaml"
Cohesion: 0.12
Nodes (16): entries, long, exits, stop_loss, fails_when, family, hypothesis, when (+8 more)

### Community 121 - "ChartSeriesTest.php"
Cohesion: 0.17
Nodes (12): VolumeProfile, chart(), {closure#10}(), {closure#11}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}() (+4 more)

### Community 122 - "TokensTest.php"
Cohesion: 0.31
Nodes (6): {closure#3}(), {closure#4}(), contrastRatio(), contrastRules(), relativeLuminance(), themeTokens()

### Community 123 - "ArchiveImportTest.php"
Cohesion: 0.35
Nodes (10): archiveBytes(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}() (+2 more)

### Community 124 - "App\Execution\Models\Account"
Cohesion: 0.12
Nodes (9): LiveGate, ApproveLive, ConfirmsOperator, CreateAccount, LiveApproval, {closure#2}(), {closure#3}(), {closure#4}() (+1 more)

### Community 125 - "Illuminate\Database\Migrations\Migration"
Cohesion: 0.15
Nodes (3): {closure#1}(), {closure#1}(), {closure#1}()

### Community 126 - "StrategyVersion"
Cohesion: 0.23
Nodes (4): {closure#1}(), ListStrategies, {closure#3}(), StrategyVersion

### Community 127 - ".register"
Cohesion: 0.09
Nodes (23): {closure#10}(), {closure#11}(), {closure#13}(), {closure#15}(), {closure#16}(), {closure#17}(), {closure#18}(), {closure#4}() (+15 more)

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
Cohesion: 0.11
Nodes (16): Normal, DecimalMath, {closure#10}(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}() (+8 more)

### Community 141 - "App\Support\Clock\Clock"
Cohesion: 0.07
Nodes (19): {closure#1}(), {closure#10}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}() (+11 more)

### Community 142 - "Statistics"
Cohesion: 0.20
Nodes (12): Statistics, {closure#1}(), {closure#2}(), {closure#3}(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}() (+4 more)

### Community 143 - "CandleRepositoryTest.php"
Cohesion: 0.28
Nodes (11): candleStore(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}() (+3 more)

### Community 146 - "App\Market\Data\MarketType"
Cohesion: 0.06
Nodes (12): CheckMarketData, SelectsInstruments, {closure#1}(), {closure#2}(), StreamCandles, VerifyMarketData, {closure#1}(), {closure#2}() (+4 more)

### Community 147 - "scripts"
Cohesion: 0.50
Nodes (4): scripts, build, dev, e2e

### Community 148 - "0001_01_01_000002_create_jobs_table.php"
Cohesion: 0.33
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

### Community 151 - "Carbon\CarbonImmutable"
Cohesion: 0.18
Nodes (11): {closure#4}(), MinuteCandles, BreakerEvent, MarketBreakers, {closure#14}(), {closure#3}(), {closure#7}(), breakerMinute() (+3 more)

### Community 157 - "strategies-screen.blade.php"
Cohesion: 0.33
Nodes (5): open, promote, promoteDeployment, run, save

### Community 159 - "extra"
Cohesion: 0.67
Nodes (3): extra, laravel, dont-discover

### Community 173 - "TradingTool"
Cohesion: 0.11
Nodes (3): SimulationTool, TradingTool, Controller

### Community 174 - "StrategyLibrary"
Cohesion: 0.19
Nodes (5): CompileStrategy, StrategiesScreen, StrategyLibrary, CheckResult, SaveResult

### Community 175 - "App\Market\FundingRateRepository"
Cohesion: 0.14
Nodes (4): ArchivePeriod, Backfill, BackfillReport, BackfillMarketData

### Community 176 - "Illuminate\Foundation\Testing\RefreshDatabase"
Cohesion: 0.12
Nodes (10): {closure#1}(), {closure#2}(), {closure#3}(), {closure#1}(), {closure#2}(), {closure#4}(), {closure#5}(), {closure#6}() (+2 more)

### Community 178 - "PaperEngineTest.php"
Cohesion: 0.16
Nodes (17): {closure#1}(), {closure#2}(), {closure#3}(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}() (+9 more)

### Community 181 - "SettingsScreenTest.php"
Cohesion: 0.09
Nodes (13): User, UserFactory, {closure#10}(), {closure#11}(), {closure#13}(), {closure#14}(), {closure#3}(), {closure#4}() (+5 more)

### Community 182 - "Timeframe"
Cohesion: 0.11
Nodes (16): {closure#4}(), {closure#2}(), {closure#8}(), Timeframe, FifteenMinutes, FiveMinutes, FourHours, OneDay (+8 more)

### Community 183 - "App\Strategy\Dsl\Document\StrategySpec"
Cohesion: 0.35
Nodes (7): {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#7}(), readBackDocument(), readBackSpec()

### Community 189 - "MarketType"
Cohesion: 0.06
Nodes (31): {closure#1}(), BinanceEndpoints, BinanceRestClient, EpochTime, Json, Sleeper, SyncInstruments, InstrumentDefinition (+23 more)

### Community 191 - "Venue"
Cohesion: 0.07
Nodes (9): Venue, SimulatedVenue, ForwardingVenue, {closure#22}(), {closure#32}(), {closure#34}(), {closure#49}(), OverReportingVenue (+1 more)

### Community 192 - "Clock"
Cohesion: 0.12
Nodes (6): AuthenticateMcpToken, FundingRateRepository, ExportStrategies, GridBacktest, ImportStrategies, Clock

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
Cohesion: 0.10
Nodes (11): Fill, PerpPosition, Position, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#8}() (+3 more)

### Community 201 - ".decide"
Cohesion: 0.17
Nodes (5): DecisionLog, DecisionRecord, IntentRecord, {closure#1}(), DatabaseDecisionLog

### Community 203 - "RiskPolicy"
Cohesion: 0.15
Nodes (11): RiskProfile, Account, Benchmark, RiskPolicy, {closure#1}(), {closure#2}(), {closure#3}(), documentedLimits() (+3 more)

### Community 204 - "LiveAccountTest.php"
Cohesion: 0.14
Nodes (18): ExchangeKey, approveLive(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}() (+10 more)

### Community 205 - "2026_11_09_000015_create_scalping_tables.php"
Cohesion: 0.33
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

### Community 207 - "Percent"
Cohesion: 0.24
Nodes (3): Percent, {closure#14}(), {closure#15}()

### Community 208 - "ChartSeries.php"
Cohesion: 0.40
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

### Community 209 - "AccountRuntime"
Cohesion: 0.11
Nodes (3): AccountRuntime, RunEngine, WarmingVenue

### Community 214 - "VenuePosition"
Cohesion: 0.31
Nodes (4): VenuePosition, {closure#1}(), SimulatedPosition, {closure#13}()

### Community 218 - ".number"
Cohesion: 0.29
Nodes (4): {closure#4}(), {closure#40}(), {closure#41}(), {closure#57}()

## Knowledge Gaps
- **148 isolated node(s):** `remove`, `fillHistory`, `Exit`, `Indicator`, `Order` (+143 more)
  These have ≤1 connection - possible missing edges. (Counts symbols only; 942 node(s) total have ≤1 connection when file, concept and rationale nodes are included.)
- **50 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `Decimal` connect `Decimal` to `CompiledStrategyTest.php`, `engineHarness`, `CanonicalJson`, `Engine`, `.parse`, `DocumentChecker`, `Side`, `.of`, `Candle`, `App\Support\Clock\Clock`, `.isGreaterThan`, `Statistics`, `DecimalMath`, `PolicyLimits`, `App\Market\Data\MarketType`, `Ledger`, `Symbol`, `RejectionReason`, `Price`, `.of`, `App\Market\Data\FundingRate`, `EnginePerpTest.php`, `SimClock`, `RiskState`, `OrderManagerTest.php`, `BacktestTest.php`, `App\Strategy\Dsl\Document\DocumentChecker`, `.zero`, `Timeframe`, `App\Market\FundingRateRepository`, `DonchianBreakout`, `.__construct`, `BinanceRestClientTest.php`, `InvalidArgumentException`, `SizedStrategyTest.php`, `App\Strategy\Dsl\Document\StrategySpec`, `IndicatorsTest.php`, `RunBacktestCommandTest.php`, `MarketType`, `IndicatorReferenceTest.php`, `Venue`, `.run`, `App\Strategy\Dsl\Document\DocumentChecker`, `PreparesBacktests`, `CandleFeedTest.php`, `FundingCarry`, `.plus`, `RiskPolicy`, `.measure`, `Percent`, `ChartSeries.php`, `AccountRuntime`, `DecimalMath`, `MonteCarlo`, `App\Support\Number\Decimal`, `.handle`, `VenuePosition`, `DocumentCheckerTest.php`, `Engine.php`, `.fromStrings`, `StrategyGates.php`, `RulesHistory`, `ChartSeriesTest.php`?**
  _High betweenness centrality (0.151) - this node is a cross-community bridge._
- **What connects `remove`, `fillHistory`, `Exit` to the rest of the system?**
  _148 weakly-connected nodes found - possible missing edges._
- **Should `engineHarness` be split into smaller, more focused modules?**
  _Cohesion score 0.09573412698412699 - nodes in this community are weakly interconnected._
- **Why does `Symbol` connect `Symbol` to `CompiledStrategyTest.php`, `engineHarness`, `CanonicalJson`, `Engine`, `.parse`, `DocumentChecker`, `Side`, `MovingAverageTrend`, `App\Support\Clock\Clock`, `ExchangeVerificationTest.php`, `PolicyLimits`, `CandleRepositoryTest.php`, `Instrument`, `Ledger`, `RejectionReason`, `Price`, `.of`, `EnginePerpTest.php`, `SimClock`, `OrderManagerTest.php`, `BacktestTest.php`, `Decimal`, `.zero`, `DonchianBreakout`, `BinanceRestClientTest.php`, `EnterOnceStrategy`, `InvalidArgumentException`, `App\Strategy\Dsl\Document\StrategySpec`, `RunBacktestCommandTest.php`, `MarketType`, `Venue`, `.run`, `App\Strategy\Dsl\Document\DocumentChecker`, `PreparesBacktests`, `CandleFeedTest.php`, `SettingsScreen`, `.plus`, `.decide`, `FundingCarry`, `.measure`, `AccountRuntime`, `.handle`, `VenuePosition`, `StrategyContext`, `Engine.php`, `AppServiceProvider.php`, `StrategyGates.php`, `RulesHistory`, `ChartSeriesTest.php`, `.register`?**
  _High betweenness centrality (0.041) - this node is a cross-community bridge._
- **Should `DslError` be split into smaller, more focused modules?**
  _Cohesion score 0.10931174089068826 - nodes in this community are weakly interconnected._
- **Why does `Candle` connect `Candle` to `CompiledStrategyTest.php`, `engineHarness`, `Engine`, `.parse`, `Side`, `ExchangeVerificationTest.php`, `Statistics`, `.isGreaterThan`, `CandleRepositoryTest.php`, `Instrument`, `Ledger`, `Carbon\CarbonImmutable`, `Price`, `App\Market\Data\FundingRate`, `EnginePerpTest.php`, `OrderManagerTest.php`, `BacktestTest.php`, `Decimal`, `.zero`, `Timeframe`, `DonchianBreakout`, `.__construct`, `Timeframe`, `IndicatorsTest.php`, `IndicatorReferenceTest.php`, `MarketType`, `App\Support\Clock\Clock`, `.timestamp`, `.run`, `CandleFeedTest.php`, `.plus`, `ChartSeries.php`, `App\Support\Number\Decimal`, `.fromStrings`, `StrategyGates.php`, `RulesHistory`, `ChartSeriesTest.php`?**
  _High betweenness centrality (0.019) - this node is a cross-community bridge._
- **Should `Engine` be split into smaller, more focused modules?**
  _Cohesion score 0.11236802413273002 - nodes in this community are weakly interconnected._