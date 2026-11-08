<?php

declare(strict_types=1);

namespace App\Market\Data;

enum MarketType: string
{
    case Spot = 'spot';
    case Perp = 'perp';
}
