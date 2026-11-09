<?php

declare(strict_types=1);

namespace Tests\Support;

use App\Strategy\Contracts\BuildableStrategy;
use App\Strategy\Contracts\Strategy;
use App\Strategy\Data\OpenLong;
use App\Strategy\Data\StrategyContext;
use App\Strategy\Data\StrategyIdentity;
use App\Strategy\Data\Subscription;
use App\Support\Symbol;
use App\Support\Timeframe;

/** Buys at its first decision and holds; its warmup is a parameter so tests can make history fall short. */
final class HoldStrategy implements BuildableStrategy
{
    /** @param array<string, string> $parameters */
    private function __construct(private readonly array $parameters, private readonly Symbol $symbol) {}

    public static function defaults(): array
    {
        return ['warmup_days' => '0'];
    }

    public static function build(array $parameters, array $symbols): Strategy
    {
        return new self($parameters, $symbols[0]);
    }

    public function identity(): StrategyIdentity
    {
        return new StrategyIdentity('hold', 1, $this->parameters);
    }

    public function subscriptions(): array
    {
        return [new Subscription($this->symbol, Timeframe::OneDay)];
    }

    public function warmupBars(): int
    {
        return (int) $this->parameters['warmup_days'];
    }

    public function onCandle(StrategyContext $context): array
    {
        return $context->position === null ? [new OpenLong($this->symbol, null, null, 'hold')] : [];
    }
}
