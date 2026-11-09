<?php

declare(strict_types=1);

namespace App\Agents\Mcp\Tools;

use App\Simulation\SimulationQueue;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Attributes\Name;

#[Name('run_backtest')]
#[Description('Queues a backtest of a saved version over stored history, with costs and funding, against the benchmarks. Answers with a job id; read the result with get_job. Each run is recorded as a trial of the family (for the deflated Sharpe ratio). At most 2 runs of this token wait at a time.')]
final class RunBacktest extends SimulationTool
{
    public function schema(JsonSchema $schema): array
    {
        return [
            ...$this->window($schema),
            'params' => $schema->object()->description('Parameter values by name, such as {"fast": "9"}; the defaults otherwise'),
        ];
    }

    public function handle(Request $request, SimulationQueue $queue): Response
    {
        $params = $request->get('params');

        return $this->submit($queue, 'backtest', [
            'strategy' => $request->get('version'),
            'from' => $request->get('from'),
            'to' => $request->get('to'),
            'params' => is_array($params) && $params !== [] ? array_map(fn (mixed $v): string => is_scalar($v) ? (string) $v : '', $params) : null,
        ]);
    }
}
