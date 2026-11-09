<?php

declare(strict_types=1);

namespace App\Execution\Data;

/** Whether a fill rested in the book (maker) or took from it (taker); it decides the fee rate. */
enum Liquidity: string
{
    case Maker = 'maker';
    case Taker = 'taker';
}
