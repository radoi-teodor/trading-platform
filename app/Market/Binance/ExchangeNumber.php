<?php

declare(strict_types=1);

namespace App\Market\Binance;

use UnexpectedValueException;

/**
 * Numbers as Binance writes them. Its archives give small funding rates in scientific notation (9.2E-7, 0E-8);
 * this rewrites them as exact plain decimals by moving the decimal point in the text, never through float
 * (Known traps: scientific notation).
 */
final class ExchangeNumber
{
    public static function plain(string $value): string
    {
        if (preg_match('/^(-?)(\d+)(?:\.(\d+))?(?:[eE]([+-]?\d+))?$/', $value, $parts) !== 1) {
            throw new UnexpectedValueException("Binance sent '{$value}' where a number was expected.");
        }

        if (! isset($parts[4])) {
            return $value;
        }

        // With an exponent, PCRE sets the skipped fraction group to ''.
        $digits = $parts[2].$parts[3];
        $point = strlen($parts[2]) + (int) $parts[4];
        $padding = max(0, 1 - $point);
        $digits = str_repeat('0', $padding).$digits;
        $point += $padding;

        $plain = $point >= strlen($digits)
            ? $digits.str_repeat('0', $point - strlen($digits))
            : substr($digits, 0, $point).'.'.substr($digits, $point);
        $plain = ltrim($plain, '0');

        if ($plain === '' || str_starts_with($plain, '.')) {
            $plain = '0'.$plain;
        }

        return $parts[1].$plain;
    }
}
