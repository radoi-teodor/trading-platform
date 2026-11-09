<?php

declare(strict_types=1);

use App\Agents\Mcp\Tools\ChartTool;
use App\Agents\Mcp\Tools\SimulationTool;
use App\Agents\Mcp\TradingTool;
use App\Http\Controllers\Controller;
use App\Market\Binance\BinanceRequestFailed;
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
    // The ignored classes are the ones built for extension.
    expect('App')->classes()->toBeFinal()->ignoring([Controller::class, BinanceRequestFailed::class, TradingTool::class, ChartTool::class, SimulationTool::class]);
});
