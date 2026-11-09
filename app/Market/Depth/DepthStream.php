<?php

declare(strict_types=1);

namespace App\Market\Depth;

use App\Market\Binance\BinanceEndpoints;
use App\Market\Binance\BinanceRequestFailed;
use App\Market\Binance\BinanceRestClient;
use App\Market\Binance\EpochTime;
use App\Market\Binance\Json;
use App\Market\Binance\Sleeper;
use App\Market\Data\CandleSource;
use App\Market\Data\OpenInterest;
use App\Market\Heartbeats;
use App\Market\Models\Instrument;
use App\Market\ScalpingRepository;
use App\Market\Stream\StreamFailed;
use App\Market\Stream\StreamTransport;
use App\Support\Clock\Clock;
use App\Support\Number\Decimal;
use Carbon\CarbonImmutable;
use JsonException;
use Psr\Log\LoggerInterface;
use UnexpectedValueException;

/**
 * Keeps a local order book of each perpetual it follows (D-91) from <symbol>@depth on /public and REST snapshots,
 * and when each 5 minutes ends, by the exchange's event time, writes the book's depth within 1, 2 and 5 % to
 * book_depth: the same rows the bookDepth archives give history, so strategies read depth the same way live.
 * A missed update starts the symbol again from a new snapshot.
 */
final class DepthStream
{
    private const int HEARTBEAT_SECONDS = 30;

    private const int MAX_BACKOFF_SECONDS = 60;

    private bool $stopRequested = false;

    /** @var array<string, OrderBook|null> by venue symbol; null until a snapshot fits the updates */
    private array $books = [];

    /** @var array<string, list<DepthUpdate>> updates waiting for a snapshot, by venue symbol */
    private array $waiting = [];

    /** @var array<string, int> the 5 minutes (start timestamp) each book is in, by venue symbol */
    private array $bucket = [];

    private int $resyncs = 0;

    public function __construct(
        private readonly StreamTransport $transport,
        private readonly BinanceEndpoints $endpoints,
        private readonly BinanceRestClient $rest,
        private readonly ScalpingRepository $repository,
        private readonly Heartbeats $heartbeats,
        private readonly Clock $clock,
        private readonly Sleeper $sleeper,
        private readonly LoggerInterface $logger,
    ) {}

    /** @param list<Instrument> $instruments perpetuals */
    public function run(array $instruments, ?int $maxSessions = null): void
    {
        $byVenue = [];

        foreach ($instruments as $instrument) {
            $byVenue[$instrument->venue_symbol] = $instrument;
        }

        $streams = implode('/', array_map(fn (string $venue): string => mb_strtolower($venue).'@depth@500ms', array_keys($byVenue)));
        $url = rtrim($this->endpoints->perpDepthStream, '/')."/stream?streams={$streams}";
        $backoff = 1;

        for ($session = 1; ! $this->stopRequested && ($maxSessions === null || $session <= $maxSessions); $session++) {
            // A new connection starts every book again: updates were missed in between.
            $this->books = $this->waiting = $this->bucket = [];
            $received = 0;

            try {
                $this->transport->connect($url);
                $lastBeat = null;

                while (! $this->stopRequested) {
                    if ($lastBeat === null || $lastBeat->diffInSeconds($this->clock->now()) >= self::HEARTBEAT_SECONDS) {
                        $this->beat($session);
                        $lastBeat = $this->clock->now();
                    }

                    $payload = $this->transport->receive();

                    if ($payload === null) {
                        break;
                    }

                    $received++;
                    $update = self::parse($payload);

                    if ($update !== null && isset($byVenue[$update->venueSymbol])) {
                        $this->handle($byVenue[$update->venueSymbol], $update);
                    }
                }
            } catch (StreamFailed $failure) {
                $this->logger->warning('market:depth: stream failed', ['error' => $failure->getMessage(), 'session' => $session]);
            } finally {
                $this->transport->close();
                $this->beat($session);
            }

            $backoff = $received > 0 ? 1 : min(self::MAX_BACKOFF_SECONDS, $backoff * 2);

            if (! $this->stopRequested && ($maxSessions === null || $session < $maxSessions)) {
                $this->sleeper->sleep($backoff);
            }
        }
    }

    public function stop(): void
    {
        $this->stopRequested = true;
    }

    private function handle(Instrument $instrument, DepthUpdate $update): void
    {
        $venue = $update->venueSymbol;
        $bucket = intdiv($update->time->getTimestamp(), OpenInterest::KNOWN_AFTER_SECONDS) * OpenInterest::KNOWN_AFTER_SECONDS;
        $book = $this->books[$venue] ?? null;

        // The 5 minutes the book was in are over: write the book as they ended, before this update.
        if ($book !== null && isset($this->bucket[$venue]) && $bucket > $this->bucket[$venue]) {
            $depth = $book->depth(CarbonImmutable::createFromTimestampUTC($this->bucket[$venue]));

            if ($depth !== null) {
                $this->repository->upsertDepth($instrument, CandleSource::Stream, [$depth]);
            }
        }

        $this->bucket[$venue] = $bucket;

        try {
            if ($book === null) {
                $this->waiting[$venue][] = $update;
                $this->books[$venue] = $this->fromSnapshot($venue);

                return;
            }

            $book->apply($update);
        } catch (OrderBookOutOfSync $outOfSync) {
            $this->resyncs++;
            $this->logger->warning("market:depth: {$outOfSync->getMessage()} Starting again from a new snapshot.");
            $this->books[$venue] = null;
            $this->waiting[$venue] = [$update];
            unset($this->bucket[$venue]);
        } catch (BinanceRequestFailed $failure) {
            $this->logger->error("market:depth: snapshot of {$venue} failed: {$failure->getMessage()}");
            $this->books[$venue] = null;
        }
    }

    /** A book from a REST snapshot with the waiting updates applied; throws OrderBookOutOfSync when it does not fit them. */
    private function fromSnapshot(string $venue): OrderBook
    {
        [$lastUpdateId, $bids, $asks] = $this->rest->depthSnapshot($venue);
        $book = OrderBook::fromSnapshot($lastUpdateId, $bids, $asks);

        foreach ($this->waiting[$venue] ?? [] as $update) {
            $book->apply($update);
        }

        $this->waiting[$venue] = [];

        return $book;
    }

    private function beat(int $session): void
    {
        $this->heartbeats->beat('market:depth', [
            'session' => $session,
            'books' => count(array_filter($this->books)),
            'resyncs' => $this->resyncs,
        ]);
    }

    private static function parse(string $payload): ?DepthUpdate
    {
        try {
            $message = json_decode($payload, true, flags: JSON_THROW_ON_ERROR);
        } catch (JsonException $invalid) {
            throw new UnexpectedValueException("Depth message is not JSON: {$invalid->getMessage()}", previous: $invalid);
        }

        $data = is_array($message) ? ($message['data'] ?? null) : null;

        if (! is_array($data) || ($data['e'] ?? null) !== 'depthUpdate') {
            return null;
        }

        $levels = fn (string $key): array => array_values(array_map(
            fn (mixed $level): array => [Decimal::of(Json::number(Json::array($level, 'depth level'), 0)), Decimal::of(Json::number(Json::array($level, 'depth level'), 1))],
            Json::arrayAt($data, $key),
        ));

        return new DepthUpdate(
            Json::string($data, 's'),
            EpochTime::parse((string) Json::int($data, 'E')),
            Json::int($data, 'U'),
            Json::int($data, 'u'),
            Json::int($data, 'pu'),
            $levels('b'),
            $levels('a'),
        );
    }
}
