<?php

declare(strict_types=1);

use App\Market\CandleRepository;
use App\Market\Data\CandleSource;
use App\Market\Data\MarketType;
use App\Market\Models\Instrument;
use App\Market\Models\InstrumentFilter;
use App\Simulation\Models\BacktestRun;
use App\Simulation\Report\CanonicalJson;
use App\Support\Clock\Clock;
use App\Support\Clock\SimClock;
use App\Support\Symbol;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Tests\Support\CandleFactory;
use Tests\Support\EnterOnceStrategy;
use Tests\Support\HoldStrategy;

uses(RefreshDatabase::class);

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

it('backtests a strategy, stores its report by hash and records the run', function (): void {
    backtestFixture();

    $exit = Artisan::call('backtest:run', ['strategy' => 'enter-once', '--from' => '2026-10-02', '--to' => '2026-10-03', '--param' => ['bars=60']]);
    $run = BacktestRun::query()->sole();
    $compressed = file_get_contents($run->artifact);
    $json = gzdecode($compressed === false ? '' : $compressed);
    $report = json_decode($json === false ? '' : $json, true, flags: JSON_THROW_ON_ERROR);

    expect($exit)->toBe(0)
        ->and(Artisan::output())->toContain('Report '.$run->report_hash)
        ->and($run->strategy)->toBe('enter-once@v1')
        ->and(hash('sha256', $json === false ? '' : $json))->toBe($run->report_hash)
        ->and($json)->toBe(CanonicalJson::encode($report))
        ->and($run->trades)->toBe(1)
        ->and($run->window_from->toDateString())->toBe('2026-10-02')
        ->and($report)->toHaveKey('definition.strategy.parameters', ['bars' => '60', 'stop' => '0.05', 'warmup' => '5'])
        ->and($report)->toHaveKey('benchmarks.buy-and-hold.strategy', 'hold|1|warmup_days=0')
        ->and($report)->toHaveKey('benchmarks.long-history.missing');
});

it('gives the same report for the same run', function (): void {
    backtestFixture();

    Artisan::call('backtest:run', ['strategy' => 'enter-once', '--from' => '2026-10-02', '--to' => '2026-10-03']);
    Artisan::call('backtest:run', ['strategy' => 'enter-once', '--from' => '2026-10-02', '--to' => '2026-10-03']);

    expect(BacktestRun::query()->distinct()->pluck('report_hash'))->toHaveCount(1)
        ->and(BacktestRun::query()->count())->toBe(2);
});

it('refuses to run over history with gaps, and says where they are', function (): void {
    $btc = backtestFixture();
    DB::table('candles')->where('instrument_id', $btc->id)->whereBetween('open_time', ['2026-10-02 10:00:00+00', '2026-10-02 10:04:00+00'])->delete();

    $exit = Artisan::call('backtest:run', ['strategy' => 'enter-once', '--from' => '2026-10-02', '--to' => '2026-10-03']);

    expect($exit)->toBe(1)
        ->and(Artisan::output())->toContain('5 minutes missing in 1 gaps, the first from 2026-10-02 10:00 to 2026-10-02 10:04')
        ->and(BacktestRun::query()->count())->toBe(0);
});

it('refuses unknown strategies, parameters, days and instruments', function (array $arguments, string $message): void {
    backtestFixture();

    expect(Artisan::call('backtest:run', $arguments))->toBe(1)
        ->and(Artisan::output())->toContain($message);
})->with([
    'strategy' => [['strategy' => 'nope', '--from' => '2026-10-02', '--to' => '2026-10-03'], "Unknown strategy 'nope'. Known: enter-once, hold."],
    'parameter' => [['strategy' => 'enter-once', '--from' => '2026-10-02', '--to' => '2026-10-03', '--param' => ['speed=2']], "Unknown or empty parameter 'speed=2'"],
    'day' => [['strategy' => 'enter-once', '--from' => '2 October', '--to' => '2026-10-03'], '--from must be a day'],
    'window' => [['strategy' => 'enter-once', '--from' => '2026-10-03', '--to' => '2026-10-02'], '--to must be after --from'],
    'instrument' => [['strategy' => 'enter-once', '--symbol' => ['ETH/USDT'], '--from' => '2026-10-02', '--to' => '2026-10-03'], 'ETH/USDT is not a known spot instrument'],
]);

it('runs a benchmark-profile strategy without comparisons when asked', function (): void {
    backtestFixture();

    expect(Artisan::call('backtest:run', ['strategy' => 'hold', '--from' => '2026-10-02', '--to' => '2026-10-03', '--no-benchmarks' => true, '--equity' => '5000']))->toBe(0)
        ->and(BacktestRun::query()->sole()->definition['risk'])->toBe(['profile' => 'benchmark'])
        ->and(BacktestRun::query()->sole()->definition['initial_balance'])->toEqual(['amount' => '5000', 'asset' => 'USDT'])
        ->and(BacktestRun::query()->sole()->definition['benchmarks'])->toBe([]);
});
