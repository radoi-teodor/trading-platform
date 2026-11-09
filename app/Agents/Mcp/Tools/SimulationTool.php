<?php

declare(strict_types=1);

namespace App\Agents\Mcp\Tools;

use App\Agents\Mcp\TradingTool;
use App\Simulation\SimulationQueue;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use InvalidArgumentException;
use Laravel\Mcp\Response;

/** Queues a simulation run on a saved version, in the background (D-62), and answers with the job to poll. */
abstract class SimulationTool extends TradingTool
{
    /** @return array<string, mixed> */
    protected function window(JsonSchema $schema): array
    {
        return [
            'version' => $schema->string()->description('A saved version, such as ema-cross-scalp-long@v1')->required(),
            'from' => $schema->string()->description('First day, UTC, YYYY-MM-DD')->required(),
            'to' => $schema->string()->description('Day after the last, UTC, YYYY-MM-DD')->required(),
        ];
    }

    /** @param array<string, mixed> $arguments */
    protected function submit(SimulationQueue $queue, string $kind, array $arguments): Response
    {
        try {
            $job = $queue->submit($kind, array_filter($arguments, fn (mixed $value): bool => $value !== null), $this->author());
        } catch (InvalidArgumentException $failure) {
            return $this->refuse($failure->getMessage());
        }

        $job->refresh();

        return $this->json([
            'job' => $job->id,
            'status' => $job->status,
            'next' => $job->status === 'queued' || $job->status === 'running' ? 'Poll get_job with this id; a grid can take several minutes.' : 'Read the result with get_job.',
        ]);
    }

    /** @return list<string>|null */
    protected static function strings(mixed $value): ?array
    {
        return is_array($value) ? array_values(array_filter($value, 'is_string')) : null;
    }
}
