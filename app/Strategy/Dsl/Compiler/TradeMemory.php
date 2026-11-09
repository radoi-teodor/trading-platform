<?php

declare(strict_types=1);

namespace App\Strategy\Dsl\Compiler;

use App\Support\Number\Decimal;
use App\Support\Side;

/** What the strategy remembers of the entry it asked for on one instrument: its side and initial stop, for trade_r. */
final class TradeMemory
{
    public function __construct(public Side $side, public ?Decimal $initialStop) {}
}
