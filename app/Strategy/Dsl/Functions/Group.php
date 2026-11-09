<?php

declare(strict_types=1);

namespace App\Strategy\Dsl\Functions;

/** The function groups of docs/strategy/configurator.md, section 2, which decide where a function may be used. */
enum Group
{
    case Price;
    case Indicator;
    case Perpetual;
    case Position;
    case Time;
    case Exit;
    case Order;
    case Universe;
}
