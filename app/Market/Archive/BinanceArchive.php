<?php

declare(strict_types=1);

namespace App\Market\Archive;

use App\Market\Binance\EpochTime;
use App\Market\Binance\ExchangeNumber;
use App\Market\Binance\RequestFailed;
use App\Market\Data\Candle;
use App\Market\Data\FundingRate;
use App\Market\Data\MarketType;
use App\Market\Data\OpenInterest;
use App\Market\Data\PriceMinute;
use App\Market\Data\TradeFlow;
use App\Market\Data\TradeFlowBuilder;
use App\Support\Number\Decimal;
use App\Support\Timeframe;
use Carbon\CarbonImmutable;
use Generator;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Factory as HttpFactory;
use UnexpectedValueException;
use ZipArchive;

/**
 * Binance's public data archives (data.binance.vision). Every archive is checked against its published SHA-256
 * before it is read; verified archives are kept in a local cache and checked again on reuse
 * (docs/market-data.md, section 3).
 */
final readonly class BinanceArchive
{
    public function __construct(
        private HttpFactory $http,
        private string $baseUrl,
        private string $cacheDirectory,
        private int $timeoutSeconds,
    ) {}

    /** @return list<Candle> 1-minute candles, oldest first */
    public function klines(MarketType $market, string $venueSymbol, ArchivePeriod $period): array
    {
        $root = $market === MarketType::Spot ? 'spot' : 'futures/um';
        $path = "{$root}/{$period->folder()}/klines/{$venueSymbol}/1m/{$venueSymbol}-1m-{$period->label()}.zip";

        return array_map(
            static fn (array $fields): Candle => Candle::fromStrings(
                EpochTime::parse($fields[0]),
                Timeframe::OneMinute,
                ExchangeNumber::plain($fields[1]),
                ExchangeNumber::plain($fields[2]),
                ExchangeNumber::plain($fields[3]),
                ExchangeNumber::plain($fields[4]),
                ExchangeNumber::plain($fields[5]),
                ExchangeNumber::plain($fields[7]),
                self::integer($fields[8]),
                ExchangeNumber::plain($fields[9]),
            ),
            self::rows($this->csv($path), 12),
        );
    }

    /** @return list<FundingRate> the funding rates of the month that contains $month, oldest first */
    public function fundingRates(string $venueSymbol, CarbonImmutable $month): array
    {
        $label = $month->utc()->format('Y-m');
        $path = "futures/um/monthly/fundingRate/{$venueSymbol}/{$venueSymbol}-fundingRate-{$label}.zip";

        return array_map(
            // calc_time carries a few milliseconds of jitter; funding happens on the second.
            static fn (array $fields): FundingRate => new FundingRate(
                EpochTime::parse($fields[0])->startOfSecond(),
                Decimal::of(ExchangeNumber::plain($fields[2])),
                self::integer($fields[1]),
                null,
            ),
            self::rows($this->csv($path), 3),
        );
    }

    /**
     * The day's aggregated trades of a USD-M perpetual, read as a stream and reduced to one row a minute (D-79). The
     * archive is deleted once read: a day of BTC is tens of megabytes and only the minutes are kept.
     *
     * @return list<TradeFlow> the minutes that had trades, oldest first
     */
    public function tradeFlow(string $venueSymbol, CarbonImmutable $day, Decimal $largeNotional): array
    {
        $local = $this->archive("futures/um/daily/aggTrades/{$venueSymbol}/{$venueSymbol}-aggTrades-{$day->utc()->format('Y-m-d')}.zip");
        $builder = new TradeFlowBuilder($largeNotional);

        try {
            foreach (self::lines($local, 7) as $fields) {
                // agg_trade_id, price, quantity, first_trade_id, last_trade_id, transact_time, is_buyer_maker
                $builder->add(self::integer($fields[5]), Decimal::of(ExchangeNumber::plain($fields[1])), Decimal::of(ExchangeNumber::plain($fields[2])), $fields[6] === 'true');
            }
        } finally {
            @unlink($local);
        }

        return $builder->finish();
    }

    /** @return list<OpenInterest> the day's open interest every 5 minutes, oldest first (the file is not in order) */
    public function openInterest(string $venueSymbol, CarbonImmutable $day): array
    {
        $local = $this->archive("futures/um/daily/metrics/{$venueSymbol}/{$venueSymbol}-metrics-{$day->utc()->format('Y-m-d')}.zip");
        $rows = [];

        foreach (self::lines($local, 4) as $fields) {
            // create_time is text, in UTC: 2026-11-01 00:05:00.
            $time = CarbonImmutable::createFromFormat('Y-m-d H:i:s', $fields[0], 'UTC') ?: throw new UnexpectedValueException("Binance metrics: '{$fields[0]}' is not a time.");
            $rows[$time->getTimestamp()] = new OpenInterest($time, Decimal::of(ExchangeNumber::plain($fields[2])), Decimal::of(ExchangeNumber::plain($fields[3])));
        }

        ksort($rows);

        return array_values($rows);
    }

    /** @return list<PriceMinute> the day's 1-minute mark price candles of a USD-M perpetual, oldest first */
    public function markPrices(string $venueSymbol, CarbonImmutable $day): array
    {
        return $this->priceMinutes('markPriceKlines', $venueSymbol, $day);
    }

    /** @return list<PriceMinute> the day's 1-minute premium index candles of a USD-M perpetual, oldest first */
    public function premiumIndex(string $venueSymbol, CarbonImmutable $day): array
    {
        return $this->priceMinutes('premiumIndexKlines', $venueSymbol, $day);
    }

    /** @return list<PriceMinute> */
    private function priceMinutes(string $kind, string $venueSymbol, CarbonImmutable $day): array
    {
        $local = $this->archive("futures/um/daily/{$kind}/{$venueSymbol}/1m/{$venueSymbol}-1m-{$day->utc()->format('Y-m-d')}.zip");
        $rows = [];

        foreach (self::lines($local, 5) as $fields) {
            $rows[] = new PriceMinute(
                EpochTime::parse($fields[0]),
                Decimal::of(ExchangeNumber::plain($fields[1])), Decimal::of(ExchangeNumber::plain($fields[2])),
                Decimal::of(ExchangeNumber::plain($fields[3])), Decimal::of(ExchangeNumber::plain($fields[4])),
            );
        }

        return $rows;
    }

    /** The verified archive's local path: downloaded, or the cached copy when its checksum still matches. */
    private function archive(string $path): string
    {
        $expected = $this->publishedChecksum($path);
        $local = $this->cacheDirectory.'/'.$path;

        if (! is_file($local) || hash_file('sha256', $local) !== $expected) {
            $this->download($path, $local, $expected);
        }

        return $local;
    }

    /**
     * The archived CSV's rows, read as a stream so a large file never sits in memory; a header line is skipped.
     *
     * @return Generator<int, list<string>>
     */
    private static function lines(string $local, int $columns): Generator
    {
        $zip = new ZipArchive;

        if ($zip->open($local) !== true || $zip->count() !== 1) {
            throw new UnexpectedValueException("{$local} is not a Binance archive with one file.");
        }

        $stream = $zip->getStreamIndex(0);

        if ($stream === false) {
            $zip->close();

            throw new UnexpectedValueException("{$local}: cannot read the archived file.");
        }

        try {
            $index = 0;

            while (($line = fgets($stream)) !== false) {
                $line = rtrim($line, "\r\n");

                if ($line === '' || ($index++ === 0 && preg_match('/^[a-z_]/', $line) === 1)) {
                    continue;
                }

                $fields = array_map(static fn (?string $field): string => (string) $field, str_getcsv($line, escape: ''));

                if (count($fields) < $columns) {
                    throw new UnexpectedValueException("Binance archive {$local}: a line has ".count($fields)." fields, expected {$columns}.");
                }

                yield $fields;
            }
        } finally {
            fclose($stream);
            $zip->close();
        }
    }

    private function csv(string $path): string
    {
        $expected = $this->publishedChecksum($path);
        $local = $this->cacheDirectory.'/'.$path;

        if (! is_file($local) || hash_file('sha256', $local) !== $expected) {
            $this->download($path, $local, $expected);
        }

        return self::unzip($local);
    }

    private function publishedChecksum(string $path): string
    {
        $text = $this->fetch($path.'.CHECKSUM');

        if (preg_match('/^([0-9a-f]{64})\s/', $text, $match) !== 1) {
            throw new UnexpectedValueException("Binance archive {$path}: unreadable CHECKSUM file.");
        }

        return $match[1];
    }

    private function download(string $path, string $local, string $expected): void
    {
        $bytes = $this->fetch($path);

        if (hash('sha256', $bytes) !== $expected) {
            throw new ChecksumMismatch("Binance archive {$path}: SHA-256 does not match the published checksum.");
        }

        if (! is_dir(dirname($local)) && ! mkdir(dirname($local), 0o775, true) && ! is_dir(dirname($local))) {
            throw new UnexpectedValueException("Cannot create the archive cache directory for {$path}.");
        }

        file_put_contents($local, $bytes);
    }

    private function fetch(string $path): string
    {
        $url = rtrim($this->baseUrl, '/').'/data/'.$path;

        try {
            $response = $this->http->timeout($this->timeoutSeconds)->get($url);
        } catch (ConnectionException $failure) {
            throw new RequestFailed("Binance archive {$path}: {$failure->getMessage()}", previous: $failure);
        }

        if ($response->status() === 404) {
            throw new ArchiveMissing("Binance archive {$path} is not published.");
        }

        if (! $response->successful()) {
            throw new RequestFailed("Binance archive {$path} failed with {$response->status()}.");
        }

        return $response->body();
    }

    private static function unzip(string $local): string
    {
        $zip = new ZipArchive;

        if ($zip->open($local) !== true || $zip->count() !== 1) {
            throw new UnexpectedValueException("{$local} is not a Binance archive with one file.");
        }

        $contents = $zip->getFromIndex(0);
        $zip->close();

        if ($contents === false) {
            throw new UnexpectedValueException("{$local}: cannot read the archived file.");
        }

        return $contents;
    }

    /** @return list<list<string>> CSV rows with at least $columns fields; a header line is skipped */
    private static function rows(string $csv, int $columns): array
    {
        $rows = [];

        foreach (preg_split('/\r?\n/', trim($csv)) ?: [] as $index => $line) {
            if ($index === 0 && preg_match('/^[a-z_]/', $line) === 1) {
                continue;
            }

            $fields = array_map(static fn (?string $field): string => (string) $field, str_getcsv($line, escape: ''));

            if (count($fields) < $columns) {
                throw new UnexpectedValueException("Binance archive line {$index} has ".count($fields)." fields, expected {$columns}.");
            }

            $rows[] = $fields;
        }

        return $rows;
    }

    private static function integer(string $value): int
    {
        if (preg_match('/^\d+$/', $value) !== 1) {
            throw new UnexpectedValueException("Binance archive: '{$value}' is not an integer.");
        }

        return (int) $value;
    }
}
