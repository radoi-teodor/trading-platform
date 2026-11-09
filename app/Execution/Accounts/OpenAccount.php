<?php

declare(strict_types=1);

namespace App\Execution\Accounts;

use App\Execution\Data\EngineMode;
use App\Execution\Models\Account;
use App\Execution\Models\ExchangeKey;
use App\Market\Instruments\StoredInstruments;
use App\Support\Clock\Clock;
use App\Support\Number\Decimal;
use App\Support\Symbol;
use Carbon\CarbonImmutable;
use InvalidArgumentException;

/**
 * Opens paper and testnet accounts and stores a testnet account's Binance key (docs/engine.md, section 9; D-75). The
 * command line and the Settings screen both go through here, so the rules are the same on both paths. Live accounts
 * come with phase 6 and the operator's approval (D-16).
 */
final readonly class OpenAccount
{
    public function __construct(private Clock $clock, private StoredInstruments $instruments = new StoredInstruments) {}

    /**
     * @param  list<string>  $symbols  instruments to follow besides the strategies' (BTC/USDT when empty)
     *
     * @throws InvalidArgumentException with the reason, in the operator's words
     */
    public function open(string $name, string $mode, string $spot, string $futures, array $symbols, ?CarbonImmutable $start): Account
    {
        if (preg_match('/^[a-z0-9]+(-[a-z0-9]+)*$/', $name) !== 1 || strlen($name) > 40) {
            throw new InvalidArgumentException('An account name is lowercase letters, digits and hyphens.');
        }

        $engineMode = EngineMode::tryFrom($mode);

        if ($engineMode !== EngineMode::Paper && $engineMode !== EngineMode::Testnet) {
            throw new InvalidArgumentException('An account is paper or testnet; live accounts need phase 6 and the operator\'s approval (D-16).');
        }

        if (Account::query()->where('name', $name)->exists()) {
            throw new InvalidArgumentException("{$name} already exists.");
        }

        $parsed = [];

        foreach ($symbols === [] ? ['BTC/USDT'] : $symbols as $text) {
            $symbol = Symbol::parse(trim($text));
            $parsed[] = $this->instruments->isTradable($symbol) ? (string) $symbol : throw new InvalidArgumentException("{$symbol} is not a stored instrument; run market:instruments first.");
        }

        $now = $this->clock->now();

        return Account::query()->create([
            'name' => $name, 'mode' => $engineMode, 'quote' => 'USDT',
            'spot_balance' => self::amount($spot, 'spot'), 'futures_balance' => self::amount($futures, 'futures'),
            'symbols' => array_values(array_unique($parsed)),
            'started_at' => ($start ?? $now)->startOfMinute(), 'created_at' => $now,
        ]);
    }

    /** @throws InvalidArgumentException when the account is not testnet or the key is not shaped like Binance's */
    public function storeKey(Account $account, string $key, string $secret): void
    {
        if ($account->mode !== EngineMode::Testnet) {
            throw new InvalidArgumentException("{$account->name} is not a testnet account; keys for live accounts come with phase 6.");
        }

        if (preg_match('/^[A-Za-z0-9]{16,128}$/', $key) !== 1 || preg_match('/^[A-Za-z0-9]{16,128}$/', $secret) !== 1) {
            throw new InvalidArgumentException('A Binance key and secret are letters and digits; nothing was stored.');
        }

        ExchangeKey::query()->updateOrCreate(['account_id' => $account->id], ['api_key' => $key, 'api_secret' => $secret, 'created_at' => $this->clock->now()]);
    }

    public function forgetKey(Account $account): void
    {
        ExchangeKey::query()->where('account_id', $account->id)->delete();
    }

    /** What the screen may say about a stored key: the API key's last 4 characters and when, never the secret. */
    public function keyHint(Account $account): ?string
    {
        $key = ExchangeKey::query()->where('account_id', $account->id)->first();

        return $key === null ? null : 'ends in '.substr($key->api_key, -4).', stored '.$key->created_at->format('Y-m-d H:i').' UTC';
    }

    private static function amount(string $value, string $wallet): string
    {
        return preg_match('/^\d+(\.\d+)?$/', trim($value)) === 1 ? (string) Decimal::of(trim($value)) : throw new InvalidArgumentException("The {$wallet} balance is an amount of USDT.");
    }
}
