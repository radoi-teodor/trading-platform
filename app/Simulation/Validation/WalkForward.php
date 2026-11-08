<?php

declare(strict_types=1);

namespace App\Simulation\Validation;

use App\Simulation\Metrics\Statistics;
use App\Support\Number\Decimal;
use Carbon\CarbonImmutable;
use InvalidArgumentException;

/**
 * Walk-forward on continuous runs (docs/simulation.md, section 5; D-57). Windows roll from the start of the history:
 * $in in-sample, then $out out of sample, stepped by $step, as long as the out-of-sample window fits. Lengths are
 * months (12m) or days (20d). In each in-sample window the set with the highest net expectancy per trade among those with at least
 * $minTrades trades is chosen (ties: the set listed first); it is judged only on the following window. A trade
 * counts in the window where it closes, so it may have opened before; the result says how many did.
 */
final class WalkForward
{
    /** @param list<WalkForwardSet> $sets */
    public static function evaluate(array $sets, CarbonImmutable $from, CarbonImmutable $to, string $in = '12m', string $out = '3m', string $step = '3m', int $minTrades = 100): WalkForwardResult
    {
        [$after, $add, $next] = [self::length($in), self::length($out), self::length($step)];

        if ($add($after($from))->isAfter($to)) {
            throw new InvalidArgumentException("A walk-forward of {$in} in-sample and {$out} out of sample needs more history: from {$from->toDateString()} its first window ends {$add($after($from))->toDateString()}, after {$to->toDateString()}.");
        }

        $windows = [];
        $outOfSample = [];

        for ($start = $from; ! $add($after($start))->isAfter($to); $start = $next($start)) {
            $outFrom = $after($start);
            $outTo = $add($outFrom);
            [$chosen, $inExpectancy, $inTrades] = self::choose($sets, $start, $outFrom, $minTrades);
            $trades = $chosen === null ? [] : self::closedIn($chosen, $outFrom, $outTo);
            $nets = array_map(fn (array $trade): Decimal => $trade['net'], $trades);
            array_push($outOfSample, ...$nets);
            $windows[] = new WalkForwardWindow(
                $start, $outFrom, $outTo, $chosen?->parameters, $inExpectancy, $inTrades, count($trades), Statistics::sum($nets),
                count(array_filter($trades, fn (array $trade): bool => $trade['opened'] < $outFrom)),
            );
        }

        $wins = Statistics::sum(array_values(array_filter($outOfSample, fn (Decimal $net): bool => $net->isPositive())));
        $losses = Statistics::sum(array_values(array_filter($outOfSample, fn (Decimal $net): bool => $net->isNegative())))->abs();

        return new WalkForwardResult(
            $windows,
            $outOfSample,
            count(array_filter($windows, fn (WalkForwardWindow $w): bool => $w->outNet->isPositive())),
            array_sum(array_map(fn (WalkForwardWindow $w): int => $w->startedEarlier, $windows)),
            Statistics::sum($outOfSample),
            Statistics::mean($outOfSample),
            self::interval($outOfSample),
            $losses->isZero() ? null : $wins->dividedBy($losses, Statistics::SCALE),
        );
    }

    /**
     * @param  list<Decimal>  $values
     * @return array{low: Decimal, high: Decimal}|null
     */
    private static function interval(array $values): ?array
    {
        $interval = count($values) >= 2 ? Statistics::confidenceInterval95($values) : null;

        return $interval === null ? null : ['low' => $interval[0], 'high' => $interval[1]];
    }

    /**
     * Moves a time forward by a length written as months (12m) or days (20d).
     *
     * @return callable(CarbonImmutable): CarbonImmutable
     */
    public static function length(string $length): callable
    {
        if (preg_match('/^([1-9]\d{0,3})([md])$/', $length, $match) !== 1) {
            throw new InvalidArgumentException("{$length} is not a length; write months as 12m or days as 20d.");
        }

        $amount = (int) $match[1];

        return $match[2] === 'm'
            ? fn (CarbonImmutable $time): CarbonImmutable => $time->addMonths($amount)
            : fn (CarbonImmutable $time): CarbonImmutable => $time->addDays($amount);
    }

    /**
     * @param  list<WalkForwardSet>  $sets
     * @return array{?WalkForwardSet, ?Decimal, int}
     */
    private static function choose(array $sets, CarbonImmutable $from, CarbonImmutable $to, int $minTrades): array
    {
        [$best, $bestExpectancy, $bestTrades] = [null, null, 0];

        foreach ($sets as $set) {
            $nets = array_map(fn (array $trade): Decimal => $trade['net'], self::closedIn($set, $from, $to));
            $expectancy = count($nets) >= max(1, $minTrades) ? Statistics::mean($nets) : null;

            if ($expectancy !== null && ($bestExpectancy === null || $expectancy->isGreaterThan($bestExpectancy))) {
                [$best, $bestExpectancy, $bestTrades] = [$set, $expectancy, count($nets)];
            }
        }

        return [$best, $bestExpectancy, $bestTrades];
    }

    /** @return list<array{opened: CarbonImmutable, closed: CarbonImmutable, net: Decimal}> */
    private static function closedIn(WalkForwardSet $set, CarbonImmutable $from, CarbonImmutable $to): array
    {
        return array_values(array_filter($set->trades, fn (array $trade): bool => $trade['closed'] >= $from && $trade['closed'] < $to));
    }
}
