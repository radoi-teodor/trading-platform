<?php

declare(strict_types=1);

use App\Execution\Contracts\Venue;
use App\Execution\Data\Balance;
use App\Execution\Data\EngineMode;
use App\Execution\Data\EngineRecord;
use App\Execution\Data\FeeSchedule;
use App\Execution\Data\OrderType;
use App\Execution\Engine;
use App\Execution\Journal\InMemoryDecisionLog;
use App\Execution\OrderBook;
use App\Execution\OrderManager;
use App\Execution\ReconciliationMismatch;
use App\Execution\Venues\CostModel;
use App\Execution\Venues\SimulatedVenue;
use App\Market\Data\Candle;
use App\Market\Data\InstrumentRules;
use App\Market\Data\MinuteCandles;
use App\Market\Data\RulesHistory;
use App\Portfolio\Data\ExitReason;
use App\Portfolio\Ledger;
use App\Risk\RiskPolicy;
use App\Strategy\Contracts\Strategy;
use App\Strategy\Data\Close;
use App\Strategy\Data\MoveStop;
use App\Strategy\Data\OpenLong;
use App\Strategy\Data\StrategyContext;
use App\Strategy\Data\StrategyIdentity;
use App\Support\Asset;
use App\Support\Clock\SimClock;
use App\Support\Number\Decimal;
use App\Support\Number\Money;
use App\Support\Number\Price;
use App\Support\Symbol;
use App\Support\Timeframe;
use Carbon\CarbonImmutable;
use Tests\Support\ForwardingVenue;
use Tests\Support\ScriptedStrategy;

mutates(Engine::class, OrderManager::class, OrderBook::class);

const ENGINE_START = '2026-10-01 00:00:00';

/** A venue that reports one USDT more than it holds: the engine must notice. */
final class OverReportingVenue extends ForwardingVenue
{
    public function balances(): array
    {
        $balances = $this->inner->balances();
        $usdt = $balances['USDT'];
        $balances['USDT'] = new Balance($usdt->asset, $usdt->free->plus(Decimal::of(1)), $usdt->locked);

        return $balances;
    }
}

final class EngineHarness
{
    public int $minute = 0;

    public function __construct(public SimClock $clock, public SimulatedVenue $venue, public Engine $engine, public InMemoryDecisionLog $log) {}

    public function minute(string $open, string $high, string $low, string $close, string $volume = '1000'): Decimal
    {
        $candle = Candle::fromStrings(
            CarbonImmutable::parse(ENGINE_START, 'UTC')->addMinutes($this->minute),
            Timeframe::OneMinute, $open, $high, $low, $close, $volume, '0', 1, '0',
        );
        $this->minute++;
        $this->clock->advanceTo($candle->endsAt());
        $this->venue->match(Symbol::parse('BTC/USDT'), $candle);

        return $this->engine->handle(new MinuteCandles($candle->endsAt(), ['BTC/USDT' => $candle]));
    }

    public function usdt(): string
    {
        return (string) $this->engine->ledger()->balance(Asset::of('USDT'));
    }

    /** @return list<string> */
    public function events(): array
    {
        return array_map(fn (EngineRecord $record): string => $record->kind, $this->log->events);
    }
}

/** @param array<string, string|int> $overrides */
function policyWith(array $overrides = []): RiskPolicy
{
    return RiskPolicy::account([...[
        'risk_per_trade' => '0.005', 'total_open_risk' => '0.03', 'notional_per_instrument' => '0.25',
        'max_open_positions' => 6, 'daily_loss' => '0.02', 'weekly_loss' => '0.05', 'drawdown_halt' => '0.10',
        'drawdown_flatten' => '0.15', 'cash_buffer' => '0.01',
    ], ...$overrides]);
}

/**
 * @param  Strategy|list<Strategy>  $strategies
 * @param  (Closure(SimulatedVenue): Venue)|null  $wrap
 */
function engineHarness(Strategy|array $strategies, ?RiskPolicy $policy = null, string $step = '0.001', string $tradingFrom = ENGINE_START, ?Closure $wrap = null, string $minNotional = '5', ?string $decideFrom = null): EngineHarness
{
    $clock = new SimClock(CarbonImmutable::parse(ENGINE_START, 'UTC'));
    $rules = ['BTC/USDT' => new RulesHistory(Symbol::parse('BTC/USDT'), [new InstrumentRules(
        CarbonImmutable::parse('2026-01-01', 'UTC'), Decimal::of('0.01'), Decimal::of($step), Decimal::of($step), null, Decimal::of($minNotional),
    )])];
    $venue = new SimulatedVenue(
        $clock,
        new FeeSchedule(Decimal::of('0.001'), Decimal::of('0.001')),
        new CostModel(1, Decimal::zero(), Decimal::zero(), Decimal::of('0.1')),
        $rules,
        [Money::of('10000', Asset::of('USDT'))],
    );
    $log = new InMemoryDecisionLog;
    $engine = new Engine(
        EngineMode::Backtest,
        $wrap === null ? $venue : $wrap($venue),
        new Ledger(Asset::of('USDT'), [Money::of('10000', Asset::of('USDT'))]),
        $policy ?? policyWith(),
        $log,
        is_array($strategies) ? $strategies : [$strategies],
        $rules,
        CarbonImmutable::parse($tradingFrom, 'UTC'),
        $decideFrom === null ? null : CarbonImmutable::parse($decideFrom, 'UTC'),
    );

    return new EngineHarness($clock, $venue, $engine, $log);
}

function long(string $stop = '98', ?string $target = '104'): OpenLong
{
    return new OpenLong(Symbol::parse('BTC/USDT'), $stop === '' ? null : Price::of($stop), $target === null ? null : Price::of($target), 'test entry');
}

it('enters, protects with an OCO and exits at the target, booking the round trip', function (): void {
    $h = engineHarness(new ScriptedStrategy(['00:01' => [long()]]));

    $h->minute('100', '100', '100', '100');
    // 10000 * 0.005 / (100 - 98) = 25 BTC, exactly the 25 % notional cap
    expect($h->log->decisions[0]->intents[0]->approved)->toBeTrue()
        ->and($h->log->decisions[0]->intents[0]->quantity)->toBe('25')
        ->and($h->log->decisions[0]->intents[0]->orders)->toHaveCount(1)
        ->and($h->log->decisions[0]->strategy)->toBe('scripted@v1')
        ->and($h->log->decisions[0]->intents[0]->type)->toBe('open_long')
        ->and($h->log->decisions[0]->id)->toBe((new StrategyIdentity('scripted', 1, []))->key().'-'.base_convert((string) CarbonImmutable::parse('2026-10-01 00:01:00', 'UTC')->getTimestamp(), 10, 36))
        ->and($h->log->decisions[0]->intents[0]->orders[0])->toBe($h->log->decisions[0]->id.'-e1');

    $equity = $h->minute('100', '101', '99.5', '101');
    $protection = $h->venue->openOrders();

    expect((string) $equity)->toBe('10022.475')
        ->and(array_map(fn ($o) => $o->type, $protection))->toBe([OrderType::StopMarket, OrderType::Limit])
        ->and((string) $protection[0]->stopPrice)->toBe('98')
        ->and((string) $protection[1]->price)->toBe('104')
        ->and((string) $protection[0]->quantity)->toBe('24.975');

    $h->minute('101', '104.02', '100.5', '104');
    $equity = $h->minute('104', '104', '104', '104');
    $trip = $h->engine->ledger()->roundTrips()[0];

    expect((string) $equity)->toBe('10094.8026')
        ->and($h->usdt())->toBe('10094.8026')
        ->and($trip->exitReason)->toBe(ExitReason::Target)
        ->and((string) $trip->netProfit)->toBe('94.8026')
        ->and((string) $trip->rMultiple)->toBe('1.896052')
        ->and((string) $trip->fees)->toBe('5.0974')
        ->and((string) $trip->maximumAdverseExcursion)->toBe('12.5')
        ->and((string) $trip->maximumFavourableExcursion)->toBe('25')
        ->and($h->venue->openOrders())->toBe([]);
});

it('takes the stop at the open when the price gaps through it', function (): void {
    $h = engineHarness(new ScriptedStrategy(['00:01' => [long()]]));
    $h->minute('100', '100', '100', '100');
    $h->minute('100', '101', '99.5', '101');
    $h->minute('97', '97.5', '96', '97');
    $h->minute('97', '97', '97', '97');

    $trip = $h->engine->ledger()->roundTrips()[0];

    expect($trip->exitReason)->toBe(ExitReason::Stop)
        ->and((string) $trip->exitPrice)->toBe('97')
        ->and($h->usdt())->toBe('9920.152425');
});

it('closes on a signal and writes off the remainder below the lot step as dust', function (): void {
    $h = engineHarness(new ScriptedStrategy([
        '00:01' => [long()],
        '00:02' => [new Close(Symbol::parse('BTC/USDT'), 'test exit')],
    ]), step: '0.01');
    $h->minute('100', '100', '100', '100');
    $h->minute('100', '101', '99.5', '101');

    expect(array_map(fn ($o) => [$o->type, (string) $o->quantity], $h->venue->openOrders()))->toBe([[OrderType::Market, '24.97']]);

    $h->minute('102', '102', '102', '102');
    $h->minute('102', '102', '102', '102');
    $trip = $h->engine->ledger()->roundTrips()[0];

    expect($trip->exitReason)->toBe(ExitReason::Signal)
        ->and((string) $trip->dust)->toBe('0.005')
        ->and((string) $h->engine->ledger()->dust(Asset::of('BTC')))->toBe('0.005')
        ->and($h->usdt())->toBe('10044.39306')
        ->and($h->events())->toBe(['dust'])
        ->and($h->log->decisions[1]->intents[0]->type)->toBe('close')
        ->and($h->log->decisions[1]->intents[0]->orders)->toHaveCount(1)
        ->and($h->engine->ledger()->positions())->toBe([]);
});

it('moves the stop towards the price and refuses to widen it', function (): void {
    $btc = Symbol::parse('BTC/USDT');
    $h = engineHarness(new ScriptedStrategy([
        '00:01' => [long()],
        '00:02' => [new MoveStop($btc, Price::of('99'), 'trail')],
        '00:03' => [new MoveStop($btc, Price::of('98.5'), 'widen')],
    ]));
    $h->minute('100', '100', '100', '100');
    $h->minute('100', '101', '99.5', '101');

    expect((string) $h->venue->openOrders()[0]->stopPrice)->toBe('99')
        ->and((string) $h->venue->openOrders()[1]->price)->toBe('104');

    $h->minute('101', '102', '100.5', '101');

    expect((string) $h->venue->openOrders()[0]->stopPrice)->toBe('99')
        ->and($h->log->decisions[2]->intents[0]->rejection)->toBe('stop_widening')
        ->and($h->log->decisions[2]->intents[0]->approved)->toBeFalse()
        ->and($h->log->decisions[1]->intents[0]->type)->toBe('move_stop')
        ->and($h->engine->ledger()->positions()[0]->quantity->equals(Decimal::of('24.975')))->toBeTrue();
});

it('flattens the account at the hard drawdown and halts new entries', function (): void {
    $h = engineHarness(new ScriptedStrategy(['00:01' => [long()], '00:04' => [long('95')]]), policyWith([
        'drawdown_halt' => '0.001', 'drawdown_flatten' => '0.002',
    ]));
    $h->minute('100', '100', '100', '100');
    $h->minute('100', '101', '99.5', '101');
    // 7500 + 24.975 * 99.2 = 9977.52, 0.45 % under the peak of 10022.475
    $h->minute('101', '101', '99', '99.2');

    expect($h->events())->toBe(['drawdown_halt', 'drawdown_flatten'])
        ->and(array_map(fn ($o) => $o->type, $h->venue->openOrders()))->toBe([OrderType::Market]);

    $h->minute('99', '99', '99', '99');

    expect($h->engine->ledger()->roundTrips()[0]->exitReason)->toBe(ExitReason::Flatten)
        ->and($h->log->decisions[1]->time->format('H:i'))->toBe('00:04')
        ->and($h->log->decisions[1]->intents[0]->rejection)->toBe('halted')
        ->and($h->log->quietDecisions)->toBe(2);
});

it('refuses a second entry for the same instrument in one decision', function (): void {
    $h = engineHarness(new ScriptedStrategy(['00:01' => [long(), long()]]));
    $h->minute('100', '100', '100', '100');

    expect(array_map(fn ($i) => $i->rejection, $h->log->decisions[0]->intents))->toBe([null, 'duplicate_intent'])
        ->and(array_map(fn ($i) => $i->approved, $h->log->decisions[0]->intents))->toBe([true, false]);
});

it('refuses an entry while one is still pending or a position is open', function (): void {
    $h = engineHarness(new ScriptedStrategy(['00:01' => [long()], '00:02' => [long()]]));
    $h->minute('100', '100', '100', '100');
    $h->minute('100', '101', '99.5', '101');

    expect($h->log->decisions[1]->intents[0]->rejection)->toBe('position_open');
});

it('refuses exits and stop moves without a position', function (): void {
    $btc = Symbol::parse('BTC/USDT');
    $h = engineHarness(new ScriptedStrategy(['00:01' => [new Close($btc, 'nothing'), new MoveStop($btc, Price::of('90'), 'nothing')]]));
    $h->minute('100', '100', '100', '100');

    expect(array_map(fn ($i) => $i->rejection, $h->log->decisions[0]->intents))->toBe(['no_position', 'no_position']);
});

it('only observes during warmup', function (): void {
    $strategy = new ScriptedStrategy(['00:01' => [long()], '00:02' => [long()]]);
    $h = engineHarness($strategy, tradingFrom: '2026-10-01 00:02:00');
    $h->minute('100', '100', '100', '100');

    expect($h->log->decisions[0]->intents[0]->rejection)->toBe('warmup')
        ->and($h->venue->openOrders())->toBe([]);

    $h->minute('100', '100', '100', '100');

    expect($h->log->decisions[1]->intents[0]->approved)->toBeTrue()
        ->and($strategy->seen)->toHaveCount(2);
});

it('decides only when the strategy\'s own candle closes, and shows it its position', function (): void {
    $strategy = new ScriptedStrategy(['00:05' => [long('95', null)]], Timeframe::FiveMinutes);
    $h = engineHarness($strategy);

    foreach (range(1, 10) as $minute) {
        $h->minute('100', '100', '100', '100');
    }

    expect(array_map(fn (StrategyContext $c): string => $c->time->format('H:i'), $strategy->seen))->toBe(['00:05', '00:10'])
        ->and($strategy->seen[0]->candle->timeframe)->toBe(Timeframe::FiveMinutes)
        ->and($strategy->seen[0]->position)->toBeNull()
        ->and((string) $strategy->seen[1]->position?->quantity)->toBe('9.99')
        ->and((string) $strategy->seen[1]->position?->stop)->toBe('95')
        ->and(array_map(fn ($o) => $o->type, $h->venue->openOrders()))->toBe([OrderType::StopMarket]);
});

it('puts all free quote into a benchmark position and leaves it unprotected', function (): void {
    $h = engineHarness(new ScriptedStrategy(['00:01' => [long('', null)]]), RiskPolicy::benchmark());
    $h->minute('100', '100', '100', '100');
    $h->minute('100', '100', '100', '100');

    expect($h->log->decisions[0]->intents[0]->quoteQuantity)->toBe('10000')
        ->and((string) $h->engine->ledger()->balance(Asset::of('BTC')))->toBe('99.9')
        ->and($h->usdt())->toBe('0')
        ->and($h->venue->openOrders())->toBe([]);
});

it('stops when the ledger and the venue disagree', function (): void {
    $h = engineHarness(new ScriptedStrategy([]), wrap: fn (SimulatedVenue $venue): Venue => new OverReportingVenue($venue));

    $h->minute('100', '100', '100', '100');
})->throws(ReconciliationMismatch::class, 'The ledger holds 10000 USDT; the venue holds 10001.');

it('refuses the benchmark profile outside backtests', function (): void {
    $clock = new SimClock(CarbonImmutable::parse(ENGINE_START, 'UTC'));
    new Engine(
        EngineMode::Paper,
        new SimulatedVenue($clock, new FeeSchedule(Decimal::zero(), Decimal::zero()), new CostModel(1, Decimal::zero(), Decimal::zero(), Decimal::zero()), [], []),
        new Ledger(Asset::of('USDT'), []),
        RiskPolicy::benchmark(),
        new InMemoryDecisionLog,
        [],
        [],
        $clock->now(),
    );
})->throws(InvalidArgumentException::class, 'backtests only');

it('halts entries after the daily loss without closing what is open', function (): void {
    $h = engineHarness(new ScriptedStrategy(['00:01' => [long()]]), policyWith(['daily_loss' => '0.001']));
    $h->minute('100', '100', '100', '100');
    $h->minute('100', '101', '99.5', '101');
    $h->minute('101', '101', '99', '99.2');

    expect($h->events())->toBe(['daily_loss_halt'])
        ->and($h->engine->ledger()->positions())->toHaveCount(1)
        ->and(array_map(fn ($o) => $o->type, $h->venue->openOrders()))->toBe([OrderType::StopMarket, OrderType::Limit]);
});

it('counts the positions and open risk of every strategy, pending entries included', function (): void {
    $first = new ScriptedStrategy(['00:01' => [long()]], name: 'first');
    $sameMinute = new ScriptedStrategy(['00:01' => [long()]], name: 'second');
    $nextMinute = new ScriptedStrategy(['00:02' => [long()]], name: 'third');

    $byCount = engineHarness([$first, $sameMinute, $nextMinute], policyWith(['max_open_positions' => 1]));
    $byCount->minute('100', '100', '100', '100');
    $byCount->minute('100', '100', '100', '100');

    $byRisk = engineHarness([
        new ScriptedStrategy(['00:01' => [long()]], name: 'first'),
        new ScriptedStrategy(['00:01' => [long()]], name: 'second'),
        new ScriptedStrategy(['00:02' => [long()]], name: 'third'),
    ], policyWith(['total_open_risk' => '0.009']));
    $byRisk->minute('100', '100', '100', '100');
    $byRisk->minute('100', '100', '100', '100');

    $rejections = fn (EngineHarness $h): array => array_values(array_map(
        fn ($d) => $d->intents[0]->rejection,
        array_filter($h->log->decisions, fn ($d) => $d->intents !== []),
    ));

    expect($rejections($byCount))->toBe([null, 'max_positions', 'max_positions'])
        ->and($rejections($byRisk))->toBe([null, 'open_risk', 'open_risk']);
});

it('journals a protection the venue refuses', function (): void {
    // 1 BTC risking 50 USDT down to 50: the stop's notional of 49.95 is under the minimum of 75
    $h = engineHarness(new ScriptedStrategy(['00:01' => [long('50', null)]]), minNotional: '75');
    $h->minute('100', '100', '100', '100');
    $h->minute('100', '100', '100', '100');

    expect($h->events())->toBe(['venue_rejected'])
        ->and($h->log->events[0]->detail)->toContain('NOTIONAL');
});

it('notices a balance the venue has and the ledger does not', function (): void {
    $h = engineHarness(new ScriptedStrategy([]), wrap: fn (SimulatedVenue $venue): Venue => new class($venue) extends ForwardingVenue
    {
        public function balances(): array
        {
            return [...$this->inner->balances(), 'BNB' => new Balance(Asset::of('BNB'), Decimal::of('0.5'), Decimal::zero())];
        }
    });

    $h->minute('100', '100', '100', '100');
})->throws(ReconciliationMismatch::class, 'The ledger holds 0 BNB; the venue holds 0.5.');

it('notices a balance the ledger has and the venue does not report', function (): void {
    $h = engineHarness(new ScriptedStrategy([]), wrap: fn (SimulatedVenue $venue): Venue => new class($venue) extends ForwardingVenue
    {
        public function balances(): array
        {
            return array_diff_key($this->inner->balances(), ['USDT' => true]);
        }
    });

    $h->minute('100', '100', '100', '100');
})->throws(ReconciliationMismatch::class, 'The ledger holds 10000 USDT; the venue holds 0.');

it('writes off a position that is already too small to sell when the account is flattened', function (): void {
    // 50 / (100 - 0.01) = 0.50005, 0.5 BTC after the lot step; at 80 the 0.4995 left are worth 39.96, under the
    // minimum notional of 45, and the account is 0.1 % under its peak.
    $h = engineHarness(new ScriptedStrategy(['00:01' => [long('0.01', null)]]), policyWith([
        'drawdown_halt' => '0.0005', 'drawdown_flatten' => '0.0008',
    ]), minNotional: '45');
    $h->minute('100', '100', '100', '100');
    $h->minute('100', '100', '100', '100');
    $h->minute('80', '80', '80', '80');

    expect($h->events())->toBe(['venue_rejected', 'drawdown_halt', 'drawdown_flatten', 'dust'])
        ->and($h->engine->ledger()->positions())->toBe([])
        ->and((string) $h->engine->ledger()->dust(Asset::of('BTC')))->toBe('0.4995');
});

it('does not call a strategy before its own warmup, yet keeps its candles aligned', function (): void {
    $strategy = new ScriptedStrategy([], Timeframe::FiveMinutes);
    $h = engineHarness($strategy, decideFrom: '2026-10-01 00:05:00');

    foreach (range(1, 10) as $minute) {
        $h->minute('100', '100', '100', '100');
    }

    expect(array_map(fn (StrategyContext $c): string => $c->time->format('H:i'), $strategy->seen))->toBe(['00:10'])
        ->and($h->log->quietDecisions)->toBe(1);
});
