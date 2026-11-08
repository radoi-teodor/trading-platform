<?php

declare(strict_types=1);

use App\Market\Data\Candle;
use App\Simulation\Validation\RegimeLabels;
use App\Support\Number\Decimal;
use App\Support\Timeframe;
use Carbon\CarbonImmutable;

mutates(RegimeLabels::class);

/**
 * Daily candles from closes: high and low 1 % around the close unless a range is given.
 *
 * @param  list<string>  $closes
 * @return list<Candle>
 */
function days(array $closes, string $spread = '0.01'): array
{
    return array_map(function (int $i, string $close) use ($spread): Candle {
        $c = Decimal::of($close);
        $band = $c->times(Decimal::of($spread));

        return Candle::fromStrings(CarbonImmutable::parse('2026-01-01', 'UTC')->addDays($i), Timeframe::OneDay, $close, (string) $c->plus($band), (string) $c->minus($band), $close, '1', '1', 1, '0');
    }, array_keys($closes), $closes);
}

it('labels a steady rise as trend once ADX is warm, and the days before as unknown', function (): void {
    $closes = array_map(fn (int $i): string => (string) (100 + 2 * $i), range(0, 59));
    $labels = RegimeLabels::fromDailyCandles(days($closes));

    expect($labels->at(CarbonImmutable::parse('2025-12-31', 'UTC')))->toBe('unknown')
        // ADX(14) needs 14 moves for the first DI and 14 more to smooth: day 28 closes on 2026-01-29 at 00:00
        ->and($labels->at(CarbonImmutable::parse('2026-01-20', 'UTC')))->toBe('unknown')
        ->and($labels->at(CarbonImmutable::parse('2026-02-28', 'UTC')))->toBe('trend');
});

it('labels a flat market as range and a volatility spike as high volatility, which wins', function (): void {
    // 80 flat days that alternate up and down, then 10 days of 15 % swings
    $flat = array_map(fn (int $i): string => $i % 2 === 0 ? '100' : '101', range(0, 79));
    $wild = array_map(fn (int $i): string => $i % 2 === 0 ? '85' : '115', range(0, 9));
    $labels = RegimeLabels::fromDailyCandles(days([...$flat, ...$wild]));

    expect($labels->at(CarbonImmutable::parse('2026-03-15', 'UTC')))->toBe('range')
        ->and($labels->at(CarbonImmutable::parse('2026-03-31', 'UTC')))->toBe('high_volatility');
});

it('splits trades by the regime at their entry', function (): void {
    $closes = array_map(fn (int $i): string => (string) (100 + 2 * $i), range(0, 59));
    $labels = RegimeLabels::fromDailyCandles(days($closes));
    $breakdown = $labels->breakdown([
        ['opened' => CarbonImmutable::parse('2026-01-05', 'UTC'), 'net' => Decimal::of('-5')],
        ['opened' => CarbonImmutable::parse('2026-02-20', 'UTC'), 'net' => Decimal::of('10')],
        ['opened' => CarbonImmutable::parse('2026-02-21', 'UTC'), 'net' => Decimal::of('20')],
    ]);

    expect($breakdown['trend']['trades'])->toBe(2)
        ->and((string) $breakdown['trend']['expectancy'])->toBe('15')
        ->and((string) $breakdown['unknown']['net'])->toBe('-5')
        ->and($breakdown['range'])->toEqual(['trades' => 0, 'net' => Decimal::zero(), 'expectancy' => null]);
});

it('reads daily candles only', function (): void {
    RegimeLabels::fromDailyCandles([Candle::fromStrings(CarbonImmutable::parse('2026-01-01', 'UTC'), Timeframe::OneHour, '1', '1', '1', '1', '1', '1', 1, '0')]);
})->throws(InvalidArgumentException::class, 'Regime labels are read from daily candles.');
