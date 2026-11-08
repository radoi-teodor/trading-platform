<?php

declare(strict_types=1);

use App\Market\Data\InstrumentRules;
use App\Risk\Data\AccountView;
use App\Risk\Data\RejectionReason;
use App\Risk\Data\RiskEventKind;
use App\Risk\Data\RiskProfile;
use App\Risk\Data\Verdict;
use App\Risk\RiskEngine;
use App\Risk\RiskPolicy;
use App\Risk\RiskState;
use App\Strategy\Data\MoveStop;
use App\Strategy\Data\OpenCarry;
use App\Strategy\Data\OpenLong;
use App\Strategy\Data\OpenShort;
use App\Support\Number\Decimal;
use App\Support\Number\Price;
use App\Support\Symbol;
use Carbon\CarbonImmutable;
use Tests\Support\Policies;

mutates(RiskEngine::class, RiskPolicy::class, RiskState::class);

function accountPolicy(): RiskPolicy
{
    return Policies::account();
}

function spotRules(): InstrumentRules
{
    return new InstrumentRules(
        CarbonImmutable::parse('2026-01-01', 'UTC'), Decimal::of('0.01'), Decimal::of('0.00001'), Decimal::of('0.00001'),
        Decimal::of('9000'), Decimal::of('5'),
    );
}

function account(string $equity = '10000', ?string $free = null, int $positions = 0, string $openRisk = '0', bool $holds = false, string $margin = '10000', string $gross = '0', string $net = '0'): AccountView
{
    return new AccountView(Decimal::of($equity), Decimal::of($free ?? $equity), $positions, Decimal::of($openRisk), $holds, Decimal::of($margin), Decimal::of($gross), Decimal::of($net));
}

function entry(?string $stop = '57000', ?string $target = null): OpenLong
{
    return new OpenLong(Symbol::parse('BTC/USDT'), $stop === null ? null : Price::of($stop), $target === null ? null : Price::of($target), 'breakout');
}

function riskAt(string $time): CarbonImmutable
{
    return CarbonImmutable::parse($time, 'UTC');
}

function engineFor(RiskPolicy $policy): RiskEngine
{
    return new RiskEngine($policy, new RiskState($policy));
}

it('sizes an entry so that the stop loses the risk per trade', function (): void {
    // 10000 * 0.005 / (60000 - 58000) = 0.025 BTC, 1500 USDT: under the 25 % notional cap
    $verdict = engineFor(accountPolicy())->assessEntry(entry('58000'), account(), spotRules(), Price::of('60000'), riskAt('2026-10-01 12:00'));

    expect($verdict->approved)->toBeTrue()
        ->and((string) $verdict->quantity)->toBe('0.025')
        ->and($verdict->quoteQuantity)->toBeNull()
        ->and((string) $verdict->plannedRisk)->toBe('50')
        ->and($verdict->adjustments)->toBe([]);
});

it('resizes down to the notional cap and to the free cash', function (): void {
    $engine = engineFor(accountPolicy());
    // 50 / 300 = 0.1666 BTC is 9999.99 USDT: capped at 2500 USDT = 0.04166 BTC after the lot step
    $capped = $engine->assessEntry(entry('59700'), account(), spotRules(), Price::of('60000'), riskAt('2026-10-01 12:00'));
    // free cash 1000 / (60000 * 1.01) = 0.01650 BTC
    $poor = $engine->assessEntry(entry('59700'), account(free: '1000'), spotRules(), Price::of('60000'), riskAt('2026-10-01 12:00'));

    expect((string) $capped->quantity)->toBe('0.04166')
        ->and($capped->adjustments)->toBe(['notional_cap'])
        ->and((string) $poor->quantity)->toBe('0.0165')
        ->and($poor->adjustments)->toBe(['notional_cap', 'cash']);
});

it('caps a quantity at the exchange maximum', function (): void {
    $rules = new InstrumentRules(CarbonImmutable::parse('2026-01-01', 'UTC'), Decimal::of('0.0001'), Decimal::of('1'), Decimal::of('1'), Decimal::of('100'), Decimal::of('5'));
    $verdict = engineFor(accountPolicy())->assessEntry(entry('0.9'), account('1000000'), $rules, Price::of('1'), riskAt('2026-10-01 12:00'));

    expect((string) $verdict->quantity)->toBe('100')
        ->and($verdict->adjustments)->toBe(['exchange_maximum']);
});

it('rejects entries the policy does not allow, with a reason', function (Verdict $verdict, RejectionReason $reason): void {
    expect($verdict->approved)->toBeFalse()
        ->and($verdict->rejection)->toBe($reason)
        ->and($verdict->quantity)->toBeNull();
})->with([
    'no stop' => [fn () => engineFor(accountPolicy())->assessEntry(entry(null), account(), spotRules(), Price::of('60000'), riskAt('2026-10-01')), RejectionReason::NoStop],
    'stop above the price' => [fn () => engineFor(accountPolicy())->assessEntry(entry('60000'), account(), spotRules(), Price::of('60000'), riskAt('2026-10-01')), RejectionReason::InvalidStop],
    'target below the price' => [fn () => engineFor(accountPolicy())->assessEntry(entry('59000', '60000'), account(), spotRules(), Price::of('60000'), riskAt('2026-10-01')), RejectionReason::InvalidTarget],
    'already holding' => [fn () => engineFor(accountPolicy())->assessEntry(entry(), account(holds: true), spotRules(), Price::of('60000'), riskAt('2026-10-01')), RejectionReason::PositionOpen],
    'too many positions' => [fn () => engineFor(accountPolicy())->assessEntry(entry(), account(positions: 6), spotRules(), Price::of('60000'), riskAt('2026-10-01')), RejectionReason::MaxPositions],
    'open risk' => [fn () => engineFor(accountPolicy())->assessEntry(entry('58000'), account(openRisk: '251'), spotRules(), Price::of('60000'), riskAt('2026-10-01')), RejectionReason::OpenRisk],
    'below the minimum' => [fn () => engineFor(accountPolicy())->assessEntry(entry('59000'), account(free: '4'), spotRules(), Price::of('60000'), riskAt('2026-10-01')), RejectionReason::BelowMinimum],
]);

it('allows an entry whose open risk reaches the limit exactly', function (): void {
    // 0.025 BTC risking 50 on top of 250: exactly 3 % of 10000
    $verdict = engineFor(accountPolicy())->assessEntry(entry('58000'), account(openRisk: '250'), spotRules(), Price::of('60000'), riskAt('2026-10-01'));

    expect($verdict->approved)->toBeTrue();
});

it('halts entries for the day after the daily loss, until midnight UTC', function (): void {
    $policy = accountPolicy();
    $state = new RiskState($policy);
    $engine = new RiskEngine($policy, $state);

    $state->update(riskAt('2026-10-01 00:00'), Decimal::of('10000'));
    expect($state->update(riskAt('2026-10-01 10:00'), Decimal::of('9801')))->toBe([]);

    $events = $state->update(riskAt('2026-10-01 11:00'), Decimal::of('9800'));

    expect(array_map(fn ($e) => $e->kind, $events))->toBe([RiskEventKind::DailyLossHalt])
        ->and($engine->assessEntry(entry(), account('9800'), spotRules(), Price::of('60000'), riskAt('2026-10-01 23:59'))->rejection)->toBe(RejectionReason::Halted)
        ->and($state->update(riskAt('2026-10-01 12:00'), Decimal::of('9700')))->toBe([]);

    $state->update(riskAt('2026-10-02 00:00'), Decimal::of('9700'));

    expect($state->entryBlock(riskAt('2026-10-02 00:00')))->toBeNull()
        ->and($engine->assessEntry(entry(), account('9700'), spotRules(), Price::of('60000'), riskAt('2026-10-02 00:00'))->approved)->toBeTrue();
});

it('halts entries for good after the weekly loss, measured from Monday', function (): void {
    $state = new RiskState(accountPolicy());

    $state->update(riskAt('2026-10-05 00:00'), Decimal::of('10000')); // a Monday
    $state->update(riskAt('2026-10-06 00:00'), Decimal::of('9850'));
    $state->update(riskAt('2026-10-07 00:00'), Decimal::of('9700'));
    $state->update(riskAt('2026-10-08 00:00'), Decimal::of('9550'));
    $events = $state->update(riskAt('2026-10-09 00:00'), Decimal::of('9500'));

    expect(array_map(fn ($e) => $e->kind, $events))->toBe([RiskEventKind::WeeklyLossHalt])
        ->and($state->entryBlock(riskAt('2026-10-20 00:00')))->toBe('weekly_loss');
});

it('starts a new week on Monday', function (): void {
    $state = new RiskState(accountPolicy());

    $state->update(riskAt('2026-10-09 00:00'), Decimal::of('10000')); // a Friday
    $state->update(riskAt('2026-10-12 00:00'), Decimal::of('9800'));  // Monday: the week starts at 9800
    $events = $state->update(riskAt('2026-10-12 00:01'), Decimal::of('9700'));

    expect($events)->toBe([])
        ->and($state->entryBlock(riskAt('2026-10-12 00:01')))->toBeNull();
});

it('halts at a 10 % drawdown from the peak and flattens at 15 %, once each', function (): void {
    $state = new RiskState(accountPolicy());

    $state->update(riskAt('2026-10-01 00:00'), Decimal::of('10000'));
    $state->update(riskAt('2026-10-02 00:00'), Decimal::of('12000'));
    $state->update(riskAt('2026-10-03 00:00'), Decimal::of('11000'));
    $state->update(riskAt('2026-10-04 00:00'), Decimal::of('10900'));
    $state->update(riskAt('2026-10-05 00:00'), Decimal::of('10850'));
    $halt = $state->update(riskAt('2026-10-06 00:00'), Decimal::of('10800'));
    $blockedAfterHalt = $state->entryBlock(riskAt('2026-10-06 00:00'));
    $again = $state->update(riskAt('2026-10-07 00:00'), Decimal::of('10700'));
    $state->update(riskAt('2026-10-08 00:00'), Decimal::of('10500'));
    $state->update(riskAt('2026-10-09 00:00'), Decimal::of('10300'));
    $flatten = $state->update(riskAt('2026-10-10 00:00'), Decimal::of('10200'));

    expect(array_map(fn ($e) => $e->kind, $halt))->toBe([RiskEventKind::DrawdownHalt])
        ->and($again)->toBe([])
        ->and($blockedAfterHalt)->toBe('drawdown')
        ->and(array_map(fn ($e) => $e->kind, $flatten))->toBe([RiskEventKind::DrawdownFlatten])
        ->and($flatten[0]->time->format('Y-m-d'))->toBe('2026-10-10')
        ->and((string) $flatten[0]->equity)->toBe('10200')
        ->and($state->entryBlock(riskAt('2027-01-01')))->toBe('drawdown')
        ->and((string) $state->peak())->toBe('12000')
        ->and($state->update(riskAt('2026-10-11 00:00'), Decimal::of('9000')))->toBe([]);
});

it('rejects a quantity below the exchange minimum even when its notional is enough', function (): void {
    $rules = new InstrumentRules(CarbonImmutable::parse('2026-01-01', 'UTC'), Decimal::of('0.01'), Decimal::of('0.00001'), Decimal::of('0.1'), null, Decimal::of('5'));

    expect(engineFor(accountPolicy())->assessEntry(entry('58000'), account(), $rules, Price::of('60000'), riskAt('2026-10-01'))->rejection)
        ->toBe(RejectionReason::BelowMinimum);
});

it('never halts the benchmark, even at nothing', function (): void {
    $state = new RiskState(RiskPolicy::benchmark());
    $state->update(riskAt('2026-10-01 00:00'), Decimal::of('10000'));

    expect($state->update(riskAt('2026-10-01 00:01'), Decimal::zero()))->toBe([])
        ->and($state->entryBlock(riskAt('2026-10-01 00:01')))->toBeNull();
});

it('sizes a benchmark entry with all free quote and never halts it', function (): void {
    $policy = RiskPolicy::benchmark();
    $state = new RiskState($policy);
    $engine = new RiskEngine($policy, $state);

    $state->update(riskAt('2026-10-01 00:00'), Decimal::of('10000'));
    $events = $state->update(riskAt('2026-10-01 12:00'), Decimal::of('5000'));
    $verdict = $engine->assessEntry(entry(null), account('5000', '5000'), spotRules(), Price::of('60000'), riskAt('2026-10-01 12:00'));

    expect($policy->profile)->toBe(RiskProfile::Benchmark)
        ->and($events)->toBe([])
        ->and($verdict->approved)->toBeTrue()
        ->and((string) $verdict->quoteQuantity)->toBe('5000')
        ->and($verdict->quantity)->toBeNull()
        ->and($engine->assessEntry(entry(null), account(positions: 1), spotRules(), Price::of('60000'), riskAt('2026-10-01'))->rejection)->toBe(RejectionReason::MaxPositions)
        ->and($engine->assessEntry(entry(null), account(free: '4.99'), spotRules(), Price::of('60000'), riskAt('2026-10-01'))->rejection)->toBe(RejectionReason::BelowMinimum);
});

it('lets a stop move towards the price only', function (): void {
    $engine = engineFor(accountPolicy());
    $move = fn (string $to): MoveStop => new MoveStop(Symbol::parse('BTC/USDT'), Price::of($to), 'trail');

    expect($engine->assessStopMove($move('58000'), Price::of('57000'), Price::of('60000'))->approved)->toBeTrue()
        ->and($engine->assessStopMove($move('56000'), Price::of('57000'), Price::of('60000'))->rejection)->toBe(RejectionReason::StopWidening)
        ->and($engine->assessStopMove($move('60000'), Price::of('57000'), Price::of('60000'))->rejection)->toBe(RejectionReason::InvalidStop)
        ->and($engine->assessStopMove($move('58000'), null, Price::of('60000'))->approved)->toBeTrue();
});

it('refuses an account policy above the ceilings of the risk policy', function (string $key, string|int $value): void {
    Policies::account([$key => $value]);
})->with([
    ['risk_per_trade', '0.0101'],
    ['total_open_risk', '0.051'],
    ['notional_per_instrument', '0.41'],
    ['max_open_positions', 11],
    ['daily_loss', '0.031'],
    ['weekly_loss', '0.071'],
    ['drawdown_halt', '0.151'],
    ['drawdown_flatten', '0.201'],
    ['cash_buffer', '-0.01'],
    ['risk_per_trade', '0'],
    ['max_open_positions', 0],
    ['max_open_positions', '6'],
    ['gross_exposure', '2.01'],
    ['net_exposure', '1.01'],
    ['perpetual_leverage', 4],
    ['perpetual_leverage', 0],
    ['carry_extra_margin', '0.49'],
])->throws(InvalidArgumentException::class);

it('names the bounds of the number of positions', function (): void {
    Policies::account(['max_open_positions' => 11]);
})->throws(InvalidArgumentException::class, 'Risk limit max_open_positions must be between 1 and 10.');

it('accepts limits exactly at the ceilings', function (): void {
    $policy = Policies::account([
        'risk_per_trade' => '0.01', 'total_open_risk' => '0.05', 'notional_per_instrument' => '0.40',
        'max_open_positions' => 10, 'daily_loss' => '0.03', 'weekly_loss' => '0.07', 'drawdown_halt' => '0.15',
        'drawdown_flatten' => '0.20', 'cash_buffer' => '0', 'gross_exposure' => '2.00', 'net_exposure' => '1.00',
        'perpetual_leverage' => 3, 'carry_extra_margin' => '0.50',
    ]);
    $single = Policies::account(['max_open_positions' => 1]);

    expect($policy->profile)->toBe(RiskProfile::Account)
        ->and($policy->maxOpenPositions)->toBe(10)
        ->and($single->maxOpenPositions)->toBe(1);
});

function perpRules(): InstrumentRules
{
    return new InstrumentRules(CarbonImmutable::parse('2026-01-01', 'UTC'), Decimal::of('0.1'), Decimal::of('0.001'), Decimal::of('0.001'), Decimal::of('1000'), Decimal::of('100'));
}

function short(?string $stop = '102', ?string $target = null, string $symbol = 'BTC/USDT:USDT'): OpenShort
{
    return new OpenShort(Symbol::parse($symbol), $stop === null ? null : Price::of($stop), $target === null ? null : Price::of($target), 'test');
}

it('sizes a short by its stop above the price, at the policy leverage', function (): void {
    // 50 / (102 - 100) = 25, notional 2500 at the cap; margin 1250 at 2x
    $verdict = engineFor(accountPolicy())->assessEntry(short(), account(), perpRules(), Price::of('100'), riskAt('2026-10-01'));

    expect($verdict->approved)->toBeTrue()
        ->and((string) $verdict->quantity)->toBe('25')
        ->and($verdict->leverage)->toBe(2)
        ->and((string) $verdict->plannedRisk)->toBe('50');
});

it('resizes a perpetual entry to the margin the USD-M wallet can give', function (): void {
    // 500 / (100 / 2 * 1.01) = 9.90099..., 9.9 after the lot step
    $verdict = engineFor(accountPolicy())->assessEntry(short(), account(margin: '500'), perpRules(), Price::of('100'), riskAt('2026-10-01'));

    expect((string) $verdict->quantity)->toBe('9.9')
        ->and($verdict->adjustments)->toBe(['cash']);
});

it('rejects perpetual entries the policy does not allow, with a reason', function (Verdict $verdict, RejectionReason $reason): void {
    expect($verdict->approved)->toBeFalse()
        ->and($verdict->rejection)->toBe($reason);
})->with([
    'a short on spot' => [fn () => engineFor(accountPolicy())->assessEntry(short(symbol: 'BTC/USDT'), account(), spotRules(), Price::of('100'), riskAt('2026-10-01')), RejectionReason::SpotShort],
    'a short stop below the price' => [fn () => engineFor(accountPolicy())->assessEntry(short('99'), account(), perpRules(), Price::of('100'), riskAt('2026-10-01')), RejectionReason::InvalidStop],
    'a short target above the price' => [fn () => engineFor(accountPolicy())->assessEntry(short('102', '101'), account(), perpRules(), Price::of('100'), riskAt('2026-10-01')), RejectionReason::InvalidTarget],
    // liquidation at 3750 / 25.25 = 148.5..., 48.5 away; 3 x 20 = 60 is further
    'liquidation closer than three stops' => [fn () => engineFor(accountPolicy())->assessEntry(short('120'), account(), perpRules(), Price::of('100'), riskAt('2026-10-01')), RejectionReason::LiquidationTooClose],
    'gross exposure' => [fn () => engineFor(accountPolicy())->assessEntry(short(), account(gross: '13000'), perpRules(), Price::of('100'), riskAt('2026-10-01')), RejectionReason::GrossExposure],
    'net exposure' => [fn () => engineFor(accountPolicy())->assessEntry(short(), account(net: '-9000'), perpRules(), Price::of('100'), riskAt('2026-10-01')), RejectionReason::NetExposure],
]);

it('lets an entry that reduces the net exposure through', function (): void {
    $verdict = engineFor(accountPolicy())->assessEntry(new OpenLong(Symbol::parse('BTC/USDT:USDT'), Price::of('98'), null, 'test'), account(net: '-9000'), perpRules(), Price::of('100'), riskAt('2026-10-01'));

    expect($verdict->approved)->toBeTrue()
        ->and($verdict->leverage)->toBe(2);
});

it('sizes a benchmark perpetual at 99 % of the free wallet and 1x', function (): void {
    $verdict = engineFor(RiskPolicy::benchmark())->assessEntry(short(null), account(margin: '10000'), perpRules(), Price::of('100'), riskAt('2026-10-01'));

    expect((string) $verdict->quantity)->toBe('99')
        ->and($verdict->leverage)->toBe(1);
});

it('sizes a carry pair from its template, the notional cap and the margin buffer', function (): void {
    $carry = new OpenCarry(Symbol::parse('BTC/USDT'), Symbol::parse('BTC/USDT:USDT'), Decimal::of('0.30'), 2, Decimal::of('0.5'), 'carry');
    // 25 % of 10000 at 100 = 25; leverage floor(2 / 1.5) = 1
    $verdict = engineFor(accountPolicy())->assessCarry($carry, account(free: '5000', margin: '5000'), spotRules(), perpRules(), Price::of('100'), Price::of('100.2'), riskAt('2026-10-01'));
    $poor = engineFor(accountPolicy())->assessCarry($carry, account(free: '1000', margin: '5000'), spotRules(), perpRules(), Price::of('100'), Price::of('100.2'), riskAt('2026-10-01'));
    $crowded = engineFor(accountPolicy())->assessCarry($carry, account(gross: '11000'), spotRules(), perpRules(), Price::of('100'), Price::of('100.2'), riskAt('2026-10-01'));
    $held = engineFor(accountPolicy())->assessCarry($carry, account(holds: true), spotRules(), perpRules(), Price::of('100'), Price::of('100.2'), riskAt('2026-10-01'));

    expect((string) $verdict->quantity)->toBe('25')
        ->and($verdict->leverage)->toBe(1)
        ->and($verdict->plannedRisk)->toBeNull()
        // 1000 / (100 * 1.01) = 9.90..., 9.9 after the perpetual's lot step
        ->and((string) $poor->quantity)->toBe('9.9')
        ->and($poor->adjustments)->toBe(['cash'])
        ->and($crowded->rejection)->toBe(RejectionReason::GrossExposure)
        ->and($held->rejection)->toBe(RejectionReason::PositionOpen);
});
