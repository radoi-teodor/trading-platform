# Graph Report - trading-platform  (2026-11-09)

## Corpus Check
- 606 files · ~252,320 words
- Verdict: corpus is large enough that graph structure adds value.
- Unclassified: 101 file(s) not represented in the graph (top: .zip 38, .CHECKSUM 38, (none) 19)

## Summary
- 4864 nodes · 16690 edges · 208 communities (153 shown, 55 thin omitted)
- Extraction: 91% EXTRACTED · 9% INFERRED · 0% AMBIGUOUS · INFERRED: 1486 edges (avg confidence: 0.85)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `29064970`
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
- Illuminate\Database\ConnectionInterface
- .of
- Candle
- ExchangeVerificationTest.php
- Quantity
- PolicyLimits
- Node
- Illuminate\Console\Command
- Instrument
- web
- Ledger
- Illuminate\Database\Schema\Blueprint
- generate.py
- App\Strategy\Dsl\Compiler\CompiledStrategy
- StrategyIdentity
- .times
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
- editor.js
- Strategy
- BinanceRestClientTest.php
- ema-cross-scalp-short.strategy.yaml
- .now
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
- CandleStream
- FortifyServiceProvider.php
- healthyMachine
- donchian-breakout.strategy.yaml
- .run
- StrategyIdentity
- App\Strategy\Dsl\Document\DocumentChecker
- PreparesBacktests
- CandleFeedTest.php
- Window
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
- MarketType
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
- RunEngine.php
- StrategyGates.php
- uses
- SettingsScreenTest.php
- devDependencies
- BinanceVenueTest.php
- App\Strategy\Dsl\TypeChecker
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
- ScalpingFunctionsTest.php
- .parse
- Statistics
- CandleRepositoryTest.php
- Illuminate\Database\Migrations\Migration
- 2026_11_09_000019_create_forming_candles_table.php
- Universe
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
- SeedingTest.php
- 2026_11_09_000012_create_account_results_tables.php
- .evaluate
- TerminalQrCodeTest.php
- BarIndicator
- CompiledStrategy
- User
- 2026_11_09_000013_add_daily_report_to_notification_settings.php
- .priceMinutes
- UserFactory.php
- Venue
- .handle
- 2026_11_09_000018_add_effective_at_to_deployments.php
- 2026_11_09_000016_create_market_universe_tables.php
- CandleStreamTest.php
- 2026_11_09_000017_create_live_tables.php
- start-local.ps1
- BinanceRestClient
- Side
- DatabaseDecisionLog
- SimulationJobsTest.php
- App\Market\Data\FundingRate
- 2026_11_09_000015_create_scalping_tables.php
- Percent
- BinanceRequestFailed
- TradeFlowBuilder
- bootstrap/app.php
- SimulatedVenue
- AppServiceProvider

## God Nodes (most connected - your core abstractions)
1. `Decimal` - 516 edges
2. `Side` - 205 edges
3. `Symbol` - 202 edges
4. `App` - 137 edges
5. `Candle` - 136 edges
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
- `up()` --calls--> `App`  [INFERRED]
  database/migrations/2026_11_09_000016_create_market_universe_tables.php → app/Notifications/SettingSource.php
- `{closure#4}()` --calls--> `App`  [INFERRED]
  tests/Feature/Simulation/SimulationJobsTest.php → app/Notifications/SettingSource.php
- `{closure#1}()` --calls--> `App`  [INFERRED]
  tests/Feature/Support/ClockBindingTest.php → app/Notifications/SettingSource.php

## Import Cycles
- None detected.

## Communities (208 total, 55 thin omitted)

### Community 0 - "CompiledStrategyTest.php"
Cohesion: 0.16
Nodes (16): LimitedStrategy, aggregatedBars(), {closure#1}(), {closure#11}(), {closure#13}(), {closure#16}(), {closure#17}(), {closure#2}() (+8 more)

### Community 1 - "engineHarness"
Cohesion: 0.09
Nodes (42): EngineCommand, EngineMode, Backtest, Live, Paper, Testnet, ScriptedStrategy, {closure#10}() (+34 more)

### Community 2 - "CanonicalJson"
Cohesion: 0.30
Nodes (6): CanonicalJson, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}()

### Community 3 - "DslError"
Cohesion: 0.11
Nodes (13): {closure#1}(), {closure#5}(), {closure#7}(), {closure#8}(), DslError, YamlList, YamlReader, YamlScalar (+5 more)

### Community 4 - "Engine"
Cohesion: 0.06
Nodes (16): DecisionLog, DecisionRecord, EngineRecord, {closure#1}(), {closure#2}(), {closure#3}(), {closure#9}(), Engine (+8 more)

### Community 5 - ".parse"
Cohesion: 0.10
Nodes (36): {closure#12}(), {closure#17}(), {closure#5}(), carryDecisions(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}() (+28 more)

### Community 7 - ".now"
Cohesion: 0.17
Nodes (5): {closure#1}(), {closure#2}(), ReviewLadder, Ladder, LadderStatus

### Community 8 - "CompiledStrategy.php"
Cohesion: 0.12
Nodes (7): {closure#1}(), {closure#2}(), PerpetualTracker, {closure#42}(), Compiler, Program, Highest

### Community 9 - "Side"
Cohesion: 0.19
Nodes (39): btc(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#15}(), {closure#16}(), {closure#17}() (+31 more)

### Community 10 - "Illuminate\Database\ConnectionInterface"
Cohesion: 0.09
Nodes (7): FundingRateRepository, Heartbeats, {closure#1}(), InstrumentSync, QualityCheck, QualityReport, GridBacktest

### Community 11 - ".of"
Cohesion: 0.13
Nodes (15): AssetMismatch, Money, {closure#2}(), {closure#1}(), usdt(), {closure#7}(), {closure#1}(), {closure#11}() (+7 more)

### Community 12 - "Candle"
Cohesion: 0.10
Nodes (35): {closure#1}(), {closure#3}(), Candle, InvalidCandle, {closure#9}(), {closure#1}(), {closure#2}(), {closure#1}() (+27 more)

### Community 13 - "ExchangeVerificationTest.php"
Cohesion: 0.10
Nodes (32): VerificationSource, DailyArchive, Rest, {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}() (+24 more)

### Community 14 - "Quantity"
Cohesion: 0.24
Nodes (3): Quantity, {closure#10}(), {closure#9}()

### Community 15 - "PolicyLimits"
Cohesion: 0.12
Nodes (11): InstrumentDirectory, StoredInstruments, CompileStrategy, PolicyLimits, StrategyRepository, {closure#1}(), {closure#2}(), {closure#3}() (+3 more)

### Community 16 - "Node"
Cohesion: 0.11
Nodes (21): Binary, Lexer, Parser, Token, TokenKind, Colon, Comma, Dot (+13 more)

### Community 17 - "Illuminate\Console\Command"
Cohesion: 0.05
Nodes (25): CreateMcpToken, RevokeMcpToken, AuthenticateMcpToken, Tokens, ApproveLive, ConfirmsOperator, CreateAccount, FlattenEngine (+17 more)

### Community 18 - "Instrument"
Cohesion: 0.08
Nodes (10): BackfillStep, CandleRepository, CandleSource, Archive, Rest, Stream, UpsertResult, Instrument (+2 more)

### Community 19 - "web"
Cohesion: 0.16
Nodes (11): {closure#1}(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#7}(), {closure#1}() (+3 more)

### Community 20 - "Ledger"
Cohesion: 0.07
Nodes (60): ExitReason, Flatten, Liquidation, Signal, Stop, Target, Marks, Ledger (+52 more)

### Community 21 - "Illuminate\Database\Schema\Blueprint"
Cohesion: 0.19
Nodes (10): {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#1}(), {closure#2}(), {closure#3}() (+2 more)

### Community 22 - "generate.py"
Cohesion: 0.07
Nodes (30): atr(), bars(), bb(), change(), dmi(), donchian(), ema(), highest() (+22 more)

### Community 23 - "App\Strategy\Dsl\Compiler\CompiledStrategy"
Cohesion: 0.08
Nodes (18): {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}(), {closure#8}() (+10 more)

### Community 24 - "StrategyIdentity"
Cohesion: 0.05
Nodes (23): {closure#2}(), {closure#3}(), BuyAndHold, DonchianBreakout, FundingCarry, MovingAverageTrend, Parameters, BuildableStrategy (+15 more)

### Community 25 - ".times"
Cohesion: 0.15
Nodes (6): InstrumentRules, AccountView, Verdict, RiskEngine, {closure#4}(), {closure#10}()

### Community 27 - "Price"
Cohesion: 0.08
Nodes (22): ClientOrderId, Income, IncomeKind, Funding, Liquidation, OcoRequest, OrderAck, OrderRequest (+14 more)

### Community 28 - ".of"
Cohesion: 0.08
Nodes (76): RejectionReason, BelowMinimum, Breaker, Cooldown, DuplicateIntent, EntryWorking, GrossExposure, Halted (+68 more)

### Community 29 - "SimulatedPerpVenueTest.php"
Cohesion: 0.19
Nodes (27): {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#15}(), {closure#18}() (+19 more)

### Community 30 - "App\Market\Stream\PhrityTransport"
Cohesion: 0.12
Nodes (3): PhrityTransport, StreamFailed, FakeTransport

### Community 31 - "oi-momentum-long.strategy.yaml"
Cohesion: 0.07
Nodes (27): entries, long, exits, stop_loss, take_profit, when, fails_when, family (+19 more)

### Community 32 - ".of"
Cohesion: 0.08
Nodes (39): {closure#5}(), {closure#5}(), {closure#41}(), {closure#44}(), {closure#45}(), {closure#46}(), {closure#47}(), {closure#48}() (+31 more)

### Community 33 - "EnginePerpTest.php"
Cohesion: 0.17
Nodes (19): InMemoryDecisionLog, MinuteCandles, {closure#14}(), {closure#3}(), {closure#7}(), {closure#10}(), {closure#11}(), {closure#13}() (+11 more)

### Community 34 - "BinanceArchive"
Cohesion: 0.17
Nodes (5): BinanceArchive, {closure#1}(), ExchangeNumber, {closure#1}(), {closure#2}()

### Community 35 - "App"
Cohesion: 0.07
Nodes (43): SendTelegram, App, {closure#1}(), {closure#2}(), {closure#3}(), {closure#3}(), {closure#1}(), {closure#2}() (+35 more)

### Community 36 - "SimClock"
Cohesion: 0.11
Nodes (22): SimClock, WallClock, {closure#1}(), {closure#1}(), {closure#2}(), definition(), aggTrade(), {closure#1}() (+14 more)

### Community 37 - "LadderTest.php"
Cohesion: 0.08
Nodes (27): Account, DatabaseSeeder, approveLive(), {closure#2}(), {closure#4}(), {closure#5}(), {closure#8}(), {closure#9}() (+19 more)

### Community 38 - "OrderRole"
Cohesion: 0.09
Nodes (32): OrderRole, Entry, Exit, Flatten, Stop, Target, ProtectionPlan, TrackedOrder (+24 more)

### Community 39 - "BacktestTest.php"
Cohesion: 0.10
Nodes (24): FeeSchedule, CostModel, CandleFeed, FundingRate, Backtest, {closure#1}(), BacktestResult, Participant (+16 more)

### Community 40 - "App\Strategy\Dsl\Document\DocumentChecker"
Cohesion: 0.07
Nodes (24): Argument, BoolLiteral, Call, DurationLiteral, Identifier, Lookback, Node, NumberLiteral (+16 more)

### Community 41 - "ema-cross-scalp-long.strategy.yaml"
Cohesion: 0.06
Nodes (33): entries, long, exits, stop_loss, take_profit, time_stop, when, fails_when (+25 more)

### Community 42 - "Decimal"
Cohesion: 0.03
Nodes (53): {closure#6}(), {closure#2}(), RoundTrip, {closure#1}(), {closure#2}(), {closure#3}(), {closure#10}(), {closure#5}() (+45 more)

### Community 43 - ".zero"
Cohesion: 0.11
Nodes (16): Liquidity, Maker, Taker, OrderStatus, Cancelled, Expired, Filled, New (+8 more)

### Community 44 - "ScalpingArchiveTest.php"
Cohesion: 0.14
Nodes (15): PriceMinute, TradeFlow, {closure#1}(), {closure#10}(), {closure#11}(), {closure#2}(), {closure#3}(), {closure#4}() (+7 more)

### Community 45 - "Timeframe"
Cohesion: 0.08
Nodes (35): {closure#4}(), CandleAggregator, CandleSequenceBroken, {closure#3}(), {closure#2}(), {closure#8}(), Timeframe, FifteenMinutes (+27 more)

### Community 46 - "Illuminate\Database\Eloquent\Model"
Cohesion: 0.04
Nodes (33): McpCall, McpToken, IntentRecord, {closure#1}(), AccountEquity, AccountSnapshot, AccountTrade, PlainDecimal (+25 more)

### Community 47 - "editor.js"
Cohesion: 0.19
Nodes (12): dependencies, codemirror@^6.0.2, @codemirror/lang-yaml@^6.1.3, @codemirror/language@^6.13.1, @lezer/highlight@^1.2.5, codemirror, @codemirror/lang-yaml, @codemirror/language (+4 more)

### Community 48 - "Strategy"
Cohesion: 0.08
Nodes (30): RiskProfile, Account, Benchmark, RiskPolicy, {closure#2}(), Strategy, {closure#1}(), {closure#2}() (+22 more)

### Community 49 - "BinanceRestClientTest.php"
Cohesion: 0.11
Nodes (26): {closure#3}(), {closure#6}(), {closure#7}(), binanceClient(), {closure#1}(), {closure#11}(), {closure#13}(), {closure#14}() (+18 more)

### Community 50 - "ema-cross-scalp-short.strategy.yaml"
Cohesion: 0.06
Nodes (33): entries, short, exits, stop_loss, take_profit, time_stop, when, fails_when (+25 more)

### Community 51 - ".now"
Cohesion: 0.06
Nodes (10): {closure#5}(), Deployment, EngineCommandRow, ReauthController, AccountScreen, HaltButton, {closure#1}(), ModeBadge (+2 more)

### Community 53 - "Laravel\Mcp\Response"
Cohesion: 0.09
Nodes (12): ChartTool, GetCandles, GetFunding, GetIndicators, GetJob, GetStrategy, {closure#1}(), ListTrials (+4 more)

### Community 54 - "postgres"
Cohesion: 0.10
Nodes (19): POSTGRES_DB, POSTGRES_PASSWORD, POSTGRES_USER, interval, retries, test, timeout, environment (+11 more)

### Community 55 - "InvalidArgumentException"
Cohesion: 0.10
Nodes (10): {closure#1}(), {closure#2}(), {closure#11}(), {closure#1}(), {closure#1}(), {closure#3}(), {closure#5}(), {closure#7}() (+2 more)

### Community 56 - "SizedStrategyTest.php"
Cohesion: 0.14
Nodes (11): SizedStrategy, {closure#1}(), {closure#1}(), {closure#2}(), __construct(), enterOnce(), identity(), limits() (+3 more)

### Community 57 - "donchian-breakout-short.strategy.yaml"
Cohesion: 0.07
Nodes (26): entries, short, default, range, step, default, range, step (+18 more)

### Community 58 - "IndicatorsTest.php"
Cohesion: 0.08
Nodes (21): Atr, Donchian, Ema, Period, Sma, Wilder, bar(), {closure#1}() (+13 more)

### Community 59 - "support.mjs"
Cohesion: 0.26
Nodes (9): @playwright/test, confirmed(), checkLayout(), EMAIL, freshCode(), PASSWORD, reauthenticate(), totp() (+1 more)

### Community 60 - "RunBacktestCommandTest.php"
Cohesion: 0.10
Nodes (29): BacktestRun, {closure#1}(), {closure#4}(), {closure#6}(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}() (+21 more)

### Community 61 - "IndicatorReferenceTest.php"
Cohesion: 0.06
Nodes (24): {closure#43}(), Bollinger, Change, Hma, Macd, Obv, RealizedVolatility, Roc (+16 more)

### Community 62 - "CandleStream"
Cohesion: 0.16
Nodes (3): CandleStream, ScalpingCollector, StreamTransport

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
Cohesion: 0.08
Nodes (27): PerformanceMetrics, TradeStatistics, Report, btcMinutes(), {closure#1}(), {closure#2}(), {closure#3}(), referencePrice() (+19 more)

### Community 67 - "StrategyIdentity"
Cohesion: 0.17
Nodes (5): StrategyIdentity, RenamedStrategy, {closure#1}(), {closure#2}(), {closure#3}()

### Community 68 - "App\Strategy\Dsl\Document\DocumentChecker"
Cohesion: 0.08
Nodes (28): {closure#3}(), {closure#4}(), {closure#9}(), ParameterSpec, DslWriter, Scope, {closure#1}(), {closure#4}() (+20 more)

### Community 70 - "CandleFeedTest.php"
Cohesion: 0.34
Nodes (13): {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#2}(), {closure#4}(), {closure#5}() (+5 more)

### Community 71 - "Window"
Cohesion: 0.12
Nodes (6): {closure#54}(), DonchianChannel, Lowest, Stochastic, Vwma, Window

### Community 72 - "SimulationQueue"
Cohesion: 0.18
Nodes (4): RunBacktest, RunGrid, SimulationTool, SimulationQueue

### Community 73 - "scripts"
Cohesion: 0.15
Nodes (13): scripts, analyse, dev, format, mutate, post-autoload-dump, post-update-cmd, pre-package-uninstall (+5 more)

### Community 74 - "ma-trend-short.strategy.yaml"
Cohesion: 0.07
Nodes (26): entries, short, exits, stop_loss, when, fails_when, family, default (+18 more)

### Community 75 - ".breaches"
Cohesion: 0.24
Nodes (10): Band, bandReport(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#7}() (+2 more)

### Community 76 - ".measure"
Cohesion: 0.18
Nodes (8): EquityTracker, Performance, {closure#1}(), {closure#2}(), {closure#3}(), {closure#5}(), {closure#8}(), trackedTwoDays()

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
Cohesion: 0.14
Nodes (19): Cscv, DeflatedSharpe, closeTo(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}() (+11 more)

### Community 82 - "MonteCarlo"
Cohesion: 0.16
Nodes (10): {closure#4}(), MonteCarlo, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}() (+2 more)

### Community 83 - "App\Support\Number\Decimal"
Cohesion: 0.07
Nodes (54): {closure#8}(), {closure#3}(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}() (+46 more)

### Community 84 - "pint/composer.json"
Cohesion: 0.20
Nodes (9): config, sort-packages, description, license, name, require, laravel/pint, php (+1 more)

### Community 85 - ".evaluate"
Cohesion: 0.24
Nodes (9): Gates, brokenGate(), {closure#1}(), {closure#3}(), {closure#4}(), {closure#6}(), {closure#7}(), gate() (+1 more)

### Community 86 - "RunSimulation.php"
Cohesion: 0.11
Nodes (4): ImportMarketData, RunSimulation, SimulationJob, {closure#7}()

### Community 87 - "DocumentCheckerTest.php"
Cohesion: 0.21
Nodes (11): KnownInstruments, checkDocument(), {closure#1}(), {closure#2}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}() (+3 more)

### Community 88 - "require-dev"
Cohesion: 0.22
Nodes (9): require-dev, fakerphp/faker, larastan/larastan, laravel/pail, laravel/pao, mockery/mockery, nunomaduro/collision, pestphp/pest (+1 more)

### Community 89 - "scripts-descriptions"
Cohesion: 0.25
Nodes (8): scripts-descriptions, analyse, format, mutate, test, test:network, test:testnet, tools:install

### Community 90 - "MarketType"
Cohesion: 0.13
Nodes (10): BinanceEndpoints, InstrumentDefinition, MarketType, Perp, Spot, SyncResult, {closure#3}(), {closure#4}() (+2 more)

### Community 91 - ".fromStrings"
Cohesion: 0.14
Nodes (7): RegimeLabels, DirectionalMovement, {closure#2}(), {closure#4}(), {closure#7}(), {closure#9}(), days()

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
Nodes (9): CheckHealth, DiskUsage, {closure#1}(), HealthAlerts, HealthChecks, HealthResult, LocalDiskUsage, {closure#6}() (+1 more)

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

### Community 102 - "RunEngine.php"
Cohesion: 0.07
Nodes (12): AccountRuntime, {closure#4}(), RunEngine, Notifier, ReplayDivergence, Sleeper, SystemSleeper, TelegramNotifier (+4 more)

### Community 103 - "StrategyGates.php"
Cohesion: 0.07
Nodes (17): {closure#11}(), {closure#12}(), {closure#2}(), {closure#3}(), {closure#6}(), {closure#8}(), {closure#2}(), GridPart (+9 more)

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
Nodes (19): {closure#2}(), Balance, BinanceVenue, BinanceApiError, BinanceUnreachable, SignedBinanceClient, Asset, binanceVenue() (+11 more)

### Community 117 - "App\Strategy\Dsl\TypeChecker"
Cohesion: 0.06
Nodes (24): {closure#2}(), ChartScreen, {closure#2}(), {closure#3}(), FunctionSpec, Group, Exit, Indicator (+16 more)

### Community 118 - "App\Agents\Mcp\TradingServer"
Cohesion: 0.17
Nodes (3): DslReference, ListInstruments, TradingServer

### Community 119 - "RulesHistory"
Cohesion: 0.28
Nodes (8): RulesHistory, {closure#2}(), {closure#4}(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), rules()

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
Cohesion: 0.31
Nodes (10): archiveBytes(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}() (+2 more)

### Community 124 - "App\Execution\Models\Account"
Cohesion: 0.06
Nodes (11): LiveGate, OpenAccount, StoreKeys, LiveApproval, {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}() (+3 more)

### Community 125 - "Illuminate\Database\Migrations\Migration"
Cohesion: 0.15
Nodes (3): {closure#1}(), {closure#1}(), {closure#1}()

### Community 126 - "StrategyVersion"
Cohesion: 0.26
Nodes (4): {closure#1}(), ListStrategies, {closure#3}(), StrategyVersion

### Community 127 - ".register"
Cohesion: 0.10
Nodes (21): {closure#1}(), {closure#10}(), {closure#11}(), {closure#13}(), {closure#15}(), {closure#16}(), {closure#17}(), {closure#18}() (+13 more)

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

### Community 140 - "ScalpingFunctionsTest.php"
Cohesion: 0.24
Nodes (10): backtestableScalpingHour(), {closure#2}(), {closure#3}(), {closure#5}(), {closure#6}(), {closure#7}(), {closure#8}(), referencePart() (+2 more)

### Community 141 - ".parse"
Cohesion: 0.33
Nodes (6): KlineMessage, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), streamLines()

### Community 142 - "Statistics"
Cohesion: 0.25
Nodes (11): Statistics, {closure#1}(), {closure#3}(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}() (+3 more)

### Community 143 - "CandleRepositoryTest.php"
Cohesion: 0.28
Nodes (11): candleStore(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}() (+3 more)

### Community 146 - "Universe"
Cohesion: 0.07
Nodes (13): ArchiveMissing, ChecksumMismatch, BackfillReport, BackfillMarketData, SelectsInstruments, {closure#1}(), {closure#2}(), StreamCandles (+5 more)

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
Cohesion: 0.25
Nodes (5): {closure#1}(), CompileStrategy, StrategyLibrary, CheckResult, SaveResult

### Community 175 - "Carbon\CarbonImmutable"
Cohesion: 0.05
Nodes (35): ArchivePeriod, Backfill, {closure#1}(), {closure#10}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}() (+27 more)

### Community 180 - ".evaluate"
Cohesion: 0.14
Nodes (13): {closure#1}(), {closure#5}(), {closure#6}(), {closure#7}(), {closure#8}(), {closure#9}(), WalkForward, {closure#1}() (+5 more)

### Community 181 - "TerminalQrCodeTest.php"
Cohesion: 0.21
Nodes (7): TerminalQrCode, {closure#3}(), {closure#4}(), {closure#5}(), expectedModules(), readColoured(), readPlain()

### Community 182 - "BarIndicator"
Cohesion: 0.15
Nodes (4): BarIndicator, Keltner, Natr, Vwap

### Community 183 - "CompiledStrategy"
Cohesion: 0.14
Nodes (10): {closure#2}(), CompiledStrategy, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#7}() (+2 more)

### Community 189 - ".priceMinutes"
Cohesion: 0.20
Nodes (5): EpochTime, Json, {closure#1}(), {closure#2}(), {closure#3}()

### Community 191 - "Venue"
Cohesion: 0.11
Nodes (8): Venue, ForwardingVenue, {closure#12}(), {closure#22}(), {closure#32}(), {closure#34}(), {closure#49}(), OverReportingVenue

### Community 192 - ".handle"
Cohesion: 0.18
Nodes (4): {closure#2}(), ReadsGridOptions, StrategyGates, StrategySpec

### Community 195 - "2026_11_09_000016_create_market_universe_tables.php"
Cohesion: 0.40
Nodes (3): {closure#1}(), {closure#2}(), up()

### Community 196 - "CandleStreamTest.php"
Cohesion: 0.26
Nodes (12): {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#6}(), {closure#7}(), {closure#8}(), klineMessage() (+4 more)

### Community 197 - "2026_11_09_000017_create_live_tables.php"
Cohesion: 0.33
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

### Community 198 - "start-local.ps1"
Cohesion: 0.27
Nodes (4): Stop-Postgres(), Stop-ProcessTree(), Test-PostgresRunning(), Write-Step()

### Community 199 - "BinanceRestClient"
Cohesion: 0.22
Nodes (6): BinanceRestClient, SyncInstruments, InstrumentStatus, Delisted, Halted, Trading

### Community 200 - "Side"
Cohesion: 0.06
Nodes (18): VenuePosition, {closure#1}(), {closure#2}(), {closure#1}(), SimulatedPosition, Fill, PerpPosition, Position (+10 more)

### Community 202 - "SimulationJobsTest.php"
Cohesion: 0.21
Nodes (5): {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), savedEmaCross()

### Community 205 - "2026_11_09_000015_create_scalping_tables.php"
Cohesion: 0.33
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

### Community 207 - "Percent"
Cohesion: 0.24
Nodes (3): Percent, {closure#14}(), {closure#15}()

### Community 208 - "BinanceRequestFailed"
Cohesion: 0.24
Nodes (4): BinanceRequestFailed, LocationRestricted, RateLimited, RequestFailed

### Community 212 - "bootstrap/app.php"
Cohesion: 0.32
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

## Knowledge Gaps
- **147 isolated node(s):** `Anchor`, `Count`, `Duration`, `Number`, `Percent` (+142 more)
  These have ≤1 connection - possible missing edges. (Counts symbols only; 943 node(s) total have ≤1 connection when file, concept and rationale nodes are included.)
- **55 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `Decimal` connect `Decimal` to `CompiledStrategyTest.php`, `engineHarness`, `CanonicalJson`, `DslError`, `Engine`, `.parse`, `DocumentChecker`, `CompiledStrategy.php`, `Side`, `Illuminate\Database\ConnectionInterface`, `.of`, `Candle`, `Statistics`, `PolicyLimits`, `Quantity`, `Ledger`, `App\Strategy\Dsl\Compiler\CompiledStrategy`, `StrategyIdentity`, `.times`, `WarmingVenue`, `Price`, `.of`, `.of`, `EnginePerpTest.php`, `LadderTest.php`, `OrderRole`, `BacktestTest.php`, `App\Strategy\Dsl\Document\DocumentChecker`, `.zero`, `Timeframe`, `Carbon\CarbonImmutable`, `Strategy`, `.now`, `.evaluate`, `BarIndicator`, `InvalidArgumentException`, `SizedStrategyTest.php`, `CompiledStrategy`, `IndicatorsTest.php`, `RunBacktestCommandTest.php`, `IndicatorReferenceTest.php`, `Venue`, `.handle`, `.run`, `App\Strategy\Dsl\Document\DocumentChecker`, `PreparesBacktests`, `CandleFeedTest.php`, `BinanceRestClient`, `Side`, `Window`, `App\Market\Data\FundingRate`, `.measure`, `Percent`, `DecimalMath`, `MonteCarlo`, `App\Support\Number\Decimal`, `SimulatedVenue`, `.evaluate`, `DocumentCheckerTest.php`, `MarketType`, `.fromStrings`, `StrategyGates.php`, `BinanceVenueTest.php`, `App\Strategy\Dsl\TypeChecker`, `RulesHistory`, `ChartSeriesTest.php`?**
  _High betweenness centrality (0.133) - this node is a cross-community bridge._
- **What connects `Anchor`, `Count`, `Duration` to the rest of the system?**
  _147 weakly-connected nodes found - possible missing edges._
- **Should `engineHarness` be split into smaller, more focused modules?**
  _Cohesion score 0.09326923076923077 - nodes in this community are weakly interconnected._
- **Why does `Symbol` connect `StrategyIdentity` to `CompiledStrategyTest.php`, `engineHarness`, `CanonicalJson`, `DslError`, `Engine`, `.parse`, `DocumentChecker`, `Side`, `ExchangeVerificationTest.php`, `PolicyLimits`, `CandleRepositoryTest.php`, `Universe`, `Instrument`, `Ledger`, `.times`, `WarmingVenue`, `Price`, `.of`, `EnginePerpTest.php`, `LadderTest.php`, `OrderRole`, `BacktestTest.php`, `Decimal`, `Carbon\CarbonImmutable`, `Strategy`, `.now`, `Laravel\Mcp\Response`, `InvalidArgumentException`, `CompiledStrategy`, `RunBacktestCommandTest.php`, `Venue`, `.handle`, `.run`, `CandleFeedTest.php`, `BinanceRestClient`, `Side`, `.measure`, `SimulatedVenue`, `DocumentCheckerTest.php`, `MarketType`, `RunEngine.php`, `StrategyGates.php`, `BinanceVenueTest.php`, `RulesHistory`, `ChartSeriesTest.php`, `App\Execution\Models\Account`, `.register`?**
  _High betweenness centrality (0.040) - this node is a cross-community bridge._
- **Should `DslError` be split into smaller, more focused modules?**
  _Cohesion score 0.11153846153846154 - nodes in this community are weakly interconnected._
- **Why does `DslError` connect `DslError` to `CompiledStrategyTest.php`, `.parse`, `App\Strategy\Dsl\Document\DocumentChecker`, `DocumentChecker`, `CompiledStrategy.php`, `App\Strategy\Dsl\Document\DocumentChecker`, `StrategyLibrary`, `PolicyLimits`, `Node`, `Strategy`, `.now`, `Laravel\Mcp\Response`, `InvalidArgumentException`, `CompiledStrategy`, `DocumentCheckerTest.php`, `ChartSeriesTest.php`?**
  _High betweenness centrality (0.020) - this node is a cross-community bridge._
- **Should `Engine` be split into smaller, more focused modules?**
  _Cohesion score 0.05974124809741248 - nodes in this community are weakly interconnected._