# Graph Report - trading-platform  (2026-11-09)

## Corpus Check
- 461 files · ~198,063 words
- Verdict: corpus is large enough that graph structure adds value.
- Unclassified: 92 file(s) not represented in the graph (top: .zip 34, .CHECKSUM 34, (none) 18)

## Summary
- 3821 nodes · 13405 edges · 149 communities (118 shown, 31 thin omitted)
- Extraction: 89% EXTRACTED · 11% INFERRED · 0% AMBIGUOUS · INFERRED: 1422 edges (avg confidence: 0.85)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `e5298dce`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- .of
- EngineTest.php
- App\Support\Clock\Clock
- AppServiceProvider.php
- OrderStatus
- .parse
- Instrument
- Position
- CompiledStrategy.php
- SimulatedVenueTest.php
- .isPositive
- SimulatedPerpVenueTest.php
- Candle
- ExchangeVerificationTest.php
- IndicatorsTest.php
- AppServiceProvider.php
- Node
- StrategyVersionsTest.php
- App\Strategy\Dsl\Position
- MarketType
- LedgerTest.php
- Illuminate\Database\Schema\Blueprint
- generate.py
- ReadBack
- Symbol
- ParamKind
- RejectionReason
- App\Strategy\Dsl\Compiler\CompiledStrategy
- .measure
- Fill
- EnginePerpTest.php
- DslBaselinesTest.php
- ValidationStatisticsTest.php
- .of
- Carbon\CarbonImmutable
- ExitReason
- Timeframe
- SimClock
- PreparesBacktests.php
- BacktestTest.php
- Illuminate\Contracts\Config\Repository
- ema-cross-scalp-long.strategy.yaml
- App\Support\Number\Decimal
- autoload-dev
- OrderRole
- User
- Illuminate\Database\Eloquent\Model
- package.json
- DocumentCheckerTest.php
- BinanceRestClientTest.php
- ema-cross-scalp-short.strategy.yaml
- .evaluate
- TokensTest.php
- Laravel\Mcp\Response
- postgres
- InstrumentRules
- PreparesBacktests
- donchian-breakout-short.strategy.yaml
- .count
- ChartSeries
- RunBacktestCommandTest.php
- Ledger
- BinanceArchive
- CandleFeedTest.php
- App\Support\Symbol
- donchian-breakout.strategy.yaml
- .evaluate
- CandleRepositoryTest.php
- App\Strategy\Dsl\Document\DocumentChecker
- .of
- App\Agents\Mcp\TradingServer
- Clock
- SimulationJobsTest.php
- scripts
- ma-trend-short.strategy.yaml
- .parse
- .walk
- composer.json
- jobs
- postgres
- ma-trend.strategy.yaml
- IndicatorReferenceTest.php
- CompiledStrategyTest.php
- App\Support\Number\Decimal
- pint/composer.json
- MonteCarlo
- RunSimulation.php
- App\Support\Number\Decimal
- require-dev
- scripts-descriptions
- RiskState
- .fromStrings
- config
- uses
- test
- with
- .handle
- run
- psr-4
- require
- DatabaseSeeder
- steps
- steps
- lint
- OrderUpdate
- MarketBreakers
- buy-and-hold.strategy.yaml
- App\Market\Data\Candle
- .isGreaterThan
- .fromStrings
- bootstrap/app.php
- App\Strategy\Data\StrategyContext
- .handle
- MarketType
- funding-carry.strategy.yaml
- McpServerTest.php
- .parse
- Decimal
- Marks
- ClientOrderId
- App\Market\Archive\BinanceArchive
- .encode
- GridPart.php
- Stringable
- StrategyVersion
- Group
- .__construct
- GlobalsTest.php
- QualityCheck
- QualityReport

## God Nodes (most connected - your core abstractions)
1. `Decimal` - 281 edges
2. `Side` - 184 edges
3. `Symbol` - 167 edges
4. `Candle` - 101 edges
5. `Timeframe` - 98 edges
6. `Price` - 81 edges
7. `MarketType` - 66 edges
8. `Position` - 61 edges
9. `Ledger` - 56 edges
10. `SimulatedVenue` - 56 edges

## Surprising Connections (you probably didn't know these)
- `{closure#13}()` --calls--> `RiskState`  [INFERRED]
  tests/Unit/Risk/RiskEngineTest.php → app/Risk/RiskState.php
- `{closure#23}()` --calls--> `RiskState`  [INFERRED]
  tests/Unit/Risk/RiskEngineTest.php → app/Risk/RiskState.php
- `engineFor()` --calls--> `RiskState`  [INFERRED]
  tests/Unit/Risk/RiskEngineTest.php → app/Risk/RiskState.php
- `{closure#4}()` --calls--> `Participant`  [INFERRED]
  tests/Golden/GoldenReportsTest.php → app/Simulation/Data/Participant.php
- `{closure#7}()` --calls--> `Participant`  [INFERRED]
  tests/Golden/GoldenReportsTest.php → app/Simulation/Data/Participant.php

## Import Cycles
- None detected.

## Communities (149 total, 31 thin omitted)

### Community 0 - ".of"
Cohesion: 0.25
Nodes (38): account(), accountPolicy(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#2}() (+30 more)

### Community 1 - "EngineTest.php"
Cohesion: 0.07
Nodes (48): CommandKind, Flatten, Halt, Pause, Resume, ResumeStrategy, EngineCommand, {closure#2}() (+40 more)

### Community 2 - "App\Support\Clock\Clock"
Cohesion: 0.19
Nodes (4): AuthenticateMcpToken, ExportStrategies, ImportStrategies, PromoteStrategy

### Community 3 - "AppServiceProvider.php"
Cohesion: 0.08
Nodes (15): ArchiveMissing, ChecksumMismatch, Sleeper, CandleStream, PhrityTransport, StreamFailed, StreamTransport, {closure#1}() (+7 more)

### Community 4 - "OrderStatus"
Cohesion: 0.08
Nodes (14): Liquidity, Maker, Taker, OrderStatus, Cancelled, Expired, Filled, New (+6 more)

### Community 5 - ".parse"
Cohesion: 0.10
Nodes (37): {closure#12}(), Parameters, {closure#4}(), {closure#5}(), {closure#7}(), carryDecisions(), {closure#10}(), {closure#11}() (+29 more)

### Community 6 - "Instrument"
Cohesion: 0.07
Nodes (17): BackfillStep, CandleRepository, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), CandleSource, Archive (+9 more)

### Community 7 - "Position"
Cohesion: 0.07
Nodes (19): {closure#2}(), Argument, BoolLiteral, Call, DurationLiteral, Identifier, Lookback, Node (+11 more)

### Community 8 - "CompiledStrategy.php"
Cohesion: 0.09
Nodes (10): {closure#1}(), {closure#2}(), {closure#3}(), CompiledStrategy, {closure#33}(), Compiler, Program, {closure#2}() (+2 more)

### Community 9 - "SimulatedVenueTest.php"
Cohesion: 0.20
Nodes (42): Side, Buy, Sell, btc(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}() (+34 more)

### Community 10 - ".isPositive"
Cohesion: 0.13
Nodes (13): Normal, DecimalMath, {closure#10}(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}() (+5 more)

### Community 11 - "SimulatedPerpVenueTest.php"
Cohesion: 0.22
Nodes (25): {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#15}(), {closure#18}() (+17 more)

### Community 12 - "Candle"
Cohesion: 0.18
Nodes (26): Candle, candleWith(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}() (+18 more)

### Community 13 - "ExchangeVerificationTest.php"
Cohesion: 0.10
Nodes (32): VerificationSource, DailyArchive, Rest, {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#2}() (+24 more)

### Community 14 - "IndicatorsTest.php"
Cohesion: 0.12
Nodes (17): Atr, Ema, Sma, {closure#5}(), fiveMinuteBars(), bar(), {closure#1}(), {closure#12}() (+9 more)

### Community 15 - "AppServiceProvider.php"
Cohesion: 0.06
Nodes (9): RunBacktest, RunGrid, SimulationTool, SystemSleeper, AppServiceProvider, {closure#3}(), {closure#4}(), {closure#6}() (+1 more)

### Community 16 - "Node"
Cohesion: 0.11
Nodes (28): Binary, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}() (+20 more)

### Community 17 - "StrategyVersionsTest.php"
Cohesion: 0.14
Nodes (9): InstrumentDirectory, StrategyRepository, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), versions() (+1 more)

### Community 18 - "App\Strategy\Dsl\Position"
Cohesion: 0.11
Nodes (12): {closure#5}(), DocumentChecker, DslError, YamlList, YamlMap, YamlReader, YamlScalar, YamlValue (+4 more)

### Community 19 - "MarketType"
Cohesion: 0.05
Nodes (28): BinanceEndpoints, BinanceRequestFailed, BinanceRestClient, {closure#1}(), LocationRestricted, RateLimited, RequestFailed, SyncInstruments (+20 more)

### Community 20 - "LedgerTest.php"
Cohesion: 0.20
Nodes (26): balanceOf(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#15}(), {closure#16}() (+18 more)

### Community 21 - "Illuminate\Database\Schema\Blueprint"
Cohesion: 0.06
Nodes (22): {closure#1}(), {closure#2}(), {closure#3}(), {closure#1}(), {closure#2}(), {closure#1}(), {closure#2}(), {closure#3}() (+14 more)

### Community 22 - "generate.py"
Cohesion: 0.08
Nodes (30): atr(), bars(), bb(), change(), dmi(), donchian(), ema(), highest() (+22 more)

### Community 24 - "Symbol"
Cohesion: 0.05
Nodes (25): {closure#1}(), BuyAndHold, DonchianBreakout, FundingCarry, MovingAverageTrend, BuildableStrategy, Strategy, Close (+17 more)

### Community 25 - "ParamKind"
Cohesion: 0.14
Nodes (10): {closure#1}(), {closure#2}(), ParamKind, Anchor, Count, Duration, Number, Percent (+2 more)

### Community 26 - "RejectionReason"
Cohesion: 0.07
Nodes (31): AccountView, RejectionReason, BelowMinimum, Breaker, Cooldown, DuplicateIntent, EntryWorking, GrossExposure (+23 more)

### Community 27 - "App\Strategy\Dsl\Compiler\CompiledStrategy"
Cohesion: 0.09
Nodes (12): {closure#1}(), {closure#2}(), {closure#5}(), {closure#6}(), {closure#7}(), EntrySpec, Expression, {closure#1}() (+4 more)

### Community 28 - ".measure"
Cohesion: 0.18
Nodes (9): EquityTracker, Performance, {closure#1}(), {closure#2}(), {closure#3}(), {closure#5}(), {closure#6}(), {closure#8}() (+1 more)

### Community 29 - "Fill"
Cohesion: 0.09
Nodes (8): Fill, PerpPosition, Position, RoundTrip, Perpetual, {closure#16}(), {closure#6}(), {closure#7}()

### Community 30 - "EnginePerpTest.php"
Cohesion: 0.12
Nodes (24): EngineMode, Backtest, Live, Paper, Testnet, InMemoryDecisionLog, {closure#1}(), {closure#2}() (+16 more)

### Community 31 - "DslBaselinesTest.php"
Cohesion: 0.12
Nodes (23): {closure#1}(), {closure#1}(), {closure#2}(), {closure#3}(), baselineDocument(), baselinePairs(), {closure#10}(), {closure#11}() (+15 more)

### Community 32 - "ValidationStatisticsTest.php"
Cohesion: 0.12
Nodes (18): Cscv, DeflatedSharpe, closeTo(), {closure#1}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}() (+10 more)

### Community 33 - ".of"
Cohesion: 0.08
Nodes (40): {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#1}(), {closure#2}(), {closure#3}(), documentedLimits() (+32 more)

### Community 34 - "Carbon\CarbonImmutable"
Cohesion: 0.12
Nodes (8): DecisionLog, DecisionRecord, EngineRecord, IntentRecord, Engine, ReconciliationMismatch, {closure#3}(), {closure#4}()

### Community 35 - "ExitReason"
Cohesion: 0.20
Nodes (25): ExitReason, Flatten, Liquidation, Signal, Stop, Target, {closure#1}(), {closure#10}() (+17 more)

### Community 36 - "Timeframe"
Cohesion: 0.11
Nodes (14): {closure#4}(), Timeframe, FifteenMinutes, FiveMinutes, FourHours, OneDay, OneHour, OneMinute (+6 more)

### Community 37 - "SimClock"
Cohesion: 0.06
Nodes (36): SimClock, WallClock, {closure#11}(), {closure#13}(), {closure#14}(), {closure#15}(), {closure#16}(), {closure#4}() (+28 more)

### Community 38 - "PreparesBacktests.php"
Cohesion: 0.18
Nodes (6): {closure#1}(), {closure#2}(), {closure#3}(), {closure#5}(), {closure#7}(), {closure#8}()

### Community 39 - "BacktestTest.php"
Cohesion: 0.07
Nodes (33): FeeSchedule, CostModel, CandleFeed, FundingRate, RiskProfile, Account, Benchmark, RiskPolicy (+25 more)

### Community 40 - "Illuminate\Contracts\Config\Repository"
Cohesion: 0.26
Nodes (4): StoredInstruments, {closure#1}(), CompileStrategy, PolicyLimits

### Community 41 - "ema-cross-scalp-long.strategy.yaml"
Cohesion: 0.06
Nodes (33): entries, long, exits, stop_loss, take_profit, time_stop, when, fails_when (+25 more)

### Community 42 - "App\Support\Number\Decimal"
Cohesion: 0.04
Nodes (32): {closure#11}(), {closure#12}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#6}(), {closure#8}(), {closure#1}() (+24 more)

### Community 43 - "autoload-dev"
Cohesion: 0.67
Nodes (3): autoload-dev, psr-4, Tests\\

### Community 44 - "OrderRole"
Cohesion: 0.12
Nodes (29): OrderRole, Entry, Exit, Flatten, Stop, Target, TrackedOrder, {closure#1}() (+21 more)

### Community 46 - "Illuminate\Database\Eloquent\Model"
Cohesion: 0.10
Nodes (12): {closure#1}(), McpCall, McpToken, BacktestRun, StrategyGateReport, StrategyHoldout, StrategyTrial, StrategyStage (+4 more)

### Community 47 - "package.json"
Cohesion: 0.13
Nodes (20): devDependencies, concurrently@^10.0.3, laravel-vite-plugin@^3.1, tailwindcss@^4.0.0, @tailwindcss/vite@^4.0.0, vite@^8.0.0, optionalDependencies, @laravel/multiplex@^0.4.1 (+12 more)

### Community 48 - "DocumentCheckerTest.php"
Cohesion: 0.35
Nodes (9): checkDocument(), {closure#2}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}(), {closure#8}(), {closure#9}() (+1 more)

### Community 49 - "BinanceRestClientTest.php"
Cohesion: 0.17
Nodes (12): binanceClient(), {closure#1}(), {closure#11}(), {closure#13}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}() (+4 more)

### Community 50 - "ema-cross-scalp-short.strategy.yaml"
Cohesion: 0.06
Nodes (33): entries, short, exits, stop_loss, take_profit, time_stop, when, fails_when (+25 more)

### Community 51 - ".evaluate"
Cohesion: 0.24
Nodes (9): Gates, brokenGate(), {closure#1}(), {closure#3}(), {closure#4}(), {closure#6}(), {closure#7}(), gate() (+1 more)

### Community 52 - "TokensTest.php"
Cohesion: 0.31
Nodes (7): {closure#3}(), {closure#4}(), {closure#5}(), contrastRatio(), contrastRules(), relativeLuminance(), themeTokens()

### Community 53 - "Laravel\Mcp\Response"
Cohesion: 0.11
Nodes (8): CompileStrategy, GetFunding, GetJob, GetStrategy, ListTrials, SaveStrategy, TradingTool, StrategyLibrary

### Community 54 - "postgres"
Cohesion: 0.10
Nodes (19): POSTGRES_DB, POSTGRES_PASSWORD, POSTGRES_USER, interval, retries, test, timeout, environment (+11 more)

### Community 55 - "InstrumentRules"
Cohesion: 0.23
Nodes (12): InstrumentRules, {closure#1}(), RulesHistory, {closure#2}(), {closure#4}(), {closure#41}(), {closure#1}(), {closure#2}() (+4 more)

### Community 57 - "donchian-breakout-short.strategy.yaml"
Cohesion: 0.07
Nodes (26): entries, short, default, range, step, default, range, step (+18 more)

### Community 58 - ".count"
Cohesion: 0.20
Nodes (11): Statistics, {closure#3}(), {closure#3}(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}() (+3 more)

### Community 59 - "ChartSeries"
Cohesion: 0.19
Nodes (5): ChartTool, GetCandles, GetIndicators, {closure#2}(), ChartSeries

### Community 60 - "RunBacktestCommandTest.php"
Cohesion: 0.12
Nodes (23): {closure#4}(), {closure#6}(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}() (+15 more)

### Community 61 - "Ledger"
Cohesion: 0.19
Nodes (4): Ledger, LedgerViolation, Asset, {closure#19}()

### Community 62 - "BinanceArchive"
Cohesion: 0.09
Nodes (13): ArchivePeriod, BinanceArchive, {closure#5}(), archiveBytes(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}() (+5 more)

### Community 63 - "CandleFeedTest.php"
Cohesion: 0.18
Nodes (17): MinuteCandles, DatabaseCandleFeed, {closure#1}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#2}() (+9 more)

### Community 64 - "App\Support\Symbol"
Cohesion: 0.11
Nodes (8): {closure#1}(), {closure#11}(), {closure#2}(), {closure#3}(), {closure#9}(), {closure#1}(), {closure#2}(), OrderManager

### Community 65 - "donchian-breakout.strategy.yaml"
Cohesion: 0.07
Nodes (26): entries, long, default, range, step, default, range, step (+18 more)

### Community 66 - ".evaluate"
Cohesion: 0.25
Nodes (8): WalkForward, WalkForwardSet, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), wfSet()

### Community 67 - "CandleRepositoryTest.php"
Cohesion: 0.28
Nodes (11): candleStore(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}() (+3 more)

### Community 68 - "App\Strategy\Dsl\Document\DocumentChecker"
Cohesion: 0.08
Nodes (29): {closure#3}(), {closure#4}(), {closure#8}(), {closure#9}(), ParameterSpec, DslWriter, FunctionSpec, Library (+21 more)

### Community 69 - ".of"
Cohesion: 0.15
Nodes (16): AssetMismatch, {closure#2}(), {closure#1}(), usdt(), {closure#7}(), {closure#1}(), {closure#11}(), {closure#12}() (+8 more)

### Community 70 - "App\Agents\Mcp\TradingServer"
Cohesion: 0.15
Nodes (4): DslReference, ListInstruments, ListStrategies, TradingServer

### Community 71 - "Clock"
Cohesion: 0.07
Nodes (18): CreateMcpToken, RevokeMcpToken, Backfill, {closure#1}(), {closure#2}(), {closure#3}(), BackfillReport, BackfillMarketData (+10 more)

### Community 72 - "SimulationJobsTest.php"
Cohesion: 0.15
Nodes (4): {closure#1}(), {closure#2}(), {closure#3}(), savedEmaCross()

### Community 73 - "scripts"
Cohesion: 0.15
Nodes (13): scripts, analyse, dev, format, mutate, post-autoload-dump, post-update-cmd, pre-package-uninstall (+5 more)

### Community 74 - "ma-trend-short.strategy.yaml"
Cohesion: 0.07
Nodes (26): entries, short, exits, stop_loss, when, fails_when, family, default (+18 more)

### Community 75 - ".parse"
Cohesion: 0.23
Nodes (11): AstPrinter, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), sexpr(), {closure#1}(), {closure#3}() (+3 more)

### Community 76 - ".walk"
Cohesion: 0.19
Nodes (18): CandleAggregator, CandleSequenceBroken, {closure#3}(), {closure#6}(), aggregate(), {closure#1}(), {closure#10}(), {closure#11}() (+10 more)

### Community 77 - "composer.json"
Cohesion: 0.17
Nodes (11): description, extra, laravel, keywords, dont-discover, license, minimum-stability, name (+3 more)

### Community 78 - "jobs"
Cohesion: 0.17
Nodes (11): concurrency, cancel-in-progress, group, jobs, name, on, pull_request, push (+3 more)

### Community 79 - "postgres"
Cohesion: 0.17
Nodes (12): POSTGRES_DB, POSTGRES_PASSWORD, POSTGRES_USER, env, image, options, ports, image (+4 more)

### Community 80 - "ma-trend.strategy.yaml"
Cohesion: 0.07
Nodes (26): entries, long, exits, stop_loss, when, fails_when, family, default (+18 more)

### Community 81 - "IndicatorReferenceTest.php"
Cohesion: 0.05
Nodes (28): {closure#34}(), {closure#42}(), Change, DonchianChannel, Highest, Hma, Lowest, Macd (+20 more)

### Community 82 - "CompiledStrategyTest.php"
Cohesion: 0.17
Nodes (16): PositionView, {closure#24}(), aggregatedBars(), {closure#11}(), {closure#13}(), {closure#16}(), {closure#17}(), {closure#2}() (+8 more)

### Community 83 - "App\Support\Number\Decimal"
Cohesion: 0.08
Nodes (37): {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#15}(), {closure#16}(), {closure#17}() (+29 more)

### Community 84 - "pint/composer.json"
Cohesion: 0.20
Nodes (9): config, sort-packages, description, license, name, require, laravel/pint, php (+1 more)

### Community 85 - "MonteCarlo"
Cohesion: 0.23
Nodes (9): MonteCarlo, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), mcDecimals() (+1 more)

### Community 87 - "App\Support\Number\Decimal"
Cohesion: 0.06
Nodes (33): {closure#1}(), {closure#2}(), {closure#3}(), {closure#10}(), {closure#5}(), {closure#4}(), {closure#12}(), {closure#10}() (+25 more)

### Community 88 - "require-dev"
Cohesion: 0.22
Nodes (9): require-dev, fakerphp/faker, larastan/larastan, laravel/pail, laravel/pao, mockery/mockery, nunomaduro/collision, pestphp/pest (+1 more)

### Community 89 - "scripts-descriptions"
Cohesion: 0.25
Nodes (8): scripts-descriptions, analyse, format, mutate, test, test:network, test:testnet, tools:install

### Community 90 - "RiskState"
Cohesion: 0.14
Nodes (11): RiskEvent, RiskEventKind, DailyLossHalt, DrawdownFlatten, DrawdownHalt, WeeklyLossHalt, RiskState, {closure#15}() (+3 more)

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

### Community 96 - ".handle"
Cohesion: 0.18
Nodes (7): StrategyGates, {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), readBackDocument(), readBackSpec()

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

### Community 108 - "OrderUpdate"
Cohesion: 0.17
Nodes (10): Income, IncomeKind, Funding, Liquidation, OrderUpdate, {closure#3}(), {closure#1}(), {closure#34}() (+2 more)

### Community 119 - "MarketBreakers"
Cohesion: 0.29
Nodes (7): {closure#4}(), BreakerEvent, MarketBreakers, breakerMinute(), {closure#1}(), {closure#2}(), {closure#3}()

### Community 120 - "buy-and-hold.strategy.yaml"
Cohesion: 0.12
Nodes (16): entries, long, exits, stop_loss, fails_when, family, hypothesis, when (+8 more)

### Community 121 - "App\Market\Data\Candle"
Cohesion: 0.08
Nodes (19): {closure#36}(), BarIndicator, DirectionalMovement, Keltner, Natr, Obv, Supertrend, VolumeProfile (+11 more)

### Community 122 - ".isGreaterThan"
Cohesion: 0.16
Nodes (5): {closure#2}(), Donchian, InvalidNumber, {closure#10}(), {closure#15}()

### Community 123 - ".fromStrings"
Cohesion: 0.29
Nodes (4): {closure#9}(), {closure#1}(), {closure#42}(), {closure#12}()

### Community 124 - "bootstrap/app.php"
Cohesion: 0.33
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

### Community 125 - "App\Strategy\Data\StrategyContext"
Cohesion: 0.21
Nodes (3): RenamedStrategy, {closure#18}(), {closure#37}()

### Community 127 - "MarketType"
Cohesion: 0.13
Nodes (20): btcMinutes(), {closure#1}(), {closure#2}(), referencePrice(), {closure#21}(), {closure#2}(), {closure#3}(), {closure#6}() (+12 more)

### Community 128 - "funding-carry.strategy.yaml"
Cohesion: 0.12
Nodes (15): fails_when, family, hypothesis, params, enter_when, exit_when, margin_buffer, max_leverage_perp (+7 more)

### Community 129 - "McpServerTest.php"
Cohesion: 0.27
Nodes (11): actAsToken(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}(), {closure#8}() (+3 more)

### Community 131 - "Decimal"
Cohesion: 0.05
Nodes (23): Balance, ProtectionPlan, VenueFill, VenuePosition, {closure#1}(), {closure#2}(), {closure#3}(), SimulatedPosition (+15 more)

### Community 132 - "Marks"
Cohesion: 0.27
Nodes (3): Marks, {closure#25}(), {closure#9}()

### Community 133 - "ClientOrderId"
Cohesion: 0.07
Nodes (13): Venue, ClientOrderId, OcoRequest, OrderAck, OrderRequest, OrderType, Limit, Market (+5 more)

### Community 138 - "App\Market\Archive\BinanceArchive"
Cohesion: 0.09
Nodes (16): {closure#1}(), {closure#2}(), EpochTime, ExchangeNumber, Json, KlineMessage, {closure#1}(), {closure#2}() (+8 more)

### Community 139 - ".encode"
Cohesion: 0.30
Nodes (6): CanonicalJson, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}()

### Community 140 - "GridPart.php"
Cohesion: 0.21
Nodes (3): {closure#2}(), GridPart, SetOutcome

### Community 142 - "StrategyVersion"
Cohesion: 0.14
Nodes (9): {closure#1}(), {closure#1}(), {closure#1}(), SaveResult, StrategyVersion, {closure#1}(), {closure#1}(), {closure#1}() (+1 more)

### Community 143 - "Group"
Cohesion: 0.22
Nodes (8): Group, Exit, Indicator, Order, Perpetual, Position, Price, Time

## Knowledge Gaps
- **122 isolated node(s):** `Halt`, `Resume`, `Flatten`, `Pause`, `ResumeStrategy` (+117 more)
  These have ≤1 connection - possible missing edges. (Counts symbols only; 749 node(s) total have ≤1 connection when file, concept and rationale nodes are included.)
- **31 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `Decimal` connect `Decimal` to `.of`, `EngineTest.php`, `OrderStatus`, `ClientOrderId`, `Instrument`, `Marks`, `.parse`, `SimulatedVenueTest.php`, `Position`, `.isPositive`, `Candle`, `GridPart.php`, `Stringable`, `.encode`, `.__construct`, `IndicatorsTest.php`, `QualityCheck`, `MarketType`, `LedgerTest.php`, `Symbol`, `RejectionReason`, `.measure`, `Fill`, `EnginePerpTest.php`, `DslBaselinesTest.php`, `ValidationStatisticsTest.php`, `.of`, `Carbon\CarbonImmutable`, `ExitReason`, `SimClock`, `BacktestTest.php`, `OrderRole`, `InstrumentRules`, `PreparesBacktests`, `.count`, `RunBacktestCommandTest.php`, `Ledger`, `BinanceArchive`, `CandleFeedTest.php`, `App\Support\Symbol`, `.of`, `.walk`, `CompiledStrategyTest.php`, `App\Support\Number\Decimal`, `App\Support\Number\Decimal`, `RiskState`, `OrderUpdate`, `App\Market\Data\Candle`, `.isGreaterThan`, `MarketType`?**
  _High betweenness centrality (0.106) - this node is a cross-community bridge._
- **What connects `Halt`, `Resume`, `Flatten` to the rest of the system?**
  _122 weakly-connected nodes found - possible missing edges._
- **Should `EngineTest.php` be split into smaller, more focused modules?**
  _Cohesion score 0.06699970614163973 - nodes in this community are weakly interconnected._
- **Why does `Candle` connect `Candle` to `EngineTest.php`, `Decimal`, `OrderStatus`, `AppServiceProvider.php`, `Instrument`, `.parse`, `SimulatedVenueTest.php`, `App\Market\Archive\BinanceArchive`, `ExchangeVerificationTest.php`, `IndicatorsTest.php`, `.__construct`, `MarketType`, `Symbol`, `EnginePerpTest.php`, `Carbon\CarbonImmutable`, `Timeframe`, `BacktestTest.php`, `OrderRole`, `BinanceRestClientTest.php`, `BinanceArchive`, `CandleFeedTest.php`, `CandleRepositoryTest.php`, `Clock`, `.walk`, `CompiledStrategyTest.php`, `App\Market\Data\Candle`, `.isGreaterThan`, `.fromStrings`, `App\Strategy\Data\StrategyContext`, `MarketType`?**
  _High betweenness centrality (0.032) - this node is a cross-community bridge._
- **Should `AppServiceProvider.php` be split into smaller, more focused modules?**
  _Cohesion score 0.07505285412262157 - nodes in this community are weakly interconnected._
- **Why does `Symbol` connect `Symbol` to `EngineTest.php`, `Decimal`, `OrderStatus`, `ClientOrderId`, `Instrument`, `Marks`, `.parse`, `SimulatedVenueTest.php`, `AppServiceProvider.php`, `SimulatedPerpVenueTest.php`, `.encode`, `Stringable`, `ExchangeVerificationTest.php`, `MarketType`, `LedgerTest.php`, `RejectionReason`, `.measure`, `Fill`, `EnginePerpTest.php`, `ValidationStatisticsTest.php`, `Carbon\CarbonImmutable`, `ExitReason`, `SimClock`, `BacktestTest.php`, `OrderRole`, `InstrumentRules`, `ChartSeries`, `RunBacktestCommandTest.php`, `Ledger`, `CandleFeedTest.php`, `App\Support\Symbol`, `CandleRepositoryTest.php`, `Clock`, `CompiledStrategyTest.php`, `App\Support\Number\Decimal`, `OrderUpdate`, `App\Strategy\Data\StrategyContext`, `MarketType`?**
  _High betweenness centrality (0.028) - this node is a cross-community bridge._
- **Should `OrderStatus` be split into smaller, more focused modules?**
  _Cohesion score 0.07784679089026915 - nodes in this community are weakly interconnected._