<?php

declare(strict_types=1);

namespace App\Agents\Mcp\Tools;

use App\Agents\Mcp\TradingTool;
use App\Simulation\StrategyLibrary;
use App\Strategy\Versions\StrategyVersion;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Attributes\Name;
use Laravel\Mcp\Server\Tools\Annotations\IsReadOnly;

#[Name('list_strategies')]
#[Description('Every saved strategy version: its name (<strategy>@v<n>), author, stage and when it was saved.')]
#[IsReadOnly]
final class ListStrategies extends TradingTool
{
    public function schema(JsonSchema $schema): array
    {
        return [];
    }

    public function handle(Request $request, StrategyLibrary $library): Response
    {
        return $this->json(['versions' => $library->versions()->all()->map(fn (StrategyVersion $version): array => [
            'version' => $version->label(),
            'author' => $version->author,
            'stage' => $version->stage(),
            'created_at' => $version->created_at->toIso8601ZuluString(),
        ])->values()->all()]);
    }
}
