# Graph Report - trading-platform  (2026-11-09)

## Corpus Check
- 600 files · ~248,114 words
- Verdict: corpus is large enough that graph structure adds value.
- Unclassified: 101 file(s) not represented in the graph (top: .zip 38, .CHECKSUM 38, (none) 19)

## Summary
- 4815 nodes · 16579 edges · 201 communities (149 shown, 52 thin omitted)
- Extraction: 91% EXTRACTED · 9% INFERRED · 0% AMBIGUOUS · INFERRED: 1470 edges (avg confidence: 0.85)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `46813e2b`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- .of
- engineHarness
- CanonicalJson
- DslError
- Engine
- .parse
- DocumentChecker
- SettingsScreen
- CompiledStrategy.php
- Side
- Illuminate\Database\ConnectionInterface
- .of
- Candle
- ExchangeVerificationTest.php
- IndicatorsTest.php
- PolicyLimits
- Node
- Clock
- Instrument
- ScreensTest.php
- Side
- Illuminate\Database\Schema\Blueprint
- generate.py
- ReadBack
- Symbol
- .of
- WarmingVenue
- Engine.php
- App\Market\Data\MarketType
- InstrumentStatus
- App\Market\Stream\PhrityTransport
- oi-momentum-long.strategy.yaml
- SimulatedPerpVenueTest.php
- .now
- BinanceArchive
- App
- Illuminate\Foundation\Testing\RefreshDatabase
- BackfillTest.php
- EngineTest.php
- BacktestTest.php
- App\Strategy\Dsl\Document\DocumentChecker
- ema-cross-scalp-long.strategy.yaml
- Decimal
- SimulatedVenue
- ScalpingArchiveTest.php
- Timeframe
- Illuminate\Database\Eloquent\Model
- editor.js
- Strategy
- BinanceRestClientTest.php
- ema-cross-scalp-short.strategy.yaml
- Illuminate\Database\ConnectionInterface
- OrderManagerTest.php
- Laravel\Mcp\Response
- postgres
- InvalidArgumentException
- SizedStrategyTest.php
- donchian-breakout-short.strategy.yaml
- IndicatorReferenceTest.php
- support.mjs
- RunBacktestCommandTest.php
- DecimalMath
- App\Support\Clock\Clock
- User
- RiskPolicy
- donchian-breakout.strategy.yaml
- .run
- StrategyIdentity
- App\Strategy\Dsl\Document\DocumentChecker
- InvalidArgumentException
- OrderRole
- .evaluate
- SimulationQueue
- scripts
- ma-trend-short.strategy.yaml
- OrderUpdate
- .measure
- composer.json
- jobs
- postgres
- ma-trend.strategy.yaml
- DecimalMath
- MonteCarlo
- App\Support\Number\Decimal
- pint/composer.json
- .register
- RunSimulation.php
- CandleFeedTest.php
- require-dev
- scripts-descriptions
- .evaluate
- .fromStrings
- config
- uses
- test
- with
- .parse
- jobs
- .parse
- psr-4
- require
- CompiledStrategyTest.php
- StrategyGates.php
- uses
- SettingsScreenTest.php
- devDependencies
- ClientOrderId
- ChartScreen.php
- TradingTool
- DecisionRecord
- buy-and-hold.strategy.yaml
- ChartSeriesTest.php
- TokensTest.php
- ArchiveImportTest.php
- bootstrap/app.php
- Illuminate\Database\Migrations\Migration
- TradeFlowBuilder
- SimClock
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
- .parse
- ForwardingVenue
- CandleRepositoryTest.php
- Illuminate\Support\Facades\Schema
- ArchivePeriod
- AppServiceProvider.php
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
- App\Agents\Mcp\TradingServer
- Carbon\CarbonImmutable
- UpsertResult
- GridWorkersTest.php
- App\Agents\Mcp\Tools\ListTrials
- 2026_11_09_000012_create_account_results_tables.php
- EngineCommand
- EngineMode
- .__construct
- SimulationJobsTest.php
- BinanceRequestFailed
- 2026_11_09_000013_add_daily_report_to_notification_settings.php
- MarketType
- MoveStop
- VerificationSource
- .handle
- IntentRecord
- 2026_11_09_000018_add_effective_at_to_deployments.php
- 2026_11_09_000016_create_market_universe_tables.php
- 2026_11_09_000017_create_live_tables.php
- RulesHistory
- .times
- InstrumentRules
- 2026_11_09_000015_create_scalping_tables.php

## God Nodes (most connected - your core abstractions)
1. `Decimal` - 516 edges
2. `Side` - 205 edges
3. `Symbol` - 204 edges
4. `Candle` - 136 edges
5. `App` - 134 edges
6. `Timeframe` - 123 edges
7. `Price` - 100 edges
8. `Position` - 96 edges
9. `Clock` - 81 edges
10. `SimClock` - 76 edges

## Surprising Connections (you probably didn't know these)
- `{closure#4}()` --calls--> `App`  [INFERRED]
  tests/Feature/Simulation/SimulationJobsTest.php → app/Notifications/SettingSource.php
- `{closure#3}()` --calls--> `App`  [INFERRED]
  tests/Feature/Network/BinancePublicDataTest.php → app/Notifications/SettingSource.php
- `{closure#5}()` --calls--> `App`  [INFERRED]
  tests/Feature/Network/BinancePublicDataTest.php → app/Notifications/SettingSource.php
- `{closure#1}()` --calls--> `App`  [INFERRED]
  tests/Feature/Support/ClockBindingTest.php → app/Notifications/SettingSource.php
- `up()` --calls--> `App`  [INFERRED]
  database/migrations/2026_11_09_000016_create_market_universe_tables.php → app/Notifications/SettingSource.php

## Import Cycles
- None detected.

## Communities (201 total, 52 thin omitted)

### Community 0 - ".of"
Cohesion: 0.12
Nodes (25): {closure#1}(), {closure#5}(), {closure#44}(), {closure#45}(), {closure#46}(), {closure#47}(), {closure#48}(), {closure#1}() (+17 more)

### Community 1 - "engineHarness"
Cohesion: 0.21
Nodes (27): ScriptedStrategy, {closure#10}(), {closure#13}(), {closure#16}(), {closure#17}(), {closure#2}(), {closure#20}(), {closure#21}() (+19 more)

### Community 2 - "CanonicalJson"
Cohesion: 0.30
Nodes (6): CanonicalJson, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}()

### Community 3 - "DslError"
Cohesion: 0.20
Nodes (7): DslError, YamlList, YamlReader, {closure#1}(), {closure#2}(), {closure#4}(), plainYaml()

### Community 4 - "Engine"
Cohesion: 0.14
Nodes (5): EngineRecord, Engine, ReconciliationMismatch, {closure#3}(), {closure#1}()

### Community 5 - ".parse"
Cohesion: 0.11
Nodes (35): baselinePairs(), {closure#17}(), carryDecisions(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}() (+27 more)

### Community 6 - "DocumentChecker"
Cohesion: 0.31
Nodes (4): DocumentChecker, YamlMap, YamlScalar, YamlValue

### Community 7 - "SettingsScreen"
Cohesion: 0.06
Nodes (9): OpenAccount, StoreKeys, AccountSnapshot, ReauthController, AccountScreen, HaltButton, {closure#1}(), ModeBadge (+1 more)

### Community 8 - "CompiledStrategy.php"
Cohesion: 0.11
Nodes (5): {closure#1}(), CompiledStrategy, {closure#42}(), Compiler, Program

### Community 9 - "Side"
Cohesion: 0.17
Nodes (42): btc(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#15}(), {closure#16}(), {closure#17}() (+34 more)

### Community 10 - "Illuminate\Database\ConnectionInterface"
Cohesion: 0.07
Nodes (17): {closure#2}(), {closure#4}(), {closure#2}(), DatabaseCandleFeed, {closure#1}(), FundingRateRepository, Heartbeats, Rows (+9 more)

### Community 11 - ".of"
Cohesion: 0.05
Nodes (28): {closure#2}(), Balance, Asset, AssetMismatch, Money, Percent, Quantity, {closure#16}() (+20 more)

### Community 12 - "Candle"
Cohesion: 0.11
Nodes (35): {closure#1}(), {closure#3}(), Candle, KlineMessage, {closure#9}(), {closure#1}(), {closure#2}(), {closure#1}() (+27 more)

### Community 13 - "ExchangeVerificationTest.php"
Cohesion: 0.12
Nodes (28): {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}() (+20 more)

### Community 14 - "IndicatorsTest.php"
Cohesion: 0.09
Nodes (22): Atr, Donchian, Ema, Period, Sma, bar(), {closure#1}(), {closure#10}() (+14 more)

### Community 15 - "PolicyLimits"
Cohesion: 0.06
Nodes (34): {closure#1}(), {closure#3}(), InstrumentDirectory, StoredInstruments, CompileStrategy, CheckResult, PolicyLimits, SaveResult (+26 more)

### Community 16 - "Node"
Cohesion: 0.11
Nodes (21): Binary, Lexer, Parser, Token, TokenKind, Colon, Comma, Dot (+13 more)

### Community 17 - "Clock"
Cohesion: 0.06
Nodes (24): CreateMcpToken, RevokeMcpToken, AuthenticateMcpToken, Tokens, ApproveLive, ConfirmsOperator, CreateAccount, FlattenEngine (+16 more)

### Community 18 - "Instrument"
Cohesion: 0.11
Nodes (8): CandleRepository, CandleSource, Archive, Rest, Stream, Instrument, ExchangeVerification, VerificationReport

### Community 19 - "ScreensTest.php"
Cohesion: 0.12
Nodes (13): DatabaseSeeder, {closure#1}(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#7}() (+5 more)

### Community 20 - "Side"
Cohesion: 0.08
Nodes (62): ExitReason, Flatten, Liquidation, Signal, Stop, Target, Marks, Ledger (+54 more)

### Community 21 - "Illuminate\Database\Schema\Blueprint"
Cohesion: 0.19
Nodes (10): {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#1}(), {closure#2}(), {closure#3}() (+2 more)

### Community 22 - "generate.py"
Cohesion: 0.07
Nodes (30): atr(), bars(), bb(), change(), dmi(), donchian(), ema(), highest() (+22 more)

### Community 23 - "ReadBack"
Cohesion: 0.08
Nodes (14): {closure#2}(), {closure#3}(), {closure#6}(), {closure#7}(), {closure#8}(), {closure#9}(), {closure#1}(), {closure#2}() (+6 more)

### Community 24 - "Symbol"
Cohesion: 0.04
Nodes (19): ProtectionPlan, VenueFill, VenueOrder, VenuePosition, {closure#2}(), {closure#2}(), {closure#3}(), {closure#4}() (+11 more)

### Community 25 - ".of"
Cohesion: 0.18
Nodes (44): RiskState, StrategyLimits, account(), accountPolicy(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}() (+36 more)

### Community 27 - "Engine.php"
Cohesion: 0.09
Nodes (12): {closure#1}(), {closure#11}(), {closure#12}(), {closure#2}(), {closure#3}(), {closure#9}(), RiskEvent, RiskEventKind (+4 more)

### Community 28 - "App\Market\Data\MarketType"
Cohesion: 0.06
Nodes (13): BackfillReport, BackfillMarketData, SelectsInstruments, {closure#1}(), {closure#2}(), StreamCandles, {closure#1}(), {closure#2}() (+5 more)

### Community 29 - "InstrumentStatus"
Cohesion: 0.10
Nodes (13): SyncInstruments, InstrumentDefinition, InstrumentStatus, Delisted, Halted, Trading, {closure#1}(), InstrumentSync (+5 more)

### Community 30 - "App\Market\Stream\PhrityTransport"
Cohesion: 0.12
Nodes (3): PhrityTransport, StreamFailed, FakeTransport

### Community 31 - "oi-momentum-long.strategy.yaml"
Cohesion: 0.07
Nodes (27): entries, long, exits, stop_loss, take_profit, when, fails_when, family (+19 more)

### Community 32 - "SimulatedPerpVenueTest.php"
Cohesion: 0.18
Nodes (27): {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#15}(), {closure#18}() (+19 more)

### Community 33 - ".now"
Cohesion: 0.05
Nodes (15): AccountRuntime, LiveGate, {closure#4}(), {closure#5}(), RunEngine, Notifier, EngineCommandRow, LiveApproval (+7 more)

### Community 35 - "App"
Cohesion: 0.09
Nodes (38): SendTelegram, App, {closure#3}(), {closure#2}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#4}() (+30 more)

### Community 36 - "Illuminate\Foundation\Testing\RefreshDatabase"
Cohesion: 0.08
Nodes (20): ExchangeKey, approveLive(), {closure#2}(), {closure#4}(), {closure#5}(), {closure#8}(), {closure#9}(), fakeLiveBinance() (+12 more)

### Community 37 - "BackfillTest.php"
Cohesion: 0.08
Nodes (24): {closure#6}(), {closure#7}(), {closure#11}(), {closure#13}(), {closure#14}(), {closure#15}(), {closure#16}(), {closure#4}() (+16 more)

### Community 38 - "EngineTest.php"
Cohesion: 0.09
Nodes (9): Venue, {closure#12}(), {closure#22}(), {closure#27}(), {closure#32}(), {closure#34}(), {closure#49}(), EngineHarness (+1 more)

### Community 39 - "BacktestTest.php"
Cohesion: 0.10
Nodes (22): FeeSchedule, CostModel, CandleFeed, FundingRate, Backtest, BacktestResult, {closure#1}(), RunDefinition (+14 more)

### Community 40 - "App\Strategy\Dsl\Document\DocumentChecker"
Cohesion: 0.06
Nodes (25): Argument, BoolLiteral, Call, DurationLiteral, Identifier, Lookback, Node, NumberLiteral (+17 more)

### Community 41 - "ema-cross-scalp-long.strategy.yaml"
Cohesion: 0.06
Nodes (33): entries, long, exits, stop_loss, take_profit, time_stop, when, fails_when (+25 more)

### Community 42 - "Decimal"
Cohesion: 0.04
Nodes (46): {closure#6}(), {closure#1}(), {closure#2}(), RoundTrip, {closure#1}(), {closure#2}(), {closure#3}(), {closure#10}() (+38 more)

### Community 43 - "SimulatedVenue"
Cohesion: 0.11
Nodes (15): Liquidity, Maker, Taker, OrderStatus, Cancelled, Expired, Filled, New (+7 more)

### Community 44 - "ScalpingArchiveTest.php"
Cohesion: 0.12
Nodes (20): PriceMinute, TradeFlow, {closure#10}(), {closure#11}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}() (+12 more)

### Community 45 - "Timeframe"
Cohesion: 0.08
Nodes (35): CandleAggregator, CandleSequenceBroken, {closure#2}(), {closure#4}(), {closure#2}(), {closure#8}(), Timeframe, FifteenMinutes (+27 more)

### Community 46 - "Illuminate\Database\Eloquent\Model"
Cohesion: 0.04
Nodes (39): McpCall, McpToken, Account, AccountEquity, AccountTrade, PlainDecimal, JournalEntry, MarketJob (+31 more)

### Community 47 - "editor.js"
Cohesion: 0.19
Nodes (12): dependencies, codemirror@^6.0.2, @codemirror/lang-yaml@^6.1.3, @codemirror/language@^6.13.1, @lezer/highlight@^1.2.5, codemirror, @codemirror/lang-yaml, @codemirror/language (+4 more)

### Community 48 - "Strategy"
Cohesion: 0.16
Nodes (18): {closure#2}(), Strategy, baselineDocument(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#14}() (+10 more)

### Community 49 - "BinanceRestClientTest.php"
Cohesion: 0.14
Nodes (17): Sleeper, SystemSleeper, {closure#9}(), binanceClient(), {closure#1}(), {closure#11}(), {closure#12}(), {closure#13}() (+9 more)

### Community 50 - "ema-cross-scalp-short.strategy.yaml"
Cohesion: 0.06
Nodes (33): entries, short, exits, stop_loss, take_profit, time_stop, when, fails_when (+25 more)

### Community 52 - "OrderManagerTest.php"
Cohesion: 0.30
Nodes (14): btcUsdt(), {closure#10}(), {closure#15}(), {closure#16}(), {closure#2}(), {closure#4}(), {closure#5}(), {closure#6}() (+6 more)

### Community 53 - "Laravel\Mcp\Response"
Cohesion: 0.13
Nodes (6): ChartTool, {closure#1}(), GetCandles, GetFunding, GetIndicators, ChartSeries

### Community 54 - "postgres"
Cohesion: 0.10
Nodes (19): POSTGRES_DB, POSTGRES_PASSWORD, POSTGRES_USER, interval, retries, test, timeout, environment (+11 more)

### Community 55 - "InvalidArgumentException"
Cohesion: 0.08
Nodes (9): {closure#1}(), SimulatedFuturesBook, UpdateStream, {closure#1}(), {closure#3}(), {closure#5}(), {closure#1}(), {closure#2}() (+1 more)

### Community 56 - "SizedStrategyTest.php"
Cohesion: 0.08
Nodes (16): SizedStrategy, LimitedStrategy, EnterOnceStrategy, Policies, {closure#1}(), {closure#2}(), __construct(), enterOnce() (+8 more)

### Community 57 - "donchian-breakout-short.strategy.yaml"
Cohesion: 0.07
Nodes (26): entries, short, default, range, step, default, range, step (+18 more)

### Community 58 - "IndicatorReferenceTest.php"
Cohesion: 0.03
Nodes (35): {closure#54}(), BarIndicator, Bollinger, Change, DonchianChannel, Highest, Hma, Keltner (+27 more)

### Community 59 - "support.mjs"
Cohesion: 0.26
Nodes (9): @playwright/test, confirmed(), checkLayout(), EMAIL, freshCode(), PASSWORD, reauthenticate(), totp() (+1 more)

### Community 60 - "RunBacktestCommandTest.php"
Cohesion: 0.14
Nodes (28): BacktestRun, {closure#1}(), {closure#4}(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}() (+20 more)

### Community 61 - "DecimalMath"
Cohesion: 0.11
Nodes (24): Statistics, {closure#3}(), DecimalMath, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}() (+16 more)

### Community 62 - "App\Support\Clock\Clock"
Cohesion: 0.07
Nodes (11): {closure#1}(), {closure#2}(), Deployment, {closure#1}(), {closure#2}(), StrategiesScreen, {closure#1}(), InstrumentFilter (+3 more)

### Community 63 - "User"
Cohesion: 0.06
Nodes (5): User, AppServiceProvider, FortifyServiceProvider, UserFactory, {closure#1}()

### Community 64 - "RiskPolicy"
Cohesion: 0.15
Nodes (11): RiskProfile, Account, Benchmark, RiskPolicy, {closure#1}(), {closure#2}(), {closure#3}(), documentedLimits() (+3 more)

### Community 65 - "donchian-breakout.strategy.yaml"
Cohesion: 0.07
Nodes (26): entries, long, default, range, step, default, range, step (+18 more)

### Community 66 - ".run"
Cohesion: 0.09
Nodes (27): Participant, PerformanceMetrics, TradeStatistics, Report, btcMinutes(), {closure#1}(), {closure#2}(), referencePrice() (+19 more)

### Community 67 - "StrategyIdentity"
Cohesion: 0.06
Nodes (12): BuyAndHold, FundingCarry, MovingAverageTrend, BuildableStrategy, StrategyIdentity, Subscription, RenamedStrategy, HoldStrategy (+4 more)

### Community 68 - "App\Strategy\Dsl\Document\DocumentChecker"
Cohesion: 0.07
Nodes (35): {closure#1}(), {closure#1}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#8}(), {closure#9}(), ParameterSpec (+27 more)

### Community 69 - "InvalidArgumentException"
Cohesion: 0.13
Nodes (3): PreparesBacktests, PromoteStrategy, RunBacktest

### Community 70 - "OrderRole"
Cohesion: 0.12
Nodes (17): OrderRole, Entry, Exit, Flatten, Stop, Target, TrackedOrder, {closure#1}() (+9 more)

### Community 71 - ".evaluate"
Cohesion: 0.24
Nodes (9): Gates, brokenGate(), {closure#1}(), {closure#3}(), {closure#4}(), {closure#6}(), {closure#7}(), gate() (+1 more)

### Community 72 - "SimulationQueue"
Cohesion: 0.18
Nodes (4): RunBacktest, RunGrid, SimulationTool, SimulationQueue

### Community 73 - "scripts"
Cohesion: 0.15
Nodes (13): scripts, analyse, dev, format, mutate, post-autoload-dump, post-update-cmd, pre-package-uninstall (+5 more)

### Community 74 - "ma-trend-short.strategy.yaml"
Cohesion: 0.07
Nodes (26): entries, short, exits, stop_loss, when, fails_when, family, default (+18 more)

### Community 75 - "OrderUpdate"
Cohesion: 0.24
Nodes (8): Income, IncomeKind, Funding, Liquidation, OrderUpdate, {closure#1}(), {closure#3}(), {closure#1}()

### Community 76 - ".measure"
Cohesion: 0.12
Nodes (13): ParticipantResult, EquityTracker, Performance, {closure#1}(), {closure#2}(), {closure#4}(), {closure#1}(), {closure#2}() (+5 more)

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
Nodes (22): {closure#7}(), Cscv, DeflatedSharpe, DirectionalMovement, Wilder, {closure#3}(), closeTo(), {closure#1}() (+14 more)

### Community 82 - "MonteCarlo"
Cohesion: 0.16
Nodes (10): {closure#4}(), MonteCarlo, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}() (+2 more)

### Community 83 - "App\Support\Number\Decimal"
Cohesion: 0.05
Nodes (66): {closure#8}(), {closure#3}(), Band, {closure#5}(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}() (+58 more)

### Community 84 - "pint/composer.json"
Cohesion: 0.20
Nodes (9): config, sort-packages, description, license, name, require, laravel/pint, php (+1 more)

### Community 85 - ".register"
Cohesion: 0.10
Nodes (24): {closure#1}(), {closure#1}(), {closure#1}(), {closure#2}(), {closure#4}(), {closure#5}(), {closure#6}(), streamMessages() (+16 more)

### Community 86 - "RunSimulation.php"
Cohesion: 0.13
Nodes (4): ImportMarketData, {closure#3}(), RunSimulation, SimulationJob

### Community 87 - "CandleFeedTest.php"
Cohesion: 0.23
Nodes (15): MinuteCandles, {closure#1}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#2}(), {closure#3}(), {closure#4}() (+7 more)

### Community 88 - "require-dev"
Cohesion: 0.22
Nodes (9): require-dev, fakerphp/faker, larastan/larastan, laravel/pail, laravel/pao, mockery/mockery, nunomaduro/collision, pestphp/pest (+1 more)

### Community 89 - "scripts-descriptions"
Cohesion: 0.25
Nodes (8): scripts-descriptions, analyse, format, mutate, test, test:network, test:testnet, tools:install

### Community 90 - ".evaluate"
Cohesion: 0.29
Nodes (7): WalkForward, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), wfSet()

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

### Community 96 - ".parse"
Cohesion: 0.17
Nodes (9): {closure#1}(), {closure#2}(), EpochTime, ExchangeNumber, {closure#1}(), {closure#2}(), {closure#3}(), {closure#1}() (+1 more)

### Community 97 - "jobs"
Cohesion: 0.25
Nodes (8): name, runs-on, name, runs-on, steps, jobs, analyse, audit

### Community 98 - ".parse"
Cohesion: 0.18
Nodes (10): AstPrinter, {closure#7}(), EntrySpec, ExitSpec, Expression, {closure#1}(), {closure#2}(), {closure#3}() (+2 more)

### Community 99 - "psr-4"
Cohesion: 0.40
Nodes (5): autoload, psr-4, App\\, Database\\Factories\\, Database\\Seeders\\

### Community 100 - "require"
Cohesion: 0.25
Nodes (8): require, laravel/fortify, laravel/framework, laravel/mcp, laravel/tinker, livewire/livewire, php, phrity/websocket

### Community 102 - "CompiledStrategyTest.php"
Cohesion: 0.12
Nodes (22): Intent, PositionView, StrategyContext, {closure#18}(), {closure#37}(), {closure#24}(), aggregatedBars(), {closure#1}() (+14 more)

### Community 103 - "StrategyGates.php"
Cohesion: 0.10
Nodes (11): {closure#3}(), {closure#8}(), {closure#2}(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}() (+3 more)

### Community 104 - "uses"
Cohesion: 0.21
Nodes (12): steps, name, runs-on, steps, frontend, lint, name, runs-on (+4 more)

### Community 105 - "SettingsScreenTest.php"
Cohesion: 0.20
Nodes (13): {closure#10}(), {closure#11}(), {closure#13}(), {closure#14}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}() (+5 more)

### Community 106 - "devDependencies"
Cohesion: 0.14
Nodes (18): devDependencies, concurrently@^10.0.3, laravel-vite-plugin@^3.1, @playwright/test@1.63.0, tailwindcss@^4.0.0, @tailwindcss/vite@^4.0.0, vite@^8.0.0, optionalDependencies (+10 more)

### Community 108 - "ClientOrderId"
Cohesion: 0.06
Nodes (26): {closure#2}(), ClientOrderId, OcoRequest, OrderAck, OrderRequest, OrderType, Limit, Market (+18 more)

### Community 117 - "ChartScreen.php"
Cohesion: 0.06
Nodes (26): {closure#2}(), ChartScreen, {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), FunctionSpec, Group (+18 more)

### Community 118 - "TradingTool"
Cohesion: 0.16
Nodes (4): DslReference, GetJob, ListInstruments, TradingTool

### Community 119 - "DecisionRecord"
Cohesion: 0.21
Nodes (3): DecisionLog, DecisionRecord, DatabaseDecisionLog

### Community 120 - "buy-and-hold.strategy.yaml"
Cohesion: 0.12
Nodes (16): entries, long, exits, stop_loss, fails_when, family, hypothesis, when (+8 more)

### Community 121 - "ChartSeriesTest.php"
Cohesion: 0.17
Nodes (12): VolumeProfile, chart(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#2}(), {closure#3}(), {closure#5}() (+4 more)

### Community 122 - "TokensTest.php"
Cohesion: 0.31
Nodes (6): {closure#3}(), {closure#4}(), contrastRatio(), contrastRules(), relativeLuminance(), themeTokens()

### Community 123 - "ArchiveImportTest.php"
Cohesion: 0.29
Nodes (10): archiveBytes(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}() (+2 more)

### Community 124 - "bootstrap/app.php"
Cohesion: 0.32
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

### Community 125 - "Illuminate\Database\Migrations\Migration"
Cohesion: 0.15
Nodes (3): {closure#1}(), {closure#1}(), {closure#1}()

### Community 127 - "SimClock"
Cohesion: 0.08
Nodes (28): SimClock, WallClock, {closure#1}(), {closure#2}(), {closure#3}(), {closure#1}(), {closure#1}(), {closure#1}() (+20 more)

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
Cohesion: 0.38
Nodes (5): lightweight-charts@^5.2.1, lightweight-charts, faded(), options(), token()

### Community 132 - "TelegramSettings"
Cohesion: 0.16
Nodes (6): NotificationSettings, SettingSource, Env, None, TelegramClient, TelegramSettings

### Community 133 - "EnginePerpTest.php"
Cohesion: 0.18
Nodes (18): InMemoryDecisionLog, {closure#1}(), {closure#2}(), {closure#10}(), {closure#11}(), {closure#13}(), {closure#14}(), {closure#2}() (+10 more)

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

### Community 140 - "Carbon\CarbonImmutable"
Cohesion: 0.29
Nodes (7): {closure#4}(), BreakerEvent, MarketBreakers, breakerMinute(), {closure#1}(), {closure#2}(), {closure#3}()

### Community 141 - ".parse"
Cohesion: 0.43
Nodes (5): {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), streamLines()

### Community 143 - "CandleRepositoryTest.php"
Cohesion: 0.28
Nodes (11): candleStore(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}() (+3 more)

### Community 146 - "AppServiceProvider.php"
Cohesion: 0.04
Nodes (18): ReplayDivergence, ArchiveMissing, ChecksumMismatch, CheckHealth, DiskUsage, {closure#1}(), HealthAlerts, HealthChecks (+10 more)

### Community 147 - "scripts"
Cohesion: 0.50
Nodes (4): scripts, build, dev, e2e

### Community 148 - "0001_01_01_000002_create_jobs_table.php"
Cohesion: 0.33
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

### Community 151 - "WalkForward.php"
Cohesion: 0.12
Nodes (11): {closure#11}(), {closure#12}(), {closure#2}(), {closure#6}(), {closure#1}(), {closure#5}(), {closure#6}(), {closure#7}() (+3 more)

### Community 157 - "strategies-screen.blade.php"
Cohesion: 0.33
Nodes (5): open, promote, promoteDeployment, run, save

### Community 159 - "extra"
Cohesion: 0.67
Nodes (3): extra, laravel, dont-discover

### Community 174 - "App\Agents\Mcp\TradingServer"
Cohesion: 0.13
Nodes (6): CompileStrategy, GetStrategy, ListStrategies, SaveStrategy, TradingServer, StrategyLibrary

### Community 175 - "Carbon\CarbonImmutable"
Cohesion: 0.07
Nodes (24): Backfill, {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#2}(), {closure#3}(), {closure#4}() (+16 more)

### Community 178 - "App\Agents\Mcp\Tools\ListTrials"
Cohesion: 0.40
Nodes (3): {closure#1}(), ListTrials, StrategyTrial

### Community 180 - "EngineCommand"
Cohesion: 0.33
Nodes (3): EngineCommand, {closure#45}(), {closure#46}()

### Community 181 - "EngineMode"
Cohesion: 0.33
Nodes (6): EngineMode, Backtest, Live, Paper, Testnet, {closure#48}()

### Community 183 - "SimulationJobsTest.php"
Cohesion: 0.21
Nodes (5): {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), savedEmaCross()

### Community 184 - "BinanceRequestFailed"
Cohesion: 0.40
Nodes (3): BinanceRequestFailed, LocationRestricted, RateLimited

### Community 189 - "MarketType"
Cohesion: 0.15
Nodes (7): BinanceEndpoints, BinanceRestClient, Json, RequestFailed, MarketType, Perp, Spot

### Community 190 - "MoveStop"
Cohesion: 0.40
Nodes (3): MoveStop, {closure#14}(), {closure#7}()

### Community 191 - "VerificationSource"
Cohesion: 0.50
Nodes (3): VerificationSource, DailyArchive, Rest

### Community 195 - "2026_11_09_000016_create_market_universe_tables.php"
Cohesion: 0.40
Nodes (3): {closure#1}(), {closure#2}(), up()

### Community 197 - "2026_11_09_000017_create_live_tables.php"
Cohesion: 0.33
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

### Community 198 - "RulesHistory"
Cohesion: 0.28
Nodes (8): RulesHistory, {closure#4}(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), rules(), {closure#10}()

### Community 200 - ".times"
Cohesion: 0.09
Nodes (8): Fill, PerpPosition, Position, {closure#4}(), TradeMemory, Perpetual, {closure#16}(), {closure#4}()

### Community 202 - "InstrumentRules"
Cohesion: 0.07
Nodes (32): InstrumentRules, {closure#1}(), AccountView, RejectionReason, BelowMinimum, Breaker, Cooldown, DuplicateIntent (+24 more)

### Community 205 - "2026_11_09_000015_create_scalping_tables.php"
Cohesion: 0.33
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

## Knowledge Gaps
- **147 isolated node(s):** `forgetTelegram`, `testTelegram`, `confirm`, `notify`, `monitor` (+142 more)
  These have ≤1 connection - possible missing edges. (Counts symbols only; 935 node(s) total have ≤1 connection when file, concept and rationale nodes are included.)
- **52 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `Decimal` connect `Decimal` to `.of`, `engineHarness`, `CanonicalJson`, `Engine`, `EnginePerpTest.php`, `DocumentChecker`, `.parse`, `Side`, `Illuminate\Database\ConnectionInterface`, `.of`, `Candle`, `IndicatorsTest.php`, `PolicyLimits`, `ForwardingVenue`, `Side`, `WalkForward.php`, `Symbol`, `.of`, `WarmingVenue`, `Engine.php`, `ReadBack`, `InstrumentStatus`, `SimulatedPerpVenueTest.php`, `Illuminate\Foundation\Testing\RefreshDatabase`, `EngineTest.php`, `BacktestTest.php`, `App\Strategy\Dsl\Document\DocumentChecker`, `SimulatedVenue`, `Timeframe`, `Carbon\CarbonImmutable`, `Strategy`, `Illuminate\Database\ConnectionInterface`, `OrderManagerTest.php`, `.__construct`, `InvalidArgumentException`, `SizedStrategyTest.php`, `IndicatorReferenceTest.php`, `RunBacktestCommandTest.php`, `MarketType`, `DecimalMath`, `RiskPolicy`, `.run`, `StrategyIdentity`, `App\Strategy\Dsl\Document\DocumentChecker`, `InvalidArgumentException`, `OrderRole`, `.evaluate`, `.times`, `RulesHistory`, `InstrumentRules`, `OrderUpdate`, `.measure`, `DecimalMath`, `MonteCarlo`, `App\Support\Number\Decimal`, `CandleFeedTest.php`, `.evaluate`, `.fromStrings`, `.parse`, `CompiledStrategyTest.php`, `StrategyGates.php`, `ClientOrderId`, `ChartScreen.php`, `ChartSeriesTest.php`, `SimClock`?**
  _High betweenness centrality (0.164) - this node is a cross-community bridge._
- **What connects `forgetTelegram`, `testTelegram`, `confirm` to the rest of the system?**
  _147 weakly-connected nodes found - possible missing edges._
- **Should `.of` be split into smaller, more focused modules?**
  _Cohesion score 0.1164021164021164 - nodes in this community are weakly interconnected._
- **Why does `Symbol` connect `Symbol` to `CanonicalJson`, `Engine`, `EnginePerpTest.php`, `DocumentChecker`, `SettingsScreen`, `.parse`, `Side`, `.of`, `ExchangeVerificationTest.php`, `ForwardingVenue`, `PolicyLimits`, `CandleRepositoryTest.php`, `Instrument`, `Side`, `.of`, `WarmingVenue`, `Engine.php`, `App\Market\Data\MarketType`, `InstrumentStatus`, `SimulatedPerpVenueTest.php`, `Illuminate\Foundation\Testing\RefreshDatabase`, `BackfillTest.php`, `EngineTest.php`, `BacktestTest.php`, `Decimal`, `Carbon\CarbonImmutable`, `Strategy`, `Illuminate\Database\ConnectionInterface`, `OrderManagerTest.php`, `Laravel\Mcp\Response`, `InvalidArgumentException`, `SizedStrategyTest.php`, `RunBacktestCommandTest.php`, `MarketType`, `MoveStop`, `.run`, `StrategyIdentity`, `App\Strategy\Dsl\Document\DocumentChecker`, `InvalidArgumentException`, `OrderRole`, `RulesHistory`, `.times`, `OrderUpdate`, `.measure`, `DecimalMath`, `.register`, `CandleFeedTest.php`, `CompiledStrategyTest.php`, `StrategyGates.php`, `ClientOrderId`, `ChartScreen.php`, `DecisionRecord`, `ChartSeriesTest.php`, `SimClock`?**
  _High betweenness centrality (0.061) - this node is a cross-community bridge._
- **Should `Engine` be split into smaller, more focused modules?**
  _Cohesion score 0.14390243902439023 - nodes in this community are weakly interconnected._
- **Why does `DslError` connect `DslError` to `.parse`, `App\Strategy\Dsl\Document\DocumentChecker`, `DocumentChecker`, `CompiledStrategyTest.php`, `CompiledStrategy.php`, `App\Strategy\Dsl\Document\DocumentChecker`, `PolicyLimits`, `Node`, `Strategy`, `ChartScreen.php`, `Laravel\Mcp\Response`, `InvalidArgumentException`, `ChartSeriesTest.php`, `App\Support\Clock\Clock`?**
  _High betweenness centrality (0.026) - this node is a cross-community bridge._
- **Should `.parse` be split into smaller, more focused modules?**
  _Cohesion score 0.11479591836734694 - nodes in this community are weakly interconnected._