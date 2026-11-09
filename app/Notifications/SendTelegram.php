<?php

declare(strict_types=1);

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Psr\Log\LoggerInterface;

/**
 * One message to the operator's Telegram chat, with the token and chat id set in the app or in `.env` (D-75). They
 * never appear in a log line or an exception: a failure names the status only.
 */
final class SendTelegram implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    public function __construct(public readonly string $text) {}

    public function handle(TelegramClient $client, LoggerInterface $log): void
    {
        if (! $client->configured()) {
            $log->warning('Telegram is not configured (Settings, or TELEGRAM_BOT_TOKEN and TELEGRAM_CHAT_ID in .env); the message stays in the journal only.');

            return;
        }

        $client->send($this->text);
    }
}
