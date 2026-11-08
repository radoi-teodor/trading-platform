<?php

declare(strict_types=1);

namespace App\Market\Data;

enum InstrumentStatus: string
{
    case Trading = 'trading';
    case Halted = 'halted';
    case Delisted = 'delisted';
}
