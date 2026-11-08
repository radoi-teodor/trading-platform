<?php

declare(strict_types=1);

namespace App\Strategy\Indicators;

use App\Market\Data\Candle;
use App\Support\Number\Decimal;

/**
 * Wilder's directional movement: +DI, -DI and ADX over n candles. From the second candle, +DM and -DM are the
 * larger of the up and down moves (zero otherwise) and TR the true range; each is summed over its first n values,
 * then smoothed as sum - sum / n + value. DI = 100 × DM / TR; DX = 100 × |+DI - -DI| / (+DI + -DI); ADX is
 * Wilder's average of DX.
 */
final class DirectionalMovement
{
    private ?Candle $previous = null;

    /** @var array{plus: list<Decimal>, minus: list<Decimal>, range: list<Decimal>} */
    private array $first = ['plus' => [], 'minus' => [], 'range' => []];

    /** @var array{plus: Decimal, minus: Decimal, range: Decimal}|null */
    private ?array $smoothed = null;

    private Wilder $adx;

    public function __construct(public readonly int $period)
    {
        $this->adx = new Wilder($period);
    }

    /** @return array{plus_di: ?Decimal, minus_di: ?Decimal, adx: ?Decimal} */
    public function push(Candle $bar): array
    {
        $previous = $this->previous;
        $this->previous = $bar;
        $none = ['plus_di' => null, 'minus_di' => null, 'adx' => null];

        if ($previous === null) {
            return $none;
        }

        $up = $bar->high->value->minus($previous->high->value);
        $down = $previous->low->value->minus($bar->low->value);
        $moves = [
            'plus' => $up->isGreaterThan($down) && $up->isPositive() ? $up : Decimal::zero(),
            'minus' => $down->isGreaterThan($up) && $down->isPositive() ? $down : Decimal::zero(),
            'range' => self::trueRange($bar, $previous),
        ];

        if ($this->smoothed === null) {
            foreach ($moves as $key => $value) {
                $this->first[$key][] = $value;
            }

            if (count($this->first['range']) < $this->period) {
                return $none;
            }

            $this->smoothed = ['plus' => self::sum($this->first['plus']), 'minus' => self::sum($this->first['minus']), 'range' => self::sum($this->first['range'])];
        } else {
            $n = Decimal::of($this->period);

            foreach ($moves as $key => $value) {
                $this->smoothed[$key] = $this->smoothed[$key]->minus($this->smoothed[$key]->dividedBy($n, 18))->plus($value);
            }
        }

        $range = $this->smoothed['range'];
        $hundred = Decimal::of(100);
        $plus = $range->isZero() ? Decimal::zero() : $hundred->times($this->smoothed['plus'])->dividedBy($range, 18);
        $minus = $range->isZero() ? Decimal::zero() : $hundred->times($this->smoothed['minus'])->dividedBy($range, 18);
        $total = $plus->plus($minus);
        $dx = $total->isZero() ? Decimal::zero() : $hundred->times($plus->minus($minus)->abs())->dividedBy($total, 18);

        return ['plus_di' => $plus, 'minus_di' => $minus, 'adx' => $this->adx->push($dx)];
    }

    private static function trueRange(Candle $bar, Candle $previous): Decimal
    {
        $range = $bar->high->value->minus($bar->low->value);

        foreach ([$bar->high->value->minus($previous->close->value)->abs(), $bar->low->value->minus($previous->close->value)->abs()] as $gap) {
            $range = $gap->isGreaterThan($range) ? $gap : $range;
        }

        return $range;
    }

    /** @param list<Decimal> $values */
    private static function sum(array $values): Decimal
    {
        $sum = Decimal::zero();

        foreach ($values as $value) {
            $sum = $sum->plus($value);
        }

        return $sum;
    }
}
