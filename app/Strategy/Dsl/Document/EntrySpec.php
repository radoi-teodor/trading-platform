<?php

declare(strict_types=1);

namespace App\Strategy\Dsl\Document;

use App\Support\Side;

/** One side's entry: its condition and its order, null for a market order. */
final readonly class EntrySpec
{
    public function __construct(public Side $side, public Expression $when, public ?Expression $order) {}
}
