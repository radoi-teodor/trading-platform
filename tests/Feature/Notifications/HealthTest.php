<?php

declare(strict_types=1);

use App\Execution\Data\EngineMode;
use App\Execution\Models\Account;
use App\Execution\Models\AccountSnapshot;
use App\Market\CandleRepository;
use App\Market\Data\CandleSource;
use App\Market\Data\MarketType;
use App\Market\Models\Instrument;
use App\Notifications\Health\DiskUsage;
use App\Support\Clock\Clock;
use App\Support\Clock\SimClock;
use App\Support\Symbol;
use Carbon\CarbonImmutable;
use Illuminate\Console\Scheduling\Event;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Tests\Support\CandleFactory;
use Tests\Support\FakeDisk;

uses(RefreshDatabase::class);

const HEALTH_NOW = '2026-11-09 10:00:30';

/** A machine where everything is healthy: the spot stream and its candle, one monitored account, empty queues. */
function healthyMachine(): Account
{
    app()->instance(Clock::class, new SimClock(CarbonImmutable::parse(HEALTH_NOW, 'UTC')));
    config()->set('market.universe', ['spot' => ['BTC/USDT'], 'perp' => []]);
    config()->set('services.telegram', ['token' => '123456:health-test-only-not-a-real-token', 'chat_id' => '42', 'base_url' => 'https://api.telegram.org']);
    $disk = new FakeDisk(400_000_000_000, 1_000_000_000_000);
    app()->instance(DiskUsage::class, $disk);
    app()->instance(FakeDisk::class, $disk);

    $btc = Instrument::register('binance', MarketType::Spot, Symbol::parse('BTC/USDT'), CarbonImmutable::parse('2026-11-01', 'UTC'));
    app(CandleRepository::class)->upsert($btc, CandleSource::Stream, CandleFactory::walk('2026-11-09 09:58:00', 2, seed: 3));
    DB::table('heartbeats')->insert(['process' => 'market:stream spot', 'beat_at' => '2026-11-09 10:00:10', 'details' => '{}']);
    $account = Account::query()->create([
        'name' => 'paper', 'mode' => EngineMode::Paper, 'quote' => 'USDT', 'spot_balance' => '1', 'futures_balance' => '0',
        'symbols' => ['BTC/USDT'], 'started_at' => '2026-11-01', 'created_at' => '2026-11-01',
    ]);
    AccountSnapshot::query()->create(['account_id' => $account->id, 'minute' => '2026-11-09 10:00', 'quiet_decisions' => 0, 'payload' => [], 'updated_at' => '2026-11-09 10:00:05']);

    return $account;
}

/**
 * @param  array<string, bool>  $options
 * @return array{int, string}
 */
function healthAt(string $time, array $options = []): array
{
    app()->instance(Clock::class, new SimClock(CarbonImmutable::parse($time, 'UTC')));
    $exit = Artisan::call('platform:health', $options);

    return [$exit, Artisan::output()];
}

/** @return list<string> */
function telegramSent(): array
{
    return array_values(Http::recorded()->map(fn (array $pair): string => is_string($pair[0]['text']) ? $pair[0]['text'] : '')->all());
}

it('says every check is fine on a healthy machine', function (): void {
    healthyMachine();
    [$exit, $output] = healthAt(HEALTH_NOW);

    expect($exit)->toBe(0)
        ->and($output)->toContain('ok    stream spot')
        ->and($output)->toContain('ok    candles BTC/USDT')
        ->and($output)->toContain('ok    engine paper')
        ->and($output)->toContain('ok    notifications queue')
        ->and($output)->toContain('ok    simulation queue')
        ->and($output)->toContain('ok    disk');
});

it('finds a silent stream, an old candle, a stopped engine, stuck queues and a full disk', function (): void {
    healthyMachine();
    DB::table('jobs')->insert(['queue' => 'notifications', 'payload' => '{}', 'attempts' => 0, 'reserved_at' => null, 'available_at' => CarbonImmutable::parse('2026-11-09 10:00', 'UTC')->getTimestamp(), 'created_at' => CarbonImmutable::parse('2026-11-09 10:00', 'UTC')->getTimestamp()]);
    DB::table('simulation_jobs')->insert(['kind' => 'backtest', 'arguments' => '{}', 'status' => 'queued', 'author' => 'operator', 'created_at' => '2026-11-09 10:00']);
    app(FakeDisk::class)->free = 30_000_000_000;

    [$exit, $output] = healthAt('2026-11-09 10:20:00');

    expect($exit)->toBe(1)
        ->and($output)->toContain('FAIL  stream spot: no heartbeat for 19 minutes')
        ->and($output)->toContain('FAIL  candles BTC/USDT: the last closed minute is 2026-11-09 10:00 UTC, 20 minutes ago')
        ->and($output)->toContain('FAIL  engine paper: the last publish was 19 minutes ago')
        ->and($output)->toContain('FAIL  notifications queue: a job has waited 20 minutes; is the notifications worker running?')
        ->and($output)->toContain('FAIL  simulation queue: a run has waited 20 minutes with none running; is the simulation worker running?')
        ->and($output)->toContain('FAIL  disk: 30 GB free of 1000 GB (3 %)');
});

it('alerts directly, once, then every hour, then when it recovers', function (): void {
    healthyMachine();
    Http::fake(['api.telegram.org/*' => Http::response(['ok' => true])]);
    Bus::fake();

    healthAt('2026-11-09 10:05:00', ['--alert' => true]);
    healthAt('2026-11-09 10:06:00', ['--alert' => true]);
    healthAt('2026-11-09 11:04:00', ['--alert' => true]);
    healthAt('2026-11-09 11:05:00', ['--alert' => true]);

    // The stream, the candles and the engine come back.
    DB::table('heartbeats')->where('process', 'market:stream spot')->update(['beat_at' => '2026-11-09 11:06:00']);
    app(CandleRepository::class)->upsert(Instrument::query()->sole(), CandleSource::Stream, CandleFactory::walk('2026-11-09 11:05:00', 1, seed: 4));
    AccountSnapshot::query()->update(['updated_at' => '2026-11-09 11:06:00']);
    healthAt('2026-11-09 11:06:10', ['--alert' => true]);

    expect(telegramSent())->toBe([
        "Trading X health: stream spot failed: no heartbeat for 4 minutes\nTrading X health: candles BTC/USDT failed: the last closed minute is 2026-11-09 10:00 UTC, 5 minutes ago\nTrading X health: engine paper failed: the last publish was 4 minutes ago",
        'Trading X health: still failing after 60 minutes: stream spot (no heartbeat for 64 minutes); candles BTC/USDT (the last closed minute is 2026-11-09 10:00 UTC, 65 minutes ago); engine paper (the last publish was 64 minutes ago)',
        'Trading X health: recovered: stream spot after 61 minutes; candles BTC/USDT after 61 minutes; engine paper after 61 minutes',
    ]);
    Bus::assertNothingDispatched();
});

it('leaves out an account the operator does not monitor and one that never ran', function (): void {
    $account = healthyMachine();
    $account->update(['monitored' => false]);
    Account::query()->create([
        'name' => 'fresh', 'mode' => EngineMode::Paper, 'quote' => 'USDT', 'spot_balance' => '1', 'futures_balance' => '0',
        'symbols' => ['BTC/USDT'], 'started_at' => '2026-11-01', 'created_at' => '2026-11-01',
    ]);

    [, $output] = healthAt('2026-11-09 10:30:00');

    expect($output)->not->toContain('engine paper')->and($output)->not->toContain('engine fresh');
});

it('keeps an alert to send again when Telegram cannot be reached', function (): void {
    healthyMachine();
    Http::fake(['api.telegram.org/*' => Http::sequence()->pushFailedConnection()->push(['ok' => true])]);

    healthAt('2026-11-09 10:05:00', ['--alert' => true]);
    healthAt('2026-11-09 10:06:00', ['--alert' => true]);

    Http::assertSentCount(2);
    Http::assertSent(fn (Request $r): bool => is_string($r['text']) && str_contains($r['text'], 'stream spot failed'));
});

it('runs every minute', function (): void {
    $events = collect(app(Schedule::class)->events())->filter(fn (Event $e): bool => str_contains($e->command ?? '', 'platform:health --alert'));

    expect($events)->toHaveCount(1)->and($events->first()?->expression)->toBe('* * * * *');
});
