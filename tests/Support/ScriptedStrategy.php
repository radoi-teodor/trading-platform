<?php

declare(strict_types=1);

namespace Tests\Support;

use App\Strategy\Contracts\Strategy;
use App\Strategy\Data\Intent;
use App\Strategy\Data\StrategyContext;
use App\Strategy\Data\StrategyIdentity;
use App\Strategy\Data\Subscription;
use App\Support\Symbol;
use App\Support\Timeframe;

/** Emits the intents scripted for each decision time (H:i) and remembers what it saw. */
final class ScriptedStrategy implements Strategy
{
    /** @var list<StrategyContext> */
    public array $seen = [];

    /** @param array<string, list<Intent>> $script */
    public function __construct(
        private readonly array $script,
        private readonly Timeframe $timeframe = Timeframe::OneMinute,
        private readonly string $name = 'scripted',
    ) {}

    public function identity(): StrategyIdentity
    {
        return new StrategyIdentity($this->name, 1, []);
    }

    public function subscriptions(): array
    {
        return [new Subscription(Symbol::parse('BTC/USDT'), $this->timeframe)];
    }

    public function warmupBars(): int
    {
        return 0;
    }

    public function onCandle(StrategyContext $context): array
    {
        $this->seen[] = $context;

        return $this->script[$context->time->format('H:i')] ?? [];
    }
}
