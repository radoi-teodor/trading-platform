<?php

declare(strict_types=1);

namespace App\Execution\Contracts;

use App\Execution\Data\EngineRecord;

/** Tells the operator what an engine did that needs a human: halts, stale data, mismatches, divergences. */
interface Notifier
{
    public function engineEvent(string $account, EngineRecord $record): void;
}
