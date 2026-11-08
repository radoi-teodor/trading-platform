<?php

declare(strict_types=1);

namespace App\Strategy\Indicators;

use App\Market\Data\Candle;
use App\Support\Number\Decimal;

/** An indicator fed whole candles: a single value, or several by field name. */
interface BarIndicator
{
    /** @return Decimal|array<string, ?Decimal>|null */
    public function push(Candle $bar): Decimal|array|null;
}
