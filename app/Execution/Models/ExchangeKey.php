<?php

declare(strict_types=1);

namespace App\Execution\Models;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Model;

/**
 * An account's Binance key, stored with encrypted casts and hidden from every array and JSON form (D-20). Trade-only,
 * no withdrawals, IP-restricted (docs/security.md, section 2).
 *
 * @property int $id
 * @property int $account_id
 * @property string $api_key
 * @property string $api_secret
 * @property CarbonImmutable $created_at
 */
final class ExchangeKey extends Model
{
    public const UPDATED_AT = null;

    protected $fillable = ['account_id', 'api_key', 'api_secret', 'created_at'];

    protected $hidden = ['api_key', 'api_secret'];

    protected function casts(): array
    {
        return ['api_key' => 'encrypted', 'api_secret' => 'encrypted', 'created_at' => 'immutable_datetime'];
    }
}
