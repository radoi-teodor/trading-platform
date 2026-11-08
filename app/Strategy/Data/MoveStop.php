<?php

declare(strict_types=1);

namespace App\Strategy\Data;

use App\Support\Number\Price;
use App\Support\Symbol;

/** Move the protective stop of the position in this instrument; only towards the price (docs/risk-policy.md, section 1). */
final readonly class MoveStop implements Intent
{
    public function __construct(public Symbol $symbol, public Price $stop, public string $reason) {}

    public function symbol(): Symbol
    {
        return $this->symbol;
    }

    public function reason(): string
    {
        return $this->reason;
    }
}
