<?php

declare(strict_types=1);

namespace App\Strategy\Data;

use App\Support\Number\Price;
use App\Support\Symbol;

/** Enter a short position at market on a perpetual, with the stop above and the optional target below (D-50). */
final readonly class OpenShort implements Intent
{
    public function __construct(
        public Symbol $symbol,
        public ?Price $stop,
        public ?Price $target,
        public string $reason,
        public ?EntryOrder $order = null,
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
