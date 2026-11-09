<?php

declare(strict_types=1);

use App\Market\Data\FundingRate;
use App\Market\Data\MinuteCandles;
use App\Market\Data\PriceMinute;
use App\Support\Number\Decimal;
use Tests\Support\CandleFactory;

mutates(MinuteCandles::class);

it('keeps of a minute only what is of the named instruments, in every part of it', function (): void {
    $candle = CandleFactory::at(5);
    $rate = new FundingRate($candle->endsAt(), Decimal::of('0.0001'), 8, null);
    $all = ['BTC/USDT' => $candle, 'AAA/USDT:USDT' => $candle, 'BBB/USDT:USDT' => $candle];
    $minute = new MinuteCandles($candle->endsAt(), $all, ['AAA/USDT:USDT' => $rate, 'BBB/USDT:USDT' => $rate]);

    $kept = $minute->only(['BBB/USDT:USDT' => true, 'ZZZ/USDT' => true]);

    expect(array_keys($kept->candles))->toBe(['BBB/USDT:USDT'])
        ->and(array_keys($kept->funding))->toBe(['BBB/USDT:USDT'])
        ->and($kept->closedAt)->toBe($minute->closedAt)
        ->and($kept->flow)->toBe([])
        // The minute itself is not changed, and one that loses nothing is given back as it is.
        ->and(array_keys($minute->candles))->toBe(array_keys($all))
        ->and($minute->only(['BTC/USDT' => true, 'AAA/USDT:USDT' => true, 'BBB/USDT:USDT' => true]))->toBe($minute)
        ->and(array_keys($minute->only([])->candles))->toBe([]);
});

it('narrows a minute whose candles are all followed when another part of it is not', function (): void {
    $candle = CandleFactory::at(5);
    $one = Decimal::of('1');
    $mark = new PriceMinute($candle->openTime, $one, $one, $one, $one);
    $minute = new MinuteCandles($candle->endsAt(), ['BTC/USDT' => $candle], marks: ['AAA/USDT:USDT' => $mark], premium: ['BTC/USDT' => $mark, 'AAA/USDT:USDT' => $mark]);

    $kept = $minute->only(['BTC/USDT' => true]);

    expect($kept)->not->toBe($minute)
        ->and(array_keys($kept->candles))->toBe(['BTC/USDT'])
        ->and($kept->marks)->toBe([])
        ->and(array_keys($kept->premium))->toBe(['BTC/USDT']);
});
