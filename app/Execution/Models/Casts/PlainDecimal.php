<?php

declare(strict_types=1);

namespace App\Execution\Models\Casts;

use App\Support\Number\Decimal;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use Stringable;
use UnexpectedValueException;

/**
 * A numeric(36,18) column read as the plain decimal text the engine wrote ("12.5", not "12.500000000000000000").
 *
 * @implements CastsAttributes<string|null, string|null>
 */
final class PlainDecimal implements CastsAttributes
{
    /** @param array<string, mixed> $attributes */
    public function get(Model $model, string $key, mixed $value, array $attributes): ?string
    {
        return match (true) {
            $value === null => null,
            is_string($value) || is_int($value) => (string) Decimal::of((string) $value),
            default => throw new UnexpectedValueException("{$key} is not a decimal column."),
        };
    }

    /** @param array<string, mixed> $attributes */
    public function set(Model $model, string $key, mixed $value, array $attributes): ?string
    {
        return match (true) {
            $value === null => null,
            is_string($value) || $value instanceof Stringable => (string) $value,
            default => throw new UnexpectedValueException("{$key} takes decimal text."),
        };
    }
}
