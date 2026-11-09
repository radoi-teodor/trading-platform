<?php

declare(strict_types=1);

use App\Strategy\Data\UniverseSchedule;
use Carbon\CarbonImmutable;

it('gives each member its runs of consecutive months (D-92)', function (): void {
    $schedule = new UniverseSchedule([
        '2025-01' => ['A', 'B'], '2025-02' => ['A'], '2025-04' => ['B', 'A'], '2025-12' => ['C'], '2026-01' => ['C'],
    ]);
    $runs = [];

    foreach ($schedule->runs() as $symbol => $own) {
        foreach ($own as [$first, $after]) {
            $runs[$symbol][] = "{$first->format('Y-m-d')} {$after->format('Y-m-d')}";
        }
    }

    expect($runs)->toBe([
        'A' => ['2025-01-01 2025-03-01', '2025-04-01 2025-05-01'],
        'B' => ['2025-01-01 2025-02-01', '2025-04-01 2025-05-01'],
        'C' => ['2025-12-01 2026-02-01'],
    ])
        ->and($schedule->isMember('C', CarbonImmutable::parse('2026-01-31 23:59', 'UTC')))->toBeTrue()
        ->and(UniverseSchedule::none()->runs())->toBe([]);
});
