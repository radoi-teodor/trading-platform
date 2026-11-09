<?php

declare(strict_types=1);

namespace App\Simulation\Validation;

use App\Support\Number\Decimal;
use Carbon\CarbonImmutable;

/** One step of a walk-forward: the set chosen in-sample and how it did out of sample. */
final readonly class WalkForwardWindow
{
    /** @param array<string, string>|null $chosen null when no set traded enough in-sample */
    public function __construct(
        public CarbonImmutable $inFrom,
        public CarbonImmutable $outFrom,
        public CarbonImmutable $outTo,
        public ?array $chosen,
        public ?Decimal $inExpectancy,
        public int $inTrades,
        public int $outTrades,
        public Decimal $outNet,
        public int $startedEarlier,
    ) {}
}
