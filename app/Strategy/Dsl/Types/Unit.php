<?php

declare(strict_types=1);

namespace App\Strategy\Dsl\Types;

/** What a number measures, so that a price is never compared with a percent or a volume. */
enum Unit: string
{
    case Price = 'price';
    case Volume = 'volume';
    case Fraction = 'rate';
    case Plain = 'value';
    case Any = 'number';
}
