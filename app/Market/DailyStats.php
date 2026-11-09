<?php

declare(strict_types=1);

namespace App\Market;

use App\Market\Data\Candle;
use App\Market\Models\Instrument;
use App\Support\Timeframe;
use Illuminate\Database\ConnectionInterface;
use InvalidArgumentException;

/** The daily candle of every instrument (D-90), for rankings and screens that must not read minutes. */
final readonly class DailyStats
{
    private const int BATCH = 500;

    public function __construct(private ConnectionInterface $db) {}

    /** @param iterable<Candle> $days daily candles, any order */
    public function upsert(Instrument $instrument, iterable $days): int
    {
        $rows = [];
        $count = 0;

        foreach ($days as $day) {
            if ($day->timeframe !== Timeframe::OneDay) {
                throw new InvalidArgumentException("Daily stats take 1d candles, got {$day->timeframe->value}.");
            }

            $rows[] = [
                'instrument_id' => $instrument->id, 'day' => $day->openTime->format('Y-m-d'),
                'open' => (string) $day->open, 'high' => (string) $day->high, 'low' => (string) $day->low, 'close' => (string) $day->close,
                'volume' => (string) $day->volume, 'quote_volume' => (string) $day->quoteVolume, 'trades' => $day->tradeCount,
            ];

            if (count($rows) === self::BATCH) {
                $count += $this->write($rows);
                $rows = [];
            }
        }

        return $rows === [] ? $count : $count + $this->write($rows);
    }

    /** Fills each day's average funding rate and last open interest value from the stored series, where they exist. */
    public function fillDerived(): void
    {
        $this->db->statement(<<<'SQL'
            UPDATE daily_stats d SET funding_rate = f.rate
            FROM (
                SELECT instrument_id, (funding_time AT TIME ZONE 'UTC')::date AS day, avg(rate) AS rate
                FROM funding_rates GROUP BY 1, 2
            ) f
            WHERE d.instrument_id = f.instrument_id AND d.day = f.day
            SQL);
        $this->db->statement(<<<'SQL'
            UPDATE daily_stats d SET open_interest_value = o.value
            FROM (
                SELECT DISTINCT ON (instrument_id, (time AT TIME ZONE 'UTC')::date)
                    instrument_id, (time AT TIME ZONE 'UTC')::date AS day, value
                FROM open_interest ORDER BY instrument_id, (time AT TIME ZONE 'UTC')::date, time DESC
            ) o
            WHERE d.instrument_id = o.instrument_id AND d.day = o.day
            SQL);
    }

    /** @param list<array<string, int|string>> $rows */
    private function write(array $rows): int
    {
        $this->db->table('daily_stats')->upsert($rows, ['instrument_id', 'day'], ['open', 'high', 'low', 'close', 'volume', 'quote_volume', 'trades']);

        return count($rows);
    }
}
