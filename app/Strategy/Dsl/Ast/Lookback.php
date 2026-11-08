<?php

declare(strict_types=1);

namespace App\Strategy\Dsl\Ast;

use App\Strategy\Dsl\Position;

/** x[n]: the value n closed candles back. */
final readonly class Lookback implements Node
{
    public function __construct(public Node $series, public int $bars, public Position $at) {}

    public function position(): Position
    {
        return $this->at;
    }
}
