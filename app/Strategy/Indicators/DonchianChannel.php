<?php

declare(strict_types=1);

namespace App\Strategy\Indicators;

use App\Market\Data\Candle;
use App\Support\Number\Decimal;

/**
 * The DSL's donchian(n): highest high and lowest low of the last n candles, the current one included, and their
 * midpoint. A breakout above the previous channel reads donchian(n).upper[1].
 */
final class DonchianChannel implements BarIndicator
{
    private Window $highs;

    private Window $lows;

    public function __construct(public readonly int $period)
    {
        $this->highs = new Window($period);
        $this->lows = new Window($period);
    }

    /** @return array{upper: ?Decimal, middle: ?Decimal, lower: ?Decimal} */
    public function push(Candle $bar): array
    {
        $this->highs->push($bar->high->value);
        $this->lows->push($bar->low->value);

        if (! $this->highs->isFull()) {
            return ['upper' => null, 'middle' => null, 'lower' => null];
        }

        $upper = $this->highs->max();
        $lower = $this->lows->min();

        return ['upper' => $upper, 'middle' => $upper->plus($lower)->dividedBy(Decimal::of(2), 18), 'lower' => $lower];
    }
}
