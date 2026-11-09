<?php

declare(strict_types=1);

namespace App\Market\Data;

use App\Support\Number\Decimal;
use Carbon\CarbonImmutable;

/** A perpetual's open interest at a time (every 5 minutes), in contracts and in quote; known 5 minutes later (D-79). */
final readonly class OpenInterest
{
    public const int KNOWN_AFTER_SECONDS = 300;

    public function __construct(public CarbonImmutable $time, public Decimal $contracts, public Decimal $value) {}

    public function knownAt(): CarbonImmutable
    {
        return $this->time->addSeconds(self::KNOWN_AFTER_SECONDS);
    }
}
