<?php

declare(strict_types=1);

namespace App\Strategy\Dsl\Ast;

use App\Strategy\Dsl\Position;

/** One argument of a call, positional or named. */
final readonly class Argument implements Node
{
    public function __construct(public ?string $name, public Node $value, public Position $at) {}

    public function position(): Position
    {
        return $this->at;
    }
}
