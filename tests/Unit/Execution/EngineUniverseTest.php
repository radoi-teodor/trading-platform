<?php

declare(strict_types=1);

use App\Execution\Data\DecisionRecord;
use App\Execution\Data\EngineMode;
use App\Execution\Data\FeeSchedule;
use App\Execution\Deployments\SizedStrategy;
use App\Execution\Engine;
use App\Execution\Journal\InMemoryDecisionLog;
use App\Execution\Venues\CostModel;
use App\Execution\Venues\SimulatedVenue;
use App\Market\Data\Candle;
use App\Market\Data\InstrumentRules;
use App\Market\Data\MinuteCandles;
use App\Market\Data\RulesHistory;
use App\Portfolio\Ledger;
use App\Risk\RiskPolicy;
use App\Strategy\Contracts\CrossSectionalStrategy;
use App\Strategy\Contracts\Strategy;
use App\Strategy\Data\Close;
use App\Strategy\Data\Intent;
use App\Strategy\Data\OpenShort;
use App\Strategy\Data\StrategyContext;
use App\Support\Asset;
use App\Support\Clock\SimClock;
use App\Support\Number\Decimal;
use App\Support\Number\Money;
use App\Support\Number\Price;
use App\Support\Side;
use App\Support\Symbol;
use App\Support\Timeframe;
use Carbon\CarbonImmutable;
use Tests\Support\Policies;
use Tests\Support\ScriptedStrategy;
use Tests\Support\ScriptedUniverseStrategy;

const UNIVERSE_START = '2026-10-01 00:00:00';
const UNIVERSE_SYMBOLS = ['AAA/USDT:USDT', 'BBB/USDT:USDT', 'CCC/USDT:USDT'];

final class UniverseEngineHarness
{
    public int $minute = 0;

    public function __construct(public SimClock $clock, public SimulatedVenue $venue, public Engine $engine, public InMemoryDecisionLog $log) {}

    /**
     * Feeds one flat minute of every instrument at 100, or at the price given for it; null leaves the instrument out.
     *
     * @param  array<string, ?string>  $prices  by base asset
     */
    public function minute(array $prices = []): void
    {
        $time = CarbonImmutable::parse(UNIVERSE_START, 'UTC')->addMinutes($this->minute);
        $this->minute++;
        $this->clock->advanceTo($time->addMinute());
        $candles = [];

        foreach (UNIVERSE_SYMBOLS as $symbol) {
            $base = substr($symbol, 0, 3);
            $price = array_key_exists($base, $prices) ? $prices[$base] : '100';

            if ($price === null) {
                continue;
            }

            $candles[$symbol] = Candle::fromStrings($time, Timeframe::OneMinute, $price, $price, $price, $price, '1000', '0', 1, '0');
            $this->venue->match(Symbol::parse($symbol), $candles[$symbol]);
        }

        $this->engine->handle(new MinuteCandles($time->addMinute(), $candles));
    }

    /** @return list<string> every decision with intents, as "H:i BASE type verdict" */
    public function decisions(): array
    {
        $lines = [];

        foreach ($this->log->decisions as $decision) {
            foreach ($decision->intents as $intent) {
                $lines[] = sprintf('%s %s %s %s', $decision->time->format('H:i'), $decision->symbol->base, $intent->type, $intent->rejection ?? 'ok');
            }
        }

        return $lines;
    }
}

/** @param list<Strategy> $strategies */
function universeEngine(array $strategies, ?RiskPolicy $policy = null): UniverseEngineHarness
{
    $clock = new SimClock(CarbonImmutable::parse(UNIVERSE_START, 'UTC'));
    $rules = [];

    foreach (UNIVERSE_SYMBOLS as $symbol) {
        $rules[$symbol] = new RulesHistory(Symbol::parse($symbol), [new InstrumentRules(
            CarbonImmutable::parse('2026-01-01', 'UTC'), Decimal::of('0.1'), Decimal::of('0.001'), Decimal::of('0.001'), null, Decimal::of('100'),
        )]);
    }

    $venue = new SimulatedVenue(
        $clock,
        new FeeSchedule(Decimal::of('0.001'), Decimal::of('0.001')),
        new CostModel(1, Decimal::zero(), Decimal::zero(), Decimal::of('0.1')),
        $rules,
        [Money::of('0', Asset::of('USDT'))],
        perpFees: new FeeSchedule(Decimal::of('0.0002'), Decimal::of('0.0005')),
        maintenanceRate: Decimal::of('0.01'),
        futuresWallet: Decimal::of('10000'),
    );
    $log = new InMemoryDecisionLog;
    $engine = new Engine(
        EngineMode::Backtest,
        $venue,
        new Ledger(Asset::of('USDT'), [Money::of('0', Asset::of('USDT'))], Decimal::of('10000')),
        $policy ?? Policies::account(),
        $log,
        $strategies,
        $rules,
        CarbonImmutable::parse(UNIVERSE_START, 'UTC'),
    );

    return new UniverseEngineHarness($clock, $venue, $engine, $log);
}

/** @param array<string, list<Intent>> $script */
function scriptedUniverse(array $script): ScriptedUniverseStrategy
{
    return new ScriptedUniverseStrategy($script, array_map(Symbol::parse(...), UNIVERSE_SYMBOLS));
}

function universeShort(string $base): OpenShort
{
    return new OpenShort(Symbol::parse("{$base}/USDT:USDT"), Price::of('102'), null, "short {$base}");
}

function universeClose(string $base): Close
{
    return new Close(Symbol::parse("{$base}/USDT:USDT"), 'exit');
}

it('gives a universe strategy every candle that closed at one moment in one call, each with its position (D-92)', function (): void {
    $strategy = scriptedUniverse(['00:01' => [universeShort('BBB')]]);
    $h = universeEngine([$strategy]);
    $h->minute();
    $h->minute();
    $h->minute();

    $seen = fn (int $call): array => array_map(
        fn (StrategyContext $c): string => sprintf('%s %s %s', $c->time->format('H:i'), $c->symbol->base, $c->position === null ? 'flat' : $c->position->side->value),
        $strategy->calls[$call],
    );

    expect($strategy->calls)->toHaveCount(3)
        ->and($seen(0))->toBe(['00:01 AAA flat', '00:01 BBB flat', '00:01 CCC flat'])
        ->and($seen(2))->toBe(['00:03 AAA flat', '00:03 BBB '.Side::Sell->value, '00:03 CCC flat'])
        ->and($h->decisions())->toBe(['00:01 BBB open_short ok'])
        ->and($h->log->quietDecisions)->toBe(8);
});

it('acts on a batch\'s closes at once and holds its entries a minute, so the freed position goes to the best ranked (D-92)', function (): void {
    $strategy = scriptedUniverse([
        '00:01' => [universeShort('AAA')],
        '00:03' => [universeShort('BBB'), universeClose('AAA'), universeShort('CCC')],
    ]);
    $h = universeEngine([$strategy], Policies::account(['max_open_positions' => 1]));
    $h->minute();
    $h->minute();
    $h->minute();

    expect($h->decisions())->toBe(['00:01 AAA open_short ok', '00:03 AAA close ok']);

    $h->minute();

    expect($h->decisions())->toBe([
        '00:01 AAA open_short ok', '00:03 AAA close ok', '00:04 BBB open_short ok', '00:04 CCC open_short max_positions',
    ])->and(array_map(fn (DecisionRecord $d): string => $d->close, $h->log->decisions))->toBe(['100', '100', '100', '100']);

    $h->minute();

    expect(array_keys($h->venue->positions()))->toBe(['BBB/USDT:USDT']);
});

it('submits the entries of a batch that closes nothing in the same minute, in the order the strategy gave (D-92)', function (): void {
    $h = universeEngine(
        [scriptedUniverse(['00:01' => [universeShort('BBB'), universeShort('AAA')]])],
        Policies::account(['max_open_positions' => 1]),
    );
    $h->minute();

    expect($h->decisions())->toBe(['00:01 BBB open_short ok', '00:01 AAA open_short max_positions']);
});

it('does not hold entries for a close that was refused (D-92)', function (): void {
    $h = universeEngine([scriptedUniverse(['00:01' => [universeClose('AAA'), universeShort('BBB')]])]);
    $h->minute();

    expect($h->decisions())->toBe(['00:01 AAA close no_position', '00:01 BBB open_short ok']);
});

it('refuses a second entry for the same instrument in one batch (D-92)', function (): void {
    $h = universeEngine([scriptedUniverse(['00:01' => [universeShort('BBB'), universeShort('BBB')]])]);
    $h->minute();

    expect($h->decisions())->toBe(['00:01 BBB open_short ok', '00:01 BBB open_short duplicate_intent']);
});

it('calls a universe strategy only when one of its candles closed (D-92)', function (): void {
    $strategy = new ScriptedUniverseStrategy([], array_map(Symbol::parse(...), UNIVERSE_SYMBOLS), Timeframe::FiveMinutes);
    $h = universeEngine([$strategy]);

    for ($i = 0; $i < 9; $i++) {
        $h->minute();
    }

    expect($strategy->calls)->toHaveCount(1)
        ->and(array_map(fn (StrategyContext $c): string => (string) $c->symbol->base, $strategy->calls[0]))->toBe(['AAA', 'BBB', 'CCC'])
        ->and($strategy->calls[0][0]->time->format('H:i'))->toBe('00:05');
});

it('keeps a sized universe strategy a universe strategy, with its limits scaled (D-83, D-92)', function (): void {
    $policy = Policies::account();
    $inner = scriptedUniverse(['00:01' => [universeShort('AAA')]]);
    $sized = SizedStrategy::of($inner, Decimal::of('0.5'), null, $policy);
    $h = universeEngine([$sized]);
    $h->minute();

    expect($sized)->toBeInstanceOf(CrossSectionalStrategy::class)
        ->and((string) $sized->limits()->riskPerTrade)->toBe((string) $policy->riskPerTrade->times(Decimal::of('0.5')))
        ->and($inner->calls)->toHaveCount(1)
        ->and($h->decisions())->toBe(['00:01 AAA open_short ok'])
        ->and(SizedStrategy::of(new ScriptedStrategy([]), Decimal::of('0.5'), null, $policy))->not->toBeInstanceOf(CrossSectionalStrategy::class);
});
