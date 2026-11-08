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
use App\Market\Data\Candle;
use App\Market\Data\CandleSource;
use App\Market\Data\FundingRate;
use App\Market\Data\UpsertResult;
use App\Market\FundingRateRepository;
use App\Market\Models\Instrument;
use App\Support\Clock\Clock;
use App\Support\Timeframe;
use Carbon\CarbonImmutable;
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
    ) {}

    public function candles(Instrument $instrument, CarbonImmutable $from, CarbonImmutable $to): BackfillReport
    {
        $now = $this->clock->now();
        $today = $now->startOfDay();
        $to = $to->min(Timeframe::OneMinute->openTimeContaining($now)->subMinute());
        $cursor = $from->utc()->startOfDay();
        $steps = [];

        try {
            while ($cursor->lessThanOrEqualTo($to)) {
                $month = ArchivePeriod::monthly($cursor);

                if ($cursor->equalTo($month->start) && $month->end()->lessThanOrEqualTo($today) && $month->end()->subMinute()->lessThanOrEqualTo($to)) {
                    try {
                        $steps[] = $this->storeArchive($instrument, $month, $from, $to);
                        $cursor = $month->end();

                        continue;
                    } catch (ArchiveMissing) {
                        // Not published: take the same month day by day.
                    }
                }

                $day = ArchivePeriod::daily($cursor);

                if ($day->end()->lessThanOrEqualTo($today)) {
                    try {
                        $steps[] = $this->storeArchive($instrument, $day, $from, $to);
                        $cursor = $day->end();

                        continue;
                    } catch (ArchiveMissing) {
                        // Not published yet: REST from here on.
                    }
                }

                $steps[] = $this->storeRest($instrument, $cursor->max($from), $to);

                break;
            }
        } catch (BinanceRequestFailed|ChecksumMismatch|UnexpectedValueException $failure) {
            return new BackfillReport($steps, $failure->getMessage());
        }

        return new BackfillReport($steps, null);
    }

    public function funding(Instrument $perp, CarbonImmutable $from, CarbonImmutable $to): BackfillReport
    {
        $currentMonth = $this->clock->now()->startOfMonth();
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

                $rates = iterator_to_array($this->rest->fundingRates($perp->venue_symbol, $cursor->max($from), $to), false);
                $steps[] = new BackfillStep("funding rest {$cursor->format('Y-m')}", new UpsertResult(inserted: $this->fundingRates->upsert($perp, CandleSource::Rest, $rates)));

                break;
            }
        } catch (BinanceRequestFailed|ChecksumMismatch|ArchiveMissing|UnexpectedValueException $failure) {
            return new BackfillReport($steps, $failure->getMessage());
        }

        return new BackfillReport($steps, null);
    }

    private function storeArchive(Instrument $instrument, ArchivePeriod $period, CarbonImmutable $from, CarbonImmutable $to): BackfillStep
    {
        $candles = array_filter(
            $this->archive->klines($instrument->market, $instrument->venue_symbol, $period),
            static fn (Candle $candle): bool => $candle->openTime->betweenIncluded($from, $to),
        );

        return new BackfillStep("{$period->folder()} {$period->label()}", $this->candles->upsert($instrument, CandleSource::Archive, $candles));
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
