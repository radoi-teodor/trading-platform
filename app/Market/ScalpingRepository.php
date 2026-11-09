<?php

declare(strict_types=1);

namespace App\Market;

use App\Market\Data\BookDepth;
use App\Market\Data\CandleSource;
use App\Market\Data\OpenInterest;
use App\Market\Data\Positioning;
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
 * written once, by whichever source comes first; a later one only fills what is missing, so a paper engine's
 * replay reads what it read live (D-84).
 */
final readonly class ScalpingRepository
{
    private const array COLUMNS = [
        'trade_flow' => ['open_time', ['large_buy_volume', 'large_sell_volume', 'large_buy_count', 'large_sell_count', 'largest_notional', 'threshold']],
        'open_interest' => ['time', ['contracts', 'value']],
        'positioning' => ['time', ['long_short_ratio', 'top_trader_ratio', 'taker_ratio']],
        'book_depth' => ['time', ['bid_1', 'bid_2', 'bid_5', 'ask_1', 'ask_2', 'ask_5']],
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

    /** @param iterable<Positioning> $rows */
    public function upsertPositioning(Instrument $instrument, CandleSource $source, iterable $rows): UpsertResult
    {
        $values = [];

        foreach ($rows as $p) {
            $values[] = [$p->time, $p->longShortRatio?->__toString(), $p->topTraderRatio?->__toString(), $p->takerRatio?->__toString()];
        }

        return $this->upsert('positioning', $instrument, $source, $values);
    }

    /** @param iterable<BookDepth> $rows */
    public function upsertDepth(Instrument $instrument, CandleSource $source, iterable $rows): UpsertResult
    {
        $values = [];

        foreach ($rows as $d) {
            $values[] = [$d->time, (string) $d->bid1, (string) $d->bid2, (string) $d->bid5, (string) $d->ask1, (string) $d->ask2, (string) $d->ask5];
        }

        return $this->upsert('book_depth', $instrument, $source, $values);
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

    /** @return list<Positioning> values stamped from $from to $to, oldest first */
    public function positioning(Instrument $instrument, CarbonImmutable $from, CarbonImmutable $to): array
    {
        return array_map(self::positioningRow(...), $this->rows('positioning', $instrument, $from, $to));
    }

    /** A stored positioning row, its missing ratios null. */
    public static function positioningRow(object $r): Positioning
    {
        $ratio = fn (string $column): ?Decimal => ($value = Rows::nullableString($r, $column)) === null ? null : Decimal::of($value);

        return new Positioning(Rows::time($r, 'time'), $ratio('long_short_ratio'), $ratio('top_trader_ratio'), $ratio('taker_ratio'));
    }

    /** @return list<BookDepth> 5-minute times from $from to $to, oldest first */
    public function depth(Instrument $instrument, CarbonImmutable $from, CarbonImmutable $to): array
    {
        return array_map(self::depthRow(...), $this->rows('book_depth', $instrument, $from, $to));
    }

    public static function depthRow(object $r): BookDepth
    {
        $d = fn (string $column): Decimal => Decimal::of(Rows::string($r, $column));

        return new BookDepth(Rows::time($r, 'time'), $d('bid_1'), $d('bid_2'), $d('bid_5'), $d('ask_1'), $d('ask_2'), $d('ask_5'));
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

    /** @return list<string> the days (UTC, Y-m-d) from $from to $to on which a table holds no row for the instrument */
    public function missingDays(string $table, Instrument $instrument, CarbonImmutable $from, CarbonImmutable $to): array
    {
        $key = self::COLUMNS[$table][0];
        $stored = [];

        foreach ($this->db->table($table)->where('instrument_id', $instrument->id)->whereBetween($key, [Rows::timestamp($from), Rows::timestamp($to)])
            ->selectRaw("to_char({$key} at time zone 'UTC', 'YYYY-MM-DD') as day")->distinct()->get() as $row) {
            $stored[Rows::string($row, 'day')] = true;
        }

        $missing = [];

        for ($day = $from->utc()->startOfDay(); $day < $to; $day = $day->addDay()) {
            if (! isset($stored[$day->format('Y-m-d')])) {
                $missing[] = $day->format('Y-m-d');
            }
        }

        return $missing;
    }

    /**
     * @param  list<list<mixed>>  $values  the key time first, then the columns of the table, in order
     */
    private function upsert(string $table, Instrument $instrument, CandleSource $source, array $values): UpsertResult
    {
        [$key, $columns] = self::COLUMNS[$table];
        $receivedAt = Rows::timestamp($this->clock->now());
        $inserted = $kept = 0;

        foreach (array_chunk($values, 1000) as $chunk) {
            $times = array_map(fn (array $v): string => Rows::timestamp($v[0] instanceof CarbonImmutable ? $v[0] : throw new UnexpectedValueException('A row without its time.')), $chunk);
            $existing = [];

            foreach ($this->db->table($table)->where('instrument_id', $instrument->id)->whereIn($key, $times)->get([$key]) as $row) {
                $existing[Rows::timestamp(Rows::time($row, $key))] = true;
            }

            $placeholders = $bindings = [];

            foreach ($chunk as $i => $value) {
                isset($existing[$times[$i]]) ? $kept++ : $inserted++;
                $placeholders[] = '('.implode(', ', array_fill(0, count($columns) + 4, '?')).')';
                array_push($bindings, $instrument->id, $times[$i], ...array_slice($value, 1));
                array_push($bindings, $source->value, $receivedAt);
            }

            $this->db->statement(
                "INSERT INTO {$table} (instrument_id, {$key}, ".implode(', ', $columns).', source, received_at) VALUES '.implode(', ', $placeholders)
                ." ON CONFLICT (instrument_id, {$key}) DO NOTHING",
                $bindings,
            );
        }

        return new UpsertResult($inserted, 0, $kept);
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
