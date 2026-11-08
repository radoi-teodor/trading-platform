<?php

declare(strict_types=1);

namespace App\Risk\Data;

use App\Support\Number\Decimal;

/**
 * The account as the risk engine needs it at a decision, built by the engine from the ledger: equity and free
 * quote, the number of open positions, the open risk (the loss if every stop is hit) and whether the deciding
 * strategy already holds the instrument.
 */
final readonly class AccountView
{
    public function __construct(
        public Decimal $equity,
        public Decimal $freeQuote,
        public int $openPositions,
        public Decimal $openRisk,
        public bool $holdsInstrument,
    ) {}
}
