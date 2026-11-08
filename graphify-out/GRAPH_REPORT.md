# Graph Report - trading-platform  (2026-11-08)

## Corpus Check
- 369 files · ~147,568 words
- Verdict: corpus is large enough that graph structure adds value.
- Unclassified: 92 file(s) not represented in the graph (top: .zip 34, .CHECKSUM 34, (none) 18)

## Summary
- 3004 nodes · 10998 edges · 135 communities (111 shown, 24 thin omitted)
- Extraction: 89% EXTRACTED · 11% INFERRED · 0% AMBIGUOUS · INFERRED: 1155 edges (avg confidence: 0.85)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `105be0a2`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- .of
- EngineTest.php
- Decimal
- Instrument
- SimulatedVenue
- .parse
- MarketType
- Position
- .call
- SimulatedVenueTest.php
- .times
- SimulatedPerpVenueTest.php
- Candle
- QualityCheckTest.php
- IndicatorsTest.php
- ForwardingVenue
- Node
- .of
- App\Strategy\Dsl\Position
- BinanceArchive
- LedgerTest.php
- Illuminate\Database\Schema\Blueprint
- generate.py
- ClientOrderId
- StrategyContext
- .get
- ExitReason
- Node
- OrderRole
- .measure
- EnginePerpTest.php
- Symbol
- SimClock
- DecimalMathTest.php
- Engine
- App\Strategy\Dsl\Document\DocumentChecker
- Timeframe
- BackfillTest.php
- DecimalMathTest.php
- Money
- .plus
- ema-cross-scalp-long.strategy.yaml
- OrderManagerTest.php
- DocumentChecker.php
- sources
- User
- .handle
- package.json
- App\Support\Timeframe
- BinanceRestClientTest.php
- ema-cross-scalp-short.strategy.yaml
- CandleStream
- TokensTest.php
- PhrityTransport
- postgres
- InstrumentRules
- Percent
- App\Strategy\Dsl\Document\DocumentChecker
- .sqrt
- GlobalsTest.php
- RunBacktestCommandTest.php
- Asset
- Carbon\CarbonImmutable
- QualityCheck
- RiskPolicy
- EngineRecord
- IndicatorReferenceTest.php
- Ledger
- CandleRepositoryTest.php
- .minus
- YamlMap
- .now
- CanonicalJson
- scripts
- .klines
- Group
- TypeChecker
- composer.json
- jobs
- postgres
- CandleFeedTest.php
- Window
- CompiledStrategyTest.php
- App\Support\Number\Decimal
- pint/composer.json
- Clock
- Quantity
- bootstrap/app.php
- require-dev
- scripts-descriptions
- App\Support\Side
- OrderStatus
- config
- uses
- test
- with
- CostModel
- run
- EngineMode
- psr-4
- require
- DatabaseSeeder
- steps
- steps
- lint
- ValueIndicator
- extra
- Parser
- ExchangeVerificationTest.php
- CompiledStrategyTest.php
- .onCandle
- CandleStreamTest.php
- .seconds
- PerformanceMetrics
- MinuteCandles
- Rsi
- .push
- FundingCarry
- ParserTest.php
- Money
- .__construct
- .fromStrings

## God Nodes (most connected - your core abstractions)
1. `Decimal` - 336 edges
2. `Symbol` - 200 edges
3. `Side` - 183 edges
4. `Candle` - 112 edges
5. `Price` - 109 edges
6. `Timeframe` - 100 edges
7. `SimulatedVenue` - 68 edges
8. `Position` - 68 edges
9. `MarketType` - 67 edges
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
- `runIndicator()` --calls--> `Atr`  [INFERRED]
  tests/Unit/Strategy/IndicatorReferenceTest.php → app/Strategy/Indicators/Atr.php

## Import Cycles
- None detected.

## Communities (135 total, 24 thin omitted)

### Community 0 - ".of"
Cohesion: 0.15
Nodes (46): RiskEvent, RiskEventKind, DailyLossHalt, DrawdownFlatten, DrawdownHalt, WeeklyLossHalt, RiskState, account() (+38 more)

### Community 1 - "EngineTest.php"
Cohesion: 0.13
Nodes (27): ScriptedStrategy, {closure#10}(), {closure#13}(), {closure#14}(), {closure#16}(), {closure#17}(), {closure#18}(), {closure#2}() (+19 more)

### Community 2 - "Decimal"
Cohesion: 0.08
Nodes (20): Balance, RoundTrip, {closure#10}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}() (+12 more)

### Community 3 - "Instrument"
Cohesion: 0.15
Nodes (4): {closure#1}(), Instrument, ExchangeVerification, VerificationReport

### Community 4 - "SimulatedVenue"
Cohesion: 0.13
Nodes (4): Liquidity, Maker, Taker, SimulatedVenue

### Community 5 - ".parse"
Cohesion: 0.10
Nodes (38): FundingRate, MovingAverageTrend, Parameters, PositionView, carryDecisions(), {closure#10}(), {closure#11}(), {closure#12}() (+30 more)

### Community 6 - "MarketType"
Cohesion: 0.08
Nodes (19): BinanceRestClient, Json, RequestFailed, SyncInstruments, InstrumentDefinition, InstrumentStatus, Delisted, Halted (+11 more)

### Community 7 - "Position"
Cohesion: 0.09
Nodes (13): Argument, BoolLiteral, Call, DurationLiteral, Identifier, NumberLiteral, Parameter, PercentLiteral (+5 more)

### Community 8 - ".call"
Cohesion: 0.12
Nodes (4): CompiledStrategy, {closure#34}(), Compiler, Program

### Community 9 - "SimulatedVenueTest.php"
Cohesion: 0.18
Nodes (41): btc(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#15}(), {closure#16}() (+33 more)

### Community 10 - ".times"
Cohesion: 0.09
Nodes (23): AccountView, RejectionReason, BelowMinimum, DuplicateIntent, GrossExposure, Halted, InvalidStop, InvalidTarget (+15 more)

### Community 11 - "SimulatedPerpVenueTest.php"
Cohesion: 0.21
Nodes (21): {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#15}(), {closure#18}(), {closure#19}() (+13 more)

### Community 12 - "Candle"
Cohesion: 0.14
Nodes (31): {closure#1}(), {closure#2}(), {closure#1}(), {closure#3}(), Candle, {closure#12}(), {closure#13}(), candleWith() (+23 more)

### Community 13 - "QualityCheckTest.php"
Cohesion: 0.19
Nodes (16): {closure#2}(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#2}(), {closure#4}() (+8 more)

### Community 14 - "IndicatorsTest.php"
Cohesion: 0.14
Nodes (19): {closure#47}(), Atr, Ema, Sma, bar(), {closure#1}(), {closure#12}(), {closure#13}() (+11 more)

### Community 15 - "ForwardingVenue"
Cohesion: 0.14
Nodes (3): ForwardingVenue, {closure#22}(), OverReportingVenue

### Community 16 - "Node"
Cohesion: 0.09
Nodes (20): Lexer, Token, TokenKind, Colon, Comma, Dot, Duration, End (+12 more)

### Community 17 - ".of"
Cohesion: 0.16
Nodes (18): {closure#2}(), {closure#1}(), usdt(), {closure#11}(), {closure#3}(), {closure#6}(), {closure#7}(), {closure#8}() (+10 more)

### Community 18 - "App\Strategy\Dsl\Position"
Cohesion: 0.13
Nodes (10): DslError, YamlList, YamlReader, YamlScalar, YamlValue, {closure#1}(), {closure#2}(), {closure#3}() (+2 more)

### Community 19 - "BinanceArchive"
Cohesion: 0.11
Nodes (6): BinanceArchive, BinanceEndpoints, SystemSleeper, AppServiceProvider, {closure#1}(), {closure#2}()

### Community 20 - "LedgerTest.php"
Cohesion: 0.23
Nodes (28): Side, Buy, Sell, balanceOf(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}() (+20 more)

### Community 21 - "Illuminate\Database\Schema\Blueprint"
Cohesion: 0.10
Nodes (14): {closure#1}(), {closure#2}(), {closure#3}(), {closure#1}(), {closure#2}(), {closure#1}(), {closure#2}(), {closure#3}() (+6 more)

### Community 22 - "generate.py"
Cohesion: 0.14
Nodes (27): atr(), bars(), bb(), change(), dmi(), donchian(), ema(), highest() (+19 more)

### Community 23 - "ClientOrderId"
Cohesion: 0.13
Nodes (16): ClientOrderId, OcoRequest, OrderRequest, OrderType, Limit, Market, StopMarket, TakeProfitMarket (+8 more)

### Community 24 - "StrategyContext"
Cohesion: 0.08
Nodes (17): BuyAndHold, DonchianBreakout, BuildableStrategy, Strategy, Close, Intent, OpenLong, OpenShort (+9 more)

### Community 25 - ".get"
Cohesion: 0.19
Nodes (5): ArchiveMissing, ChecksumMismatch, BinanceRequestFailed, LocationRestricted, RateLimited

### Community 26 - "ExitReason"
Cohesion: 0.17
Nodes (22): ExitReason, Flatten, Liquidation, Signal, Stop, Target, {closure#1}(), {closure#10}() (+14 more)

### Community 27 - "Node"
Cohesion: 0.21
Nodes (12): Lookback, Node, OnTimeframe, Unary, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}() (+4 more)

### Community 28 - "OrderRole"
Cohesion: 0.10
Nodes (15): Venue, OrderAck, OrderRole, Entry, Exit, Flatten, Stop, Target (+7 more)

### Community 29 - ".measure"
Cohesion: 0.16
Nodes (6): EquityTracker, Performance, {closure#1}(), {closure#2}(), {closure#5}(), trackedTwoDays()

### Community 30 - "EnginePerpTest.php"
Cohesion: 0.30
Nodes (13): {closure#10}(), {closure#11}(), {closure#14}(), {closure#5}(), {closure#6}(), {closure#7}(), {closure#8}(), {closure#9}() (+5 more)

### Community 31 - "Symbol"
Cohesion: 0.05
Nodes (16): Income, IncomeKind, Funding, Liquidation, VenuePosition, {closure#4}(), {closure#6}(), {closure#7}() (+8 more)

### Community 32 - "SimClock"
Cohesion: 0.16
Nodes (11): SimClock, {closure#1}(), {closure#1}(), {closure#2}(), {closure#3}(), definition(), {closure#1}(), {closure#2}() (+3 more)

### Community 33 - "DecimalMathTest.php"
Cohesion: 0.07
Nodes (45): {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#2}(), {closure#3}(), {closure#1}(), {closure#1}() (+37 more)

### Community 34 - "Engine"
Cohesion: 0.13
Nodes (3): IntentRecord, Engine, ReconciliationMismatch

### Community 35 - "App\Strategy\Dsl\Document\DocumentChecker"
Cohesion: 0.14
Nodes (16): CompileStrategy, CheckResult, {closure#1}(), PolicyLimits, {closure#1}(), checkDocument(), {closure#1}(), {closure#2}() (+8 more)

### Community 36 - "Timeframe"
Cohesion: 0.15
Nodes (22): CandleAggregator, {closure#8}(), Timeframe, FifteenMinutes, FiveMinutes, FourHours, OneDay, OneHour (+14 more)

### Community 37 - "BackfillTest.php"
Cohesion: 0.14
Nodes (13): {closure#11}(), {closure#14}(), {closure#15}(), {closure#16}(), {closure#4}(), {closure#6}(), {closure#7}(), {closure#8}() (+5 more)

### Community 38 - "DecimalMathTest.php"
Cohesion: 0.21
Nodes (10): DecimalMath, {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#4}(), {closure#6}() (+2 more)

### Community 39 - "Money"
Cohesion: 0.09
Nodes (22): FeeSchedule, CandleFeed, Backtest, {closure#1}(), RunDefinition, ReportBuilder, {closure#2}(), {closure#3}() (+14 more)

### Community 40 - ".plus"
Cohesion: 0.15
Nodes (6): PerpPosition, {closure#1}(), {closure#2}(), {closure#3}(), Perpetual, {closure#16}()

### Community 41 - "ema-cross-scalp-long.strategy.yaml"
Cohesion: 0.06
Nodes (33): entries, long, exits, stop_loss, take_profit, time_stop, when, fails_when (+25 more)

### Community 42 - "OrderManagerTest.php"
Cohesion: 0.33
Nodes (15): btcUsdt(), {closure#10}(), {closure#15}(), {closure#16}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}() (+7 more)

### Community 43 - "DocumentChecker.php"
Cohesion: 0.08
Nodes (29): {closure#3}(), {closure#4}(), ParameterSpec, FunctionSpec, {closure#1}(), {closure#2}(), Library, Scope (+21 more)

### Community 44 - "sources"
Cohesion: 0.09
Nodes (22): secret, type, sources, auth, channels, connector, exchange, id (+14 more)

### Community 46 - ".handle"
Cohesion: 0.09
Nodes (8): {closure#1}(), {closure#1}(), {closure#2}(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#5}(), RunBacktest

### Community 47 - "package.json"
Cohesion: 0.13
Nodes (20): devDependencies, concurrently@^10.0.3, laravel-vite-plugin@^3.1, tailwindcss@^4.0.0, @tailwindcss/vite@^4.0.0, vite@^8.0.0, optionalDependencies, @laravel/multiplex@^0.4.1 (+12 more)

### Community 48 - "App\Support\Timeframe"
Cohesion: 0.11
Nodes (11): InstrumentDirectory, StoredInstruments, {closure#1}(), {closure#2}(), {closure#3}(), {closure#5}(), {closure#6}(), CarryTemplate (+3 more)

### Community 49 - "BinanceRestClientTest.php"
Cohesion: 0.12
Nodes (18): {closure#13}(), {closure#5}(), binanceClient(), {closure#1}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#2}() (+10 more)

### Community 50 - "ema-cross-scalp-short.strategy.yaml"
Cohesion: 0.06
Nodes (33): entries, short, exits, stop_loss, take_profit, time_stop, when, fails_when (+25 more)

### Community 51 - "CandleStream"
Cohesion: 0.12
Nodes (4): BackfillStep, UpsertResult, CandleStream, StreamTransport

### Community 52 - "TokensTest.php"
Cohesion: 0.31
Nodes (7): {closure#3}(), {closure#4}(), {closure#5}(), contrastRatio(), contrastRules(), relativeLuminance(), themeTokens()

### Community 53 - "PhrityTransport"
Cohesion: 0.12
Nodes (3): PhrityTransport, StreamFailed, FakeTransport

### Community 54 - "postgres"
Cohesion: 0.10
Nodes (19): POSTGRES_DB, POSTGRES_PASSWORD, POSTGRES_USER, interval, retries, test, timeout, environment (+11 more)

### Community 55 - "InstrumentRules"
Cohesion: 0.13
Nodes (10): SimulatedFuturesBook, InstrumentRules, RulesHistory, {closure#4}(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}() (+2 more)

### Community 57 - "App\Strategy\Dsl\Document\DocumentChecker"
Cohesion: 0.11
Nodes (12): {closure#5}(), DslWriter, ParamKind, Anchor, Count, Duration, Number, Percent (+4 more)

### Community 58 - ".sqrt"
Cohesion: 0.27
Nodes (10): Statistics, {closure#3}(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}() (+2 more)

### Community 59 - "GlobalsTest.php"
Cohesion: 0.22
Nodes (4): Controller, {closure#3}(), {closure#4}(), {closure#5}()

### Community 60 - "RunBacktestCommandTest.php"
Cohesion: 0.13
Nodes (19): {closure#1}(), InstrumentFilter, BacktestRun, backtestFixture(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}() (+11 more)

### Community 61 - "Asset"
Cohesion: 0.18
Nodes (5): Marks, Asset, balances(), {closure#25}(), {closure#9}()

### Community 62 - "Carbon\CarbonImmutable"
Cohesion: 0.09
Nodes (16): ArchivePeriod, {closure#3}(), {closure#1}(), WallClock, archiveBytes(), {closure#1}(), {closure#2}(), {closure#3}() (+8 more)

### Community 63 - "QualityCheck"
Cohesion: 0.10
Nodes (9): {closure#2}(), {closure#4}(), DatabaseCandleFeed, {closure#1}(), Rows, QualityCheck, QualityReport, {closure#10}() (+1 more)

### Community 64 - "RiskPolicy"
Cohesion: 0.09
Nodes (29): RiskProfile, Account, Benchmark, {closure#1}(), RiskPolicy, Participant, Report, btcMinutes() (+21 more)

### Community 65 - "EngineRecord"
Cohesion: 0.16
Nodes (5): DecisionLog, DecisionRecord, EngineRecord, {closure#2}(), {closure#1}()

### Community 66 - "IndicatorReferenceTest.php"
Cohesion: 0.08
Nodes (14): {closure#1}(), Hma, Macd, Wma, assertMatchesReference(), {closure#10}(), {closure#11}(), {closure#13}() (+6 more)

### Community 67 - "Ledger"
Cohesion: 0.17
Nodes (11): InMemoryDecisionLog, Ledger, LedgerViolation, {closure#1}(), {closure#2}(), BacktestResult, ParticipantResult, {closure#23}() (+3 more)

### Community 68 - "CandleRepositoryTest.php"
Cohesion: 0.28
Nodes (11): candleStore(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}() (+3 more)

### Community 71 - ".now"
Cohesion: 0.10
Nodes (11): BackfillReport, BackfillMarketData, CheckMarketData, SelectsInstruments, {closure#1}(), StreamCandles, VerifyMarketData, Universe (+3 more)

### Community 72 - "CanonicalJson"
Cohesion: 0.30
Nodes (6): CanonicalJson, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}()

### Community 73 - "scripts"
Cohesion: 0.14
Nodes (14): scripts, analyse, dev, format, mutate, post-autoload-dump, post-update-cmd, pre-package-uninstall (+6 more)

### Community 74 - ".klines"
Cohesion: 0.09
Nodes (15): {closure#1}(), {closure#2}(), EpochTime, ExchangeNumber, KlineMessage, {closure#1}(), {closure#2}(), {closure#3}() (+7 more)

### Community 75 - "Group"
Cohesion: 0.20
Nodes (9): Group, Agent, Exit, Indicator, Order, Perpetual, Position, Price (+1 more)

### Community 76 - "TypeChecker"
Cohesion: 0.13
Nodes (13): {closure#7}(), {closure#8}(), {closure#9}(), EntrySpec, ExitSpec, Expression, TypeChecker, {closure#1}() (+5 more)

### Community 77 - "composer.json"
Cohesion: 0.17
Nodes (11): autoload-dev, psr-4, description, keywords, license, minimum-stability, name, prefer-stable (+3 more)

### Community 78 - "jobs"
Cohesion: 0.17
Nodes (11): concurrency, cancel-in-progress, group, jobs, name, on, pull_request, push (+3 more)

### Community 79 - "postgres"
Cohesion: 0.17
Nodes (12): POSTGRES_DB, POSTGRES_PASSWORD, POSTGRES_USER, env, image, options, ports, image (+4 more)

### Community 80 - "CandleFeedTest.php"
Cohesion: 0.44
Nodes (11): {closure#1}(), {closure#12}(), {closure#13}(), {closure#2}(), {closure#4}(), {closure#5}(), {closure#8}(), {closure#9}() (+3 more)

### Community 81 - "Window"
Cohesion: 0.09
Nodes (10): {closure#46}(), Change, DonchianChannel, Highest, Lowest, Roc, Stochastic, Vwma (+2 more)

### Community 82 - "CompiledStrategyTest.php"
Cohesion: 0.12
Nodes (12): RenamedStrategy, aggregatedBars(), {closure#11}(), {closure#13}(), {closure#16}(), {closure#4}(), {closure#5}(), {closure#7}() (+4 more)

### Community 83 - "App\Support\Number\Decimal"
Cohesion: 0.09
Nodes (38): {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#15}(), {closure#16}() (+30 more)

### Community 84 - "pint/composer.json"
Cohesion: 0.20
Nodes (9): config, sort-packages, description, license, name, require, laravel/pint, php (+1 more)

### Community 85 - "Clock"
Cohesion: 0.10
Nodes (14): Backfill, Sleeper, CandleRepository, CandleSource, Archive, Rest, Stream, FundingRateRepository (+6 more)

### Community 87 - "bootstrap/app.php"
Cohesion: 0.33
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

### Community 88 - "require-dev"
Cohesion: 0.22
Nodes (9): require-dev, fakerphp/faker, larastan/larastan, laravel/pail, laravel/pao, mockery/mockery, nunomaduro/collision, pestphp/pest (+1 more)

### Community 89 - "scripts-descriptions"
Cohesion: 0.22
Nodes (9): scripts-descriptions, analyse, format, mutate, test, test:llm, test:network, test:testnet (+1 more)

### Community 91 - "OrderStatus"
Cohesion: 0.16
Nodes (14): OrderStatus, Cancelled, Expired, Filled, New, PartiallyFilled, TrackedOrder, {closure#1}() (+6 more)

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

### Community 96 - "CostModel"
Cohesion: 0.22
Nodes (5): CostModel, {closure#40}(), {closure#44}(), {closure#45}(), {closure#46}()

### Community 97 - "run"
Cohesion: 0.33
Nodes (6): name, runs-on, steps, audit, run, steps

### Community 98 - "EngineMode"
Cohesion: 0.33
Nodes (5): EngineMode, Backtest, Live, Paper, Testnet

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

### Community 106 - "ValueIndicator"
Cohesion: 0.13
Nodes (6): {closure#35}(), Bollinger, RealizedVolatility, StandardDeviation, ValueIndicator, {closure#16}()

### Community 108 - "extra"
Cohesion: 0.67
Nodes (3): extra, laravel, dont-discover

### Community 120 - "ExchangeVerificationTest.php"
Cohesion: 0.26
Nodes (12): {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}() (+4 more)

### Community 121 - "CompiledStrategyTest.php"
Cohesion: 0.11
Nodes (10): {closure#37}(), BarIndicator, DirectionalMovement, Keltner, Natr, Supertrend, Vwap, {closure#2}() (+2 more)

### Community 122 - ".onCandle"
Cohesion: 0.22
Nodes (3): Donchian, {closure#10}(), {closure#15}()

### Community 123 - "CandleStreamTest.php"
Cohesion: 0.23
Nodes (8): {closure#1}(), {closure#2}(), {closure#4}(), {closure#5}(), streamMessages(), streamWith(), {closure#1}(), RecordingSleeper

### Community 124 - ".seconds"
Cohesion: 0.18
Nodes (6): {closure#4}(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}()

### Community 126 - "MinuteCandles"
Cohesion: 0.25
Nodes (4): MinuteCandles, {closure#14}(), {closure#3}(), {closure#7}()

### Community 127 - "Rsi"
Cohesion: 0.12
Nodes (3): Period, Rsi, Wilder

### Community 128 - ".push"
Cohesion: 0.36
Nodes (3): CandleSequenceBroken, {closure#6}(), {closure#2}()

### Community 130 - "ParserTest.php"
Cohesion: 0.36
Nodes (6): AstPrinter, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), sexpr()

### Community 131 - "Money"
Cohesion: 0.14
Nodes (4): Fill, Position, AssetMismatch, Money

### Community 133 - ".fromStrings"
Cohesion: 0.33
Nodes (3): {closure#1}(), {closure#42}(), {closure#12}()

## Knowledge Gaps
- **117 isolated node(s):** `BelowMinimum`, `DuplicateIntent`, `GrossExposure`, `Halted`, `InvalidStop` (+112 more)
  These have ≤1 connection - possible missing edges. (Counts symbols only; 554 node(s) total have ≤1 connection when file, concept and rationale nodes are included.)
- **24 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `Decimal` connect `Decimal` to `.of`, `FundingCarry`, `EngineTest.php`, `Money`, `SimulatedVenue`, `.__construct`, `MarketType`, `.parse`, `Position`, `SimulatedVenueTest.php`, `.times`, `SimulatedPerpVenueTest.php`, `Candle`, `IndicatorsTest.php`, `ForwardingVenue`, `.of`, `LedgerTest.php`, `ClientOrderId`, `StrategyContext`, `ExitReason`, `Node`, `OrderRole`, `.measure`, `EnginePerpTest.php`, `Symbol`, `SimClock`, `DecimalMathTest.php`, `Engine`, `Timeframe`, `DecimalMathTest.php`, `Money`, `.plus`, `OrderManagerTest.php`, `.handle`, `InstrumentRules`, `Percent`, `App\Strategy\Dsl\Document\DocumentChecker`, `.sqrt`, `RunBacktestCommandTest.php`, `Asset`, `Carbon\CarbonImmutable`, `QualityCheck`, `RiskPolicy`, `Ledger`, `.minus`, `CanonicalJson`, `.klines`, `CandleFeedTest.php`, `App\Support\Number\Decimal`, `Clock`, `Quantity`, `App\Support\Side`, `OrderStatus`, `CostModel`, `CompiledStrategyTest.php`, `.onCandle`, `PerformanceMetrics`?**
  _High betweenness centrality (0.146) - this node is a cross-community bridge._
- **What connects `BelowMinimum`, `DuplicateIntent`, `GrossExposure` to the rest of the system?**
  _117 weakly-connected nodes found - possible missing edges._
- **Should `.of` be split into smaller, more focused modules?**
  _Cohesion score 0.14858757062146893 - nodes in this community are weakly interconnected._
- **Why does `Symbol` connect `Symbol` to `.of`, `FundingCarry`, `Decimal`, `Instrument`, `SimulatedVenue`, `Money`, `MarketType`, `.parse`, `EngineTest.php`, `SimulatedVenueTest.php`, `.times`, `SimulatedPerpVenueTest.php`, `QualityCheckTest.php`, `ForwardingVenue`, `.of`, `LedgerTest.php`, `ClientOrderId`, `StrategyContext`, `ExitReason`, `OrderRole`, `EnginePerpTest.php`, `SimClock`, `Engine`, `Timeframe`, `BackfillTest.php`, `Money`, `.plus`, `OrderManagerTest.php`, `.handle`, `App\Support\Timeframe`, `InstrumentRules`, `RunBacktestCommandTest.php`, `Asset`, `RiskPolicy`, `EngineRecord`, `Ledger`, `CandleRepositoryTest.php`, `.now`, `CanonicalJson`, `CandleFeedTest.php`, `CompiledStrategyTest.php`, `Clock`, `OrderStatus`, `ExchangeVerificationTest.php`, `.onCandle`, `CandleStreamTest.php`, `MinuteCandles`?**
  _High betweenness centrality (0.057) - this node is a cross-community bridge._
- **Should `EngineTest.php` be split into smaller, more focused modules?**
  _Cohesion score 0.13232323232323231 - nodes in this community are weakly interconnected._
- **Why does `Timeframe` connect `Timeframe` to `.push`, `FundingCarry`, `EngineTest.php`, `Instrument`, `.__construct`, `.fromStrings`, `MarketType`, `.parse`, `SimulatedVenueTest.php`, `SimulatedPerpVenueTest.php`, `Candle`, `IndicatorsTest.php`, `StrategyContext`, `ExitReason`, `Node`, `EnginePerpTest.php`, `OrderManagerTest.php`, `.handle`, `App\Support\Timeframe`, `CandleStream`, `App\Strategy\Dsl\Document\DocumentChecker`, `Carbon\CarbonImmutable`, `QualityCheck`, `CandleRepositoryTest.php`, `YamlMap`, `.klines`, `CandleFeedTest.php`, `CompiledStrategyTest.php`, `Clock`, `CompiledStrategyTest.php`, `.onCandle`, `CandleStreamTest.php`, `.seconds`?**
  _High betweenness centrality (0.030) - this node is a cross-community bridge._
- **Should `Decimal` be split into smaller, more focused modules?**
  _Cohesion score 0.07922705314009662 - nodes in this community are weakly interconnected._