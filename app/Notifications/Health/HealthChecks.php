<?php

declare(strict_types=1);

namespace App\Notifications\Health;

use App\Execution\Models\Account;
use App\Execution\Models\AccountSnapshot;
use App\Market\Data\MarketType;
use App\Market\Models\Instrument;
use App\Support\Clock\Clock;
use App\Support\Symbol;
use Carbon\CarbonImmutable;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Database\ConnectionInterface;

/**
 * Whether what must run on the operator's machine is running (D-78): the market streams and their candles, each
 * monitored engine, the notification and simulation queues, and the disk.
 */
final readonly class HealthChecks
{
    public const int HEARTBEAT_SECONDS = 120;

    public const int CANDLE_SECONDS = 180;

    public const int ENGINE_SECONDS = 180;

    public const int NOTIFICATION_SECONDS = 120;

    public const int SIMULATION_SECONDS = 600;

    public const int MIN_FREE_BYTES = 5_000_000_000;

    public const int MIN_FREE_PERCENT = 10;

    public function __construct(private ConnectionInterface $db, private Clock $clock, private Repository $config, private DiskUsage $disk) {}

    /** @return list<HealthResult> */
    public function run(): array
    {
        $now = $this->clock->now();

        return [...$this->streams($now), ...$this->engines($now), $this->notifications($now), $this->simulations($now), $this->disk()];
    }

    /** @return list<HealthResult> */
    private function streams(CarbonImmutable $now): array
    {
        $results = [];
        $universe = $this->config->get('market.universe');

        foreach (is_array($universe) ? $universe : [] as $market => $symbols) {
            if (! is_array($symbols) || $symbols === [] || ! is_string($market)) {
                continue;
            }

            $beat = $this->db->table('heartbeats')->where('process', "market:stream {$market}")->value('beat_at');
            $results[] = new HealthResult("stream {$market}", is_string($beat)
                ? self::older(CarbonImmutable::parse($beat, 'UTC'), $now, self::HEARTBEAT_SECONDS, fn (int $m): string => "no heartbeat for {$m} minutes")
                : 'it has never run');

            foreach ($symbols as $text) {
                $results[] = $this->candles(Symbol::parse(is_string($text) ? $text : ''), $now);
            }
        }

        return $results;
    }

    private function candles(Symbol $symbol, CarbonImmutable $now): HealthResult
    {
        $instrument = Instrument::query()->where('market', $symbol->isPerpetual() ? MarketType::Perp : MarketType::Spot)->where('symbol', (string) $symbol)->first();
        $open = $instrument === null ? null : $this->db->table('candles')->where('instrument_id', $instrument->id)->where('timeframe', '1m')->max('open_time');

        if (! is_string($open)) {
            return new HealthResult("candles {$symbol}", 'no candle stored');
        }

        $closed = CarbonImmutable::parse($open, 'UTC')->addMinute();

        return new HealthResult("candles {$symbol}", self::older($closed, $now, self::CANDLE_SECONDS, fn (int $m): string => "the last closed minute is {$closed->format('Y-m-d H:i')} UTC, {$m} minutes ago"));
    }

    /** @return list<HealthResult> an engine is watched from its first publish, unless the operator turned it off */
    private function engines(CarbonImmutable $now): array
    {
        $results = [];

        foreach (Account::query()->where('monitored', true)->orderBy('name')->get() as $account) {
            $snapshot = AccountSnapshot::query()->find($account->id);

            if ($snapshot !== null) {
                $results[] = new HealthResult("engine {$account->name}", self::older($snapshot->updated_at, $now, self::ENGINE_SECONDS, fn (int $m): string => "the last publish was {$m} minutes ago"));
            }
        }

        return $results;
    }

    private function notifications(CarbonImmutable $now): HealthResult
    {
        $oldest = $this->db->table('jobs')->where('queue', 'notifications')->min('available_at');
        $failed = $this->db->table('failed_jobs')->where('queue', 'notifications')->where('failed_at', '>', $now->subHour())->count();

        return new HealthResult('notifications queue', match (true) {
            is_numeric($oldest) && $now->getTimestamp() - (int) $oldest > self::NOTIFICATION_SECONDS => 'a job has waited '.intdiv($now->getTimestamp() - (int) $oldest, 60).' minutes; is the notifications worker running?',
            $failed > 0 => "{$failed} message(s) failed in the last hour; see php artisan queue:failed",
            default => null,
        });
    }

    private function simulations(CarbonImmutable $now): HealthResult
    {
        $oldest = $this->db->table('simulation_jobs')->where('status', 'queued')->min('created_at');
        $running = $this->db->table('simulation_jobs')->where('status', 'running')->exists();

        if (! is_string($oldest) || $running) {
            return new HealthResult('simulation queue');
        }

        return new HealthResult('simulation queue', self::older(CarbonImmutable::parse($oldest, 'UTC'), $now, self::SIMULATION_SECONDS, fn (int $m): string => "a run has waited {$m} minutes with none running; is the simulation worker running?"));
    }

    private function disk(): HealthResult
    {
        [$free, $total] = [$this->disk->free(), $this->disk->total()];
        $percent = $total === 0 ? 0 : intdiv($free * 100, $total);
        $low = $free < self::MIN_FREE_BYTES || $percent < self::MIN_FREE_PERCENT;

        return new HealthResult('disk', $low ? intdiv($free, 1_000_000_000).' GB free of '.intdiv($total, 1_000_000_000)." GB ({$percent} %)" : null);
    }

    /** @param callable(int): string $why */
    private static function older(CarbonImmutable $at, CarbonImmutable $now, int $limit, callable $why): ?string
    {
        $seconds = $now->getTimestamp() - $at->getTimestamp();

        return $seconds > $limit ? $why(intdiv($seconds, 60)) : null;
    }
}
