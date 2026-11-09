<?php

declare(strict_types=1);

namespace App\Market\Console;

use App\Market\Depth\DepthStream;
use App\Market\Instruments\Universe;
use Illuminate\Console\Command;

/** Keeps the local order books of the perpetuals with scalping data and writes their depth every 5 minutes (D-91). */
final class StreamDepth extends Command
{
    protected $signature = 'market:depth';

    protected $description = 'Keep local order books of the scalping perpetuals and store their depth within 1, 2 and 5 % (docs/universe.md)';

    public function handle(DepthStream $stream, Universe $universe): int
    {
        $instruments = array_values($universe->scalping());

        if ($instruments === []) {
            $this->warn('No perpetual has scalping data on (Settings, Market data): nothing to follow.');

            return self::SUCCESS;
        }

        // A clean stop where PHP has pcntl; on Windows Ctrl+C ends the process, which loses at most the 5 minutes in progress.
        if (defined('SIGTERM') && defined('SIGINT')) {
            $this->trap([SIGTERM, SIGINT], fn () => $stream->stop());
        }

        $this->info('Order books of '.implode(', ', array_map(fn ($i): string => $i->symbol, $instruments)).'. Stop with Ctrl+C.');
        $stream->run($instruments);

        return self::SUCCESS;
    }
}
