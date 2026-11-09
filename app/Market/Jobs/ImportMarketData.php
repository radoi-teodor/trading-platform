<?php

declare(strict_types=1);

namespace App\Market\Jobs;

use App\Market\Models\MarketJob;
use App\Support\Clock\Clock;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Contracts\Queue\ShouldQueue;
use Throwable;

/** A history import started from the Settings screen (D-80): market:backfill in the background, its output kept. */
final class ImportMarketData implements ShouldQueue
{
    use Queueable;

    public int $tries = 1;

    public int $timeout = 6 * 3600;

    public function __construct(public readonly int $job) {}

    public function handle(Kernel $console, Clock $clock): void
    {
        $job = MarketJob::query()->findOrFail($this->job);
        $job->update(['status' => 'running', 'started_at' => $clock->now()]);
        $perpetual = str_contains($job->symbol, ':');

        try {
            $exit = $console->call('market:backfill', [
                'symbol' => $job->symbol, '--from' => $job->from->format('Y-m-d'), '--candles' => $job->candles ? 'yes' : 'no',
                '--funding' => $perpetual && $job->candles, '--flow' => $perpetual && $job->scalping, '--oi' => $perpetual && $job->scalping, '--mark' => $perpetual && $job->scalping, '--depth' => $perpetual && $job->scalping,
            ]);
            $job->update(['status' => $exit === 0 ? 'done' : 'failed', 'output' => $console->output(), 'finished_at' => $clock->now()]);
        } catch (Throwable $failure) {
            $job->update(['status' => 'failed', 'output' => $failure->getMessage(), 'finished_at' => $clock->now()]);

            throw $failure;
        }
    }
}
