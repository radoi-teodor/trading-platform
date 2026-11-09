<?php

declare(strict_types=1);

namespace App\Strategy\Dsl\Functions;

enum ParamKind: string
{
    case Count = 'a whole number of candles';
    case Number = 'a number';
    case Series = 'a series';
    case Anchor = 'a timeframe';
    case Duration = 'a duration';
    case Percent = 'a percent';
    case DepthBand = 'a band of the order book: 1%, 2% or 5%';
}
