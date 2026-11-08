<?php

declare(strict_types=1);

use App\Execution\Data\FeeSchedule;
use App\Execution\Venues\CostModel;
use App\Market\Data\Candle;
use App\Market\Data\FundingRate;
use App\Market\Data\InstrumentRules;
use App\Market\Data\MarketType;
use App\Market\Data\RulesHistory;
use App\Portfolio\Data\ExitReason;
use App\Risk\RiskPolicy;
use App\Simulation\Backtest;
use App\Simulation\Data\Participant;
use App\Simulation\Data\RunDefinition;
use App\Simulation\Report\Report;
use App\Simulation\Report\ReportBuilder;
use App\Strategy\Data\Close;
use App\Strategy\Data\OpenLong;
use App\Strategy\Data\OpenShort;
use App\Support\Asset;
use App\Support\Number\Decimal;
use App\Support\Number\Money;
use App\Support\Number\Price;
use App\Support\Side;
use App\Support\Symbol;
use Carbon\CarbonImmutable;
use Tests\Support\CandleFactory;
use Tests\Support\InMemoryCandleFeed;
use Tests\Support\Policies;
use Tests\Support\ScriptedStrategy;

mutates(Backtest::class, ReportBuilder::class);

/** @return array{RunDefinition, Participant, list<Participant>, array<string, RulesHistory>, list<Candle>} */
function backtestSetup(): array
{
    $btc = Symbol::parse('BTC/USDT');
    $candles = CandleFactory::walk('2026-10-01 00:00:00', 180, seed: 3);
    $rules = ['BTC/USDT' => new RulesHistory($btc, [new InstrumentRules(
        CarbonImmutable::parse('2026-11-08', 'UTC'), Decimal::of('0.01'), Decimal::of('0.001'), Decimal::of('0.001'), null, Decimal::of('5'),
    )])];
    $policy = Policies::account();
    $main = new ScriptedStrategy([
        '00:30' => [new OpenLong($btc, Price::of('90'), null, 'during warmup')],
        '00:45' => [new OpenLong($btc, Price::of('90'), null, 'during warmup')],
        '01:30' => [new OpenLong($btc, Price::of('90'), null, 'entry')],
        '02:10' => [new Close($btc, 'exit')],
    ]);
    $from = CarbonImmutable::parse('2026-10-01 01:00', 'UTC');
    $definition = new RunDefinition(
        $main->identity(), MarketType::Spot, [$btc], $from, CarbonImmutable::parse('2026-10-01 03:00', 'UTC'),
        CarbonImmutable::parse('2026-10-01 00:00', 'UTC'), Money::of('10000', Asset::of('USDT')),
        new FeeSchedule(Decimal::of('0.001'), Decimal::of('0.001')),
        new CostModel(1, Decimal::of('0.0002'), Decimal::of('0.1'), Decimal::of('0.1')),
        $policy, ['hold'],
    );
    $benchmark = new Participant('hold', new ScriptedStrategy(['01:01' => [new OpenLong($btc, null, null, 'hold')]], name: 'hold'), RiskPolicy::benchmark(), $from);

    return [$definition, new Participant('strategy', $main, $policy, $definition->warmupFrom), [$benchmark], $rules, $candles];
}

it('runs the strategy and its benchmarks over the same candles, trading only inside the window', function (): void {
    [$definition, $main, $benchmarks, $rules, $candles] = backtestSetup();
    $result = (new Backtest(new InMemoryCandleFeed(['BTC/USDT' => $candles])))->run($definition, $main, $rules, $benchmarks);
    $decisions = array_values(array_filter($result->main->log->decisions, fn ($d) => $d->intents !== []));
    $trip = $result->main->ledger->roundTrips()[0];

    expect($result->candles)->toBe(180)
        ->and($result->feedFrom->format('H:i'))->toBe('00:00')
        ->and(array_map(fn ($d) => $d->intents[0]->rejection, $decisions))->toBe(['warmup', 'warmup', null, null])
        ->and($trip->exitReason)->toBe(ExitReason::Signal)
        ->and($trip->openedAt->format('H:i'))->toBe('01:30')
        // The sell fills in the minute from 02:10; the remainder under the lot step is written off at the next event,
        // and the round trip ends at the sell.
        ->and($trip->closedAt->format('H:i'))->toBe('02:10')
        ->and($trip->dust->isPositive())->toBeTrue()
        ->and($result->main->equity->start->format('H:i'))->toBe('01:00')
        ->and($result->main->equity->end()->format('H:i'))->toBe('03:00')
        // In the market from the event at 01:31, which books the entry, to the one at 02:10, before the exit fills.
        ->and((string) $result->main->equity->timeInMarket())->toBe('0.333333333333333333')
        ->and((string) $result->main->equity->finalEquity())->toBe((string) $result->main->ledger->equity($result->main->marks))
        ->and($result->benchmarks[0]->ledger->balance(Asset::of('BTC'))->isPositive())->toBeTrue()
        ->and($result->benchmarks[0]->ledger->balance(Asset::of('USDT'))->isLessThan(Decimal::of('5')))->toBeTrue();
});

it('fingerprints every candle it read', function (): void {
    [$definition, $main, , $rules, $candles] = backtestSetup();
    $result = (new Backtest(new InMemoryCandleFeed(['BTC/USDT' => $candles])))->run($definition, $main, $rules);
    $lines = implode('', array_map(fn (Candle $c): string => implode('|', [
        'BTC/USDT', $c->openTime->getTimestamp(), $c->open, $c->high, $c->low, $c->close, $c->volume, $c->quoteVolume, $c->tradeCount, $c->takerBuyVolume,
    ])."\n", $candles));

    expect($result->fingerprint)->toBe(hash('sha256', $lines));
});

it('writes the same report, byte for byte, for the same definition and data', function (): void {
    $report = function (): Report {
        [$definition, $main, $benchmarks, $rules, $candles] = backtestSetup();
        $result = (new Backtest(new InMemoryCandleFeed(['BTC/USDT' => $candles])))->run($definition, $main, $rules, $benchmarks);

        return ReportBuilder::build($definition, $result, $rules, ['ma-trend' => 'needs 200 days of history before the window']);
    };
    $first = $report();

    expect($first->json())->toBe($report()->json())
        ->and($first->hash())->toBe(hash('sha256', $first->json()))
        ->and(array_keys($first->content))->toBe(['definition', 'data', 'result', 'trades', 'open_positions', 'dust', 'equity', 'decisions', 'events', 'benchmarks'])
        ->and($first->content)->toHaveKeys(['benchmarks.cash', 'benchmarks.hold', 'data.filters_assumed_before', 'result.trades'])
        ->and($first->content)->toHaveKey('benchmarks.ma-trend', ['missing' => 'needs 200 days of history before the window'])
        ->and($first->content)->toHaveKey('decisions', ['count' => 180, 'intents' => 4, 'approved' => 2, 'rejected' => ['warmup' => 2]])
        ->and($first->metrics->trades->count)->toBe(1)
        ->and($first->content)->toHaveKey('equity.hourly.1.time', '2026-10-01 03:00');
});

it('reads from the earliest warmup and names filters it had to assume', function (): void {
    [$definition, $main, , $rules, $candles] = backtestSetup();
    $early = new Participant('early', new ScriptedStrategy([], name: 'early'), RiskPolicy::benchmark(), CarbonImmutable::parse('2026-09-30', 'UTC'));
    $known = ['BTC/USDT' => new RulesHistory(Symbol::parse('BTC/USDT'), [new InstrumentRules(
        CarbonImmutable::parse('2026-01-01', 'UTC'), Decimal::of('0.01'), Decimal::of('0.001'), Decimal::of('0.001'), null, Decimal::of('5'),
    )])];
    $result = (new Backtest(new InMemoryCandleFeed(['BTC/USDT' => $candles])))->run($definition, $main, $known, [$early]);

    expect($result->feedFrom->toDateString())->toBe('2026-09-30')
        ->and(ReportBuilder::build($definition, $result, $known)->content)->toHaveKey('data.filters_assumed_before', [])
        ->and(ReportBuilder::build($definition, $result, $rules)->content)->toHaveKey('data.filters_assumed_before.BTC/USDT');
});

it('runs a perpetual strategy from the futures wallet, with funding, next to a spot benchmark of the same base', function (): void {
    $spot = Symbol::parse('BTC/USDT');
    $perp = Symbol::parse('BTC/USDT:USDT');
    $rule = fn (Symbol $symbol, string $tick, string $notional): RulesHistory => new RulesHistory($symbol, [new InstrumentRules(
        CarbonImmutable::parse('2026-01-01', 'UTC'), Decimal::of($tick), Decimal::of('0.001'), Decimal::of('0.001'), null, Decimal::of($notional),
    )]);
    $rules = ['BTC/USDT' => $rule($spot, '0.01', '5'), 'BTC/USDT:USDT' => $rule($perp, '0.1', '100')];
    $candles = CandleFactory::walk('2026-10-01 00:00:00', 180, seed: 3);
    $funding = [new FundingRate(CarbonImmutable::parse('2026-10-01 02:00', 'UTC'), Decimal::of('0.0001'), 8, null)];
    $main = new ScriptedStrategy([
        '01:30' => [new OpenShort($perp, Price::of('110'), null, 'entry')],
        '02:10' => [new Close($perp, 'exit')],
    ], name: 'short', symbols: [$perp]);
    $from = CarbonImmutable::parse('2026-10-01 01:00', 'UTC');
    $definition = new RunDefinition(
        $main->identity(), MarketType::Perp, [$perp], $from, CarbonImmutable::parse('2026-10-01 03:00', 'UTC'), $from,
        Money::of('0', Asset::of('USDT')),
        new FeeSchedule(Decimal::of('0.001'), Decimal::of('0.001')),
        new CostModel(1, Decimal::of('0.0002'), Decimal::of('0.1'), Decimal::of('0.1')),
        Policies::account(), ['hold'],
        futuresBalance: Decimal::of('10000'),
        perpetualFees: new FeeSchedule(Decimal::of('0.0002'), Decimal::of('0.0005')),
        maintenanceRate: Decimal::of('0.01'),
    );
    $benchmark = new Participant(
        'hold', new ScriptedStrategy(['01:01' => [new OpenLong($spot, null, null, 'hold')]], name: 'hold'), RiskPolicy::benchmark(), $from,
        spotBalance: Money::of('10000', Asset::of('USDT')), futuresBalance: Decimal::zero(),
    );
    $feed = new InMemoryCandleFeed(['BTC/USDT' => $candles, 'BTC/USDT:USDT' => $candles], ['BTC/USDT:USDT' => $funding]);
    $result = (new Backtest($feed))->run($definition, new Participant('strategy', $main, Policies::account(), $from), $rules, [$benchmark]);
    $trip = $result->main->ledger->roundTrips()[0];
    // The minute that closes at 02:00 is the one that opened at 01:59.
    $close = $candles[119]->close->value;
    $report = ReportBuilder::build($definition, $result, $rules);

    expect($result->candles)->toBe(240)
        ->and($trip->side)->toBe(Side::Sell)
        // A short receives a positive rate: Q × mark × rate.
        ->and((string) $trip->funding)->toBe((string) $trip->quantity->times($close)->times(Decimal::of('0.0001')))
        ->and((string) $result->main->equity->initial)->toBe('10000')
        ->and((string) $result->main->equity->finalEquity())->toBe((string) $result->main->ledger->equity($result->main->marks))
        ->and($result->benchmarks[0]->ledger->balance(Asset::of('BTC'))->isPositive())->toBeTrue()
        ->and($report->content)->toHaveKey('definition.initial_futures_balance', Decimal::of('10000'))
        ->and($report->content)->toHaveKey('trades.0.side', Side::Sell)
        ->and($report->content)->toHaveKey('result.trades.funding', $trip->funding);
});

it('reports a perpetual position still open at the end with its side, margin, leverage and funding', function (): void {
    $perp = Symbol::parse('BTC/USDT:USDT');
    $rules = ['BTC/USDT:USDT' => new RulesHistory($perp, [new InstrumentRules(
        CarbonImmutable::parse('2026-01-01', 'UTC'), Decimal::of('0.1'), Decimal::of('0.001'), Decimal::of('0.001'), null, Decimal::of('100'),
    )])];
    $candles = CandleFactory::walk('2026-10-01 00:00:00', 180, seed: 3);
    $main = new ScriptedStrategy(['01:30' => [new OpenShort($perp, Price::of('110'), null, 'entry')]], name: 'short', symbols: [$perp]);
    $from = CarbonImmutable::parse('2026-10-01 01:00', 'UTC');
    $definition = new RunDefinition(
        $main->identity(), MarketType::Perp, [$perp], $from, CarbonImmutable::parse('2026-10-01 03:00', 'UTC'), $from,
        Money::of('0', Asset::of('USDT')),
        new FeeSchedule(Decimal::of('0.001'), Decimal::of('0.001')),
        new CostModel(1, Decimal::of('0.0002'), Decimal::of('0.1'), Decimal::of('0.1')),
        Policies::account(),
        futuresBalance: Decimal::of('10000'),
        perpetualFees: new FeeSchedule(Decimal::of('0.0002'), Decimal::of('0.0005')),
        maintenanceRate: Decimal::of('0.01'),
    );
    $feed = new InMemoryCandleFeed(['BTC/USDT:USDT' => $candles], ['BTC/USDT:USDT' => [
        new FundingRate(CarbonImmutable::parse('2026-10-01 02:00', 'UTC'), Decimal::of('0.0001'), 8, null),
    ]]);
    $result = (new Backtest($feed))->run($definition, new Participant('strategy', $main, Policies::account(), $from), $rules);
    $position = $result->main->ledger->perpPositions()[0];
    $open = ReportBuilder::build($definition, $result, $rules)->content['open_positions'] ?? null;

    expect($open)->toBe([[
        'symbol' => $perp, 'opened_at' => $position->openedAt, 'side' => Side::Sell, 'quantity' => $position->quantity,
        'entry_price' => $position->entryPrice, 'margin' => $position->margin, 'leverage' => $position->leverage,
        'fees' => $position->fees, 'funding' => $position->funding,
    ]])
        ->and($position->funding->isPositive())->toBeTrue()
        ->and($result->main->equity->timeInMarket()?->isPositive())->toBeTrue();
});
