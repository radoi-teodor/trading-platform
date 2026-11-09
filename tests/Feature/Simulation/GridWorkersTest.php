<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\DatabaseTruncation;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

// Workers are separate processes: they see only committed rows, so this file commits its fixtures and truncates them.
uses(DatabaseTruncation::class);

// Leave nothing committed behind for the tests that run in a transaction after this file.
afterEach(function (): void {
    $tables = array_map(fn (array $table): string => '"'.$table['name'].'"', array_filter(Schema::getTables(), fn (array $table): bool => $table['schema'] === 'public' && $table['name'] !== 'migrations'));
    DB::statement('TRUNCATE '.implode(', ', $tables).' RESTART IDENTITY CASCADE');
});

it('gives the same sets split over workers as in one process', function (): void {
    backtestFixture(days: 36);
    $grid = fn (string $workers): array => (function () use ($workers): array {
        Artisan::call('backtest:grid', [
            '--file' => strategyFile(EMA_CROSS_DOCUMENT), '--from' => '2026-10-02', '--to' => '2026-11-05',
            '--grid' => ['fast=5,7,9', 'slow=15,21'], '--workers' => $workers, '--json' => true,
        ]);
        $lines = array_values(array_filter(explode("\n", trim(Artisan::output()))));
        $summary = json_decode((string) end($lines), true);

        return is_array($summary) && is_array($summary['sets'] ?? null) ? $summary['sets'] : [];
    })();

    $one = $grid('1');

    expect($one)->toHaveCount(6)
        ->and($grid('3'))->toBe($one)
        ->and(Artisan::call('backtest:grid', ['--file' => strategyFile(EMA_CROSS_DOCUMENT), '--from' => '2026-10-02', '--to' => '2026-10-03', '--workers' => '0']))->toBe(1)
        ->and(Artisan::output())->toContain('--workers is a whole number from 1 to 32.');
});

it('gives the same sets of a universe strategy split over workers, one member delisted during the run (D-92)', function (): void {
    universeFixture();
    $grid = fn (string $workers): array => (function () use ($workers): array {
        Artisan::call('backtest:grid', ['--file' => strategyFile(universeGridDocument()), '--from' => '2026-10-02', '--to' => '2026-10-03', '--workers' => $workers, '--json' => true]);
        $lines = array_values(array_filter(explode("\n", trim(Artisan::output()))));
        $summary = json_decode((string) end($lines), true);

        return is_array($summary) && is_array($summary['sets'] ?? null) ? $summary['sets'] : [];
    })();

    $one = $grid('1');

    expect($one)->toHaveCount(2)->and($grid('2'))->toBe($one);
});
