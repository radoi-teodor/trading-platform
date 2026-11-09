<?php

declare(strict_types=1);

namespace App\Strategy\Indicators;

use App\Market\Data\Candle;
use App\Support\Number\Decimal;

/** The highest high and lowest low of the last n bars pushed. Read it before pushing a bar to get the channel before it. */
final class Donchian
{
    /** @var list<Candle> */
    private array $window = [];

    public function __construct(public readonly int $period)
    {
        Period::check($period);
    }

    public function push(Candle $bar): void
    {
        $this->window[] = $bar;

        if (count($this->window) > $this->period) {
            array_shift($this->window);
        }
    }

    public function upper(): ?Decimal
    {
        return $this->extreme(fn (Candle $bar): Decimal => $bar->high->value, 1);
    }

    public function lower(): ?Decimal
    {
        return $this->extreme(fn (Candle $bar): Decimal => $bar->low->value, -1);
    }

    /** @param callable(Candle): Decimal $price */
    private function extreme(callable $price, int $direction): ?Decimal
    {
        if (count($this->window) < $this->period) {
            return null;
        }

        $extreme = null;

        foreach ($this->window as $bar) {
            $value = $price($bar);
            $extreme = $extreme === null || $value->compare($extreme) === $direction ? $value : $extreme;
        }

        return $extreme;
    }
}
