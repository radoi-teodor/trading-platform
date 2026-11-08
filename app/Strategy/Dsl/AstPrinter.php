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

/** Writes an expression tree as an S-expression, for tests and diagnostics: (> close (on 1d (ema 200))). */
final class AstPrinter
{
    public static function print(Node $node): string
    {
        return match (true) {
            $node instanceof NumberLiteral => (string) $node->value,
            $node instanceof PercentLiteral => $node->text,
            $node instanceof RMultipleLiteral => "{$node->value}R",
            $node instanceof DurationLiteral => "{$node->amount}{$node->unit}",
            $node instanceof TextLiteral => "\"{$node->value}\"",
            $node instanceof BoolLiteral => $node->value ? 'true' : 'false',
            $node instanceof Identifier => $node->name,
            $node instanceof Parameter => "\${$node->name}",
            $node instanceof Unary => "({$node->operator} ".self::print($node->operand).')',
            $node instanceof Binary => "({$node->operator} ".self::print($node->left).' '.self::print($node->right).')',
            $node instanceof Lookback => "([{$node->bars}] ".self::print($node->series).')',
            $node instanceof OnTimeframe => "(on {$node->timeframe->value} ".self::print($node->expression).')',
            $node instanceof Call => '('.implode(' ', [
                $node->name,
                ...array_map(fn (Argument $argument): string => ($argument->name === null ? '' : "{$argument->name}:").self::print($argument->value), $node->arguments),
                ...($node->field === null ? [] : [".{$node->field}"]),
            ]).')',
            default => throw new LogicException('Unknown node '.$node::class.'.'),
        };
    }
}
