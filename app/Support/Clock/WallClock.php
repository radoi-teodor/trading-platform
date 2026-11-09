<?php

declare(strict_types=1);

namespace App\Support\Clock;

use Carbon\CarbonImmutable;

final readonly class WallClock implements Clock
{
    public function now(): CarbonImmutable
    {
        return new CarbonImmutable('now', 'UTC');
    }
}
