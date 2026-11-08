<?php

declare(strict_types=1);

namespace App\Support\Number;

use BcMath\Number;
use Stringable;

/**
 * An exact decimal number (D-11). Wraps BcMath\Number so that amounts never pass through float, and so that the
 * rules the platform needs (plain decimal input only, explicit scale on division, rounding to a step) live in
 * one place.
 */
final readonly class Decimal implements Stringable
{
    private const string PLAIN_DECIMAL = '/^-?\d+(\.\d+)?$/';

    private function __construct(private Number $number) {}

    public static function of(string|int $value): self
    {
        if (is_string($value) && (! is_numeric($value) || preg_match(self::PLAIN_DECIMAL, $value) !== 1)) {
            throw new InvalidNumber("Not a plain decimal number: '{$value}'.");
        }

        return new self(new Number($value));
    }

    public static function zero(): self
    {
        return new self(new Number(0));
    }

    public function plus(self $other): self
    {
        return new self($this->number->add($other->number));
    }

    public function minus(self $other): self
    {
        return new self($this->number->sub($other->number));
    }

    public function times(self $other): self
    {
        return new self($this->number->mul($other->number));
    }

    /** Division needs an explicit scale; the result is truncated towards zero. */
    public function dividedBy(self $divisor, int $scale): self
    {
        if ($divisor->isZero()) {
            throw new InvalidNumber('Division by zero.');
        }

        return new self($this->number->div($divisor->number, $scale));
    }

    public function negate(): self
    {
        return new self((new Number(0))->sub($this->number));
    }

    public function abs(): self
    {
        return $this->isNegative() ? $this->negate() : $this;
    }

    /** -1, 0 or 1, comparing values whatever their scale. */
    public function compare(self $other): int
    {
        return $this->number->compare($other->number);
    }

    public function equals(self $other): bool
    {
        return $this->compare($other) === 0;
    }

    public function isGreaterThan(self $other): bool
    {
        return $this->compare($other) === 1;
    }

    public function isLessThan(self $other): bool
    {
        return $this->compare($other) === -1;
    }

    public function isZero(): bool
    {
        return $this->number->compare(0) === 0;
    }

    public function isNegative(): bool
    {
        return $this->number->compare(0) === -1;
    }

    public function isPositive(): bool
    {
        return $this->number->compare(0) === 1;
    }

    /** The largest multiple of $step that is not greater than this number. */
    public function roundDownToMultipleOf(self $step): self
    {
        $candidate = $this->truncatedMultipleOf($step);

        return $candidate->isGreaterThan($this) ? $candidate->minus($step) : $candidate;
    }

    /** The smallest multiple of $step that is not less than this number. */
    public function roundUpToMultipleOf(self $step): self
    {
        $candidate = $this->truncatedMultipleOf($step);

        return $candidate->isLessThan($this) ? $candidate->plus($step) : $candidate;
    }

    /** Plain notation without trailing zeros: "1.2300" becomes "1.23", "-0.000" becomes "0". */
    public function __toString(): string
    {
        $text = (string) $this->number;

        if (str_contains($text, '.')) {
            $text = rtrim(rtrim($text, '0'), '.');
        }

        return $text === '-0' ? '0' : $text;
    }

    private function truncatedMultipleOf(self $step): self
    {
        if (! $step->isPositive()) {
            throw new InvalidNumber("A rounding step must be positive, got '{$step}'.");
        }

        return new self($this->number->div($step->number, 0)->mul($step->number));
    }
}
