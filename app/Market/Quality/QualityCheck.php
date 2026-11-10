<?php

declare(strict_types=1);

namespace App\Market\Quality;

use App\Market\Instruments\TradingBreaks;
use App\Market\Models\Instrument;
use App\Market\Persistence\Rows;
use App\Support\Number\Decimal;
use App\Support\Timeframe;
use Carbon\CarbonImmutable;
use Illuminate\Database\ConnectionInterface;

/**
 * Checks stored 1-minute candles (docs/market-data.md, section 5). Reads raw rows, so a broken row is reported
 * instead of stopping the check.
 */
final readonly class QualityCheck
{
    private const int ZERO_VOLUME_RUN_MINUTES = 30;

    private const int OUTLIER_NEIGHBOURS = 7;

    private const string OUTLIER_FRACTION = '0.05';

    public function __construct(private ConnectionInterface $db, private TradingBreaks $breaks) {}

    public function run(Instrument $instrument, CarbonImmutable $from, CarbonImmutable $to): QualityReport
    {
        // Minutes before the listing and from the delisting on are not gaps (D-89).
        $from = Timeframe::OneMinute->openTimeContaining($instrument->listed_at === null ? $from : $from->max($instrument->listed_at));
        $to = Timeframe::OneMinute->openTimeContaining($instrument->delisted_at === null ? $to : $to->min($instrument->delisted_at->subMinute()));
        $first = $from->getTimestamp();
        $last = $to->getTimestamp();
        // Nor are the minutes of a break in trading, and a candle stored in one is not a price: it is passed over.
        $breaks = $this->breaks->among([$instrument->id])[$instrument->id] ?? [];

        // One pass over the rows, a day at a time, so that any window fits in memory (two years are a million rows).
        $stored = 0;
        $gaps = $runs = $outliers = $window = [];
        $expected = $first;
        $runStart = $previous = null;
        $evaluated = 0;

        foreach ($this->rows($instrument, $first, $last) as [$time, $close, $zeroVolume]) {
            if (TradingBreaks::covers($breaks, $time)) {
                continue;
            }

            $stored++;
            array_push($gaps, ...self::missing($expected, $time, $breaks));

            $expected = $time + 60;

            if ($runStart !== null && $previous !== null && (! $zeroVolume || $previous + 60 !== $time)) {
                $this->closeRun($runs, $runStart, $previous);
                $runStart = null;
            }

            if ($zeroVolume && $runStart === null) {
                $runStart = $time;
            }

            $previous = $time;
            $window[] = [$time, $close];
            $this->evaluateOutliers($window, $evaluated, $outliers, false);
        }

        if ($runStart !== null && $previous !== null) {
            $this->closeRun($runs, $runStart, $previous);
        }

        $this->evaluateOutliers($window, $evaluated, $outliers, true);

        array_push($gaps, ...self::missing($expected, $last + 60, $breaks));
        $expectedMinutes = intdiv($last - $first, 60) + 1;

        foreach ($breaks as [$stopped, $resumed]) {
            $expectedMinutes -= max(0, intdiv(min($resumed, $last + 60) - max($stopped, $first), 60));
        }

        return new QualityReport(
            expectedMinutes: $expectedMinutes,
            storedMinutes: $stored,
            missingMinutes: $expectedMinutes - $stored,
            gaps: $gaps,
            ohlcViolations: $this->ohlcViolations($instrument, $from, $to),
            zeroVolumeRuns: $runs,
            outliers: $outliers,
        );
    }

    /** @return iterable<array{int, Decimal, bool}> open time, close and whether volume is zero, oldest first */
    private function rows(Instrument $instrument, int $first, int $last): iterable
    {
        for ($day = $first; $day <= $last; $day += 86_400) {
            $rows = $this->db->table('candles')
                ->where('instrument_id', $instrument->id)
                ->where('timeframe', Timeframe::OneMinute->value)
                ->whereBetween('open_time', [
                    Rows::timestamp(CarbonImmutable::createFromTimestampUTC($day)),
                    Rows::timestamp(CarbonImmutable::createFromTimestampUTC(min($day + 86_400 - 60, $last))),
                ])
                ->orderBy('open_time')
                ->get(['open_time', 'close', 'volume']);

            foreach ($rows as $row) {
                yield [
                    Rows::time($row, 'open_time')->getTimestamp(),
                    Decimal::of(Rows::string($row, 'close')),
                    Decimal::of(Rows::string($row, 'volume'))->isZero(),
                ];
            }
        }
    }

    /**
     * The minutes from $from up to $until that are in no break, as ranges.
     *
     * @param  list<array{int, int}>  $breaks  oldest first
     * @return list<array{from: CarbonImmutable, to: CarbonImmutable}>
     */
    private static function missing(int $from, int $until, array $breaks): array
    {
        $gaps = [];

        foreach ($breaks as [$stopped, $resumed]) {
            if ($resumed <= $from || $stopped >= $until) {
                continue;
            }

            if ($stopped > $from) {
                $gaps[] = self::range($from, $stopped - 60);
            }

            $from = $resumed;
        }

        if ($from < $until) {
            $gaps[] = self::range($from, $until - 60);
        }

        return $gaps;
    }

    /** @return array{from: CarbonImmutable, to: CarbonImmutable} */
    private static function range(int $from, int $to): array
    {
        return ['from' => CarbonImmutable::createFromTimestampUTC($from), 'to' => CarbonImmutable::createFromTimestampUTC($to)];
    }

    /** @param list<array{from: CarbonImmutable, to: CarbonImmutable}> $runs */
    private function closeRun(array &$runs, int $start, int $end): void
    {
        if (intdiv($end - $start, 60) + 1 > self::ZERO_VOLUME_RUN_MINUTES) {
            $runs[] = self::range($start, $end);
        }
    }

    /**
     * Judges each close against the median of up to 7 closes on each side, as soon as the 7 after it have arrived
     * (or at the end), then forgets what no later close needs.
     *
     * @param  list<array{int, Decimal}>  $window
     * @param  list<CarbonImmutable>  $outliers
     */
    private function evaluateOutliers(array &$window, int &$evaluated, array &$outliers, bool $final): void
    {
        $limit = Decimal::of(self::OUTLIER_FRACTION);

        while ($evaluated < count($window) && ($final || count($window) - 1 - $evaluated >= self::OUTLIER_NEIGHBOURS)) {
            $neighbours = array_map(
                static fn (array $row): Decimal => $row[1],
                [
                    ...array_slice($window, max(0, $evaluated - self::OUTLIER_NEIGHBOURS), min($evaluated, self::OUTLIER_NEIGHBOURS)),
                    ...array_slice($window, $evaluated + 1, self::OUTLIER_NEIGHBOURS),
                ],
            );

            if ($neighbours !== []) {
                $median = self::median($neighbours);
                [$time, $close] = $window[$evaluated];

                if ($close->minus($median)->abs()->isGreaterThan($median->times($limit))) {
                    $outliers[] = CarbonImmutable::createFromTimestampUTC($time);
                }
            }

            $evaluated++;

            if ($evaluated > self::OUTLIER_NEIGHBOURS) {
                array_shift($window);
                $evaluated--;
            }
        }
    }

    private function ohlcViolations(Instrument $instrument, CarbonImmutable $from, CarbonImmutable $to): int
    {
        return $this->db->table('candles')
            ->where('instrument_id', $instrument->id)
            ->where('timeframe', Timeframe::OneMinute->value)
            ->whereBetween('open_time', [Rows::timestamp($from), Rows::timestamp($to)])
            ->whereRaw('NOT (low > 0 AND low <= open AND low <= close AND low <= high AND high >= open AND high >= close AND volume >= 0)')
            ->count();
    }

    /** @param non-empty-list<Decimal> $values */
    private static function median(array $values): Decimal
    {
        usort($values, static fn (Decimal $a, Decimal $b): int => $a->compare($b));
        $middle = intdiv(count($values), 2);

        if (count($values) % 2 === 1) {
            return $values[$middle];
        }

        return $values[$middle - 1]->plus($values[$middle])->dividedBy(Decimal::of(2), 18);
    }
}
