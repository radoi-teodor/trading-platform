<?php

declare(strict_types=1);

use App\Support\Number\Decimal;
use App\Support\Number\DecimalMath;
use App\Support\Number\InvalidNumber;

mutates(DecimalMath::class);

// Reference values: Python's decimal module at 60 significant digits, rounded half up to 18 decimals.

it('takes natural logarithms to 18 decimals', function (string $x, string $expected): void {
    expect((string) DecimalMath::ln(Decimal::of($x)))->toBe($expected);
})->with([
    ['2', '0.693147180559945309'],
    ['10', '2.302585092994045684'],
    ['0.5', '-0.693147180559945309'],
    ['1', '0'],
    ['0.0000000001', '-23.02585092994045684'],
    ['1000000', '13.815510557964274104'],
    ['123456.789', '11.723646487185880981'],
]);

it('takes exponentials to 18 decimals', function (string $x, string $expected): void {
    expect((string) DecimalMath::exp(Decimal::of($x)))->toBe($expected);
})->with([
    ['1', '2.718281828459045235'],
    ['-1', '0.367879441171442322'],
    ['0', '1'],
    ['0.5', '1.648721270700128147'],
    ['10', '22026.465794806716516958'],
    ['-20', '0.000000002061153622'],
    ['100', '26881171418161354484126255515800135873611118.773741922415191609'],
    ['-0.3', '0.740818220681717866'],
]);

it('takes square roots to 18 decimals', function (string $x, string $expected): void {
    expect((string) DecimalMath::sqrt(Decimal::of($x)))->toBe($expected);
})->with([
    ['2', '1.414213562373095049'],
    ['365', '19.104973174542800179'],
    ['0.0004', '0.02'],
    ['0', '0'],
    ['1', '1'],
]);

it('raises to fractional and integer powers', function (string $base, string $exponent, string $expected): void {
    expect((string) DecimalMath::pow(Decimal::of($base), Decimal::of($exponent)))->toBe($expected);
})->with([
    ['1.05', '36.5', '5.934845479079401723'],
    ['1.21', '0.5', '1.1'],
    ['4', '-0.5', '0.5'],
    ['2', '10', '1024'],
    ['2', '-2', '0.25'],
    ['3', '0', '1'],
    ['0', '2.5', '0'],
    ['2', '0.5', '1.414213562373095049'],
    ['0.25', '1.5', '0.125'],
    ['1.3068', '182.5', '1614973718305298443511.785000725385196368'],
]);

// exp multiplies an error in its argument by its result, so ln is taken with more digits than the round trip keeps.
it('brings exp(ln x) back to x', function (string $x): void {
    expect((string) DecimalMath::exp(DecimalMath::ln(Decimal::of($x), 30)))->toBe($x);
})->with(['8', '0.001', '1.5', '98765.4321']);

it('honours a requested scale', function (): void {
    expect((string) DecimalMath::ln(Decimal::of('2'), 4))->toBe('0.6931')
        ->and((string) DecimalMath::exp(Decimal::of('1'), 3))->toBe('2.718')
        ->and((string) DecimalMath::sqrt(Decimal::of('2'), 2))->toBe('1.41')
        ->and((string) DecimalMath::sqrt(Decimal::of('2'), 3))->toBe('1.414')
        ->and((string) DecimalMath::exp(Decimal::of('-1'), 2))->toBe('0.37')
        ->and((string) DecimalMath::ln(Decimal::of('2'), 0))->toBe('1')
        ->and((string) DecimalMath::exp(Decimal::of('1'), 0))->toBe('3')
        ->and((string) DecimalMath::pow(Decimal::of('2'), Decimal::of('0.5'), 0))->toBe('1');
});

it('refuses what has no real result', function (callable $call): void {
    $call();
})->with([
    'ln of zero' => [fn () => DecimalMath::ln(Decimal::zero())],
    'ln of a negative' => [fn () => DecimalMath::ln(Decimal::of('-1'))],
    'sqrt of a negative' => [fn () => DecimalMath::sqrt(Decimal::of('-0.01'))],
    'zero to a negative power' => [fn () => DecimalMath::pow(Decimal::zero(), Decimal::of('-1'))],
    'zero to the power zero' => [fn () => DecimalMath::pow(Decimal::zero(), Decimal::zero())],
    'a negative base' => [fn () => DecimalMath::pow(Decimal::of('-2'), Decimal::of('0.5'))],
    'a negative scale' => [fn () => DecimalMath::ln(Decimal::of('2'), -1)],
])->throws(InvalidNumber::class);
