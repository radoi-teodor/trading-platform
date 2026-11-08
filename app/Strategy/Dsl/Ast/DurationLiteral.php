<?php

declare(strict_types=1);

namespace App\Strategy\Dsl\Ast;

use App\Strategy\Dsl\Position;

/** A duration, such as 8h or 60 bars; the unit is m, h, d, w or bars. */
final readonly class DurationLiteral implements Node
{
    public function __construct(public int $amount, public string $unit, public Position $at) {}

    public function position(): Position
    {
        return $this->at;
    }
}
