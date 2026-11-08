<?php

declare(strict_types=1);

namespace App\Market\Data;

use Carbon\CarbonImmutable;

/**
 * The 1-minute candles of every followed instrument whose minute ended at $closedAt, and the funding rates whose
 * time is $closedAt (docs/engine.md, sections 1 and 8).
 */
final readonly class MinuteCandles
{
    /**
     * @param  array<string, Candle>  $candles  keyed by unified symbol, in symbol order
     * @param  array<string, FundingRate>  $funding  keyed by unified symbol, in symbol order
     */
    public function __construct(public CarbonImmutable $closedAt, public array $candles, public array $funding = []) {}
}
