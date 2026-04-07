<?php

namespace App\Console\Commands;

use App\Models\StockPrice;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Process;
use Carbon\Carbon;

class BackfillMarketData extends Command
{
    protected $signature = 'market:backfill {symbol} {--days=90 : Number of days to backfill}';

    protected $description = 'Fetch historical OHLCV data for a symbol using yfinance';

    public function handle(): int
    {
        $symbol = strtoupper($this->argument('symbol'));
        $days = (int) $this->option('days');

        $this->info("Backfilling {$days} days of data for {$symbol}...");

        // Find agents directory (sibling of laravel/)
        $projectRoot = dirname(base_path());
        $agentsDir = $projectRoot . DIRECTORY_SEPARATOR . 'agents';
        $scriptPath = $agentsDir . DIRECTORY_SEPARATOR . 'scripts' . DIRECTORY_SEPARATOR . 'fetch_prices.py';

        if (!file_exists($scriptPath)) {
            $this->error("Script not found at: {$scriptPath}");
            return self::FAILURE;
        }

        // Find Python: venv first, then system
        $venvPython = $agentsDir . DIRECTORY_SEPARATOR . 'venv' . DIRECTORY_SEPARATOR
            . (PHP_OS_FAMILY === 'Windows' ? 'Scripts' : 'bin')
            . DIRECTORY_SEPARATOR . (PHP_OS_FAMILY === 'Windows' ? 'python.exe' : 'python');

        $python = file_exists($venvPython) ? $venvPython : 'python';

        $this->line("  Python: {$python}");
        $this->line("  Script: {$scriptPath}");

        try {
            $cmd = "\"{$python}\" \"{$scriptPath}\" {$symbol} {$days}";
            $result = Process::timeout(120)->run($cmd);

            $output = $result->output();
            $errorOutput = $result->errorOutput();

            // yfinance prints warnings to stderr, ignore those
            if (!$result->successful() && !$output) {
                $this->error("Script failed: " . $errorOutput);
                return self::FAILURE;
            }

            // Parse JSON from stdout (may have warning lines before JSON)
            $jsonStart = strpos($output, '[');
            if ($jsonStart === false) {
                $this->error('No JSON array found in output.');
                $this->line($output);
                return self::FAILURE;
            }

            $jsonStr = substr($output, $jsonStart);
            $data = json_decode($jsonStr, true);

            if (!is_array($data) || empty($data)) {
                $this->error('No data returned. Symbol may be invalid.');
                return self::FAILURE;
            }

            if (isset($data['error'])) {
                $this->error('Error: ' . $data['error']);
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

            $this->info("Backfilled {$count} days for {$symbol}.");
            return self::SUCCESS;
        } catch (\Exception $e) {
            $this->error("Failed: {$e->getMessage()}");
            return self::FAILURE;
        }
    }
}
