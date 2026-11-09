<?php

declare(strict_types=1);

use App\Portfolio\Data\Marks;
use App\Support\Asset;
use App\Support\Number\Price;
use App\Support\Symbol;

mutates(Marks::class);

it('takes the prices of a whole minute at once, as one by one would', function (): void {
    $usdt = Asset::of('USDT');
    $before = (new Marks($usdt))->with(Symbol::parse('BTC/USDT'), Price::of('100'))->with(Symbol::parse('ETH/USDT:USDT'), Price::of('10'));

    $after = $before->withAll(['ETH/USDT:USDT' => Price::of('11'), 'SOL/USDT' => Price::of('5')]);

    expect((string) $after->priceOf(Asset::of('BTC')))->toBe('100')
        ->and((string) $after->priceOfSymbol(Symbol::parse('ETH/USDT:USDT')))->toBe('11')
        ->and((string) $after->priceOfCode('SOL'))->toBe('5')
        ->and($after->quote)->toBe($usdt)
        // The marks it was made from are not changed.
        ->and((string) $before->priceOfSymbol(Symbol::parse('ETH/USDT:USDT')))->toBe('10')
        ->and($before->priceOfCode('SOL'))->toBeNull();
});
