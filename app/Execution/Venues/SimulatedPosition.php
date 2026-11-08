<?php

declare(strict_types=1);

namespace App\Execution\Venues;

use App\Execution\Data\VenuePosition;
use App\Support\Number\Decimal;
use App\Support\Side;
use App\Support\Symbol;

/** The simulated venue's own record of an isolated perpetual position. */
final class SimulatedPosition
{
    public function __construct(
        public readonly Symbol $symbol,
        public readonly Side $side,
        public readonly int $leverage,
        public Decimal $quantity,
        public Decimal $entryPrice,
        public Decimal $margin,
    ) {}

    public function snapshot(): VenuePosition
    {
        return new VenuePosition($this->symbol, $this->side, $this->quantity, $this->entryPrice, $this->margin, $this->leverage);
    }
}
