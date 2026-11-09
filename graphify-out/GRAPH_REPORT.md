# Graph Report - trading-platform  (2026-11-09)

## Corpus Check
- 531 files · ~218,752 words
- Verdict: corpus is large enough that graph structure adds value.
- Unclassified: 92 file(s) not represented in the graph (top: .zip 34, .CHECKSUM 34, (none) 18)

## Summary
- 4263 nodes · 14595 edges · 178 communities (132 shown, 46 thin omitted)
- Extraction: 92% EXTRACTED · 8% INFERRED · 0% AMBIGUOUS · INFERRED: 1097 edges (avg confidence: 0.85)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `24fd4e8f`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- .of
- EngineTest.php
- Illuminate\Console\Command
- BinanceArchive
- .zero
- .parse
- DocumentChecker
- ParamKind
- CompiledStrategy.php
- SimulatedVenueTest.php
- Instrument
- SimulatedPerpVenueTest.php
- Candle
- ExchangeVerificationTest.php
- IndicatorsTest.php
- GlobalsTest.php
- Node
- PolicyLimits
- Position
- MarketType
- LedgerTest.php
- Illuminate\Database\Schema\Blueprint
- generate.py
- ReadBack
- DonchianBreakout
- CandleStreamTest.php
- FundingCarry
- Symbol
- .measure
- Strategy
- PhrityTransport
- StrategyIdentity
- .zero
- InvalidArgumentException
- InMemoryDecisionLog
- ExitReason
- PaperEngineTest.php
- SimClock
- .act
- BacktestTest.php
- Position
- ema-cross-scalp-long.strategy.yaml
- Decimal
- OrderStatus
- OrderRole
- User
- Illuminate\Database\Eloquent\Model
- package.json
- SimulatedVenue
- BinanceRestClientTest.php
- ema-cross-scalp-short.strategy.yaml
- EnginePerpTest.php
- .of
- Laravel\Mcp\Response
- postgres
- InstrumentRules
- OrderManagerTest.php
- donchian-breakout-short.strategy.yaml
- IndicatorReferenceTest.php
- BinanceVenueTest.php
- RunBacktestCommandTest.php
- TelegramTest.php
- QualityCheck
- .isFull
- .__construct
- donchian-breakout.strategy.yaml
- RiskPolicy
- CandleRepositoryTest.php
- App\Strategy\Dsl\Document\DocumentChecker
- .of
- Engine
- ScreensTest.php
- ArchiveImportTest.php
- scripts
- ma-trend-short.strategy.yaml
- DslError
- .walk
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
- Statistics
- .fromStrings
- config
- uses
- test
- with
- PreparesBacktests.php
- run
- Illuminate\Contracts\JsonSchema\JsonSchema
- psr-4
- require
- DatabaseSeeder
- steps
- steps
- lint
- TradingTool
- ClientOrderId
- StrategyLibrary
- StrategiesScreen.php
- .compute
- buy-and-hold.strategy.yaml
- ChartSeriesTest.php
- TokensTest.php
- GridBacktest.php
- AppServiceProvider.php
- Illuminate\Support\Facades\Schema
- Illuminate\Database\Migrations\Migration
- StrategyVersion
- funding-carry.strategy.yaml
- McpServerTest.php
- SettingsScreenTest.php
- MonteCarlo.php
- TelegramSettings
- Marks
- settings-screen.blade.php
- 0001_01_01_000000_create_users_table.php
- TradingServer.php
- .encode
- MarketBreakers
- BinanceRestClient
- Clock
- Account
- Carbon\CarbonImmutable
- Percent
- Quantity
- .__construct
- 0001_01_01_000002_create_jobs_table.php
- Illuminate\Foundation\Testing\RefreshDatabase
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
- command
- CompiledStrategy
- SimulationJobsTest.php
- DocumentCheckerTest.php
- DirectionalMovement
- BarIndicator
- 2026_11_09_000011_create_notification_settings_table.php

## God Nodes (most connected - your core abstractions)
1. `Decimal` - 597 edges
2. `Symbol` - 252 edges
3. `Side` - 215 edges
4. `Candle` - 170 edges
5. `Timeframe` - 155 edges
6. `Price` - 116 edges
7. `Clock` - 114 edges
8. `Position` - 96 edges
9. `MarketType` - 86 edges
10. `SimulatedVenue` - 73 edges

## Surprising Connections (you probably didn't know these)
- `{closure#2}()` --calls--> `App`  [INFERRED]
  tests/Feature/Agents/TokensTest.php → app/Notifications/SettingSource.php
- `{closure#3}()` --calls--> `App`  [INFERRED]
  tests/Feature/Agents/TokensTest.php → app/Notifications/SettingSource.php
- `{closure#2}()` --calls--> `App`  [INFERRED]
  tests/Feature/Execution/OpenAccountTest.php → app/Notifications/SettingSource.php
- `{closure#4}()` --calls--> `App`  [INFERRED]
  tests/Feature/Execution/OpenAccountTest.php → app/Notifications/SettingSource.php
- `{closure#5}()` --calls--> `App`  [INFERRED]
  tests/Feature/Execution/OpenAccountTest.php → app/Notifications/SettingSource.php

## Import Cycles
- None detected.

## Communities (178 total, 46 thin omitted)

### Community 0 - ".of"
Cohesion: 0.05
Nodes (83): AccountView, RejectionReason, BelowMinimum, Breaker, Cooldown, DuplicateIntent, EntryWorking, GrossExposure (+75 more)

### Community 1 - "EngineTest.php"
Cohesion: 0.12
Nodes (36): ScriptedStrategy, {closure#10}(), {closure#13}(), {closure#14}(), {closure#16}(), {closure#17}(), {closure#2}(), {closure#20}() (+28 more)

### Community 2 - "Illuminate\Console\Command"
Cohesion: 0.08
Nodes (16): CreateMcpToken, FlattenEngine, HaltEngine, IssuesCommands, PauseStrategy, ResumeEngine, {closure#5}(), CommandKind (+8 more)

### Community 3 - "BinanceArchive"
Cohesion: 0.15
Nodes (5): ReplayDivergence, ArchiveMissing, BinanceArchive, ChecksumMismatch, VerifyMarketData

### Community 4 - ".zero"
Cohesion: 0.07
Nodes (10): SimulatedFuturesBook, SimulatedOrder, {closure#1}(), {closure#3}(), {closure#3}(), MonteCarlo, Perpetual, {closure#5}() (+2 more)

### Community 5 - ".parse"
Cohesion: 0.11
Nodes (37): {closure#1}(), baselinePairs(), {closure#17}(), carryDecisions(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}() (+29 more)

### Community 7 - "ParamKind"
Cohesion: 0.17
Nodes (10): {closure#2}(), ParamKind, Anchor, Count, Duration, Number, Percent, Series (+2 more)

### Community 8 - "CompiledStrategy.php"
Cohesion: 0.21
Nodes (3): {closure#33}(), Compiler, Program

### Community 9 - "SimulatedVenueTest.php"
Cohesion: 0.15
Nodes (43): btc(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#15}(), {closure#16}() (+35 more)

### Community 10 - "Instrument"
Cohesion: 0.06
Nodes (17): Backfill, BackfillReport, BackfillStep, CandleRepository, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}() (+9 more)

### Community 11 - "SimulatedPerpVenueTest.php"
Cohesion: 0.21
Nodes (24): {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#15}(), {closure#18}(), {closure#19}() (+16 more)

### Community 12 - "Candle"
Cohesion: 0.13
Nodes (30): Candle, {closure#9}(), {closure#1}(), {closure#42}(), candleWith(), {closure#1}(), {closure#10}(), {closure#11}() (+22 more)

### Community 13 - "ExchangeVerificationTest.php"
Cohesion: 0.10
Nodes (32): VerificationSource, DailyArchive, Rest, {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#2}() (+24 more)

### Community 14 - "IndicatorsTest.php"
Cohesion: 0.09
Nodes (21): Atr, Donchian, Ema, Keltner, Sma, bar(), {closure#1}(), {closure#10}() (+13 more)

### Community 16 - "Node"
Cohesion: 0.14
Nodes (20): Binary, Parser, Token, TokenKind, Colon, Comma, Dot, Duration (+12 more)

### Community 17 - "PolicyLimits"
Cohesion: 0.13
Nodes (6): InstrumentDirectory, StoredInstruments, CompileStrategy, GridPart, PolicyLimits, StrategyRepository

### Community 18 - "Position"
Cohesion: 0.05
Nodes (33): Argument, BoolLiteral, Call, DurationLiteral, Identifier, Lookback, Node, NumberLiteral (+25 more)

### Community 19 - "MarketType"
Cohesion: 0.08
Nodes (25): {closure#1}(), InstrumentDefinition, InstrumentStatus, Delisted, Halted, Trading, MarketType, Perp (+17 more)

### Community 20 - "LedgerTest.php"
Cohesion: 0.15
Nodes (30): Ledger, LedgerViolation, balanceOf(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}() (+22 more)

### Community 21 - "Illuminate\Database\Schema\Blueprint"
Cohesion: 0.19
Nodes (10): {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#1}(), {closure#2}(), {closure#3}() (+2 more)

### Community 22 - "generate.py"
Cohesion: 0.08
Nodes (30): atr(), bars(), bb(), change(), dmi(), donchian(), ema(), highest() (+22 more)

### Community 23 - "ReadBack"
Cohesion: 0.09
Nodes (11): CarryTemplate, ExitSpec, StrategySpec, ReadBack, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}() (+3 more)

### Community 24 - "DonchianBreakout"
Cohesion: 0.05
Nodes (14): BuyAndHold, MovingAverageTrend, BuildableStrategy, Close, EntryOrder, OpenLong, OpenShort, StrategyContext (+6 more)

### Community 25 - "CandleStreamTest.php"
Cohesion: 0.09
Nodes (13): BinanceEndpoints, Sleeper, CandleStream, StreamTransport, {closure#4}(), {closure#1}(), {closure#2}(), {closure#4}() (+5 more)

### Community 26 - "FundingCarry"
Cohesion: 0.16
Nodes (3): DonchianBreakout, FundingCarry, Parameters

### Community 27 - "Symbol"
Cohesion: 0.05
Nodes (25): VenuePosition, {closure#1}(), {closure#11}(), {closure#12}(), {closure#2}(), {closure#3}(), {closure#9}(), {closure#2}() (+17 more)

### Community 28 - ".measure"
Cohesion: 0.15
Nodes (9): EquityTracker, Performance, {closure#1}(), {closure#2}(), {closure#3}(), {closure#5}(), {closure#6}(), {closure#8}() (+1 more)

### Community 29 - "Strategy"
Cohesion: 0.15
Nodes (19): {closure#2}(), {closure#3}(), Strategy, baselineDocument(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#14}() (+11 more)

### Community 31 - "StrategyIdentity"
Cohesion: 0.18
Nodes (5): StrategyIdentity, RenamedStrategy, {closure#1}(), {closure#2}(), {closure#3}()

### Community 32 - ".zero"
Cohesion: 0.13
Nodes (18): Cscv, DeflatedSharpe, closeTo(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#13}(), {closure#14}() (+10 more)

### Community 33 - "InvalidArgumentException"
Cohesion: 0.06
Nodes (9): RevokeMcpToken, Tokens, OpenAccount, CreateAccount, StoreKeys, {closure#2}(), {closure#3}(), SettingsScreen (+1 more)

### Community 34 - "InMemoryDecisionLog"
Cohesion: 0.12
Nodes (13): EngineMode, Backtest, Live, Paper, Testnet, InMemoryDecisionLog, {closure#2}(), BacktestResult (+5 more)

### Community 35 - "ExitReason"
Cohesion: 0.18
Nodes (26): ExitReason, Flatten, Liquidation, Signal, Stop, Target, {closure#1}(), {closure#10}() (+18 more)

### Community 36 - "PaperEngineTest.php"
Cohesion: 0.24
Nodes (11): JournalEntry, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}() (+3 more)

### Community 37 - "SimClock"
Cohesion: 0.07
Nodes (32): SimClock, WallClock, {closure#11}(), {closure#14}(), {closure#15}(), {closure#16}(), {closure#4}(), {closure#5}() (+24 more)

### Community 38 - ".act"
Cohesion: 0.11
Nodes (6): DecisionLog, DecisionRecord, IntentRecord, {closure#1}(), DatabaseDecisionLog, Intent

### Community 39 - "BacktestTest.php"
Cohesion: 0.12
Nodes (17): FeeSchedule, CostModel, Backtest, RunBacktest, {closure#1}(), RunDefinition, ReportBuilder, InMemoryCandleFeed (+9 more)

### Community 40 - "Position"
Cohesion: 0.09
Nodes (17): {closure#1}(), RMultipleLiteral, {closure#1}(), {closure#5}(), {closure#8}(), DslError, Position, YamlList (+9 more)

### Community 41 - "ema-cross-scalp-long.strategy.yaml"
Cohesion: 0.06
Nodes (33): entries, long, exits, stop_loss, take_profit, time_stop, when, fails_when (+25 more)

### Community 42 - "Decimal"
Cohesion: 0.05
Nodes (41): {closure#6}(), {closure#1}(), {closure#2}(), RoundTrip, {closure#1}(), {closure#2}(), {closure#3}(), {closure#10}() (+33 more)

### Community 43 - "OrderStatus"
Cohesion: 0.08
Nodes (20): Income, IncomeKind, Funding, Liquidation, Liquidity, Maker, Taker, OrderStatus (+12 more)

### Community 44 - "OrderRole"
Cohesion: 0.10
Nodes (18): OrderRole, Entry, Exit, Flatten, Stop, Target, ProtectionPlan, TrackedOrder (+10 more)

### Community 46 - "Illuminate\Database\Eloquent\Model"
Cohesion: 0.09
Nodes (11): McpCall, AccountSnapshot, Deployment, ExchangeKey, InstrumentFilter, StrategyHoldout, StrategyStage, {closure#1}() (+3 more)

### Community 47 - "package.json"
Cohesion: 0.07
Nodes (37): dependencies, codemirror@^6.0.2, @codemirror/lang-yaml@^6.1.3, @codemirror/language@^6.13.1, @lezer/highlight@^1.2.5, lightweight-charts@^5.2.1, devDependencies, concurrently@^10.0.3 (+29 more)

### Community 49 - "BinanceRestClientTest.php"
Cohesion: 0.12
Nodes (16): BinanceRequestFailed, LocationRestricted, RateLimited, RequestFailed, {closure#9}(), binanceClient(), {closure#1}(), {closure#11}() (+8 more)

### Community 50 - "ema-cross-scalp-short.strategy.yaml"
Cohesion: 0.06
Nodes (33): entries, short, exits, stop_loss, take_profit, time_stop, when, fails_when (+25 more)

### Community 51 - "EnginePerpTest.php"
Cohesion: 0.26
Nodes (15): {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#5}(), {closure#6}(), {closure#7}() (+7 more)

### Community 52 - ".of"
Cohesion: 0.11
Nodes (26): {closure#2}(), {closure#8}(), {closure#41}(), {closure#44}(), {closure#45}(), {closure#46}(), {closure#47}(), {closure#48}() (+18 more)

### Community 53 - "Laravel\Mcp\Response"
Cohesion: 0.15
Nodes (5): GetIndicators, GetJob, ListInstruments, {closure#1}(), StrategyTrial

### Community 54 - "postgres"
Cohesion: 0.10
Nodes (19): POSTGRES_DB, POSTGRES_PASSWORD, POSTGRES_USER, interval, retries, test, timeout, environment (+11 more)

### Community 55 - "InstrumentRules"
Cohesion: 0.17
Nodes (14): InstrumentRules, {closure#1}(), RulesHistory, {closure#1}(), {closure#2}(), {closure#3}(), {closure#5}(), {closure#4}() (+6 more)

### Community 56 - "OrderManagerTest.php"
Cohesion: 0.36
Nodes (14): btcUsdt(), {closure#10}(), {closure#15}(), {closure#16}(), {closure#2}(), {closure#4}(), {closure#5}(), {closure#6}() (+6 more)

### Community 57 - "donchian-breakout-short.strategy.yaml"
Cohesion: 0.07
Nodes (26): entries, short, default, range, step, default, range, step (+18 more)

### Community 58 - "IndicatorReferenceTest.php"
Cohesion: 0.05
Nodes (26): {closure#34}(), Bollinger, Change, Highest, Hma, Lowest, Macd, Obv (+18 more)

### Community 59 - "BinanceVenueTest.php"
Cohesion: 0.11
Nodes (14): {closure#2}(), BinanceVenue, BinanceApiError, BinanceUnreachable, SignedBinanceClient, binanceVenue(), {closure#1}(), {closure#4}() (+6 more)

### Community 60 - "RunBacktestCommandTest.php"
Cohesion: 0.18
Nodes (23): BacktestRun, {closure#4}(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}() (+15 more)

### Community 61 - "TelegramTest.php"
Cohesion: 0.10
Nodes (29): SendTelegram, App, {closure#3}(), {closure#13}(), {closure#12}(), {closure#2}(), {closure#3}(), {closure#1}() (+21 more)

### Community 62 - "QualityCheck"
Cohesion: 0.10
Nodes (8): BackfillMarketData, CheckMarketData, SelectsInstruments, {closure#1}(), StreamCandles, Universe, QualityCheck, QualityReport

### Community 63 - ".isFull"
Cohesion: 0.11
Nodes (4): DonchianChannel, Stochastic, Vwma, Window

### Community 64 - ".__construct"
Cohesion: 0.09
Nodes (7): AccountRuntime, {closure#4}(), RunEngine, Notifier, WarmingVenue, CandleFeed, {closure#2}()

### Community 65 - "donchian-breakout.strategy.yaml"
Cohesion: 0.07
Nodes (26): entries, long, default, range, step, default, range, step (+18 more)

### Community 66 - "RiskPolicy"
Cohesion: 0.07
Nodes (40): RiskProfile, Account, Benchmark, {closure#1}(), RiskPolicy, {closure#2}(), Participant, PerformanceMetrics (+32 more)

### Community 67 - "CandleRepositoryTest.php"
Cohesion: 0.28
Nodes (11): candleStore(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}() (+3 more)

### Community 68 - "App\Strategy\Dsl\Document\DocumentChecker"
Cohesion: 0.05
Nodes (47): {closure#2}(), {closure#3}(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#9}(), ParameterSpec (+39 more)

### Community 69 - ".of"
Cohesion: 0.07
Nodes (26): {closure#1}(), {closure#2}(), Balance, Asset, AssetMismatch, Money, balances(), {closure#22}() (+18 more)

### Community 70 - "Engine"
Cohesion: 0.14
Nodes (5): EngineRecord, Engine, ReconciliationMismatch, {closure#2}(), {closure#1}()

### Community 71 - "ScreensTest.php"
Cohesion: 0.14
Nodes (12): {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}(), {closure#1}() (+4 more)

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

### Community 76 - ".walk"
Cohesion: 0.20
Nodes (17): CandleAggregator, CandleSequenceBroken, {closure#6}(), aggregate(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}() (+9 more)

### Community 77 - "composer.json"
Cohesion: 0.17
Nodes (11): autoload-dev, psr-4, description, keywords, license, minimum-stability, name, prefer-stable (+3 more)

### Community 78 - "jobs"
Cohesion: 0.17
Nodes (11): concurrency, cancel-in-progress, group, jobs, name, on, pull_request, push (+3 more)

### Community 79 - "postgres"
Cohesion: 0.17
Nodes (12): POSTGRES_DB, POSTGRES_PASSWORD, POSTGRES_USER, env, image, options, ports, image (+4 more)

### Community 80 - "ma-trend.strategy.yaml"
Cohesion: 0.07
Nodes (26): entries, long, exits, stop_loss, when, fails_when, family, default (+18 more)

### Community 81 - "DecimalMath"
Cohesion: 0.12
Nodes (15): Normal, DecimalMath, {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}() (+7 more)

### Community 82 - "CompiledStrategyTest.php"
Cohesion: 0.15
Nodes (16): KnownInstruments, aggregatedBars(), {closure#11}(), {closure#13}(), {closure#16}(), {closure#17}(), {closure#2}(), {closure#3}() (+8 more)

### Community 83 - "App\Support\Number\Decimal"
Cohesion: 0.08
Nodes (40): {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#15}(), {closure#16}() (+32 more)

### Community 84 - "pint/composer.json"
Cohesion: 0.20
Nodes (9): config, sort-packages, description, license, name, require, laravel/pint, php (+1 more)

### Community 85 - "MonteCarlo"
Cohesion: 0.28
Nodes (8): {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), mcDecimals(), percentiles()

### Community 87 - "Timeframe"
Cohesion: 0.08
Nodes (20): {closure#1}(), {closure#2}(), {closure#3}(), {closure#3}(), {closure#4}(), {closure#2}(), {closure#8}(), Timeframe (+12 more)

### Community 88 - "require-dev"
Cohesion: 0.22
Nodes (9): require-dev, fakerphp/faker, larastan/larastan, laravel/pail, laravel/pao, mockery/mockery, nunomaduro/collision, pestphp/pest (+1 more)

### Community 89 - "scripts-descriptions"
Cohesion: 0.25
Nodes (8): scripts-descriptions, analyse, format, mutate, test, test:network, test:testnet, tools:install

### Community 90 - "Statistics"
Cohesion: 0.13
Nodes (18): Statistics, Gates, brokenGate(), {closure#1}(), {closure#3}(), {closure#4}(), {closure#6}(), {closure#7}() (+10 more)

### Community 91 - ".fromStrings"
Cohesion: 0.18
Nodes (7): RegimeLabels, {closure#1}(), {closure#2}(), {closure#4}(), {closure#7}(), {closure#9}(), days()

### Community 92 - "config"
Cohesion: 0.29
Nodes (7): pestphp/pest-plugin, php-http/discovery, config, allow-plugins, optimize-autoloader, preferred-install, sort-packages

### Community 93 - "uses"
Cohesion: 0.29
Nodes (7): GITHUB_TOKEN, secrets, name, runs-on, steps, env, uses

### Community 94 - "test"
Cohesion: 0.29
Nodes (7): test, php, fail-fast, matrix, name, runs-on, strategy

### Community 95 - "with"
Cohesion: 0.29
Nodes (7): with, cache, coverage, extensions, fetch-depth, node-version, php-version

### Community 96 - "PreparesBacktests.php"
Cohesion: 0.13
Nodes (4): {closure#5}(), {closure#7}(), {closure#8}(), PreparesBacktests

### Community 97 - "run"
Cohesion: 0.33
Nodes (6): name, runs-on, steps, audit, run, steps

### Community 98 - "Illuminate\Contracts\JsonSchema\JsonSchema"
Cohesion: 0.15
Nodes (3): RunBacktest, RunGrid, SimulationTool

### Community 99 - "psr-4"
Cohesion: 0.40
Nodes (5): autoload, psr-4, App\\, Database\\Factories\\, Database\\Seeders\\

### Community 100 - "require"
Cohesion: 0.25
Nodes (8): require, laravel/fortify, laravel/framework, laravel/mcp, laravel/tinker, livewire/livewire, php, phrity/websocket

### Community 103 - "steps"
Cohesion: 0.50
Nodes (4): name, runs-on, steps, analyse

### Community 104 - "steps"
Cohesion: 0.50
Nodes (4): name, runs-on, steps, frontend

### Community 105 - "lint"
Cohesion: 0.50
Nodes (4): lint, name, runs-on, steps

### Community 106 - "TradingTool"
Cohesion: 0.16
Nodes (4): GetStrategy, SaveStrategy, TradingTool, McpToken

### Community 108 - "ClientOrderId"
Cohesion: 0.10
Nodes (14): Venue, ClientOrderId, OcoRequest, OrderAck, OrderRequest, OrderType, Limit, Market (+6 more)

### Community 117 - "StrategyLibrary"
Cohesion: 0.29
Nodes (4): CompileStrategy, StrategiesScreen, {closure#1}(), StrategyLibrary

### Community 118 - "StrategiesScreen.php"
Cohesion: 0.08
Nodes (10): Account, ReauthController, AccountScreen, ChartScreen, {closure#4}(), {closure#5}(), HaltButton, {closure#1}() (+2 more)

### Community 119 - ".compute"
Cohesion: 0.21
Nodes (3): ChartTool, GetCandles, ChartSeries

### Community 120 - "buy-and-hold.strategy.yaml"
Cohesion: 0.12
Nodes (16): entries, long, exits, stop_loss, fails_when, family, hypothesis, when (+8 more)

### Community 121 - "ChartSeriesTest.php"
Cohesion: 0.23
Nodes (11): chart(), {closure#10}(), {closure#11}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}() (+3 more)

### Community 122 - "TokensTest.php"
Cohesion: 0.31
Nodes (6): {closure#3}(), {closure#4}(), contrastRatio(), contrastRules(), relativeLuminance(), themeTokens()

### Community 123 - "GridBacktest.php"
Cohesion: 0.07
Nodes (27): {closure#11}(), {closure#12}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#6}(), {closure#8}(), {closure#2}() (+19 more)

### Community 124 - "AppServiceProvider.php"
Cohesion: 0.07
Nodes (12): SystemSleeper, AppServiceProvider, {closure#3}(), {closure#5}(), {closure#6}(), {closure#3}(), {closure#4}(), FortifyServiceProvider (+4 more)

### Community 127 - "StrategyVersion"
Cohesion: 0.23
Nodes (4): {closure#1}(), ListStrategies, {closure#2}(), StrategyVersion

### Community 128 - "funding-carry.strategy.yaml"
Cohesion: 0.12
Nodes (15): fails_when, family, hypothesis, params, enter_when, exit_when, margin_buffer, max_leverage_perp (+7 more)

### Community 129 - "McpServerTest.php"
Cohesion: 0.30
Nodes (11): actAsToken(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}(), {closure#8}() (+3 more)

### Community 130 - "SettingsScreenTest.php"
Cohesion: 0.28
Nodes (8): {closure#10}(), {closure#11}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), confirmed(), received()

### Community 132 - "TelegramSettings"
Cohesion: 0.12
Nodes (7): NotificationSettings, SettingSource, Env, None, TelegramClient, TelegramNotifier, TelegramSettings

### Community 135 - "settings-screen.blade.php"
Cohesion: 0.29
Nodes (6): confirm, forgetKey, forgetTelegram, notify, revokeToken, testTelegram

### Community 137 - "0001_01_01_000000_create_users_table.php"
Cohesion: 0.33
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

### Community 138 - "TradingServer.php"
Cohesion: 0.12
Nodes (4): DslReference, GetFunding, ListTrials, TradingServer

### Community 139 - ".encode"
Cohesion: 0.30
Nodes (6): CanonicalJson, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}()

### Community 140 - "MarketBreakers"
Cohesion: 0.29
Nodes (7): {closure#4}(), BreakerEvent, MarketBreakers, breakerMinute(), {closure#1}(), {closure#2}(), {closure#3}()

### Community 141 - "BinanceRestClient"
Cohesion: 0.08
Nodes (18): {closure#1}(), {closure#2}(), BinanceRestClient, EpochTime, ExchangeNumber, Json, SyncInstruments, KlineMessage (+10 more)

### Community 142 - "Clock"
Cohesion: 0.06
Nodes (15): AuthenticateMcpToken, FundingRateRepository, Heartbeats, ExportStrategies, GridBacktest, ImportStrategies, PromoteStrategy, ReadsGridOptions (+7 more)

### Community 143 - "Account"
Cohesion: 0.15
Nodes (6): {closure#1}(), {closure#2}(), {closure#3}(), fakeTestnet(), testnetAccount(), {closure#6}()

### Community 144 - "Carbon\CarbonImmutable"
Cohesion: 0.07
Nodes (17): ArchivePeriod, {closure#1}(), {closure#2}(), {closure#3}(), FundingRate, MinuteCandles, DatabaseCandleFeed, {closure#1}() (+9 more)

### Community 145 - "Percent"
Cohesion: 0.24
Nodes (3): Percent, {closure#14}(), {closure#15}()

### Community 146 - "Quantity"
Cohesion: 0.24
Nodes (3): Quantity, {closure#10}(), {closure#9}()

### Community 148 - "0001_01_01_000002_create_jobs_table.php"
Cohesion: 0.33
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

### Community 150 - "Illuminate\Foundation\Testing\RefreshDatabase"
Cohesion: 0.10
Nodes (9): {closure#1}(), {closure#2}(), {closure#3}(), {closure#1}(), {closure#2}(), {closure#4}(), {closure#5}(), {closure#6}() (+1 more)

### Community 157 - "strategies-screen.blade.php"
Cohesion: 0.40
Nodes (4): open, promote, run, save

### Community 159 - "extra"
Cohesion: 0.67
Nodes (3): extra, laravel, dont-discover

### Community 178 - "SimulationJobsTest.php"
Cohesion: 0.21
Nodes (4): {closure#1}(), {closure#2}(), {closure#3}(), savedEmaCross()

### Community 179 - "DocumentCheckerTest.php"
Cohesion: 0.19
Nodes (12): CheckResult, SaveResult, checkDocument(), {closure#1}(), {closure#2}(), {closure#4}(), {closure#5}(), {closure#6}() (+4 more)

### Community 181 - "DirectionalMovement"
Cohesion: 0.18
Nodes (5): {closure#1}(), {closure#2}(), {closure#3}(), DirectionalMovement, Wilder

### Community 185 - "BarIndicator"
Cohesion: 0.12
Nodes (6): {closure#36}(), BarIndicator, Natr, Supertrend, VolumeProfile, Vwap

## Knowledge Gaps
- **141 isolated node(s):** `mode-badge`, `halt-button`, `command`, `forgetTelegram`, `testTelegram` (+136 more)
  These have ≤1 connection - possible missing edges. (Counts symbols only; 863 node(s) total have ≤1 connection when file, concept and rationale nodes are included.)
- **46 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `Decimal` connect `Decimal` to `.of`, `EngineTest.php`, `MonteCarlo.php`, `.zero`, `Marks`, `DocumentChecker`, `.parse`, `CompiledStrategy.php`, `SimulatedVenueTest.php`, `SimulatedPerpVenueTest.php`, `Candle`, `BinanceRestClient`, `Clock`, `IndicatorsTest.php`, `Carbon\CarbonImmutable`, `PolicyLimits`, `Position`, `MarketType`, `.__construct`, `LedgerTest.php`, `Percent`, `ReadBack`, `DonchianBreakout`, `Quantity`, `FundingCarry`, `Symbol`, `.measure`, `Strategy`, `.zero`, `InvalidArgumentException`, `InMemoryDecisionLog`, `ExitReason`, `SimClock`, `.act`, `BacktestTest.php`, `Position`, `OrderStatus`, `OrderRole`, `CompiledStrategy`, `SimulatedVenue`, `EnginePerpTest.php`, `.of`, `DirectionalMovement`, `DocumentCheckerTest.php`, `InstrumentRules`, `OrderManagerTest.php`, `BarIndicator`, `IndicatorReferenceTest.php`, `BinanceVenueTest.php`, `RunBacktestCommandTest.php`, `.encode`, `QualityCheck`, `.isFull`, `.__construct`, `RiskPolicy`, `App\Strategy\Dsl\Document\DocumentChecker`, `.of`, `Engine`, `.walk`, `DecimalMath`, `CompiledStrategyTest.php`, `App\Support\Number\Decimal`, `MonteCarlo`, `Timeframe`, `Statistics`, `.fromStrings`, `PreparesBacktests.php`, `ClientOrderId`, `StrategiesScreen.php`, `ChartSeriesTest.php`, `GridBacktest.php`?**
  _High betweenness centrality (0.180) - this node is a cross-community bridge._
- **What connects `mode-badge`, `halt-button`, `command` to the rest of the system?**
  _141 weakly-connected nodes found - possible missing edges._
- **Should `.of` be split into smaller, more focused modules?**
  _Cohesion score 0.0518783542039356 - nodes in this community are weakly interconnected._
- **Why does `Candle` connect `Candle` to `EngineTest.php`, `MonteCarlo.php`, `.zero`, `.parse`, `SimulatedVenueTest.php`, `Instrument`, `SimulatedPerpVenueTest.php`, `MarketBreakers`, `BinanceRestClient`, `Clock`, `IndicatorsTest.php`, `Carbon\CarbonImmutable`, `ExchangeVerificationTest.php`, `MarketType`, `.__construct`, `DonchianBreakout`, `CandleStreamTest.php`, `Symbol`, `.act`, `BacktestTest.php`, `Decimal`, `OrderStatus`, `BinanceRestClientTest.php`, `EnginePerpTest.php`, `DirectionalMovement`, `InstrumentRules`, `OrderManagerTest.php`, `BarIndicator`, `IndicatorReferenceTest.php`, `.isFull`, `RiskPolicy`, `CandleRepositoryTest.php`, `.walk`, `CompiledStrategyTest.php`, `App\Support\Number\Decimal`, `Timeframe`, `.fromStrings`, `ClientOrderId`, `.compute`, `ChartSeriesTest.php`?**
  _High betweenness centrality (0.068) - this node is a cross-community bridge._
- **Should `EngineTest.php` be split into smaller, more focused modules?**
  _Cohesion score 0.12121212121212122 - nodes in this community are weakly interconnected._
- **Why does `Symbol` connect `Symbol` to `.of`, `McpServerTest.php`, `EngineTest.php`, `.zero`, `.parse`, `DocumentChecker`, `SimulatedVenueTest.php`, `Instrument`, `SimulatedPerpVenueTest.php`, `.encode`, `BinanceRestClient`, `Clock`, `ExchangeVerificationTest.php`, `Carbon\CarbonImmutable`, `PolicyLimits`, `Position`, `MarketType`, `LedgerTest.php`, `ReadBack`, `DonchianBreakout`, `CandleStreamTest.php`, `FundingCarry`, `.measure`, `Strategy`, `InMemoryDecisionLog`, `ExitReason`, `SimClock`, `.act`, `BacktestTest.php`, `Position`, `Decimal`, `OrderStatus`, `OrderRole`, `CompiledStrategy`, `SimulatedVenue`, `EnginePerpTest.php`, `InstrumentRules`, `OrderManagerTest.php`, `BinanceVenueTest.php`, `RunBacktestCommandTest.php`, `QualityCheck`, `.__construct`, `RiskPolicy`, `CandleRepositoryTest.php`, `.of`, `Engine`, `CompiledStrategyTest.php`, `Timeframe`, `PreparesBacktests.php`, `ClientOrderId`, `StrategiesScreen.php`, `.compute`, `ChartSeriesTest.php`?**
  _High betweenness centrality (0.058) - this node is a cross-community bridge._
- **Should `Illuminate\Console\Command` be split into smaller, more focused modules?**
  _Cohesion score 0.08108108108108109 - nodes in this community are weakly interconnected._