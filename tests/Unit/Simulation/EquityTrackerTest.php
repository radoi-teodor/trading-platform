<?php

declare(strict_types=1);

use App\Portfolio\Data\ExitReason;
use App\Portfolio\Data\Fill;
use App\Portfolio\Ledger;
use App\Simulation\Metrics\EquityTracker;
use App\Simulation\Metrics\Performance;
use App\Support\Asset;
use App\Support\Number\Decimal;
use App\Support\Number\Money;
use App\Support\Number\Price;
use App\Support\Side;
use App\Support\Symbol;
use Carbon\CarbonImmutable;

mutates(EquityTracker::class, Performance::class);

function trackedTwoDays(): EquityTracker
{
    $tracker = new EquityTracker(Decimal::of('100'), CarbonImmutable::parse('2026-10-01 00:00', 'UTC'));

    foreach ([
        ['2026-10-01 00:01', '110', true],
        ['2026-10-01 00:02', '99', true],
        ['2026-10-01 00:03', '121', false],
        ['2026-10-02 00:00', '108.9', true],
        ['2026-10-03 00:00', '130.68', false],
    ] as [$time, $equity, $inMarket]) {
        $tracker->record(CarbonImmutable::parse($time, 'UTC'), Decimal::of($equity), $inMarket);
    }

    return $tracker;
}

it('follows the peak, the deepest fall and the longest time under water', function (): void {
    $tracker = trackedTwoDays();

    // 110 to 99 and 121 to 108.9 both fall 10 %; the second lasts from 00:03 on day one to 00:00 on day three
    expect((string) $tracker->maxDrawdown())->toBe('0.1')
        ->and($tracker->longestDrawdownSeconds())->toBe(2 * 86400 - 180)
        ->and((string) $tracker->finalEquity())->toBe('130.68')
        ->and((string) $tracker->timeInMarket())->toBe('0.6')
        ->and(array_map('strval', $tracker->daily()))->toBe(['2026-10-02 00:00' => '108.9', '2026-10-03 00:00' => '130.68'])
        ->and(array_keys($tracker->hourly()))->toBe(['2026-10-02 00:00', '2026-10-03 00:00'])
        ->and(array_map('strval', $tracker->points()))->toBe(['2026-10-01 00:00' => '100', '2026-10-02 00:00' => '108.9', '2026-10-03 00:00' => '130.68']);
});

it('counts a drawdown still open at the end up to the end', function (): void {
    $tracker = new EquityTracker(Decimal::of('100'), CarbonImmutable::parse('2026-10-01 00:00', 'UTC'));
    $tracker->record(CarbonImmutable::parse('2026-10-01 00:10', 'UTC'), Decimal::of('120'), false);
    $tracker->record(CarbonImmutable::parse('2026-10-01 00:20', 'UTC'), Decimal::of('90'), false);
    $tracker->record(CarbonImmutable::parse('2026-10-01 01:10', 'UTC'), Decimal::of('100'), false);

    expect((string) $tracker->maxDrawdown())->toBe('0.25')
        ->and($tracker->longestDrawdownSeconds())->toBe(3600)
        ->and(array_keys($tracker->hourly()))->toBe([])
        ->and((new EquityTracker(Decimal::of('1'), CarbonImmutable::parse('2026-10-01', 'UTC')))->timeInMarket())->toBeNull();
});

it('computes returns, risk-adjusted ratios and monthly returns from the daily marks', function (): void {
    // Expected values: Python's decimal module with the same truncation and rounding.
    $metrics = Performance::measure(trackedTwoDays(), new Ledger(Asset::of('USDT'), [Money::of('100', Asset::of('USDT'))]));

    expect((string) $metrics->return)->toBe('0.3068')
        ->and((string) $metrics->netProfit)->toBe('30.68')
        ->and((string) $metrics->sharpe)->toBe('35.17274782779026632')
        ->and($metrics->sortino)->toBeNull()
        ->and((string) $metrics->cagr)->toBe('1614973718305298443510.785000725385196368')
        ->and((string) $metrics->calmar)->toBe('16149737183052984435107.85000725385196368')
        ->and((string) $metrics->turnover)->toBe('0')
        ->and(array_map(fn (array $m): array => [$m['month'], (string) $m['return']], $metrics->monthlyReturns))->toBe([['2026-10', '0.3068']])
        ->and((string) $metrics->positiveMonthsShare)->toBe('1')
        ->and($metrics->trades->count)->toBe(0)
        ->and($metrics->trades->winRate)->toBeNull()
        ->and($metrics->toArray())->toHaveKeys(['return', 'cagr', 'trades.count', 'trades.expectancy_ci95']);
});

it('ends a drawdown at the recovery, not at the next peak', function (): void {
    $tracker = new EquityTracker(Decimal::of('100'), CarbonImmutable::parse('2026-10-01 00:00', 'UTC'));
    $tracker->record(CarbonImmutable::parse('2026-10-01 00:10', 'UTC'), Decimal::of('90'), false);
    $tracker->record(CarbonImmutable::parse('2026-10-01 00:20', 'UTC'), Decimal::of('120'), false);
    $tracker->record(CarbonImmutable::parse('2026-10-01 02:00', 'UTC'), Decimal::of('130'), false);

    expect($tracker->longestDrawdownSeconds())->toBe(1200);
});

it('sums up closed round trips', function (): void {
    $ledger = new Ledger(Asset::of('USDT'), [Money::of('1000', Asset::of('USDT'))]);
    $btc = Symbol::parse('BTC/USDT');
    $fill = fn (string $id, Side $side, string $price, int $minute): Fill => new Fill(
        $id, CarbonImmutable::parse('2026-10-01', 'UTC')->addMinutes($minute), 's', $btc, $side, Decimal::of('1'),
        Price::of($price), Money::of('0', Asset::of('USDT')), Decimal::zero(), Decimal::zero(),
        $side === Side::Buy ? Price::of('95') : null,
        $side === Side::Sell ? ExitReason::Signal : null,
    );

    foreach ([['110', 1], ['95', 3], ['103', 5]] as $i => [$exit, $minute]) {
        $ledger->apply($fill("b{$i}", Side::Buy, '100', $minute - 1));
        $ledger->apply($fill("s{$i}", Side::Sell, $exit, $minute));
    }

    $trades = Performance::measure(new EquityTracker(Decimal::of('1000'), CarbonImmutable::parse('2026-10-01', 'UTC')), $ledger)->trades;

    expect($trades->count)->toBe(3)
        ->and($trades->wins)->toBe(2)
        ->and((string) $trades->winRate)->toBe('0.666666666666666666')
        ->and((string) $trades->averageWin)->toBe('6.5')
        ->and((string) $trades->averageLoss)->toBe('-5')
        ->and((string) $trades->profitFactor)->toBe('2.6')
        ->and((string) $trades->expectancy)->toBe('2.666666666666666666')
        ->and((string) $trades->expectancyR)->toBe('0.533333333333333333')
        ->and($trades->expectancyCi95)->toHaveCount(2)
        ->and($trades->expectancyRCi95)->toHaveCount(2)
        ->and((string) $trades->grossProfit)->toBe('8')
        ->and((string) $trades->costsShareOfGrossProfit)->toBe('0')
        ->and((string) Performance::measure(new EquityTracker(Decimal::of('1000'), CarbonImmutable::parse('2026-10-01', 'UTC')), $ledger)->turnover)->toBe('0.608');
});

it('has no growth rate for a window without time', function (): void {
    $metrics = Performance::measure(new EquityTracker(Decimal::of('100'), CarbonImmutable::parse('2026-10-01', 'UTC')), new Ledger(Asset::of('USDT'), []));

    expect($metrics->cagr)->toBeNull()
        ->and($metrics->calmar)->toBeNull()
        ->and($metrics->monthlyReturns)->toBe([])
        ->and($metrics->positiveMonthsShare)->toBeNull();
});
