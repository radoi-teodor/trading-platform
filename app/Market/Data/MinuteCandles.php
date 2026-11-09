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
     * @param  array<string, Positioning>  $positioning  positioning known from $closedAt (its time plus 5 minutes), by symbol (D-91)
     * @param  array<string, BookDepth>  $depth  order book depth known from $closedAt (its 5 minutes over), by symbol (D-91)
     * @param  array<string, Liquidations>  $liquidations  the minute's liquidations, by symbol, only for minutes the stream heard whole (D-91)
     */
    public function __construct(
        public CarbonImmutable $closedAt,
        public array $candles,
        public array $funding = [],
        public array $flow = [],
        public array $marks = [],
        public array $premium = [],
        public array $openInterest = [],
        public array $positioning = [],
        public array $depth = [],
        public array $liquidations = [],
    ) {}

    /**
     * The minute as one who follows only $symbols sees it: every part of it without the other instruments. A minute
     * that has nothing of another instrument is given back as it is.
     *
     * @param  array<string, true>  $symbols  by unified symbol
     */
    public function only(array $symbols): self
    {
        $parts = [
            $this->candles, $this->funding, $this->flow, $this->marks, $this->premium, $this->openInterest, $this->positioning, $this->depth,
            $this->liquidations,
        ];

        foreach ($parts as $part) {
            if (array_diff_key($part, $symbols) !== []) {
                return new self(
                    $this->closedAt,
                    array_intersect_key($this->candles, $symbols),
                    array_intersect_key($this->funding, $symbols),
                    array_intersect_key($this->flow, $symbols),
                    array_intersect_key($this->marks, $symbols),
                    array_intersect_key($this->premium, $symbols),
                    array_intersect_key($this->openInterest, $symbols),
                    array_intersect_key($this->positioning, $symbols),
                    array_intersect_key($this->depth, $symbols),
                    array_intersect_key($this->liquidations, $symbols),
                );
            }
        }

        return $this;
    }
}
