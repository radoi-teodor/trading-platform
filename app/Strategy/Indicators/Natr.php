<?php

declare(strict_types=1);

namespace App\Strategy\Indicators;

use App\Market\Data\Candle;
use App\Support\Number\Decimal;

/** Normalized ATR: 100 × ATR(n) / close. */
final class Natr
{
    private Atr $atr;

    public function __construct(public readonly int $period)
    {
        $this->atr = new Atr($period);
    }

    public function push(Candle $bar): ?Decimal
    {
        $atr = $this->atr->push($bar);

        return $atr === null ? null : Decimal::of(100)->times($atr)->dividedBy($bar->close->value, 18);
    }
}
