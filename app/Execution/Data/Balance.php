<?php

declare(strict_types=1);

namespace App\Execution\Data;

use App\Support\Asset;
use App\Support\Number\Decimal;

/** An asset balance on a venue: free to use, and locked by working orders. */
final readonly class Balance
{
    public function __construct(public Asset $asset, public Decimal $free, public Decimal $locked) {}

    public function total(): Decimal
    {
        return $this->free->plus($this->locked);
    }
}
