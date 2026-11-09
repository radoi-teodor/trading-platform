<?php

declare(strict_types=1);

namespace App\Strategy\Data;

use App\Support\Symbol;

/** Leave the position in this instrument at market. Never blocked by risk. */
final readonly class Close implements Intent
{
    public function __construct(public Symbol $symbol, public string $reason) {}

    public function symbol(): Symbol
    {
        return $this->symbol;
    }

    public function reason(): string
    {
        return $this->reason;
    }
}
