<?php

declare(strict_types=1);

namespace App\Market\Quality;

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

    public function __construct(private ConnectionInterface $db) {}

    public function run(Instrument $instrument, CarbonImmutable $from, CarbonImmutable $to): QualityReport
    {
        $from = Timeframe::OneMinute->openTimeContaining($from);
        $to = Timeframe::OneMinute->openTimeContaining($to);
        $rows = $this->rows($instrument, $from, $to);
        $expected = intdiv($to->getTimestamp() - $from->getTimestamp(), 60) + 1;
        $gaps = $this->gaps($rows, $from, $to);

        return new QualityReport(
            expectedMinutes: $expected,
            storedMinutes: count($rows),
            missingMinutes: $expected - count($rows),
            gaps: $gaps,
            ohlcViolations: $this->ohlcViolations($instrument, $from, $to),
            zeroVolumeRuns: $this->zeroVolumeRuns($rows),
            outliers: $this->outliers($rows),
        );
    }

    /** @return list<array{time: CarbonImmutable, close: Decimal, volume: Decimal}> */
    private function rows(Instrument $instrument, CarbonImmutable $from, CarbonImmutable $to): array
    {
        return array_values($this->db->table('candles')
            ->where('instrument_id', $instrument->id)
            ->where('timeframe', Timeframe::OneMinute->value)
            ->whereBetween('open_time', [Rows::timestamp($from), Rows::timestamp($to)])
            ->orderBy('open_time')
            ->get(['open_time', 'close', 'volume'])
            ->map(fn (object $row): array => [
                'time' => Rows::time($row, 'open_time'),
                'close' => Decimal::of(Rows::string($row, 'close')),
                'volume' => Decimal::of(Rows::string($row, 'volume')),
            ])
            ->all());
    }

    /**
     * @param  list<array{time: CarbonImmutable, close: Decimal, volume: Decimal}>  $rows
     * @return list<array{from: CarbonImmutable, to: CarbonImmutable}>
     */
    private function gaps(array $rows, CarbonImmutable $from, CarbonImmutable $to): array
    {
        $gaps = [];
        $expected = $from;

        foreach ([...$rows, ['time' => $to->addMinute()]] as $row) {
            if ($row['time']->greaterThan($expected)) {
                $gaps[] = ['from' => $expected, 'to' => $row['time']->subMinute()];
            }

            $expected = $row['time']->addMinute();
        }

        return $gaps;
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

    /**
     * @param  list<array{time: CarbonImmutable, close: Decimal, volume: Decimal}>  $rows
     * @return list<array{from: CarbonImmutable, to: CarbonImmutable}>
     */
    private function zeroVolumeRuns(array $rows): array
    {
        $runs = [];
        $start = null;
        $previous = null;

        foreach ([...$rows, null] as $row) {
            $continues = $row !== null && $row['volume']->isZero()
                && ($previous === null || $previous['time']->addMinute()->equalTo($row['time']));

            if ($start !== null && ! $continues) {
                if ($previous !== null && $start->diffInMinutes($previous['time']) + 1 > self::ZERO_VOLUME_RUN_MINUTES) {
                    $runs[] = ['from' => $start, 'to' => $previous['time']];
                }

                $start = null;
            }

            if ($row !== null && $row['volume']->isZero() && $start === null) {
                $start = $row['time'];
            }

            $previous = $row;
        }

        return $runs;
    }

    /**
     * @param  list<array{time: CarbonImmutable, close: Decimal, volume: Decimal}>  $rows
     * @return list<CarbonImmutable>
     */
    private function outliers(array $rows): array
    {
        $outliers = [];
        $limit = Decimal::of(self::OUTLIER_FRACTION);

        foreach ($rows as $index => $row) {
            $neighbours = array_map(
                static fn (array $neighbour): Decimal => $neighbour['close'],
                [
                    ...array_slice($rows, max(0, $index - self::OUTLIER_NEIGHBOURS), min($index, self::OUTLIER_NEIGHBOURS)),
                    ...array_slice($rows, $index + 1, self::OUTLIER_NEIGHBOURS),
                ],
            );

            if ($neighbours === []) {
                continue;
            }

            $median = self::median($neighbours);

            if ($row['close']->minus($median)->abs()->isGreaterThan($median->times($limit))) {
                $outliers[] = $row['time'];
            }
        }

        return $outliers;
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
