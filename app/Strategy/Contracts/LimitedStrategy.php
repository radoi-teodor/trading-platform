<?php

declare(strict_types=1);

namespace App\Strategy\Contracts;

use App\Strategy\Data\StrategyLimits;

/** A strategy that carries its own sizing and limits (a DSL document's sizing: and limits:, D-54). */
interface LimitedStrategy extends Strategy
{
    public function limits(): StrategyLimits;
}
