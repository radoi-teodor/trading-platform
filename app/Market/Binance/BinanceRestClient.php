<?php

declare(strict_types=1);

namespace App\Market\Binance;

use App\Market\Data\Candle;
use App\Market\Data\FundingRate;
use App\Market\Data\InstrumentDefinition;
use App\Market\Data\InstrumentStatus;
use App\Market\Data\MarketType;
use App\Market\Data\OpenInterest;
use App\Market\Data\Positioning;
use App\Market\Data\PriceMinute;
use App\Market\Data\TradeFlow;
use App\Market\Data\TradeFlowBuilder;
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

    /** Perpetuals carry a delivery date of 2100-12-25 (4133404800000 ms); from 2100 on it means none. */
    private const int NO_DELIVERY = 4102444800000;

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
        // Spot permission sets are two thirds of the 17.8 MB answer and nothing reads them.
        $query = $market === MarketType::Spot ? ['showPermissionSets' => 'false'] : [];
        $definitions = [];

        foreach (Json::arrayAt($this->get($market, $path, $query), 'symbols') as $symbol) {
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
                    Decimal::of(Json::number($row, 'fundingRate')),
                    $reference === null ? 8 : (int) round(abs($time->diffInHours($reference))),
                    $markPrice === '' ? null : Price::of(ExchangeNumber::plain($markPrice)),
                );

                $previous = $time;
            }

            if (count($rows) < self::FUNDING_PAGE || $previous === null) {
                return;
            }

            $start = $previous->addSecond();
        }
    }

    /**
     * A perpetual's closed 1-minute mark price or premium index candles from $from to $to (D-81).
     *
     * @param  'markPriceKlines'|'premiumIndexKlines'  $kind
     * @return list<PriceMinute>
     */
    public function priceMinutes(string $kind, string $venueSymbol, CarbonImmutable $from, CarbonImmutable $to): array
    {
        $now = $this->clock->now();
        $minutes = [];

        for ($start = $from; $start <= $to; $start = $start->addMinutes(1500)) {
            $rows = Json::array($this->get(MarketType::Perp, "/fapi/v1/{$kind}", [
                'symbol' => $venueSymbol, 'interval' => '1m', 'limit' => 1500,
                'startTime' => EpochTime::milliseconds($start), 'endTime' => EpochTime::milliseconds($to->addSeconds(59)),
            ]), $kind);

            foreach ($rows as $row) {
                $open = EpochTime::parse((string) Json::int($row, 0));

                if ($open->addMinute() <= $now && $open <= $to) {
                    $minutes[$open->getTimestamp()] = new PriceMinute(
                        $open, Decimal::of(ExchangeNumber::plain(Json::string($row, 1))), Decimal::of(ExchangeNumber::plain(Json::string($row, 2))),
                        Decimal::of(ExchangeNumber::plain(Json::string($row, 3))), Decimal::of(ExchangeNumber::plain(Json::string($row, 4))),
                    );
                }
            }
        }

        ksort($minutes);

        return array_values($minutes);
    }

    /** @return list<OpenInterest> a perpetual's open interest every 5 minutes from $from to $to, the last 30 days only (D-81) */
    public function openInterestHistory(string $venueSymbol, CarbonImmutable $from, CarbonImmutable $to): array
    {
        $rows = Json::array($this->get(MarketType::Perp, '/futures/data/openInterestHist', [
            'symbol' => $venueSymbol, 'period' => '5m', 'limit' => 500,
            'startTime' => EpochTime::milliseconds($from), 'endTime' => EpochTime::milliseconds($to),
        ]), 'openInterestHist');
        $values = [];

        foreach ($rows as $row) {
            $time = EpochTime::parse((string) Json::int($row, 'timestamp'));
            $values[$time->getTimestamp()] = new OpenInterest($time, Decimal::of(Json::number($row, 'sumOpenInterest')), Decimal::of(Json::number($row, 'sumOpenInterestValue')));
        }

        ksort($values);

        return array_values($values);
    }

    /**
     * A snapshot of a perpetual's order book, the start of a local copy (D-91): its last update id, then bids and asks
     * as price and quantity. 1,000 levels a side, weight 20.
     *
     * @return array{int, list<array{Decimal, Decimal}>, list<array{Decimal, Decimal}>}
     */
    public function depthSnapshot(string $venueSymbol): array
    {
        $book = $this->get(MarketType::Perp, '/fapi/v1/depth', ['symbol' => $venueSymbol, 'limit' => 1000]);
        $side = fn (string $key): array => array_values(array_map(
            fn (mixed $level): array => [Decimal::of(Json::number(Json::array($level, 'depth level'), 0)), Decimal::of(Json::number(Json::array($level, 'depth level'), 1))],
            Json::arrayAt($book, $key),
        ));

        return [Json::int($book, 'lastUpdateId'), $side('bids'), $side('asks')];
    }

    /**
     * A perpetual's positioning every 5 minutes from $from to $to (D-91), joined by time from three endpoints that
     * match the metrics archive's columns: accounts, top traders' positions and taker volume. A ratio an endpoint
     * did not give for a time is null.
     *
     * @return list<Positioning>
     */
    public function positioning(string $venueSymbol, CarbonImmutable $from, CarbonImmutable $to): array
    {
        $read = function (string $path, string $field) use ($venueSymbol, $from, $to): array {
            $ratios = [];

            foreach (Json::array($this->get(MarketType::Perp, $path, [
                'symbol' => $venueSymbol, 'period' => '5m', 'limit' => 500,
                'startTime' => EpochTime::milliseconds($from), 'endTime' => EpochTime::milliseconds($to),
            ]), $path) as $row) {
                $ratios[EpochTime::parse((string) Json::int($row, 'timestamp'))->getTimestamp()] = Decimal::of(Json::number($row, $field));
            }

            return $ratios;
        };
        $accounts = $read('/futures/data/globalLongShortAccountRatio', 'longShortRatio');
        $top = $read('/futures/data/topLongShortPositionRatio', 'longShortRatio');
        $taker = $read('/futures/data/takerlongshortRatio', 'buySellRatio');
        $times = array_unique([...array_keys($accounts), ...array_keys($top), ...array_keys($taker)]);
        sort($times);

        return array_map(fn (int $time): Positioning => new Positioning(CarbonImmutable::createFromTimestampUTC($time), $accounts[$time] ?? null, $top[$time] ?? null, $taker[$time] ?? null), $times);
    }

    /**
     * A perpetual's aggregated trades from $from to $to, at most an hour, reduced to minutes as the archive is (D-79).
     *
     * @return list<TradeFlow>
     */
    public function tradeFlow(string $venueSymbol, CarbonImmutable $from, CarbonImmutable $to, Decimal $largeNotional): array
    {
        $builder = new TradeFlowBuilder($largeNotional);
        $end = EpochTime::milliseconds($to);
        $query = ['symbol' => $venueSymbol, 'startTime' => EpochTime::milliseconds($from), 'endTime' => $end, 'limit' => 1000];

        while (true) {
            $rows = Json::array($this->get(MarketType::Perp, '/fapi/v1/aggTrades', $query), 'aggTrades');
            $last = null;

            foreach ($rows as $row) {
                if (Json::int($row, 'T') > $end) {
                    return $builder->finish();
                }

                $builder->add(Json::int($row, 'T'), Decimal::of(Json::number($row, 'p')), Decimal::of(Json::number($row, 'q')), is_array($row) && ($row['m'] ?? null) === true);
                $last = Json::int($row, 'a');
            }

            if ($last === null || count($rows) < 1000) {
                return $builder->finish();
            }

            $query = ['symbol' => $venueSymbol, 'fromId' => $last + 1, 'limit' => 1000];
        }
    }

    /** @param array<string, int|string> $query */
    private function get(MarketType $market, string $path, array $query = []): mixed
    {
        try {
            // The timeout covers the whole transfer, so the answer travels compressed (D-30).
            $response = $this->http->timeout($this->endpoints->timeoutSeconds)
                ->withOptions(['decode_content' => 'gzip'])
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
        // Stocks and commodities are perpetuals too (TRADIFI_PERPETUAL); their underlyingType tells them apart (D-89).
        if (! in_array(Json::string($symbol, 'contractType'), ['PERPETUAL', 'TRADIFI_PERPETUAL'], true)) {
            return null;
        }

        $filters = self::filters($symbol);
        $status = match (Json::string($symbol, 'status')) {
            'TRADING' => InstrumentStatus::Trading,
            'SETTLING', 'CLOSE', 'DELIVERING', 'DELIVERED' => InstrumentStatus::Delisted,
            default => InstrumentStatus::Halted,
        };
        $settle = Asset::of(Json::string($symbol, 'marginAsset'));
        $definition = self::definition(
            MarketType::Perp,
            Symbol::parse(Json::string($symbol, 'baseAsset').'/'.Json::string($symbol, 'quoteAsset').':'.$settle),
            $status,
            $filters,
            self::optionalDecimal($filters['MIN_NOTIONAL'] ?? null, 'notional'),
        );
        // The lifetime (D-89): onboardDate is the listing; a perpetual's deliveryDate is 2100-12-25, meaning none,
        // and a settled contract's is when it stopped trading.
        $onboard = is_array($symbol) && is_int($symbol['onboardDate'] ?? null) ? $symbol['onboardDate'] : null;
        $delivery = is_array($symbol) && is_int($symbol['deliveryDate'] ?? null) ? $symbol['deliveryDate'] : null;
        $underlying = is_array($symbol) && is_string($symbol['underlyingType'] ?? null) ? $symbol['underlyingType'] : null;

        return new InstrumentDefinition(
            $definition->market, $definition->symbol, $definition->status, $definition->tickSize, $definition->stepSize,
            $definition->minQty, $definition->maxQty, $definition->minNotional, $definition->rawFilters,
            listedAt: $onboard === null ? null : EpochTime::parse($onboard),
            delistedAt: $delivery === null || $delivery >= self::NO_DELIVERY ? null : EpochTime::parse($delivery),
            underlyingType: $underlying,
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
            Decimal::of(Json::number($filters['PRICE_FILTER'] ?? [], 'tickSize')),
            Decimal::of(Json::number($lot, 'stepSize')),
            Decimal::of(Json::number($lot, 'minQty')),
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

        return is_string($value) ? Decimal::of(ExchangeNumber::plain($value)) : null;
    }

    /** @param array<array-key, mixed> $row */
    private static function candle(array $row, Timeframe $timeframe): Candle
    {
        return Candle::fromStrings(
            EpochTime::parse(Json::int($row, 0)),
            $timeframe,
            Json::number($row, 1),
            Json::number($row, 2),
            Json::number($row, 3),
            Json::number($row, 4),
            Json::number($row, 5),
            Json::number($row, 7),
            Json::int($row, 8),
            Json::number($row, 9),
        );
    }

    private static function fundingTime(mixed $row): CarbonImmutable
    {
        // Funding times can carry a few milliseconds of jitter; funding happens on the second.
        return EpochTime::parse(Json::int($row, 'fundingTime'))->startOfSecond();
    }
}
