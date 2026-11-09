<?php

declare(strict_types=1);

use App\Execution\Data\EngineRecord;
use App\Notifications\SendTelegram;
use App\Notifications\TelegramNotifier;
use Carbon\CarbonImmutable;
use Illuminate\Http\Client\Request;
use Illuminate\Log\Events\MessageLogged;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

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
    expect($logged)->toBe(['warning: Telegram is not configured (TELEGRAM_BOT_TOKEN, TELEGRAM_CHAT_ID); the message stays in the journal only.']);
});
