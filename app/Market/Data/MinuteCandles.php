<?php

declare(strict_types=1);

namespace App\Market\Data;

use Carbon\CarbonImmutable;

/** The 1-minute candles of every followed instrument whose minute ended at $closedAt (docs/engine.md, section 1). */
final readonly class MinuteCandles
{
    /** @param array<string, Candle> $candles keyed by unified symbol, in symbol order */
    public function __construct(public CarbonImmutable $closedAt, public array $candles) {}
}
