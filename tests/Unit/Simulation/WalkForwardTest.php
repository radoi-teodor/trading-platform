<?php

declare(strict_types=1);

use App\Simulation\Validation\WalkForward;
use App\Simulation\Validation\WalkForwardSet;
use App\Support\Number\Decimal;
use Carbon\CarbonImmutable;

mutates(WalkForward::class);

/** @param list<array{string, string, string}> $trades opened, closed, net */
function wfSet(string $name, array $trades): WalkForwardSet
{
    return new WalkForwardSet(['set' => $name], array_map(fn (array $t): array => [
        'opened' => CarbonImmutable::parse($t[0], 'UTC'), 'closed' => CarbonImmutable::parse($t[1], 'UTC'), 'net' => Decimal::of($t[2]),
    ], $trades));
}

it('chooses each in-sample window\'s best set by net expectancy and judges it only on the next window (D-57)', function (): void {
    $sets = [
        // in-sample 1: 10 a trade; out-of-sample 1: -5
        wfSet('a', [['2025-02-01', '2025-02-02', '10'], ['2025-03-01', '2025-03-02', '10'], ['2025-08-01', '2025-08-02', '-5']]),
        // steady, never the best
        wfSet('b', [['2025-02-03', '2025-02-04', '4'], ['2025-05-01', '2025-05-02', '6'], ['2025-08-03', '2025-08-04', '3'], ['2025-09-01', '2025-09-02', '3'], ['2025-11-01', '2025-11-02', '2'], ['2025-12-01', '2025-12-02', '-1']]),
        // one trade only in-sample 1 (under the minimum); best in-sample 2; its winner opens before out-of-sample 2
        wfSet('c', [['2025-03-03', '2025-03-04', '100'], ['2025-07-01', '2025-07-02', '50'], ['2025-07-03', '2025-07-04', '50'], ['2025-09-28', '2025-11-05', '20']]),
    ];
    $result = WalkForward::evaluate($sets, CarbonImmutable::parse('2025-01-01', 'UTC'), CarbonImmutable::parse('2026-01-01', 'UTC'), '6m', '3m', '3m', 2);

    expect(count($result->windows))->toBe(2)
        ->and($result->windows[0]->chosen)->toBe(['set' => 'a'])
        ->and((string) $result->windows[0]->inExpectancy)->toBe('10')
        ->and((string) $result->windows[0]->outNet)->toBe('-5')
        ->and($result->windows[0]->outFrom->toDateString())->toBe('2025-07-01')
        ->and($result->windows[1]->chosen)->toBe(['set' => 'c'])
        ->and((string) $result->windows[1]->inExpectancy)->toBe('50')
        ->and($result->windows[1]->outTrades)->toBe(1)
        ->and($result->windows[1]->startedEarlier)->toBe(1)
        ->and($result->positiveWindows)->toBe(1)
        ->and(array_map('strval', $result->outOfSample))->toBe(['-5', '20'])
        ->and((string) $result->net)->toBe('15')
        ->and((string) $result->expectancy)->toBe('7.5')
        ->and((string) $result->profitFactor)->toBe('4');
});

it('leaves a window without a choice when no set traded enough in-sample, and breaks ties by the order of the sets', function (): void {
    $sets = [
        wfSet('first', [['2025-02-01', '2025-02-02', '5'], ['2025-02-03', '2025-02-04', '5'], ['2025-08-01', '2025-08-02', '1']]),
        wfSet('second', [['2025-02-05', '2025-02-06', '5'], ['2025-02-07', '2025-02-08', '5'], ['2025-08-03', '2025-08-04', '-1']]),
    ];
    $tie = WalkForward::evaluate($sets, CarbonImmutable::parse('2025-01-01', 'UTC'), CarbonImmutable::parse('2025-10-01', 'UTC'), '6m', '3m', '3m', 2);
    $none = WalkForward::evaluate($sets, CarbonImmutable::parse('2025-01-01', 'UTC'), CarbonImmutable::parse('2025-10-01', 'UTC'), '6m', '3m', '3m', 3);

    expect($tie->windows[0]->chosen)->toBe(['set' => 'first'])
        ->and($none->windows[0]->chosen)->toBeNull()
        ->and($none->outOfSample)->toBe([])
        ->and($none->expectancy)->toBeNull();
});

it('refuses windows that do not fit', function (): void {
    WalkForward::evaluate([wfSet('a', [])], CarbonImmutable::parse('2025-01-01', 'UTC'), CarbonImmutable::parse('2025-06-01', 'UTC'), '12m', '3m', '3m', 1);
})->throws(InvalidArgumentException::class, 'A walk-forward of 12m in-sample and 3m out of sample needs more history: from 2025-01-01 its first window ends 2026-04-01, after 2025-06-01.');

it('steps by days for short histories', function (): void {
    $result = WalkForward::evaluate([wfSet('a', [['2025-01-12', '2025-01-12', '1']])], CarbonImmutable::parse('2025-01-01', 'UTC'), CarbonImmutable::parse('2025-02-04', 'UTC'), '10d', '5d', '5d', 1);

    expect(array_map(fn ($w): string => $w->outFrom->toDateString(), $result->windows))->toBe(['2025-01-11', '2025-01-16', '2025-01-21', '2025-01-26']);
});
