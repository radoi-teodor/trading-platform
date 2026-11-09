<?php

declare(strict_types=1);

use App\Market\Binance\BinanceRequestFailed;
use App\Market\Binance\BinanceRestClient;
use App\Market\Binance\LocationRestricted;
use App\Market\Binance\RateLimited;
use App\Market\Binance\Sleeper;
use App\Market\Data\Candle;
use App\Market\Data\InstrumentStatus;
use App\Market\Data\MarketType;
use App\Support\Clock\Clock;
use App\Support\Clock\SimClock;
use Carbon\CarbonImmutable;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Tests\Support\RecordingSleeper;

/** @return array{BinanceRestClient, RecordingSleeper} */
function binanceClient(string $now = '2026-11-08 12:00:00'): array
{
    $sleeper = new RecordingSleeper;
    app()->instance(Clock::class, new SimClock(CarbonImmutable::parse($now, 'UTC')));
    app()->instance(Sleeper::class, $sleeper);

    return [app(BinanceRestClient::class), $sleeper];
}

it('reads spot instruments and their filters from exchangeInfo', function (): void {
    Http::fake(['data-api.binance.vision/api/v3/exchangeInfo*' => Http::response(binanceFixture('spot-exchange-info.json'))]);
    [$client] = binanceClient();

    $definitions = $client->exchangeInfo(MarketType::Spot);
    $btc = $definitions[0];

    expect($definitions)->toHaveCount(3)
        ->and((string) $btc->symbol)->toBe('BTC/USDT')
        ->and($btc->market)->toBe(MarketType::Spot)
        ->and($btc->status)->toBe(InstrumentStatus::Trading)
        ->and((string) $btc->tickSize)->toBe('0.01')
        ->and((string) $btc->stepSize)->toBe('0.00001')
        ->and((string) $btc->minQty)->toBe('0.00001')
        ->and((string) $btc->maxQty)->toBe('9000')
        ->and((string) $btc->minNotional)->toBe('5')
        ->and($btc->rawFilters)->toHaveKey('PERCENT_PRICE_BY_SIDE');

    Http::assertSent(fn (Request $request): bool => str_starts_with($request->url(), 'https://data-api.binance.vision/api/v3/exchangeInfo'));
});

it('pages through klines and keeps only closed candles up to the end of the range', function (): void {
    $rows = json_decode(binanceFixture('spot-klines-btcusdt.json'), true);
    expect($rows)->toBeArray()->toHaveCount(5);
    $rows = is_array($rows) ? $rows : [];

    Http::fake([
        'data-api.binance.vision/api/v3/klines*' => Http::sequence()
            ->push(json_encode(array_slice($rows, 0, 3), JSON_THROW_ON_ERROR))
            ->push(json_encode(array_slice($rows, 3, 2), JSON_THROW_ON_ERROR)),
    ]);
    [$client] = binanceClient();

    $candles = iterator_to_array($client->klines(
        MarketType::Spot,
        'BTCUSDT',
        CarbonImmutable::parse('2026-10-01 00:00:00', 'UTC'),
        CarbonImmutable::parse('2026-10-01 00:03:00', 'UTC'),
        pageSize: 3,
    ), false);

    expect(array_map(fn (Candle $c): string => $c->openTime->format('H:i'), $candles))->toBe(['00:00', '00:01', '00:02', '00:03'])
        ->and((string) $candles[0]->close)->toBe('83578.01')
        ->and($candles[0]->tradeCount)->toBe(4552)
        ->and((string) $candles[0]->takerBuyVolume)->toBe('14.59132');

    Http::assertSentCount(2);
});

it('drops the candle that is still open', function (): void {
    $rows = json_decode(binanceFixture('spot-klines-btcusdt.json'), true);
    Http::fake(['data-api.binance.vision/api/v3/klines*' => Http::response(json_encode($rows, JSON_THROW_ON_ERROR))]);
    // 00:04:30: the candle that opened at 00:04 has not closed yet.
    [$client] = binanceClient('2026-10-01 00:04:30');

    $candles = iterator_to_array($client->klines(
        MarketType::Spot,
        'BTCUSDT',
        CarbonImmutable::parse('2026-10-01 00:00:00', 'UTC'),
        CarbonImmutable::parse('2026-10-01 00:10:00', 'UTC'),
    ), false);

    expect($candles)->toHaveCount(4);
});

it('slows down when the request weight gets close to the limit', function (): void {
    Http::fake(['data-api.binance.vision/api/v3/exchangeInfo*' => Http::response(binanceFixture('spot-exchange-info.json'), 200, ['X-MBX-USED-WEIGHT-1M' => '5000'])]);
    [$client, $sleeper] = binanceClient('2026-11-08 12:00:45');

    $client->exchangeInfo(MarketType::Spot);

    expect($sleeper->slept)->toBe([16]);
});

it('stops loudly on a refused location, a rate limit or any other error', function (int $status, string $exception): void {
    Http::fake(['*' => Http::response(['code' => 0, 'msg' => 'refused'], $status)]);
    [$client] = binanceClient();

    expect(fn () => $client->exchangeInfo(MarketType::Spot))->toThrow($exception);
})->with([
    'restricted location' => [451, LocationRestricted::class],
    'rate limited' => [429, RateLimited::class],
    'ip banned' => [418, RateLimited::class],
    'server error' => [500, BinanceRequestFailed::class],
]);

it('reads only perpetual contracts from the futures exchangeInfo', function (): void {
    Http::fake(['fapi.binance.com/fapi/v1/exchangeInfo*' => Http::response(binanceFixture('perp-exchange-info.hand-written.json'))]);
    [$client] = binanceClient();

    $definitions = $client->exchangeInfo(MarketType::Perp);
    $symbols = array_map(fn ($d): string => (string) $d->symbol, $definitions);

    expect($symbols)->toBe(['BTC/USDT:USDT', 'ETH/USDT:USDT', 'OLD/USDT:USDT'])
        ->and((string) $definitions[0]->tickSize)->toBe('0.1')
        ->and((string) $definitions[0]->stepSize)->toBe('0.001')
        ->and((string) $definitions[0]->minNotional)->toBe('100')
        ->and($definitions[2]->status)->toBe(InstrumentStatus::Delisted);
});

it('reads perpetual klines in milliseconds', function (): void {
    Http::fake(['fapi.binance.com/fapi/v1/klines*' => Http::response(binanceFixture('perp-klines-btcusdt.from-archive.json'))]);
    [$client] = binanceClient();

    $candles = iterator_to_array($client->klines(
        MarketType::Perp,
        'BTCUSDT',
        CarbonImmutable::parse('2026-11-06 00:00:00', 'UTC'),
        CarbonImmutable::parse('2026-11-06 00:04:00', 'UTC'),
    ), false);

    expect($candles)->toHaveCount(5)
        ->and($candles[0]->openTime->toIso8601String())->toBe('2026-11-06T00:00:00+00:00')
        ->and((string) $candles[0]->close)->toBe('76042.9')
        ->and((string) $candles[0]->volume)->toBe('146.592');

    Http::assertSent(fn (Request $request): bool => str_contains($request->url(), 'limit=1500'));
});

it('reads funding rates with their interval', function (): void {
    Http::fake(['fapi.binance.com/fapi/v1/fundingRate*' => Http::response(binanceFixture('perp-funding-btcusdt.from-archive.json'))]);
    [$client] = binanceClient();

    $rates = iterator_to_array($client->fundingRates(
        'BTCUSDT',
        CarbonImmutable::parse('2026-09-01 00:00:00', 'UTC'),
        CarbonImmutable::parse('2026-09-01 16:00:00', 'UTC'),
    ), false);

    expect(array_map(fn ($r): string => (string) $r->rate, $rates))->toBe(['0.00008482', '0.0001', '0.00003844'])
        ->and($rates[0]->fundingTime->toIso8601String())->toBe('2026-09-01T00:00:00+00:00')
        ->and($rates[1]->intervalHours)->toBe(8)
        ->and($rates[0]->markPrice)->toBeNull();
});
