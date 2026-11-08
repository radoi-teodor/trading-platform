<?php

declare(strict_types=1);

namespace App\Market\Data;

use App\Support\Number\Decimal;
use App\Support\Number\Price;
use Carbon\CarbonImmutable;

/** A funding rate of a perpetual, as a fraction per funding interval (0.0001 is 0.01 %). */
final readonly class FundingRate
{
    public function __construct(
        public CarbonImmutable $fundingTime,
        public Decimal $rate,
        public int $intervalHours,
        public ?Price $markPrice,
    ) {}
}
