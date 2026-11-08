<?php

declare(strict_types=1);

namespace App\Strategy\Versions;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Model;

/**
 * A stage a strategy version entered (D-16): draft, backtested, paper, live_micro, live; why, and when.
 *
 * @property int $id
 * @property int $strategy_version_id
 * @property string $stage
 * @property string $reason
 * @property CarbonImmutable $created_at
 */
final class StrategyStage extends Model
{
    public const UPDATED_AT = null;

    protected $fillable = ['strategy_version_id', 'stage', 'reason', 'created_at'];

    protected function casts(): array
    {
        return ['created_at' => 'immutable_datetime'];
    }
}
