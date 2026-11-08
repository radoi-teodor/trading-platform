<?php

declare(strict_types=1);

namespace App\Simulation\Metrics;

use App\Support\Number\Decimal;
use Carbon\CarbonImmutable;

/**
 * Follows one account's marked equity minute by minute: the running peak and the deepest fall from it, how long
 * it stayed under water, equity at 00:00 UTC and on the hour, and the share of minutes with a position open.
 */
final class EquityTracker
{
    private Decimal $peak;

    private CarbonImmutable $peakAt;

    private Decimal $maxDrawdown;

    private int $longestDrawdownSeconds = 0;

    private bool $underWater = false;

    private Decimal $last;

    private CarbonImmutable $lastAt;

    /** @var array<string, Decimal> equity at 00:00 UTC, keyed by that moment (Y-m-d H:i) */
    private array $daily = [];

    /** @var array<string, Decimal> equity on the hour */
    private array $hourly = [];

    private int $minutes = 0;

    private int $minutesInMarket = 0;

    public function __construct(public readonly Decimal $initial, public readonly CarbonImmutable $start)
    {
        $this->peak = $this->last = $initial;
        $this->peakAt = $this->lastAt = $start;
        $this->maxDrawdown = Decimal::zero();
    }

    public function record(CarbonImmutable $time, Decimal $equity, bool $inMarket): void
    {
        $this->minutes++;
        $this->minutesInMarket += $inMarket ? 1 : 0;
        $this->last = $equity;
        $this->lastAt = $time;

        if (! $equity->isLessThan($this->peak)) {
            $this->closeDrawdown($time);
            $this->peak = $equity;
            $this->peakAt = $time;
        } else {
            $this->underWater = true;
            $fall = $this->peak->minus($equity)->dividedBy($this->peak, Statistics::SCALE);
            $this->maxDrawdown = $fall->isGreaterThan($this->maxDrawdown) ? $fall : $this->maxDrawdown;
        }

        if ($time->minute === 0 && $time->second === 0) {
            $this->hourly[$time->format('Y-m-d H:i')] = $equity;

            if ($time->hour === 0) {
                $this->daily[$time->format('Y-m-d H:i')] = $equity;
            }
        }
    }

    public function finalEquity(): Decimal
    {
        return $this->last;
    }

    public function end(): CarbonImmutable
    {
        return $this->lastAt;
    }

    public function maxDrawdown(): Decimal
    {
        return $this->maxDrawdown;
    }

    /** The longest time from a peak to the recovery of that peak, or to the end while still below it. */
    public function longestDrawdownSeconds(): int
    {
        return $this->underWater
            ? max($this->longestDrawdownSeconds, $this->lastAt->getTimestamp() - $this->peakAt->getTimestamp())
            : $this->longestDrawdownSeconds;
    }

    /** @return array<string, Decimal> */
    public function daily(): array
    {
        return $this->daily;
    }

    /** @return array<string, Decimal> */
    public function hourly(): array
    {
        return $this->hourly;
    }

    /**
     * The start, every 00:00 UTC mark and the end, in time order: the points returns are measured between.
     *
     * @return array<string, Decimal>
     */
    public function points(): array
    {
        $points = [$this->start->format('Y-m-d H:i') => $this->initial, ...$this->daily];
        $points[$this->lastAt->format('Y-m-d H:i')] = $this->last;

        return $points;
    }

    public function timeInMarket(): ?Decimal
    {
        return $this->minutes === 0 ? null : Decimal::of($this->minutesInMarket)->dividedBy(Decimal::of($this->minutes), Statistics::SCALE);
    }

    private function closeDrawdown(CarbonImmutable $time): void
    {
        if ($this->underWater) {
            $this->longestDrawdownSeconds = max($this->longestDrawdownSeconds, $time->getTimestamp() - $this->peakAt->getTimestamp());
            $this->underWater = false;
        }
    }
}
