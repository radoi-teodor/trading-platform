<?php

declare(strict_types=1);

use App\Market\CandleRepository;
use App\Market\Data\CandleSource;
use App\Market\Data\MarketType;
use App\Market\Models\Instrument;
use App\Market\Quality\QualityCheck;
use App\Support\Symbol;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Tests\Support\CandleFactory;

uses(RefreshDatabase::class);

function qualityInstrument(): Instrument
{
    return Instrument::register('binance', MarketType::Spot, Symbol::parse('BTC/USDT'), CarbonImmutable::parse('2026-11-01', 'UTC'));
}

/** @param list<int> $minutes */
function storeMinutes(Instrument $instrument, array $minutes): void
{
    app(CandleRepository::class)->upsert($instrument, CandleSource::Archive, array_map(fn (int $m) => CandleFactory::at($m), $minutes));
}

/** @param array{from: CarbonImmutable, to: CarbonImmutable} $range */
function qualityRange(array $range): string
{
    return $range['from']->format('m-d H:i').' '.$range['to']->format('m-d H:i');
}

it('does not count minutes before the listing or from the delisting as gaps (D-89)', function (): void {
    $btc = qualityInstrument();
    $btc->update(['listed_at' => CandleFactory::time(3), 'delisted_at' => CandleFactory::time(7)]);
    storeMinutes($btc, [3, 4, 5, 6]);

    $report = app(QualityCheck::class)->run($btc, CandleFactory::time(0), CandleFactory::time(9));

    expect($report->expectedMinutes)->toBe(4)
        ->and($report->missingMinutes)->toBe(0)
        ->and($report->gaps)->toBe([]);
});

it('does not count the minutes of a break in trading as gaps, nor the candles stored in it (D-89)', function (): void {
    $btc = qualityInstrument();
    // Delisted at minute 3 and listed again at minute 8: the archive holds flat candles for a part of the break.
    DB::table('instrument_breaks')->insert(['instrument_id' => $btc->id, 'stopped_at' => CandleFactory::time(3), 'resumed_at' => CandleFactory::time(8)]);
    app(CandleRepository::class)->upsert($btc, CandleSource::Archive, [
        CandleFactory::at(0), CandleFactory::at(1), CandleFactory::at(3, '100', '0'), CandleFactory::at(4, '100', '0'), CandleFactory::at(8), CandleFactory::at(10),
    ]);

    $report = app(QualityCheck::class)->run($btc, CandleFactory::time(0), CandleFactory::time(10));

    expect($report->expectedMinutes)->toBe(6)
        ->and($report->storedMinutes)->toBe(4)
        ->and($report->missingMinutes)->toBe(2)
        ->and(array_map(fn (array $gap): string => $gap['from']->format('H:i').'-'.$gap['to']->format('H:i'), $report->gaps))->toBe(['00:02-00:02', '00:09-00:09'])
        ->and($report->zeroVolumeRuns)->toBe([]);
});

it('reports missing minutes as ranges', function (): void {
    $btc = qualityInstrument();
    storeMinutes($btc, [0, 1, 2, 5, 6, 9]);

    $report = app(QualityCheck::class)->run($btc, CandleFactory::time(0), CandleFactory::time(9));

    expect($report->missingMinutes)->toBe(4)
        ->and(array_map(fn (array $gap): string => $gap['from']->format('H:i').'-'.$gap['to']->format('H:i'), $report->gaps))->toBe(['00:03-00:04', '00:07-00:08'])
        ->and($report->hasErrors())->toBeTrue();
});

it('finds nothing wrong in a complete, plausible range', function (): void {
    $btc = qualityInstrument();
    storeMinutes($btc, range(0, 59));

    $report = app(QualityCheck::class)->run($btc, CandleFactory::time(0), CandleFactory::time(59));

    expect($report->hasErrors())->toBeFalse()
        ->and($report->hasWarnings())->toBeFalse()
        ->and($report->storedMinutes)->toBe(60);
});

it('warns about long runs without volume', function (): void {
    $btc = qualityInstrument();
    $candles = array_map(fn (int $m) => CandleFactory::at($m, '100', $m >= 10 && $m <= 41 ? '0' : '1'), range(0, 59));
    app(CandleRepository::class)->upsert($btc, CandleSource::Archive, $candles);

    $report = app(QualityCheck::class)->run($btc, CandleFactory::time(0), CandleFactory::time(59));

    expect($report->zeroVolumeRuns)->toHaveCount(1)
        ->and($report->zeroVolumeRuns[0]['from']->format('H:i'))->toBe('00:10')
        ->and($report->hasErrors())->toBeFalse()
        ->and($report->hasWarnings())->toBeTrue();
});

it('warns about a close far from its neighbours', function (): void {
    $btc = qualityInstrument();
    $candles = array_map(fn (int $m) => CandleFactory::at($m, $m === 20 ? '110' : '100'), range(0, 59));
    app(CandleRepository::class)->upsert($btc, CandleSource::Archive, $candles);

    $report = app(QualityCheck::class)->run($btc, CandleFactory::time(0), CandleFactory::time(59));

    expect(array_map(fn (CarbonImmutable $t): string => $t->format('H:i'), $report->outliers))->toBe(['00:20']);
});

it('counts OHLC violations that reached the table before the constraint', function (): void {
    $btc = qualityInstrument();
    storeMinutes($btc, range(0, 4));
    DB::statement('ALTER TABLE candles DROP CONSTRAINT candles_consistent');
    DB::table('candles')->where('open_time', '2026-10-01 00:02:00+00')->update(['high' => '50']);

    $report = app(QualityCheck::class)->run($btc, CandleFactory::time(0), CandleFactory::time(4));

    expect($report->ohlcViolations)->toBe(1)
        ->and($report->hasErrors())->toBeTrue();
});

it('exits with an error code when a gap is found', function (): void {
    storeMinutes(qualityInstrument(), [0, 2]);

    expect(Artisan::call('market:check', ['symbol' => 'BTC/USDT', '--from' => '2026-10-01', '--to' => '2026-10-01 00:02']))->toBe(1)
        ->and(Artisan::output())->toContain('1 missing')->toContain('00:01');
});

it('names a break in trading inside the checked window and does not fail for it (D-89)', function (): void {
    $btc = qualityInstrument();
    storeMinutes($btc, [0, 1, 5]);
    DB::table('instrument_breaks')->insert(['instrument_id' => $btc->id, 'stopped_at' => CandleFactory::time(2), 'resumed_at' => CandleFactory::time(5)]);

    expect(Artisan::call('market:check', ['symbol' => 'BTC/USDT', '--from' => '2026-10-01', '--to' => '2026-10-01 00:05']))->toBe(0)
        ->and(Artisan::output())->toContain('3 of 3 minutes, 0 missing')->toContain('did not trade 2026-10-01 00:02 to 2026-10-01 00:05');
});

it('finds gaps, zero-volume runs and outliers across the days it reads one at a time', function (): void {
    $instrument = qualityInstrument();
    $minutes = [];

    foreach (range(0, 3 * 1440 - 1) as $minute) {
        if ($minute >= 1438 && $minute <= 1441) {
            continue; // a gap over the first midnight
        }

        $minutes[] = CandleFactory::at($minute, $minute === 2880 ? '200' : '100', $minute >= 2870 && $minute <= 2900 ? '0' : '1');
    }

    app(CandleRepository::class)->upsert($instrument, CandleSource::Archive, $minutes);
    $report = app(QualityCheck::class)->run($instrument, CandleFactory::time(0), CandleFactory::time(3 * 1440 - 1));

    expect($report->storedMinutes)->toBe(3 * 1440 - 4)
        ->and($report->missingMinutes)->toBe(4)
        ->and(array_map(qualityRange(...), $report->gaps))->toBe(['10-01 23:58 10-02 00:01'])
        ->and(array_map(qualityRange(...), $report->zeroVolumeRuns))->toBe(['10-02 23:50 10-03 00:20'])
        ->and(array_map(fn (CarbonImmutable $t): string => $t->format('m-d H:i'), $report->outliers))->toBe(['10-03 00:00']);
});
