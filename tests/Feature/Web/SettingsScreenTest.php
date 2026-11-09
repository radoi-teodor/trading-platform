<?php

declare(strict_types=1);

use App\Agents\Models\McpToken;
use App\Execution\Accounts\OpenAccount;
use App\Execution\Models\Account;
use App\Execution\Models\ExchangeKey;
use App\Http\Controllers\ReauthController;
use App\Livewire\SettingsScreen;
use App\Market\Data\MarketType;
use App\Market\Models\Instrument;
use App\Models\User;
use App\Notifications\SettingSource;
use App\Notifications\TelegramSettings;
use App\Support\Symbol;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Livewire\Features\SupportTesting\Testable;
use Livewire\Livewire;

uses(RefreshDatabase::class);

const SETTINGS_TOKEN = '987654321:settings-test-only-not-a-real-token';

beforeEach(function (): void {
    Instrument::register('binance', MarketType::Spot, Symbol::parse('BTC/USDT'), CarbonImmutable::parse('2026-11-01', 'UTC'));
    web()->actingAs(operator());
});

function confirmed(): void
{
    session()->put(ReauthController::SESSION_KEY, time());
}

/**
 * What the browser receives: the HTML and the component's snapshot.
 *
 * @param  Testable<SettingsScreen>  $screen
 */
function received(Testable $screen): string
{
    return $screen->html().json_encode($screen->getData());
}

it('is behind the login and in the navigation', function (): void {
    $operator = User::query()->sole();
    auth()->logout();
    web()->get('/settings')->assertRedirect('/login');

    web()->actingAs($operator)->get('/settings')->assertOk()->assertSee('Settings')->assertSee('href="'.route('settings').'"', false);
});

it('hides the secret forms until the operator confirms, and refuses a save without it', function (): void {
    $screen = Livewire::test(SettingsScreen::class)
        ->assertSee('Confirm to change')
        ->assertDontSee('Bot token')
        ->set('telegramToken', SETTINGS_TOKEN)
        ->call('saveTelegram');

    $screen->assertRedirect(route('reauth'));
    expect(app(TelegramSettings::class)->tokenSource())->toBe(SettingSource::None)
        ->and(received($screen))->not->toContain(SETTINGS_TOKEN)
        ->and(session('url.intended'))->toBe(route('settings'));
});

it('saves the Telegram token and chat id, then never shows them back', function (): void {
    confirmed();
    config()->set('services.telegram.chat_id', '11');

    $screen = Livewire::test(SettingsScreen::class)
        ->assertSee('from .env')
        ->set('telegramToken', SETTINGS_TOKEN)
        ->set('telegramChat', '4242')
        ->call('saveTelegram')
        ->assertSee('Telegram is set.')
        ->assertSee('set in the app');

    expect(app(TelegramSettings::class)->token())->toBe(SETTINGS_TOKEN)
        ->and(app(TelegramSettings::class)->chatId())->toBe('4242')
        ->and(received($screen))->not->toContain(SETTINGS_TOKEN)
        ->and(received($screen))->not->toContain('4242')
        ->and(received(Livewire::test(SettingsScreen::class)))->not->toContain(SETTINGS_TOKEN);
});

it('says why a token is refused, and keeps nothing of it', function (): void {
    confirmed();

    $screen = Livewire::test(SettingsScreen::class)->set('telegramToken', 'not-a-token')->call('saveTelegram')
        ->assertSee('A bot token looks like 123456789:ABC..., as @BotFather gives it.');

    expect($screen->get('telegramToken'))->toBe('');
});

it('sends a test message and shows the status', function (): void {
    config()->set('services.telegram', ['token' => SETTINGS_TOKEN, 'chat_id' => '42', 'base_url' => 'https://api.telegram.org']);
    Http::fake(['api.telegram.org/*' => Http::response(['ok' => true])]);

    $screen = Livewire::test(SettingsScreen::class)->call('testTelegram')->assertSee('Telegram accepted the test message.');

    Http::assertSent(fn (Request $r): bool => $r['chat_id'] === '42');
    expect(received($screen))->not->toContain(SETTINGS_TOKEN);
});

it('turns optional notifications on and off without re-authentication, and not the others', function (): void {
    Livewire::test(SettingsScreen::class)
        ->assertSee('Halts, reconciliation')
        ->call('notify', 'volatility_shock', true)
        ->call('notify', 'command', false)
        ->call('notify', 'drawdown_halt', false)
        ->assertSee('drawdown_halt is always sent');

    expect(app(TelegramSettings::class)->optional())->toBe(['command' => false, 'volatility_shock' => true, 'price_sanity' => false, 'daily_report' => true]);
});

it('opens an account and says what the service refuses', function (): void {
    Livewire::test(SettingsScreen::class)
        ->set('accountName', 'paper-2')->set('accountMode', 'paper')->set('accountSpot', '5000')->set('accountSymbols', 'BTC/USDT')
        ->call('openAccount')
        ->assertSee('paper-2 (paper) is open')
        ->set('accountName', 'real')->set('accountMode', 'live')
        ->call('openAccount')
        ->assertSee('live accounts need phase 6')
        ->set('accountName', 'p3')->set('accountMode', 'paper')->set('accountSymbols', 'DOGE/USDT')
        ->call('openAccount')
        ->assertSee('DOGE/USDT is not a stored instrument');

    expect(Account::query()->pluck('name')->all())->toBe(['paper-2']);
});

it('stores a testnet key only after re-authentication and shows its last characters, never the secret', function (): void {
    $account = app(OpenAccount::class)->open('net', 'testnet', '0', '0', [], null);

    Livewire::test(SettingsScreen::class)->set('keyAccount', $account->id)->set('apiKey', 'FAKEKEYFAKEKEY1234')->set('apiSecret', 'secretsecretsecret5678')
        ->call('saveKey')->assertRedirect(route('reauth'));
    expect(ExchangeKey::query()->count())->toBe(0);

    confirmed();
    $screen = Livewire::test(SettingsScreen::class)->set('keyAccount', $account->id)->set('apiKey', 'FAKEKEYFAKEKEY1234')->set('apiSecret', 'secretsecretsecret5678')
        ->call('saveKey')
        ->assertSee('ends in 1234')
        ->assertSee('used from the engine\'s next start');

    expect(received($screen))->not->toContain('secretsecretsecret5678')->and(received($screen))->not->toContain('FAKEKEYFAKEKEY1234')
        ->and(ExchangeKey::query()->count())->toBe(1);

    $screen->call('forgetKey', $account->id);
    expect(ExchangeKey::query()->count())->toBe(0);
});

it('shows a new MCP token once, and revokes without re-authentication', function (): void {
    Livewire::test(SettingsScreen::class)->set('tokenName', 'laptop')->call('createToken')->assertRedirect(route('reauth'));
    expect(McpToken::query()->count())->toBe(0);

    confirmed();
    $screen = Livewire::test(SettingsScreen::class)->set('tokenName', 'laptop')->call('createToken');
    $secret = $screen->get('newToken');

    expect($secret)->toMatch('/^tx_[A-Za-z0-9]{40}$/');
    $screen->assertSee($secret)->call('$refresh')->assertDontSee($secret);
    expect(received(Livewire::test(SettingsScreen::class)))->not->toContain($secret);

    session()->forget(ReauthController::SESSION_KEY);
    Livewire::test(SettingsScreen::class)->assertSee('laptop')->call('revokeToken', 'laptop')->assertSee('Revoked the token of laptop.');
    expect(McpToken::query()->sole()->revoked_at)->not->toBeNull();
});

it('shows the risk limits and breaker thresholds without a way to change them', function (): void {
    Livewire::test(SettingsScreen::class)
        ->assertSee('risk_per_trade')->assertSee('0.005')
        ->assertSee('Volatility shock')
        ->assertSee('Changing a limit is a code change with a test (D-19).');
});

it('turns the health check of an account off and on without re-authentication', function (): void {
    $account = app(OpenAccount::class)->open('paper', 'paper', '1', '0', [], null);

    Livewire::test(SettingsScreen::class)->call('monitor', $account->id, false)->assertSee('The health check no longer watches paper.');
    expect($account->refresh()->monitored)->toBeFalse();

    Livewire::test(SettingsScreen::class)->call('monitor', $account->id, true);
    expect($account->refresh()->monitored)->toBeTrue();
});
