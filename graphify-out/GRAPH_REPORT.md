# Graph Report - trading-platform  (2026-11-09)

## Corpus Check
- 641 files · ~269,345 words
- Verdict: corpus is large enough that graph structure adds value.
- Unclassified: 103 file(s) not represented in the graph (top: .zip 38, .CHECKSUM 38, (none) 19)

## Summary
- 5146 nodes · 17775 edges · 223 communities (161 shown, 62 thin omitted)
- Extraction: 91% EXTRACTED · 9% INFERRED · 0% AMBIGUOUS · INFERRED: 1625 edges (avg confidence: 0.85)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `72bd8229`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- SimulatedPerpVenueTest.php
- engineHarness
- CanonicalJson
- DslError
- Engine
- .parse
- DocumentChecker
- .now
- CompiledStrategy.php
- SimulatedVenueTest.php
- BinanceVenueTest.php
- .of
- Candle
- ExchangeVerificationTest.php
- QualityCheck
- Illuminate\Contracts\Config\Repository
- Node
- Clock
- Instrument
- ScreensTest.php
- Ledger
- Illuminate\Database\Schema\Blueprint
- generate.py
- CompiledStrategy.php
- Symbol
- .times
- TerminalQrCodeTest.php
- ForwardingVenue
- .of
- Carbon\CarbonImmutable
- RuntimeException
- oi-momentum-long.strategy.yaml
- StrategyIdentity
- EnginePerpTest.php
- BinanceArchive
- LadderTest.php
- ScalpingCollectorTest.php
- App\Market\Data\FundingRate
- OrderManagerTest.php
- BacktestTest.php
- App\Strategy\Dsl\Document\DocumentChecker
- ema-cross-scalp-long.strategy.yaml
- Decimal
- App\Market\Data\Candle
- App
- Timeframe
- PaperEngineTest.php
- package.json
- OrderBook
- BinanceRestClientTest.php
- ema-cross-scalp-short.strategy.yaml
- ChartScreen.php
- ReadBack
- .isPositive
- postgres
- Symbol
- CompiledStrategy
- donchian-breakout-short.strategy.yaml
- IndicatorsTest.php
- support.mjs
- RunBacktestCommandTest.php
- IndicatorReferenceTest.php
- Psr\Log\LoggerInterface
- FundingRate
- AppServiceProvider.php
- donchian-breakout.strategy.yaml
- .run
- CompiledStrategyTest.php
- Kind
- MarketBreakersTest.php
- App\Market\Binance\BinanceRestClient
- DailyReport
- Illuminate\Console\Command
- scripts
- ma-trend-short.strategy.yaml
- CandleFeedTest.php
- .measure
- composer.json
- jobs
- postgres
- ma-trend.strategy.yaml
- .zero
- MonteCarlo
- App\Support\Number\Decimal
- pint/composer.json
- App\Agents\Mcp\TradingServer
- RunSimulation.php
- ExitReason
- require-dev
- scripts-descriptions
- WarmingVenue
- .fromStrings
- config
- uses
- test
- with
- AppServiceProvider.php
- jobs
- .parse
- psr-4
- require
- Illuminate\Http\Request
- StrategyGates.php
- uses
- .handle
- devDependencies
- Laravel\Mcp\Response
- Strategy
- ScalpingFunctionsTest.php
- .of
- buy-and-hold.strategy.yaml
- PreparesBacktests.php
- TokensTest.php
- ArchiveImportTest.php
- User
- 2026_11_09_000010_add_two_factor_columns_to_users_table.php
- InvalidArgumentException
- .register
- funding-carry.strategy.yaml
- McpServerTest.php
- env.mjs
- chart.js
- TelegramSettings
- Percent
- settings-screen.blade.php
- App\Simulation\StrategyLibrary
- steps
- 2026_11_09_000014_create_health_tables.php
- .zero
- App\Market\Models\Instrument
- App\Simulation\Backtest
- CandleRepositoryTest.php
- Illuminate\Support\Facades\Schema
- App\Support\Clock\Clock
- App\Support\Symbol
- scripts
- 2026_11_08_000007_create_strategy_gate_tables.php
- fake-telegram.mjs
- Illuminate\Database\Migrations\Migration
- Window
- 2026_11_08_000004_create_strategy_versions_tables.php
- 2026_11_08_000006_create_mcp_tables.php
- .compute
- .evaluate
- strategies-screen.blade.php
- extra
- app.blade.php
- halt
- remove
- account-screen.blade.php
- GlobalsTest.php
- DecimalMath
- .evaluate
- OrderManagerTest.php
- 2026_11_09_000015_create_scalping_tables.php
- .handle
- 2026_11_09_000012_create_account_results_tables.php
- 2026_11_09_000023_create_universe_members_table.php
- SettingsScreenTest.php
- FundingCarry
- CompiledStrategy
- App\Agents\Mcp\Tools\ListTrials
- RunEngine.php
- MarketType
- PolicyLimits
- RiskPolicy
- 0001_01_01_000002_create_jobs_table.php
- 2026_11_09_000009_create_exchange_keys_table.php
- 2026_11_09_000018_add_effective_at_to_deployments.php
- 2026_11_09_000016_create_market_universe_tables.php
- CandleStreamTest.php
- LiveAccountTest.php
- start-local.ps1
- App\Execution\Journal\DatabaseDecisionLog
- Price
- SimulationQueue
- 0001_01_01_000000_create_users_table.php
- RuntimeException
- RulesHistory
- UserFactory.php
- StrategyVersion
- SimulationJobsTest.php
- RiskEventKind
- Quantity
- bootstrap/app.php
- 2026_11_09_000020_add_lifetime_to_instruments.php
- ResearchUniverse.php
- AppServiceProvider
- .number
- EngineMode
- 2026_11_09_000013_add_daily_report_to_notification_settings.php
- 2026_11_09_000026_create_liquidation_tables.php
- 2026_11_09_000019_create_forming_candles_table.php
- 2026_11_09_000022_create_daily_stats_table.php
- 2026_11_09_000024_create_positioning_table.php
- OverReportingVenue

## God Nodes (most connected - your core abstractions)
1. `Decimal` - 500 edges
2. `Side` - 205 edges
3. `Symbol` - 191 edges
4. `App` - 168 edges
5. `Candle` - 134 edges
6. `Timeframe` - 118 edges
7. `Price` - 100 edges
8. `Position` - 96 edges
9. `SimClock` - 87 edges
10. `Clock` - 73 edges

## Surprising Connections (you probably didn't know these)
- `{closure#1}()` --calls--> `App`  [INFERRED]
  tests/Feature/Support/ClockBindingTest.php → app/Notifications/SettingSource.php
- `{closure#13}()` --calls--> `App`  [INFERRED]
  tests/Feature/Web/SettingsScreenTest.php → app/Notifications/SettingSource.php
- `{closure#8}()` --calls--> `App`  [INFERRED]
  tests/Feature/Web/SettingsScreenTest.php → app/Notifications/SettingSource.php
- `{closure#3}()` --calls--> `App`  [INFERRED]
  tests/Feature/Network/BinancePublicDataTest.php → app/Notifications/SettingSource.php
- `{closure#5}()` --calls--> `App`  [INFERRED]
  tests/Feature/Network/BinancePublicDataTest.php → app/Notifications/SettingSource.php

## Import Cycles
- None detected.

## Communities (223 total, 62 thin omitted)

### Community 0 - "SimulatedPerpVenueTest.php"
Cohesion: 0.21
Nodes (27): PriceMinute, {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#15}(), {closure#18}() (+19 more)

### Community 1 - "engineHarness"
Cohesion: 0.10
Nodes (40): EngineCommand, ScriptedStrategy, {closure#1}(), {closure#10}(), {closure#13}(), {closure#14}(), {closure#16}(), {closure#17}() (+32 more)

### Community 2 - "CanonicalJson"
Cohesion: 0.22
Nodes (7): CanonicalJson, {closure#8}(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}()

### Community 3 - "DslError"
Cohesion: 0.10
Nodes (16): {closure#1}(), {closure#1}(), {closure#5}(), {closure#7}(), {closure#8}(), DslError, YamlList, YamlReader (+8 more)

### Community 4 - "Engine"
Cohesion: 0.06
Nodes (9): EngineRecord, {closure#1}(), {closure#11}(), {closure#2}(), {closure#3}(), {closure#9}(), Engine, ReconciliationMismatch (+1 more)

### Community 5 - ".parse"
Cohesion: 0.13
Nodes (29): {closure#17}(), {closure#5}(), carryDecisions(), {closure#10}(), {closure#11}(), {closure#13}(), {closure#14}(), {closure#15}() (+21 more)

### Community 7 - ".now"
Cohesion: 0.14
Nodes (7): {closure#1}(), {closure#2}(), Deployment, {closure#1}(), {closure#1}(), Ladder, LadderStatus

### Community 8 - "CompiledStrategy.php"
Cohesion: 0.15
Nodes (5): {closure#47}(), {closure#48}(), {closure#50}(), Compiler, Program

### Community 9 - "SimulatedVenueTest.php"
Cohesion: 0.19
Nodes (45): Side, Buy, Sell, btc(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}() (+37 more)

### Community 10 - "BinanceVenueTest.php"
Cohesion: 0.11
Nodes (16): {closure#2}(), BinanceVenue, BinanceApiError, BinanceUnreachable, SignedBinanceClient, binanceVenue(), {closure#1}(), {closure#2}() (+8 more)

### Community 11 - ".of"
Cohesion: 0.13
Nodes (17): AssetMismatch, Money, {closure#2}(), {closure#1}(), usdt(), {closure#1}(), {closure#11}(), {closure#12}() (+9 more)

### Community 12 - "Candle"
Cohesion: 0.07
Nodes (46): {closure#1}(), {closure#3}(), Candle, InvalidCandle, {closure#1}(), {closure#2}(), VolumeProfile, {closure#42}() (+38 more)

### Community 13 - "ExchangeVerificationTest.php"
Cohesion: 0.15
Nodes (23): {closure#12}(), {closure#2}(), {closure#3}(), {closure#5}(), {closure#7}(), {closure#9}(), verifiedInstrument(), {closure#1}() (+15 more)

### Community 15 - "Illuminate\Contracts\Config\Repository"
Cohesion: 0.16
Nodes (3): {closure#1}(), {closure#2}(), PreparesBacktests

### Community 16 - "Node"
Cohesion: 0.11
Nodes (21): Binary, Lexer, Parser, Token, TokenKind, Colon, Comma, Dot (+13 more)

### Community 17 - "Clock"
Cohesion: 0.06
Nodes (20): AuthenticateMcpToken, McpToken, FlattenEngine, HaltEngine, IssuesCommands, PauseStrategy, ResumeEngine, {closure#5}() (+12 more)

### Community 18 - "Instrument"
Cohesion: 0.05
Nodes (21): BackfillStep, CandleRepository, {closure#1}(), CandleSource, Archive, Rest, Stream, UpsertResult (+13 more)

### Community 19 - "ScreensTest.php"
Cohesion: 0.14
Nodes (22): {closure#1}(), {closure#1}(), chartLiveQuery(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}() (+14 more)

### Community 20 - "Ledger"
Cohesion: 0.15
Nodes (28): Ledger, balanceOf(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#15}() (+20 more)

### Community 21 - "Illuminate\Database\Schema\Blueprint"
Cohesion: 0.14
Nodes (13): {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#1}(), {closure#2}(), {closure#3}() (+5 more)

### Community 22 - "generate.py"
Cohesion: 0.07
Nodes (30): atr(), bars(), bb(), change(), dmi(), donchian(), ema(), highest() (+22 more)

### Community 23 - "CompiledStrategy.php"
Cohesion: 0.09
Nodes (14): {closure#3}(), {closure#6}(), {closure#7}(), {closure#8}(), {closure#9}(), Library, {closure#2}(), {closure#5}() (+6 more)

### Community 24 - "Symbol"
Cohesion: 0.06
Nodes (13): BuyAndHold, DonchianBreakout, MovingAverageTrend, BuildableStrategy, Close, Intent, OpenLong, OpenShort (+5 more)

### Community 25 - ".times"
Cohesion: 0.11
Nodes (6): Fill, PerpPosition, Perpetual, {closure#16}(), {closure#17}(), {closure#5}()

### Community 26 - "TerminalQrCodeTest.php"
Cohesion: 0.20
Nodes (7): TerminalQrCode, {closure#3}(), {closure#4}(), {closure#5}(), expectedModules(), readColoured(), readPlain()

### Community 28 - ".of"
Cohesion: 0.06
Nodes (76): InstrumentRules, AccountView, RejectionReason, BelowMinimum, Breaker, Cooldown, DuplicateIntent, EntryWorking (+68 more)

### Community 29 - "Carbon\CarbonImmutable"
Cohesion: 0.06
Nodes (13): ArchivePeriod, Backfill, {closure#1}(), BackfillReport, BackfillMarketData, SelectsInstruments, FormingCandle, Liquidations (+5 more)

### Community 31 - "oi-momentum-long.strategy.yaml"
Cohesion: 0.07
Nodes (27): entries, long, exits, stop_loss, take_profit, when, fails_when, family (+19 more)

### Community 32 - "StrategyIdentity"
Cohesion: 0.20
Nodes (5): StrategyIdentity, RenamedStrategy, {closure#1}(), {closure#2}(), {closure#3}()

### Community 33 - "EnginePerpTest.php"
Cohesion: 0.24
Nodes (16): {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#14}(), {closure#2}(), {closure#5}(), {closure#6}() (+8 more)

### Community 34 - "BinanceArchive"
Cohesion: 0.13
Nodes (7): BinanceArchive, {closure#1}(), {closure#2}(), ExchangeNumber, TradeFlowBuilder, {closure#1}(), {closure#2}()

### Community 35 - "LadderTest.php"
Cohesion: 0.17
Nodes (13): {closure#7}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#8}(), {closure#9}() (+5 more)

### Community 36 - "ScalpingCollectorTest.php"
Cohesion: 0.14
Nodes (18): WallClock, aggTrade(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#6}(), collector() (+10 more)

### Community 37 - "App\Market\Data\FundingRate"
Cohesion: 0.16
Nodes (6): {closure#15}(), {closure#1}(), {closure#3}(), PerpetualView, TradeMemory, {closure#8}()

### Community 38 - "OrderManagerTest.php"
Cohesion: 0.12
Nodes (14): OrderRole, Entry, Exit, Flatten, Stop, Target, TrackedOrder, {closure#1}() (+6 more)

### Community 39 - "BacktestTest.php"
Cohesion: 0.12
Nodes (18): FeeSchedule, CandleFeed, Backtest, {closure#1}(), RunDefinition, ReportBuilder, {closure#2}(), {closure#3}() (+10 more)

### Community 40 - "App\Strategy\Dsl\Document\DocumentChecker"
Cohesion: 0.08
Nodes (24): Argument, BoolLiteral, Call, DurationLiteral, Identifier, Lookback, Node, NumberLiteral (+16 more)

### Community 41 - "ema-cross-scalp-long.strategy.yaml"
Cohesion: 0.06
Nodes (33): entries, long, exits, stop_loss, take_profit, time_stop, when, fails_when (+25 more)

### Community 42 - "Decimal"
Cohesion: 0.04
Nodes (46): RoundTrip, {closure#1}(), {closure#2}(), {closure#3}(), {closure#11}(), {closure#12}(), {closure#1}(), {closure#2}() (+38 more)

### Community 43 - "App\Market\Data\Candle"
Cohesion: 0.07
Nodes (20): Liquidity, Maker, Taker, OrderStatus, Cancelled, Expired, Filled, New (+12 more)

### Community 44 - "App"
Cohesion: 0.08
Nodes (41): SendTelegram, App, {closure#1}(), {closure#2}(), {closure#3}(), {closure#2}(), fakeArchiveListing(), {closure#1}() (+33 more)

### Community 45 - "Timeframe"
Cohesion: 0.07
Nodes (39): CandleAggregator, CandleSequenceBroken, {closure#2}(), {closure#4}(), CarryTemplate, {closure#2}(), {closure#8}(), Timeframe (+31 more)

### Community 46 - "PaperEngineTest.php"
Cohesion: 0.05
Nodes (41): McpCall, Account, AccountEquity, AccountSnapshot, AccountTrade, PlainDecimal, ExchangeKey, JournalEntry (+33 more)

### Community 47 - "package.json"
Cohesion: 0.15
Nodes (17): dependencies, codemirror@^6.0.2, @codemirror/lang-yaml@^6.1.3, @codemirror/language@^6.13.1, @lezer/highlight@^1.2.5, lightweight-charts@^5.2.1, optionalDependencies, @laravel/multiplex@^0.4.1 (+9 more)

### Community 48 - "OrderBook"
Cohesion: 0.23
Nodes (7): OrderBook, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), levels(), update()

### Community 49 - "BinanceRestClientTest.php"
Cohesion: 0.08
Nodes (31): {closure#6}(), {closure#7}(), {closure#1}(), {closure#3}(), binanceClient(), {closure#1}(), {closure#11}(), {closure#13}() (+23 more)

### Community 50 - "ema-cross-scalp-short.strategy.yaml"
Cohesion: 0.06
Nodes (33): entries, short, exits, stop_loss, take_profit, time_stop, when, fails_when (+25 more)

### Community 51 - "ChartScreen.php"
Cohesion: 0.05
Nodes (37): {closure#2}(), ChartScreen, {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), ChartHistoryMissing, EntrySpec (+29 more)

### Community 53 - ".isPositive"
Cohesion: 0.10
Nodes (9): {closure#6}(), {closure#10}(), {closure#5}(), {closure#4}(), {closure#12}(), {closure#2}(), {closure#5}(), Normal (+1 more)

### Community 54 - "postgres"
Cohesion: 0.10
Nodes (19): POSTGRES_DB, POSTGRES_PASSWORD, POSTGRES_USER, interval, retries, test, timeout, environment (+11 more)

### Community 55 - "Symbol"
Cohesion: 0.14
Nodes (10): VenuePosition, {closure#12}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#1}(), SimulatedPosition, Symbol (+2 more)

### Community 56 - "CompiledStrategy"
Cohesion: 0.14
Nodes (9): SizedStrategy, {closure#1}(), {closure#1}(), {closure#2}(), identity(), limits(), onCandle(), subscriptions() (+1 more)

### Community 57 - "donchian-breakout-short.strategy.yaml"
Cohesion: 0.07
Nodes (26): entries, short, default, range, step, default, range, step (+18 more)

### Community 58 - "IndicatorsTest.php"
Cohesion: 0.09
Nodes (22): Atr, Donchian, Ema, Sma, ValueIndicator, bar(), {closure#1}(), {closure#10}() (+14 more)

### Community 59 - "support.mjs"
Cohesion: 0.26
Nodes (9): @playwright/test, confirmed(), checkLayout(), EMAIL, freshCode(), PASSWORD, reauthenticate(), totp() (+1 more)

### Community 60 - "RunBacktestCommandTest.php"
Cohesion: 0.10
Nodes (29): BacktestRun, {closure#1}(), {closure#4}(), {closure#6}(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}() (+21 more)

### Community 61 - "IndicatorReferenceTest.php"
Cohesion: 0.05
Nodes (28): BarIndicator, Change, DonchianChannel, Highest, Hma, Keltner, Macd, Natr (+20 more)

### Community 62 - "Psr\Log\LoggerInterface"
Cohesion: 0.08
Nodes (9): BinanceEndpoints, Sleeper, DepthStream, CandleStream, ScalpingCollector, StreamTransport, {closure#4}(), now() (+1 more)

### Community 63 - "FundingRate"
Cohesion: 0.08
Nodes (16): {closure#2}(), {closure#4}(), FundingRate, {closure#2}(), DatabaseCandleFeed, FormingCandles, {closure#1}(), FundingRateRepository (+8 more)

### Community 64 - "AppServiceProvider.php"
Cohesion: 0.14
Nodes (5): SystemSleeper, LocalDiskUsage, {closure#3}(), {closure#5}(), {closure#7}()

### Community 65 - "donchian-breakout.strategy.yaml"
Cohesion: 0.07
Nodes (26): entries, long, default, range, step, default, range, step (+18 more)

### Community 66 - ".run"
Cohesion: 0.10
Nodes (27): Participant, PerformanceMetrics, Report, btcMinutes(), {closure#1}(), {closure#2}(), {closure#3}(), referencePrice() (+19 more)

### Community 67 - "CompiledStrategyTest.php"
Cohesion: 0.20
Nodes (14): PositionView, StrategyContext, {closure#24}(), aggregatedBars(), {closure#11}(), {closure#13}(), {closure#16}(), {closure#17}() (+6 more)

### Community 68 - "Kind"
Cohesion: 0.08
Nodes (26): {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#9}(), ParameterSpec, DslWriter, Scope (+18 more)

### Community 69 - "MarketBreakersTest.php"
Cohesion: 0.29
Nodes (7): {closure#4}(), BreakerEvent, MarketBreakers, breakerMinute(), {closure#1}(), {closure#2}(), {closure#3}()

### Community 70 - "App\Market\Binance\BinanceRestClient"
Cohesion: 0.07
Nodes (14): ArchiveCatalog, {closure#7}(), BinanceRequestFailed, {closure#1}(), {closure#2}(), {closure#5}(), LocationRestricted, RateLimited (+6 more)

### Community 72 - "Illuminate\Console\Command"
Cohesion: 0.10
Nodes (11): CreateMcpToken, RevokeMcpToken, Tokens, ApproveLive, ConfirmsOperator, CreateAccount, CheckMarketData, {closure#1}() (+3 more)

### Community 73 - "scripts"
Cohesion: 0.15
Nodes (13): scripts, analyse, dev, format, mutate, post-autoload-dump, post-update-cmd, pre-package-uninstall (+5 more)

### Community 74 - "ma-trend-short.strategy.yaml"
Cohesion: 0.07
Nodes (26): entries, short, exits, stop_loss, when, fails_when, family, default (+18 more)

### Community 75 - "CandleFeedTest.php"
Cohesion: 0.24
Nodes (15): {closure#1}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#15}(), {closure#2}(), {closure#3}() (+7 more)

### Community 76 - ".measure"
Cohesion: 0.18
Nodes (10): EquityTracker, Performance, {closure#1}(), {closure#2}(), {closure#3}(), {closure#5}(), {closure#6}(), {closure#7}() (+2 more)

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

### Community 81 - ".zero"
Cohesion: 0.09
Nodes (31): Statistics, Cscv, DeflatedSharpe, {closure#3}(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}() (+23 more)

### Community 82 - "MonteCarlo"
Cohesion: 0.16
Nodes (10): {closure#4}(), MonteCarlo, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}() (+2 more)

### Community 83 - "App\Support\Number\Decimal"
Cohesion: 0.07
Nodes (56): {closure#3}(), {closure#5}(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}() (+48 more)

### Community 84 - "pint/composer.json"
Cohesion: 0.20
Nodes (9): config, sort-packages, description, license, name, require, laravel/pint, php (+1 more)

### Community 86 - "RunSimulation.php"
Cohesion: 0.15
Nodes (3): ImportMarketData, RunSimulation, SimulationJob

### Community 87 - "ExitReason"
Cohesion: 0.15
Nodes (26): ExitReason, Flatten, Liquidation, Signal, Stop, Target, LedgerViolation, {closure#1}() (+18 more)

### Community 88 - "require-dev"
Cohesion: 0.22
Nodes (9): require-dev, fakerphp/faker, larastan/larastan, laravel/pail, laravel/pao, mockery/mockery, nunomaduro/collision, pestphp/pest (+1 more)

### Community 89 - "scripts-descriptions"
Cohesion: 0.25
Nodes (8): scripts-descriptions, analyse, format, mutate, test, test:network, test:testnet, tools:install

### Community 91 - ".fromStrings"
Cohesion: 0.11
Nodes (10): {closure#1}(), {closure#2}(), RegimeLabels, DirectionalMovement, {closure#1}(), {closure#2}(), {closure#4}(), {closure#7}() (+2 more)

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
Cohesion: 0.09
Nodes (7): {closure#2}(), StreamCandles, Universe, DiskUsage, {closure#1}(), HealthChecks, HealthResult

### Community 97 - "jobs"
Cohesion: 0.25
Nodes (8): name, runs-on, name, runs-on, steps, jobs, analyse, audit

### Community 98 - ".parse"
Cohesion: 0.33
Nodes (7): AstPrinter, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), sexpr(), {closure#5}()

### Community 99 - "psr-4"
Cohesion: 0.40
Nodes (5): autoload, psr-4, App\\, Database\\Factories\\, Database\\Seeders\\

### Community 100 - "require"
Cohesion: 0.22
Nodes (9): require, bacon/bacon-qr-code, laravel/fortify, laravel/framework, laravel/mcp, laravel/tinker, livewire/livewire, php (+1 more)

### Community 102 - "Illuminate\Http\Request"
Cohesion: 0.18
Nodes (4): ChartLiveController, {closure#8}(), {closure#3}(), {closure#4}()

### Community 103 - "StrategyGates.php"
Cohesion: 0.09
Nodes (15): {closure#2}(), {closure#3}(), {closure#4}(), {closure#6}(), {closure#8}(), {closure#2}(), {closure#1}(), {closure#2}() (+7 more)

### Community 104 - "uses"
Cohesion: 0.21
Nodes (12): steps, name, runs-on, steps, frontend, lint, name, runs-on (+4 more)

### Community 106 - "devDependencies"
Cohesion: 0.18
Nodes (12): devDependencies, concurrently@^10.0.3, laravel-vite-plugin@^3.1, @playwright/test@1.63.0, tailwindcss@^4.0.0, @tailwindcss/vite@^4.0.0, vite@^8.0.0, concurrently (+4 more)

### Community 108 - "Laravel\Mcp\Response"
Cohesion: 0.10
Nodes (11): ChartTool, CompileStrategy, GetCandles, GetFunding, GetIndicators, GetJob, GetStrategy, ListInstruments (+3 more)

### Community 117 - "Strategy"
Cohesion: 0.13
Nodes (21): {closure#3}(), LimitedStrategy, Strategy, baselineDocument(), baselinePairs(), {closure#10}(), {closure#11}(), {closure#12}() (+13 more)

### Community 118 - "ScalpingFunctionsTest.php"
Cohesion: 0.08
Nodes (29): {closure#11}(), {closure#13}(), {closure#3}(), {closure#5}(), Positioning, {closure#1}(), {closure#10}(), {closure#11}() (+21 more)

### Community 119 - ".of"
Cohesion: 0.10
Nodes (32): Band, {closure#8}(), {closure#9}(), balances(), {closure#41}(), bandReport(), {closure#1}(), {closure#2}() (+24 more)

### Community 120 - "buy-and-hold.strategy.yaml"
Cohesion: 0.12
Nodes (16): entries, long, exits, stop_loss, fails_when, family, hypothesis, when (+8 more)

### Community 121 - "PreparesBacktests.php"
Cohesion: 0.07
Nodes (12): {closure#1}(), {closure#2}(), {closure#1}(), {closure#1}(), {closure#2}(), {closure#5}(), {closure#7}(), {closure#8}() (+4 more)

### Community 122 - "TokensTest.php"
Cohesion: 0.31
Nodes (6): {closure#3}(), {closure#4}(), contrastRatio(), contrastRules(), relativeLuminance(), themeTokens()

### Community 123 - "ArchiveImportTest.php"
Cohesion: 0.31
Nodes (10): archiveBytes(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}() (+2 more)

### Community 126 - "InvalidArgumentException"
Cohesion: 0.05
Nodes (13): LiveGate, OpenAccount, StoreKeys, LiveApproval, ReauthController, AccountScreen, ModeBadge, {closure#2}() (+5 more)

### Community 127 - ".register"
Cohesion: 0.06
Nodes (58): SimClock, {closure#9}(), {closure#1}(), {closure#1}(), {closure#4}(), {closure#10}(), {closure#12}(), {closure#13}() (+50 more)

### Community 128 - "funding-carry.strategy.yaml"
Cohesion: 0.12
Nodes (15): fails_when, family, hypothesis, params, enter_when, exit_when, margin_buffer, max_leverage_perp (+7 more)

### Community 129 - "McpServerTest.php"
Cohesion: 0.30
Nodes (11): actAsToken(), {closure#10}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}() (+3 more)

### Community 130 - "env.mjs"
Cohesion: 0.27
Nodes (4): sizes, BASE_URL, E2E_ENV, TELEGRAM_URL

### Community 131 - "chart.js"
Cohesion: 0.27
Nodes (7): faded(), options(), priceChart(), token(), volumeColor(), dslEditor(), highlight

### Community 132 - "TelegramSettings"
Cohesion: 0.13
Nodes (6): NotificationSettings, SettingSource, Env, None, TelegramClient, TelegramSettings

### Community 133 - "Percent"
Cohesion: 0.24
Nodes (3): Percent, {closure#14}(), {closure#15}()

### Community 135 - "settings-screen.blade.php"
Cohesion: 0.15
Nodes (12): approveLive, confirm, forgetKey, forgetTelegram, importUniverse, monitor, notify, removeInstrument (+4 more)

### Community 137 - "App\Simulation\StrategyLibrary"
Cohesion: 0.17
Nodes (5): ListStrategies, StrategiesScreen, StrategyLibrary, CheckResult, SaveResult

### Community 138 - "steps"
Cohesion: 0.33
Nodes (6): name, runs-on, services, steps, e2e, if

### Community 139 - "2026_11_09_000014_create_health_tables.php"
Cohesion: 0.33
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

### Community 140 - ".zero"
Cohesion: 0.15
Nodes (9): CostModel, SimulatedVenue, {closure#23}(), {closure#40}(), {closure#44}(), {closure#45}(), {closure#46}(), {closure#47}() (+1 more)

### Community 141 - "App\Market\Models\Instrument"
Cohesion: 0.13
Nodes (13): {closure#10}(), {closure#12}(), {closure#2}(), {closure#4}(), {closure#6}(), {closure#8}(), {closure#9}(), BookDepth (+5 more)

### Community 142 - "App\Simulation\Backtest"
Cohesion: 0.13
Nodes (11): InMemoryDecisionLog, Marks, {closure#1}(), {closure#2}(), BacktestResult, ParticipantResult, {closure#1}(), {closure#3}() (+3 more)

### Community 143 - "CandleRepositoryTest.php"
Cohesion: 0.31
Nodes (11): candleStore(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}() (+3 more)

### Community 144 - "Illuminate\Support\Facades\Schema"
Cohesion: 0.15
Nodes (3): {closure#1}(), {closure#1}(), {closure#1}()

### Community 145 - "App\Support\Clock\Clock"
Cohesion: 0.04
Nodes (16): BuildUniverse, Heartbeats, HistoryImports, {closure#1}(), InstrumentSync, ResearchUniverse, SyncResult, LiquidationRepository (+8 more)

### Community 146 - "App\Support\Symbol"
Cohesion: 0.08
Nodes (14): {closure#1}(), {closure#1}(), {closure#2}(), {closure#2}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#4}() (+6 more)

### Community 147 - "scripts"
Cohesion: 0.50
Nodes (4): scripts, build, dev, e2e

### Community 151 - "Illuminate\Database\Migrations\Migration"
Cohesion: 0.12
Nodes (4): {closure#1}(), {closure#2}(), {closure#1}(), {closure#1}()

### Community 152 - "Window"
Cohesion: 0.13
Nodes (3): Lowest, StandardDeviation, Window

### Community 155 - ".compute"
Cohesion: 0.11
Nodes (3): PerpetualTracker, CompiledStrategy, {closure#29}()

### Community 156 - ".evaluate"
Cohesion: 0.29
Nodes (7): WalkForward, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), wfSet()

### Community 157 - "strategies-screen.blade.php"
Cohesion: 0.33
Nodes (5): open, promote, promoteDeployment, run, save

### Community 159 - "extra"
Cohesion: 0.67
Nodes (3): extra, laravel, dont-discover

### Community 174 - "DecimalMath"
Cohesion: 0.15
Nodes (12): DecimalMath, {closure#1}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#2}(), {closure#4}() (+4 more)

### Community 175 - ".evaluate"
Cohesion: 0.24
Nodes (9): Gates, brokenGate(), {closure#1}(), {closure#3}(), {closure#4}(), {closure#6}(), {closure#7}(), gate() (+1 more)

### Community 176 - "OrderManagerTest.php"
Cohesion: 0.26
Nodes (16): btcUsdt(), {closure#10}(), {closure#12}(), {closure#14}(), {closure#15}(), {closure#16}(), {closure#2}(), {closure#4}() (+8 more)

### Community 177 - "2026_11_09_000015_create_scalping_tables.php"
Cohesion: 0.33
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

### Community 181 - "SettingsScreenTest.php"
Cohesion: 0.17
Nodes (13): {closure#10}(), {closure#11}(), {closure#13}(), {closure#14}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}() (+5 more)

### Community 183 - "CompiledStrategy"
Cohesion: 0.29
Nodes (9): {closure#2}(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#7}(), readBackDocument() (+1 more)

### Community 184 - "App\Agents\Mcp\Tools\ListTrials"
Cohesion: 0.29
Nodes (3): {closure#1}(), ListTrials, StrategyTrial

### Community 185 - "RunEngine.php"
Cohesion: 0.15
Nodes (4): {closure#4}(), RunEngine, Notifier, TelegramNotifier

### Community 189 - "MarketType"
Cohesion: 0.07
Nodes (29): {closure#14}(), BinanceRestClient, {closure#3}(), {closure#4}(), EpochTime, Json, InstrumentDefinition, InstrumentStatus (+21 more)

### Community 190 - "PolicyLimits"
Cohesion: 0.07
Nodes (25): InstrumentDirectory, StoredInstruments, {closure#1}(), CompileStrategy, GridPart, PolicyLimits, StrategyRepository, {closure#1}() (+17 more)

### Community 191 - "RiskPolicy"
Cohesion: 0.12
Nodes (13): RiskProfile, Account, Benchmark, RiskPolicy, {closure#1}(), {closure#2}(), {closure#3}(), documentedLimits() (+5 more)

### Community 192 - "0001_01_01_000002_create_jobs_table.php"
Cohesion: 0.33
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

### Community 195 - "2026_11_09_000016_create_market_universe_tables.php"
Cohesion: 0.40
Nodes (3): {closure#1}(), {closure#2}(), up()

### Community 196 - "CandleStreamTest.php"
Cohesion: 0.13
Nodes (15): {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#7}(), {closure#8}(), klineMessage() (+7 more)

### Community 197 - "LiveAccountTest.php"
Cohesion: 0.19
Nodes (14): approveLive(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#8}(), {closure#9}(), fakeLiveBinance() (+6 more)

### Community 198 - "start-local.ps1"
Cohesion: 0.27
Nodes (4): Stop-Postgres(), Stop-ProcessTree(), Test-PostgresRunning(), Write-Step()

### Community 199 - "App\Execution\Journal\DatabaseDecisionLog"
Cohesion: 0.18
Nodes (5): DecisionLog, DecisionRecord, IntentRecord, {closure#1}(), DatabaseDecisionLog

### Community 200 - "Price"
Cohesion: 0.06
Nodes (25): Venue, Balance, ClientOrderId, Income, IncomeKind, Funding, Liquidation, OcoRequest (+17 more)

### Community 201 - "SimulationQueue"
Cohesion: 0.18
Nodes (4): RunBacktest, RunGrid, SimulationTool, SimulationQueue

### Community 202 - "0001_01_01_000000_create_users_table.php"
Cohesion: 0.33
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

### Community 203 - "RuntimeException"
Cohesion: 0.22
Nodes (5): ReplayDivergence, ArchiveMissing, ChecksumMismatch, VerifyMarketData, OrderBookOutOfSync

### Community 204 - "RulesHistory"
Cohesion: 0.28
Nodes (8): RulesHistory, {closure#4}(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), rules(), {closure#10}()

### Community 206 - "StrategyVersion"
Cohesion: 0.29
Nodes (3): {closure#1}(), {closure#3}(), StrategyVersion

### Community 207 - "SimulationJobsTest.php"
Cohesion: 0.21
Nodes (5): {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), savedEmaCross()

### Community 208 - "RiskEventKind"
Cohesion: 0.25
Nodes (6): RiskEvent, RiskEventKind, DailyLossHalt, DrawdownFlatten, DrawdownHalt, WeeklyLossHalt

### Community 209 - "Quantity"
Cohesion: 0.28
Nodes (3): Quantity, {closure#10}(), {closure#9}()

### Community 210 - "bootstrap/app.php"
Cohesion: 0.32
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

### Community 215 - ".number"
Cohesion: 0.33
Nodes (4): {closure#4}(), {closure#45}(), {closure#46}(), {closure#63}()

### Community 216 - "EngineMode"
Cohesion: 0.40
Nodes (5): EngineMode, Backtest, Live, Paper, Testnet

### Community 222 - "OverReportingVenue"
Cohesion: 0.67
Nodes (3): {closure#22}(), {closure#49}(), OverReportingVenue

## Knowledge Gaps
- **150 isolated node(s):** `Backtest`, `Live`, `Paper`, `Testnet`, `Cancelled` (+145 more)
  These have ≤1 connection - possible missing edges. (Counts symbols only; 974 node(s) total have ≤1 connection when file, concept and rationale nodes are included.)
- **62 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `Decimal` connect `Decimal` to `engineHarness`, `CanonicalJson`, `DslError`, `Engine`, `Percent`, `DocumentChecker`, `.parse`, `SimulatedVenueTest.php`, `BinanceVenueTest.php`, `.of`, `.zero`, `Candle`, `App\Simulation\Backtest`, `Ledger`, `CompiledStrategy.php`, `Symbol`, `.times`, `Window`, `.compute`, `.of`, `ForwardingVenue`, `.evaluate`, `EnginePerpTest.php`, `App\Market\Data\FundingRate`, `OrderManagerTest.php`, `BacktestTest.php`, `App\Strategy\Dsl\Document\DocumentChecker`, `App\Market\Data\Candle`, `Timeframe`, `DecimalMath`, `.evaluate`, `OrderManagerTest.php`, `.isPositive`, `FundingCarry`, `Symbol`, `CompiledStrategy`, `IndicatorsTest.php`, `RunBacktestCommandTest.php`, `IndicatorReferenceTest.php`, `PolicyLimits`, `FundingRate`, `RiskPolicy`, `.run`, `CompiledStrategyTest.php`, `Kind`, `Price`, `.measure`, `RulesHistory`, `RiskEventKind`, `.zero`, `MonteCarlo`, `App\Support\Number\Decimal`, `Quantity`, `ExitReason`, `WarmingVenue`, `.fromStrings`, `StrategyGates.php`, `.handle`, `Strategy`, `.of`, `PreparesBacktests.php`?**
  _High betweenness centrality (0.129) - this node is a cross-community bridge._
- **What connects `Backtest`, `Live`, `Paper` to the rest of the system?**
  _150 weakly-connected nodes found - possible missing edges._
- **Should `engineHarness` be split into smaller, more focused modules?**
  _Cohesion score 0.09994711792702274 - nodes in this community are weakly interconnected._
- **Why does `Symbol` connect `Symbol` to `engineHarness`, `CanonicalJson`, `DslError`, `Engine`, `.parse`, `DocumentChecker`, `SimulatedVenueTest.php`, `BinanceVenueTest.php`, `Candle`, `App\Simulation\Backtest`, `CandleRepositoryTest.php`, `App\Support\Symbol`, `Instrument`, `Ledger`, `CompiledStrategy.php`, `Symbol`, `.times`, `.compute`, `.of`, `Carbon\CarbonImmutable`, `ForwardingVenue`, `EnginePerpTest.php`, `OrderManagerTest.php`, `BacktestTest.php`, `Decimal`, `App\Market\Data\Candle`, `Timeframe`, `OrderManagerTest.php`, `FundingCarry`, `RunBacktestCommandTest.php`, `MarketType`, `PolicyLimits`, `FundingRate`, `.run`, `CompiledStrategyTest.php`, `App\Execution\Journal\DatabaseDecisionLog`, `Price`, `RulesHistory`, `.measure`, `ExitReason`, `WarmingVenue`, `StrategyGates.php`, `.handle`, `Laravel\Mcp\Response`, `Strategy`, `PreparesBacktests.php`, `.register`?**
  _High betweenness centrality (0.026) - this node is a cross-community bridge._
- **Should `DslError` be split into smaller, more focused modules?**
  _Cohesion score 0.09966777408637874 - nodes in this community are weakly interconnected._
- **Why does `Clock` connect `Clock` to `TelegramSettings`, `App\Simulation\StrategyLibrary`, `BinanceVenueTest.php`, `CandleRepositoryTest.php`, `App\Support\Clock\Clock`, `Instrument`, `Carbon\CarbonImmutable`, `ScalpingCollectorTest.php`, `App`, `.handle`, `RunBacktestCommandTest.php`, `Psr\Log\LoggerInterface`, `PolicyLimits`, `AppServiceProvider.php`, `FundingRate`, `App\Execution\Journal\DatabaseDecisionLog`, `Illuminate\Console\Command`, `SimulationQueue`, `RuntimeException`, `RunSimulation.php`, `StrategyGates.php`, `.handle`, `InvalidArgumentException`, `.register`?**
  _High betweenness centrality (0.023) - this node is a cross-community bridge._
- **Should `Engine` be split into smaller, more focused modules?**
  _Cohesion score 0.0645933014354067 - nodes in this community are weakly interconnected._