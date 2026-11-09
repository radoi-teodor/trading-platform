<?php

declare(strict_types=1);

namespace App\Simulation\Models;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Model;

/**
 * The summary of one backtest; the full report is the artifact, a gzip of its canonical JSON.
 *
 * @property int $id
 * @property string $strategy
 * @property string $strategy_key
 * @property array<string, mixed> $definition
 * @property string $definition_hash
 * @property string $report_hash
 * @property string $data_fingerprint
 * @property CarbonImmutable $window_from
 * @property CarbonImmutable $window_to
 * @property string $net_return
 * @property string $max_drawdown
 * @property string|null $sharpe
 * @property int $trades
 * @property string $artifact
 * @property CarbonImmutable $started_at
 * @property CarbonImmutable $finished_at
 * @property int $duration_ms
 */
final class BacktestRun extends Model
{
    public $timestamps = false;

    protected $guarded = ['id'];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'definition' => 'array',
            'window_from' => 'immutable_datetime',
            'window_to' => 'immutable_datetime',
            'started_at' => 'immutable_datetime',
            'finished_at' => 'immutable_datetime',
        ];
    }
}
