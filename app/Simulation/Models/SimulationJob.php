<?php

declare(strict_types=1);

namespace App\Simulation\Models;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Model;

/**
 * A backtest or grid run in the background (D-62): queued, running, done or failed.
 *
 * @property int $id
 * @property string $kind
 * @property array<string, mixed> $arguments
 * @property string $status
 * @property array<string, mixed>|null $summary
 * @property string|null $error
 * @property string $author
 * @property CarbonImmutable $created_at
 * @property CarbonImmutable|null $started_at
 * @property CarbonImmutable|null $finished_at
 */
final class SimulationJob extends Model
{
    public const UPDATED_AT = null;

    protected $fillable = ['kind', 'arguments', 'status', 'summary', 'error', 'author', 'created_at', 'started_at', 'finished_at'];

    protected function casts(): array
    {
        return [
            'arguments' => 'array',
            'summary' => 'array',
            'created_at' => 'immutable_datetime',
            'started_at' => 'immutable_datetime',
            'finished_at' => 'immutable_datetime',
        ];
    }
}
