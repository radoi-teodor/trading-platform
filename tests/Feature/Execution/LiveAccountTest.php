<?php

declare(strict_types=1);

use App\Execution\Accounts\LiveGate;
use App\Execution\Accounts\OpenAccount;
use App\Execution\Models\Account;
use App\Execution\Models\ExchangeKey;
use App\Market\Data\MarketType;
use App\Market\Models\Instrument;
use App\Support\Clock\Clock;
use App\Support\Clock\SimClock;
use App\Support\Symbol;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Http;
use Illuminate\Testing\PendingCommand;
use PragmaRX\Google2FA\Google2FA;

uses(RefreshDatabase::class);

// api.binance.com is never reached: every response here is faked, in the shapes of Binance's documentation.

beforeEach(function (): void {
    app()->instance(Clock::class, new SimClock(CarbonImmutable::parse('2026-11-09 10:00', 'UTC')));
    Instrument::register('binance', MarketType::Spot, Symbol::parse('BTC/USDT'), CarbonImmutable::parse('2026-11-01', 'UTC'));
});

function liveAccount(): Account
{
    return app(OpenAccount::class)->open('live', 'live', '0', '0', ['BTC/USDT'], null, confirmed: true);
}

function approveLive(): PendingCommand
{
    $command = web()->artisan('account:approve-live', ['account' => 'live']);

    return $command instanceof PendingCommand ? $command : throw new LogicException('Expected a pending command.');
}

/** @param array<string, bool> $restrictions */
function fakeLiveBinance(array $restrictions = []): void
{
    Http::fake([
        'api.binance.com/api/v3/time' => Http::response(['serverTime' => CarbonImmutable::parse('2026-11-09 10:00', 'UTC')->getTimestampMs()]),
        'api.binance.com/sapi/v1/account/apiRestrictions*' => Http::response([
            'ipRestrict' => true, 'createTime' => 1, 'enableWithdrawals' => false, 'enableInternalTransfer' => false, 'permitsUniversalTransfer' => false,
            'enableVanillaOptions' => false, 'enableReading' => true, 'enableFutures' => true, 'enableMargin' => false, 'enableSpotAndMarginTrading' => true, ...$restrictions,
        ]),
        '*' => Http::response(['code' => -1, 'msg' => 'not faked'], 400),
    ]);
}

it('opens a live account only when the operator confirmed it', function (): void {
    expect(fn () => app(OpenAccount::class)->open('live', 'live', '0', '0', [], null))
        ->toThrow(InvalidArgumentException::class, 'A live account is opened after re-authentication');

    expect(liveAccount()->mode->value)->toBe('live');
});

it('refuses to run a live account the operator has not approved, or approved less than 24 hours ago', function (): void {
    $account = liveAccount();
    ExchangeKey::query()->create(['account_id' => $account->id, 'api_key' => 'FAKEKEYFAKEKEY1234', 'api_secret' => 'FAKESECRETFAKESECRET5678', 'created_at' => '2026-11-09']);
    fakeLiveBinance();

    expect(Artisan::call('engine:run', ['account' => 'live', '--once' => true]))->toBe(1)
        ->and(Artisan::output())->toContain('live is not approved for live trading');

    app(LiveGate::class)->approve($account, 'cli');

    expect(Artisan::call('engine:run', ['account' => 'live', '--once' => true]))->toBe(1)
        ->and(Artisan::output())->toContain('the approval of 2026-11-09 10:00 UTC takes effect at 2026-11-10 10:00 UTC');
});

it('refuses a key that can withdraw, or is not restricted to IP addresses', function (array $restrictions, string $why): void {
    $account = liveAccount();
    ExchangeKey::query()->create(['account_id' => $account->id, 'api_key' => 'FAKEKEYFAKEKEY1234', 'api_secret' => 'FAKESECRETFAKESECRET5678', 'created_at' => '2026-11-09']);
    app(LiveGate::class)->approve($account, 'cli');
    app()->instance(Clock::class, new SimClock(CarbonImmutable::parse('2026-11-10 10:01', 'UTC')));
    $flags = [];

    foreach ($restrictions as $name => $value) {
        $flags[(string) $name] = $value === true;
    }

    fakeLiveBinance($flags);

    expect(Artisan::call('engine:run', ['account' => 'live', '--once' => true]))->toBe(1)
        ->and(Artisan::output())->toContain($why);
    Http::assertSent(fn (Request $r): bool => str_contains($r->url(), 'api.binance.com/sapi/v1/account/apiRestrictions'));
    Http::assertNotSent(fn (Request $r): bool => str_contains($r->url(), 'testnet'));
})->with([
    'withdrawals' => [['enableWithdrawals' => true], 'its key can withdraw: create a key without the withdrawal right'],
    'no IP restriction' => [['ipRestrict' => false], 'its key is not restricted to IP addresses'],
    'spot trading off' => [['enableSpotAndMarginTrading' => false], 'its key cannot trade spot'],
]);

it('stops being approved when the operator withdraws the approval', function (): void {
    $account = liveAccount();
    $gate = app(LiveGate::class);
    $gate->approve($account, 'ui');
    app()->instance(Clock::class, new SimClock(CarbonImmutable::parse('2026-11-11', 'UTC')));

    expect(app(LiveGate::class)->refusal($account))->toBeNull();

    app(LiveGate::class)->withdraw($account);

    expect(app(LiveGate::class)->refusal($account))->toBe('live is not approved for live trading: approve it on the Settings screen or with php artisan account:approve-live live; it takes effect 24 hours later (D-82).');
});

it('approves from the command line after the password and a fresh code', function (): void {
    liveAccount();
    $user = operator();

    approveLive()
        ->expectsQuestion('Password (hidden)', 'a long test password')
        ->expectsQuestion('Code from your authenticator', (new Google2FA)->getCurrentOtp(OPERATOR_SECRET))
        ->expectsOutputToContain('approved; it takes effect at 2026-11-10 10:00 UTC')
        ->assertSuccessful();

    approveLive()
        ->expectsQuestion('Password (hidden)', 'wrong')
        ->expectsQuestion('Code from your authenticator', '000000')
        ->expectsOutputToContain('The password or the code does not match')
        ->assertFailed();

    expect($user->id)->toBeInt();
});
