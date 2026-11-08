<?php

declare(strict_types=1);

namespace App\Strategy\Indicators;

use App\Support\Number\Decimal;

/** Exponential moving average with alpha 2 / (n + 1), seeded with the simple average of its first n values. */
final class Ema implements ValueIndicator
{
    private Sma $seed;

    private Decimal $alpha;

    private ?Decimal $value = null;

    public function __construct(public readonly int $period)
    {
        $this->seed = new Sma(Period::check($period));
        $this->alpha = Decimal::of(2)->dividedBy(Decimal::of($period + 1), 18);
    }

    public function push(Decimal $value): ?Decimal
    {
        $this->value = $this->value === null
            ? $this->seed->push($value)
            : $this->value->plus($this->alpha->times($value->minus($this->value)))->truncatedTo(18);

        return $this->value;
    }

    public function value(): ?Decimal
    {
        return $this->value;
    }
}
