<?php

declare(strict_types=1);

use App\Simulation\Models\BacktestRun;
use App\Simulation\Models\StrategyGateReport;
use App\Simulation\Models\StrategyHoldout;
use App\Simulation\StrategyLibrary;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

uses(RefreshDatabase::class);

/**
 * @param  array<string, mixed>  $options
 * @return array{int, string, array<array-key, mixed>} the exit code, the output and the sets of the summary
 */
function universeGrid(array $options = []): array
{
    $exit = Artisan::call('backtest:grid', ['--file' => strategyFile(universeGridDocument()), '--from' => '2026-10-02', '--to' => '2026-10-03', '--json' => true, ...$options]);
    $output = Artisan::output();
    $lines = array_values(array_filter(explode("\n", trim($output))));
    $summary = json_decode((string) end($lines), true);

    return [$exit, $output, is_array($summary) && is_array($summary['sets'] ?? null) ? $summary['sets'] : []];
}

it('runs every parameter set of a universe strategy in one pass, each as its own backtest would (D-92)', function (): void {
    universeFixture();

    [$exit, $output, $sets] = universeGrid();
    Artisan::call('backtest:run', ['--file' => strategyFile(universeGridDocument()), '--from' => '2026-10-02', '--to' => '2026-10-03', '--no-benchmarks' => true]);
    $alone = BacktestRun::query()->sole();

    expect($exit)->toBe(0)
        ->and($output)->toContain('2 parameter sets of xs-grid on the top 6 by quote_volume_30d from 2026-10-02 to 2026-10-03, in one pass')
        ->and(data_get($sets, '*.parameters'))->toBe([['look' => '6'], ['look' => '9']])
        ->and(data_get($sets, '0.trades'))->toBe($alone->trades)
        ->and($alone->trades)->toBeGreaterThan(0)
        ->and(data_get($sets, '1.trades'))->toBeGreaterThan(0)
        ->and(data_get($sets, '1.return'))->not->toBe(data_get($sets, '0.return'));
});

it('refuses a universe grid it cannot answer for', function (Closure $arrange, string $message): void {
    universeFixture();
    $arrange();

    [$exit, $output] = universeGrid();

    expect($exit)->toBe(1)->and($output)->toContain($message);
})->with([
    'a universe that is not built' => [
        fn () => DB::table('universe_members')->delete(),
        'The research universe is not built: run market:universe:build first.',
    ],
    'a member with minutes missing' => [
        fn () => DB::table('candles')->where('instrument_id', DB::table('instruments')->where('symbol', 'CCC/USDT:USDT')->value('id'))
            ->whereBetween('open_time', ['2026-10-02 03:00:00+00', '2026-10-02 03:02:00+00'])->delete(),
        'CCC/USDT:USDT: 3 minutes missing in 1 gaps, the first from 2026-10-02 03:00 to 2026-10-02 03:02. Backfill it before backtesting (market:backfill).',
    ],
]);

it('writes a gate report for a universe strategy over the months the universe is built for (D-92)', function (): void {
    universeFixture(days: 36, members: 3);
    $label = app(StrategyLibrary::class)->versions()->save(universeGridDocument(top: 3, picks: 1, timeframe: '1h'), 'operator')->version?->label()
        ?? throw new RuntimeException('The universe document does not compile.');

    $exit = Artisan::call('strategy:gates', ['strategy' => $label, '--from' => '2026-10-02', '--in' => '10d', '--out' => '5d', '--step' => '5d', '--min-trades' => '1']);
    $output = Artisan::output();
    $report = StrategyGateReport::query()->sole();

    expect($exit)->toBe(0)
        // To the end of what is stored of the members of the last month, which is before the end of that month.
        ->and($output)->toContain('Gates for xs-grid@v1: history 2026-10-02 to 2026-11-06, hold-out from 2026-10-30; 2 parameter sets walk forward')
        ->and(data_get($report->report, 'gates.*.gate'))->toBe(['trades', 'edge', 'profit_factor', 'walk_forward', 'deflated_sharpe', 'robustness', 'cost_stress', 'outliers', 'drawdown'])
        ->and($report->passed)->toBeFalse()
        // The regimes are those of the member ranked first when the history starts.
        ->and(data_get($report->report, 'regimes'))->toHaveKeys(['trend', 'range', 'high_volatility', 'unknown'])
        ->and(StrategyHoldout::query()->sole()->window_from->toDateString())->toBe('2026-10-30');
});

it('starts the history of a universe strategy where the universe starts, and refuses one too short (D-92)', function (): void {
    universeFixture(days: 3, members: 3);
    app(StrategyLibrary::class)->versions()->save(universeGridDocument(top: 3, picks: 1, timeframe: '1h'), 'operator');

    expect(Artisan::call('strategy:gates', ['strategy' => 'xs-grid@v1']))->toBe(1)
        ->and(Artisan::output())->toContain('The history from 2026-10-01 to 2026-10-04 is too short for gates: backfill more.');
});
