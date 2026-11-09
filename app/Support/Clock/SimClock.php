<?php

declare(strict_types=1);

namespace App\Support\Clock;

use Carbon\CarbonImmutable;
use LogicException;

/** A clock that moves only when the simulation moves it, and never backwards. */
final class SimClock implements Clock
{
    private CarbonImmutable $now;

    public function __construct(CarbonImmutable $start)
    {
        $this->now = $start->utc();
    }

    public function now(): CarbonImmutable
    {
        return $this->now;
    }

    public function advanceTo(CarbonImmutable $moment): void
    {
        $moment = $moment->utc();

        if ($moment->lessThan($this->now)) {
            throw new LogicException("A simulated clock cannot go back from {$this->now->toIso8601String()} to {$moment->toIso8601String()}.");
        }

        $this->now = $moment;
    }

    public function advanceBy(int $seconds): void
    {
        if ($seconds < 0) {
            throw new LogicException('A simulated clock cannot go back.');
        }

        $this->now = $this->now->addSeconds($seconds);
    }
}
