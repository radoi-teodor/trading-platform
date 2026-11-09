<?php

declare(strict_types=1);

namespace App\Strategy\Dsl;

final readonly class Token
{
    public function __construct(public TokenKind $kind, public string $text, public Position $position) {}

    public function is(TokenKind $kind, ?string $text = null): bool
    {
        return $this->kind === $kind && ($text === null || $this->text === $text);
    }

    /** How the token reads in an error message. */
    public function describe(): string
    {
        return $this->kind === TokenKind::End ? 'the end of the expression' : $this->text;
    }
}
