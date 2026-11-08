<?php

declare(strict_types=1);

use App\Strategy\Dsl\Ast\Call;
use App\Strategy\Dsl\DslError;
use App\Strategy\Dsl\DslWriter;
use App\Strategy\Dsl\Functions\Group;
use App\Strategy\Dsl\Parser;
use App\Strategy\Dsl\Scope;
use App\Strategy\Dsl\TypeChecker;
use App\Strategy\Dsl\Types\Kind;
use App\Strategy\Dsl\Types\Type;
use App\Support\Timeframe;

mutates(TypeChecker::class, DslWriter::class, Scope::class);

function dslScope(bool $perpetual = false): Scope
{
    return new Scope(Timeframe::FourHours, $perpetual, ['fast' => Type::of(Kind::Number), 'band' => Type::of(Kind::Percent), 'k' => Type::of(Kind::Number)], ['fast' => true]);
}

it('types expressions', function (string $source, string $type, bool $perpetual): void {
    $result = (new TypeChecker)->check(Parser::parse($source), dslScope($perpetual)->allowing(Group::Exit, Group::Order));

    expect($result->describe())->toBe($type);
})->with([
    'price' => ['close', 'a price series', false],
    'indicator of a price' => ['ema(20)', 'a price series', false],
    'indicator of a volume' => ['sma(20, src: volume)', 'a volume series', false],
    'oscillator' => ['rsi(14)', 'a value series', false],
    'field' => ['bb(20, 2).upper', 'a price series', false],
    'width is a rate' => ['bb(20).width', 'a rate series', false],
    'price times number' => ['close * 1.02', 'a price series', false],
    'price over price' => ['close / close[1]', 'a value series', false],
    'price minus price' => ['high - low', 'a price series', false],
    'number arithmetic' => ['2 * 3', 'a number', false],
    'comparison' => ['close > ema(200) on 1d', 'a condition', false],
    'cross' => ['ema($fast) crosses_above ema(50)', 'a condition', false],
    'logic' => ['adx(14) > 20 and not in_position', 'a condition', false],
    'call without arguments' => ['vwap() > close', 'a condition', false],
    'funding vs percent' => ['funding_rate_avg(9) > 0.01%', 'a condition', true],
    'stop level' => ['level(lowest(low, 10))', 'a stop level', false],
    'trail' => ['chandelier(22, 3)', 'a trailing stop', false],
    'order' => ['limit(offset: -0.1%, expiry: 2 bars)', 'an order type', false],
    'market order' => ['market', 'an order type', false],
    'percent param' => ['-$band', 'a percent', false],
    'none' => ['none', 'none', false],
    'lookback of a condition' => ['(close > open)[1]', 'a condition', false],
]);

it('refuses expressions without meaning, saying why', function (string $source, string $message, bool $perpetual = false): void {
    expect(fn () => (new TypeChecker)->check(Parser::parse($source, 22, 15), dslScope($perpetual)))->toThrow(DslError::class, $message);
})->with([
    'price vs percent' => ['close > 2%', 'line 22, column 15: cannot compare a price series with a percent (close > 2%). Did you mean close > close[1] * 1.02 ?'],
    'price vs oscillator' => ['close > rsi(14)', 'line 22, column 15: cannot compare a price series with a value series (close > rsi(14)).'],
    'price plus volume' => ['close + volume', 'line 22, column 15: cannot add a price series and a volume series (close + volume).'],
    'unknown function' => ['emma(20)', 'line 22, column 15: emma is not a function of the DSL. Did you mean ema?'],
    'refused function' => ['open_interest > 0', 'line 22, column 15: open_interest has no data yet'],
    'perpetual only' => ['funding_rate > 0%', 'line 22, column 15: funding_rate exists only for perpetuals; this strategy trades spot.'],
    'exit level elsewhere' => ['close > level(low)', 'line 22, column 23: level is an exit level: use it in stop_loss or trailing.'],
    'order elsewhere' => ['market', 'line 22, column 15: market is an order type: use it in entries.<side>.order.'],
    'missing argument' => ['sma()', 'line 22, column 15: sma needs n, a whole number of candles.'],
    'count not whole' => ['ema(2.5)', 'line 22, column 19: n of ema must be a whole number of candles from 1, such as ema(20).'],
    'count from a non-whole parameter' => ['ema($k)', 'line 22, column 19: n of ema must be a whole number of candles from 1, such as ema(20).'],
    'unknown argument' => ['rsi(14, length: 3)', 'line 22, column 23: rsi has no argument length. Its arguments: n, src.'],
    'too many arguments' => ['atr(14, 3)', 'line 22, column 23: atr takes 1 argument.'],
    'positional after named' => ['rsi(n: 14, close)', 'line 22, column 26: in rsi(...), positional arguments come before named ones.'],
    'given twice' => ['rsi(14, n: 3)', 'line 22, column 23: n of rsi is given twice.'],
    'missing field' => ['bb(20) > close', 'line 22, column 15: bb(...) has several values: pick one with .upper, .middle, .lower, .width.'],
    'field on single' => ['ema(20).upper', 'line 22, column 15: ema(...) has a single value; remove .upper.'],
    'function without parens' => ['vwap > close', 'line 22, column 15: vwap is a function: write vwap(...).'],
    'name with parens' => ['close()', 'line 22, column 15: close takes no arguments: write close.'],
    'agent functions are gone (D-64)' => ['regime() > 0', 'line 22, column 15: regime is not a function of the DSL.'],
    'risk flags are gone (D-64)' => ['risk_flag(1)', 'line 22, column 15: risk_flag is not a function of the DSL.'],
    'logic on numbers' => ['close and true', 'line 22, column 15: and needs a condition, but close is a price series.'],
    'undeclared parameter' => ['ema($slow)', 'line 22, column 19: $slow is not declared under params.'],
    'shorter timeframe' => ['close on 1h', 'line 22, column 15: on 1h reads a shorter timeframe than the strategy\'s 4h; use 4h or longer.'],
    'wrong argument kind' => ['vwap(anchor: 3h)', 'line 22, column 28: anchor of vwap must be a timeframe, not 3h.'],
    'not on numbers' => ['not close', 'line 22, column 19: not needs a condition, but close is a price series.'],
    'negating a condition' => ['-(close > open)', 'line 22, column 15: - applies to numbers, not to a condition.'],
]);

it('resolves arguments with their defaults for the compiler', function (): void {
    $checker = new TypeChecker;
    $node = Parser::parse('macd(signal: 5).hist');
    $checker->check($node, dslScope());

    expect($node)->toBeInstanceOf(Call::class)
        ->and(array_map(DslWriter::write(...), $node instanceof Call ? $checker->arguments($node) : []))->toBe(['signal' => '5', 'fast' => '12', 'slow' => '26']);
});

it('writes expressions back as DSL with only the parentheses they need', function (string $source, string $written): void {
    expect(DslWriter::write(Parser::parse($source)))->toBe($written);
})->with([
    ['close   >  ema( 200 )  on 1d', 'close > ema(200) on 1d'],
    ['(close > ema(200)) on 1d', '(close > ema(200)) on 1d'],
    ['a - (b - c)', 'a - (b - c)'],
    ['(a - b) - c', 'a - b - c'],
    ['(a + b) * c', '(a + b) * c'],
    ['-(a + b)', '-(a + b)'],
    ['not (a > b and c)', 'not (a > b and c)'],
    ['macd(fast: 12).hist[1]', 'macd(fast: 12).hist[1]'],
    ['time_stop(60 bars)', 'time_stop(60 bars)'],
    ['3R', '3R'],
]);
