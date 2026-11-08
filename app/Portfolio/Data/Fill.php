<?php

declare(strict_types=1);

namespace App\Portfolio\Data;

use App\Support\Number\Decimal;
use App\Support\Number\Money;
use App\Support\Number\Price;
use App\Support\Side;
use App\Support\Symbol;
use Carbon\CarbonImmutable;
use InvalidArgumentException;

/**
 * A fill as the ledger books it: what the venue reported, attributed to the strategy whose order it was.
 * $feeValue is the fee valued in the quote asset at fill time; $slippage is the cost against the reference price,
 * in quote. $initialStop is the planned stop of an entry; $exitReason the role of an exit.
 */
final readonly class Fill
{
    public function __construct(
        public string $id,
        public CarbonImmutable $time,
        public string $strategy,
        public Symbol $symbol,
        public Side $side,
        public Decimal $quantity,
        public Price $price,
        public Money $fee,
        public Decimal $feeValue,
        public Decimal $slippage,
        public ?Price $initialStop = null,
        public ?ExitReason $exitReason = null,
    ) {
        if (! $quantity->isPositive()) {
            throw new InvalidArgumentException("A fill needs a positive quantity, got {$quantity} in fill {$id}.");
        }

        if ($fee->amount->isNegative() || $feeValue->isNegative()) {
            throw new InvalidArgumentException("A fee cannot be negative, got {$fee} in fill {$id}.");
        }
    }

    public function notional(): Decimal
    {
        return $this->quantity->times($this->price->value);
    }
}
