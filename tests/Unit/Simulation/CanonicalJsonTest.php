<?php

declare(strict_types=1);

use App\Market\Data\MarketType;
use App\Simulation\Report\CanonicalJson;
use App\Support\Number\Decimal;
use App\Support\Symbol;
use Carbon\CarbonImmutable;

mutates(CanonicalJson::class);

it('sorts object keys at every level and keeps lists in order', function (): void {
    $value = ['b' => ['z' => 1, 'a' => [3, 1, 2]], 'a' => true, 'c' => null];

    expect(CanonicalJson::encode($value))->toBe('{"a":true,"b":{"a":[3,1,2],"z":1},"c":null}');
});

it('writes decimals, times, enums and symbols as text', function (): void {
    $value = [
        'price' => Decimal::of('0.10'),
        'time' => CarbonImmutable::parse('2026-10-01 02:00:00', 'Europe/Bucharest'),
        'market' => MarketType::Spot,
        'symbol' => Symbol::parse('BTC/USDT'),
        'name' => 'ăî/µ',
    ];

    expect(CanonicalJson::encode($value))
        ->toBe('{"market":"spot","name":"ăî/µ","price":"0.1","symbol":"BTC/USDT","time":"2026-09-30T23:00:00Z"}');
});

it('identifies a report by the hash of its canonical text, whatever the key order', function (): void {
    $one = CanonicalJson::hash(['a' => '1', 'b' => ['c' => '2', 'd' => '3']]);
    $two = CanonicalJson::hash(['b' => ['d' => '3', 'c' => '2'], 'a' => '1']);

    expect($one)->toBe($two)
        ->and($one)->toBe(hash('sha256', '{"a":"1","b":{"c":"2","d":"3"}}'));
});

it('prints the same content indented for people', function (): void {
    expect(CanonicalJson::pretty(['b' => 'a/ă', 'a' => '1']))->toBe("{\n    \"a\": \"1\",\n    \"b\": \"a/ă\"\n}\n");
});

it('refuses a float, which has no canonical form', function (): void {
    CanonicalJson::encode(['ratio' => 0.1]);
})->throws(InvalidArgumentException::class, 'float');
