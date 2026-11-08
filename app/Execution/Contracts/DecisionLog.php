<?php

declare(strict_types=1);

namespace App\Execution\Contracts;

use App\Execution\Data\DecisionRecord;
use App\Execution\Data\EngineRecord;

/** Where the engine journals (docs/engine.md, section 7). In memory for backtests; the database journal is Phase 4. */
interface DecisionLog
{
    public function decision(DecisionRecord $record): void;

    public function event(EngineRecord $record): void;
}
