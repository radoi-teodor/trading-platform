<?php

declare(strict_types=1);

namespace App\Simulation\Data;

use Carbon\CarbonImmutable;

final readonly class BacktestResult
{
    /** @param list<ParticipantResult> $benchmarks */
    public function __construct(
        public string $fingerprint,
        public int $candles,
        public CarbonImmutable $feedFrom,
        public ParticipantResult $main,
        public array $benchmarks,
    ) {}
}
