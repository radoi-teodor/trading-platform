<?php

declare(strict_types=1);

namespace App\Strategy\Indicators;

use App\Support\Number\Decimal;

/** Simple moving average of the last n values, kept with a running sum. Divisions truncate at 18 decimals. */
final class Sma
{
    /** @var list<Decimal> */
    private array $window = [];

    private Decimal $sum;

    private ?Decimal $value = null;

    public function __construct(public readonly int $period)
    {
        Period::check($period);
        $this->sum = Decimal::zero();
    }

    public function push(Decimal $value): ?Decimal
    {
        $this->window[] = $value;
        $this->sum = $this->sum->plus($value);

        if (count($this->window) > $this->period) {
            $this->sum = $this->sum->minus(array_shift($this->window));
        }

        if (count($this->window) === $this->period) {
            $this->value = $this->sum->dividedBy(Decimal::of($this->period), 18);
        }

        return $this->value;
    }

    public function value(): ?Decimal
    {
        return $this->value;
    }
}
