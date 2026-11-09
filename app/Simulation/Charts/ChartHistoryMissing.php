<?php

declare(strict_types=1);

namespace App\Simulation\Charts;

use Carbon\CarbonImmutable;
use InvalidArgumentException;

/**
 * The chart's window needs minutes that are not stored (D-88): its history starts inside a candle, or a minute is
 * missing. Candles are only built whole (D-36), so the chart says what is missing and from which day to fill it.
 */
final class ChartHistoryMissing extends InvalidArgumentException
{
    public function __construct(public readonly string $symbol, public readonly string $reason, public readonly CarbonImmutable $fillFrom)
    {
        parent::__construct("{$reason} Charts and these tools build whole candles only (D-36): ask the operator to fill its history from {$fillFrom->format('Y-m-d')}.");
    }
}
