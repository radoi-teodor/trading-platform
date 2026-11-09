<?php

declare(strict_types=1);

namespace App\Execution\Models;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Model;

/**
 * The account as its engine last published it (D-70): the UI reads this, never the engine.
 *
 * @property int $account_id
 * @property CarbonImmutable $minute
 * @property int $quiet_decisions
 * @property array<string, mixed> $payload
 * @property CarbonImmutable $updated_at
 */
final class AccountSnapshot extends Model
{
    public const CREATED_AT = null;

    protected $primaryKey = 'account_id';

    public $incrementing = false;

    protected $fillable = ['account_id', 'minute', 'quiet_decisions', 'payload', 'updated_at'];

    protected function casts(): array
    {
        return ['minute' => 'immutable_datetime', 'payload' => 'array', 'quiet_decisions' => 'integer', 'updated_at' => 'immutable_datetime'];
    }
}
