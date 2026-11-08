<?php

declare(strict_types=1);

namespace App\Execution\Data;

use App\Support\Symbol;
use Carbon\CarbonImmutable;

/** One call to a strategy (docs/engine.md, section 7): the candle it saw, its intents and their outcome. */
final readonly class DecisionRecord
{
    /** @param list<IntentRecord> $intents */
    public function __construct(
        public string $id,
        public CarbonImmutable $time,
        public string $strategy,
        public Symbol $symbol,
        public string $close,
        public array $intents,
    ) {}
}
