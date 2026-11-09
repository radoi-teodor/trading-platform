<?php

declare(strict_types=1);

namespace App\Market\Instruments;

final readonly class SyncResult
{
    public function __construct(public int $instruments, public int $filterChanges, public int $delisted) {}
}
