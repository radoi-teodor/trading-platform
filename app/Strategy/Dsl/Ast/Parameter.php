<?php

declare(strict_types=1);

namespace App\Strategy\Dsl\Ast;

use App\Strategy\Dsl\Position;

/** A declared parameter, written $name (D-51). */
final readonly class Parameter implements Node
{
    public function __construct(public string $name, public Position $at) {}

    public function position(): Position
    {
        return $this->at;
    }
}
