<?php

declare(strict_types=1);

use App\Market\Data\Candle;
use App\Strategy\Contracts\CrossSectionalStrategy;
use App\Strategy\Contracts\LimitedStrategy;
use App\Strategy\Data\Close;
use App\Strategy\Data\Intent;
use App\Strategy\Data\MoveStop;
use App\Strategy\Data\OpenLong;
use App\Strategy\Data\OpenShort;
use App\Strategy\Data\PositionView;
use App\Strategy\Data\StrategyContext;
use App\Strategy\Data\Subscription;
use App\Strategy\Data\UniverseSchedule;
use App\Strategy\Dsl\Compiler\CompiledStrategy;
use App\Strategy\Dsl\Compiler\CrossSection;
use App\Strategy\Dsl\Compiler\UniverseStrategy;
use App\Strategy\Dsl\Document\DocumentChecker;
use App\Strategy\Dsl\Document\PolicyLimits;
use App\Strategy\Dsl\Document\StrategySpec;
use App\Strategy\Dsl\DslError;
use App\Support\Number\Decimal;
use App\Support\Side;
use App\Support\Symbol;
use App\Support\Timeframe;
use Carbon\CarbonImmutable;
use Tests\Support\KnownInstruments;

mutates(UniverseStrategy::class, CrossSection::class, UniverseSchedule::class);

const XS_FIVE = ['AAA/USDT:USDT', 'BBB/USDT:USDT', 'CCC/USDT:USDT', 'DDD/USDT:USDT', 'EEE/USDT:USDT'];

const XS_MOMENTUM = <<<'YAML'
    entries:
      long:
        when: top(2, roc(1))
      short:
        when: bottom(1, roc(1))
    exits:
      stop_loss: 5%
    YAML;

function universeSpec(string $body, string $timeframe = '1h'): StrategySpec
{
    $yaml = "strategy: xs\ntitle: Test\nfamily: trend\nhypothesis: Test.\nfails_when: Never.\nuniverse: { rule: top, n: 5, by: quote_volume_30d }\ntimeframe: {$timeframe}\n{$body}";
    $result = DocumentChecker::check($yaml, new KnownInstruments([]), new PolicyLimits(Decimal::of('0.005'), Decimal::of('0.25'), 6));

    return $result->spec ?? throw new RuntimeException(implode("\n", array_map(fn (DslError $e): string => $e->getMessage(), $result->errors)));
}

/** @param array<string, list<string>> $months */
function universeStrategy(string $body, array $months = ['2026-10' => XS_FIVE], string $timeframe = '1h'): CrossSectionalStrategy
{
    $strategy = CompiledStrategy::fromSpec(universeSpec($body, $timeframe), [], 1, new UniverseSchedule($months));

    return $strategy instanceof CrossSectionalStrategy ? $strategy : throw new RuntimeException('Not a universe strategy.');
}

/**
 * The candles that close at one moment: closes by coin (AAA is AAA/USDT:USDT), opened at $open.
 *
 * @param  array<string, string>  $closes
 * @param  array<string, PositionView>  $positions
 * @return list<StrategyContext>
 */
function xsCloses(string $open, array $closes, array $positions = [], Timeframe $timeframe = Timeframe::OneHour): array
{
    $contexts = [];

    foreach ($closes as $coin => $close) {
        $candle = Candle::fromStrings(CarbonImmutable::parse($open, 'UTC'), $timeframe, $close, $close, $close, $close, '1', '1', 1, '0');
        $contexts[] = new StrategyContext($candle->endsAt(), Symbol::parse("{$coin}/USDT:USDT"), $candle, $positions[$coin] ?? null);
    }

    return $contexts;
}

/**
 * @param  list<Intent>  $intents
 * @return list<string>
 */
function xsIntents(array $intents): array
{
    return array_map(fn (Intent $intent): string => match (true) {
        $intent instanceof OpenLong => 'long '.$intent->symbol->base,
        $intent instanceof OpenShort => 'short '.$intent->symbol->base,
        $intent instanceof Close => 'close '.$intent->symbol->base.' '.$intent->reason,
        $intent instanceof MoveStop => 'stop '.$intent->symbol->base,
        default => 'other',
    }, $intents);
}

function xsLong(string $entry, string $openedAt): PositionView
{
    return new PositionView(Decimal::of(1), Decimal::of($entry), null, CarbonImmutable::parse($openedAt, 'UTC'), Side::Buy);
}

it('ranks the members that closed and enters the best ranked first, long and short', function (): void {
    $strategy = universeStrategy(XS_MOMENTUM);
    $flat = ['AAA' => '100', 'BBB' => '100', 'CCC' => '100', 'DDD' => '100', 'EEE' => '100'];

    expect($strategy->onCloses(xsCloses('2026-10-10 00:00', $flat)))->toBe([]);

    $intents = $strategy->onCloses(xsCloses('2026-10-10 01:00', ['AAA' => '101', 'BBB' => '104', 'CCC' => '97', 'DDD' => '102', 'EEE' => '100']));
    $first = $intents[0] ?? null;

    expect(xsIntents($intents))->toBe(['long BBB', 'short CCC', 'long DDD'])
        ->and($first instanceof OpenLong && $first->stop?->value->equals(Decimal::of('98.8')))->toBeTrue()
        ->and($first?->reason())->toBe('entries.long.when');
});

it('breaks a tie by symbol, whatever the order the candles come in', function (): void {
    $strategy = universeStrategy(XS_MOMENTUM);
    $strategy->onCloses(xsCloses('2026-10-10 00:00', ['EEE' => '100', 'DDD' => '100', 'CCC' => '100', 'BBB' => '100', 'AAA' => '100']));

    $intents = $strategy->onCloses(xsCloses('2026-10-10 01:00', ['EEE' => '99', 'CCC' => '102', 'BBB' => '102', 'DDD' => '99', 'AAA' => '102']));

    expect(xsIntents($intents))->toBe(['long AAA', 'short EEE', 'long BBB']);
});

it('sends closes before entries and manages a position by its own rank', function (): void {
    $strategy = universeStrategy(XS_MOMENTUM."\n  when: rank(roc(1)) > 2\n");
    $strategy->onCloses(xsCloses('2026-10-10 00:00', ['AAA' => '100', 'BBB' => '100', 'CCC' => '100', 'DDD' => '100', 'EEE' => '100']));

    $intents = $strategy->onCloses(xsCloses(
        '2026-10-10 01:00',
        ['AAA' => '101', 'BBB' => '104', 'CCC' => '97', 'DDD' => '102', 'EEE' => '100'],
        ['AAA' => xsLong('100', '2026-10-10 01:00'), 'DDD' => xsLong('100', '2026-10-10 01:00')],
    ));

    expect(xsIntents($intents))->toBe(['close AAA exits.when', 'long BBB', 'short CCC']);
});

it('ranks only the members of the month and closes the position of one that left', function (): void {
    $months = ['2026-10' => ['AAA/USDT:USDT', 'BBB/USDT:USDT', 'CCC/USDT:USDT'], '2026-11' => ['AAA/USDT:USDT', 'BBB/USDT:USDT', 'DDD/USDT:USDT']];
    $strategy = universeStrategy("entries:\n  long:\n    when: top(1, roc(1))\nexits:\n  stop_loss: 5%\n", $months);
    $strategy->onCloses(xsCloses('2026-10-31 21:00', ['AAA' => '100', 'BBB' => '100', 'CCC' => '100', 'DDD' => '100']));

    $october = $strategy->onCloses(xsCloses('2026-10-31 22:00', ['AAA' => '101', 'BBB' => '102', 'CCC' => '105', 'DDD' => '110']));
    $november = $strategy->onCloses(xsCloses(
        '2026-10-31 23:00',
        ['AAA' => '102', 'BBB' => '103', 'CCC' => '120', 'DDD' => '121'],
        ['CCC' => xsLong('105', '2026-10-31 23:00')],
    ));

    expect(xsIntents($october))->toBe(['long CCC'])
        ->and(xsIntents($november))->toBe(['close CCC universe.left', 'long DDD']);
});

it('ranks a member only once it has its history, and again from nothing after a gap', function (): void {
    $strategy = universeStrategy("entries:\n  long:\n    when: top(1, close)\nexits:\n  stop_loss: 5%\n  when: roc(3) < 0\n", ['2026-10' => ['AAA/USDT:USDT', 'BBB/USDT:USDT']]);
    $lines = [];

    foreach (range(0, 8) as $hour) {
        $closes = ['AAA' => '100'] + ($hour >= 3 && $hour !== 7 ? ['BBB' => '200'] : []);
        $lines[] = implode(',', xsIntents($strategy->onCloses(xsCloses("2026-10-10 0{$hour}:00", $closes))));
    }

    expect($strategy->warmupBars())->toBe(4)
        ->and($lines)->toBe(['', '', '', 'long AAA', 'long AAA', 'long AAA', 'long BBB', 'long AAA', 'long AAA']);
});

it('orders entries by the ranking of the filters when the condition ranks nothing, else by symbol', function (string $filters, array $expected): void {
    $strategy = universeStrategy("{$filters}entries:\n  long:\n    when: roc(1) > 0\nexits:\n  stop_loss: 5%\n");
    $strategy->onCloses(xsCloses('2026-10-10 00:00', ['AAA' => '100', 'BBB' => '100', 'CCC' => '100', 'DDD' => '100', 'EEE' => '100']));

    $intents = $strategy->onCloses(xsCloses('2026-10-10 01:00', ['AAA' => '101', 'BBB' => '104', 'CCC' => '97', 'DDD' => '102', 'EEE' => '100']));

    expect(xsIntents($intents))->toBe($expected);
})->with([
    'a ranking in filters' => ["filters:\n  - top(3, roc(1))\n", ['long BBB', 'long DDD', 'long AAA']],
    'the weakest first' => ["filters:\n  - bottom(4, roc(1))\n", ['long AAA', 'long DDD']],
    'no ranking' => ['', ['long AAA', 'long BBB', 'long DDD']],
]);

it('gives a percentile from 0 to 1 and counts the members ranked', function (): void {
    $strategy = universeStrategy("entries:\n  long:\n    when: percentile(roc(1)) >= 75% and count == 5\nexits:\n  stop_loss: 5%\n");
    $strategy->onCloses(xsCloses('2026-10-10 00:00', ['AAA' => '100', 'BBB' => '100', 'CCC' => '100', 'DDD' => '100', 'EEE' => '100']));

    $five = $strategy->onCloses(xsCloses('2026-10-10 01:00', ['AAA' => '101', 'BBB' => '104', 'CCC' => '97', 'DDD' => '102', 'EEE' => '100']));
    $four = $strategy->onCloses(xsCloses('2026-10-10 02:00', ['AAA' => '102', 'BBB' => '108', 'CCC' => '96', 'DDD' => '104']));

    expect(xsIntents($five))->toBe(['long BBB', 'long DDD'])
        ->and(xsIntents($four))->toBe([]);
});

it('ranks a value of a longer timeframe from its last closed candle', function (): void {
    $strategy = universeStrategy("entries:\n  long:\n    when: top(1, close on 4h)\nexits:\n  stop_loss: 5%\n", ['2026-10' => ['AAA/USDT:USDT', 'BBB/USDT:USDT']]);
    $lines = [];

    foreach (range(0, 3) as $hour) {
        $contexts = xsCloses("2026-10-10 0{$hour}:00", ['AAA' => '100', 'BBB' => '90']);
        $contexts = $hour === 3 ? [...$contexts, ...xsCloses('2026-10-10 00:00', ['BBB' => '60', 'AAA' => '50'], [], Timeframe::FourHours)] : $contexts;
        $lines[] = implode(',', xsIntents($strategy->onCloses($contexts)));
    }

    expect($lines)->toBe(['', '', '', 'long BBB'])
        ->and($strategy->warmupBars())->toBe(1)
        ->and(array_map(fn (Subscription $s): string => "{$s->symbol} {$s->timeframe->value}", $strategy->subscriptions()))
        ->toBe(['AAA/USDT:USDT 4h', 'AAA/USDT:USDT 1h', 'BBB/USDT:USDT 4h', 'BBB/USDT:USDT 1h']);
});

it('is a strategy with the document\'s limits, deciding only for a whole moment', function (): void {
    $strategy = universeStrategy(XS_MOMENTUM."\nsizing:\n  risk_per_trade: 0.3%\n");
    $one = xsCloses('2026-10-10 00:00', ['AAA' => '100']);
    $mixed = [...$one, ...xsCloses('2026-10-10 01:00', ['BBB' => '100'])];

    expect($strategy)->toBeInstanceOf(LimitedStrategy::class)
        ->and($strategy instanceof LimitedStrategy ? (string) $strategy->limits()->riskPerTrade : null)->toBe('0.003')
        ->and($strategy->identity()->name)->toBe('xs')
        ->and($strategy->warmupBars())->toBe(2)
        ->and($strategy->onCloses([]))->toBe([])
        ->and(fn () => $strategy->onCloses($mixed))->toThrow(LogicException::class, 'xs was given candles of two moments, 2026-10-10 01:00 and 2026-10-10 02:00: a universe is decided one moment at a time.')
        ->and(fn () => $strategy->onCandle($one[0]))->toThrow(LogicException::class, 'xs trades a universe: it decides for all its members at once, through onCloses.');
});

it('needs the members of its universe to be built', function (): void {
    expect(fn () => CompiledStrategy::fromSpec(universeSpec(XS_MOMENTUM)))
        ->toThrow(InvalidArgumentException::class, "xs trades a universe: it needs the universe's members to run.");
});

it('knows the members month by month', function (): void {
    $schedule = new UniverseSchedule(['2026-11' => ['BBB/USDT:USDT', 'AAA/USDT:USDT'], '2026-10' => ['CCC/USDT:USDT', 'AAA/USDT:USDT']]);
    $at = fn (string $time): CarbonImmutable => CarbonImmutable::parse($time, 'UTC');

    expect($schedule->symbols())->toBe(['AAA/USDT:USDT', 'BBB/USDT:USDT', 'CCC/USDT:USDT'])
        ->and($schedule->isMember('CCC/USDT:USDT', $at('2026-10-31 23:59:59')))->toBeTrue()
        ->and($schedule->isMember('CCC/USDT:USDT', $at('2026-11-01 00:00:00')))->toBeFalse()
        ->and($schedule->isMember('BBB/USDT:USDT', $at('2026-11-01 00:00:00')))->toBeTrue()
        ->and($schedule->isMember('BBB/USDT:USDT', CarbonImmutable::parse('2026-11-01 01:00:00', 'Europe/Bucharest')))->toBeFalse()
        ->and($schedule->isMember('AAA/USDT:USDT', $at('2026-12-01 00:00:00')))->toBeFalse()
        ->and(UniverseSchedule::none()->symbols())->toBe([]);
});

it('ranks decimals from the highest, ties by member, and leaves out a member without a value', function (): void {
    $section = new CrossSection;
    $section->enter('BBB', [Decimal::of('2'), null]);
    $section->enter('AAA', [Decimal::of('2'), Decimal::of('-1')]);
    $section->enter('CCC', [Decimal::of('10'), Decimal::of('-0.5')]);
    $section->enter('DDD', [null, null]);

    expect([$section->rank(0, 'CCC'), $section->rank(0, 'AAA'), $section->rank(0, 'BBB'), $section->rank(0, 'DDD')])->toBe([1, 2, 3, null])
        ->and([$section->rank(1, 'CCC'), $section->rank(1, 'AAA'), $section->rank(1, 'BBB')])->toBe([1, 2, null])
        ->and([$section->ranked(0), $section->ranked(1), $section->ranked(7), $section->count()])->toBe([3, 2, 0, 4])
        ->and([$section->has('DDD'), $section->has('EEE'), $section->rank(0, 'EEE')])->toBe([true, false, null]);

    $section->begin();

    expect([$section->count(), $section->ranked(0), $section->rank(0, 'CCC'), $section->has('CCC')])->toBe([0, 0, null, false]);
});
