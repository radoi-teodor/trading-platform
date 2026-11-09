<?php

declare(strict_types=1);

namespace App\Portfolio\Data;

use App\Support\Number\Decimal;
use App\Support\Side;
use App\Support\Symbol;
use Carbon\CarbonImmutable;

/**
 * One position from its first entry to its close (docs/engine.md, section 6). Profits, fees and excursions are in
 * the quote asset. Net profit is quote received minus quote spent, so it holds no rounding; dust written off at the
 * close is not counted in it.
 */
final readonly class RoundTrip
{
    public function __construct(
        public string $strategy,
        public Symbol $symbol,
        public CarbonImmutable $openedAt,
        public CarbonImmutable $closedAt,
        public Decimal $quantity,
        public Decimal $entryPrice,
        public ?Decimal $exitPrice,
        public Decimal $grossProfit,
        public Decimal $fees,
        public Decimal $slippage,
        public Decimal $netProfit,
        public ?Decimal $initialRisk,
        public ?Decimal $rMultiple,
        public Decimal $maximumAdverseExcursion,
        public Decimal $maximumFavourableExcursion,
        public ?ExitReason $exitReason,
        public Decimal $dust,
        public Side $side = Side::Buy,
        public ?Decimal $funding = null,
    ) {}
}
