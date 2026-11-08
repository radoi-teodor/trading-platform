<?php

declare(strict_types=1);

namespace App\Agents\Mcp\Tools;

use App\Agents\Mcp\TradingTool;
use App\Simulation\Models\StrategyTrial;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Attributes\Name;
use Laravel\Mcp\Server\Tools\Annotations\IsReadOnly;

#[Name('list_trials')]
#[Description('Every backtest recorded for a strategy family (D-58): parameters, window and daily Sharpe ratio. The deflated Sharpe ratio counts all of them, so trying many variants raises the bar each one must clear.')]
#[IsReadOnly]
final class ListTrials extends TradingTool
{
    private const int LIMIT = 500;

    public function schema(JsonSchema $schema): array
    {
        return ['family' => $schema->string()->description('The strategy id, such as ema-cross-scalp-long')->required()];
    }

    public function handle(Request $request): Response
    {
        $family = $request->get('family');
        $query = StrategyTrial::query()->where('family', is_string($family) ? $family : '');
        $trials = (clone $query)->orderByDesc('id')->limit(self::LIMIT)->get();

        return $this->json([
            'family' => $family,
            'count' => $query->count(),
            'shown' => $trials->count(),
            'trials' => $trials->map(fn (StrategyTrial $trial): array => [
                'strategy' => $trial->strategy,
                'parameters' => $trial->parameters,
                'from' => $trial->window_from->toDateString(),
                'to' => $trial->window_to->toDateString(),
                'daily_sharpe' => $trial->daily_sharpe,
                'observations' => $trial->observations,
            ])->values()->all(),
        ]);
    }
}
