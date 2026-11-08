<?php

declare(strict_types=1);

namespace App\Simulation\Data;

use App\Risk\RiskPolicy;
use App\Strategy\Contracts\Strategy;
use App\Support\Number\Decimal;
use App\Support\Number\Money;
use Carbon\CarbonImmutable;

/**
 * One account in a backtest: the strategy under test or a benchmark, each with its own venue and ledger. Balances
 * default to the run definition's (D-49).
 */
final readonly class Participant
{
    public function __construct(
        public string $label,
        public Strategy $strategy,
        public RiskPolicy $policy,
        public CarbonImmutable $warmupFrom,
        public ?Money $spotBalance = null,
        public ?Decimal $futuresBalance = null,
    ) {}
}
