<?php

declare(strict_types=1);

namespace App\Strategy\Indicators;

use App\Market\Data\Candle;
use App\Support\Number\Decimal;

/** Volume-weighted average of the closes of the last n candles; no value when they traded nothing. */
final class Vwma
{
    private Window $weighted;

    private Window $volumes;

    public function __construct(public readonly int $period)
    {
        $this->weighted = new Window($period);
        $this->volumes = new Window($period);
    }

    public function push(Candle $bar): ?Decimal
    {
        $this->weighted->push($bar->close->value->times($bar->volume));
        $this->volumes->push($bar->volume);
        $volume = $this->volumes->sum();

        return $this->volumes->isFull() && $volume->isPositive() ? $this->weighted->sum()->dividedBy($volume, 18) : null;
    }
}
