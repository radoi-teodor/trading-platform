<?php

declare(strict_types=1);

namespace App\Market\Data;

/** What an upsert did: rows created, rows replaced by a higher-ranked source, rows left as they were. */
final readonly class UpsertResult
{
    public function __construct(
        public int $inserted = 0,
        public int $overwritten = 0,
        public int $kept = 0,
        public int $discrepancies = 0,
    ) {}

    public function plus(self $other): self
    {
        return new self(
            $this->inserted + $other->inserted,
            $this->overwritten + $other->overwritten,
            $this->kept + $other->kept,
            $this->discrepancies + $other->discrepancies,
        );
    }
}
