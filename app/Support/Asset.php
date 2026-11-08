<?php

declare(strict_types=1);

namespace App\Support;

use InvalidArgumentException;
use Stringable;

/** An asset code such as BTC, USDT, 1000PEPE or 币安人生: letters and digits of any script, upper case where the script has case. */
final readonly class Asset implements Stringable
{
    private function __construct(public string $code) {}

    public static function of(string $code): self
    {
        $normalized = mb_strtoupper($code, 'UTF-8');

        if (preg_match('/^[\p{L}\p{N}]{1,20}$/u', $normalized) !== 1) {
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
