<?php

declare(strict_types=1);

use App\Simulation\Validation\Band;
use App\Support\Number\Decimal;

/**
 * A gate report as stored: out-of-sample expectancy 10 with its interval over 100 trades, Monte Carlo p95 drawdown 5 %.
 *
 * @return array<string, mixed>
 */
function bandReport(): array
{
    return [
        'out_of_sample' => ['trades' => 100, 'expectancy' => '10', 'interval' => ['4', '16']],
        'monte_carlo' => ['results' => ['trades' => ['drawdown' => ['p95' => '0.05']]]],
    ];
}

/** @return list<Decimal> */
function nets(string ...$values): array
{
    return array_map(fn (string $v): Decimal => Decimal::of($v), array_values($values));
}

/** @return list<Decimal> */
function repeated(string $value, int $times): array
{
    return array_fill(0, $times, Decimal::of($value));
}

it('keeps results like the backtest in band', function (): void {
    $results = array_merge(...array_fill(0, 15, nets('20', '0')));

    expect(Band::breaches($results, repeated('0.002', 30), Decimal::of('10000'), bandReport(), Decimal::of('0.002')))->toBe([]);
});

it('finds the last 30 trades\' interval wholly below the backtest\'s', function (): void {
    $results = array_merge(...array_fill(0, 15, nets('-1', '-3')));

    expect(Band::breaches($results, [], Decimal::of('1000000'), bandReport(), Decimal::of('0.002'))[0] ?? '')->toStartWith('expectancy: the last 30 trades');
});

it('finds a drawdown beyond the Monte Carlo 95th percentile, with any number of trades', function (): void {
    expect(Band::breaches(nets('100', '-700'), [], Decimal::of('10000'), bandReport(), Decimal::of('0.002')))
        ->toBe(['drawdown: 6.93 % against at most 5 % in the backtest\'s Monte Carlo']);
});

it('raises the CUSUM alarm on a run of results under the backtest mean', function (): void {
    // sigma from the report: (16 - 4) / 2 * sqrt(100) / 1.96 = 30.6; the alarm at 4 sigma.
    $results = [...repeated('20', 20), ...repeated('-20', 30)];

    expect(array_values(array_filter(Band::breaches($results, [], Decimal::of('1000000'), bandReport(), Decimal::of('0.002')), fn (string $b): bool => str_starts_with($b, 'CUSUM'))))
        ->toHaveCount(1);
});

it('finds costs more than 50 % above the cost model', function (): void {
    expect(Band::breaches(nets('10'), repeated('0.0031', 1), Decimal::of('10000'), bandReport(), Decimal::of('0.002')))
        ->toBe(['costs: 0.31 % of the notional a trade against 0.2 % in the cost model']);
});
