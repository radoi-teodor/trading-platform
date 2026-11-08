<?php

declare(strict_types=1);

namespace App\Strategy\Dsl\Document;

use App\Market\Data\MarketType;
use App\Support\Number\Decimal;
use App\Support\Symbol;
use App\Support\Timeframe;

/** A strategy document that passed every check: what the compiler builds a strategy from. */
final readonly class StrategySpec
{
    /**
     * @param  array<string, ParameterSpec>  $params
     * @param  list<Symbol>  $instruments
     * @param  list<Expression>  $filters
     * @param  array<string, EntrySpec>  $entries  by side, long then short
     */
    public function __construct(
        public string $id,
        public string $title,
        public string $family,
        public string $hypothesis,
        public string $failsWhen,
        public bool $benchmark,
        public ?CarryTemplate $template,
        public array $params,
        public MarketType $market,
        public array $instruments,
        public Timeframe $timeframe,
        public array $filters,
        public array $entries,
        public ExitSpec $exits,
        public ?Decimal $riskPerTrade,
        public ?Decimal $maxPosition,
        public ?int $maxOpenPositions,
        public ?int $maxTradesPerDay,
        public ?int $cooldownSeconds,
        public ?int $maxBriefAgeSeconds,
    ) {}
}
