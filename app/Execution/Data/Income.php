<?php

declare(strict_types=1);

namespace App\Execution\Data;

use App\Support\Number\Decimal;
use App\Support\Number\Price;
use App\Support\Symbol;
use Carbon\CarbonImmutable;

/**
 * A change to a perpetual position that is not an order fill: funding paid or received, or a liquidation (the
 * amount is the margin lost and $price the liquidation price). Numbered in the same stream as order updates.
 */
final readonly class Income
{
    public function __construct(
        public int $sequence,
        public string $id,
        public IncomeKind $kind,
        public Symbol $symbol,
        public Decimal $amount,
        public ?Price $price,
        public CarbonImmutable $time,
    ) {}
}
