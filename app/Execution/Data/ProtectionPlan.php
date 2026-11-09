<?php

declare(strict_types=1);

namespace App\Execution\Data;

use App\Support\Number\Decimal;
use App\Support\Number\Price;
use App\Support\Side;

/** The exits a strategy asked for when it entered: the stop and target to keep on the venue for its position. */
final readonly class ProtectionPlan
{
    public function __construct(
        public ?Price $stop,
        public ?Price $target,
        public ?Decimal $plannedRisk,
        public ?int $leverage = null,
        public Side $side = Side::Buy,
    ) {}

    public function withStop(Price $stop): self
    {
        return new self($stop, $this->target, $this->plannedRisk, $this->leverage, $this->side);
    }
}
