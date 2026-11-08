<?php

declare(strict_types=1);

namespace App\Agents\Mcp\Tools;

use App\Simulation\Charts\ChartSeries;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Attributes\Name;
use Laravel\Mcp\Server\Tools\Annotations\IsReadOnly;

#[Name('get_candles')]
#[Description('Candles of a timeframe built from the stored 1-minute candles, at most 1000, oldest first.')]
#[IsReadOnly]
final class GetCandles extends ChartTool
{
    public function handle(Request $request, ChartSeries $chart): Response
    {
        return $this->chart($request, $chart, []);
    }
}
