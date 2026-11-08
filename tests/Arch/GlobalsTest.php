<?php

declare(strict_types=1);

use App\Http\Controllers\Controller;
use Pest\Preset;

arch('php preset', function (): void {
    (new Preset)->php();
});

// No eval, exec, shell_exec, unserialize, md5 and the like (legacy lesson S2: command injection).
arch('security preset', function (): void {
    (new Preset)->security();
});

// One subject per expectation: see the note in DomainPurityTest.php.
foreach (['dd', 'dump', 'ray', 'var_dump', 'print_r', 'die', 'exit'] as $debugFunction) {
    arch("no {$debugFunction} left in the code", function () use ($debugFunction): void {
        expect($debugFunction)->not->toBeUsed();
    });
}

arch('every file declares strict types', function (): void {
    expect(['App', 'Database', 'Tests'])->toUseStrictTypes();
});

arch('application classes are final unless built for extension', function (): void {
    expect('App')->classes()->toBeFinal()->ignoring(Controller::class);
});
