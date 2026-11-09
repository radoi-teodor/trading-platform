<?php

declare(strict_types=1);

namespace App\Simulation\Console;

use App\Simulation\Ladder\Ladder;
use Illuminate\Console\Command;

/** Once a day: every deployment in force against its band; one out of band goes a step down at once (D-83). */
final class ReviewLadder extends Command
{
    protected $signature = 'strategy:review';

    protected $description = 'Check every deployment against its band and demote the ones that left it';

    public function handle(Ladder $ladder): int
    {
        $done = $ladder->review();

        foreach ($done as $line) {
            $this->warn($line);
        }

        $this->line($done === [] ? 'Every deployment is in its band.' : count($done).' demoted.');

        return self::SUCCESS;
    }
}
