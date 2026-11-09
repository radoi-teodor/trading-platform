<?php

declare(strict_types=1);

namespace App\Support\Number;

use Stringable;

/** A non-negative quantity of the base asset. Rounded only downwards: never buy or sell more than intended. */
final readonly class Quantity implements Stringable
{
    private function __construct(public Decimal $value) {}

    public static function of(string|int|Decimal $value): self
    {
        $decimal = $value instanceof Decimal ? $value : Decimal::of($value);

        if ($decimal->isNegative()) {
            throw new InvalidNumber("A quantity cannot be negative, got '{$decimal}'.");
        }

        return new self($decimal);
    }

    public function roundDownToStep(Decimal $stepSize): self
    {
        return self::of($this->value->roundDownToMultipleOf($stepSize));
    }

    public function isZero(): bool
    {
        return $this->value->isZero();
    }

    public function __toString(): string
    {
        return (string) $this->value;
    }
}
