<?php

declare(strict_types=1);

namespace App\Strategy\Dsl\Compiler;

use App\Support\Number\Decimal;

/**
 * The members of a universe at one candle close, with the values the strategy ranks them by (D-92). A member is
 * entered once it belongs to the universe and has its history; a ranking then reads its place among the others.
 * The highest value is rank 1, equal values are ordered by member, and a member without a value is not ranked.
 */
final class CrossSection
{
    /** @var array<string, list<mixed>> the ranked values of each member, by slot */
    private array $members = [];

    /** @var array<int, array<string, int>> the rank of each member, by slot, worked out when first read */
    private array $ranks = [];

    /** Starts the next moment: nobody is entered. */
    public function begin(): void
    {
        $this->members = [];
        $this->ranks = [];
    }

    /** @param list<mixed> $values the member's value in each slot */
    public function enter(string $member, array $values): void
    {
        $this->members[$member] = $values;
        $this->ranks = [];
    }

    public function has(string $member): bool
    {
        return isset($this->members[$member]);
    }

    /** How many members are entered, ranked or not. */
    public function count(): int
    {
        return count($this->members);
    }

    /** The place of $member by the values of $slot, from 1 for the highest, or null when it has no value there. */
    public function rank(int $slot, string $member): ?int
    {
        return $this->ranks($slot)[$member] ?? null;
    }

    /** How many members have a value in $slot. */
    public function ranked(int $slot): int
    {
        return count($this->ranks($slot));
    }

    /** @return array<string, int> */
    private function ranks(int $slot): array
    {
        if (isset($this->ranks[$slot])) {
            return $this->ranks[$slot];
        }

        $values = [];

        foreach ($this->members as $member => $row) {
            $value = $row[$slot] ?? null;

            if ($value instanceof Decimal) {
                $values[(string) $member] = $value;
            }
        }

        $members = array_map('strval', array_keys($values));
        usort($members, function (string $a, string $b) use ($values): int {
            $order = $values[$b]->compare($values[$a]);

            return $order !== 0 ? $order : strcmp($a, $b);
        });

        $ranks = [];

        foreach ($members as $index => $member) {
            $ranks[$member] = $index + 1;
        }

        return $this->ranks[$slot] = $ranks;
    }
}
