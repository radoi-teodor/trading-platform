<?php

declare(strict_types=1);

namespace App\Agents\Mcp\Tools;

use App\Agents\Mcp\TradingTool;
use App\Simulation\Charts\ChartSeries;
use App\Strategy\Dsl\DslError;
use App\Support\Symbol;
use App\Support\Timeframe;
use Carbon\CarbonImmutable;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use InvalidArgumentException;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Throwable;

/** Candles and DSL expressions over a window, through the chart service the UI will use. */
abstract class ChartTool extends TradingTool
{
    public function schema(JsonSchema $schema): array
    {
        return [
            'symbol' => $schema->string()->description('Unified symbol: BTC/USDT for spot, BTC/USDT:USDT for the perpetual')->required(),
            'timeframe' => $schema->string()->description('1m, 5m, 15m, 1h, 4h, 1d or 1w')->required(),
            'from' => $schema->string()->description('Start, UTC, as 2026-09-01T00:00:00Z')->required(),
            'to' => $schema->string()->description('End, UTC, exclusive; at most 1000 candles after from')->required(),
        ];
    }

    /** @param list<string> $expressions */
    protected function chart(Request $request, ChartSeries $chart, array $expressions): Response
    {
        try {
            $timeframe = Timeframe::tryFrom(is_string($request->get('timeframe')) ? $request->get('timeframe') : '')
                ?? throw new InvalidArgumentException('timeframe must be one of 1m, 5m, 15m, 1h, 4h, 1d, 1w.');
            $result = $chart->compute(
                Symbol::parse(is_string($request->get('symbol')) ? $request->get('symbol') : ''),
                $timeframe,
                self::time($request->get('from')),
                self::time($request->get('to')),
                $expressions,
            );
        } catch (DslError|InvalidArgumentException $failure) {
            return $this->refuse($failure->getMessage());
        }

        return $this->json($result);
    }

    private static function time(mixed $value): CarbonImmutable
    {
        try {
            return CarbonImmutable::parse(is_string($value) ? $value : '', 'UTC')->utc();
        } catch (Throwable) {
            throw new InvalidArgumentException('from and to are times in UTC, such as 2026-09-01T00:00:00Z.');
        }
    }
}
