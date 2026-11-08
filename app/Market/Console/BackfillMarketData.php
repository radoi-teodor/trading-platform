<?php

declare(strict_types=1);

namespace App\Market\Console;

use App\Market\Backfill\Backfill;
use App\Market\Backfill\BackfillReport;
use App\Market\Data\MarketType;
use App\Market\Instruments\Universe;
use App\Support\Clock\Clock;
use Illuminate\Console\Command;
use InvalidArgumentException;

final class BackfillMarketData extends Command
{
    use SelectsInstruments;

    protected $signature = 'market:backfill
        {symbol? : Unified symbol, BTC/USDT for spot or BTC/USDT:USDT for a perpetual}
        {--universe : Every instrument of config/market.php}
        {--from= : First day or month, YYYY-MM-DD or YYYY-MM}
        {--to= : Last moment, YYYY-MM-DD [HH:MM] in UTC; default the last closed minute}
        {--funding : Also funding rates, for perpetuals}';

    protected $description = 'Fill candle history from Binance archives, then REST (docs/market-data.md)';

    public function handle(Backfill $backfill, Universe $universe, Clock $clock): int
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
            $this->line("{$instrument->symbol} {$instrument->market->value}");
            $failed = $this->print($backfill->candles($instrument, $from, $to)) || $failed;

            if ($this->option('funding') && $instrument->market === MarketType::Perp) {
                $failed = $this->print($backfill->funding($instrument, $from, $to)) || $failed;
            }
        }

        return $failed ? self::FAILURE : self::SUCCESS;
    }

    private function print(BackfillReport $report): bool
    {

        foreach ($report->steps as $step) {
            $this->line(sprintf('  %-24s +%d new, %d replaced, %d kept, %d discrepancies', $step->source, $step->result->inserted, $step->result->overwritten, $step->result->kept, $step->result->discrepancies));
        }

        if ($report->failure !== null) {
            $this->error("  stopped: {$report->failure}");
        }

        return $report->failed();
    }
}
