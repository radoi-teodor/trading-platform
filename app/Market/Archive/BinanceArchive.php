<?php

declare(strict_types=1);

namespace App\Market\Archive;

use App\Market\Binance\EpochTime;
use App\Market\Binance\ExchangeNumber;
use App\Market\Binance\RequestFailed;
use App\Market\Data\Candle;
use App\Market\Data\FundingRate;
use App\Market\Data\MarketType;
use App\Support\Number\Decimal;
use App\Support\Timeframe;
use Carbon\CarbonImmutable;
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
