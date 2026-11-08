<?php

declare(strict_types=1);

namespace App\Strategy\Data;

use App\Support\Symbol;
use App\Support\Timeframe;

/** An instrument and the timeframe a strategy decides on. */
final readonly class Subscription
{
    public function __construct(public Symbol $symbol, public Timeframe $timeframe) {}
}
