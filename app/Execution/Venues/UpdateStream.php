<?php

declare(strict_types=1);

namespace App\Execution\Venues;

use App\Execution\Data\Income;
use App\Execution\Data\OrderUpdate;

/** The simulated venue's one numbered stream of order updates and perpetual income. */
final class UpdateStream
{
    /** @var list<OrderUpdate|Income> */
    private array $updates = [];

    public function next(): int
    {
        return count($this->updates) + 1;
    }

    public function push(OrderUpdate|Income $update): void
    {
        $this->updates[] = $update;
    }

    /** @return list<OrderUpdate|Income> */
    public function since(int $sequence): array
    {
        return array_slice($this->updates, max(0, $sequence));
    }
}
