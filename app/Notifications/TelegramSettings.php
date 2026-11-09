<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Notifications\Models\NotificationSettings;
use Carbon\CarbonImmutable;
use Illuminate\Contracts\Config\Repository;
use InvalidArgumentException;

/**
 * The Telegram token and chat id and the optional notifications (D-75). A value set in the app wins over `.env`
 * (`services.telegram.*`), and each value says where it comes from.
 */
final readonly class TelegramSettings
{
    /** The notifications the operator may turn off or on; every other one is always sent. */
    public const array OPTIONAL = ['command' => 'notify_command', 'volatility_shock' => 'notify_volatility_shock', 'price_sanity' => 'notify_price_sanity', 'daily_report' => 'notify_daily_report'];

    public function __construct(private Repository $config) {}

    public function token(): ?string
    {
        return $this->row()->telegram_token ?? $this->env('token');
    }

    public function chatId(): ?string
    {
        return $this->row()->telegram_chat_id ?? $this->env('chat_id');
    }

    public function tokenSource(): SettingSource
    {
        return $this->source($this->row()?->telegram_token, 'token');
    }

    public function chatSource(): SettingSource
    {
        return $this->source($this->row()?->telegram_chat_id, 'chat_id');
    }

    public function setAt(): ?CarbonImmutable
    {
        return $this->row()?->telegram_set_at;
    }

    /** Stores what is given; a null keeps the stored value. */
    public function saveTelegram(?string $token, ?string $chatId, CarbonImmutable $now): void
    {
        $row = $this->row() ?? new NotificationSettings;

        if ($token !== null) {
            $row->telegram_token = preg_match('/^\d{3,20}:[A-Za-z0-9_-]{20,100}$/', $token) === 1 ? $token : throw new InvalidArgumentException('A bot token looks like 123456789:ABC..., as @BotFather gives it.');
        }

        if ($chatId !== null) {
            $row->telegram_chat_id = preg_match('/^-?\d{1,20}$/', $chatId) === 1 ? $chatId : throw new InvalidArgumentException('A chat id is a number, negative for a group.');
        }

        $row->telegram_set_at = $now;
        $row->save();
    }

    /** Drops the token and chat id set in the app, so `.env` applies again. */
    public function forgetTelegram(): void
    {
        $row = $this->row();

        if ($row !== null) {
            $row->telegram_token = null;
            $row->telegram_chat_id = null;
            $row->telegram_set_at = null;
            $row->save();
        }
    }

    /** @return array<string, bool> each optional notification and whether it is sent */
    public function optional(): array
    {
        $row = $this->row() ?? new NotificationSettings;

        return array_map(fn (string $column): bool => (bool) $row->getAttribute($column), self::OPTIONAL);
    }

    public function setOptional(string $kind, bool $on): void
    {
        $column = self::OPTIONAL[$kind] ?? throw new InvalidArgumentException("{$kind} is always sent; only ".implode(', ', array_keys(self::OPTIONAL)).' can be turned off or on.');
        $row = $this->row() ?? new NotificationSettings;
        $row->setAttribute($column, $on);
        $row->save();
    }

    public function wants(string $kind): bool
    {
        return ! isset(self::OPTIONAL[$kind]) || $this->optional()[$kind];
    }

    private function row(): ?NotificationSettings
    {
        return NotificationSettings::query()->orderBy('id')->first();
    }

    private function source(?string $stored, string $key): SettingSource
    {
        return match (true) {
            $stored !== null && $stored !== '' => SettingSource::App,
            $this->env($key) !== null => SettingSource::Env,
            default => SettingSource::None,
        };
    }

    private function env(string $key): ?string
    {
        $value = $this->config->get("services.telegram.{$key}");

        return is_string($value) && $value !== '' ? $value : null;
    }
}
