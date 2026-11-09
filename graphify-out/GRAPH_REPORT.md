# Graph Report - trading-platform  (2026-11-09)

## Corpus Check
- 603 files · ~251,584 words
- Verdict: corpus is large enough that graph structure adds value.
- Unclassified: 101 file(s) not represented in the graph (top: .zip 38, .CHECKSUM 38, (none) 19)

## Summary
- 4846 nodes · 16630 edges · 219 communities (160 shown, 59 thin omitted)
- Extraction: 91% EXTRACTED · 9% INFERRED · 0% AMBIGUOUS · INFERRED: 1482 edges (avg confidence: 0.85)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `e5b63c1c`
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
- SettingsScreen
- CompiledStrategy.php
- Side
- InstrumentSync
- .of
- Candle
- ExchangeVerificationTest.php
- .isLessThan
- PolicyLimits
- Node
- Illuminate\Console\Command
- Clock
- web
- Ledger
- Illuminate\Database\Schema\Blueprint
- generate.py
- App\Strategy\Dsl\Compiler\CompiledStrategy
- StrategyIdentity
- RejectionReason
- Venue
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
- LiveAccountTest.php
- OrderRole
- BacktestTest.php
- App\Strategy\Dsl\Document\DocumentChecker
- ema-cross-scalp-long.strategy.yaml
- Decimal
- .zero
- ScalpingArchiveTest.php
- Timeframe
- Illuminate\Database\Eloquent\Model
- editor.js
- Strategy
- BinanceRestClientTest.php
- ema-cross-scalp-short.strategy.yaml
- .now
- RiskState
- Laravel\Mcp\Response
- postgres
- InvalidArgumentException
- SizedStrategyTest.php
- donchian-breakout-short.strategy.yaml
- IndicatorReferenceTest.php
- support.mjs
- RunBacktestCommandTest.php
- DecimalMath
- OrderManagerTest.php
- FortifyServiceProvider.php
- healthyMachine
- donchian-breakout.strategy.yaml
- .run
- FundingCarry
- App\Strategy\Dsl\Document\DocumentChecker
- InvalidArgumentException
- CandleFeedTest.php
- .isFull
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
- Ledger
- require-dev
- scripts-descriptions
- InstrumentStatus
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
- App\Support\Clock\Clock
- GridBacktest.php
- uses
- SettingsScreenTest.php
- devDependencies
- BinanceVenueTest.php
- ParamKind
- App\Agents\Mcp\TradingServer
- VenuePosition
- buy-and-hold.strategy.yaml
- ChartSeriesTest.php
- TokensTest.php
- ArchiveImportTest.php
- App\Execution\Models\Account
- Illuminate\Database\Migrations\Migration
- ExitReason
- .register
- funding-carry.strategy.yaml
- McpServerTest.php
- env.mjs
- chart.js
- TelegramSettings
- EngineMode
- settings-screen.blade.php
- 0001_01_01_000000_create_users_table.php
- steps
- 2026_11_09_000014_create_health_tables.php
- ScalpingFunctionsTest.php
- .parse
- Statistics
- CandleRepositoryTest.php
- Illuminate\Support\Facades\Schema
- Asset
- RuntimeException
- scripts
- 0001_01_01_000002_create_jobs_table.php
- fake-telegram.mjs
- MarketBreakersTest.php
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
- Carbon\CarbonImmutable
- MarketType
- WalkForward.php
- Engine.php
- 2026_11_09_000012_create_account_results_tables.php
- .evaluate
- TerminalQrCodeTest.php
- Timeframe
- CompiledStrategy
- User
- 2026_11_09_000013_add_daily_report_to_notification_settings.php
- .priceMinutes
- UserFactory.php
- ForwardingVenue
- .handle
- ScalpingCollectorTest.php
- 2026_11_09_000018_add_effective_at_to_deployments.php
- 2026_11_09_000016_create_market_universe_tables.php
- BinanceEndpoints
- 2026_11_09_000017_create_live_tables.php
- start-local.ps1
- BinanceRestClient
- .times
- DatabaseDecisionLog
- SimulationJobsTest.php
- .compute
- StrategyGates.php
- 2026_11_09_000015_create_scalping_tables.php
- Money
- Percent
- BinanceRequestFailed
- DirectionalMovement
- TradeFlowBuilder
- Expression
- bootstrap/app.php
- {closure#40}
- AppServiceProvider
- GridWorkersTest.php
- .number
- DslReference
- ReadsGridOptions

## God Nodes (most connected - your core abstractions)
1. `Decimal` - 516 edges
2. `Side` - 205 edges
3. `Symbol` - 202 edges
4. `App` - 135 edges
5. `Candle` - 134 edges
6. `Timeframe` - 123 edges
7. `Price` - 100 edges
8. `Position` - 96 edges
9. `Clock` - 77 edges
10. `SimClock` - 75 edges

## Surprising Connections (you probably didn't know these)
- `{closure#13}()` --calls--> `App`  [INFERRED]
  tests/Feature/Web/SettingsScreenTest.php → app/Notifications/SettingSource.php
- `{closure#8}()` --calls--> `App`  [INFERRED]
  tests/Feature/Web/SettingsScreenTest.php → app/Notifications/SettingSource.php
- `{closure#1}()` --calls--> `App`  [INFERRED]
  tests/Feature/Support/ClockBindingTest.php → app/Notifications/SettingSource.php
- `up()` --calls--> `App`  [INFERRED]
  database/migrations/2026_11_09_000016_create_market_universe_tables.php → app/Notifications/SettingSource.php
- `{closure#2}()` --calls--> `App`  [INFERRED]
  tests/Feature/Agents/TokensTest.php → app/Notifications/SettingSource.php

## Import Cycles
- None detected.

## Communities (219 total, 59 thin omitted)

### Community 0 - "CompiledStrategyTest.php"
Cohesion: 0.15
Nodes (18): LimitedStrategy, PositionView, aggregatedBars(), {closure#1}(), {closure#11}(), {closure#13}(), {closure#16}(), {closure#17}() (+10 more)

### Community 1 - "engineHarness"
Cohesion: 0.09
Nodes (42): EngineCommand, ScriptedStrategy, balances(), {closure#1}(), {closure#10}(), {closure#13}(), {closure#14}(), {closure#16}() (+34 more)

### Community 2 - "CanonicalJson"
Cohesion: 0.30
Nodes (6): CanonicalJson, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}()

### Community 3 - "DslError"
Cohesion: 0.11
Nodes (14): {closure#1}(), {closure#1}(), {closure#2}(), {closure#5}(), {closure#8}(), DslError, YamlList, YamlReader (+6 more)

### Community 4 - "Engine"
Cohesion: 0.12
Nodes (6): DecisionLog, DecisionRecord, EngineRecord, Engine, ReconciliationMismatch, {closure#3}()

### Community 5 - ".parse"
Cohesion: 0.09
Nodes (37): {closure#12}(), baselinePairs(), {closure#17}(), {closure#5}(), {closure#4}(), carryDecisions(), {closure#10}(), {closure#11}() (+29 more)

### Community 7 - "SettingsScreen"
Cohesion: 0.12
Nodes (3): OpenAccount, StoreKeys, SettingsScreen

### Community 8 - "CompiledStrategy.php"
Cohesion: 0.13
Nodes (7): {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#42}(), Compiler, Program

### Community 9 - "Side"
Cohesion: 0.18
Nodes (42): Side, Buy, Sell, btc(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}() (+34 more)

### Community 10 - "InstrumentSync"
Cohesion: 0.28
Nodes (3): {closure#1}(), InstrumentSync, SyncResult

### Community 11 - ".of"
Cohesion: 0.19
Nodes (16): {closure#2}(), {closure#1}(), usdt(), {closure#7}(), {closure#1}(), {closure#11}(), {closure#12}(), {closure#13}() (+8 more)

### Community 12 - "Candle"
Cohesion: 0.09
Nodes (31): {closure#1}(), {closure#3}(), Candle, {closure#1}(), {closure#2}(), candleWith(), {closure#1}(), {closure#10}() (+23 more)

### Community 13 - "ExchangeVerificationTest.php"
Cohesion: 0.10
Nodes (30): VerificationSource, DailyArchive, Rest, {closure#1}(), {closure#12}(), {closure#13}(), {closure#2}(), {closure#3}() (+22 more)

### Community 14 - ".isLessThan"
Cohesion: 0.11
Nodes (4): InvalidNumber, Quantity, {closure#10}(), {closure#9}()

### Community 15 - "PolicyLimits"
Cohesion: 0.05
Nodes (38): {closure#1}(), {closure#2}(), {closure#3}(), InstrumentDirectory, StoredInstruments, {closure#1}(), CompileStrategy, GridPart (+30 more)

### Community 16 - "Node"
Cohesion: 0.11
Nodes (21): Binary, Lexer, Parser, Token, TokenKind, Colon, Comma, Dot (+13 more)

### Community 17 - "Illuminate\Console\Command"
Cohesion: 0.07
Nodes (19): CreateMcpToken, RevokeMcpToken, Tokens, ApproveLive, ConfirmsOperator, CreateAccount, FlattenEngine, HaltEngine (+11 more)

### Community 18 - "Clock"
Cohesion: 0.05
Nodes (26): BackfillStep, CandleRepository, {closure#2}(), {closure#4}(), CheckMarketData, CandleSource, Archive, Rest (+18 more)

### Community 19 - "web"
Cohesion: 0.16
Nodes (11): {closure#1}(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#7}(), {closure#1}() (+3 more)

### Community 20 - "Ledger"
Cohesion: 0.22
Nodes (26): balanceOf(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#15}(), {closure#16}() (+18 more)

### Community 21 - "Illuminate\Database\Schema\Blueprint"
Cohesion: 0.19
Nodes (10): {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#1}(), {closure#2}(), {closure#3}() (+2 more)

### Community 22 - "generate.py"
Cohesion: 0.07
Nodes (30): atr(), bars(), bb(), change(), dmi(), donchian(), ema(), highest() (+22 more)

### Community 23 - "App\Strategy\Dsl\Compiler\CompiledStrategy"
Cohesion: 0.08
Nodes (14): {closure#1}(), {closure#2}(), {closure#6}(), {closure#7}(), {closure#8}(), {closure#9}(), {closure#1}(), {closure#2}() (+6 more)

### Community 24 - "StrategyIdentity"
Cohesion: 0.05
Nodes (22): BuyAndHold, DonchianBreakout, MovingAverageTrend, BuildableStrategy, Close, EntryOrder, Intent, MoveStop (+14 more)

### Community 25 - "RejectionReason"
Cohesion: 0.08
Nodes (30): AccountView, RejectionReason, BelowMinimum, Breaker, Cooldown, DuplicateIntent, EntryWorking, GrossExposure (+22 more)

### Community 26 - "Venue"
Cohesion: 0.10
Nodes (5): Venue, WarmingVenue, {closure#12}(), {closure#32}(), {closure#34}()

### Community 27 - "Price"
Cohesion: 0.07
Nodes (26): Balance, ClientOrderId, Income, IncomeKind, Funding, Liquidation, OcoRequest, OrderAck (+18 more)

### Community 28 - ".of"
Cohesion: 0.22
Nodes (41): account(), accountPolicy(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#2}() (+33 more)

### Community 29 - "SimulatedPerpVenueTest.php"
Cohesion: 0.17
Nodes (27): {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#15}(), {closure#18}() (+19 more)

### Community 30 - "App\Market\Stream\PhrityTransport"
Cohesion: 0.12
Nodes (3): PhrityTransport, StreamFailed, FakeTransport

### Community 31 - "oi-momentum-long.strategy.yaml"
Cohesion: 0.07
Nodes (27): entries, long, exits, stop_loss, take_profit, when, fails_when, family (+19 more)

### Community 32 - ".of"
Cohesion: 0.09
Nodes (31): {closure#5}(), {closure#1}(), {closure#2}(), {closure#8}(), {closure#5}(), {closure#41}(), {closure#44}(), {closure#45}() (+23 more)

### Community 33 - "EnginePerpTest.php"
Cohesion: 0.20
Nodes (16): InMemoryDecisionLog, {closure#10}(), {closure#11}(), {closure#13}(), {closure#14}(), {closure#2}(), {closure#5}(), {closure#6}() (+8 more)

### Community 34 - "BinanceArchive"
Cohesion: 0.12
Nodes (6): ArchivePeriod, BinanceArchive, {closure#10}(), ExchangeNumber, {closure#1}(), {closure#2}()

### Community 35 - "App"
Cohesion: 0.09
Nodes (40): SendTelegram, App, {closure#2}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#2}(), {closure#4}() (+32 more)

### Community 36 - "SimClock"
Cohesion: 0.09
Nodes (19): SimClock, WallClock, {closure#1}(), {closure#2}(), {closure#3}(), {closure#1}(), {closure#1}(), {closure#2}() (+11 more)

### Community 37 - "LiveAccountTest.php"
Cohesion: 0.22
Nodes (12): approveLive(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}() (+4 more)

### Community 38 - "OrderRole"
Cohesion: 0.11
Nodes (19): OrderRole, Entry, Exit, Flatten, Stop, Target, ProtectionPlan, TrackedOrder (+11 more)

### Community 39 - "BacktestTest.php"
Cohesion: 0.08
Nodes (32): FeeSchedule, CostModel, CandleFeed, FundingRate, RulesHistory, Backtest, {closure#1}(), BacktestResult (+24 more)

### Community 40 - "App\Strategy\Dsl\Document\DocumentChecker"
Cohesion: 0.07
Nodes (25): Argument, BoolLiteral, Call, DurationLiteral, Identifier, Lookback, Node, NumberLiteral (+17 more)

### Community 41 - "ema-cross-scalp-long.strategy.yaml"
Cohesion: 0.06
Nodes (33): entries, long, exits, stop_loss, take_profit, time_stop, when, fails_when (+25 more)

### Community 42 - "Decimal"
Cohesion: 0.04
Nodes (45): {closure#2}(), {closure#6}(), {closure#1}(), {closure#2}(), RoundTrip, {closure#1}(), {closure#2}(), {closure#3}() (+37 more)

### Community 43 - ".zero"
Cohesion: 0.08
Nodes (13): Liquidity, Maker, Taker, {closure#2}(), {closure#3}(), SimulatedFuturesBook, SimulatedOrder, {closure#1}() (+5 more)

### Community 44 - "ScalpingArchiveTest.php"
Cohesion: 0.14
Nodes (14): OpenInterest, PriceMinute, TradeFlow, {closure#10}(), {closure#11}(), {closure#2}(), {closure#3}(), {closure#4}() (+6 more)

### Community 45 - "Timeframe"
Cohesion: 0.15
Nodes (19): CandleAggregator, CandleSequenceBroken, {closure#3}(), {closure#6}(), CandleFactory, aggregate(), {closure#1}(), {closure#10}() (+11 more)

### Community 46 - "Illuminate\Database\Eloquent\Model"
Cohesion: 0.04
Nodes (46): McpCall, McpToken, {closure#5}(), IntentRecord, {closure#1}(), Account, AccountEquity, AccountSnapshot (+38 more)

### Community 47 - "editor.js"
Cohesion: 0.19
Nodes (12): dependencies, codemirror@^6.0.2, @codemirror/lang-yaml@^6.1.3, @codemirror/language@^6.13.1, @lezer/highlight@^1.2.5, codemirror, @codemirror/lang-yaml, @codemirror/language (+4 more)

### Community 48 - "Strategy"
Cohesion: 0.19
Nodes (17): Strategy, baselineDocument(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#14}(), {closure#15}() (+9 more)

### Community 49 - "BinanceRestClientTest.php"
Cohesion: 0.11
Nodes (26): {closure#3}(), {closure#11}(), binanceClient(), {closure#1}(), {closure#11}(), {closure#13}(), {closure#14}(), {closure#15}() (+18 more)

### Community 50 - "ema-cross-scalp-short.strategy.yaml"
Cohesion: 0.06
Nodes (33): entries, short, exits, stop_loss, take_profit, time_stop, when, fails_when (+25 more)

### Community 51 - ".now"
Cohesion: 0.05
Nodes (16): {closure#1}(), {closure#2}(), Deployment, ReauthController, AccountScreen, HaltButton, {closure#1}(), ModeBadge (+8 more)

### Community 52 - "RiskState"
Cohesion: 0.30
Nodes (5): RiskState, {closure#15}(), {closure#17}(), {closure#18}(), {closure#22}()

### Community 53 - "Laravel\Mcp\Response"
Cohesion: 0.10
Nodes (11): ChartTool, GetCandles, GetIndicators, GetJob, ListStrategies, {closure#1}(), ListTrials, SaveStrategy (+3 more)

### Community 54 - "postgres"
Cohesion: 0.10
Nodes (19): POSTGRES_DB, POSTGRES_PASSWORD, POSTGRES_USER, interval, retries, test, timeout, environment (+11 more)

### Community 55 - "InvalidArgumentException"
Cohesion: 0.06
Nodes (13): {closure#1}(), {closure#2}(), StreamCandles, {closure#1}(), {closure#2}(), Universe, UniverseEntry, {closure#1}() (+5 more)

### Community 56 - "SizedStrategyTest.php"
Cohesion: 0.12
Nodes (11): SizedStrategy, {closure#2}(), {closure#1}(), {closure#2}(), __construct(), enterOnce(), identity(), limits() (+3 more)

### Community 57 - "donchian-breakout-short.strategy.yaml"
Cohesion: 0.07
Nodes (26): entries, short, default, range, step, default, range, step (+18 more)

### Community 58 - "IndicatorReferenceTest.php"
Cohesion: 0.05
Nodes (53): Atr, BarIndicator, Bollinger, Change, Donchian, DonchianChannel, Ema, Highest (+45 more)

### Community 59 - "support.mjs"
Cohesion: 0.26
Nodes (9): @playwright/test, confirmed(), checkLayout(), EMAIL, freshCode(), PASSWORD, reauthenticate(), totp() (+1 more)

### Community 60 - "RunBacktestCommandTest.php"
Cohesion: 0.14
Nodes (28): BacktestRun, {closure#1}(), {closure#4}(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}() (+20 more)

### Community 61 - "DecimalMath"
Cohesion: 0.09
Nodes (17): DeflatedSharpe, Normal, DecimalMath, {closure#12}(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}() (+9 more)

### Community 62 - "OrderManagerTest.php"
Cohesion: 0.26
Nodes (16): InstrumentRules, {closure#1}(), btcUsdt(), {closure#10}(), {closure#15}(), {closure#16}(), {closure#2}(), {closure#4}() (+8 more)

### Community 63 - "FortifyServiceProvider.php"
Cohesion: 0.24
Nodes (3): {closure#7}(), {closure#3}(), {closure#4}()

### Community 64 - "healthyMachine"
Cohesion: 0.39
Nodes (8): {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), healthAt(), healthyMachine(), telegramSent()

### Community 65 - "donchian-breakout.strategy.yaml"
Cohesion: 0.07
Nodes (26): entries, long, default, range, step, default, range, step (+18 more)

### Community 66 - ".run"
Cohesion: 0.07
Nodes (31): RiskProfile, Account, Benchmark, RiskPolicy, PerformanceMetrics, TradeStatistics, Report, {closure#1}() (+23 more)

### Community 68 - "App\Strategy\Dsl\Document\DocumentChecker"
Cohesion: 0.06
Nodes (33): ChartScreen, {closure#2}(), {closure#3}(), {closure#3}(), {closure#4}(), {closure#9}(), ParameterSpec, DslWriter (+25 more)

### Community 69 - "InvalidArgumentException"
Cohesion: 0.08
Nodes (7): AuthenticateMcpToken, ExportStrategies, GridBacktest, ImportStrategies, PreparesBacktests, PromoteStrategy, RunBacktest

### Community 70 - "CandleFeedTest.php"
Cohesion: 0.25
Nodes (15): MinuteCandles, {closure#1}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#2}(), {closure#3}(), {closure#4}() (+7 more)

### Community 72 - "SimulationQueue"
Cohesion: 0.16
Nodes (4): RunBacktest, RunGrid, SimulationTool, SimulationQueue

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
Cohesion: 0.18
Nodes (17): Cscv, closeTo(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#13}(), {closure#14}(), {closure#15}() (+9 more)

### Community 82 - "MonteCarlo"
Cohesion: 0.15
Nodes (10): {closure#4}(), MonteCarlo, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}() (+2 more)

### Community 83 - "App\Support\Number\Decimal"
Cohesion: 0.07
Nodes (52): {closure#8}(), {closure#3}(), {closure#5}(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}() (+44 more)

### Community 84 - "pint/composer.json"
Cohesion: 0.20
Nodes (9): config, sort-packages, description, license, name, require, laravel/pint, php (+1 more)

### Community 85 - ".evaluate"
Cohesion: 0.24
Nodes (9): Gates, brokenGate(), {closure#1}(), {closure#3}(), {closure#4}(), {closure#6}(), {closure#7}(), gate() (+1 more)

### Community 86 - "RunSimulation.php"
Cohesion: 0.13
Nodes (4): ImportMarketData, RunSimulation, SimulationJob, {closure#7}()

### Community 87 - "Ledger"
Cohesion: 0.26
Nodes (4): Ledger, LedgerViolation, {closure#19}(), {closure#14}()

### Community 88 - "require-dev"
Cohesion: 0.22
Nodes (9): require-dev, fakerphp/faker, larastan/larastan, laravel/pail, laravel/pao, mockery/mockery, nunomaduro/collision, pestphp/pest (+1 more)

### Community 89 - "scripts-descriptions"
Cohesion: 0.25
Nodes (8): scripts-descriptions, analyse, format, mutate, test, test:network, test:testnet, tools:install

### Community 90 - "InstrumentStatus"
Cohesion: 0.16
Nodes (9): InstrumentDefinition, InstrumentStatus, Delisted, Halted, Trading, {closure#3}(), {closure#4}(), {closure#5}() (+1 more)

### Community 91 - ".fromStrings"
Cohesion: 0.11
Nodes (12): RegimeLabels, {closure#2}(), {closure#1}(), {closure#42}(), {closure#1}(), {closure#1}(), {closure#2}(), {closure#4}() (+4 more)

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
Cohesion: 0.11
Nodes (7): DiskUsage, {closure#1}(), HealthChecks, HealthResult, LocalDiskUsage, {closure#6}(), FakeDisk

### Community 97 - "jobs"
Cohesion: 0.25
Nodes (8): name, runs-on, name, runs-on, steps, jobs, analyse, audit

### Community 98 - ".parse"
Cohesion: 0.12
Nodes (18): AstPrinter, Group, Exit, Indicator, Order, Perpetual, Position, Price (+10 more)

### Community 99 - "psr-4"
Cohesion: 0.40
Nodes (5): autoload, psr-4, App\\, Database\\Factories\\, Database\\Seeders\\

### Community 100 - "require"
Cohesion: 0.22
Nodes (9): require, bacon/bacon-qr-code, laravel/fortify, laravel/framework, laravel/mcp, laravel/tinker, livewire/livewire, php (+1 more)

### Community 102 - "App\Support\Clock\Clock"
Cohesion: 0.05
Nodes (11): {closure#4}(), Notifier, {closure#1}(), CandleStream, ScalpingCollector, StreamTransport, CheckHealth, HealthAlerts (+3 more)

### Community 103 - "GridBacktest.php"
Cohesion: 0.14
Nodes (9): {closure#2}(), {closure#3}(), {closure#6}(), {closure#8}(), {closure#2}(), {closure#2}(), {closure#3}(), SetOutcome (+1 more)

### Community 104 - "uses"
Cohesion: 0.21
Nodes (12): steps, name, runs-on, steps, frontend, lint, name, runs-on (+4 more)

### Community 105 - "SettingsScreenTest.php"
Cohesion: 0.20
Nodes (13): {closure#10}(), {closure#11}(), {closure#13}(), {closure#14}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}() (+5 more)

### Community 106 - "devDependencies"
Cohesion: 0.14
Nodes (18): devDependencies, concurrently@^10.0.3, laravel-vite-plugin@^3.1, @playwright/test@1.63.0, tailwindcss@^4.0.0, @tailwindcss/vite@^4.0.0, vite@^8.0.0, optionalDependencies (+10 more)

### Community 108 - "BinanceVenueTest.php"
Cohesion: 0.09
Nodes (17): {closure#2}(), BinanceVenue, {closure#2}(), {closure#3}(), {closure#4}(), BinanceApiError, SignedBinanceClient, binanceVenue() (+9 more)

### Community 117 - "ParamKind"
Cohesion: 0.15
Nodes (10): {closure#2}(), ParamKind, Anchor, Count, Duration, Number, Percent, Series (+2 more)

### Community 118 - "App\Agents\Mcp\TradingServer"
Cohesion: 0.18
Nodes (3): GetFunding, ListInstruments, TradingServer

### Community 119 - "VenuePosition"
Cohesion: 0.36
Nodes (3): VenuePosition, {closure#1}(), SimulatedPosition

### Community 120 - "buy-and-hold.strategy.yaml"
Cohesion: 0.12
Nodes (16): entries, long, exits, stop_loss, fails_when, family, hypothesis, when (+8 more)

### Community 121 - "ChartSeriesTest.php"
Cohesion: 0.26
Nodes (10): chart(), {closure#10}(), {closure#11}(), {closure#2}(), {closure#3}(), {closure#5}(), {closure#6}(), {closure#7}() (+2 more)

### Community 122 - "TokensTest.php"
Cohesion: 0.31
Nodes (6): {closure#3}(), {closure#4}(), contrastRatio(), contrastRules(), relativeLuminance(), themeTokens()

### Community 123 - "ArchiveImportTest.php"
Cohesion: 0.29
Nodes (10): archiveBytes(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}() (+2 more)

### Community 124 - "App\Execution\Models\Account"
Cohesion: 0.07
Nodes (9): AccountRuntime, LiveGate, RunEngine, LiveApproval, {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}() (+1 more)

### Community 125 - "Illuminate\Database\Migrations\Migration"
Cohesion: 0.15
Nodes (3): {closure#1}(), {closure#1}(), {closure#1}()

### Community 126 - "ExitReason"
Cohesion: 0.19
Nodes (24): ExitReason, Flatten, Liquidation, Signal, Stop, Target, {closure#1}(), {closure#10}() (+16 more)

### Community 127 - ".register"
Cohesion: 0.08
Nodes (28): {closure#1}(), {closure#10}(), {closure#13}(), {closure#15}(), {closure#16}(), {closure#17}(), {closure#18}(), {closure#4}() (+20 more)

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
Cohesion: 0.14
Nodes (7): SendDailyReport, NotificationSettings, SettingSource, Env, None, TelegramClient, TelegramSettings

### Community 133 - "EngineMode"
Cohesion: 0.40
Nodes (5): EngineMode, Backtest, Live, Paper, Testnet

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

### Community 140 - "ScalpingFunctionsTest.php"
Cohesion: 0.11
Nodes (14): {closure#1}(), {closure#2}(), {closure#3}(), fakeTestnet(), testnetAccount(), backtestableScalpingHour(), {closure#3}(), {closure#5}() (+6 more)

### Community 141 - ".parse"
Cohesion: 0.33
Nodes (6): KlineMessage, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), streamLines()

### Community 142 - "Statistics"
Cohesion: 0.25
Nodes (11): Statistics, {closure#1}(), {closure#3}(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}() (+3 more)

### Community 143 - "CandleRepositoryTest.php"
Cohesion: 0.28
Nodes (11): candleStore(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}() (+3 more)

### Community 145 - "Asset"
Cohesion: 0.12
Nodes (6): Marks, {closure#2}(), ParticipantResult, Asset, {closure#25}(), {closure#9}()

### Community 146 - "RuntimeException"
Cohesion: 0.25
Nodes (3): ReplayDivergence, ArchiveMissing, ChecksumMismatch

### Community 147 - "scripts"
Cohesion: 0.50
Nodes (4): scripts, build, dev, e2e

### Community 148 - "0001_01_01_000002_create_jobs_table.php"
Cohesion: 0.33
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

### Community 151 - "MarketBreakersTest.php"
Cohesion: 0.29
Nodes (7): {closure#4}(), BreakerEvent, MarketBreakers, breakerMinute(), {closure#1}(), {closure#2}(), {closure#3}()

### Community 157 - "strategies-screen.blade.php"
Cohesion: 0.33
Nodes (5): open, promote, promoteDeployment, run, save

### Community 159 - "extra"
Cohesion: 0.67
Nodes (3): extra, laravel, dont-discover

### Community 174 - "StrategyLibrary"
Cohesion: 0.29
Nodes (3): CompileStrategy, GetStrategy, StrategyLibrary

### Community 175 - "Carbon\CarbonImmutable"
Cohesion: 0.06
Nodes (25): {closure#1}(), Backfill, {closure#1}(), {closure#11}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}() (+17 more)

### Community 176 - "MarketType"
Cohesion: 0.29
Nodes (10): MarketType, Perp, Spot, {closure#1}(), {closure#3}(), goldenMinutes(), goldenRates(), perpetualReferencePrice() (+2 more)

### Community 177 - "WalkForward.php"
Cohesion: 0.15
Nodes (9): {closure#11}(), {closure#12}(), {closure#1}(), {closure#5}(), {closure#6}(), {closure#7}(), {closure#8}(), {closure#9}() (+1 more)

### Community 178 - "Engine.php"
Cohesion: 0.09
Nodes (11): {closure#1}(), {closure#11}(), {closure#2}(), {closure#3}(), {closure#9}(), RiskEvent, RiskEventKind, DailyLossHalt (+3 more)

### Community 180 - ".evaluate"
Cohesion: 0.29
Nodes (7): WalkForward, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), wfSet()

### Community 181 - "TerminalQrCodeTest.php"
Cohesion: 0.20
Nodes (7): TerminalQrCode, {closure#3}(), {closure#4}(), {closure#5}(), expectedModules(), readColoured(), readPlain()

### Community 182 - "Timeframe"
Cohesion: 0.08
Nodes (17): {closure#4}(), InvalidCandle, CarryTemplate, {closure#8}(), Timeframe, FifteenMinutes, FiveMinutes, FourHours (+9 more)

### Community 189 - ".priceMinutes"
Cohesion: 0.20
Nodes (5): EpochTime, Json, {closure#1}(), {closure#2}(), {closure#3}()

### Community 193 - "ScalpingCollectorTest.php"
Cohesion: 0.32
Nodes (9): aggTrade(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#5}(), collector(), defaultBinanceFakes(), markUpdate() (+1 more)

### Community 195 - "2026_11_09_000016_create_market_universe_tables.php"
Cohesion: 0.40
Nodes (3): {closure#1}(), {closure#2}(), up()

### Community 196 - "BinanceEndpoints"
Cohesion: 0.18
Nodes (4): BinanceEndpoints, Sleeper, SystemSleeper, RecordingSleeper

### Community 197 - "2026_11_09_000017_create_live_tables.php"
Cohesion: 0.33
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

### Community 198 - "start-local.ps1"
Cohesion: 0.27
Nodes (4): Stop-Postgres(), Stop-ProcessTree(), Test-PostgresRunning(), Write-Step()

### Community 200 - ".times"
Cohesion: 0.07
Nodes (10): Fill, PerpPosition, Position, {closure#4}(), TradeMemory, VolumeProfile, Perpetual, {closure#16}() (+2 more)

### Community 202 - "SimulationJobsTest.php"
Cohesion: 0.21
Nodes (5): {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), savedEmaCross()

### Community 204 - "StrategyGates.php"
Cohesion: 0.18
Nodes (5): {closure#1}(), {closure#4}(), {closure#5}(), {closure#7}(), {closure#9}()

### Community 205 - "2026_11_09_000015_create_scalping_tables.php"
Cohesion: 0.33
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

### Community 207 - "Percent"
Cohesion: 0.24
Nodes (3): Percent, {closure#14}(), {closure#15}()

### Community 208 - "BinanceRequestFailed"
Cohesion: 0.24
Nodes (5): BinanceRequestFailed, BinanceUnreachable, LocationRestricted, RateLimited, RequestFailed

### Community 211 - "Expression"
Cohesion: 0.32
Nodes (4): {closure#7}(), EntrySpec, ExitSpec, Expression

### Community 212 - "bootstrap/app.php"
Cohesion: 0.32
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

### Community 213 - "{closure#40}"
Cohesion: 0.29
Nodes (4): {closure#1}(), {closure#34}(), {closure#40}(), orderUpdate()

### Community 216 - ".number"
Cohesion: 0.33
Nodes (4): {closure#4}(), {closure#40}(), {closure#41}(), {closure#57}()

## Knowledge Gaps
- **147 isolated node(s):** `Exit`, `Indicator`, `Order`, `Perpetual`, `Position` (+142 more)
  These have ≤1 connection - possible missing edges. (Counts symbols only; 941 node(s) total have ≤1 connection when file, concept and rationale nodes are included.)
- **59 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `Decimal` connect `Decimal` to `CompiledStrategyTest.php`, `engineHarness`, `CanonicalJson`, `DslError`, `Engine`, `.parse`, `DocumentChecker`, `Side`, `InstrumentSync`, `.of`, `Candle`, `ScalpingFunctionsTest.php`, `.isLessThan`, `Statistics`, `PolicyLimits`, `Asset`, `Clock`, `Ledger`, `App\Strategy\Dsl\Compiler\CompiledStrategy`, `StrategyIdentity`, `RejectionReason`, `Venue`, `Price`, `.of`, `.of`, `EnginePerpTest.php`, `OrderRole`, `BacktestTest.php`, `App\Strategy\Dsl\Document\DocumentChecker`, `.zero`, `Timeframe`, `Carbon\CarbonImmutable`, `Strategy`, `WalkForward.php`, `Engine.php`, `.now`, `RiskState`, `MarketType`, `Timeframe`, `InvalidArgumentException`, `SizedStrategyTest.php`, `CompiledStrategy`, `IndicatorReferenceTest.php`, `.evaluate`, `RunBacktestCommandTest.php`, `DecimalMath`, `OrderManagerTest.php`, `ForwardingVenue`, `.handle`, `.run`, `FundingCarry`, `App\Strategy\Dsl\Document\DocumentChecker`, `InvalidArgumentException`, `CandleFeedTest.php`, `BinanceRestClient`, `.times`, `.isFull`, `StrategyGates.php`, `.measure`, `Money`, `Percent`, `DirectionalMovement`, `MonteCarlo`, `Expression`, `App\Support\Number\Decimal`, `.evaluate`, `DecimalMath`, `Ledger`, `InstrumentStatus`, `.fromStrings`, `GridBacktest.php`, `BinanceVenueTest.php`, `VenuePosition`, `ChartSeriesTest.php`, `ExitReason`?**
  _High betweenness centrality (0.129) - this node is a cross-community bridge._
- **What connects `Exit`, `Indicator`, `Order` to the rest of the system?**
  _147 weakly-connected nodes found - possible missing edges._
- **Should `CompiledStrategyTest.php` be split into smaller, more focused modules?**
  _Cohesion score 0.14666666666666667 - nodes in this community are weakly interconnected._
- **Why does `Symbol` connect `StrategyIdentity` to `CompiledStrategyTest.php`, `engineHarness`, `CanonicalJson`, `DslError`, `Engine`, `.parse`, `DocumentChecker`, `SettingsScreen`, `Side`, `ScalpingFunctionsTest.php`, `ExchangeVerificationTest.php`, `PolicyLimits`, `CandleRepositoryTest.php`, `Asset`, `Clock`, `Ledger`, `RejectionReason`, `Venue`, `Price`, `.of`, `EnginePerpTest.php`, `OrderRole`, `BacktestTest.php`, `Decimal`, `.zero`, `Carbon\CarbonImmutable`, `Strategy`, `MarketType`, `Engine.php`, `.now`, `Laravel\Mcp\Response`, `Timeframe`, `InvalidArgumentException`, `CompiledStrategy`, `RunBacktestCommandTest.php`, `OrderManagerTest.php`, `ForwardingVenue`, `.handle`, `.run`, `FundingCarry`, `CandleFeedTest.php`, `BinanceRestClient`, `.times`, `StrategyGates.php`, `.measure`, `Ledger`, `InstrumentStatus`, `BinanceVenueTest.php`, `VenuePosition`, `ChartSeriesTest.php`, `ExitReason`, `.register`?**
  _High betweenness centrality (0.060) - this node is a cross-community bridge._
- **Should `engineHarness` be split into smaller, more focused modules?**
  _Cohesion score 0.09137529137529138 - nodes in this community are weakly interconnected._
- **Why does `Timeframe` connect `Timeframe` to `CompiledStrategyTest.php`, `engineHarness`, `DslError`, `.parse`, `DocumentChecker`, `CompiledStrategy.php`, `Side`, `Candle`, `Statistics`, `CandleRepositoryTest.php`, `PolicyLimits`, `Clock`, `App\Strategy\Dsl\Compiler\CompiledStrategy`, `StrategyIdentity`, `MarketBreakersTest.php`, `EnginePerpTest.php`, `BinanceArchive`, `App\Strategy\Dsl\Document\DocumentChecker`, `.zero`, `Timeframe`, `.now`, `Laravel\Mcp\Response`, `CompiledStrategy`, `IndicatorReferenceTest.php`, `.priceMinutes`, `DecimalMath`, `OrderManagerTest.php`, `.handle`, `FundingCarry`, `App\Strategy\Dsl\Document\DocumentChecker`, `InvalidArgumentException`, `CandleFeedTest.php`, `StrategyGates.php`, `.fromStrings`, `ChartSeriesTest.php`?**
  _High betweenness centrality (0.035) - this node is a cross-community bridge._
- **Should `DslError` be split into smaller, more focused modules?**
  _Cohesion score 0.11470985155195682 - nodes in this community are weakly interconnected._