<?php

declare(strict_types=1);

namespace App\Simulation\Console;

use InvalidArgumentException;

/** The options backtest:grid and strategy:gates share: workers and the walk-forward windows. */
trait ReadsGridOptions
{
    protected function workers(): int
    {
        $workers = is_string($this->option('workers')) ? $this->option('workers') : '1';

        if (preg_match('/^([1-9]|[12]\d|3[0-2])$/', $workers) !== 1) {
            throw new InvalidArgumentException('--workers is a whole number from 1 to 32.');
        }

        return (int) $workers;
    }

    /** @return array{in: string, out: string, step: string, min: int} */
    protected function walkOptions(): array
    {
        $text = fn (string $name): string => is_string($this->option($name)) ? $this->option($name) : '';
        [$in, $out, $step] = [$text('in'), $text('out'), $text('step')];

        foreach ([$in, $out, $step] as $length) {
            if (preg_match('/^[1-9]\d{0,3}[md]$/', $length) !== 1) {
                throw new InvalidArgumentException('--in and --out are whole numbers of days or months, such as 12m or 20d.');
            }
        }

        $min = $text('min-trades');

        if (preg_match('/^[1-9]\d{0,5}$/', $min) !== 1) {
            throw new InvalidArgumentException('--min-trades is a whole number from 1.');
        }

        return ['in' => $in, 'out' => $out, 'step' => $step, 'min' => (int) $min];
    }
}
