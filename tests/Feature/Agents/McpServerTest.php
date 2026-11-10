<?php

declare(strict_types=1);

use App\Agents\Mcp\BoundedRead;
use App\Agents\Mcp\QueryTooSlow;
use App\Agents\Mcp\Tools\CompileStrategy;
use App\Agents\Mcp\Tools\DslReference;
use App\Agents\Mcp\Tools\GetCandles;
use App\Agents\Mcp\Tools\GetFunding;
use App\Agents\Mcp\Tools\GetIndicators;
use App\Agents\Mcp\Tools\GetJob;
use App\Agents\Mcp\Tools\GetStrategy;
use App\Agents\Mcp\Tools\ListStrategies;
use App\Agents\Mcp\Tools\ListTrials;
use App\Agents\Mcp\Tools\RunBacktest;
use App\Agents\Mcp\Tools\RunGrid;
use App\Agents\Mcp\Tools\SaveStrategy;
use App\Agents\Mcp\TradingServer;
use App\Agents\Models\McpCall;
use App\Agents\Models\McpToken;
use App\Market\CandleRepository;
use App\Market\Data\BookDepth;
use App\Market\Data\CandleSource;
use App\Market\Data\InstrumentStatus;
use App\Market\Data\MarketType;
use App\Market\LiquidationRepository;
use App\Market\Models\Instrument;
use App\Market\ScalpingRepository;
use App\Simulation\Charts\ChartSeries;
use App\Simulation\Models\SimulationJob;
use App\Strategy\Versions\StrategyVersion;
use App\Support\Clock\Clock;
use App\Support\Clock\SimClock;
use App\Support\Number\Decimal;
use App\Support\Symbol;
use App\Support\Timeframe;
use Carbon\CarbonImmutable;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Testing\TestResponse;
use Pest\TestSuite;
use Symfony\Component\HttpFoundation\Response;
use Tests\Support\CandleFactory;
use Tests\TestCase;

uses(RefreshDatabase::class);

/** Creates a token through the command and returns its secret, as the operator would copy it. */
function mcpToken(string $name = 'desk'): string
{
    Artisan::call('mcp:token', ['name' => $name]);
    preg_match('/\b(tx_[A-Za-z0-9]{40})\b/', Artisan::output(), $match);

    return $match[1] ?? throw new RuntimeException('No token printed.');
}

/**
 * @param  array<string, mixed>  $params
 * @return TestResponse<Response>
 */
function rpc(string $method, array $params = [], ?string $token = null): TestResponse
{
    $test = TestSuite::getInstance()->test;

    return ($test instanceof TestCase ? $test : throw new LogicException('rpc() runs inside a test.'))->postJson('/mcp', ['jsonrpc' => '2.0', 'id' => 1, 'method' => $method, 'params' => $params], array_filter([
        'Authorization' => $token === null ? null : "Bearer {$token}",
        'Accept' => 'application/json, text/event-stream',
    ]));
}

/**
 * Stores a USDT perpetual with the daily stats given, as market:daily leaves them.
 *
 * @param  array<string, list<string|null>>  $days  by day: open, high, low, close, quote volume, then the funding rate and the open interest value where known
 */
function dailyPerp(string $base, array $days, ?string $listed = null, ?string $delisted = null, string $type = 'COIN'): Instrument
{
    $perp = Instrument::register('binance', MarketType::Perp, Symbol::parse("{$base}/USDT:USDT"), CarbonImmutable::parse('2026-11-01', 'UTC'), $delisted === null ? InstrumentStatus::Trading : InstrumentStatus::Delisted);
    $perp->update([
        'underlying_type' => $type,
        'listed_at' => $listed === null ? null : CarbonImmutable::parse($listed, 'UTC'),
        'delisted_at' => $delisted === null ? null : CarbonImmutable::parse($delisted, 'UTC'),
    ]);

    foreach ($days as $day => $d) {
        DB::table('daily_stats')->insert([
            'instrument_id' => $perp->id, 'day' => $day, 'open' => $d[0], 'high' => $d[1], 'low' => $d[2], 'close' => $d[3], 'volume' => '1',
            'quote_volume' => $d[4], 'trades' => 1, 'funding_rate' => $d[5] ?? null, 'open_interest_value' => $d[6] ?? null,
        ]);
    }

    return $perp;
}

/**
 * Calls a tool as an agent does, over HTTP with the token.
 *
 * @param  array<string, mixed>  $arguments
 * @return array<array-key, mixed>|string the tool's JSON answer, or the text of its refusal
 */
function ask(string $token, string $tool, array $arguments = []): array|string
{
    $result = rpc('tools/call', ['name' => $tool, 'arguments' => $arguments], $token)->assertOk()->json('result');
    $text = data_get($result, 'content.0.text');
    $text = is_string($text) ? $text : throw new RuntimeException("{$tool} answered without text.");

    if (data_get($result, 'isError') === true) {
        return $text;
    }

    $answer = json_decode($text, true, flags: JSON_THROW_ON_ERROR);

    return is_array($answer) ? $answer : throw new RuntimeException("{$tool} did not answer with an object.");
}

/** Acts as the token's agent for tools called directly on the server. */
function actAsToken(string $name = 'desk'): void
{
    request()->attributes->set('mcp_token', McpToken::query()->where('name', $name)->firstOrFail());
}

beforeEach(function (): void {
    config()->set('mcp.enabled', true);
});

it('is off unless enabled (D-63)', function (): void {
    config()->set('mcp.enabled', false);

    rpc('tools/list', token: mcpToken())->assertNotFound();
});

it('refuses calls without a valid token, and records the ones it serves without the token', function (): void {
    $token = mcpToken();

    rpc('tools/list')->assertUnauthorized();
    rpc('tools/list', token: 'tx_'.str_repeat('a', 40))->assertUnauthorized();
    rpc('tools/call', ['name' => 'list_instruments', 'arguments' => []], $token)->assertOk();

    $call = McpCall::query()->sole();
    $stored = McpToken::query()->sole();

    expect($call->token_name)->toBe('desk')
        ->and($call->method)->toBe('tools/call')
        ->and($call->tool)->toBe('list_instruments')
        ->and($call->outcome)->toBe('ok')
        ->and($stored->token_hash)->toBe(hash('sha256', $token))
        ->and(json_encode([$call->toArray(), $stored->toArray()]))->not->toContain($token)
        ->and($stored->last_used_at)->not->toBeNull();

    Artisan::call('mcp:revoke', ['name' => 'desk']);
    rpc('tools/list', token: $token)->assertUnauthorized();
});

it('limits calls per minute per token', function (): void {
    config()->set('mcp.calls_per_minute', 2);
    $token = mcpToken();

    rpc('tools/list', token: $token)->assertOk();
    rpc('tools/list', token: $token)->assertOk();
    rpc('tools/list', token: $token)->assertTooManyRequests();
});

it('offers only reading, draft versions and simulation runs: no order, limit or stage tool (D-7, D-63)', function (): void {
    $tools = rpc('tools/list', token: mcpToken())->assertOk()->json('result.tools');
    $names = collect(is_array($tools) ? $tools : [])->pluck('name')->sort()->values()->all();

    expect($names)->toBe([
        'compile_strategy', 'dsl_reference', 'get_candles', 'get_depth', 'get_funding', 'get_indicators', 'get_job', 'get_liquidations',
        'get_listing_stats', 'get_strategy', 'list_instruments', 'list_strategies', 'list_trials', 'list_universe', 'run_backtest', 'run_grid',
        'save_strategy', 'screen_market',
    ]);

    // Everything added for research only reads (D-93).
    foreach (collect(is_array($tools) ? $tools : [])->whereIn('name', ['get_depth', 'get_liquidations', 'get_listing_stats', 'list_universe', 'list_instruments', 'screen_market']) as $tool) {
        expect(data_get($tool, 'annotations.readOnlyHint'))->toBeTrue();
    }
});

it('lists the instruments that have candles with their first and last stored minute', function (): void {
    backtestFixture();
    perpFixture();
    Instrument::register('binance', MarketType::Spot, Symbol::parse('ETH/USDT'), CarbonImmutable::parse('2026-11-08 12:00:00', 'UTC'));

    expect(ask(mcpToken(), 'list_instruments'))->toBe(['instruments' => [
        ['first_minute' => '2026-10-01T00:00:00Z', 'last_minute' => '2026-10-02T23:59:00Z', 'market' => 'perp', 'status' => 'trading', 'symbol' => 'BTC/USDT:USDT'],
        ['first_minute' => '2026-10-01T00:00:00Z', 'last_minute' => '2026-10-02T23:59:00Z', 'market' => 'spot', 'status' => 'trading', 'symbol' => 'BTC/USDT'],
    ]]);
});

it('lists the members of the universe on a day, with their lifetime and what is stored of them (D-93)', function (): void {
    $token = mcpToken();

    expect(ask($token, 'list_universe'))->toBe('The research universe is not built yet; the operator builds it (market:universe:build).');

    universeFixture();
    $october = ask($token, 'list_universe', ['date' => '2026-10-15']);

    expect($october)->toBeArray()
        ->and(data_get($october, 'universe'))->toBe(['by' => 'quote_volume_30d', 'from' => '2026-10', 'n' => 6, 'rule' => 'top', 'to' => '2026-10'])
        ->and(data_get($october, 'month'))->toBe('2026-10')
        ->and(data_get($october, 'members.0'))->toBe([
            'delisted_at' => null, 'first_minute' => '2026-10-01T00:00:00Z', 'last_minute' => '2026-10-02T23:59:00Z', 'listed_at' => null,
            'quote_volume_30d' => '600', 'rank' => 1, 'status' => 'trading', 'symbol' => 'AAA/USDT:USDT',
        ])
        ->and(data_get($october, 'members.5'))->toBe([
            'delisted_at' => '2026-10-02T12:00:00Z', 'first_minute' => '2026-10-01T00:00:00Z', 'last_minute' => '2026-10-02T11:59:00Z', 'listed_at' => null,
            'quote_volume_30d' => '100', 'rank' => 6, 'status' => 'delisted', 'symbol' => 'FFF/USDT:USDT',
        ])
        // Without a day it answers for the last month built.
        ->and(ask($token, 'list_universe'))->toBe($october)
        ->and(ask($token, 'list_universe', ['date' => '2026-12-01']))->toBe('The universe is built from 2026-10 to 2026-10; 2026-12 is outside it.')
        ->and(ask($token, 'list_universe', ['date' => 'soon']))->toBe('date is a day, YYYY-MM-DD.');
});

it('reads order book depth as stored or averaged over longer steps, and says which days have none (D-93)', function (): void {
    backtestFixture();
    $perp = perpFixture();
    $token = mcpToken();
    $at = fn (string $time, int $bid): BookDepth => new BookDepth(
        CarbonImmutable::parse($time, 'UTC'), Decimal::of($bid), Decimal::of($bid * 2), Decimal::of($bid * 5), Decimal::of($bid + 10), Decimal::of($bid * 2 + 10), Decimal::of($bid * 5 + 10),
    );
    app(ScalpingRepository::class)->upsertDepth($perp, CandleSource::Archive, [
        $at('2026-10-01 00:00:00', 100), $at('2026-10-01 00:05:00', 301), $at('2026-10-01 01:00:00', 500), $at('2026-10-01 02:00:00', 900),
    ]);
    $window = ['symbol' => 'BTC/USDT:USDT', 'from' => '2026-10-01T00:00:00Z', 'to' => '2026-10-01T02:00:00Z'];

    expect(ask($token, 'get_depth', $window))->toBe(['depth' => [
        ['ask_1' => '110', 'ask_2' => '210', 'ask_5' => '510', 'bid_1' => '100', 'bid_2' => '200', 'bid_5' => '500', 'time' => '2026-10-01T00:00:00Z'],
        ['ask_1' => '311', 'ask_2' => '612', 'ask_5' => '1515', 'bid_1' => '301', 'bid_2' => '602', 'bid_5' => '1505', 'time' => '2026-10-01T00:05:00Z'],
        ['ask_1' => '510', 'ask_2' => '1010', 'ask_5' => '2510', 'bid_1' => '500', 'bid_2' => '1000', 'bid_5' => '2500', 'time' => '2026-10-01T01:00:00Z'],
    ], 'every' => '5m', 'missing_days' => []])
        ->and(ask($token, 'get_depth', [...$window, 'every' => '1h']))->toBe(['depth' => [
            ['ask_1' => '210.5', 'ask_2' => '411', 'ask_5' => '1012.5', 'bid_1' => '200.5', 'bid_2' => '401', 'bid_5' => '1002.5', 'time' => '2026-10-01T00:00:00Z'],
            ['ask_1' => '510', 'ask_2' => '1010', 'ask_5' => '2510', 'bid_1' => '500', 'bid_2' => '1000', 'bid_5' => '2500', 'time' => '2026-10-01T01:00:00Z'],
        ], 'every' => '1h', 'missing_days' => []])
        ->and(data_get(ask($token, 'get_depth', [...$window, 'to' => '2026-10-03T00:00:00Z', 'every' => '1d']), 'missing_days'))->toBe(['2026-10-02'])
        ->and(ask($token, 'get_depth', [...$window, 'to' => '2026-10-05T00:00:00Z']))->toBe('That is 1152 rows; ask for at most 1000, with a shorter window or a longer step in every.')
        ->and(ask($token, 'get_depth', [...$window, 'every' => '1m']))->toBe('every must be one of 5m, 15m, 1h, 4h, 1d.')
        ->and(ask($token, 'get_depth', [...$window, 'from' => '2026-10-02T00:00:00Z', 'to' => '2026-10-02T06:00:00Z']))
        ->toBe('No depth is stored for BTC/USDT:USDT in that window; the operator imports it (market:backfill --depth).')
        ->and(ask($token, 'get_depth', [...$window, 'symbol' => 'BTC/USDT']))->toBe('BTC/USDT is not a stored perpetual.')
        ->and(ask($token, 'get_depth', [...$window, 'to' => '2026-10-01T00:00:00Z']))->toBe('from is before to; both are times in UTC, such as 2026-09-01T00:00:00Z.');
});

it('reads liquidations by the minute or summed over longer steps, as a lower bound, and says what was not collected (D-93)', function (): void {
    backtestFixture();
    $perp = perpFixture();
    $token = mcpToken();
    $start = CarbonImmutable::parse('2026-10-01 00:00:00', 'UTC');
    $stored = app(LiquidationRepository::class);
    $stored->store($perp, $start, 2, '1000', 0, '0');
    $stored->store($perp, $start->addMinute(), 0, '0', 1, '250.5');
    $stored->store($perp, $start->addMinutes(61), 1, '10', 0, '0');
    $stored->cover(array_map(fn (int $minute): CarbonImmutable => $start->addMinutes($minute), range(0, 119)));
    $window = ['symbol' => 'BTC/USDT:USDT', 'from' => '2026-10-01T00:00:00Z', 'to' => '2026-10-01T02:00:00Z'];
    $minutes = ask($token, 'get_liquidations', $window);

    expect(data_get($minutes, 'liquidations'))->toBe([
        ['long_count' => 2, 'long_notional' => '1000', 'short_count' => 0, 'short_notional' => '0', 'time' => '2026-10-01T00:00:00Z'],
        ['long_count' => 0, 'long_notional' => '0', 'short_count' => 1, 'short_notional' => '250.5', 'time' => '2026-10-01T00:01:00Z'],
        ['long_count' => 1, 'long_notional' => '10', 'short_count' => 0, 'short_notional' => '0', 'time' => '2026-10-01T01:01:00Z'],
    ])
        ->and(data_get($minutes, 'incomplete_days'))->toBe([])
        ->and(data_get($minutes, 'note'))->toBe('A lower bound: the exchange streams at most one liquidation per symbol per second. A step without a row had none that was heard.')
        ->and(data_get(ask($token, 'get_liquidations', [...$window, 'every' => '1h']), 'liquidations'))->toBe([
            ['long_count' => 2, 'long_notional' => '1000', 'short_count' => 1, 'short_notional' => '250.5', 'time' => '2026-10-01T00:00:00Z'],
            ['long_count' => 1, 'long_notional' => '10', 'short_count' => 0, 'short_notional' => '0', 'time' => '2026-10-01T01:00:00Z'],
        ])
        // The stream was heard for two hours of that day only.
        ->and(data_get(ask($token, 'get_liquidations', [...$window, 'to' => '2026-10-01T12:00:00Z']), 'incomplete_days'))->toBe(['2026-10-01'])
        ->and(ask($token, 'get_liquidations', [...$window, 'to' => '2026-10-02T00:00:00Z']))->toBe('That is 1440 rows; ask for at most 1000, with a shorter window or a longer step in every.')
        ->and(ask($token, 'get_liquidations', [...$window, 'every' => '1w']))->toBe('every must be one of 1m, 5m, 15m, 1h, 4h, 1d.')
        ->and(ask($token, 'get_liquidations', [...$window, 'from' => '2026-10-02T00:00:00Z', 'to' => '2026-10-02T06:00:00Z']))
        ->toBe('No liquidations were collected in that window: they come from the live stream only (market:stream perp), from the day it was started.')
        ->and(ask($token, 'get_liquidations', [...$window, 'symbol' => 'ETH/USDT:USDT']))->toBe('ETH/USDT:USDT is not a stored perpetual.');
});

it('stops a read that takes longer than its bound and leaves the connection as it was (D-93)', function (): void {
    config()->set('mcp.statement_timeout_ms', 150);
    $read = app(BoundedRead::class);
    $bound = fn (): mixed => data_get(DB::selectOne('show statement_timeout'), 'statement_timeout');
    $before = $bound();

    expect($read->run(fn (): int => 7))->toBe(7)
        ->and($bound())->toBe($before)
        ->and(fn (): mixed => $read->run(fn (): array => DB::select('select pg_sleep(2)')))
        ->toThrow(QueryTooSlow::class, 'The data took longer than 0.15 s to read; ask for a shorter window or fewer rows.')
        ->and($bound())->toBe($before)
        // Another failure is not taken for a slow read.
        ->and(fn (): mixed => $read->run(fn (): array => DB::select('select * from no_such_table')))->toThrow(QueryException::class);
});

it('answers a tool whose read is too slow with the bound, not with a failure', function (): void {
    config()->set('mcp.statement_timeout_ms', 150);
    $token = mcpToken();
    // Another session holds the table, as a long import or a rebuild of the universe does.
    config()->set('database.connections.other', config('database.connections.'.DB::getDefaultConnection()));
    $other = DB::connection('other');
    $other->beginTransaction();

    try {
        $other->statement('lock table universe_members in access exclusive mode');

        expect(ask($token, 'list_universe'))->toBe('The data took longer than 0.15 s to read; ask for a shorter window or fewer rows.');
    } finally {
        $other->rollBack();
        DB::purge('other');
    }

    expect(ask($token, 'list_universe'))->toBe('The research universe is not built yet; the operator builds it (market:universe:build).');
});

it('evaluates indicators exactly as the chart service does', function (): void {
    backtestFixture();
    mcpToken();
    actAsToken();
    $expected = app(ChartSeries::class)->compute(Symbol::parse('BTC/USDT'), Timeframe::FiveMinutes, CarbonImmutable::parse('2026-10-02 10:00', 'UTC'), CarbonImmutable::parse('2026-10-02 12:00', 'UTC'), ['ema(9)', 'rsi(7)']);

    TradingServer::tool(GetIndicators::class, ['symbol' => 'BTC/USDT', 'timeframe' => '5m', 'from' => '2026-10-02T10:00:00Z', 'to' => '2026-10-02T12:00:00Z', 'expressions' => ['ema(9)', 'rsi(7)']])
        ->assertOk()
        ->assertSee([(string) $expected['series']['ema(9)'][23], (string) $expected['series']['rsi(7)'][23]]);
    TradingServer::tool(GetIndicators::class, ['symbol' => 'BTC/USDT', 'timeframe' => '5m', 'from' => '2026-10-02T10:00:00Z', 'to' => '2026-10-02T12:00:00Z', 'expressions' => ['level(low)']])
        ->assertHasErrors(['level is an exit level']);
});

it('compiles a universe strategy and reads it back with its rule (D-92)', function (): void {
    backtestFixture();
    mcpToken();
    actAsToken();

    TradingServer::tool(CompileStrategy::class, ['document' => UNIVERSE_DOCUMENT])
        ->assertOk()
        ->assertSee(['xs-momentum-test', '{ rule: top, n: 50, by: quote_volume_30d }', 'On the top 50 USDT perpetuals by 30-day quote volume', 'it is among the 5 members with the highest rate of change(42)']);
});

it('compiles, saves a draft by the token\'s agent, runs a grid in the background and reports it', function (): void {
    backtestFixture(days: 36);
    mcpToken();
    actAsToken();

    TradingServer::tool(CompileStrategy::class, ['document' => str_replace('1.5 * atr(14)', 'close > 2%', EMA_CROSS_DOCUMENT)])
        ->assertOk()
        ->assertSee('cannot compare a price series with a percent');
    TradingServer::tool(SaveStrategy::class, ['document' => EMA_CROSS_DOCUMENT])->assertOk()->assertSee('ema-cross-test@v1');
    TradingServer::tool(RunGrid::class, [
        'version' => 'ema-cross-test@v1', 'from' => '2026-10-02', 'to' => '2026-11-05', 'grid' => ['fast=5,9', 'slow=15,21'],
        'walk_forward' => ['in' => '10d', 'out' => '5d', 'step' => '5d', 'min_trades' => 1],
    ])->assertOk();

    $job = SimulationJob::query()->sole();

    expect(StrategyVersion::query()->sole()->author)->toBe('agent:desk')
        ->and($job->author)->toBe('agent:desk')
        ->and($job->status)->toBe('done');
    TradingServer::tool(GetJob::class, ['id' => $job->id])->assertOk()->assertSee(['done', 'deflated_sharpe', 'walk_forward']);
});

it('reads candles, funding and the DSL reference, refusing what it cannot serve', function (): void {
    backtestFixture();
    perpFixture();
    mcpToken();
    actAsToken();

    TradingServer::tool(GetCandles::class, ['symbol' => 'BTC/USDT', 'timeframe' => '1h', 'from' => '2026-10-01T00:00:00Z', 'to' => '2026-10-01T03:00:00Z'])
        ->assertOk()->assertSee(['2026-10-01T00:00:00Z', '2026-10-01T02:00:00Z'])->assertDontSee('2026-10-01T03:00:00Z');
    TradingServer::tool(GetCandles::class, ['symbol' => 'BTC/USDT', 'timeframe' => '2m', 'from' => '2026-10-01T00:00:00Z', 'to' => '2026-10-01T03:00:00Z'])
        ->assertHasErrors(['timeframe must be one of']);
    TradingServer::tool(GetFunding::class, ['symbol' => 'BTC/USDT:USDT', 'from' => '2026-10-01', 'to' => '2026-10-01'])
        ->assertOk()->assertSee(['2026-10-01T08:00:00Z', '0.0001']);
    TradingServer::tool(GetFunding::class, ['symbol' => 'BTC/USDT', 'from' => '2026-10-01', 'to' => '2026-10-01'])
        ->assertHasErrors(['BTC/USDT is not a stored perpetual.']);
    TradingServer::tool(DslReference::class)
        ->assertOk()->assertSee(['## 2. Expression language', 'bb(n = 20: a whole number of candles', 'fields .upper', 'oi_change(n: a whole number of candles', 'strategy: ema-cross-scalp-long']);
});

it('refuses candles over incomplete history with the day to fill, which only the operator can do (D-88)', function (): void {
    app()->instance(Clock::class, new SimClock(CarbonImmutable::parse('2026-10-03 01:30:00', 'UTC')));
    $eth = Instrument::register('binance', MarketType::Spot, Symbol::parse('ETH/USDT'), CarbonImmutable::parse('2026-10-03', 'UTC'));
    app(CandleRepository::class)->upsert($eth, CandleSource::Stream, CandleFactory::walk('2026-10-03 00:52:00', 30, seed: 3));
    mcpToken();
    actAsToken();

    TradingServer::tool(GetCandles::class, ['symbol' => 'ETH/USDT', 'timeframe' => '1h', 'from' => '2026-10-02T00:00:00Z', 'to' => '2026-10-03T01:00:00Z'])
        ->assertHasErrors(['ETH/USDT has stored minutes only from 2026-10-03 00:52', 'ask the operator to fill its history from 2026-10-0']);
});

it('lists and reads saved versions, runs a backtest on one and lists its trials', function (): void {
    backtestFixture(days: 3);
    mcpToken();
    actAsToken();

    TradingServer::tool(SaveStrategy::class, ['document' => 'strategy: ['])->assertHasErrors(['does not compile; nothing was saved.']);
    TradingServer::tool(SaveStrategy::class, ['document' => EMA_CROSS_DOCUMENT])->assertOk();
    TradingServer::tool(ListStrategies::class)->assertOk()->assertSee(['ema-cross-test@v1', 'agent:desk', 'draft']);
    TradingServer::tool(GetStrategy::class, ['version' => 'ema-cross-test@v1'])->assertOk()->assertSee(['strategy: ema-cross-test', 'agent:desk', 'On BTC/USDT spot, every time a 5-minute candle closes: buy at market when the 9-period EMA crosses above the 21-period EMA.']);
    TradingServer::tool(GetStrategy::class, ['version' => 'ema-cross-test@v2'])->assertHasErrors(['ema-cross-test@v2 is not saved']);
    TradingServer::tool(RunBacktest::class, ['version' => 'ema-cross-test@v1', 'from' => '2026-10-02', 'to' => '2026-10-04', 'params' => ['fast' => 5]])->assertOk();
    TradingServer::tool(RunBacktest::class, ['version' => 'ema-cross-test', 'from' => '2026-10-01', 'to' => '2026-10-04'])
        ->assertHasErrors(['strategy is a saved version']);

    $job = SimulationJob::query()->sole();

    expect($job->error)->toBeNull()->and($job->status)->toBe('done')
        ->and($job->arguments['params'])->toBe(['fast' => '5']);
    TradingServer::tool(ListTrials::class, ['family' => 'ema-cross-test'])->assertOk()->assertSee(['"count":1', '"fast":"5"']);
});

it('creates a token shown once and stored only as its hash, and revokes it', function (): void {
    $exit = Artisan::call('mcp:token', ['name' => 'laptop']);
    $output = Artisan::output();

    expect($exit)->toBe(0)
        ->and($output)->toContain('shown once')
        ->and(Artisan::call('mcp:token', ['name' => 'laptop']))->toBe(1)
        ->and(Artisan::output())->toContain('laptop already has a token; revoke it first.')
        ->and(Artisan::call('mcp:revoke', ['name' => 'nobody']))->toBe(1)
        ->and(McpToken::query()->sole()->revoked_at)->toBeNull();
});

it('screens the market over the days before a date, point in time, by any of five measures (D-93)', function (): void {
    config()->set('mcp.heavy_calls_per_minute', 100);
    $token = mcpToken();

    expect(ask($token, 'screen_market'))->toBe('No daily stats are stored; the operator fills them (market:daily).');

    $aaa = dailyPerp('AAA', [
        '2026-10-01' => ['100', '110', '100', '110', '100', '0.0001', '1000'],
        '2026-10-02' => ['110', '121', '110', '120', '100', '0.0003', '1200'],
        '2026-10-03' => ['120', '150', '120', '150', '100', '0.0002', '1500'],
    ]);
    $bbb = dailyPerp('BBB', [
        '2026-10-01' => ['100', '100', '95', '95', '300'],
        '2026-10-02' => ['90', '99', '90', '90', '300'],
        '2026-10-03' => ['90', '90', '90', '90', '300'],
        // The day of the screen itself is not known to it.
        '2026-10-04' => ['90', '1000', '90', '1000', '9000'],
    ]);
    // Listed inside the window: it has two of the three days.
    dailyPerp('CCC', ['2026-10-02' => ['10', '12', '10', '11', '50'], '2026-10-03' => ['12', '12', '12', '12', '50']], listed: '2026-10-02');
    // A stock contract is never screened.
    dailyPerp('TSLA', ['2026-10-01' => ['1', '9', '1', '9', '99999'], '2026-10-02' => ['9', '9', '9', '9', '99999'], '2026-10-03' => ['9', '9', '9', '9', '99999']], type: 'EQUITY');

    $a = ['days' => 3, 'funding' => '0.0002', 'open_interest_change' => '0.5', 'quote_volume' => '300', 'return' => '0.5', 'symbol' => 'AAA/USDT:USDT', 'volatility' => '0.15'];
    $b = ['days' => 3, 'funding' => null, 'open_interest_change' => null, 'quote_volume' => '900', 'return' => '-0.1', 'symbol' => 'BBB/USDT:USDT', 'volatility' => '0.05'];
    $c = ['days' => 2, 'funding' => null, 'open_interest_change' => null, 'quote_volume' => '100', 'return' => '0.2', 'symbol' => 'CCC/USDT:USDT', 'volatility' => '0.1'];
    $ranked = function (array ...$rows): array {
        $ranked = [];

        foreach (array_values($rows) as $index => $row) {
            $row['rank'] = $index + 1;
            ksort($row);
            $ranked[] = $row;
        }

        return $ranked;
    };
    $all = ['date' => '2026-10-04', 'window_days' => 3, 'scope' => 'all'];

    expect(ask($token, 'screen_market', $all))->toBe([
        'rows' => $ranked($a, $b),
        'screen' => ['by' => 'return', 'date' => '2026-10-04', 'from' => '2026-10-01', 'order' => 'desc', 'scope' => 'all', 'to' => '2026-10-03', 'whole_window' => true, 'window_days' => 3],
    ])
        ->and(data_get(ask($token, 'screen_market', [...$all, 'by' => 'quote_volume']), 'rows'))->toBe($ranked($b, $a))
        ->and(data_get(ask($token, 'screen_market', [...$all, 'by' => 'volatility']), 'rows'))->toBe($ranked($a, $b))
        // An instrument without the measure is left out, not ranked as zero.
        ->and(data_get(ask($token, 'screen_market', [...$all, 'by' => 'funding']), 'rows'))->toBe($ranked($a))
        ->and(data_get(ask($token, 'screen_market', [...$all, 'by' => 'open_interest_change']), 'rows'))->toBe($ranked($a))
        ->and(data_get(ask($token, 'screen_market', [...$all, 'order' => 'asc']), 'rows'))->toBe($ranked($b, $a))
        ->and(data_get(ask($token, 'screen_market', [...$all, 'whole_window' => false]), 'rows'))->toBe($ranked($a, $c, $b))
        ->and(data_get(ask($token, 'screen_market', [...$all, 'whole_window' => false, 'limit' => 1]), 'rows'))->toBe($ranked($a));

    // Without a date it screens the days up to the last one stored.
    $latest = ask($token, 'screen_market', ['scope' => 'all', 'window_days' => 4, 'whole_window' => false]);

    expect(data_get($latest, 'screen.date'))->toBe('2026-10-05')
        ->and(data_get($latest, 'rows.0.symbol'))->toBe('BBB/USDT:USDT')
        ->and(data_get($latest, 'rows.0.return'))->toBe('9')
        ->and(ask($token, 'screen_market', [...$all, 'date' => '2026-10-09']))->toBe('Daily stats are stored up to 2026-10-04; date is at most 2026-10-05.')
        ->and(ask($token, 'screen_market', [...$all, 'date' => '2026-09-10']))
        ->toBe('Nothing to rank by return from 2026-09-07 to 2026-09-09: no instrument of that scope has the daily stats for it. Try whole_window false or another window; the operator fills daily stats (market:daily).')
        // The universe is the scope unless all is asked for.
        ->and(ask($token, 'screen_market', ['date' => '2026-10-04', 'window_days' => 3]))
        ->toBe('The universe is not built for 2026-10; ask with scope all, or the operator builds it (market:universe:build).');

    DB::table('universe_members')->insert([
        ['month' => '2026-10-01', 'instrument_id' => $bbb->id, 'rank' => 1, 'quote_volume_30d' => '900'],
        ['month' => '2026-10-01', 'instrument_id' => $aaa->id, 'rank' => 2, 'quote_volume_30d' => '300'],
    ]);
    $members = ask($token, 'screen_market', ['date' => '2026-10-04', 'window_days' => 3, 'whole_window' => false]);

    expect(data_get($members, 'rows'))->toBe($ranked($a, $b))
        ->and(data_get($members, 'screen.scope'))->toBe('universe')
        ->and(ask($token, 'screen_market', [...$all, 'by' => 'luck']))->toBe('by must be one of return, quote_volume, funding, open_interest_change, volatility.')
        ->and(ask($token, 'screen_market', [...$all, 'window_days' => 400]))->toBe('window_days is a whole number from 1 to 365.')
        ->and(ask($token, 'screen_market', [...$all, 'limit' => 51]))->toBe('limit is a whole number from 1 to 50.')
        ->and(ask($token, 'screen_market', [...$all, 'scope' => 'mine']))->toBe('scope must be universe or all.')
        ->and(ask($token, 'screen_market', [...$all, 'order' => 'up']))->toBe('order must be desc or asc.')
        ->and(ask($token, 'screen_market', [...$all, 'whole_window' => 'yes']))->toBe('whole_window is true or false.')
        ->and(ask($token, 'screen_market', [...$all, 'date' => 'soon']))->toBe('date is a day, YYYY-MM-DD.');
});

it('says what perpetuals did after their listing, only where the days from the listing are stored (D-93)', function (): void {
    config()->set('mcp.heavy_calls_per_minute', 100);
    $token = mcpToken();
    $first = CarbonImmutable::parse('2026-09-01', 'UTC');
    $days = ['2026-09-01' => ['10', '20', '10', '20', '1000']];

    for ($age = 1; $age <= 30; $age++) {
        $days[$first->addDays($age)->toDateString()] = match (true) {
            $age < 6 => ['20', '20', '20', '20', '100'],
            // The seventh day falls to a quarter of the high and closes the first week at 15.
            $age === 6 => ['20', '20', '5', '15', '100'],
            $age < 29 => ['15', '15', '15', '15', '10'],
            // The thirtieth day closes at 30, the day after at 12.
            $age === 29 => ['15', '30', '15', '30', '10'],
            default => ['30', '30', '12', '12', '7'],
        };
    }

    dailyPerp('NEW', $days, listed: '2026-09-01 08:00:00');
    dailyPerp('GONE', [
        '2026-09-05' => ['10', '10', '8', '8', '50'], '2026-09-06' => ['8', '8', '4', '4', '30'], '2026-09-07' => ['4', '6', '4', '6', '20'],
    ], listed: '2026-09-05', delisted: '2026-09-08');
    // Its stored days begin two days after its listing: what it did on its first day is not known.
    dailyPerp('LATE', ['2026-09-12' => ['1', '1', '1', '1', '1']], listed: '2026-09-10');
    dailyPerp('OLD', ['2026-09-01' => ['1', '1', '1', '1', '1']], listed: '2024-01-01');
    dailyPerp('TSLA', ['2026-09-03' => ['1', '1', '1', '1', '1']], listed: '2026-09-03', type: 'EQUITY');

    expect(ask($token, 'get_listing_stats', ['from' => '2026-09-01', 'to' => '2026-10-01']))->toBe([
        'listings' => [
            [
                'days' => 31, 'delisted_at' => null, 'first_day' => '2026-09-01', 'last_day' => '2026-10-01', 'listed_at' => '2026-09-01T08:00:00Z',
                'max_drawdown' => '-0.75', 'return_1d' => '1', 'return_30d' => '2', 'return_7d' => '0.5', 'return_since_listing' => '0.2',
                'status' => 'trading', 'symbol' => 'NEW/USDT:USDT', 'volume_1d' => '1000', 'volume_30d' => '1830', 'volume_7d' => '1600',
            ],
            [
                // It did not live a week: its week and month are not numbers.
                'days' => 3, 'delisted_at' => '2026-09-08T00:00:00Z', 'first_day' => '2026-09-05', 'last_day' => '2026-09-07', 'listed_at' => '2026-09-05T00:00:00Z',
                'max_drawdown' => '-0.6', 'return_1d' => '-0.2', 'return_30d' => null, 'return_7d' => null, 'return_since_listing' => '-0.4',
                'status' => 'delisted', 'symbol' => 'GONE/USDT:USDT', 'volume_1d' => '50', 'volume_30d' => null, 'volume_7d' => null,
            ],
        ],
        'not_covered' => ['LATE/USDT:USDT'],
        'window' => ['from' => '2026-09-01', 'to' => '2026-10-01'],
    ])
        ->and(ask($token, 'get_listing_stats', ['from' => '2027-01-01', 'to' => '2027-02-01']))->toBe('No stored USDT perpetual was listed from 2027-01-01 to 2027-02-01.')
        ->and(ask($token, 'get_listing_stats', ['from' => '2026-10-01', 'to' => '2026-09-01']))->toBe('from is before to; both are days, YYYY-MM-DD.')
        ->and(ask($token, 'get_listing_stats', ['from' => 'spring', 'to' => '2026-09-01']))->toBe('from is before to; both are days, YYYY-MM-DD.');

    foreach (range(1, 49) as $n) {
        dailyPerp("N{$n}", [], listed: '2026-09-20');
    }

    expect(ask($token, 'get_listing_stats', ['from' => '2026-09-01', 'to' => '2026-10-01']))->toBe('That is 52 listings; ask for at most 50, with a shorter window.')
        ->and(data_get(ask($token, 'get_listing_stats', ['from' => '2026-09-01', 'to' => '2026-09-06']), 'listings.1.symbol'))->toBe('GONE/USDT:USDT');
});

it('answers the tools that read the whole market a few times a minute for a token (D-93)', function (): void {
    config()->set('mcp.heavy_calls_per_minute', 2);
    $token = mcpToken();
    $other = mcpToken('laptop');
    $slow = 'screen_market and get_listing_stats answer 2 times a minute for a token; ask again in a minute.';
    $empty = 'No daily stats are stored; the operator fills them (market:daily).';

    expect(ask($token, 'screen_market'))->toBe($empty)
        ->and(ask($token, 'get_listing_stats', ['from' => '2027-01-01', 'to' => '2027-02-01']))->toBe('No stored USDT perpetual was listed from 2027-01-01 to 2027-02-01.')
        ->and(ask($token, 'screen_market'))->toBe($slow)
        ->and(ask($token, 'get_listing_stats', ['from' => '2027-01-01', 'to' => '2027-02-01']))->toBe($slow)
        // The other tools and the other tokens are not held back.
        ->and(ask($token, 'list_instruments'))->toBe(['instruments' => []])
        ->and(ask($other, 'screen_market'))->toBe($empty);
});
