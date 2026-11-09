<?php

declare(strict_types=1);

namespace App\Strategy\Dsl;

use App\Strategy\Dsl\Ast\Binary;
use App\Strategy\Dsl\Ast\Call;
use App\Strategy\Dsl\Ast\Lookback;
use App\Strategy\Dsl\Ast\Node;
use App\Strategy\Dsl\Ast\OnTimeframe;
use App\Strategy\Dsl\Ast\Unary;

/** Finds the rankings of a universe (rank, percentile, top, bottom) inside an expression (D-92). */
final class Rankings
{
    public const array NAMES = ['rank', 'percentile', 'top', 'bottom'];

    /** The first ranking of $node, reading left to right, or null when it ranks nothing. */
    public static function first(Node $node): ?Call
    {
        if ($node instanceof Call) {
            if (in_array($node->name, self::NAMES, true)) {
                return $node;
            }

            foreach ($node->arguments as $argument) {
                $found = self::first($argument->value);

                if ($found !== null) {
                    return $found;
                }
            }

            return null;
        }

        return match (true) {
            $node instanceof Binary => self::first($node->left) ?? self::first($node->right),
            $node instanceof Unary => self::first($node->operand),
            $node instanceof Lookback => self::first($node->series),
            $node instanceof OnTimeframe => self::first($node->expression),
            default => null,
        };
    }
}
