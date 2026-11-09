<?php

declare(strict_types=1);

namespace App\Strategy\Baselines;

use App\Strategy\Contracts\BuildableStrategy;
use App\Strategy\Contracts\Strategy;
use App\Strategy\Data\OpenLong;
use App\Strategy\Data\StrategyContext;
use App\Strategy\Data\StrategyIdentity;
use App\Strategy\Data\Subscription;
use App\Support\Symbol;
use App\Support\Timeframe;

/** Buys at the first minute of the window and holds to the end: the first baseline (docs/simulation.md, section 11). */
final readonly class BuyAndHold implements BuildableStrategy
{
    private function __construct(private Symbol $symbol) {}

    public static function defaults(): array
    {
        return [];
    }

    public static function build(array $parameters, array $symbols): Strategy
    {
        return new self($symbols[0]);
    }

    public function identity(): StrategyIdentity
    {
        return new StrategyIdentity('buy-and-hold', 1, []);
    }

    public function subscriptions(): array
    {
        return [new Subscription($this->symbol, Timeframe::OneMinute)];
    }

    public function warmupBars(): int
    {
        return 0;
    }

    public function onCandle(StrategyContext $context): array
    {
        return $context->position === null ? [new OpenLong($this->symbol, null, null, 'buy and hold')] : [];
    }
}
