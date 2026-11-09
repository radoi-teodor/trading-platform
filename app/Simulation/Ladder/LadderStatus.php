<?php

declare(strict_types=1);

namespace App\Simulation\Ladder;

/** Where a deployment stands on the ladder (D-83): its days and trades, what keeps it from the next step, its band. */
final readonly class LadderStatus
{
    /** @param list<string> $breaches */
    public function __construct(
        public int $days,
        public int $trades,
        public array $breaches,
        public ?string $next,
        public ?string $missing,
        public bool $ready,
    ) {}
}
