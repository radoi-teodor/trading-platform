<?php

declare(strict_types=1);

use App\Market\Data\Candle;
use App\Market\Data\MinuteCandles;
use App\Risk\Breakers\BreakerEvent;
use App\Risk\Breakers\MarketBreakers;
use App\Support\Timeframe;
use Carbon\CarbonImmutable;

mutates(MarketBreakers::class);

/** @return list<string> the events of one minute, as kind:tripped|cleared */
function breakerMinute(MarketBreakers $breakers, int $minute, string $low, string $high, string $close): array
{
    $candle = Candle::fromStrings(CarbonImmutable::parse('2026-10-01', 'UTC')->addMinutes($minute), Timeframe::OneMinute, $close, $high, $low, $close, '1', '1', 1, '0');

    return array_map(fn (BreakerEvent $e): string => $e->kind.':'.($e->tripped ? 'tripped' : 'cleared'), $breakers->observe(new MinuteCandles($candle->endsAt(), ['BTC/USDT' => $candle])));
}

it('rejects entries while a close is more than 5 % from the median of the 15 minutes before it', function (): void {
    $breakers = new MarketBreakers;

    foreach (range(0, 14) as $m) {
        expect(breakerMinute($breakers, $m, '99.5', '100.5', '100'))->toBe([]);
    }

    expect(breakerMinute($breakers, 15, '100', '105.2', '105.1'))->toBe(['price_sanity:tripped'])
        ->and($breakers->block('BTC/USDT', CarbonImmutable::parse('2026-10-01 00:16', 'UTC')))->toBe('price_sanity')
        // 105 is exactly 5 % away: not more than 5 %
        ->and(breakerMinute($breakers, 16, '104.9', '105', '105'))->toBe(['price_sanity:cleared'])
        ->and($breakers->block('BTC/USDT', CarbonImmutable::parse('2026-10-01 00:17', 'UTC')))->toBeNull();
});

it('pauses entries for 15 minutes after a minute that moves 5 times the hour\'s mean range', function (): void {
    $breakers = new MarketBreakers;

    foreach (range(0, 59) as $m) {
        breakerMinute($breakers, $m, '99.5', '100.5', '100');
    }

    // a range of 5 is exactly 5 times the mean of 1: not above it
    expect(breakerMinute($breakers, 60, '97.5', '102.5', '100'))->toBe([])
        ->and(breakerMinute($breakers, 61, '97', '103.1', '100'))->toBe(['volatility_shock:tripped'])
        ->and($breakers->block('BTC/USDT', CarbonImmutable::parse('2026-10-01 01:16', 'UTC')))->toBe('volatility_shock')
        ->and(breakerMinute($breakers, 76, '99.5', '100.5', '100'))->toBe(['volatility_shock:cleared'])
        ->and($breakers->block('BTC/USDT', CarbonImmutable::parse('2026-10-01 01:17', 'UTC')))->toBeNull();
});
