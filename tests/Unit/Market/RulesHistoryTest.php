<?php

declare(strict_types=1);

use App\Market\Data\InstrumentRules;
use App\Market\Data\RulesHistory;
use App\Support\Number\Decimal;
use App\Support\Symbol;
use Carbon\CarbonImmutable;

mutates(RulesHistory::class, InstrumentRules::class);

function rules(string $validFrom, string $step): InstrumentRules
{
    return new InstrumentRules(
        CarbonImmutable::parse($validFrom, 'UTC'),
        tickSize: Decimal::of('0.01'),
        stepSize: Decimal::of($step),
        minQty: Decimal::of($step),
        maxQty: Decimal::of('9000'),
        minNotional: Decimal::of('5'),
    );
}

it('gives the filters in force at a moment', function (): void {
    $history = new RulesHistory(Symbol::parse('BTC/USDT'), [
        rules('2026-11-08 12:00:00', '0.00001'),
        rules('2026-12-01 00:00:00', '0.0001'),
    ]);

    expect((string) $history->at(CarbonImmutable::parse('2026-11-20', 'UTC'))->stepSize)->toBe('0.00001')
        ->and((string) $history->at(CarbonImmutable::parse('2026-12-01 00:00:00', 'UTC'))->stepSize)->toBe('0.0001')
        ->and((string) $history->at(CarbonImmutable::parse('2027-01-01', 'UTC'))->stepSize)->toBe('0.0001');
});

it('uses the oldest known filters before the first one and says so', function (): void {
    $history = new RulesHistory(Symbol::parse('BTC/USDT'), [
        rules('2026-12-01 00:00:00', '0.0001'),
        rules('2026-11-08 12:00:00', '0.00001'),
    ]);
    $october = CarbonImmutable::parse('2026-10-15', 'UTC');

    expect((string) $history->at($october)->stepSize)->toBe('0.00001')
        ->and($history->isAssumedAt($october))->toBeTrue()
        ->and($history->isAssumedAt(CarbonImmutable::parse('2026-11-08 12:00:00', 'UTC')))->toBeFalse()
        ->and($history->knownFrom()->toIso8601String())->toBe('2026-11-08T12:00:00+00:00');
});

it('needs at least one set of filters', function (): void {
    new RulesHistory(Symbol::parse('BTC/USDT'), []);
})->throws(InvalidArgumentException::class, 'BTC/USDT');

it('refuses filters that cannot be applied', function (string $field): void {
    $values = ['tickSize' => '0.01', 'stepSize' => '0.001', 'minQty' => '0'];
    $values[$field] = $field === 'minQty' ? '-1' : '0';

    new InstrumentRules(
        CarbonImmutable::parse('2026-11-08', 'UTC'),
        Decimal::of($values['tickSize']),
        Decimal::of($values['stepSize']),
        Decimal::of($values['minQty']),
        null,
        null,
    );
})->with(['tickSize', 'stepSize', 'minQty'])->throws(InvalidArgumentException::class);
