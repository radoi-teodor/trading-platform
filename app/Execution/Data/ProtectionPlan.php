<?php

declare(strict_types=1);

namespace App\Execution\Data;

use App\Support\Number\Decimal;
use App\Support\Number\Price;

/** The exits a strategy asked for when it entered: the stop and target to keep on the venue for its position. */
final readonly class ProtectionPlan
{
    public function __construct(public ?Price $stop, public ?Price $target, public ?Decimal $plannedRisk) {}

    public function withStop(Price $stop): self
    {
        return new self($stop, $this->target, $this->plannedRisk);
    }
}
