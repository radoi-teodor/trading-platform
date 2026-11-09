<?php

declare(strict_types=1);

// Module dependencies from docs/architecture.md, section 3. Each module may use only the modules listed for it;
// everything else under App is forbidden. The rule that matters most is D-7: Agents never reach Execution.

const MODULES = [
    'Support', 'Market', 'Strategy', 'Risk', 'Portfolio',
    'Execution', 'Simulation', 'Agents', 'Journal', 'Notifications', 'Web',
];

const ALLOWED_DEPENDENCIES = [
    'Support' => [],
    'Market' => ['Support'],
    'Strategy' => ['Support', 'Market'],
    'Risk' => ['Support', 'Market', 'Strategy'],
    'Portfolio' => ['Support', 'Market'],
    'Execution' => ['Support', 'Market', 'Strategy', 'Risk', 'Portfolio'],
    'Simulation' => ['Support', 'Market', 'Strategy', 'Risk', 'Portfolio', 'Execution'],
    'Agents' => ['Support', 'Market', 'Strategy', 'Simulation', 'Portfolio', 'Journal'],
    'Journal' => ['Support'],
    'Notifications' => ['Support', 'Journal', 'Execution'],
];

foreach (ALLOWED_DEPENDENCIES as $module => $allowed) {
    $forbidden = array_map(
        static fn (string $other): string => "App\\{$other}",
        array_values(array_diff(MODULES, [$module], $allowed)),
    );

    arch("{$module} uses only the modules it is allowed to", function () use ($module, $forbidden): void {
        expect("App\\{$module}")->not->toUse($forbidden);
    });
}

arch('agents can never reach execution (D-7)', function (): void {
    expect('App\Agents')->not->toUse('App\Execution');
});
