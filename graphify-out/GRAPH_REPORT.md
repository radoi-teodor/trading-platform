# Graph Report - trading-platform  (2026-11-09)

## Corpus Check
- 548 files · ~224,256 words
- Verdict: corpus is large enough that graph structure adds value.
- Unclassified: 93 file(s) not represented in the graph (top: .zip 34, .CHECKSUM 34, (none) 19)

## Summary
- 4334 nodes · 14814 edges · 181 communities (132 shown, 49 thin omitted)
- Extraction: 92% EXTRACTED · 8% INFERRED · 0% AMBIGUOUS · INFERRED: 1170 edges (avg confidence: 0.85)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `4bb2320d`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- .of
- EngineTest.php
- Illuminate\Console\Command
- DslError
- Fill
- .parse
- DocumentChecker
- .times
- CompiledStrategy.php
- SimulatedVenueTest.php
- .now
- SimulatedPerpVenueTest.php
- Candle
- ExchangeVerificationTest.php
- IndicatorsTest.php
- RiskPolicy
- Node
- PolicyLimits
- StrategyVersion
- ScreensTest.php
- Ledger
- Illuminate\Database\Schema\Blueprint
- generate.py
- ReadBack
- StrategyIdentity
- Clock
- Instrument
- Symbol
- Illuminate\Foundation\Testing\RefreshDatabase
- Strategy
- CandleStreamTest.php
- PaperEngineTest.php
- .zero
- ChartScreen.php
- BinanceArchive.php
- CandleRepository.php
- StrategyGates.php
- SimClock
- .act
- FundingRate
- Position
- ema-cross-scalp-long.strategy.yaml
- Decimal
- SimulatedVenue
- OrderRole
- .walk
- Illuminate\Database\Eloquent\Model
- package.json
- SimulatedVenue
- BinanceRestClientTest.php
- ema-cross-scalp-short.strategy.yaml
- OpenAccount
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
- BinanceArchive
- .isFull
- RunEngine.php
- donchian-breakout.strategy.yaml
- .run
- CandleRepositoryTest.php
- App\Strategy\Dsl\Document\DocumentChecker
- InvalidArgumentException
- Engine
- User
- SimulationQueue
- scripts
- ma-trend-short.strategy.yaml
- .parse
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
- StrategyLibrary
- .fromStrings
- config
- uses
- test
- with
- Illuminate\Contracts\Config\Repository
- jobs
- Illuminate\Http\Client\Factory
- psr-4
- require
- QualityCheck
- .evaluate
- uses
- SettingsScreenTest.php
- devDependencies
- ClientOrderId
- WarmingVenue
- TradingServer.php
- CandleFeedTest.php
- buy-and-hold.strategy.yaml
- ChartSeriesTest.php
- TokensTest.php
- GridBacktest.php
- AppServiceProvider.php
- Illuminate\Support\Facades\Schema
- Illuminate\Database\Migrations\Migration
- ChartSeries
- funding-carry.strategy.yaml
- McpServerTest.php
- env.mjs
- chart.js
- TelegramSettings
- EnginePerpTest.php
- settings-screen.blade.php
- 0001_01_01_000000_create_users_table.php
- steps
- .encode
- Carbon\CarbonImmutable
- MarketType
- .handle
- Percent
- BackfillTest.php
- .of
- FundingRateRepositoryTest.php
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
- ChartScreen
- .handle
- ParamKind
- E2eSeeder.php
- AuthenticateMcpToken.php
- 2026_11_09_000012_create_account_results_tables.php
- 2026_11_09_000009_create_exchange_keys_table.php

## God Nodes (most connected - your core abstractions)
1. `Decimal` - 598 edges
2. `Symbol` - 251 edges
3. `Side` - 215 edges
4. `Candle` - 170 edges
5. `Timeframe` - 155 edges
6. `Price` - 116 edges
7. `Clock` - 115 edges
8. `Position` - 96 edges
9. `MarketType` - 87 edges
10. `App` - 84 edges

## Surprising Connections (you probably didn't know these)
- `{closure#8}()` --calls--> `App`  [INFERRED]
  tests/Feature/Web/SettingsScreenTest.php → app/Notifications/SettingSource.php
- `{closure#3}()` --calls--> `App`  [INFERRED]
  tests/Feature/Network/BinancePublicDataTest.php → app/Notifications/SettingSource.php
- `{closure#5}()` --calls--> `App`  [INFERRED]
  tests/Feature/Network/BinancePublicDataTest.php → app/Notifications/SettingSource.php
- `{closure#1}()` --calls--> `App`  [INFERRED]
  tests/Feature/Support/ClockBindingTest.php → app/Notifications/SettingSource.php
- `{closure#2}()` --calls--> `App`  [INFERRED]
  tests/Feature/Agents/TokensTest.php → app/Notifications/SettingSource.php

## Import Cycles
- None detected.

## Communities (181 total, 49 thin omitted)

### Community 0 - ".of"
Cohesion: 0.06
Nodes (82): AccountView, RejectionReason, BelowMinimum, Breaker, Cooldown, DuplicateIntent, EntryWorking, GrossExposure (+74 more)

### Community 1 - "EngineTest.php"
Cohesion: 0.13
Nodes (34): ScriptedStrategy, {closure#10}(), {closure#13}(), {closure#14}(), {closure#16}(), {closure#17}(), {closure#2}(), {closure#20}() (+26 more)

### Community 2 - "Illuminate\Console\Command"
Cohesion: 0.06
Nodes (22): CreateMcpToken, RevokeMcpToken, Tokens, CreateAccount, FlattenEngine, HaltEngine, IssuesCommands, PauseStrategy (+14 more)

### Community 3 - "DslError"
Cohesion: 0.10
Nodes (15): {closure#1}(), {closure#1}(), {closure#8}(), DslError, YamlList, YamlReader, YamlScalar, YamlValue (+7 more)

### Community 4 - "Fill"
Cohesion: 0.08
Nodes (7): Fill, PerpPosition, Position, VolumeProfile, {closure#16}(), {closure#10}(), {closure#4}()

### Community 5 - ".parse"
Cohesion: 0.12
Nodes (34): {closure#17}(), carryDecisions(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#15}() (+26 more)

### Community 7 - ".times"
Cohesion: 0.10
Nodes (6): {closure#4}(), {closure#3}(), {closure#6}(), {closure#5}(), Normal, InvalidNumber

### Community 8 - "CompiledStrategy.php"
Cohesion: 0.12
Nodes (4): CompiledStrategy, {closure#33}(), Compiler, Program

### Community 9 - "SimulatedVenueTest.php"
Cohesion: 0.18
Nodes (42): Side, Buy, Sell, btc(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}() (+34 more)

### Community 10 - ".now"
Cohesion: 0.08
Nodes (9): Backfill, BackfillReport, BackfillStep, BackfillMarketData, CandleSource, Archive, Rest, Stream (+1 more)

### Community 11 - "SimulatedPerpVenueTest.php"
Cohesion: 0.21
Nodes (24): {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#15}(), {closure#18}(), {closure#19}() (+16 more)

### Community 12 - "Candle"
Cohesion: 0.10
Nodes (33): Candle, InvalidCandle, {closure#9}(), {closure#1}(), {closure#2}(), {closure#1}(), {closure#42}(), candleWith() (+25 more)

### Community 13 - "ExchangeVerificationTest.php"
Cohesion: 0.12
Nodes (27): {closure#12}(), {closure#13}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}() (+19 more)

### Community 14 - "IndicatorsTest.php"
Cohesion: 0.09
Nodes (22): Atr, Donchian, Ema, Sma, {closure#5}(), fiveMinuteBars(), bar(), {closure#10}() (+14 more)

### Community 15 - "RiskPolicy"
Cohesion: 0.10
Nodes (16): EngineMode, Backtest, Live, Paper, Testnet, RiskProfile, Account, Benchmark (+8 more)

### Community 16 - "Node"
Cohesion: 0.11
Nodes (21): Binary, Lexer, Parser, Token, TokenKind, Colon, Comma, Dot (+13 more)

### Community 17 - "PolicyLimits"
Cohesion: 0.07
Nodes (29): InstrumentDirectory, StoredInstruments, {closure#1}(), CompileStrategy, PolicyLimits, StrategyRepository, {closure#1}(), {closure#2}() (+21 more)

### Community 18 - "StrategyVersion"
Cohesion: 0.23
Nodes (5): {closure#1}(), {closure#2}(), CheckResult, SaveResult, StrategyVersion

### Community 19 - "ScreensTest.php"
Cohesion: 0.14
Nodes (12): {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}(), {closure#1}() (+4 more)

### Community 20 - "Ledger"
Cohesion: 0.05
Nodes (67): ExitReason, Flatten, Liquidation, Signal, Stop, Target, Marks, Ledger (+59 more)

### Community 21 - "Illuminate\Database\Schema\Blueprint"
Cohesion: 0.19
Nodes (10): {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#1}(), {closure#2}(), {closure#3}() (+2 more)

### Community 22 - "generate.py"
Cohesion: 0.08
Nodes (30): atr(), bars(), bb(), change(), dmi(), donchian(), ema(), highest() (+22 more)

### Community 24 - "StrategyIdentity"
Cohesion: 0.04
Nodes (18): BuyAndHold, DonchianBreakout, FundingCarry, MovingAverageTrend, Parameters, BuildableStrategy, EntryOrder, OpenLong (+10 more)

### Community 25 - "Clock"
Cohesion: 0.13
Nodes (11): BinanceEndpoints, Sleeper, CandleRepository, FundingRateRepository, Heartbeats, {closure#1}(), InstrumentSync, SyncResult (+3 more)

### Community 26 - "Instrument"
Cohesion: 0.11
Nodes (7): {closure#1}(), Instrument, ExchangeVerification, VerificationReport, VerificationSource, DailyArchive, Rest

### Community 27 - "Symbol"
Cohesion: 0.04
Nodes (35): Income, IncomeKind, Funding, Liquidation, OrderType, Limit, Market, StopMarket (+27 more)

### Community 28 - "Illuminate\Foundation\Testing\RefreshDatabase"
Cohesion: 0.10
Nodes (10): {closure#1}(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), savedEmaCross(), {closure#1}(), {closure#2}() (+2 more)

### Community 29 - "Strategy"
Cohesion: 0.11
Nodes (22): {closure#2}(), {closure#3}(), {closure#5}(), {closure#7}(), {closure#8}(), Strategy, baselineDocument(), baselinePairs() (+14 more)

### Community 30 - "CandleStreamTest.php"
Cohesion: 0.07
Nodes (11): PhrityTransport, StreamFailed, StreamTransport, {closure#1}(), {closure#2}(), {closure#4}(), {closure#5}(), streamMessages() (+3 more)

### Community 31 - "PaperEngineTest.php"
Cohesion: 0.14
Nodes (17): AccountEquity, AccountTrade, JournalEntry, {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}() (+9 more)

### Community 32 - ".zero"
Cohesion: 0.08
Nodes (32): Statistics, Cscv, DeflatedSharpe, {closure#1}(), {closure#2}(), {closure#3}(), {closure#3}(), {closure#1}() (+24 more)

### Community 33 - "ChartScreen.php"
Cohesion: 0.10
Nodes (13): Account, AccountSnapshot, AccountScreen, {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), HaltButton (+5 more)

### Community 34 - "BinanceArchive.php"
Cohesion: 0.13
Nodes (9): {closure#1}(), {closure#2}(), EpochTime, ExchangeNumber, {closure#1}(), {closure#2}(), {closure#3}(), {closure#1}() (+1 more)

### Community 35 - "CandleRepository.php"
Cohesion: 0.16
Nodes (9): {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), DatabaseCandleFeed, {closure#1}(), Rows, {closure#10}() (+1 more)

### Community 36 - "StrategyGates.php"
Cohesion: 0.10
Nodes (12): {closure#3}(), {closure#8}(), {closure#2}(), GridPart, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}() (+4 more)

### Community 37 - "SimClock"
Cohesion: 0.11
Nodes (14): SimClock, WallClock, {closure#1}(), {closure#2}(), {closure#3}(), {closure#1}(), {closure#1}(), {closure#1}() (+6 more)

### Community 38 - ".act"
Cohesion: 0.09
Nodes (8): DecisionLog, DecisionRecord, IntentRecord, {closure#1}(), {closure#2}(), {closure#4}(), {closure#1}(), DatabaseDecisionLog

### Community 39 - "FundingRate"
Cohesion: 0.10
Nodes (22): FeeSchedule, CostModel, CandleFeed, FundingRate, {closure#2}(), Backtest, {closure#2}(), RunBacktest (+14 more)

### Community 40 - "Position"
Cohesion: 0.05
Nodes (32): Argument, BoolLiteral, Call, DurationLiteral, Identifier, Lookback, Node, NumberLiteral (+24 more)

### Community 41 - "ema-cross-scalp-long.strategy.yaml"
Cohesion: 0.06
Nodes (33): entries, long, exits, stop_loss, take_profit, time_stop, when, fails_when (+25 more)

### Community 42 - "Decimal"
Cohesion: 0.04
Nodes (47): {closure#6}(), {closure#1}(), {closure#2}(), RoundTrip, {closure#1}(), {closure#2}(), {closure#3}(), {closure#10}() (+39 more)

### Community 43 - "SimulatedVenue"
Cohesion: 0.08
Nodes (20): ClientOrderId, Liquidity, Maker, Taker, OrderStatus, Cancelled, Expired, Filled (+12 more)

### Community 44 - "OrderRole"
Cohesion: 0.13
Nodes (9): OrderRole, Entry, Exit, Flatten, Stop, Target, ProtectionPlan, OrderManager (+1 more)

### Community 45 - ".walk"
Cohesion: 0.20
Nodes (17): CandleAggregator, CandleSequenceBroken, {closure#6}(), aggregate(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}() (+9 more)

### Community 46 - "Illuminate\Database\Eloquent\Model"
Cohesion: 0.06
Nodes (11): {closure#1}(), McpCall, McpToken, PlainDecimal, Deployment, ExchangeKey, InstrumentFilter, StrategyGateReport (+3 more)

### Community 47 - "package.json"
Cohesion: 0.15
Nodes (17): dependencies, codemirror@^6.0.2, @codemirror/lang-yaml@^6.1.3, @codemirror/language@^6.13.1, @lezer/highlight@^1.2.5, lightweight-charts@^5.2.1, optionalDependencies, @laravel/multiplex@^0.4.1 (+9 more)

### Community 48 - "SimulatedVenue"
Cohesion: 0.08
Nodes (13): Venue, Balance, SimulatedVenue, UpdateStream, ForwardingVenue, {closure#12}(), balances(), {closure#22}() (+5 more)

### Community 49 - "BinanceRestClientTest.php"
Cohesion: 0.16
Nodes (15): binanceClient(), {closure#1}(), {closure#11}(), {closure#13}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}() (+7 more)

### Community 50 - "ema-cross-scalp-short.strategy.yaml"
Cohesion: 0.06
Nodes (33): entries, short, exits, stop_loss, take_profit, time_stop, when, fails_when (+25 more)

### Community 52 - ".of"
Cohesion: 0.08
Nodes (29): Perpetual, {closure#44}(), {closure#45}(), {closure#46}(), {closure#47}(), {closure#48}(), {closure#17}(), {closure#5}() (+21 more)

### Community 53 - "Laravel\Mcp\Response"
Cohesion: 0.11
Nodes (8): CompileStrategy, GetFunding, GetJob, GetStrategy, ListInstruments, ListTrials, SaveStrategy, TradingTool

### Community 54 - "postgres"
Cohesion: 0.10
Nodes (19): POSTGRES_DB, POSTGRES_PASSWORD, POSTGRES_USER, interval, retries, test, timeout, environment (+11 more)

### Community 55 - "InstrumentRules"
Cohesion: 0.17
Nodes (12): InstrumentRules, RulesHistory, {closure#1}(), {closure#2}(), {closure#4}(), {closure#41}(), {closure#1}(), {closure#2}() (+4 more)

### Community 56 - "OrderManagerTest.php"
Cohesion: 0.15
Nodes (24): TrackedOrder, {closure#3}(), {closure#9}(), OrderBook, {closure#1}(), btcUsdt(), {closure#10}(), {closure#11}() (+16 more)

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
Cohesion: 0.18
Nodes (23): BacktestRun, {closure#4}(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}() (+15 more)

### Community 61 - "TelegramTest.php"
Cohesion: 0.11
Nodes (23): SendTelegram, App, {closure#1}(), {closure#2}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#1}() (+15 more)

### Community 62 - "BinanceArchive"
Cohesion: 0.10
Nodes (17): ArchiveMissing, ArchivePeriod, BinanceArchive, ChecksumMismatch, {closure#1}(), {closure#2}(), {closure#3}(), archiveBytes() (+9 more)

### Community 64 - "RunEngine.php"
Cohesion: 0.07
Nodes (12): AccountRuntime, {closure#4}(), {closure#5}(), RunEngine, EngineCommand, ReplayDivergence, EngineCommandRow, {closure#1}() (+4 more)

### Community 65 - "donchian-breakout.strategy.yaml"
Cohesion: 0.07
Nodes (26): entries, long, default, range, step, default, range, step (+18 more)

### Community 66 - ".run"
Cohesion: 0.13
Nodes (24): Report, btcMinutes(), {closure#1}(), {closure#2}(), referencePrice(), {closure#13}(), {closure#21}(), {closure#2}() (+16 more)

### Community 67 - "CandleRepositoryTest.php"
Cohesion: 0.28
Nodes (11): candleStore(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}() (+3 more)

### Community 68 - "App\Strategy\Dsl\Document\DocumentChecker"
Cohesion: 0.05
Nodes (49): {closure#2}(), {closure#1}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#9}(), ParameterSpec, DslWriter (+41 more)

### Community 69 - "InvalidArgumentException"
Cohesion: 0.08
Nodes (5): {closure#1}(), {closure#2}(), {closure#1}(), {closure#1}(), Period

### Community 70 - "Engine"
Cohesion: 0.16
Nodes (5): EngineRecord, Engine, ReconciliationMismatch, {closure#2}(), {closure#1}()

### Community 71 - "User"
Cohesion: 0.09
Nodes (4): ReauthController, User, CreateUser, UserFactory

### Community 72 - "SimulationQueue"
Cohesion: 0.17
Nodes (4): RunBacktest, RunGrid, SimulationTool, SimulationQueue

### Community 73 - "scripts"
Cohesion: 0.15
Nodes (13): scripts, analyse, dev, format, mutate, post-autoload-dump, post-update-cmd, pre-package-uninstall (+5 more)

### Community 74 - "ma-trend-short.strategy.yaml"
Cohesion: 0.07
Nodes (26): entries, short, exits, stop_loss, when, fails_when, family, default (+18 more)

### Community 75 - ".parse"
Cohesion: 0.17
Nodes (9): AstPrinter, {closure#7}(), Expression, {closure#1}(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}() (+1 more)

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
Cohesion: 0.14
Nodes (14): DecimalMath, {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#2}() (+6 more)

### Community 82 - "CompiledStrategyTest.php"
Cohesion: 0.10
Nodes (20): Intent, PositionView, StrategyContext, {closure#18}(), {closure#37}(), aggregatedBars(), {closure#11}(), {closure#13}() (+12 more)

### Community 83 - "App\Support\Number\Decimal"
Cohesion: 0.07
Nodes (40): {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#15}(), {closure#16}() (+32 more)

### Community 84 - "pint/composer.json"
Cohesion: 0.20
Nodes (9): config, sort-packages, description, license, name, require, laravel/pint, php (+1 more)

### Community 85 - "MonteCarlo"
Cohesion: 0.14
Nodes (10): {closure#4}(), MonteCarlo, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}() (+2 more)

### Community 86 - "RunSimulation.php"
Cohesion: 0.14
Nodes (3): RunSimulation, SimulationJob, {closure#4}()

### Community 87 - "Timeframe"
Cohesion: 0.07
Nodes (23): {closure#1}(), {closure#2}(), {closure#3}(), {closure#3}(), {closure#4}(), CarryTemplate, {closure#2}(), ExitSpec (+15 more)

### Community 88 - "require-dev"
Cohesion: 0.22
Nodes (9): require-dev, fakerphp/faker, larastan/larastan, laravel/pail, laravel/pao, mockery/mockery, nunomaduro/collision, pestphp/pest (+1 more)

### Community 89 - "scripts-descriptions"
Cohesion: 0.25
Nodes (8): scripts-descriptions, analyse, format, mutate, test, test:network, test:testnet, tools:install

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

### Community 96 - "Illuminate\Contracts\Config\Repository"
Cohesion: 0.13
Nodes (4): ExportStrategies, ImportStrategies, PreparesBacktests, PromoteStrategy

### Community 97 - "jobs"
Cohesion: 0.25
Nodes (8): name, runs-on, name, runs-on, steps, jobs, analyse, audit

### Community 98 - "Illuminate\Http\Client\Factory"
Cohesion: 0.18
Nodes (3): BinanceApiError, BinanceUnreachable, SignedBinanceClient

### Community 99 - "psr-4"
Cohesion: 0.40
Nodes (5): autoload, psr-4, App\\, Database\\Factories\\, Database\\Seeders\\

### Community 100 - "require"
Cohesion: 0.25
Nodes (8): require, laravel/fortify, laravel/framework, laravel/mcp, laravel/tinker, livewire/livewire, php, phrity/websocket

### Community 103 - ".evaluate"
Cohesion: 0.29
Nodes (7): WalkForward, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), wfSet()

### Community 104 - "uses"
Cohesion: 0.21
Nodes (12): steps, name, runs-on, steps, frontend, lint, name, runs-on (+4 more)

### Community 105 - "SettingsScreenTest.php"
Cohesion: 0.12
Nodes (20): {closure#3}(), {closure#13}(), {closure#5}(), {closure#12}(), {closure#2}(), {closure#3}(), {closure#12}(), {closure#19}() (+12 more)

### Community 106 - "devDependencies"
Cohesion: 0.18
Nodes (12): devDependencies, concurrently@^10.0.3, laravel-vite-plugin@^3.1, @playwright/test@1.63.0, tailwindcss@^4.0.0, @tailwindcss/vite@^4.0.0, vite@^8.0.0, concurrently (+4 more)

### Community 108 - "ClientOrderId"
Cohesion: 0.08
Nodes (16): {closure#2}(), OcoRequest, OrderAck, OrderRequest, BinanceVenue, binanceVenue(), {closure#1}(), {closure#2}() (+8 more)

### Community 118 - "TradingServer.php"
Cohesion: 0.21
Nodes (3): DslReference, ListStrategies, TradingServer

### Community 119 - "CandleFeedTest.php"
Cohesion: 0.44
Nodes (11): {closure#1}(), {closure#12}(), {closure#13}(), {closure#2}(), {closure#4}(), {closure#5}(), {closure#8}(), {closure#9}() (+3 more)

### Community 120 - "buy-and-hold.strategy.yaml"
Cohesion: 0.12
Nodes (16): entries, long, exits, stop_loss, fails_when, family, hypothesis, when (+8 more)

### Community 121 - "ChartSeriesTest.php"
Cohesion: 0.28
Nodes (7): chart(), {closure#11}(), {closure#2}(), {closure#3}(), {closure#6}(), {closure#7}(), {closure#9}()

### Community 122 - "TokensTest.php"
Cohesion: 0.31
Nodes (6): {closure#3}(), {closure#4}(), contrastRatio(), contrastRules(), relativeLuminance(), themeTokens()

### Community 123 - "GridBacktest.php"
Cohesion: 0.18
Nodes (7): {closure#11}(), {closure#12}(), {closure#2}(), {closure#6}(), {closure#5}(), {closure#6}(), WalkForwardWindow

### Community 124 - "AppServiceProvider.php"
Cohesion: 0.07
Nodes (13): Notifier, SystemSleeper, TelegramNotifier, AppServiceProvider, {closure#3}(), {closure#5}(), {closure#6}(), {closure#3}() (+5 more)

### Community 127 - "ChartSeries"
Cohesion: 0.27
Nodes (4): ChartTool, GetCandles, GetIndicators, ChartSeries

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

### Community 133 - "EnginePerpTest.php"
Cohesion: 0.14
Nodes (19): InMemoryDecisionLog, {closure#1}(), {closure#2}(), BacktestResult, ParticipantResult, Close, {closure#10}(), {closure#11}() (+11 more)

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
Cohesion: 0.17
Nodes (10): MinuteCandles, BreakerEvent, MarketBreakers, {closure#14}(), {closure#3}(), {closure#7}(), breakerMinute(), {closure#1}() (+2 more)

### Community 141 - "MarketType"
Cohesion: 0.07
Nodes (26): BinanceRequestFailed, BinanceRestClient, {closure#1}(), Json, LocationRestricted, RateLimited, RequestFailed, SyncInstruments (+18 more)

### Community 143 - "Percent"
Cohesion: 0.24
Nodes (3): Percent, {closure#14}(), {closure#15}()

### Community 144 - "BackfillTest.php"
Cohesion: 0.14
Nodes (14): {closure#11}(), {closure#14}(), {closure#15}(), {closure#16}(), {closure#4}(), {closure#6}(), {closure#7}(), {closure#8}() (+6 more)

### Community 145 - ".of"
Cohesion: 0.12
Nodes (18): AssetMismatch, Money, {closure#16}(), {closure#2}(), {closure#1}(), usdt(), {closure#1}(), {closure#11}() (+10 more)

### Community 146 - "FundingRateRepositoryTest.php"
Cohesion: 0.18
Nodes (3): {closure#2}(), {closure#3}(), {closure#6}()

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

### Community 176 - "ParamKind"
Cohesion: 0.22
Nodes (7): ParamKind, Anchor, Count, Duration, Number, Percent, Series

### Community 177 - "E2eSeeder.php"
Cohesion: 0.32
Nodes (3): DatabaseSeeder, {closure#1}(), E2eSeeder

## Knowledge Gaps
- **141 isolated node(s):** `Backtest`, `Live`, `Paper`, `Testnet`, `Archive` (+136 more)
  These have ≤1 connection - possible missing edges. (Counts symbols only; 875 node(s) total have ≤1 connection when file, concept and rationale nodes are included.)
- **49 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `Decimal` connect `Decimal` to `.of`, `EngineTest.php`, `Fill`, `EnginePerpTest.php`, `DocumentChecker`, `.times`, `CompiledStrategy.php`, `SimulatedVenueTest.php`, `.parse`, `SimulatedPerpVenueTest.php`, `Candle`, `MarketType`, `.handle`, `RiskPolicy`, `IndicatorsTest.php`, `.of`, `PolicyLimits`, `Percent`, `Ledger`, `FundingRateRepositoryTest.php`, `ReadBack`, `StrategyIdentity`, `Clock`, `DirectionalMovement`, `Symbol`, `Strategy`, `.zero`, `ChartScreen.php`, `BinanceArchive.php`, `StrategyGates.php`, `.act`, `FundingRate`, `Position`, `SimulatedVenue`, `OrderRole`, `.walk`, `Illuminate\Database\Eloquent\Model`, `Quantity`, `SimulatedVenue`, `BinanceRestClientTest.php`, `.of`, `InstrumentRules`, `OrderManagerTest.php`, `IndicatorReferenceTest.php`, `RunBacktestCommandTest.php`, `.encode`, `.isFull`, `.run`, `App\Strategy\Dsl\Document\DocumentChecker`, `InvalidArgumentException`, `Engine`, `.parse`, `.evaluate`, `DecimalMath`, `CompiledStrategyTest.php`, `App\Support\Number\Decimal`, `MonteCarlo`, `Timeframe`, `.fromStrings`, `Illuminate\Contracts\Config\Repository`, `QualityCheck`, `.evaluate`, `ClientOrderId`, `WarmingVenue`, `CandleFeedTest.php`, `ChartSeriesTest.php`, `GridBacktest.php`?**
  _High betweenness centrality (0.216) - this node is a cross-community bridge._
- **What connects `Backtest`, `Live`, `Paper` to the rest of the system?**
  _141 weakly-connected nodes found - possible missing edges._
- **Should `.of` be split into smaller, more focused modules?**
  _Cohesion score 0.05868653935724266 - nodes in this community are weakly interconnected._
- **Why does `Symbol` connect `Symbol` to `.of`, `McpServerTest.php`, `Illuminate\Console\Command`, `EngineTest.php`, `Fill`, `EnginePerpTest.php`, `DocumentChecker`, `.parse`, `CompiledStrategy.php`, `SimulatedVenueTest.php`, `SimulatedPerpVenueTest.php`, `.encode`, `MarketType`, `.handle`, `ExchangeVerificationTest.php`, `BackfillTest.php`, `PolicyLimits`, `FundingRateRepositoryTest.php`, `ScreensTest.php`, `Ledger`, `StrategyIdentity`, `Clock`, `Instrument`, `Strategy`, `CandleStreamTest.php`, `ChartScreen.php`, `StrategyGates.php`, `.act`, `FundingRate`, `Position`, `Decimal`, `SimulatedVenue`, `OrderRole`, `Illuminate\Database\Eloquent\Model`, `SimulatedVenue`, `BinanceRestClientTest.php`, `E2eSeeder.php`, `.of`, `InstrumentRules`, `OrderManagerTest.php`, `RunBacktestCommandTest.php`, `RunEngine.php`, `.run`, `CandleRepositoryTest.php`, `App\Strategy\Dsl\Document\DocumentChecker`, `InvalidArgumentException`, `Engine`, `CompiledStrategyTest.php`, `App\Support\Number\Decimal`, `Timeframe`, `Illuminate\Contracts\Config\Repository`, `ClientOrderId`, `WarmingVenue`, `CandleFeedTest.php`, `ChartSeriesTest.php`, `ChartSeries`?**
  _High betweenness centrality (0.066) - this node is a cross-community bridge._
- **Should `EngineTest.php` be split into smaller, more focused modules?**
  _Cohesion score 0.12917271407837447 - nodes in this community are weakly interconnected._
- **Why does `Clock` connect `Clock` to `Illuminate\Console\Command`, `.now`, `MarketType`, `.handle`, `ExchangeVerificationTest.php`, `BackfillTest.php`, `PolicyLimits`, `FundingRateRepositoryTest.php`, `Instrument`, `Strategy`, `CandleStreamTest.php`, `ChartScreen.php`, `CandleRepository.php`, `StrategyGates.php`, `SimClock`, `.act`, `FundingRate`, `SimulatedVenue`, `Illuminate\Database\Eloquent\Model`, `.handle`, `SimulatedVenue`, `BinanceRestClientTest.php`, `AuthenticateMcpToken.php`, `OpenAccount`, `RunBacktestCommandTest.php`, `BinanceArchive`, `RunEngine.php`, `CandleRepositoryTest.php`, `InvalidArgumentException`, `User`, `SimulationQueue`, `RunSimulation.php`, `StrategyLibrary`, `Illuminate\Contracts\Config\Repository`, `Illuminate\Http\Client\Factory`, `QualityCheck`, `CandleFeedTest.php`, `GridBacktest.php`, `AppServiceProvider.php`?**
  _High betweenness centrality (0.039) - this node is a cross-community bridge._
- **Should `Illuminate\Console\Command` be split into smaller, more focused modules?**
  _Cohesion score 0.05714285714285714 - nodes in this community are weakly interconnected._