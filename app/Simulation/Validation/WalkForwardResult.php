<?php

declare(strict_types=1);

namespace App\Simulation\Validation;

use App\Support\Number\Decimal;

/** The windows of a walk-forward and the out-of-sample trades stitched together. */
final readonly class WalkForwardResult
{
    /**
     * @param  list<WalkForwardWindow>  $windows
     * @param  list<Decimal>  $outOfSample  net profit of every out-of-sample trade, in order
     * @param  array{low: Decimal, high: Decimal}|null  $interval  the 95 % interval of the expectancy
     */
    public function __construct(
        public array $windows,
        public array $outOfSample,
        public int $positiveWindows,
        public int $startedEarlier,
        public Decimal $net,
        public ?Decimal $expectancy,
        public ?array $interval,
        public ?Decimal $profitFactor,
    ) {}
}
