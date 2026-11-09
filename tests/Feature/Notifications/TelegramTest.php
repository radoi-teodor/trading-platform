<?php

declare(strict_types=1);

use App\Execution\Data\EngineRecord;
use App\Notifications\Models\NotificationSettings;
use App\Notifications\SendTelegram;
use App\Notifications\SettingSource;
use App\Notifications\TelegramClient;
use App\Notifications\TelegramNotifier;
use App\Notifications\TelegramSettings;
use Carbon\CarbonImmutable;
use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\Request;
use Illuminate\Log\Events\MessageLogged;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

uses(RefreshDatabase::class);

const TEST_TELEGRAM_TOKEN = '123456:test-only-not-a-real-token';

it('queues the events that need the operator and leaves the routine breakers in the journal', function (): void {
    Bus::fake();
    $notifier = app(TelegramNotifier::class);
    $at = CarbonImmutable::parse('2026-11-09 10:00', 'UTC');

    $notifier->engineEvent('paper', new EngineRecord($at, 'drawdown_halt', 'equity 9000'));
    $notifier->engineEvent('paper', new EngineRecord($at, 'breaker_tripped', 'stale_data: the next minute closes at 2026-11-09T10:01:00Z'));
    $notifier->engineEvent('paper', new EngineRecord($at, 'breaker_tripped', 'volatility_shock BTC/USDT: a range of 9'));
    $notifier->engineEvent('paper', new EngineRecord($at, 'entry_expired', 'x'));

    Bus::assertDispatchedTimes(SendTelegram::class, 2);
    Bus::assertDispatched(SendTelegram::class, fn (SendTelegram $job): bool => $job->text === 'paper drawdown halt, 2026-11-09 10:00 UTC: equity 9000');
});

it('posts to the bot and never lets the token into an error', function (): void {
    config()->set('services.telegram', ['token' => TEST_TELEGRAM_TOKEN, 'chat_id' => '42']);
    Http::fake(['api.telegram.org/*' => Http::sequence()->push(['ok' => true])->push(['ok' => false], 401)]);

    app()->call([new SendTelegram('paper halted'), 'handle']);

    Http::assertSent(fn (Request $r): bool => $r->url() === 'https://api.telegram.org/bot'.TEST_TELEGRAM_TOKEN.'/sendMessage' && $r['chat_id'] === '42' && $r['text'] === 'paper halted');

    try {
        app()->call([new SendTelegram('again'), 'handle']);
        $message = 'no error';
    } catch (RuntimeException $failure) {
        $message = $failure->getMessage().($failure->getPrevious()?->getMessage() ?? '');
    }

    expect($message)->toBe('Telegram refused the message with status 401.')
        ->and($message)->not->toContain(TEST_TELEGRAM_TOKEN);
});

it('keeps the message in the journal only when Telegram is not configured', function (): void {
    config()->set('services.telegram', ['token' => null, 'chat_id' => null]);
    Http::fake();
    $logged = [];
    Log::listen(function (MessageLogged $message) use (&$logged): void {
        $logged[] = "{$message->level}: {$message->message}";
    });

    app()->call([new SendTelegram('paper halted'), 'handle']);

    Http::assertNothingSent();
    expect($logged)->toBe(['warning: Telegram is not configured (Settings, or TELEGRAM_BOT_TOKEN and TELEGRAM_CHAT_ID in .env); the message stays in the journal only.']);
});

it('keeps the engine running when a message cannot be queued, and says so in the log', function (): void {
    $dispatcher = Mockery::mock(Dispatcher::class);
    $dispatcher->shouldReceive('dispatch')->andThrow(new RuntimeException('Connection refused'));
    app()->instance(Dispatcher::class, $dispatcher);
    $logged = [];
    Log::listen(function (MessageLogged $message) use (&$logged): void {
        $logged[] = "{$message->level}: {$message->message}";
    });

    app(TelegramNotifier::class)->engineEvent('paper', new EngineRecord(CarbonImmutable::parse('2026-11-09', 'UTC'), 'drawdown_halt', 'equity 9000'));

    expect($logged)->toBe(['error: A notification could not be queued (Connection refused); it stays in the journal of paper.']);
});

it('prefers the token and chat set in the app over .env, and names where each comes from', function (): void {
    config()->set('services.telegram.token', 'env-token');
    config()->set('services.telegram.chat_id', null);
    $settings = app(TelegramSettings::class);

    expect($settings->token())->toBe('env-token')
        ->and($settings->tokenSource())->toBe(SettingSource::Env)
        ->and($settings->chatSource())->toBe(SettingSource::None);

    $settings->saveTelegram(TEST_TELEGRAM_TOKEN, '42', CarbonImmutable::parse('2026-11-09 10:00', 'UTC'));

    expect($settings->token())->toBe(TEST_TELEGRAM_TOKEN)
        ->and($settings->chatId())->toBe('42')
        ->and($settings->tokenSource())->toBe(SettingSource::App)
        ->and($settings->chatSource())->toBe(SettingSource::App);

    $settings->forgetTelegram();

    expect($settings->token())->toBe('env-token')->and($settings->tokenSource())->toBe(SettingSource::Env);
});

it('stores the token encrypted and never in plain text', function (): void {
    app(TelegramSettings::class)->saveTelegram(TEST_TELEGRAM_TOKEN, '42', CarbonImmutable::parse('2026-11-09', 'UTC'));

    $row = (array) DB::table('notification_settings')->first();

    expect(json_encode($row))->not->toContain(TEST_TELEGRAM_TOKEN)
        ->and(json_encode(NotificationSettings::query()->first()?->toArray()))->not->toContain(TEST_TELEGRAM_TOKEN);
});

it('sends with the token set in the app', function (): void {
    config()->set('services.telegram.token', null);
    app(TelegramSettings::class)->saveTelegram(TEST_TELEGRAM_TOKEN, '42', CarbonImmutable::parse('2026-11-09', 'UTC'));
    Http::fake(['api.telegram.org/*' => Http::response(['ok' => true])]);

    app()->call([new SendTelegram('paper halted'), 'handle']);

    Http::assertSent(fn (Request $r): bool => $r->url() === 'https://api.telegram.org/bot'.TEST_TELEGRAM_TOKEN.'/sendMessage' && $r['chat_id'] === '42');
});

it('sends the optional notifications only when the operator turned them on, and the others always', function (): void {
    $at = CarbonImmutable::parse('2026-11-09 10:00', 'UTC');
    $send = function () use ($at): void {
        $notifier = app(TelegramNotifier::class);
        $notifier->engineEvent('paper', new EngineRecord($at, 'command', 'halt by operator'));
        $notifier->engineEvent('paper', new EngineRecord($at, 'breaker_tripped', 'volatility_shock BTC/USDT: a range of 9'));
        $notifier->engineEvent('paper', new EngineRecord($at, 'breaker_cleared', 'volatility_shock BTC/USDT: the pause is over'));
        $notifier->engineEvent('paper', new EngineRecord($at, 'breaker_tripped', 'price_sanity BTC/USDT: close 1 against a median of 2'));
        $notifier->engineEvent('paper', new EngineRecord($at, 'reconciliation_mismatch', 'BTC 0.1 against 0.2'));
    };

    Bus::fake();
    $send();
    Bus::assertDispatchedTimes(SendTelegram::class, 2);

    $settings = app(TelegramSettings::class);
    $settings->setOptional('command', false);
    $settings->setOptional('volatility_shock', true);
    $settings->setOptional('price_sanity', true);
    Bus::fake();
    $send();

    // A shock that is over is not worth a second message.
    Bus::assertDispatchedTimes(SendTelegram::class, 3);
    Bus::assertNotDispatched(SendTelegram::class, fn (SendTelegram $job): bool => str_contains($job->text, 'command'));
});

it('refuses an optional notification it does not know', function (): void {
    app(TelegramSettings::class)->setOptional('drawdown_halt', false);
})->throws(InvalidArgumentException::class, 'drawdown_halt is always sent');

it('sends a test message at once and reports the status without the token', function (): void {
    config()->set('services.telegram', ['token' => TEST_TELEGRAM_TOKEN, 'chat_id' => '42', 'base_url' => 'https://api.telegram.org']);
    Http::fake(['api.telegram.org/*' => Http::sequence()->push(['ok' => true])->push(['ok' => false, 'description' => 'Unauthorized '.TEST_TELEGRAM_TOKEN], 401)->pushFailedConnection()]);
    $client = app(TelegramClient::class);

    expect($client->test())->toBe('Telegram accepted the test message.')
        ->and($client->test())->toBe('Telegram refused the message with status 401.')
        ->and($client->test())->toBe('Telegram could not be reached.');

    config()->set('services.telegram', ['token' => null, 'chat_id' => null]);
    expect($client->test())->toBe('Telegram is not configured: set the bot token and the chat id.');
});

it('posts to the configured Telegram host', function (): void {
    config()->set('services.telegram', ['token' => TEST_TELEGRAM_TOKEN, 'chat_id' => '42', 'base_url' => 'http://127.0.0.1:9999/']);
    Http::fake(['127.0.0.1:9999/*' => Http::response(['ok' => true])]);

    app(TelegramClient::class)->send('hello');

    Http::assertSent(fn (Request $r): bool => $r->url() === 'http://127.0.0.1:9999/bot'.TEST_TELEGRAM_TOKEN.'/sendMessage');
});
