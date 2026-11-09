<?php

declare(strict_types=1);

namespace App\Market\Binance;

use UnexpectedValueException;

/** Typed access to decoded Binance JSON; a shape the code does not expect fails loudly. */
final class Json
{
    /** @return array<array-key, mixed> */
    public static function array(mixed $value, string $what): array
    {
        if (! is_array($value)) {
            throw new UnexpectedValueException("Binance response: {$what} is not an array.");
        }

        return $value;
    }

    /** @return array<array-key, mixed> */
    public static function arrayAt(mixed $value, string|int $key): array
    {
        return self::array(self::array($value, 'object')[$key] ?? null, (string) $key);
    }

    public static function string(mixed $value, string|int $key): string
    {
        $field = self::array($value, 'object')[$key] ?? null;

        if (! is_string($field)) {
            throw new UnexpectedValueException("Binance response: {$key} is not a string.");
        }

        return $field;
    }

    /** A numeric field as an exact plain decimal string, whatever notation Binance used. */
    public static function number(mixed $value, string|int $key): string
    {
        return ExchangeNumber::plain(self::string($value, $key));
    }

    public static function int(mixed $value, string|int $key): int
    {
        $field = self::array($value, 'object')[$key] ?? null;

        if (! is_int($field)) {
            throw new UnexpectedValueException("Binance response: {$key} is not an integer.");
        }

        return $field;
    }
}
