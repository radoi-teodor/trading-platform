<?php

namespace App\Services;

use App\Models\NotificationChannel;
use App\Models\NotificationLog;
use App\Models\NotificationRule;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

class NotificationService
{
    public function dispatch(User $user, string $eventType, array $payload): void
    {
        // If a specific channel override is provided (e.g. for testing), use it directly
        if (isset($payload['channel_override'])) {
            $channel = $payload['channel_override'];
            unset($payload['channel_override']);
            $this->sendToChannel($user, $channel, $eventType, $payload);
            return;
        }

        // Find matching rules for this event type
        $rules = NotificationRule::where('user_id', $user->id)
            ->where('event_type', $eventType)
            ->where('is_active', true)
            ->get();

        if ($rules->isEmpty()) {
            return;
        }

        // Collect all channel IDs from matching rules
        $channelIds = $rules->pluck('channels')->flatten()->unique()->toArray();

        // Load active channels
        $channels = NotificationChannel::where('user_id', $user->id)
            ->where('is_active', true)
            ->whereIn('id', $channelIds)
            ->get();

        foreach ($channels as $channel) {
            $this->sendToChannel($user, $channel, $eventType, $payload);
        }
    }

    private function sendToChannel(User $user, NotificationChannel $channel, string $eventType, array $payload): void
    {
        $status = 'sent';
        $errorMessage = null;

        try {
            match ($channel->type) {
                'email' => $this->sendEmail($channel, $eventType, $payload),
                'telegram' => $this->sendTelegram($channel, $eventType, $payload),
                'discord' => $this->sendDiscord($channel, $eventType, $payload),
                default => throw new \InvalidArgumentException("Unknown channel type: {$channel->type}"),
            };
        } catch (\Exception $e) {
            $status = 'failed';
            $errorMessage = $e->getMessage();
        }

        NotificationLog::create([
            'user_id' => $user->id,
            'channel_type' => $channel->type,
            'event_type' => $eventType,
            'payload' => $payload,
            'status' => $status,
            'error' => $errorMessage,
            'sent_at' => now(),
        ]);
    }

    private function sendEmail(NotificationChannel $channel, string $eventType, array $payload): void
    {
        $toAddress = $channel->config['email'] ?? $channel->user->email;
        $subject = "Trading Platform: {$eventType}";
        $message = $payload['message'] ?? json_encode($payload);

        Mail::raw($message, function ($mail) use ($toAddress, $subject) {
            $mail->to($toAddress)->subject($subject);
        });
    }

    private function sendTelegram(NotificationChannel $channel, string $eventType, array $payload): void
    {
        $botToken = $channel->config['bot_token']
            ?? throw new \RuntimeException('Telegram bot token not configured.');
        $chatId = $channel->config['chat_id']
            ?? throw new \RuntimeException('Telegram chat ID not configured.');

        $text = "*{$eventType}*\n" . ($payload['message'] ?? json_encode($payload));

        $response = Http::post("https://api.telegram.org/bot{$botToken}/sendMessage", [
            'chat_id' => $chatId,
            'text' => $text,
            'parse_mode' => 'Markdown',
        ]);

        if (!$response->successful()) {
            throw new \RuntimeException("Telegram API error: " . $response->body());
        }
    }

    private function sendDiscord(NotificationChannel $channel, string $eventType, array $payload): void
    {
        $webhookUrl = $channel->config['webhook_url']
            ?? throw new \RuntimeException('Discord webhook URL not configured.');

        $content = "**{$eventType}**\n" . ($payload['message'] ?? json_encode($payload));

        $response = Http::post($webhookUrl, [
            'content' => $content,
        ]);

        if (!$response->successful()) {
            throw new \RuntimeException("Discord webhook error: " . $response->body());
        }
    }
}
