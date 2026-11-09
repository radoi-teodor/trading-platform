<?php

declare(strict_types=1);

namespace App\Strategy\Indicators;

use App\Support\Number\Decimal;

/**
 * Relative strength index with Wilder's averages of gains and losses over n changes: 100 - 100 / (1 + gain / loss);
 * 100 when nothing was lost, 50 when nothing moved.
 */
final class Rsi implements ValueIndicator
{
    private Wilder $gains;

    private Wilder $losses;

    private ?Decimal $previous = null;

    public function __construct(public readonly int $period)
    {
        $this->gains = new Wilder($period);
        $this->losses = new Wilder($period);
    }

    public function push(Decimal $value): ?Decimal
    {
        $previous = $this->previous;
        $this->previous = $value;

        if ($previous === null) {
            return null;
        }

        $change = $value->minus($previous);
        $gain = $this->gains->push($change->isPositive() ? $change : Decimal::zero());
        $loss = $this->losses->push($change->isNegative() ? $change->negate() : Decimal::zero());

        if ($gain === null || $loss === null) {
            return null;
        }

        if ($loss->isZero()) {
            return Decimal::of($gain->isZero() ? 50 : 100);
        }

        $hundred = Decimal::of(100);

        return $hundred->minus($hundred->dividedBy(Decimal::of(1)->plus($gain->dividedBy($loss, 18)), 18));
    }
}
