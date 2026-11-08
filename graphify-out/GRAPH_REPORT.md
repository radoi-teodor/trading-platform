# Graph Report - trading-platform  (2026-11-08)

## Corpus Check
- 301 files · ~116,249 words
- Verdict: corpus is large enough that graph structure adds value.
- Unclassified: 92 file(s) not represented in the graph (top: .zip 34, .CHECKSUM 34, (none) 18)
- WARNING: 92 of 417 files seen (22%) have no supported extractor and are missing from this graph entirely. It may look complete while representing only part of the codebase.

## Summary
- 2417 nodes · 9111 edges · 120 communities (99 shown, 21 thin omitted)
- Extraction: 89% EXTRACTED · 11% INFERRED · 0% AMBIGUOUS · INFERRED: 1016 edges (avg confidence: 0.85)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `5c756c00`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- .of
- EngineTest.php
- Decimal
- Instrument
- SimulatedVenue
- Strategy/BaselinesTest.php
- BinanceRestClient
- Position
- Clock
- SimulatedVenueTest.php
- .times
- SimulatedPerpVenueTest.php
- Candle
- ExchangeVerificationTest.php
- IndicatorsTest.php
- ForwardingVenue
- TokenKind
- .of
- BinanceArchive.php
- BinanceArchive
- LedgerTest.php
- Illuminate\Database\Schema\Blueprint
- RiskPolicy
- OrderRequest
- Symbol
- Backfill.php
- ExitReason
- StrategyContext
- OrderRole
- .measure
- EnginePerpTest.php
- Carbon\CarbonImmutable
- SimClock
- DecimalMathTest.php
- Engine
- Asset
- Timeframe
- BackfillTest.php
- OpenLong
- BacktestTest.php
- Venue
- .walk
- OrderManagerTest.php
- Node
- sources
- User
- .handle
- package.json
- Money
- BinanceRestClientTest.php
- InMemoryDecisionLog
- ExchangeVerificationTest.php
- .expect
- PhrityTransport
- postgres
- RulesHistory
- Report
- MovingAverageTrend
- Parser
- CandleStreamTest.php
- RunBacktestCommandTest.php
- Stringable
- ArchiveImportTest.php
- Rows
- .run
- SimulatedVenue
- Marks
- Ledger
- CandleRepositoryTest.php
- .zero
- TrackedOrder
- Universe
- CanonicalJson
- scripts
- .parse
- VenuePosition
- DslError
- composer.json
- jobs
- postgres
- CandleFeedTest.php
- .parse
- StrategyIdentity
- Strategy
- pint/composer.json
- .now
- FundingCarry
- bootstrap/app.php
- require-dev
- scripts-descriptions
- .__construct
- .build
- config
- uses
- test
- with
- .__construct
- run
- QualityReport
- psr-4
- require
- DatabaseSeeder
- steps
- steps
- lint
- HoldStrategy
- extra
- Illuminate\Support\Facades\Http

## God Nodes (most connected - your core abstractions)
1. `Decimal` - 354 edges
2. `Symbol` - 208 edges
3. `Side` - 183 edges
4. `Candle` - 114 edges
5. `Price` - 109 edges
6. `Timeframe` - 95 edges
7. `SimulatedVenue` - 68 edges
8. `Position` - 68 edges
9. `MarketType` - 66 edges
10. `InstrumentRules` - 64 edges

## Surprising Connections (you probably didn't know these)
- `{closure#2}()` --references--> `EngineRecord`  [EXTRACTED]
  tests/Unit/Execution/EnginePerpTest.php → app/Execution/Data/EngineRecord.php
- `{closure#1}()` --references--> `EngineRecord`  [EXTRACTED]
  tests/Unit/Execution/EngineTest.php → app/Execution/Data/EngineRecord.php
- `{closure#4}()` --references--> `Candle`  [EXTRACTED]
  tests/Feature/Market/BinanceRestClientTest.php → app/Market/Data/Candle.php
- `{closure#7}()` --references--> `Candle`  [EXTRACTED]
  tests/Feature/Market/CandleRepositoryTest.php → app/Market/Data/Candle.php
- `{closure#12}()` --references--> `Candle`  [EXTRACTED]
  tests/Unit/Market/CandleAggregatorTest.php → app/Market/Data/Candle.php

## Import Cycles
- None detected.

## Communities (120 total, 21 thin omitted)

### Community 0 - ".of"
Cohesion: 0.07
Nodes (72): AccountView, RejectionReason, BelowMinimum, DuplicateIntent, GrossExposure, Halted, InvalidStop, InvalidTarget (+64 more)

### Community 1 - "EngineTest.php"
Cohesion: 0.15
Nodes (24): ScriptedStrategy, {closure#1}(), {closure#10}(), {closure#13}(), {closure#14}(), {closure#16}(), {closure#17}(), {closure#2}() (+16 more)

### Community 2 - "Decimal"
Cohesion: 0.06
Nodes (25): {closure#1}(), {closure#2}(), RoundTrip, {closure#4}(), {closure#10}(), {closure#2}(), {closure#3}(), {closure#4}() (+17 more)

### Community 3 - "Instrument"
Cohesion: 0.07
Nodes (15): BackfillStep, CandleRepository, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), CandleSource, Archive (+7 more)

### Community 4 - "SimulatedVenue"
Cohesion: 0.07
Nodes (22): ClientOrderId, Liquidity, Maker, Taker, OrderStatus, Cancelled, Expired, Filled (+14 more)

### Community 5 - "Strategy/BaselinesTest.php"
Cohesion: 0.25
Nodes (14): carryDecisions(), {closure#13}(), {closure#19}(), {closure#21}(), {closure#22}(), {closure#23}(), {closure#24}(), {closure#27}() (+6 more)

### Community 6 - "BinanceRestClient"
Cohesion: 0.09
Nodes (19): BinanceRestClient, {closure#1}(), Json, SyncInstruments, InstrumentDefinition, InstrumentStatus, Delisted, Halted (+11 more)

### Community 7 - "Position"
Cohesion: 0.07
Nodes (12): Argument, BoolLiteral, Call, DurationLiteral, Identifier, NumberLiteral, Parameter, PercentLiteral (+4 more)

### Community 8 - "Clock"
Cohesion: 0.14
Nodes (7): FundingRateRepository, Heartbeats, {closure#1}(), InstrumentSync, SyncResult, CandleStream, Clock

### Community 9 - "SimulatedVenueTest.php"
Cohesion: 0.16
Nodes (46): OcoRequest, Side, Buy, Sell, btc(), {closure#1}(), {closure#10}(), {closure#11}() (+38 more)

### Community 10 - ".times"
Cohesion: 0.10
Nodes (4): {closure#3}(), Percent, Perpetual, {closure#5}()

### Community 11 - "SimulatedPerpVenueTest.php"
Cohesion: 0.19
Nodes (23): {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#15}(), {closure#18}() (+15 more)

### Community 12 - "Candle"
Cohesion: 0.13
Nodes (31): {closure#1}(), Candle, {closure#1}(), {closure#2}(), {closure#1}(), {closure#42}(), candleWith(), {closure#1}() (+23 more)

### Community 13 - "ExchangeVerificationTest.php"
Cohesion: 0.21
Nodes (16): {closure#2}(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#2}(), {closure#4}() (+8 more)

### Community 14 - "IndicatorsTest.php"
Cohesion: 0.10
Nodes (20): Atr, Donchian, Ema, Sma, {closure#1}(), {closure#10}(), {closure#12}(), {closure#13}() (+12 more)

### Community 15 - "ForwardingVenue"
Cohesion: 0.17
Nodes (3): ForwardingVenue, {closure#22}(), OverReportingVenue

### Community 16 - "TokenKind"
Cohesion: 0.09
Nodes (20): Lexer, Token, TokenKind, Colon, Comma, Dot, Duration, End (+12 more)

### Community 17 - ".of"
Cohesion: 0.16
Nodes (18): AssetMismatch, {closure#2}(), {closure#1}(), usdt(), {closure#11}(), {closure#6}(), {closure#7}(), {closure#1}() (+10 more)

### Community 18 - "BinanceArchive.php"
Cohesion: 0.09
Nodes (12): {closure#2}(), EpochTime, ExchangeNumber, FundingRate, {closure#1}(), {closure#2}(), {closure#1}(), {closure#1}() (+4 more)

### Community 19 - "BinanceArchive"
Cohesion: 0.13
Nodes (5): BinanceArchive, BinanceEndpoints, AppServiceProvider, {closure#1}(), {closure#2}()

### Community 20 - "LedgerTest.php"
Cohesion: 0.22
Nodes (25): balanceOf(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#15}(), {closure#16}() (+17 more)

### Community 21 - "Illuminate\Database\Schema\Blueprint"
Cohesion: 0.10
Nodes (14): {closure#1}(), {closure#2}(), {closure#3}(), {closure#1}(), {closure#2}(), {closure#1}(), {closure#2}(), {closure#3}() (+6 more)

### Community 22 - "RiskPolicy"
Cohesion: 0.18
Nodes (10): RiskProfile, Account, Benchmark, {closure#1}(), RiskPolicy, {closure#1}(), {closure#2}(), {closure#3}() (+2 more)

### Community 23 - "OrderRequest"
Cohesion: 0.21
Nodes (6): OrderRequest, OrderType, Limit, Market, StopMarket, TakeProfitMarket

### Community 24 - "Symbol"
Cohesion: 0.10
Nodes (9): DecisionLog, DecisionRecord, {closure#6}(), {closure#7}(), {closure#5}(), MoveStop, OpenCarry, Symbol (+1 more)

### Community 25 - "Backfill.php"
Cohesion: 0.07
Nodes (15): ArchiveMissing, ChecksumMismatch, {closure#1}(), {closure#2}(), {closure#3}(), BinanceRequestFailed, LocationRestricted, RateLimited (+7 more)

### Community 26 - "ExitReason"
Cohesion: 0.15
Nodes (24): ExitReason, Flatten, Liquidation, Signal, Stop, Target, {closure#1}(), {closure#10}() (+16 more)

### Community 27 - "StrategyContext"
Cohesion: 0.09
Nodes (9): Close, Intent, OpenShort, StrategyContext, Subscription, EnterOnceStrategy, {closure#1}(), {closure#18}() (+1 more)

### Community 28 - "OrderRole"
Cohesion: 0.18
Nodes (9): OrderAck, OrderRole, Entry, Exit, Flatten, Stop, Target, OrderManager (+1 more)

### Community 29 - ".measure"
Cohesion: 0.11
Nodes (16): EquityTracker, Performance, Statistics, {closure#1}(), {closure#2}(), {closure#3}(), {closure#5}(), trackedTwoDays() (+8 more)

### Community 30 - "EnginePerpTest.php"
Cohesion: 0.26
Nodes (15): {closure#10}(), {closure#11}(), {closure#14}(), {closure#2}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}() (+7 more)

### Community 31 - "Carbon\CarbonImmutable"
Cohesion: 0.07
Nodes (12): ProtectionPlan, {closure#1}(), MinuteCandles, Fill, PerpPosition, Position, BacktestResult, PositionView (+4 more)

### Community 32 - "SimClock"
Cohesion: 0.16
Nodes (10): SimClock, WallClock, {closure#1}(), {closure#23}(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}() (+2 more)

### Community 33 - "DecimalMathTest.php"
Cohesion: 0.10
Nodes (33): DecimalMath, InvalidNumber, {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}() (+25 more)

### Community 34 - "Engine"
Cohesion: 0.23
Nodes (4): EngineRecord, IntentRecord, Engine, ReconciliationMismatch

### Community 35 - "Asset"
Cohesion: 0.15
Nodes (3): Balance, Asset, balances()

### Community 36 - "Timeframe"
Cohesion: 0.11
Nodes (13): Timeframe, FifteenMinutes, FiveMinutes, FourHours, OneDay, OneHour, OneMinute, OneWeek (+5 more)

### Community 37 - "BackfillTest.php"
Cohesion: 0.14
Nodes (15): {closure#11}(), {closure#14}(), {closure#15}(), {closure#16}(), {closure#4}(), {closure#6}(), {closure#7}(), {closure#8}() (+7 more)

### Community 38 - "OpenLong"
Cohesion: 0.13
Nodes (4): BuyAndHold, DonchianBreakout, BuildableStrategy, OpenLong

### Community 39 - "BacktestTest.php"
Cohesion: 0.14
Nodes (17): FeeSchedule, CostModel, CandleFeed, Backtest, {closure#1}(), RunDefinition, InMemoryCandleFeed, Policies (+9 more)

### Community 40 - "Venue"
Cohesion: 0.13
Nodes (6): Venue, Income, IncomeKind, Funding, Liquidation, {closure#3}()

### Community 41 - ".walk"
Cohesion: 0.19
Nodes (18): CandleAggregator, CandleSequenceBroken, {closure#6}(), CandleFactory, aggregate(), {closure#1}(), {closure#10}(), {closure#11}() (+10 more)

### Community 42 - "OrderManagerTest.php"
Cohesion: 0.36
Nodes (14): btcUsdt(), {closure#10}(), {closure#15}(), {closure#16}(), {closure#2}(), {closure#4}(), {closure#5}(), {closure#6}() (+6 more)

### Community 43 - "Node"
Cohesion: 0.13
Nodes (13): Lookback, Node, OnTimeframe, Unary, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}() (+5 more)

### Community 44 - "sources"
Cohesion: 0.09
Nodes (22): secret, type, sources, auth, channels, connector, exchange, id (+14 more)

### Community 47 - "package.json"
Cohesion: 0.13
Nodes (20): devDependencies, concurrently@^10.0.3, laravel-vite-plugin@^3.1, tailwindcss@^4.0.0, @tailwindcss/vite@^4.0.0, vite@^8.0.0, optionalDependencies, @laravel/multiplex@^0.4.1 (+12 more)

### Community 48 - "Money"
Cohesion: 0.20
Nodes (7): {closure#1}(), {closure#2}(), Participant, Money, {closure#2}(), {closure#3}(), {closure#5}()

### Community 49 - "BinanceRestClientTest.php"
Cohesion: 0.15
Nodes (18): {closure#13}(), {closure#5}(), binanceClient(), {closure#1}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#2}() (+10 more)

### Community 50 - "InMemoryDecisionLog"
Cohesion: 0.14
Nodes (10): EngineMode, Backtest, Live, Paper, Testnet, InMemoryDecisionLog, {closure#1}(), {closure#2}() (+2 more)

### Community 51 - "ExchangeVerificationTest.php"
Cohesion: 0.18
Nodes (11): {closure#1}(), {closure#12}(), {closure#13}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}() (+3 more)

### Community 52 - ".expect"
Cohesion: 0.08
Nodes (23): Controller, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#3}(), {closure#4}(), {closure#5}() (+15 more)

### Community 53 - "PhrityTransport"
Cohesion: 0.09
Nodes (4): PhrityTransport, StreamFailed, StreamTransport, FakeTransport

### Community 54 - "postgres"
Cohesion: 0.10
Nodes (19): POSTGRES_DB, POSTGRES_PASSWORD, POSTGRES_USER, interval, retries, test, timeout, environment (+11 more)

### Community 55 - "RulesHistory"
Cohesion: 0.32
Nodes (7): RulesHistory, {closure#41}(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), rules()

### Community 56 - "Report"
Cohesion: 0.36
Nodes (7): {closure#1}(), {closure#3}(), goldenMinutes(), goldenRates(), perpetualReferencePrice(), referenceMean(), reportTrades()

### Community 57 - "MovingAverageTrend"
Cohesion: 0.16
Nodes (8): MovingAverageTrend, {closure#10}(), {closure#11}(), {closure#12}(), {closure#20}(), {closure#3}(), {closure#8}(), {closure#9}()

### Community 58 - "Parser"
Cohesion: 0.43
Nodes (3): Binary, DslError, Parser

### Community 59 - "CandleStreamTest.php"
Cohesion: 0.23
Nodes (9): Sleeper, SystemSleeper, {closure#1}(), {closure#2}(), {closure#4}(), {closure#5}(), streamMessages(), streamWith() (+1 more)

### Community 60 - "RunBacktestCommandTest.php"
Cohesion: 0.14
Nodes (15): {closure#1}(), InstrumentFilter, BacktestRun, backtestFixture(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}() (+7 more)

### Community 61 - "Stringable"
Cohesion: 0.09
Nodes (3): Period, Quantity, {closure#10}()

### Community 62 - "ArchiveImportTest.php"
Cohesion: 0.29
Nodes (10): archiveBytes(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}() (+2 more)

### Community 63 - "Rows"
Cohesion: 0.28
Nodes (5): DatabaseCandleFeed, {closure#1}(), Rows, {closure#10}(), {closure#11}()

### Community 64 - ".run"
Cohesion: 0.19
Nodes (16): Report, btcMinutes(), {closure#1}(), {closure#2}(), {closure#3}(), referencePrice(), {closure#2}(), {closure#3}() (+8 more)

### Community 65 - "SimulatedVenue"
Cohesion: 0.20
Nodes (4): SimulatedVenue, {closure#12}(), {closure#32}(), {closure#34}()

### Community 66 - "Marks"
Cohesion: 0.29
Nodes (4): Marks, ParticipantResult, {closure#25}(), {closure#9}()

### Community 67 - "Ledger"
Cohesion: 0.36
Nodes (3): Ledger, LedgerViolation, {closure#12}()

### Community 68 - "CandleRepositoryTest.php"
Cohesion: 0.28
Nodes (11): candleStore(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}() (+3 more)

### Community 69 - ".zero"
Cohesion: 0.11
Nodes (5): {closure#44}(), {closure#45}(), {closure#46}(), {closure#47}(), {closure#48}()

### Community 70 - "TrackedOrder"
Cohesion: 0.22
Nodes (9): TrackedOrder, {closure#4}(), {closure#1}(), {closure#2}(), OrderBook, {closure#11}(), {closure#12}(), {closure#13}() (+1 more)

### Community 71 - "Universe"
Cohesion: 0.14
Nodes (7): CheckMarketData, SelectsInstruments, {closure#1}(), StreamCandles, VerifyMarketData, Universe, QualityCheck

### Community 72 - "CanonicalJson"
Cohesion: 0.30
Nodes (6): CanonicalJson, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}()

### Community 73 - "scripts"
Cohesion: 0.14
Nodes (14): scripts, analyse, dev, format, mutate, post-autoload-dump, post-update-cmd, pre-package-uninstall (+6 more)

### Community 74 - ".parse"
Cohesion: 0.33
Nodes (6): KlineMessage, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), streamLines()

### Community 75 - "VenuePosition"
Cohesion: 0.31
Nodes (4): VenuePosition, {closure#1}(), SimulatedPosition, {closure#13}()

### Community 76 - "DslError"
Cohesion: 0.33
Nodes (6): AstPrinter, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), sexpr()

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

### Community 81 - ".parse"
Cohesion: 0.33
Nodes (6): {closure#19}(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}()

### Community 82 - "StrategyIdentity"
Cohesion: 0.44
Nodes (4): StrategyIdentity, {closure#1}(), {closure#2}(), {closure#3}()

### Community 83 - "Strategy"
Cohesion: 0.50
Nodes (3): Strategy, {closure#2}(), {closure#25}()

### Community 84 - "pint/composer.json"
Cohesion: 0.20
Nodes (9): config, sort-packages, description, license, name, require, laravel/pint, php (+1 more)

### Community 85 - ".now"
Cohesion: 0.15
Nodes (4): ArchivePeriod, Backfill, BackfillReport, BackfillMarketData

### Community 86 - "FundingCarry"
Cohesion: 0.24
Nodes (7): FundingCarry, {closure#14}(), {closure#15}(), {closure#16}(), {closure#17}(), {closure#18}(), {closure#26}()

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

## Knowledge Gaps
- **86 isolated node(s):** `Backtest`, `Paper`, `Testnet`, `Live`, `Funding` (+81 more)
  These have ≤1 connection - possible missing edges. (Counts symbols only; 431 node(s) total have ≤1 connection when file, concept and rationale nodes are included.)
- **21 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `Decimal` connect `Decimal` to `.of`, `EngineTest.php`, `Instrument`, `SimulatedVenue`, `Strategy/BaselinesTest.php`, `BinanceRestClient`, `Position`, `Clock`, `SimulatedVenueTest.php`, `.times`, `SimulatedPerpVenueTest.php`, `Candle`, `IndicatorsTest.php`, `ForwardingVenue`, `.of`, `BinanceArchive.php`, `LedgerTest.php`, `RiskPolicy`, `OrderRequest`, `Symbol`, `ExitReason`, `StrategyContext`, `OrderRole`, `.measure`, `EnginePerpTest.php`, `Carbon\CarbonImmutable`, `DecimalMathTest.php`, `Engine`, `Asset`, `Timeframe`, `OpenLong`, `BacktestTest.php`, `Venue`, `.walk`, `OrderManagerTest.php`, `Node`, `.handle`, `Money`, `InMemoryDecisionLog`, `RulesHistory`, `Report`, `RunBacktestCommandTest.php`, `Stringable`, `.run`, `Marks`, `Ledger`, `.zero`, `TrackedOrder`, `Universe`, `CanonicalJson`, `VenuePosition`, `CandleFeedTest.php`, `FundingCarry`, `.__construct`, `.__construct`?**
  _High betweenness centrality (0.209) - this node is a cross-community bridge._
- **What connects `Backtest`, `Paper`, `Testnet` to the rest of the system?**
  _86 weakly-connected nodes found - possible missing edges._
- **Should `.of` be split into smaller, more focused modules?**
  _Cohesion score 0.06524749048113535 - nodes in this community are weakly interconnected._
- **Why does `Symbol` connect `Symbol` to `.of`, `EngineTest.php`, `Decimal`, `Instrument`, `SimulatedVenue`, `Strategy/BaselinesTest.php`, `BinanceRestClient`, `Clock`, `SimulatedVenueTest.php`, `SimulatedPerpVenueTest.php`, `ExchangeVerificationTest.php`, `ForwardingVenue`, `BinanceArchive.php`, `LedgerTest.php`, `OrderRequest`, `ExitReason`, `StrategyContext`, `OrderRole`, `.measure`, `EnginePerpTest.php`, `Carbon\CarbonImmutable`, `Engine`, `Asset`, `Timeframe`, `BackfillTest.php`, `OpenLong`, `BacktestTest.php`, `Venue`, `OrderManagerTest.php`, `.handle`, `Money`, `InMemoryDecisionLog`, `ExchangeVerificationTest.php`, `RulesHistory`, `Report`, `MovingAverageTrend`, `CandleStreamTest.php`, `RunBacktestCommandTest.php`, `Stringable`, `.run`, `SimulatedVenue`, `Marks`, `Ledger`, `CandleRepositoryTest.php`, `.zero`, `TrackedOrder`, `CanonicalJson`, `VenuePosition`, `CandleFeedTest.php`, `.parse`, `FundingCarry`, `.__construct`, `HoldStrategy`?**
  _High betweenness centrality (0.082) - this node is a cross-community bridge._
- **Should `EngineTest.php` be split into smaller, more focused modules?**
  _Cohesion score 0.14878048780487804 - nodes in this community are weakly interconnected._
- **Why does `Candle` connect `Candle` to `EngineTest.php`, `Decimal`, `Instrument`, `SimulatedVenue`, `Strategy/BaselinesTest.php`, `BinanceRestClient`, `SimulatedVenueTest.php`, `.times`, `SimulatedPerpVenueTest.php`, `ExchangeVerificationTest.php`, `IndicatorsTest.php`, `BinanceArchive.php`, `Symbol`, `Backfill.php`, `ExitReason`, `StrategyContext`, `EnginePerpTest.php`, `Carbon\CarbonImmutable`, `Timeframe`, `BacktestTest.php`, `.walk`, `OrderManagerTest.php`, `Money`, `BinanceRestClientTest.php`, `Report`, `CandleStreamTest.php`, `Rows`, `.run`, `SimulatedVenue`, `CandleRepositoryTest.php`, `.parse`, `CandleFeedTest.php`, `.__construct`?**
  _High betweenness centrality (0.050) - this node is a cross-community bridge._
- **Should `Decimal` be split into smaller, more focused modules?**
  _Cohesion score 0.05803571428571429 - nodes in this community are weakly interconnected._