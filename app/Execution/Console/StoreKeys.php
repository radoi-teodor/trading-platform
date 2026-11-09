<?php

declare(strict_types=1);

namespace App\Execution\Console;

use App\Execution\Accounts\OpenAccount;
use App\Execution\Data\EngineMode;
use App\Execution\Models\Account;
use Illuminate\Console\Command;
use InvalidArgumentException;

/**
 * Stores an account's Binance key and secret, asked without echo, encrypted (D-20). They are never an argument, an
 * option, an environment variable or a line in a file (docs/security.md, section 2).
 */
final class StoreKeys extends Command
{
    protected $signature = 'account:keys {account : The account name}';

    protected $description = 'Store the Binance key of a testnet account, asked interactively';

    public function handle(OpenAccount $accounts): int
    {
        $name = (string) $this->argument('account');
        $account = Account::query()->where('name', $name)->first();

        if ($account === null || $account->mode !== EngineMode::Testnet) {
            $this->error("{$name} is not a testnet account; keys for live accounts come with phase 6.");

            return self::FAILURE;
        }

        $key = $this->secret('API key (hidden)');
        $secret = $this->secret('API secret (hidden)');

        try {
            $accounts->storeKey($account, is_string($key) ? $key : '', is_string($secret) ? $secret : '');
        } catch (InvalidArgumentException $failure) {
            $this->error($failure->getMessage());

            return self::FAILURE;
        }

        $this->info("The key of {$name} is stored, encrypted. Check on Binance that it can trade, cannot withdraw and is restricted to this machine's IP.");

        return self::SUCCESS;
    }
}
