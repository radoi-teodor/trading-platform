<?php

declare(strict_types=1);

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
    app()->instance(Clock::class, new SimClock(CarbonImmutable::parse('2026-11-09 12:00:00', 'UTC')));
});

/** A 1d kline line as USD-M archives write it (milliseconds, after a header). */
function dayLine(string $day, string $volume): string
{
    $open = CarbonImmutable::parse($day, 'UTC')->getTimestamp() * 1000;

    return implode(',', [$open, '10', '11', '9', '10.5', $volume, $open + 86_399_999, (string) ((int) $volume * 10), 7, '0', '0', '0']);
}

/**
 * The S3 listing of a symbol's 1d monthly archives.
 *
 * @param  list<string>  $months
 */
function monthsListing(string $symbol, array $months): string
{
    $contents = implode('', array_map(fn (string $m): string => "<Contents><Key>data/futures/um/monthly/klines/{$symbol}/1d/{$symbol}-1d-{$m}.zip</Key></Contents><Contents><Key>data/futures/um/monthly/klines/{$symbol}/1d/{$symbol}-1d-{$m}.zip.CHECKSUM</Key></Contents>", $months));

    return '<?xml version="1.0" encoding="UTF-8"?><ListBucketResult xmlns="http://s3.amazonaws.com/doc/2006-03-01/"><IsTruncated>false</IsTruncated>'.$contents.'</ListBucketResult>';
}

it('keeps a day per instrument from REST for a trading perpetual and from archives for a delisted one, with its lifetime (D-90)', function (): void {
    $btc = Instrument::register('binance', MarketType::Perp, Symbol::parse('BTC/USDT:USDT'), CarbonImmutable::parse('2026-11-01', 'UTC'));
    $btc->update(['underlying_type' => 'COIN', 'listed_at' => CarbonImmutable::parse('2019-09-25 08:00:00', 'UTC')]);
    $srm = Instrument::register('binance', MarketType::Perp, Symbol::parse('SRM/USDT:USDT'), CarbonImmutable::parse('2026-11-01', 'UTC'), InstrumentStatus::Delisted);
    // Left out of the research universe: a stock contract and a USDC pair.
    Instrument::register('binance', MarketType::Perp, Symbol::parse('TSLA/USDT:USDT'), CarbonImmutable::parse('2026-11-01', 'UTC'))->update(['underlying_type' => 'EQUITY']);
    Instrument::register('binance', MarketType::Perp, Symbol::parse('ETH/USDC:USDC'), CarbonImmutable::parse('2026-11-01', 'UTC'))->update(['underlying_type' => 'COIN']);

    $september = ArchiveFactory::make('SRMUSDT-1d-2026-09.zip', ['open_time,open,high,low,close,volume,close_time,quote_volume,count,taker_buy_volume,taker_buy_quote_volume,ignore', dayLine('2026-09-29', '0'), dayLine('2026-09-30', '5')]);
    $october = ArchiveFactory::make('SRMUSDT-1d-2026-10.zip', ['open_time,open,high,low,close,volume,close_time,quote_volume,count,taker_buy_volume,taker_buy_quote_volume,ignore', dayLine('2026-10-01', '4'), dayLine('2026-10-02', '3'), dayLine('2026-10-03', '0'), dayLine('2026-10-04', '0')]);
    $rest = [
        [1793404800000, '70000', '71000', '69000', '70500', '100', 1793491199999, '7050000', 1000, '50', '3525000', '0'], // 2026-10-31
        [1793491200000, '70500', '72000', '70000', '71000', '120', 1793577599999, '8520000', 1200, '60', '4260000', '0'], // 2026-11-01
    ];

    Http::fake(function (Request $request) use ($september, $october, $rest) {
        $url = $request->url();

        return match (true) {
            str_contains($url, 'fapi/v1/klines') => Http::response($rest),
            str_contains($url, 'prefix=data%2Ffutures%2Fum%2Fmonthly%2Fklines%2FSRMUSDT%2F1d%2F') => Http::response(monthsListing('SRMUSDT', ['2026-09', '2026-10'])),
            str_ends_with($url, 'SRMUSDT-1d-2026-09.zip.CHECKSUM') => Http::response($september['checksum']),
            str_ends_with($url, 'SRMUSDT-1d-2026-09.zip') => Http::response($september['zip']),
            str_ends_with($url, 'SRMUSDT-1d-2026-10.zip.CHECKSUM') => Http::response($october['checksum']),
            str_ends_with($url, 'SRMUSDT-1d-2026-10.zip') => Http::response($october['zip']),
            default => Http::response('unexpected '.$url, 500),
        };
    });

    expect(Artisan::call('market:daily', ['--from' => '2026-09-01']))->toBe(0);

    $days = fn (string $symbol): array => DB::table('daily_stats')->join('instruments', 'instruments.id', '=', 'daily_stats.instrument_id')
        ->where('instruments.symbol', $symbol)->orderBy('day')->pluck('day')->map(fn (mixed $d): string => substr(is_string($d) ? $d : '', 0, 10))->all();
    $srm->refresh();

    expect($days('BTC/USDT:USDT'))->toBe(['2026-10-31', '2026-11-01'])
        // SRM traded from 2026-09-30 to 2026-10-02: the zero-volume days before and after are not its life.
        ->and($days('SRM/USDT:USDT'))->toBe(['2026-09-30', '2026-10-01', '2026-10-02'])
        ->and($srm->listed_at?->toIso8601String())->toBe('2026-09-30T00:00:00+00:00')
        ->and($srm->delisted_at?->toIso8601String())->toBe('2026-10-03T00:00:00+00:00')
        ->and($days('TSLA/USDT:USDT'))->toBe([])
        ->and($days('ETH/USDC:USDC'))->toBe([])
        ->and(DB::table('daily_stats')->where('instrument_id', $btc->id)->orderBy('day')->value('quote_volume'))->toStartWith('7050000');
});
