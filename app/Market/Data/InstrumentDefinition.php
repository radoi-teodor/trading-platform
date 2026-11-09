<?php

declare(strict_types=1);

namespace App\Market\Data;

use App\Support\Number\Decimal;
use App\Support\Symbol;

/** An instrument as the exchange describes it, with the filters that orders must respect. */
final readonly class InstrumentDefinition
{
    /** @param array<string, mixed> $rawFilters every filter as the exchange sent it, keyed by filter type */
    public function __construct(
        public MarketType $market,
        public Symbol $symbol,
        public InstrumentStatus $status,
        public Decimal $tickSize,
        public Decimal $stepSize,
        public Decimal $minQty,
        public ?Decimal $maxQty,
        public ?Decimal $minNotional,
        public array $rawFilters,
    ) {}
}
