<?php

declare(strict_types=1);

namespace App\Execution\Data;

use Carbon\CarbonImmutable;

/** Something the engine did on its own: a halt, a flatten, dust written off, an order the venue refused. */
final readonly class EngineRecord
{
    public function __construct(public CarbonImmutable $time, public string $kind, public string $detail) {}
}
