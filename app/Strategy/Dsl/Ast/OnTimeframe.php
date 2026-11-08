<?php

declare(strict_types=1);

namespace App\Strategy\Dsl\Ast;

use App\Strategy\Dsl\Position;
use App\Support\Timeframe;

/** x on 1d: the expression on the last closed candle of another timeframe (D-53). */
final readonly class OnTimeframe implements Node
{
    public function __construct(public Node $expression, public Timeframe $timeframe, public Position $at) {}

    public function position(): Position
    {
        return $this->at;
    }
}
