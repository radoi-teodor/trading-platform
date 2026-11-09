<?php

declare(strict_types=1);

namespace App\Strategy\Data;

use App\Support\Number\Decimal;
use InvalidArgumentException;

/**
 * A limit entry (D-55): the limit is the decision price moved by $offset (a fraction: -0.001 is 0.1 % under it), and
 * the order works for $expirySeconds before it is cancelled.
 */
final readonly class EntryOrder
{
    public function __construct(public Decimal $offset, public int $expirySeconds)
    {
        if ($expirySeconds < 60) {
            throw new InvalidArgumentException("A limit entry works at least a minute, got {$expirySeconds} s.");
        }
    }
}
