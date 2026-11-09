<?php

declare(strict_types=1);

namespace App\Strategy\Data;

use App\Support\Number\Decimal;

/**
 * What a strategy reads of a perpetual's data for scalping when one of its candles closes (D-79): the latest mark
 * price and premium index, the latest open interest known, and the large-trade flow summed over the candle. A value
 * is null when no data is stored for it.
 */
final readonly class PerpetualView
{
    public function __construct(
        public ?Decimal $mark = null,
        public ?Decimal $basis = null,
        public ?Decimal $openInterest = null,
        public ?Decimal $largeBuyVolume = null,
        public ?Decimal $largeSellVolume = null,
    ) {}
}
