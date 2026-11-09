<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Agents\Models\McpToken;
use App\Agents\Tokens;
use App\Execution\Accounts\OpenAccount;
use App\Execution\Data\EngineMode;
use App\Execution\Models\Account;
use App\Http\Controllers\ReauthController;
use App\Notifications\TelegramClient;
use App\Notifications\TelegramSettings;
use App\Risk\Breakers\MarketBreakers;
use App\Risk\RiskPolicy;
use App\Support\Clock\Clock;
use App\Support\Number\Decimal;
use Carbon\CarbonImmutable;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\View\View;
use InvalidArgumentException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

/**
 * The operator's settings (D-75): Telegram and the optional notifications, paper and testnet accounts with their
 * testnet keys, MCP tokens, and the risk limits read-only. A stored secret is never shown back and the fields that
 * carry one are emptied after every action. Changing the Telegram token or chat id, a key or creating a token asks
 * for re-authentication first (docs/security.md, section 3).
 */
#[Layout('layouts.app')]
#[Title('Settings')]
final class SettingsScreen extends Component
{
    public string $telegramToken = '';

    public string $telegramChat = '';

    public string $telegramMessage = '';

    public string $notifyMessage = '';

    public string $accountName = '';

    public string $accountMode = 'paper';

    public string $accountSpot = '10000';

    public string $accountFutures = '0';

    public string $accountSymbols = 'BTC/USDT';

    public string $accountStart = '';

    public string $accountMessage = '';

    public ?int $keyAccount = null;

    public string $apiKey = '';

    public string $apiSecret = '';

    public string $keyMessage = '';

    public string $tokenName = '';

    /** A new token's secret, shown in the response that created it and gone at the next one. */
    public string $newToken = '';

    public string $tokenMessage = '';

    public function hydrate(): void
    {
        $this->newToken = '';
    }

    public function confirm(): void
    {
        $this->askReauth();
    }

    public function saveTelegram(TelegramSettings $settings, Clock $clock): void
    {
        [$token, $chat] = [trim($this->telegramToken), trim($this->telegramChat)];
        $this->telegramToken = $this->telegramChat = '';

        if (! $this->confirmed()) {
            return;
        }

        if ($token === '' && $chat === '') {
            $this->telegramMessage = 'Type a new bot token, a new chat id or both.';

            return;
        }

        try {
            $settings->saveTelegram($token === '' ? null : $token, $chat === '' ? null : $chat, $clock->now());
            $this->telegramMessage = 'Telegram is set. Send a test message to check it.';
        } catch (InvalidArgumentException $failure) {
            $this->telegramMessage = $failure->getMessage();
        }
    }

    public function forgetTelegram(TelegramSettings $settings): void
    {
        if ($this->confirmed()) {
            $settings->forgetTelegram();
            $this->telegramMessage = 'The token and chat id set in the app are removed; .env applies again.';
        }
    }

    public function testTelegram(TelegramClient $client): void
    {
        $this->telegramMessage = $client->test();
    }

    public function notify(string $kind, bool $on, TelegramSettings $settings): void
    {
        try {
            $settings->setOptional($kind, $on);
            $this->notifyMessage = '';
        } catch (InvalidArgumentException $failure) {
            $this->notifyMessage = $failure->getMessage();
        }
    }

    public function openAccount(OpenAccount $accounts): void
    {
        try {
            $start = trim($this->accountStart) === '' ? null : CarbonImmutable::parse(trim($this->accountStart), 'UTC');
            $symbols = array_values(array_filter(array_map(trim(...), explode(',', $this->accountSymbols)), fn (string $s): bool => $s !== ''));
            $account = $accounts->open(trim($this->accountName), $this->accountMode, $this->accountSpot, $this->accountFutures, $symbols, $start);
        } catch (InvalidArgumentException $failure) {
            $this->accountMessage = $failure->getMessage();

            return;
        }

        $this->accountName = '';
        $this->accountMessage = "{$account->name} ({$account->mode->value}) is open from {$account->started_at->format('Y-m-d H:i')} UTC. Run it with php artisan engine:run {$account->name}.";
    }

    /** Lowers what is watched, so it needs no re-authentication: the alert it silences is the operator's own. */
    public function monitor(int $account, bool $on): void
    {
        $found = Account::query()->find($account);

        if ($found !== null) {
            $found->update(['monitored' => $on]);
            $this->accountMessage = $on ? "The health check watches {$found->name} again." : "The health check no longer watches {$found->name}.";
        }
    }

    public function saveKey(OpenAccount $accounts): void
    {
        [$key, $secret] = [trim($this->apiKey), trim($this->apiSecret)];
        $this->apiKey = $this->apiSecret = '';

        if (! $this->confirmed()) {
            return;
        }

        $account = $this->keyAccount === null ? null : Account::query()->find($this->keyAccount);

        if ($account === null) {
            $this->keyMessage = 'Pick a testnet account.';

            return;
        }

        try {
            $accounts->storeKey($account, $key, $secret);
            $this->keyMessage = "The key of {$account->name} is stored, encrypted, and used from the engine's next start. Check on Binance that it can trade, cannot withdraw and is restricted to this machine's IP.";
        } catch (InvalidArgumentException $failure) {
            $this->keyMessage = $failure->getMessage();
        }
    }

    public function forgetKey(int $account, OpenAccount $accounts): void
    {
        $found = Account::query()->find($account);

        if ($found !== null && $this->confirmed()) {
            $accounts->forgetKey($found);
            $this->keyMessage = "The key of {$found->name} is deleted; its engine stops at its next start until a new one is stored.";
        }
    }

    public function createToken(Tokens $tokens): void
    {
        if (! $this->confirmed()) {
            return;
        }

        try {
            $this->newToken = $tokens->create(trim($this->tokenName));
            $this->tokenMessage = "Token for {$this->tokenName}, shown once; it cannot be read back. Send it as \"Authorization: Bearer <token>\" to /mcp.";
            $this->tokenName = '';
        } catch (InvalidArgumentException $failure) {
            $this->tokenMessage = $failure->getMessage();
        }
    }

    public function revokeToken(string $name, Tokens $tokens): void
    {
        try {
            $tokens->revoke($name);
            $this->tokenMessage = "Revoked the token of {$name}.";
        } catch (InvalidArgumentException $failure) {
            $this->tokenMessage = $failure->getMessage();
        }
    }

    public function render(TelegramSettings $telegram, OpenAccount $accounts, Repository $config): View
    {
        $accountList = Account::query()->orderBy('name')->get();
        $risk = $config->get('risk.account');

        return view('livewire.settings-screen', [
            'fresh' => ReauthController::fresh(),
            'telegram' => $telegram,
            'optional' => $telegram->optional(),
            'accounts' => $accountList,
            'testnet' => $accountList->filter(fn (Account $a): bool => $a->mode === EngineMode::Testnet)->values(),
            'hints' => $accountList->mapWithKeys(fn (Account $a): array => [$a->id => $a->mode === EngineMode::Testnet ? $accounts->keyHint($a) : null])->all(),
            'tokens' => McpToken::query()->orderBy('name')->orderByDesc('id')->get(),
            'risk' => RiskPolicy::account(is_array($risk) ? $risk : [])->toArray(),
            'breakers' => [
                'Price sanity' => sprintf('a close more than %s %% from the median close of the %d minutes before it rejects entries on the instrument while it lasts', (string) Decimal::of(MarketBreakers::PRICE_BAND)->times(Decimal::of('100'))->roundedTo(0), MarketBreakers::MEDIAN_MINUTES),
                'Volatility shock' => sprintf('a minute whose true range is above %d times the mean true range of the %d minutes before it pauses entries for %d minutes', MarketBreakers::SHOCK_MULTIPLE, MarketBreakers::ATR_MINUTES, MarketBreakers::SHOCK_PAUSE_MINUTES),
            ],
        ]);
    }

    /** True when the operator re-authenticated in the last 10 minutes; otherwise sends them to do it and back here. */
    private function confirmed(): bool
    {
        if (ReauthController::fresh()) {
            return true;
        }

        $this->askReauth();

        return false;
    }

    private function askReauth(): void
    {
        session()->put('url.intended', route('settings'));
        $this->redirectRoute('reauth');
    }
}
