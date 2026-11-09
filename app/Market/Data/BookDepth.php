<?php

declare(strict_types=1);

namespace App\Market\Data;

use App\Support\Number\Decimal;
use Carbon\CarbonImmutable;
use InvalidArgumentException;

/**
 * The order book of a perpetual over 5 minutes from $time (D-91): the quote notional resting within 1, 2 and 5 % of
 * the price, bids below and asks above, as the last snapshot of those 5 minutes showed it. Known at their end.
 */
final readonly class BookDepth
{
    public const array BANDS = [1, 2, 5];

    public function __construct(
        public CarbonImmutable $time,
        public Decimal $bid1,
        public Decimal $bid2,
        public Decimal $bid5,
        public Decimal $ask1,
        public Decimal $ask2,
        public Decimal $ask5,
    ) {}

    public function knownAt(): CarbonImmutable
    {
        return $this->time->addSeconds(OpenInterest::KNOWN_AFTER_SECONDS);
    }

    /** Bid notional within $percent % below the price. */
    public function bid(int $percent): Decimal
    {
        return match ($percent) {
            1 => $this->bid1, 2 => $this->bid2, 5 => $this->bid5,
            default => throw new InvalidArgumentException("Depth is kept within 1, 2 or 5 %, not {$percent}."),
        };
    }

    /** Ask notional within $percent % above the price. */
    public function ask(int $percent): Decimal
    {
        return match ($percent) {
            1 => $this->ask1, 2 => $this->ask2, 5 => $this->ask5,
            default => throw new InvalidArgumentException("Depth is kept within 1, 2 or 5 %, not {$percent}."),
        };
    }
}
