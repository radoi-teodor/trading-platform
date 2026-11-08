<?php

declare(strict_types=1);

namespace App\Simulation\Metrics;

use App\Portfolio\Data\RoundTrip;
use App\Portfolio\Ledger;
use App\Support\Number\Decimal;
use App\Support\Number\DecimalMath;
use Carbon\CarbonImmutable;

/** The metrics of docs/simulation.md, section 6, from an account's equity and its ledger. */
final class Performance
{
    public static function measure(EquityTracker $equity, Ledger $ledger): PerformanceMetrics
    {
        $initial = $equity->initial;
        $final = $equity->finalEquity();
        $return = $final->dividedBy($initial, Statistics::SCALE)->minus(Decimal::of(1));
        $seconds = $equity->end()->getTimestamp() - $equity->start->getTimestamp();
        $cagr = self::cagr($initial, $final, $seconds);
        $daily = self::returns($equity->daily(), $equity);
        $mean = Statistics::mean($daily);
        $annual = DecimalMath::sqrt(Decimal::of(365));
        $trips = $ledger->roundTrips();

        $months = self::monthly($equity);
        $positive = count(array_filter($months, fn (array $month): bool => $month['return']->isPositive()));

        return new PerformanceMetrics(
            initialEquity: $initial,
            finalEquity: $final,
            netProfit: $final->minus($initial),
            return: $return,
            cagr: $cagr,
            maxDrawdown: $equity->maxDrawdown(),
            maxDrawdownSeconds: $equity->longestDrawdownSeconds(),
            sharpe: $mean === null ? null : self::times(Statistics::ratio($mean, Statistics::standardDeviation($daily)), $annual),
            sortino: $mean === null ? null : self::times(Statistics::ratio($mean, Statistics::downsideDeviation($daily)), $annual),
            calmar: $cagr === null ? null : Statistics::ratio($cagr, $equity->maxDrawdown()),
            timeInMarket: $equity->timeInMarket(),
            turnover: $ledger->tradedNotional()->dividedBy($initial, Statistics::SCALE),
            monthlyReturns: $months,
            positiveMonthsShare: $months === [] ? null : Decimal::of($positive)->dividedBy(Decimal::of(count($months)), Statistics::SCALE),
            trades: self::trades($ledger->roundTrips()),
        );
    }

    /** @param list<RoundTrip> $trips */
    private static function trades(array $trips): TradeStatistics
    {
        $net = array_map(fn (RoundTrip $trip): Decimal => $trip->netProfit, $trips);
        $wins = array_values(array_filter($net, fn (Decimal $profit): bool => $profit->isPositive()));
        $losses = array_values(array_filter($net, fn (Decimal $profit): bool => ! $profit->isPositive()));
        $r = array_values(array_filter(array_map(fn (RoundTrip $trip): ?Decimal => $trip->rMultiple, $trips)));
        $gross = Statistics::sum(array_map(fn (RoundTrip $trip): Decimal => $trip->grossProfit, $trips));
        $costs = Statistics::sum(array_map(fn (RoundTrip $trip): Decimal => $trip->fees->plus($trip->slippage), $trips));
        $lost = Statistics::sum($losses)->abs();

        return new TradeStatistics(
            count: count($trips),
            wins: count($wins),
            winRate: $trips === [] ? null : Decimal::of(count($wins))->dividedBy(Decimal::of(count($trips)), Statistics::SCALE),
            averageWin: Statistics::mean($wins),
            averageLoss: Statistics::mean($losses),
            profitFactor: Statistics::ratio(Statistics::sum($wins), $lost),
            expectancy: Statistics::mean($net),
            expectancyCi95: Statistics::confidenceInterval95($net),
            expectancyR: Statistics::mean($r),
            expectancyRCi95: Statistics::confidenceInterval95($r),
            grossProfit: $gross,
            fees: Statistics::sum(array_map(fn (RoundTrip $trip): Decimal => $trip->fees, $trips)),
            slippage: Statistics::sum(array_map(fn (RoundTrip $trip): Decimal => $trip->slippage, $trips)),
            costsShareOfGrossProfit: $gross->isPositive() ? $costs->dividedBy($gross, Statistics::SCALE) : null,
        );
    }

    /**
     * Returns between consecutive 00:00 UTC marks, starting from the initial equity.
     *
     * @param  array<string, Decimal>  $daily
     * @return list<Decimal>
     */
    private static function returns(array $daily, EquityTracker $equity): array
    {
        $returns = [];
        $previous = $equity->initial;

        foreach ($daily as $value) {
            $returns[] = $value->dividedBy($previous, Statistics::SCALE)->minus(Decimal::of(1));
            $previous = $value;
        }

        return $returns;
    }

    /** @return list<array{month: string, return: Decimal}> */
    private static function monthly(EquityTracker $equity): array
    {
        $points = $equity->points();
        array_shift($points);
        $ends = [];

        foreach ($points as $time => $value) {
            // A mark at 00:00 on the 1st closes the month before it.
            $ends[CarbonImmutable::parse($time, 'UTC')->subSecond()->format('Y-m')] = $value;
        }

        $months = [];
        $previous = $equity->initial;

        foreach ($ends as $month => $value) {
            $months[] = ['month' => $month, 'return' => $value->dividedBy($previous, Statistics::SCALE)->minus(Decimal::of(1))];
            $previous = $value;
        }

        return $months;
    }

    private static function cagr(Decimal $initial, Decimal $final, int $seconds): ?Decimal
    {
        if ($seconds <= 0 || ! $final->isPositive()) {
            return null;
        }

        $exponent = Decimal::of(365 * 86400)->dividedBy(Decimal::of($seconds), Statistics::SCALE);

        return DecimalMath::pow($final->dividedBy($initial, Statistics::SCALE), $exponent)->minus(Decimal::of(1));
    }

    private static function times(?Decimal $value, Decimal $factor): ?Decimal
    {
        return $value?->times($factor)->truncatedTo(Statistics::SCALE);
    }
}
