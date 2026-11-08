<?php

declare(strict_types=1);

namespace App\Support;

use Carbon\CarbonImmutable;
use InvalidArgumentException;

/** Candle timeframes, with Binance's alignment: weeks open on Monday 00:00 UTC, everything else on the epoch grid. */
enum Timeframe: string
{
    case OneMinute = '1m';
    case FiveMinutes = '5m';
    case FifteenMinutes = '15m';
    case OneHour = '1h';
    case FourHours = '4h';
    case OneDay = '1d';
    case OneWeek = '1w';

    /** 1970-01-05 was a Monday: the origin of the weekly grid. */
    private const int FIRST_MONDAY = 345_600;

    public function seconds(): int
    {
        return match ($this) {
            self::OneMinute => 60,
            self::FiveMinutes => 300,
            self::FifteenMinutes => 900,
            self::OneHour => 3_600,
            self::FourHours => 14_400,
            self::OneDay => 86_400,
            self::OneWeek => 604_800,
        };
    }

    public function openTimeContaining(CarbonImmutable $moment): CarbonImmutable
    {
        $origin = $this === self::OneWeek ? self::FIRST_MONDAY : 0;
        $seconds = $moment->getTimestamp() - $origin;
        $aligned = $seconds - ($seconds % $this->seconds()) + $origin;

        return CarbonImmutable::createFromTimestampUTC($aligned);
    }

    public function nextOpen(CarbonImmutable $open): CarbonImmutable
    {
        $this->assertAligned($open);

        return $open->addSeconds($this->seconds());
    }

    /** The last millisecond of the candle, as Binance reports close times. */
    public function closeTime(CarbonImmutable $open): CarbonImmutable
    {
        return $this->nextOpen($open)->subMillisecond();
    }

    private function assertAligned(CarbonImmutable $open): void
    {
        if (! $this->openTimeContaining($open)->equalTo($open)) {
            throw new InvalidArgumentException("{$open->toIso8601String()} is not a {$this->value} open time.");
        }
    }
}
