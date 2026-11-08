<?php

declare(strict_types=1);

namespace App\Market\Models;

use App\Market\Data\InstrumentStatus;
use App\Market\Data\MarketType;
use App\Support\Symbol;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $exchange
 * @property MarketType $market
 * @property string $symbol
 * @property string $venue_symbol
 * @property string $base
 * @property string $quote
 * @property string|null $settle
 * @property InstrumentStatus $status
 * @property CarbonImmutable|null $first_seen_at
 * @property CarbonImmutable|null $last_seen_at
 */
final class Instrument extends Model
{
    protected $fillable = [
        'exchange', 'market', 'symbol', 'venue_symbol', 'base', 'quote', 'settle', 'status',
        'first_seen_at', 'last_seen_at',
    ];

    /** Creates the instrument the first time it is seen, and records when it was last seen. */
    public static function register(string $exchange, MarketType $market, Symbol $symbol, CarbonImmutable $seenAt, InstrumentStatus $status = InstrumentStatus::Trading): self
    {
        $instrument = self::query()->firstOrNew([
            'exchange' => $exchange,
            'market' => $market,
            'venue_symbol' => $symbol->venueSymbol(),
        ]);

        $instrument->fill([
            'symbol' => (string) $symbol,
            'base' => $symbol->base->code,
            'quote' => $symbol->quote->code,
            'settle' => $symbol->settle?->code,
            'status' => $status,
            'last_seen_at' => $seenAt,
        ]);
        $instrument->first_seen_at ??= $seenAt;
        $instrument->save();

        return $instrument;
    }

    public function unifiedSymbol(): Symbol
    {
        return Symbol::parse($this->symbol);
    }

    /** @return HasMany<InstrumentFilter, $this> */
    public function filters(): HasMany
    {
        return $this->hasMany(InstrumentFilter::class);
    }

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'market' => MarketType::class,
            'status' => InstrumentStatus::class,
            'first_seen_at' => 'immutable_datetime',
            'last_seen_at' => 'immutable_datetime',
        ];
    }
}
