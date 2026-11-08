<?php

declare(strict_types=1);

namespace App\Strategy\Contracts;

use App\Strategy\Data\Intent;
use App\Strategy\Data\StrategyContext;
use App\Strategy\Data\StrategyIdentity;
use App\Strategy\Data\Subscription;

/**
 * A strategy turns closed candles into intents, never orders (D-7). It is a deterministic function of the candles
 * and its own position: no clock, no randomness, no I/O. One instance runs one backtest or one account.
 */
interface Strategy
{
    public function identity(): StrategyIdentity;

    /** @return list<Subscription> */
    public function subscriptions(): array;

    /** How many candles of its timeframe it needs before its intents mean anything. */
    public function warmupBars(): int;

    /** @return list<Intent> */
    public function onCandle(StrategyContext $context): array;
}
