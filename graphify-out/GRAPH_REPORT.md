# Graph Report - trading-platform  (2026-11-09)

## Corpus Check
- 591 files · ~242,996 words
- Verdict: corpus is large enough that graph structure adds value.
- Unclassified: 101 file(s) not represented in the graph (top: .zip 38, .CHECKSUM 38, (none) 19)

## Summary
- 4723 nodes · 16196 edges · 206 communities (152 shown, 54 thin omitted)
- Extraction: 91% EXTRACTED · 9% INFERRED · 0% AMBIGUOUS · INFERRED: 1437 edges (avg confidence: 0.85)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `3f3fe2a9`
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
- .times
- CompiledStrategy.php
- Side
- Instrument
- .of
- Candle
- ExchangeVerificationTest.php
- Donchian
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
- ClientOrderId
- Engine.php
- .now
- CompiledStrategy.php
- App\Market\Stream\PhrityTransport
- oi-momentum-long.strategy.yaml
- SimulatedPerpVenueTest.php
- App\Execution\Models\Account
- Carbon\CarbonImmutable
- TelegramTest.php
- Illuminate\Http\Client\Request
- SimClock
- BinanceRestClient
- BacktestTest.php
- App\Strategy\Dsl\Document\DocumentChecker
- ema-cross-scalp-long.strategy.yaml
- Decimal
- SimulatedVenue
- ScalpingArchiveTest.php
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
- Window
- OrderManagerTest.php
- donchian-breakout-short.strategy.yaml
- IndicatorReferenceTest.php
- support.mjs
- RunBacktestCommandTest.php
- .sqrt
- TrackedOrder
- DecisionRecord
- Ledger
- donchian-breakout.strategy.yaml
- .run
- StrategyIdentity
- App\Strategy\Dsl\Document\DocumentChecker
- InvalidArgumentException
- Engine
- .evaluate
- SimulationQueue
- scripts
- ma-trend-short.strategy.yaml
- .get
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
- Timeframe
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
- .parse
- StrategyGates.php
- uses
- SettingsScreenTest.php
- devDependencies
- BinanceVenueTest.php
- OrderManagerTest.php
- App\Agents\Mcp\TradingServer
- CandleFeedTest.php
- buy-and-hold.strategy.yaml
- ChartSeriesTest.php
- TokensTest.php
- ArchiveImportTest.php
- FortifyServiceProvider.php
- Illuminate\Support\Facades\Schema
- BinanceArchive
- ScalpingCollectorTest.php
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
- GridPart.php
- CandleRepositoryTest.php
- Illuminate\Support\Facades\Schema
- Money
- RuntimeException
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
- ChartScreen.php
- App\Strategy\Dsl\Document\StrategySpec
- RiskEventKind
- 2026_11_09_000012_create_account_results_tables.php
- MonteCarlo.php
- .tradeFlow
- DocumentCheckerTest.php
- SimulationJobsTest.php
- Quantity
- 2026_11_09_000013_add_daily_report_to_notification_settings.php
- MarketType
- Expression
- .number
- CandleStreamTest.php
- Agents/TokensTest.php
- .__construct
- 2026_11_09_000016_create_market_universe_tables.php
- 2026_11_09_000009_create_exchange_keys_table.php
- 2026_11_09_000017_create_live_tables.php
- RulesHistory
- ScalpingFunctionsTest.php
- VenuePosition
- {closure#40}
- Price
- VerificationSource
- StrategyTrial
- 2026_11_09_000015_create_scalping_tables.php

## God Nodes (most connected - your core abstractions)
1. `Decimal` - 516 edges
2. `Side` - 205 edges
3. `Symbol` - 205 edges
4. `Candle` - 136 edges
5. `App` - 125 edges
6. `Timeframe` - 123 edges
7. `Price` - 100 edges
8. `Position` - 96 edges
9. `Clock` - 81 edges
10. `MarketType` - 74 edges

## Surprising Connections (you probably didn't know these)
- `{closure#3}()` --calls--> `App`  [INFERRED]
  tests/Feature/Execution/LiveAccountTest.php → app/Notifications/SettingSource.php
- `{closure#8}()` --calls--> `App`  [INFERRED]
  tests/Feature/Web/SettingsScreenTest.php → app/Notifications/SettingSource.php
- `{closure#13}()` --calls--> `App`  [INFERRED]
  tests/Feature/Web/SettingsScreenTest.php → app/Notifications/SettingSource.php
- `{closure#4}()` --calls--> `App`  [INFERRED]
  tests/Feature/Simulation/SimulationJobsTest.php → app/Notifications/SettingSource.php
- `{closure#3}()` --calls--> `App`  [INFERRED]
  tests/Feature/Network/BinancePublicDataTest.php → app/Notifications/SettingSource.php

## Import Cycles
- None detected.

## Communities (206 total, 54 thin omitted)

### Community 0 - ".of"
Cohesion: 0.08
Nodes (33): {closure#1}(), {closure#1}(), {closure#2}(), {closure#3}(), documentedLimits(), {closure#5}(), balances(), {closure#41}() (+25 more)

### Community 1 - "EngineTest.php"
Cohesion: 0.05
Nodes (49): Venue, EngineCommand, EngineMode, Backtest, Live, Paper, Testnet, WarmingVenue (+41 more)

### Community 2 - "CanonicalJson"
Cohesion: 0.24
Nodes (6): CanonicalJson, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}()

### Community 3 - "DslError"
Cohesion: 0.14
Nodes (14): {closure#1}(), {closure#1}(), {closure#1}(), {closure#5}(), {closure#8}(), DslError, YamlList, YamlReader (+6 more)

### Community 4 - "Asset"
Cohesion: 0.18
Nodes (4): Marks, Asset, {closure#25}(), {closure#9}()

### Community 5 - ".parse"
Cohesion: 0.08
Nodes (55): Strategy, baselineDocument(), baselinePairs(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#14}() (+47 more)

### Community 7 - ".times"
Cohesion: 0.07
Nodes (6): Fill, PerpPosition, Position, VolumeProfile, Perpetual, {closure#5}()

### Community 8 - "CompiledStrategy.php"
Cohesion: 0.14
Nodes (4): {closure#1}(), {closure#42}(), Compiler, Program

### Community 9 - "Side"
Cohesion: 0.18
Nodes (43): TradeMemory, Side, Buy, Sell, btc(), {closure#10}(), {closure#11}(), {closure#12}() (+35 more)

### Community 10 - "Instrument"
Cohesion: 0.05
Nodes (24): BackfillStep, CandleRepository, {closure#2}(), {closure#4}(), CandleSource, Archive, Rest, Stream (+16 more)

### Community 11 - ".of"
Cohesion: 0.12
Nodes (24): {closure#7}(), {closure#2}(), {closure#1}(), usdt(), {closure#11}(), {closure#3}(), {closure#6}(), {closure#7}() (+16 more)

### Community 12 - "Candle"
Cohesion: 0.09
Nodes (31): {closure#1}(), {closure#3}(), Candle, {closure#1}(), {closure#2}(), candleWith(), {closure#1}(), {closure#10}() (+23 more)

### Community 13 - "ExchangeVerificationTest.php"
Cohesion: 0.11
Nodes (35): App, {closure#2}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#10}(), {closure#11}(), {closure#12}() (+27 more)

### Community 14 - "Donchian"
Cohesion: 0.52
Nodes (3): Donchian, {closure#10}(), {closure#15}()

### Community 15 - "PolicyLimits"
Cohesion: 0.10
Nodes (13): InstrumentDirectory, StoredInstruments, {closure#1}(), CompileStrategy, PolicyLimits, StrategyRepository, {closure#1}(), {closure#2}() (+5 more)

### Community 16 - "Node"
Cohesion: 0.11
Nodes (31): Binary, Node, Unary, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}() (+23 more)

### Community 17 - "Clock"
Cohesion: 0.05
Nodes (28): CreateMcpToken, RevokeMcpToken, AuthenticateMcpToken, Tokens, FlattenEngine, HaltEngine, IssuesCommands, PauseStrategy (+20 more)

### Community 18 - "RunEngine.php"
Cohesion: 0.08
Nodes (6): AccountRuntime, {closure#4}(), RunEngine, JournalEntry, {closure#2}(), {closure#4}()

### Community 19 - "ScreensTest.php"
Cohesion: 0.08
Nodes (15): User, {closure#1}(), {closure#1}(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}() (+7 more)

### Community 20 - "LedgerTest.php"
Cohesion: 0.22
Nodes (26): balanceOf(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#15}(), {closure#16}() (+18 more)

### Community 21 - "Illuminate\Database\Schema\Blueprint"
Cohesion: 0.19
Nodes (10): {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#1}(), {closure#2}(), {closure#3}() (+2 more)

### Community 22 - "generate.py"
Cohesion: 0.07
Nodes (30): atr(), bars(), bb(), change(), dmi(), donchian(), ema(), highest() (+22 more)

### Community 24 - "Symbol"
Cohesion: 0.04
Nodes (43): {closure#2}(), {closure#3}(), {closure#4}(), {closure#1}(), BuyAndHold, DonchianBreakout, FundingCarry, MovingAverageTrend (+35 more)

### Community 25 - ".of"
Cohesion: 0.19
Nodes (44): RiskState, StrategyLimits, account(), accountPolicy(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}() (+36 more)

### Community 26 - "ClientOrderId"
Cohesion: 0.09
Nodes (22): Balance, ClientOrderId, Income, IncomeKind, Funding, Liquidation, OcoRequest, OrderRequest (+14 more)

### Community 27 - "Engine.php"
Cohesion: 0.09
Nodes (8): IntentRecord, {closure#1}(), {closure#11}(), {closure#12}(), {closure#2}(), {closure#3}(), {closure#9}(), {closure#1}()

### Community 28 - ".now"
Cohesion: 0.09
Nodes (10): Backfill, BackfillReport, BackfillMarketData, SelectsInstruments, {closure#2}(), StreamCandles, {closure#1}(), {closure#2}() (+2 more)

### Community 29 - "CompiledStrategy.php"
Cohesion: 0.08
Nodes (11): {closure#2}(), {closure#3}(), {closure#5}(), {closure#6}(), {closure#7}(), {closure#8}(), {closure#9}(), {closure#1}() (+3 more)

### Community 30 - "App\Market\Stream\PhrityTransport"
Cohesion: 0.12
Nodes (3): PhrityTransport, StreamFailed, FakeTransport

### Community 31 - "oi-momentum-long.strategy.yaml"
Cohesion: 0.07
Nodes (27): entries, long, exits, stop_loss, take_profit, when, fails_when, family (+19 more)

### Community 32 - "SimulatedPerpVenueTest.php"
Cohesion: 0.18
Nodes (27): {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#15}(), {closure#18}() (+19 more)

### Community 33 - "App\Execution\Models\Account"
Cohesion: 0.03
Nodes (22): LiveGate, OpenAccount, ApproveLive, ConfirmsOperator, CreateAccount, {closure#5}(), StoreKeys, Account (+14 more)

### Community 34 - "Carbon\CarbonImmutable"
Cohesion: 0.06
Nodes (16): ArchivePeriod, {closure#1}(), {closure#11}(), {closure#12}(), {closure#1}(), OpenInterest, DiskUsage, {closure#1}() (+8 more)

### Community 35 - "TelegramTest.php"
Cohesion: 0.13
Nodes (18): SendTelegram, {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#15}(), {closure#16}() (+10 more)

### Community 36 - "Illuminate\Http\Client\Request"
Cohesion: 0.13
Nodes (17): approveLive(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}(), {closure#8}() (+9 more)

### Community 37 - "SimClock"
Cohesion: 0.10
Nodes (23): SimClock, {closure#1}(), {closure#1}(), {closure#11}(), {closure#14}(), {closure#15}(), {closure#16}(), {closure#4}() (+15 more)

### Community 38 - "BinanceRestClient"
Cohesion: 0.24
Nodes (3): BinanceRestClient, Json, {closure#3}()

### Community 39 - "BacktestTest.php"
Cohesion: 0.09
Nodes (27): FeeSchedule, InMemoryDecisionLog, CostModel, CandleFeed, FundingRate, Backtest, {closure#1}(), {closure#2}() (+19 more)

### Community 40 - "App\Strategy\Dsl\Document\DocumentChecker"
Cohesion: 0.07
Nodes (15): Argument, BoolLiteral, Call, DurationLiteral, Identifier, Lookback, NumberLiteral, OnTimeframe (+7 more)

### Community 41 - "ema-cross-scalp-long.strategy.yaml"
Cohesion: 0.06
Nodes (33): entries, long, exits, stop_loss, take_profit, time_stop, when, fails_when (+25 more)

### Community 42 - "Decimal"
Cohesion: 0.03
Nodes (47): {closure#2}(), {closure#1}(), SimulatedPosition, {closure#6}(), {closure#2}(), RoundTrip, {closure#1}(), {closure#2}() (+39 more)

### Community 43 - "SimulatedVenue"
Cohesion: 0.07
Nodes (18): Liquidity, Maker, Taker, OrderStatus, Cancelled, Expired, Filled, New (+10 more)

### Community 44 - "ScalpingArchiveTest.php"
Cohesion: 0.14
Nodes (14): PriceMinute, TradeFlow, {closure#1}(), {closure#10}(), {closure#11}(), {closure#2}(), {closure#3}(), {closure#4}() (+6 more)

### Community 45 - ".walk"
Cohesion: 0.16
Nodes (19): CandleAggregator, CandleSequenceBroken, {closure#3}(), {closure#9}(), CandleFactory, aggregate(), {closure#1}(), {closure#10}() (+11 more)

### Community 46 - "Illuminate\Database\Eloquent\Model"
Cohesion: 0.05
Nodes (26): McpCall, McpToken, AccountEquity, AccountTrade, PlainDecimal, Deployment, ExchangeKey, {closure#1}() (+18 more)

### Community 47 - "package.json"
Cohesion: 0.15
Nodes (17): dependencies, codemirror@^6.0.2, @codemirror/lang-yaml@^6.1.3, @codemirror/language@^6.13.1, @lezer/highlight@^1.2.5, lightweight-charts@^5.2.1, optionalDependencies, @laravel/multiplex@^0.4.1 (+9 more)

### Community 49 - "BinanceRestClientTest.php"
Cohesion: 0.14
Nodes (16): Sleeper, SystemSleeper, binanceClient(), {closure#1}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#2}() (+8 more)

### Community 50 - "ema-cross-scalp-short.strategy.yaml"
Cohesion: 0.06
Nodes (33): entries, short, exits, stop_loss, take_profit, time_stop, when, fails_when (+25 more)

### Community 51 - "Illuminate\Database\ConnectionInterface"
Cohesion: 0.20
Nodes (3): ReadsGridOptions, StrategyGates, StrategySpec

### Community 52 - "Side"
Cohesion: 0.22
Nodes (22): ExitReason, Flatten, Liquidation, Signal, Stop, Target, {closure#1}(), {closure#10}() (+14 more)

### Community 53 - "Laravel\Mcp\Response"
Cohesion: 0.13
Nodes (6): GetFunding, GetJob, ListTrials, SaveStrategy, TradingTool, ChartSeries

### Community 54 - "postgres"
Cohesion: 0.10
Nodes (19): POSTGRES_DB, POSTGRES_PASSWORD, POSTGRES_USER, interval, retries, test, timeout, environment (+11 more)

### Community 56 - "OrderManagerTest.php"
Cohesion: 0.13
Nodes (10): OrderAck, OrderRole, Entry, Exit, Flatten, Stop, Target, ProtectionPlan (+2 more)

### Community 57 - "donchian-breakout-short.strategy.yaml"
Cohesion: 0.07
Nodes (26): entries, short, default, range, step, default, range, step (+18 more)

### Community 58 - "IndicatorReferenceTest.php"
Cohesion: 0.05
Nodes (50): Atr, BarIndicator, Bollinger, Change, DonchianChannel, Ema, Highest, Hma (+42 more)

### Community 59 - "support.mjs"
Cohesion: 0.27
Nodes (9): @playwright/test, confirmed(), checkLayout(), EMAIL, freshCode(), PASSWORD, reauthenticate(), totp() (+1 more)

### Community 60 - "RunBacktestCommandTest.php"
Cohesion: 0.10
Nodes (29): BacktestRun, {closure#1}(), {closure#4}(), {closure#6}(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}() (+21 more)

### Community 61 - ".sqrt"
Cohesion: 0.08
Nodes (18): Normal, {closure#3}(), DecimalMath, InvalidNumber, {closure#10}(), {closure#1}(), {closure#10}(), {closure#11}() (+10 more)

### Community 62 - "TrackedOrder"
Cohesion: 0.25
Nodes (9): TrackedOrder, {closure#1}(), {closure#2}(), OrderBook, {closure#1}(), {closure#11}(), {closure#12}(), {closure#13}() (+1 more)

### Community 63 - "DecisionRecord"
Cohesion: 0.15
Nodes (6): DecisionLog, DecisionRecord, DatabaseDecisionLog, {closure#1}(), {closure#3}(), {closure#4}()

### Community 64 - "Ledger"
Cohesion: 0.23
Nodes (5): Ledger, LedgerViolation, {closure#19}(), {closure#12}(), {closure#14}()

### Community 65 - "donchian-breakout.strategy.yaml"
Cohesion: 0.07
Nodes (26): entries, long, default, range, step, default, range, step (+18 more)

### Community 66 - ".run"
Cohesion: 0.08
Nodes (29): RiskProfile, Account, Benchmark, RiskPolicy, PerformanceMetrics, Report, btcMinutes(), {closure#1}() (+21 more)

### Community 67 - "StrategyIdentity"
Cohesion: 0.22
Nodes (5): StrategyIdentity, RenamedStrategy, {closure#1}(), {closure#2}(), {closure#3}()

### Community 68 - "App\Strategy\Dsl\Document\DocumentChecker"
Cohesion: 0.07
Nodes (33): {closure#1}(), {closure#3}(), {closure#4}(), {closure#9}(), ParameterSpec, DslWriter, Library, Scope (+25 more)

### Community 69 - "InvalidArgumentException"
Cohesion: 0.08
Nodes (10): {closure#1}(), {closure#1}(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#5}(), PreparesBacktests, {closure#1}() (+2 more)

### Community 70 - "Engine"
Cohesion: 0.12
Nodes (6): EngineRecord, Engine, {closure#14}(), {closure#7}(), {closure#2}(), {closure#1}()

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

### Community 75 - ".get"
Cohesion: 0.19
Nodes (5): BinanceRequestFailed, LocationRestricted, RateLimited, RequestFailed, SyncInstruments

### Community 76 - ".measure"
Cohesion: 0.10
Nodes (18): EquityTracker, Performance, Statistics, {closure#2}(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#1}() (+10 more)

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
Nodes (20): {closure#7}(), Cscv, DeflatedSharpe, {closure#3}(), closeTo(), {closure#1}(), {closure#11}(), {closure#12}() (+12 more)

### Community 82 - "MonteCarlo"
Cohesion: 0.30
Nodes (9): MonteCarlo, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), mcDecimals() (+1 more)

### Community 83 - "App\Support\Number\Decimal"
Cohesion: 0.07
Nodes (53): {closure#8}(), {closure#3}(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}() (+45 more)

### Community 84 - "pint/composer.json"
Cohesion: 0.20
Nodes (9): config, sort-packages, description, license, name, require, laravel/pint, php (+1 more)

### Community 85 - "HealthTest.php"
Cohesion: 0.07
Nodes (31): {closure#1}(), {closure#1}(), {closure#2}(), {closure#3}(), fakeTestnet(), testnetAccount(), {closure#2}(), {closure#3}() (+23 more)

### Community 86 - "RunSimulation.php"
Cohesion: 0.08
Nodes (9): ImportMarketData, MarketJob, {closure#3}(), RunSimulation, SimulationJob, {closure#4}(), {closure#5}(), {closure#3}() (+1 more)

### Community 87 - "Timeframe"
Cohesion: 0.09
Nodes (17): {closure#2}(), {closure#4}(), CarryTemplate, {closure#2}(), Timeframe, FifteenMinutes, FiveMinutes, FourHours (+9 more)

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
Cohesion: 0.09
Nodes (13): {closure#9}(), RegimeLabels, DirectionalMovement, {closure#1}(), {closure#42}(), {closure#1}(), {closure#1}(), {closure#2}() (+5 more)

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
Cohesion: 0.24
Nodes (5): {closure#1}(), {closure#2}(), CheckResult, SaveResult, StrategyVersion

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

### Community 102 - ".parse"
Cohesion: 0.22
Nodes (8): {closure#1}(), {closure#2}(), EpochTime, ExchangeNumber, {closure#1}(), {closure#2}(), {closure#1}(), {closure#2}()

### Community 103 - "StrategyGates.php"
Cohesion: 0.13
Nodes (8): {closure#8}(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#7}(), WalkForwardSet

### Community 104 - "uses"
Cohesion: 0.21
Nodes (12): steps, name, runs-on, steps, frontend, lint, name, runs-on (+4 more)

### Community 105 - "SettingsScreenTest.php"
Cohesion: 0.24
Nodes (11): {closure#10}(), {closure#11}(), {closure#13}(), {closure#14}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}() (+3 more)

### Community 106 - "devDependencies"
Cohesion: 0.18
Nodes (12): devDependencies, concurrently@^10.0.3, laravel-vite-plugin@^3.1, @playwright/test@1.63.0, tailwindcss@^4.0.0, @tailwindcss/vite@^4.0.0, vite@^8.0.0, concurrently (+4 more)

### Community 108 - "BinanceVenueTest.php"
Cohesion: 0.10
Nodes (17): {closure#2}(), BinanceVenue, BinanceApiError, BinanceUnreachable, SignedBinanceClient, binanceVenue(), {closure#1}(), {closure#2}() (+9 more)

### Community 117 - "OrderManagerTest.php"
Cohesion: 0.30
Nodes (14): btcUsdt(), {closure#10}(), {closure#15}(), {closure#16}(), {closure#2}(), {closure#4}(), {closure#5}(), {closure#6}() (+6 more)

### Community 118 - "App\Agents\Mcp\TradingServer"
Cohesion: 0.13
Nodes (5): DslReference, GetStrategy, ListInstruments, ListStrategies, TradingServer

### Community 119 - "CandleFeedTest.php"
Cohesion: 0.35
Nodes (12): {closure#1}(), {closure#10}(), {closure#12}(), {closure#13}(), {closure#2}(), {closure#4}(), {closure#5}(), {closure#6}() (+4 more)

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

### Community 124 - "FortifyServiceProvider.php"
Cohesion: 0.06
Nodes (9): AppServiceProvider, {closure#7}(), {closure#3}(), {closure#4}(), FortifyServiceProvider, {closure#1}(), {closure#2}(), {closure#3}() (+1 more)

### Community 127 - "ScalpingCollectorTest.php"
Cohesion: 0.12
Nodes (18): WallClock, {closure#1}(), aggTrade(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#5}(), {closure#6}() (+10 more)

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
Cohesion: 0.08
Nodes (10): Notifier, CheckHealth, HealthAlerts, NotificationSettings, SettingSource, Env, None, TelegramClient (+2 more)

### Community 133 - "EnginePerpTest.php"
Cohesion: 0.26
Nodes (13): {closure#10}(), {closure#11}(), {closure#14}(), {closure#5}(), {closure#6}(), {closure#7}(), {closure#8}(), {closure#9}() (+5 more)

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
Cohesion: 0.17
Nodes (11): {closure#4}(), MinuteCandles, BreakerEvent, MarketBreakers, {closure#14}(), {closure#3}(), {closure#7}(), breakerMinute() (+3 more)

### Community 141 - ".parse"
Cohesion: 0.26
Nodes (6): KlineMessage, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), streamLines()

### Community 142 - "GridPart.php"
Cohesion: 0.24
Nodes (3): {closure#2}(), GridPart, SetOutcome

### Community 143 - "CandleRepositoryTest.php"
Cohesion: 0.28
Nodes (11): candleStore(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}() (+3 more)

### Community 146 - "RuntimeException"
Cohesion: 0.23
Nodes (4): ReplayDivergence, ReconciliationMismatch, ArchiveMissing, ChecksumMismatch

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
Cohesion: 0.40
Nodes (4): open, promote, run, save

### Community 159 - "extra"
Cohesion: 0.67
Nodes (3): extra, laravel, dont-discover

### Community 173 - "GlobalsTest.php"
Cohesion: 0.14
Nodes (4): ChartTool, GetCandles, GetIndicators, Controller

### Community 174 - "StrategyLibrary"
Cohesion: 0.25
Nodes (3): CompileStrategy, StrategiesScreen, StrategyLibrary

### Community 175 - "App\Support\Clock\Clock"
Cohesion: 0.07
Nodes (16): {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}(), {closure#8}(), {closure#9}() (+8 more)

### Community 176 - "ChartScreen.php"
Cohesion: 0.06
Nodes (26): {closure#2}(), ChartScreen, {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), FunctionSpec, Group (+18 more)

### Community 177 - "App\Strategy\Dsl\Document\StrategySpec"
Cohesion: 0.29
Nodes (8): {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#7}(), readBackDocument(), readBackSpec()

### Community 178 - "RiskEventKind"
Cohesion: 0.24
Nodes (6): RiskEvent, RiskEventKind, DailyLossHalt, DrawdownFlatten, DrawdownHalt, WeeklyLossHalt

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
Cohesion: 0.09
Nodes (14): BinanceEndpoints, InstrumentDefinition, InstrumentStatus, Delisted, Halted, Trading, MarketType, Perp (+6 more)

### Community 190 - "Expression"
Cohesion: 0.28
Nodes (4): {closure#7}(), EntrySpec, ExitSpec, Expression

### Community 192 - "CandleStreamTest.php"
Cohesion: 0.44
Nodes (8): {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), streamMessages(), streamWith()

### Community 193 - "Agents/TokensTest.php"
Cohesion: 0.29
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

### Community 195 - "2026_11_09_000016_create_market_universe_tables.php"
Cohesion: 0.40
Nodes (3): {closure#1}(), {closure#2}(), up()

### Community 197 - "2026_11_09_000017_create_live_tables.php"
Cohesion: 0.33
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

### Community 198 - "RulesHistory"
Cohesion: 0.25
Nodes (9): RulesHistory, {closure#2}(), {closure#4}(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), rules() (+1 more)

### Community 199 - "ScalpingFunctionsTest.php"
Cohesion: 0.24
Nodes (10): backtestableScalpingHour(), {closure#2}(), {closure#3}(), {closure#5}(), {closure#6}(), {closure#7}(), {closure#8}(), referencePart() (+2 more)

### Community 202 - "Price"
Cohesion: 0.07
Nodes (33): InstrumentRules, {closure#1}(), AccountView, RejectionReason, BelowMinimum, Breaker, Cooldown, DuplicateIntent (+25 more)

### Community 203 - "VerificationSource"
Cohesion: 0.50
Nodes (3): VerificationSource, DailyArchive, Rest

### Community 205 - "2026_11_09_000015_create_scalping_tables.php"
Cohesion: 0.33
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

## Knowledge Gaps
- **146 isolated node(s):** `forgetTelegram`, `testTelegram`, `confirm`, `notify`, `monitor` (+141 more)
  These have ≤1 connection - possible missing edges. (Counts symbols only; 928 node(s) total have ≤1 connection when file, concept and rationale nodes are included.)
- **54 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `Decimal` connect `Decimal` to `.of`, `EngineTest.php`, `CanonicalJson`, `DslError`, `Asset`, `.parse`, `DocumentChecker`, `.times`, `EnginePerpTest.php`, `Side`, `Instrument`, `.of`, `Candle`, `GridPart.php`, `PolicyLimits`, `Node`, `Clock`, `Donchian`, `Money`, `LedgerTest.php`, `WalkForward.php`, `Symbol`, `.of`, `ClientOrderId`, `Engine.php`, `CompiledStrategy.php`, `SimulatedPerpVenueTest.php`, `Carbon\CarbonImmutable`, `SimClock`, `BinanceRestClient`, `BacktestTest.php`, `App\Strategy\Dsl\Document\DocumentChecker`, `SimulatedVenue`, `.walk`, `App\Support\Clock\Clock`, `ChartScreen.php`, `.compute`, `RiskEventKind`, `Illuminate\Database\ConnectionInterface`, `MonteCarlo.php`, `App\Strategy\Dsl\Document\StrategySpec`, `Side`, `Window`, `OrderManagerTest.php`, `Quantity`, `IndicatorReferenceTest.php`, `DocumentCheckerTest.php`, `RunBacktestCommandTest.php`, `MarketType`, `TrackedOrder`, `DecisionRecord`, `Ledger`, `.sqrt`, `.__construct`, `.run`, `Expression`, `InvalidArgumentException`, `Engine`, `.evaluate`, `VenuePosition`, `App\Strategy\Dsl\Document\DocumentChecker`, `Price`, `RulesHistory`, `.measure`, `DecimalMath`, `MonteCarlo`, `App\Support\Number\Decimal`, `HealthTest.php`, `Timeframe`, `.evaluate`, `.fromStrings`, `StrategyGates.php`, `BinanceVenueTest.php`, `OrderManagerTest.php`, `CandleFeedTest.php`, `ChartSeriesTest.php`?**
  _High betweenness centrality (0.179) - this node is a cross-community bridge._
- **What connects `forgetTelegram`, `testTelegram`, `confirm` to the rest of the system?**
  _146 weakly-connected nodes found - possible missing edges._
- **Should `.of` be split into smaller, more focused modules?**
  _Cohesion score 0.08108108108108109 - nodes in this community are weakly interconnected._
- **Why does `Symbol` connect `Symbol` to `EngineTest.php`, `CanonicalJson`, `DslError`, `Asset`, `.parse`, `DocumentChecker`, `.times`, `EnginePerpTest.php`, `Side`, `Instrument`, `.of`, `ExchangeVerificationTest.php`, `PolicyLimits`, `CandleRepositoryTest.php`, `RunEngine.php`, `ScreensTest.php`, `LedgerTest.php`, `.of`, `ClientOrderId`, `Engine.php`, `.now`, `CompiledStrategy.php`, `SimulatedPerpVenueTest.php`, `App\Execution\Models\Account`, `SimClock`, `BinanceRestClient`, `BacktestTest.php`, `Decimal`, `SimulatedVenue`, `App\Support\Clock\Clock`, `ChartScreen.php`, `App\Strategy\Dsl\Document\StrategySpec`, `Illuminate\Database\ConnectionInterface`, `Side`, `Laravel\Mcp\Response`, `OrderManagerTest.php`, `RunBacktestCommandTest.php`, `MarketType`, `TrackedOrder`, `DecisionRecord`, `Ledger`, `.run`, `InvalidArgumentException`, `RulesHistory`, `Engine`, `VenuePosition`, `Price`, `DecimalMath`, `HealthTest.php`, `Timeframe`, `StrategyGates.php`, `BinanceVenueTest.php`, `OrderManagerTest.php`, `CandleFeedTest.php`, `ChartSeriesTest.php`?**
  _High betweenness centrality (0.053) - this node is a cross-community bridge._
- **Should `EngineTest.php` be split into smaller, more focused modules?**
  _Cohesion score 0.053534660260809885 - nodes in this community are weakly interconnected._
- **Why does `Candle` connect `Candle` to `EngineTest.php`, `EnginePerpTest.php`, `.parse`, `.times`, `Side`, `Instrument`, `Carbon\CarbonImmutable`, `.parse`, `Donchian`, `CandleRepositoryTest.php`, `ExchangeVerificationTest.php`, `Symbol`, `Carbon\CarbonImmutable`, `BacktestTest.php`, `Decimal`, `SimulatedVenue`, `.walk`, `App\Support\Clock\Clock`, `BinanceRestClientTest.php`, `MonteCarlo.php`, `Window`, `IndicatorReferenceTest.php`, `.__construct`, `.run`, `Price`, `.measure`, `App\Support\Number\Decimal`, `Timeframe`, `.fromStrings`, `StrategyGates.php`, `OrderManagerTest.php`, `CandleFeedTest.php`, `ChartSeriesTest.php`?**
  _High betweenness centrality (0.027) - this node is a cross-community bridge._
- **Should `DslError` be split into smaller, more focused modules?**
  _Cohesion score 0.1361344537815126 - nodes in this community are weakly interconnected._