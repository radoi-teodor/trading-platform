<?php

declare(strict_types=1);

namespace App\Execution\Data;

use App\Support\Number\Decimal;
use InvalidArgumentException;

/** Commission rates as fractions (0.001 is 0.1 %), read from the account or the defaults of D-28. */
final readonly class FeeSchedule
{
    public function __construct(public Decimal $maker, public Decimal $taker)
    {
        if ($maker->isNegative() || $taker->isNegative()) {
            throw new InvalidArgumentException("Fee rates cannot be negative, got {$maker} and {$taker}.");
        }
    }

    public function rate(Liquidity $liquidity): Decimal
    {
        return $liquidity === Liquidity::Maker ? $this->maker : $this->taker;
    }
}
