<?php

declare(strict_types=1);

namespace App\Strategy\Indicators;

use App\Market\Data\Candle;
use App\Support\Number\Decimal;

/**
 * Supertrend: bands at (high + low) / 2 ± k × ATR(n). The upper band only falls and the lower only rises unless the
 * previous close broke through them. Direction starts up (1) and turns down (-1) on a close under the lower band,
 * up again on a close above the upper one; the line is the lower band going up, the upper going down.
 */
final class Supertrend implements BarIndicator
{
    private Atr $atr;

    private ?Decimal $upper = null;

    private ?Decimal $lower = null;

    private int $direction = 1;

    private ?Decimal $previousClose = null;

    public function __construct(public readonly int $period, private readonly Decimal $k)
    {
        $this->atr = new Atr($period);
    }

    /** @return array{line: ?Decimal, direction: ?Decimal} */
    public function push(Candle $bar): array
    {
        $atr = $this->atr->push($bar);
        $previousClose = $this->previousClose;
        $this->previousClose = $bar->close->value;

        if ($atr === null) {
            return ['line' => null, 'direction' => null];
        }

        $middle = $bar->high->value->plus($bar->low->value)->dividedBy(Decimal::of(2), 18);
        $basicUpper = $middle->plus($this->k->times($atr));
        $basicLower = $middle->minus($this->k->times($atr));

        if ($this->upper === null || $this->lower === null || $previousClose === null) {
            $this->upper = $basicUpper;
            $this->lower = $basicLower;
        } else {
            $this->upper = $basicUpper->isLessThan($this->upper) || $previousClose->isGreaterThan($this->upper) ? $basicUpper : $this->upper;
            $this->lower = $basicLower->isGreaterThan($this->lower) || $previousClose->isLessThan($this->lower) ? $basicLower : $this->lower;

            if ($this->direction === 1 && $bar->close->value->isLessThan($this->lower)) {
                $this->direction = -1;
            } elseif ($this->direction === -1 && $bar->close->value->isGreaterThan($this->upper)) {
                $this->direction = 1;
            }
        }

        return ['line' => $this->direction === 1 ? $this->lower : $this->upper, 'direction' => Decimal::of($this->direction)];
    }
}
