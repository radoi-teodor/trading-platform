<?php

declare(strict_types=1);

namespace App\Market\Contracts;

use App\Market\Data\MinuteCandles;
use App\Support\Symbol;
use Carbon\CarbonImmutable;

/**
 * Closed 1-minute candles in event order: by time, then by symbol, then funding. Symbols of spot and perpetual
 * markets may be mixed; each symbol's market follows from its form (BTC/USDT or BTC/USDT:USDT).
 */
interface CandleFeed
{
    /**
     * Minutes whose candles open in [$from, $to).
     *
     * @param  list<Symbol>  $symbols
     * @return iterable<MinuteCandles>
     */
    public function minutes(array $symbols, CarbonImmutable $from, CarbonImmutable $to): iterable;
}
