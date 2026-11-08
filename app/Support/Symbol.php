<?php

declare(strict_types=1);

namespace App\Support;

use InvalidArgumentException;
use Stringable;

/**
 * A unified instrument symbol, as ccxt writes them: BASE/QUOTE for spot, BASE/QUOTE:SETTLE for a perpetual
 * (docs/market-data.md). The exchange's own spelling is venueSymbol(); never strip suffixes by hand
 * (Known traps: symbol spellings).
 */
final readonly class Symbol implements Stringable
{
    private function __construct(public Asset $base, public Asset $quote, public ?Asset $settle) {}

    public static function parse(string $symbol): self
    {
        if (preg_match('/^([\p{L}\p{N}]+)\/([\p{L}\p{N}]+)(?::([\p{L}\p{N}]+))?$/u', $symbol, $parts) !== 1) {
            throw new InvalidArgumentException("Not a unified symbol: '{$symbol}'.");
        }

        $settle = isset($parts[3]) ? Asset::of($parts[3]) : null;

        return new self(Asset::of($parts[1]), Asset::of($parts[2]), $settle);
    }

    public function isPerpetual(): bool
    {
        return $this->settle !== null;
    }

    /** Binance spells both BTC/USDT and BTC/USDT:USDT as BTCUSDT; the market tells them apart. */
    public function venueSymbol(): string
    {
        return $this->base->code.$this->quote->code;
    }

    public function equals(self $other): bool
    {
        return (string) $this === (string) $other;
    }

    public function __toString(): string
    {
        $symbol = "{$this->base}/{$this->quote}";

        return $this->settle === null ? $symbol : "{$symbol}:{$this->settle}";
    }
}
