<?php

declare(strict_types=1);

namespace App\Strategy\Dsl;

enum TokenKind
{
    case Number;
    case Percent;
    case Duration;
    case RMultiple;
    case Text;
    case Identifier;
    case Parameter;
    case Keyword;
    case Operator;
    case LeftParen;
    case RightParen;
    case LeftBracket;
    case RightBracket;
    case Comma;
    case Colon;
    case Dot;
    case End;
}
