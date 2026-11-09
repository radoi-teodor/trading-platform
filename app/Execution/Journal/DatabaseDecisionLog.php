<?php

declare(strict_types=1);

namespace App\Execution\Journal;

use App\Execution\Contracts\DecisionLog;
use App\Execution\Data\DecisionRecord;
use App\Execution\Data\EngineRecord;
use App\Execution\Data\IntentRecord;
use App\Execution\Models\JournalEntry;
use App\Support\Clock\Clock;
use Carbon\CarbonImmutable;
use Closure;

/**
 * An account's journal in `engine_journal` (docs/engine.md, sections 7 and 9). While the engine replays the minutes
 * it already handled, every record it makes must equal the next journaled one, or the replay diverges (D-68); after
 * the replay it writes. Decisions without intents are counted, not written. Notices are written and never replayed.
 */
final class DatabaseDecisionLog implements DecisionLog
{
    public const int FORMAT = 1;

    /** @var list<array{kind: string, payload: array<string, mixed>}> */
    private array $expected = [];

    private int $position = 0;

    public int $quietDecisions = 0;

    /** @param (Closure(EngineRecord): void)|null $onEvent called for every new event once replay is over */
    public function __construct(private readonly int $account, private readonly Clock $clock, private readonly ?Closure $onEvent = null)
    {
        foreach (JournalEntry::query()->where('account_id', $account)->where('kind', '!=', 'notice')->orderBy('id')->get() as $entry) {
            $this->expected[] = ['kind' => $entry->kind, 'payload' => $entry->payload];
        }
    }

    public function replaying(): bool
    {
        return $this->position < count($this->expected);
    }

    public function decision(DecisionRecord $record): void
    {
        if ($record->intents === []) {
            $this->quietDecisions++;

            return;
        }

        $this->record('decision', $record->time->getTimestamp(), [
            'id' => $record->id,
            'time' => $record->time->toIso8601ZuluString(),
            'strategy' => $record->strategy,
            'symbol' => (string) $record->symbol,
            'close' => $record->close,
            'intents' => array_map(fn (IntentRecord $i): array => [
                'type' => $i->type, 'reason' => $i->reason, 'approved' => $i->approved, 'rejection' => $i->rejection,
                'quantity' => $i->quantity, 'quote_quantity' => $i->quoteQuantity, 'adjustments' => $i->adjustments, 'orders' => $i->orders,
            ], $record->intents),
        ]);
    }

    public function event(EngineRecord $record): void
    {
        $replayed = $this->replaying();
        $this->record('event', $record->time->getTimestamp(), ['time' => $record->time->toIso8601ZuluString(), 'kind' => $record->kind, 'detail' => $record->detail]);

        if (! $replayed && $this->onEvent !== null) {
            ($this->onEvent)($record);
        }
    }

    /**
     * What happened outside the minutes, from the wall clock (stale data, a divergent replay): written but never
     * replayed, since a replay has no wall clock.
     */
    public function notice(EngineRecord $record): void
    {
        JournalEntry::query()->create([
            'account_id' => $this->account,
            'event_at' => $record->time,
            'kind' => 'notice',
            'format' => self::FORMAT,
            'payload' => ['time' => $record->time->toIso8601ZuluString(), 'kind' => $record->kind, 'detail' => $record->detail],
            'created_at' => $this->clock->now(),
        ]);

        if ($this->onEvent !== null) {
            ($this->onEvent)($record);
        }
    }

    /** @param array<string, mixed> $payload */
    private function record(string $kind, int $time, array $payload): void
    {
        if ($this->replaying()) {
            $expected = $this->expected[$this->position];
            $this->position++;

            if ($expected['kind'] !== $kind || $expected['payload'] !== $payload) {
                throw new ReplayDivergence(sprintf(
                    'Replay diverged at journal record %d: the journal has %s %s, the replay made %s %s.',
                    $this->position, $expected['kind'], json_encode($expected['payload']), $kind, json_encode($payload),
                ));
            }

            return;
        }

        JournalEntry::query()->create([
            'account_id' => $this->account,
            'event_at' => CarbonImmutable::createFromTimestampUTC($time),
            'kind' => $kind,
            'format' => self::FORMAT,
            'payload' => $payload,
            'created_at' => $this->clock->now(),
        ]);
        $this->expected[] = ['kind' => $kind, 'payload' => $payload];
        $this->position++;
    }
}
