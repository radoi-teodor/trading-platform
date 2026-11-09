<?php

declare(strict_types=1);

namespace App\Market\Stream;

use App\Market\Binance\BinanceRequestFailed;
use App\Market\Binance\BinanceRestClient;
use App\Market\Data\CandleSource;
use App\Market\Data\PriceMinute;
use App\Market\Data\TradeFlowBuilder;
use App\Market\Models\Instrument;
use App\Market\ScalpingRepository;
use App\Support\Clock\Clock;
use App\Support\Number\Decimal;
use Carbon\CarbonImmutable;
use Psr\Log\LoggerInterface;

/**
 * Keeps the data for scalping of the perpetuals it follows current (D-81): it reduces the stream's aggregated trades
 * and mark prices to minutes and writes each once it is over, reads the premium index and open interest over REST
 * every ten seconds, and on reconnect refills the last hour over REST. Rows are written once (D-84).
 */
final class ScalpingCollector
{
    public const int REST_SECONDS = 10;

    public const int REPAIR_MINUTES = 60;

    /** @var array<string, Instrument> by venue symbol */
    private array $instruments = [];

    /** @var array<string, Decimal> large-trade notional by venue symbol */
    private array $thresholds = [];

    /** @var array<string, TradeFlowBuilder> */
    private array $flows = [];

    /** @var array<string, array{minute: int, open: Decimal, high: Decimal, low: Decimal, close: Decimal}> */
    private array $marks = [];

    private ?int $lastRest = null;

    private const int POSITIONING_EVERY_PASSES = 3;

    private int $restPasses = 0;

    public function __construct(
        private readonly BinanceRestClient $rest,
        private readonly ScalpingRepository $repository,
        private readonly Clock $clock,
        private readonly LoggerInterface $logger,
    ) {}

    /**
     * @param  array<string, Instrument>  $instruments  by venue symbol
     * @param  array<string, Decimal>  $thresholds  large-trade notional by venue symbol
     */
    public function follow(array $instruments, array $thresholds): void
    {
        $this->instruments = $instruments;
        $this->thresholds = $thresholds;

        foreach ($instruments as $venue => $instrument) {
            $this->flows[$venue] = new TradeFlowBuilder($thresholds[$venue] ?? Decimal::zero());
        }
    }

    /** @return list<string> the WebSocket streams to subscribe to */
    public function streams(): array
    {
        $streams = [];

        foreach (array_keys($this->instruments) as $venue) {
            $streams[] = mb_strtolower($venue).'@aggTrade';
            $streams[] = mb_strtolower($venue).'@markPrice@1s';
        }

        return $streams;
    }

    /**
     * One message of the stream; false when it is not one of the collector's.
     *
     * @param  array<array-key, mixed>  $data
     */
    public function message(array $data): bool
    {
        $venue = is_string($data['s'] ?? null) ? $data['s'] : '';

        if (! isset($this->instruments[$venue])) {
            return false;
        }

        return match ($data['e'] ?? null) {
            'aggTrade' => $this->trade($venue, $data),
            'markPriceUpdate' => $this->mark($venue, $data),
            default => false,
        };
    }

    /** Writes the minutes that are over and, every ten seconds, the premium index and open interest read over REST. */
    public function tick(): void
    {
        $now = $this->clock->now();
        $minute = intdiv($now->getTimestamp(), 60) * 60;

        foreach ($this->instruments as $venue => $instrument) {
            $this->repository->upsertFlow($instrument, CandleSource::Stream, $this->flows[$venue]->takeBefore($minute));
            $mark = $this->marks[$venue] ?? null;

            if ($mark !== null && $mark['minute'] < $minute) {
                $this->repository->upsertMarks($instrument, CandleSource::Stream, [self::priceMinute($mark)]);
                unset($this->marks[$venue]);
            }
        }

        if ($this->lastRest !== null && $now->getTimestamp() - $this->lastRest < self::REST_SECONDS) {
            return;
        }

        $this->lastRest = $now->getTimestamp();
        $closed = CarbonImmutable::createFromTimestampUTC($minute)->subMinute();
        // Positioning changes every 5 minutes and costs three requests: every third pass, 30 seconds, is enough (D-91).
        $positioning = $this->restPasses++ % self::POSITIONING_EVERY_PASSES === 0;

        foreach ($this->instruments as $venue => $instrument) {
            $this->guarded($venue, function () use ($venue, $instrument, $closed, $positioning): void {
                $this->repository->upsertPremium($instrument, CandleSource::Rest, $this->rest->priceMinutes('premiumIndexKlines', $venue, $closed->subMinutes(2), $closed));
                $this->repository->upsertOpenInterest($instrument, CandleSource::Rest, $this->rest->openInterestHistory($venue, $closed->subMinutes(15), $closed->addMinute()));

                if ($positioning) {
                    $this->repository->upsertPositioning($instrument, CandleSource::Rest, $this->rest->positioning($venue, $closed->subMinutes(15), $closed->addMinute()));
                }
            });
        }
    }

    /** Refills the last hour over REST: mark price, premium index, open interest, and the trade flow of missing minutes. */
    public function repair(): void
    {
        $to = CarbonImmutable::createFromTimestampUTC(intdiv($this->clock->now()->getTimestamp(), 60) * 60)->subMinute();
        $from = $to->subMinutes(self::REPAIR_MINUTES - 1);

        foreach ($this->instruments as $venue => $instrument) {
            $this->guarded($venue, function () use ($venue, $instrument, $from, $to): void {
                $this->repository->upsertMarks($instrument, CandleSource::Rest, $this->rest->priceMinutes('markPriceKlines', $venue, $from, $to));
                $this->repository->upsertPremium($instrument, CandleSource::Rest, $this->rest->priceMinutes('premiumIndexKlines', $venue, $from, $to));
                $this->repository->upsertOpenInterest($instrument, CandleSource::Rest, $this->rest->openInterestHistory($venue, $from, $to->addMinute()));
                $this->repository->upsertPositioning($instrument, CandleSource::Rest, $this->rest->positioning($venue, $from, $to->addMinute()));
                $stored = [];

                foreach ($this->repository->flow($instrument, $from, $to) as $flow) {
                    $stored[$flow->openTime->getTimestamp()] = true;
                }

                foreach (self::missingRanges($from, $to, $stored) as [$start, $end]) {
                    $flows = $this->rest->tradeFlow($venue, $start, $end->addSeconds(59)->addMilliseconds(999), $this->thresholds[$venue] ?? Decimal::zero());
                    $this->repository->upsertFlow($instrument, CandleSource::Rest, $flows);
                }
            });
        }
    }

    /** @param array<array-key, mixed> $data */
    private function trade(string $venue, array $data): bool
    {
        if (! is_int($data['T'] ?? null) || ! is_string($data['p'] ?? null) || ! is_string($data['q'] ?? null)) {
            $this->logger->error("An aggregated trade of {$venue} could not be read.");

            return true;
        }

        $this->flows[$venue]->add($data['T'], Decimal::of($data['p']), Decimal::of($data['q']), ($data['m'] ?? null) === true);

        return true;
    }

    /** @param array<array-key, mixed> $data */
    private function mark(string $venue, array $data): bool
    {
        if (! is_int($data['E'] ?? null) || ! is_string($data['p'] ?? null)) {
            $this->logger->error("A mark price of {$venue} could not be read.");

            return true;
        }

        $minute = intdiv($data['E'], 60_000) * 60;
        $price = Decimal::of($data['p']);
        $current = $this->marks[$venue] ?? null;

        if ($current !== null && $current['minute'] !== $minute) {
            $this->repository->upsertMarks($this->instruments[$venue], CandleSource::Stream, [self::priceMinute($current)]);
            $current = null;
        }

        $this->marks[$venue] = $current === null
            ? ['minute' => $minute, 'open' => $price, 'high' => $price, 'low' => $price, 'close' => $price]
            : ['minute' => $minute, 'open' => $current['open'], 'high' => $price->isGreaterThan($current['high']) ? $price : $current['high'], 'low' => $price->isLessThan($current['low']) ? $price : $current['low'], 'close' => $price];

        return true;
    }

    /** @param callable(): void $work */
    private function guarded(string $venue, callable $work): void
    {
        try {
            $work();
        } catch (BinanceRequestFailed $failure) {
            // The engine waits for what is missing (D-84) and the next pass or repair fills it; the failure is logged.
            $this->logger->error("Scalping data of {$venue} over REST failed: {$failure->getMessage()}");
        }
    }

    /** @param array{minute: int, open: Decimal, high: Decimal, low: Decimal, close: Decimal} $mark */
    private static function priceMinute(array $mark): PriceMinute
    {
        return new PriceMinute(CarbonImmutable::createFromTimestampUTC($mark['minute']), $mark['open'], $mark['high'], $mark['low'], $mark['close']);
    }

    /**
     * @param  array<int, true>  $stored  minute open timestamps
     * @return list<array{CarbonImmutable, CarbonImmutable}> contiguous ranges of minutes without a row
     */
    private static function missingRanges(CarbonImmutable $from, CarbonImmutable $to, array $stored): array
    {
        $ranges = [];
        $start = null;

        for ($at = $from; $at <= $to; $at = $at->addMinute()) {
            if (isset($stored[$at->getTimestamp()])) {
                if ($start !== null) {
                    $ranges[] = [$start, $at->subMinute()];
                    $start = null;
                }
            } else {
                $start ??= $at;
            }
        }

        if ($start !== null) {
            $ranges[] = [$start, $to];
        }

        return $ranges;
    }
}
