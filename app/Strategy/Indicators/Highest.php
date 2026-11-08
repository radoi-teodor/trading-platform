<?php

declare(strict_types=1);

namespace App\Strategy\Indicators;

use App\Support\Number\Decimal;

/** The highest of the last n values, the current one included. */
final class Highest
{
    private Window $window;

    public function __construct(public readonly int $period)
    {
        $this->window = new Window($period);
    }

    public function push(Decimal $value): ?Decimal
    {
        $this->window->push($value);

        return $this->window->isFull() ? $this->window->max() : null;
    }
}
