<?php

declare(strict_types=1);

namespace App\Strategy\Dsl\Functions;

enum ParamKind: string
{
    case Count = 'a whole number of candles';
    case Number = 'a number';
    case Series = 'a series';
    case Anchor = 'a timeframe';
    case Text = 'a quoted text';
    case Duration = 'a duration';
    case Percent = 'a percent';
}
