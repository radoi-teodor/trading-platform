<?php

declare(strict_types=1);

namespace App\Strategy\Data;

use App\Support\Number\Decimal;

/**
 * A strategy's own sizing and limits (D-54), only ever tighter than the account policy: the risk engine applies the
 * lower of the two. Null means the policy's value.
 */
final readonly class StrategyLimits
{
    public function __construct(
        public ?Decimal $riskPerTrade = null,
        public ?Decimal $maxPosition = null,
        public ?int $maxOpenPositions = null,
        public ?int $maxTradesPerDay = null,
        public ?int $cooldownSeconds = null,
    ) {}
}
