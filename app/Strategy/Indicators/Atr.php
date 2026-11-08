<?php

declare(strict_types=1);

namespace App\Strategy\Indicators;

use App\Market\Data\Candle;
use App\Support\Number\Decimal;

/**
 * Average true range with Wilder's smoothing: the first value is the mean of the first n true ranges, then
 * (previous × (n - 1) + true range) / n. The first bar's true range is its high minus its low.
 */
final class Atr implements BarIndicator
{
    private ?Decimal $previousClose = null;

    /** @var list<Decimal> */
    private array $first = [];

    private ?Decimal $value = null;

    public function __construct(public readonly int $period)
    {
        Period::check($period);
    }

    public function push(Candle $bar): ?Decimal
    {
        $high = $bar->high->value;
        $low = $bar->low->value;
        $range = $high->minus($low);

        if ($this->previousClose !== null) {
            foreach ([$high->minus($this->previousClose)->abs(), $low->minus($this->previousClose)->abs()] as $gap) {
                $range = $gap->isGreaterThan($range) ? $gap : $range;
            }
        }

        $this->previousClose = $bar->close->value;
        $n = Decimal::of($this->period);

        if ($this->value !== null) {
            $this->value = $this->value->times(Decimal::of($this->period - 1))->plus($range)->dividedBy($n, 18);

            return $this->value;
        }

        $this->first[] = $range;

        if (count($this->first) === $this->period) {
            $sum = array_reduce($this->first, fn (Decimal $carry, Decimal $tr): Decimal => $carry->plus($tr), Decimal::zero());
            $this->value = $sum->dividedBy($n, 18);
        }

        return $this->value;
    }

    public function value(): ?Decimal
    {
        return $this->value;
    }
}
