<?php

declare(strict_types=1);

namespace App\Market;

use App\Market\Data\Liquidations;
use App\Market\Models\Instrument;
use App\Market\Persistence\Rows;
use App\Support\Clock\Clock;
use App\Support\Number\Decimal;
use Carbon\CarbonImmutable;
use Illuminate\Database\ConnectionInterface;

/** Liquidations per perpetual and minute, and the minutes the stream heard whole (D-91). */
final readonly class LiquidationRepository
{
    public function __construct(private ConnectionInterface $db, private Clock $clock) {}

    /** Stores a minute's liquidations of one perpetual; a minute already stored is kept as it is (D-84). */
    public function store(Instrument $instrument, CarbonImmutable $minute, int $longCount, string $longNotional, int $shortCount, string $shortNotional): void
    {
        $this->db->table('liquidation_minutes')->insertOrIgnore([
            'instrument_id' => $instrument->id, 'open_time' => Rows::timestamp($minute),
            'long_count' => $longCount, 'long_notional' => $longNotional, 'short_count' => $shortCount, 'short_notional' => $shortNotional,
            'received_at' => Rows::timestamp($this->clock->now()),
        ]);
    }

    /** @param list<CarbonImmutable> $minutes minutes the stream heard whole */
    public function cover(array $minutes): void
    {
        if ($minutes !== []) {
            $this->db->table('liquidation_coverage')->insertOrIgnore(array_map(fn (CarbonImmutable $m): array => ['minute' => Rows::timestamp($m)], $minutes));
        }
    }

    /** @return list<Liquidations> the stored minutes opening from $from to $to, oldest first */
    public function minutes(Instrument $instrument, CarbonImmutable $from, CarbonImmutable $to): array
    {
        return array_values($this->db->table('liquidation_minutes')->where('instrument_id', $instrument->id)
            ->whereBetween('open_time', [Rows::timestamp($from), Rows::timestamp($to)])->orderBy('open_time')->get()
            ->map(fn (object $r): Liquidations => self::row($r))->all());
    }

    public static function row(object $r): Liquidations
    {
        return new Liquidations(
            Rows::time($r, 'open_time'), Rows::int($r, 'long_count'), Decimal::of(Rows::string($r, 'long_notional')),
            Rows::int($r, 'short_count'), Decimal::of(Rows::string($r, 'short_notional')),
        );
    }

    /** @return list<string> the days (UTC, Y-m-d) from $from to $to with a minute the stream did not hear */
    public function missingDays(CarbonImmutable $from, CarbonImmutable $to): array
    {
        $covered = [];

        foreach ($this->db->table('liquidation_coverage')->whereBetween('minute', [Rows::timestamp($from), Rows::timestamp($to)])
            ->selectRaw("to_char(minute at time zone 'UTC', 'YYYY-MM-DD') as day, count(*) as minutes")->groupByRaw('1')->get() as $row) {
            $covered[Rows::string($row, 'day')] = Rows::int($row, 'minutes');
        }

        $missing = [];

        for ($day = $from->utc()->startOfDay(); $day < $to; $day = $day->addDay()) {
            $start = $day->max($from);
            $end = $day->addDay()->min($to);
            $expected = intdiv($end->getTimestamp() - $start->getTimestamp(), 60);

            if (($covered[$day->format('Y-m-d')] ?? 0) < $expected) {
                $missing[] = $day->format('Y-m-d');
            }
        }

        return $missing;
    }
}
