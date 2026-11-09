<?php

declare(strict_types=1);

namespace App\Market\Console;

use App\Market\Archive\ArchiveCatalog;
use App\Market\Binance\BinanceRequestFailed;
use App\Market\Binance\BinanceRestClient;
use App\Market\Data\InstrumentStatus;
use App\Market\Data\MarketType;
use App\Market\Instruments\InstrumentSync;
use App\Market\Models\Instrument;
use App\Support\Clock\Clock;
use Illuminate\Console\Command;
use UnexpectedValueException;

final class SyncInstruments extends Command
{
    protected $signature = 'market:instruments
        {market=all : spot, perp or all}
        {--history : Also register, as delisted, the perpetuals only the archives still know (D-89)}';

    protected $description = 'Load Binance instruments and their filters (docs/market-data.md)';

    public function handle(BinanceRestClient $client, InstrumentSync $sync, ArchiveCatalog $catalog, Clock $clock): int
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

        if (! $failed && $this->option('history') && in_array(MarketType::Perp, $markets, true)) {
            $failed = ! $this->registerArchived($catalog, $clock);
        }

        return $failed ? self::FAILURE : self::SUCCESS;
    }

    /** Registers the perpetuals the archives know and exchangeInfo no longer lists, as delisted, for history only. */
    private function registerArchived(ArchiveCatalog $catalog, Clock $clock): bool
    {
        try {
            $symbols = $catalog->perpetuals();
        } catch (BinanceRequestFailed|UnexpectedValueException $failure) {
            $this->error("archive listing: {$failure->getMessage()}");

            return false;
        }

        $known = Instrument::query()->where('exchange', 'binance')->where('market', MarketType::Perp)->get()
            ->map(fn (Instrument $instrument): string => $instrument->venue_symbol)->all();
        $added = 0;

        foreach (array_diff($symbols, $known) as $venueSymbol) {
            $symbol = ArchiveCatalog::unified($venueSymbol);

            if ($symbol === null) {
                $this->warn("{$venueSymbol}: quote asset not recognised, left out.");

                continue;
            }

            Instrument::register('binance', MarketType::Perp, $symbol, $clock->now(), InstrumentStatus::Delisted);
            $added++;
        }

        $this->info("{$added} perpetuals known only from the archives, registered as delisted (history only).");

        return true;
    }
}
