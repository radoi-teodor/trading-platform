<?php

declare(strict_types=1);

use App\Market\CandleRepository;
use App\Market\Data\Candle;
use App\Market\Data\CandleSource;
use App\Market\Data\FundingRate;
use App\Market\Data\MarketType;
use App\Market\Data\OpenInterest;
use App\Market\Data\Positioning;
use App\Market\Data\PriceMinute;
use App\Market\Data\TradeFlow;
use App\Market\FundingRateRepository;
use App\Market\Models\Instrument;
use App\Market\Models\InstrumentFilter;
use App\Market\ScalpingRepository;
use App\Simulation\Charts\ChartSeries;
use App\Strategy\Dsl\DslError;
use App\Support\Number\Decimal;
use App\Support\Symbol;
use App\Support\Timeframe;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Tests\Support\CandleFactory;

uses(RefreshDatabase::class);

/**
 * The oracle's inputs and expected values, every value as text (null where the oracle has none).
 *
 * @return array{minutes: list<array<string, ?string>>, open_interest: list<array<string, ?string>>, expected: list<array<string, ?string>>}
 */
function scalpingReference(): array
{
    $json = json_decode((string) file_get_contents(__DIR__.'/../../Fixtures/scalping-functions/reference.json'), true, flags: JSON_THROW_ON_ERROR);

    return ['minutes' => referencePart($json, 'minutes'), 'open_interest' => referencePart($json, 'open_interest'), 'expected' => referencePart($json, 'expected')];
}

/** @return list<array<string, ?string>> */
function referencePart(mixed $json, string $key): array
{
    $rows = is_array($json) && is_array($json[$key] ?? null) ? $json[$key] : throw new RuntimeException("The reference has no {$key}.");
    $parts = [];

    foreach ($rows as $row) {
        $fields = [];

        foreach (is_array($row) ? $row : throw new RuntimeException('A reference row is not a map.') as $name => $value) {
            $fields[(string) $name] = is_int($value) || is_string($value) ? (string) $value : null;
        }

        $parts[] = $fields;
    }

    return $parts;
}

/** Stores the reference's hour of a perpetual: candles, trade flow, marks, premium and open interest. */
function storeScalpingHour(): Instrument
{
    $reference = scalpingReference();
    $perp = Instrument::register('binance', MarketType::Perp, Symbol::parse('BTC/USDT:USDT'), CarbonImmutable::parse('2026-11-01', 'UTC'));
    $candles = $flow = $marks = $premium = [];
    $s = fn (array $m, string $key): string => is_string($m[$key] ?? null) ? $m[$key] : throw new RuntimeException("No {$key}.");

    foreach ($reference['minutes'] as $m) {
        $at = CarbonImmutable::createFromTimestampUTC((int) $s($m, 'open_time'));
        $candles[] = Candle::fromStrings($at, Timeframe::OneMinute, $s($m, 'open'), $s($m, 'high'), $s($m, 'low'), $s($m, 'close'), $s($m, 'volume'), '1000', 10, $s($m, 'taker_buy'));
        $flow[] = new TradeFlow($at, Decimal::of($s($m, 'large_buy')), Decimal::of($s($m, 'large_sell')), 1, 1, Decimal::of('1'), Decimal::of('100000'));
        $marks[] = new PriceMinute($at, Decimal::of($s($m, 'mark')), Decimal::of($s($m, 'mark')), Decimal::of($s($m, 'mark')), Decimal::of($s($m, 'mark')));
        $premium[] = new PriceMinute($at, Decimal::of($s($m, 'premium')), Decimal::of($s($m, 'premium')), Decimal::of($s($m, 'premium')), Decimal::of($s($m, 'premium')));
    }

    $repository = app(ScalpingRepository::class);
    app(CandleRepository::class)->upsert($perp, CandleSource::Archive, $candles);
    $repository->upsertFlow($perp, CandleSource::Archive, $flow);
    $repository->upsertMarks($perp, CandleSource::Archive, $marks);
    $repository->upsertPremium($perp, CandleSource::Archive, $premium);
    $repository->upsertOpenInterest($perp, CandleSource::Archive, array_map(
        fn (array $o): OpenInterest => new OpenInterest(CarbonImmutable::createFromTimestampUTC((int) $s($o, 'time')), Decimal::of($s($o, 'contracts')), Decimal::of($s($o, 'value'))),
        $reference['open_interest'],
    ));

    return $perp;
}

it('computes every scalping function as the Python oracle does, at the close of each candle', function (): void {
    storeScalpingHour();
    $layers = ['buy_volume', 'sell_volume', 'delta', 'cvd(3)', 'large_buy_volume', 'large_delta', 'open_interest', 'oi_change(2)', 'mark_price', 'basis'];

    $chart = app(ChartSeries::class)->compute(Symbol::parse('BTC/USDT:USDT'), Timeframe::FiveMinutes, CarbonImmutable::parse('2026-11-01 00:00', 'UTC'), CarbonImmutable::parse('2026-11-01 01:00', 'UTC'), $layers);

    foreach ($layers as $layer) {
        $expected = array_map(fn (array $row): mixed => $row[$layer], scalpingReference()['expected']);
        expect($chart['series'][$layer])->toBe($expected, $layer);
    }
});

it('reads open interest only five minutes after its time, never earlier', function (): void {
    storeScalpingHour();
    $stamped = scalpingReference()['open_interest'];

    $chart = app(ChartSeries::class)->compute(Symbol::parse('BTC/USDT:USDT'), Timeframe::FiveMinutes, CarbonImmutable::parse('2026-11-01 00:00', 'UTC'), CarbonImmutable::parse('2026-11-01 00:10', 'UTC'), ['open_interest']);

    // The candle closing at 00:05 sees the value stamped 00:00, not the one stamped 00:05.
    expect($chart['series']['open_interest'][0])->toBe((string) Decimal::of($stamped[0]['contracts'] ?? ''))
        ->and($chart['series']['open_interest'][0])->not->toBe((string) Decimal::of($stamped[1]['contracts'] ?? ''));
});

it('reads the long/short, top-trader and taker ratios five minutes after their time (D-91)', function (): void {
    $perp = storeScalpingHour();
    $rows = [];

    foreach (range(0, 11) as $i) {
        $rows[] = new Positioning(CarbonImmutable::parse('2026-11-01 00:00', 'UTC')->addMinutes(5 * $i), Decimal::of((string) (2 + $i)), Decimal::of((string) (10 + $i)), Decimal::of((string) (20 + $i)));
    }

    app(ScalpingRepository::class)->upsertPositioning($perp, CandleSource::Archive, $rows);
    $chart = app(ChartSeries::class)->compute(Symbol::parse('BTC/USDT:USDT'), Timeframe::FiveMinutes, CarbonImmutable::parse('2026-11-01 00:00', 'UTC'), CarbonImmutable::parse('2026-11-01 00:15', 'UTC'), ['long_short_ratio', 'top_trader_ratio', 'taker_ratio']);

    // The candle closing at 00:05 sees the row stamped 00:00, the one closing at 00:15 the row stamped 00:10.
    expect($chart['series']['long_short_ratio'])->toBe(['2', '3', '4'])
        ->and($chart['series']['top_trader_ratio'])->toBe(['10', '11', '12'])
        ->and($chart['series']['taker_ratio'])->toBe(['20', '21', '22'])
        ->and($chart['types']['long_short_ratio'])->toBe('a value series');
});

it('knows what each function measures, and keeps the large-trade flow to perpetuals', function (): void {
    storeScalpingHour();
    $types = app(ChartSeries::class)->compute(Symbol::parse('BTC/USDT:USDT'), Timeframe::FiveMinutes, CarbonImmutable::parse('2026-11-01 00:00', 'UTC'), CarbonImmutable::parse('2026-11-01 00:10', 'UTC'), ['oi_change(2) > 2%', 'cvd(3)', 'basis'])['types'];

    expect($types)->toBe(['oi_change(2) > 2%' => 'a condition', 'cvd(3)' => 'a volume series', 'basis' => 'a rate series']);

    Instrument::register('binance', MarketType::Spot, Symbol::parse('BTC/USDT'), CarbonImmutable::parse('2026-11-01', 'UTC'));
    app(ChartSeries::class)->compute(Symbol::parse('BTC/USDT'), Timeframe::FiveMinutes, CarbonImmutable::parse('2026-11-01 00:00', 'UTC'), CarbonImmutable::parse('2026-11-01 00:10', 'UTC'), ['large_delta']);
})->throws(DslError::class, 'large_delta');

const OI_STRATEGY = <<<'YAML'
    strategy: oi-push
    title: Open interest push, long, on the perpetual
    family: trend
    hypothesis: New positions opening into a rising price carry it further.
    fails_when: Open interest rises on hedges, not on new longs.
    params:
      bars: { default: 2, range: [2, 4], step: 1 }
    market: { exchange: binance, type: perp, instruments: [BTC/USDT:USDT] }
    timeframe: 5m
    entries:
      long:
        when: oi_change($bars) > 0 and delta > 0 and mark_price > 0
    exits:
      stop_loss: 1 * atr(3)
      take_profit: 2R
    YAML;

/** The hour of storeScalpingHour with what a backtest needs besides: rules and funding. */
function backtestableScalpingHour(): string
{
    $perp = storeScalpingHour();
    // Candles around the reference hour: the warmup day before it and the rest of its day.
    app(CandleRepository::class)->upsert($perp, CandleSource::Archive, [
        ...CandleFactory::walk('2026-10-31 00:00:00', 1440, seed: 5, price: '99.00'),
        ...CandleFactory::walk('2026-11-01 01:00:00', 1380, seed: 6, price: '96.00'),
    ]);
    InstrumentFilter::query()->create([
        'instrument_id' => $perp->id, 'valid_from' => '2026-10-01', 'tick_size' => '0.01', 'step_size' => '0.001',
        'min_qty' => '0.001', 'max_qty' => '1000', 'min_notional' => '5', 'raw' => [], 'created_at' => '2026-10-01',
    ]);
    app(FundingRateRepository::class)->upsert($perp, CandleSource::Archive, [
        ...array_map(fn (int $h): FundingRate => new FundingRate(CarbonImmutable::parse('2026-10-31 00:00', 'UTC')->addHours($h), Decimal::of('0.0001'), 8, null), [0, 8, 16, 24, 32, 40, 48]),
    ]);
    $file = storage_path('framework/testing/oi-push.strategy.yaml');
    @mkdir(dirname($file), 0o775, true);
    file_put_contents($file, OI_STRATEGY);

    return $file;
}

it('backtests a strategy that reads open interest and the mark price, and refuses a range without them', function (): void {
    $file = backtestableScalpingHour();
    $run = fn (): int => Artisan::call('backtest:run', ['--file' => $file, '--from' => '2026-11-01', '--to' => '2026-11-02', '--no-benchmarks' => true]);

    expect($run())->toBe(0, Artisan::output());

    DB::table('open_interest')->delete();

    expect($run())->toBe(1)
        ->and(Artisan::output())->toContain('BTC/USDT:USDT: no open interest stored on 2026-11-01 (market:backfill --oi)');
});
