<?php

declare(strict_types=1);

namespace App\Portfolio\Data;

use App\Support\Asset;
use App\Support\Number\Price;
use App\Support\Symbol;

/** The last price of each instrument against the quote asset, used to value holdings. */
final readonly class Marks
{
    /** @param array<string, Price> $prices keyed by unified symbol, such as BTC/USDT */
    public function __construct(public Asset $quote, private array $prices = []) {}

    public function with(Symbol $symbol, Price $price): self
    {
        return new self($this->quote, [...$this->prices, (string) $symbol => $price]);
    }

    public function priceOf(Asset $asset): ?Price
    {
        return $this->priceOfCode($asset->code);
    }

    /** The last price of an instrument itself, such as a perpetual. */
    public function priceOfSymbol(Symbol $symbol): ?Price
    {
        return $this->prices[(string) $symbol] ?? null;
    }

    public function priceOfCode(string $asset): ?Price
    {
        return $this->prices["{$asset}/{$this->quote->code}"] ?? null;
    }
}
