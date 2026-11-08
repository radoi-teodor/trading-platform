<?php

declare(strict_types=1);

namespace App\Simulation\Console;

use App\Support\Clock\Clock;
use Illuminate\Console\Command;
use Illuminate\Contracts\Config\Repository;
use RuntimeException;

/** Writes every saved version as <id>.v<n>.strategy.yaml, for review in git (docs/strategy/configurator.md, section 6). */
final class ExportStrategies extends Command
{
    use PreparesBacktests;

    protected $signature = 'strategy:export {path=catalog/strategies : The directory to write to}';

    protected $description = 'Write every saved strategy version to the catalog';

    public function handle(Repository $config, Clock $clock): int
    {
        $path = $this->argument('path');
        $directory = rtrim(is_string($path) ? $path : '', '/');

        if (! is_dir($directory) && ! mkdir($directory, 0o755, true) && ! is_dir($directory)) {
            throw new RuntimeException("Cannot create {$directory}.");
        }

        foreach ($this->versions($config, $clock)->all() as $version) {
            $file = "{$directory}/{$version->strategy}.v{$version->version}.strategy.yaml";

            if (is_file($file) && file_get_contents($file) === $version->document) {
                continue;
            }

            if (file_put_contents($file, $version->document) === false) {
                throw new RuntimeException("Cannot write {$file}.");
            }

            $this->line("{$version->label()} written to {$file}");
        }

        return self::SUCCESS;
    }
}
