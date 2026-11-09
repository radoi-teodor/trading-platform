<?php

declare(strict_types=1);

namespace App\Simulation;

use App\Simulation\Jobs\RunSimulation;
use App\Simulation\Models\SimulationJob;
use App\Support\Clock\Clock;
use Illuminate\Contracts\Bus\Dispatcher;
use InvalidArgumentException;

/**
 * Accepts backtest and grid runs for the background (D-62), only on saved versions and only with arguments it knows,
 * at most two waiting or running per author.
 */
final readonly class SimulationQueue
{
    public const int WAITING_PER_AUTHOR = 2;

    private const array ARGUMENTS = [
        'backtest' => ['strategy', 'from', 'to', 'params', 'no_benchmarks'],
        'grid' => ['strategy', 'from', 'to', 'grid', 'walk_forward'],
        'gates' => ['strategy', 'to'],
    ];

    public function __construct(private Dispatcher $dispatcher, private Clock $clock, private string $connection) {}

    /** @param array<string, mixed> $arguments */
    public function submit(string $kind, array $arguments, string $author): SimulationJob
    {
        $allowed = self::ARGUMENTS[$kind] ?? throw new InvalidArgumentException('A simulation run is a backtest, a grid or a gate report.');

        foreach (array_keys($arguments) as $name) {
            if (! in_array($name, $allowed, true)) {
                throw new InvalidArgumentException("Unknown argument {$name}.");
            }
        }

        $strategy = $arguments['strategy'] ?? null;

        if (! is_string($strategy) || preg_match('/^[a-z0-9]+(-[a-z0-9]+)*@v[1-9]\d{0,5}$/', $strategy) !== 1) {
            throw new InvalidArgumentException('strategy is a saved version, such as ema-cross-scalp-long@v1.');
        }

        foreach ($kind === 'gates' ? (isset($arguments['to']) ? ['to'] : []) : ['from', 'to'] as $day) {
            if (! is_string($arguments[$day] ?? null) || preg_match('/^\d{4}-\d{2}-\d{2}$/', $arguments[$day]) !== 1) {
                throw new InvalidArgumentException('from and to are days, YYYY-MM-DD.');
            }
        }

        $waiting = SimulationJob::query()->where('author', $author)->whereIn('status', ['queued', 'running'])->count();

        if ($waiting >= self::WAITING_PER_AUTHOR) {
            throw new InvalidArgumentException("{$author} already has {$waiting} runs waiting or running; wait for one to finish.");
        }

        $job = SimulationJob::query()->create([
            'kind' => $kind, 'arguments' => $arguments, 'status' => 'queued', 'author' => $author, 'created_at' => $this->clock->now(),
        ]);
        $this->dispatcher->dispatch((new RunSimulation($job->id))->onConnection($this->connection)->onQueue('simulation'));

        return $job;
    }
}
