<?php

declare(strict_types=1);

namespace App\Market\Data;

use App\Support\Symbol;
use Carbon\CarbonImmutable;
use InvalidArgumentException;

/**
 * The filters of one instrument over time, so that an order at a past moment is checked against the filters of
 * that moment (docs/simulation.md, section 2). Before the first known row the oldest row is used, and
 * isAssumedAt() says so, for the report to state it (docs/engine.md, section 4).
 */
final readonly class RulesHistory
{
    /** @var non-empty-list<InstrumentRules> oldest first */
    private array $rules;

    /** @param list<InstrumentRules> $rules */
    public function __construct(public Symbol $symbol, array $rules)
    {
        if ($rules === []) {
            throw new InvalidArgumentException("No exchange filters are known for {$symbol}.");
        }

        usort($rules, fn (InstrumentRules $a, InstrumentRules $b): int => $a->validFrom <=> $b->validFrom);
        $this->rules = $rules;
    }

    public function at(CarbonImmutable $moment): InstrumentRules
    {
        $inForce = $this->rules[0];

        foreach ($this->rules as $rules) {
            if ($rules->validFrom > $moment) {
                break;
            }

            $inForce = $rules;
        }

        return $inForce;
    }

    public function isAssumedAt(CarbonImmutable $moment): bool
    {
        return $moment < $this->knownFrom();
    }

    public function knownFrom(): CarbonImmutable
    {
        return $this->rules[0]->validFrom;
    }
}
