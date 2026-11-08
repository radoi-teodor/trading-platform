<?php

declare(strict_types=1);

namespace App\Strategy\Data;

use App\Support\Number\Price;
use App\Support\Symbol;

/** Enter a long position at market, with the protective stop and optional target to place once it fills. */
final readonly class OpenLong implements Intent
{
    public function __construct(
        public Symbol $symbol,
        public ?Price $stop,
        public ?Price $target,
        public string $reason,
    ) {}

    public function symbol(): Symbol
    {
        return $this->symbol;
    }

    public function reason(): string
    {
        return $this->reason;
    }
}
