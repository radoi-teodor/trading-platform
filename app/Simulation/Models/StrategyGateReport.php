<?php

declare(strict_types=1);

namespace App\Simulation\Models;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Model;

/**
 * A gate report of a strategy version (docs/simulation.md, section 7): every gate with its numbers. Promotion to
 * Backtested reads the latest one.
 *
 * @property int $id
 * @property int $strategy_version_id
 * @property bool $passed
 * @property array<string, mixed> $report
 * @property CarbonImmutable $created_at
 */
final class StrategyGateReport extends Model
{
    public const UPDATED_AT = null;

    protected $fillable = ['strategy_version_id', 'passed', 'report', 'created_at'];

    protected function casts(): array
    {
        return ['passed' => 'boolean', 'report' => 'array', 'created_at' => 'immutable_datetime'];
    }
}
