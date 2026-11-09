<?php

declare(strict_types=1);

namespace App\Execution\Models;

use App\Execution\Data\EngineMode;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * A paper or testnet account (docs/engine.md, section 9): its mode, starting balances, the instruments it follows
 * besides its strategies', and the moment its engine starts trading.
 *
 * @property int $id
 * @property string $name
 * @property EngineMode $mode
 * @property string $quote
 * @property string $spot_balance
 * @property string $futures_balance
 * @property list<string> $symbols
 * @property bool $monitored whether the health check watches its engine (D-78)
 * @property CarbonImmutable $started_at
 * @property CarbonImmutable $created_at
 */
final class Account extends Model
{
    public const UPDATED_AT = null;

    protected $fillable = ['name', 'mode', 'quote', 'spot_balance', 'futures_balance', 'symbols', 'started_at', 'created_at', 'monitored'];

    /** @return HasMany<Deployment, $this> */
    public function deployments(): HasMany
    {
        return $this->hasMany(Deployment::class);
    }

    protected function casts(): array
    {
        return ['mode' => EngineMode::class, 'symbols' => 'array', 'monitored' => 'boolean', 'started_at' => 'immutable_datetime', 'created_at' => 'immutable_datetime'];
    }
}
