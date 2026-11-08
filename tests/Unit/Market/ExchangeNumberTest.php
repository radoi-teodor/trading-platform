<?php

declare(strict_types=1);

use App\Market\Binance\ExchangeNumber;

mutates(ExchangeNumber::class);

// Binance writes small funding rates in scientific notation in its archives (9.2E-7, -7E-8, 0E-8).
it('turns scientific notation into an exact plain decimal', function (string $input, string $expected): void {
    expect(ExchangeNumber::plain($input))->toBe($expected);
})->with([
    ['9.2E-7', '0.00000092'],
    ['-7E-8', '-0.00000007'],
    ['0E-8', '0.00000000'],
    ['1.5e3', '1500'],
    ['1.25E+2', '125'],
    ['-3.75E1', '-37.5'],
    ['12.5E-1', '1.25'],
    ['9.2E-1', '0.92'],
    ['5E0', '5'],
    ['0E2', '0'],
    ['00.5E1', '5'],
    ['0.00010000', '0.00010000'],
    ['76050.00', '76050.00'],
    ['42', '42'],
]);

it('refuses anything that is not a number', function (string $input): void {
    ExchangeNumber::plain($input);
})->with(['', 'abc', '1e', 'E5', '1.2.3', '1e5.5', ' 1'])->throws(UnexpectedValueException::class);
