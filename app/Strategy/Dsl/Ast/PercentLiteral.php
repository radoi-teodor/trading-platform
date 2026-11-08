<?php

declare(strict_types=1);

namespace App\Strategy\Dsl\Ast;

use App\Strategy\Dsl\Position;
use App\Support\Number\Decimal;

/** A percent, such as 0.5%, kept as a fraction (0.005) and as written. */
final readonly class PercentLiteral implements Node
{
    public function __construct(public Decimal $fraction, public string $text, public Position $at) {}

    public function position(): Position
    {
        return $this->at;
    }
}
