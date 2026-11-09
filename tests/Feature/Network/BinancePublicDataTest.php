<?php

declare(strict_types=1);

use App\Market\Binance\BinanceRestClient;
use App\Market\Data\InstrumentDefinition;
use App\Market\Data\MarketType;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Http;

// Opt-in: reads Binance's real public endpoints (composer test:network). No keys, nothing is written.

beforeEach(fn () => Http::allowStrayRequests());

$skip = fn (): bool => getenv('TRADING_NETWORK') !== '1';

it('reads real spot instruments and closed klines', function (): void {
    $client = app(BinanceRestClient::class);

    $btc = collect($client->exchangeInfo(MarketType::Spot))->first(fn (InstrumentDefinition $d): bool => (string) $d->symbol === 'BTC/USDT');
    $candles = iterator_to_array($client->klines(MarketType::Spot, 'BTCUSDT', CarbonImmutable::parse('2026-10-01 00:00:00', 'UTC'), CarbonImmutable::parse('2026-10-01 00:09:00', 'UTC')), false);

    expect($btc)->not->toBeNull()
        ->and($btc?->tickSize->isPositive())->toBeTrue()
        ->and($candles)->toHaveCount(10);
})->group('network')->skip($skip, 'Set TRADING_NETWORK=1 to read the real endpoints.');

it('reads real perpetual instruments, klines and funding', function (): void {
    $client = app(BinanceRestClient::class);

    $btc = collect($client->exchangeInfo(MarketType::Perp))->first(fn (InstrumentDefinition $d): bool => (string) $d->symbol === 'BTC/USDT:USDT');
    $candles = iterator_to_array($client->klines(MarketType::Perp, 'BTCUSDT', CarbonImmutable::parse('2026-10-01 00:00:00', 'UTC'), CarbonImmutable::parse('2026-10-01 00:09:00', 'UTC')), false);
    $funding = iterator_to_array($client->fundingRates('BTCUSDT', CarbonImmutable::parse('2026-10-01 00:00:00', 'UTC'), CarbonImmutable::parse('2026-10-02 00:00:00', 'UTC')), false);

    expect($btc)->not->toBeNull()
        ->and($btc?->minNotional?->isPositive())->toBeTrue()
        ->and($candles)->toHaveCount(10)
        ->and($funding)->not->toBeEmpty();
})->group('network')->skip($skip, 'Set TRADING_NETWORK=1 to read the real endpoints.');
