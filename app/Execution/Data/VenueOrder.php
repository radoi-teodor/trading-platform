<?php

declare(strict_types=1);

namespace App\Execution\Data;

use App\Support\Number\Decimal;
use App\Support\Number\Price;
use App\Support\Side;
use App\Support\Symbol;

/** A working order as the venue sees it. */
final readonly class VenueOrder
{
    public function __construct(
        public ClientOrderId $clientOrderId,
        public Symbol $symbol,
        public Side $side,
        public OrderType $type,
        public ?Decimal $quantity,
        public Decimal $filledQuantity,
        public ?Price $price,
        public ?Price $stopPrice,
        public OrderStatus $status,
        public ?ClientOrderId $ocoWith,
    ) {}
}
