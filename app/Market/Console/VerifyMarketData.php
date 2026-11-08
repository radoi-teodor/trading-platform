<?php

declare(strict_types=1);

namespace App\Market\Console;

use App\Market\Archive\ArchiveMissing;
use App\Market\Archive\ChecksumMismatch;
use App\Market\Binance\BinanceRequestFailed;
use App\Market\Instruments\Universe;
use App\Market\Quality\ExchangeVerification;
use App\Market\Quality\VerificationSource;
use App\Support\Clock\Clock;
use Illuminate\Console\Command;
use InvalidArgumentException;

final class VerifyMarketData extends Command
{
    use SelectsInstruments;

    private const int MAX_LISTED = 20;

    protected $signature = 'market:verify
        {symbol? : Unified symbol, BTC/USDT or BTC/USDT:USDT}
        {--universe : Every instrument of config/market.php}
        {--from= : YYYY-MM, YYYY-MM-DD or YYYY-MM-DD HH:MM (UTC)}
        {--to= : Same formats; a bare day or month means its last minute; default now}
        {--sample=500 : Candles to compare per instrument}
        {--against=rest : rest or daily-archive}
        {--seed=2026 : Seed of the sample, for a repeatable check}';

    protected $description = 'Compare a sample of stored candles with a fresh read from Binance';

    public function handle(ExchangeVerification $verification, Universe $universe, Clock $clock): int
    {
        try {
            $symbols = $this->selectedSymbols($universe);
            $from = $this->momentOption('from');
            $to = $this->momentOption('to', $clock->now());
            $against = VerificationSource::tryFrom((string) $this->option('against'))
                ?? throw new InvalidArgumentException('--against must be rest or daily-archive.');
        } catch (InvalidArgumentException $invalid) {
            $this->error($invalid->getMessage());

            return self::INVALID;
        }

        $failed = false;

        foreach ($symbols as $symbol) {
            $instrument = $universe->instrument($symbol);

            try {
                $report = $verification->verify($instrument, $from, $to, (int) $this->option('sample'), $against, (int) $this->option('seed'));
            } catch (BinanceRequestFailed|ArchiveMissing|ChecksumMismatch $failure) {
                $this->error("{$instrument->symbol} {$instrument->market->value}: {$failure->getMessage()}");
                $failed = true;

                continue;
            }

            $this->line("{$instrument->symbol} {$instrument->market->value}: {$report->sampled} sampled, ".count($report->mismatches).' mismatches');

            foreach (array_slice($report->mismatches, 0, self::MAX_LISTED) as $mismatch) {
                $this->line("  {$mismatch}");
            }

            $failed = $failed || $report->mismatches !== [];
        }

        return $failed ? self::FAILURE : self::SUCCESS;
    }
}
