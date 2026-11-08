<?php

declare(strict_types=1);

namespace App\Simulation\Data;

use App\Risk\RiskPolicy;
use App\Strategy\Contracts\Strategy;
use Carbon\CarbonImmutable;

/** One account in a backtest: the strategy under test or a benchmark, each with its own venue and ledger. */
final readonly class Participant
{
    public function __construct(
        public string $label,
        public Strategy $strategy,
        public RiskPolicy $policy,
        public CarbonImmutable $warmupFrom,
    ) {}
}
