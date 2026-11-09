<?php

declare(strict_types=1);

use App\Market\Archive\ArchiveMissing;
use App\Market\Archive\BinanceArchive;
use App\Market\Data\OpenInterest;
use App\Market\Data\PriceMinute;
use App\Market\Data\TradeFlow;
use App\Support\Number\Decimal;
use Carbon\CarbonImmutable;
use Tests\Support\ScalpingFixtures;

const SCALPING_DAY = '2026-11-01';

beforeEach(function (): void {
    ScalpingFixtures::fake();
    config()->set('exchanges.binance.archive_cache', storage_path('framework/testing/archive-'.getmypid()));
});

/** @return array{string, string, string, string, int, int, string} */
function flowRow(TradeFlow $f): array
{
    return [$f->openTime->toIso8601ZuluString(), (string) $f->largeBuyVolume, (string) $f->largeSellVolume, (string) $f->largestNotional, $f->largeBuyCount, $f->largeSellCount, (string) $f->threshold];
}

it('reduces aggregated trades to one row a minute, matching an independent computation', function (): void {
    $flow = app(BinanceArchive::class)->tradeFlow('BTCUSDT', CarbonImmutable::parse(SCALPING_DAY, 'UTC'), Decimal::of('100000'));

    // Expected values from Python's decimal module over the same file (tests/Fixtures/binance/scalping/README.md).
    expect($flow)->toHaveCount(22)
        ->and(flowRow($flow[0]))->toBe(['2026-11-01T00:00:00Z', '0', '13.291', '388394.2062', 0, 6, '100000'])
        ->and(flowRow($flow[1]))->toBe(['2026-11-01T00:01:00Z', '4.975', '26.588', '623398.2005', 2, 7, '100000'])
        ->and(flowRow($flow[2]))->toBe(['2026-11-01T00:02:00Z', '0', '0', '73301.4722', 0, 0, '100000'])
        ->and(flowRow($flow[21]))->toBe(['2026-11-01T00:21:00Z', '34.86', '52.393', '3493622.5', 12, 3, '100000'])
        ->and((string) array_reduce($flow, fn (Decimal $s, TradeFlow $f): Decimal => $s->plus($f->largeBuyVolume), Decimal::zero()))->toBe('101.124')
        ->and((string) array_reduce($flow, fn (Decimal $s, TradeFlow $f): Decimal => $s->plus($f->largeSellVolume), Decimal::zero()))->toBe('174.245')
        ->and(array_sum(array_map(fn (TradeFlow $f): int => $f->largeBuyCount + $f->largeSellCount, $flow)))->toBe(78);
});

it('reads open interest every 5 minutes in time order, from its text timestamps', function (): void {
    $rows = app(BinanceArchive::class)->openInterest('BTCUSDT', CarbonImmutable::parse(SCALPING_DAY, 'UTC'));
    $view = fn (OpenInterest $o): array => [$o->time->toIso8601ZuluString(), (string) $o->contracts, (string) $o->value];

    expect($rows)->toHaveCount(288)
        ->and($view($rows[0]))->toBe(['2026-11-01T00:00:00Z', '96913.056', '7510208385.689683'])
        ->and($view($rows[287]))->toBe(['2026-11-01T23:55:00Z', '95255.531', '7459972293.553056']);
});

it('reads the mark price and the premium index of every minute', function (): void {
    $archive = app(BinanceArchive::class);
    $marks = $archive->markPrices('BTCUSDT', CarbonImmutable::parse(SCALPING_DAY, 'UTC'));
    $premium = $archive->premiumIndex('BTCUSDT', CarbonImmutable::parse(SCALPING_DAY, 'UTC'));
    $view = fn (PriceMinute $p): array => [$p->openTime->toIso8601ZuluString(), (string) $p->open, (string) $p->high, (string) $p->low, (string) $p->close];

    expect($marks)->toHaveCount(1440)->and($premium)->toHaveCount(1440)
        ->and($view($marks[0]))->toBe(['2026-11-01T00:00:00Z', '77528.51118333', '77528.51118333', '77483.75088333', '77483.75088333'])
        ->and($view($premium[0]))->toBe(['2026-11-01T00:00:00Z', '-0.00051473', '-0.00039698', '-0.00059669', '-0.00054368'])
        ->and($view($marks[1439])[0])->toBe('2026-11-01T23:59:00Z');
});

it('keeps no copy of the large trade archives, and keeps the small ones', function (): void {
    $cache = storage_path('framework/testing/archive-'.getmypid());
    app(BinanceArchive::class)->tradeFlow('BTCUSDT', CarbonImmutable::parse(SCALPING_DAY, 'UTC'), Decimal::of('100000'));
    app(BinanceArchive::class)->markPrices('BTCUSDT', CarbonImmutable::parse(SCALPING_DAY, 'UTC'));

    expect(glob($cache.'/futures/um/daily/aggTrades/BTCUSDT/*') ?: [])->toBe([])
        ->and(glob($cache.'/futures/um/daily/markPriceKlines/BTCUSDT/1m/*') ?: [])->toHaveCount(1);
});

it('says a day is not published yet', function (): void {
    app(BinanceArchive::class)->openInterest('BTCUSDT', CarbonImmutable::parse('2026-11-02', 'UTC'));
})->throws(ArchiveMissing::class, 'futures/um/daily/metrics/BTCUSDT/BTCUSDT-metrics-2026-11-02.zip.CHECKSUM is not published');
