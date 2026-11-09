# Graph Report - trading-platform  (2026-11-09)

## Corpus Check
- 562 files · ~228,083 words
- Verdict: corpus is large enough that graph structure adds value.
- Unclassified: 93 file(s) not represented in the graph (top: .zip 34, .CHECKSUM 34, (none) 19)

## Summary
- 4436 nodes · 15123 edges · 184 communities (134 shown, 50 thin omitted)
- Extraction: 92% EXTRACTED · 8% INFERRED · 0% AMBIGUOUS · INFERRED: 1199 edges (avg confidence: 0.85)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `eeb223da`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- InstrumentRules
- EngineTest.php
- .of
- DslError
- Price
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
- App\Execution\Models\Account
- ScreensTest.php
- LedgerTest.php
- Illuminate\Database\Schema\Blueprint
- generate.py
- ReadBack
- StrategyIdentity
- .of
- RuntimeException
- App\Execution\Venues\BinanceVenue
- SimulatedPerpVenueTest.php
- Strategy
- PhrityTransport
- Illuminate\Database\ConnectionInterface
- .zero
- Account
- App\Market\Archive\BinanceArchive
- BinanceVenueTest.php
- PreparesBacktests.php
- SimClock
- RiskEventKind
- FundingRate
- Position
- ema-cross-scalp-long.strategy.yaml
- Decimal
- SimulatedVenue
- OrderRole
- .walk
- Illuminate\Database\Eloquent\Model
- package.json
- ForwardingVenue
- BinanceRestClientTest.php
- ema-cross-scalp-short.strategy.yaml
- .handle
- Side
- Laravel\Mcp\Response
- postgres
- MonteCarlo
- OrderManagerTest.php
- donchian-breakout-short.strategy.yaml
- IndicatorReferenceTest.php
- support.mjs
- RunBacktestCommandTest.php
- .__construct
- BinanceArchive
- StrategyIdentity
- AppServiceProvider.php
- donchian-breakout.strategy.yaml
- .run
- CandleRepositoryTest.php
- App\Strategy\Dsl\Document\DocumentChecker
- AccountRuntime.php
- Carbon\CarbonImmutable
- UserFactory.php
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
- PolicyLimits
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
- QualityReport
- StrategyGates.php
- uses
- SettingsScreenTest.php
- devDependencies
- BinanceVenueTest.php
- ClientOrderId
- TradingServer.php
- CandleFeedTest.php
- buy-and-hold.strategy.yaml
- ChartSeriesTest.php
- TokensTest.php
- ArchiveImportTest.php
- AppServiceProvider.php
- Illuminate\Support\Facades\Schema
- CompiledStrategy
- StrategyLimits
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
- .account
- Percent
- 2026_11_09_000009_create_exchange_keys_table.php
- .of
- DocumentCheckerTest.php
- scripts
- 0001_01_01_000002_create_jobs_table.php
- fake-telegram.mjs
- DirectionalMovement
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
- Quantity
- GlobalsTest.php
- .parse
- ParamKind
- VenuePosition
- 2026_11_09_000012_create_account_results_tables.php
- Illuminate\Database\Migrations\Migration
- 2026_11_09_000013_add_daily_report_to_notification_settings.php

## God Nodes (most connected - your core abstractions)
1. `Decimal` - 597 edges
2. `Symbol` - 251 edges
3. `Side` - 215 edges
4. `Candle` - 170 edges
5. `Timeframe` - 155 edges
6. `Price` - 116 edges
7. `Clock` - 112 edges
8. `Position` - 96 edges
9. `App` - 95 edges
10. `MarketType` - 87 edges

## Surprising Connections (you probably didn't know these)
- `{closure#6}()` --calls--> `App`  [INFERRED]
  tests/Feature/Notifications/DailyReportTest.php → app/Notifications/SettingSource.php
- `{closure#8}()` --calls--> `App`  [INFERRED]
  tests/Feature/Notifications/HealthTest.php → app/Notifications/SettingSource.php
- `{closure#8}()` --calls--> `App`  [INFERRED]
  tests/Feature/Web/SettingsScreenTest.php → app/Notifications/SettingSource.php
- `{closure#13}()` --calls--> `App`  [INFERRED]
  tests/Feature/Web/SettingsScreenTest.php → app/Notifications/SettingSource.php
- `{closure#3}()` --calls--> `App`  [INFERRED]
  tests/Feature/Network/BinancePublicDataTest.php → app/Notifications/SettingSource.php

## Import Cycles
- None detected.

## Communities (184 total, 50 thin omitted)

### Community 0 - "InstrumentRules"
Cohesion: 0.08
Nodes (30): InstrumentRules, AccountView, RejectionReason, BelowMinimum, Breaker, Cooldown, DuplicateIntent, EntryWorking (+22 more)

### Community 1 - "EngineTest.php"
Cohesion: 0.11
Nodes (36): EngineCommand, ScriptedStrategy, {closure#1}(), {closure#10}(), {closure#13}(), {closure#14}(), {closure#16}(), {closure#17}() (+28 more)

### Community 2 - ".of"
Cohesion: 0.07
Nodes (36): Balance, FeeSchedule, CostModel, RunDefinition, {closure#2}(), balances(), {closure#40}(), {closure#41}() (+28 more)

### Community 3 - "DslError"
Cohesion: 0.09
Nodes (16): {closure#1}(), Identifier, {closure#1}(), {closure#5}(), {closure#8}(), DslError, Position, YamlList (+8 more)

### Community 4 - "Price"
Cohesion: 0.13
Nodes (5): Marks, PerpPosition, Price, {closure#25}(), {closure#9}()

### Community 5 - ".parse"
Cohesion: 0.11
Nodes (35): baselinePairs(), {closure#17}(), carryDecisions(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}() (+27 more)

### Community 7 - ".times"
Cohesion: 0.11
Nodes (5): {closure#2}(), {closure#3}(), DonchianBreakout, VolumeProfile, Perpetual

### Community 8 - "CompiledStrategy.php"
Cohesion: 0.14
Nodes (3): {closure#33}(), Compiler, Program

### Community 9 - "Side"
Cohesion: 0.18
Nodes (42): Side, Buy, Sell, btc(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}() (+34 more)

### Community 10 - "Instrument"
Cohesion: 0.06
Nodes (20): {closure#3}(), BackfillStep, CandleRepository, CandleSource, Archive, Rest, Stream, FundingRate (+12 more)

### Community 11 - ".measure"
Cohesion: 0.14
Nodes (9): EquityTracker, Performance, {closure#1}(), {closure#2}(), {closure#3}(), {closure#5}(), {closure#6}(), {closure#8}() (+1 more)

### Community 12 - "Candle"
Cohesion: 0.10
Nodes (36): {closure#1}(), {closure#2}(), {closure#1}(), {closure#3}(), Candle, {closure#9}(), {closure#1}(), {closure#2}() (+28 more)

### Community 13 - "ExchangeVerificationTest.php"
Cohesion: 0.06
Nodes (53): SendTelegram, App, {closure#1}(), {closure#2}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#1}() (+45 more)

### Community 14 - "IndicatorsTest.php"
Cohesion: 0.09
Nodes (22): Atr, Donchian, Ema, Sma, {closure#5}(), bar(), {closure#1}(), {closure#10}() (+14 more)

### Community 15 - "RiskPolicy"
Cohesion: 0.14
Nodes (15): RiskProfile, Account, Benchmark, RiskPolicy, RiskState, {closure#1}(), {closure#2}(), {closure#3}() (+7 more)

### Community 16 - "Node"
Cohesion: 0.11
Nodes (21): Binary, Lexer, Parser, Token, TokenKind, Colon, Comma, Dot (+13 more)

### Community 17 - "Clock"
Cohesion: 0.05
Nodes (24): CreateMcpToken, RevokeMcpToken, AuthenticateMcpToken, Tokens, OpenAccount, CreateAccount, FlattenEngine, HaltEngine (+16 more)

### Community 18 - "App\Execution\Models\Account"
Cohesion: 0.17
Nodes (3): AccountSnapshot, SendDailyReport, DailyReport

### Community 19 - "ScreensTest.php"
Cohesion: 0.07
Nodes (20): User, {closure#1}(), {closure#1}(), {closure#2}(), {closure#3}(), fakeTestnet(), testnetAccount(), {closure#1}() (+12 more)

### Community 20 - "LedgerTest.php"
Cohesion: 0.16
Nodes (31): Ledger, LedgerViolation, balanceOf(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}() (+23 more)

### Community 21 - "Illuminate\Database\Schema\Blueprint"
Cohesion: 0.19
Nodes (10): {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#1}(), {closure#2}(), {closure#3}() (+2 more)

### Community 22 - "generate.py"
Cohesion: 0.08
Nodes (30): atr(), bars(), bb(), change(), dmi(), donchian(), ema(), highest() (+22 more)

### Community 23 - "ReadBack"
Cohesion: 0.09
Nodes (11): CarryTemplate, ExitSpec, StrategySpec, ReadBack, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}() (+3 more)

### Community 24 - "StrategyIdentity"
Cohesion: 0.04
Nodes (22): {closure#12}(), {closure#1}(), {closure#1}(), BuyAndHold, MovingAverageTrend, BuildableStrategy, Close, EntryOrder (+14 more)

### Community 25 - ".of"
Cohesion: 0.26
Nodes (36): account(), accountPolicy(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#2}(), {closure#21}() (+28 more)

### Community 26 - "RuntimeException"
Cohesion: 0.10
Nodes (10): ArchiveMissing, ChecksumMismatch, SelectsInstruments, {closure#1}(), StreamCandles, VerifyMarketData, Universe, VerificationSource (+2 more)

### Community 27 - "App\Execution\Venues\BinanceVenue"
Cohesion: 0.13
Nodes (10): Income, IncomeKind, Funding, Liquidation, UpdateStream, {closure#3}(), {closure#1}(), {closure#1}() (+2 more)

### Community 28 - "SimulatedPerpVenueTest.php"
Cohesion: 0.21
Nodes (24): {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#15}(), {closure#18}(), {closure#19}() (+16 more)

### Community 29 - "Strategy"
Cohesion: 0.16
Nodes (19): {closure#3}(), Strategy, baselineDocument(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#14}(), {closure#15}() (+11 more)

### Community 30 - "PhrityTransport"
Cohesion: 0.09
Nodes (11): PhrityTransport, StreamFailed, StreamTransport, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}() (+3 more)

### Community 31 - "Illuminate\Database\ConnectionInterface"
Cohesion: 0.12
Nodes (8): {closure#2}(), {closure#4}(), DatabaseCandleFeed, {closure#1}(), Heartbeats, Rows, QualityCheck, {closure#10}()

### Community 32 - ".zero"
Cohesion: 0.09
Nodes (21): Cscv, DeflatedSharpe, Normal, Vwap, closeTo(), {closure#1}(), {closure#10}(), {closure#11}() (+13 more)

### Community 33 - "Account"
Cohesion: 0.06
Nodes (16): EngineMode, Backtest, Live, Paper, Testnet, Account, ReauthController, AccountScreen (+8 more)

### Community 34 - "App\Market\Archive\BinanceArchive"
Cohesion: 0.11
Nodes (10): {closure#1}(), {closure#2}(), EpochTime, ExchangeNumber, {closure#5}(), {closure#1}(), {closure#2}(), {closure#3}() (+2 more)

### Community 35 - "BinanceVenueTest.php"
Cohesion: 0.14
Nodes (17): BinanceUnreachable, binanceVenue(), {closure#1}(), {closure#3}(), {closure#4}(), {closure#6}(), {closure#7}(), {closure#9}() (+9 more)

### Community 36 - "PreparesBacktests.php"
Cohesion: 0.06
Nodes (16): FundingRateRepository, StoredInstruments, {closure#1}(), GridPart, {closure#2}(), {closure#5}(), {closure#7}(), {closure#8}() (+8 more)

### Community 37 - "SimClock"
Cohesion: 0.07
Nodes (27): SimClock, WallClock, {closure#1}(), {closure#2}(), {closure#3}(), {closure#11}(), {closure#14}(), {closure#15}() (+19 more)

### Community 38 - "RiskEventKind"
Cohesion: 0.24
Nodes (6): RiskEvent, RiskEventKind, DailyLossHalt, DrawdownFlatten, DrawdownHalt, WeeklyLossHalt

### Community 39 - "FundingRate"
Cohesion: 0.08
Nodes (22): CandleFeed, RulesHistory, Backtest, ReportBuilder, {closure#1}(), {closure#2}(), {closure#3}(), {closure#5}() (+14 more)

### Community 40 - "Position"
Cohesion: 0.05
Nodes (33): Argument, BoolLiteral, Call, DurationLiteral, Lookback, Node, NumberLiteral, OnTimeframe (+25 more)

### Community 41 - "ema-cross-scalp-long.strategy.yaml"
Cohesion: 0.06
Nodes (33): entries, long, exits, stop_loss, take_profit, time_stop, when, fails_when (+25 more)

### Community 42 - "Decimal"
Cohesion: 0.05
Nodes (41): {closure#6}(), {closure#1}(), RoundTrip, {closure#1}(), {closure#2}(), {closure#3}(), {closure#10}(), {closure#5}() (+33 more)

### Community 43 - "SimulatedVenue"
Cohesion: 0.09
Nodes (14): Liquidity, Maker, Taker, OrderStatus, Cancelled, Expired, Filled, New (+6 more)

### Community 44 - "OrderRole"
Cohesion: 0.10
Nodes (20): OrderRole, Entry, Exit, Flatten, Stop, Target, ProtectionPlan, TrackedOrder (+12 more)

### Community 45 - ".walk"
Cohesion: 0.15
Nodes (19): CandleAggregator, CandleSequenceBroken, {closure#3}(), {closure#6}(), CandleFactory, aggregate(), {closure#1}(), {closure#10}() (+11 more)

### Community 46 - "Illuminate\Database\Eloquent\Model"
Cohesion: 0.05
Nodes (30): {closure#1}(), McpCall, McpToken, {closure#5}(), AccountEquity, AccountTrade, PlainDecimal, Deployment (+22 more)

### Community 47 - "package.json"
Cohesion: 0.15
Nodes (17): dependencies, codemirror@^6.0.2, @codemirror/lang-yaml@^6.1.3, @codemirror/language@^6.13.1, @lezer/highlight@^1.2.5, lightweight-charts@^5.2.1, optionalDependencies, @laravel/multiplex@^0.4.1 (+9 more)

### Community 48 - "ForwardingVenue"
Cohesion: 0.15
Nodes (4): ForwardingVenue, {closure#22}(), {closure#49}(), OverReportingVenue

### Community 49 - "BinanceRestClientTest.php"
Cohesion: 0.18
Nodes (11): binanceClient(), {closure#1}(), {closure#11}(), {closure#13}(), {closure#3}(), {closure#5}(), {closure#6}(), {closure#7}() (+3 more)

### Community 50 - "ema-cross-scalp-short.strategy.yaml"
Cohesion: 0.06
Nodes (33): entries, short, exits, stop_loss, take_profit, time_stop, when, fails_when (+25 more)

### Community 51 - ".handle"
Cohesion: 0.17
Nodes (3): GridBacktest, ReadsGridOptions, StrategyGates

### Community 52 - "Side"
Cohesion: 0.13
Nodes (24): ExitReason, Flatten, Liquidation, Signal, Stop, Target, {closure#1}(), {closure#10}() (+16 more)

### Community 53 - "Laravel\Mcp\Response"
Cohesion: 0.10
Nodes (11): ChartTool, CompileStrategy, GetCandles, GetFunding, GetIndicators, GetStrategy, ListTrials, SaveStrategy (+3 more)

### Community 54 - "postgres"
Cohesion: 0.10
Nodes (19): POSTGRES_DB, POSTGRES_PASSWORD, POSTGRES_USER, interval, retries, test, timeout, environment (+11 more)

### Community 55 - "MonteCarlo"
Cohesion: 0.17
Nodes (11): {closure#4}(), {closure#5}(), MonteCarlo, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}() (+3 more)

### Community 56 - "OrderManagerTest.php"
Cohesion: 0.20
Nodes (16): Fill, Position, btcUsdt(), {closure#10}(), {closure#15}(), {closure#16}(), {closure#2}(), {closure#4}() (+8 more)

### Community 57 - "donchian-breakout-short.strategy.yaml"
Cohesion: 0.07
Nodes (26): entries, short, default, range, step, default, range, step (+18 more)

### Community 58 - "IndicatorReferenceTest.php"
Cohesion: 0.04
Nodes (35): {closure#34}(), {closure#36}(), BarIndicator, Bollinger, Change, DonchianChannel, Highest, Hma (+27 more)

### Community 59 - "support.mjs"
Cohesion: 0.27
Nodes (9): @playwright/test, confirmed(), checkLayout(), EMAIL, freshCode(), PASSWORD, reauthenticate(), totp() (+1 more)

### Community 60 - "RunBacktestCommandTest.php"
Cohesion: 0.07
Nodes (32): {closure#4}(), {closure#6}(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}() (+24 more)

### Community 62 - "BinanceArchive"
Cohesion: 0.10
Nodes (5): ArchivePeriod, BinanceArchive, Backfill, BackfillReport, BackfillMarketData

### Community 63 - "StrategyIdentity"
Cohesion: 0.20
Nodes (5): StrategyIdentity, RenamedStrategy, {closure#1}(), {closure#2}(), {closure#3}()

### Community 64 - "AppServiceProvider.php"
Cohesion: 0.04
Nodes (18): AccountRuntime, RunEngine, Notifier, ReplayDivergence, SystemSleeper, CheckHealth, DiskUsage, {closure#1}() (+10 more)

### Community 65 - "donchian-breakout.strategy.yaml"
Cohesion: 0.07
Nodes (26): entries, long, default, range, step, default, range, step (+18 more)

### Community 66 - ".run"
Cohesion: 0.09
Nodes (30): {closure#2}(), Participant, PerformanceMetrics, Report, btcMinutes(), {closure#1}(), {closure#2}(), {closure#3}() (+22 more)

### Community 67 - "CandleRepositoryTest.php"
Cohesion: 0.28
Nodes (11): candleStore(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}() (+3 more)

### Community 68 - "App\Strategy\Dsl\Document\DocumentChecker"
Cohesion: 0.05
Nodes (47): {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}() (+39 more)

### Community 69 - "AccountRuntime.php"
Cohesion: 0.10
Nodes (5): {closure#1}(), {closure#2}(), {closure#1}(), {closure#1}(), Period

### Community 70 - "Carbon\CarbonImmutable"
Cohesion: 0.07
Nodes (11): {closure#4}(), DecisionLog, DecisionRecord, EngineRecord, IntentRecord, Engine, {closure#1}(), DatabaseDecisionLog (+3 more)

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
Cohesion: 0.10
Nodes (23): Statistics, Gates, {closure#1}(), {closure#2}(), {closure#3}(), brokenGate(), {closure#1}(), {closure#3}() (+15 more)

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
Cohesion: 0.16
Nodes (13): DecimalMath, InvalidNumber, {closure#1}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#2}() (+5 more)

### Community 82 - "CompiledStrategyTest.php"
Cohesion: 0.19
Nodes (15): aggregatedBars(), {closure#1}(), {closure#11}(), {closure#13}(), {closure#16}(), {closure#17}(), {closure#2}(), {closure#3}() (+7 more)

### Community 83 - "App\Support\Number\Decimal"
Cohesion: 0.07
Nodes (41): {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#15}(), {closure#16}() (+33 more)

### Community 84 - "pint/composer.json"
Cohesion: 0.20
Nodes (9): config, sort-packages, description, license, name, require, laravel/pint, php (+1 more)

### Community 85 - "HealthTest.php"
Cohesion: 0.14
Nodes (18): {closure#1}(), {closure#2}(), {closure#3}(), {closure#6}(), {closure#7}(), reportAccount(), reportEvent(), reportTrade() (+10 more)

### Community 86 - "RunSimulation.php"
Cohesion: 0.16
Nodes (3): RunSimulation, {closure#4}(), {closure#5}()

### Community 87 - "Timeframe"
Cohesion: 0.08
Nodes (20): InvalidCandle, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#2}(), {closure#8}(), Timeframe (+12 more)

### Community 88 - "require-dev"
Cohesion: 0.22
Nodes (9): require-dev, fakerphp/faker, larastan/larastan, laravel/pail, laravel/pao, mockery/mockery, nunomaduro/collision, pestphp/pest (+1 more)

### Community 89 - "scripts-descriptions"
Cohesion: 0.25
Nodes (8): scripts-descriptions, analyse, format, mutate, test, test:network, test:testnet, tools:install

### Community 90 - "PolicyLimits"
Cohesion: 0.12
Nodes (8): StrategiesScreen, InstrumentDirectory, CompileStrategy, StrategyLibrary, CheckResult, PolicyLimits, SaveResult, StrategyRepository

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

### Community 96 - "StrategyVersion"
Cohesion: 0.27
Nodes (4): {closure#1}(), ListStrategies, {closure#2}(), StrategyVersion

### Community 97 - "jobs"
Cohesion: 0.25
Nodes (8): name, runs-on, name, runs-on, steps, jobs, analyse, audit

### Community 98 - ".parse"
Cohesion: 0.39
Nodes (6): AstPrinter, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), sexpr()

### Community 99 - "psr-4"
Cohesion: 0.40
Nodes (5): autoload, psr-4, App\\, Database\\Factories\\, Database\\Seeders\\

### Community 100 - "require"
Cohesion: 0.25
Nodes (8): require, laravel/fortify, laravel/framework, laravel/mcp, laravel/tinker, livewire/livewire, php, phrity/websocket

### Community 103 - "StrategyGates.php"
Cohesion: 0.05
Nodes (31): {closure#11}(), {closure#12}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#6}(), {closure#8}(), {closure#2}() (+23 more)

### Community 104 - "uses"
Cohesion: 0.21
Nodes (12): steps, name, runs-on, steps, frontend, lint, name, runs-on (+4 more)

### Community 105 - "SettingsScreenTest.php"
Cohesion: 0.23
Nodes (10): {closure#10}(), {closure#11}(), {closure#13}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#8}() (+2 more)

### Community 106 - "devDependencies"
Cohesion: 0.18
Nodes (12): devDependencies, concurrently@^10.0.3, laravel-vite-plugin@^3.1, @playwright/test@1.63.0, tailwindcss@^4.0.0, @tailwindcss/vite@^4.0.0, vite@^8.0.0, concurrently (+4 more)

### Community 108 - "BinanceVenueTest.php"
Cohesion: 0.10
Nodes (16): {closure#2}(), OrderType, Limit, Market, StopMarket, TakeProfitMarket, BinanceVenue, {closure#1}() (+8 more)

### Community 117 - "ClientOrderId"
Cohesion: 0.08
Nodes (15): Venue, ClientOrderId, OcoRequest, OrderAck, OrderRequest, OrderUpdate, VenueFill, VenueOrder (+7 more)

### Community 118 - "TradingServer.php"
Cohesion: 0.14
Nodes (4): DslReference, GetJob, ListInstruments, TradingServer

### Community 119 - "CandleFeedTest.php"
Cohesion: 0.38
Nodes (12): {closure#1}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#2}(), {closure#4}(), {closure#5}(), {closure#8}() (+4 more)

### Community 120 - "buy-and-hold.strategy.yaml"
Cohesion: 0.12
Nodes (16): entries, long, exits, stop_loss, fails_when, family, hypothesis, when (+8 more)

### Community 121 - "ChartSeriesTest.php"
Cohesion: 0.23
Nodes (10): chart(), {closure#10}(), {closure#11}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#6}(), {closure#7}() (+2 more)

### Community 122 - "TokensTest.php"
Cohesion: 0.31
Nodes (6): {closure#3}(), {closure#4}(), contrastRatio(), contrastRules(), relativeLuminance(), themeTokens()

### Community 123 - "ArchiveImportTest.php"
Cohesion: 0.29
Nodes (10): archiveBytes(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}() (+2 more)

### Community 124 - "AppServiceProvider.php"
Cohesion: 0.10
Nodes (8): AppServiceProvider, {closure#7}(), {closure#3}(), {closure#4}(), FortifyServiceProvider, {closure#1}(), {closure#2}(), {closure#3}()

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
Nodes (20): InMemoryDecisionLog, {closure#1}(), {closure#2}(), BacktestResult, ParticipantResult, {closure#10}(), {closure#11}(), {closure#13}() (+12 more)

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
Cohesion: 0.10
Nodes (15): {closure#1}(), {closure#11}(), {closure#2}(), {closure#4}(), {closure#9}(), MinuteCandles, BreakerEvent, MarketBreakers (+7 more)

### Community 141 - "MarketType"
Cohesion: 0.06
Nodes (28): BinanceEndpoints, BinanceRequestFailed, BinanceRestClient, Json, LocationRestricted, RateLimited, RequestFailed, Sleeper (+20 more)

### Community 142 - ".account"
Cohesion: 0.33
Nodes (4): Policies, {closure#26}(), {closure#27}(), {closure#28}()

### Community 143 - "Percent"
Cohesion: 0.24
Nodes (3): Percent, {closure#14}(), {closure#15}()

### Community 145 - ".of"
Cohesion: 0.08
Nodes (19): Asset, AssetMismatch, Money, {closure#2}(), {closure#1}(), usdt(), {closure#7}(), {closure#1}() (+11 more)

### Community 146 - "DocumentCheckerTest.php"
Cohesion: 0.15
Nodes (17): {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), versions(), KnownInstruments, checkDocument() (+9 more)

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

### Community 172 - "Quantity"
Cohesion: 0.24
Nodes (3): Quantity, {closure#10}(), {closure#9}()

### Community 174 - ".parse"
Cohesion: 0.33
Nodes (6): KlineMessage, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), streamLines()

### Community 176 - "ParamKind"
Cohesion: 0.12
Nodes (12): {closure#2}(), {closure#1}(), {closure#2}(), ParamKind, Anchor, Count, Duration, Number (+4 more)

### Community 178 - "VenuePosition"
Cohesion: 0.36
Nodes (3): VenuePosition, {closure#1}(), SimulatedPosition

## Knowledge Gaps
- **142 isolated node(s):** `forgetTelegram`, `testTelegram`, `confirm`, `notify`, `monitor` (+137 more)
  These have ≤1 connection - possible missing edges. (Counts symbols only; 900 node(s) total have ≤1 connection when file, concept and rationale nodes are included.)
- **50 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `Decimal` connect `Decimal` to `InstrumentRules`, `EngineTest.php`, `.of`, `DslError`, `Price`, `EnginePerpTest.php`, `DocumentChecker`, `.times`, `CompiledStrategy.php`, `Side`, `Instrument`, `.measure`, `Carbon\CarbonImmutable`, `MarketType`, `Candle`, `RiskPolicy`, `IndicatorsTest.php`, `.of`, `App\Execution\Models\Account`, `Percent`, `LedgerTest.php`, `DocumentCheckerTest.php`, `ReadBack`, `StrategyIdentity`, `DirectionalMovement`, `.of`, `App\Execution\Venues\BinanceVenue`, `SimulatedPerpVenueTest.php`, `Strategy`, `.parse`, `Illuminate\Database\ConnectionInterface`, `.zero`, `Account`, `App\Market\Archive\BinanceArchive`, `BinanceVenueTest.php`, `PreparesBacktests.php`, `RiskEventKind`, `FundingRate`, `Position`, `SimulatedVenue`, `OrderRole`, `.walk`, `Quantity`, `.roundDownToTick`, `ForwardingVenue`, `VenuePosition`, `.handle`, `Side`, `MonteCarlo`, `OrderManagerTest.php`, `IndicatorReferenceTest.php`, `.__construct`, `.run`, `App\Strategy\Dsl\Document\DocumentChecker`, `AccountRuntime.php`, `Carbon\CarbonImmutable`, `FundingCarry`, `.sqrt`, `DecimalMath`, `CompiledStrategyTest.php`, `App\Support\Number\Decimal`, `Timeframe`, `PolicyLimits`, `.fromStrings`, `StrategyGates.php`, `BinanceVenueTest.php`, `ClientOrderId`, `CandleFeedTest.php`, `ChartSeriesTest.php`, `CompiledStrategy`, `StrategyLimits`?**
  _High betweenness centrality (0.248) - this node is a cross-community bridge._
- **What connects `forgetTelegram`, `testTelegram`, `confirm` to the rest of the system?**
  _142 weakly-connected nodes found - possible missing edges._
- **Should `InstrumentRules` be split into smaller, more focused modules?**
  _Cohesion score 0.08244897959183674 - nodes in this community are weakly interconnected._
- **Why does `Symbol` connect `StrategyIdentity` to `McpServerTest.php`, `.of`, `DslError`, `Price`, `EnginePerpTest.php`, `DocumentChecker`, `.times`, `CompiledStrategy.php`, `Side`, `Instrument`, `.parse`, `Carbon\CarbonImmutable`, `MarketType`, `ExchangeVerificationTest.php`, `EngineTest.php`, `.measure`, `.of`, `Clock`, `DocumentCheckerTest.php`, `LedgerTest.php`, `ScreensTest.php`, `ReadBack`, `.of`, `RuntimeException`, `App\Execution\Venues\BinanceVenue`, `SimulatedPerpVenueTest.php`, `Strategy`, `PhrityTransport`, `Account`, `BinanceVenueTest.php`, `PreparesBacktests.php`, `SimClock`, `FundingRate`, `Position`, `Decimal`, `SimulatedVenue`, `OrderRole`, `ForwardingVenue`, `VenuePosition`, `.handle`, `Side`, `Laravel\Mcp\Response`, `OrderManagerTest.php`, `.__construct`, `AppServiceProvider.php`, `.run`, `CandleRepositoryTest.php`, `App\Strategy\Dsl\Document\DocumentChecker`, `AccountRuntime.php`, `Carbon\CarbonImmutable`, `FundingCarry`, `CompiledStrategyTest.php`, `Timeframe`, `PolicyLimits`, `StrategyGates.php`, `BinanceVenueTest.php`, `ClientOrderId`, `CandleFeedTest.php`, `ChartSeriesTest.php`, `CompiledStrategy`?**
  _High betweenness centrality (0.070) - this node is a cross-community bridge._
- **Should `EngineTest.php` be split into smaller, more focused modules?**
  _Cohesion score 0.1119177253478524 - nodes in this community are weakly interconnected._
- **Why does `Candle` connect `Candle` to `EngineTest.php`, `Price`, `EnginePerpTest.php`, `.parse`, `.times`, `Side`, `Instrument`, `Carbon\CarbonImmutable`, `MarketType`, `IndicatorsTest.php`, `ExchangeVerificationTest.php`, `DirectionalMovement`, `StrategyIdentity`, `App\Execution\Venues\BinanceVenue`, `SimulatedPerpVenueTest.php`, `PhrityTransport`, `Illuminate\Database\ConnectionInterface`, `.zero`, `App\Market\Archive\BinanceArchive`, `FundingRate`, `Decimal`, `SimulatedVenue`, `.walk`, `.parse`, `BinanceRestClientTest.php`, `Side`, `OrderManagerTest.php`, `IndicatorReferenceTest.php`, `.run`, `CandleRepositoryTest.php`, `Carbon\CarbonImmutable`, `.sqrt`, `CompiledStrategyTest.php`, `App\Support\Number\Decimal`, `Timeframe`, `.fromStrings`, `StrategyGates.php`, `ClientOrderId`, `CandleFeedTest.php`, `ChartSeriesTest.php`?**
  _High betweenness centrality (0.037) - this node is a cross-community bridge._
- **Should `.of` be split into smaller, more focused modules?**
  _Cohesion score 0.07346938775510205 - nodes in this community are weakly interconnected._