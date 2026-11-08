<?php

declare(strict_types=1);

namespace App\Market\Contracts;

use App\Market\Data\MarketType;
use App\Market\Data\MinuteCandles;
use App\Support\Symbol;
use Carbon\CarbonImmutable;

/** Closed 1-minute candles in event order: by time, then by symbol. */
interface CandleFeed
{
    /**
     * Minutes whose candles open in [$from, $to).
     *
     * @param  list<Symbol>  $symbols
     * @return iterable<MinuteCandles>
     */
    public function minutes(MarketType $market, array $symbols, CarbonImmutable $from, CarbonImmutable $to): iterable;
}
