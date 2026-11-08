<?php

declare(strict_types=1);

namespace App\Strategy\Dsl\Document;

use App\Support\Number\Decimal;

/** The account policy's limits a document may only tighten (D-54): fractions of equity and a count. */
final readonly class PolicyLimits
{
    public function __construct(public Decimal $riskPerTrade, public Decimal $maxPosition, public int $maxOpenPositions) {}
}
