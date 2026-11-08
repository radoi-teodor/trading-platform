<?php

declare(strict_types=1);

namespace App\Market\Data;

/**
 * Where a candle or funding rate came from. The value is its rank: a higher-ranked source overwrites a lower one,
 * never the reverse (docs/market-data.md, section 2).
 */
enum CandleSource: int
{
    case Stream = 1;
    case Rest = 2;
    case Archive = 3;

    public function outranks(self $other): bool
    {
        return $this->value > $other->value;
    }
}
