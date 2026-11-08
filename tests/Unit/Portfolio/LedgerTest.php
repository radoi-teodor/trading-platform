<?php

declare(strict_types=1);

use App\Portfolio\Data\ExitReason;
use App\Portfolio\Data\Fill;
use App\Portfolio\Data\Marks;
use App\Portfolio\Data\Position;
use App\Portfolio\Data\RoundTrip;
use App\Portfolio\Ledger;
use App\Portfolio\LedgerViolation;
use App\Support\Asset;
use App\Support\Number\Decimal;
use App\Support\Number\Money;
use App\Support\Number\Price;
use App\Support\Side;
use App\Support\Symbol;
use Carbon\CarbonImmutable;
use Random\Engine\Xoshiro256StarStar;
use Random\Randomizer;

mutates(Ledger::class, Position::class, RoundTrip::class, Marks::class);

function usdt(string $amount): Money
{
    return Money::of($amount, Asset::of('USDT'));
}

function ledgerWith(string ...$balances): Ledger
{
    return new Ledger(Asset::of('USDT'), array_values(array_map(function (string $balance): Money {
        [$amount, $asset] = explode(' ', $balance);

        return Money::of($amount, Asset::of($asset));
    }, $balances)));
}

function fill(
    string $id,
    Side $side,
    string $quantity,
    string $price,
    string $fee,
    string $feeAsset,
    string $feeValue,
    string $strategy = 'breakout',
    string $symbol = 'BTC/USDT',
    int $minute = 0,
    ?string $stop = null,
    ?ExitReason $reason = null,
    string $slippage = '0',
): Fill {
    return new Fill(
        id: $id,
        time: CarbonImmutable::parse('2026-10-01 00:00:00', 'UTC')->addMinutes($minute),
        strategy: $strategy,
        symbol: Symbol::parse($symbol),
        side: $side,
        quantity: Decimal::of($quantity),
        price: Price::of($price),
        fee: Money::of($fee, Asset::of($feeAsset)),
        feeValue: Decimal::of($feeValue),
        slippage: Decimal::of($slippage),
        initialStop: $stop === null ? null : Price::of($stop),
        exitReason: $reason,
    );
}

function balanceOf(Ledger $ledger, string $asset): string
{
    return (string) $ledger->balance(Asset::of($asset));
}

it('charges a buy fee in base and a sell fee in quote, and closes the round trip', function (): void {
    $ledger = ledgerWith('10000 USDT');

    $ledger->apply(fill('1', Side::Buy, '0.1', '60000', '0.0001', 'BTC', '6', stop: '57000', slippage: '3'));

    expect(balanceOf($ledger, 'BTC'))->toBe('0.0999')
        ->and(balanceOf($ledger, 'USDT'))->toBe('4000')
        ->and((string) $ledger->position('breakout', Symbol::parse('BTC/USDT'))?->quantity)->toBe('0.0999');

    $ledger->apply(fill('2', Side::Sell, '0.0999', '66000', '6.5934', 'USDT', '6.5934', minute: 90, reason: ExitReason::Target, slippage: '2'));

    expect(balanceOf($ledger, 'BTC'))->toBe('0')
        ->and(balanceOf($ledger, 'USDT'))->toBe('10586.8066')
        ->and($ledger->position('breakout', Symbol::parse('BTC/USDT')))->toBeNull()
        ->and($ledger->roundTrips())->toHaveCount(1);

    $trip = $ledger->roundTrips()[0];

    expect($trip->strategy)->toBe('breakout')
        ->and((string) $trip->symbol)->toBe('BTC/USDT')
        ->and($trip->openedAt->format('H:i'))->toBe('00:00')
        ->and($trip->closedAt->format('H:i'))->toBe('01:30')
        ->and((string) $trip->quantity)->toBe('0.1')
        ->and((string) $trip->entryPrice)->toBe('60000')
        ->and((string) $trip->exitPrice)->toBe('66000')
        ->and((string) $trip->netProfit)->toBe('586.8066')
        ->and((string) $trip->fees)->toBe('12.5934')
        ->and((string) $trip->grossProfit)->toBe('599.4')
        ->and((string) $trip->slippage)->toBe('5')
        ->and((string) $trip->initialRisk)->toBe('300')
        ->and((string) $trip->rMultiple)->toBe('1.956022')
        ->and($trip->exitReason)->toBe(ExitReason::Target)
        ->and((string) $trip->dust)->toBe('0');
});

it('leaves dust when the lot step stops a full exit, and writes it off', function (): void {
    $ledger = ledgerWith('10000 USDT');
    $ledger->apply(fill('1', Side::Buy, '0.1', '60000', '0.0001', 'BTC', '6'));
    $ledger->apply(fill('2', Side::Sell, '0.099', '60000', '5.94', 'USDT', '5.94', minute: 5, reason: ExitReason::Signal));

    expect((string) $ledger->position('breakout', Symbol::parse('BTC/USDT'))?->quantity)->toBe('0.0009')
        ->and($ledger->roundTrips())->toBe([]);

    $ledger->writeOffDust('breakout', Symbol::parse('BTC/USDT'), CarbonImmutable::parse('2026-10-01 00:05:00', 'UTC'));

    $trip = $ledger->roundTrips()[0];

    expect($ledger->position('breakout', Symbol::parse('BTC/USDT')))->toBeNull()
        ->and(balanceOf($ledger, 'BTC'))->toBe('0.0009')
        ->and((string) $ledger->dust(Asset::of('BTC')))->toBe('0.0009')
        ->and((string) $trip->dust)->toBe('0.0009')
        ->and((string) $trip->netProfit)->toBe('-65.94')
        ->and($trip->exitReason)->toBe(ExitReason::Signal)
        ->and($trip->rMultiple)->toBeNull()
        ->and($trip->initialRisk)->toBeNull();
});

it('averages partial exits', function (): void {
    $ledger = ledgerWith('1000 USDT');
    $ledger->apply(fill('1', Side::Buy, '1', '100', '0.001', 'BTC', '0.1'));
    $ledger->apply(fill('2', Side::Sell, '0.5', '110', '0.055', 'USDT', '0.055', minute: 1, reason: ExitReason::Target));
    $ledger->apply(fill('3', Side::Sell, '0.499', '120', '0.05988', 'USDT', '0.05988', minute: 2, reason: ExitReason::Stop));

    $trip = $ledger->roundTrips()[0];

    expect((string) $trip->exitPrice)->toBe('114.994994994994994994')
        ->and((string) $trip->netProfit)->toBe('14.76512')
        ->and((string) $trip->fees)->toBe('0.21488')
        ->and($trip->exitReason)->toBe(ExitReason::Stop)
        ->and(balanceOf($ledger, 'USDT'))->toBe('1014.76512');
});

it('averages entries that add to a position', function (): void {
    $ledger = ledgerWith('1000 USDT');
    $ledger->apply(fill('1', Side::Buy, '1', '100', '0', 'BTC', '0', stop: '90'));
    $ledger->apply(fill('2', Side::Buy, '1', '110', '0', 'BTC', '0', minute: 1));

    $position = $ledger->position('breakout', Symbol::parse('BTC/USDT'));

    expect((string) $position?->entryPrice())->toBe('105')
        ->and((string) $position?->quantity)->toBe('2')
        ->and((string) $position?->initialStop)->toBe('90')
        ->and($position?->openedAt->format('H:i'))->toBe('00:00');
});

it('tracks the worst and best excursion while a position is open', function (): void {
    $ledger = ledgerWith('10000 USDT');
    $btc = Symbol::parse('BTC/USDT');
    $ledger->apply(fill('1', Side::Buy, '0.1', '60000', '0', 'BTC', '0'));
    $ledger->observe($btc, Price::of('61000'), Price::of('59000'));
    $ledger->observe($btc, Price::of('63000'), Price::of('58500'));
    $ledger->observe(Symbol::parse('ETH/USDT'), Price::of('9000'), Price::of('1'));
    $ledger->apply(fill('2', Side::Sell, '0.1', '62000', '0', 'USDT', '0', minute: 3, reason: ExitReason::Signal));
    $ledger->observe($btc, Price::of('99999'), Price::of('1'));

    $trip = $ledger->roundTrips()[0];

    expect((string) $trip->maximumAdverseExcursion)->toBe('150')
        ->and((string) $trip->maximumFavourableExcursion)->toBe('300');
});

it('counts an excursion that never went against the entry as zero', function (): void {
    $ledger = ledgerWith('10000 USDT');
    $ledger->apply(fill('1', Side::Buy, '0.1', '60000', '0', 'BTC', '0'));
    $ledger->observe(Symbol::parse('BTC/USDT'), Price::of('60000'), Price::of('60000'));
    $ledger->apply(fill('2', Side::Sell, '0.1', '60000', '0', 'USDT', '0', reason: ExitReason::Signal));

    expect((string) $ledger->roundTrips()[0]->maximumAdverseExcursion)->toBe('0')
        ->and((string) $ledger->roundTrips()[0]->maximumFavourableExcursion)->toBe('0');
});

it('values equity in quote at the given marks', function (): void {
    $ledger = ledgerWith('10000 USDT');
    $ledger->apply(fill('1', Side::Buy, '0.1', '60000', '0.0001', 'BTC', '6'));
    $marks = new Marks(Asset::of('USDT'), ['BTC/USDT' => Price::of('61000')]);

    expect((string) $ledger->equity($marks))->toBe('10093.9');
});

it('refuses to value an asset it has no price for', function (): void {
    $ledger = ledgerWith('10000 USDT', '1 ETH');

    $ledger->equity(new Marks(Asset::of('USDT'), ['BTC/USDT' => Price::of('61000')]));
})->throws(LedgerViolation::class, 'ETH');

it('keeps positions of strategies apart on the same instrument', function (): void {
    $ledger = ledgerWith('10000 USDT');
    $ledger->apply(fill('1', Side::Buy, '0.05', '60000', '0', 'BTC', '0', strategy: 'a'));
    $ledger->apply(fill('2', Side::Buy, '0.02', '60000', '0', 'BTC', '0', strategy: 'b'));
    $ledger->apply(fill('3', Side::Sell, '0.02', '61000', '0', 'USDT', '0', strategy: 'b', reason: ExitReason::Signal));

    expect((string) $ledger->position('a', Symbol::parse('BTC/USDT'))?->quantity)->toBe('0.05')
        ->and($ledger->position('b', Symbol::parse('BTC/USDT')))->toBeNull()
        ->and($ledger->positions())->toHaveCount(1)
        ->and(balanceOf($ledger, 'BTC'))->toBe('0.05');
});

it('charges a fee in a third asset', function (): void {
    $ledger = ledgerWith('10000 USDT', '1 BNB');
    $ledger->apply(fill('1', Side::Buy, '0.1', '60000', '0.01', 'BNB', '4.5'));

    expect(balanceOf($ledger, 'BNB'))->toBe('0.99')
        ->and(balanceOf($ledger, 'BTC'))->toBe('0.1')
        ->and(balanceOf($ledger, 'USDT'))->toBe('4000');
});

it('refuses what would make a balance negative or has no position', function (callable $breakIt): void {
    $breakIt(ledgerWith('1000 USDT'));
})->with([
    'buy beyond cash' => [fn (Ledger $l) => $l->apply(fill('1', Side::Buy, '1', '1000.01', '0', 'BTC', '0'))],
    'sell without position' => [fn (Ledger $l) => $l->apply(fill('1', Side::Sell, '0.1', '100', '0', 'USDT', '0'))],
    'sell beyond position' => [function (Ledger $l): void {
        $l->apply(fill('1', Side::Buy, '1', '100', '0', 'BTC', '0'));
        $l->apply(fill('2', Side::Sell, '1.1', '100', '0', 'USDT', '0'));
    }],
    'fee asset missing' => [fn (Ledger $l) => $l->apply(fill('1', Side::Buy, '1', '100', '0.1', 'BNB', '0'))],
    'the same fill twice' => [function (Ledger $l): void {
        $l->apply(fill('1', Side::Buy, '1', '100', '0', 'BTC', '0'));
        $l->apply(fill('1', Side::Buy, '1', '100', '0', 'BTC', '0'));
    }],
    'another quote asset' => [fn (Ledger $l) => $l->apply(fill('1', Side::Buy, '1', '100', '0', 'BTC', '0', symbol: 'BTC/USDC'))],
    'dust of no position' => [fn (Ledger $l) => $l->writeOffDust('x', Symbol::parse('BTC/USDT'), CarbonImmutable::parse('2026-10-01', 'UTC'))],
])->throws(LedgerViolation::class);

it('refuses a fill with a quantity that is not positive', function (): void {
    fill('1', Side::Buy, '0', '100', '0', 'BTC', '0');
})->throws(InvalidArgumentException::class);

it('keeps every balance equal to its initial value plus flows minus fees', function (): void {
    $random = new Randomizer(new Xoshiro256StarStar(2026));

    for ($run = 0; $run < 1000; $run++) {
        $ledger = ledgerWith('10000 USDT');
        $flows = ['USDT' => Decimal::of('10000'), 'BTC' => Decimal::zero()];

        for ($step = 0; $step < 6; $step++) {
            $price = Decimal::of((string) $random->getInt(50, 150));
            $held = $ledger->position('s', Symbol::parse('BTC/USDT'))->quantity ?? Decimal::zero();

            if ($held->isPositive() && $random->getInt(0, 1) === 1) {
                $quantity = $held->times(Decimal::of((string) $random->getInt(1, 10)))->dividedBy(Decimal::of(10), 18);
                $fee = $quantity->times($price)->times(Decimal::of('0.001'));
                $ledger->apply(fill("{$run}-{$step}", Side::Sell, (string) $quantity, (string) $price, (string) $fee, 'USDT', (string) $fee, strategy: 's', minute: $step, reason: ExitReason::Signal));
                $flows['BTC'] = $flows['BTC']->minus($quantity);
                $flows['USDT'] = $flows['USDT']->plus($quantity->times($price))->minus($fee);

                continue;
            }

            $quantity = Decimal::of((string) $random->getInt(1, 20))->dividedBy(Decimal::of(1000), 3);

            if ($quantity->times($price)->isGreaterThan($ledger->balance(Asset::of('USDT')))) {
                continue;
            }

            $fee = $quantity->times(Decimal::of('0.001'));
            $ledger->apply(fill("{$run}-{$step}", Side::Buy, (string) $quantity, (string) $price, (string) $fee, 'BTC', (string) $fee->times($price), strategy: 's', minute: $step));
            $flows['BTC'] = $flows['BTC']->plus($quantity)->minus($fee);
            $flows['USDT'] = $flows['USDT']->minus($quantity->times($price));
        }

        expect(balanceOf($ledger, 'USDT'))->toBe((string) $flows['USDT'])
            ->and(balanceOf($ledger, 'BTC'))->toBe((string) $flows['BTC'])
            ->and((string) ($ledger->position('s', Symbol::parse('BTC/USDT'))->quantity ?? Decimal::zero()))->toBe((string) $flows['BTC']);
    }
});

it('adds up dust written off over several round trips', function (): void {
    $ledger = ledgerWith('10000 USDT');
    $btc = Symbol::parse('BTC/USDT');

    foreach ([1, 2] as $trip) {
        $ledger->apply(fill("b{$trip}", Side::Buy, '0.1', '60000', '0.0001', 'BTC', '6'));
        $ledger->apply(fill("s{$trip}", Side::Sell, '0.099', '60000', '0', 'USDT', '0', reason: ExitReason::Signal));
        $ledger->writeOffDust('breakout', $btc, CarbonImmutable::parse('2026-10-01', 'UTC'));
    }

    expect((string) $ledger->dust(Asset::of('BTC')))->toBe('0.0018')
        ->and((string) $ledger->dust(Asset::of('ETH')))->toBe('0');
});

it('lists balances in asset order and positions as a list', function (): void {
    $ledger = ledgerWith('10000 USDT', '1 BNB');
    $ledger->apply(fill('1', Side::Buy, '0.1', '60000', '0', 'BTC', '0', strategy: 'z'));
    $ledger->apply(fill('2', Side::Buy, '1', '2000', '0', 'ETH', '0', strategy: 'a', symbol: 'ETH/USDT'));

    expect(array_map(fn (Decimal $balance): string => (string) $balance, $ledger->balances()))
        ->toBe(['BNB' => '1', 'BTC' => '0.1', 'ETH' => '1', 'USDT' => '2000'])
        ->and(array_keys($ledger->positions()))->toBe([0, 1]);
});

it('values only what is held and skips assets whose balance is zero', function (): void {
    $ledger = ledgerWith('10000 USDT');
    $ledger->apply(fill('1', Side::Buy, '0.1', '60000', '0', 'BTC', '0'));
    $ledger->apply(fill('2', Side::Sell, '0.1', '61000', '0', 'USDT', '0', reason: ExitReason::Signal));
    $ledger->apply(fill('3', Side::Buy, '1', '2000', '0', 'ETH', '0', symbol: 'ETH/USDT'));
    $marks = (new Marks(Asset::of('USDT')))->with(Symbol::parse('ETH/USDT'), Price::of('2100'));

    expect((string) $ledger->equity($marks))->toBe('10200')
        ->and($marks->with(Symbol::parse('SOL/USDT'), Price::of('150'))->priceOf(Asset::of('ETH'))?->value)->toEqual(Decimal::of('2100'))
        ->and($marks->priceOf(Asset::of('SOL')))->toBeNull();
});

it('refuses a base fee larger than the quantity bought', function (): void {
    ledgerWith('1000 USDT')->apply(fill('1', Side::Buy, '0.1', '100', '0.2', 'BTC', '20'));
})->throws(LedgerViolation::class, 'BTC');
