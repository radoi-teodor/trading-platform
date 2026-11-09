<?php

declare(strict_types=1);

use App\Execution\Accounts\OpenAccount;
use App\Execution\Data\EngineMode;
use App\Execution\Models\Account;
use App\Execution\Models\ExchangeKey;
use App\Market\Data\MarketType;
use App\Market\Models\Instrument;
use App\Support\Clock\Clock;
use App\Support\Clock\SimClock;
use App\Support\Symbol;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    app()->instance(Clock::class, new SimClock(CarbonImmutable::parse('2026-11-09 10:00:30', 'UTC')));
    Instrument::register('binance', MarketType::Spot, Symbol::parse('BTC/USDT'), CarbonImmutable::parse('2026-11-01', 'UTC'));
});

it('opens a paper or testnet account starting at the current minute', function (): void {
    $account = app(OpenAccount::class)->open('paper-2', 'paper', '2500.5', '0', ['BTC/USDT'], null);

    expect($account->mode)->toBe(EngineMode::Paper)
        ->and($account->spot_balance)->toBe('2500.5')
        ->and($account->symbols)->toBe(['BTC/USDT'])
        ->and($account->started_at->toIso8601ZuluString())->toBe('2026-11-09T10:00:00Z')
        ->and(app(OpenAccount::class)->open('net', 'testnet', '0', '100', [], CarbonImmutable::parse('2026-11-10 08:00', 'UTC'))->symbols)->toBe(['BTC/USDT']);
});

it('refuses what an account cannot be', function (string $name, string $mode, string $equity, array $symbols, string $message): void {
    Account::query()->create(['name' => 'paper', 'mode' => EngineMode::Paper, 'quote' => 'USDT', 'spot_balance' => '1', 'futures_balance' => '0', 'symbols' => ['BTC/USDT'], 'started_at' => '2026-11-09', 'created_at' => '2026-11-09']);

    expect(fn () => app(OpenAccount::class)->open($name, $mode, $equity, '0', array_values(array_filter($symbols, is_string(...))), null))->toThrow(InvalidArgumentException::class, $message);
})->with([
    'a name with spaces' => ['my account', 'paper', '1', [], 'An account name is lowercase letters, digits and hyphens.'],
    'a live account' => ['real', 'live', '1', [], 'An account is paper or testnet; live accounts need phase 6 and the operator\'s approval (D-16).'],
    'a taken name' => ['paper', 'paper', '1', [], 'paper already exists.'],
    'a negative amount' => ['p2', 'paper', '-5', [], 'The spot balance is an amount of USDT.'],
    'an unknown instrument' => ['p2', 'paper', '1', ['DOGE/USDT'], 'DOGE/USDT is not a stored instrument; run market:instruments first.'],
]);

it('stores a testnet key encrypted, shows only its last characters, and forgets it', function (): void {
    $service = app(OpenAccount::class);
    $account = $service->open('net', 'testnet', '0', '0', [], null);

    $service->storeKey($account, 'abcdefghijklmnop1234', 'secretsecretsecret5678');

    $row = json_encode((array) DB::table('exchange_keys')->first());
    expect($row)->not->toContain('abcdefghijklmnop1234')->and($row)->not->toContain('secretsecretsecret5678')
        ->and($service->keyHint($account))->toBe('ends in 1234, stored 2026-11-09 10:00 UTC');

    $service->forgetKey($account);
    expect(ExchangeKey::query()->count())->toBe(0)->and($service->keyHint($account))->toBeNull();
});

it('stores keys for testnet accounts only, and only in their shape', function (): void {
    $service = app(OpenAccount::class);
    $paper = $service->open('paper', 'paper', '1', '0', [], null);
    $net = $service->open('net', 'testnet', '0', '0', [], null);

    expect(fn () => $service->storeKey($paper, 'abcdefghijklmnop1234', 'secretsecretsecret5678'))->toThrow(InvalidArgumentException::class, 'paper is not a testnet account; keys for live accounts come with phase 6.')
        ->and(fn () => $service->storeKey($net, 'short', 'secretsecretsecret5678'))->toThrow(InvalidArgumentException::class, 'A Binance key and secret are letters and digits; nothing was stored.');
});
