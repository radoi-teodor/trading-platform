<?php

declare(strict_types=1);

namespace App\Strategy\Dsl\Compiler;

use App\Strategy\Dsl\Ast\Call;
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

    /** @var array<string, int> the slot of each ranked source, by its text */
    private array $slots = [];

    /** @var list<Closure(Frame): mixed> the sources a universe strategy ranks its members by, by slot */
    private array $sources = [];

    /** @var array<int, int> the slot each ranking reads, by the ranking's object id */
    private array $rankings = [];

    /**
     * The slot of the source $ranking ranks by (D-92). Two rankings of the same source share one, so the source is
     * worked out once per candle; every instrument's program numbers its slots alike.
     *
     * @param  Closure(): (Closure(Frame): mixed)  $compile
     */
    public function slot(Call $ranking, string $source, Closure $compile): int
    {
        if (! isset($this->slots[$source])) {
            $compiled = $compile();
            $this->sources[] = $compiled;
            $this->slots[$source] = count($this->sources) - 1;
        }

        return $this->rankings[spl_object_id($ranking)] = $this->slots[$source];
    }

    /** The slot $ranking reads, or null when this program does not have it. */
    public function slotOf(Call $ranking): ?int
    {
        return $this->rankings[spl_object_id($ranking)] ?? null;
    }

    /** @return list<mixed> every ranked source's value on the strategy's candle that just closed, by slot */
    public function collect(Frame $frame): array
    {
        return array_map(fn (Closure $source): mixed => $source($frame), $this->sources);
    }

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
