<?php

declare(strict_types=1);

namespace App\Market\Data;

use App\Support\Number\Decimal;
use Carbon\CarbonImmutable;
use InvalidArgumentException;

/** The exchange filters that orders on an instrument must respect, from $validFrom on. */
final readonly class InstrumentRules
{
    public function __construct(
        public CarbonImmutable $validFrom,
        public Decimal $tickSize,
        public Decimal $stepSize,
        public Decimal $minQty,
        public ?Decimal $maxQty,
        public ?Decimal $minNotional,
    ) {
        if (! $tickSize->isPositive() || ! $stepSize->isPositive()) {
            throw new InvalidArgumentException("Tick size and lot step must be positive, got {$tickSize} and {$stepSize}.");
        }

        if ($minQty->isNegative()) {
            throw new InvalidArgumentException("The minimum quantity cannot be negative, got {$minQty}.");
        }
    }
}
