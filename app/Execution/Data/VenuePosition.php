<?php

declare(strict_types=1);

namespace App\Execution\Data;

use App\Support\Number\Decimal;
use App\Support\Side;
use App\Support\Symbol;

/** A perpetual position as the venue holds it (one-way, isolated). */
final readonly class VenuePosition
{
    public function __construct(
        public Symbol $symbol,
        public Side $side,
        public Decimal $quantity,
        public Decimal $entryPrice,
        public Decimal $margin,
        public int $leverage,
    ) {}
}
