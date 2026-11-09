<?php

declare(strict_types=1);

namespace App\Simulation\Report;

use App\Support\Number\Decimal;
use BackedEnum;
use Carbon\CarbonImmutable;
use InvalidArgumentException;
use Stringable;

/**
 * The one text form of a report (D-42): object keys sorted, decimals and times as strings, no float. The same
 * report gives the same bytes on every machine, so its SHA-256 identifies it.
 */
final class CanonicalJson
{
    public static function encode(mixed $value): string
    {
        return json_encode(self::normalize($value), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);
    }

    /** Indented, for files people read; the hash is always taken over encode(). */
    public static function pretty(mixed $value): string
    {
        return json_encode(self::normalize($value), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR)."\n";
    }

    public static function hash(mixed $value): string
    {
        return hash('sha256', self::encode($value));
    }

    private static function normalize(mixed $value): mixed
    {
        return match (true) {
            $value === null, is_bool($value), is_int($value), is_string($value) => $value,
            $value instanceof Decimal => (string) $value,
            $value instanceof CarbonImmutable => $value->utc()->format('Y-m-d\TH:i:s\Z'),
            $value instanceof BackedEnum => $value->value,
            $value instanceof Stringable => (string) $value,
            is_array($value) => self::normalizeArray($value),
            default => throw new InvalidArgumentException('A report holds no '.get_debug_type($value).'; floats and objects have no canonical form.'),
        };
    }

    /**
     * @param  array<mixed>  $value
     * @return array<mixed>
     */
    private static function normalizeArray(array $value): array
    {
        $normalized = array_map(self::normalize(...), $value);

        if (! array_is_list($value)) {
            ksort($normalized, SORT_STRING);
        }

        return $normalized;
    }
}
