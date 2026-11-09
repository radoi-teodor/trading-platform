<?php

declare(strict_types=1);

namespace App\Agents\Mcp\Tools;

use App\Agents\Mcp\TradingTool;
use App\Simulation\Models\SimulationJob;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Attributes\Name;
use Laravel\Mcp\Server\Tools\Annotations\IsReadOnly;

#[Name('get_job')]
#[Description('A simulation run: queued, running, done or failed; when done, its summary (metrics, benchmarks, deflated Sharpe, PBO, walk-forward, report path); when failed, the error.')]
#[IsReadOnly]
final class GetJob extends TradingTool
{
    public function schema(JsonSchema $schema): array
    {
        return ['id' => $schema->integer()->description('The job id run_backtest or run_grid gave')->required()];
    }

    public function handle(Request $request): Response
    {
        $id = $request->get('id');
        $job = is_numeric($id) ? SimulationJob::query()->find((int) $id) : null;

        if ($job === null) {
            return $this->refuse('There is no job '.(is_scalar($id) ? (string) $id : '').'.');
        }

        return $this->json([
            'job' => $job->id,
            'kind' => $job->kind,
            'arguments' => $job->arguments,
            'status' => $job->status,
            'author' => $job->author,
            'created_at' => $job->created_at->toIso8601ZuluString(),
            'started_at' => $job->started_at?->toIso8601ZuluString(),
            'finished_at' => $job->finished_at?->toIso8601ZuluString(),
            'summary' => $job->summary,
            'error' => $job->error,
        ]);
    }
}
