<?php

declare(strict_types=1);

namespace App\Market;

use App\Market\Instruments\DailyHistory;
use App\Market\Models\Instrument;
use Carbon\CarbonImmutable;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Database\Eloquent\Builder;

/**
 * What a perpetual did after its listing, from daily_stats alone (D-89, D-93): its volume and return after one, seven
 * and thirty days, its return since, and its deepest fall. A day's age is counted from the UTC day of the listing.
 */
final readonly class ListingStudy
{
    public function __construct(private ConnectionInterface $db) {}

    /** How many candidates of the research universe were listed from $from to before $to. */
    public function count(CarbonImmutable $from, CarbonImmutable $to): int
    {
        return self::listed($from, $to)->count();
    }

    /**
     * Each of them, by listing time, then symbol. first_age is how many days after its listing its stored days begin:
     * null with no day stored, and above zero when what it did on its first day is not known. A value over seven or
     * thirty days is null until every one of those days is stored. The drawdown measures each day's low against the
     * highest high of the days before it, or its own open when that is higher: a fall inside one day from that day's
     * own high is not seen, so the true fall is at least this deep.
     *
     * @return list<object{symbol: string, status: string, listed_at: string, delisted_at: string|null, first_day: string|null, last_day: string|null, days: int|null, first_age: int|null, volume_1d: string|null, volume_7d: string|null, volume_30d: string|null, return_1d: string|null, return_7d: string|null, return_30d: string|null, return_since_listing: string|null, max_drawdown: string|null}>
     */
    public function of(CarbonImmutable $from, CarbonImmutable $to): array
    {
        $listed = self::listed($from, $to)->toBase()->selectRaw("id, (listed_at at time zone 'UTC')::date as listing_day");

        $days = $this->db->table('daily_stats as s')->joinSub($listed, 'l', 'l.id', '=', 's.instrument_id')
            ->whereColumn('s.day', '>=', 'l.listing_day')
            ->selectRaw(<<<'SQL'
                s.instrument_id, s.day, s.day - l.listing_day as age, s.open, s.close, s.low, s.quote_volume,
                greatest(max(s.high) over (partition by s.instrument_id order by s.day rows between unbounded preceding and 1 preceding), s.open) as peak
                SQL);

        $after = $this->db->table($days, 'd')->groupBy('d.instrument_id')
            ->selectRaw(<<<'SQL'
                d.instrument_id, min(d.day) as first_day, max(d.day) as last_day, count(*) as days, min(d.age) as first_age,
                count(*) filter (where d.age < 7) = 7 as has_week,
                count(*) filter (where d.age < 30) = 30 as has_month,
                sum(d.quote_volume) filter (where d.age = 0) as volume_1d,
                sum(d.quote_volume) filter (where d.age < 7) as volume_7d,
                sum(d.quote_volume) filter (where d.age < 30) as volume_30d,
                max(d.open) filter (where d.age = 0) as first_open,
                max(d.close) filter (where d.age = 0) as close_1d,
                max(d.close) filter (where d.age = 6) as close_7d,
                max(d.close) filter (where d.age = 29) as close_30d,
                (array_agg(d.close order by d.day desc))[1] as last_close,
                min(d.low / nullif(d.peak, 0) - 1) as max_drawdown
                SQL);

        /** @var list<object{symbol: string, status: string, listed_at: string, delisted_at: string|null, first_day: string|null, last_day: string|null, days: int|null, first_age: int|null, volume_1d: string|null, volume_7d: string|null, volume_30d: string|null, return_1d: string|null, return_7d: string|null, return_30d: string|null, return_since_listing: string|null, max_drawdown: string|null}> */
        return array_values(self::listed($from, $to)->toBase()->leftJoinSub($after, 'a', 'a.instrument_id', '=', 'instruments.id')
            ->orderBy('listed_at')->orderBy('symbol')
            ->selectRaw(<<<'SQL'
                symbol, status, listed_at, delisted_at, a.first_day, a.last_day, a.days, a.first_age,
                round(a.volume_1d, 2) as volume_1d,
                case when a.has_week then round(a.volume_7d, 2) end as volume_7d,
                case when a.has_month then round(a.volume_30d, 2) end as volume_30d,
                round(a.close_1d / nullif(a.first_open, 0) - 1, 6) as return_1d,
                case when a.has_week then round(a.close_7d / nullif(a.first_open, 0) - 1, 6) end as return_7d,
                case when a.has_month then round(a.close_30d / nullif(a.first_open, 0) - 1, 6) end as return_30d,
                round(a.last_close / nullif(a.first_open, 0) - 1, 6) as return_since_listing,
                round(a.max_drawdown, 6) as max_drawdown
                SQL)
            ->get()->all());
    }

    /** @return Builder<Instrument> */
    private static function listed(CarbonImmutable $from, CarbonImmutable $to): Builder
    {
        return DailyHistory::candidates()->reorder()->where('listed_at', '>=', $from)->where('listed_at', '<', $to);
    }
}
