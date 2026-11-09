<?php

declare(strict_types=1);

namespace App\Strategy\Indicators;

use App\Market\Data\Candle;
use App\Support\Number\Decimal;
use InvalidArgumentException;

/**
 * The volume profile of the last n candles (D-72): 24 bins between their lowest low and highest high, each candle's
 * volume spread evenly over the bins its range covers. The point of control is the middle of the fullest bin (the
 * lower on a tie); the value area grows from it one bin at a time towards the larger neighbour (the lower on a tie)
 * until it holds 70 % of the volume, and its high and low are the edges of the bins it holds.
 */
final class VolumeProfile implements BarIndicator
{
    public const int BINS = 24;

    private const int SCALE = 18;

    /** @var list<Candle> */
    private array $window = [];

    public function __construct(public readonly int $period)
    {
        if ($period < 1) {
            throw new InvalidArgumentException("A volume profile needs at least one candle, got {$period}.");
        }
    }

    /** @return array{poc: ?Decimal, vah: ?Decimal, val: ?Decimal} */
    public function push(Candle $bar): array
    {
        $this->window[] = $bar;

        if (count($this->window) > $this->period) {
            array_shift($this->window);
        }

        if (count($this->window) < $this->period) {
            return ['poc' => null, 'vah' => null, 'val' => null];
        }

        $lo = $this->window[0]->low->value;
        $hi = $this->window[0]->high->value;

        foreach ($this->window as $candle) {
            $lo = $candle->low->value->isLessThan($lo) ? $candle->low->value : $lo;
            $hi = $candle->high->value->isGreaterThan($hi) ? $candle->high->value : $hi;
        }

        if ($hi->equals($lo)) {
            return ['poc' => $lo, 'vah' => $lo, 'val' => $lo];
        }

        $width = $hi->minus($lo)->dividedBy(Decimal::of(self::BINS), self::SCALE);
        $volumes = array_fill(0, self::BINS, Decimal::zero());

        foreach ($this->window as $candle) {
            $first = $this->bin($candle->low->value, $lo, $width);
            $last = $this->bin($candle->high->value, $lo, $width);
            $part = $candle->volume->dividedBy(Decimal::of($last - $first + 1), self::SCALE);

            for ($j = $first; $j <= $last; $j++) {
                $volumes[$j] = $volumes[$j]->plus($part);
            }
        }

        $poc = 0;
        $total = Decimal::zero();

        foreach ($volumes as $j => $volume) {
            $poc = $volume->isGreaterThan($volumes[$poc]) ? $j : $poc;
            $total = $total->plus($volume);
        }

        $target = $total->times(Decimal::of('0.7'));
        [$held, $low, $high] = [$volumes[$poc], $poc, $poc];

        while ($held->isLessThan($target) && ($low > 0 || $high < self::BINS - 1)) {
            $below = $low > 0 ? $volumes[$low - 1] : null;
            $above = $high < self::BINS - 1 ? $volumes[$high + 1] : null;

            if ($above === null || ($below !== null && ! $below->isLessThan($above))) {
                $low--;
                $held = $held->plus($below);
            } else {
                $high++;
                $held = $held->plus($above);
            }
        }

        return [
            'poc' => $lo->plus($width->times(Decimal::of($poc))->plus($width->dividedBy(Decimal::of(2), self::SCALE))),
            'vah' => $lo->plus($width->times(Decimal::of($high + 1))),
            'val' => $lo->plus($width->times(Decimal::of($low))),
        ];
    }

    private function bin(Decimal $price, Decimal $lo, Decimal $width): int
    {
        return min(self::BINS - 1, (int) (string) $price->minus($lo)->dividedBy($width, self::SCALE)->truncatedTo(0));
    }
}
