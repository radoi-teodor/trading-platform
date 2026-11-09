<?php

declare(strict_types=1);

use App\Agents\Mcp\AuthenticateMcpToken;
use App\Agents\Mcp\TradingServer;
use Laravel\Mcp\Facades\Mcp;

// The MCP endpoint (D-63): a live token first, then the per-token rate limit. Off unless MCP_ENABLED=true.
Mcp::web('/mcp', TradingServer::class)->middleware([AuthenticateMcpToken::class, 'throttle:mcp']);
