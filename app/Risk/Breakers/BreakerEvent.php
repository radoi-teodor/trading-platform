<?php

declare(strict_types=1);

namespace App\Risk\Breakers;

use Carbon\CarbonImmutable;

/** A breaker that tripped ($tripped) or cleared on an instrument, or on the account when $symbol is null. */
final readonly class BreakerEvent
{
    public function __construct(public string $kind, public ?string $symbol, public bool $tripped, public CarbonImmutable $time, public string $detail) {}
}
