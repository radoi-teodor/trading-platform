# Graph Report - trading-platform  (2026-11-08)

## Corpus Check
- 382 files · ~155,112 words
- Verdict: corpus is large enough that graph structure adds value.
- Unclassified: 92 file(s) not represented in the graph (top: .zip 34, .CHECKSUM 34, (none) 18)

## Summary
- 3133 nodes · 11477 edges · 122 communities (96 shown, 26 thin omitted)
- Extraction: 89% EXTRACTED · 11% INFERRED · 0% AMBIGUOUS · INFERRED: 1211 edges (avg confidence: 0.85)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `0986132c`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- .of
- EngineTest.php
- InvalidArgumentException
- Instrument
- SimulatedVenue
- .parse
- MarketType
- Position
- CompiledStrategy.php
- SimulatedVenueTest.php
- .assessEntry
- SimulatedPerpVenueTest.php
- Candle
- ExchangeVerificationTest.php
- IndicatorsTest.php
- BinanceArchive
- Node
- Money
- App\Strategy\Dsl\Position
- CandleStreamTest.php
- .of
- Illuminate\Database\Schema\Blueprint
- generate.py
- Price
- StrategyContext
- Group
- OrderManagerTest.php
- Node
- OrderRole
- .measure
- EnginePerpTest.php
- Engine.php
- ValidationStatisticsTest.php
- DecimalMathTest.php
- Engine
- App\Strategy\Dsl\Document\DocumentChecker
- Timeframe
- SimClock
- Decimal
- BacktestTest.php
- .times
- ema-cross-scalp-long.strategy.yaml
- RiskPolicy
- DocumentChecker.php
- sources
- User
- GridBacktest.php
- package.json
- App\Market\Data\MarketType
- BinanceRestClientTest.php
- ema-cross-scalp-short.strategy.yaml
- .handle
- TokensTest.php
- PhrityTransport
- postgres
- InstrumentRules
- PreparesBacktests
- TypeChecker
- .sqrt
- GlobalsTest.php
- RunBacktestCommandTest.php
- OrderManager
- Carbon\CarbonImmutable
- CandleFeedTest.php
- .run
- QualityReport
- IndicatorReferenceTest.php
- DonchianBreakout
- CandleRepositoryTest.php
- Rows
- YamlMap
- Universe
- SimulatedFuturesBook.php
- scripts
- BinanceArchive.php
- App\Simulation\Backtest
- .account
- composer.json
- jobs
- postgres
- .build
- Window
- CompiledStrategyTest.php
- App\Support\Number\Decimal
- pint/composer.json
- App\Strategy\Contracts\Strategy
- Quantity
- bootstrap/app.php
- require-dev
- scripts-descriptions
- PerpetualBaselinesTest.php
- App\Risk\RiskState
- config
- uses
- test
- with
- Rsi
- run
- psr-4
- require
- DatabaseSeeder
- steps
- steps
- lint
- extra
- Parser
- CompiledStrategyTest.php
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
- `engineHarness()` --calls--> `FeeSchedule`  [INFERRED]
  tests/Unit/Execution/EngineTest.php → app/Execution/Data/FeeSchedule.php
- `engineHarness()` --calls--> `CostModel`  [INFERRED]
  tests/Unit/Execution/EngineTest.php → app/Execution/Venues/CostModel.php
- `engineHarness()` --calls--> `InstrumentRules`  [INFERRED]
  tests/Unit/Execution/EngineTest.php → app/Market/Data/InstrumentRules.php
- `engineHarness()` --calls--> `RulesHistory`  [INFERRED]
  tests/Unit/Execution/EngineTest.php → app/Market/Data/RulesHistory.php
- `engineHarness()` --calls--> `Ledger`  [INFERRED]
  tests/Unit/Execution/EngineTest.php → app/Portfolio/Ledger.php

## Import Cycles
- None detected.

## Communities (122 total, 26 thin omitted)

### Community 0 - ".of"
Cohesion: 0.09
Nodes (69): RejectionReason, BelowMinimum, Cooldown, DuplicateIntent, EntryWorking, GrossExposure, Halted, InvalidStop (+61 more)

### Community 1 - "EngineTest.php"
Cohesion: 0.09
Nodes (35): {closure#1}(), MoveStop, ScriptedStrategy, {closure#1}(), {closure#10}(), {closure#13}(), {closure#14}(), {closure#16}() (+27 more)

### Community 2 - "InvalidArgumentException"
Cohesion: 0.10
Nodes (11): {closure#10}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}(), {closure#8}() (+3 more)

### Community 3 - "Instrument"
Cohesion: 0.05
Nodes (24): Backfill, {closure#1}(), {closure#2}(), {closure#3}(), BackfillStep, CandleRepository, {closure#1}(), {closure#2}() (+16 more)

### Community 4 - "SimulatedVenue"
Cohesion: 0.13
Nodes (11): Liquidity, Maker, Taker, OrderStatus, Cancelled, Expired, Filled, New (+3 more)

### Community 5 - ".parse"
Cohesion: 0.11
Nodes (33): carryDecisions(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#15}(), {closure#16}() (+25 more)

### Community 6 - "MarketType"
Cohesion: 0.06
Nodes (29): BinanceRestClient, {closure#1}(), Json, RequestFailed, SyncInstruments, InstrumentDefinition, InstrumentStatus, Delisted (+21 more)

### Community 7 - "Position"
Cohesion: 0.08
Nodes (14): Argument, BoolLiteral, Call, DurationLiteral, Identifier, NumberLiteral, OnTimeframe, Parameter (+6 more)

### Community 8 - "CompiledStrategy.php"
Cohesion: 0.09
Nodes (10): {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), CompiledStrategy, {closure#34}(), Compiler (+2 more)

### Community 9 - "SimulatedVenueTest.php"
Cohesion: 0.18
Nodes (46): Side, Buy, Sell, btc(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}() (+38 more)

### Community 10 - ".assessEntry"
Cohesion: 0.16
Nodes (5): {closure#2}(), AccountView, Verdict, RiskEngine, OpenShort

### Community 11 - "SimulatedPerpVenueTest.php"
Cohesion: 0.18
Nodes (25): {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#15}(), {closure#18}() (+17 more)

### Community 12 - "Candle"
Cohesion: 0.11
Nodes (30): Candle, InvalidCandle, {closure#1}(), {closure#2}(), {closure#42}(), candleWith(), {closure#1}(), {closure#10}() (+22 more)

### Community 13 - "ExchangeVerificationTest.php"
Cohesion: 0.09
Nodes (30): VerificationSource, DailyArchive, Rest, {closure#1}(), {closure#12}(), {closure#13}(), {closure#2}(), {closure#3}() (+22 more)

### Community 14 - "IndicatorsTest.php"
Cohesion: 0.15
Nodes (18): {closure#47}(), Atr, Ema, Sma, ValueIndicator, bar(), {closure#1}(), {closure#12}() (+10 more)

### Community 15 - "BinanceArchive"
Cohesion: 0.12
Nodes (6): BinanceArchive, BinanceEndpoints, SystemSleeper, AppServiceProvider, {closure#1}(), {closure#2}()

### Community 16 - "Node"
Cohesion: 0.09
Nodes (20): Lexer, Token, TokenKind, Colon, Comma, Dot, Duration, End (+12 more)

### Community 18 - "App\Strategy\Dsl\Position"
Cohesion: 0.13
Nodes (10): DslError, YamlList, YamlReader, YamlScalar, YamlValue, {closure#1}(), {closure#2}(), {closure#3}() (+2 more)

### Community 19 - "CandleStreamTest.php"
Cohesion: 0.10
Nodes (12): Sleeper, CandleStream, StreamTransport, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}() (+4 more)

### Community 20 - ".of"
Cohesion: 0.05
Nodes (76): Balance, ExitReason, Flatten, Liquidation, Signal, Stop, Target, Marks (+68 more)

### Community 21 - "Illuminate\Database\Schema\Blueprint"
Cohesion: 0.09
Nodes (15): {closure#1}(), {closure#2}(), {closure#3}(), {closure#1}(), {closure#2}(), {closure#1}(), {closure#2}(), {closure#3}() (+7 more)

### Community 22 - "generate.py"
Cohesion: 0.09
Nodes (28): atr(), bars(), bb(), change(), dmi(), donchian(), ema(), highest() (+20 more)

### Community 23 - "Price"
Cohesion: 0.06
Nodes (25): Venue, ClientOrderId, OcoRequest, OrderAck, OrderRequest, OrderType, Limit, Market (+17 more)

### Community 24 - "StrategyContext"
Cohesion: 0.06
Nodes (17): BuyAndHold, FundingCarry, MovingAverageTrend, BuildableStrategy, Strategy, Close, Intent, OpenLong (+9 more)

### Community 25 - "Group"
Cohesion: 0.07
Nodes (23): FunctionSpec, Group, Agent, Exit, Indicator, Order, Perpetual, Position (+15 more)

### Community 26 - "OrderManagerTest.php"
Cohesion: 0.28
Nodes (15): btcUsdt(), {closure#10}(), {closure#15}(), {closure#16}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}() (+7 more)

### Community 27 - "Node"
Cohesion: 0.19
Nodes (11): Lookback, Node, Unary, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}() (+3 more)

### Community 28 - "OrderRole"
Cohesion: 0.14
Nodes (15): OrderRole, Entry, Exit, Flatten, Stop, Target, TrackedOrder, {closure#1}() (+7 more)

### Community 29 - ".measure"
Cohesion: 0.19
Nodes (9): EquityTracker, Performance, {closure#1}(), {closure#2}(), {closure#3}(), {closure#5}(), {closure#6}(), {closure#8}() (+1 more)

### Community 30 - "EnginePerpTest.php"
Cohesion: 0.24
Nodes (16): {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#14}(), {closure#2}(), {closure#5}(), {closure#6}() (+8 more)

### Community 31 - "Engine.php"
Cohesion: 0.09
Nodes (10): DecisionLog, DecisionRecord, EngineMode, Backtest, Live, Paper, Testnet, {closure#4}() (+2 more)

### Community 32 - "ValidationStatisticsTest.php"
Cohesion: 0.11
Nodes (20): Cscv, DeflatedSharpe, Normal, closeTo(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}() (+12 more)

### Community 33 - "DecimalMathTest.php"
Cohesion: 0.06
Nodes (51): {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#2}(), {closure#3}(), {closure#1}(), {closure#2}() (+43 more)

### Community 34 - "Engine"
Cohesion: 0.19
Nodes (4): EngineRecord, IntentRecord, Engine, {closure#3}()

### Community 35 - "App\Strategy\Dsl\Document\DocumentChecker"
Cohesion: 0.10
Nodes (18): InstrumentDirectory, StoredInstruments, CompileStrategy, CheckResult, {closure#1}(), PolicyLimits, KnownInstruments, {closure#1}() (+10 more)

### Community 36 - "Timeframe"
Cohesion: 0.09
Nodes (32): CandleAggregator, CandleSequenceBroken, {closure#8}(), Timeframe, FifteenMinutes, FiveMinutes, FourHours, OneDay (+24 more)

### Community 37 - "SimClock"
Cohesion: 0.06
Nodes (32): FundingRate, SimClock, WallClock, {closure#11}(), {closure#13}(), {closure#14}(), {closure#15}(), {closure#16}() (+24 more)

### Community 38 - "Decimal"
Cohesion: 0.07
Nodes (15): Position, RoundTrip, {closure#1}(), {closure#2}(), {closure#3}(), {closure#1}(), {closure#2}(), {closure#4}() (+7 more)

### Community 39 - "BacktestTest.php"
Cohesion: 0.17
Nodes (17): FeeSchedule, CostModel, CandleFeed, Backtest, Participant, {closure#1}(), RunDefinition, InMemoryCandleFeed (+9 more)

### Community 40 - ".times"
Cohesion: 0.10
Nodes (7): {closure#2}(), Fill, PerpPosition, Perpetual, {closure#16}(), {closure#6}(), {closure#7}()

### Community 41 - "ema-cross-scalp-long.strategy.yaml"
Cohesion: 0.06
Nodes (33): entries, long, exits, stop_loss, take_profit, time_stop, when, fails_when (+25 more)

### Community 42 - "RiskPolicy"
Cohesion: 0.15
Nodes (9): RiskProfile, Account, Benchmark, RiskPolicy, {closure#1}(), Policies, {closure#26}(), {closure#27}() (+1 more)

### Community 43 - "DocumentChecker.php"
Cohesion: 0.07
Nodes (31): {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#7}(), {closure#8}(), {closure#9}(), EntrySpec (+23 more)

### Community 44 - "sources"
Cohesion: 0.09
Nodes (22): secret, type, sources, auth, channels, connector, exchange, id (+14 more)

### Community 46 - "GridBacktest.php"
Cohesion: 0.09
Nodes (12): {closure#10}(), {closure#2}(), {closure#3}(), {closure#5}(), {closure#6}(), {closure#8}(), {closure#9}(), GridBacktest (+4 more)

### Community 47 - "package.json"
Cohesion: 0.13
Nodes (20): devDependencies, concurrently@^10.0.3, laravel-vite-plugin@^3.1, tailwindcss@^4.0.0, @tailwindcss/vite@^4.0.0, vite@^8.0.0, optionalDependencies, @laravel/multiplex@^0.4.1 (+12 more)

### Community 48 - "App\Market\Data\MarketType"
Cohesion: 0.24
Nodes (3): CarryTemplate, ExitSpec, StrategySpec

### Community 49 - "BinanceRestClientTest.php"
Cohesion: 0.13
Nodes (17): {closure#9}(), binanceClient(), {closure#1}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#2}(), {closure#3}() (+9 more)

### Community 50 - "ema-cross-scalp-short.strategy.yaml"
Cohesion: 0.06
Nodes (33): entries, short, exits, stop_loss, take_profit, time_stop, when, fails_when (+25 more)

### Community 51 - ".handle"
Cohesion: 0.18
Nodes (7): RunBacktest, CanonicalJson, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}()

### Community 52 - "TokensTest.php"
Cohesion: 0.31
Nodes (7): {closure#3}(), {closure#4}(), {closure#5}(), contrastRatio(), contrastRules(), relativeLuminance(), themeTokens()

### Community 53 - "PhrityTransport"
Cohesion: 0.08
Nodes (7): ArchiveMissing, ChecksumMismatch, BinanceRequestFailed, LocationRestricted, RateLimited, PhrityTransport, StreamFailed

### Community 54 - "postgres"
Cohesion: 0.10
Nodes (19): POSTGRES_DB, POSTGRES_PASSWORD, POSTGRES_USER, interval, retries, test, timeout, environment (+11 more)

### Community 55 - "InstrumentRules"
Cohesion: 0.18
Nodes (15): InstrumentRules, {closure#1}(), RulesHistory, {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#4}() (+7 more)

### Community 56 - "PreparesBacktests"
Cohesion: 0.13
Nodes (3): {closure#6}(), {closure#1}(), PreparesBacktests

### Community 57 - "TypeChecker"
Cohesion: 0.14
Nodes (11): {closure#6}(), Scope, {closure#4}(), TypeChecker, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}() (+3 more)

### Community 58 - ".sqrt"
Cohesion: 0.27
Nodes (9): Statistics, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}() (+1 more)

### Community 59 - "GlobalsTest.php"
Cohesion: 0.22
Nodes (4): Controller, {closure#3}(), {closure#4}(), {closure#5}()

### Community 60 - "RunBacktestCommandTest.php"
Cohesion: 0.12
Nodes (23): {closure#1}(), InstrumentFilter, BacktestRun, StrategyTrial, backtestFixture(), {closure#1}(), {closure#10}(), {closure#11}() (+15 more)

### Community 62 - "Carbon\CarbonImmutable"
Cohesion: 0.17
Nodes (11): ArchivePeriod, archiveBytes(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}() (+3 more)

### Community 63 - "CandleFeedTest.php"
Cohesion: 0.25
Nodes (15): MinuteCandles, {closure#1}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#2}(), {closure#3}(), {closure#4}() (+7 more)

### Community 64 - ".run"
Cohesion: 0.13
Nodes (17): PerformanceMetrics, TradeStatistics, Report, btcMinutes(), {closure#1}(), {closure#2}(), referencePrice(), {closure#2}() (+9 more)

### Community 66 - "IndicatorReferenceTest.php"
Cohesion: 0.08
Nodes (17): Bollinger, Hma, Macd, RealizedVolatility, StandardDeviation, Wma, assertMatchesReference(), {closure#10}() (+9 more)

### Community 67 - "DonchianBreakout"
Cohesion: 0.16
Nodes (5): DonchianBreakout, Parameters, Donchian, {closure#10}(), {closure#15}()

### Community 68 - "CandleRepositoryTest.php"
Cohesion: 0.28
Nodes (11): candleStore(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}() (+3 more)

### Community 69 - "Rows"
Cohesion: 0.28
Nodes (5): DatabaseCandleFeed, {closure#1}(), Rows, {closure#10}(), {closure#11}()

### Community 71 - "Universe"
Cohesion: 0.14
Nodes (8): BackfillReport, BackfillMarketData, CheckMarketData, SelectsInstruments, {closure#1}(), StreamCandles, VerifyMarketData, Universe

### Community 72 - "SimulatedFuturesBook.php"
Cohesion: 0.07
Nodes (11): Income, IncomeKind, Funding, Liquidation, VenuePosition, {closure#1}(), {closure#3}(), SimulatedFuturesBook (+3 more)

### Community 73 - "scripts"
Cohesion: 0.14
Nodes (14): scripts, analyse, dev, format, mutate, post-autoload-dump, post-update-cmd, pre-package-uninstall (+6 more)

### Community 74 - "BinanceArchive.php"
Cohesion: 0.12
Nodes (9): {closure#1}(), {closure#2}(), EpochTime, ExchangeNumber, {closure#1}(), {closure#2}(), {closure#3}(), {closure#1}() (+1 more)

### Community 75 - "App\Simulation\Backtest"
Cohesion: 0.24
Nodes (5): InMemoryDecisionLog, {closure#1}(), {closure#2}(), BacktestResult, ParticipantResult

### Community 77 - "composer.json"
Cohesion: 0.17
Nodes (11): autoload-dev, psr-4, description, keywords, license, minimum-stability, name, prefer-stable (+3 more)

### Community 78 - "jobs"
Cohesion: 0.17
Nodes (11): concurrency, cancel-in-progress, group, jobs, name, on, pull_request, push (+3 more)

### Community 79 - "postgres"
Cohesion: 0.17
Nodes (12): POSTGRES_DB, POSTGRES_PASSWORD, POSTGRES_USER, env, image, options, ports, image (+4 more)

### Community 81 - "Window"
Cohesion: 0.07
Nodes (7): {closure#46}(), Change, Highest, Lowest, Roc, Vwma, Window

### Community 82 - "CompiledStrategyTest.php"
Cohesion: 0.16
Nodes (15): PositionView, aggregatedBars(), {closure#11}(), {closure#13}(), {closure#16}(), {closure#17}(), {closure#2}(), {closure#3}() (+7 more)

### Community 83 - "App\Support\Number\Decimal"
Cohesion: 0.08
Nodes (40): {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#15}(), {closure#16}() (+32 more)

### Community 84 - "pint/composer.json"
Cohesion: 0.20
Nodes (9): config, sort-packages, description, license, name, require, laravel/pint, php (+1 more)

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
Cohesion: 0.36
Nodes (7): {closure#1}(), {closure#3}(), goldenMinutes(), goldenRates(), perpetualReferencePrice(), referenceMean(), reportTrades()

### Community 91 - "App\Risk\RiskState"
Cohesion: 0.24
Nodes (6): RiskEvent, RiskEventKind, DailyLossHalt, DrawdownFlatten, DrawdownHalt, WeeklyLossHalt

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

### Community 121 - "CompiledStrategyTest.php"
Cohesion: 0.09
Nodes (11): {closure#37}(), {closure#1}(), BarIndicator, DirectionalMovement, DonchianChannel, Keltner, Natr, Stochastic (+3 more)

### Community 130 - "ParserTest.php"
Cohesion: 0.36
Nodes (6): AstPrinter, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), sexpr()

## Knowledge Gaps
- **121 isolated node(s):** `Halted`, `PositionOpen`, `DuplicateIntent`, `NoPosition`, `NoStop` (+116 more)
  These have ≤1 connection - possible missing edges. (Counts symbols only; 572 node(s) total have ≤1 connection when file, concept and rationale nodes are included.)
- **26 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `Decimal` connect `Decimal` to `.of`, `EngineTest.php`, `InvalidArgumentException`, `Instrument`, `SimulatedVenue`, `.parse`, `MarketType`, `Position`, `SimulatedVenueTest.php`, `.assessEntry`, `Candle`, `IndicatorsTest.php`, `Money`, `.of`, `Price`, `StrategyContext`, `OrderManagerTest.php`, `Node`, `OrderRole`, `.measure`, `EnginePerpTest.php`, `Engine.php`, `DecimalMathTest.php`, `Engine`, `Timeframe`, `SimClock`, `BacktestTest.php`, `.times`, `RiskPolicy`, `GridBacktest.php`, `.handle`, `InstrumentRules`, `.sqrt`, `RunBacktestCommandTest.php`, `OrderManager`, `CandleFeedTest.php`, `.run`, `DonchianBreakout`, `SimulatedFuturesBook.php`, `BinanceArchive.php`, `App\Simulation\Backtest`, `.account`, `CompiledStrategyTest.php`, `App\Support\Number\Decimal`, `Quantity`, `PerpetualBaselinesTest.php`, `App\Risk\RiskState`, `CompiledStrategyTest.php`?**
  _High betweenness centrality (0.109) - this node is a cross-community bridge._
- **What connects `Halted`, `PositionOpen`, `DuplicateIntent` to the rest of the system?**
  _121 weakly-connected nodes found - possible missing edges._
- **Should `.of` be split into smaller, more focused modules?**
  _Cohesion score 0.0876750700280112 - nodes in this community are weakly interconnected._
- **Why does `Timeframe` connect `Timeframe` to `EngineTest.php`, `InvalidArgumentException`, `Instrument`, `.parse`, `MarketType`, `Position`, `CompiledStrategy.php`, `SimulatedVenueTest.php`, `Candle`, `IndicatorsTest.php`, `CandleStreamTest.php`, `.of`, `Price`, `StrategyContext`, `OrderManagerTest.php`, `Node`, `EnginePerpTest.php`, `CandleFeedTest.php`, `DonchianBreakout`, `CandleRepositoryTest.php`, `YamlMap`, `BinanceArchive.php`, `CompiledStrategyTest.php`, `CompiledStrategyTest.php`?**
  _High betweenness centrality (0.045) - this node is a cross-community bridge._
- **Should `EngineTest.php` be split into smaller, more focused modules?**
  _Cohesion score 0.09011776753712238 - nodes in this community are weakly interconnected._
- **Why does `Symbol` connect `Price` to `.of`, `EngineTest.php`, `InvalidArgumentException`, `Instrument`, `SimulatedVenue`, `.parse`, `MarketType`, `CompiledStrategy.php`, `SimulatedVenueTest.php`, `SimulatedPerpVenueTest.php`, `ExchangeVerificationTest.php`, `CandleStreamTest.php`, `.of`, `StrategyContext`, `OrderManagerTest.php`, `OrderRole`, `.measure`, `EnginePerpTest.php`, `Engine.php`, `Engine`, `Timeframe`, `SimClock`, `Decimal`, `BacktestTest.php`, `.times`, `.handle`, `InstrumentRules`, `PreparesBacktests`, `RunBacktestCommandTest.php`, `CandleFeedTest.php`, `.run`, `DonchianBreakout`, `CandleRepositoryTest.php`, `Universe`, `SimulatedFuturesBook.php`, `App\Simulation\Backtest`, `CompiledStrategyTest.php`, `Quantity`, `PerpetualBaselinesTest.php`?**
  _High betweenness centrality (0.042) - this node is a cross-community bridge._
- **Should `InvalidArgumentException` be split into smaller, more focused modules?**
  _Cohesion score 0.09686609686609686 - nodes in this community are weakly interconnected._