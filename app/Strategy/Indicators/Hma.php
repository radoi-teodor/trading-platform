<?php

declare(strict_types=1);

namespace App\Strategy\Indicators;

use App\Support\Number\Decimal;
use InvalidArgumentException;

/** Hull moving average: the WMA over floor(sqrt(n)) of 2 × WMA(n / 2) - WMA(n), n / 2 rounded down. */
final class Hma
{
    private Wma $half;

    private Wma $full;

    private Wma $smooth;

    public function __construct(public readonly int $period)
    {
        if ($period < 2) {
            throw new InvalidArgumentException("A Hull average needs a period of at least 2, got {$period}.");
        }

        $this->half = new Wma(intdiv($period, 2));
        $this->full = new Wma($period);
        $root = 1;

        while (($root + 1) * ($root + 1) <= $period) {
            $root++;
        }

        $this->smooth = new Wma($root);
    }

    public function push(Decimal $value): ?Decimal
    {
        $half = $this->half->push($value);
        $full = $this->full->push($value);

        return $half === null || $full === null ? null : $this->smooth->push($half->times(Decimal::of(2))->minus($full));
    }
}
