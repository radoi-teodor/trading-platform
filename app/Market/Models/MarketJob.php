<?php

declare(strict_types=1);

namespace App\Market\Models;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Model;

/**
 * A history import started from the Settings screen (D-80): what it fills, its state and market:backfill's output.
 *
 * @property int $id
 * @property string $symbol
 * @property CarbonImmutable $from
 * @property CarbonImmutable|null $to the last day imported; null means up to now
 * @property bool $candles
 * @property bool $scalping
 * @property string $status queued, running, done or failed
 * @property string|null $output
 * @property CarbonImmutable $created_at
 * @property CarbonImmutable|null $started_at
 * @property CarbonImmutable|null $finished_at
 */
final class MarketJob extends Model
{
    public const UPDATED_AT = null;

    protected $fillable = ['symbol', 'from', 'to', 'candles', 'scalping', 'status', 'output', 'created_at', 'started_at', 'finished_at'];

    protected function casts(): array
    {
        return ['from' => 'immutable_date', 'to' => 'immutable_date', 'candles' => 'boolean', 'scalping' => 'boolean', 'created_at' => 'immutable_datetime', 'started_at' => 'immutable_datetime', 'finished_at' => 'immutable_datetime'];
    }
}
