<?php

declare(strict_types=1);

namespace App\Market\Console;

use App\Market\Instruments\ResearchUniverse;
use App\Support\Clock\Clock;
use Carbon\CarbonImmutable;
use Illuminate\Console\Command;

/** Ranks the research universe month by month from daily_stats (D-89); run after market:daily. */
final class BuildUniverse extends Command
{
    protected $signature = 'market:universe:build
        {--top=50 : Members per month}
        {--from=2024-11 : First month, YYYY-MM}
        {--to= : Last month, YYYY-MM (default: this month)}';

    protected $description = 'Rank the top USDT crypto perpetuals by 30-day quote volume, month by month, point in time (docs/universe.md)';

    public function handle(ResearchUniverse $universe, Clock $clock): int
    {
        $from = CarbonImmutable::createFromFormat('Y-m-d', $this->option('from').'-01', 'UTC');
        $to = is_string($this->option('to')) ? CarbonImmutable::createFromFormat('Y-m-d', $this->option('to').'-01', 'UTC') : $clock->now()->startOfMonth();
        $top = (int) $this->option('top');

        if ($from === null || $to === null || $top < 1) {
            $this->error('--from and --to are months, YYYY-MM, and --top is at least 1.');

            return self::INVALID;
        }

        $months = $universe->build($top, $from->startOfDay(), $to->startOfDay());
        $members = count($universe->everyMember());
        $this->info("Research universe: top {$top}, {$months} months, {$members} instruments were members at some time.");

        return self::SUCCESS;
    }
}
