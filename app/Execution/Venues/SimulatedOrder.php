<?php

declare(strict_types=1);

namespace App\Execution\Venues;

use App\Execution\Data\ClientOrderId;
use App\Execution\Data\OrderRequest;
use App\Execution\Data\OrderStatus;
use App\Execution\Data\VenueOrder;
use App\Support\Number\Decimal;

/** The simulated venue's own record of an order: what it locks, how much has filled, whether it has met a minute yet. */
final class SimulatedOrder
{
    public OrderStatus $status = OrderStatus::New;

    public Decimal $filled;

    /** Whether the order has been matched against at least one minute; a limit is marketable only on arrival. */
    public bool $arrived = false;

    public function __construct(
        public readonly OrderRequest $request,
        public string $lockAsset,
        public Decimal $locked,
        public ?ClientOrderId $ocoWith = null,
    ) {
        $this->filled = Decimal::zero();
    }

    public function remaining(): Decimal
    {
        return ($this->request->quantity ?? Decimal::zero())->minus($this->filled);
    }

    public function snapshot(): VenueOrder
    {
        return new VenueOrder(
            $this->request->clientOrderId,
            $this->request->symbol,
            $this->request->side,
            $this->request->type,
            $this->request->quantity,
            $this->filled,
            $this->request->price,
            $this->request->stopPrice,
            $this->status,
            $this->ocoWith,
        );
    }
}
