<?php

declare(strict_types=1);

use App\Execution\Data\ClientOrderId;
use App\Execution\Data\FeeSchedule;
use App\Execution\Data\Liquidity;
use App\Execution\Data\OcoRequest;
use App\Execution\Data\OrderRequest;
use App\Execution\Data\OrderStatus;
use App\Execution\Data\OrderUpdate;
use App\Execution\Venues\CostModel;
use App\Execution\Venues\SimulatedVenue;
use App\Market\Data\Candle;
use App\Market\Data\InstrumentRules;
use App\Market\Data\RulesHistory;
use App\Support\Asset;
use App\Support\Clock\SimClock;
use App\Support\Number\Decimal;
use App\Support\Number\Money;
use App\Support\Number\Price;
use App\Support\Side;
use App\Support\Symbol;
use App\Support\Timeframe;
use Carbon\CarbonImmutable;

mutates(SimulatedVenue::class, CostModel::class, FeeSchedule::class);

const VENUE_START = '2026-10-01 00:00:00';

final class VenueHarness
{
    public int $minute = 0;

    public function __construct(public SimulatedVenue $venue, public SimClock $clock) {}

    /** Feeds the next minute to the venue, as the backtest driver does, and moves the clock to its end. */
    public function minute(string $open, string $high, string $low, string $close, string $volume = '1000'): void
    {
        $candle = Candle::fromStrings(
            CarbonImmutable::parse(VENUE_START, 'UTC')->addMinutes($this->minute),
            Timeframe::OneMinute, $open, $high, $low, $close, $volume, '0', 1, '0',
        );
        $this->venue->match(Symbol::parse('BTC/USDT'), $candle);
        $this->minute++;
        $this->clock->advanceTo($candle->endsAt());
    }

    public function balance(string $asset): string
    {
        $balance = $this->venue->balances()[$asset] ?? null;

        return $balance === null ? '0' : "{$balance->free}/{$balance->locked}";
    }

    /** @return list<string> */
    public function updates(): array
    {
        return array_map(
            fn (OrderUpdate $u): string => "{$u->clientOrderId} {$u->status->value}".($u->fill === null ? '' : " {$u->fill->quantity}@{$u->fill->price} fee {$u->fill->fee} {$u->fill->liquidity->value}"),
            $this->venue->updatesSince(0),
        );
    }
}

function venueHarness(string ...$balances): VenueHarness
{
    $clock = new SimClock(CarbonImmutable::parse(VENUE_START, 'UTC'));
    $rules = new InstrumentRules(
        CarbonImmutable::parse('2026-01-01', 'UTC'),
        tickSize: Decimal::of('0.01'),
        stepSize: Decimal::of('0.001'),
        minQty: Decimal::of('0.001'),
        maxQty: Decimal::of('1000'),
        minNotional: Decimal::of('5'),
    );
    $venue = new SimulatedVenue(
        $clock,
        new FeeSchedule(maker: Decimal::of('0.0002'), taker: Decimal::of('0.0005')),
        new CostModel(version: 1, halfSpread: Decimal::of('0.0002'), impact: Decimal::of('0.1'), limitVolumeShare: Decimal::of('0.1')),
        ['BTC/USDT' => new RulesHistory(Symbol::parse('BTC/USDT'), [$rules])],
        array_values(array_map(function (string $balance): Money {
            [$amount, $asset] = explode(' ', $balance);

            return Money::of($amount, Asset::of($asset));
        }, $balances === [] ? ['10000 USDT'] : $balances)),
    );

    return new VenueHarness($venue, $clock);
}

function orderId(string $id): ClientOrderId
{
    return ClientOrderId::of($id);
}

function btc(): Symbol
{
    return Symbol::parse('BTC/USDT');
}

it('fills a market buy at the next open plus slippage, rounded up to the tick, with the taker fee in base', function (): void {
    $h = venueHarness();
    $h->minute('99', '101', '98', '100');

    $ack = $h->venue->submit(OrderRequest::market(orderId('e1'), btc(), Side::Buy, Decimal::of('1')));
    // participation 1 / 1000: slippage 0.0002 + 0.1 * 0.001 = 0.0003 of the open
    $h->minute('100', '102', '99', '101');

    expect($ack->accepted)->toBeTrue()
        ->and($h->updates())->toBe(['e1 new', 'e1 filled 1@100.03 fee 0.0005 BTC taker'])
        ->and($h->balance('BTC'))->toBe('0.9995/0')
        ->and($h->balance('USDT'))->toBe('9899.97/0')
        ->and((string) $h->venue->updatesSince(1)[0]->fill?->slippage)->toBe('0.03')
        ->and($h->venue->updatesSince(1)[0]->fill?->time->format('H:i'))->toBe('00:01')
        ->and($h->venue->updatesSince(1)[0]->fill?->id)->toBe('sim-1')
        ->and($h->venue->updatesSince(-3))->toHaveCount(2);
});

it('spends a quote amount on a market buy, rounding the quantity down to the lot step', function (): void {
    $h = venueHarness();
    $h->minute('99', '101', '98', '100');

    $h->venue->submit(OrderRequest::marketForQuote(orderId('e1'), btc(), Decimal::of('1000')));
    expect($h->balance('USDT'))->toBe('9000/1000');
    // estimated participation 10 / 1000: slippage 0.0012; 1000 / 100.12 = 9.98801..., 9.988 after the step
    $h->minute('100', '102', '99', '101');

    expect($h->updates()[1])->toBe('e1 filled 9.988@100.12 fee 0.004994 BTC taker')
        ->and($h->balance('USDT'))->toBe('9000.00144/0');
});

it('fills a market sell at the next open minus slippage, rounded down, with the fee in quote', function (): void {
    $h = venueHarness('2 BTC');
    $h->minute('99', '101', '98', '100');

    $h->venue->submit(OrderRequest::market(orderId('x1'), btc(), Side::Sell, Decimal::of('2')));

    expect($h->balance('BTC'))->toBe('0/2');

    // participation 2 / 1000: slippage 0.0004, 100 * 0.9996 = 99.96
    $h->minute('100', '102', '99', '101');

    expect($h->updates()[1])->toBe('x1 filled 2@99.96 fee 0.09996 USDT taker')
        ->and($h->balance('BTC'))->toBe('0/0')
        ->and($h->balance('USDT'))->toBe('199.82004/0');
});

it('does not fill a limit that is only touched, and fills it at the limit when traded through by a tick', function (): void {
    $h = venueHarness('1 BTC');
    $h->minute('99', '101', '98', '100');
    $h->venue->submit(OrderRequest::limit(orderId('t1'), btc(), Side::Sell, Decimal::of('1'), Price::of('105')));

    $h->minute('100', '105', '99', '104');
    expect($h->updates())->toBe(['t1 new']);

    $h->minute('104', '105.01', '103', '104');
    expect($h->updates()[1])->toBe('t1 filled 1@105 fee 0.021 USDT maker')
        ->and($h->balance('USDT'))->toBe('104.979/0');
});

it('fills a resting buy limit only below it', function (): void {
    $h = venueHarness();
    $h->minute('99', '101', '98', '100');
    $h->venue->submit(OrderRequest::limit(orderId('b1'), btc(), Side::Buy, Decimal::of('1'), Price::of('95')));

    expect($h->balance('USDT'))->toBe('9905/95');

    $h->minute('100', '101', '95', '96');
    expect($h->updates())->toBe(['b1 new']);
    $h->minute('96', '97', '94.99', '95');

    expect($h->updates())->toBe(['b1 new', 'b1 filled 1@95 fee 0.0002 BTC maker'])
        ->and($h->balance('USDT'))->toBe('9905/0')
        ->and($h->balance('BTC'))->toBe('0.9998/0');
});

it('fills at most a tenth of the minute volume, leaving the rest working', function (): void {
    $h = venueHarness('5 BTC');
    $h->minute('99', '101', '98', '100');
    $h->venue->submit(OrderRequest::limit(orderId('t1'), btc(), Side::Sell, Decimal::of('5'), Price::of('105')));

    $h->minute('104', '106', '103', '105', volume: '20');
    $h->minute('105', '106', '103', '105', volume: '35');

    expect($h->updates())->toBe([
        't1 new',
        't1 partially_filled 2@105 fee 0.042 USDT maker',
        't1 filled 3@105 fee 0.063 USDT maker',
    ]);
});

it('fills a limit that is marketable on arrival like a market order, never beyond its limit', function (Side $side, string $limit, string $expected): void {
    $h = venueHarness('10000 USDT', '1 BTC');
    $h->minute('99', '101', '98', '100');
    $h->venue->submit(OrderRequest::limit(orderId('l1'), btc(), $side, Decimal::of('1'), Price::of($limit)));
    $h->minute('100', '102', '99', '101');

    expect($h->updates())->toBe(['l1 new', $expected]);
})->with([
    'buy above the open' => [Side::Buy, '105', 'l1 filled 1@100.03 fee 0.0005 BTC taker'],
    'buy slipping past its limit' => [Side::Buy, '100.01', 'l1 filled 1@100.01 fee 0.0005 BTC taker'],
    'sell below the open' => [Side::Sell, '95', 'l1 filled 1@99.97 fee 0.049985 USDT taker'],
]);

it('triggers a stop on the minute range and fills it at the stop minus slippage', function (): void {
    $h = venueHarness('1 BTC');
    $h->minute('99', '101', '98', '100');
    $h->venue->submit(OrderRequest::stop(orderId('s1'), btc(), Side::Sell, Decimal::of('1'), Price::of('95')));

    $h->minute('100', '101', '95.01', '96');
    expect($h->updates())->toBe(['s1 new']);

    // 95 * (1 - 0.0003) = 94.9715, rounded down to 94.97
    $h->minute('96', '97', '94', '95');
    expect($h->updates()[1])->toBe('s1 filled 1@94.97 fee 0.047485 USDT taker')
        ->and((string) $h->venue->updatesSince(1)[0]->fill?->slippage)->toBe('0.03');
});

it('fills a stop at the open when the minute gaps through it', function (): void {
    $h = venueHarness('1 BTC');
    $h->minute('99', '101', '98', '100');
    $h->venue->submit(OrderRequest::stop(orderId('s1'), btc(), Side::Sell, Decimal::of('1'), Price::of('95')));

    // 90 * 0.9997 = 89.973, rounded down to 89.97
    $h->minute('90', '91', '89', '90');

    expect($h->updates()[1])->toBe('s1 filled 1@89.97 fee 0.044985 USDT taker');
});

it('fills the stop first when a minute reaches both legs of an OCO, and cancels the target', function (): void {
    $h = venueHarness('1 BTC');
    $h->minute('99', '101', '98', '100');
    $ack = $h->venue->submitOco(new OcoRequest(orderId('s1'), orderId('t1'), btc(), Side::Sell, Decimal::of('1'), Price::of('95'), Price::of('105')));

    expect($ack->accepted)->toBeTrue()
        ->and($h->balance('BTC'))->toBe('0/1');

    $h->minute('100', '110', '90', '100');

    expect($h->updates())->toBe(['s1 new', 't1 new', 's1 filled 1@94.97 fee 0.047485 USDT taker', 't1 cancelled'])
        ->and($h->balance('BTC'))->toBe('0/0')
        ->and($h->venue->openOrders())->toBe([]);
});

it('cancels the stop when the target of an OCO fills, even partly, and keeps the rest of the target working', function (): void {
    $h = venueHarness('5 BTC');
    $h->minute('99', '101', '98', '100');
    $h->venue->submitOco(new OcoRequest(orderId('s1'), orderId('t1'), btc(), Side::Sell, Decimal::of('5'), Price::of('95'), Price::of('105')));

    $h->minute('104', '106', '103', '105', volume: '20');

    expect($h->updates())->toBe(['s1 new', 't1 new', 't1 partially_filled 2@105 fee 0.042 USDT maker', 's1 cancelled'])
        ->and($h->balance('BTC'))->toBe('0/3')
        ->and(array_map(fn ($o): string => (string) $o->clientOrderId, $h->venue->openOrders()))->toBe(['t1']);

    $h->minute('100', '101', '80', '90');
    expect($h->updates())->toHaveCount(4);
});

it('locks funds for working orders and refuses what the free balance cannot cover', function (): void {
    $h = venueHarness('1000 USDT', '1 BTC');
    $h->minute('99', '101', '98', '100');

    $first = $h->venue->submit(OrderRequest::limit(orderId('b1'), btc(), Side::Buy, Decimal::of('9'), Price::of('100')));
    $second = $h->venue->submit(OrderRequest::limit(orderId('b2'), btc(), Side::Buy, Decimal::of('1.1'), Price::of('100')));
    $sell = $h->venue->submit(OrderRequest::limit(orderId('x1'), btc(), Side::Sell, Decimal::of('1.001'), Price::of('110')));

    expect($first->accepted)->toBeTrue()
        ->and($second->accepted)->toBeFalse()
        ->and($second->reason)->toBe('INSUFFICIENT_BALANCE')
        ->and($sell->reason)->toBe('INSUFFICIENT_BALANCE')
        ->and($h->balance('USDT'))->toBe('100/900')
        ->and($h->venue->cancel(orderId('b1')))->toBeTrue()
        ->and($h->balance('USDT'))->toBe('1000/0')
        ->and($h->venue->cancel(orderId('b1')))->toBeFalse()
        ->and($h->updates())->toBe(['b1 new', 'b1 cancelled']);
});

it('refuses orders that break the exchange filters', function (OrderRequest $request, string $reason): void {
    $h = venueHarness('10000 USDT', '10 BTC');
    $h->minute('99', '101', '98', '100');

    expect($h->venue->submit($request)->reason)->toBe($reason);
})->with([
    'quantity off the lot step' => [fn () => OrderRequest::limit(orderId('a'), btc(), Side::Buy, Decimal::of('1.0005'), Price::of('100')), 'LOT_SIZE'],
    'quantity below the minimum' => [fn () => OrderRequest::market(orderId('a'), btc(), Side::Sell, Decimal::of('0.0001')), 'LOT_SIZE'],
    'quantity above the maximum' => [fn () => OrderRequest::market(orderId('a'), btc(), Side::Buy, Decimal::of('1001')), 'LOT_SIZE'],
    'price off the tick' => [fn () => OrderRequest::limit(orderId('a'), btc(), Side::Buy, Decimal::of('1'), Price::of('100.005')), 'PRICE_FILTER'],
    'stop off the tick' => [fn () => OrderRequest::stop(orderId('a'), btc(), Side::Sell, Decimal::of('1'), Price::of('95.001')), 'PRICE_FILTER'],
    'limit notional too small' => [fn () => OrderRequest::limit(orderId('a'), btc(), Side::Buy, Decimal::of('0.04'), Price::of('100')), 'NOTIONAL'],
    'market notional too small' => [fn () => OrderRequest::market(orderId('a'), btc(), Side::Sell, Decimal::of('0.049')), 'NOTIONAL'],
    'quote amount too small' => [fn () => OrderRequest::marketForQuote(orderId('a'), btc(), Decimal::of('4.99')), 'NOTIONAL'],
    'unknown instrument' => [fn () => OrderRequest::market(orderId('a'), Symbol::parse('ETH/USDT'), Side::Buy, Decimal::of('1')), 'UNKNOWN_SYMBOL'],
]);

it('measures the notional of a limit or stop at its own price', function (OrderRequest $request): void {
    $h = venueHarness('10000 USDT', '10 BTC');
    $h->minute('99', '101', '98', '100');

    expect($h->venue->submit($request)->accepted)->toBeTrue();
})->with([
    'limit above the last close' => [fn () => OrderRequest::limit(orderId('a'), btc(), Side::Sell, Decimal::of('0.04'), Price::of('200'))],
    'stop above the last close' => [fn () => OrderRequest::stop(orderId('a'), btc(), Side::Buy, Decimal::of('0.04'), Price::of('150'))],
]);

it('refuses a client order id it has already seen', function (): void {
    $h = venueHarness();
    $h->minute('99', '101', '98', '100');
    $h->venue->submit(OrderRequest::market(orderId('e1'), btc(), Side::Buy, Decimal::of('1')));

    expect($h->venue->submit(OrderRequest::market(orderId('e1'), btc(), Side::Buy, Decimal::of('1')))->reason)->toBe('DUPLICATE_CLIENT_ORDER_ID')
        ->and($h->venue->submitOco(new OcoRequest(orderId('e1'), orderId('t9'), btc(), Side::Sell, Decimal::of('1'), Price::of('95'), Price::of('105')))->reason)->toBe('DUPLICATE_CLIENT_ORDER_ID');
});

it('refuses a market order before it has seen a price', function (): void {
    $h = venueHarness();

    expect($h->venue->submit(OrderRequest::market(orderId('e1'), btc(), Side::Buy, Decimal::of('1')))->reason)->toBe('NO_PRICE');
});

it('fills what the balance covers when a market buy costs more than expected, and expires the rest', function (): void {
    $h = venueHarness('1000 USDT');
    $h->minute('99', '101', '98', '100');
    $h->venue->submit(OrderRequest::market(orderId('e1'), btc(), Side::Buy, Decimal::of('9.99')));

    // The next minute opens far higher: slippage 0.0002 + 0.1 * 0.00999 = 0.001199, 120 * 1.001199 = 120.14388,
    // rounded up to 120.15; the 1000 USDT that were free and locked buy 8.322 after the lot step.
    $h->minute('120', '121', '119', '120', volume: '1000');

    expect($h->updates()[1])->toBe('e1 expired 8.322@120.15 fee 0.004161 BTC taker')
        ->and($h->balance('USDT'))->toBe('0.1117/0');
});

it('makes a minute without volume costly for a market order and impossible for a limit', function (): void {
    $h = venueHarness('2 BTC');
    $h->minute('99', '101', '98', '100');
    $h->venue->submit(OrderRequest::market(orderId('x1'), btc(), Side::Sell, Decimal::of('1')));
    $h->venue->submit(OrderRequest::limit(orderId('t1'), btc(), Side::Sell, Decimal::of('1'), Price::of('101')));

    // participation is capped at 1: slippage 0.0002 + 0.1 = 0.1002
    $h->minute('100', '110', '99', '100', volume: '0');

    expect($h->updates())->toBe(['x1 new', 't1 new', 'x1 filled 1@89.98 fee 0.04499 USDT taker']);
});

it('describes its working orders and reports updates after a cursor', function (): void {
    $h = venueHarness('1 BTC');
    $h->minute('99', '101', '98', '100');
    $h->venue->submit(OrderRequest::limit(orderId('t1'), btc(), Side::Sell, Decimal::of('0.5'), Price::of('105')));
    $h->venue->submit(OrderRequest::stop(orderId('s1'), btc(), Side::Sell, Decimal::of('0.5'), Price::of('95')));

    [$target, $stop] = $h->venue->openOrders();

    expect((string) $target->clientOrderId)->toBe('t1')
        ->and($target->status)->toBe(OrderStatus::New)
        ->and((string) $target->price)->toBe('105')
        ->and((string) $stop->stopPrice)->toBe('95')
        ->and((string) $stop->quantity)->toBe('0.5')
        ->and((string) $stop->filledQuantity)->toBe('0')
        ->and(array_map(fn (OrderUpdate $u): int => $u->sequence, $h->venue->updatesSince(1)))->toBe([2])
        ->and($h->venue->updatesSince(2))->toBe([]);
});

it('fills a buy stop when the price rises to it', function (): void {
    $h = venueHarness();
    $h->minute('99', '101', '98', '100');
    $h->venue->submit(OrderRequest::stop(orderId('s1'), btc(), Side::Buy, Decimal::of('1'), Price::of('105')));
    expect($h->balance('USDT'))->toBe('9895/105');

    $h->minute('100', '104.99', '99', '104');
    expect($h->updates())->toBe(['s1 new']);
    $h->minute('104', '106', '103', '105');

    // 105 * 1.0003 = 105.0315, rounded up to 105.04
    expect($h->updates())->toBe(['s1 new', 's1 filled 1@105.04 fee 0.0005 BTC taker'])
        ->and(Liquidity::Taker->value)->toBe('taker');
});

it('releases what a partly filled buy limit no longer needs', function (): void {
    $h = venueHarness('1000 USDT');
    $h->minute('99', '101', '98', '100');
    $h->venue->submit(OrderRequest::limit(orderId('b1'), btc(), Side::Buy, Decimal::of('5'), Price::of('95')));
    expect($h->balance('USDT'))->toBe('525/475');

    $h->minute('96', '97', '94', '95', volume: '20');
    expect($h->balance('USDT'))->toBe('525/285')
        ->and($h->balance('BTC'))->toBe('1.9996/0');

    $h->minute('95', '96', '94', '95', volume: '100');
    expect($h->balance('USDT'))->toBe('525/0')
        ->and($h->updates())->toBe([
            'b1 new',
            'b1 partially_filled 2@95 fee 0.0004 BTC maker',
            'b1 filled 3@95 fee 0.0006 BTC maker',
        ]);
});

it('cancels both legs of an OCO when one is cancelled, releasing the lock', function (): void {
    $h = venueHarness('1 BTC');
    $h->minute('99', '101', '98', '100');
    $h->venue->submitOco(new OcoRequest(orderId('s1'), orderId('t1'), btc(), Side::Sell, Decimal::of('1'), Price::of('95'), Price::of('105')));

    expect($h->venue->cancel(orderId('t1')))->toBeTrue()
        ->and($h->updates())->toBe(['s1 new', 't1 new', 't1 cancelled', 's1 cancelled'])
        ->and($h->balance('BTC'))->toBe('1/0');
});

it('refuses an OCO the balance cannot cover or whose legs share an id', function (): void {
    $h = venueHarness('1 BTC');
    $h->minute('99', '101', '98', '100');

    expect($h->venue->submitOco(new OcoRequest(orderId('s1'), orderId('t1'), btc(), Side::Sell, Decimal::of('1.5'), Price::of('95'), Price::of('105')))->reason)->toBe('INSUFFICIENT_BALANCE')
        ->and($h->venue->submitOco(new OcoRequest(orderId('s1'), orderId('s1'), btc(), Side::Sell, Decimal::of('1'), Price::of('95'), Price::of('105')))->reason)->toBe('DUPLICATE_CLIENT_ORDER_ID')
        ->and($h->venue->submitOco(new OcoRequest(orderId('s1'), orderId('t1'), btc(), Side::Sell, Decimal::of('1'), Price::of('95'), Price::of('105.001')))->reason)->toBe('PRICE_FILTER')
        ->and($h->updates())->toBe([]);
});

it('expires a market buy the balance cannot cover at all', function (): void {
    $h = venueHarness('1000 USDT');
    $h->minute('99', '101', '98', '100');
    $h->venue->submit(OrderRequest::market(orderId('e1'), btc(), Side::Buy, Decimal::of('9')));
    $h->minute('2000000', '2000001', '1999999', '2000000');

    expect($h->updates())->toBe(['e1 new', 'e1 expired'])
        ->and($h->balance('USDT'))->toBe('1000/0');
});

it('matches only the orders of the minute\'s instrument', function (): void {
    $clock = new SimClock(CarbonImmutable::parse(VENUE_START, 'UTC'));
    $rules = fn (string $symbol): RulesHistory => new RulesHistory(Symbol::parse($symbol), [new InstrumentRules(
        CarbonImmutable::parse('2026-01-01', 'UTC'), Decimal::of('0.01'), Decimal::of('0.001'), Decimal::of('0.001'), null, null,
    )]);
    $venue = new SimulatedVenue(
        $clock,
        new FeeSchedule(Decimal::of('0.001'), Decimal::of('0.001')),
        new CostModel(1, Decimal::zero(), Decimal::zero(), Decimal::of('0.1')),
        ['BTC/USDT' => $rules('BTC/USDT'), 'ETH/USDT' => $rules('ETH/USDT')],
        [Money::of('1', Asset::of('ETH')), Money::of('1', Asset::of('BTC')), Money::of('5', Asset::of('USDT'))],
    );
    $minute = fn (int $at, string $price): Candle => Candle::fromStrings(
        CarbonImmutable::parse(VENUE_START, 'UTC')->addMinutes($at), Timeframe::OneMinute, $price, $price, $price, $price, '1000', '0', 1, '0',
    );
    $venue->match(Symbol::parse('ETH/USDT'), $minute(0, '2000'));
    $venue->submit(OrderRequest::market(ClientOrderId::of('eth'), Symbol::parse('ETH/USDT'), Side::Sell, Decimal::of('1')));
    $venue->match(Symbol::parse('BTC/USDT'), $minute(1, '60000'));

    expect($venue->updatesSince(0))->toHaveCount(1)
        ->and(array_keys($venue->balances()))->toBe(['BTC', 'ETH', 'USDT']);

    $venue->match(Symbol::parse('ETH/USDT'), $minute(1, '2010'));

    expect((string) $venue->updatesSince(1)[0]->fill?->price)->toBe('2010')
        ->and((string) $venue->balances()['USDT']->free)->toBe('2012.99');
});

it('refuses cost and fee parameters that are negative', function (callable $build): void {
    $build();
})->with([
    'half spread' => [fn () => new CostModel(1, Decimal::of('-0.1'), Decimal::zero(), Decimal::zero())],
    'impact' => [fn () => new CostModel(1, Decimal::zero(), Decimal::of('-0.1'), Decimal::zero())],
    'volume share' => [fn () => new CostModel(1, Decimal::zero(), Decimal::zero(), Decimal::of('-0.1'))],
    'maker rate' => [fn () => new FeeSchedule(Decimal::of('-0.001'), Decimal::zero())],
    'taker rate' => [fn () => new FeeSchedule(Decimal::zero(), Decimal::of('-0.001'))],
])->throws(InvalidArgumentException::class);
