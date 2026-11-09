<?php

declare(strict_types=1);

namespace App\Market\Binance;

/** Binance answered a signed request with an error: its HTTP status, its code and its message, never the key. */
final class BinanceApiError extends BinanceRequestFailed
{
    public function __construct(public readonly int $status, public readonly int $binanceCode, string $message)
    {
        parent::__construct("Binance {$status} ({$binanceCode}): {$message}");
    }

    /** An invalid key, signature or permission: the account halts at once (docs/risk-policy.md, section 3). */
    public function isAuthentication(): bool
    {
        return $this->status === 401 || in_array($this->binanceCode, [-2014, -2015, -1022], true);
    }
}
