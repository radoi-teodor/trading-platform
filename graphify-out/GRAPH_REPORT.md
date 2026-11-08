# Graph Report - trading-platform  (2026-11-08)

## Corpus Check
- 379 files · ~152,964 words
- Verdict: corpus is large enough that graph structure adds value.
- Unclassified: 92 file(s) not represented in the graph (top: .zip 34, .CHECKSUM 34, (none) 18)

## Summary
- 3090 nodes · 11307 edges · 123 communities (98 shown, 25 thin omitted)
- Extraction: 90% EXTRACTED · 10% INFERRED · 0% AMBIGUOUS · INFERRED: 1181 edges (avg confidence: 0.85)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `e90c3c74`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- .of
- EngineTest.php
- App\Support\Number\Decimal
- Instrument
- SimulatedVenue
- .parse
- MarketType
- Position
- .call
- SimulatedVenueTest.php
- .assessEntry
- SimulatedPerpVenueTest.php
- Candle
- ExchangeVerificationTest.php
- IndicatorsTest.php
- ForwardingVenue
- Node
- .of
- App\Strategy\Dsl\Position
- CandleStreamTest.php
- LedgerTest.php
- Illuminate\Database\Schema\Blueprint
- generate.py
- Price
- StrategyContext
- Unit
- ExitReason
- .postfix
- OrderManagerTest.php
- .measure
- EnginePerpTest.php
- Engine.php
- ValidationStatisticsTest.php
- DecimalMathTest.php
- Engine
- App\Strategy\Dsl\Document\DocumentChecker
- Timeframe
- SimClock
- .zero
- BacktestTest.php
- Decimal
- ema-cross-scalp-long.strategy.yaml
- RiskPolicy
- DocumentChecker.php
- sources
- User
- GridBacktest.php
- package.json
- App\Support\Timeframe
- BinanceRestClientTest.php
- ema-cross-scalp-short.strategy.yaml
- .handle
- TokensTest.php
- PhrityTransport
- postgres
- Symbol
- PreparesBacktests
- App\Strategy\Dsl\Document\DocumentChecker
- .sqrt
- GlobalsTest.php
- RunBacktestCommandTest.php
- Asset
- Carbon\CarbonImmutable
- Carbon\CarbonImmutable
- .run
- QualityCheck
- IndicatorReferenceTest.php
- Ledger
- CandleRepositoryTest.php
- App\Market\Models\Instrument
- YamlMap
- Clock
- VenuePosition
- scripts
- .parse
- Group
- Expression
- composer.json
- jobs
- postgres
- .build
- .isFull
- CompiledStrategyTest.php
- App\Support\Number\Decimal
- pint/composer.json
- Unary
- Quantity
- bootstrap/app.php
- require-dev
- scripts-descriptions
- config
- uses
- test
- with
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
- .seconds
- PerformanceMetrics
- .push
- FundingCarry
- ParserTest.php
- Position
- .fromStrings

## God Nodes (most connected - your core abstractions)
1. `Decimal` - 324 edges
2. `Symbol` - 200 edges
3. `Side` - 183 edges
4. `Candle` - 112 edges
5. `Price` - 109 edges
6. `Timeframe` - 100 edges
7. `SimulatedVenue` - 68 edges
8. `Position` - 68 edges
9. `MarketType` - 66 edges
10. `InstrumentRules` - 64 edges

## Surprising Connections (you probably didn't know these)
- `backtestFixture()` --calls--> `SimClock`  [INFERRED]
  tests/Feature/Simulation/RunBacktestCommandTest.php → app/Support/Clock/SimClock.php
- `perpFixture()` --calls--> `FundingRate`  [INFERRED]
  tests/Feature/Simulation/RunBacktestCommandTest.php → app/Market/Data/FundingRate.php
- `{closure#4}()` --references--> `Candle`  [EXTRACTED]
  tests/Feature/Market/BinanceRestClientTest.php → app/Market/Data/Candle.php
- `{closure#7}()` --references--> `Candle`  [EXTRACTED]
  tests/Feature/Market/CandleRepositoryTest.php → app/Market/Data/Candle.php
- `{closure#12}()` --references--> `Candle`  [EXTRACTED]
  tests/Unit/Market/CandleAggregatorTest.php → app/Market/Data/Candle.php

## Import Cycles
- None detected.

## Communities (123 total, 25 thin omitted)

### Community 0 - ".of"
Cohesion: 0.08
Nodes (69): RejectionReason, BelowMinimum, DuplicateIntent, GrossExposure, Halted, InvalidStop, InvalidTarget, LiquidationTooClose (+61 more)

### Community 1 - "EngineTest.php"
Cohesion: 0.15
Nodes (25): ScriptedStrategy, {closure#10}(), {closure#13}(), {closure#14}(), {closure#16}(), {closure#17}(), {closure#2}(), {closure#20}() (+17 more)

### Community 2 - "App\Support\Number\Decimal"
Cohesion: 0.07
Nodes (17): {closure#10}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}(), {closure#8}() (+9 more)

### Community 3 - "Instrument"
Cohesion: 0.08
Nodes (10): BackfillStep, CandleRepository, CandleSource, Archive, Rest, Stream, UpsertResult, Instrument (+2 more)

### Community 4 - "SimulatedVenue"
Cohesion: 0.10
Nodes (16): Liquidity, Maker, Taker, OrderStatus, Cancelled, Expired, Filled, New (+8 more)

### Community 5 - ".parse"
Cohesion: 0.10
Nodes (35): {closure#2}(), {closure#4}(), carryDecisions(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}() (+27 more)

### Community 6 - "MarketType"
Cohesion: 0.05
Nodes (30): {closure#2}(), BinanceRequestFailed, BinanceRestClient, EpochTime, ExchangeNumber, Json, LocationRestricted, RateLimited (+22 more)

### Community 7 - "Position"
Cohesion: 0.10
Nodes (13): Argument, BoolLiteral, Call, DurationLiteral, Identifier, NumberLiteral, Parameter, PercentLiteral (+5 more)

### Community 8 - ".call"
Cohesion: 0.18
Nodes (3): {closure#34}(), Compiler, Program

### Community 9 - "SimulatedVenueTest.php"
Cohesion: 0.20
Nodes (39): btc(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#15}(), {closure#16}(), {closure#17}() (+31 more)

### Community 10 - ".assessEntry"
Cohesion: 0.21
Nodes (3): AccountView, Verdict, RiskEngine

### Community 11 - "SimulatedPerpVenueTest.php"
Cohesion: 0.20
Nodes (21): {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#15}(), {closure#18}(), {closure#19}() (+13 more)

### Community 12 - "Candle"
Cohesion: 0.15
Nodes (29): {closure#1}(), {closure#2}(), Candle, {closure#1}(), {closure#2}(), candleWith(), {closure#1}(), {closure#10}() (+21 more)

### Community 13 - "ExchangeVerificationTest.php"
Cohesion: 0.09
Nodes (30): VerificationSource, DailyArchive, Rest, {closure#12}(), {closure#13}(), {closure#2}(), {closure#3}(), {closure#4}() (+22 more)

### Community 14 - "IndicatorsTest.php"
Cohesion: 0.09
Nodes (20): Atr, Donchian, Ema, Period, Sma, bar(), {closure#1}(), {closure#10}() (+12 more)

### Community 15 - "ForwardingVenue"
Cohesion: 0.12
Nodes (5): Balance, ForwardingVenue, balances(), {closure#22}(), OverReportingVenue

### Community 16 - "Node"
Cohesion: 0.09
Nodes (20): Lexer, Token, TokenKind, Colon, Comma, Dot, Duration, End (+12 more)

### Community 17 - ".of"
Cohesion: 0.11
Nodes (21): Fill, AssetMismatch, Money, {closure#2}(), {closure#40}(), {closure#1}(), usdt(), {closure#11}() (+13 more)

### Community 18 - "App\Strategy\Dsl\Position"
Cohesion: 0.14
Nodes (11): DslError, {closure#4}(), YamlList, YamlReader, YamlScalar, YamlValue, {closure#1}(), {closure#2}() (+3 more)

### Community 19 - "CandleStreamTest.php"
Cohesion: 0.07
Nodes (18): BinanceArchive, BinanceEndpoints, Sleeper, SystemSleeper, {closure#1}(), CandleStream, StreamTransport, AppServiceProvider (+10 more)

### Community 20 - "LedgerTest.php"
Cohesion: 0.21
Nodes (29): Side, Buy, Sell, balanceOf(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}() (+21 more)

### Community 21 - "Illuminate\Database\Schema\Blueprint"
Cohesion: 0.10
Nodes (15): {closure#1}(), {closure#2}(), {closure#3}(), {closure#1}(), {closure#2}(), {closure#1}(), {closure#2}(), {closure#3}() (+7 more)

### Community 22 - "generate.py"
Cohesion: 0.09
Nodes (28): atr(), bars(), bb(), change(), dmi(), donchian(), ema(), highest() (+20 more)

### Community 23 - "Price"
Cohesion: 0.06
Nodes (26): Venue, ClientOrderId, Income, IncomeKind, Funding, Liquidation, OcoRequest, OrderAck (+18 more)

### Community 24 - "StrategyContext"
Cohesion: 0.06
Nodes (16): BuyAndHold, DonchianBreakout, MovingAverageTrend, BuildableStrategy, Strategy, Close, OpenLong, OpenShort (+8 more)

### Community 25 - "Unit"
Cohesion: 0.09
Nodes (18): {closure#1}(), {closure#2}(), ParamKind, Anchor, Count, Duration, Number, Percent (+10 more)

### Community 26 - "ExitReason"
Cohesion: 0.16
Nodes (23): ExitReason, Flatten, Liquidation, Signal, Stop, Target, {closure#1}(), {closure#10}() (+15 more)

### Community 27 - ".postfix"
Cohesion: 0.29
Nodes (3): Lookback, OnTimeframe, {closure#7}()

### Community 28 - "OrderManagerTest.php"
Cohesion: 0.10
Nodes (32): OrderRole, Entry, Exit, Flatten, Stop, Target, ProtectionPlan, TrackedOrder (+24 more)

### Community 29 - ".measure"
Cohesion: 0.12
Nodes (14): BacktestResult, ParticipantResult, EquityTracker, Performance, {closure#1}(), {closure#3}(), {closure#4}(), {closure#1}() (+6 more)

### Community 30 - "EnginePerpTest.php"
Cohesion: 0.14
Nodes (18): DecisionLog, DecisionRecord, InMemoryDecisionLog, {closure#10}(), {closure#11}(), {closure#12}(), {closure#14}(), {closure#2}() (+10 more)

### Community 31 - "Engine.php"
Cohesion: 0.12
Nodes (7): EngineMode, Backtest, Live, Paper, Testnet, {closure#6}(), MoveStop

### Community 32 - "ValidationStatisticsTest.php"
Cohesion: 0.16
Nodes (17): Cscv, closeTo(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#13}(), {closure#14}(), {closure#15}() (+9 more)

### Community 33 - "DecimalMathTest.php"
Cohesion: 0.07
Nodes (43): {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#2}(), {closure#3}(), {closure#41}(), {closure#44}() (+35 more)

### Community 34 - "Engine"
Cohesion: 0.12
Nodes (6): EngineRecord, IntentRecord, Engine, ReconciliationMismatch, Intent, {closure#1}()

### Community 35 - "App\Strategy\Dsl\Document\DocumentChecker"
Cohesion: 0.12
Nodes (22): CheckResult, {closure#1}(), {closure#7}(), {closure#8}(), PolicyLimits, checkDocument(), {closure#1}(), {closure#2}() (+14 more)

### Community 36 - "Timeframe"
Cohesion: 0.16
Nodes (23): CandleAggregator, {closure#8}(), Timeframe, FifteenMinutes, FiveMinutes, FourHours, OneDay, OneHour (+15 more)

### Community 37 - "SimClock"
Cohesion: 0.06
Nodes (33): SimClock, WallClock, {closure#11}(), {closure#13}(), {closure#14}(), {closure#15}(), {closure#16}(), {closure#4}() (+25 more)

### Community 38 - ".zero"
Cohesion: 0.09
Nodes (16): {closure#2}(), DeflatedSharpe, Normal, DecimalMath, {closure#3}(), {closure#1}(), {closure#3}(), goldenMinutes() (+8 more)

### Community 39 - "BacktestTest.php"
Cohesion: 0.21
Nodes (17): FeeSchedule, CostModel, Backtest, {closure#1}(), {closure#2}(), Participant, RunDefinition, {closure#5}() (+9 more)

### Community 40 - "Decimal"
Cohesion: 0.06
Nodes (16): SimulatedFuturesBook, UpdateStream, {closure#1}(), {closure#2}(), PerpPosition, {closure#1}(), {closure#2}(), {closure#3}() (+8 more)

### Community 41 - "ema-cross-scalp-long.strategy.yaml"
Cohesion: 0.06
Nodes (33): entries, long, exits, stop_loss, take_profit, time_stop, when, fails_when (+25 more)

### Community 42 - "RiskPolicy"
Cohesion: 0.16
Nodes (10): RiskProfile, Account, Benchmark, RiskPolicy, {closure#1}(), {closure#2}(), {closure#3}(), documentedLimits() (+2 more)

### Community 43 - "DocumentChecker.php"
Cohesion: 0.10
Nodes (21): {closure#3}(), {closure#4}(), ParameterSpec, DslWriter, Library, Scope, {closure#1}(), TypeChecker (+13 more)

### Community 44 - "sources"
Cohesion: 0.09
Nodes (22): secret, type, sources, auth, channels, connector, exchange, id (+14 more)

### Community 46 - "GridBacktest.php"
Cohesion: 0.12
Nodes (11): {closure#10}(), {closure#2}(), {closure#3}(), {closure#5}(), {closure#6}(), {closure#8}(), {closure#9}(), {closure#1}() (+3 more)

### Community 47 - "package.json"
Cohesion: 0.13
Nodes (20): devDependencies, concurrently@^10.0.3, laravel-vite-plugin@^3.1, tailwindcss@^4.0.0, @tailwindcss/vite@^4.0.0, vite@^8.0.0, optionalDependencies, @laravel/multiplex@^0.4.1 (+12 more)

### Community 48 - "App\Support\Timeframe"
Cohesion: 0.08
Nodes (13): InstrumentDirectory, StoredInstruments, {closure#1}(), CompileStrategy, {closure#1}(), {closure#2}(), {closure#3}(), {closure#5}() (+5 more)

### Community 49 - "BinanceRestClientTest.php"
Cohesion: 0.13
Nodes (16): {closure#9}(), binanceClient(), {closure#1}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#2}(), {closure#3}() (+8 more)

### Community 50 - "ema-cross-scalp-short.strategy.yaml"
Cohesion: 0.06
Nodes (33): entries, short, exits, stop_loss, take_profit, time_stop, when, fails_when (+25 more)

### Community 52 - "TokensTest.php"
Cohesion: 0.31
Nodes (7): {closure#3}(), {closure#4}(), {closure#5}(), contrastRatio(), contrastRules(), relativeLuminance(), themeTokens()

### Community 53 - "PhrityTransport"
Cohesion: 0.08
Nodes (5): ArchiveMissing, ChecksumMismatch, PhrityTransport, StreamFailed, FakeTransport

### Community 54 - "postgres"
Cohesion: 0.10
Nodes (19): POSTGRES_DB, POSTGRES_PASSWORD, POSTGRES_USER, interval, retries, test, timeout, environment (+11 more)

### Community 55 - "Symbol"
Cohesion: 0.09
Nodes (15): {closure#7}(), RulesHistory, {closure#1}(), {closure#1}(), {closure#1}(), Symbol, {closure#1}(), {closure#3}() (+7 more)

### Community 58 - ".sqrt"
Cohesion: 0.21
Nodes (10): Statistics, Parameters, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}() (+2 more)

### Community 59 - "GlobalsTest.php"
Cohesion: 0.22
Nodes (4): Controller, {closure#3}(), {closure#4}(), {closure#5}()

### Community 60 - "RunBacktestCommandTest.php"
Cohesion: 0.11
Nodes (26): BacktestRun, CanonicalJson, backtestFixture(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}() (+18 more)

### Community 61 - "Asset"
Cohesion: 0.14
Nodes (5): Marks, RoundTrip, Asset, {closure#25}(), {closure#9}()

### Community 62 - "Carbon\CarbonImmutable"
Cohesion: 0.17
Nodes (11): ArchivePeriod, archiveBytes(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}() (+3 more)

### Community 63 - "Carbon\CarbonImmutable"
Cohesion: 0.07
Nodes (27): {closure#1}(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), CandleFeed, FundingRate, MinuteCandles (+19 more)

### Community 64 - ".run"
Cohesion: 0.20
Nodes (15): Report, btcMinutes(), {closure#1}(), {closure#2}(), referencePrice(), {closure#2}(), {closure#3}(), {closure#4}() (+7 more)

### Community 66 - "IndicatorReferenceTest.php"
Cohesion: 0.08
Nodes (26): {closure#35}(), Change, DonchianChannel, Highest, Hma, Lowest, Macd, RealizedVolatility (+18 more)

### Community 67 - "Ledger"
Cohesion: 0.26
Nodes (5): Ledger, LedgerViolation, {closure#19}(), {closure#12}(), {closure#14}()

### Community 68 - "CandleRepositoryTest.php"
Cohesion: 0.28
Nodes (11): candleStore(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}() (+3 more)

### Community 69 - "App\Market\Models\Instrument"
Cohesion: 0.25
Nodes (3): {closure#1}(), InstrumentFilter, StrategyTrial

### Community 71 - "Clock"
Cohesion: 0.08
Nodes (15): Backfill, {closure#3}(), BackfillReport, BackfillMarketData, CheckMarketData, SelectsInstruments, StreamCandles, VerifyMarketData (+7 more)

### Community 72 - "VenuePosition"
Cohesion: 0.31
Nodes (4): VenuePosition, {closure#1}(), SimulatedPosition, {closure#13}()

### Community 73 - "scripts"
Cohesion: 0.14
Nodes (14): scripts, analyse, dev, format, mutate, post-autoload-dump, post-update-cmd, pre-package-uninstall (+6 more)

### Community 74 - ".parse"
Cohesion: 0.26
Nodes (6): KlineMessage, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), streamLines()

### Community 75 - "Group"
Cohesion: 0.14
Nodes (10): FunctionSpec, Group, Agent, Exit, Indicator, Order, Perpetual, Position (+2 more)

### Community 76 - "Expression"
Cohesion: 0.28
Nodes (4): {closure#9}(), EntrySpec, ExitSpec, Expression

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
Cohesion: 0.07
Nodes (23): {closure#1}(), PositionView, StrategyContext, CompiledStrategy, RenamedStrategy, {closure#18}(), {closure#37}(), {closure#24}() (+15 more)

### Community 83 - "App\Support\Number\Decimal"
Cohesion: 0.07
Nodes (36): {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#15}(), {closure#16}(), {closure#17}() (+28 more)

### Community 84 - "pint/composer.json"
Cohesion: 0.20
Nodes (9): config, sort-packages, description, license, name, require, laravel/pint, php (+1 more)

### Community 86 - "Quantity"
Cohesion: 0.24
Nodes (3): Quantity, {closure#10}(), {closure#9}()

### Community 87 - "bootstrap/app.php"
Cohesion: 0.33
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

### Community 88 - "require-dev"
Cohesion: 0.22
Nodes (9): require-dev, fakerphp/faker, larastan/larastan, laravel/pail, laravel/pao, mockery/mockery, nunomaduro/collision, pestphp/pest (+1 more)

### Community 89 - "scripts-descriptions"
Cohesion: 0.22
Nodes (9): scripts-descriptions, analyse, format, mutate, test, test:llm, test:network, test:testnet (+1 more)

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

### Community 119 - "Parser"
Cohesion: 0.29
Nodes (10): Binary, Node, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}() (+2 more)

### Community 121 - "CompiledStrategyTest.php"
Cohesion: 0.11
Nodes (9): {closure#37}(), BarIndicator, DirectionalMovement, Keltner, Natr, Supertrend, {closure#9}(), {closure#8}() (+1 more)

### Community 124 - ".seconds"
Cohesion: 0.18
Nodes (6): {closure#4}(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}()

### Community 128 - ".push"
Cohesion: 0.21
Nodes (4): InvalidCandle, CandleSequenceBroken, {closure#6}(), {closure#2}()

### Community 130 - "ParserTest.php"
Cohesion: 0.36
Nodes (6): AstPrinter, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), sexpr()

### Community 133 - ".fromStrings"
Cohesion: 0.33
Nodes (3): {closure#1}(), {closure#1}(), {closure#42}()

## Knowledge Gaps
- **117 isolated node(s):** `DailyLossHalt`, `DrawdownFlatten`, `DrawdownHalt`, `WeeklyLossHalt`, `BelowMinimum` (+112 more)
  These have ≤1 connection - possible missing edges. (Counts symbols only; 572 node(s) total have ≤1 connection when file, concept and rationale nodes are included.)
- **25 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `Decimal` connect `Decimal` to `.of`, `FundingCarry`, `App\Support\Number\Decimal`, `Position`, `SimulatedVenue`, `.parse`, `MarketType`, `Position`, `EngineTest.php`, `SimulatedVenueTest.php`, `.assessEntry`, `SimulatedPerpVenueTest.php`, `Candle`, `IndicatorsTest.php`, `ForwardingVenue`, `.of`, `LedgerTest.php`, `Price`, `StrategyContext`, `ExitReason`, `OrderManagerTest.php`, `.measure`, `EnginePerpTest.php`, `Engine.php`, `DecimalMathTest.php`, `Engine`, `Timeframe`, `SimClock`, `.zero`, `BacktestTest.php`, `RiskPolicy`, `GridBacktest.php`, `App\Support\Timeframe`, `Symbol`, `App\Strategy\Dsl\Document\DocumentChecker`, `.sqrt`, `RunBacktestCommandTest.php`, `Asset`, `Carbon\CarbonImmutable`, `.run`, `QualityCheck`, `Ledger`, `App\Market\Models\Instrument`, `Clock`, `VenuePosition`, `CompiledStrategyTest.php`, `App\Support\Number\Decimal`, `Quantity`, `Parser`, `CompiledStrategyTest.php`, `PerformanceMetrics`?**
  _High betweenness centrality (0.152) - this node is a cross-community bridge._
- **What connects `DailyLossHalt`, `DrawdownFlatten`, `DrawdownHalt` to the rest of the system?**
  _117 weakly-connected nodes found - possible missing edges._
- **Should `.of` be split into smaller, more focused modules?**
  _Cohesion score 0.0840504876649455 - nodes in this community are weakly interconnected._
- **Why does `Symbol` connect `Symbol` to `.of`, `FundingCarry`, `App\Support\Number\Decimal`, `Instrument`, `SimulatedVenue`, `Position`, `MarketType`, `.parse`, `EngineTest.php`, `SimulatedVenueTest.php`, `.assessEntry`, `SimulatedPerpVenueTest.php`, `ExchangeVerificationTest.php`, `ForwardingVenue`, `.of`, `CandleStreamTest.php`, `LedgerTest.php`, `Price`, `StrategyContext`, `ExitReason`, `OrderManagerTest.php`, `.measure`, `EnginePerpTest.php`, `Engine.php`, `Engine`, `Timeframe`, `SimClock`, `.zero`, `BacktestTest.php`, `Decimal`, `App\Support\Timeframe`, `.sqrt`, `RunBacktestCommandTest.php`, `Asset`, `Carbon\CarbonImmutable`, `.run`, `Ledger`, `CandleRepositoryTest.php`, `App\Market\Models\Instrument`, `Clock`, `VenuePosition`, `CompiledStrategyTest.php`?**
  _High betweenness centrality (0.051) - this node is a cross-community bridge._
- **Should `App\Support\Number\Decimal` be split into smaller, more focused modules?**
  _Cohesion score 0.07030527289546716 - nodes in this community are weakly interconnected._
- **Why does `MarketType` connect `MarketType` to `.run`, `CandleRepositoryTest.php`, `App\Market\Models\Instrument`, `SimClock`, `Clock`, `BacktestTest.php`, `YamlMap`, `.zero`, `ExchangeVerificationTest.php`, `App\Support\Timeframe`, `BinanceRestClientTest.php`, `CandleStreamTest.php`, `Symbol`, `PreparesBacktests`, `RunBacktestCommandTest.php`, `Carbon\CarbonImmutable`, `Carbon\CarbonImmutable`?**
  _High betweenness centrality (0.034) - this node is a cross-community bridge._
- **Should `Instrument` be split into smaller, more focused modules?**
  _Cohesion score 0.0782608695652174 - nodes in this community are weakly interconnected._