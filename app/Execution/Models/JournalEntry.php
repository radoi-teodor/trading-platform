<?php

declare(strict_types=1);

namespace App\Execution\Models;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Model;

/**
 * One record of an account's journal: a decision with intents, or an engine event (docs/engine.md, section 7).
 *
 * @property int $id
 * @property int $account_id
 * @property CarbonImmutable $event_at
 * @property string $kind
 * @property int $format
 * @property array<string, mixed> $payload
 * @property CarbonImmutable $created_at
 */
final class JournalEntry extends Model
{
    public const UPDATED_AT = null;

    protected $table = 'engine_journal';

    protected $fillable = ['account_id', 'event_at', 'kind', 'format', 'payload', 'created_at'];

    protected function casts(): array
    {
        return ['event_at' => 'immutable_datetime', 'payload' => 'array', 'format' => 'integer', 'created_at' => 'immutable_datetime'];
    }
}
