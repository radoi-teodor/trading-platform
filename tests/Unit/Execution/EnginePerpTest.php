<?php

declare(strict_types=1);

use App\Execution\Contracts\Venue;
use App\Execution\Data\EngineMode;
use App\Execution\Data\EngineRecord;
use App\Execution\Data\FeeSchedule;
use App\Execution\Data\OrderType;
use App\Execution\Data\VenuePosition;
use App\Execution\Engine;
use App\Execution\Journal\InMemoryDecisionLog;
use App\Execution\ReconciliationMismatch;
use App\Execution\Venues\CostModel;
use App\Execution\Venues\SimulatedVenue;
use App\Market\Data\Candle;
use App\Market\Data\FundingRate;
use App\Market\Data\InstrumentRules;
use App\Market\Data\MinuteCandles;
use App\Market\Data\RulesHistory;
use App\Portfolio\Data\ExitReason;
use App\Portfolio\Ledger;
use App\Risk\RiskPolicy;
use App\Strategy\Contracts\Strategy;
use App\Strategy\Data\Close;
use App\Strategy\Data\OpenCarry;
use App\Strategy\Data\OpenShort;
use App\Support\Asset;
use App\Support\Clock\SimClock;
use App\Support\Number\Decimal;
use App\Support\Number\Money;
use App\Support\Number\Price;
use App\Support\Side;
use App\Support\Symbol;
use App\Support\Timeframe;
use Carbon\CarbonImmutable;
use Tests\Support\ForwardingVenue;
use Tests\Support\Policies;
use Tests\Support\ScriptedStrategy;

const PERP_START = '2026-10-01 00:00:00';

final class PerpEngineHarness
{
    public int $minute = 0;

    public function __construct(public SimClock $clock, public SimulatedVenue $venue, public Engine $engine, public InMemoryDecisionLog $log) {}

    /** Feeds one minute of spot and perpetual candles at the same prices, with funding when given. */
    public function minute(string $open, string $high, string $low, string $close, ?string $fundingRate = null): Decimal
    {
        $time = CarbonImmutable::parse(PERP_START, 'UTC')->addMinutes($this->minute);
        $make = fn (): Candle => Candle::fromStrings($time, Timeframe::OneMinute, $open, $high, $low, $close, '1000', '0', 1, '0');
        $candles = ['BTC/USDT' => $make(), 'BTC/USDT:USDT' => $make()];
        $this->minute++;
        $this->clock->advanceTo($time->addMinute());
        $funding = [];

        foreach ($candles as $symbol => $candle) {
            $this->venue->match(Symbol::parse($symbol), $candle);
        }

        if ($fundingRate !== null) {
            $funding['BTC/USDT:USDT'] = new FundingRate($time->addMinute(), Decimal::of($fundingRate), 8, null);
            $this->venue->settleFunding(Symbol::parse('BTC/USDT:USDT'), $funding['BTC/USDT:USDT'], Price::of($close));
        }

        return $this->engine->handle(new MinuteCandles($time->addMinute(), $candles, $funding));
    }

    /** @return list<string> */
    public function events(): array
    {
        return array_map(fn (EngineRecord $r): string => $r->kind, $this->log->events);
    }

    /** @return list<string> */
    public function openOrders(): array
    {
        return array_map(fn ($o): string => "{$o->type->value} {$o->side->value} {$o->quantity} ".($o->stopPrice ?? ''), $this->venue->openOrders());
    }
}

/**
 * @param  list<Strategy>  $strategies
 * @param  (Closure(SimulatedVenue): Venue)|null  $wrap
 */
function perpEngine(array $strategies, ?RiskPolicy $policy = null, string $spot = '0', string $futures = '10000', ?Closure $wrap = null): PerpEngineHarness
{
    $clock = new SimClock(CarbonImmutable::parse(PERP_START, 'UTC'));
    $rule = fn (string $symbol, string $tick, string $step, string $notional): RulesHistory => new RulesHistory(Symbol::parse($symbol), [new InstrumentRules(
        CarbonImmutable::parse('2026-01-01', 'UTC'), Decimal::of($tick), Decimal::of($step), Decimal::of($step), null, Decimal::of($notional),
    )]);
    $rules = ['BTC/USDT' => $rule('BTC/USDT', '0.01', '0.00001', '5'), 'BTC/USDT:USDT' => $rule('BTC/USDT:USDT', '0.1', '0.001', '100')];
    $venue = new SimulatedVenue(
        $clock,
        new FeeSchedule(Decimal::of('0.001'), Decimal::of('0.001')),
        new CostModel(1, Decimal::zero(), Decimal::zero(), Decimal::of('0.1')),
        $rules,
        [Money::of($spot, Asset::of('USDT'))],
        perpFees: new FeeSchedule(Decimal::of('0.0002'), Decimal::of('0.0005')),
        maintenanceRate: Decimal::of('0.01'),
        futuresWallet: Decimal::of($futures),
    );
    $log = new InMemoryDecisionLog;
    $engine = new Engine(
        EngineMode::Backtest,
        $wrap === null ? $venue : $wrap($venue),
        new Ledger(Asset::of('USDT'), [Money::of($spot, Asset::of('USDT'))], Decimal::of($futures)),
        $policy ?? Policies::account(),
        $log,
        $strategies,
        $rules,
        CarbonImmutable::parse(PERP_START, 'UTC'),
    );

    return new PerpEngineHarness($clock, $venue, $engine, $log);
}

function perpShort(string $stop = '102', ?string $target = '94'): OpenShort
{
    return new OpenShort(Symbol::parse('BTC/USDT:USDT'), $stop === '' ? null : Price::of($stop), $target === null ? null : Price::of($target), 'test short');
}

/** @param array<string, list<App\Strategy\Data\Intent>> $script */
function perpOnly(array $script, string $name = 'scripted'): ScriptedStrategy
{
    return new ScriptedStrategy($script, Timeframe::OneMinute, $name, [Symbol::parse('BTC/USDT:USDT')]);
}

it('shorts a perpetual, protects it with a reduce-only stop and take-profit, and books the target', function (): void {
    $h = perpEngine([perpOnly(['00:01' => [perpShort()]])]);
    $h->minute('100', '100', '100', '100');

    // 50 / (102 - 100) = 25, 2500 notional at the cap; margin 1250 at 2x
    expect($h->log->decisions[0]->intents[0]->quantity)->toBe('25');

    $h->minute('100', '100', '100', '100');

    expect($h->openOrders())->toBe(['stop_market buy 25 102', 'take_profit_market buy 25 94'])
        ->and($h->venue->positions()['BTC/USDT:USDT']->side)->toBe(Side::Sell);

    $h->minute('99', '99', '93.9', '94');
    $h->minute('94', '94', '94', '94');
    $trip = $h->engine->ledger()->roundTrips()[0];

    // 10000 - 1.25 + 25 x 6 - 1.175
    expect($trip->side)->toBe(Side::Sell)
        ->and($trip->exitReason)->toBe(ExitReason::Target)
        ->and((string) $trip->netProfit)->toBe('147.575')
        ->and((string) $h->engine->ledger()->futuresWallet())->toBe('10147.575')
        ->and($h->openOrders())->toBe([]);
});

it('books funding on the short and shows the strategy its side and the last funding rate', function (): void {
    $strategy = perpOnly(['00:01' => [perpShort()]]);
    $h = perpEngine([$strategy]);
    $h->minute('100', '100', '100', '100');
    $h->minute('100', '100', '100', '100', fundingRate: '0.0001');
    $h->minute('100', '100', '100', '100');

    // a short receives 25 x 100 x 0.0001 = 0.25
    expect((string) $h->engine->ledger()->perpPosition((string) $strategy->identity()->key(), Symbol::parse('BTC/USDT:USDT'))?->funding)->toBe('0.25')
        ->and($strategy->seen[2]->position?->side)->toBe(Side::Sell)
        ->and((string) $strategy->seen[2]->funding?->rate)->toBe('0.0001')
        ->and($strategy->seen[0]->funding)->toBeNull();
});

it('closes a short on a signal with a reduce-only buy and cancels its protection', function (): void {
    $h = perpEngine([perpOnly(['00:01' => [perpShort()], '00:02' => [new Close(Symbol::parse('BTC/USDT:USDT'), 'exit')]])]);
    $h->minute('100', '100', '100', '100');
    $h->minute('100', '100', '100', '100');

    expect($h->openOrders())->toBe(['market buy 25 ']);

    $h->minute('98', '98', '98', '98');
    $h->minute('98', '98', '98', '98');

    expect($h->engine->ledger()->roundTrips()[0]->exitReason)->toBe(ExitReason::Signal)
        ->and($h->venue->positions())->toBe([]);
});

it('books a liquidation the venue reports', function (): void {
    $h = perpEngine([perpOnly(['00:01' => [perpShort('', null)]])], RiskPolicy::benchmark());
    $h->minute('100', '100', '100', '100');
    $h->minute('100', '100', '100', '100');
    // 99 at 1x: liquidation at (9900 + 9900) / (99 x 1.01) = 198.01...
    $h->minute('150', '200', '150', '190');
    $h->minute('190', '190', '190', '190');

    expect($h->events())->toBe(['liquidation'])
        ->and($h->engine->ledger()->roundTrips()[0]->exitReason)->toBe(ExitReason::Liquidation)
        ->and($h->engine->ledger()->perpPositions())->toBe([]);
});

it('opens both legs of a carry, keeps them unprotected, and closes them', function (): void {
    $carry = new ScriptedStrategy([
        '00:01' => [new OpenCarry(Symbol::parse('BTC/USDT'), Symbol::parse('BTC/USDT:USDT'), Decimal::of('0.30'), 2, Decimal::of('0.5'), 'carry')],
        '00:03' => [new Close(Symbol::parse('BTC/USDT'), 'exit spot'), new Close(Symbol::parse('BTC/USDT:USDT'), 'exit perp')],
    ], Timeframe::OneMinute, 'carry', [Symbol::parse('BTC/USDT:USDT'), Symbol::parse('BTC/USDT')]);
    $h = perpEngine([$carry], spot: '5000', futures: '5000');
    $h->minute('100', '100', '100', '100');
    $h->minute('100', '100', '100', '100');
    $key = $carry->identity()->key();

    // 25 % of 10000 at 100 = 25 per leg; the perpetual leg at floor(2 / 1.5) = 1x
    expect((string) $h->engine->ledger()->position($key, Symbol::parse('BTC/USDT'))?->quantity)->toBe('24.975')
        ->and((string) $h->engine->ledger()->perpPosition($key, Symbol::parse('BTC/USDT:USDT'))?->quantity)->toBe('25')
        ->and($h->venue->positions()['BTC/USDT:USDT']->leverage)->toBe(1)
        ->and($h->openOrders())->toBe([]);

    $h->minute('101', '101', '101', '101');
    $h->minute('101', '101', '101', '101');
    $h->minute('101', '101', '101', '101');

    expect($h->engine->ledger()->perpPositions())->toBe([])
        ->and($h->engine->ledger()->positions())->toBe([])
        ->and(count($h->engine->ledger()->roundTrips()))->toBe(2);
});

it('refuses two strategies on the same perpetual', function (): void {
    perpEngine([perpOnly([], 'one'), perpOnly([], 'two')]);
})->throws(InvalidArgumentException::class, 'BTC/USDT:USDT');

it('stops when the venue\'s position differs from the ledger\'s', function (): void {
    $h = perpEngine([perpOnly(['00:01' => [perpShort()]])], wrap: fn (SimulatedVenue $venue): Venue => new class($venue) extends ForwardingVenue
    {
        public function positions(): array
        {
            return array_map(fn (VenuePosition $p): VenuePosition => new VenuePosition($p->symbol, $p->side, $p->quantity->plus(Decimal::of('0.001')), $p->entryPrice, $p->margin, $p->leverage), $this->inner->positions());
        }
    });
    $h->minute('100', '100', '100', '100');
    $h->minute('100', '100', '100', '100');
})->throws(ReconciliationMismatch::class, 'BTC/USDT:USDT');

it('flattens perpetual positions with reduce-only orders', function (): void {
    $h = perpEngine([perpOnly(['00:01' => [perpShort('110', null)]])], Policies::account(['drawdown_halt' => '0.001', 'drawdown_flatten' => '0.002']));
    $h->minute('100', '100', '100', '100');
    $h->minute('100', '100', '100', '100');
    // 10000 - 0.25 fee - 5 x 5 = 9974.75, 0.25 % under the peak
    $h->minute('100', '105', '100', '105');

    expect($h->events())->toBe(['drawdown_halt', 'drawdown_flatten'])
        ->and(array_map(fn ($o) => $o->type, $h->venue->openOrders()))->toBe([OrderType::Market]);

    $h->minute('102', '102', '102', '102');
    $h->minute('102', '102', '102', '102');

    expect($h->engine->ledger()->roundTrips()[0]->exitReason)->toBe(ExitReason::Flatten);
});
