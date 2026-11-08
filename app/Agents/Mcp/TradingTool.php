<?php

declare(strict_types=1);

namespace App\Agents\Mcp;

use App\Agents\Models\McpToken;
use App\Simulation\Report\CanonicalJson;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Tool;
use LogicException;

/** What every Trading X tool shares: the calling token's author and JSON answers (D-63). */
abstract class TradingTool extends Tool
{
    /** agent:<token name>, recorded on what this call writes. */
    protected function author(): string
    {
        $token = request()->attributes->get('mcp_token');

        return $token instanceof McpToken ? $token->author() : throw new LogicException('An MCP tool runs only behind a token.');
    }

    /** @param array<array-key, mixed> $data */
    protected function json(array $data): Response
    {
        return Response::text(CanonicalJson::encode($data));
    }

    /** @param list<string>|string $lines */
    protected function refuse(array|string $lines): Response
    {
        return Response::error(is_array($lines) ? implode("\n", $lines) : $lines);
    }
}
