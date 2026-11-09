# Graph Report - trading-platform  (2026-11-09)

## Corpus Check
- 601 files · ~250,126 words
- Verdict: corpus is large enough that graph structure adds value.
- Unclassified: 101 file(s) not represented in the graph (top: .zip 38, .CHECKSUM 38, (none) 19)

## Summary
- 4827 nodes · 16593 edges · 209 communities (155 shown, 54 thin omitted)
- Extraction: 91% EXTRACTED · 9% INFERRED · 0% AMBIGUOUS · INFERRED: 1471 edges (avg confidence: 0.85)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `b111b25b`
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
- Clock
- .of
- Candle
- ExchangeVerificationTest.php
- IndicatorsTest.php
- PolicyLimits
- Node
- Illuminate\Console\Command
- Instrument
- Illuminate\Foundation\Testing\RefreshDatabase
- Ledger
- Illuminate\Database\Schema\Blueprint
- generate.py
- ReadBack
- Symbol
- .of
- WarmingVenue
- Engine.php
- App\Support\Clock\Clock
- App\Strategy\Dsl\Compiler\CompiledStrategy
- App\Market\Stream\PhrityTransport
- oi-momentum-long.strategy.yaml
- SimulatedPerpVenueTest.php
- RunEngine.php
- BinanceArchive
- App
- Illuminate\Http\Client\Request
- MarketDataSettingsTest.php
- ClientOrderId
- BacktestTest.php
- App\Strategy\Dsl\Document\DocumentChecker
- ema-cross-scalp-long.strategy.yaml
- Decimal
- SimulatedVenue
- Carbon\CarbonImmutable
- Timeframe
- Illuminate\Database\Eloquent\Model
- editor.js
- Strategy
- BinanceRestClientTest.php
- ema-cross-scalp-short.strategy.yaml
- SettingsScreen.php
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
- Ladder
- FortifyServiceProvider.php
- LadderTest.php
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
- App\Execution\Venues\BinanceVenue
- .measure
- composer.json
- jobs
- postgres
- ma-trend.strategy.yaml
- DecimalMath
- MonteCarlo
- App\Support\Number\Decimal
- pint/composer.json
- HealthTest.php
- RunSimulation.php
- CandleFeedTest.php
- require-dev
- scripts-descriptions
- App\Market\Data\Candle
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
- App\Market\CandleRepository
- StrategyGates.php
- uses
- SettingsScreenTest.php
- devDependencies
- BinanceVenueTest.php
- ChartScreen.php
- App\Agents\Mcp\TradingServer
- DecisionRecord
- buy-and-hold.strategy.yaml
- ChartSeriesTest.php
- TokensTest.php
- ArchiveImportTest.php
- LiveGate
- Illuminate\Database\Migrations\Migration
- ExitReason
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
- .sqrt
- CandleRepositoryTest.php
- Illuminate\Support\Facades\Schema
- Asset
- RuntimeException
- scripts
- 0001_01_01_000002_create_jobs_table.php
- fake-telegram.mjs
- TrackedOrder
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
- App\Market\Models\Instrument
- .isFull
- .breaches
- E2eSeeder.php
- 2026_11_09_000012_create_account_results_tables.php
- Tokens
- UserFactory.php
- .seconds
- CompiledStrategy
- App\Strategy\Dsl\Document\StrategySpec
- 2026_11_09_000013_add_daily_report_to_notification_settings.php
- MarketType
- StrategyVersion
- Percent
- ReadsGridOptions
- ScalpingCollectorTest.php
- 2026_11_09_000018_add_effective_at_to_deployments.php
- 2026_11_09_000016_create_market_universe_tables.php
- Quantity
- 2026_11_09_000017_create_live_tables.php
- start-local.ps1
- .times
- VenuePosition
- Fill
- CandleStreamTest.php
- Expression
- 2026_11_09_000015_create_scalping_tables.php
- Console/CompileStrategy.php

## God Nodes (most connected - your core abstractions)
1. `Decimal` - 516 edges
2. `Side` - 205 edges
3. `Symbol` - 204 edges
4. `Candle` - 134 edges
5. `App` - 134 edges
6. `Timeframe` - 123 edges
7. `Price` - 100 edges
8. `Position` - 96 edges
9. `Clock` - 80 edges
10. `SimClock` - 75 edges

## Surprising Connections (you probably didn't know these)
- `{closure#13}()` --calls--> `App`  [INFERRED]
  tests/Feature/Web/SettingsScreenTest.php → app/Notifications/SettingSource.php
- `{closure#8}()` --calls--> `App`  [INFERRED]
  tests/Feature/Web/SettingsScreenTest.php → app/Notifications/SettingSource.php
- `{closure#1}()` --calls--> `App`  [INFERRED]
  tests/Feature/Support/ClockBindingTest.php → app/Notifications/SettingSource.php
- `{closure#4}()` --calls--> `App`  [INFERRED]
  tests/Feature/Simulation/SimulationJobsTest.php → app/Notifications/SettingSource.php
- `up()` --calls--> `App`  [INFERRED]
  database/migrations/2026_11_09_000016_create_market_universe_tables.php → app/Notifications/SettingSource.php

## Import Cycles
- None detected.

## Communities (209 total, 54 thin omitted)

### Community 0 - ".of"
Cohesion: 0.12
Nodes (24): {closure#44}(), {closure#45}(), {closure#46}(), {closure#47}(), {closure#48}(), {closure#17}(), {closure#1}(), {closure#10}() (+16 more)

### Community 1 - "engineHarness"
Cohesion: 0.10
Nodes (41): EngineCommand, EngineMode, Backtest, Live, Paper, Testnet, ScriptedStrategy, {closure#1}() (+33 more)

### Community 2 - "CanonicalJson"
Cohesion: 0.24
Nodes (6): CanonicalJson, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}()

### Community 3 - "DslError"
Cohesion: 0.13
Nodes (13): {closure#1}(), {closure#5}(), {closure#7}(), {closure#8}(), DslError, YamlList, YamlReader, YamlScalar (+5 more)

### Community 4 - "Engine"
Cohesion: 0.16
Nodes (4): EngineRecord, Engine, ReconciliationMismatch, {closure#3}()

### Community 5 - ".parse"
Cohesion: 0.07
Nodes (50): {closure#12}(), Parameters, LimitedStrategy, {closure#17}(), {closure#5}(), carryDecisions(), {closure#10}(), {closure#11}() (+42 more)

### Community 7 - "SettingsScreen"
Cohesion: 0.10
Nodes (3): OpenAccount, StoreKeys, SettingsScreen

### Community 8 - "CompiledStrategy.php"
Cohesion: 0.13
Nodes (7): {closure#1}(), {closure#3}(), {closure#42}(), {closure#43}(), {closure#45}(), Compiler, Program

### Community 9 - "Side"
Cohesion: 0.17
Nodes (44): TradeMemory, Side, Buy, Sell, btc(), {closure#10}(), {closure#11}(), {closure#12}() (+36 more)

### Community 10 - "Clock"
Cohesion: 0.07
Nodes (11): AuthenticateMcpToken, CheckMarketData, SyncInstruments, FundingRateRepository, Heartbeats, {closure#1}(), InstrumentSync, SyncResult (+3 more)

### Community 11 - ".of"
Cohesion: 0.12
Nodes (19): AssetMismatch, Money, {closure#2}(), {closure#1}(), usdt(), {closure#11}(), {closure#7}(), {closure#1}() (+11 more)

### Community 12 - "Candle"
Cohesion: 0.11
Nodes (35): {closure#1}(), {closure#3}(), Candle, {closure#9}(), {closure#1}(), {closure#42}(), candleWith(), {closure#1}() (+27 more)

### Community 13 - "ExchangeVerificationTest.php"
Cohesion: 0.12
Nodes (29): {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#2}(), {closure#3}(), {closure#4}() (+21 more)

### Community 14 - "IndicatorsTest.php"
Cohesion: 0.09
Nodes (23): Atr, Donchian, Ema, Sma, {closure#5}(), fiveMinuteBars(), bar(), {closure#1}() (+15 more)

### Community 15 - "PolicyLimits"
Cohesion: 0.10
Nodes (20): InstrumentDirectory, PolicyLimits, StrategyRepository, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}() (+12 more)

### Community 16 - "Node"
Cohesion: 0.14
Nodes (20): Binary, Parser, Token, TokenKind, Colon, Comma, Dot, Duration (+12 more)

### Community 17 - "Illuminate\Console\Command"
Cohesion: 0.10
Nodes (14): FlattenEngine, HaltEngine, IssuesCommands, PauseStrategy, ResumeEngine, CommandKind, Flatten, Halt (+6 more)

### Community 18 - "Instrument"
Cohesion: 0.07
Nodes (19): BackfillStep, CandleRepository, {closure#2}(), {closure#4}(), CandleSource, Archive, Rest, Stream (+11 more)

### Community 19 - "Illuminate\Foundation\Testing\RefreshDatabase"
Cohesion: 0.06
Nodes (22): {closure#5}(), EngineCommandRow, User, {closure#1}(), {closure#1}(), {closure#2}(), {closure#3}(), fakeTestnet() (+14 more)

### Community 20 - "Ledger"
Cohesion: 0.15
Nodes (31): Ledger, LedgerViolation, balanceOf(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}() (+23 more)

### Community 21 - "Illuminate\Database\Schema\Blueprint"
Cohesion: 0.19
Nodes (10): {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#1}(), {closure#2}(), {closure#3}() (+2 more)

### Community 22 - "generate.py"
Cohesion: 0.07
Nodes (30): atr(), bars(), bb(), change(), dmi(), donchian(), ema(), highest() (+22 more)

### Community 24 - "Symbol"
Cohesion: 0.06
Nodes (14): Close, EntryOrder, Intent, MoveStop, OpenCarry, OpenLong, OpenShort, Subscription (+6 more)

### Community 25 - ".of"
Cohesion: 0.06
Nodes (82): AccountView, RejectionReason, BelowMinimum, Breaker, Cooldown, DuplicateIntent, EntryWorking, GrossExposure (+74 more)

### Community 27 - "Engine.php"
Cohesion: 0.10
Nodes (6): {closure#1}(), {closure#11}(), {closure#2}(), {closure#3}(), {closure#9}(), PerpetualTracker

### Community 28 - "App\Support\Clock\Clock"
Cohesion: 0.06
Nodes (11): Backfill, BackfillReport, BackfillMarketData, SelectsInstruments, {closure#1}(), {closure#2}(), StreamCandles, {closure#1}() (+3 more)

### Community 29 - "App\Strategy\Dsl\Compiler\CompiledStrategy"
Cohesion: 0.08
Nodes (14): {closure#2}(), {closure#3}(), {closure#5}(), {closure#6}(), {closure#7}(), {closure#8}(), {closure#9}(), {closure#40}() (+6 more)

### Community 30 - "App\Market\Stream\PhrityTransport"
Cohesion: 0.12
Nodes (3): PhrityTransport, StreamFailed, FakeTransport

### Community 31 - "oi-momentum-long.strategy.yaml"
Cohesion: 0.07
Nodes (27): entries, long, exits, stop_loss, take_profit, when, fails_when, family (+19 more)

### Community 32 - "SimulatedPerpVenueTest.php"
Cohesion: 0.20
Nodes (26): {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#15}(), {closure#18}(), {closure#19}() (+18 more)

### Community 33 - "RunEngine.php"
Cohesion: 0.08
Nodes (5): AccountRuntime, {closure#4}(), RunEngine, Notifier, TelegramNotifier

### Community 35 - "App"
Cohesion: 0.09
Nodes (25): SendTelegram, App, {closure#1}(), {closure#2}(), {closure#3}(), {closure#2}(), {closure#4}(), {closure#5}() (+17 more)

### Community 36 - "Illuminate\Http\Client\Request"
Cohesion: 0.12
Nodes (22): {closure#4}(), {closure#6}(), queryOf(), approveLive(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}() (+14 more)

### Community 37 - "MarketDataSettingsTest.php"
Cohesion: 0.11
Nodes (14): {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), scalpingBackfill(), {closure#2}() (+6 more)

### Community 38 - "ClientOrderId"
Cohesion: 0.06
Nodes (22): Venue, Balance, ClientOrderId, OcoRequest, OrderAck, OrderRequest, OrderType, Limit (+14 more)

### Community 39 - "BacktestTest.php"
Cohesion: 0.08
Nodes (35): FeeSchedule, CostModel, CandleFeed, InstrumentRules, {closure#1}(), RulesHistory, Backtest, {closure#1}() (+27 more)

### Community 40 - "App\Strategy\Dsl\Document\DocumentChecker"
Cohesion: 0.08
Nodes (25): Argument, BoolLiteral, Call, DurationLiteral, Identifier, Lookback, Node, NumberLiteral (+17 more)

### Community 41 - "ema-cross-scalp-long.strategy.yaml"
Cohesion: 0.06
Nodes (33): entries, long, exits, stop_loss, take_profit, time_stop, when, fails_when (+25 more)

### Community 42 - "Decimal"
Cohesion: 0.04
Nodes (48): {closure#6}(), RoundTrip, {closure#1}(), {closure#2}(), {closure#3}(), {closure#10}(), {closure#11}(), {closure#12}() (+40 more)

### Community 43 - "SimulatedVenue"
Cohesion: 0.17
Nodes (8): OrderStatus, Cancelled, Expired, Filled, New, PartiallyFilled, SimulatedOrder, SimulatedVenue

### Community 44 - "Carbon\CarbonImmutable"
Cohesion: 0.06
Nodes (22): ArchivePeriod, {closure#1}(), {closure#11}(), OpenInterest, PriceMinute, TradeFlow, QualityReport, DiskUsage (+14 more)

### Community 45 - "Timeframe"
Cohesion: 0.09
Nodes (33): CandleAggregator, CandleSequenceBroken, CarryTemplate, {closure#2}(), {closure#8}(), Timeframe, FifteenMinutes, FiveMinutes (+25 more)

### Community 46 - "Illuminate\Database\Eloquent\Model"
Cohesion: 0.05
Nodes (30): McpCall, McpToken, AccountEquity, AccountSnapshot, AccountTrade, PlainDecimal, ExchangeKey, JournalEntry (+22 more)

### Community 47 - "editor.js"
Cohesion: 0.19
Nodes (12): dependencies, codemirror@^6.0.2, @codemirror/lang-yaml@^6.1.3, @codemirror/language@^6.13.1, @lezer/highlight@^1.2.5, codemirror, @codemirror/lang-yaml, @codemirror/language (+4 more)

### Community 48 - "Strategy"
Cohesion: 0.19
Nodes (18): Strategy, baselineDocument(), baselinePairs(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#14}() (+10 more)

### Community 49 - "BinanceRestClientTest.php"
Cohesion: 0.14
Nodes (15): binanceClient(), {closure#1}(), {closure#11}(), {closure#13}(), {closure#14}(), {closure#15}(), {closure#2}(), {closure#3}() (+7 more)

### Community 50 - "ema-cross-scalp-short.strategy.yaml"
Cohesion: 0.06
Nodes (33): entries, short, exits, stop_loss, take_profit, time_stop, when, fails_when (+25 more)

### Community 51 - "SettingsScreen.php"
Cohesion: 0.08
Nodes (11): Deployment, ReauthController, AccountScreen, HaltButton, {closure#1}(), ModeBadge, {closure#2}(), {closure#4}() (+3 more)

### Community 52 - "OrderManagerTest.php"
Cohesion: 0.33
Nodes (15): btcUsdt(), {closure#10}(), {closure#14}(), {closure#15}(), {closure#16}(), {closure#2}(), {closure#4}(), {closure#5}() (+7 more)

### Community 53 - "Laravel\Mcp\Response"
Cohesion: 0.09
Nodes (13): ChartTool, GetCandles, GetFunding, GetIndicators, GetStrategy, ListInstruments, ListStrategies, {closure#1}() (+5 more)

### Community 54 - "postgres"
Cohesion: 0.10
Nodes (19): POSTGRES_DB, POSTGRES_PASSWORD, POSTGRES_USER, interval, retries, test, timeout, environment (+11 more)

### Community 55 - "InvalidArgumentException"
Cohesion: 0.08
Nodes (16): {closure#1}(), {closure#2}(), {closure#2}(), {closure#3}(), {closure#1}(), {closure#2}(), {closure#1}(), {closure#2}() (+8 more)

### Community 56 - "SizedStrategyTest.php"
Cohesion: 0.12
Nodes (11): SizedStrategy, {closure#1}(), {closure#1}(), {closure#2}(), __construct(), enterOnce(), identity(), limits() (+3 more)

### Community 57 - "donchian-breakout-short.strategy.yaml"
Cohesion: 0.07
Nodes (26): entries, short, default, range, step, default, range, step (+18 more)

### Community 58 - "IndicatorReferenceTest.php"
Cohesion: 0.04
Nodes (34): BarIndicator, Bollinger, Change, DonchianChannel, Highest, Hma, Keltner, Lowest (+26 more)

### Community 59 - "support.mjs"
Cohesion: 0.26
Nodes (9): @playwright/test, confirmed(), checkLayout(), EMAIL, freshCode(), PASSWORD, reauthenticate(), totp() (+1 more)

### Community 60 - "RunBacktestCommandTest.php"
Cohesion: 0.07
Nodes (34): BacktestRun, {closure#1}(), {closure#4}(), {closure#6}(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}() (+26 more)

### Community 61 - "DecimalMath"
Cohesion: 0.12
Nodes (15): Normal, DecimalMath, {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}() (+7 more)

### Community 62 - "Ladder"
Cohesion: 0.20
Nodes (4): {closure#1}(), {closure#2}(), Ladder, LadderStatus

### Community 63 - "FortifyServiceProvider.php"
Cohesion: 0.10
Nodes (8): AppServiceProvider, {closure#7}(), {closure#3}(), {closure#4}(), FortifyServiceProvider, {closure#1}(), {closure#2}(), {closure#3}()

### Community 64 - "LadderTest.php"
Cohesion: 0.13
Nodes (26): Account, {closure#4}(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}() (+18 more)

### Community 65 - "donchian-breakout.strategy.yaml"
Cohesion: 0.07
Nodes (26): entries, long, default, range, step, default, range, step (+18 more)

### Community 66 - ".run"
Cohesion: 0.06
Nodes (38): RiskProfile, Account, Benchmark, RiskPolicy, RunBacktest, PerformanceMetrics, Report, MovingAverageTrend (+30 more)

### Community 67 - "StrategyIdentity"
Cohesion: 0.06
Nodes (14): BuyAndHold, DonchianBreakout, FundingCarry, BuildableStrategy, PositionView, StrategyContext, StrategyIdentity, RenamedStrategy (+6 more)

### Community 68 - "App\Strategy\Dsl\Document\DocumentChecker"
Cohesion: 0.07
Nodes (33): {closure#1}(), {closure#3}(), {closure#4}(), {closure#9}(), ParameterSpec, DslWriter, {closure#2}(), Scope (+25 more)

### Community 69 - "InvalidArgumentException"
Cohesion: 0.10
Nodes (7): StoredInstruments, {closure#1}(), {closure#1}(), ExportStrategies, ImportStrategies, PreparesBacktests, PromoteStrategy

### Community 70 - "OrderRole"
Cohesion: 0.15
Nodes (8): OrderRole, Entry, Exit, Flatten, Stop, Target, ProtectionPlan, OrderManager

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

### Community 75 - "App\Execution\Venues\BinanceVenue"
Cohesion: 0.13
Nodes (16): Income, IncomeKind, Funding, Liquidation, OrderUpdate, VenueFill, {closure#1}(), {closure#2}() (+8 more)

### Community 76 - ".measure"
Cohesion: 0.13
Nodes (12): ParticipantResult, EquityTracker, Performance, {closure#1}(), {closure#4}(), {closure#1}(), {closure#2}(), {closure#3}() (+4 more)

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
Cohesion: 0.14
Nodes (11): {closure#4}(), {closure#5}(), MonteCarlo, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}() (+3 more)

### Community 83 - "App\Support\Number\Decimal"
Cohesion: 0.08
Nodes (50): PerpetualView, {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#15}() (+42 more)

### Community 84 - "pint/composer.json"
Cohesion: 0.20
Nodes (9): config, sort-packages, description, license, name, require, laravel/pint, php (+1 more)

### Community 85 - "HealthTest.php"
Cohesion: 0.23
Nodes (10): {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#8}(), healthAt(), healthyMachine() (+2 more)

### Community 86 - "RunSimulation.php"
Cohesion: 0.14
Nodes (3): ImportMarketData, RunSimulation, SimulationJob

### Community 87 - "CandleFeedTest.php"
Cohesion: 0.30
Nodes (14): {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#2}(), {closure#4}(), {closure#5}() (+6 more)

### Community 88 - "require-dev"
Cohesion: 0.22
Nodes (9): require-dev, fakerphp/faker, larastan/larastan, laravel/pail, laravel/pao, mockery/mockery, nunomaduro/collision, pestphp/pest (+1 more)

### Community 89 - "scripts-descriptions"
Cohesion: 0.25
Nodes (8): scripts-descriptions, analyse, format, mutate, test, test:network, test:testnet, tools:install

### Community 90 - "App\Market\Data\Candle"
Cohesion: 0.14
Nodes (6): Liquidity, Maker, Taker, SimulatedFuturesBook, UpdateStream, {closure#10}()

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

### Community 96 - "AppServiceProvider.php"
Cohesion: 0.07
Nodes (11): {closure#2}(), {closure#12}(), {closure#1}(), SystemSleeper, LocalDiskUsage, {closure#2}(), {closure#3}(), {closure#4}() (+3 more)

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
Cohesion: 0.25
Nodes (8): require, laravel/fortify, laravel/framework, laravel/mcp, laravel/tinker, livewire/livewire, php, phrity/websocket

### Community 102 - "App\Market\CandleRepository"
Cohesion: 0.13
Nodes (3): CandleStream, ScalpingCollector, StreamTransport

### Community 103 - "StrategyGates.php"
Cohesion: 0.06
Nodes (24): {closure#2}(), {closure#3}(), {closure#4}(), {closure#6}(), {closure#8}(), {closure#2}(), GridPart, {closure#1}() (+16 more)

### Community 104 - "uses"
Cohesion: 0.21
Nodes (12): steps, name, runs-on, steps, frontend, lint, name, runs-on (+4 more)

### Community 105 - "SettingsScreenTest.php"
Cohesion: 0.22
Nodes (11): {closure#10}(), {closure#11}(), {closure#13}(), {closure#14}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}() (+3 more)

### Community 106 - "devDependencies"
Cohesion: 0.14
Nodes (18): devDependencies, concurrently@^10.0.3, laravel-vite-plugin@^3.1, @playwright/test@1.63.0, tailwindcss@^4.0.0, @tailwindcss/vite@^4.0.0, vite@^8.0.0, optionalDependencies (+10 more)

### Community 108 - "BinanceVenueTest.php"
Cohesion: 0.11
Nodes (13): {closure#2}(), BinanceVenue, BinanceApiError, BinanceUnreachable, SignedBinanceClient, binanceVenue(), {closure#1}(), {closure#2}() (+5 more)

### Community 117 - "ChartScreen.php"
Cohesion: 0.06
Nodes (27): {closure#2}(), ChartScreen, {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), FunctionSpec, Group (+19 more)

### Community 118 - "App\Agents\Mcp\TradingServer"
Cohesion: 0.16
Nodes (3): DslReference, GetJob, TradingServer

### Community 119 - "DecisionRecord"
Cohesion: 0.16
Nodes (5): DecisionLog, DecisionRecord, IntentRecord, {closure#1}(), DatabaseDecisionLog

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
Cohesion: 0.26
Nodes (10): archiveBytes(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}() (+2 more)

### Community 124 - "LiveGate"
Cohesion: 0.12
Nodes (7): LiveGate, ApproveLive, ConfirmsOperator, CreateAccount, LiveApproval, {closure#5}(), CreateUser

### Community 125 - "Illuminate\Database\Migrations\Migration"
Cohesion: 0.15
Nodes (3): {closure#1}(), {closure#1}(), {closure#1}()

### Community 126 - "ExitReason"
Cohesion: 0.18
Nodes (22): ExitReason, Flatten, Liquidation, Signal, Stop, Target, {closure#1}(), {closure#10}() (+14 more)

### Community 127 - "SimClock"
Cohesion: 0.07
Nodes (32): FundingRate, SimClock, WallClock, {closure#1}(), {closure#1}(), {closure#11}(), {closure#14}(), {closure#15}() (+24 more)

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
Cohesion: 0.18
Nodes (6): NotificationSettings, SettingSource, Env, None, TelegramClient, TelegramSettings

### Community 133 - "EnginePerpTest.php"
Cohesion: 0.15
Nodes (19): InMemoryDecisionLog, MinuteCandles, {closure#14}(), {closure#3}(), {closure#7}(), {closure#10}(), {closure#11}(), {closure#14}() (+11 more)

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
Cohesion: 0.33
Nodes (6): KlineMessage, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), streamLines()

### Community 142 - ".sqrt"
Cohesion: 0.22
Nodes (12): Statistics, {closure#1}(), {closure#2}(), {closure#3}(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}() (+4 more)

### Community 143 - "CandleRepositoryTest.php"
Cohesion: 0.28
Nodes (11): candleStore(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}() (+3 more)

### Community 145 - "Asset"
Cohesion: 0.17
Nodes (4): Marks, Asset, {closure#25}(), {closure#9}()

### Community 146 - "RuntimeException"
Cohesion: 0.10
Nodes (8): ReplayDivergence, ArchiveMissing, ChecksumMismatch, VerifyMarketData, VerificationSource, DailyArchive, Rest, HealthAlerts

### Community 147 - "scripts"
Cohesion: 0.50
Nodes (4): scripts, build, dev, e2e

### Community 148 - "0001_01_01_000002_create_jobs_table.php"
Cohesion: 0.33
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

### Community 151 - "TrackedOrder"
Cohesion: 0.25
Nodes (9): TrackedOrder, {closure#1}(), {closure#2}(), OrderBook, {closure#1}(), {closure#11}(), {closure#12}(), {closure#13}() (+1 more)

### Community 157 - "strategies-screen.blade.php"
Cohesion: 0.33
Nodes (5): open, promote, promoteDeployment, run, save

### Community 159 - "extra"
Cohesion: 0.67
Nodes (3): extra, laravel, dont-discover

### Community 174 - "App\Simulation\StrategyLibrary"
Cohesion: 0.17
Nodes (6): {closure#1}(), CompileStrategy, StrategiesScreen, StrategyLibrary, CheckResult, SaveResult

### Community 175 - "App\Market\Models\Instrument"
Cohesion: 0.11
Nodes (20): {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}(), {closure#8}(), {closure#9}() (+12 more)

### Community 177 - ".breaches"
Cohesion: 0.29
Nodes (10): Band, bandReport(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#7}() (+2 more)

### Community 178 - "E2eSeeder.php"
Cohesion: 0.15
Nodes (5): {closure#1}(), InstrumentFilter, StrategyGateReport, DatabaseSeeder, E2eSeeder

### Community 180 - "Tokens"
Cohesion: 0.23
Nodes (3): CreateMcpToken, RevokeMcpToken, Tokens

### Community 182 - ".seconds"
Cohesion: 0.15
Nodes (3): InvalidCandle, {closure#2}(), {closure#4}()

### Community 184 - "App\Strategy\Dsl\Document\StrategySpec"
Cohesion: 0.35
Nodes (7): {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#7}(), readBackDocument(), readBackSpec()

### Community 189 - "MarketType"
Cohesion: 0.06
Nodes (29): {closure#1}(), BinanceEndpoints, BinanceRequestFailed, BinanceRestClient, EpochTime, ExchangeNumber, Json, LocationRestricted (+21 more)

### Community 190 - "StrategyVersion"
Cohesion: 0.33
Nodes (3): {closure#1}(), {closure#3}(), StrategyVersion

### Community 191 - "Percent"
Cohesion: 0.24
Nodes (3): Percent, {closure#14}(), {closure#15}()

### Community 193 - "ScalpingCollectorTest.php"
Cohesion: 0.35
Nodes (9): aggTrade(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#5}(), {closure#6}(), collector(), defaultBinanceFakes() (+1 more)

### Community 195 - "2026_11_09_000016_create_market_universe_tables.php"
Cohesion: 0.40
Nodes (3): {closure#1}(), {closure#2}(), up()

### Community 196 - "Quantity"
Cohesion: 0.24
Nodes (3): Quantity, {closure#10}(), {closure#9}()

### Community 197 - "2026_11_09_000017_create_live_tables.php"
Cohesion: 0.33
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

### Community 198 - "start-local.ps1"
Cohesion: 0.27
Nodes (4): Stop-Postgres(), Stop-ProcessTree(), Test-PostgresRunning(), Write-Step()

### Community 200 - ".times"
Cohesion: 0.06
Nodes (10): {closure#2}(), PerpPosition, {closure#2}(), VolumeProfile, InvalidNumber, Perpetual, {closure#16}(), {closure#5}() (+2 more)

### Community 201 - "VenuePosition"
Cohesion: 0.31
Nodes (4): VenuePosition, {closure#1}(), SimulatedPosition, {closure#13}()

### Community 203 - "CandleStreamTest.php"
Cohesion: 0.44
Nodes (8): {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), streamMessages(), streamWith()

### Community 204 - "Expression"
Cohesion: 0.32
Nodes (3): EntrySpec, ExitSpec, Expression

### Community 205 - "2026_11_09_000015_create_scalping_tables.php"
Cohesion: 0.33
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

## Knowledge Gaps
- **147 isolated node(s):** `Limit`, `Market`, `StopMarket`, `TakeProfitMarket`, `Exit` (+142 more)
  These have ≤1 connection - possible missing edges. (Counts symbols only; 938 node(s) total have ≤1 connection when file, concept and rationale nodes are included.)
- **54 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `Decimal` connect `Decimal` to `.of`, `engineHarness`, `CanonicalJson`, `DslError`, `Engine`, `.parse`, `DocumentChecker`, `EnginePerpTest.php`, `CompiledStrategy.php`, `Side`, `Clock`, `.of`, `Candle`, `.sqrt`, `PolicyLimits`, `IndicatorsTest.php`, `Asset`, `Instrument`, `Ledger`, `TrackedOrder`, `Symbol`, `.of`, `WarmingVenue`, `Engine.php`, `App\Strategy\Dsl\Compiler\CompiledStrategy`, `ClientOrderId`, `BacktestTest.php`, `App\Strategy\Dsl\Document\DocumentChecker`, `SimulatedVenue`, `Timeframe`, `App\Market\Models\Instrument`, `.isFull`, `Strategy`, `OrderManagerTest.php`, `.seconds`, `InvalidArgumentException`, `SizedStrategyTest.php`, `CompiledStrategy`, `App\Strategy\Dsl\Document\StrategySpec`, `IndicatorReferenceTest.php`, `RunBacktestCommandTest.php`, `MarketType`, `DecimalMath`, `Percent`, `.run`, `StrategyIdentity`, `App\Strategy\Dsl\Document\DocumentChecker`, `InvalidArgumentException`, `OrderRole`, `.submit`, `.times`, `VenuePosition`, `Fill`, `App\Execution\Venues\BinanceVenue`, `.measure`, `.evaluate`, `Expression`, `DecimalMath`, `MonteCarlo`, `App\Support\Number\Decimal`, `CandleFeedTest.php`, `App\Market\Data\Candle`, `Quantity`, `.fromStrings`, `AppServiceProvider.php`, `StrategyGates.php`, `BinanceVenueTest.php`, `ChartScreen.php`, `ChartSeriesTest.php`, `ExitReason`, `SimClock`?**
  _High betweenness centrality (0.153) - this node is a cross-community bridge._
- **What connects `Limit`, `Market`, `StopMarket` to the rest of the system?**
  _147 weakly-connected nodes found - possible missing edges._
- **Should `.of` be split into smaller, more focused modules?**
  _Cohesion score 0.1164021164021164 - nodes in this community are weakly interconnected._
- **Why does `Symbol` connect `Symbol` to `engineHarness`, `CanonicalJson`, `DslError`, `Engine`, `.parse`, `DocumentChecker`, `SettingsScreen`, `EnginePerpTest.php`, `Side`, `.of`, `ExchangeVerificationTest.php`, `PolicyLimits`, `CandleRepositoryTest.php`, `Asset`, `Instrument`, `Illuminate\Foundation\Testing\RefreshDatabase`, `Ledger`, `TrackedOrder`, `.of`, `WarmingVenue`, `Engine.php`, `App\Support\Clock\Clock`, `ClientOrderId`, `BacktestTest.php`, `Decimal`, `Timeframe`, `App\Market\Models\Instrument`, `Strategy`, `OrderManagerTest.php`, `Laravel\Mcp\Response`, `InvalidArgumentException`, `App\Strategy\Dsl\Document\StrategySpec`, `RunBacktestCommandTest.php`, `MarketType`, `.run`, `StrategyIdentity`, `OrderRole`, `.submit`, `.times`, `VenuePosition`, `Fill`, `App\Execution\Venues\BinanceVenue`, `.measure`, `DecimalMath`, `CandleFeedTest.php`, `App\Market\Data\Candle`, `AppServiceProvider.php`, `StrategyGates.php`, `BinanceVenueTest.php`, `ChartScreen.php`, `DecisionRecord`, `ChartSeriesTest.php`, `ExitReason`, `SimClock`?**
  _High betweenness centrality (0.058) - this node is a cross-community bridge._
- **Should `engineHarness` be split into smaller, more focused modules?**
  _Cohesion score 0.10382513661202186 - nodes in this community are weakly interconnected._
- **Why does `Candle` connect `Candle` to `engineHarness`, `EnginePerpTest.php`, `.parse`, `CompiledStrategy.php`, `Side`, `Carbon\CarbonImmutable`, `.parse`, `.sqrt`, `IndicatorsTest.php`, `CandleRepositoryTest.php`, `ExchangeVerificationTest.php`, `Instrument`, `Symbol`, `BacktestTest.php`, `Decimal`, `Carbon\CarbonImmutable`, `Timeframe`, `.isFull`, `OrderManagerTest.php`, `.seconds`, `IndicatorReferenceTest.php`, `MarketType`, `.run`, `.times`, `DecimalMath`, `MonteCarlo`, `CandleFeedTest.php`, `App\Market\Data\Candle`, `.fromStrings`, `App\Market\CandleRepository`, `StrategyGates.php`, `ChartSeriesTest.php`?**
  _High betweenness centrality (0.028) - this node is a cross-community bridge._
- **Should `DslError` be split into smaller, more focused modules?**
  _Cohesion score 0.12612612612612611 - nodes in this community are weakly interconnected._