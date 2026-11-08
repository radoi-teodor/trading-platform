# Graph Report - trading-platform  (2026-11-08)

## Corpus Check
- 402 files · ~160,519 words
- Verdict: corpus is large enough that graph structure adds value.
- Unclassified: 92 file(s) not represented in the graph (top: .zip 34, .CHECKSUM 34, (none) 18)

## Summary
- 3259 nodes · 11801 edges · 129 communities (104 shown, 25 thin omitted)
- Extraction: 89% EXTRACTED · 11% INFERRED · 0% AMBIGUOUS · INFERRED: 1259 edges (avg confidence: 0.85)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `f4d25082`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- .of
- EngineTest.php
- App\Support\Number\Decimal
- Instrument
- .zero
- .parse
- MarketType
- Position
- CompiledStrategy.php
- SimulatedVenueTest.php
- App\Support\Symbol
- SimulatedPerpVenueTest.php
- Candle
- ExchangeVerificationTest.php
- IndicatorsTest.php
- AppServiceProvider.php
- Node
- .of
- App\Strategy\Dsl\Position
- CandleStreamTest.php
- Ledger
- Illuminate\Database\Schema\Blueprint
- generate.py
- SimulatedVenue
- Symbol
- Unit
- OrderManagerTest.php
- RejectionReason
- OrderRole
- Decimal
- EnginePerpTest.php
- .times
- ValidationStatisticsTest.php
- DecimalMathTest.php
- Engine
- .expect
- Timeframe
- SimClock
- .plus
- BacktestTest.php
- Price
- ema-cross-scalp-long.strategy.yaml
- RiskPolicy
- Kind
- sources
- User
- GridBacktest.php
- package.json
- PreparesBacktests.php
- BinanceRestClientTest.php
- ema-cross-scalp-short.strategy.yaml
- .handle
- TokensTest.php
- ExitReason
- postgres
- InstrumentRules
- PreparesBacktests
- PolicyLimits
- .measure
- GlobalsTest.php
- RunBacktestCommandTest.php
- OrderManager
- BinanceArchive
- CandleFeedTest.php
- .run
- ArchiveImportTest.php
- IndicatorReferenceTest.php
- SimClock
- CandleRepositoryTest.php
- Ledger
- YamlMap
- Illuminate\Database\ConnectionInterface
- SimulationJobsTest.php
- scripts
- BinanceArchive.php
- .__construct
- .walk
- composer.json
- jobs
- postgres
- .build
- CompiledStrategyTest.php
- App\Support\Number\Decimal
- pint/composer.json
- .count
- RunSimulation.php
- bootstrap/app.php
- require-dev
- scripts-descriptions
- PerpetualBaselinesTest.php
- .evaluate
- config
- uses
- test
- with
- OrderUpdate
- run
- psr-4
- require
- DatabaseSeeder
- steps
- steps
- lint
- extra
- Carbon\CarbonImmutable
- .handle
- CompiledStrategyTest.php
- App\Simulation\Validation\WalkForward
- Group
- ForwardingVenue
- VerificationReport
- Illuminate\Console\Command
- .__construct
- ParserTest.php

## God Nodes (most connected - your core abstractions)
1. `Decimal` - 295 edges
2. `Side` - 182 edges
3. `Symbol` - 169 edges
4. `Candle` - 103 edges
5. `Timeframe` - 99 edges
6. `Price` - 81 edges
7. `Position` - 68 edges
8. `MarketType` - 66 edges
9. `SimulatedVenue` - 56 edges
10. `Ledger` - 56 edges

## Surprising Connections (you probably didn't know these)
- `backtestFixture()` --calls--> `SimClock`  [INFERRED]
  tests/Support/backtest-fixtures.php → app/Support/Clock/SimClock.php
- `perpFixture()` --calls--> `FundingRate`  [INFERRED]
  tests/Support/backtest-fixtures.php → app/Market/Data/FundingRate.php
- `{closure#14}()` --calls--> `MoveStop`  [INFERRED]
  tests/Unit/Execution/EngineTest.php → app/Strategy/Data/MoveStop.php
- `{closure#7}()` --calls--> `MoveStop`  [INFERRED]
  tests/Unit/Execution/EngineTest.php → app/Strategy/Data/MoveStop.php
- `{closure#4}()` --references--> `Candle`  [EXTRACTED]
  tests/Feature/Market/BinanceRestClientTest.php → app/Market/Data/Candle.php

## Import Cycles
- None detected.

## Communities (129 total, 25 thin omitted)

### Community 0 - ".of"
Cohesion: 0.20
Nodes (43): RiskState, account(), accountPolicy(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}() (+35 more)

### Community 1 - "EngineTest.php"
Cohesion: 0.08
Nodes (34): RenamedStrategy, ScriptedStrategy, {closure#1}(), {closure#10}(), {closure#13}(), {closure#14}(), {closure#16}(), {closure#17}() (+26 more)

### Community 2 - "App\Support\Number\Decimal"
Cohesion: 0.04
Nodes (25): {closure#15}(), {closure#3}(), {closure#10}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}() (+17 more)

### Community 3 - "Instrument"
Cohesion: 0.08
Nodes (15): Backfill, {closure#3}(), BackfillReport, BackfillStep, CandleRepository, CandleSource, Archive, Rest (+7 more)

### Community 4 - ".zero"
Cohesion: 0.09
Nodes (25): FeeSchedule, Liquidity, Maker, Taker, OrderStatus, Cancelled, Expired, Filled (+17 more)

### Community 5 - ".parse"
Cohesion: 0.10
Nodes (36): {closure#1}(), Parameters, carryDecisions(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}() (+28 more)

### Community 6 - "MarketType"
Cohesion: 0.07
Nodes (20): BinanceRestClient, Json, InstrumentDefinition, InstrumentStatus, Delisted, Halted, Trading, MarketType (+12 more)

### Community 7 - "Position"
Cohesion: 0.07
Nodes (25): Argument, BoolLiteral, Call, DurationLiteral, Identifier, Lookback, Node, NumberLiteral (+17 more)

### Community 8 - "CompiledStrategy.php"
Cohesion: 0.12
Nodes (6): {closure#3}(), CompiledStrategy, {closure#34}(), Compiler, Program, {closure#2}()

### Community 9 - "SimulatedVenueTest.php"
Cohesion: 0.18
Nodes (42): btc(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#15}(), {closure#16}() (+34 more)

### Community 10 - "App\Support\Symbol"
Cohesion: 0.08
Nodes (13): {closure#6}(), {closure#7}(), {closure#2}(), {closure#1}(), LimitedStrategy, EntryOrder, OpenLong, OpenShort (+5 more)

### Community 11 - "SimulatedPerpVenueTest.php"
Cohesion: 0.19
Nodes (24): {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#15}(), {closure#18}(), {closure#19}() (+16 more)

### Community 12 - "Candle"
Cohesion: 0.15
Nodes (29): {closure#1}(), {closure#2}(), Candle, {closure#1}(), {closure#2}(), candleWith(), {closure#1}(), {closure#10}() (+21 more)

### Community 13 - "ExchangeVerificationTest.php"
Cohesion: 0.11
Nodes (28): {closure#1}(), {closure#12}(), {closure#13}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}() (+20 more)

### Community 14 - "IndicatorsTest.php"
Cohesion: 0.08
Nodes (22): Atr, Donchian, Ema, Period, Sma, bar(), {closure#1}(), {closure#10}() (+14 more)

### Community 15 - "AppServiceProvider.php"
Cohesion: 0.17
Nodes (5): SystemSleeper, AppServiceProvider, {closure#1}(), {closure#2}(), SimulationQueue

### Community 16 - "Node"
Cohesion: 0.13
Nodes (22): Binary, DslError, Parser, Token, TokenKind, Colon, Comma, Dot (+14 more)

### Community 17 - ".of"
Cohesion: 0.12
Nodes (16): AssetMismatch, {closure#2}(), {closure#1}(), usdt(), {closure#7}(), {closure#1}(), {closure#11}(), {closure#12}() (+8 more)

### Community 18 - "App\Strategy\Dsl\Position"
Cohesion: 0.14
Nodes (9): YamlList, YamlReader, YamlScalar, YamlValue, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}() (+1 more)

### Community 19 - "CandleStreamTest.php"
Cohesion: 0.07
Nodes (14): BinanceEndpoints, Sleeper, CandleStream, PhrityTransport, StreamFailed, StreamTransport, {closure#1}(), {closure#2}() (+6 more)

### Community 20 - "Ledger"
Cohesion: 0.21
Nodes (30): Side, Buy, Sell, balanceOf(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}() (+22 more)

### Community 21 - "Illuminate\Database\Schema\Blueprint"
Cohesion: 0.08
Nodes (18): {closure#1}(), {closure#2}(), {closure#3}(), {closure#1}(), {closure#2}(), {closure#1}(), {closure#2}(), {closure#3}() (+10 more)

### Community 22 - "generate.py"
Cohesion: 0.09
Nodes (28): atr(), bars(), bb(), change(), dmi(), donchian(), ema(), highest() (+20 more)

### Community 23 - "SimulatedVenue"
Cohesion: 0.08
Nodes (20): Venue, ClientOrderId, OcoRequest, OrderAck, OrderRequest, OrderType, Limit, Market (+12 more)

### Community 24 - "Symbol"
Cohesion: 0.06
Nodes (19): {closure#1}(), BuyAndHold, DonchianBreakout, FundingCarry, BuildableStrategy, Strategy, OpenCarry, PositionView (+11 more)

### Community 25 - "Unit"
Cohesion: 0.08
Nodes (19): {closure#1}(), {closure#2}(), ParamKind, Anchor, Count, Duration, Number, Percent (+11 more)

### Community 26 - "OrderManagerTest.php"
Cohesion: 0.28
Nodes (15): btcUsdt(), {closure#10}(), {closure#14}(), {closure#15}(), {closure#16}(), {closure#2}(), {closure#4}(), {closure#5}() (+7 more)

### Community 27 - "RejectionReason"
Cohesion: 0.08
Nodes (27): AccountView, RejectionReason, BelowMinimum, Cooldown, DuplicateIntent, EntryWorking, GrossExposure, Halted (+19 more)

### Community 28 - "OrderRole"
Cohesion: 0.16
Nodes (14): OrderRole, Entry, Exit, Flatten, Stop, Target, TrackedOrder, {closure#1}() (+6 more)

### Community 29 - "Decimal"
Cohesion: 0.06
Nodes (16): Balance, VenuePosition, {closure#1}(), SimulatedPosition, {closure#1}(), {closure#2}(), {closure#1}(), {closure#1}() (+8 more)

### Community 30 - "EnginePerpTest.php"
Cohesion: 0.17
Nodes (17): InMemoryDecisionLog, Close, {closure#10}(), {closure#11}(), {closure#13}(), {closure#14}(), {closure#2}(), {closure#5}() (+9 more)

### Community 32 - "ValidationStatisticsTest.php"
Cohesion: 0.14
Nodes (16): DeflatedSharpe, Normal, closeTo(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#2}() (+8 more)

### Community 33 - "DecimalMathTest.php"
Cohesion: 0.08
Nodes (43): DecimalMath, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#2}(), {closure#3}(), balances() (+35 more)

### Community 34 - "Engine"
Cohesion: 0.11
Nodes (6): DecisionLog, EngineRecord, IntentRecord, Engine, ReconciliationMismatch, Intent

### Community 35 - ".expect"
Cohesion: 0.16
Nodes (14): CheckResult, {closure#1}(), KnownInstruments, {closure#1}(), checkDocument(), {closure#1}(), {closure#2}(), {closure#4}() (+6 more)

### Community 36 - "Timeframe"
Cohesion: 0.11
Nodes (15): {closure#4}(), {closure#8}(), Timeframe, FifteenMinutes, FiveMinutes, FourHours, OneDay, OneHour (+7 more)

### Community 37 - "SimClock"
Cohesion: 0.10
Nodes (21): {closure#11}(), {closure#13}(), {closure#14}(), {closure#15}(), {closure#16}(), {closure#4}(), {closure#5}(), {closure#6}() (+13 more)

### Community 38 - ".plus"
Cohesion: 0.12
Nodes (7): Fill, Position, {closure#2}(), Perpetual, {closure#16}(), {closure#17}(), {closure#5}()

### Community 39 - "BacktestTest.php"
Cohesion: 0.12
Nodes (22): CandleFeed, Backtest, {closure#1}(), {closure#2}(), Participant, RunDefinition, {closure#3}(), {closure#4}() (+14 more)

### Community 40 - "Price"
Cohesion: 0.10
Nodes (4): ProtectionPlan, PerpPosition, MoveStop, Price

### Community 41 - "ema-cross-scalp-long.strategy.yaml"
Cohesion: 0.06
Nodes (33): entries, long, exits, stop_loss, take_profit, time_stop, when, fails_when (+25 more)

### Community 42 - "RiskPolicy"
Cohesion: 0.16
Nodes (10): RiskProfile, Account, Benchmark, {closure#1}(), RiskPolicy, {closure#1}(), {closure#2}(), {closure#3}() (+2 more)

### Community 43 - "Kind"
Cohesion: 0.08
Nodes (33): {closure#3}(), {closure#4}(), {closure#7}(), {closure#8}(), {closure#9}(), Expression, ParameterSpec, DslWriter (+25 more)

### Community 44 - "sources"
Cohesion: 0.09
Nodes (22): secret, type, sources, auth, channels, connector, exchange, id (+14 more)

### Community 46 - "GridBacktest.php"
Cohesion: 0.06
Nodes (15): {closure#3}(), {closure#10}(), {closure#11}(), {closure#14}(), {closure#17}(), {closure#18}(), {closure#19}(), {closure#2}() (+7 more)

### Community 47 - "package.json"
Cohesion: 0.13
Nodes (20): devDependencies, concurrently@^10.0.3, laravel-vite-plugin@^3.1, tailwindcss@^4.0.0, @tailwindcss/vite@^4.0.0, vite@^8.0.0, optionalDependencies, @laravel/multiplex@^0.4.1 (+12 more)

### Community 48 - "PreparesBacktests.php"
Cohesion: 0.13
Nodes (9): SaveResult, StrategyRepository, StrategyVersion, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}() (+1 more)

### Community 49 - "BinanceRestClientTest.php"
Cohesion: 0.11
Nodes (16): BinanceRequestFailed, LocationRestricted, RateLimited, RequestFailed, binanceClient(), {closure#1}(), {closure#11}(), {closure#13}() (+8 more)

### Community 50 - "ema-cross-scalp-short.strategy.yaml"
Cohesion: 0.06
Nodes (33): entries, short, exits, stop_loss, take_profit, time_stop, when, fails_when (+25 more)

### Community 51 - ".handle"
Cohesion: 0.20
Nodes (6): CanonicalJson, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}()

### Community 52 - "TokensTest.php"
Cohesion: 0.31
Nodes (7): {closure#3}(), {closure#4}(), {closure#5}(), contrastRatio(), contrastRules(), relativeLuminance(), themeTokens()

### Community 53 - "ExitReason"
Cohesion: 0.15
Nodes (26): ExitReason, Flatten, Liquidation, Signal, Stop, Target, RoundTrip, {closure#1}() (+18 more)

### Community 54 - "postgres"
Cohesion: 0.10
Nodes (19): POSTGRES_DB, POSTGRES_PASSWORD, POSTGRES_USER, interval, retries, test, timeout, environment (+11 more)

### Community 55 - "InstrumentRules"
Cohesion: 0.19
Nodes (13): InstrumentRules, {closure#1}(), RulesHistory, {closure#1}(), {closure#2}(), {closure#4}(), {closure#41}(), {closure#1}() (+5 more)

### Community 56 - "PreparesBacktests"
Cohesion: 0.13
Nodes (4): ExportStrategies, ImportStrategies, PreparesBacktests, RunBacktest

### Community 57 - "PolicyLimits"
Cohesion: 0.26
Nodes (4): InstrumentDirectory, StoredInstruments, CompileStrategy, PolicyLimits

### Community 58 - ".measure"
Cohesion: 0.18
Nodes (9): EquityTracker, Performance, {closure#1}(), {closure#2}(), {closure#3}(), {closure#5}(), {closure#6}(), {closure#8}() (+1 more)

### Community 59 - "GlobalsTest.php"
Cohesion: 0.22
Nodes (4): Controller, {closure#3}(), {closure#4}(), {closure#5}()

### Community 60 - "RunBacktestCommandTest.php"
Cohesion: 0.20
Nodes (21): {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#15}(), {closure#16}() (+13 more)

### Community 61 - "OrderManager"
Cohesion: 0.21
Nodes (3): {closure#4}(), {closure#1}(), OrderManager

### Community 63 - "CandleFeedTest.php"
Cohesion: 0.25
Nodes (15): MinuteCandles, {closure#1}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#2}(), {closure#3}(), {closure#4}() (+7 more)

### Community 64 - ".run"
Cohesion: 0.12
Nodes (17): PerformanceMetrics, TradeStatistics, Report, MovingAverageTrend, btcMinutes(), {closure#1}(), {closure#2}(), referencePrice() (+9 more)

### Community 65 - "ArchiveImportTest.php"
Cohesion: 0.16
Nodes (12): ArchiveMissing, ChecksumMismatch, archiveBytes(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}() (+4 more)

### Community 66 - "IndicatorReferenceTest.php"
Cohesion: 0.09
Nodes (23): Change, DonchianChannel, Highest, Hma, Lowest, Macd, RealizedVolatility, Roc (+15 more)

### Community 67 - "SimClock"
Cohesion: 0.16
Nodes (14): SimClock, WallClock, {closure#1}(), {closure#1}(), {closure#2}(), {closure#3}(), definition(), {closure#1}() (+6 more)

### Community 68 - "CandleRepositoryTest.php"
Cohesion: 0.28
Nodes (11): candleStore(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}() (+3 more)

### Community 69 - "Ledger"
Cohesion: 0.12
Nodes (10): Marks, Ledger, LedgerViolation, ParticipantResult, {closure#1}(), {closure#3}(), {closure#4}(), Asset (+2 more)

### Community 70 - "YamlMap"
Cohesion: 0.30
Nodes (3): {closure#5}(), DocumentChecker, YamlMap

### Community 71 - "Illuminate\Database\ConnectionInterface"
Cohesion: 0.09
Nodes (12): {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), DatabaseCandleFeed, {closure#1}(), Heartbeats, Rows (+4 more)

### Community 72 - "SimulationJobsTest.php"
Cohesion: 0.12
Nodes (5): {closure#1}(), {closure#1}(), {closure#2}(), {closure#3}(), savedEmaCross()

### Community 73 - "scripts"
Cohesion: 0.14
Nodes (14): scripts, analyse, dev, format, mutate, post-autoload-dump, post-update-cmd, pre-package-uninstall (+6 more)

### Community 74 - "BinanceArchive.php"
Cohesion: 0.09
Nodes (16): {closure#1}(), {closure#2}(), EpochTime, ExchangeNumber, KlineMessage, {closure#3}(), {closure#1}(), {closure#2}() (+8 more)

### Community 75 - ".__construct"
Cohesion: 0.33
Nodes (5): EngineMode, Backtest, Live, Paper, Testnet

### Community 76 - ".walk"
Cohesion: 0.21
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

### Community 82 - "CompiledStrategyTest.php"
Cohesion: 0.15
Nodes (16): {closure#1}(), {closure#42}(), aggregatedBars(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#16}(), {closure#17}() (+8 more)

### Community 83 - "App\Support\Number\Decimal"
Cohesion: 0.07
Nodes (38): {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#15}(), {closure#16}(), {closure#17}() (+30 more)

### Community 84 - "pint/composer.json"
Cohesion: 0.20
Nodes (9): config, sort-packages, description, license, name, require, laravel/pint, php (+1 more)

### Community 85 - ".count"
Cohesion: 0.28
Nodes (9): Statistics, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}() (+1 more)

### Community 87 - "bootstrap/app.php"
Cohesion: 0.33
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

### Community 88 - "require-dev"
Cohesion: 0.22
Nodes (9): require-dev, fakerphp/faker, larastan/larastan, laravel/pail, laravel/pao, mockery/mockery, nunomaduro/collision, pestphp/pest (+1 more)

### Community 89 - "scripts-descriptions"
Cohesion: 0.22
Nodes (9): scripts-descriptions, analyse, format, mutate, test, test:llm, test:network, test:testnet (+1 more)

### Community 90 - "PerpetualBaselinesTest.php"
Cohesion: 0.24
Nodes (8): {closure#1}(), {closure#3}(), goldenMinutes(), goldenRates(), perpetualReferencePrice(), referenceMean(), reportTrades(), GoldenData

### Community 91 - ".evaluate"
Cohesion: 0.25
Nodes (8): WalkForward, WalkForwardSet, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), wfSet()

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

### Community 96 - "OrderUpdate"
Cohesion: 0.16
Nodes (8): Income, IncomeKind, Funding, Liquidation, OrderUpdate, VenueFill, {closure#3}(), {closure#1}()

### Community 97 - "run"
Cohesion: 0.33
Nodes (6): name, runs-on, steps, audit, run, steps

### Community 99 - "psr-4"
Cohesion: 0.40
Nodes (5): autoload, psr-4, App\\, Database\\Factories\\, Database\\Seeders\\

### Community 100 - "require"
Cohesion: 0.40
Nodes (5): require, laravel/framework, laravel/tinker, php, phrity/websocket

### Community 103 - "steps"
Cohesion: 0.50
Nodes (4): name, runs-on, steps, analyse

### Community 104 - "steps"
Cohesion: 0.50
Nodes (4): name, runs-on, steps, frontend

### Community 105 - "lint"
Cohesion: 0.50
Nodes (4): lint, name, runs-on, steps

### Community 108 - "extra"
Cohesion: 0.67
Nodes (3): extra, laravel, dont-discover

### Community 119 - "Carbon\CarbonImmutable"
Cohesion: 0.06
Nodes (14): DecisionRecord, {closure#1}(), FundingRate, InstrumentFilter, RiskEvent, RiskEventKind, DailyLossHalt, DrawdownFlatten (+6 more)

### Community 121 - "CompiledStrategyTest.php"
Cohesion: 0.09
Nodes (10): {closure#37}(), BarIndicator, DirectionalMovement, Keltner, Natr, Supertrend, Vwap, {closure#9}() (+2 more)

### Community 122 - "App\Simulation\Validation\WalkForward"
Cohesion: 0.18
Nodes (8): {closure#1}(), {closure#3}(), {closure#5}(), {closure#6}(), {closure#7}(), {closure#8}(), {closure#9}(), WalkForwardWindow

### Community 123 - "Group"
Cohesion: 0.20
Nodes (9): Group, Agent, Exit, Indicator, Order, Perpetual, Position, Price (+1 more)

### Community 127 - "Illuminate\Console\Command"
Cohesion: 0.14
Nodes (11): BackfillMarketData, CheckMarketData, SelectsInstruments, {closure#1}(), StreamCandles, SyncInstruments, VerifyMarketData, Universe (+3 more)

### Community 130 - "ParserTest.php"
Cohesion: 0.18
Nodes (7): AstPrinter, Lexer, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), sexpr()

## Knowledge Gaps
- **121 isolated node(s):** `Limit`, `Market`, `StopMarket`, `TakeProfitMarket`, `Colon` (+116 more)
  These have ≤1 connection - possible missing edges. (Counts symbols only; 596 node(s) total have ≤1 connection when file, concept and rationale nodes are included.)
- **25 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `Decimal` connect `Decimal` to `.__construct`, `.of`, `App\Support\Number\Decimal`, `.zero`, `.parse`, `MarketType`, `Position`, `SimulatedVenueTest.php`, `App\Support\Symbol`, `Candle`, `IndicatorsTest.php`, `.of`, `Ledger`, `SimulatedVenue`, `Symbol`, `OrderManagerTest.php`, `RejectionReason`, `OrderRole`, `EnginePerpTest.php`, `.times`, `ValidationStatisticsTest.php`, `DecimalMathTest.php`, `Engine`, `.plus`, `BacktestTest.php`, `Price`, `RiskPolicy`, `GridBacktest.php`, `.handle`, `ExitReason`, `InstrumentRules`, `.measure`, `OrderManager`, `CandleFeedTest.php`, `.run`, `SimClock`, `Ledger`, `Illuminate\Database\ConnectionInterface`, `BinanceArchive.php`, `.walk`, `.count`, `PerpetualBaselinesTest.php`, `OrderUpdate`, `Carbon\CarbonImmutable`, `CompiledStrategyTest.php`, `ForwardingVenue`?**
  _High betweenness centrality (0.139) - this node is a cross-community bridge._
- **What connects `Limit`, `Market`, `StopMarket` to the rest of the system?**
  _121 weakly-connected nodes found - possible missing edges._
- **Should `EngineTest.php` be split into smaller, more focused modules?**
  _Cohesion score 0.08413461538461539 - nodes in this community are weakly interconnected._
- **Why does `Candle` connect `Candle` to `.__construct`, `Instrument`, `.zero`, `.parse`, `MarketType`, `SimulatedVenueTest.php`, `ExchangeVerificationTest.php`, `IndicatorsTest.php`, `CandleStreamTest.php`, `SimulatedVenue`, `Symbol`, `OrderManagerTest.php`, `Decimal`, `EnginePerpTest.php`, `Timeframe`, `BacktestTest.php`, `Price`, `BinanceRestClientTest.php`, `ExitReason`, `BinanceArchive`, `CandleFeedTest.php`, `.run`, `CandleRepositoryTest.php`, `Illuminate\Database\ConnectionInterface`, `BinanceArchive.php`, `.walk`, `CompiledStrategyTest.php`, `PerpetualBaselinesTest.php`, `Carbon\CarbonImmutable`, `CompiledStrategyTest.php`?**
  _High betweenness centrality (0.038) - this node is a cross-community bridge._
- **Should `App\Support\Number\Decimal` be split into smaller, more focused modules?**
  _Cohesion score 0.041801801801801805 - nodes in this community are weakly interconnected._
- **Why does `Symbol` connect `Symbol` to `App\Support\Number\Decimal`, `Instrument`, `.zero`, `.parse`, `MarketType`, `SimulatedVenueTest.php`, `App\Support\Symbol`, `SimulatedPerpVenueTest.php`, `ExchangeVerificationTest.php`, `CandleStreamTest.php`, `Ledger`, `SimulatedVenue`, `OrderManagerTest.php`, `OrderRole`, `Decimal`, `EnginePerpTest.php`, `Engine`, `SimClock`, `.plus`, `BacktestTest.php`, `Price`, `GridBacktest.php`, `.handle`, `ExitReason`, `InstrumentRules`, `.measure`, `OrderManager`, `CandleFeedTest.php`, `.run`, `SimClock`, `CandleRepositoryTest.php`, `Ledger`, `PerpetualBaselinesTest.php`, `OrderUpdate`, `Carbon\CarbonImmutable`, `ForwardingVenue`?**
  _High betweenness centrality (0.033) - this node is a cross-community bridge._
- **Should `Instrument` be split into smaller, more focused modules?**
  _Cohesion score 0.07562136435748282 - nodes in this community are weakly interconnected._