<?php

declare(strict_types=1);

namespace App\Execution\Data;

enum IncomeKind: string
{
    case Funding = 'funding';
    case Liquidation = 'liquidation';
}
