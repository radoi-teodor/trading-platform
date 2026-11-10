<?php

declare(strict_types=1);

namespace App\Market\Backfill;

use App\Market\Archive\ArchiveMissing;
use App\Market\Archive\ArchivePeriod;
use App\Market\Archive\BinanceArchive;
use App\Market\Archive\ChecksumMismatch;
use App\Market\Binance\BinanceRequestFailed;
use App\Market\Binance\BinanceRestClient;
use App\Market\CandleRepository;
use App\Market\Data\BookDepth;
use App\Market\Data\CandleSource;
use App\Market\Data\FundingRate;
use App\Market\Data\InstrumentStatus;
use App\Market\Data\OpenInterest;
use App\Market\Data\Positioning;
use App\Market\Data\PriceMinute;
use App\Market\Data\TradeFlow;
use App\Market\Data\UpsertResult;
use App\Market\FundingRateRepository;
use App\Market\Instruments\TradingBreaks;
use App\Market\Models\Instrument;
use App\Market\ScalpingRepository;
use App\Support\Clock\Clock;
use App\Support\Number\Decimal;
use App\Support\Timeframe;
use Carbon\CarbonImmutable;
use Generator;
use InvalidArgumentException;
use UnexpectedValueException;

/**
 * Fills history in the order of docs/market-data.md, section 3: monthly archives for whole past months, daily
 * archives for past days, REST for what is not archived yet. A failure stops the run and is reported; what was
 * stored before it stays (writes are idempotent).
 */
final readonly class Backfill
{
    public function __construct(
        private BinanceArchive $archive,
        private BinanceRestClient $rest,
        private CandleRepository $candles,
        private FundingRateRepository $fundingRates,
        private Clock $clock,
        private ScalpingRepository $scalping,
        private TradingBreaks $breaks,
    ) {}

    /**
     * The data for scalping of a perpetual (D-79), day by day from the daily archives: the trade flow above
     * $largeNotional, open interest, and the mark price with the premium index. A day Binance has not published yet
     * is reported and skipped; the live stream and its REST repair fill recent days (D-81).
     */
    public function scalping(Instrument $perp, CarbonImmutable $from, CarbonImmutable $to, bool $flow, bool $openInterest, bool $marks, Decimal $largeNotional, bool $depth = false): BackfillReport
    {
        $steps = [];
        $within = fn (CarbonImmutable $t): bool => ! $t->lessThan($from) && ! $t->greaterThan($to);

        try {
            for ($day = $from->utc()->startOfDay(); ! $day->greaterThan($to) && $day->lessThan($this->clock->now()->startOfDay()); $day = $day->addDay()) {
                $label = $day->format('Y-m-d');
                $jobs = array_filter([
                    'flow' => $flow ? fn (): UpsertResult => $this->scalping->upsertFlow($perp, CandleSource::Archive, array_filter($this->archive->tradeFlow($perp->venue_symbol, $day, $largeNotional), fn (TradeFlow $f): bool => $within($f->openTime))) : null,
                    'open interest' => $openInterest ? fn (): UpsertResult => $this->scalping->upsertOpenInterest($perp, CandleSource::Archive, array_filter($this->archive->openInterest($perp->venue_symbol, $day), fn (OpenInterest $o): bool => $within($o->time))) : null,
                    'positioning' => $openInterest ? fn (): UpsertResult => $this->scalping->upsertPositioning($perp, CandleSource::Archive, array_filter($this->archive->positioning($perp->venue_symbol, $day), fn (Positioning $p): bool => $within($p->time))) : null,
                    'order book depth' => $depth ? fn (): UpsertResult => $this->scalping->upsertDepth($perp, CandleSource::Archive, array_filter($this->archive->bookDepth($perp->venue_symbol, $day), fn (BookDepth $d): bool => $within($d->time))) : null,
                    'mark price' => $marks ? fn (): UpsertResult => $this->scalping->upsertMarks($perp, CandleSource::Archive, array_filter($this->archive->markPrices($perp->venue_symbol, $day), fn (PriceMinute $m): bool => $within($m->openTime))) : null,
                    'premium index' => $marks ? fn (): UpsertResult => $this->scalping->upsertPremium($perp, CandleSource::Archive, array_filter($this->archive->premiumIndex($perp->venue_symbol, $day), fn (PriceMinute $m): bool => $within($m->openTime))) : null,
                ]);

                foreach ($jobs as $name => $job) {
                    try {
                        $steps[] = new BackfillStep("{$name} {$label}", $job());
                    } catch (ArchiveMissing) {
                        $steps[] = new BackfillStep("{$name} {$label}: not published yet", new UpsertResult);
                    }
                }
            }
        } catch (BinanceRequestFailed|ChecksumMismatch|InvalidArgumentException|UnexpectedValueException $failure) {
            return new BackfillReport($steps, $failure->getMessage());
        }

        return new BackfillReport($steps, null);
    }

    public function candles(Instrument $instrument, CarbonImmutable $from, CarbonImmutable $to): BackfillReport
    {
        $now = $this->clock->now();
        $today = $now->startOfDay();
        $to = $to->min(Timeframe::OneMinute->openTimeContaining($now)->subMinute());
        // The instrument's lifetime bounds the range (D-89): nothing before the listing, nothing from the delisting.
        $from = $instrument->listed_at === null ? $from : $from->max($instrument->listed_at);
        $to = $instrument->delisted_at === null ? $to : $to->min($instrument->delisted_at->subMinute());
        // REST knows only what trades: a delisted instrument is filled from archives alone, its gaps stay gaps.
        $rest = $instrument->status !== InstrumentStatus::Delisted;
        $cursor = $from->utc()->startOfDay();
        $steps = [];

        try {
            while ($cursor->lessThanOrEqualTo($to)) {
                $month = ArchivePeriod::monthly($cursor);

                if ($cursor->equalTo($month->start) && $month->end()->lessThanOrEqualTo($today) && $month->end()->subMinute()->lessThanOrEqualTo($to)) {
                    try {
                        $this->storeArchive($instrument, $month, $from, $to, $steps, $rest);
                        $cursor = $month->end();

                        continue;
                    } catch (ArchiveMissing) {
                        // Not published: take the same month day by day.
                    }
                }

                $day = ArchivePeriod::daily($cursor);

                if ($day->end()->lessThanOrEqualTo($today)) {
                    try {
                        $this->storeArchive($instrument, $day, $from, $to, $steps, $rest);
                        $cursor = $day->end();

                        continue;
                    } catch (ArchiveMissing) {
                        // Not published yet: REST from here on, for an instrument REST still knows.
                    }
                }

                if (! $rest) {
                    break;
                }

                $steps[] = $this->storeRest($instrument, $cursor->max($from), $to);

                break;
            }
        } catch (BinanceRequestFailed|ChecksumMismatch|InvalidArgumentException|UnexpectedValueException $failure) {
            return new BackfillReport($steps, $failure->getMessage());
        }

        // A symbol delisted and listed again: what its days show as a break is set to the minute by the new candles.
        $this->breaks->find($instrument);

        return new BackfillReport($steps, null);
    }

    public function funding(Instrument $perp, CarbonImmutable $from, CarbonImmutable $to): BackfillReport
    {
        $currentMonth = $this->clock->now()->startOfMonth();
        // The lifetime bounds funding too (D-89); a delisted perpetual has archives only.
        $from = $perp->listed_at === null ? $from : $from->max($perp->listed_at);
        $to = $perp->delisted_at === null ? $to : $to->min($perp->delisted_at->subMinute());
        $cursor = $from->utc()->startOfMonth();
        $steps = [];

        try {
            while ($cursor->lessThanOrEqualTo($to)) {
                if ($cursor->lessThan($currentMonth)) {
                    $rates = self::fundingWithin($this->archive->fundingRates($perp->venue_symbol, $cursor), $from, $to);
                    $steps[] = new BackfillStep("funding monthly {$cursor->format('Y-m')}", new UpsertResult(inserted: $this->fundingRates->upsert($perp, CandleSource::Archive, $rates)));
                    $cursor = $cursor->addMonth();

                    continue;
                }

                if ($perp->status === InstrumentStatus::Delisted) {
                    break;
                }

                $rates = iterator_to_array($this->rest->fundingRates($perp->venue_symbol, $cursor->max($from), $to), false);
                $steps[] = new BackfillStep("funding rest {$cursor->format('Y-m')}", new UpsertResult(inserted: $this->fundingRates->upsert($perp, CandleSource::Rest, $rates)));

                break;
            }
        } catch (BinanceRequestFailed|ChecksumMismatch|ArchiveMissing|InvalidArgumentException|UnexpectedValueException $failure) {
            return new BackfillReport($steps, $failure->getMessage());
        }

        return new BackfillReport($steps, null);
    }

    /**
     * Stores one archive, then fills through REST any minute of the asked range that the archive does not have:
     * published archives can be incomplete (Binance's USD-M archives for October 2026 have no 2026-10-28).
     * Steps are appended as they complete, so a failure still reports what was stored before it.
     *
     * @param  list<BackfillStep>  $steps
     */
    private function storeArchive(Instrument $instrument, ArchivePeriod $period, CarbonImmutable $from, CarbonImmutable $to, array &$steps, bool $rest = true): void
    {
        $archived = $this->archive->klines($instrument->market, $instrument->venue_symbol, $period);
        $openTimes = [];
        // Streamed into the upsert, which writes in batches: a month of candles never sits in memory at once.
        $candles = (static function () use ($archived, $from, $to, &$openTimes): Generator {
            foreach ($archived as $candle) {
                if ($candle->openTime->betweenIncluded($from, $to)) {
                    $openTimes[] = $candle->openTime->getTimestamp();

                    yield $candle;
                }
            }
        })();
        $steps[] = new BackfillStep("{$period->folder()} {$period->label()}", $this->candles->upsert($instrument, CandleSource::Archive, $candles));

        if (! $rest) {
            return;
        }

        $windowStart = $period->start->max(Timeframe::OneMinute->openTimeContaining($from));
        $windowEnd = $period->end()->subMinute()->min($to);

        foreach (self::missingRanges($openTimes, $windowStart, $windowEnd) as [$gapFrom, $gapTo]) {
            $steps[] = new BackfillStep(
                "rest gap {$gapFrom->format('Y-m-d H:i')}",
                $this->candles->upsert($instrument, CandleSource::Rest, $this->rest->klines($instrument->market, $instrument->venue_symbol, $gapFrom, $gapTo)),
            );
        }
    }

    /**
     * @param  list<int>  $openTimes  Unix seconds of the stored candles, oldest first (integers: a month of Carbon
     *                                objects would undo the streaming)
     * @return list<array{0: CarbonImmutable, 1: CarbonImmutable}>
     */
    private static function missingRanges(array $openTimes, CarbonImmutable $from, CarbonImmutable $to): array
    {
        $ranges = [];
        $expected = $from->getTimestamp();
        $openTimes[] = $to->addMinute()->getTimestamp();

        foreach ($openTimes as $time) {
            if ($time > $expected) {
                $ranges[] = [CarbonImmutable::createFromTimestampUTC($expected), CarbonImmutable::createFromTimestampUTC($time - 60)];
            }

            $expected = max($time + 60, $expected);
        }

        return $ranges;
    }

    private function storeRest(Instrument $instrument, CarbonImmutable $from, CarbonImmutable $to): BackfillStep
    {
        $candles = $this->rest->klines($instrument->market, $instrument->venue_symbol, $from, $to);

        return new BackfillStep("rest {$from->format('Y-m-d')}", $this->candles->upsert($instrument, CandleSource::Rest, $candles));
    }

    /**
     * @param  list<FundingRate>  $rates
     * @return list<FundingRate>
     */
    private static function fundingWithin(array $rates, CarbonImmutable $from, CarbonImmutable $to): array
    {
        return array_values(array_filter($rates, static fn (FundingRate $rate): bool => $rate->fundingTime->betweenIncluded($from, $to)));
    }
}
