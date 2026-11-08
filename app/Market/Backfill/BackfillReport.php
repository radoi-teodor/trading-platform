<?php

declare(strict_types=1);

namespace App\Market\Backfill;

final readonly class BackfillReport
{
    /** @param list<BackfillStep> $steps */
    public function __construct(public array $steps, public ?string $failure) {}

    public function failed(): bool
    {
        return $this->failure !== null;
    }
}
