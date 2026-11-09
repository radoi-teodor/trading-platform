<?php

declare(strict_types=1);

namespace App\Strategy\Dsl\Ast;

use App\Strategy\Dsl\Position;

/** An arithmetic, comparison, cross or logical operator between two operands. */
final readonly class Binary implements Node
{
    public function __construct(public string $operator, public Node $left, public Node $right, public Position $at) {}

    public function position(): Position
    {
        return $this->at;
    }
}
