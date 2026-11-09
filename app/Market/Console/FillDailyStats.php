<?php

declare(strict_types=1);

namespace App\Market\Console;

use App\Market\DailyStats;
use App\Market\Instruments\DailyHistory;
use App\Market\Models\Instrument;
use Carbon\CarbonImmutable;
use Illuminate\Console\Command;

/** Fills daily_stats for every candidate of the research universe (D-90); run after market:instruments perp --history. */
final class FillDailyStats extends Command
{
    protected $signature = 'market:daily {--from=2024-10-01 : First day, YYYY-MM-DD (the universe needs 30 days before its first month)}';

    protected $description = 'Store the daily candle of every USDT-quoted crypto perpetual, delisted ones included (docs/universe.md)';

    public function handle(DailyHistory $history, DailyStats $stats): int
    {
        $from = CarbonImmutable::createFromFormat('Y-m-d', (string) $this->option('from'), 'UTC');

        if ($from === null) {
            $this->error('--from is a day, YYYY-MM-DD.');

            return self::INVALID;
        }

        $from = $from->startOfDay();
        $instruments = DailyHistory::candidates()->get();
        $problems = 0;
        $this->info("Daily candles of {$instruments->count()} perpetuals from {$from->format('Y-m-d')}.");

        foreach ($instruments as $instrument) {
            /** @var Instrument $instrument */
            $problem = $history->fill($instrument, $from);

            if ($problem !== null) {
                $this->warn(" {$problem}");
                $problems++;
            }
        }

        $stats->fillDerived();
        $this->info("Done; {$problems} instruments without daily candles (listed above).");

        return self::SUCCESS;
    }
}
