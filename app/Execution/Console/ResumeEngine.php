<?php

declare(strict_types=1);

namespace App\Execution\Console;

use App\Execution\Data\CommandKind;
use App\Support\Clock\Clock;
use Illuminate\Console\Command;

/** docs/risk-policy.md, section 5. */
final class ResumeEngine extends Command
{
    use IssuesCommands;

    protected $signature = 'engine:resume {account : The account name}';

    protected $description = 'Lift the account\'s halts (the day\'s loss lifts by itself at midnight UTC)';

    public function handle(Clock $clock): int
    {
        return $this->issue($clock, CommandKind::Resume);
    }
}
