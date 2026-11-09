<?php

declare(strict_types=1);

namespace App\Strategy\Dsl;

/** A place in a strategy document, 1-based, as an editor shows it. */
final readonly class Position
{
    public function __construct(public int $line, public int $column) {}

    public function describe(): string
    {
        return "line {$this->line}, column {$this->column}";
    }
}
