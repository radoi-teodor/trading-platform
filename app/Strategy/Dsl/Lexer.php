<?php

declare(strict_types=1);

namespace App\Strategy\Dsl;

/**
 * Splits one DSL expression into tokens. Positions are those of the document: the expression starts at
 * ($line, $column); later lines start at column 1.
 */
final class Lexer
{
    public const array KEYWORDS = ['and', 'or', 'not', 'on', 'crosses_above', 'crosses_below', 'true', 'false'];

    private const array OPERATORS = ['>=', '<=', '==', '!=', '>', '<', '+', '-', '*', '/'];

    private const array PUNCTUATION = [
        '(' => TokenKind::LeftParen, ')' => TokenKind::RightParen, '[' => TokenKind::LeftBracket,
        ']' => TokenKind::RightBracket, ',' => TokenKind::Comma, ':' => TokenKind::Colon, '.' => TokenKind::Dot,
    ];

    private int $offset = 0;

    private int $line;

    private int $lineStart;

    private function __construct(private readonly string $source, int $line, private readonly int $firstColumn)
    {
        $this->line = $line;
        $this->lineStart = 0;
    }

    /** @return list<Token> the tokens, ending with an End token */
    public static function tokenize(string $source, int $line = 1, int $column = 1): array
    {
        $lexer = new self($source, $line, $column);
        $tokens = [];

        do {
            $token = $lexer->next();
            $tokens[] = $token;
        } while ($token->kind !== TokenKind::End);

        return $tokens;
    }

    private function next(): Token
    {
        $this->skipWhitespace();
        $at = $this->position();

        if ($this->offset >= strlen($this->source)) {
            return new Token(TokenKind::End, '', $at);
        }

        $rest = substr($this->source, $this->offset);
        $char = $rest[0];

        if (ctype_digit($char)) {
            return $this->number($rest, $at);
        }

        if ($char === '"') {
            $end = strpos($rest, '"', 1);

            if ($end === false) {
                throw new DslError($at, 'the text starting here has no closing quote.');
            }

            return $this->take(TokenKind::Text, substr($rest, 1, $end - 1), $end + 1, $at);
        }

        if ($char === '$') {
            if (preg_match('/^\$([a-z_][a-z0-9_]*)/', $rest, $match) !== 1) {
                throw new DslError($at, 'a parameter is written $name, such as $fast.');
            }

            return $this->take(TokenKind::Parameter, $match[1], strlen($match[0]), $at);
        }

        if (preg_match('/^[a-z_][a-z0-9_]*/i', $rest, $match) === 1) {
            $kind = in_array($match[0], self::KEYWORDS, true) ? TokenKind::Keyword : TokenKind::Identifier;

            return $this->take($kind, $match[0], strlen($match[0]), $at);
        }

        foreach (self::OPERATORS as $operator) {
            if (str_starts_with($rest, $operator)) {
                return $this->take(TokenKind::Operator, $operator, strlen($operator), $at);
            }
        }

        if (isset(self::PUNCTUATION[$char])) {
            return $this->take(self::PUNCTUATION[$char], $char, 1, $at);
        }

        throw new DslError($at, "unexpected character {$char}.");
    }

    /** A number, or a percent (2%), an R multiple (3R), a duration (8h, 60 bars) written with one. */
    private function number(string $rest, Position $at): Token
    {
        $number = preg_match('/^\d+(\.\d+)?/', $rest, $match) === 1 ? $match[0] : $rest[0];
        $after = substr($rest, strlen($number));

        if (str_starts_with($after, '%')) {
            return $this->take(TokenKind::Percent, $number.'%', strlen($number) + 1, $at);
        }

        if (preg_match('/^(R|m|h|d|w)(?![A-Za-z0-9_])/', $after, $unit) === 1) {
            return $this->take($unit[1] === 'R' ? TokenKind::RMultiple : TokenKind::Duration, $number.$unit[1], strlen($number) + 1, $at);
        }

        if (preg_match('/^\s+bars(?![A-Za-z0-9_])/', $after, $bars) === 1) {
            return $this->take(TokenKind::Duration, $number.'bars', strlen($number) + strlen($bars[0]), $at);
        }

        return $this->take(TokenKind::Number, $number, strlen($number), $at);
    }

    private function take(TokenKind $kind, string $text, int $length, Position $at): Token
    {
        $this->offset += $length;

        return new Token($kind, $text, $at);
    }

    private function skipWhitespace(): void
    {
        while ($this->offset < strlen($this->source) && ctype_space($this->source[$this->offset])) {
            if ($this->source[$this->offset] === "\n") {
                $this->line++;
                $this->lineStart = $this->offset + 1;
            }

            $this->offset++;
        }
    }

    private function position(): Position
    {
        $first = $this->lineStart === 0;

        return new Position($this->line, ($first ? $this->firstColumn : 1) + $this->offset - $this->lineStart);
    }
}
