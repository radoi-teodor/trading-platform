<?php

declare(strict_types=1);

namespace App\Agents\Mcp\Tools;

use App\Simulation\SimulationQueue;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Attributes\Name;

#[Name('run_grid')]
#[Description('Queues a grid of a saved version: every combination of the given parameter values, each backtested, with the deflated Sharpe ratio and the probability of backtest overfitting; optionally a walk-forward that picks the best set in-sample and judges it only on the next window. Answers with a job id; read the result with get_job.')]
final class RunGrid extends SimulationTool
{
    public function schema(JsonSchema $schema): array
    {
        return [
            ...$this->window($schema),
            'grid' => $schema->array()->items($schema->string())->description('Values per parameter, such as ["fast=5,7,9", "slow=21:34:4"] (list, or start:end:step); the parameter ranges otherwise'),
            'walk_forward' => $schema->object([
                'in' => $schema->string()->description('In-sample length, such as 6m or 10d'),
                'out' => $schema->string()->description('Out-of-sample length, such as 3m or 5d'),
                'step' => $schema->string()->description('Step between windows; the out-of-sample length otherwise'),
                'min_trades' => $schema->integer()->description('Trades a set needs in-sample to be chosen'),
            ])->description('Run a walk-forward on the grid (D-57)'),
        ];
    }

    public function handle(Request $request, SimulationQueue $queue): Response
    {
        $walk = $request->get('walk_forward');

        return $this->submit($queue, 'grid', [
            'strategy' => $request->get('version'),
            'from' => $request->get('from'),
            'to' => $request->get('to'),
            'grid' => self::strings($request->get('grid')),
            'walk_forward' => is_array($walk) ? array_filter($walk, fn (mixed $v, mixed $k): bool => is_scalar($v) && in_array($k, ['in', 'out', 'step', 'min_trades'], true), ARRAY_FILTER_USE_BOTH) : null,
        ]);
    }
}
