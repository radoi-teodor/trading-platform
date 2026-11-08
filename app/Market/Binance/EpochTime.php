<?php

declare(strict_types=1);

namespace App\Market\Binance;

use Carbon\CarbonImmutable;
use UnexpectedValueException;

/**
 * Binance epoch timestamps. Spot archives switched from milliseconds to microseconds on 2025-01-01, so the unit
 * is recognized by the number of digits and anything else is refused (Known traps: archive time units).
 */
final class EpochTime
{
    public static function parse(int|string $value): CarbonImmutable
    {
        $digits = (string) $value;

        if (preg_match('/^\d+$/', $digits) !== 1) {
            throw new UnexpectedValueException("Not an epoch timestamp: '{$digits}'.");
        }

        $number = (int) $digits;

        return match (strlen($digits)) {
            13 => CarbonImmutable::createFromTimestampMsUTC($number),
            16 => CarbonImmutable::createFromTimestampMsUTC(intdiv($number, 1000)),
            default => throw new UnexpectedValueException("Unknown epoch unit for '{$digits}': expected 13 digits (ms) or 16 (µs)."),
        };
    }

    public static function milliseconds(CarbonImmutable $time): int
    {
        return (int) $time->utc()->getPreciseTimestamp(3);
    }
}
