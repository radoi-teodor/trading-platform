<?php

declare(strict_types=1);

namespace App\Simulation\Validation;

use App\Simulation\Metrics\Statistics;
use App\Support\Number\Decimal;
use App\Support\Number\DecimalMath;

/**
 * Whether a deployment's results still look like its backtest (docs/risk-policy.md, section 7; D-83). Four tests:
 * the interval of the last 30 trades against the backtest's, the drawdown against the Monte Carlo 95th percentile,
 * a one-sided CUSUM of the results against the backtest mean, and the costs against the cost model. With fewer
 * than 30 trades only the drawdown and the costs are judged.
 */
final class Band
{
    public const int TRADES = 30;

    private const string CUSUM_REFERENCE = '0.5';

    private const string CUSUM_ALARM = '4';

    private const string COST_TOLERANCE = '1.5';

    /**
     * @param  list<Decimal>  $nets  the deployment's trade results, oldest first
     * @param  list<Decimal>  $costRates  each trade's fees and slippage over its entry notional
     * @param  array<array-key, mixed>  $report  the version's latest passing gate report
     * @param  Decimal  $modelCost  what the cost model charges a round trip, over the notional
     * @return list<string> why the deployment is out of band; empty when it is in band
     */
    public static function breaches(array $nets, array $costRates, Decimal $startEquity, array $report, Decimal $modelCost): array
    {
        $breaches = [];
        $interval = self::interval($report);

        if (count($nets) >= self::TRADES && $interval !== null) {
            $recent = Statistics::confidenceInterval95(array_slice($nets, -self::TRADES));

            if ($recent !== null && $recent[1]->isLessThan($interval[0])) {
                $breaches[] = sprintf('expectancy: the last 30 trades give %s to %s, wholly below the backtest\'s %s to %s', $recent[0]->roundedTo(2), $recent[1]->roundedTo(2), $interval[0]->roundedTo(2), $interval[1]->roundedTo(2));
            }
        }

        $limit = self::decimal(self::at($report, 'monte_carlo', 'results', 'trades', 'drawdown', 'p95'));
        $drawdown = self::drawdown($nets, $startEquity);

        if ($limit !== null && $drawdown->isGreaterThan($limit)) {
            $breaches[] = sprintf('drawdown: %s %% against at most %s %% in the backtest\'s Monte Carlo', $drawdown->times(Decimal::of(100))->roundedTo(2), $limit->times(Decimal::of(100))->roundedTo(2));
        }

        $mean = self::decimal(self::at($report, 'out_of_sample', 'expectancy'));
        $trades = self::at($report, 'out_of_sample', 'trades');

        if (count($nets) >= self::TRADES && $mean !== null && $interval !== null && is_int($trades) && $trades > 1) {
            // The backtest's standard deviation, from its interval: half-width times sqrt(n) over 1.96.
            $sigma = $interval[1]->minus($interval[0])->dividedBy(Decimal::of(2), 18)->times(DecimalMath::sqrt(Decimal::of($trades)))->dividedBy(Decimal::of('1.96'), 18);
            [$k, $h] = [$sigma->times(Decimal::of(self::CUSUM_REFERENCE)), $sigma->times(Decimal::of(self::CUSUM_ALARM))];
            $sum = Decimal::zero();

            foreach ($nets as $index => $net) {
                $sum = $sum->plus($mean->minus($net)->minus($k));
                $sum = $sum->isNegative() ? Decimal::zero() : $sum;

                if ($sum->isGreaterThan($h)) {
                    $breaches[] = sprintf('CUSUM: the results fell under the backtest mean of %s by more than 4 standard deviations, at trade %d', $mean->roundedTo(2), $index + 1);

                    break;
                }
            }
        }

        $cost = Statistics::mean($costRates);

        if ($cost !== null && $cost->isGreaterThan($modelCost->times(Decimal::of(self::COST_TOLERANCE)))) {
            $breaches[] = sprintf('costs: %s %% of the notional a trade against %s %% in the cost model', $cost->times(Decimal::of(100))->roundedTo(2), $modelCost->times(Decimal::of(100))->roundedTo(2));
        }

        return $breaches;
    }

    /** @param list<Decimal> $nets */
    private static function drawdown(array $nets, Decimal $startEquity): Decimal
    {
        [$equity, $peak, $worst] = [$startEquity, $startEquity, Decimal::zero()];

        foreach ($nets as $net) {
            $equity = $equity->plus($net);
            $peak = $equity->isGreaterThan($peak) ? $equity : $peak;
            $drop = $peak->isPositive() ? $peak->minus($equity)->dividedBy($peak, 18) : Decimal::zero();
            $worst = $drop->isGreaterThan($worst) ? $drop : $worst;
        }

        return $worst;
    }

    /**
     * @param  array<array-key, mixed>  $report
     * @return array{Decimal, Decimal}|null
     */
    private static function interval(array $report): ?array
    {
        $pair = self::at($report, 'out_of_sample', 'interval');
        [$low, $high] = is_array($pair) ? [self::decimal($pair[0] ?? null), self::decimal($pair[1] ?? null)] : [null, null];

        return $low === null || $high === null ? null : [$low, $high];
    }

    /** @param array<array-key, mixed> $report */
    private static function at(array $report, string ...$keys): mixed
    {
        $value = $report;

        foreach ($keys as $key) {
            $value = is_array($value) ? ($value[$key] ?? null) : null;
        }

        return $value;
    }

    private static function decimal(mixed $value): ?Decimal
    {
        return is_string($value) || is_int($value) ? Decimal::of((string) $value) : null;
    }
}
