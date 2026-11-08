<?php

declare(strict_types=1);

namespace App\Strategy\Indicators;

use App\Support\Number\Decimal;

/** Weighted moving average: weights 1 to n, the most recent value weighing n. */
final class Wma implements ValueIndicator
{
    private Window $window;

    private Decimal $total;

    public function __construct(public readonly int $period)
    {
        $this->window = new Window($period);
        $this->total = Decimal::of(intdiv($period * ($period + 1), 2));
    }

    public function push(Decimal $value): ?Decimal
    {
        $this->window->push($value);

        if (! $this->window->isFull()) {
            return null;
        }

        $sum = Decimal::zero();

        foreach ($this->window->values() as $index => $item) {
            $sum = $sum->plus($item->times(Decimal::of($index + 1)));
        }

        return $sum->dividedBy($this->total, 18);
    }
}
