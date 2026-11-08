<?php

declare(strict_types=1);

namespace App\Execution;

use App\Execution\Data\ClientOrderId;
use App\Execution\Data\OrderStatus;
use App\Execution\Data\OrderUpdate;
use App\Execution\Data\TrackedOrder;
use App\Support\Symbol;

/** The orders the engine has placed, by client order id, updated from the venue's order updates. */
final class OrderBook
{
    /** @var array<string, TrackedOrder> */
    private array $orders = [];

    public function track(TrackedOrder $order): void
    {
        $this->orders[$order->id->value] = $order;
    }

    public function apply(OrderUpdate $update): TrackedOrder
    {
        $order = $this->orders[$update->clientOrderId->value]
            ?? throw new ReconciliationMismatch("The venue reports order {$update->clientOrderId}, which the engine never placed.");
        $order->status = $update->status;

        if ($update->fill !== null) {
            $order->filled = $order->filled->plus($update->fill->quantity);
        }

        return $order;
    }

    /** Records a cancellation the venue confirmed, with the other leg of an OCO, before its updates arrive. */
    public function cancelled(ClientOrderId $id): void
    {
        $order = $this->orders[$id->value] ?? null;

        foreach ([$order, $order?->ocoWith === null ? null : ($this->orders[$order->ocoWith->value] ?? null)] as $leg) {
            if ($leg !== null && $leg->status->isWorking()) {
                $leg->status = OrderStatus::Cancelled;
            }
        }
    }

    /** @return list<TrackedOrder> */
    public function working(): array
    {
        return array_values(array_filter($this->orders, fn (TrackedOrder $order): bool => $order->status->isWorking()));
    }

    /** @return list<TrackedOrder> */
    public function workingFor(string $strategy, Symbol $symbol): array
    {
        return array_values(array_filter(
            $this->working(),
            fn (TrackedOrder $order): bool => $order->strategy === $strategy && $order->symbol->equals($symbol),
        ));
    }
}
