# Graph Report - trading-platform  (2026-11-08)

## Corpus Check
- 396 files · ~159,187 words
- Verdict: corpus is large enough that graph structure adds value.
- Unclassified: 92 file(s) not represented in the graph (top: .zip 34, .CHECKSUM 34, (none) 18)

## Summary
- 3218 nodes · 11721 edges · 130 communities (108 shown, 22 thin omitted)
- Extraction: 90% EXTRACTED · 10% INFERRED · 0% AMBIGUOUS · INFERRED: 1225 edges (avg confidence: 0.85)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `b9cb713e`
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
- CompiledStrategy.php
- SimulatedVenueTest.php
- App\Support\Symbol
- SimulatedPerpVenueTest.php
- Candle
- ExchangeVerificationTest.php
- IndicatorsTest.php
- CandleStream
- Node
- .of
- App\Strategy\Dsl\Position
- StreamTransport
- Ledger
- Illuminate\Database\Schema\Blueprint
- generate.py
- SimulatedVenue
- StrategyContext
- Unit
- RejectionReason
- OrderRole
- .isPositive
- EnginePerpTest.php
- RiskEventKind
- ValidationStatisticsTest.php
- DecimalMathTest.php
- Engine
- .expect
- Timeframe
- SimClock
- Decimal
- BacktestTest.php
- Symbol
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
- CompileStrategy.php
- .count
- GlobalsTest.php
- RunBacktestCommandTest.php
- OrderManager
- Carbon\CarbonImmutable
- CandleFeedTest.php
- .run
- Close
- IndicatorReferenceTest.php
- Ledger
- CandleRepositoryTest.php
- Asset
- YamlMap
- Universe
- Fill
- scripts
- BinanceArchive.php
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
- App\Strategy\Contracts\Strategy
- Quantity
- bootstrap/app.php
- require-dev
- scripts-descriptions
- .count
- DonchianBreakout
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
- Illuminate\Database\Eloquent\Model
- .handle
- CompiledStrategyTest.php
- OrderType
- Money
- CostModel
- RunBacktest.php
- StrategyIdentity
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
9. `Ledger` - 56 edges
10. `SimulatedVenue` - 56 edges

## Surprising Connections (you probably didn't know these)
- `backtestFixture()` --calls--> `SimClock`  [INFERRED]
  tests/Feature/Simulation/RunBacktestCommandTest.php → app/Support/Clock/SimClock.php
- `perpFixture()` --calls--> `FundingRate`  [INFERRED]
  tests/Feature/Simulation/RunBacktestCommandTest.php → app/Market/Data/FundingRate.php
- `versions()` --calls--> `PolicyLimits`  [INFERRED]
  tests/Feature/Strategy/StrategyVersionsTest.php → app/Strategy/Dsl/Document/PolicyLimits.php
- `versions()` --calls--> `SimClock`  [INFERRED]
  tests/Feature/Strategy/StrategyVersionsTest.php → app/Support/Clock/SimClock.php
- `{closure#5}()` --calls--> `SimClock`  [INFERRED]
  tests/Feature/Strategy/StrategyVersionsTest.php → app/Support/Clock/SimClock.php

## Import Cycles
- None detected.

## Communities (130 total, 22 thin omitted)

### Community 0 - ".of"
Cohesion: 0.17
Nodes (46): RiskState, account(), accountPolicy(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}() (+38 more)

### Community 1 - "EngineTest.php"
Cohesion: 0.09
Nodes (35): {closure#1}(), MoveStop, ScriptedStrategy, {closure#1}(), {closure#10}(), {closure#13}(), {closure#14}(), {closure#16}() (+27 more)

### Community 2 - "App\Support\Number\Decimal"
Cohesion: 0.06
Nodes (29): {closure#14}(), {closure#10}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}() (+21 more)

### Community 3 - "Instrument"
Cohesion: 0.05
Nodes (29): Backfill, {closure#1}(), {closure#2}(), {closure#3}(), BackfillReport, BackfillStep, CandleRepository, {closure#1}() (+21 more)

### Community 4 - "SimulatedVenue"
Cohesion: 0.10
Nodes (15): Liquidity, Maker, Taker, OrderStatus, Cancelled, Expired, Filled, New (+7 more)

### Community 5 - ".parse"
Cohesion: 0.12
Nodes (33): carryDecisions(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#15}(), {closure#16}() (+25 more)

### Community 6 - "MarketType"
Cohesion: 0.06
Nodes (23): BinanceRestClient, {closure#1}(), Json, RequestFailed, SyncInstruments, InstrumentDefinition, InstrumentStatus, Delisted (+15 more)

### Community 7 - "Position"
Cohesion: 0.07
Nodes (17): Argument, BoolLiteral, Call, DurationLiteral, Identifier, Lookback, NumberLiteral, OnTimeframe (+9 more)

### Community 8 - "CompiledStrategy.php"
Cohesion: 0.11
Nodes (7): {closure#3}(), CompiledStrategy, {closure#34}(), Compiler, Program, {closure#2}(), Vwap

### Community 9 - "SimulatedVenueTest.php"
Cohesion: 0.18
Nodes (39): btc(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#15}(), {closure#16}(), {closure#17}() (+31 more)

### Community 10 - "App\Support\Symbol"
Cohesion: 0.07
Nodes (11): IntentRecord, {closure#6}(), {closure#7}(), {closure#1}(), LimitedStrategy, EntryOrder, OpenLong, OpenShort (+3 more)

### Community 11 - "SimulatedPerpVenueTest.php"
Cohesion: 0.19
Nodes (24): {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#15}(), {closure#18}(), {closure#19}() (+16 more)

### Community 12 - "Candle"
Cohesion: 0.13
Nodes (30): {closure#1}(), Candle, {closure#1}(), {closure#2}(), {closure#1}(), {closure#42}(), candleWith(), {closure#1}() (+22 more)

### Community 13 - "ExchangeVerificationTest.php"
Cohesion: 0.10
Nodes (27): {closure#1}(), {closure#12}(), {closure#13}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}() (+19 more)

### Community 14 - "IndicatorsTest.php"
Cohesion: 0.12
Nodes (16): Atr, Ema, Sma, bar(), {closure#1}(), {closure#12}(), {closure#13}(), {closure#14}() (+8 more)

### Community 15 - "CandleStream"
Cohesion: 0.07
Nodes (12): BinanceEndpoints, Sleeper, SystemSleeper, CandleStream, StreamTransport, AppServiceProvider, {closure#1}(), {closure#2}() (+4 more)

### Community 16 - "Node"
Cohesion: 0.11
Nodes (30): Binary, Node, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}() (+22 more)

### Community 17 - ".of"
Cohesion: 0.17
Nodes (17): {closure#2}(), {closure#1}(), usdt(), {closure#3}(), {closure#6}(), {closure#7}(), {closure#1}(), {closure#11}() (+9 more)

### Community 18 - "App\Strategy\Dsl\Position"
Cohesion: 0.11
Nodes (15): {closure#1}(), {closure#5}(), {closure#8}(), DslError, YamlList, YamlReader, YamlScalar, YamlValue (+7 more)

### Community 19 - "StreamTransport"
Cohesion: 0.06
Nodes (11): ArchiveMissing, ChecksumMismatch, BinanceRequestFailed, LocationRestricted, RateLimited, VerificationSource, DailyArchive, Rest (+3 more)

### Community 20 - "Ledger"
Cohesion: 0.23
Nodes (29): Side, Buy, Sell, balanceOf(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}() (+21 more)

### Community 21 - "Illuminate\Database\Schema\Blueprint"
Cohesion: 0.08
Nodes (17): {closure#1}(), {closure#2}(), {closure#3}(), {closure#1}(), {closure#2}(), {closure#1}(), {closure#2}(), {closure#3}() (+9 more)

### Community 22 - "generate.py"
Cohesion: 0.09
Nodes (28): atr(), bars(), bb(), change(), dmi(), donchian(), ema(), highest() (+20 more)

### Community 23 - "SimulatedVenue"
Cohesion: 0.07
Nodes (16): Venue, Balance, ClientOrderId, OcoRequest, OrderAck, OrderRequest, {closure#2}(), SimulatedVenue (+8 more)

### Community 24 - "StrategyContext"
Cohesion: 0.10
Nodes (8): BuildableStrategy, Strategy, StrategyContext, Subscription, Period, EnterOnceStrategy, HoldStrategy, {closure#1}()

### Community 25 - "Unit"
Cohesion: 0.06
Nodes (29): Group, Agent, Exit, Indicator, Order, Perpetual, Position, Price (+21 more)

### Community 27 - "RejectionReason"
Cohesion: 0.08
Nodes (27): AccountView, RejectionReason, BelowMinimum, Cooldown, DuplicateIntent, EntryWorking, GrossExposure, Halted (+19 more)

### Community 28 - "OrderRole"
Cohesion: 0.12
Nodes (29): OrderRole, Entry, Exit, Flatten, Stop, Target, TrackedOrder, {closure#1}() (+21 more)

### Community 29 - ".isPositive"
Cohesion: 0.13
Nodes (3): {closure#2}(), Normal, InvalidNumber

### Community 30 - "EnginePerpTest.php"
Cohesion: 0.28
Nodes (14): {closure#10}(), {closure#11}(), {closure#14}(), {closure#2}(), {closure#5}(), {closure#6}(), {closure#7}(), {closure#8}() (+6 more)

### Community 31 - "RiskEventKind"
Cohesion: 0.33
Nodes (5): RiskEventKind, DailyLossHalt, DrawdownFlatten, DrawdownHalt, WeeklyLossHalt

### Community 32 - "ValidationStatisticsTest.php"
Cohesion: 0.12
Nodes (19): Cscv, DeflatedSharpe, closeTo(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}() (+11 more)

### Community 33 - "DecimalMathTest.php"
Cohesion: 0.06
Nodes (50): {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#2}(), {closure#3}(), {closure#1}(), {closure#2}() (+42 more)

### Community 34 - "Engine"
Cohesion: 0.10
Nodes (8): DecisionLog, DecisionRecord, EngineRecord, Engine, ReconciliationMismatch, RiskEvent, {closure#4}(), {closure#1}()

### Community 35 - ".expect"
Cohesion: 0.19
Nodes (13): InstrumentDirectory, CheckResult, PolicyLimits, KnownInstruments, checkDocument(), {closure#2}(), {closure#4}(), {closure#5}() (+5 more)

### Community 36 - "Timeframe"
Cohesion: 0.10
Nodes (15): {closure#4}(), {closure#8}(), Timeframe, FifteenMinutes, FiveMinutes, FourHours, OneDay, OneHour (+7 more)

### Community 37 - "SimClock"
Cohesion: 0.06
Nodes (40): SimClock, {closure#11}(), {closure#13}(), {closure#14}(), {closure#15}(), {closure#16}(), {closure#4}(), {closure#5}() (+32 more)

### Community 38 - "Decimal"
Cohesion: 0.06
Nodes (16): PerformanceMetrics, {closure#1}(), {closure#2}(), {closure#3}(), TradeStatistics, {closure#2}(), Decimal, DecimalMath (+8 more)

### Community 39 - "BacktestTest.php"
Cohesion: 0.18
Nodes (12): CandleFeed, Backtest, InMemoryCandleFeed, Policies, backtestSetup(), {closure#1}(), {closure#11}(), {closure#4}() (+4 more)

### Community 40 - "Symbol"
Cohesion: 0.07
Nodes (10): ProtectionPlan, VenueOrder, VenuePosition, {closure#1}(), SimulatedPosition, PositionView, Price, Symbol (+2 more)

### Community 41 - "ema-cross-scalp-long.strategy.yaml"
Cohesion: 0.06
Nodes (33): entries, long, exits, stop_loss, take_profit, time_stop, when, fails_when (+25 more)

### Community 42 - "RiskPolicy"
Cohesion: 0.25
Nodes (5): RiskProfile, Account, Benchmark, RiskPolicy, {closure#1}()

### Community 43 - "Kind"
Cohesion: 0.07
Nodes (33): {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#7}(), {closure#9}(), EntrySpec, Expression (+25 more)

### Community 44 - "sources"
Cohesion: 0.09
Nodes (22): secret, type, sources, auth, channels, connector, exchange, id (+14 more)

### Community 46 - "GridBacktest.php"
Cohesion: 0.11
Nodes (11): {closure#10}(), {closure#13}(), {closure#16}(), {closure#17}(), {closure#18}(), {closure#3}(), {closure#5}(), {closure#6}() (+3 more)

### Community 47 - "package.json"
Cohesion: 0.13
Nodes (20): devDependencies, concurrently@^10.0.3, laravel-vite-plugin@^3.1, tailwindcss@^4.0.0, @tailwindcss/vite@^4.0.0, vite@^8.0.0, optionalDependencies, @laravel/multiplex@^0.4.1 (+12 more)

### Community 48 - "PreparesBacktests.php"
Cohesion: 0.18
Nodes (8): {closure#1}(), StrategyRepository, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), versions()

### Community 49 - "BinanceRestClientTest.php"
Cohesion: 0.17
Nodes (12): binanceClient(), {closure#1}(), {closure#11}(), {closure#13}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}() (+4 more)

### Community 50 - "ema-cross-scalp-short.strategy.yaml"
Cohesion: 0.06
Nodes (33): entries, short, exits, stop_loss, take_profit, time_stop, when, fails_when (+25 more)

### Community 51 - ".handle"
Cohesion: 0.33
Nodes (5): CanonicalJson, {closure#1}(), {closure#3}(), {closure#4}(), {closure#5}()

### Community 52 - "TokensTest.php"
Cohesion: 0.31
Nodes (7): {closure#3}(), {closure#4}(), {closure#5}(), contrastRatio(), contrastRules(), relativeLuminance(), themeTokens()

### Community 53 - "ExitReason"
Cohesion: 0.17
Nodes (24): ExitReason, Flatten, Liquidation, Signal, Stop, Target, RoundTrip, {closure#1}() (+16 more)

### Community 54 - "postgres"
Cohesion: 0.10
Nodes (19): POSTGRES_DB, POSTGRES_PASSWORD, POSTGRES_USER, interval, retries, test, timeout, environment (+11 more)

### Community 55 - "InstrumentRules"
Cohesion: 0.22
Nodes (13): InstrumentRules, RulesHistory, {closure#2}(), {closure#3}(), {closure#5}(), {closure#4}(), {closure#41}(), {closure#1}() (+5 more)

### Community 56 - "PreparesBacktests"
Cohesion: 0.15
Nodes (3): StoredInstruments, {closure#1}(), PreparesBacktests

### Community 57 - "CompileStrategy.php"
Cohesion: 0.15
Nodes (3): CompileStrategy, SaveResult, StrategyVersion

### Community 58 - ".count"
Cohesion: 0.09
Nodes (24): EquityTracker, Performance, Statistics, WalkForward, WalkForwardSet, {closure#1}(), {closure#2}(), {closure#5}() (+16 more)

### Community 59 - "GlobalsTest.php"
Cohesion: 0.22
Nodes (4): Controller, {closure#3}(), {closure#4}(), {closure#5}()

### Community 60 - "RunBacktestCommandTest.php"
Cohesion: 0.20
Nodes (21): backtestFixture(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#15}() (+13 more)

### Community 61 - "OrderManager"
Cohesion: 0.19
Nodes (4): {closure#4}(), {closure#1}(), {closure#2}(), OrderManager

### Community 62 - "Carbon\CarbonImmutable"
Cohesion: 0.08
Nodes (14): ArchivePeriod, BinanceArchive, ExchangeVerification, VerificationReport, archiveBytes(), {closure#1}(), {closure#2}(), {closure#3}() (+6 more)

### Community 63 - "CandleFeedTest.php"
Cohesion: 0.25
Nodes (15): MinuteCandles, {closure#1}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#2}(), {closure#3}(), {closure#4}() (+7 more)

### Community 64 - ".run"
Cohesion: 0.20
Nodes (15): Report, btcMinutes(), {closure#1}(), {closure#2}(), referencePrice(), {closure#2}(), {closure#3}(), {closure#4}() (+7 more)

### Community 65 - "Close"
Cohesion: 0.13
Nodes (4): FundingCarry, Close, Intent, OpenCarry

### Community 66 - "IndicatorReferenceTest.php"
Cohesion: 0.06
Nodes (22): {closure#35}(), Bollinger, Change, Highest, Hma, Lowest, Macd, RealizedVolatility (+14 more)

### Community 67 - "Ledger"
Cohesion: 0.24
Nodes (5): Ledger, LedgerViolation, {closure#19}(), {closure#12}(), {closure#14}()

### Community 68 - "CandleRepositoryTest.php"
Cohesion: 0.28
Nodes (11): candleStore(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}() (+3 more)

### Community 69 - "Asset"
Cohesion: 0.18
Nodes (4): Marks, Asset, {closure#25}(), {closure#9}()

### Community 71 - "Universe"
Cohesion: 0.12
Nodes (6): CheckMarketData, SelectsInstruments, VerifyMarketData, Universe, QualityCheck, QualityReport

### Community 72 - "Fill"
Cohesion: 0.25
Nodes (3): Fill, PerpPosition, Position

### Community 73 - "scripts"
Cohesion: 0.14
Nodes (14): scripts, analyse, dev, format, mutate, post-autoload-dump, post-update-cmd, pre-package-uninstall (+6 more)

### Community 74 - "BinanceArchive.php"
Cohesion: 0.10
Nodes (14): {closure#2}(), EpochTime, ExchangeNumber, KlineMessage, {closure#1}(), {closure#2}(), {closure#3}(), {closure#1}() (+6 more)

### Community 75 - "App\Simulation\Backtest"
Cohesion: 0.13
Nodes (13): EngineMode, Backtest, Live, Paper, Testnet, InMemoryDecisionLog, {closure#1}(), {closure#2}() (+5 more)

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

### Community 81 - "Window"
Cohesion: 0.12
Nodes (3): DonchianChannel, Vwma, Window

### Community 82 - "CompiledStrategyTest.php"
Cohesion: 0.19
Nodes (15): aggregatedBars(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#16}(), {closure#17}(), {closure#2}(), {closure#3}() (+7 more)

### Community 83 - "App\Support\Number\Decimal"
Cohesion: 0.07
Nodes (37): {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#15}(), {closure#16}(), {closure#17}() (+29 more)

### Community 84 - "pint/composer.json"
Cohesion: 0.20
Nodes (9): config, sort-packages, description, license, name, require, laravel/pint, php (+1 more)

### Community 85 - "App\Strategy\Contracts\Strategy"
Cohesion: 0.22
Nodes (3): {closure#1}(), {closure#2}(), RenamedStrategy

### Community 87 - "bootstrap/app.php"
Cohesion: 0.33
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

### Community 88 - "require-dev"
Cohesion: 0.22
Nodes (9): require-dev, fakerphp/faker, larastan/larastan, laravel/pail, laravel/pao, mockery/mockery, nunomaduro/collision, pestphp/pest (+1 more)

### Community 89 - "scripts-descriptions"
Cohesion: 0.22
Nodes (9): scripts-descriptions, analyse, format, mutate, test, test:llm, test:network, test:testnet (+1 more)

### Community 90 - ".count"
Cohesion: 0.14
Nodes (12): Parameters, Donchian, {closure#3}(), {closure#1}(), {closure#3}(), goldenMinutes(), goldenRates(), perpetualReferencePrice() (+4 more)

### Community 91 - "DonchianBreakout"
Cohesion: 0.12
Nodes (3): BuyAndHold, DonchianBreakout, MovingAverageTrend

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
Cohesion: 0.19
Nodes (10): Income, IncomeKind, Funding, Liquidation, OrderUpdate, UpdateStream, {closure#3}(), {closure#1}() (+2 more)

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

### Community 119 - "Illuminate\Database\Eloquent\Model"
Cohesion: 0.17
Nodes (5): {closure#1}(), InstrumentFilter, BacktestRun, StrategyTrial, StrategyStage

### Community 121 - "CompiledStrategyTest.php"
Cohesion: 0.11
Nodes (10): {closure#37}(), BarIndicator, DirectionalMovement, Keltner, Natr, Stochastic, Supertrend, {closure#9}() (+2 more)

### Community 122 - "OrderType"
Cohesion: 0.33
Nodes (5): OrderType, Limit, Market, StopMarket, TakeProfitMarket

### Community 123 - "Money"
Cohesion: 0.23
Nodes (3): Participant, AssetMismatch, Money

### Community 124 - "CostModel"
Cohesion: 0.33
Nodes (5): FeeSchedule, CostModel, {closure#1}(), RunDefinition, {closure#23}()

### Community 125 - "RunBacktest.php"
Cohesion: 0.20
Nodes (4): {closure#1}(), {closure#2}(), {closure#3}(), RunBacktest

### Community 126 - "StrategyIdentity"
Cohesion: 0.44
Nodes (4): StrategyIdentity, {closure#1}(), {closure#2}(), {closure#3}()

### Community 127 - "Illuminate\Console\Command"
Cohesion: 0.24
Nodes (4): {closure#1}(), StreamCandles, ExportStrategies, ImportStrategies

### Community 130 - "ParserTest.php"
Cohesion: 0.18
Nodes (7): AstPrinter, Lexer, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), sexpr()

## Knowledge Gaps
- **121 isolated node(s):** `BelowMinimum`, `Cooldown`, `DuplicateIntent`, `EntryWorking`, `GrossExposure` (+116 more)
  These have ≤1 connection - possible missing edges. (Counts symbols only; 582 node(s) total have ≤1 connection when file, concept and rationale nodes are included.)
- **22 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `Decimal` connect `Decimal` to `.__construct`, `EngineTest.php`, `App\Support\Number\Decimal`, `Instrument`, `SimulatedVenue`, `.of`, `MarketType`, `Position`, `.parse`, `SimulatedVenueTest.php`, `Candle`, `IndicatorsTest.php`, `Node`, `.of`, `Ledger`, `SimulatedVenue`, `StrategyContext`, `RejectionReason`, `OrderRole`, `.isPositive`, `EnginePerpTest.php`, `ValidationStatisticsTest.php`, `DecimalMathTest.php`, `Engine`, `SimClock`, `BacktestTest.php`, `Symbol`, `RiskPolicy`, `GridBacktest.php`, `.handle`, `ExitReason`, `InstrumentRules`, `PreparesBacktests`, `.count`, `RunBacktestCommandTest.php`, `OrderManager`, `CandleFeedTest.php`, `.run`, `Close`, `Ledger`, `Asset`, `Universe`, `Fill`, `BinanceArchive.php`, `App\Simulation\Backtest`, `.walk`, `App\Support\Number\Decimal`, `Quantity`, `.count`, `DonchianBreakout`, `OrderUpdate`, `CompiledStrategyTest.php`, `Money`, `CostModel`, `RunBacktest.php`?**
  _High betweenness centrality (0.123) - this node is a cross-community bridge._
- **What connects `BelowMinimum`, `Cooldown`, `DuplicateIntent` to the rest of the system?**
  _121 weakly-connected nodes found - possible missing edges._
- **Should `EngineTest.php` be split into smaller, more focused modules?**
  _Cohesion score 0.08557692307692308 - nodes in this community are weakly interconnected._
- **Why does `Symbol` connect `Symbol` to `EngineTest.php`, `App\Support\Number\Decimal`, `Instrument`, `SimulatedVenue`, `.parse`, `MarketType`, `SimulatedVenueTest.php`, `App\Support\Symbol`, `SimulatedPerpVenueTest.php`, `ExchangeVerificationTest.php`, `.of`, `Ledger`, `SimulatedVenue`, `StrategyContext`, `RejectionReason`, `OrderRole`, `EnginePerpTest.php`, `Engine`, `Timeframe`, `SimClock`, `BacktestTest.php`, `PreparesBacktests.php`, `.handle`, `ExitReason`, `InstrumentRules`, `RunBacktestCommandTest.php`, `OrderManager`, `CandleFeedTest.php`, `.run`, `Close`, `Ledger`, `CandleRepositoryTest.php`, `Asset`, `Universe`, `Fill`, `App\Simulation\Backtest`, `Quantity`, `.count`, `DonchianBreakout`, `OrderUpdate`, `CostModel`?**
  _High betweenness centrality (0.040) - this node is a cross-community bridge._
- **Should `App\Support\Number\Decimal` be split into smaller, more focused modules?**
  _Cohesion score 0.05669199298655757 - nodes in this community are weakly interconnected._
- **Why does `Timeframe` connect `Timeframe` to `.__construct`, `EngineTest.php`, `Instrument`, `.parse`, `MarketType`, `Position`, `CompiledStrategy.php`, `SimulatedVenueTest.php`, `Candle`, `IndicatorsTest.php`, `Node`, `StrategyContext`, `OrderRole`, `EnginePerpTest.php`, `SimClock`, `Symbol`, `ExitReason`, `PreparesBacktests`, `Carbon\CarbonImmutable`, `CandleFeedTest.php`, `Close`, `CandleRepositoryTest.php`, `YamlMap`, `BinanceArchive.php`, `.walk`, `CompiledStrategyTest.php`, `.count`, `DonchianBreakout`, `CompiledStrategyTest.php`?**
  _High betweenness centrality (0.028) - this node is a cross-community bridge._
- **Should `Instrument` be split into smaller, more focused modules?**
  _Cohesion score 0.05352968676951847 - nodes in this community are weakly interconnected._