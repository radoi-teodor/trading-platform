<?php

declare(strict_types=1);

namespace App\Execution\Console;

use App\Execution\Accounts\LiveGate;
use App\Execution\Models\Account;
use Illuminate\Console\Command;
use Illuminate\Contracts\Encryption\Encrypter;
use Illuminate\Contracts\Hashing\Hasher;
use InvalidArgumentException;
use Laravel\Fortify\Contracts\TwoFactorAuthenticationProvider;

/** The operator's approval of a live account, after the password and a code; effective 24 hours later (D-82). */
final class ApproveLive extends Command
{
    use ConfirmsOperator;

    protected $signature = 'account:approve-live {account : The live account} {--withdraw : Withdraw the approval instead, at once}';

    protected $description = 'Approve a live account for trading (effective 24 hours later), or withdraw the approval';

    public function handle(LiveGate $gate, TwoFactorAuthenticationProvider $totp, Hasher $hasher, Encrypter $encrypter): int
    {
        $account = Account::query()->where('name', (string) $this->argument('account'))->first();

        if ($account === null) {
            $this->error('No such account.');

            return self::FAILURE;
        }

        if ($this->option('withdraw')) {
            $gate->withdraw($account);
            $this->info("The approval of {$account->name} is withdrawn; its engine stops at its next start.");

            return self::SUCCESS;
        }

        if (! $this->operatorConfirms($totp, $hasher, $encrypter)) {
            return self::FAILURE;
        }

        try {
            $approval = $gate->approve($account, 'cli');
        } catch (InvalidArgumentException $failure) {
            $this->error($failure->getMessage());

            return self::FAILURE;
        }

        $this->info("{$account->name} approved; it takes effect at {$approval->effective_at->format('Y-m-d H:i')} UTC. Withdraw with --withdraw.");

        return self::SUCCESS;
    }
}
