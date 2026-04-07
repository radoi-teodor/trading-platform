<?php

namespace App\Console\Commands;

use App\Models\StockPrice;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Process;
use Carbon\Carbon;

class BackfillMarketData extends Command
{
    protected $signature = 'market:backfill {symbol} {--days=365 : Number of days to backfill}';

    protected $description = 'Fetch historical market data for a symbol';

    public function handle(): int
    {
        $symbol = strtoupper($this->argument('symbol'));
        $days = (int) $this->option('days');

        $this->info("Backfilling {$days} days of data for {$symbol}...");

        $startDate = Carbon::now()->subDays($days)->format('Y-m-d');
        $endDate = Carbon::now()->format('Y-m-d');

        // Try calling the Python yfinance helper script
        $scriptPath = base_path('../python/scripts/fetch_prices.py');

        try {
            $result = Process::timeout(120)->run(
                "python \"{$scriptPath}\" {$symbol} {$startDate} {$endDate}"
            );

            if ($result->successful()) {
                $data = json_decode($result->output(), true);

                if (!is_array($data) || empty($data)) {
                    $this->error('No data returned from price fetcher.');
                    return self::FAILURE;
                }

                $count = 0;
                foreach ($data as $row) {
                    StockPrice::updateOrCreate(
                        [
                            'symbol' => $symbol,
                            'date' => $row['date'],
                        ],
                        [
                            'open' => (int) round($row['open'] * 100),
                            'high' => (int) round($row['high'] * 100),
                            'low' => (int) round($row['low'] * 100),
                            'close' => (int) round($row['close'] * 100),
                            'volume' => (int) ($row['volume'] ?? 0),
                        ]
                    );
                    $count++;
                }

                $this->info("Successfully backfilled {$count} records for {$symbol}.");
                return self::SUCCESS;
            }

            $this->error("Python script failed: " . $result->errorOutput());
            return self::FAILURE;
        } catch (\Exception $e) {
            $this->error("Failed to run backfill: {$e->getMessage()}");
            $this->info('Ensure Python and yfinance are installed, and the fetch_prices.py script exists.');
            return self::FAILURE;
        }
    }
}
