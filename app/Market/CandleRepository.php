<?php

declare(strict_types=1);

namespace App\Market;

use App\Market\Data\Candle;
use App\Market\Data\CandleSource;
use App\Market\Data\UpsertResult;
use App\Market\Models\Instrument;
use App\Market\Persistence\Rows;
use App\Support\Clock\Clock;
use App\Support\Timeframe;
use Carbon\CarbonImmutable;
use Illuminate\Database\ConnectionInterface;
use Psr\Log\LoggerInterface;
use UnexpectedValueException;

/**
 * Stores and reads candles. Writes are idempotent; a higher-ranked source overwrites a lower one and any
 * disagreement between sources is logged (docs/market-data.md, section 2).
 */
final readonly class CandleRepository
{
    private const int BATCH_SIZE = 1000;

    private const string COLUMNS = 'instrument_id, timeframe, open_time, open, high, low, close, volume, quote_volume, trade_count, taker_buy_volume, source, received_at';

    public function __construct(
        private ConnectionInterface $db,
        private Clock $clock,
        private LoggerInterface $logger,
    ) {}

    /** @param iterable<Candle> $candles */
    public function upsert(Instrument $instrument, CandleSource $source, iterable $candles): UpsertResult
    {
        $result = new UpsertResult;
        $batch = [];

        foreach ($candles as $candle) {
            $batch[self::key($candle->timeframe, $candle->openTime)] = $candle;

            if (count($batch) === self::BATCH_SIZE) {
                $result = $result->plus($this->upsertBatch($instrument, $source, $batch));
                $batch = [];
            }
        }

        return $batch === [] ? $result : $result->plus($this->upsertBatch($instrument, $source, $batch));
    }

    public function latestOpenTime(Instrument $instrument, Timeframe $timeframe): ?CarbonImmutable
    {
        $latest = $this->db->table('candles')
            ->where('instrument_id', $instrument->id)
            ->where('timeframe', $timeframe->value)
            ->max('open_time');

        return is_string($latest) ? CarbonImmutable::parse($latest)->utc() : null;
    }

    /**
     * The first and last stored 1-minute candle of every instrument that has one, or of those of $ids. Read from the
     * index, two rows an instrument, never by grouping the candles: that takes minutes over the whole history.
     *
     * @param  list<int>|null  $ids
     * @return array<int, array{CarbonImmutable, CarbonImmutable}> by instrument id
     */
    public function coverage(?array $ids = null): array
    {
        if ($ids === []) {
            return [];
        }

        $only = $ids === null ? '' : 'where i.id in ('.implode(', ', array_fill(0, count($ids), '?')).')';
        $edge = fn (string $order): string => "cross join lateral (select open_time from candles where instrument_id = i.id and timeframe = '1m' order by open_time {$order} limit 1)";
        $coverage = [];

        foreach ($this->db->select("select i.id, f.open_time as first, l.open_time as last from instruments i {$edge('asc')} f {$edge('desc')} l {$only}", $ids ?? []) as $row) {
            if (is_object($row)) {
                $coverage[Rows::int($row, 'id')] = [Rows::time($row, 'first'), Rows::time($row, 'last')];
            }
        }

        return $coverage;
    }

    /** @return list<Candle> candles with $from <= open time <= $to, oldest first */
    public function range(Instrument $instrument, Timeframe $timeframe, CarbonImmutable $from, CarbonImmutable $to): array
    {
        $rows = $this->db->table('candles')
            ->where('instrument_id', $instrument->id)
            ->where('timeframe', $timeframe->value)
            ->whereBetween('open_time', [Rows::timestamp($from), Rows::timestamp($to)])
            ->orderBy('open_time')
            ->get();

        return array_values($rows->map(fn (object $row): Candle => self::candleFromRow($row))->all());
    }

    /** @return list<CarbonImmutable> stored open times with $from <= open time <= $to, oldest first */
    /**
     * Open times in [$from, $to] as Unix timestamps, oldest first: cheap enough for windows of years.
     *
     * @return list<int>
     */
    public function openTimestamps(Instrument $instrument, Timeframe $timeframe, CarbonImmutable $from, CarbonImmutable $to): array
    {
        $timestamps = [];
        $last = $to->getTimestamp();

        // A day at a time: one query over years would hold every row in the driver's buffer at once.
        for ($day = $from->getTimestamp(); $day <= $last; $day += 86_400) {
            $epochs = $this->db->table('candles')
                ->where('instrument_id', $instrument->id)
                ->where('timeframe', $timeframe->value)
                ->where('open_time', '>=', Rows::timestamp(CarbonImmutable::createFromTimestampUTC($day)))
                ->where('open_time', '<=', Rows::timestamp(CarbonImmutable::createFromTimestampUTC(min($day + 86_399, $last))))
                ->orderBy('open_time')
                ->selectRaw('extract(epoch from open_time)::bigint as epoch')
                ->pluck('epoch');

            foreach ($epochs as $epoch) {
                $timestamps[] = is_numeric($epoch) ? (int) $epoch : throw new UnexpectedValueException('Not an epoch.');
            }
        }

        return $timestamps;
    }

    /**
     * The candles opening at the given Unix timestamps, by timestamp; missing ones are absent.
     *
     * @param  list<int>  $timestamps
     * @return array<int, Candle>
     */
    public function at(Instrument $instrument, Timeframe $timeframe, array $timestamps): array
    {
        $candles = [];

        foreach (array_chunk($timestamps, 1000) as $chunk) {
            $rows = $this->db->table('candles')
                ->where('instrument_id', $instrument->id)
                ->where('timeframe', $timeframe->value)
                ->whereIn('open_time', array_map(fn (int $t): string => Rows::timestamp(CarbonImmutable::createFromTimestampUTC($t)), $chunk))
                ->get();

            foreach ($rows as $row) {
                $candle = self::candleFromRow($row);
                $candles[$candle->openTime->getTimestamp()] = $candle;
            }
        }

        return $candles;
    }

    /** @param array<string, Candle> $batch */
    private function upsertBatch(Instrument $instrument, CandleSource $source, array $batch): UpsertResult
    {
        $existing = $this->existing($instrument, $batch);
        [$inserted, $overwritten, $kept, $discrepancies] = [0, 0, 0, 0];

        foreach ($batch as $key => $candle) {
            if (! isset($existing[$key])) {
                $inserted++;

                continue;
            }

            [$stored, $storedSource] = $existing[$key];

            if (! $stored->sameValuesAs($candle)) {
                $discrepancies++;
                $this->logger->warning('Candle discrepancy between sources', [
                    'instrument' => $instrument->symbol,
                    'market' => $instrument->market->value,
                    'open_time' => $candle->openTime->toIso8601String(),
                    'stored_source' => $storedSource->name,
                    'incoming_source' => $source->name,
                    'stored_close' => (string) $stored->close,
                    'incoming_close' => (string) $candle->close,
                ]);
            }

            $source->outranks($storedSource) ? $overwritten++ : $kept++;
        }

        $this->write($instrument, $source, $batch);

        return new UpsertResult($inserted, $overwritten, $kept, $discrepancies);
    }

    /** @param array<string, Candle> $batch */
    private function write(Instrument $instrument, CandleSource $source, array $batch): void
    {
        $receivedAt = Rows::timestamp($this->clock->now());
        $placeholders = [];
        $bindings = [];

        foreach ($batch as $candle) {
            $placeholders[] = '(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
            array_push(
                $bindings,
                $instrument->id,
                $candle->timeframe->value,
                Rows::timestamp($candle->openTime),
                (string) $candle->open,
                (string) $candle->high,
                (string) $candle->low,
                (string) $candle->close,
                (string) $candle->volume,
                (string) $candle->quoteVolume,
                $candle->tradeCount,
                (string) $candle->takerBuyVolume,
                $source->value,
                $receivedAt,
            );
        }

        $this->db->statement(
            'INSERT INTO candles ('.self::COLUMNS.') VALUES '.implode(', ', $placeholders).'
            ON CONFLICT (instrument_id, timeframe, open_time) DO UPDATE SET
                open = EXCLUDED.open, high = EXCLUDED.high, low = EXCLUDED.low, close = EXCLUDED.close,
                volume = EXCLUDED.volume, quote_volume = EXCLUDED.quote_volume, trade_count = EXCLUDED.trade_count,
                taker_buy_volume = EXCLUDED.taker_buy_volume, source = EXCLUDED.source, received_at = EXCLUDED.received_at
            WHERE candles.source < EXCLUDED.source',
            $bindings,
        );
    }

    /**
     * @param  array<string, Candle>  $batch
     * @return array<string, array{0: Candle, 1: CandleSource}>
     */
    private function existing(Instrument $instrument, array $batch): array
    {
        $timeframes = array_unique(array_map(fn (Candle $c): string => $c->timeframe->value, $batch));
        $openTimes = array_map(fn (Candle $c): string => Rows::timestamp($c->openTime), $batch);

        $rows = $this->db->table('candles')
            ->where('instrument_id', $instrument->id)
            ->whereIn('timeframe', array_values($timeframes))
            ->whereIn('open_time', array_values($openTimes))
            ->get();

        $existing = [];

        foreach ($rows as $row) {
            $candle = self::candleFromRow($row);
            $existing[self::key($candle->timeframe, $candle->openTime)] = [$candle, CandleSource::from(Rows::int($row, 'source'))];
        }

        return $existing;
    }

    public static function candleFromRow(object $row): Candle
    {
        return Candle::fromStrings(
            Rows::time($row, 'open_time'),
            Timeframe::from(Rows::string($row, 'timeframe')),
            Rows::string($row, 'open'),
            Rows::string($row, 'high'),
            Rows::string($row, 'low'),
            Rows::string($row, 'close'),
            Rows::string($row, 'volume'),
            Rows::string($row, 'quote_volume'),
            Rows::int($row, 'trade_count'),
            Rows::string($row, 'taker_buy_volume'),
        );
    }

    private static function key(Timeframe $timeframe, CarbonImmutable $openTime): string
    {
        return $timeframe->value.'|'.$openTime->getTimestamp();
    }
}
