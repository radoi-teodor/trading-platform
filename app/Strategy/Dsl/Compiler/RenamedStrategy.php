<?php

declare(strict_types=1);

namespace App\Strategy\Dsl\Compiler;

use App\Strategy\Contracts\Strategy;
use App\Strategy\Data\StrategyContext;
use App\Strategy\Data\StrategyIdentity;

/** A coded template (funding_carry) under the identity of the document that configured it (D-56). */
final readonly class RenamedStrategy implements Strategy
{
    public function __construct(private Strategy $strategy, private StrategyIdentity $identity) {}

    public function identity(): StrategyIdentity
    {
        return $this->identity;
    }

    public function subscriptions(): array
    {
        return $this->strategy->subscriptions();
    }

    public function warmupBars(): int
    {
        return $this->strategy->warmupBars();
    }

    public function onCandle(StrategyContext $context): array
    {
        return $this->strategy->onCandle($context);
    }
}
