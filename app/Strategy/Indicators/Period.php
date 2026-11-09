<?php

declare(strict_types=1);

namespace App\Strategy\Indicators;

use InvalidArgumentException;

final class Period
{
    public static function check(int $period): int
    {
        if ($period < 1) {
            throw new InvalidArgumentException("An indicator period must be at least 1, got {$period}.");
        }

        return $period;
    }
}
