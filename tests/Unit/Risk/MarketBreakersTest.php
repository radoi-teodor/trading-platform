<?php

declare(strict_types=1);

use App\Market\Data\Candle;
use App\Market\Data\MinuteCandles;
use App\Risk\Breakers\BreakerEvent;
use App\Risk\Breakers\MarketBreakers;
use App\Support\Timeframe;
use Carbon\CarbonImmutable;
use Random\Engine\Xoshiro256StarStar;
use Random\Randomizer;
use Tests\Support\ReferenceMarketBreakers;

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

it('says what sorting and summing every minute says, over a market with ties, jumps and wide minutes', function (int $seed): void {
    $random = new Randomizer(new Xoshiro256StarStar($seed));
    $breakers = new MarketBreakers;
    $reference = new ReferenceMarketBreakers;
    $cents = ['BTC/USDT' => 10000, 'ETH/USDT:USDT' => 250, 'SOL/USDT' => 31];
    $line = fn (BreakerEvent $e): string => implode('|', [$e->kind, $e->symbol, $e->tripped ? 'tripped' : 'cleared', $e->time->toIso8601String(), $e->detail]);
    $price = fn (int $c): string => intdiv($c, 100).'.'.str_pad((string) ($c % 100), 2, '0', STR_PAD_LEFT);
    $seen = [];

    foreach (range(0, 599) as $m) {
        $time = CarbonImmutable::parse('2026-10-01', 'UTC')->addMinutes($m);
        $candles = [];

        foreach ($cents as $symbol => $open) {
            // A third of the minutes do not move, one in forty jumps 8 %, one in thirty has a wide range.
            $close = match (true) {
                $random->getInt(1, 40) === 1 => max(2, intdiv($open * ($random->getInt(0, 1) === 1 ? 108 : 92), 100)),
                $random->getInt(1, 3) === 1 => $open,
                default => max(2, $open + $random->getInt(-3, 3)),
            };
            $wide = $random->getInt(1, 30) === 1 ? intdiv($open, 4) : $random->getInt(0, 2);
            $candles[$symbol] = Candle::fromStrings($time, Timeframe::OneMinute, $price($open), $price(max($open, $close) + $wide), $price(max(1, min($open, $close) - $wide)), $price($close), '1', '1', 1, '0');
            $cents[$symbol] = $close;
        }

        // A symbol that misses minutes keeps its own windows.
        if ($m % 7 === 3) {
            unset($candles['SOL/USDT']);
        }

        $event = new MinuteCandles($time->addMinute(), $candles);
        $got = array_map($line, $breakers->observe($event));

        expect($got)->toBe(array_map($line, $reference->observe($event)));

        foreach (array_keys($cents) as $symbol) {
            expect($breakers->block($symbol, $event->closedAt))->toBe($reference->block($symbol, $event->closedAt));
        }

        foreach ($got as $text) {
            $seen[implode('|', [explode('|', $text)[0], explode('|', $text)[2]])] = true;
        }
    }

    expect(array_keys($seen))->toEqualCanonicalizing(['price_sanity|tripped', 'price_sanity|cleared', 'volatility_shock|tripped', 'volatility_shock|cleared']);
})->with([1, 2, 3]);
