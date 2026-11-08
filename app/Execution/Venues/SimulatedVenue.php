<?php

declare(strict_types=1);

namespace App\Execution\Venues;

use App\Execution\Contracts\Venue;
use App\Execution\Data\Balance;
use App\Execution\Data\ClientOrderId;
use App\Execution\Data\FeeSchedule;
use App\Execution\Data\Liquidity;
use App\Execution\Data\OcoRequest;
use App\Execution\Data\OrderAck;
use App\Execution\Data\OrderRequest;
use App\Execution\Data\OrderStatus;
use App\Execution\Data\OrderType;
use App\Execution\Data\OrderUpdate;
use App\Execution\Data\VenueFill;
use App\Market\Data\Candle;
use App\Market\Data\InstrumentRules;
use App\Market\Data\RulesHistory;
use App\Support\Asset;
use App\Support\Clock\Clock;
use App\Support\Number\Decimal;
use App\Support\Number\Money;
use App\Support\Number\Price;
use App\Support\Side;
use App\Support\Symbol;
use LogicException;

/**
 * An exchange made of our own code (D-41). It keeps balances and orders, locks funds for working orders, refuses
 * what the filters or the balance do not allow, and matches its orders against each 1-minute candle it is fed with
 * the rules of docs/simulation.md, section 3 (D-36, D-37). It never reads the engine's state.
 *
 * Within one minute, market orders fill first (at the open), then stops, then limits, each group in submission
 * order: so when a minute reaches both legs of an OCO, the stop fills and the target is cancelled.
 */
final class SimulatedVenue implements Venue
{
    private const int SCALE = 18;

    /** @var array<string, Decimal> */
    private array $free = [];

    /** @var array<string, Decimal> */
    private array $locked = [];

    /** @var array<string, SimulatedOrder> by client order id, in submission order */
    private array $orders = [];

    /** @var list<OrderUpdate> */
    private array $updates = [];

    /** @var array<string, Candle> the last minute seen, by symbol */
    private array $lastMinute = [];

    private int $fills = 0;

    /**
     * @param  array<string, RulesHistory>  $rules  by unified symbol
     * @param  list<Money>  $balances
     */
    public function __construct(
        private readonly Clock $clock,
        private readonly FeeSchedule $fees,
        private readonly CostModel $costs,
        private readonly array $rules,
        array $balances,
    ) {
        foreach ($balances as $balance) {
            $this->credit((string) $balance->asset, $balance->amount);
        }
    }

    public function submit(OrderRequest $request): OrderAck
    {
        $refusal = $this->refusal($request);

        if ($refusal !== null) {
            return OrderAck::rejected($request->clientOrderId, $refusal);
        }

        [$asset, $amount] = $this->lockFor($request);

        if ($amount->isGreaterThan($this->free[$asset] ?? Decimal::zero())) {
            return OrderAck::rejected($request->clientOrderId, 'INSUFFICIENT_BALANCE');
        }

        $this->accept(new SimulatedOrder($request, $asset, $amount));

        return OrderAck::accepted($request->clientOrderId);
    }

    public function submitOco(OcoRequest $request): OrderAck
    {
        $stop = $request->stopLeg();
        $target = $request->targetLeg();
        $refusal = $request->stopId->equals($request->targetId)
            ? 'DUPLICATE_CLIENT_ORDER_ID'
            : $this->refusal($stop) ?? $this->refusal($target);

        if ($refusal !== null) {
            return OrderAck::rejected($request->stopId, $refusal);
        }

        // Both legs sell (or buy) the same quantity, so the list locks it once, on the stop leg.
        [$asset, $amount] = $this->lockFor($stop);

        if ($amount->isGreaterThan($this->free[$asset] ?? Decimal::zero())) {
            return OrderAck::rejected($request->stopId, 'INSUFFICIENT_BALANCE');
        }

        $this->accept(new SimulatedOrder($stop, $asset, $amount, $request->targetId));
        $this->accept(new SimulatedOrder($target, $asset, Decimal::zero(), $request->stopId));

        return OrderAck::accepted($request->stopId);
    }

    public function cancel(ClientOrderId $id): bool
    {
        $order = $this->orders[$id->value] ?? null;

        if ($order === null || ! $order->status->isWorking()) {
            return false;
        }

        $this->finish($order, OrderStatus::Cancelled);
        $this->cancelPartner($order);

        return true;
    }

    public function updatesSince(int $sequence): array
    {
        return array_slice($this->updates, max(0, $sequence));
    }

    public function openOrders(): array
    {
        $open = [];

        foreach ($this->orders as $order) {
            if ($order->status->isWorking()) {
                $open[] = $order->snapshot();
            }
        }

        return $open;
    }

    public function balances(): array
    {
        $balances = [];

        foreach (array_keys($this->free + $this->locked) as $asset) {
            $code = (string) $asset;
            $balances[$code] = new Balance(Asset::of($code), $this->free[$code] ?? Decimal::zero(), $this->locked[$code] ?? Decimal::zero());
        }

        ksort($balances, SORT_STRING);

        return $balances;
    }

    /** Matches the working orders of $symbol against one closed 1-minute candle. */
    public function match(Symbol $symbol, Candle $minute): void
    {
        $working = array_filter(
            $this->orders,
            fn (SimulatedOrder $order): bool => $order->status->isWorking() && $order->request->symbol->equals($symbol),
        );

        foreach ([OrderType::Market, OrderType::StopMarket, OrderType::Limit] as $type) {
            foreach ($working as $order) {
                if ($order->request->type === $type && $order->status->isWorking()) {
                    $this->matchOrder($order, $minute);
                }
            }
        }

        foreach ($working as $order) {
            $order->arrived = true;
        }

        $this->lastMinute[(string) $symbol] = $minute;
    }

    private function matchOrder(SimulatedOrder $order, Candle $minute): void
    {
        $request = $order->request;
        $rules = $this->rulesFor($request->symbol);

        match ($request->type) {
            OrderType::Market => $this->fillMarket($order, $minute, $rules),
            OrderType::StopMarket => $this->fillStop($order, $minute, $rules),
            OrderType::Limit => $this->fillLimit($order, $minute, $rules),
        };
    }

    private function fillMarket(SimulatedOrder $order, Candle $minute, InstrumentRules $rules): void
    {
        $request = $order->request;
        $open = $minute->open->value;
        $wanted = $request->quantity ?? ($request->quoteQuantity ?? Decimal::zero())->dividedBy($open, self::SCALE);
        $price = $this->slipped($request->side, $open, $this->costs->slippage($wanted, $minute->volume), $rules);
        $quantity = $request->quantity
            ?? ($request->quoteQuantity ?? Decimal::zero())->dividedBy($price, self::SCALE)->roundDownToMultipleOf($rules->stepSize);
        $status = OrderStatus::Filled;

        if ($request->side === Side::Buy) {
            $available = ($this->free[$order->lockAsset] ?? Decimal::zero())->plus($order->locked);

            if ($quantity->times($price)->isGreaterThan($available)) {
                $quantity = $available->dividedBy($price, self::SCALE)->roundDownToMultipleOf($rules->stepSize);
                $status = OrderStatus::Expired;
            }
        }

        if ($quantity->isLessThan($rules->minQty) || $quantity->isZero()) {
            $this->finish($order, OrderStatus::Expired);

            return;
        }

        $this->fill($order, $quantity, Price::of($price), Liquidity::Taker, $open, $minute, $status);
    }

    private function fillStop(SimulatedOrder $order, Candle $minute, InstrumentRules $rules): void
    {
        $request = $order->request;
        $stop = ($request->stopPrice ?? throw new LogicException('A stop order without a stop price.'))->value;
        $selling = $request->side === Side::Sell;
        $triggered = $selling ? ! $minute->low->value->isGreaterThan($stop) : ! $minute->high->value->isLessThan($stop);

        if (! $triggered) {
            return;
        }

        $open = $minute->open->value;
        // A minute that opens beyond the stop fills at its open (a gap), never at the better stop price.
        $reference = ($selling ? $open->isLessThan($stop) : $open->isGreaterThan($stop)) ? $open : $stop;
        $quantity = $order->remaining();
        $price = $this->slipped($request->side, $reference, $this->costs->slippage($quantity, $minute->volume), $rules);

        $this->fill($order, $quantity, Price::of($price), Liquidity::Taker, $reference, $minute, OrderStatus::Filled);
    }

    private function fillLimit(SimulatedOrder $order, Candle $minute, InstrumentRules $rules): void
    {
        $request = $order->request;
        $limit = ($request->price ?? throw new LogicException('A limit order without a price.'))->value;
        $buying = $request->side === Side::Buy;
        $open = $minute->open->value;
        $remaining = $order->remaining();

        if (! $order->arrived && ($buying ? ! $open->isGreaterThan($limit) : ! $open->isLessThan($limit))) {
            // Marketable on arrival: it takes liquidity like a market order, but never beyond its limit.
            $slipped = $this->slipped($request->side, $open, $this->costs->slippage($remaining, $minute->volume), $rules);
            $price = $buying ? ($slipped->isGreaterThan($limit) ? $limit : $slipped) : ($slipped->isLessThan($limit) ? $limit : $slipped);
            $this->fill($order, $remaining, Price::of($price), Liquidity::Taker, $open, $minute, OrderStatus::Filled);

            return;
        }

        // Resting: a touch is not a fill; the price must trade through the limit by one tick.
        $through = $buying
            ? ! $minute->low->value->isGreaterThan($limit->minus($rules->tickSize))
            : ! $minute->high->value->isLessThan($limit->plus($rules->tickSize));

        if (! $through) {
            return;
        }

        $capacity = $this->costs->limitCapacity($minute->volume)->roundDownToMultipleOf($rules->stepSize);
        $quantity = $capacity->isLessThan($remaining) ? $capacity : $remaining;

        if (! $quantity->isPositive()) {
            return;
        }

        $status = $quantity->equals($remaining) ? OrderStatus::Filled : OrderStatus::PartiallyFilled;
        $this->fill($order, $quantity, Price::of($limit), Liquidity::Maker, $limit, $minute, $status);
    }

    /** Moves a price against the order by $slippage and rounds it to the tick, again against the order. */
    private function slipped(Side $side, Decimal $reference, Decimal $slippage, InstrumentRules $rules): Decimal
    {
        $one = Decimal::of(1);

        return $side === Side::Buy
            ? $reference->times($one->plus($slippage))->roundUpToMultipleOf($rules->tickSize)
            : $reference->times($one->minus($slippage))->roundDownToMultipleOf($rules->tickSize);
    }

    private function fill(
        SimulatedOrder $order,
        Decimal $quantity,
        Price $price,
        Liquidity $liquidity,
        Decimal $reference,
        Candle $minute,
        OrderStatus $status,
    ): void {
        $request = $order->request;
        $base = (string) $request->symbol->base;
        $quote = (string) $request->symbol->quote;
        $notional = $quantity->times($price->value);
        $rate = $this->fees->rate($liquidity);

        if ($request->side === Side::Buy) {
            $release = $status === OrderStatus::PartiallyFilled ? $quantity->times($request->price->value ?? $price->value) : $order->locked;
            $this->unlock($order, $release);
            $this->debit($quote, $notional);
            $fee = Money::of($quantity->times($rate), Asset::of($base));
            $this->credit($base, $quantity->minus($fee->amount));
        } else {
            $this->unlock($order, $quantity);
            $this->debit($base, $quantity);
            $fee = Money::of($notional->times($rate), Asset::of($quote));
            $this->credit($quote, $notional->minus($fee->amount));
        }

        $order->filled = $order->filled->plus($quantity);
        $this->fills++;

        $fill = new VenueFill(
            id: "sim-{$this->fills}",
            clientOrderId: $request->clientOrderId,
            symbol: $request->symbol,
            side: $request->side,
            quantity: $quantity,
            price: $price,
            fee: $fee,
            liquidity: $liquidity,
            slippage: $price->value->minus($reference)->abs()->times($quantity),
            time: $minute->openTime,
        );

        $this->finish($order, $status, $fill);
        $this->cancelPartner($order);
    }

    private function cancelPartner(SimulatedOrder $order): void
    {
        $partner = $order->ocoWith === null ? null : ($this->orders[$order->ocoWith->value] ?? null);

        if ($partner === null || ! $partner->status->isWorking()) {
            return;
        }

        // The list's lock follows the leg that is still working.
        if ($order->status->isWorking()) {
            $order->locked = $order->locked->plus($partner->locked);
            $partner->locked = Decimal::zero();
        }

        $this->finish($partner, OrderStatus::Cancelled);
    }

    private function finish(SimulatedOrder $order, OrderStatus $status, ?VenueFill $fill = null): void
    {
        $order->status = $status;

        if (! $status->isWorking()) {
            $this->unlock($order, $order->locked);
        }

        $this->record($order, $fill);
    }

    private function accept(SimulatedOrder $order): void
    {
        $this->free[$order->lockAsset] = ($this->free[$order->lockAsset] ?? Decimal::zero())->minus($order->locked);
        $this->locked[$order->lockAsset] = ($this->locked[$order->lockAsset] ?? Decimal::zero())->plus($order->locked);
        $this->orders[$order->request->clientOrderId->value] = $order;
        $this->record($order);
    }

    private function record(SimulatedOrder $order, ?VenueFill $fill = null): void
    {
        $this->updates[] = new OrderUpdate(
            count($this->updates) + 1,
            $order->request->clientOrderId,
            $order->status,
            $this->clock->now(),
            $fill,
        );
    }

    private function unlock(SimulatedOrder $order, Decimal $amount): void
    {
        $order->locked = $order->locked->minus($amount);
        $this->locked[$order->lockAsset] = ($this->locked[$order->lockAsset] ?? Decimal::zero())->minus($amount);
        $this->free[$order->lockAsset] = ($this->free[$order->lockAsset] ?? Decimal::zero())->plus($amount);
    }

    private function credit(string $asset, Decimal $amount): void
    {
        $this->free[$asset] = ($this->free[$asset] ?? Decimal::zero())->plus($amount);
    }

    private function debit(string $asset, Decimal $amount): void
    {
        $this->free[$asset] = ($this->free[$asset] ?? Decimal::zero())->minus($amount);
    }

    /** @return array{string, Decimal} the asset and amount an order locks while it works */
    private function lockFor(OrderRequest $request): array
    {
        if ($request->side === Side::Sell) {
            return [(string) $request->symbol->base, $request->quantity ?? Decimal::zero()];
        }

        $quote = (string) $request->symbol->quote;

        if ($request->quoteQuantity !== null) {
            return [$quote, $request->quoteQuantity];
        }

        $price = $request->price ?? $request->stopPrice ?? $this->lastClose($request->symbol)
            ?? throw new LogicException("No price to lock funds for order {$request->clientOrderId}.");

        return [$quote, ($request->quantity ?? Decimal::zero())->times($price->value)];
    }

    /** Why the exchange would refuse this order, or null. */
    private function refusal(OrderRequest $request): ?string
    {
        $history = $this->rules[(string) $request->symbol] ?? null;

        if ($history === null) {
            return 'UNKNOWN_SYMBOL';
        }

        if (isset($this->orders[$request->clientOrderId->value])) {
            return 'DUPLICATE_CLIENT_ORDER_ID';
        }

        $rules = $history->at($this->clock->now());
        $quantity = $request->quantity;

        if ($quantity !== null && (
            ! $quantity->equals($quantity->roundDownToMultipleOf($rules->stepSize))
            || $quantity->isLessThan($rules->minQty)
            || ($rules->maxQty !== null && $quantity->isGreaterThan($rules->maxQty))
        )) {
            return 'LOT_SIZE';
        }

        foreach ([$request->price, $request->stopPrice] as $price) {
            if ($price !== null && ! $price->value->equals($price->value->roundDownToMultipleOf($rules->tickSize))) {
                return 'PRICE_FILTER';
            }
        }

        $reference = $request->price ?? $request->stopPrice ?? $this->lastClose($request->symbol);

        if ($request->quoteQuantity === null && $reference === null) {
            return 'NO_PRICE';
        }

        $notional = $request->quoteQuantity ?? ($quantity ?? Decimal::zero())->times($reference === null ? Decimal::zero() : $reference->value);

        if ($rules->minNotional !== null && $notional->isLessThan($rules->minNotional)) {
            return 'NOTIONAL';
        }

        return null;
    }

    private function lastClose(Symbol $symbol): ?Price
    {
        return ($this->lastMinute[(string) $symbol] ?? null)?->close;
    }

    private function rulesFor(Symbol $symbol): InstrumentRules
    {
        return ($this->rules[(string) $symbol] ?? throw new LogicException("No rules for {$symbol}."))->at($this->clock->now());
    }
}
