<?php

declare(strict_types=1);

namespace App\Execution\Deployments;

use App\Strategy\Contracts\CrossSectionalStrategy;
use App\Strategy\Contracts\LimitedStrategy;
use App\Strategy\Data\StrategyContext;
use App\Strategy\Data\StrategyIdentity;
use App\Strategy\Data\StrategyLimits;

/**
 * A universe strategy at its deployment's step of the ladder (D-83, D-92): it decides as the strategy does, for all
 * its members at once, with the limits of SizedStrategy. Built by SizedStrategy::of.
 */
final readonly class SizedUniverseStrategy implements CrossSectionalStrategy, LimitedStrategy
{
    public function __construct(private CrossSectionalStrategy $strategy, private SizedStrategy $sized) {}

    public function identity(): StrategyIdentity
    {
        return $this->strategy->identity();
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

    public function onCloses(array $contexts): array
    {
        return $this->strategy->onCloses($contexts);
    }

    public function limits(): StrategyLimits
    {
        return $this->sized->limits();
    }
}
