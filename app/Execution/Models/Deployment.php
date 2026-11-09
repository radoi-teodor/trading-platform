<?php

declare(strict_types=1);

namespace App\Execution\Models;

use App\Strategy\Versions\StrategyVersion;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * A strategy version running on an account, with the parameters it runs with (D-71).
 *
 * @property int $id
 * @property int $account_id
 * @property int $strategy_version_id
 * @property array<string, string> $parameters
 * @property bool $active
 * @property string $reason
 * @property CarbonImmutable $created_at
 * @property CarbonImmutable|null $ended_at
 * @property string $stage paper, live_micro or live (D-83)
 * @property string $size_multiplier scales the strategy's risk per trade; 1 is full size (D-83)
 */
final class Deployment extends Model
{
    public const UPDATED_AT = null;

    protected $fillable = ['account_id', 'strategy_version_id', 'parameters', 'active', 'reason', 'created_at', 'ended_at', 'stage', 'size_multiplier'];

    /** @return BelongsTo<StrategyVersion, $this> */
    public function version(): BelongsTo
    {
        return $this->belongsTo(StrategyVersion::class, 'strategy_version_id');
    }

    protected function casts(): array
    {
        return ['parameters' => 'array', 'active' => 'boolean', 'created_at' => 'immutable_datetime', 'ended_at' => 'immutable_datetime'];
    }
}
