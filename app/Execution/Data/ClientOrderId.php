<?php

declare(strict_types=1);

namespace App\Execution\Data;

use InvalidArgumentException;
use Stringable;

/** The id we give an order, derived from the decision that made it, so that a retry never doubles it (Binance: 36 characters at most). */
final readonly class ClientOrderId implements Stringable
{
    private function __construct(public string $value) {}

    public static function of(string $value): self
    {
        if (preg_match('/^[.A-Za-z0-9:\/_-]{1,36}$/', $value) !== 1) {
            throw new InvalidArgumentException("Not a valid client order id: '{$value}'.");
        }

        return new self($value);
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
