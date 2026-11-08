<?php

declare(strict_types=1);

namespace App\Support\Clock;

use Carbon\CarbonImmutable;
use Psr\Clock\ClockInterface;

/** The only source of time for domain code (D-12): WallClock live and in paper, SimClock in backtests. */
interface Clock extends ClockInterface
{
    public function now(): CarbonImmutable;
}
