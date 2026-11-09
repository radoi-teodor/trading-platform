<?php

declare(strict_types=1);

namespace App\Simulation\Console;

use App\Support\Clock\Clock;
use Illuminate\Console\Command;
use Illuminate\Contracts\Config\Repository;

/** Saves strategy documents as versions (D-61): a file, or every *.strategy.yaml of a directory, in name order. */
final class ImportStrategies extends Command
{
    use PreparesBacktests;

    protected $signature = 'strategy:import {path=catalog/strategies : A strategy document or a directory of them}';

    protected $description = 'Save strategy documents as versions; an unchanged document keeps its version';

    public function handle(Repository $config, Clock $clock): int
    {
        $path = $this->argument('path');
        $path = is_string($path) ? $path : '';
        $files = is_dir($path) ? (glob(rtrim($path, '/').'/*.strategy.yaml') ?: []) : [$path];
        natsort($files);
        $versions = $this->versions($config, $clock);
        $failed = false;

        foreach ($files as $file) {
            $text = is_file($file) ? file_get_contents($file) : false;

            if ($text === false) {
                $this->error("Cannot read {$file}.");
                $failed = true;

                continue;
            }

            $saved = $versions->save($text, 'operator');

            if ($saved->version === null) {
                foreach ($saved->check->errors as $error) {
                    $this->error("{$file}:{$error->position->line}:{$error->position->column}: {$error->problem}");
                }

                $failed = true;

                continue;
            }

            $this->line($saved->version->label().($saved->created ? ' saved' : ' unchanged')." from {$file}");
        }

        return $failed ? self::FAILURE : self::SUCCESS;
    }
}
