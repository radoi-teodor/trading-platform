<?php

declare(strict_types=1);

namespace App\Strategy\Dsl\Ast;

use App\Strategy\Dsl\Position;

/** A name without arguments, such as close or hl2. */
final readonly class Identifier implements Node
{
    public function __construct(public string $name, public Position $at) {}

    public function position(): Position
    {
        return $this->at;
    }
}
