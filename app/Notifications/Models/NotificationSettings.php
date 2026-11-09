<?php

declare(strict_types=1);

namespace App\Notifications\Models;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Model;

/**
 * The one row of the operator's notification settings (D-75): the Telegram token and chat id with encrypted casts,
 * hidden from every array and JSON form, and the optional notifications.
 *
 * @property int $id
 * @property string|null $telegram_token
 * @property string|null $telegram_chat_id
 * @property CarbonImmutable|null $telegram_set_at
 * @property bool $notify_command
 * @property bool $notify_volatility_shock
 * @property bool $notify_price_sanity
 * @property CarbonImmutable|null $updated_at
 */
final class NotificationSettings extends Model
{
    public const CREATED_AT = null;

    protected $table = 'notification_settings';

    protected $fillable = ['telegram_token', 'telegram_chat_id', 'telegram_set_at', 'notify_command', 'notify_volatility_shock', 'notify_price_sanity'];

    protected $hidden = ['telegram_token', 'telegram_chat_id'];

    protected $attributes = ['notify_command' => true, 'notify_volatility_shock' => false, 'notify_price_sanity' => false];

    protected function casts(): array
    {
        return [
            'telegram_token' => 'encrypted', 'telegram_chat_id' => 'encrypted', 'telegram_set_at' => 'immutable_datetime',
            'notify_command' => 'boolean', 'notify_volatility_shock' => 'boolean', 'notify_price_sanity' => 'boolean', 'updated_at' => 'immutable_datetime',
        ];
    }
}
