<?php

declare(strict_types=1);

namespace App\Strategy\Dsl;

use App\Strategy\Dsl\Ast\Argument;
use App\Strategy\Dsl\Ast\Binary;
use App\Strategy\Dsl\Ast\BoolLiteral;
use App\Strategy\Dsl\Ast\Call;
use App\Strategy\Dsl\Ast\DurationLiteral;
use App\Strategy\Dsl\Ast\Identifier;
use App\Strategy\Dsl\Ast\Lookback;
use App\Strategy\Dsl\Ast\Node;
use App\Strategy\Dsl\Ast\NumberLiteral;
use App\Strategy\Dsl\Ast\OnTimeframe;
use App\Strategy\Dsl\Ast\Parameter;
use App\Strategy\Dsl\Ast\PercentLiteral;
use App\Strategy\Dsl\Ast\RMultipleLiteral;
use App\Strategy\Dsl\Ast\TextLiteral;
use App\Strategy\Dsl\Ast\Unary;
use LogicException;

/** Writes an expression back as DSL text, with parentheses only where precedence needs them. */
final class DslWriter
{
    private const array PRECEDENCE = [
        'or' => 1, 'and' => 2, 'not' => 3,
        '>' => 4, '>=' => 4, '<' => 4, '<=' => 4, '==' => 4, '!=' => 4, 'crosses_above' => 4, 'crosses_below' => 4,
        '+' => 5, '-' => 5, '*' => 6, '/' => 6, 'neg' => 7,
    ];

    public static function write(Node $node): string
    {
        return match (true) {
            $node instanceof NumberLiteral => (string) $node->value,
            $node instanceof PercentLiteral => $node->text,
            $node instanceof RMultipleLiteral => "{$node->value}R",
            $node instanceof DurationLiteral => $node->unit === 'bars' ? "{$node->amount} bars" : "{$node->amount}{$node->unit}",
            $node instanceof TextLiteral => "\"{$node->value}\"",
            $node instanceof BoolLiteral => $node->value ? 'true' : 'false',
            $node instanceof Identifier => $node->name,
            $node instanceof Parameter => "\${$node->name}",
            $node instanceof Unary => $node->operator === 'not'
                ? 'not '.self::operand($node->operand, 3, false)
                : '-'.self::operand($node->operand, 7, false),
            $node instanceof Binary => self::operand($node->left, self::PRECEDENCE[$node->operator], false)
                ." {$node->operator} ".self::operand($node->right, self::PRECEDENCE[$node->operator], true),
            $node instanceof Lookback => self::operand($node->series, 8, false)."[{$node->bars}]",
            $node instanceof OnTimeframe => self::operand($node->expression, 8, false)." on {$node->timeframe->value}",
            $node instanceof Call => $node->name.'('.implode(', ', array_map(
                fn (Argument $argument): string => ($argument->name === null ? '' : "{$argument->name}: ").self::write($argument->value),
                $node->arguments,
            )).')'.($node->field === null ? '' : ".{$node->field}"),
            default => throw new LogicException('Unknown node '.$node::class.'.'),
        };
    }

    /** $node inside an operator of precedence $outer, parenthesized when it binds looser (or equal, on the right). */
    private static function operand(Node $node, int $outer, bool $right): string
    {
        $inner = match (true) {
            $node instanceof Binary => self::PRECEDENCE[$node->operator],
            $node instanceof Unary => self::PRECEDENCE[$node->operator],
            $node instanceof OnTimeframe, $node instanceof Lookback => 8,
            default => 9,
        };
        $text = self::write($node);

        return $inner < $outer || ($right && $inner === $outer && $inner !== 8) ? "({$text})" : $text;
    }
}
