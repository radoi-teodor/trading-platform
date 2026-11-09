<?php

declare(strict_types=1);

namespace App\Market\Instruments;

use Carbon\CarbonImmutable;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Database\Query\Builder;

/**
 * The research universe (D-89): on the first day of each month, the top N USDT-quoted crypto perpetuals by quote
 * volume over the 30 days before, among those listed at least 30 days earlier and not yet delisted. Stored month by
 * month in universe_members, so a backtest at any time sees only the members of that time, delisted ones included.
 */
final readonly class ResearchUniverse
{
    public const int LOOKBACK_DAYS = 30;

    public function __construct(private ConnectionInterface $db) {}

    /** Ranks the months from $from to $to (first days of months, both included); returns the months ranked. */
    public function build(int $top, CarbonImmutable $from, CarbonImmutable $to): int
    {
        $months = 0;

        for ($month = $from->startOfMonth(); $month->lessThanOrEqualTo($to->startOfMonth()); $month = $month->addMonth()) {
            $start = $month->subDays(self::LOOKBACK_DAYS);
            $candidates = DailyHistory::candidates()->select('instruments.id')->toBase();

            $this->db->transaction(function () use ($month, $start, $top, $candidates): void {
                $this->db->table('universe_members')->where('month', $month->format('Y-m-d'))->delete();
                $ranked = $this->db->table('daily_stats as d')
                    ->join('instruments as i', 'i.id', '=', 'd.instrument_id')
                    ->whereIn('d.instrument_id', $candidates)
                    ->where('d.day', '>=', $start->format('Y-m-d'))
                    ->where('d.day', '<', $month->format('Y-m-d'))
                    ->where(fn (Builder $q) => $q->whereNull('i.listed_at')->orWhere('i.listed_at', '<=', $start->toIso8601String()))
                    ->where(fn (Builder $q) => $q->whereNull('i.delisted_at')->orWhere('i.delisted_at', '>', $month->toIso8601String()))
                    ->groupBy('d.instrument_id', 'i.symbol')
                    ->orderByRaw('sum(d.quote_volume) desc, i.symbol')
                    ->limit($top)
                    ->get(['d.instrument_id', $this->db->raw('sum(d.quote_volume) as volume')]);
                $rows = [];

                foreach ($ranked->values() as $index => $row) {
                    $rows[] = ['month' => $month->format('Y-m-d'), 'instrument_id' => $row->instrument_id, 'rank' => $index + 1, 'quote_volume_30d' => $row->volume];
                }

                $this->db->table('universe_members')->insert($rows);
            });

            $months++;
        }

        return $months;
    }

    /** @return list<string> the members at a moment, by rank */
    public function symbolsAt(CarbonImmutable $at): array
    {
        return array_values($this->db->table('universe_members as u')->join('instruments as i', 'i.id', '=', 'u.instrument_id')
            ->where('u.month', $at->utc()->startOfMonth()->format('Y-m-d'))->orderBy('u.rank')->pluck('i.symbol')
            ->map(fn (mixed $symbol): string => is_string($symbol) ? $symbol : '')->all());
    }

    /**
     * What to import: each instrument over its runs of consecutive member months, from the month before (the
     * indicators' warmup) to the last day of the run, by symbol then time.
     *
     * @return list<array{string, CarbonImmutable, CarbonImmutable}>
     */
    public function spans(): array
    {
        $spans = [];
        $open = null;

        foreach ($this->db->table('universe_members as u')->join('instruments as i', 'i.id', '=', 'u.instrument_id')
            ->orderBy('i.symbol')->orderBy('u.month')->get(['i.symbol', 'u.month']) as $row) {
            $symbol = is_string($row->symbol ?? null) ? $row->symbol : '';
            $month = CarbonImmutable::parse(is_string($row->month ?? null) ? $row->month : '', 'UTC')->startOfMonth();

            if ($open !== null && $open[0] === $symbol && $open[2]->equalTo($month)) {
                $open[2] = $month->addMonth();

                continue;
            }

            if ($open !== null) {
                $spans[] = [$open[0], $open[1], $open[2]->subDay()];
            }

            $open = [$symbol, $month->subMonth(), $month->addMonth()];
        }

        if ($open !== null) {
            $spans[] = [$open[0], $open[1], $open[2]->subDay()];
        }

        return $spans;
    }

    /** @return array<string, CarbonImmutable> the first month each instrument was a member, by symbol */
    public function firstMonths(): array
    {
        $first = [];

        foreach ($this->db->table('universe_members as u')->join('instruments as i', 'i.id', '=', 'u.instrument_id')
            ->groupBy('i.symbol')->orderBy('i.symbol')->get(['i.symbol', $this->db->raw('min(u.month) as month')]) as $row) {
            if (is_string($row->symbol ?? null) && is_string($row->month ?? null)) {
                $first[$row->symbol] = CarbonImmutable::parse($row->month, 'UTC');
            }
        }

        return $first;
    }

    /**
     * The members of a month, by rank, with what the screen shows of them.
     *
     * @return list<object{rank: int, symbol: string, status: string, listed_at: string|null, quote_volume_30d: string}>
     */
    public function membersOf(CarbonImmutable $month): array
    {
        /** @var list<object{rank: int, symbol: string, status: string, listed_at: string|null, quote_volume_30d: string}> */
        return array_values($this->db->table('universe_members as u')->join('instruments as i', 'i.id', '=', 'u.instrument_id')
            ->where('u.month', $month->utc()->startOfMonth()->format('Y-m-d'))->orderBy('u.rank')
            ->get(['u.rank', 'i.symbol', 'i.status', 'i.listed_at', 'u.quote_volume_30d'])->all());
    }

    /** @return list<string> every instrument that was ever a member, by symbol */
    public function everyMember(): array
    {
        return array_values($this->db->table('universe_members as u')->join('instruments as i', 'i.id', '=', 'u.instrument_id')
            ->distinct()->orderBy('i.symbol')->pluck('i.symbol')
            ->map(fn (mixed $symbol): string => is_string($symbol) ? $symbol : '')->all());
    }
}
