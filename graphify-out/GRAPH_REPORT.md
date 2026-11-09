# Graph Report - trading-platform  (2026-11-09)

## Corpus Check
- 544 files · ~221,441 words
- Verdict: corpus is large enough that graph structure adds value.
- Unclassified: 92 file(s) not represented in the graph (top: .zip 34, .CHECKSUM 34, (none) 18)

## Summary
- 4310 nodes · 14761 edges · 174 communities (128 shown, 46 thin omitted)
- Extraction: 92% EXTRACTED · 8% INFERRED · 0% AMBIGUOUS · INFERRED: 1154 edges (avg confidence: 0.85)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `6a3cdac8`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- .of
- EngineTest.php
- Clock
- RejectionReason
- Fill
- .parse
- DocumentChecker
- InstrumentRules
- CompiledStrategy.php
- SimulatedVenueTest.php
- Instrument
- SimulatedPerpVenueTest.php
- Candle
- ExchangeVerificationTest.php
- IndicatorsTest.php
- RiskPolicy
- Node
- PolicyLimits
- CompiledStrategy.php
- TestnetEngineTest.php
- LedgerTest.php
- Illuminate\Database\Schema\Blueprint
- generate.py
- ReadBack
- DonchianBreakout
- GoldenReportsTest.php
- FundingCarry
- Symbol
- .measure
- Strategy
- PhrityTransport
- StrategyIdentity
- .zero
- InvalidArgumentException
- RoundTrip
- ExitReason
- StrategyGates.php
- SimClock
- .act
- FundingRate
- Position
- ema-cross-scalp-long.strategy.yaml
- Decimal
- SimulatedVenue
- OrderRole
- Engine.php
- Illuminate\Database\Eloquent\Model
- package.json
- ForwardingVenue
- BinanceRestClientTest.php
- ema-cross-scalp-short.strategy.yaml
- TrackedOrder
- .of
- Laravel\Mcp\Response
- postgres
- InstrumentRules
- OrderManagerTest.php
- donchian-breakout-short.strategy.yaml
- IndicatorReferenceTest.php
- support.mjs
- RunBacktestCommandTest.php
- TelegramTest.php
- InvalidArgumentException
- RunEngine.php
- donchian-breakout.strategy.yaml
- .run
- CandleRepositoryTest.php
- App\Strategy\Dsl\Document\DocumentChecker
- Asset
- Engine
- SettingsScreenTest.php
- ArchiveImportTest.php
- scripts
- ma-trend-short.strategy.yaml
- DslError
- .evaluate
- composer.json
- jobs
- postgres
- ma-trend.strategy.yaml
- DecimalMath
- CompiledStrategyTest.php
- App\Support\Number\Decimal
- pint/composer.json
- MonteCarlo
- RunSimulation.php
- Timeframe
- require-dev
- scripts-descriptions
- .sqrt
- .fromStrings
- config
- uses
- test
- with
- PreparesBacktests.php
- jobs
- Ledger
- psr-4
- require
- .benchmark
- .evaluate
- uses
- GoldenData.php
- devDependencies
- ClientOrderId
- WarmingVenue
- .parse
- RiskEventKind
- buy-and-hold.strategy.yaml
- ChartSeriesTest.php
- TokensTest.php
- GridBacktest.php
- AppServiceProvider.php
- Illuminate\Support\Facades\Schema
- Illuminate\Database\Migrations\Migration
- .assertSameAsset
- funding-carry.strategy.yaml
- McpServerTest.php
- env.mjs
- chart.js
- TelegramSettings
- Participant
- settings-screen.blade.php
- 0001_01_01_000000_create_users_table.php
- steps
- .encode
- Carbon\CarbonImmutable
- MarketType
- .handle
- RegimeLabels
- BackfillTest.php
- .of
- OpenLong
- scripts
- 0001_01_01_000002_create_jobs_table.php
- fake-telegram.mjs
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
- CompiledStrategy
- DocumentCheckerTest.php
- 2026_11_09_000011_create_notification_settings_table.php

## God Nodes (most connected - your core abstractions)
1. `Decimal` - 599 edges
2. `Symbol` - 257 edges
3. `Side` - 215 edges
4. `Candle` - 170 edges
5. `Timeframe` - 155 edges
6. `Clock` - 124 edges
7. `Price` - 116 edges
8. `Position` - 96 edges
9. `MarketType` - 90 edges
10. `App` - 84 edges

## Surprising Connections (you probably didn't know these)
- `{closure#3}()` --calls--> `App`  [INFERRED]
  tests/Feature/Network/BinancePublicDataTest.php → app/Notifications/SettingSource.php
- `{closure#5}()` --calls--> `App`  [INFERRED]
  tests/Feature/Network/BinancePublicDataTest.php → app/Notifications/SettingSource.php
- `{closure#4}()` --calls--> `App`  [INFERRED]
  tests/Feature/Simulation/SimulationJobsTest.php → app/Notifications/SettingSource.php
- `{closure#1}()` --calls--> `App`  [INFERRED]
  tests/Feature/Support/ClockBindingTest.php → app/Notifications/SettingSource.php
- `{closure#8}()` --calls--> `App`  [INFERRED]
  tests/Feature/Web/SettingsScreenTest.php → app/Notifications/SettingSource.php

## Import Cycles
- None detected.

## Communities (174 total, 46 thin omitted)

### Community 0 - ".of"
Cohesion: 0.26
Nodes (36): account(), accountPolicy(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#2}(), {closure#21}() (+28 more)

### Community 1 - "EngineTest.php"
Cohesion: 0.07
Nodes (57): EngineMode, Backtest, Live, Paper, Testnet, InMemoryDecisionLog, Close, ScriptedStrategy (+49 more)

### Community 2 - "Clock"
Cohesion: 0.06
Nodes (22): CreateMcpToken, RevokeMcpToken, McpToken, Tokens, FlattenEngine, HaltEngine, IssuesCommands, PauseStrategy (+14 more)

### Community 3 - "RejectionReason"
Cohesion: 0.07
Nodes (26): RejectionReason, BelowMinimum, Breaker, Cooldown, DuplicateIntent, EntryWorking, GrossExposure, Halted (+18 more)

### Community 4 - "Fill"
Cohesion: 0.09
Nodes (8): Fill, PerpPosition, Position, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), Perpetual

### Community 5 - ".parse"
Cohesion: 0.11
Nodes (37): {closure#1}(), baselinePairs(), {closure#17}(), {closure#4}(), {closure#5}(), carryDecisions(), {closure#10}(), {closure#11}() (+29 more)

### Community 7 - "InstrumentRules"
Cohesion: 0.20
Nodes (6): InstrumentRules, AccountView, Verdict, RiskEngine, OpenCarry, StrategyLimits

### Community 8 - "CompiledStrategy.php"
Cohesion: 0.14
Nodes (4): {closure#33}(), {closure#34}(), Compiler, Program

### Community 9 - "SimulatedVenueTest.php"
Cohesion: 0.17
Nodes (45): Side, Buy, Sell, btc(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}() (+37 more)

### Community 10 - "Instrument"
Cohesion: 0.04
Nodes (24): ArchivePeriod, Backfill, BackfillReport, BackfillStep, CandleRepository, {closure#2}(), {closure#4}(), BackfillMarketData (+16 more)

### Community 11 - "SimulatedPerpVenueTest.php"
Cohesion: 0.21
Nodes (24): {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#15}(), {closure#18}(), {closure#19}() (+16 more)

### Community 12 - "Candle"
Cohesion: 0.08
Nodes (36): {closure#1}(), {closure#2}(), {closure#1}(), {closure#3}(), Candle, InvalidCandle, {closure#9}(), {closure#1}() (+28 more)

### Community 13 - "ExchangeVerificationTest.php"
Cohesion: 0.12
Nodes (27): {closure#1}(), {closure#12}(), {closure#13}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}() (+19 more)

### Community 14 - "IndicatorsTest.php"
Cohesion: 0.08
Nodes (21): Donchian, Ema, Period, Sma, Wilder, {closure#5}(), bar(), {closure#1}() (+13 more)

### Community 15 - "RiskPolicy"
Cohesion: 0.11
Nodes (13): RiskProfile, Account, Benchmark, {closure#1}(), RiskPolicy, {closure#1}(), {closure#2}(), {closure#3}() (+5 more)

### Community 16 - "Node"
Cohesion: 0.10
Nodes (30): Binary, Node, Unary, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}() (+22 more)

### Community 17 - "PolicyLimits"
Cohesion: 0.09
Nodes (13): CompileStrategy, {closure#1}(), {closure#2}(), StrategiesScreen, InstrumentDirectory, CompileStrategy, {closure#2}(), StrategyLibrary (+5 more)

### Community 18 - "CompiledStrategy.php"
Cohesion: 0.08
Nodes (19): {closure#5}(), {closure#6}(), CarryTemplate, {closure#7}(), EntrySpec, ExitSpec, Expression, StrategySpec (+11 more)

### Community 19 - "TestnetEngineTest.php"
Cohesion: 0.15
Nodes (10): {closure#1}(), {closure#2}(), {closure#3}(), fakeTestnet(), testnetAccount(), {closure#3}(), {closure#4}(), {closure#5}() (+2 more)

### Community 20 - "LedgerTest.php"
Cohesion: 0.21
Nodes (26): balanceOf(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#15}(), {closure#16}() (+18 more)

### Community 21 - "Illuminate\Database\Schema\Blueprint"
Cohesion: 0.19
Nodes (10): {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#1}(), {closure#2}(), {closure#3}() (+2 more)

### Community 22 - "generate.py"
Cohesion: 0.08
Nodes (30): atr(), bars(), bb(), change(), dmi(), donchian(), ema(), highest() (+22 more)

### Community 24 - "DonchianBreakout"
Cohesion: 0.06
Nodes (8): BuyAndHold, DonchianBreakout, MovingAverageTrend, BuildableStrategy, Subscription, EnterOnceStrategy, HoldStrategy, {closure#1}()

### Community 25 - "GoldenReportsTest.php"
Cohesion: 0.13
Nodes (11): PerformanceMetrics, TradeStatistics, Report, {closure#21}(), {closure#1}(), {closure#3}(), {closure#6}(), {closure#8}() (+3 more)

### Community 27 - "Symbol"
Cohesion: 0.04
Nodes (19): IncomeKind, Funding, Liquidation, VenuePosition, {closure#12}(), {closure#2}(), {closure#2}(), {closure#3}() (+11 more)

### Community 28 - ".measure"
Cohesion: 0.17
Nodes (9): EquityTracker, Performance, {closure#1}(), {closure#2}(), {closure#3}(), {closure#5}(), {closure#6}(), {closure#8}() (+1 more)

### Community 29 - "Strategy"
Cohesion: 0.14
Nodes (20): Strategy, baselineDocument(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#15}() (+12 more)

### Community 30 - "PhrityTransport"
Cohesion: 0.12
Nodes (3): PhrityTransport, StreamFailed, FakeTransport

### Community 31 - "StrategyIdentity"
Cohesion: 0.18
Nodes (5): StrategyIdentity, RenamedStrategy, {closure#1}(), {closure#2}(), {closure#3}()

### Community 32 - ".zero"
Cohesion: 0.11
Nodes (20): Cscv, DeflatedSharpe, {closure#3}(), closeTo(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}() (+12 more)

### Community 33 - "InvalidArgumentException"
Cohesion: 0.06
Nodes (13): OpenAccount, CreateAccount, StoreKeys, Account, AccountScreen, HaltButton, {closure#1}(), ModeBadge (+5 more)

### Community 34 - "RoundTrip"
Cohesion: 0.13
Nodes (15): RoundTrip, {closure#1}(), {closure#2}(), {closure#4}(), {closure#6}(), {closure#7}(), {closure#10}(), {closure#2}() (+7 more)

### Community 35 - "ExitReason"
Cohesion: 0.21
Nodes (23): ExitReason, Flatten, Liquidation, Signal, Stop, Target, {closure#1}(), {closure#10}() (+15 more)

### Community 36 - "StrategyGates.php"
Cohesion: 0.11
Nodes (11): {closure#3}(), {closure#8}(), {closure#2}(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}() (+3 more)

### Community 37 - "SimClock"
Cohesion: 0.10
Nodes (21): SimClock, WallClock, {closure#1}(), {closure#1}(), {closure#2}(), {closure#3}(), definition(), {closure#1}() (+13 more)

### Community 38 - ".act"
Cohesion: 0.15
Nodes (5): DecisionLog, DecisionRecord, IntentRecord, {closure#1}(), DatabaseDecisionLog

### Community 39 - "FundingRate"
Cohesion: 0.09
Nodes (23): FeeSchedule, CostModel, {closure#3}(), FundingRate, Backtest, {closure#1}(), RunDefinition, TradeMemory (+15 more)

### Community 40 - "Position"
Cohesion: 0.05
Nodes (29): {closure#1}(), {closure#1}(), Argument, BoolLiteral, DurationLiteral, Identifier, Lookback, NumberLiteral (+21 more)

### Community 41 - "ema-cross-scalp-long.strategy.yaml"
Cohesion: 0.06
Nodes (33): entries, long, exits, stop_loss, take_profit, time_stop, when, fails_when (+25 more)

### Community 42 - "Decimal"
Cohesion: 0.05
Nodes (32): {closure#6}(), {closure#2}(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#10}(), {closure#4}(), {closure#5}() (+24 more)

### Community 43 - "SimulatedVenue"
Cohesion: 0.07
Nodes (21): Income, Liquidity, Maker, Taker, OrderStatus, Cancelled, Expired, Filled (+13 more)

### Community 44 - "OrderRole"
Cohesion: 0.14
Nodes (9): OrderRole, Entry, Exit, Flatten, Stop, Target, ProtectionPlan, OrderManager (+1 more)

### Community 45 - "Engine.php"
Cohesion: 0.11
Nodes (7): EngineCommand, {closure#1}(), {closure#11}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#9}()

### Community 46 - "Illuminate\Database\Eloquent\Model"
Cohesion: 0.06
Nodes (22): {closure#1}(), McpCall, AccountSnapshot, Deployment, ExchangeKey, JournalEntry, {closure#1}(), InstrumentFilter (+14 more)

### Community 47 - "package.json"
Cohesion: 0.15
Nodes (17): dependencies, codemirror@^6.0.2, @codemirror/lang-yaml@^6.1.3, @codemirror/language@^6.13.1, @lezer/highlight@^1.2.5, lightweight-charts@^5.2.1, optionalDependencies, @laravel/multiplex@^0.4.1 (+9 more)

### Community 49 - "BinanceRestClientTest.php"
Cohesion: 0.09
Nodes (31): {closure#1}(), {closure#3}(), {closure#4}(), {closure#6}(), queryOf(), {closure#13}(), binanceClient(), {closure#1}() (+23 more)

### Community 50 - "ema-cross-scalp-short.strategy.yaml"
Cohesion: 0.06
Nodes (33): entries, short, exits, stop_loss, take_profit, time_stop, when, fails_when (+25 more)

### Community 51 - "TrackedOrder"
Cohesion: 0.25
Nodes (9): TrackedOrder, {closure#1}(), {closure#2}(), OrderBook, {closure#1}(), {closure#11}(), {closure#12}(), {closure#13}() (+1 more)

### Community 52 - ".of"
Cohesion: 0.12
Nodes (23): {closure#2}(), {closure#8}(), {closure#17}(), {closure#5}(), {closure#24}(), {closure#1}(), {closure#10}(), {closure#11}() (+15 more)

### Community 53 - "Laravel\Mcp\Response"
Cohesion: 0.06
Nodes (18): ChartTool, DslReference, GetCandles, GetFunding, GetIndicators, GetJob, GetStrategy, ListInstruments (+10 more)

### Community 54 - "postgres"
Cohesion: 0.10
Nodes (19): POSTGRES_DB, POSTGRES_PASSWORD, POSTGRES_USER, interval, retries, test, timeout, environment (+11 more)

### Community 55 - "InstrumentRules"
Cohesion: 0.23
Nodes (9): {closure#1}(), RulesHistory, {closure#4}(), {closure#41}(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}() (+1 more)

### Community 56 - "OrderManagerTest.php"
Cohesion: 0.30
Nodes (14): btcUsdt(), {closure#10}(), {closure#15}(), {closure#16}(), {closure#2}(), {closure#4}(), {closure#5}(), {closure#6}() (+6 more)

### Community 57 - "donchian-breakout-short.strategy.yaml"
Cohesion: 0.07
Nodes (26): entries, short, default, range, step, default, range, step (+18 more)

### Community 58 - "IndicatorReferenceTest.php"
Cohesion: 0.05
Nodes (36): {closure#36}(), Atr, BarIndicator, Bollinger, Change, DonchianChannel, Highest, Hma (+28 more)

### Community 59 - "support.mjs"
Cohesion: 0.27
Nodes (9): @playwright/test, confirmed(), checkLayout(), EMAIL, freshCode(), PASSWORD, reauthenticate(), totp() (+1 more)

### Community 60 - "RunBacktestCommandTest.php"
Cohesion: 0.09
Nodes (28): BacktestRun, {closure#4}(), {closure#6}(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}() (+20 more)

### Community 61 - "TelegramTest.php"
Cohesion: 0.09
Nodes (26): SendTelegram, App, {closure#1}(), {closure#2}(), {closure#3}(), {closure#1}(), {closure#2}(), {closure#4}() (+18 more)

### Community 62 - "InvalidArgumentException"
Cohesion: 0.05
Nodes (20): Notifier, BinanceArchive, ChecksumMismatch, SystemSleeper, CheckMarketData, SelectsInstruments, {closure#1}(), StreamCandles (+12 more)

### Community 64 - "RunEngine.php"
Cohesion: 0.07
Nodes (11): AccountRuntime, {closure#5}(), RunEngine, ReplayDivergence, EngineCommandRow, ArchiveMissing, BinanceApiError, BinanceRequestFailed (+3 more)

### Community 65 - "donchian-breakout.strategy.yaml"
Cohesion: 0.07
Nodes (26): entries, long, default, range, step, default, range, step (+18 more)

### Community 66 - ".run"
Cohesion: 0.19
Nodes (12): btcMinutes(), {closure#1}(), {closure#2}(), referencePrice(), {closure#1}(), {closure#3}(), goldenMinutes(), goldenRates() (+4 more)

### Community 67 - "CandleRepositoryTest.php"
Cohesion: 0.28
Nodes (11): candleStore(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}() (+3 more)

### Community 68 - "App\Strategy\Dsl\Document\DocumentChecker"
Cohesion: 0.04
Nodes (60): {closure#2}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#1}(), {closure#2}(), {closure#3}() (+52 more)

### Community 69 - "Asset"
Cohesion: 0.06
Nodes (12): {closure#1}(), {closure#2}(), Balance, Asset, Money, Quantity, balances(), {closure#22}() (+4 more)

### Community 70 - "Engine"
Cohesion: 0.14
Nodes (5): {closure#4}(), EngineRecord, Engine, ReconciliationMismatch, Intent

### Community 71 - "SettingsScreenTest.php"
Cohesion: 0.05
Nodes (26): ReauthController, ChartScreen, User, UserFactory, {closure#1}(), {closure#1}(), {closure#1}(), {closure#2}() (+18 more)

### Community 72 - "ArchiveImportTest.php"
Cohesion: 0.29
Nodes (10): archiveBytes(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}() (+2 more)

### Community 73 - "scripts"
Cohesion: 0.15
Nodes (13): scripts, analyse, dev, format, mutate, post-autoload-dump, post-update-cmd, pre-package-uninstall (+5 more)

### Community 74 - "ma-trend-short.strategy.yaml"
Cohesion: 0.07
Nodes (26): entries, short, exits, stop_loss, when, fails_when, family, default (+18 more)

### Community 75 - "DslError"
Cohesion: 0.19
Nodes (7): AstPrinter, Lexer, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), sexpr()

### Community 76 - ".evaluate"
Cohesion: 0.24
Nodes (9): Gates, brokenGate(), {closure#1}(), {closure#3}(), {closure#4}(), {closure#6}(), {closure#7}(), gate() (+1 more)

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
Nodes (14): DecimalMath, {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#2}() (+6 more)

### Community 82 - "CompiledStrategyTest.php"
Cohesion: 0.11
Nodes (21): LimitedStrategy, PositionView, StrategyContext, {closure#18}(), {closure#37}(), aggregatedBars(), {closure#1}(), {closure#11}() (+13 more)

### Community 83 - "App\Support\Number\Decimal"
Cohesion: 0.08
Nodes (40): {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#15}(), {closure#16}() (+32 more)

### Community 84 - "pint/composer.json"
Cohesion: 0.20
Nodes (9): config, sort-packages, description, license, name, require, laravel/pint, php (+1 more)

### Community 85 - "MonteCarlo"
Cohesion: 0.14
Nodes (10): {closure#4}(), MonteCarlo, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}() (+2 more)

### Community 86 - "RunSimulation.php"
Cohesion: 0.09
Nodes (7): RunSimulation, SimulationJob, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), savedEmaCross()

### Community 87 - "Timeframe"
Cohesion: 0.07
Nodes (49): CandleAggregator, CandleSequenceBroken, {closure#1}(), {closure#2}(), {closure#3}(), {closure#3}(), {closure#4}(), {closure#2}() (+41 more)

### Community 88 - "require-dev"
Cohesion: 0.22
Nodes (9): require-dev, fakerphp/faker, larastan/larastan, laravel/pail, laravel/pao, mockery/mockery, nunomaduro/collision, pestphp/pest (+1 more)

### Community 89 - "scripts-descriptions"
Cohesion: 0.25
Nodes (8): scripts-descriptions, analyse, format, mutate, test, test:network, test:testnet, tools:install

### Community 90 - ".sqrt"
Cohesion: 0.23
Nodes (11): Statistics, {closure#1}(), {closure#3}(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}() (+3 more)

### Community 91 - ".fromStrings"
Cohesion: 0.16
Nodes (7): DirectionalMovement, {closure#1}(), {closure#2}(), {closure#4}(), {closure#7}(), {closure#9}(), days()

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

### Community 96 - "PreparesBacktests.php"
Cohesion: 0.06
Nodes (10): AuthenticateMcpToken, QualityCheck, GridBacktest, GridPart, {closure#3}(), {closure#5}(), {closure#7}(), {closure#8}() (+2 more)

### Community 97 - "jobs"
Cohesion: 0.25
Nodes (8): name, runs-on, name, runs-on, steps, jobs, analyse, audit

### Community 98 - "Ledger"
Cohesion: 0.27
Nodes (5): Ledger, LedgerViolation, {closure#19}(), {closure#12}(), {closure#14}()

### Community 99 - "psr-4"
Cohesion: 0.40
Nodes (5): autoload, psr-4, App\\, Database\\Factories\\, Database\\Seeders\\

### Community 100 - "require"
Cohesion: 0.25
Nodes (8): require, laravel/fortify, laravel/framework, laravel/mcp, laravel/tinker, livewire/livewire, php, phrity/websocket

### Community 102 - ".benchmark"
Cohesion: 0.24
Nodes (10): RiskState, {closure#3}(), {closure#13}(), {closure#15}(), {closure#17}(), {closure#18}(), {closure#22}(), {closure#23}() (+2 more)

### Community 103 - ".evaluate"
Cohesion: 0.29
Nodes (7): WalkForward, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), wfSet()

### Community 104 - "uses"
Cohesion: 0.21
Nodes (12): steps, name, runs-on, steps, frontend, lint, name, runs-on (+4 more)

### Community 105 - "GoldenData.php"
Cohesion: 0.23
Nodes (5): ReportBuilder, {closure#1}(), {closure#2}(), {closure#3}(), {closure#5}()

### Community 106 - "devDependencies"
Cohesion: 0.18
Nodes (12): devDependencies, concurrently@^10.0.3, laravel-vite-plugin@^3.1, @playwright/test@1.63.0, tailwindcss@^4.0.0, @tailwindcss/vite@^4.0.0, vite@^8.0.0, concurrently (+4 more)

### Community 108 - "ClientOrderId"
Cohesion: 0.07
Nodes (23): {closure#2}(), Venue, ClientOrderId, OcoRequest, OrderAck, OrderRequest, OrderType, Limit (+15 more)

### Community 118 - ".parse"
Cohesion: 0.33
Nodes (6): KlineMessage, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), streamLines()

### Community 119 - "RiskEventKind"
Cohesion: 0.24
Nodes (6): RiskEvent, RiskEventKind, DailyLossHalt, DrawdownFlatten, DrawdownHalt, WeeklyLossHalt

### Community 120 - "buy-and-hold.strategy.yaml"
Cohesion: 0.12
Nodes (16): entries, long, exits, stop_loss, fails_when, family, hypothesis, when (+8 more)

### Community 121 - "ChartSeriesTest.php"
Cohesion: 0.20
Nodes (10): VolumeProfile, chart(), {closure#10}(), {closure#11}(), {closure#2}(), {closure#3}(), {closure#6}(), {closure#7}() (+2 more)

### Community 122 - "TokensTest.php"
Cohesion: 0.31
Nodes (6): {closure#3}(), {closure#4}(), contrastRatio(), contrastRules(), relativeLuminance(), themeTokens()

### Community 123 - "GridBacktest.php"
Cohesion: 0.12
Nodes (11): {closure#11}(), {closure#12}(), {closure#2}(), {closure#6}(), {closure#1}(), {closure#5}(), {closure#6}(), {closure#7}() (+3 more)

### Community 124 - "AppServiceProvider.php"
Cohesion: 0.08
Nodes (8): AppServiceProvider, {closure#6}(), {closure#3}(), {closure#4}(), FortifyServiceProvider, {closure#1}(), {closure#2}(), {closure#3}()

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
Cohesion: 0.14
Nodes (6): NotificationSettings, SettingSource, Env, None, TelegramClient, TelegramSettings

### Community 133 - "Participant"
Cohesion: 0.18
Nodes (10): Marks, {closure#1}(), {closure#2}(), {closure#2}(), Participant, ParticipantResult, {closure#16}(), {closure#7}() (+2 more)

### Community 135 - "settings-screen.blade.php"
Cohesion: 0.29
Nodes (6): confirm, forgetKey, forgetTelegram, notify, revokeToken, testTelegram

### Community 137 - "0001_01_01_000000_create_users_table.php"
Cohesion: 0.33
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

### Community 138 - "steps"
Cohesion: 0.33
Nodes (6): name, runs-on, services, steps, e2e, if

### Community 139 - ".encode"
Cohesion: 0.30
Nodes (6): CanonicalJson, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}()

### Community 140 - "Carbon\CarbonImmutable"
Cohesion: 0.09
Nodes (12): MinuteCandles, QualityReport, BreakerEvent, MarketBreakers, BacktestResult, {closure#14}(), {closure#3}(), {closure#7}() (+4 more)

### Community 141 - "MarketType"
Cohesion: 0.04
Nodes (31): {closure#1}(), {closure#2}(), BinanceEndpoints, BinanceRestClient, {closure#1}(), EpochTime, ExchangeNumber, Json (+23 more)

### Community 144 - "BackfillTest.php"
Cohesion: 0.12
Nodes (16): {closure#11}(), {closure#14}(), {closure#15}(), {closure#16}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}() (+8 more)

### Community 145 - ".of"
Cohesion: 0.12
Nodes (19): Percent, {closure#2}(), {closure#1}(), usdt(), {closure#7}(), {closure#1}(), {closure#11}(), {closure#12}() (+11 more)

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

### Community 179 - "DocumentCheckerTest.php"
Cohesion: 0.30
Nodes (10): checkDocument(), {closure#1}(), {closure#2}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}(), {closure#8}() (+2 more)

## Knowledge Gaps
- **141 isolated node(s):** `Halt`, `Resume`, `Flatten`, `Pause`, `ResumeStrategy` (+136 more)
  These have ≤1 connection - possible missing edges. (Counts symbols only; 876 node(s) total have ≤1 connection when file, concept and rationale nodes are included.)
- **46 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `Decimal` connect `Decimal` to `.of`, `EngineTest.php`, `Clock`, `RejectionReason`, `Fill`, `Participant`, `DocumentChecker`, `InstrumentRules`, `CompiledStrategy.php`, `SimulatedVenueTest.php`, `Instrument`, `SimulatedPerpVenueTest.php`, `Candle`, `MarketType`, `Carbon\CarbonImmutable`, `RiskPolicy`, `.handle`, `PolicyLimits`, `CompiledStrategy.php`, `Node`, `LedgerTest.php`, `IndicatorsTest.php`, `.of`, `ReadBack`, `DonchianBreakout`, `GoldenReportsTest.php`, `FundingCarry`, `Symbol`, `.measure`, `Strategy`, `.parse`, `.zero`, `InvalidArgumentException`, `RoundTrip`, `ExitReason`, `StrategyGates.php`, `SimClock`, `FundingRate`, `Position`, `SimulatedVenue`, `OrderRole`, `Engine.php`, `CompiledStrategy`, `ForwardingVenue`, `TrackedOrder`, `.of`, `DocumentCheckerTest.php`, `InstrumentRules`, `OrderManagerTest.php`, `IndicatorReferenceTest.php`, `RunBacktestCommandTest.php`, `.encode`, `.isFull`, `.run`, `App\Strategy\Dsl\Document\DocumentChecker`, `Asset`, `Engine`, `.evaluate`, `DecimalMath`, `CompiledStrategyTest.php`, `App\Support\Number\Decimal`, `MonteCarlo`, `Timeframe`, `.sqrt`, `.fromStrings`, `PreparesBacktests.php`, `Ledger`, `.benchmark`, `.evaluate`, `GoldenData.php`, `ClientOrderId`, `WarmingVenue`, `RiskEventKind`, `ChartSeriesTest.php`, `GridBacktest.php`?**
  _High betweenness centrality (0.266) - this node is a cross-community bridge._
- **What connects `Halt`, `Resume`, `Flatten` to the rest of the system?**
  _141 weakly-connected nodes found - possible missing edges._
- **Should `EngineTest.php` be split into smaller, more focused modules?**
  _Cohesion score 0.06716604244694133 - nodes in this community are weakly interconnected._
- **Why does `Clock` connect `Clock` to `Instrument`, `MarketType`, `.handle`, `ExchangeVerificationTest.php`, `BackfillTest.php`, `PolicyLimits`, `TestnetEngineTest.php`, `InvalidArgumentException`, `StrategyGates.php`, `SimClock`, `.act`, `SimulatedVenue`, `Illuminate\Database\Eloquent\Model`, `BinanceRestClientTest.php`, `RunBacktestCommandTest.php`, `TelegramTest.php`, `InvalidArgumentException`, `RunEngine.php`, `CandleRepositoryTest.php`, `Asset`, `RunSimulation.php`, `Timeframe`, `PreparesBacktests.php`, `ClientOrderId`, `GridBacktest.php`?**
  _High betweenness centrality (0.054) - this node is a cross-community bridge._
- **Should `Clock` be split into smaller, more focused modules?**
  _Cohesion score 0.05516431924882629 - nodes in this community are weakly interconnected._
- **Why does `Symbol` connect `Symbol` to `.of`, `EngineTest.php`, `Clock`, `McpServerTest.php`, `Fill`, `Participant`, `DocumentChecker`, `.parse`, `CompiledStrategy.php`, `SimulatedVenueTest.php`, `Instrument`, `SimulatedPerpVenueTest.php`, `InstrumentRules`, `MarketType`, `.handle`, `ExchangeVerificationTest.php`, `BackfillTest.php`, `PolicyLimits`, `OpenLong`, `CompiledStrategy.php`, `LedgerTest.php`, `DonchianBreakout`, `GoldenReportsTest.php`, `FundingCarry`, `.measure`, `Strategy`, `.zero`, `InvalidArgumentException`, `RoundTrip`, `ExitReason`, `StrategyGates.php`, `SimClock`, `.act`, `FundingRate`, `Position`, `Decimal`, `SimulatedVenue`, `OrderRole`, `Engine.php`, `ForwardingVenue`, `BinanceRestClientTest.php`, `TrackedOrder`, `Laravel\Mcp\Response`, `InstrumentRules`, `OrderManagerTest.php`, `RunBacktestCommandTest.php`, `TelegramTest.php`, `InvalidArgumentException`, `.encode`, `RunEngine.php`, `.run`, `CandleRepositoryTest.php`, `App\Strategy\Dsl\Document\DocumentChecker`, `Asset`, `Engine`, `SettingsScreenTest.php`, `CompiledStrategyTest.php`, `Timeframe`, `PreparesBacktests.php`, `Ledger`, `GoldenData.php`, `ClientOrderId`, `WarmingVenue`, `ChartSeriesTest.php`?**
  _High betweenness centrality (0.052) - this node is a cross-community bridge._
- **Should `RejectionReason` be split into smaller, more focused modules?**
  _Cohesion score 0.07142857142857142 - nodes in this community are weakly interconnected._