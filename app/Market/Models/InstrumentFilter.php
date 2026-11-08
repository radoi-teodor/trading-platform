<?php

declare(strict_types=1);

namespace App\Market\Models;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * The exchange filters of an instrument from valid_from on. A new row is written only when they change, so the
 * filters in force at any past moment can be found (docs/simulation.md, section 2).
 *
 * @property int $id
 * @property int $instrument_id
 * @property CarbonImmutable $valid_from
 * @property string $tick_size
 * @property string $step_size
 * @property string $min_qty
 * @property string|null $max_qty
 * @property string|null $min_notional
 * @property array<string, mixed> $raw
 */
final class InstrumentFilter extends Model
{
    public const null UPDATED_AT = null;

    protected $fillable = [
        'instrument_id', 'valid_from', 'tick_size', 'step_size', 'min_qty', 'max_qty', 'min_notional', 'raw',
    ];

    /** @return BelongsTo<Instrument, $this> */
    public function instrument(): BelongsTo
    {
        return $this->belongsTo(Instrument::class);
    }

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'valid_from' => 'immutable_datetime',
            'raw' => 'array',
        ];
    }
}
