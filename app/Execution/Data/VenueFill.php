<?php

declare(strict_types=1);

namespace App\Execution\Data;

use App\Support\Number\Decimal;
use App\Support\Number\Money;
use App\Support\Number\Price;
use App\Support\Side;
use App\Support\Symbol;
use Carbon\CarbonImmutable;

/** A trade the venue reports. $slippage is its cost against the reference price, in quote. */
final readonly class VenueFill
{
    public function __construct(
        public string $id,
        public ClientOrderId $clientOrderId,
        public Symbol $symbol,
        public Side $side,
        public Decimal $quantity,
        public Price $price,
        public Money $fee,
        public Liquidity $liquidity,
        public Decimal $slippage,
        public CarbonImmutable $time,
    ) {}
}
