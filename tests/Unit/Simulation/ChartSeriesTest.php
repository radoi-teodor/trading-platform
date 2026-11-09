<?php

declare(strict_types=1);

use App\Market\Data\Candle;
use App\Simulation\Charts\ChartSeries;
use App\Strategy\Dsl\DslError;
use App\Strategy\Indicators\Ema;
use App\Strategy\Indicators\VolumeProfile;
use App\Support\Number\Decimal;
use App\Support\Symbol;
use App\Support\Timeframe;
use Carbon\CarbonImmutable;
use Tests\Support\CandleFactory;
use Tests\Support\InMemoryCandleFeed;

mutates(ChartSeries::class);

/** @return list<Candle> 5-minute candles built from the same minutes, as the engine's aggregator closes them */
function fiveMinuteBars(): array
{
    $bars = [];
    $group = [];

    foreach (CandleFactory::walk('2026-10-01 00:00:00', 600, seed: 9) as $minute) {
        $group[] = $minute;

        if (count($group) === 5) {
            $bars[] = $group;
            $group = [];
        }
    }

    return array_map(fn (array $g): Candle => Candle::fromStrings(
        $g[0]->openTime, Timeframe::FiveMinutes, (string) $g[0]->open,
        (string) max(array_map(fn (Candle $c): string => (string) $c->high, $g)),
        (string) min(array_map(fn (Candle $c): string => (string) $c->low, $g)),
        (string) $g[4]->close, (string) array_reduce($g, fn (Decimal $sum, Candle $c): Decimal => $sum->plus($c->volume), Decimal::zero()), '1', 1, '0',
    ), $bars);
}

function chart(): ChartSeries
{
    return new ChartSeries(new InMemoryCandleFeed(['BTC/USDT' => CandleFactory::walk('2026-10-01 00:00:00', 600, seed: 9)]));
}

it('evaluates DSL expressions on candles of any timeframe with the backtest\'s formulas, after their warmup', function (): void {
    $result = chart()->compute(Symbol::parse('BTC/USDT'), Timeframe::FiveMinutes, CarbonImmutable::parse('2026-10-01 05:00', 'UTC'), CarbonImmutable::parse('2026-10-01 10:00', 'UTC'), ['ema(9)', 'close > ema(9)', 'bb(20, 2).upper']);
    $ema = new Ema(9);
    $expected = [];

    // The warmup is the longest any expression needs, bb(20): 20 candles and one more before 05:00, at 03:15.
    foreach (array_filter(fiveMinuteBars(), fn (Candle $bar): bool => $bar->openTime >= CarbonImmutable::parse('2026-10-01 03:15', 'UTC')) as $bar) {
        $value = $ema->push($bar->close->value);

        if ($bar->openTime >= CarbonImmutable::parse('2026-10-01 05:00', 'UTC')) {
            $expected[] = (string) $value;
        }
    }

    expect($result['candles'])->toHaveCount(60)
        ->and($result['candles'][0]['time'])->toBe('2026-10-01T05:00:00Z')
        ->and($result['series']['ema(9)'])->toBe($expected)
        ->and($result['series']['close > ema(9)'][0])->toBeIn(['true', 'false'])
        ->and($result['series']['bb(20, 2).upper'][0])->not->toBeNull();
});

it('reads a higher timeframe from its last closed candle, as strategies do', function (): void {
    $result = chart()->compute(Symbol::parse('BTC/USDT'), Timeframe::FifteenMinutes, CarbonImmutable::parse('2026-10-01 03:00', 'UTC'), CarbonImmutable::parse('2026-10-01 05:00', 'UTC'), ['close on 1h']);
    $hourly = [];

    foreach (CandleFactory::walk('2026-10-01 00:00:00', 600, seed: 9) as $minute) {
        if ((int) $minute->openTime->format('i') === 59) {
            $hourly[$minute->endsAt()->getTimestamp()] = (string) $minute->close;
        }
    }

    // The 15-minute candle that closes at 04:00 sees the hour that closed at 04:00; the next three see it too.
    expect(array_slice($result['series']['close on 1h'], 3, 4))->toBe(array_fill(0, 4, $hourly[CarbonImmutable::parse('2026-10-01 04:00', 'UTC')->getTimestamp()]));
});

it('refuses expressions that are not series or conditions, and windows too long', function (array $expressions, string $from, string $to, string $message): void {
    expect(fn () => chart()->compute(Symbol::parse('BTC/USDT'), Timeframe::FiveMinutes, CarbonImmutable::parse($from, 'UTC'), CarbonImmutable::parse($to, 'UTC'), array_values(array_filter($expressions, 'is_string'))))
        ->toThrow($message === '' ? DslError::class : InvalidArgumentException::class, $message);
})->with([
    'syntax' => [['ema(9'], '2026-10-01 05:00', '2026-10-01 06:00', 'expected ) to close the call to ema'],
    'stop level' => [['level(low)'], '2026-10-01 05:00', '2026-10-01 06:00', 'level is an exit level'],
    'too many candles' => [['ema(9)'], '2026-10-01 00:00', '2026-10-05 00:00', 'at most 1000 candles; ask for a shorter window or a longer timeframe.'],
    'too many expressions' => [array_fill(0, 21, 'close'), '2026-10-01 05:00', '2026-10-01 06:00', 'at most 20 expressions at a time.'],
]);

it('draws OBV and the volume profile levels with the indicators\' own formulas (D-72)', function (): void {
    $result = chart()->compute(Symbol::parse('BTC/USDT'), Timeframe::FiveMinutes, CarbonImmutable::parse('2026-10-01 05:00', 'UTC'), CarbonImmutable::parse('2026-10-01 10:00', 'UTC'), ['vp_poc(20)', 'vp_vah(20)', 'vp_val(20)', 'obv() - sma(20, src: obv())']);
    $profile = new VolumeProfile(20);
    $expected = [];

    // the warmup is the 20 candles before 05:00, from 03:20
    foreach (array_filter(fiveMinuteBars(), fn (Candle $bar): bool => $bar->openTime >= CarbonImmutable::parse('2026-10-01 03:20', 'UTC')) as $bar) {
        $levels = $profile->push($bar);

        if ($bar->openTime >= CarbonImmutable::parse('2026-10-01 05:00', 'UTC')) {
            $expected[] = (string) $levels['poc'];
        }
    }

    expect($result['series']['vp_poc(20)'])->toBe($expected)
        ->and($result['series']['vp_val(20)'][0] <= $result['series']['vp_poc(20)'][0])->toBeTrue()
        ->and($result['series']['vp_vah(20)'][0] >= $result['series']['vp_poc(20)'][0])->toBeTrue()
        ->and($result['series']['obv() - sma(20, src: obv())'][59])->not->toBeNull();
});
