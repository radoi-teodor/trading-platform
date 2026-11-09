<?php

declare(strict_types=1);

namespace App\Execution\Data;

/** One intent of a decision and what became of it. */
final readonly class IntentRecord
{
    /**
     * @param  list<string>  $adjustments
     * @param  list<string>  $orders  client order ids
     */
    public function __construct(
        public string $type,
        public string $reason,
        public bool $approved,
        public ?string $rejection,
        public ?string $quantity,
        public ?string $quoteQuantity,
        public array $adjustments,
        public array $orders,
    ) {}
}
