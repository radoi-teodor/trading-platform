<?php

declare(strict_types=1);

namespace App\Execution\Models;

use App\Strategy\Versions\StrategyVersion;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Builder;
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
 * @property CarbonImmutable|null $effective_at when a step up takes effect, 24 hours after its approval; null: at creation
 */
final class Deployment extends Model
{
    public const UPDATED_AT = null;

    /** The defaults of the columns, so a new deployment reads them before it is reloaded: paper, at full size. */
    protected $attributes = ['stage' => 'paper', 'size_multiplier' => '1'];

    protected $fillable = ['account_id', 'strategy_version_id', 'parameters', 'active', 'reason', 'created_at', 'ended_at', 'stage', 'size_multiplier', 'effective_at'];

    /** @return BelongsTo<StrategyVersion, $this> */
    public function version(): BelongsTo
    {
        return $this->belongsTo(StrategyVersion::class, 'strategy_version_id');
    }

    protected function casts(): array
    {
        return ['parameters' => 'array', 'active' => 'boolean', 'created_at' => 'immutable_datetime', 'ended_at' => 'immutable_datetime', 'effective_at' => 'immutable_datetime'];
    }

    /**
     * The deployments an engine runs at $now: active, in effect, not ended.
     *
     * @return Builder<self>
     */
    public static function inForce(int $account, CarbonImmutable $now): Builder
    {
        return self::query()->where('account_id', $account)->where('active', true)
            ->where(fn (Builder $q) => $q->whereNull('effective_at')->orWhere('effective_at', '<=', $now))
            ->where(fn (Builder $q) => $q->whereNull('ended_at')->orWhere('ended_at', '>', $now));
    }

    /** When the deployment's results start counting: when it took effect. */
    public function start(): CarbonImmutable
    {
        return $this->effective_at ?? $this->created_at;
    }
}
