# Graph Report - trading-platform  (2026-11-08)

## Corpus Check
- 430 files · ~163,386 words
- Verdict: corpus is large enough that graph structure adds value.
- Unclassified: 92 file(s) not represented in the graph (top: .zip 34, .CHECKSUM 34, (none) 18)

## Summary
- 3399 nodes · 12352 edges · 122 communities (99 shown, 23 thin omitted)
- Extraction: 90% EXTRACTED · 10% INFERRED · 0% AMBIGUOUS · INFERRED: 1295 edges (avg confidence: 0.85)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `1d5410cf`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- .of
- EngineTest.php
- App\Support\Number\Decimal
- Instrument
- .zero
- .parse
- DatabaseCandleFeed
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
- Money
- App\Strategy\Dsl\Position
- App\Market\Stream\PhrityTransport
- Side
- Illuminate\Database\Schema\Blueprint
- generate.py
- ForwardingVenue
- Symbol
- App\Strategy\Dsl\TypeChecker
- StrategyIdentity
- Normal
- .fromStrings
- .plus
- EnginePerpTest.php
- App\Market\Data\InstrumentRules
- ValidationStatisticsTest.php
- .of
- OrderManagerTest.php
- .expect
- Timeframe
- SimClock
- .times
- BacktestTest.php
- DonchianBreakout
- ema-cross-scalp-long.strategy.yaml
- RiskPolicy
- autoload-dev
- User
- package.json
- StrategyLibrary
- BinanceRestClientTest.php
- ema-cross-scalp-short.strategy.yaml
- .handle
- TokensTest.php
- Laravel\Mcp\Response
- postgres
- InstrumentRules
- App\Support\Clock\Clock
- .measure
- GlobalsTest.php
- RunBacktestCommandTest.php
- BinanceArchive
- CandleFeedTest.php
- .run
- ArchiveImportTest.php
- IndicatorReferenceTest.php
- SimClock
- App\Strategy\Dsl\Document\DocumentChecker
- InvalidArgumentException
- .count
- scripts
- BinanceRestClient
- App\Simulation\Backtest
- .walk
- composer.json
- jobs
- postgres
- .build
- Window
- CompiledStrategyTest.php
- App\Support\Number\Decimal
- pint/composer.json
- .count
- RunSimulation.php
- require-dev
- scripts-descriptions
- .run
- .evaluate
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
- PreparesBacktests.php
- CompiledStrategyTest.php
- Carbon\CarbonImmutable
- App\Strategy\Dsl\Compiler\CompiledStrategy
- MarketType
- .__construct
- McpServerTest.php
- ParserTest.php
- Decimal
- FundingCarry
- OrderRequest

## God Nodes (most connected - your core abstractions)
1. `Decimal` - 295 edges
2. `Side` - 182 edges
3. `Symbol` - 169 edges
4. `Candle` - 103 edges
5. `Timeframe` - 99 edges
6. `Price` - 81 edges
7. `Position` - 68 edges
8. `MarketType` - 66 edges
9. `Ledger` - 56 edges
10. `SimulatedVenue` - 56 edges

## Surprising Connections (you probably didn't know these)
- `{closure#4}()` --references--> `Candle`  [EXTRACTED]
  tests/Feature/Market/BinanceRestClientTest.php → app/Market/Data/Candle.php
- `{closure#7}()` --references--> `Candle`  [EXTRACTED]
  tests/Feature/Market/CandleRepositoryTest.php → app/Market/Data/Candle.php
- `{closure#12}()` --references--> `Candle`  [EXTRACTED]
  tests/Unit/Market/CandleAggregatorTest.php → app/Market/Data/Candle.php
- `{closure#13}()` --references--> `Candle`  [EXTRACTED]
  tests/Unit/Market/CandleAggregatorTest.php → app/Market/Data/Candle.php
- `runIndicator()` --calls--> `Atr`  [INFERRED]
  tests/Unit/Strategy/IndicatorReferenceTest.php → app/Strategy/Indicators/Atr.php

## Import Cycles
- None detected.

## Communities (122 total, 23 thin omitted)

### Community 0 - ".of"
Cohesion: 0.06
Nodes (75): AccountView, RejectionReason, BelowMinimum, Cooldown, DuplicateIntent, EntryWorking, GrossExposure, Halted (+67 more)

### Community 1 - "EngineTest.php"
Cohesion: 0.08
Nodes (37): {closure#1}(), RenamedStrategy, ScriptedStrategy, {closure#1}(), {closure#10}(), {closure#13}(), {closure#14}(), {closure#16}() (+29 more)

### Community 2 - "App\Support\Number\Decimal"
Cohesion: 0.05
Nodes (33): {closure#1}(), {closure#10}(), {closure#11}(), {closure#14}(), {closure#15}(), {closure#17}(), {closure#18}(), {closure#19}() (+25 more)

### Community 3 - "Instrument"
Cohesion: 0.07
Nodes (19): Backfill, {closure#3}(), BackfillStep, BinanceEndpoints, Sleeper, CandleRepository, {closure#2}(), {closure#4}() (+11 more)

### Community 4 - ".zero"
Cohesion: 0.08
Nodes (22): ClientOrderId, Liquidity, Maker, Taker, OrderAck, OrderStatus, Cancelled, Expired (+14 more)

### Community 5 - ".parse"
Cohesion: 0.11
Nodes (34): carryDecisions(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#15}(), {closure#16}() (+26 more)

### Community 6 - "DatabaseCandleFeed"
Cohesion: 0.26
Nodes (5): DatabaseCandleFeed, {closure#1}(), Rows, {closure#10}(), {closure#11}()

### Community 7 - "Position"
Cohesion: 0.07
Nodes (25): Argument, BoolLiteral, Call, DurationLiteral, Identifier, Lookback, Node, NumberLiteral (+17 more)

### Community 8 - "CompiledStrategy.php"
Cohesion: 0.10
Nodes (9): {closure#1}(), {closure#2}(), {closure#3}(), CompiledStrategy, {closure#34}(), Compiler, Program, {closure#2}() (+1 more)

### Community 9 - "SimulatedVenueTest.php"
Cohesion: 0.18
Nodes (46): OcoRequest, Side, Buy, Sell, btc(), {closure#1}(), {closure#10}(), {closure#11}() (+38 more)

### Community 10 - "App\Support\Symbol"
Cohesion: 0.13
Nodes (8): {closure#6}(), {closure#7}(), {closure#2}(), {closure#1}(), EntryOrder, Intent, OpenLong, OpenShort

### Community 11 - "SimulatedPerpVenueTest.php"
Cohesion: 0.19
Nodes (24): {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#15}(), {closure#18}(), {closure#19}() (+16 more)

### Community 12 - "Candle"
Cohesion: 0.14
Nodes (31): {closure#1}(), {closure#2}(), {closure#1}(), {closure#3}(), Candle, {closure#1}(), {closure#2}(), candleWith() (+23 more)

### Community 13 - "ExchangeVerificationTest.php"
Cohesion: 0.11
Nodes (26): {closure#1}(), {closure#12}(), {closure#13}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}() (+18 more)

### Community 14 - "IndicatorsTest.php"
Cohesion: 0.09
Nodes (20): Atr, Donchian, Ema, Period, Sma, bar(), {closure#1}(), {closure#10}() (+12 more)

### Community 15 - "AppServiceProvider.php"
Cohesion: 0.09
Nodes (10): SystemSleeper, AppServiceProvider, {closure#2}(), {closure#3}(), {closure#4}(), {closure#6}(), SimulationQueue, {closure#1}() (+2 more)

### Community 16 - "Node"
Cohesion: 0.13
Nodes (21): Binary, Parser, Token, TokenKind, Colon, Comma, Dot, Duration (+13 more)

### Community 17 - "Money"
Cohesion: 0.19
Nodes (5): AssetMismatch, Money, {closure#2}(), {closure#1}(), usdt()

### Community 18 - "App\Strategy\Dsl\Position"
Cohesion: 0.11
Nodes (14): InstrumentDirectory, {closure#5}(), DocumentChecker, DslError, YamlList, YamlMap, YamlReader, YamlScalar (+6 more)

### Community 19 - "App\Market\Stream\PhrityTransport"
Cohesion: 0.08
Nodes (11): PhrityTransport, StreamFailed, StreamTransport, {closure#1}(), {closure#2}(), {closure#4}(), {closure#5}(), streamMessages() (+3 more)

### Community 20 - "Side"
Cohesion: 0.06
Nodes (71): ExitReason, Flatten, Liquidation, Signal, Stop, Target, Marks, Ledger (+63 more)

### Community 21 - "Illuminate\Database\Schema\Blueprint"
Cohesion: 0.07
Nodes (20): {closure#1}(), {closure#2}(), {closure#3}(), {closure#1}(), {closure#2}(), {closure#1}(), {closure#2}(), {closure#3}() (+12 more)

### Community 22 - "generate.py"
Cohesion: 0.09
Nodes (28): atr(), bars(), bb(), change(), dmi(), donchian(), ema(), highest() (+20 more)

### Community 24 - "Symbol"
Cohesion: 0.09
Nodes (10): {closure#2}(), BuildableStrategy, Strategy, Close, StrategyContext, Subscription, EnterOnceStrategy, HoldStrategy (+2 more)

### Community 25 - "App\Strategy\Dsl\TypeChecker"
Cohesion: 0.05
Nodes (30): FunctionSpec, Group, Agent, Exit, Indicator, Order, Perpetual, Position (+22 more)

### Community 26 - "StrategyIdentity"
Cohesion: 0.19
Nodes (5): BuyAndHold, StrategyIdentity, {closure#1}(), {closure#2}(), {closure#3}()

### Community 29 - ".plus"
Cohesion: 0.12
Nodes (7): Fill, PerpPosition, Position, RoundTrip, {closure#1}(), {closure#2}(), {closure#4}()

### Community 30 - "EnginePerpTest.php"
Cohesion: 0.21
Nodes (17): Policies, {closure#1}(), {closure#10}(), {closure#11}(), {closure#13}(), {closure#14}(), {closure#2}(), {closure#5}() (+9 more)

### Community 31 - "App\Market\Data\InstrumentRules"
Cohesion: 0.14
Nodes (4): Income, SimulatedFuturesBook, UpdateStream, {closure#5}()

### Community 32 - "ValidationStatisticsTest.php"
Cohesion: 0.15
Nodes (17): Cscv, DeflatedSharpe, closeTo(), {closure#1}(), {closure#11}(), {closure#13}(), {closure#14}(), {closure#15}() (+9 more)

### Community 33 - ".of"
Cohesion: 0.06
Nodes (53): {closure#3}(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#2}(), {closure#3}(), {closure#1}() (+45 more)

### Community 34 - "OrderManagerTest.php"
Cohesion: 0.05
Nodes (40): DecisionLog, Venue, EngineRecord, IntentRecord, OrderRole, Entry, Exit, Flatten (+32 more)

### Community 35 - ".expect"
Cohesion: 0.35
Nodes (10): checkDocument(), {closure#2}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}(), {closure#8}(), {closure#9}() (+2 more)

### Community 36 - "Timeframe"
Cohesion: 0.12
Nodes (16): {closure#8}(), Timeframe, FifteenMinutes, FiveMinutes, FourHours, OneDay, OneHour, OneMinute (+8 more)

### Community 37 - "SimClock"
Cohesion: 0.10
Nodes (21): {closure#11}(), {closure#13}(), {closure#14}(), {closure#15}(), {closure#16}(), {closure#4}(), {closure#5}(), {closure#6}() (+13 more)

### Community 38 - ".times"
Cohesion: 0.12
Nodes (4): DecimalMath, InvalidNumber, {closure#11}(), {closure#12}()

### Community 39 - "BacktestTest.php"
Cohesion: 0.18
Nodes (15): FeeSchedule, CostModel, CandleFeed, Backtest, {closure#1}(), RunDefinition, InMemoryCandleFeed, {closure#23}() (+7 more)

### Community 40 - "DonchianBreakout"
Cohesion: 0.13
Nodes (3): DonchianBreakout, MovingAverageTrend, Parameters

### Community 41 - "ema-cross-scalp-long.strategy.yaml"
Cohesion: 0.06
Nodes (33): entries, long, exits, stop_loss, take_profit, time_stop, when, fails_when (+25 more)

### Community 42 - "RiskPolicy"
Cohesion: 0.21
Nodes (6): RiskProfile, Account, Benchmark, RiskPolicy, {closure#2}(), {closure#1}()

### Community 43 - "autoload-dev"
Cohesion: 0.67
Nodes (3): autoload-dev, psr-4, Tests\\

### Community 45 - "User"
Cohesion: 0.10
Nodes (3): User, UserFactory, {closure#1}()

### Community 47 - "package.json"
Cohesion: 0.13
Nodes (20): devDependencies, concurrently@^10.0.3, laravel-vite-plugin@^3.1, tailwindcss@^4.0.0, @tailwindcss/vite@^4.0.0, vite@^8.0.0, optionalDependencies, @laravel/multiplex@^0.4.1 (+12 more)

### Community 48 - "StrategyLibrary"
Cohesion: 0.08
Nodes (16): {closure#1}(), CompileStrategy, {closure#1}(), StrategyLibrary, {closure#1}(), SaveResult, StrategyRepository, StrategyVersion (+8 more)

### Community 49 - "BinanceRestClientTest.php"
Cohesion: 0.21
Nodes (11): binanceClient(), {closure#1}(), {closure#11}(), {closure#13}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}() (+3 more)

### Community 50 - "ema-cross-scalp-short.strategy.yaml"
Cohesion: 0.06
Nodes (33): entries, short, exits, stop_loss, take_profit, time_stop, when, fails_when (+25 more)

### Community 51 - ".handle"
Cohesion: 0.30
Nodes (6): CanonicalJson, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}()

### Community 52 - "TokensTest.php"
Cohesion: 0.31
Nodes (7): {closure#3}(), {closure#4}(), {closure#5}(), contrastRatio(), contrastRules(), relativeLuminance(), themeTokens()

### Community 53 - "Laravel\Mcp\Response"
Cohesion: 0.07
Nodes (17): ChartTool, {closure#2}(), DslReference, GetCandles, GetFunding, GetIndicators, GetJob, GetStrategy (+9 more)

### Community 54 - "postgres"
Cohesion: 0.10
Nodes (19): POSTGRES_DB, POSTGRES_PASSWORD, POSTGRES_USER, interval, retries, test, timeout, environment (+11 more)

### Community 55 - "InstrumentRules"
Cohesion: 0.19
Nodes (14): InstrumentRules, {closure#1}(), RulesHistory, {closure#2}(), {closure#3}(), {closure#5}(), {closure#4}(), {closure#41}() (+6 more)

### Community 56 - "App\Support\Clock\Clock"
Cohesion: 0.08
Nodes (11): AuthenticateMcpToken, StoredInstruments, {closure#1}(), {closure#2}(), {closure#1}(), CompileStrategy, ExportStrategies, ImportStrategies (+3 more)

### Community 58 - ".measure"
Cohesion: 0.19
Nodes (9): EquityTracker, Performance, {closure#1}(), {closure#2}(), {closure#3}(), {closure#5}(), {closure#6}(), {closure#8}() (+1 more)

### Community 60 - "RunBacktestCommandTest.php"
Cohesion: 0.08
Nodes (29): {closure#1}(), McpCall, McpToken, {closure#1}(), InstrumentFilter, BacktestRun, StrategyTrial, StrategyStage (+21 more)

### Community 62 - "BinanceArchive"
Cohesion: 0.09
Nodes (5): ArchivePeriod, BinanceArchive, ExchangeVerification, VerificationReport, {closure#5}()

### Community 63 - "CandleFeedTest.php"
Cohesion: 0.25
Nodes (15): MinuteCandles, {closure#1}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#2}(), {closure#3}(), {closure#4}() (+7 more)

### Community 64 - ".run"
Cohesion: 0.14
Nodes (12): PerformanceMetrics, TradeStatistics, Report, {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}() (+4 more)

### Community 65 - "ArchiveImportTest.php"
Cohesion: 0.12
Nodes (15): ArchiveMissing, ChecksumMismatch, VerificationSource, DailyArchive, Rest, archiveBytes(), {closure#1}(), {closure#2}() (+7 more)

### Community 66 - "IndicatorReferenceTest.php"
Cohesion: 0.06
Nodes (22): {closure#35}(), Bollinger, Change, Highest, Hma, Lowest, Macd, RealizedVolatility (+14 more)

### Community 67 - "SimClock"
Cohesion: 0.09
Nodes (27): SimClock, WallClock, candleStore(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}() (+19 more)

### Community 68 - "App\Strategy\Dsl\Document\DocumentChecker"
Cohesion: 0.09
Nodes (29): {closure#3}(), {closure#4}(), {closure#7}(), {closure#8}(), {closure#9}(), ParameterSpec, DslWriter, Scope (+21 more)

### Community 70 - "InvalidArgumentException"
Cohesion: 0.15
Nodes (3): {closure#2}(), {closure#3}(), {closure#1}()

### Community 71 - ".count"
Cohesion: 0.08
Nodes (12): CreateMcpToken, RevokeMcpToken, BackfillReport, BackfillMarketData, CheckMarketData, SelectsInstruments, {closure#1}(), StreamCandles (+4 more)

### Community 73 - "scripts"
Cohesion: 0.15
Nodes (13): scripts, analyse, dev, format, mutate, post-autoload-dump, post-update-cmd, pre-package-uninstall (+5 more)

### Community 74 - "BinanceRestClient"
Cohesion: 0.09
Nodes (16): {closure#1}(), {closure#2}(), EpochTime, ExchangeNumber, Json, KlineMessage, {closure#1}(), {closure#2}() (+8 more)

### Community 75 - "App\Simulation\Backtest"
Cohesion: 0.16
Nodes (11): EngineMode, Backtest, Live, Paper, Testnet, InMemoryDecisionLog, {closure#1}(), {closure#2}() (+3 more)

### Community 76 - ".walk"
Cohesion: 0.18
Nodes (18): CandleAggregator, CandleSequenceBroken, {closure#6}(), CandleFactory, aggregate(), {closure#1}(), {closure#10}(), {closure#11}() (+10 more)

### Community 77 - "composer.json"
Cohesion: 0.17
Nodes (11): description, extra, laravel, keywords, dont-discover, license, minimum-stability, name (+3 more)

### Community 78 - "jobs"
Cohesion: 0.17
Nodes (11): concurrency, cancel-in-progress, group, jobs, name, on, pull_request, push (+3 more)

### Community 79 - "postgres"
Cohesion: 0.17
Nodes (12): POSTGRES_DB, POSTGRES_PASSWORD, POSTGRES_USER, env, image, options, ports, image (+4 more)

### Community 81 - "Window"
Cohesion: 0.11
Nodes (4): DonchianChannel, Stochastic, Vwma, Window

### Community 82 - "CompiledStrategyTest.php"
Cohesion: 0.16
Nodes (12): KnownInstruments, {closure#11}(), {closure#13}(), {closure#16}(), {closure#17}(), {closure#2}(), {closure#3}(), {closure#4}() (+4 more)

### Community 83 - "App\Support\Number\Decimal"
Cohesion: 0.07
Nodes (37): {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#15}(), {closure#16}(), {closure#17}() (+29 more)

### Community 84 - "pint/composer.json"
Cohesion: 0.20
Nodes (9): config, sort-packages, description, license, name, require, laravel/pint, php (+1 more)

### Community 85 - ".count"
Cohesion: 0.26
Nodes (9): Statistics, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}() (+1 more)

### Community 86 - "RunSimulation.php"
Cohesion: 0.10
Nodes (6): RunSimulation, SimulationJob, {closure#1}(), {closure#2}(), {closure#3}(), savedEmaCross()

### Community 88 - "require-dev"
Cohesion: 0.22
Nodes (9): require-dev, fakerphp/faker, larastan/larastan, laravel/pail, laravel/pao, mockery/mockery, nunomaduro/collision, pestphp/pest (+1 more)

### Community 89 - "scripts-descriptions"
Cohesion: 0.25
Nodes (8): scripts-descriptions, analyse, format, mutate, test, test:network, test:testnet, tools:install

### Community 90 - ".run"
Cohesion: 0.18
Nodes (13): btcMinutes(), {closure#1}(), {closure#2}(), {closure#3}(), referencePrice(), {closure#1}(), {closure#3}(), goldenMinutes() (+5 more)

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

### Community 97 - "run"
Cohesion: 0.33
Nodes (6): name, runs-on, steps, audit, run, steps

### Community 99 - "psr-4"
Cohesion: 0.40
Nodes (5): autoload, psr-4, App\\, Database\\Factories\\, Database\\Seeders\\

### Community 100 - "require"
Cohesion: 0.33
Nodes (6): require, laravel/framework, laravel/mcp, laravel/tinker, php, phrity/websocket

### Community 103 - "steps"
Cohesion: 0.50
Nodes (4): name, runs-on, steps, analyse

### Community 104 - "steps"
Cohesion: 0.50
Nodes (4): name, runs-on, steps, frontend

### Community 105 - "lint"
Cohesion: 0.50
Nodes (4): lint, name, runs-on, steps

### Community 120 - "PreparesBacktests.php"
Cohesion: 0.17
Nodes (4): GridBacktest, {closure#1}(), CheckResult, StrategySpec

### Community 121 - "CompiledStrategyTest.php"
Cohesion: 0.09
Nodes (17): {closure#37}(), BarIndicator, DirectionalMovement, Keltner, Natr, chart(), {closure#1}(), {closure#2}() (+9 more)

### Community 122 - "Carbon\CarbonImmutable"
Cohesion: 0.04
Nodes (29): DecisionRecord, IncomeKind, Funding, Liquidation, ProtectionPlan, {closure#4}(), {closure#1}(), FundingRate (+21 more)

### Community 123 - "App\Strategy\Dsl\Compiler\CompiledStrategy"
Cohesion: 0.14
Nodes (8): {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), EntrySpec, Expression

### Community 127 - "MarketType"
Cohesion: 0.07
Nodes (25): BinanceRequestFailed, BinanceRestClient, {closure#1}(), LocationRestricted, RateLimited, RequestFailed, SyncInstruments, InstrumentDefinition (+17 more)

### Community 129 - "McpServerTest.php"
Cohesion: 0.16
Nodes (13): TradingServer, actAsToken(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}() (+5 more)

### Community 130 - "ParserTest.php"
Cohesion: 0.18
Nodes (7): AstPrinter, Lexer, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), sexpr()

### Community 131 - "Decimal"
Cohesion: 0.07
Nodes (12): Balance, VenuePosition, {closure#1}(), SimulatedPosition, {closure#1}(), {closure#2}(), {closure#3}(), Decimal (+4 more)

### Community 133 - "OrderRequest"
Cohesion: 0.23
Nodes (6): OrderRequest, OrderType, Limit, Market, StopMarket, TakeProfitMarket

## Knowledge Gaps
- **121 isolated node(s):** `DailyLossHalt`, `DrawdownFlatten`, `DrawdownHalt`, `WeeklyLossHalt`, `Agent` (+116 more)
  These have ≤1 connection - possible missing edges. (Counts symbols only; 596 node(s) total have ≤1 connection when file, concept and rationale nodes are included.)
- **23 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `Decimal` connect `Decimal` to `.__construct`, `.of`, `App\Support\Number\Decimal`, `Instrument`, `.zero`, `OrderRequest`, `EngineTest.php`, `FundingCarry`, `Position`, `SimulatedVenueTest.php`, `.parse`, `Candle`, `IndicatorsTest.php`, `Money`, `Side`, `ForwardingVenue`, `Symbol`, `Normal`, `.plus`, `EnginePerpTest.php`, `App\Market\Data\InstrumentRules`, `.of`, `OrderManagerTest.php`, `.times`, `BacktestTest.php`, `DonchianBreakout`, `RiskPolicy`, `.handle`, `InstrumentRules`, `App\Support\Clock\Clock`, `.measure`, `RunBacktestCommandTest.php`, `BinanceArchive`, `CandleFeedTest.php`, `.run`, `SimClock`, `InvalidArgumentException`, `.count`, `App\Simulation\Backtest`, `.walk`, `.count`, `.run`, `CompiledStrategyTest.php`, `Carbon\CarbonImmutable`, `MarketType`?**
  _High betweenness centrality (0.118) - this node is a cross-community bridge._
- **What connects `DailyLossHalt`, `DrawdownFlatten`, `DrawdownHalt` to the rest of the system?**
  _121 weakly-connected nodes found - possible missing edges._
- **Should `.of` be split into smaller, more focused modules?**
  _Cohesion score 0.06346300533943555 - nodes in this community are weakly interconnected._
- **Why does `Symbol` connect `Carbon\CarbonImmutable` to `App\Support\Number\Decimal`, `Decimal`, `.zero`, `OrderRequest`, `Instrument`, `FundingCarry`, `.parse`, `SimulatedVenueTest.php`, `App\Support\Symbol`, `SimulatedPerpVenueTest.php`, `ExchangeVerificationTest.php`, `AppServiceProvider.php`, `App\Market\Stream\PhrityTransport`, `Side`, `ForwardingVenue`, `Symbol`, `StrategyIdentity`, `.plus`, `EnginePerpTest.php`, `App\Market\Data\InstrumentRules`, `OrderManagerTest.php`, `SimClock`, `BacktestTest.php`, `DonchianBreakout`, `.handle`, `InstrumentRules`, `.measure`, `RunBacktestCommandTest.php`, `CandleFeedTest.php`, `.run`, `SimClock`, `InvalidArgumentException`, `.count`, `App\Simulation\Backtest`, `CompiledStrategyTest.php`, `.run`, `PreparesBacktests.php`, `App\Strategy\Dsl\Compiler\CompiledStrategy`, `MarketType`?**
  _High betweenness centrality (0.031) - this node is a cross-community bridge._
- **Should `EngineTest.php` be split into smaller, more focused modules?**
  _Cohesion score 0.07757885763000852 - nodes in this community are weakly interconnected._
- **Why does `MarketType` connect `MarketType` to `Instrument`, `ExchangeVerificationTest.php`, `AppServiceProvider.php`, `App\Strategy\Dsl\Position`, `App\Market\Stream\PhrityTransport`, `SimClock`, `BacktestTest.php`, `BinanceRestClientTest.php`, `.handle`, `InstrumentRules`, `App\Support\Clock\Clock`, `RunBacktestCommandTest.php`, `BinanceArchive`, `CandleFeedTest.php`, `ArchiveImportTest.php`, `SimClock`, `InvalidArgumentException`, `.count`, `BinanceRestClient`, `.run`, `PreparesBacktests.php`?**
  _High betweenness centrality (0.029) - this node is a cross-community bridge._
- **Should `App\Support\Number\Decimal` be split into smaller, more focused modules?**
  _Cohesion score 0.04869215291750503 - nodes in this community are weakly interconnected._