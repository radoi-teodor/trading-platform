<?php

declare(strict_types=1);

namespace App\Strategy\Indicators;

use App\Market\Data\Candle;
use App\Support\Number\Decimal;

/**
 * Stochastic oscillator: %K = 100 × (close - lowest low) / (highest high - lowest low) over k candles, 50 when the
 * range is empty; %D = SMA(d) of %K.
 */
final class Stochastic implements BarIndicator
{
    private Window $highs;

    private Window $lows;

    private Sma $d;

    public function __construct(int $k, int $d)
    {
        $this->highs = new Window($k);
        $this->lows = new Window($k);
        $this->d = new Sma($d);
    }

    /** @return array{k: ?Decimal, d: ?Decimal} */
    public function push(Candle $bar): array
    {
        $this->highs->push($bar->high->value);
        $this->lows->push($bar->low->value);

        if (! $this->highs->isFull()) {
            return ['k' => null, 'd' => null];
        }

        $high = $this->highs->max();
        $low = $this->lows->min();
        $k = $high->equals($low)
            ? Decimal::of(50)
            : Decimal::of(100)->times($bar->close->value->minus($low))->dividedBy($high->minus($low), 18);

        return ['k' => $k, 'd' => $this->d->push($k)];
    }
}
