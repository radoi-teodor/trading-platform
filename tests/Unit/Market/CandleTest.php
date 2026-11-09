<?php

declare(strict_types=1);

use App\Market\Data\Candle;
use App\Market\Data\InvalidCandle;
use App\Support\Timeframe;
use Carbon\CarbonImmutable;

mutates(Candle::class);

/**
 * @param  array{open?: string, high?: string, low?: string, close?: string, volume?: string, quoteVolume?: string, tradeCount?: int, takerBuyVolume?: string, openTime?: string}  $overrides
 */
function candleWith(array $overrides = []): Candle
{
    return Candle::fromStrings(
        CarbonImmutable::parse($overrides['openTime'] ?? '2026-10-01 00:00:00', 'UTC'),
        Timeframe::OneMinute,
        $overrides['open'] ?? '100',
        $overrides['high'] ?? '110',
        $overrides['low'] ?? '95',
        $overrides['close'] ?? '105',
        $overrides['volume'] ?? '2.5',
        $overrides['quoteVolume'] ?? '250',
        $overrides['tradeCount'] ?? 10,
        $overrides['takerBuyVolume'] ?? '1',
    );
}

it('accepts a consistent candle', function (): void {
    $candle = candleWith();

    expect((string) $candle->close)->toBe('105')
        ->and($candle->tradeCount)->toBe(10)
        ->and($candle->closesAt()->format('H:i:s.v'))->toBe('00:00:59.999');
});

it('accepts a candle without trades, where every price is the previous close', function (): void {
    $candle = candleWith(['open' => '100', 'high' => '100', 'low' => '100', 'close' => '100', 'volume' => '0', 'quoteVolume' => '0', 'tradeCount' => 0, 'takerBuyVolume' => '0']);

    expect($candle->volume->isZero())->toBeTrue();
});

it('refuses an inconsistent candle', function (Closure $build): void {
    $build();
})->with([
    'low above open' => [fn (): Candle => candleWith(['low' => '101'])],
    'low above close' => [fn (): Candle => candleWith(['low' => '106', 'open' => '107'])],
    'high below close' => [fn (): Candle => candleWith(['high' => '104'])],
    'high below open' => [fn (): Candle => candleWith(['high' => '104', 'close' => '103', 'open' => '104.5'])],
    'negative volume' => [fn (): Candle => candleWith(['volume' => '-1'])],
    'negative quote volume' => [fn (): Candle => candleWith(['quoteVolume' => '-1'])],
    'negative trade count' => [fn (): Candle => candleWith(['tradeCount' => -1])],
    'negative taker volume' => [fn (): Candle => candleWith(['takerBuyVolume' => '-0.1'])],
    'taker volume above volume' => [fn (): Candle => candleWith(['takerBuyVolume' => '3'])],
    'open time not aligned' => [fn (): Candle => candleWith(['openTime' => '2026-10-01 00:00:30'])],
])->throws(InvalidCandle::class);

it('compares every value of two candles', function (Candle $different): void {
    expect(candleWith()->sameValuesAs(candleWith()))->toBeTrue()
        ->and(candleWith()->sameValuesAs($different))->toBeFalse();
})->with([
    'open' => [fn (): Candle => candleWith(['open' => '101'])],
    'high' => [fn (): Candle => candleWith(['high' => '111'])],
    'low' => [fn (): Candle => candleWith(['low' => '96'])],
    'close' => [fn (): Candle => candleWith(['close' => '104'])],
    'volume' => [fn (): Candle => candleWith(['volume' => '2.6'])],
    'quote volume' => [fn (): Candle => candleWith(['quoteVolume' => '251'])],
    'trade count' => [fn (): Candle => candleWith(['tradeCount' => 11])],
    'taker buy volume' => [fn (): Candle => candleWith(['takerBuyVolume' => '1.1'])],
]);
