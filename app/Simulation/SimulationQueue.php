<?php

declare(strict_types=1);

namespace App\Simulation;

use App\Simulation\Jobs\RunSimulation;
use App\Simulation\Models\SimulationJob;
use App\Strategy\Dsl\Document\StrategySpec;
use App\Support\Clock\Clock;
use Carbon\CarbonImmutable;
use Illuminate\Contracts\Bus\Dispatcher;
use InvalidArgumentException;

/**
 * Accepts backtest and grid runs for the background (D-62), only on saved versions and only with arguments it knows,
 * at most two waiting or running per author, and none of more instrument-days than MAX_INSTRUMENT_DAYS. A run over
 * a universe is given six hours instead of one (D-92).
 */
final readonly class SimulationQueue
{
    public const int WAITING_PER_AUTHOR = 2;

    /** Instruments times days a backtest or a grid may read: the top 50 over two years is 36,500. */
    public const int MAX_INSTRUMENT_DAYS = 40_000;

    private const array ARGUMENTS = [
        'backtest' => ['strategy', 'from', 'to', 'params', 'no_benchmarks'],
        'grid' => ['strategy', 'from', 'to', 'grid', 'walk_forward'],
        'gates' => ['strategy', 'to'],
    ];

    public function __construct(private Dispatcher $dispatcher, private Clock $clock, private string $connection, private StrategyLibrary $library) {}

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

        $spec = $this->spec($strategy);

        $from = $arguments['from'] ?? null;
        $to = $arguments['to'] ?? null;

        // A gate report has no window of its own: it reads the history there is.
        if ($spec !== null && is_string($from) && is_string($to)) {
            $instruments = $spec->universe === null ? count($spec->instruments) : $spec->universe->n;
            $days = (int) CarbonImmutable::parse($from, 'UTC')->diffInDays(CarbonImmutable::parse($to, 'UTC'));

            if ($instruments * $days > self::MAX_INSTRUMENT_DAYS) {
                throw new InvalidArgumentException(sprintf(
                    '%s from %s to %s is %d instruments over %d days, %d instrument-days; a background run takes at most %d. Shorten the window.',
                    $strategy, $from, $to, $instruments, $days, $instruments * $days, self::MAX_INSTRUMENT_DAYS,
                ));
            }
        }

        $waiting = SimulationJob::query()->where('author', $author)->whereIn('status', ['queued', 'running'])->count();

        if ($waiting >= self::WAITING_PER_AUTHOR) {
            throw new InvalidArgumentException("{$author} already has {$waiting} runs waiting or running; wait for one to finish.");
        }

        $job = SimulationJob::query()->create([
            'kind' => $kind, 'arguments' => $arguments, 'status' => 'queued', 'author' => $author, 'created_at' => $this->clock->now(),
        ]);
        $timeout = $spec?->universe === null ? RunSimulation::TIMEOUT : RunSimulation::UNIVERSE_TIMEOUT;
        $this->dispatcher->dispatch((new RunSimulation($job->id, $timeout))->onConnection($this->connection)->onQueue('simulation'));

        return $job;
    }

    /** The document of a saved version as compiled, or null when the version is not saved or no longer compiles: the run says why. */
    private function spec(string $label): ?StrategySpec
    {
        $version = $this->library->versions()->find($label);

        return $version === null ? null : $this->library->check($version->document)->spec;
    }
}
