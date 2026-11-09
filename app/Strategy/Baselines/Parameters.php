<?php

declare(strict_types=1);

namespace App\Strategy\Baselines;

use App\Support\Number\Decimal;
use App\Support\Side;
use App\Support\Symbol;
use App\Support\Timeframe;
use InvalidArgumentException;

/** Reads the text parameters of a hand-coded strategy, refusing what it cannot use. */
final class Parameters
{
    /** @param array<string, string> $parameters */
    public static function count(array $parameters, string $key): int
    {
        $value = $parameters[$key] ?? '';

        if (preg_match('/^[1-9]\d{0,5}$/', $value) !== 1) {
            throw new InvalidArgumentException("Parameter '{$key}' must be a whole number from 1, got '{$value}'.");
        }

        return (int) $value;
    }

    /** @param array<string, string> $parameters */
    public static function decimal(array $parameters, string $key): Decimal
    {
        $value = Decimal::of($parameters[$key] ?? '');

        if (! $value->isPositive()) {
            throw new InvalidArgumentException("Parameter '{$key}' must be positive, got '{$value}'.");
        }

        return $value;
    }

    /**
     * A number of any sign, such as a funding rate threshold.
     *
     * @param  array<string, string>  $parameters
     */
    public static function rate(array $parameters, string $key): Decimal
    {
        $value = $parameters[$key] ?? '';

        if (preg_match('/^-?\d+(\.\d+)?$/', $value) !== 1) {
            throw new InvalidArgumentException("Parameter '{$key}' must be a number, got '{$value}'.");
        }

        return Decimal::of($value);
    }

    /**
     * Long buys, short sells; a short needs a perpetual (D-50).
     *
     * @param  array<string, string>  $parameters
     */
    public static function side(array $parameters, string $key, Symbol $symbol): Side
    {
        $side = match ($parameters[$key] ?? '') {
            'long' => Side::Buy,
            'short' => Side::Sell,
            default => throw new InvalidArgumentException("Parameter '{$key}' must be long or short, got '".($parameters[$key] ?? '')."'."),
        };

        if ($side === Side::Sell && ! $symbol->isPerpetual()) {
            throw new InvalidArgumentException("A short needs a perpetual, not {$symbol}.");
        }

        return $side;
    }

    /** @param array<string, string> $parameters */
    public static function timeframe(array $parameters, string $key): Timeframe
    {
        return Timeframe::tryFrom($parameters[$key] ?? '')
            ?? throw new InvalidArgumentException("Parameter '{$key}' must be a timeframe such as 1h or 1d, got '".($parameters[$key] ?? '')."'.");
    }
}
