<?php

declare(strict_types=1);

namespace App\Simulation\Validation;

use App\Simulation\Metrics\Statistics;
use App\Support\Number\Decimal;

/**
 * The gates for the Backtested stage (docs/simulation.md, section 7), judged on out-of-sample results only: the
 * walk-forward windows plus the hold-out. Each gate gives its number, its threshold, whether it passes and, when it
 * fails, what would have to change. The carry template counts 12 months and 10 closed cycles instead of 100 trades
 * (D-60). Gates are never relaxed for one strategy without a D-number.
 */
final class Gates
{
    public const int MIN_TRADES = 100;

    public const int CARRY_MONTHS = 12;

    public const int CARRY_CYCLES = 10;

    public const string MIN_PROFIT_FACTOR = '1.2';

    public const string MIN_DEFLATED_SHARPE = '0.95';

    public const string MIN_ROBUST_SHARE = '0.8';

    public const string ROBUST_PROFIT_FACTOR = '1.1';

    private const int SCALE = 18;

    /**
     * @param  list<Decimal>  $trades  net results of the out-of-sample trades, in order
     * @param  ?Decimal  $robustShare  the share of parameter neighbours with a profit factor of 1.1 or more; null without parameters
     * @return list<array{gate: string, passed: bool, value: string, threshold: string, advice: string}>
     */
    public static function evaluate(
        array $trades,
        int $windows,
        int $positiveWindows,
        ?Decimal $deflatedSharpe,
        ?Decimal $robustShare,
        ?Decimal $stressedExpectancy,
        ?Decimal $drawdownP95,
        Decimal $drawdownLimit,
        bool $carry = false,
        int $outOfSampleMonths = 0,
    ): array {
        $count = count($trades);
        $interval = Statistics::confidenceInterval95($trades);
        $profitFactor = self::profitFactor($trades);
        $withoutBest = self::withoutBest($trades);
        $text = fn (?Decimal $value, int $scale = 4): string => $value === null ? 'n/a' : (string) $value->roundedTo($scale);
        $checks = [];

        $checks[] = $carry
            ? self::check('trades', $count >= self::CARRY_CYCLES && $outOfSampleMonths >= self::CARRY_MONTHS, "{$count} cycles over {$outOfSampleMonths} months",
                self::CARRY_CYCLES.' cycles and '.self::CARRY_MONTHS.' months (D-60)', 'Needs more out-of-sample history: a longer window or an earlier start.')
            : self::check('trades', $count >= self::MIN_TRADES, (string) $count, 'at least '.self::MIN_TRADES,
                'Too few trades to measure an edge: a longer history, more instruments, or a rule that trades more often.');
        $checks[] = self::check('edge', $interval !== null && $interval[0]->isPositive(), $interval === null ? 'n/a' : "{$text($interval[0])} to {$text($interval[1])}",
            'lower bound of the 95 % interval of net expectancy above 0 (D-15)', 'The edge after costs is not distinguishable from zero.');
        $noLoss = $profitFactor === null && $trades !== [] && array_filter($trades, fn (Decimal $t): bool => $t->isNegative()) === [] && Statistics::sum($trades)->isPositive();
        $checks[] = self::check('profit_factor', $noLoss || ($profitFactor !== null && ! $profitFactor->isLessThan(Decimal::of(self::MIN_PROFIT_FACTOR))), $noLoss ? 'no losing trade' : $text($profitFactor),
            'at least '.self::MIN_PROFIT_FACTOR, 'Winners do not pay enough for the losers after costs.');
        $checks[] = self::check('walk_forward', $windows > 0 && 3 * $positiveWindows >= 2 * $windows, "{$positiveWindows} of {$windows} windows positive",
            'positive in at least 2 of every 3 windows', 'The parameters chosen in-sample do not keep working on the next window.');
        $checks[] = self::check('deflated_sharpe', $deflatedSharpe !== null && ! $deflatedSharpe->isLessThan(Decimal::of(self::MIN_DEFLATED_SHARPE)), $text($deflatedSharpe),
            'at least '.self::MIN_DEFLATED_SHARPE, 'After counting every variant tried, the best Sharpe ratio is likely luck.');
        $checks[] = $robustShare === null
            ? self::check('robustness', true, 'no declared parameters', 'at least 80 % of neighbours keep a profit factor of '.self::ROBUST_PROFIT_FACTOR, '')
            : self::check('robustness', ! $robustShare->isLessThan(Decimal::of(self::MIN_ROBUST_SHARE)), $text($robustShare->times(Decimal::of(100)), 1).' %',
                'at least 80 % of neighbours keep a profit factor of '.self::ROBUST_PROFIT_FACTOR, 'The result sits on a peak: moving a parameter 20 % breaks it.');
        $checks[] = self::check('cost_stress', $stressedExpectancy !== null && $stressedExpectancy->isPositive(), $text($stressedExpectancy),
            'net expectancy above 0 with fees x1.5 and slippage x2', 'The edge does not survive higher costs.');
        $checks[] = self::check('outliers', $withoutBest !== null && $withoutBest->isPositive(), $text($withoutBest),
            'net expectancy above 0 without the best 1 % of trades', 'A few large winners carry the whole result.');
        $checks[] = self::check('drawdown', $drawdownP95 !== null && $drawdownP95->isLessThan($drawdownLimit),
            $drawdownP95 === null ? 'n/a' : $text($drawdownP95->times(Decimal::of(100)), 2).' %',
            'Monte Carlo 95th percentile drawdown below '.$drawdownLimit->times(Decimal::of(100)).' %', 'Size it down, or cut the losing streaks.');

        return $checks;
    }

    /** @param list<array{passed: bool}> $checks */
    public static function passed(array $checks): bool
    {
        return $checks !== [] && array_filter($checks, fn (array $check): bool => ! $check['passed']) === [];
    }

    /**
     * Gross wins over gross losses; null without a losing trade.
     *
     * @param  list<Decimal>  $trades
     */
    public static function profitFactor(array $trades): ?Decimal
    {
        $wins = Statistics::sum(array_values(array_filter($trades, fn (Decimal $t): bool => $t->isPositive())));
        $losses = Statistics::sum(array_values(array_filter($trades, fn (Decimal $t): bool => $t->isNegative())))->abs();

        return $losses->isZero() ? null : $wins->dividedBy($losses, self::SCALE);
    }

    /**
     * Net expectancy without the best 1 % of trades, at least the single best one.
     *
     * @param  list<Decimal>  $trades
     */
    public static function withoutBest(array $trades): ?Decimal
    {
        $sorted = $trades;
        usort($sorted, fn (Decimal $a, Decimal $b): int => $b->compare($a));

        return Statistics::mean(array_slice($sorted, max(1, intdiv(count($sorted) + 99, 100))));
    }

    /** @return array{gate: string, passed: bool, value: string, threshold: string, advice: string} */
    private static function check(string $gate, bool $passed, string $value, string $threshold, string $advice): array
    {
        return ['gate' => $gate, 'passed' => $passed, 'value' => $value, 'threshold' => $threshold, 'advice' => $passed ? '' : $advice];
    }
}
