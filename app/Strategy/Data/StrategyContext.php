<?php

declare(strict_types=1);

namespace App\Strategy\Data;

use App\Market\Data\Candle;
use App\Market\Data\FundingRate;
use App\Support\Symbol;
use Carbon\CarbonImmutable;

/**
 * One call to a strategy: the candle that just closed, the strategy's own position in that instrument and, on a
 * perpetual, the last funding rate known at that moment.
 */
final readonly class StrategyContext
{
    public function __construct(
        public CarbonImmutable $time,
        public Symbol $symbol,
        public Candle $candle,
        public ?PositionView $position,
        public ?FundingRate $funding = null,
    ) {}
}
