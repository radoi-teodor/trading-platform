<?php

declare(strict_types=1);

namespace App\Execution\Models;

use App\Execution\Data\CommandKind;
use App\Execution\Data\EngineCommand;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Model;

/**
 * A command for an account's engine, written by the web process or the command line and applied by the engine at
 * the next event (docs/engine.md, section 9).
 *
 * @property int $id
 * @property int $account_id
 * @property CommandKind $kind
 * @property string|null $strategy
 * @property string $issued_by
 * @property CarbonImmutable $created_at
 * @property CarbonImmutable|null $applied_at
 * @property CarbonImmutable|null $applied_event_at
 */
final class EngineCommandRow extends Model
{
    public const UPDATED_AT = null;

    protected $table = 'engine_commands';

    protected $fillable = ['account_id', 'kind', 'strategy', 'issued_by', 'created_at', 'applied_at', 'applied_event_at'];

    public function command(): EngineCommand
    {
        return new EngineCommand($this->kind, $this->strategy, $this->issued_by);
    }

    protected function casts(): array
    {
        return ['kind' => CommandKind::class, 'created_at' => 'immutable_datetime', 'applied_at' => 'immutable_datetime', 'applied_event_at' => 'immutable_datetime'];
    }
}
