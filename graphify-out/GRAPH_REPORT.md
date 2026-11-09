# Graph Report - trading-platform  (2026-11-10)

## Corpus Check
- 661 files · ~285,672 words
- Verdict: corpus is large enough that graph structure adds value.
- Unclassified: 103 file(s) not represented in the graph (top: .zip 38, .CHECKSUM 38, (none) 19)

## Summary
- 5410 nodes · 18840 edges · 221 communities (159 shown, 62 thin omitted)
- Extraction: 90% EXTRACTED · 10% INFERRED · 0% AMBIGUOUS · INFERRED: 1858 edges (avg confidence: 0.85)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `04dee9b6`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- SimulatedPerpVenueTest.php
- engineHarness
- .of
- PreparesBacktests.php
- SettingsScreen
- Strategy
- DocumentChecker
- .now
- CompiledStrategy.php
- .of
- BinanceVenueTest.php
- StrategyVersionsTest.php
- Candle
- ExchangeVerificationTest.php
- Engine
- .handle
- Strategy
- Clock
- Instrument
- ScreensTest.php
- Ledger
- Illuminate\Database\Schema\Blueprint
- generate.py
- App\Strategy\Dsl\Compiler\CompiledStrategy
- Symbol
- .measure
- TerminalQrCodeTest.php
- .of
- EngineUniverseTest.php
- StrategyVersion
- depthStreamWith
- oi-momentum-long.strategy.yaml
- App\Strategy\Data\StrategyContext
- App\Support\Symbol
- DslError
- App\Livewire\StrategiesScreen
- ScalpingCollectorTest.php
- healthyMachine
- OrderManagerTest.php
- InstrumentRules
- Position
- ema-cross-scalp-long.strategy.yaml
- Decimal
- .times
- Parser
- Timeframe
- .now
- package.json
- .plus
- BinanceRestClientTest.php
- ema-cross-scalp-short.strategy.yaml
- ChartScreen.php
- ReadBack
- App\Market\Stream\PhrityTransport
- postgres
- UniverseStrategy
- LadderTest.php
- donchian-breakout-short.strategy.yaml
- IndicatorsTest.php
- support.mjs
- backtestFixture
- IndicatorReferenceTest.php
- App\Support\Clock\Clock
- .minus
- Illuminate\Http\Request
- donchian-breakout.strategy.yaml
- .run
- .expect
- Kind
- Price
- CompiledStrategyTest.php
- Statistics
- ExitReason
- scripts
- ma-trend-short.strategy.yaml
- .evaluate
- EnginePerpTest.php
- composer.json
- jobs
- postgres
- ma-trend.strategy.yaml
- .isPositive
- ForwardingVenue
- App\Support\Number\Decimal
- pint/composer.json
- App\Market\Instruments\Universe
- RunSimulation.php
- App\Notifications\SendTelegram
- require-dev
- scripts-descriptions
- MarketBreakers
- AccountRuntime.php
- config
- uses
- test
- with
- self
- jobs
- Lexer
- psr-4
- require
- InvalidArgumentException
- StrategyGates.php
- uses
- .account
- devDependencies
- Laravel\Mcp\Response
- StrategyIdentity
- BinanceArchive
- PaperEngineTest.php
- buy-and-hold.strategy.yaml
- TradingTool
- TokensTest.php
- ArchiveImportTest.php
- User
- Illuminate\Support\Facades\Schema
- ScalpingArchiveTest.php
- .register
- funding-carry.strategy.yaml
- McpServerTest.php
- env.mjs
- chart.js
- TelegramSettings
- UserFactory.php
- settings-screen.blade.php
- .of
- steps
- 2026_11_09_000014_create_health_tables.php
- DecimalMath
- App\Market\Models\Instrument
- App\Simulation\StrategyLibrary
- CandleRepositoryTest.php
- DailyReport
- Illuminate\Console\Command
- OrderBook
- scripts
- 2026_11_08_000007_create_strategy_gate_tables.php
- fake-telegram.mjs
- 2026_11_08_000003_create_strategy_trials_table.php
- Percent
- 2026_11_08_000004_create_strategy_versions_tables.php
- 2026_11_08_000006_create_mcp_tables.php
- 2026_11_09_000017_create_live_tables.php
- App\Strategy\Data\StrategyLimits
- strategies-screen.blade.php
- extra
- app.blade.php
- halt
- remove
- account-screen.blade.php
- Illuminate\Database\Migrations\Migration
- SimulationQueue
- VenuePosition
- Venue
- 2026_11_09_000027_add_to_to_market_jobs.php
- UniverseStrategyTest.php
- 2026_11_09_000012_create_account_results_tables.php
- 2026_11_09_000023_create_universe_members_table.php
- SettingsScreenTest.php
- GlobalsTest.php
- App\Strategy\Dsl\DslError
- UniverseBacktestTest.php
- ParserTest.php
- MarketType
- Illuminate\Contracts\Config\Repository
- 2026_11_09_000025_create_book_depth_table.php
- 0001_01_01_000002_create_jobs_table.php
- 2026_11_09_000009_create_exchange_keys_table.php
- 2026_11_09_000018_add_effective_at_to_deployments.php
- 2026_11_09_000016_create_market_universe_tables.php
- TradeFlowBuilder
- DslReference
- start-local.ps1
- Illuminate\Database\Seeder
- ClientOrderId
- App\Agents\Mcp\Tools\ListTrials
- 0001_01_01_000000_create_users_table.php
- MarketDataSettingsTest.php
- WallClock
- RulesHistory
- KlineMessageTest.php
- Engine.php
- streamWith
- 2026_11_09_000020_add_lifetime_to_instruments.php
- CompiledStrategy
- App
- GridWorkersTest.php
- 2026_11_09_000026_create_liquidation_tables.php
- 2026_11_09_000022_create_daily_stats_table.php
- StrategyGatesTest.php
- 2026_11_09_000010_add_two_factor_columns_to_users_table.php
- 2026_11_09_000024_create_positioning_table.php

## God Nodes (most connected - your core abstractions)
1. `Decimal` - 472 edges
2. `Side` - 202 edges
3. `Symbol` - 179 edges
4. `App` - 179 edges
5. `Candle` - 116 edges
6. `Timeframe` - 105 edges
7. `Price` - 92 edges
8. `Position` - 89 edges
9. `SimClock` - 85 edges
10. `SimulatedVenue` - 70 edges

## Surprising Connections (you probably didn't know these)
- `{closure#1}()` --calls--> `App`  [INFERRED]
  tests/Feature/Support/ClockBindingTest.php → app/Notifications/SettingSource.php
- `{closure#10}()` --calls--> `App`  [INFERRED]
  tests/Feature/Market/ScalpingArchiveTest.php → app/Notifications/SettingSource.php
- `{closure#13}()` --calls--> `App`  [INFERRED]
  tests/Feature/Market/ScalpingArchiveTest.php → app/Notifications/SettingSource.php
- `{closure#6}()` --calls--> `App`  [INFERRED]
  tests/Feature/Market/ScalpingArchiveTest.php → app/Notifications/SettingSource.php
- `{closure#8}()` --calls--> `App`  [INFERRED]
  tests/Feature/Market/ScalpingArchiveTest.php → app/Notifications/SettingSource.php

## Import Cycles
- None detected.

## Communities (221 total, 62 thin omitted)

### Community 0 - "SimulatedPerpVenueTest.php"
Cohesion: 0.16
Nodes (32): FundingRate, MinuteCandles, PriceMinute, {closure#2}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}() (+24 more)

### Community 1 - "engineHarness"
Cohesion: 0.10
Nodes (42): EngineMode, Backtest, Live, Paper, Testnet, ScriptedStrategy, {closure#10}(), {closure#13}() (+34 more)

### Community 2 - ".of"
Cohesion: 0.08
Nodes (31): FeeSchedule, CostModel, {closure#3}(), {closure#9}(), balances(), {closure#41}(), {closure#44}(), {closure#45}() (+23 more)

### Community 3 - "PreparesBacktests.php"
Cohesion: 0.06
Nodes (30): {closure#9}(), {closure#1}(), {closure#5}(), {closure#2}(), {closure#4}(), BookDepth, FormingCandle, Liquidations (+22 more)

### Community 4 - "SettingsScreen"
Cohesion: 0.08
Nodes (6): CreateMcpToken, RevokeMcpToken, Tokens, OpenAccount, StoreKeys, SettingsScreen

### Community 5 - "Strategy"
Cohesion: 0.10
Nodes (36): {closure#12}(), {closure#17}(), {closure#5}(), carryDecisions(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}() (+28 more)

### Community 7 - ".now"
Cohesion: 0.09
Nodes (18): {closure#1}(), {closure#2}(), Deployment, {closure#1}(), {closure#1}(), ReviewLadder, Ladder, LadderStatus (+10 more)

### Community 8 - "CompiledStrategy.php"
Cohesion: 0.10
Nodes (8): PerpetualTracker, {closure#45}(), {closure#47}(), {closure#54}(), {closure#70}(), Compiler, {closure#1}(), Program

### Community 9 - ".of"
Cohesion: 0.17
Nodes (43): TradeMemory, Side, Buy, Sell, btc(), {closure#10}(), {closure#11}(), {closure#12}() (+35 more)

### Community 10 - "BinanceVenueTest.php"
Cohesion: 0.10
Nodes (17): {closure#2}(), BinanceVenue, BinanceApiError, BinanceUnreachable, SignedBinanceClient, binanceVenue(), {closure#1}(), {closure#2}() (+9 more)

### Community 11 - "StrategyVersionsTest.php"
Cohesion: 0.20
Nodes (8): InstrumentDirectory, StrategyRepository, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), versions()

### Community 12 - "Candle"
Cohesion: 0.06
Nodes (44): {closure#1}(), {closure#3}(), Candle, InvalidCandle, KlineMessage, RegimeLabels, DirectionalMovement, {closure#1}() (+36 more)

### Community 13 - "ExchangeVerificationTest.php"
Cohesion: 0.11
Nodes (31): {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#2}(), {closure#3}(), {closure#4}() (+23 more)

### Community 14 - "Engine"
Cohesion: 0.08
Nodes (8): DecisionLog, DecisionRecord, EngineRecord, IntentRecord, Engine, {closure#1}(), DatabaseDecisionLog, {closure#1}()

### Community 15 - ".handle"
Cohesion: 0.11
Nodes (4): GridBacktest, {closure#2}(), ReadsGridOptions, StrategyGates

### Community 16 - "Strategy"
Cohesion: 0.11
Nodes (24): CanonicalJson, Strategy, baselineDocument(), baselinePairs(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}() (+16 more)

### Community 17 - "Clock"
Cohesion: 0.07
Nodes (18): AuthenticateMcpToken, FlattenEngine, HaltEngine, IssuesCommands, PauseStrategy, ResumeEngine, {closure#5}(), CommandKind (+10 more)

### Community 18 - "Instrument"
Cohesion: 0.08
Nodes (11): BackfillStep, CandleRepository, CandleSource, Archive, Rest, Stream, UpsertResult, FundingRateRepository (+3 more)

### Community 19 - "ScreensTest.php"
Cohesion: 0.14
Nodes (22): {closure#1}(), chartLiveQuery(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}() (+14 more)

### Community 20 - "Ledger"
Cohesion: 0.14
Nodes (31): Ledger, LedgerViolation, balanceOf(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}() (+23 more)

### Community 21 - "Illuminate\Database\Schema\Blueprint"
Cohesion: 0.14
Nodes (13): {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#1}(), {closure#2}(), {closure#3}() (+5 more)

### Community 22 - "generate.py"
Cohesion: 0.07
Nodes (30): atr(), bars(), bb(), change(), dmi(), donchian(), ema(), highest() (+22 more)

### Community 23 - "App\Strategy\Dsl\Compiler\CompiledStrategy"
Cohesion: 0.06
Nodes (22): {closure#2}(), {closure#3}(), {closure#5}(), {closure#6}(), {closure#7}(), {closure#8}(), {closure#9}(), {closure#2}() (+14 more)

### Community 24 - "Symbol"
Cohesion: 0.04
Nodes (23): {closure#2}(), {closure#3}(), {closure#4}(), BuyAndHold, DonchianBreakout, FundingCarry, MovingAverageTrend, Parameters (+15 more)

### Community 25 - ".measure"
Cohesion: 0.14
Nodes (9): EquityTracker, Performance, {closure#1}(), {closure#2}(), {closure#3}(), {closure#5}(), {closure#6}(), {closure#8}() (+1 more)

### Community 26 - "TerminalQrCodeTest.php"
Cohesion: 0.20
Nodes (7): TerminalQrCode, {closure#3}(), {closure#4}(), {closure#5}(), expectedModules(), readColoured(), readPlain()

### Community 27 - ".of"
Cohesion: 0.06
Nodes (80): AccountView, RejectionReason, BelowMinimum, Breaker, Cooldown, Delisting, DuplicateIntent, EntryWorking (+72 more)

### Community 28 - "EngineUniverseTest.php"
Cohesion: 0.21
Nodes (16): {closure#10}(), {closure#11}(), {closure#13}(), {closure#14}(), {closure#17}(), {closure#2}(), {closure#3}(), {closure#6}() (+8 more)

### Community 29 - "StrategyVersion"
Cohesion: 0.25
Nodes (5): {closure#1}(), {closure#3}(), CheckResult, SaveResult, StrategyVersion

### Community 30 - "depthStreamWith"
Cohesion: 0.67
Nodes (4): {closure#1}(), {closure#2}(), depthMessage(), depthStreamWith()

### Community 31 - "oi-momentum-long.strategy.yaml"
Cohesion: 0.07
Nodes (27): entries, long, exits, stop_loss, take_profit, when, fails_when, family (+19 more)

### Community 32 - "App\Strategy\Data\StrategyContext"
Cohesion: 0.12
Nodes (9): {closure#2}(), {closure#3}(), {closure#1}(), ScriptedUniverseStrategy, {closure#12}(), {closure#16}(), {closure#5}(), {closure#11}() (+1 more)

### Community 33 - "App\Support\Symbol"
Cohesion: 0.05
Nodes (26): {closure#1}(), {closure#2}(), {closure#3}(), PerpetualView, LiveBar, approveLive(), {closure#2}(), {closure#4}() (+18 more)

### Community 34 - "DslError"
Cohesion: 0.22
Nodes (11): {closure#1}(), DslError, YamlList, YamlMap, YamlReader, YamlScalar, YamlValue, {closure#1}() (+3 more)

### Community 35 - "App\Livewire\StrategiesScreen"
Cohesion: 0.08
Nodes (12): Account, ReauthController, AccountScreen, ChartScreen, HaltButton, {closure#1}(), ModeBadge, {closure#2}() (+4 more)

### Community 36 - "ScalpingCollectorTest.php"
Cohesion: 0.35
Nodes (10): aggTrade(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#6}(), collector(), defaultBinanceFakes() (+2 more)

### Community 37 - "healthyMachine"
Cohesion: 0.21
Nodes (9): {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), healthAt(), healthyMachine(), telegramSent() (+1 more)

### Community 38 - "OrderManagerTest.php"
Cohesion: 0.10
Nodes (33): OrderAck, OrderRole, Entry, Exit, Flatten, Stop, Target, TrackedOrder (+25 more)

### Community 39 - "InstrumentRules"
Cohesion: 0.07
Nodes (31): CandleFeed, Backtest, {closure#1}(), {closure#2}(), {closure#3}(), {closure#2}(), RunBacktest, BacktestResult (+23 more)

### Community 40 - "Position"
Cohesion: 0.07
Nodes (24): Argument, BoolLiteral, Call, DurationLiteral, Identifier, Lookback, Node, NumberLiteral (+16 more)

### Community 41 - "ema-cross-scalp-long.strategy.yaml"
Cohesion: 0.06
Nodes (33): entries, long, exits, stop_loss, take_profit, time_stop, when, fails_when (+25 more)

### Community 42 - "Decimal"
Cohesion: 0.04
Nodes (37): {closure#2}(), RoundTrip, {closure#10}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}() (+29 more)

### Community 43 - ".times"
Cohesion: 0.06
Nodes (17): OrderStatus, Cancelled, Expired, Filled, New, PartiallyFilled, {closure#2}(), {closure#3}() (+9 more)

### Community 44 - "Parser"
Cohesion: 0.14
Nodes (20): Binary, Parser, Token, TokenKind, Colon, Comma, Dot, Duration (+12 more)

### Community 45 - "Timeframe"
Cohesion: 0.06
Nodes (55): CandleAggregator, CandleSequenceBroken, {closure#4}(), CarryTemplate, {closure#8}(), Timeframe, FifteenMinutes, FiveMinutes (+47 more)

### Community 46 - ".now"
Cohesion: 0.03
Nodes (32): McpCall, McpToken, AccountRuntime, LiveGate, RunEngine, ReplayDivergence, AccountEquity, AccountSnapshot (+24 more)

### Community 47 - "package.json"
Cohesion: 0.15
Nodes (17): dependencies, codemirror@^6.0.2, @codemirror/lang-yaml@^6.1.3, @codemirror/language@^6.13.1, @lezer/highlight@^1.2.5, lightweight-charts@^5.2.1, optionalDependencies, @laravel/multiplex@^0.4.1 (+9 more)

### Community 48 - ".plus"
Cohesion: 0.09
Nodes (16): {closure#1}(), VolumeProfile, {closure#8}(), {closure#16}(), chart(), {closure#10}(), {closure#11}(), {closure#2}() (+8 more)

### Community 49 - "BinanceRestClientTest.php"
Cohesion: 0.09
Nodes (30): {closure#6}(), {closure#7}(), {closure#1}(), {closure#3}(), {closure#12}(), {closure#22}(), {closure#5}(), {closure#9}() (+22 more)

### Community 50 - "ema-cross-scalp-short.strategy.yaml"
Cohesion: 0.06
Nodes (33): entries, short, exits, stop_loss, take_profit, time_stop, when, fails_when (+25 more)

### Community 51 - "ChartScreen.php"
Cohesion: 0.07
Nodes (26): {closure#2}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), Group, Exit, Indicator (+18 more)

### Community 53 - "App\Market\Stream\PhrityTransport"
Cohesion: 0.12
Nodes (3): PhrityTransport, StreamFailed, FakeTransport

### Community 54 - "postgres"
Cohesion: 0.10
Nodes (19): POSTGRES_DB, POSTGRES_PASSWORD, POSTGRES_USER, interval, retries, test, timeout, environment (+11 more)

### Community 55 - "UniverseStrategy"
Cohesion: 0.16
Nodes (4): UniverseSchedule, UniverseStrategy, {closure#17}(), {closure#1}()

### Community 56 - "LadderTest.php"
Cohesion: 0.38
Nodes (11): at(), {closure#1}(), {closure#10}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}() (+3 more)

### Community 57 - "donchian-breakout-short.strategy.yaml"
Cohesion: 0.07
Nodes (26): entries, short, default, range, step, default, range, step (+18 more)

### Community 58 - "IndicatorsTest.php"
Cohesion: 0.09
Nodes (22): Atr, Donchian, Ema, Period, Sma, ValueIndicator, {closure#5}(), bar() (+14 more)

### Community 59 - "support.mjs"
Cohesion: 0.26
Nodes (9): @playwright/test, confirmed(), checkLayout(), EMAIL, freshCode(), PASSWORD, reauthenticate(), totp() (+1 more)

### Community 60 - "backtestFixture"
Cohesion: 0.16
Nodes (31): {closure#1}(), {closure#1}(), depthSnapshot(), {closure#2}(), {closure#4}(), {closure#5}(), scalpingBackfill(), {closure#4}() (+23 more)

### Community 61 - "IndicatorReferenceTest.php"
Cohesion: 0.03
Nodes (31): BarIndicator, Change, DonchianChannel, Highest, Hma, Keltner, Lowest, Macd (+23 more)

### Community 62 - "App\Support\Clock\Clock"
Cohesion: 0.05
Nodes (12): DepthStream, FormingCandles, Heartbeats, {closure#1}(), InstrumentSync, SyncResult, LiquidationRepository, CandleStream (+4 more)

### Community 63 - ".minus"
Cohesion: 0.13
Nodes (11): {closure#4}(), {closure#5}(), MonteCarlo, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}() (+3 more)

### Community 64 - "Illuminate\Http\Request"
Cohesion: 0.10
Nodes (8): ChartLiveController, AppServiceProvider, {closure#3}(), {closure#4}(), FortifyServiceProvider, {closure#1}(), {closure#2}(), {closure#3}()

### Community 65 - "donchian-breakout.strategy.yaml"
Cohesion: 0.07
Nodes (26): entries, long, default, range, step, default, range, step (+18 more)

### Community 66 - ".run"
Cohesion: 0.08
Nodes (35): RiskProfile, Account, Benchmark, RiskPolicy, Report, {closure#1}(), {closure#2}(), {closure#3}() (+27 more)

### Community 67 - ".expect"
Cohesion: 0.18
Nodes (15): UniverseRule, checkDocument(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#2}() (+7 more)

### Community 68 - "Kind"
Cohesion: 0.07
Nodes (40): {closure#1}(), {closure#3}(), {closure#4}(), ParameterSpec, DslWriter, FunctionSpec, Library, Scope (+32 more)

### Community 69 - "Price"
Cohesion: 0.13
Nodes (8): ProtectionPlan, {closure#1}(), {closure#2}(), Fill, PerpPosition, Position, Price, Perpetual

### Community 70 - "CompiledStrategyTest.php"
Cohesion: 0.14
Nodes (19): PositionView, StrategyContext, {closure#18}(), {closure#37}(), aggregatedBars(), {closure#1}(), {closure#11}(), {closure#13}() (+11 more)

### Community 71 - "Statistics"
Cohesion: 0.13
Nodes (19): Statistics, Gates, {closure#3}(), brokenGate(), {closure#1}(), {closure#3}(), {closure#4}(), {closure#6}() (+11 more)

### Community 72 - "ExitReason"
Cohesion: 0.19
Nodes (24): ExitReason, Flatten, Liquidation, Signal, Stop, Target, {closure#1}(), {closure#10}() (+16 more)

### Community 73 - "scripts"
Cohesion: 0.15
Nodes (13): scripts, analyse, dev, format, mutate, post-autoload-dump, post-update-cmd, pre-package-uninstall (+5 more)

### Community 74 - "ma-trend-short.strategy.yaml"
Cohesion: 0.07
Nodes (26): entries, short, exits, stop_loss, when, fails_when, family, default (+18 more)

### Community 75 - ".evaluate"
Cohesion: 0.25
Nodes (8): WalkForward, WalkForwardSet, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), wfSet()

### Community 76 - "EnginePerpTest.php"
Cohesion: 0.20
Nodes (15): InMemoryDecisionLog, {closure#10}(), {closure#11}(), {closure#14}(), {closure#2}(), {closure#5}(), {closure#6}(), {closure#7}() (+7 more)

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
Nodes (20): {closure#7}(), Cscv, DeflatedSharpe, closeTo(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}() (+12 more)

### Community 83 - "App\Support\Number\Decimal"
Cohesion: 0.06
Nodes (66): {closure#3}(), TradeFlow, {closure#1}(), {closure#8}(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}() (+58 more)

### Community 84 - "pint/composer.json"
Cohesion: 0.20
Nodes (9): config, sort-packages, description, license, name, require, laravel/pint, php (+1 more)

### Community 85 - "App\Market\Instruments\Universe"
Cohesion: 0.04
Nodes (20): ArchiveMissing, ChecksumMismatch, SelectsInstruments, {closure#1}(), {closure#2}(), StreamCandles, VerifyMarketData, {closure#1}() (+12 more)

### Community 86 - "RunSimulation.php"
Cohesion: 0.07
Nodes (14): {closure#4}(), Notifier, Sleeper, SystemSleeper, HistoryImports, MarketJob, LocalDiskUsage, TelegramNotifier (+6 more)

### Community 87 - "App\Notifications\SendTelegram"
Cohesion: 0.15
Nodes (5): ImportMarketData, RunSimulation, {closure#5}(), {closure#5}(), {closure#7}()

### Community 88 - "require-dev"
Cohesion: 0.22
Nodes (9): require-dev, fakerphp/faker, larastan/larastan, laravel/pail, laravel/pao, mockery/mockery, nunomaduro/collision, pestphp/pest (+1 more)

### Community 89 - "scripts-descriptions"
Cohesion: 0.25
Nodes (8): scripts-descriptions, analyse, format, mutate, test, test:network, test:testnet, tools:install

### Community 90 - "MarketBreakers"
Cohesion: 0.19
Nodes (9): BreakerEvent, MarketBreakers, ReferenceMarketBreakers, breakerMinute(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}() (+1 more)

### Community 91 - "AccountRuntime.php"
Cohesion: 0.17
Nodes (4): {closure#2}(), SizedStrategy, {closure#1}(), LimitedStrategy

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

### Community 96 - "self"
Cohesion: 0.09
Nodes (10): {closure#6}(), {closure#10}(), {closure#5}(), {closure#4}(), {closure#12}(), {closure#1}(), InvalidNumber, Quantity (+2 more)

### Community 97 - "jobs"
Cohesion: 0.25
Nodes (8): name, runs-on, name, runs-on, steps, jobs, analyse, audit

### Community 99 - "psr-4"
Cohesion: 0.40
Nodes (5): autoload, psr-4, App\\, Database\\Factories\\, Database\\Seeders\\

### Community 100 - "require"
Cohesion: 0.22
Nodes (9): require, bacon/bacon-qr-code, laravel/fortify, laravel/framework, laravel/mcp, laravel/tinker, livewire/livewire, php (+1 more)

### Community 102 - "InvalidArgumentException"
Cohesion: 0.05
Nodes (16): {closure#1}(), BuildUniverse, AssumedRules, {closure#2}(), {closure#3}(), ResearchUniverse, {closure#1}(), {closure#5}() (+8 more)

### Community 103 - "StrategyGates.php"
Cohesion: 0.09
Nodes (16): {closure#11}(), {closure#12}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#6}(), {closure#8}(), {closure#2}() (+8 more)

### Community 104 - "uses"
Cohesion: 0.21
Nodes (12): steps, name, runs-on, steps, frontend, lint, name, runs-on (+4 more)

### Community 105 - ".account"
Cohesion: 0.16
Nodes (13): Policies, {closure#1}(), {closure#2}(), __construct(), enterOnce(), identity(), limits(), onCandle() (+5 more)

### Community 106 - "devDependencies"
Cohesion: 0.18
Nodes (12): devDependencies, concurrently@^10.0.3, laravel-vite-plugin@^3.1, @playwright/test@1.63.0, tailwindcss@^4.0.0, @tailwindcss/vite@^4.0.0, vite@^8.0.0, concurrently (+4 more)

### Community 117 - "StrategyIdentity"
Cohesion: 0.22
Nodes (5): StrategyIdentity, RenamedStrategy, {closure#1}(), {closure#2}(), {closure#3}()

### Community 118 - "BinanceArchive"
Cohesion: 0.05
Nodes (18): ArchiveCatalog, ArchivePeriod, BinanceArchive, {closure#1}(), {closure#2}(), {closure#14}(), ExchangeNumber, FillDailyStats (+10 more)

### Community 119 - "PaperEngineTest.php"
Cohesion: 0.26
Nodes (13): {closure#1}(), {closure#10}(), {closure#12}(), {closure#14}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#6}() (+5 more)

### Community 120 - "buy-and-hold.strategy.yaml"
Cohesion: 0.12
Nodes (16): entries, long, exits, stop_loss, fails_when, family, hypothesis, when (+8 more)

### Community 121 - "TradingTool"
Cohesion: 0.09
Nodes (10): ChartTool, GetCandles, GetFunding, GetIndicators, GetJob, GetStrategy, ListInstruments, ListStrategies (+2 more)

### Community 122 - "TokensTest.php"
Cohesion: 0.31
Nodes (6): {closure#3}(), {closure#4}(), contrastRatio(), contrastRules(), relativeLuminance(), themeTokens()

### Community 123 - "ArchiveImportTest.php"
Cohesion: 0.29
Nodes (10): archiveBytes(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}() (+2 more)

### Community 125 - "Illuminate\Support\Facades\Schema"
Cohesion: 0.14
Nodes (4): {closure#1}(), {closure#1}(), {closure#1}(), {closure#2}()

### Community 126 - "ScalpingArchiveTest.php"
Cohesion: 0.09
Nodes (20): Positioning, {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#2}(), {closure#3}() (+12 more)

### Community 127 - ".register"
Cohesion: 0.08
Nodes (38): SimClock, {closure#1}(), {closure#10}(), {closure#13}(), {closure#14}(), {closure#15}(), {closure#16}(), {closure#18}() (+30 more)

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

### Community 135 - "settings-screen.blade.php"
Cohesion: 0.15
Nodes (12): approveLive, confirm, forgetKey, forgetTelegram, importUniverse, monitor, notify, removeInstrument (+4 more)

### Community 137 - ".of"
Cohesion: 0.09
Nodes (21): Asset, AssetMismatch, Money, {closure#16}(), {closure#2}(), {closure#1}(), usdt(), {closure#11}() (+13 more)

### Community 138 - "steps"
Cohesion: 0.33
Nodes (6): name, runs-on, services, steps, e2e, if

### Community 139 - "2026_11_09_000014_create_health_tables.php"
Cohesion: 0.33
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

### Community 140 - "DecimalMath"
Cohesion: 0.16
Nodes (14): DecimalMath, {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#2}() (+6 more)

### Community 141 - "App\Market\Models\Instrument"
Cohesion: 0.09
Nodes (20): Backfill, {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#15}(), {closure#2}() (+12 more)

### Community 142 - "App\Simulation\StrategyLibrary"
Cohesion: 0.16
Nodes (6): CompileStrategy, SaveStrategy, StrategiesScreen, {closure#3}(), SimulationQueue, StrategyLibrary

### Community 143 - "CandleRepositoryTest.php"
Cohesion: 0.31
Nodes (11): candleStore(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}() (+3 more)

### Community 145 - "Illuminate\Console\Command"
Cohesion: 0.11
Nodes (9): ApproveLive, ConfirmsOperator, CreateAccount, CheckMarketData, {closure#1}(), StreamDepth, CheckHealth, PromoteStrategy (+1 more)

### Community 146 - "OrderBook"
Cohesion: 0.15
Nodes (8): DepthUpdate, OrderBook, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), levels(), update()

### Community 147 - "scripts"
Cohesion: 0.50
Nodes (4): scripts, build, dev, e2e

### Community 152 - "Percent"
Cohesion: 0.24
Nodes (3): Percent, {closure#14}(), {closure#15}()

### Community 155 - "2026_11_09_000017_create_live_tables.php"
Cohesion: 0.33
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

### Community 157 - "strategies-screen.blade.php"
Cohesion: 0.33
Nodes (5): open, promote, promoteDeployment, run, save

### Community 159 - "extra"
Cohesion: 0.67
Nodes (3): extra, laravel, dont-discover

### Community 173 - "Illuminate\Database\Migrations\Migration"
Cohesion: 0.12
Nodes (4): {closure#1}(), {closure#2}(), {closure#1}(), {closure#1}()

### Community 174 - "SimulationQueue"
Cohesion: 0.16
Nodes (3): RunBacktest, RunGrid, SimulationTool

### Community 175 - "VenuePosition"
Cohesion: 0.31
Nodes (4): VenuePosition, {closure#1}(), SimulatedPosition, {closure#13}()

### Community 176 - "Venue"
Cohesion: 0.09
Nodes (5): Venue, WarmingVenue, {closure#12}(), {closure#32}(), {closure#34}()

### Community 178 - "UniverseStrategyTest.php"
Cohesion: 0.21
Nodes (18): CrossSectionalStrategy, {closure#10}(), {closure#12}(), {closure#15}(), {closure#16}(), {closure#18}(), {closure#3}(), {closure#4}() (+10 more)

### Community 181 - "SettingsScreenTest.php"
Cohesion: 0.18
Nodes (14): {closure#1}(), {closure#10}(), {closure#11}(), {closure#13}(), {closure#14}(), {closure#2}(), {closure#3}(), {closure#4}() (+6 more)

### Community 183 - "App\Strategy\Dsl\DslError"
Cohesion: 0.15
Nodes (15): {closure#2}(), {closure#1}(), StrategySpec, KnownInstruments, {closure#1}(), {closure#1}(), {closure#2}(), {closure#3}() (+7 more)

### Community 184 - "UniverseBacktestTest.php"
Cohesion: 0.13
Nodes (13): {closure#9}(), {closure#1}(), {closure#4}(), {closure#5}(), reportRows(), universeBacktest(), {closure#1}(), {closure#2}() (+5 more)

### Community 185 - "ParserTest.php"
Cohesion: 0.28
Nodes (6): AstPrinter, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), sexpr()

### Community 189 - "MarketType"
Cohesion: 0.06
Nodes (31): BinanceEndpoints, BinanceRequestFailed, BinanceRestClient, {closure#2}(), {closure#3}(), {closure#4}(), EpochTime, Json (+23 more)

### Community 190 - "Illuminate\Contracts\Config\Repository"
Cohesion: 0.10
Nodes (6): StoredInstruments, {closure#1}(), CompileStrategy, GridPart, PreparesBacktests, PolicyLimits

### Community 192 - "0001_01_01_000002_create_jobs_table.php"
Cohesion: 0.33
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

### Community 195 - "2026_11_09_000016_create_market_universe_tables.php"
Cohesion: 0.40
Nodes (3): {closure#1}(), {closure#2}(), up()

### Community 198 - "start-local.ps1"
Cohesion: 0.27
Nodes (4): Stop-Postgres(), Stop-ProcessTree(), Test-PostgresRunning(), Write-Step()

### Community 199 - "Illuminate\Database\Seeder"
Cohesion: 0.33
Nodes (3): DatabaseSeeder, {closure#1}(), E2eSeeder

### Community 200 - "ClientOrderId"
Cohesion: 0.08
Nodes (24): Balance, ClientOrderId, Income, IncomeKind, Funding, Liquidation, Liquidity, Maker (+16 more)

### Community 201 - "App\Agents\Mcp\Tools\ListTrials"
Cohesion: 0.40
Nodes (3): {closure#1}(), ListTrials, StrategyTrial

### Community 202 - "0001_01_01_000000_create_users_table.php"
Cohesion: 0.33
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

### Community 203 - "MarketDataSettingsTest.php"
Cohesion: 0.18
Nodes (5): {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), savedEmaCross()

### Community 204 - "WallClock"
Cohesion: 0.33
Nodes (3): WallClock, {closure#1}(), {closure#6}()

### Community 205 - "RulesHistory"
Cohesion: 0.32
Nodes (7): RulesHistory, {closure#4}(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), rules()

### Community 206 - "KlineMessageTest.php"
Cohesion: 0.47
Nodes (5): {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), streamLines()

### Community 208 - "Engine.php"
Cohesion: 0.08
Nodes (10): {closure#1}(), {closure#11}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#9}(), ReconciliationMismatch, OrderBookOutOfSync (+2 more)

### Community 210 - "streamWith"
Cohesion: 0.24
Nodes (11): {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#7}(), {closure#8}(), {closure#9}() (+3 more)

### Community 213 - "CompiledStrategy"
Cohesion: 0.10
Nodes (7): CompiledStrategy, {closure#57}(), {closure#58}(), {closure#59}(), {closure#60}(), CrossSection, {closure#19}()

### Community 214 - "App"
Cohesion: 0.05
Nodes (53): SendTelegram, App, {closure#1}(), {closure#2}(), {closure#3}(), {closure#3}(), {closure#1}(), {closure#2}() (+45 more)

### Community 222 - "StrategyGatesTest.php"
Cohesion: 0.60
Nodes (4): {closure#1}(), {closure#2}(), {closure#3}(), gatedVersion()

## Knowledge Gaps
- **152 isolated node(s):** `Backtest`, `Live`, `Paper`, `Testnet`, `Limit` (+147 more)
  These have ≤1 connection - possible missing edges. (Counts symbols only; 1005 node(s) total have ≤1 connection when file, concept and rationale nodes are included.)
- **62 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `Decimal` connect `Decimal` to `SimulatedPerpVenueTest.php`, `engineHarness`, `.of`, `Strategy`, `.of`, `.of`, `BinanceVenueTest.php`, `Candle`, `App\Market\Models\Instrument`, `Engine`, `DecimalMath`, `StrategyVersionsTest.php`, `Strategy`, `Ledger`, `App\Strategy\Dsl\Compiler\CompiledStrategy`, `Symbol`, `.measure`, `Percent`, `.of`, `App\Strategy\Data\StrategyLimits`, `App\Support\Symbol`, `OrderManagerTest.php`, `InstrumentRules`, `Position`, `.times`, `Timeframe`, `VenuePosition`, `Venue`, `.plus`, `UniverseStrategyTest.php`, `IndicatorsTest.php`, `IndicatorReferenceTest.php`, `Illuminate\Contracts\Config\Repository`, `.minus`, `.run`, `Kind`, `Price`, `CompiledStrategyTest.php`, `Statistics`, `ClientOrderId`, `ExitReason`, `.evaluate`, `EnginePerpTest.php`, `RulesHistory`, `Engine.php`, `.isPositive`, `ForwardingVenue`, `App\Support\Number\Decimal`, `CompiledStrategy`, `AccountRuntime.php`, `self`, `InvalidArgumentException`, `StrategyGates.php`?**
  _High betweenness centrality (0.129) - this node is a cross-community bridge._
- **What connects `Backtest`, `Live`, `Paper` to the rest of the system?**
  _152 weakly-connected nodes found - possible missing edges._
- **Should `engineHarness` be split into smaller, more focused modules?**
  _Cohesion score 0.10382513661202186 - nodes in this community are weakly interconnected._
- **Why does `Position` connect `Position` to `DslError`, `Lexer`, `Kind`, `DocumentChecker`, `CompiledStrategy.php`, `Parser`, `ReadBack`, `App\Strategy\Dsl\Compiler\CompiledStrategy`?**
  _High betweenness centrality (0.029) - this node is a cross-community bridge._
- **Should `.of` be split into smaller, more focused modules?**
  _Cohesion score 0.08392603129445235 - nodes in this community are weakly interconnected._
- **Why does `Timeframe` connect `Timeframe` to `engineHarness`, `Strategy`, `DocumentChecker`, `.of`, `Candle`, `CandleRepositoryTest.php`, `Instrument`, `App\Strategy\Dsl\Compiler\CompiledStrategy`, `Symbol`, `App\Strategy\Data\StrategyContext`, `App\Support\Symbol`, `OrderManagerTest.php`, `Position`, `Decimal`, `.plus`, `UniverseStrategyTest.php`, `ReadBack`, `IndicatorsTest.php`, `IndicatorReferenceTest.php`, `MarketType`, `CompiledStrategyTest.php`, `EnginePerpTest.php`, `Laravel\Mcp\Response`?**
  _High betweenness centrality (0.025) - this node is a cross-community bridge._
- **Should `PreparesBacktests.php` be split into smaller, more focused modules?**
  _Cohesion score 0.05822784810126582 - nodes in this community are weakly interconnected._