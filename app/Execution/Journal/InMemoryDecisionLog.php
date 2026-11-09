<?php

declare(strict_types=1);

namespace App\Execution\Journal;

use App\Execution\Contracts\DecisionLog;
use App\Execution\Data\DecisionRecord;
use App\Execution\Data\EngineRecord;

/**
 * Keeps a backtest's journal for its report. Decisions without intents, most of them on short timeframes, are only
 * counted, so that a year of minute decisions does not fill the memory.
 */
final class InMemoryDecisionLog implements DecisionLog
{
    /** @var list<DecisionRecord> decisions that had intents */
    public array $decisions = [];

    public int $quietDecisions = 0;

    /** @var list<EngineRecord> */
    public array $events = [];

    public function decision(DecisionRecord $record): void
    {
        if ($record->intents === []) {
            $this->quietDecisions++;

            return;
        }

        $this->decisions[] = $record;
    }

    public function event(EngineRecord $record): void
    {
        $this->events[] = $record;
    }
}
