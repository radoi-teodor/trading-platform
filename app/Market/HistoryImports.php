<?php

declare(strict_types=1);

namespace App\Market;

use App\Market\Jobs\ImportMarketData;
use App\Market\Models\MarketJob;
use App\Support\Clock\Clock;
use Carbon\CarbonImmutable;
use Illuminate\Contracts\Bus\Dispatcher;

/**
 * Queues a history import (D-80) for the simulation worker: market:backfill in the background, its state and output
 * kept in market_jobs. Started from Settings, Market data, and from the chart when its history is missing (D-88).
 */
final readonly class HistoryImports
{
    public function __construct(private Dispatcher $bus, private Clock $clock) {}

    public function queue(string $symbol, CarbonImmutable $from, bool $candles, bool $scalping): MarketJob
    {
        $job = MarketJob::query()->create([
            'symbol' => $symbol, 'from' => $from->startOfDay(), 'candles' => $candles, 'scalping' => $scalping,
            'status' => 'queued', 'created_at' => $this->clock->now(),
        ]);
        $this->bus->dispatch((new ImportMarketData($job->id))->onConnection('simulation')->onQueue('market'));

        return $job;
    }
}
