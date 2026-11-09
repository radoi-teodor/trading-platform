<?php

declare(strict_types=1);

namespace App\Agents\Mcp\Tools;

use App\Agents\Mcp\TradingTool;
use App\Simulation\StrategyLibrary;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Attributes\Name;

#[Name('save_strategy')]
#[Description('Saves a strategy document that compiles as a new draft version, <strategy>@v<n>, authored by this token. A document identical to the latest version is that version. Versions are never changed; a draft cannot trade until the operator promotes it.')]
final class SaveStrategy extends TradingTool
{
    public function schema(JsonSchema $schema): array
    {
        return ['document' => $schema->string()->description('The YAML strategy document')->required()];
    }

    public function handle(Request $request, StrategyLibrary $library): Response
    {
        $document = $request->get('document');
        $saved = $library->versions()->save(is_string($document) ? $document : '', $this->author());

        if ($saved->version === null) {
            return $this->refuse(['The document does not compile; nothing was saved.', ...CompileStrategy::report($saved->check, $library)['errors']]);
        }

        return $this->json([
            'version' => $saved->version->label(),
            'created' => $saved->created,
            'stage' => $saved->version->stage(),
            'warnings' => $saved->check->warnings,
        ]);
    }
}
