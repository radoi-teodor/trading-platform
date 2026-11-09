<?php

declare(strict_types=1);

namespace App\Notifications\Health;

use App\Notifications\TelegramClient;
use App\Support\Clock\Clock;
use Carbon\CarbonImmutable;
use Illuminate\Database\ConnectionInterface;
use Psr\Log\LoggerInterface;
use RuntimeException;
use UnexpectedValueException;

/**
 * Tells the operator what the health checks found (D-78): a message when a check starts failing, a reminder every
 * hour while it fails and one when it recovers. Sent at once through the Telegram client, not the queue, which may
 * be what failed; a message that cannot be sent is tried again at the next run.
 */
final readonly class HealthAlerts
{
    public const int REMINDER_SECONDS = 3600;

    public function __construct(private ConnectionInterface $db, private Clock $clock, private TelegramClient $telegram, private LoggerInterface $log) {}

    /** @param list<HealthResult> $results */
    public function process(array $results): void
    {
        $now = $this->clock->now();
        $rows = $this->db->table('health_checks')->get()->keyBy('name');
        [$failed, $reminders, $recovered] = [[], [], []];

        foreach ($results as $result) {
            $row = $rows->get($result->name);

            if ($result->ok()) {
                if ($row !== null && is_string($row->alerted_at ?? null)) {
                    $recovered[$result->name] = intdiv($now->getTimestamp() - self::at($row->failing_since)->getTimestamp(), 60);
                } elseif ($row !== null) {
                    $this->db->table('health_checks')->where('name', $result->name)->delete();
                }

                continue;
            }

            if ($row === null) {
                $this->db->table('health_checks')->insert(['name' => $result->name, 'failing_since' => $now, 'alerted_at' => null, 'detail' => $result->failure]);
                $failed[$result->name] = $result->failure;
            } elseif (! is_string($row->alerted_at ?? null)) {
                $failed[$result->name] = $result->failure;
            } elseif ($now->getTimestamp() - self::at($row->alerted_at)->getTimestamp() >= self::REMINDER_SECONDS) {
                $reminders[$result->name] = [$result->failure, intdiv($now->getTimestamp() - self::at($row->failing_since)->getTimestamp(), 60)];
            }
        }

        // A check that no longer exists (an account removed or no longer watched) is forgotten.
        $names = array_map(fn (HealthResult $r): string => $r->name, $results);
        $this->db->table('health_checks')->whereNotIn('name', $names === [] ? [''] : $names)->delete();

        if ($failed !== [] && $this->send(implode("\n", array_map(fn (string $name, ?string $why): string => "Trading X health: {$name} failed: {$why}", array_keys($failed), $failed)))) {
            $this->db->table('health_checks')->whereIn('name', array_keys($failed))->update(['alerted_at' => $now]);
        }

        if ($reminders !== []) {
            $since = max(array_column($reminders, 1));
            $text = "Trading X health: still failing after {$since} minutes: ".implode('; ', array_map(fn (string $name, array $r): string => "{$name} ({$r[0]})", array_keys($reminders), $reminders));

            if ($this->send($text)) {
                $this->db->table('health_checks')->whereIn('name', array_keys($reminders))->update(['alerted_at' => $now]);
            }
        }

        if ($recovered !== [] && $this->send('Trading X health: recovered: '.implode('; ', array_map(fn (string $name, int $minutes): string => "{$name} after {$minutes} minutes", array_keys($recovered), $recovered)))) {
            $this->db->table('health_checks')->whereIn('name', array_keys($recovered))->delete();
        }
    }

    private static function at(mixed $value): CarbonImmutable
    {
        return is_string($value) ? CarbonImmutable::parse($value, 'UTC') : throw new UnexpectedValueException('A health check row without its time.');
    }

    private function send(string $text): bool
    {
        try {
            $this->telegram->send($text);

            return true;
        } catch (RuntimeException $failure) {
            $this->log->error("A health alert could not be sent ({$failure->getMessage()}): {$text}");

            return false;
        }
    }
}
