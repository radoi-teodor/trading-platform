<?php

declare(strict_types=1);

namespace App\Agents\Mcp\Tools;

use App\Simulation\Charts\ChartSeries;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Attributes\Name;
use Laravel\Mcp\Server\Tools\Annotations\IsReadOnly;

#[Name('get_indicators')]
#[Description('Candles with DSL expressions evaluated on each one, with the formulas backtests use: series such as ema(9), rsi(7), bb(20, 2).upper, vwap(), close on 1h, or conditions such as ema(9) crosses_above ema(21). Values are null while an expression warms up.')]
#[IsReadOnly]
final class GetIndicators extends ChartTool
{
    public function schema(JsonSchema $schema): array
    {
        return [
            ...parent::schema($schema),
            'expressions' => $schema->array()->items($schema->string())->description('Up to 20 DSL expressions')->required(),
        ];
    }

    public function handle(Request $request, ChartSeries $chart): Response
    {
        $expressions = $request->get('expressions');

        return $this->chart($request, $chart, is_array($expressions) ? array_values(array_filter($expressions, 'is_string')) : []);
    }
}
