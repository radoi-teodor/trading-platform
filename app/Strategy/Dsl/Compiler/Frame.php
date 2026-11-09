<?php

declare(strict_types=1);

namespace App\Strategy\Dsl\Compiler;

use App\Market\Data\Candle;
use App\Market\Data\FundingRate;
use App\Strategy\Data\PerpetualView;
use App\Strategy\Data\PositionView;
use Carbon\CarbonImmutable;

/** What compiled expressions read when a candle closes: the candle, the time, the position, the last funding and a perpetual's data for scalping. */
final readonly class Frame
{
    public function __construct(
        public Candle $candle,
        public CarbonImmutable $time,
        public ?PositionView $position,
        public ?FundingRate $funding,
        public ?TradeMemory $trade,
        public ?PerpetualView $perpetual = null,
    ) {}
}
