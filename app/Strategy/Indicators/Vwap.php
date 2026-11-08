<?php

declare(strict_types=1);

namespace App\Strategy\Indicators;

use App\Market\Data\Candle;
use App\Support\Number\Decimal;
use App\Support\Timeframe;

/**
 * Volume-weighted average of the typical price (high + low + close) / 3 since the start of the anchor period
 * (1d: since 00:00 UTC); no value while the period has traded nothing.
 */
final class Vwap implements BarIndicator
{
    private Decimal $weighted;

    private Decimal $volume;

    private ?int $period = null;

    public function __construct(public readonly Timeframe $anchor)
    {
        $this->weighted = Decimal::zero();
        $this->volume = Decimal::zero();
    }

    public function push(Candle $bar): ?Decimal
    {
        $period = $this->anchor->openTimeContaining($bar->openTime)->getTimestamp();

        if ($period !== $this->period) {
            $this->weighted = Decimal::zero();
            $this->volume = Decimal::zero();
            $this->period = $period;
        }

        $typical = $bar->high->value->plus($bar->low->value)->plus($bar->close->value)->dividedBy(Decimal::of(3), 18);
        $this->weighted = $this->weighted->plus($typical->times($bar->volume));
        $this->volume = $this->volume->plus($bar->volume);

        return $this->volume->isPositive() ? $this->weighted->dividedBy($this->volume, 18) : null;
    }
}
