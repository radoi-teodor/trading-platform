<?php

declare(strict_types=1);

namespace App\Support;

use InvalidArgumentException;
use Stringable;

/** An asset code such as BTC, USDT or 1000PEPE, always upper case. */
final readonly class Asset implements Stringable
{
    private function __construct(public string $code) {}

    public static function of(string $code): self
    {
        $normalized = strtoupper($code);

        if (preg_match('/^[A-Z0-9]{1,20}$/', $normalized) !== 1) {
            throw new InvalidArgumentException("Not an asset code: '{$code}'.");
        }

        return new self($normalized);
    }

    public function equals(self $other): bool
    {
        return $this->code === $other->code;
    }

    public function __toString(): string
    {
        return $this->code;
    }
}
