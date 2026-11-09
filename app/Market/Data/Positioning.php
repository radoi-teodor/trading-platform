<?php

declare(strict_types=1);

namespace App\Market\Data;

use App\Support\Number\Decimal;
use Carbon\CarbonImmutable;

/**
 * Where a perpetual's traders stand at a time, every 5 minutes (D-91): accounts long against short, the top traders'
 * positions long against short, and taker buy volume against sell volume. Known 5 minutes later, like open interest.
 */
final readonly class Positioning
{
    public function __construct(
        public CarbonImmutable $time,
        public ?Decimal $longShortRatio,
        public ?Decimal $topTraderRatio,
        public ?Decimal $takerRatio,
    ) {}

    public function knownAt(): CarbonImmutable
    {
        return $this->time->addSeconds(OpenInterest::KNOWN_AFTER_SECONDS);
    }
}
