<?php

declare(strict_types=1);

use App\Support\Number\Decimal;
use App\Support\Number\InvalidNumber;

mutates(Decimal::class);

it('adds without binary rounding errors', function (): void {
    expect(Decimal::of('0.1')->plus(Decimal::of('0.2'))->equals(Decimal::of('0.3')))->toBeTrue();
});

it('accepts plain decimal strings and integers', function (string|int $input, string $expected): void {
    expect((string) Decimal::of($input))->toBe($expected);
})->with([
    ['10', '10'],
    ['-0.5', '-0.5'],
    ['1.2300', '1.23'],
    ['0.000', '0'],
    ['-0.000', '0'],
    [42, '42'],
    ['007.10', '7.1'],
]);

it('rejects anything that is not a plain decimal', function (string $input): void {
    Decimal::of($input);
})->with(['', 'abc', '1e5', ' 1', '1 ', '+1', '.5', '1.', '1,5', 'NaN', 'INF'])->throws(InvalidNumber::class);

it('subtracts, multiplies and negates exactly', function (): void {
    expect((string) Decimal::of('1')->minus(Decimal::of('1.25')))->toBe('-0.25')
        ->and((string) Decimal::of('0.1')->times(Decimal::of('3')))->toBe('0.3')
        ->and((string) Decimal::of('2.5')->negate())->toBe('-2.5')
        ->and((string) Decimal::of('-2.5')->abs())->toBe('2.5');
});

it('divides with an explicit scale, truncating towards zero', function (): void {
    expect((string) Decimal::of('10')->dividedBy(Decimal::of('3'), 4))->toBe('3.3333')
        ->and((string) Decimal::of('-10')->dividedBy(Decimal::of('3'), 2))->toBe('-3.33');
});

it('refuses to divide by zero', function (): void {
    Decimal::of('1')->dividedBy(Decimal::zero(), 2);
})->throws(InvalidNumber::class);

it('compares by value, not by scale', function (): void {
    expect(Decimal::of('1.50')->equals(Decimal::of('1.5')))->toBeTrue()
        ->and(Decimal::of('1.5')->compare(Decimal::of('2')))->toBe(-1)
        ->and(Decimal::of('2')->compare(Decimal::of('1.5')))->toBe(1)
        ->and(Decimal::of('2')->isGreaterThan(Decimal::of('1.5')))->toBeTrue()
        ->and(Decimal::of('1.5')->isLessThan(Decimal::of('2')))->toBeTrue()
        ->and(Decimal::zero()->isZero())->toBeTrue()
        ->and(Decimal::of('-0.1')->isNegative())->toBeTrue()
        ->and(Decimal::of('0.1')->isPositive())->toBeTrue()
        ->and(Decimal::zero()->isPositive())->toBeFalse()
        ->and(Decimal::zero()->isNegative())->toBeFalse();
});

it('rounds down and up to a multiple of a step', function (string $value, string $step, string $down, string $up): void {
    $decimal = Decimal::of($value);

    expect((string) $decimal->roundDownToMultipleOf(Decimal::of($step)))->toBe($down)
        ->and((string) $decimal->roundUpToMultipleOf(Decimal::of($step)))->toBe($up);
})->with([
    ['1.23456', '0.001', '1.234', '1.235'],
    ['0.00099', '0.001', '0', '0.001'],
    ['100.01', '0.01', '100.01', '100.01'],
    ['7', '2', '6', '8'],
    ['-1.5', '1', '-2', '-1'],
]);

it('requires a positive step', function (string $step): void {
    Decimal::of('1')->roundDownToMultipleOf(Decimal::of($step));
})->with(['0', '-0.1'])->throws(InvalidNumber::class);
