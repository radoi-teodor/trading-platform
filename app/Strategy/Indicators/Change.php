<?php

declare(strict_types=1);

namespace App\Strategy\Indicators;

use App\Support\Number\Decimal;

/** The value minus the value n back. */
final class Change implements ValueIndicator
{
    private Window $window;

    public function __construct(public readonly int $period)
    {
        $this->window = new Window(Period::check($period) + 1);
    }

    public function push(Decimal $value): ?Decimal
    {
        $this->window->push($value);

        return $this->window->isFull() ? $value->minus($this->window->first()) : null;
    }
}
