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
 */
final class MarketBreakers
{
    public const string PRICE_BAND = '0.05';

    public const int MEDIAN_MINUTES = 15;

    public const int SHOCK_MULTIPLE = 5;

    public const int ATR_MINUTES = 60;

    public const int SHOCK_PAUSE_MINUTES = 15;

    /** @var array<string, list<Decimal>> */
    private array $closes = [];

    /** @var array<string, list<Decimal>> */
    private array $ranges = [];

    /** @var array<string, bool> */
    private array $insane = [];

    /** @var array<string, CarbonImmutable> */
    private array $shockUntil = [];

    /** @return list<BreakerEvent> what tripped or cleared at this minute */
    public function observe(MinuteCandles $event): array
    {
        $events = [];

        foreach ($event->candles as $symbol => $candle) {
            $events = [...$events, ...$this->priceSanity($symbol, $candle, $event->closedAt), ...$this->shock($symbol, $candle, $event->closedAt)];
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

    /** @return list<BreakerEvent> */
    private function priceSanity(string $symbol, Candle $candle, CarbonImmutable $time): array
    {
        $before = $this->closes[$symbol] ?? [];
        $this->closes[$symbol] = array_slice([...$before, $candle->close->value], -self::MEDIAN_MINUTES);

        if (count($before) < self::MEDIAN_MINUTES) {
            return [];
        }

        usort($before, fn (Decimal $a, Decimal $b): int => $a->compare($b));
        $median = $before[intdiv(self::MEDIAN_MINUTES, 2)];
        $away = $candle->close->value->minus($median)->abs()->dividedBy($median, 18);
        $insane = $away->isGreaterThan(Decimal::of(self::PRICE_BAND));
        $was = $this->insane[$symbol] ?? false;
        $this->insane[$symbol] = $insane;

        return $insane === $was ? [] : [new BreakerEvent('price_sanity', $symbol, $insane, $time, "close {$candle->close} against a median of {$median}")];
    }

    /** @return list<BreakerEvent> */
    private function shock(string $symbol, Candle $candle, CarbonImmutable $time): array
    {
        $previous = $this->closes[$symbol][count($this->closes[$symbol]) - 2] ?? null;
        $high = $candle->high->value;
        $low = $candle->low->value;
        $max = fn (Decimal $a, Decimal $b): Decimal => $a->isGreaterThan($b) ? $a : $b;
        $range = $previous === null ? $high->minus($low) : $max($max($high->minus($low), $high->minus($previous)->abs()), $low->minus($previous)->abs());
        $before = $this->ranges[$symbol] ?? [];
        $this->ranges[$symbol] = array_slice([...$before, $range], -self::ATR_MINUTES);
        $events = [];

        if (isset($this->shockUntil[$symbol]) && ! ($time < $this->shockUntil[$symbol])) {
            unset($this->shockUntil[$symbol]);
            $events[] = new BreakerEvent('volatility_shock', $symbol, false, $time, 'the pause is over');
        }

        if (count($before) < self::ATR_MINUTES) {
            return $events;
        }

        $mean = array_reduce($before, fn (Decimal $sum, Decimal $r): Decimal => $sum->plus($r), Decimal::zero())->dividedBy(Decimal::of(self::ATR_MINUTES), 18);

        if ($mean->isPositive() && $range->isGreaterThan($mean->times(Decimal::of(self::SHOCK_MULTIPLE)))) {
            $this->shockUntil[$symbol] = $time->addMinutes(self::SHOCK_PAUSE_MINUTES);
            $events[] = new BreakerEvent('volatility_shock', $symbol, true, $time, "a range of {$range} against a mean of {$mean->roundedTo(8)}");
        }

        return $events;
    }
}
