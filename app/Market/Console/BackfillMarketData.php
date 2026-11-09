<?php

declare(strict_types=1);

namespace App\Market\Console;

use App\Market\Backfill\Backfill;
use App\Market\Backfill\BackfillReport;
use App\Market\Data\MarketType;
use App\Market\Instruments\Universe;
use App\Market\Models\Instrument;
use App\Support\Clock\Clock;
use App\Support\Number\Decimal;
use App\Support\Symbol;
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
        {--funding : Also funding rates, for perpetuals}
        {--flow : Also the trade flow from aggregated trades, for perpetuals (D-79)}
        {--oi : Also open interest, for perpetuals}
        {--mark : Also the mark price and the premium index, for perpetuals}
        {--depth : Also the order book depth within 1, 2 and 5 %, for perpetuals (D-91)}
        {--large= : The notional from which a trade is large, in quote (default config/market.php)}
        {--candles=yes : no to leave the candles out}';

    protected $description = 'Fill candle history from Binance archives, then REST (docs/market-data.md)';

    public function handle(Backfill $backfill, Universe $universe, Clock $clock): int
    {
        try {
            $symbols = $this->selectedSymbols($universe);
            $from = $this->momentOption('from');
            $to = $this->momentOption('to', $clock->now());
            $option = $this->option('large');

            if (is_string($option) && preg_match('/^\d+(\.\d+)?$/', $option) !== 1) {
                throw new InvalidArgumentException('--large is an amount of quote, such as 100000.');
            }
        } catch (InvalidArgumentException $invalid) {
            $this->error($invalid->getMessage());

            return self::INVALID;
        }

        $failed = false;
        $scalping = (bool) $this->option('flow') || (bool) $this->option('oi') || (bool) $this->option('mark') || (bool) $this->option('depth');

        if ($scalping && is_string($this->argument('symbol')) && ! Symbol::parse($this->argument('symbol'))->isPerpetual()) {
            $this->error('--flow, --oi, --mark and --depth are for perpetuals, such as BTC/USDT:USDT.');

            return self::INVALID;
        }

        foreach ($symbols as $symbol) {
            $instrument = $universe->instrument($symbol);
            $this->line("{$instrument->symbol} {$instrument->market->value}");

            if ($this->option('candles') !== 'no') {
                $failed = $this->print($backfill->candles($instrument, $from, $to)) || $failed;
            }

            if ($this->option('funding') && $instrument->market === MarketType::Perp) {
                $failed = $this->print($backfill->funding($instrument, $from, $to)) || $failed;
            }

            if ($scalping && $instrument->market === MarketType::Perp) {
                $failed = $this->print($backfill->scalping(
                    $instrument, $from, $to, (bool) $this->option('flow'), (bool) $this->option('oi'), (bool) $this->option('mark'), $this->largeNotional($instrument, $universe), (bool) $this->option('depth'),
                )) || $failed;
            }
        }

        return $failed ? self::FAILURE : self::SUCCESS;
    }

    private function largeNotional(Instrument $instrument, Universe $universe): Decimal
    {
        $option = $this->option('large');

        return is_string($option) ? Decimal::of($option) : $universe->largeNotional($instrument);
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
