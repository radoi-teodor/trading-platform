<?php

declare(strict_types=1);

namespace App\Strategy\Baselines;

use App\Strategy\Contracts\BuildableStrategy;
use App\Strategy\Contracts\Strategy;
use App\Strategy\Data\Close;
use App\Strategy\Data\OpenLong;
use App\Strategy\Data\StrategyContext;
use App\Strategy\Data\StrategyIdentity;
use App\Strategy\Data\Subscription;
use App\Strategy\Indicators\Sma;
use App\Support\Symbol;
use App\Support\Timeframe;
use InvalidArgumentException;

/**
 * Long while the fast simple average of closes is above the slow one, flat otherwise: the trend baseline
 * (docs/simulation.md, section 11, 50/200 on daily candles by default). No stop, so it runs with the benchmark
 * profile (D-40).
 */
final class MovingAverageTrend implements BuildableStrategy
{
    private readonly Sma $fast;

    private readonly Sma $slow;

    /** @param array<string, string> $parameters */
    private function __construct(private readonly array $parameters, private readonly Symbol $symbol, private readonly Timeframe $timeframe)
    {
        $fast = Parameters::count($parameters, 'fast');
        $slow = Parameters::count($parameters, 'slow');

        if ($fast >= $slow) {
            throw new InvalidArgumentException("The fast average must be shorter than the slow one, got {$fast} and {$slow}.");
        }

        $this->fast = new Sma($fast);
        $this->slow = new Sma($slow);
    }

    public static function defaults(): array
    {
        return ['fast' => '50', 'slow' => '200', 'timeframe' => '1d'];
    }

    public static function build(array $parameters, array $symbols): Strategy
    {
        return new self($parameters, $symbols[0], Parameters::timeframe($parameters, 'timeframe'));
    }

    public function identity(): StrategyIdentity
    {
        return new StrategyIdentity('ma-trend', 1, $this->parameters);
    }

    public function subscriptions(): array
    {
        return [new Subscription($this->symbol, $this->timeframe)];
    }

    public function warmupBars(): int
    {
        return $this->slow->period;
    }

    public function onCandle(StrategyContext $context): array
    {
        $close = $context->candle->close->value;
        $fast = $this->fast->push($close);
        $slow = $this->slow->push($close);

        if ($fast === null || $slow === null) {
            return [];
        }

        if ($fast->isGreaterThan($slow)) {
            return $context->position === null ? [new OpenLong($this->symbol, null, null, 'fast average above slow')] : [];
        }

        return $context->position === null ? [] : [new Close($this->symbol, 'fast average at or below slow')];
    }
}
