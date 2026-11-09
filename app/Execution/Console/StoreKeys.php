<?php

declare(strict_types=1);

namespace App\Execution\Console;

use App\Execution\Data\EngineMode;
use App\Execution\Models\Account;
use App\Execution\Models\ExchangeKey;
use App\Support\Clock\Clock;
use Illuminate\Console\Command;

/**
 * Stores an account's Binance key and secret, asked without echo, encrypted (D-20). They are never an argument, an
 * option, an environment variable or a line in a file (docs/security.md, section 2).
 */
final class StoreKeys extends Command
{
    protected $signature = 'account:keys {account : The account name}';

    protected $description = 'Store the Binance key of a testnet account, asked interactively';

    public function handle(Clock $clock): int
    {
        $name = (string) $this->argument('account');
        $account = Account::query()->where('name', $name)->first();

        if ($account === null || $account->mode !== EngineMode::Testnet) {
            $this->error("{$name} is not a testnet account; keys for live accounts come with phase 6.");

            return self::FAILURE;
        }

        $key = $this->secret('API key (hidden)');
        $secret = $this->secret('API secret (hidden)');

        if (! is_string($key) || ! is_string($secret) || preg_match('/^[A-Za-z0-9]{16,128}$/', $key) !== 1 || preg_match('/^[A-Za-z0-9]{16,128}$/', $secret) !== 1) {
            $this->error('A Binance key and secret are letters and digits; nothing was stored.');

            return self::FAILURE;
        }

        ExchangeKey::query()->updateOrCreate(['account_id' => $account->id], ['api_key' => $key, 'api_secret' => $secret, 'created_at' => $clock->now()]);
        $this->info("The key of {$name} is stored, encrypted. Check on Binance that it can trade, cannot withdraw and is restricted to this machine's IP.");

        return self::SUCCESS;
    }
}
