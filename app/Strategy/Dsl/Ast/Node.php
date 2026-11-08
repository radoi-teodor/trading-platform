<?php

declare(strict_types=1);

namespace App\Strategy\Dsl\Ast;

use App\Strategy\Dsl\Position;

/** A node of a parsed expression. Nodes are immutable and carry where they start in the document. */
interface Node
{
    public function position(): Position;
}
