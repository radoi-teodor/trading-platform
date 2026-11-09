<?php

declare(strict_types=1);

use App\Strategy\Data\StrategyIdentity;

mutates(StrategyIdentity::class);

it('keys a strategy by name, version and parameters, whatever their order', function (): void {
    $a = new StrategyIdentity('donchian-breakout', 1, ['entry' => '20', 'exit' => '10']);
    $b = new StrategyIdentity('donchian-breakout', 1, ['exit' => '10', 'entry' => '20']);

    expect($a->key())->toBe($b->key())
        ->and($a->key())->toMatch('/^[0-9a-f]{8}$/')
        ->and($a->key())->toBe(substr(hash('sha256', 'donchian-breakout|1|entry=20;exit=10'), 0, 8))
        ->and($a->canonical())->toBe('donchian-breakout|1|entry=20;exit=10')
        ->and($a->label())->toBe('donchian-breakout@v1');
});

it('gives other parameters or another version another key', function (): void {
    $base = new StrategyIdentity('ma-trend', 1, ['fast' => '50', 'slow' => '200']);

    expect((new StrategyIdentity('ma-trend', 1, ['fast' => '20', 'slow' => '200']))->key())->not->toBe($base->key())
        ->and((new StrategyIdentity('ma-trend', 2, ['fast' => '50', 'slow' => '200']))->key())->not->toBe($base->key());
});

it('refuses a name that cannot go into a client order id', function (string $name): void {
    new StrategyIdentity($name, 1, []);
})->with(['', 'MA Trend', 'ma_trend', str_repeat('a', 41)])->throws(InvalidArgumentException::class);
