<?php

declare(strict_types=1);

namespace App\Agents\Mcp\Tools;

use App\Agents\Mcp\TradingTool;
use App\Strategy\Dsl\Functions\FunctionSpec;
use App\Strategy\Dsl\Functions\Library;
use App\Strategy\Dsl\Functions\ParamSpec;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Attributes\Name;
use Laravel\Mcp\Server\Tools\Annotations\IsReadOnly;
use RuntimeException;

#[Name('dsl_reference')]
#[Description('The strategy DSL: the document format, the expression grammar and semantics, every function with its parameters and defaults, the error format, and a complete example. Read it before writing a strategy.')]
#[IsReadOnly]
final class DslReference extends TradingTool
{
    private const string EXAMPLE = 'catalog/strategies/ema-cross-scalp-long.strategy.yaml';

    public function schema(JsonSchema $schema): array
    {
        return [];
    }

    public function handle(Request $request): Response
    {
        $doc = self::read('docs/strategy/configurator.md');
        $sections = preg_split('/^(?=## \d+\. )/m', $doc) ?: [];
        $kept = array_filter($sections, fn (string $section): bool => preg_match('/^## [1237]\. /', $section) === 1);
        $functions = array_map(self::line(...), array_values(Library::all()));

        return Response::text(implode("\n", [
            ...array_map('trim', $kept),
            '',
            '## Functions available now, generated from the compiler',
            '',
            ...$functions,
            '',
            '## A complete document ('.self::EXAMPLE.')',
            '',
            '```yaml',
            rtrim(self::read(self::EXAMPLE)),
            '```',
        ]));
    }

    private static function line(FunctionSpec $spec): string
    {
        $params = array_map(fn (ParamSpec $p): string => $p->name.($p->default === null ? '' : " = {$p->default}").": {$p->kind->value}", $spec->params);
        $call = $spec->isCall ? $spec->name.'('.implode(', ', $params).')' : $spec->name;
        $result = $spec->fields === []
            ? ($spec->result === null ? 'measures what its src measures' : $spec->result->describe())
            : 'fields '.implode(', ', array_map(fn (string $field): string => ".{$field}", array_keys($spec->fields)));
        $values = $spec->values === [] ? '' : '; values '.implode(', ', $spec->values);
        $refused = $spec->refused === null ? '' : " NOT AVAILABLE: {$spec->refused}";

        return "- {$call} [{$spec->group->name}] {$result}{$values}{$refused}";
    }

    private static function read(string $path): string
    {
        $text = file_get_contents(base_path($path));

        return $text === false ? throw new RuntimeException("Cannot read {$path}.") : $text;
    }
}
