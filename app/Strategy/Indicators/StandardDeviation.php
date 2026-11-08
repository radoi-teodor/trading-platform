<?php

declare(strict_types=1);

namespace App\Strategy\Indicators;

use App\Support\Number\Decimal;
use App\Support\Number\DecimalMath;

/** Population standard deviation of the last n values. */
final class StandardDeviation
{
    private Window $window;

    public function __construct(public readonly int $period)
    {
        $this->window = new Window($period);
    }

    public function push(Decimal $value): ?Decimal
    {
        $this->window->push($value);

        return $this->window->isFull() ? self::of($this->window->values(), 0) : null;
    }

    /**
     * The standard deviation of $values around their mean, dividing the sum of squares by count - $lost.
     *
     * @param  list<Decimal>  $values
     */
    public static function of(array $values, int $lost): Decimal
    {
        $count = Decimal::of(count($values));
        $sum = Decimal::zero();

        foreach ($values as $value) {
            $sum = $sum->plus($value);
        }

        $mean = $sum->dividedBy($count, 18);
        $squares = Decimal::zero();

        foreach ($values as $value) {
            $deviation = $value->minus($mean);
            $squares = $squares->plus($deviation->times($deviation));
        }

        return DecimalMath::sqrt($squares->dividedBy(Decimal::of(count($values) - $lost), 18));
    }
}
