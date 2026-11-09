<?php

declare(strict_types=1);

namespace App\Strategy\Indicators;

use App\Market\Data\Candle;
use App\Support\Number\Decimal;

/**
 * On-balance volume (D-72): from 0 at the first candle, adds the volume of a candle that closes higher than the one
 * before and subtracts the volume of one that closes lower.
 */
final class Obv implements BarIndicator
{
    private ?Decimal $previous = null;

    private Decimal $total;

    public function __construct()
    {
        $this->total = Decimal::zero();
    }

    public function push(Candle $bar): Decimal
    {
        $close = $bar->close->value;

        if ($this->previous !== null && ! $close->equals($this->previous)) {
            $this->total = $close->isGreaterThan($this->previous) ? $this->total->plus($bar->volume) : $this->total->minus($bar->volume);
        }

        $this->previous = $close;

        return $this->total;
    }
}
