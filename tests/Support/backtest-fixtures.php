<?php

declare(strict_types=1);

use App\Market\CandleRepository;
use App\Market\Data\CandleSource;
use App\Market\Data\FundingRate;
use App\Market\Data\InstrumentStatus;
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
use Illuminate\Support\Facades\DB;
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

const UNIVERSE_DOCUMENT = <<<'YAML'
    strategy: xs-momentum-test
    title: Cross-sectional momentum
    family: trend
    hypothesis: The perpetuals that rose most over a week keep rising against those that fell most.
    fails_when: Sharp reversals of the whole market.

    universe: { rule: top, n: 50, by: quote_volume_30d }
    timeframe: 4h

    entries:
      long:
        when: top(5, roc(42))
      short:
        when: bottom(5, roc(42))

    exits:
      stop_loss: 2 * atr(14)
      when: rank(roc(42)) > 10 and rank(roc(42)) <= count - 10

    sizing:
      risk_per_trade: 0.3%
    YAML;

const UNIVERSE_SYMBOLS_FIXTURE = ['AAA/USDT:USDT', 'BBB/USDT:USDT', 'CCC/USDT:USDT', 'DDD/USDT:USDT', 'EEE/USDT:USDT', 'FFF/USDT:USDT'];

const UNIVERSE_FIXTURE_DOCUMENT = <<<'YAML'
    strategy: xs-fixture
    title: Cross-sectional momentum on the fixture universe
    family: trend
    hypothesis: The perpetuals that rose most over half an hour keep rising against those that fell most.
    fails_when: Sharp reversals of the whole market.

    universe: { rule: top, n: 6, by: quote_volume_30d }
    timeframe: 5m

    entries:
      long:
        when: top(2, roc(6))
      short:
        when: bottom(2, roc(6))

    exits:
      stop_loss: 2 * atr(6)
      when: rank(roc(6)) > 2 and rank(roc(6)) <= count - 2

    sizing:
      risk_per_trade: 0.02%
    YAML;

/**
 * The research universe of October 2026: six liquid perpetuals, AAA to FFF by rank, with minutes and funding from
 * 2026-10-01 for $days days, next to the spot fixture. FFF is known only from the archives: it has no filters and
 * stopped trading on 2026-10-02 at 12:00.
 *
 * @return array<string, Instrument> by unified symbol
 */
function universeFixture(int $days = 2): array
{
    backtestFixture($days);
    $start = CarbonImmutable::parse('2026-10-01 00:00:00', 'UTC');
    $perps = [];

    foreach (UNIVERSE_SYMBOLS_FIXTURE as $index => $symbol) {
        $gone = $symbol === 'FFF/USDT:USDT';
        $minutes = $gone ? 2160 : $days * 1440;
        $perp = Instrument::register('binance', MarketType::Perp, Symbol::parse($symbol), CarbonImmutable::parse('2026-11-08 12:00:00', 'UTC'), $gone ? InstrumentStatus::Delisted : InstrumentStatus::Trading);

        if ($gone) {
            $perp->update(['delisted_at' => $start->addMinutes($minutes)]);
        } else {
            InstrumentFilter::query()->create([
                'instrument_id' => $perp->id, 'valid_from' => '2026-09-01 00:00:00', 'tick_size' => '0.01', 'step_size' => '0.001',
                'min_qty' => '0.001', 'max_qty' => '100000', 'min_notional' => '5', 'raw' => [], 'created_at' => '2026-09-01 00:00:00',
            ]);
        }

        app(CandleRepository::class)->upsert($perp, CandleSource::Archive, CandleFactory::walk('2026-10-01 00:00:00', $minutes, seed: 31 + $index, liquidity: 1000));
        $rates = [];

        for ($time = $start; $time <= $start->addMinutes($minutes); $time = $time->addHours(8)) {
            $rates[] = new FundingRate($time, Decimal::of('0.0001'), 8, null);
        }

        app(FundingRateRepository::class)->upsert($perp, CandleSource::Archive, $rates);
        DB::table('universe_members')->insert(['month' => '2026-10-01', 'instrument_id' => $perp->id, 'rank' => $index + 1, 'quote_volume_30d' => (string) (600 - 100 * $index)]);
        $perps[$symbol] = $perp;
    }

    return $perps;
}
