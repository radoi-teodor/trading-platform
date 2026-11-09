<?php

declare(strict_types=1);

namespace App\Market\Data;

use Carbon\CarbonImmutable;

/**
 * The 1-minute candles of every followed instrument whose minute ended at $closedAt, the funding rates whose time is
 * $closedAt (docs/engine.md, sections 1 and 8), and for perpetuals the data for scalping known at $closedAt (D-79):
 * the minute's trade flow, mark price and premium index, and the open interest that became known then.
 */
final readonly class MinuteCandles
{
    /**
     * @param  array<string, Candle>  $candles  keyed by unified symbol, in symbol order
     * @param  array<string, FundingRate>  $funding  keyed by unified symbol, in symbol order
     * @param  array<string, TradeFlow>  $flow  the minute's trade flow, by symbol
     * @param  array<string, PriceMinute>  $marks  the minute's mark price, by symbol
     * @param  array<string, PriceMinute>  $premium  the minute's premium index, by symbol
     * @param  array<string, OpenInterest>  $openInterest  open interest known from $closedAt (its time plus 5 minutes), by symbol
     */
    public function __construct(
        public CarbonImmutable $closedAt,
        public array $candles,
        public array $funding = [],
        public array $flow = [],
        public array $marks = [],
        public array $premium = [],
        public array $openInterest = [],
    ) {}
}
