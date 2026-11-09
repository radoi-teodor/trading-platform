# Graph Report - trading-platform  (2026-11-09)

## Corpus Check
- 575 files · ~235,962 words
- Verdict: corpus is large enough that graph structure adds value.
- Unclassified: 101 file(s) not represented in the graph (top: .zip 38, .CHECKSUM 38, (none) 19)

## Summary
- 4570 nodes · 15673 edges · 195 communities (142 shown, 53 thin omitted)
- Extraction: 91% EXTRACTED · 9% INFERRED · 0% AMBIGUOUS · INFERRED: 1339 edges (avg confidence: 0.85)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `7bd2c16c`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- RuntimeException
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
- AccountRuntime
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
- .timestamp
- .zero
- Account
- Carbon\CarbonImmutable
- App
- PreparesBacktests.php
- .register
- .isGreaterThan
- FundingRate
- App\Strategy\Dsl\Document\DocumentChecker
- ema-cross-scalp-long.strategy.yaml
- Decimal
- SimulatedVenue
- OrderRole
- .walk
- Illuminate\Database\Eloquent\Model
- package.json
- Window
- Illuminate\Http\Client\Request
- ema-cross-scalp-short.strategy.yaml
- .handle
- Side
- Laravel\Mcp\Response
- postgres
- MonteCarlo
- Price
- donchian-breakout-short.strategy.yaml
- IndicatorReferenceTest.php
- support.mjs
- RunBacktestCommandTest.php
- .of
- BinanceArchive
- DecisionRecord
- HealthChecks
- donchian-breakout.strategy.yaml
- .run
- SimClock
- App\Strategy\Dsl\Document\DocumentChecker
- InvalidArgumentException
- Carbon\CarbonImmutable
- BinanceRestClientTest.php
- SimulationQueue
- scripts
- ma-trend-short.strategy.yaml
- FundingCarry
- .sqrt
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
- ForwardingVenue
- StrategyGates.php
- uses
- SettingsScreenTest.php
- devDependencies
- BinanceVenueTest.php
- Venue
- App\Agents\Mcp\TradingServer
- CandleFeedTest.php
- buy-and-hold.strategy.yaml
- ChartSeriesTest.php
- TokensTest.php
- ArchiveImportTest.php
- FortifyServiceProvider.php
- Illuminate\Support\Facades\Schema
- BinanceArchive
- App\Market\Models\Instrument
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
- MarketType
- PaperEngineTest.php
- Balance
- Illuminate\Database\Migrations\Migration
- .of
- .push
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
- GlobalsTest.php
- StrategyLibrary
- AppServiceProvider.php
- ChartScreen.php
- 2026_11_09_000012_create_account_results_tables.php
- 2026_11_09_000011_create_notification_settings_table.php
- RiskPolicy
- SimulationJobsTest.php
- OrderManagerTest.php
- 2026_11_09_000013_add_daily_report_to_notification_settings.php
- .parse
- BarIndicator
- StrategyIdentity
- Account.php
- RulesHistory
- .parse
- VenuePosition
- .__construct
- 2026_11_09_000015_create_scalping_tables.php

## God Nodes (most connected - your core abstractions)
1. `Decimal` - 524 edges
2. `Symbol` - 213 edges
3. `Side` - 208 edges
4. `Candle` - 139 edges
5. `Timeframe` - 130 edges
6. `App` - 108 edges
7. `Price` - 101 edges
8. `Position` - 96 edges
9. `Clock` - 94 edges
10. `MarketType` - 81 edges

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

## Communities (195 total, 53 thin omitted)

### Community 0 - "RuntimeException"
Cohesion: 0.08
Nodes (9): ReplayDivergence, ArchiveMissing, ChecksumMismatch, CheckMarketData, SelectsInstruments, VerifyMarketData, Universe, QualityCheck (+1 more)

### Community 1 - "EngineTest.php"
Cohesion: 0.11
Nodes (38): ScriptedStrategy, {closure#1}(), {closure#10}(), {closure#13}(), {closure#14}(), {closure#16}(), {closure#17}(), {closure#2}() (+30 more)

### Community 2 - "CanonicalJson"
Cohesion: 0.30
Nodes (6): CanonicalJson, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}()

### Community 3 - "DslError"
Cohesion: 0.10
Nodes (14): {closure#1}(), {closure#1}(), BoolLiteral, DslError, Position, YamlList, YamlReader, YamlScalar (+6 more)

### Community 4 - "Asset"
Cohesion: 0.15
Nodes (4): Marks, Asset, {closure#25}(), {closure#9}()

### Community 5 - ".parse"
Cohesion: 0.08
Nodes (47): Strategy, baselineDocument(), baselinePairs(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#14}() (+39 more)

### Community 7 - ".times"
Cohesion: 0.07
Nodes (8): {closure#2}(), VolumeProfile, Perpetual, {closure#16}(), {closure#5}(), {closure#4}(), {closure#6}(), {closure#7}()

### Community 8 - "CompiledStrategy.php"
Cohesion: 0.13
Nodes (6): {closure#1}(), {closure#2}(), PerpetualTracker, {closure#42}(), Compiler, Program

### Community 9 - "Side"
Cohesion: 0.16
Nodes (48): Side, Buy, Sell, btc(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}() (+40 more)

### Community 10 - "Instrument"
Cohesion: 0.07
Nodes (15): BackfillStep, CandleRepository, CandleSource, Archive, Rest, Stream, UpsertResult, Instrument (+7 more)

### Community 11 - ".measure"
Cohesion: 0.18
Nodes (9): EquityTracker, Performance, {closure#1}(), {closure#2}(), {closure#3}(), {closure#5}(), {closure#6}(), {closure#8}() (+1 more)

### Community 12 - "Candle"
Cohesion: 0.10
Nodes (37): {closure#1}(), {closure#3}(), Candle, {closure#9}(), {closure#1}(), {closure#2}(), {closure#1}(), {closure#42}() (+29 more)

### Community 13 - "ExchangeVerificationTest.php"
Cohesion: 0.11
Nodes (29): {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}() (+21 more)

### Community 14 - "IndicatorsTest.php"
Cohesion: 0.10
Nodes (20): Atr, Donchian, Ema, Period, Sma, bar(), {closure#1}(), {closure#10}() (+12 more)

### Community 15 - "RiskPolicy"
Cohesion: 0.06
Nodes (32): InstrumentDirectory, StoredInstruments, {closure#1}(), CompileStrategy, CheckResult, PolicyLimits, StrategySpec, SaveResult (+24 more)

### Community 16 - "Node"
Cohesion: 0.11
Nodes (30): Binary, Node, Unary, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}() (+22 more)

### Community 17 - "Clock"
Cohesion: 0.05
Nodes (25): CreateMcpToken, RevokeMcpToken, Tokens, FlattenEngine, HaltEngine, IssuesCommands, PauseStrategy, ResumeEngine (+17 more)

### Community 19 - "ScreensTest.php"
Cohesion: 0.11
Nodes (17): {closure#1}(), {closure#1}(), {closure#2}(), {closure#3}(), fakeTestnet(), testnetAccount(), {closure#1}(), {closure#2}() (+9 more)

### Community 20 - "LedgerTest.php"
Cohesion: 0.22
Nodes (26): balanceOf(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#15}(), {closure#16}() (+18 more)

### Community 21 - "Illuminate\Database\Schema\Blueprint"
Cohesion: 0.19
Nodes (10): {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#1}(), {closure#2}(), {closure#3}() (+2 more)

### Community 22 - "generate.py"
Cohesion: 0.07
Nodes (30): atr(), bars(), bb(), change(), dmi(), donchian(), ema(), highest() (+22 more)

### Community 23 - "ReadBack"
Cohesion: 0.13
Nodes (7): {closure#7}(), EntrySpec, Expression, {closure#1}(), {closure#2}(), {closure#5}(), ReadBack

### Community 24 - "Symbol"
Cohesion: 0.04
Nodes (25): {closure#12}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#1}(), BuyAndHold, DonchianBreakout, MovingAverageTrend (+17 more)

### Community 25 - ".of"
Cohesion: 0.07
Nodes (77): {closure#2}(), InstrumentRules, {closure#1}(), AccountView, RejectionReason, BelowMinimum, Breaker, Cooldown (+69 more)

### Community 26 - "ClientOrderId"
Cohesion: 0.14
Nodes (8): ClientOrderId, OcoRequest, OrderRequest, OrderType, Limit, Market, StopMarket, TakeProfitMarket

### Community 27 - "Engine.php"
Cohesion: 0.12
Nodes (6): {closure#1}(), {closure#11}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#9}()

### Community 28 - "SimulatedPerpVenueTest.php"
Cohesion: 0.20
Nodes (26): {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#15}(), {closure#18}(), {closure#19}() (+18 more)

### Community 29 - "CompiledStrategy.php"
Cohesion: 0.07
Nodes (9): {closure#2}(), {closure#2}(), {closure#3}(), {closure#5}(), {closure#6}(), {closure#7}(), {closure#8}(), {closure#9}() (+1 more)

### Community 30 - "App\Market\Stream\PhrityTransport"
Cohesion: 0.12
Nodes (3): PhrityTransport, StreamFailed, FakeTransport

### Community 31 - ".timestamp"
Cohesion: 0.19
Nodes (9): {closure#2}(), {closure#4}(), {closure#2}(), DatabaseCandleFeed, {closure#1}(), Rows, {closure#1}(), {closure#2}() (+1 more)

### Community 32 - ".zero"
Cohesion: 0.12
Nodes (19): Cscv, DeflatedSharpe, {closure#3}(), closeTo(), {closure#1}(), {closure#11}(), {closure#12}(), {closure#13}() (+11 more)

### Community 33 - "Account"
Cohesion: 0.05
Nodes (13): OpenAccount, CreateAccount, StoreKeys, Account, AccountSnapshot, ReauthController, AccountScreen, HaltButton (+5 more)

### Community 34 - "Carbon\CarbonImmutable"
Cohesion: 0.12
Nodes (16): OpenInterest, PriceMinute, TradeFlow, {closure#1}(), {closure#10}(), {closure#11}(), {closure#2}(), {closure#3}() (+8 more)

### Community 35 - "App"
Cohesion: 0.09
Nodes (27): SendTelegram, App, {closure#1}(), {closure#2}(), {closure#3}(), {closure#1}(), {closure#2}(), {closure#4}() (+19 more)

### Community 37 - ".register"
Cohesion: 0.16
Nodes (13): {closure#11}(), {closure#14}(), {closure#15}(), {closure#16}(), {closure#4}(), {closure#6}(), {closure#7}(), {closure#8}() (+5 more)

### Community 39 - "FundingRate"
Cohesion: 0.10
Nodes (20): FeeSchedule, CostModel, CandleFeed, FundingRate, Backtest, {closure#2}(), Participant, RunDefinition (+12 more)

### Community 40 - "App\Strategy\Dsl\Document\DocumentChecker"
Cohesion: 0.06
Nodes (21): {closure#2}(), Argument, Call, DurationLiteral, Identifier, Lookback, NumberLiteral, OnTimeframe (+13 more)

### Community 41 - "ema-cross-scalp-long.strategy.yaml"
Cohesion: 0.06
Nodes (33): entries, long, exits, stop_loss, take_profit, time_stop, when, fails_when (+25 more)

### Community 42 - "Decimal"
Cohesion: 0.04
Nodes (53): {closure#6}(), {closure#2}(), RoundTrip, {closure#1}(), {closure#2}(), {closure#3}(), {closure#10}(), {closure#11}() (+45 more)

### Community 43 - "SimulatedVenue"
Cohesion: 0.06
Nodes (27): Income, IncomeKind, Funding, Liquidation, Liquidity, Maker, Taker, OrderStatus (+19 more)

### Community 44 - "OrderRole"
Cohesion: 0.25
Nodes (9): TrackedOrder, {closure#1}(), {closure#2}(), OrderBook, {closure#1}(), {closure#11}(), {closure#12}(), {closure#13}() (+1 more)

### Community 45 - ".walk"
Cohesion: 0.18
Nodes (18): CandleAggregator, CandleSequenceBroken, {closure#3}(), {closure#6}(), aggregate(), {closure#1}(), {closure#10}(), {closure#11}() (+10 more)

### Community 46 - "Illuminate\Database\Eloquent\Model"
Cohesion: 0.08
Nodes (9): McpCall, McpToken, PlainDecimal, Deployment, {closure#1}(), InstrumentFilter, StrategyGateReport, StrategyHoldout (+1 more)

### Community 47 - "package.json"
Cohesion: 0.15
Nodes (17): dependencies, codemirror@^6.0.2, @codemirror/lang-yaml@^6.1.3, @codemirror/language@^6.13.1, @lezer/highlight@^1.2.5, lightweight-charts@^5.2.1, optionalDependencies, @laravel/multiplex@^0.4.1 (+9 more)

### Community 48 - "Window"
Cohesion: 0.13
Nodes (6): {closure#54}(), DonchianChannel, Highest, Lowest, Stochastic, Window

### Community 49 - "Illuminate\Http\Client\Request"
Cohesion: 0.13
Nodes (17): {closure#3}(), {closure#13}(), {closure#5}(), {closure#12}(), {closure#2}(), {closure#1}(), {closure#2}(), {closure#3}() (+9 more)

### Community 50 - "ema-cross-scalp-short.strategy.yaml"
Cohesion: 0.06
Nodes (33): entries, short, exits, stop_loss, take_profit, time_stop, when, fails_when (+25 more)

### Community 51 - ".handle"
Cohesion: 0.15
Nodes (3): GridBacktest, ReadsGridOptions, StrategyGates

### Community 52 - "Side"
Cohesion: 0.20
Nodes (22): ExitReason, Flatten, Liquidation, Signal, Stop, Target, {closure#1}(), {closure#10}() (+14 more)

### Community 53 - "Laravel\Mcp\Response"
Cohesion: 0.09
Nodes (13): ChartTool, CompileStrategy, GetCandles, GetFunding, GetIndicators, GetJob, GetStrategy, {closure#1}() (+5 more)

### Community 54 - "postgres"
Cohesion: 0.10
Nodes (19): POSTGRES_DB, POSTGRES_PASSWORD, POSTGRES_USER, interval, retries, test, timeout, environment (+11 more)

### Community 55 - "MonteCarlo"
Cohesion: 0.16
Nodes (10): {closure#4}(), MonteCarlo, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}() (+2 more)

### Community 56 - "Price"
Cohesion: 0.16
Nodes (12): OrderAck, OrderRole, Entry, Exit, Flatten, Stop, Target, ProtectionPlan (+4 more)

### Community 57 - "donchian-breakout-short.strategy.yaml"
Cohesion: 0.07
Nodes (26): entries, short, default, range, step, default, range, step (+18 more)

### Community 58 - "IndicatorReferenceTest.php"
Cohesion: 0.06
Nodes (24): {closure#43}(), Bollinger, Change, Hma, Macd, Obv, RealizedVolatility, Roc (+16 more)

### Community 59 - "support.mjs"
Cohesion: 0.27
Nodes (9): @playwright/test, confirmed(), checkLayout(), EMAIL, freshCode(), PASSWORD, reauthenticate(), totp() (+1 more)

### Community 60 - "RunBacktestCommandTest.php"
Cohesion: 0.10
Nodes (28): BacktestRun, {closure#4}(), {closure#6}(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}() (+20 more)

### Community 61 - ".of"
Cohesion: 0.12
Nodes (24): {closure#41}(), {closure#44}(), {closure#45}(), {closure#46}(), {closure#47}(), {closure#48}(), {closure#1}(), {closure#10}() (+16 more)

### Community 62 - "BinanceArchive"
Cohesion: 0.11
Nodes (5): ArchivePeriod, Backfill, BackfillReport, BackfillMarketData, {closure#1}()

### Community 63 - "DecisionRecord"
Cohesion: 0.18
Nodes (5): DecisionLog, DecisionRecord, IntentRecord, {closure#1}(), DatabaseDecisionLog

### Community 64 - "HealthChecks"
Cohesion: 0.07
Nodes (9): CheckHealth, DiskUsage, {closure#1}(), HealthAlerts, HealthChecks, HealthResult, LocalDiskUsage, {closure#6}() (+1 more)

### Community 65 - "donchian-breakout.strategy.yaml"
Cohesion: 0.07
Nodes (26): entries, long, default, range, step, default, range, step (+18 more)

### Community 66 - ".run"
Cohesion: 0.10
Nodes (26): PerformanceMetrics, Report, btcMinutes(), {closure#1}(), {closure#2}(), referencePrice(), {closure#13}(), {closure#16}() (+18 more)

### Community 67 - "SimClock"
Cohesion: 0.09
Nodes (26): SimClock, WallClock, candleStore(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}() (+18 more)

### Community 68 - "App\Strategy\Dsl\Document\DocumentChecker"
Cohesion: 0.06
Nodes (35): {closure#1}(), {closure#3}(), {closure#4}(), {closure#9}(), ParameterSpec, {closure#1}(), {closure#2}(), Library (+27 more)

### Community 69 - "InvalidArgumentException"
Cohesion: 0.07
Nodes (8): {closure#1}(), {closure#1}(), {closure#1}(), {closure#5}(), {closure#7}(), {closure#1}(), {closure#3}(), Quantity

### Community 70 - "Carbon\CarbonImmutable"
Cohesion: 0.13
Nodes (5): {closure#4}(), EngineRecord, Engine, {closure#3}(), {closure#4}()

### Community 71 - "BinanceRestClientTest.php"
Cohesion: 0.27
Nodes (11): binanceClient(), {closure#1}(), {closure#11}(), {closure#13}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}() (+3 more)

### Community 72 - "SimulationQueue"
Cohesion: 0.16
Nodes (4): RunBacktest, RunGrid, SimulationTool, SimulationQueue

### Community 73 - "scripts"
Cohesion: 0.15
Nodes (13): scripts, analyse, dev, format, mutate, post-autoload-dump, post-update-cmd, pre-package-uninstall (+5 more)

### Community 74 - "ma-trend-short.strategy.yaml"
Cohesion: 0.07
Nodes (26): entries, short, exits, stop_loss, when, fails_when, family, default (+18 more)

### Community 76 - ".sqrt"
Cohesion: 0.13
Nodes (20): Statistics, Gates, {closure#3}(), brokenGate(), {closure#1}(), {closure#3}(), {closure#4}(), {closure#6}() (+12 more)

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
Nodes (15): Normal, DecimalMath, {closure#10}(), {closure#1}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}() (+7 more)

### Community 82 - "CompiledStrategyTest.php"
Cohesion: 0.20
Nodes (15): PositionView, StrategyContext, {closure#18}(), {closure#37}(), aggregatedBars(), {closure#11}(), {closure#13}(), {closure#16}() (+7 more)

### Community 83 - "App\Support\Number\Decimal"
Cohesion: 0.07
Nodes (53): {closure#8}(), PerpetualView, {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}() (+45 more)

### Community 84 - "pint/composer.json"
Cohesion: 0.20
Nodes (9): config, sort-packages, description, license, name, require, laravel/pint, php (+1 more)

### Community 85 - "HealthTest.php"
Cohesion: 0.11
Nodes (21): {closure#4}(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#5}(), {closure#6}(), {closure#7}(), reportAccount() (+13 more)

### Community 87 - "Timeframe"
Cohesion: 0.08
Nodes (18): {closure#4}(), CarryTemplate, {closure#2}(), ExitSpec, {closure#8}(), Timeframe, FifteenMinutes, FiveMinutes (+10 more)

### Community 88 - "require-dev"
Cohesion: 0.22
Nodes (9): require-dev, fakerphp/faker, larastan/larastan, laravel/pail, laravel/pao, mockery/mockery, nunomaduro/collision, pestphp/pest (+1 more)

### Community 89 - "scripts-descriptions"
Cohesion: 0.25
Nodes (8): scripts-descriptions, analyse, format, mutate, test, test:network, test:testnet, tools:install

### Community 90 - "Ledger"
Cohesion: 0.31
Nodes (5): Ledger, LedgerViolation, {closure#19}(), {closure#12}(), {closure#14}()

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
Cohesion: 0.27
Nodes (3): {closure#1}(), {closure#2}(), StrategyVersion

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

### Community 103 - "StrategyGates.php"
Cohesion: 0.07
Nodes (21): {closure#2}(), {closure#3}(), {closure#6}(), {closure#8}(), {closure#2}(), GridPart, {closure#1}(), {closure#2}() (+13 more)

### Community 104 - "uses"
Cohesion: 0.21
Nodes (12): steps, name, runs-on, steps, frontend, lint, name, runs-on (+4 more)

### Community 105 - "SettingsScreenTest.php"
Cohesion: 0.16
Nodes (13): ExchangeKey, {closure#10}(), {closure#11}(), {closure#13}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}() (+5 more)

### Community 106 - "devDependencies"
Cohesion: 0.18
Nodes (12): devDependencies, concurrently@^10.0.3, laravel-vite-plugin@^3.1, @playwright/test@1.63.0, tailwindcss@^4.0.0, @tailwindcss/vite@^4.0.0, vite@^8.0.0, concurrently (+4 more)

### Community 108 - "BinanceVenueTest.php"
Cohesion: 0.11
Nodes (16): {closure#2}(), BinanceVenue, BinanceApiError, BinanceUnreachable, SignedBinanceClient, binanceVenue(), {closure#1}(), {closure#2}() (+8 more)

### Community 117 - "Venue"
Cohesion: 0.13
Nodes (5): Venue, ReconciliationMismatch, {closure#12}(), {closure#32}(), {closure#34}()

### Community 118 - "App\Agents\Mcp\TradingServer"
Cohesion: 0.17
Nodes (3): DslReference, ListInstruments, TradingServer

### Community 119 - "CandleFeedTest.php"
Cohesion: 0.34
Nodes (13): {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#2}(), {closure#4}(), {closure#5}() (+5 more)

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
Cohesion: 0.35
Nodes (10): archiveBytes(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}() (+2 more)

### Community 124 - "FortifyServiceProvider.php"
Cohesion: 0.05
Nodes (11): User, AppServiceProvider, {closure#7}(), {closure#3}(), {closure#4}(), FortifyServiceProvider, {closure#1}(), {closure#2}() (+3 more)

### Community 126 - "BinanceArchive"
Cohesion: 0.08
Nodes (10): BinanceArchive, {closure#1}(), {closure#2}(), EpochTime, ExchangeNumber, {closure#1}(), {closure#2}(), {closure#3}() (+2 more)

### Community 127 - "App\Market\Models\Instrument"
Cohesion: 0.13
Nodes (13): {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}() (+5 more)

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
Nodes (9): AuthenticateMcpToken, Notifier, NotificationSettings, SettingSource, Env, None, TelegramClient, TelegramNotifier (+1 more)

### Community 133 - "EnginePerpTest.php"
Cohesion: 0.11
Nodes (24): EngineMode, Backtest, Live, Paper, Testnet, InMemoryDecisionLog, {closure#1}(), {closure#2}() (+16 more)

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
Cohesion: 0.13
Nodes (16): MinuteCandles, BreakerEvent, MarketBreakers, RiskEvent, RiskEventKind, DailyLossHalt, DrawdownFlatten, DrawdownHalt (+8 more)

### Community 141 - "MarketType"
Cohesion: 0.08
Nodes (22): BinanceRequestFailed, BinanceRestClient, {closure#1}(), LocationRestricted, RateLimited, RequestFailed, SyncInstruments, InstrumentDefinition (+14 more)

### Community 142 - "PaperEngineTest.php"
Cohesion: 0.16
Nodes (17): AccountEquity, AccountTrade, JournalEntry, {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}() (+9 more)

### Community 143 - "Balance"
Cohesion: 0.32
Nodes (3): {closure#2}(), Balance, balances()

### Community 145 - ".of"
Cohesion: 0.11
Nodes (23): {closure#1}(), AssetMismatch, {closure#2}(), {closure#1}(), usdt(), {closure#11}(), {closure#7}(), {closure#1}() (+15 more)

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

### Community 174 - "StrategyLibrary"
Cohesion: 0.30
Nodes (3): ListStrategies, StrategiesScreen, StrategyLibrary

### Community 175 - "AppServiceProvider.php"
Cohesion: 0.07
Nodes (13): RunEngine, BinanceEndpoints, Sleeper, SystemSleeper, {closure#1}(), Heartbeats, CandleStream, StreamTransport (+5 more)

### Community 176 - "ChartScreen.php"
Cohesion: 0.07
Nodes (21): ChartScreen, {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), FunctionSpec, Group, Exit (+13 more)

### Community 181 - "RiskPolicy"
Cohesion: 0.12
Nodes (15): RiskProfile, Account, Benchmark, RiskPolicy, {closure#1}(), {closure#2}(), {closure#3}(), documentedLimits() (+7 more)

### Community 183 - "SimulationJobsTest.php"
Cohesion: 0.21
Nodes (5): {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), savedEmaCross()

### Community 184 - "OrderManagerTest.php"
Cohesion: 0.30
Nodes (14): btcUsdt(), {closure#10}(), {closure#15}(), {closure#16}(), {closure#2}(), {closure#4}(), {closure#5}(), {closure#6}() (+6 more)

### Community 189 - ".parse"
Cohesion: 0.17
Nodes (7): Json, KlineMessage, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), streamLines()

### Community 190 - "BarIndicator"
Cohesion: 0.11
Nodes (6): {closure#45}(), BarIndicator, Keltner, Natr, Supertrend, Vwma

### Community 193 - "StrategyIdentity"
Cohesion: 0.18
Nodes (6): {closure#3}(), StrategyIdentity, RenamedStrategy, {closure#1}(), {closure#2}(), {closure#3}()

### Community 198 - "RulesHistory"
Cohesion: 0.25
Nodes (9): RulesHistory, {closure#2}(), {closure#4}(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), rules() (+1 more)

### Community 199 - ".parse"
Cohesion: 0.10
Nodes (24): {closure#2}(), {closure#3}(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}() (+16 more)

### Community 202 - "VenuePosition"
Cohesion: 0.32
Nodes (4): VenuePosition, {closure#1}(), SimulatedPosition, {closure#13}()

### Community 205 - "2026_11_09_000015_create_scalping_tables.php"
Cohesion: 0.33
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

## Knowledge Gaps
- **142 isolated node(s):** `BelowMinimum`, `Breaker`, `Cooldown`, `DuplicateIntent`, `EntryWorking` (+137 more)
  These have ≤1 connection - possible missing edges. (Counts symbols only; 894 node(s) total have ≤1 connection when file, concept and rationale nodes are included.)
- **53 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `Decimal` connect `Decimal` to `RuntimeException`, `EngineTest.php`, `CanonicalJson`, `Asset`, `EnginePerpTest.php`, `DocumentChecker`, `.times`, `.parse`, `Side`, `.measure`, `Candle`, `MarketType`, `Carbon\CarbonImmutable`, `Balance`, `RiskPolicy`, `.of`, `AccountRuntime`, `Node`, `IndicatorsTest.php`, `.push`, `LedgerTest.php`, `Fill`, `Symbol`, `.of`, `ClientOrderId`, `Engine.php`, `ReadBack`, `CompiledStrategy.php`, `.zero`, `Account`, `PreparesBacktests.php`, `.isGreaterThan`, `FundingRate`, `App\Strategy\Dsl\Document\DocumentChecker`, `SimulatedVenue`, `OrderRole`, `.walk`, `ChartScreen.php`, `Window`, `.handle`, `Side`, `RiskPolicy`, `MonteCarlo`, `Price`, `OrderManagerTest.php`, `IndicatorReferenceTest.php`, `RunBacktestCommandTest.php`, `.of`, `BinanceArchive`, `BarIndicator`, `.run`, `SimClock`, `App\Strategy\Dsl\Document\DocumentChecker`, `InvalidArgumentException`, `Carbon\CarbonImmutable`, `.parse`, `RulesHistory`, `VenuePosition`, `FundingCarry`, `.__construct`, `.sqrt`, `DecimalMath`, `CompiledStrategyTest.php`, `App\Support\Number\Decimal`, `Timeframe`, `Ledger`, `.fromStrings`, `ForwardingVenue`, `StrategyGates.php`, `BinanceVenueTest.php`, `Venue`, `CandleFeedTest.php`, `ChartSeriesTest.php`, `App\Market\Models\Instrument`?**
  _High betweenness centrality (0.163) - this node is a cross-community bridge._
- **What connects `BelowMinimum`, `Breaker`, `Cooldown` to the rest of the system?**
  _142 weakly-connected nodes found - possible missing edges._
- **Should `RuntimeException` be split into smaller, more focused modules?**
  _Cohesion score 0.08258258258258258 - nodes in this community are weakly interconnected._
- **Why does `Symbol` connect `Symbol` to `RuntimeException`, `EngineTest.php`, `CanonicalJson`, `Asset`, `EnginePerpTest.php`, `DocumentChecker`, `.parse`, `Side`, `Instrument`, `.measure`, `MarketType`, `ExchangeVerificationTest.php`, `RiskPolicy`, `AccountRuntime`, `ScreensTest.php`, `LedgerTest.php`, `Fill`, `.of`, `ClientOrderId`, `Engine.php`, `CompiledStrategy.php`, `Account`, `.register`, `.isGreaterThan`, `FundingRate`, `App\Strategy\Dsl\Document\DocumentChecker`, `Decimal`, `SimulatedVenue`, `OrderRole`, `AppServiceProvider.php`, `ChartScreen.php`, `Illuminate\Http\Client\Request`, `.handle`, `Side`, `Laravel\Mcp\Response`, `Price`, `OrderManagerTest.php`, `RunBacktestCommandTest.php`, `BinanceArchive`, `DecisionRecord`, `.run`, `SimClock`, `InvalidArgumentException`, `Carbon\CarbonImmutable`, `RulesHistory`, `.parse`, `VenuePosition`, `FundingCarry`, `CompiledStrategyTest.php`, `Timeframe`, `Ledger`, `ForwardingVenue`, `StrategyGates.php`, `BinanceVenueTest.php`, `Venue`, `CandleFeedTest.php`, `ChartSeriesTest.php`, `App\Market\Models\Instrument`?**
  _High betweenness centrality (0.051) - this node is a cross-community bridge._
- **Should `EngineTest.php` be split into smaller, more focused modules?**
  _Cohesion score 0.1119177253478524 - nodes in this community are weakly interconnected._
- **Why does `Timeframe` connect `Timeframe` to `EngineTest.php`, `EnginePerpTest.php`, `DocumentChecker`, `.parse`, `CompiledStrategy.php`, `Side`, `Instrument`, `Candle`, `MarketType`, `Carbon\CarbonImmutable`, `RiskPolicy`, `Node`, `IndicatorsTest.php`, `ReadBack`, `Symbol`, `CompiledStrategy.php`, `PreparesBacktests.php`, `App\Strategy\Dsl\Document\DocumentChecker`, `Decimal`, `SimulatedVenue`, `.walk`, `ChartScreen.php`, `Illuminate\Http\Client\Request`, `Laravel\Mcp\Response`, `OrderManagerTest.php`, `IndicatorReferenceTest.php`, `.parse`, `SimClock`, `App\Strategy\Dsl\Document\DocumentChecker`, `InvalidArgumentException`, `FundingCarry`, `.__construct`, `CompiledStrategyTest.php`, `HealthTest.php`, `.fromStrings`, `StrategyGates.php`, `CandleFeedTest.php`, `ChartSeriesTest.php`?**
  _High betweenness centrality (0.029) - this node is a cross-community bridge._
- **Should `DslError` be split into smaller, more focused modules?**
  _Cohesion score 0.09693877551020408 - nodes in this community are weakly interconnected._