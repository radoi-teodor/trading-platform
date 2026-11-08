<?php

declare(strict_types=1);

namespace App\Execution\Data;

enum EngineMode: string
{
    case Backtest = 'backtest';
    case Paper = 'paper';
    case Testnet = 'testnet';
    case Live = 'live';
}
