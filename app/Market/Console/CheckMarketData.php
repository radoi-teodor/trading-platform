<?php

declare(strict_types=1);

namespace App\Market\Console;

use App\Market\Instruments\Universe;
use App\Market\Quality\QualityCheck;
use App\Support\Clock\Clock;
use Illuminate\Console\Command;
use InvalidArgumentException;

final class CheckMarketData extends Command
{
    use SelectsInstruments;

    private const int MAX_LISTED = 20;

    protected $signature = 'market:check
        {symbol? : Unified symbol, BTC/USDT or BTC/USDT:USDT}
        {--universe : Every instrument of config/market.php}
        {--from= : YYYY-MM, YYYY-MM-DD or YYYY-MM-DD HH:MM (UTC)}
        {--to= : Same formats; a bare day or month means its last minute; default now}
        {--strict : Fail on warnings too}';

    protected $description = 'Check stored candles for gaps, OHLC violations, zero-volume runs and outliers';

    public function handle(QualityCheck $check, Universe $universe, Clock $clock): int
    {
        try {
            $symbols = $this->selectedSymbols($universe);
            $from = $this->momentOption('from');
            $to = $this->momentOption('to', $clock->now());
        } catch (InvalidArgumentException $invalid) {
            $this->error($invalid->getMessage());

            return self::INVALID;
        }

        $failed = false;

        foreach ($symbols as $symbol) {
            $instrument = $universe->instrument($symbol);
            $report = $check->run($instrument, $from, $to);

            $this->line(sprintf(
                '%s %s: %d of %d minutes, %d missing, %d OHLC violations, %d zero-volume runs, %d outliers',
                $instrument->symbol, $instrument->market->value, $report->storedMinutes, $report->expectedMinutes,
                $report->missingMinutes, $report->ohlcViolations, count($report->zeroVolumeRuns), count($report->outliers),
            ));

            foreach (array_slice($report->gaps, 0, self::MAX_LISTED) as $gap) {
                $this->line("  gap {$gap['from']->format('Y-m-d H:i')} to {$gap['to']->format('Y-m-d H:i')}");
            }

            foreach (array_slice($report->zeroVolumeRuns, 0, self::MAX_LISTED) as $run) {
                $this->line("  no volume {$run['from']->format('Y-m-d H:i')} to {$run['to']->format('Y-m-d H:i')}");
            }

            foreach (array_slice($report->outliers, 0, self::MAX_LISTED) as $outlier) {
                $this->line("  outlier {$outlier->format('Y-m-d H:i')}");
            }

            $failed = $failed || $report->hasErrors() || ($this->option('strict') && $report->hasWarnings());
        }

        return $failed ? self::FAILURE : self::SUCCESS;
    }
}
