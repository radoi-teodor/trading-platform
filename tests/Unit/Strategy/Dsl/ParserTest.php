<?php

declare(strict_types=1);

use App\Strategy\Dsl\Ast\DurationLiteral;
use App\Strategy\Dsl\Ast\PercentLiteral;
use App\Strategy\Dsl\AstPrinter;
use App\Strategy\Dsl\DslError;
use App\Strategy\Dsl\Lexer;
use App\Strategy\Dsl\Parser;

mutates(Lexer::class, Parser::class, AstPrinter::class);

function sexpr(string $source): string
{
    return AstPrinter::print(Parser::parse($source));
}

it('parses the grammar of docs/strategy/configurator.md with its precedence', function (string $source, string $tree): void {
    expect(sexpr($source))->toBe($tree);
})->with([
    'arithmetic binds tighter than comparison' => ['close > close[1] * 1.02', '(> close (* ([1] close) 1.02))'],
    'multiplication before addition' => ['a + b * c', '(+ a (* b c))'],
    'left associative' => ['a - b - c', '(- (- a b) c)'],
    'division' => ['a / b * c', '(* (/ a b) c)'],
    'parentheses' => ['(a + b) * c', '(* (+ a b) c)'],
    'unary minus' => ['-a * b', '(* (neg a) b)'],
    'and before or' => ['a or b and c', '(or a (and b c))'],
    'not binds one comparison' => ['not a > b and c', '(and (not (> a b)) c)'],
    'on binds the term before it' => ['close > ema(200) on 1d', '(> close (on 1d (ema 200)))'],
    'on over a parenthesized condition' => ['(close > ema(200)) on 1d', '(on 1d (> close (ema 200)))'],
    'lookback then on' => ['close[1] on 4h', '(on 4h ([1] close))'],
    'crosses' => ['ema(20) crosses_above ema(50)', '(crosses_above (ema 20) (ema 50))'],
    'crosses below' => ['ema(20) crosses_below ema(50)', '(crosses_below (ema 20) (ema 50))'],
    'comparisons' => ['a >= b or a <= b or a == b or a != b or a < b', '(or (or (or (or (>= a b) (<= a b)) (== a b)) (!= a b)) (< a b))'],
    'named arguments and a field' => ['macd(fast: 12, slow: 26).hist', '(macd fast:12 slow:26 .hist)'],
    'mixed arguments' => ['rsi(14, src: hl2)', '(rsi 14 src:hl2)'],
    'no arguments' => ['vwap() > close', '(> (vwap) close)'],
    'nested calls' => ['level(lowest(low, 10))', '(level (lowest low 10))'],
    'percent' => ['close > 2%', '(> close 2%)'],
    'negative percent' => ['limit(offset: -0.1%)', '(limit offset:(neg 0.1%))'],
    'durations and bars' => ['limit(offset: 0.1%, expiry: 2h)', '(limit offset:0.1% expiry:2h)'],
    'bars' => ['time_stop(60 bars)', '(time_stop 60bars)'],
    'r multiple' => ['3R', '3R'],
    'booleans' => ['true and not false', '(and true (not false))'],
    'parameters' => ['ema($fast) crosses_above ema($slow)', '(crosses_above (ema $fast) (ema $slow))'],
    'whitespace and newlines' => ["a\n  and\tb", '(and a b)'],
]);

it('keeps the values of literals', function (): void {
    $percent = Parser::parse('0.5%');
    $duration = Parser::parse('8h');
    $bars = Parser::parse('60 bars');

    expect($percent)->toBeInstanceOf(PercentLiteral::class)
        ->and($percent instanceof PercentLiteral ? (string) $percent->fraction : '')->toBe('0.005')
        ->and($duration instanceof DurationLiteral ? [$duration->amount, $duration->unit] : [])->toBe([8, 'h'])
        ->and($bars instanceof DurationLiteral ? [$bars->amount, $bars->unit] : [])->toBe([60, 'bars'])
        ->and(Parser::parse('ema(20)')->position()->column)->toBe(1);
});

it('reports errors with the line and column of the document', function (string $source, string $message): void {
    try {
        Parser::parse($source, line: 18, column: 9);
        $error = 'no error';
    } catch (DslError $failure) {
        $error = $failure->getMessage();
    }

    expect($error)->toBe($message);
})->with([
    'missing operand' => ['close >', 'line 18, column 16: expected a value after >, found the end of the expression.'],
    'unclosed call' => ['ema(20', 'line 18, column 15: expected ) to close the call to ema, found the end of the expression.'],
    'two operators' => ['close > > 2', 'line 18, column 17: expected a value after >, found >.'],
    'chained comparison' => ['a > b > c', 'line 18, column 15: comparisons do not chain. Combine them with and: a > b and b > c.'],
    'index not whole' => ['close[a]', 'line 18, column 15: the index in [ ] must be a whole number of candles back, such as close[1].'],
    'unknown timeframe' => ['close on 2h', 'line 18, column 18: 2h is not a timeframe. Use one of 1m, 5m, 15m, 1h, 4h, 1d, 1w.'],
    'unknown character' => ['close @ 2', 'line 18, column 15: unexpected character @.'],
    'text in quotes' => ['close == "range"', 'line 18, column 18: the language has no text in quotes; compare series and numbers, such as close > ema(20).'],
    'trailing input' => ['close close', 'line 18, column 15: expected the end of the expression or an operator, found close.'],
    'parameter without name' => ['ema($)', 'line 18, column 13: a parameter is written $name, such as $fast.'],
    'field on a non-call' => ['close.upper', 'line 18, column 14: a field such as .upper follows a call, such as bb(20).upper.'],
    'second line' => ["a and\n  b >", 'line 19, column 6: expected a value after >, found the end of the expression.'],
]);

it('starts at line 1, column 1 by default and counts columns from there', function (): void {
    $node = Parser::parse('  close > 2');

    expect([$node->position()->line, $node->position()->column])->toBe([1, 3])
        ->and(AstPrinter::print(Parser::parse('2.5R + 1.5%')))->toBe('(+ 2.5R 1.5%)')
        ->and(AstPrinter::print(Parser::parse('12w')))->toBe('12w');
});
