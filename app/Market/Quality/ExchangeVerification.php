<?php

declare(strict_types=1);

namespace App\Market\Quality;

use App\Market\Archive\ArchivePeriod;
use App\Market\Archive\BinanceArchive;
use App\Market\Binance\BinanceRestClient;
use App\Market\CandleRepository;
use App\Market\Data\Candle;
use App\Market\Models\Instrument;
use App\Support\Timeframe;
use Carbon\CarbonImmutable;
use Random\Engine\Xoshiro256StarStar;
use Random\Randomizer;

/**
 * Compares a seeded random sample of stored candles with a fresh read from the exchange, field by field
 * (docs/market-data.md, section 5).
 */
final readonly class ExchangeVerification
{
    private const int REST_WINDOW_MINUTES = 1000;

    public function __construct(
        private CandleRepository $candles,
        private BinanceRestClient $rest,
        private BinanceArchive $archive,
    ) {}

    public function verify(Instrument $instrument, CarbonImmutable $from, CarbonImmutable $to, int $sample, VerificationSource $against, int $seed = 2026): VerificationReport
    {
        // Only the timestamps of the whole window are read; candles are loaded for the sample alone, so a window of
        // years fits in memory.
        $times = $this->candles->openTimestamps($instrument, Timeframe::OneMinute, $from, $to);

        if ($times === []) {
            return new VerificationReport(0, [], []);
        }

        $picked = $sample >= count($times)
            ? $times
            : array_map(static fn (int $index): int => $times[$index], (new Randomizer(new Xoshiro256StarStar($seed)))->pickArrayKeys($times, max(1, $sample)));
        sort($picked);
        $stored = $this->candles->at($instrument, Timeframe::OneMinute, $picked);

        $reference = match ($against) {
            VerificationSource::Rest => $this->fromRest($instrument, $picked, $to),
            VerificationSource::DailyArchive => $this->fromDailyArchives($instrument, $picked),
        };

        $mismatches = [];

        foreach ($picked as $time) {
            $mismatch = self::compare($stored[$time], $reference[$time] ?? null);

            if ($mismatch !== null) {
                $mismatches[] = $mismatch;
            }
        }

        return new VerificationReport(
            count($picked),
            array_map(static fn (int $time): string => CarbonImmutable::createFromTimestampUTC($time)->toIso8601String(), $picked),
            $mismatches,
        );
    }

    /**
     * @param  list<int>  $times
     * @return array<int, Candle>
     */
    private function fromRest(Instrument $instrument, array $times, CarbonImmutable $to): array
    {
        $reference = [];
        $coveredUntil = null;

        foreach ($times as $time) {
            if ($coveredUntil !== null && $time <= $coveredUntil) {
                continue;
            }

            $start = CarbonImmutable::createFromTimestampUTC($time);
            $end = $start->addMinutes(self::REST_WINDOW_MINUTES - 1)->min($to);

            foreach ($this->rest->klines($instrument->market, $instrument->venue_symbol, $start, $end) as $candle) {
                $reference[$candle->openTime->getTimestamp()] = $candle;
            }

            $coveredUntil = $end->getTimestamp();
        }

        return $reference;
    }

    /**
     * @param  list<int>  $times
     * @return array<int, Candle>
     */
    private function fromDailyArchives(Instrument $instrument, array $times): array
    {
        $reference = [];
        $days = array_unique(array_map(static fn (int $time): string => CarbonImmutable::createFromTimestampUTC($time)->format('Y-m-d'), $times));

        $wanted = array_flip($times);

        foreach ($days as $day) {
            // Only the sampled minutes are kept; a day's archive is read and dropped.
            foreach ($this->archive->klines($instrument->market, $instrument->venue_symbol, ArchivePeriod::daily(CarbonImmutable::parse($day, 'UTC'))) as $candle) {
                if (isset($wanted[$candle->openTime->getTimestamp()])) {
                    $reference[$candle->openTime->getTimestamp()] = $candle;
                }
            }
        }

        return $reference;
    }

    private static function compare(Candle $stored, ?Candle $reference): ?string
    {
        $time = $stored->openTime->toIso8601String();

        if ($reference === null) {
            return "{$time}: stored but not at the exchange";
        }

        $differences = [];
        $fields = [
            'open' => [$stored->open->value, $reference->open->value],
            'high' => [$stored->high->value, $reference->high->value],
            'low' => [$stored->low->value, $reference->low->value],
            'close' => [$stored->close->value, $reference->close->value],
            'volume' => [$stored->volume, $reference->volume],
            'quote volume' => [$stored->quoteVolume, $reference->quoteVolume],
            'taker buy volume' => [$stored->takerBuyVolume, $reference->takerBuyVolume],
        ];

        foreach ($fields as $name => [$mine, $theirs]) {
            if (! $mine->equals($theirs)) {
                $differences[] = "{$name} {$mine} vs {$theirs}";
            }
        }

        if ($stored->tradeCount !== $reference->tradeCount) {
            $differences[] = "trades {$stored->tradeCount} vs {$reference->tradeCount}";
        }

        return $differences === [] ? null : "{$time}: ".implode(', ', $differences);
    }
}
