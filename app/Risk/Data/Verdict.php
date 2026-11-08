<?php

declare(strict_types=1);

namespace App\Risk\Data;

use App\Support\Number\Decimal;

/**
 * The risk engine's answer to an intent. An approved entry has a base quantity, or a quote amount for the benchmark
 * profile; $adjustments name every limit that resized it; $plannedRisk is the loss if the stop is hit.
 */
final readonly class Verdict
{
    /** @param list<string> $adjustments */
    private function __construct(
        public bool $approved,
        public ?Decimal $quantity,
        public ?Decimal $quoteQuantity,
        public ?Decimal $plannedRisk,
        public array $adjustments,
        public ?RejectionReason $rejection,
    ) {}

    /** @param list<string> $adjustments */
    public static function quantity(Decimal $quantity, ?Decimal $plannedRisk, array $adjustments): self
    {
        return new self(true, $quantity, null, $plannedRisk, $adjustments, null);
    }

    public static function quoteAmount(Decimal $quoteQuantity): self
    {
        return new self(true, null, $quoteQuantity, null, [], null);
    }

    public static function approved(): self
    {
        return new self(true, null, null, null, [], null);
    }

    public static function rejected(RejectionReason $reason): self
    {
        return new self(false, null, null, null, [], $reason);
    }
}
