<?php

declare(strict_types=1);

namespace App\Market\Models;

use App\Market\Data\InstrumentRules;
use App\Market\Data\InstrumentStatus;
use App\Market\Data\MarketType;
use App\Market\Data\RulesHistory;
use App\Support\Number\Decimal;
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
 * @property CarbonImmutable|null $listed_at when the instrument was listed (D-89)
 * @property CarbonImmutable|null $delisted_at when it stopped trading; null while it trades or when not known
 * @property string|null $underlying_type COIN, INDEX, ... as the exchange names it
 */
final class Instrument extends Model
{
    protected $fillable = [
        'exchange', 'market', 'symbol', 'venue_symbol', 'base', 'quote', 'settle', 'status',
        'first_seen_at', 'last_seen_at', 'listed_at', 'delisted_at', 'underlying_type',
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

    /** The filters this instrument had over time, oldest first (docs/engine.md, section 4). */
    public function rulesHistory(): RulesHistory
    {
        $rules = $this->filters()->orderBy('valid_from')->get()->map(fn (InstrumentFilter $filter): InstrumentRules => new InstrumentRules(
            $filter->valid_from,
            Decimal::of($filter->tick_size),
            Decimal::of($filter->step_size),
            Decimal::of($filter->min_qty),
            $filter->max_qty === null ? null : Decimal::of($filter->max_qty),
            $filter->min_notional === null ? null : Decimal::of($filter->min_notional),
        ));

        return new RulesHistory($this->unifiedSymbol(), array_values($rules->all()));
    }

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'market' => MarketType::class,
            'status' => InstrumentStatus::class,
            'first_seen_at' => 'immutable_datetime',
            'last_seen_at' => 'immutable_datetime',
            'listed_at' => 'immutable_datetime',
            'delisted_at' => 'immutable_datetime',
        ];
    }
}
