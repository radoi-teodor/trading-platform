<?php

declare(strict_types=1);

use App\Execution\Models\EngineCommandRow;
use App\Http\Controllers\ReauthController;
use App\Livewire\AccountScreen;
use App\Livewire\ChartScreen;
use App\Livewire\HaltButton;
use App\Livewire\StrategiesScreen;
use App\Models\User;
use App\Simulation\Models\SimulationJob;
use App\Strategy\Dsl\Functions\Library;
use App\Strategy\Versions\StrategyVersion;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Testing\PendingCommand;
use Laravel\Fortify\Contracts\TwoFactorAuthenticationProvider;
use Livewire\Livewire;
use Pest\TestSuite;
use PragmaRX\Google2FA\Google2FA;
use Tests\TestCase;

uses(RefreshDatabase::class);

const OPERATOR_SECRET = 'JBSWY3DPEHPK3PXPJBSWY3DPEHPK3PXP';

function web(): TestCase
{
    $test = TestSuite::getInstance()->test;

    return $test instanceof TestCase ? $test : throw new LogicException('Runs inside a test.');
}

function operator(): User
{
    $user = new User(['name' => 'Operator', 'email' => 'op@example.test', 'password' => 'a long test password']);
    $user->forceFill(['two_factor_secret' => encrypt(OPERATOR_SECRET), 'two_factor_recovery_codes' => encrypt('[]'), 'two_factor_confirmed_at' => now()])->save();

    return $user;
}

it('sends a visitor to the login, and asks a TOTP code after the password', function (): void {
    operator();

    foreach (['/chart', '/strategies', '/account'] as $path) {
        web()->get($path)->assertRedirect('/login');
    }

    web()->get('/login')->assertOk()->assertSee('Log in');
    web()->post('/login', ['email' => 'op@example.test', 'password' => 'a long test password'])->assertRedirect('/two-factor-challenge');
    web()->post('/two-factor-challenge', ['code' => (new Google2FA)->getCurrentOtp(OPERATOR_SECRET)])->assertRedirect('/account');
    web()->get('/chart')->assertOk()->assertSee('Halt');
});

it('creates the one operator with a confirmed authenticator, and no second one', function (): void {
    app()->instance(TwoFactorAuthenticationProvider::class, new class implements TwoFactorAuthenticationProvider
    {
        public function generateSecretKey(): string
        {
            return OPERATOR_SECRET;
        }

        public function qrCodeUrl($companyName, $companyEmail, $secret): string
        {
            return 'otpauth://totp/test';
        }

        public function verify($secret, $code): bool
        {
            return $code === '123456';
        }
    });

    $command = web()->artisan('user:create', ['email' => 'op@example.test']);

    if (! $command instanceof PendingCommand) {
        throw new LogicException('Expected a pending command.');
    }

    $command->expectsQuestion('Password (hidden, at least 12 characters)', 'a long test password')
        ->expectsQuestion('The same password again', 'a long test password')
        ->expectsQuestion('A code from the app', '123456')
        ->assertSuccessful()
        ->run();

    expect(User::query()->sole()->two_factor_confirmed_at)->not->toBeNull()
        ->and(Artisan::call('user:create', ['email' => 'other@example.test']))->toBe(1);
});

it('halts every account from the button on every screen, without re-authentication', function (): void {
    web()->actingAs(operator());
    Artisan::call('account:create', ['name' => 'paper']);
    Artisan::call('account:create', ['name' => 'testnet', '--mode' => 'testnet']);

    Livewire::test(HaltButton::class)->call('halt')->assertSee('Halt queued for paper, testnet.');

    expect(EngineCommandRow::query()->pluck('kind')->map->value->all())->toBe(['halt', 'halt']);
});

it('asks for the password and a fresh code before resuming or flattening, not before halting', function (): void {
    web()->actingAs(operator());
    Artisan::call('account:create', ['name' => 'paper']);

    Livewire::test(AccountScreen::class)->call('command', 'flatten')->assertRedirect(route('reauth'));
    Livewire::test(AccountScreen::class)->call('command', 'halt')->assertSee('Halt queued for paper');

    web()->post('/confirm', ['password' => 'a long test password', 'code' => '000000'])->assertSessionHasErrors('password');
    web()->post('/confirm', ['password' => 'a long test password', 'code' => (new Google2FA)->getCurrentOtp(OPERATOR_SECRET)])->assertRedirect();

    session()->put(ReauthController::SESSION_KEY, time());
    Livewire::test(AccountScreen::class)->call('command', 'flatten')->assertSee('Flatten queued for paper');

    expect(EngineCommandRow::query()->orderBy('id')->pluck('kind')->map->value->all())->toBe(['halt', 'flatten']);
});

it('draws candles and layers with their types, and says why an expression cannot be drawn', function (): void {
    web()->actingAs(operator());
    backtestFixture(days: 3);

    $component = Livewire::test(ChartScreen::class)->set('timeframe', '15m')->set('expression', 'rsi(14)')->call('add');
    $payload = $component->viewData('payload');

    expect($component->get('layers'))->toBe(['ema(9)', 'ema(21)', 'rsi(14)'])
        ->and(data_get($payload, 'types'))->toBe(['ema(9)' => 'a price series', 'ema(21)' => 'a price series', 'rsi(14)' => 'a value series'])
        ->and(data_get($payload, 'candles'))->toHaveCount(288)
        ->and(data_get($payload, 'profile'))->toHaveCount(24)
        ->and(ChartScreen::template(Library::get('bb')))->toBe('bb().upper');

    Livewire::test(ChartScreen::class)->set('layers', ['ema(9'])->assertSee('expected ) to close the call to ema');
});

it('compiles as the operator types, reads the strategy back, saves it and runs it in the background', function (): void {
    web()->actingAs(operator());
    backtestFixture(days: 3);

    Livewire::test(StrategiesScreen::class)
        ->set('document', str_replace('1.5 * atr(14)', 'close > 2%', EMA_CROSS_DOCUMENT))
        ->assertSee('cannot compare a price series with a percent')
        ->set('document', EMA_CROSS_DOCUMENT)
        ->assertSee('On BTC/USDT spot, every time a 5-minute candle closes')
        ->call('save')
        ->assertSee('Saved as ema-cross-test@v1.')
        ->set('from', '2026-10-02')->set('to', '2026-10-04')
        ->call('run', 'backtest')
        ->assertSee('queued');

    expect(StrategyVersion::query()->sole()->author)->toBe('operator')
        ->and(SimulationJob::query()->sole()->status)->toBe('done');
});
