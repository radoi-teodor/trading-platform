<?php

declare(strict_types=1);

namespace App\Simulation\Validation;

use App\Simulation\Metrics\Statistics;
use App\Support\Number\Decimal;
use InvalidArgumentException;

/**
 * The probability of backtest overfitting by combinatorially symmetric cross-validation (Bailey, Borwein, López de
 * Prado and Zhu, 2017; D-58). The returns of every configuration are cut into S equal blocks of consecutive days;
 * for each way to pick S/2 blocks as in-sample, the configuration with the best in-sample Sharpe ratio is ranked
 * out of sample among all of them. PBO is the share of splits where it ranks at or below the median, a relative
 * rank ω ≤ 1/2, a logit ln(ω / (1 - ω)) ≤ 0. The oldest days that do not fill a block are left out.
 */
final class Cscv
{
    /**
     * @param  list<list<Decimal>>  $returns  daily returns per configuration, all of the same length
     * @return array{probability: Decimal, combinations: int, days: int, below: int}
     */
    public static function overfitting(array $returns, int $partitions = 16): array
    {
        $configurations = count($returns);
        $length = $configurations === 0 ? 0 : count($returns[0]);

        if ($configurations < 2 || $partitions < 2 || $partitions % 2 !== 0) {
            throw new InvalidArgumentException("CSCV needs at least 2 configurations and an even number of blocks, got {$configurations} and {$partitions}.");
        }

        foreach ($returns as $series) {
            if (count($series) !== $length) {
                throw new InvalidArgumentException('CSCV needs every configuration over the same days.');
            }
        }

        $size = intdiv($length, $partitions);

        if ($size < 2) {
            throw new InvalidArgumentException("CSCV over {$partitions} blocks needs at least ".(2 * $partitions)." days, got {$length}.");
        }

        $skip = $length - $size * $partitions;
        // Per configuration and block: count, sum and sum of squares, so any union of blocks is a sum.
        $blocks = [];

        foreach ($returns as $c => $series) {
            for ($b = 0; $b < $partitions; $b++) {
                [$sum, $squares] = [Decimal::zero(), Decimal::zero()];

                foreach (array_slice($series, $skip + $b * $size, $size) as $value) {
                    $sum = $sum->plus($value);
                    $squares = $squares->plus($value->times($value));
                }

                $blocks[$c][$b] = [$sum, $squares];
            }
        }

        $combinations = 0;
        $below = 0;

        foreach (self::combinations($partitions, intdiv($partitions, 2)) as $inSample) {
            $outSample = array_values(array_diff(range(0, $partitions - 1), $inSample));
            $best = null;
            $bestScore = null;

            for ($c = 0; $c < $configurations; $c++) {
                $score = self::score($blocks[$c], $inSample, $size);

                if ($bestScore === null || $score->isGreaterThan($bestScore)) {
                    [$best, $bestScore] = [$c, $score];
                }
            }

            $target = self::score($blocks[(int) $best], $outSample, $size);
            $rank = 1;

            for ($c = 0; $c < $configurations; $c++) {
                $score = self::score($blocks[$c], $outSample, $size);

                if ($score->isLessThan($target) || ($score->equals($target) && $c < (int) $best)) {
                    $rank++;
                }
            }

            $combinations++;
            // ω = rank / (N + 1) ≤ 1/2
            $below += 2 * $rank <= $configurations + 1 ? 1 : 0;
        }

        return [
            'probability' => Decimal::of($below)->dividedBy(Decimal::of($combinations), Statistics::SCALE),
            'combinations' => $combinations,
            'days' => $size * $partitions,
            'below' => $below,
        ];
    }

    /**
     * A score that orders like the Sharpe ratio without a square root: sign(mean) × mean² / variance.
     *
     * @param  array<int, array{Decimal, Decimal}>  $blocks
     * @param  list<int>  $chosen
     */
    private static function score(array $blocks, array $chosen, int $size): Decimal
    {
        [$sum, $squares] = [Decimal::zero(), Decimal::zero()];

        foreach ($chosen as $b) {
            $sum = $sum->plus($blocks[$b][0]);
            $squares = $squares->plus($blocks[$b][1]);
        }

        $n = Decimal::of(count($chosen) * $size);
        $mean = $sum->dividedBy($n, 30);
        $variance = $squares->minus($sum->times($mean))->dividedBy($n->minus(Decimal::of(1)), 30);

        if (! $variance->isPositive()) {
            return $mean;
        }

        $score = $mean->times($mean)->dividedBy($variance, 30);

        return $mean->isNegative() ? $score->negate() : $score;
    }

    /** @return iterable<list<int>> every choice of $k of 0..$n-1, in lexicographic order */
    private static function combinations(int $n, int $k): iterable
    {
        $pick = range(0, $k - 1);

        while (true) {
            yield array_values($pick);
            $i = $k - 1;

            while ($i >= 0 && $pick[$i] === $n - $k + $i) {
                $i--;
            }

            if ($i < 0) {
                return;
            }

            $pick[$i]++;

            for ($j = $i + 1; $j < $k; $j++) {
                $pick[$j] = $pick[$j - 1] + 1;
            }
        }
    }
}
