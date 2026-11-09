<?php

declare(strict_types=1);

namespace App\Simulation\Validation;

use App\Support\Number\Decimal;
use Carbon\CarbonImmutable;

/** One parameter set's continuous run, as walk-forward reads it: its closed trades (D-57). */
final readonly class WalkForwardSet
{
    /**
     * @param  array<string, string>  $parameters
     * @param  list<array{opened: CarbonImmutable, closed: CarbonImmutable, net: Decimal}>  $trades
     */
    public function __construct(public array $parameters, public array $trades) {}
}
