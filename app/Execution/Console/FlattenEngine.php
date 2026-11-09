<?php

declare(strict_types=1);

namespace App\Execution\Console;

use App\Execution\Data\CommandKind;
use App\Support\Clock\Clock;
use Illuminate\Console\Command;

/** docs/risk-policy.md, section 5. */
final class FlattenEngine extends Command
{
    use IssuesCommands;

    protected $signature = 'engine:flatten {account : The account name}';

    protected $description = 'Cancel every order, close every position at market, then halt';

    public function handle(Clock $clock): int
    {
        return $this->issue($clock, CommandKind::Flatten);
    }
}
