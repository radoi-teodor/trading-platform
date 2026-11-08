<?php

declare(strict_types=1);

namespace App\Strategy\Indicators;

use App\Support\Number\Decimal;

/** An indicator fed one value of a series per candle: a single value, or several by field name. */
interface ValueIndicator
{
    /** @return Decimal|array<string, ?Decimal>|null */
    public function push(Decimal $value): Decimal|array|null;
}
