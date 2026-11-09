<?php

declare(strict_types=1);

use App\Market\Backfill\Backfill;
use App\Market\Backfill\BackfillReport;
use App\Market\Data\InstrumentStatus;
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
 * The archive and REST steps of a report, without the gap fills that the deliberately sparse test archives cause.
 *
 * @return list<string>
 */
function mainSteps(BackfillReport $report): array
{
    return array_values(array_filter(
        array_map(fn ($step): string => $step->source, $report->steps),
        fn (string $source): bool => ! str_starts_with($source, 'rest gap'),
    ));
}

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

    expect(mainSteps($report))->toBe(['monthly 2026-10', 'daily 2026-11-01', 'daily 2026-11-02', 'rest 2026-11-03'])
        ->and($report->failure)->toBeNull()
        ->and(DB::table('candles')->count())->toBe(5);

    Http::assertNotSent(fn (Request $request): bool => str_contains($request->url(), '2026-11-03.zip'));
});

// A month of 1-minute candles held as objects is about 160 MB; SOL/USDT:USDT for October 2026 passed Herd's
// 128 MB limit. The archive is read line by line and stored as it is read, so the memory stays small.
it('stores a full monthly archive without holding the month in memory', function (): void {
    $start = 1790812800000; // 2026-10-01 00:00 UTC
    $october = ArchiveFactory::make('BTCUSDT-1m-2026-10.zip', array_map(
        fn (int $minute): string => ArchiveFactory::spotLine($start + $minute * 60_000),
        range(0, 31 * 1440 - 1),
    ));
    Http::fake([
        '*/monthly/klines/BTCUSDT/1m/BTCUSDT-1m-2026-10.zip.CHECKSUM' => Http::response($october['checksum']),
        '*/monthly/klines/BTCUSDT/1m/BTCUSDT-1m-2026-10.zip' => Http::response($october['zip']),
    ]);
    app()->instance(Clock::class, new SimClock(CarbonImmutable::parse('2026-11-03 12:00:00', 'UTC')));
    $instrument = Instrument::register('binance', MarketType::Spot, Symbol::parse('BTC/USDT'), CarbonImmutable::parse('2026-11-03', 'UTC'));
    $backfill = app(Backfill::class);
    memory_reset_peak_usage();
    $before = memory_get_usage();

    $report = $backfill->candles($instrument, CarbonImmutable::parse('2026-10-01', 'UTC'), CarbonImmutable::parse('2026-10-31 23:59:00', 'UTC'));

    expect($report->steps)->toHaveCount(1)
        ->and($report->steps[0]->result->inserted)->toBe(44640)
        ->and($report->failure)->toBeNull()
        ->and(DB::table('candles')->count())->toBe(44640)
        ->and(memory_get_peak_usage() - $before)->toBeLessThan(48 * 1024 * 1024);
});

it('starts at the listing, without asking for archives or REST before it (D-89)', function (): void {
    fakeBinanceForBackfill();
    app()->instance(Clock::class, new SimClock(CarbonImmutable::parse('2026-11-03 12:00:00', 'UTC')));
    $instrument = Instrument::register('binance', MarketType::Spot, Symbol::parse('BTC/USDT'), CarbonImmutable::parse('2026-11-03', 'UTC'));
    $instrument->update(['listed_at' => CarbonImmutable::parse('2026-11-02 00:00:00', 'UTC')]);

    $report = app(Backfill::class)->candles($instrument, CarbonImmutable::parse('2026-10-01', 'UTC'), CarbonImmutable::parse('2026-11-03 12:00:00', 'UTC'));

    expect(mainSteps($report))->toBe(['daily 2026-11-02', 'rest 2026-11-03'])
        ->and($report->failure)->toBeNull();
    Http::assertNotSent(fn (Request $request): bool => str_contains($request->url(), '2026-10.zip') || str_contains($request->url(), '2026-11-01.zip'));
});

it('stops at the delisting and never asks REST for a delisted instrument (D-89)', function (): void {
    fakeBinanceForBackfill();
    app()->instance(Clock::class, new SimClock(CarbonImmutable::parse('2026-11-03 12:00:00', 'UTC')));
    $instrument = Instrument::register('binance', MarketType::Spot, Symbol::parse('BTC/USDT'), CarbonImmutable::parse('2026-11-03', 'UTC'), InstrumentStatus::Delisted);
    $instrument->update(['delisted_at' => CarbonImmutable::parse('2026-11-02 00:00:00', 'UTC')]);

    $report = app(Backfill::class)->candles($instrument, CarbonImmutable::parse('2026-10-01', 'UTC'), CarbonImmutable::parse('2026-11-03 12:00:00', 'UTC'));

    // The sparse test archives leave gaps; for a delisted instrument they stay gaps, REST knows nothing of it.
    expect(array_map(fn ($step): string => $step->source, $report->steps))->toBe(['monthly 2026-10', 'daily 2026-11-01'])
        ->and($report->failure)->toBeNull();
    Http::assertNotSent(fn (Request $request): bool => str_contains($request->url(), '/klines?') || str_contains($request->url(), '2026-11-02.zip'));
});

it('only keeps candles inside the asked range', function (): void {
    $day = ArchiveFactory::make('BTCUSDT-1m-2026-10-31.zip', [
        ArchiveFactory::spotLine(1793404800000), // 2026-10-31 00:00
        ArchiveFactory::spotLine(1793491140000), // 2026-10-31 23:59
    ]);
    Http::fake([
        '*/daily/klines/BTCUSDT/1m/BTCUSDT-1m-2026-10-31.zip.CHECKSUM' => Http::response($day['checksum']),
        '*/daily/klines/BTCUSDT/1m/BTCUSDT-1m-2026-10-31.zip' => Http::response($day['zip']),
        'data-api.binance.vision/api/v3/klines*' => Http::response([]),
    ]);
    app()->instance(Clock::class, new SimClock(CarbonImmutable::parse('2026-11-03 12:00:00', 'UTC')));
    $instrument = Instrument::register('binance', MarketType::Spot, Symbol::parse('BTC/USDT'), CarbonImmutable::parse('2026-11-03', 'UTC'));

    $report = app(Backfill::class)->candles($instrument, CarbonImmutable::parse('2026-10-31 12:00:00', 'UTC'), CarbonImmutable::parse('2026-10-31 23:59:00', 'UTC'));

    expect(mainSteps($report))->toBe(['daily 2026-10-31'])
        ->and(DB::table('candles')->count())->toBe(1);
});

it('falls back from a missing monthly archive to the daily archives of that month', function (): void {
    // Each day's archive is built once: a zip records file times, so building it twice can change its bytes.
    $archives = [];
    Http::fake(function (Request $request) use (&$archives) {
        if (str_contains($request->url(), '/monthly/')) {
            return Http::response('Not found', 404);
        }

        if (str_contains($request->url(), '/api/v3/klines')) {
            return Http::response([]);
        }

        preg_match('/BTCUSDT-1m-(\d{4}-\d{2}-\d{2})\.zip/', $request->url(), $match);
        $label = $match[1] ?? '2026-10-01';
        $day = CarbonImmutable::parse($label, 'UTC');
        $archives[$label] ??= ArchiveFactory::make("BTCUSDT-1m-{$label}.zip", [ArchiveFactory::spotLine($day->getTimestamp() * 1000)]);

        return Http::response(str_ends_with($request->url(), '.CHECKSUM') ? $archives[$label]['checksum'] : $archives[$label]['zip']);
    });
    app()->instance(Clock::class, new SimClock(CarbonImmutable::parse('2026-11-03 12:00:00', 'UTC')));
    $instrument = Instrument::register('binance', MarketType::Spot, Symbol::parse('BTC/USDT'), CarbonImmutable::parse('2026-11-03', 'UTC'));

    $report = app(Backfill::class)->candles($instrument, CarbonImmutable::parse('2026-10-01', 'UTC'), CarbonImmutable::parse('2026-10-31 23:59:00', 'UTC'));

    expect(mainSteps($report))->toHaveCount(31)
        ->and(mainSteps($report)[0])->toBe('daily 2026-10-01')
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

it('takes funding only inside a delisted perpetual\'s lifetime, never from REST (D-89)', function (): void {
    Http::fake([
        '*/monthly/fundingRate/BTCUSDT/BTCUSDT-fundingRate-2026-09.zip.CHECKSUM' => Http::response(binanceFixture('archive/perp-BTCUSDT-fundingRate-2026-09.zip.CHECKSUM')),
        '*/monthly/fundingRate/BTCUSDT/BTCUSDT-fundingRate-2026-09.zip' => Http::response(binanceFixture('archive/perp-BTCUSDT-fundingRate-2026-09.zip')),
    ]);
    app()->instance(Clock::class, new SimClock(CarbonImmutable::parse('2026-11-03 12:00:00', 'UTC')));
    $perp = Instrument::register('binance', MarketType::Perp, Symbol::parse('BTC/USDT:USDT'), CarbonImmutable::parse('2026-11-03', 'UTC'), InstrumentStatus::Delisted);
    $perp->update(['listed_at' => CarbonImmutable::parse('2026-09-01', 'UTC'), 'delisted_at' => CarbonImmutable::parse('2026-10-01', 'UTC')]);

    $report = app(Backfill::class)->funding($perp, CarbonImmutable::parse('2026-07-01', 'UTC'), CarbonImmutable::parse('2026-11-03 12:00:00', 'UTC'));

    expect(array_map(fn ($step): string => $step->source, $report->steps))->toBe(['funding monthly 2026-09'])
        ->and($report->failure)->toBeNull();
});

it('fills through REST the minutes a published archive does not have', function (): void {
    // Binance's USD-M archives for October 2026 have no 2026-10-28; the same gap is simulated here on one day.
    $day = ArchiveFactory::make('BTCUSDT-1m-2026-10-31.zip', [
        ArchiveFactory::spotLine(1793404800000), // 00:00
        ArchiveFactory::spotLine(1793404920000), // 00:02, so 00:01 is missing
    ]);
    $missing = [[1793404860000, '100.0', '101.0', '99.0', '100.5', '2.0', 1793404919999, '201.0', 7, '1.0', '100.5', '0']];
    Http::fake([
        '*/daily/klines/BTCUSDT/1m/BTCUSDT-1m-2026-10-31.zip.CHECKSUM' => Http::response($day['checksum']),
        '*/daily/klines/BTCUSDT/1m/BTCUSDT-1m-2026-10-31.zip' => Http::response($day['zip']),
        'data-api.binance.vision/api/v3/klines*' => Http::response(json_encode($missing, JSON_THROW_ON_ERROR)),
    ]);
    app()->instance(Clock::class, new SimClock(CarbonImmutable::parse('2026-11-03 12:00:00', 'UTC')));
    $instrument = Instrument::register('binance', MarketType::Spot, Symbol::parse('BTC/USDT'), CarbonImmutable::parse('2026-11-03', 'UTC'));

    $report = app(Backfill::class)->candles($instrument, CarbonImmutable::parse('2026-10-31 00:00:00', 'UTC'), CarbonImmutable::parse('2026-10-31 00:02:00', 'UTC'));

    expect(array_map(fn ($step): string => $step->source, $report->steps))->toBe(['daily 2026-10-31', 'rest gap 2026-10-31 00:01'])
        ->and(DB::table('candles')->count())->toBe(3);
    Http::assertSent(fn (Request $request): bool => str_contains($request->url(), 'startTime=1793404860000'));
});

it('reports a format problem for one instrument instead of crashing', function (): void {
    $broken = ArchiveFactory::make('BTCUSDT-1m-2026-10-31.zip', ['1793404800000000,not-a-price,101,99,100,1,1793404859999999,100,1,0,0,0']);
    Http::fake([
        '*/daily/klines/BTCUSDT/1m/BTCUSDT-1m-2026-10-31.zip.CHECKSUM' => Http::response($broken['checksum']),
        '*/daily/klines/BTCUSDT/1m/BTCUSDT-1m-2026-10-31.zip' => Http::response($broken['zip']),
    ]);
    app()->instance(Clock::class, new SimClock(CarbonImmutable::parse('2026-11-03 12:00:00', 'UTC')));
    $instrument = Instrument::register('binance', MarketType::Spot, Symbol::parse('BTC/USDT'), CarbonImmutable::parse('2026-11-03', 'UTC'));

    $report = app(Backfill::class)->candles($instrument, CarbonImmutable::parse('2026-10-31', 'UTC'), CarbonImmutable::parse('2026-10-31 23:59:00', 'UTC'));

    expect($report->failure)->toContain('not-a-price');
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
