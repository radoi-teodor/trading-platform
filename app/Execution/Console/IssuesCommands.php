<?php

declare(strict_types=1);

namespace App\Execution\Console;

use App\Execution\Data\CommandKind;
use App\Execution\Models\Account;
use App\Execution\Models\EngineCommandRow;
use App\Support\Clock\Clock;

/** Writes a command for an account's engine, which applies it at its next event; it works with the web process down. */
trait IssuesCommands
{
    protected function issue(Clock $clock, CommandKind $kind, ?string $strategy = null): int
    {
        $name = (string) $this->argument('account');
        $account = Account::query()->where('name', $name)->first();

        if ($account === null) {
            $this->error("No account {$name}.");

            return self::FAILURE;
        }

        EngineCommandRow::query()->create(['account_id' => $account->id, 'kind' => $kind, 'strategy' => $strategy, 'issued_by' => 'cli', 'created_at' => $clock->now()]);
        $this->info(ucfirst(str_replace('_', ' ', $kind->value))." queued for {$account->name}; its engine applies it at the next minute.");

        return self::SUCCESS;
    }
}
