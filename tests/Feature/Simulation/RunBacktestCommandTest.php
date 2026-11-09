<?php

declare(strict_types=1);

use App\Simulation\Models\BacktestRun;
use App\Simulation\Models\StrategyTrial;
use App\Simulation\Report\CanonicalJson;
use App\Strategy\Baselines\FundingCarry;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

uses(RefreshDatabase::class);

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
        ->and($report)->toHaveKey('definition.strategy.parameters', ['bars' => '60', 'side' => 'long', 'stop' => '0.05', 'warmup' => '5'])
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

it('backtests a perpetual strategy from the futures wallet, with funding, against spot benchmarks of the same base', function (): void {
    backtestFixture();
    perpFixture();

    $exit = Artisan::call('backtest:run', [
        'strategy' => 'enter-once', '--symbol' => ['BTC/USDT:USDT'], '--from' => '2026-10-02', '--to' => '2026-10-03',
        '--param' => ['side=short', 'bars=600'],
    ]);
    $run = BacktestRun::query()->sole();
    $report = storedReport($run);

    expect($exit)->toBe(0)
        ->and($run->trades)->toBe(1)
        ->and($report)->toHaveKey('definition.market', 'perp')
        ->and($report)->toHaveKey('definition.initial_balance', ['amount' => '0', 'asset' => 'USDT'])
        ->and($report)->toHaveKey('definition.initial_futures_balance', '10000')
        ->and($report)->toHaveKey('definition.perpetual', ['fees' => ['maker' => '0.0002', 'taker' => '0.0005'], 'maintenance_margin_rate' => '0.01'])
        ->and($report)->toHaveKey('trades.0.side', 'sell')
        ->and($report)->toHaveKey('benchmarks.buy-and-hold.strategy', 'hold|1|warmup_days=0')
        ->and(Artisan::output())->toContain('Funding');
    // Two fundings while the short was open (08:00 and 16:00), both received: a positive amount.
    expect(data_get($report, 'trades.0.funding'))->toMatch('/^\d+\.\d+$/');
});

it('splits the equity between the wallets when the strategy follows both markets, whichever symbol it was given', function (): void {
    backtestFixture();
    perpFixture();
    config()->set('simulation.strategies.funding-carry', ['class' => FundingCarry::class, 'profile' => 'account']);

    expect(Artisan::call('backtest:run', [
        'strategy' => 'funding-carry', '--symbol' => ['BTC/USDT:USDT'], '--from' => '2026-10-02', '--to' => '2026-10-03',
        '--param' => ['entry_periods=1', 'exit_periods=1'], '--no-benchmarks' => true,
    ]))->toBe(0)
        ->and(BacktestRun::query()->sole()->definition['symbols'])->toBe(['BTC/USDT', 'BTC/USDT:USDT'])
        ->and(BacktestRun::query()->sole()->definition['initial_balance'])->toEqual(['amount' => '5000', 'asset' => 'USDT'])
        ->and(BacktestRun::query()->sole()->definition['initial_futures_balance'])->toBe('5000');
});

it('refuses a perpetual run over missing funding, and says where it is missing', function (): void {
    backtestFixture();
    $perp = perpFixture();
    DB::table('funding_rates')->where('instrument_id', $perp->id)->where('funding_time', '2026-10-02 08:00:00+00')->delete();

    $exit = Artisan::call('backtest:run', ['strategy' => 'enter-once', '--symbol' => ['BTC/USDT:USDT'], '--from' => '2026-10-02', '--to' => '2026-10-03']);

    expect($exit)->toBe(1)
        ->and(Artisan::output())->toContain('BTC/USDT:USDT: funding missing between 2026-10-02 00:00 and 2026-10-02 16:00')
        ->and(BacktestRun::query()->count())->toBe(0);
});

it('refuses a short on spot', function (): void {
    backtestFixture();

    expect(Artisan::call('backtest:run', ['strategy' => 'enter-once', '--from' => '2026-10-02', '--to' => '2026-10-03', '--param' => ['side=short']]))->toBe(1)
        ->and(Artisan::output())->toContain('short');
});

it('backtests a strategy document', function (): void {
    backtestFixture();

    $exit = Artisan::call('backtest:run', ['--file' => strategyFile(EMA_CROSS_DOCUMENT), '--from' => '2026-10-02', '--to' => '2026-10-03', '--param' => ['fast=5']]);
    $run = BacktestRun::query()->sole();
    $report = storedReport($run);

    expect($exit)->toBe(0)
        ->and($run->strategy)->toBe('ema-cross-test@v1')
        ->and($report)->toHaveKey('definition.strategy.parameters', ['fast' => '5', 'slow' => '21'])
        ->and($report)->toHaveKey('benchmarks.buy-and-hold.strategy')
        ->and($run->trades)->toBeGreaterThan(0);
});

it('refuses a document with errors and lists them with their places', function (): void {
    backtestFixture();
    $path = strategyFile(str_replace('stop_loss: 1.5 * atr(14)', 'stop_loss: close > 2%', EMA_CROSS_DOCUMENT));

    expect(Artisan::call('backtest:run', ['--file' => $path, '--from' => '2026-10-02', '--to' => '2026-10-03']))->toBe(1)
        ->and(Artisan::output())->toContain("{$path}:15:14: cannot compare a price series with a percent (close > 2%). Did you mean close > close[1] * 1.02 ?")
        ->and(BacktestRun::query()->count())->toBe(0);
});

it('compiles a document and says what it found', function (): void {
    backtestFixture();
    $path = strategyFile(EMA_CROSS_DOCUMENT);

    $exit = Artisan::call('strategy:compile', ['file' => $path]);
    $output = Artisan::output();

    expect($exit)->toBe(0)
        ->and($output)->toContain('ema-cross-test compiles')
        ->and($output)->toContain('BTC/USDT on 5m')
        ->and($output)->toContain('Warmup: 22 candles')
        ->and($output)->toContain('fast = 9, slow = 21');
    expect(Artisan::call('strategy:compile', ['file' => strategyFile(str_replace('ema($fast)', 'emma($fast)', EMA_CROSS_DOCUMENT))]))->toBe(1)
        ->and(Artisan::output())->toContain(':13:11: emma is not a function of the DSL. Did you mean ema?');
});

it('compiles a universe strategy without its members and shows its rule (D-92)', function (): void {
    backtestFixture();

    $exit = Artisan::call('strategy:compile', ['file' => strategyFile(UNIVERSE_DOCUMENT)]);
    $output = Artisan::output();

    expect($exit)->toBe(0)
        ->and($output)->toContain('xs-momentum-test compiles')
        ->and($output)->toContain('universe { rule: top, n: 50, by: quote_volume_30d } on 4h')
        ->and($output)->toContain('Warmup: 43 candles')
        ->and($output)->toContain('On the top 50 USDT perpetuals by 30-day quote volume');
});

it('runs a grid of parameters in one pass, with the deflated Sharpe ratio and the probability of overfitting', function (): void {
    backtestFixture(days: 36);

    $exit = Artisan::call('backtest:grid', [
        '--file' => strategyFile(EMA_CROSS_DOCUMENT), '--from' => '2026-10-02', '--to' => '2026-11-05',
        '--grid' => ['fast=5,9', 'slow=15..21:6'],
    ]);
    $output = Artisan::output();
    $trials = StrategyTrial::query()->orderBy('id')->get();

    expect($exit)->toBe(0)
        ->and($output)->toContain('4 parameter sets')
        ->and($output)->toContain('fast=5 slow=15')
        ->and($output)->toContain('fast=9 slow=21')
        ->and($output)->toContain('Deflated Sharpe ratio of the best set')
        ->and($output)->toContain('Probability of backtest overfitting')
        ->and($trials)->toHaveCount(4)
        ->and($trials->first()?->family)->toBe('ema-cross-test')
        ->and($trials->first()?->observations)->toBe(34);
    expect(glob(config()->string('simulation.artifacts').'/grid-*.json.gz'))->toHaveCount(1);
});

it('refuses a grid it cannot run', function (array $grid, string $message): void {
    backtestFixture();

    expect(Artisan::call('backtest:grid', ['--file' => strategyFile(EMA_CROSS_DOCUMENT), '--from' => '2026-10-02', '--to' => '2026-10-03', '--grid' => $grid]))->toBe(1)
        ->and(Artisan::output())->toContain($message);
})->with([
    'unknown parameter' => [['speed=1,2'], 'ema-cross-test has no parameter speed.'],
    'bad values' => [['fast=a..b'], 'fast=a..b: write values as 5,7,9 or 5..13:2.'],
    'too many sets' => [['fast=1..100:1', 'slow=1..100:1'], '10000 parameter sets; at most 400 run in one pass.'],
]);

it('records every backtest of a document as a trial of its family', function (): void {
    backtestFixture();

    Artisan::call('backtest:run', ['--file' => strategyFile(EMA_CROSS_DOCUMENT), '--from' => '2026-10-02', '--to' => '2026-10-03']);

    expect(StrategyTrial::query()->sole()->family)->toBe('ema-cross-test')
        ->and(StrategyTrial::query()->sole()->parameters)->toBe(['fast' => '9', 'slow' => '21']);
});

it('walks forward over the grid\'s continuous runs', function (): void {
    backtestFixture(days: 36);

    $exit = Artisan::call('backtest:grid', [
        '--file' => strategyFile(EMA_CROSS_DOCUMENT), '--from' => '2026-10-02', '--to' => '2026-11-05',
        '--grid' => ['fast=5,9', 'slow=15,21'], '--walk-forward' => true, '--in' => '0', '--out' => '0',
    ]);

    expect($exit)->toBe(1)
        ->and(Artisan::output())->toContain('--in and --out are whole numbers of days or months, such as 12m or 20d.');

    $exit = Artisan::call('backtest:grid', [
        '--file' => strategyFile(EMA_CROSS_DOCUMENT), '--from' => '2026-10-02', '--to' => '2026-11-05',
        '--grid' => ['fast=5,9', 'slow=15,21'], '--walk-forward' => true, '--in' => '10d', '--out' => '5d', '--step' => '5d', '--min-trades' => '1',
    ]);
    $output = Artisan::output();

    expect($exit)->toBe(0)
        ->and($output)->toContain('Walk-forward: 4 windows of 10 days in-sample and 5 days out of sample, stepped by 5 days')
        ->and($output)->toContain('Out of sample:')
        ->and($output)->toContain('positive windows');
});

it('backtests a saved version, with the same report as its file and the version in its identity', function (): void {
    backtestFixture();
    $file = strategyFile(EMA_CROSS_DOCUMENT);
    Artisan::call('strategy:import', ['path' => $file]);

    Artisan::call('backtest:run', ['--file' => $file, '--from' => '2026-10-02', '--to' => '2026-10-03', '--no-benchmarks' => true]);
    $exit = Artisan::call('backtest:run', ['strategy' => 'ema-cross-test@v1', '--from' => '2026-10-02', '--to' => '2026-10-03', '--no-benchmarks' => true]);
    $runs = BacktestRun::query()->orderBy('id')->get();

    expect($exit)->toBe(0)
        ->and($runs)->toHaveCount(2)
        ->and($runs->pluck('report_hash')->unique()->all())->toHaveCount(1)
        ->and(Artisan::call('backtest:run', ['strategy' => 'ema-cross-test@v9', '--from' => '2026-10-02', '--to' => '2026-10-03']))->toBe(1)
        ->and(Artisan::output())->toContain('ema-cross-test@v9 is not a saved version.');
});
