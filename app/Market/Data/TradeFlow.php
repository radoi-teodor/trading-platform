<?php

declare(strict_types=1);

namespace App\Market\Data;

use App\Support\Number\Decimal;
use Carbon\CarbonImmutable;

/**
 * One minute of a perpetual's aggregated trades, reduced to its large trades (D-79): the base quantity bought and
 * sold by trades whose notional is at least the threshold, how many, and the minute's largest trade notional.
 */
final readonly class TradeFlow
{
    public function __construct(
        public CarbonImmutable $openTime,
        public Decimal $largeBuyVolume,
        public Decimal $largeSellVolume,
        public int $largeBuyCount,
        public int $largeSellCount,
        public Decimal $largestNotional,
        public Decimal $threshold,
    ) {}

    public function largeDelta(): Decimal
    {
        return $this->largeBuyVolume->minus($this->largeSellVolume);
    }
}
