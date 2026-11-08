<?php

declare(strict_types=1);

use App\Execution\Data\ClientOrderId;
use App\Execution\Data\FeeSchedule;
use App\Execution\Data\OrderAck;
use App\Execution\Data\OrderRole;
use App\Execution\Data\OrderStatus;
use App\Execution\Data\OrderType;
use App\Execution\Data\OrderUpdate;
use App\Execution\Data\TrackedOrder;
use App\Execution\OrderBook;
use App\Execution\OrderManager;
use App\Execution\ReconciliationMismatch;
use App\Execution\Venues\CostModel;
use App\Execution\Venues\SimulatedVenue;
use App\Market\Data\Candle;
use App\Market\Data\InstrumentRules;
use App\Market\Data\RulesHistory;
use App\Portfolio\Data\Fill;
use App\Portfolio\Data\Position;
use App\Risk\Data\Verdict;
use App\Support\Asset;
use App\Support\Clock\SimClock;
use App\Support\Number\Decimal;
use App\Support\Number\Money;
use App\Support\Number\Price;
use App\Support\Side;
use App\Support\Symbol;
use App\Support\Timeframe;
use Carbon\CarbonImmutable;

mutates(OrderManager::class, OrderBook::class);

final class ManagerHarness
{
    public function __construct(public SimulatedVenue $venue, public OrderBook $book, public OrderManager $manager, public InstrumentRules $rules) {}

    public static function make(string $baseHeld = '10'): self
    {
        $clock = new SimClock(CarbonImmutable::parse('2026-10-01 00:01:00', 'UTC'));
        $rules = new InstrumentRules(CarbonImmutable::parse('2026-01-01', 'UTC'), Decimal::of('0.01'), Decimal::of('0.001'), Decimal::of('0.001'), null, Decimal::of('5'));
        $venue = new SimulatedVenue(
            $clock,
            new FeeSchedule(Decimal::zero(), Decimal::zero()),
            new CostModel(1, Decimal::zero(), Decimal::zero(), Decimal::of('0.1')),
            ['BTC/USDT' => new RulesHistory(Symbol::parse('BTC/USDT'), [$rules])],
            [Money::of($baseHeld, Asset::of('BTC')), Money::of('10000', Asset::of('USDT'))],
        );
        $venue->match(Symbol::parse('BTC/USDT'), Candle::fromStrings(
            CarbonImmutable::parse('2026-10-01 00:00:00', 'UTC'), Timeframe::OneMinute, '100', '100', '100', '100', '1000', '0', 1, '0',
        ));
        $book = new OrderBook;

        return new self($venue, $book, new OrderManager($venue, $book), $rules);
    }

    /** Gives strategy k1 a plan with a stop and an optional target, without leaving an entry working. */
    public function plan(?string $target = '110'): void
    {
        $ack = $this->manager->enter('k1', btcUsdt(), Verdict::quantity(Decimal::of('1'), null, []), Price::of('95'), $target === null ? null : Price::of($target), managerTime());
        $this->manager->cancelAll('k1', btcUsdt());
        expect($ack->accepted)->toBeTrue();
    }

    /** @return 'kept'|'placed'|'unprotected'|'dust'|'busy'|OrderAck */
    public function protect(Position $position, int $minute = 1): string|OrderAck
    {
        return $this->manager->protect($position, $this->rules, Price::of('100'), managerTime($minute));
    }

    /** @return list<string> */
    public function open(): array
    {
        return array_map(fn ($o): string => "{$o->type->value} {$o->quantity} ".($o->stopPrice ?? $o->price), $this->venue->openOrders());
    }
}

function btcUsdt(): Symbol
{
    return Symbol::parse('BTC/USDT');
}

function managerTime(int $minute = 1): CarbonImmutable
{
    return CarbonImmutable::parse('2026-10-01 00:00:00', 'UTC')->addMinutes($minute);
}

function heldPosition(string $quantity, string $strategy = 'k1'): Position
{
    return Position::open(new Fill('f', managerTime(), $strategy, btcUsdt(), Side::Buy, Decimal::of($quantity), Price::of('100'), Money::of('0', Asset::of('BTC')), Decimal::zero(), Decimal::zero()))
        ->withBuy(new Fill('f', managerTime(), $strategy, btcUsdt(), Side::Buy, Decimal::of($quantity), Price::of('100'), Money::of('0', Asset::of('BTC')), Decimal::zero(), Decimal::zero()), Decimal::of($quantity), Decimal::zero());
}

it('names orders after the strategy, the event time and their role, counting within an event', function (): void {
    $h = ManagerHarness::make();
    $time = base_convert((string) managerTime()->getTimestamp(), 10, 36);
    $h->plan();
    $h->protect(heldPosition('1.0004'));
    $exit = $h->manager->exit(heldPosition('1.0004'), OrderRole::Exit, $h->rules, Price::of('100'), managerTime(2));
    $ids = array_map(fn (OrderUpdate $u): string => $u->clientOrderId->value, $h->venue->updatesSince(0));

    expect(array_values(array_unique($ids)))->toBe(["k1-{$time}-e1", "k1-{$time}-s2", "k1-{$time}-t3", 'k1-'.base_convert((string) managerTime(2)->getTimestamp(), 10, 36).'-x1'])
        ->and($exit?->accepted)->toBeTrue();
});

it('places the planned stop and target once, rounded down to the lot step, and then keeps them', function (): void {
    $h = ManagerHarness::make();
    $position = heldPosition('1.0004');

    expect($h->protect($position))->toBe('unprotected');

    $h->plan();

    expect($h->protect($position))->toBe('placed')
        ->and($h->open())->toBe(['stop_market 1 95', 'limit 1 110'])
        ->and($h->protect($position))->toBe('kept')
        ->and($h->open())->toHaveCount(2);
});

it('replaces the protection when it no longer covers the position', function (): void {
    $h = ManagerHarness::make();
    $h->plan();
    $h->protect(heldPosition('1'));

    expect($h->protect(heldPosition('0.6'), 2))->toBe('placed')
        ->and($h->open())->toBe(['stop_market 0.6 95', 'limit 0.6 110']);
});

it('protects with a stop alone when no target was planned, and replaces it when the stop moves', function (): void {
    $h = ManagerHarness::make();
    $h->plan(null);

    expect($h->protect(heldPosition('1')))->toBe('placed')
        ->and($h->open())->toBe(['stop_market 1 95'])
        ->and($h->protect(heldPosition('1')))->toBe('kept');

    $h->manager->moveStop('k1', btcUsdt(), Price::of('97.004'));

    expect($h->open())->toBe([])
        ->and($h->protect(heldPosition('1'), 2))->toBe('placed')
        ->and($h->open())->toBe(['stop_market 1 97.01']);
});

it('creates a stop-only plan when a stop is moved without one', function (): void {
    $h = ManagerHarness::make();
    $h->manager->moveStop('k1', btcUsdt(), Price::of('96'));

    expect((string) $h->manager->plan('k1', btcUsdt())?->stop)->toBe('96')
        ->and($h->manager->plan('k1', btcUsdt())?->target)->toBeNull();
});

it('leaves a position alone while an entry or exit order works for it', function (): void {
    $h = ManagerHarness::make();
    $h->plan();
    $h->manager->exit(heldPosition('1'), OrderRole::Exit, $h->rules, Price::of('100'), managerTime());

    expect($h->protect(heldPosition('1')))->toBe('busy')
        ->and($h->open())->toBe(['market 1 ']);
});

it('calls a remainder dust when the exchange would refuse to sell it, and cancels its protection', function (string $quantity): void {
    $h = ManagerHarness::make();
    $h->plan();
    $h->protect(heldPosition('1'));

    expect($h->protect(heldPosition($quantity), 2))->toBe('dust')
        ->and($h->open())->toBe([])
        ->and($h->manager->exit(heldPosition($quantity), OrderRole::Exit, $h->rules, Price::of('100'), managerTime(2)))->toBeNull();
})->with([
    'below the lot step' => ['0.0009'],
    'below the minimum notional' => ['0.049'],
]);

it('returns the venue\'s refusal when a protection cannot be placed', function (?string $target): void {
    $h = ManagerHarness::make(baseHeld: '0.5');
    $h->plan($target);
    $outcome = $h->protect(heldPosition('1'));

    expect($outcome)->toBeInstanceOf(OrderAck::class)
        ->and($outcome instanceof OrderAck ? $outcome->reason : null)->toBe('INSUFFICIENT_BALANCE')
        ->and($h->book->working())->toBe([]);
})->with([
    'an OCO' => ['110'],
    'a stop alone' => [null],
]);

it('marks a cancelled OCO leg and its partner as cancelled, and nothing else', function (): void {
    $book = new OrderBook;
    $stop = ClientOrderId::of('s');
    $target = ClientOrderId::of('t');
    $book->track(new TrackedOrder($stop, 'k1', btcUsdt(), OrderRole::Stop, Decimal::of('1'), $target));
    $book->track(new TrackedOrder($target, 'k1', btcUsdt(), OrderRole::Target, Decimal::of('1'), $stop));
    $book->track(new TrackedOrder(ClientOrderId::of('other'), 'k2', Symbol::parse('ETH/USDT'), OrderRole::Stop, Decimal::of('1')));
    $book->track($done = new TrackedOrder(ClientOrderId::of('done'), 'k1', btcUsdt(), OrderRole::Exit, Decimal::of('1')));
    $done->status = OrderStatus::Filled;

    $book->cancelled($target);
    $book->cancelled(ClientOrderId::of('unknown'));
    $book->cancelled(ClientOrderId::of('done'));

    expect(array_map(fn (TrackedOrder $o): string => $o->id->value, $book->working()))->toBe(['other'])
        ->and($done->status)->toBe(OrderStatus::Filled)
        ->and($book->workingFor('k2', Symbol::parse('ETH/USDT')))->toHaveCount(1)
        ->and($book->workingFor('k1', Symbol::parse('ETH/USDT')))->toBe([])
        ->and($book->workingFor('k2', btcUsdt()))->toBe([]);
});

it('lists working orders as a list and refuses updates for orders it never placed', function (): void {
    $book = new OrderBook;
    $book->track($first = new TrackedOrder(ClientOrderId::of('a'), 'k1', btcUsdt(), OrderRole::Entry, Decimal::of('1')));
    $book->track(new TrackedOrder(ClientOrderId::of('b'), 'k1', btcUsdt(), OrderRole::Stop, Decimal::of('1')));
    $first->status = OrderStatus::Filled;

    expect(array_keys($book->working()))->toBe([0])
        ->and(array_keys($book->workingFor('k1', btcUsdt())))->toBe([0]);

    $book->apply(new OrderUpdate(1, ClientOrderId::of('nobody'), OrderStatus::New, managerTime()));
})->throws(ReconciliationMismatch::class, 'nobody');

it('keeps the type of an order it tracks', function (): void {
    expect(OrderType::Market->value)->toBe('market')
        ->and(OrderRole::Target->isProtective())->toBeTrue()
        ->and(OrderRole::Exit->isProtective())->toBeFalse();
});

it('replaces the protection when either leg is gone', function (OrderRole $missing): void {
    $h = ManagerHarness::make();
    $h->plan();
    $h->protect(heldPosition('1'));

    foreach ($h->book->working() as $order) {
        if ($order->role === $missing) {
            $order->status = OrderStatus::Cancelled;
        }
    }

    expect($h->protect(heldPosition('1'), 2))->toBe('placed')
        ->and($h->open())->toBe(['stop_market 1 95', 'limit 1 110']);
})->with([
    'the stop' => [OrderRole::Stop],
    'the target' => [OrderRole::Target],
]);

it('calls a quantity below the exchange minimum dust even when its notional is enough', function (): void {
    $h = ManagerHarness::make();
    $h->plan();
    $rules = new InstrumentRules(CarbonImmutable::parse('2026-01-01', 'UTC'), Decimal::of('0.01'), Decimal::of('0.001'), Decimal::of('0.01'), null, null);

    expect($h->manager->protect(heldPosition('0.005'), $rules, Price::of('100000'), managerTime()))->toBe('dust');
});

it('keeps list keys when filtering working orders by strategy', function (): void {
    $book = new OrderBook;
    $book->track(new TrackedOrder(ClientOrderId::of('a'), 'k1', btcUsdt(), OrderRole::Stop, Decimal::of('1')));
    $book->track(new TrackedOrder(ClientOrderId::of('b'), 'k2', btcUsdt(), OrderRole::Stop, Decimal::of('1')));

    expect(array_keys($book->workingFor('k2', btcUsdt())))->toBe([0]);
});
