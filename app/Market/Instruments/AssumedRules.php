<?php

declare(strict_types=1);

namespace App\Market\Instruments;

use App\Market\Data\InstrumentRules;
use App\Market\Data\InstrumentStatus;
use App\Market\Data\RulesHistory;
use App\Market\Models\Instrument;
use App\Support\Number\Decimal;
use App\Support\Timeframe;
use Carbon\CarbonImmutable;
use Illuminate\Database\ConnectionInterface;
use InvalidArgumentException;

/**
 * The exchange filters of a perpetual known only from the archives (D-89): it was delisted before its filters were
 * ever read, so a backtest works them out from its stored candles. The tick size is the finest decimal its prices
 * use in its last stored month, the lot step the finest its volumes use; the least quantity is one step and the
 * least notional the one USD-M contracts commonly have. They are valid from the minute after its last candle, so
 * every moment it traded is before them and a report states they are assumed (RulesHistory::isAssumedAt).
 */
final readonly class AssumedRules
{
    /** The least notional of an order, in the quote asset. */
    public const string MIN_NOTIONAL = '5';

    private const int SAMPLE_DAYS = 30;

    public function __construct(private ConnectionInterface $db) {}

    public function of(Instrument $instrument): RulesHistory
    {
        $symbol = $instrument->unifiedSymbol();

        if ($instrument->status !== InstrumentStatus::Delisted) {
            throw new InvalidArgumentException("No exchange filters are known for {$symbol}; run market:instruments.");
        }

        $last = $this->db->table('candles')->where('instrument_id', $instrument->id)->where('timeframe', Timeframe::OneMinute->value)->max('open_time');

        if (! is_string($last)) {
            throw new InvalidArgumentException("{$symbol} has neither exchange filters nor candles to work them out from; import its history first.");
        }

        $last = CarbonImmutable::parse($last)->utc();
        $row = $this->db->table('candles')
            ->where('instrument_id', $instrument->id)
            ->where('timeframe', Timeframe::OneMinute->value)
            ->where('open_time', '>', $last->subDays(self::SAMPLE_DAYS)->toIso8601String())
            ->selectRaw('max(greatest(scale(trim_scale(open)), scale(trim_scale(high)), scale(trim_scale(low)), scale(trim_scale(close)))) as price, max(scale(trim_scale(volume))) as quantity')
            ->first();
        $tick = self::unit($row->price ?? null);
        $step = self::unit($row->quantity ?? null);

        return new RulesHistory($symbol, [new InstrumentRules($last->addMinute(), $tick, $step, $step, null, Decimal::of(self::MIN_NOTIONAL))]);
    }

    /** One unit of the last of $decimals decimals: 0.001 for 3, 1 for none. */
    private static function unit(mixed $decimals): Decimal
    {
        $decimals = is_numeric($decimals) ? (int) $decimals : 0;

        return Decimal::of($decimals === 0 ? '1' : '0.'.str_repeat('0', $decimals - 1).'1');
    }
}
