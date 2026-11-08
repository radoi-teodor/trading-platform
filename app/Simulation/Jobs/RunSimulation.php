<?php

declare(strict_types=1);

namespace App\Simulation\Jobs;

use App\Simulation\Models\SimulationJob;
use App\Support\Clock\Clock;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Contracts\Queue\ShouldQueue;
use Symfony\Component\Console\Output\BufferedOutput;
use Throwable;

/**
 * Runs one background simulation (D-62) through the same command the operator runs, backtest:run or backtest:grid
 * with --json, so that a job's report is the command's report (D-42). It keeps the command's summary, or its error.
 */
final class RunSimulation implements ShouldQueue
{
    use Queueable;

    public int $tries = 1;

    public int $timeout = 3600;

    public function __construct(public readonly int $jobId) {}

    public function handle(Kernel $console, Clock $clock): void
    {
        $job = SimulationJob::query()->findOrFail($this->jobId);
        $job->update(['status' => 'running', 'started_at' => $clock->now()]);
        $output = new BufferedOutput;

        try {
            $exit = $console->call($job->kind === 'grid' ? 'backtest:grid' : 'backtest:run', self::options($job), $output);
            $text = $output->fetch();
            $lines = array_values(array_filter(array_map('trim', explode("\n", $text)), fn (string $line): bool => $line !== ''));
            $summary = $exit === 0 ? json_decode((string) end($lines), true) : null;

            if (! is_array($summary)) {
                $job->update(['status' => 'failed', 'error' => self::tail($text), 'finished_at' => $clock->now()]);

                return;
            }

            $job->update(['status' => 'done', 'summary' => $summary, 'finished_at' => $clock->now()]);
        } catch (Throwable $failure) {
            $job->update(['status' => 'failed', 'error' => $failure->getMessage(), 'finished_at' => $clock->now()]);

            throw $failure;
        }
    }

    /** @return array<string, mixed> the command's arguments and options */
    private static function options(SimulationJob $job): array
    {
        $a = $job->arguments;
        $options = ['strategy' => $a['strategy'] ?? '', '--from' => $a['from'] ?? '', '--to' => $a['to'] ?? '', '--json' => true];
        $list = fn (mixed $value): array => is_array($value) ? array_values(array_filter($value, 'is_string')) : [];

        if (isset($a['params']) && is_array($a['params'])) {
            $options['--param'] = array_map(fn (string $k, mixed $v): string => $k.'='.(is_scalar($v) ? (string) $v : ''), array_keys($a['params']), $a['params']);
        }

        if ($job->kind === 'grid') {
            $options['--grid'] = $list($a['grid'] ?? []);
            $walk = $a['walk_forward'] ?? null;

            if (is_array($walk)) {
                $options['--walk-forward'] = true;

                foreach (['in' => '--in', 'out' => '--out', 'step' => '--step', 'min_trades' => '--min-trades'] as $key => $option) {
                    if (isset($walk[$key]) && is_scalar($walk[$key])) {
                        $options[$option] = (string) $walk[$key];
                    }
                }
            }
        } elseif (($a['no_benchmarks'] ?? false) === true) {
            $options['--no-benchmarks'] = true;
        }

        return $options;
    }

    private static function tail(string $text): string
    {
        $lines = array_slice(array_values(array_filter(explode("\n", trim($text)))), -20);

        return implode("\n", $lines) ?: 'The run ended without a summary.';
    }
}
