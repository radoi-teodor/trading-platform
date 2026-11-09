<?php

declare(strict_types=1);

use App\Market\Data\Candle;
use App\Market\Data\FundingRate;
use App\Strategy\Baselines\BuyAndHold;
use App\Strategy\Baselines\DonchianBreakout;
use App\Strategy\Baselines\FundingCarry;
use App\Strategy\Baselines\MovingAverageTrend;
use App\Strategy\Baselines\Parameters;
use App\Strategy\Contracts\Strategy;
use App\Strategy\Data\Close;
use App\Strategy\Data\Intent;
use App\Strategy\Data\OpenCarry;
use App\Strategy\Data\OpenLong;
use App\Strategy\Data\OpenShort;
use App\Strategy\Data\PositionView;
use App\Strategy\Data\StrategyContext;
use App\Support\Number\Decimal;
use App\Support\Side;
use App\Support\Symbol;
use App\Support\Timeframe;
use Carbon\CarbonImmutable;

mutates(BuyAndHold::class, MovingAverageTrend::class, DonchianBreakout::class, FundingCarry::class, Parameters::class);

function hourBar(int $hour, string $high, string $low, string $close): Candle
{
    return Candle::fromStrings(CarbonImmutable::parse('2026-10-01', 'UTC')->addHours($hour), Timeframe::OneHour, $close, $high, $low, $close, '1', '1', 1, '0');
}

/**
 * @param  list<Candle>  $bars
 * @return list<string> one line per decision: the intents' classes and prices
 */
function decide(Strategy $strategy, array $bars, ?int $holdFrom = null, string $symbol = 'BTC/USDT', Side $side = Side::Buy): array
{
    $lines = [];

    foreach ($bars as $i => $bar) {
        $position = $holdFrom !== null && $i >= $holdFrom
            ? new PositionView(Decimal::of('1'), Decimal::of('100'), null, CarbonImmutable::parse('2026-10-01', 'UTC'), $side)
            : null;
        $intents = $strategy->onCandle(new StrategyContext($bar->endsAt(), Symbol::parse($symbol), $bar, $position));
        $lines[] = intentLines($intents);
    }

    return $lines;
}

/** @param list<Intent> $intents */
function intentLines(array $intents): string
{
    return implode(',', array_map(fn ($intent): string => match (true) {
        $intent instanceof OpenLong => "open stop={$intent->stop} target={$intent->target}",
        $intent instanceof OpenShort => "short stop={$intent->stop} target={$intent->target}",
        $intent instanceof OpenCarry => "carry {$intent->spot} {$intent->perpetual} max={$intent->maxPosition} leverage={$intent->maxLeverage} buffer={$intent->marginBuffer}",
        $intent instanceof Close => "close {$intent->symbol}",
        default => 'other',
    }, $intents));
}

it('buys once and holds', function (): void {
    $strategy = BuyAndHold::build([], [Symbol::parse('BTC/USDT')]);

    expect(decide($strategy, [hourBar(0, '1', '1', '1'), hourBar(1, '1', '1', '1')], holdFrom: 1))->toBe(['open stop= target=', ''])
        ->and($strategy->warmupBars())->toBe(0)
        ->and($strategy->subscriptions()[0]->timeframe)->toBe(Timeframe::OneMinute)
        ->and($strategy->identity()->canonical())->toBe('buy-and-hold|1|');
});

it('goes long when the fast average crosses above the slow one and leaves when it falls back', function (): void {
    $strategy = MovingAverageTrend::build(['fast' => '2', 'side' => 'long', 'slow' => '3', 'timeframe' => '1h'], [Symbol::parse('BTC/USDT')]);
    $closes = ['10', '10', '10', '13', '14', '9', '8'];
    $bars = array_map(fn (string $close, int $i): Candle => hourBar($i, $close, $close, $close), $closes, array_keys($closes));

    // fast/slow: -, -, 10/10, 11.5/11, 13.5/12.33, 11.5/12, 8.5/10.33
    expect(decide($strategy, $bars, holdFrom: 4))->toBe(['', '', '', 'open stop= target=', '', 'close BTC/USDT', 'close BTC/USDT'])
        ->and($strategy->warmupBars())->toBe(3)
        ->and($strategy->subscriptions()[0]->timeframe)->toBe(Timeframe::OneHour);
});

it('enters a breakout with an ATR stop and an R-multiple target, and leaves under the exit channel', function (): void {
    $strategy = DonchianBreakout::build([...DonchianBreakout::defaults(), 'entry' => '3', 'exit' => '2', 'atr' => '2', 'timeframe' => '1h'], [Symbol::parse('BTC/USDT')]);
    $bars = [
        hourBar(0, '101', '99', '100'),   // TR 2
        hourBar(1, '102', '100', '101'),  // TR 2, ATR 2
        hourBar(2, '102', '100', '101'),  // TR 2, ATR 2; channel of 3 not full before this bar
        hourBar(3, '106', '101', '105'),  // close 105 > 102: TR 5, ATR 3.5; stop 98, target 126
        hourBar(4, '106', '104', '105'),  // holding; 105 is above 100, the low of the 2 before
        hourBar(5, '105', '100', '100.5'), // 100.5 under 101, the low of the 2 before: close
    ];

    expect(decide($strategy, $bars, holdFrom: 4))->toBe(['', '', '', 'open stop=98 target=126', '', 'close BTC/USDT'])
        ->and($strategy->warmupBars())->toBe(4)
        ->and($strategy->identity()->name)->toBe('donchian-breakout');
});

it('does not enter when the stop would not be above zero', function (): void {
    $strategy = DonchianBreakout::build([...DonchianBreakout::defaults(), 'entry' => '1', 'exit' => '1', 'atr' => '1', 'stop_atr' => '50', 'timeframe' => '1h'], [Symbol::parse('BTC/USDT')]);

    expect(decide($strategy, [hourBar(0, '10', '8', '9'), hourBar(1, '12', '9', '11')]))->toBe(['', '']);
});

it('refuses parameters it cannot use', function (callable $build): void {
    $build();
})->with([
    'fast not shorter' => [fn () => MovingAverageTrend::build(['fast' => '5', 'side' => 'long', 'slow' => '5', 'timeframe' => '1d'], [Symbol::parse('BTC/USDT')])],
    'not a count' => [fn () => MovingAverageTrend::build(['fast' => '0', 'side' => 'long', 'slow' => '5', 'timeframe' => '1d'], [Symbol::parse('BTC/USDT')])],
    'not a timeframe' => [fn () => MovingAverageTrend::build(['fast' => '2', 'side' => 'long', 'slow' => '5', 'timeframe' => '2h'], [Symbol::parse('BTC/USDT')])],
    'no side' => [fn () => MovingAverageTrend::build(['fast' => '2', 'slow' => '5', 'timeframe' => '1d'], [Symbol::parse('BTC/USDT')])],
    'not a side' => [fn () => MovingAverageTrend::build([...MovingAverageTrend::defaults(), 'side' => 'sideways'], [Symbol::parse('BTC/USDT:USDT')])],
    'short on spot' => [fn () => DonchianBreakout::build([...DonchianBreakout::defaults(), 'side' => 'short'], [Symbol::parse('BTC/USDT')])],
    'carry rate not a number' => [fn () => FundingCarry::build([...FundingCarry::defaults(), 'exit' => 'zero'], [Symbol::parse('BTC/USDT:USDT')])],
    'carry leverage not a count' => [fn () => FundingCarry::build([...FundingCarry::defaults(), 'max_leverage' => '1.5'], [Symbol::parse('BTC/USDT:USDT')])],
    'carry buffer not positive' => [fn () => FundingCarry::build([...FundingCarry::defaults(), 'margin_buffer' => '0'], [Symbol::parse('BTC/USDT:USDT')])],
    'carry position not positive' => [fn () => FundingCarry::build([...FundingCarry::defaults(), 'max_position' => '-0.1'], [Symbol::parse('BTC/USDT:USDT')])],
    'carry exit above entry' => [fn () => FundingCarry::build([...FundingCarry::defaults(), 'exit' => '0.0002'], [Symbol::parse('BTC/USDT:USDT')])],
    'not positive' => [fn () => DonchianBreakout::build([...DonchianBreakout::defaults(), 'stop_atr' => '0'], [Symbol::parse('BTC/USDT')])],
])->throws(InvalidArgumentException::class);

it('goes short on a perpetual while the fast average is below the slow one', function (): void {
    $strategy = MovingAverageTrend::build([...MovingAverageTrend::defaults(), 'fast' => '2', 'slow' => '3', 'timeframe' => '1h', 'side' => 'short'], [Symbol::parse('BTC/USDT:USDT')]);
    $closes = ['10', '10', '10', '7', '6', '11', '12'];
    $bars = array_map(fn (string $close, int $i): Candle => hourBar($i, $close, $close, $close), $closes, array_keys($closes));

    // fast/slow: -, -, 10/10, 8.5/9, 6.5/7.67, 8.5/8, 11.5/9.67
    expect(decide($strategy, $bars, holdFrom: 4, symbol: 'BTC/USDT:USDT', side: Side::Sell))
        ->toBe(['', '', '', 'short stop= target=', '', 'close BTC/USDT:USDT', 'close BTC/USDT:USDT']);
});

it('shorts a breakdown with an ATR stop above and an R-multiple target below, and leaves above the exit channel', function (): void {
    $strategy = DonchianBreakout::build([...DonchianBreakout::defaults(), 'entry' => '3', 'exit' => '2', 'atr' => '2', 'timeframe' => '1h', 'side' => 'short'], [Symbol::parse('ETH/USDT:USDT')]);
    $bars = [
        hourBar(0, '101', '99', '100'),   // TR 2
        hourBar(1, '100', '98', '99'),    // TR 2, ATR 2
        hourBar(2, '100', '98', '99'),    // TR 2, ATR 2
        hourBar(3, '99', '94', '95'),     // close 95 < 98: TR 5, ATR 3.5; stop 102, target 74
        hourBar(4, '96', '94', '95'),     // holding; 95 is under 100, the high of the 2 before
        hourBar(5, '100', '95', '99.5'),  // 99.5 above 99, the high of the 2 before: close
    ];

    expect(decide($strategy, $bars, holdFrom: 4, symbol: 'ETH/USDT:USDT', side: Side::Sell))
        ->toBe(['', '', '', 'short stop=102 target=74', '', 'close ETH/USDT:USDT']);
});

it('shorts without a target when the target would not be above zero', function (): void {
    $strategy = DonchianBreakout::build([...DonchianBreakout::defaults(), 'entry' => '1', 'exit' => '1', 'atr' => '1', 'target_r' => '50', 'timeframe' => '1h', 'side' => 'short'], [Symbol::parse('BTC/USDT:USDT')]);

    expect(decide($strategy, [hourBar(0, '12', '10', '11'), hourBar(1, '10', '8', '9')], symbol: 'BTC/USDT:USDT'))->toBe(['', 'short stop=15 target=']);
});

/**
 * Feeds the carry an hour at a time: the spot candle, then the perpetual one with the funding rate known then.
 *
 * @param  list<array{?string, string, bool, bool}>  $hours  funding time (hours from the start), rate, spot held, perpetual held
 * @return list<string>
 */
function carryDecisions(Strategy $strategy, array $hours, ?int $skipSpotAt = null): array
{
    $lines = [];
    $view = fn (bool $held, Side $side): ?PositionView => $held ? new PositionView(Decimal::of('1'), Decimal::of('100'), null, CarbonImmutable::parse('2026-10-01', 'UTC'), $side) : null;

    foreach ($hours as $i => [$fundingHour, $rate, $spotHeld, $perpHeld]) {
        $bar = hourBar($i, '100', '100', '100');

        if ($i !== $skipSpotAt) {
            expect($strategy->onCandle(new StrategyContext($bar->endsAt(), Symbol::parse('BTC/USDT'), $bar, $view($spotHeld, Side::Buy))))->toBe([]);
        }

        $funding = $fundingHour === null ? null : new FundingRate(CarbonImmutable::parse('2026-10-01', 'UTC')->addHours((int) $fundingHour), Decimal::of($rate), 8, null);
        $lines[] = intentLines($strategy->onCandle(new StrategyContext($bar->endsAt(), Symbol::parse('BTC/USDT:USDT'), $bar, $view($perpHeld, Side::Sell), $funding)));
    }

    return $lines;
}

it('opens a carry when the average funding is above the entry, closes both legs below the exit, and never keeps one leg alone', function (): void {
    $strategy = FundingCarry::build([...FundingCarry::defaults(), 'entry_periods' => '3', 'exit_periods' => '2'], [Symbol::parse('BTC/USDT:USDT')]);

    expect(carryDecisions($strategy, [
        [null, '0', false, false],
        ['1', '0.0002', false, false],  // 1 rate
        ['1', '0.0002', false, false],  // the same funding again: still 1
        ['3', '0.0002', false, false],  // 2
        ['4', '0.0002', false, false],  // 3, average 0.0002 > 0.0001: open
        ['5', '0.0001', true, true],    // last 2 average 0.00015: hold
        ['6', '-0.0003', true, true],   // last 2 average -0.0001 < 0: close both
        ['6', '-0.0003', true, false],  // the hedge is gone: close the spot leg
        ['6', '-0.0003', false, true],  // the spot leg is gone: close the hedge
    ]))->toBe([
        '', '', '', '',
        'carry BTC/USDT BTC/USDT:USDT max=0.3 leverage=2 buffer=0.5',
        '',
        'close BTC/USDT,close BTC/USDT:USDT',
        'close BTC/USDT',
        'close BTC/USDT:USDT',
    ])
        ->and($strategy->subscriptions()[0]->symbol->isPerpetual())->toBeFalse()
        ->and($strategy->subscriptions()[1]->symbol->isPerpetual())->toBeTrue()
        ->and($strategy->identity()->name)->toBe('funding-carry')
        // 3 funding periods of 8 hours on 1-hour candles, plus one
        ->and($strategy->warmupBars())->toBe(25);
});

it('decides nothing in an hour whose spot candle it has not seen', function (): void {
    $strategy = FundingCarry::build([...FundingCarry::defaults(), 'entry_periods' => '1', 'exit_periods' => '1'], [Symbol::parse('BTC/USDT')]);

    expect(carryDecisions($strategy, [['0', '0.0005', false, false], ['1', '0.0005', false, false]], skipSpotAt: 0))
        ->toBe(['', 'carry BTC/USDT BTC/USDT:USDT max=0.3 leverage=2 buffer=0.5']);
});

it('stays out while the close is inside the entry channel, on either side', function (string $side, string $symbol): void {
    $strategy = DonchianBreakout::build([...DonchianBreakout::defaults(), 'entry' => '2', 'exit' => '2', 'atr' => '1', 'timeframe' => '1h', 'side' => $side], [Symbol::parse($symbol)]);

    // The channel of the 2 bars before the third is 98 to 104; 101 breaks neither side.
    expect(decide($strategy, [hourBar(0, '104', '99', '100'), hourBar(1, '102', '98', '101'), hourBar(2, '102', '100', '101')], symbol: $symbol))->toBe(['', '', '']);
})->with([['long', 'BTC/USDT'], ['short', 'BTC/USDT:USDT']]);
