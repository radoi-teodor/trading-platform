<?php

declare(strict_types=1);

namespace App\Strategy\Contracts;

use App\Support\Symbol;

/** A hand-coded strategy that can be built by name from text parameters, as `backtest:run` does. */
interface BuildableStrategy extends Strategy
{
    /** @return array<string, string> every parameter with its default */
    public static function defaults(): array;

    /**
     * @param  array<string, string>  $parameters  the defaults overridden by the caller
     * @param  list<Symbol>  $symbols
     */
    public static function build(array $parameters, array $symbols): Strategy;
}
