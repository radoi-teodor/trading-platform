<?php

declare(strict_types=1);

use App\Simulation\Models\StrategyGateReport;
use App\Simulation\Models\StrategyHoldout;
use App\Simulation\StrategyLibrary;
use App\Strategy\Versions\StrategyVersion;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;

uses(RefreshDatabase::class);

const GATES_WINDOW = ['--from' => '2026-10-02', '--to' => '2026-11-05', '--in' => '10d', '--out' => '5d', '--step' => '5d', '--min-trades' => '1'];

/** The test document with two values per parameter, saved as a version. */
function gatedVersion(): string
{
    $document = str_replace(['range: [5, 13], step: 2', 'range: [15, 30], step: 3'], ['range: [7, 9], step: 2', 'range: [18, 21], step: 3'], EMA_CROSS_DOCUMENT);
    $saved = app(StrategyLibrary::class)->versions()->save($document, 'operator');

    return $saved->version?->label() ?? throw new RuntimeException('The gated document does not compile.');
}

it('writes a gate report from the walk-forward, the hold-out used once, robustness, cost stress, Monte Carlo and regimes', function (): void {
    backtestFixture(days: 36);
    $label = gatedVersion();

    $exit = Artisan::call('strategy:gates', ['strategy' => $label, ...GATES_WINDOW]);
    $output = Artisan::output();
    $report = StrategyGateReport::query()->sole();

    expect($exit)->toBe(0)
        ->and($output)->toContain('4 parameter sets walk forward')
        ->and($output)->toContain('Hold-out 2026-10-29 to 2026-11-05, used once for this version')
        ->and($output)->toContain('Cost stress from 2026-10-12')
        ->and($output)->toContain('Monte Carlo, trades:')
        ->and(array_column($report->report['gates'] ?? [], 'gate'))->toBe(['trades', 'edge', 'profit_factor', 'walk_forward', 'deflated_sharpe', 'robustness', 'cost_stress', 'outliers', 'drawdown'])
        // a few dozen trades on 36 days cannot pass a 100-trade gate
        ->and($report->passed)->toBeFalse()
        ->and($report->report['monte_carlo']['seed'] ?? null)->toBe(2026)
        ->and(array_keys($report->report['regimes'] ?? []))->toEqualCanonicalizing(['trend', 'range', 'high_volatility', 'unknown'])
        ->and(StrategyHoldout::query()->sole()->window_from->toDateString())->toBe('2026-10-29');

    // The second report reads the recorded hold-out instead of looking at it again (D-58), and gives the same gates.
    Artisan::call('strategy:gates', ['strategy' => $label, ...GATES_WINDOW]);

    expect(Artisan::output())->toContain('its recorded result is read, not run again')
        ->and(StrategyHoldout::query()->count())->toBe(1)
        ->and(StrategyGateReport::query()->orderByDesc('id')->first()?->report['gates'])->toBe($report->report['gates']);
});

it('refuses benchmarks and versions it does not know', function (): void {
    backtestFixture(days: 36);
    app(StrategyLibrary::class)->versions()->save(str_replace("title: EMA cross on 5m\n", "title: EMA cross on 5m\nprofile: benchmark\n", str_replace('stop_loss: 1.5 * atr(14)', 'stop_loss: none', EMA_CROSS_DOCUMENT)), 'operator');

    expect(Artisan::call('strategy:gates', ['strategy' => 'ema-cross-test@v1', ...GATES_WINDOW]))->toBe(1)
        ->and(Artisan::output())->toContain('is a benchmark: it runs in backtests only and is never promoted')
        ->and(Artisan::call('strategy:gates', ['strategy' => 'nothing@v1']))->toBe(1)
        ->and(Artisan::output())->toContain('nothing@v1 is not a saved version');
});

it('promotes a draft to backtested only on a passing report, and never further', function (): void {
    backtestFixture(days: 36);
    $label = gatedVersion();
    $version = StrategyVersion::query()->sole();

    expect(Artisan::call('strategy:promote', ['strategy' => $label]))->toBe(1)
        ->and(Artisan::output())->toContain('has no gate report');

    StrategyGateReport::query()->create(['strategy_version_id' => $version->id, 'passed' => false, 'report' => ['gates' => [['gate' => 'trades', 'passed' => false], ['gate' => 'edge', 'passed' => true]]], 'created_at' => now()]);

    $exit = Artisan::call('strategy:promote', ['strategy' => $label]);

    expect($exit)->toBe(1)
        ->and(Artisan::output())->toMatch('/failed its last gate report \\(\\d+\\): trades\\./')
        ->and($version->stage())->toBe('draft');

    StrategyGateReport::query()->create(['strategy_version_id' => $version->id, 'passed' => true, 'report' => ['gates' => []], 'created_at' => now()]);

    expect(Artisan::call('strategy:promote', ['strategy' => $label]))->toBe(0)
        ->and($version->stage())->toBe('backtested')
        ->and(Artisan::call('strategy:promote', ['strategy' => $label]))->toBe(1)
        ->and(Artisan::output())->toContain('is backtested, not draft');
});
