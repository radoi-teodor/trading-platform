<?php

declare(strict_types=1);

namespace App\Risk;

use App\Risk\Data\RiskEvent;
use App\Risk\Data\RiskEventKind;
use App\Risk\Data\RiskProfile;
use App\Support\Number\Decimal;
use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;

/**
 * The loss limits over time (docs/engine.md, section 5): equity at 00:00 UTC and on Monday 00:00 UTC, the equity
 * peak, and the halts they trigger. Updated with the marked equity at every event, before strategies run.
 */
final class RiskState
{
    private ?CarbonImmutable $day = null;

    private Decimal $dayStart;

    private ?CarbonImmutable $week = null;

    private Decimal $weekStart;

    private ?Decimal $peak = null;

    private ?CarbonImmutable $dailyHaltUntil = null;

    private bool $weeklyHalt = false;

    private bool $drawdownHalt = false;

    private bool $flattened = false;

    public function __construct(private readonly RiskPolicy $policy)
    {
        $this->dayStart = $this->weekStart = Decimal::zero();
    }

    /** @return list<RiskEvent> the limits reached by this update */
    public function update(CarbonImmutable $time, Decimal $equity): array
    {
        if ($this->policy->profile === RiskProfile::Benchmark) {
            return [];
        }

        $day = $time->startOfDay();
        $week = $time->startOfWeek(CarbonInterface::MONDAY);

        if ($this->day === null || ! $this->day->equalTo($day)) {
            $this->day = $day;
            $this->dayStart = $equity;
        }

        if ($this->week === null || ! $this->week->equalTo($week)) {
            $this->week = $week;
            $this->weekStart = $equity;
        }

        $this->peak = $this->peak === null || $equity->isGreaterThan($this->peak) ? $equity : $this->peak;
        $events = [];

        if ($this->dailyHaltUntil === null && self::fell($equity, $this->dayStart, $this->policy->dailyLoss)) {
            $this->dailyHaltUntil = $day->addDay();
            $events[] = new RiskEvent(RiskEventKind::DailyLossHalt, $time, $equity);
        }

        if ($this->dailyHaltUntil !== null && ! $time->lessThan($this->dailyHaltUntil)) {
            $this->dailyHaltUntil = null;
        }

        if (! $this->weeklyHalt && self::fell($equity, $this->weekStart, $this->policy->weeklyLoss)) {
            $this->weeklyHalt = true;
            $events[] = new RiskEvent(RiskEventKind::WeeklyLossHalt, $time, $equity);
        }

        if (! $this->drawdownHalt && self::fell($equity, $this->peak, $this->policy->drawdownHalt)) {
            $this->drawdownHalt = true;
            $events[] = new RiskEvent(RiskEventKind::DrawdownHalt, $time, $equity);
        }

        if (! $this->flattened && self::fell($equity, $this->peak, $this->policy->drawdownFlatten)) {
            $this->flattened = true;
            $events[] = new RiskEvent(RiskEventKind::DrawdownFlatten, $time, $equity);
        }

        return $events;
    }

    /** Why entries are blocked at $time, or null when they are allowed. */
    public function entryBlock(CarbonImmutable $time): ?string
    {
        return match (true) {
            $this->drawdownHalt || $this->flattened => 'drawdown',
            $this->weeklyHalt => 'weekly_loss',
            $this->dailyHaltUntil !== null && $time->lessThan($this->dailyHaltUntil) => 'daily_loss',
            default => null,
        };
    }

    public function peak(): ?Decimal
    {
        return $this->peak;
    }

    /** Whether $equity is at least $limit (a fraction) below $reference. */
    private static function fell(Decimal $equity, Decimal $reference, Decimal $limit): bool
    {
        return ! $equity->isGreaterThan($reference->times(Decimal::of(1)->minus($limit)));
    }
}
