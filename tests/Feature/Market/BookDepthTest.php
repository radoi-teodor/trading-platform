<?php

declare(strict_types=1);

use App\Market\Archive\BinanceArchive;
use App\Market\CandleRepository;
use App\Market\Data\BookDepth;
use App\Market\Data\Candle;
use App\Market\Data\CandleSource;
use App\Market\Data\MarketType;
use App\Market\Models\Instrument;
use App\Market\ScalpingRepository;
use App\Simulation\Charts\ChartSeries;
use App\Strategy\Dsl\DslError;
use App\Support\Number\Decimal;
use App\Support\Symbol;
use App\Support\Timeframe;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Tests\Support\ArchiveFactory;

uses(RefreshDatabase::class);

/**
 * Twelve levels of one snapshot in the real bookDepth layout: timestamp, percentage, depth, notional.
 *
 * @return list<string>
 */
function depthSnapshot(string $time, int $scale): array
{
    $lines = [];

    foreach (['-5.00', '-4.00', '-3.00', '-2.00', '-1.00', '-0.20', '0.20', '1.00', '2.00', '3.00', '4.00', '5.00'] as $i => $percentage) {
        $lines[] = "{$time},{$percentage},".(10 + $i).'.00000000,'.($scale * (100 + $i)).'.00000000';
    }

    return $lines;
}

it('keeps the last snapshot of each 5 minutes, with bid and ask notional within 1, 2 and 5 % (D-91)', function (): void {
    $directory = storage_path('framework/testing/binance-archive');
    File::deleteDirectory($directory);
    config()->set('exchanges.binance.archive_cache', $directory);
    $archive = ArchiveFactory::make('BTCUSDT-bookDepth-2026-11-01.zip', [
        'timestamp,percentage,depth,notional',
        ...depthSnapshot('2026-11-01 00:00:01', 1),
        ...depthSnapshot('2026-11-01 00:04:30', 2),  // the last of the first 5 minutes
        ...depthSnapshot('2026-11-01 00:05:01', 3),
    ]);
    Http::fake([
        '*/daily/bookDepth/BTCUSDT/BTCUSDT-bookDepth-2026-11-01.zip.CHECKSUM' => Http::response($archive['checksum']),
        '*/daily/bookDepth/BTCUSDT/BTCUSDT-bookDepth-2026-11-01.zip' => Http::response($archive['zip']),
    ]);

    $rows = app(BinanceArchive::class)->bookDepth('BTCUSDT', CarbonImmutable::parse('2026-11-01', 'UTC'));
    $view = fn (BookDepth $d): array => [$d->time->format('H:i'), (string) $d->bid(1), (string) $d->bid(2), (string) $d->bid(5), (string) $d->ask(1), (string) $d->ask(2), (string) $d->ask(5)];

    expect(array_map($view, $rows))->toBe([
        ['00:00', '208', '206', '200', '214', '216', '222'],
        ['00:05', '312', '309', '300', '321', '324', '333'],
    ])->and($rows[0]->knownAt()->format('H:i'))->toBe('00:05');
});

it('gives strategies the depth and its imbalance five minutes after its time, and only 1, 2 or 5 %', function (): void {
    $perp = Instrument::register('binance', MarketType::Perp, Symbol::parse('BTC/USDT:USDT'), CarbonImmutable::parse('2026-11-01', 'UTC'));
    $candles = [];

    for ($m = 0; $m < 15; $m++) {
        $candles[] = Candle::fromStrings(CarbonImmutable::parse('2026-11-01 00:00', 'UTC')->addMinutes($m), Timeframe::OneMinute, '100', '101', '99', '100', '1', '100', 1, '0.5');
    }

    app(CandleRepository::class)->upsert($perp, CandleSource::Archive, $candles);
    app(ScalpingRepository::class)->upsertDepth($perp, CandleSource::Archive, array_map(
        fn (int $i): BookDepth => new BookDepth(CarbonImmutable::parse('2026-11-01 00:00', 'UTC')->addMinutes(5 * $i), Decimal::of((string) (300 + $i)), Decimal::of('600'), Decimal::of('900'), Decimal::of('100'), Decimal::of('200'), Decimal::of('300')),
        range(0, 2),
    ));

    $chart = app(ChartSeries::class)->compute(Symbol::parse('BTC/USDT:USDT'), Timeframe::FiveMinutes, CarbonImmutable::parse('2026-11-01 00:00', 'UTC'), CarbonImmutable::parse('2026-11-01 00:15', 'UTC'), ['bid_depth(1%)', 'ask_depth(5%)', 'depth_imbalance(1%)']);

    // The candle closing at 00:05 sees the snapshot of 00:00 to 00:05; imbalance is (bid - ask) / (bid + ask).
    expect($chart['series']['bid_depth(1%)'])->toBe(['300', '301', '302'])
        ->and($chart['series']['ask_depth(5%)'])->toBe(['300', '300', '300'])
        ->and($chart['series']['depth_imbalance(1%)'][0])->toBe('0.5');

    app(ChartSeries::class)->compute(Symbol::parse('BTC/USDT:USDT'), Timeframe::FiveMinutes, CarbonImmutable::parse('2026-11-01 00:00', 'UTC'), CarbonImmutable::parse('2026-11-01 00:15', 'UTC'), ['bid_depth(3%)']);
})->throws(DslError::class, 'band of bid_depth must be 1%, 2% or 5%');
