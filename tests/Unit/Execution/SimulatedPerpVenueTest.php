<?php

declare(strict_types=1);

use App\Execution\Data\ClientOrderId;
use App\Execution\Data\FeeSchedule;
use App\Execution\Data\Income;
use App\Execution\Data\IncomeKind;
use App\Execution\Data\OrderRequest;
use App\Execution\Data\OrderUpdate;
use App\Execution\Data\VenuePosition;
use App\Execution\Venues\CostModel;
use App\Execution\Venues\SimulatedFuturesBook;
use App\Execution\Venues\SimulatedVenue;
use App\Market\Data\Candle;
use App\Market\Data\FundingRate;
use App\Market\Data\InstrumentRules;
use App\Market\Data\RulesHistory;
use App\Support\Clock\SimClock;
use App\Support\Number\Decimal;
use App\Support\Number\Price;
use App\Support\Side;
use App\Support\Symbol;
use App\Support\Timeframe;
use Carbon\CarbonImmutable;

mutates(SimulatedVenue::class, SimulatedFuturesBook::class);

final class PerpHarness
{
    public int $minute = 0;

    public function __construct(public SimulatedVenue $venue, public SimClock $clock) {}

    public static function make(string $wallet = '10000', string $halfSpread = '0'): self
    {
        $clock = new SimClock(CarbonImmutable::parse('2026-10-01', 'UTC'));
        $rules = new InstrumentRules(CarbonImmutable::parse('2026-01-01', 'UTC'), Decimal::of('0.1'), Decimal::of('0.001'), Decimal::of('0.001'), Decimal::of('1000'), Decimal::of('100'));
        $venue = new SimulatedVenue(
            $clock,
            new FeeSchedule(Decimal::of('0.001'), Decimal::of('0.001')),
            new CostModel(1, Decimal::of($halfSpread), Decimal::zero(), Decimal::of('0.1')),
            ['BTC/USDT:USDT' => new RulesHistory(perp(), [$rules]), 'ETH/USDT:USDT' => new RulesHistory(Symbol::parse('ETH/USDT:USDT'), [$rules])],
            [],
            perpFees: new FeeSchedule(Decimal::of('0.0002'), Decimal::of('0.0005')),
            maintenanceRate: Decimal::of('0.01'),
            futuresWallet: Decimal::of($wallet),
        );
        $h = new self($venue, $clock);
        $h->minute('100', '100', '100', '100');

        return $h;
    }

    public function minute(string $open, string $high, string $low, string $close): void
    {
        $candle = Candle::fromStrings(
            CarbonImmutable::parse('2026-10-01', 'UTC')->addMinutes($this->minute), Timeframe::OneMinute,
            $open, $high, $low, $close, '1000', '0', 1, '0',
        );
        $this->minute++;
        $this->clock->advanceTo($candle->endsAt());
        $this->venue->match(perp(), $candle);
    }

    /** @return list<string> */
    public function updates(): array
    {
        return array_map(fn (OrderUpdate|Income $u): string => $u instanceof Income
            ? "{$u->kind->value} {$u->amount}".($u->price === null ? '' : " @{$u->price}")
            : "{$u->clientOrderId} {$u->status->value}".($u->fill === null ? '' : " {$u->fill->side->value} {$u->fill->quantity}@{$u->fill->price} fee {$u->fill->fee}"),
            $this->venue->updatesSince(0));
    }

    public function position(): string
    {
        $p = $this->venue->positions()['BTC/USDT:USDT'] ?? null;

        return $p === null ? 'none' : "{$p->side->value} {$p->quantity}@{$p->entryPrice} margin {$p->margin} x{$p->leverage}";
    }

    public function short(string $quantity = '1', string $id = 'e'): void
    {
        $this->venue->setLeverage(perp(), 2);
        expect($this->venue->submit(OrderRequest::market(ClientOrderId::of($id), perp(), Side::Sell, Decimal::of($quantity)))->accepted)->toBeTrue();
    }

    public function long(string $quantity = '1'): void
    {
        $this->venue->setLeverage(perp(), 2);
        expect($this->venue->submit(OrderRequest::market(ClientOrderId::of('e'), perp(), Side::Buy, Decimal::of($quantity)))->accepted)->toBeTrue();
    }
}

function perp(): Symbol
{
    return Symbol::parse('BTC/USDT:USDT');
}

function reduce(string $id, Side $side, string $quantity): OrderRequest
{
    return OrderRequest::market(ClientOrderId::of($id), perp(), $side, Decimal::of($quantity))->reduceOnly();
}

it('opens a short at the next open, taking margin and the taker fee from the USD-M wallet', function (): void {
    $h = PerpHarness::make();
    $h->short();
    $h->minute('100', '101', '99', '100');

    expect($h->updates())->toBe(['e new', 'e filled sell 1@100 fee 0.05 USDT'])
        ->and($h->position())->toBe('sell 1@100 margin 50 x2')
        ->and((string) $h->venue->futuresWallet())->toBe('9949.95');
});

it('refuses an entry the wallet cannot margin, below the minimum notional, or of a type it does not simulate', function (): void {
    $h = PerpHarness::make('10');
    $h->venue->setLeverage(perp(), 2);

    expect($h->venue->submit(OrderRequest::market(ClientOrderId::of('a'), perp(), Side::Sell, Decimal::of('1')))->reason)->toBe('INSUFFICIENT_MARGIN')
        ->and($h->venue->submit(OrderRequest::market(ClientOrderId::of('b'), perp(), Side::Sell, Decimal::of('0.5')))->reason)->toBe('NOTIONAL')
        ->and($h->venue->submit(OrderRequest::limit(ClientOrderId::of('c'), perp(), Side::Sell, Decimal::of('1'), Price::of('100')))->reason)->toBe('UNSUPPORTED_ORDER_TYPE')
        ->and($h->venue->submit(reduce('d', Side::Buy, '0.5'))->accepted)->toBeTrue();
});

it('caps a reduce-only exit at the position, releasing margin and the realized profit', function (): void {
    $h = PerpHarness::make();
    $h->short();
    $h->minute('100', '100', '100', '100');
    $h->venue->submit(reduce('x', Side::Buy, '2'));
    $h->minute('90', '90', '90', '90');

    expect($h->updates()[3])->toBe('x filled buy 1@90 fee 0.045 USDT')
        ->and($h->position())->toBe('none')
        ->and((string) $h->venue->futuresWallet())->toBe('10009.905');
});

it('lets a reduce-only order expire when it would open or increase a position', function (Side $side): void {
    $h = PerpHarness::make();
    $h->short();
    $h->minute('100', '100', '100', '100');
    $h->venue->submit(reduce('x', $side, '1'));
    $h->minute('100', '100', '100', '100');

    expect($h->updates()[3])->toBe($side === Side::Sell ? 'x expired' : 'x filled buy 1@100 fee 0.05 USDT');
})->with([Side::Sell, Side::Buy]);

it('lets a reduce-only order expire when there is no position', function (): void {
    $h = PerpHarness::make();
    $h->venue->submit(reduce('x', Side::Buy, '1'));
    $h->minute('100', '100', '100', '100');

    expect($h->updates())->toBe(['x new', 'x expired']);
});

it('triggers a short\'s stop on the high, at the open when the minute gapped through it', function (): void {
    $h = PerpHarness::make();
    $h->short();
    $h->minute('100', '100', '100', '100');
    $h->venue->submit(OrderRequest::stop(ClientOrderId::of('s'), perp(), Side::Buy, Decimal::of('1'), Price::of('105'))->reduceOnly());
    $h->minute('104', '104.9', '103', '104');

    expect($h->updates())->toHaveCount(3);

    $h->minute('106', '107', '105.5', '106');

    expect($h->updates()[3])->toBe('s filled buy 1@106 fee 0.053 USDT')
        ->and((string) $h->venue->futuresWallet())->toBe('9993.897');
});

it('takes a short\'s profit on the low at the target, and fills the stop first when a minute reaches both', function (): void {
    $h = PerpHarness::make();
    $h->short();
    $h->minute('100', '100', '100', '100');
    $h->venue->submit(OrderRequest::takeProfit(ClientOrderId::of('t'), perp(), Side::Buy, Decimal::of('1'), Price::of('95')));
    $h->minute('97', '98', '94', '96');

    expect($h->updates()[3])->toBe('t filled buy 1@95 fee 0.0475 USDT')
        ->and((string) $h->venue->futuresWallet())->toBe('10004.9025');

    $both = PerpHarness::make();
    $both->short();
    $both->minute('100', '100', '100', '100');
    $both->venue->submit(OrderRequest::takeProfit(ClientOrderId::of('t'), perp(), Side::Buy, Decimal::of('1'), Price::of('95')));
    $both->venue->submit(OrderRequest::stop(ClientOrderId::of('s'), perp(), Side::Buy, Decimal::of('1'), Price::of('105'))->reduceOnly());
    $both->minute('100', '106', '94', '100');

    expect(array_slice($both->updates(), 4))->toBe(['s filled buy 1@105 fee 0.0525 USDT', 't expired']);
});

it('takes a long\'s profit on the high, at the open when the minute gapped above the target', function (): void {
    $h = PerpHarness::make();
    $h->long();
    $h->minute('100', '100', '100', '100');
    $h->venue->submit(OrderRequest::takeProfit(ClientOrderId::of('t'), perp(), Side::Sell, Decimal::of('1'), Price::of('110')));
    $h->minute('112', '113', '111', '112');

    expect($h->updates()[3])->toBe('t filled sell 1@112 fee 0.056 USDT');
});

it('settles funding at its time: shorts receive and longs pay a positive rate', function (): void {
    $h = PerpHarness::make();
    $h->short();
    $h->minute('100', '100', '100', '100');
    $h->venue->settleFunding(perp(), new FundingRate(CarbonImmutable::parse('2026-10-01 00:02', 'UTC'), Decimal::of('0.0001'), 8, null), Price::of('100'));

    $l = PerpHarness::make();
    $l->long();
    $l->minute('100', '100', '100', '100');
    $l->venue->settleFunding(perp(), new FundingRate(CarbonImmutable::parse('2026-10-01 00:02', 'UTC'), Decimal::of('0.0001'), 8, null), Price::of('100'));
    $l->venue->settleFunding(Symbol::parse('ETH/USDT:USDT'), new FundingRate(CarbonImmutable::parse('2026-10-01 00:02', 'UTC'), Decimal::of('0.0001'), 8, null), Price::of('100'));

    expect($h->updates()[2])->toBe('funding 0.01')
        ->and((string) $h->venue->futuresWallet())->toBe('9949.96')
        ->and(array_slice($l->updates(), 2))->toBe(['funding -0.01'])
        ->and($h->venue->updatesSince(2)[0]->sequence)->toBe(3);
});

it('takes funding the wallet cannot pay from the position\'s margin', function (): void {
    $h = PerpHarness::make('50.05');
    $h->short();
    $h->minute('100', '100', '100', '100');
    $h->venue->settleFunding(perp(), new FundingRate(CarbonImmutable::parse('2026-10-01 00:02', 'UTC'), Decimal::of('-0.0002'), 8, null), Price::of('100'));

    expect((string) $h->venue->futuresWallet())->toBe('0')
        ->and($h->position())->toBe('sell 1@100 margin 49.98 x2');
});

it('liquidates on the minute\'s adverse extreme and loses the whole margin', function (): void {
    $h = PerpHarness::make();
    $h->long();
    $h->minute('100', '100', '100', '100');
    $h->minute('60', '61', '50', '55');

    expect(array_slice($h->updates(), 2))->toBe(['liquidation -50 @50.50505050505050505'])
        ->and($h->position())->toBe('none')
        ->and((string) $h->venue->futuresWallet())->toBe('9949.95');
});

it('liquidates at the open before matching orders when the minute gaps through the liquidation price', function (): void {
    $h = PerpHarness::make();
    $h->short();
    $h->minute('100', '100', '100', '100');
    $h->venue->submit(OrderRequest::stop(ClientOrderId::of('s'), perp(), Side::Buy, Decimal::of('1'), Price::of('120'))->reduceOnly());
    $h->minute('160', '161', '159', '160');

    expect(array_slice($h->updates(), 3))->toBe(['liquidation -50 @148.514851485148514851', 's expired']);
});

it('keeps the leverage of an open position', function (): void {
    $h = PerpHarness::make();
    $h->short();
    $h->minute('100', '100', '100', '100');

    $h->venue->setLeverage(perp(), 3);
})->throws(InvalidArgumentException::class, 'leverage');

it('names fills and liquidations, and moves prices against the order by the slippage', function (): void {
    $h = PerpHarness::make(halfSpread: '0.001');
    $h->short();
    $h->minute('100', '100', '100', '100');
    $h->venue->submit(reduce('x', Side::Buy, '1'));
    $h->minute('100', '100', '100', '100');
    $fills = array_values(array_filter(array_map(fn ($u) => $u instanceof OrderUpdate ? $u->fill : null, $h->venue->updatesSince(0))));

    expect(array_map(fn ($f): string => "{$f->id} {$f->price} {$f->slippage}", $fills))->toBe(['fut-1 99.9 0.1', 'fut-2 100.1 0.1']);

    $l = PerpHarness::make();
    $l->long();
    $l->minute('100', '100', '100', '100');
    $l->minute('50', '50', '50', '50');
    $income = $l->venue->updatesSince(2)[0];

    expect($income instanceof Income ? $income->id : null)->toBe('liquidation-1')
        ->and($income instanceof Income ? $income->kind : null)->toBe(IncomeKind::Liquidation);
});

it('enters at 1x when no leverage was set, and refuses a leverage under 1', function (): void {
    $h = PerpHarness::make();
    $h->venue->submit(OrderRequest::market(ClientOrderId::of('e'), perp(), Side::Sell, Decimal::of('1')));
    $h->minute('100', '100', '100', '100');

    expect($h->position())->toBe('sell 1@100 margin 100 x1')
        ->and($h->venue->positions()['BTC/USDT:USDT'])->toBeInstanceOf(VenuePosition::class);

    $h->venue->setLeverage(Symbol::parse('ETH/USDT:USDT'), 0);
})->throws(InvalidArgumentException::class);

it('matches only the orders of the minute\'s perpetual', function (): void {
    $h = PerpHarness::make();
    $h->venue->setLeverage(Symbol::parse('ETH/USDT:USDT'), 2);
    $h->venue->match(Symbol::parse('ETH/USDT:USDT'), Candle::fromStrings(CarbonImmutable::parse('2026-10-01', 'UTC'), Timeframe::OneMinute, '100', '100', '100', '100', '1000', '0', 1, '0'));
    $h->venue->submit(OrderRequest::market(ClientOrderId::of('eth'), Symbol::parse('ETH/USDT:USDT'), Side::Sell, Decimal::of('1')));
    $h->minute('100', '100', '100', '100');

    expect($h->updates())->toBe(['eth new'])
        ->and(array_keys($h->venue->positions()))->toBe([]);
});

it('refuses quantities off the lot step or above the maximum', function (string $quantity): void {
    $h = PerpHarness::make();

    expect($h->venue->submit(OrderRequest::market(ClientOrderId::of('a'), perp(), Side::Sell, Decimal::of($quantity)))->reason)->toBe('LOT_SIZE');
})->with(['1.0005', '1001', '0.0005']);

it('gives back the margin a cancelled entry reserved', function (): void {
    $h = PerpHarness::make('60');
    $h->venue->setLeverage(perp(), 2);
    $h->venue->submit(OrderRequest::market(ClientOrderId::of('a'), perp(), Side::Sell, Decimal::of('1')));

    expect($h->venue->submit(OrderRequest::market(ClientOrderId::of('b'), perp(), Side::Sell, Decimal::of('1')))->reason)->toBe('INSUFFICIENT_MARGIN')
        ->and($h->venue->cancel(ClientOrderId::of('a')))->toBeTrue()
        ->and($h->venue->cancel(ClientOrderId::of('a')))->toBeFalse()
        ->and($h->venue->submit(OrderRequest::market(ClientOrderId::of('c'), perp(), Side::Sell, Decimal::of('1')))->accepted)->toBeTrue()
        ->and((string) $h->venue->futuresWallet())->toBe('60');
});
