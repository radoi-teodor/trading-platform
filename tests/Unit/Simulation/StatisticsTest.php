<?php

declare(strict_types=1);

use App\Simulation\Metrics\Statistics;
use App\Support\Number\Decimal;
use App\Support\Number\DecimalMath;

mutates(Statistics::class);

/** @return list<Decimal> */
function decimals(string ...$values): array
{
    return array_values(array_map(fn (string $value): Decimal => Decimal::of($value), $values));
}

// Expected values: Python's decimal module with the same truncated divisions and rounded square roots.

it('takes the mean and the sample standard deviation', function (): void {
    expect((string) Statistics::mean(decimals('1', '2', '3', '4', '5')))->toBe('3')
        ->and((string) Statistics::standardDeviation(decimals('1', '2', '3', '4', '5')))->toBe('1.581138830084189666')
        ->and(Statistics::mean([]))->toBeNull()
        ->and(Statistics::standardDeviation(decimals('7')))->toBeNull();
});

it('measures the deviation of the losses only', function (): void {
    expect((string) Statistics::downsideDeviation(decimals('-0.1', '0.2', '-0.3')))->toBe('0.18257418583505537')
        ->and((string) Statistics::downsideDeviation(decimals('0.1', '0.2')))->toBe('0')
        ->and(Statistics::downsideDeviation([]))->toBeNull();
});

it('gives the 95 % interval of a mean with Student\'s t below 30 values and 1.96 from 30', function (): void {
    [$low, $high] = Statistics::confidenceInterval95(decimals('1', '2', '3', '4', '5')) ?? [null, null];
    $thirty = Statistics::confidenceInterval95(decimals(...array_map('strval', range(1, 30)))) ?? [null, null];

    expect((string) $low)->toBe('1.037071575426144072')
        ->and((string) $high)->toBe('4.962928424573855928')
        ->and((string) $thirty[0])->toBe('12.349740751408968035')
        ->and((string) $thirty[1])->toBe('18.650259248591031965')
        ->and(Statistics::confidenceInterval95(decimals('1')))->toBeNull();
});

it('uses the critical value of each sample size', function (): void {
    [$low, $high] = Statistics::confidenceInterval95(decimals('0', '2')) ?? [Decimal::zero(), Decimal::zero()];

    // n = 2: t(1) = 12.706, sd = sqrt(2), margin = 12.706 * sqrt(2) / sqrt(2)
    expect((string) $high->minus($low)->roundedTo(6))->toBe('25.412');
});

it('divides only by what is not zero', function (): void {
    expect((string) Statistics::ratio(Decimal::of('1'), Decimal::of('4')))->toBe('0.25')
        ->and(Statistics::ratio(Decimal::of('1'), Decimal::zero()))->toBeNull()
        ->and(Statistics::ratio(Decimal::of('1'), null))->toBeNull()
        ->and((string) Statistics::sum(decimals('0.1', '0.2')))->toBe('0.3');
});

it('uses Student\'s t up to 29 values', function (): void {
    $values = decimals(...array_map('strval', range(1, 29)));
    [, $high] = Statistics::confidenceInterval95($values) ?? [Decimal::zero(), Decimal::zero()];
    $margin = Decimal::of('2.048')->times(Statistics::standardDeviation($values) ?? Decimal::zero())
        ->dividedBy(DecimalMath::sqrt(Decimal::of(29)), 18);

    expect((string) $high)->toBe((string) Decimal::of('15')->plus($margin));
});
