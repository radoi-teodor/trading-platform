<?php

declare(strict_types=1);

namespace App\Market\Stream;

use App\Market\Binance\BinanceEndpoints;
use App\Market\Binance\BinanceRequestFailed;
use App\Market\Binance\BinanceRestClient;
use App\Market\Binance\Sleeper;
use App\Market\CandleRepository;
use App\Market\Data\CandleSource;
use App\Market\Data\MarketType;
use App\Market\Heartbeats;
use App\Market\Models\Instrument;
use App\Support\Clock\Clock;
use App\Support\Number\Decimal;
use App\Support\Timeframe;
use Carbon\CarbonImmutable;
use Psr\Log\LoggerInterface;
use UnexpectedValueException;

/**
 * Keeps 1-minute candles current from a combined WebSocket stream (docs/market-data.md, section 4): stores
 * closed candles, repairs the gap through REST on every connect, reconnects with backoff, writes a heartbeat.
 */
final class CandleStream
{
    private const int HEARTBEAT_SECONDS = 30;

    private const int MAX_SESSION_SECONDS = 23 * 3600 + 30 * 60;

    private const int MAX_BACKOFF_SECONDS = 60;

    private bool $stopRequested = false;

    private ?string $gapRepair = null;

    private ?CarbonImmutable $lastCandleAt = null;

    private int $messagesThisSession = 0;

    public function __construct(
        private readonly StreamTransport $transport,
        private readonly BinanceEndpoints $endpoints,
        private readonly BinanceRestClient $rest,
        private readonly CandleRepository $candles,
        private readonly Heartbeats $heartbeats,
        private readonly Clock $clock,
        private readonly Sleeper $sleeper,
        private readonly LoggerInterface $logger,
        private readonly ScalpingCollector $scalping,
    ) {}

    /**
     * @param  list<Instrument>  $instruments
     * @param  array<string, Instrument>  $scalping  perpetuals whose data for scalping is collected too, by venue symbol (D-81)
     * @param  array<string, Decimal>  $thresholds  their large-trade notional, by venue symbol
     */
    public function run(MarketType $market, array $instruments, ?int $maxSessions = null, array $scalping = [], array $thresholds = []): void
    {
        $this->scalping->follow($scalping, $thresholds);
        $byVenue = [];

        foreach ($instruments as $instrument) {
            $byVenue[$instrument->venue_symbol] = $instrument;
        }

        $url = $this->url($market, array_keys($byVenue));
        $process = "market:stream {$market->value}";
        $backoff = 1;

        for ($session = 1; ! $this->stopRequested && ($maxSessions === null || $session <= $maxSessions); $session++) {
            $this->messagesThisSession = 0;

            try {
                $this->transport->connect($url);
                $this->repairGaps($instruments);
                $this->scalping->repair();
                $endedOnPurpose = $this->listen($byVenue, $process, $session);
            } catch (StreamFailed $failure) {
                $this->logger->warning("{$process}: stream failed", ['error' => $failure->getMessage(), 'session' => $session]);
                $endedOnPurpose = false;
            } finally {
                $this->transport->close();
            }

            // A session that delivered data starts the backoff over; one that failed or ended empty doubles it.
            if ($this->messagesThisSession > 0) {
                $backoff = 1;
            }

            if (! $endedOnPurpose && ! $this->stopRequested) {
                $this->sleeper->sleep($backoff);
                $backoff = min(self::MAX_BACKOFF_SECONDS, $backoff * 2);
            }
        }
    }

    public function stop(): void
    {
        $this->stopRequested = true;
    }

    /**
     * @param  array<string, Instrument>  $byVenue
     * @return bool true when the session ended on purpose (stop or planned reconnect)
     */
    private function listen(array $byVenue, string $process, int $session): bool
    {
        $connectedAt = $this->clock->now();
        $lastBeat = null;

        while (! $this->stopRequested) {
            if ($lastBeat === null || $lastBeat->diffInSeconds($this->clock->now()) >= self::HEARTBEAT_SECONDS) {
                $this->beat($process, $session);
                $lastBeat = $this->clock->now();
            }

            if ($connectedAt->diffInSeconds($this->clock->now()) >= self::MAX_SESSION_SECONDS) {
                return true;
            }

            $this->scalping->tick();
            $payload = $this->transport->receive();

            if ($payload === null) {
                $this->beat($process, $session);

                return false;
            }

            $this->messagesThisSession++;
            $this->store($payload, $byVenue, $process);
        }

        return true;
    }

    /** @param array<string, Instrument> $byVenue */
    private function store(string $payload, array $byVenue, string $process): void
    {
        $decoded = json_decode($payload, true);
        $data = is_array($decoded) && is_array($decoded['data'] ?? null) ? $decoded['data'] : null;

        if ($data !== null && in_array($data['e'] ?? null, ['aggTrade', 'markPriceUpdate'], true)) {
            $this->scalping->message($data);

            return;
        }

        try {
            $message = KlineMessage::parse($payload);
        } catch (UnexpectedValueException $unreadable) {
            $this->logger->error("{$process}: unreadable stream message", ['error' => $unreadable->getMessage()]);

            return;
        }

        if ($message === null || ! $message->closed || ! isset($byVenue[$message->venueSymbol])) {
            return;
        }

        $this->candles->upsert($byVenue[$message->venueSymbol], CandleSource::Stream, [$message->candle]);
        $this->lastCandleAt = $message->candle->openTime;
    }

    /** @param list<Instrument> $instruments */
    private function repairGaps(array $instruments): void
    {
        $lastClosed = Timeframe::OneMinute->openTimeContaining($this->clock->now())->subMinute();
        $failures = [];

        foreach ($instruments as $instrument) {
            $latest = $this->candles->latestOpenTime($instrument, Timeframe::OneMinute);

            if ($latest === null || $latest->greaterThanOrEqualTo($lastClosed)) {
                continue;
            }

            try {
                $this->candles->upsert($instrument, CandleSource::Rest, $this->rest->klines($instrument->market, $instrument->venue_symbol, $latest->addMinute(), $lastClosed));
            } catch (BinanceRequestFailed $failure) {
                $failures[] = "{$instrument->symbol}: {$failure->getMessage()}";
                $this->logger->error('Gap repair failed; the gap stays until an archive or a later repair fills it', ['instrument' => $instrument->symbol, 'error' => $failure->getMessage()]);
            }
        }

        $this->gapRepair = $failures === [] ? 'ok' : 'failed: '.implode('; ', $failures);
    }

    private function beat(string $process, int $session): void
    {
        $this->heartbeats->beat($process, [
            'session' => $session,
            'gap_repair' => $this->gapRepair,
            'last_candle_open' => $this->lastCandleAt?->toIso8601String(),
        ]);
    }

    /** @param list<string> $venueSymbols */
    private function url(MarketType $market, array $venueSymbols): string
    {
        $streams = implode('/', [...array_map(static fn (string $symbol): string => mb_strtolower($symbol).'@kline_1m', $venueSymbols), ...$this->scalping->streams()]);

        return $this->endpoints->stream($market).'/stream?streams='.$streams;
    }
}
