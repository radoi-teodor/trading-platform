<?php

declare(strict_types=1);

namespace App\Execution\Data;

use Carbon\CarbonImmutable;

/** One change of an order's state, numbered in venue order; a fill comes with the status it led to. */
final readonly class OrderUpdate
{
    public function __construct(
        public int $sequence,
        public ClientOrderId $clientOrderId,
        public OrderStatus $status,
        public CarbonImmutable $time,
        public ?VenueFill $fill = null,
    ) {}
}
