# Graph Report - trading-platform  (2026-11-09)

## Corpus Check
- 579 files · ~238,862 words
- Verdict: corpus is large enough that graph structure adds value.
- Unclassified: 101 file(s) not represented in the graph (top: .zip 38, .CHECKSUM 38, (none) 19)

## Summary
- 4612 nodes · 15896 edges · 199 communities (144 shown, 55 thin omitted)
- Extraction: 91% EXTRACTED · 9% INFERRED · 0% AMBIGUOUS · INFERRED: 1399 edges (avg confidence: 0.85)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `db9e9a53`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- QualityReport
- EngineTest.php
- CanonicalJson
- DslError
- Asset
- .parse
- DocumentChecker
- .times
- CompiledStrategy.php
- Side
- Instrument
- .measure
- Candle
- ExchangeVerificationTest.php
- IndicatorsTest.php
- RiskPolicy
- Node
- Clock
- .now
- ScreensTest.php
- LedgerTest.php
- Illuminate\Database\Schema\Blueprint
- generate.py
- ReadBack
- Symbol
- .of
- ClientOrderId
- Engine.php
- SimulatedPerpVenueTest.php
- CompiledStrategy.php
- App\Market\Stream\PhrityTransport
- Account
- .run
- StrategiesScreen.php
- Carbon\CarbonImmutable
- App
- PreparesBacktests.php
- .register
- .isGreaterThan
- BacktestTest.php
- App\Strategy\Dsl\Document\DocumentChecker
- ema-cross-scalp-long.strategy.yaml
- Decimal
- SimulatedVenue
- User
- .walk
- Illuminate\Database\Eloquent\Model
- package.json
- .compute
- BinanceRestClientTest.php
- ema-cross-scalp-short.strategy.yaml
- Illuminate\Database\ConnectionInterface
- Side
- Laravel\Mcp\Response
- postgres
- MonteCarlo
- OrderManagerTest.php
- donchian-breakout-short.strategy.yaml
- IndicatorReferenceTest.php
- support.mjs
- RunBacktestCommandTest.php
- .of
- SettingsScreen
- DecisionRecord
- HealthChecks
- donchian-breakout.strategy.yaml
- .benchmark
- SimClock
- App\Strategy\Dsl\Document\DocumentChecker
- InvalidArgumentException
- Carbon\CarbonImmutable
- .evaluate
- SimulationQueue
- scripts
- ma-trend-short.strategy.yaml
- FundingCarry
- .measure
- composer.json
- jobs
- postgres
- ma-trend.strategy.yaml
- DecimalMath
- CompiledStrategyTest.php
- App\Support\Number\Decimal
- pint/composer.json
- HealthTest.php
- RunSimulation.php
- Timeframe
- require-dev
- scripts-descriptions
- Ledger
- .fromStrings
- config
- uses
- test
- with
- StrategyVersion
- jobs
- .parse
- psr-4
- require
- MarketType
- StrategyGates.php
- uses
- SettingsScreenTest.php
- devDependencies
- App\Execution\Venues\BinanceVenue
- .handle
- App\Agents\Mcp\TradingServer
- CandleFeedTest.php
- buy-and-hold.strategy.yaml
- ChartSeriesTest.php
- TokensTest.php
- ArchiveImportTest.php
- FortifyServiceProvider.php
- Illuminate\Support\Facades\Schema
- BinanceArchive
- App\Support\Clock\Clock
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
- InstrumentStatus
- PaperEngineTest.php
- CandleRepositoryTest.php
- Illuminate\Database\Migrations\Migration
- .of
- BinanceVenueTest.php
- scripts
- 0001_01_01_000002_create_jobs_table.php
- fake-telegram.mjs
- Fill
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
- ChartSeries
- StrategyLibrary
- AppServiceProvider.php
- ChartScreen.php
- StrategySpec
- Percent
- 2026_11_09_000012_create_account_results_tables.php
- 2026_11_09_000011_create_notification_settings_table.php
- RiskPolicy
- Illuminate\Support\Facades\DB
- SimulationJobsTest.php
- ScalpingCollectorTest.php
- 2026_11_09_000013_add_daily_report_to_notification_settings.php
- BinanceRestClient
- WarmingVenue
- .number
- OpenInterest
- StrategyIdentity
- RulesHistory
- ScalpingFunctionsTest.php
- VenuePosition
- .__construct
- 2026_11_09_000015_create_scalping_tables.php

## God Nodes (most connected - your core abstractions)
1. `Decimal` - 521 edges
2. `Side` - 208 edges
3. `Symbol` - 207 edges
4. `Candle` - 136 edges
5. `Timeframe` - 125 edges
6. `App` - 113 edges
7. `Price` - 100 edges
8. `Position` - 96 edges
9. `Clock` - 84 edges
10. `MarketType` - 73 edges

## Surprising Connections (you probably didn't know these)
- `{closure#13}()` --calls--> `App`  [INFERRED]
  tests/Feature/Web/SettingsScreenTest.php → app/Notifications/SettingSource.php
- `{closure#8}()` --calls--> `App`  [INFERRED]
  tests/Feature/Web/SettingsScreenTest.php → app/Notifications/SettingSource.php
- `{closure#3}()` --calls--> `App`  [INFERRED]
  tests/Feature/Network/BinancePublicDataTest.php → app/Notifications/SettingSource.php
- `{closure#5}()` --calls--> `App`  [INFERRED]
  tests/Feature/Network/BinancePublicDataTest.php → app/Notifications/SettingSource.php
- `{closure#4}()` --calls--> `App`  [INFERRED]
  tests/Feature/Simulation/SimulationJobsTest.php → app/Notifications/SettingSource.php

## Import Cycles
- None detected.

## Communities (199 total, 55 thin omitted)

### Community 1 - "EngineTest.php"
Cohesion: 0.08
Nodes (41): EngineCommand, ForwardingVenue, ScriptedStrategy, {closure#10}(), {closure#13}(), {closure#14}(), {closure#16}(), {closure#17}() (+33 more)

### Community 2 - "CanonicalJson"
Cohesion: 0.30
Nodes (6): CanonicalJson, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}()

### Community 3 - "DslError"
Cohesion: 0.15
Nodes (13): {closure#1}(), {closure#5}(), {closure#8}(), DslError, YamlList, YamlReader, YamlScalar, YamlValue (+5 more)

### Community 4 - "Asset"
Cohesion: 0.19
Nodes (4): Marks, Asset, {closure#25}(), {closure#9}()

### Community 5 - ".parse"
Cohesion: 0.08
Nodes (48): Strategy, baselineDocument(), baselinePairs(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#14}() (+40 more)

### Community 7 - ".times"
Cohesion: 0.12
Nodes (4): PerpPosition, TradeMemory, {closure#6}(), {closure#7}()

### Community 8 - "CompiledStrategy.php"
Cohesion: 0.16
Nodes (7): {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#24}(), {closure#42}(), Compiler

### Community 9 - "Side"
Cohesion: 0.19
Nodes (43): Side, Buy, Sell, btc(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}() (+35 more)

### Community 10 - "Instrument"
Cohesion: 0.06
Nodes (22): BackfillStep, CandleRepository, {closure#2}(), {closure#4}(), CandleSource, Archive, Rest, Stream (+14 more)

### Community 11 - ".measure"
Cohesion: 0.11
Nodes (13): InMemoryDecisionLog, {closure#1}(), {closure#2}(), BacktestResult, ParticipantResult, EquityTracker, {closure#1}(), {closure#2}() (+5 more)

### Community 12 - "Candle"
Cohesion: 0.11
Nodes (36): {closure#1}(), {closure#3}(), Candle, {closure#9}(), {closure#1}(), {closure#42}(), candleWith(), {closure#1}() (+28 more)

### Community 13 - "ExchangeVerificationTest.php"
Cohesion: 0.12
Nodes (27): {closure#1}(), {closure#12}(), {closure#13}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}() (+19 more)

### Community 14 - "IndicatorsTest.php"
Cohesion: 0.10
Nodes (19): Atr, Donchian, Ema, Sma, bar(), {closure#1}(), {closure#10}(), {closure#12}() (+11 more)

### Community 15 - "RiskPolicy"
Cohesion: 0.08
Nodes (22): InstrumentDirectory, StoredInstruments, CompileStrategy, PolicyLimits, StrategyRepository, {closure#1}(), {closure#2}(), {closure#3}() (+14 more)

### Community 16 - "Node"
Cohesion: 0.11
Nodes (21): Binary, Lexer, Parser, Token, TokenKind, Colon, Comma, Dot (+13 more)

### Community 17 - "Clock"
Cohesion: 0.05
Nodes (27): CreateMcpToken, RevokeMcpToken, AuthenticateMcpToken, Tokens, FlattenEngine, HaltEngine, IssuesCommands, PauseStrategy (+19 more)

### Community 18 - ".now"
Cohesion: 0.06
Nodes (13): AccountRuntime, {closure#5}(), RunEngine, ReplayDivergence, ArchiveMissing, ChecksumMismatch, BinanceApiError, BinanceRequestFailed (+5 more)

### Community 19 - "ScreensTest.php"
Cohesion: 0.12
Nodes (16): {closure#1}(), {closure#1}(), {closure#2}(), {closure#3}(), fakeTestnet(), testnetAccount(), {closure#2}(), {closure#3}() (+8 more)

### Community 20 - "LedgerTest.php"
Cohesion: 0.21
Nodes (25): balanceOf(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#15}(), {closure#16}() (+17 more)

### Community 21 - "Illuminate\Database\Schema\Blueprint"
Cohesion: 0.19
Nodes (10): {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#1}(), {closure#2}(), {closure#3}() (+2 more)

### Community 22 - "generate.py"
Cohesion: 0.07
Nodes (30): atr(), bars(), bb(), change(), dmi(), donchian(), ema(), highest() (+22 more)

### Community 24 - "Symbol"
Cohesion: 0.05
Nodes (18): {closure#1}(), BuyAndHold, DonchianBreakout, MovingAverageTrend, BuildableStrategy, Close, EntryOrder, Intent (+10 more)

### Community 25 - ".of"
Cohesion: 0.06
Nodes (74): InstrumentRules, AccountView, RejectionReason, BelowMinimum, Breaker, Cooldown, DuplicateIntent, EntryWorking (+66 more)

### Community 26 - "ClientOrderId"
Cohesion: 0.09
Nodes (12): ClientOrderId, OcoRequest, OrderAck, OrderRequest, OrderType, Limit, Market, StopMarket (+4 more)

### Community 27 - "Engine.php"
Cohesion: 0.09
Nodes (10): {closure#12}(), {closure#3}(), {closure#9}(), RiskEvent, RiskEventKind, DailyLossHalt, DrawdownFlatten, DrawdownHalt (+2 more)

### Community 28 - "SimulatedPerpVenueTest.php"
Cohesion: 0.18
Nodes (27): {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#15}(), {closure#18}() (+19 more)

### Community 29 - "CompiledStrategy.php"
Cohesion: 0.09
Nodes (6): {closure#2}(), {closure#5}(), {closure#6}(), {closure#7}(), {closure#8}(), {closure#9}()

### Community 30 - "App\Market\Stream\PhrityTransport"
Cohesion: 0.12
Nodes (3): PhrityTransport, StreamFailed, FakeTransport

### Community 31 - "Account"
Cohesion: 0.11
Nodes (11): OpenAccount, CreateAccount, StoreKeys, EngineMode, Backtest, Live, Paper, Testnet (+3 more)

### Community 32 - ".run"
Cohesion: 0.32
Nodes (6): btcMinutes(), {closure#1}(), {closure#2}(), {closure#3}(), referencePrice(), GoldenData

### Community 33 - "StrategiesScreen.php"
Cohesion: 0.08
Nodes (9): AccountSnapshot, EngineCommandRow, ReauthController, AccountScreen, ChartScreen, HaltButton, {closure#1}(), ModeBadge (+1 more)

### Community 34 - "Carbon\CarbonImmutable"
Cohesion: 0.17
Nodes (12): PriceMinute, TradeFlow, {closure#10}(), {closure#11}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}() (+4 more)

### Community 35 - "App"
Cohesion: 0.08
Nodes (27): SendTelegram, App, {closure#1}(), {closure#2}(), {closure#3}(), {closure#1}(), {closure#2}(), {closure#4}() (+19 more)

### Community 36 - "PreparesBacktests.php"
Cohesion: 0.13
Nodes (3): {closure#1}(), {closure#2}(), PreparesBacktests

### Community 37 - ".register"
Cohesion: 0.12
Nodes (16): {closure#11}(), {closure#14}(), {closure#15}(), {closure#16}(), {closure#4}(), {closure#6}(), {closure#7}(), {closure#8}() (+8 more)

### Community 39 - "BacktestTest.php"
Cohesion: 0.21
Nodes (13): FeeSchedule, CostModel, CandleFeed, Backtest, RunDefinition, InMemoryCandleFeed, backtestSetup(), {closure#1}() (+5 more)

### Community 40 - "App\Strategy\Dsl\Document\DocumentChecker"
Cohesion: 0.06
Nodes (27): Argument, BoolLiteral, Call, DurationLiteral, Identifier, Lookback, Node, NumberLiteral (+19 more)

### Community 41 - "ema-cross-scalp-long.strategy.yaml"
Cohesion: 0.06
Nodes (33): entries, long, exits, stop_loss, take_profit, time_stop, when, fails_when (+25 more)

### Community 42 - "Decimal"
Cohesion: 0.04
Nodes (49): {closure#6}(), RoundTrip, {closure#1}(), {closure#2}(), {closure#3}(), {closure#10}(), {closure#5}(), {closure#4}() (+41 more)

### Community 43 - "SimulatedVenue"
Cohesion: 0.07
Nodes (17): OrderStatus, Cancelled, Expired, Filled, New, PartiallyFilled, {closure#11}(), {closure#2}() (+9 more)

### Community 45 - ".walk"
Cohesion: 0.16
Nodes (19): CandleAggregator, CandleSequenceBroken, {closure#3}(), {closure#6}(), CandleFactory, aggregate(), {closure#1}(), {closure#10}() (+11 more)

### Community 46 - "Illuminate\Database\Eloquent\Model"
Cohesion: 0.07
Nodes (11): McpCall, McpToken, PlainDecimal, Deployment, ExchangeKey, JournalEntry, {closure#1}(), InstrumentFilter (+3 more)

### Community 47 - "package.json"
Cohesion: 0.15
Nodes (17): dependencies, codemirror@^6.0.2, @codemirror/lang-yaml@^6.1.3, @codemirror/language@^6.13.1, @lezer/highlight@^1.2.5, lightweight-charts@^5.2.1, optionalDependencies, @laravel/multiplex@^0.4.1 (+9 more)

### Community 49 - "BinanceRestClientTest.php"
Cohesion: 0.08
Nodes (32): {closure#3}(), {closure#13}(), {closure#5}(), binanceClient(), {closure#1}(), {closure#11}(), {closure#12}(), {closure#13}() (+24 more)

### Community 50 - "ema-cross-scalp-short.strategy.yaml"
Cohesion: 0.06
Nodes (33): entries, short, exits, stop_loss, take_profit, time_stop, when, fails_when (+25 more)

### Community 51 - "Illuminate\Database\ConnectionInterface"
Cohesion: 0.07
Nodes (10): FundingRateRepository, Heartbeats, {closure#1}(), InstrumentSync, SyncResult, QualityCheck, GridBacktest, {closure#2}() (+2 more)

### Community 52 - "Side"
Cohesion: 0.15
Nodes (23): ExitReason, Flatten, Liquidation, Signal, Stop, Target, {closure#1}(), {closure#10}() (+15 more)

### Community 53 - "Laravel\Mcp\Response"
Cohesion: 0.11
Nodes (9): {closure#1}(), GetFunding, GetJob, ListInstruments, ListStrategies, {closure#1}(), ListTrials, TradingTool (+1 more)

### Community 54 - "postgres"
Cohesion: 0.10
Nodes (19): POSTGRES_DB, POSTGRES_PASSWORD, POSTGRES_USER, interval, retries, test, timeout, environment (+11 more)

### Community 55 - "MonteCarlo"
Cohesion: 0.30
Nodes (9): MonteCarlo, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), mcDecimals() (+1 more)

### Community 56 - "OrderManagerTest.php"
Cohesion: 0.08
Nodes (33): OrderRole, Entry, Exit, Flatten, Stop, Target, ProtectionPlan, TrackedOrder (+25 more)

### Community 57 - "donchian-breakout-short.strategy.yaml"
Cohesion: 0.07
Nodes (26): entries, short, default, range, step, default, range, step (+18 more)

### Community 58 - "IndicatorReferenceTest.php"
Cohesion: 0.03
Nodes (35): {closure#43}(), BarIndicator, Bollinger, Change, DirectionalMovement, DonchianChannel, Highest, Hma (+27 more)

### Community 59 - "support.mjs"
Cohesion: 0.27
Nodes (9): @playwright/test, confirmed(), checkLayout(), EMAIL, freshCode(), PASSWORD, reauthenticate(), totp() (+1 more)

### Community 60 - "RunBacktestCommandTest.php"
Cohesion: 0.14
Nodes (28): BacktestRun, {closure#1}(), {closure#4}(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}() (+20 more)

### Community 61 - ".of"
Cohesion: 0.11
Nodes (26): {closure#41}(), {closure#44}(), {closure#45}(), {closure#46}(), {closure#47}(), {closure#48}(), {closure#17}(), {closure#24}() (+18 more)

### Community 63 - "DecisionRecord"
Cohesion: 0.18
Nodes (5): DecisionLog, DecisionRecord, IntentRecord, {closure#1}(), DatabaseDecisionLog

### Community 64 - "HealthChecks"
Cohesion: 0.06
Nodes (14): Notifier, CheckHealth, DiskUsage, {closure#1}(), HealthAlerts, HealthChecks, HealthResult, LocalDiskUsage (+6 more)

### Community 65 - "donchian-breakout.strategy.yaml"
Cohesion: 0.07
Nodes (26): entries, long, default, range, step, default, range, step (+18 more)

### Community 66 - ".benchmark"
Cohesion: 0.29
Nodes (13): Participant, Report, {closure#13}(), {closure#16}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}() (+5 more)

### Community 67 - "SimClock"
Cohesion: 0.14
Nodes (15): SimClock, WallClock, {closure#1}(), {closure#1}(), {closure#2}(), {closure#3}(), definition(), {closure#1}() (+7 more)

### Community 68 - "App\Strategy\Dsl\Document\DocumentChecker"
Cohesion: 0.06
Nodes (35): {closure#1}(), {closure#3}(), {closure#4}(), {closure#9}(), ParameterSpec, DslWriter, {closure#1}(), {closure#2}() (+27 more)

### Community 69 - "InvalidArgumentException"
Cohesion: 0.06
Nodes (17): {closure#1}(), {closure#2}(), Balance, {closure#1}(), {closure#1}(), {closure#1}(), {closure#3}(), {closure#5}() (+9 more)

### Community 70 - "Carbon\CarbonImmutable"
Cohesion: 0.08
Nodes (13): {closure#4}(), Venue, EngineRecord, Engine, ReconciliationMismatch, FundingRate, Position, PerpetualTracker (+5 more)

### Community 71 - ".evaluate"
Cohesion: 0.24
Nodes (9): Gates, brokenGate(), {closure#1}(), {closure#3}(), {closure#4}(), {closure#6}(), {closure#7}(), gate() (+1 more)

### Community 72 - "SimulationQueue"
Cohesion: 0.19
Nodes (4): RunBacktest, RunGrid, SimulationTool, SimulationQueue

### Community 73 - "scripts"
Cohesion: 0.15
Nodes (13): scripts, analyse, dev, format, mutate, post-autoload-dump, post-update-cmd, pre-package-uninstall (+5 more)

### Community 74 - "ma-trend-short.strategy.yaml"
Cohesion: 0.07
Nodes (26): entries, short, exits, stop_loss, when, fails_when, family, default (+18 more)

### Community 76 - ".measure"
Cohesion: 0.17
Nodes (13): Performance, Statistics, {closure#1}(), {closure#2}(), {closure#3}(), {closure#1}(), {closure#2}(), {closure#3}() (+5 more)

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
Cohesion: 0.07
Nodes (34): Cscv, DeflatedSharpe, Normal, DecimalMath, closeTo(), {closure#1}(), {closure#10}(), {closure#11}() (+26 more)

### Community 82 - "CompiledStrategyTest.php"
Cohesion: 0.15
Nodes (15): {closure#11}(), PerpetualView, PositionView, StrategyContext, aggregatedBars(), {closure#11}(), {closure#13}(), {closure#16}() (+7 more)

### Community 83 - "App\Support\Number\Decimal"
Cohesion: 0.07
Nodes (49): {closure#8}(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#15}() (+41 more)

### Community 84 - "pint/composer.json"
Cohesion: 0.20
Nodes (9): config, sort-packages, description, license, name, require, laravel/pint, php (+1 more)

### Community 85 - "HealthTest.php"
Cohesion: 0.13
Nodes (21): {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}(), reportAccount() (+13 more)

### Community 87 - "Timeframe"
Cohesion: 0.09
Nodes (16): CarryTemplate, {closure#2}(), {closure#8}(), Timeframe, FifteenMinutes, FiveMinutes, FourHours, OneDay (+8 more)

### Community 88 - "require-dev"
Cohesion: 0.22
Nodes (9): require-dev, fakerphp/faker, larastan/larastan, laravel/pail, laravel/pao, mockery/mockery, nunomaduro/collision, pestphp/pest (+1 more)

### Community 89 - "scripts-descriptions"
Cohesion: 0.25
Nodes (8): scripts-descriptions, analyse, format, mutate, test, test:network, test:testnet, tools:install

### Community 90 - "Ledger"
Cohesion: 0.24
Nodes (5): Ledger, LedgerViolation, {closure#19}(), {closure#12}(), {closure#14}()

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

### Community 96 - "StrategyVersion"
Cohesion: 0.21
Nodes (5): {closure#1}(), {closure#2}(), CheckResult, SaveResult, StrategyVersion

### Community 97 - "jobs"
Cohesion: 0.25
Nodes (8): name, runs-on, name, runs-on, steps, jobs, analyse, audit

### Community 98 - ".parse"
Cohesion: 0.16
Nodes (12): AstPrinter, {closure#7}(), EntrySpec, Expression, {closure#1}(), {closure#2}(), {closure#5}(), {closure#1}() (+4 more)

### Community 99 - "psr-4"
Cohesion: 0.40
Nodes (5): autoload, psr-4, App\\, Database\\Factories\\, Database\\Seeders\\

### Community 100 - "require"
Cohesion: 0.25
Nodes (8): require, laravel/fortify, laravel/framework, laravel/mcp, laravel/tinker, livewire/livewire, php, phrity/websocket

### Community 102 - "MarketType"
Cohesion: 0.26
Nodes (10): MarketType, Perp, Spot, {closure#1}(), {closure#3}(), goldenMinutes(), goldenRates(), perpetualReferencePrice() (+2 more)

### Community 103 - "StrategyGates.php"
Cohesion: 0.05
Nodes (32): {closure#11}(), {closure#12}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#6}(), {closure#8}(), {closure#2}() (+24 more)

### Community 104 - "uses"
Cohesion: 0.21
Nodes (12): steps, name, runs-on, steps, frontend, lint, name, runs-on (+4 more)

### Community 105 - "SettingsScreenTest.php"
Cohesion: 0.19
Nodes (12): {closure#10}(), {closure#11}(), {closure#13}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}() (+4 more)

### Community 106 - "devDependencies"
Cohesion: 0.18
Nodes (12): devDependencies, concurrently@^10.0.3, laravel-vite-plugin@^3.1, @playwright/test@1.63.0, tailwindcss@^4.0.0, @tailwindcss/vite@^4.0.0, vite@^8.0.0, concurrently (+4 more)

### Community 108 - "App\Execution\Venues\BinanceVenue"
Cohesion: 0.10
Nodes (21): {closure#2}(), Income, IncomeKind, Funding, Liquidation, Liquidity, Maker, Taker (+13 more)

### Community 118 - "App\Agents\Mcp\TradingServer"
Cohesion: 0.15
Nodes (4): DslReference, GetStrategy, SaveStrategy, TradingServer

### Community 119 - "CandleFeedTest.php"
Cohesion: 0.44
Nodes (11): {closure#1}(), {closure#12}(), {closure#13}(), {closure#2}(), {closure#4}(), {closure#5}(), {closure#8}(), {closure#9}() (+3 more)

### Community 120 - "buy-and-hold.strategy.yaml"
Cohesion: 0.12
Nodes (16): entries, long, exits, stop_loss, fails_when, family, hypothesis, when (+8 more)

### Community 121 - "ChartSeriesTest.php"
Cohesion: 0.18
Nodes (12): VolumeProfile, chart(), {closure#10}(), {closure#11}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}() (+4 more)

### Community 122 - "TokensTest.php"
Cohesion: 0.31
Nodes (6): {closure#3}(), {closure#4}(), contrastRatio(), contrastRules(), relativeLuminance(), themeTokens()

### Community 123 - "ArchiveImportTest.php"
Cohesion: 0.29
Nodes (10): archiveBytes(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}() (+2 more)

### Community 124 - "FortifyServiceProvider.php"
Cohesion: 0.10
Nodes (8): AppServiceProvider, {closure#7}(), {closure#3}(), {closure#4}(), FortifyServiceProvider, {closure#1}(), {closure#2}(), {closure#3}()

### Community 126 - "BinanceArchive"
Cohesion: 0.12
Nodes (3): ArchivePeriod, BinanceArchive, TradeFlowBuilder

### Community 127 - "App\Support\Clock\Clock"
Cohesion: 0.06
Nodes (26): Backfill, {closure#1}(), {closure#10}(), {closure#12}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}() (+18 more)

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
Cohesion: 0.29
Nodes (6): faded(), options(), priceChart(), token(), dslEditor(), highlight

### Community 132 - "TelegramSettings"
Cohesion: 0.15
Nodes (6): NotificationSettings, SettingSource, Env, None, TelegramClient, TelegramSettings

### Community 133 - "EnginePerpTest.php"
Cohesion: 0.20
Nodes (19): {closure#10}(), {closure#11}(), {closure#14}(), {closure#2}(), {closure#5}(), {closure#6}(), {closure#7}(), {closure#8}() (+11 more)

### Community 135 - "settings-screen.blade.php"
Cohesion: 0.25
Nodes (7): confirm, forgetKey, forgetTelegram, monitor, notify, revokeToken, testTelegram

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
Cohesion: 0.18
Nodes (11): {closure#4}(), MinuteCandles, BreakerEvent, MarketBreakers, {closure#14}(), {closure#3}(), {closure#7}(), breakerMinute() (+3 more)

### Community 141 - "InstrumentStatus"
Cohesion: 0.16
Nodes (9): InstrumentDefinition, InstrumentStatus, Delisted, Halted, Trading, {closure#3}(), {closure#4}(), {closure#5}() (+1 more)

### Community 142 - "PaperEngineTest.php"
Cohesion: 0.08
Nodes (19): AccountEquity, AccountTrade, SendDailyReport, DailyReport, {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}() (+11 more)

### Community 143 - "CandleRepositoryTest.php"
Cohesion: 0.28
Nodes (11): candleStore(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}() (+3 more)

### Community 145 - ".of"
Cohesion: 0.12
Nodes (19): AssetMismatch, Money, {closure#2}(), {closure#1}(), usdt(), {closure#11}(), {closure#7}(), {closure#1}() (+11 more)

### Community 146 - "BinanceVenueTest.php"
Cohesion: 0.27
Nodes (10): binanceVenue(), {closure#1}(), {closure#2}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}(), {closure#8}() (+2 more)

### Community 147 - "scripts"
Cohesion: 0.50
Nodes (4): scripts, build, dev, e2e

### Community 148 - "0001_01_01_000002_create_jobs_table.php"
Cohesion: 0.33
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

### Community 157 - "strategies-screen.blade.php"
Cohesion: 0.40
Nodes (4): open, promote, run, save

### Community 159 - "extra"
Cohesion: 0.67
Nodes (3): extra, laravel, dont-discover

### Community 173 - "ChartSeries"
Cohesion: 0.13
Nodes (5): ChartTool, GetCandles, GetIndicators, Controller, ChartSeries

### Community 174 - "StrategyLibrary"
Cohesion: 0.27
Nodes (3): CompileStrategy, StrategiesScreen, StrategyLibrary

### Community 175 - "AppServiceProvider.php"
Cohesion: 0.07
Nodes (12): BinanceEndpoints, Sleeper, SystemSleeper, SelectsInstruments, {closure#1}(), {closure#2}(), StreamCandles, {closure#1}() (+4 more)

### Community 176 - "ChartScreen.php"
Cohesion: 0.07
Nodes (23): {closure#2}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), FunctionSpec, Group, Exit (+15 more)

### Community 177 - "StrategySpec"
Cohesion: 0.32
Nodes (8): StrategySpec, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), readBackDocument(), readBackSpec()

### Community 178 - "Percent"
Cohesion: 0.24
Nodes (3): Percent, {closure#14}(), {closure#15}()

### Community 181 - "RiskPolicy"
Cohesion: 0.11
Nodes (15): RiskProfile, Account, Benchmark, RiskPolicy, {closure#1}(), {closure#2}(), {closure#3}(), documentedLimits() (+7 more)

### Community 182 - "Illuminate\Support\Facades\DB"
Cohesion: 0.18
Nodes (3): {closure#2}(), {closure#3}(), {closure#6}()

### Community 183 - "SimulationJobsTest.php"
Cohesion: 0.21
Nodes (5): {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), savedEmaCross()

### Community 184 - "ScalpingCollectorTest.php"
Cohesion: 0.35
Nodes (9): aggTrade(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#5}(), {closure#6}(), collector(), defaultBinanceFakes() (+1 more)

### Community 189 - "BinanceRestClient"
Cohesion: 0.10
Nodes (17): {closure#1}(), {closure#2}(), BinanceRestClient, EpochTime, ExchangeNumber, Json, KlineMessage, {closure#1}() (+9 more)

### Community 191 - ".number"
Cohesion: 0.29
Nodes (4): {closure#4}(), {closure#40}(), {closure#41}(), {closure#57}()

### Community 193 - "StrategyIdentity"
Cohesion: 0.20
Nodes (5): StrategyIdentity, RenamedStrategy, {closure#1}(), {closure#2}(), {closure#3}()

### Community 198 - "RulesHistory"
Cohesion: 0.18
Nodes (12): RulesHistory, {closure#1}(), {closure#2}(), {closure#3}(), {closure#5}(), {closure#4}(), {closure#1}(), {closure#2}() (+4 more)

### Community 199 - "ScalpingFunctionsTest.php"
Cohesion: 0.22
Nodes (10): backtestableScalpingHour(), {closure#2}(), {closure#3}(), {closure#5}(), {closure#6}(), {closure#7}(), {closure#8}(), referencePart() (+2 more)

### Community 202 - "VenuePosition"
Cohesion: 0.31
Nodes (4): VenuePosition, {closure#1}(), SimulatedPosition, {closure#13}()

### Community 205 - "2026_11_09_000015_create_scalping_tables.php"
Cohesion: 0.33
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

## Knowledge Gaps
- **142 isolated node(s):** `Archive`, `Rest`, `Stream`, `DailyArchive`, `Rest` (+137 more)
  These have ≤1 connection - possible missing edges. (Counts symbols only; 893 node(s) total have ≤1 connection when file, concept and rationale nodes are included.)
- **55 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `Decimal` connect `Decimal` to `EngineTest.php`, `CanonicalJson`, `DslError`, `Asset`, `.parse`, `DocumentChecker`, `.times`, `EnginePerpTest.php`, `Side`, `Instrument`, `.measure`, `Candle`, `InstrumentStatus`, `IndicatorsTest.php`, `RiskPolicy`, `.of`, `BinanceVenueTest.php`, `LedgerTest.php`, `Fill`, `Symbol`, `.of`, `ClientOrderId`, `Engine.php`, `ReadBack`, `CompiledStrategy.php`, `Account`, `.run`, `PreparesBacktests.php`, `.isGreaterThan`, `BacktestTest.php`, `App\Strategy\Dsl\Document\DocumentChecker`, `SimulatedVenue`, `.walk`, `ChartScreen.php`, `StrategySpec`, `Percent`, `Illuminate\Database\ConnectionInterface`, `Side`, `RiskPolicy`, `Illuminate\Support\Facades\DB`, `MonteCarlo`, `OrderManagerTest.php`, `IndicatorReferenceTest.php`, `RunBacktestCommandTest.php`, `BinanceRestClient`, `WarmingVenue`, `.of`, `.benchmark`, `SimClock`, `App\Strategy\Dsl\Document\DocumentChecker`, `InvalidArgumentException`, `Carbon\CarbonImmutable`, `.evaluate`, `RulesHistory`, `VenuePosition`, `FundingCarry`, `.__construct`, `.measure`, `DecimalMath`, `CompiledStrategyTest.php`, `App\Support\Number\Decimal`, `Timeframe`, `Ledger`, `.fromStrings`, `MarketType`, `StrategyGates.php`, `App\Execution\Venues\BinanceVenue`, `CandleFeedTest.php`, `ChartSeriesTest.php`, `App\Support\Clock\Clock`?**
  _High betweenness centrality (0.175) - this node is a cross-community bridge._
- **What connects `Archive`, `Rest`, `Stream` to the rest of the system?**
  _142 weakly-connected nodes found - possible missing edges._
- **Should `EngineTest.php` be split into smaller, more focused modules?**
  _Cohesion score 0.08281573498964803 - nodes in this community are weakly interconnected._
- **Why does `Symbol` connect `Symbol` to `EngineTest.php`, `CanonicalJson`, `DslError`, `Asset`, `EnginePerpTest.php`, `DocumentChecker`, `.times`, `.parse`, `Side`, `Instrument`, `.measure`, `InstrumentStatus`, `ExchangeVerificationTest.php`, `RiskPolicy`, `CandleRepositoryTest.php`, `BinanceVenueTest.php`, `ScreensTest.php`, `LedgerTest.php`, `Fill`, `.of`, `ClientOrderId`, `Engine.php`, `Account`, `.run`, `PreparesBacktests.php`, `.register`, `.isGreaterThan`, `BacktestTest.php`, `App\Strategy\Dsl\Document\DocumentChecker`, `Decimal`, `SimulatedVenue`, `AppServiceProvider.php`, `ChartScreen.php`, `Illuminate\Database\ConnectionInterface`, `Side`, `Laravel\Mcp\Response`, `Illuminate\Support\Facades\DB`, `OrderManagerTest.php`, `RunBacktestCommandTest.php`, `BinanceRestClient`, `WarmingVenue`, `DecisionRecord`, `HealthChecks`, `.benchmark`, `SimClock`, `InvalidArgumentException`, `Carbon\CarbonImmutable`, `RulesHistory`, `VenuePosition`, `FundingCarry`, `CompiledStrategyTest.php`, `Timeframe`, `Ledger`, `MarketType`, `StrategyGates.php`, `App\Execution\Venues\BinanceVenue`, `CandleFeedTest.php`, `ChartSeriesTest.php`, `App\Support\Clock\Clock`?**
  _High betweenness centrality (0.060) - this node is a cross-community bridge._
- **Should `DslError` be split into smaller, more focused modules?**
  _Cohesion score 0.14962121212121213 - nodes in this community are weakly interconnected._
- **Why does `Candle` connect `Candle` to `EngineTest.php`, `EnginePerpTest.php`, `.parse`, `.times`, `Side`, `Instrument`, `Carbon\CarbonImmutable`, `ExchangeVerificationTest.php`, `IndicatorsTest.php`, `CandleRepositoryTest.php`, `.run`, `.isGreaterThan`, `BacktestTest.php`, `Decimal`, `.walk`, `BinanceRestClientTest.php`, `Side`, `OrderManagerTest.php`, `IndicatorReferenceTest.php`, `BinanceRestClient`, `Carbon\CarbonImmutable`, `RulesHistory`, `.__construct`, `.measure`, `CompiledStrategyTest.php`, `App\Support\Number\Decimal`, `Timeframe`, `.fromStrings`, `MarketType`, `StrategyGates.php`, `CandleFeedTest.php`, `ChartSeriesTest.php`?**
  _High betweenness centrality (0.032) - this node is a cross-community bridge._
- **Should `.parse` be split into smaller, more focused modules?**
  _Cohesion score 0.07785547785547786 - nodes in this community are weakly interconnected._