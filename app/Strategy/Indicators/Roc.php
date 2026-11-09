<?php

declare(strict_types=1);

namespace App\Strategy\Indicators;

use App\Support\Number\Decimal;

/** Rate of change in percent: 100 × (value / value n back - 1). */
final class Roc implements ValueIndicator
{
    private Window $window;

    public function __construct(public readonly int $period)
    {
        $this->window = new Window(Period::check($period) + 1);
    }

    public function push(Decimal $value): ?Decimal
    {
        $this->window->push($value);

        return $this->window->isFull()
            ? Decimal::of(100)->times($value->dividedBy($this->window->first(), 18)->minus(Decimal::of(1)))
            : null;
    }
}
