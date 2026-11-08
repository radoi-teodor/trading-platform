<?php

declare(strict_types=1);

namespace App\Market\Console;

use App\Market\Binance\BinanceRequestFailed;
use App\Market\Binance\BinanceRestClient;
use App\Market\Data\MarketType;
use App\Market\Instruments\InstrumentSync;
use Illuminate\Console\Command;

final class SyncInstruments extends Command
{
    protected $signature = 'market:instruments {market=all : spot, perp or all}';

    protected $description = 'Load Binance instruments and their filters (docs/market-data.md)';

    public function handle(BinanceRestClient $client, InstrumentSync $sync): int
    {
        $choice = $this->argument('market');
        $markets = match ($choice) {
            'spot' => [MarketType::Spot],
            'perp' => [MarketType::Perp],
            'all' => [MarketType::Spot, MarketType::Perp],
            default => null,
        };

        if ($markets === null) {
            $this->error('The market must be spot, perp or all.');

            return self::INVALID;
        }

        $failed = false;

        foreach ($markets as $market) {
            try {
                $result = $sync->sync('binance', $market, $client->exchangeInfo($market));
                $this->info("{$market->value}: {$result->instruments} instruments, {$result->filterChanges} filter changes, {$result->delisted} delisted");
            } catch (BinanceRequestFailed $failure) {
                $this->error("{$market->value}: {$failure->getMessage()}");
                $failed = true;
            }
        }

        return $failed ? self::FAILURE : self::SUCCESS;
    }
}
