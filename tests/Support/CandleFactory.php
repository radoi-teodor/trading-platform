<?php

declare(strict_types=1);

namespace Tests\Support;

use App\Market\Data\Candle;
use App\Support\Timeframe;
use Carbon\CarbonImmutable;
use Random\Engine\Xoshiro256StarStar;
use Random\Randomizer;

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

    /**
     * $count consecutive 1-minute candles from $start: a seeded random walk with varied ranges and volumes, so that
     * aggregates are not trivially equal to their parts.
     *
     * @return list<Candle>
     */
    public static function walk(string $start, int $count, int $seed = 7, string $price = '100.00'): array
    {
        $random = new Randomizer(new Xoshiro256StarStar($seed));
        $time = CarbonImmutable::parse($start, 'UTC');
        $cents = (int) round((float) $price * 100);
        $candles = [];

        for ($i = 0; $i < $count; $i++) {
            $open = $cents;
            $close = max(100, $open + $random->getInt(-40, 40));
            $high = max($open, $close) + $random->getInt(0, 25);
            $low = max(1, min($open, $close) - $random->getInt(0, 25));
            $volume = $random->getInt(0, 5000);
            $taker = $random->getInt(0, $volume);

            $candles[] = Candle::fromStrings(
                $time->addMinutes($i),
                Timeframe::OneMinute,
                self::cents($open),
                self::cents($high),
                self::cents($low),
                self::cents($close),
                self::thousandths($volume),
                self::cents($volume * $close),
                $random->getInt(0, 200),
                self::thousandths($taker),
            );
            $cents = $close;
        }

        return $candles;
    }

    private static function cents(int $value): string
    {
        return intdiv($value, 100).'.'.str_pad((string) ($value % 100), 2, '0', STR_PAD_LEFT);
    }

    private static function thousandths(int $value): string
    {
        return intdiv($value, 1000).'.'.str_pad((string) ($value % 1000), 3, '0', STR_PAD_LEFT);
    }
}
