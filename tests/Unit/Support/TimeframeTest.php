<?php

declare(strict_types=1);

use App\Support\Timeframe;
use Carbon\CarbonImmutable;

mutates(Timeframe::class);

it('knows its length in seconds', function (Timeframe $timeframe, int $seconds): void {
    expect($timeframe->seconds())->toBe($seconds);
})->with([
    [Timeframe::OneMinute, 60],
    [Timeframe::FiveMinutes, 300],
    [Timeframe::FifteenMinutes, 900],
    [Timeframe::OneHour, 3600],
    [Timeframe::FourHours, 14400],
    [Timeframe::OneDay, 86400],
    [Timeframe::OneWeek, 604800],
]);

it('finds the open time of the candle that contains a moment', function (Timeframe $timeframe, string $expected): void {
    $moment = CarbonImmutable::parse('2026-11-08 14:34:56.789', 'UTC');

    expect($timeframe->openTimeContaining($moment)->toIso8601String())->toBe($expected);
})->with([
    [Timeframe::OneMinute, '2026-11-08T14:34:00+00:00'],
    [Timeframe::FifteenMinutes, '2026-11-08T14:30:00+00:00'],
    [Timeframe::OneHour, '2026-11-08T14:00:00+00:00'],
    [Timeframe::FourHours, '2026-11-08T12:00:00+00:00'],
    [Timeframe::OneDay, '2026-11-08T00:00:00+00:00'],
    // Binance weeks start on Monday 00:00 UTC; 2026-11-08 is a Sunday.
    [Timeframe::OneWeek, '2026-11-02T00:00:00+00:00'],
]);

it('works in UTC whatever the zone of the input', function (): void {
    $bucharest = CarbonImmutable::parse('2026-11-08 01:30:00', 'Europe/Bucharest');

    expect(Timeframe::OneDay->openTimeContaining($bucharest)->toIso8601String())->toBe('2026-11-07T00:00:00+00:00');
});

it('gives the close and the next open of a candle', function (): void {
    $open = CarbonImmutable::parse('2026-11-08 14:00:00', 'UTC');

    expect(Timeframe::OneHour->nextOpen($open)->toIso8601String())->toBe('2026-11-08T15:00:00+00:00')
        ->and(Timeframe::OneHour->closeTime($open)->format('Y-m-d H:i:s.v'))->toBe('2026-11-08 14:59:59.999');
});

it('refuses an open time that is not aligned', function (): void {
    Timeframe::OneHour->nextOpen(CarbonImmutable::parse('2026-11-08 14:01:00', 'UTC'));
})->throws(InvalidArgumentException::class);
