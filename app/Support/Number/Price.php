<?php

declare(strict_types=1);

namespace App\Support\Number;

use Stringable;

/** A strictly positive price, in the quote asset of its instrument. */
final readonly class Price implements Stringable
{
    private function __construct(public Decimal $value) {}

    public static function of(string|int|Decimal $value): self
    {
        $decimal = $value instanceof Decimal ? $value : Decimal::of($value);

        if (! $decimal->isPositive()) {
            throw new InvalidNumber("A price must be positive, got '{$decimal}'.");
        }

        return new self($decimal);
    }

    public function roundDownToTick(Decimal $tickSize): self
    {
        return self::of($this->value->roundDownToMultipleOf($tickSize));
    }

    public function roundUpToTick(Decimal $tickSize): self
    {
        return self::of($this->value->roundUpToMultipleOf($tickSize));
    }

    public function __toString(): string
    {
        return (string) $this->value;
    }
}
