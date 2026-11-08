<?php

declare(strict_types=1);

use App\Market\Data\Candle;
use App\Strategy\Baselines\BuyAndHold;
use App\Strategy\Baselines\DonchianBreakout;
use App\Strategy\Baselines\MovingAverageTrend;
use App\Strategy\Baselines\Parameters;
use App\Strategy\Contracts\Strategy;
use App\Strategy\Data\Close;
use App\Strategy\Data\OpenLong;
use App\Strategy\Data\PositionView;
use App\Strategy\Data\StrategyContext;
use App\Support\Number\Decimal;
use App\Support\Symbol;
use App\Support\Timeframe;
use Carbon\CarbonImmutable;

mutates(BuyAndHold::class, MovingAverageTrend::class, DonchianBreakout::class, Parameters::class);

function hourBar(int $hour, string $high, string $low, string $close): Candle
{
    return Candle::fromStrings(CarbonImmutable::parse('2026-10-01', 'UTC')->addHours($hour), Timeframe::OneHour, $close, $high, $low, $close, '1', '1', 1, '0');
}

/**
 * @param  list<Candle>  $bars
 * @return list<string> one line per decision: the intents' classes and prices
 */
function decide(Strategy $strategy, array $bars, ?int $holdFrom = null): array
{
    $lines = [];

    foreach ($bars as $i => $bar) {
        $position = $holdFrom !== null && $i >= $holdFrom
            ? new PositionView(Decimal::of('1'), Decimal::of('100'), null, CarbonImmutable::parse('2026-10-01', 'UTC'))
            : null;
        $intents = $strategy->onCandle(new StrategyContext($bar->endsAt(), Symbol::parse('BTC/USDT'), $bar, $position));
        $lines[] = implode(',', array_map(fn ($intent): string => match (true) {
            $intent instanceof OpenLong => "open stop={$intent->stop} target={$intent->target}",
            $intent instanceof Close => 'close',
            default => 'other',
        }, $intents));
    }

    return $lines;
}

it('buys once and holds', function (): void {
    $strategy = BuyAndHold::build([], [Symbol::parse('BTC/USDT')]);

    expect(decide($strategy, [hourBar(0, '1', '1', '1'), hourBar(1, '1', '1', '1')], holdFrom: 1))->toBe(['open stop= target=', ''])
        ->and($strategy->warmupBars())->toBe(0)
        ->and($strategy->subscriptions()[0]->timeframe)->toBe(Timeframe::OneMinute)
        ->and($strategy->identity()->canonical())->toBe('buy-and-hold|1|');
});

it('goes long when the fast average crosses above the slow one and leaves when it falls back', function (): void {
    $strategy = MovingAverageTrend::build(['fast' => '2', 'slow' => '3', 'timeframe' => '1h'], [Symbol::parse('BTC/USDT')]);
    $closes = ['10', '10', '10', '13', '14', '9', '8'];
    $bars = array_map(fn (string $close, int $i): Candle => hourBar($i, $close, $close, $close), $closes, array_keys($closes));

    // fast/slow: -, -, 10/10, 11.5/11, 13.5/12.33, 11.5/12, 8.5/10.33
    expect(decide($strategy, $bars, holdFrom: 4))->toBe(['', '', '', 'open stop= target=', '', 'close', 'close'])
        ->and($strategy->warmupBars())->toBe(3)
        ->and($strategy->subscriptions()[0]->timeframe)->toBe(Timeframe::OneHour);
});

it('enters a breakout with an ATR stop and an R-multiple target, and leaves under the exit channel', function (): void {
    $strategy = DonchianBreakout::build(['entry' => '3', 'exit' => '2', 'atr' => '2', 'stop_atr' => '2', 'target_r' => '3', 'timeframe' => '1h'], [Symbol::parse('BTC/USDT')]);
    $bars = [
        hourBar(0, '101', '99', '100'),   // TR 2
        hourBar(1, '102', '100', '101'),  // TR 2, ATR 2
        hourBar(2, '102', '100', '101'),  // TR 2, ATR 2; channel of 3 not full before this bar
        hourBar(3, '106', '101', '105'),  // close 105 > 102: TR 5, ATR 3.5; stop 98, target 126
        hourBar(4, '106', '104', '105'),  // holding; 105 is above 100, the low of the 2 before
        hourBar(5, '105', '100', '100.5'), // 100.5 under 101, the low of the 2 before: close
    ];

    expect(decide($strategy, $bars, holdFrom: 4))->toBe(['', '', '', 'open stop=98 target=126', '', 'close'])
        ->and($strategy->warmupBars())->toBe(4)
        ->and($strategy->identity()->name)->toBe('donchian-breakout');
});

it('does not enter when the stop would not be above zero', function (): void {
    $strategy = DonchianBreakout::build(['entry' => '1', 'exit' => '1', 'atr' => '1', 'stop_atr' => '50', 'target_r' => '3', 'timeframe' => '1h'], [Symbol::parse('BTC/USDT')]);

    expect(decide($strategy, [hourBar(0, '10', '8', '9'), hourBar(1, '12', '9', '11')]))->toBe(['', '']);
});

it('refuses parameters it cannot use', function (callable $build): void {
    $build();
})->with([
    'fast not shorter' => [fn () => MovingAverageTrend::build(['fast' => '5', 'slow' => '5', 'timeframe' => '1d'], [Symbol::parse('BTC/USDT')])],
    'not a count' => [fn () => MovingAverageTrend::build(['fast' => '0', 'slow' => '5', 'timeframe' => '1d'], [Symbol::parse('BTC/USDT')])],
    'not a timeframe' => [fn () => MovingAverageTrend::build(['fast' => '2', 'slow' => '5', 'timeframe' => '2h'], [Symbol::parse('BTC/USDT')])],
    'not positive' => [fn () => DonchianBreakout::build([...DonchianBreakout::defaults(), 'stop_atr' => '0'], [Symbol::parse('BTC/USDT')])],
])->throws(InvalidArgumentException::class);
