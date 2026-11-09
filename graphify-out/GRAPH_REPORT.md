# Graph Report - trading-platform  (2026-11-09)

## Corpus Check
- 626 files · ~263,819 words
- Verdict: corpus is large enough that graph structure adds value.
- Unclassified: 103 file(s) not represented in the graph (top: .zip 38, .CHECKSUM 38, (none) 19)

## Summary
- 5036 nodes · 17354 edges · 203 communities (151 shown, 52 thin omitted)
- Extraction: 91% EXTRACTED · 9% INFERRED · 0% AMBIGUOUS · INFERRED: 1598 edges (avg confidence: 0.85)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `ca588e6a`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- App\Market\Data\MarketType
- engineHarness
- CanonicalJson
- DslError
- Engine
- .parse
- DocumentChecker
- .now
- CompiledStrategy.php
- .of
- BinanceVenueTest.php
- .of
- Candle
- ExchangeVerificationTest.php
- Quantity
- Illuminate\Contracts\Config\Repository
- Node
- Clock
- Instrument
- ScreensTest.php
- LedgerTest.php
- Illuminate\Database\Schema\Blueprint
- generate.py
- App\Strategy\Dsl\Compiler\CompiledStrategy
- Symbol
- TypeCheckerTest.php
- TerminalQrCodeTest.php
- ClientOrderId
- .of
- Ledger
- RuntimeException
- oi-momentum-long.strategy.yaml
- StrategyIdentity
- EnginePerpTest.php
- BinanceArchive
- LadderTest.php
- ScalpingCollectorTest.php
- RiskState
- OrderManagerTest.php
- BacktestTest.php
- App\Strategy\Dsl\Document\DocumentChecker
- ema-cross-scalp-long.strategy.yaml
- Decimal
- .zero
- App
- Timeframe
- PaperEngineTest.php
- package.json
- TradeFlowBuilder
- BinanceRestClientTest.php
- ema-cross-scalp-short.strategy.yaml
- App\Livewire\SettingsScreen
- ReadBack
- SettingsScreen
- postgres
- AccountRuntime.php
- SizedStrategyTest.php
- donchian-breakout-short.strategy.yaml
- .minus
- support.mjs
- RunBacktestCommandTest.php
- IndicatorReferenceTest.php
- App\Support\Clock\Clock
- Illuminate\Database\ConnectionInterface
- RunEngine.php
- donchian-breakout.strategy.yaml
- .run
- Strategy
- App\Strategy\Dsl\Document\DocumentChecker
- Symbol
- Illuminate\Http\Client\Factory
- Illuminate\Contracts\Bus\Dispatcher
- ExitReason
- scripts
- ma-trend-short.strategy.yaml
- EngineMode
- .measure
- composer.json
- jobs
- postgres
- ma-trend.strategy.yaml
- DecimalMath
- MonteCarlo
- App\Support\Number\Decimal
- pint/composer.json
- App\Agents\Mcp\TradingServer
- RunSimulation.php
- DocumentCheckerTest.php
- require-dev
- scripts-descriptions
- MinuteCandles
- .fromStrings
- config
- uses
- test
- with
- AppServiceProvider.php
- jobs
- App\Strategy\Dsl\Document\DocumentChecker
- psr-4
- require
- Illuminate\Http\Request
- StrategyGates.php
- uses
- RiskEventKind
- devDependencies
- Laravel\Mcp\Response
- App\Strategy\Dsl\TypeChecker
- Carbon\CarbonImmutable
- RulesHistoryTest.php
- buy-and-hold.strategy.yaml
- .times
- TokensTest.php
- ArchiveImportTest.php
- UserFactory.php
- Illuminate\Database\Migrations\Migration
- InvalidArgumentException
- .register
- funding-carry.strategy.yaml
- McpServerTest.php
- env.mjs
- chart.js
- TelegramSettings
- Percent
- settings-screen.blade.php
- .account
- steps
- 2026_11_09_000014_create_health_tables.php
- DecimalMath
- App\Market\Models\Instrument
- Statistics
- CandleRepositoryTest.php
- Illuminate\Support\Facades\Schema
- MarketDataSettingsTest.php
- App\Support\Symbol
- scripts
- 2026_11_08_000007_create_strategy_gate_tables.php
- fake-telegram.mjs
- 2026_11_08_000005_create_simulation_jobs_table.php
- 0001_01_01_000001_create_cache_table.php
- 2026_11_08_000004_create_strategy_versions_tables.php
- 2026_11_08_000006_create_mcp_tables.php
- 2026_11_09_000011_create_notification_settings_table.php
- 2026_11_09_000019_create_forming_candles_table.php
- strategies-screen.blade.php
- extra
- app.blade.php
- halt
- remove
- account-screen.blade.php
- GlobalsTest.php
- App\Simulation\StrategyLibrary
- .evaluate
- Asset
- 2026_11_09_000015_create_scalping_tables.php
- App\Simulation\Backtest
- 2026_11_09_000012_create_account_results_tables.php
- 2026_11_09_000023_create_universe_members_table.php
- SettingsScreenTest.php
- FundingCarry
- CompiledStrategy
- SimulationQueue
- 2026_11_09_000013_add_daily_report_to_notification_settings.php
- MarketType
- PolicyLimits
- 2026_11_09_000018_add_effective_at_to_deployments.php
- 2026_11_09_000016_create_market_universe_tables.php
- CandleStreamTest.php
- 2026_11_09_000017_create_live_tables.php
- start-local.ps1
- .plus
- 0001_01_01_000000_create_users_table.php
- RejectionReason
- App\Execution\Models\Account
- 2026_11_09_000020_add_lifetime_to_instruments.php

## God Nodes (most connected - your core abstractions)
1. `Decimal` - 502 edges
2. `Side` - 205 edges
3. `Symbol` - 191 edges
4. `App` - 162 edges
5. `Candle` - 134 edges
6. `Timeframe` - 119 edges
7. `Price` - 100 edges
8. `Position` - 96 edges
9. `SimClock` - 85 edges
10. `Clock` - 73 edges

## Surprising Connections (you probably didn't know these)
- `{closure#6}()` --calls--> `App`  [INFERRED]
  tests/Feature/Market/ScalpingArchiveTest.php → app/Notifications/SettingSource.php
- `{closure#8}()` --calls--> `App`  [INFERRED]
  tests/Feature/Market/ScalpingArchiveTest.php → app/Notifications/SettingSource.php
- `{closure#10}()` --calls--> `App`  [INFERRED]
  tests/Feature/Market/ScalpingArchiveTest.php → app/Notifications/SettingSource.php
- `{closure#13}()` --calls--> `App`  [INFERRED]
  tests/Feature/Market/ScalpingArchiveTest.php → app/Notifications/SettingSource.php
- `{closure#2}()` --calls--> `App`  [INFERRED]
  tests/Feature/Web/MarketDataSettingsTest.php → app/Notifications/SettingSource.php

## Import Cycles
- None detected.

## Communities (203 total, 52 thin omitted)

### Community 0 - "App\Market\Data\MarketType"
Cohesion: 0.11
Nodes (6): SelectsInstruments, {closure#2}(), StreamCandles, {closure#1}(), {closure#2}(), Universe

### Community 1 - "engineHarness"
Cohesion: 0.11
Nodes (37): EngineCommand, EntryOrder, ScriptedStrategy, {closure#1}(), {closure#10}(), {closure#13}(), {closure#14}(), {closure#16}() (+29 more)

### Community 2 - "CanonicalJson"
Cohesion: 0.30
Nodes (6): CanonicalJson, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}()

### Community 3 - "DslError"
Cohesion: 0.14
Nodes (8): YamlList, YamlReader, YamlScalar, YamlValue, {closure#1}(), {closure#2}(), {closure#4}(), plainYaml()

### Community 4 - "Engine"
Cohesion: 0.11
Nodes (8): DecisionLog, DecisionRecord, EngineRecord, IntentRecord, Engine, {closure#1}(), ReconciliationMismatch, PerpetualTracker

### Community 5 - ".parse"
Cohesion: 0.12
Nodes (34): {closure#17}(), carryDecisions(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#15}() (+26 more)

### Community 7 - ".now"
Cohesion: 0.16
Nodes (6): {closure#1}(), {closure#2}(), {closure#1}(), ReviewLadder, Ladder, LadderStatus

### Community 8 - "CompiledStrategy.php"
Cohesion: 0.13
Nodes (6): {closure#1}(), {closure#45}(), Compiler, Program, Atr, {closure#1}()

### Community 9 - ".of"
Cohesion: 0.05
Nodes (112): PriceMinute, Band, TradeMemory, Side, Buy, Sell, {closure#2}(), {closure#8}() (+104 more)

### Community 10 - "BinanceVenueTest.php"
Cohesion: 0.10
Nodes (14): {closure#2}(), BinanceVenue, BinanceApiError, BinanceUnreachable, SignedBinanceClient, binanceVenue(), {closure#1}(), {closure#4}() (+6 more)

### Community 11 - ".of"
Cohesion: 0.11
Nodes (19): AssetMismatch, Money, {closure#2}(), {closure#1}(), usdt(), {closure#11}(), {closure#7}(), {closure#1}() (+11 more)

### Community 12 - "Candle"
Cohesion: 0.05
Nodes (49): {closure#1}(), {closure#3}(), Candle, InvalidCandle, BarIndicator, DirectionalMovement, {closure#1}(), {closure#2}() (+41 more)

### Community 13 - "ExchangeVerificationTest.php"
Cohesion: 0.11
Nodes (29): {closure#1}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}() (+21 more)

### Community 14 - "Quantity"
Cohesion: 0.24
Nodes (3): Quantity, {closure#10}(), {closure#9}()

### Community 15 - "Illuminate\Contracts\Config\Repository"
Cohesion: 0.10
Nodes (6): {closure#1}(), ExportStrategies, GridBacktest, ImportStrategies, PreparesBacktests, PromoteStrategy

### Community 16 - "Node"
Cohesion: 0.11
Nodes (21): Binary, Lexer, Parser, Token, TokenKind, Colon, Comma, Dot (+13 more)

### Community 17 - "Clock"
Cohesion: 0.07
Nodes (17): AuthenticateMcpToken, FlattenEngine, HaltEngine, IssuesCommands, PauseStrategy, ResumeEngine, CommandKind, Flatten (+9 more)

### Community 18 - "Instrument"
Cohesion: 0.06
Nodes (17): BackfillStep, CandleRepository, VerifyMarketData, CandleSource, Archive, Rest, Stream, UpsertResult (+9 more)

### Community 19 - "ScreensTest.php"
Cohesion: 0.08
Nodes (27): User, {closure#1}(), approveLive(), {closure#9}(), {closure#1}(), chartLiveQuery(), {closure#10}(), {closure#11}() (+19 more)

### Community 20 - "LedgerTest.php"
Cohesion: 0.22
Nodes (25): balanceOf(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#15}(), {closure#16}() (+17 more)

### Community 21 - "Illuminate\Database\Schema\Blueprint"
Cohesion: 0.14
Nodes (13): {closure#1}(), {closure#2}(), {closure#3}(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}() (+5 more)

### Community 22 - "generate.py"
Cohesion: 0.07
Nodes (30): atr(), bars(), bb(), change(), dmi(), donchian(), ema(), highest() (+22 more)

### Community 23 - "App\Strategy\Dsl\Compiler\CompiledStrategy"
Cohesion: 0.09
Nodes (12): {closure#3}(), {closure#5}(), {closure#6}(), {closure#7}(), {closure#8}(), {closure#9}(), {closure#43}(), {closure#60}() (+4 more)

### Community 24 - "Symbol"
Cohesion: 0.04
Nodes (32): BuyAndHold, DonchianBreakout, MovingAverageTrend, BuildableStrategy, Close, Intent, OpenLong, OpenShort (+24 more)

### Community 25 - "TypeCheckerTest.php"
Cohesion: 0.15
Nodes (12): Group, Exit, Indicator, Order, Perpetual, Position, Price, Time (+4 more)

### Community 26 - "TerminalQrCodeTest.php"
Cohesion: 0.20
Nodes (7): TerminalQrCode, {closure#3}(), {closure#4}(), {closure#5}(), expectedModules(), readColoured(), readPlain()

### Community 27 - "ClientOrderId"
Cohesion: 0.07
Nodes (15): Venue, ClientOrderId, OcoRequest, OrderAck, OrderRequest, OrderType, Limit, Market (+7 more)

### Community 28 - ".of"
Cohesion: 0.26
Nodes (36): account(), accountPolicy(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#2}(), {closure#21}() (+28 more)

### Community 29 - "Ledger"
Cohesion: 0.26
Nodes (5): Ledger, LedgerViolation, {closure#19}(), {closure#12}(), {closure#14}()

### Community 31 - "oi-momentum-long.strategy.yaml"
Cohesion: 0.07
Nodes (27): entries, long, exits, stop_loss, take_profit, when, fails_when, family (+19 more)

### Community 32 - "StrategyIdentity"
Cohesion: 0.22
Nodes (5): StrategyIdentity, RenamedStrategy, {closure#1}(), {closure#2}(), {closure#3}()

### Community 33 - "EnginePerpTest.php"
Cohesion: 0.17
Nodes (17): OpenCarry, {closure#1}(), {closure#10}(), {closure#11}(), {closure#13}(), {closure#14}(), {closure#2}(), {closure#5}() (+9 more)

### Community 34 - "BinanceArchive"
Cohesion: 0.15
Nodes (3): ArchivePeriod, BinanceArchive, {closure#12}()

### Community 35 - "LadderTest.php"
Cohesion: 0.09
Nodes (36): UniverseEntry, {closure#4}(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}() (+28 more)

### Community 36 - "ScalpingCollectorTest.php"
Cohesion: 0.29
Nodes (11): aggTrade(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#6}(), collector(), defaultBinanceFakes() (+3 more)

### Community 37 - "RiskState"
Cohesion: 0.32
Nodes (7): RiskState, {closure#13}(), {closure#15}(), {closure#17}(), {closure#18}(), {closure#22}(), {closure#23}()

### Community 38 - "OrderManagerTest.php"
Cohesion: 0.11
Nodes (30): OrderRole, Entry, Exit, Flatten, Stop, Target, TrackedOrder, {closure#1}() (+22 more)

### Community 39 - "BacktestTest.php"
Cohesion: 0.11
Nodes (20): FeeSchedule, CostModel, CandleFeed, FundingRate, Backtest, {closure#1}(), RunDefinition, ReportBuilder (+12 more)

### Community 40 - "App\Strategy\Dsl\Document\DocumentChecker"
Cohesion: 0.09
Nodes (24): Argument, BoolLiteral, Call, DurationLiteral, Identifier, Lookback, Node, NumberLiteral (+16 more)

### Community 41 - "ema-cross-scalp-long.strategy.yaml"
Cohesion: 0.06
Nodes (33): entries, long, exits, stop_loss, take_profit, time_stop, when, fails_when (+25 more)

### Community 42 - "Decimal"
Cohesion: 0.04
Nodes (49): {closure#6}(), {closure#2}(), RoundTrip, {closure#1}(), {closure#2}(), {closure#3}(), {closure#10}(), {closure#5}() (+41 more)

### Community 43 - ".zero"
Cohesion: 0.08
Nodes (18): Income, Liquidity, Maker, Taker, OrderStatus, Cancelled, Expired, Filled (+10 more)

### Community 44 - "App"
Cohesion: 0.07
Nodes (36): SendTelegram, App, {closure#1}(), {closure#2}(), {closure#3}(), {closure#2}(), {closure#3}(), {closure#4}() (+28 more)

### Community 45 - "Timeframe"
Cohesion: 0.06
Nodes (48): CandleAggregator, CandleSequenceBroken, {closure#2}(), {closure#4}(), CarryTemplate, {closure#2}(), ExitSpec, StrategySpec (+40 more)

### Community 46 - "PaperEngineTest.php"
Cohesion: 0.07
Nodes (26): McpCall, AccountEquity, AccountSnapshot, AccountTrade, PlainDecimal, ExchangeKey, JournalEntry, {closure#1}() (+18 more)

### Community 47 - "package.json"
Cohesion: 0.15
Nodes (17): dependencies, codemirror@^6.0.2, @codemirror/lang-yaml@^6.1.3, @codemirror/language@^6.13.1, @lezer/highlight@^1.2.5, lightweight-charts@^5.2.1, optionalDependencies, @laravel/multiplex@^0.4.1 (+9 more)

### Community 49 - "BinanceRestClientTest.php"
Cohesion: 0.07
Nodes (34): {closure#3}(), {closure#6}(), {closure#7}(), {closure#1}(), {closure#3}(), {closure#12}(), {closure#22}(), {closure#5}() (+26 more)

### Community 50 - "ema-cross-scalp-short.strategy.yaml"
Cohesion: 0.06
Nodes (33): entries, short, exits, stop_loss, take_profit, time_stop, when, fails_when (+25 more)

### Community 51 - "App\Livewire\SettingsScreen"
Cohesion: 0.06
Nodes (16): Account, Deployment, ReauthController, AccountScreen, ChartScreen, {closure#2}(), {closure#3}(), {closure#4}() (+8 more)

### Community 53 - "SettingsScreen"
Cohesion: 0.09
Nodes (4): CreateMcpToken, McpToken, Tokens, SettingsScreen

### Community 54 - "postgres"
Cohesion: 0.10
Nodes (19): POSTGRES_DB, POSTGRES_PASSWORD, POSTGRES_USER, interval, retries, test, timeout, environment (+11 more)

### Community 55 - "AccountRuntime.php"
Cohesion: 0.10
Nodes (12): {closure#1}(), {closure#2}(), Balance, {closure#2}(), {closure#3}(), {closure#1}(), {closure#2}(), balances() (+4 more)

### Community 56 - "SizedStrategyTest.php"
Cohesion: 0.05
Nodes (23): {closure#1}(), SizedStrategy, {closure#1}(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#5}(), {closure#7}() (+15 more)

### Community 57 - "donchian-breakout-short.strategy.yaml"
Cohesion: 0.07
Nodes (26): entries, short, default, range, step, default, range, step (+18 more)

### Community 59 - "support.mjs"
Cohesion: 0.26
Nodes (9): @playwright/test, confirmed(), checkLayout(), EMAIL, freshCode(), PASSWORD, reauthenticate(), totp() (+1 more)

### Community 60 - "RunBacktestCommandTest.php"
Cohesion: 0.10
Nodes (29): BacktestRun, {closure#1}(), {closure#4}(), {closure#6}(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}() (+21 more)

### Community 61 - "IndicatorReferenceTest.php"
Cohesion: 0.03
Nodes (47): {closure#46}(), Bollinger, Change, DonchianChannel, Ema, Highest, Hma, Lowest (+39 more)

### Community 62 - "App\Support\Clock\Clock"
Cohesion: 0.04
Nodes (17): BuildUniverse, {closure#1}(), FormingCandles, Heartbeats, {closure#1}(), InstrumentSync, {closure#2}(), {closure#3}() (+9 more)

### Community 63 - "Illuminate\Database\ConnectionInterface"
Cohesion: 0.08
Nodes (14): {closure#2}(), {closure#4}(), {closure#2}(), DatabaseCandleFeed, {closure#1}(), Rows, QualityCheck, QualityReport (+6 more)

### Community 64 - "RunEngine.php"
Cohesion: 0.11
Nodes (10): {closure#4}(), {closure#5}(), Notifier, EngineCommandRow, TelegramNotifier, {closure#1}(), {closure#2}(), {closure#3}() (+2 more)

### Community 65 - "donchian-breakout.strategy.yaml"
Cohesion: 0.07
Nodes (26): entries, long, default, range, step, default, range, step (+18 more)

### Community 66 - ".run"
Cohesion: 0.07
Nodes (40): RiskProfile, Account, Benchmark, RiskPolicy, Participant, Report, {closure#1}(), {closure#2}() (+32 more)

### Community 67 - "Strategy"
Cohesion: 0.17
Nodes (19): Strategy, baselineDocument(), baselinePairs(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#14}() (+11 more)

### Community 68 - "App\Strategy\Dsl\Document\DocumentChecker"
Cohesion: 0.09
Nodes (23): {closure#3}(), {closure#4}(), ParameterSpec, DslWriter, FunctionSpec, Library, Scope, {closure#1}() (+15 more)

### Community 69 - "Symbol"
Cohesion: 0.05
Nodes (24): IncomeKind, Funding, Liquidation, ProtectionPlan, VenuePosition, {closure#1}(), {closure#11}(), {closure#12}() (+16 more)

### Community 70 - "Illuminate\Http\Client\Factory"
Cohesion: 0.11
Nodes (6): ArchiveCatalog, FillDailyStats, {closure#1}(), SyncInstruments, DailyStats, DailyHistory

### Community 71 - "Illuminate\Contracts\Bus\Dispatcher"
Cohesion: 0.16
Nodes (3): HistoryImports, SendDailyReport, DailyReport

### Community 72 - "ExitReason"
Cohesion: 0.20
Nodes (22): ExitReason, Flatten, Liquidation, Signal, Stop, Target, {closure#1}(), {closure#10}() (+14 more)

### Community 73 - "scripts"
Cohesion: 0.15
Nodes (13): scripts, analyse, dev, format, mutate, post-autoload-dump, post-update-cmd, pre-package-uninstall (+5 more)

### Community 74 - "ma-trend-short.strategy.yaml"
Cohesion: 0.07
Nodes (26): entries, short, exits, stop_loss, when, fails_when, family, default (+18 more)

### Community 75 - "EngineMode"
Cohesion: 0.20
Nodes (6): EngineMode, Backtest, Live, Paper, Testnet, StrategyGateReport

### Community 76 - ".measure"
Cohesion: 0.15
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
Cohesion: 0.12
Nodes (18): Cscv, DeflatedSharpe, closeTo(), {closure#1}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}() (+10 more)

### Community 82 - "MonteCarlo"
Cohesion: 0.16
Nodes (10): {closure#4}(), MonteCarlo, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}() (+2 more)

### Community 83 - "App\Support\Number\Decimal"
Cohesion: 0.07
Nodes (52): {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#15}(), {closure#16}() (+44 more)

### Community 84 - "pint/composer.json"
Cohesion: 0.20
Nodes (9): config, sort-packages, description, license, name, require, laravel/pint, php (+1 more)

### Community 85 - "App\Agents\Mcp\TradingServer"
Cohesion: 0.17
Nodes (4): DslReference, GetStrategy, ListInstruments, TradingServer

### Community 86 - "RunSimulation.php"
Cohesion: 0.11
Nodes (5): ImportMarketData, MarketJob, {closure#3}(), RunSimulation, SimulationJob

### Community 87 - "DocumentCheckerTest.php"
Cohesion: 0.30
Nodes (10): checkDocument(), {closure#1}(), {closure#2}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}(), {closure#8}() (+2 more)

### Community 88 - "require-dev"
Cohesion: 0.22
Nodes (9): require-dev, fakerphp/faker, larastan/larastan, laravel/pail, laravel/pao, mockery/mockery, nunomaduro/collision, pestphp/pest (+1 more)

### Community 89 - "scripts-descriptions"
Cohesion: 0.25
Nodes (8): scripts-descriptions, analyse, format, mutate, test, test:network, test:testnet, tools:install

### Community 90 - "MinuteCandles"
Cohesion: 0.09
Nodes (10): AccountRuntime, {closure#4}(), WarmingVenue, MinuteCandles, BreakerEvent, MarketBreakers, breakerMinute(), {closure#1}() (+2 more)

### Community 91 - ".fromStrings"
Cohesion: 0.18
Nodes (7): RegimeLabels, {closure#1}(), {closure#2}(), {closure#4}(), {closure#7}(), {closure#9}(), days()

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
Nodes (11): SystemSleeper, DiskUsage, {closure#1}(), HealthChecks, HealthResult, LocalDiskUsage, {closure#4}(), {closure#5}() (+3 more)

### Community 97 - "jobs"
Cohesion: 0.25
Nodes (8): name, runs-on, name, runs-on, steps, jobs, analyse, audit

### Community 98 - "App\Strategy\Dsl\Document\DocumentChecker"
Cohesion: 0.10
Nodes (16): {closure#1}(), AstPrinter, {closure#1}(), {closure#5}(), {closure#7}(), {closure#8}(), {closure#9}(), EntrySpec (+8 more)

### Community 99 - "psr-4"
Cohesion: 0.40
Nodes (5): autoload, psr-4, App\\, Database\\Factories\\, Database\\Seeders\\

### Community 100 - "require"
Cohesion: 0.22
Nodes (9): require, bacon/bacon-qr-code, laravel/fortify, laravel/framework, laravel/mcp, laravel/tinker, livewire/livewire, php (+1 more)

### Community 102 - "Illuminate\Http\Request"
Cohesion: 0.09
Nodes (9): ChartLiveController, AppServiceProvider, {closure#8}(), {closure#3}(), {closure#4}(), FortifyServiceProvider, {closure#1}(), {closure#2}() (+1 more)

### Community 103 - "StrategyGates.php"
Cohesion: 0.05
Nodes (33): {closure#11}(), {closure#12}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#6}(), {closure#8}(), {closure#2}() (+25 more)

### Community 104 - "uses"
Cohesion: 0.21
Nodes (12): steps, name, runs-on, steps, frontend, lint, name, runs-on (+4 more)

### Community 105 - "RiskEventKind"
Cohesion: 0.24
Nodes (6): RiskEvent, RiskEventKind, DailyLossHalt, DrawdownFlatten, DrawdownHalt, WeeklyLossHalt

### Community 106 - "devDependencies"
Cohesion: 0.18
Nodes (12): devDependencies, concurrently@^10.0.3, laravel-vite-plugin@^3.1, @playwright/test@1.63.0, tailwindcss@^4.0.0, @tailwindcss/vite@^4.0.0, vite@^8.0.0, concurrently (+4 more)

### Community 108 - "Laravel\Mcp\Response"
Cohesion: 0.12
Nodes (8): GetFunding, GetJob, ListStrategies, ListTrials, SaveStrategy, TradingTool, FundingRateRepository, ChartSeries

### Community 117 - "App\Strategy\Dsl\TypeChecker"
Cohesion: 0.08
Nodes (19): {closure#2}(), {closure#1}(), {closure#1}(), {closure#2}(), ParamKind, Anchor, Count, Duration (+11 more)

### Community 118 - "Carbon\CarbonImmutable"
Cohesion: 0.05
Nodes (38): {closure#2}(), {closure#1}(), {closure#13}(), {closure#3}(), {closure#5}(), {closure#7}(), {closure#1}(), {closure#3}() (+30 more)

### Community 119 - "RulesHistoryTest.php"
Cohesion: 0.23
Nodes (12): InstrumentRules, {closure#1}(), RulesHistory, {closure#2}(), {closure#4}(), {closure#41}(), {closure#1}(), {closure#2}() (+4 more)

### Community 120 - "buy-and-hold.strategy.yaml"
Cohesion: 0.12
Nodes (16): entries, long, exits, stop_loss, fails_when, family, hypothesis, when (+8 more)

### Community 121 - ".times"
Cohesion: 0.28
Nodes (4): {closure#3}(), {closure#2}(), {closure#57}(), {closure#58}()

### Community 122 - "TokensTest.php"
Cohesion: 0.31
Nodes (6): {closure#3}(), {closure#4}(), contrastRatio(), contrastRules(), relativeLuminance(), themeTokens()

### Community 123 - "ArchiveImportTest.php"
Cohesion: 0.29
Nodes (10): archiveBytes(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}() (+2 more)

### Community 125 - "Illuminate\Database\Migrations\Migration"
Cohesion: 0.12
Nodes (4): {closure#1}(), {closure#1}(), {closure#2}(), {closure#1}()

### Community 126 - "InvalidArgumentException"
Cohesion: 0.10
Nodes (9): RevokeMcpToken, OpenAccount, ApproveLive, ConfirmsOperator, CreateAccount, StoreKeys, CheckMarketData, ReadsGridOptions (+1 more)

### Community 127 - ".register"
Cohesion: 0.08
Nodes (34): SimClock, {closure#1}(), {closure#10}(), {closure#13}(), {closure#14}(), {closure#15}(), {closure#16}(), {closure#18}() (+26 more)

### Community 128 - "funding-carry.strategy.yaml"
Cohesion: 0.12
Nodes (15): fails_when, family, hypothesis, params, enter_when, exit_when, margin_buffer, max_leverage_perp (+7 more)

### Community 129 - "McpServerTest.php"
Cohesion: 0.28
Nodes (12): actAsToken(), {closure#10}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}() (+4 more)

### Community 130 - "env.mjs"
Cohesion: 0.27
Nodes (4): sizes, BASE_URL, E2E_ENV, TELEGRAM_URL

### Community 131 - "chart.js"
Cohesion: 0.27
Nodes (7): faded(), options(), priceChart(), token(), volumeColor(), dslEditor(), highlight

### Community 132 - "TelegramSettings"
Cohesion: 0.14
Nodes (6): NotificationSettings, SettingSource, Env, None, TelegramClient, TelegramSettings

### Community 133 - "Percent"
Cohesion: 0.24
Nodes (3): Percent, {closure#14}(), {closure#15}()

### Community 135 - "settings-screen.blade.php"
Cohesion: 0.15
Nodes (12): approveLive, confirm, forgetKey, forgetTelegram, importUniverse, monitor, notify, removeInstrument (+4 more)

### Community 137 - ".account"
Cohesion: 0.33
Nodes (4): Policies, {closure#26}(), {closure#27}(), {closure#28}()

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
Cohesion: 0.10
Nodes (15): Backfill, {closure#10}(), {closure#11}(), {closure#2}(), {closure#4}(), {closure#6}(), {closure#8}(), {closure#9}() (+7 more)

### Community 142 - "Statistics"
Cohesion: 0.23
Nodes (11): Statistics, {closure#1}(), {closure#3}(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}() (+3 more)

### Community 143 - "CandleRepositoryTest.php"
Cohesion: 0.31
Nodes (11): candleStore(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}() (+3 more)

### Community 144 - "Illuminate\Support\Facades\Schema"
Cohesion: 0.15
Nodes (3): {closure#1}(), {closure#1}(), {closure#1}()

### Community 145 - "MarketDataSettingsTest.php"
Cohesion: 0.10
Nodes (13): {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), savedEmaCross(), {closure#2}(), {closure#3}(), {closure#4}() (+5 more)

### Community 146 - "App\Support\Symbol"
Cohesion: 0.06
Nodes (31): InstrumentDefinition, InstrumentStatus, Delisted, Halted, Trading, {closure#1}(), {closure#11}(), {closure#12}() (+23 more)

### Community 147 - "scripts"
Cohesion: 0.50
Nodes (4): scripts, build, dev, e2e

### Community 157 - "strategies-screen.blade.php"
Cohesion: 0.33
Nodes (5): open, promote, promoteDeployment, run, save

### Community 159 - "extra"
Cohesion: 0.67
Nodes (3): extra, laravel, dont-discover

### Community 173 - "GlobalsTest.php"
Cohesion: 0.14
Nodes (4): ChartTool, GetCandles, GetIndicators, Controller

### Community 174 - "App\Simulation\StrategyLibrary"
Cohesion: 0.26
Nodes (3): CompileStrategy, StrategiesScreen, StrategyLibrary

### Community 175 - ".evaluate"
Cohesion: 0.12
Nodes (11): StrategyGates, Gates, {closure#1}(), brokenGate(), {closure#1}(), {closure#3}(), {closure#4}(), {closure#6}() (+3 more)

### Community 176 - "Asset"
Cohesion: 0.24
Nodes (4): Marks, Asset, {closure#25}(), {closure#9}()

### Community 177 - "2026_11_09_000015_create_scalping_tables.php"
Cohesion: 0.33
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

### Community 178 - "App\Simulation\Backtest"
Cohesion: 0.24
Nodes (5): InMemoryDecisionLog, {closure#1}(), {closure#2}(), BacktestResult, ParticipantResult

### Community 181 - "SettingsScreenTest.php"
Cohesion: 0.18
Nodes (13): {closure#10}(), {closure#11}(), {closure#13}(), {closure#14}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}() (+5 more)

### Community 183 - "CompiledStrategy"
Cohesion: 0.17
Nodes (8): CompiledStrategy, {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#7}(), readBackDocument(), readBackSpec()

### Community 184 - "SimulationQueue"
Cohesion: 0.18
Nodes (4): RunBacktest, RunGrid, SimulationTool, SimulationQueue

### Community 189 - "MarketType"
Cohesion: 0.08
Nodes (25): {closure#1}(), BinanceEndpoints, BinanceRequestFailed, BinanceRestClient, {closure#2}(), EpochTime, ExchangeNumber, Json (+17 more)

### Community 190 - "PolicyLimits"
Cohesion: 0.08
Nodes (18): {closure#1}(), {closure#3}(), InstrumentDirectory, StoredInstruments, {closure#1}(), CompileStrategy, CheckResult, PolicyLimits (+10 more)

### Community 195 - "2026_11_09_000016_create_market_universe_tables.php"
Cohesion: 0.40
Nodes (3): {closure#1}(), {closure#2}(), up()

### Community 196 - "CandleStreamTest.php"
Cohesion: 0.08
Nodes (19): ReplayDivergence, ArchiveMissing, ChecksumMismatch, Sleeper, StreamFailed, {closure#1}(), {closure#2}(), {closure#3}() (+11 more)

### Community 197 - "2026_11_09_000017_create_live_tables.php"
Cohesion: 0.33
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

### Community 198 - "start-local.ps1"
Cohesion: 0.27
Nodes (4): Stop-Postgres(), Stop-ProcessTree(), Test-PostgresRunning(), Write-Step()

### Community 200 - ".plus"
Cohesion: 0.25
Nodes (3): Fill, PerpPosition, {closure#16}()

### Community 202 - "0001_01_01_000000_create_users_table.php"
Cohesion: 0.33
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

### Community 203 - "RejectionReason"
Cohesion: 0.08
Nodes (30): AccountView, RejectionReason, BelowMinimum, Breaker, Cooldown, DuplicateIntent, EntryWorking, GrossExposure (+22 more)

### Community 208 - "App\Execution\Models\Account"
Cohesion: 0.15
Nodes (7): LiveGate, RunEngine, LiveApproval, {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}()

## Knowledge Gaps
- **149 isolated node(s):** `Limit`, `Market`, `StopMarket`, `TakeProfitMarket`, `Anchor` (+144 more)
  These have ≤1 connection - possible missing edges. (Counts symbols only; 965 node(s) total have ≤1 connection when file, concept and rationale nodes are included.)
- **52 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `Decimal` connect `Decimal` to `engineHarness`, `CanonicalJson`, `Engine`, `Percent`, `DocumentChecker`, `.parse`, `CompiledStrategy.php`, `.of`, `BinanceVenueTest.php`, `.of`, `Candle`, `App\Market\Models\Instrument`, `Statistics`, `DecimalMath`, `Quantity`, `LedgerTest.php`, `App\Strategy\Dsl\Compiler\CompiledStrategy`, `Symbol`, `ClientOrderId`, `.of`, `Ledger`, `EnginePerpTest.php`, `RiskState`, `OrderManagerTest.php`, `BacktestTest.php`, `App\Strategy\Dsl\Document\DocumentChecker`, `.zero`, `Timeframe`, `.evaluate`, `Asset`, `App\Simulation\Backtest`, `FundingCarry`, `AccountRuntime.php`, `SizedStrategyTest.php`, `CompiledStrategy`, `.minus`, `RunBacktestCommandTest.php`, `IndicatorReferenceTest.php`, `PolicyLimits`, `.run`, `Strategy`, `App\Strategy\Dsl\Document\DocumentChecker`, `Symbol`, `.plus`, `ExitReason`, `RejectionReason`, `.measure`, `DecimalMath`, `MonteCarlo`, `App\Support\Number\Decimal`, `DocumentCheckerTest.php`, `MinuteCandles`, `.fromStrings`, `App\Strategy\Dsl\Document\DocumentChecker`, `StrategyGates.php`, `RiskEventKind`, `Carbon\CarbonImmutable`, `RulesHistoryTest.php`, `.times`?**
  _High betweenness centrality (0.111) - this node is a cross-community bridge._
- **What connects `Limit`, `Market`, `StopMarket` to the rest of the system?**
  _149 weakly-connected nodes found - possible missing edges._
- **Should `App\Market\Data\MarketType` be split into smaller, more focused modules?**
  _Cohesion score 0.11083743842364532 - nodes in this community are weakly interconnected._
- **Why does `Symbol` connect `Symbol` to `App\Market\Data\MarketType`, `engineHarness`, `CanonicalJson`, `Engine`, `.parse`, `DocumentChecker`, `.of`, `BinanceVenueTest.php`, `.of`, `Candle`, `ExchangeVerificationTest.php`, `CandleRepositoryTest.php`, `App\Support\Symbol`, `LedgerTest.php`, `App\Strategy\Dsl\Compiler\CompiledStrategy`, `Symbol`, `ClientOrderId`, `.of`, `Ledger`, `EnginePerpTest.php`, `OrderManagerTest.php`, `BacktestTest.php`, `Decimal`, `.zero`, `Timeframe`, `.evaluate`, `Asset`, `App\Simulation\Backtest`, `FundingCarry`, `AccountRuntime.php`, `CompiledStrategy`, `RunBacktestCommandTest.php`, `PolicyLimits`, `Illuminate\Database\ConnectionInterface`, `.run`, `Strategy`, `.plus`, `ExitReason`, `.measure`, `MinuteCandles`, `App\Strategy\Dsl\Document\DocumentChecker`, `StrategyGates.php`, `Laravel\Mcp\Response`, `RulesHistoryTest.php`, `InvalidArgumentException`?**
  _High betweenness centrality (0.039) - this node is a cross-community bridge._
- **Should `engineHarness` be split into smaller, more focused modules?**
  _Cohesion score 0.10929281122150789 - nodes in this community are weakly interconnected._
- **Why does `DonchianBreakout` connect `Symbol` to `.run`, `Strategy`, `.parse`, `Symbol`, `CompiledStrategy.php`, `.of`, `Decimal`, `Timeframe`, `FundingCarry`, `.minus`?**
  _High betweenness centrality (0.022) - this node is a cross-community bridge._
- **Should `DslError` be split into smaller, more focused modules?**
  _Cohesion score 0.13978494623655913 - nodes in this community are weakly interconnected._