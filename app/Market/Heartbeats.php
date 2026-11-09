<?php

declare(strict_types=1);

namespace App\Market;

use App\Market\Persistence\Rows;
use App\Support\Clock\Clock;
use Illuminate\Database\ConnectionInterface;

/** The last sign of life of each long-running process, read by health checks (docs/runbook.md). */
final readonly class Heartbeats
{
    public function __construct(private ConnectionInterface $db, private Clock $clock) {}

    /** @param array<string, mixed> $details */
    public function beat(string $process, array $details): void
    {
        $this->db->table('heartbeats')->upsert(
            [['process' => $process, 'beat_at' => Rows::timestamp($this->clock->now()), 'details' => json_encode($details, JSON_THROW_ON_ERROR)]],
            ['process'],
            ['beat_at', 'details'],
        );
    }
}
