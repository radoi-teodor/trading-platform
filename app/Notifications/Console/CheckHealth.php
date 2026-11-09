<?php

declare(strict_types=1);

namespace App\Notifications\Console;

use App\Notifications\Health\HealthAlerts;
use App\Notifications\Health\HealthChecks;
use Illuminate\Console\Command;

/** Prints the health of the machine (D-78); with --alert, tells the operator on Telegram what changed. */
final class CheckHealth extends Command
{
    protected $signature = 'platform:health {--alert : Send what started failing, still fails or recovered to Telegram}';

    protected $description = 'Check the market streams, the engines, the queues and the disk';

    public function handle(HealthChecks $checks, HealthAlerts $alerts): int
    {
        $results = $checks->run();

        foreach ($results as $result) {
            $this->line(sprintf('%-5s %s', $result->ok() ? 'ok' : 'FAIL', $result->name).($result->ok() ? '' : ": {$result->failure}"));
        }

        if ($this->option('alert')) {
            $alerts->process($results);
        }

        return array_filter($results, fn ($r): bool => ! $r->ok()) === [] ? self::SUCCESS : self::FAILURE;
    }
}
