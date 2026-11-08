<?php

declare(strict_types=1);

namespace App\Support\Number;

/**
 * Logarithm, exponential, square root and powers on decimals (D-39). Statistics in reports use these instead of
 * float functions, whose last digits depend on the C library, so that a report hashes the same on every machine.
 * Results are rounded half away from zero to the requested scale; the work is done with guard digits.
 */
final class DecimalMath
{
    public const int SCALE = 18;

    private const int GUARD = 12;

    public static function ln(Decimal $x, int $scale = self::SCALE): Decimal
    {
        return self::lnAt($x, $scale + self::GUARD)->roundedTo($scale);
    }

    public static function exp(Decimal $x, int $scale = self::SCALE): Decimal
    {
        return self::expAt($x, $scale + self::GUARD)->roundedTo($scale);
    }

    public static function sqrt(Decimal $x, int $scale = self::SCALE): Decimal
    {
        return $x->squareRoot($scale + self::GUARD)->roundedTo($scale);
    }

    /** $base to the power $exponent, for a base that is not negative: exp($exponent × ln $base). */
    public static function pow(Decimal $base, Decimal $exponent, int $scale = self::SCALE): Decimal
    {
        if ($base->isZero() && $exponent->isPositive()) {
            return Decimal::zero()->roundedTo($scale);
        }

        $working = $scale + self::GUARD;
        // An error in ln(base) grows with the exponent and with the size of the result (about 0.4343 y digits), so
        // ln is taken with that many more digits.
        $rough = (int) (string) $exponent->times(self::lnAt($base, $working))->truncatedTo(0);
        $precise = $working + self::GUARD + intdiv(abs($rough) * 4343, 10000) + strlen((string) $exponent->abs()->truncatedTo(0));
        $product = $exponent->times(self::lnAt($base, $precise))->truncatedTo($precise);

        return self::expAt($product, $working)->roundedTo($scale);
    }

    /** ln x = k ln 2 + ln y, with y = x / 2^k in [1, 2) and ln y from the series of 2 atanh((y - 1) / (y + 1)). */
    private static function lnAt(Decimal $x, int $working): Decimal
    {
        if (! $x->isPositive()) {
            throw new InvalidNumber("No real logarithm of '{$x}'.");
        }

        $two = Decimal::of(2);
        $one = Decimal::of(1);
        $k = 0;
        $y = $x;

        while ($y->isLessThan($one)) {
            $y = $y->times($two);
            $k--;
        }

        $power = $one;

        while (! $y->isLessThan($power->times($two))) {
            $power = $power->times($two);
            $k++;
        }

        $y = $y->dividedBy($power, $working);
        $lnY = self::atanhSeries($y->minus($one)->dividedBy($y->plus($one), $working), $working);

        return self::lnTwo($working)->times(Decimal::of($k))->plus($lnY)->truncatedTo($working);
    }

    /** 2 atanh(s) = 2 (s + s^3/3 + s^5/5 + ...), which is ln((1 + s) / (1 - s)). */
    private static function atanhSeries(Decimal $s, int $working): Decimal
    {
        $square = $s->times($s)->truncatedTo($working);
        $power = $s;
        $sum = $s;

        for ($n = 3; ; $n += 2) {
            $power = $power->times($square)->truncatedTo($working);
            $term = $power->dividedBy(Decimal::of($n), $working);

            if ($term->isZero()) {
                return $sum->times(Decimal::of(2));
            }

            $sum = $sum->plus($term);
        }
    }

    /** y = 2 gives s = 1/3. */
    private static function lnTwo(int $working): Decimal
    {
        return self::atanhSeries(Decimal::of(1)->dividedBy(Decimal::of(3), $working), $working);
    }

    /**
     * e^x = 2^k e^r, with k = x / ln 2 truncated and |r| below ln 2, e^r from its Taylor series. Multiplying by 2^k
     * multiplies the error of e^r too, so e^r is computed with as many more digits as 2^k has.
     */
    private static function expAt(Decimal $x, int $working): Decimal
    {
        $k = (int) (string) $x->dividedBy(self::lnTwo($working), 0);
        $power = Decimal::of(2)->toThePowerOf(abs($k));
        $precise = $working + strlen((string) $power);

        $r = $x->minus(self::lnTwo($precise)->times(Decimal::of($k)))->truncatedTo($precise);
        $sum = Decimal::of(1);
        $term = Decimal::of(1);

        for ($n = 1; ! $term->isZero(); $n++) {
            $term = $term->times($r)->dividedBy(Decimal::of($n), $precise);
            $sum = $sum->plus($term);
        }

        return $k < 0 ? $sum->dividedBy($power, $precise) : $sum->times($power);
    }
}
