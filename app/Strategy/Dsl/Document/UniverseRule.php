<?php

declare(strict_types=1);

namespace App\Strategy\Dsl\Document;

/**
 * What a universe strategy trades (D-92): not a list of instruments but a rule, such as the top 50 USDT perpetuals
 * by 30-day quote volume. The rule is part of the strategy's version; who the members are at a moment comes from
 * universe_members (D-89).
 */
final readonly class UniverseRule
{
    public function __construct(public string $rule, public int $n, public string $by) {}

    /** The rule as the document writes it. */
    public function text(): string
    {
        return "{ rule: {$this->rule}, n: {$this->n}, by: {$this->by} }";
    }
}
