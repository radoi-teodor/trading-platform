# Graph Report - trading-platform  (2026-11-09)

## Corpus Check
- 600 files · ~247,874 words
- Verdict: corpus is large enough that graph structure adds value.
- Unclassified: 101 file(s) not represented in the graph (top: .zip 38, .CHECKSUM 38, (none) 19)

## Summary
- 4808 nodes · 16560 edges · 202 communities (154 shown, 48 thin omitted)
- Extraction: 91% EXTRACTED · 9% INFERRED · 0% AMBIGUOUS · INFERRED: 1467 edges (avg confidence: 0.85)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `0562df24`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- .of
- EngineTest.php
- CanonicalJson
- DslError
- Asset
- .parse
- DocumentChecker
- ChartScreen.php
- CompiledStrategy.php
- Side
- Instrument
- .of
- Candle
- ExchangeVerificationTest.php
- IndicatorsTest.php
- PolicyLimits
- Node
- Clock
- RunEngine.php
- ScreensTest.php
- LedgerTest.php
- Illuminate\Database\Schema\Blueprint
- generate.py
- ReadBack
- Symbol
- .of
- Price
- Engine.php
- .now
- CompiledStrategy.php
- App\Market\Stream\PhrityTransport
- oi-momentum-long.strategy.yaml
- SimulatedPerpVenueTest.php
- .now
- Carbon\CarbonImmutable
- App
- Illuminate\Http\Client\Request
- .parse
- Venue
- BacktestTest.php
- App\Strategy\Dsl\Document\DocumentChecker
- ema-cross-scalp-long.strategy.yaml
- Decimal
- SimulatedVenue
- ScalpingArchiveTest.php
- Timeframe
- Illuminate\Database\Eloquent\Model
- package.json
- CompiledStrategy
- Illuminate\Http\Client\Request
- ema-cross-scalp-short.strategy.yaml
- Illuminate\Database\ConnectionInterface
- Side
- Laravel\Mcp\Response
- postgres
- InvalidArgumentException
- SizedStrategyTest.php
- donchian-breakout-short.strategy.yaml
- IndicatorReferenceTest.php
- support.mjs
- RunBacktestCommandTest.php
- DecimalMath
- Deployment
- User
- Ledger
- donchian-breakout.strategy.yaml
- PerpetualBaselinesTest.php
- StrategyIdentity
- App\Strategy\Dsl\Document\DocumentChecker
- InvalidArgumentException
- Engine
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
- healthyMachine
- RunSimulation.php
- .sqrt
- require-dev
- scripts-descriptions
- .evaluate
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
- CompiledStrategyTest.php
- StrategyGates.php
- uses
- SettingsScreenTest.php
- devDependencies
- BinanceVenueTest.php
- FunctionSpec
- App\Agents\Mcp\TradingServer
- .breaches
- buy-and-hold.strategy.yaml
- ChartSeriesTest.php
- TokensTest.php
- ArchiveImportTest.php
- FortifyServiceProvider.php
- Illuminate\Database\Migrations\Migration
- Laravel\Fortify\Contracts\TwoFactorAuthenticationProvider
- collector
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
- Account
- CandleRepositoryTest.php
- Illuminate\Support\Facades\Schema
- Money
- HealthChecks
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
- StrategyLibrary
- App\Support\Clock\Clock
- Unit
- App\Strategy\Dsl\Document\StrategySpec
- PerformanceMetrics
- 2026_11_09_000012_create_account_results_tables.php
- MonteCarlo.php
- Percent
- DocumentCheckerTest.php
- SimulationJobsTest.php
- Quantity
- 2026_11_09_000013_add_daily_report_to_notification_settings.php
- MarketType
- Expression
- Deployment.php
- ReadsGridOptions
- WallClock
- 2026_11_09_000018_add_effective_at_to_deployments.php
- 2026_11_09_000016_create_market_universe_tables.php
- {closure#12}
- 2026_11_09_000017_create_live_tables.php
- RulesHistory
- Side
- .times
- 2026_11_09_000015_create_scalping_tables.php

## God Nodes (most connected - your core abstractions)
1. `Decimal` - 516 edges
2. `Side` - 205 edges
3. `Symbol` - 204 edges
4. `Candle` - 136 edges
5. `App` - 132 edges
6. `Timeframe` - 123 edges
7. `Price` - 100 edges
8. `Position` - 96 edges
9. `Clock` - 81 edges
10. `SimClock` - 76 edges

## Surprising Connections (you probably didn't know these)
- `{closure#1}()` --calls--> `App`  [INFERRED]
  tests/Feature/Support/ClockBindingTest.php → app/Notifications/SettingSource.php
- `{closure#4}()` --calls--> `App`  [INFERRED]
  tests/Feature/Simulation/SimulationJobsTest.php → app/Notifications/SettingSource.php
- `{closure#3}()` --calls--> `App`  [INFERRED]
  tests/Feature/Network/BinancePublicDataTest.php → app/Notifications/SettingSource.php
- `{closure#5}()` --calls--> `App`  [INFERRED]
  tests/Feature/Network/BinancePublicDataTest.php → app/Notifications/SettingSource.php
- `up()` --calls--> `App`  [INFERRED]
  database/migrations/2026_11_09_000016_create_market_universe_tables.php → app/Notifications/SettingSource.php

## Import Cycles
- None detected.

## Communities (202 total, 48 thin omitted)

### Community 0 - ".of"
Cohesion: 0.07
Nodes (35): {closure#1}(), {closure#2}(), {closure#4}(), {closure#6}(), {closure#7}(), {closure#8}(), {closure#1}(), {closure#2}() (+27 more)

### Community 1 - "EngineTest.php"
Cohesion: 0.09
Nodes (42): EngineCommand, EngineMode, Backtest, Live, Paper, Testnet, ScriptedStrategy, {closure#10}() (+34 more)

### Community 2 - "CanonicalJson"
Cohesion: 0.20
Nodes (6): CanonicalJson, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}()

### Community 3 - "DslError"
Cohesion: 0.14
Nodes (12): {closure#1}(), {closure#5}(), {closure#8}(), DslError, YamlList, YamlReader, YamlScalar, YamlValue (+4 more)

### Community 4 - "Asset"
Cohesion: 0.11
Nodes (10): Marks, {closure#2}(), BacktestResult, ParticipantResult, {closure#1}(), {closure#3}(), {closure#4}(), Asset (+2 more)

### Community 5 - ".parse"
Cohesion: 0.08
Nodes (48): Strategy, baselineDocument(), baselinePairs(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#14}() (+40 more)

### Community 7 - "ChartScreen.php"
Cohesion: 0.08
Nodes (11): EngineCommandRow, ReauthController, AccountScreen, ChartScreen, {closure#4}(), {closure#5}(), HaltButton, ModeBadge (+3 more)

### Community 8 - "CompiledStrategy.php"
Cohesion: 0.20
Nodes (3): {closure#42}(), Compiler, Program

### Community 9 - "Side"
Cohesion: 0.15
Nodes (43): btc(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#15}(), {closure#16}() (+35 more)

### Community 10 - "Instrument"
Cohesion: 0.05
Nodes (25): BackfillStep, CandleRepository, {closure#2}(), {closure#4}(), CheckMarketData, CandleSource, Archive, Rest (+17 more)

### Community 11 - ".of"
Cohesion: 0.15
Nodes (18): AssetMismatch, {closure#2}(), {closure#1}(), usdt(), {closure#11}(), {closure#7}(), {closure#1}(), {closure#11}() (+10 more)

### Community 12 - "Candle"
Cohesion: 0.10
Nodes (33): {closure#1}(), {closure#3}(), Candle, InvalidCandle, {closure#1}(), {closure#2}(), {closure#42}(), candleWith() (+25 more)

### Community 13 - "ExchangeVerificationTest.php"
Cohesion: 0.10
Nodes (30): VerificationSource, DailyArchive, Rest, {closure#1}(), {closure#12}(), {closure#13}(), {closure#2}(), {closure#3}() (+22 more)

### Community 14 - "IndicatorsTest.php"
Cohesion: 0.09
Nodes (21): Atr, Donchian, Ema, Period, Sma, {closure#5}(), bar(), {closure#1}() (+13 more)

### Community 15 - "PolicyLimits"
Cohesion: 0.15
Nodes (9): InstrumentDirectory, PolicyLimits, StrategyRepository, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), versions() (+1 more)

### Community 16 - "Node"
Cohesion: 0.14
Nodes (20): Binary, Parser, Token, TokenKind, Colon, Comma, Dot, Duration (+12 more)

### Community 17 - "Clock"
Cohesion: 0.07
Nodes (19): CreateMcpToken, RevokeMcpToken, AuthenticateMcpToken, Tokens, FlattenEngine, HaltEngine, IssuesCommands, PauseStrategy (+11 more)

### Community 18 - "RunEngine.php"
Cohesion: 0.09
Nodes (6): {closure#4}(), ReplayDivergence, ArchiveMissing, ChecksumMismatch, VerifyMarketData, TelegramNotifier

### Community 19 - "ScreensTest.php"
Cohesion: 0.13
Nodes (11): {closure#1}(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#7}(), {closure#2}() (+3 more)

### Community 20 - "LedgerTest.php"
Cohesion: 0.21
Nodes (26): balanceOf(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#15}(), {closure#16}() (+18 more)

### Community 21 - "Illuminate\Database\Schema\Blueprint"
Cohesion: 0.19
Nodes (10): {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#1}(), {closure#2}(), {closure#3}() (+2 more)

### Community 22 - "generate.py"
Cohesion: 0.07
Nodes (30): atr(), bars(), bb(), change(), dmi(), donchian(), ema(), highest() (+22 more)

### Community 23 - "ReadBack"
Cohesion: 0.17
Nodes (3): {closure#7}(), {closure#6}(), ReadBack

### Community 24 - "Symbol"
Cohesion: 0.04
Nodes (25): {closure#2}(), {closure#3}(), {closure#4}(), BuyAndHold, DonchianBreakout, FundingCarry, MovingAverageTrend, Parameters (+17 more)

### Community 25 - ".of"
Cohesion: 0.07
Nodes (79): RejectionReason, BelowMinimum, Breaker, Cooldown, DuplicateIntent, EntryWorking, GrossExposure, Halted (+71 more)

### Community 26 - "Price"
Cohesion: 0.07
Nodes (16): ClientOrderId, OcoRequest, OrderAck, OrderRequest, OrderType, Limit, Market, StopMarket (+8 more)

### Community 27 - "Engine.php"
Cohesion: 0.10
Nodes (5): {closure#1}(), {closure#11}(), {closure#2}(), {closure#3}(), {closure#9}()

### Community 28 - ".now"
Cohesion: 0.08
Nodes (10): BackfillReport, BackfillMarketData, SelectsInstruments, {closure#1}(), {closure#2}(), StreamCandles, {closure#1}(), {closure#2}() (+2 more)

### Community 29 - "CompiledStrategy.php"
Cohesion: 0.10
Nodes (7): {closure#6}(), {closure#8}(), {closure#9}(), {closure#1}(), {closure#2}(), {closure#5}(), {closure#7}()

### Community 30 - "App\Market\Stream\PhrityTransport"
Cohesion: 0.12
Nodes (3): PhrityTransport, StreamFailed, FakeTransport

### Community 31 - "oi-momentum-long.strategy.yaml"
Cohesion: 0.07
Nodes (27): entries, long, exits, stop_loss, take_profit, when, fails_when, family (+19 more)

### Community 32 - "SimulatedPerpVenueTest.php"
Cohesion: 0.21
Nodes (26): {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#15}(), {closure#18}(), {closure#19}() (+18 more)

### Community 33 - ".now"
Cohesion: 0.05
Nodes (11): AccountRuntime, LiveGate, OpenAccount, RunEngine, {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}() (+3 more)

### Community 34 - "Carbon\CarbonImmutable"
Cohesion: 0.05
Nodes (19): ArchivePeriod, BinanceArchive, {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#1}(), OpenInterest (+11 more)

### Community 35 - "App"
Cohesion: 0.07
Nodes (43): SendTelegram, App, {closure#1}(), {closure#2}(), {closure#3}(), {closure#2}(), {closure#4}(), {closure#5}() (+35 more)

### Community 36 - "Illuminate\Http\Client\Request"
Cohesion: 0.27
Nodes (11): approveLive(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}(), {closure#8}() (+3 more)

### Community 37 - ".parse"
Cohesion: 0.06
Nodes (49): SimClock, {closure#1}(), {closure#1}(), {closure#11}(), {closure#13}(), {closure#14}(), {closure#15}(), {closure#16}() (+41 more)

### Community 38 - "Venue"
Cohesion: 0.07
Nodes (9): Venue, WarmingVenue, ForwardingVenue, {closure#12}(), {closure#22}(), {closure#32}(), {closure#34}(), {closure#49}() (+1 more)

### Community 39 - "BacktestTest.php"
Cohesion: 0.08
Nodes (42): FeeSchedule, CostModel, CandleFeed, FundingRate, RiskProfile, Account, Benchmark, RiskPolicy (+34 more)

### Community 40 - "App\Strategy\Dsl\Document\DocumentChecker"
Cohesion: 0.08
Nodes (24): Argument, BoolLiteral, Call, DurationLiteral, Identifier, Lookback, Node, NumberLiteral (+16 more)

### Community 41 - "ema-cross-scalp-long.strategy.yaml"
Cohesion: 0.06
Nodes (33): entries, long, exits, stop_loss, take_profit, time_stop, when, fails_when (+25 more)

### Community 42 - "Decimal"
Cohesion: 0.04
Nodes (47): {closure#6}(), {closure#1}(), {closure#2}(), Fill, PerpPosition, Position, RoundTrip, {closure#1}() (+39 more)

### Community 43 - "SimulatedVenue"
Cohesion: 0.07
Nodes (16): Liquidity, Maker, Taker, OrderStatus, Cancelled, Expired, Filled, New (+8 more)

### Community 44 - "ScalpingArchiveTest.php"
Cohesion: 0.12
Nodes (19): TradeFlow, {closure#1}(), {closure#10}(), {closure#11}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}() (+11 more)

### Community 45 - "Timeframe"
Cohesion: 0.08
Nodes (45): CandleAggregator, CandleSequenceBroken, {closure#4}(), {closure#2}(), {closure#8}(), Timeframe, FifteenMinutes, FiveMinutes (+37 more)

### Community 46 - "Illuminate\Database\Eloquent\Model"
Cohesion: 0.03
Nodes (51): McpCall, McpToken, {closure#5}(), IntentRecord, {closure#1}(), AccountEquity, AccountSnapshot, AccountTrade (+43 more)

### Community 47 - "package.json"
Cohesion: 0.15
Nodes (17): dependencies, codemirror@^6.0.2, @codemirror/lang-yaml@^6.1.3, @codemirror/language@^6.13.1, @lezer/highlight@^1.2.5, lightweight-charts@^5.2.1, optionalDependencies, @laravel/multiplex@^0.4.1 (+9 more)

### Community 49 - "Illuminate\Http\Client\Request"
Cohesion: 0.07
Nodes (33): BinanceApiError, BinanceUnreachable, binanceVenue(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}() (+25 more)

### Community 50 - "ema-cross-scalp-short.strategy.yaml"
Cohesion: 0.06
Nodes (33): entries, short, exits, stop_loss, take_profit, time_stop, when, fails_when (+25 more)

### Community 51 - "Illuminate\Database\ConnectionInterface"
Cohesion: 0.16
Nodes (5): {closure#2}(), StrategyGates, ExitSpec, StrategySpec, {closure#1}()

### Community 52 - "Side"
Cohesion: 0.22
Nodes (22): ExitReason, Flatten, Liquidation, Signal, Stop, Target, {closure#1}(), {closure#10}() (+14 more)

### Community 53 - "Laravel\Mcp\Response"
Cohesion: 0.10
Nodes (10): GetFunding, GetIndicators, GetJob, GetStrategy, ListStrategies, {closure#1}(), ListTrials, SaveStrategy (+2 more)

### Community 54 - "postgres"
Cohesion: 0.10
Nodes (19): POSTGRES_DB, POSTGRES_PASSWORD, POSTGRES_USER, interval, retries, test, timeout, environment (+11 more)

### Community 55 - "InvalidArgumentException"
Cohesion: 0.10
Nodes (5): {closure#1}(), {closure#2}(), Balance, {closure#1}(), balances()

### Community 56 - "SizedStrategyTest.php"
Cohesion: 0.11
Nodes (12): SizedStrategy, {closure#1}(), LimitedStrategy, {closure#1}(), {closure#2}(), __construct(), enterOnce(), identity() (+4 more)

### Community 57 - "donchian-breakout-short.strategy.yaml"
Cohesion: 0.07
Nodes (26): entries, short, default, range, step, default, range, step (+18 more)

### Community 58 - "IndicatorReferenceTest.php"
Cohesion: 0.04
Nodes (29): {closure#43}(), Bollinger, Change, DonchianChannel, Highest, Hma, Lowest, Macd (+21 more)

### Community 59 - "support.mjs"
Cohesion: 0.28
Nodes (9): @playwright/test, confirmed(), checkLayout(), EMAIL, freshCode(), PASSWORD, reauthenticate(), totp() (+1 more)

### Community 60 - "RunBacktestCommandTest.php"
Cohesion: 0.10
Nodes (29): BacktestRun, {closure#1}(), {closure#4}(), {closure#6}(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}() (+21 more)

### Community 61 - "DecimalMath"
Cohesion: 0.17
Nodes (14): DecimalMath, {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#2}() (+6 more)

### Community 62 - "Deployment"
Cohesion: 0.18
Nodes (5): Notifier, Deployment, ReviewLadder, Ladder, LadderStatus

### Community 64 - "Ledger"
Cohesion: 0.31
Nodes (5): Ledger, LedgerViolation, {closure#19}(), {closure#12}(), {closure#14}()

### Community 65 - "donchian-breakout.strategy.yaml"
Cohesion: 0.07
Nodes (26): entries, long, default, range, step, default, range, step (+18 more)

### Community 66 - "PerpetualBaselinesTest.php"
Cohesion: 0.19
Nodes (12): btcMinutes(), {closure#1}(), {closure#2}(), referencePrice(), {closure#1}(), {closure#3}(), goldenMinutes(), goldenRates() (+4 more)

### Community 67 - "StrategyIdentity"
Cohesion: 0.18
Nodes (5): StrategyIdentity, RenamedStrategy, {closure#1}(), {closure#2}(), {closure#3}()

### Community 68 - "App\Strategy\Dsl\Document\DocumentChecker"
Cohesion: 0.07
Nodes (28): {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#9}(), ParameterSpec, DslWriter, Scope (+20 more)

### Community 69 - "InvalidArgumentException"
Cohesion: 0.07
Nodes (11): StoredInstruments, {closure#1}(), ExportStrategies, GridBacktest, GridPart, ImportStrategies, {closure#1}(), PreparesBacktests (+3 more)

### Community 70 - "Engine"
Cohesion: 0.05
Nodes (37): DecisionLog, DecisionRecord, EngineRecord, OrderRole, Entry, Exit, Flatten, Stop (+29 more)

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
Cohesion: 0.16
Nodes (9): Income, IncomeKind, Funding, Liquidation, OrderUpdate, {closure#1}(), UpdateStream, {closure#3}() (+1 more)

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
Cohesion: 0.10
Nodes (20): Cscv, DeflatedSharpe, Normal, closeTo(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}() (+12 more)

### Community 82 - "MonteCarlo"
Cohesion: 0.30
Nodes (9): MonteCarlo, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), mcDecimals() (+1 more)

### Community 83 - "App\Support\Number\Decimal"
Cohesion: 0.07
Nodes (56): {closure#8}(), {closure#3}(), {closure#5}(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}() (+48 more)

### Community 84 - "pint/composer.json"
Cohesion: 0.20
Nodes (9): config, sort-packages, description, license, name, require, laravel/pint, php (+1 more)

### Community 85 - "healthyMachine"
Cohesion: 0.39
Nodes (8): {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), healthAt(), healthyMachine(), telegramSent()

### Community 86 - "RunSimulation.php"
Cohesion: 0.13
Nodes (4): ImportMarketData, {closure#3}(), RunSimulation, SimulationJob

### Community 87 - ".sqrt"
Cohesion: 0.23
Nodes (11): Statistics, {closure#1}(), {closure#3}(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}() (+3 more)

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
Cohesion: 0.13
Nodes (8): RegimeLabels, DirectionalMovement, {closure#1}(), {closure#2}(), {closure#4}(), {closure#7}(), {closure#9}(), days()

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
Cohesion: 0.42
Nodes (3): {closure#1}(), {closure#3}(), StrategyVersion

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

### Community 102 - "CompiledStrategyTest.php"
Cohesion: 0.18
Nodes (16): aggregatedBars(), {closure#1}(), {closure#11}(), {closure#13}(), {closure#16}(), {closure#17}(), {closure#2}(), {closure#3}() (+8 more)

### Community 103 - "StrategyGates.php"
Cohesion: 0.11
Nodes (11): {closure#8}(), {closure#2}(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#7}() (+3 more)

### Community 104 - "uses"
Cohesion: 0.21
Nodes (12): steps, name, runs-on, steps, frontend, lint, name, runs-on (+4 more)

### Community 105 - "SettingsScreenTest.php"
Cohesion: 0.24
Nodes (9): {closure#10}(), {closure#11}(), {closure#14}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), confirmed() (+1 more)

### Community 106 - "devDependencies"
Cohesion: 0.18
Nodes (12): devDependencies, concurrently@^10.0.3, laravel-vite-plugin@^3.1, @playwright/test@1.63.0, tailwindcss@^4.0.0, @tailwindcss/vite@^4.0.0, vite@^8.0.0, concurrently (+4 more)

### Community 108 - "BinanceVenueTest.php"
Cohesion: 0.21
Nodes (4): {closure#2}(), BinanceVenue, SignedBinanceClient, {closure#1}()

### Community 117 - "FunctionSpec"
Cohesion: 0.12
Nodes (13): {closure#2}(), {closure#3}(), FunctionSpec, Group, Exit, Indicator, Order, Perpetual (+5 more)

### Community 118 - "App\Agents\Mcp\TradingServer"
Cohesion: 0.17
Nodes (3): DslReference, ListInstruments, TradingServer

### Community 119 - ".breaches"
Cohesion: 0.24
Nodes (10): Band, bandReport(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#7}() (+2 more)

### Community 120 - "buy-and-hold.strategy.yaml"
Cohesion: 0.12
Nodes (16): entries, long, exits, stop_loss, fails_when, family, hypothesis, when (+8 more)

### Community 121 - "ChartSeriesTest.php"
Cohesion: 0.07
Nodes (16): BarIndicator, Keltner, Natr, Obv, Supertrend, VolumeProfile, Vwap, chart() (+8 more)

### Community 122 - "TokensTest.php"
Cohesion: 0.31
Nodes (6): {closure#3}(), {closure#4}(), contrastRatio(), contrastRules(), relativeLuminance(), themeTokens()

### Community 123 - "ArchiveImportTest.php"
Cohesion: 0.29
Nodes (10): archiveBytes(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}() (+2 more)

### Community 124 - "FortifyServiceProvider.php"
Cohesion: 0.10
Nodes (8): AppServiceProvider, {closure#7}(), {closure#3}(), {closure#4}(), FortifyServiceProvider, {closure#1}(), {closure#2}(), {closure#3}()

### Community 125 - "Illuminate\Database\Migrations\Migration"
Cohesion: 0.15
Nodes (3): {closure#1}(), {closure#1}(), {closure#1}()

### Community 126 - "Laravel\Fortify\Contracts\TwoFactorAuthenticationProvider"
Cohesion: 0.31
Nodes (3): ApproveLive, ConfirmsOperator, CreateAccount

### Community 127 - "collector"
Cohesion: 0.32
Nodes (8): aggTrade(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#5}(), collector(), defaultBinanceFakes(), markUpdate()

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
Cohesion: 0.16
Nodes (6): NotificationSettings, SettingSource, Env, None, TelegramClient, TelegramSettings

### Community 133 - "EnginePerpTest.php"
Cohesion: 0.14
Nodes (21): InMemoryDecisionLog, MinuteCandles, {closure#14}(), {closure#3}(), {closure#7}(), {closure#1}(), {closure#10}(), {closure#11}() (+13 more)

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

### Community 142 - "Account"
Cohesion: 0.24
Nodes (8): Account, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), reportAccount(), reportEvent(), reportTrade()

### Community 143 - "CandleRepositoryTest.php"
Cohesion: 0.28
Nodes (11): candleStore(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}() (+3 more)

### Community 145 - "Money"
Cohesion: 0.27
Nodes (3): {closure#3}(), {closure#2}(), Money

### Community 146 - "HealthChecks"
Cohesion: 0.09
Nodes (9): CheckHealth, DiskUsage, {closure#1}(), HealthAlerts, HealthChecks, HealthResult, LocalDiskUsage, {closure#6}() (+1 more)

### Community 147 - "scripts"
Cohesion: 0.50
Nodes (4): scripts, build, dev, e2e

### Community 148 - "0001_01_01_000002_create_jobs_table.php"
Cohesion: 0.33
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

### Community 151 - "WalkForward.php"
Cohesion: 0.11
Nodes (12): {closure#11}(), {closure#12}(), {closure#2}(), {closure#3}(), {closure#6}(), {closure#1}(), {closure#5}(), {closure#6}() (+4 more)

### Community 157 - "strategies-screen.blade.php"
Cohesion: 0.33
Nodes (5): open, promote, promoteDeployment, run, save

### Community 159 - "extra"
Cohesion: 0.67
Nodes (3): extra, laravel, dont-discover

### Community 173 - "GlobalsTest.php"
Cohesion: 0.13
Nodes (4): ChartTool, GetCandles, Controller, ChartSeries

### Community 174 - "StrategyLibrary"
Cohesion: 0.18
Nodes (6): {closure#1}(), CompileStrategy, StrategiesScreen, StrategyLibrary, CheckResult, SaveResult

### Community 175 - "App\Support\Clock\Clock"
Cohesion: 0.04
Nodes (24): Backfill, {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}(), {closure#8}() (+16 more)

### Community 176 - "Unit"
Cohesion: 0.09
Nodes (18): {closure#2}(), {closure#1}(), {closure#2}(), ParamKind, Anchor, Count, Duration, Number (+10 more)

### Community 177 - "App\Strategy\Dsl\Document\StrategySpec"
Cohesion: 0.35
Nodes (7): {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#7}(), readBackDocument(), readBackSpec()

### Community 181 - "Percent"
Cohesion: 0.31
Nodes (3): Percent, {closure#14}(), {closure#15}()

### Community 182 - "DocumentCheckerTest.php"
Cohesion: 0.30
Nodes (10): checkDocument(), {closure#1}(), {closure#2}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}(), {closure#8}() (+2 more)

### Community 183 - "SimulationJobsTest.php"
Cohesion: 0.21
Nodes (5): {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), savedEmaCross()

### Community 184 - "Quantity"
Cohesion: 0.24
Nodes (3): Quantity, {closure#10}(), {closure#9}()

### Community 189 - "MarketType"
Cohesion: 0.06
Nodes (31): {closure#1}(), {closure#2}(), BinanceRequestFailed, BinanceRestClient, EpochTime, ExchangeNumber, Json, LocationRestricted (+23 more)

### Community 190 - "Expression"
Cohesion: 0.40
Nodes (3): {closure#7}(), EntrySpec, Expression

### Community 193 - "WallClock"
Cohesion: 0.40
Nodes (3): WallClock, {closure#1}(), {closure#6}()

### Community 195 - "2026_11_09_000016_create_market_universe_tables.php"
Cohesion: 0.40
Nodes (3): {closure#1}(), {closure#2}(), up()

### Community 197 - "2026_11_09_000017_create_live_tables.php"
Cohesion: 0.33
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

### Community 198 - "RulesHistory"
Cohesion: 0.23
Nodes (9): RulesHistory, {closure#2}(), {closure#4}(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), rules() (+1 more)

### Community 200 - "Side"
Cohesion: 0.14
Nodes (11): VenuePosition, {closure#1}(), {closure#2}(), SimulatedPosition, PositionView, TradeMemory, Side, Buy (+3 more)

### Community 202 - ".times"
Cohesion: 0.12
Nodes (5): InstrumentRules, AccountView, Verdict, RiskEngine, {closure#4}()

### Community 205 - "2026_11_09_000015_create_scalping_tables.php"
Cohesion: 0.33
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

## Knowledge Gaps
- **147 isolated node(s):** `forgetTelegram`, `testTelegram`, `confirm`, `notify`, `monitor` (+142 more)
  These have ≤1 connection - possible missing edges. (Counts symbols only; 933 node(s) total have ≤1 connection when file, concept and rationale nodes are included.)
- **48 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `Decimal` connect `Decimal` to `.of`, `EngineTest.php`, `CanonicalJson`, `DslError`, `Asset`, `.parse`, `DocumentChecker`, `ChartScreen.php`, `EnginePerpTest.php`, `Side`, `Instrument`, `.of`, `Candle`, `IndicatorsTest.php`, `PolicyLimits`, `Money`, `LedgerTest.php`, `DocumentCheckerTest.php`, `WalkForward.php`, `Symbol`, `.of`, `Price`, `Engine.php`, `CompiledStrategy.php`, `Carbon\CarbonImmutable`, `Venue`, `BacktestTest.php`, `App\Strategy\Dsl\Document\DocumentChecker`, `SimulatedVenue`, `Timeframe`, `Illuminate\Database\Eloquent\Model`, `App\Support\Clock\Clock`, `App\Strategy\Dsl\Document\StrategySpec`, `PerformanceMetrics`, `Illuminate\Database\ConnectionInterface`, `MonteCarlo.php`, `Percent`, `Illuminate\Http\Client\Request`, `InvalidArgumentException`, `SizedStrategyTest.php`, `Quantity`, `IndicatorReferenceTest.php`, `Side`, `RunBacktestCommandTest.php`, `MarketType`, `Expression`, `DecimalMath`, `Ledger`, `PerpetualBaselinesTest.php`, `App\Strategy\Dsl\Document\DocumentChecker`, `InvalidArgumentException`, `Engine`, `.evaluate`, `Side`, `RulesHistory`, `.times`, `OrderUpdate`, `.measure`, `DecimalMath`, `MonteCarlo`, `App\Support\Number\Decimal`, `.sqrt`, `.evaluate`, `.fromStrings`, `CompiledStrategyTest.php`, `StrategyGates.php`, `BinanceVenueTest.php`, `ChartSeriesTest.php`?**
  _High betweenness centrality (0.109) - this node is a cross-community bridge._
- **What connects `forgetTelegram`, `testTelegram`, `confirm` to the rest of the system?**
  _147 weakly-connected nodes found - possible missing edges._
- **Should `.of` be split into smaller, more focused modules?**
  _Cohesion score 0.07200929152148665 - nodes in this community are weakly interconnected._
- **Why does `Symbol` connect `Symbol` to `EngineTest.php`, `CanonicalJson`, `DslError`, `Asset`, `.parse`, `DocumentChecker`, `ChartScreen.php`, `EnginePerpTest.php`, `Side`, `Instrument`, `ExchangeVerificationTest.php`, `PolicyLimits`, `CandleRepositoryTest.php`, `ScreensTest.php`, `LedgerTest.php`, `.of`, `Price`, `Engine.php`, `.now`, `.parse`, `Venue`, `BacktestTest.php`, `Decimal`, `SimulatedVenue`, `GlobalsTest.php`, `Timeframe`, `App\Support\Clock\Clock`, `Illuminate\Database\Eloquent\Model`, `App\Strategy\Dsl\Document\StrategySpec`, `Illuminate\Http\Client\Request`, `Illuminate\Database\ConnectionInterface`, `Side`, `InvalidArgumentException`, `RunBacktestCommandTest.php`, `MarketType`, `Ledger`, `PerpetualBaselinesTest.php`, `{closure#12}`, `InvalidArgumentException`, `RulesHistory`, `Engine`, `Side`, `OrderUpdate`, `.measure`, `CompiledStrategyTest.php`, `StrategyGates.php`, `BinanceVenueTest.php`, `ChartSeriesTest.php`?**
  _High betweenness centrality (0.045) - this node is a cross-community bridge._
- **Should `EngineTest.php` be split into smaller, more focused modules?**
  _Cohesion score 0.09326923076923077 - nodes in this community are weakly interconnected._
- **Why does `Timeframe` connect `Timeframe` to `EngineTest.php`, `DslError`, `.parse`, `DocumentChecker`, `ChartScreen.php`, `EnginePerpTest.php`, `Side`, `Instrument`, `Candle`, `Carbon\CarbonImmutable`, `IndicatorsTest.php`, `CandleRepositoryTest.php`, `ReadBack`, `Symbol`, `Engine.php`, `CompiledStrategy.php`, `Carbon\CarbonImmutable`, `App\Strategy\Dsl\Document\DocumentChecker`, `GlobalsTest.php`, `App\Support\Clock\Clock`, `App\Strategy\Dsl\Document\StrategySpec`, `Illuminate\Database\ConnectionInterface`, `DocumentCheckerTest.php`, `InvalidArgumentException`, `IndicatorReferenceTest.php`, `MarketType`, `App\Strategy\Dsl\Document\DocumentChecker`, `InvalidArgumentException`, `Engine`, `.sqrt`, `.fromStrings`, `CompiledStrategyTest.php`, `StrategyGates.php`, `ChartSeriesTest.php`?**
  _High betweenness centrality (0.023) - this node is a cross-community bridge._
- **Should `DslError` be split into smaller, more focused modules?**
  _Cohesion score 0.1361344537815126 - nodes in this community are weakly interconnected._