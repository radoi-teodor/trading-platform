<?php

declare(strict_types=1);

use App\Market\CandleRepository;
use App\Market\Data\CandleSource;
use App\Market\Data\MarketType;
use App\Market\Models\Instrument;
use App\Market\Quality\ExchangeVerification;
use App\Market\Quality\VerificationSource;
use App\Support\Clock\Clock;
use App\Support\Clock\SimClock;
use App\Support\Symbol;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Tests\Support\ArchiveFactory;
use Tests\Support\CandleFactory;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    app()->instance(Clock::class, new SimClock(CarbonImmutable::parse('2026-11-08 12:00:00', 'UTC')));
    File::deleteDirectory(storage_path('framework/testing/binance-archive'));
    config()->set('exchanges.binance.archive_cache', storage_path('framework/testing/binance-archive'));
});

function verifiedInstrument(): Instrument
{
    $btc = Instrument::register('binance', MarketType::Spot, Symbol::parse('BTC/USDT'), CarbonImmutable::parse('2026-11-01', 'UTC'));
    app(CandleRepository::class)->upsert($btc, CandleSource::Archive, array_map(fn (int $m) => CandleFactory::at($m), range(0, 9)));

    return $btc;
}

it('finds no mismatch when the exchange agrees', function (): void {
    $btc = verifiedInstrument();
    Http::fake(['data-api.binance.vision/api/v3/klines*' => Http::response(array_map(fn (int $m) => CandleFactory::restRow($m), range(0, 9)))]);

    $report = app(ExchangeVerification::class)->verify($btc, CandleFactory::time(0), CandleFactory::time(9), sample: 10, against: VerificationSource::Rest);

    expect($report->sampled)->toBe(10)
        ->and($report->mismatches)->toBe([]);
});

it('lists every candle the exchange reports differently or not at all', function (): void {
    $btc = verifiedInstrument();
    $rows = array_map(fn (int $m) => CandleFactory::restRow($m, $m === 4 ? '100.5' : '100'), range(0, 8));
    Http::fake(['data-api.binance.vision/api/v3/klines*' => Http::response($rows)]);

    $report = app(ExchangeVerification::class)->verify($btc, CandleFactory::time(0), CandleFactory::time(9), sample: 10, against: VerificationSource::Rest);

    expect($report->mismatches)->toHaveCount(2)
        ->and($report->mismatches[0])->toContain('2026-10-01T00:04:00')->toContain('close')
        ->and($report->mismatches[1])->toContain('2026-10-01T00:09:00')->toContain('not at the exchange');
});

it('draws the same sample for the same seed', function (): void {
    $btc = verifiedInstrument();
    Http::fake(['data-api.binance.vision/api/v3/klines*' => Http::response(array_map(fn (int $m) => CandleFactory::restRow($m), range(0, 9)))]);
    $verification = app(ExchangeVerification::class);

    $first = $verification->verify($btc, CandleFactory::time(0), CandleFactory::time(9), sample: 3, against: VerificationSource::Rest, seed: 7);
    $second = $verification->verify($btc, CandleFactory::time(0), CandleFactory::time(9), sample: 3, against: VerificationSource::Rest, seed: 7);

    expect($first->sampledTimes)->toHaveCount(3)
        ->and($first->sampledTimes)->toBe($second->sampledTimes);
});

it('can compare with the daily archives instead of REST', function (): void {
    $btc = verifiedInstrument();
    $lines = array_map(fn (int $m): string => ArchiveFactory::spotLine(CandleFactory::time($m)->getTimestamp() * 1000), range(0, 9));
    $archive = ArchiveFactory::make('BTCUSDT-1m-2026-10-01.zip', $lines);
    Http::fake([
        '*/daily/klines/BTCUSDT/1m/BTCUSDT-1m-2026-10-01.zip.CHECKSUM' => Http::response($archive['checksum']),
        '*/daily/klines/BTCUSDT/1m/BTCUSDT-1m-2026-10-01.zip' => Http::response($archive['zip']),
    ]);

    $report = app(ExchangeVerification::class)->verify($btc, CandleFactory::time(0), CandleFactory::time(9), sample: 10, against: VerificationSource::DailyArchive);

    // ArchiveFactory lines differ from CandleFactory candles in every price field: all ten must be reported.
    expect($report->sampled)->toBe(10)
        ->and($report->mismatches)->toHaveCount(10);
});

it('reports an empty range without asking the exchange', function (): void {
    $btc = Instrument::register('binance', MarketType::Spot, Symbol::parse('BTC/USDT'), CarbonImmutable::parse('2026-11-01', 'UTC'));

    $report = app(ExchangeVerification::class)->verify($btc, CandleFactory::time(0), CandleFactory::time(9), sample: 10, against: VerificationSource::Rest);

    expect($report->sampled)->toBe(0);
});

it('verifies from the command line and fails on mismatches', function (): void {
    verifiedInstrument();
    Http::fake(['data-api.binance.vision/api/v3/klines*' => Http::response(array_map(fn (int $m) => CandleFactory::restRow($m), range(0, 9)))]);

    expect(Artisan::call('market:verify', ['symbol' => 'BTC/USDT', '--from' => '2026-10-01', '--to' => '2026-10-01 00:09', '--sample' => 5]))->toBe(0)
        ->and(Artisan::output())->toContain('5 sampled, 0 mismatches');
});
