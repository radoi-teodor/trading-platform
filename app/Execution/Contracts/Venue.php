<?php

declare(strict_types=1);

namespace App\Execution\Contracts;

use App\Execution\Data\Balance;
use App\Execution\Data\ClientOrderId;
use App\Execution\Data\OcoRequest;
use App\Execution\Data\OrderAck;
use App\Execution\Data\OrderRequest;
use App\Execution\Data\OrderUpdate;
use App\Execution\Data\VenueOrder;

/** What the engine knows of an exchange (docs/engine.md, section 3). The venue is the source of truth (D-10). */
interface Venue
{
    public function submit(OrderRequest $request): OrderAck;

    public function submitOco(OcoRequest $request): OrderAck;

    /** True when the order was working and is now cancelled; false when it was already final. */
    public function cancel(ClientOrderId $id): bool;

    /** @return list<OrderUpdate> the updates numbered after $sequence, in order */
    public function updatesSince(int $sequence): array;

    /** @return list<VenueOrder> working orders, in submission order */
    public function openOrders(): array;

    /** @return array<string, Balance> by asset code, in code order */
    public function balances(): array;
}
