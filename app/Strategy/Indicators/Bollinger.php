<?php

declare(strict_types=1);

namespace App\Strategy\Indicators;

use App\Support\Number\Decimal;

/** Bollinger bands: the SMA(n) ± k population standard deviations; width = (upper - lower) / middle. */
final class Bollinger
{
    private Sma $middle;

    private StandardDeviation $deviation;

    public function __construct(public readonly int $period, private readonly Decimal $k)
    {
        $this->middle = new Sma($period);
        $this->deviation = new StandardDeviation($period);
    }

    /** @return array{upper: ?Decimal, middle: ?Decimal, lower: ?Decimal, width: ?Decimal} */
    public function push(Decimal $value): array
    {
        $middle = $this->middle->push($value);
        $deviation = $this->deviation->push($value);

        if ($middle === null || $deviation === null) {
            return ['upper' => null, 'middle' => null, 'lower' => null, 'width' => null];
        }

        $upper = $middle->plus($this->k->times($deviation));
        $lower = $middle->minus($this->k->times($deviation));

        return ['upper' => $upper, 'middle' => $middle, 'lower' => $lower, 'width' => $upper->minus($lower)->dividedBy($middle, 18)];
    }
}
