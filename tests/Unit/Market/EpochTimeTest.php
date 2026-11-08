<?php

declare(strict_types=1);

use App\Market\Binance\EpochTime;
use Carbon\CarbonImmutable;

mutates(EpochTime::class);

it('reads milliseconds and microseconds as the same moment', function (): void {
    $milliseconds = EpochTime::parse(1793923200000);
    $microseconds = EpochTime::parse('1793923200000000');

    expect($milliseconds->toIso8601String())->toBe('2026-11-06T00:00:00+00:00')
        ->and($microseconds->equalTo($milliseconds))->toBeTrue()
        ->and(EpochTime::parse('1793923259999')->format('H:i:s.v'))->toBe('00:00:59.999');
});

it('refuses any other unit or shape', function (int|string $value): void {
    EpochTime::parse($value);
})->with([1793923200, '17939232000', '1793923200000000000', '-1793923200000', '1793923200000.5', ''])->throws(UnexpectedValueException::class);

it('converts a moment to milliseconds', function (): void {
    expect(EpochTime::milliseconds(CarbonImmutable::parse('2026-11-06 00:00:59.999', 'UTC')))->toBe(1793923259999);
});
