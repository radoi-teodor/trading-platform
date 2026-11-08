<?php

declare(strict_types=1);

namespace App\Execution\Data;

use App\Support\Number\Decimal;
use App\Support\Symbol;

/** The engine's own record of an order it placed: whose it is, what for, and its last known state. */
final class TrackedOrder
{
    public OrderStatus $status = OrderStatus::New;

    public Decimal $filled;

    public function __construct(
        public readonly ClientOrderId $id,
        public readonly string $strategy,
        public readonly Symbol $symbol,
        public readonly OrderRole $role,
        public readonly ?Decimal $quantity,
        public readonly ?ClientOrderId $ocoWith = null,
    ) {
        $this->filled = Decimal::zero();
    }

    public function remaining(): ?Decimal
    {
        return $this->quantity?->minus($this->filled);
    }
}
