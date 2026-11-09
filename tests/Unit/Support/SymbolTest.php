<?php

declare(strict_types=1);

use App\Support\Symbol;

mutates(Symbol::class);

it('parses spot and perpetual symbols', function (): void {
    $spot = Symbol::parse('BTC/USDT');
    $perp = Symbol::parse('btc/usdt:usdt');

    expect($spot->base->code)->toBe('BTC')
        ->and($spot->quote->code)->toBe('USDT')
        ->and($spot->settle)->toBeNull()
        ->and($spot->isPerpetual())->toBeFalse()
        ->and((string) $spot)->toBe('BTC/USDT')
        ->and($spot->venueSymbol())->toBe('BTCUSDT');

    expect($perp->settle?->code)->toBe('USDT')
        ->and($perp->isPerpetual())->toBeTrue()
        ->and((string) $perp)->toBe('BTC/USDT:USDT')
        ->and($perp->venueSymbol())->toBe('BTCUSDT');
});

it('keeps a settle asset that differs from the quote', function (): void {
    $inverse = Symbol::parse('BTC/USD:BTC');

    expect($inverse->settle?->code)->toBe('BTC')
        ->and((string) $inverse)->toBe('BTC/USD:BTC');
});

it('parses symbols whose base is not ASCII', function (): void {
    $symbol = Symbol::parse('币安人生/USDT');

    expect($symbol->base->code)->toBe('币安人生')
        ->and($symbol->venueSymbol())->toBe('币安人生USDT')
        ->and((string) $symbol)->toBe('币安人生/USDT');
});

it('compares symbols by value', function (): void {
    expect(Symbol::parse('ETH/USDT')->equals(Symbol::parse('eth/usdt')))->toBeTrue()
        ->and(Symbol::parse('ETH/USDT')->equals(Symbol::parse('ETH/USDT:USDT')))->toBeFalse();
});

it('rejects malformed symbols', function (string $input): void {
    Symbol::parse($input);
})->with(['BTCUSDT', 'BTC-USD', 'BTC/', '/USDT', 'BTC/USDT:', 'BTC/USDT:USDT:X', ''])->throws(InvalidArgumentException::class);
