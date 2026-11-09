<?php

declare(strict_types=1);

namespace App\Execution\Deployments;

use App\Risk\RiskPolicy;
use App\Strategy\Contracts\CrossSectionalStrategy;
use App\Strategy\Contracts\LimitedStrategy;
use App\Strategy\Contracts\Strategy;
use App\Strategy\Data\StrategyContext;
use App\Strategy\Data\StrategyIdentity;
use App\Strategy\Data\StrategyLimits;
use App\Support\Number\Decimal;

/**
 * A deployment's strategy at its step of the ladder (D-83): risk per trade scaled by the size multiplier and, on live
 * micro, the notional capped. It only ever tightens: a strategy's own limit is scaled, never replaced by a looser
 * one. Same identity, so the ledger, the journal and the replay see the same strategy.
 */
final readonly class SizedStrategy implements LimitedStrategy
{
    public function __construct(private Strategy $strategy, private Decimal $multiplier, private ?Decimal $notionalCap, private RiskPolicy $policy) {}

    /** A strategy at its step of the ladder; one over a universe stays one over a universe (D-92). */
    public static function of(Strategy $strategy, Decimal $multiplier, ?Decimal $notionalCap, RiskPolicy $policy): LimitedStrategy
    {
        $sized = new self($strategy, $multiplier, $notionalCap, $policy);

        return $strategy instanceof CrossSectionalStrategy ? new SizedUniverseStrategy($strategy, $sized) : $sized;
    }

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

    public function limits(): StrategyLimits
    {
        $own = $this->strategy instanceof LimitedStrategy ? $this->strategy->limits() : new StrategyLimits;
        $risk = ($own->riskPerTrade ?? $this->policy->riskPerTrade)->times($this->multiplier);
        $position = $own->maxPosition;

        if ($this->notionalCap !== null) {
            $position = $position === null || $this->notionalCap->isLessThan($position) ? $this->notionalCap : $position;
        }

        return new StrategyLimits($risk, $position, $own->maxOpenPositions, $own->maxTradesPerDay, $own->cooldownSeconds);
    }
}
