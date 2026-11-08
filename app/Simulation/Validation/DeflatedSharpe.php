<?php

declare(strict_types=1);

namespace App\Simulation\Validation;

use App\Simulation\Metrics\Statistics;
use App\Support\Number\Decimal;
use App\Support\Number\DecimalMath;
use InvalidArgumentException;

/**
 * The deflated Sharpe ratio of Bailey and López de Prado (2014), in decimals (D-58). All Sharpe ratios here are per
 * observation (daily), not annualized.
 *
 * - expectedMaximum(): the Sharpe ratio the best of N trials reaches by luck when every trial's true Sharpe is zero:
 *   sqrt(V) × ((1 - γ) Φ⁻¹(1 - 1/N) + γ Φ⁻¹(1 - 1/(N e))), γ the Euler-Mascheroni constant, V the variance of the
 *   trials' Sharpe ratios.
 * - probability(): the probability that the true Sharpe exceeds a benchmark, given T observations, their skewness
 *   γ3 and kurtosis γ4: Φ((SR - SR*) √(T - 1) / √(1 - γ3 SR + (γ4 - 1) / 4 × SR²)). With SR* the expected maximum,
 *   it is the deflated Sharpe ratio; the gate wants at least 0.95 (docs/simulation.md, section 7).
 */
final class DeflatedSharpe
{
    private const string EULER = '0.5772156649015328606065120900824024310422';

    private const int SCALE = 40;

    public static function expectedMaximum(int $trials, Decimal $variance): Decimal
    {
        if ($trials < 1 || $variance->isNegative()) {
            throw new InvalidArgumentException("The expected maximum needs at least one trial and a variance of zero or more, got {$trials} and {$variance}.");
        }

        if ($trials === 1 || $variance->isZero()) {
            return Decimal::zero();
        }

        $one = Decimal::of(1);
        $gamma = Decimal::of(self::EULER);
        $n = Decimal::of($trials);
        $e = DecimalMath::exp($one, self::SCALE);
        $first = Normal::inverse($one->minus($one->dividedBy($n, self::SCALE)));
        $second = Normal::inverse($one->minus($one->dividedBy($n->times($e), self::SCALE)));

        return DecimalMath::sqrt($variance, self::SCALE)
            ->times($one->minus($gamma)->times($first)->plus($gamma->times($second)))
            ->roundedTo(Statistics::SCALE);
    }

    public static function probability(Decimal $sharpe, int $observations, Decimal $skewness, Decimal $kurtosis, Decimal $benchmark): Decimal
    {
        if ($observations < 2) {
            throw new InvalidArgumentException("The deflated Sharpe ratio needs at least 2 observations, got {$observations}.");
        }

        $spread = Decimal::of(1)
            ->minus($skewness->times($sharpe))
            ->plus($kurtosis->minus(Decimal::of(1))->dividedBy(Decimal::of(4), self::SCALE)->times($sharpe)->times($sharpe));

        if (! $spread->isPositive()) {
            throw new InvalidArgumentException('The returns\' moments give no spread for the Sharpe ratio.');
        }

        $z = $sharpe->minus($benchmark)
            ->times(DecimalMath::sqrt(Decimal::of($observations - 1), self::SCALE))
            ->dividedBy(DecimalMath::sqrt($spread, self::SCALE), self::SCALE);

        return Normal::cdf($z);
    }

    /**
     * Mean, sample standard deviation, skewness and kurtosis (not excess; 3 for a normal) of returns.
     *
     * @param  list<Decimal>  $returns
     * @return array{mean: Decimal, deviation: Decimal, skewness: Decimal, kurtosis: Decimal}
     */
    public static function moments(array $returns): array
    {
        $count = count($returns);

        if ($count < 2) {
            throw new InvalidArgumentException("Moments need at least 2 returns, got {$count}.");
        }

        $n = Decimal::of($count);
        $mean = Statistics::sum($returns)->dividedBy($n, self::SCALE);
        [$m2, $m3, $m4] = [Decimal::zero(), Decimal::zero(), Decimal::zero()];

        foreach ($returns as $value) {
            $d = $value->minus($mean);
            $square = $d->times($d);
            $m2 = $m2->plus($square);
            $m3 = $m3->plus($square->times($d));
            $m4 = $m4->plus($square->times($square));
        }

        $deviation = DecimalMath::sqrt($m2->dividedBy(Decimal::of($count - 1), self::SCALE), self::SCALE);
        [$m2, $m3, $m4] = [$m2->dividedBy($n, self::SCALE), $m3->dividedBy($n, self::SCALE), $m4->dividedBy($n, self::SCALE)];

        if ($m2->isZero()) {
            return ['mean' => $mean->roundedTo(Statistics::SCALE), 'deviation' => Decimal::zero(), 'skewness' => Decimal::zero(), 'kurtosis' => Decimal::of(3)];
        }

        $skewness = $m3->dividedBy($m2->times(DecimalMath::sqrt($m2, self::SCALE)), self::SCALE);

        return [
            'mean' => $mean->roundedTo(Statistics::SCALE),
            'deviation' => $deviation->roundedTo(Statistics::SCALE),
            'skewness' => $skewness->roundedTo(Statistics::SCALE),
            'kurtosis' => $m4->dividedBy($m2->times($m2), self::SCALE)->roundedTo(Statistics::SCALE),
        ];
    }

    /**
     * The per-observation Sharpe ratio of returns: mean over sample deviation, null when they do not move.
     *
     * @param  list<Decimal>  $returns
     */
    public static function sharpe(array $returns): ?Decimal
    {
        $moments = self::moments($returns);

        return $moments['deviation']->isZero() ? null : $moments['mean']->dividedBy($moments['deviation'], Statistics::SCALE);
    }
}
