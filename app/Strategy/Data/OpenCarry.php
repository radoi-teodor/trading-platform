<?php

declare(strict_types=1);

namespace App\Strategy\Data;

use App\Support\Number\Decimal;
use App\Support\Symbol;

/**
 * Buy $spot and sell $perpetual for the same quantity: a hedged pair without a stop (D-48). $maxPosition is the
 * template's share of equity per leg, $maxLeverage and $marginBuffer its perpetual leg's limits.
 */
final readonly class OpenCarry implements Intent
{
    public function __construct(
        public Symbol $spot,
        public Symbol $perpetual,
        public Decimal $maxPosition,
        public int $maxLeverage,
        public Decimal $marginBuffer,
        public string $reason,
    ) {}

    public function symbol(): Symbol
    {
        return $this->perpetual;
    }

    public function reason(): string
    {
        return $this->reason;
    }
}
