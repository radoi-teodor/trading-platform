<?php

declare(strict_types=1);

namespace App\Market\Data;

use App\Support\Number\Decimal;
use Carbon\CarbonImmutable;

/**
 * A perpetual's liquidations over the minute opening at $openTime (D-91): long positions closed by the exchange (its
 * sells) and short ones (its buys), counted and in quote notional. A lower bound: Binance streams one per symbol per
 * second.
 */
final readonly class Liquidations
{
    public function __construct(
        public CarbonImmutable $openTime,
        public int $longCount,
        public Decimal $longNotional,
        public int $shortCount,
        public Decimal $shortNotional,
    ) {}

    public static function none(CarbonImmutable $openTime): self
    {
        return new self($openTime, 0, Decimal::zero(), 0, Decimal::zero());
    }
}
