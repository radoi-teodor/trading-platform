<?php

declare(strict_types=1);

namespace App\Risk\Breakers;

use App\Market\Data\Candle;
use App\Market\Data\MinuteCandles;
use App\Support\Number\Decimal;
use Carbon\CarbonImmutable;

/**
 * The breakers of docs/risk-policy.md, section 3, that the minutes themselves can trip, per instrument:
 * price sanity (a close more than 5 % from the median close of the 15 minutes before it rejects entries on the
 * instrument while it lasts) and volatility shock (a minute whose true range is above 5 times the mean true range of
 * the hour before it pauses entries on the instrument for 15 minutes).
 *
 * It is asked at every minute of every instrument, so it keeps the closes in order of size and the sum of the ranges
 * as it goes, where sorting and summing them each time would say the same and cost a run over many instruments
 * half its time (tests/Support/ReferenceMarketBreakers.php is that plain statement).
 */
final class MarketBreakers
{
    public const string PRICE_BAND = '0.05';

    public const int MEDIAN_MINUTES = 15;

    public const int SHOCK_MULTIPLE = 5;

    public const int ATR_MINUTES = 60;

    public const int SHOCK_PAUSE_MINUTES = 15;

    /** @var array<string, list<Decimal>> the closes of the last 15 minutes, the oldest first */
    private array $closes = [];

    /** @var array<string, list<Decimal>> the same closes, the smallest first */
    private array $sorted = [];

    /** @var array<string, list<Decimal>> the true ranges of the last 60 minutes, the oldest first */
    private array $ranges = [];

    /** @var array<string, Decimal> the sum of those ranges */
    private array $rangeSums = [];

    /** @var array<string, bool> */
    private array $insane = [];

    /** @var array<string, CarbonImmutable> */
    private array $shockUntil = [];

    private readonly Decimal $band;

    private readonly Decimal $shockMultiple;

    private readonly Decimal $atrMinutes;

    public function __construct()
    {
        $this->band = Decimal::of(self::PRICE_BAND);
        $this->shockMultiple = Decimal::of(self::SHOCK_MULTIPLE);
        $this->atrMinutes = Decimal::of(self::ATR_MINUTES);
    }

    /** @return list<BreakerEvent> what tripped or cleared at this minute */
    public function observe(MinuteCandles $event): array
    {
        $events = [];

        foreach ($event->candles as $symbol => $candle) {
            $sanity = $this->priceSanity($symbol, $candle, $event->closedAt);

            if ($sanity !== null) {
                $events[] = $sanity;
            }

            foreach ($this->shock($symbol, $candle, $event->closedAt) as $shock) {
                $events[] = $shock;
            }
        }

        return $events;
    }

    /** Why entries on $symbol are blocked at $time, or null. */
    public function block(string $symbol, CarbonImmutable $time): ?string
    {
        return match (true) {
            $this->insane[$symbol] ?? false => 'price_sanity',
            isset($this->shockUntil[$symbol]) && $time < $this->shockUntil[$symbol] => 'volatility_shock',
            default => null,
        };
    }

    private function priceSanity(string $symbol, Candle $candle, CarbonImmutable $time): ?BreakerEvent
    {
        $close = $candle->close->value;
        $closes = $this->closes[$symbol] ?? [];
        $sorted = $this->sorted[$symbol] ?? [];
        $median = count($closes) === self::MEDIAN_MINUTES ? $sorted[intdiv(self::MEDIAN_MINUTES, 2)] : null;

        if ($median !== null) {
            array_splice($sorted, self::place($sorted, $closes[0]), 1);
            array_shift($closes);
        }

        array_splice($sorted, self::place($sorted, $close), 0, [$close]);
        $closes[] = $close;
        $this->closes[$symbol] = $closes;
        $this->sorted[$symbol] = $sorted;

        if ($median === null) {
            return null;
        }

        $insane = $close->minus($median)->abs()->dividedBy($median, 18)->isGreaterThan($this->band);
        $was = $this->insane[$symbol] ?? false;
        $this->insane[$symbol] = $insane;

        return $insane === $was ? null : new BreakerEvent('price_sanity', $symbol, $insane, $time, "close {$candle->close} against a median of {$median}");
    }

    /**
     * Where $value stands among $sorted: the first place whose number is not below it. A number that is in the list
     * is found there, or one equal to it.
     *
     * @param  list<Decimal>  $sorted  the smallest first
     */
    private static function place(array $sorted, Decimal $value): int
    {
        $low = 0;
        $high = count($sorted);

        while ($low < $high) {
            $middle = intdiv($low + $high, 2);

            if ($sorted[$middle]->compare($value) < 0) {
                $low = $middle + 1;
            } else {
                $high = $middle;
            }
        }

        return $low;
    }

    /** @return list<BreakerEvent> */
    private function shock(string $symbol, Candle $candle, CarbonImmutable $time): array
    {
        $closes = $this->closes[$symbol];
        $previous = $closes[count($closes) - 2] ?? null;
        $high = $candle->high->value;
        $low = $candle->low->value;
        $range = $high->minus($low);

        if ($previous !== null) {
            $above = $high->minus($previous)->abs();
            $below = $low->minus($previous)->abs();
            $range = $above->isGreaterThan($range) ? $above : $range;
            $range = $below->isGreaterThan($range) ? $below : $range;
        }

        $ranges = $this->ranges[$symbol] ?? [];
        $before = $this->rangeSums[$symbol] ?? Decimal::zero();
        $full = count($ranges) === self::ATR_MINUTES;
        $sum = $before->plus($range);

        if ($full) {
            $sum = $sum->minus($ranges[0]);
            array_shift($ranges);
        }

        $ranges[] = $range;
        $this->ranges[$symbol] = $ranges;
        $this->rangeSums[$symbol] = $sum;
        $events = [];

        if (isset($this->shockUntil[$symbol]) && ! ($time < $this->shockUntil[$symbol])) {
            unset($this->shockUntil[$symbol]);
            $events[] = new BreakerEvent('volatility_shock', $symbol, false, $time, 'the pause is over');
        }

        if (! $full) {
            return $events;
        }

        $mean = $before->dividedBy($this->atrMinutes, 18);

        if ($mean->isPositive() && $range->isGreaterThan($mean->times($this->shockMultiple))) {
            $this->shockUntil[$symbol] = $time->addMinutes(self::SHOCK_PAUSE_MINUTES);
            $events[] = new BreakerEvent('volatility_shock', $symbol, true, $time, "a range of {$range} against a mean of {$mean->roundedTo(8)}");
        }

        return $events;
    }
}
