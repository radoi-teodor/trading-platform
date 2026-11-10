<?php

declare(strict_types=1);

namespace App\Market\Instruments;

use App\Market\Models\Instrument;
use App\Market\Persistence\Rows;
use App\Support\Timeframe;
use Carbon\CarbonImmutable;
use Illuminate\Database\ConnectionInterface;

/**
 * The times an instrument did not trade inside its life (D-89). Binance has delisted a perpetual and listed it again
 * under the same symbol; between the two its archives hold flat candles without volume for some days and nothing
 * for others. A break runs from the minute after the last trade to the minute of the first one after it. Its
 * minutes are not gaps, the candles stored in it are not prices, and no run holds the instrument across it.
 *
 * A break is read from the stored days: two days with trades that do not follow each other. The stored 1-minute
 * candles of those two days, where there are any, set its ends to the minute.
 */
final readonly class TradingBreaks
{
    public function __construct(private ConnectionInterface $db) {}

    /** Works out the breaks of $instrument again from its stored days and candles; returns how many it has. */
    public function find(Instrument $instrument): int
    {
        $days = $this->db->table('daily_stats')
            ->where('instrument_id', $instrument->id)
            ->where('trades', '>', 0)
            ->orderBy('day')
            ->get(['day']);
        $rows = [];
        $previous = null;

        foreach ($days as $row) {
            $day = CarbonImmutable::parse(Rows::string($row, 'day'), 'UTC')->startOfDay();

            if ($previous !== null && $previous->addDay()->lessThan($day)) {
                $rows[] = [
                    'instrument_id' => $instrument->id,
                    'stopped_at' => Rows::timestamp($this->traded($instrument, $previous, 'max')?->addMinute() ?? $previous->addDay()),
                    'resumed_at' => Rows::timestamp($this->traded($instrument, $day, 'min') ?? $day),
                ];
            }

            $previous = $day;
        }

        $this->db->transaction(function () use ($instrument, $rows): void {
            $this->db->table('instrument_breaks')->where('instrument_id', $instrument->id)->delete();
            $this->db->table('instrument_breaks')->insert($rows);
        });

        return count($rows);
    }

    /** @return list<array{from: CarbonImmutable, to: CarbonImmutable}> $instrument did not trade in [from, to); oldest first */
    public function of(Instrument $instrument): array
    {
        $breaks = [];

        foreach ($this->among([$instrument->id])[$instrument->id] ?? [] as [$from, $to]) {
            $breaks[] = ['from' => CarbonImmutable::createFromTimestampUTC($from), 'to' => CarbonImmutable::createFromTimestampUTC($to)];
        }

        return $breaks;
    }

    /**
     * @param  list<int>  $instrumentIds
     * @return array<int, list<array{int, int}>> by instrument id, for those that have any: the timestamps a break
     *                                           starts at and ends before, oldest first
     */
    public function among(array $instrumentIds): array
    {
        $breaks = [];
        $rows = $this->db->table('instrument_breaks')
            ->whereIn('instrument_id', $instrumentIds)
            ->orderBy('instrument_id')
            ->orderBy('stopped_at')
            ->get(['instrument_id', 'stopped_at', 'resumed_at']);

        foreach ($rows as $row) {
            $breaks[Rows::int($row, 'instrument_id')][] = [Rows::time($row, 'stopped_at')->getTimestamp(), Rows::time($row, 'resumed_at')->getTimestamp()];
        }

        return $breaks;
    }

    /**
     * Whether $time falls in one of $breaks, as among() gives them for one instrument.
     *
     * @param  list<array{int, int}>  $breaks
     */
    public static function covers(array $breaks, int $time): bool
    {
        foreach ($breaks as [$stopped, $resumed]) {
            if ($time >= $stopped && $time < $resumed) {
                return true;
            }
        }

        return false;
    }

    /**
     * The first ('min') or the last ('max') stored minute of $day in which $instrument traded.
     *
     * @param  'min'|'max'  $which
     */
    private function traded(Instrument $instrument, CarbonImmutable $day, string $which): ?CarbonImmutable
    {
        $minutes = $this->db->table('candles')
            ->where('instrument_id', $instrument->id)
            ->where('timeframe', Timeframe::OneMinute->value)
            ->where('open_time', '>=', Rows::timestamp($day))
            ->where('open_time', '<', Rows::timestamp($day->addDay()))
            ->where('volume', '>', 0);
        $minute = $which === 'min' ? $minutes->min('open_time') : $minutes->max('open_time');

        return is_string($minute) ? CarbonImmutable::parse($minute)->utc() : null;
    }
}
