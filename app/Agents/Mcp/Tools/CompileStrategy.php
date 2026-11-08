<?php

declare(strict_types=1);

namespace App\Agents\Mcp\Tools;

use App\Agents\Mcp\TradingTool;
use App\Simulation\StrategyLibrary;
use App\Strategy\Dsl\Document\CheckResult;
use App\Strategy\Dsl\DslError;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Attributes\Name;
use Laravel\Mcp\Server\Tools\Annotations\IsReadOnly;

#[Name('compile_strategy')]
#[Description('Checks a strategy document against the DSL, the stored instruments and the account policy. Gives every error with its line and column, the warnings, and what it compiles to: instruments, timeframe, warmup and parameters. Saves nothing.')]
#[IsReadOnly]
final class CompileStrategy extends TradingTool
{
    public function schema(JsonSchema $schema): array
    {
        return ['document' => $schema->string()->description('The YAML strategy document')->required()];
    }

    public function handle(Request $request, StrategyLibrary $library): Response
    {
        $document = $request->get('document');
        $result = $library->check(is_string($document) ? $document : '');

        return $this->json(['compiles' => $result->spec !== null, ...self::report($result, $library)]);
    }

    /** @return array{errors: list<string>, warnings: list<string>, compiled: array<string, mixed>|null} */
    public static function report(CheckResult $result, StrategyLibrary $library): array
    {
        return [
            'errors' => array_map(fn (DslError $error): string => $error->getMessage(), $result->errors),
            'warnings' => $result->warnings,
            'compiled' => $library->describe($result),
        ];
    }
}
