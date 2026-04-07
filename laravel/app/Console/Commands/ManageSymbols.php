<?php

namespace App\Console\Commands;

use App\Models\StockPrice;
use Illuminate\Console\Command;

class ManageSymbols extends Command
{
    protected $signature = 'market:symbols
                            {action=list : Action to perform (list, add, remove)}
                            {symbol? : The stock symbol (required for add/remove)}';

    protected $description = 'List, add, or remove tracked stock symbols';

    public function handle(): int
    {
        $action = $this->argument('action');
        $symbol = $this->argument('symbol');

        return match ($action) {
            'list' => $this->listSymbols(),
            'add' => $this->addSymbol($symbol),
            'remove' => $this->removeSymbol($symbol),
            default => $this->invalidAction($action),
        };
    }

    private function listSymbols(): int
    {
        $symbols = StockPrice::select('symbol')
            ->selectRaw('COUNT(*) as data_points')
            ->selectRaw('MIN(date) as first_date')
            ->selectRaw('MAX(date) as last_date')
            ->groupBy('symbol')
            ->orderBy('symbol')
            ->get();

        if ($symbols->isEmpty()) {
            $this->info('No symbols are currently tracked.');
            return self::SUCCESS;
        }

        $rows = $symbols->map(function ($s) {
            return [
                $s->symbol,
                $s->data_points,
                $s->first_date,
                $s->last_date,
            ];
        })->toArray();

        $this->table(['Symbol', 'Data Points', 'First Date', 'Last Date'], $rows);

        return self::SUCCESS;
    }

    private function addSymbol(?string $symbol): int
    {
        if (!$symbol) {
            $this->error('Symbol is required for the add action.');
            return self::FAILURE;
        }

        $symbol = strtoupper(trim($symbol));

        $exists = StockPrice::where('symbol', $symbol)->exists();
        if ($exists) {
            $this->info("Symbol {$symbol} is already tracked.");
            return self::SUCCESS;
        }

        // Create a placeholder entry so the symbol is tracked
        StockPrice::create([
            'symbol' => $symbol,
            'date' => now()->toDateString(),
            'open' => 0,
            'high' => 0,
            'low' => 0,
            'close' => 0,
            'volume' => 0,
        ]);

        $this->info("Symbol {$symbol} added. Run market:backfill {$symbol} to fetch historical data.");

        return self::SUCCESS;
    }

    private function removeSymbol(?string $symbol): int
    {
        if (!$symbol) {
            $this->error('Symbol is required for the remove action.');
            return self::FAILURE;
        }

        $symbol = strtoupper(trim($symbol));

        $deleted = StockPrice::where('symbol', $symbol)->delete();

        if ($deleted > 0) {
            $this->info("Symbol {$symbol} removed ({$deleted} records deleted).");
        } else {
            $this->warn("Symbol {$symbol} was not found.");
        }

        return self::SUCCESS;
    }

    private function invalidAction(string $action): int
    {
        $this->error("Unknown action: {$action}. Use list, add, or remove.");
        return self::FAILURE;
    }
}
