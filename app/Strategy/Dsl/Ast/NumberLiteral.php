<?php

declare(strict_types=1);

namespace App\Strategy\Dsl\Ast;

use App\Strategy\Dsl\Position;
use App\Support\Number\Decimal;

/** A number, such as 20 or 2.5. */
final readonly class NumberLiteral implements Node
{
    public function __construct(public Decimal $value, public Position $at) {}

    public function position(): Position
    {
        return $this->at;
    }
}
