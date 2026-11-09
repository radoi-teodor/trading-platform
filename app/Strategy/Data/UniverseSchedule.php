<?php

declare(strict_types=1);

namespace App\Strategy\Data;

use Carbon\CarbonImmutable;

/**
 * Who is in a universe, month by month (D-89): the members are fixed on the first day of a month, from what was
 * known before it, and hold until the next. A month the schedule does not have has no members.
 */
final readonly class UniverseSchedule
{
    /** @var array<string, array<string, true>> symbols by month (2026-10) */
    private array $months;

    /** @param array<string, list<string>> $months the members of each month, by 'Y-m' in UTC */
    public function __construct(array $months)
    {
        $this->months = array_map(fn (array $symbols): array => array_fill_keys($symbols, true), $months);
    }

    /** A schedule with nobody in it, for reading a strategy without running it. */
    public static function none(): self
    {
        return new self([]);
    }

    /** Whether $symbol is a member in the month of $time (UTC). */
    public function isMember(string $symbol, CarbonImmutable $time): bool
    {
        return isset($this->months[$time->utc()->format('Y-m')][$symbol]);
    }

    /** @return list<string> every symbol that is a member in some month, sorted */
    public function symbols(): array
    {
        $symbols = [];

        foreach ($this->months as $members) {
            $symbols += $members;
        }

        $symbols = array_map('strval', array_keys($symbols));
        sort($symbols, SORT_STRING);

        return $symbols;
    }
}
