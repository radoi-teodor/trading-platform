<?php

declare(strict_types=1);

namespace Tests\Support;

use App\Strategy\Contracts\CrossSectionalStrategy;
use App\Strategy\Data\Intent;
use App\Strategy\Data\StrategyContext;
use App\Strategy\Data\StrategyIdentity;
use App\Strategy\Data\Subscription;
use App\Support\Symbol;
use App\Support\Timeframe;
use LogicException;

/** A strategy over several instruments at once: emits the intents scripted for each moment (H:i) and keeps what it was given. */
final class ScriptedUniverseStrategy implements CrossSectionalStrategy
{
    /** @var list<list<StrategyContext>> one entry per call */
    public array $calls = [];

    /**
     * @param  array<string, list<Intent>>  $script
     * @param  list<Symbol>  $symbols
     */
    public function __construct(
        private readonly array $script,
        private readonly array $symbols,
        private readonly Timeframe $timeframe = Timeframe::OneMinute,
        private readonly string $name = 'universe',
    ) {}

    public function identity(): StrategyIdentity
    {
        return new StrategyIdentity($this->name, 1, []);
    }

    public function subscriptions(): array
    {
        return array_map(fn (Symbol $symbol): Subscription => new Subscription($symbol, $this->timeframe), $this->symbols);
    }

    public function warmupBars(): int
    {
        return 0;
    }

    public function onCandle(StrategyContext $context): array
    {
        throw new LogicException('A universe strategy is decided through onCloses.');
    }

    public function onCloses(array $contexts): array
    {
        $this->calls[] = $contexts;

        return $this->script[$contexts[0]->time->format('H:i')] ?? [];
    }
}
