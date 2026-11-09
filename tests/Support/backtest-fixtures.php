<?php

declare(strict_types=1);

use App\Market\CandleRepository;
use App\Market\Data\CandleSource;
use App\Market\Data\FundingRate;
use App\Market\Data\MarketType;
use App\Market\FundingRateRepository;
use App\Market\Models\Instrument;
use App\Market\Models\InstrumentFilter;
use App\Simulation\Models\BacktestRun;
use App\Support\Clock\Clock;
use App\Support\Clock\SimClock;
use App\Support\Number\Decimal;
use App\Support\Symbol;
use Carbon\CarbonImmutable;
use Tests\Support\CandleFactory;
use Tests\Support\EnterOnceStrategy;
use Tests\Support\HoldStrategy;

/* Fixtures shared by the backtest command and simulation job tests. */

function backtestFixture(int $days = 2): Instrument
{
    app()->instance(Clock::class, new SimClock(CarbonImmutable::parse('2026-11-08 12:00:00', 'UTC')));
    $btc = Instrument::register('binance', MarketType::Spot, Symbol::parse('BTC/USDT'), CarbonImmutable::parse('2026-11-08 12:00:00', 'UTC'));
    InstrumentFilter::query()->create([
        'instrument_id' => $btc->id, 'valid_from' => '2026-11-08 12:00:00', 'tick_size' => '0.01', 'step_size' => '0.001',
        'min_qty' => '0.001', 'max_qty' => '9000', 'min_notional' => '5', 'raw' => [], 'created_at' => '2026-11-08 12:00:00',
    ]);
    app(CandleRepository::class)->upsert($btc, CandleSource::Archive, CandleFactory::walk('2026-10-01 00:00:00', $days * 1440, seed: 17));

    $directory = storage_path('framework/testing/simulations');
    array_map('unlink', glob("{$directory}/*.json.gz") ?: []);
    config()->set('simulation.artifacts', $directory);
    config()->set('simulation.strategies', [
        'enter-once' => ['class' => EnterOnceStrategy::class, 'profile' => 'account'],
        'hold' => ['class' => HoldStrategy::class, 'profile' => 'benchmark'],
    ]);
    config()->set('simulation.benchmarks', [
        'buy-and-hold' => ['strategy' => 'hold', 'parameters' => []],
        'long-history' => ['strategy' => 'hold', 'parameters' => ['warmup_days' => '30']],
    ]);

    return $btc;
}

/** A BTC perpetual next to the spot fixture, with funding every 8 hours from 2026-10-01 00:00 for $days days. */
function perpFixture(int $days = 2): Instrument
{
    $perp = Instrument::register('binance', MarketType::Perp, Symbol::parse('BTC/USDT:USDT'), CarbonImmutable::parse('2026-11-08 12:00:00', 'UTC'));
    InstrumentFilter::query()->create([
        'instrument_id' => $perp->id, 'valid_from' => '2026-11-08 12:00:00', 'tick_size' => '0.1', 'step_size' => '0.001',
        'min_qty' => '0.001', 'max_qty' => '1000', 'min_notional' => '100', 'raw' => [], 'created_at' => '2026-11-08 12:00:00',
    ]);
    app(CandleRepository::class)->upsert($perp, CandleSource::Archive, CandleFactory::walk('2026-10-01 00:00:00', $days * 1440, seed: 17));
    $rates = [];

    for ($i = 0; $i <= $days * 3; $i++) {
        $rates[] = new FundingRate(CarbonImmutable::parse('2026-10-01 00:00:00', 'UTC')->addHours(8 * $i), Decimal::of('0.0001'), 8, null);
    }

    app(FundingRateRepository::class)->upsert($perp, CandleSource::Archive, $rates);

    return $perp;
}

/** @return array<array-key, mixed> the decoded canonical JSON */
function storedReport(BacktestRun $run): array
{
    $compressed = file_get_contents($run->artifact);
    $json = gzdecode($compressed === false ? '' : $compressed);

    $report = json_decode($json === false ? '' : $json, true, flags: JSON_THROW_ON_ERROR);

    return is_array($report) ? $report : throw new RuntimeException('The stored report is not an object.');
}

/** Writes a strategy document for a command test and returns its path. */
function strategyFile(string $yaml): string
{
    $path = storage_path('framework/testing/strategy-'.substr(hash('sha256', $yaml), 0, 8).'.yaml');
    file_put_contents($path, $yaml);

    return $path;
}

const EMA_CROSS_DOCUMENT = <<<'YAML'
    strategy: ema-cross-test
    title: EMA cross on 5m
    family: trend
    hypothesis: Short-term momentum continues after a cross.
    fails_when: Chop.
    params:
      fast: { default: 9, range: [5, 13], step: 2 }
      slow: { default: 21, range: [15, 30], step: 3 }
    market: { exchange: binance, type: spot, instruments: [BTC/USDT] }
    timeframe: 5m
    entries:
      long:
        when: ema($fast) crosses_above ema($slow)
    exits:
      stop_loss: 1.5 * atr(14)
      take_profit: 2R
      when: ema($fast) crosses_below ema($slow)
    YAML;
