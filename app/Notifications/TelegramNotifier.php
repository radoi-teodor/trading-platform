<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Execution\Contracts\Notifier;
use App\Execution\Data\EngineRecord;
use Illuminate\Contracts\Bus\Dispatcher;

/**
 * Sends the engine events that need the operator to Telegram, through the `notifications` queue so that an engine
 * never waits on the network. Volatility shocks and price sanity trip many times a day and stay in the journal only.
 */
final readonly class TelegramNotifier implements Notifier
{
    /** Events worth a message; a breaker counts only when it is the feed, the venue or the clock. */
    public const array KINDS = [
        'daily_loss_halt', 'weekly_loss_halt', 'drawdown_halt', 'drawdown_flatten', 'command', 'liquidation',
        'reconciliation_mismatch', 'reconciliation_halt', 'replay_divergence', 'venue_error', 'clock_skew',
    ];

    public function __construct(private Dispatcher $dispatcher) {}

    public function engineEvent(string $account, EngineRecord $record): void
    {
        $breaker = in_array($record->kind, ['breaker_tripped', 'breaker_cleared'], true) && str_starts_with($record->detail, 'stale_data');

        if (! $breaker && ! in_array($record->kind, self::KINDS, true)) {
            return;
        }

        $this->dispatcher->dispatch((new SendTelegram(sprintf('%s %s, %s UTC: %s', $account, str_replace('_', ' ', $record->kind), $record->time->format('Y-m-d H:i'), $record->detail)))->onQueue('notifications'));
    }
}
