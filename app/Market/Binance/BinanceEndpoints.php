<?php

declare(strict_types=1);

namespace App\Market\Binance;

use App\Market\Data\MarketType;

final readonly class BinanceEndpoints
{
    public function __construct(
        public string $spotRest,
        public string $perpRest,
        public string $spotStream,
        public string $perpStream,
        public string $archive,
        public int $timeoutSeconds,
    ) {}

    public function rest(MarketType $market): string
    {
        return rtrim($market === MarketType::Spot ? $this->spotRest : $this->perpRest, '/');
    }

    public function stream(MarketType $market): string
    {
        return rtrim($market === MarketType::Spot ? $this->spotStream : $this->perpStream, '/');
    }
}
