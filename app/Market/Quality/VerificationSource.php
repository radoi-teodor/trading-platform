<?php

declare(strict_types=1);

namespace App\Market\Quality;

enum VerificationSource: string
{
    case Rest = 'rest';
    case DailyArchive = 'daily-archive';
}
