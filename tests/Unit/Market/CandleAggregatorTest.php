<?php

declare(strict_types=1);

use App\Market\Data\Candle;
use App\Market\Feed\CandleAggregator;
use App\Market\Feed\CandleSequenceBroken;
use App\Support\Number\Decimal;
use App\Support\Timeframe;
use Tests\Support\CandleFactory;

mutates(CandleAggregator::class);

/**
 * @param  list<Candle>  $minutes
 * @return list<Candle>
 */
function aggregate(Timeframe $timeframe, array $minutes): array
{
    $aggregator = new CandleAggregator($timeframe);
    $closed = [];

    foreach ($minutes as $minute) {
        $candle = $aggregator->push($minute);

        if ($candle !== null) {
            $closed[] = $candle;
        }
    }

    return $closed;
}

it('emits a candle only when the last minute of its period closes', function (): void {
    $aggregator = new CandleAggregator(Timeframe::FiveMinutes);
    $minutes = CandleFactory::walk('2026-10-01 00:00:00', 10);
    $emitted = array_map(fn (Candle $minute): ?Candle => $aggregator->push($minute), $minutes);

    expect(array_keys(array_filter($emitted)))->toBe([4, 9]);
});

it('builds open, high, low, close and volumes from the minutes', function (): void {
    $minutes = CandleFactory::walk('2026-10-01 00:00:00', 60, seed: 11);
    [$hour] = aggregate(Timeframe::OneHour, $minutes);

    $high = $minutes[0]->high;
    $low = $minutes[0]->low;
    $volume = $quote = $taker = Decimal::zero();
    $trades = 0;

    foreach ($minutes as $minute) {
        $high = $minute->high->value->isGreaterThan($high->value) ? $minute->high : $high;
        $low = $minute->low->value->isLessThan($low->value) ? $minute->low : $low;
        $volume = $volume->plus($minute->volume);
        $quote = $quote->plus($minute->quoteVolume);
        $taker = $taker->plus($minute->takerBuyVolume);
        $trades += $minute->tradeCount;
    }

    expect($hour->timeframe)->toBe(Timeframe::OneHour)
        ->and($hour->openTime->toIso8601String())->toBe('2026-10-01T00:00:00+00:00')
        ->and((string) $hour->open)->toBe((string) $minutes[0]->open)
        ->and((string) $hour->close)->toBe((string) $minutes[59]->close)
        ->and((string) $hour->high)->toBe((string) $high)
        ->and((string) $hour->low)->toBe((string) $low)
        ->and((string) $hour->volume)->toBe((string) $volume)
        ->and((string) $hour->quoteVolume)->toBe((string) $quote)
        ->and((string) $hour->takerBuyVolume)->toBe((string) $taker)
        ->and($hour->tradeCount)->toBe($trades);
});

it('keeps the extremes of the minute where they happened', function (): void {
    $minutes = CandleFactory::walk('2026-10-01 00:00:00', 5, seed: 3);
    $peak = Candle::fromStrings($minutes[2]->openTime, Timeframe::OneMinute, '100', '500', '1', '100', '1', '100', 1, '0');
    $minutes[2] = $peak;
    [$candle] = aggregate(Timeframe::FiveMinutes, array_values($minutes));

    expect((string) $candle->high)->toBe('500')
        ->and((string) $candle->low)->toBe('1');
});

it('passes 1-minute candles through unchanged', function (): void {
    $minutes = CandleFactory::walk('2026-10-01 00:00:00', 3);

    expect(aggregate(Timeframe::OneMinute, $minutes))->toBe($minutes);
});

it('closes days at midnight UTC', function (): void {
    $minutes = CandleFactory::walk('2026-10-01 00:00:00', 2 * 1440, seed: 5);
    $days = aggregate(Timeframe::OneDay, $minutes);

    expect($days)->toHaveCount(2)
        ->and($days[1]->openTime->toIso8601String())->toBe('2026-10-02T00:00:00+00:00')
        ->and((string) $days[1]->close)->toBe((string) $minutes[2879]->close);
});

it('refuses a missing minute instead of building a partial candle', function (): void {
    $minutes = CandleFactory::walk('2026-10-01 00:00:00', 5);
    unset($minutes[2]);

    aggregate(Timeframe::FiveMinutes, array_values($minutes));
})->throws(CandleSequenceBroken::class, '2026-10-01 00:03');

it('refuses to start in the middle of a period', function (): void {
    $minutes = CandleFactory::walk('2026-10-01 00:02:00', 5);

    aggregate(Timeframe::FiveMinutes, $minutes);
})->throws(CandleSequenceBroken::class, 'starts inside');

it('refuses candles that are not 1-minute candles', function (): void {
    [$hour] = aggregate(Timeframe::OneHour, CandleFactory::walk('2026-10-01 00:00:00', 60));

    (new CandleAggregator(Timeframe::FourHours))->push($hour);
})->throws(CandleSequenceBroken::class, '1-minute');

it('refuses a minute that repeats or goes back in time', function (): void {
    $minutes = CandleFactory::walk('2026-10-01 00:00:00', 3);
    $aggregator = new CandleAggregator(Timeframe::FiveMinutes);
    $aggregator->push($minutes[0]);
    $aggregator->push($minutes[1]);

    $aggregator->push($minutes[1]);
})->throws(CandleSequenceBroken::class);

it('continues across periods without carrying values over', function (): void {
    $minutes = CandleFactory::walk('2026-10-01 00:00:00', 10, seed: 9);
    [$first, $second] = aggregate(Timeframe::FiveMinutes, $minutes);

    expect((string) $second->open)->toBe((string) $minutes[5]->open)
        ->and($second->tradeCount)->toBe(array_sum(array_map(fn (Candle $m): int => $m->tradeCount, array_slice($minutes, 5))))
        ->and($first->tradeCount)->toBe(array_sum(array_map(fn (Candle $m): int => $m->tradeCount, array_slice($minutes, 0, 5))));
});

/**
 * @param  list<Candle>  $minutes
 * @return list<string> the open times of the candles a restarting aggregator emits
 */
function aggregateRestarting(Timeframe $timeframe, array $minutes): array
{
    $aggregator = new CandleAggregator($timeframe, restarts: true);
    $closed = [];

    foreach ($minutes as $minute) {
        $candle = $aggregator->push($minute);

        if ($candle !== null) {
            $closed[] = $candle->openTime->format('H:i');
        }
    }

    return $closed;
}

it('drops the period a missing minute broke and starts again at the next one, when told to restart (D-92)', function (): void {
    $minutes = CandleFactory::walk('2026-10-01 00:00:00', 15);
    unset($minutes[7]);

    expect(aggregateRestarting(Timeframe::FiveMinutes, array_values($minutes)))->toBe(['00:00', '00:10']);
});

it('waits for a period to open when a restarting aggregator starts inside one (D-92)', function (): void {
    expect(aggregateRestarting(Timeframe::FiveMinutes, CandleFactory::walk('2026-10-01 00:02:00', 13)))->toBe(['00:05', '00:10']);
});

it('builds the same candles as a strict aggregator when no minute is missing (D-92)', function (): void {
    $minutes = CandleFactory::walk('2026-10-01 00:00:00', 10);
    $strict = aggregate(Timeframe::FiveMinutes, $minutes);
    $restarting = new CandleAggregator(Timeframe::FiveMinutes, restarts: true);
    $closed = array_values(array_filter(array_map(fn (Candle $minute): ?Candle => $restarting->push($minute), $minutes)));

    expect($closed)->toHaveCount(2)
        ->and($closed[0]->sameValuesAs($strict[0]))->toBeTrue()
        ->and($closed[1]->sameValuesAs($strict[1]))->toBeTrue();
});

it('still refuses a minute that repeats when it restarts (D-92)', function (): void {
    $minutes = CandleFactory::walk('2026-10-01 00:00:00', 3);
    $aggregator = new CandleAggregator(Timeframe::FiveMinutes, restarts: true);
    $aggregator->push($minutes[0]);
    $aggregator->push($minutes[1]);

    $aggregator->push($minutes[1]);
})->throws(CandleSequenceBroken::class);
