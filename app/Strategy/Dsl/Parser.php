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
use App\Strategy\Dsl\Ast\Unary;
use App\Support\Number\Decimal;
use App\Support\Timeframe;

/**
 * A recursive-descent parser for the expression grammar of docs/strategy/configurator.md, section 2 (D-51). It
 * checks syntax only; types and names are the type checker's.
 */
final class Parser
{
    private const array COMPARISONS = ['>', '>=', '<', '<=', '==', '!='];

    private const array CROSSES = ['crosses_above', 'crosses_below'];

    private int $index = 0;

    /** @param list<Token> $tokens */
    private function __construct(private readonly array $tokens) {}

    /** Parses one expression that starts at ($line, $column) of its document. */
    public static function parse(string $source, int $line = 1, int $column = 1): Node
    {
        $parser = new self(Lexer::tokenize($source, $line, $column));
        $expression = $parser->orExpression();
        $next = $parser->peek();

        if ($next->is(TokenKind::Dot)) {
            throw new DslError($next->position, 'a field such as .upper follows a call, such as bb(20).upper.');
        }

        if (! $next->is(TokenKind::End)) {
            throw new DslError($next->position, "expected the end of the expression or an operator, found {$next->describe()}.");
        }

        return $expression;
    }

    private function orExpression(): Node
    {
        $left = $this->andExpression();

        while ($this->peek()->is(TokenKind::Keyword, 'or')) {
            $operator = $this->advance();
            $left = new Binary('or', $left, $this->operand($operator, fn (): Node => $this->andExpression()), $left->position());
        }

        return $left;
    }

    private function andExpression(): Node
    {
        $left = $this->notExpression();

        while ($this->peek()->is(TokenKind::Keyword, 'and')) {
            $operator = $this->advance();
            $left = new Binary('and', $left, $this->operand($operator, fn (): Node => $this->notExpression()), $left->position());
        }

        return $left;
    }

    private function notExpression(): Node
    {
        if ($this->peek()->is(TokenKind::Keyword, 'not')) {
            $not = $this->advance();

            return new Unary('not', $this->operand($not, fn (): Node => $this->comparison()), $not->position);
        }

        return $this->comparison();
    }

    private function comparison(): Node
    {
        $left = $this->additive();

        if (! $this->isComparison($this->peek())) {
            return $left;
        }

        $operator = $this->advance();
        $right = $this->operand($operator, fn (): Node => $this->additive());
        $after = $this->peek();

        if ($this->isComparison($after)) {
            throw new DslError($after->position, 'comparisons do not chain. Combine them with and: a > b and b > c.');
        }

        return new Binary($operator->text, $left, $right, $left->position());
    }

    private function additive(): Node
    {
        $left = $this->multiplicative();

        while ($this->peek()->is(TokenKind::Operator, '+') || $this->peek()->is(TokenKind::Operator, '-')) {
            $operator = $this->advance();
            $left = new Binary($operator->text, $left, $this->operand($operator, fn (): Node => $this->multiplicative()), $left->position());
        }

        return $left;
    }

    private function multiplicative(): Node
    {
        $left = $this->unary();

        while ($this->peek()->is(TokenKind::Operator, '*') || $this->peek()->is(TokenKind::Operator, '/')) {
            $operator = $this->advance();
            $left = new Binary($operator->text, $left, $this->operand($operator, fn (): Node => $this->unary()), $left->position());
        }

        return $left;
    }

    private function unary(): Node
    {
        if ($this->peek()->is(TokenKind::Operator, '-')) {
            $minus = $this->advance();

            return new Unary('neg', $this->operand($minus, fn (): Node => $this->postfix()), $minus->position);
        }

        return $this->postfix();
    }

    private function postfix(): Node
    {
        $node = $this->primary();

        if ($this->peek()->is(TokenKind::LeftBracket)) {
            $this->advance();
            $index = $this->peek();

            if (! $index->is(TokenKind::Number) || ! ctype_digit($index->text)) {
                throw new DslError($index->position, 'the index in [ ] must be a whole number of candles back, such as close[1].');
            }

            $this->advance();
            $this->expect(TokenKind::RightBracket, 'expected ] after the index');
            $node = new Lookback($node, (int) $index->text, $node->position());
        }

        if ($this->peek()->is(TokenKind::Keyword, 'on')) {
            $on = $this->advance();
            $frame = $this->peek();
            $timeframe = $frame->is(TokenKind::Duration) ? Timeframe::tryFrom($frame->text) : null;

            if ($timeframe === null) {
                $what = $frame->is(TokenKind::End) ? "a timeframe after {$on->text}" : "{$frame->text} is not a timeframe";

                throw new DslError($frame->position, "{$what}. Use one of ".implode(', ', array_map(fn (Timeframe $t): string => $t->value, Timeframe::cases())).'.');
            }

            $this->advance();
            $node = new OnTimeframe($node, $timeframe, $node->position());
        }

        return $node;
    }

    private function primary(): Node
    {
        $token = $this->peek();

        switch ($token->kind) {
            case TokenKind::Number:
                $this->advance();

                return new NumberLiteral(Decimal::of($token->text), $token->position);
            case TokenKind::Percent:
                $this->advance();
                $number = substr($token->text, 0, -1);

                return new PercentLiteral(Decimal::of($number)->dividedBy(Decimal::of(100), 18), $token->text, $token->position);
            case TokenKind::RMultiple:
                $this->advance();

                return new RMultipleLiteral(Decimal::of(substr($token->text, 0, -1)), $token->position);
            case TokenKind::Duration:
                $this->advance();
                $unit = str_ends_with($token->text, 'bars') ? 'bars' : substr($token->text, -1);

                return new DurationLiteral((int) substr($token->text, 0, -strlen($unit)), $unit, $token->position);
            case TokenKind::Parameter:
                $this->advance();

                return new Parameter($token->text, $token->position);
            case TokenKind::Keyword:
                if ($token->text === 'true' || $token->text === 'false') {
                    $this->advance();

                    return new BoolLiteral($token->text === 'true', $token->position);
                }

                break;
            case TokenKind::LeftParen:
                $this->advance();
                $inner = $this->orExpression();
                $this->expect(TokenKind::RightParen, 'expected ) to close the parenthesis');

                return $inner;
            case TokenKind::Identifier:
                $this->advance();

                return $this->peek()->is(TokenKind::LeftParen) ? $this->call($token) : new Identifier($token->text, $token->position);
            default:
                break;
        }

        throw new DslError($token->position, "expected a value, found {$token->describe()}.");
    }

    private function call(Token $name): Call
    {
        $this->advance();
        $arguments = [];

        if (! $this->peek()->is(TokenKind::RightParen)) {
            do {
                $arguments[] = $this->argument();
            } while ($this->peek()->is(TokenKind::Comma) && $this->advance()->is(TokenKind::Comma));
        }

        $this->expect(TokenKind::RightParen, "expected ) to close the call to {$name->text}");
        $field = null;

        if ($this->peek()->is(TokenKind::Dot)) {
            $this->advance();
            $field = $this->expect(TokenKind::Identifier, 'expected a field name after .')->text;
        }

        return new Call($name->text, $arguments, $field, $name->position);
    }

    private function argument(): Argument
    {
        $token = $this->peek();

        if ($token->is(TokenKind::Identifier) && $this->peek(1)->is(TokenKind::Colon)) {
            $this->advance();
            $this->advance();

            return new Argument($token->text, $this->operand($token, fn (): Node => $this->orExpression()), $token->position);
        }

        $value = $this->orExpression();

        return new Argument(null, $value, $value->position());
    }

    /**
     * The operand that must follow $operator.
     *
     * @param  callable(): Node  $parse
     */
    private function operand(Token $operator, callable $parse): Node
    {
        $next = $this->peek();

        if ($next->is(TokenKind::End) || $next->is(TokenKind::Operator) && $next->text !== '-' || $this->isComparison($next)
            || $next->is(TokenKind::RightParen) || $next->is(TokenKind::Comma)) {
            throw new DslError($next->position, "expected a value after {$operator->text}, found {$next->describe()}.");
        }

        return $parse();
    }

    private function isComparison(Token $token): bool
    {
        return $token->is(TokenKind::Operator) && in_array($token->text, self::COMPARISONS, true)
            || $token->is(TokenKind::Keyword) && in_array($token->text, self::CROSSES, true);
    }

    private function expect(TokenKind $kind, string $message): Token
    {
        $token = $this->peek();

        if (! $token->is($kind)) {
            throw new DslError($token->position, "{$message}, found {$token->describe()}.");
        }

        return $this->advance();
    }

    private function peek(int $ahead = 0): Token
    {
        return $this->tokens[min($this->index + $ahead, count($this->tokens) - 1)];
    }

    private function advance(): Token
    {
        $token = $this->tokens[$this->index];
        $this->index = min($this->index + 1, count($this->tokens) - 1);

        return $token;
    }
}
