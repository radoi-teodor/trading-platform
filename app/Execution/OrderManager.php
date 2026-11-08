<?php

declare(strict_types=1);

namespace App\Execution;

use App\Execution\Contracts\Venue;
use App\Execution\Data\ClientOrderId;
use App\Execution\Data\OcoRequest;
use App\Execution\Data\OrderAck;
use App\Execution\Data\OrderRequest;
use App\Execution\Data\OrderRole;
use App\Execution\Data\ProtectionPlan;
use App\Execution\Data\TrackedOrder;
use App\Market\Data\InstrumentRules;
use App\Portfolio\Data\PerpPosition;
use App\Portfolio\Data\Position;
use App\Risk\Data\Verdict;
use App\Support\Number\Decimal;
use App\Support\Number\Price;
use App\Support\Side;
use App\Support\Symbol;
use Carbon\CarbonImmutable;

/**
 * Turns approved intents into orders (docs/engine.md, section 4): market entries, the protective stop and target
 * for each position, exits and flattening. Client order ids derive from the strategy, the event time and the
 * order's role, so a retry cannot place an order twice. Quantities round down to the lot step; a stop and a
 * target of a long position round up to the tick (towards the market and away from it).
 */
final class OrderManager
{
    /** @var array<string, ProtectionPlan> by strategy and symbol */
    private array $plans = [];

    private ?CarbonImmutable $eventTime = null;

    private int $sequence = 0;

    public function __construct(private readonly Venue $venue, private readonly OrderBook $book) {}

    public function enter(string $strategy, Symbol $symbol, Side $side, Verdict $verdict, ?Price $stop, ?Price $target, CarbonImmutable $time): OrderAck
    {
        $id = $this->nextId($strategy, OrderRole::Entry, $time);

        if ($symbol->isPerpetual()) {
            $this->venue->setLeverage($symbol, $verdict->leverage ?? 1);
        }

        $request = $verdict->quoteQuantity !== null
            ? OrderRequest::marketForQuote($id, $symbol, $verdict->quoteQuantity)
            : OrderRequest::market($id, $symbol, $side, $verdict->quantity ?? Decimal::zero());
        $ack = $this->submit($request, $strategy, OrderRole::Entry);

        if ($ack->accepted) {
            $this->plans[self::key($strategy, $symbol)] = new ProtectionPlan($stop, $target, $verdict->plannedRisk, $verdict->leverage, $side);
        }

        return $ack;
    }

    /** @return array{OrderAck, OrderAck|null} the spot leg's answer, and the perpetual leg's when the spot leg was accepted */
    public function enterCarry(string $strategy, Symbol $spot, Symbol $perpetual, Verdict $verdict, CarbonImmutable $time): array
    {
        $spotAck = $this->enter($strategy, $spot, Side::Buy, Verdict::quantity($verdict->quantity ?? Decimal::zero(), null, $verdict->adjustments), null, null, $time);

        return [$spotAck, $spotAck->accepted ? $this->enter($strategy, $perpetual, Side::Sell, $verdict, null, null, $time) : null];
    }

    /** Cancels the perpetual position's working orders, then closes it with a reduce-only market order (D-46). */
    public function exitPerpetual(PerpPosition $position, OrderRole $role, CarbonImmutable $time): OrderAck
    {
        $this->cancelAll($position->strategy, $position->symbol);
        $id = $this->nextId($position->strategy, $role, $time);
        $request = OrderRequest::market($id, $position->symbol, self::opposite($position->side), $position->quantity)->reduceOnly();

        return $this->submit($request, $position->strategy, $role);
    }

    /**
     * Keeps a reduce-only stop, and a reduce-only take-profit when one was planned, on the venue for a perpetual
     * position (D-46). Prices round towards the market for the stop and away from it for the target.
     *
     * @return 'kept'|'placed'|'unprotected'|'busy'|OrderAck
     */
    public function protectPerpetual(PerpPosition $position, InstrumentRules $rules, CarbonImmutable $time): string|OrderAck
    {
        $working = $this->book->workingFor($position->strategy, $position->symbol);

        foreach ($working as $order) {
            if (! $order->role->isProtective()) {
                return 'busy';
            }
        }

        $plan = $this->plan($position->strategy, $position->symbol);

        if ($plan?->stop === null) {
            return 'unprotected';
        }

        if ($this->isProtected($working, $position->quantity, $plan)) {
            return 'kept';
        }

        $this->cancelAll($position->strategy, $position->symbol);
        $exit = self::opposite($position->side);
        $round = fn (Price $price): Price => Price::of($position->side === Side::Buy
            ? $price->value->roundUpToMultipleOf($rules->tickSize)
            : $price->value->roundDownToMultipleOf($rules->tickSize));
        $stopId = $this->nextId($position->strategy, OrderRole::Stop, $time);
        $ack = $this->submit(OrderRequest::stop($stopId, $position->symbol, $exit, $position->quantity, $round($plan->stop))->reduceOnly(), $position->strategy, OrderRole::Stop);

        if (! $ack->accepted) {
            return $ack;
        }

        if ($plan->target !== null) {
            $targetId = $this->nextId($position->strategy, OrderRole::Target, $time);
            $ack = $this->submit(OrderRequest::takeProfit($targetId, $position->symbol, $exit, $position->quantity, $round($plan->target)), $position->strategy, OrderRole::Target);

            if (! $ack->accepted) {
                return $ack;
            }
        }

        return 'placed';
    }

    /**
     * Cancels the position's working orders, then sells what the lot step allows at market.
     *
     * @return OrderAck|null null when nothing can be sold: the remainder is dust
     */
    public function exit(Position $position, OrderRole $role, InstrumentRules $rules, Price $mark, CarbonImmutable $time): ?OrderAck
    {
        $this->cancelAll($position->strategy, $position->symbol);
        $quantity = $this->sellable($position, $rules, $mark);

        if ($quantity === null) {
            return null;
        }

        $id = $this->nextId($position->strategy, $role, $time);

        return $this->submit(OrderRequest::market($id, $position->symbol, Side::Sell, $quantity), $position->strategy, $role);
    }

    /**
     * Keeps exactly one protective stop, with its target when one was planned, on the venue for the position.
     *
     * @return 'kept'|'placed'|'unprotected'|'dust'|'busy'|OrderAck
     */
    public function protect(Position $position, InstrumentRules $rules, Price $mark, CarbonImmutable $time): string|OrderAck
    {
        $working = $this->book->workingFor($position->strategy, $position->symbol);

        foreach ($working as $order) {
            if (! $order->role->isProtective()) {
                return 'busy';
            }
        }

        $quantity = $this->sellable($position, $rules, $mark);

        if ($quantity === null) {
            $this->cancelAll($position->strategy, $position->symbol);

            return 'dust';
        }

        $plan = $this->plan($position->strategy, $position->symbol);

        if ($plan?->stop === null) {
            return 'unprotected';
        }

        if ($this->isProtected($working, $quantity, $plan)) {
            return 'kept';
        }

        $this->cancelAll($position->strategy, $position->symbol);
        $stop = Price::of($plan->stop->value->roundUpToMultipleOf($rules->tickSize));
        $stopId = $this->nextId($position->strategy, OrderRole::Stop, $time);

        if ($plan->target === null) {
            $ack = $this->submit(OrderRequest::stop($stopId, $position->symbol, Side::Sell, $quantity, $stop), $position->strategy, OrderRole::Stop);

            return $ack->accepted ? 'placed' : $ack;
        }

        $target = Price::of($plan->target->value->roundUpToMultipleOf($rules->tickSize));
        $targetId = $this->nextId($position->strategy, OrderRole::Target, $time);
        $ack = $this->venue->submitOco(new OcoRequest($stopId, $targetId, $position->symbol, Side::Sell, $quantity, $stop, $target));

        if ($ack->accepted) {
            $this->book->track(new TrackedOrder($stopId, $position->strategy, $position->symbol, OrderRole::Stop, $quantity, $targetId));
            $this->book->track(new TrackedOrder($targetId, $position->strategy, $position->symbol, OrderRole::Target, $quantity, $stopId));

            return 'placed';
        }

        return $ack;
    }

    /** Changes the planned stop and cancels the protective orders; protect() then places the new ones. */
    public function moveStop(string $strategy, Symbol $symbol, Price $stop): void
    {
        $key = self::key($strategy, $symbol);
        $this->plans[$key] = ($this->plans[$key] ?? new ProtectionPlan(null, null, null))->withStop($stop);
        $this->cancelAll($strategy, $symbol);
    }

    public function plan(string $strategy, Symbol $symbol): ?ProtectionPlan
    {
        return $this->plans[self::key($strategy, $symbol)] ?? null;
    }

    public function cancelAll(string $strategy, Symbol $symbol): void
    {
        foreach ($this->book->workingFor($strategy, $symbol) as $order) {
            $this->cancel($order);
        }
    }

    private function cancel(TrackedOrder $order): void
    {
        if ($order->status->isWorking() && $this->venue->cancel($order->id)) {
            $this->book->cancelled($order->id);
        }
    }

    /** The quantity a sell may carry: the position rounded down to the lot step, or null when the exchange would refuse it. */
    private function sellable(Position $position, InstrumentRules $rules, Price $mark): ?Decimal
    {
        $quantity = $position->quantity->roundDownToMultipleOf($rules->stepSize);
        $tooSmall = $quantity->isLessThan($rules->minQty) || ! $quantity->isPositive()
            || ($rules->minNotional !== null && $quantity->times($mark->value)->isLessThan($rules->minNotional));

        return $tooSmall ? null : $quantity;
    }

    /** @param list<TrackedOrder> $working */
    private function isProtected(array $working, Decimal $quantity, ProtectionPlan $plan): bool
    {
        $stop = $target = false;

        foreach ($working as $order) {
            $covers = $order->remaining()?->equals($quantity) ?? false;
            $stop = $stop || ($order->role === OrderRole::Stop && $covers);
            $target = $target || ($order->role === OrderRole::Target && $covers);
        }

        return $stop && ($plan->target === null || $target);
    }

    private function submit(OrderRequest $request, string $strategy, OrderRole $role): OrderAck
    {
        $ack = $this->venue->submit($request);

        if ($ack->accepted) {
            $this->book->track(new TrackedOrder($request->clientOrderId, $strategy, $request->symbol, $role, $request->quantity));
        }

        return $ack;
    }

    private function nextId(string $strategy, OrderRole $role, CarbonImmutable $time): ClientOrderId
    {
        if ($this->eventTime === null || ! $this->eventTime->equalTo($time)) {
            $this->eventTime = $time;
            $this->sequence = 0;
        }

        $this->sequence++;

        return ClientOrderId::of(sprintf('%s-%s-%s%d', $strategy, base_convert((string) $time->getTimestamp(), 10, 36), $role->value, $this->sequence));
    }

    private static function opposite(Side $side): Side
    {
        return $side === Side::Buy ? Side::Sell : Side::Buy;
    }

    private static function key(string $strategy, Symbol $symbol): string
    {
        return "{$strategy}\0{$symbol}";
    }
}
