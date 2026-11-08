<?php

declare(strict_types=1);

namespace App\Strategy\Dsl\Ast;

use App\Strategy\Dsl\Position;

/** true or false. */
final readonly class BoolLiteral implements Node
{
    public function __construct(public bool $value, public Position $at) {}

    public function position(): Position
    {
        return $this->at;
    }
}
