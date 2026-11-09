# Graph Report - trading-platform  (2026-11-09)

## Corpus Check
- 608 files · ~254,184 words
- Verdict: corpus is large enough that graph structure adds value.
- Unclassified: 101 file(s) not represented in the graph (top: .zip 38, .CHECKSUM 38, (none) 19)

## Summary
- 4883 nodes · 16780 edges · 221 communities (166 shown, 55 thin omitted)
- Extraction: 91% EXTRACTED · 9% INFERRED · 0% AMBIGUOUS · INFERRED: 1504 edges (avg confidence: 0.85)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `6a248b98`
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
- QualityCheck
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
- App\Strategy\Dsl\Document\DocumentChecker
- StrategyIdentity
- RejectionReason
- WarmingVenue
- Price
- .of
- SimulatedPerpVenueTest.php
- App\Market\Stream\PhrityTransport
- oi-momentum-long.strategy.yaml
- .of
- EnginePerpTest.php
- BinanceArchive
- App
- SimClock
- LadderTest.php
- OrderRole
- BacktestTest.php
- App\Strategy\Dsl\Document\DocumentChecker
- ema-cross-scalp-long.strategy.yaml
- Decimal
- .zero
- ScalpingArchiveTest.php
- Timeframe
- Illuminate\Database\Eloquent\Model
- package.json
- Strategy
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
- SimulationQueue
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
- SettingsScreenTest.php
- devDependencies
- BinanceVenueTest.php
- App\Strategy\Dsl\TypeChecker
- TradingTool
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
- Tokens
- settings-screen.blade.php
- 0001_01_01_000000_create_users_table.php
- steps
- 2026_11_09_000014_create_health_tables.php
- DecimalMath
- App\Market\Models\Instrument
- Statistics
- CandleRepositoryTest.php
- Illuminate\Database\Migrations\Migration
- 2026_11_09_000019_create_forming_candles_table.php
- Universe
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
- Backfill.php
- Illuminate\Foundation\Testing\RefreshDatabase
- ExitReason
- PaperEngineTest.php
- 2026_11_09_000012_create_account_results_tables.php
- .evaluate
- User
- Timeframe
- App\Strategy\Dsl\Document\StrategySpec
- Participant
- 2026_11_09_000013_add_daily_report_to_notification_settings.php
- MarketType
- TrackedOrder
- Venue
- .handle
- Asset
- 2026_11_09_000018_add_effective_at_to_deployments.php
- 2026_11_09_000016_create_market_universe_tables.php
- CandleStreamTest.php
- 2026_11_09_000017_create_live_tables.php
- start-local.ps1
- FundingCarry
- .plus
- .decide
- Laravel\Fortify\Contracts\TwoFactorAuthenticationProvider
- RiskPolicy
- LiveAccountTest.php
- 2026_11_09_000015_create_scalping_tables.php
- Ledger
- Percent
- RuntimeException
- AccountRuntime
- .parse
- Fill
- PerformanceMetrics
- OrderManagerTest.php
- VenuePosition
- .build
- CompiledStrategy
- InstrumentFilter
- .number
- DslReference
- EngineCommand

## God Nodes (most connected - your core abstractions)
1. `Decimal` - 514 edges
2. `Side` - 205 edges
3. `Symbol` - 201 edges
4. `App` - 141 edges
5. `Candle` - 136 edges
6. `Timeframe` - 121 edges
7. `Price` - 100 edges
8. `Position` - 96 edges
9. `Clock` - 77 edges
10. `SimClock` - 75 edges

## Surprising Connections (you probably didn't know these)
- `{closure#13}()` --calls--> `App`  [INFERRED]
  tests/Feature/Web/SettingsScreenTest.php → app/Notifications/SettingSource.php
- `{closure#8}()` --calls--> `App`  [INFERRED]
  tests/Feature/Web/SettingsScreenTest.php → app/Notifications/SettingSource.php
- `up()` --calls--> `App`  [INFERRED]
  database/migrations/2026_11_09_000016_create_market_universe_tables.php → app/Notifications/SettingSource.php
- `{closure#4}()` --calls--> `App`  [INFERRED]
  tests/Feature/Simulation/SimulationJobsTest.php → app/Notifications/SettingSource.php
- `{closure#3}()` --calls--> `App`  [INFERRED]
  tests/Feature/Execution/LiveAccountTest.php → app/Notifications/SettingSource.php

## Import Cycles
- None detected.

## Communities (221 total, 55 thin omitted)

### Community 0 - "CompiledStrategyTest.php"
Cohesion: 0.25
Nodes (9): LimitedStrategy, aggregatedBars(), {closure#11}(), {closure#13}(), {closure#16}(), {closure#17}(), {closure#8}(), compileDocument() (+1 more)

### Community 1 - "engineHarness"
Cohesion: 0.11
Nodes (37): ScriptedStrategy, {closure#1}(), {closure#10}(), {closure#13}(), {closure#14}(), {closure#16}(), {closure#17}(), {closure#18}() (+29 more)

### Community 2 - "CanonicalJson"
Cohesion: 0.18
Nodes (8): RunBacktest, CanonicalJson, {closure#1}(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}()

### Community 3 - "DslError"
Cohesion: 0.12
Nodes (12): {closure#1}(), {closure#8}(), DslError, YamlList, YamlReader, YamlScalar, YamlValue, {closure#1}() (+4 more)

### Community 4 - "Engine"
Cohesion: 0.14
Nodes (5): DecisionLog, EngineRecord, Engine, ReconciliationMismatch, PerpetualTracker

### Community 5 - ".parse"
Cohesion: 0.13
Nodes (28): {closure#17}(), {closure#4}(), {closure#5}(), {closure#7}(), carryDecisions(), {closure#13}(), {closure#14}(), {closure#15}() (+20 more)

### Community 7 - ".now"
Cohesion: 0.15
Nodes (7): {closure#1}(), {closure#2}(), Deployment, {closure#1}(), ReviewLadder, Ladder, LadderStatus

### Community 8 - "CompiledStrategy.php"
Cohesion: 0.14
Nodes (5): {closure#1}(), {closure#3}(), {closure#42}(), Compiler, Program

### Community 9 - "Side"
Cohesion: 0.17
Nodes (43): TradeMemory, Side, Buy, Sell, btc(), {closure#10}(), {closure#11}(), {closure#12}() (+35 more)

### Community 11 - ".of"
Cohesion: 0.10
Nodes (23): AssetMismatch, Money, {closure#2}(), {closure#1}(), usdt(), {closure#11}(), {closure#3}(), {closure#6}() (+15 more)

### Community 12 - "Candle"
Cohesion: 0.11
Nodes (33): {closure#1}(), {closure#3}(), Candle, InvalidCandle, {closure#1}(), {closure#2}(), candleWith(), {closure#1}() (+25 more)

### Community 13 - "ExchangeVerificationTest.php"
Cohesion: 0.12
Nodes (28): {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}() (+20 more)

### Community 14 - "Quantity"
Cohesion: 0.24
Nodes (3): Quantity, {closure#10}(), {closure#9}()

### Community 15 - "PolicyLimits"
Cohesion: 0.09
Nodes (15): InstrumentDirectory, StoredInstruments, {closure#1}(), CompileStrategy, PolicyLimits, StrategyRepository, {closure#1}(), {closure#2}() (+7 more)

### Community 16 - "Node"
Cohesion: 0.11
Nodes (21): Binary, Lexer, Parser, Token, TokenKind, Colon, Comma, Dot (+13 more)

### Community 17 - "Illuminate\Console\Command"
Cohesion: 0.08
Nodes (18): AuthenticateMcpToken, FlattenEngine, HaltEngine, IssuesCommands, PauseStrategy, ResumeEngine, CommandKind, Flatten (+10 more)

### Community 18 - "Instrument"
Cohesion: 0.07
Nodes (14): BackfillStep, CandleRepository, VerifyMarketData, CandleSource, Archive, Rest, Stream, UpsertResult (+6 more)

### Community 19 - "ScreensTest.php"
Cohesion: 0.12
Nodes (20): {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), chartLiveQuery(), {closure#10}(), {closure#11}() (+12 more)

### Community 20 - "LedgerTest.php"
Cohesion: 0.23
Nodes (25): balanceOf(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#15}(), {closure#16}() (+17 more)

### Community 21 - "Illuminate\Database\Schema\Blueprint"
Cohesion: 0.19
Nodes (10): {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#1}(), {closure#2}(), {closure#3}() (+2 more)

### Community 22 - "generate.py"
Cohesion: 0.07
Nodes (30): atr(), bars(), bb(), change(), dmi(), donchian(), ema(), highest() (+22 more)

### Community 23 - "App\Strategy\Dsl\Document\DocumentChecker"
Cohesion: 0.14
Nodes (10): {closure#6}(), {closure#9}(), {closure#5}(), {closure#7}(), {closure#9}(), EntrySpec, Expression, DslWriter (+2 more)

### Community 24 - "StrategyIdentity"
Cohesion: 0.05
Nodes (19): BuyAndHold, DonchianBreakout, BuildableStrategy, Close, EntryOrder, Intent, MoveStop, OpenCarry (+11 more)

### Community 25 - "RejectionReason"
Cohesion: 0.07
Nodes (29): AccountView, RejectionReason, BelowMinimum, Breaker, Cooldown, DuplicateIntent, EntryWorking, GrossExposure (+21 more)

### Community 27 - "Price"
Cohesion: 0.07
Nodes (29): Balance, ClientOrderId, Income, IncomeKind, Funding, Liquidation, OcoRequest, OrderAck (+21 more)

### Community 28 - ".of"
Cohesion: 0.17
Nodes (44): RiskState, StrategyLimits, account(), accountPolicy(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}() (+36 more)

### Community 29 - "SimulatedPerpVenueTest.php"
Cohesion: 0.19
Nodes (26): {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#15}(), {closure#18}(), {closure#19}() (+18 more)

### Community 30 - "App\Market\Stream\PhrityTransport"
Cohesion: 0.08
Nodes (6): Sleeper, PhrityTransport, StreamFailed, StreamTransport, FakeTransport, RecordingSleeper

### Community 31 - "oi-momentum-long.strategy.yaml"
Cohesion: 0.07
Nodes (27): entries, long, exits, stop_loss, take_profit, when, fails_when, family (+19 more)

### Community 32 - ".of"
Cohesion: 0.09
Nodes (28): {closure#3}(), {closure#3}(), balances(), {closure#44}(), {closure#45}(), {closure#46}(), {closure#47}(), {closure#48}() (+20 more)

### Community 33 - "EnginePerpTest.php"
Cohesion: 0.19
Nodes (18): Policies, {closure#10}(), {closure#11}(), {closure#14}(), {closure#2}(), {closure#5}(), {closure#6}(), {closure#7}() (+10 more)

### Community 34 - "BinanceArchive"
Cohesion: 0.14
Nodes (6): BinanceArchive, {closure#1}(), ExchangeNumber, TradeFlowBuilder, {closure#1}(), {closure#2}()

### Community 35 - "App"
Cohesion: 0.09
Nodes (27): SendTelegram, App, {closure#1}(), {closure#2}(), {closure#3}(), {closure#1}(), {closure#2}(), {closure#4}() (+19 more)

### Community 36 - "SimClock"
Cohesion: 0.10
Nodes (24): SimClock, WallClock, {closure#1}(), {closure#1}(), {closure#2}(), {closure#3}(), definition(), aggTrade() (+16 more)

### Community 37 - "LadderTest.php"
Cohesion: 0.15
Nodes (23): {closure#4}(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#5}(), {closure#6}(), {closure#7}(), reportAccount() (+15 more)

### Community 38 - "OrderRole"
Cohesion: 0.13
Nodes (9): OrderRole, Entry, Exit, Flatten, Stop, Target, ProtectionPlan, OrderManager (+1 more)

### Community 39 - "BacktestTest.php"
Cohesion: 0.15
Nodes (17): FeeSchedule, CostModel, CandleFeed, FundingRate, Backtest, {closure#1}(), RunDefinition, {closure#1}() (+9 more)

### Community 40 - "App\Strategy\Dsl\Document\DocumentChecker"
Cohesion: 0.06
Nodes (27): Argument, BoolLiteral, Call, DurationLiteral, Identifier, Lookback, Node, NumberLiteral (+19 more)

### Community 41 - "ema-cross-scalp-long.strategy.yaml"
Cohesion: 0.06
Nodes (33): entries, long, exits, stop_loss, take_profit, time_stop, when, fails_when (+25 more)

### Community 42 - "Decimal"
Cohesion: 0.04
Nodes (49): {closure#6}(), RoundTrip, {closure#1}(), {closure#2}(), {closure#3}(), {closure#10}(), {closure#11}(), {closure#12}() (+41 more)

### Community 43 - ".zero"
Cohesion: 0.09
Nodes (9): Liquidity, Maker, Taker, {closure#2}(), SimulatedFuturesBook, SimulatedOrder, {closure#1}(), UpdateStream (+1 more)

### Community 44 - "ScalpingArchiveTest.php"
Cohesion: 0.11
Nodes (22): PriceMinute, TradeFlow, {closure#10}(), {closure#11}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}() (+14 more)

### Community 45 - "Timeframe"
Cohesion: 0.14
Nodes (19): CandleAggregator, CandleSequenceBroken, {closure#3}(), {closure#6}(), CandleFactory, aggregate(), {closure#1}(), {closure#10}() (+11 more)

### Community 46 - "Illuminate\Database\Eloquent\Model"
Cohesion: 0.06
Nodes (21): {closure#1}(), McpCall, {closure#5}(), AccountEquity, AccountSnapshot, AccountTrade, PlainDecimal, EngineCommandRow (+13 more)

### Community 47 - "package.json"
Cohesion: 0.15
Nodes (17): dependencies, codemirror@^6.0.2, @codemirror/lang-yaml@^6.1.3, @codemirror/language@^6.13.1, @lezer/highlight@^1.2.5, lightweight-charts@^5.2.1, optionalDependencies, @laravel/multiplex@^0.4.1 (+9 more)

### Community 48 - "Strategy"
Cohesion: 0.16
Nodes (20): Strategy, baselineDocument(), baselinePairs(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}() (+12 more)

### Community 49 - "BinanceRestClientTest.php"
Cohesion: 0.09
Nodes (27): {closure#3}(), {closure#4}(), {closure#6}(), queryOf(), binanceClient(), {closure#1}(), {closure#11}(), {closure#13}() (+19 more)

### Community 50 - "ema-cross-scalp-short.strategy.yaml"
Cohesion: 0.06
Nodes (33): entries, short, exits, stop_loss, take_profit, time_stop, when, fails_when (+25 more)

### Community 51 - "App\Livewire\SettingsScreen"
Cohesion: 0.08
Nodes (11): Account, JournalEntry, ReauthController, AccountScreen, HaltButton, {closure#1}(), ModeBadge, {closure#2}() (+3 more)

### Community 52 - "ReadBack"
Cohesion: 0.17
Nodes (3): {closure#7}(), {closure#6}(), ReadBack

### Community 53 - "Laravel\Mcp\Response"
Cohesion: 0.15
Nodes (5): ChartTool, {closure#1}(), GetCandles, GetIndicators, ChartSeries

### Community 54 - "postgres"
Cohesion: 0.10
Nodes (19): POSTGRES_DB, POSTGRES_PASSWORD, POSTGRES_USER, interval, retries, test, timeout, environment (+11 more)

### Community 55 - "InvalidArgumentException"
Cohesion: 0.09
Nodes (11): {closure#1}(), {closure#2}(), {closure#3}(), {closure#2}(), {closure#2}(), {closure#5}(), {closure#7}(), {closure#8}() (+3 more)

### Community 56 - "SizedStrategyTest.php"
Cohesion: 0.10
Nodes (13): SizedStrategy, {closure#1}(), {closure#3}(), {closure#2}(), {closure#1}(), {closure#2}(), __construct(), enterOnce() (+5 more)

### Community 57 - "donchian-breakout-short.strategy.yaml"
Cohesion: 0.07
Nodes (26): entries, short, default, range, step, default, range, step (+18 more)

### Community 58 - "IndicatorsTest.php"
Cohesion: 0.08
Nodes (23): Atr, Donchian, Ema, Keltner, Period, Sma, {closure#5}(), bar() (+15 more)

### Community 59 - "support.mjs"
Cohesion: 0.26
Nodes (9): @playwright/test, confirmed(), checkLayout(), EMAIL, freshCode(), PASSWORD, reauthenticate(), totp() (+1 more)

### Community 60 - "RunBacktestCommandTest.php"
Cohesion: 0.07
Nodes (33): BacktestRun, {closure#1}(), {closure#4}(), {closure#6}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}() (+25 more)

### Community 61 - "IndicatorReferenceTest.php"
Cohesion: 0.04
Nodes (32): BarIndicator, Bollinger, Change, DonchianChannel, Highest, Hma, Lowest, Macd (+24 more)

### Community 62 - "App\Support\Clock\Clock"
Cohesion: 0.07
Nodes (8): ChartLiveController, BinanceEndpoints, FormingCandles, Heartbeats, CandleStream, ScalpingCollector, HealthAlerts, LiveBar

### Community 63 - ".timestamp"
Cohesion: 0.13
Nodes (14): {closure#2}(), {closure#4}(), OpenInterest, {closure#2}(), DatabaseCandleFeed, {closure#1}(), Rows, {closure#1}() (+6 more)

### Community 64 - "healthyMachine"
Cohesion: 0.26
Nodes (11): {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}(), {closure#8}(), {closure#9}() (+3 more)

### Community 65 - "donchian-breakout.strategy.yaml"
Cohesion: 0.07
Nodes (26): entries, long, default, range, step, default, range, step (+18 more)

### Community 66 - ".run"
Cohesion: 0.09
Nodes (30): Report, MovingAverageTrend, btcMinutes(), {closure#1}(), {closure#2}(), {closure#3}(), referencePrice(), {closure#21}() (+22 more)

### Community 67 - "StrategyIdentity"
Cohesion: 0.18
Nodes (5): StrategyIdentity, RenamedStrategy, {closure#1}(), {closure#2}(), {closure#3}()

### Community 68 - "App\Strategy\Dsl\Document\DocumentChecker"
Cohesion: 0.07
Nodes (30): {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), ParameterSpec, FunctionSpec, Library, Scope (+22 more)

### Community 69 - "PreparesBacktests"
Cohesion: 0.16
Nodes (3): {closure#1}(), {closure#2}(), PreparesBacktests

### Community 70 - "CandleFeedTest.php"
Cohesion: 0.44
Nodes (11): {closure#1}(), {closure#12}(), {closure#13}(), {closure#2}(), {closure#4}(), {closure#5}(), {closure#8}(), {closure#9}() (+3 more)

### Community 71 - "SettingsScreen"
Cohesion: 0.11
Nodes (3): OpenAccount, StoreKeys, SettingsScreen

### Community 72 - "SimulationQueue"
Cohesion: 0.12
Nodes (5): RunBacktest, RunGrid, SimulationTool, SimulationJob, SimulationQueue

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
Cohesion: 0.17
Nodes (5): EquityTracker, Performance, {closure#1}(), {closure#2}(), {closure#5}()

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
Cohesion: 0.11
Nodes (20): Cscv, DeflatedSharpe, Normal, closeTo(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}() (+12 more)

### Community 82 - "MonteCarlo"
Cohesion: 0.15
Nodes (11): {closure#4}(), {closure#5}(), MonteCarlo, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}() (+3 more)

### Community 83 - "App\Support\Number\Decimal"
Cohesion: 0.07
Nodes (51): {closure#5}(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#15}() (+43 more)

### Community 84 - "pint/composer.json"
Cohesion: 0.20
Nodes (9): config, sort-packages, description, license, name, require, laravel/pint, php (+1 more)

### Community 85 - ".evaluate"
Cohesion: 0.24
Nodes (9): Gates, brokenGate(), {closure#1}(), {closure#3}(), {closure#4}(), {closure#6}(), {closure#7}(), gate() (+1 more)

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
Nodes (12): {closure#1}(), {closure#11}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#9}(), RiskEvent, RiskEventKind (+4 more)

### Community 91 - ".fromStrings"
Cohesion: 0.09
Nodes (13): {closure#9}(), RegimeLabels, DirectionalMovement, {closure#1}(), {closure#42}(), {closure#1}(), {closure#1}(), {closure#2}() (+5 more)

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
Cohesion: 0.08
Nodes (7): DiskUsage, {closure#1}(), HealthChecks, HealthResult, LocalDiskUsage, {closure#6}(), FakeDisk

### Community 97 - "jobs"
Cohesion: 0.25
Nodes (8): name, runs-on, name, runs-on, steps, jobs, analyse, audit

### Community 98 - ".parse"
Cohesion: 0.20
Nodes (10): AstPrinter, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), sexpr(), {closure#1}(), {closure#3}() (+2 more)

### Community 99 - "psr-4"
Cohesion: 0.40
Nodes (5): autoload, psr-4, App\\, Database\\Factories\\, Database\\Seeders\\

### Community 100 - "require"
Cohesion: 0.22
Nodes (9): require, bacon/bacon-qr-code, laravel/fortify, laravel/framework, laravel/mcp, laravel/tinker, livewire/livewire, php (+1 more)

### Community 102 - "AppServiceProvider.php"
Cohesion: 0.06
Nodes (14): {closure#4}(), Notifier, SystemSleeper, TelegramNotifier, AppServiceProvider, {closure#3}(), {closure#4}(), {closure#7}() (+6 more)

### Community 103 - "StrategyGates.php"
Cohesion: 0.07
Nodes (16): FundingRateRepository, {closure#2}(), {closure#3}(), {closure#4}(), {closure#6}(), {closure#8}(), {closure#2}(), GridPart (+8 more)

### Community 104 - "uses"
Cohesion: 0.21
Nodes (12): steps, name, runs-on, steps, frontend, lint, name, runs-on (+4 more)

### Community 105 - "SettingsScreenTest.php"
Cohesion: 0.22
Nodes (12): {closure#10}(), {closure#11}(), {closure#13}(), {closure#14}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}() (+4 more)

### Community 106 - "devDependencies"
Cohesion: 0.18
Nodes (12): devDependencies, concurrently@^10.0.3, laravel-vite-plugin@^3.1, @playwright/test@1.63.0, tailwindcss@^4.0.0, @tailwindcss/vite@^4.0.0, vite@^8.0.0, concurrently (+4 more)

### Community 108 - "BinanceVenueTest.php"
Cohesion: 0.08
Nodes (17): {closure#2}(), BinanceVenue, {closure#2}(), {closure#3}(), {closure#4}(), BinanceApiError, SignedBinanceClient, {closure#1}() (+9 more)

### Community 117 - "App\Strategy\Dsl\TypeChecker"
Cohesion: 0.06
Nodes (25): {closure#2}(), ChartScreen, {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), Group, Exit (+17 more)

### Community 118 - "TradingTool"
Cohesion: 0.10
Nodes (8): GetFunding, GetJob, GetStrategy, ListInstruments, ListTrials, SaveStrategy, TradingServer, TradingTool

### Community 119 - "RulesHistory"
Cohesion: 0.21
Nodes (13): InstrumentRules, {closure#1}(), RulesHistory, {closure#2}(), {closure#3}(), {closure#4}(), {closure#41}(), {closure#1}() (+5 more)

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
Cohesion: 0.35
Nodes (10): archiveBytes(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}() (+2 more)

### Community 124 - "App\Execution\Models\Account"
Cohesion: 0.11
Nodes (7): LiveGate, RunEngine, LiveApproval, {closure#3}(), {closure#5}(), SendDailyReport, DailyReport

### Community 125 - "Illuminate\Database\Migrations\Migration"
Cohesion: 0.15
Nodes (3): {closure#1}(), {closure#1}(), {closure#1}()

### Community 126 - "StrategyVersion"
Cohesion: 0.26
Nodes (4): {closure#1}(), ListStrategies, {closure#3}(), StrategyVersion

### Community 127 - ".register"
Cohesion: 0.10
Nodes (21): {closure#10}(), {closure#11}(), {closure#13}(), {closure#15}(), {closure#16}(), {closure#17}(), {closure#18}(), {closure#4}() (+13 more)

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
Cohesion: 0.27
Nodes (7): faded(), options(), priceChart(), token(), volumeColor(), dslEditor(), highlight

### Community 132 - "TelegramSettings"
Cohesion: 0.13
Nodes (6): NotificationSettings, SettingSource, Env, None, TelegramClient, TelegramSettings

### Community 133 - "Tokens"
Cohesion: 0.12
Nodes (4): CreateMcpToken, RevokeMcpToken, McpToken, Tokens

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
Cohesion: 0.13
Nodes (15): DecimalMath, InvalidNumber, {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}() (+7 more)

### Community 141 - "App\Market\Models\Instrument"
Cohesion: 0.22
Nodes (7): {closure#2}(), {closure#4}(), {closure#6}(), {closure#8}(), ScalpingRepository, {closure#1}(), {closure#2}()

### Community 142 - "Statistics"
Cohesion: 0.22
Nodes (12): Statistics, {closure#1}(), {closure#2}(), {closure#3}(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}() (+4 more)

### Community 143 - "CandleRepositoryTest.php"
Cohesion: 0.28
Nodes (11): candleStore(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}() (+3 more)

### Community 146 - "Universe"
Cohesion: 0.08
Nodes (10): BackfillReport, BackfillMarketData, SelectsInstruments, {closure#1}(), {closure#2}(), StreamCandles, {closure#1}(), {closure#2}() (+2 more)

### Community 147 - "scripts"
Cohesion: 0.50
Nodes (4): scripts, build, dev, e2e

### Community 148 - "0001_01_01_000002_create_jobs_table.php"
Cohesion: 0.33
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

### Community 151 - "Carbon\CarbonImmutable"
Cohesion: 0.16
Nodes (11): FormingCandle, MinuteCandles, BreakerEvent, MarketBreakers, {closure#14}(), {closure#3}(), {closure#7}(), breakerMinute() (+3 more)

### Community 157 - "strategies-screen.blade.php"
Cohesion: 0.33
Nodes (5): open, promote, promoteDeployment, run, save

### Community 159 - "extra"
Cohesion: 0.67
Nodes (3): extra, laravel, dont-discover

### Community 174 - "StrategyLibrary"
Cohesion: 0.18
Nodes (5): CompileStrategy, StrategiesScreen, StrategyLibrary, CheckResult, SaveResult

### Community 175 - "Backfill.php"
Cohesion: 0.09
Nodes (11): ArchivePeriod, Backfill, {closure#1}(), {closure#10}(), {closure#11}(), {closure#3}(), {closure#5}(), {closure#7}() (+3 more)

### Community 176 - "Illuminate\Foundation\Testing\RefreshDatabase"
Cohesion: 0.14
Nodes (10): DatabaseSeeder, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), scalpingBackfill() (+2 more)

### Community 177 - "ExitReason"
Cohesion: 0.21
Nodes (23): ExitReason, Flatten, Liquidation, Signal, Stop, Target, {closure#1}(), {closure#10}() (+15 more)

### Community 178 - "PaperEngineTest.php"
Cohesion: 0.19
Nodes (15): {closure#1}(), {closure#2}(), {closure#3}(), {closure#5}(), {closure#6}(), {closure#7}(), {closure#8}(), {closure#9}() (+7 more)

### Community 180 - ".evaluate"
Cohesion: 0.29
Nodes (7): WalkForward, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), wfSet()

### Community 181 - "User"
Cohesion: 0.09
Nodes (9): User, TerminalQrCode, UserFactory, {closure#3}(), {closure#4}(), {closure#5}(), expectedModules(), readColoured() (+1 more)

### Community 182 - "Timeframe"
Cohesion: 0.09
Nodes (19): {closure#2}(), {closure#4}(), CarryTemplate, {closure#2}(), {closure#8}(), Vwap, Timeframe, FifteenMinutes (+11 more)

### Community 183 - "App\Strategy\Dsl\Document\StrategySpec"
Cohesion: 0.35
Nodes (7): {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#7}(), readBackDocument(), readBackSpec()

### Community 184 - "Participant"
Cohesion: 0.16
Nodes (12): EngineMode, Backtest, Live, Paper, Testnet, InMemoryDecisionLog, {closure#1}(), {closure#2}() (+4 more)

### Community 189 - "MarketType"
Cohesion: 0.06
Nodes (29): BinanceRestClient, EpochTime, Json, RequestFailed, SyncInstruments, InstrumentDefinition, InstrumentStatus, Delisted (+21 more)

### Community 190 - "TrackedOrder"
Cohesion: 0.23
Nodes (9): TrackedOrder, {closure#1}(), {closure#2}(), OrderBook, {closure#1}(), {closure#11}(), {closure#12}(), {closure#13}() (+1 more)

### Community 191 - "Venue"
Cohesion: 0.09
Nodes (10): Venue, SimulatedVenue, ForwardingVenue, {closure#12}(), {closure#22}(), {closure#32}(), {closure#34}(), {closure#49}() (+2 more)

### Community 192 - ".handle"
Cohesion: 0.15
Nodes (4): GridBacktest, ReadsGridOptions, StrategyGates, StrategySpec

### Community 193 - "Asset"
Cohesion: 0.18
Nodes (4): Marks, Asset, {closure#25}(), {closure#9}()

### Community 195 - "2026_11_09_000016_create_market_universe_tables.php"
Cohesion: 0.40
Nodes (3): {closure#1}(), {closure#2}(), up()

### Community 196 - "CandleStreamTest.php"
Cohesion: 0.32
Nodes (12): {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#7}(), {closure#8}(), {closure#9}() (+4 more)

### Community 197 - "2026_11_09_000017_create_live_tables.php"
Cohesion: 0.33
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

### Community 198 - "start-local.ps1"
Cohesion: 0.27
Nodes (4): Stop-Postgres(), Stop-ProcessTree(), Test-PostgresRunning(), Write-Step()

### Community 200 - ".plus"
Cohesion: 0.12
Nodes (7): PerpPosition, {closure#1}(), {closure#2}(), Perpetual, {closure#8}(), {closure#16}(), {closure#6}()

### Community 201 - ".decide"
Cohesion: 0.14
Nodes (4): DecisionRecord, IntentRecord, {closure#1}(), DatabaseDecisionLog

### Community 202 - "Laravel\Fortify\Contracts\TwoFactorAuthenticationProvider"
Cohesion: 0.25
Nodes (4): ApproveLive, ConfirmsOperator, CreateAccount, CreateUser

### Community 203 - "RiskPolicy"
Cohesion: 0.17
Nodes (8): RiskProfile, Account, Benchmark, RiskPolicy, {closure#1}(), {closure#2}(), {closure#3}(), documentedLimits()

### Community 204 - "LiveAccountTest.php"
Cohesion: 0.22
Nodes (12): approveLive(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}() (+4 more)

### Community 205 - "2026_11_09_000015_create_scalping_tables.php"
Cohesion: 0.33
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

### Community 206 - "Ledger"
Cohesion: 0.30
Nodes (3): Ledger, LedgerViolation, {closure#12}()

### Community 207 - "Percent"
Cohesion: 0.24
Nodes (3): Percent, {closure#14}(), {closure#15}()

### Community 208 - "RuntimeException"
Cohesion: 0.14
Nodes (7): ReplayDivergence, ArchiveMissing, ChecksumMismatch, BinanceRequestFailed, BinanceUnreachable, LocationRestricted, RateLimited

### Community 210 - ".parse"
Cohesion: 0.25
Nodes (7): {closure#12}(), {closure#19}(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}()

### Community 213 - "OrderManagerTest.php"
Cohesion: 0.30
Nodes (14): btcUsdt(), {closure#10}(), {closure#15}(), {closure#16}(), {closure#2}(), {closure#4}(), {closure#5}(), {closure#6}() (+6 more)

### Community 214 - "VenuePosition"
Cohesion: 0.31
Nodes (4): VenuePosition, {closure#1}(), SimulatedPosition, {closure#13}()

### Community 218 - ".number"
Cohesion: 0.29
Nodes (4): {closure#4}(), {closure#40}(), {closure#41}(), {closure#57}()

## Knowledge Gaps
- **147 isolated node(s):** `remove`, `Backtest`, `Live`, `Paper`, `Testnet` (+142 more)
  These have ≤1 connection - possible missing edges. (Counts symbols only; 942 node(s) total have ≤1 connection when file, concept and rationale nodes are included.)
- **55 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `Decimal` connect `Decimal` to `CompiledStrategyTest.php`, `engineHarness`, `CanonicalJson`, `Engine`, `.parse`, `DocumentChecker`, `Side`, `QualityCheck`, `.of`, `Candle`, `App\Market\Models\Instrument`, `Statistics`, `DecimalMath`, `PolicyLimits`, `Quantity`, `LedgerTest.php`, `App\Strategy\Dsl\Document\DocumentChecker`, `StrategyIdentity`, `RejectionReason`, `WarmingVenue`, `Price`, `.of`, `.of`, `EnginePerpTest.php`, `SimClock`, `OrderRole`, `BacktestTest.php`, `App\Strategy\Dsl\Document\DocumentChecker`, `.zero`, `Timeframe`, `Backfill.php`, `Strategy`, `ExitReason`, `.evaluate`, `Timeframe`, `InvalidArgumentException`, `SizedStrategyTest.php`, `Participant`, `App\Strategy\Dsl\Document\StrategySpec`, `IndicatorsTest.php`, `RunBacktestCommandTest.php`, `MarketType`, `TrackedOrder`, `IndicatorReferenceTest.php`, `.handle`, `Asset`, `.run`, `Venue`, `App\Strategy\Dsl\Document\DocumentChecker`, `PreparesBacktests`, `CandleFeedTest.php`, `FundingCarry`, `.plus`, `RiskPolicy`, `.measure`, `Ledger`, `Percent`, `DecimalMath`, `MonteCarlo`, `Fill`, `PerformanceMetrics`, `.evaluate`, `VenuePosition`, `App\Support\Number\Decimal`, `OrderManagerTest.php`, `DocumentCheckerTest.php`, `Engine.php`, `.fromStrings`, `StrategyGates.php`, `BinanceVenueTest.php`, `App\Strategy\Dsl\TypeChecker`, `RulesHistory`, `ChartSeriesTest.php`?**
  _High betweenness centrality (0.121) - this node is a cross-community bridge._
- **What connects `remove`, `Backtest`, `Live` to the rest of the system?**
  _147 weakly-connected nodes found - possible missing edges._
- **Should `engineHarness` be split into smaller, more focused modules?**
  _Cohesion score 0.10753945061367622 - nodes in this community are weakly interconnected._
- **Why does `Symbol` connect `StrategyIdentity` to `CompiledStrategyTest.php`, `engineHarness`, `CanonicalJson`, `Engine`, `.parse`, `DocumentChecker`, `Side`, `.of`, `App\Market\Models\Instrument`, `ExchangeVerificationTest.php`, `PolicyLimits`, `CandleRepositoryTest.php`, `Universe`, `Instrument`, `LedgerTest.php`, `App\Strategy\Dsl\Document\DocumentChecker`, `WarmingVenue`, `Price`, `.of`, `EnginePerpTest.php`, `SimClock`, `OrderRole`, `BacktestTest.php`, `Decimal`, `.zero`, `Strategy`, `ExitReason`, `Laravel\Mcp\Response`, `Timeframe`, `InvalidArgumentException`, `Participant`, `App\Strategy\Dsl\Document\StrategySpec`, `RunBacktestCommandTest.php`, `MarketType`, `TrackedOrder`, `.timestamp`, `Venue`, `Asset`, `.handle`, `.run`, `CandleFeedTest.php`, `SettingsScreen`, `.plus`, `.decide`, `FundingCarry`, `Ledger`, `.parse`, `Fill`, `OrderManagerTest.php`, `VenuePosition`, `Engine.php`, `StrategyGates.php`, `BinanceVenueTest.php`, `RulesHistory`, `ChartSeriesTest.php`, `.register`?**
  _High betweenness centrality (0.042) - this node is a cross-community bridge._
- **Should `DslError` be split into smaller, more focused modules?**
  _Cohesion score 0.11711711711711711 - nodes in this community are weakly interconnected._
- **Why does `Side` connect `Side` to `CompiledStrategyTest.php`, `Engine`, `.parse`, `.of`, `LedgerTest.php`, `App\Strategy\Dsl\Document\DocumentChecker`, `StrategyIdentity`, `RejectionReason`, `Price`, `SimulatedPerpVenueTest.php`, `.of`, `EnginePerpTest.php`, `OrderRole`, `BacktestTest.php`, `Decimal`, `.zero`, `ExitReason`, `ReadBack`, `Venue`, `Asset`, `.run`, `FundingCarry`, `.plus`, `.measure`, `Fill`, `OrderManagerTest.php`, `VenuePosition`, `DocumentCheckerTest.php`, `Engine.php`, `BinanceVenueTest.php`?**
  _High betweenness centrality (0.024) - this node is a cross-community bridge._
- **Should `Engine` be split into smaller, more focused modules?**
  _Cohesion score 0.13842746400885936 - nodes in this community are weakly interconnected._