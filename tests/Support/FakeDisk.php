<?php

declare(strict_types=1);

namespace Tests\Support;

use App\Notifications\Health\DiskUsage;

/** A disk whose free space a test sets. */
final class FakeDisk implements DiskUsage
{
    public function __construct(public int $free, public int $total) {}

    public function free(): int
    {
        return $this->free;
    }

    public function total(): int
    {
        return $this->total;
    }
}
