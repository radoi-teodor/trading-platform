<?php

declare(strict_types=1);

use App\Simulation\Jobs\RunSimulation;
use App\Simulation\Models\BacktestRun;
use App\Simulation\Models\SimulationJob;
use App\Simulation\SimulationQueue;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Queue;

uses(RefreshDatabase::class);

mutates(SimulationQueue::class, RunSimulation::class);

function savedEmaCross(): void
{
    backtestFixture(days: 36);
    Artisan::call('strategy:import', ['path' => strategyFile(EMA_CROSS_DOCUMENT)]);
}

it('runs a queued backtest of a saved version to the same report as the command, and keeps its summary (D-62)', function (): void {
    savedEmaCross();
    $job = app(SimulationQueue::class)->submit('backtest', ['strategy' => 'ema-cross-test@v1', 'from' => '2026-10-02', 'to' => '2026-10-03'], 'agent:desk');
    $job->refresh();
    $summary = $job->summary ?? [];
    $run = BacktestRun::query()->sole();

    expect($job->status)->toBe('done')
        ->and($job->author)->toBe('agent:desk')
        ->and($job->started_at)->not->toBeNull()
        ->and($job->finished_at)->not->toBeNull()
        ->and($summary['report'] ?? null)->toBe($run->report_hash)
        ->and(data_get($summary, 'metrics.trades'))->toBe($run->trades)
        ->and($summary)->toHaveKeys(['artifact', 'metrics.return', 'metrics.sharpe', 'benchmarks']);
});

it('runs a queued grid with its walk-forward and keeps the validation', function (): void {
    savedEmaCross();
    $job = app(SimulationQueue::class)->submit('grid', [
        'strategy' => 'ema-cross-test@v1', 'from' => '2026-10-02', 'to' => '2026-11-05', 'grid' => ['fast=5,9', 'slow=15,21'],
        'walk_forward' => ['in' => '10d', 'out' => '5d', 'step' => '5d', 'min_trades' => 1],
    ], 'agent:desk');
    $summary = $job->refresh()->summary ?? [];

    expect($job->status)->toBe('done')
        ->and($summary['sets'] ?? [])->toHaveCount(4)
        ->and($summary)->toHaveKeys(['artifact', 'validation.deflated_sharpe', 'validation.pbo', 'validation.walk_forward.windows']);
});

it('keeps the error of a run that fails', function (): void {
    savedEmaCross();
    $job = app(SimulationQueue::class)->submit('backtest', ['strategy' => 'ema-cross-test@v7', 'from' => '2026-10-02', 'to' => '2026-10-03'], 'operator');

    expect($job->refresh()->status)->toBe('failed')
        ->and($job->error)->toContain('ema-cross-test@v7 is not a saved version.');
});

it('refuses what a run cannot be, and more than two waiting runs per author', function (): void {
    Queue::fake();
    $queue = app(SimulationQueue::class);
    $valid = ['strategy' => 'ema-cross-test@v1', 'from' => '2026-10-02', 'to' => '2026-10-03'];
    $queue->submit('backtest', $valid, 'agent:desk');
    $queue->submit('backtest', $valid, 'agent:desk');

    expect(fn () => $queue->submit('backtest', $valid, 'agent:desk'))->toThrow(InvalidArgumentException::class, 'agent:desk already has 2 runs waiting or running; wait for one to finish.')
        ->and(fn () => $queue->submit('backtest', [...$valid, 'strategy' => 'ema-cross-test'], 'operator'))->toThrow(InvalidArgumentException::class, 'strategy is a saved version, such as ema-cross-scalp-long@v1.')
        ->and(fn () => $queue->submit('backtest', [...$valid, 'from' => 'yesterday'], 'operator'))->toThrow(InvalidArgumentException::class, 'from and to are days, YYYY-MM-DD.')
        ->and(fn () => $queue->submit('deploy', $valid, 'operator'))->toThrow(InvalidArgumentException::class, 'A simulation run is a backtest or a grid.')
        ->and(fn () => $queue->submit('backtest', [...$valid, 'shell' => 'rm'], 'operator'))->toThrow(InvalidArgumentException::class, 'Unknown argument shell.');
    Queue::assertPushed(RunSimulation::class, 2);
    expect(SimulationJob::query()->where('status', 'queued')->count())->toBe(2);
});
