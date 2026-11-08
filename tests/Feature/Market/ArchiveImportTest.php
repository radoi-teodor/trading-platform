<?php

declare(strict_types=1);

use App\Market\Archive\ArchiveMissing;
use App\Market\Archive\ArchivePeriod;
use App\Market\Archive\BinanceArchive;
use App\Market\Archive\ChecksumMismatch;
use App\Market\Data\MarketType;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;

function archiveBytes(string $name): string
{
    return binanceFixture('archive/'.$name);
}

function useArchiveCache(): void
{
    $directory = storage_path('framework/testing/binance-archive');
    File::deleteDirectory($directory);
    config()->set('exchanges.binance.archive_cache', $directory);
}

it('imports a spot daily archive written in microseconds', function (): void {
    useArchiveCache();
    Http::fake([
        'data.binance.vision/data/spot/daily/klines/BTCUSDT/1m/BTCUSDT-1m-2026-11-06.zip.CHECKSUM' => Http::response(archiveBytes('spot-BTCUSDT-1m-2026-11-06.zip.CHECKSUM')),
        'data.binance.vision/data/spot/daily/klines/BTCUSDT/1m/BTCUSDT-1m-2026-11-06.zip' => Http::response(archiveBytes('spot-BTCUSDT-1m-2026-11-06.zip')),
    ]);

    $candles = app(BinanceArchive::class)->klines(MarketType::Spot, 'BTCUSDT', ArchivePeriod::daily(CarbonImmutable::parse('2026-11-06', 'UTC')));

    expect($candles)->toHaveCount(1440)
        ->and($candles[0]->openTime->toIso8601String())->toBe('2026-11-06T00:00:00+00:00')
        ->and((string) $candles[0]->open)->toBe('76088.01')
        ->and((string) $candles[0]->close)->toBe('76076.96')
        ->and($candles[0]->tradeCount)->toBe(5184)
        ->and($candles[1439]->openTime->toIso8601String())->toBe('2026-11-06T23:59:00+00:00');
});

it('imports a perpetual daily archive and skips its header line', function (): void {
    useArchiveCache();
    Http::fake([
        'data.binance.vision/data/futures/um/daily/klines/BTCUSDT/1m/BTCUSDT-1m-2026-11-06.zip.CHECKSUM' => Http::response(archiveBytes('perp-BTCUSDT-1m-2026-11-06.zip.CHECKSUM')),
        'data.binance.vision/data/futures/um/daily/klines/BTCUSDT/1m/BTCUSDT-1m-2026-11-06.zip' => Http::response(archiveBytes('perp-BTCUSDT-1m-2026-11-06.zip')),
    ]);

    $candles = app(BinanceArchive::class)->klines(MarketType::Perp, 'BTCUSDT', ArchivePeriod::daily(CarbonImmutable::parse('2026-11-06', 'UTC')));

    expect($candles)->toHaveCount(1440)
        ->and($candles[0]->openTime->toIso8601String())->toBe('2026-11-06T00:00:00+00:00')
        ->and((string) $candles[0]->close)->toBe('76042.9');
});

it('imports monthly funding rates with their interval', function (): void {
    useArchiveCache();
    Http::fake([
        'data.binance.vision/data/futures/um/monthly/fundingRate/BTCUSDT/BTCUSDT-fundingRate-2026-09.zip.CHECKSUM' => Http::response(archiveBytes('perp-BTCUSDT-fundingRate-2026-09.zip.CHECKSUM')),
        'data.binance.vision/data/futures/um/monthly/fundingRate/BTCUSDT/BTCUSDT-fundingRate-2026-09.zip' => Http::response(archiveBytes('perp-BTCUSDT-fundingRate-2026-09.zip')),
    ]);

    $rates = app(BinanceArchive::class)->fundingRates('BTCUSDT', CarbonImmutable::parse('2026-09-01', 'UTC'));

    expect($rates)->toHaveCount(90)
        ->and($rates[0]->fundingTime->toIso8601String())->toBe('2026-09-01T00:00:00+00:00')
        ->and((string) $rates[0]->rate)->toBe('0.00008482')
        ->and($rates[0]->intervalHours)->toBe(8);
});

it('refuses an archive whose checksum does not match, and keeps nothing of it', function (): void {
    useArchiveCache();
    Http::fake([
        '*.CHECKSUM' => Http::response(str_repeat('0', 64).'  BTCUSDT-1m-2026-11-06.zip'),
        '*.zip' => Http::response(archiveBytes('spot-BTCUSDT-1m-2026-11-06.zip')),
    ]);

    expect(fn () => app(BinanceArchive::class)->klines(MarketType::Spot, 'BTCUSDT', ArchivePeriod::daily(CarbonImmutable::parse('2026-11-06', 'UTC'))))
        ->toThrow(ChecksumMismatch::class);
    $cache = storage_path('framework/testing/binance-archive');
    expect(is_dir($cache) ? File::allFiles($cache) : [])->toBe([]);
});

it('reports an archive that is not published yet', function (): void {
    useArchiveCache();
    Http::fake(['*' => Http::response('Not found', 404)]);

    app(BinanceArchive::class)->klines(MarketType::Spot, 'BTCUSDT', ArchivePeriod::monthly(CarbonImmutable::parse('2026-11-01', 'UTC')));
})->throws(ArchiveMissing::class);

it('reuses a verified archive instead of downloading it again', function (): void {
    useArchiveCache();
    Http::fake([
        '*.CHECKSUM' => Http::response(archiveBytes('spot-BTCUSDT-1m-2026-11-06.zip.CHECKSUM')),
        '*.zip' => Http::response(archiveBytes('spot-BTCUSDT-1m-2026-11-06.zip')),
    ]);
    $archive = app(BinanceArchive::class);
    $period = ArchivePeriod::daily(CarbonImmutable::parse('2026-11-06', 'UTC'));

    $archive->klines(MarketType::Spot, 'BTCUSDT', $period);
    $archive->klines(MarketType::Spot, 'BTCUSDT', $period);

    Http::assertSentCount(3);
});
