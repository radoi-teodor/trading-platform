<?php

declare(strict_types=1);

use App\Simulation\Validation\MonteCarlo;
use App\Support\Number\Decimal;

mutates(MonteCarlo::class);

// Xoshiro256** is specified to the bit, so these draws are the same on every machine and PHP version.
const PINNED_TRADES_RETURN = ['p5' => '-0.039', 'p50' => '0.0135', 'p95' => '0.0695'];
const PINNED_DAILY_DRAWDOWN = ['p5' => '0.011', 'p50' => '0.02395136', 'p95' => '0.031381582345687524'];

/** @return list<Decimal> */
function mcDecimals(string ...$values): array
{
    return array_map(Decimal::of(...), array_values($values));
}

/**
 * @param  array{p5: Decimal, p50: Decimal, p95: Decimal}  $percentiles
 * @return array<string, string>
 */
function percentiles(array $percentiles): array
{
    return array_map('strval', $percentiles);
}

it('gives one outcome when every trade and every day is the same', function (): void {
    $result = MonteCarlo::run(array_fill(0, 10, Decimal::of('10')), array_fill(0, 10, Decimal::of('0.01')), Decimal::of('1000'), seed: 7, resamples: 50);

    expect(percentiles($result['trades']['return']))->toBe(['p5' => '0.1', 'p50' => '0.1', 'p95' => '0.1'])
        ->and(percentiles($result['trades']['drawdown']))->toBe(['p5' => '0', 'p50' => '0', 'p95' => '0'])
        // one trade in ten is skipped: nine of 10
        ->and(percentiles($result['skip_10_percent']['return']))->toBe(['p5' => '0.09', 'p50' => '0.09', 'p95' => '0.09'])
        // 1.01^10 - 1 = 0.1046221254112045100100..., truncated at 18 decimals
        ->and((string) $result['daily_blocks']['return']['p50'])->toBe('0.10462212541120451')
        ->and(percentiles($result['daily_blocks']['losing_streak']))->toBe(['p5' => '0', 'p50' => '0', 'p95' => '0']);
});

it('measures drawdowns and losing streaks on every path', function (): void {
    $losses = MonteCarlo::run(mcDecimals('-100', '-100', '-100'), [], Decimal::of('1000'), seed: 1, resamples: 20);

    // 1000 to 700: 30 % below the peak, three losses in a row
    expect(percentiles($losses['trades']['drawdown']))->toBe(['p5' => '0.3', 'p50' => '0.3', 'p95' => '0.3'])
        ->and(percentiles($losses['trades']['losing_streak']))->toBe(['p5' => '3', 'p50' => '3', 'p95' => '3'])
        ->and($losses)->not->toHaveKey('daily_blocks');
});

it('draws the same numbers for the same seed on every machine, and others for another seed', function (): void {
    $trades = mcDecimals('120', '-80', '45', '-60', '200', '-150', '30', '-20', '90', '-110', '15', '60');
    $daily = mcDecimals('0.012', '-0.008', '0.004', '-0.02', '0.015', '0.001', '-0.003', '0.009', '-0.011', '0.006', '0.002', '-0.004');
    $first = MonteCarlo::run($trades, $daily, Decimal::of('10000'), seed: 2026);
    $again = MonteCarlo::run($trades, $daily, Decimal::of('10000'), seed: 2026);
    $other = MonteCarlo::run($trades, $daily, Decimal::of('10000'), seed: 2027);

    expect($first)->toEqual($again)
        ->and($first)->not->toEqual($other)
        ->and(percentiles($first['trades']['return']))->toBe(PINNED_TRADES_RETURN)
        ->and(percentiles($first['daily_blocks']['drawdown']))->toBe(PINNED_DAILY_DRAWDOWN)
        ->and($first['trades']['return']['p5']->isLessThan($first['trades']['return']['p95']))->toBeTrue();
});

it('takes the nearest-rank percentile', function (): void {
    $sorted = array_map(fn (int $i): Decimal => Decimal::of($i), range(1, 20));

    expect([(string) MonteCarlo::percentile($sorted, 5), (string) MonteCarlo::percentile($sorted, 50), (string) MonteCarlo::percentile($sorted, 95)])->toBe(['1', '10', '19'])
        ->and((string) MonteCarlo::percentile([Decimal::of(4)], 5))->toBe('4');
});

it('refuses an equity that is not positive', function (): void {
    MonteCarlo::run(mcDecimals('1'), [], Decimal::zero(), seed: 1);
})->throws(InvalidArgumentException::class, 'Monte Carlo needs a positive equity and at least one resample.');
