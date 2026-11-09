<?php

declare(strict_types=1);

namespace App\Notifications;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Http\Client\Factory;
use RuntimeException;
use Throwable;

/**
 * Posts a message to the operator's chat with the token and chat id of `TelegramSettings` (D-75). The token is in the
 * URL, so no failure carries the client's message, the URL or the response body: only the status.
 */
final readonly class TelegramClient
{
    public function __construct(private TelegramSettings $settings, private Factory $http, private Repository $config) {}

    public function configured(): bool
    {
        return $this->settings->token() !== null && $this->settings->chatId() !== null;
    }

    /** @throws RuntimeException when Telegram cannot be reached or refuses the message */
    public function send(string $text): void
    {
        $token = $this->settings->token();
        $chat = $this->settings->chatId();

        if ($token === null || $chat === null) {
            throw new RuntimeException('Telegram is not configured: set the bot token and the chat id.');
        }

        $base = $this->config->get('services.telegram.base_url');
        $base = rtrim(is_string($base) && $base !== '' ? $base : 'https://api.telegram.org', '/');

        try {
            $response = $this->http->asJson()->timeout(10)->post("{$base}/bot{$token}/sendMessage", ['chat_id' => $chat, 'text' => $text]);
        } catch (Throwable) {
            throw new RuntimeException('Telegram could not be reached.');
        }

        if (! $response->successful()) {
            throw new RuntimeException("Telegram refused the message with status {$response->status()}.");
        }
    }

    /** Sends a test message now and says what happened, for the Settings screen. */
    public function test(): string
    {
        try {
            $this->send('Trading X: a test message from the Settings screen.');
        } catch (RuntimeException $failure) {
            return $failure->getMessage();
        }

        return 'Telegram accepted the test message.';
    }
}
