<?php

declare(strict_types=1);

namespace App\Strategy\Dsl\Ast;

use App\Strategy\Dsl\Position;
use App\Support\Number\Decimal;

/** A multiple of the initial risk, such as 3R. */
final readonly class RMultipleLiteral implements Node
{
    public function __construct(public Decimal $value, public Position $at) {}

    public function position(): Position
    {
        return $this->at;
    }
}
