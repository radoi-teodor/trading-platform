<?php

declare(strict_types=1);

namespace App\Market\Instruments;

use App\Market\Contracts\InstrumentDirectory;
use App\Market\Data\InstrumentStatus;
use App\Market\Data\MarketType;
use App\Market\Models\Instrument;
use App\Support\Symbol;

/** The instruments market:instruments stored, as the compiler checks them. */
final readonly class StoredInstruments implements InstrumentDirectory
{
    public function __construct(private string $exchange = 'binance') {}

    public function isTradable(Symbol $symbol): bool
    {
        return Instrument::query()
            ->where('exchange', $this->exchange)
            ->where('market', $symbol->isPerpetual() ? MarketType::Perp : MarketType::Spot)
            ->where('symbol', (string) $symbol)
            ->where('status', InstrumentStatus::Trading)
            ->exists();
    }
}
