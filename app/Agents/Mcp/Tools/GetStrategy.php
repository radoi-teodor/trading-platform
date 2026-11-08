<?php

declare(strict_types=1);

namespace App\Agents\Mcp\Tools;

use App\Agents\Mcp\TradingTool;
use App\Simulation\StrategyLibrary;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use InvalidArgumentException;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Attributes\Name;
use Laravel\Mcp\Server\Tools\Annotations\IsReadOnly;

#[Name('get_strategy')]
#[Description('One saved version: its YAML document, its read-back in plain sentences, author and stage.')]
#[IsReadOnly]
final class GetStrategy extends TradingTool
{
    public function schema(JsonSchema $schema): array
    {
        return ['version' => $schema->string()->description('A saved version, such as ema-cross-scalp-long@v1')->required()];
    }

    public function handle(Request $request, StrategyLibrary $library): Response
    {
        $label = $request->get('version');

        try {
            $version = $library->versions()->find(is_string($label) ? $label : '');
        } catch (InvalidArgumentException $failure) {
            return $this->refuse($failure->getMessage());
        }

        if ($version === null) {
            return $this->refuse((is_string($label) ? $label : 'That version').' is not saved; list_strategies shows the saved ones.');
        }

        return $this->json([
            'version' => $version->label(),
            'author' => $version->author,
            'stage' => $version->stage(),
            'created_at' => $version->created_at->toIso8601ZuluString(),
            'sha256' => $version->sha256,
            'document' => $version->document,
            'read_back' => $library->describe($library->check($version->document))['read_back'] ?? null,
        ]);
    }
}
