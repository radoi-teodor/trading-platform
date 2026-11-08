<?php

declare(strict_types=1);

// Domain modules must run inside a backtest without the framework booted and must be deterministic
// (docs/conventions.md, sections 2 and 3; D-8, D-12).
//
// Each expectation has ONE subject. Pest's negated arch expectations with an array subject
// (expect([a, b])->not->toUse(...)) pass when only some subjects violate the rule
// (Known traps in .claude/skills/bug-fix/SKILL.md).

const DOMAIN_MODULES = [
    'App\Support', 'App\Strategy', 'App\Risk', 'App\Portfolio', 'App\Execution', 'App\Simulation',
];

const MONEY_MODULES = ['App\Support', 'App\Risk', 'App\Portfolio', 'App\Execution'];

const CLOCK_SYMBOLS = ['now', 'today', 'time', 'date', 'microtime', 'hrtime', 'Carbon\Carbon', 'Illuminate\Support\Carbon'];

const RANDOM_SYMBOLS = ['rand', 'mt_rand', 'random_int', 'random_bytes', 'lcg_value', 'mt_srand', 'srand'];

const FRAMEWORK_SYMBOLS = ['app', 'resolve', 'config', 'env', 'cache', 'logger', 'request', 'session', 'Illuminate\Support\Facades'];

foreach (DOMAIN_MODULES as $module) {
    arch("{$module} reads time only from the injected Clock", function () use ($module): void {
        expect($module)->not->toUse(CLOCK_SYMBOLS);
    });

    arch("{$module} uses only the injected, seeded Randomizer", function () use ($module): void {
        expect($module)->not->toUse(RANDOM_SYMBOLS);
    });

    arch("{$module} does not reach into the framework container or facades", function () use ($module): void {
        expect($module)->not->toUse(FRAMEWORK_SYMBOLS);
    });
}

foreach (MONEY_MODULES as $module) {
    arch("{$module} never converts amounts to float", function () use ($module): void {
        expect($module)->not->toUse(['floatval']);
    });
}
