<?php

declare(strict_types=1);

namespace App\Strategy\Dsl\Ast;

use App\Strategy\Dsl\Position;

/** A function call, with an optional field: bb(20).upper. */
final readonly class Call implements Node
{
    /** @param list<Argument> $arguments */
    public function __construct(public string $name, public array $arguments, public ?string $field, public Position $at) {}

    public function position(): Position
    {
        return $this->at;
    }
}
