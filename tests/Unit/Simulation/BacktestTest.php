<?php

declare(strict_types=1);

use App\Execution\Data\FeeSchedule;
use App\Execution\Venues\CostModel;
use App\Market\Data\Candle;
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
use App\Support\Asset;
use App\Support\Number\Decimal;
use App\Support\Number\Money;
use App\Support\Number\Price;
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
