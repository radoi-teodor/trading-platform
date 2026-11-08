<?php

declare(strict_types=1);

namespace App\Strategy\Dsl\Ast;

use App\Strategy\Dsl\Position;

/** A quoted text, such as "high_volatility". */
final readonly class TextLiteral implements Node
{
    public function __construct(public string $value, public Position $at) {}

    public function position(): Position
    {
        return $this->at;
    }
}
