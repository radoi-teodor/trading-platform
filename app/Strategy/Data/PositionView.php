<?php

declare(strict_types=1);

namespace App\Strategy\Data;

use App\Support\Number\Decimal;
use App\Support\Number\Price;
use Carbon\CarbonImmutable;

/** What a strategy may know of its own position: never balances, other strategies or orders. */
final readonly class PositionView
{
    public function __construct(
        public Decimal $quantity,
        public Decimal $entryPrice,
        public ?Price $stop,
        public CarbonImmutable $openedAt,
    ) {}
}
