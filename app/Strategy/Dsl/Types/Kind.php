<?php

declare(strict_types=1);

namespace App\Strategy\Dsl\Types;

/** What an expression is (docs/strategy/configurator.md, section 2). */
enum Kind: string
{
    case Series = 'series';
    case Condition = 'condition';
    case Number = 'number';
    case Percent = 'percent';
    case Duration = 'duration';
    case RMultiple = 'R multiple';
    case Text = 'text';
    case Stop = 'stop level';
    case Trail = 'trailing stop';
    case Order = 'order';
    case None = 'none';
}
