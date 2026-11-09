<?php

declare(strict_types=1);

namespace App\Market\Console;

use App\Market\Data\MarketType;
use App\Market\Instruments\Universe;
use App\Market\Stream\CandleStream;
use Illuminate\Console\Command;

final class StreamCandles extends Command
{
    protected $signature = 'market:stream {market : spot or perp}';

    protected $description = 'Keep 1-minute candles of the universe current from the Binance WebSocket (docs/market-data.md)';

    public function handle(CandleStream $stream, Universe $universe): int
    {
        $market = MarketType::tryFrom((string) $this->argument('market'));

        if ($market === null) {
            $this->error('The market must be spot or perp.');

            return self::INVALID;
        }

        $instruments = $universe->instruments($market);
        $this->trap([SIGTERM, SIGINT], fn () => $stream->stop());
        $this->info("Streaming {$market->value} candles for ".count($instruments).' instruments. Stop with Ctrl+C or SIGTERM.');

        $scalping = $market === MarketType::Perp ? $universe->scalping() : [];
        $thresholds = array_map(fn ($instrument) => $universe->largeNotional($instrument), $scalping);

        if ($scalping !== []) {
            $this->info('With the trade flow, mark price, premium index and open interest of '.implode(', ', array_keys($scalping)).'.');
        }

        $stream->run($market, $instruments, scalping: $scalping, thresholds: $thresholds);

        return self::SUCCESS;
    }
}
