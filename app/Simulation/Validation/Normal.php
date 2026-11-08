<?php

declare(strict_types=1);

namespace App\Simulation\Validation;

use App\Simulation\Metrics\Statistics;
use App\Support\Number\Decimal;
use App\Support\Number\DecimalMath;
use InvalidArgumentException;

/**
 * The standard normal distribution in decimals (D-39), for the deflated Sharpe ratio: its cumulative distribution by
 * the Taylor series near zero and Laplace's continued fraction in the tails, and its inverse by bisection.
 */
final class Normal
{
    private const int SCALE = 40;

    public static function cdf(Decimal $x): Decimal
    {
        if ($x->abs()->isGreaterThan(Decimal::of(38))) {
            return Decimal::of($x->isPositive() ? 1 : 0);
        }

        $value = $x->abs()->isGreaterThan(Decimal::of(3))
            ? self::tail($x)
            : Decimal::of('0.5')->plus(self::series($x));

        return $value->roundedTo(Statistics::SCALE);
    }

    /** The x with cdf(x) = p, for 0 < p < 1, to 18 decimals. */
    public static function inverse(Decimal $p): Decimal
    {
        if (! $p->isPositive() || ! $p->isLessThan(Decimal::of(1))) {
            throw new InvalidArgumentException("The inverse normal needs 0 < p < 1, got {$p}.");
        }

        [$low, $high] = [Decimal::of(-40), Decimal::of(40)];
        $two = Decimal::of(2);

        for ($i = 0; $i < 90; $i++) {
            $middle = $low->plus($high)->dividedBy($two, self::SCALE);
            [$low, $high] = self::raw($middle)->isLessThan($p) ? [$middle, $high] : [$low, $middle];
        }

        return $low->plus($high)->dividedBy($two, self::SCALE)->roundedTo(Statistics::SCALE);
    }

    /** The density at x. */
    public static function density(Decimal $x): Decimal
    {
        $root = DecimalMath::sqrt(Decimal::of(2)->times(self::pi()), self::SCALE);

        return DecimalMath::exp($x->times($x)->dividedBy(Decimal::of(-2), self::SCALE), self::SCALE)->dividedBy($root, self::SCALE);
    }

    /** cdf without the final rounding, for the bisection. */
    private static function raw(Decimal $x): Decimal
    {
        if ($x->abs()->isGreaterThan(Decimal::of(38))) {
            return Decimal::of($x->isPositive() ? 1 : 0);
        }

        return $x->abs()->isGreaterThan(Decimal::of(3)) ? self::tail($x) : Decimal::of('0.5')->plus(self::series($x));
    }

    /** cdf(x) - 1/2 = density-free series: (1 / sqrt(2 pi)) sum (-1)^n x^(2n+1) / (2^n n! (2n + 1)). */
    private static function series(Decimal $x): Decimal
    {
        $square = $x->times($x);
        $term = $x;
        $sum = Decimal::zero();
        $epsilon = Decimal::of('0.'.str_repeat('0', 44).'1');

        for ($n = 0; $n < 400; $n++) {
            $piece = $term->dividedBy(Decimal::of(2 * $n + 1), self::SCALE);
            $sum = $sum->plus($piece);

            if ($piece->abs()->isLessThan($epsilon)) {
                break;
            }

            $term = $term->times($square)->dividedBy(Decimal::of(-2 * ($n + 1)), self::SCALE);
        }

        return $sum->dividedBy(DecimalMath::sqrt(Decimal::of(2)->times(self::pi()), self::SCALE), self::SCALE);
    }

    /** The tail by Laplace's continued fraction: Q(z) = density(z) / (z + 1 / (z + 2 / (z + 3 / (z + ...)))), z > 3. */
    private static function tail(Decimal $x): Decimal
    {
        $z = $x->abs();
        $fraction = $z;

        for ($k = 300; $k >= 1; $k--) {
            $fraction = $z->plus(Decimal::of($k)->dividedBy($fraction, self::SCALE));
        }

        $upper = self::density($z)->dividedBy($fraction, self::SCALE);

        return $x->isPositive() ? Decimal::of(1)->minus($upper) : $upper;
    }

    private static function pi(): Decimal
    {
        return Decimal::of('3.1415926535897932384626433832795028841971693993751');
    }
}
