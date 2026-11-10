<?php

declare(strict_types=1);

namespace App\Market;

use App\Market\Data\ScreenMeasure;
use App\Market\Instruments\DailyHistory;
use Carbon\CarbonImmutable;
use Illuminate\Database\ConnectionInterface;

/**
 * Ranks instruments by what they did over a window of days, from daily_stats alone (D-90, D-93). It reads only the
 * days it is given, so a screen for a past day says what was known then.
 */
final readonly class MarketScreen
{
    public function __construct(private ConnectionInterface $db) {}

    /** The last day any instrument has daily stats for. */
    public function lastDay(): ?CarbonImmutable
    {
        $last = $this->db->table('daily_stats')->max('day');

        return is_string($last) ? CarbonImmutable::parse($last, 'UTC') : null;
    }

    /**
     * The instruments with the highest (or lowest) $by over the $days days that end with $last, ties by symbol. An
     * instrument without the measure is left out, never ranked as zero.
     *
     * @param  list<int>|null  $members  the instruments ranked; null for every candidate of the research universe
     * @param  bool  $wholeWindow  only instruments with a row on each of the days
     * @return list<object{symbol: string, days: int, price_return: string|null, quote_volume: string, funding: string|null, open_interest_change: string|null, volatility: string|null}>
     */
    public function rank(?array $members, CarbonImmutable $last, int $days, ScreenMeasure $by, bool $descending, int $limit, bool $wholeWindow): array
    {
        $window = $this->db->table('daily_stats')
            ->whereBetween('day', [$last->subDays($days - 1)->format('Y-m-d'), $last->format('Y-m-d')])
            ->whereIn('instrument_id', $members ?? DailyHistory::candidates()->reorder()->select('id'))
            ->groupBy('instrument_id')
            ->selectRaw(<<<'SQL'
                instrument_id, count(*) as days, sum(quote_volume) as quote_volume, avg(funding_rate) as funding,
                avg((high - low) / nullif(open, 0)) as volatility,
                (array_agg(open order by day))[1] as first_open,
                (array_agg(close order by day desc))[1] as last_close,
                (array_agg(open_interest_value order by day))[1] as first_interest,
                (array_agg(open_interest_value order by day desc))[1] as last_interest
                SQL);

        if ($wholeWindow) {
            $window->havingRaw('count(*) = ?', [$days]);
        }

        $measured = $this->db->table($window, 'w')->join('instruments as i', 'i.id', '=', 'w.instrument_id')
            ->selectRaw(<<<'SQL'
                i.symbol, w.days,
                round(w.last_close / nullif(w.first_open, 0) - 1, 6) as price_return,
                round(w.quote_volume, 2) as quote_volume,
                round(w.funding, 8) as funding,
                case when w.days > 1 then round(w.last_interest / nullif(w.first_interest, 0) - 1, 6) end as open_interest_change,
                round(w.volatility, 6) as volatility
                SQL);

        /** @var list<object{symbol: string, days: int, price_return: string|null, quote_volume: string, funding: string|null, open_interest_change: string|null, volatility: string|null}> */
        return array_values($this->db->table($measured, 'm')->whereNotNull($by->column())
            ->orderBy($by->column(), $descending ? 'desc' : 'asc')->orderBy('symbol')->limit($limit)->get()->all());
    }
}
