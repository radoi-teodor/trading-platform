# Graph Report - trading-platform  (2026-11-09)

## Corpus Check
- 518 files · ~213,891 words
- Verdict: corpus is large enough that graph structure adds value.
- Unclassified: 92 file(s) not represented in the graph (top: .zip 34, .CHECKSUM 34, (none) 18)

## Summary
- 4143 nodes · 14247 edges · 168 communities (125 shown, 43 thin omitted)
- Extraction: 93% EXTRACTED · 7% INFERRED · 0% AMBIGUOUS · INFERRED: 1031 edges (avg confidence: 0.85)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `85f54179`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- .of
- EngineTest.php
- Clock
- InvalidArgumentException
- InstrumentRules
- .parse
- DocumentChecker
- Position
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
- App\Strategy\Dsl\Position
- MarketType
- LedgerTest.php
- Illuminate\Database\Schema\Blueprint
- generate.py
- ReadBack
- Symbol
- AppServiceProvider.php
- FundingCarry
- Price
- .measure
- Strategy
- CandleStreamTest.php
- StrategyIdentity
- .zero
- .of
- Ledger
- ExitReason
- PaperEngineTest.php
- BackfillTest.php
- RiskPolicy
- BacktestTest.php
- BinanceVenue
- ema-cross-scalp-long.strategy.yaml
- RoundTrip
- Money
- OrderRole
- User
- Illuminate\Database\Eloquent\Model
- package.json
- Venue
- BinanceRestClientTest.php
- ema-cross-scalp-short.strategy.yaml
- EnginePerpTest.php
- SimClock
- Laravel\Mcp\Response
- postgres
- InstrumentRules
- Engine
- donchian-breakout-short.strategy.yaml
- IndicatorReferenceTest.php
- RunEngine.php
- RunBacktestCommandTest.php
- Asset
- BinanceArchive
- .isFull
- DecisionRecord
- donchian-breakout.strategy.yaml
- .run
- CandleRepositoryTest.php
- App\Strategy\Dsl\Document\DocumentChecker
- .of
- Engine
- ScreensTest.php
- ArchiveImportTest.php
- scripts
- ma-trend-short.strategy.yaml
- .parse
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
- .evaluate
- .fromStrings
- config
- uses
- test
- with
- PreparesBacktests
- run
- GridBacktest.php
- psr-4
- require
- DatabaseSeeder
- steps
- steps
- lint
- CandleFeedTest.php
- ClientOrderId
- StrategyVersion
- StrategiesScreen.php
- RiskEventKind
- buy-and-hold.strategy.yaml
- ChartSeriesTest.php
- TokensTest.php
- StrategyGates.php
- AppServiceProvider.php
- Illuminate\Support\Facades\Schema
- Illuminate\Database\Migrations\Migration
- .count
- funding-carry.strategy.yaml
- McpServerTest.php
- Illuminate\Http\Client\Request
- .times
- .__construct
- PreparesBacktests.php
- ReadBackTest.php
- 0001_01_01_000000_create_users_table.php
- DslReference
- .encode
- Carbon\CarbonImmutable
- {closure#40}
- BinanceVenue.php
- Percent
- Quantity
- .isPositive
- 0001_01_01_000002_create_jobs_table.php
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

## God Nodes (most connected - your core abstractions)
1. `Decimal` - 598 edges
2. `Symbol` - 253 edges
3. `Side` - 215 edges
4. `Candle` - 170 edges
5. `Timeframe` - 155 edges
6. `Clock` - 122 edges
7. `Price` - 116 edges
8. `Position` - 96 edges
9. `MarketType` - 86 edges
10. `SimulatedVenue` - 73 edges

## Surprising Connections (you probably didn't know these)
- `{closure#7}()` --calls--> `EngineRecord`  [EXTRACTED]
  tests/Feature/Notifications/TelegramTest.php → app/Execution/Data/EngineRecord.php
- `{closure#2}()` --references--> `EngineRecord`  [EXTRACTED]
  tests/Unit/Execution/EnginePerpTest.php → app/Execution/Data/EngineRecord.php
- `{closure#1}()` --references--> `EngineRecord`  [EXTRACTED]
  tests/Unit/Execution/EngineTest.php → app/Execution/Data/EngineRecord.php
- `{closure#4}()` --references--> `Candle`  [EXTRACTED]
  tests/Feature/Market/BinanceRestClientTest.php → app/Market/Data/Candle.php
- `{closure#7}()` --references--> `Candle`  [EXTRACTED]
  tests/Feature/Market/CandleRepositoryTest.php → app/Market/Data/Candle.php

## Import Cycles
- None detected.

## Communities (168 total, 43 thin omitted)

### Community 0 - ".of"
Cohesion: 0.07
Nodes (75): RejectionReason, BelowMinimum, Breaker, Cooldown, DuplicateIntent, EntryWorking, GrossExposure, Halted (+67 more)

### Community 1 - "EngineTest.php"
Cohesion: 0.10
Nodes (41): EngineCommand, EngineMode, Backtest, Live, Paper, Testnet, ScriptedStrategy, {closure#1}() (+33 more)

### Community 2 - "Clock"
Cohesion: 0.06
Nodes (23): CreateMcpToken, RevokeMcpToken, AuthenticateMcpToken, McpToken, CreateAccount, FlattenEngine, HaltEngine, IssuesCommands (+15 more)

### Community 3 - "InvalidArgumentException"
Cohesion: 0.07
Nodes (16): ArchiveMissing, ChecksumMismatch, BinanceRequestFailed, BinanceUnreachable, LocationRestricted, RateLimited, CheckMarketData, SelectsInstruments (+8 more)

### Community 4 - "InstrumentRules"
Cohesion: 0.10
Nodes (18): Liquidity, Maker, Taker, OrderStatus, Cancelled, Expired, Filled, New (+10 more)

### Community 5 - ".parse"
Cohesion: 0.10
Nodes (37): {closure#2}(), {closure#1}(), baselinePairs(), {closure#17}(), {closure#5}(), carryDecisions(), {closure#10}(), {closure#11}() (+29 more)

### Community 7 - "Position"
Cohesion: 0.06
Nodes (24): Argument, BoolLiteral, Call, DurationLiteral, Identifier, Lookback, Node, OnTimeframe (+16 more)

### Community 8 - "CompiledStrategy.php"
Cohesion: 0.15
Nodes (3): {closure#33}(), Compiler, Program

### Community 9 - "SimulatedVenueTest.php"
Cohesion: 0.17
Nodes (42): btc(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#15}(), {closure#16}() (+34 more)

### Community 10 - "Instrument"
Cohesion: 0.06
Nodes (17): CandleRepository, {closure#2}(), {closure#4}(), CandleSource, Archive, Rest, Stream, DatabaseCandleFeed (+9 more)

### Community 11 - "SimulatedPerpVenueTest.php"
Cohesion: 0.21
Nodes (24): {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#15}(), {closure#18}(), {closure#19}() (+16 more)

### Community 12 - "Candle"
Cohesion: 0.10
Nodes (34): {closure#1}(), {closure#2}(), {closure#1}(), {closure#3}(), Candle, KlineMessage, {closure#9}(), {closure#1}() (+26 more)

### Community 13 - "ExchangeVerificationTest.php"
Cohesion: 0.11
Nodes (29): {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#2}(), {closure#3}(), {closure#4}() (+21 more)

### Community 14 - "IndicatorsTest.php"
Cohesion: 0.10
Nodes (18): Donchian, Ema, Sma, bar(), {closure#1}(), {closure#10}(), {closure#12}(), {closure#13}() (+10 more)

### Community 16 - "Node"
Cohesion: 0.11
Nodes (21): Binary, Lexer, Parser, Token, TokenKind, Colon, Comma, Dot (+13 more)

### Community 17 - "PolicyLimits"
Cohesion: 0.10
Nodes (20): InstrumentDirectory, PolicyLimits, StrategyRepository, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}() (+12 more)

### Community 18 - "App\Strategy\Dsl\Position"
Cohesion: 0.09
Nodes (16): {closure#1}(), NumberLiteral, RMultipleLiteral, {closure#1}(), {closure#5}(), {closure#8}(), DslError, Position (+8 more)

### Community 19 - "MarketType"
Cohesion: 0.05
Nodes (26): {closure#1}(), {closure#2}(), BinanceRestClient, EpochTime, ExchangeNumber, Json, RequestFailed, SyncInstruments (+18 more)

### Community 20 - "LedgerTest.php"
Cohesion: 0.21
Nodes (29): Side, Buy, Sell, balanceOf(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}() (+21 more)

### Community 21 - "Illuminate\Database\Schema\Blueprint"
Cohesion: 0.19
Nodes (10): {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#1}(), {closure#2}(), {closure#3}() (+2 more)

### Community 22 - "generate.py"
Cohesion: 0.08
Nodes (30): atr(), bars(), bb(), change(), dmi(), donchian(), ema(), highest() (+22 more)

### Community 23 - "ReadBack"
Cohesion: 0.12
Nodes (8): {closure#6}(), {closure#7}(), EntrySpec, Expression, {closure#1}(), {closure#2}(), {closure#5}(), ReadBack

### Community 24 - "Symbol"
Cohesion: 0.04
Nodes (27): VenuePosition, {closure#2}(), {closure#3}(), {closure#4}(), {closure#1}(), SimulatedPosition, {closure#1}(), BuyAndHold (+19 more)

### Community 25 - "AppServiceProvider.php"
Cohesion: 0.12
Nodes (5): Notifier, BinanceEndpoints, TelegramNotifier, {closure#4}(), {closure#5}()

### Community 26 - "FundingCarry"
Cohesion: 0.11
Nodes (4): DonchianBreakout, FundingCarry, MovingAverageTrend, Parameters

### Community 27 - "Price"
Cohesion: 0.07
Nodes (10): {closure#1}(), {closure#11}(), {closure#12}(), {closure#2}(), {closure#3}(), FundingRate, PositionView, Price (+2 more)

### Community 28 - ".measure"
Cohesion: 0.20
Nodes (8): EquityTracker, {closure#1}(), {closure#2}(), {closure#3}(), {closure#5}(), {closure#6}(), {closure#8}(), trackedTwoDays()

### Community 29 - "Strategy"
Cohesion: 0.16
Nodes (18): {closure#3}(), Strategy, baselineDocument(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#14}() (+10 more)

### Community 30 - "CandleStreamTest.php"
Cohesion: 0.06
Nodes (19): Sleeper, SystemSleeper, CandleStream, PhrityTransport, StreamFailed, StreamTransport, {closure#1}(), {closure#2}() (+11 more)

### Community 31 - "StrategyIdentity"
Cohesion: 0.20
Nodes (5): StrategyIdentity, RenamedStrategy, {closure#1}(), {closure#2}(), {closure#3}()

### Community 32 - ".zero"
Cohesion: 0.09
Nodes (21): Cscv, DeflatedSharpe, DirectionalMovement, Wilder, closeTo(), {closure#1}(), {closure#10}(), {closure#11}() (+13 more)

### Community 33 - ".of"
Cohesion: 0.13
Nodes (22): {closure#1}(), {closure#2}(), {closure#8}(), {closure#41}(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#13}() (+14 more)

### Community 34 - "Ledger"
Cohesion: 0.18
Nodes (12): InMemoryDecisionLog, Ledger, LedgerViolation, {closure#1}(), {closure#2}(), {closure#2}(), Participant, ParticipantResult (+4 more)

### Community 35 - "ExitReason"
Cohesion: 0.17
Nodes (24): ExitReason, Flatten, Liquidation, Signal, Stop, Target, {closure#1}(), {closure#10}() (+16 more)

### Community 36 - "PaperEngineTest.php"
Cohesion: 0.12
Nodes (19): JournalEntry, SendTelegram, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}() (+11 more)

### Community 37 - "BackfillTest.php"
Cohesion: 0.15
Nodes (13): {closure#11}(), {closure#14}(), {closure#15}(), {closure#16}(), {closure#4}(), {closure#6}(), {closure#7}(), {closure#8}() (+5 more)

### Community 38 - "RiskPolicy"
Cohesion: 0.08
Nodes (18): StoredInstruments, {closure#1}(), RiskProfile, Account, Benchmark, {closure#1}(), RiskPolicy, CompileStrategy (+10 more)

### Community 39 - "BacktestTest.php"
Cohesion: 0.11
Nodes (20): FeeSchedule, CostModel, Backtest, {closure#1}(), RunDefinition, ReportBuilder, InMemoryCandleFeed, {closure#44}() (+12 more)

### Community 40 - "BinanceVenue"
Cohesion: 0.21
Nodes (4): {closure#2}(), BinanceVenue, {closure#1}(), {closure#1}()

### Community 41 - "ema-cross-scalp-long.strategy.yaml"
Cohesion: 0.06
Nodes (33): entries, long, exits, stop_loss, take_profit, time_stop, when, fails_when (+25 more)

### Community 42 - "RoundTrip"
Cohesion: 0.13
Nodes (15): RoundTrip, {closure#1}(), {closure#2}(), {closure#4}(), {closure#6}(), {closure#7}(), {closure#10}(), {closure#2}() (+7 more)

### Community 43 - "Money"
Cohesion: 0.16
Nodes (3): Fill, Position, Money

### Community 44 - "OrderRole"
Cohesion: 0.09
Nodes (33): OrderRole, Entry, Exit, Flatten, Stop, Target, ProtectionPlan, TrackedOrder (+25 more)

### Community 46 - "Illuminate\Database\Eloquent\Model"
Cohesion: 0.07
Nodes (13): {closure#1}(), McpCall, {closure#5}(), AccountSnapshot, Deployment, EngineCommandRow, ExchangeKey, {closure#1}() (+5 more)

### Community 47 - "package.json"
Cohesion: 0.07
Nodes (37): dependencies, codemirror@^6.0.2, @codemirror/lang-yaml@^6.1.3, @codemirror/language@^6.13.1, @lezer/highlight@^1.2.5, lightweight-charts@^5.2.1, devDependencies, concurrently@^10.0.3 (+29 more)

### Community 48 - "Venue"
Cohesion: 0.09
Nodes (9): Venue, SimulatedVenue, ForwardingVenue, {closure#12}(), {closure#22}(), {closure#32}(), {closure#34}(), {closure#49}() (+1 more)

### Community 49 - "BinanceRestClientTest.php"
Cohesion: 0.19
Nodes (12): binanceClient(), {closure#1}(), {closure#11}(), {closure#13}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}() (+4 more)

### Community 50 - "ema-cross-scalp-short.strategy.yaml"
Cohesion: 0.06
Nodes (33): entries, short, exits, stop_loss, take_profit, time_stop, when, fails_when (+25 more)

### Community 51 - "EnginePerpTest.php"
Cohesion: 0.26
Nodes (15): {closure#10}(), {closure#11}(), {closure#13}(), {closure#14}(), {closure#2}(), {closure#5}(), {closure#6}(), {closure#7}() (+7 more)

### Community 52 - "SimClock"
Cohesion: 0.12
Nodes (12): SimClock, WallClock, {closure#1}(), {closure#1}(), {closure#2}(), definition(), {closure#1}(), {closure#2}() (+4 more)

### Community 53 - "Laravel\Mcp\Response"
Cohesion: 0.07
Nodes (16): ChartTool, CompileStrategy, GetCandles, GetFunding, GetIndicators, GetJob, GetStrategy, ListInstruments (+8 more)

### Community 54 - "postgres"
Cohesion: 0.10
Nodes (19): POSTGRES_DB, POSTGRES_PASSWORD, POSTGRES_USER, interval, retries, test, timeout, environment (+11 more)

### Community 55 - "InstrumentRules"
Cohesion: 0.10
Nodes (13): {closure#1}(), {closure#1}(), RulesHistory, {closure#2}(), {closure#3}(), {closure#5}(), {closure#4}(), {closure#1}() (+5 more)

### Community 57 - "donchian-breakout-short.strategy.yaml"
Cohesion: 0.07
Nodes (26): entries, short, default, range, step, default, range, step (+18 more)

### Community 58 - "IndicatorReferenceTest.php"
Cohesion: 0.04
Nodes (37): {closure#34}(), {closure#36}(), Atr, BarIndicator, Bollinger, Change, DonchianChannel, Highest (+29 more)

### Community 59 - "RunEngine.php"
Cohesion: 0.09
Nodes (8): AccountRuntime, RunEngine, ReplayDivergence, Account, BinanceApiError, SignedBinanceClient, CandleFeed, {closure#2}()

### Community 60 - "RunBacktestCommandTest.php"
Cohesion: 0.09
Nodes (28): BacktestRun, {closure#4}(), {closure#6}(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}() (+20 more)

### Community 61 - "Asset"
Cohesion: 0.13
Nodes (7): {closure#2}(), Balance, Marks, Asset, balances(), {closure#25}(), {closure#9}()

### Community 62 - "BinanceArchive"
Cohesion: 0.09
Nodes (7): ArchivePeriod, BinanceArchive, Backfill, BackfillReport, BackfillStep, BackfillMarketData, UpsertResult

### Community 64 - "DecisionRecord"
Cohesion: 0.15
Nodes (5): DecisionLog, DecisionRecord, IntentRecord, {closure#1}(), DatabaseDecisionLog

### Community 65 - "donchian-breakout.strategy.yaml"
Cohesion: 0.07
Nodes (26): entries, long, default, range, step, default, range, step (+18 more)

### Community 66 - ".run"
Cohesion: 0.08
Nodes (29): PerformanceMetrics, TradeStatistics, Report, btcMinutes(), {closure#1}(), {closure#2}(), {closure#3}(), referencePrice() (+21 more)

### Community 67 - "CandleRepositoryTest.php"
Cohesion: 0.28
Nodes (11): candleStore(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}() (+3 more)

### Community 68 - "App\Strategy\Dsl\Document\DocumentChecker"
Cohesion: 0.04
Nodes (58): {closure#2}(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#5}(), {closure#3}(), {closure#4}(), {closure#9}() (+50 more)

### Community 69 - ".of"
Cohesion: 0.15
Nodes (18): AssetMismatch, {closure#2}(), {closure#1}(), usdt(), {closure#11}(), {closure#7}(), {closure#1}(), {closure#11}() (+10 more)

### Community 71 - "ScreensTest.php"
Cohesion: 0.12
Nodes (15): {closure#1}(), {closure#2}(), {closure#3}(), fakeTestnet(), testnetAccount(), {closure#1}(), {closure#1}(), {closure#2}() (+7 more)

### Community 72 - "ArchiveImportTest.php"
Cohesion: 0.29
Nodes (10): archiveBytes(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}() (+2 more)

### Community 73 - "scripts"
Cohesion: 0.15
Nodes (13): scripts, analyse, dev, format, mutate, post-autoload-dump, post-update-cmd, pre-package-uninstall (+5 more)

### Community 74 - "ma-trend-short.strategy.yaml"
Cohesion: 0.07
Nodes (26): entries, short, exits, stop_loss, when, fails_when, family, default (+18 more)

### Community 75 - ".parse"
Cohesion: 0.39
Nodes (6): AstPrinter, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), sexpr()

### Community 76 - ".walk"
Cohesion: 0.14
Nodes (20): CandleAggregator, CandleSequenceBroken, {closure#1}(), {closure#3}(), {closure#6}(), CandleFactory, aggregate(), {closure#1}() (+12 more)

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
Cohesion: 0.15
Nodes (14): DecimalMath, {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#2}() (+6 more)

### Community 82 - "CompiledStrategyTest.php"
Cohesion: 0.19
Nodes (15): aggregatedBars(), {closure#1}(), {closure#11}(), {closure#13}(), {closure#16}(), {closure#17}(), {closure#2}(), {closure#3}() (+7 more)

### Community 83 - "App\Support\Number\Decimal"
Cohesion: 0.05
Nodes (58): {closure#6}(), {closure#1}(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#10}(), {closure#5}(), {closure#4}() (+50 more)

### Community 84 - "pint/composer.json"
Cohesion: 0.20
Nodes (9): config, sort-packages, description, license, name, require, laravel/pint, php (+1 more)

### Community 85 - "MonteCarlo"
Cohesion: 0.15
Nodes (11): {closure#4}(), {closure#5}(), MonteCarlo, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}() (+3 more)

### Community 86 - "RunSimulation.php"
Cohesion: 0.08
Nodes (8): {closure#3}(), RunSimulation, SimulationJob, SimulationQueue, {closure#1}(), {closure#2}(), {closure#3}(), savedEmaCross()

### Community 87 - "Timeframe"
Cohesion: 0.08
Nodes (17): {closure#2}(), {closure#4}(), CarryTemplate, {closure#2}(), Timeframe, FifteenMinutes, FiveMinutes, FourHours (+9 more)

### Community 88 - "require-dev"
Cohesion: 0.22
Nodes (9): require-dev, fakerphp/faker, larastan/larastan, laravel/pail, laravel/pao, mockery/mockery, nunomaduro/collision, pestphp/pest (+1 more)

### Community 89 - "scripts-descriptions"
Cohesion: 0.25
Nodes (8): scripts-descriptions, analyse, format, mutate, test, test:network, test:testnet, tools:install

### Community 90 - ".evaluate"
Cohesion: 0.22
Nodes (9): Gates, brokenGate(), {closure#1}(), {closure#3}(), {closure#4}(), {closure#6}(), {closure#7}(), gate() (+1 more)

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

### Community 96 - "PreparesBacktests"
Cohesion: 0.05
Nodes (9): FundingRateRepository, QualityCheck, QualityReport, GridBacktest, PreparesBacktests, ReadsGridOptions, StrategyGates, ExitSpec (+1 more)

### Community 97 - "run"
Cohesion: 0.33
Nodes (6): name, runs-on, steps, audit, run, steps

### Community 98 - "GridBacktest.php"
Cohesion: 0.15
Nodes (9): {closure#11}(), {closure#12}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#6}(), {closure#5}(), {closure#6}() (+1 more)

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

### Community 106 - "CandleFeedTest.php"
Cohesion: 0.44
Nodes (11): {closure#1}(), {closure#12}(), {closure#13}(), {closure#2}(), {closure#4}(), {closure#5}(), {closure#8}(), {closure#9}() (+3 more)

### Community 108 - "ClientOrderId"
Cohesion: 0.07
Nodes (16): ClientOrderId, OcoRequest, OrderAck, OrderRequest, OrderType, Limit, Market, StopMarket (+8 more)

### Community 117 - "StrategyVersion"
Cohesion: 0.24
Nodes (5): {closure#1}(), {closure#2}(), CheckResult, SaveResult, StrategyVersion

### Community 118 - "StrategiesScreen.php"
Cohesion: 0.07
Nodes (12): ReauthController, AccountScreen, ChartScreen, {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), HaltButton (+4 more)

### Community 119 - "RiskEventKind"
Cohesion: 0.24
Nodes (6): RiskEvent, RiskEventKind, DailyLossHalt, DrawdownFlatten, DrawdownHalt, WeeklyLossHalt

### Community 120 - "buy-and-hold.strategy.yaml"
Cohesion: 0.12
Nodes (16): entries, long, exits, stop_loss, fails_when, family, hypothesis, when (+8 more)

### Community 121 - "ChartSeriesTest.php"
Cohesion: 0.23
Nodes (11): chart(), {closure#10}(), {closure#11}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}() (+3 more)

### Community 122 - "TokensTest.php"
Cohesion: 0.31
Nodes (6): {closure#3}(), {closure#4}(), contrastRatio(), contrastRules(), relativeLuminance(), themeTokens()

### Community 123 - "StrategyGates.php"
Cohesion: 0.11
Nodes (14): {closure#8}(), {closure#1}(), {closure#2}(), {closure#4}(), {closure#5}(), {closure#7}(), WalkForward, WalkForwardSet (+6 more)

### Community 124 - "AppServiceProvider.php"
Cohesion: 0.08
Nodes (8): AppServiceProvider, {closure#6}(), {closure#3}(), {closure#4}(), FortifyServiceProvider, {closure#1}(), {closure#2}(), {closure#3}()

### Community 127 - ".count"
Cohesion: 0.10
Nodes (17): Performance, Statistics, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#1}(), {closure#2}() (+9 more)

### Community 128 - "funding-carry.strategy.yaml"
Cohesion: 0.12
Nodes (15): fails_when, family, hypothesis, params, enter_when, exit_when, margin_buffer, max_leverage_perp (+7 more)

### Community 129 - "McpServerTest.php"
Cohesion: 0.20
Nodes (12): TradingServer, actAsToken(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}() (+4 more)

### Community 130 - "Illuminate\Http\Client\Request"
Cohesion: 0.20
Nodes (11): {closure#3}(), {closure#4}(), {closure#6}(), queryOf(), {closure#13}(), {closure#5}(), {closure#12}(), {closure#2}() (+3 more)

### Community 131 - ".times"
Cohesion: 0.07
Nodes (10): {closure#2}(), PerpPosition, AccountView, Verdict, RiskEngine, {closure#3}(), VolumeProfile, InvalidNumber (+2 more)

### Community 133 - "PreparesBacktests.php"
Cohesion: 0.22
Nodes (4): {closure#2}(), {closure#5}(), {closure#7}(), {closure#8}()

### Community 135 - "ReadBackTest.php"
Cohesion: 0.38
Nodes (7): {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), readBackDocument(), readBackSpec()

### Community 137 - "0001_01_01_000000_create_users_table.php"
Cohesion: 0.33
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

### Community 139 - ".encode"
Cohesion: 0.30
Nodes (6): CanonicalJson, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}()

### Community 140 - "Carbon\CarbonImmutable"
Cohesion: 0.11
Nodes (13): {closure#4}(), {closure#1}(), MinuteCandles, BreakerEvent, MarketBreakers, BacktestResult, {closure#14}(), {closure#3}() (+5 more)

### Community 143 - "BinanceVenue.php"
Cohesion: 0.18
Nodes (9): Income, IncomeKind, Funding, Liquidation, OrderUpdate, {closure#1}(), UpdateStream, {closure#3}() (+1 more)

### Community 145 - "Percent"
Cohesion: 0.24
Nodes (3): Percent, {closure#14}(), {closure#15}()

### Community 146 - "Quantity"
Cohesion: 0.24
Nodes (3): Quantity, {closure#10}(), {closure#9}()

### Community 147 - ".isPositive"
Cohesion: 0.10
Nodes (12): {closure#1}(), {closure#2}(), {closure#4}(), {closure#5}(), {closure#6}(), Normal, {closure#1}(), {closure#3}() (+4 more)

### Community 148 - "0001_01_01_000002_create_jobs_table.php"
Cohesion: 0.33
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

### Community 157 - "strategies-screen.blade.php"
Cohesion: 0.40
Nodes (4): open, promote, run, save

### Community 159 - "extra"
Cohesion: 0.67
Nodes (3): extra, laravel, dont-discover

## Knowledge Gaps
- **133 isolated node(s):** `Halt`, `Resume`, `Flatten`, `Pause`, `ResumeStrategy` (+128 more)
  These have ≤1 connection - possible missing edges. (Counts symbols only; 842 node(s) total have ≤1 connection when file, concept and rationale nodes are included.)
- **43 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `Decimal` connect `App\Support\Number\Decimal` to `.of`, `EngineTest.php`, `Clock`, `.times`, `InstrumentRules`, `.__construct`, `PreparesBacktests.php`, `Position`, `CompiledStrategy.php`, `SimulatedVenueTest.php`, `Instrument`, `DocumentChecker`, `Candle`, `Carbon\CarbonImmutable`, `IndicatorsTest.php`, `BinanceVenue.php`, `SimulatedPerpVenueTest.php`, `PolicyLimits`, `App\Strategy\Dsl\Position`, `.isPositive`, `MarketType`, `Percent`, `Quantity`, `ReadBack`, `Symbol`, `LedgerTest.php`, `FundingCarry`, `Price`, `.measure`, `Strategy`, `.parse`, `.zero`, `.of`, `Ledger`, `ExitReason`, `RiskPolicy`, `BacktestTest.php`, `BinanceVenue`, `ReadBackTest.php`, `RoundTrip`, `Money`, `OrderRole`, `Venue`, `EnginePerpTest.php`, `InstrumentRules`, `Engine`, `IndicatorReferenceTest.php`, `RunBacktestCommandTest.php`, `Asset`, `.encode`, `.isFull`, `.run`, `App\Strategy\Dsl\Document\DocumentChecker`, `.of`, `Engine`, `.walk`, `DecimalMath`, `CompiledStrategyTest.php`, `MonteCarlo`, `Timeframe`, `.evaluate`, `.fromStrings`, `PreparesBacktests`, `GridBacktest.php`, `CandleFeedTest.php`, `ClientOrderId`, `StrategiesScreen.php`, `RiskEventKind`, `ChartSeriesTest.php`, `StrategyGates.php`, `.count`?**
  _High betweenness centrality (0.216) - this node is a cross-community bridge._
- **What connects `Halt`, `Resume`, `Flatten` to the rest of the system?**
  _133 weakly-connected nodes found - possible missing edges._
- **Should `.of` be split into smaller, more focused modules?**
  _Cohesion score 0.07006101409636019 - nodes in this community are weakly interconnected._
- **Why does `Symbol` connect `Symbol` to `.of`, `McpServerTest.php`, `Clock`, `.times`, `InstrumentRules`, `InvalidArgumentException`, `DocumentChecker`, `PreparesBacktests.php`, `CompiledStrategy.php`, `SimulatedVenueTest.php`, `Instrument`, `SimulatedPerpVenueTest.php`, `.parse`, `Position`, `ExchangeVerificationTest.php`, `BinanceVenue.php`, `.encode`, `PolicyLimits`, `App\Strategy\Dsl\Position`, `MarketType`, `LedgerTest.php`, `FundingCarry`, `Price`, `.measure`, `Strategy`, `CandleStreamTest.php`, `EngineTest.php`, `Ledger`, `ExitReason`, `BackfillTest.php`, `BacktestTest.php`, `BinanceVenue`, `RoundTrip`, `Money`, `OrderRole`, `Venue`, `EnginePerpTest.php`, `Laravel\Mcp\Response`, `InstrumentRules`, `Engine`, `RunEngine.php`, `RunBacktestCommandTest.php`, `Asset`, `DecisionRecord`, `.run`, `CandleRepositoryTest.php`, `App\Strategy\Dsl\Document\DocumentChecker`, `.walk`, `CompiledStrategyTest.php`, `App\Support\Number\Decimal`, `Timeframe`, `PreparesBacktests`, `CandleFeedTest.php`, `ClientOrderId`, `StrategiesScreen.php`, `ChartSeriesTest.php`, `StrategyGates.php`?**
  _High betweenness centrality (0.057) - this node is a cross-community bridge._
- **Should `EngineTest.php` be split into smaller, more focused modules?**
  _Cohesion score 0.09573412698412699 - nodes in this community are weakly interconnected._
- **Why does `Timeframe` connect `Timeframe` to `McpServerTest.php`, `EngineTest.php`, `InvalidArgumentException`, `.__construct`, `.parse`, `DocumentChecker`, `Position`, `CompiledStrategy.php`, `SimulatedVenueTest.php`, `Instrument`, `SimulatedPerpVenueTest.php`, `Candle`, `Carbon\CarbonImmutable`, `IndicatorsTest.php`, `PolicyLimits`, `App\Strategy\Dsl\Position`, `MarketType`, `ReadBack`, `Symbol`, `FundingCarry`, `CandleStreamTest.php`, `OrderRole`, `EnginePerpTest.php`, `Laravel\Mcp\Response`, `IndicatorReferenceTest.php`, `CandleRepositoryTest.php`, `App\Strategy\Dsl\Document\DocumentChecker`, `.walk`, `CompiledStrategyTest.php`, `App\Support\Number\Decimal`, `.fromStrings`, `PreparesBacktests`, `CandleFeedTest.php`, `StrategiesScreen.php`, `ChartSeriesTest.php`, `StrategyGates.php`, `.count`?**
  _High betweenness centrality (0.052) - this node is a cross-community bridge._
- **Should `Clock` be split into smaller, more focused modules?**
  _Cohesion score 0.059076682316118935 - nodes in this community are weakly interconnected._