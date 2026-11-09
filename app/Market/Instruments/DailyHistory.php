<?php

declare(strict_types=1);

namespace App\Market\Instruments;

use App\Market\Archive\ArchiveCatalog;
use App\Market\Archive\ArchiveMissing;
use App\Market\Archive\ArchivePeriod;
use App\Market\Archive\BinanceArchive;
use App\Market\Archive\ChecksumMismatch;
use App\Market\Binance\BinanceRequestFailed;
use App\Market\Binance\BinanceRestClient;
use App\Market\DailyStats;
use App\Market\Data\Candle;
use App\Market\Data\InstrumentStatus;
use App\Market\Data\MarketType;
use App\Market\Models\Instrument;
use App\Support\Clock\Clock;
use App\Support\Timeframe;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Builder;
use UnexpectedValueException;

/**
 * Fills daily_stats for every candidate of the research universe (D-89, D-90): USDT-quoted crypto perpetuals. A
 * trading one comes from REST in one request; a delisted one from its monthly daily archives, which also give its
 * lifetime: the first day with volume is its listing, the day after the last one its delisting.
 */
final readonly class DailyHistory
{
    public function __construct(
        private BinanceRestClient $rest,
        private BinanceArchive $archive,
        private ArchiveCatalog $catalog,
        private DailyStats $stats,
        private Clock $clock,
    ) {}

    /** @return Builder<Instrument> USDT-quoted crypto perpetuals: a contract on a coin, or of unknown kind when only the archives know it */
    public static function candidates(): Builder
    {
        return Instrument::query()->where('exchange', 'binance')->where('market', MarketType::Perp)->where('quote', 'USDT')
            ->where(fn (Builder $q) => $q->where('underlying_type', 'COIN')->orWhereNull('underlying_type'))
            ->orderBy('symbol');
    }

    /** @return string|null what went wrong for this instrument, or null when its days are stored */
    public function fill(Instrument $instrument, CarbonImmutable $from): ?string
    {
        try {
            return $instrument->status === InstrumentStatus::Delisted ? $this->fromArchives($instrument, $from) : $this->fromRest($instrument, $from);
        } catch (BinanceRequestFailed|ChecksumMismatch|ArchiveMissing|UnexpectedValueException $failure) {
            return $failure->getMessage();
        }
    }

    private function fromRest(Instrument $instrument, CarbonImmutable $from): null
    {
        $start = $instrument->listed_at === null ? $from : $from->max(Timeframe::OneDay->openTimeContaining($instrument->listed_at));
        $yesterday = Timeframe::OneDay->openTimeContaining($this->clock->now())->subDay();

        if ($start->greaterThan($yesterday)) {
            return null;
        }

        $this->stats->upsert($instrument, $this->rest->klines(MarketType::Perp, $instrument->venue_symbol, $start, $yesterday, Timeframe::OneDay));

        return null;
    }

    private function fromArchives(Instrument $instrument, CarbonImmutable $from): ?string
    {
        $months = $this->catalog->months($instrument->venue_symbol);

        if ($months === []) {
            return "{$instrument->symbol}: no daily archive.";
        }

        $wanted = array_values(array_filter($months, fn (string $m): bool => $m >= $from->format('Y-m')));
        $first = $months[0];
        $last = $months[count($months) - 1];
        $read = array_values(array_unique([...($instrument->listed_at === null ? [$first] : []), ...$wanted, ...($instrument->delisted_at === null ? [$last] : [])]));
        sort($read);
        $days = [];

        foreach ($read as $month) {
            foreach ($this->archive->klines(MarketType::Perp, $instrument->venue_symbol, ArchivePeriod::monthly(CarbonImmutable::parse("{$month}-01", 'UTC')), Timeframe::OneDay) as $day) {
                $days[$day->openTime->getTimestamp()] = $day;
            }
        }

        ksort($days);
        $traded = array_values(array_filter($days, fn (Candle $day): bool => $day->volume->isPositive()));

        if ($traded === []) {
            return "{$instrument->symbol}: its archives hold no day with volume.";
        }

        // Its lifetime, from the archives (D-89); a date already known is kept.
        if ($instrument->listed_at === null && in_array($first, $read, true)) {
            $instrument->listed_at = $traded[0]->openTime;
        }

        if ($instrument->delisted_at === null && in_array($last, $read, true)) {
            $instrument->delisted_at = $traded[count($traded) - 1]->openTime->addDay();
        }

        $instrument->save();
        $listed = $instrument->listed_at;
        $delisted = $instrument->delisted_at;

        $this->stats->upsert($instrument, array_filter($days, fn (Candle $day): bool => $day->openTime->greaterThanOrEqualTo($from)
            && ($listed === null || $day->openTime->greaterThanOrEqualTo(Timeframe::OneDay->openTimeContaining($listed)))
            && ($delisted === null || $day->openTime->lessThan($delisted))));

        return null;
    }
}
