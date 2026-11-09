<?php

declare(strict_types=1);

namespace App\Execution\Console;

use App\Execution\Data\CommandKind;
use App\Support\Clock\Clock;
use Illuminate\Console\Command;

/** Pauses one strategy on an account, or resumes it: a paused strategy's intents are rejected, its protection stays. */
final class PauseStrategy extends Command
{
    use IssuesCommands;

    protected $signature = 'engine:pause {account : The account name} {strategy : The version, such as donchian-breakout@v1} {--resume : Resume it instead}';

    protected $description = 'Pause or resume one strategy on an account';

    public function handle(Clock $clock): int
    {
        $strategy = $this->argument('strategy');

        return $this->issue($clock, $this->option('resume') === true ? CommandKind::ResumeStrategy : CommandKind::Pause, is_string($strategy) ? $strategy : null);
    }
}
