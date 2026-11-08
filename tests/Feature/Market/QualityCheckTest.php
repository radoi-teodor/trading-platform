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
