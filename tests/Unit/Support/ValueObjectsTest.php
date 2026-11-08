<?php

declare(strict_types=1);

use App\Support\Asset;
use App\Support\Number\AssetMismatch;
use App\Support\Number\Decimal;
use App\Support\Number\InvalidNumber;
use App\Support\Number\Money;
use App\Support\Number\Percent;
use App\Support\Number\Price;
use App\Support\Number\Quantity;

mutates(Asset::class, Money::class, Percent::class, Price::class, Quantity::class);

it('normalizes asset codes and rejects invalid ones', function (): void {
    expect(Asset::of('btc')->code)->toBe('BTC')
        ->and(Asset::of('1000PEPE')->equals(Asset::of('1000pepe')))->toBeTrue();

    expect(fn () => Asset::of('BT C'))->toThrow(InvalidArgumentException::class)
        ->and(fn () => Asset::of(''))->toThrow(InvalidArgumentException::class)
        ->and(fn () => Asset::of(str_repeat('A', 21)))->toThrow(InvalidArgumentException::class);
});

it('keeps prices positive and rounds them to a tick in the asked direction', function (): void {
    $price = Price::of('100.019');

    expect((string) $price->roundDownToTick(Decimal::of('0.01')))->toBe('100.01')
        ->and((string) $price->roundUpToTick(Decimal::of('0.01')))->toBe('100.02')
        ->and($price->value->equals(Decimal::of('100.019')))->toBeTrue();

    expect(fn () => Price::of('0'))->toThrow(InvalidNumber::class)
        ->and(fn () => Price::of('-1'))->toThrow(InvalidNumber::class);
});

it('keeps quantities non-negative and rounds them down to the lot step only', function (): void {
    expect((string) Quantity::of('0.123456')->roundDownToStep(Decimal::of('0.0001')))->toBe('0.1234')
        ->and(Quantity::of('0')->isZero())->toBeTrue()
        ->and(Quantity::of('0.1')->isZero())->toBeFalse();

    expect(fn () => Quantity::of('-0.1'))->toThrow(InvalidNumber::class);
});

it('adds and subtracts money of the same asset, possibly below zero', function (): void {
    $usdt = Asset::of('USDT');
    $balance = Money::of('10.5', $usdt)->plus(Money::of('0.25', $usdt))->minus(Money::of('20', $usdt));

    expect((string) $balance)->toBe('-9.25 USDT')
        ->and($balance->amount->isNegative())->toBeTrue()
        ->and($balance->asset->equals($usdt))->toBeTrue()
        ->and((string) Money::of('3', $usdt)->negate())->toBe('-3 USDT');
});

it('refuses to mix assets', function (): void {
    Money::of('1', Asset::of('USDT'))->plus(Money::of('1', Asset::of('BTC')));
})->throws(AssetMismatch::class);

it('refuses to subtract a different asset', function (): void {
    Money::of('1', Asset::of('USDT'))->minus(Money::of('1', Asset::of('BNB')));
})->throws(AssetMismatch::class);

it('stores percentages as fractions', function (): void {
    $half = Percent::fromPercentage('0.5');

    expect((string) $half->fraction)->toBe('0.005')
        ->and((string) $half->percentage())->toBe('0.5')
        ->and((string) $half->of(Decimal::of('200')))->toBe('1')
        ->and(Percent::fromFraction(Decimal::of('0.25'))->equals(Percent::fromPercentage('25')))->toBeTrue();
});

it('keeps every digit of a tiny percentage', function (): void {
    expect((string) Percent::fromPercentage('0.00000000000000001')->fraction)->toBe('0.0000000000000000001');
});

it('accepts an existing decimal as an amount', function (): void {
    $amount = Decimal::of('1.5');

    expect(Money::of($amount, Asset::of('BTC'))->amount)->toBe($amount);
});
