<?php

declare(strict_types=1);

namespace App\Strategy\Indicators;

use App\Support\Number\Decimal;

/** Wilder's average: the mean of the first n values, then (previous × (n - 1) + value) / n. */
final class Wilder
{
    /** @var list<Decimal> */
    private array $first = [];

    private ?Decimal $value = null;

    public function __construct(public readonly int $period)
    {
        Period::check($period);
    }

    public function push(Decimal $value): ?Decimal
    {
        if ($this->value !== null) {
            return $this->value = $this->value->times(Decimal::of($this->period - 1))->plus($value)->dividedBy(Decimal::of($this->period), 18);
        }

        $this->first[] = $value;

        if (count($this->first) === $this->period) {
            $sum = Decimal::zero();

            foreach ($this->first as $item) {
                $sum = $sum->plus($item);
            }

            $this->value = $sum->dividedBy(Decimal::of($this->period), 18);
        }

        return $this->value;
    }
}
