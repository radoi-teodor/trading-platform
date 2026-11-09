<?php

declare(strict_types=1);

namespace App\Strategy\Dsl\Document;

use App\Support\Number\Decimal;

/** The exits of a document; a null stop is the benchmark's stop_loss: none (D-56). */
final readonly class ExitSpec
{
    public function __construct(
        public ?Expression $stopLoss,
        public ?Decimal $takeProfit,
        public ?Expression $trailing,
        public ?Expression $timeStop,
        public ?Expression $when,
    ) {}
}
