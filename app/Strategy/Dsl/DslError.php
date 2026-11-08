<?php

declare(strict_types=1);

namespace App\Strategy\Dsl;

use InvalidArgumentException;

/** A mistake in a strategy document, with where it is and, when there is an obvious one, how to fix it. */
final class DslError extends InvalidArgumentException
{
    public function __construct(public readonly Position $position, public readonly string $problem)
    {
        parent::__construct("{$position->describe()}: {$problem}");
    }
}
