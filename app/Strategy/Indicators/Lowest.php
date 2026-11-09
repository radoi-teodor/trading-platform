<?php

declare(strict_types=1);

namespace App\Strategy\Indicators;

use App\Support\Number\Decimal;

/** The lowest of the last n values, the current one included. */
final class Lowest implements ValueIndicator
{
    private Window $window;

    public function __construct(public readonly int $period)
    {
        $this->window = new Window($period);
    }

    public function push(Decimal $value): ?Decimal
    {
        $this->window->push($value);

        return $this->window->isFull() ? $this->window->min() : null;
    }
}
