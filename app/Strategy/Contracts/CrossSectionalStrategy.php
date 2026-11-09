<?php

declare(strict_types=1);

namespace App\Strategy\Contracts;

use App\Strategy\Data\Intent;
use App\Strategy\Data\StrategyContext;

/**
 * A strategy that decides for many instruments at once, by comparing them (D-92). It is given every candle that
 * closed at one moment together, and answers with intents in the order they are to be handled: closes and stop
 * moves first, then entries from the best ranked. They are intents, never orders (D-7).
 */
interface CrossSectionalStrategy extends Strategy
{
    /**
     * @param  list<StrategyContext>  $contexts  the candles that closed at one moment, of any instrument and timeframe it follows
     * @return list<Intent>
     */
    public function onCloses(array $contexts): array;
}
