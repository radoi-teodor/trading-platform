# Graph Report - trading-platform  (2026-11-09)

## Corpus Check
- 603 files · ~251,089 words
- Verdict: corpus is large enough that graph structure adds value.
- Unclassified: 101 file(s) not represented in the graph (top: .zip 38, .CHECKSUM 38, (none) 19)

## Summary
- 4845 nodes · 16624 edges · 203 communities (151 shown, 52 thin omitted)
- Extraction: 91% EXTRACTED · 9% INFERRED · 0% AMBIGUOUS · INFERRED: 1473 edges (avg confidence: 0.85)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `1d5758b2`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- CompiledStrategyTest.php
- engineHarness
- CanonicalJson
- DslError
- Engine.php
- .parse
- DocumentChecker
- SettingsScreen
- CompiledStrategy.php
- .of
- Illuminate\Database\ConnectionInterface
- .of
- Candle
- ExchangeVerificationTest.php
- .isGreaterThan
- PolicyLimits
- Node
- Illuminate\Console\Command
- Instrument
- Illuminate\Foundation\Testing\RefreshDatabase
- Ledger
- Illuminate\Database\Schema\Blueprint
- generate.py
- ReadBack
- StrategyIdentity
- .of
- WarmingVenue
- Laravel\Mcp\Response
- Universe
- App\Strategy\Dsl\Compiler\CompiledStrategy
- App\Market\Stream\PhrityTransport
- oi-momentum-long.strategy.yaml
- PaperEngineTest.php
- Illuminate\Contracts\Bus\Dispatcher
- BinanceArchive
- App
- App
- .register
- Symbol
- EnginePerpTest.php
- App\Strategy\Dsl\Document\DocumentChecker
- ema-cross-scalp-long.strategy.yaml
- Decimal
- SimulatedVenue
- ScalpingArchiveTest.php
- Timeframe
- Illuminate\Database\Eloquent\Model
- editor.js
- Strategy
- BinanceRestClientTest.php
- ema-cross-scalp-short.strategy.yaml
- SettingsScreen.php
- RiskPolicy
- Illuminate\Contracts\JsonSchema\JsonSchema
- postgres
- InvalidArgumentException
- SizedStrategyTest.php
- donchian-breakout-short.strategy.yaml
- IndicatorReferenceTest.php
- support.mjs
- RunBacktestCommandTest.php
- DecimalMath
- Ladder
- FortifyServiceProvider.php
- HealthTest.php
- donchian-breakout.strategy.yaml
- .run
- FundingCarry
- App\Strategy\Dsl\Document\DocumentChecker
- InvalidArgumentException
- RulesHistory
- HealthAlerts.php
- SimulationQueue
- scripts
- ma-trend-short.strategy.yaml
- .parse
- .measure
- composer.json
- jobs
- postgres
- ma-trend.strategy.yaml
- DecimalMath
- MonteCarlo
- App\Support\Number\Decimal
- pint/composer.json
- App\Market\Data\CandleSource
- RunSimulation.php
- .timestamp
- require-dev
- scripts-descriptions
- InstrumentStatus
- .fromStrings
- config
- uses
- test
- with
- HealthChecks
- jobs
- .parse
- psr-4
- require
- App\Market\CandleRepository
- StrategyGates.php
- uses
- SettingsScreenTest.php
- devDependencies
- BinanceVenueTest.php
- ChartScreen.php
- App\Agents\Mcp\TradingServer
- DecisionRecord
- buy-and-hold.strategy.yaml
- ChartSeriesTest.php
- TokensTest.php
- ArchiveImportTest.php
- App\Support\Clock\Clock
- Illuminate\Database\Migrations\Migration
- ExitReason
- SimClock
- funding-carry.strategy.yaml
- McpServerTest.php
- env.mjs
- chart.js
- TelegramSettings
- App\Simulation\Backtest
- settings-screen.blade.php
- 0001_01_01_000000_create_users_table.php
- steps
- 2026_11_09_000014_create_health_tables.php
- ScalpingFunctionsTest.php
- .parse
- .evaluate
- CandleRepositoryTest.php
- Illuminate\Support\Facades\Schema
- Marks
- RuntimeException
- scripts
- 0001_01_01_000002_create_jobs_table.php
- fake-telegram.mjs
- QualityCheck
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
- App\Simulation\StrategyLibrary
- Carbon\CarbonImmutable
- Laravel\Mcp\Request
- DocumentCheckerTest.php
- RiskEventKind
- 2026_11_09_000012_create_account_results_tables.php
- Tokens
- User
- Timeframe
- CompiledStrategy
- App\Strategy\Dsl\Document\StrategySpec
- 2026_11_09_000013_add_daily_report_to_notification_settings.php
- MarketType
- StrategyVersion
- ForwardingVenue
- .handle
- ScalpingCollectorTest.php
- 2026_11_09_000018_add_effective_at_to_deployments.php
- 2026_11_09_000016_create_market_universe_tables.php
- Quantity
- 2026_11_09_000017_create_live_tables.php
- start-local.ps1
- ArchivePeriod
- .times
- DatabaseDecisionLog
- 2026_11_09_000015_create_scalping_tables.php

## God Nodes (most connected - your core abstractions)
1. `Decimal` - 516 edges
2. `Side` - 205 edges
3. `Symbol` - 203 edges
4. `Candle` - 134 edges
5. `App` - 134 edges
6. `Timeframe` - 123 edges
7. `Price` - 100 edges
8. `Position` - 96 edges
9. `Clock` - 78 edges
10. `SimClock` - 75 edges

## Surprising Connections (you probably didn't know these)
- `{closure#13}()` --calls--> `App`  [INFERRED]
  tests/Feature/Web/SettingsScreenTest.php → app/Notifications/SettingSource.php
- `{closure#8}()` --calls--> `App`  [INFERRED]
  tests/Feature/Web/SettingsScreenTest.php → app/Notifications/SettingSource.php
- `{closure#1}()` --calls--> `App`  [INFERRED]
  tests/Feature/Support/ClockBindingTest.php → app/Notifications/SettingSource.php
- `{closure#3}()` --calls--> `App`  [INFERRED]
  tests/Feature/Network/BinancePublicDataTest.php → app/Notifications/SettingSource.php
- `{closure#5}()` --calls--> `App`  [INFERRED]
  tests/Feature/Network/BinancePublicDataTest.php → app/Notifications/SettingSource.php

## Import Cycles
- None detected.

## Communities (203 total, 52 thin omitted)

### Community 0 - "CompiledStrategyTest.php"
Cohesion: 0.19
Nodes (17): PositionView, StrategyContext, aggregatedBars(), {closure#11}(), {closure#13}(), {closure#16}(), {closure#17}(), {closure#2}() (+9 more)

### Community 1 - "engineHarness"
Cohesion: 0.09
Nodes (42): EngineCommand, EntryOrder, ScriptedStrategy, balances(), {closure#10}(), {closure#13}(), {closure#14}(), {closure#16}() (+34 more)

### Community 2 - "CanonicalJson"
Cohesion: 0.14
Nodes (8): RunBacktest, CanonicalJson, ReportBuilder, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}()

### Community 3 - "DslError"
Cohesion: 0.15
Nodes (12): {closure#1}(), {closure#8}(), DslError, YamlList, YamlReader, YamlScalar, YamlValue, {closure#1}() (+4 more)

### Community 4 - "Engine.php"
Cohesion: 0.05
Nodes (12): EngineRecord, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#9}(), Engine, PerpetualTracker (+4 more)

### Community 5 - ".parse"
Cohesion: 0.10
Nodes (37): baselinePairs(), {closure#11}(), {closure#14}(), {closure#17}(), carryDecisions(), {closure#10}(), {closure#11}(), {closure#12}() (+29 more)

### Community 8 - "CompiledStrategy.php"
Cohesion: 0.12
Nodes (8): {closure#1}(), {closure#2}(), {closure#3}(), {closure#3}(), {closure#4}(), {closure#42}(), Compiler, Program

### Community 9 - ".of"
Cohesion: 0.05
Nodes (112): Band, TradeMemory, Side, Buy, Sell, {closure#8}(), {closure#10}(), {closure#11}() (+104 more)

### Community 10 - "Illuminate\Database\ConnectionInterface"
Cohesion: 0.15
Nodes (5): FundingRateRepository, Heartbeats, {closure#1}(), InstrumentSync, SyncResult

### Community 11 - ".of"
Cohesion: 0.10
Nodes (18): Asset, AssetMismatch, Money, {closure#2}(), {closure#1}(), usdt(), {closure#1}(), {closure#11}() (+10 more)

### Community 12 - "Candle"
Cohesion: 0.11
Nodes (33): {closure#1}(), {closure#3}(), Candle, KlineMessage, {closure#1}(), {closure#2}(), {closure#42}(), candleWith() (+25 more)

### Community 13 - "ExchangeVerificationTest.php"
Cohesion: 0.12
Nodes (29): {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#2}(), {closure#3}(), {closure#4}() (+21 more)

### Community 14 - ".isGreaterThan"
Cohesion: 0.10
Nodes (7): {closure#2}(), {closure#1}(), DonchianBreakout, Donchian, InvalidNumber, {closure#10}(), {closure#15}()

### Community 15 - "PolicyLimits"
Cohesion: 0.11
Nodes (13): InstrumentDirectory, StoredInstruments, {closure#1}(), CompileStrategy, PolicyLimits, StrategyRepository, Clock, {closure#1}() (+5 more)

### Community 16 - "Node"
Cohesion: 0.14
Nodes (20): Binary, Parser, Token, TokenKind, Colon, Comma, Dot, Duration (+12 more)

### Community 17 - "Illuminate\Console\Command"
Cohesion: 0.07
Nodes (19): FlattenEngine, HaltEngine, IssuesCommands, PauseStrategy, ResumeEngine, CommandKind, Flatten, Halt (+11 more)

### Community 18 - "Instrument"
Cohesion: 0.09
Nodes (10): BackfillStep, CandleRepository, CandleSource, Archive, Rest, Stream, UpsertResult, Instrument (+2 more)

### Community 19 - "Illuminate\Foundation\Testing\RefreshDatabase"
Cohesion: 0.07
Nodes (25): {closure#1}(), approveLive(), {closure#9}(), {closure#1}(), {closure#2}(), {closure#3}(), fakeTestnet(), testnetAccount() (+17 more)

### Community 20 - "Ledger"
Cohesion: 0.14
Nodes (28): Ledger, LedgerViolation, balanceOf(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}() (+20 more)

### Community 21 - "Illuminate\Database\Schema\Blueprint"
Cohesion: 0.19
Nodes (10): {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#1}(), {closure#2}(), {closure#3}() (+2 more)

### Community 22 - "generate.py"
Cohesion: 0.07
Nodes (30): atr(), bars(), bb(), change(), dmi(), donchian(), ema(), highest() (+22 more)

### Community 24 - "StrategyIdentity"
Cohesion: 0.06
Nodes (13): BuyAndHold, MovingAverageTrend, BuildableStrategy, Close, StrategyIdentity, Subscription, RenamedStrategy, EnterOnceStrategy (+5 more)

### Community 25 - ".of"
Cohesion: 0.05
Nodes (96): InstrumentRules, {closure#1}(), AccountView, RejectionReason, BelowMinimum, Breaker, Cooldown, DuplicateIntent (+88 more)

### Community 27 - "Laravel\Mcp\Response"
Cohesion: 0.17
Nodes (4): GetStrategy, ListInstruments, SaveStrategy, TradingTool

### Community 28 - "Universe"
Cohesion: 0.20
Nodes (4): {closure#1}(), {closure#2}(), StreamCandles, Universe

### Community 29 - "App\Strategy\Dsl\Compiler\CompiledStrategy"
Cohesion: 0.09
Nodes (10): {closure#2}(), {closure#5}(), {closure#6}(), {closure#7}(), {closure#8}(), {closure#9}(), {closure#1}(), {closure#2}() (+2 more)

### Community 30 - "App\Market\Stream\PhrityTransport"
Cohesion: 0.12
Nodes (3): PhrityTransport, StreamFailed, FakeTransport

### Community 31 - "oi-momentum-long.strategy.yaml"
Cohesion: 0.07
Nodes (27): entries, long, exits, stop_loss, take_profit, when, fails_when, family (+19 more)

### Community 32 - "PaperEngineTest.php"
Cohesion: 0.18
Nodes (17): {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#2}(), {closure#3}(), {closure#4}() (+9 more)

### Community 33 - "Illuminate\Contracts\Bus\Dispatcher"
Cohesion: 0.17
Nodes (3): SendDailyReport, DailyReport, TelegramNotifier

### Community 35 - "App"
Cohesion: 0.09
Nodes (27): SendTelegram, {closure#3}(), {closure#6}(), {closure#7}(), {closure#14}(), {closure#2}(), {closure#4}(), {closure#3}() (+19 more)

### Community 36 - "App"
Cohesion: 0.14
Nodes (21): App, {closure#1}(), {closure#2}(), {closure#3}(), {closure#3}(), {closure#1}(), {closure#2}(), {closure#4}() (+13 more)

### Community 37 - ".register"
Cohesion: 0.11
Nodes (23): FundingRate, {closure#1}(), {closure#1}(), {closure#2}(), {closure#4}(), {closure#5}(), {closure#6}(), streamMessages() (+15 more)

### Community 38 - "Symbol"
Cohesion: 0.05
Nodes (40): Venue, ClientOrderId, OcoRequest, OrderAck, OrderRequest, OrderRole, Entry, Exit (+32 more)

### Community 39 - "EnginePerpTest.php"
Cohesion: 0.10
Nodes (33): FeeSchedule, CostModel, CandleFeed, Backtest, {closure#1}(), RunDefinition, InMemoryCandleFeed, Policies (+25 more)

### Community 40 - "App\Strategy\Dsl\Document\DocumentChecker"
Cohesion: 0.07
Nodes (25): Argument, BoolLiteral, Call, DurationLiteral, Identifier, Lookback, Node, NumberLiteral (+17 more)

### Community 41 - "ema-cross-scalp-long.strategy.yaml"
Cohesion: 0.06
Nodes (33): entries, long, exits, stop_loss, take_profit, time_stop, when, fails_when (+25 more)

### Community 42 - "Decimal"
Cohesion: 0.04
Nodes (43): RoundTrip, {closure#1}(), {closure#1}(), {closure#2}(), {closure#4}(), {closure#6}(), {closure#7}(), {closure#10}() (+35 more)

### Community 43 - "SimulatedVenue"
Cohesion: 0.12
Nodes (13): Liquidity, Maker, Taker, OrderStatus, Cancelled, Expired, Filled, New (+5 more)

### Community 44 - "ScalpingArchiveTest.php"
Cohesion: 0.15
Nodes (14): OpenInterest, PriceMinute, TradeFlow, {closure#10}(), {closure#11}(), {closure#2}(), {closure#3}(), {closure#4}() (+6 more)

### Community 45 - "Timeframe"
Cohesion: 0.13
Nodes (29): CandleAggregator, CandleSequenceBroken, {closure#1}(), {closure#12}(), {closure#13}(), {closure#2}(), {closure#4}(), {closure#5}() (+21 more)

### Community 46 - "Illuminate\Database\Eloquent\Model"
Cohesion: 0.05
Nodes (20): McpCall, {closure#4}(), {closure#5}(), Notifier, AccountEquity, AccountSnapshot, AccountTrade, PlainDecimal (+12 more)

### Community 47 - "editor.js"
Cohesion: 0.19
Nodes (12): dependencies, codemirror@^6.0.2, @codemirror/lang-yaml@^6.1.3, @codemirror/language@^6.13.1, @lezer/highlight@^1.2.5, codemirror, @codemirror/lang-yaml, @codemirror/language (+4 more)

### Community 48 - "Strategy"
Cohesion: 0.18
Nodes (16): Strategy, baselineDocument(), {closure#1}(), {closure#10}(), {closure#12}(), {closure#15}(), {closure#18}(), {closure#20}() (+8 more)

### Community 49 - "BinanceRestClientTest.php"
Cohesion: 0.18
Nodes (14): Sleeper, binanceClient(), {closure#1}(), {closure#11}(), {closure#13}(), {closure#15}(), {closure#3}(), {closure#5}() (+6 more)

### Community 50 - "ema-cross-scalp-short.strategy.yaml"
Cohesion: 0.06
Nodes (33): entries, short, exits, stop_loss, take_profit, time_stop, when, fails_when (+25 more)

### Community 51 - "SettingsScreen.php"
Cohesion: 0.09
Nodes (8): Deployment, EngineCommandRow, ReauthController, AccountScreen, HaltButton, {closure#1}(), ModeBadge, {closure#1}()

### Community 52 - "RiskPolicy"
Cohesion: 0.15
Nodes (11): RiskProfile, Account, Benchmark, RiskPolicy, {closure#1}(), {closure#2}(), {closure#3}(), documentedLimits() (+3 more)

### Community 53 - "Illuminate\Contracts\JsonSchema\JsonSchema"
Cohesion: 0.11
Nodes (4): GetFunding, GetJob, {closure#1}(), StrategyTrial

### Community 54 - "postgres"
Cohesion: 0.10
Nodes (19): POSTGRES_DB, POSTGRES_PASSWORD, POSTGRES_USER, interval, retries, test, timeout, environment (+11 more)

### Community 55 - "InvalidArgumentException"
Cohesion: 0.05
Nodes (21): {closure#1}(), {closure#2}(), Balance, Income, IncomeKind, Funding, Liquidation, {closure#11}() (+13 more)

### Community 56 - "SizedStrategyTest.php"
Cohesion: 0.11
Nodes (12): SizedStrategy, {closure#2}(), LimitedStrategy, {closure#1}(), {closure#2}(), __construct(), enterOnce(), identity() (+4 more)

### Community 57 - "donchian-breakout-short.strategy.yaml"
Cohesion: 0.07
Nodes (26): entries, short, default, range, step, default, range, step (+18 more)

### Community 58 - "IndicatorReferenceTest.php"
Cohesion: 0.03
Nodes (51): {closure#43}(), Atr, BarIndicator, Bollinger, Change, DonchianChannel, Ema, Highest (+43 more)

### Community 59 - "support.mjs"
Cohesion: 0.26
Nodes (9): @playwright/test, confirmed(), checkLayout(), EMAIL, freshCode(), PASSWORD, reauthenticate(), totp() (+1 more)

### Community 60 - "RunBacktestCommandTest.php"
Cohesion: 0.08
Nodes (34): BacktestRun, {closure#1}(), {closure#4}(), {closure#6}(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}() (+26 more)

### Community 61 - "DecimalMath"
Cohesion: 0.12
Nodes (15): Normal, DecimalMath, {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}() (+7 more)

### Community 62 - "Ladder"
Cohesion: 0.19
Nodes (4): {closure#1}(), {closure#2}(), Ladder, LadderStatus

### Community 63 - "FortifyServiceProvider.php"
Cohesion: 0.09
Nodes (11): SystemSleeper, AppServiceProvider, {closure#4}(), {closure#5}(), {closure#7}(), {closure#3}(), {closure#4}(), FortifyServiceProvider (+3 more)

### Community 64 - "HealthTest.php"
Cohesion: 0.13
Nodes (21): Account, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#6}(), {closure#7}(), reportAccount() (+13 more)

### Community 65 - "donchian-breakout.strategy.yaml"
Cohesion: 0.07
Nodes (26): entries, long, default, range, step, default, range, step (+18 more)

### Community 66 - ".run"
Cohesion: 0.10
Nodes (27): Participant, PerformanceMetrics, Report, btcMinutes(), {closure#1}(), {closure#2}(), {closure#3}(), referencePrice() (+19 more)

### Community 68 - "App\Strategy\Dsl\Document\DocumentChecker"
Cohesion: 0.06
Nodes (37): {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#9}(), ParameterSpec, DslWriter (+29 more)

### Community 69 - "InvalidArgumentException"
Cohesion: 0.13
Nodes (3): {closure#1}(), PreparesBacktests, PromoteStrategy

### Community 70 - "RulesHistory"
Cohesion: 0.18
Nodes (12): RulesHistory, {closure#1}(), {closure#2}(), {closure#3}(), {closure#5}(), {closure#4}(), {closure#1}(), {closure#2}() (+4 more)

### Community 71 - "HealthAlerts.php"
Cohesion: 0.17
Nodes (3): CheckHealth, {closure#1}(), HealthAlerts

### Community 72 - "SimulationQueue"
Cohesion: 0.20
Nodes (4): RunBacktest, RunGrid, SimulationTool, SimulationQueue

### Community 73 - "scripts"
Cohesion: 0.15
Nodes (13): scripts, analyse, dev, format, mutate, post-autoload-dump, post-update-cmd, pre-package-uninstall (+5 more)

### Community 74 - "ma-trend-short.strategy.yaml"
Cohesion: 0.07
Nodes (26): entries, short, exits, stop_loss, when, fails_when, family, default (+18 more)

### Community 75 - ".parse"
Cohesion: 0.18
Nodes (9): {closure#1}(), {closure#2}(), EpochTime, ExchangeNumber, {closure#1}(), {closure#2}(), {closure#3}(), {closure#1}() (+1 more)

### Community 76 - ".measure"
Cohesion: 0.17
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
Nodes (22): {closure#1}(), {closure#7}(), Cscv, DeflatedSharpe, Vwap, closeTo(), {closure#1}(), {closure#10}() (+14 more)

### Community 82 - "MonteCarlo"
Cohesion: 0.14
Nodes (11): {closure#4}(), {closure#5}(), MonteCarlo, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}() (+3 more)

### Community 83 - "App\Support\Number\Decimal"
Cohesion: 0.07
Nodes (53): {closure#8}(), PerpetualView, {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}() (+45 more)

### Community 84 - "pint/composer.json"
Cohesion: 0.20
Nodes (9): config, sort-packages, description, license, name, require, laravel/pint, php (+1 more)

### Community 85 - "App\Market\Data\CandleSource"
Cohesion: 0.31
Nodes (6): {closure#2}(), {closure#4}(), {closure#6}(), {closure#8}(), {closure#1}(), {closure#2}()

### Community 86 - "RunSimulation.php"
Cohesion: 0.13
Nodes (4): ImportMarketData, {closure#3}(), RunSimulation, SimulationJob

### Community 87 - ".timestamp"
Cohesion: 0.15
Nodes (12): {closure#2}(), {closure#4}(), {closure#2}(), DatabaseCandleFeed, {closure#1}(), Rows, {closure#1}(), {closure#2}() (+4 more)

### Community 88 - "require-dev"
Cohesion: 0.22
Nodes (9): require-dev, fakerphp/faker, larastan/larastan, laravel/pail, laravel/pao, mockery/mockery, nunomaduro/collision, pestphp/pest (+1 more)

### Community 89 - "scripts-descriptions"
Cohesion: 0.25
Nodes (8): scripts-descriptions, analyse, format, mutate, test, test:network, test:testnet, tools:install

### Community 90 - "InstrumentStatus"
Cohesion: 0.16
Nodes (9): InstrumentDefinition, InstrumentStatus, Delisted, Halted, Trading, {closure#3}(), {closure#4}(), {closure#5}() (+1 more)

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

### Community 96 - "HealthChecks"
Cohesion: 0.11
Nodes (6): DiskUsage, HealthChecks, HealthResult, LocalDiskUsage, {closure#6}(), FakeDisk

### Community 97 - "jobs"
Cohesion: 0.25
Nodes (8): name, runs-on, name, runs-on, steps, jobs, analyse, audit

### Community 98 - ".parse"
Cohesion: 0.14
Nodes (10): AstPrinter, {closure#7}(), EntrySpec, Expression, Lexer, {closure#1}(), {closure#2}(), {closure#3}() (+2 more)

### Community 99 - "psr-4"
Cohesion: 0.40
Nodes (5): autoload, psr-4, App\\, Database\\Factories\\, Database\\Seeders\\

### Community 100 - "require"
Cohesion: 0.22
Nodes (9): require, bacon/bacon-qr-code, laravel/fortify, laravel/framework, laravel/mcp, laravel/tinker, livewire/livewire, php (+1 more)

### Community 102 - "App\Market\CandleRepository"
Cohesion: 0.12
Nodes (3): CandleStream, ScalpingCollector, StreamTransport

### Community 103 - "StrategyGates.php"
Cohesion: 0.05
Nodes (32): {closure#6}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}() (+24 more)

### Community 104 - "uses"
Cohesion: 0.21
Nodes (12): steps, name, runs-on, steps, frontend, lint, name, runs-on (+4 more)

### Community 105 - "SettingsScreenTest.php"
Cohesion: 0.20
Nodes (12): {closure#10}(), {closure#11}(), {closure#13}(), {closure#14}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}() (+4 more)

### Community 106 - "devDependencies"
Cohesion: 0.14
Nodes (18): devDependencies, concurrently@^10.0.3, laravel-vite-plugin@^3.1, @playwright/test@1.63.0, tailwindcss@^4.0.0, @tailwindcss/vite@^4.0.0, vite@^8.0.0, optionalDependencies (+10 more)

### Community 108 - "BinanceVenueTest.php"
Cohesion: 0.10
Nodes (15): {closure#2}(), BinanceVenue, BinanceApiError, BinanceUnreachable, SignedBinanceClient, binanceVenue(), {closure#1}(), {closure#2}() (+7 more)

### Community 117 - "ChartScreen.php"
Cohesion: 0.06
Nodes (25): {closure#2}(), ChartScreen, {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), FunctionSpec, Group (+17 more)

### Community 118 - "App\Agents\Mcp\TradingServer"
Cohesion: 0.16
Nodes (3): DslReference, ListTrials, TradingServer

### Community 119 - "DecisionRecord"
Cohesion: 0.14
Nodes (7): DecisionLog, DecisionRecord, IntentRecord, VenuePosition, {closure#1}(), {closure#1}(), SimulatedPosition

### Community 120 - "buy-and-hold.strategy.yaml"
Cohesion: 0.12
Nodes (16): entries, long, exits, stop_loss, fails_when, family, hypothesis, when (+8 more)

### Community 121 - "ChartSeriesTest.php"
Cohesion: 0.17
Nodes (10): VolumeProfile, chart(), {closure#10}(), {closure#11}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#6}() (+2 more)

### Community 122 - "TokensTest.php"
Cohesion: 0.31
Nodes (6): {closure#3}(), {closure#4}(), contrastRatio(), contrastRules(), relativeLuminance(), themeTokens()

### Community 123 - "ArchiveImportTest.php"
Cohesion: 0.29
Nodes (10): archiveBytes(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}() (+2 more)

### Community 124 - "App\Support\Clock\Clock"
Cohesion: 0.05
Nodes (21): AccountRuntime, LiveGate, OpenAccount, ApproveLive, ConfirmsOperator, CreateAccount, RunEngine, StoreKeys (+13 more)

### Community 125 - "Illuminate\Database\Migrations\Migration"
Cohesion: 0.15
Nodes (3): {closure#1}(), {closure#1}(), {closure#1}()

### Community 126 - "ExitReason"
Cohesion: 0.18
Nodes (25): ExitReason, Flatten, Liquidation, Signal, Stop, Target, {closure#1}(), {closure#10}() (+17 more)

### Community 127 - "SimClock"
Cohesion: 0.07
Nodes (30): {closure#1}(), InstrumentFilter, SimClock, WallClock, {closure#11}(), {closure#13}(), {closure#14}(), {closure#15}() (+22 more)

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
Cohesion: 0.38
Nodes (5): lightweight-charts@^5.2.1, lightweight-charts, faded(), options(), token()

### Community 132 - "TelegramSettings"
Cohesion: 0.17
Nodes (6): NotificationSettings, SettingSource, Env, None, TelegramClient, TelegramSettings

### Community 133 - "App\Simulation\Backtest"
Cohesion: 0.15
Nodes (9): EngineMode, Backtest, Live, Paper, Testnet, InMemoryDecisionLog, {closure#2}(), BacktestResult (+1 more)

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

### Community 140 - "ScalpingFunctionsTest.php"
Cohesion: 0.24
Nodes (10): backtestableScalpingHour(), {closure#2}(), {closure#3}(), {closure#5}(), {closure#6}(), {closure#7}(), {closure#8}(), referencePart() (+2 more)

### Community 141 - ".parse"
Cohesion: 0.43
Nodes (5): {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), streamLines()

### Community 142 - ".evaluate"
Cohesion: 0.09
Nodes (22): Statistics, TradeStatistics, Gates, {closure#1}(), {closure#2}(), {closure#3}(), brokenGate(), {closure#1}() (+14 more)

### Community 143 - "CandleRepositoryTest.php"
Cohesion: 0.28
Nodes (11): candleStore(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}() (+3 more)

### Community 145 - "Marks"
Cohesion: 0.42
Nodes (4): Marks, {closure#25}(), {closure#8}(), {closure#9}()

### Community 146 - "RuntimeException"
Cohesion: 0.12
Nodes (10): ReplayDivergence, ReconciliationMismatch, ArchiveMissing, ChecksumMismatch, BinanceRequestFailed, LocationRestricted, RateLimited, VerificationSource (+2 more)

### Community 147 - "scripts"
Cohesion: 0.50
Nodes (4): scripts, build, dev, e2e

### Community 148 - "0001_01_01_000002_create_jobs_table.php"
Cohesion: 0.33
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

### Community 157 - "strategies-screen.blade.php"
Cohesion: 0.33
Nodes (5): open, promote, promoteDeployment, run, save

### Community 159 - "extra"
Cohesion: 0.67
Nodes (3): extra, laravel, dont-discover

### Community 174 - "App\Simulation\StrategyLibrary"
Cohesion: 0.18
Nodes (6): {closure#1}(), CompileStrategy, StrategiesScreen, StrategyLibrary, CheckResult, SaveResult

### Community 175 - "Carbon\CarbonImmutable"
Cohesion: 0.06
Nodes (24): Backfill, {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#3}(), {closure#5}(), {closure#7}() (+16 more)

### Community 176 - "Laravel\Mcp\Request"
Cohesion: 0.30
Nodes (4): ChartTool, GetCandles, GetIndicators, ChartSeries

### Community 177 - "DocumentCheckerTest.php"
Cohesion: 0.30
Nodes (10): checkDocument(), {closure#1}(), {closure#2}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}(), {closure#8}() (+2 more)

### Community 178 - "RiskEventKind"
Cohesion: 0.24
Nodes (6): RiskEvent, RiskEventKind, DailyLossHalt, DrawdownFlatten, DrawdownHalt, WeeklyLossHalt

### Community 180 - "Tokens"
Cohesion: 0.13
Nodes (5): CreateMcpToken, RevokeMcpToken, AuthenticateMcpToken, McpToken, Tokens

### Community 181 - "User"
Cohesion: 0.07
Nodes (10): User, TerminalQrCode, UserFactory, {closure#1}(), {closure#3}(), {closure#4}(), {closure#5}(), expectedModules() (+2 more)

### Community 182 - "Timeframe"
Cohesion: 0.10
Nodes (15): InvalidCandle, CarryTemplate, Timeframe, FifteenMinutes, FiveMinutes, FourHours, OneDay, OneHour (+7 more)

### Community 184 - "App\Strategy\Dsl\Document\StrategySpec"
Cohesion: 0.26
Nodes (9): {closure#2}(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#7}(), readBackDocument() (+1 more)

### Community 189 - "MarketType"
Cohesion: 0.13
Nodes (8): BinanceEndpoints, BinanceRestClient, Json, RequestFailed, SyncInstruments, MarketType, Perp, Spot

### Community 190 - "StrategyVersion"
Cohesion: 0.21
Nodes (4): {closure#1}(), ListStrategies, {closure#3}(), StrategyVersion

### Community 192 - ".handle"
Cohesion: 0.14
Nodes (4): GridBacktest, ReadsGridOptions, StrategyGates, StrategySpec

### Community 193 - "ScalpingCollectorTest.php"
Cohesion: 0.35
Nodes (9): aggTrade(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#5}(), {closure#6}(), collector(), defaultBinanceFakes() (+1 more)

### Community 195 - "2026_11_09_000016_create_market_universe_tables.php"
Cohesion: 0.40
Nodes (3): {closure#1}(), {closure#2}(), up()

### Community 196 - "Quantity"
Cohesion: 0.24
Nodes (3): Quantity, {closure#10}(), {closure#9}()

### Community 197 - "2026_11_09_000017_create_live_tables.php"
Cohesion: 0.33
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

### Community 198 - "start-local.ps1"
Cohesion: 0.27
Nodes (4): Stop-Postgres(), Stop-ProcessTree(), Test-PostgresRunning(), Write-Step()

### Community 200 - ".times"
Cohesion: 0.09
Nodes (11): Fill, PerpPosition, {closure#3}(), {closure#3}(), {closure#54}(), {closure#55}(), Perpetual, {closure#16}() (+3 more)

### Community 205 - "2026_11_09_000015_create_scalping_tables.php"
Cohesion: 0.33
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

## Knowledge Gaps
- **147 isolated node(s):** `Backtest`, `Live`, `Paper`, `Testnet`, `Exit` (+142 more)
  These have ≤1 connection - possible missing edges. (Counts symbols only; 941 node(s) total have ≤1 connection when file, concept and rationale nodes are included.)
- **52 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `Decimal` connect `Decimal` to `CompiledStrategyTest.php`, `engineHarness`, `CanonicalJson`, `Engine.php`, `App\Simulation\Backtest`, `DocumentChecker`, `.parse`, `CompiledStrategy.php`, `.of`, `Illuminate\Database\ConnectionInterface`, `.of`, `Candle`, `.isGreaterThan`, `.evaluate`, `PolicyLimits`, `Marks`, `Ledger`, `QualityCheck`, `StrategyIdentity`, `.of`, `WarmingVenue`, `App\Strategy\Dsl\Compiler\CompiledStrategy`, `.register`, `Symbol`, `EnginePerpTest.php`, `App\Strategy\Dsl\Document\DocumentChecker`, `SimulatedVenue`, `Timeframe`, `Carbon\CarbonImmutable`, `Strategy`, `DocumentCheckerTest.php`, `RiskEventKind`, `RiskPolicy`, `Timeframe`, `InvalidArgumentException`, `SizedStrategyTest.php`, `App\Strategy\Dsl\Document\StrategySpec`, `IndicatorReferenceTest.php`, `RunBacktestCommandTest.php`, `MarketType`, `DecimalMath`, `ForwardingVenue`, `.handle`, `.run`, `FundingCarry`, `App\Strategy\Dsl\Document\DocumentChecker`, `InvalidArgumentException`, `Quantity`, `RulesHistory`, `.times`, `.measure`, `DecimalMath`, `MonteCarlo`, `App\Support\Number\Decimal`, `InstrumentStatus`, `.fromStrings`, `StrategyGates.php`, `BinanceVenueTest.php`, `ChartScreen.php`, `DecisionRecord`, `ChartSeriesTest.php`, `ExitReason`, `SimClock`?**
  _High betweenness centrality (0.134) - this node is a cross-community bridge._
- **What connects `Backtest`, `Live`, `Paper` to the rest of the system?**
  _147 weakly-connected nodes found - possible missing edges._
- **Should `engineHarness` be split into smaller, more focused modules?**
  _Cohesion score 0.09184149184149185 - nodes in this community are weakly interconnected._
- **Why does `Candle` connect `Candle` to `CompiledStrategyTest.php`, `engineHarness`, `.parse`, `CompiledStrategy.php`, `.of`, `ExchangeVerificationTest.php`, `.evaluate`, `.isGreaterThan`, `CandleRepositoryTest.php`, `Instrument`, `.of`, `Symbol`, `EnginePerpTest.php`, `Decimal`, `Timeframe`, `Carbon\CarbonImmutable`, `Timeframe`, `IndicatorReferenceTest.php`, `.run`, `RulesHistory`, `.times`, `.parse`, `DecimalMath`, `App\Support\Number\Decimal`, `.timestamp`, `.fromStrings`, `StrategyGates.php`, `ChartSeriesTest.php`?**
  _High betweenness centrality (0.037) - this node is a cross-community bridge._
- **Should `CanonicalJson` be split into smaller, more focused modules?**
  _Cohesion score 0.13846153846153847 - nodes in this community are weakly interconnected._
- **Why does `Symbol` connect `Symbol` to `CompiledStrategyTest.php`, `engineHarness`, `CanonicalJson`, `Engine.php`, `App\Simulation\Backtest`, `DocumentChecker`, `.parse`, `.of`, `.of`, `ExchangeVerificationTest.php`, `.isGreaterThan`, `PolicyLimits`, `CandleRepositoryTest.php`, `Marks`, `Instrument`, `Ledger`, `StrategyIdentity`, `.of`, `WarmingVenue`, `Laravel\Mcp\Response`, `.register`, `EnginePerpTest.php`, `Decimal`, `Timeframe`, `Carbon\CarbonImmutable`, `Strategy`, `Timeframe`, `InvalidArgumentException`, `App\Strategy\Dsl\Document\StrategySpec`, `RunBacktestCommandTest.php`, `MarketType`, `ForwardingVenue`, `.handle`, `.run`, `FundingCarry`, `App\Strategy\Dsl\Document\DocumentChecker`, `RulesHistory`, `.times`, `.measure`, `InstrumentStatus`, `StrategyGates.php`, `BinanceVenueTest.php`, `ChartScreen.php`, `DecisionRecord`, `ChartSeriesTest.php`, `App\Support\Clock\Clock`, `ExitReason`, `SimClock`?**
  _High betweenness centrality (0.028) - this node is a cross-community bridge._
- **Should `Engine.php` be split into smaller, more focused modules?**
  _Cohesion score 0.054061624649859946 - nodes in this community are weakly interconnected._