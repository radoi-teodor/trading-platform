# Graph Report - trading-platform  (2026-11-10)

## Corpus Check
- 657 files · ~281,596 words
- Verdict: corpus is large enough that graph structure adds value.
- Unclassified: 103 file(s) not represented in the graph (top: .zip 38, .CHECKSUM 38, (none) 19)

## Summary
- 5366 nodes · 18676 edges · 229 communities (166 shown, 63 thin omitted)
- Extraction: 90% EXTRACTED · 10% INFERRED · 0% AMBIGUOUS · INFERRED: 1790 edges (avg confidence: 0.85)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `1f22130c`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- SimulatedPerpVenueTest.php
- engineHarness
- .encode
- PreparesBacktests.php
- EngineRecord
- Strategy
- DocumentChecker
- .now
- CompiledStrategy.php
- SimulatedVenueTest.php
- BinanceVenueTest.php
- .of
- Candle
- ExchangeVerificationTest.php
- .act
- Illuminate\Contracts\Config\Repository
- Strategy
- Clock
- Instrument
- ScreensTest.php
- LedgerTest.php
- Illuminate\Database\Schema\Blueprint
- generate.py
- .parse
- Symbol
- ScalpingFunctionsTest.php
- TerminalQrCodeTest.php
- .of
- EngineUniverseTest.php
- Universe
- DepthStreamTest.php
- oi-momentum-long.strategy.yaml
- App\Strategy\Data\StrategyContext
- EnginePerpTest.php
- Window
- App\Livewire\StrategiesScreen
- ScalpingCollectorTest.php
- LadderTest.php
- OrderManagerTest.php
- InstrumentRules
- Position
- ema-cross-scalp-long.strategy.yaml
- Decimal
- .times
- App\Simulation\Data\SetOutcome
- Timeframe
- Illuminate\Database\Eloquent\Model
- package.json
- BookDepth
- BinanceRestClientTest.php
- ema-cross-scalp-short.strategy.yaml
- ChartScreen.php
- App\Strategy\Dsl\Compiler\CompiledStrategy
- RunEngine.php
- postgres
- UniverseStrategy
- App\Risk\RiskPolicy
- donchian-breakout-short.strategy.yaml
- IndicatorsTest.php
- support.mjs
- backtestFixture
- IndicatorReferenceTest.php
- App\Support\Clock\Clock
- .plus
- Illuminate\Http\Request
- donchian-breakout.strategy.yaml
- .run
- .expect
- Kind
- MarketBreakersTest.php
- App\Support\Symbol
- Statistics
- App\Execution\Journal\DatabaseDecisionLog
- scripts
- ma-trend-short.strategy.yaml
- .evaluate
- .measure
- composer.json
- jobs
- postgres
- ma-trend.strategy.yaml
- .isPositive
- MonteCarlo
- App\Support\Number\Decimal
- pint/composer.json
- HealthChecks
- RunSimulation.php
- App\Market\Instruments\Universe
- require-dev
- scripts-descriptions
- Carbon\CarbonImmutable
- .fromStrings
- config
- uses
- test
- with
- .isGreaterThan
- jobs
- Node
- psr-4
- require
- AccountRuntime.php
- StrategyGates.php
- uses
- .handle
- devDependencies
- Laravel\Mcp\Response
- ExitReason
- BinanceArchive
- .breaches
- buy-and-hold.strategy.yaml
- TradingTool
- TokensTest.php
- ArchiveImportTest.php
- App\Http\Controllers\ReauthController
- Illuminate\Support\Facades\Schema
- RuntimeException
- .register
- funding-carry.strategy.yaml
- McpServerTest.php
- env.mjs
- chart.js
- TelegramSettings
- CompiledStrategyTest.php
- settings-screen.blade.php
- Asset
- steps
- 2026_11_09_000014_create_health_tables.php
- .of
- App\Market\Models\Instrument
- StrategyLibrary
- CandleRepositoryTest.php
- Ledger
- .now
- OrderBookTest.php
- scripts
- 2026_11_08_000007_create_strategy_gate_tables.php
- fake-telegram.mjs
- 2026_11_08_000003_create_strategy_trials_table.php
- ResearchUniverse
- 2026_11_08_000004_create_strategy_versions_tables.php
- 2026_11_08_000006_create_mcp_tables.php
- 2026_11_09_000017_create_live_tables.php
- .account
- strategies-screen.blade.php
- extra
- app.blade.php
- halt
- remove
- account-screen.blade.php
- Illuminate\Database\Migrations\Migration
- SimulationQueue
- .evaluate
- Venue
- 2026_11_09_000027_add_to_to_market_jobs.php
- UniverseStrategyTest.php
- 2026_11_09_000012_create_account_results_tables.php
- 2026_11_09_000023_create_universe_members_table.php
- SettingsScreenTest.php
- DailyHistory
- App\Strategy\Dsl\DslError
- UniverseBacktestTest.php
- ResearchUniverse.php
- MarketType
- PolicyLimits
- 2026_11_09_000025_create_book_depth_table.php
- 0001_01_01_000002_create_jobs_table.php
- 2026_11_09_000009_create_exchange_keys_table.php
- 2026_11_09_000018_add_effective_at_to_deployments.php
- 2026_11_09_000016_create_market_universe_tables.php
- .account
- App\Agents\Mcp\TradingServer
- start-local.ps1
- GridBacktest.php
- Symbol
- GlobalsTest.php
- 0001_01_01_000000_create_users_table.php
- SimulationJobsTest.php
- DailyReport
- InstrumentRules
- BarIndicator
- .handle
- Side
- UniverseSchedule
- streamWith
- 2026_11_09_000020_add_lifetime_to_instruments.php
- CrossSection
- App
- AppServiceProvider.php
- App\Market\Data\MinuteCandles
- GridWorkersTest.php
- 2026_11_09_000026_create_liquidation_tables.php
- 2026_11_09_000022_create_daily_stats_table.php
- QualityReport
- StrategyGatesTest.php
- SeedingTest.php
- 2026_11_09_000010_add_two_factor_columns_to_users_table.php
- 2026_11_09_000024_create_positioning_table.php

## God Nodes (most connected - your core abstractions)
1. `Decimal` - 488 edges
2. `Side` - 203 edges
3. `Symbol` - 186 edges
4. `App` - 175 edges
5. `Candle` - 124 edges
6. `Timeframe` - 107 edges
7. `Price` - 98 edges
8. `Position` - 89 edges
9. `SimClock` - 85 edges
10. `Clock` - 69 edges

## Surprising Connections (you probably didn't know these)
- `{closure#13}()` --calls--> `App`  [INFERRED]
  tests/Feature/Web/SettingsScreenTest.php → app/Notifications/SettingSource.php
- `{closure#8}()` --calls--> `App`  [INFERRED]
  tests/Feature/Web/SettingsScreenTest.php → app/Notifications/SettingSource.php
- `up()` --calls--> `App`  [INFERRED]
  database/migrations/2026_11_09_000016_create_market_universe_tables.php → app/Notifications/SettingSource.php
- `{closure#10}()` --calls--> `App`  [INFERRED]
  tests/Feature/Market/ScalpingArchiveTest.php → app/Notifications/SettingSource.php
- `{closure#13}()` --calls--> `App`  [INFERRED]
  tests/Feature/Market/ScalpingArchiveTest.php → app/Notifications/SettingSource.php

## Import Cycles
- None detected.

## Communities (229 total, 63 thin omitted)

### Community 0 - "SimulatedPerpVenueTest.php"
Cohesion: 0.18
Nodes (29): PriceMinute, {closure#2}(), {closure#8}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}() (+21 more)

### Community 1 - "engineHarness"
Cohesion: 0.12
Nodes (36): EngineCommand, ScriptedStrategy, {closure#1}(), {closure#10}(), {closure#13}(), {closure#14}(), {closure#16}(), {closure#17}() (+28 more)

### Community 2 - ".encode"
Cohesion: 0.30
Nodes (6): CanonicalJson, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}()

### Community 3 - "PreparesBacktests.php"
Cohesion: 0.05
Nodes (30): {closure#5}(), {closure#7}(), {closure#5}(), {closure#2}(), {closure#4}(), Liquidations, OpenInterest, {closure#2}() (+22 more)

### Community 5 - "Strategy"
Cohesion: 0.08
Nodes (43): {closure#12}(), {closure#17}(), {closure#7}(), {closure#9}(), {closure#3}(), {closure#5}(), {closure#6}(), carryDecisions() (+35 more)

### Community 6 - "DocumentChecker"
Cohesion: 0.11
Nodes (10): {closure#2}(), {closure#5}(), {closure#7}(), {closure#8}(), {closure#9}(), DocumentChecker, EntrySpec, ExitSpec (+2 more)

### Community 7 - ".now"
Cohesion: 0.17
Nodes (6): {closure#1}(), {closure#2}(), {closure#1}(), ReviewLadder, Ladder, LadderStatus

### Community 8 - "CompiledStrategy.php"
Cohesion: 0.08
Nodes (7): PerpetualTracker, CompiledStrategy, {closure#47}(), {closure#54}(), Compiler, {closure#1}(), Program

### Community 9 - "SimulatedVenueTest.php"
Cohesion: 0.18
Nodes (42): btc(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#15}(), {closure#16}() (+34 more)

### Community 10 - "BinanceVenueTest.php"
Cohesion: 0.11
Nodes (14): {closure#2}(), BinanceVenue, BinanceApiError, BinanceUnreachable, SignedBinanceClient, binanceVenue(), {closure#1}(), {closure#4}() (+6 more)

### Community 11 - ".of"
Cohesion: 0.12
Nodes (16): AssetMismatch, {closure#7}(), {closure#1}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#15}() (+8 more)

### Community 12 - "Candle"
Cohesion: 0.07
Nodes (43): {closure#1}(), {closure#3}(), Candle, InvalidCandle, {closure#1}(), {closure#2}(), VolumeProfile, {closure#42}() (+35 more)

### Community 13 - "ExchangeVerificationTest.php"
Cohesion: 0.11
Nodes (31): {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#2}(), {closure#3}(), {closure#4}() (+23 more)

### Community 15 - "Illuminate\Contracts\Config\Repository"
Cohesion: 0.10
Nodes (4): AuthenticateMcpToken, {closure#1}(), {closure#2}(), PreparesBacktests

### Community 16 - "Strategy"
Cohesion: 0.21
Nodes (16): Strategy, baselineDocument(), baselinePairs(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#14}() (+8 more)

### Community 17 - "Clock"
Cohesion: 0.06
Nodes (22): CreateMcpToken, RevokeMcpToken, Tokens, FlattenEngine, HaltEngine, IssuesCommands, PauseStrategy, ResumeEngine (+14 more)

### Community 18 - "Instrument"
Cohesion: 0.08
Nodes (10): BackfillStep, CandleRepository, CandleSource, Archive, Rest, Stream, UpsertResult, Instrument (+2 more)

### Community 19 - "ScreensTest.php"
Cohesion: 0.10
Nodes (28): approveLive(), {closure#9}(), {closure#1}(), {closure#2}(), {closure#3}(), fakeTestnet(), testnetAccount(), {closure#1}() (+20 more)

### Community 20 - "LedgerTest.php"
Cohesion: 0.22
Nodes (26): balanceOf(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#15}(), {closure#16}() (+18 more)

### Community 21 - "Illuminate\Database\Schema\Blueprint"
Cohesion: 0.14
Nodes (13): {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#1}(), {closure#2}(), {closure#3}() (+5 more)

### Community 22 - "generate.py"
Cohesion: 0.07
Nodes (30): atr(), bars(), bb(), change(), dmi(), donchian(), ema(), highest() (+22 more)

### Community 23 - ".parse"
Cohesion: 0.20
Nodes (16): AstPrinter, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), sexpr(), {closure#1}(), {closure#10}() (+8 more)

### Community 24 - "Symbol"
Cohesion: 0.05
Nodes (23): {closure#3}(), BuyAndHold, DonchianBreakout, FundingCarry, MovingAverageTrend, BuildableStrategy, Close, Intent (+15 more)

### Community 25 - "ScalpingFunctionsTest.php"
Cohesion: 0.08
Nodes (28): {closure#3}(), Positioning, {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#2}() (+20 more)

### Community 26 - "TerminalQrCodeTest.php"
Cohesion: 0.20
Nodes (7): TerminalQrCode, {closure#3}(), {closure#4}(), {closure#5}(), expectedModules(), readColoured(), readPlain()

### Community 27 - ".of"
Cohesion: 0.07
Nodes (74): AccountView, RejectionReason, BelowMinimum, Breaker, Cooldown, Delisting, DuplicateIntent, EntryWorking (+66 more)

### Community 28 - "EngineUniverseTest.php"
Cohesion: 0.24
Nodes (16): {closure#10}(), {closure#11}(), {closure#13}(), {closure#14}(), {closure#17}(), {closure#2}(), {closure#3}(), {closure#6}() (+8 more)

### Community 29 - "Universe"
Cohesion: 0.09
Nodes (8): CheckMarketData, SelectsInstruments, {closure#1}(), {closure#2}(), StreamCandles, VerifyMarketData, Universe, QualityCheck

### Community 30 - "DepthStreamTest.php"
Cohesion: 0.06
Nodes (14): Sleeper, SystemSleeper, {closure#1}(), StreamDepth, DepthStream, PhrityTransport, StreamFailed, StreamTransport (+6 more)

### Community 31 - "oi-momentum-long.strategy.yaml"
Cohesion: 0.07
Nodes (27): entries, long, exits, stop_loss, take_profit, when, fails_when, family (+19 more)

### Community 32 - "App\Strategy\Data\StrategyContext"
Cohesion: 0.13
Nodes (9): {closure#2}(), {closure#3}(), {closure#1}(), ScriptedUniverseStrategy, {closure#12}(), {closure#16}(), {closure#5}(), {closure#11}() (+1 more)

### Community 33 - "EnginePerpTest.php"
Cohesion: 0.22
Nodes (17): {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#2}(), {closure#5}() (+9 more)

### Community 34 - "Window"
Cohesion: 0.09
Nodes (6): DonchianChannel, Lowest, StandardDeviation, Stochastic, Vwma, Window

### Community 35 - "App\Livewire\StrategiesScreen"
Cohesion: 0.09
Nodes (8): Account, Deployment, JournalEntry, AccountScreen, HaltButton, {closure#1}(), ModeBadge, {closure#1}()

### Community 36 - "ScalpingCollectorTest.php"
Cohesion: 0.35
Nodes (10): aggTrade(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#6}(), collector(), defaultBinanceFakes() (+2 more)

### Community 37 - "LadderTest.php"
Cohesion: 0.10
Nodes (34): {closure#4}(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#5}(), {closure#6}(), {closure#7}(), reportAccount() (+26 more)

### Community 38 - "OrderManagerTest.php"
Cohesion: 0.09
Nodes (33): OrderAck, OrderRole, Entry, Exit, Flatten, Stop, Target, ProtectionPlan (+25 more)

### Community 39 - "InstrumentRules"
Cohesion: 0.07
Nodes (29): FeeSchedule, CostModel, CandleFeed, FundingRate, {closure#2}(), Backtest, {closure#1}(), {closure#2}() (+21 more)

### Community 40 - "Position"
Cohesion: 0.06
Nodes (24): {closure#1}(), Argument, BoolLiteral, Call, DurationLiteral, Identifier, NumberLiteral, OnTimeframe (+16 more)

### Community 41 - "ema-cross-scalp-long.strategy.yaml"
Cohesion: 0.06
Nodes (33): entries, long, exits, stop_loss, take_profit, time_stop, when, fails_when (+25 more)

### Community 42 - "Decimal"
Cohesion: 0.04
Nodes (49): {closure#6}(), RoundTrip, {closure#1}(), {closure#2}(), {closure#3}(), {closure#10}(), {closure#5}(), {closure#4}() (+41 more)

### Community 43 - ".times"
Cohesion: 0.08
Nodes (12): OrderStatus, Cancelled, Expired, Filled, New, PartiallyFilled, SimulatedFuturesBook, SimulatedOrder (+4 more)

### Community 44 - "App\Simulation\Data\SetOutcome"
Cohesion: 0.25
Nodes (5): {closure#1}(), {closure#2}(), {closure#4}(), {closure#6}(), {closure#7}()

### Community 45 - "Timeframe"
Cohesion: 0.05
Nodes (62): CandleAggregator, CandleSequenceBroken, {closure#3}(), {closure#4}(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}() (+54 more)

### Community 46 - "Illuminate\Database\Eloquent\Model"
Cohesion: 0.05
Nodes (31): {closure#1}(), McpCall, McpToken, AccountEquity, AccountSnapshot, AccountTrade, PlainDecimal, ExchangeKey (+23 more)

### Community 47 - "package.json"
Cohesion: 0.15
Nodes (17): dependencies, codemirror@^6.0.2, @codemirror/lang-yaml@^6.1.3, @codemirror/language@^6.13.1, @lezer/highlight@^1.2.5, lightweight-charts@^5.2.1, optionalDependencies, @laravel/multiplex@^0.4.1 (+9 more)

### Community 48 - "BookDepth"
Cohesion: 0.16
Nodes (5): {closure#9}(), BookDepth, OrderBook, {closure#2}(), {closure#4}()

### Community 49 - "BinanceRestClientTest.php"
Cohesion: 0.09
Nodes (31): {closure#3}(), {closure#6}(), {closure#7}(), {closure#1}(), {closure#3}(), binanceClient(), {closure#1}(), {closure#11}() (+23 more)

### Community 50 - "ema-cross-scalp-short.strategy.yaml"
Cohesion: 0.06
Nodes (33): entries, short, exits, stop_loss, take_profit, time_stop, when, fails_when (+25 more)

### Community 51 - "ChartScreen.php"
Cohesion: 0.08
Nodes (18): {closure#2}(), ChartScreen, {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#1}(), {closure#2}() (+10 more)

### Community 52 - "App\Strategy\Dsl\Compiler\CompiledStrategy"
Cohesion: 0.07
Nodes (14): Lookback, {closure#2}(), {closure#3}(), {closure#6}(), {closure#7}(), {closure#8}(), {closure#9}(), Rankings (+6 more)

### Community 53 - "RunEngine.php"
Cohesion: 0.11
Nodes (6): {closure#4}(), {closure#5}(), Notifier, EngineCommandRow, TelegramNotifier, {closure#18}()

### Community 54 - "postgres"
Cohesion: 0.10
Nodes (19): POSTGRES_DB, POSTGRES_PASSWORD, POSTGRES_USER, interval, retries, test, timeout, environment (+11 more)

### Community 56 - "App\Risk\RiskPolicy"
Cohesion: 0.16
Nodes (3): SizedStrategy, SizedUniverseStrategy, {closure#1}()

### Community 57 - "donchian-breakout-short.strategy.yaml"
Cohesion: 0.07
Nodes (26): entries, short, default, range, step, default, range, step (+18 more)

### Community 58 - "IndicatorsTest.php"
Cohesion: 0.08
Nodes (24): {closure#67}(), Atr, Donchian, Ema, Period, Sma, {closure#5}(), bar() (+16 more)

### Community 59 - "support.mjs"
Cohesion: 0.26
Nodes (9): @playwright/test, confirmed(), checkLayout(), EMAIL, freshCode(), PASSWORD, reauthenticate(), totp() (+1 more)

### Community 60 - "backtestFixture"
Cohesion: 0.17
Nodes (24): {closure#1}(), {closure#4}(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}() (+16 more)

### Community 61 - "IndicatorReferenceTest.php"
Cohesion: 0.06
Nodes (23): {closure#48}(), Bollinger, Change, Highest, Hma, Macd, RealizedVolatility, Roc (+15 more)

### Community 62 - "App\Support\Clock\Clock"
Cohesion: 0.07
Nodes (9): {closure#1}(), EpochTime, {closure#2}(), LiquidationRepository, CandleStream, LiquidationCollector, ScalpingCollector, HealthAlerts (+1 more)

### Community 63 - ".plus"
Cohesion: 0.11
Nodes (5): PerpPosition, Position, {closure#2}(), {closure#8}(), {closure#16}()

### Community 64 - "Illuminate\Http\Request"
Cohesion: 0.09
Nodes (9): ChartLiveController, AppServiceProvider, {closure#8}(), {closure#3}(), {closure#4}(), FortifyServiceProvider, {closure#1}(), {closure#2}() (+1 more)

### Community 65 - "donchian-breakout.strategy.yaml"
Cohesion: 0.07
Nodes (26): entries, long, default, range, step, default, range, step (+18 more)

### Community 66 - ".run"
Cohesion: 0.06
Nodes (36): RiskProfile, Account, Benchmark, RiskPolicy, PerformanceMetrics, TradeStatistics, Report, {closure#1}() (+28 more)

### Community 67 - ".expect"
Cohesion: 0.18
Nodes (21): {closure#2}(), {closure#4}(), {closure#5}(), {closure#6}(), scalpingBackfill(), checkDocument(), {closure#10}(), {closure#11}() (+13 more)

### Community 68 - "Kind"
Cohesion: 0.06
Nodes (38): {closure#1}(), {closure#3}(), {closure#4}(), ParameterSpec, DslWriter, FunctionSpec, Group, Exit (+30 more)

### Community 69 - "MarketBreakersTest.php"
Cohesion: 0.23
Nodes (8): {closure#4}(), MinuteCandles, BreakerEvent, MarketBreakers, breakerMinute(), {closure#1}(), {closure#2}(), {closure#3}()

### Community 70 - "App\Support\Symbol"
Cohesion: 0.04
Nodes (38): {closure#11}(), InstrumentDefinition, InstrumentStatus, Delisted, Halted, Trading, {closure#1}(), InstrumentSync (+30 more)

### Community 71 - "Statistics"
Cohesion: 0.27
Nodes (10): Statistics, {closure#3}(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}() (+2 more)

### Community 72 - "App\Execution\Journal\DatabaseDecisionLog"
Cohesion: 0.15
Nodes (5): DecisionLog, IntentRecord, {closure#1}(), DatabaseDecisionLog, InMemoryDecisionLog

### Community 73 - "scripts"
Cohesion: 0.15
Nodes (13): scripts, analyse, dev, format, mutate, post-autoload-dump, post-update-cmd, pre-package-uninstall (+5 more)

### Community 74 - "ma-trend-short.strategy.yaml"
Cohesion: 0.07
Nodes (26): entries, short, exits, stop_loss, when, fails_when, family, default (+18 more)

### Community 75 - ".evaluate"
Cohesion: 0.29
Nodes (7): WalkForward, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), wfSet()

### Community 76 - ".measure"
Cohesion: 0.16
Nodes (10): ParticipantResult, EquityTracker, Performance, {closure#1}(), {closure#2}(), {closure#3}(), {closure#5}(), {closure#6}() (+2 more)

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
Cohesion: 0.08
Nodes (24): Cscv, DeflatedSharpe, {closure#40}(), {closure#44}(), {closure#45}(), {closure#46}(), {closure#47}(), {closure#48}() (+16 more)

### Community 82 - "MonteCarlo"
Cohesion: 0.30
Nodes (9): MonteCarlo, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), mcDecimals() (+1 more)

### Community 83 - "App\Support\Number\Decimal"
Cohesion: 0.06
Nodes (62): TradeFlow, PerpetualView, {closure#5}(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}() (+54 more)

### Community 84 - "pint/composer.json"
Cohesion: 0.20
Nodes (9): config, sort-packages, description, license, name, require, laravel/pint, php (+1 more)

### Community 85 - "HealthChecks"
Cohesion: 0.15
Nodes (5): DiskUsage, {closure#1}(), HealthChecks, HealthResult, FakeDisk

### Community 86 - "RunSimulation.php"
Cohesion: 0.09
Nodes (7): HistoryImports, ImportMarketData, MarketJob, {closure#3}(), RunSimulation, SimulationJob, {closure#8}()

### Community 87 - "App\Market\Instruments\Universe"
Cohesion: 0.13
Nodes (6): ArchivePeriod, Backfill, BackfillReport, BackfillMarketData, {closure#1}(), {closure#2}()

### Community 88 - "require-dev"
Cohesion: 0.22
Nodes (9): require-dev, fakerphp/faker, larastan/larastan, laravel/pail, laravel/pao, mockery/mockery, nunomaduro/collision, pestphp/pest (+1 more)

### Community 89 - "scripts-descriptions"
Cohesion: 0.25
Nodes (8): scripts-descriptions, analyse, format, mutate, test, test:network, test:testnet, tools:install

### Community 90 - "Carbon\CarbonImmutable"
Cohesion: 0.10
Nodes (13): FormingCandle, RiskEvent, RiskEventKind, DailyLossHalt, DrawdownFlatten, DrawdownHalt, WeeklyLossHalt, ChartHistoryMissing (+5 more)

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

### Community 97 - "jobs"
Cohesion: 0.25
Nodes (8): name, runs-on, name, runs-on, steps, jobs, analyse, audit

### Community 98 - "Node"
Cohesion: 0.10
Nodes (31): Binary, Node, Unary, Lexer, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}() (+23 more)

### Community 99 - "psr-4"
Cohesion: 0.40
Nodes (5): autoload, psr-4, App\\, Database\\Factories\\, Database\\Seeders\\

### Community 100 - "require"
Cohesion: 0.22
Nodes (9): require, bacon/bacon-qr-code, laravel/fortify, laravel/framework, laravel/mcp, laravel/tinker, livewire/livewire, php (+1 more)

### Community 102 - "AccountRuntime.php"
Cohesion: 0.07
Nodes (11): {closure#1}(), {closure#2}(), Balance, {closure#2}(), {closure#3}(), {closure#1}(), {closure#2}(), Quantity (+3 more)

### Community 103 - "StrategyGates.php"
Cohesion: 0.11
Nodes (12): {closure#3}(), {closure#8}(), {closure#2}(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}() (+4 more)

### Community 104 - "uses"
Cohesion: 0.21
Nodes (12): steps, name, runs-on, steps, frontend, lint, name, runs-on (+4 more)

### Community 105 - ".handle"
Cohesion: 0.26
Nodes (3): StrategyGates, StrategySpec, {closure#16}()

### Community 106 - "devDependencies"
Cohesion: 0.18
Nodes (12): devDependencies, concurrently@^10.0.3, laravel-vite-plugin@^3.1, @playwright/test@1.63.0, tailwindcss@^4.0.0, @tailwindcss/vite@^4.0.0, vite@^8.0.0, concurrently (+4 more)

### Community 108 - "Laravel\Mcp\Response"
Cohesion: 0.17
Nodes (5): ChartTool, GetCandles, GetIndicators, ListTrials, ChartSeries

### Community 117 - "ExitReason"
Cohesion: 0.19
Nodes (24): ExitReason, Flatten, Liquidation, Signal, Stop, Target, Fill, {closure#1}() (+16 more)

### Community 118 - "BinanceArchive"
Cohesion: 0.11
Nodes (8): BinanceArchive, {closure#1}(), {closure#2}(), {closure#3}(), ExchangeNumber, TradeFlowBuilder, {closure#1}(), {closure#2}()

### Community 119 - ".breaches"
Cohesion: 0.24
Nodes (10): Band, bandReport(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#7}() (+2 more)

### Community 120 - "buy-and-hold.strategy.yaml"
Cohesion: 0.12
Nodes (16): entries, long, exits, stop_loss, fails_when, family, hypothesis, when (+8 more)

### Community 121 - "TradingTool"
Cohesion: 0.13
Nodes (5): GetJob, GetStrategy, ListStrategies, SaveStrategy, TradingTool

### Community 122 - "TokensTest.php"
Cohesion: 0.31
Nodes (6): {closure#3}(), {closure#4}(), contrastRatio(), contrastRules(), relativeLuminance(), themeTokens()

### Community 123 - "ArchiveImportTest.php"
Cohesion: 0.29
Nodes (10): archiveBytes(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}() (+2 more)

### Community 124 - "App\Http\Controllers\ReauthController"
Cohesion: 0.08
Nodes (6): ApproveLive, ConfirmsOperator, CreateAccount, ReauthController, User, UserFactory

### Community 125 - "Illuminate\Support\Facades\Schema"
Cohesion: 0.14
Nodes (4): {closure#1}(), {closure#1}(), {closure#1}(), {closure#2}()

### Community 126 - "RuntimeException"
Cohesion: 0.12
Nodes (10): ReplayDivergence, ArchiveMissing, ChecksumMismatch, BinanceRequestFailed, LocationRestricted, RateLimited, OrderBookOutOfSync, VerificationSource (+2 more)

### Community 127 - ".register"
Cohesion: 0.07
Nodes (39): SimClock, {closure#1}(), {closure#10}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#15}(), {closure#16}() (+31 more)

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
Cohesion: 0.17
Nodes (6): NotificationSettings, SettingSource, Env, None, TelegramClient, TelegramSettings

### Community 133 - "CompiledStrategyTest.php"
Cohesion: 0.17
Nodes (12): LimitedStrategy, {closure#1}(), {closure#11}(), {closure#13}(), {closure#16}(), {closure#17}(), {closure#2}(), {closure#3}() (+4 more)

### Community 135 - "settings-screen.blade.php"
Cohesion: 0.15
Nodes (12): approveLive, confirm, forgetKey, forgetTelegram, importUniverse, monitor, notify, removeInstrument (+4 more)

### Community 137 - "Asset"
Cohesion: 0.18
Nodes (4): Marks, Asset, {closure#25}(), {closure#9}()

### Community 138 - "steps"
Cohesion: 0.33
Nodes (6): name, runs-on, services, steps, e2e, if

### Community 139 - "2026_11_09_000014_create_health_tables.php"
Cohesion: 0.33
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

### Community 140 - ".of"
Cohesion: 0.09
Nodes (34): DecimalMath, {closure#9}(), balances(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}() (+26 more)

### Community 141 - "App\Market\Models\Instrument"
Cohesion: 0.10
Nodes (17): {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#15}(), {closure#2}() (+9 more)

### Community 142 - "StrategyLibrary"
Cohesion: 0.23
Nodes (4): CompileStrategy, StrategyLibrary, CheckResult, SaveResult

### Community 143 - "CandleRepositoryTest.php"
Cohesion: 0.31
Nodes (11): candleStore(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}() (+3 more)

### Community 144 - "Ledger"
Cohesion: 0.26
Nodes (5): Ledger, LedgerViolation, {closure#19}(), {closure#12}(), {closure#14}()

### Community 145 - ".now"
Cohesion: 0.06
Nodes (16): AccountRuntime, LiveGate, OpenAccount, RunEngine, StoreKeys, EngineMode, Backtest, Live (+8 more)

### Community 146 - "OrderBookTest.php"
Cohesion: 0.38
Nodes (7): DepthUpdate, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), levels(), update()

### Community 147 - "scripts"
Cohesion: 0.50
Nodes (4): scripts, build, dev, e2e

### Community 155 - "2026_11_09_000017_create_live_tables.php"
Cohesion: 0.33
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

### Community 156 - ".account"
Cohesion: 0.18
Nodes (13): Policies, {closure#1}(), {closure#2}(), __construct(), enterOnce(), identity(), limits(), onCandle() (+5 more)

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
Cohesion: 0.18
Nodes (4): RunBacktest, RunGrid, SimulationTool, SimulationQueue

### Community 175 - ".evaluate"
Cohesion: 0.24
Nodes (9): Gates, brokenGate(), {closure#1}(), {closure#3}(), {closure#4}(), {closure#6}(), {closure#7}(), gate() (+1 more)

### Community 176 - "Venue"
Cohesion: 0.07
Nodes (8): Venue, WarmingVenue, ForwardingVenue, {closure#22}(), {closure#32}(), {closure#34}(), {closure#49}(), OverReportingVenue

### Community 178 - "UniverseStrategyTest.php"
Cohesion: 0.25
Nodes (16): CrossSectionalStrategy, {closure#10}(), {closure#12}(), {closure#15}(), {closure#18}(), {closure#3}(), {closure#4}(), {closure#5}() (+8 more)

### Community 181 - "SettingsScreenTest.php"
Cohesion: 0.18
Nodes (13): {closure#10}(), {closure#11}(), {closure#13}(), {closure#14}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}() (+5 more)

### Community 182 - "DailyHistory"
Cohesion: 0.26
Nodes (3): FillDailyStats, DailyStats, DailyHistory

### Community 183 - "App\Strategy\Dsl\DslError"
Cohesion: 0.21
Nodes (13): {closure#2}(), {closure#1}(), {closure#1}(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}() (+5 more)

### Community 184 - "UniverseBacktestTest.php"
Cohesion: 0.21
Nodes (6): {closure#1}(), {closure#4}(), {closure#5}(), reportRows(), universeBacktest(), universeFixture()

### Community 189 - "MarketType"
Cohesion: 0.09
Nodes (19): BinanceRestClient, {closure#2}(), {closure#3}(), {closure#4}(), Json, RequestFailed, MarketType, Perp (+11 more)

### Community 190 - "PolicyLimits"
Cohesion: 0.07
Nodes (20): {closure#1}(), {closure#3}(), StrategiesScreen, InstrumentDirectory, StoredInstruments, {closure#1}(), CompileStrategy, GridPart (+12 more)

### Community 192 - "0001_01_01_000002_create_jobs_table.php"
Cohesion: 0.33
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

### Community 195 - "2026_11_09_000016_create_market_universe_tables.php"
Cohesion: 0.40
Nodes (3): {closure#1}(), {closure#2}(), up()

### Community 196 - ".account"
Cohesion: 0.19
Nodes (3): {closure#1}(), {closure#2}(), ReconciliationMismatch

### Community 197 - "App\Agents\Mcp\TradingServer"
Cohesion: 0.16
Nodes (4): DslReference, GetFunding, ListInstruments, TradingServer

### Community 198 - "start-local.ps1"
Cohesion: 0.27
Nodes (4): Stop-Postgres(), Stop-ProcessTree(), Test-PostgresRunning(), Write-Step()

### Community 199 - "GridBacktest.php"
Cohesion: 0.20
Nodes (6): {closure#11}(), {closure#12}(), {closure#2}(), {closure#4}(), {closure#6}(), WalkForwardWindow

### Community 200 - "Symbol"
Cohesion: 0.07
Nodes (28): ClientOrderId, Income, IncomeKind, Funding, Liquidation, Liquidity, Maker, Taker (+20 more)

### Community 202 - "0001_01_01_000000_create_users_table.php"
Cohesion: 0.33
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

### Community 203 - "SimulationJobsTest.php"
Cohesion: 0.21
Nodes (5): {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), savedEmaCross()

### Community 205 - "InstrumentRules"
Cohesion: 0.24
Nodes (12): InstrumentRules, {closure#1}(), RulesHistory, {closure#2}(), {closure#4}(), {closure#41}(), {closure#1}(), {closure#2}() (+4 more)

### Community 206 - "BarIndicator"
Cohesion: 0.11
Nodes (6): {closure#50}(), BarIndicator, Keltner, Natr, Obv, Supertrend

### Community 208 - "Side"
Cohesion: 0.05
Nodes (16): VenuePosition, {closure#3}(), {closure#9}(), {closure#2}(), {closure#1}(), SimulatedPosition, MoveStop, PositionView (+8 more)

### Community 209 - "UniverseSchedule"
Cohesion: 0.31
Nodes (3): UniverseSchedule, {closure#17}(), {closure#1}()

### Community 210 - "streamWith"
Cohesion: 0.24
Nodes (11): {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#7}(), {closure#8}(), {closure#9}() (+3 more)

### Community 213 - "CrossSection"
Cohesion: 0.23
Nodes (5): {closure#57}(), {closure#58}(), {closure#59}(), CrossSection, {closure#19}()

### Community 214 - "App"
Cohesion: 0.06
Nodes (41): SendTelegram, App, {closure#1}(), {closure#2}(), {closure#3}(), {closure#2}(), {closure#3}(), {closure#4}() (+33 more)

### Community 215 - "AppServiceProvider.php"
Cohesion: 0.08
Nodes (9): ArchiveCatalog, BinanceEndpoints, {closure#1}(), SyncInstruments, LocalDiskUsage, {closure#4}(), {closure#5}(), {closure#6}() (+1 more)

### Community 216 - "App\Market\Data\MinuteCandles"
Cohesion: 0.20
Nodes (3): {closure#15}(), {closure#4}(), {closure#8}()

### Community 222 - "StrategyGatesTest.php"
Cohesion: 0.60
Nodes (4): {closure#1}(), {closure#2}(), {closure#3}(), gatedVersion()

## Knowledge Gaps
- **152 isolated node(s):** `DailyArchive`, `Rest`, `Env`, `None`, `approveLive` (+147 more)
  These have ≤1 connection - possible missing edges. (Counts symbols only; 1000 node(s) total have ≤1 connection when file, concept and rationale nodes are included.)
- **63 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `Decimal` connect `Decimal` to `SimulatedPerpVenueTest.php`, `engineHarness`, `.encode`, `PreparesBacktests.php`, `Strategy`, `DocumentChecker`, `CompiledStrategyTest.php`, `SimulatedVenueTest.php`, `BinanceVenueTest.php`, `Asset`, `Candle`, `.of`, `.of`, `Ledger`, `Strategy`, `LedgerTest.php`, `Symbol`, `.of`, `EnginePerpTest.php`, `Window`, `OrderManagerTest.php`, `InstrumentRules`, `Position`, `.times`, `App\Simulation\Data\SetOutcome`, `Timeframe`, `.evaluate`, `Venue`, `App\Strategy\Dsl\Compiler\CompiledStrategy`, `App\Risk\RiskPolicy`, `IndicatorsTest.php`, `IndicatorReferenceTest.php`, `PolicyLimits`, `.plus`, `.run`, `.account`, `Kind`, `App\Support\Symbol`, `GridBacktest.php`, `Symbol`, `Statistics`, `.evaluate`, `.measure`, `InstrumentRules`, `BarIndicator`, `Side`, `.isPositive`, `MonteCarlo`, `App\Support\Number\Decimal`, `Carbon\CarbonImmutable`, `App\Strategy\Data\StrategyLimits`, `.fromStrings`, `.isGreaterThan`, `Node`, `AccountRuntime.php`, `StrategyGates.php`, `.handle`, `ExitReason`?**
  _High betweenness centrality (0.118) - this node is a cross-community bridge._
- **What connects `DailyArchive`, `Rest`, `Env` to the rest of the system?**
  _152 weakly-connected nodes found - possible missing edges._
- **Should `engineHarness` be split into smaller, more focused modules?**
  _Cohesion score 0.11883116883116883 - nodes in this community are weakly interconnected._
- **Why does `Symbol` connect `Symbol` to `SimulatedPerpVenueTest.php`, `engineHarness`, `.encode`, `PreparesBacktests.php`, `Strategy`, `DocumentChecker`, `CompiledStrategyTest.php`, `SimulatedVenueTest.php`, `BinanceVenueTest.php`, `Asset`, `Candle`, `ExchangeVerificationTest.php`, `.act`, `CandleRepositoryTest.php`, `Ledger`, `Strategy`, `LedgerTest.php`, `Symbol`, `.of`, `EngineUniverseTest.php`, `App\Strategy\Data\StrategyContext`, `EnginePerpTest.php`, `OrderManagerTest.php`, `InstrumentRules`, `Decimal`, `.times`, `Timeframe`, `Venue`, `PolicyLimits`, `.plus`, `.run`, `.account`, `App\Support\Symbol`, `.measure`, `InstrumentRules`, `Side`, `.isGreaterThan`, `AccountRuntime.php`, `StrategyGates.php`, `.handle`, `Laravel\Mcp\Response`, `ExitReason`?**
  _High betweenness centrality (0.034) - this node is a cross-community bridge._
- **Should `PreparesBacktests.php` be split into smaller, more focused modules?**
  _Cohesion score 0.04757603131586871 - nodes in this community are weakly interconnected._
- **Why does `App` connect `App` to `McpServerTest.php`, `TelegramSettings`, `BinanceVenueTest.php`, `App\Market\Models\Instrument`, `ExchangeVerificationTest.php`, `CandleRepositoryTest.php`, `.now`, `Clock`, `ScreensTest.php`, `ScalpingFunctionsTest.php`, `DepthStreamTest.php`, `ScalpingCollectorTest.php`, `LadderTest.php`, `Timeframe`, `Illuminate\Database\Eloquent\Model`, `BinanceRestClientTest.php`, `SettingsScreenTest.php`, `UniverseBacktestTest.php`, `backtestFixture`, `PolicyLimits`, `.run`, `2026_11_09_000016_create_market_universe_tables.php`, `.expect`, `App\Support\Symbol`, `SimulationJobsTest.php`, `streamWith`, `StrategyGatesTest.php`, `ArchiveImportTest.php`, `.register`?**
  _High betweenness centrality (0.018) - this node is a cross-community bridge._
- **Should `Strategy` be split into smaller, more focused modules?**
  _Cohesion score 0.08448540706605223 - nodes in this community are weakly interconnected._