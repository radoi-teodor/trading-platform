<?php

declare(strict_types=1);

namespace App\Execution\Journal;

use App\Execution\Contracts\DecisionLog;
use App\Execution\Data\DecisionRecord;
use App\Execution\Data\EngineRecord;

final class InMemoryDecisionLog implements DecisionLog
{
    /** @var list<DecisionRecord> */
    public array $decisions = [];

    /** @var list<EngineRecord> */
    public array $events = [];

    public function decision(DecisionRecord $record): void
    {
        $this->decisions[] = $record;
    }

    public function event(EngineRecord $record): void
    {
        $this->events[] = $record;
    }
}
