<?php

declare(strict_types=1);

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Http\Client\Factory;
use Psr\Log\LoggerInterface;
use RuntimeException;
use Throwable;

/**
 * One message to the operator's Telegram chat. The bot token and chat id come from `.env` (TELEGRAM_BOT_TOKEN,
 * TELEGRAM_CHAT_ID) and never appear in a log line or an exception: a failure names the status only.
 */
final class SendTelegram implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    public function __construct(public readonly string $text) {}

    public function handle(Repository $config, Factory $http, LoggerInterface $log): void
    {
        $token = $config->get('services.telegram.token');
        $chat = $config->get('services.telegram.chat_id');

        if (! is_string($token) || $token === '' || ! is_string($chat) || $chat === '') {
            $log->warning('Telegram is not configured (TELEGRAM_BOT_TOKEN, TELEGRAM_CHAT_ID); the message stays in the journal only.');

            return;
        }

        try {
            $response = $http->asJson()->timeout(10)->post("https://api.telegram.org/bot{$token}/sendMessage", ['chat_id' => $chat, 'text' => $this->text]);
        } catch (Throwable) {
            // The client's message carries the URL, and the URL carries the token: rethrow without it.
            throw new RuntimeException('Telegram could not be reached.');
        }

        if (! $response->successful()) {
            throw new RuntimeException("Telegram refused the message with status {$response->status()}.");
        }
    }
}
