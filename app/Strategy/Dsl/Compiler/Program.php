<?php

declare(strict_types=1);

namespace App\Strategy\Dsl\Compiler;

use Closure;

/**
 * The compiled expressions of one strategy on one instrument. Every expression is evaluated once per closed candle
 * of its timeframe, whether or not its value is used, so that indicators stay in step: expressions under `on` are
 * updated when their own timeframe's candle closes, before the strategy's own (D-53).
 */
final class Program
{
    /** @var array<string, list<Closure(Frame): void>> updaters by timeframe */
    private array $updaters = [];

    /** @var array<string, Closure(Frame): mixed> the strategy timeframe's named expressions */
    private array $expressions = [];

    /** @param Closure(Frame): void $updater */
    public function onTimeframe(string $timeframe, Closure $updater): void
    {
        $this->updaters[$timeframe][] = $updater;
    }

    /** @param Closure(Frame): mixed $expression */
    public function name(string $name, Closure $expression): void
    {
        $this->expressions[$name] = $expression;
    }

    /** Updates the expressions of another timeframe when one of its candles closes. */
    public function update(string $timeframe, Frame $frame): void
    {
        foreach ($this->updaters[$timeframe] ?? [] as $updater) {
            $updater($frame);
        }
    }

    /** @return array<string, mixed> every named expression's value on the strategy's candle that just closed */
    public function evaluate(Frame $frame): array
    {
        $values = [];

        foreach ($this->expressions as $name => $expression) {
            $values[$name] = $expression($frame);
        }

        return $values;
    }

    /** @return list<string> the other timeframes this program follows */
    public function timeframes(): array
    {
        return array_keys($this->updaters);
    }
}
