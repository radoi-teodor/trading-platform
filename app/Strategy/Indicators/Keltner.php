<?php

declare(strict_types=1);

namespace App\Strategy\Indicators;

use App\Market\Data\Candle;
use App\Support\Number\Decimal;

/** Keltner channel: EMA(n) of the close ± k × ATR(n). */
final class Keltner implements BarIndicator
{
    private Ema $middle;

    private Atr $atr;

    public function __construct(public readonly int $period, private readonly Decimal $k)
    {
        $this->middle = new Ema($period);
        $this->atr = new Atr($period);
    }

    /** @return array{upper: ?Decimal, middle: ?Decimal, lower: ?Decimal} */
    public function push(Candle $bar): array
    {
        $middle = $this->middle->push($bar->close->value);
        $atr = $this->atr->push($bar);

        if ($middle === null || $atr === null) {
            return ['upper' => null, 'middle' => null, 'lower' => null];
        }

        return ['upper' => $middle->plus($this->k->times($atr)), 'middle' => $middle, 'lower' => $middle->minus($this->k->times($atr))];
    }
}
