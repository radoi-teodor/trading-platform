<?php

declare(strict_types=1);

namespace App\Risk\Data;

use App\Support\Number\Decimal;
use Carbon\CarbonImmutable;

/** A loss limit reached: the engine halts or flattens and journals it. */
final readonly class RiskEvent
{
    public function __construct(public RiskEventKind $kind, public CarbonImmutable $time, public Decimal $equity) {}
}
