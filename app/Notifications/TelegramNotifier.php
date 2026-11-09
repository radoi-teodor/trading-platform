<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Execution\Contracts\Notifier;
use App\Execution\Data\EngineRecord;
use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Contracts\Config\Repository;
use Psr\Log\LoggerInterface;
use Throwable;

/**
 * Sends the engine events that need the operator to Telegram, through the `notifications` queue of its own connection
 * (`database` by default, like the simulations, D-62) so that an engine never waits on the network. Volatility shocks and price sanity trip many times a day and stay in the journal only.
 */
final readonly class TelegramNotifier implements Notifier
{
    /** Events worth a message; a breaker counts only when it is the feed, the venue or the clock. */
    public const array KINDS = [
        'daily_loss_halt', 'weekly_loss_halt', 'drawdown_halt', 'drawdown_flatten', 'command', 'liquidation',
        'reconciliation_mismatch', 'reconciliation_halt', 'replay_divergence', 'venue_error', 'clock_skew',
    ];

    public function __construct(private Dispatcher $dispatcher, private Repository $config, private LoggerInterface $log) {}

    public function engineEvent(string $account, EngineRecord $record): void
    {
        $breaker = in_array($record->kind, ['breaker_tripped', 'breaker_cleared'], true) && str_starts_with($record->detail, 'stale_data');

        if (! $breaker && ! in_array($record->kind, self::KINDS, true)) {
            return;
        }

        $connection = $this->config->get('services.telegram.queue_connection');
        $job = (new SendTelegram(sprintf('%s %s, %s UTC: %s', $account, str_replace('_', ' ', $record->kind), $record->time->format('Y-m-d H:i'), $record->detail)))
            ->onConnection(is_string($connection) ? $connection : null)->onQueue('notifications');

        try {
            $this->dispatcher->dispatch($job);
        } catch (Throwable $failure) {
            // The event is in the journal already; an engine never stops because a message could not be queued.
            $this->log->error("A notification could not be queued ({$failure->getMessage()}); it stays in the journal of {$account}.");
        }
    }
}
