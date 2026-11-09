<?php

declare(strict_types=1);

use App\Market\Data\CandleSource;
use App\Market\Data\MarketType;
use App\Market\Data\PriceMinute;
use App\Market\Models\Instrument;
use App\Market\ScalpingRepository;
use App\Support\Clock\Clock;
use App\Support\Clock\SimClock;
use App\Support\Number\Decimal;
use App\Support\Symbol;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Tests\Support\ScalpingFixtures;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    ScalpingFixtures::fake();
    config()->set('exchanges.binance.archive_cache', storage_path('framework/testing/archive-'.getmypid()));
    app()->instance(Clock::class, new SimClock(CarbonImmutable::parse('2026-11-03 12:00', 'UTC')));
    Instrument::register('binance', MarketType::Perp, Symbol::parse('BTC/USDT:USDT'), CarbonImmutable::parse('2026-11-01', 'UTC'));
});

/**
 * @param  array<string, string|bool>  $options
 * @return array{int, string}
 */
function scalpingBackfill(array $options = []): array
{
    $exit = Artisan::call('market:backfill', ['symbol' => 'BTC/USDT:USDT', '--from' => '2026-11-01', '--to' => '2026-11-02 23:59', '--flow' => true, '--oi' => true, '--mark' => true, ...$options]);

    return [$exit, Artisan::output()];
}

it('stores the trade flow, open interest, mark price and premium index of each archived day', function (): void {
    // Only the candles are faked as missing here: the scalping data is the subject.
    [$exit, $output] = scalpingBackfill(['--candles' => 'no']);

    expect($exit)->toBe(0)
        ->and($output)->toContain('flow 2026-11-01')->and($output)->toContain('+22 new')
        ->and($output)->toContain('open interest 2026-11-01')->and($output)->toContain('+288 new')
        ->and($output)->toContain('mark price 2026-11-01')->and($output)->toContain('+1440 new')
        ->and($output)->toContain('2026-11-02: not published yet')
        ->and(DB::table('trade_flow')->count())->toBe(22)
        ->and(DB::table('trade_flow')->value('threshold'))->toBe('100000.000000000000000000')
        ->and(DB::table('open_interest')->count())->toBe(288)
        ->and(DB::table('mark_price_minutes')->count())->toBe(1440)
        ->and(DB::table('premium_index_minutes')->count())->toBe(1440);

    $repository = app(ScalpingRepository::class);
    $btc = Instrument::query()->sole();
    $flow = $repository->flow($btc, CarbonImmutable::parse('2026-11-01 00:01', 'UTC'), CarbonImmutable::parse('2026-11-01 00:01', 'UTC'));

    expect((string) $flow[0]->largeBuyVolume)->toBe('4.975')
        ->and((string) $flow[0]->largeDelta())->toBe('-21.613')
        ->and(array_map(fn (PriceMinute $m): string => (string) $m->close, $repository->marks($btc, CarbonImmutable::parse('2026-11-01 00:00', 'UTC'), CarbonImmutable::parse('2026-11-01 00:01', 'UTC'))))->toBe(['77483.75088333', '77485.8'])
        ->and((string) $repository->openInterest($btc, CarbonImmutable::parse('2026-11-01 00:00', 'UTC'), CarbonImmutable::parse('2026-11-01 00:05', 'UTC'))[1]->contracts)->not->toBe('');
});

it('stores nothing twice, and keeps what the stream wrote first so a replay reads it again (D-84)', function (): void {
    $btc = Instrument::query()->sole();
    $repository = app(ScalpingRepository::class);
    $at = CarbonImmutable::parse('2026-11-01 00:00', 'UTC');
    $repository->upsertMarks($btc, CandleSource::Stream, [new PriceMinute($at, Decimal::of(1), Decimal::of(1), Decimal::of(1), Decimal::of(1))]);

    scalpingBackfill(['--candles' => 'no']);
    [, $again] = scalpingBackfill(['--candles' => 'no']);

    expect((string) $repository->marks($btc, $at, $at)[0]->close)->toBe('1')
        ->and((string) $repository->marks($btc, $at->addMinute(), $at->addMinute())[0]->close)->toBe('77485.8')
        ->and($again)->toContain('+0 new')
        ->and(DB::table('mark_price_minutes')->count())->toBe(1440);
});

it('takes the large-trade notional from the option, then the instrument default', function (): void {
    scalpingBackfill(['--candles' => 'no', '--large' => '250000', '--oi' => false, '--mark' => false]);

    expect(DB::table('trade_flow')->distinct()->pluck('threshold')->all())->toBe(['250000.000000000000000000']);
});

it('refuses scalping data for a spot instrument', function (): void {
    Instrument::register('binance', MarketType::Spot, Symbol::parse('BTC/USDT'), CarbonImmutable::parse('2026-11-01', 'UTC'));

    expect(Artisan::call('market:backfill', ['symbol' => 'BTC/USDT', '--from' => '2026-11-01', '--flow' => true]))->toBe(2)
        ->and(Artisan::output())->toContain('--flow, --oi and --mark are for perpetuals');
});
