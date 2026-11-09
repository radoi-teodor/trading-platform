<?php

declare(strict_types=1);

use App\Market\CandleRepository;
use App\Market\Data\Candle;
use App\Market\Data\CandleSource;
use App\Market\Data\FundingRate;
use App\Market\Data\MarketType;
use App\Market\Data\MinuteCandles;
use App\Market\Feed\CandleAggregator;
use App\Market\Feed\DatabaseCandleFeed;
use App\Market\FundingRateRepository;
use App\Market\Models\Instrument;
use App\Market\Models\InstrumentFilter;
use App\Market\Persistence\Rows;
use App\Support\Clock\Clock;
use App\Support\Clock\SimClock;
use App\Support\Number\Decimal;
use App\Support\Symbol;
use App\Support\Timeframe;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\Support\CandleFactory;

uses(RefreshDatabase::class);

function feedInstrument(string $symbol, MarketType $market = MarketType::Spot): Instrument
{
    $clock = new SimClock(CarbonImmutable::parse('2026-11-08 12:00:00', 'UTC'));
    app()->instance(Clock::class, $clock);

    return Instrument::register('binance', $market, Symbol::parse($symbol), $clock->now());
}

/** @param list<Candle> $candles */
function storeCandles(Instrument $instrument, array $candles): void
{
    app(CandleRepository::class)->upsert($instrument, CandleSource::Archive, $candles);
}

/**
 * @param  list<string>  $symbols
 * @return list<MinuteCandles>
 */
function readFeed(array $symbols, string $from, string $to, int $chunkMinutes = 1440): array
{
    $feed = new DatabaseCandleFeed(DB::connection(), 'binance', $chunkMinutes);

    return array_values([...$feed->minutes(
        array_map(fn (string $symbol): Symbol => Symbol::parse($symbol), $symbols),
        CarbonImmutable::parse($from, 'UTC'),
        CarbonImmutable::parse($to, 'UTC'),
    )]);
}

it('streams a day of 50 instruments without holding the day in memory (D-90)', function (): void {
    $symbols = [];

    foreach (range(1, 50) as $n) {
        $symbol = "C{$n}/USDT";
        $symbols[] = Symbol::parse($symbol);
        DB::table('candles')->insertUsing(
            ['instrument_id', 'timeframe', 'open_time', 'open', 'high', 'low', 'close', 'volume', 'quote_volume', 'trade_count', 'taker_buy_volume', 'source', 'received_at'],
            DB::query()->selectRaw("?, '1m', timestamptz '2026-10-01 00:00:00+00' + make_interval(mins => m), 100, 101, 99, 100, 1, 100, 1, 0.5, 1, now()", [feedInstrument($symbol)->id])
                ->fromRaw('generate_series(0, 1439) as m'),
        );
    }

    $feed = new DatabaseCandleFeed(DB::connection());
    memory_reset_peak_usage();
    $before = memory_get_usage();
    $minutes = 0;
    $candles = 0;

    foreach ($feed->minutes($symbols, CarbonImmutable::parse('2026-10-01', 'UTC'), CarbonImmutable::parse('2026-10-02', 'UTC')) as $minute) {
        $minutes++;
        $candles += count($minute->candles);
    }

    expect($minutes)->toBe(1440)
        ->and($candles)->toBe(72_000)
        ->and(memory_get_peak_usage() - $before)->toBeLessThan(64 * 1024 * 1024);
});

it('groups the candles of several instruments by minute, in time then symbol order', function (): void {
    $eth = feedInstrument('ETH/USDT');
    $btc = feedInstrument('BTC/USDT');
    storeCandles($eth, CandleFactory::walk('2026-10-01 00:00:00', 3, seed: 1));
    storeCandles($btc, CandleFactory::walk('2026-10-01 00:00:00', 3, seed: 2));

    $minutes = readFeed(['ETH/USDT', 'BTC/USDT'], '2026-10-01 00:00:00', '2026-10-01 00:03:00');

    expect($minutes)->toHaveCount(3)
        ->and(array_map(fn (MinuteCandles $m): string => $m->closedAt->format('H:i'), $minutes))->toBe(['00:01', '00:02', '00:03'])
        ->and(array_keys($minutes[0]->candles))->toBe(['BTC/USDT', 'ETH/USDT'])
        ->and($minutes[2]->candles['ETH/USDT']->openTime->format('H:i'))->toBe('00:02');
});

it('reads the half-open window [from, to) across chunk boundaries', function (): void {
    $btc = feedInstrument('BTC/USDT');
    storeCandles($btc, CandleFactory::walk('2026-10-01 23:50:00', 30));

    $minutes = readFeed(['BTC/USDT'], '2026-10-01 23:55:00', '2026-10-02 00:10:00', chunkMinutes: 7);

    expect($minutes)->toHaveCount(15)
        ->and($minutes[0]->candles['BTC/USDT']->openTime->format('Y-m-d H:i'))->toBe('2026-10-01 23:55')
        ->and($minutes[14]->candles['BTC/USDT']->openTime->format('Y-m-d H:i'))->toBe('2026-10-02 00:09');
});

it('reads only 1-minute candles of the asked market', function (): void {
    $spot = feedInstrument('BTC/USDT');
    $perp = feedInstrument('BTC/USDT:USDT', MarketType::Perp);
    storeCandles($spot, CandleFactory::walk('2026-10-01 00:00:00', 60));
    storeCandles($perp, CandleFactory::walk('2026-10-01 00:00:00', 60, seed: 4));
    $aggregator = new CandleAggregator(Timeframe::OneHour);
    [$hour] = array_values(array_filter(array_map(
        fn (Candle $minute): ?Candle => $aggregator->push($minute),
        CandleFactory::walk('2026-10-01 00:00:00', 60),
    )));
    storeCandles($spot, [$hour]);

    $minutes = readFeed(['BTC/USDT'], '2026-10-01 00:00:00', '2026-10-01 01:00:00');

    expect($minutes)->toHaveCount(60)
        ->and(array_unique(array_map(fn (MinuteCandles $m): int => count($m->candles), $minutes)))->toBe([1]);
});

it('refuses a symbol the database does not know', function (): void {
    feedInstrument('BTC/USDT');

    readFeed(['BTC/USDT', 'DOGE/USDT'], '2026-10-01 00:00:00', '2026-10-01 01:00:00');
})->throws(InvalidArgumentException::class, 'DOGE/USDT');

it('aggregates exactly like an independent SQL computation', function (Timeframe $timeframe): void {
    $btc = feedInstrument('BTC/USDT');
    storeCandles($btc, CandleFactory::walk('2026-10-01 00:00:00', 2 * 1440, seed: 21));

    $aggregator = new CandleAggregator($timeframe);
    $ours = [];

    foreach (readFeed(['BTC/USDT'], '2026-10-01 00:00:00', '2026-10-03 00:00:00') as $minute) {
        $candle = $aggregator->push($minute->candles['BTC/USDT']);

        if ($candle !== null) {
            $ours[] = implode('|', [
                $candle->openTime->getTimestamp(), $candle->open, $candle->high, $candle->low, $candle->close,
                $candle->volume, $candle->quoteVolume, $candle->tradeCount, $candle->takerBuyVolume,
            ]);
        }
    }

    $bucket = match ($timeframe) {
        Timeframe::FiveMinutes => 'to_timestamp(floor(extract(epoch from open_time) / 300) * 300)',
        Timeframe::OneHour => "date_trunc('hour', open_time)",
        default => "date_trunc('day', open_time)",
    };
    $rows = DB::table('candles')
        ->selectRaw('extract(epoch from '.$bucket.')::bigint as bucket,
               (array_agg(open order by open_time))[1] as open, max(high) as high, min(low) as low,
               (array_agg(close order by open_time desc))[1] as close, sum(volume) as volume,
               sum(quote_volume) as quote_volume, sum(trade_count) as trade_count,
               sum(taker_buy_volume) as taker_buy_volume')
        ->where('instrument_id', $btc->id)
        ->where('timeframe', '1m')
        ->groupByRaw('1')
        ->orderByRaw('1')
        ->get();
    $plain = fn (object $row, string $column): string => (string) Decimal::of(Rows::string($row, $column));
    $theirs = array_values($rows->map(fn (object $row): string => implode('|', [
        Rows::int($row, 'bucket'), $plain($row, 'open'), $plain($row, 'high'), $plain($row, 'low'), $plain($row, 'close'),
        $plain($row, 'volume'), $plain($row, 'quote_volume'), Rows::int($row, 'trade_count'), $plain($row, 'taker_buy_volume'),
    ]))->all());

    expect($ours)->toBe($theirs);
})->with([
    '5 minutes' => [Timeframe::FiveMinutes],
    '1 hour' => [Timeframe::OneHour],
    '1 day' => [Timeframe::OneDay],
]);

it('gives an instrument the history of its filters', function (): void {
    $btc = feedInstrument('BTC/USDT');
    foreach (['2026-11-08 12:00:00' => '0.00001', '2026-12-01 00:00:00' => '0.0001'] as $validFrom => $step) {
        InstrumentFilter::query()->create([
            'instrument_id' => $btc->id, 'valid_from' => $validFrom, 'tick_size' => '0.01', 'step_size' => $step,
            'min_qty' => $step, 'max_qty' => '9000', 'min_notional' => '5', 'raw' => [], 'created_at' => $validFrom,
        ]);
    }

    $history = $btc->rulesHistory();

    expect((string) $history->symbol)->toBe('BTC/USDT')
        ->and((string) $history->at(CarbonImmutable::parse('2026-12-02', 'UTC'))->stepSize)->toBe('0.0001')
        ->and((string) $history->at(CarbonImmutable::parse('2026-11-09', 'UTC'))->minNotional)->toBe('5')
        ->and($history->isAssumedAt(CarbonImmutable::parse('2026-10-01', 'UTC')))->toBeTrue();
});

it('mixes spot and perpetual instruments and hands out funding with the minute that ends at its time', function (): void {
    $spot = feedInstrument('BTC/USDT');
    $perp = feedInstrument('BTC/USDT:USDT', MarketType::Perp);
    storeCandles($spot, CandleFactory::walk('2026-10-01 07:55:00', 10, seed: 1));
    storeCandles($perp, CandleFactory::walk('2026-10-01 07:55:00', 10, seed: 2));
    app(FundingRateRepository::class)->upsert($perp, CandleSource::Archive, [
        new FundingRate(CarbonImmutable::parse('2026-10-01 08:00:00', 'UTC'), Decimal::of('0.0001'), 8, null),
        new FundingRate(CarbonImmutable::parse('2026-10-01 16:00:00', 'UTC'), Decimal::of('0.0002'), 8, null),
    ]);

    $minutes = readFeed(['BTC/USDT:USDT', 'BTC/USDT'], '2026-10-01 07:55:00', '2026-10-01 08:05:00', chunkMinutes: 3);
    $withFunding = array_values(array_filter($minutes, fn (MinuteCandles $m): bool => $m->funding !== []));

    expect($minutes)->toHaveCount(10)
        ->and(array_keys($minutes[0]->candles))->toBe(['BTC/USDT', 'BTC/USDT:USDT'])
        ->and($withFunding)->toHaveCount(1)
        ->and($withFunding[0]->closedAt->format('H:i'))->toBe('08:00')
        ->and(array_keys($withFunding[0]->funding))->toBe(['BTC/USDT:USDT'])
        ->and((string) $withFunding[0]->funding['BTC/USDT:USDT']->rate)->toBe('0.0001');
});
