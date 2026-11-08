<?php

declare(strict_types=1);

namespace App\Strategy\Baselines;

use App\Support\Number\Decimal;
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

    /** @param array<string, string> $parameters */
    public static function timeframe(array $parameters, string $key): Timeframe
    {
        return Timeframe::tryFrom($parameters[$key] ?? '')
            ?? throw new InvalidArgumentException("Parameter '{$key}' must be a timeframe such as 1h or 1d, got '".($parameters[$key] ?? '')."'.");
    }
}
