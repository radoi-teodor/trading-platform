<?php

declare(strict_types=1);

namespace App\Market\Stream;

use App\Market\Data\MarketType;
use App\Market\LiquidationRepository;
use App\Market\Models\Instrument;
use App\Support\Clock\Clock;
use App\Support\Number\Decimal;
use Carbon\CarbonImmutable;
use Psr\Log\LoggerInterface;

/**
 * Sums the liquidations of every perpetual the platform knows, minute by minute, from !forceOrder@arr (D-91), writes
 * each minute once it is over, and records the minutes it heard whole, so a minute without liquidations is told apart
 * from one nobody listened to.
 */
final class LiquidationCollector
{
    /** @var array<string, Instrument> every known perpetual, by venue symbol */
    private array $instruments = [];

    /** @var array<string, array<int, array{long: int, longNotional: Decimal, short: int, shortNotional: Decimal}>> by venue symbol, then minute */
    private array $minutes = [];

    /** The first minute heard whole since the stream connected. */
    private ?int $since = null;

    /** The minute up to which coverage is written (exclusive). */
    private ?int $coveredTo = null;

    public function __construct(private readonly LiquidationRepository $repository, private readonly Clock $clock, private readonly LoggerInterface $logger) {}

    /** Called on every connect: coverage starts again from the next whole minute. */
    public function start(): void
    {
        $this->instruments = [];

        foreach (Instrument::query()->where('exchange', 'binance')->where('market', MarketType::Perp)->get() as $instrument) {
            $this->instruments[$instrument->venue_symbol] = $instrument;
        }

        $this->since = intdiv($this->clock->now()->getTimestamp() + 59, 60) * 60;
        $this->coveredTo = $this->since;
        $this->minutes = [];
    }

    /** @param array<array-key, mixed> $data a forceOrder event */
    public function message(array $data): void
    {
        $order = is_array($data['o'] ?? null) ? $data['o'] : [];
        $venue = is_string($order['s'] ?? null) ? $order['s'] : '';

        if (! isset($this->instruments[$venue])) {
            return;
        }

        if (! is_int($order['T'] ?? null) || ! is_string($order['ap'] ?? null) || ! is_string($order['z'] ?? null) || ! in_array($order['S'] ?? null, ['BUY', 'SELL'], true)) {
            $this->logger->error("A liquidation of {$venue} could not be read.");

            return;
        }

        $minute = intdiv($order['T'], 60_000) * 60;
        $notional = Decimal::of($order['ap'])->times(Decimal::of($order['z']));
        $sum = $this->minutes[$venue][$minute] ?? ['long' => 0, 'longNotional' => Decimal::zero(), 'short' => 0, 'shortNotional' => Decimal::zero()];

        // The exchange sells to close a long position and buys to close a short one.
        if ($order['S'] === 'SELL') {
            $sum['long']++;
            $sum['longNotional'] = $sum['longNotional']->plus($notional);
        } else {
            $sum['short']++;
            $sum['shortNotional'] = $sum['shortNotional']->plus($notional);
        }

        $this->minutes[$venue][$minute] = $sum;
    }

    /** Writes the minutes that are over, and the coverage of those heard whole. */
    public function tick(): void
    {
        if ($this->since === null || $this->coveredTo === null) {
            return;
        }

        $current = intdiv($this->clock->now()->getTimestamp(), 60) * 60;

        foreach ($this->minutes as $venue => $minutes) {
            foreach ($minutes as $minute => $sum) {
                if ($minute < $current) {
                    $this->repository->store($this->instruments[$venue], CarbonImmutable::createFromTimestampUTC($minute), $sum['long'], (string) $sum['longNotional'], $sum['short'], (string) $sum['shortNotional']);
                    unset($this->minutes[$venue][$minute]);
                }
            }
        }

        $covered = [];

        for ($minute = $this->coveredTo; $minute < $current; $minute += 60) {
            $covered[] = CarbonImmutable::createFromTimestampUTC($minute);
        }

        $this->repository->cover($covered);
        $this->coveredTo = max($this->coveredTo, $current);
    }
}
