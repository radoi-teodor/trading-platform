<?php

declare(strict_types=1);

use App\Portfolio\Data\ExitReason;
use App\Portfolio\Data\Fill;
use App\Portfolio\Data\Marks;
use App\Portfolio\Data\PerpPosition;
use App\Portfolio\Ledger;
use App\Portfolio\LedgerViolation;
use App\Support\Asset;
use App\Support\Number\Decimal;
use App\Support\Number\Money;
use App\Support\Number\Price;
use App\Support\Perpetual;
use App\Support\Side;
use App\Support\Symbol;
use Carbon\CarbonImmutable;
use Random\Engine\Xoshiro256StarStar;
use Random\Randomizer;

mutates(Ledger::class, Perpetual::class, PerpPosition::class);

function perpLedger(string $wallet = '1000', string $spot = '0'): Ledger
{
    return new Ledger(Asset::of('USDT'), [Money::of($spot, Asset::of('USDT'))], Decimal::of($wallet));
}

function perpFill(string $id, Side $side, string $quantity, string $price, string $fee, ?int $leverage = 2, int $minute = 0, ?string $stop = null, ?ExitReason $reason = null, string $symbol = 'BTC/USDT:USDT', string $strategy = 's'): Fill
{
    return new Fill(
        id: $id,
        time: CarbonImmutable::parse('2026-10-01', 'UTC')->addMinutes($minute),
        strategy: $strategy,
        symbol: Symbol::parse($symbol),
        side: $side,
        quantity: Decimal::of($quantity),
        price: Price::of($price),
        fee: Money::of($fee, Asset::of('USDT')),
        feeValue: Decimal::of($fee),
        slippage: Decimal::zero(),
        initialStop: $stop === null ? null : Price::of($stop),
        exitReason: $reason,
        leverage: $leverage,
    );
}

function perpSymbol(): Symbol
{
    return Symbol::parse('BTC/USDT:USDT');
}

it('opens a short with margin from the wallet and closes it with the profit', function (): void {
    $ledger = perpLedger();
    // notional 100, fee 0.05, margin 100 / 2 = 50
    $ledger->apply(perpFill('1', Side::Sell, '1', '100', '0.05', stop: '105'));
    $position = $ledger->perpPosition('s', perpSymbol());

    expect((string) $ledger->futuresWallet())->toBe('949.95')
        ->and($position?->side)->toBe(Side::Sell)
        ->and((string) $position?->margin)->toBe('50')
        ->and((string) $ledger->equity(new Marks(Asset::of('USDT'), ['BTC/USDT:USDT' => Price::of('90')])))->toBe('1009.95');

    $ledger->apply(perpFill('2', Side::Buy, '1', '90', '0.045', leverage: null, minute: 30, reason: ExitReason::Target));
    $trip = $ledger->roundTrips()[0];

    expect((string) $ledger->futuresWallet())->toBe('1009.905')
        ->and($ledger->perpPosition('s', perpSymbol()))->toBeNull()
        ->and($trip->side)->toBe(Side::Sell)
        ->and((string) $trip->entryPrice)->toBe('100')
        ->and((string) $trip->exitPrice)->toBe('90')
        ->and((string) $trip->grossProfit)->toBe('10')
        ->and((string) $trip->fees)->toBe('0.095')
        ->and((string) $trip->netProfit)->toBe('9.905')
        ->and((string) $trip->rMultiple)->toBe('1.981')
        ->and($trip->exitReason)->toBe(ExitReason::Target)
        ->and((string) $ledger->tradedNotional())->toBe('190');
});

it('releases margin in proportion when reducing and averages the entry when adding', function (): void {
    $ledger = perpLedger();
    $ledger->apply(perpFill('1', Side::Buy, '2', '100', '0'));
    $ledger->apply(perpFill('2', Side::Sell, '0.5', '110', '0', leverage: null, minute: 1, reason: ExitReason::Signal));

    expect((string) $ledger->futuresWallet())->toBe('930')
        ->and((string) $ledger->perpPosition('s', perpSymbol())?->margin)->toBe('75');

    // An added fill carries another leverage: the position keeps its own.
    $ledger->apply(perpFill('3', Side::Buy, '0.5', '120', '0', leverage: 5, minute: 2));
    $position = $ledger->perpPosition('s', perpSymbol());

    expect((string) $position?->entryPrice)->toBe('105')
        ->and((string) $position?->quantity)->toBe('2')
        ->and((string) $position?->margin)->toBe('105')
        ->and((string) $ledger->futuresWallet())->toBe('900')
        ->and(array_keys($ledger->perpPositions()))->toBe([0]);
});

it('books funding paid and received on the wallet and in the round trip', function (): void {
    $ledger = perpLedger();
    $ledger->apply(perpFill('1', Side::Sell, '1', '100', '0'));
    $ledger->applyFunding('f1', 's', perpSymbol(), Decimal::of('0.01'), CarbonImmutable::parse('2026-10-01 08:00', 'UTC'));
    $ledger->applyFunding('f2', 's', perpSymbol(), Decimal::of('-0.004'), CarbonImmutable::parse('2026-10-01 16:00', 'UTC'));
    $ledger->apply(perpFill('2', Side::Buy, '1', '100', '0', leverage: null, minute: 1000, reason: ExitReason::Signal));
    $trip = $ledger->roundTrips()[0];

    expect((string) $ledger->futuresWallet())->toBe('1000.006')
        ->and((string) $trip->funding)->toBe('0.006')
        ->and((string) $trip->netProfit)->toBe('0.006')
        ->and((string) $trip->grossProfit)->toBe('0');
});

it('loses the whole isolated margin on liquidation', function (): void {
    $ledger = perpLedger();
    $ledger->apply(perpFill('1', Side::Buy, '1', '100', '0.05'));
    $ledger->liquidate('l1', 's', perpSymbol(), Price::of('50.6'), CarbonImmutable::parse('2026-10-01 01:00', 'UTC'));
    $trip = $ledger->roundTrips()[0];

    expect((string) $ledger->futuresWallet())->toBe('949.95')
        ->and($ledger->perpPositions())->toBe([])
        ->and($trip->exitReason)->toBe(ExitReason::Liquidation)
        ->and((string) $trip->exitPrice)->toBe('50.6')
        ->and((string) $trip->grossProfit)->toBe('-50')
        ->and((string) $trip->netProfit)->toBe('-50.05');
});

it('computes isolated liquidation prices', function (): void {
    expect((string) Perpetual::liquidationPrice(Side::Buy, Decimal::of('1'), Decimal::of('100'), Decimal::of('50'), Decimal::of('0.01')))->toBe('50.50505050505050505')
        ->and((string) Perpetual::liquidationPrice(Side::Sell, Decimal::of('1'), Decimal::of('100'), Decimal::of('50'), Decimal::of('0.01')))->toBe('148.514851485148514851')
        ->and((string) Perpetual::initialMargin(Decimal::of('0.003'), Decimal::of('60000.1'), 3))->toBe('60.0001')
        ->and((string) Perpetual::released(Decimal::of('10'), Decimal::of('1'), Decimal::of('3')))->toBe('3.333333333333333333');
});

it('values spot, the USD-M wallet and open positions together, and tracks short excursions', function (): void {
    $ledger = perpLedger('1000', '500');
    $ledger->apply(perpFill('1', Side::Sell, '1', '100', '0'));
    $ledger->observe(perpSymbol(), Price::of('104'), Price::of('97'));
    $marks = new Marks(Asset::of('USDT'), ['BTC/USDT:USDT' => Price::of('101')]);

    expect((string) $ledger->equity($marks))->toBe('1499')
        ->and((string) $ledger->perpPosition('s', perpSymbol())?->highest)->toBe('104');

    $ledger->apply(perpFill('2', Side::Buy, '1', '101', '0', leverage: null, minute: 5, reason: ExitReason::Signal));

    expect((string) $ledger->roundTrips()[0]->maximumAdverseExcursion)->toBe('4')
        ->and((string) $ledger->roundTrips()[0]->maximumFavourableExcursion)->toBe('3');
});

it('refuses what a perpetual account cannot book', function (callable $breakIt): void {
    $breakIt(perpLedger('100'));
})->with([
    'no leverage on opening' => [fn (Ledger $l) => $l->apply(perpFill('1', Side::Buy, '1', '100', '0', leverage: null))],
    'margin beyond the wallet' => [fn (Ledger $l) => $l->apply(perpFill('1', Side::Buy, '3', '100', '0', leverage: 2))],
    'reducing more than held' => [function (Ledger $l): void {
        $l->apply(perpFill('1', Side::Buy, '1', '100', '0'));
        $l->apply(perpFill('2', Side::Sell, '1.5', '100', '0', leverage: null));
    }],
    'a fee in another asset' => [fn (Ledger $l) => $l->apply(new Fill('1', CarbonImmutable::parse('2026-10-01', 'UTC'), 's', perpSymbol(), Side::Buy, Decimal::of('1'), Price::of('10'), Money::of('0.1', Asset::of('BNB')), Decimal::zero(), Decimal::zero(), leverage: 2))],
    'funding without a position' => [fn (Ledger $l) => $l->applyFunding('f', 's', perpSymbol(), Decimal::of('1'), CarbonImmutable::parse('2026-10-01', 'UTC'))],
    'funding twice' => [function (Ledger $l): void {
        $l->apply(perpFill('1', Side::Buy, '1', '100', '0'));
        $l->applyFunding('f', 's', perpSymbol(), Decimal::of('1'), CarbonImmutable::parse('2026-10-01', 'UTC'));
        $l->applyFunding('f', 's', perpSymbol(), Decimal::of('1'), CarbonImmutable::parse('2026-10-01', 'UTC'));
    }],
    'liquidating nothing' => [fn (Ledger $l) => $l->liquidate('l', 's', perpSymbol(), Price::of('1'), CarbonImmutable::parse('2026-10-01', 'UTC'))],
])->throws(LedgerViolation::class);

it('keeps the wallet plus open margins equal to the start plus realized profits, funding and fees', function (): void {
    $random = new Randomizer(new Xoshiro256StarStar(7));

    for ($run = 0; $run < 300; $run++) {
        $ledger = perpLedger('100000');
        $expected = Decimal::of('100000');
        $side = $random->getInt(0, 1) === 1 ? Side::Buy : Side::Sell;

        for ($step = 0; $step < 6; $step++) {
            $price = Decimal::of((string) $random->getInt(80, 120));
            $position = $ledger->perpPosition('s', perpSymbol());
            $quantity = Decimal::of((string) $random->getInt(1, 9))->dividedBy(Decimal::of(10), 1);
            $fee = $quantity->times($price)->times(Decimal::of('0.0005'));

            if ($position !== null && $random->getInt(0, 2) === 0) {
                $amount = Decimal::of((string) $random->getInt(-50, 50))->dividedBy(Decimal::of(1000), 3);
                $ledger->applyFunding("f{$run}-{$step}", 's', perpSymbol(), $amount, CarbonImmutable::parse('2026-10-01', 'UTC'));
                $expected = $expected->plus($amount);

                continue;
            }

            if ($position !== null && $random->getInt(0, 1) === 1) {
                $quantity = $quantity->isGreaterThan($position->quantity) ? $position->quantity : $quantity;
                $opposite = $position->side === Side::Buy ? Side::Sell : Side::Buy;
                $direction = $position->side === Side::Buy ? Decimal::of(1) : Decimal::of(-1);
                $realized = $direction->times($quantity)->times($price->minus($position->entryPrice));
                $ledger->apply(perpFill("{$run}-{$step}", $opposite, (string) $quantity, (string) $price, (string) $fee, leverage: null, minute: $step, reason: ExitReason::Signal));
                $expected = $expected->plus($realized)->minus($fee);

                continue;
            }

            $ledger->apply(perpFill("{$run}-{$step}", $position->side ?? $side, (string) $quantity, (string) $price, (string) $fee, leverage: 2, minute: $step));
            $expected = $expected->minus($fee);
        }

        $margins = array_reduce($ledger->perpPositions(), fn (Decimal $carry, $p): Decimal => $carry->plus($p->margin), Decimal::zero());

        expect((string) $ledger->futuresWallet()->plus($margins))->toBe((string) $expected);
    }
});

it('pays funding from longs and to shorts when the rate is positive', function (): void {
    expect((string) Perpetual::funding(Side::Buy, Decimal::of('2'), Decimal::of('100'), Decimal::of('0.0001')))->toBe('-0.02')
        ->and((string) Perpetual::funding(Side::Sell, Decimal::of('2'), Decimal::of('100'), Decimal::of('0.0001')))->toBe('0.02')
        ->and((string) Perpetual::funding(Side::Sell, Decimal::of('2'), Decimal::of('100'), Decimal::of('-0.0001')))->toBe('-0.02');
});

it('keeps the first stop when a position is added to without one', function (): void {
    $ledger = perpLedger();
    $ledger->apply(perpFill('1', Side::Buy, '1', '100', '0', stop: '90'));
    $ledger->apply(perpFill('2', Side::Buy, '1', '110', '0', minute: 1));
    $ledger->apply(perpFill('3', Side::Sell, '2', '120', '0', leverage: null, minute: 2, reason: ExitReason::Signal));
    $trip = $ledger->roundTrips()[0];

    // entry 105, risk 2 × (105 - 90) = 30, net 30
    expect((string) $trip->entryPrice)->toBe('105')
        ->and((string) $trip->initialRisk)->toBe('30')
        ->and((string) $trip->rMultiple)->toBe('1');
});

it('takes funding the wallet cannot pay from the position\'s margin', function (): void {
    $ledger = perpLedger('50.05');
    $ledger->apply(perpFill('1', Side::Sell, '1', '100', '0.05'));
    $ledger->applyFunding('f', 's', perpSymbol(), Decimal::of('-0.02'), CarbonImmutable::parse('2026-10-01 08:00', 'UTC'));

    expect((string) $ledger->futuresWallet())->toBe('0')
        ->and((string) $ledger->perpPosition('s', perpSymbol())?->margin)->toBe('49.98')
        ->and((string) $ledger->perpPosition('s', perpSymbol())?->funding)->toBe('-0.02');
});
