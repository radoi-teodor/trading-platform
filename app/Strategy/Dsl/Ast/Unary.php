<?php

declare(strict_types=1);

namespace App\Strategy\Dsl\Ast;

use App\Strategy\Dsl\Position;

/** neg or not, applied to one operand. */
final readonly class Unary implements Node
{
    public function __construct(public string $operator, public Node $operand, public Position $at) {}

    public function position(): Position
    {
        return $this->at;
    }
}
