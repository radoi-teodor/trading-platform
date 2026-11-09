<?php

declare(strict_types=1);

namespace App\Market\Data;

use App\Support\Number\Decimal;
use App\Support\Symbol;
use Carbon\CarbonImmutable;

/** An instrument as the exchange describes it, with the filters that orders must respect. */
final readonly class InstrumentDefinition
{
    /**
     * @param  array<string, mixed>  $rawFilters  every filter as the exchange sent it, keyed by filter type
     * @param  CarbonImmutable|null  $listedAt  when the contract was listed, where the exchange says (D-89)
     * @param  CarbonImmutable|null  $delistedAt  when a settled contract stopped trading
     * @param  string|null  $underlyingType  what the contract is on, as the exchange names it (COIN, INDEX, ...)
     */
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
        public ?CarbonImmutable $listedAt = null,
        public ?CarbonImmutable $delistedAt = null,
        public ?string $underlyingType = null,
    ) {}
}
