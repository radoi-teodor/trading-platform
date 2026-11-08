<?php

declare(strict_types=1);

namespace App\Support\Number;

/** A percentage held as its fraction: 0.5 % is stored as 0.005 (docs/conventions.md, section 3). */
final readonly class Percent
{
    private function __construct(public Decimal $fraction) {}

    public static function fromFraction(Decimal $fraction): self
    {
        return new self($fraction);
    }

    public static function fromPercentage(string|int $percentage): self
    {
        // Multiplying by 0.01 is exact; dividing by 100 would need a scale and could cut digits.
        return new self(Decimal::of($percentage)->times(Decimal::of('0.01')));
    }

    public function percentage(): Decimal
    {
        return $this->fraction->times(Decimal::of(100));
    }

    public function of(Decimal $amount): Decimal
    {
        return $amount->times($this->fraction);
    }

    public function equals(self $other): bool
    {
        return $this->fraction->equals($other->fraction);
    }
}
