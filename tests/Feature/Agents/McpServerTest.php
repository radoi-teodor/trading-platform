<?php

declare(strict_types=1);

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
use App\Simulation\Charts\ChartSeries;
use App\Simulation\Models\SimulationJob;
use App\Strategy\Versions\StrategyVersion;
use App\Support\Symbol;
use App\Support\Timeframe;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Testing\TestResponse;
use Pest\TestSuite;
use Symfony\Component\HttpFoundation\Response;
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
        'compile_strategy', 'dsl_reference', 'get_candles', 'get_funding', 'get_indicators', 'get_job', 'get_strategy',
        'list_instruments', 'list_strategies', 'list_trials', 'run_backtest', 'run_grid', 'save_strategy',
    ]);
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
