<?php

declare(strict_types=1);

namespace App\Market\Data;

use App\Support\Number\Decimal;
use Carbon\CarbonImmutable;

/** One minute of a price series that is not traded: a perpetual's mark price or its premium index (D-79). */
final readonly class PriceMinute
{
    public function __construct(public CarbonImmutable $openTime, public Decimal $open, public Decimal $high, public Decimal $low, public Decimal $close) {}
}
