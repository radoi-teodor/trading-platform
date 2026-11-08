<?php

declare(strict_types=1);

namespace App\Market\Binance;

use App\Market\Data\Candle;
use App\Market\Data\FundingRate;
use App\Market\Data\InstrumentDefinition;
use App\Market\Data\InstrumentStatus;
use App\Market\Data\MarketType;
use App\Support\Asset;
use App\Support\Clock\Clock;
use App\Support\Number\Decimal;
use App\Support\Number\Price;
use App\Support\Symbol;
use App\Support\Timeframe;
use Carbon\CarbonImmutable;
use Generator;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Factory as HttpFactory;
use Illuminate\Http\Client\Response;

/** Binance public REST market data, spot and USD-M (D-30, docs/market-data.md). No keys. */
final readonly class BinanceRestClient
{
    private const float WEIGHT_THRESHOLD = 0.8;

    private const int FUNDING_PAGE = 1000;

    public function __construct(
        private HttpFactory $http,
        private BinanceEndpoints $endpoints,
        private Clock $clock,
        private Sleeper $sleeper,
    ) {}

    /** @return list<InstrumentDefinition> */
    public function exchangeInfo(MarketType $market): array
    {
        $path = $market === MarketType::Spot ? '/api/v3/exchangeInfo' : '/fapi/v1/exchangeInfo';
        $definitions = [];

        foreach (Json::arrayAt($this->get($market, $path), 'symbols') as $symbol) {
            $definition = $market === MarketType::Spot ? $this->spotDefinition($symbol) : $this->perpDefinition($symbol);

            if ($definition !== null) {
                $definitions[] = $definition;
            }
        }

        return $definitions;
    }

    /**
     * Closed candles with $from <= open time <= $to, oldest first.
     *
     * @return Generator<int, Candle>
     */
    public function klines(MarketType $market, string $venueSymbol, CarbonImmutable $from, CarbonImmutable $to, Timeframe $timeframe = Timeframe::OneMinute, ?int $pageSize = null): Generator
    {
        $limit = $pageSize ?? ($market === MarketType::Spot ? 1000 : 1500);
        $path = $market === MarketType::Spot ? '/api/v3/klines' : '/fapi/v1/klines';
        $now = $this->clock->now();
        $start = $from;

        while ($start->lessThanOrEqualTo($to)) {
            $rows = Json::array($this->get($market, $path, [
                'symbol' => $venueSymbol,
                'interval' => $timeframe->value,
                'startTime' => EpochTime::milliseconds($start),
                'endTime' => EpochTime::milliseconds($timeframe->closeTime($to)),
                'limit' => $limit,
            ]), 'klines');

            $last = null;

            foreach ($rows as $row) {
                $candle = self::candle(Json::array($row, 'kline'), $timeframe);

                if ($candle->openTime->greaterThan($to) || $candle->closesAt()->greaterThanOrEqualTo($now)) {
                    return;
                }

                yield $candle;
                $last = $candle;
            }

            if ($last === null || count($rows) < $limit) {
                return;
            }

            $start = $timeframe->nextOpen($last->openTime);
        }
    }

    /** @return Generator<int, FundingRate> */
    public function fundingRates(string $venueSymbol, CarbonImmutable $from, CarbonImmutable $to): Generator
    {
        $start = $from;
        $previous = null;

        while ($start->lessThanOrEqualTo($to)) {
            $rows = Json::array($this->get(MarketType::Perp, '/fapi/v1/fundingRate', [
                'symbol' => $venueSymbol,
                'startTime' => EpochTime::milliseconds($start),
                'endTime' => EpochTime::milliseconds($to),
                'limit' => self::FUNDING_PAGE,
            ]), 'fundingRate');

            $times = array_map(fn (mixed $row): CarbonImmutable => self::fundingTime($row), $rows);

            foreach (array_values($rows) as $index => $row) {
                $time = $times[$index];
                $reference = $previous ?? ($times[$index + 1] ?? null);
                $markPrice = Json::string($row, 'markPrice');

                yield new FundingRate(
                    $time,
                    Decimal::of(Json::string($row, 'fundingRate')),
                    $reference === null ? 8 : (int) round(abs($time->diffInHours($reference))),
                    $markPrice === '' ? null : Price::of($markPrice),
                );

                $previous = $time;
            }

            if (count($rows) < self::FUNDING_PAGE || $previous === null) {
                return;
            }

            $start = $previous->addSecond();
        }
    }

    /** @param array<string, int|string> $query */
    private function get(MarketType $market, string $path, array $query = []): mixed
    {
        try {
            $response = $this->http->timeout($this->endpoints->timeoutSeconds)
                ->acceptJson()
                ->get($this->endpoints->rest($market).$path, $query);
        } catch (ConnectionException $failure) {
            throw new RequestFailed("Binance {$path}: {$failure->getMessage()}", previous: $failure);
        }

        $this->guard($path, $response);
        $this->pace($market, $response);

        return $response->json();
    }

    private function guard(string $path, Response $response): void
    {
        $status = $response->status();

        match (true) {
            $status === 451 => throw new LocationRestricted("Binance {$path} refused this location (451). Configure the binance.vision hosts or run from an allowed location."),
            $status === 418, $status === 429 => throw new RateLimited("Binance {$path} rate limit reached ({$status}). Stopped; retry later."),
            ! $response->successful() => throw new RequestFailed("Binance {$path} failed with {$status}: ".mb_substr($response->body(), 0, 200)),
            default => null,
        };
    }

    /** Waits for the next minute when the used request weight passes 80 % of the limit. */
    private function pace(MarketType $market, Response $response): void
    {
        $used = (int) $response->header('X-MBX-USED-WEIGHT-1M');
        $limit = $market === MarketType::Spot ? 6000 : 2400;

        if ($used >= $limit * self::WEIGHT_THRESHOLD) {
            $this->sleeper->sleep(60 - $this->clock->now()->second + 1);
        }
    }

    private function spotDefinition(mixed $symbol): InstrumentDefinition
    {
        $filters = self::filters($symbol);

        return self::definition(
            MarketType::Spot,
            Symbol::parse(Json::string($symbol, 'baseAsset').'/'.Json::string($symbol, 'quoteAsset')),
            Json::string($symbol, 'status') === 'TRADING' ? InstrumentStatus::Trading : InstrumentStatus::Halted,
            $filters,
            self::optionalDecimal($filters['NOTIONAL'] ?? $filters['MIN_NOTIONAL'] ?? null, 'minNotional'),
        );
    }

    private function perpDefinition(mixed $symbol): ?InstrumentDefinition
    {
        if (Json::string($symbol, 'contractType') !== 'PERPETUAL') {
            return null;
        }

        $filters = self::filters($symbol);
        $status = match (Json::string($symbol, 'status')) {
            'TRADING' => InstrumentStatus::Trading,
            'SETTLING', 'CLOSE', 'DELIVERING', 'DELIVERED' => InstrumentStatus::Delisted,
            default => InstrumentStatus::Halted,
        };
        $settle = Asset::of(Json::string($symbol, 'marginAsset'));

        return self::definition(
            MarketType::Perp,
            Symbol::parse(Json::string($symbol, 'baseAsset').'/'.Json::string($symbol, 'quoteAsset').':'.$settle),
            $status,
            $filters,
            self::optionalDecimal($filters['MIN_NOTIONAL'] ?? null, 'notional'),
        );
    }

    /** @param array<string, array<array-key, mixed>> $filters */
    private static function definition(MarketType $market, Symbol $symbol, InstrumentStatus $status, array $filters, ?Decimal $minNotional): InstrumentDefinition
    {
        $lot = $filters['LOT_SIZE'] ?? [];

        return new InstrumentDefinition(
            $market,
            $symbol,
            $status,
            Decimal::of(Json::string($filters['PRICE_FILTER'] ?? [], 'tickSize')),
            Decimal::of(Json::string($lot, 'stepSize')),
            Decimal::of(Json::string($lot, 'minQty')),
            self::optionalDecimal($lot, 'maxQty'),
            $minNotional,
            $filters,
        );
    }

    /** @return array<string, array<array-key, mixed>> filters keyed by filter type */
    private static function filters(mixed $symbol): array
    {
        $filters = [];

        foreach (Json::arrayAt($symbol, 'filters') as $filter) {
            $filters[Json::string($filter, 'filterType')] = Json::array($filter, 'filter');
        }

        return $filters;
    }

    /** @param array<array-key, mixed>|null $filter */
    private static function optionalDecimal(?array $filter, string $field): ?Decimal
    {
        $value = $filter[$field] ?? null;

        return is_string($value) ? Decimal::of($value) : null;
    }

    /** @param array<array-key, mixed> $row */
    private static function candle(array $row, Timeframe $timeframe): Candle
    {
        return Candle::fromStrings(
            EpochTime::parse(Json::int($row, 0)),
            $timeframe,
            Json::string($row, 1),
            Json::string($row, 2),
            Json::string($row, 3),
            Json::string($row, 4),
            Json::string($row, 5),
            Json::string($row, 7),
            Json::int($row, 8),
            Json::string($row, 9),
        );
    }

    private static function fundingTime(mixed $row): CarbonImmutable
    {
        // Funding times can carry a few milliseconds of jitter; funding happens on the second.
        return EpochTime::parse(Json::int($row, 'fundingTime'))->startOfSecond();
    }
}
