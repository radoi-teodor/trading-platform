<?php

declare(strict_types=1);

use App\Simulation\Validation\Gates;
use App\Support\Number\Decimal;

mutates(Gates::class);

/** @return list<Decimal> $count trades alternating a win and a loss */
function gateTrades(int $count, string $win, string $loss): array
{
    return array_map(fn (int $i): Decimal => Decimal::of($i % 2 === 0 ? $win : $loss), range(0, $count - 1));
}

/**
 * @param  list<array{gate: string, passed: bool, value: string, threshold: string, advice: string}>  $checks
 * @return array{gate: string, passed: bool, value: string, threshold: string, advice: string}
 */
function gate(array $checks, string $name): array
{
    return array_values(array_filter($checks, fn (array $c): bool => $c['gate'] === $name))[0];
}

it('passes a strategy whose every out-of-sample number clears its gate', function (): void {
    $checks = Gates::evaluate(gateTrades(120, '30', '-10'), 6, 5, Decimal::of('0.97'), Decimal::of('0.85'), Decimal::of('4'), Decimal::of('0.06'), Decimal::of('0.10'));

    expect(Gates::passed($checks))->toBeTrue()
        ->and(array_column($checks, 'gate'))->toBe(['trades', 'edge', 'profit_factor', 'walk_forward', 'deflated_sharpe', 'robustness', 'cost_stress', 'outliers', 'drawdown'])
        ->and(gate($checks, 'profit_factor')['value'])->toBe('3')
        ->and(gate($checks, 'walk_forward')['value'])->toBe('5 of 6 windows positive')
        ->and(gate($checks, 'robustness')['value'])->toBe('85 %')
        ->and(gate($checks, 'drawdown')['value'])->toBe('6 %');
});

/** @return array{list<Decimal>, int, int, ?Decimal, ?Decimal, ?Decimal, ?Decimal, Decimal} inputs that pass every gate but $name */
function brokenGate(string $name): array
{
    return [
        match ($name) {
            'trades' => gateTrades(99, '30', '-10'),
            // wins of 11 and losses of 10: expectancy 0.5, with an interval reaching below zero and a profit factor of 1.1
            'edge', 'profit_factor' => gateTrades(120, '11', '-10'),
            default => gateTrades(120, '30', '-10'),
        },
        $name === 'walk_forward' ? 3 : 6,
        $name === 'walk_forward' ? 1 : 6,
        Decimal::of($name === 'deflated_sharpe' ? '0.94' : '0.99'),
        $name === 'robustness' ? Decimal::of('0.79') : null,
        Decimal::of($name === 'cost_stress' ? '-0.5' : '1'),
        Decimal::of($name === 'drawdown' ? '0.10' : '0.01'),
        Decimal::of('0.1'),
    ];
}

it('fails each gate on its own number, and says what would have to change', function (string $name): void {
    $a = brokenGate($name);
    $checks = Gates::evaluate($a[0], $a[1], $a[2], $a[3], $a[4], $a[5], $a[6], $a[7]);
    $failed = array_values(array_filter($checks, fn (array $c): bool => ! $c['passed']));

    expect(Gates::passed($checks))->toBeFalse()
        ->and(array_column($failed, 'gate'))->toContain($name)
        ->and(gate($checks, $name)['advice'])->not->toBe('');
})->with(['trades', 'edge', 'profit_factor', 'walk_forward', 'deflated_sharpe', 'robustness', 'cost_stress', 'drawdown']);

it('fails a result carried by its best trade', function (): void {
    // 99 losers of 1 and one winner of 200: positive expectancy only thanks to the winner
    $trades = [...array_fill(0, 99, Decimal::of('-1')), Decimal::of('200')];

    expect((string) Gates::withoutBest($trades))->toBe('-1')
        ->and(gate(Gates::evaluate($trades, 1, 1, null, null, null, null, Decimal::of('0.1')), 'outliers')['passed'])->toBeFalse()
        // without a loser the profit factor has no denominator; it passes and says why
        ->and(gate(Gates::evaluate(array_fill(0, 100, Decimal::of('2')), 1, 1, null, null, null, null, Decimal::of('0.1')), 'profit_factor')['value'])->toBe('no losing trade');
});

it('counts months and cycles for the carry template (D-60)', function (): void {
    $cycles = array_fill(0, 10, Decimal::of('50'));

    expect(gate(Gates::evaluate($cycles, 4, 4, null, null, null, null, Decimal::of('0.1'), carry: true, outOfSampleMonths: 12), 'trades')['passed'])->toBeTrue()
        ->and(gate(Gates::evaluate($cycles, 4, 4, null, null, null, null, Decimal::of('0.1'), carry: true, outOfSampleMonths: 11), 'trades')['passed'])->toBeFalse()
        ->and(gate(Gates::evaluate(array_slice($cycles, 1), 4, 4, null, null, null, null, Decimal::of('0.1'), carry: true, outOfSampleMonths: 24), 'trades')['value'])->toBe('9 cycles over 24 months');
});
