<?php

declare(strict_types=1);

namespace App\Strategy\Indicators;

use App\Support\Number\Decimal;

/** The last n values pushed, oldest first. */
final class Window
{
    /** @var list<Decimal> */
    private array $values = [];

    public function __construct(public readonly int $size)
    {
        Period::check($size);
    }

    public function push(Decimal $value): void
    {
        $this->values[] = $value;

        if (count($this->values) > $this->size) {
            array_shift($this->values);
        }
    }

    public function isFull(): bool
    {
        return count($this->values) === $this->size;
    }

    /** @return list<Decimal> */
    public function values(): array
    {
        return $this->values;
    }

    public function sum(): Decimal
    {
        $sum = Decimal::zero();

        foreach ($this->values as $value) {
            $sum = $sum->plus($value);
        }

        return $sum;
    }

    public function max(): Decimal
    {
        $max = $this->values[0];

        foreach ($this->values as $value) {
            $max = $value->isGreaterThan($max) ? $value : $max;
        }

        return $max;
    }

    public function min(): Decimal
    {
        $min = $this->values[0];

        foreach ($this->values as $value) {
            $min = $value->isLessThan($min) ? $value : $min;
        }

        return $min;
    }

    /** The oldest value kept: n - 1 pushes back once full. */
    public function first(): Decimal
    {
        return $this->values[0];
    }
}
