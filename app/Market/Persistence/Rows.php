<?php

declare(strict_types=1);

namespace App\Market\Persistence;

use App\Support\Timeframe;
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

    /**
     * The SQL for the start of the step a time column falls in, counted from the UTC epoch as candles are. It takes
     * the step's seconds twice, as bindings (stepBindings).
     *
     * @param  literal-string  $column
     * @return literal-string
     */
    public static function step(string $column): string
    {
        return "to_timestamp(floor(extract(epoch from {$column}) / ?) * ?)";
    }

    /** @return array{int, int} what step() binds for a step of $every */
    public static function stepBindings(Timeframe $every): array
    {
        return [$every->seconds(), $every->seconds()];
    }

    /** The text form PostgreSQL reads back into timestamptz without losing the zone. */
    public static function timestamp(CarbonImmutable $time): string
    {
        return $time->utc()->format('Y-m-d H:i:s.uP');
    }
}
