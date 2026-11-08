<?php

declare(strict_types=1);

use App\Simulation\Validation\Cscv;
use App\Simulation\Validation\DeflatedSharpe;
use App\Simulation\Validation\Normal;
use App\Support\Number\Decimal;

/*
 * The deflated Sharpe ratio and the probability of backtest overfitting (D-58) against values computed
 * independently with mpmath at 50 digits (tests/Fixtures/validation/generate.py).
 */

mutates(Normal::class, DeflatedSharpe::class, Cscv::class);

/** @return array<array-key, mixed> */
function validationReference(): array
{
    $json = json_decode((string) file_get_contents(dirname(__DIR__, 2).'/Fixtures/validation/reference.json'), true, flags: JSON_THROW_ON_ERROR);

    return is_array($json) ? $json : throw new UnexpectedValueException('reference.json is not an object.');
}

/** @return list<list<string>> */
function referenceRows(string $key): array
{
    $rows = validationReference()[$key] ?? null;
    $texts = [];

    foreach (is_array($rows) ? $rows : [] as $row) {
        $texts[] = array_values(array_map(referenceText(...), is_array($row) ? $row : []));
    }

    return $texts;
}

function referenceText(mixed $value): string
{
    return is_string($value) ? $value : (is_int($value) ? (string) $value : throw new UnexpectedValueException('Not text.'));
}

function closeTo(Decimal $actual, string $expected, string $tolerance = '0.000000000000001'): bool
{
    return ! $actual->minus(Decimal::of($expected))->abs()->isGreaterThan(Decimal::of($tolerance));
}

it('computes the normal distribution and its inverse', function (): void {
    foreach (referenceRows('cdf') as [$x, $p]) {
        expect(closeTo(Normal::cdf(Decimal::of($x)), $p))->toBeTrue("cdf({$x})");
    }

    foreach (referenceRows('inverse') as [$p, $x]) {
        expect(closeTo(Normal::inverse(Decimal::of($p)), $x, '0.000000000001'))->toBeTrue("inverse({$p})");
    }
});

it('computes the expected maximum Sharpe ratio of N trials', function (): void {
    foreach (referenceRows('expected_maximum') as [$n, $variance, $expected]) {
        expect(closeTo(DeflatedSharpe::expectedMaximum((int) $n, Decimal::of($variance)), $expected, '0.000000000001'))
            ->toBeTrue("N = {$n}");
    }
});

it('computes the probabilistic and deflated Sharpe ratios', function (): void {
    foreach (referenceRows('probability') as [$sharpe, $t, $skew, $kurt, $benchmark, $expected]) {
        $actual = DeflatedSharpe::probability(Decimal::of($sharpe), (int) $t, Decimal::of($skew), Decimal::of($kurt), Decimal::of($benchmark));

        expect(closeTo($actual, $expected, '0.000000000001'))->toBeTrue("{$sharpe} over {$t}: {$actual}");
    }
});

it('computes the moments of returns', function (): void {
    $reference = validationReference();
    $sample = array_map(fn (mixed $v): Decimal => Decimal::of(referenceText($v)), is_array($reference['sample']) ? array_values($reference['sample']) : []);
    $expected = is_array($reference['moments']) ? $reference['moments'] : [];
    $moments = DeflatedSharpe::moments($sample);

    foreach (['mean', 'deviation', 'skewness', 'kurtosis'] as $key) {
        expect(closeTo($moments[$key], referenceText($expected[$key] ?? null)))->toBeTrue($key);
    }
});

it('estimates the probability of backtest overfitting exactly as the independent reference', function (): void {
    $reference = validationReference()['cscv'];
    $reference = is_array($reference) ? $reference : [];
    $matrix = array_map(
        fn (mixed $series): array => array_map(fn (mixed $v): Decimal => Decimal::of(referenceText($v)), is_array($series) ? array_values($series) : []),
        is_array($reference['returns']) ? array_values($reference['returns']) : [],
    );
    $result = Cscv::overfitting($matrix, 16);

    expect($result['combinations'])->toBe(12870)
        ->and($result['below'])->toBe($reference['below'])
        ->and($result['days'])->toBe(48)
        ->and((string) $result['probability'])->toBe((string) Decimal::of(referenceText($reference['below']))->dividedBy(Decimal::of(12870), 18));
});

it('refuses inputs without meaning', function (callable $call): void {
    $call();
})->with([
    'inverse of 1' => [fn () => Normal::inverse(Decimal::of(1))],
    'no trials' => [fn () => DeflatedSharpe::expectedMaximum(0, Decimal::of('0.1'))],
    'one observation' => [fn () => DeflatedSharpe::probability(Decimal::of('0.1'), 1, Decimal::zero(), Decimal::of(3), Decimal::zero())],
    'one configuration' => [fn () => Cscv::overfitting([[Decimal::zero()]])],
    'too few days' => [fn () => Cscv::overfitting(array_fill(0, 2, array_fill(0, 20, Decimal::zero())))],
    'odd blocks' => [fn () => Cscv::overfitting(array_fill(0, 2, array_fill(0, 40, Decimal::zero())), 15)],
])->throws(InvalidArgumentException::class);
