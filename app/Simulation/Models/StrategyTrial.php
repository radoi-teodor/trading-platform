<?php

declare(strict_types=1);

namespace App\Simulation\Models;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Model;

/**
 * One backtest of a strategy family (D-58): its parameters, window and daily Sharpe ratio (not annualized).
 *
 * @property int $id
 * @property string $family
 * @property string $strategy
 * @property array<string, string> $parameters
 * @property string $definition_hash
 * @property CarbonImmutable $window_from
 * @property CarbonImmutable $window_to
 * @property string|null $daily_sharpe
 * @property int $observations
 * @property CarbonImmutable $created_at
 */
final class StrategyTrial extends Model
{
    public const UPDATED_AT = null;

    protected $fillable = ['family', 'strategy', 'parameters', 'definition_hash', 'window_from', 'window_to', 'daily_sharpe', 'observations', 'created_at'];

    protected function casts(): array
    {
        return [
            'parameters' => 'array',
            'window_from' => 'immutable_date',
            'window_to' => 'immutable_date',
            'created_at' => 'immutable_datetime',
            'observations' => 'integer',
        ];
    }
}
