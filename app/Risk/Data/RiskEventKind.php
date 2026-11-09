<?php

declare(strict_types=1);

namespace App\Risk\Data;

enum RiskEventKind: string
{
    case DailyLossHalt = 'daily_loss_halt';
    case WeeklyLossHalt = 'weekly_loss_halt';
    case DrawdownHalt = 'drawdown_halt';
    case DrawdownFlatten = 'drawdown_flatten';
}
