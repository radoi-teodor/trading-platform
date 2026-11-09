<?php

declare(strict_types=1);

namespace App\Strategy\Dsl\Document;

use App\Strategy\Dsl\Ast\Node;
use App\Strategy\Dsl\Types\Type;

/** A checked expression of a document: its tree, its type and its text as written back by DslWriter. */
final readonly class Expression
{
    public function __construct(public Node $node, public Type $type, public string $text) {}
}
