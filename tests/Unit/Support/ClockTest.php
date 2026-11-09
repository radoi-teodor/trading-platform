<?php

declare(strict_types=1);

use App\Support\Clock\SimClock;
use App\Support\Clock\WallClock;
use Carbon\CarbonImmutable;

mutates(SimClock::class, WallClock::class);

it('moves a simulated clock only when told, and only forward', function (): void {
    $clock = new SimClock(CarbonImmutable::parse('2026-10-01 00:00:00', 'UTC'));

    expect($clock->now()->toIso8601String())->toBe('2026-10-01T00:00:00+00:00');

    $clock->advanceTo(CarbonImmutable::parse('2026-10-01 00:01:00', 'UTC'));
    $clock->advanceBy(30);

    expect($clock->now()->toIso8601String())->toBe('2026-10-01T00:01:30+00:00');
    expect(fn () => $clock->advanceTo(CarbonImmutable::parse('2026-10-01 00:00:59', 'UTC')))
        ->toThrow(LogicException::class);
    expect(fn () => $clock->advanceBy(-1))->toThrow(LogicException::class);
});

it('treats advancing by zero seconds as staying put', function (): void {
    $clock = new SimClock(CarbonImmutable::parse('2026-10-01 00:00:00', 'UTC'));
    $clock->advanceBy(0);

    expect($clock->now()->toIso8601String())->toBe('2026-10-01T00:00:00+00:00');
});

it('keeps a simulated clock in UTC', function (): void {
    $clock = new SimClock(CarbonImmutable::parse('2026-10-01 03:00:00', 'Europe/Bucharest'));

    expect($clock->now()->getTimezone()->getName())->toBe('UTC')
        ->and($clock->now()->toIso8601String())->toBe('2026-10-01T00:00:00+00:00');
});

it('reads the wall clock in UTC', function (): void {
    $before = time();
    $now = (new WallClock)->now();

    expect($now)->toBeInstanceOf(CarbonImmutable::class)
        ->and($now->getTimezone()->getName())->toBe('UTC')
        ->and($now->getTimestamp())->toBeGreaterThanOrEqual($before)
        ->and($now->getTimestamp())->toBeLessThanOrEqual(time());
});
