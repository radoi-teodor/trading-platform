<?php

declare(strict_types=1);

namespace App\Market\Contracts;

use App\Support\Symbol;

/** Which instruments the exchange lists and trades now, by unified symbol (spot or perpetual). */
interface InstrumentDirectory
{
    public function isTradable(Symbol $symbol): bool;
}
