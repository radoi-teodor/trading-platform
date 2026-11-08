<?php

declare(strict_types=1);

namespace App\Portfolio\Data;

/** Why a position was reduced: the role of the order that sold (docs/engine.md, section 6). */
enum ExitReason: string
{
    case Signal = 'signal';
    case Stop = 'stop';
    case Target = 'target';
    case Flatten = 'flatten';
    case Liquidation = 'liquidation';
}
