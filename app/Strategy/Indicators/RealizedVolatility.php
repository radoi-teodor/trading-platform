<?php

declare(strict_types=1);

namespace App\Strategy\Indicators;

use App\Support\Number\Decimal;
use App\Support\Number\DecimalMath;
use App\Support\Timeframe;
use InvalidArgumentException;

/**
 * Realized volatility: the sample standard deviation of the last n log returns, annualized with the square root of
 * the candles in a 365-day year (crypto trades every day).
 */
final class RealizedVolatility
{
    private Window $returns;

    private ?Decimal $previous = null;

    private Decimal $annualizer;

    public function __construct(public readonly int $period, Timeframe $timeframe)
    {
        if ($period < 2) {
            throw new InvalidArgumentException("Realized volatility needs at least 2 returns, got {$period}.");
        }

        $this->returns = new Window($period);
        $this->annualizer = DecimalMath::sqrt(Decimal::of(intdiv(365 * 86_400, $timeframe->seconds())));
    }

    public function push(Decimal $value): ?Decimal
    {
        $previous = $this->previous;
        $this->previous = $value;

        if ($previous === null) {
            return null;
        }

        $this->returns->push(DecimalMath::ln($value->dividedBy($previous, 18)));

        return $this->returns->isFull() ? StandardDeviation::of($this->returns->values(), 1)->times($this->annualizer)->truncatedTo(18) : null;
    }
}
