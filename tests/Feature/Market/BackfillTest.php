<?php

declare(strict_types=1);

use App\Market\Backfill\Backfill;
use App\Market\Data\MarketType;
use App\Market\Models\Instrument;
use App\Support\Clock\Clock;
use App\Support\Clock\SimClock;
use App\Support\Symbol;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Tests\Support\ArchiveFactory;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $directory = storage_path('framework/testing/binance-archive');
    File::deleteDirectory($directory);
    config()->set('exchanges.binance.archive_cache', $directory);
});

/**
 * Fakes Binance for a backfill of 2026-10-01 to 2026-11-03 12:00 seen from 2026-11-03 12:00:
 * the October monthly archive, daily archives for November 1 and 2, REST for November 3.
 */
function fakeBinanceForBackfill(): void
{
    $october = ArchiveFactory::make('BTCUSDT-1m-2026-10.zip', [
        ArchiveFactory::spotLine(1793318400000), // 2026-10-30 00:00
        ArchiveFactory::spotLine(1793491140000), // 2026-10-31 23:59
    ]);
    $first = ArchiveFactory::make('BTCUSDT-1m-2026-11-01.zip', [ArchiveFactory::spotLine(1793491200000)]);
    $second = ArchiveFactory::make('BTCUSDT-1m-2026-11-02.zip', [ArchiveFactory::spotLine(1793577600000)]);
    $rest = [[1793664000000, '100.0', '101.0', '99.0', '100.5', '2.0', 1793664059999, '201.0', 7, '1.0', '100.5', '0']];

    Http::fake([
        '*/monthly/klines/BTCUSDT/1m/BTCUSDT-1m-2026-10.zip.CHECKSUM' => Http::response($october['checksum']),
        '*/monthly/klines/BTCUSDT/1m/BTCUSDT-1m-2026-10.zip' => Http::response($october['zip']),
        '*/daily/klines/BTCUSDT/1m/BTCUSDT-1m-2026-11-01.zip.CHECKSUM' => Http::response($first['checksum']),
        '*/daily/klines/BTCUSDT/1m/BTCUSDT-1m-2026-11-01.zip' => Http::response($first['zip']),
        '*/daily/klines/BTCUSDT/1m/BTCUSDT-1m-2026-11-02.zip.CHECKSUM' => Http::response($second['checksum']),
        '*/daily/klines/BTCUSDT/1m/BTCUSDT-1m-2026-11-02.zip' => Http::response($second['zip']),
        'data-api.binance.vision/api/v3/klines*' => Http::response(json_encode($rest, JSON_THROW_ON_ERROR)),
    ]);
}

it('takes full months from monthly archives, past days from daily ones and today from REST', function (): void {
    fakeBinanceForBackfill();
    app()->instance(Clock::class, new SimClock(CarbonImmutable::parse('2026-11-03 12:00:00', 'UTC')));
    $instrument = Instrument::register('binance', MarketType::Spot, Symbol::parse('BTC/USDT'), CarbonImmutable::parse('2026-11-03', 'UTC'));

    $report = app(Backfill::class)->candles($instrument, CarbonImmutable::parse('2026-10-01', 'UTC'), CarbonImmutable::parse('2026-11-03 12:00:00', 'UTC'));

    expect(array_map(fn ($step): string => $step->source, $report->steps))->toBe(['monthly 2026-10', 'daily 2026-11-01', 'daily 2026-11-02', 'rest 2026-11-03'])
        ->and($report->failure)->toBeNull()
        ->and(DB::table('candles')->count())->toBe(5);

    Http::assertNotSent(fn (Request $request): bool => str_contains($request->url(), '2026-11-03.zip'));
});

it('only keeps candles inside the asked range', function (): void {
    $day = ArchiveFactory::make('BTCUSDT-1m-2026-10-31.zip', [
        ArchiveFactory::spotLine(1793404800000), // 2026-10-31 00:00
        ArchiveFactory::spotLine(1793491140000), // 2026-10-31 23:59
    ]);
    Http::fake([
        '*/daily/klines/BTCUSDT/1m/BTCUSDT-1m-2026-10-31.zip.CHECKSUM' => Http::response($day['checksum']),
        '*/daily/klines/BTCUSDT/1m/BTCUSDT-1m-2026-10-31.zip' => Http::response($day['zip']),
    ]);
    app()->instance(Clock::class, new SimClock(CarbonImmutable::parse('2026-11-03 12:00:00', 'UTC')));
    $instrument = Instrument::register('binance', MarketType::Spot, Symbol::parse('BTC/USDT'), CarbonImmutable::parse('2026-11-03', 'UTC'));

    $report = app(Backfill::class)->candles($instrument, CarbonImmutable::parse('2026-10-31 12:00:00', 'UTC'), CarbonImmutable::parse('2026-10-31 23:59:00', 'UTC'));

    expect(array_map(fn ($step): string => $step->source, $report->steps))->toBe(['daily 2026-10-31'])
        ->and(DB::table('candles')->count())->toBe(1);
});

it('falls back from a missing monthly archive to the daily archives of that month', function (): void {
    Http::fake(function (Request $request) {
        if (str_contains($request->url(), '/monthly/')) {
            return Http::response('Not found', 404);
        }

        preg_match('/BTCUSDT-1m-(\d{4}-\d{2}-\d{2})\.zip/', $request->url(), $match);
        $day = CarbonImmutable::parse($match[1] ?? '2026-10-01', 'UTC');
        $archive = ArchiveFactory::make("BTCUSDT-1m-{$day->format('Y-m-d')}.zip", [ArchiveFactory::spotLine($day->getTimestamp() * 1000)]);

        return Http::response(str_ends_with($request->url(), '.CHECKSUM') ? $archive['checksum'] : $archive['zip']);
    });
    app()->instance(Clock::class, new SimClock(CarbonImmutable::parse('2026-11-03 12:00:00', 'UTC')));
    $instrument = Instrument::register('binance', MarketType::Spot, Symbol::parse('BTC/USDT'), CarbonImmutable::parse('2026-11-03', 'UTC'));

    $report = app(Backfill::class)->candles($instrument, CarbonImmutable::parse('2026-10-01', 'UTC'), CarbonImmutable::parse('2026-10-31 23:59:00', 'UTC'));

    expect($report->steps)->toHaveCount(31)
        ->and($report->steps[0]->source)->toBe('daily 2026-10-01')
        ->and(DB::table('candles')->count())->toBe(31);
});

it('takes funding from monthly archives', function (): void {
    Http::fake([
        '*/monthly/fundingRate/BTCUSDT/BTCUSDT-fundingRate-2026-09.zip.CHECKSUM' => Http::response(binanceFixture('archive/perp-BTCUSDT-fundingRate-2026-09.zip.CHECKSUM')),
        '*/monthly/fundingRate/BTCUSDT/BTCUSDT-fundingRate-2026-09.zip' => Http::response(binanceFixture('archive/perp-BTCUSDT-fundingRate-2026-09.zip')),
    ]);
    app()->instance(Clock::class, new SimClock(CarbonImmutable::parse('2026-11-03 12:00:00', 'UTC')));
    $perp = Instrument::register('binance', MarketType::Perp, Symbol::parse('BTC/USDT:USDT'), CarbonImmutable::parse('2026-11-03', 'UTC'));

    $report = app(Backfill::class)->funding($perp, CarbonImmutable::parse('2026-09-01', 'UTC'), CarbonImmutable::parse('2026-09-30 23:59:00', 'UTC'));

    expect(array_map(fn ($step): string => $step->source, $report->steps))->toBe(['funding monthly 2026-09'])
        ->and(DB::table('funding_rates')->count())->toBe(90);
});

it('backfills from the command line', function (): void {
    fakeBinanceForBackfill();
    app()->instance(Clock::class, new SimClock(CarbonImmutable::parse('2026-11-03 12:00:00', 'UTC')));

    expect(Artisan::call('market:backfill', ['symbol' => 'BTC/USDT', '--from' => '2026-10', '--to' => '2026-11-03 12:00']))->toBe(0)
        ->and(Artisan::output())->toContain('BTC/USDT spot')->toContain('monthly 2026-10')->toContain('rest 2026-11-03');
});

it('reports a REST failure without losing what the archives brought', function (): void {
    $first = ArchiveFactory::make('BTCUSDT-1m-2026-11-01.zip', [ArchiveFactory::spotLine(1793491200000)]);
    Http::fake([
        '*/daily/klines/BTCUSDT/1m/BTCUSDT-1m-2026-11-01.zip.CHECKSUM' => Http::response($first['checksum']),
        '*/daily/klines/BTCUSDT/1m/BTCUSDT-1m-2026-11-01.zip' => Http::response($first['zip']),
        '*/daily/*' => Http::response('Not found', 404),
        'data-api.binance.vision/*' => Http::response(['code' => 0, 'msg' => 'refused'], 451),
    ]);
    app()->instance(Clock::class, new SimClock(CarbonImmutable::parse('2026-11-03 12:00:00', 'UTC')));
    $instrument = Instrument::register('binance', MarketType::Spot, Symbol::parse('BTC/USDT'), CarbonImmutable::parse('2026-11-03', 'UTC'));

    $report = app(Backfill::class)->candles($instrument, CarbonImmutable::parse('2026-11-01', 'UTC'), CarbonImmutable::parse('2026-11-03 12:00:00', 'UTC'));

    expect($report->failure)->toContain('451')
        ->and(DB::table('candles')->count())->toBe(1);
});
