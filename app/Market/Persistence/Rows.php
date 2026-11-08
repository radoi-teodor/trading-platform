<?php

declare(strict_types=1);

namespace App\Market\Persistence;

use Carbon\CarbonImmutable;
use UnexpectedValueException;

/** Typed reads from database rows; anything unexpected fails loudly instead of being cast. */
final class Rows
{
    public static function string(object $row, string $column): string
    {
        $value = $row->{$column} ?? null;

        if (is_string($value)) {
            return $value;
        }

        if (is_int($value)) {
            return (string) $value;
        }

        throw new UnexpectedValueException("Column {$column} is not a string or integer.");
    }

    public static function nullableString(object $row, string $column): ?string
    {
        return ($row->{$column} ?? null) === null ? null : self::string($row, $column);
    }

    public static function int(object $row, string $column): int
    {
        $value = $row->{$column} ?? null;

        if (is_int($value)) {
            return $value;
        }

        if (is_string($value) && preg_match('/^-?\d+$/', $value) === 1) {
            return (int) $value;
        }

        throw new UnexpectedValueException("Column {$column} is not an integer.");
    }

    public static function time(object $row, string $column): CarbonImmutable
    {
        return CarbonImmutable::parse(self::string($row, $column))->utc();
    }

    /** The text form PostgreSQL reads back into timestamptz without losing the zone. */
    public static function timestamp(CarbonImmutable $time): string
    {
        return $time->utc()->format('Y-m-d H:i:s.uP');
    }
}
