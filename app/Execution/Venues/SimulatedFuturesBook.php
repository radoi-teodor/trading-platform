<?php

declare(strict_types=1);

namespace App\Execution\Venues;

use App\Execution\Data\ClientOrderId;
use App\Execution\Data\FeeSchedule;
use App\Execution\Data\Income;
use App\Execution\Data\IncomeKind;
use App\Execution\Data\Liquidity;
use App\Execution\Data\OrderAck;
use App\Execution\Data\OrderRequest;
use App\Execution\Data\OrderStatus;
use App\Execution\Data\OrderType;
use App\Execution\Data\OrderUpdate;
use App\Execution\Data\VenueFill;
use App\Execution\Data\VenueOrder;
use App\Execution\Data\VenuePosition;
use App\Market\Data\Candle;
use App\Market\Data\FundingRate;
use App\Market\Data\InstrumentRules;
use App\Support\Asset;
use App\Support\Clock\Clock;
use App\Support\Number\Decimal;
use App\Support\Number\Money;
use App\Support\Number\Price;
use App\Support\Perpetual;
use App\Support\Side;
use App\Support\Symbol;
use InvalidArgumentException;
use LogicException;

/**
 * The USD-M side of the simulated venue (docs/engine.md, section 8; docs/simulation.md, section 3): a wallet,
 * isolated positions in one-way mode, market, stop-market and take-profit-market orders, reduce-only, funding and
 * liquidation. Within a minute: a liquidation at the open first, then markets, stops and take-profits, each in
 * submission order, then a liquidation on the minute's adverse extreme (D-44 to D-47).
 */
final class SimulatedFuturesBook
{
    private const int SCALE = 18;

    private const string MARGIN_ASSET = 'USDT';

    /** Free USDT; working entries reserve part of it in $reserved. */
    private Decimal $wallet;

    private Decimal $reserved;

    /** @var array<string, SimulatedOrder> */
    private array $orders = [];

    /** @var array<string, SimulatedOrder> */
    private array $open = [];

    /** @var array<string, SimulatedPosition> by unified symbol */
    private array $positions = [];

    /** @var array<string, int> */
    private array $leverage = [];

    /** @var array<string, Price> */
    private array $lastClose = [];

    private int $fills = 0;

    private int $liquidations = 0;

    public function __construct(
        private readonly Clock $clock,
        private readonly FeeSchedule $fees,
        private readonly CostModel $costs,
        private readonly Decimal $maintenanceRate,
        Decimal $wallet,
        private readonly UpdateStream $stream,
    ) {
        $this->wallet = $wallet;
        $this->reserved = Decimal::zero();
    }

    public function has(ClientOrderId $id): bool
    {
        return isset($this->orders[$id->value]);
    }

    public function submit(OrderRequest $request, InstrumentRules $rules): OrderAck
    {
        $refusal = $this->refusal($request, $rules);

        if ($refusal !== null) {
            return OrderAck::rejected($request->clientOrderId, $refusal);
        }

        $reserve = Decimal::zero();

        if (! $request->reduceOnly) {
            $reference = ($request->stopPrice ?? $this->lastClose[(string) $request->symbol] ?? throw new LogicException('No price.'))->value;
            $notional = ($request->quantity ?? Decimal::zero())->times($reference);
            $reserve = $notional->dividedBy(Decimal::of($this->leverageOf($request->symbol)), self::SCALE)
                ->plus($notional->times($this->fees->taker));

            if ($reserve->isGreaterThan($this->wallet)) {
                return OrderAck::rejected($request->clientOrderId, 'INSUFFICIENT_MARGIN');
            }
        }

        $order = new SimulatedOrder($request, self::MARGIN_ASSET, $reserve);
        $this->wallet = $this->wallet->minus($reserve);
        $this->reserved = $this->reserved->plus($reserve);
        $this->orders[$request->clientOrderId->value] = $order;
        $this->open[$request->clientOrderId->value] = $order;
        $this->record($order);

        return OrderAck::accepted($request->clientOrderId);
    }

    public function cancel(ClientOrderId $id): bool
    {
        $order = $this->orders[$id->value] ?? null;

        if ($order === null || ! $order->status->isWorking()) {
            return false;
        }

        $this->finish($order, OrderStatus::Cancelled);

        return true;
    }

    public function setLeverage(Symbol $symbol, int $leverage): void
    {
        $position = $this->positions[(string) $symbol] ?? null;

        if ($leverage < 1 || ($position !== null && $position->leverage !== $leverage)) {
            throw new InvalidArgumentException("Cannot set the leverage of {$symbol} to {$leverage} while a position at {$position?->leverage}x is open.");
        }

        $this->leverage[(string) $symbol] = $leverage;
    }

    public function wallet(): Decimal
    {
        return $this->wallet->plus($this->reserved);
    }

    /** @return array<string, VenuePosition> */
    public function positions(): array
    {
        $positions = array_map(fn (SimulatedPosition $p): VenuePosition => $p->snapshot(), $this->positions);
        ksort($positions, SORT_STRING);

        return $positions;
    }

    /** @return list<VenueOrder> */
    public function openOrders(): array
    {
        return array_values(array_map(fn (SimulatedOrder $order): VenueOrder => $order->snapshot(), $this->open));
    }

    public function match(Symbol $symbol, Candle $minute, InstrumentRules $rules): void
    {
        $key = (string) $symbol;
        $this->liquidateIf($symbol, $minute, $minute->open->value, $minute->open->value);
        $working = array_filter($this->open, fn (SimulatedOrder $order): bool => $order->request->symbol->equals($symbol));

        foreach ([OrderType::Market, OrderType::StopMarket, OrderType::TakeProfitMarket] as $type) {
            foreach ($working as $order) {
                if ($order->request->type === $type && $order->status->isWorking()) {
                    $this->matchOrder($order, $minute, $rules);
                }
            }
        }

        $this->liquidateIf($symbol, $minute, $minute->low->value, $minute->high->value);
        $this->lastClose[$key] = $minute->close;
    }

    /** Funding at its time on the open position, valued at $mark; what the wallet cannot pay comes from the margin. */
    public function settleFunding(Symbol $symbol, FundingRate $rate, Price $mark): void
    {
        $position = $this->positions[(string) $symbol] ?? null;

        if ($position === null) {
            return;
        }

        $amount = Perpetual::funding($position->side, $position->quantity, $mark->value, $rate->rate);
        $wallet = $this->wallet->plus($amount);

        if ($wallet->isNegative()) {
            $position->margin = $position->margin->plus($wallet);
            $wallet = Decimal::zero();
        }

        $this->wallet = $wallet;
        $this->stream->push(new Income(
            $this->stream->next(), "funding-{$symbol}-{$rate->fundingTime->getTimestamp()}", IncomeKind::Funding, $symbol,
            $amount, null, $rate->fundingTime,
        ));
    }

    private function matchOrder(SimulatedOrder $order, Candle $minute, InstrumentRules $rules): void
    {
        $request = $order->request;
        $buying = $request->side === Side::Buy;
        $open = $minute->open->value;

        if ($request->type === OrderType::Market) {
            $this->fill($order, $open, $minute, $rules);

            return;
        }

        $trigger = ($request->stopPrice ?? throw new LogicException('A triggered order without a trigger.'))->value;
        // A stop buys on the way up and sells on the way down; a take-profit the reverse.
        $rising = ($request->type === OrderType::StopMarket) === $buying;
        $triggered = $rising ? ! $minute->high->value->isLessThan($trigger) : ! $minute->low->value->isGreaterThan($trigger);

        if (! $triggered) {
            return;
        }

        $gapped = $rising ? $open->isGreaterThan($trigger) : $open->isLessThan($trigger);
        $this->fill($order, $gapped ? $open : $trigger, $minute, $rules);
    }

    private function fill(SimulatedOrder $order, Decimal $reference, Candle $minute, InstrumentRules $rules): void
    {
        $request = $order->request;
        $key = (string) $request->symbol;
        $position = $this->positions[$key] ?? null;
        $quantity = $request->quantity ?? Decimal::zero();
        $adds = $position === null || $position->side === $request->side;

        if ($request->reduceOnly && ($position === null || $position->side === $request->side)) {
            $this->finish($order, OrderStatus::Expired);

            return;
        }

        if (! $request->reduceOnly && ! $adds) {
            // One strategy per perpetual (D-43): an order against the position is only ever reduce-only.
            $this->finish($order, OrderStatus::Expired);

            return;
        }

        if ($position !== null && ! $adds && $quantity->isGreaterThan($position->quantity)) {
            $quantity = $position->quantity;
        }

        $price = $this->slipped($request->side, $reference, $this->costs->slippage($quantity, $minute->volume), $rules);
        $status = OrderStatus::Filled;
        $this->release($order);

        if ($adds) {
            $leverage = $position->leverage ?? $this->leverageOf($request->symbol);
            $perUnit = $price->dividedBy(Decimal::of($leverage), self::SCALE)->plus($price->times($this->fees->taker));

            if ($quantity->times($perUnit)->isGreaterThan($this->wallet)) {
                $quantity = $this->wallet->dividedBy($perUnit, self::SCALE)->roundDownToMultipleOf($rules->stepSize);
                $status = OrderStatus::Expired;
            }

            if ($quantity->isLessThan($rules->minQty) || ! $quantity->isPositive()) {
                $this->finish($order, OrderStatus::Expired);

                return;
            }

            $fee = $quantity->times($price)->times($this->fees->taker);
            $this->wallet = $this->wallet->minus(Perpetual::initialMargin($quantity, $price, $leverage))->minus($fee);
            $this->positions[$key] = $position === null
                ? new SimulatedPosition($request->symbol, $request->side, $leverage, $quantity, $price, Perpetual::initialMargin($quantity, $price, $leverage))
                : $this->added($position, $quantity, $price);
        } else {
            /** @var SimulatedPosition $position */
            $fee = $quantity->times($price)->times($this->fees->taker);
            $released = Perpetual::released($position->margin, $quantity, $position->quantity);
            $realized = Perpetual::pnl($position->side, $quantity, $position->entryPrice, $price);
            $this->wallet = $this->wallet->plus($released)->plus($realized)->minus($fee);
            $position->margin = $position->margin->minus($released);
            $position->quantity = $position->quantity->minus($quantity);

            if ($position->quantity->isZero()) {
                unset($this->positions[$key]);
            }
        }

        $order->filled = $order->filled->plus($quantity);
        $this->fills++;
        $this->finish($order, $status, new VenueFill(
            id: "fut-{$this->fills}",
            clientOrderId: $request->clientOrderId,
            symbol: $request->symbol,
            side: $request->side,
            quantity: $quantity,
            price: Price::of($price),
            fee: Money::of($fee, Asset::of(self::MARGIN_ASSET)),
            liquidity: Liquidity::Taker,
            slippage: $price->minus($reference)->abs()->times($quantity),
            time: $minute->openTime,
        ));
    }

    private function added(SimulatedPosition $position, Decimal $quantity, Decimal $price): SimulatedPosition
    {
        $position->entryPrice = Perpetual::averageEntry($position->entryPrice, $position->quantity, $price, $quantity);
        $position->margin = $position->margin->plus(Perpetual::initialMargin($quantity, $price, $position->leverage));
        $position->quantity = $position->quantity->plus($quantity);

        return $position;
    }

    /** Liquidates the position on $symbol when the range [$low, $high] reaches its liquidation price (D-45). */
    private function liquidateIf(Symbol $symbol, Candle $minute, Decimal $low, Decimal $high): void
    {
        $position = $this->positions[(string) $symbol] ?? null;

        if ($position === null) {
            return;
        }

        $price = Perpetual::liquidationPrice($position->side, $position->quantity, $position->entryPrice, $position->margin, $this->maintenanceRate);
        $reached = $position->side === Side::Buy ? ! $low->isGreaterThan($price) : ! $high->isLessThan($price);

        if (! $reached) {
            return;
        }

        unset($this->positions[(string) $symbol]);
        $this->liquidations++;
        $this->stream->push(new Income(
            $this->stream->next(), "liquidation-{$this->liquidations}", IncomeKind::Liquidation, $symbol,
            $position->margin->negate(), Price::of($price), $minute->openTime,
        ));
    }

    private function slipped(Side $side, Decimal $reference, Decimal $slippage, InstrumentRules $rules): Decimal
    {
        $one = Decimal::of(1);

        return $side === Side::Buy
            ? $reference->times($one->plus($slippage))->roundUpToMultipleOf($rules->tickSize)
            : $reference->times($one->minus($slippage))->roundDownToMultipleOf($rules->tickSize);
    }

    private function refusal(OrderRequest $request, InstrumentRules $rules): ?string
    {
        if (! in_array($request->type, [OrderType::Market, OrderType::StopMarket, OrderType::TakeProfitMarket], true) || $request->quantity === null) {
            return 'UNSUPPORTED_ORDER_TYPE';
        }

        $quantity = $request->quantity;

        if (! $quantity->equals($quantity->roundDownToMultipleOf($rules->stepSize)) || $quantity->isLessThan($rules->minQty)
            || ($rules->maxQty !== null && $quantity->isGreaterThan($rules->maxQty))) {
            return 'LOT_SIZE';
        }

        if ($request->stopPrice !== null && ! $request->stopPrice->value->equals($request->stopPrice->value->roundDownToMultipleOf($rules->tickSize))) {
            return 'PRICE_FILTER';
        }

        $reference = $request->stopPrice ?? $this->lastClose[(string) $request->symbol] ?? null;

        if ($reference === null) {
            return 'NO_PRICE';
        }

        // Binance exempts reduce-only orders from the minimum notional.
        if (! $request->reduceOnly && $rules->minNotional !== null && $quantity->times($reference->value)->isLessThan($rules->minNotional)) {
            return 'NOTIONAL';
        }

        return null;
    }

    private function leverageOf(Symbol $symbol): int
    {
        return $this->leverage[(string) $symbol] ?? 1;
    }

    private function release(SimulatedOrder $order): void
    {
        $this->wallet = $this->wallet->plus($order->locked);
        $this->reserved = $this->reserved->minus($order->locked);
        $order->locked = Decimal::zero();
    }

    private function finish(SimulatedOrder $order, OrderStatus $status, ?VenueFill $fill = null): void
    {
        $order->status = $status;

        if (! $status->isWorking()) {
            $this->release($order);
            unset($this->open[$order->request->clientOrderId->value]);
        }

        $this->record($order, $fill);
    }

    private function record(SimulatedOrder $order, ?VenueFill $fill = null): void
    {
        $this->stream->push(new OrderUpdate($this->stream->next(), $order->request->clientOrderId, $order->status, $this->clock->now(), $fill));
    }
}
