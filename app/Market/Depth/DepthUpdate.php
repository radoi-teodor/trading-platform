<?php

declare(strict_types=1);

namespace App\Market\Depth;

use App\Support\Number\Decimal;
use Carbon\CarbonImmutable;

/**
 * One diff of a USD-M order book from <symbol>@depth (D-91): the levels that changed between update ids $first and
 * $last, following $previous; a quantity of zero removes the level.
 */
final readonly class DepthUpdate
{
    /**
     * @param  list<array{Decimal, Decimal}>  $bids  price and quantity
     * @param  list<array{Decimal, Decimal}>  $asks  price and quantity
     */
    public function __construct(
        public string $venueSymbol,
        public CarbonImmutable $time,
        public int $first,
        public int $last,
        public int $previous,
        public array $bids,
        public array $asks,
    ) {}
}
