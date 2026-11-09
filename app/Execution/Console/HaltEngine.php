<?php

declare(strict_types=1);

namespace App\Execution\Console;

use App\Execution\Data\CommandKind;
use App\Support\Clock\Clock;
use Illuminate\Console\Command;

/** docs/risk-policy.md, section 5. */
final class HaltEngine extends Command
{
    use IssuesCommands;

    protected $signature = 'engine:halt {account : The account name}';

    protected $description = 'No new entries on the account; working entries are cancelled, protection stays';

    public function handle(Clock $clock): int
    {
        return $this->issue($clock, CommandKind::Halt);
    }
}
