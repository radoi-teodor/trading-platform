<?php

declare(strict_types=1);

namespace App\Simulation\Models;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Model;

/**
 * The hold-out run of a strategy version: the last 20 % of its history, used once (D-58). A second gate report reads
 * this outcome instead of running the hold-out again.
 *
 * @property int $id
 * @property int $strategy_version_id
 * @property array<string, string> $parameters
 * @property CarbonImmutable $window_from
 * @property CarbonImmutable $window_to
 * @property array<string, mixed> $outcome
 * @property CarbonImmutable $created_at
 */
final class StrategyHoldout extends Model
{
    public const UPDATED_AT = null;

    protected $fillable = ['strategy_version_id', 'parameters', 'window_from', 'window_to', 'outcome', 'created_at'];

    protected function casts(): array
    {
        return [
            'parameters' => 'array', 'outcome' => 'array', 'window_from' => 'immutable_date', 'window_to' => 'immutable_date',
            'created_at' => 'immutable_datetime',
        ];
    }
}
