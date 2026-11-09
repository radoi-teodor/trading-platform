<?php

declare(strict_types=1);

namespace App\Simulation\Data;

use App\Execution\Journal\InMemoryDecisionLog;
use App\Portfolio\Data\Marks;
use App\Portfolio\Ledger;
use App\Simulation\Metrics\EquityTracker;

final readonly class ParticipantResult
{
    public function __construct(
        public Participant $participant,
        public EquityTracker $equity,
        public Ledger $ledger,
        public InMemoryDecisionLog $log,
        public Marks $marks,
    ) {}
}
