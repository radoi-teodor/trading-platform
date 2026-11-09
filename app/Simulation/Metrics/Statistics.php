<?php

declare(strict_types=1);

namespace App\Simulation\Metrics;

use App\Support\Number\Decimal;
use App\Support\Number\DecimalMath;

/** Sample statistics on decimals (D-39). Divisions truncate at 18 decimals; square roots round at 18. */
final class Statistics
{
    public const int SCALE = 18;

    /** Two-sided 95 % critical values of Student's t by degrees of freedom, below 30; 1.96 from 30 on. */
    private const array T95 = [
        1 => '12.706', 2 => '4.303', 3 => '3.182', 4 => '2.776', 5 => '2.571', 6 => '2.447', 7 => '2.365',
        8 => '2.306', 9 => '2.262', 10 => '2.228', 11 => '2.201', 12 => '2.179', 13 => '2.160', 14 => '2.145',
        15 => '2.131', 16 => '2.120', 17 => '2.110', 18 => '2.101', 19 => '2.093', 20 => '2.086', 21 => '2.080',
        22 => '2.074', 23 => '2.069', 24 => '2.064', 25 => '2.060', 26 => '2.056', 27 => '2.052', 28 => '2.048',
        29 => '2.045',
    ];

    /** @param list<Decimal> $values */
    public static function sum(array $values): Decimal
    {
        return array_reduce($values, fn (Decimal $carry, Decimal $value): Decimal => $carry->plus($value), Decimal::zero());
    }

    /** @param list<Decimal> $values */
    public static function mean(array $values): ?Decimal
    {
        return $values === [] ? null : self::sum($values)->dividedBy(Decimal::of(count($values)), self::SCALE);
    }

    /**
     * Sample standard deviation, with n - 1 in the denominator.
     *
     * @param  list<Decimal>  $values
     */
    public static function standardDeviation(array $values): ?Decimal
    {
        $mean = self::mean($values);

        if ($mean === null || count($values) < 2) {
            return null;
        }

        $squares = array_map(fn (Decimal $value): Decimal => $value->minus($mean)->times($value->minus($mean)), $values);

        return DecimalMath::sqrt(self::sum($squares)->dividedBy(Decimal::of(count($values) - 1), self::SCALE));
    }

    /**
     * sqrt(sum(min(r, 0)^2) / n): the deviation of the losses only, for the Sortino ratio.
     *
     * @param  list<Decimal>  $values
     */
    public static function downsideDeviation(array $values): ?Decimal
    {
        if ($values === []) {
            return null;
        }

        $squares = array_map(fn (Decimal $value): Decimal => $value->isNegative() ? $value->times($value) : Decimal::zero(), $values);

        return DecimalMath::sqrt(self::sum($squares)->dividedBy(Decimal::of(count($values)), self::SCALE));
    }

    /**
     * The 95 % confidence interval of the mean: mean ± t × sd / sqrt(n); null below two values.
     *
     * @param  list<Decimal>  $values
     * @return array{Decimal, Decimal}|null
     */
    public static function confidenceInterval95(array $values): ?array
    {
        $mean = self::mean($values);
        $deviation = self::standardDeviation($values);

        if ($mean === null || $deviation === null) {
            return null;
        }

        $n = count($values);
        $critical = Decimal::of($n < 30 ? self::T95[$n - 1] : '1.96');
        $margin = $critical->times($deviation)->dividedBy(DecimalMath::sqrt(Decimal::of($n)), self::SCALE);

        return [$mean->minus($margin), $mean->plus($margin)];
    }

    public static function ratio(Decimal $numerator, ?Decimal $denominator): ?Decimal
    {
        return $denominator === null || $denominator->isZero() ? null : $numerator->dividedBy($denominator, self::SCALE);
    }
}
