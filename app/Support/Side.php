<?php

declare(strict_types=1);

namespace App\Support;

enum Side: string
{
    case Buy = 'buy';
    case Sell = 'sell';
}
