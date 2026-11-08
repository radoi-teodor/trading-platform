<?php

declare(strict_types=1);

namespace Tests\Support;

use App\Market\Data\Candle;
use App\Support\Timeframe;
use Carbon\CarbonImmutable;

final class CandleFactory
{
    public const string START = '2026-10-01 00:00:00';

    /** A consistent 1-minute candle $minute minutes after START. */
    public static function at(int $minute, string $close = '100', string $volume = '1'): Candle
    {
        $high = max(101.0, (float) $close + 1);
        $low = min(99.0, (float) $close - 1);

        return Candle::fromStrings(
            CarbonImmutable::parse(self::START, 'UTC')->addMinutes($minute),
            Timeframe::OneMinute,
            $close,
            (string) $high,
            (string) $low,
            $close,
            $volume,
            $volume === '0' ? '0' : '100',
            $volume === '0' ? 0 : 3,
            '0',
        );
    }

    public static function time(int $minute): CarbonImmutable
    {
        return CarbonImmutable::parse(self::START, 'UTC')->addMinutes($minute);
    }

    /**
     * The REST layout of the candle at $minute, in milliseconds.
     *
     * @return list<int|string>
     */
    public static function restRow(int $minute, string $close = '100'): array
    {
        $candle = self::at($minute, $close);
        $open = $candle->openTime->getTimestamp() * 1000;

        return [$open, (string) $candle->open, (string) $candle->high, (string) $candle->low, (string) $candle->close, '1', $open + 59_999, '100', 3, '0', '0', '0'];
    }
}
