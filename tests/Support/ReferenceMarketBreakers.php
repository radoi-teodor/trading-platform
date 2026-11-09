<?php

declare(strict_types=1);

namespace Tests\Support;

use App\Market\Data\Candle;
use App\Market\Data\MinuteCandles;
use App\Risk\Breakers\BreakerEvent;
use App\Risk\Breakers\MarketBreakers;
use App\Support\Number\Decimal;
use Carbon\CarbonImmutable;

/**
 * The market breakers stated plainly: at every minute the 15 closes are sorted for their median and the 60 ranges
 * are summed for their mean. App\Risk\Breakers\MarketBreakers keeps both as it goes and must say the same.
 */
final class ReferenceMarketBreakers
{
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
        $this->closes[$symbol] = array_slice([...$before, $candle->close->value], -MarketBreakers::MEDIAN_MINUTES);

        if (count($before) < MarketBreakers::MEDIAN_MINUTES) {
            return [];
        }

        usort($before, fn (Decimal $a, Decimal $b): int => $a->compare($b));
        $median = $before[intdiv(MarketBreakers::MEDIAN_MINUTES, 2)];
        $away = $candle->close->value->minus($median)->abs()->dividedBy($median, 18);
        $insane = $away->isGreaterThan(Decimal::of(MarketBreakers::PRICE_BAND));
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
        $this->ranges[$symbol] = array_slice([...$before, $range], -MarketBreakers::ATR_MINUTES);
        $events = [];

        if (isset($this->shockUntil[$symbol]) && ! ($time < $this->shockUntil[$symbol])) {
            unset($this->shockUntil[$symbol]);
            $events[] = new BreakerEvent('volatility_shock', $symbol, false, $time, 'the pause is over');
        }

        if (count($before) < MarketBreakers::ATR_MINUTES) {
            return $events;
        }

        $mean = array_reduce($before, fn (Decimal $sum, Decimal $r): Decimal => $sum->plus($r), Decimal::zero())->dividedBy(Decimal::of(MarketBreakers::ATR_MINUTES), 18);

        if ($mean->isPositive() && $range->isGreaterThan($mean->times(Decimal::of(MarketBreakers::SHOCK_MULTIPLE)))) {
            $this->shockUntil[$symbol] = $time->addMinutes(MarketBreakers::SHOCK_PAUSE_MINUTES);
            $events[] = new BreakerEvent('volatility_shock', $symbol, true, $time, "a range of {$range} against a mean of {$mean->roundedTo(8)}");
        }

        return $events;
    }
}
