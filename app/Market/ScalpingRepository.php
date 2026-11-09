<?php

declare(strict_types=1);

namespace App\Market;

use App\Market\Data\CandleSource;
use App\Market\Data\OpenInterest;
use App\Market\Data\PriceMinute;
use App\Market\Data\TradeFlow;
use App\Market\Data\UpsertResult;
use App\Market\Models\Instrument;
use App\Market\Persistence\Rows;
use App\Support\Clock\Clock;
use App\Support\Number\Decimal;
use Carbon\CarbonImmutable;
use Illuminate\Database\ConnectionInterface;
use UnexpectedValueException;

/**
 * Stores and reads the data for scalping (D-79): trade flow, open interest, mark price and premium index. A row is
 * replaced only by a higher-ranked source (archive above REST above stream), as for candles.
 */
final readonly class ScalpingRepository
{
    private const array COLUMNS = [
        'trade_flow' => ['open_time', ['large_buy_volume', 'large_sell_volume', 'large_buy_count', 'large_sell_count', 'largest_notional', 'threshold']],
        'open_interest' => ['time', ['contracts', 'value']],
        'mark_price_minutes' => ['open_time', ['open', 'high', 'low', 'close']],
        'premium_index_minutes' => ['open_time', ['open', 'high', 'low', 'close']],
    ];

    public function __construct(private ConnectionInterface $db, private Clock $clock) {}

    /** @param iterable<TradeFlow> $rows */
    public function upsertFlow(Instrument $instrument, CandleSource $source, iterable $rows): UpsertResult
    {
        $values = [];

        foreach ($rows as $f) {
            $values[] = [$f->openTime, (string) $f->largeBuyVolume, (string) $f->largeSellVolume, $f->largeBuyCount, $f->largeSellCount, (string) $f->largestNotional, (string) $f->threshold];
        }

        return $this->upsert('trade_flow', $instrument, $source, $values);
    }

    /** @param iterable<OpenInterest> $rows */
    public function upsertOpenInterest(Instrument $instrument, CandleSource $source, iterable $rows): UpsertResult
    {
        $values = [];

        foreach ($rows as $o) {
            $values[] = [$o->time, (string) $o->contracts, (string) $o->value];
        }

        return $this->upsert('open_interest', $instrument, $source, $values);
    }

    /** @param iterable<PriceMinute> $rows */
    public function upsertMarks(Instrument $instrument, CandleSource $source, iterable $rows): UpsertResult
    {
        return $this->upsert('mark_price_minutes', $instrument, $source, self::prices($rows));
    }

    /** @param iterable<PriceMinute> $rows */
    public function upsertPremium(Instrument $instrument, CandleSource $source, iterable $rows): UpsertResult
    {
        return $this->upsert('premium_index_minutes', $instrument, $source, self::prices($rows));
    }

    /** @return list<TradeFlow> minutes opening from $from to $to, oldest first */
    public function flow(Instrument $instrument, CarbonImmutable $from, CarbonImmutable $to): array
    {
        return array_map(fn (object $r): TradeFlow => new TradeFlow(
            Rows::time($r, 'open_time'), Decimal::of(Rows::string($r, 'large_buy_volume')), Decimal::of(Rows::string($r, 'large_sell_volume')),
            Rows::int($r, 'large_buy_count'), Rows::int($r, 'large_sell_count'), Decimal::of(Rows::string($r, 'largest_notional')), Decimal::of(Rows::string($r, 'threshold')),
        ), $this->rows('trade_flow', $instrument, $from, $to));
    }

    /** @return list<OpenInterest> values stamped from $from to $to, oldest first */
    public function openInterest(Instrument $instrument, CarbonImmutable $from, CarbonImmutable $to): array
    {
        return array_map(fn (object $r): OpenInterest => new OpenInterest(Rows::time($r, 'time'), Decimal::of(Rows::string($r, 'contracts')), Decimal::of(Rows::string($r, 'value'))), $this->rows('open_interest', $instrument, $from, $to));
    }

    /** @return list<PriceMinute> */
    public function marks(Instrument $instrument, CarbonImmutable $from, CarbonImmutable $to): array
    {
        return array_map(self::price(...), $this->rows('mark_price_minutes', $instrument, $from, $to));
    }

    /** @return list<PriceMinute> */
    public function premium(Instrument $instrument, CarbonImmutable $from, CarbonImmutable $to): array
    {
        return array_map(self::price(...), $this->rows('premium_index_minutes', $instrument, $from, $to));
    }

    /** How many rows a table holds for the instrument from $from to $to (minutes, or 5-minute times for open interest). */
    public function count(string $table, Instrument $instrument, CarbonImmutable $from, CarbonImmutable $to): int
    {
        $key = self::COLUMNS[$table][0];

        return $this->db->table($table)->where('instrument_id', $instrument->id)->whereBetween($key, [Rows::timestamp($from), Rows::timestamp($to)])->count();
    }

    /**
     * @param  list<list<mixed>>  $values  the key time first, then the columns of the table, in order
     */
    private function upsert(string $table, Instrument $instrument, CandleSource $source, array $values): UpsertResult
    {
        [$key, $columns] = self::COLUMNS[$table];
        $receivedAt = Rows::timestamp($this->clock->now());
        $inserted = $replaced = $kept = 0;

        foreach (array_chunk($values, 1000) as $chunk) {
            $times = array_map(fn (array $v): string => Rows::timestamp($v[0] instanceof CarbonImmutable ? $v[0] : throw new UnexpectedValueException('A row without its time.')), $chunk);
            $existing = [];

            foreach ($this->db->table($table)->where('instrument_id', $instrument->id)->whereIn($key, $times)->get([$key, 'source']) as $row) {
                $existing[Rows::timestamp(Rows::time($row, $key))] = Rows::int($row, 'source');
            }

            $placeholders = $bindings = [];

            foreach ($chunk as $i => $value) {
                $before = $existing[$times[$i]] ?? null;
                $before === null ? $inserted++ : ($before < $source->value ? $replaced++ : $kept++);
                $placeholders[] = '('.implode(', ', array_fill(0, count($columns) + 4, '?')).')';
                array_push($bindings, $instrument->id, $times[$i], ...array_slice($value, 1));
                array_push($bindings, $source->value, $receivedAt);
            }

            $set = implode(', ', array_map(fn (string $c): string => "{$c} = EXCLUDED.{$c}", [...$columns, 'source', 'received_at']));
            $this->db->statement(
                "INSERT INTO {$table} (instrument_id, {$key}, ".implode(', ', $columns).', source, received_at) VALUES '.implode(', ', $placeholders)
                ." ON CONFLICT (instrument_id, {$key}) DO UPDATE SET {$set} WHERE {$table}.source < EXCLUDED.source",
                $bindings,
            );
        }

        return new UpsertResult($inserted, $replaced, $kept);
    }

    /** @return list<object> */
    private function rows(string $table, Instrument $instrument, CarbonImmutable $from, CarbonImmutable $to): array
    {
        $key = self::COLUMNS[$table][0];

        return array_values($this->db->table($table)->where('instrument_id', $instrument->id)
            ->whereBetween($key, [Rows::timestamp($from), Rows::timestamp($to)])->orderBy($key)->get()->all());
    }

    /**
     * @param  iterable<PriceMinute>  $rows
     * @return list<list<mixed>>
     */
    private static function prices(iterable $rows): array
    {
        $values = [];

        foreach ($rows as $p) {
            $values[] = [$p->openTime, (string) $p->open, (string) $p->high, (string) $p->low, (string) $p->close];
        }

        return $values;
    }

    private static function price(object $r): PriceMinute
    {
        return new PriceMinute(Rows::time($r, 'open_time'), Decimal::of(Rows::string($r, 'open')), Decimal::of(Rows::string($r, 'high')), Decimal::of(Rows::string($r, 'low')), Decimal::of(Rows::string($r, 'close')));
    }
}
