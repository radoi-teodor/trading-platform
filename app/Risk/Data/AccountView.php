<?php

declare(strict_types=1);

namespace App\Risk\Data;

use App\Support\Number\Decimal;
use Carbon\CarbonImmutable;

/**
 * The account as the risk engine needs it at a decision, built by the engine from the ledger: equity and free
 * quote, the number of open positions, the open risk (the loss if every stop is hit), whether the deciding
 * strategy already holds the instrument, the free USD-M wallet, and the gross and signed net notional of every
 * position, in quote.
 */
final readonly class AccountView
{
    public function __construct(
        public Decimal $equity,
        public Decimal $freeQuote,
        public int $openPositions,
        public Decimal $openRisk,
        public bool $holdsInstrument,
        public ?Decimal $freeMargin = null,
        public ?Decimal $grossExposure = null,
        public ?Decimal $netExposure = null,
        public int $strategyPositions = 0,
        public int $entriesToday = 0,
        public ?CarbonImmutable $lastLossAt = null,
    ) {}
}
