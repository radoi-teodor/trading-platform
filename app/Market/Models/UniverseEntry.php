<?php

declare(strict_types=1);

namespace App\Market\Models;

use App\Market\Data\MarketType;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Model;

/**
 * An instrument the platform follows (D-80): streamed or history only, with or without the data for scalping, and
 * the notional from which its trades count as large (null: the default of config/market.php).
 *
 * @property int $id
 * @property MarketType $market
 * @property string $symbol
 * @property bool $streamed
 * @property bool $scalping
 * @property string|null $large_notional
 * @property CarbonImmutable $created_at
 */
final class UniverseEntry extends Model
{
    public const UPDATED_AT = null;

    protected $table = 'market_universe';

    protected $fillable = ['market', 'symbol', 'streamed', 'scalping', 'large_notional', 'created_at'];

    protected function casts(): array
    {
        return ['market' => MarketType::class, 'streamed' => 'boolean', 'scalping' => 'boolean', 'created_at' => 'immutable_datetime'];
    }
}
