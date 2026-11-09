<?php

declare(strict_types=1);

namespace App\Execution\Data;

enum OrderType: string
{
    case Market = 'market';
    case Limit = 'limit';
    case StopMarket = 'stop_market';
    case TakeProfitMarket = 'take_profit_market';
}
