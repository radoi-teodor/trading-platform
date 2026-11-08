<?php

declare(strict_types=1);

namespace App\Simulation\Validation;

use App\Market\Data\Candle;
use App\Simulation\Metrics\Statistics;
use App\Strategy\Indicators\DirectionalMovement;
use App\Strategy\Indicators\RealizedVolatility;
use App\Support\Number\Decimal;
use App\Support\Timeframe;
use Carbon\CarbonImmutable;
use InvalidArgumentException;

/**
 * Deterministic regime labels for the regime breakdown (docs/simulation.md, section 5; D-58): the daily ADX(14) at 25
 * or more is trend, under 20 range, otherwise unknown; a 30-day realized volatility above its 80th percentile over
 * the history read is high_volatility, which takes precedence. A time takes the label of the last daily candle
 * closed at or before it.
 */
final readonly class RegimeLabels
{
    public const array LABELS = ['trend', 'range', 'high_volatility', 'unknown'];

    /** @param array<int, string> $labels by the timestamp at which each daily candle closes, ascending */
    private function __construct(private array $labels) {}

    /** @param list<Candle> $daily daily candles of the history read, in order */
    public static function fromDailyCandles(array $daily): self
    {
        $adx = new DirectionalMovement(14);
        $volatility = new RealizedVolatility(30, Timeframe::OneDay);
        $rows = [];

        foreach ($daily as $candle) {
            if ($candle->timeframe !== Timeframe::OneDay) {
                throw new InvalidArgumentException('Regime labels are read from daily candles.');
            }

            $rows[$candle->endsAt()->getTimestamp()] = [$adx->push($candle)['adx'], $volatility->push($candle->close->value)];
        }

        $vols = array_values(array_filter(array_map(fn (array $row): ?Decimal => $row[1], $rows)));
        usort($vols, fn (Decimal $a, Decimal $b): int => $a->compare($b));
        $threshold = $vols === [] ? null : MonteCarlo::percentile($vols, 80);
        $labels = [];

        foreach ($rows as $time => [$strength, $vol]) {
            $labels[$time] = match (true) {
                $threshold !== null && $vol !== null && $vol->isGreaterThan($threshold) => 'high_volatility',
                $strength === null => 'unknown',
                ! $strength->isLessThan(Decimal::of(25)) => 'trend',
                $strength->isLessThan(Decimal::of(20)) => 'range',
                default => 'unknown',
            };
        }

        return new self($labels);
    }

    public function at(CarbonImmutable $time): string
    {
        $label = 'unknown';

        foreach ($this->labels as $closed => $value) {
            if ($closed > $time->getTimestamp()) {
                break;
            }

            $label = $value;
        }

        return $label;
    }

    /**
     * Trades split by the regime at their entry: count, net and expectancy for each label.
     *
     * @param  list<array{opened: CarbonImmutable, net: Decimal}>  $trades
     * @return array<string, array{trades: int, net: Decimal, expectancy: ?Decimal}>
     */
    public function breakdown(array $trades): array
    {
        $groups = array_fill_keys(self::LABELS, []);

        foreach ($trades as $trade) {
            $groups[$this->at($trade['opened'])][] = $trade['net'];
        }

        return array_map(fn (array $nets): array => [
            'trades' => count($nets), 'net' => Statistics::sum($nets), 'expectancy' => Statistics::mean($nets),
        ], $groups);
    }
}
